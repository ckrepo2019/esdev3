<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;
use PDF;

class DeportmentRecordController extends \App\Http\Controllers\Controller
{

    public function show(){

        $sy = DB::table('sy')
            ->select(
                
                'id',
                'isactive',
                'sydesc as text'
            )
            ->get();

        $teacherID = DB::table('teacher')
                ->where('tid',auth()->user()->email)
                ->where('deleted', 0)
                ->first()
                ->id;

        $teacher = DB::table('teacher')
            ->where('deleted', 0)
            ->where('userid', $teacherID)
            ->get();

        $section = DB::table('sections')
            ->join('sectiondetail', 'sections.id', 'sectiondetail.sectionid')
            ->where('sectiondetail.teacherid', $teacherID)
            ->select(
                
                'sections.id',
                'sections.sectionname as text'
            )
            ->get();

            

        $values = DB::table('deportment_values')
            ->where('deleted', 0)
            ->get();
            
        $items = DB::table('values_items')
            ->where('deleted', 0)
            ->orderBy('sort', 'asc')
            ->get();

        $deportment = DB::table('deportment_setup')
            ->where('deleted', 0)
            ->select(
                
                'id',
                'deportment_desc as text'
            )
            ->get();

        $transmutation = DB::table('deportment_transmutation')
            ->get();

        return view('superadmin.pages.teacher.deportment-record',[
            'sy' => $sy,
            'sections' => $section,
            // 'data' => $data,
            'values' => $values,
            'items' => $items,
            'deportments' => $deportment,
            'transmutation' => $transmutation

        ]);
        
    }

