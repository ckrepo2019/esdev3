<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class SPP_Semester extends Model
{
    public static function loadSemester(){

        return DB::table('semester')->get();

    }
}
