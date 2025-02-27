<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Principal\SPP_AcademicProg;
use App\Models\Principal\SPP_Gradelevel;
use Session;

class SPP_EnrolledStudent extends Model
{
    public static function getSHEnrollmentInfo($id){

        return DB::table('sh_enrolledstud')
                ->join('gradelevel',function($join){
                    $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                    $join->where('gradelevel.deleted','0');
                })
                ->join('sy',function($join){
                    $join->on('sy.id','=','sh_enrolledstud.syid');
                    $join->where('sy.isactive','1');
                })
                ->where('sh_enrolledstud.studid',$id)
                ->where('sh_enrolledstud.deleted','0')
                ->first();

    }

    public static function getJHEnrollmentInfo($id){

        return DB::table('enrolledstud')
                ->join('gradelevel',function($join){
                    $join->on('enrolledstud.levelid','=','gradelevel.id');
                    $join->where('gradelevel.deleted','0');
                })
                ->join('sy',function($join){
                    $join->on('sy.id','=','enrolledstud.syid');
                    $join->where('sy.isactive','1');
                })
                ->where('enrolledstud.studid',$id)
                ->where('enrolledstud.deleted','0')
                ->first();

    }

    
    public static function seniorHighEnrolledStud(){

        return DB::table('sh_enrolledstud') 
                            ->where('sh_enrolledstud.deleted',0)
                            ->join('sections',function($join){
                                $join->on('sections.id','=','sh_enrolledstud.sectionid');
                                $join->where('sections.deleted','0');
                            })
                            ->join('gradelevel',function($join){
                                $join->on('gradelevel.id','=','sh_enrolledstud.levelid');
                                $join->where('gradelevel.deleted','0');
                            })
                            ->leftJoin('sectiondetail',function($join){
                                $join->on('sectiondetail.sectionid','=','sh_enrolledstud.sectionid');
                                $join->where('sectiondetail.deleted','0');
                                
                                if(Session::has('schoolYear')){
                                    $join->join('sy as sectdetsy',function($join){
                                        $join->on('sectiondetail.syid','=','sectdetsy.id');
                                        $join->where('sectdetsy.id',Session::get('schoolYear')->id);
                                    });
                                
                                }
                                else{
                                    $join->join('sy as sectdetsy',function($join){
                                        $join->on('sectiondetail.syid','=','sectdetsy.id');
                                        $join->where('sectdetsy.isactive','1');
                                    })
                                    ;
                                }
                                
                            })
                            ->leftJoin('teacher',function($join){
                                $join->on('sectiondetail.teacherid','=','teacher.id');
                                $join->where('teacher.deleted','0');
                            })
                            ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id');

    }

    public static function enrolledStud(){

        $query = DB::table('enrolledstud')
                    ->where('enrolledstud.deleted',0);

        if(Session::has('schoolYear')){

            $query->join('sy',function($join){
                $join->on('enrolledstud.syid','=','sy.id');
                $join->where('sy.id',Session::get('schoolYear')->id);
            });
        
        }
        else{

            $query->join('sy',function($join){
                $join->on('enrolledstud.syid','=','sy.id');
                $join->where('sy.isactive','1');
            });
        
        }
        $query->join('sections',function($join){
            $join->on('enrolledstud.sectionid','=','sections.id');

        })
        ->leftJoin('sectiondetail',function($join){
            $join->on('sectiondetail.sectionid','=','enrolledstud.sectionid');
            $join->on('sectiondetail.syid','=','enrolledstud.syid');
            $join->where('sectiondetail.deleted','0');
       
        })
        ->join('gradelevel',function($join){
            $join->on('gradelevel.id','=','enrolledstud.levelid');
            $join->where('gradelevel.deleted','0');
        })
        ->leftJoin('teacher',function($join){
            $join->on('sectiondetail.teacherid','=','teacher.id');
            $join->where('teacher.deleted','0');
        })
        ->join('studinfo','enrolledstud.studid','=','studinfo.id');


        return $query;

    }





