<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use \Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use App\SPP_Queries;

class SPP_ClassSchedule extends Model
{

    public static function getSHStudentAssignedSubject($sectionid){
        
        return DB::table('sh_subjects')->where('sectionid',$sectionid)->get();
    }


    public static function storeAssignsubjDetail($headerid,$subjid,$teacherid){

        return DB::table('assignsubjdetail')->insert([
            'headerid'=> $headerid,
            'subjid'=>$subjid,
            'teacherid'=>$teacherid,
            'deleted'=>'0',
            'createdby'=>auth()->user()->id,
        ]);

    }

    public static function storeClassSched($levelid,$sectionid,$subjid,$sy){

        return DB::table('classsched')->insertGetId([
            'glevelid'=>$levelid,
            'sectionid'=>$sectionid,
            'subjid'=>$subjid,
            'syid'=>$sy,
            'deleted'=>'0',
            'createdby'=>auth()->user()->id,
        ]);

    }

    public static function storeClassSchedDetail($headerid,$days,$stime,$etime,$roomid){

        return DB::table('classscheddetail')->insert([
            'headerid'=>$headerid,
            'days'=>$days,
            'stime'=>$stime,
            'etime'=>$etime,
            'roomid'=>$roomid,
            'deleted'=>'0',
            'createdby'=>auth()->user()->id,
        ]);

    }

    public static function updateClassSchedDetail($headerid,$day,$stime,$etime,$roomid){
        
        return DB::table('classscheddetail')
                ->where('id',$headerid)
                ->update([
                    'stime'=> $stime,
                    'etime'=> $etime,
                    'roomid'=> $roomid,
                    'updatedby'=>auth()->user()->id
                ]);

    }

    public static function updateAssignSubjDetail($headerid,$subjid,$teacherid){

        return  DB::table('assignsubjdetail')
                    ->where('id',$headerid)
                    ->update([
                        'subjid'=>$subjid,
                        'teacherid'=>$teacherid,
                        'updatedby'=>auth()->user()->id
                    ]);
    }

    public static function removeclassscheddetail($classschedId){

        DB::table('classscheddetail')
            ->where('id',$classschedId)
            ->update(['deleted'=>'1']);

    }

    public static function removeassignsubjdetail($assignsubjdetailid){

        DB::table('assignsubjdetail')
            ->where('id',$assignsubjdetailid)
            ->update(['deleted'=>'1']);

    }

    public static function removeclasssched($classschedid){

        DB::table('classsched')
            ->where('id',$classschedid)
            ->update(['deleted'=>'1']);

    }

    

    /*
        $asd = assignsubjdetail['asssubjheaderid',headerid','subjid','teacherid']
        $csd = classscheddetail['day','glevelid','sectionid','syid','subjid','stime','etime','roomid']
    */

