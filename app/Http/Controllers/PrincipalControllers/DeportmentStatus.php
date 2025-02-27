<?php

namespace App\Http\Controllers\PrincipalControllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;
use Session;

class DeportmentStatus extends \App\Http\Controllers\Controller
{
    //
    public function show(){

        $sy = DB::table('sy')
            ->select(
                
                'id',
                'isactive',
                'sydesc as text'
            )
			->orderBy('sydesc', 'desc')
            ->get();

        $teacher = DB::table('teacher')
            ->where('deleted', 0)
            ->select(
                'id',
                'firstname',
                'lastname',
                'firstname as text'
            )
            ->get();

        $section = DB::table('sections')
            ->join('gradelevel', 'sections.levelid', 'gradelevel.id')
            ->where('gradelevel.id', '<', 17)
            ->where('sections.deleted', 0)
            ->select(
                
                'sections.id',
                'sections.sectionname as text'
            )
            ->get();

    

        return view('principalsportal.pages.grading.deportment-status',[
            'sy' => $sy,
            'sections' => $section,
            'teachers' => $teacher,
            // 'values' => $values,
            // 'items' => $items,
            // 'deportments' => $deportment,
            // 'transmutation' => $transmutation

        ]);
        
    }

    public function get_deportment_details(Request $request){

        $secid = DB::table('sections')
            ->where('deleted', 0)
            ->select(
                'id'
            )
            ->get();

        $arraysecid = [];
        $statuspersec = [];

        foreach ($secid as $sections) {
            
            $arraysecid[] = $sections->id;

            $array = [];
            
            $submitted = $this->countbysection($request, 2, $sections->id);
            $pending = $this->countbysection($request, 3, $sections->id);
            $aproved = $this->countbysection($request, 4, $sections->id);
            $posted = $this->countbysection($request, 5, $sections->id);

            array_push($array, $submitted, $pending, $aproved, $posted);

            $statuspersec[$sections->id] = $array;

        }

        $currentPortal = Session::get('currentPortal');
        $teacherID = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->where('deleted', 0)
            ->first()
            ->id;

        $teacheracadprog = DB::table('teacheracadprog')
            ->where('acadprogutype', $currentPortal)
            ->where('teacherid', $teacherID)
            ->where('syid', $request->sy)
            ->where('deleted', 0)
            ->select(
                'acadprogid'
            )
            ->get();

        $acadprog = [];

        foreach($teacheracadprog as $item){

            array_push($acadprog, $item->acadprogid);

        }

        // return $acadprog;

        $deportment = DB::table('sectiondetail')
            ->leftJoin('deportment_hps', function ($join) {
                $join->on('sectiondetail.sectionid', 'deportment_hps.sectionid');
            })
            ->join('sections', function ($join) {
                $join->on('sectiondetail.sectionid','sections.id');
            })
            ->leftJoin('teacher', function ($join) {
                $join->on('sectiondetail.teacherid', 'teacher.id');
            })
            ->join('gradelevel', function ($join) {
                $join->on('sections.levelid', 'gradelevel.id');
            })
            // ->join('teacheracadprog', function ($join) {
            //     $join->on('gradelevel.acadprogid', 'teacheracadprog.acadprogid');
            // })
            
            ->where('sectiondetail.syid', $request->sy)
            ->where('deportment_hps.syid', $request->sy)
            ->where('deportment_hps.quarter_ID', $request->quarter)
            ->where('teacher.deleted', 0)
            ->where('deportment_hps.deleted', 0)
            ->where('sectiondetail.deleted', 0)
            ->where('sections.deleted', 0)
            ->whereIn('gradelevel.acadprogid', $acadprog)
            ->whereIn('sections.id', $arraysecid)
            ->select(

                'sections.sectionname',
                'gradelevel.levelname',
                'sections.id',
                'teacher.title',
                'teacher.firstname',
                'teacher.lastname',
                'teacher.tid',
                'deportment_hps.gradestatus',
                'deportment_hps.deportment_setupid'
            )
            ->get();

            
        

        // return collect($statuspersec);

        foreach ($statuspersec as $key => $value) {
            
            foreach ($deportment as $dep) {
                
                if($dep->id == $key){

                    $dep->gradestatus = $value;

                }
            }
        }
        
        return $deportment;

    }

