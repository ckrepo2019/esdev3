<?php

namespace App\Http\Controllers\GuidanceController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ReferralController extends \App\Http\Controllers\Controller
{
    public function referral_view(Request $request)
    {
        return view(
            'guidanceV2.pages.referral',
            [
                'current_page' => 'Referral Page',
                'jsonData' => DB::table('guidance_referral')
                    ->where('guidance_referral.deleted', 0)
                    ->join('teacher', 'guidance_referral.referredby', '=', 'teacher.id')
                    ->select(
                        'guidance_referral.*',
                        DB::raw('CONCAT(teacher.lastname, " ", teacher.firstname) AS referredby_fullname'),
                        DB::raw('DATE_FORMAT(guidance_referral.filleddate, "%M %e, %Y") as formatted_filleddate'),
                        DB::raw('DATE_FORMAT(guidance_referral.counselingdate, "%M %e, %Y") as formatted_counselingdate')
                    )
                    ->get()
            ]
        );
    }
    public function referralViewTeaher(Request $request)
    {
        $currentUser = DB::table('teacher')->where('userid', auth()->user()->id)->first();
        return view(
            'guidanceV2.pages.referralteacher',
            [
                'current_page' => 'Referral Page',
                'jsonData' => DB::table('guidance_referral')
                    ->where('guidance_referral.deleted', 0)
                    ->where('guidance_referral.referredby', $currentUser->id)
                    ->join('teacher', 'guidance_referral.referredby', '=', 'teacher.id')
                    ->select(
                        'guidance_referral.*',
                        DB::raw('CONCAT(teacher.lastname, " ", teacher.firstname) AS referredby_fullname'),
                        DB::raw('DATE_FORMAT(guidance_referral.filleddate, "%M %e, %Y") as formatted_filleddate'),
                        DB::raw('DATE_FORMAT(guidance_referral.counselingdate, "%M %e, %Y") as formatted_counselingdate')
                    )
                    ->get()
            ]
        );
    }
    public function referral_form(Request $request)
    {

        return view(
            'guidanceV2.pages.referralform',
            [
                'current_page' => 'Referral Form',
                'jsonData' => []
            ]
        );
    }

    public function referrals()
    {
        return DB::table('guidance_referral')
            ->where('deleted', 0)
            ->select(
                'guidance_referral.*',
                DB::raw('DATE_FORMAT(guidance_referral.filleddate, "%M %e, %Y") as formatted_filleddate'),
                DB::raw('DATE_FORMAT(guidance_referral.counselingdate, "%M %e, %Y") as formatted_counselingdate')
            )
            ->get();
    }
    public function get_referral(Request $request)
    {
        $result = DB::table('guidance_referral')
            ->where('id', $request->id)
            ->where('deleted', 0)
            ->select(
                'guidance_referral.*',
                DB::raw('DATE_FORMAT(guidance_referral.filleddate, "%M %e, %Y") as formatted_filleddate')
            )
            ->first();

        $reasons = DB::table('guidance_referral_info')
            ->where('referralid', $request->id)
            ->where('deleted', 0)
            ->get();

        $result->reasons = $reasons;

        return response()->json($result);
    }

    public function store_referral(Request $request)
    {
        $resultId = 0;
        $validator = Validator::make($request->all(), [
            'studid' => 'required',
            'reasons' => 'required',
            'referredby' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $resultId = DB::table('guidance_referral')
            ->insertGetId(
                [
                    'studid' => $request->studid,
                    'studname' => $request->studname,
                    'filleddate' => $request->filleddate,
                    'referredby' => $request->referredby,
                    // 'counselingdate' => $request->counselingdate,
                    // 'processingofficer' => $request->processingofficer,
                    // 'reason' => $request->reason,
                    // 'counselor' => $request->counselor,
                    'created_at' => now('Asia/Manila'),
                    'updated_at' => now('Asia/Manila'),
                ]
            );

        if ($resultId) {
            $insertData = [];
            $reasonsJson = $request->input('reasons');
            $reasons = json_decode($reasonsJson, true);
            if (!empty($reasons)) {
                $insertData = [];
                foreach ($reasons as $item) {
                    $insertData[] = [
                        'referralid' => $resultId,
                        'reason' => $item['checkboxText'],
                        'description' => $item['inputValue'],
                        'created_at' => now('Asia/Manila'),
                        'updated_at' => now('Asia/Manila'),
                    ];
                }
                DB::table('guidance_referral_info')->insert($insertData);
            }

            return response()->json(['status' => 'success', 'message' => 'Success!',]);
        }

    }

    public function delete_referral(Request $request)
    {
        DB::table('guidance_referral')
            ->where('id', $request->id)
            ->update([
                'deleted' => 1
            ]);

        return response()->json(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }

    public function acknowledge_referral(Request $request)
    {
        DB::table('guidance_referral')
            ->where('id', $request->id)
            ->update([
                'datereceived' => $request->datereceived,
                'counselingdate' => $request->counselingdate,
                'counselor' => $request->receivedby,
                'remarks' => $request->remarks,
                'status' => 1,
            ]);

        return response()->json(['status' => 'success', 'message' => 'Acknowledge Successfully!']);
    }

    public function done_referral(Request $request)
    {
        DB::table('guidance_referral')
            ->where('id', $request->id)
            ->update([
                'status' => 2,
            ]);

        return response()->json(['status' => 'success', 'message' => 'Status Updated!']);
    }
}