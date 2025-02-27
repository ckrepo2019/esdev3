<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Model;
use DB;
use Hash;
class PasswordGenerator extends Model
{
    public static function getallusers($usertypeid)
    {
        // $students = DB::table('studinfo')
        //     ->select('studinfo.id','studinfo.userid','studinfo.firstname','studinfo.middlename','studinfo.lastname','studinfo.suffix','users.email','users.passwordstr','users.password')
        //     ->join('users','studinfo.userid','=','users.id')
        //     ->orderBy('lastname','asc')
        //     ->get();

        // if(count($students)>0)
        // {
        //     foreach($students as $student)
        //     {
        //         $student->usertype = 'STUDENT';
        //         $student->usertypeid = 7;
        //     }
        // }
        
        // $teachers = DB::table('teacher')
        //     ->select('teacher.id','teacher.userid','teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix','users.email','users.passwordstr','users.password','usertype.utype as usertype','usertype.id as usertypeid')
        //     ->join('users','teacher.userid','=','users.id')
        //     ->join('usertype','users.type','=','usertype.id')
        //     ->orderBy('lastname','asc')
        //     ->get();
            
        // $allusers = collect();
        // $allusers = $allusers->merge($students);
        // $allusers = $allusers->merge($teachers);
        $users = DB::table('users')
            ->select('users.id as userid','users.name','users.email','users.passwordstr','users.password','usertype.utype as usertype','usertype.id as usertypeid')
            ->join('usertype','users.type','=','usertype.id')
            ->where('users.deleted','0')
            ->where('usertype.deleted','0')
            //->where('users.id','!=','3')
            ->get();

        $allusers = $users;

        if($usertypeid != null)
        {
            $allusers = $allusers->where('usertypeid', $usertypeid)->values()->all();
        }
        return $allusers;

    }
    public static function generatepassword($userid)
    {
        
        $lowcaps = 'abcdefghijklmnopqrstuvwxyz';

        $permitted_chars = '0123456789'.$lowcaps;

        $input_length = strlen($permitted_chars);

        $random_string = '';
        for($i = 0; $i < 10; $i++) {
            $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        $checkifexists = DB::table('users')
            ->where('passwordstr','like','%'.$random_string.'%')
            ->first();

        if($checkifexists)
        {
            return self::generatepassword($userid);
        }else{
            $hashed = Hash::make($random_string);
            $data = (object)[
                'code'=>$random_string,
                'hash'=>$hashed
            ];
            DB::table('users')
                ->where('id', $userid)
                ->update([
                    'passwordstr'   => $random_string,
                    'password'      => $hashed
                ]);
        }
        
        return $data;
    }
}
