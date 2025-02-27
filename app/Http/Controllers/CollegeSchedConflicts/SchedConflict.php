<?php

namespace App\Http\Controllers\CollegeSchedConflicts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\DeanControllers\DeanSectionController;

class SchedConflict extends Controller
{

      public function schedconflict_college_instructor(Request $request){
            $teacherid = auth()->user()->id;
            $schedid = $request->get('schedid');
            $room = $request->get('room');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $days = $request->get('day');
     
            $time = explode(" - ", $request->get('time'));
            if(!empty($time[0]) && !empty($time[1])){
            
                  $stime = \Carbon\Carbon::create($time[0])->isoFormat('HH:mm:ss');
                  $etime = \Carbon\Carbon::create($time[1])->isoFormat('HH:mm:ss');

                  $teacherid = (int)$teacherid;

                  $teacher = DB::table('college_classsched')
                  ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
                  ->join('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerID')
                  ->join('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
                  ->join('days', 'college_scheddetail.day', '=', 'days.id')
                  ->leftJoin('college_instructor', function($join) {
                        $join->on('college_classsched.id', '=', 'college_instructor.classschedid')
                              ->where('college_instructor.deleted', 0);
                  })
                  ->join('teacher', function($join) use ($teacherid) {
                        $join->on('college_instructor.teacherid', '=', 'teacher.id');
                        $join->where('teacher.deleted', 0);
                        $join->where('teacher.userid', $teacherid);
                        })
                  ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                  ->join('college_subjects', 'college_prospectus.subjectID', '=', 'college_subjects.id')
                  ->where('college_instructor.deleted', 0)
                  ->where('college_classsched.id', '<>', $schedid)
                  ->where('college_classsched.syid', $syid)
                  ->where('college_classsched.semesterid', $semid)
                  ->where('college_classsched.deleted', 0)
                  ->select(
                        'college_sections.sectionDesc',
                        'college_classsched.id as schedid',
                        'college_subjects.subjDesc',
                        'college_subjects.subjCode',
                        'college_scheddetail.day',
                        'rooms.roomname',
                        'college_scheddetail.stime',
                        'college_scheddetail.etime',
                        'days.description as dayname',
                        DB::raw("CONCAT(teacher.firstname, ' ', IFNULL(teacher.middlename, ''), ' ', teacher.lastname) AS teachername"),

                  )
                  ->get();
                  $conflict_list = array();

                  foreach ($teacher as $item) {

                              foreach($days as $day){
                                    if($item->day == $day){
                                          if ($stime >= $item->stime && $stime <= $item->etime) {
                                                if ($stime != $item->etime) {
                                                      array_push($conflict_list, $item);
                                                }
                                          } else if ($etime >= $item->stime && $etime <= $item->etime) {
                                                if ($etime != $item->stime) {
                                                      array_push($conflict_list, $item);
                                                }
                                          } else if ($item->stime >= $stime && $item->etime <= $etime) {
                                                array_push($conflict_list, $item);
                                          }
                                    }
                              }
                        }


                  $schedule = DB::table('college_classsched')
                  ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
                  ->join('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerID')
                  ->join('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
                  ->join('days', 'college_scheddetail.day', '=', 'days.id')
                  ->join('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
                  ->join('teacher', 'college_instructor.teacherid', '=', 'teacher.id')
                  ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                  ->join('college_subjects', 'college_prospectus.subjectID', '=', 'college_subjects.id')
                  ->where('college_instructor.deleted', 0)
                  ->where('college_classsched.id', '<>', $schedid)
                  ->where('college_classsched.syid', $syid)
                  ->where('college_classsched.semesterid', $semid)
                  ->where('college_classsched.deleted', 0)
                  ->select(
                        'college_sections.sectionDesc',
                        'college_classsched.id as schedid',
                        'college_subjects.subjDesc',
                        'college_subjects.subjCode',
                        'college_scheddetail.day',
                        'rooms.roomname',
                        'college_scheddetail.stime',
                        'college_scheddetail.etime',
                        'days.description as dayname',
                        DB::raw("CONCAT(teacher.firstname, ' ', IFNULL(teacher.middlename, ''), ' ', teacher.lastname) AS teachername"),

                  )
                  ->get();
                  

                  foreach ($schedule as $item) {
                        foreach($days as $day){
                              if($item->day == $day){
                                    if($item->roomname == $room){

                                          if ($stime >= $item->stime && $stime <= $item->etime) {
                                                if ($stime != $item->etime) {
                                                      array_push($conflict_list, $item);
                                                }
                                          } else if ($etime >= $item->stime && $etime <= $item->etime) {
                                                if ($etime != $item->stime) {
                                                      array_push($conflict_list, $item);
                                                }
                                          } else if ($item->stime >= $stime && $item->etime <= $etime) {
                                                array_push($conflict_list, $item);
                                          }
                                    }
                                    
                        }
                        
                        }
                  }

                  // foreach ($section as $item) {
                  //       foreach($days as $day){
                  //             if($item->day == $day){
                  //                   if($item->roomname == $room){
                  //                         array_push($conflict_list, $item);
                  //                   }
                  //            }
                        
                  //       }
                  // }
                  
                  
                  if(!empty($conflict_list)){
                        foreach($conflict_list as $item){
                        $item->stime = \Carbon\Carbon::createFromFormat('H:i:s', $item->stime)->format('g:i A');
                        $item->etime = \Carbon\Carbon::createFromFormat('H:i:s', $item->etime)->format('g:i A');
                        }
                        $combined = collect($conflict_list)
                        ->groupBy(function($item) {
                              // Group by fields other than day and description
                              return $item->schedid . '-' .
                              $item->subjDesc . '-' .
                              $item->stime . '-' .
                              $item->etime . '-' .
                              $item->roomname;
                        })
                        ->map(function($group) {
                              // Combine descriptions (days)
                              $days = $group->sortBy('day')->pluck('dayname')->map(function($item) {
                                    return substr($item, 0, 3);
                              })->unique()->join('/');
                              
                              // Take the first item and update its description field
                              $item = $group->first();
                              $item->dayname = $days;
                              return $item;
                        })
                        ->values()
                        ->toArray();

                        return $combined;
                  }

            }
      }


    
      public function teacher_conflict($data){
            $schedid = $data['schedid'];
            $days = $data['day'];
            $stime = $data['stime'];
            $etime = $data['etime'];
            $teachers = $data['instructor'];
            $teacher_conflict = new DeanSectionController();
            $syid = $data['syid'];
            $semid = $data['semester'];

            $conflict_list = array();
            foreach($teachers as $teacher){
                  $teacher = (int)$teacher;
                  
                  $section = DB::table('college_classsched')
                  ->where('college_classsched.id', '<>', $schedid)
                  ->where('college_classsched.deleted',0)
                  ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
                  ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                  ->join('college_subjects', 'college_prospectus.subjectID', '=', 'college_subjects.id')
                  ->join('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerID')
                  ->where('college_scheddetail.deleted',0)
                  // ->leftjoin('rooms', 'college_scheddetail.roomID', '=', 'rooms.id')
                  ->join('days', 'college_scheddetail.day', '=', 'days.id')
                  ->leftJoin('college_instructor', function($join) {
                        $join->on('college_classsched.id', '=', 'college_instructor.classschedid')
                              ->where('college_instructor.deleted', 0);
                  })
                  ->join('teacher', function($join) use ($teacher) {
                        $join->on('college_instructor.teacherid', '=', 'teacher.id');
                        $join->where('teacher.deleted', 0);
                        $join->where('teacher.id', $teacher);
                        })
                  ->where('college_classsched.syid', $syid)
                  ->where('college_classsched.semesterid', $semid)
                  ->select(
                        'college_sections.sectionDesc',
                        'college_subjects.subjDesc',
                        DB::raw("CONCAT(teacher.firstname, ' ', IFNULL(teacher.middlename, ''), ' ', teacher.lastname) AS teachername"),
                        'college_classsched.id as schedid',
                        'college_scheddetail.id as scheddetailid',
                        'college_scheddetail.headerID',
                        'college_scheddetail.stime',
                        'college_scheddetail.etime',
                        'college_scheddetail.day',
                        'college_scheddetail.roomID',
                        // 'rooms.roomname',
                        'days.description',
                        'days.id as dayid',
                        'college_subjects.subjCode',
                        'college_subjects.subjDesc',
                        'teacher.id as teacherid',
                  )
                  ->get();


            
                  foreach ($section as $item) {
                        foreach($days as $day){
                              if($item->day == $day){
                                    if ($stime >= $item->stime && $stime <= $item->etime) {
                                          if ($stime != $item->etime) {
                                                array_push($conflict_list, $item);
                                          }
                                    } else if ($etime >= $item->stime && $etime <= $item->etime) {
                                          if ($etime != $item->stime) {
                                                array_push($conflict_list, $item);
                                          }
                                    } else if ($item->stime >= $stime && $item->etime <= $etime) {
                                          array_push($conflict_list, $item);
                                    }
                              }
                        }
                  }
            }
            


            
            if(!empty($conflict_list)){
                  foreach($conflict_list as $item){
                      $item->stime = \Carbon\Carbon::createFromFormat('H:i:s', $item->stime)->format('g:i A');
                      $item->etime = \Carbon\Carbon::createFromFormat('H:i:s', $item->etime)->format('g:i A');
                  }
                  $combined = collect($conflict_list)
                      ->groupBy(function($item) {
                          // Group by fields other than day and description
                          return $item->schedid . '-' .
                          $item->headerID . '-' .
                          $item->subjDesc . '-' .
                          $item->stime . '-' .
                          $item->etime . '-' .
                          $item->roomID . '-' .
                        //   $item->roomname . '-' . 
                          $item->subjCode;
                      })
                      ->map(function($group) {
                          // Combine descriptions (days)
                          $days = $group->sortBy('dayid')->pluck('description')->map(function($item) {
                              return substr($item, 0, 3);
                          })->unique()->join('/');
                          
                          // Take the first item and update its description field
                          $item = $group->first();
                          $item->description = $days;
                          return $item;
                      })
                      ->values()
                      ->toArray();

                  return $combined;
            }else{
                  return $teacher_conflict->sched_conflict(new Request($data), 1);
            }

      }
}
