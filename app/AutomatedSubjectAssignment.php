<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AutomatedSubjectAssignment extends Model
{
    public static function checkTeacherTimeConflict($teacherDaySched,$sampleclassstart){

        $hour = 1;
        $minutes = 0;

        foreach($teacherDaySched as $classStart){
            if( $sampleclassstart >= $classStart->stime && $sampleclassstart<=$classStart->etime){
                if($classStart->subjdesc == 'Reccess'){
                    $sampleclassstart = Carbon::create($sampleclassstart)->addMinutes(20)->isoFormat('HH:mm:ss');
                }
                else if($classStart->subjdesc == 'Lunch Break'){
                    $sampleclassstart = Carbon::create($sampleclassstart)->addMinutes(40)->isoFormat('HH:mm:ss');
                }
                else{
                    $sampleclassstart = Carbon::create($sampleclassstart)->addHour($hour)->addMinutes($minutes)->isoFormat('HH:mm:ss');
                }
            }
            else{
                break;
            }
        }

        return $sampleclassstart;
    }

    public static function assignSubject($subject,$sections,$days,$teacherId){

        $hour = 1;
        $minutes = 0;

        $recessstart = '09:30:00';
        $recessend = '09:50:00';
        $luchstart = '11:50:00';
        $lunchend = '12:30:00';
        $classduration = '01:30:00';
        $sampleclassstart = '07:30:00';
        $samlpleendtime = '17:30:00';
        $numberofsubject = 9;

        $classEnd = '';

        $subjectInfo = DB::table('subjects')->where('id',$subject)->first();

        $currentSchoolYear = DB::table('sy')->where('isactive','1')->first();

        $schedsuggestion = array();

        $suggestedschedisEmpty = true;

        foreach($days as $day){

            $techerSchedisConflict = false;

            $dayInWord = substr( DB::table('days')->where('id',$day)->first()->description,0,3);

            $teacherDaySched = ClassSched::teacherDaySched($teacherId,$day);

            //recess
            $teacherDaySched->push((object)[
                'subjdesc'=>'Reccess',
                'stime'=>$recessstart,
                'etime'=>$recessend]);

            //lunch
            $teacherDaySched->push((object)[
                'subjdesc'=>'Lunch Break',
                'stime'=>$luchstart,
                'etime'=>$lunchend]);
                
            $teacherDaySched = collect($teacherDaySched)->sortBy('stime');

            foreach($sections as $item){

                $sampleclassstart = '07:30:00';

                $sectionSchedisConflict = true;

                $sectionIsNotAssignedWithSameSubject = true;
                $subjectIsAssignedOntheSameDAy = true;
                
                $sampleclassstart = self::checkTeacherTimeConflict($teacherDaySched,$sampleclassstart);

                //check if teacher is asssigned with the same section and subject
                $teacherAssigned = DB::table('assignsubj')
                            ->leftJoin('assignsubjdetail',function($join) use($teacherId,$subject){
                                $join->on('assignsubj.id','=','assignsubjdetail.headerid');
                                $join->where('assignsubjdetail.teacherid',$teacherId);
                                $join->where('assignsubjdetail.subjid',$subject);
                                $join->where('assignsubjdetail.deleted','0');
                            })
                            ->join('sy',function($join){
                                $join->on('sy.id','=    ','assignsubj.syid');
                                $join->where('sy.isactive','1');
                            })
                            ->where('assignsubj.deleted','0')
                            ->where('assignsubj.sectionid',$item->id)
                            ->get();

                if(count($teacherAssigned)>0){

                    if($teacherAssigned[0]->teacherid==null && $teacherAssigned[0]->subjid!=null){
                        $sectionIsNotAssignedWithSameSubject = false;
                    }

                    else{

                        $sectionDaySched = DB::table('classsched')
                                    ->join('classscheddetail',function($join) use($day){
                                        $join->on('classsched.id','=','classscheddetail.headerid');
                                        $join->where('classscheddetail.days',$day);
                                        $join->where('classscheddetail.deleted','0');
                                    })
                                    ->join('sy',function($join){
                                        $join->on('sy.id','=','classsched.syid');
                                        $join->where('sy.isactive','1');
                                    })
                                    ->where('subjid',$subject)
                                    ->where('sectionid',$item->id)
                                    ->where('classsched.deleted','0')
                                    ->count();

                        if( $sectionDaySched > 0){
                            $subjectIsAssignedOntheSameDAy = false;
                        }
                    }
                }

                if($sectionIsNotAssignedWithSameSubject && $subjectIsAssignedOntheSameDAy){

                    while($sectionSchedisConflict && $sampleclassstart < $samlpleendtime ){

                        $classEnd = Carbon::create($sampleclassstart)->addHour($hour)->addMinutes($minutes)->isoFormat('HH:mm:ss');

                        $timeIsAvailableInTheSection = false;

                        $classssched = DB::table('classsched')
                                    ->join('classscheddetail',function($join) use($day,$sampleclassstart){
                                        $join->on('classsched.id','=','classscheddetail.headerid');
                                        $join->where('classscheddetail.days',$day);
                                        $join->where('stime',$sampleclassstart);
                                        $join->where('classscheddetail.deleted','0');
                                    })
                                    ->join('sy',function($join){
                                        $join->on('sy.id','=','classsched.syid');
                                        $join->where('sy.isactive','1');
                                    })
                                    ->select(
                                        'classscheddetail.stime') 
                                    ->where('subjid',$subject)
                                    ->where('sectionid',$item->id)
                                    ->where('classsched.deleted','0')
                                    ->count();

                        if($classssched==0){

                            $sectionDaySched = DB::table('classsched')
                                ->join('classscheddetail',function($join) use($day,$sampleclassstart){
                                    $join->on('classsched.id','=','classscheddetail.headerid');
                                    $join->where('classscheddetail.days',$day);
                                    $join->where('stime',$sampleclassstart);
                                    $join->where('classscheddetail.deleted','0');
                                })
                                ->join('sy',function($join){
                                    $join->on('sy.id','=','classsched.syid');
                                    $join->where('sy.isactive','1');
                                })
                                ->select(
                                    'classscheddetail.stime') 
                                ->where('sectionid',$item->id)
                                ->where('classsched.deleted','0')
                                ->count();

                            if($sectionDaySched==0){
                                $timeIsAvailableInTheSection = true;
                            }

                        }

                        if($sectionDaySched==0){
                            
                            $teacherDaySched->push((object)[
                                    'subjdesc'=>$subjectInfo->subjdesc,
                                    'stime'=>$sampleclassstart,
                                    'etime'=>$classEnd]);

                            $teacherDaySched = collect($teacherDaySched)->sortBy('stime');

                            $matchingSched = true;
                 
                            foreach($schedsuggestion as $sugsched){
                                
                                if($sugsched->stime == $sampleclassstart
                                    && $sugsched->etime == $classEnd
                                    && $sugsched->sectionname == $item->sectionname
                                    && $sugsched->levelname == $item->levelname
                                ){
                                    $sugsched->day.='/ '.$dayInWord;
                                    $matchingSched = false;
                                    break;
                                }
                                
                            }

                            if($matchingSched){

                                array_push($schedsuggestion ,(object) [
                                    'section'=>$item->id,
                                    'sectionname'=>$item->sectionname,
                                    'levelname'=>$item->levelname,
                                    'stime'=>$sampleclassstart,
                                    'etime'=>$classEnd,
                                    'day'=>$dayInWord,
                                    'subject'=>$subjectInfo->subjdesc
                                    ]);
                            }

                            $sectionSchedisConflict = false;
                        }

                        else{

                            $sampleclassstart = Carbon::create($sampleclassstart)->addHour($hour)->addMinutes($minutes)->isoFormat('HH:mm:ss');

                            $sampleclassstart = self::checkTeacherTimeConflict($teacherDaySched,$sampleclassstart);
                        }
                    }
                }

            }

            $teacherDaySched = collect($teacherDaySched)->sortBy('stime');

        }

        $dataString = '';

        foreach($schedsuggestion as $key=>$item){
            $dataString.='<tr class="'.str_replace('ST. ','',$item->sectionname).'">
                        <td class="pr-0">
                        <div class="form-group">
                            <div class="icheck-primary d-inline">
                                <input class="'.str_replace('ST. ','',$item->sectionname).'" type="checkbox" id="'.$key.'" name="suggested[]" value="'.$item->section.'" checked="">
                                <label for="'.$key.'">
                                </label>
                            </div>
                        </div>
                        </td>
                        <td class="'.str_replace('ST. ','',$item->sectionname).'">'.$item->levelname.' - '.$item->sectionname.'</td>'.
                        '<td class="'.str_replace('ST. ','',$item->sectionname).' day">'.$item->day.'</td>'.
                        '<td class="'.str_replace('ST. ','',$item->sectionname).' time">'.Carbon::create($item->stime)->isoFormat('hh:mm a').' - '.Carbon::create($item->etime)->isoFormat('hh:mm a').'</td></tr>';
                       
        }

        if($dataString==''){
            return  '<td colspan="4" class="text-center">Dates are conflict</td>';
        }
        else{
            return  $dataString;
        }
 
    }
}
