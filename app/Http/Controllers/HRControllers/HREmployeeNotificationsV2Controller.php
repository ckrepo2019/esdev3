<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use \Carbon\Carbon;
use Carbon\CarbonPeriod;
use Crypt;
use File;
use DateTime;
use DateInterval;
use DatePeriod;
use App\Models\HR\HRDeductions;
use App\Models\HR\HREmployeeAttendance;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use App\Mail\HRMail;
use Mail;
use App\Http\Controllers\NotificationController\NotificationController;


class HREmployeeNotificationsV2Controller extends Controller
{
    public function index(Request $request)
    {
        $sy = DB::table('sy')
            ->where('isactive', 1)
            ->first();

        $semester = DB::table('semester')
            ->where('isactive', 1)
            ->first();

        $departments = DB::table('hr_departments')
            ->select('department as text', 'id')
            ->where('deleted', '0')
            ->orderBy('department', 'ASC')
            ->get();

        $employees = DB::table('teacher')
            ->select('teacher.id', 'lastname', 'firstname', 'middlename', 'suffix', 'amount as salaryamount', 'utype as designation')
            ->leftJoin('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            // ->where('employee_basicsalaryinfo.deleted','0')
            ->where('teacher.deleted', '0')
            ->where('teacher.isactive', '1')
            ->orderBy('lastname', 'asc')
            ->get();



        $id = $request->get('id');

        return view('hr.employees.notifications.index')
            ->with('id', $id ?? 0)
            ->with('sy', $sy)
            ->with('employees', $employees)
            ->with('departments', $departments)
            ->with('semester', $semester);
    }

    public function markasreadByUser($id)
    {
        $createdby = $id;
        $recipient = DB::table('teacher')->where('userid', auth()->user()->id)->value('id');

        DB::table('hr_notifications')
            ->where('createdby', $createdby)
            ->where('recipientid', $recipient)
            ->update([
                'seen' => 1
            ]);

       $items = DB::table('hr_notifications_replies')
            ->where('hr_notifications_replies.createdby', $createdby)
            ->where('hr_notifications.createdby', $recipient)
            ->join('hr_notifications', 'hr_notifications_replies.hr_notfication_id', '=', 'hr_notifications.id')
            ->select('hr_notifications_replies.*', 'hr_notifications.recipientid')
            ->update([
                'hr_notifications_replies.seen' => 1
            ]);


        return response()->json([
            'status' => 'success',
            'message' => 'Notification marked as read'
        ]);
    }

    public function valid_employees(Request $request)
    {

        $search = $request->get('search');
        $id = $request->get('id');
        $createdby = DB::table('teacher')->where('userid', auth()->user()->id)->value('id');

        $employees = DB::table('teacher')
            ->select(
                'teacher.id',
                'teacher.picurl',
                DB::raw('CONCAT_WS(" ", COALESCE(firstname, ""), COALESCE(middlename, ""), COALESCE(lastname, "")) AS full_name'),
                'suffix',
                'amount as salaryamount',
                'utype as designation',
                'employee_basicsalaryinfo.salarybasistype',
                'employee_basicsalaryinfo.clsubjperhour',
                'employee_personalinfo.date_joined',
                'employee_personalinfo.yos',
                DB::raw('CONCAT_WS(" ", COALESCE(firstname, ""), COALESCE(middlename, ""), COALESCE(lastname, "")) AS text'),

            )
            ->when($search != '', function ($query) use ($search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('teacher.firstname', 'like', '%' . $search . '%')
                        ->orWhere('teacher.middlename', 'like', '%' . $search . '%')
                        ->orWhere('teacher.lastname', 'like', '%' . $search . '%');
                });
            })
            ->when($id != '', function ($query) use ($id) {
                return $query->where(function ($query) use ($id) {
                    $query->where('teacher.id', $id);
                });
            })

