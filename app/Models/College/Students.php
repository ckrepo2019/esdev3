<?php

namespace App\Models\College;
use DB;

use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    public static function enrolled(
          $take= null,
          $skip = null,
          $search = null
      )
    {
      
            $studentlist = DB::table('studinfo')
                              ->select(
                                    'studinfo.id',
                                    'studinfo.sid',
                                    'studinfo.firstname',
                                    'studinfo.lastname',
                                    'college_courses.courseabrv',
                                    'gradelevel.levelname',
                                    'studinfo.sectionname',
                                    'courseid',
                                    'courseDesc'
                              )
                              ->where('studstatus',1)
                              ->whereIn('levelid',['17','18','19','20','21']);

            $studentlist = $studentlist->join('gradelevel',function($join){
                                    $join->on('studinfo.levelid','=','gradelevel.id');
                              })
                              ->join('college_courses',function($join){
                                    $join->on('studinfo.courseid','=','college_courses.id');
                              });
                            
                              
            if($search != null && $search != 'null'){

                  $studentlist = $studentlist->where(function($query) use($search){
                        $query->where('firstname','like','%'.$search.'%');
                        $query->orWhere('sid','like','%'.$search.'%');
                        $query->orWhere('lastname','like','%'.$search.'%');
                        $query->orWhere('levelname','like','%'.$search.'%');
                        $query->orWhere('college_courses.courseDesc','like','%'.$search.'%');
                        $query->orWhere('college_courses.courseabrv','like','%'.$search.'%');
                  });

            }      



                        
            $studentcount = $studentlist->count();

            if($take != null){

                  $studentlist->take($take);

            }

            if($skip != null){

                  $studentlist->skip($take * ( $skip - 1 ));

            }

            $studentlist = $studentlist->get();
           

            $data = array((object)[
                  'data'=>$studentlist,
                  'count'=>$studentcount
            ]);

            return $data;
                        
     }


      public static function studentMasterList(
            $take= null,
            $skip = null,
            $serch = null,
            $studsatus = null,
            $levelid = null,
            $select = null
      )
      {

            $studentlist = DB::table('studinfo')
                              ->join('gradelevel',function($join){
                                    $join->on('studinfo.levelid','=','gradelevel.id');
                                    $join->where('gradelevel.acadprogid',6);
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->join('college_courses',function($join){
                                    $join->on('studinfo.courseid','=','college_courses.id');
                                    $join->where('college_courses.deleted',0);
                              })
                              ->where('studinfo.deleted',0);
                              ;

          

            if($select != null){

                  $studentlist->select($select);
                  
            }
            else{

                  $studentlist = $studentlist->select(
                                    'studinfo.id',
                                    'studinfo.sid',
                                    'studinfo.firstname',
                                    'studinfo.lastname',
                                    'college_courses.courseabrv',
                                    'gradelevel.levelname',
                                    'studinfo.sectionname',
                                    'courseDesc'
                              );

            }

            if($studsatus != null){

                  $studentlist = $studentlist->whereIn('studstatus',$studsatus);

            }

            if( $levelid != null){

                  $studentlist = $studentlist->whereIn('levelid',$levelid);

            }

          
            
            $studentcount = $studentlist->count();

            if($take != null){

                  $studentlist->take($take);

            }

            if($skip != null){

                  $studentlist->skip($take * ( $skip - 1 ));

            }


            $studentlist = $studentlist->get();

            $data = array((object)[
                  'data'=>$studentlist,
                  'count'=>$studentcount
            ]);

            return $data;
                        
      }

   

}
