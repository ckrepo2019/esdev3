<?php

namespace App\Http\Controllers\CollegeControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use PDF;
use PDO;

class ClgCollegeAttendanceController extends Controller
{
    public function clgattndcShowPage(){ //Return the view page
        $sy = DB::table('sy')
        ->select(
            'id',
            'sydesc as text'
        )
        ->orderBy('sydesc', 'desc')
        ->get();

        $activeSY = DB::table('sy')->where('isactive',1)->first()->id;
        $activeSem = DB::table('semester')->where('isactive',1)->first()->id;
        $teacher = DB::table('teacher')
            ->where('tid',auth()->user()->email)
            ->where('deleted', 0)
            ->select(
                'id', 
                DB::raw("CONCAT(lastname,' ',firstname) as teachername")
            )
            ->first();
        

        $subject = DB::table('college_classsched')
        ->join('college_prospectus', function ($join){

            $join->on('college_classsched.subjectID', 'college_prospectus.id');
            $join->where('college_classsched.deleted', 0);
        })
        ->select(
            'college_classsched.sectionID',
            'college_prospectus.id',
            'college_prospectus.subjDesc as text'
        )
        ->where('college_classsched.deleted', 0)
        ->where('college_classsched.teacherID', $teacher->id)
        ->where('college_classsched.syID', $activeSY)
        ->where('college_classsched.semesterID', $activeSem)
        ->get();
            
        $subject = collect($subject)->unique('text');

        return view('superadmin.pages.college.collegeattendance',[
            'subject'=>$subject->values()->all(),
            'teacher'=>$teacher,
            'sy'=>$sy
        ]);
    }

    public function getFilterSubject(Request $request){

        $teacher = DB::table('teacher')
        ->where('tid',auth()->user()->email)
        ->where('deleted', 0)
        ->select(
            'id', 
            DB::raw("CONCAT(lastname,' ',firstname) as teachername")
        )
        ->first();

        $subject = DB::table('college_classsched')
        ->join('college_prospectus', function ($join){

            $join->on('college_classsched.subjectID', 'college_prospectus.id');
            $join->where('college_classsched.deleted', 0);
        })
        ->select(
            'college_classsched.sectionID',
            'college_prospectus.id',
            'college_prospectus.subjDesc as text'
        )
        ->where('college_classsched.deleted', 0)
        ->where('college_classsched.teacherID', $teacher->id)
        ->where('college_classsched.syID', $request->syid)
        ->where('college_classsched.semesterID', $request->semid)
        ->get();
            
        $subject = collect($subject)->unique('text');

        return response()->json($subject);
    }

    public function clgattndcGetSelect(Request $request){

        // $collegeschedgroup = DB::table('college_schedgroup')
        //     ->where('college_schedgroup.id', $request->subjectid)
        //     ->join('college_schedgroup_detail', function ($join) {
        //         $join->on('college_schedgroup.id', 'college_schedgroup_detail.groupid')
        //         ->where('college_schedgroup.deleted', 0);
        //     })->get();
        

        $enrollstudents = DB::table('college_enrolledstud')
            ->join('studinfo', function ($join) {
                $join->on('college_enrolledstud.studid', 'studinfo.id')
                ->where('studinfo.deleted', 0);
            })
            ->select(
                'college_enrolledstud.studid as id',
                'studinfo.gender',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.gender',
                DB::raw("CONCAT(lastname,' ',firstname,' ', COALESCE(`middlename`,'')) as text")
            )
            ->where('college_enrolledstud.syid', $request->syid)
            ->where('college_enrolledstud.sectionID', $request->sectionid)
            ->whereIn('college_enrolledstud.studstatus', [1,2,4])
            ->get();

        $sy = DB::table('sy')
            ->select(
                'id',
                'sydesc as text'
            )
            ->orderBy('sydesc', 'desc')
            ->get();

        $teacher = DB::table('teacher')
            ->where('tid',auth()->user()->email)
            ->where('deleted', 0)
            ->select(
                'id', 
                DB::raw("CONCAT(lastname,' ',firstname) as teachername")
            )
            ->first();

        $sectionlist = DB::table('college_classsched')
						->join('college_schedgroup_detail',function($join){
								$join->on('college_classsched.id','=','college_schedgroup_detail.schedid');
								$join->where('college_schedgroup_detail.deleted',0);
						})
						->join('college_schedgroup',function($join){
								$join->on('college_schedgroup_detail.groupid','=','college_schedgroup.id');
								$join->where('college_schedgroup.deleted',0);
						})
						->leftJoin('college_courses',function($join){
								$join->on('college_schedgroup.courseid','=','college_courses.id');
								$join->where('college_courses.deleted',0);
						})
						->leftJoin('gradelevel',function($join){
								$join->on('college_schedgroup.levelid','=','gradelevel.id');
								$join->where('gradelevel.deleted',0);
						})
						->leftJoin('college_colleges',function($join){
								$join->on('college_schedgroup.collegeid','=','college_colleges.id');
								$join->where('college_colleges.deleted',0);
						})
						->select(
								'college_schedgroup.courseid',
								'college_schedgroup.levelid',
								'college_schedgroup.collegeid',
								'sectionID',
								'courseDesc',
								'collegeDesc',
								'levelname',
								'courseabrv',
								'collegeabrv',
								'college_schedgroup_detail.id',
								'college_schedgroup.schedgroupdesc',
								'schedgroupdesc as text',
								'college_schedgroup_detail.groupid'
						)
						->distinct('groupid')
						->where('subjectID', $request->subjectid)
						->where('teacherid', $teacher->id)
						->get();



            foreach($sectionlist as $item){
                $text = '';
                if($item->courseid != null){
                            $text = $item->courseabrv;
                }else{
                            $text = $item->collegeabrv;
                }
                $text .= '-'.$item->levelname[0] . ' '.$item->schedgroupdesc;
                $item->text = $text;
          }



        // $section = DB::table('college_sections')
        //     ->whereIn('college_sections.id',  collect($collegesched)->pluck('id'))
        //     ->select(
                
        //         'college_sections.id',
        //         'college_sections.sectionDesc as text'
        //     )
        //     ->where('college_sections.deleted', 0)
        //     ->get();
        return array (
            (object)[

            'section'=>$sectionlist,
            'enrollstudents'=>$enrollstudents,
            'sy'=>$sy,

        ]); 
    }
    