    public function get_student_list(Request $request){

        $notsubmitted = $this->count($request, 1);
        $submitted = $this->count($request, 2);
        $pending = $this->count($request, 3);
        $aproved = $this->count($request, 4);
        $posted = $this->count($request, 5);
    

        return array (
                (object)[
    
                'notsubmitted'=>$notsubmitted,
                'submitted'=>$submitted,
                'pending'=>$pending,
                'aproved'=>$aproved,
                'posted'=>$posted,
                
            ]); 
    }

    public function get_student_status(Request $request){

        $status = [];


        array_push($status, (object)[
            'id' => 1,
            'text' => 'Not Submitted',
            'isactive' => 0,
        ]);

        array_push($status, (object)[
            'id' => 2,
            'text' => 'Submitted',
            'isactive' => 0,
        ]);


        array_push($status, (object)[
            'id' => 3,
            'text' => 'Pending',
            'isactive' => 0,
        ]);


        array_push($status, (object)[
            'id' => 4,
            'text' => 'Aproved',
            'isactive' => 0,
        ]);


        array_push($status, (object)[
            'id' => 5,
            'text' => 'Posted',
            'isactive' => 0,
        ]);

        $i = 1;
        foreach ($status as $value) {
            
            if($i == $request->status){

                $value->isactive = 1;
            }
            $i++;
        }


        $students = DB::table('student_deportment')
		
			->join('enrolledstud', function ($join) {
                $join->on('enrolledstud.id', '=', 'student_deportment.studid');
            })
			->join('studinfo', function ($join) {
                $join->on('studinfo.id', '=', 'enrolledstud.studid');
            })
			->join('sections', function ($join) {
                $join->on('student_deportment.sectionid', '=', 'sections.id');
            })
            ->join('sectiondetail', function ($join) {
                $join->on('sections.id', '=', 'sectiondetail.sectionid');
            })
            ->join('teacher', function ($join) {
                $join->on('sectiondetail.teacherid', '=', 'teacher.id');
            })
            ->join('gradelevel', function ($join) {
                $join->on('sections.levelid', 'gradelevel.id');
            })
            ->where('student_deportment.syid', $request->sy)
            ->where('student_deportment.quarter_ID', $request->quarter)
            ->where('student_deportment.deleted', 0)
            ->where('student_deportment.gradestatus', $request->status)
            ->select(
                'student_deportment.*',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.lastname',
                'studinfo.sid',
                'sections.sectionname',
                'gradelevel.levelname',
                'teacher.title',
                'teacher.tid',
                'teacher.firstname as teacherfirtname',
                'teacher.lastname as teacherlastname'
            )
            ->get();

        return array (
            (object)[

            'student'=>$students,
            'status'=>$status,
            

        ]);
    }

    public function filter_student_status(Request $request){

        $students = DB::table('student_deportment')
            ->join('studinfo', function ($join) {
                $join->on('studinfo.id', 'student_deportment.studid');
            })
            ->join('sectiondetail', function ($join) {
                $join->on('student_deportment.sectionid', 'sectiondetail.id');
            })
            ->join('teacher', function ($join) {
                $join->on('sectiondetail.teacherid', 'teacher.id');
            })
            ->join('sections', function ($join) {
                $join->on('student_deportment.sectionid', 'sections.id');
            })
            ->where('student_deportment.syid', $request->sy)
            ->where('student_deportment.quarter_ID', $request->quarter)
            ->where('student_deportment.deleted', 0)
            ->where('student_deportment.gradestatus', $request->status)
            ->where('student_deportment.sectionid', $request->section)
            // ->where('student_deportment.gradestatus', $request->teacher)
            ->select(
                'student_deportment.*',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.lastname',
                'sections.sectionname',
                'teacher.title',
                'teacher.firstname as teacherfirtname',
                'teacher.lastname as teacherlastname'
            )
            ->get();

        return $students;
    }