    public static function assignNewSubjectToExistingSection($asd, $csd){

        if($asd[0]->asssubjheaderid!=null){
            $subjid = DB::table('assignsubjdetail')->where('id',$asd[0]->asssubjheaderid)->get();
        }

        self::storeAssignsubjDetail(
                $asd[0]->headerid,
                $asd[0]->subjid,
                $asd[0]->teacherid
            );

        $classschedId = self::storeClassSched(
                    $csd[0]->glevelid,
                    $csd[0]->sectionid,
                    $csd[0]->subjid,
                    $csd[0]->syid
                );
               

        foreach($csd[0]->day as $day){

            if($asd[0]->asssubjheaderid!=null){

                $existingClassSchedId = DB::table('classsched')
                                        ->join('classscheddetail',function($join) use($day){
                                            $join->on('classsched.id','=','classscheddetail.headerid');
                                            $join->where('classscheddetail.deleted','0');
                                            $join->where('days',$day);
                                        })
                                        ->select('classscheddetail.id')
                                        ->where('sectionid',$csd[0]->sectionid)
                                        ->where('subjid',$subjid[0]->subjid)
                                        ->where('classsched.deleted','0')
                                        ->where('syid',$csd[0]->syid)
                                        ->get();

                if(count($existingClassSchedId)!=0){

                    /*
                        Remove class sched detail for the given day if subject is changed 
                    */

                    self::removeclassscheddetail($existingClassSchedId[0]->id);

                }
                
            }

            self::storeClassSchedDetail(
                $classschedId,
                $day,
                $csd[0]->stime,
                $csd[0]->etime,
                $csd[0]->roomid
            );

        }

        /* 
            check if subject in still available in the section
        */

        if($asd[0]->asssubjheaderid!=null){

            $checkIfSubjectStillExist = DB::table('classsched')
                                            ->join('classscheddetail',function($join){
                                                $join->on('classsched.id','=','classscheddetail.headerid');
                                                $join->where('classscheddetail.deleted','0');
                                            })
                                            ->where('sectionid',$csd[0]->sectionid)
                                            ->where('subjid',$subjid[0]->subjid)
                                            ->where('classsched.deleted','0')
                                            ->where('syid',$csd[0]->syid)
                                            ->count();
                                    
            if($checkIfSubjectStillExist==0){

                $subjid = $subjid[0]->subjid;

                $assignsubjdetailid =  DB::table('assignsubj')
                        ->join('assignsubjdetail',function($join) use ($subjid){
                            $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                            $join->where('assignsubjdetail.deleted','0');
                            $join->where('subjid',$subjid);
                        })
                        ->where('assignsubj.deleted','0')
                        ->where('sectionid',$csd[0]->sectionid)
                        ->select('assignsubjdetail.*')
                        ->where('syid',$csd[0]->syid)
                        ->get();

                if(count($assignsubjdetailid)>0){

                    self::removeassignsubjdetail($assignsubjdetailid[0]->id);

                }

                $classsched = DB::table('classsched')
                        ->where('subjid',$subjid)
                        ->where('sectionid',$csd[0]->sectionid)
                        ->where('classsched.deleted','0')
                        ->where('syid',$csd[0]->syid)
                        ->get();

                if(count($classsched)>0){
                    
                    self::removeclasssched($classsched[0]->id);

                }


            }
        }

        toast('Class schedule successfully created','success')->autoClose(2000)->toToast($position = 'top-right');

    }


    /*
        $asd = assignsubjdetail['headerid','subjid','teacherid']
        $csd = classscheddetail['day','glevelid','sectionid','syid','subjid','stime','etime','roomid,'classschedheaderid']
    */

    public static function udpateToExistingSection($asd, $csd){

        self::updateAssignSubjDetail(
                $asd[0]->headerid,
                $asd[0]->subjid,
                $asd[0]->teacherid
            );

        foreach($csd[0]->day as $day){

            /*
                check if classscheddetail exist in classscheddetail based on headerid and day
            */

            $countscheddetail = DB::table('classscheddetail')
                    ->where('headerid', $csd[0]->classschedheaderid)
                    ->where('days',$day)
                    ->where('deleted','0')
                    ->get();

            /*
               Insert if did not exist in classscheddetail
            */
            if(count($countscheddetail)==0){

                self::storeClassSchedDetail(
                    $csd[0]->classschedheaderid,
                    $day,
                    $csd[0]->stime,
                    $csd[0]->etime,
                    $csd[0]->roomid
                );

            }

            /*
               Update if exist in classscheddetail
            */

            else{

                self::updateClassSchedDetail(
                    $countscheddetail[0]->id,
                    $day,
                    $csd[0]->stime,
                    $csd[0]->etime,
                    $csd[0]->roomid
                );
            }
        }
    }

