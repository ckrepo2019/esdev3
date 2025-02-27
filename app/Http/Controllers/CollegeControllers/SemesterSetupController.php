<?php

namespace App\Http\Controllers\CollegeControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Rules\PassingRate;

class SemesterSetupController extends Controller
{
    public function show(){

        $syidActive = DB::table('sy')->where('isactive',1)->first()->id; 
        $semidActive = DB::table('semester')->where('isactive',1)->first()->id;
        $data = DB::table('semester_setup')
            ->join('sy', function ($join) {
                $join->on('sy.id', 'semester_setup.sy');
            })
            ->join('semester', function ($join) {
                $join->on('semester.id', 'semester_setup.semester');
            })
            ->select(
                'semester_setup.*',
                'sy.sydesc',
                'semester.semester'
            )
            ->where('semester_setup.sy', $syidActive)
            ->where('semester_setup.semester', $semidActive)
            ->where('semester_setup.deleted', 0)
            ->orderBy('semester_setup.id', 'desc')
            ->get();

        
        $dataTrans = DB::table('gengradetrans')
            ->select(
                'gengradetrans.id',
                'gengradetrans.trans_desc as text'
            )
            ->where('gengradetrans.deleted', 0)
            ->get();
        $dataSy = DB::table('sy')
            ->select(
                'id',
                'sydesc as text'
            )
            ->get();
        $dataSem = DB::table('semester')
            ->select(
                'id',
                'semester as text'
            )
        ->get();

        // return $data;
        return view('superadmin.pages.college.semester-setup',[
            'data' => $data,
            'transmutation' => $dataTrans,
            'sy'=>$dataSy,
            'semester'=>$dataSem,
        ]);
    }

    public function getsetupdata(Request $request){

        $data = DB::table('semester_setup')
            ->join('sy', function ($join) {
                $join->on('sy.id', 'semester_setup.sy');
            })
            ->join('semester', function ($join) {
                $join->on('semester.id', 'semester_setup.semester');
            })
            ->select(
                'semester_setup.*',
                'sy.sydesc',
                'semester.semester'
            )
            ->where('semester_setup.sy', $request->syid)
            ->where('semester_setup.semester', $request->semid)
            ->where('semester_setup.deleted', 0)
            ->orderBy('semester_setup.id', 'desc')
            ->get();

        return $data;
    } 

