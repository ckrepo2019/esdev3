<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;
use \Carbon\Carbon;

class SPP_Rooms extends Model
{

    protected $table = 'rooms';

   

    public static function roomQuery(){

        return DB::table('rooms')
                ->where('deleted','0');

    }

    public static function managerooms()
    {
        self::roomQuery()->get();
    }

    public static function storeroom($request){

        $checkIfExist = DB::table('rooms')
                        ->where('roomname',strtoupper($request->get('rn')))
                        ->where('deleted','0')
                        ->get();

        if(count($checkIfExist)==0){
            DB::table('rooms')->insert([
                'roomname'=>strtoupper($request->get('rn')),
                'capacity'=>$request->get('ca'),
                'deleted'=>'0',
                'buildingid'=>'0',
                'createdby'=>auth()->user()->id,
                'createddatetime'=>Carbon::now('Asia/Manila')
            ]);
            return back()->with('success', ['Room '.$request->get('rn').' has been successfully added!']);  
        }
        else{
            return back()->with('error', ['Room '.$request->get('rn').' already exist!']);  
        }

      
    }

    public static function updateroom(
        $roomId = null , 
        $roomName = null,
        $roomCapacity = null,
        $building = null
    ){


        $data = ['id'=>$roomId,'roomName'=>$roomName,'roomCapacity' => $roomCapacity,'building'=>$building];

        $validator = self::validateform($data);

     

        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');

            return back()->withErrors($validator)->withInput()->with('invalidupdate','invalidupdate');
        }
        else{

            DB::table('rooms')
                ->where('id',$roomId)
                ->update([
                    'roomname'=>$roomName,
                    'capacity'=>$roomCapacity,
                    'buildingid'=>$building,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>Carbon::now('Asia/Manila')
                    ]);

            toast('Successfull','success')->autoClose(2000)->toToast($position = 'top-right');

            return back();
        }



    }


    public static function validateform(
        $data
    ){

        $message = [
            'roomName.required'=>'Room Name is required',
            'roomName.unique'=>'Room Name already exist',
            'roomCapacity.required'=>'Room Capacity is required',
            'roomCapacity.integer'=>'Room capacity must be integer',
            'building.required'=>'Room building is required'
        ];

        $validator = Validator::make($data, [
            'roomName' => ['required',Rule::unique('rooms','roomname')->where(function($query){
                return $query->where('deleted','0');
            })->ignore($data['id'],'id')],
            'roomCapacity' => 'required|integer',
            'building' => 'required',
        ], $message);

        return $validator;

    }

    public static function createRoom($roomName = null,$roomCapacity = null,$building = null){

        $data = ['id'=>null,'roomName'=>$roomName,'roomCapacity' => $roomCapacity,'building'=>$building];

        $validator = self::validateform($data);

        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');

            return back()->withErrors($validator)->withInput();
        }
        else{

            DB::table('rooms')
                ->insert([
                    'roomname'=>strtoupper($roomName),
                    'capacity'=>$roomCapacity,
                    'deleted'=>'0',
                    'buildingid'=>$building,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>Carbon::now('Asia/Manila')
                    ]);

            toast('Successfull','success')->autoClose(2000)->toToast($position = 'top-right');

            return back();
        }
    }


    // public static function roomTable($rooms){

    //     $dataString='';

    //     foreach($rooms as $key=>$item){
    //         $dataString .= '
    //             <tr>
    //                 <td class="align-middle">'.$item->roomname.'</td>
    //                 <td class="align-middle">'.$item->capacity.'</td>
    //                 <td>
    //                     <button class="text-primary btn btn-xs ee bg-info p-1" id="'.$item->id.'"><i class="far fa-edit"></i></button>
    //                     <button class="text-danger btn btn-xs bg-danger p-1" id="'.$item->id.'"><i class="fa fa-trash"></i></button>
    //                 </td>
    //             </tr> ';
    //     }

    //     return $dataString;


    // }

    public static function getRooms(
        $skip = null, 
        $take = null, 
        $searchid = null,
        $search = null,
        $type = 'all'
    ){

        $data = array();
        $roomQuery = DB::table('rooms')
                        ->leftJoin('building','rooms.buildingid','=','building.id');

        if($search!=null){

            $roomQuery->where(function($query) use($search){
                $query->where('rooms.roomname','like',$search.'%');
            });

            Session::put('search',$search);
            Session::put('pagenum','1');

        }

        if($searchid!=null){

            $roomQuery->where('id',$searchid);
        }

        $roomQuery->where('rooms.deleted','0');

        if($type == 'vacant'){

            $roomQuery->leftJoin('sections',function($join){
                $join->on('rooms.id','=','sections.roomid');
                $join->where('sections.deleted',0);
            })
            ->where('sections.id',null);

        }

        $count = $roomQuery->count();



        if($take!=null){
            $roomQuery->take($take);
        }

        if($skip!=null){

            $roomQuery->skip(($skip-1)*$take);
            
            Session::put('pagenum',$skip);
            Session::put('count',$count);
        }

        $rooms = $roomQuery->select('rooms.*','building.description')->get();

        array_push($data,(object)['data'=>$rooms,'count'=>$count]);

        return $data;    

    }



    public static function getRoomUsage($id){

        $roomInUse = false;
       
        $cdetails = DB::table('classsched')
            ->join('classscheddetail', 'classsched.id', '=', 'classscheddetail.headerid')
            ->join('sections', 'classsched.sectionid', '=', 'sections.id')
            ->select('sections.sectionname')
            ->where('classscheddetail.roomid', $id)
            ->where('classscheddetail.deleted', '0')
            ->where('classsched.deleted','0')
            ->distinct()
            ->count();


    
        $shcdetail = DB::table('sh_classsched')
                ->leftJoin('sh_classscheddetail', 'sh_classsched.id', '=','sh_classscheddetail.headerid')
                ->where('sh_classscheddetail.roomid', $id)
                ->where('sh_classscheddetail.deleted', '0')
                ->where('sh_classsched.deleted','0')
                ->count();


        $shcblocksched = DB::table('sh_blocksched')
                ->leftJoin('sh_blockscheddetail', 'sh_blocksched.id', '=','sh_blockscheddetail.headerid')
                ->where('sh_blockscheddetail.roomid', $id)
                ->where('sh_blockscheddetail.deleted', '0')
                ->where('sh_blocksched.deleted','0')
                ->count();   

          
        if($cdetails > 0 || $shcdetail > 0 || $shcblocksched > 0){

            $roomInUse = true;

        }

        return  $roomInUse;

    }
}
