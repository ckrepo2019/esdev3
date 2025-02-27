<?php

namespace App\Http\Controllers\CollegeControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;

class CollegeReportController extends Controller
{
    public function studentsubjects(Request $request){


        if($request->get('blade') == 'blade' && $request->has('blade')){

            return view('scholarshipcoor.pages.collegereports.studentsubjects.studentsubjectsblade');

        }
        else if($request->get('table') == 'table' && $request->has('table')){

            

            $sectionid = $request->get('sectionid');
            $courseid = $request->get('courseid');
            $gradelevelid = $request->get('gradelevelid');
            $sy = $request->get('sy');
            $sem = $request->get('sem');
            $gender = $request->get('gender');

            $college_sections = DB::table('college_sections')
                            ->where('deleted',0)
                            ->get();

            $requestskip = 0;
    
            $college_classsched = DB::table('college_classsched')
                                    ->join('college_prospectus',function($join){
                                        $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                        $join->where('college_prospectus.deleted',0);
                                    })
                                    ->join('college_studsched',function($join){
                                        $join->on('college_classsched.id','=','college_studsched.schedid');
                                        $join->where('college_studsched.deleted',0);
                                    })
                                    ->where('college_classsched.syID',$sy)
                                    ->where('college_classsched.semesterID',$sem)
                                    ->where('college_classsched.deleted',0);

                               
            $students = DB::table('studinfo')
                            ->join('college_enrolledstud',function($join) use($sy, $sem){
                                $join->on('studinfo.id','=','college_enrolledstud.studid');
                                $join->where('college_enrolledstud.deleted',0);
                                $join->where('college_enrolledstud.syid',$sy);
                                $join->where('college_enrolledstud.semid',$sem);
                            })
                            ->where('studinfo.deleted',0)
                            ->orderBy('lastname');

            if( $sectionid  != null && $sectionid  != 'null' ){

                $college_classsched =  $college_classsched->where('college_classsched.sectionid',$sectionid);
                $students =  $students->where('college_enrolledstud.sectionID',$sectionid);

            }

            if( $courseid  != null && $courseid  != 'null' ){

                $college_classsched =  $college_classsched->where('college_prospectus.courseID',$courseid);
                $students =  $students->where('studinfo.courseid',$courseid);

            }

            
            if( $gradelevelid  != null && $gradelevelid  != 'null' ){

                $college_classsched =  $college_classsched->where('college_prospectus.yearID',$gradelevelid);
                $students =  $students->where('studinfo.levelid',$gradelevelid);

            }

            if( $sy  != null && $sy  != 'null' ){

                $college_classsched =  $college_classsched->where('college_classsched.syID',$sy);
                $students =  $students->where('college_enrolledstud.syid',$sy);

            }

            if( $sem  != null && $sem  != 'null' ){

                $college_classsched =  $college_classsched->where('college_classsched.semesterID',$sem);
                $students =  $students->where('college_enrolledstud.semid',$sem);

            }

            if( $gender  != null && $gender  != 'null' ){

                $students =  $students->where('studinfo.gender',$gender);

            }


            $studentCount = $students->count();
           

            if( ( $request->get('take') != null && $request->get('take') != 'null' ) && $request->has('take')){

                $students = $students->take(10);

            }        

            if( ( $request->get('skip') != null && $request->get('skip') != 'null' ) && $request->has('skip')){

                $students = $students->skip( ( $request->get('skip') - 1 ) * 10);

                $requestskip =  ( $request->get('skip') - 1 ) * 10;

            } 


           

                     
            $college_classsched =   $college_classsched->select(
                                        'subjDesc', 
                                        'subjCode',
                                        'labunits',
                                        'lecunits',
                                        'college_classsched.subjectID as schedid'
                                        )
                                    ->distinct()
                                    ->get();

            $students = $students->select('studinfo.id','lastname','firstname')->get();
    
            foreach($students as $item){
    
                $studentSched = DB::table('college_studsched')
                                ->join('college_classsched',function($join) use($sem, $sy){
                                    $join->on('college_studsched.schedid','=','college_classsched.id');
                                    $join->where('college_classsched.deleted',0);
                                    // $join->where('college_classsched.sectionid',$sectionid);
                                    $join->where('college_classsched.syID',$sy);
                                    $join->where('college_classsched.semesterID',$sem);
                                })
                                ->join('college_prospectus',function($join){
                                    $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                    $join->where('college_prospectus.deleted',0);
                                })
                                ->select(
                                    'labunits',
                                    'lecunits',
                                    'subjCode',
                                    'college_classsched.subjectID as schedid'
                                    )
                                ->where('studid',$item->id)
                                ->where('college_studsched.deleted',0)
                                ->get();

             
    
                $item->sched = $studentSched;
    
            }


    
            $data = array((object)[
                'count'=>$studentCount,
                'data'=>$students
            ]);

            
            if( $request->get('pdf') == 'pdf' && $request->has('pdf') ){

                $activeSy = DB::table('sy')->where('id',$sy)->first();
                $activeSem = DB::table('semester')->where('id',$sem)->first();

                $course = DB::table('college_courses')->where('id',$courseid)->where('deleted',0)->select('courseDesc','courseabrv')->first();

                $gradelevel = DB::table('gradelevel')->where('id',$gradelevelid)->where('deleted',0)->select('levelname')->first();

                $schoolInfo = DB::table('schoolinfo')->join('refregion','schoolinfo.region','=','refregion.regCode')->first();

                $signatories = DB::table('signatory')->where('form','college_enrollment_report')->get();

                // return $course;

                $pdf = PDF::loadView('scholarshipcoor.pages.collegereports.studentsubjects.studentsubjectpdf',compact('college_classsched','data','activeSy','activeSem','schoolInfo','course','gender','signatories'))->setPaper('legal', 'landscape');
                $pdf->getDomPDF()->set_option("enable_php", true);

                return $pdf->stream();
              

            }else{

                return view('scholarshipcoor.pages.collegereports.studentsubjects.studentsubjecttable')
                            ->with('skip',$requestskip)
                            ->with('college_classsched',$college_classsched)
                            ->with('data',$data);

            }

           

              


        }

        

        // return $students;
        // resources\views\collegeportal\pages\reports\mStudentSubject.blade.php     

        // return view('collegeportal.pages.reports.mStudentSubject')
        //             ->with('college_classsched',$college_classsched)
        //             ->with('students',$students);


        // return $students;


        // return $college_classsched;

    }

    public function signatory(Request $request){


        if($request->get('update') == 'update' && $request->has('update')){

            try{

                DB::table('signatory')
                        ->where('id',$request->get('signatoryid'))
                        ->update([
                            $request->get('field')=>$request->get('value')
                        ]);

                return 1;

            }catch(\Exception $e){

                DB::table('zerrorlogs')
                            ->insert([
                                'error'=>$e,
                                'createdby'=>auth()->user()->id,
                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);

                return 0;
             
            }

           


        }

    }

}
