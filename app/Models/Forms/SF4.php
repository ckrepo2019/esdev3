<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Model;
use DB;

class SF4 extends Model
{
    
    public static function generate($year = null, $month = null, $days = null, $syid = null){

        $month = \Carbon\Carbon::create(null , $month)->isoFormat('MM');

        $sections = DB::table('sections')
                        ->join('gradelevel',function($join){
                            $join->on('sections.levelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted',0);
                        })
                        ->leftJoin('sectiondetail',function($join) use($syid){
                            $join->on('sections.id','=','sectiondetail.sectionid');
                            $join->where('sectiondetail.deleted','0');
                            $join->where('syid',$syid);
                        })
                        ->leftJoin('teacher',function($join){
                            $join->on('sectiondetail.teacherid','=','teacher.id');
                            $join->where('teacher.deleted','0');
                            $join->where('teacher.isactive','1');
                        })
                        ->where('sections.deleted',0)
                        ->select('sections.id as sectionid','sectionname','acadprogid','levelname','levelid','sortid','lastname','firstname')
                        ->orderBy('sortid')
                        ->get();

        foreach($sections as $item){
            if($item->acadprogid == 5){
                $item->registered = self::sh_count($year, $month, $item->sectionid , [1,2,4], $syid);


             
                // return $item->registered;
                $item->dropped_out_a = self::sh_count($year, $month, $item->sectionid , [3], $syid);
                $item->dropped_out_b = self::sh_count($year, $month, $item->sectionid , [3], $syid);
                $item->transferred_out_a = self::sh_count($year, $month, $item->sectionid , [5], $syid);
                $item->transferred_out_b = self::sh_count($year, $month, $item->sectionid ,[5], $syid);
                $item->transferred_in_a = self::sh_count($year, $month, $item->sectionid ,[4], $syid);
                $item->transferred_in_b = self::sh_count($year, $month, $item->sectionid , [4], $syid);
                $item->attendance = self::sh_attendance($year, $month, $item->sectionid , [1,2,4] , $days, $item->registered, $syid);
            
                // return self::sh_attendance($year, $month - 1, $item->sectionid , [1,2,4] , $days, $item->registered, $syid);
            
            }else{
                $item->registered = self::count($year, $month, $item->sectionid, [1,2,4], $syid);
                $item->dropped_out_a = self::count($year, $month, $item->sectionid , [3], $syid);
                $item->dropped_out_b = self::count($year, $month,$item->sectionid , [3], $syid);
                $item->transferred_out_a = self::count($year, $month, $item->sectionid , [5], $syid);
                $item->transferred_out_b = self::count($year, $month, $item->sectionid , [5], $syid);
                $item->transferred_in_a = self::count($year, $month, $item->sectionid , [4], $syid);
                $item->transferred_in_b = self::count($year, $month, $item->sectionid , [4], $syid);

                // return collect(self::attendance($year, $month, $item->sectionid , [1,2,4] , $days, $item->registered, $syid));

                $item->attendance = self::attendance($year, $month, $item->sectionid , [1,2,4] , $days, $item->registered, $syid);
               
            }
        }
        // dd($sections);

        return $sections;
    }

    public static function count($year = null, $month = null, $sectionid = null, $status = null, $syid = null){

        $month = \Carbon\Carbon::create(null , $month)->isoFormat('MM');

        $male_reg = DB::table('enrolledstud')
                            ->where('enrolledstud.deleted',0)
                            // ->where('enrolledstud.dateenrolled','like','%'.$year.'-'. $month.'%')
                            ->whereIn('enrolledstud.studstatus',$status)
                            ->where('enrolledstud.sectionid',$sectionid)
                            ->where('enrolledstud.syid',$syid)
                            ->join('studinfo',function($join){
                                $join->on('enrolledstud.studid','=','studinfo.id');
                                $join->where('studinfo.deleted',0);
                            })
                            ->where(function($query){
                                $query->where('gender','Male');
                                $query->where('gender','MALE');
                            })
                            ->count();
                            
        $female_reg = DB::table('enrolledstud')
                            ->where('enrolledstud.deleted',0)
                            // ->where('enrolledstud.dateenrolled','like','%'.$year.'-'. $month.'%')
                            ->whereIn('enrolledstud.studstatus',$status)
                            ->where('enrolledstud.sectionid',$sectionid)
                            ->where('enrolledstud.syid',$syid)
                            ->join('studinfo',function($join){
                                $join->on('enrolledstud.studid','=','studinfo.id');
                                $join->where('studinfo.deleted',0);
                            })
                            ->where(function($query){
                                $query->where('gender','Female');
                                $query->where('gender','FEMALE');
                            })
                            ->count();

        return (object)[
                        'male'=>$male_reg,
                        'female'=>$female_reg,
                        'total'=>$male_reg+$female_reg
                    ];


    }