    public static function getStudent(
        $skip = null,
        $take = null,
        $studentid = null,
        $studentname = null,
        $acadprogid = null,
        $sectionid = null,
        $userid = null,
        $levelid = null,
        $syid = null,
        $all = false,
        $sf4 = false,
        $type = 'all',
        $gender = null,
        $grantee = null
        ){ 

        $data = array();

        if($acadprogid == 5){

            $students = self::seniorHighEnrolledStud();

            if($syid != null){

                $students->join('sy',function($join) use ($syid){
                    $join->on('sy.id','=','sh_enrolledstud.syid');
                    $join->where('sy.id',$syid);
                });
            
            }
            else{

                if(Session::has('schoolYear')){

                    $students ->join('sy',function($join){
                        $join->on('sh_enrolledstud.syid','=','sy.id');
                       $join->where('sy.id',Session::get('schoolYear')->id);
                    });
    
                }
                else{
    
                    $students ->join('sy',function($join){
                        $join->on('sh_enrolledstud.syid','=','sy.id');
                        $join->where('sy.isactive','1');
                    });
    
                }

            }

            if(Session::has('semester')){

                $students->join('semester',function($join) use ($syid){
                    $join->on('sh_enrolledstud.semid','=','semester.id');
                    $join->where('semester.id',Session::get('semester')->id);
                });

                
            
            }
            else{

                $students->join('semester',function($join){
                    $join->on('sh_enrolledstud.semid','=','semester.id');
                    $join->where('semester.isactive','1');
                });

            }

            if($type == 'all'){

                $students->select('sh_enrolledstud.promotionstatus','sh_enrolledstud.studstatus as enstudstat','sh_enrolledstud.sectionid','sh_enrolledstud.studstatus');
            
            }


        }
        elseif($acadprogid == 6){

            $students = DB::table('studinfo')
                            ->join('college_enrolledstud',function($join){
                                $join->on('studinfo.id','=','college_enrolledstud.studid');
                            })
                            ->leftJoin('college_sections',function($join){
                                $join->on('college_enrolledstud.sectionID','=','college_sections.id');
                            })
                            ->join('gradelevel',function($join){
                                $join->on('college_enrolledstud.yearLevel','=','gradelevel.id');
                            });

            if($syid != null){

                $students->join('sy',function($join) use ($syid){
                    $join->on('college_enrolledstud.syid','=','sy.id');
                    $join->where('sy.id',$syid);
                });
            
            }
            else{
    
                $students ->join('sy',function($join){
                    $join->on('college_enrolledstud.syid','=','sy.id');
                    $join->where('sy.isactive','1');
                });
    

            }

            $students->join('semester',function($join){
                $join->on('college_enrolledstud.semid','=','semester.id');
                $join->where('semester.isactive','1');
            });

            

            if($type == 'all'){

                $students->select('college_enrolledstud.promotionstatus','college_enrolledstud.studstatus as enstudstat','college_enrolledstud.sectionid', 'college_enrolledstud.studstatus');
            
            }

        }
        else{

            $students = self::enrolledStud();

            if($type == 'all'){

                $students->select('enrolledstud.promotionstatus','enrolledstud.studstatus as enstudstat', 'enrolledstud.studstatus');

            }
            

        }

        if($acadprogid !=null && $acadprogid != 5){

            $students->where('acadprogid',$acadprogid);

        }

        if($sectionid!=null){

            if( $acadprogid == 5){

                $students->where('sh_enrolledstud.sectionid',$sectionid);
               

            }
            elseif($acadprogid == 6){

                $students->where('college_enrolledstud.sectionID',$sectionid);

            }
            else{

                $students->where('enrolledstud.sectionid',$sectionid);

            }
   
        }

        if( $acadprogid == 5){

            if(!$all){

                $students->where('sh_enrolledstud.studstatus','1');
            
            }
            if($sf4){

                $students->whereIn('sh_enrolledstud.studstatus',['1','2','3','4','5']);

            }

        }elseif($acadprogid == 6){

            if(!$all){

                $students->where('college_enrolledstud.studstatus','1');
            
            }
            if($sf4){

                $students->whereIn('college_enrolledstud.studstatus',['1','2','3','4','5']);

            }

        }
        else{

            if(!$all){

                $students->where('enrolledstud.studstatus','1');

            }
            if($sf4){

                $students->whereIn('enrolledstud.studstatus',['1','2','3','4','5']);

            }


        }

            



        if($levelid!=null){

            $students->where('studinfo.levelid',$levelid);

        }

        if($gender!=null){

            $students->where('studinfo.gender',$gender);

        }

        if($grantee!=null){

            $students->where('studinfo.grantee',$grantee);

        }
   
        if($studentid !=null){
        
            $students->where('studinfo.id',$studentid);

        }

        if($studentname !=null){
           
            $students->where(function($query) use($studentname){
                $query->where('studinfo.firstname','like','%'.$studentname.'%');
                $query->orWhere('studinfo.lastname','like','%'.$studentname.'%');
                $query->orWhere('gradelevel.levelname','like','%'.$studentname.'%');
                $query->orWhere('sections.sectionname','like','%'.$studentname.'%');
            });

        }

 
        if($userid!=null){

            $students->where('studinfo.userid',$userid);
            
        }

        if($sf4){

            if($acadprogid == 5){

                $students->select(
                    'studinfo.id',
                    'sh_enrolledstud.dateenrolled',
                    'sh_enrolledstud.studstatus',
                    'sh_enrolledstud.levelid as enlevelid',
                    'sh_enrolledstud.sectionid as ensectid',
                    'studinfo.gender',
                    'sh_enrolledstud.updateddatetime',
                    'studinfo.strandid'
                );
    
            }
            elseif($acadprogid == 6){


            }
            else{
    
                $students->select(
                    'studinfo.id',
                    'enrolledstud.dateenrolled',
                    'enrolledstud.studstatus',
                    'studinfo.gender',
                    'enrolledstud.levelid as enlevelid',
                    'enrolledstud.updateddatetime',
                    'enrolledstud.sectionid as ensectid',
                    'studinfo.strandid'
                   
                );
    
            }

            


        }
  
 
        if($type == 'all'){

            if($acadprogid == 2 || $acadprogid == 3 || $acadprogid == 4 || $acadprogid == 5 || $acadprogid == null)

                $students->addSelect(
                    'studinfo.userid',
                    'studinfo.firstname',
                    'studinfo.lastname',
                    'studinfo.middlename',
                    'studinfo.lrn',
                    'studinfo.gender',
                    'studinfo.contactno',
                    'studinfo.gcontactno',
                    'studinfo.mcontactno',
                    'studinfo.fcontactno',
                    'studinfo.sid',
                    'studinfo.picurl',
                    'studinfo.blockid',
                    'studinfo.id',
                    'studinfo.levelid',
                    'studinfo.semid',
                    'studinfo.sectionid',
                    'sections.sectionname',
                    'gradelevel.levelname',
                    'gradelevel.acadprogid',
                    'teacher.firstname as teacherfirstname',
                    'teacher.lastname as teacherlastname',
                    'teacher.middlename as teachermiddlename',
                    'sections.sectionname as ensectname',
                    'gradelevel.levelname as enlevelname',
                    'studinfo.dob',
                    'studinfo.grantee',
                    'studinfo.strandid',
                    'studinfo.suffix'
                );

            else{

                $students->addSelect(
                    'studinfo.userid',
                    'studinfo.firstname',
                    'studinfo.lastname',
                    'studinfo.middlename',
                    'studinfo.lrn',
                    'studinfo.gender',
                    'studinfo.contactno',
                    'studinfo.gcontactno',
                    'studinfo.mcontactno',
                    'studinfo.fcontactno',
                    'studinfo.sid',
                    'studinfo.picurl',
                    'studinfo.blockid',
                    'studinfo.id',
                    'studinfo.levelid',
                    'studinfo.semid',
                    'studinfo.sectionid',
                    'college_sections.sectionDesc',
                    'gradelevel.levelname',
                    'gradelevel.acadprogid',
                    'college_sections.sectionDesc as ensectname',
                    'gradelevel.levelname as enlevelname',
                    'studinfo.dob',
                    'studinfo.grantee',
                    'studinfo.strandid',
                    'studinfo.suffix'
                );

            }
            
        }
        else if($type == 'basic'){

            $students->addSelect(

                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.id',
                'studinfo.contactno',
                'studinfo.gcontactno',
                'studinfo.mcontactno',
                'studinfo.fcontactno',
                'studinfo.isfathernum',
                'studinfo.ismothernum',
                'studinfo.isguardannum',
                'studinfo.sid',
                'studinfo.picurl',
                'gradelevel.acadprogid',
                'sections.sectionname as ensectname',
                'gradelevel.levelname as enlevelname',
                'studinfo.strandid',
                'studinfo.suffix'

            );

        }
        else if($type == 'sf6'){

            if($acadprogid == 5){

                $students->select(
                    'studinfo.id',
                    'sh_enrolledstud.dateenrolled',
                    'sh_enrolledstud.studstatus',
                    'sh_enrolledstud.levelid as enlevelid',
                    'sh_enrolledstud.sectionid as ensectid',
                    'studinfo.gender',
                    'studinfo.strandid'
                )
                ->where('sh_enrolledstud.studstatus','1');
    
            }
            else{
    
                $students->select(
                    'studinfo.id',
                    'enrolledstud.dateenrolled',
                    'enrolledstud.studstatus',
                    'studinfo.gender',
                    'enrolledstud.levelid as enlevelid',
                    'enrolledstud.updateddatetime',
                    'enrolledstud.sectionid as ensectid',
                    'studinfo.strandid'
                )
                ->where('enrolledstud.studstatus','1');
    
            }

            return $students->get();

        }
        else if($type == 'namId'){

            $students->addSelect(

                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.id',
                'studinfo.strandid',
                'studinfo.suffix'

            );

        }
      

        if($type == 'all'){

            if( $acadprogid == 5){

                $students->addSelect('sh_enrolledstud.sectionid as ensectid');
                $students->addSelect('sh_enrolledstud.levelid as enlevelid');
                $students->addSelect('sh_enrolledstud.id as enid');
                $students->addSelect('sh_enrolledstud.syid');
                
            }
            else if( $acadprogid == 6){

                $students->addSelect('college_enrolledstud.sectionID as ensectid');
                $students->addSelect('college_enrolledstud.yearLevel as enlevelid');
                $students->addSelect('college_enrolledstud.id as enid');
                $students->addSelect('college_enrolledstud.syid');

            }
            else{

                $students->addSelect('enrolledstud.sectionid as ensectid');
                $students->addSelect('enrolledstud.levelid as enlevelid');
                $students->addSelect('enrolledstud.id as enid');
                $students->addSelect('enrolledstud.syid');

            }

            if($acadprogid == 5){

                $students->addSelect('sh_enrolledstud.dateenrolled');

            }
            else if( $acadprogid == 6){

                $students->addSelect('college_enrolledstud.date_enrolled');
            }
            else{

                $students->addSelect('enrolledstud.dateenrolled');

            }
            $students->addSelect('studinfo.studstatdate');

        }


      
        $count = $students->distinct()->get()->count();

        $students = $students->orderBy('lastname')->distinct();

        if($take != null){

            $students->take($take);

        }

        if($skip != null){

            $students->skip(($skip-1)*$take);
        }


        array_push($data,(object)[
            'data'=>$students->get(),
            'count'=>$count
            ]);

        return $data;   

    }

