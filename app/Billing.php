<?php

namespace App;
use DB;
use \Carbon\Carbon;
use App\LoadData;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    public static function billingDetails($studentid){

        $currentMonth = Carbon::now()->isoFormat('YYYY-MM');

        $billdet = DB::table('studpaysched')
                  ->where('studid',$studentid->id)
                  ->where('deleted','0')
                  ->join('sy',function($join){
                        $join->on('studpaysched.syid','=','sy.id');
                        $join->where('isactive','1');
                    })
                  ->get();

        foreach($billdet as $key=>$item){

            if(Carbon::create($item->duedate)->isoFormat('YYYY-MM')<$currentMonth && $item->balance==0){
                $billdet->pull($key);
            }
            elseif(Carbon::create($item->duedate)->isoFormat('YYYY-MM')>$currentMonth){
                $billdet->pull($key);
            }
        }

        return $billdet;
    }

}
