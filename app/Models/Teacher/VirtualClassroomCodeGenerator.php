<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Model;
use DB;
use Crypt;

class VirtualClassroomCodeGenerator extends Model
{
    public static function codegenerator()
    {

        $length = 6;    
        return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
    }
    public static function codegeneration()
    {

        $schoolid = Db::table('schoolinfo')
                    ->first()
                    ->schoolid;
               
        $code = self::codegenerator();
        $checkifexists = Db::table('virtualclassrooms')
            ->where('code', 'essentiel'.$schoolid.$code)
            ->get();

        if(count($checkifexists)>0){
            self::codegeneration();
        }else{
            return 'essentiel'.$schoolid.$code;
        }
    }
    public static function passwordgeneration()
    {

        $code = self::codegenerator();
        $checkifexists = Db::table('virtualclassrooms')
            ->where('password', $code)
            ->get();

        if(count($checkifexists)>0){
            self::passwordgeneration();
        }else{
            return $code;
        }
    }
}