    public static function sh_count($year = null, $month = null, $sectionid = null, $status = null, $syid = null ){

        $month = \Carbon\Carbon::create(null , $month)->isoFormat('MM');
        $male_reg = DB::table('sh_enrolledstud')
                        // ->select('dateenrolled')
                        ->where('sh_enrolledstud.deleted',0)
                        // ->where('sh_enrolledstud.dateenrolled','like','%'.$year.'-'. $month.'%')
                        // ->where('sh_enrolledstud.dateenrolled','<=',$year.'-'. $month.'-'.date('t', strtotime($year.'-'. $month.'-'.date('t'))))
                        ->whereIn('sh_enrolledstud.studstatus',$status)
                        ->where('sh_enrolledstud.sectionid',$sectionid)
                        ->where('sh_enrolledstud.syid',$syid)
                        ->join('studinfo',function($join){
                            $join->on('sh_enrolledstud.studid','=','studinfo.id');
                            $join->where('studinfo.deleted',0);
                        })
                        ->where(function($query){
                            $query->where('gender','Male');
                            $query->where('gender','MALE');
                        })
                        ->distinct('studid')
                        ->count();
                        // ->get();

        $female_reg = DB::table('sh_enrolledstud')
                        ->where('sh_enrolledstud.deleted',0)
                        // ->where('sh_enrolledstud.dateenrolled','like','%'.$year.'-'. $month.'%')
                        // ->where('sh_enrolledstud.dateenrolled','<=',$year.'-'. $month.'-'.date('t', strtotime($year.'-'. $month.'-'.date('t'))))
                        ->whereIn('sh_enrolledstud.studstatus',$status)
                        ->where('sh_enrolledstud.sectionid',$sectionid)
                        ->where('sh_enrolledstud.syid',$syid)
                        ->join('studinfo',function($join){
                            $join->on('sh_enrolledstud.studid','=','studinfo.id');
                            $join->where('studinfo.deleted',0);
                        })
                        ->where(function($query){
                            $query->where('gender','Female');
                            $query->where('gender','FEMALE');
                        })
                        ->distinct('studid')
                        ->count();
        
        return (object)[
            'male'=>$male_reg,
            'female'=>$female_reg,
            'total'=>$male_reg+$female_reg
        ];

    }