    public function search(Request $request){

        if($request->ajax()){
    
            $data=DB::table('semester_setup')
            ->where('id','like','%'.$request->search.'%')
            ->where('deleted','0')
            // ->orwhere('prelimPercentage','like','%'.$request->search.'%')
            // ->orwhere('midtermPercentage','like','%'.$request->search.'%')
            // ->orwhere('prefiPercentage','like','%'.$request->search.'%')
            // ->orwhere('finalPercentage','like','%'.$request->search.'%')
            ->orderBy('id', 'desc')
            ->get();
            
    
            $output='';
            if(count($data)>0){
                $output ='
                <table id="table" class="table subscbr-tbl">
                    <thead  class="thead-dark">

                        <th>#</th>
                        <th>School Year</th>
                        <th>Prelim</th>
                        <th>Midterm</th>
                        <th>Pre-Final</th>
                        <th>Final</th>
                        <th>Transmute</th>
                        <th>Passing Rate</th>
                        <th>Grading Scaling</th>
                        <th>Operations</th>

                    </thead>
                    <tbody>
                    ';
                    foreach($data as $datas){
                        $output .='
                        <tr class="items">

                            <td>'. $datas->id .'</td>
                            <td>2022-2023</td>
                            <td>';
                                if($datas->prelim == 1){
                                    $output .= ''.$datas->prelimPercentage.'%';
                                }else{
                                    $output .='N/A';
                                }
                        $output .='
                            </td>
                            <td>';
                                if($datas->midterm == 1){
                                    $output .=''.$datas->midtermPercentage.'%';
                                }else{
                                    $output .='N/A';
                                }
                        $output .=' 
                            </td>
                            <td>';
                                if($datas->prefi == 1){
                                    $output .=''.$datas->prefiPercentage.'%';
                                }else{
                                    $output .='N/A';
                                }
                        $output .='
                            </td>
                            <td>';
                                if($datas->final == 1){
                                    $output .=''.$datas->finalPercentage.'%';
                                }else{
                                    $output .='N/A';
                                }

                        $output .='
                            </td>';
                            if($datas->transmute_ID != 0){
                                $output .='<td style="background: green; width: 100px;"></td>';
                            }else{
                                $output .='<td style="background: #bb4c4c; width: 100px;"></td>';
                            }

                        $output .='
                            </td>
                            <td>'. $datas->passingRate .'</td>
                            <td>';
                                if($datas->isPointScaled == 1){
                                    $output .='1-5 Scale';
                                }else{
                                    $output .='60-100 Scale';
                                }
                        
                        $output .='
                            <td>
                                <button type="button" value ="'.$datas->id.'" class="btn editBtn btn-primary"><i class="fas fa-edit"></i></button>
                                <button type="button" value ="'.$datas->id.'" class="btn deleteBtn btn-primary"><i class="fas fa-trash-alt"></i></button>
                            </td>

                        </tr>';
                        }
                $output .='</tbody>
                </table>';

            
        
                // $output ='
                // <div class="row row-parent">';
        
                //         foreach($data as $data){
                //             $output .='
                //             <div class="col-sm-6">
                //                 <div class="card">
                //                     <h5 class="card-header card-parent">Semester Setup '.$data->id.'</h5>
                //                     <div class="card-body">
                //                         <div class="row">

                //                         <div class="card-content">
                //                             <div>';
                //                                 if($data->isPointScaled == 1){
                //                                     $output .='<p class="card-text">1-5 Point Scaled Grading System</p>';
                //                                 }
                //                                 else{
                //                                     $output .='<p class="card-text">60-100 Point Scaled Grading System</p>';
                //                                 }
                //                                 if($data->isTransmuted== 1){
                //                                     $output .='<div class="form-check form-switch">
                //                                         <input class="form-check-input" type="checkbox" role="switch" id="checkboxFinal" checked disabled>
                //                                         <label class="form-check-label" for="flexSwitchCheckChecked">Transmutation Setup {{$data->transmute_ID}}</label>
                //                                     </div>';
                //                                 }
                //                                 else{
                //                                     $output .='<div class="form-check form-switch">
                //                                         <input class="form-check-input" type="checkbox" role="switch" id="checkboxFinal" disabled>
                //                                         <label class="form-check-label" for="flexSwitchCheckChecked">Not Transmutated</label>
                //                                     </div>';
                //                                 }

                //                 $output .=' <div  class="card-content">
                //                                 <label>Passing Rate: </label>
                //                                 <p>'.$data->passingRate.'</p>
                //                             </div>
                                            
                //                             </div>
                //                             <div  class="card-content">
                //                                 <label>School Year: </label>
                //                                 <p>2022-2021</p>
                //                             </div>
                //                         </div>
                                            

                //                         <div class="col-sm-6">
                //                             <div class="card">
                //                                 <h5 class="card-header card-child">Prelim</h5>
                //                                 <div class="card-body">';
                //                                     if($data->prelim == 1)
                //                                     {
                //                                         $output .='<h2>'.$data->prelimPercentage.'%</h2>';
                //                                     }
                //                                     else{
                //                                         $output .='<h2>N/A</h2>';
                //                                     }

                                                    
                //                     $output .=' </div>
                //                             </div>
                                            
                //                         </div>

                //                         <div class="col-sm-6">
                //                             <div class="card">
                //                                 <h5 class="card-header card-child">Midterm</h5>
                //                                 <div class="card-body">';
                //                                     if($data->midterm == 1)
                //                                     {
                //                                         $output .='<h2>'.$data->midtermPercentage.'%</h2>';
                //                                     }
                //                                     else{
                //                                         $output .='<h2>N/A</h2>';
                //                                     }
                //                      $output .='</div>
                //                             </div>
                                            
                //                         </div>

                //                         <div class="col-sm-6">
                //                             <div class="card">
                //                                 <h5 class="card-header card-child">Pre-Final</h5>
                //                                 <div class="card-body">';
                //                                     if($data->prefi == 1)
                //                                     {
                //                                         $output .='<h2>'.$data->prefiPercentage.'%</h2>';
                //                                     }
                //                                     else{
                //                                         $output .='<h2>N/A</h2>';
                //                                     }
                                    
                //                     $output .='</div>
                //                             </div>
                                            
                //                         </div>

                //                         <div class="col-sm-6">
                //                             <div class="card">
                //                                 <h5 class="card-header card-child">Final</h5>
                //                                 <div class="card-body">';
                //                                     if($data->final == 1)
                //                                     {
                //                                         $output .='<h2>'.$data->finalPercentage.'%</h2>';
                //                                     }
                //                                     else{
                //                                         $output .='<h2>N/A</h2>';
                //                                     }
                                        
                //                     $output .='</div>
                //                             </div>
                //                         </div>

                //                     </div>
                //                             <div class="setup-button">
                //                                 <button type="button" value ="'.$data->id.'" class="btn editBtn btn-primary"><i class="fa-solid fa-pen-to-square"></i></i></button>
                //                                 <button type="button" value ="'.$data->id.'" class="btn deleteBtn btn-primary"><i class="fa-solid fa-trash"></i></button>
                //                             </div>

                //                     </div>
                //                 </div>
                //             </div>
                //                 ';
                //         }
        
        
        
                // $output .= '
                //     </tbody>
                //     </table>';
        
        
        
            }
            else{
        
                $output .='<p>No matching records found</p>';
        
            }
        
            
        
        }

        return $output;
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'prelim'=>'required',
            'midterm'=>'required',
            'prefi'=>'required',
            'final'=>'required',

            'prelimPercentage'=> 'max:100',
            'midtermPercentage'=>'max:100',
            'prefiPercentage'=>'max:100',
            'finalPercentage'=>'max:100',
            'passingRate'=>'max:100',

            'isPointScaled'=>'required',
            'passingRate'=>'required',
        ]);

        if($validator->fails()){

            return response()-> Json([

                'status'=>400,
                // 'errors'=>$validator->messages(),

            ]);

        }else{

            try {

                $prelim = 90;
                $midterm = 90;
                $prefi = 90;
                $final = 90;

                eval($request->formula.';');
                
    
            } catch (\Throwable $th) {
                
                return response()-> Json([
    
                    'status'=>505,
                    'message'=>"Formula is not valid. Make sure to add ($) before the variable."
    
                ]);
            }

            $formulabackend = $request->formula;
            $formulafrontend = str_replace('$', '', $request->formula);

            $isTransmuted = 0;
            $transmute_ID = 0;

            if($request->isTransmuted > 0){

                $isTransmuted = 1;
                $transmute_ID = $request->isTransmuted ;
                
            }else{
                $isTransmuted = 0;
            }

            if($request->prelim == 0 && $request->midterm == 0 && $request->prefi == 0 && $request->final == 0){

                return response()-> Json([

                    'status'=>404,
                    'message'=>'Failed to Setup. Please Select Term!',
                    'code'=>'error'

                ]);

            }else{

                // $isSpecified = $request->percentageSpecify;

                // if($isSpecified == 1){

                

                // $total = $request->prelimPercentage+$request->midtermPercentage+$request->prefiPercentage+$request->finalPercentage;

                // if($total != 100){

                //     return response()-> Json([

                //         'status'=>404,
                //         'message'=>'Failed to Setup. Specific Perecentage should be total 100%! ',
                //         'code'=>'error'
    
                //     ]);

                // }else{

                    $query = DB::table('semester_setup')
                    ->insert(
                        array(
                            
                            'setup_desc'=> $request->setupDesc,
                            'prelim' => $request->prelim, 
                            'midterm' => $request->midterm, 
                            'prefi' => $request->prefi, 
                            'final' => $request->final, 

                            // 'prelimPercentage' => $request->prelimPercentage, 
                            // 'midtermPercentage' => $request->midtermPercentage, 
                            // 'prefiPercentage' => $request->prefiPercentage,
                            // 'finalPercentage' => $request->finalPercentage,

                            'isPointScaled' => $request->isPointScaled,
                            'passingRate' => $request->passingRate,
                            'decimalPoint' => $request->decimalPoint,
                            'sy' => $request->schoolyear,
                            'semester' => $request->semester,
                            // 'isTransmuted' => $isTransmuted,
                            // 'transmute_ID' => $transmute_ID,

                            'prelimTransmuteID'=>$request->prelimTransmutationID,
                            'midtermTransmuteID'=>$request->midtermTransmutationID,
                            'prefiTransmuteID'=>$request->prefiTransmutationID,
                            'finalTransmuteID'=>$request->finalTransmutationID, 
                            'finalGradeTransmuteID'=>$request->finalGradeTransmutationID, 

                            'f_frontend'=> $formulafrontend,
                            'f_backend'=> $formulabackend,
                            
                            'isPrelimDisplay'=>$request->isPrelimDisplay,
                            'isMidtermDisplay'=>$request->isMidtermDisplay,
                            'isPrefiDisplay'=>$request->isPrefiDisplay,
                            'isFinalDisplay'=>$request->isFinalDisplay,

                            "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                            "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
                            
                        )
                    );

                    if($query){

                        return response()-> Json([
    
                            'status'=>200,
                            'message'=>'Semester Succesfully Setup!',
                            'code'=>'success'
                        ]);
    
                    }else{
    
                        return response()-> Json([
    
                            'status'=>404   ,
                            'message'=>'Failed to Setup!',
                            'code'=>'error'
    
                        ]);
                    }

                // }
                    
                // }else{   

                //     $query = DB::table('semester_setup')
                //     ->insert(
                //         array(

                //             'prelim' => $request->prelim, 
                //             'midterm' => $request->midterm, 
                //             'prefi' => $request->prefi, 
                //             'final' => $request->final, 

                //             'isPointScaled' => $request->isPointScaled,
                //             'sy' => $request->schoolyear,
                //             'passingRate' => $request->passingRate,
                //             'isTransmuted' => $isTransmuted,
                //             'transmute_ID' => $transmute_ID,
                //             'f_frontend'=> $formulafrontend,
                //             'f_backend'=> $formulabackend,

                //             'isPrelimDisplay'=>$request->isPrelimDisplay,
                //             'isMidtermDisplay'=>$request->isMidtermDisplay,
                //             'isPrefiDisplay'=>$request->isPrefiDisplay,
                //             'isFinalDisplay'=>$request->isFinalDisplay,

                //             "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                //             "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
                            
                //         )
                //     );

                //     if($query){

                //         return response()-> Json([
    
                //             'status'=>200,
                //             'message'=>'Semester Succesfully Setup!',
                //             'code'=>'success'
                //         ]);
    
                //     }else{
    
                //         return response()-> Json([
    
                //             'status'=>404   ,
                //             'message'=>'Failed to Setup!',
                //             'code'=>'error'
    
                //         ]);
                //     }
                // }

            }

        }

        
    }

    public function createTransmute(Request $request){

        $transHeaderID = DB::table('gengradetrans')
        ->insertGetId(
            [
                "trans_desc" => $request->transdesc, 
                "semid" => $request->schoolyear, 
                "syid" => $request->semester, 
            ]
        );

        $array = $request->array;

        for ($i=0; $i < count($array); $i+=3) { 
        
            DB::table('college_transmutation')
            ->insert(
                [

                "headerid" => $transHeaderID, 
                "initial1" => $array[$i]['value'],
                "initial2" => $array[$i+1]['value'],
                "final" => $array[$i+2]['value']
                
                ]
            );

        }

        
        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Succesfully Added Values!'
        ]); 
        
    }

    public function getTranmutation(Request $request){

        $transmutation = DB::table('college_transmutation')
            ->where('headerid', $request->id)
            ->where('deleted', 0)
        ->get();

        $transmutdetails =DB::table('gengradetrans')
            ->where('id', $request->id)
            ->select('id','trans_desc')
            ->where('deleted', 0)
            ->get();

        return array (
            (object)[
            'transmutation'=>$transmutation,
            'transmutdetails'=>$transmutdetails,
        ]); 
    }

    public function setactive(Request $request){

        if($request->isSetActiveStat == 1){

            $active = DB::table('semester_setup')
            ->select('id')
            ->where('sy', $request->syid)
            ->where('semester', $request->semid)
            ->where('deleted', 0)
            ->where('activestatus', 1)
            ->count();

            if($active != 0){

                return array (
                    (object)[
                    'status'=>405,
                    'statusCode'=>"error",
                    'message'=>'Active setup already exist!'
                ]); 

            }else{

                DB::table('semester_setup')
                ->where('id', $request->id)
                ->update([
                    'activestatus' => $request->isSetActiveStat,
                ]);

                return array (
                    (object)[
        
                    'status'=>200,
                    'statusCode'=>"success",
                    'message'=>'Successfully Activated!'
                ]); 
            }

            // DB::table('semester_setup')
            // ->where('sy', $request->syid)
            // ->where('semester', $request->semid)
            // ->where('deleted', 0)
            // ->update([
            //     'activestatus' => 0,
            // ]);

            // DB::table('semester_setup')
            // ->where('id', $request->id)
            // ->update([
            //     'activestatus' => $request->isSetActiveStat,
            // ]);


        }else{

            $grades = DB::table('college_studentprospectus')
                ->where('syid', $request->syid)
                ->where('semid', $request->semid)
                ->count();


            if($grades != 0 ){

                return array (
                    (object)[
        
                    'status'=>406,
                    'statusCode'=>"error",
                    'message'=>'Grades found in this setup!'
                ]);

            }else{

                DB::table('semester_setup')
                ->where('id', $request->id)
                ->update([
                    'activestatus' => $request->isSetActiveStat,
                ]); 

                return array (
                    (object)[
        
                    'status'=>200,
                    'statusCode'=>"success",
                    'message'=>'Successfully Deactivated!'
                ]);
            }

        }

    }

    public function semesterSetupGetEdit(Request $request){

        $id = $request->id;
        $data = DB::table('semester_setup')
        ->where('id',$id)
        ->get();

        return array ( 
            (object)[

            'status'=>200,
            'message'=>'Setup Found',
            'semesterSetup'=> $data,
            'code'=>'success'
        ]);
    }

    public function edit(Request $request){

            try {

                $prelim = 90;
                $midterm = 90;
                $prefi = 90;
                $final = 90;

                eval($request->formula.';');
                
    
            } catch (\Throwable $th) {
                
                return response()-> Json([
    
                    'status'=>505,
                    'message'=>"Formula is not valid. Make sure to add ($) before the variable."
    
                ]);
            }

            $formulabackend = $request->formula;
            $formulafrontend = str_replace('$', '', $request->formula);
            
            $isTransmuted = 0;
            $transmute_ID = 0;

            if($request->isTransmuted > 0){

                $isTransmuted = 1;
                $transmute_ID = $request->isTransmuted ;
                
            }else{
                $isTransmuted = 0;
            }

            if($request->prelim == 0 && $request->midterm == 0 && $request->prefi == 0 && $request->final == 0){

                return response()-> Json([

                    'status'=>404,
                    'message'=>'Failed to Edit Setup. Please Select Term!',
                    'code'=>'error'

                ]);

            }else{

                // $isSpecified = $request->percentageSpecify;

                // if($isSpecified == 1){

                //     $total = $request->prelimPercentage+$request->midtermPercentage+$request->prefiPercentage+$request->finalPercentage;
                //     if($total != 100){

                //         return response()-> Json([
                //             'status'=>404,
                //             'message'=>'Failed to Edit Setup. Specific Perecentage should be total 100%! ',
                //             'code'=>'error'
                //         ]);

                //     }else{
                    

                        $query = DB::table('semester_setup')
                        ->where('id', $request->id)
                        ->update(
                            [
                                
                                'setup_desc'=> $request->setupDesc,
                                'prelim' => $request->prelim, 
                                'midterm' => $request->midterm, 
                                'prefi' => $request->prefi, 
                                'final' => $request->final, 

                                // 'prelimPercentage' => $request->prelimPercentage, 
                                // 'midtermPercentage' => $request->midtermPercentage, 
                                // 'prefiPercentage' => $request->prefiPercentage,
                                // 'finalPercentage' => $request->finalPercentage,

                                'isPointScaled' => $request->isPointScaled,
                                'passingRate' => $request->passingRate,
                                'decimalPoint' => $request->decimalPoint,
                                'sy' => $request->schoolyear,
                                'semester' => $request->semester,
                                // 'isTransmuted' => $isTransmuted,
                                // 'transmute_ID' => $transmute_ID,

                                'prelimTransmuteID'=>$request->prelimTransmutationID,
                                'midtermTransmuteID'=>$request->midtermTransmutationID,
                                'prefiTransmuteID'=>$request->prefiTransmutationID,
                                'finalTransmuteID'=>$request->finalTransmutationID, 
                                'finalGradeTransmuteID'=>$request->finalGradeTransmutationID, 

                                'f_frontend'=> $formulafrontend,
                                'f_backend'=> $formulabackend,
                                
                                'isPrelimDisplay'=>$request->isPrelimDisplay,
                                'isMidtermDisplay'=>$request->isMidtermDisplay,
                                'isPrefiDisplay'=>$request->isPrefiDisplay,
                                'isFinalDisplay'=>$request->isFinalDisplay,

                                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
                                
                            ]
                        );

                        if($query){

                            return response()-> Json([

                                'status'=>200,
                                'message'=>"Successfully Edited Setup!",
                                'code'=>'success'
                            ]);

                        }else{

                            return response()-> Json([

                                'status'=>404   ,
                                'message'=>'Failed to Setup!',
                                'code'=>'error'

                            ]);
                        }   

                    }

                // }else{

                //     $query = DB::table('semester_setup')
                //     ->where('id', $request->id)
                //     ->update([

                //         'prelim' => $request->prelim, 
                //         'midterm' => $request->midterm, 
                //         'prefi' => $request->prefi, 
                //         'final' => $request->final, 

                //         'prelimPercentage' => $request->prelimPercentage, 
                //         'midtermPercentage' => $request->midtermPercentage, 
                //         'prefiPercentage' => $request->prefiPercentage,
                //         'finalPercentage' => $request->finalPercentage,

                //         'isPointScaled' => $request->isPointScaled,
                //         'sy'=> $request->schoolyear,
                //         'passingRate' => $request->passingRate,
                //         'isTransmuted' => $isTransmuted,
                //         'transmute_ID' => $transmute_ID,

                //         "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()

                //     ]);

                //     if($query){

                //         return response()-> Json([

                //             'status'=>200,
                //             'message'=>"Successfully Edited Setup!",
                //             'code'=>'success'
                //         ]);

                //     }else{

                //         return response()-> Json([

                //             'status'=>404   ,
                //             'message'=>'Failed to Setup!',
                //             'code'=>'error'

                //         ]);
                //     }
                // }

                
            // }

        
    }

    public function destroy(Request $request){

        $id = $request->id;

        $query = DB::table('semester_setup')
              ->where('id', $id)
              ->update([
                'deleted' => 1,
                "deleted_at" => \Carbon\Carbon::now(),
            ]);

        if($query){
            return array (
                (object)[

                'status'=>200,
                'message'=>'Succesfully Deleted Setup!',
                'code'=>'success'
            ]);
    
        }
        else{
            return array (
                (object)[

                'status'=>404,
                'message'=>'Failed Delete Setup!',
                'code'=>'error'
            ]);
        }
      
    }

     public function getActiveSetup(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');

        $setup = DB::table('semester_setup')
            ->where('activestatus', 1)
            ->where('sy', $syid)
            ->where('semester', $semid)
            ->where('semester_setup.deleted', 0)
            ->get();

        return $setup;
    }
    
}
