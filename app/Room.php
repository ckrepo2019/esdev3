<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Section;

class Room extends Model
{
    public static function getAllRoom(){
        return DB::table('rooms')
                ->where('deleted','0')
                ->get();
    }

    public static function getVacantRoom($exceptRoom = null){

       $rooms = self:: getAllRoom();

       foreach($rooms as $key=>$item){

           $roomisnotavailable=null;

           if($item->id!=$exceptRoom){

                $roomisnotavailable = Section::getSectionByRoomId($item->id);
                
           }

           if($roomisnotavailable != null){
                if(count($roomisnotavailable)>0){
                    unset($rooms[$key]);
                }
            }

       }

       return $rooms;

    }

}
