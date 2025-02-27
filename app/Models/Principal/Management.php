<?php

namespace App\Models\Principal;
use DB;
use Session;

use Illuminate\Database\Eloquent\Model;

class Management extends Model
{
    public static function storeSubject($subjdesc,$subjcode,$levelid){

        $acadprogid = DB::table('academicprogram')->where('principalid',auth()->user()->id)->first();

        DB::table('subjects')->insert([
            'subjdesc'=>$subjdesc,
            'subjcode'=>$subjcode,
            'levelid'=>$levelid,
            'acadprogid'=>$acadprogid->id, 
            'createdby'=>auth()->user()->id
        ]);

    }
    

  

    

   
}