    public function clgattndcGenerateAttendance(Request $request){ //Return all the student in table after clicking generate

        $collegeschedgroup = DB::table('college_schedgroup_detail')
            ->where('college_schedgroup_detail.id', $request->sectionid)
            ->join('college_schedgroup', function ($join) {
                $join->on('college_schedgroup_detail.groupid', 'college_schedgroup.id')
                ->where('college_schedgroup.deleted', 0);
            })->get();


        $enrollstudents = DB::table('college_studsched')
            ->join('college_enrolledstud', function ($join) use($request, $collegeschedgroup) {
                $join->on('college_studsched.studid', 'college_enrolledstud.studid')
                ->where('college_enrolledstud.syid', $request->syid)
                ->where('college_enrolledstud.semid', $request->semid)
                ->where('college_studsched.deleted', 0)
                ->whereIn('college_enrolledstud.courseid', collect($collegeschedgroup)->pluck('courseid'))
                ->whereIn('college_enrolledstud.studstatus', [1,2,4]);
            })
            ->join('studinfo', function ($join) {
                $join->on('college_enrolledstud.studid', 'studinfo.id')
                ->where('studinfo.deleted', 0);
            })
            ->select(
                'college_enrolledstud.studid',
                'studinfo.gender',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.gender',
                DB::raw("CONCAT(lastname,' ',firstname,' ', COALESCE(`middlename`,'')) as studentname")
            )
            ->whereIn('college_studsched.schedid', collect($collegeschedgroup)->pluck('schedid'))
            ->where('college_studsched.schedstatus', '!=', 'DROPPED')
            ->orderBy('studinfo.lastname', 'asc')
            ->get();

        $sections = DB::table('college_classsched')
            ->join('college_schedgroup_detail', function ($join) use($request){
                $join->on('college_classsched.id', 'college_schedgroup_detail.schedid')
                ->where('college_schedgroup_detail.id', $request->sectionid)
                ->where('college_schedgroup_detail.deleted', 0);
            })
            ->select('college_classsched.sectionID')
            ->where('college_classsched.deleted', 0)
            ->first();

        foreach ($enrollstudents as $enrollstudent) {

            $check = DB::table('college_attendance')
                ->where('studid',$enrollstudent->studid)
                ->where('syid',$request->syid)
                ->where('semid',$request->semid)
                ->where('subjectid',$request->subjectid)
                ->where('monthid',$request->monthid)
                ->where('yearid',$request->yearid)
                ->where('deleted',0)
                ->count();

            if($check == 0){

                DB::table('college_attendance')
                ->insert([
                    'studid'=> $enrollstudent->studid,
                    'syid'=> $request->syid,
                    'sectionid'=> $sections->sectionID,
                    'subjectid'=>$request->subjectid,
                    'semid'=> $request->semid,
                    'monthid'=> $request->monthid,
                    'yearid'=> $request->yearid,
                    
                ]);

            }
        }

        $attendace = DB::table('college_attendance')
            ->join('studinfo', function ($join) {
                $join->on('college_attendance.studid', 'studinfo.id')
                ->where('studinfo.deleted', 0);
            })
            ->select(
                'college_attendance.*',
                'studinfo.gender',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.gender',
                DB::raw("CONCAT(lastname,' ',firstname,' ', COALESCE(`middlename`,'')) as studentname")
            )
            ->where('college_attendance.syid',$request->syid)
            ->where('college_attendance.semid',$request->semid)
            ->where('college_attendance.subjectid',$request->subjectid)
            ->where('college_attendance.sectionid',$sections->sectionID)
            // ->where('college_attendance.monthid',$request->monthid)
            // ->where('college_attendance.yearid',$request->yearid)
            ->where('college_attendance.deleted', 0)
            ->orderBy('studinfo.lastname', 'asc')
            ->get();

        $columnSize = 0;
        if($request->dataArray != null){
            $columnSize = count($request->dataArray);
        }
        $column = $request->dataArray;
        $column = collect($column)->sortBy('datadate')->values();

        $output = "";

        $output .= '<table class="table attendance-table" style="width: 100%; border-collapse: separate; border-spacing: 0">
            <thead class="thead-dark">
                
                <tr class="attendance-table-header">
                    <th class="pl-1 tablenumber">#</th>';
                
                    if($columnSize == 1){

                        $output .= '
                        <th width="70%" class="text-left" style="padding-left: 10px;">Student Name</th>
                        <th width="30%" class="text-center" yearid="'.$column[0]['yearid'].'" month-id="'.$column[0]['monthid'].'" >'.$column[0]['date'].'</th>';
                                    
                    }else if($columnSize == 2){

                        $output .= '
                        <th width="70%" class="text-left" style="padding-left: 10px;">Student Name</th>
                        <th width="15%" class="text-center" yearid="'.$column[0]['yearid'].'" month-id="'.$column[0]['monthid'].'">'.$column[0]['date'].'</th>
                        <th width="15%" class="text-center" yearid="'.$column[1]['yearid'].'" month-id="'.$column[1]['monthid'].'">'.$column[1]['date'].'</th>';

                    }else if($columnSize == 3){

                        $output .= '
                        <th width="70%" class="text-left" style="padding-left: 10px;">Student Name</th>
                        <th width="10%" class="text-center" yearid="'.$column[0]['yearid'].'" month-id="'.$column[0]['monthid'].'">'.$column[0]['date'].'</th>
                        <th width="10%" class="text-center" yearid="'.$column[1]['yearid'].'" month-id="'.$column[1]['monthid'].'">'.$column[1]['date'].'</th>
                        <th width="10%" class="text-center" yearid="'.$column[2]['yearid'].'" month-id="'.$column[2]['monthid'].'">'.$column[2]['date'].'</th>';

                    }else{

                        $output .= '
                        <th class="text-left" style=" width:350px; padding-left: 10px; min-width: 350px;">Student Name</th>';

                        foreach ($column as $element) {
                            
                            $output .= '<th width="50px" yearid="'.$element['yearid'].'" month-id="'.$element['monthid'].'" col-id="'.$element['col_name'].'" class="text-center">'.$element['date'].'</th>';
                        }
                    }
                    

        $output .= '
                </tr>

            </thead>
            <tbody>
            
                <tr>
                    <td class="text-left table-primary"></td>

                    <td class="text-left table-primary tabledata" style="padding-left: 10px;"><label class="m-0">Male</label></td>';
                    
                    foreach ($column as $element) {
                        $colname = $element['col_name'];

                        $output .= '<td class="table-primary spanholder p-0" menu-type="column" yearid="'.$element['yearid'].'" monthid="'.$element['monthid'].'" click-count="0" col-name="'.$element['col_name'].'">
                            <i style="font-size:15px" class="fas fa-caret-down"></i>
                        </td>';
                    }

        $output .= '</tr>';
                
            $counter = 1;

                foreach ($enrollstudents as $attndc) {

                    
                    if($attndc->gender == 'MALE'){
                            $itemcounter = $counter++;

                            if($columnSize > 1){
                                $output .= '

                                <tr>
                                    <td class="text-center tablenumber pl-1">'.$itemcounter.'</td>

                                    <td class="text-left tabledata" style="padding-left: 10px;background: white;">
                                        <div class="d-flex justify-content-between">
                                            
                                            '.$attndc->studentname.'
                                            <div class="spanholder" menu-type="row" yearid="'.$element['yearid'].'" monthid="'.$element['monthid'].'" data-id="'.$attndc->studid.'" click-count="0">
                                                <span style="line-height: 13px;" class="badge badge-primary">
                                                    <i class="fas fa-caret-right"></i>
                                                </span>
                                            </div>

                                        </div>
                                    </td>'; 
                            }else{

                                $output .= '
                                <tr>
                                    <td class="text-center tablenumber pl-1">'.$itemcounter.'</td>

                                    <td class="text-left tabledata" style="padding-left: 10px;background: white;">'.$attndc->studentname.'</td>';
                            }

        

                            //Dre ang pag display sa attendance status 
                            foreach ($column as $element) {

                                $colname = $element['col_name'];
                                $collectedAtt = collect($attendace)
                                    ->where('studid', $attndc->studid)
                                    ->where('monthid', $element['monthid'])
                                    ->where('yearid', $element['yearid'])
                                    ->first();

                                if(!isset($collectedAtt)){
                                    return array (
                                        (object)[
                            
                                        'status'=>400,
                                        'monthid'=>$element['monthid'],
                                        'yearid'=>$element['yearid'],
                            
                                    ]); 
                                }

                                if($collectedAtt->$colname == 1){
                                    $output .= '
                                        <td class="date_cell bg-success tabledata" 
                                            yearid="'.$element['yearid'].'"
                                            monthid="'.$element['monthid'].'" 
                                            data-id="'.$attndc->studid.'" 
                                            col-name="'.$element['col_name'].'"  
                                            clicked="1">Present</td>';
                                }elseif ($collectedAtt->$colname == 2) {
                                    $output .= '
                                        <td class="date_cell bg-danger tabledata" 
                                            yearid="'.$element['yearid'].'" 
                                            monthid="'.$element['monthid'].'"
                                            data-id="'.$attndc->studid.'" 
                                            col-name="'.$element['col_name'].'"  
                                            clicked="2">Absent</td>';
                                }elseif ($collectedAtt->$colname == 3) {
                                    $output .= '
                                        <td class="date_cell bg-warning tabledata" 
                                            yearid="'.$element['yearid'].'" 
                                            monthid="'.$element['monthid'].'" 
                                            data-id="'.$attndc->studid.'"
                                            col-name="'.$element['col_name'].'"  
                                            clicked="3">Late</td>';
                                }elseif ($collectedAtt->$colname == 4) {
                                    $output .= '
                                        <td class="date_cell bg-info tabledata" 
                                            yearid="'.$element['yearid'].'" 
                                            monthid="'.$element['monthid'].'" 
                                            data-id="'.$attndc->studid.'"
                                            col-name="'.$element['col_name'].'"  
                                            clicked="4">Excuse</td>';
                                }else{
                                    $output .= '
                                        <td class="date_cell tabledata" yearid="'.$element['yearid'].'"
                                            monthid="'.$element['monthid'].'" 
                                            data-id="'.$attndc->studid.'" 
                                            col-name="'.$element['col_name'].'"  
                                            clicked="0"></td>';
                                }

                            }
                        $output .= '</tr>';
                        
                    }
                }

                $output .= '
                    <tr>
                        <td class="text-left table-danger"></td>
                        <td class="text-left table-danger tabledata" style="padding-left: 10px;"><label class="m-0">Female</label></td>';

                        foreach ($column as $element) {
                            $colname = $element['col_name'];

                            $output .= '<td class="table-danger" col-name="'.$element['col_name'].'"></td>';
                        }
       
                $output .= '</tr>';

                foreach ($enrollstudents as $attndc) {
                    
                    if($attndc->gender == 'FEMALE'){
                        $itemcounter = $counter++;

                            if($columnSize > 1){
                                $output .= '

                                <tr>
                                    <td class="text-center tablenumber pl-1">'.$itemcounter.'</td>

                                    <td class="text-left tabledata" style="padding-left: 10px;background: white;">
                                        <div class="d-flex justify-content-between">
                                            
                                            '.$attndc->studentname.'
                                            <div class="spanholder" menu-type="row" yearid="'.$element['yearid'].'" monthid="'.$element['monthid'].'" data-id="'.$attndc->studid.'" click-count="0">
                                                <span style="line-height: 13px;" class="badge badge-primary">
                                                    <i class="fas fa-caret-right"></i>
                                                </span>
                                            </div>

                                        </div>
                                    </td>'; 
                            }else{

                                $output .= '
                                <tr>
                                    <td class="text-center tablenumber pl-1">'.$itemcounter.'</td>

                                    <td class="text-left tabledata" style="padding-left: 10px;background: white;">'.$attndc->studentname.'</td>';
                            }

                            //Dre ang pag display sa attendance status 
                            foreach ($column as $element) {

                                $colname = $element['col_name'];
                                $collectedAtt = collect($attendace)
                                    ->where('studid', $attndc->studid)
                                    ->where('monthid', $element['monthid'])
                                    ->where('yearid', $element['yearid'])
                                    ->first();


                                if(!isset($collectedAtt)){
                                    return array (
                                        (object)[
                            
                                        'status'=>400,
                                        'monthid'=>$element['monthid'],
                                        'yearid'=>$element['yearid'],
                            
                                    ]); 
                                }

                                if($collectedAtt->$colname == 1){
                                    $output .= '
                                        <td class="date_cell bg-success tabledata" 
                                            yearid="'.$element['yearid'].'"
                                            monthid="'.$element['monthid'].'" 
                                            data-id="'.$attndc->studid.'" 
                                            col-name="'.$element['col_name'].'"  
                                            clicked="1">Present</td>';
                                }elseif ($collectedAtt->$colname == 2) {
                                    $output .= '
                                        <td class="date_cell bg-danger tabledata" 
                                            yearid="'.$element['yearid'].'" 
                                            monthid="'.$element['monthid'].'"
                                            data-id="'.$attndc->studid.'" 
                                            col-name="'.$element['col_name'].'"  
                                            clicked="2">Absent</td>';
                                }elseif ($collectedAtt->$colname == 3) {
                                    $output .= '
                                        <td class="date_cell bg-warning tabledata" 
                                            yearid="'.$element['yearid'].'" 
                                            monthid="'.$element['monthid'].'" 
                                            data-id="'.$attndc->studid.'"
                                            col-name="'.$element['col_name'].'"  
                                            clicked="3">Late</td>';
                                }elseif ($collectedAtt->$colname == 4) {
                                    $output .= '
                                        <td class="date_cell bg-info tabledata" 
                                            yearid="'.$element['yearid'].'" 
                                            monthid="'.$element['monthid'].'" 
                                            data-id="'.$attndc->studid.'"
                                            col-name="'.$element['col_name'].'"  
                                            clicked="4">Excuse</td>';
                                }else{
                                    $output .= '
                                        <td class="date_cell tabledata" yearid="'.$element['yearid'].'"
                                            monthid="'.$element['monthid'].'" 
                                            data-id="'.$attndc->studid.'" 
                                            col-name="'.$element['col_name'].'"  
                                            clicked="0"></td>';
                                }

                            }
                        $output .= '</tr>';
                        
                    }
                }

        $output .= '
            </tbody>
            
        </table>';


        $students = DB::table('college_enrolledstud')
            ->where('syid', $request->syid)
            ->where('sectionID', $sections->sectionID)
            ->select('studid')
            ->whereIn('college_enrolledstud.studstatus', [1,2,4])
            ->get();

        return array (
            (object)[

            'output'=>$output,
            'enrollstudents'=>$enrollstudents,

        ]); 

        
    }