    public function loadtable(Request $request){

        $data = DB::table('enrolledstud')
            ->join('student_deportment', function ($join) {
                $join->on('enrolledstud.id', 'student_deportment.studid');
            })
            ->join('studinfo', function ($join) {
                $join->on('student_deportment.studid', 'studinfo.id');
            })
            ->select('student_deportment.*', 'studinfo.gender' ,'studinfo.firstname', 'studinfo.lastname', 'studinfo.middlename')
            ->where('enrolledstud.syid', $request->syid)
            ->where('enrolledstud.sectionid', $request->sectionid)
            ->where('student_deportment.quarter_ID', $request->quarterid)
            // ->where('student_deportment.gradestatus', $request->grade_status)
            ->whereIn('enrolledstud.studstatus', [1,2,4])
			->orderBy('studinfo.lastname', 'asc')
            ->get();


        $hps =  DB::table('deportment_hps')
            ->where('syid',$request->syid)
            ->where('sectionid',$request->sectionid)
            ->where('quarter_ID',$request->quarterid)
            ->where('deleted',0)
            ->first();

        $deportmentID =  DB::table('deportment_hps')
            ->where('syid',$request->syid)
            ->where('sectionid',$request->sectionid)
            ->where('quarter_ID',$request->quarterid)
            ->where('deleted',0)
            ->select('deportment_setupid')
            ->first();

        $values = DB::table('deportment_values')
            ->where('deportment_setupID', $deportmentID->deportment_setupid)
            ->where('deleted','0')
            ->get();

            
        $items = DB::table('values_items')
            ->where('deleted', 0)
            ->orderBy('sort', 'asc')
            ->get();

        $items_search = DB::table('values_items')
            ->where('deportment_setupID', $deportmentID->deportment_setupid)
            ->where('deleted', 0)
            ->orderBy('sort', 'asc')
            ->get();

        $deportment_values ='
                <input type="hidden" id="section_id" value="'.$request->sectionid.'"> 
                <div class="table_container  table-responsive mt-1" style="height: 60vh">
                <table id="table" class="table deportment-table">
                    <tr  class="deportment-table-header1">

                        <th style="z-index: 100;  width: 5px"><div class="form-check"><input class="form-check-input" type="checkbox" id="checkAll"></div></th>
                        <th style=" z-index: 100; min-width: 250px; width: 300px">Student Name</th>';

                        
                        foreach($values as $value){

                            if(count($items) != null){

                                $count = 0;
                                
                                foreach($items as $item){
                                    if($value->id == $item->values_setupID){
                                        $count++;
                                    }
                                }

                                if($count != 0){
                                    
                                    $deportment_values .='<th colspan="'.$count.'" style="text-align: center">
                                    '.$value->value_desc.'</th>';
                                }

                            }
                        }
                        
                        $deportment_values .='<th></th>
                        <th style="text-align: center">Initial</th>
                        <th style="text-align: center">Quarterly</th>


                    </tr>
                
                    <tr  class="deportment-table-header2">

                        <th style="z-index: 100; " colspan="2"></th>';

                        foreach($values as $value){
                            
                            foreach($items as $item){
                                if($value->id == $item->values_setupID){
                                    $deportment_values .='<th style="max-width: 30px;  min-width: 30px; text-align: center;  overflow: hidden;" >'.$item->value_item_abbr.'</th>';
                                }
                    
                            }
                        }

                        $deportment_values .='<th class="text-center">Total</th>
                        <th class="text-center">Initial</th>
                        <th class="text-center">Final</th>


                    </tr>
                    
                    <tr row_id="'.$hps->id.'" class="deportment-table-header3 hps-holder">';
                        $deportment_values .='
                            <th colspan="2" style="z-index: 100; width: 200px; text-align: center;">Highest Possible Score</th>';
                        $sum = 0; 
                        $col_count = 1;
                        $col_val = 0;
                        foreach($values as $value){
                            
                            foreach($items as $item){
                                
                                if($value->id == $item->values_setupID){
                                    
                                    $col = $col_count++;
                                    $col_val = 'col'.$col;

                                    
                                    $deportment_values .='<th style="text-align: center;  max-width: 30px;  min-width: 30px;">'.$hps->$col_val.'</th>';

                                    $sum+= $hps->$col_val;

                                }

                            }
                            
                        }


                    
                        $deportment_values .='<th class="text-center"><p style="margin-bottom: 0px" id="over_all_total">'.$sum.'</p></th>
                        <div>

                            <th class="text-center">100</th>
                            <th class="text-center">100</th>
                        </div>


                    </tr>

                    <tr>
                        <td colspan="2" class="male-color text-center">Male</td>
                        <td colspan="100%" class="male-color"></td>
                    </tr>

                    <tbody> ';

                    $count = 1;

                    foreach($data as $student){

                        $total = 0;
                        $sum = 0;
                        $status = "";
                        $color = "";
                        $isDisable = ' ';

                        if($student->gender == "MALE"){

                            if($student->gradestatus == 1){

                                $status = "Not Submitted";
                                $color = "secondary";
								$isDisable = "disabled";
                            } 
                            else if($student->gradestatus == 2){

                                $status = "Submitted";
                                $color = "success";

                            }
                            else if($student->gradestatus == 3){
                                
                                $status = "Pending";
                                $color = "warning";
                                $isDisable = "disabled";

                            }
                            else if($student->gradestatus == 4){
                                
                                $status = "Aproved";
                                $color = "primary";

                            }
                            else if($student->gradestatus == 5){
                                
                                $status = "Posted";
                                $color = "info";

                            }

                            $deportment_values .='<tr class="items" row_id="'. $student->id .'">';

                            $row_count = $count++;
                            $deportment_values .='<td ><div class="form-check checkbox_changestat"><input '.$isDisable.' class="form-check-input" value="'.$student->studid.'" name="status_checkbox" type="checkbox"></div></td>
                            <td>'. $student->lastname.', '.$student->firstname .'<span style="float: right; width: 70px;" class="badge badge-'.$color.' mr-2">'.$status.'</span></td>';

                            $isEditable = "false";

                            if($student->gradestatus == 2  || $student->gradestatus == 3 || $student->gradestatus == 4 || $student->gradestatus == 5){
                                
                                $isEditable = "false";
                            
                            }else{

                                $isEditable = "true";

                            }

                            for ($i=1; $i < count($items_search)+1; $i++) { 
                        
                                $col_var = 'col'.$i;

                                if($student->$col_var == 0){

                                    $deportment_values .= '<td style="text-align: center;  max-width: 30px;  min-width: 30px;" ></td>';

                                }else{

                                    $deportment_values .= '<td style="text-align: center;  max-width: 30px;  min-width: 30px;">'.$student->$col_var.'</td>';

                                }

                            }

                            $deportment_values .='<td class="text-center">'.$student->total.'</td>
                            <td class="text-center">'.$student->initial.'</td>
                            <td class="text-center">'.$student->final.'</td>';


                            $deportment_values .='</tr>';
                        }
                        
                    }

                    $deportment_values .= '<tr>
                        <td colspan="2" class="text-center female-color">Female</td>
                        <td colspan="100%" class="female-color"></td>
                    </tr>';


                    foreach($data as $student){

                        $total = 0;
                        $sum = 0;
                        $status;
                        $color;
                        $isDisable = ' ';

                        if($student->gender == "FEMALE"){

                            if($student->gradestatus == 1){

                                $status = "Not Submitted";
                                $color = "secondary";
								$isDisable = "disabled";
                            } 
                            else if($student->gradestatus == 2){

                                $status = "Submitted";
                                $color = "success";

                            }
                            else if($student->gradestatus == 3){
                                
                                $status = "Pending";
                                $color = "warning";
                                $isDisable = "disabled";

                            }
                            else if($student->gradestatus == 4){
                                
                                $status = "Aproved";
                                $color = "primary";

                            }
                            else if($student->gradestatus == 5){
                                
                                $status = "Posted";
                                $color = "info";

                            }
                            $deportment_values .='<tr class="items" row_id="'. $student->id .'">';

                            $row_count = $count++;
                            $deportment_values .='<td ><div class="form-check checkbox_changestat"><input '.$isDisable.'  class="form-check-input" value="'.$student->studid.'" name="status_checkbox" type="checkbox"></div></td>
                            <td>'. $student->lastname.', '.$student->firstname .'<span style="float: right; width: 70px;" class="badge badge-'.$color.' mr-2">'.$status.'</span></td>';

                            $isEditable = "false";

                            if($student->gradestatus == 2  || $student->gradestatus == 3 || $student->gradestatus == 4 || $student->gradestatus == 5){
                                
                                $isEditable = "false";
                            
                            }else{

                                $isEditable = "true";

                            }

                            for ($i=1; $i < count($items_search)+1; $i++) { 
                        
                                $col_var = 'col'.$i;

                                if($student->$col_var == 0){

                                    $deportment_values .= '<td style="text-align: center;  max-width: 30px;  min-width: 30px;" ></td>';

                                }else{

                                    $deportment_values .= '<td style="text-align: center;  max-width: 30px;  min-width: 30px;">'.$student->$col_var.'</td>';

                                }

                            }

                            $deportment_values .='<td class="text-center">'.$student->total.'</td>
                            <td class="text-center">'.$student->initial.'</td>
                            <td class="text-center">'.$student->final.'</td>';


                            $deportment_values .='</tr>';
                        }
                        
                    }


                    // foreach($data as $student){

                    //     $total = 0;
                    //     $sum = 0;

                    //     if($student->gender == "FEMALE"){

                    //         $deportment_values .='<tr class="items" row_id="'. $student->id .'">';

                    //         $row_count = $count++;
                    //         $deportment_values .='<td ><div class="form-check checkbox_changestat"><input class="form-check-input" value="'.$student->studid.'" name="status_checkbox" type="checkbox"></div></td>
                    //         <td><span class="badge badge-primary mr-2">'.$student->gradestatus.'</span>'. $student->lastname.', '.$student->firstname .'</td>';

                    //         $isEditable = "false";

                    //         if($student->gradestatus == 2  || $student->gradestatus == 3 || $student->gradestatus == 4 || $student->gradestatus == 5){
                                
                    //             $isEditable = "false";
                            
                    //         }else{

                    //             $isEditable = "true";

                    //         }

                    //         for ($i=1; $i < count($items_search)+1; $i++) { 
                        
                    //             $col_var = 'col'.$i;

                    //             if($student->$col_var == 0){

                    //                 $deportment_values .= '<td style="text-align: center;  max-width: 30px;  min-width: 30px;" ></td>';

                    //             }else{

                    //                 $deportment_values .= '<td style="text-align: center;  max-width: 30px;  min-width: 30px;">'.$student->$col_var.'</td>';

                    //             }

                    //         }

                    //         $deportment_values .='<td class="text-center">'.$student->total.'</td>
                    //         <td class="text-center">'.$student->initial.'</td>
                    //         <td class="text-center">'.$student->final.'</td>';


                    //         $deportment_values .='</tr>';
                    //     }
                        
                    // }

              

                    $deportment_values .= '</tbody>
                </table>
            </div>';

        return $deportment_values;
    }

