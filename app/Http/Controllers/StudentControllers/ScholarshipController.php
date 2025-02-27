<?php

namespace App\Http\Controllers\StudentControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\NotificationController\NotificationController;

class ScholarshipController extends Controller
{

    public function getScholarship(Request $request)
    {

        $studid = DB::table('studinfo')->where('sid', str_replace('S', '', auth()->user()->email))->value('id');


        $studid = DB::table('studinfo')
            ->where('id', $studid)
            ->select('id', 'levelid')
            ->first();

        $data = DB::table('scholarship_applicants')
            ->where('studid', $studid->id)
            ->join('scholarship_setup', function ($join) {
                $join->on('scholarship_setup.id', '=', 'scholarship_applicants.scholarship_setup_id');
                $join->where('scholarship_setup.deleted', 0);
            })
            ->when($request->get('id'), function ($query) use ($request) {
                $query->where('scholarship_applicants.id', $request->get('id'));
            })
            ->select('scholarship_setup.description', 'scholarship_applicants.*')
            ->where('scholarship_applicants.deleted', 0)
            ->get();


        foreach ($data as $item) {

            if ($studid->levelid >= 17 && $studid->levelid <= 22) {

                $item->studstatus = DB::table('college_enrolledstud')
                    ->where('studid', $studid->id)
                    ->value('regStatus');

            } else {

                $item->studstatus = 'N/A';

            }

        }
        return $data;
    }


    public function getRequirement(Request $request)
    {
        return DB::table('scholarship_setup_details')
            ->where('scholarship_setup_id', $request->get('id'))
            ->where('deleted', 0)
            ->get();
    }

    public function delscholarship(Request $request)
    {
        $id = $request->get('id');


        DB::table('scholarship_applicants')
            ->where('id', $id)
            ->update([
                'deleted' => 1
            ]);

        return 1;
    }

    public function saveScholarship(Request $request)
    {

        $scholarshipid = $request->get('scholarship_id');
        $semesterid = $request->get('semesterid');

        $studid = DB::table('studinfo')->where('sid', str_replace('S', '', auth()->user()->email))->value('id');

        $activesy = DB::table('sy')
            ->where('isactive', 1)
            ->value('id');


        $checkifexist = DB::table('scholarship_applicants')
            ->where('studid', $studid)
            ->where('scholarship_setup_id', $scholarshipid)
            ->where('semid', $request->get('semesterid'))
            ->where('syid', $activesy)
            ->where('deleted', 0)
            ->get();


        if (count($checkifexist) == 0) {


            $applicationid = DB::table('scholarship_applicants')
                ->insertGetId([
                    'studid' => $studid,
                    'scholarship_setup_id' => $scholarshipid,
                    'semid' => $semesterid,
                    'syid' => $activesy,
                    'createddatetime' => now()

                ]);



            if ($applicationid > 0) {
                // Retrieve users with specific conditions
                $users = DB::table('users')
                    ->where('deleted', 0)
                    ->where(function ($query) {
                        $query->where('type', 4)
                            ->orWhere('type', 15);
                    })
                    ->select('id', 'type') // Fetch only the necessary columns
                    ->get();

                // Retrieve faspriv data excluding existing user ids
                $users2 = DB::table('faspriv')
                    ->where('deleted', 0)
                    ->where(function ($query) {
                        $query->where('usertype', 4)
                            ->orWhere('usertype', 15);
                    })
                    ->select('userid as id', 'usertype as type') // Align column names with $users
                    ->get();

                // Combine both collections
                $allUsers = $users->merge($users2); // Merge $users and $users2 into a single collection

                // Iterate over all users and send notifications
                foreach ($allUsers as $user) {
                    NotificationController::sendNotification(
                        'Scholarship Request',
                        sprintf(
                            "New Scholarship Request from %s",
                            auth()->user()->name
                        ),
                        $user->id, // Pass receiver_id
                        'notification',
                        'Request',
                        '/finance/scholarship',
                        null,
                        $user->type
                    );
                }
            }



        } else {



            $applicationid = $checkifexist[0]->id;


        }





        $requirementsArray = json_decode($request->get('requirementsArray'));


        foreach ($requirementsArray as $requirement) {

            $checkifexist = DB::table('scholarship_applicant_details')
                ->where('requirement_id', $requirement->dataId)
                ->where('deleted', 0)
                ->get();

            if (count($checkifexist) == 0) {

                DB::table('scholarship_applicant_details')
                    ->insert([
                        'scholarship_applicant_id' => $applicationid,
                        'requirement_id' => $requirement->dataId,
                        'fileurl' => $requirement->value,
                    ]);

            } else {

                DB::table('scholarship_applicant_details')
                    ->where('id', $checkifexist[0]->id)
                    ->update([
                        'fileurl' => $requirement->value,
                    ]);
            }


        }


        $remarks = json_decode($request->get('remarks'));

        // foreach ($remarks as $remark) {

        //     $checkifexist = DB::table('scholarship_applicant_details')
        //         ->where('requirement_id', $remark->dataId)
        //         ->where('deleted', 0)
        //         ->get();

        //     if (count($checkifexist) == 0) {

        //         DB::table('scholarship_applicant_details')
        //             ->insert([
        //                 'scholarship_applicant_id' => $applicationid,
        //                 'requirementid' => $requirement->dataId,
        //                 'fileurl' => $remark->value,
        //             ]);

        //     } else {

        //         DB::table('scholarship_applicant_details')
        //             ->where('id', $checkifexist[0]->id)
        //             ->update([
        //                 'fileurl' => $requirement->value,
        //             ]);
        //     }


        // }

        return 1;
    }


    public function uploadrequirement(Request $request)
    {

        $file = $request->file('file');

        $newFileName = time() . '.' . $file->getClientOriginalExtension();
        ;

        $destinationPath = public_path('scholarship/'); // Adjust the destination path as needed

        $file->move($destinationPath, $newFileName);

        // Store the uploaded file in the public/products directory
        $path = '/scholarship/' . $newFileName;


        return response()->json(['url' => $path], 200);
    }
}
