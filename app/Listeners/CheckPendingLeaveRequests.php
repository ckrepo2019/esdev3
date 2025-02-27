<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Http\Controllers\NotificationController\NotificationController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CheckPendingLeaveRequests
{
    public function handle(Login $event)
    {
        $user = auth()->user();
        $usertypes_ids = [];

        if ($user->type != 17) {
            $usertypes_ids[] = $user->type;
            $otherusertypes = DB::table('faspriv')
                    ->where('userid', $user->id)
                    ->get();
            
            foreach ($otherusertypes as $otherusertype) {
                $usertypes_ids[] = $otherusertype->usertype;
            }

            $teacher = DB::table('teacher')
                ->where('userid', $user->id)
                ->where('deleted', '0')->first();
            // Ensure the teacher record exists
            if (!$teacher) {
                return;
            }

            $id = $teacher->id;
            $isDepartmentHead = false;
            $deptids = [];

            // Check if the user is a department head
            $checkdepthead = DB::table('hr_departmentheads')
                ->where('deptheadid', $id)
                ->where('deleted', '0')
                ->get();
            
            if ($checkdepthead->isNotEmpty()) {
                $isDepartmentHead = true;
                $deptids = $checkdepthead->pluck('deptheadid');
            }
            
            if ($isDepartmentHead) {
                // Cache key for tracking last notification date
                $cacheKey = 'last_leave_notifications_sent_date_' . $user->id;
                $lastNotificationDate = Cache::get($cacheKey);
            
                // Check if notifications were already sent today
                if ($lastNotificationDate === today()->toDateString()) {
                    return; // Exit if notifications have been processed today
                }

                $filedleaves = DB::table('hr_leaveemployees')
                    ->select(
                        'hr_leaveemployees.id',
                        'hr_leaveemployees.employeeid',
                        'hr_leaveemployees.leaveid',
                        'hr_leaveemployees.leavestatus',
                        'hr_leaveemployees.remarks',
                        'hr_leaveemployees.datefrom',
                        'hr_leaveemployees.dateto',
                        'hr_leaveemployees.createdby',
                        'hr_leaves.leave_type',
                        'hr_leaves.days',
                        'teacher.lastname',
                        'teacher.userid',
                        'teacher.firstname',
                        'teacher.middlename',
                        'teacher.suffix',
                        'teacher.picurl',
                        'usertype.utype',
                        'hr_leaveemployees.createddatetime',
                        'employee_personalinfo.departmentid'
                    )
                    ->join('teacher', 'hr_leaveemployees.employeeid', '=', 'teacher.id')
                    ->join('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                    ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                    ->join('hr_leaves', 'hr_leaveemployees.leaveid', '=', 'hr_leaves.id')
                    ->where('hr_leaves.deleted', '0')
                    ->where('hr_leaveemployees.deleted', '0')
                    ->orderByDesc('hr_leaveemployees.createddatetime')
                    ->get();
                
                // Process leave applications
                foreach ($filedleaves as $leaveapp) {
                    $leaveapp->approvercount = DB::table('hr_leaveemployeesappr')
                        ->where('headerid', $leaveapp->id)
                        ->whereIn('appuserid', function ($query) use ($leaveapp) {
                            $query->select('appuserid')
                                ->from('hr_leavesappr')
                                ->where('leaveid', $leaveapp->leaveid)
                                ->where('deleted', '0');
                        })
                        ->where('appstatus', 1)
                        ->where('deleted', '0')
                        ->count();
                }
                
                // Filter pending leave applications for the department
                $leaveapplicationPending = collect($filedleaves)
                    ->whereIn('departmentid', $deptids)
                    ->where('approvercount', 0)
                    ->values();
                
                // Send notifications for each pending leave request
                foreach ($leaveapplicationPending as $pendingapplication) {
                    foreach ($deptids as $key => $dept) {
                        NotificationController::sendNotification(
                            $pendingapplication->leave_type . ' Pending',
                            "{$pendingapplication->firstname} {$pendingapplication->lastname} has pending leave requests that need approval.",
                            $dept,   // Receiver ID (payroll officer)
                            'notification'
                        );
                    }
                }

                // Update the cache with today’s date and set to expire at the end of the day
                Cache::put($cacheKey, today()->toDateString(), now()->endOfDay());
            }
        }
    }
}

