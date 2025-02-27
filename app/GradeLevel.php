<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use App\Grade;
use DB;
class GradeLevel extends Model
{
    // use Notifiable;

    protected $table = 'gradelevel';
    
    // protected $fillable = [
    //     'uname', 'pword'
    // ];

    // public static function getGradeLevel(){

    //     $value=DB::table('gradelevel')->orderBy('id', 'asc')->get(); 
   
    //     return $value;
    //   }
}