    public static function evaluateInsertedInformation($request){
        
        $levelid = DB::table('sections')->where('id',$request->get('section'))->select('levelid')->first();

        $activesy = DB::table('sy')->where('isactive','1')->first();

        $time = explode(" - ", $request->get('t'));
        $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
        $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');

        $inAssignSubj = SPP_Queries::assignsubjQuery()
                                ->where('assignsubj.sectionid',$request->get('section'))
                                ->where('assignsubj.deleted','0')
                                ->where('assignsubj.syid',$activesy->id)
                                ->select('assignsubj.*')
                                ->get();

        if(count($inAssignSubj)==0){

            $assignsubjheaderid = DB::table('assignsubj')
                                ->insertGetId([
                                    'glevelid'=>$levelid->levelid,
                                    'sectionid'=>$request->get('section'),
                                    'syid'=> $activesy->id,
                                    'deleted'=>'0',
                                    'createdby'=>auth()->user()->id
                                ]);

            DB::table('assignsubjdetail')->insert([
                'headerid'=> $assignsubjheaderid,
                'subjid'=>$request->get('s'),
                'teacherid'=>$request->get('tea'),
                'deleted'=>'0',
                'createdby'=>auth()->user()->id
            ]);

        }
        else{

            $subjectExist = SPP_Queries::getStudentAssignedSubjectsJHSQuery()
                                ->where('assignsubj.sectionid',$request->get('section'))
                                ->where('assignsubj.deleted','0')
                                ->where('assignsubj.syid',$activesy->id)
                                ->where('assignsubjdetail.subjid',$request->get('s'))
                                ->exists();

            if(!$subjectExist){

                DB::table('assignsubjdetail')->insert([
                    'headerid'=> $inAssignSubj[0]->ID,
                    'subjid'=>$request->get('s'),
                    'teacherid'=>$request->get('tea'),
                    'deleted'=>'0',
                    'createdby'=>auth()->user()->id
                ]);

            }

        }

        $inClassSched = SPP_Queries::classschedQuery()
                            ->where('sectionid',$request->get('section'))
                            ->where('subjid',$request->get('s'))
                            ->where('deleted','0')
                            ->exists();
        
        if(!$inClassSched){
            
            $classschedid = DB::table('classsched')
                        ->insertGetId([
                            'glevelid'=>$levelid->levelid,
                            'sectionid'=>$request->get('section'),
                            'subjid'=>$request->get('s'),
                            'syid'=> $activesy->id,
                            'deleted'=>'0',
                            'createdby'=>auth()->user()->id
                        ]);

            foreach($request->get('days') as $day){

                DB::table('classscheddetail')
                        ->insert([
                            'headerid'=> $classschedid,
                            'days'=>$day,
                            'stime'=>$stime,
                            'etime'=>$etime,
                            'roomid'=>$request->get('r'),
                            'deleted'=>'0',
                            'createdby'=>auth()->user()->id
                        ]);

            }

        }
        
        return back();

    }