    public function clgattndcGeneratePerMonth(Request $request){ //Generating the attendance of students per month if not yet generated

        if(!isset($request->students)){

            return "NO STUDENT";
            
        }else{

            $sections = DB::table('college_classsched')
            ->join('college_schedgroup_detail', function ($join) use($request){
                $join->on('college_classsched.id', 'college_schedgroup_detail.schedid')
                ->where('college_schedgroup_detail.id', $request->sectionid)
                ->where('college_schedgroup_detail.deleted', 0);
            })
            ->select('college_classsched.sectionID')
            ->where('college_classsched.deleted', 0)
            ->first();

            foreach ($request->students as $studs) {

                DB::table('college_attendance')
                ->insert([
                    'studid'=> $studs['studid'],
                    'syid'=> $request->syid,
                    'sectionid'=> $sections->sectionID,
                    'subjectid'=>$request->subjectid,
                    'semid'=> $request->semid,
                    'monthid'=> $request->monthid,
                    'yearid'=> $request->yearid,
                ]);
            }
        }
            
    }

    public function clgattndcSetStatus(Request $request){ //Setting the student's attendance status per cell
        
        // $checker = DB::table('college_attendance')
        //     ->where('studid',$request->studid)
        //     ->where('syid',$request->syid)
        //     ->where('semid',$request->semid)
        //     ->where('subjectid',$request->subjectid)
        //     ->where('monthid',$request->monthid)
        //     ->where('yearid',$request->yearid)
        //     ->where('deleted',0)
        //     ->get()
        //     ->count();

        // if($checker != 0){

        //     DB::table('college_attendance')
        //     ->where('studid',$request->studid)
        //     ->where('syid',$request->syid)
        //     ->where('semid',$request->semid)
        //     ->where('subjectid',$request->subjectid)
        //     ->where('monthid',$request->monthid)
        //     ->where('yearid',$request->yearid)
        //     ->update([
        //         $request->colname => $request->status,
        //     ]);

        //     return "Existing updated only";

        // }else{

        //     $id = DB::table('college_attendance')
        //         ->insertGetId([
        //             'studid'=> $request->studid,
        //             'syid'=> $request->syid,
        //             'sectionid'=> $request->sectionid,
        //             'subjectid'=>$request->subjectid,
        //             'semid'=> $request->semid,
        //             'monthid'=> $request->monthid,
        //             'yearid'=> $request->yearid,
        //         ]);

        //     DB::table('college_attendance')
        //         ->where('id', $id)
        //         ->where('monthid', $request->monthid)
        //         ->where('yearid', $request->yearid)
        //         ->update([
        //             $request->colname => $request->status,
        //         ]);
        // }


        DB::table('college_attendance')
            ->where('studid',$request->studid)
            ->where('syid',$request->syid)
            ->where('semid',$request->semid)
            ->where('subjectid',$request->subjectid)
            ->where('monthid',$request->monthid)
            ->where('yearid',$request->yearid)
            ->update([
                $request->colname => $request->status,
            ]);

    }

