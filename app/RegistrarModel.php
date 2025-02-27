<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class RegistrarModel extends Model
{
    public static function idprefix($acadid, $studid)
    {
        $prefix = db::table('idprefix')->first()->prefix;
        $id = sprintf('%05d', $studid);
        $year = date_create(RegistrarModel::getServerDateTime());
        $year = date_format($year, 'y');

        return $prefix . $acadid . $year . $id;
    }

    public static function getSYID()
    {
        $sy = DB::table('sy')
                ->select('id')
                ->where('isactive', 1)
                ->get();

        return $sy[0]->id;
    }

    public static function getSY()
    {
        $sy = db::table('sy')
            ->orderBy('sydesc', 'DESC')
            ->get();

        return $sy;
    }

    public static function getServerDateTime()
    {
        // $serverDateTime = db::select('SELECT CURRENT_TIMESTAMP');

        // return $serverDateTime[0]->CURRENT_TIMESTAMP;

      return Carbon::now('Asia/Manila');
    }

    public static function loadtrack()
    {
        $loadtrack = db::table('sh_track')
                ->where('deleted', 0)
                ->get();

        return $loadtrack;
    }

    public static function getSemID()
    {
        $semID = db::table('semester')
                ->where('isactive', 1)
                ->first();
        return $semID->id;
    }

    public static function getSem()
    {
        $sem = db::table('semester')
            ->where('deleted', 0)
            ->get();
        return $sem;
    }

    public static function loadGradeLevel($levelid)
    {
      $glevel = db::table('gradelevel')
          ->where('deleted', 0)
          ->orderBy('sortid', 'ASC')
          ->get();

      $glevelList = '<option value="0" selected="">Grade Level</option>';
      
      if($levelid == 0)
      {
        foreach($glevel as $level)
        {
          $glevelList .='
            <option value="'.$level->id.'">'.$level->levelname.'</option>
          ';
        }
      }
      else
      {
        foreach($glevel as $level)
        {
          
          if($level->id == $levelid)
          {
            $glevelList .='
              <option selected value="'.$level->id.'">'.$level->levelname.'</option>
            ';
          }
          else
          {
           $glevelList .='
              <option value="'.$level->id.'">'.$level->levelname.'</option>
            '; 
          }
        } 
      }

      return $glevelList;
    }

    public static function loadSY($active)
    {
      $syList = db::table('sy')
          ->get();


      $syearList = '';
      foreach($syList as $sList)
      {
        if($active == $sList->id)
        {
          $syearList .='
            <option selected value="'.$sList->id.'">'.$sList->sydesc.'</option>
          ';
        }
        else
        {
          $syearList .='
            <option value="'.$sList->id.'">'.$sList->sydesc.'</option>
          ';
        }
      }

      return $syearList;
    }

    public static function loadSEM($active, $levelid)
    {
      $semList = db::table('semester')
          ->get();

      

      if($levelid == 14 || $levelid == 15)
      {
        $slist = '<option value="0">Semester</option>';
        foreach($semList as $list)
        {

          if($active == $slist->id)
          {
            $slist .='
              <option selected value="'.$list->id.'">'.$list->semester.'</option>
            ';
          }
          else
          {
            $slist .='
              <option value="'.$list->id.'">'.$list->semester.'</option>
            ';
          }
        }
      }
      else
      {
        $slist = '<option selected value="0">Semester</option>';
        foreach($semList as $list)
        {
         
          $slist .='
            <option value="'.$list->id.'">'.$list->semester.'</option>
          ';
        
        } 
      }
      return $slist;
    }

    public static function loadcourses()
    {
      $courses = db::table('college_courses')
          ->where('deleted', 0)
          ->get();

      return $courses;

    }

    public static function loadcollegeSection($courseid)
    {
      $section = db::table('college_sections')
          ->where('deleted', 0)
          ->where('courseID', $courseid)
          ->get();
          
      return $section;
    }

    public static function activeacadprog()
    {
      $acadprog = db::table('teacher')
        ->select('acadprogid')
        ->join('users', 'teacher.userid', '=', 'users.id')
        ->join('teacheracadprog', 'teacher.id', '=', 'teacheracadprog.teacherid')
        ->where('users.id', auth()->user()->id)
        ->where('teacheracadprog.deleted', 0)
        ->get();

      $in = array();
      foreach($acadprog as $prog)
      {
        array_push($in, $prog->acadprogid);
      }

      return $in;
    }
    
    public static function getshblock($sectionid, $strandid)
    {
      $blocks = db::table('sh_sectionblockassignment')
        ->select('sh_sectionblockassignment.*', 'blockname')
        ->join('sh_block', 'sh_sectionblockassignment.blockid', '=', 'sh_block.id')
        ->where('sectionid', $sectionid)
        ->where('strandid', $strandid)
        ->where('sh_sectionblockassignment.deleted', 0)
        ->where('sh_block.deleted', 0)
        ->get();

      return $blocks;
    }
    
    public static function checkDP($studid, $levelid)
    {
        $stud = db::table('studinfo')
            ->where('id', $studid)
            ->first();

        $dp = 0;
        $ok = 0;

        $gradelevel = db::table('gradelevel')
            ->select('gradelevel.id', 'nodp', 'esc', 'voucher')
            ->where('gradelevel.id', $levelid)
            ->first();



        if($gradelevel->nodp == 1)
        {   
            $ok = 1;
            $dp = 0;
        }
        elseif($stud->nodp == 1)
        {
            $ok = 1;
            $dp = 0;
        }
        elseif($gradelevel->esc == 1)
        {

            if($stud->grantee == 2)
            {
                $ok = 1;
                $dp = 0;
            }
            else
            {
                $ok = 0;
            }
        }
        elseif($gradelevel->voucher == 1)
        {
            if($stud->grantee == 3)
            {
                $ok = 1;
                $dp = 0;    
            }
            else
            {
                $ok = 0;
            }   
        }

        // if($ok == 0)
        // {
            if(db::table('schoolinfo')->first()->cashierversion == 1) //DP on cashierversion 1
            {
                $getdp = db::table('chrngtransdetail')              
                        ->select('studid', 'chrngtrans.syid', 'chrngtrans.semid', 'itemkind', 'payschedid', 'items.isdp')
                        ->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
                        ->join('items', 'chrngtransdetail.payschedid', '=', 'items.id')
                        ->where('studid', $studid)
                        ->where('chrngtrans.syid', RegistrarModel::getSYID())
                        ->where(function($q) use($levelid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if(db::table('schoolinfo')->first()->shssetup == 0)
                                {
                                    $q->where('chrngtrans.semid', RegistrarModel::getSemID());
                                }
                            }
                            if($levelid >= 17 && $levelid <= 20)
                            {
                                $q->where('chrngtrans.semid', RegistrarModel::getSemID());
                            }
                        })
                        ->where('itemkind', 1)
                        ->where('items.isdp', 1)
                        ->where('cancelled', 0)
                        ->get();
            }
            else //DP on cashierversion 2
            {
                $dpsetup = db::table('dpsetup')
                    ->where('levelid', $levelid)
                    ->where('deleted', 0)
                    ->groupBy('classid')
                    ->first();

                $getdp = db::table('studledger')
                    ->select('studledger.studid', 'chrngtransdetail.classid', 'payment as amount', 'chrngtrans.ornum')
                    ->join('chrngtrans', 'studledger.transid', '=', 'chrngtrans.id')
                    ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                    ->where('studledger.studid', $studid)
                    ->where('studledger.deleted', 0)
                    ->where('studledger.void', 0)
                    ->where('studledger.syid', RegistrarModel::getSYID())
                    ->where(function($q) use($levelid){
                        if($levelid == 14 || $levelid == 15)
                        {   
                            if(db::table('schoolinfo')->first()->shssetup == 0)
                            {
                                $q->where('studledger.semid', RegistrarModel::getSemID());
                            }
                        }
                        if($levelid >= 17 && $levelid <= 20)
                        {
                            $q->where('studledger.semid', RegistrarModel::getSemID());
                        }
                    })
                    ->get();
            }

            if(count($getdp) > 0)
            {
                // $ok = 1;
                $dp = 1;
            }
            else
            {
                // $ok = 0;
            }

            return $dp;
        // }
    }

    public static function checkDPv2($studid, $levelid, $syid, $semid)
    {
        // return $syid . ' ' . $semid;
        $getDP = db::table('studinfo')
            ->select(db::raw('ornum, SUM(chrngtransdetail.`amount`) AS amount, transdate'))
            ->join('chrngtrans', 'studinfo.id', '=', 'chrngtrans.studid')
            ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
            ->where('deleted', 0)
            ->where('cancelled', 0)
            ->where('itemkind', 0)
            ->where('studid', $studid)
            ->where('chrngtrans.syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 20)
                {
                    $q->where('chrngtrans.semid', $semid);
                }
            })
            ->groupBy('ornum')
            ->first();

        if($getDP)
        {
            return 'paid';
        }
        else
        {
            return '';
        }
    
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
                    'password'      => $hashed,
					'isDefault'     => 3
                ]);
        }
        
        return $data;
    }

    public static function ledgeritemizedreset($studid, $syid, $semid)
    {

        $studinfo = db::table('studinfo')
            ->where('id', $studid)
            ->first();

        $levelid = $studinfo->levelid;

        db::table('studledgeritemized')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }

                if($levelid >= 17 && $levelid <= 20)
                {
                    $q->where('semid', $semid);
                }
            })
            ->delete();


        if($studinfo)
        {
            $levelid = $studinfo->levelid;

            if($studinfo->studstatus != 0)
            {

                if($studinfo->feesid != null)
                {
                    $tuitions = db::table('tuitionheader')
                        ->select('syid', 'semid', 'grantee', 'tuitiondetail.id as detailid', 'tuitionitems.id as tuitionitemid', 'classificationid', 'itemid', 'tuitionitems.amount', 'istuition')
                        ->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
                        ->join('tuitionitems', 'tuitiondetail.id', '=', 'tuitionitems.tuitiondetailid')
                        ->where('tuitionheader.deleted', 0)
                        ->where('tuitiondetail.deleted', 0)
                        ->where('tuitionitems.deleted', 0)
                        ->where('tuitionheader.id', $studinfo->feesid)
                        ->get();          
                }
                else
                {
                    $thead = db::table('tuitionheader')
                        ->where('levelid', $studinfo->levelid)
                        ->where('syid', $syid)
                        ->where(function($q) use($levelid, $semid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if(db::table('schoolinfo')->first()->shssetup == 0)
                                {
                                    $q->where('semid', $semid);
                                }
                            }

                            if($levelid >= 17 && $levelid <= 20)
                            {
                                $q->where('semid', $semid);
                            }
                        })
                        ->first();


                    if($thead)
                    {
                        $tuitions = db::table('tuitionheader')
                            ->select('syid', 'semid', 'grantee', 'tuitiondetail.id as detailid', 'tuitionitems.id as tuitionitemid', 'classificationid', 'itemid', 'tuitionitems.amount', 'istuition')
                            ->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
                            ->join('tuitionitems', 'tuitiondetail.id', '=', 'tuitionitems.tuitiondetailid')
                            ->where('tuitionheader.deleted', 0)
                            ->where('tuitiondetail.deleted', 0)
                            ->where('tuitionitems.deleted', 0)
                            ->where('tuitionheader.id', $thead->id)
                            ->get();
                    }
                }

                foreach($tuitions as $tuition)
                {
                    if($studinfo->levelid >= 17 || $studinfo->levelid <= 20)
                    {
                        $totalunits = db::table('college_studsched')
                            ->select(db::raw('SUM(lecunits) + SUM(labunits) AS totalunits'))
                            ->join('college_classsched', 'college_studsched.schedid', '=', 'college_classsched.id')
                            ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                            ->where('college_studsched.studid', $studid)
                            ->where('college_studsched.deleted', 0)
                            ->where('college_classsched.syID', $syid)
                            ->where(function($q) use($levelid, $semid){
                                if($levelid == 14 || $levelid == 15)
                                {
                                    if(db::table('schoolinfo')->first()->shssetup == 0)
                                    {
                                        $q->where('college_classsched.semesterID', $semid);
                                    }
                                }

                                if($levelid >= 17 && $levelid <= 20)
                                {
                                    $q->where('college_classsched.semesterID', $semid);
                                }
                            })
                            ->first();

                        // $totalunits = db::select('
                        //     SELECT SUM(lecunits) + SUM(labunits) AS totalunits
                        //     FROM college_studsched
                        //     INNER JOIN college_classsched ON college_studsched.`schedid` = college_classsched.`id`
                        //     INNER JOIN college_prospectus ON college_classsched.`subjectID` = college_prospectus.`id`
                        //     WHERE college_studsched.`studid` = ? and college_studsched.`deleted` = 0  
                        // ', [$studinfo->id]);

                        $units = $totalunits->totalunits;

                        if($tuition->istuition == 1)
                        {
                            $totalamount = $units * $tuition->amount;
                        }
                        else
                        {
                            $totalamount = $tuition->amount; 
                        }

                        db::table('studledgeritemized')
                            ->insert([
                                'studid' => $studinfo->id,
                                'syid' => $syid,
                                'semid' => $semid,
                                'tuitiondetailid' => $tuition->detailid,
                                'classificationid' => $tuition->classificationid,
                                'tuitionitemid' => $tuition->tuitionitemid,
                                'itemAmount' => $totalamount,
                                'itemid' => $tuition->itemid,
                                'deleted' => 0
                            ]); 
                    }
                    else
                    {
                        db::table('studledgeritemized')
                            ->insert([
                                'studid' => $studinfo->id,
                                'syid' => $syid,
                                'semid' => $semid,
                                'tuitiondetailid' => $tuition->detailid,
                                'classificationid' => $tuition->classificationid,
                                'tuitionitemid' => $tuition->tuitionitemid,
                                'itemAmount' => $tuition->amount,
                                'itemid' => $tuition->itemid,
                                'deleted' => 0
                          ]); 
                    }
                }

                $balforwardsetup = db::table('balforwardsetup')
                    ->first();


                $studledger = db::table('studledger')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where(function($q) use($levelid, $semid){
                        if($levelid == 14 || $levelid == 15)
                        {
                            if(db::table('schoolinfo')->first()->shssetup == 0)
                            {
                                $q->where('semid', $semid);
                            }
                        }

                        if($levelid >= 17 && $levelid <= 20)
                        {
                            $q->where('semid', $semid);
                        }
                    })
                    ->where('classid', $balforwardsetup->classid)
                    ->where('void', 0)
                    ->where('deleted', 0)
                    ->first();

                if($studledger)
                {
                    db::table('studledgeritemized')
                        ->insert([
                            'studid' => $studid,
                            'syid' => $syid,
                            'semid' => $semid, 
                            'classificationid' => $studledger->classid,
                            'itemamount' => $studledger->amount,
                            'deleted' => 0,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => RegistrarModel::getServerDateTime()
                        ]);
                }

            }
        }
    }

    public static function transitemsreset($studid, $syid, $semid)
    {
        $stud = db::table('studinfo')
            ->where('id', $studid)
            ->first();

        $levelid = $stud->levelid;

        db::table('chrngtransitems')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }

                if($levelid >= 17 && $levelid <= 20)
                {
                    $q->where('semid', $semid);
                }
            })
            ->delete();

        $chrngtrans = db::table('chrngtrans')
            ->select('chrngtransid', 'chrngtransdetail.id as chrngtransdetailid', 'ornum', 'classid', 'chrngtransdetail.amount', 'studid', 'syid', 'semid')
            ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
            ->where('cancelled', 0)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }

                if($levelid >= 17 && $levelid <= 20)
                {
                    $q->where('semid', $semid);
                }
            })
            ->where('studid', $studid)
            ->get();

        $transOR = 0;
        $transstudID = 0;

        foreach($chrngtrans as $trans)
        {

            $transOR = $trans->ornum;
            $transstudID = $trans->studid;

            $transamount = $trans->amount;


            top:
            // return $trans->semid;
            $ledgeritemized = db::table('studledgeritemized')
                ->where('studid', $trans->studid)
                ->where('syid', $trans->syid)
                ->where(function($q) use($levelid, $trans){
                    if($levelid == 14 || $levelid == 15)
                    {
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('semid', $trans->semid);
                        }
                    }

                    if($levelid >= 17 && $levelid <= 20)
                    {
                        $q->where('semid', $trans->semid);
                    }
                })
                ->where('classificationid', $trans->classid)
                ->where('deleted', 0)
                ->where(function($q) {
                    $q->whereColumn('totalamount', '<', 'itemamount')
                        ->orWhere('totalamount', null);
                })
                ->get();

            if(count($ledgeritemized) == 0)
            {
                $ledgeritemized = db::table('studledgeritemized')
                    ->where('studid', $trans->studid)
                    ->where('syid', $trans->syid)
                    ->where(function($q) use($levelid, $semid, $trans){
                        if($levelid == 14 || $levelid == 15)
                        {
                            if(db::table('schoolinfo')->first()->shssetup == 0)
                            {
                                $q->where('semid', $trans->semid);
                            }
                        }

                        if($levelid >= 17 && $levelid <= 20)
                        {
                            $q->where('semid', $trans->semid);
                        }
                    })
                    // ->where('classificationid', $trans->classid)
                    ->where('deleted', 0)
                    ->where(function($q) {
                        $q->whereColumn('totalamount', '<', 'itemamount')
                            ->orWhere('totalamount', null);
                    })
                    ->get();

            }

                // return $ledgeritemized;

            foreach($ledgeritemized as $item)
            {
                // echo ' transamount: ' . $transamount . '; itemid: ' . $item->itemid . ' classid: ' . $trans->classid;

                if($transamount > 0)
                {
                    // echo ' transamount: ' . $transamount . '; itemid: ' . $item->itemid . ' classid: ' . $trans->classid;
                    $checkitem = db::table('studledgeritemized')
                        ->where('id', $item->id)
                        ->first();

                    // echo $checkitem->totalamount . ' < ' . $item->itemamount . '; ';
                    if($checkitem)
                    {
                        if($checkitem->totalamount < $item->itemamount)
                        {
                            $_getamount = $item->itemamount - $item->totalamount;

                            if($transamount >= $_getamount)
                            {
                                db::table('studledgeritemized')
                                    ->where('id', $item->id)
                                    ->update([
                                        'totalamount' => $item->totalamount + $_getamount,
                                        'updatedby' => auth()->user()->id,
                                        'updateddatetime' => RegistrarModel::getServerDateTime()
                                    ]);


                                db::table('chrngtransitems')
                                    ->insert([
                                        'chrngtransid' => $trans->chrngtransid,
                                        'chrngtransdetailid' => $trans->chrngtransdetailid,
                                        'ornum' => $trans->ornum,
                                        'itemid' => $item->itemid,
                                        'classid' => $item->classificationid,
                                        'amount' => $_getamount,
                                        'studid' => $item->studid,
                                        'syid' => $trans->syid,
                                        'semid' => $trans->semid,
                                        'createdby' => auth()->user()->id,
                                        'createddatetime' => RegistrarModel::getServerDateTime()
                                    ]);


                                $transamount -= $_getamount;

                            }
                            else
                            {
                                db::table('studledgeritemized')
                                    ->where('id', $item->id)
                                    ->update([
                                        'totalamount' => $item->totalamount + $transamount,
                                        'updatedby' => auth()->user()->id,
                                        'updateddatetime' => RegistrarModel::getServerDateTime()
                                    ]);

                                db::table('chrngtransitems')
                                    ->insert([
                                    'chrngtransid' => $trans->chrngtransid,
                                    'chrngtransdetailid' => $trans->chrngtransdetailid,
                                    'ornum' => $trans->ornum,
                                    'itemid' => $item->itemid,
                                    'classid' => $item->classificationid,
                                    'amount' => $transamount,
                                    'studid' => $item->studid,
                                    'syid' => $trans->syid,
                                    'semid' => $trans->semid,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => RegistrarModel::getServerDateTime()
                                ]);

                                $transamount = 0;
                            }
              
                        }
                    }
                }
            }

                // return $transamount;

            if($transamount > 0)
            {
                // goto top;

                $chrngtrans = db::table('chrngtrans')
                    ->select('chrngtransid', 'chrngtransdetail.id as chrngtransdetailid', 'ornum', 'classid', 'chrngtransdetail.amount', 'studid', 'syid', 'semid')
                    ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                    ->where('cancelled', 0)
                    ->where('posted', 1)
                    ->where('studid', $transstudID)
                    ->where('ornum', $transOR)
                    ->get();

                foreach($chrngtrans as $trans)
                {
                    if($transamount > 0)
                    {
                        $ledgeritemized = db::select(
                            'SELECT *
                            FROM `studledgeritemized` 
                            WHERE `studid` = ? 
                                AND `syid` = ? 
                                AND `semid` = ? 
                                AND `classificationid` =? 
                                AND `deleted` = 0', [$trans->studid, $trans->syid, $trans->semid, $trans->classid]
                        );

                        $ledgeritemized = db::table('studledgeritemized')
                            ->where('studid', $trans->studid)
                            ->where('syid', $trans->syid)
                            ->where(function($q) use($levelid, $semid){
                                if($levelid == 14 || $levelid == 15)
                                {
                                    if(db::table('schoolinfo')->first()->shssetup == 0)
                                    {
                                        $q->where('semid', $trans->semid);
                                    }
                                }

                                if($levelid >= 17 && $levelid <= 20)
                                {
                                    $q->where('semid', $trans->semid);
                                }
                            })
                            // ->where('classificationid', $trans->classid)
                            ->where('deleted', 0)
                            ->having('totalamount', '<', 'itemamount')
                            ->orHaving('totalamount', null)
                            ->get();

                        foreach($ledgeritemized as $item)
                        {
                            db::table('chrngtransitems')
                                ->insert([
                                    'chrngtransid' => $trans->chrngtransid,
                                    'chrngtransdetailid' => $trans->chrngtransdetailid,
                                    'ornum' => $trans->ornum,
                                    'itemid' => $item->itemid,
                                    'classid' => $item->classificationid,
                                    'amount' => $transamount,
                                    'studid' => $item->studid,
                                    'syid' => $trans->syid,
                                    'semid' => $trans->semid,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => RegistrarModel::getServerDateTime()
                                ]);

                            $transamount = 0;
                        }
                    }
                }
            }
        }

        $chrngtrans1 = db::table('chrng_earlyenrollmentpayment')
            ->select('chrng_earlyenrollmentpayment.chrngtransid', 'chrngtransdetail.id as chrngtransdetailid', 'ornum', 'classid', 'chrngtransdetail.amount', 'chrng_earlyenrollmentpayment.studid', 'chrng_earlyenrollmentpayment.syid', 'chrng_earlyenrollmentpayment.semid')
            ->join('chrngtrans', 'chrng_earlyenrollmentpayment.chrngtransid', '=', 'chrngtrans.id')
            ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
            ->where('chrng_earlyenrollmentpayment.studid', $studid)
            ->where('chrng_earlyenrollmentpayment.syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('chrng_earlyenrollmentpayment.semid', $semid);
                    }
                }

                if($levelid >= 17 && $levelid <= 20)
                {
                    $q->where('chrng_earlyenrollmentpayment.semid', $semid);
                }
            })
            ->get();


        foreach($chrngtrans1 as $trans)
        {

            $transOR = $trans->ornum;
            $transstudID = $trans->studid;

            $transamount = $trans->amount;


            top1:

            $ledgeritemized = db::table('studledgeritemized')
                ->where('studid', $trans->studid)
                ->where('syid', $trans->syid)
                ->where(function($q) use($levelid, $semid){
                    if($levelid == 14 || $levelid == 15)
                    {
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('semid', $trans->semid);
                        }
                    }

                    if($levelid >= 17 && $levelid <= 20)
                    {
                        $q->where('semid', $trans->semid);
                    }
                })
                ->where('classificationid', $trans->classid)
                ->where('deleted', 0)
                ->where(function($q) {
                    $q->whereColumn('totalamount', '<', 'itemamount')
                        ->orWhere('totalamount', null);
                })
                ->get();

            if(count($ledgeritemized) == 0)
            {
                $ledgeritemized = db::table('studledgeritemized')
                ->where('studid', $trans->studid)
                ->where('syid', $trans->syid)
                ->where(function($q) use($levelid, $semid){
                    if($levelid == 14 || $levelid == 15)
                    {
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('semid', $trans->semid);
                        }
                    }

                    if($levelid >= 17 && $levelid <= 20)
                    {
                        $q->where('semid', $trans->semid);
                    }
                })
                // ->where('classificationid', $trans->classid)
                ->where('deleted', 0)
                ->where(function($q) {
                    $q->whereColumn('totalamount', '<', 'itemamount')
                        ->orWhere('totalamount', null);
                })
                ->get();

            }

            // return $ledgeritemized;

            foreach($ledgeritemized as $item)
            {
                // echo ' transamount: ' . $transamount . '; itemid: ' . $item->itemid . ' classid: ' . $trans->classid;

                if($transamount > 0)
                {
                    // echo ' transamount: ' . $transamount . '; itemid: ' . $item->itemid . ' classid: ' . $trans->classid;
                    $checkitem = db::table('studledgeritemized')
                        ->where('id', $item->id)
                        ->first();

                    // echo $checkitem->totalamount . ' < ' . $item->itemamount . '; ';
                    if($checkitem)
                    {
                        if($checkitem->totalamount < $item->itemamount)
                        {
                            $_getamount = $item->itemamount - $item->totalamount;

                            if($transamount >= $_getamount)
                            {
                                db::table('studledgeritemized')
                                    ->where('id', $item->id)
                                    ->update([
                                        'totalamount' => $item->totalamount + $_getamount,
                                        'updatedby' => auth()->user()->id,
                                        'updateddatetime' => RegistrarModel::getServerDateTime()
                                    ]);


                                db::table('chrngtransitems')
                                    ->insert([
                                        'chrngtransid' => $trans->chrngtransid,
                                        'chrngtransdetailid' => $trans->chrngtransdetailid,
                                        'ornum' => $trans->ornum,
                                        'itemid' => $item->itemid,
                                        'classid' => $item->classificationid,
                                        'amount' => $_getamount,
                                        'studid' => $item->studid,
                                        'syid' => $trans->syid,
                                        'semid' => $trans->semid,
                                        'createdby' => auth()->user()->id,
                                        'createddatetime' => RegistrarModel::getServerDateTime()
                                    ]);


                                $transamount -= $_getamount;

                            }
                            else
                            {
                                db::table('studledgeritemized')
                                    ->where('id', $item->id)
                                    ->update([
                                        'totalamount' => $item->totalamount + $transamount,
                                        'updatedby' => auth()->user()->id,
                                        'updateddatetime' => RegistrarModel::getServerDateTime()
                                    ]);

                                db::table('chrngtransitems')
                                    ->insert([
                                    'chrngtransid' => $trans->chrngtransid,
                                    'chrngtransdetailid' => $trans->chrngtransdetailid,
                                    'ornum' => $trans->ornum,
                                    'itemid' => $item->itemid,
                                    'classid' => $item->classificationid,
                                    'amount' => $transamount,
                                    'studid' => $item->studid,
                                    'syid' => $trans->syid,
                                    'semid' => $trans->semid,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => RegistrarModel::getServerDateTime()
                                ]);

                                $transamount = 0;
                            }
              
                        }
                    }
                }
            }

                // return $transamount;

            if($transamount > 0)
            {
                // goto top;

                $chrngtrans = db::table('chrngtrans')
                    ->select('chrngtransid', 'chrngtransdetail.id as chrngtransdetailid', 'ornum', 'classid', 'chrngtransdetail.amount', 'studid', 'syid', 'semid')
                    ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                    ->where('cancelled', 0)
                    ->where('posted', 1)
                    ->where('studid', $transstudID)
                    ->where('ornum', $transOR)
                    ->get();

                foreach($chrngtrans as $trans)
                {
                    if($transamount > 0)
                    {
                        $ledgeritemized = db::select(
                            'SELECT *
                            FROM `studledgeritemized` 
                            WHERE `studid` = ? 
                                AND `syid` = ? 
                                AND `semid` = ? 
                                AND `classificationid` =? 
                                AND `deleted` = 0', [$trans->studid, $trans->syid, $trans->semid, $trans->classid]
                        );

                        $ledgeritemized = db::table('studledgeritemized')
                            ->where('studid', $trans->studid)
                            ->where('syid', $trans->syid)
                            ->where(function($q) use($levelid, $semid){
                                if($levelid == 14 || $levelid == 15)
                                {
                                    if(db::table('schoolinfo')->first()->shssetup == 0)
                                    {
                                        $q->where('semid', $trans->semid);
                                    }
                                }

                                if($levelid >= 17 && $levelid <= 20)
                                {
                                    $q->where('semid', $trans->semid);
                                }
                            })
                            // ->where('classificationid', $trans->classid)
                            ->where('deleted', 0)
                            ->having('totalamount', '<', 'itemamount')
                            ->orHaving('totalamount', null)
                            ->get();

                        foreach($ledgeritemized as $item)
                        {
                            db::table('chrngtransitems')
                                ->insert([
                                    'chrngtransid' => $trans->chrngtransid,
                                    'chrngtransdetailid' => $trans->chrngtransdetailid,
                                    'ornum' => $trans->ornum,
                                    'itemid' => $item->itemid,
                                    'classid' => $item->classificationid,
                                    'amount' => $transamount,
                                    'studid' => $item->studid,
                                    'syid' => $trans->syid,
                                    'semid' => $trans->semid,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => RegistrarModel::getServerDateTime()
                                ]);

                            $transamount = 0;
                        }
                    }
                }
            }
        }


    }

    public static function chrngdistlogs($studid, $transid, $transdetailid, $scheddetailid, $classid, $amount)
    {
        db::table('chrngdistlogs')
            ->insert([
                'studid' => $studid,
                'transid' => $transid,
                'transdetailid' => $transdetailid,
                'scheddetailid' => $scheddetailid,
                'classid' => $classid,
                'amount' => $amount,
                'createddatetime' => RegistrarModel::getServerDateTime(),
                'createdby' => auth()->user()->id
            ]);
    }

    public static function checkEE($studid, $syid, $semid)
    {
        // $eesetup = DB::table('chrng_earlyenrollmentsetup')
        //     ->first();

        // $ee = db::table('chrngtrans')
        //     ->select('chrngtransdetail.amount')
        //     ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
        //     ->where('studid', $studid)
        //     ->where('syid', $syid)
        //     ->where('semid', $semid)
        //     ->where('itemkind', 1)
        //     ->where('payschedid', $eesetup->itemid)
        //     ->first();

        $ee = db::table('chrng_earlyenrollmentpayment')
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->where('studid', $studid)
            ->where('deleted', 0)
            ->first();


        if($ee)
        {
            return $ee->amount;
        }
        else
        {
            return 0;
        }
    }

    public static function procItemized($studid, $classid, $amount, $syid, $semid)
    {

        $setup = db::table('chrngsetup')
            ->where('classid', $classid)
            ->first();

        $itemized = db::table('studledgeritemized')
            ->where('studid', $studid)
            ->where('classificationid', $classid)
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->where('deleted', 0)
            ->whereColumn('totalamount', '<', 'itemamount')
            ->get();

        if(count($itemized) == 0)
        {
            $itemized = db::table('studledgeritemized')
                ->where('studid', $studid)
                // ->where('classificationid', $classid)
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->where('deleted', 0)
                ->whereColumn('totalamount', '<', 'itemamount')
                ->get();
        }

        $iAmount = $amount;

        foreach($itemized as $item)
        {
            if($iAmount > 0)
            {
                $bal = $item->itemamount - $item->totalamount;

                if($iAmount > $bal)
                {
                    db::table('studledgeritemized')
                        ->where('id', $item->id)
                        ->update([
                            'totalamount' => $item->totalamount + $bal,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => RegistrarModel::getServerDateTime()
                        ]);

                    $iAmount -= $bal;
                }
                else
                {
                    db::table('studledgeritemized')
                        ->where('id', $item->id)
                        ->update([
                            'totalamount' => $item->totalamount + $iAmount,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => RegistrarModel::getServerDateTime()
                        ]);

                    $iAmount = 0;
                }
            }
        }

        if($iAmount > 0)
        {
            $itemized = db::table('studledgeritemized')
                ->where('studid', $studid)
                // ->where('classificationid', $classid)
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->where('deleted', 0)
                ->whereColumn('totalamount', '<', 'itemamount')
                ->get();

            foreach($itemized as $item)
            {
                if($iAmount > 0)
                {
                    $bal = $item->itemamount - $item->totalamount;

                    if($iAmount > $bal)
                    {
                        db::table('studledgeritemized')
                            ->where('id', $item->id)
                            ->update([
                                'totalamount' => $item->totalamount + $bal,
                                'updatedby' => auth()->user()->id,
                                'updateddatetime' => RegistrarModel::getServerDateTime()
                            ]);

                        $iAmount -= $bal;
                    }
                    else
                    {
                        db::table('studledgeritemized')
                            ->where('id', $item->id)
                            ->update([
                                'totalamount' => $item->totalamount + $iAmount,
                                'updatedby' => auth()->user()->id,
                                'updateddatetime' => RegistrarModel::getServerDateTime()
                            ]);

                        $iAmount = 0;
                    }
                }
            }
        }
    }

    public static function shssetup()
    {
        $setup = db::table('schoolinfo')
            ->first()
            ->shssetup;

        return $setup;
    }
	
	public static function enrollmentsmsnotification($studid, $syid, $semid)
    {
        $substr = '';

        $studinfo = db::table('studinfo')
                ->where('id', $studid)
                ->first();

        $sname = $studinfo->firstname . ' ' . $studinfo->middlename . ' ' . $studinfo->lastname . ' ' . $studinfo->suffix;


        if($studinfo->ismothernum == 1)
        {
            $pname = $studinfo->mothername;

            if($pname == '')
            {
                $pname = $sname;
            }
            else
            {
                $pname = $studinfo->mothername;
            }

            $substr = $studinfo->mcontactno;

        }
        elseif($studinfo->isfathernum == 1)
        {
            $pname = $studinfo->fathername;

            if($pname == '')
            {
                $pname = $sname;
            }
            else
            {
                $pname = $studinfo->fathername; 
            }
            
            $substr = $studinfo->fcontactno;
        }
        elseif($studinfo->isguardannum == 1)
        {
            $pname = $studinfo->guardianname;
            
            if($pname == '')
            {
                $pname = $sname;
            }
            else
            {
                $pname = $studinfo->guardianname;   
            }
            
            $substr = $studinfo->gcontactno;
        }
        else
        {
            $pname = $sname;
        }

        $contactno = $studinfo->contactno;

        $schoolinfo = db::table('schoolinfo')
            ->first();

        $abbr = $schoolinfo->abbreviation;
        

        $_sy = db::table('sy')
            ->where('id', $syid)
            ->first()
            ->sydesc;

        if(substr($contactno, 0,1)=='0')
        {
            $contactno = '+63' . substr($contactno, 1);
        }

        $smsStud = db::table('smsbunker')
            ->insert([
                'message' =>$abbr . ' message: CONGRATULATIONS!' . $studinfo->firstname .' you are officially enrolled for S.Y ' . $_sy,
                'receiver' => $contactno,
                'smsstatus' => 0
            ]);

        if(substr($substr, 0,1)=='0')
        {
            $substr = '+63' . substr($substr, 1);
        }

        $smsParent = db::table('smsbunker')
            ->insert([
                'message' => $abbr . ' message: CONGRATULATIONS! Your student '. $studinfo->firstname .' is now officially enrolled for S.Y ' . $_sy,
                'receiver' => $substr,
                'smsstatus' => 0
            ]);
    }

}