    public static function attendance($year = null, $month = null, $sectionid = null, $status = null, $days, $registered, $syid = null){

        $average_male_attendance = 0;
        $average_female_attendance = 0;
        $getsection = (object)[];
        if( $registered->total != 0){
           

            if($month == '01')
            {
                $lastmonth = '12';
                $lastyear = (int)$year - 1;
            }else{
                $lastmonth = $month - 1;
                $lastyear = (int)$year - 1;
            }
           

            $getstudents = DB::table('enrolledstud')
                            ->select('enrolledstud.*', DB::raw('LOWER(`gender`) as gender'))
                            ->join('studinfo','enrolledstud.studid','=','studinfo.id')
                            ->where('enrolledstud.sectionid', $sectionid)
                            ->where('enrolledstud.syid',$syid)
                            ->where('enrolledstud.deleted','0')
                            ->where('enrolledstud.studstatus','!=','0')
                            ->where('enrolledstud.studstatus','!=','6')
                            ->get();
                    
         
            $sectionsetup = DB::table('sf2_setup')
                            ->select('sf2_setup.*','sh_strand.strandname','sh_strand.strandcode')
                            ->leftJoin('sh_strand','sf2_setup.strandid','=','sh_strand.id')
                            ->where('sf2_setup.deleted','0')
                            ->where('sf2_setup.syid', $syid)
                            ->where('sf2_setup.month',$month)
                            ->where('sf2_setup.year',$year)
                            ->where('sf2_setup.sectionid',$sectionid)
                            ->get();

        
                                
            $studattendance = DB::table('studattendance')
                            ->whereIn('studid', collect($getstudents)->pluck('studid'))
                            ->whereMonth('tdate',$month)
                            ->whereYear('tdate',$year)
                            ->where('studattendance.syid', $syid)
                            ->where('deleted','0')
                            ->get();
        
            $data = array();
            $dates = array();
            
            

            if(count($sectionsetup)>0)
            {
                $dates = DB::table('sf2_setupdates')
                    ->where('setupid', $sectionsetup[0]->id)
                    ->where('deleted','0')
                    ->orderBy('dates','asc')
                    ->get();

                
                if(count($dates)>0)
                {
                    foreach($dates as $eachdate)
                    {                                        
                        $presentmale = 0;
                        $presentfemale = 0;

                        if(count($getstudents)>0)
                        {
                            foreach($getstudents as $eachstudent)
                            {
                                $eachday = collect($studattendance)
                                    ->where('studid', $eachstudent->studid)
                                    ->where('tdate',$year.'-'.str_pad($month,2,0,STR_PAD_LEFT).'-'.date('d',strtotime($eachdate->dates)))
                                    ->where('deleted','0')
                                    ->first();

                                // return $year.'-'.str_pad($month,2,0,STR_PAD_LEFT).'-'.date('d',strtotime($eachdate->dates));
                                // return collect($eachday);

                                if($eachday)
                                {
                                    if($eachday->present == 1 || $eachday->tardy == 1 ||$eachday->cc == 1 )
                                    {
                                        if($eachstudent->gender == 'male')
                                        {
                                            $presentmale+=1;
                                        }elseif($eachstudent->gender == 'female')
                                        {
                                            $presentfemale+=1;
                                        }
                                    }
                                    else if($eachday->presentam == 1 || $eachday->presentpm == 1 || $eachday->absentam == 1 || $eachday->absentpm == 1 )
                                    {
                                        if($eachstudent->gender == 'male')
                                        {
                                            $presentmale+=.5;
                                        }elseif($eachstudent->gender == 'female')
                                        {
                                            $presentfemale+=.5;
                                        }
                                    }

                                   
                                }
                            }
                        }

                        // return $getstudents;
                        $eachdate->presentmale = $presentmale;
                        $eachdate->presentfemale = $presentfemale;
                    }                                        
                }                                        
            }

            $getsection->countdates =  count($dates);
            $getsection->presentmale =  collect($dates)->sum('presentmale');
            
            $getsection->presentfemale =  collect($dates)->sum('presentfemale');

            $getsection->registeredmale = collect($getstudents)->where('sectionid',$sectionid)->whereIn('studstatus',[1,2,4])->where('gender','male')->count();
            $getsection->registeredfemale = collect($getstudents)->where('sectionid',$sectionid)->whereIn('studstatus',[1,2,4])->where('gender','female')->count();
            
            $getsection->nlpa_a_m = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',3)->where('gender','male')->whereBetween('studstatdate', [$lastyear.'-'.$lastmonth.'-01',$lastyear.'-'.$lastmonth.'-'.date('t')])->count();
            $getsection->nlpa_a_f = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',3)->where('gender','female')->whereBetween('studstatdate', [$lastyear.'-'.$lastmonth.'-01',$lastyear.'-'.$lastmonth.'-'.date('t')])->count();
            $getsection->nlpa_b_m = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',3)->where('gender','female')->whereBetween('studstatdate', [$year.'-'.$month.'-01',$year.'-'.$month.'-'.date('t')])->count();
            $getsection->nlpa_b_f = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',3)->where('gender','female')->whereBetween('studstatdate', [$year.'-'.$month.'-01',$year.'-'.$month.'-'.date('t')])->count();
            
            $getsection->to_a_m = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',5)->where('gender','male')->whereBetween('studstatdate', [$lastyear.'-'.$lastmonth.'-01',$lastyear.'-'.$lastmonth.'-'.date('t')])->count();
            $getsection->to_a_f = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',5)->where('gender','female')->whereBetween('studstatdate', [$lastyear.'-'.$lastmonth.'-01',$lastyear.'-'.$lastmonth.'-'.date('t')])->count();
            $getsection->to_b_m = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',5)->where('gender','female')->whereBetween('studstatdate', [$year.'-'.$month.'-01',$year.'-'.$month.'-'.date('t')])->count();
            $getsection->to_b_f = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',5)->where('gender','female')->whereBetween('studstatdate', [$year.'-'.$month.'-01',$year.'-'.$month.'-'.date('t')])->count();

            $getsection->ti_a_m = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',4)->where('gender','male')->whereBetween('studstatdate', [$lastyear.'-'.$lastmonth.'-01',$lastyear.'-'.$lastmonth.'-'.date('t')])->count();
            $getsection->ti_a_f = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',4)->where('gender','female')->whereBetween('studstatdate', [$lastyear.'-'.$lastmonth.'-01',$lastyear.'-'.$lastmonth.'-'.date('t')])->count();
            $getsection->ti_b_m = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',4)->where('gender','female')->whereBetween('studstatdate', [$year.'-'.$month.'-01',$year.'-'.$month.'-'.date('t')])->count();
            $getsection->ti_b_f = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',4)->where('gender','female')->whereBetween('studstatdate', [$year.'-'.$month.'-01',$year.'-'.$month.'-'.date('t')])->count();

        }


        if( $registered->total != 0  && $getsection->countdates > 0 && $getsection->registeredmale > 0 &&$getsection->registeredfemale > 0 ){

            
            $ptm_m = number_format((( number_format($getsection->presentmale/$getsection->countdates,2))/$getsection->registeredmale)*100,2);
            $ptm_f = number_format((( number_format($getsection->presentfemale/$getsection->countdates,2))/$getsection->registeredfemale)*100,2);

            return (object)[
                'data' => $getsection,
                'male'=>number_format($getsection->presentmale/$getsection->countdates,2),
                'female'=>number_format($getsection->presentfemale/$getsection->countdates,2),
                'total'=>number_format(((($getsection->presentmale/$getsection->countdates)+($getsection->presentfemale/$getsection->countdates))/2),2),
                'ptm_m'=>number_format((( number_format($getsection->presentmale/$getsection->countdates,2))/$getsection->registeredmale)*100,2),
                'ptm_f'=>number_format((( number_format($getsection->presentfemale/$getsection->countdates,2))/$getsection->registeredfemale)*100,2),
                'ptm_t'=> number_format(($ptm_m + $ptm_f) / 2,2)  ];
        }else{
            return (object)[
                'data' => $getsection,
                'male'=>0,
                'female'=>0,
                'total'=>0,
                'ptm_m'=>0,
                'ptm_f'=>0,
                'ptm_t'=>0
            ];
        }

    }


