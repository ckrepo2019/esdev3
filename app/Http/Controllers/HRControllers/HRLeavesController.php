<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Facades\File; // To check and create directories
use Illuminate\Support\Facades\Storage; // Optional, for handling files
use App\Http\Controllers\NotificationController\NotificationController;
use App\Events\NotificationEvent;


class HRLeavesController extends Controller
{
    public function requirements()
    {
        $requirements = $this->allRequiremnts()['requirements'];
        // dd($requirements);
        return view('hr.requirements.requirements', ['requirements' => $requirements]);
    }

    public function allRequiremnts()
    {
        $requirements = DB::table("hr_requirements")
            ->where('deleted', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($requirements as $requirement) {
            $subdata = $this->viewrequirementById($requirement->id)['requirement'];
            // dd($subdata);
            // Assuming totalEmployees is not zero to avoid division by zero
            $totalEmployees = isset($subdata->totalEmployees) ? $subdata->totalEmployees : 0;

            $requirement->totemp = $totalEmployees;
            $requirement->totSub = $subdata->totalSubmitted ?? 0;
            $requirement->totApp = $subdata->totalApproved ?? 0;
            $requirement->totRej = $subdata->totalRejected ?? 0;
            $requirement->totNotSub = $subdata->totalNotSubmitted ?? 0;

            if ($totalEmployees > 0) {
                // Calculate percentages
                $requirement->percentageSubmitted = $totalEmployees > 0 ? ($subdata->totalSubmitted / $totalEmployees) * 100 : 0;
                $requirement->percentageApproved = $totalEmployees > 0 ? ($subdata->totalApproved / $totalEmployees) * 100 : 0;
                $requirement->percentageRejected = $totalEmployees > 0 ? ($subdata->totalRejected / $totalEmployees) * 100 : 0;
                $requirement->percentageNotSubmitted = $totalEmployees > 0 ? ($subdata->totalNotSubmitted / $totalEmployees) * 100 : 0;
            } else {
                $requirement->percentageSubmitted = 0;
                $requirement->percentageApproved = 0;
                $requirement->percentageRejected = 0;
                $requirement->percentageNotSubmitted = 0;
            }
        }

        return [
            'requirements' => $requirements
        ];
    }

    public function getTeacherLogs(Request $request)
    {

        $logs = DB::table('hr_requirements_history')
            ->where('hr_requirements_history.req_employee_id', $request->id)
            ->join('hr_requirements', 'hr_requirements_history.requirement_id', '=', 'hr_requirements.id')
            ->join('teacher', 'hr_requirements_history.created_by', '=', 'teacher.userid')
            ->leftJoinSub(
                DB::table('hr_requirements_file')
                    ->where('hr_requirements_file.deleted', 0)
                    ->where('hr_requirements_file.employee_id', $request->id)
                    ->select('req_id', DB::raw('MIN(filepath) as filepath'), 'filename', 'fileextension') // Select only the first file
                    ->groupBy('req_id'),
                'files',
                'hr_requirements_history.requirement_id',
                '=',
                'files.req_id'
            )
            ->select(
                'hr_requirements.requirement_name',
                'hr_requirements.created_at AS req_created_at',
                'hr_requirements_history.*',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.lastname',
                'files.filepath', // Contains the first file path for each requirement
                'files.filename', // Contains the filename for each requirement
                'files.fileextension' // Contains the file extension for each requirement
            )
            ->orderBy('hr_requirements_history.created_at', 'asc')
            ->get()
            ->groupBy('requirement_id'); // Group by requirement_id for easy access
        return response()->json($logs);

    }


    public function teacherRequirementsDashboard(Request $request)
    {
        // Fetch the employee first
        $employee = DB::table('teacher')
            ->where('deleted', 0)
            ->where('userid', auth()->user()->id)
            ->first();

        // Fetch requirements in one query
        $requirements = DB::table("hr_requirements")
            ->where('hr_requirements.deleted', 0)
            ->where(function ($query) use ($employee) {
                // Check if visibility_type is 'selectedemployee' and filter by employee ID in the exploded 'employees' column
                $query->where(function ($query) use ($employee) {
                    $query->where('visibility_type', 'selectedemployee')
                        ->whereRaw("FIND_IN_SET(?, employees)", [$employee->id]);
                })
                    // Check if visibility_type is 'selecteddepartments' and filter by department in the exploded 'departments' column
                    ->orWhere(function ($query) use ($employee) {
                    $query->where('visibility_type', 'selecteddepartments')
                        ->whereRaw("FIND_IN_SET(?, departments)", [$employee->schooldeptid]);
                });
            })
            ->join('teacher', 'hr_requirements.createdby', '=', 'teacher.userid')
            ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->select('hr_requirements.*', 'teacher.firstname', 'teacher.middlename', 'teacher.lastname', 'usertype.utype')
            ->orderBy('created_at', 'desc')
            ->get();

        // Gather all requirement IDs
        $requirementIds = $requirements->pluck('id');

        // Fetch all requirement history at once
        $employeeReqHistories = DB::table('hr_requirements_history')
            ->whereIn('requirement_id', $requirementIds)
            ->where('req_employee_id', $employee->id)
            ->join('teacher', 'hr_requirements_history.created_by', '=', 'teacher.userid')
            ->select(
                'hr_requirements_history.*',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.lastname'
            )
            ->orderBy('hr_requirements_history.created_at', 'desc')
            ->get()
            ->groupBy('requirement_id'); // Group by requirement_id for easy access

        // return $employeeReqHistories;

        // Fetch all requirement files at once
        $reqFiles = DB::table('hr_requirements_file')
            ->whereIn('req_id', $requirementIds)
            ->where('deleted', 0)
            ->where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('req_id');
        // ->keyBy('req_id'); // Index by req_id for easy access

        // return $reqFiles;

        // Now loop through requirements and add necessary details
        foreach ($requirements as $requirement) {
            // Attach the latest file for the requirement
            $requirement->req_file = $reqFiles->get($requirement->id) ?? null;

            // Get the history for the requirement
            $history = $employeeReqHistories->get($requirement->id) ?? collect();

            if ($history->isNotEmpty()) {
                $requirement->has_req = 1;
                $requirement->latest_history = $history->first();
                $requirement->req_history = $history->sortBy('created_at')->values()->all();
            } else {
                $requirement->has_req = 0;
                $requirement->req_history = [];
                $requirement->latest_history = null;
            }
        }

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'requirements' => $requirements, 'employee' => $employee]);
        }

        return view('teacher.requirements.myrequirements', ['requirements' => $requirements, 'employee' => $employee]);
    }

    public function cancelUpload($fileId)
    {
        // Logic to delete the file from storage and database
        $file = DB::table('hr_requirements_file')->where('id', $fileId)->first();
        if ($file && File::exists(public_path($file->filepath))) {
            File::delete(public_path($file->filepath));
            DB::table('hr_requirements_file')->where('id', $fileId)->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'File not found'], 404);
    }

    public function uploadRequirement(Request $request)
    {
        // Validate the required fields (req_id and employee_id)
        $request->validate([
            'req_id' => 'required|integer',
            'employee_id' => 'required|integer',
            'file' => 'required|file|max:5120', // Max file size is 5 MB
        ]);

        // Define the destination folder path
        $destinationPath = public_path('hrrequirements');

        // Check if the directory exists, if not, create it
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true); // Create the directory with permissions
        }

        // Ensure the request has a file
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Get the original file name and its extension
            $fileName = time() . '_' . $file->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();

            // Move the file to the destination folder
            $file->move($destinationPath, $fileName);

            // File path relative to the public directory
            $filePath = 'hrrequirements/' . $fileName;

            // Insert the data into the hr_requirements_file table using DB::table
            DB::table('hr_requirements_file')->insert([
                'req_id' => $request->input('req_id'),
                'employee_id' => $request->input('employee_id'),
                'filepath' => $filePath,     // Store the file path
                'filename' => $fileName,     // Store the file name
                'fileextension' => $fileExtension, // Store the file extension
                'created_at' => now('Asia/Manila'),       // Current timestamp
            ]);

            DB::table('hr_requirements_history')->insert([
                'requirement_id' => $request->req_id,
                'req_employee_id' => $request->employee_id,
                'req_status' => 'submitted',
                'req_remarks' => 'Initial requirement created.',
                'created_by' => auth()->user()->id,
                'created_at' => now('Asia/Manila')->format('Y-m-d H:i:s'),
            ]);

            $createdBy = DB::table('hr_requirements')
                ->where('id', $request->req_id)
                ->value('createdby');


            $person = DB::table('teacher')->where('id', $request->employee_id)->first();

            // Prepare notification data as an associative array
            $notificationData = [
                'message' => $person->lastname . ', ' . $person->firstname . ' Submitted a new requirement.',
                'receiver_id' => $createdBy,
                'purpose' => 'Requirement submitted',
                'status' => 'submitted',
            ];
            // Attempt to send notification
            try {
                NotificationController::sendNotification($notificationData['purpose'], $notificationData['message'], $notificationData['receiver_id'], 'notification', $notificationData['status'], 'hr/requirements/employee');
            } catch (\Exception $e) {
                \Log::error('Notification sending failed:', ['error' => $e->getMessage()]);
                // Continue execution even if notification fails
            }

            // Return a success response
            return response()->json(['success' => true, 'file' => $fileName], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }
    }

    public function editrequirement(Request $request)
    {
        $requirement = DB::table('hr_requirements')
            ->where('id', $request->id)
            ->first();

        // Explode employees and departments fields
        $requirement->employees = explode(',', $requirement->employees);
        $requirement->departments = explode(',', $requirement->departments);

        return response()->json([
            'requirement' => $requirement
        ]);

    }
    public function updaterequirement(Request $request)
    {
        if ($request->purpose && $request->purpose == 'updatename') {
            DB::table('hr_requirements')->where('id', $request->requirement_id)
                ->update([
                    'requirement_name' => $request->requirement_name,
                ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Updated Name Succesfully',
            ]);
        }

        DB::table('hr_requirements')->where('id', $request->id)
            ->update([
                'requirement_name' => $request->name,
                'requirement_desc' => $request->description,
                'due_date' => $request->due_date,
                'due_reminder' => $request->reminder,
                'visibility_type' => $request->visibility_type,
                'employees' => $request->selected_employees ? implode(',', $request->selected_employees) : null,
                'departments' => $request->selected_departments ? implode(',', $request->selected_departments) : null,
            ]);


        return response()->json([
            'status' => 'success',
            'message' => 'Updated Succesfully',
            'requirements' => $this->allRequiremnts()['requirements'],
        ]);

    }

    public function viewrequirementById($id)
    {
        $totalSubmitted = 0;
        $totalApproved = 0;
        $totalRejected = 0;
        $requirement = DB::table('hr_requirements')
            ->where('hr_requirements.id', $id)
            ->where('hr_requirements.deleted', 0)
            ->leftJoin('teacher', 'hr_requirements.createdby', '=', 'teacher.userid')
            ->select('hr_requirements.*', 'teacher.firstname', 'teacher.middlename', 'teacher.lastname')
            ->first();

        $departments = [];
        $employees = DB::table('teacher')
            ->where('teacher.deleted', 0)
            ->select('teacher.*', 'usertype.utype')
            ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id');

        if (isset($requirement->visibility_type)) {
            if ($requirement->visibility_type == 'selectedemployee') {
                $employees = $employees->whereIn('teacher.id', explode(',', $requirement->employees));
            } else if ($requirement->visibility_type == 'selecteddepartments') {
                $employees = $employees->whereIn('schooldeptid', explode(',', $requirement->departments));
                $departments = DB::table('hr_departments')->whereIn('id', explode(',', $requirement->departments))->get();
            }
        }

        if (isset($requirement->visibility_type) && $requirement->visibility_type != 'onlyme') {
            $employees = $employees->get(); // Get the employees collection

            foreach ($employees as $employee) {
                // Fetch the requirement history ordered by created_at in descending order (latest first)
                $employee_req_history = DB::table('hr_requirements_history')
                    ->where('requirement_id', $requirement->id)
                    ->where('req_employee_id', $employee->id)
                    ->where('hr_requirements_history.deleted', 0)
                    ->join('teacher', 'hr_requirements_history.created_by', '=', 'teacher.userid')
                    ->select(
                        'hr_requirements_history.*',
                        'teacher.firstname',
                        'teacher.middlename',
                        'teacher.lastname'
                    )
                    ->orderBy('created_at', 'desc')
                    ->get();

                $reqfile = DB::table('hr_requirements_file')
                    ->where('req_id', $requirement->id)
                    ->where('deleted', 0)
                    ->where('employee_id', $employee->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

                // Map the results to include the full asset URL for filepath
                $reqfile = $reqfile->map(function ($file) {
                    $file->filepath = asset($file->filepath); // Append the asset URL
                    return $file;
                });

                $employee->req_file = $reqfile;


                // Check if there is any history
                if ($employee_req_history->isNotEmpty()) {
                    $employee->has_req = 1;
                    $totalSubmitted += 1;

                    $employee->latest_history = $employee_req_history->first();
                    if ($employee->latest_history->req_status == 'approved') {
                        $totalApproved += 1;
                    } else if ($employee->latest_history->req_status == 'rejected') {
                        $totalRejected += 1;
                    }

                    // History ordered by ascending (oldest first)
                    $employee->req_history = $employee_req_history->sortBy('created_at')->values()->all();
                } else {
                    $employee->has_req = 0;
                    $employee->req_history = [];
                    $employee->latest_history = null;
                }
            }


            $requirement->totalEmployees = $employees->count();
            $requirement->totalSubmitted = $totalSubmitted;
            $requirement->totalApproved = $totalApproved;
            $requirement->totalRejected = $totalRejected;
            $requirement->totalNotSubmitted = $employees->count() - $totalSubmitted;

            // dd($requirement);
        }

        return [
            'requirement' => $requirement,
            'employees' => $employees,
            'departments' => $departments,
        ];
    }

    public function viewrequirement(Request $request)
    {

        $requirement = $this->viewrequirementById($request->id);
        // dd($requirement);
        return view(
            'hr.requirements.requirement',
            [
                'requirement' => $requirement['requirement'],
                'employees' => $requirement['employees'],
                'departments' => $requirement['departments'],
            ]
        );
    }

    public function create_folder_requirement(Request $request)
    {
        // Validate the request input and return errors if validation fails
        $validator = \Validator::make($request->all(), [
            'requirement_name' => 'required|string|max:255',
            'requirement_desc' => 'nullable|string|max:255',
            'due_date' => 'required|date',
            'due_reminder' => 'required|in:weekly,daily',
            'visibility_type' => 'required|in:all,selectedemployee,onlyme,selecteddepartments',
            'employees' => 'nullable|array',
            'departments' => 'nullable|array',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // Proceed with the insertion if validation is successful
        DB::table('hr_requirements')->insert([
            'requirement_name' => $request->requirement_name, // Correct variable name
            'requirement_desc' => $request->requirement_desc,
            'createdby' => auth()->user()->id,
            'due_date' => $request->due_date,
            'due_reminder' => $request->due_reminder,
            'created_at' => now('Asia/Manila')->format('Y-m-d H:i:s'),
            'visibility_type' => $request->visibility_type,
            'employees' => $request->employees ? implode(',', $request->employees) : null,
            'departments' => $request->departments ? implode(',', $request->departments) : null,
        ]);

        if ($request->visibility_type != 'onlyme') {
            foreach ($request->employees as $signee) {
                NotificationController::sendNotification(
                    'HR Requirement',
                    'You have been assigned to a new requirement: ' . $request->requirement_name,
                    DB::table('teacher')->where('id', $signee)->first()->userid,
                    'notification',
                    'pending',
                    '/hr/requirements/employee'
                );
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Requirement created successfully',
            'requirements' => $this->allRequiremnts()['requirements'], // Updated list to refresh the table
        ], 200);
    }

    public function destroyrequirement($id)
    {
        // Find the requirement by id
        $requirement = DB::table('hr_requirements')->find($id);

        // Check if the requirement exists
        if (!$requirement) {
            return response()->json([
                'status' => 'error',
                'message' => 'Requirement not found'
            ], 404);
        }

        // Delete the requirement
        DB::table('hr_requirements')->where('id', $id)->update([
            'deleted' => 1
        ]);


        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Requirement deleted successfully',
            'requirements' => $this->allRequiremnts()['requirements'], // Updated list to refresh the table
        ], 200);
    }

    public function requirementApprove(Request $request)
    {

        // Insert into the hr_requirements_history table
        DB::table('hr_requirements_history')->insert([
            'requirement_id' => $request->req_id,
            'req_employee_id' => $request->employee_id,
            'req_status' => $request->req_status,
            'req_remarks' => $request->req_remarks,
            'created_by' => auth()->user()->id,
            'created_at' => now('Asia/Manila')->format('Y-m-d H:i:s'),
        ]);

        if ($request->req_status == 'rejected' || $request->req_status == 'returned') {
            DB::table('hr_requirements_file')
                ->where('req_id', $request->req_id)
                ->where('employee_id', $request->employee_id)
                ->update([
                    'deleted' => 1,
                ]);
        }

        // Fetch the updated requirement data
        $requirement = $this->viewrequirementById($request->req_id);

        // Prepare notification data as an associative array
        $notificationData = [
            'message' => sprintf(
                "The '%s' was '%s'.",
                $requirement['requirement']->requirement_name,
                $request->req_status
            ),
            'receiver_id' => DB::table('teacher')->where('id', $request->employee_id)->value('userid'),
            'purpose' => 'Requirement ' . $request->req_status,
            'status' => $request->req_status
        ];

        // Attempt to send notification
        try {
            NotificationController::sendNotification($notificationData['purpose'], $notificationData['message'], $notificationData['receiver_id'], 'notification', $notificationData['status'], 'hr/requirements/employee');
        } catch (\Exception $e) {
            \Log::error('Notification sending failed:', ['error' => $e->getMessage()]);
            // Continue execution even if notification fails
        }

        // Broadcast the notification event
        // broadcast(new NotificationEvent($notificationData));

        return response()->json([
            'status' => 'success',
            'message' => 'Requirement ' . $request->req_status . ' successfully',
            'requirement' => $requirement['requirement'],
            'employees' => $requirement['employees'],
            'departments' => $requirement['departments'],
        ], 200);


    }

    public function notificationv2()
    {
        $notifications = DB::table('notificationv2')
            ->where('deleted', 0)
            ->orderBy('created_at', 'desc')
            ->where('receiver', auth()->user()->id)->get();

        return view('websockets.notificationv2', compact('notifications'));
    }
    public function getAllNotificationsv2(Request $request)
    {
        $notifications = DB::table('notificationv2')
            ->where('deleted', 0)
            ->where('receiver', auth()->user()->id)
            ->where(function ($query) {
                $query->whereNull('usertype') // Include notifications where usertype is NULL
                    ->orWhere('usertype', auth()->user()->type); // Or usertype matches the user's type
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'notifications' => $notifications
        ], 200);
    }



    public function notifmarkasread(Request $request)
    {
        DB::table('notificationv2')
            ->where('id', $request->id)
            ->update([
                'seen' => 1
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Notification marked as read'
        ], 200);
    }

    public function notifmarkasdisplayed(Request $request)
    {
        DB::table('notificationv2')
            ->where('receiver', auth()->user()->id)
            ->where(function ($query) {
                $query->whereNull('usertype') // Include notifications where usertype is NULL
                    ->orWhere('usertype', auth()->user()->type); // Or usertype matches the user's type
            })
            ->update([
                'displayed' => 1
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Notification marked as displayed'
        ], 200);
    }






    public function index(Request $request)
    {
        $check_refid = DB::table('usertype')
            ->where('id', Session::get('currentPortal'))
            ->select('refid', 'resourcepath')
            ->first();

        if (Session::get('currentPortal') == 14) {
            $extends = 'deanportal.layouts.app2';
        } elseif (auth()->user()->type == 17) {
            $extends = 'superadmin.layouts.app2';
        } elseif (Session::get('currentPortal') == 3) {
            $extends = 'registrar.layouts.app';
        } elseif (Session::get('currentPortal') == 8) {
            $extends = 'admission.layouts.app2';
        } elseif (Session::get('currentPortal') == 1) {
            $extends = 'teacher.layouts.app';
        } elseif (Session::get('currentPortal') == 2) {
            $extends = 'principalsportal.layouts.app2';
        } elseif (Session::get('currentPortal') == 4) {
            $extends = 'finance.layouts.app';
        } elseif (Session::get('currentPortal') == 15) {
            $extends = 'finance.layouts.app';
        } elseif (Session::get('currentPortal') == 18) {
            $extends = 'ctportal.layouts.app2';
        } elseif (Session::get('currentPortal') == 10) {
            $extends = 'hr.layouts.app';
        } elseif (Session::get('currentPortal') == 16) {
            $extends = 'chairpersonportal.layouts.app2';
        } elseif (auth()->user()->type == 16) {
            $extends = 'chairpersonportal.layouts.app2';
        } else {
            if (isset($check_refid->refid)) {
                if ($check_refid->resourcepath == null) {
                    $extends = 'general.defaultportal.layouts.app';
                } elseif ($check_refid->refid == 27) {
                    $extends = 'academiccoor.layouts.app2';
                } elseif ($check_refid->refid == 22) {
                    $extends = 'principalcoor.layouts.app2';
                } elseif ($check_refid->refid == 29) {
                    $extends = 'idmanagement.layouts.app2';
                } elseif ($check_refid->refid == 23) {
                    $extends = 'clinic.index';
                } elseif ($check_refid->refid == 24) {
                    $extends = 'clinic_nurse.index';
                } elseif ($check_refid->refid == 25) {
                    $extends = 'clinic_doctor.index';
                } elseif ($check_refid->refid == 26) {
                    $extends = 'hr.layouts.app';
                } elseif ($check_refid->refid == 31) {
                    $extends = 'guidanceV2.layouts.app2';
                } else {
                    $extends = 'general.defaultportal.layouts.app';
                }
            } else {
                $extends = 'general.defaultportal.layouts.app';
            }
        }

        if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait') {
            $id = DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted', '0')->where('isactive', '1')->first()->id;
            $isDepartmentHead = false;

            $checkdepthead = DB::table('hr_departmentheads')
                ->where('deptheadid', $id)
                ->where('deleted', '0')
                ->get();

            if (count($checkdepthead) > 0) {
                $isDepartmentHead = true;
            }
            $isinSignatory = false;

            $checksign = DB::table('sait_leavesignatories')
                ->select('sait_leavesignatories.*', 'teacher.lastname', 'teacher.firstname', 'teacher.suffix')
                ->join('teacher', 'sait_leavesignatories.userid', '=', 'teacher.userid')
                // ->where('sait_leavesignatories.userid',auth()->user()->id)
                ->where('sait_leavesignatories.deleted', '0')
                ->where('teacher.deleted', '0')
                ->orderBy('sait_leavesignatories.id', 'asc')
                ->get();

            if (count($checksign) > 0) {
                $isinSignatory = true;
            }
            $leaveapplications = array();
            if ($isDepartmentHead) {
                foreach ($checkdepthead as $eachdept) {
                    $eachdeptemployees = DB::table('teacher')
                        ->select('sait_leaveapply.*', 'teacher.lastname', 'teacher.firstname', 'teacher.suffix', 'department', 'hr_leaves.leave_type')
                        ->join('sait_leaveapply', 'teacher.userid', '=', 'sait_leaveapply.userid')
                        ->join('hr_departments', 'teacher.schooldeptid', '=', 'hr_departments.id')
                        ->join('hr_leaves', 'sait_leaveapply.leavetypeid', '=', 'hr_leaves.id')
                        ->where('teacher.deleted', '0')
                        ->where('sait_leaveapply.deleted', '0')
                        ->where('schooldeptid', $eachdept->deptid)
                        ->get();

                    if (count($eachdeptemployees) > 0) {
                        foreach ($eachdeptemployees as $eachdeptemployee) {
                            $checkapprecord = DB::table('sait_approvaldetails')
                                ->where('applicationid', $eachdeptemployee->id)
                                ->where('approvaluserid', auth()->user()->id)
                                ->where('deleted', '0')
                                ->first();

                            if ($checkapprecord) {
                                $eachdeptemployee->appstatus = $checkapprecord->appstatus;
                                $eachdeptemployee->appstatusdesc = $checkapprecord->appstatus == 0 ? 'Pending' : ($checkapprecord->appstatus == 1 ? 'Approved' : 'Rejected');
                                $eachdeptemployee->appstatusdate = date('m/d/Y', strtotime($checkapprecord->updateddatetime));
                                $eachdeptemployee->remarks = $checkapprecord->remarks;

                            } else {
                                $eachdeptemployee->appstatus = 0;
                                $eachdeptemployee->appstatusdesc = 'Pending';
                                $eachdeptemployee->appstatusdate = '';
                                $eachdeptemployee->remarks = '';
                            }
                            $eachdeptemployee->signatorylabel = 'Department Head';
                            array_push($leaveapplications, $eachdeptemployee);
                        }
                    }
                }
            }
            // return $checksign;
            if ($isinSignatory) {
                $employees = DB::table('teacher')
                    ->select('sait_leaveapply.*', 'teacher.lastname', 'teacher.firstname', 'teacher.suffix', 'department', 'hr_leaves.leave_type')
                    ->join('sait_leaveapply', 'teacher.userid', '=', 'sait_leaveapply.userid')
                    ->join('hr_departments', 'teacher.schooldeptid', '=', 'hr_departments.id')
                    ->join('hr_leaves', 'sait_leaveapply.leavetypeid', '=', 'hr_leaves.id')
                    ->where('teacher.deleted', '0')
                    ->where('sait_leaveapply.deleted', '0')
                    ->get();

                if (count($employees) > 0) {
                    foreach ($employees as $eachemployee) {
                        $countapprecord = DB::table('sait_approvaldetails')
                            ->select('sait_approvaldetails.*', 'teacher.lastname', 'teacher.firstname')
                            ->where('applicationid', $eachemployee->id)
                            // ->where('approvaluserid', auth()->user()->id)
                            ->join('teacher', 'sait_approvaldetails.approvaluserid', '=', 'teacher.userid')

                            ->where('sait_approvaldetails.deleted', '0')
                            ->where('teacher.deleted', '0')
                            ->get();

                        // if(collect($countapprecord)->where('appstatus','1')->count()>0)
                        // {
                        foreach ($checksign as $eachsign) {
                            if (collect($countapprecord)->where('approvaluserid', $eachsign->userid)->count() > 0) {
                                foreach (collect($countapprecord)->where('approvaluserid', $eachsign->userid)->values() as $eachentry) {
                                    $eachsign->appstatus = $eachentry->appstatus;
                                    $eachsign->appstatusdesc = $eachentry->appstatus == 0 ? 'Pending' : ($eachentry->appstatus == 1 ? 'Approved' : 'Rejected');
                                    $eachsign->appstatusdate = date('m/d/Y', strtotime($eachentry->updateddatetime));
                                    $eachsign->remarks = $eachentry->remarks;
                                    $eachsign->signatorylabel = $eachsign->description;
                                    $eachsign->signatoryname = $eachsign->lastname . ', ' . $eachsign->firstname;

                                    if ($eachentry->approvaluserid == auth()->user()->id) {
                                        $eachemployee->appstatus = $eachentry->appstatus;
                                        $eachemployee->appstatusdesc = $eachentry->appstatus == 0 ? 'Pending' : ($eachentry->appstatus == 1 ? 'Approved' : 'Rejected');
                                        $eachemployee->appstatusdate = date('m/d/Y', strtotime($eachentry->updateddatetime));
                                        $eachemployee->remarks = $eachentry->remarks;
                                        $eachemployee->signatorylabel = $eachsign->description;
                                        $eachemployee->signatoryname = $eachsign->lastname . ', ' . $eachsign->firstname;
                                    }
                                }

                            } else {
                                $eachsign->appstatus = 0;
                                $eachsign->appstatusdesc = 'Pending';
                                $eachsign->appstatusdate = '';
                                $eachsign->remarks = '';
                                $eachsign->signatorylabel = $eachsign->description;
                                $eachsign->signatoryname = $eachsign->lastname . ', ' . $eachsign->firstname;
                                if ($eachsign->userid == auth()->user()->id) {
                                    $eachemployee->appstatus = 0;
                                    $eachemployee->appstatusdesc = 'Pending';
                                    $eachemployee->appstatusdate = '';
                                    $eachemployee->remarks = '';
                                    $eachemployee->signatorylabel = $eachsign->description;
                                    $eachemployee->signatoryname = $eachsign->lastname . ', ' . $eachsign->firstname;
                                }
                            }
                        }
                        $eachemployee->approvals = $checksign;
                        array_push($leaveapplications, $eachemployee);
                        // }
                    }
                }
            }
            // return $leaveapplications;
            // $leaveapplications = collect($leaveapplications)->unique();
            // sait_leavesignatories
            // return count($leaveapplications);
            // dd($isDepartmentHead);

            return view('hr.leaves.sait.index')
                ->with('leaveapplications', $leaveapplications)
                ->with('extends', $extends);

        } else {

            $payrollinfo = DB::table('hr_payrollv2')
                ->where('status', '1')
                ->where('deleted', '0')
                ->first();

            $getMyid = DB::table('teacher')
                ->select('id')
                ->where('userid', auth()->user()->id)
                ->first();

            $hr_approvals = DB::table('hr_leavesappr')
                ->where('deleted', '0')
                ->get();


            $employees = DB::table('teacher')
                ->select('teacher.*')
                ->join('employee_basicsalaryinfo', 'teacher.id', '=', 'employee_basicsalaryinfo.employeeid')
                ->where('teacher.isactive', '1')
                ->where('teacher.deleted', '0')
                ->get();

            // $mydepartment = DB::table('hr_departmentheads')
            //     ->where('deptheadid', )
            $id = DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted', '0')->where('isactive', '1')->first()->id;
            $isDepartmentHead = false;
            $deptids = [];
            $checkdepthead = DB::table('hr_departmentheads')
                ->where('deptheadid', $id)
                ->where('deleted', '0')
                ->get();

            if (count($checkdepthead) > 0) {
                $isDepartmentHead = true;
                $deptids = $checkdepthead->pluck('deptid');
            }

            $appliedleaves = array();
            $leaves = array();

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
                    'hr_leaveemployees.leavestatus',
                    'hr_leaveemployees.createddatetime',
                    'employee_personalinfo.departmentid'

                )
                ->join('teacher', 'hr_leaveemployees.employeeid', '=', 'teacher.id')
                ->join('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                ->join('hr_leaves', 'hr_leaveemployees.leaveid', '=', 'hr_leaves.id')
                ->where('hr_leaves.deleted', '0')
                ->when(isset($request->action) && $request->action == 'myleave', function ($query) use ($request) {
                    return $query->where('teacher.userid', auth()->user()->id);
                })
                ->where('hr_leaveemployees.deleted', '0')
                ->orderByDesc('hr_leaveemployees.createddatetime')
                ->get();


            // if (count($filedleaves) > 0) {
            //     foreach ($filedleaves as $leaveapp) {
            //         $leaveapp->approvercount = 0;

            //         $approvals = DB::table('hr_leavesappr')
            //             ->select('teacher.id', 'teacher.userid', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'hr_leavesappr.appuserid')
            //             ->join('teacher', 'hr_leavesappr.appuserid', '=', 'teacher.userid')
            //             ->where('hr_leavesappr.leaveid', $leaveapp->leaveid)
            //             ->where('hr_leavesappr.deleted', '0')
            //             ->get();

            //         if (count($approvals) > 0) {
            //             foreach ($approvals as $approvalheader) {
            //                 $getapprdata = DB::table('hr_leaveemployeesappr')
            //                     ->where('headerid', $leaveapp->id)
            //                     ->where('appuserid', $approvalheader->appuserid)
            //                     ->where('appstatus', 1)
            //                     ->where('deleted', '0')
            //                     ->first();

            //                 if ($getapprdata) {
            //                     $leaveapp->approvercount++;
            //                 }
            //             }
            //         }
            //     }
            // }

            // $leaveapplicationPending = collect($filedleaves)->whereIn('departmentid', $deptids)->where('approvercount', 0)->values();


            if ($request->has('leavetypeid')) {
                if ($request->get('leavetypeid') > 0) {
                    $filedleaves = collect($filedleaves)->where('leaveid', $request->get('leavetypeid'))->values();
                }
            }

            if (count($filedleaves) > 0) {
                foreach ($filedleaves as $filedleave) {

                    $filedleave->display = 0;

                    if ($filedleave->datefrom == $filedleave->dateto) {
                        $filedleave->duration = date('F j, Y', strtotime($filedleave->datefrom));
                    } else {
                        $start_date = date('F j, Y', strtotime($filedleave->datefrom));
                        $end_date = date('F j, Y', strtotime($filedleave->dateto));
                        $filedleave->duration = $start_date . ' - ' . $end_date;
                    }


                    $filedleave->display = 1;
                    $attachments = array();
                    // DB::table('hr_leaveempattach')
                    //     ->where('headerid', $filedleave->id)
                    //     ->where('deleted','0')
                    // ->get();
                    $approvals = DB::table('hr_leavesappr')
                        ->select('teacher.id', 'teacher.userid', 'teacher.userid', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'hr_leavesappr.appuserid')
                        ->join('teacher', 'hr_leavesappr.appuserid', '=', 'teacher.userid')
                        // ->where('employeeid', $filedleave->employeeid)
                        ->where('leaveid', $filedleave->leaveid)
                        ->where('hr_leavesappr.deleted', '0')
                        ->get();

                    // return $approvals;
                    if (count($approvals) > 0) {

                        foreach ($approvals as $approvalheader) {
                            $getapprdata = DB::table('hr_leaveemployeesappr')
                                ->where('headerid', $filedleave->id)
                                ->where('appuserid', $approvalheader->appuserid)
                                ->where('deleted', '0')
                                ->first();

                            if ($getapprdata) {
                                $approvalheader->remarks = $getapprdata->remarks;
                                $approvalheader->appstatus = $getapprdata->appstatus;
                            } else {
                                $approvalheader->remarks = '';
                                $approvalheader->appstatus = 0;
                            }
                        }

                    }


                    if ($payrollinfo) {
                        $payrollhistory = DB::table('hr_payrollv2historydetail')
                            ->where('payrollid', $payrollinfo->id)
                            ->where('employeeid', $filedleave->employeeid)
                            ->where('employeeleaveid', $filedleave->id)
                            ->where('deleted', '0')
                            ->count();

                        if ($payrollhistory > 0) {
                            $filedleave->display = 0;
                        }
                    }
                    if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc') {
                        $dates = DB::table('employee_leavesdetail')
                            ->select('id', 'ldate', 'dayshift', 'leavestatus')
                            ->where('headerid', $filedleave->id)
                            ->where('deleted', '0')
                            ->get();
                    } else {
                        $dates = DB::table('hr_leaveempdetails')
                            ->select('id', 'ldate', 'dayshift', 'leavestatus', 'remarks', 'halfday')
                            ->where('headerid', $filedleave->id)
                            ->where('deleted', '0')
                            ->get();

                        // return $dates;

                    }

                    $numdays = 0;
                    if (count($dates) > 0) {
                        // return collect($approvals)->where('appstatus','1')->count();
                        foreach ($dates as $date) {
                            $date->daystatus = ' ';

                            if ($date->halfday == 0) {
                                $date->daystatus = 'Whole Day';
                            } else if ($date->halfday == 1) {
                                $date->daystatus = 'AM Half Day';
                            } else if ($date->halfday == 2) {
                                $date->daystatus = 'PM Half Day';
                            }
                            if (collect($approvals)->where('appstatus', '1')->count() == count($approvals)) {

                                if ($date->dayshift == 0) {
                                    $numdays += 1;
                                } else {
                                    $numdays += 0.5;
                                }
                            }
                            if (collect($approvals)->where('appstatus', '0')->count() > 0) {
                                if ($date->dayshift == 0) {
                                    $numdays += 1;
                                } else {
                                    $numdays += 0.5;
                                }
                            }
                        }
                    }

                    // return $approvals;
                    $filedleave->dates = $dates;

                    // return $dates;
                    $filedleave->attachments = $attachments;
                    $filedleave->approvals = $approvals;
                    $filedleave->numdays = $numdays;


                    $numdaysleft = collect($filedleaves)->where('leaveid', $filedleave->id)->sum('numdays');

                    $filedleave->numdaysleft = $numdaysleft;
                }
            }

            $leavetypes = DB::table('hr_leaves')
                ->where('isactive', '1')
                ->where('deleted', '0')
                ->get();


            if ($request->has('leavetypeid')) {
                if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc') {
                    return view('hr.leaves.results')
                        ->with('appliedleaves', $appliedleaves)
                        ->with('filedleaves', $filedleaves)
                        ->with('employees', $employees)
                        ->with('action', $request->action);
                } else {
                    return view('hr.leaves.results_default')
                        ->with('appliedleaves', $appliedleaves)
                        ->with('filedleaves', $filedleaves)
                        ->with('action', $request->action)
                        ->with('employees', $employees);
                }

            } else {
                return view('hr.leaves.index')
                    ->with('extends', $extends)
                    ->with('appliedleaves', $appliedleaves)
                    ->with('filedleaves', $filedleaves)
                    ->with('employees', $employees)
                    ->with('action', $request->action)
                    ->with('leavetypes', $leavetypes);
            }
        }
    }

    public function filedleaves(Request $request)
    {
        $searchValue = $request->input('search.value');
        $start = $request->input('start');
        $length = $request->input('length');

        $payrollinfo = DB::table('hr_payrollv2')
            ->where('status', '1')
            ->where('deleted', '0')
            ->first();

        $getMyid = DB::table('teacher')
            ->select('id')
            ->where('userid', auth()->user()->id)
            ->first();

        $hr_approvals = DB::table('hr_leavesappr')
            ->where('deleted', '0')
            ->get();


        // $employees = DB::table('teacher')
        //     ->select('teacher.*')
        //     ->join('employee_basicsalaryinfo','teacher.id','=','employee_basicsalaryinfo.employeeid')
        //     ->where('teacher.isactive','1')
        //     ->where('teacher.deleted','0')
        //     ->get();


        $appliedleaves = array();
        $leaves = array();


        $query = DB::table('hr_leaveemployees')
            ->select(
                'hr_leaveemployees.id',
                'hr_leaveemployees.employeeid',
                'hr_leaveemployees.leaveid',
                'hr_leaveemployees.leavestatus',
                'hr_leaveemployees.remarks',
                'hr_leaveemployees.createdby',
                'hr_leaves.leave_type',
                'hr_leaves.days',
                'teacher.lastname',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.suffix',
                'teacher.picurl',
                'usertype.utype',
                'hr_leaveemployees.leavestatus',
                'hr_leaveemployees.createddatetime'
            )
            ->join('teacher', 'hr_leaveemployees.employeeid', '=', 'teacher.id')
            ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->join('hr_leaves', 'hr_leaveemployees.leaveid', '=', 'hr_leaves.id')
            ->where('hr_leaves.deleted', '0')
            ->where('hr_leaveemployees.deleted', '0')
            ->orderByDesc('hr_leaveemployees.createddatetime');

        // Apply search filter if there is any search value
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('teacher.lastname', 'like', '%' . $searchValue . '%')
                    ->orWhere('teacher.firstname', 'like', '%' . $searchValue . '%')
                    ->orWhere('hr_leaves.leave_type', 'like', '%' . $searchValue . '%');
            });
        }

        // Get total records
        $recordsTotal = $query->count();
        // Apply pagination and retrieve data
        $filedleaves = $query->skip($start)->take($length)->get();

        if ($request->has('leavetypeid')) {
            if ($request->get('leavetypeid') > 0) {
                $filedleaves = collect($filedleaves)->where('leaveid', $request->get('leavetypeid'))->values();
            }
        }

        if (count($filedleaves) > 0) {
            foreach ($filedleaves as $filedleave) {

                $filedleave->display = 0;

                $filedleave->display = 1;
                $attachments = array();

                $approvals = DB::table('hr_leavesappr')
                    ->select('teacher.id', 'teacher.userid', 'teacher.userid', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'hr_leavesappr.appuserid')
                    ->join('teacher', 'hr_leavesappr.appuserid', '=', 'teacher.userid')
                    ->where('hr_leavesappr.leaveid', $filedleave->leaveid)
                    ->where('hr_leavesappr.deleted', '0')
                    ->get();

                if (count($approvals) > 0) {
                    foreach ($approvals as $approvalheader) {
                        $getapprdata = DB::table('hr_leaveemployeesappr')
                            ->where('headerid', $filedleave->id)
                            ->where('appuserid', $approvalheader->appuserid)
                            ->where('deleted', '0')
                            ->first();

                        if ($getapprdata) {
                            $approvalheader->remarks = $getapprdata->remarks;
                            $approvalheader->appstatus = $getapprdata->appstatus;
                        } else {
                            $approvalheader->remarks = '';
                            $approvalheader->appstatus = 0;
                        }
                    }
                }




                if ($payrollinfo) {
                    $payrollhistory = DB::table('hr_payrollv2historydetail')
                        ->where('payrollid', $payrollinfo->id)
                        ->where('employeeid', $filedleave->employeeid)
                        ->where('employeeleaveid', $filedleave->id)
                        ->where('deleted', '0')
                        ->count();

                    if ($payrollhistory > 0) {
                        $filedleave->display = 0;
                    }
                }

                $dates = DB::table('hr_leaveempdetails')
                    ->select('id', 'ldate', 'dayshift', 'leavestatus', 'remarks', 'halfday')
                    ->where('headerid', $filedleave->id)
                    ->where('deleted', '0')
                    ->get();

                $numdays = 0;
                if (count($dates) > 0) {
                    foreach ($dates as $date) {
                        $date->daystatus = ' ';

                        if ($date->halfday == 0) {
                            $date->daystatus = 'Whole Day';
                        } else if ($date->halfday == 1) {
                            $date->daystatus = 'AM Half Day';
                        } else if ($date->halfday == 2) {
                            $date->daystatus = 'PM Half Day';
                        }
                        if (collect($approvals)->where('appstatus', '1')->count() == count($approvals)) {

                            if ($date->dayshift == 0) {
                                $numdays += 1;
                            } else {
                                $numdays += 0.5;
                            }
                        }
                        if (collect($approvals)->where('appstatus', '0')->count() > 0) {
                            if ($date->dayshift == 0) {
                                $numdays += 1;
                            } else {
                                $numdays += 0.5;
                            }
                        }
                    }
                }

                // return $approvals;
                $filedleave->dates = $dates;

                // return $dates;
                $filedleave->attachments = $attachments;
                $filedleave->approvals = $approvals;
                $filedleave->numdays = $numdays;


                $numdaysleft = collect($filedleaves)->where('leaveid', $filedleave->id)->sum('numdays');

                $filedleave->numdaysleft = $numdaysleft;
            }
        }

        $leavetypes = DB::table('hr_leaves')
            ->where('isactive', '1')
            ->where('deleted', '0')
            ->get();

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,  // Update this if implementing filtering
            'data' => $filedleaves
        ]);
    }

    public function fileleave(Request $request)
    {
        $employeeids = $request->get('employeeids');
        $leaveid = $request->get('leaveid');
        $selecteddates = $request->get('selecteddates');
        $remarks = $request->get('remarks');

        foreach ($employeeids as $employeeid) {
            $checkifexists = DB::table('employee_leaves')
                ->where('employeeid', $employeeid)
                ->where('leaveid', $leaveid)
                ->where('deleted', '0')
                ->get();

            if (count($checkifexists) == 0) {
                $id = DB::table('employee_leaves')
                    ->insertGetId([
                        'employeeid' => $employeeid,
                        'leaveid' => $leaveid,
                        'remarks' => $remarks,
                        'numofdays' => count($selecteddates),
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);

                foreach ($selecteddates as $selecteddate) {
                    DB::table('employee_leavesdetail')
                        ->insert([
                            'headerid' => $id,
                            'ldate' => $selecteddate,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => date('Y-m-d H:i:s')
                        ]);
                }

            } else {
                foreach ($selecteddates as $selecteddate) {
                    $checkdateifexists = DB::table('employee_leavesdetail')
                        ->where('headerid', $checkifexists[0]->id)
                        ->where('ldate', $selecteddate)
                        ->where('deleted', '0')
                        ->get();

                    if (count($checkdateifexists) == 0) {
                        DB::table('employee_leavesdetail')
                            ->insert([
                                'headerid' => $checkifexists[0]->id,
                                'ldate' => $selecteddate,
                                'createdby' => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);
                    }
                }
            }
        }
    }
    public function delete(Request $request)
    {
        $id = $request->get('id');
        try {
            DB::table('employee_leaves')
                ->where('id', $id)
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => date('Y-m-d H:i:s')
                ]);

            DB::table('employee_leavesdetail')
                ->where('headerid', $id)
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => date('Y-m-d H:i:s')
                ]);
            return 1;
        } catch (\Exception $error) {
            return 0;
        }
    }
    public function changestatus(Request $request)
    {
        $id = $request->get('id');
        $status = $request->get('selectedstatus');
        $remarks = $request->get('reason');
        $leavedatesid = $request->get('leaveDatesArray');

        $hrleave = DB::table('hr_leaveemployees')
            ->join('hr_leaves', 'hr_leaveemployees.leaveid', '=', 'hr_leaves.id')
            ->join('teacher', 'hr_leaveemployees.employeeid', '=', 'teacher.id')
            ->where('hr_leaveemployees.id', $id)
            ->first();

        $checkifexists = DB::table('hr_leaveemployeesappr')
            ->where('headerid', $id)
            ->where('deleted', '0')
            ->where('createdby', auth()->user()->id)
            ->first();

        // return collect($hrleave);
        // return $request->all();
        // return collect($checkifexists);
        if ($checkifexists) {

            if ($status == 3) {
                DB::table('hr_leaveemployees')
                    ->where('id', $id)
                    ->update([
                        // 'appstatus'         => $status,
                        'deleted' => 1,
                        // 'remarks' => $remarks,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);


                DB::table('hr_leaveemployeesappr')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'appstatus' => $status,
                        'deleted' => 1,
                        // 'remarks' => $remarks,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);

                NotificationController::sendNotification(
                    $hrleave->leave_type . ' Apply Leave',
                    "Your Application for the period {$hrleave->datefrom} - {$hrleave->dateto} has been remove.",
                    $hrleave->userid,   // Receiver ID (payroll officer)
                    'notification',
                    null,
                    'leaves/apply/index',
                    null
                );
            } else if ($status == 2) {
                DB::table('hr_leaveemployeesappr')
                    ->insert([
                        'headerid' => $id,
                        'remarks' => $remarks,
                        'appuserid' => auth()->user()->id,
                        'appstatus' => $status,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);

                if ($leavedatesid == null) {
                    DB::table('hr_leaveempdetails')
                        ->where('id', $id)
                        ->update([
                            'appstatus' => $status,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => date('Y-m-d H:i:s')
                        ]);
                } else {
                    DB::table('hr_leaveempdetails')
                        ->whereIn('id', $leavedatesid)
                        ->update([
                            'appstatus' => $status,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => date('Y-m-d H:i:s')
                        ]);
                }

                NotificationController::sendNotification(
                    $hrleave->leave_type . ' Apply Leave',
                    "Your leave application for the period {$hrleave->datefrom} to {$hrleave->dateto} has been disapproved.",
                    $hrleave->userid,   // Receiver ID (payroll officer)
                    'notification',
                    null,
                    'leaves/apply/index',
                    null
                );

            } else if ($status == 1) {
                DB::table('hr_leaveemployeesappr')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'appstatus' => $status,
                        'remarks' => $remarks,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);

                if ($leavedatesid == null) {
                    DB::table('hr_leaveempdetails')
                        ->where('headerid', $id)
                        ->update([
                            'appstatus' => $status,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => date('Y-m-d H:i:s')
                        ]);

                } else {
                    DB::table('hr_leaveempdetails')
                        ->where('headerid', $id)
                        ->update([
                            'appstatus' => $status,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => date('Y-m-d H:i:s')
                        ]);
                }

                NotificationController::sendNotification(
                    $hrleave->leave_type . ' Apply Leave',
                    "Your leave application for the period {$hrleave->datefrom} to {$hrleave->dateto} has been approved.",
                    $hrleave->userid,   // Receiver ID (payroll officer)
                    'notification',
                    null,
                    'leaves/apply/index',
                    null
                );
            } else {

                DB::table('hr_leaveemployeesappr')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'appstatus' => $status,
                        'remarks' => $remarks,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);

                if ($leavedatesid == null) {
                    DB::table('hr_leaveempdetails')
                        ->where('headerid', $id)
                        ->update([
                            'appstatus' => $status,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => date('Y-m-d H:i:s')
                        ]);

                } else {
                    DB::table('hr_leaveempdetails')
                        ->where('headerid', $id)
                        ->update([
                            'appstatus' => $status,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => date('Y-m-d H:i:s')
                        ]);
                }

                NotificationController::sendNotification(
                    $hrleave->leave_type . ' Apply Leave',
                    "Your leave application for the period {$hrleave->datefrom} to {$hrleave->dateto} has been reverted to pending status. Please check for any additional updates or actions required.",
                    $hrleave->userid,   // Receiver ID (payroll officer)
                    'notification',
                    null,
                    'leaves/apply/index',
                    null
                );
            }

        } else {
            if ($status == 3) {
                DB::table('hr_leaveemployees')
                    ->where('id', $id)
                    ->update([
                        'deleted' => 1,
                        // 'remarks' => $remarks,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);

                DB::table('hr_leaveemployeesappr')
                    ->where('headerid', $id)
                    ->update([
                        'deleted' => 1,
                        // 'remarks' => $remarks,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);

                NotificationController::sendNotification(
                    $hrleave->leave_type . ' Apply Leave',
                    "Your Application dated {$hrleave->datefrom} - {$hrleave->dateto} has been remove.",
                    $hrleave->userid,   // Receiver ID (payroll officer)
                    'notification',
                    null,
                    'leaves/apply/index',
                    null
                );
            } else if ($status == 2) {
                DB::table('hr_leaveemployeesappr')
                    ->insert([
                        'headerid' => $id,
                        'remarks' => $remarks,
                        'appuserid' => auth()->user()->id,
                        'appstatus' => $status,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);

                if ($leavedatesid == null) {
                    DB::table('hr_leaveempdetails')
                        ->where('id', $id)
                        ->update([
                            'appstatus' => $status,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => date('Y-m-d H:i:s')
                        ]);
                } else {
                    DB::table('hr_leaveempdetails')
                        ->whereIn('id', $leavedatesid)
                        ->update([
                            'appstatus' => $status,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => date('Y-m-d H:i:s')
                        ]);
                }

                NotificationController::sendNotification(
                    $hrleave->leave_type . ' Apply Leave',
                    "Your leave application for the period {$hrleave->datefrom} to {$hrleave->dateto} has been disapproved.",
                    $hrleave->userid,   // Receiver ID (payroll officer)
                    'notification',
                    null,
                    'leaves/apply/index',
                    null
                );

            } else if ($status == 1) {
                DB::table('hr_leaveemployeesappr')
                    ->insert([
                        'headerid' => $id,
                        'remarks' => $remarks,
                        'appuserid' => auth()->user()->id,
                        'appstatus' => $status,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);

                if ($leavedatesid == null) {
                    DB::table('hr_leaveempdetails')
                        ->where('id', $id)
                        ->update([
                            'appstatus' => $status,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => date('Y-m-d H:i:s')
                        ]);
                } else {
                    DB::table('hr_leaveempdetails')
                        ->whereIn('id', $leavedatesid)
                        ->update([
                            'appstatus' => $status,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => date('Y-m-d H:i:s')
                        ]);
                }

                NotificationController::sendNotification(
                    $hrleave->leave_type . ' Apply Leave',
                    "Your leave application for the period {$hrleave->datefrom} to {$hrleave->dateto} has been approved.",
                    $hrleave->userid,   // Receiver ID (payroll officer)
                    'notification',
                    null,
                    'leaves/apply/index',
                    null
                );
            } else {

                DB::table('hr_leaveemployeesappr')
                    ->insert([
                        'headerid' => $id,
                        'remarks' => $remarks,
                        'appuserid' => auth()->user()->id,
                        'appstatus' => $status,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);

                if ($leavedatesid == null) {
                    DB::table('hr_leaveempdetails')
                        ->where('id', $id)
                        ->update([
                            'appstatus' => $status,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => date('Y-m-d H:i:s')
                        ]);
                } else {
                    DB::table('hr_leaveempdetails')
                        ->whereIn('id', $leavedatesid)
                        ->update([
                            'appstatus' => $status,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => date('Y-m-d H:i:s')
                        ]);
                }

                NotificationController::sendNotification(
                    $hrleave->leave_type . ' Apply Leave',
                    "Your leave application for the period {$hrleave->datefrom} to {$hrleave->dateto} has been reverted to pending status. Please check for any additional updates or actions required.",
                    $hrleave->userid,   // Receiver ID (payroll officer)
                    'notification',
                    null,
                    'leaves/apply/index',
                    null
                );


            }



        }
        return 1;
    }
    public function approve(Request $request)
    {
        $id = $request->get('id');
        try {

            // DB::table('employee_leaves')
            // DB::table('employee_leavesdetail')
            //     ->where('id', $id)
            //     ->update([
            //         'leavestatus'           => 1,
            //         'updatedby'             => auth()->user()->id,
            //         'updateddatetime'       => date('Y-m-d H:i:s')
            //     ]);
            // $checkifexists = DB::table('hr_leavesapprdetails')
            //     ->where('employeeleaveid', $id)
            //     ->where('deleted','0')
            //     ->where('createdby',auth()->user()->id)
            //     ->first();

            // if($checkifexists)
            // {
            //     DB::table('hr_leavesapprdetails')
            //         ->where('id', $checkifexists->id)
            //         ->update([
            //             'appstatus'         => 1,
            //             'updatedby'         => auth()->user()->id,
            //             'updateddatetime'   => date('Y-m-d H:i:s')
            //         ]);
            // }else{
            //     DB::table('hr_leavesapprdetails')
            //         ->insert([
            //             'employeeleaveid'    => $id,
            //             'appstatus'          => 1,
            //             'createdby'          => auth()->user()->id,
            //             'createddatetime'    => date('Y-m-d H:i:s')
            //         ]);

            // }
            $checkifexists = DB::table('employee_leavesappr')
                ->where('ldateid', $id)
                ->where('deleted', '0')
                ->where('createdby', auth()->user()->id)
                ->first();

            if ($checkifexists) {
                DB::table('employee_leavesappr')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'appstatus' => 1,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);
            } else {
                DB::table('employee_leavesappr')
                    ->insert([
                        'ldateid' => $id,
                        'appuserid' => auth()->user()->id,
                        'appstatus' => 1,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);

            }
            return 1;
        } catch (\Exception $error) {
            // return $error;
            return 0;
        }
    }
    public function pending(Request $request)
    {
        $id = $request->get('id');
        try {

            // DB::table('employee_leaves')
            // DB::table('employee_leavesdetail')
            //     ->where('id', $id)
            //     ->update([
            //         'leavestatus'           => 0,
            //         'updatedby'             => auth()->user()->id,
            //         'updateddatetime'       => date('Y-m-d H:i:s')
            //     ]);

            // $checkifexists = DB::table('hr_leavesapprdetails')
            //     ->where('employeeleaveid', $id)
            //     ->where('deleted','0')
            //     ->where('createdby',auth()->user()->id)
            //     ->first();
            // if($checkifexists)
            // {
            //     DB::table('hr_leavesapprdetails')
            //         ->where('id', $checkifexists->id)
            //         ->update([
            //             'appstatus'         => 0,
            //             'updatedby'         => auth()->user()->id,
            //             'updateddatetime'   => date('Y-m-d H:i:s')
            //         ]);
            // }else{
            //     DB::table('hr_leavesapprdetails')
            //         ->insert([
            //             'employeeleaveid'    => $id,
            //             'appstatus'          => 0,
            //             'createdby'          => auth()->user()->id,
            //             'createddatetime'    => date('Y-m-d H:i:s')
            //         ]);

            // }
            // return 1;
            if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc') {
                $checkifexists = DB::table('employee_leavesappr')
                    ->where('ldateid', $id)
                    ->where('deleted', '0')
                    ->where('createdby', auth()->user()->id)
                    ->first();

                if ($checkifexists) {
                    DB::table('employee_leavesappr')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'appstatus' => 0,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => date('Y-m-d H:i:s')
                        ]);
                } else {
                    DB::table('employee_leavesappr')
                        ->insert([
                            'ldateid' => $id,
                            'appuserid' => auth()->user()->id,
                            'appstatus' => 0,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => date('Y-m-d H:i:s')
                        ]);

                }
            } else {
                $checkifexists = DB::table('hr_leaveemployeesappr')
                    ->where('headerid', $id)
                    ->where('deleted', '0')
                    ->where('createdby', auth()->user()->id)
                    ->first();

                if ($checkifexists) {
                    DB::table('hr_leaveemployeesappr')
                        ->where('id', $checkifexists->id)
                        ->update([
                            'appstatus' => 0,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => date('Y-m-d H:i:s')
                        ]);
                } else {
                    DB::table('hr_leaveemployeesappr')
                        ->insert([
                            'headerid' => $id,
                            'appuserid' => auth()->user()->id,
                            'appstatus' => 0,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => date('Y-m-d H:i:s')
                        ]);

                }
            }
            return 1;
        } catch (\Exception $error) {
            return 0;
        }
    }
    public function disapprove(Request $request)
    {
        $id = $request->get('id');
        try {

            // DB::table('employee_leaves')
            // DB::table('employee_leavesdetail')
            //     ->where('id', $id)
            //     ->update([
            //         'leavestatus'           => 2,
            //         'updatedby'             => auth()->user()->id,
            //         'updateddatetime'       => date('Y-m-d H:i:s')
            //     ]);
            // $checkifexists = DB::table('hr_leavesapprdetails')
            //     ->where('employeeleaveid', $id)
            //     ->where('deleted','0')
            //     ->where('createdby',auth()->user()->id)
            //     ->first();
            // if($checkifexists)
            // {
            //     DB::table('hr_leavesapprdetails')
            //         ->where('id', $checkifexists->id)
            //         ->update([
            //             'appstatus'         => 2,
            //             'updatedby'         => auth()->user()->id,
            //             'updateddatetime'   => date('Y-m-d H:i:s')
            //         ]);
            // }else{
            //     DB::table('hr_leavesapprdetails')
            //         ->insert([
            //             'employeeleaveid'    => $id,
            //             'appstatus'          => 2,
            //             'createdby'          => auth()->user()->id,
            //             'createddatetime'    => date('Y-m-d H:i:s')
            //         ]);

            // }

            // return 1;
            $checkifexists = DB::table('employee_leavesappr')
                ->where('ldateid', $id)
                ->where('deleted', '0')
                ->where('createdby', auth()->user()->id)
                ->first();

            if ($checkifexists) {
                DB::table('employee_leavesappr')
                    ->where('id', $checkifexists->id)
                    ->update([
                        'appstatus' => 2,
                        'remarks' => $request->get('remarks'),
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => date('Y-m-d H:i:s')
                    ]);
            } else {
                DB::table('employee_leavesappr')
                    ->insert([
                        'ldateid' => $id,
                        'appuserid' => auth()->user()->id,
                        'appstatus' => 2,
                        'remarks' => $request->get('remarks'),
                        'createdby' => auth()->user()->id,
                        'createddatetime' => date('Y-m-d H:i:s')
                    ]);

            }
            return 1;
        } catch (\Exception $error) {
            return 0;
        }
    }
    // public function updatestatus(Request $request)
    // {
    //     return $request->all();
    // }
    public function getleaves(Request $request)
    {
        // Step 1: Fetch applied leaves with related details
        // $leavesappr = DB::table('hr_leaveemployees')
        //     ->select(
        //         'hr_leaves.id',
        //         'hr_leaves.leave_type',
        //         'hr_leaveemployees.id as employeeleaveid',
        //         'hr_leaveemployees.employeeid',
        //         'hr_leaveempdetails.id as ldateid',
        //         'hr_leaveempdetails.ldate',
        //         'hr_leaveempdetails.dayshift',
        //         'hr_leaveempdetails.halfday',
        //         'hr_leaveempdetails.remarks'
        //     )
        //     ->join('hr_leaves', 'hr_leaveemployees.leaveid', '=', 'hr_leaves.id')
        //     ->join('hr_leaveempdetails', 'hr_leaveemployees.id', '=', 'hr_leaveempdetails.headerid')
        //     ->where('hr_leaveemployees.deleted', '0')
        //     ->where('hr_leaveempdetails.deleted', '0')
        //     ->orderByDesc('hr_leaveemployees.createddatetime')
        //     ->groupBy('hr_leaveemployees.id')
        //     ->get();

        // // Initialize arrays for different parts of the result
        // $leaveTypes = [];
        // $leaveCounts = [];
        // $appliedLeaves = [];

        // if ($leavesappr->isNotEmpty()) {
        //     foreach ($leavesappr as $leaveapp) {
        //         // Initialize fields for each leave application
        //         $leaveapp->display = 0;
        //         $leaveapp->approvercount = 0;
        //         $leaveapp->leavestatus = 0;

        //         // Fetch approvers for the current leave application
        //         $approvals = DB::table('hr_leavesappr')
        //             ->select('teacher.id', 'teacher.userid', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'hr_leavesappr.appuserid')
        //             ->join('teacher', 'hr_leavesappr.appuserid', '=', 'teacher.userid')
        //             ->where('hr_leavesappr.leaveid', $leaveapp->id)
        //             ->where('hr_leavesappr.deleted', '0')
        //             ->get();

        //         if ($approvals->isNotEmpty()) {
        //             foreach ($approvals as $approvalheader) {
        //                 $getapprdata = DB::table('hr_leaveemployeesappr')
        //                     ->where('headerid', $leaveapp->employeeleaveid)
        //                     ->where('appuserid', $approvalheader->appuserid)
        //                     ->where('appstatus', 1)
        //                     ->where('deleted', '0')
        //                     ->first();

        //                 if ($getapprdata) {
        //                     $leaveapp->approvercount++;
        //                     $leaveapp->leavestatus = 1;
        //                 }
        //             }
        //         }

        //         // Add to applied leaves array
        //         $appliedLeaves[] = $leaveapp;

        //         // Add leave type if not already added
        //         if (!in_array($leaveapp->leave_type, $leaveTypes)) {
        //             $leaveTypes[] = $leaveapp->leave_type;
        //         }

        //         // Count occurrences of each leave type
        //         if (isset($leaveCounts[$leaveapp->leave_type])) {
        //             $leaveCounts[$leaveapp->leave_type]++;
        //         } else {
        //             $leaveCounts[$leaveapp->leave_type] = 1;
        //         }
        //     }
        // }
        $leavesappr = DB::table('hr_leaveemployees')
            ->select(
                'hr_leaves.id',
                'hr_leaves.leave_type',
                'hr_leaveemployees.employeeid as employeeleaveid',
                'hr_leaveemployees.employeeid',
                'hr_leaveemployees.id as headerid',
                'hr_leaveemployees.datefrom',
                'hr_leaveemployees.dateto',
                'hr_leaveempdetails.id as ldateid',
                'hr_leaveempdetails.ldate',
                'hr_leaveempdetails.dayshift',
                'hr_leaveempdetails.halfday',
                'hr_leaveempdetails.createddatetime',
                'hr_leaveempdetails.remarks'
            )
            ->join('hr_leaves', 'hr_leaveemployees.leaveid', '=', 'hr_leaves.id')
            ->join('hr_leaveempdetails', 'hr_leaveemployees.id', '=', 'hr_leaveempdetails.headerid')
            ->where('hr_leaveemployees.deleted', '0')
            ->where('hr_leaveempdetails.deleted', '0')
            ->orderByDesc('hr_leaveemployees.createddatetime')
            ->groupBy('hr_leaveemployees.id')
            ->get();

        $leaveTypes = [];
        $leaveCounts = [];
        $appliedLeaves = [];
        if (count($leavesappr) > 0) {
            foreach ($leavesappr as $leaveapp) {
                // Initialize fields for each leave application
                $leaveapp->display = 0;
                $leaveapp->approvercount = 0; // Initialize approver count
                $leaveapp->disapprovercount = 0; // Initialize approver count
                $attachments = array();
                $leaveapp->leavestatus = 0;
                $leaveapp->approvers = [];

                // Fetch approvers for the current leave application
                $approvals = DB::table('hr_leavesappr')
                    ->select('teacher.id', 'teacher.userid', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'hr_leavesappr.appuserid')
                    ->join('teacher', 'hr_leavesappr.appuserid', '=', 'teacher.userid')
                    ->where('hr_leavesappr.leaveid', $leaveapp->id)
                    ->where('hr_leavesappr.deleted', '0') // Ensure not deleted
                    ->get();

                if (count($approvals) > 0) {
                    foreach ($approvals as $approvalheader) {
                        // Check if the approval entry exists for this approver and leave application
                        $getapprdata = DB::table('hr_leaveemployeesappr')
                            ->where('headerid', $leaveapp->headerid)
                            ->where('appuserid', $approvalheader->appuserid)
                            ->where('deleted', '0') // Ensure approval entry is not deleted
                            ->first();

                        // Determine approval status
                        $status = 0; // Default: not approved or disapproved
                        if ($getapprdata) {
                            if ($getapprdata->appstatus == 1) {
                                $status = 1; // Approved
                                $leaveapp->approvercount++;
                            } elseif ($getapprdata->appstatus == 2) {
                                $status = 2; // Disapproved
                                $leaveapp->disapprovercount++;
                            }
                        }

                        // Add the approver and their approval status
                        $leaveapp->approvers[] = [
                            'userid' => $approvalheader->userid,
                            'name' => $approvalheader->lastname . ', ' . $approvalheader->firstname . ' ' . ($approvalheader->middlename ?? ''),
                            'status' => $status,
                        ];

                    }
                }

                $appliedLeaves[] = $leaveapp;

                // Add leave type if not already added
                if (!in_array($leaveapp->leave_type, $leaveTypes)) {
                    $leaveTypes[] = $leaveapp->leave_type;
                }

                // Count occurrences of each leave type
                if (isset($leaveCounts[$leaveapp->leave_type])) {
                    $leaveCounts[$leaveapp->leave_type]++;
                } else {
                    $leaveCounts[$leaveapp->leave_type] = 1;
                }
            }
        }

        // Prepare final response structure
        $result = [
            'leave' => array_map(function ($type) {
                return ['type' => $type];
            }, $leaveTypes),
            'count_per_leave' => array_map(function ($type, $count) {
                return ['type' => $type, 'count' => $count];
            }, array_keys($leaveCounts), $leaveCounts),
            'applied_leaves' => $appliedLeaves
        ];

        return $result;
    }



}
