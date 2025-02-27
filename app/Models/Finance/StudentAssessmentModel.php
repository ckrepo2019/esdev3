<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\FinanceModel;
class StudentAssessmentModel extends Model
{
    public static function allstudents($selectedschoolyear,$selectedsemester,$selectedmonth,$selectedgradelevel,$selectedsection)
    {
        
        $studinfo_1 = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.sid',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.lastname',
                'studinfo.suffix',
                'studinfo.gender',
                'studinfo.studtype',
                'studinfo.contactno',
                // 'studinfo.mol',
                // 'studinfo.grantee as granteeid',
                'sections.id as sectionid',
                'sections.sectionname',
                'gradelevel.id as levelid',
                'gradelevel.levelname',
                'academicprogram.id as acadprogid'
                // 'academicprogram.acadprogcode',
                // 'modeoflearning.description as mol',
                // 'grantee.description as grantee'
                )
            ->join('enrolledstud', 'studinfo.id','=','enrolledstud.studid')
            ->join('sections', 'studinfo.sectionid','=','sections.id')
            ->join('gradelevel', 'sections.levelid','=','gradelevel.id')
            ->leftJoin('academicprogram', 'gradelevel.acadprogid','=','academicprogram.id')
            // ->leftJoin('modeoflearning', 'studinfo.mol','=','modeoflearning.id')
            // ->leftJoin('grantee', 'studinfo.grantee','=','grantee.id')
            ->where('sections.deleted','0')
            ->where('studinfo.deleted','0')
            ->where('enrolledstud.deleted','0')
            ->where('studinfo.studstatus','!=','0')
            ->where('enrolledstud.syid',$selectedschoolyear)
            // ->take(5)
            ->get();
            
