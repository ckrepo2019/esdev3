<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class SPP_Strand extends Model
{
    public static function loadSHStrands(){

        return DB::table('sh_strand')
                ->where('deleted','0')
                ->where('active','1')
                ->get();
                
    }
}
