<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;

class SPP_Days extends Model
{
    public static function loadDays(){

        return DB::table('days')->get();
        
    }
}
