<?php

namespace App\Http\Controllers\TeacherControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
class SummaryOfLoadController extends Controller
{
    public function summaryofloads($id, Request $request)
    {

        $sem = DB::table('semester')
        ->where('isactive','1')
        ->first();
        
        
        $teacherid = DB::table('teacher')
                    ->select('id')
                    ->where('tid',auth()->user()->email)
                    ->first();


      
        $syid = $request->get('syid');
        $semid = $request->get('semid');
            
        $mondayArray = array();
        $tuesdayArray = array();
        $wednesdayArray = array();
        $thursdayArray = array();
        $fridayArray = array();
        $saturdayArray = array();

        $assignsubjLower = DB::table('assignsubjdetail')
                ->select(
                    'gradelevel.levelname',
                    'sections.sectionname',
                    'assignsubjdetail.subjid',
                    'subjects.subjdesc',
                    'classscheddetail.days as day',
                    'days.description',
                    'classscheddetail.stime',
                    'classscheddetail.etime',
                    'rooms.roomname',
                    'schedclassification.description as scheddesc'
                    )
                ->join('assignsubj',function($join) use($syid){
                    $join->on('assignsubjdetail.headerid','assignsubj.id');
                    $join->where('assignsubj.deleted',0);
                    $join->where('assignsubj.syid',$syid);
                })
                ->join('classsched',function($join) use($syid){
                    $join->on('assignsubj.sectionid','=','classsched.sectionid');
                    $join->on('assignsubjdetail.subjid','=','classsched.subjid');
                    $join->where('classsched.deleted',0);
                    $join->where('classsched.syid',$syid);
                })
                ->join('classscheddetail','classsched.id','=','classscheddetail.headerid')
                ->leftJOin('rooms',function($join) use($syid){
                    $join->on('classscheddetail.roomid','=','rooms.id');
                    $join->where('rooms.deleted',0);
                })
                ->leftJOin('schedclassification',function($join) use($syid){
                    $join->on('classscheddetail.classification','=','schedclassification.id');
                    $join->where('schedclassification.deleted',0);
                })
                ->join('gradelevel','assignsubj.glevelid','gradelevel.id')
                ->join('sections','assignsubj.sectionid','sections.id')
                ->join('subjects','classsched.subjid','subjects.id')
                ->join('days','classscheddetail.days','days.id')
                ->where('assignsubjdetail.teacherid',$teacherid->id)
                ->where('assignsubjdetail.deleted','0')
                ->where('classscheddetail.deleted','0')
                ->where('gradelevel.deleted','0')
                ->where('sections.deleted','0')
                ->where('subjects.deleted','0')
                ->distinct()
                ->get();

        foreach($assignsubjLower as $subjectassignment){
            
            foreach($subjectassignment as $key => $item)
            {
                if($key == 'stime'){
                    $subjectassignment->stime = date('h:i:s A', strtotime($subjectassignment->stime));
                }
                elseif($key == 'etime'){
                    $subjectassignment->etime = date('h:i:s A', strtotime($subjectassignment->etime));
                }
            }
            if($subjectassignment->description == "Monday"){
                array_push($mondayArray,$subjectassignment);
            }
            elseif($subjectassignment->description == "Tuesday"){
                array_push($tuesdayArray,$subjectassignment);
            }
            elseif($subjectassignment->description == "Wednesday"){
                array_push($wednesdayArray,$subjectassignment);
            }
            elseif($subjectassignment->description == "Thursday"){
                array_push($thursdayArray,$subjectassignment);
            }
            elseif($subjectassignment->description == "Friday"){
                array_push($fridayArray,$subjectassignment);
            }
            elseif($subjectassignment->description == "Saturday"){
                array_push($saturdayArray,$subjectassignment);
            }
        }
        
        $assignsubjHigher = DB::table('sh_classsched')
                                ->select('gradelevel.levelname','sections.sectionname','sh_subjects.subjtitle as subjdesc','sh_classscheddetail.day','days.description','sh_classscheddetail.stime','sh_classscheddetail.etime','rooms.roomname')
                                ->join('sh_classscheddetail','sh_classsched.id','=','sh_classscheddetail.headerid')
                                ->join('sh_subjects','sh_classsched.subjid','=','sh_subjects.id')
                                ->join('gradelevel','sh_classsched.glevelid','=','gradelevel.id')
                                ->join('sections','sh_classsched.sectionid','=','sections.id')
                                ->join('days','sh_classscheddetail.day','=','days.id')
                                ->leftJOin('rooms',function($join) use($syid){
                                    $join->on('sh_classscheddetail.roomid','=','rooms.id');
                                    $join->where('rooms.deleted',0);
                                })
                              
                                ->where('sh_classsched.teacherid',$teacherid->id)
                                ->where('sh_classsched.syid', $syid)
                                ->where('sh_classsched.semid', $semid)
                                ->where('sh_classsched.deleted', '0')
                                ->where('sh_classscheddetail.deleted', '0')
                                ->where('sh_subjects.deleted', '0')
                                ->where('gradelevel.deleted', '0')
                                ->where('sections.deleted', '0')
                                ->distinct()
                                ->get();
            
        if(count($assignsubjHigher)>0){
            foreach($assignsubjHigher as $subjectassignment){
                foreach($subjectassignment as $key => $item)
                {
                    if($key == 'stime'){
                        $subjectassignment->stime = date('h:i:s A', strtotime($subjectassignment->stime));
                    }
                    elseif($key == 'etime'){
                        $subjectassignment->etime = date('h:i:s A', strtotime($subjectassignment->etime));
                    }
                }
                if($subjectassignment->description == "Monday"){
                    array_push($mondayArray,$subjectassignment);
                }
                elseif($subjectassignment->description == "Tuesday"){
                    array_push($tuesdayArray,$subjectassignment);
                }
                elseif($subjectassignment->description == "Wednesday"){
                    array_push($wednesdayArray,$subjectassignment);
                }
                elseif($subjectassignment->description == "Thursday"){
                    array_push($thursdayArray,$subjectassignment);
                }
                elseif($subjectassignment->description == "Friday"){
                    array_push($fridayArray,$subjectassignment);
                }
                elseif($subjectassignment->description == "Saturday"){
                    array_push($saturdayArray,$subjectassignment);
                }
            }
        }

        $blocks = DB::table('sh_blocksched')
            ->select('gradelevel.levelname','sections.sectionname','sh_subjects.subjtitle as subjdesc','sh_blockscheddetail.day','days.description','sh_blockscheddetail.stime','sh_blockscheddetail.etime','rooms.roomname')
            ->join('sh_blockscheddetail','sh_blocksched.id','=','sh_blockscheddetail.headerid')
            ->join('sh_subjects','sh_blocksched.subjid','=','sh_subjects.id')
            ->join('sh_sectionblockassignment','sh_blocksched.blockid','=','sh_sectionblockassignment.blockid')
            ->join('sections','sh_sectionblockassignment.sectionid','=','sections.id')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('days','sh_blockscheddetail.day','=','days.id')
            ->leftJOin('rooms',function($join) use($syid){
                $join->on('sh_blockscheddetail.roomid','=','rooms.id');
                $join->where('rooms.deleted',0);
            })
            ->where('sh_blocksched.teacherid',$teacherid->id)
            ->where('sh_blocksched.deleted','0')
            ->where('sh_blocksched.syid',$syid)
            ->where('sh_blockscheddetail.deleted','0')
            ->where('sh_subjects.deleted','0')
            ->where('sh_sectionblockassignment.deleted','0')
            ->where('sections.deleted','0')
            ->where('gradelevel.deleted','0')
            ->distinct()
            ->get();
            

        if(count($blocks) > 0){
            foreach($blocks as $block){
                foreach($block as $key => $item)
                {
                    if($key == 'stime'){
                        $block->stime = date('h:i:s A', strtotime($subjectassignment->stime));
                    }
                    elseif($key == 'etime'){
                        $block->etime = date('h:i:s A', strtotime($subjectassignment->etime));
                    }
                }
                if($block->description == "Monday"){
                    array_push($mondayArray,$block);
                }
                elseif($block->description == "Tuesday"){
                    array_push($tuesdayArray,$block);
                }
                elseif($block->description == "Wednesday"){
                    array_push($wednesdayArray,$block);
                }
                elseif($block->description == "Thursday"){
                    array_push($thursdayArray,$block);
                }
                elseif($block->description == "Friday"){
                    array_push($fridayArray,$block);
                }
                elseif($block->description == "Saturday"){
                    array_push($saturdayArray,$block);
                }
            }
        }
        
            
        $section = DB::table('sections')
            ->where('teacherid',$teacherid->id)
            ->get();

        $countSection = count($section);
        $monday = count($mondayArray);
        $tuesday = count($tuesdayArray);
        $wednesday = count($wednesdayArray);
        $thursday = count($thursdayArray);
        $friday = count($fridayArray);
        $saturday = count($saturdayArray);

        $days = Db::table('days')
            ->whereIn('id',[1,2,3,4,5,6])
            ->get();
        
        $mondayArray        = collect($mondayArray)->unique();
        $tuesdayArray       = collect($tuesdayArray)->unique();
        $wednesdayArray     = collect($wednesdayArray)->unique();
        $thursdayArray      = collect($thursdayArray)->unique();
        $fridayArray        = collect($fridayArray)->unique();
        $saturdayArray      = collect($saturdayArray)->unique();
        if($id == 'dashboard'){

            return view('teacher.summaries.summaryofloads')
                ->with('monday',$mondayArray)
                ->with('tuesday',$tuesdayArray)
                ->with('wednesday',$wednesdayArray)
                ->with('thursday',$thursdayArray)
                ->with('friday',$fridayArray)
                ->with('saturday',$saturdayArray)
                ->with('days',$days)
                ->with('selection','all');

        }
        if($id == 'changeday'){
            if($request->get('selecteddayoftheweek') == 'all'){

                return view('teacher.summaries.summaryofloads')
                    ->with('monday',$mondayArray)
                    ->with('tuesday',$tuesdayArray)
                    ->with('wednesday',$wednesdayArray)
                    ->with('thursday',$thursdayArray)
                    ->with('friday',$fridayArray)
                    ->with('saturday',$saturdayArray)
                    ->with('days',$days)
                    ->with('selection','all');

            }
            if($request->get('selecteddayoftheweek') == 1){

                return view('teacher.summaries.summaryofloads')
                    ->with('monday',$mondayArray)
                    ->with('days',$days)
                    ->with('selection','1');

            }
            if($request->get('selecteddayoftheweek') == 2){

                return view('teacher.summaries.summaryofloads')
                    ->with('tuesday',$tuesdayArray)
                    ->with('days',$days)
                    ->with('selection','2');
                    
            }
            if($request->get('selecteddayoftheweek') == 3){

                return view('teacher.summaries.summaryofloads')
                    ->with('wednesday',$wednesdayArray)
                    ->with('days',$days)
                    ->with('selection','3');
                    
            }
            if($request->get('selecteddayoftheweek') == 4){

                return view('teacher.summaries.summaryofloads')
                    ->with('thursday',$thursdayArray)
                    ->with('days',$days)
                    ->with('selection','4');
                    
            }
            if($request->get('selecteddayoftheweek') == 5){

                return view('teacher.summaries.summaryofloads')
                    ->with('friday',$fridayArray)
                    ->with('days',$days)
                    ->with('selection','5');
                    
            }
            if($request->get('selecteddayoftheweek') == 6){

                return view('teacher.summaries.summaryofloads')
                    ->with('saturday',$saturdayArray)
                    ->with('days',$days)
                    ->with('selection','5');
                    
            }

        }
        if($id == 'print'){
            
            $schoolinfo = Db::table('schoolinfo')
                ->get();

            $sy = Db::table('sy')
                ->where('id',$syid)
                ->get();

            $myinfo = DB::table('teacher')
                ->select(
                    'lastname',
                    'firstname',
                    'middlename',
                    'suffix'
                )
                ->where('userid', auth()->user()->id)
                ->first();

            $selection = $request->get('selection');

            if($request->get('selection') == 'all'){

                $pdf = PDF::loadview('teacher/pdf/pdf_summaryofloads',compact('mondayArray','tuesdayArray','wednesdayArray','thursdayArray','fridayArray','saturdayArray','schoolinfo','sy','myinfo','selection'))->setPaper('a4');

                return $pdf->stream('Loads - '.$myinfo->lastname.'.pdf');
            }
            if($request->get('selection') == 1){


                $pdf = PDF::loadview('teacher/pdf/pdf_summaryofloads',compact('mondayArray','schoolinfo','sy','myinfo','selection'))->setPaper('a4');

                return $pdf->stream('Loads - '.$myinfo->lastname.'.pdf');

            }
            if($request->get('selection') == 2){


                $pdf = PDF::loadview('teacher/pdf/pdf_summaryofloads',compact('tuesdayArray','schoolinfo','sy','myinfo','selection'))->setPaper('a4');

                return $pdf->stream('Loads - '.$myinfo->lastname.'.pdf');
                    
            }
            if($request->get('selection') == 3){


                $pdf = PDF::loadview('teacher/pdf/pdf_summaryofloads',compact('wednesdayArray','schoolinfo','sy','myinfo','selection'))->setPaper('a4');

                return $pdf->stream('Loads - '.$myinfo->lastname.'.pdf');
                    
            }
            if($request->get('selection') == 4){


                $pdf = PDF::loadview('teacher/pdf/pdf_summaryofloads',compact('thursdayArray','schoolinfo','sy','myinfo','selection'))->setPaper('a4');

                return $pdf->stream('Loads - '.$myinfo->lastname.'.pdf');
                    
            }
            if($request->get('selection') == 5){


                $pdf = PDF::loadview('teacher/pdf/pdf_summaryofloads',compact('fridayArray','schoolinfo','sy','myinfo','selection'))->setPaper('a4');

                return $pdf->stream('Loads - '.$myinfo->lastname.'.pdf');
                    
            }
            if($request->get('selection') == 6){


                $pdf = PDF::loadview('teacher/pdf/pdf_summaryofloads',compact('saturdayArray','schoolinfo','sy','myinfo','selection'))->setPaper('a4');

                return $pdf->stream('Loads - '.$myinfo->lastname.'.pdf');
                    
            }
        }

    }
}
