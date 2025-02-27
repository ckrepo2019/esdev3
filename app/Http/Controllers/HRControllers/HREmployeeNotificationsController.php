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
class HREmployeeNotificationsController extends Controller
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
            ->where('deleted','0')
            ->orderBy('department','ASC')
            ->get();

        $employees = DB::table('teacher')
            ->select('teacher.id','lastname','firstname','middlename','suffix','amount as salaryamount','utype as designation')
            ->leftJoin('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
            ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
            // ->where('employee_basicsalaryinfo.deleted','0')
            ->where('teacher.deleted','0')
            ->where('teacher.isactive','1')
            ->orderBy('lastname','asc')
            ->get();

        return view('hr.employees.notifications')
            ->with('sy', $sy)
            ->with('employees',$employees)
            ->with('departments',$departments)
            ->with('semester', $semester);
    }


    public function sendnotification(Request $request){
        $file = $request->input('formData');

        $recipienttype = $request->input('recipienttype');
        $recipientids = $request->input('recipientid'); // this might be a comma-separated string
        $recipientids = explode(',', $recipientids); // convert to array   

        $subject = $request->input('subject');
        $sentruemail = $request->input('sentruemail');
        $sentrusystem = $request->input('sentrusystem');
        $message = $request->input('additionalmessage');
        
        $email_array = array();
        // $attachments = [];
        if ($sentruemail == 1) {
            if ($recipienttype == 2) {
                foreach ($recipientids as $recipientid) {
                    $email = DB::table('employee_personalinfo')
                        ->where('employeeid', $recipientid)
                        ->first();
            
                    // Check if $email is not null before accessing its property
                    if ($email && isset($email->email)) {
                        $email_array[] = $email->email;
                    }
                }
            }

            if ($recipienttype == 1) {
                foreach ($recipientids as $recipientid) {
                    $emails = DB::table('employee_personalinfo')
                        ->where('departmentid', $recipientid)
                        ->get();

                    foreach ($emails as $email) {
                        // Check if $email is not null before accessing its property
                        if ($email && isset($email->email)) {
                            $email_array[] = $email->email;
                        }
                    }

                }

            }
        }
        
        // Convert recipient IDs to a comma-separated string
        $recipientidString = implode(',', $recipientids);

        try {
            $details = [
                'subject' => $subject,
                'content' => $message
            ];

            foreach ($email_array as $email) {
                Mail::to($email)->send(new HRMail($details));
            }

            // Insert the record with recipient IDs as a comma-separated string
            $data = DB::table('hr_notifications')->insert([
                'recipienttype' => $recipienttype,
                'recipientid' => $recipientidString,
                'subject' => $subject,
                'sentruemail' => $sentruemail,
                'sentrusystem' => $sentrusystem,
                'additionalmessage' => $message,
                'createddatetime' => date('Y-m-d H:i:s'),
            ]);


            // ===========================================================================
            // $notificationid = $data;


            // if($request->hasFile('files'))
            // {
            //     foreach($request->file('files') as $file)
            //     {
            //         $filename = $file->getClientOriginalName();
            //         $extension = $file->getClientOriginalExtension();

            //         $localfolder = 'HumanResourceFiles/'.auth()->user()->email;
            //         if (! File::exists(public_path().$localfolder)) {
            
            //             $path = public_path($localfolder);
            
            //             if(!File::isDirectory($path)){
                            
            //                 File::makeDirectory($path, 0777, true, true);
            
            //             }
                        
            //         }

            //         if (strpos($request->root(),'http://') !== false) {
            //             $urlFolder = str_replace('http://','',$request->root());
            //         } else {
            //             $urlFolder = str_replace('https://','',$request->root());
            //         }
                        
            //         if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder)) {
            
            //             $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder;
                        
            //             if(!File::isDirectory($cloudpath)){
            
            //                 File::makeDirectory($cloudpath, 0777, true, true);
                            
            //             }
                        
            //         }

            //         $destinationPath = public_path($localfolder.'/');
                    
            //         try{
            
            //             $file->move($destinationPath,$filename);
            //             $attachments[] = [
            //                 'path' => $destinationPath . $filename,
            //                 'name' => $filename,
            //                 'mime' => $file->getClientMimeType(),
            //             ];
            
            //         }
            //         catch(\Exception $e){
                        
                
            //         }

            //         DB::table('hr_files')
            //             ->insert([
            //                 'headerid'          => $notificationid,
            //                 'filename'          => $filename,
            //                 'picurl'            => $localfolder.'/'.$filename,
            //                 'extension'         => $extension,
            //                 'createdby'         => auth()->user()->id,
            //                 'createddatetime'   => date('Y-m-d H:i:s')
            //             ]);
            //     }

            // }

            // $details = [
            //     'subject' => $subject,
            //     'content' => $message,
            // ];

            // foreach ($email_array as $email) {
            //     Mail::to($email)->send(new HRMail($details, $attachments));
            // }

            // ===========================================================================

            return array((object)[
                'status' => 1,
                'message' => 'Notification Sent!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to send notification: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function load_notifications(Request $request){
        $notifications = DB::table('hr_notifications')
            ->where('deleted', 0)
            ->get();

        return $notifications;
    }

    public function delete_notification(Request $request){
        $id = $request->input('id');

        $notifications = DB::table('hr_notifications')
            ->where('deleted', 0)
            ->where('id', $id)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => date('Y-m-d H:i:s')
            ]);

        return array((object)[
            'status' => 1,
            'message' => 'Deleted Successfully!',
        ]);
    }

    





    // USERS FUNCTIONS

    public function userview_notifications(){
        $sy = DB::table('sy')
            ->where('isactive', 1)
            ->first();
        
        $semester = DB::table('semester')
            ->where('isactive', 1)
            ->first();
        
        return view('general.notifications.user_notifications')
            ->with('sy', $sy);
    }

    public function load_user_notifications(Request $request) {
        $authid = auth()->user()->id;
        $deptid = null;
        
        $userid = DB::table('teacher')
            ->where('userid', $authid)
            ->first()->id;
    
        $dept_userid = DB::table('employee_personalinfo')
            ->where('employeeid', $userid)
            ->first();
        if ($dept_userid) {
            if ($dept_userid->departmentid == null || $dept_userid->departmentid == '') {
                $deptid = null;
            } else {
                $deptid = $dept_userid->departmentid;
            }
        }
    
        $notifications = DB::table('hr_notifications')
            ->where('sentrusystem', 1)
            ->where('deleted', 0)
            ->get()
            ->map(function ($notification) use ($userid, $deptid) {
                $recipientIds = explode(',', $notification->recipientid);
                $acknowledgeIds = explode(',', $notification->acknowledgeby);

                // Check if the user ID or department ID is in the recipient IDs
                $isRecipient = in_array($userid, $recipientIds) || in_array($deptid, $recipientIds);

                // Set acknowledge status
                $notification->acknowledge_status = in_array($userid, $acknowledgeIds) ? 1 : 0;

                // Return the notification only if the user or department is a recipient
                return $isRecipient ? $notification : null;
            })
            ->filter()
            ->values(); // Reset the keys to ensure no extra keys are present

        // Convert to an array of objects
        $notificationsArray = $notifications->toArray();

        // Output JSON without the outer key
        return response()->json($notificationsArray);
    }

    public function acknowledge_notification(Request $request) {
        try {
            $authid = auth()->user()->id;
            $notificationid = $request->get('notificationid');
    
            $teacher = DB::table('teacher')
                ->where('userid', $authid)
                ->first();
    
            if (!$teacher) {
                return array((object)[
                    'status' => 0,
                    'message' => 'Teacher not found!',
                ]);
            }
    
            $userid = $teacher->id;

            $notification = DB::table('hr_notifications')
                ->where('id', $notificationid)
                ->where('sentrusystem', 1)
                ->where('deleted', 0)
                ->first();

            if ($notification) {
                $existingAcknowledgeBy = $notification->acknowledgeby;

                if (is_null($existingAcknowledgeBy) || $existingAcknowledgeBy === '') {
                    // If acknowledgeby is null or empty, set it directly to the userid
                    $newAcknowledgeBy = (string)$userid;
                } else {
                    // Convert the string to an array
                    $acknowledgeByArray = explode(',', $existingAcknowledgeBy);
                    
                    // Add the new userid if it does not already exist
                    if (!in_array($userid, $acknowledgeByArray)) {
                        $acknowledgeByArray[] = $userid;
                    }
                    
                    // Convert the array back to a comma-separated string
                    $newAcknowledgeBy = implode(',', $acknowledgeByArray);
                }
                
                // Update the database
                DB::table('hr_notifications')
                    ->where('id', $notificationid)
                    ->where('sentrusystem', 1)
                    ->where('deleted', 0)
                    ->update([
                        'acknowledgeby' => $newAcknowledgeBy,
                    ]);

                return array((object)[
                    'status' => 1,
                    'message' => 'Acknowledged!',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error acknowledging notification: ' . $e->getMessage());
    
            return response()->json([
                'status' => 0,
                'message' => 'An error occurred while processing your request. Please try again later.',
            ], 500);
        }
    }


    

}