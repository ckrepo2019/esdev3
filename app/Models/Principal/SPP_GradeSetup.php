<?php

namespace App\Models\Principal;
use DB;
use Session;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Database\Eloquent\Model;

class SPP_GradeSetup extends Model

{
    public static function getAllGradeSetup(
        $skip = null,
        $take = null,
        $gradesetupid = null,
        $gradeinfo = null,
        $acadid = null,
        $subjid = null
    ){


        $query = DB::table('gradessetup');

        if( $subjid != null){

            $query->where('subjid',$subjid);
            
        }
                   
        if($acadid==5){

            $query->join('sh_subjects',function($query){
                        $query->on('gradessetup.subjid','=','sh_subjects.id');
                    });

        }
        else{

            $query->join('subjects',function($query) {
                $query->on('gradessetup.subjid','=','subjects.id');
                
            });

        }

        if(Session::has('schoolYear')){

            $query->where('gradessetup.syid',Session::get('schoolYear')->id);
            
        }
        else{

            $query->where('gradessetup.syid',function($query){
                $query->select('id')->from('sy')->where('sy.isactive','1');
            });

        }   
        

        $query->join('gradelevel',function($join) use ($acadid){
            $join->on('gradessetup.levelid','=','gradelevel.id');
            $join->where('gradelevel.acadprogid',$acadid);
          
        });


        if($gradeinfo != null){


            $query->where(function($query) use($gradeinfo){
                $query->where('gradelevel.levelname','like',$gradeinfo.'%');
            });

            if($acadid==5){

                $query->orWhere(function($query) use($gradeinfo){
                    $query->where('sh_subjects.subjtitle','like',$gradeinfo.'%');
                });
            }
            else{

                $query->orWhere(function($query) use($gradeinfo){
                    $query->where('subjects.subjdesc','like',$gradeinfo.'%');
                });
            }

        }

    

        if($gradesetupid != null){

            $query->where('id',$gradesetupid);
        }

        $query->orderBy('gradelevel.sortid');

        $query->select(
            'gradessetup.*',
            'gradelevel.levelname',
            'gradelevel.sortid',
            'gradelevel.acadprogid'
        );

        if($acadid==5){

            $query->addSelect(
                'sh_subjects.subjtitle',
                'sh_subjects.subjcode'
            );
        }
        else{

            $query->addSelect(
                'subjects.subjdesc',
                'subjects.subjcode'
            );
        }

        $query->where('gradessetup.deleted','0');

        $count = $query->count();

        if($take!=null){

            $query->take($take);

        }

        
      
        if($skip!=null){

            $query->skip(($skip-1)*$take);

        }

        $data = array();
    
        array_push($data,(object)['data'=>$query->get(),'count'=>$count]);

        return $data;  


    }

