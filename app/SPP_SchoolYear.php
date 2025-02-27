<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class SPP_SchoolYear extends Model
{
    public static function loadAllSchoolYear(){

        return DB::table('sy')->get();
    }

    public static function storeschoolyear($request){

        $stime = Carbon::create($request->get('stime'));
        $etime = Carbon::create($request->get('etime'));

        $sydesc = $etime->isoFormat('YYYY').'-'.$etime->isoFormat('YYYY');

        $checkShoolYear = DB::table('sy')->where('sydesc',$sydesc)->get();

        if(count($checkShoolYear) == 0){

            DB::table('sy')->insert([
                'sydesc'=>$sydesc,
                'sdate'=>$stime->isoFormat('YYYY-MM-DD'),
                'edate'=>$etime->isoFormat('YYYY-MM-DD'),
                'isactive'=>'0',
                'createdby'=>auth()->user()->id
            ]);

            return back();
        }
        else{

            return back()->with('error',['School Year Already Exist']);  

        }

    }

    public static function setschoolyearactive($request){

        DB::table('sy')->where('isactive','1')->update(['isactive'=>'0']);
        DB::table('sy')->where('id',$request->get('i'))->update(['isactive'=>'1']);

    }

    public static function getActiveSchoolYear(){

        return DB::table('sy')->where('isactive','1')->first();
        
    }
}
