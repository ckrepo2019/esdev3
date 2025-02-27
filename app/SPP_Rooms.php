<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class SPP_Rooms extends Model
{

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
                'createdby'=>auth()->user()->id
            ]);
            return back()->with('success', ['Room '.$request->get('rn').' has been successfully added!']);  
        }
        else{
            return back()->with('error', ['Room '.$request->get('rn').' already exist!']);  
        }

      
    }

    public static function updateroom($request){

        $checkIfExist = DB::table('rooms')
                        ->where('roomname',strtoupper($request->get('rn')))
                        ->where('deleted','0')
                        ->where('id','!=',$request->get('si'))
                        ->get();

        if(count($checkIfExist)==0){

            DB::table('rooms')
                ->where('id',$request->get('si'))
                ->update([
                    'roomname'=>strtoupper($request->get('rn')),
                    'capacity'=>$request->get('ca'),
                    'editedby'=>auth()->user()->id,
                ]);

            return back()->with('success', ['Room '.$request->get('rn').' has been successfully updated!']);  
        }
        else{
            return back()->with('error', ['Room '.$request->get('rn').' already exist!']);  
        }


       
    }

    public static function loadroomsbyten(){

        $data = array();

        $roomCount = count(self::roomQuery()->get())/10;

        if(round($roomCount) < $roomCount){
            $roomCount = round($roomCount)+1;
        }
        else{
            $roomCount = round($roomCount);
        }
        
        array_push($data, (object) array(
            'rooms'=>self::roomQuery()->take(10)->get(),
            'roomCount'=> $roomCount
            ));

        return $data;

    }

    public static function searchroom($request){

        $dataString = '';
        $pageString = '';
        $data = '';

        $room = self::roomQuery()
                ->where('roomname','like',$request->get('data').'%')
                ->skip(($request->get('pagenum')-1)*10)
                ->take(10)
                ->get();


        $roomCount = count(self::roomQuery()
                        ->where('roomname','like',$request->get('data').'%')
                        ->get())/10;

        if(round($roomCount) < $roomCount){
            $roomCount = round($roomCount)+1;
        }
        else{
            $roomCount = round($roomCount);
        }

        foreach($room as $key=>$item){
            $dataString .= '
                <tr>
                    <td>'.$item->roomname.'</td>
                    <td>'.$item->capacity.'</td>
                    <td>
                        <button class="text-primary btn p-0 ee mr-2" id="'.$item->id.'"><i class="far fa-edit"></i></button>
                    </td>
                </tr> ';
        }
        if($roomCount!=0){
            $pageString .='<ul class="pagination pagination-sm m-0 pt-3">
                        <li class="page-item"><a class="page-link" href="#">«</a></li>';
                        for ($x = 1; $x<=$roomCount;$x++){
                            if($x==1){
                                $pageString.=' <li class="page-item"><a class="page-link page-link-active" id="'.$x.'" href="#">'.$x.'</a></li>';
                            }
                            else{
                                $pageString.=' <li class="page-item"><a class="page-link" id="'.$x.'" href="#">'.$x.'</a></li>';
                            }
                        }
                        $pageString.=' <li class="page-item"><a class="page-link" href="#">»</a></li>
                    </ul>';
        }

        return array((object)[
            'dataString'=>$dataString,
            'pageString'=> $pageString
        ]);

    }

    




}
