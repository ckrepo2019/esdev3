<?php

namespace App\Http\Controllers\SuperAdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;

class DeportmentSetupController extends \App\Http\Controllers\Controller
{
 public function show(){

        $deportment = DB::table('deportment_setup')
            ->where('deleted', 0)
            ->get();

        $values = DB::table('deportment_values')
            ->where('deleted','0')
            ->get();

        $items = DB::table('values_items')
            ->where('deleted', 0)
            ->orderBy('sort', 'asc')
            ->get();


        return view('principalsportal.pages.management.deportment-setup',[

            'deportments' => $deportment,
            'values' => $values,
            'items' => $items

        ]);
        
    }
    
    public function create_deportment(Request $request){

 
        $deportment_setup_id = DB::table('deportment_setup')
            ->insertGetId(
               [
                "deportment_desc" => $request->formData[0]['value']
               ]
            );

        for ($i=1; $i < count($request->formData); $i++) { 
            
            DB::table('deportment_values')
                ->insert(
                    [

                    "deportment_setupID" => $deportment_setup_id, 
                    "value_desc" => $request->formData[$i]['value'],
                    
                    ]
            );

        }

        
        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Succesfully Created Deportment!'
        ]); 
        
    }

    public function get_deportment_values(Request $request){

 
        $data = DB::table('deportment_values')
            ->where('id', $request->id)
            ->get();
        
        return array (
            (object)[

            'data'=> $data,
            'id'=> $request->id,

        ]); 
        
    }

    public function get_all_deportment(){

        $deportment = DB::table('deportment_setup')
            ->where('deleted', 0)
            ->get();

        return $deportment;
        
    }

    public function get_specific_values(Request $request){

        $values = DB::table('deportment_values')
            ->where('deportment_setupID', $request->id)
            ->where('deleted', 0)
            ->get();

        $items = DB::table('values_items')
            ->where('deleted', 0)
            ->orderBy('sort', 'asc')
            ->get();

            $values_item = '
                        <div class="row">';
                            
                            foreach($values as $value){
                                $values_item .= '<div class="col-lg-6">
                                    <div class="base_rating">
                                        <div hidden id="hidden_div'.$value->id.'">

                                            <button class="delete_deportment_values" value="'.$value->id.'">
                                                    <i class="far fa-trash-alt text-danger"></i>
                                                </button>

                                                <button class="edit_deportment_values" value="'.$value->id.'">
                                                    <i class="far fa-edit text-primary"></i>
                                            </button>

                                            
                                        </div>

                                        <input checked style="display: none" value="'.$value->id.'" class="form-check-input hide_show" type="checkbox" id="flexCheckDefault'.$value->id.'">
                                        <label class="form-check-label" for="flexCheckDefault'.$value->id.'">
                                            <h5 class="values_title">   
                                            '. $value->value_desc .'
                                            </h5>
                                        </label>
                          
                                    </div>
                                        
                                        <ul class="rating_ul">';

                                            foreach($items as $item){
                                                
 
                                                if($value->id == $item->values_setupID){
                                                    if(  $item->value_item_desc != null){
                                                        
                                                        $values_item .= '<li class="base_rating">

                                                        <div style="display: flex;" hidden id="hidden_div_item'.$item->id.'">
                                                        
                                                            <button class="delete_values_item" value="'.$item->id.'">
                                                                <i class="far fa-trash-alt text-danger"></i>
                                                            </button>

                                                            <button class="edit" value="'.$item->id.'">
                                                                <i class="far fa-edit text-primary"></i>
                                                            </button>
                                                        </div>

                                                        <input checked style="display: none" value="'.$item->id.'" class="form-check-input item_hide_show" type="checkbox" id="itemCheckDefault'.$item->id.'">
                                                        <label class="form-check-label" for="itemCheckDefault'.$item->id.'">
                                                            <p class="li_p">('.$item->sort.')  '. $item->value_item_abbr .' - '. $item->value_item_desc  .'</p>
                                                        </label>
                
                                                        </li>';
                                                    }
                                                }
                                
                                            }


                                            $values_item .= '</ul>
                                        <button type="button" value="'.$value->id.'" class="btn dark-light btn_values_item text-primary">
                                            <i class="fas fa-plus"></i>
                                            Add Item
                                        </button>
                                        <br>
                                        <br>
                                        <br>
                                    
                                </div>';
                            }
                            $values_item .= '</div>';

        return array (
            (object)[

            'status'=>200,
            'values' => $values_item,
        ]);
        
    }

    public function edit_deportment_values(Request $request){

 
        DB::table('deportment_values')
            ->where('id', $request->id)
            ->update(
                [

                'value_desc' => $request->value_desc

                ]
            );

        
        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Succesfully Edited Deportment Values!',
        ]); 
        
    }

    public function delete_deportment_values(Request $request){
        
        $students = DB::table('student_deportment')->get();

        $items = DB::table('values_items')
                ->where('deleted', 0)
                ->orderBy('sort', 'asc')
                ->get();

        
        foreach ($students as $student) {

            for ($i=1; $i < count($items)+1; $i++) { 
                                    
                $col_var = 'col'.$i;



                if($student->$col_var != 0){
                    
                    return array (
                        (object)[
            
                        'status'=>404,
                        'statusCode'=>"warning",
                        'message'=>'Cannot delete values. Grades are present in columns!'
                    ]);
                    

                }
                

                    DB::table('deportment_values')
                    ->where('id', $request->id)
                    ->update(
                        [

                        'deleted' => 1,
                        "deleted_at" => \Carbon\Carbon::now()
                        
                        ]
                    );

                    DB::table('values_items')
                    ->where('values_setupID', $request->id)
                    ->update(
                        [

                        'deleted' => 1,
                        "deleted_at" => \Carbon\Carbon::now()
                        
                        ]
                    );


                    return array (
                        (object)[
            
                        'status'=>200,
                        'statusCode'=>"success",
                        'message'=>'Succesfully Deleted Values!'
                    ]);
                
            }
        }
        

    }

    public function create_more_values(Request $request){

 
        for ($i=0; $i < count($request->formData); $i++) { 
            
            DB::table('deportment_values')
                ->insert(
                    [

                    "deportment_setupID" => $request->id, 
                    "value_desc" => $request->formData[$i]['value']
                    
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

    public function get_values_item(Request $request){

        $data = DB::table('values_items')
                ->where('id', $request->id)
                ->select(
                    'id',
                    'values_setupID',
                    'value_item_desc',
                    // 'value_highest_score',
                    'value_item_abbr',
                    'sort'
                )
                ->get();

        return $data;
        
    }

    public function create_values_item(Request $request){   

        $validator = Validator::make($request->all(), [

            // 'value_highest_score'=>['numeric', 'required'],
            'values_desc'=>['required'],
            'values_abbreviation'=>['required']

        ]);

        if($validator->fails()){

            return array (
                (object)[

                'status'=>404,
                'message'=>$validator->messages(),
                'code'=>'error'

            ]);
            
        }else{

            $check = DB::table('values_items')
                ->where('deportment_setupID', $request->deportment_setupID)
                ->where('deleted', 0)
                ->count();

            if ($check < 25) {
                    
                DB::table('values_items')
                        ->insert(
                            [
                            "deportment_setupID" => $request->deportment_setupID,
                            "values_setupID" => $request->values_setupID, 
                            "value_item_desc" => $request->values_desc,
                            "value_item_abbr" => $request->values_abbreviation,
                            // "value_highest_score" => $request->value_highest_score,
                            "sort" => $request->values_sort
                            
                            ]
                        );

                return array (
                    (object)[

                    'status'=>200,
                    'statusCode'=>"success",
                    'message'=>'Succesfully Created Deportment!'
                ]); 

            }else{

                return array (
                    (object)[

                    'status'=>500,
                    'statusCode'=>"error",
                    'message'=>'Failed to Add! Already has maximum 25 columns.'
                ]); 

            }
            
        }
        
    }

    public function get_deportment(Request $request){   


        $deportment = DB::table('deportment_setup')
            ->where('id', $request->id)
            ->get();

        return $deportment;
        
    }

    public function edit_deportment(Request $request){   


        DB::table('deportment_setup')
            ->where('id', $request->id)
            ->update(
                [

                'deportment_desc' => $request->deportment_desc,

                ]
            );

        $deportment = DB::table('deportment_setup')
            ->where('id', $request->id)
            ->get();

        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Succesfully Edited Deportment!',
            'deportment'=>$deportment
        ]); 
            
    }

    public function delete_deportment(Request $request){   


        DB::table('deportment_setup')
            ->where('id', $request->id)
            ->update(
                [

                'deleted' => 1

                ]
            );
			
		$valuessetupid = DB::table('deportment_values')
							->where('deportment_setupID', $request->id)
							->first();
        
        DB::table('deportment_values')
            ->where('deportment_setupID', $request->id)
            ->update(
                [
                'deleted' => 1
                ]
            );
			
		DB::table('values_items')
            ->where('deportment_setupID', $request->id)
			->where('values_setupID', $valuessetupid->id)
            ->update(
                [

                'deleted' => 1

                ]
            );

        return array (
            (object)[

				'status'=>200,
				'statusCode'=>"success",
				'message'=>'Succesfully Deleted Deportment!'
            
        ]); 
            
    }
    
    public function edit_values_item(Request $request){

 
        DB::table('values_items')
            ->where('id', $request->id)
            ->update(
                [

                'value_item_desc' => $request->values_desc,
                'value_item_abbr' => $request->values_abbreviation,
                // 'value_highest_score' => $request->values_highest_score,
                'sort' => $request->values_sort,
                
                ]
            );

        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Succesfully Edited Values!'
        ]); 
        
    }

    public function delete_values_item(Request $request){

        DB::table('values_items')
                ->where('id', $request->id)
                ->update(
                    [

                    'deleted' => 1,
                    "deleted_at" => \Carbon\Carbon::now()
                    
                    ]
                );

        return array (
            (object)[

            'status'=>200,
            'statusCode'=>"success",
            'message'=>'Succesfully Deleted Values Base Rating!'
        ]); 
        
    }
}
