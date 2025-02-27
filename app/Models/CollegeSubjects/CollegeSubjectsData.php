<?php

namespace App\Models\CollegeSubjects;

use Illuminate\Database\Eloquent\Model;
use DB;

class CollegeSubjectsData extends Model
{
      public static function college_subject_list(){

            $college_subjects = DB::table('college_subjects')
                                    ->where('deleted',0)
                                    ->get();

            return $college_subjects;

      }
    
}
