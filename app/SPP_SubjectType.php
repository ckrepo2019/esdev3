<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class SPP_SubjectType extends Model
{

    public static function loadSubjectType(){

        return DB::table('sh_subjecttype')->get();
        
    }
  
}