    public static function updateClassSchedJNS($request){

        // return $request->all();

        // {"section":"10","sub":"6","tea":"2","roo":"1","tim":"07:30 AM - 05:30 PM","csid":"2","days":["1","2","3","4","5"],"temp":"6"}




        $levelid = DB::table('sections')->where('id',$request->get('section'))->first();
        $activesy = DB::table('sy')->where('isactive','1')->first();

        $time = explode(" - ", $request->get('tim'));
    
        $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
        $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');

        $subjeExist = SPP_Queries::getStudentAssignedSubjectsJHSQuery()
                        ->where('assignsubj.sectionid',$request->get('section'))
                        ->where('assignsubj.deleted','0')
                        ->where('assignsubj.syid',$activesy->id)
                        ->where('assignsubjdetail.subjid',$request->get('sub'))
                        ->exists();


        if($subjeExist){

            $inAssignSubj = SPP_Queries::assignsubjQuery()
                                ->where('assignsubj.sectionid',$request->get('section'))
                                ->where('assignsubj.deleted','0')
                                ->where('assignsubj.syid',$activesy->id)
                                ->select('assignsubj.*')
                                ->first();

            DB::table('assignsubjdetail')
                    ->where('headerid',$inAssignSubj->ID)
                    ->where('subjid',$request->get('sub'))
                    ->update([
                        'teacherid'=>$request->get('tea'),
                        'updatedby'=>auth()->user()->id

                    ]);


        }
        else{

            $inAssignSubj = SPP_Queries::assignsubjQuery()
                                ->where('assignsubj.sectionid',$request->get('section'))
                                ->where('assignsubj.deleted','0')
                                ->where('assignsubj.syid',$activesy->id)
                                ->select('assignsubj.*')
                                ->first();

            DB::table('assignsubjdetail')->insert([
                'headerid'=> $inAssignSubj->ID,
                'subjid'=>$request->get('sub'),
                'teacherid'=>$request->get('tea'),
                'deleted'=>'0',
                'createdby'=>auth()->user()->id
            ]);

            DB::table('assignsubjdetail')
                ->where('headerid',$inAssignSubj->ID)
                ->where('subjid',$request->get('temp'))
                ->update([
                    'deleted'=>'1',
                    'deletedby'=>auth()->user()->id
                ]);

        }

        $inClassSched = SPP_Queries::classschedQuery()
                            ->where('sectionid',$request->get('section'))
                            ->where('subjid',$request->get('sub'))
                            ->where('deleted','0')
                            ->exists();

        if($inClassSched){

            $inClassSched = SPP_Queries::classschedQuery()
                    ->where('sectionid',$request->get('section'))
                    ->where('subjid',$request->get('sub'))
                    ->where('deleted','0')
                    ->select('classsched.*')
                    ->first();

            $classsched = SPP_Queries::classschedQuery()
                            ->join('classscheddetail',function($join){
                                $join->on('classsched.id','=','classscheddetail.headerid');
                                $join->where('classscheddetail.deleted','0');
                            })
                            ->select('classscheddetail.*')
                            ->where('sectionid',$request->get('section'))
                            ->where('subjid',$request->get('sub'))
                            ->where('classsched.deleted','0')
                            ->get();

            $day = SPP_Days::loadDays();

            foreach($day  as $item){

                $indb = false;
                $ingiven = false;
    
                foreach($classsched as $csh){
                    if($csh->days ==  $item->id){
                        $indb = true;
                    }
                }
    
                foreach($request->get('days') as $day){
    
                    if($day ==  $item->id){
                        $ingiven = true;
                    }
    
                }
    
                if($indb && $ingiven){
    
                    $time = explode(" - ", $request->get('tim'));
    
                    $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
                    $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');
    
                    DB::table('classscheddetail')
                        ->where('headerid',$inClassSched->id)
                        ->where('days',$item->id)
                        ->where('deleted','0')
                        ->update([
                            'stime'=>$stime,
                            'etime'=>$etime,
                            'roomid'=>$request->get('roo')
                        ]);
    
                }
    
                else if($indb && !$ingiven){
    
                    DB::table('classscheddetail')
                        ->where('headerid',$inClassSched->id)
                        ->where('days',$item->id)
                        ->where('deleted','0')
                        ->update([
                           'deleted'=>'1',
                           'deletedby'=>auth()->user()->id
                        ]);
    
                }
                else if(!$indb && $ingiven){
    
                    $time = explode(" - ", $request->get('tim'));
    
                    $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
                    $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');
    
                    DB::table('classscheddetail')
                        ->insert([
                            'headerid'=>$inClassSched->id,
                            'stime'=>$stime,
                            'etime'=>$etime,
                            'roomid'=>$request->get('roo'),
                            'days'=>$item->id
                        ]);
    
                }
    
            }

            return "adad";


        }

        else{

            //insert new class sched
            $classschedid = DB::table('classsched')
                        ->insertGetId([
                            'glevelid'=>$levelid->id,
                            'sectionid'=>$request->get('section'),
                            'subjid'=>$request->get('sub'),
                            'syid'=> $activesy->id,
                            'deleted'=>'0',
                            'createdby'=>auth()->user()->id
                        ]);

            foreach($request->get('days') as $day){

                DB::table('classscheddetail')
                        ->insert([
                            'headerid'=> $classschedid,
                            'days'=>$day,
                            'stime'=>$stime,
                            'etime'=>$etime,
                            'roomid'=>$request->get('roo'),
                            'deleted'=>'0',
                            'createdby'=>auth()->user()->id
                        ]);

            }
            
            //remove existing subject in class sched

            $getTempid = DB::table('classsched')
                        ->where('sectionid',$request->get('section'))
                        ->where('subjid',$request->get('temp'))
                        ->where('deleted','0')
                        ->first();

            DB::table('classsched')
                ->where('sectionid',$request->get('section'))
                ->where('subjid',$request->get('temp'))
                    ->update([
                        'deleted'=>'1',
                        'deletedby'=>auth()->user()->id
                    ]);

            
            DB::table('classscheddetail')
                ->where('headerid',$getTempid->id)
                ->update([
                    'deleted'=>'1',
                    'deletedby'=>auth()->user()->id
                ]);

            return "sfsdf";

        }
        

        return 'success';

    }



    // public static function evaluateInsertedInformation($request){
        
    //     $sy = DB::table('sy')->where('isactive','1')->first();