    public function clgattndcBulkSetRowStatus(Request $request){ //Setting the student's attendance status by row
        
        foreach ($request->colname as $column) {

            DB::table('college_attendance')
                ->where('studid', $request->id)
                ->where('yearid', $column['yearid'])
                ->where('monthid', $column['monthid'])
                ->update([
                    $column['col_name'] => $request->status,
                ]);
        }


    }

    public function clgattndcBulkSetColStatus(Request $request){ //Setting the student's attendance status by column
        
        $sections = DB::table('college_classsched')
            ->join('college_schedgroup_detail', function ($join) use($request){
                $join->on('college_classsched.id', 'college_schedgroup_detail.schedid')
                ->where('college_schedgroup_detail.id', $request->sectionid)
                ->where('college_schedgroup_detail.deleted', 0);
            })
            ->select('college_classsched.sectionID')
            ->where('college_classsched.deleted', 0)
            ->first();

        foreach ($request->students as $row) {
            $column = $request->colname;

            DB::table('college_attendance')
                ->where('studid', $row['studid'])
                ->where('syid', $request->syid)
                ->where('semid', $request->semid)
                ->where('sectionid', $sections->sectionID)
                ->where('subjectid', $request->subjectid)
                ->where('monthid', $request->monthid)
                ->where('yearid', $request->yearid)
                ->update([
                    $column => $request->status,
                ]);
        }
            
    }