        $studinfo_2 = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.sid',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.lastname',
                'studinfo.suffix',
                'studinfo.gender',
                'studinfo.studtype',
                'studinfo.contactno',
                // 'studinfo.mol',
                // 'studinfo.grantee as granteeid',
                'sections.id as sectionid',
                'sections.sectionname',
                'gradelevel.id as levelid',
                'gradelevel.levelname',
                'academicprogram.id as acadprogid'
                // 'academicprogram.acadprogcode',
                // 'academicprogram.id as acadprogid',
                // 'modeoflearning.description as mol',
                // 'grantee.description as grantee'
                )
            ->join('sh_enrolledstud', 'studinfo.id','=','sh_enrolledstud.studid')
            ->join('sections', 'studinfo.sectionid','=','sections.id')
            ->join('gradelevel', 'sections.levelid','=','gradelevel.id')
            ->leftJoin('academicprogram', 'gradelevel.acadprogid','=','academicprogram.id')
            // ->leftJoin('modeoflearning', 'studinfo.mol','=','modeoflearning.id')
            // ->leftJoin('grantee', 'studinfo.grantee','=','grantee.id')
            ->where('sections.deleted','0')
            ->where('studinfo.deleted','0')
            ->where('sh_enrolledstud.deleted','0')
            ->where('studinfo.studstatus','!=','0')
            ->where('sh_enrolledstud.syid',$selectedschoolyear)
            ->where(function($q) use($selectedsemester){
                if($selectedsemester == '')
                {
                    $q->where('sh_enrolledstud.semid', FinanceModel::getSemID());
                }
            })
            // ->take(5)
            ->get();
            
        
            $studinfo_3 = DB::table('studinfo')
                ->select(
                    'studinfo.id',
                    'studinfo.sid',
                    'studinfo.firstname',
                    'studinfo.middlename',
                    'studinfo.lastname',
                    'studinfo.suffix',
                    'studinfo.gender',
                    'studinfo.studtype',
                    'studinfo.contactno',
                    // 'studinfo.grantee as granteeid',
                    'college_enrolledstud.sectionID as sectionid',
                    'college_enrolledstud.courseid',
                    'college_sections.id as sectionid',
                    'college_sections.sectionDesc as sectionname',
                    'gradelevel.levelname',
                    'gradelevel.id as levelid',
                    'academicprogram.id as acadprogid'
                    // 'academicprogram.acadprogcode',
                    // 'modeoflearning.description as mol',
                    // 'grantee.description as grantee'
                    )
                ->join('college_enrolledstud', 'studinfo.id','=','college_enrolledstud.studid')
                ->join('college_sections', 'college_enrolledstud.sectionID','=','college_sections.id')
                ->join('gradelevel', 'college_enrolledstud.yearLevel','=','gradelevel.id')
                ->leftJoin('academicprogram', 'gradelevel.acadprogid','=','academicprogram.id')
                // ->join('academicprogram', 'gradelevel.acadprogid','=','academicprogram.id')
                // ->leftJoin('modeoflearning', 'studinfo.mol','=','modeoflearning.id')
                // ->leftJoin('grantee', 'studinfo.grantee','=','grantee.id')
                ->where('college_sections.deleted','0')
                ->where('studinfo.deleted','0')
                ->where('college_enrolledstud.deleted','0')
                ->where('studinfo.studstatus','!=','0')
                ->where('college_enrolledstud.syid',$selectedschoolyear)
                ->where(function($q) use($selectedsemester){
                    if($selectedsemester == '')
                    {
                        $q->where('college_enrolledstud.semid', FinanceModel::getSemID());
                    }
                })
                // ->take(5)
                ->get();

            $allItems = collect();
            $allItems = $allItems->merge($studinfo_1);
            $allItems = $allItems->merge($studinfo_2);
            $allItems = $allItems->merge($studinfo_3);
            foreach($allItems as $item)
            {
                if($item->levelid > 16)
                {
                    // return collect($item);
                    $collegeenrolledstud = DB::table('college_enrolledstud')
                        ->select('college_enrolledstud.*','college_courses.id as courseid','college_courses.courseabrv')
                        ->leftJoin('college_courses','college_enrolledstud.courseid','=','college_courses.id')
                        ->where('studid', $item->id)
                        ->where('syid', $selectedschoolyear)
                        // ->where('semid', $selectedschoolyear)
                        // ->where('yearLevel', $item->levelid)
                        ->where('studstatus','1')
                        ->where('college_enrolledstud.deleted','0')
                        ->where('college_courses.deleted','0')
                        ->first();

                    if($collegeenrolledstud)
                    {
                        // return collect($collegeenrolledstud);
                        $item->courseid = $collegeenrolledstud->courseid;
                        $item->coursename = $collegeenrolledstud->courseabrv;
                    }else{
                        $item->courseid = null;
                        $item->coursename = null;
                    }

                    // return $collegeenrolledstud;
                }else{
                    $item->courseid = null;
                    $item->coursename = null;
                }
            }
            if($selectedgradelevel != null)
            {
                $allItems = $allItems->where('levelid', $selectedgradelevel);
            }
            if($selectedsection != null)
            {
                $allItems = $allItems->where('sectionid', $selectedsection);
            }
            return $allItems;
    }
    public static function studpayscheddetail($studentid,$syid,$semid,$duemonth)
    {
        // $scheddetailinfo = DB::table('studpayscheddetail')
        //     ->select(Db::raw('SUM(balance) as balance'),Db::raw('SUM(amount) as amount'),Db::raw('SUM(amountpay) as amountpay'),'semid','duedate')
        //     ->where('studid', $studentid)
        //     ->where('syid', $syid)
        //     ->where('deleted','0')
        //     ->groupBy('duedate')
        //     ->get();

        // return $scheddetailinfo;

        $stud = db::table('studinfo')
            ->where('id', $studentid)
            ->first();

        $levelid = $stud->levelid;
        $duedate = '';

        $month = 0;

        if($duemonth == 'january')
        {
            $month = 1;
        }
        elseif($duemonth == 'february')
        {
            $month = 2;
        }
        elseif($duemonth == 'march')
        {
            $month = 3;
        }
        elseif($duemonth == 'april')
        {
            $month = 4;
        }
        elseif($duemonth == 'may')
        {
            $month = 5;
        }
        elseif($duemonth == 'june')
        {
            $month = 6;
        }
        elseif($duemonth == 'july')
        {
            $month = 7;
        }
        elseif($duemonth == 'august')
        {
            $month = 8;
        }
        elseif($duemonth == 'september')
        {
            $month = 9;
        }
        elseif($duemonth == 'october')
        {
            $month = 10;
        }
        elseif($duemonth == 'november')
        {
            $month = 11;
        }
        elseif($duemonth == 'december')
        {
            $month = 12;
        }

        // return $duemonth;

        $scheddetail = DB::table('studpayscheddetail')
            // ->select(Db::raw('SUM(balance) as balance'),Db::raw('SUM(amount) as amount'),Db::raw('SUM(amountpay) as amountpay'),'semid','duedate')
            // ->select(db::raw())
            ->select(db::raw('SUM(balance) as balance, SUM(amount) as amount, SUM(amountpay) as amountpay, semid, duedate'))
            ->where('studid', $studentid)
            ->where('syid', $syid)
            ->where(function($q) use($semid, $levelid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 20)
                {
                    $q->where('semid', $semid);
                }
            })
            ->where('deleted','0')
            // ->whereMonth('duedate', $month)
            ->where(function($q) use($month){
                if($month !=0 || $month != null)
                {
                    $q->whereMonth('duedate', $month);
                }
            })
            ->groupBy('duedate')
            ->orderBy('duedate','asc')
            ->first();

        if($month != 0 || $month != null)
        {
            $duedate = $scheddetail->duedate;
        }
        else
        {
            $duedate = '';
        }

        // return $duedate;



        $scheddetail = DB::table('studpayscheddetail')
            ->select(Db::raw('SUM(balance) as balance'),Db::raw('SUM(amount) as amount'),Db::raw('SUM(amountpay) as amountpay'),'semid','duedate')
            // ->select(db::raw())
            ->where('studid', $studentid)
            ->where('syid', $syid)
            ->where(function($q) use($semid, $levelid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 20)
                {
                    $q->where('semid', $semid);
                }
            })
            ->where('deleted','0')
            // ->where('duedate', '<=',  $duedate)
            ->where(function($q) use($duedate){
                if($duedate != '')
                {
                    $q->where('duedate', '<=',  $duedate);
                }
            })
            ->groupBy('duedate')
            ->orderBy('duedate','asc')
            ->get();

        // return $scheddetail;

        $totalamount = 0;
        $totalamountpay = 0;
        $balance = 0;

        foreach($scheddetail as $detail)
        {   
            $totalamount += $detail->amount;
            $totalamountpay += $detail->amountpay;
            $balance += $detail->balance;

            // $detailmonth = date_format(date_create($detail->duedate), 'n');
            // return $detailmonth;

            // if($month <= $detailmonth)
            // {
                
            // }
        }

        // $scheddetail    = collect($scheddetail)->sortBy('duedate');
        // // return $scheddetail;

        // // if($semid!=null)
        // // {
        // //     $scheddetail = $scheddetail->where('semid', $semid);
        // // }
        // $scheddetail = $scheddetail->sortBy('duedate')->values()->all();
        
        // if($duemonth!=null){
        //     // $duemonth = date('m', strtotime($duemonth));
        //     // return $duemonth;
        //     $monthduedate = collect($scheddetail)->filter(function ($item) use ($duemonth) {
        //         if(date('m', strtotime(data_get($item, 'duedate'))) == $duemonth)
        //         {
        //             return $item;
        //         }
        //         // else
        //         // {
        //         //     return 'aaa';
        //         // }
        //     });
            
        //     if(count($monthduedate) == 0)
        //     {
        //         $duedate = null;
        //     }else{
        //         $duedate = $monthduedate->where('duedate','!=',null)->flatten()[0]->duedate;
        //         // $duedate = $monthduedate->sortBy('duedate')->reverse()->values()[0]->duedate;
        //     }
        //     // return $duedate;
            
        //     $scheddetail = collect($scheddetail)->filter(function ($item) use ($duedate) {
        //         if(data_get($item, 'duedate') == null || data_get($item, 'duedate') <= $duedate)
        //         {
        //             return $item;
        //         }
        //     });
            
        // }
        // // return $scheddetail;
        // // return $studentid;
        //     // return $scheddetail->amount;
        // // try{
        // //     // return $scheddetail->amount;
        // //     $totalamount    = $scheddetail->sum('amount');

        // // }catch(\Exception $error)
        // // {
        // //     return $studentid;
        // // }

        // // if(isset($scheddetail->amount))
        // // {
            
        // $totalamount    = collect($scheddetail)->sum('amount');
        // // }
        // $totalamountpay = collect($scheddetail)->sum('amountpay');
        // $balance        = collect($scheddetail)->sum('balance');
        
        return (object)[
            'totalamount'       => $totalamount,
            'totalamountpay'    => $totalamountpay,
            'balance'           => $balance
        ];
    }
}