    //     $sections = DB::table('sections')->where('id',$request->get('section'))->first();

    //     $assignedsubjects = DB::table('assignsubj')
    //                         ->leftJoin('sy',function($join){
    //                             $join->on('assignsubj.syid','=','sy.id');
    //                             $join->where('isactive','1');
    //                         })
    //                         ->where('sectionid',$request->get('section'))
    //                         ->where('assignsubj.deleted','0')
    //                         ->select('assignsubj.*')
    //                         ->get();

    //     if(count($assignedsubjects)==0){

    //         $assignsubjId = DB::table('assignsubj')->insertGetId([
    //             'sectionid'=>$request->get('section'),
    //             'syid'=> $sy->id,
    //             'glevelid'=>$sections->levelid,
    //             'deleted'=>'0',
    //             'createdby'=>auth()->user()->id,
    //         ]);


    //         foreach($request->get('d') as $item){

    //             $time = explode(" - ", $item['ti']);

    //             $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
    //             $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');

    //             $asd =  array( (object) [
    //                 'asssubjheaderid'=>null,
    //                 'headerid' => $assignsubjId,
    //                 'subjid'=> $item['su'],
    //                 'teacherid'=>$item['te']
    //             ]);

    //             $csd  = array((object)[
    //                 'day'=>$request->get('days'),
    //                 'glevelid'=>$sections->levelid,
    //                 'sectionid'=>$request->get('section'),
    //                 'syid'=>$sy->id,
    //                 'subjid'=>$item['su'],
    //                 'stime'=> $stime,
    //                 'etime'=> $etime,
    //                 'roomid'=>$item['ro']
    //             ]);

    //             self::assignNewSubjectToExistingSection($asd,$csd);

    //             toast('Class schedule successfully created','success')->autoClose(2000)->toToast($position = 'top-right');

    //         }

    //     }

    //    /*
    //         If Section is already in assignsubj
    //     */
    //     else{
            
    //         foreach($request->get('d') as $item){
              

    //             if($item['ty']=='old'){

                  
    //                 $asdata = explode(" ", $item['as']);

    //                 $asssub = str_replace('as','',$asdata[0]);
    //                 $csid = str_replace('clasch','',$asdata[1]);

    //                 $countExistingAssignSubjDetail = DB::table('assignsubjdetail')
    //                                                     ->where('headerid',$assignedsubjects[0]->ID)
    //                                                     ->where('subjid',$item['su'])
    //                                                     ->count();
                    
    //                 /*
    //                     If Section Subject is Changed
    //                     then store new classscheddetail, classsched, and classscheddetail
    //                 */

               
                    
    //                 if($countExistingAssignSubjDetail == 0 ){
                       

    //                     $time = explode(" - ", $item['ti']);

    //                     $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
    //                     $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');
        
    //                     $asd =  array( (object) [
    //                         'asssubjheaderid'=>$asssub,
    //                         'headerid' => $assignedsubjects[0]->ID,
    //                         'subjid'=> $item['su'],
    //                         'teacherid'=>$item['te']
    //                     ]);
        
    //                     $csd  = array((object)[
    //                         'day'=>$request->get('days'),
    //                         'glevelid'=>$sections->levelid,
    //                         'sectionid'=>$request->get('section'),
    //                         'syid'=>$sy->id,
    //                         'subjid'=>$item['su'],
    //                         'stime'=> $stime,
    //                         'etime'=> $etime,
    //                         'roomid'=>$item['ro']
    //                     ]);
                     
                     
    //                     return self::assignNewSubjectToExistingSection($asd,$csd);

    //                     toast('Class schedule successfully created','success')->autoClose(2000)->toToast($position = 'top-right');

    //                 }

    //                 /*
    //                     If Section Subject is did not changed
    //                     then update only the teacherid in classscheddetail
    //                 */

    //                 else{

                      

    //                     if($item['ti']!="Time Not Set"){
                            
    //                         $time = explode(" - ", $item['ti']);
    //                         $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
    //                         $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');
            
    //                         $asd =  array( (object) [
    //                             'headerid' => $asssub,
    //                             'subjid'=> $item['su'],
    //                             'teacherid'=>$item['te']
    //                         ]);
            