    public function update_gradestatus(Request $request){

        $idarray = $request->array;

        foreach ($idarray as $id) {

            DB::table('student_deportment')
            ->where('studid', $id)
            ->where('syid', $request->syid)
            ->where('sectionid', $request->sectionid)
            ->where('quarter_ID', $request->quarter_ID)
            ->update([
                'gradestatus' => $request->status,
            ]);
        }

        DB::table('deportment_hps')
            ->where('id', $request->hpsid)
            ->where('syid', $request->syid)
            ->where('quarter_ID', $request->quarter_ID)
            ->update([
                'gradestatus' => $request->status,
            ]);

        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Succesfully Approved!',

        ]); 

    }

    public function update_specific_gradestatus(Request $request){

        DB::table('student_deportment')
            ->where('id', $request->studid)
            ->where('syid', $request->sy)
            ->where('quarter_ID', $request->quarter)
            ->update([
                'gradestatus' => $request->status,
        ]);

        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Succesfully Changed!',
            'currentStudStatus'=>$request->currentStatus,

        ]); 

    }


    public static function count($request, $status){

        $currentPortal = Session::get('currentPortal');
        $teacherID = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->where('deleted', 0)
            ->first()
            ->id;

        $teacheracadprog = DB::table('teacheracadprog')
            ->where('acadprogutype', $currentPortal)
            ->where('teacherid', $teacherID)
            ->where('syid', $request->sy)
            ->where('deleted', 0)
            ->select(
                'acadprogid'
            )
            ->get();

        $acadprog = [];

        foreach($teacheracadprog as $item){

            array_push($acadprog, $item->acadprogid);

        }

        $count = DB::table('student_deportment')
            ->leftJoin('sections', function ($join) {
                $join->on('sections.id', 'student_deportment.sectionid');
            })
            ->join('gradelevel', function ($join) {
                $join->on('gradelevel.id', 'sections.levelid');
            })
            ->where('student_deportment.syid', $request->sy)
            ->where('student_deportment.quarter_ID', $request->quarter)
            ->where('student_deportment.deleted', 0)
            ->where('student_deportment.gradestatus', $status)
            ->whereIn('gradelevel.acadprogid', $acadprog)
            ->get()
            ->count();

        return $count;
    }


    public static function countbysection($request, $status, $sectionid){

        $count = DB::table('student_deportment')
            ->where('syid', $request->sy)
            ->where('quarter_ID', $request->quarter)
            ->where('sectionid', $sectionid)
            ->where('deleted', 0)
            ->where('gradestatus', $status)
            ->get()
            ->count();

        return $count;
    }

}