    public function search_deportment(Request $request){

        if($request->ajax()){

            $check = DB::table('deportment_hps')
                ->where('syid',$request->data[0]['value'])
                ->where('sectionid',$request->data[1]['value'])
                ->where('quarter_ID',$request->data[2]['value'])
                ->where('deleted',0)
                ->count();

            if($check == 0){

                DB::table('deportment_hps')
                ->insert([
                    'syid'=> $request->data[0]['value'],
                    'sectionid'=> $request->data[1]['value'],
                    'quarter_ID'=> $request->data[2]['value'],
                    'deportment_setupid'=> $request->data[3]['value'],
                ]);

            }
            
            $hps =  DB::table('deportment_hps')
                ->where('syid',$request->data[0]['value'])
                ->where('sectionid',$request->data[1]['value'])
                ->where('quarter_ID',$request->data[2]['value'])
                ->where('deleted',0)
                ->first();
            
            $enrollstudents = DB::table('enrolledstud')
                ->where('syid', $request->data[0]['value'])
                ->where('sectionid', $request->data[1]['value'])
                ->whereIn('enrolledstud.studstatus', [1,2,4])
                ->get();

                

            foreach ($enrollstudents as $enrollstudent) {

                $check = DB::table('student_deportment')
                    ->where('studid',$enrollstudent->id)
                    ->where('syid',$enrollstudent->syid)
                    ->where('quarter_ID',$request->data[2]['value'])
                    ->where('deleted',0)
                    ->count();

                if($check == 0){

                    DB::table('student_deportment')
                    ->insert([
                        'studid'=> $enrollstudent->id,
                        'syid'=> $enrollstudent->syid,
                        'sectionid'=> $enrollstudent->sectionid,
                        'levelid'=> $enrollstudent->levelid,
                        'quarter_ID'=> $request->data[2]['value'],
                        'gradestatus'=> 1,
                    ]);

                }
            }

 
            $data = DB::table('enrolledstud')
                ->join('student_deportment', 'enrolledstud.id', 'student_deportment.studid')
                ->join('studinfo', 'student_deportment.studid', 'studinfo.id')
                ->select('student_deportment.*', 'studinfo.gender' ,'studinfo.firstname', 'studinfo.lastname', 'studinfo.middlename')
                ->where('enrolledstud.syid', $request->data[0]['value'])
                ->where('enrolledstud.sectionid', $request->data[1]['value'])
                ->where('student_deportment.quarter_ID', $request->data[2]['value'])
                ->whereIn('enrolledstud.studstatus', [1,2,4])
				->orderBy('studinfo.lastname', 'asc')
                ->get();
            

            $values = DB::table('deportment_values')
                ->where('deportment_setupID', $request->data[3]['value'])
                ->where('deleted','0')
                ->get();

                
            $items = DB::table('values_items')
                ->where('deleted', 0)
                ->orderBy('sort', 'asc')
                ->get();

            $items_search = DB::table('values_items')
                ->where('deportment_setupID', $request->data[3]['value'])
                ->where('deleted', 0)
                ->orderBy('sort', 'asc')
                ->get();

            $signatory = DB::table('deportment_signatory')
                ->where('deleted', 0)
                ->where('syid', $request->data[0]['value'])
                ->where('sectionid', $request->data[1]['value'])
                ->where('quarter', $request->data[2]['value'])
                ->get();

            $deportment_values='';
            $values_item = '';
            $sign = '';

            if(count($values) > 0){

                $deportment_values ='
                    <div class="table_container">
					<div class="text-left p-1 bg-danger" style="font-size: 15px"><i class="fas fa-exclamation-circle pl-2 pr-2"></i> Grading is automatic upon input.</div>

                    <table id="table" class="table deportment-table">
                        <tr  class="deportment-table-header1">

                            <th style="z-index: 100;  width: 5px">#</th>
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

                            $deportment_values .='<th>Total</th>
                            <th>Initial</th>
                            <th>Final</th>


                        </tr>
                        
                        <tr row_id="'. $hps->id .'" class="deportment-table-header3 hps-holder">';
                            $deportment_values .='<th colspan="2"style="z-index: 100; width: 200px; text-align: center;">Highest Possible Score</th>';
                            $sum = 0; 
                            $col_count = 1;
                            $col_val;
                            foreach($values as $value){
                                
                                foreach($items as $item){
                                    
                                    if($value->id == $item->values_setupID){
                                        
                                        $col = $col_count++;
                                        $col_val = 'col'.$col;

                                        
                                        $deportment_values .='<th style="text-align: center;  max-width: 30px;  min-width: 30px; white-space: nowrap; overflow: hidden;" ><div col="'.$col.'" id="hpsrow'.$col.'" col_name="col'.$col.'" class="hps" contenteditable="true">'.$hps->$col_val.'</div></th>';

                                        $sum+= $hps->$col_val;

                                    }

                                }
                                
                            }


                        
                            $deportment_values .='<th><p style="margin-bottom: 0px" id="over_all_total">'.$sum.'</p></th>
                            <div>

                                <th>100</th>
                                <th>100</th>
                            </div>


                        </tr>
                        <tr>
                            <td colspan="2" style="z-index: 1" class="text-center male-color">Male</td>
                            <td colspan="100%" class="male-color"></td>
                        </tr>

                        <tbody> ';

                        $count = 1;

                        foreach($data as $student){

                            $total = 0;
                            $sum = 0;
                            $status;
                            $color;

                            $deportment_values .='<tr class="items" row_id="'. $student->id .'">';

                                if($student->gender == 'MALE'){

                                    if($student->gradestatus == 1){

                                        $status = "Not Submitter";
                                        $color = "secondary";
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
                                    else{

                                        false;
                                    }

                                    $row_count = $count++;
                                    $deportment_values .='<td >'. $row_count .'</td>
                                    <td>'. $student->lastname.', '.$student->firstname .'<span style="float: right; width: 70px;" class="badge badge-'.$color.' mr-2">'.$status.'</span></td></td>';

                                    $isEditable = "false";

                                    if($student->gradestatus == 2 || $student->gradestatus == 4 || $student->gradestatus == 5){
                                        
                                        $isEditable = "false";
                                    
                                    }else{

                                        $isEditable = "true";

                                    }

                                    for ($i=1; $i < count($items_search)+1; $i++) { 
                                
                                        $col_var = 'col'.$i;
    
                                        if($student->$col_var == 0){
    
                                            $deportment_values .= '<td style="text-align: center;  max-width: 30px;  min-width: 30px; white-space: nowrap; overflow: hidden;" ><div col="'.$i.'" row="'. $row_count.'" id="'. $i .'_'. $row_count .'" class="row_data" contenteditable="'.$isEditable.'"  edit_type="click" col_name="col'. $i.'" value=""></div></td>';
    
                                        }else{
    
                                            $deportment_values .= '<td style="text-align: center;  max-width: 30px;  min-width: 30px; white-space: nowrap; overflow: hidden;" ><div col="'.$i.'" row="'. $row_count.'" id="'. $i .'_'. $row_count .'" class="row_data" contenteditable="'.$isEditable.'"  edit_type="click" col_name="col'. $i .'">'.$student->$col_var.'</div></td>';

                                        }

                                    }

                                    $deportment_values .='<td><p class="tr_p" id="totalid'.$row_count.'">'.$student->total.'</p></td>
                                    <td><p class="tr_p"  row_id="'.$student->id.'" id="initialid'.$row_count.'">'.$student->initial.'</p></td>
                                    <td><p class="tr_p" id="finalid'.$row_count.'">'.$student->final.'</p></td>';
    
                                    

                                }

                            $deportment_values .='</tr>';
                            
                        }

                        $deportment_values .= '<tr>
                            <td colspan="2" style="z-index: 1" class="text-center female-color">Female</td>
                            <td colspan="100%" class="female-color"></td>
                        </tr>';

                        
                        
                            foreach($data as $student){

                                $total = 0;
                                $status;
                                $color;

                                $deportment_values .='<tr class="items" row_id="'. $student->id .'">';
    
                                    if($student->gender == 'FEMALE'){

                                        if($student->gradestatus == 1){

                                            $status = "Not Submitter";
                                            $color = "secondary";
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

                                        }else{

                                            false;
                                        }
            

                                        $row_count = $count++ ;
    
                                        $deportment_values .='<td >'.$row_count .'</td>
                                        <td>'. $student->lastname.', '.$student->firstname .'<span style="float: right; width: 70px;" class="badge badge-'.$color.' mr-2">'.$status.'</span></td></td>';

                                        $isEditable = "false";

                                        if($student->gradestatus == 2 || $student->gradestatus == 4 || $student->gradestatus == 5){
                                        
                                            $isEditable = "false";
                                        
                                        }else{
    
                                            $isEditable = "true";
    
                                        }
    
                                        for ($i=1; $i < count($items_search)+1; $i++) { 
                                    
                                            $col_var = 'col'.$i;
        
                                            if($student->$col_var == 0){
                                                
                                                $deportment_values .= '<td style="text-align: center;  max-width: 30px;  min-width: 30px; white-space: nowrap; overflow: hidden;"><div col="'.$i.'" row="'. $row_count.'" id="'. $i .'_'. $row_count .'" class="row_data" contenteditable="'.$isEditable.'"  edit_type="click" col_name="col'. $i .'" value=""></div></td>';
        
                                            }else{


                                                $deportment_values .= '<td style="text-align: center;  max-width: 30px; min-width: 30px; white-space: nowrap; overflow: hidden;"><div col="'.$i.'" row="'. $row_count .'" id="'. $i .'_'. $row_count .'" class="row_data" contenteditable="'.$isEditable.'"  edit_type="click" col_name="col'. $i .'" >'.$student->$col_var.'</div></td>';
                                            }

                                            $total += $student->$col_var;


                                        }

        
                                        $deportment_values .='<td><p class="tr_p" id="totalid'.$row_count.'">'.$student->total.'</p></td>
                                        <td><p class="tr_p" row_id="'.$student->id.'" id="initialid'.$row_count.'">'.$student->initial.'</p></td>
                                        <td><p class="tr_p" id="finalid'.$row_count.'">'.$student->final.'</p></td>';
    
                                    }else{

                                        $deportment_values .='<tr class="items" style"display:none" row_id="14"></tr>';
                                    }
    
                                $deportment_values .='</tr>';
                                
                            }

                        $deportment_values .= '</tbody>
                    </table>
                </div>';


                $sign .= '<div class="signatory-holder" signatory_count="'.count($signatory).'">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="base-rating-title">Signatory</h5> 
                        </div>

                        <div class="card-body">

                            <button type="button" class="btn btn-primary btn-sm signatory">
                                <i class="fas fa-plus"></i> Signatory
                            </button>
                            
                            <div>

                            <div class="row">';

                                    foreach($signatory as $signatory){

                                    $sign .= '<div class="col m-2">
        
                                        <div class="row">
                                            <div contentEditable="true" id="signatory_type" col_id="'.$signatory->id.'" col_name="type" class="col-md-11 m-0 signatory_data">
                                                '.$signatory->type.':
                                            </div>
                                            <div class="col-md-1">

                                                <i class="fas fa-times-circle delete_signatory"  value="'.$signatory->id.'"></i>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p contentEditable="true" id="signatory_name" col_id="'.$signatory->id.'" col_name="name" class="text-center m-0 signatory_data">'.$signatory->name.'</p>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div style="height: 1px; width: 100%; background: black; margin-left: 20px"></div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p contentEditable="true" id="signatory_position" col_id="'.$signatory->id.'" col_name="position" class="text-center m-0 signatory_data">'.$signatory->position.'</p>
                                            </div>
                                        </div>
                                    </div>';
                                    }
                                        
                            $sign .= '</div>
                        </div>
                    </div>
                </div>';

                                    

            // BASES FOR RATING

                $values_item = '
                <div class="base_rating_container" >
                
                    <div class="values">';
                            
                            foreach($values as $value){
                                $values_item .= '<div class="row">
                                    <div>
                                        <div class="base_rating">

                                            <input checked style="display: none" value="'.$value->id.'" class="form-check-input hide_show" type="checkbox" id="flexCheckDefault'.$value->id.'">
                                            <label class="form-check-label" for="flexCheckDefault'.$value->id.'">
                                            
                                            <h5 class="values_title">'. $value->value_desc .'</h5>
                                            </label>
                          
                                    </div>
                                        
                                        <ul class="rating_ul">';
                                            foreach($items as $item){
                                                
 
                                                if($value->id == $item->values_setupID){
                                                    if(  $item->value_item_desc != null){
                                                        
                                                        $values_item .= '<li class="base_rating">

                                                        <input checked style="display: none" value="'.$item->id.'" class="form-check-input item_hide_show" type="checkbox" id="itemCheckDefault'.$item->id.'">
                                                        <label class="form-check-label" for="itemCheckDefault'.$item->id.'">
                                                            <p class="li_p">'. $item->value_item_abbr .' - '. $item->value_item_desc  .'</p>
                                                        </label>
                
                                                        </li>';
                                                    }
                                                }
                                
                                            }


                                    $values_item .= '</ul>
          
                                </div>
                                </div>';
                            }
                            $values_item .= '</div>
                        </div>';
            
            // 

                
            } else{
        
                $deportment_values ='
                    <div class="table_container">
                    <table id="table" class="table subscbr-tbl">
                        <thead  class="thead-dark">

                            <th style="width: 5px">#</th>
                            <th style="width: 250px">Student Name</th>';
                            
                            $deportment_values .='<th></th>

                            <th style="text-align: center">Initial</th>
                            <th style="text-align: center">Quarterly</th>


                        </thead>
                    
                        <thead  class="thead-dark">

                            <th colspan="2"></th>';

            

                            $deportment_values .='<th>Total</th>
                            <th>Grade</th>
                            <th>Grade</th>


                        </thead>
                        
                        <thead class="table-light">';
                            $deportment_values .='<th colspan="2" style="width: 200px; text-align: center;">Highest Possible Score</th>';
                            $sum = 0; 
                            foreach($values as $value){
                                
                                foreach($items as $item){
                                    if($value->id == $item->values_setupID){
                                        
                                        $sum+= $item->value_highest_score;
                                        
                                    }

                                }
                                
                            }


                        
                            $deportment_values .='<th>'.$sum.'</th>
                            <th>100.00</th>
                            <th>100.00</th>


                        </thead>
                        <tr class="table-danger">
                            <td colspan="100%" style="text-align: center;">Female</td>

                        </tr>

                        <tbody> ';

                        $count = 1;
                        
                        foreach($data as $student){
                    
                            $deportment_values .='<tr class="items" row_id="'.$student->id.'">';
                                
                                if($student->gender == 'FEMALE'){
                                    
                                    $deportment_values .='<td >'. $count++ .'</td>
                                    <td>'. $student->lastname.','.$student->firstname.' '.$student->middlename.'</td>
                                    
                                    <td></td>
                                    <td></td>
                                    <td></td>';
                                }

                            $deportment_values .='</tr>';
                        
                        }

                        $deportment_values .= '<tr class="table-primary">
                                <td colspan="100%" style="text-align: center;">Male</td>
                            </tr>';

                        
                    
                        foreach($data as $student){
                    
                            $deportment_values .='<tr class="items" row_id="'.$student->id.'">';
                                
                                if($student->gender == 'MALE'){
                                    
                                    $deportment_values .='<td >'. $count++ .'</td>
                                    <td>'. $student->lastname.','.$student->firstname.' '.$student->middlename.'</td>
                                    
                                    <td></td>
                                    <td></td>
                                    <td></td>';
                                }

                            $deportment_values .='</tr>';
                        
                        }
    

                        $deportment_values .= '</tbody>
                    </table>
                </div>';

                $values_item .='<div class="card text-center">
                    <div class="card-body">
                    <p class="card-text">No Data Found.</p>
                    </div>
                </div>';


        
            }

            return array (
                (object)[
    
                'deportment_values'=>$deportment_values,
                'values_item'=>$values_item,
                'total_col'=>$col_count,
                'signatory'=>$sign,
   
            ]); 
        }
        
    }

    public function hps(Request $request){

        $query = DB::table('deportment_hps')
            ->where('id', $request->id)
            ->update([
                $request->col_name => $request->value,
            ]);


        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Succesfully Graded!',

        ]); 
    }

    public function grading_deportment(Request $request){

        $query = DB::table('student_deportment')
            ->where('id', $request->id)
            ->update([
                $request->col_name => $request->value,
                'total' => $request->total,
                'initial' => $request->initial,
                'final' => $request->final,
            ]);


        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Succesfully Graded!',

        ]); 
        
    }

    public function delete_signatory(Request $request){

        $query = DB::table('deportment_signatory')
            ->where('id', $request->id)
            ->update([

                'deleted' => 1,
            ]);


        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Deleted!',

        ]); 
        
    }
    
    public function add_signatory(Request $request){

        DB::table('deportment_signatory')
        ->insert([

            'name'=>$request->sign_name,
            'type'=>$request->sign_type,
            'position'=>$request->sign_position,

            'syid'=>$request->syid,
            'sectionid'=>$request->sectionid,
            'quarter'=>$request->quarter_ID

        ]);

        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Added!',

        ]); 
        
    }

    public function edit_signatory(Request $request){

        DB::table('deportment_signatory')
            ->where('id', $request->id)
            ->update([
                $request->col_name => $request->val,
        
            ]);

        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Saved!',

        ]); 
        
    }

    public function get_submit_grade(Request $request){
        

        $student_grade = DB::table('enrolledstud')
            ->join('student_deportment', 'enrolledstud.id', 'student_deportment.studid')
            ->join('studinfo', 'enrolledstud.studid', 'studinfo.id')
            ->select('student_deportment.*', 'studinfo.gender' ,'studinfo.firstname', 'studinfo.lastname', 'studinfo.middlename')
            ->where('enrolledstud.syid', $request->syid)
            ->where('enrolledstud.sectionid', $request->sectionid)
            ->where('student_deportment.quarter_ID', $request->quarter_ID)
            ->whereIn('student_deportment.gradestatus', [1,3])
            ->whereIn('enrolledstud.studstatus', [1,2,4])
            ->get();

        return $student_grade;
    }

    public function submit_student_grade(Request $request){

        $idarray = $request->array;

        foreach ($idarray as $id) {

            DB::table('student_deportment')
            ->where('id', $id)
            ->where('syid', $request->syid)
            ->where('sectionid', $request->sectionid)
            ->where('quarter_ID', $request->quarter_ID)
            ->update([
                'gradestatus' => 2,
            ]);
        }

        

        DB::table('deportment_hps')
            ->where('id', $request->hpsid)
            ->where('syid', $request->syid)
            ->where('quarter_ID', $request->quarter_ID)
            ->update([
                'gradestatus' => 2,
            ]);

        

        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Succesfully Submitted!',

        ]); 

    }
    
    public function generatePDF($syid, $sectionid, $quarter_ID, $deportment_setupID){

        $data = DB::table('enrolledstud')
            ->join('student_deportment', 'enrolledstud.id', 'student_deportment.studid')
            ->join('studinfo', 'student_deportment.studid', 'studinfo.id')
            ->select('student_deportment.*', 'studinfo.gender' ,'studinfo.firstname', 'studinfo.lastname', 'studinfo.middlename')
            ->where('enrolledstud.syid', $syid)
            ->where('enrolledstud.sectionid', $sectionid)
            ->where('student_deportment.quarter_ID', $quarter_ID)
            ->where('enrolledstud.studstatus', [1, 2, 3])
			->orderBy('studinfo.lastname', 'asc')
            ->get();

        $hps =  DB::table('deportment_hps')
            ->where('syid',$syid)
            ->where('sectionid',$sectionid)
            ->where('quarter_ID',$quarter_ID)
            ->where('deleted',0)
            ->first();
        
        $gradelvlid = DB::table('sections')
            ->where('id', $sectionid)
            ->where('deleted', 0)
            ->select(
                'levelid'
            )
            ->first();

        $gradelvl = DB::table('gradelevel')
            ->where('id', $gradelvlid->levelid)
            ->where('deleted', 0)
            ->select('levelname', 'acadprogid')
            ->first();

            
        $schoolyear = DB::table('sy')
            ->where('id', $syid)
            ->first();

        $sections = DB::table('sections')
            ->where('id', $sectionid)
            ->where('deleted', 0)
            ->select('sectionname')
            ->first();

        $values = DB::table('deportment_values')
            ->where('deportment_setupID', $deportment_setupID)
            ->where('deleted','0')
            ->get();
    
        $items = DB::table('values_items')
            ->where('deleted', 0)
            ->orderBy('sort', 'asc')
            ->get();

        $items_search = DB::table('values_items')
            ->where('deportment_setupID', $deportment_setupID)
            ->where('deleted', 0)
            ->orderBy('sort', 'asc')
            ->get();

        $schoolinfo = DB::table('schoolinfo')
            ->first();

        $signatory = DB::table('deportment_signatory')
            ->where('deleted', 0)
            ->where('syid', $syid)
            ->where('sectionid', $sectionid)
            ->where('quarter', $quarter_ID)
            ->get();


        // return collect($schoolyear);
        // return collect($schoolinfo);

        $pdf = PDF::loadView('superadmin.pages.printable.deportment-pdf', compact( 'hps', 'values', 'items', 'items_search', 'data', 'sections', 'schoolinfo', 'schoolyear', 'quarter_ID', 'gradelvl', 'signatory'))->setPaper('legal', 'landscape');
        
        $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);

        return $pdf->stream();
    }

    function generate_excel($syid, $sectionid, $quarter_ID, $deportment_setupID){

        $data = DB::table('enrolledstud')
            ->join('student_deportment', 'enrolledstud.id', 'student_deportment.studid')
            ->join('studinfo', 'student_deportment.studid', 'studinfo.id')
            ->select('student_deportment.*', 'studinfo.gender' ,'studinfo.firstname', 'studinfo.lastname', 'studinfo.middlename')
            ->where('enrolledstud.syid', $syid)
            ->where('enrolledstud.sectionid', $sectionid)
            ->where('student_deportment.quarter_ID', $quarter_ID)
            ->where('enrolledstud.studstatus', 1)
            ->orWhere('enrolledstud.studstatus', 2)
            ->orWhere('enrolledstud.studstatus', 4)
			->orderBy('studinfo.lastname', 'asc')
            ->get();
        
        $hps =  DB::table('deportment_hps')
            ->where('syid',$syid)
            ->where('sectionid',$sectionid)
            ->where('quarter_ID',$quarter_ID)
            ->where('deleted',0)
            ->first();
        
        $gradelvlid = DB::table('sections')
            ->where('id', $sectionid)
            ->where('deleted', 0)
            ->select(
                'levelid'
            )
            ->first();

        $gradelvl = DB::table('gradelevel')
            ->where('id', $gradelvlid->levelid)
            ->where('deleted', 0)
            ->select('levelname', 'acadprogid')
            ->first();

            
        $schoolyear = DB::table('sy')
            ->where('id', $syid)
            ->first();

        $sections = DB::table('sections')
            ->where('id', $sectionid)
            ->where('deleted', 0)
            ->select('sectionname')
            ->first();

        $values = DB::table('deportment_values')
            ->where('deportment_setupID', $deportment_setupID)
            ->where('deleted','0')
            ->get();

        $items = DB::table('values_items')
            ->where('deleted', 0)
            ->orderBy('sort', 'asc')
            ->get();
    
        $items1 = DB::table('values_items')
            ->where('deleted', 0)
            ->where('values_setupID', 1) // naka pa static
            ->orderBy('sort', 'asc')
            ->get();

        $items2 = DB::table('values_items')
            ->where('deleted', 0)
            ->where('values_setupID', 2) // naka pa static
            ->orderBy('sort', 'asc')
            ->get();

        $items_search = DB::table('values_items')
            ->where('deportment_setupID', $deportment_setupID)
            ->where('deleted', 0)
            ->orderBy('sort', 'asc')
            ->get();

        $schoolinfo = DB::table('schoolinfo')
            ->first();

        $signatory = DB::table('deportment_signatory')
            ->where('deleted', 0)
            ->where('syid', $syid)
            ->where('sectionid', $sectionid)
            ->where('quarter', $quarter_ID)
            ->get();
                
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load("deportment/deportment-temp.xlsx");


        //

        $quarter;
        $col = [];
        $rowid = 1;


        for( $i = 65; $i < 91; $i++){

            $col [] = (object) [$rowid++ => chr($i)];

        }


        $sheet = $spreadsheet->setActiveSheetIndex(0);


        $sheet->setCellValue('A1', $schoolinfo->schoolname);
        $sheet->setCellValue('A2', $schoolinfo->address);
        $sheet->setCellValue('A5', $sections->sectionname.' - '.$gradelvl->levelname);
        

        if($quarter_ID == 1){
            $quarter = 'First Quarter';
        }elseif ($quarter_ID == 2) {
            $quarter = 'Second Quarter';
        }elseif ($quarter_ID == 3) {
            $quarter = 'Third Quarter';
        }elseif ($quarter_ID == 4) {
            $quarter = 'Fouth Quarter';
        }else{

            return false;
        }

        $sheet->setCellValue('A6', $quarter);

        if($gradelvl->acadprogid == 2){

            $last_word = $gradelvl->levelname;

        }else{

            $last_word = str_replace('GRADE ', '', $gradelvl->levelname);
        }

        $sheet->setCellValue('A7', 'Christian Living Education '.$last_word);
        $sheet->setCellValue('Z40', 'Christian Living Education '.$last_word);
        $sheet->setCellValue('Z79', 'Christian Living Education '.$last_word);
        
        $sheet->setCellValue('A8', 'SY '.$schoolyear->sydesc);

        //A-1  B-2  C-3  D-4  E-5  F-6  G-7  H-8  I-9  J-10  K-11  L-12  M-13  N-14  O-15  P-16 Q-17  R-18  S-19  T-20  U-21  V-22  W-23  X-24  Y-25  Z-26

        $spacing = number_format(32/count($values));
        $column = 65;

        foreach ($values as $value) {

            $sheet->setCellValue(chr($column).'11', $value->value_desc);
            $column+= $spacing;
        }

        
        $item_count1 = 11;

        foreach ($items1 as $item) {
            $item_count1++;
            
            if($item->values_setupID == 1){

                $sheet->setCellValue('A'.$item_count1, $item->value_item_abbr);
                $sheet->setCellValue('B'.$item_count1, $item->value_item_desc);

            }
        }

        $item_count2 = 11;

        foreach ($items2 as $item) {
            $item_count2++;
            
            if($item->values_setupID == 2){

                $sheet->setCellValue('Q'.$item_count2, $item->value_item_abbr);
                $sheet->setCellValue('R'.$item_count2, $item->value_item_desc);
            }

            

        }

        $spacing = count($col)/count($values);

        $sheet->setCellValue('A35', $schoolinfo->schoolname);
        $sheet->setCellValue('A36', $schoolinfo->address);
        $sheet->setCellValue('A39', 'SY '.$schoolyear->sydesc);

        
        $sheet->setCellValue('A40', $sections->sectionname.' - '.$gradelvl->levelname);
        $sheet->setCellValue('A41', $quarter);

        $col1 = 70;
        $col2 = 64;

        $f = 6;
        $y = 25;

        $col_count = 1;
        $col_name;
        foreach ($items1 as $item) {

            $col = $col_count++;
            $col_name = 'col'.$col;  
             

            $col_val = chr($col1);

            if($col1 > 90){

                $col_val = chr(65);

                $col_val .= chr($col2);

                
            }
            
            $sheet->setCellValue($col_val.'44', $hps->$col_name);

            $sheet->setCellValue($col_val.'43', $item->value_item_abbr);

            $col1++;

            if($col1 > 90){

                $col2++;
            }


        }

        foreach ($items2 as $item) {

            $col = $col_count++;
            $col_name = 'col'.$col;


            $col_val = chr($col1);

            if($col1 > 90){

                $col_val = chr(65);

                $col_val .= chr($col2);

            }

            $sheet->setCellValue($col_val.'44', $hps->$col_name);

            $sheet->setCellValue($col_val.'43', $item->value_item_abbr);

            $col1++;

            if($col1 > 90){

                $col2++;
            }

        }


        $student_colM = 45;
        $student_male = 1;
        foreach ($data as $student) {
            
            if($student->gender == 'MALE'){
                $student_colM++;

                $sheet->setCellValue('A'.$student_colM, $student_male++);
                $sheet->setCellValue('B'.$student_colM, $student->lastname);
                $sheet->setCellValue('C'.$student_colM, ",");
                $sheet->setCellValue('D'.$student_colM, $student->firstname);
                $sheet->setCellValue('E'.$student_colM, substr($student->middlename, 0, 1));

                $sheet->setCellValue('F'.$student_colM, $student->col1);
                $sheet->setCellValue('G'.$student_colM, $student->col2);
                $sheet->setCellValue('H'.$student_colM, $student->col3);
                $sheet->setCellValue('I'.$student_colM, $student->col4);
                $sheet->setCellValue('J'.$student_colM, $student->col5);
                $sheet->setCellValue('K'.$student_colM, $student->col6);
                $sheet->setCellValue('L'.$student_colM, $student->col7);
                $sheet->setCellValue('M'.$student_colM, $student->col8);
                $sheet->setCellValue('N'.$student_colM, $student->col9);
                $sheet->setCellValue('O'.$student_colM, $student->col10);
                $sheet->setCellValue('P'.$student_colM, $student->col11);
                $sheet->setCellValue('Q'.$student_colM, $student->col12);
                $sheet->setCellValue('R'.$student_colM, $student->col13);
                $sheet->setCellValue('S'.$student_colM, $student->col14);
                $sheet->setCellValue('T'.$student_colM, $student->col15);
                $sheet->setCellValue('U'.$student_colM, $student->col16);
                $sheet->setCellValue('V'.$student_colM, $student->col17);
                $sheet->setCellValue('W'.$student_colM, $student->col18);
                $sheet->setCellValue('X'.$student_colM, $student->col19);
                $sheet->setCellValue('Y'.$student_colM, $student->col20);
                $sheet->setCellValue('Z'.$student_colM, $student->col21);
                $sheet->setCellValue('AA'.$student_colM, $student->col22);
                $sheet->setCellValue('AB'.$student_colM, $student->col23);
                $sheet->setCellValue('AC'.$student_colM, $student->col24);
                $sheet->setCellValue('AD'.$student_colM, $student->col25);

                $sheet->setCellValue('AG'.$student_colM, $student->final);

            }
            
        }

        $spacing1 = number_format(32/count($signatory));
        $column1 = 65;
        $column2 = 67;

        foreach ($signatory as $sign) {

            $sheet->setCellValue(chr($column1).'69', $sign->type.':');
            $column1+= $spacing1;
        }

        foreach ($signatory as $sign) {

            $sheet->setCellValue(chr($column2).'70', $sign->name);
            $column2+= $spacing1;
        }

        foreach ($signatory as $sign) {

            $sheet->setCellValue(chr($column2).'71', $sign->position);
            $column2+= $spacing1;
        }



        //


        $col1 = 70;
        $col2 = 64;

        $f = 6;
        $y = 25;

        $col_count = 1;
        $col_name;
        foreach ($items1 as $item) {

            $col = $col_count++;
            $col_name = 'col'.$col;  
             

            $col_val = chr($col1);

            if($col1 > 90){

                $col_val = chr(65);

                $col_val .= chr($col2);

                
            }
            
            $sheet->setCellValue($col_val.'83', $hps->$col_name);

            $sheet->setCellValue($col_val.'82', $item->value_item_abbr);

            $col1++;

            if($col1 > 90){

                $col2++;
            }


        }

        foreach ($items2 as $item) {

            $col = $col_count++;
            $col_name = 'col'.$col;


            $col_val = chr($col1);

            if($col1 > 90){

                $col_val = chr(65);

                $col_val .= chr($col2);

            }

            $sheet->setCellValue($col_val.'83', $hps->$col_name);

            $sheet->setCellValue($col_val.'82', $item->value_item_abbr);

            $col1++;

            if($col1 > 90){

                $col2++;
            }

        }



        $student_colF = 84;
        $student_female = 1;
        foreach ($data as $student) {
            
            if($student->gender == 'FEMALE'){
                $student_colF++;

                $sheet->setCellValue('A'.$student_colF, $student_female++);
                $sheet->setCellValue('B'.$student_colF, $student->lastname);
                $sheet->setCellValue('C'.$student_colF, ",");
                $sheet->setCellValue('D'.$student_colF, $student->firstname);
                $sheet->setCellValue('E'.$student_colF, substr($student->middlename, 0, 1));

                $sheet->setCellValue('F'.$student_colF, $student->col1);
                $sheet->setCellValue('G'.$student_colF, $student->col2);
                $sheet->setCellValue('H'.$student_colF, $student->col3);
                $sheet->setCellValue('I'.$student_colF, $student->col4);
                $sheet->setCellValue('J'.$student_colF, $student->col5);
                $sheet->setCellValue('K'.$student_colF, $student->col6);
                $sheet->setCellValue('L'.$student_colF, $student->col7);
                $sheet->setCellValue('M'.$student_colF, $student->col8);
                $sheet->setCellValue('N'.$student_colF, $student->col9);
                $sheet->setCellValue('O'.$student_colF, $student->col10);
                $sheet->setCellValue('P'.$student_colF, $student->col11);
                $sheet->setCellValue('Q'.$student_colF, $student->col12);
                $sheet->setCellValue('R'.$student_colF, $student->col13);
                $sheet->setCellValue('S'.$student_colF, $student->col14);
                $sheet->setCellValue('T'.$student_colF, $student->col15);
                $sheet->setCellValue('U'.$student_colF, $student->col16);
                $sheet->setCellValue('V'.$student_colF, $student->col17);
                $sheet->setCellValue('W'.$student_colF, $student->col18);
                $sheet->setCellValue('X'.$student_colF, $student->col19);
                $sheet->setCellValue('Y'.$student_colF, $student->col20);
                $sheet->setCellValue('Z'.$student_colF, $student->col21);
                $sheet->setCellValue('AA'.$student_colF, $student->col22);
                $sheet->setCellValue('AB'.$student_colF, $student->col23);
                $sheet->setCellValue('AC'.$student_colF, $student->col24);
                $sheet->setCellValue('AD'.$student_colF, $student->col25);

                $sheet->setCellValue('AG'.$student_colF, $student->final);


            }
            
        }

        $spacing2 = number_format(32/count($signatory));
        $column3 = 65;
        $column4 = 67;

        foreach ($signatory as $sign) {

            $sheet->setCellValue(chr($column3).'108', $sign->type.':');
            $column3+= $spacing2;
        }

        foreach ($signatory as $sign) {

            $sheet->setCellValue(chr($column4).'109', $sign->name);
            $column4+= $spacing2;
        }

        foreach ($signatory as $sign) {

            $sheet->setCellValue(chr($column4).'110', $sign->position);
            $column4+= $spacing2;
        }

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="deportment-'.$schoolyear->sydesc.'-'.$sections->sectionname.'-'.$quarter.'.xlsx"');
        $writer->save("php://output");
        exit();
    }


    function getstudentgrade(){
        
    }
    
}