    public static function searchAllLowerYearStudents($acadprogid,$studInfo,$pagenum){ /* Kinder to Junior High*/

        $students = SPP_AcademicProg::searchAllEnrolledStudentBaseOnAcademicProgram($acadprogid,$studInfo);

        $data = array();

        $studentCountSH = count($students)/6;

        if(round($studentCountSH) < $studentCountSH){
            $studentCountSH = round($studentCountSH)+1;
        }
        else{
            $studentCountSH = round($studentCountSH);
        }
        
        array_push($data, (object) array(
            'students'=>$students->slice(($pagenum-1)*6)->take(6),
            'studentcount'=> $studentCountSH
            ));

        return $data;
        
    }




    
    public static function EnrolledStudQuerySHS(){

        return DB::table('sh_enrolledstud') 
            ->join('sy',function($join){
                $join->on('sy.id','=','sh_enrolledstud.syid');
                $join->where('sy.isactive','1');
            })
            ->join('semester',function($join){
                $join->on('semester.id','=','sh_enrolledstud.semid');
                $join->where('sy.isactive','1');
            })
            ->join('sections',function($join){
                $join->on('sections.id','=','sh_enrolledstud.sectionid');
                $join->where('sections.deleted','0');
            })
            ->join('gradelevel',function($join){
                $join->on('gradelevel.id','=','sh_enrolledstud.levelid');
                $join->where('gradelevel.deleted','0');
            })
            ->join('studinfo','sh_enrolledstud.studid','=','studinfo.id');

    } 
    