    public static function insertGradeSetup(
        $gradelevel = null,
        $subject = null,
        $ww = null,
        $pt = null,
        $qa = null,
        $q = null
    ){

        $data = [
            'gradelevel' => $gradelevel,
            'subject' => $subject,
            'ww' => $ww,
            'pt' => $pt,
            'qa' => $qa,
            'q' => $q
        ];

        $message = [
            'gradelevel.required'=>'Subject name is required.',
            'subject.required'=>'Subject code is required.',
            'q.required' => 'Quarter is required'
        ];

        $validator = Validator::make($data, [
            'gradelevel' => 'required',
            'subject' => 'required',
            'q' => 'required',
            
        ], $message);

        $validator->after(function ($validator) use($ww, $pt, $qa){
            if (($ww + $pt + $qa) != 100) {
                $validator->errors()->add('total', 'Written works, Performance Task and Quarter Assesment should equal to 100');
            }
        });

        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
            return back()->withErrors($validator)->withInput();

        }
        else{

            
            date_default_timezone_set('Asia/Manila');
            $date = date('Y-m-d H:i:s');

            $first = 0;
            $second = 0;
            $third = 0;
            $fourth = 0;
            $dataString = '';

            if(in_array('1',$q)){
                $first = 1;
            }
            
            if(in_array('2',$q)){
                $second = 1;
            }

            if(in_array('3',$q)){
                $third = 1;
            }

            if(in_array('4',$q)){
                $fourth = 1;
            }

            foreach($gradelevel as $glitem){

                foreach($subject as $subitem){

                   

                    $gradesetup = DB::table('gradessetup')
                                    ->where('levelid',$glitem)
                                    ->where('subjid',$subitem)
                                    ->get();

                    if(count($gradesetup)==0){

                        DB::table('gradessetup')
                            ->insert([
                                'levelid'=>$glitem,
                                'subjid'=>$subitem,
                                'writtenworks'=>$ww,
                                'performancetask'=>$pt,
                                'qassesment'=>$qa,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>$date,
                                'first'=>$first,
                                'second'=>$second,
                                'third'=> $third,
                                'fourth'=> $fourth,
                                'deleted'=>0,
                                'syid'=>DB::table('sy')->where('isactive','1')->first()->id
                            ]);

                    }
                    else{

                        DB::table('gradessetup')
                            ->where('id',$gradesetup[0]->id)
                            ->update([
                                'levelid'=>$glitem,
                                'subjid'=>$subitem,
                                'writtenworks'=>$ww,
                                'performancetask'=>$pt,
                                'qassesment'=>$qa,
                                'updateddatetime'=>$date,
                                'first'=>$first,
                                'second'=>$second,
                                'third'=> $third,
                                'fourth'=> $fourth,
                                'deleted'=>0,
                                'syid'=>DB::table('sy')->where('isactive','1')->first()->id
                            ]);
                    }


                }

            }

            toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
                
            return back();


        }



    }




    public static function getGradeSetupSHSQuery(){

        return  DB::table('academicprogram')
                    ->leftJoin('gradelevel',function($join){
                        $join->on('academicprogram.id','=','gradelevel.acadprogid');
                        $join->where('gradelevel.deleted','0');
                    })
                    ->Join('gradessetup',function($join){
                        $join->on('gradelevel.id','=','gradessetup.levelid');
                        $join->where('gradessetup.deleted','0');
                    })
                    ->join('sh_subjects',function($join){
                        $join->on('gradessetup.subjid','=','sh_subjects.id');
                        $join->where('sh_subjects.deleted','0');
                    });

    }

    public static function getGradeSetupJHSQuery(){

        return  DB::table('academicprogram')
                    ->leftJoin('gradelevel',function($join){
                        $join->on('academicprogram.id','=','gradelevel.acadprogid');
                        $join->where('gradelevel.deleted','0');
                    })
                    ->Join('gradessetup',function($join){
                        $join->on('gradelevel.id','=','gradessetup.levelid');
                        $join->where('gradessetup.deleted','0');
                    })
                    ->join('subjects',function($join){
                        $join->on('gradessetup.subjid','=','subjects.id');
                        $join->where('subjects.deleted','0');
                    });
    }

    public static function getAllGradeStupByPrincipal($principalId){

        $gradeSetupSHS = self::getGradeSetupSHSQuery()
                            ->where('academicprogram.principalid',$principalId)
                            ->where('gradelevel.acadprogid','5')
                            ->select(
                                'gradessetup.*',
                                'sh_subjects.subjtitle as subjdesc',
                                'sh_subjects.id',
                                'gradelevel.levelname')
                            ->get();

        $gradeSetupGS = self::getGradeSetupJHSQuery()
            ->where('academicprogram.principalid',$principalId)
            ->where('academicprogram.id','3')
            ->get();
    

        $gradeSetupJHS = self::getGradeSetupJHSQuery()
                            ->where('academicprogram.principalid',$principalId)
                            ->where('academicprogram.id','4')
                            ->get();

        $gradeSetupPS = self::getGradeSetupJHSQuery()
                            ->where('academicprogram.principalid',$principalId)
                            ->where('academicprogram.id','2')
                            ->get();

        foreach($gradeSetupSHS as $item){

            $gradeSetupJHS->push($item);

        }

        foreach($gradeSetupGS as $item){

            $gradeSetupJHS->push($item);

        }

        foreach($gradeSetupPS as $item){

            $gradeSetupJHS->push($item);

        }


        $gradeSetupJHS = $gradeSetupJHS->sortBy('sortid');

        $data = array();

        $gradeSetupCount = count($gradeSetupJHS)/10;

        if(round($gradeSetupCount) < $gradeSetupCount){
            $gradeSetupCount = round($gradeSetupCount)+1;
        }
        else{
            $gradeSetupCount = round($gradeSetupCount);
        }
        
        array_push($data, (object) array(
            'gradeSetup'=>$gradeSetupJHS->take(10),
            'gradeSetupCount'=> $gradeSetupCount
            ));

        return $data;
    
    }

    public static function gradestupvalidation($request){

        $inputsareValid = true;
        $errorMessage = back();

        $totalsetup = $request->get('ww')+$request->get('pt')+$request->get('qa');

        if($request->get('sc')==null){
            $inputsareValid = false;
            $errorMessage->with('sc', (object) ['message'=>'Grade Level is required']);
        }
        else{
            $errorMessage->with('scs', (object) ['message'=>$request->get('sc')]);
        }

        if($request->get('su')==null){
            $inputsareValid = false;
            $errorMessage->with('su', (object) ['message'=>'Subject is required']);
        }
        else{
            $errorMessage->with('sus', (object) ['message'=>$request->get('su')]);
        }

       

        if($request->has('q')==false){

            $inputsareValid = false;

            $errorMessage->with('q', (object) ['message'=>'Atleast one quarter is required']);

            if($request->has('q')){

                if(in_array('1',$request->get('q'))){
                    $errorMessage->with('q1','q1');
                }
                
                if(in_array('2',$request->get('q'))){
                    $errorMessage->with('q2','q2');
                }

                if(in_array('3',$request->get('q'))){
                    $errorMessage->with('q3','q3');
                }

                if(in_array('4',$request->get('q'))){
                    $errorMessage->with('q4','q4');
                }
            }
        }
        else{

           if($request->has('q')){

                if(in_array('1',$request->get('q'))){
                    $errorMessage->with('q1','q1');
                }
                
                if(in_array('2',$request->get('q'))){
                    $errorMessage->with('q2','q2');
                }

                if(in_array('3',$request->get('q'))){
                    $errorMessage->with('q3','q3');
                }

                if(in_array('4',$request->get('q'))){
                    $errorMessage->with('q4','q4');
                }
            }
        }
        
        if($request->get('ww')==null || $request->get('pt')==null || $request->get('qa')==null){
            $inputsareValid = false;
            $errorMessage->with('setuptotal', (object) ['message'=>'WW, PT and QA are required']);
            $errorMessage->with('wws', (object) ['message'=>$request->get('ww')]);
            $errorMessage->with('pts', (object) ['message'=>$request->get('pt')]);
            $errorMessage->with('qas', (object) ['message'=>$request->get('qa')]);
        }
           
        else if( $totalsetup<100 || $totalsetup>100){
            $inputsareValid = false;
            $errorMessage->with('setuptotal', (object) ['message'=>'WW + PT + QA should equal to 100']);
            $errorMessage->with('wws', (object) ['message'=>$request->get('ww')]);
            $errorMessage->with('pts', (object) ['message'=>$request->get('pt')]);
            $errorMessage->with('qas', (object) ['message'=>$request->get('qa')]);
        }
        else{
            $errorMessage->with('wws', (object) ['message'=>$request->get('ww')]);
            $errorMessage->with('pts', (object) ['message'=>$request->get('pt')]);
            $errorMessage->with('qas', (object) ['message'=>$request->get('qa')]);
        }
        
        $errorMessage->with('Inputstatus',$inputsareValid);

        return [$errorMessage,$inputsareValid];

    }

    public static function storegradesetup($request){
      
        $validategradesetup = self::gradestupvalidation($request);

        if($validategradesetup[1]){
    
            $first = 0;
            $second = 0;
            $third = 0;
            $fourth = 0;
            $dataString = '';

            if(in_array('1',$request->get('q'))){
                $first = 1;
            }
            
            if(in_array('2',$request->get('q'))){
                $second = 1;
            }

            if(in_array('3',$request->get('q'))){
                $third = 1;
            }

            if(in_array('4',$request->get('q'))){
                $fourth = 1;
            }

            DB::table('gradessetup')->insert([
                'levelid'=>$request->get('sc'),
                'subjid'=>$request->get('su'),
                'writtenworks'=>$request->get('ww'),
                'performancetask'=>$request->get('pt'),
                'qassesment'=>$request->get('qa'),
                'first'=>$first,
                'second'=>$second,
                'third'=> $third,
                'fourth'=> $fourth,
                'deleted'=>'0',
                'deleted'=>'0',
                'createdby'=>auth()->user()->id,
                'syid'=>DB::table('sy')->where('isactive','1')->first()->id
            ]);

            toast('Grade Setup Successfully created','success')->autoClose(2000)->toToast($position = 'top-right')->hideCloseButton();

            return back();

        }

        else{

            toast('Invalid Inputs','error')->autoClose(2000)->toToast($position = 'top-right')->hideCloseButton();

            return $validategradesetup[0];  
        }
        
    }

 

    public static function updategradesetup($request){
       
        $validategradesetup = self::gradestupvalidation($request);

        if($validategradesetup[1]){

            $first = 0;
            $second = 0;
            $third = 0;
            $fourth = 0;
    
            if(in_array('1',$request->get('q'))){
                $first = 1;
            }
            
            if(in_array('2',$request->get('q'))){
                $second = 1;
            }
    
            if(in_array('3',$request->get('q'))){
                $third = 1;
            }
    
            if(in_array('4',$request->get('q'))){
                $fourth = 1;
            }

            DB::table('gradessetup')
                ->where('id',$request->get('si'))
                ->update([
                    'levelid'=>$request->get('sc'),
                    'subjid'=>$request->get('su'),
                    'writtenworks'=>$request->get('ww'),
                    'performancetask'=>$request->get('pt'),
                    'qassesment'=>$request->get('qa'),
                    'first'=>$first,
                    'second'=>$second,
                    'third'=> $third,
                    'fourth'=> $fourth,
                    'deleted'=>'0',
                    'updatedbu'=>auth()->user()->id
                ]);

            toast('Grade setup successfully updated','info')->autoClose(2000)->toToast($position = 'top-right')->hideCloseButton();

            return back();
        }
        else{
            toast('Invalid Inputs','error')->autoClose(2000)->toToast($position = 'top-right');
           
            return $validategradesetup[0]
                    ->with('invalidinputs',['sadfd'])
                    ->with('edit', (object) [''])
                    ->with('si', (object) ['message'=>$request->get('si')]);
                                
        }

    }

    public static function searchgradesetup($request){

        $dataString = '';
        $pageString = '';
        $data = '';


        $gradeSetupSHS = self::getGradeSetupSHSQuery()
                                ->where('academicprogram.principalid',Session::get('prinInfo')->id)
                                ->where('gradelevel.acadprogid','5')
                                ->where(function($query) use($request){
                                    $query->where('sh_subjects.subjtitle','like',$request->get('data').'%');
                                    $query->orWhere('gradelevel.levelname','like',$request->get('data').'%');
                                })
                                ->select(
                                    'gradessetup.*',
                                    'sh_subjects.subjtitle as subjdesc',
                                    'sh_subjects.id',
                                    'gradelevel.levelname')
                                ->get();

        $gradeSetupGS = self::getGradeSetupJHSQuery()
                    ->where('academicprogram.principalid',Session::get('prinInfo')->id)
                    ->where('gradelevel.acadprogid','3')
                    ->get();


        $gradeSetupJHS = self::getGradeSetupJHSQuery()
                ->where('academicprogram.principalid',Session::get('prinInfo')->id)
                ->where('gradelevel.acadprogid','4')
                ->where(function($query) use($request){
                    $query->where('subjects.subjdesc','like',$request->get('data').'%');
                    $query ->orWhere('gradelevel.levelname','like',$request->get('data').'%');
                })
                ->get();

        $gradeSetupPS = self::getGradeSetupJHSQuery()
                ->where('academicprogram.principalid',Session::get('prinInfo')->id)
                ->where('gradelevel.acadprogid','2')
                ->where(function($query) use($request){
                    $query->where('subjects.subjdesc','like',$request->get('data').'%');
                    $query ->orWhere('gradelevel.levelname','like',$request->get('data').'%');
                })
                ->get();


        foreach($gradeSetupSHS as $item){

            $gradeSetupJHS->push($item);

        }

        foreach($gradeSetupGS as $item){

            $gradeSetupJHS->push($item);

        }

        foreach($gradeSetupPS as $item){

            $gradeSetupJHS->push($item);

        }

        $gradeSetupJHS = $gradeSetupJHS->sortBy('sortid');

        $gradeSetupCount = count($gradeSetupJHS)/10;

        if(round($gradeSetupCount) < $gradeSetupCount){
            $gradeSetupCount = round($gradeSetupCount)+1;

        }
        else{

            $gradeSetupCount = round($gradeSetupCount);

        }


        $gradeSetupJHS = $gradeSetupJHS->slice(($request->get('pagenum')-1)*10)->take(10);

        foreach($gradeSetupJHS as $key=>$item){
            $dataString.='
                <tr id="'. $key.'">
                    <td>
                        <button class="text-primary btn p-0 ee mr-2" id="'.$item->id.'}"><i class="far fa-edit"></i></button>
                    </td>
                    <td>'.$item->levelname.'</td>
                    <td>'.$item->subjdesc.'</td>
                    <td>'.$item->writtenworks.'</td>
                    <td>'.$item->performancetask.'</td>
                    <td>'.$item->qassesment.'</td>';

                    if($item->first==1){
                         $dataString.='<td><i class="fas fa-check-square text-success"></i></td>';
                    }
                    else{
                        $dataString.='<td><i class="fas fa-times-circle text-danger"></i></td>';
                    }
                    

                    if($item->second==1){
                        $dataString.='<td><i class="fas fa-check-square text-success"></i></td>';
                    }
                    else{
                        $dataString.='<td><i class="fas fa-times-circle text-danger"></i></td>';
                    }
                    

                    if($item->third==1){
                        $dataString.='<td><i class="fas fa-check-square text-success"></i></td>';
                    }
                    else{
                        $dataString.='<td><i class="fas fa-times-circle text-danger"></i></td>';
                    }
                

                    if($item->fourth==1){
                        $dataString.='<td><i class="fas fa-check-square text-success"></i></td>';
                    }
                    else{
                        $dataString.='<td><i class="fas fa-times-circle text-danger"></i></td>';
                    }
                   
                $dataString.='</tr>';
        }

        $pageString.='<ul class="pagination pagination-sm m-0 pt-3">
                    <li class="page-item"><a class="page-link" href="#">«</a></li>';
        
                    for ($x = 1; $x<=$gradeSetupCount;$x++){
                        if($x==1){
                            $pageString.='<li class="page-item"><a class="page-link-active" id="'.$x.'" href="#">'.$x.'</a></li>';
                        }
                        else{
                            $pageString.='<li class="page-item"><a class="page-link" id="'.$x.'" href="#">'.$x.'</a></li>';
                        }
                    }

        $pageString.='<li class="page-item"><a class="page-link" href="#">»</a></li>
        </ul>';

        return array((object)[
            'dataString'=>$dataString,
            'pageString'=> $pageString
        ]);

    }

    public static function getGradeSetup(
        $skip = null, 
        $take = null, 
        $gradesetupid = null, 
        $gradesetupinfo = null,
        $acadprogid = null
    ){
        
        $query = DB::table('gradesetup');



    }   


}
