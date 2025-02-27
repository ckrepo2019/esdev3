<?php

namespace App\Http\Controllers\RegistrarControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class RegistrarGoodMoralCertController extends Controller
{
    public function goodmoralcertificate(Request $request)
    {

        // return $request->all();
        $students = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.lastname',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.suffix',
                'studinfo.levelid',
                'studinfo.studstatus',
                'gradelevel.levelname',
                'gradelevel.sortid',
                'academicprogram.acadprogcode'
            )
            ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('studinfo.deleted','0')
            ->whereIn('studinfo.studstatus',[1,2,4])
            ->distinct()
            ->get();
        return $students;  
        foreach($students as $student){

            if($student->middlename != null){

                $student->middlename = $student->middlename[0].'.';

            }

            if(strtolower($student->acadprogcode) == 'shs'){
                $table = 'sh_enrolledstud';
            }else{
                $table = 'enrolledstud';
            }

            $promotionstatus = Db::table($table)
                ->where('studid', $student->id)
                ->where('levelid', $student->levelid)
                ->get();
            // return $promotionstatus
            if(count($promotionstatus) > 0){

                $student->promotionstatus = $promotionstatus[0]->promotionstatus;

            }else{
                $promotionstatus = Db::table($table)
                    ->where('studid', $student->id)
                    ->where('levelid', ($student->levelid -1 ))
                    ->get();
                if(count($promotionstatus) > 0){

                    $student->promotionstatus = $promotionstatus[0]->promotionstatus;

                }else{
                    $student->promotionstatus = 0;
                }
            }

        }
        return view('registrar.forms.goodmoralcertificate')
            ->with('students',$students);

    }
}