    public static function checkStudentEnrollmentStatusSHS($studentid){

        return self::EnrolledStudQuerySHS()->where('studid',$studentid)->count();

    }

    public static function checkStudentEnrollmentStatusJHS($studentid){

        return self::EnrolledStudQueryJHS()->where('studid',$studentid)->count();


    }

    public static function studentlistToString($students,$studentCount,$pagenum){

        $dataString = '';

        if(count($students)>0){
            $dataString .='<div class="row d-flex align-items-stretch p-4">';
           foreach($students as $student){
               $dataString .= '<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                   <div class="card bg-light w-100">
                       <div class="card-header text-muted border-bottom-0">
                       </div>
                       <div class="card-body pt-0">
                       <div class="row">
                           <div class="col-7">
                           <h2 class="lead"><b> '.strtoupper(explode(' ',trim($student->lastname))[0]).'<span class="h6"><br>'.strtoupper($student->firstname).'</span></b></h2>
                           '.$student->levelname.' <br> '.$student->sectionname.'
                           <p class="text-muted text-sm"><b>Student ID</b><br>'.$student->sid.'</p>
                           <ul class="ml-4 mb-0 fa-ul text-muted">
                           <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span>Phone #: '.$student->contactno.'</li>
                           </ul>
                           </div>
                           <div class="col-5 text-center">
                           <img src="'.asset($student->picurl).'" alt="" class="img-circle img-fluid">
                           </div>
                       </div>
                       </div>
                       <a href="/principalPortalStudentProfile/'.$student->id.'/'.$student->acadprogid.'" class="card-footer bg-info text-center"><span class="text-white">More info </span><i class=" text-white fas fa-arrow-circle-right"></i></a>
                   </div>
               </div>';
           }
           $dataString .=' </div>';
       }
       else{
           $dataString .='<div class="row d-flex align-items-stretch p-4">';
           $dataString .= '<a class="w-100 text-center">No Student Found</a>';
           $dataString .='<div">';
       }
        return  $dataString;

    }