            ->leftJoin('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
            ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->where('teacher.deleted', '0')
            ->where('teacher.id', '!=', $createdby)
            ->where('teacher.isactive', '1')
            ->orderBy('lastname', 'asc')
            ->groupBy('teacher.id')
            ->get();

        foreach ($employees as $employee) {
            $totalRep = 0;

            // Fetch messages created today for the employee
            $messages = DB::table('hr_notifications')
                ->where('hr_notifications.createdby', $employee->id)
                ->where('hr_notifications.deleted', 0)
                ->where('hr_notifications.seen', 0)
                ->where('hr_notifications.recipientid', $createdby)
                // ->whereDate('hr_notifications.createddatetime', Carbon::today()) // Filter messages created today
                ->select('hr_notifications.*')
                ->groupBy('hr_notifications.id') // Group by notification ID
                ->get();

            if ($messages->count() > 0) {
                foreach ($messages as $msg) {
                    $repliesCount = DB::table('hr_notifications_replies')
                        ->where('seen', 0)
                        ->where('hr_notifications_replies.hr_notfication_id', $msg->id) // Fixed typo in column name
                        ->where('hr_notifications_replies.deleted', 0)
                        // ->whereDate('hr_notifications_replies.createddatetime', Carbon::today()) // Filter replies created today
                        ->count(); // Get the count of replies

                    $totalRep += $repliesCount;
                }

                // Assign the total count of current messages and replies to employee
                $employee->totalCurrentMsg = $messages->count() + $totalRep;
            } else {
                $empId = $employee->id;
                $repliesCount = DB::table('hr_notifications_replies')
                ->where('hr_notifications_replies.seen', '0') // Use string if 'seen' is a string
                ->where('hr_notifications_replies.deleted', '0') // Use string if 'deleted' is a string
                ->where( 'hr_notifications_replies.employee_id', (string)$empId) // Cast empId to string
                ->where( 'hr_notifications.createdby', $createdby) // Cast empId to string
                ->join('hr_notifications', 'hr_notifications_replies.hr_notfication_id', '=', 'hr_notifications.id')
                ->select('hr_notifications_replies.*')
                ->get();
            

                $totalRep += $repliesCount->count();

                // Assign the total count of current messages and replies to employee
                $employee->totalCurrentMsg = $totalRep;
                $employee->msg = $repliesCount;
            }
        }

        if ($id != '' && $id != 0) {

            foreach ($employees as $employee) {
                $message = DB::table('hr_notifications')
                    ->where('createdby', $employee->id)
                    ->where('deleted', 0)
                    ->get();

                $employee->concernCount = $message->where('messagettype', 'concern')->count();
                $employee->messageCount = $message->where('messagettype', 'message')->count();
                $employee->requestCount = $message->where('messagettype', 'request')->count();


            }

        }

        return $employees;
    }

