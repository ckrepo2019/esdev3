<?php

namespace App\Models\CollegeInstructor;

use Illuminate\Database\Eloquent\Model;
use DB;

class CollegeInstructorProccess extends Model
{
    public static function submit_grades_status(
        $statid = null,
        $field = null
    ){
        $check = Db::table('college_grade_status')
                    ->where('id',$statid)
                    ->where('createdby',auth()->user()->id)
                    ->where('deleted',0)
                    ->first();

      
        if($check->$field == null || $check->$field == 4){

            Db::table('college_grade_status')
                    ->where('id',$statid)
                    ->update([
                        $field=>1,
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

            return 1;

        }
        else{
            return 0;
        }

    }

  

    

    



}