    public static function EnrolledStudQueryJHS(){

        $studendinfo =  SPP_EnrolledStudent::getStudent(null,null,null,null,$studendinfoAcadProg->acadprogid,null,auth()->user()->id);

        return  $studendinfo;

        return DB::table('enrolledstud') 
            ->join('sy',function($join){
                $join->on('sy.id','=','enrolledstud.syid');
                $join->where('sy.isactive','1');
            })
            ->join('gradelevel',function($join){
                $join->on('gradelevel.id','=','enrolledstud.levelid');
                $join->where('gradelevel.deleted','0');
            })
            ->join('studinfo','enrolledstud.studid','=','studinfo.id');

    } 

    public static function getallenrolledstudbyGradeLevel($levelid){

        $gradelevelInfo = SPP_Gradelevel::getGradeLevelAcadProg($levelid);

        if($gradelevelInfo->acadprogid == '5'){

            return DB::table('sh_enrolledstud')->where('levelid',$levelid)->get();

        }

        else{

            return DB::table('enrolledstud')
                    ->where('enrolledstud.levelid',$levelid)
                    ->join('studinfo',function($join){
                        $join->on('enrolledstud.studid','=','studinfo.id');
                        $join->where('studinfo.deleted','0');
                    })
                    ->join('sy',function($join){
                        $join->on('sy.id','=','enrolledstud.syid');
                        $join->where('sy.isactive','1');
                    })
                    ->select('studinfo.*')
                    ->get();

        }


    }





}
