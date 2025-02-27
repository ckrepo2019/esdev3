<?php

namespace App\Http\Controllers\enrollment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\RegistrarModel;
use App\Http\Controllers\FinanceControllers;
use App\Models\Grading\GradingReport;
use App\SyncModel;
use PDF;
use Illuminate\Support\Facades\Hash;

class StudentManagementController extends Controller
{
	public function studentmanagement()
	{
		return view('enrollment/studmanagement');	
	}

	public function sm_loadstudents(Request $request)
	{
		if($request->ajax())
		{
			$status = $request->get('status');
			$type = $request->get('type');
			$filter = $request->get('filter');
			$levelid = $request->get('levelid');
			$status2 = $request->get('status2');
			$syid = $request->get('syid');
			$semid = $request->get('semid');

			// return $levelid;

			$studlist = '';
			$studinfo = array();

			if($status2 == 'online')
			{
				$studinfo = db::table('studinfo')
					->select(db::raw('studinfo.id, studinfo.sid, lastname, firstname, middlename, levelname, grantee.`description` AS grantee, studentstatus.`description` AS studstatus, CONCAT(studinfo.sid, " ", lastname, ", ", firstname) AS studname, studinfo.studstatus as statusid, sectionname, studinfo.nodp, studinfo.levelid'))
					->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
					->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
					->join('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
					->join('student_pregistration', 'studinfo.id', '=', 'student_pregistration.studid')
					->where('studinfo.deleted', 0)
					->where(function($q) use($levelid, $status, $status2){
						if($levelid != 0)
						{
							$q->where('levelid', $levelid);
						}

						if($status2 != 'notenrolled')
						{
							if($status != 0)
							{
								$q->where('studstatus', $status);
							}
						}
						else
						{
							$q->where('studstatus', 0);
						}
					})
					->where('syid', $syid)
					->where('student_pregistration.semid', $semid)
					->having('studname', 'like', '%'.$filter.'%')
					->orderBy('lastname')
					->orderBy('firstname')
					->get();
			}
			elseif($status2 == '' && $status != 0)
			{
				if($levelid == 0)
				{
					$_studinfo = db::table('studinfo')
						->select(db::raw('studinfo.id, studinfo.sid, lastname, firstname, middlename, levelname, grantee.`description` AS grantee, studentstatus.`description` AS studstatus, CONCAT(studinfo.sid, " ", lastname, ", ", firstname) AS studname, enrolledstud.studstatus as statusid, sections.sectionname, studinfo.nodp, studinfo.levelid'))
						->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
						->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
						->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
						->join('sections', 'enrolledstud.sectionid', '=', 'sections.id')
						->join('studentstatus', 'enrolledstud.studstatus', '=', 'studentstatus.id')
						->where('enrolledstud.deleted', 0)
						->where('studinfo.deleted', 0)
						->where(function($q) use($levelid, $status, $status2){
							if($levelid != 0)
							{
								$q->where('levelid', $levelid);
							}

							if($status2 != 'notenrolled')
							{
								if($status != 0)
								{
									$q->where('enrolledstud.studstatus', $status);
								}
							}
							else
							{
								$q->where('enrolledstud.studstatus', 0);
							}
						})
						->where('enrolledstud.syid', $syid)
						->having('studname', 'like', '%'.$filter.'%')
						->orderBy('lastname')
						->orderBy('firstname')
						->get();

					foreach($_studinfo as $info)
					{
						array_push($studinfo, (object)[
							'id' => $info->id,
							'sid' => $info->sid,
							'lastname' => $info->lastname,
							'firstname' => $info->firstname,
							'middlename' => $info->middlename,
							'levelname' => $info->levelname,
							'grantee' => $info->grantee,
							'studstatus' => $info->studstatus,
							'studname' => $info->studname,
							'statusid' => $info->statusid,
							'sectionname' => $info->sectionname,
							'nodp' => $info->nodp,
							'levelid' => $info->levelid
						]);
					}

					$_studinfo = db::table('studinfo')
						->select(db::raw('studinfo.id, studinfo.sid, lastname, firstname, middlename, levelname, grantee.`description` AS grantee, studentstatus.`description` AS studstatus, CONCAT(studinfo.sid, " ", lastname, ", ", firstname) AS studname, sh_enrolledstud.studstatus as statusid, sections.sectionname, studinfo.nodp, studinfo.levelid'))
						->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
						->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
						->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
						->join('sections', 'sh_enrolledstud.sectionid', '=', 'sections.id')
						->join('studentstatus', 'sh_enrolledstud.studstatus', '=', 'studentstatus.id')
						->where('sh_enrolledstud.deleted', 0)
						->where('studinfo.deleted', 0)
						->where(function($q) use($levelid, $status, $status2){
							if($levelid != 0)
							{
								$q->where('levelid', $levelid);
							}

							if($status2 != 'notenrolled')
							{
								if($status != 0)
								{
									$q->where('sh_enrolledstud.studstatus', $status);
								}
							}
							else
							{
								$q->where('sh_enrolledstud.studstatus', 0);
							}
						})
						->where('sh_enrolledstud.syid', $syid)
						->where('sh_enrolledstud.semid', $semid)
						->having('studname', 'like', '%'.$filter.'%')
						->orderBy('lastname')
						->orderBy('firstname')
						->get();

					foreach($_studinfo as $info)
					{
						array_push($studinfo, (object)[
							'id' => $info->id,
							'sid' => $info->sid,
							'lastname' => $info->lastname,
							'firstname' => $info->firstname,
							'middlename' => $info->middlename,
							'levelname' => $info->levelname,
							'grantee' => $info->grantee,
							'studstatus' => $info->studstatus,
							'studname' => $info->studname,
							'statusid' => $info->statusid,
							'sectionname' => $info->sectionname,
							'nodp' => $info->nodp,
							'levelid' => $info->levelid
						]);
					}

					$_studinfo = db::table('studinfo')
						->select(db::raw('studinfo.id, studinfo.sid, lastname, firstname, middlename, levelname, grantee.`description` AS grantee, studentstatus.`description` AS studstatus, CONCAT(studinfo.sid, " ", lastname, ", ", firstname) AS studname, college_enrolledstud.studstatus as statusid, college_sections.sectionDesc as sectionname, studinfo.nodp, studinfo.levelid'))
						->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
						->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
						->join('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
						->join('college_sections', 'college_enrolledstud.sectionid', '=', 'college_sections.id')
						->join('studentstatus', 'college_enrolledstud.studstatus', '=', 'studentstatus.id')
						->where('college_enrolledstud.deleted', 0)
						->where('studinfo.deleted', 0)
						->where(function($q) use($levelid, $status, $status2){
							if($levelid != 0)
							{
								$q->where('levelid', $levelid);
							}

							if($status2 != 'notenrolled')
							{
								if($status != 0)
								{
									$q->where('college_enrolledstud.studstatus', $status);
								}
							}
							else
							{
								$q->where('college_enrolledstud.studstatus', 0);
							}
						})
						->where('college_enrolledstud.syid', $syid)
						->where('college_enrolledstud.semid', $semid)
						->having('studname', 'like', '%'.$filter.'%')
						->orderBy('lastname')
						->orderBy('firstname')
						->get();

					foreach($_studinfo as $info)
					{
						array_push($studinfo, (object)[
							'id' => $info->id,
							'sid' => $info->sid,
							'lastname' => $info->lastname,
							'firstname' => $info->firstname,
							'middlename' => $info->middlename,
							'levelname' => $info->levelname,
							'grantee' => $info->grantee,
							'studstatus' => $info->studstatus,
							'studname' => $info->studname,
							'statusid' => $info->statusid,
							'sectionname' => $info->sectionname,
							'nodp' => $info->nodp,
							'levelid' => $info->levelid
						]);
					}

					$studinfo = collect($studinfo)->sortBy('lastname');
				}
				else
				{
					$_enroll = '';
					$_levelname = '';

					if($levelid == 14 || $levelid == 15)
					{
						$_enroll = 'sh_enrolledstud';
						$_levelname = 'levelid';
						$_section = 'sections';
						$_sectionname = 'sectionname';
					}
					elseif($levelid >= 17 && $levelid <= 20)
					{
						$_enroll = 'college_enrolledstud';
						$_levelname = 'yearLevel';
						$_section = 'college_sections';
						$_sectionname = 'sectionDesc';
					}
					else
					{
						$_enroll = 'enrolledstud';
						$_levelname = 'levelid';
						$_section = 'sections';
						$_sectionname = 'sectionname';
					}

					$studinfo = db::table('studinfo')
						->select(db::raw('studinfo.id, studinfo.sid, lastname, firstname, middlename, levelname, grantee.`description` AS grantee, studentstatus.`description` AS studstatus, CONCAT(studinfo.sid, " ", lastname, ", ", firstname) AS studname, '.$_enroll.'.studstatus as statusid, '.$_section.' . '.$_sectionname.' as sectionname, studinfo.nodp, studinfo.levelid'))
						->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
						->join($_enroll, 'studinfo.id', $_enroll .'.studid')
						->join('gradelevel', $_enroll . '.' . $_levelname, '=', 'gradelevel.id')
						->join('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
						->join($_section, $_enroll . '.sectionid', '=', $_section. '.id')
						->where('studinfo.deleted', 0)
						->where($_enroll .'.deleted', 0)
						->where($_enroll . '.syid', $syid)
						->where(function($q) use($_enroll, $semid, $levelid){
							if($levelid == 14 || $levelid == 15)
							{
								$q->where($_enroll . '.semid', $semid);
							}

							if($levelid >= 17 && $levelid <= 20)
							{
								$q->where($_enroll . '.semid', $semid);	
							}
						})
						->where(function($q) use($levelid, $status, $status2, $_enroll, $_levelname){
							if($levelid != 0)
							{
								// $q->where($_enroll'levelid', $levelid);
								$q->where($_enroll . '.' . $_levelname, $levelid);
							}

							if($status2 != 'notenrolled')
							{
								if($status != 0)
								{
									$q->where($_enroll . '.studstatus', $status);
								}
							}
							else
							{
								$q->where($_enroll . '.studstatus', 0);
							}
						})
						->having('studname', 'like', '%'.$filter.'%')
						->orderBy('lastname')
						->orderBy('firstname')
						->get();
				}
			}
			else
			{
				$studinfo = db::table('studinfo')
					->select(db::raw('studinfo.id, studinfo.sid, lastname, firstname, middlename, levelname, grantee.`description` AS grantee, studentstatus.`description` AS studstatus, CONCAT(studinfo.sid, " ", lastname, ", ", firstname) AS studname, studinfo.studstatus as statusid, sectionname, studinfo.nodp, studinfo.levelid'))
					->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
					->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
					->join('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
					->where('studinfo.deleted', 0)
					->where(function($q) use($levelid, $status, $status2){
						if($levelid != 0)
						{
							$q->where('levelid', $levelid);
						}

						if($status2 != 'notenrolled')
						{
							if($status != 0)
							{
								$q->where('studstatus', $status);
							}
						}
						else
						{
							$q->where('studstatus', 0);
						}
					})
					->having('studname', 'like', '%'.$filter.'%')
					->orderBy('lastname')
					->orderBy('firstname')
					->get();
			}

			// return $studinfo;

			foreach($studinfo as $stud)
			{
				$name = $stud->sid . ' - ' . $stud->lastname . ', ' . $stud->firstname . ' ' . $stud->middlename;
				$levelid = $stud->levelid;

				$bg = '';
				$payment = '';
				$paybg = '';

				$getDP = db::table('studinfo')
					->select(db::raw('ornum, SUM(chrngtransdetail.`amount`) AS amount, transdate'))
					->join('chrngtrans', 'studinfo.id', '=', 'chrngtrans.studid')
					->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
					->where('deleted', 0)
					->where('cancelled', 0)
					->where('itemkind', 0)
					->where('studid', $stud->id)
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
					->groupBy('ornum')
					->first();

				if($getDP)
				{
					$payment = 'DP PAID';
					$paybg = 'bg-success';
				}
				else
				{
					if($stud->nodp == 1)
					{
						$payment = 'ALLOW NO DP';
						$paybg = 'bg-warning';
					}
				}

				if($stud->statusid == 1)
				{
					$bg = 'bg-success';
				}
				elseif($stud->statusid == 2)
				{
					$bg = 'bg-primary';	
				}
				elseif($stud->statusid == 3)
				{
					$bg = 'bg-danger';
				}
				elseif($stud->statusid == 4)
				{
					$bg = 'bg-warning';
				}
				elseif($stud->statusid == 5)
				{
					$bg = 'bg-secondary';
				}
				elseif($stud->statusid == 6)
				{
					$bg = 'bg-orange';
				}
				else
				{
					$bg ='bg-default';
				}

				// return $status2;

				if($status2 == 'ready')
				{

					if($getDP && $stud->statusid == 0)
					{
						// echo $stud->sid . ' payment: ' . $payment . ' status: ' . $stud->statusid . ' ' .$status2. '<br>' ;
						$studlist .='
							<tr data-id="'.$stud->id.'">
								<td>'.$name.'</td>
								<td style="width:140px">'.$stud->levelname.'</td>
								<td>'.$stud->sectionname.'</td>
								<td>'.$stud->grantee.'</td>
								<td class="'.$bg.'">'.$stud->studstatus.'</td>
								<td style="width:111px" class="'.$paybg.'">'.$payment.'</td>
							</tr>
						';		
					}
					else
					{
						if($stud->nodp == 1 && $stud->statusid == 0)
						{
							// echo $stud->sid . ' payment: ' . $payment . ' status: ' . $stud->statusid . ' ' .$status2. '<br>' ;
							$studlist .='
								<tr data-id="'.$stud->id.'">
									<td>'.$name.'</td>
									<td style="width:140px">'.$stud->levelname.'</td>
									<td>'.$stud->sectionname.'</td>
									<td>'.$stud->grantee.'</td>
									<td class="'.$bg.'">'.$stud->studstatus.'</td>
									<td style="width:111px" class="'.$paybg.'">'.$payment.'</td>
								</tr>
							';				
						}
					}
				}
				elseif($status2 == 'online')
				{
					$chkonline = db::table('student_pregistration')
						->where('deleted', 0)
						->where('syid', $syid)
						->where('semid', $semid)
						->where('studid', $stud->id)
						->first();

					if($chkonline && $stud->statusid == 0)
					{
						$studlist .='
							<tr data-id="'.$stud->id.'">
								<td>'.$name.'</td>
								<td style="width:140px">'.$stud->levelname.'</td>
								<td>'.$stud->sectionname.'</td>
								<td>'.$stud->grantee.'</td>
								<td class="'.$bg.'">'.$stud->studstatus.'</td>
								<td style="width:111px" class="'.$paybg.'">'.$payment.'</td>
							</tr>
						';		
					}
				}
				else
				{
					$studlist .='
						<tr data-id="'.$stud->id.'">
							<td>'.$name.'</td>
							<td style="width:140px">'.$stud->levelname.'</td>
							<td>'.$stud->sectionname.'</td>
							<td>'.$stud->grantee.'</td>
							<td class="'.$bg.'">'.$stud->studstatus.'</td>
							<td style="width:111px" class="'.$paybg.'">'.$payment.'</td>
						</tr>
					';
				}
			}

			$data = array(
				'studlist' => $studlist
			);

			echo json_encode($data);

		}
	}

	public function sm_savestudent(Request $request)
	{
		$dataid = $request->get('dataid');
		$studid = 0;
		$cloudstudid = $request->get('studid');
		$cloudsid = $request->get('cloudsid');

		$lrn = $request->get('lrn');
		$levelid = $request->get('levelid');
		$grantee = $request->get('grantee');
		$mol = $request->get('modality');
		$studtype = $request->get('studtype');
		$firstname = $request->get('firstname');
		$middlename = $request->get('middlename');
		$lastname = $request->get('lastname');
		$suffix = $request->get('suffix');
		$dob = $request->get('dob');
		$gender = $request->get('gender');
		$contactno = $request->get('contactno');
		$religion = $request->get('religion');
		$mt = $request->get('mt');
		$eg = $request->get('eg');
		$nationality = $request->get('nationality');
		$street = $request->get('street');
		$barangay = $request->get('barangay');
		$city = $request->get('city');
		$province = $request->get('province');
		$fathername = $request->get('fathername');
		$fatheroccupation = $request->get('fatheroccupation');
		$fathercontactno = $request->get('fathercontactno');
		$fatherdefault = $request->get('fatherdefault');
		$mothername = $request->get('mothername');
		$motheroccupation = $request->get('motheroccupation');
		$mothercontactno = $request->get('mothercontactno');
		$motherdefault = $request->get('motherdefault');
		$guardianname = $request->get('guardianname');
		$guardianrelation = $request->get('guardianrelation');
		$guardiancontactno = $request->get('guardiancontactno');
		$guardiandefault = $request->get('guardiandefault');
		$bt = $request->get('bt');
		$allergy = $request->get('allergy');
		$medoth = $request->get('medoth');
		$lastschool = $request->get('lastschool');
		$lastsy = $request->get('lastsy');
		$rfid = $request->get('rfid');
		$pantawid = $request->get('pantawid');
		$courseid = $request->get('courseid');
		$district = $request->get('district');
		$region = $request->get('region');

		$return = '';

		$regstyle = db::table('schoolinfo')
			->first()
			->studinfo_crud;

		// return $dob;

		if($lastname == '' || $firstname == '' || $dob == '' || $levelid == 0)
		{
			$studid = 0;
			$return = 'lacking';
			goto endsave;
		}

		if($dataid == 0)
		{
			$checkinfo = db::table('studinfo')
				->where('lastname', $lastname)
				->where('firstname', $firstname)
				->where('deleted', 0)
				->count();

			if($checkinfo > 0)
			{
				$studid = 0;
				$return = 'exist';
			}
			else
			{
				
				$studid = DB::table('studinfo')
	  				->insertGetId([
	  					'id' => $cloudstudid,
	  					'sid' => $cloudsid,
	  					'lrn' => $lrn,
	  					'grantee' => $grantee,
	  					'levelid' => $levelid,
	  					'firstname' => $firstname,
	  					'middlename' => $middlename,
	  					'lastname' => $lastname,
	  					'suffix' => $suffix,
	  					'dob' => $dob,
	  					'gender' => $gender,
	  					'contactno' => $contactno,
	  					'religionid' => $religion,
	  					'mtid' => $mt,
	  					'egid' => $eg,
	  					'street' => $street,
	  					'barangay' => $barangay,
	  					'city' => $city,
	  					'province' => $province,
	  					'fathername' => $fathername,
	  					'foccupation' => $fatheroccupation,
	  					'fcontactno' => $fathercontactno,
	  					'isfathernum' => $fatherdefault,
	  					'mothername' => $mothername,
	  					'moccupation' => $motheroccupation,
	  					'mcontactno' => $mothercontactno,
	  					'ismothernum' => $motherdefault,
	  					'guardianname' => $guardianname,
	  					'guardianrelation' => $guardianrelation,
	  					'gcontactno' => $guardiancontactno,
	  					'isguardannum' => $guardiandefault,
	  					'bloodtype' => $bt,
	  					'allergy' => $allergy,
	  					'others' => $medoth,
	  					'rfid' => $rfid,
	  					'mol' => $mol,
	  					'nationality' => $nationality,
	  					'lastschoolatt' => $lastschool,
	  					'lastschoolsy' => $lastsy,
	  					'deleted' => 0,
	  					'studtype' => $studtype,
	  					'pantawid' => $pantawid,
	  					'courseid' => $courseid,
	  					'district' => $district,
	  					'region' => $region,
	  					'createddatetime' => RegistrarModel::getServerDateTime(),
	  					'createdby' => auth()->user()->id
	  				]);

	  			if($regstyle == 'offline')
	  			{
		  			$acadid = db::table('gradelevel')
			  			->where('id', $levelid)
			  			->first()
			  			->acadprogid;	  		
			  		
			  		$sid = RegistrarModel::idprefix($acadid, $studid);

			  		$upd = db::table('studinfo')
			  			->where('id', $studid)
			  			->update([
			  				'sid' => $sid
			  			]);
			  	}

				$return = 'done';
			}
		}
		else
		{
			$checkinfo = db::table('studinfo')
				->where('firstname', $firstname)
				->where('lastname', $lastname)
				->where('deleted', 0)
				->where('id', '!=', $dataid)
				->count();

			// return $checkinfo;

			if($checkinfo > 0)
			{
				$studid = 0;
				$return = 'exist';
			}
			else
			{
				DB::table('studinfo')
					->where('id', $dataid)
	  				->update([
	  					'lrn' => $lrn,
	  					'grantee' => $grantee,
	  					'levelid' => $levelid,
	  					'firstname' => $firstname,
	  					'middlename' => $middlename,
	  					'lastname' => $lastname,
	  					'suffix' => $suffix,
	  					'dob' => $dob,
	  					'gender' => $gender,
	  					'contactno' => $contactno,
	  					'religionid' => $religion,
	  					'mtid' => $mt,
	  					'egid' => $eg,
	  					'street' => $street,
	  					'barangay' => $barangay,
	  					'city' => $city,
	  					'province' => $province,
	  					'fathername' => $fathername,
	  					'foccupation' => $fatheroccupation,
	  					'fcontactno' => $fathercontactno,
	  					'isfathernum' => $fatherdefault,
	  					'mothername' => $mothername,
	  					'moccupation' => $motheroccupation,
	  					'mcontactno' => $mothercontactno,
	  					'ismothernum' => $motherdefault,
	  					'guardianname' => $guardianname,
	  					'guardianrelation' => $guardianrelation,
	  					'gcontactno' => $guardiancontactno,
	  					'isguardannum' => $guardiandefault,
	  					'bloodtype' => $bt,
	  					'allergy' => $allergy,
	  					'others' => $medoth,
	  					'rfid' => $rfid,
	  					'mol' => $mol,
	  					'nationality' => $nationality,
	  					'lastschoolatt' => $lastschool,
	  					'lastschoolsy' => $lastsy,
	  					'deleted' => 0,
	  					'studtype' => $studtype,
	  					'pantawid' => $pantawid,
	  					'courseid' => $courseid,
	  					'district' => $district,
	  					'region' => $region,
	  					'updateddatetime' => RegistrarModel::getServerDateTime(),
	  					'updatedby' => auth()->user()->id
	  				]);

	  			$studid = $dataid;
	  			$return = 'done';
			}

		}

		endsave:

		$data = array(
			'studid' => $studid,
			'return' => $return,
		);

		echo json_encode($data);
	}
	
	public function sm_viewstud(Request $request)
	{
		$dataid = $request->get('dataid');

		$stud = db::table('studinfo')
			->where('id', $dataid)
			->first();

		$levelid = $stud->levelid;
		$syid = RegistrarModel::getSYID();
		$semid = RegistrarModel::getSemID();

		$dp = RegistrarModel::checkDPv2($stud->id, $levelid, $syid, $semid);

		$fatherdefault = 0;
		$motherdefault = 0;
		$guardiandefault = 0;
		$pantawid = 0;

		if($stud->isfathernum == 0)
		{
			$fatherdefault = 0;
		}
		else
		{
			$fatherdefault = 1;
		}

		if($stud->ismothernum == 0)
		{
			$motherdefault = 0;
		}
		else
		{
			$motherdefault = 1;
		}

		if($stud->isguardannum == 0)
		{
			$guardiandefault = 0;
		}
		else
		{
			$guardiandefault = 1;
		}

		if($stud->pantawid == 0)
		{
			$pantawid = 0;
		}
		else
		{
			$pantawid = 1;
		}
		
		$data = array(
			'dp' => $dp,
			'dataid' => $dataid,
			'lrn' => $stud->lrn,
			'levelid' => $stud->levelid,
			'grantee' => $stud->grantee,
			'courseid' => $stud->courseid,
			'mol' => $stud->mol,
			'studtype' => $stud->studtype,
			'firstname' => $stud->firstname,
			'lastname' => $stud->lastname,
			'middlename' => $stud->middlename,
			'suffix' => $stud->suffix,
			'dob' => $stud->dob,
			'gender' => $stud->gender,
			'contactno' => $stud->contactno,
			'religion' => $stud->religionid,
			'mt' => $stud->mtid,
			'eg' => $stud->egid,
			'nationality' => $stud->nationality,
			'street' => $stud->street,
			'barangay' => $stud->barangay,
			'city' => $stud->city,
			'province' => $stud->province,
			'fathername' => $stud->fathername,
			'fatheroccupation' => $stud->foccupation,
			'fathercontactno' => $stud->fcontactno,
			'fatherdefault' => $stud->isfathernum,
			'mothername' => $stud->mothername,
			'motheroccupation' => $stud->moccupation,
			'mothercontactno' => $stud->mcontactno,
			'motherdefault' => $stud->ismothernum,
			'guardianname' => $stud->guardianname,
			'guardianrelation' => $stud->guardianrelation,
			'guardiancontactno' => $stud->gcontactno,
			'guardiandefault' => $stud->isguardannum,
			'bt' => $stud->bloodtype,
			'allergy' => $stud->allergy,
			'medoth' => $stud->others,
			'lastschool' => $stud->lastschoolatt,
			'lastsy' => $stud->lastschoolsy,
			'rfid' => $stud->rfid,
			'pantawid' => $pantawid,
			'fatherdefault' => $fatherdefault,
			'motherdefault' => $motherdefault,
			'guardiandefault' => $guardiandefault,
			'district' => $stud->district,
			'region' => $stud->region
		);

		echo json_encode($data);
	}

	public function sm_loadenrollmentinfo(Request $request)
	{
		$dataid = $request->get('dataid');
		$syid = $request->get('syid');
		$semid = $request->get('semid');

		$stud = db::table('studinfo')
			->where('id', $dataid)
			->first();

		$levelid = $stud->levelid;
		$name = $stud->lastname . ', ' . $stud->firstname . ' ' . $stud->middlename . ' ' . $stud->suffix;
		$sid = $stud->sid;
		$lrn = $stud->lrn;

		$enrolled = '';
		$acad = '';
		$studstatus = 0;
		$level = '';
		$sectionlist = '';
		$courselist = '';
		$strandlist = '';
		$strandid = 0;
		$sectionid = 0;
		$courseid = 0;
		$dateenrolled = '';
		$enrollid = 0;

		if($levelid == 14 || $levelid == 15)
		{
			$sections = db::table('sections')
				->select('id', 'sectionname')
				->where('levelid', $levelid)
				->where('deleted', 0)
				->where('sectactive', 1)
				->get();

			foreach($sections as $section)
			{
				$studcount = db::table('sh_enrolledstud')
					->where('levelid', $levelid)
					->where('sectionid', $section->id)
					->where('deleted', 0)
					->where('syid', $syid)
					->count();

				$sectionlist .='
					<option value="'.$section->id.'">'.$section->sectionname.' - '.$studcount.'</option>
				';
			}

			$enrolled = 'sh_enrolledstud';

			if(db::table('schoolinfo')->first()->shssetup == 1)
			{
				$acad = 'sh_nosem';
			}
			else
			{
				$acad = 'sh';
			}

			// $strands = db::table('sh_strand')
			// 	->where('deleted', 0)
			// 	->where('active', 1)
			// 	->get();



			$enrollinfo = db::table('sh_enrolledstud')
				->where('deleted', 0)
				->where('studid', $dataid)
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

			if($enrollinfo)
			{
				$enrollid = $enrollinfo->id;
				$studstatus = $enrollinfo->studstatus;
				$sectionid = $enrollinfo->sectionid;
				$strandid = $enrollinfo->strandid;

				if($enrollinfo->studstatus == 1)
				{
					$dateenrolled = date_format(date_create($enrollinfo->dateenrolled), 'Y-m-d');
				}
				else
				{
					$dateenrolled = date_format(date_create($enrollinfo->studstatdate), 'Y-m-d');	
				}

				if($levelid == 14 || $levelid == 15)
				{
					$strandid = $enrollinfo->strandid;
				}
			}
			else
			{
				$studstatus = 0;
				$dateenrolled = '-';
				$strandid = $stud->strandid;

				if($stud->sectionid == null)
				{
					$sectionid = 0;	
				}
				else
				{
					$sectionid = $stud->sectionid;
				}

				
			}

			$sectionindex0 = 0;

			if($sectionid == 0)
			{
				if(count($sections) > 0)
				{
					$sectionindex0 = $sections[0]->id;
				}
			}

			$strands = db::table('sh_sectionblockassignment')
				->select('blockname', 'strandid')
				->join('sh_block', 'sh_sectionblockassignment.blockid', '=', 'sh_block.id')
				->where(function($q) use($sectionindex0, $sectionid){
					if($sectionid == 0)
					{
						$q->where('sectionid', $sectionindex0);
					}
					else
					{
						$q->where('sectionid', $sectionid);
					}
				})
				->where('sh_sectionblockassignment.deleted', 0)
				->where('sh_sectionblockassignment.syid', $syid)
				->get();

			$strandlist .='
				<option value="0">STRAND</option>
			';

			foreach($strands as $strand)
			{
				$strandlist .='
					<option value="'.$strand->strandid.'">'.$strand->blockname.'</option>
				';
			}

			$dp = RegistrarModel::checkDPv2($dataid, $levelid, $syid, $semid);

			if($dp != 'paid')
			{
				if($stud->nodp == 1)
				{
					$dp = 'allownodp';
				}
				else
				{
					$gradelevel = db::table('gradelevel')
						->where('id', $levelid)
						->first();

					if($gradelevel->nodp == 1)
					{
						$dp = 'allownodp';		
					}
				}
			}
		}
		elseif($levelid >= 17 && $levelid <= 20)
		{
			$enrolled = 'college_enrolledstud';
			$acad = 'college';
			$level = 'yearLevel';

			$sectionid = $stud->sectionid;

			$section = db::table('college_sections')
				->select('id', 'sectiondesc')
				->where('id', $sectionid)
				->first();

			if($section)
			{
				$studcount = db::table('college_enrolledstud')
					->where('yearLevel', $levelid)
					->where('sectionid', $section->id)
					->where('deleted', 0)
					->where('syid', $syid)
					->where('semid', $semid)
					->count();	

				$sectionlist .='
					<option value="'.$section->id.'">'.$section->sectiondesc.' - '.$studcount.'</option>
				';
			}

			$courseid = $stud->courseid;

			$course = db::table('college_courses')
				->where('id', $courseid)
				->first();

			if($course)
			{
				$courselist .='
					<option value="'.$course->id.'">'.$course->courseabrv.'</option>
				';
			}

			$enrollinfo = db::table('college_enrolledstud')
				->where('deleted', 0)
				->where('studid', $dataid)
				->where('syid', $syid)
				->where('semid', $semid)
				->first();

			if($enrollinfo)
			{
				$enrollid = $enrollinfo->id;
				$studstatus = $enrollinfo->studstatus;

				if($enrollinfo->studstatus == 1)
				{
					$dateenrolled = date_format(date_create($enrollinfo->date_enrolled), 'Y-m-d');
				}
				else
				{
					$dateenrolled = date_format(date_create($enrollinfo->studstatdate), 'Y-m-d');	
				}
			}
			else
			{
				$studstatus = 0;
				$dateenrolled = '-';
			}

		}
		else
		{
			$enrolled = 'enrolledstud';
			$acad = 'basic';
			$level = 'levelid';

			$sections = db::table('sections')
				->select('id', 'sectionname')
				->where('levelid', $levelid)
				->where('deleted', 0)
				->where('sectactive', 1)
				->get();

			foreach($sections as $section)
			{
				$studcount = db::table($enrolled)
					->where('levelid', $levelid)
					->where('sectionid', $section->id)
					->where('deleted', 0)
					->where('syid', $syid)
					->count();

				$sectionlist .='
					<option value="'.$section->id.'">'.$section->sectionname.' - '.$studcount.'</option>
				';
			}

			$enrollinfo = db::table($enrolled)
				->where('deleted', 0)
				->where('studid', $dataid)
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

			if($enrollinfo)
			{
				$enrollid = $enrollinfo->id;
				$studstatus = $enrollinfo->studstatus;
				$sectionid = $enrollinfo->sectionid;

				if($enrollinfo->studstatus == 1)
				{
					$dateenrolled = date_format(date_create($enrollinfo->dateenrolled), 'Y-m-d');
				}
				else
				{
					$dateenrolled = date_format(date_create($enrollinfo->studstatdate), 'Y-m-d');	
				}

				if($levelid == 14 || $levelid == 15)
				{
					$strandid = $enrollinfo->strandid;
				}
			}
			else
			{
				$studstatus = 0;
				$dateenrolled = '-';
			}
		}

		// return $sectionlist;
		

		$dp = RegistrarModel::checkDPv2($dataid, $levelid, $syid, $semid);

		if($dp != 'paid')
		{
			if($stud->nodp == 1)
			{
				$dp = 'allownodp';
			}
			else
			{
				$gradelevel = db::table('gradelevel')
					->where('id', $levelid)
					->first();

				if($gradelevel->nodp == 1)
				{
					$dp = 'allownodp';		
				}
			}
		}

		$data = array(
			'levelid' => $levelid,
			'name' => $name,
			'sid' => $sid,
			'studid' => $stud->id,
			'lrn' => $lrn,
			'acad' => $acad,
			'studstatus' => $studstatus,
			'grantee' => $stud->grantee,
			'mol' => $stud->mol,
			'studtype' => $stud->studtype,
			'sectionlist' => $sectionlist,
			'strandlist' => $strandlist,
			'courselist' => $courselist,
			'sectionid' => $sectionid,
			'strandid' => $strandid,
			'courseid' => $courseid,
			'dp' => $dp,
			'dateenrolled' => $dateenrolled,
			'enrollid' => $enrollid
		);

		echo json_encode($data);
	}

	public function sm_enrollstudent(Request $request)
	{
		$studid = $request->get('studid');
        $levelid = $request->get('levelid');
        $grantee = $request->get('grantee');
        $mol = $request->get('mol');
        $studtype = $request->get('studtype');
        $sectionid = $request->get('sectionid');
        $strand = $request->get('strand');
        $syid = $request->get('syid');
        $semid = $request->get('semid');

        $level = '';
        $enrollment = '';
        $teacherid = 0;
        $feesid = null;
        $return = '';
        $sectionname = '';

        $stud = db::table('studinfo')
        	->where('id', $studid)
        	->select('feesid', 'courseid')
        	->first();

        $courseid = $stud->courseid;

        if($levelid == 14 || $levelid == 15)
        {
        	$section = db::table('sections')
        		->where('id', $sectionid)
        		->first();

        	if($section)
        	{
        		$teacherid = $section->teacherid;
        		$sectionname = $section->sectionname;
        	}
        		
        	$checkEnroll = db::table('sh_enrolledstud')
	        	->where('studid', $studid)
	        	->where('syid', $syid)
	        	->where(function($q) use($semid, $levelid){
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
	        	->first();

	        if(!$checkEnroll)
	        {
	        	db::table('sh_enrolledstud')
	        		->insert([
	        			'studid' => $studid,
	        			'levelid' => $levelid,
	        			'sectionid' => $sectionid,
	        			'strandid' => $strand,
	        			'syid' => $syid,
	        			'semid' => $semid,
	        			'teacherid' => $teacherid,
	        			'dateenrolled' => RegistrarModel::getServerDateTime(),
	        			'studstatus' => 1,
	        			'createddatetime' => RegistrarModel::getServerDateTime()
 	        		]);

 	        	if($stud->feesid == null || $stud->feesid == '')
 	        	{
 	        		$fees = db::table('tuitionheader')
 	        			->where('deleted', 0)
 	        			->where('levelid', $levelid)
 	        			->where('syid', $syid)
 	        			->where('grantee', $grantee)
 	        			->where(function($q) use($semid, $levelid){
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

			        if(!$fees)
			        {
			        	$fees = db::table('tuitionheader')
	 	        			->where('deleted', 0)
	 	        			->where('levelid', $levelid)
	 	        			->where('syid', $syid)
	 	        			->where(function($q) use($semid, $levelid){
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

				        if($fees)
				        {
				        	$feesid = $fees->id;
				        }
				        else
				        {
				        	$feesid = 0;
				        }
			        }
			        else
			        {
			        	$feesid = $fees->id;
			        }
 	        	}
 	        	else
 	        	{
 	        		$feesid = $stud->feesid;
 	        	}

 	        	// return $feessid;

 	        	db::table('studinfo')
 	        		->where('id', $studid)
 	        		->update([
 	        			'levelid' => $levelid,
 	        			'grantee' => $grantee,
 	        			'mol' => $mol,
 	        			'studtype' => $studtype,
 	        			'studstatus' => 1,
 	        			'feesid' => $feesid,
 	        			'sectionname' => $sectionname
 	        			// 'sectionid' => $sectionid,
 	        			// 'strandid' => $strandid
 	        		]);

 	        	// $request->request->add(['feesid' => $feesid]);

 	        	// app('App\Http\Controllers\FinanceControllers\UtilityController')->resetpayment_v3($request);
 	        	// UtilityController::resetpayment_v3($request);

 	        	$return = 'done';
	        }
	        else
	        {
	        	$return = 'exist';
	        }

	        $data = array(
	        	'return'=> $return,
	        	'feesid' => $feesid
	        );

	        echo json_encode($data);	
        }
        elseif($levelid >= 17 && $levelid <= 20)
        {
        	$checkEnroll = db::table('college_enrolledstud')
        		->where('studid', $studid)
        		->where('syid', $syid)
        		->where('semid', $semid)
        		->where('deleted', 0)
        		->first();

        	if(!$checkEnroll)
        	{
        		$section = db::table('college_sections')
        			->where('id', $sectionid)
        			->first();

        		if($section)
        		{
        			$sectionname = $section->sectionDesc;
        		}

        		db::table('college_enrolledstud')
	        		->insert([
	        			'studid' => $studid,
	        			'yearLevel' => $levelid,
	        			'sectionid' => $sectionid,
	        			'courseid' => $courseid,
	        			'syid' => $syid,
	        			'semid' => $semid,
	        			'deleted' => 0,
	        			'date_enrolled' => RegistrarModel::getServerDateTime(),
	        			'studstatus' => 1,
	        			'createddatetime' => RegistrarModel::getServerDateTime(),
	        			'createdby' => auth()->user()->id
 	        		]);

 	        	if($stud->feesid == null || $stud->feesid == '')
 	        	{
 	        		$fees = db::table('tuitionheader')
 	        			->where('deleted', 0)
 	        			->where('levelid', $levelid)
 	        			->where('syid', $syid)
 	        			->where('semid', $semid)
			        	->where('courseid', $courseid)
			        	->first();

			        if(!$fees)
			        {
			        	$fees = db::table('tuitionheader')
	 	        			->where('deleted', 0)
	 	        			->where('levelid', $levelid)
	 	        			->where('syid', $syid)
	 	        			->where(function($q) use($semid, $levelid){
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

				        if($fees)
				        {
				        	$feesid = $fees->id;
				        }
				        else
				        {
				        	$feesid = 0;
				        }
			        }
			        else
			        {
			        	$feesid = $fees->id;
			        }	
 	        	}
 	        	else
 	        	{
 	        		$feesid = $stud->feesid;
 	        	}

 	        	db::table('studinfo')
 	        		->where('id', $studid)
 	        		->update([
 	        			'levelid' => $levelid,
 	        			'mol' => $mol,
 	        			'studtype' => $studtype,
 	        			'studstatus' => 1,
 	        			'feesid' => $feesid,
 	        			'sectionname' => $sectionname
 	        		]);

 	        	$return = 'done';
        	}
        	else
        	{
        		$return = 'exist';
        	}

        	$data = array(
	        	'return'=> $return,
	        	'feesid' => $feesid
	        );

	        echo json_encode($data);
        }
        else
        {
        	$section = db::table('sections')
        		->where('id', $sectionid)
        		->first();

        	if($section)
        	{
        		$teacherid = $section->teacherid;
        		$sectionname = $section->sectionname;
        	}
        		
        	$checkEnroll = db::table('enrolledstud')
	        	->where('studid', $studid)
	        	->where('syid', $syid)
	        	->where(function($q) use($semid, $levelid){
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
	        	->first();

	        if(!$checkEnroll)
	        {
	        	db::table('enrolledstud')
	        		->insert([
	        			'studid' => $studid,
	        			'levelid' => $levelid,
	        			'sectionid' => $sectionid,
	        			'syid' => $syid,
	        			'teacherid' => $teacherid,
	        			'dateenrolled' => RegistrarModel::getServerDateTime(),
	        			'studstatus' => 1,
	        			'createddatetime' => RegistrarModel::getServerDateTime(),
	        			'createdby' => auth()->user()->id
 	        		]);

 	        	if($stud->feesid == null || $stud->feesid == '')
 	        	{
 	        		$fees = db::table('tuitionheader')
 	        			->where('deleted', 0)
 	        			->where('levelid', $levelid)
 	        			->where('syid', $syid)
 	        			->where('grantee', $grantee)
 	        			->where(function($q) use($semid, $levelid){
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

			        if(!$fees)
			        {
			        	$fees = db::table('tuitionheader')
	 	        			->where('deleted', 0)
	 	        			->where('levelid', $levelid)
	 	        			->where('syid', $syid)
	 	        			->where(function($q) use($semid, $levelid){
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

				        if($fees)
				        {
				        	$feesid = $fees->id;
				        }
				        else
				        {
				        	$feesid = 0;
				        }
			        }
			        else
			        {
			        	$feesid = $fees->id;
			        }
 	        	}
 	        	else
 	        	{
 	        		$feesid = $stud->feesid;
 	        	}

 	        	// return $feessid;

 	        	db::table('studinfo')
 	        		->where('id', $studid)
 	        		->update([
 	        			'levelid' => $levelid,
 	        			'grantee' => $grantee,
 	        			'mol' => $mol,
 	        			'studtype' => $studtype,
 	        			'studstatus' => 1,
 	        			'feesid' => $feesid,
 	        			'sectionname' => $sectionname
 	        		]);

 	        	// $request->request->add(['feesid' => $feesid]);

 	        	// app('App\Http\Controllers\FinanceControllers\UtilityController')->resetpayment_v3($request);
 	        	// UtilityController::resetpayment_v3($request);

 	        	$return = 'done';
	        }
	        else
	        {
	        	$return = 'exist';
	        }



	        $data = array(
	        	'return'=> $return,
	        	'feesid' => $feesid
	        );

	        echo json_encode($data);
        }   

        if($return == 'done')
        {
        	RegistrarModel::enrollmentsmsnotification($studid, $syid, $semid);
        }
	}
	
	public function sm_update_studstatus(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');
			$enrollid = $request->get('enrollid');
			$studstatus = $request->get('studstatus');
			$levelid = $request->get('levelid');
			$estud = '';
			$syid = 0;
			$semid = 0;

			if($levelid == 14 || $levelid == 15)
			{
				$estud = 'sh_enrolledstud';
			}
			elseif($levelid >= 17 && $levelid <= 20)
			{
				$estud = 'college_enrolledstud';
			}
			else
			{
				$estud = 'enrolledstud';
			}

			if($studstatus == 0)
			{
				$enrolled = db::table($estud)
					->where('id', $enrollid)
					->first();

				$syid = $enrolled->syid;
				if($estud != 'enrolledstud')
				{
					$semid = $enrolled->semid;
				}

				db::table('studpayscheddetail')
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
					->where('deleted', 0)
					->update([
						'deleted' => 1,
						'deleteddatetime' => RegistrarModel::getServerDateTime()
					]);

				db::table('studledger')
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
					->where('enrollid', '!=', 0)
					->where('deleted', 0)
					->update([
						'deleted' => 1,
						'deleteddatetime' => RegistrarModel::getServerDateTime()
					]);

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
					->where('deleted', 0)
					->update([
						'deleted' => 1,
						'deleteddatetime' => RegistrarModel::getServerDateTime()
					]);

				db::table('studinfo')
					->where('id', $studid)
					->update([
						'studstatus' => 0,
						'updateddatetime' => RegistrarModel::getServerDateTime()
					]);

				db::table($estud)
					->where('id', $enrollid)
					->update([
						'deleted' => 1,
						'updateddatetime' => RegistrarModel::getServerDateTime()
					]);

				db::table('enrollment_unenrolllogs')
					->insert([
						'studid' => $studid,
						'enrollmentid' => $enrollid,
						'syid' => $syid,
						'semid' => $semid,
						'updatedby' => auth()->user()->id,
						'updateddatetime' => RegistrarModel::getServerDateTime()
					]);

			}
			elseif($studstatus != 1)
			{
				db::table($estud)
					->where('id', $enrollid)
					->where('studid', $studid)
					->update([
						'studstatus' => $studstatus,
						'studstatdate' => RegistrarModel::getServerDateTime(),
						'updateddatetime' => RegistrarModel::getServerDateTime(),
						'updatedby' => auth()->user()->id
					]);

				db::table('studinfo')
				->where('id', $studid)
				->update([
					'studstatus' => $studstatus,
					'studstatdate' => RegistrarModel::getServerDateTime(),
					'updateddatetime' => RegistrarModel::getServerDateTime(),
					'updatedby' => auth()->user()->id
				]);
			}
			else
			{
				db::table($estud)
					->where('id', $enrollid)
					->where('studid', $studid)
					->update([
						'studstatus' => $studstatus,
						'updateddatetime' => RegistrarModel::getServerDateTime(),
						'updatedby' => auth()->user()->id
					]);

				db::table('studinfo')
				->where('id', $studid)
				->update([
					'studstatus' => $studstatus,
					'updateddatetime' => RegistrarModel::getServerDateTime(),
					'updatedby' => auth()->user()->id
				]);
			}

			return 'done';
		}
	}

	public function sm_update_studstatdate(Request $request)
	{
		$levelid = $request->get('levelid');
		$enrollid = $request->get('enrollid');
		$studid = $request->get('studid');
		$studstatdate = $request->get('studstatdate');

		$estud = '';
		$date = '';

		if($levelid == 14 || $levelid == 15)
		{
			$estud = 'sh_enrolledstud';
			$date = 'dateenrolled';
		}
		elseif($levelid >= 17 && $levelid <= 20)
		{
			$estud = 'college_enrolledstud';
			$date = 'date_enrolled';
		}
		else
		{
			$estud = 'enrolledstud';
			$date = 'dateenrolled';
		}

		$info = db::table($estud)
			->where('id', $enrollid)
			->first();

		if($info)
		{
			if($info->studstatus != 1)
			{
				db::table($estud)
					->where('id', $enrollid)
					->where('studid', $studid)
					->update([
						'studstatdate' => $studstatdate,
						'updatedby' => auth()->user()->id,
						'updateddatetime' => RegistrarModel::getServerDateTime()
					]);

				db::table('studinfo')
					->where('id', $studid)
					->update([
						'studstatdate' => $studstatdate,
						'updatedby' => auth()->user()->id,
						'updateddatetime' => RegistrarModel::getServerDateTime()
					]);

			}
			else
			{
				db::table($estud)
					->where('id', $enrollid)
					->where('studid', $studid)
					->update([
						'studstatdate' => null,
						$date => $studstatdate,
						'updatedby' => auth()->user()->id,
						'updateddatetime' => RegistrarModel::getServerDateTime()
					]);

				db::table('studinfo')
					->where('id', $studid)
					->update([
						'studstatdate' => null,
						'updatedby' => auth()->user()->id,
						'updateddatetime' => RegistrarModel::getServerDateTime()
					]);
			}
		}

		return 'done';
	}

	public function sm_update_studtype(Request $request)
	{
		$levelid = $request->get('levelid');
		$enrollid = $request->get('enrollid');
		$studid = $request->get('studid');
		$studtype = $request->get('studtype');

		db::table('studinfo')
			->where('id', $studid)
			->update([
				'studtype' => $studtype,
				'updatedby' => auth()->user()->id,
				'updateddatetime' => RegistrarModel::getServerDateTime()
			]);

		return 'done';
	}

	public function sm_update_mol(Request $request)
	{
		$levelid = $request->get('levelid');
		$enrollid = $request->get('enrollid');
		$studid = $request->get('studid');
		$mol = $request->get('mol');

		db::table('studinfo')
			->where('id', $studid)
			->update([
				'mol' => $mol,
				'updatedby' => auth()->user()->id,
				'updateddatetime' => RegistrarModel::getServerDateTime()
			]);

		return 'done';
	}

	public function sm_update_grantee(Request $request)
	{
		$levelid = $request->get('levelid');
		$enrollid = $request->get('enrollid');
		$studid = $request->get('studid');
		$grantee = $request->get('grantee');
		$syid = $request->get('syid');
		$semid = $request->get('semid');

		$estud = '';

		if($levelid == 14 || $levelid == 15)
		{
			$estud = 'sh_enrolledstud';
		}
		else
		{
			$estud = 'enrolledstud';
		}

		db::table($estud)
			->where('id', $enrollid)
			->where('studid', $studid)
			->update([
				'grantee' => $grantee,
				'updatedby' => auth()->user()->id,
				'updateddatetime' => RegistrarModel::getServerDateTime()
			]);


		$fees = db::table('tuitionheader')
			->where('deleted', 0)
			->where('levelid', $levelid)
			->where('syid', $syid)
			->where('grantee', $grantee)
			->where(function($q) use($semid, $levelid){
	    		if($levelid == 14 || $levelid == 15)
	    		{
	    			if(db::table('schoolinfo')->first()->shssetup == 0)
	    			{
	    				$q->where('semid', $semid);
	    			}
	    		}
	    	})
	    	->first();

	    if(!$fees)
	    {
	    	$feesid = 0;
	    }
	    else
	    {
	    	$feesid = $fees->id;
	    }

		db::table('studinfo')
			->where('id', $studid)
			->update([
				'grantee' => $grantee,
				'updatedby' => auth()->user()->id,
				'updateddatetime' => RegistrarModel::getServerDateTime()
			]);

		return $feesid;
	}

	public function sm_update_level(Request $request)
	{
		$levelid = $request->get('levelid');
		$enrollid = $request->get('enrollid');
		$studid = $request->get('studid');
		$syid = $request->get('syid');
		$semid = $request->get('semid');
		$dateenrolled = $request->get('dateenrolled');
		$curlevelid = $request->get('curlevelid');
		$sectionid = null;
		$sectionname = null;
		$return = '';
		$acad = '';
		$feesid = null;

		$estud = '';
		$level = '';
		$curEstud = '';

		if($curlevelid == 14 || $curlevelid == 15)
		{
			$curEstud = 'sh_enrolledstud';
			$level = 'levelid';
		}
		elseif($curlevelid >= 17 && $curlevelid <= 20)
		{
			$curEstud = 'college_enrolledstud';
			$level = 'yearLevel';
		}
		else
		{
			$curEstud = 'enrolledstud';
			$level = 'levelid';
		}

		if($levelid == 14 || $levelid == 15)
		{
			$estud = 'sh_enrolledstud';
		}
		elseif($levelid >= 17 && $levelid <= 20)
		{
			$estud = 'college_enrolledstud';
		}
		else
		{
			$estud = 'enrolledstud';
		}

		$studinfo = db::table('studinfo')
			->where('id', $studid)
			->first();

		$enrollinfo = db::table($curEstud)
			->select('id', $level . ' as level')
			->where('id', $enrollid)
			->where('studid', $studid)
			->first();

		if($enrollinfo)
		{
			if($levelid < 17)
			{
				db::table($curEstud)
					->where('id', $enrollinfo->id)
					->where('studid', $studid)
					->update([
						'deleted' => 1,
						'deletedby' => auth()->user()->id,
						'deleteddatetime' => RegistrarModel::getServerDateTime()
					]);

				$checkestud = db::table($estud)
					->where($level, $levelid)
					->where('studid', $studid)
					->where('syid', $syid)
					->where('deleted', 0)
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
					->count();

				if($checkestud == 0)
				{
					if($levelid == 14 && $levelid == 15)
					{
						if(db::table('schoolinfo')->first()->shssetup == 0)
						{
							$acad = 'sh';
						}
						else
						{
							$acad = 'sh_nosem';
						}

						$sections = db::table('sections')
							->where('levelid', $levelid)
							->where('deleted', 0)
							->where('sectactive', 1)
							->first();

						if($sections)
						{
							$sectionid = $sections->id;
							$sectionname = $sections->sectionname;
						}

						db::table($estud)
							->insert([
								'studid' => $studid,
								'syid' => $syid,
								'semid' => $semid,
								'levelid' => $levelid,
								'sectionid' => $sectionid,
								'strandid' => $studinfo->strandid,
								'dateenrolled' => $dateenrolled,
								'studstatus' => $studinfo->studstatus,
								'grantee' => $studinfo->grantee,
								'deleted' => 0,
								'createdby' => auth()->user()->id,
								'createddatetime' => RegistrarModel::getServerDateTime()
							]);
					}
					else
					{
						$acad = 'basic';

						$sections = db::table('sections')
							->where('levelid', $levelid)
							->where('deleted', 0)
							->where('sectactive', 1)
							->first();

						if($sections)
						{
							$sectionid = $sections->id;
							$sectionname = $sections->sectionname;
						}

						db::table($estud)
							->insert([
								'studid' => $studid,
								'syid' => $syid,
								'levelid' => $levelid,
								'sectionid' => $sectionid,
								'dateenrolled' => $dateenrolled,
								'studstatus' => $studinfo->studstatus,
								'grantee' => $studinfo->grantee,
								'deleted' => 0,
								'createdby' => auth()->user()->id,
								'createddatetime' => RegistrarModel::getServerDateTime()
							]);
					}

					db::table('studinfo')
						->where('id', $studid)
						->update([
							'levelid' => $levelid,
							'sectionid' => $sectionid,
							'sectionname' => $sectionname,
							'updatedby' => auth()->user()->id,
							'updateddatetime' => RegistrarModel::getServerDateTime()
						]);	


 	        	
 	        		$fees = db::table('tuitionheader')
 	        			->where('deleted', 0)
 	        			->where('levelid', $levelid)
 	        			->where('syid', $syid)
 	        			->where('grantee', $studinfo->grantee)
 	        			->where(function($q) use($semid, $levelid){
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

			        if(!$fees)
			        {
			        	$fees = db::table('tuitionheader')
	 	        			->where('deleted', 0)
	 	        			->where('levelid', $levelid)
	 	        			->where('syid', $syid)
	 	        			->where(function($q) use($semid, $levelid){
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

				        if($fees)
				        {
				        	$feesid = $fees->id;
				        }
				        else
				        {
				        	$feesid = 0;
				        }
			        }
			        else
			        {
			        	$feesid = $fees->id;
			        }

					$return = 'done';
				}
				else
				{
					$return = 'exist';
				}
			}
			else
			{
				$return = 'not allowed';
			}
		}
		else
		{
			$return = 'not enrolled';
		}

		$data = array(
			'return' => $return,
			'acad' => $acad,
			'feesid' => $feesid
		);

		echo json_encode($data);
	}

	public function sm_update_strand(Request $request)
	{
		$levelid = $request->get('levelid');
		$enrollid = $request->get('enrollid');
		$studid = $request->get('studid');
		$strandid = $request->get('strandid');

		if($levelid == 14 || $levelid == 15)
		{
			db::table('sh_enrolledstud')
				->where('id', $enrollid)
				->where('studid', $studid)
				->update([
					'strandid' => $strandid,
					'updateddatetime' => RegistrarModel::getServerDateTime(),
					'updatedby' => auth()->user()->id
				]);

			db::table('studinfo')
				->where('id', $studid)
				->update([
					'strandid' => $strandid,
					'updateddatetime' => RegistrarModel::getServerDateTime(),
					'updatedby' => auth()->user()->id
				]);

			return 'done';
		}
	}

	public function sm_update_section(Request $request)
	{
		$levelid = $request->get('levelid');
		$enrollid = $request->get('enrollid');
		$studid = $request->get('studid');
		$sectionid = $request->get('sectionid');

		$estud = '';

		if($levelid == 14 || $levelid == 15)
		{
			$estud = 'sh_enrolledstud';
		}
		elseif($levelid >= 17 && $levelid <= 20)
		{
			$estud = 'college_enrolledstud';
		}
		else
		{
			$estud = 'enrolledstud';
		}

		$sectionname = '';

		if($levelid < 17)
		{
			$section = db::table('sections')
				->where('id', $sectionid)
				->first();

			$sectionname = $section->sectionname;
		}
		else
		{
			$section = db::table('college_sections')
				->where('id', $sectionid)
				->first();

			$sectionname = $section->sectionDesc;	
		}

		if($enrollid > 0)
		{
			db::table($estud)
				->where('id', $enrollid)
				->where('studid', $studid)
				->update([
					'sectionid' => $sectionid,
					'updateddatetime' => RegistrarModel::getServerDateTime(),
					'updatedby' => auth()->user()->id
				]);
		}

		db::table('studinfo')
			->where('id', $studid)
			->update([
				'sectionid' => $sectionid,
				'sectionname' => $sectionname
			]);

		return 'done';
	}
}
