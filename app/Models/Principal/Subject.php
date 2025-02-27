<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;
use Crypt;

class Subject extends Model
{
    public static function getAllSubject(){  

        return DB::table('subjects')->where('deleted','0')->where('isactive','1')->get();
        
    }

    public static function storeSubject($request){

        $dataString = '';


        if($request->get('sn')==null){
            $dataString = '<ul>
                            <li class="text-danger">Suject Name is required</li>     
                          </ul>';
        }

        else{

            $existingsubj = self::getSubjectbyname(strtoupper($request->get('sn')));

            if(count($existingsubj)==0){
                DB::table('subjects')->insert([
                    'subjdesc'=>strtoupper($request->get('sn')),
                    'subjcode'=>$request->get('sc'),
                    'deleted'=>'0',
                    'isactive'=>'1',
                    'createdby'=>auth()->user()->id
                ]);
            }
            else{
                $dataString = '<ul>
                                <li class="text-danger">Suject Name already exist</li>     
                               </ul>';
            }
        }

        return $dataString;

    }

    public static function updateSubject($request){
        
        $dataString = '';

        $acadprog = DB::table('teacher')
                        ->leftJoin('academicprogram','teacher.id','=','academicprogram.principalid')
                        ->select('academicprogram.id as acadid')
                        ->where('teacher.userid',auth()->user()->id)
                        ->where('teacher.deleted','0')
                        ->get();

                        
        if($request->get('sn')==null){
            $dataString = '<ul>
                            <li class="text-danger">Suject Name is required</li>     
                          </ul>';
        }


        else{

            $getsubjectInfo = self::getSubjectbyid($request->get('i'));

            if($getsubjectInfo[0]->subjdesc!=$request->get('sn')){
                $existingsubj = self::getSubjectbyname(strtoupper($request->get('sn')));
                
                if(count($existingsubj)==0){
                    DB::table('subjects')
                            ->where('id',Crypt::decrypt($request->get('i')))
                            ->update([
                                'subjdesc'=>strtoupper($request->get('sn')),
                                'subjcode'=>$request->get('sc')
                                ]);
                }
                else{
                    $dataString = '<ul>
                                    <li class="text-danger">Subject Name already exist</li>     
                            </ul>';
                }
            }
            else{
                $dataString = '<ul>
                                    <li class="text-danger">Subject Name did not changed</li>     
                               </ul>';
            }

        }

        return $dataString;

    }





    public static function getSubjectbyname($subjectname){

        return DB::table('subjects')
                    ->where('subjdesc',$subjectname)
                    ->where('deleted','0')
                    ->get();

    }

    public static function getSubjectbyid($subjectid){

        return DB::table('subjects')
                    ->where('id',Crypt::decrypt($subjectid))
                    ->select('subjdesc','subjcode')
                    ->get();

    }

    //Senior High

    public static function loadshsubject(){

        return DB::table('sh_subjects')->get();

    }

    public static function loadshsubjectbystrand($strandid){

        return DB::table('sh_subjects')
                ->where('strandid',$strandid)
                ->get();

    }

    
   
    
}