    public function clgattndcGeneratePDF($syid, $subjectid, $sectionid ,$semid, $arraymonth){
        
        $months = json_decode($arraymonth);

        $months = collect($months)->toArray();

        $collegeschedgroup = DB::table('college_schedgroup_detail')
            ->where('college_schedgroup_detail.id', $sectionid)
            ->join('college_schedgroup', function ($join) {
                $join->on('college_schedgroup_detail.groupid', 'college_schedgroup.id')
                ->where('college_schedgroup.deleted', 0);
            })->get();

        $sec = DB::table('college_classsched')
            ->join('college_schedgroup_detail', function ($join) use($sectionid){
                $join->on('college_classsched.id', 'college_schedgroup_detail.schedid')
                ->where('college_schedgroup_detail.id', $sectionid)
                ->where('college_schedgroup_detail.deleted', 0);
            })
            ->select('college_classsched.sectionID')
            ->where('college_classsched.deleted', 0)
            ->first();

        $enrollstudents = DB::table('college_studsched')
            ->join('college_enrolledstud', function ($join) use($syid, $semid, $collegeschedgroup) {
                $join->on('college_studsched.studid', 'college_enrolledstud.studid')
                ->where('college_enrolledstud.syid', $syid)
                ->where('college_enrolledstud.semid', $semid)
                ->where('college_studsched.deleted', 0)
                ->whereIn('college_enrolledstud.courseid', collect($collegeschedgroup)->pluck('courseid'))
                ->whereIn('college_enrolledstud.studstatus', [1,2,4]);
            })
            ->join('studinfo', function ($join) {
                $join->on('college_enrolledstud.studid', 'studinfo.id')
                ->where('studinfo.deleted', 0);
            })
            ->select(
                'college_enrolledstud.studid',
                'studinfo.gender',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.gender',
                DB::raw("CONCAT(lastname,' ',firstname,' ', COALESCE(`middlename`,'')) as studentname")
            )
            ->whereIn('college_studsched.schedid', collect($collegeschedgroup)->pluck('schedid'))
            ->where('college_studsched.schedstatus', '!=', 'DROPPED')
            ->orderBy('studinfo.lastname', 'asc')
            ->get();

        // $enrollstudents = DB::table('college_enrolledstud')
        //     ->join('studinfo', function ($join) {
        //         $join->on('college_enrolledstud.studid', 'studinfo.id')
        //         ->where('studinfo.deleted', 0);
        //     })
        //     ->select(
        //         'college_enrolledstud.studid',
        //         'studinfo.gender',
        //         'studinfo.lastname',
        //         'studinfo.firstname',
        //         'studinfo.middlename',
        //         'studinfo.gender',
        //         DB::raw("CONCAT(lastname,' ',firstname,' ', COALESCE(`middlename`,'')) as studentname")
        //     )
        //     ->where('college_enrolledstud.syid', $syid)
        //     ->where('college_enrolledstud.sectionID', $sections->sectionID)
        //     ->whereIn('college_enrolledstud.studstatus', [1,2,4])
        //     ->orderBy('studinfo.lastname', 'asc')
        //     ->get();


        $attendance = DB::table('college_attendance')
            ->join('studinfo', function ($join) {
                $join->on('college_attendance.studid', 'studinfo.id')
                ->where('studinfo.deleted', 0);
            })
            ->select(
                'college_attendance.*',
                'studinfo.gender',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.gender',
                DB::raw("CONCAT(lastname,' ',firstname,' ', COALESCE(`middlename`,'')) as studentname")
            )
            ->where('college_attendance.syid',$syid)
            ->where('college_attendance.semid',$semid)
            ->where('college_attendance.subjectid',$subjectid)
            ->where('college_attendance.sectionid',$sec->sectionID)
            // ->where('college_attendance.monthid', 1)
            // ->where('college_attendance.yearid',$yearid)
            ->where('college_attendance.deleted', 0)
            ->orderBy('studinfo.lastname', 'asc')
            ->get();

        $schoolyear = DB::table('sy')
            ->where('id', $syid)
            ->first();

        $sections = DB::table('college_sections')
            ->where('id', $sec->sectionID)
            ->where('deleted', 0)
            ->select('sectionDesc')
            ->first();
            
			
        $gradelvlid = DB::table('college_sections')
            ->where('id', $sec->sectionID)
            ->where('deleted', 0)
            ->select(
                'yearID'
            )
            ->first();

        $gradelvl = DB::table('gradelevel')
            ->where('id', $gradelvlid->yearID)
            ->where('deleted', 0)
            ->select('levelname', 'acadprogid')
            ->first();


        $schoolinfo = DB::table('schoolinfo')
            ->first();

        $pdf = PDF::loadView('superadmin.pages.printable.collegeattendance-pdf', compact('enrollstudents', 'attendance', 'sections', 'schoolinfo', 'gradelvl', 'semid', 'schoolyear', 'months'))->setPaper('legal', 'landscape');
        
        $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);