    //                         $csd  = array((object)[
    //                             'day'=>$request->get('days'),
    //                             'glevelid'=>$sections->levelid,
    //                             'sectionid'=>$request->get('section'),
    //                             'syid'=>$sy->id,
    //                             'subjid'=>$item['su'],
    //                             'stime'=> $stime,
    //                             'etime'=> $etime,
    //                             'roomid'=>$item['ro'],
    //                             'classschedheaderid'=>$csid
    //                         ]);
                           
            
    //                         self::udpateToExistingSection($asd,$csd);

    //                         toast('Class schedule successfully update','info')->autoClose(2000)->toToast($position = 'top-right');

    //                     }
    //                 }
    //             }

    //             /*
    //                 If section is in assignsubj but data is new
    //             */
    //             else{

    //                 $subjid = $item['su'];

    //                 /*
    //                     Check if subject is in assignsubjdetail
    //                 */

    //                 $countsubjc = DB::table('assignsubj')
    //                                 ->join('assignsubjdetail',function($join) use($subjid){
    //                                     $join->on('assignsubj.id','=','assignsubjdetail.headerid')
    //                                     ->where('assignsubjdetail.subjid',$subjid)
    //                                     ->where('assignsubjdetail.deleted','0');
    //                                 })
    //                                 ->leftJoin('sy',function($join){
    //                                     $join->on('assignsubj.syid','=','sy.id');
    //                                     $join->where('isactive','1');
    //                                 })
    //                                 ->select('assignsubjdetail.*')
    //                                 ->where('sectionid',$request->get('section'))
    //                                 ->get();

    //                 /*
    //                     If subject is not inside assignsubjdetail
    //                 */           

    //                 if(count($countsubjc)==0){

    //                     $time = explode(" - ", $item['ti']);

    //                     $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
    //                     $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');
        
    //                     $asd =  array( (object) [
    //                         'asssubjheaderid'=>null,
    //                         'headerid' => $assignedsubjects[0]->ID,
    //                         'subjid'=> $item['su'],
    //                         'teacherid'=>$item['te']
    //                     ]);
        
    //                     $csd  = array((object)[
    //                         'day'=>$request->get('days'),
    //                         'glevelid'=>$sections->levelid,
    //                         'sectionid'=>$request->get('section'),
    //                         'syid'=>$sy->id,
    //                         'subjid'=>$item['su'],
    //                         'stime'=> $stime,
    //                         'etime'=> $etime,
    //                         'roomid'=>$item['ro']
    //                     ]);
        
    //                     self::assignNewSubjectToExistingSection($asd,$csd);

    //                     toast('Class schedule successfully created','success')->autoClose(2000)->toToast($position = 'top-right');

    //                 }
                    
    //                 /*
    //                     If subject is inside assignsubjdetail
    //                 */  
    //                 else{

    //                     if($item['ti']!="Time Not Set"){

    //                         $classschedId = DB::table('classsched')
    //                             ->where('subjid',$item['su'])
    //                             ->where('syid',$sy->id)
    //                             ->where('sectionid',$request->get('section'))
    //                             ->get();
                            
    //                         $time = explode(" - ", $item['ti']);
    //                         $stime = Carbon::create($time[0])->isoFormat('HH:mm:ss');
    //                         $etime = Carbon::create($time[1])->isoFormat('HH:mm:ss');
            
    //                         $asd =  array( (object) [
    //                             'headerid' => $countsubjc[0]->id,
    //                             'subjid'=> $subjid,
    //                             'teacherid'=>$item['te']
    //                         ]);
            
    //                         $csd  = array((object)[
    //                             'day'=>$request->get('days'),
    //                             'glevelid'=>$sections->levelid,
    //                             'sectionid'=>$request->get('section'),
    //                             'syid'=>$sy->id,
    //                             'subjid'=>$item['su'],
    //                             'stime'=> $stime,
    //                             'etime'=> $etime,
    //                             'roomid'=>$item['ro'],
    //                             'classschedheaderid'=>$classschedId[0]->id
    //                         ]);
            
    //                         self::udpateToExistingSection($asd,$csd);

    //                         toast('Class schedule successfully update','info')->autoClose(2000)->toToast($position = 'top-right');
    //                     }

    //                 }
    //             }
    //         }
    //     }
    // }
}
