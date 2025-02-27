<?php

namespace App\Http\Controllers\GuidanceController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class CounselController extends \App\Http\Controllers\Controller
{
    public function counsel_view(Request $request)
    {
        $appointments = DB::table('guidance_counseling_appointment')
            ->where('guidance_counseling_appointment.deleted', 0)
            ->join('studinfo', 'guidance_counseling_appointment.studid', '=', 'studinfo.id')
            ->select(
                'studinfo.sid',
                'guidance_counseling_appointment.*',
                DB::raw('DATE_FORMAT(guidance_counseling_appointment.filleddate, "%M %e, %Y") as formatted_filleddate'),
                DB::raw('DATE_FORMAT(guidance_counseling_appointment.counselingdate, "%M %e, %Y") as formatted_counselingdate')
            )->get();

        return view(
            'guidanceV2.pages.counseling',
            [
                'current_page' => $request->page,
                'jsonData' => $appointments,
            ]
        );
    }

    public function store_appointment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'studid' => 'required',
            'counselingdate' => 'required',
            'reason' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $resultId = DB::table('guidance_counseling_appointment')
            ->insertGetId([
                'studid' => $request->studid,
                'studname' => $request->studname,
                'filleddate' => $request->filleddate,
                'counselingdate' => $request->counselingdate,
                'processingofficer' => $request->processingofficer,
                'reason' => $request->reason,
                'counselor' => $request->counselor,
                'created_at' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
            ]);

        if ($resultId) {
            return response()->json(['status' => 'success', 'message' => 'Appointment Successful!']);
        }
    }

    public function store_referral_setup(Request $request)
    {
        $resultId = 0;
        $validator = Validator::make($request->all(), [
            'privacyclause' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $returnId = DB::table('guidance_referral_setup')->first();
        if ($returnId->id) {
            DB::table('guidance_referral_setup')
                ->where('id', $returnId->id)
                ->update(
                    [
                        'privacyclause' => $request->privacyclause,
                        'reasons' => $request->reasons,
                        'remarks' => $request->remarks
                    ]
                );
            return response()->json(['status' => 'success', 'message' => 'Setup Updated!',]);
        } else {
            $resultId = DB::table('guidance_referral_setup')->insertGetId(
                [
                    'privacyclause' => $request->privacyclause,
                    'reasons' => $request->reasons,
                    'remarks' => $request->remarks
                ]
            );

            if ($resultId) {
                return response()->json(['status' => 'success', 'message' => 'Success!',]);
            }
        }

    }

    public function get_referral_setup()
    {
        return response()->json(DB::table('guidance_referral_setup')->first());
    }
    public function get_student(Request $request)
    {
        $returnId = DB::table('studinfo')
            ->where('studinfo.id', $request->id)
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->select(
                'studinfo.*',
                'gradelevel.levelname'
            )
            ->first();

        return response()->json($returnId);
    }
    public function get_teacher(Request $request)
    {
        $returnId = DB::table('teacher')
            ->where('teacher.id', $request->id)
            ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->select(
                'teacher.*',
                'usertype.utype'
            )
            ->first();

        return response()->json($returnId);
    }

    public function getAllEvalCriteria()
    {
        return response()->json(DB::table('guidance_eval_setup')->where('deleted', 0)->get());
    }
    public function storeEvalCriteria(Request $request)
    {
        $returnId = DB::table('guidance_eval_setup')->insertGetId($request->all());
        if ($returnId) {
            return response()->json(['status' => 'success', 'message' => 'Success!']);
        }

    }

    public function storeLikert(Request $request)
    {
        $returnId = DB::table('guidance_likert_scale')->insertGetId($request->all());
        if ($returnId) {
            return response()->json(['status' => 'success', 'message' => 'Success!']);
        }

    }

    public function removeLikert(Request $request)
    {
        DB::table('guidance_likert_scale')->where('id', $request->id)->update(['deleted' => 1]);

        return response()->json(['status' => 'success', 'message' => 'Success!']);
    }

    public function getAllEvalLikert()
    {
        return response()->json(DB::table('guidance_likert_scale')->where('deleted', 0)->orderBy('sorting')->get());
    }

    public function removeCriteria(Request $request)
    {
        DB::table('guidance_eval_setup')->where('id', $request->id)->update(['deleted' => 1]);

        return response()->json(['status' => 'success', 'message' => 'Success!']);
    }

}