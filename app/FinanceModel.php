<?php

namespace App;
use DB;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class FinanceModel extends Model
{
  public static function getSYID()
  {
  	$sy = DB::table('sy')
	      ->select('id')
	      ->where('isactive', 1)
	      ->get();

      return $sy[0]->id;
  }

  public static function getSYDesc()
  {
    $sy = DB::table('sy')
        ->select('id', 'sydesc')
        ->where('isactive', 1)
        ->get();

      return $sy[0]->sydesc;
  }

  public static function getSY()
  {
    $sy = DB::table('sy')
        ->select('id', 'sydesc', 'isactive')
        ->get();   
    return $sy; 
  }

  public static function getServerDateTime()
  {
      // $serverDateTime = db::select('SELECT CURRENT_TIMESTAMP');

      // return $serverDateTime[0]->CURRENT_TIMESTAMP;
    return Carbon::now('Asia/Manila');
  }

  public static function getSemID()
  {
    $semID = db::table('semester')
            ->where('isactive', 1)
            ->first();
    return $semID->id;
  }
  
  public static function getSemDesc()
  {
    $sem = DB::table('semester')
        ->select('id', 'semester')
        ->where('isactive', 1)
        ->first();

      return $sem->semester;
  }

  public static function getSem()
  {
      $sem = db::table('semester')
          ->get();
      return $sem;
  }

  public static function FCPayTotal($headerid)
  {
    $payTotal = db::select('select SUM(amount) as paytotal from tuitiondetail where headerid = ? and deleted = 0', [$headerid]);
    return number_format($payTotal[0]->paytotal, 2);
  }


  public static function getFCItemList($detailID)
  {
    $items = db::table('tuitionitems')
        ->select('tuitionitems.id', 'items.description', 'tuitionitems.amount', 'tuitionitems.itemid')
        ->join('items', 'tuitionitems.itemid', '=', 'items.id')
        ->where('tuitiondetailid', $detailID)
        ->where('tuitionitems.deleted', 0)
        ->get();

    $itemLayout = '';
    
    if(count($items) > 0)
    {
      foreach($items as $item)
      {
        $itemLayout .= '
          <tr>
            <td>'.$item->description.'</td>
            <td class="text-right">'.number_format($item->amount, 2).'</td>
            <td style="width:10px;">
              <button class="btn btn-primary btn-sm btnitemedit" data-id="'.$item->id.'" item-id="'.$item->itemid.'" item-amount="'.$item->amount.'" data-toggle="modal" data-target="#modal-item-edit">
                <i class="fas fa-edit"></i>
              </button>
            </td>
            <td style="width:10px;">
              <button class="btn btn-danger btn-sm btnitemdelete" data-id="'.$item->id.'"><i class="fas fa-trash"></i>    
            </td>
          </tr>
        ';
      }
    }
    else
    {
      $itemLayout .='
        <tr></tr>
      ';
    }

    return $itemLayout;

  }

  public static function FCItemTotal($detailID)
  {
    $itemTotal = db::select('select SUM(amount) as itemtotal from tuitionitems where tuitiondetailid = ? and deleted = 0', [$detailID]);

    if($itemTotal[0]->itemtotal > 0)
    {
      return number_format($itemTotal[0]->itemtotal, 2);
    }
    else
    {
      return '0.00';
    }
  }

  public static function getFCPayClassList($headerid)
  {

    $tuitiondetail = db::table('tuitiondetail')
          ->select('tuitiondetail.id', 'classificationid', 'pschemeid', 'itemclassification.description', 'paymentsetup.paymentdesc', 'tuitiondetail.amount', 'tuitiondetail.isdp')
          ->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
          ->join('paymentsetup', 'tuitiondetail.pschemeid', '=', 'paymentsetup.id')
          ->where('headerid', $headerid)
          ->where('tuitiondetail.deleted', 0)
          ->get();

    $tDetail = '';
    $PayClassAMount = 0;
    foreach($tuitiondetail as $detail)
    {

      $PayClassAMount += $detail->amount;
      $tDetail .= '
        <tr class="payClass" data-id="'.$detail->id.'">
          <td class="descval">'.$detail->description.'</td>
          <td class="">'.$detail->paymentdesc.'</td>
          <td id="payclass-'.$detail->id.'" class="text-right">'.number_format($detail->amount, 2).'</td>
          <td style="width:5px">
            <button class="btn btn-primary btn-sm btnpayedit" data-id="'.$detail->id.'" data-desc="'.$detail->classificationid.'" data-mop="'.$detail->pschemeid.'" data-toggle="modal" data-dp="'.$detail->isdp.'" data-target="#modal-pay-edit">
              <i class="fas fa-edit"></i>
            </button>    
          </td>
          <td style="width:5px">
            <button class="btn btn-danger btn-sm btnpaydelete" data-id="'.$detail->id.'"><i class="fas fa-trash"></i>    
          </td>
        </tr>
      ';
    }

    $PayClassData = array(
      'tDetail' => $tDetail,
      'pAmount' => number_format($PayClassAMount, 2)
    );

    return $PayClassData;
  }

  public static function loadDiscountTemplate()
  {
    $discounts = db::table('discounts')
        ->where('deleted', 0)
        ->get();

    return $discounts;
  }

  public static function loadMOP()
  {
    $mop = db::table('paymentsetup')
        ->where('deleted', 0)
        ->get();

    return $mop;
  }

  public static function loadClassification($studid)
  {
    $studinfo = db::table('studinfo')
        ->where('id', $studid)
        ->first();

    $levelid = $studinfo->levelid;

    if($levelid == 14 || $levelid == 15)
    {
      $classification = db::table('tuitionheader')
          ->select('itemclassification.id', 'levelid', 'classificationid', 'itemclassification.description', 'pschemeid')
          ->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
          ->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
          ->where('tuitionheader.levelid', $levelid)
          ->where('syid', FinanceModel::getSYID())
          ->where('semid', FinanceModel::getSemID())
          ->where('tuitionheader.deleted', 0)
          ->where('tuitiondetail.deleted', 0)
          ->get();
    }
    else
    {
      $classification = db::table('tuitionheader')
          ->select('itemclassification.id', 'levelid', 'classificationid', 'itemclassification.description', 'pschemeid')
          ->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
          ->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
          ->where('tuitionheader.levelid', $levelid)
          ->where('syid', FinanceModel::getSYID())
          ->where('tuitionheader.deleted', 0)
          ->where('tuitiondetail.deleted', 0)
          ->get();
    }

    return $classification;
  }

  public static function loadGlevel()
  {
    $levelList = db::table('gradelevel')
        ->where('deleted', 0)
        ->orderBy('sortid', 'ASC')
        ->get();

    return $levelList;
  }

  public static function loadstud()
  {
    $studinfo = db::table('studinfo')
        ->select('studinfo.id as studid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname')
        ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
        ->where('studinfo.deleted', 0)
        ->orderBy('lastname', 'ASC')
        ->orderBy('firstname', 'ASC')
        ->get();

    $list = '';
    if(count($studinfo) > 0)
    {
      foreach($studinfo as $stud)
      {
        $studname = $stud->lastname . ', ' . $stud->firstname . ' ' . $stud->middlename . ' ' . $stud->suffix . ' - ' . $stud->levelname;

        $list .= '
          <option value ="'.$stud->studid.'">'.$studname.'</option>
        ';
      }

      return $list;
    }
  }

  public static function loadItemClass()
  {
    $classification = db::table('itemclassification')
        ->where('deleted', 0)
        ->get();

    return $classification;
  }

  public static function expenseitems()
  {
    $items = db::table('items')
        ->where('isexpense', 1)
        ->where('deleted', 0)
        ->orderBy('description')
        ->get();

    return $items;
  }

  public static function receivableitems()
  {
    $items = db::table('items')
        ->where('isreceivable', 1)
        ->where('deleted', 0)
        ->orderBy('description')
        ->get();

    return $items;
  }

  public static function users()
  {
    $users = db::table('users')
        ->select('users.*', 'hr_school_department.department')
        ->join('usertype', 'users.type', '=', 'usertype.id')
        ->join('hr_school_department', 'usertype.departmentid', '=', 'hr_school_department.id')
        ->where('type', '!=', 7)
        ->where('type', '!=', 9)
        ->where('type', '!=', 6)
        ->where('type', '!=', 12)
        ->where('users.deleted', 0)
        ->orderBy('name')
        ->get();

    return $users;
  }

  public static function countOnlinePayment()
  {
    $countOpayment = db::table('onlinepayments')  
      ->select('studinfo.id', 'sid')
      ->join('studinfo', 'onlinepayments.queingcode', '=', 'studinfo.sid')
      ->where('isapproved', 0)
      ->get();

    return count($countOpayment);
  }
  public static function countPendingRateElevationRequests()
  {
    $rateelevation = Db::table('hr_rateelevation')
      ->where('hr_rateelevation.deleted','0')
      ->where('hr_rateelevation.newsalary','!=',DB::raw('hr_rateelevation.oldsalary'))
      ->where('hr_rateelevation.status','0')
      ->get();

    return count($rateelevation);
  }

  public static function strandlist()
  {
    $strand = db::table('sh_strand')
        ->where('deleted', 0)
        ->get();

    return $strand;
  }

  public static function checksetup($levelid)
  {
    $stat = 0;
    $tuitionheader = db::table('tuitionheader')
        ->where('levelid', $levelid)
        ->where('deleted', 0)
        ->where('syid', FinanceModel::getSYID())
        ->where(function($q) use($levelid){
          if($levelid == 14 || $levelid == 15)
          {
            if(db::table('schoolinfo')->first()->shssetup == 0)
            {
              $q->where('semid', FinanceModel::getSemID());
            }
          }
          if($levelid >= 17 && $levelid <= 20)
          {
            $q->where('semid', FinanceModel::getSemID());
          }
        })
        ->get();

    return $tuitionheader;

  }

  public static function paymenttype()
  {
    $paymenttype = db::table('paymenttype')
        ->get();

    return $paymenttype;
  }

  public static function getCashTerminals()
  {
    $terminals = db::table('chrngterminals')
      ->select('chrngterminals.id', 'description', 'name')
      ->leftjoin('users', 'chrngterminals.owner', '=', 'users.id')
      ->get();

    return $terminals;
  }

  public static function getCOAGroup()
  {
    $coagroup = db::table('acc_coagroup')
        ->where('deleted', 0)
        ->get();

    return $coagroup;
  }

  public static function loadElevatedUser($userid)
  {
    $permission = db::table('chrngpermission')
      ->where('userid', $userid)
      ->get();

    return $permission;
  }

  public static function loadCourses()
  {
    $courses = db::table('college_courses')
      ->where('deleted', 0)
      ->get();

    return $courses;
  }

  public static function loadCOA()
  {
    $coa = db::table('acc_coa')
      ->where('deleted', 0)
      ->orderBy('code')
      ->get();

    return $coa;
  }

  public static function FCClassList($headid)
  {
    $details = db::table('tuitiondetail')
      ->select('tuitiondetail.id', 'itemclassification.description as classdesc', 'paymentsetup.paymentdesc', 'amount')
      ->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
      ->join('paymentsetup', 'tuitiondetail.pschemeid', '=', 'paymentsetup.id')
      ->where('headerid', $headid)
      ->where('tuitiondetail.deleted', 0)
      ->get();

    return $details;
  }

  // =========Update 06082020=============//

  public static function loadAcadProg()
  {
    $acadprog = db::table('academicprogram')
      ->get();

    return $acadprog;
  }

  public static function loadGrantee()
  {
    $grantee = db::table('grantee')
      ->get();

    return $grantee;
  }

  public static function loadMOL()
  {
    $mol = db::table('modeoflearning')
      ->where('deleted', 0)
      ->get();

    return $mol;
  }

  public static function loadcoaMap()
  {
    $mapping = db::table('acc_map')
      ->where('deleted', 0)
      ->get();

    return $mapping;
  }
  
  public static function shssetup()
  {
      $setup = db::table('schoolinfo')
          ->first()
          ->shssetup;

      return $setup;
  }
  
    public static function reloaddiscounts($studid, $syid, $semid, $levelid)
    {
        $discounts = db::table('studdiscounts')
            // ->select('aaa')
            ->where('deleted', 0)
            ->where('posted', 1)
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    $q->where('semid', $semid);
                }

                if($levelid >= 17 && $levelid <= 20)
                {
                    $q->where('semid', $semid);
                }
            })
            ->get();

        foreach($discounts as $discount)
        {
            $ledger = db::table('studledger')
                ->where('studid', $studid)
                ->where('syid', $syid)
                ->where('deleted', 0)
                ->where('particulars', 'like', '%DISCOUNT:%')
                // ->where('ornum', $discount->id)
                ->where(function($q) use($levelid, $semid){
                    if($levelid == 14 || $levelid == 15)
                    {
                        $q->where('semid', $semid);
                    }

                    if($levelid >= 17 && $levelid <= 20)
                    {
                        $q->where('semid', $semid);
                    }
                })
                ->first();


            if($ledger)
            {
                $payscheddetail = db::table('studpayscheddetail')
                    ->where('studid', $studid)
                    ->where('deleted', 0)
                    ->where('classid', $discount->classid)
                    ->where('balance', '>', 0)
                    ->where('syid', $syid)
                    ->where(function($q) use($levelid, $semid){
                        if($levelid == 14 || $levelid == 15)
                        {
                            $q->where('semid', $semid);
                        }

                        if($levelid >= 17 && $levelid <= 20)
                        {
                            $q->where('semid', $semid);
                        }
                    })
                    ->get();

                $disamount = $ledger->payment;

                foreach($payscheddetail as $paysched)
                {
                    if($disamount > 0)
                    {
                        if($disamount > $paysched->balance)
                        {
                            db::table('studpayscheddetail')
                                ->where('id', $paysched->id)
                                ->update([
                                    'amountpay' => $paysched->amountpay + $paysched->balance,
                                    'balance' => 0,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => FinanceModel::getServerDateTime()
                                ]);

                            $disamount -= $paysched->balance;
                        }
                        else
                        {
                            db::table('studpayscheddetail')
                                ->where('id', $paysched->id)
                                ->update([
                                    'amountpay' => $paysched->amountpay + $disamount,
                                    'balance' => $paysched->balance - $disamount,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => FinanceModel::getServerDateTime()
                                ]);

                            $disamount = 0;
                        }
                    }
                }

                if($disamount > 0)
                {
                    $ledger = db::table('studledger')
                        ->where('studid', $studid)
                        ->where('syid', $syid)
                        ->where('deleted', 0)
                        ->where('particulars', 'like', '%DISCOUNT:%')
                        // ->where('ornum', $discount->id)
                        ->where(function($q) use($levelid, $semid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                $q->where('semid', $semid);
                            }

                            if($levelid >= 17 && $levelid <= 20)
                            {
                                $q->where('semid', $semid);
                            }
                        })
                        ->first();

                    $payscheddetail = db::table('studpayscheddetail')
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        //->where('classid', $discount->classid)
                        ->where('balance', '>', 0)
                        ->where('syid', $syid)
                        ->where(function($q) use($levelid, $semid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                $q->where('semid', $semid);
                            }

                            if($levelid >= 17 && $levelid <= 20)
                            {
                                $q->where('semid', $semid);
                            }
                        })
                        ->get();

                    foreach($payscheddetail as $paysched)
                    {
                        if($disamount > 0)
                        {
                            if($disamount > $paysched->balance)
                            {
                                db::table('studpayscheddetail')
                                    ->where('id', $paysched->id)
                                    ->update([
                                        'amountpay' => $paysched->amountpay + $paysched->balance,
                                        'balance' => 0,
                                        'updatedby' => auth()->user()->id,
                                        'updateddatetime' => FinanceModel::getServerDateTime()
                                    ]);

                                $disamount -= $paysched->balance;
                            }
                            else
                            {
                                db::table('studpayscheddetail')
                                    ->where('id', $paysched->id)
                                    ->update([
                                        'amountpay' => $paysched->amountpay + $disamount,
                                        'balance' => $paysched->balance - $disamount,
                                        'updatedby' => auth()->user()->id,
                                        'updateddatetime' => FinanceModel::getServerDateTime()
                                    ]);

                                $disamount = 0;
                            }
                        }
                    }

                }
            }
        }
    }
    
    public static function like_match($pattern, $subject)
    {
        
        $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
        // return $pattern;
        return (bool) preg_match("/^{$pattern}$/i", $subject);
        
    }
    
    public static function ledgeritemizedreset($studid)
    {
        db::table('studledgeritemized')
            ->where('studid', $studid)
            ->where('syid', FinanceModel::getSYID())
            ->where('semid', FinanceModel::getSemID())
            ->update([
              'deleted' => 0
            ]);


        $studinfo = db::table('studinfo')
            ->where('id', $studid)
            ->first();

        if($studinfo)
        {
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
                        ->where('syid', FinanceModel::getSYID())
                        ->where('semid', FinanceModel::getSemID())
                        ->first();



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

                foreach($tuitions as $tuition)
                {
                    if($studinfo->levelid >= 17 || $studinfo->levelid <= 20)
                    {
                        $totalunits = db::select('
                            SELECT SUM(lecunits) + SUM(labunits) AS totalunits
                            FROM college_studsched
                            INNER JOIN college_classsched ON college_studsched.`schedid` = college_classsched.`id`
                            INNER JOIN college_prospectus ON college_classsched.`subjectID` = college_prospectus.`id`
                            WHERE college_studsched.`studid` = ? and college_studsched.`deleted` = 0  
                        ', [$studinfo->id]);

                        $units = $totalunits[0]->totalunits;

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
                                'syid' => FinanceModel::getSYID(),
                                'semid' => FinanceModel::getSemID(),
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
                                'syid' => FinanceModel::getSYID(),
                                'semid' => FinanceModel::getSemID(),
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
                    ->where('syid', FinanceModel::getSYID())
                    ->where('semid', FinanceModel::getSemID())
                    ->where('classid', $balforwardsetup->classid)
                    ->where('void', 0)
                    ->where('deleted', 0)
                    ->first();

                if($studledger)
                {
                    db::table('studledgeritemized')
                        ->insert([
                            'studid' => $studid,
                            'syid' => FinanceModel::getSYID(),
                            'semid' => FinanceModel::getSemID(), 
                            'classificationid' => $studledger->classid,
                            'itemamount' => $studledger->amount,
                            'deleted' => 0,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => FinanceModel::getServerDateTime()
                        ]);
                }

            }
        }
    }
	
    public static function transitemsreset($studid)
    {
        db::table('chrngtransitems')
            ->where('studid', $studid)
            ->where('syid', FinanceModel::getSYID())
            ->where('semid', FinanceModel::getSemID())
            ->update([
              'deleted' => 1
            ]);

        $chrngtrans = db::table('chrngtrans')
            ->select('chrngtransid', 'chrngtransdetail.id as chrngtransdetailid', 'ornum', 'classid', 'chrngtransdetail.amount', 'studid', 'syid', 'semid')
            ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
            ->where('cancelled', 0)
            // ->where('posted', 1)
            ->where('studid', $studid)
            ->get();

        // return $chrngtrans;

        $transOR = 0;
        $transstudID = 0;

        foreach($chrngtrans as $trans)
        {

            $transOR = $trans->ornum;
            $transstudID = $trans->studid;

            $transamount = $trans->amount;


            top:

            $ledgeritemized = db::select(
                'SELECT *
                FROM `studledgeritemized` 
                WHERE `studid` = ? 
                    AND `syid` = ? 
                    AND `semid` = ? 
                    AND `classificationid` =? 
                    AND `deleted` = 0
                    AND (`totalamount` < itemamount or isnull(totalamount))', [$trans->studid, $trans->syid, $trans->semid, $trans->classid]
            );


            if(count($ledgeritemized) == 0)
            {
                // $transamount = 0;
                // $ledgeritemized = db::select(
                //   'SELECT *
                //   FROM `studledgeritemized` 
                //   WHERE `studid` = ? 
                //     AND `syid` = ? 
                //     AND `semid` = ? 
                //     AND `deleted` = 0
                //     AND (`totalamount` < itemamount or isnull(totalamount))', [$trans->studid, $trans->syid, $trans->semid]
                // );
                $ledgeritemized = db::select(
                    'SELECT *
                    FROM `studledgeritemized` 
                    WHERE `studid` = ? 
                        AND `syid` = ? 
                        AND `semid` = ? 
                        AND `classificationid` =? 
                        AND `deleted` = 0', [$trans->studid, $trans->syid, $trans->semid, $trans->classid]
                );

                    // return $ledgeritemized;

                    // if($ledgeritemized == 0)
                    // {
                    //   $ledgeritemized = db::select(
                    //     'SELECT *
                    //     FROM `studledgeritemized` 
                    //     WHERE `studid` = ? 
                    //       AND `syid` = ? 
                    //       AND `semid` = ? 
                    //       AND `classificationid` =? 
                    //       AND `deleted` = 0', [$trans->studid, $trans->syid, $trans->semid, $trans->classid]
                    //   );          
                    // }

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
                                        'updateddatetime' => FinanceModel::getServerDateTime()
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
                                        'createddatetime' => FinanceModel::getServerDateTime()
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
                                        'updateddatetime' => FinanceModel::getServerDateTime()
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
                                    'createddatetime' => FinanceModel::getServerDateTime()
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
                                    'createddatetime' => FinanceModel::getServerDateTime()
                                ]);

                            $transamount = 0;
                        }
                    }
                }
            }
        }
    }
    
    public static function enrolledstudcount($syid, $semid)
    {
        $count = 0;
        
        $basic = db::table('enrolledstud')
          ->where('deleted', 0)
          ->whereIn('studstatus', [1,2,4])
          ->where('syid', $syid)
          ->count();
        
        $shs = db::table('sh_enrolledstud')
          ->where('deleted', 0)
          ->whereIn('studstatus', [1,2,4])
          ->where('syid', $syid)
          ->where('semid', $semid)
          ->count();
        
        $col = db::table('college_enrolledstud')
          ->where('deleted', 0)
          ->whereIn('studstatus', [1,2,4])
          ->where('syid', $syid)
          ->where('semid', $semid)
          ->count();
        
        $count = $basic + $shs + $col;
        
        return $count;
    }

    public static function readytoenroll($syid, $semid)
    {
        if(db::table('schoolinfo')->first()->cashierversion == 1) //DP on cashierversion 1
        {
            $getdp = db::table('chrngtransdetail')              
              ->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
              ->join('items', 'chrngtransdetail.payschedid', '=', 'items.id')
              ->join('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
              ->where('studstatus', 0)
              ->where('chrngtrans.syid', FinanceModel::getSYID())
              ->where('chrngtrans.semid', FinanceModel::getSemID())
              ->where('itemkind', 1)
              ->where('items.isdp', 1)
              ->where('cancelled', 0)
              ->count();
        }
        else //DP on cashierversion 2
        {
			$oldclassid = db::table('balforwardsetup')
				->first()
				->classid;

            $getdp = db::table('studledger')
                ->select(db::raw('studledger.studid, lastname, firstname, middlename, suffix, sum(chrngtransdetail.amount) as amountpaid, chrngtrans.ornum, transdate'))
                ->join('chrngtrans', 'studledger.transid', '=', 'chrngtrans.id')
                ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                ->join('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
                ->where('studstatus', 0)
				->where('chrngtransdetail.classid', '!=', $oldclassid)
                ->where('studledger.deleted', 0)
                ->where('studledger.void', 0)
                ->where('studledger.syid', FinanceModel::getSYID())
                ->where('studledger.semid', FinanceModel::getSemID())
				->where('itemkind', 0)
                // ->where(function($q) use($levelid){
                //     if($levelid == 14 || $levelid == 15)
                //     {   
                //         if(db::table('schoolinfo')->first()->shssetup == 0)
                //         {
                //             $q->where('studledger.semid', FinanceModel::getSemID());
                //         }
                //     }
                //     if($levelid >= 17 && $levelid <= 20)
                //     {
                //         $q->where('studledger.semid', FinanceModel::getSemID());
                //     }
                // })
                ->groupBy('studledger.studid')
                ->get();

			$ready_array = array();

			foreach($getdp as $dp)
			{
				array_push($ready_array, (object)[
					'lastname' => $dp->lastname,
					'firstname' => $dp->firstname,
					'middlename' => ($dp->middlename != null) ? $dp->middlename : '' ,
					'suffix' => ($dp->suffix != null) ? $dp->suffix : '',
					'amount' => number_format($dp->amountpaid, 2),
					'ornum' => $dp->ornum,
					'transdate' => date_format(date_create($dp->transdate), 'm/d/Y h:i A')
				]);
			}

            return $ready_array;
        }
    
        // return $getdpcounter;
    }
	
	public static function studperlevel($levelid, $syid, $semid)
    {
        if($levelid == 14 || $levelid == 15)
        {
            $stud = db::table('sh_enrolledstud')
                ->select(db::raw('count(id) as numofstud'))
                ->where('deleted', 0)
                ->where('levelid', $levelid)
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->whereIn('studstatus', [1,2,4])
                ->first();
        }
        elseif($levelid >= 17 && $levelid <= 21)
        {
            $stud = db::table('college_enrolledstud')
                ->select(db::raw('count(id) as numofstud'))
                ->where('deleted', 0)
                ->where('yearLevel', $levelid)
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->whereIn('studstatus', [1,2,4])
                ->first();
        }
        else
        {
            $stud = db::table('enrolledstud')
                ->select(db::raw('count(id) as numofstud'))
                ->where('deleted', 0)
                ->where('levelid', $levelid)
                ->where('syid', $syid)
                ->whereIn('studstatus', [1,2,4])
                ->first();
        }

        return $stud->numofstud;
    }
}