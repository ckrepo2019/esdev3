<?php

namespace App\Http\Controllers\QueuingController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueuingInterfaceController extends Controller
{
    public function show(){

        $student = DB::table('studinfo')
            ->where('deleted', 0)
            ->select(
                'id',
                'sid',
                'firstname as text',
                'middlename',
                'lastname',
            )
            ->get();


        foreach ($student as $studentdata) {
            
            $studentdata->text = $studentdata->lastname.', '.$studentdata->text." ".substr($studentdata->middlename, 0);

        }

        $departments = DB::table('queuing_window')
            ->join('usertype', 'usertype.id', 'queuing_window.departmentid')
            ->select(
                'queuing_window.*',
                'usertype.id',
                'usertype.utype'
            )
            ->where('queuing_window.deleted', 0)
            ->where('queuing_window.que_setupid', 1)
            ->get();
        
        $data = collect($departments);
        
        for ($i=0; $i < count($data); $i++) { 
            
            if($data[$i]->departmentid == $data[$i+1]->departmentid){

                unset($data[$i]);
            }
            
        }


        return view('superadmin.pages.queuing.queuing-ui', [

            'departments' => $data,
            'student' => $student,
        ]);
        
    }

    public function queuing_transaction(Request $request){

        if($request->department == null){

            return array (
                (object)[

                'status'=>401,
                'statusCode'=>"error",
                'message'=>'Please select Department!'
            ]); 

        }else if($request->studname == null || $request->studname == ""){

            return array (
                (object)[

                'status'=>402,
                'statusCode'=>"error",
                'message'=>'Please select Student Name!'
            ]); 


        }else if($request->idnumber == null || $request->idnumber == ""){

            return array (
                (object)[

                'status'=>403,
                'statusCode'=>"error",
                'message'=>'Please select ID Number!'
            ]); 

        }else{
            
            $checker = DB::table('studinfo')
                ->where('sid', $request->idnumber)
                ->where('deleted', 0)
                ->first();
            
            if($checker == null){

                return array (
                    (object)[
        
                    'status'=>404,
                    'statusCode'=>"error",
                    'message'=>'Unknow ID Number!'
                ]); 

            }else{

                $dapartment = strtolower($request->department);
                

                $data = DB::table('studinfo')
                    ->where('deleted', 0)
                    ->where('id', $request->studname)
                    ->get([
                        'firstname',
                        'middlename',
                        'lastname',
                    ])
                    ->first();



                $studname = strtolower($data->lastname.', '.$data->firstname.' '.substr($data->middlename, 0));

                $number = DB::table('queuing_transaction')
                    ->insertGetId([
                        'sid' => $request->idnumber,
                        'studname' => ucwords($studname),
                        'department' => ucwords($dapartment),
                        'departmentid' => $request->departmentid,
                    ]);

                


                return array (
                    (object)[

                    'status'=>200,
                    'statusCode'=>"success",
                    'message'=>'Succesfully Submitted Que!',
                    'studname'=>ucwords($studname),
                    'que_number'=>$number,
                    'department'=>ucwords($dapartment)
                ]); 
            }

        }

    }

    public function live_getstudent(Request $request){

        if($request->ajax()){
            $output = '';
            $total = 0;
            $studname = $request->studname;


            if($studname != null){

                $data =  DB::table('studinfo')
                ->havingRaw("CONCAT(lastname,' ',firstname,' ',middlename) like ?", ['%'.$studname.'%'])
                ->get();

                $total = $data->count();

                if($total > 0){

                    foreach ($data as $element) {
                        
                        $output .= '
        
                            <li><div class="d-flex justify-content-between"><a class="student_item" data-id="'.$element->id.'">'.$element->lastname.', '.$element->firstname.' '.$element->middlename.'</a><a>'.$element->sid.'</a></div></li>
                        ';
                    }
                }

            }else{

                $output = '';
            }


            return array (
                (object)[
    
                'output'=>$output,
                'total'=>$total
            ]); 
            ;

        }
        
    }
}