    public static function sh_attendance($year = null, $month = null, $sectionid = null, $status = null, $days, $registered, $syid = null){



        $average_male_attendance = 0;
        $average_female_attendance = 0;
        $getsection = (object)[];
        if( $registered->total != 0){
           

            if($month == '01')
            {
                $lastmonth = '12';
                $lastyear = (int)$year - 1;
            }else{
                $lastmonth = $month - 1;
                $lastyear = (int)$year - 1;
            }
           

            $getstudents = DB::table('sh_enrolledstud')
                            // ->select('sh_enrolledstud.*', DB::raw('LOWER(`gender`) as gender'))
                            ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id')
                            ->where('sh_enrolledstud.sectionid', $sectionid)
                            ->where('sh_enrolledstud.syid',$syid)
                            ->where('sh_enrolledstud.deleted','0')
                            ->where('sh_enrolledstud.studstatus','!=','0')
                            ->where('sh_enrolledstud.studstatus','!=','6')
                            ->select(
                                'studid',
                                DB::raw('LOWER(`gender`) as gender'),
                                'sh_enrolledstud.sectionid',
                                'sh_enrolledstud.studstatus',
                                'sh_enrolledstud.studstatdate'
                            )
                            ->distinct('studid')
                            ->get();

          
                    
         
            $sectionsetup = DB::table('sf2_setup')
                            ->select('sf2_setup.*','sh_strand.strandname','sh_strand.strandcode')
                            ->leftJoin('sh_strand','sf2_setup.strandid','=','sh_strand.id')
                            ->where('sf2_setup.deleted','0')
                            ->where('sf2_setup.syid', $syid)
                            ->where('sf2_setup.month',$month)
                            ->where('sf2_setup.year',$year)
                            ->where('sf2_setup.sectionid',$sectionid)
                            ->get();
                                
            $studattendance = DB::table('studattendance')
                            ->whereIn('studid', collect($getstudents)->pluck('studid'))
                            ->whereMonth('tdate',$month)
                            ->whereYear('tdate',$year)
                            ->where('studattendance.syid', $syid)
                            ->where('deleted','0')
                            ->get();
        
            $data = array();
            $dates = array();
            
            

            if(count($sectionsetup)>0)
            {
                $dates = DB::table('sf2_setupdates')
                    ->where('setupid', $sectionsetup[0]->id)
                    ->where('deleted','0')
                    ->orderBy('dates','asc')
                    ->get();

                
                if(count($dates)>0)
                {
                    foreach($dates as $eachdate)
                    {                                        
                        $presentmale = 0;
                        $presentfemale = 0;

                        if(count($getstudents)>0)
                        {
                            foreach($getstudents as $eachstudent)
                            {
                                $eachday = collect($studattendance)
                                    ->where('studid', $eachstudent->studid)
                                    ->where('tdate',$year.'-'.str_pad($month,2,0,STR_PAD_LEFT).'-'.date('d',strtotime($eachdate->dates)))
                                    ->where('deleted','0')
                                    ->first();

                                // return $year.'-'.str_pad($month,2,0,STR_PAD_LEFT).'-'.date('d',strtotime($eachdate->dates));
                                // return collect($eachday);

                                if($eachday)
                                {
                                    if($eachday->present == 1 || $eachday->tardy == 1 ||$eachday->cc == 1 )
                                    {
                                        if($eachstudent->gender == 'male')
                                        {
                                            $presentmale+=1;
                                        }elseif($eachstudent->gender == 'female')
                                        {
                                            $presentfemale+=1;
                                        }
                                    }
                                    else if($eachday->presentam == 1 || $eachday->presentpm == 1 || $eachday->absentam == 1 || $eachday->absentpm == 1 )
                                    {
                                        if($eachstudent->gender == 'male')
                                        {
                                            $presentmale+=.5;
                                        }elseif($eachstudent->gender == 'female')
                                        {
                                            $presentfemale+=.5;
                                        }
                                    }
                                }
                            }
                        }

                        // return $getstudents;
                        $eachdate->presentmale = $presentmale;
                        $eachdate->presentfemale = $presentfemale;
                    }                                        
                }                                        
            }

            // return $dates;
            
           
            // $getsection->data =  $data;
            // $getsection->dates =  $dates;
            $getsection->countdates =  count($dates);
            $getsection->presentmale =  collect($dates)->sum('presentmale');
            
            
            
            $getsection->presentfemale =  collect($dates)->sum('presentfemale');

            $getsection->registeredmale = collect($getstudents)->where('sectionid',$sectionid)->whereIn('studstatus',[1,2,4])->where('gender','male')->count();
            $getsection->registeredfemale = collect($getstudents)->where('sectionid',$sectionid)->whereIn('studstatus',[1,2,4])->where('gender','female')->count();

            
            $getsection->nlpa_a_m = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',3)->where('gender','male')->whereBetween('studstatdate', [$lastyear.'-'.$lastmonth.'-01',$lastyear.'-'.$lastmonth.'-'.date('t')])->count();
            $getsection->nlpa_a_f = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',3)->where('gender','female')->whereBetween('studstatdate', [$lastyear.'-'.$lastmonth.'-01',$lastyear.'-'.$lastmonth.'-'.date('t')])->count();
            $getsection->nlpa_b_m = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',3)->where('gender','female')->whereBetween('studstatdate', [$year.'-'.$month.'-01',$year.'-'.$month.'-'.date('t')])->count();
            $getsection->nlpa_b_f = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',3)->where('gender','female')->whereBetween('studstatdate', [$year.'-'.$month.'-01',$year.'-'.$month.'-'.date('t')])->count();
            
            $getsection->to_a_m = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',5)->where('gender','male')->whereBetween('studstatdate', [$lastyear.'-'.$lastmonth.'-01',$lastyear.'-'.$lastmonth.'-'.date('t')])->count();
            $getsection->to_a_f = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',5)->where('gender','female')->whereBetween('studstatdate', [$lastyear.'-'.$lastmonth.'-01',$lastyear.'-'.$lastmonth.'-'.date('t')])->count();
            $getsection->to_b_m = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',5)->where('gender','female')->whereBetween('studstatdate', [$year.'-'.$month.'-01',$year.'-'.$month.'-'.date('t')])->count();
            $getsection->to_b_f = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',5)->where('gender','female')->whereBetween('studstatdate', [$year.'-'.$month.'-01',$year.'-'.$month.'-'.date('t')])->count();

            $getsection->ti_a_m = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',4)->where('gender','male')->whereBetween('studstatdate', [$lastyear.'-'.$lastmonth.'-01',$lastyear.'-'.$lastmonth.'-'.date('t')])->count();
            $getsection->ti_a_f = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',4)->where('gender','female')->whereBetween('studstatdate', [$lastyear.'-'.$lastmonth.'-01',$lastyear.'-'.$lastmonth.'-'.date('t')])->count();
            $getsection->ti_b_m = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',4)->where('gender','female')->whereBetween('studstatdate', [$year.'-'.$month.'-01',$year.'-'.$month.'-'.date('t')])->count();
            $getsection->ti_b_f = collect($getstudents)->where('sectionid',$sectionid)->where('studstatus',4)->where('gender','female')->whereBetween('studstatdate', [$year.'-'.$month.'-01',$year.'-'.$month.'-'.date('t')])->count();

        }

       
        if( $registered->total != 0){
            try{
                $ptm_m = number_format((( number_format($getsection->presentmale/$getsection->countdates,2))/$getsection->registeredmale)*100,2);
                $ptm_f = number_format((( number_format($getsection->presentfemale/$getsection->countdates,2))/$getsection->registeredfemale)*100,2);

                return (object)[
                    'data' => $getsection,
                    'male'=>number_format($getsection->presentmale/$getsection->countdates,2),
                    'female'=>number_format($getsection->presentfemale/$getsection->countdates,2),
                    'total'=>number_format(((($getsection->presentmale/$getsection->countdates)+($getsection->presentfemale/$getsection->countdates))/2),2),
                    'ptm_m'=>number_format((( number_format($getsection->presentmale/$getsection->countdates,2))/$getsection->registeredmale)*100,2),
                    'ptm_f'=>number_format((( number_format($getsection->presentfemale/$getsection->countdates,2))/$getsection->registeredfemale)*100,2),
                    'ptm_t'=> number_format(($ptm_m + $ptm_f) / 2,2)  ];
            }catch(\Exception $e){
                return (object)[
                    'data' => $getsection,
                    'male'=>0,
                    'female'=>0,
                    'total'=>0,
                    'ptm_m'=>0,
                    'ptm_f'=>0,
                    'ptm_t'=>0
                ];
            }
            
        }else{
            return (object)[
                'data' => $getsection,
                'male'=>0,
                'female'=>0,
                'total'=>0,
                'ptm_m'=>0,
                'ptm_f'=>0,
                'ptm_t'=>0
            ];
        }
        

    }
    
