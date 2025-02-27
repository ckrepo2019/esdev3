<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Str;

class SPP_Building extends Model
{

    public static function getBuilding(
        $skip = null, 
        $take = null, 
        $searchid = null,
        $search = null
    ){

        $data = array();
        $roomQuery = DB::table('building');

        if($search!=null){

            $roomQuery->where(function($query) use($search){
                $query->where('building.description','like',$search.'%');
            });

        }

        if($searchid!=null){

            $roomQuery->where('id',$searchid);
        }

        $roomQuery->where('building.deleted','0');
      
        $count = $roomQuery->count();

        if($take!=null){

            $roomQuery->take($take);

        }

        if($skip!=null){

            $roomQuery->skip(($skip-1)*$take);
            
        }

        $buildings = $roomQuery->get();

        array_push($data,(object)['data'=>$buildings,'count'=>$count]);

        return $data;    

    }

    public static function addbuilding(
       $data
    ){

        $message = [
            'buildingDesc.required'=>'Building Description is required',
            'buildingDesc.unique'=>'Building Description already exist',
            'buildingCapacity.required'=>'Building Capacity is required',
           
        ];

        $validator = Validator::make(   $data->all(), [
            'buildingDesc' => ['required',Rule::unique('building','description')->where(function($query){
                return $query->where('deleted','0');
            })],
            'buildingCapacity' => 'required|integer',
        ], $message);


        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
            return back()->withErrors($validator)->withInput();

        }
        else{

            DB::table('building')
                ->insert([
                    'description'=>$data->get('buildingDesc'),
                    'capacity'=>$data->get('buildingCapacity'),
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            toast('Successfull','success')->autoClose(2000)->toToast($position = 'top-right');

            return back();
        }

    }

    public static function updatebuilding(
        $data
     ){
 
         $message = [
             'buildingDesc.required'=>'Building Description is required',
             'buildingDesc.unique'=>'Building Description already exist',
             'buildingCapacity.required'=>'Building Capacity is required',
         ];
 
         $validator = Validator::make(   $data->all(), [
             'buildingDesc' => ['required',Rule::unique('building','description')->where(function($query){
                 return $query->where('deleted','0');
             })->ignore($data->get('data-id'),'id')],
             'buildingCapacity' => 'required|integer',
         ], $message);
 
 
         if ($validator->fails()) {
 
             toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
             return back()->withErrors($validator)->withInput();
 
         }
         else{
 
             DB::table('building')
                ->where('id',$data->get('data-id'))
                ->update([
                     'description'=>$data->get('buildingDesc'),
                     'capacity'=>$data->get('buildingCapacity'),
                     'updatedby'=>auth()->user()->id,
                     'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                     ]);
 
             toast('Successfull','success')->autoClose(2000)->toToast($position = 'top-right');

             return redirect('admin/view/building/info/'.$data->get('data-id'));

         }
 
     }
     
    


}