        return $pdf->stream();
    }

    public function clgattndcGenerateExcel($syid, $subjectid, $sectionid ,$semid, $arraymonth){

        $monthNames = ['January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        $months = json_decode($arraymonth);

        $months = collect($months)->toArray();

        $enrollstudents = DB::table('college_enrolledstud')
            ->join('studinfo', function ($join) {
                $join->on('college_enrolledstud.studid', 'studinfo.id')
                ->where('studinfo.deleted', 0);
            })
            ->select(
                'college_enrolledstud.studid',
                'studinfo.gender',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.gender',
                DB::raw("CONCAT(lastname,' ',firstname,' ', COALESCE(`middlename`,'')) as studentname")
            )
            ->where('college_enrolledstud.syid', $syid)
            ->where('college_enrolledstud.sectionID', $sectionid)
            ->whereIn('college_enrolledstud.studstatus', [1,2,4])
            ->orderBy('lastname', 'asc')
            ->get();

        $attendance = DB::table('college_attendance')
            ->join('studinfo', function ($join) {
                $join->on('college_attendance.studid', 'studinfo.id')
                ->where('studinfo.deleted', 0);
            })
            ->select(
                'college_attendance.*',
                'studinfo.gender',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.gender',
                DB::raw("CONCAT(lastname,' ',firstname,' ', COALESCE(`middlename`,'')) as studentname")
            )
            ->where('college_attendance.syid',$syid)
            ->where('college_attendance.semid',$semid)
            ->where('college_attendance.subjectid',$subjectid)
            ->where('college_attendance.sectionid',$sectionid)
            // ->where('college_attendance.monthid', 1)
            // ->where('college_attendance.yearid',$yearid)
            ->where('college_attendance.deleted', 0)
            ->orderBy('studinfo.lastname', 'asc')
            ->get();

        $schoolyear = DB::table('sy')
            ->where('id', $syid)
            ->first();

        $sections = DB::table('college_sections')
            ->where('id', $sectionid)
            ->where('deleted', 0)
            ->select('sectionDesc')
            ->first();
			
		

        $gradelvlid = DB::table('college_sections')
            ->where('id', $sectionid)
            ->where('deleted', 0)
            ->select(
                'yearID'
            )
            ->first();

        $gradelvl = DB::table('gradelevel')
            ->where('id', $gradelvlid->yearID)
            ->where('deleted', 0)
            ->select('levelname', 'acadprogid')
            ->first();


        $schoolinfo = DB::table('schoolinfo')
            ->first();
        
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load("collegeattendance/collegeattendance-temp.xlsx");

        $sheet = $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getSheetByName('Month0');
        
        if(in_array(13, $months)){
            
            $months = [];
            $months = [1,2,3,4,5,6,7,8,9,10,11,12];

            for ($i=1; $i < count($months); $i++) { 
            
                $clonedWorksheet = clone $spreadsheet->setActiveSheetIndex(0);
                $clonedWorksheet->setTitle('Month'.$i);
                $spreadsheet->addSheet($clonedWorksheet);
            }

        }else{

            if(count($months) != 1){

                for ($i=1; $i < count($months); $i++) { 
                
                    $clonedWorksheet = clone $spreadsheet->setActiveSheetIndex(0);
                    $clonedWorksheet->setTitle('Month'.$i);
                    $spreadsheet->addSheet($clonedWorksheet);
                   
                }
            }
        }

        for ($i=0; $i < count($months); $i++) {

            //Dre mag add para sa specific worksheet
            
            $currentSheet = $spreadsheet->setActiveSheetIndex($i);
            $currentSheet->setTitle($monthNames[$months[$i]-1]);

            $currentSheet->setCellValue('A2', $schoolinfo->schoolname);
            $currentSheet->setCellValue('A3', $schoolinfo->address);
            $currentSheet->setCellValue('A6', $schoolyear->sydesc);

            $currentSheet->setCellValue('B7', $sections->sectionDesc." - ".$gradelvl->levelname);


            if($semid == 1){
                $currentSheet->setCellValue('B8', "First Semester");
            }else if($semid == 2){
                $currentSheet->setCellValue('B8', "Second Semester");
            }else {
                $currentSheet->setCellValue('B8', "Summer");
            }


            $currentSheet->setCellValue('D10', $monthNames[$months[$i]-1]);

            $currentyear = explode( "-", $schoolyear->sydesc);

            $date = new \DateTime("1-".($months[$i]).'-2023');
            $date->modify("last day of this month");

            $counter = 1;
            $cellCounter = 12;
            $columnCounter = 1;
            $lastcolumn = "D";

            for ($column = 'D'; $column != 'AI'; $column++) {
                if($columnCounter <= $date->format("d")){
                    $currentSheet->setCellValue($column.'11', $columnCounter++);
                    $lastcolumn = $column;
                }
            }

            //MALES
            foreach ($enrollstudents as $student) {

                if($student->gender == "MALE"){

                    $cellCounter++;
                    $count = $counter++;


                    $middlename = "";
                    if($student->middlename == '-' || $student->middlename == null) {
                        $middlename = "";
                    }else{
                        $middlename = substr($student->middlename, 0, 1).'.';
                    }
                    
                    $currentSheet->setCellValue('A'.$cellCounter, $count);
                    $currentSheet->setCellValue('B'.$cellCounter, $student->lastname.', '.$student->firstname.' '.$middlename);

                    $collectedAttndc = collect($attendance)
                        ->where('studid', $student->studid)
                        ->where('monthid', (int) $months[$i])
                        // ->where('yearid', $element['yearid'])
                        ->first();

                    // return $collectedAttndc->day1;

                    $number = 1;
                    for ($column = 'D'; $column != $lastcolumn; $column++) {
                        $col = $number++;
                        $colname = 'day'.$col; 

                        if(isset($collectedAttndc->$colname)){

                            if ($collectedAttndc->$colname == 1){

                                $currentSheet->setCellValue($column.$cellCounter, "P");
                                
                            }else if ($collectedAttndc->$colname == 2){
                                
                                $currentSheet->setCellValue($column.$cellCounter, "A");
    
                            }elseif ($collectedAttndc->$colname == 3){
    
                                $currentSheet->setCellValue($column.$cellCounter, "L");
                                
                            }elseif ($collectedAttndc->$colname == 4){
    
                                $currentSheet->setCellValue($column.$cellCounter, "E");
                                
                            }else{
    
                                $currentSheet->setCellValue($column.$cellCounter, "");
    
                            }

                        }else{

                            $currentSheet->setCellValue($column.$cellCounter, "");
                        }
                        
                    }

                }

            }

            $styleArray = [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'e6b8b7',
                    ],
              
                ],
            ];
            
            $cellCounter += 1;
            $currentSheet->mergeCells('A'.($cellCounter).':C'.$cellCounter);
            
            $currentSheet->setCellValue('A'.$cellCounter, "FEMALE");

            for ($column = 'A'; $column != 'AQ'; $column++) {
                $currentSheet->getStyle($column.$cellCounter)->applyFromArray($styleArray);
            }

            //FEMALES
            foreach ($enrollstudents as $student) {
                if($student->gender == "FEMALE"){

                    $cellCounter++;
                    $count = $counter++;

                    $middlename = "";
                    if($student->middlename == "" || $student->middlename == null) {
                        $middlename = "";
                    }else{
                        $middlename = substr($student->middlename, 0, 1).'.';
                    }
                    
                    $currentSheet->setCellValue('A'.$cellCounter, $count);
                    $currentSheet->setCellValue('B'.$cellCounter, $student->lastname.', '.$student->firstname.' '.$middlename);

                    $collectedAttndc = collect($attendance)
                        ->where('studid', $student->studid)
                        ->where('monthid', (int) $months[$i])
                        // ->where('yearid', $element['yearid'])
                        ->first();

                    $number = 1;
                    for ($column = 'D'; $column != $lastcolumn; $column++) {
                        $col = $number++;
                        $colname = 'day'.$col;

                        if(isset($collectedAttndc->$colname)){

                            if ($collectedAttndc->$colname == 1){

                                $currentSheet->setCellValue($column.$cellCounter, "P");
                                
                            }else if ($collectedAttndc->$colname == 2){
                                
                                $currentSheet->setCellValue($column.$cellCounter, "A");
    
                            }elseif ($collectedAttndc->$colname == 3){
    
                                $currentSheet->setCellValue($column.$cellCounter, "L");
                                
                            }elseif ($collectedAttndc->$colname == 4){
    
                                $currentSheet->setCellValue($column.$cellCounter, "E");
                                
                            }else{
    
                                $currentSheet->setCellValue($column.$cellCounter, "");
    
                            }

                        }else{

                            $currentSheet->setCellValue($column.$cellCounter, "");
                        }
                        
                    }

                }
            }

            $cellCounter += 1;
            //Delete ang sobra nga rows
            for ($x=$cellCounter; $x <= 70; $x++) { 

                $currentSheet->removeRow($cellCounter);
            }
            $lastcolumn++;
            for ($y=$lastcolumn; $y <= "AI"; $y++) { 

                $currentSheet->removeColumn($lastcolumn);
            }


        }


        
        // Delete ang sobra nga rows

        // $cellCounter += 1;
        
        // for ($i=$cellCounter; $i <= 70; $i++) { 

        //     $currentSheet->removeRow($cellCounter);

        // }

        



        //
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="college-attendance-temp.xlsx"');
        $writer->save("php://output");
        exit();

    }

    public function clgattndcGetColumns(Request $reques){
        $monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"];
        $array = [];

        $sections = DB::table('college_classsched')
            ->join('college_schedgroup_detail', function ($join) use($reques){
                $join->on('college_classsched.id', 'college_schedgroup_detail.schedid')
                ->where('college_schedgroup_detail.id', $reques->sectionid)
                ->where('college_schedgroup_detail.deleted', 0);
            })
            ->select('college_classsched.sectionID')
            ->where('college_classsched.deleted', 0)
            ->first();

        $cols = DB::table('college_attendance')
            ->where('syid', $reques->syid)
            ->where('semid', $reques->semid)
            ->where('subjectid', $reques->subjectid)
            ->where('sectionid', $sections->sectionID)
            ->where('monthid',$reques->monthid)
            ->where('yearid',$reques->yearid)
            ->where('deleted', 0)
            ->get();


        if(collect($cols)->count() == 0){

            $datemonth = $reques->monthid;
            if($reques->monthid < 10){$datemonth = '0'.$reques->monthid;}

            array_push($array, [
                'date' => $monthNames[$reques->monthid-1].' 1',
                'col_name'=> 'day1', 
                'monthid'=> $reques->monthid, 
                'yearid'=> $reques->yearid, 
                'datadate'=> $reques->yearid.'-'.$datemonth.'-01'
            ]);

            return array (
                (object)[
                'status'=>400,
                'monthid'=>$reques->monthid,
                'yearid'=>$reques->yearid,
                'data'=>$array,
    
            ]); 

        }else{

            foreach ($cols as $col) {

                for ($i=1; $i < 33; $i++) { 
                    
                    $colname = 'day'.$i;
                    if($col->$colname != 0){
                        
                        $dateday = $i;
                        $datemonth = $reques->monthid;
                        if($i < 10){$dateday = '0'.$i;}

                        if($reques->monthid < 10){$datemonth = '0'.$reques->monthid;}
        
                        array_push($array, [
                            'date' => $monthNames[$reques->monthid-1].' '.$i,
                            'col_name'=> 'day'.$i, 
                            'monthid'=> $reques->monthid, 
                            'yearid'=> $reques->yearid, 
                            'datadate'=> $reques->yearid.'-'.$datemonth.'-'.$dateday
                        ]);
                                                            

                    }
                }


            }

            
            if(count($array) == 0){

                $datemonth = $reques->monthid;
                if($reques->monthid < 10){$datemonth = '0'.$reques->monthid;}
    
                array_push($array, [
                    'date' => $monthNames[$reques->monthid-1].' 1',
                    'col_name'=> 'day1', 
                    'monthid'=> $reques->monthid, 
                    'yearid'=> $reques->yearid, 
                    'datadate'=> $reques->yearid.'-'.$datemonth.'-01'
                ]);
    
                return array (
                    (object)[

                    'status'=>404,
                    'data'=>$array,
        
                ]); 
            }


            $final  = array();

            foreach ($array as $current) {
                if ( ! in_array($current, $final)) {
                    $final[] = $current;
                }
            }

            return array (
                (object)[

                'status'=>200,
                'data'=>$final,

            ]); 
        }

    }
}