    public function sendnotificationv2(Request $request)
    {
        date_default_timezone_set('Asia/Manila');

        $createdby = DB::table('teacher')->where('userid', auth()->user()->id)->value('id');


        $messageType = $request->input('messageType');
        $recipientid = $request->input('recipientid'); // this might be a comma-separated string

        $subject = $request->input('subject');
        $message = $request->input('additionalmessage');

        if ($request->has('sendByDepartment')) {
            return $this->sendNotificationV2WithDepartment($request);
        }

        if ($recipientid == 'All') {
            return $this->sendNotificationV2All($request);
        }

        $email = DB::table('employee_personalinfo')
            ->where('employeeid', $recipientid)
            ->value('email');


        // Convert recipient IDs to a comma-separated string


        try {



            $details = [
                'subject' => $subject,
                'content' => $message
            ];


            $hr_notification_id = DB::table('hr_notifications')
                ->insertGetID([
                    'messagettype' => $messageType,
                    'recipientid' => $recipientid,
                    'subject' => $subject,
                    'additionalmessage' => $message,
                    'createdby' => $createdby,
                    'createddatetime' => date('Y-m-d H:i:s')
                ]);

            $imageArray = json_decode($request->get('imageArray'));


            foreach ($imageArray as $image) {


                DB::table('hr_notification_attachment')
                    ->insert([
                        'hr_notification_id' => $hr_notification_id,
                        'fileurl' => $image->value,
                    ]);

            }

            //Mail::to($email)->send(new HRMail($details));

            $sender = DB::table('teacher')->where('userid', auth()->user()->id)->first();
            $receiver = DB::table('teacher')->where('id', $recipientid)->value('userid');

            $notificationData = [
                'message' => 'You have a new message from ' . $sender->firstname . ' ' . $sender->lastname,
                'receiver_id' => $receiver,
                'purpose' => 'New Message',
                'status' => ''
            ];

            // Attempt to send notification
            try {
                NotificationController::sendNotification($notificationData['purpose'], $notificationData['message'], $notificationData['receiver_id'], 'message', $notificationData['status'], 'hr/settings/notification/index');
            } catch (\Exception $e) {
                \Log::error('Notification sending failed:', ['error' => $e->getMessage()]);
                // Continue execution even if notification fails
            }

            return array(
                (object) [
                    'status' => 1,
                    'message' => 'Notification Sent!',
                ]
            );

        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to send notification: ' . $e->getMessage(),
            ], 500);
        }



    }

    public function sendNotificationV2WithDepartment(Request $request)
    {
        $createdby = DB::table('teacher')->where('userid', auth()->user()->id)->value('id');
        $messageType = $request->input('messageType');
        $recipientid = $request->input('recipientid');
        $subject = $request->input('subject');
        $message = $request->input('additionalmessage');

        $emails = DB::table('employee_personalinfo')
            ->where('departmentid', $recipientid)
            ->where('deleted', 0)
            ->select('email', 'employeeid')
            ->get();

        $sent = 0;

        foreach ($emails as $email) {

            try {



                $details = [
                    'subject' => $subject,
                    'content' => $message
                ];


                $hr_notification_id = DB::table('hr_notifications')
                    ->insertGetID([
                        'messagettype' => $messageType,
                        'recipientid' => $email->employeeid,
                        'subject' => $subject,
                        'additionalmessage' => $message,
                        'createdby' => $createdby,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);

                $imageArray = json_decode($request->get('imageArray'));


                foreach ($imageArray as $image) {


                    DB::table('hr_notification_attachment')
                        ->insert([
                            'hr_notification_id' => $hr_notification_id,
                            'fileurl' => $image->value,
                        ]);

                }

                $sent++;



                //Mail::to($email)->send(new HRMail($details));

            } catch (\Exception $e) {


            }
        }

        return array(
            (object) [
                'status' => 1,
                'message' => $sent . ' Notification Sent!',
            ]
        );

    }


    public function sendNotificationV2All(Request $request)
    {
        $createdby = DB::table('teacher')->where('userid', auth()->user()->id)->value('id');
        $messageType = $request->input('messageType');
        $recipientid = $request->input('recipientid');
        $subject = $request->input('subject');
        $message = $request->input('additionalmessage');



        $employees = DB::table('teacher')
            ->select('teacher.id', 'employee_personalinfo.email')
            ->leftJoin('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
            ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
            ->leftJoin('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->where('teacher.deleted', '0')
            ->where('teacher.id', '!=', $createdby)
            ->where('teacher.isactive', '1')
            ->orderBy('lastname', 'asc')
            ->get();

        $sent = 0;

        foreach ($employees as $employee) {

            try {



                $details = [
                    'subject' => $subject,
                    'content' => $message
                ];


                $hr_notification_id = DB::table('hr_notifications')
                    ->insertGetID([
                        'messagettype' => $messageType,
                        'recipientid' => $employee->id,
                        'subject' => $subject,
                        'additionalmessage' => $message,
                        'createdby' => $createdby,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);


                $imageArray = json_decode($request->get('imageArray'));


                foreach ($imageArray as $image) {


                    DB::table('hr_notification_attachment')
                        ->insert([
                            'hr_notification_id' => $hr_notification_id,
                            'fileurl' => $image->value,
                        ]);

                }



                $sent++;

                if (isset($employee->email)) {
                    //Mail::to($email)->send(new HRMail($details));
                }


            } catch (\Exception $e) {


            }
        }

        return array(
            (object) [
                'status' => 1,
                'message' => $sent . ' Notification Sent!',
            ]
        );

    }


    public function getMessages(Request $request)
    {
        $recipientid = $request->input('id');
        $createdby = DB::table('teacher')->where('userid', auth()->user()->id)->value('id');



        $messages1 = DB::table('hr_notifications')
            ->where('recipientid', $recipientid)
            ->where('hr_notifications.createdby', $createdby)
            ->where('hr_notifications.deleted', 0)
            ->join('teacher as createdbyTeacher', function ($join) use ($recipientid) {
                $join->on('createdbyTeacher.id', '=', 'hr_notifications.createdby');
                $join->where('createdbyTeacher.deleted', 0);
            })
            ->join('teacher as recipientTeacher', function ($join) use ($recipientid) {
                $join->on('recipientTeacher.id', '=', 'hr_notifications.recipientid');
                $join->where('recipientTeacher.deleted', 0);
            })
            ->select(
                'createdbyTeacher.picurl',
                DB::raw('CONCAT_WS(" ", COALESCE(createdbyTeacher.firstname, ""), COALESCE(createdbyTeacher.middlename, ""), COALESCE(createdbyTeacher.lastname, "")) AS full_name'),
                DB::raw('CONCAT_WS(" ", COALESCE(recipientTeacher.firstname, ""), COALESCE(recipientTeacher.middlename, ""), COALESCE(recipientTeacher.lastname, "")) AS recepientfull_name'),
                'hr_notifications.*'
            )
            ->orderBy('hr_notifications.createddatetime', 'desc')
            ->get();



        $messages2 = DB::table('hr_notifications')
            ->where('recipientid', $createdby)
            ->where('hr_notifications.createdby', $recipientid)
            ->where('hr_notifications.deleted', 0)
            ->join('teacher as createdbyTeacher', function ($join) use ($recipientid) {
                $join->on('createdbyTeacher.id', '=', 'hr_notifications.createdby');
                $join->where('createdbyTeacher.deleted', 0);
            })
            ->join('teacher as recipientTeacher', function ($join) use ($recipientid) {
                $join->on('recipientTeacher.id', '=', 'hr_notifications.recipientid');
                $join->where('recipientTeacher.deleted', 0);
            })
            ->select(
                'createdbyTeacher.picurl',
                DB::raw('CONCAT_WS(" ", COALESCE(createdbyTeacher.firstname, ""), COALESCE(createdbyTeacher.middlename, ""), COALESCE(createdbyTeacher.lastname, "")) AS full_name'),
                DB::raw('CONCAT_WS(" ", COALESCE(recipientTeacher.firstname, ""), COALESCE(recipientTeacher.middlename, ""), COALESCE(recipientTeacher.lastname, "")) AS recepientfull_name'),
                'hr_notifications.*'
            )
            ->orderBy('hr_notifications.createddatetime', 'desc')
            ->get();


        $messages = $messages1->merge($messages2);




        foreach ($messages as $message) {

            $message->time_ago = Carbon::parse($message->createddatetime)->diffForHumans();
            $message->date_created = Carbon::parse($message->createddatetime)->format('F j, Y');

            $message->img = DB::table('hr_notification_attachment')
                ->where('hr_notification_id', $message->id)
                ->where('deleted', 0)
                ->get();

            $message->replies = DB::table('hr_notifications_replies')
                ->where('hr_notfication_id', $message->id)
                ->where('hr_notifications_replies.deleted', 0)
                ->join('teacher', function ($join) {
                    $join->on('teacher.id', '=', 'hr_notifications_replies.createdby');
                    $join->where('teacher.deleted', 0);
                })
                ->select(
                    'teacher.picurl',
                    DB::raw('CONCAT_WS(" ", COALESCE(teacher.firstname, ""), COALESCE(teacher.middlename, ""), COALESCE(teacher.lastname, "")) AS full_name'),
                    'hr_notifications_replies.id',
                    'hr_notifications_replies.message',
                    'hr_notifications_replies.createddatetime',
                    // DB::raw('DATE_FORMAT(hr_notifications_replies.createddatetime, "%M %e, %Y at %l:%i %p") as formatted_date')
                )
                ->orderBy('hr_notifications_replies.createddatetime', 'desc')
                ->get();
        }
        
        
        $this->markasreadByUser($recipientid);

        return $messages;


    }

    public function sendReply(Request $request)
    {

        date_default_timezone_set('Asia/Manila');

        $createdby = DB::table('teacher')->where('userid', auth()->user()->id)->value('id');

        DB::table('hr_notifications_replies')
            ->insert([
                'hr_notfication_id' => $request->input('messageid'),
                'message' => $request->input('message'),
                'employee_id' => $createdby,
                'createdby' => $createdby,
                'createddatetime' => date('Y-m-d H:i:s')
            ]);

        return array(
            (object) [
                'status' => 1,
                'message' => 'Reply Sent!',
            ]
        );

    }

    public function getReply(Request $request)
    {
        $messageid = $request->input('id');


        $replies = DB::table('hr_notifications_replies')
            ->where('hr_notfication_id', $messageid)
            ->where('hr_notifications_replies.deleted', 0)
            ->join('teacher', function ($join) {
                $join->on('teacher.id', '=', 'hr_notifications_replies.createdby');
                $join->where('teacher.deleted', 0);
            })
            ->where('teacher.deleted', 0)
            ->select(
                'teacher.picurl',
                DB::raw('CONCAT_WS(" ", COALESCE(teacher.firstname, ""), COALESCE(teacher.middlename, ""), COALESCE(teacher.lastname, "")) AS full_name'),
                'hr_notifications_replies.id',
                'hr_notifications_replies.message',
                'hr_notifications_replies.createddatetime'

            )
            ->orderBy('hr_notifications_replies.createddatetime', 'desc')
            ->get();


        return $replies;
    }

    public function markAsDisplayed(Request $request)
    {
        $recipientid = DB::table('teacher')->where('userid', auth()->user()->id)->value('id');
        DB::table('hr_notifications')
            ->where('recipientid', $recipientid)
            ->update([
                'displayed' => 1
            ]);

        DB::table('hr_notifications_replies')
            ->join('hr_notifications', function ($join) {
                $join->on('hr_notifications.id', '=', 'hr_notifications_replies.hr_notfication_id');
            })
            ->where('recipientid', $recipientid)
            ->update([
                'hr_notifications_replies.displayed' => 1
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Notification marked as displayed'
        ], 200);
    }

    public function markAsRead(Request $request)
    {
        if (isset($request->type) && $request->type == 'reply') {
            DB::table('hr_notifications_replies')
                ->where('id', $request->get('id'))
                ->update([
                    'seen' => 1,
                ]);
            return array(
                (object) [
                    'status' => 1,
                    'message' => 'Message Marked as Read!',
                ]
            );
        }

        DB::table('hr_notifications')
            ->where('id', $request->get('id'))
            ->update([
                'seen' => 1,
            ]);

        return array(
            (object) [
                'status' => 1,
                'message' => 'Message Marked as Read!',
            ]
        );
    }

    public function getAllMessages(Request $request)
    {
        $recipientid = DB::table('teacher')->where('userid', auth()->user()->id)->value('id');
        $header = $request->get('header');
        $header2 = $request->get('header2');
        // dd($header2);
        $messages = DB::table('hr_notifications')
            ->where('hr_notifications.recipientid', $recipientid)
            ->where('hr_notifications.deleted', 0)
            // ->when(isset($request->purpose) && $request->purpose == 'notification', function ($query) {
            //     $query->where('hr_notifications.seen', 0);
            // })
            // ->where('hr_notifications.displayed', 0)
            ->join('teacher as createdbyTeacher', function ($join) use ($recipientid) {
                $join->on('createdbyTeacher.id', '=', 'hr_notifications.createdby');
                $join->where('createdbyTeacher.deleted', 0);
            })
            ->when($header, function ($query) use ($header) {
                return $query->where(function ($query) use ($header) {
                    $query->take(5);
                });
            })
            ->unless($header || $header2, function ($query) use ($recipientid) {
                return $query->orWhere('hr_notifications.createdby', $recipientid);
            })
            ->join('teacher as recipientTeacher', function ($join) use ($recipientid) {
                $join->on('recipientTeacher.id', '=', 'hr_notifications.recipientid');
                $join->where('recipientTeacher.deleted', 0);
            })
            ->select(
                'createdbyTeacher.picurl',
                'createdbyTeacher.id as createdbyid',
                'recipientTeacher.id as reciepientid',
                DB::raw('CONCAT_WS(" ", COALESCE(createdbyTeacher.firstname, ""), COALESCE(createdbyTeacher.middlename, ""), COALESCE(createdbyTeacher.lastname, "")) AS full_name'),
                DB::raw('CONCAT_WS(" ", COALESCE(recipientTeacher.firstname, ""), COALESCE(recipientTeacher.middlename, ""), COALESCE(recipientTeacher.lastname, "")) AS recepientfull_name'),
                'hr_notifications.*'
            )
            ->orderBy('hr_notifications.createddatetime', 'desc')
            ->get();

        $replies = [];
        if ($request->purpose == 'notification') {

            $replies = DB::table('hr_notifications_replies')
                // ->where('hr_notfication_id', $messageid)
                ->where('hr_notifications.createdby', $recipientid)
                ->where('hr_notifications_replies.createdby', '!=', $recipientid)
                ->where('hr_notifications_replies.deleted', 0)
                // ->where('hr_notifications_replies.seen', 0)
                // ->where('hr_notifications_replies.displayed', 0)
                ->join('hr_notifications', function ($join) {
                    $join->on('hr_notifications.id', '=', 'hr_notifications_replies.hr_notfication_id');
                })
                ->join('teacher', function ($join) {
                    $join->on('teacher.id', '=', 'hr_notifications_replies.createdby');
                    $join->where('teacher.deleted', 0);
                })
                ->join('teacher as recepientTeacher', function ($join) {
                    $join->on('recepientTeacher.id', '=', 'hr_notifications.createdby');
                    $join->where('recepientTeacher.deleted', 0);
                })
                ->where('teacher.deleted', 0)
                ->select(
                    'teacher.picurl',
                    DB::raw('CONCAT_WS(" ", COALESCE(teacher.firstname, ""), COALESCE(teacher.middlename, ""), COALESCE(teacher.lastname, "")) AS full_name'),
                    'hr_notifications_replies.id',
                    'hr_notifications_replies.message',
                    'hr_notifications_replies.createddatetime',
                    'hr_notifications.recipientid',
                    'hr_notifications.deleted',
                    'hr_notifications.seen',
                    'hr_notifications_replies.createdby',
                    DB::raw('CONCAT_WS(" ", COALESCE(recepientTeacher.firstname, ""), COALESCE(recepientTeacher.middlename, ""), COALESCE(recepientTeacher.lastname, "")) AS recepientfull_name')

                )
                ->groupBy('hr_notifications_replies.createdby')
                ->orderBy('hr_notifications_replies.createddatetime', 'desc')
                ->get();
        }
        


        $grouped_messages = DB::table('hr_notifications')
            ->where('hr_notifications.recipientid', $recipientid)
            ->where('hr_notifications.deleted', 0)
            ->join('teacher as createdbyTeacher', function ($join) use ($recipientid) {
                $join->on('createdbyTeacher.id', '=', 'hr_notifications.createdby');
                $join->where('createdbyTeacher.deleted', 0);
            })
            ->when($header, function ($query) use ($header) {
                return $query->where(function ($query) use ($header) {
                    $query->take(5);
                });
            })
            ->unless($header || $header2, function ($query) use ($recipientid) {
                return $query->orWhere('hr_notifications.createdby', $recipientid);
            })
            ->join('teacher as recipientTeacher', function ($join) use ($recipientid) {
                $join->on('recipientTeacher.id', '=', 'hr_notifications.recipientid');
                $join->where('recipientTeacher.deleted', 0);
            })
            ->select(
                'createdbyTeacher.picurl',
                'createdbyTeacher.id as createdbyid',
                'recipientTeacher.id as reciepientid',
                DB::raw('CONCAT_WS(" ", COALESCE(createdbyTeacher.firstname, ""), COALESCE(createdbyTeacher.middlename, ""), COALESCE(createdbyTeacher.lastname, "")) AS full_name'),
                DB::raw('CONCAT_WS(" ", COALESCE(recipientTeacher.firstname, ""), COALESCE(recipientTeacher.middlename, ""), COALESCE(recipientTeacher.lastname, "")) AS recepientfull_name'),
                'hr_notifications.*'
            )
            ->groupBy('hr_notifications.createdby')
            ->groupBy('hr_notifications.recipientid')
            ->orderBy('hr_notifications.createddatetime', 'desc')
            ->get();

        // dd($grouped_messages);

        $newmessagearr = [];

        foreach ($grouped_messages as $key => $msg) {
            $latest_msg = $messages
                ->where('reciepientid', $msg->recipientid)
                ->where('createdbyid', $msg->createdbyid)
                ->sortByDesc('createddatetime')
                ->first();
            if ($latest_msg) {
                $newmessagearr[] = $latest_msg;
            }

        }


        $newmessagearr = collect($newmessagearr)->sortByDesc('createddatetime')->values()->all();
        // dd($newmessagearr);


        foreach ($newmessagearr as $message) {
            $message->time_ago = Carbon::parse($message->createddatetime)->diffForHumans();
            $message->date_created = Carbon::parse($message->createddatetime)->format('F j, Y');
            if ($message->createdbyid == $recipientid) {
                $message->data_id = $message->reciepientid;
                $message->name = $message->recepientfull_name;
            } else {
                $message->data_id = $message->createdbyid;
                $message->name = $message->full_name;
            }
        }

        //    dd($newmessagearr);

        $newreplyarr = [];
        $latest_reply = collect($replies)->values();
        if (count($latest_reply) > 0) {
            foreach ($latest_reply as $reply) {
                $reply->time_ago = Carbon::parse($reply->createddatetime)->setTimezone('Asia/Manila')->diffForHumans();
                $reply->date_created = Carbon::parse($reply->createddatetime)->format('F j, Y');
                $reply->type = 'reply';
                $reply->data_id = $reply->createdby;
                $reply->name = $reply->full_name;

                $newreplyarr[] = $reply;
            }
        }

        if (count($newreplyarr) > 0) {
            $merged_arr = array_merge($newmessagearr, $newreplyarr);
            usort($merged_arr, function ($a, $b) {
                return strtotime($b->createddatetime) - strtotime($a->createddatetime);
            });
            return $merged_arr;
        } else {
            return $newmessagearr;
        }



    }

    public function getDepartment(Request $request)
    {

        $department = DB::table('hr_departments')
            ->where('deleted', 0)
            ->select('department as text', 'id')
            ->get();


        return $department;

    }

    public function uploadattachedfile(Request $request)
    {

        $file = $request->file('file');

        $newFileName = time() . '.' . $file->getClientOriginalExtension();
        ;

        $destinationPath = public_path('notification_attachment/'); // Adjust the destination path as needed

        $file->move($destinationPath, $newFileName);

        // Store the uploaded file in the public/products directory
        $path = '/notification_attachment/' . $newFileName;


        return response()->json(['url' => $path], 200);
    }







}