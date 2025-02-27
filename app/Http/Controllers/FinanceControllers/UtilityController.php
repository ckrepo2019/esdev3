<?php


namespace App\Http\Controllers\FinanceControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\FinanceModel;
use App\RegistrarModel;
use App\Models\Finance\FinanceUtilityModel;
use PDF;
use Dompdf\Dompdf;
use Session;
use Auth;
use Hash;

class UtilityController extends Controller
{
	public function utilities()
	{
		return view('finance/utilities/utils');
	}

	public function genpayinfo(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');
			$syid = $request->get('syid');
			$semid = $request->get('semid');
			$batchid = 0;
			$levelid = 0;

			$tvbatch = db::table('tv_batch')
				->where('deleted', 0)
				->where('isactive', 1)
				->first();

			if($tvbatch)
			{
				$batchid = $tvbatch->id;
			}
			
			$studinfo = db::table('studinfo')
				->where('id', $studid)
				->first();

			

			if($studinfo->levelid == 15 || $studinfo->levelid == 14)
			{
				$enrollstud = db::table('sh_enrolledstud')
					->where('studid', $studid)
					->where('syid', $syid)
					->where(function($q) use($semid){
						if($semid == 3)
						{
							$q->where('semid', 3);
						}
						else
						{
							if(db::table('schoolinfo')->first()->shssetup == 0)
							{
								$q->where('semid', $semid);
							}
						}
					})
					->first();

				if($enrollstud)
				{
					$levelid = $enrollstud->levelid;
				}
				else
				{
					$levelid = $studinfo->levelid;
				}
			}
			elseif($studinfo->levelid >= 17 && $studinfo->levelid <= 20)
			{
				$enrollstud = db::table('college_enrolledstud')
					->where('studid', $studid)
					->where('syid', $syid)
					->where('semid', $semid)
					->first();	

				if($enrollstud)
				{
					$levelid = $enrollstud->yearLevel;
				}
				else
				{
					$levelid = $studinfo->levelid;
				}
			}
			else
			{
				$enrollstud = db::table('enrolledstud')
					->where('studid', $studid)
					->where('syid', $syid)
					->where(function($q) use($semid){
						if($semid == 3)
						{
							$q->where('ghssemid', 3);
						}
					})
					->first();

				if($enrollstud)
				{
					$levelid = $enrollstud->levelid;
				}
				else
				{
					$levelid = $studinfo->levelid;
				}
			}

			// $levelid = $studinfo->levelid;


			$studledger = db::table('studledger')
				->where('studid', $studid)
				->where(function($q) use($levelid, $semid){
					if($levelid == 15 || $levelid == 14)
					{
						if(FinanceModel::shssetup() == 0)
						{
							$q->where('semid', $semid);	
						}
					}
					if($levelid >= 17 && $levelid <= 20)
					{
						$q->where('semid', $semid);
					}
				})
				->where(function($q) use($levelid, $batchid, $syid){
					if($levelid == 21)
					{
						$q->where('enrollid', $batchid);
					}
					else
					{
						$q->where('syid', $syid);
					}
				})
				->where('deleted', 0)
				->orderBy('createddatetime')
				->get();





			$_ledger = '';

			$rbal = 0;
			$payment = '';
			$amount = '';
			$totalpayment = 0;
			$totalamount = 0;

			foreach($studledger as $ledger)
			{
				if($ledger->payment > 0)
				{
					$payment = number_format($ledger->payment, 2);
				}
				else
				{
					$payment = '';
				}

				if($ledger->amount > 0)
				{
					$amount = number_format($ledger->amount, 2);
				}
				else
				{
					$amount = '';
				}

				if($ledger->void == 0)
				{
					$rbal += $ledger->amount - $ledger->payment;

					$ldate = date_create($ledger->createddatetime);
					$ldate = date_format($ldate, 'm-d-Y');

					$totalamount += $ledger->amount;
					$totalpayment  += $ledger->payment;


					$_ledger .='
						<tr data-id="'.$ledger->id.'">
							<td>'.$ldate.'</td>
							<td>'.$ledger->particulars.'</td>
							<td class="text-right">'.$amount.'</td>
							<td class="text-right">'.$payment.'</td>
							<td class="text-right">'.number_format($rbal, 2).'</td>
							<td>
								<!--<button class="btn btn-warning btn-sm rpa-itemfix" data-id="'.$ledger->id.'"><i class="fas fa-tools"></i></button>
								<button class="btn btn-danger btn-sm rpa-itemremove" data-id="'.$ledger->id.'"><i class="fas fa-trash-alt"></i></button>-->
							</td>
						</tr>
					';
				}
				else
				{
					$ldate = date_create($ledger->createddatetime);
					$ldate = date_format($ldate, 'm-d-Y');

					$_ledger .='
						<tr data-id="'.$ledger->id.'">
							<td class="text-danger"><del>'.$ldate.'</del></td>
							<td class="text-danger"><del>'.$ledger->particulars.'</del></td>
							<td class="text-right text-danger"><del>'.$amount.'</del></td>
							<td class="text-right text-danger"><del>'.$payment.'</del></td>
							<td class="text-right text-danger"><del>'.number_format($rbal, 2).'</del></td>
							<td>
								<!--<button class="btn btn-warning btn-sm rpa-itemfix" data-id="'.$ledger->id.'"><i class="fas fa-tools"></i></button>
								<button class="btn btn-danger btn-sm rpa-itemremove" data-id="'.$ledger->id.'"><i class="fas fa-trash-alt"></i></button>-->
							</td>
						</tr>
					';	
				}
			}

			$feelist = '';

			$fees = db::table('tuitionheader')
				->where('levelid', $levelid)
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
				->where('deleted', 0)
				->get();

			foreach($fees as $fee)
			{
				$feelist .='
					<option value="'.$fee->id.'">'.$fee->description.' - '.$fee->grantee.' - '.$fee->paymentplan.'</option>
				';
			}




			$data = array(
				'ledgerlist' => $_ledger,
				'totalamount' => number_format($totalamount, 2),
				'totalpayment' => number_format($totalpayment, 2),
				'rbal' => number_format($rbal, 2),
				'studid' => $studinfo->sid,
				'grantee' => $studinfo->grantee,
				'feelist' => $feelist,
				'levelid' => $levelid
			);

			echo json_encode($data);
		}
	}
	
	public function resetpayment(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');
			$feesid = $request->get('feesid');

			db::table('studinfo')
				->where('id', $studid)
				->update([
					'feesid' => $feesid
				]);

			$studinfo = db::table('studinfo')
				->where('id', $studid)
				->first();

			$levelid = $studinfo->levelid;
			$grantee = $studinfo->grantee;
			$strandid = $studinfo->strandid;
			$courseid = $studinfo->courseid;
			$dateenrolled = 0;


			if($levelid == 14 || $levelid == 15)
			{
				$enrollstud = db::table('sh_enrolledstud')
					->where('studid', $studid)
					->where('deleted', 0)
					->first();

				if($enrollstud)
				{
					$dateenrolled = $enrollstud->dateenrolled;
				}
			}
			elseif($levelid >= 17 && $levelid <= 22)
			{
				$enrollstud = db::table('college_enrolledstud')
					->where('studid', $studid)
					->where('deleted', 0)
					->first();	

				if($enrollstud)
				{
					$dateenrolled = $enrollstud->date_enrolled;
				}
			}
			else
			{
				$enrollstud = db::table('enrolledstud')
					->where('studid', $studid)
					->where('deleted', 0)
					->first();		

				// if($enrollstud)
				// {
					
				// }

				$dateenrolled = $enrollstud->dateenrolled;
			}

			// return $dateenrolled;


			//clear studledger


			// 

			


			$studledger = db::table('studledger')
				->where('studid', $studid)
				->where('deleted', 0)
				->where('syid', FinanceModel::getSYID())
				->where(function($q) use($levelid){
				    if($levelid == 14 || $levelid == 15)    
				    {
				        $q->where('semid', FinanceModel::getSemID());
				    }
				    if($levelid >= 17 && $levelid <= 20)    
				    {
				        $q->where('semid', FinanceModel::getSemID());
				    }
				})
				->get();

			foreach($studledger as $ledger)
			{
				$tuitiondetail = db::table('tuitiondetail')
					->where('classificationid', $ledger->classid)
					->where('deleted', 0)
					->count();

				if($tuitiondetail > 0)
				{
					db::table('studledger')
						->where('id', $ledger->id)
						->update([
							'deleted' => 1,
							'deleteddatetime' => FinanceModel::getServerDateTime(),
							'deletedby' => auth()->user()->id
						]);

				}
			}

			
			db::table('studpayscheddetail')
				->where('studid', $studid)
				->where('syid', FinanceModel::getSYID())
				->where(function($q) use($levelid){
				    if($levelid == 14 || $levelid == 15)    
				    {
				        $q->where('semid', FinanceModel::getSemID());
				    }
				    if($levelid >= 17 && $levelid <= 20)    
				    {
				        $q->where('semid', FinanceModel::getSemID());
				    }
				})
				->update([
					'deleted' => 1
				]);

			db::table('studledgeritemized')
				->where('studid', $studid)
				->where('syid', FinanceModel::getSYID())
				->where(function($q) use($levelid){
				    if($levelid == 14 || $levelid == 15)    
				    {
				        $q->where('semid', FinanceModel::getSemID());
				    }
				    if($levelid >= 17 && $levelid <= 20)    
				    {
				        $q->where('semid', FinanceModel::getSemID());
				    }
				})
				->update([
					'totalamount' => 0,
					'updatedby' => auth()->user()->id,
					'updateddatetime' => FinanceModel::getServerDateTime()
				]);

			$divbal = 0;

			$balforwardsetup = db::table('balforwardsetup')
				->first();

			$balforwardledger = db::table('studledger')
				->where('studid', $studid)
				->where('syid', FinanceModel::getSYID())
				->where(function($q) use($levelid){
				    if($levelid == 14 || $levelid == 15)    
				    {
				        $q->where('semid', FinanceModel::getSemID());
				    }
				    if($levelid >= 17 && $levelid <= 20)    
				    {
				        $q->where('semid', FinanceModel::getSemID());
				    }
				})
				->where('deleted', 0)
				->where('classid', $balforwardsetup->classid)
				->first();
			
			if($balforwardledger)
			{
				if($balforwardledger->createddatetime <= $dateenrolled)
				{
					// echo $balforwardledger->createddatetime . ' <= ' . $dateenrolled;

					$balforwardpayment = db::table('chrngtrans')
						->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
						->where('studid', $studid)
						->where('classid', $balforwardledger->classid)
						->where('cancelled', 0)
						->count();

					if($balforwardpayment == 0)
					{
						$paymentsetup = db::table('paymentsetup')
							->join('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
							->where('paymentsetup.id', $balforwardsetup->mopid)
							->where('paymentsetupdetail.deleted', 0)
							->get();

						if(count($paymentsetup) > 0)
						{
							$divbal = $balforwardledger->amount / $paymentsetup[0]->noofpayment;
							$divbal = number_format($divbal, 2, '.', '');
							$totalbalforward = 0;

							foreach($paymentsetup as $mop)
							{
								if($mop->paymentno != $mop->noofpayment)
								{
									$totalbalforward += $divbal;
									db::table('studpayscheddetail')
										->insert([
											'studid' => $studid,
											'enrollid' => $enrollstud->id,
											'semid' => FinanceModel::getSemID(),
											'syid' => FinanceModel::getSYID(),
											'classid' => $balforwardsetup->classid,
											'paymentno' => $mop->paymentno,
											'particulars' => $balforwardledger->particulars,
											'duedate' => $mop->duedate,
											'amount' => $divbal,
											'amountpay' => 0,
											'balance' => $divbal,
											'createdby' => auth()->user()->id,
											'createddatetime' => FinanceModel::getServerDateTime()
										]);
								}
								else
								{
									$r_bal = $balforwardledger->amount - $totalbalforward;

									db::table('studpayscheddetail')
										->insert([
											'studid' => $studid,
											'enrollid' => $enrollstud->id,
											'semid' => FinanceModel::getSemID(),
											'syid' => FinanceModel::getSYID(),
											'classid' => $balforwardsetup->classid,
											'paymentno' => $mop->paymentno,
											'particulars' => $balforwardledger->particulars,
											'duedate' => $mop->duedate,
											'amount' => $r_bal,
											'amountpay' => 0,
											'balance' => $r_bal,
											'createdby' => auth()->user()->id,
											'createddatetime' => FinanceModel::getServerDateTime()
										]);
								}
							}
						}

					}
					else
					{
						$paymentsetup = db::table('paymentsetup')
							->join('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
							->where('paymentsetup.id', $balforwardsetup->mopid)
							->where('paymentsetupdetail.deleted', 0)
							->get();

						if(count($paymentsetup) > 0)
						{
							$divbal = $balforwardledger->amount / $paymentsetup[0]->noofpayment;
							$divbal = number_format($divbal, 2, '.', '');
							$totalbalforward = 0;

							foreach($paymentsetup as $mop)
							{	
								if($mop->paymentno != $mop->noofpayment)
								{
									$totalbalforward += $divbal;
									db::table('studpayscheddetail')
										->insert([
											'studid' => $studid,
											'enrollid' => $enrollstud->id,
											'semid' => FinanceModel::getSemID(),
											'syid' => FinanceModel::getSYID(),
											'classid' => $balforwardsetup->classid,
											'paymentno' => $mop->paymentno,
											'particulars' => $balforwardledger->particulars,
											'duedate' => $mop->duedate,
											'amount' => $divbal,
											'amountpay' => 0,
											'balance' => $divbal,
											'createdby' => auth()->user()->id,
											'createddatetime' => FinanceModel::getServerDateTime()
										]);
								}
								else
								{
									$r_bal = $balforwardledger->amount - $totalbalforward;

									db::table('studpayscheddetail')
										->insert([
											'studid' => $studid,
											'enrollid' => $enrollstud->id,
											'semid' => FinanceModel::getSemID(),
											'syid' => FinanceModel::getSYID(),
											'classid' => $balforwardsetup->classid,
											'paymentno' => $mop->paymentno,
											'particulars' => $balforwardledger->particulars,
											'duedate' => $mop->duedate,
											'amount' => $r_bal,
											'amountpay' => 0,
											'balance' => $r_bal,
											'createdby' => auth()->user()->id,
											'createddatetime' => FinanceModel::getServerDateTime()
										]);
								}

							}
						}
					}
				}
				else
				{
					$paymentsetup = db::table('paymentsetup')
						->join('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
						->where('paymentsetup.id', $balforwardsetup->mopid)
						->where('paymentsetupdetail.deleted', 0)
						->get();

					if(count($paymentsetup) > 0)
					{
						$divbal = $balforwardledger->amount / $paymentsetup[0]->noofpayment;
						$divbal = number_format($divbal, 2, '.', '');
						$totalbalforward = 0;

						foreach($paymentsetup as $mop)
						{
							if($mop->paymentno != $mop->noofpayment)
							{
								$totalbalforward += $divbal;
								db::table('studpayscheddetail')
									->insert([
										'studid' => $studid,
										'enrollid' => $enrollstud->id,
										'semid' => FinanceModel::getSemID(),
										'syid' => FinanceModel::getSYID(),
										'classid' => $balforwardsetup->classid,
										'paymentno' => $mop->paymentno,
										'particulars' => $balforwardledger->particulars,
										'duedate' => $mop->duedate,
										'amount' => $divbal,
										'amountpay' => 0,
										'balance' => $divbal,
										'createdby' => auth()->user()->id,
										'createddatetime' => FinanceModel::getServerDateTime()
									]);
							}
							else
							{
								$r_bal = $balforwardledger->amount - $totalbalforward;

								db::table('studpayscheddetail')
									->insert([
										'studid' => $studid,
										'enrollid' => $enrollstud->id,
										'semid' => FinanceModel::getSemID(),
										'syid' => FinanceModel::getSYID(),
										'classid' => $balforwardsetup->classid,
										'paymentno' => $mop->paymentno,
										'particulars' => $balforwardledger->particulars,
										'duedate' => $mop->duedate,
										'amount' => $r_bal,
										'amountpay' => 0,
										'balance' => $r_bal,
										'createdby' => auth()->user()->id,
										'createddatetime' => FinanceModel::getServerDateTime()
									]);
							}
						}
					}


				}
			
			}

			$units = 0;

			$tuitions = db::table('tuitionheader')
				->select('tuitionheader.id', 'syid', 'semid', 'grantee', 'strandid', 'courseid', 'classificationid', 'itemclassification.description as classdescription', 'tuitiondetail.amount', 'tuitiondetail.pschemeid', 'tuitiondetail.id as tuitiondetailid', 'istuition')
				->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
				->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
				->where('tuitionheader.deleted', 0)
				->where('tuitiondetail.deleted', 0)
				->where('tuitionheader.id', $feesid)
				->get();

			if($levelid >= 17 && $levelid <=20)
			{
				$totalunits = db::select('
	  				SELECT SUM(lecunits) + SUM(labunits) AS totalunits
						FROM college_studsched
					INNER JOIN college_classsched ON college_studsched.`schedid` = college_classsched.`id`
					INNER JOIN college_prospectus ON college_classsched.`subjectID` = college_prospectus.`id`
					WHERE college_studsched.`studid` = ? and college_studsched.`deleted` = 0	
	  			', [$studid]);

	  			$units = $totalunits[0]->totalunits;
	  		}

			foreach($tuitions as $tuition)
			{

				if($levelid >= 17 && $levelid <=20)
				{
					if($tuition->istuition == 1)
	  				{
	  					// echo $tui->amount . ' * ' . $units;
	  					$tuitionamount = $tuition->amount * $units;
	  				}
	  				else
	  				{
	  					$tuitionamount = $tuition->amount;
	  				}
				}
				else
				{
					$tuitionamount = $tuition->amount;
				}

				db::table('studledger')
					->insert([
						'studid' => $studid,
						'semid' => FinanceModel::getSemID(),
						'syid' => FinanceModel::getSYID(),
						'classid' => $tuition->classificationid,
						'particulars' => $tuition->classdescription,
						'amount' => $tuitionamount,
						'pschemeid' => $tuition->pschemeid,
						'createdby' => auth()->user()->id,
						'createddatetime' => $dateenrolled,
						'deleted' => 0
					]);

				$tuitionitems = db::table('tuitionitems')
					->where('tuitiondetailid', $tuition->tuitiondetailid)
					->where('deleted', 0)
					->get();

				foreach($tuitionitems as $items)
				{
					$countitemized = db::table('studledgeritemized')
						->where('studid', $studid)
						->where('syid', FinanceModel::getSYID())
						->where(function($q) use($levelid){
						    if($levelid == 14 || $levelid == 15)    
						    {
						        $q->where('semid', FinanceModel::getSemID());
						    }
						    if($levelid >= 17 && $levelid <= 20)    
						    {
						        $q->where('semid', FinanceModel::getSemID());
						    }
						})
						->where('itemid', $items->itemid)
						->count();

					if($countitemized > 0)
					{
						db::table('studledgeritemized')
							->where('studid', $studid)
							->where('syid', FinanceModel::getSYID())
							->where('semid', FinanceModel::getSemID())
							->where('itemid', $items->itemid)
							->update([
								'itemamount' => $items->amount,
								'updatedby' => auth()->user()->id,
								'updateddatetime' => FinanceModel::getServerDateTime()
							]);
					}
					else
					{
						db::table('studledgeritemized')
							->insert([
								'studid' => $studid,
								'semid' => FinanceModel::getSemID(),
								'syid' => FinanceModel::getSYID(),
								'tuitiondetailid' => $items->tuitiondetailid,
								'itemid' => $items->itemid,
								'itemamount' => $items->amount,
								'deleted' => 0
							]);
					}
				}

				if(db::table('schoolinfo')->first()->snr == 'dcc')
  				{
  					$dpsetup = db::table('dpsetup')
  						->where('levelid', $levelid)
  						->where('deleted', 0)
  						->first();
  					// return $dpsetup->classid;
  					// echo ' ' . $tuition->classificationid . ' ' . $dpsetup->classid . ';';
  					if($tuition->classificationid == $dpsetup->classid)
  					{

  						$tuitionamount -= $dpsetup->amount;


  						$scheditem = db::table('studpayscheddetail')
	  						->insert([
	  							'studid' => $studid,
	  							'enrollid' => $enrollstud->id,
	  							'syid' => FinanceModel::getSYID(),
	  							'semid' => FinanceModel::getSemID(),
	  							'tuitiondetailid' => $tuition->tuitiondetailid,
	  							'particulars' => 'Downpayment',
	  							'amount' => $dpsetup->amount,
	  							'amountpay' => 0,
	  							'balance' => $dpsetup->amount,
	  							'classid' => $tuition->classificationid,
	  							'createddatetime' => FinanceModel::getServerDateTime(),
	  							'createdby' => auth()->user()->id
	  						]);
  					}



  				}

				$paymentsetup = db::table('paymentsetup')
  					->select('paymentsetup.id', 'paymentdesc', 'paymentsetup.noofpayment', 'paymentno', 'duedate', 'payopt', 'percentamount')
  					->leftjoin('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
  					->where('paymentsetup.id', $tuition->pschemeid)
  					->where('paymentsetupdetail.deleted', 0)
  					->get();

  				if(count($paymentsetup) > 0)
  				{
  					if($paymentsetup[0]->payopt == 'divided')
  					{
  						$divPay = 0;

  						if(count($paymentsetup) > 1)
			  			{
			  				$paymentno = $paymentsetup[0]->noofpayment;
			  				$divPay = $tuitionamount / $paymentno;
			  				$divPay = number_format($divPay, 2, '.', '');
			  			}
			  			else
			  			{
			  				$paymentno = 1;
			  				$divPay = $tuitionamount;
			  				$divPay = number_format($divPay, 2, '.', '');
			  			}

			  			$paycount = 0;
			  			$paytAmount = 0;
			  			$paydisbalance = 0;
			  			$testclassid = 0;

			  			foreach($paymentsetup as $pay)
			  			{
			  				$paycount += 1;
	  						$paytAmount += $divPay;

	  						if($paycount != $paymentno)
	  						{	
	  							
	  							$scheditem = db::table('studpayscheddetail')
			  						->insert([
			  							'studid' => $studid,
			  							'enrollid' => $enrollstud->id,
			  							'syid' => FinanceModel::getSYID(),
			  							'semid' => FinanceModel::getSemID(),
			  							'tuitiondetailid' => $tuition->tuitiondetailid,
			  							'particulars' => $tuition->classdescription,
			  							'duedate' => $pay->duedate,
			  							'paymentno' => $pay->paymentno,
			  							'amount' => $divPay,
			  							'amountpay' => 0,
			  							'balance' => $divPay,
			  							'classid' => $tuition->classificationid,
			  							'createddatetime' => FinanceModel::getServerDateTime(),
			  							'createdby' => auth()->user()->id
			  						]);

			  						
	  						}
	  						else
	  						{
	  							if($paytAmount <= $tuitionamount)
	  							{
	  								
	  								$paydisbalance = $tuitionamount - $paytAmount;
			  						$paydisbalance = number_format($paydisbalance, 2, '.', '');

			  						$divPay += $paydisbalance;
			  						
			  						// echo ' paydisbalance: ' . $paydisbalance;
			  						// echo ' +divPay: '. $divPay;
			  						$scheditem = db::table('studpayscheddetail')
				  						->insert([
				  							'studid' => $studid,
				  							'enrollid' => $enrollstud->id,
				  							'syid' => FinanceModel::getSYID(),
			  								'semid' => FinanceModel::getSemID(),
				  							'tuitiondetailid' => $tuition->tuitiondetailid,
				  							'particulars' => $tuition->classdescription,
				  							'duedate' => $pay->duedate,
				  							'paymentno' => $pay->paymentno,
				  							'amount' => $divPay,
				  							'balance' => $divPay,
				  							'amountpay' => 0,
				  							'classid' => $tuition->classificationid,
				  							'createddatetime' => FinanceModel::getServerDateTime(),
			  								'createdby' => auth()->user()->id
				  						]);
	  							}
	  							else
	  							{
	  								
	  								$paydisbalance = $paytAmount - $tuitionamount;
	  								$paydisbalance = number_format($paydisbalance, 2, '.', '');

	  								$divPay -= $paydisbalance;

	  								$scheditem = db::table('studpayscheddetail')
				  						->insert([
				  							'studid' => $studid,
				  							'enrollid' => $enrollstud->id,
				  							'syid' => FinanceModel::getSYID(),
			  								'semid' => FinanceModel::getSemID(),
				  							'tuitiondetailid' => $tuition->tuitiondetailid,
				  							'particulars' => $tuition->classdescription,
				  							'duedate' => $pay->duedate,
				  							'paymentno' => $pay->paymentno,
				  							'amount' => $divPay,
				  							'amountpay' => 0,
				  							'balance' => $divPay,
				  							'classid' => $tuition->classificationid,
				  							'createddatetime' => FinanceModel::getServerDateTime(),
			  								'createdby' => auth()->user()->id
				  						]);
	  							}
	  						}
	  						
			  			}
			  			
  					}
  					else
  					{

  						$paycount = 0;
			  			$pAmount = 0;
			  			$curAmount = $tuition->amount;

			  			foreach($paymentsetup as $pay)
			  			{
			  				$paycount +=1;

			  				if($paycount < count($paymentsetup))
			  				{
			  					if($curAmount > 0)
			  					{
				  					$pAmount = round($pay->percentamount * ($tui->amount/100), 2);
				  					$curAmount = (round($curAmount - $pAmount, 2));
 
				  					$scheditem = db::table('studpayscheddetail')
				  					->insert([
				  							'studid' => $studid,
				  							'enrollid' => $enrollid,
				  							'syid' => $sy,
				  							'semid' => $semid,
				  							'tuitiondetailid' => $tui->tuitiondetailid,
				  							'particulars' => $tui->particulars,
				  							'duedate' => $pay->duedate,
				  							'paymentno' => $pay->paymentno,
				  							'amount' => $pAmount,
				  							'amountpay' => 0,
				  							'balance' => $pAmount,
				  							'classid' => $tui->classid,
				  							'createddatetime' => FinanceModel::getServerDateTime(),
			  								'createdby' => auth()->user()->id
				  						]);
				  				}
			  				}
			  				else
			  				{
			  					if($curAmount > 0)
			  					{
			  						$scheditem = db::table('studpayscheddetail')
			  						->insert([
				  							'studid' => $studid,
				  							'enrollid' => $enrollid,
				  							'syid' => $sy,
				  							'semid' => $semid,
				  							'tuitiondetailid' => $tui->tuitiondetailid,
				  							'particulars' => $tui->particulars,
				  							'duedate' => $pay->duedate,
				  							'paymentno' => $pay->paymentno,
				  							'amount' => $curAmount,
				  							'amountpay' => 0,
				  							'balance' => $curAmount,
				  							'classid' => $tui->classid,
				  							'createddatetime' => FinanceModel::getServerDateTime(),
			  								'createdby' => auth()->user()->id
				  						]);	
			  						$curAmount = 0;
			  					}
			  				}
			  			}
  					}
  				}
			}

			$tDP = 0;
	  		$dpBal = 0;
	  		$schdbal = 0;
	  		$aPay = 0;
	  		$_over=0;


	  		if($levelid == 14 || $levelid == 15)
	  		{
	  			$getdp = db::table('chrngtransdetail')				
					->select('studid', 'syid', 'semid', 'itemkind', 'payschedid', 'isdp', 'chrngtransdetail.classid', 'chrngtransdetail.amount')
					->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
					->join('items', 'chrngtransdetail.payschedid', '=', 'items.id')
					->where('studid', $studid)
					->where('syid', FinanceModel::getSYID())
					->where('semid', FinanceModel::getSemID())
					->where('itemkind', 1)
					->where('isdp', 1)
					->where('chrngtrans.cancelled', 0)
					->get();

				// echo ' getDP: ' . $getdp;
				if(count($getdp) > 0)
				{
					foreach($getdp as $dp)
					{
						$dpBal = $dp->amount;

						$balforward = db::table('balforwardsetup')
							->first();


						$getpaySched = db::table('studpayscheddetail')
							// ->select('studpayscheddetail.*', 'aaaa')
							->where('studid', $studid)
							->where('syid', FinanceModel::getSYID())
							->where('semid', FinanceModel::getSemID())
							->where('classid', $balforward->classid)
							->where('deleted', 0)
							->get();



						if(count($getpaySched) > 0)
						{
							foreach($getpaySched as $sched)
							{
								if($dpBal > 0)
								{
									$schedbal = $sched->amount - $sched->amountpay;

									if($dpBal > $schedbal)
									{
										$deductDP = db::table('studpayscheddetail')
											->where('id', $sched->id)
											->update([
												'amountpay' => $schedbal + $sched->amountpay,
												'balance' => 0,
												'updateddatetime' => FinanceModel::getServerDateTime(),
												'updatedby' => auth()->user()->id
												]);

										$dpBal -= $schedbal;

									}
									else
									{
										$tDP = $sched->amount - $dpBal;
										$aPay = $sched->amountpay + $dpBal;

										$deductDP = db::table('studpayscheddetail')
											->where('id', $sched->id)
											->update([
												'amountpay' => $aPay,
												'balance' => $tDP,
												'updateddatetime' => FinanceModel::getServerDateTime(),
												'updatedby' => auth()->user()->id
											]);
										$dpBal = 0;

									}
								}
							}
						}

						$getpaySched = db::table('studpayscheddetail')
							->where('studid', $studid)
							->where('syid', FinanceModel::getSYID())
							->where('semid', FinanceModel::getSemID())
							->where('classid', $dp->classid)
							->where('deleted', 0)
							->get();

						if(count($getpaySched) > 0)
						{
							foreach($getpaySched as $sched)
							{
								if($dpBal > 0)
								{
									// echo '[' . $dpBal . '>' . $sched->amount . ']';
									$schedbal = $sched->amount - $sched->amountpay;
									
									if($dpBal > $schedbal)
									{
										$tDP = 0;
										
										$deductDP = db::table('studpayscheddetail')
											->where('id', $sched->id)
											->update([
												'amountpay' => $schedbal + $sched->amountpay,
												'balance' => $tDP,
												'updateddatetime' => FinanceModel::getServerDateTime(),
												'updatedby' => auth()->user()->id
												]);

										$dpBal -= $schedbal;
									}
									else
									{
										$tDP = $sched->amount - $dpBal;
										$aPay = $sched->amountpay + $dpBal;

										// echo ' aPay = ' . $aPay;
										
										$deductDP = db::table('studpayscheddetail')
											->where('id', $sched->id)
											->update([
												'amountpay' => $aPay,
												'balance' => $tDP,
												'updateddatetime' => FinanceModel::getServerDateTime(),
												'updatedby' => auth()->user()->id
											]);
										

										$dpBal = 0;
									}
								}
							}

							if($dpBal > 0)
							{
								$gpaydetail = db::select('
									SELECT studid, syid, semid, tuitiondetailid, classid, particulars, SUM(amount) AS amount, SUM(amountpay) AS amountpay, SUM(balance) AS balance 
									FROM studpayscheddetail
									WHERE studid = ?  AND syid = ? and semid = ? AND deleted = 0 and balance > 0
									GROUP BY classid
								', [$studid, FinanceModel::getSYID(), FinanceModel::getSemID()]);

								foreach($gpaydetail as $detail)
								{
									$paysched = db::table('studpayscheddetail')
											->where('classid', $detail->classid)
											->where('studid', $studid)
											->where('syid', FinanceModel::getSYID())
											->where('semid', FinanceModel::getSemID())
											->where('deleted', 0)
											->get();

									if($dpBal > 0)
									{		
										foreach($paysched as $sched)
										{
											if($dpBal > $sched->balance)
											{
												$tDP = 0;
												$deductDP = db::table('studpayscheddetail')
													->where('id', $sched->id)
													->update([
														'amountpay' => $sched->balance + $sched->amountpay,
														'balance' => $tDP
													]);

												$dpBal -= $sched->balance;
											}
											else
											{
												$tDP = $sched->balance - $dpBal;
												$aPay = $sched->amountpay + $dpBal;
												
												$deductDP = db::table('studpayscheddetail')
													->where('id', $sched->id)
													->update([
														'amountpay' => $aPay,
														'balance' => $tDP,
														'updateddatetime' => FinanceModel::getServerDateTime(),
														'updatedby' => auth()->user()->id
													]);
												

												$dpBal = 0;
											}
										}
									}

								}

							}
						}
						else
						{
							$getpaySched = db::table('studpayscheddetail')
								->where('studid', $studid)
								->where('syid', FinanceModel::getSYID())
								->where('semid', FinanceModel::getSemID())
								->where('deleted', 0)
								->get();

							if(count($getpaySched) > 0)
							{
								foreach($getpaySched as $sched)
								{
									if($dpBal > 0)
									{
										// echo '[' . $dpBal . '>' . $sched->amount . ']';
										$schedbal = $sched->amount - $sched->amountpay;
										
										if($dpBal > $schedbal)
										{
											$tDP = 0;
											
											$deductDP = db::table('studpayscheddetail')
												->where('id', $sched->id)
												->update([
													'amountpay' => $schedbal + $sched->amountpay,
													'balance' => $tDP,
													'updateddatetime' => FinanceModel::getServerDateTime(),
													'updatedby' => auth()->user()->id
													]);

											$dpBal -= $schedbal;
										}
										else
										{
											$tDP = $sched->amount - $dpBal;
											$aPay = $sched->amountpay + $dpBal;

											// echo ' aPay = ' . $aPay;
											
											$deductDP = db::table('studpayscheddetail')
												->where('id', $sched->id)
												->update([
													'amountpay' => $aPay,
													'balance' => $tDP,
													'updateddatetime' => FinanceModel::getServerDateTime(),
													'updatedby' => auth()->user()->id
												]);
											

											$dpBal = 0;
										}
									}
								}

								if($dpBal > 0)
								{
									$gpaydetail = db::select('
										SELECT studid, syid, semid, tuitiondetailid, classid, particulars, SUM(amount) AS amount, SUM(amountpay) AS amountpay, SUM(balance) AS balance 
										FROM studpayscheddetail
										WHERE studid = ?  AND syid = ? and semid = ? AND deleted = 0 and balance > 0
										GROUP BY classid
									', [$studid, FinanceModel::getSYID(), FinanceModel::getSemID()]);

									foreach($gpaydetail as $detail)
									{
										$paysched = db::table('studpayscheddetail')
												->where('classid', $detail->classid)
												->where('studid', $studid)
												->where('syid', FinanceModel::getSYID())
												->where('semid', FinanceModel::getSemID())
												->where('deleted', 0)
												->get();

										if($dpBal > 0)
										{		
											foreach($paysched as $sched)
											{
												if($dpBal > $sched->balance)
												{
													$tDP = 0;
													$deductDP = db::table('studpayscheddetail')
														->where('id', $sched->id)
														->update([
															'amountpay' => $sched->balance + $sched->amountpay,
															'balance' => $tDP
														]);

													$dpBal -= $sched->balance;
												}
												else
												{
													$tDP = $sched->balance - $dpBal;
													$aPay = $sched->amountpay + $dpBal;
													
													$deductDP = db::table('studpayscheddetail')
														->where('id', $sched->id)
														->update([
															'amountpay' => $aPay,
															'balance' => $tDP,
															'updateddatetime' => FinanceModel::getServerDateTime(),
															'updatedby' => auth()->user()->id
														]);
													

													$dpBal = 0;
												}
											}
										}

									}

								}
							}
						}
					}
				}
	  		}
	  		elseif($levelid >= 17 && $levelid <=20)
	  		{
	  			$getdp = db::table('chrngtransdetail')				
					->select('studid', 'syid', 'semid', 'itemkind', 'payschedid', 'isdp', 'chrngtransdetail.classid', 'chrngtransdetail.amount')
					->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
					->join('items', 'chrngtransdetail.payschedid', '=', 'items.id')
					->where('studid', $studid)
					->where('syid', FinanceModel::getSYID())
					->where('semid', FinanceModel::getSemID())
					->where('itemkind', 1)
					->where('isdp', 1)
					->where('chrngtrans.cancelled', 0)
					->get();

				if(count($getdp) > 0)
				{
					foreach($getdp as $dp)
					{
						$dpBal = $dp->amount;

						$balforward = db::table('balforwardsetup')
							->first();

						$getpaySched = db::table('studpayscheddetail')
								->where('studid', $studid)
								->where('syid', FinanceModel::getSYID())
								->where('semid', FinanceModel::getSemID())
								->where('classid', $balforward->classid)
								->where('deleted', 0)
								->get();

						if(count($getpaySched) > 0)
						{
							foreach($getpaySched as $sched)
							{
								if($dpBal > 0)
								{
									$schedbal = $sched->amount - $sched->amountpay;

									if($dpBal > $schedbal)
									{
										$deductDP = db::table('studpayscheddetail')
											->where('id', $sched->id)
											->update([
												'amountpay' => $schedbal + $sched->amountpay,
												'balance' => 0,
												'updateddatetime' => FinanceModel::getServerDateTime(),
												'updatedby' => auth()->user()->id
												]);

										$dpBal -= $schedbal;
									}
									else
									{
										$tDP = $sched->amount - $dpBal;
										$aPay = $sched->amountpay + $dpBal;

										$deductDP = db::table('studpayscheddetail')
											->where('id', $sched->id)
											->update([
												'amountpay' => $aPay,
												'balance' => $tDP,
												'updateddatetime' => FinanceModel::getServerDateTime(),
												'updatedby' => auth()->user()->id
											]);
									}
								}
							}
						}

						$getpaySched = db::table('studpayscheddetail')
							// ->select('studpayscheddetail.*', 'aaa')
							->where('studid', $studid)
							->where('syid', FinanceModel::getSYID())
							->where('semid', FinanceModel::getSemID())
							->where('classid', $dp->classid)
							->where('deleted', 0)
							->get();

						if(count($getpaySched) == 0)
						{
							$getpaySched = db::table('studpayscheddetail')
							// ->select('studpayscheddetail.*', 'aaa')
								->where('studid', $studid)
								->where('syid', FinanceModel::getSYID())
								->where('semid', FinanceModel::getSemID())
								->where('deleted', 0)
								->get();							
						}

						if(count($getpaySched) > 0)
						{
							foreach($getpaySched as $sched)
							{
								if($dpBal > 0)
								{
									// echo '[' . $dpBal . '>' . $sched->amount . ']';
									$schedbal = $sched->amount - $sched->amountpay;
									
									if($dpBal > $schedbal)
									{
										$tDP = 0;
										
										$deductDP = db::table('studpayscheddetail')
											->where('id', $sched->id)
											->update([
												'amountpay' => $schedbal + $sched->amountpay,
												'balance' => $tDP,
												'updateddatetime' => FinanceModel::getServerDateTime(),
												'updatedby' => auth()->user()->id
												]);

										$dpBal -= $schedbal;
									}
									else
									{
										$tDP = $sched->amount - $dpBal;
										$aPay = $sched->amountpay + $dpBal;

										// echo ' aPay = ' . $aPay;
										
										$deductDP = db::table('studpayscheddetail')
											->where('id', $sched->id)
											->update([
												'amountpay' => $aPay,
												'balance' => $tDP,
												'updateddatetime' => FinanceModel::getServerDateTime(),
												'updatedby' => auth()->user()->id
											]);
										

										$dpBal = 0;
									}
								}
							}

							if($dpBal >0)
							{
								$gpaydetail = db::select('
									SELECT studid, syid, semid, tuitiondetailid, classid, particulars, SUM(amount) AS amount, SUM(amountpay) AS amountpay, SUM(balance) AS balance 
									FROM studpayscheddetail
									WHERE studid = ?  AND syid = ? and semid = ? AND deleted = 0 and balance > 0
									GROUP BY classid
								', [$studid, FinanceModel::getSYID(), FinanceModel::getSemID()]);

								foreach($gpaydetail as $detail)
								{
									$paysched = db::table('studpayscheddetail')
											->where('classid', $detail->classid)
											->where('studid', $studid)
											->where('syid', FinanceModel::getSYID())
											->where('semid', FinanceModel::getSemID())
											->where('deleted', 0)
											->get();

									if($dpBal > 0)
									{		
										foreach($paysched as $sched)
										{
											if($dpBal > $sched->balance)
											{
												$tDP = 0;
												$deductDP = db::table('studpayscheddetail')
													->where('id', $sched->id)
													->update([
														'amountpay' => $sched->balance + $sched->amountpay,
														'balance' => $tDP
													]);

												$dpBal -= $sched->balance;
											}
											else
											{
												$tDP = $sched->balance - $dpBal;
												$aPay = $sched->amountpay + $dpBal;
												
												$deductDP = db::table('studpayscheddetail')
													->where('id', $sched->id)
													->update([
														'amountpay' => $aPay,
														'balance' => $tDP,
														'updateddatetime' => FinanceModel::getServerDateTime(),
														'updatedby' => auth()->user()->id
													]);
												

												$dpBal = 0;
											}
										}
									}

								}
							}
						}						
					}
				}
	  		}
	  		else
	  		{
	  			$getdp = db::table('chrngtransdetail')				
						->select('studid', 'syid', 'semid', 'itemkind', 'payschedid', 'isdp', 'chrngtransdetail.classid', 'chrngtransdetail.amount')
						->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
						->join('items', 'chrngtransdetail.payschedid', '=', 'items.id')
						->where('studid', $studid)
						->where('syid', FinanceModel::getSYID())
						->where('itemkind', 1)
						->where('isdp', 1)
						->where('chrngtrans.cancelled', 0)
						->get();


				if(count($getdp) > 0)
				{
					foreach($getdp as $dp)
					{
						$dpBal = $dp->amount;

						$balforward = db::table('balforwardsetup')
							->first();

						$getpaySched = db::table('studpayscheddetail')
							// ->select('studpayscheddetail.*', 'aaa')
							->where('studid', $studid)
							->where('syid', FinanceModel::getSYID())
							// ->where('semid', RegistrarModel::getSemID())
							->where('classid', $balforward->classid)
							->where('deleted', 0)
							->get();

						//check DP payment
						$chkdpPayment = db::table('chrngtransdetail')
							->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
							->where('studid', $studid)
							->where('classid', $balforward->classid)
							->where('syid', FinanceModel::getSYID())
							->where('semid', FinanceModel::getSemID())
							->count();


						if($chkdpPayment == 0)
						{
							if(count($getpaySched) > 0)
							{
								foreach($getpaySched as $sched)
								{
									if($dpBal > 0)
									{
										$schedbal = $sched->amount - $sched->amountpay;

										if($dpBal > $schedbal)
										{
											$deductDP = db::table('studpayscheddetail')
												->where('id', $sched->id)
												->update([
													'amountpay' => $schedbal + $sched->amountpay,
													'balance' => 0,
													'updateddatetime' => FinanceModel::getServerDateTime(),
													'updatedby' => auth()->user()->id
													]);

											$dpBal -= $schedbal;
										}
										else
										{
											$tDP = $sched->amount - $dpBal;
											$aPay = $sched->amountpay + $dpBal;
											
											$deductDP = db::table('studpayscheddetail')
												->where('id', $sched->id)
												->update([
													'amountpay' => $aPay,
													'balance' => $tDP,
													'updateddatetime' => FinanceModel::getServerDateTime(),
													'updatedby' => auth()->user()->id
												]);
											$dpBal = 0;
										}
									}
								}
							}
						}

						if(db::table('schoolinfo')->first()->dpdist == 0)
						{
							$getpaySched = db::table('studpayscheddetail')
								// ->select('studpayscheddetail.*', 'aaa')
								->where('studid', $studid)
								->where('syid', FinanceModel::getSYID())
								->where('classid', $dp->classid)
								->where('deleted', 0)
								->get();
						}
						else
						{
							$classarray = array();

							$feeclass = db::table('tuitiondetail')
								->where('headerid', $feesid)
								->where('deleted', 0)
								->get();

							foreach($feeclass as $fc)
							{
								array_push($classarray, $fc->classificationid);
							}


							$getpaySched = db::table('studpayscheddetail')
								// ->select('studpayscheddetail.*', 'aaa')
								->where('studid', $studid)
								->where('syid', FinanceModel::getSYID())
								->whereIn('classid', $classarray)
								->where('deleted', 0)
								->get();	
						}
						

						if(count($getpaySched) > 0)
						{
							foreach($getpaySched as $sched)
							{
								if($dpBal > 0)
								{
									$schedbal = $sched->amount - $sched->amountpay;

									// echo $dpBal . ' > ' . $schedbal . ' ';

									if($dpBal > $schedbal)
									{
										$tDP = 0;
										
										$deductDP = db::table('studpayscheddetail')
											->where('id', $sched->id)
											->update([
												'amountpay' => $schedbal + $sched->amountpay,
												'balance' => $tDP,
												'updateddatetime' => FinanceModel::getServerDateTime(),
												'updatedby' => auth()->user()->id
											]);

										$dpBal -= $schedbal;
									}
									else
									{
										$tDP = $schedbal - $dpBal;
										$aPay = $sched->amountpay + $dpBal;
										
										$deductDP = db::table('studpayscheddetail')
											->where('id', $sched->id)
											->update([
												'amountpay' => $aPay,
												'balance' => $tDP,
												'updateddatetime' => FinanceModel::getServerDateTime(),
												'updatedby' => auth()->user()->id
											]);
										

										$dpBal = 0;
									}		
								}
							}

							if($dpBal > 0)
							{
								$gpaydetail = db::select('
									SELECT studid, syid, semid, tuitiondetailid, classid, particulars, SUM(amount) AS amount, SUM(amountpay) AS amountpay, SUM(balance) AS balance 
									FROM studpayscheddetail
									WHERE studid = ?  AND syid = ? AND deleted = 0 and balance > 0
									GROUP BY classid
								', [$studid, FinanceModel::getSYID()]);

								foreach($gpaydetail as $detail)
								{
									$paysched = db::table('studpayscheddetail')
											->where('classid', $detail->classid)
											->where('studid', $studid)
											->where('syid', FinanceModel::getSYID())
											->where('deleted', 0)
											->get();

									if($dpBal > 0)
									{		
										foreach($paysched as $sched)
										{
											if($dpBal > $sched->balance)
											{
												$tDP = 0;
												$deductDP = db::table('studpayscheddetail')
													->where('id', $sched->id)
													->update([
														'amountpay' => $sched->balance + $sched->amountpay,
														'balance' => $tDP,
														'updateddatetime' => FinanceModel::getServerDateTime(),
														'updatedby' => auth()->user()->id
													]);

												$dpBal -= $sched->balance;
											}
											else
											{
												$tDP = $sched->balance - $dpBal;
												$aPay = $sched->amountpay + $dpBal;
												
												$deductDP = db::table('studpayscheddetail')
													->where('id', $sched->id)
													->update([
														'amountpay' => $aPay,
														'balance' => $tDP,
														'updateddatetime' => FinanceModel::getServerDateTime(),
														'updatedby' => auth()->user()->id
													]);
												

												$dpBal = 0;
											}
										}
									}

								}

							}
						}
						else
						{
							$getpaySched = db::table('studpayscheddetail')
								->where('studid', $studid)
								->where('syid', FinanceModel::getSYID())
								// ->where('semid', RegistrarModel::getSemID())
								->where('deleted', 0)
								->get();

							if(count($getpaySched) > 0)
							{
								foreach($getpaySched as $sched)
								{
									if($dpBal > 0)
									{
										// echo '[' . $dpBal . '>' . $sched->amount . ']';
										$schedbal = $sched->amount - $sched->amountpay;
										
										if($dpBal > $schedbal)
										{
											$tDP = 0;
											
											$deductDP = db::table('studpayscheddetail')
												->where('id', $sched->id)
												->update([
													'amountpay' => $schedbal + $sched->amountpay,
													'balance' => $tDP,
													'updateddatetime' => FinanceModel::getServerDateTime(),
													'updatedby' => auth()->user()->id
													]);

											$dpBal -= $schedbal;
										}
										else
										{
											$tDP = $sched->amount - $dpBal;
											$aPay = $sched->amountpay + $dpBal;

											// echo ' aPay = ' . $aPay;
											
											$deductDP = db::table('studpayscheddetail')
												->where('id', $sched->id)
												->update([
													'amountpay' => $aPay,
													'balance' => $tDP,
													'updateddatetime' => FinanceModel::getServerDateTime(),
													'updatedby' => auth()->user()->id
												]);
											

											$dpBal = 0;
										}
									}
								}

								if($dpBal > 0)
								{
									$gpaydetail = db::select('
										SELECT studid, syid, semid, tuitiondetailid, classid, particulars, SUM(amount) AS amount, SUM(amountpay) AS amountpay, SUM(balance) AS balance 
										FROM studpayscheddetail
										WHERE studid = ?  AND syid = ? and semid = ? AND deleted = 0 and balance > 0
										GROUP BY classid
									', [$studid, FinanceModel::getSYID(), FinanceModel::getSemID()]);


									foreach($gpaydetail as $detail)
									{
										$paysched = db::table('studpayscheddetail')
												->where('classid', $detail->classid)
												->where('studid', $studid)
												->where('syid', FinanceModel::getSYID())
												->where('semid', FinanceModel::getSemID())
												->where('deleted', 0)
												->get();

										if($dpBal > 0)
										{
											foreach($paysched as $sched)
											{
												if($dpBal > $sched->balance)
												{
													$tDP = 0;
													$deductDP = db::table('studpayscheddetail')
														->where('id', $sched->id)
														->update([
															'amountpay' => $sched->balance + $sched->amountpay,
															'balance' => $tDP
														]);

													$dpBal -= $sched->balance;
												}
												else
												{
													$tDP = $sched->balance - $dpBal;
													$aPay = $sched->amountpay + $dpBal;
													
													$deductDP = db::table('studpayscheddetail')
														->where('id', $sched->id)
														->update([
															'amountpay' => $aPay,
															'balance' => $tDP,
															'updateddatetime' => FinanceModel::getServerDateTime(),
															'updatedby' => auth()->user()->id
														]);
													

													$dpBal = 0;
												}
											}
										}
									}
								}
							}
						}
					}
				}
	  		}



	  		//Check Adjustment
	  		$adjbal = 0;
	  		$adjustment = db::table('adjustments')
	  			->select('adjustments.id', 'description', 'amount', 'classid', 'isdebit', 'iscredit', 'syid', 'semid', 'adjstatus', 'studid', 'mop', 'adjustments.createddatetime')
	  			->join('adjustmentdetails', 'adjustments.id', '=', 'adjustmentdetails.headerid')
	  			->where('studid', $studid)
	  			->where('syid', FinanceModel::getSYID())
	  			->where(function($q) use($levelid){
	  				if($levelid == 14 || $levelid ==15)
	  				{
	  					$q->where('semid', FinanceModel::getSemID());
	  				}
	  				if($levelid >= 17 && $levelid <=20)
	  				{
	  					$q->where('semid', FinanceModel::getSemID());
	  				}
	  			})
	  			->where('adjstatus', 'APPROVED')
	  			->where('adjustmentdetails.deleted', 0)
	  			->get();


	  		foreach($adjustment as $adj)
	  		{
	  			$adjbal = $adj->amount;
	  			$adjbal = number_format($adj->amount, 2, '.', '');
	  			$particulars = $adj->description;
  			
  				if($adj->iscredit == 1)
  				{

  					

  					$checkadjledger = db::table('studledger')
  						->where('studid', $studid)
  						->where('semid', FinanceModel::getSemID())
		  				->where(function($q) use($levelid){
			  				if($levelid == 14 || $levelid ==15)
			  				{
			  					$q->where('semid', FinanceModel::getSemID());
			  				}
			  				if($levelid >= 17 && $levelid <=20)
			  				{
			  					$q->where('semid', FinanceModel::getSemID());
			  				}
			  			})
		  				->where('particulars', 'like', '%ADJ%')
		  				->where('ornum', $adj->id)
		  				->where('deleted', 0)
		  				->first();

		  			if(!$checkadjledger)
		  			{
		  				db::table('studledger')
		  					->insert([
		  						'studid' => $studid,
		  						'semid' => FinanceModel::getSemID(),
		  						'syid' => FinanceModel::getSYID(),
		  						'classid' => $adj->classid,
		  						'particulars' => 'ADJ: ' . $adj->description,
		  						'payment' => $adj->amount,
		  						'pschemeid' => $adj->mop,
		  						'ornum' => $adj->id,
		  						'deleted' => 0,
		  						'createdby' => auth()->user()->id,
		  						'createddatetime' => $adj->createddatetime //FinanceModel::getServerDateTime()
		  					]);
		  			}

		  			adjloop:

  					$scheddetail = db::table('studpayscheddetail')
		  				->where('studid', $studid)
		  				->where(function($q) use($levelid){
			  				if($levelid == 14 || $levelid ==15)
			  				{
			  					$q->where('semid', FinanceModel::getSemID());
			  				}
			  				if($levelid >= 17 && $levelid <=20)
			  				{
			  					$q->where('semid', FinanceModel::getSemID());
			  				}
			  			})
		  				->where('syid', FinanceModel::getSYID())
		  				->where('classid', $adj->classid)
		  				->where('balance' , '>', 0)
		  				->where('deleted', 0)
		  				->first();		  			

		  			if($scheddetail)
		  			{
		  				// echo ' bal: ' . $adjbal;
	  					if($scheddetail->balance >= $adjbal)
	  					{	
	  						db::table('studpayscheddetail')
			  					->where('id', $scheddetail->id)
			  					->update([
				  						// 'particulars' => $particulars,
				  						'amountpay' => $scheddetail->amountpay + $adjbal,
				  						'balance' => $scheddetail->balance - $adjbal,
				  						'deleted' => 0,
				  						'updatedby' => auth()->user()->id,
				  						'updateddatetime' => FinanceModel::getServerDateTime()
				  					]);


			  				$adjbal = 0;
	  					}
	  					else
	  					{
	  						db::table('studpayscheddetail')
			  					->where('id', $scheddetail->id)
			  					->update([
				  						// 'particulars' => $particulars,
				  						'amountpay' => $scheddetail->amountpay + $scheddetail->balance,
				  						'balance' => 0,
				  						'deleted' => 0,
				  						'updatedby' => auth()->user()->id,
				  						'updateddatetime' => FinanceModel::getServerDateTime()
				  					]);

			  				$adjbal -= $scheddetail->balance;

			  				if($adjbal > 0)
			  				{
			  					goto adjloop;
			  				}

	  					}	
	  				}
	  				else
	  				{
	  					$scheddetail = db::table('studpayscheddetail')
			  				->where('studid', $studid)
			  				->where(function($q) use($levelid){
				  				if($levelid == 14 || $levelid ==15)
				  				{
				  					$q->where('semid', FinanceModel::getSemID());
				  				}
				  				if($levelid >= 17 && $levelid <=20)
				  				{
				  					$q->where('semid', FinanceModel::getSemID());
				  				}
				  			})
			  				->where('syid', FinanceModel::getSYID())
			  				->where('balance' , '>', 0)
			  				->where('deleted', 0)
			  				->first();

			  			if($scheddetail)
			  			{
			  				// echo ' bal: ' . $adjbal;
		  					if($scheddetail->balance >= $adjbal)
		  					{	
		  						db::table('studpayscheddetail')
				  					->where('id', $scheddetail->id)
				  					->update([
					  						// 'particulars' => $particulars,
					  						'amountpay' => $scheddetail->amountpay + $adjbal,
					  						'balance' => $scheddetail->balance - $adjbal,
					  						'deleted' => 0,
					  						'updatedby' => auth()->user()->id,
					  						'updateddatetime' => FinanceModel::getServerDateTime()
					  					]);


				  				$adjbal = 0;
		  					}
		  					else
		  					{
		  						db::table('studpayscheddetail')
				  					->where('id', $scheddetail->id)
				  					->update([
					  						// 'particulars' => $particulars,
					  						'amountpay' => $scheddetail->amountpay + $scheddetail->balance,
					  						'balance' => 0,
					  						'deleted' => 0,
					  						'updatedby' => auth()->user()->id,
					  						'updateddatetime' => FinanceModel::getServerDateTime()
					  					]);

				  				$adjbal -= $scheddetail->balance;

				  				if($adjbal > 0)
				  				{
				  					goto adjloop;
				  				}

		  					}
			  			}

	  				}
  				}
  				else
  				{

  					// return 'debit';
  					$checkadjledger = db::table('studledger')
  						// ->select('studledger.*, aaa')
  						->where('studid', $studid)
  						->where(function($q) use($levelid){
			  				if($levelid == 14 || $levelid ==15)
			  				{
			  					$q->where('semid', FinanceModel::getSemID());
			  				}
			  				if($levelid >= 17 && $levelid <=20)
			  				{
			  					$q->where('semid', FinanceModel::getSemID());
			  				}
			  			})
		  				->where('syid', FinanceModel::getSYID())
		  				->where('particulars', 'like', '%ADJ%')
		  				->where('ornum', $adj->id)
		  				->where('deleted', 0)
		  				->first();
		  			// return $checkadjledger;

		  			if($checkadjledger)
		  			{
		  				
		  			}
		  			else
		  			{
		  				db::table('studledger')
		  					->insert([
		  						'studid' => $studid,
		  						'semid' => FinanceModel::getSemID(),
		  						'syid' => FinanceModel::getSYID(),
		  						'classid' => $adj->classid,
		  						'particulars' => 'ADJ: ' . $adj->description,
		  						'amount' => $adj->amount,
		  						'pschemeid' => $adj->mop,
		  						'ornum' => $adj->id,
		  						'deleted' => 0,
		  						'createdby' => auth()->user()->id,
		  						'createddatetime' => FinanceModel::getServerDateTime()
		  					]);
		  			}

  					$modeofpayment = db::table('paymentsetup')
  						->select('noofpayment', 'paymentsetupdetail.*')
  						->join('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
  						->where('paymentsetup.id', $adj->mop)
  						->where('paymentsetupdetail.deleted', 0)
  						->get();

  					$divadjbal = $adjbal / $modeofpayment[0]->noofpayment;
  					$divadjbal = number_format($divadjbal, '2', '.', '');
  					$totaladjbal = 0;

  					foreach($modeofpayment as $mop)
  					{
  						$scheddetail = db::table('studpayscheddetail')
			  				->where('studid', $studid)
			  				->where(function($q) use($levelid){
				  				if($levelid == 14 || $levelid ==15)
				  				{
				  					$q->where('semid', FinanceModel::getSemID());
				  				}
				  				if($levelid >= 17 && $levelid <=20)
				  				{
				  					$q->where('semid', FinanceModel::getSemID());
				  				}
				  			})
			  				->where('syid', FinanceModel::getSYID())
			  				->where('classid', $adj->classid)
			  				->where('paymentno', $mop->paymentno)
			  				->where('deleted', 0)
			  				->first();

			  			if($scheddetail)
			  			{
				  			if($mop->paymentno < $mop->noofpayment)
				  			{
					  			db::table('studpayscheddetail')
					  				->where('id', $scheddetail->id)
					  				->update([
					  					'amount' => $scheddetail->amount + $divadjbal,
					  					'balance' => $scheddetail->balance + $divadjbal,
					  					'updateddatetime' => FinanceModel::getServerDateTime(),
					  					'updatedby' => auth()->user()->id
					  				]);
					  			$totaladjbal += $divadjbal;
				  			}
				  			else
				  			{
				  				$totaladjbal = $adj->amount - $totaladjbal;

				  				db::table('studpayscheddetail')
					  				->where('id', $scheddetail->id)
					  				->update([
					  					'amount' => $scheddetail->amount + $totaladjbal,
					  					'balance' => $scheddetail->balance + $totaladjbal,
					  					'updateddatetime' => FinanceModel::getServerDateTime(),
					  					'updatedby' => auth()->user()->id
					  				]);
				  			}
				  		}
				  		else
				  		{
				  			if($mop->paymentno < $mop->noofpayment)
				  			{
					  			db::table('studpayscheddetail')
					  				->insert([
					  					'studid' => $studid,
					  					'enrollid' => $enrollstud->id,
					  					'syid' => FinanceModel::getSYID(),
					  					'semid' => FinanceModel::getSemID(),
					  					'classid' => $adj->classid,
					  					'paymentno' => $mop->paymentno,
					  					'particulars' => 'ADJ: ' . $adj->description,
					  					'duedate' => $mop->duedate,
					  					'amount' => $divadjbal,
					  					'balance' => $divadjbal,
					  					'createddatetime' => FinanceModel::getServerDateTime(),
					  					'createdby' => auth()->user()->id
					  				]);
					  			$totaladjbal += $divadjbal;
					  		}
					  		else
					  		{
					  			$totaladjbal = $adj->amount - $totaladjbal;

				  				db::table('studpayscheddetail')
					  				->insert([
					  					'studid' => $studid,
					  					'enrollid' => $enrollstud->id,
					  					'syid' => FinanceModel::getSYID(),
					  					'semid' => FinanceModel::getSemID(),
					  					'classid' => $adj->classid,
					  					'paymentno' => $mop->paymentno,
					  					'particulars' => 'ADJ: ' . $adj->description,
					  					'duedate' => $mop->duedate,
					  					'amount' => $totaladjbal,
					  					'balance' => $totaladjbal,
					  					'updateddatetime' => FinanceModel::getServerDateTime(),
					  					'updatedby' => auth()->user()->id
					  				]);
					  		}
				  		}

			  			
  					}

  				}
	  			
	  		}
	  		// return 22222;
	  		//Check Adjustment

	  		//Check book entries
	  		$besetup = db::table('bookentrysetup')
	  			->first();

	  		if($besetup)
	  		{
	  			$particulars = db::table('itemclassification')->where('id', $besetup->classid)->first()->description;
	  		}

	  		$bookentries = db::table('bookentries')
	  			->where('studid', $studid)
	  			->where('bestatus', 'APPROVED')
	  			->where('deleted', 0)
	  			->get();

	  		foreach($bookentries as $book)
	  		{
	  			$mop = db::table('paymentsetup')
	  				->select(db::raw('paymentsetup.id, noofpayment, paymentno, duedate, noofpayment'))
	  				->join('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
	  				->where('paymentsetup.id', $besetup->mopid)
	  				->first();


	  			if($mop)
	  			{
	  				if($mop->noofpayment > 1)
		  			{
		  				$divbal = $book->amount / $mop->noofpayment;
						$divbal = number_format($divbal, 2, '.', '');
						$totalBE = 0;

		  				$paymentsetup = db::table('paymentsetupdetail')
		  					->where('paymentid', $mop->id)
		  					->where('deleted', 0)
		  					->get();

						foreach($paymentsetup as $mopd)
						{
							if($mopd->paymentno != $mop->noofpayment)
							{
								$totalBE += $divbal;
								db::table('studpayscheddetail')
									->insert([
										'studid' => $studid,
										'enrollid' => $enrollstud->id,
										'semid' => FinanceModel::getSemID(),
										'syid' => FinanceModel::getSYID(),
										'classid' => $book->classid,
										'paymentno' => $mopd->paymentno,
										'particulars' => $particulars,
										'duedate' => $mopd->duedate,
										'amount' => $divbal,
										'balance' => $divbal,
										'createdby' => auth()->user()->id,
										'createddatetime' => FinanceModel::getServerDateTime()
									]);
							}
							else
							{
								$r_bal = $book->amount - $totalBE;

								db::table('studpayscheddetail')
									->insert([
										'studid' => $studid,
										'enrollid' => $enrollstud->id,
										'semid' => FinanceModel::getSemID(),
										'syid' => FinanceModel::getSYID(),
										'classid' => $book->classid,
										'paymentno' => $mopd->paymentno,
										'particulars' => $particulars,
										'duedate' => $mopd->duedate,
										'amount' => $r_bal,
										'balance' => $r_bal,
										'createdby' => auth()->user()->id,
										'createddatetime' => FinanceModel::getServerDateTime()
									]);
							}
						}							


		  			}	
		  			else
		  			{
		  				

		  				db::table('studpayscheddetail')
		  					->insert([
		  						'studid' => $studid,
		  						'semid' => FinanceModel::getSemID(),
		  						'syid' => FinanceModel::getSYID(),
		  						'classid' => $book->classid,
		  						'paymentno' => 1,
		  						'particulars' => $particulars,
		  						'amount' => $book->amount,
		  						'balance' => $book->amount,
		  						'deleted' => 0,
		  						'updatedby' => auth()->user()->id,
		  						'updateddatetime' => FinanceModel::getServerDateTime()
		  					]);
		  			}
	  			}
	  		}

	  		//Check book entries


	  		//Balance Forwarding
	  // 		$divbal = 0;
	  // 		$balforwardsetup = db::table('balforwardsetup')
			// 	->first();

			// $balforwardledger = db::table('studledger')
			// 	->where('studid', $studid)
			// 	->where('syid', FinanceModel::getSYID())
			// 	->where('semid', FinanceModel::getSemID())
			// 	->where('deleted', 0)
			// 	->where('classid', $balforwardsetup->classid)
			// 	->first();
			

			// if($balforwardledger)
			// {
			// 	if($balforwardledger->createddatetime > $dateenrolled)
			// 	{
			// 		$balforwardpayment = db::table('chrngtrans')
			// 			->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
			// 			->where('studid', $studid)
			// 			->where('classid', $balforwardledger->classid)
			// 			->where('cancelled', 0)
			// 			->count();

			// 		// return $balforwardpayment;

			// 		if($balforwardpayment == 0)
			// 		{
			// 			$paymentsetup = db::table('paymentsetup')
			// 				->join('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
			// 				->where('paymentsetup.id', $balforwardsetup->mopid)
			// 				->where('paymentsetupdetail.deleted', 0)
			// 				// ->where('syid', FinanceModel::getSYID())
			// 				// ->where('semid', FinanceModel::getSemID())
			// 				->get();

			// 			if(count($paymentsetup) > 0)
			// 			{
			// 				$divbal = $balforwardledger->amount / $paymentsetup[0]->noofpayment;
			// 				$divbal = number_format($divbal, 2, '.', '');
			// 				$totalbalforward = 0;

			// 				foreach($paymentsetup as $mop)
			// 				{
			// 					if($mop->paymentno != $mop->noofpayment)
			// 					{
			// 						$totalbalforward += $divbal;
			// 						db::table('studpayscheddetail')
			// 							->insert([
			// 								'studid' => $studid,
			// 								'enrollid' => $enrollstud->id,
			// 								'semid' => FinanceModel::getSemID(),
			// 								'syid' => FinanceModel::getSYID(),
			// 								'classid' => $balforwardsetup->classid,
			// 								'paymentno' => $mop->paymentno,
			// 								'particulars' => $balforwardledger->particulars,
			// 								'duedate' => $mop->duedate,
			// 								'amount' => $divbal,
			// 								'createdby' => auth()->user()->id,
			// 								'createddatetime' => FinanceModel::getServerDateTime()
			// 							]);
			// 					}
			// 					else
			// 					{
			// 						$r_bal = $balforwardledger->amount - $totalbalforward;

			// 						db::table('studpayscheddetail')
			// 							->insert([
			// 								'studid' => $studid,
			// 								'enrollid' => $enrollstud->id,
			// 								'semid' => FinanceModel::getSemID(),
			// 								'syid' => FinanceModel::getSYID(),
			// 								'classid' => $balforwardsetup->classid,
			// 								'paymentno' => $mop->paymentno,
			// 								'particulars' => $balforwardledger->particulars,
			// 								'duedate' => $mop->duedate,
			// 								'amount' => $r_bal,
			// 								'createdby' => auth()->user()->id,
			// 								'createddatetime' => FinanceModel::getServerDateTime()
			// 							]);
			// 					}
			// 				}
			// 			}

			// 		}
			// 	}
			// }
	  		//Balance Forwarding



	  		//Reset Payment Sched

			$studledger = db::table('studledger') //for VOid transactions
				->where('studid', $studid)
				->where('syid', FinanceModel::getSYID())
	  			->where('semid', FinanceModel::getSemID())
	  			->where('deleted', 0)
	  			->where('classid', null)
	  			->where('transid', '!=', null)
	  			->get();

	  		// return $studledger;

	  		foreach($studledger as $ledger)
	  		{
	  			$trans = db::table('chrngtrans')
	  					->where('id', $ledger->transid)
	  					->where('cancelled', 1)
	  					->first();

  				// if(! $trans)
  				// {
  				// 	$trans = db::table('chrngtrans')
  				// 	->where('ornum', 'like', '%'.$ledger->ornum.'%')
  				// 	->where('cancelled', 1)
  				// 	->first();
  				// }

	  			$ledgerid = $ledger->id;

	  			if($trans)
	  			{
	  				if($trans->cancelled == 1)
	  				{
	  					db::table('studledger')
	  						->where('id', $ledger->id)
	  						->update([
	  							'void' => 1,
	  							'voiddatetime' => $trans->cancelleddatetime,
	  							'voidby' => $trans->cancelledby
	  						]);
	  				}
	  				else
	  				{
	  					db::table('studledger')
	  						->where('id', $ledger->id)
	  						->update([
	  							'void' => 0,
	  							'updatedby' => auth()->user()->id,
	  							'updateddatetime' => FinanceModel::getServerDateTime()
	  						]);	
	  				}
	  			}
	  			else
	  			{
	  				db::table('studledger')
  						->where('id', $ledgerid)
  						->update([
  							'void' => 0,
  							'updatedby' => auth()->user()->id,
  							'updateddatetime' => FinanceModel::getServerDateTime()
  						]);	
	  			}
	  			
	  		}



	  		$studledger = db::table('studledger')
	  			->where('studid', $studid)
	  			->where('syid', FinanceModel::getSYID())
	  			->where(function($q) use($levelid){
	  				if($levelid == 14 || $levelid ==15)
	  				{
	  					$q->where('semid', FinanceModel::getSemID());
	  				}
	  				if($levelid >= 17 && $levelid <=20)
	  				{
	  					$q->where('semid', FinanceModel::getSemID());
	  				}
	  			})
	  			->where('deleted', 0)
	  			->where('void', 0)
	  			->where('classid', null)
	  			->where('particulars', 'like', '%TUITION/BOOKS/OTH%')
	  			->get();

	  		$detailbal = 0;
	  		$detailclassid = 0;

	  		if(count($studledger) > 0)
	  		{
	  			foreach($studledger as $ledger)
	  			{
	  				// echo ' ledgerid: ' . $ledger->id;
	  				$transdetail = db::table('chrngtransdetail')
	  					->select('chrngtransdetail.*', 'cancelled', 'ornum')
	  					->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
	  					->where('chrngtransid', $ledger->transid)
	  					->where('cancelled', 0)
	  					->where('itemkind', 0)
	  					->get();


	  				if(count($transdetail) == 0)
	  				{
	  					$transdetail = db::table('chrngtransdetail')
		  					->select('chrngtransdetail.*', 'cancelled', 'ornum')
		  					->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
		  					->where('ornum', 'like', '%'.$ledger->ornum.'%')
		  					->where('studid', $studid)
		  					->where('cancelled', 0)
		  					->where('itemkind', 0)
		  					->get();
	  				}


	  				// return $transdetail;

	  				// echo ' transdetail: ' . $transdetail. '; ';


	  				foreach($transdetail as $detail)
	  				{
	  					// echo ' classid: ' . $detail->classid . '; ';
	  					$detailclassid = $detail->classid;

	  					$scheddetail = db::table('studpayscheddetail')
	  						->where('studid', $studid)
	  						->where('deleted', 0)
	  						->where('syid', FinanceModel::getSYID())
	  						->where(function($q) use($levelid){
				  				if($levelid == 14 || $levelid ==15)
				  				{
				  					$q->where('semid', FinanceModel::getSemID());
				  				}
				  				if($levelid >= 17 && $levelid <=20)
				  				{
				  					$q->where('semid', FinanceModel::getSemID());
				  				}
				  			})
	  						->where('classid', $detail->classid)
	  						->where('balance', '>', 0)
	  						->first();

	  						// echo ' scheddetail: ' . $scheddetail->id
	  						// echo ' detailbal: ' . $detailbal

	  					if($scheddetail)
	  					{
	  						$detailbal += $detail->amount;
	  						echo ' scheddetailid: ' . $scheddetail->id;
	  						if($detailbal > $scheddetail->balance)
	  						{
			  					db::table('studpayscheddetail')
			  						->where('id', $scheddetail->id)
			  						->update([
			  							'amountpay' => $scheddetail->amountpay + $scheddetail->balance, //$detail->amount,
			  							'balance' => 0, 
			  							'updateddatetime' => FinanceModel::getServerDateTime(),
			  							'updatedby' => auth()->user()->id
			  						]);

			  					$detailbal -= $scheddetail->balance;

			  					echo ' 1st: ' . $detailbal;
	  						}
	  						else
	  						{

	  							db::table('studpayscheddetail')
			  					->where('id', $scheddetail->id)
	  							->update([
			  							'amountpay' => $scheddetail->amountpay + $detailbal,
			  							'balance' => $scheddetail->balance - $detailbal, 
			  							'updateddatetime' => FinanceModel::getServerDateTime(),
			  							'updatedby' => auth()->user()->id
			  						]);

	  							$detailbal = 0;

	  							echo ' 2nd: ' . $detailbal;
	  						}
	  						// echo ' scheddetail: ' . $scheddetail->id;
	  						// echo ' detailbal: ' . $detailbal . '; ';
	  						// echo ' detailid: ' . $detail->id . '; ';

	  						if($detailbal > 0)
	  						{
	  							clearZero:

	  							$scheddetail = db::table('studpayscheddetail')
	  								// ->select('studpayscheddetail.*', 'aaa')
			  						->where('studid', $studid)
			  						->where('deleted', 0)
			  						->where('syid', FinanceModel::getSYID())
			  						->where(function($q) use($levelid){
						  				if($levelid == 14 || $levelid ==15)
						  				{
						  					$q->where('semid', FinanceModel::getSemID());
						  				}
						  				if($levelid >= 17 && $levelid <=20)
						  				{
						  					$q->where('semid', FinanceModel::getSemID());
						  				}
						  			})
			  						->where('classid', $detailclassid)
			  						->where('balance', '>', 0)
			  						->first();

			  					echo ' classid: ' . $detail->classid . '; ';
			  					if($scheddetail)
			  					{
			  						// $detailbal += $detail->amount;
			  						// echo ' scheddetailid: ' . $scheddetail->id;
			  						
			  						if($detailbal >= $scheddetail->balance)
			  						{
			  							echo ' scheddetailid: ' . $scheddetail->id;
					  					db::table('studpayscheddetail')
					  						->where('id', $scheddetail->id)
					  						->update([
					  							'amountpay' => $scheddetail->amountpay + $scheddetail->balance, //$detail->amount,
					  							'balance' => 0, 
					  							'updateddatetime' => FinanceModel::getServerDateTime(),
					  							'updatedby' => auth()->user()->id
					  						]);

					  					$detailbal -= $scheddetail->balance;
					  					echo ' 3rd: ' . $detailbal;
			  						}
			  						else
			  						{

			  							db::table('studpayscheddetail')
					  					->where('id', $scheddetail->id)
			  							->update([
					  							'amountpay' => $scheddetail->amountpay + $detailbal,
					  							'balance' => $scheddetail->balance - $detailbal, 
					  							'updateddatetime' => FinanceModel::getServerDateTime(),
					  							'updatedby' => auth()->user()->id
					  						]);

			  							$detailbal = 0;
			  						}

			  						echo ' 4th: ' . $detailbal;
			  						// echo ' scheddetail: ' . $scheddetail->id;
			  						// echo ' detailbal: ' . $detailbal;
			  					}
			  					else
			  					{
			  						echo ' secondClear: ' . $detailbal;
			  						$scheddetail = db::table('studpayscheddetail')
	  								// ->select('studpayscheddetail.*', 'aaa')
				  						->where('studid', $studid)
				  						->where('deleted', 0)
				  						->where('syid', FinanceModel::getSYID())
				  						->where(function($q) use($levelid){
							  				if($levelid == 14 || $levelid ==15)
							  				{
							  					$q->where('semid', FinanceModel::getSemID());
							  				}
							  				if($levelid >= 17 && $levelid <=20)
							  				{
							  					$q->where('semid', FinanceModel::getSemID());
							  				}
							  			})
				  						// ->where('classid', $detailclassid)
				  						->where('balance', '>', 0)
				  						->orderBy('id', 'ASC')
				  						->first();

				  					if($scheddetail)
				  					{
				  						if($detailbal >= $scheddetail->balance)
				  						{
						  					db::table('studpayscheddetail')
						  						->where('id', $scheddetail->id)
						  						->update([
						  							'amountpay' => $scheddetail->amountpay + $scheddetail->balance, //$detail->amount,
						  							'balance' => 0, 
						  							'updateddatetime' => FinanceModel::getServerDateTime(),
						  							'updatedby' => auth()->user()->id
						  						]);

						  					$detailbal -= $scheddetail->balance;
						  					echo ' 5th: ' . $detailbal;
				  						}
				  						else
				  						{

				  							db::table('studpayscheddetail')
						  					->where('id', $scheddetail->id)
				  							->update([
						  							'amountpay' => $scheddetail->amountpay + $detailbal,
						  							'balance' => $scheddetail->balance - $detailbal, 
						  							'updateddatetime' => FinanceModel::getServerDateTime(),
						  							'updatedby' => auth()->user()->id
						  						]);

				  							$detailbal = 0;
				  							echo ' 6th: ' . $detailbal;
				  						}
				  					}
			  					}

			  					echo ' lastdetailbal: ' . $detailbal;

			  					if($detailbal > 0)
			  					{
			  						echo ' chkdetailbal: ' . $detailbal;
			  						goto clearZero;
			  					}

	  						}
	  					}

	  					// echo ' detailbal: ' . $detailbal;
	  				}
	  			}
	  		}




			return 1;
		}
	}

	public function genstud(Request $request)
	{
		if($request->ajax())
		{
			$levelid = $request->get('levelid');
			$grantee = $request->get('grantee');
			$course = $request->get('course');
			$syid = $request->get('syid');
			$semid = $request->get('semid');

			// return $grantee; // . ' ' . $course;

			$enrolled = '';

			if($levelid == 14 || $levelid == 15)
			{
				$enrolled = 'sh_enrolledstud';
			}
			elseif($levelid >= 17 && $levelid <= 20)
			{
				$enrolled = 'college_enrolledstud';
			}
			else
			{
				$enrolled = 'enrolledstud';
			}


			$studinfo = db::table('studinfo')
				->select(db::raw('studinfo.id, lastname, firstname, middlename, sid'))
				->join($enrolled, 'studinfo.id', '=', $enrolled . '.studid')
				->where($enrolled . '.studstatus', '!=', 0)
				->where($enrolled . '.deleted', 0)
				->where($enrolled . '.levelid', $levelid)
				->where($enrolled . '.syid', $syid)
				->where(function($q) use($grantee, $levelid, $course, $semid){
					if($levelid >= 17 && $levelid <= 21)
					{
						if($course > 0)
						{
							$q->where('college_enrolledstud.courseid', $course);
						}

						$q->where('college_enrolledstud.semid', $semid);
					}	
					elseif($levelid == 14 || $levelid == 15)
					{
						if($grantee > 0)
						{
							$q->where('grantee', $grantee);
						}

						if($semid == 3)
						{
							$q->where('sh_enrolledstud.semid', $semid);
						}
						else
						{
							if(db::table('schoolinfo')->first()->shssetup == 0)
							{
								$q->where('sh_enrolledstud.semid', $semid);		
							}
						}
					}
					else
					{
						if($grantee > 0)
						{
							$q->where('grantee', $grantee);
						}

						if($semid == 3)
						{
							$q->where('ghssemid', $semid);
						}
					}
				})
				->orderBy('lastname', 'ASC')
				->orderBy('firstname', 'ASC')
				->get();

			$ledgeramount = 0;

			$studcount = count($studinfo);

			$studlist = '';

			foreach($studinfo as $stud)
			{

				$ledger = db::table('studledger')
					->select(DB::raw('SUM(amount) - SUM(payment) as amount'))
					->where('studid', $stud->id)
					->where('syid', FinanceModel::getSYID())
					->where(function($q) use($levelid){
		  				if($levelid == 14 || $levelid ==15)
		  				{
		  					$q->where('semid', FinanceModel::getSemID());
		  				}
		  				if($levelid >= 17 && $levelid <=20)
		  				{
		  					$q->where('semid', FinanceModel::getSemID());
		  				}
		  			})
					->where('deleted', 0)
					->groupBy('studid')
					->first();

				if($ledger)
				{
					$ledgeramount = $ledger->amount;
				}
					


				$name = $stud->lastname . ', '. $stud->firstname . ' ' . $stud->middlename ;

				$studlist .='
					<tr data-id="'.$stud->id.'">
						<td>'.$name.'</td>
						<td class="td-amount">'.number_format($ledgeramount, 2).'</td>
						<td><button class="btn-reset btn btn-warning btn-block" data-id="'.$stud->id.'">RESET</button></td>
					</tr>
				';
			}

			$fees = db::table('tuitionheader')
				->where('levelid', $levelid)
				->where(function($q) use($levelid){
	  				if($levelid == 14 || $levelid ==15)
	  				{
	  					$q->where('semid', FinanceModel::getSemID());
	  				}
	  				if($levelid >= 17 && $levelid <=20)
	  				{
	  					$q->where('semid', FinanceModel::getSemID());
	  				}
	  			})
				->where('syid', FinanceModel::getSYID())
				->where('deleted', 0)
				->get();

			$feelist ='';

			foreach($fees as $fee)
			{
				$feelist .='
					<option value="'.$fee->id.'">'.$fee->description.' - '.$fee->grantee.'</option>
				';
			}

			$data = array(
				'studlist' => $studlist,
				'feelist' => $feelist,
				'studcount' => $studcount
			);

			echo json_encode($data);
		}
	}
	
	public function calcLedger(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');
			
			$stud = db::table('studinfo')
				->where('id', $studid)
				->first();
			
			$levelid = $stud->levelid;

			$ledgeramount = db::table('studledger')
					->select(DB::raw('SUM(amount) - SUM(payment) as amount'))
					->where('studid', $studid)
					->where('syid', FinanceModel::getSYID())
					->where(function($q) use($levelid){
		  				if($levelid == 14 || $levelid ==15)
		  				{
		  					$q->where('semid', FinanceModel::getSemID());
		  				}
		  				if($levelid >= 17 && $levelid <=20)
		  				{
		  					$q->where('semid', FinanceModel::getSemID());
		  				}
		  			})
					->where('deleted', 0)
					->groupBy('studid')
					->first()
					->amount;

			return number_format($ledgeramount, 2);
		}
	}

	public function clearledger(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');

			$studledger = db::table('studledger')
				->where('studid', $studid)
				->where('deleted', 0)
				->where('syid', FinanceModel::getSYID())
				->where('semid', FinanceModel::getSemID())
				->get();

			foreach($studledger as $ledger)
			{
				$tuitiondetail = db::table('tuitiondetail')
					->where('classificationid', $ledger->classid)
					->where('deleted', 0)
					->count();

				if($tuitiondetail > 0)
				{
					db::table('studledger')
						->where('id', $ledger->id)
						->update([
							'deleted' => 1,
							'deleteddatetime' => FinanceModel::getServerDateTime(),
							'deletedby' => auth()->user()->id
						]);

					db::table('studpayscheddetail')
						->where('studid', $studid)
						->where('syid', FinanceModel::getSYID())
						->where('semid', FinanceModel::getSemID())
						->where('classid', $ledger->classid)
						->where('deleted', 0)
						->update([
							'deleted' => 1,
							'updateddatetime' => FinanceModel::getServerDateTime(),
							'updatedby' => auth()->user()->id
						]);

					$balforwardsetup = db::table('balforwardsetup')
						->first();

					$studpayscheddetail = db::table('studpayscheddetail')
						->where('studid', $studid)
						->where('syid', FinanceModel::getSYID())
						->where('semid', FinanceModel::getSemID())
						->where('classid', $balforwardsetup->classid)
						->where('deleted', 0)
						->get();

					foreach($studpayscheddetail as $sched)
					{
						db::table('studpayscheddetail')						
							->where('id', $sched->id)
							->update([
								'amountpay' => 0,
								'balance' => $sched->amount,
								'updatedby' => auth()->user()->id,
								'updateddatetime' => FinanceModel::getServerDateTime()
							]);
					}
				}
			}


		}

		return 1;
	}

	public function fixledgerrow(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');
			$ledgerid = $request->get('ledgerid');

			// return $ledgerid;

			$ledger = db::table('studledger')
				->where('id', $ledgerid)
				->first();

			if($ledger)
			{
				$chrngtrans = DB::table('chrngtrans')
					->where('studid', $studid)
					->where('ornum', $ledger->ornum)
					->where('cancelled', 0)
					->first();

				db::table('studledger')
					->where('id', $ledgerid)
					->update([
						'payment' => $chrngtrans->amountpaid,
						'updateddatetime' => FinanceModel::getServerDateTime(),
						'updatedby' => auth()->user()->id
					]);	

				return 1;
			}
			else
			{
				return 5;	
			}
		}
	}

	public function removeledgerrow(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');
			$ledgerid = $request->get('ledgerid');

			$ledger = db::table('studledger')
				->where('id', $ledgerid)
				->first();

			db::table('studledger')
				->where('id', $ledgerid)
				->update([
					'deleted' => 1,
					'deletedby' => auth()->user()->id,
					'deleteddatetime' => FinanceModel::getServerDateTime()
				]);



		}
	}

	public function genitemizedinfo(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');

			$stud = db::table('studinfo')
				->select('studinfo.id', 'sid', 'grantee.description as grantee')
				->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
				->where('studinfo.id', $studid)
				->first();

			$transitems = db::table('chrngtransitems')
				->where('studid', $studid)
				->where('syid', FinanceModel::getSYID())
				->where('semid', FinanceModel::getSemID())
				->where('deleted', 0)
				->get();

			$titemlist = '';
			$transitemamount = 0;
			foreach($transitems as $item)
			{
				$titemlist .='
					<tr data-id="'.$item->id.'">
						<td>'.$item->studid.'</td>
						<td>'.$item->ornum.'</td>
						<td>'.$item->itemid.'</td>
						<td>'.$item->classid.'</td>
						<td class="text-right">'.number_format($item->amount, 2).'</td>
					</tr>
				';

				$transitemamount += $item->amount;
			}

			$titemlist .='
				<tr>
					<td colspan="4" class="text-right text-bold">TOTAL: </td>
					<td colspan="4" class="text-right text-bold">'.number_format($transitemamount, 2).'</td>
				</tr>
			';

			$ledgeritemized = db::table('studledgeritemized')
				->where('studid', $studid)
				->where('syid', FinanceModel::getSYID())
				->where('semid', FinanceModel::getSemID())
				->where('deleted', 0)
				->get();


			$ledgerlist = '';
			$totalamount = 0;
			$totalitemamount = 0;

			foreach($ledgeritemized as $ledgeritem)
			{
				$totalamount += $ledgeritem->totalamount;
				$totalitemamount += $ledgeritem->itemamount;

				$ledgerlist .= '
					<tr data-id="'.$ledgeritem->id.'">
						<td>'.$ledgeritem->studid.'</td>
						<td>'.$ledgeritem->itemid.'</td>
						<td>'.$ledgeritem->classificationid.'</td>
						<td class="text-right">'.number_format($ledgeritem->totalamount,2).'</td>
						<td class="text-right">'.number_format($ledgeritem->itemamount,2).'</td>
					</tr>
				';
			}

			$ledgerlist .= '
				<tr>
					<td colspan="3" class="text-bold text-right">TOTAL: </td>
					<td class="text-right">'.number_format($totalamount,2).'</td>
					<td class="text-right">'.number_format($totalitemamount,2).'</td>
				</tr>
			';


			$data = array(
				'itemlist' => $titemlist,
				'ledgerlist' => $ledgerlist,
				'sid' => $stud->sid,
				'grantee' => $stud->grantee
			);



			echo json_encode($data);
		}
	}

	public function ledgeritemizedreset(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');

			FinanceModel::ledgeritemizedreset($studid);
		}

		return 1;
	}

	public function transitemsreset(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');

			// return $studid;

			FinanceModel::transitemsreset($studid);

		}
	}

	public function transitemstruncate(Request $request)
	{
		if($request->ajax())
		{

			$studinfo = db::table('studinfo')
				->select('id')
				->where('studstatus', '!=', 0)
				->where('deleted', 0)
				->get();

			foreach($studinfo as $stud)
			{
				FinanceModel::transitemsreset($stud->id);
			}

			return 1;
		}
	}

	public function ledgeritemizedtruncate(Request $request)
	{
		if($request->ajax())
		{

			$studinfo = db::table('studinfo')
				->select('id')
				->where('studstatus', '!=', 0)
				->where('deleted', 0)
				->get();

			foreach($studinfo as $stud)
			{
				FinanceModel::ledgeritemizedreset($stud->id);
				// FinanceModel::transitemsreset($stud->id);
			}

			return 1;
		}
	}

	public function adj_search(Request $request)
	{
		if($request->ajax())
		{
			$levelid = $request->get('levelid');
			$filter = $request->get('filter');

			$adjstud = db::table('adjustments')
				->select(db::raw('studinfo.sid, adjustments.id as adjid, adjustmentdetails.id as adjdetailid, lastname, firstname, middlename, suffix, levelname, isdebit, iscredit, amount, refnum, description'))
				->join('adjustmentdetails', 'adjustments.id', '=', 'adjustmentdetails.headerid')
				->join('studinfo', 'adjustmentdetails.studid', '=', 'studinfo.id')
				->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
				->where('adjustmentdetails.deleted', 0)
				->where(function($q) use($levelid){
					if($levelid > 0)
					{
						$q->where('levelid', $levelid, $filter);
					}
				})
				->where(function($q) use($filter){
					$q->where('refnum', 'like', '%'.$filter.'%')
						->orWhere('description', 'like', '%'.$filter.'%');
				})
				->get();


			$list = '';

			foreach($adjstud as $adj)
			{
				$name = $adj->lastname . ', ' . $adj->firstname . ' ' . $adj->middlename;
				$iscredit = '';

				if($adj->iscredit == 1)
				{
					$iscredit = 'CREDIT';					
				}
				else
				{
					$iscredit = 'DEBIT';
				}

				$list .='
					<tr data-id="'.$adj->adjdetailid.'">
						<td>'.$adj->sid.'</td>
						<td>'.$name.'</td>
						<td>'.$adj->refnum.'</td>
						<td>'.$adj->description.'</td>
						<td>'.$adj->amount.'</td>
						<td>'.$iscredit.'</td>
					</tr>
				';
			}

			$data = array(
				'list' => $list,
				'studcount' => count($adjstud)
			);

			echo json_encode($data);

		}
	}

	public function adj_removedetail(Request $request)
	{
		if($request->ajax())
		{
			$dataid = $request->get('dataid');

			$adjdetail = db::table('adjustmentdetails')
				->where('id', $dataid)
				->first();

			db::table('adjustmentdetails')
				->where('id', $dataid)
				->update([
					'deleted' => 1,
					'deleteddatetime' => FinanceModel::getServerDateTime(),
					'deletedby' => auth()->user()->id
				]);


			db::table('studledger')
				->where('ornum', $adjdetail->headerid)
				->where('studid', $adjdetail->studid)
				->where('particulars', 'like', '%ADJ:%')
				->update([
					'deleted' => 1,
					'deletedby' => auth()->user()->id,
					'deleteddatetime' => FinanceModel::getServerDateTime()
				]);


			return 1;
		}
	}

	public function lockfees(Request $request)
	{
		
		$lockfees = $request->get('lockfees');

		db::table('schoolinfo')
			->update([
				'lockfees' => $lockfees
			]);

		return back();
		
	}

	public function fwd_gennegstud(Request $request)
	{
		if($request->ajax())
		{
			$balforwardsetup = db::table('balforwardsetup')->first();

			$studledger = db::table('studledger')
				->select(db::raw('studinfo.id, sid, lastname, firstname, middlename, amount, classid, levelname'))
				->join('studinfo', 'studledger.studid', '=', 'studinfo.id')
				->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
				->where('amount', '<', 0)
				->where('studledger.deleted', 0)
				->where('classid', $balforwardsetup->classid)
				->get();

			$list = '';

			foreach($studledger as $stud)
			{
				$name = $stud->sid . ' - ' . $stud->lastname . ', ' . $stud->firstname . ' ' . $stud->middlename . ' ' . $stud->levelname;

				$list .='
					<option value="'.$stud->id.'">'.$name.'</option>
				';
			}

			$data = array(
				'list' => $list
			);

			echo json_encode($data);
		}
	}

	public function fwd_genstudinfo(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');
			$bal = 0;
			$payschedbal =0;
			$totalamount = 0;
			$totalpayment = 0;
			$totalschedamount = 0;
			$totalschedpayamount = 0;
			$totalpayschedbal = 0;

			$studledger = db::table('studledger')
				->where('studid', $studid)
				->where('deleted', 0)
				->where('syid', FinanceModel::getSYID())
				->where('semid', FinanceModel::getSemID())
				->get();
			
			$ledgerlist = '';

			foreach($studledger as $ledger)
			{
				$totalamount += $ledger->amount;
				$totalpayment += $ledger->payment;
				$dt = date_create($ledger->createddatetime);
				$dt = date_format($dt, 'm-d-Y');
				$bal += $ledger->amount - $ledger->payment;
				$ledgerlist .='
					<tr>
						<td>'.$dt.'</td>
						<td>'.$ledger->particulars.'</td>
						<td class="text-right">'.number_format($ledger->amount,2).'</td>
						<td class="text-right">'.number_format($ledger->payment,2).'</td>
						<td class="text-right">'.number_format($bal, 2).'</td>
					</tr>
				';
			}

			$ledgerlist .='
				<tr>
					<td colspan="3" class="text-bold text-right">'.number_format($totalamount, 2).'</td>
					<td class="text-bold text-right">'.number_format($totalpayment, 2).'</td>
					<td class="text-bold text-right">'.number_format($bal, 2).'</td>
				</tr>
			';


			$studpayscheddetail = db::table('studpayscheddetail')
				->where('studid', $studid)
				->where('deleted', 0)
				->where('syid', FinanceModel::getSYID())
				->where('semid', FinanceModel::getSemID())
				->where('amount', '>', 0)
				->orderBy('duedate', 'ASC')
				->get();


			$payschedlist = '';

			foreach($studpayscheddetail as $paysched)
			{
				$payschedbal = $paysched->amount - $paysched->amountpay;
				$dmonth = date_create($paysched->duedate);
				$dmonth = date_format($dmonth, 'M');

				$totalschedamount += $paysched->amount;
				$totalschedpayamount += $paysched->amountpay;
				$totalpayschedbal += $payschedbal;

				$payschedlist .='
					<tr>
						<td>'.$paysched->classid.'</td>
						<td>'.$paysched->particulars.'</td>
						<td class="text-right">'.number_format($paysched->amount, 2).'</td>
						<td class="text-right">'.number_format($paysched->amountpay, 2).'</td>
						<td class="text-right">'.number_format($payschedbal, 2).'</td>
						<td>'.$dmonth.'</td>
						<td>'.$paysched->paymentno.'</td>
					</tr>
				';
			}

			$ledgerheader = '
				<tr>
					<td colspan="3" class="text-bold text-right">'.number_format($totalamount, 2).'</td>
					<td class="text-bold text-right">'.number_format($totalpayment, 2).'</td>
					<td class="text-bold text-right">'.number_format($bal, 2).'</td>
				</tr>
			';

			$payschedlist .='
				<tr>
					<td colspan="3" class="text-right text-bold">'.number_format($totalschedamount, 2).'</td>
					<td class="text-right text-bold">'.number_format($totalschedpayamount, 2).'</td>
					<td class="text-right text-bold">'.number_format($totalpayschedbal, 2).'</td>
					<td></td>
				</tr>
			';

			$headertotal = '
				<tr>
					<td colspan="3" class="text-right text-bold">'.number_format($totalschedamount, 2).'</td>
					<td class="text-right text-bold">'.number_format($totalschedpayamount, 2).'</td>
					<td class="text-right text-bold">'.number_format($totalpayschedbal, 2).'</td>
					<td></td>
				</tr>	
			';

			$data = array(
				'ledgerlist' => $ledgerheader . $ledgerlist,
				'payschedlist' => $headertotal . $payschedlist,
			);

			echo json_encode($data);
		}
	}

	public function fwd_fixnegativebal(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');

			$balforwardsetup = db::table('balforwardsetup')
				->first();


			$balfwdamount = db::table('studledger')
				->where('studid', $studid)
				->where('classid', $balforwardsetup->classid)
				->where('syid', FinanceModel::getSYID())
				->where('semid', FinanceModel::getSemID())
				->where('deleted', 0)
				->sum('amount');

			$fwdamount = $balfwdamount * (-1);

			$studpayscheddetail = db::table('studpayscheddetail')
				->where('studid', $studid)
				->where('syid', FinanceModel::getSYID())
				->where('semid', FinanceModel::getSemID())
				->where('deleted', 0)
				->where('balance', '>', 0)
				->orderBy('duedate')
				->get();

			$fwdbal = $fwdamount;

			foreach($studpayscheddetail as $paysched)
			{
				
				if($fwdbal > 0)
				{
					if($fwdbal > $paysched->balance)
					{
						db::table('studpayscheddetail')
							->where('id', $paysched->id)
							->update([
								'amountpay' => $paysched->amountpay + $paysched->balance,
								'balance' => 0,
								'updateddatetime' => FinanceModel::getServerDateTime(),
								'updatedby' => auth()->user()->id
							]);

						$fwdbal -= $paysched->balance;
					}
					else
					{
						db::table('studpayscheddetail')
							->where('id', $paysched->id)
							->update([
								'amountpay' => $paysched->amountpay + $fwdbal,
								'balance' => $paysched->balance - $fwdbal,
								'updateddatetime' => FinanceModel::getServerDateTime(),
								'updatedby' => auth()->user()->id
							]);

						$fwdbal = 0;
					}
				}

			}

			$studpayscheddetail = db::table('studpayscheddetail')
				->where('studid', $studid)
				->where('syid', FinanceModel::getSYID())
				->where('semid', FinanceModel::getSemID())
				->where('deleted', 0)
				->where('amount', '<', 0)
				->update([
					'deleted' => 0
					// 'deleteddatetime' => FinanceModel::getServerDateTime(),
					// 'deletedby' => auth()->user()->id
				]);

			return 1;
		}
	}

	public function dpfix_geninfo(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');

			$stud = db::table('studinfo')
				->where('id', $studid)
				->first();

			$levelid = $stud->levelid;

			if($studid != 0)
			{
				$chrngtrans = db::table('chrngtrans')
					->where('studid', $studid)
					->where('cancelled', 0)
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

				$translist = '';
				$or = '';
				$totaltrans=0;


				foreach($chrngtrans as $trans)
				{
					$tdate = date_create($trans->transdate);
					$tdate = date_format($tdate, 'm-d-Y');

					$totalDetail=0;

					$translist .= '
						<tr class=" trans-head trans" trans-id="'.$trans->id.'">
							<td>'.$tdate.'</td>
							<td>'.$trans->ornum.'</td>
							<td class="text-right">'.$trans->amountpaid.'</td>
						</tr>
					';

					// $transdetail = db::table('chrngtransdetail')
					// 	->where('chrngtransid', $trans->id)
					// 	->get();

					// foreach($transdetail as $detail)
					// {
					// 	$translist .='
					// 		<tr class="trans" trans-id="'.$trans->id.'">
					// 			<td></td>
					// 			<td></td>
					// 			<td>'.$detail->items.'</td>
					// 			<td class="text-right">'.number_format($detail->amount, 2).'</td>
					// 		</tr>		
					// 	';

					// 	$totalDetail += $detail->amount;
					// }

					$totaltrans += $trans->amountpaid;
					// $translist .='

					// 	<tr class="bg-primary" data-id="'.$trans->id.'">
					// 		<td colspan="3" class="text-right">TOTAL: </td>
					// 		<td class="text-right text-bold text-lg">'.number_format($trans->amountpaid, 2).'</td>
					// 	</tr>		
					// ';
				}

				$translist .='

						<tr class="bg-success" data-id="">
							<td colspan="2" class="text-right">GRAND TOTAL: </td>
							<td class="text-right text-bold text-lg">'.number_format($totaltrans, 2).'</td>
						</tr>		
					';


				$ledgerlist = '';

				$studledger = db::table('studledger')
					->where('studid', $studid)
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
					->where('deleted', 0)
					->orderBy('createddatetime', 'ASC')
					->get();

				$bal = 0;
				$debit=0;
				$credit=0;
				$totaldebit = 0;
				$totalcredit = 0;

				foreach($studledger as $ledger)
				{
					$cdate = date_create($ledger->createddatetime);
					$cdate = date_format($cdate, 'm-d-Y');
					$bal += $ledger->amount - $ledger->payment;
					$totaldebit += $ledger->amount;
					$totalcredit += $ledger->payment;

					if($ledger->amount > 0)
					{
						$debit = number_format($ledger->amount, 2);
					}
					else
					{
						$debit = '';
					}

					if($ledger->payment > 0)
					{
						$credit = number_format($ledger->payment, 2);
					}
					else
					{
						$credit = '';
					}

					$ledgerlist .='
						<tr>
							<td>'.$cdate.'</td>
							<td>'.$ledger->particulars.'</td>
							<td class="text-right">'.$debit.'</td>
							<td class="text-right">'.$credit.'</td>
							<td class="text-right">'.number_format($bal).'</td>
						</tr>
					';
				}

				$ledgerlist .='
					<tr class="bg-info">
						<td colspan="2" class="text-right text-bold">TOTAL:</td>
						<td class="text-right text-bold text-lg">'.number_format($totaldebit,2).'</td>
						<td class="text-right text-bold text-lg">'.number_format($totalcredit,2).'</td>
						<td class="text-right text-bold text-lg">'.number_format($bal,2).'</td>
					</tr>
				';

				$data = array(
					'translist' => $translist,
					'ledgerlist' => $ledgerlist
				);

				echo json_encode($data);
			}
		}
	}

	public function disbalance_genstud_list(Request $request)
	{
		if($request->ajax())
		{
			$levelid = $request->get('levelid');

			$studinfo = db::table('studinfo')
				->select('studinfo.id', 'sid', 'lastname', 'firstname', 'middlename', 'grantee.description as grantee', 'levelname', 'strandcode', 'levelid')
				->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
				->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
				->leftjoin('sh_strand', 'studinfo.strandid', '=', 'sh_strand.id')
				->where('studinfo.deleted', 0)
				->where('studstatus', '>', 0)
				->where(function($q) use($levelid){
					if($levelid != 0)
					{
						$q->where('levelid', $levelid);
					}
				})
				->orderBy('sortid', 'ASC')
				->orderBy('lastname', 'ASC')
				->orderBy('firstname', 'ASC')
				// ->limit(20)
				->get();
			$studcount = count($studinfo);

			$array_studlist = array();
			
			foreach($studinfo as $stud)
			{
				array_push($array_studlist, (object)[
					'studid' => $stud->id
				]);
			}

			$data = array(
				'studcount' => 'COUNT:' . $studcount,
				'count' => $studcount,
				'studlist' => $array_studlist
			);

			echo json_encode($data);
		}
	}

	public function disbalance_genstud(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');
			$syid = $request->get('syid');
			$semid = $request->get('semid');

			$stud = db::table('studinfo')
				->select('studinfo.id', 'sid', 'lastname', 'firstname', 'middlename', 'grantee.description as grantee', 'levelname', 'strandcode', 'levelid')
				->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
				->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
				->leftjoin('sh_strand', 'studinfo.strandid', '=', 'sh_strand.id')
				->where('studinfo.id', $studid)
				->first();

			if($stud)
			{
				$levelid = $stud->levelid;

				$name = $stud->sid . ' - ' . $stud->lastname . ', ' . $stud->firstname . ' ' . $stud->middlename . ' - ' . $stud->levelname . ' ' . $stud->strandcode . ' | ' . $stud->grantee;

				$studledger = db::table('studledger')
					->select(db::raw('SUM(amount) - SUM(payment) AS balance'))
					->where('studid', $stud->id)
					->where('syid', $syid)
					->where(function($q) use($levelid, $semid){
						if($levelid == 14 || $levelid == 15)
						{
							if(FinanceModel::shssetup() == 0)
							{
								$q->where('semid', $semid);
							}
						}

						if($levelid >= 17 && $levelid <= 20)
						{
							$q->where('semid', $semid);
						}
					})
					->where('deleted', 0)
					->where('void', 0)
					->first();

				$studpayscheddetail = db::table('studpayscheddetail')
					->select(db::raw('SUM(amount) - SUM(amountpay) AS balance'))
					->where('studid', $stud->id)
					->where('syid', $syid)
					->where(function($q) use($levelid, $semid){
						if($levelid == 14 || $levelid == 15)
						{
							if(FinanceModel::shssetup() == 0)
							{
								$q->where('semid', $semid);
							}
						}

						if($levelid >= 17 && $levelid <= 20)
						{
							$q->where('semid', $semid);
						}
					})
					->where('deleted', 0)
					->first();
				$list = '';
				$disbalcount = 0;


				// echo 'studid: ' . $stud->id . ' ledger: ' . $studledger->balance . ' - ' . ' paysched: ' . $studpayscheddetail->balance . '<br>' ;
				
				if(floatval($studledger->balance) != floatval($studpayscheddetail->balance))
				{
					if(floatval($studledger->balance) < 0 && floatval($studpayscheddetail->balance) == 0)
					{
							goto foot;
					}
					else
					{
						$list .='
							<tr data-id="'.$stud->id.'">
								<td class="trname">'.$name.'</td>
								<td class="text-right">'.number_format($studledger->balance, 2).'</td>
								<td class="text-right">'.number_format($studpayscheddetail->balance, 2).'</td>
								<td ><button class="btn btn-info btn-block disbal-reloadledger" data-id="'.$stud->id.'">Reload Ledger</button</td>
							</tr>
						';
					}

					

					$disbalcount += 1;
				}

				foot:

				$data = array(
					'list' => $list,
					'disbalcount' => $disbalcount
				);

				echo json_encode($data);
			}	
		}
	}

	public function dpfix_loadorinfo(Request $request)
	{
		if($request->ajax())
		{
			$dataid = $request->get('dataid');
			$studid = $request->get('studid');

			$chrngtrans = db::table('chrngtrans')
				->select(db::raw('chrngtransid, ornum, studid, chrngtransdetail.*'))
				->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
				->where('chrngtrans.id', $dataid)
				->get();

			// return $chrngtrans;


			$list = '';
			$ornum = '';

			foreach($chrngtrans as $trans)
			{

				$ornum = $trans->ornum;
				$list .='
					<tr data-id="'.$trans->id.'">
						<td>'.$trans->items.'</td>
						<td class="text-right">'.number_format($trans->amount, 2).'</td>
					</tr>
				';
			}

			$studledger = db::table('studledger')
				->where('studid', $studid)
				->where('deleted', 0)
				->where('classid', '!=', null)
				// ->where('pschemeid', '!=', null)
				->where('semid', FinanceModel::getSemID())
				->where('syid', FinanceModel::getSYID())
				->get();

			$ledgerlist ='<option value="0">Select Classification</option>';

			foreach($studledger as $ledger)
			{
				$ledgerlist .='
					<option value="'.$ledger->classid.'">'.$ledger->particulars.'</option>
				';
			}


			$data = array(
				'list' => $list,
				'ornum' => $ornum,
				'ledgerlist' => $ledgerlist,
				'transid' => $dataid
			);

			echo json_encode($data);

		}
	}

	public function dpfix_pushtoledger(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');
			$classid = $request->get('classid');
			$transdetailid = $request->get('transdetailid');
			$ornum = $request->get('ornum');
			$pushall = $request->get('pushall');
			$transid = $request->get('transid');

			$toledger = $request->get('toledger');
			$topaysched = $request->get('topaysched');

			if($pushall == 0)
			{
				$detail = db::table('chrngtransdetail')
					->select('chrngtransdetail.*', 'chrngtrans.transdate as createddatetime', 'paytype')
					->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
					->where('chrngtransdetail.id', $transdetailid)
					->first();

				$chkledger = db::table('studledger')
					->where('transid', $detail->chrngtransid)
					->where('deleted', 0)
					->count();

			
				if($chkledger == 0)
				{
					if($toledger == 'true')
					{
						db::table('studledger')	
							->insert([
								'studid' => $studid,
								'semid' => FinanceModel::getSemID(),
								'syid' => FinanceModel::getSYID(),
								'classid' => null,
								'particulars' => $detail->items . ' OR: ' . $ornum . ' - ' . $detail->paytype,
								'payment' => $detail->amount,
								'ornum' => $ornum,
								'createdby' => auth()->user()->id,
								'createddatetime' => $detail->createddatetime,
								'transid' => $detail->chrngtransid,
								'paytype' => $detail->paytype,
								'deleted' => 0
							]);
					}

					if($topaysched == 'true')
					{
						$schoolinfo = db::table('schoolinfo')
							->first();

						if($schoolinfo->dpdist == 0)
						{
							$paysched = db::table('studpayscheddetail')
								->where('studid', $studid)
								->where('semid', FinanceModel::getSemID())
								->where('syid', FinanceModel::getSYID())
								->where('deleted', 0)
								->where('balance', '>', 0)
								->where('classid', $classid)
								->get();

							$dpamount = $detail->amount;

							foreach($paysched as $pay)
							{
								if($dpamount > 0)
								{
									if($dpamount >= $pay->balance)
									{
										db::table('studpayscheddetail')
											->where('id', $pay->id)
											->update([
												'amountpay' => $pay->amountpay + $pay->balance,
												'balance' => 0,
												'updatedby' => auth()->user()->id,
												'updateddatetime' => FinanceModel::getServerDateTime()
											]);

										$dpamount -= $pay->balance;
									}
									else
									{
										db::table('studpayscheddetail')
											->where('id', $pay->id)
											->update([
												'amountpay' => $pay->amountpay + $dpamount,
												'balance' => $pay->balance - $dpamount,
												'updatedby' => auth()->user()->id,
												'updateddatetime' => FinanceModel::getServerDateTime()
											]);						

										$dpamount = 0;
									}
								}
							}

							if($dpamount > 0)
							{
								$paysched = db::table('studpayscheddetail')
									->where('studid', $studid)
									->where('semid', FinanceModel::getSemID())
									->where('syid', FinanceModel::getSYID())
									->where('deleted', 0)
									->where('balance', '>', 0)
									// ->where('classid', $classid)
									->get();

								if(count($paysched) > 0)
								{
									foreach($paysched as $pay)
									{
										if($dpamount > 0)
										{
											if($dpamount >= $pay->balance)
											{
												db::table('studpayscheddetail')
													->where('id', $pay->id)
													->update([
														'amountpay' => $pay->amountpay + $pay->balance,
														'balance' => 0,
														'updatedby' => auth()->user()->id,
														'updateddatetime' => FinanceModel::getServerDateTime()
													]);

												$dpamount -= $pay->balance;
											}
											else
											{
												db::table('studpayscheddetail')
													->where('id', $pay->id)
													->update([
														'amountpay' => $pay->amountpay + $dpamount,
														'balance' => $pay->balance - $dpamount,
														'updatedby' => auth()->user()->id,
														'updateddatetime' => FinanceModel::getServerDateTime()
													]);						

												$dpamount = 0;
											}
										}
									}
								}
							}

						}
						else
						{
							$paysched = db::table('studpayscheddetail')
								->where('studid', $studid)
								->where('semid', FinanceModel::getSemID())
								->where('syid', FinanceModel::getSYID())
								->where('deleted', 0)
								->where('balance', '>', 0)
								->orderBy('duedate', 'ASC')
								->get();

							$dpamount = $detail->amount;

							foreach($paysched as $pay)
							{
								if($dpamount > 0)
								{
									if($dpamount >= $pay->balance)
									{
										db::table('studpayscheddetail')
											->where('id', $pay->id)
											->update([
												'amountpay' => $pay->amountpay + $pay->balance,
												'balance' => 0,
												'updatedby' => auth()->user()->id,
												'updateddatetime' => FinanceModel::getServerDateTime()
											]);

										$dpamount -= $pay->balance;
									}
									else
									{
										db::table('studpayscheddetail')
											->where('id', $pay->id)
											->update([
												'amountpay' => $pay->amountpay + $dpamount,
												'balance' => $pay->balance - $dpamount,
												'updatedby' => auth()->user()->id,
												'updateddatetime' => FinanceModel::getServerDateTime()
											]);						

										$dpamount = 0;
									}
								}
							}

						}
					}

					return 1;
				}
				else
				{
					return 2;
				}
			}
			else
			{

				$chrngtrans = db::table('chrngtrans')
					->where('id', $transid)
					->first();

				$stud = db::table('studinfo')
					->select('levelid')
					->where('id', $studid)
					->first();

				$levelid = $stud->levelid;
				$curAmount = $chrngtrans->amountpaid;

				db::table('studledger')	
					->insert([
						'studid' => $studid,
						'semid' => FinanceModel::getSemID(),
						'syid' => FinanceModel::getSYID(),
						'classid' => null,
						'particulars' => 'PAYMENT TUITION/BOOKS/OTH - OR: ' . $ornum . ' - ' . $chrngtrans->paytype,
						'payment' => $chrngtrans->amountpaid,
						'ornum' => $ornum,
						'createdby' => auth()->user()->id,
						'createddatetime' => $chrngtrans->transdate,
						'transid' => $chrngtrans->id,
						'paytype' => $chrngtrans->paytype,
						'deleted' => 0
					]);

				$payscheddetail = db::table('studpayscheddetail')
					->where('studid', $studid)
					->where('classid', $classid)
					->where('balance', '>', 0)
					->where('deleted', 0)
					->where('syid', FinanceModel::getSYID())
					->where(function($q) use($levelid){
						if($levelid == 14 || $levelid == 15)
						{
							$q->where('syid', FinanceModel::getSYID());
						}

						if($levelid >= 17 && $levelid <= 20)
						{
							$q->where('syid', FinanceModel::getSYID());
						}
					})
					->get();


				foreach($payscheddetail as $paysched)
				{	
					if($curAmount > 0)
					{
						if($curAmount > $paysched->balance)
						{
							db::table('studpayscheddetail')
								->where('id', $paysched->id)
								->update([
									'amountpay' => $paysched->amountpay + $paysched->balance,
									'balance' => 0,
									'updatedby' => auth()->user()->id,
									'updateddatetime' => FinanceModel::getServerDateTime()
								]);

							$curAmount -= $paysched->balance;
						}
						else
						{
							db::table('studpayscheddetail')
								->where('id', $paysched->id)
								->update([
									'amountpay' => $paysched->amountpay + $curAmount,
									'balance' => $paysched->balance - $curAmount,
									'updatedby' => auth()->user()->id,
									'updateddatetime' => FinanceModel::getServerDateTime()
								]);

							$curAmount = 0;
						}
					}
				}

				return 1;
			}

		}
	}

	public function disbaltrans_genstud(Request $request)
	{
		if($request->ajax())
		{
			$syid = $request->get('syid');
			$semid = $request->get('semid');

			$totalpayledger=0;
			$totalpaytrans=0;
			$list ='';

			$studinfo = db::table('studinfo')
				->select('studinfo.id', 'sid', 'lastname', 'firstname', 'middlename', 'levelname', 'grantee.description as grantee', 'levelid')
				->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
				->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
				->where('studinfo.deleted', 0)
				->where('studstatus', '>', 0)
				// ->limit(4)f
				->get();

			foreach($studinfo as $stud)
			{
				$levelid = $stud->levelid;

				$studledger = db::table('studledger')
					->where('deleted', 0)
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
					->where('studid', $stud->id)
					->where('void', 0)
					->where('classid', null)
					->where('ornum', '!=', null)
					->count();

				$chrng = db::table('chrngtrans')
					->select('chrngtrans.ornum')
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
					->where('studid', $stud->id)
					->where('cancelled', 0)
					->where('items', 'not like', '%CERTI%')
					->groupBy('ornum')
					->get();

				$chrngtrans = (count($chrng));

				$name = $stud->lastname . ', ' . $stud->firstname . ' ' . $stud->middlename . ' - ' . $stud->levelname . ' | ' . $stud->grantee;

				if($studledger < $chrngtrans)
				{
					$list .= '
						<tr data-id="'.$stud->id.'">
							<td>'.$stud->sid.'</td>
							<td>'.$name.'</td>
							<td class="text-right text-lg">'.$chrngtrans.'</td>
							<td class="text-right text-lg">'.$studledger.'</td>
							<td></td>
						</tr>
					';
				}
			}

			$data = array(
				'list' => $list
			);

			echo json_encode($data);
		}
	}

	public function tlf_generate(Request $request)
	{
		if($request->ajax())
		{
			$list ='';

			$studledger = db::table('studledger')
				->where('deleted', 0)
				->where('void', 1)
				->get();


			foreach($studledger as $ledger)
			{

				$studinfo = db::table('studinfo')
					->where('id', $ledger->studid)
					->first();


				if($studinfo)
				{
					$name = $studinfo->lastname . ', '	. $studinfo->firstname . ' ' . $studinfo->middlename;
					$sid = $studinfo->sid;
				}
				else
				{
					$name = '';
					$sid = '';
 				}

 				$trans = db::table('chrngtrans')
 					->where('studid', $ledger->studid)
 					->where('cancelled', 0)
 					->where('ornum', 'like', $ledger->ornum)
 					->first();

				

				if($trans)
				{
					$date = date_create($trans->transdate);
					$date = date_format($date, 'm-d-Y');

					$ledgerstatus = '';
					$transstatus = '';

					if($ledger->void == 0)
					{
						$ledgerstatus = 'FALSE';
					}
					else
					{
						$ledgerstatus = 'TRUE';
					}

					if($trans->cancelled == 0)
					{
						$transstatus = 'FALSE';
					}
					else
					{
						$transstatus = 'TRUE';
					}

					
					if($ledger->void != $trans->cancelled)
					{
						$list .='
							<tr>
								<td>'.$sid.'</td>
								<td>'.$name.'</td>
								<td>'.$date.'</td>
								<td>'.$trans->ornum.'</td>
								<td>'.$ledger->particulars.'</td>
								<td>'.number_format($ledger->payment, 2).'</td>
								<td>'.$transstatus.'</td>
								<td>'.$ledgerstatus.'</td>
								<td>
									<button class="btn btn-warning btn-sm btn-tlffix" data-trans="'.$trans->id.'" data-ledger="'.$ledger->id.'">
										<i class="fas fa-retweet"></i>
									</button
								</td>
							</tr>
						';
					}
				}
			}

			$data = array(
				'list' => $list
			);

			echo json_encode($data);
		}
	}

	public function tlf_fix(Request $request)
	{
		$transid = $request->get('transid');
		$ledgerid = $request->get('ledgerid');

		$trans = db::table('chrngtrans')
			->where('id', $transid)
			->first();

		if($trans)
		{
			db::table('studledger')
				->where('id', $ledgerid)
				->update([
					'void' => $trans->cancelled,
					'updateddatetime' => FinanceModel::getServerDateTime(),
					'updatedby' => auth()->user()->id
				]);
		}
	}

	public function dcc_view(Request $request)
	{
		if($request->ajax())
		{
			$dcc_header = db::table('dcc_header')
				->where('deleted', 0)
				->get();

			$headerlist = '';
			$bodylist = '';
			$width = '';

			foreach($dcc_header as $head)
			{
				if($head->widthsize == 0)
				{
					$width = '';
				}
				else
				{
					$width = 'width="'.$head->widthsize.'"';
				}


				$headerlist .='
						<th data-id="'.$head->id.'" '.$width.'>'.$head->description.'</th>
				';
			}

			$dcc_body = db::table('dcc_body')
				->where('deleted', 0)
				->get();



			foreach($dcc_body as $body)
			{

				$header = db::table('dcc_header')
					->where('deleted', 0)
					->get();

				$bodylist .='
					<tr>
				';

				foreach($header as $h)
				{
					$bodylist .='
						<td data-col="'.$h->id.'" data-row="'.$body->id.'"></td>
					';
				}

				$bodylist .='</tr>';

			}



			$data = array(
				'headerlist' => $headerlist,
				'bodylist' => $bodylist,
				'bodyarray' => $dcc_body
			);

			echo json_encode($data);
		}
	}

	public function dcc_headinsert(Request $request)
	{
		if($request->ajax())
		{
			$description  = $request->get('description');
			$type = $request->get('type');
			$width = $request->get('head_width');

			db::table('dcc_header')
				->insert([
					'description' => $description,
					'headertype' => $type,
					'widthsize' => $width,
					'createdby' => auth()->user()->id,
					'createddatetime' => FinanceModel::getServerDateTime()
				]);

		}
	}

	public function dcc_bodyinsert(Request $request)
	{
		if($request->ajax())
		{
			$headerid = $request->get('headerid');
            $itemid = $request->get('itemid');
            $idtype = $request->get('idtype');
            $itemdesc = $request->get('itemdesc');

            db::table('dcc_body')
            	->insert([
            		'headerid' => $headerid,
            		'itemid' => $itemid,
            		'idtype' => $idtype,
            		'itemdesc' => $itemdesc
            	]);

		}
	}

	public function dcc_loadmiscitems(Request $request)
	{
		if($request->ajax())
		{
			$miscitems = db::table('dcc_miscitems')
				->where('deleted', 0)
				->get();


			$list = '';

			foreach($miscitems as $misc)
			{
				$list .='
					<tr data-id="'.$misc->id.'">
						<td>'.$misc->classdesc.'</td>
					</tr>
				';
			}

			$data = array(
				'list' => $list
			);

			echo json_encode($data);
		}
	}

	public function dcc_addmiscitems(Request $request)
	{
		if($request->ajax())
		{
			$classid = $request->get('classid');
			
			$class = db::table('itemclassification')
				->where('id', $classid)
				->first();

			db::table('dcc_miscitems')
				->insert([
					'classid' => $classid,
					'classdesc' => $class->description,
					'createdby' => auth()->user()->id,
					'createddatetime' => FinanceModel::getServerDateTime()
				]);
		}
	}

	public function be_search(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->studid;
			$list = '';

			$stud = db::table('studinfo')
				->select('sid', 'lastname', 'firstname', 'middlename', 'levelname', 'grantee.description as grantee', 'levelid')
				->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
				->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
				->where('studinfo.id', $studid)
				->first();

			$name = $stud->sid . ' - ' . $stud->lastname . ', ' . $stud->firstname . ' ' . $stud->middlename;
			$levelid = $stud->levelid;
			$gradelevel = $stud->levelname;
			$grantee = $stud->grantee;

			$bookentries = db::table('bookentries')
				->where('deleted', 0)
				->where('studid', $studid)
				->where('syid', FinanceModel::getSYID())
				->where(function($q) use($levelid){
					if($levelid == 14 || $levelid == 15)
					{
						$q->where('semid', FinanceModel::getSemID());
					}

					if($levelid >= 17 && $levelid <= 20)
					{
						$q->where('semid', FinanceModel::getSemID());
					}
				})
				->get();

			foreach($bookentries as $be)
			{
				$list .='
					<tr>
						<td>'.$name.'</td>
						<td>'.number_format($be->amount, 2).'</td>
						<td>'.$be->bestatus.'</td>
						<td>
							<button class="btn btn-warning btn-sm" data-id="'.$be->id.'"><i class="fas fa-tools"></i></button>
							<button class="btn btn-danger btn-sm be-remove" data-id="'.$be->id.'"><i class="fas fa-trash-alt"></i></button>
						</td>
					</tr>
				';
			}

			$data = array(
				'list' => $list,
				'levelname' => $gradelevel,
				'grantee' => $grantee
			);

			echo json_encode($data);
		}
	}

	public function be_remove(Request $request)
	{
		if($request->ajax())
		{
			$dataid = $request->get('dataid');

			db::table('bookentries')
				->where('id', $dataid)
				->update([
					'deleted' => 1,
					'deletedby' => auth()->user()->id,
					'deleteddatetime' => FinanceModel::getServerDateTime()
				]);
		}
	}

	public function chrng_addclass(Request $request)
	{
		if($request->ajax())
		{
			$classid = $request->get('classid');
			$itemized = $request->get('itemized');
			$groupname = $request->get('groupname');

			if($itemized == 'true')
			{
				$itemized = 1;
			}
			else
			{
				$itemized = 0;	
			}

			// return $itemized;

			db::table('chrngsetup')
				->insert([
					'classid' => $classid,
					'itemized' => $itemized,
					'groupname' => $groupname,
					'createdby' => auth()->user()->id,
					'createddatetime' => FinanceModel::getServerDateTime(),
				]);

		}
	}

	public function chrng_loadclass(Request $request)
	{
		if($request->ajax())
		{
			$reg = '';
			$tui = '';
			$misc = '';
			$oth = '';

			$chrngsetup = db::table('chrngsetup')
				->select('itemclassification.description', 'groupname')
				->join('itemclassification', 'chrngsetup.classid', '=', 'itemclassification.id')
				->where('chrngsetup.deleted', 0)
				->get();
			// return $chrngsetup;

			foreach($chrngsetup as $setup)
			{

				if($setup->groupname == 'REG')
				{
					$reg .='
						<tr>
							<td>'.$setup->description.'</td>
						</tr>
					';
				}
				elseif($setup->groupname == 'TUI')
				{
					$tui .='
						<tr>
							<td>'.$setup->description.'</td>
						</tr>
					';
				}
				elseif($setup->groupname == 'MISC')
				{
					$misc .='
						<tr>
							<td>'.$setup->description.'</td>
						</tr>
					';
				}
				elseif($setup->groupname == 'OTH')
				{
					$oth .='
						<tr>
							<td>'.$setup->description.'</td>
						</tr>
					';
				}
			}

			$data = array(
				'reg' => $reg,
				'tui' => $tui,
				'misc' => $misc,
				'oth' => $oth
			);

			echo json_encode($data);
		}
	}

	public function paysched_loaddetails(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');
			$syid = $request->get('syid');
			$semid = $request->get('semid');
			$order = $request->get('order');

			$totalamount = 0;
			$totalpaid = 0;
			$totalbalance = 0;

			$batchid = 0;

			$tvbatch = db::table('tv_batch')
				->where('deleted', 0)
				->where('isactive', 1)
				->first();

			if($tvbatch)
			{
				$batchid = $tvbatch->id;
			}

			$info = db::table('studinfo')
				->where('id', $studid)
				->first();

			$levelid = $info->levelid;

			$paysched = db::table('studpayscheddetail')
				->select('studpayscheddetail.id', 'itemclassification.description as classification', 'paymentno', 'particulars', 'duedate', 'amount', 'amountpay', 'balance')
				->join('itemclassification', 'studpayscheddetail.classid', '=', 'itemclassification.id')
				->where('studid', $studid)
				->where('studpayscheddetail.deleted', 0)
				->where(function($q) use($levelid, $batchid, $syid){
					if($levelid == 21)
					{
						$q->where('enrollid', $batchid);
					}
					else
					{
						$q->where('syid', $syid);
					}
				})
				->where(function($q) use($levelid, $semid){
					if($levelid == 14 || $levelid == 15)
					{
						if(FinanceModel::shssetup() == 0)
						{
							$q->where('semid', $semid);
						}
					}

					if($levelid >= 17 && $levelid <= 20)
					{
						$q->where('semid', $semid);
					}
				})
				->orderBy($order, 'ASC')
				->get();


			$list = '';

			foreach($paysched as $sched)
			{
				$due = date_create($sched->duedate);
				$due = date_format($due, 'm-d-Y');

				$totalamount += $sched->amount;
				$totalpaid += $sched->amountpay;
				$totalbalance += $sched->balance;

				$list .='
					<tr data-id="'.$sched->id.'">
						<!--<td>'.$sched->classification.'</td>-->
						<td class="text-center">'.$sched->paymentno.'</td>
						<td>'.$sched->particulars.'</td>
						<td>'.$due.'</td>
						<td class="text-right">'.number_format($sched->amount, 2).'</td>
						<td class="text-right">'.number_format($sched->amountpay, 2).'</td>
						<td class="text-right">'.number_format($sched->balance, 2).'</td>
					</tr>
				';
			}

			$data = array(
				'list' => $list,
				'totalamount' => number_format($totalamount, 2),
				'totalpaid' => number_format($totalpaid, 2),
				'totalbalance' => number_format($totalbalance, 2)
			);

			echo json_encode($data);
		}
	}

	public function paysched_edit(Request $request)
	{
		if($request->ajax())
		{
			$dataid = $request->get('dataid');

			$paysched = db::table('studpayscheddetail')
				->where('id', $dataid)
				->first();

			$classification = db::table('itemclassification')
				->where('deleted', 0)
				->get();

			$classlist = '';

			foreach($classification as $class)
			{
				if($class->id == $paysched->classid)
				{
					$classlist .='
						<option value="'.$class->id.'" selected>'.$class->description.'</option>
					';
				}
				else
				{
					$classlist .='
						<option value="'.$class->id.'">'.$class->description.'</option>
					';
				}
			}


			$particulars = $paysched->particulars;
			$duedate = $paysched->duedate;
			$amount = $paysched->amount;
			$paid = $paysched->amountpay;
			$balance = $paysched->balance;

			$data = array(
				'classlist' => $classlist,
				'particulars' => $particulars,
				'duedate' => $duedate,
				'amount' => $amount,
				'paid' => $paid,
				'balance' => $balance
			);

			echo json_encode($data);
		}
	}

	public function paysched_update(Request $request)
	{
		if($request->ajax())
		{
			$dataid = $request->get('dataid');
			$classid = $request->get('classid');
			$particulars = $request->get('particulars');
			$duedate = $request->get('duedate');
			$amount = str_replace(',', '', $request->get('amount'));
			$paid = str_replace(',', '', $request->get('paid'));
			$balance = str_replace(',', '', $request->get('balance'));
			$studid = $request->get('studid');

			db::table('studpayscheddetail')
				->where('id', $dataid)
				->update([
					'classid' => $classid,
					'particulars' => $particulars,
					'duedate' => $duedate,
					'amount' => $amount,
					'amountpay' => $paid,
					'balance' => $balance,
					'updatedby' => auth()->user()->id,
					'updateddatetime' => FinanceModel::getServerDateTime()
				]);

			$this->fixSched($studid);
		}
	}

	public function fixSched($studid)
    {
        $syid = FinanceModel::getSYID();
        $semid = FinanceModel::getSemID();

        $levelid = db::table('studinfo')->where('id', $studid)->first()->levelid;

        
        $paysched = db::table('studpayscheddetail')
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
            	if($levelid == 14 || $levelid == 15)
            	{
            		if(FinanceModel::shssetup() == 0)
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
            ->where('deleted', 0)
            ->get();
    
        foreach($paysched as $pay)
        {
            $bal = $pay->amount - $pay->amountpay;

            if($bal != $pay->balance)
            {
                $upd = db::table('studpayscheddetail')
                    ->where('id', $pay->id)
                    ->update([
                      	'balance' => $bal,
                      	'updateddatetime' => FinanceModel::getServerDateTime(),
                      	'updatedby' => auth()->user()->id
                    ]);
            }
        }

    }

    public function fwd_vpbf_generate(Request $request)
    {
    	if($request->ajax())
    	{
    		$levelid = $request->get('levelid');
    		$orlist = '';
    		$list = '';
    		$count = 0;
    		$reported = '';

    		$balclassid = db::table('balforwardsetup')->first()->classid;

    		$studledger = db::table('studledger')
    			->select(db::raw('studinfo.id as studid, sid, lastname, firstname, middlename, studledger.*'))
    			->join('studinfo', 'studledger.studid', '=', 'studinfo.id')
    			->where('classid', $balclassid)
    			->where('studledger.deleted', 0)
    			->where('amount', '>', 0)
    			->where('levelid', $levelid)
    			->where('syid', FinanceModel::getSYID())
    			->where(function($q) use($levelid){
    				if($levelid == 14 || $levelid = 15)
    				{
    					$q->where('studledger.semid', FinanceModel::getSemID());
    				}

    				if($levelid >= 17 && $levelid <= 20)
    				{
    					$q->where('studledger.semid', FinanceModel::getSemID());
    				}
    			})
    			->orderBy('lastname', 'ASC')
    			->orderBy('firstname', 'ASC')
    			->get();

    		foreach($studledger as $ledger)
    		{
    			$old = db::table('oldaccmonitoring_reports')
    				->where('studid', $ledger->studid)
    				->where('syid', FinanceModel::getSYID())
    				->where('semid', FinanceModel::getSemID())
    				->where('deleted', 0)
    				->count();


    			if($old > 0)
    			{
    				$reported = 'btn-secondary';
    			}
    			else
    			{
    				$reported = 'btn-warning';
    			}

    			$paysched = db::table('studpayscheddetail')
    				->select(db::raw('sum(balance) as totalbalance'))
    				->where('studid', $ledger->studid)
    				->where('deleted', 0)
    				->where('classid', $balclassid)
    				->where('syid', FinanceModel::getSYID())
	    			->where(function($q) use($levelid){
	    				if($levelid == 14 || $levelid = 15)
	    				{
	    					$q->where('semid', FinanceModel::getSemID());
	    				}

	    				if($levelid >= 17 && $levelid <= 20)
	    				{
	    					$q->where('semid', FinanceModel::getSemID());
	    				}
	    			})
	    			->groupBy('classid')
	    			->first();

	    		$cashtrans = db::table('chrngtrans')
	    			->select(db::raw('ornum, transdate, studid, SUM(chrngtransdetail.`amount`) as totalamount'))
	    			->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
	    			->where('studid', $ledger->studid)
	    			->where('cancelled', 0)
	    			->where('classid', $balclassid)
	    			->get();

	    		$totalpay = 0;
	    		$ornums = '';

	    		foreach($cashtrans as $trans)
	    		{
	    			if($ornums == '')	
	    			{
	    				$ornums = $trans->ornum;
	    			}
	    			else
	    			{
	    				$ornums .='; ' . $trans->ornum;
	    			}

	    			$totalpay += $trans->totalamount;
	    		}

	    		$count += 1;
	    		$list .='
	    			<tr data-id="'.$ledger->studid.'">
	    				<td>'.$count.'</td>
	    				<td class="info_sid">'.$ledger->sid.'</td>
	    				<td class="info_name">'.$ledger->lastname . ', ' .$ledger->firstname . ' ' . $ledger->middlename .'</td>
	    				<td class="info_ornum">'.$ornums.'</td>
	    				<td class="text-right info_totalpay">'.number_format($totalpay, 2).'</td>
	    				<td class="text-right info_amount">'.number_format($ledger->amount, 2).'</td>
	    				<td class="text-right info_totalbalance">'.number_format($paysched->totalbalance, 2).'</td>
	    				<td><button class="btn btn-sm btn-block '.$reported.' btn-report text-sm" data-id="'.$ledger->studid.'">Report</button></td>
	    			</tr>
	    		';

    		}

    		$data = array(
    			'list' => $list
    		);

    		echo json_encode($data);
    	}
    }

    public function resetpayment_v2(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');
			$feesid = $request->get('feesid');



			$studinfo = db::table('studinfo')
				->where('id', $studid)
				->first();

			$levelid = $studinfo->levelid;
			$grantee = $studinfo->grantee;
			$strandid = $studinfo->strandid;
			$courseid = $studinfo->courseid;
			$dateenrolled = 0;

			if($levelid == 14 || $levelid == 15)
			{
				$enrollstud = db::table('sh_enrolledstud')
					->where('studid', $studid)
					->where('deleted', 0)
					->where('syid', FinanceModel::getSYID())
					->where(function($q){
						if(FinanceModel::shssetup() == 0)
						{
							$q->where('semid', FinanceModel::getSemID());
						}
					})
					->first();

				if($enrollstud)
				{
					$dateenrolled = $enrollstud->dateenrolled;
				}
			}
			elseif($levelid >= 17 && $levelid <= 22)
			{
				$enrollstud = db::table('college_enrolledstud')
					->where('studid', $studid)
					->where('deleted', 0)
					->first();	

				if($enrollstud)
				{
					$dateenrolled = $enrollstud->date_enrolled;
				}
			}
			else
			{
				$enrollstud = db::table('enrolledstud')
					->where('studid', $studid)
					->where('deleted', 0)
					->first();		

				$dateenrolled = $enrollstud->dateenrolled;
			}


			$studledger = db::table('studledger')
				->where('studid', $studid)
				->where('deleted', 0)
				->where('syid', FinanceModel::getSYID())
				->where(function($q) use($levelid){
					if($levelid == 14 || $levelid == 15)
					{
						if(FinanceModel::shssetup() == 0)
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

			foreach($studledger as $ledger)
			{
				$tuitiondetail = db::table('tuitiondetail')
					->where('classificationid', $ledger->classid)
					->where('deleted', 0)
					->count();

				if($tuitiondetail > 0)
				{
					db::table('studledger')
						->where('id', $ledger->id)
						->update([
							'deleted' => 1,
							'deleteddatetime' => FinanceModel::getServerDateTime(),
							'deletedby' => auth()->user()->id
						]);

				}
			}

			
			db::table('studpayscheddetail')
				->where('studid', $studid)
				->where('syid', FinanceModel::getSYID())
				->where(function($q) use($levelid){
					if($levelid == 14 || $levelid == 15)
					{
						if(FinanceModel::shssetup() == 0)	
						{
							$q->where('semid', FinanceModel::getSemID());
						}
					}

					if($levelid >= 17 && $levelid <= 20)
					{
						$q->where('semid', FinanceModel::getSemID());
					}
				})
				->update([
					'deleted' => 1
				]);

			db::table('studledgeritemized')
				->where('studid', $studid)
				->where('syid', FinanceModel::getSYID())
				->where(function($q) use($levelid){
					if($levelid == 14 || $levelid == 15)
					{
						if(FinanceModel::shssetup() == 0)	
						{
							$q->where('semid', FinanceModel::getSemID());
						}
					}

					if($levelid >= 17 && $levelid <= 20)
					{
						$q->where('semid', FinanceModel::getSemID());
					}
				})
				->update([
					'totalamount' => 0,
					'updatedby' => auth()->user()->id,
					'updateddatetime' => FinanceModel::getServerDateTime()
				]);

			$divbal = 0;

			$balforwardsetup = db::table('balforwardsetup')
				->first();

			$balforwardledger = db::table('studledger')
				->where('studid', $studid)
				->where('syid', FinanceModel::getSYID())
				->where(function($q) use($levelid){
					if($levelid == 14 || $levelid == 15)
					{
						if(FinanceModel::shssetup() == 0)	
						{
							$q->where('semid', FinanceModel::getSemID());
						}
					}

					if($levelid >= 17 && $levelid <= 20)
					{
						$q->where('semid', FinanceModel::getSemID());
					}
				})
				->where('deleted', 0)
				->where('classid', $balforwardsetup->classid)
				->first();
			
			if($balforwardledger)
			{
				if($balforwardledger->createddatetime <= $dateenrolled)
				{
					// echo $balforwardledger->createddatetime . ' <= ' . $dateenrolled;

					$balforwardpayment = db::table('chrngtrans')
						->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
						->where('studid', $studid)
						->where('classid', $balforwardledger->classid)
						->where('cancelled', 0)
						->count();

					if($balforwardpayment == 0)
					{
						$paymentsetup = db::table('paymentsetup')
							->join('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
							->where('paymentsetup.id', $balforwardsetup->mopid)
							->where('paymentsetupdetail.deleted', 0)
							->get();

						if(count($paymentsetup) > 0)
						{
							$divbal = $balforwardledger->amount / $paymentsetup[0]->noofpayment;
							$divbal = number_format($divbal, 2, '.', '');
							$totalbalforward = 0;

							foreach($paymentsetup as $mop)
							{
								if($mop->paymentno != $mop->noofpayment)
								{
									$totalbalforward += $divbal;
									db::table('studpayscheddetail')
										->insert([
											'studid' => $studid,
											'enrollid' => $enrollstud->id,
											'semid' => FinanceModel::getSemID(),
											'syid' => FinanceModel::getSYID(),
											'classid' => $balforwardsetup->classid,
											'paymentno' => $mop->paymentno,
											'particulars' => $balforwardledger->particulars,
											'duedate' => $mop->duedate,
											'amount' => $divbal,
											'amountpay' => 0,
											'balance' => $divbal,
											'createdby' => auth()->user()->id,
											'createddatetime' => FinanceModel::getServerDateTime()
										]);
								}
								else
								{
									$r_bal = $balforwardledger->amount - $totalbalforward;

									db::table('studpayscheddetail')
										->insert([
											'studid' => $studid,
											'enrollid' => $enrollstud->id,
											'semid' => FinanceModel::getSemID(),
											'syid' => FinanceModel::getSYID(),
											'classid' => $balforwardsetup->classid,
											'paymentno' => $mop->paymentno,
											'particulars' => $balforwardledger->particulars,
											'duedate' => $mop->duedate,
											'amount' => $r_bal,
											'amountpay' => 0,
											'balance' => $r_bal,
											'createdby' => auth()->user()->id,
											'createddatetime' => FinanceModel::getServerDateTime()
										]);
								}
							}
						}

					}
					else
					{
						$paymentsetup = db::table('paymentsetup')
							->join('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
							->where('paymentsetup.id', $balforwardsetup->mopid)
							->where('paymentsetupdetail.deleted', 0)
							->get();

						if(count($paymentsetup) > 0)
						{
							$divbal = $balforwardledger->amount / $paymentsetup[0]->noofpayment;
							$divbal = number_format($divbal, 2, '.', '');
							$totalbalforward = 0;

							foreach($paymentsetup as $mop)
							{	
								if($mop->paymentno != $mop->noofpayment)
								{
									$totalbalforward += $divbal;
									db::table('studpayscheddetail')
										->insert([
											'studid' => $studid,
											'enrollid' => $enrollstud->id,
											'semid' => FinanceModel::getSemID(),
											'syid' => FinanceModel::getSYID(),
											'classid' => $balforwardsetup->classid,
											'paymentno' => $mop->paymentno,
											'particulars' => $balforwardledger->particulars,
											'duedate' => $mop->duedate,
											'amount' => $divbal,
											'amountpay' => 0,
											'balance' => $divbal,
											'createdby' => auth()->user()->id,
											'createddatetime' => FinanceModel::getServerDateTime()
										]);
								}
								else
								{
									$r_bal = $balforwardledger->amount - $totalbalforward;

									db::table('studpayscheddetail')
										->insert([
											'studid' => $studid,
											'enrollid' => $enrollstud->id,
											'semid' => FinanceModel::getSemID(),
											'syid' => FinanceModel::getSYID(),
											'classid' => $balforwardsetup->classid,
											'paymentno' => $mop->paymentno,
											'particulars' => $balforwardledger->particulars,
											'duedate' => $mop->duedate,
											'amount' => $r_bal,
											'amountpay' => 0,
											'balance' => $r_bal,
											'createdby' => auth()->user()->id,
											'createddatetime' => FinanceModel::getServerDateTime()
										]);
								}

							}
						}
					}
				}
				else
				{
					$paymentsetup = db::table('paymentsetup')
						->join('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
						->where('paymentsetup.id', $balforwardsetup->mopid)
						->where('paymentsetupdetail.deleted', 0)
						->get();

					if(count($paymentsetup) > 0)
					{
						$divbal = $balforwardledger->amount / $paymentsetup[0]->noofpayment;
						$divbal = number_format($divbal, 2, '.', '');
						$totalbalforward = 0;

						foreach($paymentsetup as $mop)
						{
							if($mop->paymentno != $mop->noofpayment)
							{
								$totalbalforward += $divbal;
								db::table('studpayscheddetail')
									->insert([
										'studid' => $studid,
										'enrollid' => $enrollstud->id,
										'semid' => FinanceModel::getSemID(),
										'syid' => FinanceModel::getSYID(),
										'classid' => $balforwardsetup->classid,
										'paymentno' => $mop->paymentno,
										'particulars' => $balforwardledger->particulars,
										'duedate' => $mop->duedate,
										'amount' => $divbal,
										'amountpay' => 0,
										'balance' => $divbal,
										'createdby' => auth()->user()->id,
										'createddatetime' => FinanceModel::getServerDateTime()
									]);
							}
							else
							{
								$r_bal = $balforwardledger->amount - $totalbalforward;

								db::table('studpayscheddetail')
									->insert([
										'studid' => $studid,
										'enrollid' => $enrollstud->id,
										'semid' => FinanceModel::getSemID(),
										'syid' => FinanceModel::getSYID(),
										'classid' => $balforwardsetup->classid,
										'paymentno' => $mop->paymentno,
										'particulars' => $balforwardledger->particulars,
										'duedate' => $mop->duedate,
										'amount' => $r_bal,
										'amountpay' => 0,
										'balance' => $r_bal,
										'createdby' => auth()->user()->id,
										'createddatetime' => FinanceModel::getServerDateTime()
									]);
							}
						}
					}

				}

			}

			$units = 0;

			$tuitions = db::table('tuitionheader')
				->select('tuitionheader.id', 'syid', 'semid', 'grantee', 'strandid', 'courseid', 'classificationid', 'itemclassification.description as classdescription', 'tuitiondetail.amount', 'tuitiondetail.pschemeid', 'tuitiondetail.id as tuitiondetailid', 'istuition')
				->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
				->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
				->where('tuitionheader.deleted', 0)
				->where('tuitiondetail.deleted', 0)
				->where('tuitionheader.id', $feesid)
				->get();

			if($levelid >= 17 && $levelid <=20)
			{
				$totalunits = db::table('college_studsched')
					->select(db::raw('SUM(lecunits) + SUM(labunits) AS totalunits'))
					->join('college_classsched', 'college_studsched.schedid', '=', 'college_classsched.id')
					->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
					->where('college_studsched.studid', $studid)
					->where('college_studsched.deleted', 0)
					->where('college_classsched.syid', FinanceModel::getSYID())
					->where('college_classsched.semid', FinanceModel::getSemID())
					->first();

				$units = $totalunits[0]->totalunits;
			}

			foreach($tuitions as $tuition)
			{

				if($levelid >= 17 && $levelid <=20)
				{
					if($tuition->istuition == 1)
	  				{
	  					// echo $tui->amount . ' * ' . $units;
	  					$tuitionamount = $tuition->amount * $units;
	  				}
	  				else
	  				{
	  					$tuitionamount = $tuition->amount;
	  				}
				}
				else
				{
					$tuitionamount = $tuition->amount;
				}

				db::table('studledger')
					->insert([
						'studid' => $studid,
						'semid' => FinanceModel::getSemID(),
						'syid' => FinanceModel::getSYID(),
						'classid' => $tuition->classificationid,
						'particulars' => $tuition->classdescription,
						'amount' => $tuitionamount,
						'pschemeid' => $tuition->pschemeid,
						'createdby' => auth()->user()->id,
						'createddatetime' => $dateenrolled,
						'deleted' => 0
					]);

				$tuitionitems = db::table('tuitionitems')
					->where('tuitiondetailid', $tuition->tuitiondetailid)
					->where('deleted', 0)
					->get();

				foreach($tuitionitems as $items)
				{
					$countitemized = db::table('studledgeritemized')
						->where('studid', $studid)
						->where('syid', FinanceModel::getSYID())
						->where(function($q) use($levelid){
							if($levelid == 14 || $levelid == 15)
							{
								if(FinanceModel::shssetup() == 0)	
								{
									$q->where('semid', FinanceModel::getSemID());
								}
							}

							if($levelid >= 17 && $levelid <= 20)
							{
								$q->where('semid', FinanceModel::getSemID());
							}
						})
						->where('itemid', $items->itemid)
						->count();

					if($countitemized > 0)
					{
						db::table('studledgeritemized')
							->where('studid', $studid)
							->where('syid', FinanceModel::getSYID())
							->where(function($q) use($levelid){
								if($levelid == 14 || $levelid == 15)
								{
									if(FinanceModel::shssetup() == 0)	
									{
										$q->where('semid', FinanceModel::getSemID());
									}
								}

								if($levelid >= 17 && $levelid <= 20)
								{
									$q->where('semid', FinanceModel::getSemID());
								}
							})
							->where('itemid', $items->itemid)
							->update([
								'itemamount' => $items->amount,
								'updatedby' => auth()->user()->id,
								'updateddatetime' => FinanceModel::getServerDateTime()
							]);
					}
					else
					{
						db::table('studledgeritemized')
							->insert([
								'studid' => $studid,
								'semid' => FinanceModel::getSemID(),
								'syid' => FinanceModel::getSYID(),
								'tuitiondetailid' => $items->tuitiondetailid,
								'itemid' => $items->itemid,
								'itemamount' => $items->amount,
								'deleted' => 0
							]);
					}
				}

				$paymentsetup = db::table('paymentsetup')
  					->select('paymentsetup.id', 'paymentdesc', 'paymentsetup.noofpayment', 'paymentno', 'duedate', 'payopt', 'percentamount')
  					->leftjoin('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
  					->where('paymentsetup.id', $tuition->pschemeid)
  					->where('paymentsetupdetail.deleted', 0)
  					->get();

  				if(count($paymentsetup) > 0)
  				{
  					if($paymentsetup[0]->payopt == 'divided')
  					{
  						$divPay = 0;

  						if(count($paymentsetup) > 1)
			  			{
			  				$paymentno = $paymentsetup[0]->noofpayment;
			  				$divPay = $tuitionamount / $paymentno;
			  				$divPay = number_format($divPay, 2, '.', '');
			  			}
			  			else
			  			{
			  				$paymentno = 1;
			  				$divPay = $tuitionamount;
			  				$divPay = number_format($divPay, 2, '.', '');
			  			}

			  			$paycount = 0;
			  			$paytAmount = 0;
			  			$paydisbalance = 0;
			  			$testclassid = 0;

			  			foreach($paymentsetup as $pay)
			  			{
			  				$paycount += 1;
	  						$paytAmount += $divPay;

	  						if($paycount != $paymentno)
	  						{	
	  							
	  							$scheditem = db::table('studpayscheddetail')
			  						->insert([
			  							'studid' => $studid,
			  							'enrollid' => $enrollstud->id,
			  							'syid' => FinanceModel::getSYID(),
			  							'semid' => FinanceModel::getSemID(),
			  							'tuitiondetailid' => $tuition->tuitiondetailid,
			  							'particulars' => $tuition->classdescription,
			  							'duedate' => $pay->duedate,
			  							'paymentno' => $pay->paymentno,
			  							'amount' => $divPay,
			  							'amountpay' => 0,
			  							'balance' => $divPay,
			  							'classid' => $tuition->classificationid,
			  							'createddatetime' => FinanceModel::getServerDateTime(),
			  							'createdby' => auth()->user()->id
			  						]);

			  						
	  						}
	  						else
	  						{
	  							if($paytAmount <= $tuitionamount)
	  							{
	  								
	  								$paydisbalance = $tuitionamount - $paytAmount;
			  						$paydisbalance = number_format($paydisbalance, 2, '.', '');

			  						$divPay += $paydisbalance;
			  						
			  						// echo ' paydisbalance: ' . $paydisbalance;
			  						// echo ' +divPay: '. $divPay;
			  						$scheditem = db::table('studpayscheddetail')
				  						->insert([
				  							'studid' => $studid,
				  							'enrollid' => $enrollstud->id,
				  							'syid' => FinanceModel::getSYID(),
			  								'semid' => FinanceModel::getSemID(),
				  							'tuitiondetailid' => $tuition->tuitiondetailid,
				  							'particulars' => $tuition->classdescription,
				  							'duedate' => $pay->duedate,
				  							'paymentno' => $pay->paymentno,
				  							'amount' => $divPay,
				  							'balance' => $divPay,
				  							'amountpay' => 0,
				  							'classid' => $tuition->classificationid,
				  							'createddatetime' => FinanceModel::getServerDateTime(),
			  								'createdby' => auth()->user()->id
				  						]);
	  							}
	  							else
	  							{
	  								
	  								$paydisbalance = $paytAmount - $tuitionamount;
	  								$paydisbalance = number_format($paydisbalance, 2, '.', '');

	  								$divPay -= $paydisbalance;

	  								$scheditem = db::table('studpayscheddetail')
				  						->insert([
				  							'studid' => $studid,
				  							'enrollid' => $enrollstud->id,
				  							'syid' => FinanceModel::getSYID(),
			  								'semid' => FinanceModel::getSemID(),
				  							'tuitiondetailid' => $tuition->tuitiondetailid,
				  							'particulars' => $tuition->classdescription,
				  							'duedate' => $pay->duedate,
				  							'paymentno' => $pay->paymentno,
				  							'amount' => $divPay,
				  							'amountpay' => 0,
				  							'balance' => $divPay,
				  							'classid' => $tuition->classificationid,
				  							'createddatetime' => FinanceModel::getServerDateTime(),
			  								'createdby' => auth()->user()->id
				  						]);
	  							}
	  						}
	  						
			  			}
			  			
  					}
  					else
  					{

  						$paycount = 0;
			  			$pAmount = 0;
			  			$curAmount = $tuition->amount;

			  			foreach($paymentsetup as $pay)
			  			{
			  				$paycount +=1;

			  				if($paycount < count($paymentsetup))
			  				{
			  					if($curAmount > 0)
			  					{
				  					$pAmount = round($pay->percentamount * ($tui->amount/100), 2);
				  					$curAmount = (round($curAmount - $pAmount, 2));
 
				  					$scheditem = db::table('studpayscheddetail')
				  					->insert([
				  							'studid' => $studid,
				  							'enrollid' => $enrollid,
				  							'syid' => $sy,
				  							'semid' => $semid,
				  							'tuitiondetailid' => $tui->tuitiondetailid,
				  							'particulars' => $tui->particulars,
				  							'duedate' => $pay->duedate,
				  							'paymentno' => $pay->paymentno,
				  							'amount' => $pAmount,
				  							'amountpay' => 0,
				  							'balance' => $pAmount,
				  							'classid' => $tui->classid,
				  							'createddatetime' => FinanceModel::getServerDateTime(),
			  								'createdby' => auth()->user()->id
				  						]);
				  				}
			  				}
			  				else
			  				{
			  					if($curAmount > 0)
			  					{
			  						$scheditem = db::table('studpayscheddetail')
			  						->insert([
				  							'studid' => $studid,
				  							'enrollid' => $enrollid,
				  							'syid' => $sy,
				  							'semid' => $semid,
				  							'tuitiondetailid' => $tui->tuitiondetailid,
				  							'particulars' => $tui->particulars,
				  							'duedate' => $pay->duedate,
				  							'paymentno' => $pay->paymentno,
				  							'amount' => $curAmount,
				  							'amountpay' => 0,
				  							'balance' => $curAmount,
				  							'classid' => $tui->classid,
				  							'createddatetime' => FinanceModel::getServerDateTime(),
			  								'createdby' => auth()->user()->id
				  						]);	
			  						$curAmount = 0;
			  					}
			  				}
			  			}
  					}
  				}
			}

			//Check Adjustment
	  		$adjbal = 0;
	  		$adjustment = db::table('adjustments')
	  			->select('adjustments.id', 'description', 'amount', 'classid', 'isdebit', 'iscredit', 'syid', 'semid', 'adjstatus', 'studid', 'mop', 'adjustments.createddatetime')
	  			->join('adjustmentdetails', 'adjustments.id', '=', 'adjustmentdetails.headerid')
	  			->where('studid', $studid)
	  			->where('syid', FinanceModel::getSYID())
	  			->where(function($q) use($levelid){
	  				if($levelid == 14 || $levelid == 15)
	  				{
	  					if(FinanceModel::shssetup() == 0)
	  					{
	  						$q->where('semid', FinanceModel::getSemID());
	  					}
	  				}

	  				if($levelid >= 17 && $levelid <= 20)
	  				{
	  					$q->where('semid', FinanceModel::getSemID());
	  				}
	  			})
	  			->where('adjstatus', 'APPROVED')
	  			->where('adjustmentdetails.deleted', 0)
	  			->get();



	  		foreach($adjustment as $adj)
	  		{
	  			$adjbal = $adj->amount;
	  			$adjbal = number_format($adj->amount, 2, '.', '');
	  			$particulars = $adj->description;
  			
  				if($adj->iscredit == 1)
  				{
  					$checkadjledger = db::table('studledger')
  						->where('studid', $studid)
		  				->where('ornum', $adj->id)
		  				->where('deleted', 0)
		  				->first();

		  			if(!$checkadjledger)
		  			{
		  				db::table('studledger')
		  					->insert([
		  						'studid' => $studid,
		  						'semid' => FinanceModel::getSemID(),
		  						'syid' => FinanceModel::getSYID(),
		  						'classid' => $adj->classid,
		  						'particulars' => 'ADJ: ' . $adj->description,
		  						'payment' => $adj->amount,
		  						'pschemeid' => $adj->mop,
		  						'ornum' => $adj->id,
		  						'deleted' => 0,
		  						'createdby' => auth()->user()->id,
		  						'createddatetime' => $adj->createddatetime //FinanceModel::getServerDateTime()
		  					]);
		  			}

		  			
  				}
  				else
  				{
  					// return 'debit';
  					$checkadjledger = db::table('studledger')
  						// ->select('studledger.*, aaa')
  						->where('studid', $studid)
		  				->where('syid', FinanceModel::getSYID())
		  				->where(function($q) use($levelid){
			  				if($levelid == 14 || $levelid == 15)
			  				{
			  					if(FinanceModel::shssetup() == 0)
			  					{
			  						$q->where('semid', FinanceModel::getSemID());
			  					}
			  				}

			  				if($levelid >= 17 && $levelid <= 20)
			  				{
			  					$q->where('semid', FinanceModel::getSemID());
			  				}
			  			})
		  				->where('particulars', 'like', '%ADJ%')
		  				->where('ornum', $adj->id)
		  				->where('deleted', 0)
		  				->first();
		  			// return $checkadjledger;

		  			if(!$checkadjledger)
		  			{
		  				db::table('studledger')
		  					->insert([
		  						'studid' => $studid,
		  						'semid' => FinanceModel::getSemID(),
		  						'syid' => FinanceModel::getSYID(),
		  						'classid' => $adj->classid,
		  						'particulars' => 'ADJ: ' . $adj->description,
		  						'amount' => $adj->amount,
		  						'pschemeid' => $adj->mop,
		  						'ornum' => $adj->id,
		  						'deleted' => 0,
		  						'createdby' => auth()->user()->id,
		  						'createddatetime' => FinanceModel::getServerDateTime()
		  					]);
		  			}
  				}
	  			
	  		}
	  		// return 22222;
	  		//Check Adjustment


			//Book Entries

			$besetup = db::table('bookentrysetup')
	  			->first();

	  		if($besetup)
	  		{
	  			$particulars = db::table('itemclassification')->where('id', $besetup->classid)->first()->description;
	  		}

	  		$bookentries = db::table('bookentries')
	  			->where('studid', $studid)
	  			->where('bestatus', 'APPROVED')
	  			->where('syid', FinanceModel::getSYID())
	  			->where(function($q) use($levelid){
	  				if($levelid == 14 || $levelid == 15)
	  				{
		  				if(FinanceModel::shssetup() == 0)
		  				{
		  					$q->where('semid', FinanceModel::getSemID());
		  				}
		  			}
		  			if($levelid >= 17 && $levelid <= 20)
		  			{
		  				$q->where('semid', FinanceModel::getSemID());
		  			}
	  			})
	  			->where('deleted', 0)
	  			->get();

	  		foreach($bookentries as $book)
	  		{
	  			$mop = db::table('paymentsetup')
	  				->select(db::raw('paymentsetup.id, noofpayment, paymentno, duedate, noofpayment'))
	  				->join('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
	  				->where('paymentsetup.id', $besetup->mopid)
	  				->first();


	  			if($mop)
	  			{
	  				if($mop->noofpayment > 1)
		  			{
		  				$divbal = $book->amount / $mop->noofpayment;
						$divbal = number_format($divbal, 2, '.', '');
						$totalBE = 0;

		  				$paymentsetup = db::table('paymentsetupdetail')
		  					->where('paymentid', $mop->id)
		  					->where('deleted', 0)
		  					->get();

						foreach($paymentsetup as $mopd)
						{
							if($mopd->paymentno != $mop->noofpayment)
							{
								$totalBE += $divbal;
								db::table('studpayscheddetail')
									->insert([
										'studid' => $studid,
										'enrollid' => $enrollstud->id,
										'semid' => FinanceModel::getSemID(),
										'syid' => FinanceModel::getSYID(),
										'classid' => $book->classid,
										'paymentno' => $mopd->paymentno,
										'particulars' => $particulars,
										'duedate' => $mopd->duedate,
										'amount' => $divbal,
										'balance' => $divbal,
										'createdby' => auth()->user()->id,
										'createddatetime' => FinanceModel::getServerDateTime()
									]);
							}
							else
							{
								$r_bal = $book->amount - $totalBE;

								db::table('studpayscheddetail')
									->insert([
										'studid' => $studid,
										'enrollid' => $enrollstud->id,
										'semid' => FinanceModel::getSemID(),
										'syid' => FinanceModel::getSYID(),
										'classid' => $book->classid,
										'paymentno' => $mopd->paymentno,
										'particulars' => $particulars,
										'duedate' => $mopd->duedate,
										'amount' => $r_bal,
										'balance' => $r_bal,
										'createdby' => auth()->user()->id,
										'createddatetime' => FinanceModel::getServerDateTime()
									]);
							}
						}							


		  			}	
		  			else
		  			{
		  				

		  				db::table('studpayscheddetail')
		  					->insert([
		  						'studid' => $studid,
		  						'semid' => FinanceModel::getSemID(),
		  						'syid' => FinanceModel::getSYID(),
		  						'classid' => $book->classid,
		  						'paymentno' => 1,
		  						'particulars' => $particulars,
		  						'amount' => $book->amount,
		  						'balance' => $book->amount,
		  						'deleted' => 0,
		  						'updatedby' => auth()->user()->id,
		  						'updateddatetime' => FinanceModel::getServerDateTime()
		  					]);
		  			}
	  			}
	  		}
	  		//Book Entries

	  		FinanceModel::reloaddiscounts($studid, FinanceModel::getSYID(), FinanceModel::getSemID(), $levelid);

			$balforwardsetup = db::table('balforwardsetup')
				->first();

			$balclassid = $balforwardsetup->classid;

			if($besetup)
			{
				$be_classid = $besetup->classid;
			}

			//Ledger Payments

			$ledger_payments = db::table('studledger')
				// ->select('aaa')
				->where('studid', $studid)
				->where('syid', FinanceModel::getSYID())
				->where('deleted', 0)
				->where('ornum', '!=', null)
				->where('void', 0)
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
            
            $_tid = 0;
			foreach($ledger_payments as $ledpay)
			{

				$ledgeramount = $ledpay->payment;
				$dpclass_array = array(); // v2 DP
				// return $ledpay->payment;
				// goto Adjustment;

				//DP Transactions
				
				if($_tid != 0)
				{

					if($_tid == $ledpay->transid)
					{
						$_tid = $ledpay->transid;
						goto endreset;
					}
				}

				$_tid = $ledpay->transid;

				if(db::table('schoolinfo')->first()->cashierversion == 1)
				{
					$led_ornum = $ledpay->ornum;
					$led_transid = $ledpay->transid;

					$getdp = db::table('chrngtransdetail')
						->select('studid', 'syid', 'semid', 'itemkind', 'payschedid', 'isdp', 'chrngtransdetail.classid', 'chrngtransdetail.amount')
						->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
						->join('items', 'chrngtransdetail.payschedid', '=', 'items.id')
						->where('studid', $studid)
						->where('isdp', 1)
						->where('chrngtrans.id', $led_transid)
						->where('chrngtransdetail.classid', '!=', $balclassid)
						->get();

					if(count($getdp) > 0)
					{
						

						// echo ' DP transactions: ' . $ledpay->transid . '; amount:';
						foreach($getdp as $dp)
						{
							$dpBal = $dp->amount;
							
							echo ' DP transactions: ' . $ledpay->transid . '; amount:' . $dp->amount;

							$getpaySched = db::table('studpayscheddetail')
								->where('studid', $studid)
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
								->where('balance', '>', 0)
								->where('classid', $dp->classid)
								->where('deleted', 0)
								->get();


							foreach($getpaySched as $sched)
							{
								if($dpBal > 0)
								{
									// echo '[' . $dpBal . '>' . $sched->amount . ']';
									// $schedbal = $sched->amount - $sched->amountpay;
									
									if($dpBal > $sched->balance)
									{	
										$deductDP = db::table('studpayscheddetail')
											->where('id', $sched->id)
											->update([
												'amountpay' => $sched->amountpay + $sched->balance,
												'balance' => 0,
												'updateddatetime' => FinanceModel::getServerDateTime(),
												'updatedby' => auth()->user()->id
												]);

										$dpBal -= $sched->balance;
									}
									else
									{
										$tDP = $sched->amount - $dpBal;
										$aPay = $sched->amountpay + $dpBal;

										// echo ' aPay = ' . $aPay;
										
										$deductDP = db::table('studpayscheddetail')
											->where('id', $sched->id)
											->update([
												'amountpay' => $sched->amountpay + $dpBal,
												'balance' => $sched->balance - $dpBal,
												'updateddatetime' => FinanceModel::getServerDateTime(),
												'updatedby' => auth()->user()->id
											]);
										

										$dpBal = 0;
									}
								}
							}

							if($dpBal > 0)
							{
								$gpaydetail = db::table('studpayscheddetail')
									->select(db::raw('studid, syid, semid, tuitiondetailid, classid, particulars, SUM(amount) AS amount, SUM(amountpay) AS amountpay, SUM(balance) AS balance'))
									->where('studid', $studid)
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
									->where('deleted', 0)
									->where('balance', '>', 0)
									->groupBy('classid')
									->get();

								foreach($gpaydetail as $detail)
								{
									$paysched = db::table('studpayscheddetail')
											->where('classid', $detail->classid)
											->where('studid', $studid)
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
											->where('deleted', 0)
											->where('balance', '>', 0)
											->get();

									if($dpBal > 0)
									{		
										foreach($paysched as $sched)
										{
											if($dpBal > $sched->balance)
											{
												$deductDP = db::table('studpayscheddetail')
													->where('id', $sched->id)
													->update([
														'amountpay' => $sched->balance + $sched->amountpay,
														'balance' => 0,
														'updateddatetime' => FinanceModel::getServerDateTime(),
														'updatedby' => auth()->user()->id
													]);

												$dpBal -= $sched->balance;
											}
											else
											{
												$tDP = $sched->balance - $dpBal;
												$aPay = $sched->amountpay + $dpBal;
												
												$deductDP = db::table('studpayscheddetail')
													->where('id', $sched->id)
													->update([
														'amountpay' => $sched->amountpay + $dpBal ,
														'balance' => $sched->balance - $dpBal,
														'updateddatetime' => FinanceModel::getServerDateTime(),
														'updatedby' => auth()->user()->id
													]);
												

												$dpBal = 0;
											}
										}
									}

								}
							}
						}

						goto endreset;
					}

				}
				else //cashierversion 2
				{
					// echo ' dp: ' . $ledpay->ornum . ' ';
					$syid = FinanceModel::getSYID();
					$semid = FinanceModel::getSemID();

					$ee_amount = FinanceModel::checkEE($studid, $syid, $semid);

					$getdp = array();
					
					if($ee_amount > 0)
					{
						$getdp1 = db::table('chrng_earlyenrollmentpayment')
				            ->select(db::raw('chrng_earlyenrollmentpayment.studid, chrngtransdetail.`classid`, chrng_earlyenrollmentpayment.amount, chrngtrans.`ornum`, payschedid, chrng_earlyenrollmentpayment.chrngtransid, chrngtransdetail.`id` AS chrngtransdetailid, chrngtransdetail.items, paytype, transdate'))
				            ->join('chrngtrans', 'chrng_earlyenrollmentpayment.chrngtransid', '=', 'chrngtrans.id')
				            ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
				            ->where('chrng_earlyenrollmentpayment.syid', $syid)
				            ->where('chrng_earlyenrollmentpayment.semid', $semid)
				            ->where('chrng_earlyenrollmentpayment.studid', $studid)
				            ->where('deleted', 0)
				            ->get();


				     	foreach($getdp1 as $_dp)
				     	{
				     		$transdate = date_create($_dp->transdate);
				     		$transdate = date_format($transdate, 'Y-m-d');

				     		$sLedger = db::table('studledger')
								->insert([
									'studid' => $studid,
									'enrollid' => $enrollid,
									'syid' => $syid,
									'semid' => 1,
									'classid' => $_dp->classid,
									'particulars' => $_dp->items . ' - OR: ' . $_dp->ornum . ' - ' . $_dp->paytype,
									'payment' => $_dp->amount,
									'ornum' => $_dp->ornum,
									'paytype' => $_dp->paytype,
									'transid' => $_dp->chrngtransid,
									'deleted' => 0,
									'createddatetime' => $transdate
								]);

							// array_push($getdp, $_dp);
				     	}
					}

					$dpsetup = db::table('dpsetup')
						->where('levelid', $levelid)
						->where('deleted', 0)
						->where('syid', $syid)
						->where('semid', $semid)
						->groupBy('classid')
						->get();

					

					foreach($dpsetup as $setup)
					{
						array_push($dpclass_array, $setup->classid);
					}

					$getdp2 = db::table('studledger')
						->select(db::raw('studledger.studid, chrngtransdetail.classid, sum(chrngtransdetail.`amount`) as amount, chrngtrans.ornum, payschedid, chrngtransid, chrngtransdetail.id as chrngtransdetailid, chrngtransdetail.items, chrngtrans.paytype, transdate'))
						->join('chrngtrans', 'studledger.transid', '=', 'chrngtrans.id')
						->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
						->whereIn('chrngtransdetail.classid', $dpclass_array)
						->where('studledger.studid', $studid)
						->where('studledger.deleted', 0)
						->where('studledger.syid', $syid)
						->where('studledger.semid', $semid)
						->where('transid', $ledpay->transid)
						->groupBy('classid')
						->get();

					foreach($getdp2 as $_dp1)
					{
						array_push($getdp, $_dp1);	
					}

					if(count($getdp) > 0)
					{
						// return $getdp;

						// if($ledpay->transid == 77)
						// {
						// 	return $getdp;
						// }

						foreach($getdp as $dp)
						{
							$dpBal = $dp->amount;

							$getpaySched = db::table('studpayscheddetail')
								->where('studid', $studid)
								->where('syid', $syid)
								->where('semid', $semid)
								->where('classid', $dp->classid)
								->where('deleted', 0)
								->get();

							$retdp = 0;

							if(count($getpaySched) > 0)
							{
								foreach($getpaySched as $sched)
								{
									if($dpBal > 0)
									{
										// echo '[' . $dpBal . '>' . $sched->amount . ']';
										$schedbal = $sched->amount - $sched->amountpay;
										
										if($dpBal > $schedbal)
										{
											$tDP = 0;
											
											$deductDP = db::table('studpayscheddetail')
												->where('id', $sched->id)
												->update([
													'amountpay' => $schedbal + $sched->amountpay,
													'balance' => $tDP,
													'updateddatetime' => FinanceModel::getServerDateTime(),
													'updatedby' => auth()->user()->id
													]);

											RegistrarModel::chrngdistlogs($studid, $dp->chrngtransid, $dp->chrngtransdetailid, $sched->id, $sched->classid, $schedbal);
											RegistrarModel::procItemized($studid, $sched->classid, $schedbal, $syid, $semid);

											$dpBal -= $schedbal;
											$ledgeramount -= $schedbal;
										}
										else
										{
											$tDP = $sched->amount - $dpBal;
											$aPay = $sched->amountpay + $dpBal;

											// echo ' aPay = ' . $aPay;
											
											$deductDP = db::table('studpayscheddetail')
												->where('id', $sched->id)
												->update([
													'amountpay' => $aPay,
													'balance' => $tDP,
													'updateddatetime' => RegistrarModel::getServerDateTime(),
													'updatedby' => auth()->user()->id
												]);
											
											RegistrarModel::chrngdistlogs($studid, $dp->chrngtransid, $dp->chrngtransdetailid, $sched->id, $sched->classid, $dpBal);
											RegistrarModel::procItemized($studid, $sched->classid, $dpBal, $syid, $semid);

											$ledgeramount -= $dpBal;
											$dpBal = 0;
										}
									}
								}

								if($dpBal > 0)
								{
									$chrngsetup = db::table('chrngsetup')
										->where('groupname', 'OTH')
										->where('deleted', 0)
										->get();

									$setuparray = array();

									foreach($chrngsetup as $chrng)
									{
										array_push($setuparray, $chrng->classid);
									}

									$gpaydetail = db::table('studpayscheddetail')
										->select(db::raw('studid, syid, semid, tuitiondetailid, classid, particulars, SUM(amount) AS amount, SUM(amountpay) AS amountpay, SUM(balance) AS balance'))
										->where('studid', $studid)
										->where('syid', $syid)
										->where('semid', $semid)
										->where('balance', '>', 0)
										->whereIn('classid', $setuparray)
										->groupBy('classid')
										->get();

									if(count($gpaydetail) == 0)
									{
										dptoempty_sh:
										$gpaydetail = db::table('studpayscheddetail')
											->select(db::raw('studid, syid, semid, tuitiondetailid, classid, particulars, SUM(amount) AS amount, SUM(amountpay) AS amountpay, SUM(balance) AS balance'))
											->where('studid', $studid)
											->where('syid', $syid)
											->where('balance', '>', 0)
											->groupBy('classid')
											->get();									
									}

									foreach($gpaydetail as $detail)
									{
										$paysched = db::table('studpayscheddetail')
												->where('classid', $detail->classid)
												->where('studid', $studid)
												->where('syid', $syid)
												->where('semid', $semid)
												->where('deleted', 0)
												->get();

										if($dpBal > 0)
										{		
											foreach($paysched as $sched)
											{
												if($dpBal > $sched->balance)
												{
													$tDP = 0;
													$deductDP = db::table('studpayscheddetail')
														->where('id', $sched->id)
														->update([
															'amountpay' => $sched->balance + $sched->amountpay,
															'balance' => $tDP
														]);

													RegistrarModel::chrngdistlogs($studid, 0, 0, $sched->id, $sched->classid, $sched->balance);
													RegistrarModel::procItemized($studid, $sched->classid, $sched->balance, $syid, $semid);

													$dpBal -= $sched->balance;
													$ledgeramount -= $sched->balance;
												}
												else
												{
													$tDP = $sched->balance - $dpBal;
													$aPay = $sched->amountpay + $dpBal;
													
													$deductDP = db::table('studpayscheddetail')
														->where('id', $sched->id)
														->update([
															'amountpay' => $aPay,
															'balance' => $tDP,
															'updateddatetime' => RegistrarModel::getServerDateTime(),
															'updatedby' => auth()->user()->id
														]);
													
													RegistrarModel::chrngdistlogs($studid, 0, 0, $sched->id, $sched->classid, $dpBal);
													RegistrarModel::procItemized($studid, $sched->classid, $dpBal, $syid, $semid);

													$ledgeramount -= $dpBal;
													$dpBal = 0;
												}
											}
										}

									}

									if($dpBal > 0)
									{
										// echo 'dpbal: ' . $dpBal . 'retdp: ' . $retdp . '<br>';
										if($retdp == 0)
										{
											$retdp = 1;
											goto dptoempty_sh;
											
										}
									}
								}
							}
							else
							{
								//ELSE
								$getpaySched = db::table('studpayscheddetail')
									->where('studid', $studid)
									->where('syid', $syid)
									->where('semid', $semid)
									->get();
								if(count($getpaySched) > 0)
								{
									foreach($getpaySched as $sched)
									{
										if($dpBal > 0)
										{
											// echo '[' . $dpBal . '>' . $sched->amount . ']';
											$schedbal = $sched->amount - $sched->amountpay;
											
											if($dpBal > $schedbal)
											{
												$tDP = 0;
												
												$deductDP = db::table('studpayscheddetail')
													->where('id', $sched->id)
													->update([
														'amountpay' => $schedbal + $sched->amountpay,
														'balance' => $tDP,
														'updateddatetime' => RegistrarModel::getServerDateTime(),
														'updatedby' => auth()->user()->id
														]);

												$dpBal -= $schedbal;
											}
											else
											{
												$tDP = $sched->amount - $dpBal;
												$aPay = $sched->amountpay + $dpBal;

												// echo ' aPay = ' . $aPay;
												
												$deductDP = db::table('studpayscheddetail')
													->where('id', $sched->id)
													->update([
														'amountpay' => $aPay,
														'balance' => $tDP,
														'updateddatetime' => RegistrarModel::getServerDateTime(),
														'updatedby' => auth()->user()->id
													]);
												

												$dpBal = 0;
											}
										}
									}
									
									if($dpBal > 0)
									{
										$gpaydetail = db::select('
											SELECT studid, syid, semid, tuitiondetailid, classid, particulars, SUM(amount) AS amount, SUM(amountpay) AS amountpay, SUM(balance) AS balance 
											FROM studpayscheddetail
											WHERE studid = ?  AND syid = ? and semid = ? AND deleted = 0 and balance > 0
											GROUP BY classid
										', [$studid, $syid, $semid]);

										foreach($gpaydetail as $detail)
										{
											$paysched = db::table('studpayscheddetail')
													->where('classid', $detail->classid)
													->where('studid', $studid)
													->where('syid', $syid)
													->where('semid', $semid)
													->where('deleted', 0)
													->get();

											if($dpBal > 0)
											{		
												foreach($paysched as $sched)
												{
													if($dpBal > $sched->balance)
													{
														$tDP = 0;
														$deductDP = db::table('studpayscheddetail')
															->where('id', $sched->id)
															->update([
																'amountpay' => $sched->balance + $sched->amountpay,
																'balance' => $tDP
															]);

														RegistrarModel::chrngdistlogs($studid, 0, 0, $sched->id, $sched->classid, $sched->balance);
														$dpBal -= $sched->balance;
													}
													else
													{
														$tDP = $sched->balance - $dpBal;
														$aPay = $sched->amountpay + $dpBal;
														
														$deductDP = db::table('studpayscheddetail')
															->where('id', $sched->id)
															->update([
																'amountpay' => $aPay,
																'balance' => $tDP,
																'updateddatetime' => RegistrarModel::getServerDateTime(),
																'updatedby' => auth()->user()->id
															]);
														
														RegistrarModel::chrngdistlogs($studid, 0, 0, $sched->id, $sched->classid, $dpBal);
														$dpBal = 0;
													}
												}
											}

										}

									}		
								}
							
							}
						}	
					}

				}

				//DP Transactions

				// return 'aaa';

				//Cashier Transactions	

				if($ledpay->transid != null)
				{
					$transdetail = db::table('chrngtransdetail')
	  					->select('chrngtransdetail.*', 'cancelled', 'ornum')
	  					->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
	  					->where('chrngtransid', $ledpay->transid)
	  					->where('cancelled', 0)
	  					->where('itemkind', 0)
	  					->where('classid', '!=', $balclassid)
	  					->whereNotIn('classid', $dpclass_array)
	  					->get();
				}				

					
				// if(FinanceModel::like_match('%ADJ%', $ledpay->particulars) == false)
				if($ledpay->transid != null)
				{
					if(count($transdetail) == 0)
	  				{
	  					goto endreset;
	  				}
	  				else
	  				{	
	  					$detailbal = 0;
		  				$detailclassid = 0;
		  				// echo ' '. $ledpay->transid .'; ';
	  					foreach($transdetail as $detail)
		  				{
		  					// if($detail->chrngtransid == 77)
		  					// {
		  					// 	return 'bbb';
		  					// }

		  					if($ledgeramount <= 0)
		  					{
		  						echo 'ledgeramount zero';
		  						goto endreset;
		  					}

		  					echo ' ornum: ' . $detail->ornum . '; ';
		  					$detailclassid = $detail->classid;

		  					$scheddetail = db::table('studpayscheddetail')
		  						->where('studid', $studid)
		  						->where('deleted', 0)
		  						->where('syid', FinanceModel::getSYID())
		  						->where(function($q) use($levelid){
					  				if($levelid == 14 || $levelid == 15)
					  				{
					  					if(FinanceModel::shssetup() == 0)
					  					{
					  						$q->where('semid', FinanceModel::getSemID());
					  					}
					  				}

					  				if($levelid >= 17 && $levelid <= 20)
					  				{
					  					$q->where('semid', FinanceModel::getSemID());
					  				}
					  			})
		  						->where('classid', $detail->classid)
		  						->where('balance', '>', 0)
		  						->first();

		  						// echo ' scheddetail: ' . $scheddetail->id
		  						// echo ' detailbal: ' . $detailbal

		  					if($scheddetail)
		  					{	
		  						echo 'ledgeramount: ' . $ledgeramount . ' ';
		  						// $detailbal = $ledgeramount;
		  						$detailbal += $detail->amount;
		  						echo ' detailbal: ' . $detailbal . ' ';
		  						if($detailbal > $scheddetail->balance)
		  						{
				  					db::table('studpayscheddetail')
				  						->where('id', $scheddetail->id)
				  						->update([
				  							'amountpay' => $scheddetail->amountpay + $scheddetail->balance, //$detail->amount,
				  							'balance' => 0, 
				  							'updateddatetime' => FinanceModel::getServerDateTime(),
				  							'updatedby' => auth()->user()->id
				  						]);

				  					echo ' 1st: ' . $scheddetail->balance;
				  					
				  					$detailbal -= $scheddetail->balance;
				  					$ledgeramount -= $scheddetail->balance;
		  						}
		  						else
		  						{
		  							db::table('studpayscheddetail')
				  					->where('id', $scheddetail->id)
		  							->update([
				  							'amountpay' => $scheddetail->amountpay + $detailbal,
				  							'balance' => $scheddetail->balance - $detailbal, 
				  							'updateddatetime' => FinanceModel::getServerDateTime(),
				  							'updatedby' => auth()->user()->id
				  						]);

		  							echo ' 2nd: ' . $detailbal;

		  							$ledgeramount -= $detailbal;
		  							$detailbal = 0;
		  						}
		  						// echo ' scheddetail: ' . $scheddetail->id;
		  						// echo ' detailbal: ' . $detailbal . '; ';
		  						// echo ' detailid: ' . $detail->id . '; ';

		  						if($detailbal > 0)
		  						{
		  							clearZero:

		  							$scheddetail = db::table('studpayscheddetail')
		  								// ->select('studpayscheddetail.*', 'aaa')
				  						->where('studid', $studid)
				  						->where('deleted', 0)
				  						->where('syid', FinanceModel::getSYID())
				  						->where(function($q) use($levelid){
							  				if($levelid == 14 || $levelid == 15)
							  				{
							  					if(FinanceModel::shssetup() == 0)
							  					{
							  						$q->where('semid', FinanceModel::getSemID());
							  					}
							  				}

							  				if($levelid >= 17 && $levelid <= 20)
							  				{
							  					$q->where('semid', FinanceModel::getSemID());
							  				}
							  			})
				  						->where('classid', $detailclassid)
				  						->where('balance', '>', 0)
				  						->first();

				  					// echo ' classid: ' . $detail->classid . '; ';
				  					if($scheddetail)
				  					{
				  						// $detailbal += $detail->amount;
				  						// echo ' scheddetailid: ' . $scheddetail->id;
				  						
				  						if($detailbal >= $scheddetail->balance)
				  						{
				  							// echo ' scheddetailid: ' . $scheddetail->id;
						  					db::table('studpayscheddetail')
						  						->where('id', $scheddetail->id)
						  						->update([
						  							'amountpay' => $scheddetail->amountpay + $scheddetail->balance, //$detail->amount,
						  							'balance' => 0, 
						  							'updateddatetime' => FinanceModel::getServerDateTime(),
						  							'updatedby' => auth()->user()->id
						  						]);

						  					echo ' 3rd: ' . $scheddetail->balance;
						  					$detailbal -= $scheddetail->balance;
						  					$ledgeramount -= $scheddetail->balancel;
						  					
				  						}
				  						else
				  						{

				  							db::table('studpayscheddetail')
						  					->where('id', $scheddetail->id)
				  							->update([
						  							'amountpay' => $scheddetail->amountpay + $detailbal,
						  							'balance' => $scheddetail->balance - $detailbal, 
						  							'updateddatetime' => FinanceModel::getServerDateTime(),
						  							'updatedby' => auth()->user()->id
						  						]);

				  							echo ' 4th: ' . $detailbal;

				  							$ledgeramount -= $detailbal;
				  							$detailbal = 0;
				  						}

				  						
				  						// echo ' scheddetail: ' . $scheddetail->id;
				  						// echo ' detailbal: ' . $detailbal;
				  					}
				  					else
				  					{
				  						// echo ' secondClear: ' . $detailbal;
				  						$scheddetail = db::table('studpayscheddetail')
		  								// ->select('studpayscheddetail.*', 'aaa')
					  						->where('studid', $studid)
					  						->where('deleted', 0)
					  						->where('syid', FinanceModel::getSYID())
					  						// ->where('classid', $detailclassid)
					  						->where('balance', '>', 0)
					  						->orderBy('id', 'ASC')
					  						->first();

					  					if($scheddetail)
					  					{
					  						if($detailbal >= $scheddetail->balance)
					  						{
							  					db::table('studpayscheddetail')
							  						->where('id', $scheddetail->id)
							  						->update([
							  							'amountpay' => $scheddetail->amountpay + $scheddetail->balance, //$detail->amount,
							  							'balance' => 0, 
							  							'updateddatetime' => FinanceModel::getServerDateTime(),
							  							'updatedby' => auth()->user()->id
							  						]);

							  					echo ' 5th: ' . $scheddetail->balance;
							  					$detailbal -= $scheddetail->balance;
							  					$ledgeramount -= $scheddetail->balance;
					  						}
					  						else
					  						{

					  							db::table('studpayscheddetail')
							  					->where('id', $scheddetail->id)
					  							->update([
							  							'amountpay' => $scheddetail->amountpay + $detailbal,
							  							'balance' => $scheddetail->balance - $detailbal, 
							  							'updateddatetime' => FinanceModel::getServerDateTime(),
							  							'updatedby' => auth()->user()->id
							  						]);

					  							echo ' 6th: ' . $detailbal;

					  							$ledgeramount -= $detailbal;
					  							$detailbal = 0;
					  							
					  						}
					  					}
				  					}

				  					// echo ' lastdetailbal: ' . $detailbal;

				  					if($detailbal > 0)
				  					{
				  						// echo ' chkdetailbal: ' . $detailbal;
				  						goto clearZero;
				  						echo '<br>';
				  					}

		  						}
		  					}
		  					else
		  					{
		  						$scheddetail = db::table('studpayscheddetail')
			  						->where('studid', $studid)
			  						->where('deleted', 0)
			  						->where('syid', FinanceModel::getSYID())
			  						->where(function($q) use($levelid){
						  				if($levelid == 14 || $levelid == 15)
						  				{
						  					if(FinanceModel::shssetup() == 0)
						  					{
						  						$q->where('semid', FinanceModel::getSemID());
						  					}
						  				}

						  				if($levelid >= 17 && $levelid <= 20)
						  				{
						  					$q->where('semid', FinanceModel::getSemID());
						  				}
						  			})
			  						->where('balance', '>', 0)
			  						->first();

			  					if($scheddetail)
			  					{
			  						$detailbal += $detail->amount;
		  						// echo ' scheddetailid: ' . $scheddetail->id;
		  						if($detailbal > $scheddetail->balance)
		  						{
				  					db::table('studpayscheddetail')
				  						->where('id', $scheddetail->id)
				  						->update([
				  							'amountpay' => $scheddetail->amountpay + $scheddetail->balance, //$detail->amount,
				  							'balance' => 0, 
				  							'updateddatetime' => FinanceModel::getServerDateTime(),
				  							'updatedby' => auth()->user()->id
				  						]);

				  					echo ' _1st: ' . $scheddetail->balance;
				  					$detailbal -= $scheddetail->balance;
				  					$ledgeramount -= $scheddetail->balance;
		  						}
		  						else
		  						{

		  							db::table('studpayscheddetail')
				  					->where('id', $scheddetail->id)
		  							->update([
				  							'amountpay' => $scheddetail->amountpay + $detailbal,
				  							'balance' => $scheddetail->balance - $detailbal, 
				  							'updateddatetime' => FinanceModel::getServerDateTime(),
				  							'updatedby' => auth()->user()->id
				  						]);

		  							echo ' _2nd: ' . $detailbal;
		  							$ledgeramount -= $detailbal;
		  							$detailbal = 0;
		  						}
		  						// echo ' scheddetail: ' . $scheddetail->id;
		  						// echo ' detailbal: ' . $detailbal . '; ';
		  						// echo ' detailid: ' . $detail->id . '; ';

		  						if($detailbal > 0)
		  						{
		  							clearZero1:

		  							$scheddetail = db::table('studpayscheddetail')
		  								// ->select('studpayscheddetail.*', 'aaa')
				  						->where('studid', $studid)
				  						->where('deleted', 0)
				  						->where('syid', FinanceModel::getSYID())
				  						->where(function($q) use($levelid){
							  				if($levelid == 14 || $levelid == 15)
							  				{
							  					if(FinanceModel::shssetup() == 0)
							  					{
							  						$q->where('semid', FinanceModel::getSemID());
							  					}
							  				}

							  				if($levelid >= 17 && $levelid <= 20)
							  				{
							  					$q->where('semid', FinanceModel::getSemID());
							  				}
							  			})
				  						->where('classid', $detailclassid)
				  						->where('balance', '>', 0)
				  						->first();

				  					// echo ' classid: ' . $detail->classid . '; ';
				  					if($scheddetail)
				  					{
				  						// $detailbal += $detail->amount;
				  						// echo ' scheddetailid: ' . $scheddetail->id;
				  						
				  						if($detailbal >= $scheddetail->balance)
				  						{
				  							// echo ' scheddetailid: ' . $scheddetail->id;
						  					db::table('studpayscheddetail')
						  						->where('id', $scheddetail->id)
						  						->update([
						  							'amountpay' => $scheddetail->amountpay + $scheddetail->balance, //$detail->amount,
						  							'balance' => 0, 
						  							'updateddatetime' => FinanceModel::getServerDateTime(),
						  							'updatedby' => auth()->user()->id
						  						]);

						  					echo ' _3rd: ' . $scheddetail->balance;
						  					$detailbal -= $scheddetail->balance;
						  					$ledgeramount -= $scheddetail->balance;
				  						}
				  						else
				  						{

				  							db::table('studpayscheddetail')
						  					->where('id', $scheddetail->id)
				  							->update([
						  							'amountpay' => $scheddetail->amountpay + $detailbal,
						  							'balance' => $scheddetail->balance - $detailbal, 
						  							'updateddatetime' => FinanceModel::getServerDateTime(),
						  							'updatedby' => auth()->user()->id
						  						]);

				  							echo ' _4th: ' . $detailbal;
				  							$ledgeramount -= $detailbal;
				  							$detailbal = 0;
				  						}
				  						// echo ' scheddetail: ' . $scheddetail->id;
				  						// echo ' detailbal: ' . $detailbal;
				  					}
				  					else
				  					{
				  						// echo ' secondClear: ' . $detailbal;
				  						$scheddetail = db::table('studpayscheddetail')
		  								// ->select('studpayscheddetail.*', 'aaa')
					  						->where('studid', $studid)
					  						->where('deleted', 0)
					  						->where('syid', FinanceModel::getSYID())
					  						// ->where('classid', $detailclassid)
					  						->where('balance', '>', 0)
					  						->orderBy('id', 'ASC')
					  						->first();

					  					if($scheddetail)
					  					{
					  						if($detailbal >= $scheddetail->balance)
					  						{
							  					db::table('studpayscheddetail')
							  						->where('id', $scheddetail->id)
							  						->update([
							  							'amountpay' => $scheddetail->amountpay + $scheddetail->balance, //$detail->amount,
							  							'balance' => 0, 
							  							'updateddatetime' => FinanceModel::getServerDateTime(),
							  							'updatedby' => auth()->user()->id
							  						]);

							  					echo ' _5th: ' . $scheddetail->balance;
							  					$detailbal -= $scheddetail->balance;
							  					$ledgeramount -= $scheddetail->balance;
					  						}
					  						else
					  						{

					  							db::table('studpayscheddetail')
							  					->where('id', $scheddetail->id)
					  							->update([
							  							'amountpay' => $scheddetail->amountpay + $detailbal,
							  							'balance' => $scheddetail->balance - $detailbal, 
							  							'updateddatetime' => FinanceModel::getServerDateTime(),
							  							'updatedby' => auth()->user()->id
							  						]);

					  							echo ' _6th: ' . $detailbal;
					  							$ledgeramount -= $detailbal;
					  							$detailbal = 0;
					  						}
					  					}
				  					}

				  					// echo ' lastdetailbal: ' . $detailbal;

				  					if($detailbal > 0)
				  					{
				  						// echo ' chkdetailbal: ' . $detailbal;
				  						goto clearZero1;
				  					}

		  						}
			  					}
		  					}

		  					echo ' ___detailbal: ' . $detailbal;

		  					
		  				}
		  				
		  				goto endreset;	
	  				}
	  			}

	  			// return ' aaa';

				//Cashier Transactions

	  			// Adjustment Transactions
	  			// Adjustment:
	  			if(FinanceModel::like_match('%ADJ%', $ledpay->particulars) == true)
	  			{
  					$adjustments = db::table('adjustments')
  						->select('adjustments.id', 'description', 'amount', 'classid', 'isdebit', 'iscredit', 'syid', 'semid', 'adjstatus', 'studid', 'mop', 'adjustments.createddatetime')
  						->join('adjustmentdetails', 'adjustments.id', '=', 'adjustmentdetails.headerid')
  						->where('studid', $studid)
  						->where('adjustments.id', $ledpay->ornum)
  						->where('adjstatus', 'APPROVED')
  						->where('adjustmentdetails.deleted', 0)
  						->get();


  					foreach($adjustments as $adj)
  					{

  						$adjbal = $adj->amount;
			  			$adjbal = number_format($adj->amount, 2, '.', '');
			  			$particulars = $adj->description;

  						if($adj->iscredit == 1)
  						{
	  						adjloop:

	  						$scheddetail = db::table('studpayscheddetail')
				  				->where('studid', $studid)
				  				->where('syid', FinanceModel::getSYID())
				  				->where(function($q) use($levelid){
					  				if($levelid == 14 || $levelid == 15)
					  				{
					  					if(FinanceModel::shssetup() == 0)
					  					{
					  						$q->where('semid', FinanceModel::getSemID());
					  					}
					  				}

					  				if($levelid >= 17 && $levelid <= 20)
					  				{
					  					$q->where('semid', FinanceModel::getSemID());
					  				}
					  			})
				  				->where('classid', $adj->classid)
				  				->where('balance' , '>', 0)
				  				->where('deleted', 0)
				  				->first();		

				  			if($scheddetail)
				  			{
				  				if($scheddetail->balance >= $adjbal)
			  					{	
			  						// echo ' schedid+: ' . $scheddetail->id . '; classid: ' . $scheddetail->classid . '; pay: ' . $adjbal . '; ';
			  						db::table('studpayscheddetail')
					  					->where('id', $scheddetail->id)
					  					->update([
						  						// 'particulars' => $particulars,
						  						'amountpay' => $scheddetail->amountpay + $adjbal,
						  						'balance' => $scheddetail->balance - $adjbal,
						  						'deleted' => 0,
						  						'updatedby' => auth()->user()->id,
						  						'updateddatetime' => FinanceModel::getServerDateTime()
						  					]);

					  				
					  				$adjbal = 0;
					  				// echo ' adjbal: ' . $adjbal . '; ';
			  					}
			  					else
			  					{
			  						// echo ' schedid+: ' . $scheddetail->id . '; classid: ' . $scheddetail->classid . '; pay: ' . $scheddetail->balance . '; ';
			  						db::table('studpayscheddetail')
					  					->where('id', $scheddetail->id)
					  					->update([
						  						// 'particulars' => $particulars,
						  						'amountpay' => $scheddetail->amountpay + $scheddetail->balance,
						  						'balance' => 0,
						  						'deleted' => 0,
						  						'updatedby' => auth()->user()->id,
						  						'updateddatetime' => FinanceModel::getServerDateTime()
						  					]);

					  				$adjbal -= $scheddetail->balance;
					  				// echo ' adjbal: ' . $adjbal . '; ';
			  					}

			  					if($adjbal > 0)
				  				{
				  					goto adjloop;
				  				}
				  			}
				  			else
				  			{
				  				// echo 'adj:- ' .$ledpay->id. '; ';
				  				$scheddetail = db::table('studpayscheddetail')
				  					// ->select('aaa')
					  				->where('studid', $studid)
					  				->where('syid', FinanceModel::getSYID())
					  				->where(function($q) use($levelid){
						  				if($levelid == 14 || $levelid == 15)
						  				{
						  					if(FinanceModel::shssetup() == 0)
						  					{
						  						$q->where('semid', FinanceModel::getSemID());
						  					}
						  				}

						  				if($levelid >= 17 && $levelid <= 20)
						  				{
						  					$q->where('semid', FinanceModel::getSemID());
						  				}
						  			})
					  				->where('balance' , '>', 0)
					  				->where('deleted', 0)
					  				->first();

					  			if($scheddetail)
					  			{
					  				if($scheddetail->balance >= $adjbal)
				  					{	

				  						// echo ' schedid: ' . $scheddetail->id . '; classid: ' . $scheddetail->classid . '; pay: ' . $adjbal;
				  						db::table('studpayscheddetail')
						  					->where('id', $scheddetail->id)
						  					->update([
							  						// 'particulars' => $particulars,
							  						'amountpay' => $scheddetail->amountpay + $adjbal,
							  						'balance' => $scheddetail->balance - $adjbal,
							  						'deleted' => 0,
							  						'updatedby' => auth()->user()->id,
							  						'updateddatetime' => FinanceModel::getServerDateTime()
							  					]);

						  				$adjbal = 0;
						  				// echo ' adjbal: ' . $adjbal . '; ';
				  					}
				  					else
				  					{
				  						// echo ' schedid: ' . $scheddetail->id . '; classid: ' . $scheddetail->classid . '; pay: ' . $scheddetail->balance;
				  						db::table('studpayscheddetail')
						  					->where('id', $scheddetail->id)
						  					->update([
							  						// 'particulars' => $particulars,
							  						'amountpay' => $scheddetail->amountpay + $scheddetail->balance,
							  						'balance' => 0,
							  						'deleted' => 0,
							  						'updatedby' => auth()->user()->id,
							  						'updateddatetime' => FinanceModel::getServerDateTime()
							  					]);

						  				$adjbal -= $scheddetail->balance;
						  				// echo ' adjbal: ' . $adjbal;
				  					}
				  				}
				  				else
				  				{
				  					$adjbal = 0;
				  				}
				  			}

				  			// echo ' adjbal: ' . $adjbal;

				  			if($adjbal > 0)
			  				{
			  					goto adjloop;
			  				}

			  				goto endreset;
				  		}
				  		else
				  		{
				  			$modeofpayment = db::table('paymentsetup')
		  						->select('noofpayment', 'paymentsetupdetail.*')
		  						->join('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
		  						->where('paymentsetup.id', $adj->mop)
		  						->where('paymentsetupdetail.deleted', 0)
		  						->get();

		  					$divadjbal = $adjbal / $modeofpayment[0]->noofpayment;
		  					$divadjbal = number_format($divadjbal, '2', '.', '');
		  					$totaladjbal = 0;

		  					foreach($modeofpayment as $mop)
		  					{
		  						$scheddetail = db::table('studpayscheddetail')
					  				->where('studid', $studid)
					  				->where('syid', FinanceModel::getSYID())
					  				->where(function($q) use($levelid){
						  				if($levelid == 14 || $levelid == 15)
						  				{
						  					if(FinanceModel::shssetup() == 0)
						  					{
						  						$q->where('semid', FinanceModel::getSemID());
						  					}
						  				}

						  				if($levelid >= 17 && $levelid <= 20)
						  				{
						  					$q->where('semid', FinanceModel::getSemID());
						  				}
						  			})
					  				->where('classid', $adj->classid)
					  				->where('paymentno', $mop->paymentno)
					  				->where('deleted', 0)
					  				->first();

					  			if($scheddetail)
					  			{
						  			if($mop->paymentno < $mop->noofpayment)
						  			{
							  			db::table('studpayscheddetail')
							  				->where('id', $scheddetail->id)
							  				->update([
							  					'amount' => $scheddetail->amount + $divadjbal,
							  					'balance' => $scheddetail->balance + $divadjbal,
							  					'updateddatetime' => FinanceModel::getServerDateTime(),
							  					'updatedby' => auth()->user()->id
							  				]);
							  			$totaladjbal += $divadjbal;
						  			}
						  			else
						  			{
						  				$totaladjbal = $adj->amount - $totaladjbal;

						  				db::table('studpayscheddetail')
							  				->where('id', $scheddetail->id)
							  				->update([
							  					'amount' => $scheddetail->amount + $totaladjbal,
							  					'balance' => $scheddetail->balance + $totaladjbal,
							  					'updateddatetime' => FinanceModel::getServerDateTime(),
							  					'updatedby' => auth()->user()->id
							  				]);
						  			}
						  		}
						  		else
						  		{
						  			if($mop->paymentno < $mop->noofpayment)
						  			{
							  			db::table('studpayscheddetail')
							  				->insert([
							  					'studid' => $studid,
							  					'enrollid' => $enrollstud->id,
							  					'syid' => FinanceModel::getSYID(),
							  					'semid' => FinanceModel::getSemID(),
							  					'classid' => $adj->classid,
							  					'paymentno' => $mop->paymentno,
							  					'particulars' => 'ADJ: ' . $adj->description,
							  					'duedate' => $mop->duedate,
							  					'amount' => $divadjbal,
							  					'balance' => $divadjbal,
							  					'createddatetime' => FinanceModel::getServerDateTime(),
							  					'createdby' => auth()->user()->id
							  				]);
							  			$totaladjbal += $divadjbal;
							  		}
							  		else
							  		{
							  			$totaladjbal = $adj->amount - $totaladjbal;

						  				db::table('studpayscheddetail')
							  				->insert([
							  					'studid' => $studid,
							  					'enrollid' => $enrollstud->id,
							  					'syid' => FinanceModel::getSYID(),
							  					'semid' => FinanceModel::getSemID(),
							  					'classid' => $adj->classid,
							  					'paymentno' => $mop->paymentno,
							  					'particulars' => 'ADJ: ' . $adj->description,
							  					'duedate' => $mop->duedate,
							  					'amount' => $totaladjbal,
							  					'balance' => $totaladjbal,
							  					'updateddatetime' => FinanceModel::getServerDateTime(),
							  					'updatedby' => auth()->user()->id
							  				]);
							  		}
						  		}
		  					}
				  		}
  					}
	  			}

	  			// Adjustment Transactions
				
				endreset:	
				// return ' endledger: ' . $ledgeramount;
			}
            
            FinanceModel::reloadforwardbalance($studid, FinanceModel::getSYID(), FinanceModel::getSemID(), $levelid);
            FinanceModel::reloadrefundables($studid, $levelid);
			FinanceModel::ledgeritemizedreset($studid);
			FinanceModel::transitemsreset($studid);
		}
	}

	public function ltid_generate(Request $request)
    {
    	if($request->ajax())
    	{
    		$studledger = db::table('studledger')
    			->select('sid', 'lastname', 'firstname', 'middlename', 'studid', 'payment', 'ornum', 'studledger.id as ledgerid', 'particulars')
    			->join('studinfo', 'studledger.studid', 'studinfo.id')
    			->where('transid', null)
    			->where('studledger.deleted', 0)
    			->where('ornum', '!=', null)
    			->where('particulars', 'not like', '%ADJ:%')
    			->where('void', 0)
    			->get();

    		$list = '';

    		foreach($studledger as $ledger)
    		{
    			$name = $ledger->sid . ' - ' . $ledger->lastname . ', ' . $ledger->firstname. ' ' . $ledger->middlename;

    			$chrngtrans = db::table('chrngtrans')
    				->select('ornum', 'id as transid', 'amountpaid')
    				->where('cancelled', 0)
    				->where('studid', $ledger->studid)
    				->where('ornum', 'like', '%'.$ledger->ornum.'%')
    				->get();

    			foreach($chrngtrans as $trans)
    			{
    				$bg = '';

    				if($ledger->payment != $trans->amountpaid)
    				{
    					$bg = 'bg-danger';
    				}
    				else
    				{
    					$bg = '';
    				}

    				$list .='
    					<tr data-id="'.$ledger->ledgerid.'" data-trans="'.$trans->transid.'" class="'.$bg.'">
    						<td>'.$name.'</td>
    						<td>'.$ledger->particulars.'</td>
    						<td>'.$ledger->ornum.'</td>
    						<td>'.$trans->ornum.'</td>
    						<td>'.number_format($ledger->payment, 2).'</td>
    						<td>'.number_format($trans->amountpaid, 2).'</td>
    						<td>'.$trans->transid.'</td>
    					</tr>
    				';
    			}
    		}

    		$data = array(
    			'list' => $list
    		);

    		echo json_encode($data);
    	}
    }

    public function ltid_copytransid(Request $request)
    {
    	if($request->ajax())
    	{
    		$ledgerid = $request->get('dataid');
    		$transid = $request->get('transid');

    		db::table('studledger')
    			->where('id', $ledgerid)
    			->update([
    				'transid' => $transid
    			]);
    	}
    }

    public function ftd_generate(Request $request)
    {
    	if($request->ajax())
    	{
    		$d_list = '';
    		$list = '';
    		$b_list = '';

    		$ornum = $request->get('ornum');

    		$chrngtrans = db::table('chrngtrans')
    			->where('cancelled', 0)
    			->where(function($q) use($ornum){
    				if($ornum != '')
    				{
    					$q->where('ornum', $ornum);
    				}
    			})
    			->get();

    		foreach($chrngtrans as $trans)
    		{
    			$transdetail = db::table('chrngtransdetail')
    				->select(db::raw('sum(amount) as amount'))
    				->where('chrngtransid', $trans->id)
    				->first();

    			if(floatval($transdetail->amount != floatval($trans->amountpaid)))
    			{
    				$list .='
    					<tr class="trans-item" data-id="'.$trans->id.'">
    						<td>'.$trans->id.'</td>
    						<td>'.$trans->studname.'</td>
    						<td>'.$trans->ornum.'</td>
    						<td>'.$trans->transdate.'</td>
    						<td>'.number_format($trans->amountpaid, 2).'</td>
    					</tr>
    				';
    			}

    		}

    		$data = array(
    			'list' => $list
    		);

    		echo json_encode($data);


    	}
    }

    public function ftd_trans(Request $request)
    {
    	if($request->ajax())
    	{
    		$transid = $request->get('transid');
    		$list = '';

    		$trans = db::table('chrngtrans')
    			->where('id', $transid)
    			->first();

    		$studid = $trans->studid;
    		$transdate = explode(' ', $trans->transdate);

    		$date = $transdate[0];

    		$dfrom = date_create($date);
    		$dfrom = date_format($dfrom, 'Y-m-d 00:00');

    		$dto = date_create($date);
    		$dto = date_format($dto, 'Y-m-d 23:59');

    		$cashtrans = db::table('chrngcashtrans')
    			->where('studid', $studid)
    			->where('deleted', 0)
    			->whereBetween('transdatetime', [$dfrom, $dto])
    			->get();

    		$totalamount = 0;

    		foreach($cashtrans as $cash)
    		{
    			$cashdate = date_create($cash->transdatetime);
    			$cashdate = date_format($cashdate, 'm-d-Y h:n A');

    			$list .='
    				<tr data-id="'.$cash->id.'">
    					<td>'.$cash->transno.'</td>
    					<td>'.$cashdate.'</td>
    					<td>'.$cash->particulars.'</td>
    					<td class="text-right">'.number_format($cash->amount, 2).'</td>
    				<tr>
    			';

    			$totalamount += $cash->amount;
    		}

    		$list .='
    			<tr>
    				<td colspan="3" class="text-right text-bold text-success">Total: </td>
    				<td class="text-right text-bold">'.number_format($totalamount, 2).'</td>
    			</tr>
    		';

    		$data = array(
    			'list' => $list
    		);

    		echo json_encode($data);

    	}
    }

    public function ftd_bunkertotd(Request $request)
    {
    	if($request->ajax())
    	{
    		$bunkerid = $request->get('bunkerid');
    		$transid = $request->get('transid');

    		$cashtrans = db::table('chrngcashtrans')
    			->where('id', $bunkerid)
    			->first();

    		$tdetail = db::table('chrngcashtrans')
    			->join('tuitiondetail', 'chrngcashtrans.classid', '=', 'tuitiondetail.classificationid')
    			->where('chrngcashtrans.id', $bunkerid)
    			->where('syid', FinanceModel::getSYID())
    			->where('tuitiondetail.deleted', 0)
    			->count();

    		$kind = 0;

    		if($tdetail > 0)
    		{
    			$kind = 0;
    		}
    		else
    		{
    			$kind = 1;
    		}

    		db::table('chrngtransdetail')
    			->insert([
    				'chrngtransid' => $transid,
    				'payschedid' => $cashtrans->payscheddetailid,
    				'items' => $cashtrans->particulars,
    				'itemprice' => $cashtrans->itemprice,
    				'qty' => $cashtrans->qty,
    				'amount' => $cashtrans->amount,
    				'classid' => $cashtrans->classid,
    				'itemkind' => $kind
    			]);



    	}
    }

    public function ftd_cashiertdetail(Request $request)
    {
    	if($request->ajax())
    	{
    		$transid = $request->get('transid');
    		$list = '';
    		$totalamount = 0;

    		$chrngtransdetail = db::table('chrngtransdetail')
    			->where('chrngtransid', $transid)
    			->get();


    		foreach($chrngtransdetail as $detail)
    		{
    			$list .='
    				<tr data-id="'.$detail->id.'">
    					<td>'.$detail->items.'</td>
    					<td class="text-right">'.number_format($detail->amount, 2).'</td>
    				</tr>
    			';

    			$totalamount += $detail->amount;
    		}

    		$list .='
    			<tr>
    				<td class="text-right text-bold text-success">Total: </td>
    				<td class="text-right text-bold">'.number_format($totalamount, 2).'</td>
    			</tr>
    		';

    		$data = array(
    			'list' => $list
    		);

    		echo json_encode($data);
    	}
    }
    
    public function ftd_cashiertdetail_edit(Request $request)
    {
    	if($request->ajax())
    	{
    		$detailid = $request->get('detailid');

    		$detail = db::table('chrngtransdetail')
    			->where('id', $detailid)
    			->first();


    		$data = array(
    			'detailid' => $detail->id,
    			'amount' => $detail->amount
    		);

    		echo json_encode($data);
    	}
    }

    public function ftd_cashiertdetail_update(Request $request)
    {
    	$detailid = $request->get('detailid');
    	$amount = str_replace(',', '', $request->get('amount'));

    	db::table('chrngtransdetail')
    		->where('id', $detailid)
    		->update([
    			'amount' => $amount
    		]);
    }

    public function tvl_resetpaysched(Request $request)
    {
    	if($request->ajax())
    	{
    		$studid = $request->get('studid');


    		$batch = db::table('tv_batch')
    			->where('deleted', 0)
    			->where('isactive', 1)
    			->first();

    		$studpayscheddetail = db::table('studpayscheddetail')
    			->where('studid', $studid)
    			->where('enrollid', $batch->id)
    			->where('deleted', 0)
    			->get();

    		foreach($studpayscheddetail as $scheddetail)
    		{
    			db::table('studpayscheddetail')
    				->where('id', $scheddetail->id)
    				->update([
    					'amountpay' => 0,
    					'balance' => $scheddetail->amount
    				]);
    		}

    		// return 'aaa';

    		$studledger = db::table('studledger')
    			->where('studid', $studid)
    			->where('enrollid', $batch->id)
    			->where('deleted', 0)
    			->where('transid', '!=', null)
    			->get();

    		foreach($studledger as $ledger)
    		{
    			$chrngtrans = db::table('chrngtrans')
    				->select(db::raw('studid, ornum, chrngtransdetail.*'))
    				->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
    				->where('chrngtrans.id', $ledger->transid)
    				->where('chrngtrans.cancelled', 0)
    				->where('itemkind', 0)
    				->get();

    			foreach($chrngtrans as $trans)
    			{
    				$sched = db::table('studpayscheddetail')
    					->where('id', $trans->payschedid)
    					->first();


    				db::table('studpayscheddetail')
    					->where('id', $sched->id)
    					->update([
    						'amountpay' => $sched->amountpay + $trans->amount,
    						'balance' => $sched->balance - $trans->amount,
    						'updatedby' => auth()->user()->id,
    						'updateddatetime' => FinanceModel::getServerDateTime()
    					]);
    			}


    		}


    	
    	}
    }

    public static function resetpayment_v3(Request $request)
    {
		$studid = $request->get('studid');
		$syid = $request->get('syid');
		$semid = $request->get('semid');
		$feesid = $request->get('feesid');

		$enrollid = 0;

		$stud = db::table('studinfo')
			->where('id', $studid)
			->first();

		$levelid = 0;
		
		$einfo = db::table('enrolledstud')
			->select('id', 'studstatus', 'levelid')
			->where('syid', $syid)
			->where(function($q) use($semid){
				if($semid == 3)
				{
					$q->where('ghssemid', 3);
				}
				else
				{
					$q->where('ghssemid', '!=', 3);
				}
			})
			->where('studid', $studid)
			->where('deleted', 0)
			->first();

		if($einfo)
		{
			$levelid = $einfo->levelid;
			$enrollid = $einfo->id;
		}
		else
		{
			$einfo = db::table('sh_enrolledstud')
				->select('id', 'studstatus', 'levelid')
				->where('syid', $syid)
				->where(function($q) use($semid){
					if($semid == 3)
					{
						$q->where('semid', 3);
					}
					else
					{
						if(db::table('schoolinfo')->first()->shssetup == 0)
						{
							$q->where('semid', $semid);
						}
						else
						{
							$q->where('semid', '!=', 3);
						}
					}
				})
				->where('studid', $studid)
				->where('deleted', 0)
				->first();

			if($einfo)
			{
				$levelid = $einfo->levelid;
				$enrollid = $einfo->id;
			}
			else
			{
				$einfo = db::table('college_enrolledstud')
					->select('id', 'studstatus', 'yearlevel as levelid')
					->where('syid', $syid)
					->where('semid', $semid)
					->where('studid', $studid)
					->where('deleted', 0)
					->first();

				if($einfo)
				{
					
					$levelid = $einfo->levelid;
					$enrollid = $einfo->id;		
				}
				else
				{
					return 'notenrolled';
				}
			}
		}

		

		//Clear Payments(Ledger, LedgerItemized, StudpayschedLedger, TransItems)

		//Ledger

		$balclassid = db::table('balforwardsetup')->first()->classid;

		$studledger = db::table('studledger')
		->where('studid', $studid)
		->where('syid', $syid)
		->where(function($q) use($levelid, $semid){
			if($levelid == 14 || $levelid == 15)
			{
				if(FinanceModel::shssetup() == 0)
				{
					$q->where('semid', $semid);
				}
			}
			if($levelid >= 17 && $levelid <= 20)
			{
				$q->where('semid', $semid);
			}
		})
		->update([
			'deleted' => 1,
			'deleteddatetime' => FinanceModel::getServerDateTime()
		]);

		// foreach($studledger as $ledger)
		// {
			// if($ledger->classid != $balclassid)
			// {
				// db::table('studledger')
					// ->where('id', $ledger->id)
					// ->update([
						// 'deleted' => 1,
						// 'deleteddatetime' => FinanceModel::getServerDateTime()
					// ]);
			// }
		// }

		//LedgerItemized
		db::table('studledgeritemized')
			->where('studid', $studid)
			->where('syid', $syid)
			->where(function($q) use($levelid, $semid){
				if($levelid == 14 || $levelid == 15)
				{
					if(FinanceModel::shssetup() == 0)
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
			// ->update([
			// 	'deleted' => 1,
			// 	'deleteddatetime' => FinanceModel::getServerDateTime()
			// ]);

		//StudpayschedDetail
		db::table('studpayscheddetail')    		
			->where('studid', $studid)
			->where('syid', $syid)
			->where(function($q) use($levelid, $semid){
				if($levelid == 14 || $levelid == 15)
				{
					if(FinanceModel::shssetup() == 0)
					{
						$q->where('semid', $semid);
					}
				}
				if($levelid >= 17 && $levelid <= 20)
				{
					$q->where('semid', $semid);
				}
			})
			->update([
				'deleted' => 1,
				'deleteddatetime' => FinanceModel::getServerDateTime()
			]);

		db::table('chrngtransitems')
			->where('studid', $studid)
			->where('syid', $syid)
			->where(function($q) use($levelid, $semid){
				if($levelid == 14 || $levelid == 15)
				{
					if(FinanceModel::shssetup() == 0)
					{
						$q->where('semid', $semid);
					}
				}
				if($levelid >= 17 && $levelid <= 20)
				{
					$q->where('semid', $semid);
				}
			})
			->update([
				'deleted' => 1,
				'deleteddatetime' => FinanceModel::getServerDateTime()
			]);


		// return 'aaa';
		//Clear Payments(Ledger, LedgerItemized, StudpayschedLedger)

		//Generate Fees
		FinanceUtilityModel::resetv3_generatefees($studid, $levelid, $enrollid, $syid, $semid, $feesid);
		
		//Generate ESP
		FinanceUtilityModel::resetv3_generateesp($studid, $levelid, $enrollid, $syid, $semid, $feesid);

		//Generate Book Entries
		FinanceUtilityModel::resetv3_generatebookentries($studid, $levelid, $syid, $semid);

		//Generate Balance Forwarding
		// FinanceUtilityModel::resetv3_generateoldaccounts($studid, $levelid, $syid, $semid);
		FinanceUtilityModel::resetv3_generateoa_v2($studid, $levelid, $syid, $semid, 0);
		
		//Generate Labfees
		FinanceUtilityModel::resetv3_generatelabfees($studid, $levelid, $enrollid, $syid, $semid);

		//Generate Discounts
		FinanceUtilityModel::resetv3_generatediscounts($studid, $levelid, $syid, $semid, 0);

		//Generate Adjustments
		FinanceUtilityModel::resetv3_generateadjustments($studid, $levelid, $syid, $semid);

		//Genrate Payments
		FinanceUtilityModel::resetv3_generatepayments($studid, $levelid, $enrollid, $syid, $semid);
		
		return 'done';
    	
    }

    public function besetup_save(Request $request)
    {
    	$classid = $request->get('classid');
    	$itemid = $request->get('itemid');
    	$mopid = $request->get('mopid');

    	$besetup = db::table('bookentrysetup')
    		->first();

    	if($besetup)
    	{
    		db::table('bookentrysetup')
    			->update([
    				'classid' => $classid,
    				'itemid' => $itemid,
    				'mopid' => $mopid
    			]);
    	}
		else
		{
			db::table('bookentrysetup')
				->insert([
					'classid' => $classid,
					'itemid' => $itemid,
					'mopid' => $mopid
				]);
		
    	}
    }

    public function trxitemized_generatetrx(Request $request)
    {
    	if($request->ajax())
    	{
    		$syid = $request->get('syid');
    		$semid = $request->get('semid');
    		$daterange = $request->get('daterange');
    		$daterange = explode(' - ', $daterange);
    		$datefrom = $daterange[0];
    		$dateto = $daterange[1];

    		$datefrom = date_create($datefrom);
    		$datefrom = date_format($datefrom, 'Y-m-d 00:00');
    		$dateto = date_create($dateto);
    		$dateto = date_format($dateto, 'Y-m-d 23:59');

    		$totalamount = 0;

    		$chrngtrans = db::table('chrngtrans')
    			->where('syid', $syid)
    			->where('semid', $semid)
    			->whereBetween('transdate', [$datefrom, $dateto])
    			->where('cancelled', 0)
    			->get();

    		$list = '';

			foreach($chrngtrans as $trans)
			{
				$chrngitems = db::table('chrngtransitems')
					->where('chrngtransid', $trans->id)
					->where('deleted', 0)
					->get();

				if(count($chrngitems) == 0)
				{
					$list.='
						<tr class="bg-warning" data-id="'.$trans->id.'">
							<td>'.$trans->ornum.'</td>
							<td>'.$trans->transdate.'</td>
							<td>'.$trans->syid.'</td>
							<td>'.$trans->semid.'</td>
							<td class="text-right">'.number_format($trans->amountpaid, 2).'</td>
						</tr>
					';
				}
				else
				{
					$list.='
						<tr class="" data-id="'.$trans->id.'">
							<td>'.$trans->ornum.'</td>
							<td>'.$trans->transdate.'</td>
							<td>'.$trans->syid.'</td>
							<td>'.$trans->semid.'</td>
							<td class="text-right">'.number_format($trans->amountpaid, 2).'</td>
						</tr>
					';	
				}

				$totalamount += $trans->amountpaid;
			}

			$list .='
				<tr class="bg-secondary">
					<td colspan=4 class="text-bold text-right">TOTAL: </td>
					<td class="text-right text-bold text-lg text-warning">'.number_format($totalamount, 2).'</td>
				</tr>
			';

			$data = array(
				'list' => $list
			);

			echo json_encode($data);

    	}
    }

    public function trxitemized_generatetrxitemized(Request $request)
    {
    	$transid = $request->get('transid');
    	$syid = $request->get('syid');
    	$semid = $request->get('semid');
    	$dpitems = array();
    	$levelid = 0;
    	$list = '';
    	$totalamount = 0;

    	$trans = db::table('chrngtrans')
    		->where('id', $transid)
    		->first();

    	$stud = db::table('studinfo')
    		->where('id', $trans->studid)
    		->first();

    	if($stud)
    	{
    		$levelid = $stud->levelid;
    	}

    	$trxitemized = db::table('chrngtransitems')
    		->where('chrngtransid', $transid)
    		->where('deleted', 0)
    		->get();

    	if(count($trxitemized) == 0)
    	{
    		$cashtrans = db::table('chrngcashtrans')
    			->where('transno', $trans->transno)
    			->where('kind', '!=', 'item')
    			->get();

    		foreach($cashtrans as $cashtrx)
    		{
    			if($cashtrx->kind = 'misc' || $cashtrx->kind = 'reg' || $cashtrx->kind = 'dp')
    			{
    				$dpsetup = db::table('dpsetup')
    					->where('syid', $syid)
    					->where(function($q) use($levelid, $semid){
    						if($levelid == 14 || $levelid == 15)
    						{
    							if(FinanceModel::shssetup() == 0)
    							{
    								$q->where('semid', $semid);
    							}
    						}
    						if($levelid >= 17 && $levelid <= 20)
    						{
    							$q->where('semid', $semid);
    						}
    					})
    					->where('levelid', $levelid)
    					->where('deleted', 0)
    					->get();

    				foreach($dpsetup as $dp)
    				{
    					array_push($dpitems, $dp->itemid);
    				}

    				$trxamount = $cashtrx->amount;

    				
    				foreach($dpitems as $_items)
    				{

    					$tuition = db::table('tuitionheader')
    						->select(db::raw('syid, semid, levelid, itemid,classificationid AS classid, items.`description`, tuitionitems.`amount`'))
    						->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
    						->join('tuitionitems', 'tuitiondetail.id', '=', 'tuitionitems.tuitiondetailid')
    						->join('items', 'tuitionitems.itemid', '=', 'items.id')
    						->where('levelid', $levelid)
    						->where('syid', $syid)
    						->where(function($q) use($semid, $levelid){
    							if($levelid == 14 || $levelid == 15)
	    						{
	    							if(FinanceModel::shssetup() == 0)
	    							{
	    								$q->where('semid', $semid);
	    							}
	    						}
	    						if($levelid >= 17 && $levelid <= 20)
	    						{
	    							$q->where('semid', $semid);
	    						}
    						})
    						->where('grantee', 1)
    						->where('tuitionheader.deleted', 0)
    						->where('tuitiondetail.deleted', 0)
    						->where('tuitionitems.deleted', 0)
    						->where('itemid', $_items)
    						->first();

    					if($tuition)
    					{
    						if($trxamount > 0)
    						{
    							if($trxamount > $tuition->amount)
    							{
    								$list .='
			    						<tr 
			    							data-transid="'.$trans->id.'" data-ornum="'.$trans->ornum.'" data-itemid="'.$tuition->itemid.'" data-classid="'.$tuition->classid.'" data-amount="'.$tuition->amount.'" data-studid="'.$trans->studid.'" data-syid="'.$trans->syid.'" data-semid="'.$trans->semid.'
			    						">
			    							<td>'.$trans->ornum.'</td>
			    							<td>'.$tuition->description.'</td>
			    							<td class="text-right">'.number_format($tuition->amount, 2).'</td>
			    						</tr>
			    					';		

			    					$trxamount -= $tuition->amount;
			    					$totalamount += $tuition->amount;
    							}
    							else
	    						{
    								$list .='
			    						<tr 
			    							data-transid="'.$trans->id.'" data-ornum="'.$trans->ornum.'" data-itemid="'.$tuition->itemid.'" data-classid="'.$tuition->classid.'" data-amount="'.$trxamount.'" data-studid="'.$trans->studid.'" data-syid="'.$trans->syid.'" data-semid="'.$trans->semid.'
			    						">
			    							<td>'.$trans->ornum.'</td>
			    							<td>'.$tuition->description.'</td>
			    							<td class="text-right">'.number_format($trxamount, 2).'</td>
			    						</tr>
			    					';		

			    					$totalamount += $trxamount;
			    					$trxamount = 0;
	    						}


    						}
    					}
    				}
    			}
    			else
    			{

    			}
    		}
    	}

    	$list .='
    		<tr>
    			<td colspan="2" class="text-right text-bold">TOTAL: </td>
    			<td colspan="2" class="text-right text-bold">'.number_format($totalamount, 2).'</td>
    		</tr>
    	';

    	$data = array(
    		'list' => $list
    	);

    	echo json_encode($data);
    }

    public function trxitemized_savetrxitems(Request $request)
    {
    	if($request->ajax())
    	{
    		$transid = $request->get('transid');
            $ornum = $request->get('ornum');
            $itemid = $request->get('itemid');
            $classid = $request->get('classid');
            $amount = $request->get('amount');
            $studid = $request->get('studid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            db::table('chrngtransitems')
            	->insert([
            		'chrngtransid' => $transid,
            		'ornum' => $ornum,
            		'itemid' => $itemid,
            		'classid' => $classid,
            		'amount' => $amount,
            		'studid' => $studid,
            		'syid' => $syid,
            		'semid' => $semid
            	]);
    	}
    }
	
	public static function api_adjunits(Request $request)
    {
    	$studid = $request->get('studid');
    	$subjcode = $request->get('subjcode');
    	$units = $request->get('units');
    	$process = $request->get('process'); //add, drop, delete
    	$syid = $request->get('syid');
    	$semid = $request->get('semid');

    	// return 'studid: ' . $studid . ', syid: ' . $syid. ' semid: ' . $semid;

    	$amount = 0;
    	$isdebit = 0;
    	$mop = 0;
    	$adjDesc = strtoupper($process) . ' SUBJECT: ' . $subjcode . '- ' . $units . ' Units';

		$stud = db::table('studinfo')
			->where('id', $studid)
			->first();

		$levelid = $stud->levelid;
		$feesid = $stud->feesid;

		$request->request->add(['feesid' => $feesid]);

		$chrngsetup = db::table('chrngsetup')
			->where('groupname', 'TUI')
			->where('deleted', 0)
			->first();

		$classid = 0;

		if($chrngsetup)
		{
			$classid = $chrngsetup->classid;
		}

		$tuition = db::table('tuitionheader')
			->select('amount', 'pschemeid')
			->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
			->where('tuitionheader.id', $feesid)
			->where('tuitiondetail.deleted', 0)
			->where('classificationid', $classid)
			->first();


		if($tuition)
		{
			$amount = $tuition->amount;
			$mop = $tuition->pschemeid;
		}

		$totalamount = $amount * $units;

		if($process == 'add')
		{
			$isdebit = 1;
		}
		else
		{
			$isdebit = 0;
		}

		if($process == 'add' || $process == 'drop')
		{
			$adjid = db::table('adjustments')
				->insertGetId([
					'description' => $adjDesc,
					'classid' => $classid,
					'amount' => $totalamount,
					'mop' => $mop,
					'isdebit' => $isdebit,
					'levelid' => $levelid,
					'syid' => $syid,
					'semid' => $semid,
					'createddatetime' => FinanceModel::getServerDateTime(),
					'adjstatus' => 'APPROVED',
					'remarks' => 'API'
				]);

			db::table('adjustmentdetails')
				->insert([
					'headerid' => $adjid,
					'studid' => $studid,
					'createddatetime' => FinanceModel::getServerDateTime()
				]);

			db::table('adjustments')
				->where('id', $adjid)
				->update([
					'refnum' => 'ADJ'. date('Y') . sprintf('%05d', $adjid),
					'updateddatetime' => FinanceModel::getServerDateTime()
				]);

			// FinanceUtilityModel::resetv3_generateadjustments($studid, $levelid, $syid, $semid);

		}

		return self::resetpayment_v3($request);

		// return 'done';

		
    }
	
	public static function getpaidinfo($syid, $semid)
	{
		$getpaid = db::table('chrngtrans')
			->select(db::raw('chrngtrans.studid, SUM(chrngcashtrans.`amount`) AS amount'), 'chrngtrans.semid')
			->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
			->where('chrngtrans.syid', $syid)
			->where(function($q) use($semid){
				if($semid != null || $semid != 0)
				{
					$q->where('chrngtrans.semid', $semid);		
				}
			})
			//->where('chrngtrans.semid', $semid)
			->where('cancelled', 0)
			->where('kind', '!=', 'item')
			->groupBy('studid')
			->get();
			
		return $getpaid;
	}
	
	public function u_temp_studportal(Request $request)
	{
		return view('finance/utilities/studportal');
	}

	public function u_loadlevel(Request $request, $levelid =null)
	{
		if($request != null)
		{
			$levelid = $request->get('levelid');
			$syid = $request->get('syid');
			$semid = $request->get('semid');
		}

		$gradelevel = db::table('gradelevel')
			->where('deleted', 0)
			->orderBy('sortid')
			->get();

		$levels = '';

		foreach($gradelevel as $level)
		{
			if($level->id == $levelid)
			{
				$levels .='
					<option value="'.$level->id.'" selected>'.$level->levelname.'</option>
				';
			}
			else
			{
				$levels .='
					<option value="'.$level->id.'">'.$level->levelname.'</option>
				';	
			}
		}

		$data = array(
			'levels' => $gradelevel,
			'levelid' => $levelid
		);

		// return $levels;
		return view('finance/utilities/viewtuition', $data);
		// return view('finance/utilities/viewtuition')->with('data', $data);
	}

	public function u_loadtuitionheader(Request $request, $levelid=null, $syid=null, $semid=null)
	{
		if($request != null)
		{
			$levelid = $request->get('levelid');
			$syid = $request->get('syid');
			$semid = $request->get('semid');
		}

		$tuition = db::table('tuitionheader')
			->where('levelid', $levelid)
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
			->where('deleted', 0)
			->get();

		$fees = '';

		foreach($tuition as $tui)
		{
			$fees .='
				<option value="'.$tui->id.'">'.$tui->description.'</option>
			';
		}

		return $fees;

	}

	public static function u_viewtuitiondetails(Request $request, $feesid=null)
    {
    	if($request != null)
    	{
	    	$feesid = $request->get('feesid');
	    }

    	$tuition = db::table('tuitionheader')
    		->select(db::raw('tuitionheader.`description`, itemclassification.`description` AS classname,  tuitiondetail.id AS detailid'))
    		->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
    		->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
    		->where('tuitionheader.id', $feesid)
    		->where('tuitionheader.deleted', 0)
    		->where('tuitiondetail.deleted', 0)
    		->groupBy('itemclassification.id')
    		->get();

    	$list = '';
    	$grandtotal = 0;

    	foreach($tuition as $tui)
    	{
    		$details = db::table('tuitionitems')
    			->select(db::raw('itemclassification.description AS classname, items.description AS itemname, tuitionitems.amount'))
    			->join('tuitiondetail', 'tuitionitems.tuitiondetailid', '=', 'tuitiondetail.id')
    			->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
    			->join('items', 'tuitionitems.itemid', '=', 'items.id')
    			->where('tuitiondetailid', $tui->detailid)
    			->where('tuitionitems.deleted', 0)
    			->get();

    		$totaldetail = 0;

    		if(count($details) > 1)
    		{
    			$list .='
    				<tr>
    					<td colspan="2" class="text-bold">'.$tui->classname.'</td>
    				</tr>
    			';

    			foreach($details as $detail)
    			{
    				$list .='
    					<tr>
    						<td>'.$detail->itemname.'</td>
    						<td class="text-right">'.number_format($detail->amount, 2).'</td>
    					</tr>
    				';

    				$totaldetail += $detail->amount;
    				$grandtotal += $detail->amount;
    			}

    			$list.='
    				<tr>
    					<td class="text-bold text-right">TOTAL: </td>
    					<td class="text-bold text-right">'.number_format($totaldetail, 2).'</td>
    				</tr>
    			';
    		}
    		else
    		{
    			foreach($details as $detail)
    			{
    				$list .='
    					<tr>
    						<td class="text-bold">'.$detail->itemname.'</td>
    						<td class="text-right text-bold">'.number_format($detail->amount, 2).'</td>
    					</tr>
    				';	

    				$grandtotal += $detail->amount;
    			}
    		}
    	}

    	$list.='
			<tr>
				<td class="text-bold text-right">GRAND TOTAL: </td>
				<td class="text-bold text-right">'.number_format($grandtotal, 2).'</td>
			</tr>
		';

    	return $list;

    	// return view('finance/utilities/viewtuition', compact('tuition', 'tuition'));
    	// $pdf = PDF::loadview('enrollment/pdf/studentinformation',compact('studinfo','schoolinfo'))->setPaper('8.5x11','portrait'); 
    }
	
	public static function assessment_gen(Request $request)
    {
    	$studid = $request->get('studid');
    	$syid = $request->get('syid');
    	$semid = $request->get('semid');
    	$month = $request->get('monthid');
    	$levelid = 0;
    	$amount = 0;
    	$paymentno = 0;
		$paymonth = 0;
    	// return $month;

		// dd($request);

    	$einfo = db::table('enrolledstud')
    		->select('levelid')
    		->where('studid', $studid)
    		->where('syid', $syid)
    		->where(function($q) use($semid){
    			if($semid == 3)
    			{
    				$q->where('ghssemid', 3);
    			}
    			else
    			{
    				$q->where('ghssemid', '!=', 3);
    			}
    		})
    		->where('deleted', 0)
    		->first();

    	if($einfo)
    	{
    		$levelid = $einfo->levelid;
			$paymonth = 10;
    	}
    	else
    	{
    		$einfo = db::table('sh_enrolledstud')
    			->select('levelid')
    			->where('studid', $studid)
    			->where('syid', $syid)
    			->where(function($q) use($semid){
    				if($semid == 3)
    				{
    					$q->where('semid', 3);
    				}
    				else
    				{
    					if(db::table('schoolinfo')->first()->shssetup == 0)
    					{
    						$q->where('semid', $semid);
    					}
    					else
    					{
    						$q->where('semid', '!=', 3);
    					}
    				}
    			})
    			->where('deleted', 0)
    			->first();

    		if($einfo)
    		{
    			$levelid = $einfo->levelid;
				$paymonth = 10;
    		}
    		else
    		{
    			$einfo = db::table('college_enrolledstud')
    				->select('yearLevel as levelid')
    				->where('studid', $studid)
    				->where('syid', $syid)
    				->where('semid', $semid)
    				->where('deleted', 0)
    				->first();

    			if($einfo)
    			{
    				$levelid = $einfo->levelid;
					$paymonth = 5;
    			}
    			else
    			{
    				$levelid = db::table('studinfo')->where('id', $studid)->first()->levelid;
    			}
    		}
    	}

 		$paysched = db::table('studpayscheddetail')
 			->select(db::raw('SUM(amount) AS amount'))
 			->where('studid', $studid)
 			->where('syid', $syid)
 			->where(function($q) use($semid, $levelid){
 				if($levelid == 14 || $levelid == 15)
 				{
 					if($semid == 3)
    				{
    					$q->where('semid', 3);
    				}
    				else
    				{
    					if(db::table('schoolinfo')->first()->shssetup == 0)
    					{
    						$q->where('semid', $semid);
    					}
    					else
    					{
    						$q->where('semid', '!=', 3);
    					}
    				}
 				}
 				elseif($levelid >= 17 && $levelid <= 21)
 				{
 					$q->where('semid', $semid);
 				}
 				else
 				{
 					if($semid == 3)
	    			{
	    				$q->where('semid', 3);
	    			}
	    			else
	    			{
	    				$q->where('semid', '!=', 3);
	    			}
 				}
 			})
 			->where('deleted', 0)
 			->first();

 		if($paysched)
 		{
 			$amount = $paysched->amount/$paymonth;
 		}

 		$paysetup = db::table('paymentsetup')
 			->select('paymentsetupdetail.*')
 			->join('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
 			->where(function($q) use($levelid){
				if($levelid == 14 || $levelid == 15)
				{
					$q->where('paymentsetup.id', 3);
				}
				elseif($levelid >= 17 && $levelid <= 21)
				{
					$q->where('paymentsetup.id', 4);
				}
				else{
					$q->where('paymentsetup.id', 3);
				}
			})
 			->where('paymentsetupdetail.deleted', 0)
 			->get();

 		$payment_array = array();

 		foreach($paysetup as $pay)
 		{
 			$pay_month = date_format(date_create($pay->duedate), 'n');
 			if($pay_month == $month)
 			{
 				$paymentno = $pay->paymentno;
 			}

 			array_push($payment_array, (object)[
 				'paymentno' => $pay->paymentno,
 				'duedate' => $pay->duedate,
 				'amount' => $amount
 			]);
 		}



 		$fees = collect($payment_array);

 		// return $fees;

 		$paysched = db::table('studpayscheddetail')
 			->select(db::raw('SUM(amountpay) AS amount'))
 			->where('studid', $studid)
 			->where('syid', $syid)
 			->where(function($q) use($semid, $levelid){
 				if($levelid == 14 || $levelid == 15)
 				{
 					if($semid == 3)
    				{
    					$q->where('semid', 3);
    				}
    				else
    				{
    					if(db::table('schoolinfo')->first()->shssetup == 0)
    					{
    						$q->where('semid', $semid);
    					}
    					else
    					{
    						$q->where('semid', '!=', 3);
    					}
    				}
 				}
 				elseif($levelid >= 17 && $levelid <= 21)
 				{
 					$q->where('semid', $semid);
 				}
 				else
 				{
 					if($semid == 3)
	    			{
	    				$q->where('semid', 3);
	    			}
	    			else
	    			{
	    				$q->where('semid', '!=', 3);
	    			}
 				}
 			})
 			->where('deleted', 0)
 			->first();

 		$totalpayment = $paysched->amount;

 		$assessment = array();

 		foreach($fees as $fee)
 		{
 			$month = date_format(date_create($fee->duedate), 'F');

 			if($totalpayment > 0)
 			{
	 			if($totalpayment > $fee->amount)
	 			{
	 				array_push($assessment, (object)[
		 				'paymentno' => $fee->paymentno,
		 				'duedate' => $fee->duedate,
		 				'amount' => '0.00',
		 				'particulars' => $month
		 			]);

		 			$totalpayment -= $fee->amount;
	 			}
	 			else
	 			{
					array_push($assessment, (object)[
		 				'paymentno' => $fee->paymentno,
		 				'duedate' => $fee->duedate,
		 				'amount' => number_format($fee->amount - $totalpayment, 2),
		 				'particulars' => $month
		 			]); 				

		 			$totalpayment = 0;
	 			}
	 		}
	 		else
	 		{
				array_push($assessment, (object)[
	 				'paymentno' => $fee->paymentno,
	 				'duedate' => $fee->duedate,
	 				'amount' => number_format($fee->amount, 2),
	 				'particulars' => $month
	 			]);	 			
	 		}
 			
 		}



 		$assessment = collect($assessment)->where('paymentno', '<=', $paymentno);

 		return $assessment;
    }
	
}