    public static function get_calendar($month = null,$year = null){

        $list=array();
        $today = date("d"); // Current day

        function draw_calendar($month,$year){

            /* draw table */
            $calendar = '<table class="table-bordered" style="width: 100%;">';
        
            /* table headings */
            $headings = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
            $calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';
        
            /* days and weeks vars now ... */
            $running_day = date('w',mktime(0,0,0,$month,1,$year));
            $days_in_month = date('t',mktime(0,0,0,$month,1,$year));
            $days_in_this_week = 1;
            $day_counter = 0;
            $dates_array = array();
        
            /* row for week one */
            $calendar.= '<tr class="calendar-row">';
        
            /* print "blank" days until the first of the current week */
            for($x = 0; $x < $running_day; $x++):
                $calendar.= '<td class="calendar-day-np"> </td>';
                $days_in_this_week++;
            endfor;
        
            /* keep going with days.... */
            for($list_day = 1; $list_day <= $days_in_month; $list_day++):
                $calendar.= '<td class="calendar-day active-date align-middle" data-id="'.$list_day.'">';
                    /* add in the day number */
                    $calendar.= '<div class="day-number"><a class="btn btn-block "  data-id="'.$list_day.'">'.$list_day.'</a></div>';
        
                    /** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
                    $calendar.= str_repeat('<p> </p>',2);
                    
                $calendar.= '</td>';
                if($running_day == 6):
                    $calendar.= '</tr>';
                    if(($day_counter+1) != $days_in_month):
                        $calendar.= '<tr class="calendar-row">';
                    endif;
                    $running_day = -1;
                    $days_in_this_week = 0;
                endif;
                $days_in_this_week++; $running_day++; $day_counter++;
            endfor;
        
            /* finish the rest of the days in the week */
            if($days_in_this_week < 8):
                for($x = 1; $x <= (8 - $days_in_this_week); $x++):
                    $calendar.= '<td class="calendar-day-np"> </td>';
                endfor;
            endif;
        
            /* final row */
            $calendar.= '</tr>';
        
            /* end the table */
            $calendar.= '</table>';
            
            /* all done, return result */
            return $calendar;
        }
        
        /* sample usages */
        echo '<h2><center>' . date('F Y', strtotime($year.'-'.$month)) . '</center></h2>';
        return draw_calendar($month,$year);


    }



}
