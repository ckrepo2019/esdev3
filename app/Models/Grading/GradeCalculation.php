<?php

namespace App\Models\Grading;
use DB;

use Illuminate\Database\Eloquent\Model;

class GradeCalculation extends Model
{
   
      public static function calculate_initial_grade(){



      }


      public static function grade_transmutation( $intial_grade ){

            $transmutation = DB::table('gradetransmutation')->get();

            foreach ($transmutation as $gt){

                if($intial_grade >= $gt->gfrom && $intial_grade <= $gt->gto){


                    return $gt->gvalue;

                }


            }
            if($intial_grade == 100 ){

                return 100;

            }

            return 60;


      }



}
