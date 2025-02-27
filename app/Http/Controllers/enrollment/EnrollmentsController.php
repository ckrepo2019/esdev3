<?php

namespace App\Http\Controllers\enrollment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\RegistrarModel;
use App\SyncModel;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Illuminate\Support\Facades\Hash;

class EnrollmentsController extends Controller
{
	  public function registrarIndex()
	  {
	  	return view('enrollment/index');	
	  }

	  public function studentinfo()
	  {

	  	$in = RegistrarModel::activeacadprog();
	  	// return $in;


	  	$level = db::table('gradelevel')
	  		->whereIn('acadprogid', $in)
  			->where('deleted', 0)
  			->orderBy('sortid')
  			->get();
  		// return $level;

  		$data = array(
  			'level' => $level,
  			'acadprog' => $in
  		);
	  	
	  	return view('enrollment/studinfo')
	  		->with($data);
	  		
	  }

	  public function studentsearch(Request $request)
	  {
	  	if($request->ajax())
	  	{
	  		$glevel = $request->get('glevel');
	  		$query = $request->get('query');
	  		$skip = $request->get('skip');
	  		$take = $request->get('take');
	  		$curpage = $request->get('curpage');

	  		$skip = ((int)$skip - 1) * $take;

	  		$acadprog = RegistrarModel::activeacadprog();
	  		// return $acadprog;

	  		
    		$student = db::table('studinfo')
				->select('studinfo.*', 'gradelevel.levelname', 'sections.sectionname as secname', 'grantee.description as grantee', 'studentstatus.description')
				->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
				->leftJoin('sections', 'studinfo.sectionid', '=', 'sections.id')
				->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
				->join('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
				->where(function($q) use($glevel, $acadprog){
					if($glevel > 0)
					{
						$q->where('studinfo.levelid', $glevel);
					}
					else
					{
						$q->whereIn('gradelevel.acadprogid', $acadprog);
					}
				})
				->where('lastname', 'like', '%'.$query.'%')
				->where('studinfo.deleted', 0)
				->orWhere('firstname', 'like', '%'.$query.'%')
				->where('studinfo.levelid', $glevel)
				->where('studinfo.deleted', 0)
				->orderBy('lastname','ASC')
				->orderBy('firstname','ASC')
				->skip($skip)
				->take($take)
				->get();

    				// return $student;

    		$recCount = db::table('studinfo')
    				->select('studinfo.*', 'gradelevel.levelname', 'sections.sectionname as secname')
    				->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
    				->leftJoin('sections', 'studinfo.sectionid', '=', 'sections.id')
    				->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
    				->join('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
    				->where(function($q) use($glevel, $acadprog){
    					if($glevel > 0)
    					{
    						$q->where('studinfo.levelid', $glevel);
    					}
    					else
    					{
    						$q->whereIn('gradelevel.acadprogid', $acadprog);
    					}
    				})
    				->where('lastname', 'like', '%'.$query.'%')
    				->where('studinfo.deleted', 0)
    				->orWhere('firstname', 'like', '%'.$query.'%')
    				->where('studinfo.levelid', $glevel)
    				->where('studinfo.deleted', 0)
    				->count();
	  		
	  		
	  		$output = '';
	  		$paginate = '';
	  		$paginate .= '
	  			<li class="paginate_button page-item previous" id="example2_previous">
						<a href="#" aria-controls="example2" data-page="0" tabindex="0" class="page-link">Previous</a>
					</li>
	  		';
	  		$count = count($student);

	  		foreach($student as $s)
	  		{
	  			if($s->studstatus == 1)
	  			{
	  				$status = '<span style="width:1px height:100%" class="bg-success">&nbsp;</span>';
	  			}
	  			elseif($s->studstatus == 2)
	  			{
	  				$status = '<span style="width:1px height:100%" class="bg-primary">&nbsp;</span>';	
	  			}
	  			elseif($s->studstatus == 3)
	  			{
	  				$status = '<span style="width:1px height:100%" class="bg-danger">&nbsp;</span>';
	  			}
	  			elseif($s->studstatus == 4)
	  			{
	  				$status = '<span style="width:1px height:100%" class="bg-warning">&nbsp;</span>';	
	  			}
	  			elseif($s->studstatus == 5)
	  			{
	  				$status = '<span style="width:1px height:100%" class="bg-secondary">&nbsp;</span>';	
	  			}
	  			elseif($s->studstatus == 6)
	  			{
	  				$status = '<span style="width:1px height:100%" class="bg-orange">&nbsp;</span>';	
	  			}
	  			else
	  			{
	  				$status = '';		
	  			}
	  			
	  			if($s->levelid == 14 || $s->levelid == 15)
	  			{
	  				$chkEnroll = db::table('sh_enrolledstud')
	  						->where('studid', $s->id)
	  						->where('syid', RegistrarModel::getSYID())
	  						->where('semid', RegistrarModel::getSemID())
	  						->where('studstatus', '>', '0')
	  						->where('deleted', 0)
	  						->get();
	  			}
	  			else
	  			{
	  				$chkEnroll = db::table('enrolledstud')
	  						->where('studid', $s->id)
	  						->where('syid', RegistrarModel::getSYID())
	  						->where('studstatus', '>', '0')
	  						->where('deleted', 0)
	  						->get();
	  			}

	  			// if($s->studstatus == 0)
	  			// return $chkEnroll;
	  			if(count($chkEnroll) == 0)
	  			{
		  			$output .= '

		  				<tr>
		  					<td> '.$status.' '.$s->sid.'</td>
		  					<td id="studname"><a href="studentinfo/edit/'.$s->id.'">'.strtoupper($s->lastname.' '.$s->firstname).'</a></td>
		  					<td>'.$s->gender.'</td>
		  					<td>'.$s->levelname.'</td>
		  					<td>'.$s->secname.'</td>
		  					<td>'.$s->grantee.'</td>
		  					<td>'.$s->description.'</td>
		  				</tr>

		  			';
		  		}
		  		else
		  		{
		  			$output .= '

		  				<tr>
		  					<td> '.$status.' '.$s->sid.'</td>
		  					<td id="studname"><a href="studentinfo/edit/'.$s->id.'">'.strtoupper($s->lastname.' '.$s->firstname).'</a></td>
		  					<td>'.$s->gender.'</td>
		  					<td>'.$s->levelname.'</td>
		  					<td>'.$s->secname.'</td>
		  					<td>'.$s->grantee.'</td>
		  					<td>'.$s->description.'</td>
		  				</tr>

		  			';	
		  		}
	  		}
	  		// return $output;
	  		$data = array(
	  			'output' => $output,
	  			
	  			'glevel' => $glevel,
	  			'recCount' => $recCount
	  		);

	  		echo json_encode($data);
	  	}
	  }

	  public function studentedit(Request $request, $id)
	  {
	  	$glevel = db::table('gradelevel')
	  			->where('deleted', 0)
	  			->orderBy('sortid', 'asc')
	  			->get();

	  	$religion = db::table('religion')
	  			->where('deleted', 0)
	  			->get();

	  	$mothertongue = db::table('mothertongue')
	  			->where('deleted', 0)
	  			->get();

	  	$ethnic = db::table('ethnic')
	  			->where('deleted', 0)
	  			->get();

	  	$stud = db::table('studinfo')
	  			->where('id', $id)
	  			->first();

	  	$grantee = db::table('grantee')
	  		->get();

	  	$nationality = db::table('nationality')
	  		->where('deleted', 0)
	  		->get();

	  	$modeoflearning  = db::table('modeoflearning')
	  		->where('deleted', 0)
	  		->get();

	  	// return $stud->grantee;
	  	
	  	$data = array(
	  		'stud' => $stud,
	  		'glevel' => $glevel,
	  		'mothertongue' => $mothertongue,
	  		'religion' => $religion,
	  		'ethnic' => $ethnic,
	  		'grantee' => $grantee,
	  		'modeoflearning' => $modeoflearning,
	  		'nationality' => $nationality
	  	);
	  	return view('/enrollment/studinfo_edit')->with($data);
	  }

	  public function studentupdate(Request $request)
	  {
	  	if($request->ajax())
	  	{
	  		$studid = $request->get('studid');
	  		$lrn = $request->get('lrn');
	  		$grantee = $request->get('grantee');
	  		$glevel = $request->get('glevel');
	  		$fname = $request->get('fname');
	  		$mname = $request->get('mname');
	  		$lname = $request->get('lname');
	  		$suffix = $request->get('suffix');
	  		$dob = $request->get('dob');
	  		$gender = $request->get('gender');
	  		$contactno = $request->get('contactno');
	  		$religion = $request->get('religion');
	  		$mt = $request->get('mt');
	  		$eg = $request->get('eg');
	  		$street = $request->get('street');
	  		$barangay = $request->get('barangay');
	  		$city = $request->get('city');
	  		$province = $request->get('province');
	  		$fathername = $request->get('fathername');
	  		$foccupation = $request->get('foccupation');
	  		$fcontactno = $request->get('fcontactno');
	  		$mothername = $request->get('mothername');
	  		$moccupation = $request->get('moccupation');
	  		$mcontactno = $request->get('mcontactno');
	  		$guardianname = $request->get('guardianname');
	  		$guardianrelation = $request->get('guardianrelation');
	  		$gcontactno = $request->get('gcontactno');
	  		$bloodtype = $request->get('bloodtype');
	  		$allergy = $request->get('allergy');
	  		$others = $request->get('others');
	  		$rfid = $request->get('rfid');
	  		$mol = $request->get('mol');
	  		$nationality = $request->get('nationality');
	  		$lastschool = $request->get('lastschool');

	  		$isfather = $request->get('isfather');
	  		$ismother = $request->get('ismother');
	  		$isguardian = $request->get('isguardian');
	  		$studtype = $request->get('studtype');

	  		$upd = DB::table('studinfo')
  				->where('id', $studid)
  				->update([
  					'lrn' => $lrn,
  					'grantee' => $grantee,
  					'levelid' => $glevel,
  					'firstname' => $fname,
  					'middlename' => $mname,
  					'lastname' => $lname,
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
  					'foccupation' => $foccupation,
  					'fcontactno' => $fcontactno,
  					'isfathernum' => $isfather,
  					'mothername' => $mothername,
  					'moccupation' => $moccupation,
  					'mcontactno' => $mcontactno,
  					'ismothernum' => $ismother,
  					'guardianname' => $guardianname,
  					'guardianrelation' => $guardianrelation,
  					'gcontactno' => $gcontactno,
  					'isguardannum' => $isguardian,
  					'bloodtype' => $bloodtype,
  					'allergy' => $allergy,
  					'others' => $others,
  					'rfid' => $rfid,
  					'mol' => $mol,
  					'nationality' => $nationality,
  					'lastschoolatt' => $lastschool,
  					'updateddatetime' => RegistrarModel::getServerDateTime(),
  					'updatedby' => auth()->user()->id,
  					'studtype' => $studtype
  				]);
	  	}
	 }
		public function studentprint(Request $request)
		{
		  
			date_default_timezone_set('Asia/Manila');

			$studentinfo = DB::table('studinfo')
							->leftJoin('gradelevel','studinfo.levelid','=','gradelevel.id')
							// ->leftJoin('sections','studinfo.sectionid','=','sections.id')
							->where('studinfo.id', $request->get('studid'))
							->where('gradelevel.deleted', '0')
							// ->where('sections.deleted', '0')
							->get();
			// return $studentinfo;
			$schoolinfo = DB::table('schoolinfo')
				->select(
					'schoolinfo.schoolid',
					'schoolinfo.schoolname',
					'schoolinfo.authorized',
					'refcitymun.citymunDesc as division',
					'schoolinfo.district',
					'schoolinfo.picurl',
					'schoolinfo.address',
					'refregion.regDesc as region'
				)
				->join('refregion','schoolinfo.region','=','refregion.regCode')
				->join('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
				->get();
							
			foreach($studentinfo as $studinfo){

				foreach($studinfo as $key => $value){

					if($key == 'dob'){

						$studinfo->dob = date('F d, Y', strtotime($value));

					}					

				}

			}
			$pdf = PDF::loadview('enrollment/pdf/studentinformation',compact('studentinfo','schoolinfo'))->setPaper('8.5x11','portrait'); 
			return $pdf->stream('Student Information - '.$studentinfo[0]->lastname.' - '.$studentinfo[0]->firstname.'.pdf');

		}

	  public function studentcreate(Request $request)
	  {
	  	$glevel = db::table('gradelevel')
	  			->where('deleted', 0)
	  			->orderBy('sortid', 'asc')
	  			->get();

	  	$religion = db::table('religion')
	  			->where('deleted', 0)
	  			->get();

	  	$mothertongue = db::table('mothertongue')
	  			->where('deleted', 0)
	  			->get();

	  	$ethnic = db::table('ethnic')
	  			->where('deleted', 0)
	  			->get();

	  	$mol = db::table('modeoflearning')
	  		->where('deleted', 0)
	  		->get();

	  	$nationality = db::table('nationality')
	  		->where('deleted', 0)
	  		->orderBy('nationality', 'ASC')
	  		->get();

	  	$data = array(
	  		'glevel' => $glevel,
	  		'religion' => $religion,
	  		'mothertongue' => $mothertongue,
	  		'ethnic' => $ethnic,
	  		'modeoflearning' => $mol,
	  		'nationality' => $nationality
	  	);
	  	return view('/enrollment/studinfo_create')->with($data);
	  }

	  public function studentinsert(Request $request)
	  {
	  	if($request->ajax())
	  	{
	  		$lrn = $request->get('lrn');
	  		$grantee = $request->get('grantee');
	  		$glevel = $request->get('glevel');
	  		$fname = $request->get('fname');
	  		$mname = $request->get('mname');
	  		$lname = $request->get('lname');
	  		$suffix = $request->get('suffix');
	  		$dob = $request->get('dob');
	  		$gender = $request->get('gender');
	  		$contactno = $request->get('contactno');
	  		$religion = $request->get('religion');
	  		$mt = $request->get('mt');
	  		$eg = $request->get('eg');
	  		$street = $request->get('street');
	  		$barangay = $request->get('barangay');
	  		$city = $request->get('city');
	  		$province = $request->get('province');
	  		$fathername = $request->get('fathername');
	  		$foccupation = $request->get('foccupation');
	  		$fcontactno = $request->get('fcontactno');
	  		$mothername = $request->get('mothername');
	  		$moccupation = $request->get('moccupation');
	  		$mcontactno = $request->get('mcontactno');
	  		$guardianname = $request->get('guardianname');
	  		$guardianrelation = $request->get('guardianrelation');
	  		$gcontactno = $request->get('gcontactno');
	  		$bloodtype = $request->get('bloodtype');
	  		$allergy = $request->get('allergy');
	  		$others = $request->get('others');
	  		$rfid = $request->get('rfid');
	  		$mol = $request->get('mol');
	  		$nationality = $request->get('nationality');
	  		$lastschool = $request->get('lastschool');

	  		$isfather = $request->get('isfather');
	  		$ismother = $request->get('ismother');
	  		$isguardian = $request->get('isguardian');
	  		$studtype = $request->get('studtype');


	  		$studid = DB::table('studinfo')
	  				->insertGetId([
	  					'lrn' => $lrn,
	  					'grantee' => $grantee,
	  					'levelid' => $glevel,
	  					'firstname' => $fname,
	  					'middlename' => $mname,
	  					'lastname' => $lname,
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
	  					'foccupation' => $foccupation,
	  					'fcontactno' => $fcontactno,
	  					'isfathernum' => $isfather,
	  					'mothername' => $mothername,
	  					'moccupation' => $moccupation,
	  					'mcontactno' => $mcontactno,
	  					'ismothernum' => $ismother,
	  					'guardianname' => $guardianname,
	  					'guardianrelation' => $guardianrelation,
	  					'gcontactno' => $gcontactno,
	  					'isguardannum' => $isguardian,
	  					'bloodtype' => $bloodtype,
	  					'allergy' => $allergy,
	  					'others' => $others,
	  					'rfid' => $rfid,
	  					'mol' => $mol,
	  					'nationality' => $nationality,
	  					'lastschoolatt' => $lastschool,
	  					'deleted' => 0,
	  					'studtype' => $studtype
	  				]);

	  		$idprefix = db::table('idprefix')->first();
	  		
	  		$id = sprintf('%06d', $studid);

	  		$sid = $idprefix->prefix . $id;

	  		$upd = db::table('studinfo')
	  			->where('id', $studid)
	  			->update([
	  				'sid' => $sid
	  			]);
	  	}
	  }

	  public function admission(Request $request)
	  {
	  	return view('/enrollment/admission');
	  }

	  public function searchPreReg(Request $request)
	  {
	  	if($request->ajax())
	  	{
	  		$code = $request->get('code');

	  		$acadprog = RegistrarModel::activeacadprog();

	  		$gradelevel = db::table('gradelevel')
	  			->select('id')
	  			->whereIn('acadprogid', $acadprog)
	  			->where('deleted', 0)
	  			->get();

	  		$lvl = array();
	  		foreach($gradelevel as $level)
	  		{
	  			array_push($lvl, $level->id);
	  		}

	  		$prereg = db::table('preregistration')
	  			->where('queing_code', 'like', '%'.$code.'%')
	  			->where('status', 0)
	  			->where('deleted', 0)
	  			->whereIn('gradelevelid', $lvl)
	  			->orWhere('last_name', 'like', '%'.$code.'%')
	  			->where('status', 0)
	  			->where('deleted', 0)
	  			->whereIn('gradelevelid', $lvl)
	  			->orWhere('first_name', 'like', '%'.$code.'%')
	  			->where('status', 0)
	  			->where('deleted', 0)
	  			->whereIn('gradelevelid', $lvl)
	  			->orderBy('last_name', 'ASC')
	  			->get();

	  		$output = '';

	  		foreach($prereg as $reg)
	  		{
	  			$dateregistered = date_create($reg->date_created);
	  			$dateregistered = date_format($dateregistered, 'm-d-Y');
	  			$dob = date_create($reg->dob);
	  			$dob = date_format($dob, 'm-d-Y');
	  			$output .= '
	  				<tr>
	  					<td>'.strtoupper($reg->last_name.' '.$reg->first_name.' ' .$reg->middle_name. ' ' .$reg->suffix).'</td>
	  					<td>'.$dob.'</td>
	  					<td>'.$reg->contact_number.'</td>
	  					<td>'.$reg->queing_code.'</td>
	  					<td>'.$dateregistered.'</td>
	  					<td style="width:1px">
	  						<a class="btn btn-primary" style="color:white !important;" href="/admission/edit/' .$reg->queing_code.'">Register</a>
	  					</td>
	  					<td>
	  						<button id="" class="btn btn-danger preregdel" data-id = "'.$reg->id.'">Delete</button>
	  					</td>
	  				</tr>
	  			';
	  		}
	  		$data = array(
	  			'output' => $output
	  		);

	  		echo json_encode($data);
	  	}
	  }

	  public function admissionedit(Request $request, $code)
	  {
	  	
	  	$glevel = db::table('gradelevel')
  			->where('deleted', 0)
  			->orderBy('sortid', 'asc')
  			->get();

	  	$religion = db::table('religion')
  			->where('deleted', 0)
  			->get();

	  	$mothertongue = db::table('mothertongue')
  			->where('deleted', 0)
  			->get();

	  	$ethnic = db::table('ethnic')
  			->where('deleted', 0)
  			->get();


	  	$stud = db::table('preregistration')
	  		->where('queing_code', $code)
	  		->first();

	  	$grantee = db::table('grantee')
	  		->get();

	  	$nationality = db::table('nationality')
	  		->where('deleted', 0)
	  		->get();

	  	$modeoflearning  = db::table('modeoflearning')
	  		->where('deleted', 0)
	  		->get();

	  	$data = array(
	  		'glevel' => $glevel,
	  		'religion' => $religion,
	  		'mothertongue' => $mothertongue,
	  		'ethnic' => $ethnic,
	  		'stud' => $stud,
	  		'code' => $code,
	  		'grantee' => $grantee,
	  		'nationality' => $nationality,
	  		'modeoflearning' => $modeoflearning
	  	);

	  	return view('/enrollment/admission_edit')->with($data);
	  }

	  public function admissionregister(Request $request)
	  {
	  	if($request->ajax())	
	  	{	
	  		$code = $request->get('code');
	  		$lrn = $request->get('lrn');
	  		$grantee = $request->get('grantee');
	  		$glevel = $request->get('glevel');
	  		$fname = $request->get('fname');
	  		$mname = $request->get('mname');
	  		$lname = $request->get('lname');
	  		$suffix = $request->get('suffix');
	  		$dob = $request->get('dob');
	  		$gender = $request->get('gender');
	  		$contactno = $request->get('contactno');
	  		$religion = $request->get('religion');
	  		$mt = $request->get('mt');
	  		$eg = $request->get('eg');
	  		$street = $request->get('street');
	  		$barangay = $request->get('barangay');
	  		$city = $request->get('city');
	  		$province = $request->get('province');
	  		$fathername = $request->get('fathername');
	  		$foccupation = $request->get('foccupation');
	  		$fcontactno = $request->get('fcontactno');
	  		$mothername = $request->get('mothername');
	  		$moccupation = $request->get('moccupation');
	  		$mcontactno = $request->get('mcontactno');
	  		$guardianname = $request->get('guardianname');
	  		$guardianrelation = $request->get('guardianrelation');
	  		$gcontactno = $request->get('gcontactno');
	  		$bloodtype = $request->get('bloodtype');
	  		$allergy = $request->get('allergy');
	  		$others = $request->get('others');
	  		$lastschool = $request->get('lastschool');

	  		$mol = $request->get('mol');
	  		$nationality = $request->get('nationality');

	  		$strandid = $request->get('strandid');
	  		

	  		$isfather = $request->get('isfather');
	  		$ismother = $request->get('ismother');
	  		$isguardian = $request->get('isguardian');

	  		$studtype = $request->get('studtype');

	  		$prereg = db::table('preregistration')
	  			->where('queing_code', $code)
	  			->first();


	  		$courseid = $prereg->courseid;


	  		$validate = db::table('studinfo')
	  				->where('lastname', $lname)
	  				->where('firstname', $fname)
	  				->where('middlename', $mname)
	  				->where('dob', $dob)
	  				->where('deleted', 0)
	  				->get();

	  		if(count($validate) > 0)
	  		{
	  			return 0;
	  		}
	  		else
	  		{
	  			$studid = DB::table('studinfo')
	  				->insertGetId([
	  					'lrn' => $lrn,
	  					'levelid' => $glevel,
	  					'firstname' => $fname,
	  					'middlename' => $mname,
	  					'lastname' => $lname,
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
	  					'foccupation' => $foccupation,
	  					'fcontactno' => $fcontactno,
	  					'isfathernum' => $isfather,
	  					'mothername' => $mothername,
	  					'moccupation' => $moccupation,
	  					'mcontactno' => $mcontactno,
	  					'ismothernum' => $ismother,
	  					'guardianname' => $guardianname,
	  					'guardianrelation' => $guardianrelation,
	  					'gcontactno' => $gcontactno,
	  					'isguardannum' => $isguardian,
	  					'bloodtype' => $bloodtype,
	  					'allergy' => $allergy,
	  					'others' => $others,
	  					'deleted' => 0,
	  					'studstatus' => 0,
	  					'qcode' => $code,
	  					'courseid' => $courseid,
	  					'preEnrolled' => 1,
	  					'strandid' => $strandid,
	  					'grantee' => $grantee,
	  					'mol' => $mol,
	  					'nationality' => $nationality,
	  					'lastschoolatt' => $lastschool,
	  					'studtype' => $studtype,
	  					'createdby' => auth()->user()->id,
	  					'createddatetime' => RegistrarModel::getServerDateTime()
	  				]);	

	  			$idprefix = db::table('idprefix')->first();
	  		
		  		$id = sprintf('%06d', $studid);

		  		$sid = $idprefix->prefix . $id;

		  		$upd = db::table('studinfo')
		  			->where('id', $studid)
		  			->update([
		  				'sid' => $sid
		  			]);

		  		//SYNC FOR STUDINFO
		  			
		  		//SYNC FOR STUDINFO
		  			

		  		$updstat = db::table('preregistration')
	  				->where('queing_code', $code)
	  				->update([
	  					'status' => 1,
	  					'updateddatetime' => RegistrarModel::getServerDateTime(),
	  					'updatedby' => auth()->user()->id
	  				]);
	  			return 1;
	  		}
	  	}
	  }

	  public function preregdel(Request $request) 
	  {
	  	if($request->ajax())
	  	{
	  		$dataid = $request->get('dataid');

	  		$prereg = db::table('preregistration')
	  				->where('id', $dataid)
	  				->first();

	  		$onlinepay = db::table('onlinepayments')
	  				->where('queingcode', $prereg->queing_code)
	  				->get();

	  		if(count($onlinepay) > 0)
	  		{
	  			return 1;
	  		}
	  		else
	  		{

		  		$prereg = db::table('preregistration')
		  				->where('id', $dataid)
		  				->update([
		  					'deleted' => 1,
		  					'deletedby' => auth()->user()->id,
		  					'deleteddatetime'=> RegistrarModel::getServerDateTime()
		  				]);

		  		return 0;
		  	}

	  	}
	  }

	  public function studdata(Request $request)
	  {
	  	if($request->ajax())
	  	{
	  		$strandid = 0;
	  		$blockid = 0;
	  		$courseid = 0;
	  		$sectionid = 0;
	  		$sectionlist = '';
	  		$units = 0;
			$subjlist = '';
			$section_col;


	  		$studid = $request->get('studid');
	  		$curlevelid = $request->get('curlevelid');
	  		// $glvl = $request->get('glevel');

	  		

	  		$syid = RegistrarModel::getSYID();
	  		$semid = RegistrarModel::getSemID();

	  		$studentstatus = db::table('studentstatus')
	  				->get();

	  		$student = db::table('studinfo')
	  				->where('id', $studid)
	  				->first();

	  		$studlevelid = $student->levelid;

	  		// $glvl = $student->levelid;
	  		$glvl = $curlevelid;

	  		if($glvl == '')	
		  	{
		  		$sections = db::table('sections')
		  			->select('sections.id', 'sectionname', 'capacity')
		  			->join('rooms', 'sections.roomid', '=', 'rooms.id')
		  			->where('sections.deleted', 0)
		  			->where('levelid', $student->levelid)
		  			->get();

		  		$lID = $student->levelid;
		  	}

		  	else
		  	{
		  		$sections = db::table('sections')
		  				->select('sections.id', 'sectionname', 'capacity')
		  				->join('rooms', 'sections.roomid', '=', 'rooms.id')
		  				->where('sections.deleted', 0)
		  				->where('levelid', $glvl)
		  				->get();
		  		// return $sections;	

		  		$lID = $glvl;
			}		  			

			$sectionid = 0;


			if($student->studtype == null || $student->studtype != 'new')
			{
				$glevel = db::table('gradelevel')
	  				// ->where('id', $lID)
					// ->select('*', 'a')
					->where(function($q) use($studlevelid) {
						$gradelevel = db::table('gradelevel')
							->where('id', $studlevelid)
							->first();

						$q->where('sortid', $gradelevel->sortid);
						$q->orWhere('sortid', $gradelevel->sortid + 1);
					})
	  				->orderBy('sortid')
	  				->get();
			}
			else
			{
				$glevel = db::table('gradelevel')
					->where('deleted', 0)
					->orderBy('sortid', 'ASC')
					->get();
			}

	  		$level ='';
	  		if(count($glevel) > 0)
	  		{
		  		foreach($glevel as $l)
		  		{
		  			if($l->id == $student->levelid)
		  			{
		  				$level .= '
			  				<option selected value="'.$l->id.'">'.$l->levelname.'</option>
			  			';
		  			}
		  			else
		  			{
			  			$level .= '
			  				<option value="'.$l->id.'">'.$l->levelname.'</option>
			  			';
			  		}
		  		}
		  	}

		  	$section = '<option></option>';

		  	foreach($sections as $sec)
	  		{
	  			if($lID == 14 || $lID == 15)
	  			{
		  			$studCount = db::select('SELECT rooms.capacity, COUNT(DISTINCT sh_enrolledstud.studid) AS studcount 
		  					FROM sh_enrolledstud 
		  					INNER JOIN sections ON sh_enrolledstud.sectionid = sections.id 
		  					INNER JOIN rooms ON sections.roomid = rooms.id 
		  					WHERE sectionid = ? and semid = ?', [$sec->id, RegistrarModel::getSemID()]);

		  			$strandid = $student->strandid;
	  			}
	  			elseif($lID >= 17 && $lID <= 21)
	  			{
	  				$studCount = db::select('SELECT rooms.capacity, COUNT(DISTINCT sh_enrolledstud.studid) AS studcount 
		  					FROM sh_enrolledstud 
		  					INNER JOIN sections ON sh_enrolledstud.sectionid = sections.id 
		  					INNER JOIN rooms ON sections.roomid = rooms.id 
		  					WHERE sectionid = ? and semid = ?', [$sec->id, RegistrarModel::getSemID()]);

	  			}
	  			else
	  			{
	  				$studCount = db::select('SELECT rooms.capacity, COUNT(DISTINCT enrolledstud.studid) AS studcount 
		  					FROM enrolledstud 
		  					INNER JOIN sections ON enrolledstud.sectionid = sections.id 
		  					INNER JOIN rooms ON sections.roomid = rooms.id 
		  					WHERE sectionid = ?', [$sec->id]);	
	  			}

	  			// return $sectionid;

	  			$gCount = $studCount[0]->capacity * .25;

	  			if($studCount[0]->studcount <= $gCount)
	  			{
	  				if($sec->id == $sectionid)
	  				{
						$section .= '
	  						<option class="text-success" selected="" value="'.$sec->id.'">
	  							'.$sec->sectionname.' - ('.$studCount[0]->studcount.' | '.$studCount[0]->capacity.')
	  						</option>
		  				';	  				
		  			}
		  			else
		  			{
		  				$section .= '
		  					<option class="text-success" value="'.$sec->id.'">
		  						'.$sec->sectionname.' - ('.$studCount[0]->studcount.' | '.$studCount[0]->capacity.')
		  					</option>
		  				';
		  			}
	  			}
	  			else if($studCount[0]->studcount <= $studCount[0]->capacity * .50)
	  			{
	  				if($sec->id == $sectionid)
	  				{
		  				$section .= '
		  				<option class="text-primary" selected="" value="'.$sec->id.'">'.$sec->sectionname.' - ('.$studCount[0]->studcount.' | '.$studCount[0]->capacity.')</option>
		  				';
		  			}
		  			else
		  			{
		  				$section .= '
		  				<option class="text-primary" value="'.$sec->id.'">'.$sec->sectionname.' - ('.$studCount[0]->studcount.' | '.$studCount[0]->capacity.')</option>
		  				';
		  			}
	  			}
	  			elseif($studCount[0]->studcount <= $studCount[0]->capacity *.75)
	  			{
	  				if($sec->id == $sectionid)
	  				{
		  				$section .= '
		  				<option class="text-secondary" selected="" value="'.$sec->id.'">'.$sec->sectionname.' - ('.$studCount[0]->studcount.' | '.$studCount[0]->capacity.')</option>
		  				';	
		  			}
		  			else
		  			{
		  				$section .= '
		  				<option class="text-secondary" value="'.$sec->id.'">'.$sec->sectionname.' - ('.$studCount[0]->studcount.' | '.$studCount[0]->capacity.')</option>
		  				';	
		  			}
	  			}
	  			else
	  			{
	  				if($sec->id == $sectionid)
	  				{
		  				$section .= '
		  				<option selected="" class="text-danger" value="'.$sec->id.'">'.$sec->sectionname.' - ('.$studCount[0]->studcount.' | '.$studCount[0]->capacity.')</option>
		  				';
		  			}
		  			else
		  			{
		  				$section .= '
		  				<option class="text-danger" value="'.$sec->id.'">'.$sec->sectionname.' - ('.$studCount[0]->studcount.' | '.$studCount[0]->capacity.')</option>
		  				';
		  			}
	  			}

	  			
	  		}

	  		$sy = db::table('sy')
	  				->get();

	  		$syear = '';

	  		foreach($sy as $s)
	  		{
	  			if($s->isactive == 1)
	  			{
	  				$syear .='
	  					<option selected value="'.$s->id.'">'.$s->sydesc.'</option>
	  				';
	  			}
	  			else
	  			{
	  				$syear .='<option value="'.$s->id.'">'.$s->sydesc.'</option>';
	  			}
	  		}

	  		$sem = '';
	  		$str = '';
	  		$blk = '';

	  		// if($student->levelid == 14 || $student->levelid == 15)
	  		if($curlevelid == 14 || $curlevelid == 15)
	  		{
		  		$semesters = db::table('semester')
					->where('isactive', 1)
					->get();

				$enrollment = db::table('sh_enrolledstud')	
					->where('studid',  $student->id)
					->get();


				foreach($semesters as $semester)
				{
					if($semester->id == $semid)
					{
						$sem .= '
							<option selected="" value="'.$semester->id.'">'.$semester->semester.'</option>
						';
					}
					else
					{
						$sem .= '
							<option value="'.$semester->id.'">'.$semester->semester.'</option>
						';
					}
				}

				$strand = db::table('sh_strand')
				->where('deleted', 0)
				->where('active', 1)
				->get();

				$str = '<option></option>';

				foreach($strand as $s)
				{
					// return $strandid;
					if($s->id == $strandid)
					{
						$str .= '
							<option selected="" value="'.$s->id.'">'.strtoupper($s->strandname).'</option>
						';
					}
					else
					{
						$str .= '
							<option value="'.$s->id.'">'.strtoupper($s->strandname).'</option>
						';
					}
				}

				$getblock = db::table('sh_block')
					->where('strandid', $strandid)
					->where('deleted', 0)
					->get();

				$blk = '<option></option>';
				if(count($getblock) > 0)
				{
					foreach($getblock as $block)
					{

						if(count($enrollment) > 0 && $block->id == $enrollment[0]->blockid)
						{
							$blk .= '
								<option selected="" value="'.$block->id.'">'.$block->blockname.'</option>
							';
						}
						else
						{
							$blk .= '
								<option value="'.$block->id.'">'.$block->blockname.'</option>
							';
						}
					}
				}
			}
			// elseif($student->levelid >= 17 && $student->levelid <= 21)
			elseif($curlevelid >= 17 && $curlevelid <= 21)
			{
				$courseid = $student->courseid;
  			
	  			$section = $student->sectionid;
	  			$sectionlist = '<option></option>';
	  			foreach(RegistrarModel::loadcollegeSection($courseid) as $sec)
	  			{
	  				$sectionlist .='
	  					<option value="'.$sec->id.'">'.$sec->sectionDesc.'</option>
	  				';
	  			}

				$semesters = db::table('semester')
					->where('isactive', 1)
					->get();

				foreach($semesters as $semester)
				{
					if($semester->id == $semid)
					{
						$sem .= '
							<option selected="" value="'.$semester->id.'">'.$semester->semester.'</option>
						';
					}
					else
					{
						$sem .= '
							<option value="'.$semester->id.'">'.$semester->semester.'</option>
						';
					}
				}

				

				$scheds = db::table('college_studsched')
						->select('studid', 'college_classsched.syID', 'college_classsched.semesterID', 'sectionDesc', 'subjDesc', 'lecunits', 'labunits', 
							'days.description', 'stime', 'etime')
						->join('college_classsched' , 'college_studsched.schedid' , '=', 'college_classsched.id')
						->leftjoin('college_scheddetail',function($join){
							$join->on('college_classsched.id', '=', 'college_scheddetail.headerID');
							$join->where('college_scheddetail.deleted',0);
						} )
						->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
						->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
						->leftjoin('days', 'college_scheddetail.day', '=', 'days.id')
						->where('college_classsched.syID' , RegistrarModel::getSYID())
						->where('college_classsched.semesterID' , RegistrarModel::getSemID())
						->where('studid', $studid)
						// ->where('college_scheddetail.deleted', 0)
						// ->where('college_classsched.deleted', 0)
						->where('college_studsched.deleted', 0)
						->groupBy('college_prospectus.id')
						->groupBy('day')
						->get();

				$subjdesc = '';
				foreach($scheds as $sched)
				{
					if($sched->stime != null)
					{
						$stime = date_create($sched->stime);
						$stime = date_format($stime, 'h:i A');
					}
					else
					{
						$stime = '';
					}


					if($sched->etime != null)
					{
						$etime = date_create($sched->etime);
						$etime = date_format($etime, 'h:i A');						
					}
					else
					{
						$etime = '';
					}

					if($subjdesc != $sched->subjDesc)
					{

						$subjdesc = $sched->subjDesc;
						$units += $sched->lecunits;
						$units += $sched->labunits;
						$subjlist .= '
							<tr class="bg-warning text-bold">
								<td colspan="2">'.$sched->subjDesc.'</td>
								<td colspan="" class="text-center">'.$sched->lecunits.'</td>
								<td colspan="" class="text-center">'.$sched->labunits.'</td>
								<td></td>
								<td></td>
							</tr>
						';
					}

					$subjlist .='
						<tr class="ml-3">
							<td></td>
						<td>'.$sched->description.'</td>
						<td></td>
						<td></td>
						<td class="text-center">'.$stime.'</td>
						<td class="text-center">'.$etime.'</td>
					</tr>
					';

				}

				$subjlist .='
					<tr class="">
						<td></td>
						<td colspan="1" class="text-right text-bold"></td>
						<td class="text-bold text-primary">TOTAL UNITS: '.$units.'</td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				';

			}

			$studstatus = '';
	  		foreach($studentstatus as $stat)
	  		{
	  			if($stat->id == 0)
	  			{
	  				$studstatus .= '
	  					<option selected value="'.$stat->id.'">'.$stat->description.'</option>
	  				';	
	  			}
	  			else
	  			{
		  			$studstatus .= '
		  				<option value="'.$stat->id.'">'.$stat->description.'</option>
		  			';
		  		}
	  		}

	  		if($curlevelid == '')
	  		{
	  			$curlevelid = $student->levelid;
	  		}

	  		// return $curlevelid;

	  		$data = array(
	  			'studid' =>$student->id,
	  			'lrn' => $student->lrn,
	  			'name' => strtoupper($student->lastname . ' ' . $student->firstname . ' ' . $student->middlename . ' ' . $student->suffix),
	  			'sid' => $student->sid,
	  			'level' => $level,
	  			'section' => $section,
	  			'syear' => $syear,
	  			'studstatus' => $studstatus,
	  			'sem' => $sem,
	  			'strand' => $str,
	  			'block' => $blk,
	  			'courseid' => $courseid,
	  			'sstatus' => 0,
	  			'sectionlist' => $sectionlist,
	  			'subjlist' => $subjlist,
	  			'units' => $units,
	  			'grantee' => $student->grantee,
	  			'mol' => $student->mol,
	  			'studclass' => $student->studclass,
	  			'curlevelid' => $curlevelid,
	  			'sectionname' => $student->sectionname
	  		);

	  		echo json_encode($data);

	  	}
	  }

	  public function enrollgetinfo(Request $request)
	  {
	  	if($request->ajax())
	  	{
	  		// $semid = 0;
	  		$strandid = 0;
	  		$blockid = 0;
	  		$courseid = 0;
	  		$units = 0;
	  		$subjlist = '';
	  		$sectionlist ='';

	  		$studstatid = 0;

	  		$studid = $request->get('studid');
	  		$glvl = $request->get('glevel');

	  		$studstatdate = '';

	  		$syid = RegistrarModel::getSYID();
	  		$semid = RegistrarModel::getSemID();

	  		$sy = db::table('sy')
	  				->get();

	  		$studentstatus = db::table('studentstatus')
	  				->get();

	  		$student = db::table('studinfo')
	  				->where('id', $studid)
	  				->first();

	  		if($student->levelid == 14 || $student->levelid == 15)
	  		{
	  			$enrollinfo = db::table('sh_enrolledstud')
	  				->where('studid', $student->id)
	  				->where('syid', $syid)
	  				->where('semid', $semid)
	  				->get();

	  			$sections = db::table('sections')
	  				->select('sections.id', 'sectionname', 'capacity')
	  				->join('rooms', 'sections.roomid', '=', 'rooms.id')
	  				->where('sections.deleted', 0)
	  				->where('levelid', $enrollinfo[0]->levelid)
	  				->get();

	  			$lID = $enrollinfo[0]->levelid;

	  		}
	  		elseif($student->levelid >= 17 && $student->levelid <= 21)
	  		{
	  			$enrollinfo = db::table('college_enrolledstud')
	  				->where('studid', $student->id)
	  				->where('syid', $syid)
	  				->where('semid', $semid)
	  				->get();	

	  			$sections = db::table('sections')
	  				->select('sections.id', 'sectionname', 'capacity')
	  				->join('rooms', 'sections.roomid', '=', 'rooms.id')
	  				->where('sections.deleted', 0)
	  				->where('levelid', $enrollinfo[0]->yearLevel)
	  				->get();


	  			$lID = $enrollinfo[0]->yearLevel;
	  		}
	  		else
	  		{
	  			$enrollinfo = db::table('enrolledstud')
	  				->where('studid', $student->id)
	  				->where('syid', $syid)
	  				->get();	

	  			$sections = db::table('sections')
	  				->select('sections.id', 'sectionname', 'capacity')
	  				->join('rooms', 'sections.roomid', '=', 'rooms.id')
	  				->where('sections.deleted', 0)
	  				->where('levelid', $enrollinfo[0]->levelid)
	  				->get();

	  			$lID = $enrollinfo[0]->levelid;
	  		}


		  	$dateenrolled = 0; 
		  	$sectionid = 0;
		  	$sStatus = 0;

		  	$sem = '';

	  		if($lID == 14 || $lID == 15)
	  		{
	  			
	  			if(count($enrollinfo) > 0)
	  			{
		  			$sectionid = $enrollinfo[0]->sectionid;
		  			$sStatus = $enrollinfo[0]->studstatus;
		  			$blockid = $enrollinfo[0]->blockid;
		  			$strandid = $enrollinfo[0]->strandid;
		  			$semid = $enrollinfo[0]->semid;
		  			$dateenrolled = $enrollinfo[0]->dateenrolled;
		  		}
		  		else
		  		{
		  			$strandid = $student->strandid;
		  		}

		  		$promoted = $enrollinfo[0]->promotionstatus;

		  		$glevel = db::table('gradelevel')
	  				->where('id', $enrollinfo[0]->levelid)
	  				->orderBy('sortid')
	  				->get();

	  		}
	  		if($lID >= 17 && $lID <= 21)
	  		{

	  			$courseid = $student->courseid;

					$semesters = db::table('semester')
			        ->where('isactive', 1)
			        ->get();

			    foreach($semesters as $semester)
			    {
			        if($semester->id == $semid)
			        {
			            $sem .= '
			                <option selected="" value="'.$semester->id.'">'.$semester->semester.'</option>
			            ';
			        }
			        else
			        {
			            $sem .= '
			                <option value="'.$semester->id.'">'.$semester->semester.'</option>
			            ';
			        }
			    }

					$scheds = db::table('college_studsched')
						->select('studid', 'college_classsched.syID', 'college_classsched.semesterID', 'sectionDesc', 'subjDesc', 'lecunits', 'labunits', 
							'days.description', 'stime', 'etime')
						->join('college_classsched' , 'college_studsched.schedid' , '=', 'college_classsched.id')
						->leftjoin('college_scheddetail',function($join){
							$join->on('college_classsched.id', '=', 'college_scheddetail.headerID');
							$join->where('college_scheddetail.deleted',0);
						})
						->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
						->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
						->leftjoin('days', 'college_scheddetail.day', '=', 'days.id')
						->where('college_classsched.syID' , RegistrarModel::getSYID())
						->where('college_classsched.semesterID' , RegistrarModel::getSemID())
						->where('studid', $studid)
						// ->where('college_scheddetail.deleted', 0)
						// ->where('college_classsched.deleted', 0)
						->where('college_studsched.deleted', 0)
						->groupBy('college_prospectus.id')
						->groupBy('day')
						->get();

					// return $scheds;

					$subjdesc = '';
					foreach($scheds as $sched)
					{
						if($sched->stime != null)
						{
							$stime = date_create($sched->stime);
							$stime = date_format($stime, 'h:i A');
						}
						else
						{
							$stime = '';
						}


						if($sched->etime != null)
						{
							$etime = date_create($sched->etime);
							$etime = date_format($etime, 'h:i A');						
						}
						else
						{
							$etime = '';
						}

						if($subjdesc != $sched->subjDesc)
						{

							$subjdesc = $sched->subjDesc;
							$units += $sched->lecunits;
							$units += $sched->labunits;
							$subjlist .= '
								<tr class="bg-warning text-bold">
									<td colspan="2">'.$sched->subjDesc.'</td>
									<td colspan="" class="text-center">'.$sched->lecunits.'</td>
									<td colspan="" class="text-center">'.$sched->labunits.'</td>
									<td></td>
									<td></td>
								</tr>
							';
						}

						$subjlist .='
							<tr class="ml-3">
								<td></td>
								<td>'.$sched->description.'</td>
								<td></td>
								<td></td>
								<td class="text-center">'.$stime.'</td>
								<td class="text-center">'.$etime.'</td>
    						</tr>
						';

					}

					$subjlist .='
						<tr class="">
							<td></td>
							<td colspan="1" class="text-right text-bold"></td>
							<td class="text-bold text-primary">TOTAL UNITS: '.$units.'</td>
							<td></td>
							<td></td>
							<td></td>
  						</tr>
					';

	  			if(count($enrollinfo) > 0)
	  			{
		  			$sectionid = $enrollinfo[0]->sectionID;
		  			$sStatus = $enrollinfo[0]->studstatus;
		  			$courseid	= $enrollinfo[0]->courseid;
		  			$semid = $enrollinfo[0]->semid;
		  			$dateenrolled = $enrollinfo[0]->date_enrolled;
		  		}

		  		$promoted = 0;

		  		$glevel = db::table('gradelevel')
	  				->where('id', $enrollinfo[0]->yearLevel)
	  				->orderBy('sortid')
	  				->get();
	  		}
	  		else
	  		{
	  			if(count($enrollinfo) > 0)
	  			{
		  			$sectionid = $enrollinfo[0]->sectionid;
		  			$sStatus = $enrollinfo[0]->studstatus;
		  			$dateenrolled = $enrollinfo[0]->dateenrolled;
		  		}

		  		$promoted = $enrollinfo[0]->promotionstatus;

		  		$glevel = db::table('gradelevel')
	  				->where('id', $enrollinfo[0]->levelid)
	  				->orderBy('sortid')
	  				->get();
	  		}

	  		$level = '';

	  		if($glvl == '')
	  		{
		  		foreach($glevel as $l)
		  		{
		  			if($l->id == $enrollinfo->levelid)
		  			{
		  				$level .= '
			  				<option selected value="'.$l->id.'">'.$l->levelname.'</option>
			  			';
		  			}
		  			else
		  			{
			  			$level .= '
			  				<option value="'.$l->id.'">'.$l->levelname.'</option>
			  			';
			  		}
		  		}
			}
			else
			{
				foreach($glevel as $l)
		  		{
		  			if($l->id == $glvl)
		  			{
		  				$level .= '
			  				<option selected value="'.$l->id.'">'.$l->levelname.'</option>
			  			';
		  			}
		  			else
		  			{
			  			$level .= '
			  				<option value="'.$l->id.'">'.$l->levelname.'</option>
			  			';
			  		}
		  		}
			}

	  		$section = '<option></option>';

	  		foreach($sections as $sec)
	  		{
	  			if($lID == 14 || $lID == 15)
	  			{
		  			$studCount = db::select('SELECT rooms.capacity, COUNT(DISTINCT sh_enrolledstud.studid) AS studcount 
		  					FROM sh_enrolledstud 
		  					INNER JOIN sections ON sh_enrolledstud.sectionid = sections.id 
		  					INNER JOIN rooms ON sections.roomid = rooms.id 
		  					WHERE sectionid = ? and semid = ?', [$sec->id, RegistrarModel::getSemID()]);
	  			}
	  			elseif($lID >= 17 && $lID <= 21)
	  			{
	  				$studCount = db::select('SELECT rooms.capacity, COUNT(DISTINCT college_enrolledstud.studid) AS studcount 
		  					FROM college_enrolledstud 
		  					INNER JOIN sections ON college_enrolledstud.sectionID = sections.id 
		  					INNER JOIN rooms ON sections.roomid = rooms.id 
		  					WHERE sectionID = ? and semid = ?', [$sec->id, RegistrarModel::getSemID()]);

							// $sections = db::table('college_sections')
			  		// 		->select('college_sections.id', 'sectionDesc')
			  		// 		->where('college_sections.deleted', 0)
			  		// 		->where('yearID', $enrollinfo[0]->yearLevel)
			  		// 		->get();

			  			
	  			}
	  			else
	  			{
	  				$studCount = db::select('SELECT rooms.capacity, COUNT(DISTINCT enrolledstud.studid) AS studcount 
		  					FROM enrolledstud 
		  					INNER JOIN sections ON enrolledstud.sectionid = sections.id 
		  					INNER JOIN rooms ON sections.roomid = rooms.id 
		  					WHERE sectionid = ?', [$sec->id]);	
	  			}

	  			// return $sectionid;

	  			$gCount = $studCount[0]->capacity * .25;

	  			if($studCount[0]->studcount <= $gCount)
	  			{
	  				if($sec->id == $enrollinfo[0]->sectionid)
	  				{
						$section .= '
	  						<option class="text-success" selected="" value="'.$sec->id.'">
	  							'.$sec->sectionname.' - ('.$studCount[0]->studcount.' | '.$studCount[0]->capacity.')
	  						</option>
		  				';	  				
		  			}
		  			else
		  			{
		  				$section .= '
		  					<option class="text-success" value="'.$sec->id.'">
		  						'.$sec->sectionname.' - ('.$studCount[0]->studcount.' | '.$studCount[0]->capacity.')
		  					</option>
		  				';
		  			}
	  			}
	  			else if($studCount[0]->studcount <= $studCount[0]->capacity * .50)
	  			{
	  				if($sec->id == $sectionid)
	  				{
		  				$section .= '
		  				<option class="text-primary" selected="" value="'.$sec->id.'">'.$sec->sectionname.' - ('.$studCount[0]->studcount.' | '.$studCount[0]->capacity.')</option>
		  				';
		  			}
		  			else
		  			{
		  				$section .= '
		  				<option class="text-primary" value="'.$sec->id.'">'.$sec->sectionname.' - ('.$studCount[0]->studcount.' | '.$studCount[0]->capacity.')</option>
		  				';
		  			}
	  			}
	  			elseif($studCount[0]->studcount <= $studCount[0]->capacity *.75)
	  			{
	  				if($sec->id == $sectionid)
	  				{
		  				$section .= '
		  				<option class="text-secondary" selected="" value="'.$sec->id.'">'.$sec->sectionname.' - ('.$studCount[0]->studcount.' | '.$studCount[0]->capacity.')</option>
		  				';	
		  			}
		  			else
		  			{
		  				$section .= '
		  				<option class="text-secondary" value="'.$sec->id.'">'.$sec->sectionname.' - ('.$studCount[0]->studcount.' | '.$studCount[0]->capacity.')</option>
		  				';	
		  			}
	  			}
	  			else
	  			{
	  				if($sec->id == $enrollinfo[0]->sectionid)
	  				{
		  				$section .= '
		  				<option selected="" class="text-danger" value="'.$sec->id.'">'.$sec->sectionname.' - ('.$studCount[0]->studcount.' | '.$studCount[0]->capacity.')</option>
		  				';
		  			}
		  			else
		  			{
		  				$section .= '
		  				<option class="text-danger" value="'.$sec->id.'">'.$sec->sectionname.' - ('.$studCount[0]->studcount.' | '.$studCount[0]->capacity.')</option>
		  				';
		  			}
	  			}

	  			
	  		}

	  		$syear = '';

	  		foreach($sy as $s)
	  		{
	  			if($s->isactive == 1)
	  			{
	  				$syear .='
	  					<option selected value="'.$s->id.'">'.$s->sydesc.'</option>
	  				';
	  			}
	  			else
	  			{
	  				$syear .='<option value="'.$s->id.'">'.$s->sydesc.'</option>';
	  			}
	  		}
	  		
	  		// $sem = '';
	  		$str = '';
	  		$blk = '';

	  		if($lID == 14 || $lID == 15)
	  		{
		  		$semesters = db::table('semester')
						->where('isactive', 1)
						->get();

					

					foreach($semesters as $semester)
					{
						if($semester->id == $semid)
						{
							$sem .= '
								<option selected="" value="'.$semester->id.'">'.$semester->semester.'</option>
							';
						}
						else
						{
							$sem .= '
								<option value="'.$semester->id.'">'.$semester->semester.'</option>
							';
						}
					}

					$strand = db::table('sh_strand')
					->where('deleted', 0)
					->where('active', 1)
					->get();

					$str = '<option></option>';

					foreach($strand as $s)
					{
						// return $strandid;
						if($s->id == $strandid)
						{
							$str .= '
								<option selected="" value="'.$s->id.'">'.strtoupper($s->strandname).'</option>
							';
						}
						else
						{
							$str .= '
								<option value="'.$s->id.'">'.strtoupper($s->strandname).'</option>
							';
						}
					}

					$getblock = db::table('sh_block')
						->where('strandid', $strandid)
						->where('deleted', 0)
						->get();

					$blk = '<option></option>';
					if(count($getblock) > 0)
					{
						foreach($getblock as $block)
						{

							if(count($enrollinfo) > 0 && $block->id == $enrollinfo[0]->blockid)
							{
								$blk .= '
									<option selected="" value="'.$block->id.'">'.$block->blockname.'</option>
								';
							}
							else
							{
								$blk .= '
									<option value="'.$block->id.'">'.$block->blockname.'</option>
								';
							}
						}
					}
				}

				if($lID >= 17 && $lID <= 21)
				{
					$section = $student->sectionid;
					$sectionlist = '<option></option>';
					foreach(RegistrarModel::loadcollegeSection($courseid) as $sec)
					{
					    $sectionlist .='
					        <option value="'.$sec->id.'">'.$sec->sectionDesc.'</option>
					    ';
					}
				}

				// return $studentstatus;
			$studstat = '';
	  		$studstatus = '';
	  		$studstatdate = $student->studstatdate;

	  		$studstatdate = date_create($studstatdate);
	  		$studstatdate = date_format($studstatdate, 'm-d-Y');

	  		$studstatid = $student->studstatus;

	  		foreach($studentstatus as $stat)
	  		{
	  			if($stat->id == $sStatus)
	  			{
	  				$studstat = $stat->description;

	  				$studstatus .= '
	  					<option selected value="'.$stat->id.'">'.$stat->description.'</option>
	  				';	
	  			}
	  			else
	  			{
		  			$studstatus .= '
		  				<option value="'.$stat->id.'">'.$stat->description.'</option>
		  			';
		  		}
	  		}




	  		$dateenrolled = date_create($dateenrolled);
	  		$dateenrolled = date_format($dateenrolled, 'm-d-Y');

	  		// return $section;
	  		$data = array(
	  			'studid' =>$student->id,
	  			'lrn' => $student->lrn,
	  			'name' => strtoupper($student->lastname . ' ' . $student->firstname . ' ' . $student->middlename . ' ' . $student->suffix),
	  			'dateenrolled' => $dateenrolled,
	  			'sid' => $student->sid,
	  			'level' => $level,
	  			'section' => $section,
	  			'syear' => $syear,
	  			'studstatus' => $studstatus,
	  			'sem' => $sem,
	  			'strand' => $str,
	  			'block' => $blk,
	  			'sstatus' => $sStatus,
	  			'promoteid' => $promoted,
	  			'courseid' => $courseid,
	  			'sectionlist' => $sectionlist,
	  			'subjlist' => $subjlist,
	  			'units' => $units,
	  			'grantee' => $student->grantee,
	  			'mol' => $student->mol,
	  			'studclass' => $student->studclass,
	  			'studstat' => $studstat,
	  			'studstatdate' => $studstatdate,
	  			'studstatid' => $studstatid,
	  			'sectionname' => $student->sectionname
	  		);


	  		$glvl='';
	  		echo json_encode($data);

	  	}
	  }

	  public function enrollstud(Request $request)
	  {

	  	if($request->ajax())
	  	{
	  		$studid = $request->get('studid');
	  		$sid = $request->get('sid');
	  		$glevel = $request->get('glevel');
	  		$section = $request->get('section');
	  		$sy = $request->get('sy');
	  		$studstatus = 1; //$request->get('studstatus');
	  		$courseid = $request->get('courseid');
	  		$sectionname = $request->get('sectionname');
	  		$grantee = $request->get('grantee');
	  		$mol = $request->get('mol');
	  		$studclass = $request->get('studclass');

	  		$units = 0;
	  		$tuitionamount = 0;

			if($glevel == 14 || $glevel == 15)
			{	  		
		  		$strandid = $request->get('strandid');
		  		$blockid = $request->get('blockid');
		  		// $semid = $request->get('semid');

		  	}

	  		$sec = db::table('sections')
	  				->where('id', $section)
	  				->first();

	  		if($sec)
	  		{
	  			$teacherid = $sec->teacherid;
	  		}
	  		else
	  		{
	  			
	  		}

	  		$dateEnrolled = RegistrarModel::getServerDateTime();
	  		$semid = RegistrarModel::getSemID();

	  		

	  		// return date('n-j-Y', strtotime($dateEnrolled));

	  		if($glevel == 14 || $glevel == 15)
	  		{

	  			$enrollstud = db::table('sh_enrolledstud')
	  					->where('studid', $studid)
	  					->where('syid', $sy)
	  					->where('semid', $semid)
	  					->where('deleted', 0)
	  					->get();
	  			
	  			if(count($enrollstud) > 0)
	  			{
	  				$enrollid = $enrollstud[0]->id;
	  				$updEnrollStud = db::table('sh_enrolledstud')
	  						->where('id', $enrollstud[0]->id)
	  						->update([
			  					'syid' => $sy,
			  					'levelid' => $glevel,
			  					'sectionid' => $section,
			  					'strandid' => $strandid,
			  					'blockid' => $blockid,
			  					'semid' => $semid,
			  					'teacherid' => $teacherid,
			  					'studstatus' => 1,
			  					'dateenrolled' => $dateEnrolled,
			  					'updatedby' => auth()->user()->id,
			  					'updateddatetime' => RegistrarModel::getServerDateTime()
	  						]);
	  			}
	  			else
	  			{
			  		$enrollid = db::table('sh_enrolledstud')
			  				->insertGetId([
			  					'studid' => $studid,
			  					'syid' => $sy,
			  					'levelid' => $glevel,
			  					'sectionid' => $section,
			  					'strandid' => $strandid,
			  					'blockid' => $blockid,
			  					'semid' => $semid,
			  					'teacherid' => $teacherid,
			  					'studstatus' => $studstatus,
			  					'dateenrolled' => $dateEnrolled,
			  					'deleted' => 0,
			  					'createdby' => auth()->user()->id,
			  					'createddatetime' => RegistrarModel::getServerDateTime()
			  				]);
				}


		  			$updStudInfo = db::table('studinfo')
		  				->where('id', $studid)
		  				->update([
		  					'studstatus' => $studstatus,
		  					'sectionid' => $section,
		  					'levelid' => $glevel,
		  					'sectionname' => $sec->sectionname,
		  					'strandid' => $strandid,
		  					'blockid' => $blockid,
		  					'semid' => $semid,
		  					'picurl' => 'storage/STUDENT/' .$sid. '.jpg',
		  					'preEnrolled' => 0,
		  					'grantee' => $grantee,
		  					'mol' => $mol,
		  					'studclass' => $studclass
		  				]);
			}
			elseif($glevel >= 17 && $glevel <=20)
			{
				$enrollstud = db::table('college_enrolledstud')
  					->where('studid', $studid)
  					->where('syid', $sy)
  					->where('deleted', 0)
  					->get();

	  			if(count($enrollstud) > 0)
	  			{
	  				$enrollid = $enrollstud[0]->id;
	  				$updEnrollStud = db::table('college_enrolledstud')
  						->where('id', $enrollid)
  						->update([
		  					'syid' => $sy,
		  					'semid' =>$semid,
		  					'yearLevel' => $glevel,
		  					'sectionID' => $section,
		  					'courseid' => $courseid,
		  					'studstatus' => 1,
		  					'date_enrolled' => $dateEnrolled,
		  					'deleted' => 0,
		  					'updatedby' => auth()->user()->id,
		  					'updateddatetime' => RegistrarModel::getServerDateTime()
  						]);
	  			}
	  			else
	  			{

					$enrollid = db::table('college_enrolledstud')
		  				->insertGetId([
		  					'studid' => $studid,
		  					'syid' => $sy,
		  					'semid' =>$semid,
		  					'yearLevel' => $glevel,
		  					'sectionID' => $section,
		  					'courseid' => $courseid,
		  					'studstatus' => $studstatus,
		  					'date_enrolled' => $dateEnrolled,
		  					'deleted' => 0,
		  					'createdby' => auth()->user()->id,
		  					'createddatetime' => RegistrarModel::getServerDateTime()
		  				]);
			  	}

			  	$updStudInfo = db::table('studinfo')
	  				->where('id', $studid)
	  				->update([
	  					'studstatus' => $studstatus,
	  					'sectionid' => $section,
	  					'levelid' => $glevel,
	  					'sectionname' => $sectionname,
	  					'courseid' => $courseid,
	  					'semid' => $semid,
	  					'picurl' => 'storage/STUDENT/' .$sid. '.jpg',
	  					'preEnrolled' => 0
	  				]);
			}
			else
			{

				$enrollstud = db::table('enrolledstud')
  					->where('studid', $studid)
  					->where('syid', $sy)
  					->where('deleted', 0)
  					->get();

  				if(count($enrollstud) > 0)
	  			{
	  				$enrollid = $enrollstud[0]->id;
	  				$updEnrollStud = db::table('enrolledstud')
  						->where('id', $enrollid)
  						->update([
		  					'syid' => $sy,
		  					'levelid' => $glevel,
		  					'sectionid' => $section,
		  					'teacherid' => $teacherid,
		  					'studstatus' => 1,
		  					'dateenrolled' => $dateEnrolled,
		  					'updatedby' => auth()->user()->id,
		  					'updateddatetime' => RegistrarModel::getServerDateTime()
  						]);
	  			}
	  			else
	  			{

					$enrollid = db::table('enrolledstud')
		  				->insertGetId([
		  					'studid' => $studid,
		  					'syid' => $sy,
		  					'levelid' => $glevel,
		  					'sectionid' => $section,
		  					'teacherid' => $teacherid,
		  					'studstatus' => $studstatus,
		  					'dateenrolled' => $dateEnrolled,
		  					'createdby' => auth()->user()->id
		  				]);
			  	}


	  			$updStudInfo = db::table('studinfo')
	  				->where('id', $studid)
	  				->update([
	  					'studstatus' => $studstatus,
	  					'sectionid' => $section,
	  					'levelid' => $glevel,
	  					'sectionname' => $sec->sectionname,
	  					'picurl' => 'storage/STUDENT/' .$sid. '.jpg',
	  					'preEnrolled' => 0,
	  					'grantee' => $grantee,
	  					'mol' => $mol,
	  					'studclass' => $studclass
	  				]);
			}

			$stud = db::table('studinfo')
  				->where('id', $studid)
  				->first();


			if($glevel == 14 || $glevel == 15)
			{

		  		$tuition = db::table('tuitionheader')
		  			->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'grantee', 'levelid', 'grantee.description', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid', 'semid')
		  			->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')	
		  			->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
		  			->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
		  			->where('levelid', $glevel)
		  			->where('syid', $sy)
		  			->where('semid', $semid)
		  			->where('grantee', $stud->grantee)
		  			->where('strandid', $strandid)
		  			->where('tuitionheader.deleted', 0)
		  			->where('tuitiondetail.deleted', 0)
		  			->get();

		  		if(count($tuition) == 0)
		  		{
					$tuition = db::table('tuitionheader')
			  			->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'syid', 'levelid', 'grantee.description', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid', 'semid')
			  			->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')	
			  			->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
			  			->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
			  			->where('levelid', $glevel)
			  			->where('syid', $sy)
			  			->where('semid', $semid)
			  			->where('grantee', $stud->grantee)
			  			->where('tuitionheader.deleted', 0)
			  			->where('tuitiondetail.deleted', 0)
			  			->get();		  			

			  		if(count($tuition) == 0)
			  		{
			  			$tuition = db::table('tuitionheader')
				  			->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'syid', 'levelid', 'grantee.description', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid', 'semid')
				  			->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')	
				  			->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
				  			->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
				  			->where('levelid', $glevel)
				  			->where('syid', $sy)
				  			->where('semid', $semid)
				  			->where('tuitionheader.deleted', 0)
				  			->where('tuitiondetail.deleted', 0)
				  			->get();
			  		}
		  		}

		  	}
		  	elseif($glevel >= 17 && $glevel <=20)
		  	{
		  		$tuition = db::table('tuitionheader')
		  			->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'syid', 'levelid', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid', 'semid', 'istuition')
		  			->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')	
		  			->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
		  			->where('levelid', $glevel)
		  			->where('courseid', $courseid)
		  			->where('syid', $sy)
		  			->where('semid', $semid)
		  			->where('tuitionheader.deleted', 0)
		  			->where('tuitiondetail.deleted', 0)
		  			->get();

		  		if(count($tuition) == 0)
		  		{
		  			$tuition = db::table('tuitionheader')
			  			->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'syid', 'levelid', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid', 'semid', 'istuition')
			  			->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')	
			  			->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
			  			->where('levelid', $glevel)
			  			->where('syid', $sy)
			  			->where('semid', $semid)
			  			->where('tuitionheader.deleted', 0)
			  			->where('tuitiondetail.deleted', 0)
			  			->get();		
		  		}

		  		$totalunits = db::select('
	  				SELECT SUM(lecunits) + SUM(labunits) AS totalunits
					FROM college_studsched
					INNER JOIN college_classsched ON college_studsched.`schedid` = college_classsched.`id`
					INNER JOIN college_prospectus ON college_classsched.`subjectID` = college_prospectus.`id`
					WHERE college_studsched.`studid` = ? and college_studsched.`deleted` = 0	
	  			', [$studid]);
		  			


		  		$units = $totalunits[0]->totalunits;

		  	}
		  	else
		  	{
		  		$tuition = db::table('tuitionheader')
		  			->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'syid', 'levelid', 'grantee.description', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid')
		  			->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')	
		  			->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
		  			->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
		  			->where('levelid', $glevel)
		  			->where('syid', $sy)
		  			->where('grantee', $stud->grantee)
		  			->where('tuitionheader.deleted', 0)
		  			->where('tuitiondetail.deleted', 0)
		  			->get();	
		  			// return $tuition;
		  		if(count($tuition) == 0)
		  		{
		  			$tuition = db::table('tuitionheader')
		  			->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'syid', 'levelid', 'grantee.description', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid')
		  			->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')	
		  			->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
			  		->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
		  			->where('levelid', $glevel)
		  			->where('syid', $sy)
		  			->where('tuitionheader.deleted', 0)
		  			->where('tuitiondetail.deleted', 0)
		  			->get();	
		  		}
		  	}
		  	// return $tuition;

	  		foreach($tuition as $tui)
	  		{
	  			if($glevel >= 17 && $glevel <=20)
	  			{
	  				if($tui->istuition == 1)
	  				{
	  					// echo $tui->amount . ' * ' . $units;
	  					$tuitionamount = $tui->amount * $units;
	  				}
	  				else
	  				{
	  					$tuitionamount = $tui->amount;
	  				}
	  			}
	  			else
	  			{
	  				$tuitionamount = $tui->amount;
	  			}


	  			$sLedger = db::table('studledger')
  					->insert([
  						'studid' => $studid,
  						'enrollid' => $enrollid,
  						'syid' => $sy,
  						'semid' => $semid,
  						'classid' => $tui->classid,
  						'particulars' => $tui->particulars,
  						'amount' => $tuitionamount,
  						'pschemeid' => $tui->pschemeid,
  						'deleted' => 0,
  						'createddatetime' => RegistrarModel::getServerDateTime()
  					]);


  					//studledger Itemized

  				$tuitionitems = db::table('tuitionitems')
  					->where('tuitiondetailid', $tui->tuitiondetailid)
  					->where('deleted', 0)
  					->get();

  				foreach($tuitionitems as $tItems)
  				{
  					$checkitemized = db::table('studledgeritemized')
  						->where('studid', $studid)
  						->where('tuitionitemid', $tItems->id)
  						->where('deleted', 0)
  						->count();

  					if($checkitemized == 0)
  					{
	  					db::table('studledgeritemized')
	  						->insert([
	  							'studid' => $studid,
	  							'semid' => $semid,
	  							'syid' => $sy,
	  							'tuitiondetailid' => $tui->tuitiondetailid,
	  							'classificationid' => $tui->classid,
	  							'tuitionitemid' => $tItems->id,
	  							'itemid' => $tItems->itemid,
	  							'itemamount' => $tItems->amount,
	  							'createdby' => auth()->user()->id,
	  							'createddatetime' => RegistrarModel::getServerDateTime(),
	  							'deleted' => 0
	  						]);
  					}
  				}


	  			$paymentsetup = db::table('paymentsetup')
  					->select('paymentsetup.id', 'paymentdesc', 'paymentsetup.noofpayment', 'paymentno', 'duedate', 'payopt', 'percentamount')
  					->leftjoin('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
  					->where('paymentsetup.id', $tui->pschemeid)
  					->where('paymentsetupdetail.deleted', 0)
  					->get();
	  			
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

		  			// echo ' divPay: ' . $divPay;
		  			$paycount = 0;
		  			$paytAmount = 0;
		  			$paydisbalance = 0;

		  			

		  			foreach($paymentsetup as $pay)
		  			{
		  				$paycount += 1;
		  				$paytAmount += $divPay;

		  				if($paycount != $paymentno)
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
		  							'amount' => $divPay,
		  							'balance' => $divPay,
		  							'classid' => $tui->classid
		  						]);
		  				}
		  				else
		  				{
		  					// echo ' payAmount: '. $paytAmount . ' <= ' . $tuitionamount . '; ';
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
			  							'enrollid' => $enrollid,
			  							'syid' => $sy,
			  							'semid' => $semid,
			  							'tuitiondetailid' => $tui->tuitiondetailid,
			  							'particulars' => $tui->particulars,
			  							'duedate' => $pay->duedate,
			  							'paymentno' => $pay->paymentno,
			  							'amount' => $divPay,
			  							'balance' => $divPay,
			  							'classid' => $tui->classid
			  						]);

		  					}
		  					else
		  					{
		  						$paydisbalance = $paytAmount - $tuitionamount;
		  						$paydisbalance = number_format($paydisbalance, 2, '.', '');


		  						// $divPay = number_format($divPay - $paydisbalance);
		  						$divPay -= $paydisbalance;
		  						// echo ' paydisbalance: ' . $paydisbalance;
		  						// echo ' -divPay: '. $divPay;

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
		  							'amount' => $divPay,
		  							'balance' => $divPay,
		  							'classid' => $tui->classid
		  						]);
		  					}
		  				}
		  			}
		  		}
		  		else
		  		{
		  			$paycount = 0;
		  			$pAmount = 0;
		  			$curAmount = $tui->amount;

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
			  							'balance' => $pAmount,
			  							'classid' => $tui->classid
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
			  							'balance' => $curAmount,
			  							'classid' => $tui->classid
			  						]);	
		  						$curAmount = 0;
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

	  		if($glevel == 14 || $glevel == 15)
			{
				$getdp = db::table('chrngtransdetail')				
					->select('studid', 'syid', 'semid', 'itemkind', 'payschedid', 'isdp', 'chrngtransdetail.classid', 'chrngtransdetail.amount')
					->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
					->join('items', 'chrngtransdetail.payschedid', '=', 'items.id')
					->where('studid', $studid)
					->where('syid', RegistrarModel::getSYID())
					->where('semid', RegistrarModel::getSemID())
					->where('itemkind', 1)
					->where('isdp', 1)
					->where('chrngtrans.cancelled', 0)
					->get();

				if(count($getdp) > 0)
				{
					foreach($getdp as $dp)
					{
						$dpBal = $dp->amount;

						// echo '(' . $dpBal . ')';



						$balforward = db::table('balforwardsetup')
							->first();


						$getpaySched = db::table('studpayscheddetail')
								->where('studid', $studid)
								->where('syid', RegistrarModel::getSYID())
								->where('semid', RegistrarModel::getSemID())
								->where('classid', $balforward->classid)
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
												'updateddatetime' => RegistrarModel::getServerDateTime(),
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
												'updateddatetime' => RegistrarModel::getServerDateTime(),
												'updatedby' => auth()->user()->id
											]);
										$dpBal = 0;

									}
								}
							}
						}



						$getpaySched = db::table('studpayscheddetail')
							->where('studid', $studid)
							->where('syid', RegistrarModel::getSYID())
							->where('semid', RegistrarModel::getSemID())
							->where('classid', $dp->classid)
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
								', [$studid, RegistrarModel::getSYID(), RegistrarModel::getSemID()]);

								foreach($gpaydetail as $detail)
								{
									$paysched = db::table('studpayscheddetail')
											->where('classid', $detail->classid)
											->where('studid', $studid)
											->where('syid', RegistrarModel::getSYID())
											->where('semid', RegistrarModel::getSemID())
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
														'updateddatetime' => RegistrarModel::getServerDateTime(),
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
							//ELSE
							$getpaySched = db::table('studpayscheddetail')
								->where('studid', $studid)
								->where('syid', RegistrarModel::getSYID())
								->where('semid', RegistrarModel::getSemID())
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
									', [$studid, RegistrarModel::getSYID(), RegistrarModel::getSemID()]);

									foreach($gpaydetail as $detail)
									{
										$paysched = db::table('studpayscheddetail')
												->where('classid', $detail->classid)
												->where('studid', $studid)
												->where('syid', RegistrarModel::getSYID())
												->where('semid', RegistrarModel::getSemID())
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
															'updateddatetime' => RegistrarModel::getServerDateTime(),
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
			elseif($glevel >= 17 && $glevel <=20)
			{
				$getdp = db::table('chrngtransdetail')				
					->select('studid', 'syid', 'semid', 'itemkind', 'payschedid', 'isdp', 'chrngtransdetail.classid', 'chrngtransdetail.amount')
					->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
					->join('items', 'chrngtransdetail.payschedid', '=', 'items.id')
					->where('studid', $studid)
					->where('syid', RegistrarModel::getSYID())
					->where('semid', RegistrarModel::getSemID())
					->where('itemkind', 1)
					->where('isdp', 1)
					->where('chrngtrans.cancelled', 0)
					->get();
				if(count($getdp) > 0)
				{
					foreach($getdp as $dp)
					{
						$dpBal = $dp->amount;

						// echo '(' . $dpBal . ')';



						$balforward = db::table('balforwardsetup')
							->first();


						$getpaySched = db::table('studpayscheddetail')
								->where('studid', $studid)
								->where('syid', RegistrarModel::getSYID())
								->where('semid', RegistrarModel::getSemID())
								->where('classid', $balforward->classid)
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
												'updateddatetime' => RegistrarModel::getServerDateTime(),
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
												'updateddatetime' => RegistrarModel::getServerDateTime(),
												'updatedby' => auth()->user()->id
											]);
									}
								}
							}
						}



						$getpaySched = db::table('studpayscheddetail')
							->where('studid', $studid)
							->where('syid', RegistrarModel::getSYID())
							->where('semid', RegistrarModel::getSemID())
							->where('classid', $dp->classid)
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
								', [$studid, RegistrarModel::getSYID(), RegistrarModel::getSemID()]);

								foreach($gpaydetail as $detail)
								{
									$paysched = db::table('studpayscheddetail')
											->where('classid', $detail->classid)
											->where('studid', $studid)
											->where('syid', RegistrarModel::getSYID())
											->where('semid', RegistrarModel::getSemID())
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
														'updateddatetime' => RegistrarModel::getServerDateTime(),
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
			else //PRESCHOOL - GRADE 10
			{
				$getdp = db::table('chrngtransdetail')				
						->select('studid', 'syid', 'semid', 'itemkind', 'payschedid', 'isdp', 'chrngtransdetail.classid', 'chrngtransdetail.amount')
						->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
						->join('items', 'chrngtransdetail.payschedid', '=', 'items.id')
						->where('studid', $studid)
						->where('syid', RegistrarModel::getSYID())
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
							->where('syid', RegistrarModel::getSYID())
							// ->where('semid', RegistrarModel::getSemID())
							->where('classid', $balforward->classid)
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
												'updateddatetime' => RegistrarModel::getServerDateTime(),
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
												'updateddatetime' => RegistrarModel::getServerDateTime(),
												'updatedby' => auth()->user()->id
											]);
										$dpBal = 0;
									}
								}
							}
						}


						$getpaySched = db::table('studpayscheddetail')
							->where('studid', $studid)
							->where('syid', RegistrarModel::getSYID())
							->where('classid', $dp->classid)
							->get();

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
												'updateddatetime' => RegistrarModel::getServerDateTime(),
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
									WHERE studid = ?  AND syid = ? AND deleted = 0 and balance > 0
									GROUP BY classid
								', [$studid, RegistrarModel::getSYID()]);

								foreach($gpaydetail as $detail)
								{
									$paysched = db::table('studpayscheddetail')
											->where('classid', $detail->classid)
											->where('studid', $studid)
											->where('syid', RegistrarModel::getSYID())
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
														'updateddatetime' => RegistrarModel::getServerDateTime(),
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
														'updateddatetime' => RegistrarModel::getServerDateTime(),
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
							//ELSE
							$getpaySched = db::table('studpayscheddetail')
								->where('studid', $studid)
								->where('syid', RegistrarModel::getSYID())
								// ->where('semid', RegistrarModel::getSemID())
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
									', [$studid, RegistrarModel::getSYID(), RegistrarModel::getSemID()]);

									foreach($gpaydetail as $detail)
									{
										$paysched = db::table('studpayscheddetail')
												->where('classid', $detail->classid)
												->where('studid', $studid)
												->where('syid', RegistrarModel::getSYID())
												->where('semid', RegistrarModel::getSemID())
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
															'updateddatetime' => RegistrarModel::getServerDateTime(),
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


	  		$schedgroup = db::select('SELECT duedate, paymentno, particulars, classid, SUM(amount) AS amount FROM studpayscheddetail where studid = ? and syid = ? group by month(duedate), duedate, paymentno order by duedate', [$studid, $sy]);
	  		
	  		// return $schedgroup;

  			foreach($schedgroup as $sched)
  			{
  				if(empty($sched->duedate))
  				{
  					$paysched = db::table('studpaysched')
  						->insert([
  							'enrollid' => $enrollid,
  							'studid' => $studid,
  							'syid' =>$sy,
  							'classid' => $sched->classid,
  							'semid' => $semid,
  							'paymentno' => $sched->paymentno,
  							'particulars' => $sched->particulars,
  							'duedate' => $sched->duedate,
  							'amountdue' => $sched->amount,
  							'balance' => $sched->amount
  						]);		
  				}
  				else
  				{
  					$paysched = db::table('studpaysched')
  						->insert([
  							'enrollid' => $enrollid,
  							'studid' => $studid,
  							'syid' =>$sy,
  							'semid' => $semid,
  							'classid' => $sched->classid,
  							'paymentno' => $sched->paymentno,
  							'particulars' => 'TUITION/BOOK FEE - '. strtoupper(date('F', strtotime($sched->duedate))),
  							'duedate' => $sched->duedate,
  							'amountdue' => $sched->amount,
  							'balance' => $sched->amount
  						]);	
  				}
  				
  			}





	  		//create users

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
	  		
	  		$ucheck = db::table('users')
	  				->where('id', $studinfo->userid)
	  				->get();

	  		if(count($ucheck) == 0)
	  		{	
		  		$studuser = db::table('users')
		  				->insertGetId([
		  					'name' => $sname,
		  					'email' => 'S'.$sid,
		  					'type' => 7,
		  					'password' => Hash::make('123456')
		  				]);


		  		$putUserid = db::table('studinfo')
		  				->where('id', $studid)
		  				->update([
		  					'userid' => $studuser,
		  					'updateddatetime' => RegistrarModel::getServerDateTime(),
		  					'updatedby' => auth()->user()->id
		  				]);


		  		if(substr($contactno, 0,1)=='0')
		  		{
		  			$contactno = '+63' . substr($contactno, 1);
		  		}

		  		$smsStud = db::table('smsbunker')
		 					->insert([
		 						'message' => $studinfo->firstname .' you are already enrolled. Portal Credential - Username:S'.$sid . ' Default Password: 123456',
		 						'receiver' => $contactno,
		 						'smsstatus' => 0
		 					]);

		  		$parentuser = db::table('users')
		  				->insert([
		  					'name' => $pname,
		  					'email' => 'P'.$sid,
		  					'type' => 9,
		  					'password' => Hash::make('123456')
		  				]);

		  		if(substr($substr, 0,1)=='0')
		  		{
		  			$substr = '+63' . substr($substr, 1);
		  		}

		  		$smsParent = db::table('smsbunker')
					->insert([
						'message' => 'Your student '. $studinfo->firstname .' is already enrolled. Portal Credential - Username:P'.$sid . ' Default Password: 123456',
						'receiver' => $substr,
						'smsstatus' => 0
					]);
		 	}

		 	//studledgeritemized

		 // 	$chrngtrans = db::table('chrngtrans')
			// 	->select('chrngtransid', 'chrngtransdetail.id as chrngtransdetailid', 'ornum', 'classid', 'chrngtransdetail.amount', 'studid', 'syid', 'semid')
			// 	->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
			// 	->where('cancelled', 0)
			// 	->where('studid', $studid)
			// 	->get();

			// foreach($chrngtrans as $trans)
			// {
			// 	$transamount = $trans->amount;

			// 	$balforwardsetup = db::table('balforwardsetup')
			// 		->first();

			// 	$ledgeritemized = db::table('studledgeritemized')
			// 		->where('studid', $trans->studid)
			// 		->where('syid', $trans->syid)
			// 		->where('semid', $trans->semid)
			// 		->where('classificationid', $balforwardsetup->classid)
			// 		->first();

			// 	if($ledgeritemized)
			// 	{
			// 		$diff = $ledgeritemized->itemamount - $ledgeritemized->totalamount;
			// 		if($transamount > $diff)
			// 		{
			// 			db::table('studledgeritemized')
			// 				->where('id', $ledgeritemized->id)
			// 				->update([
			// 					'totalamount' => $ledgeritemized->totalamount + $diff,
			// 					'updatedby' => auth()->user()->id,
			// 					'updateddatetime' => RegistrarModel::getServerDateTime()
			// 				]);

			// 			$transamount -= $diff;

			// 			db::table('chrngtransitems')
			// 				->insert([
			// 					'chrngtransid' => $trans->chrngtransid,
			// 					'chrngtransdetailid' => $trans->chrngtransdetailid,
			// 					'ornum' => $trans->ornum,
			// 					'itemid' => $ledgeritemized->itemid,
			// 					'classid' => $ledgeritemized->classificationid,
			// 					'amount' => $diff,
			// 					'studid' => $trans->studid,
			// 					'syid' => $trans->syid,
			// 					'semid' => $trans->semid,
			// 					'createdby' => auth()->user()->id,
			// 					'createddatetime' => RegistrarModel::getServerDateTime()
			// 				]);
			// 		}
			// 		else
			// 		{
			// 			db::table('studledgeritemized')
			// 				->where('id', $ledgeritemized->id)
			// 				->update([
			// 					'totalamount' => $ledgeritemized->totalamount + $transamount,
			// 					'updatedby' => auth()->user()->id,
			// 					'updateddatetime' => RegistrarModel::getServerDateTime()
			// 				]);							

			// 			$transamount = 0;

			// 			db::table('chrngtransitems')
			// 				->insert([
			// 					'chrngtransid' => $trans->chrngtransid,
			// 					'chrngtransdetailid' => $trans->chrngtransdetailid,
			// 					'ornum' => $trans->ornum,
			// 					'itemid' => $ledgeritemized->itemid,
			// 					'classid' => $ledgeritemized ->classificationid,
			// 					'amount' => $transamount,
			// 					'studid' => $trans->studid,
			// 					'syid' => $trans->syid,
			// 					'semid' => $trans->semid,
			// 					'createdby' => auth()->user()->id,
			// 					'createddatetime' => RegistrarModel::getServerDateTime()
			// 				]);
			// 		}
			// 	}

			// 	if($transamount > 0)
			// 	{
			// 		$ledgeritemized = db::table('studledgeritemized')
			// 			->where('studid', $trans->studid)
			// 			->where('syid', $trans->syid)
			// 			->where('semid', $trans->semid)
			// 			->where('classificationid', $trans->classid)
			// 			->get();

			// 		foreach($ledgeritemized as $item)
			// 		{
			// 			$checkitem = db::table('studledgeritemized')
			// 				->where('id', $item->id)
			// 				->first();


			// 			if($checkitem)
			// 			{
			// 				if($checkitem->totalamount < $item->itemamount)
			// 				{
			// 					$_getamount = $item->itemamount - $item->totalamount;

			// 					if($transamount >= $_getamount)
			// 					{
			// 						db::table('studledgeritemized')
			// 							->where('id', $item->id)
			// 							->update([
			// 								'totalamount' => $item->totalamount + $_getamount,
			// 								'updatedby' => auth()->user()->id,
			// 								'updateddatetime' => RegistrarModel::getServerDateTime()
			// 							]);


			// 						db::table('chrngtransitems')
			// 							->insert([
			// 								'chrngtransid' => $trans->chrngtransid,
			// 								'chrngtransdetailid' => $trans->chrngtransdetailid,
			// 								'ornum' => $trans->ornum,
			// 								'itemid' => $item->itemid,
			// 								'classid' => $item->classificationid,
			// 								'amount' => $_getamount,
			// 								'studid' => $item->studid,
			// 								'syid' => $trans->syid,
			// 								'semid' => $trans->semid,
			// 								'createdby' => auth()->user()->id,
			// 								'createddatetime' => RegistrarModel::getServerDateTime()
			// 							]);


			// 						$transamount -= $_getamount;

			// 					}
			// 					else
			// 					{
			// 						db::table('studledgeritemized')
			// 							->where('id', $item->id)
			// 							->update([
			// 								'totalamount' => $item->totalamount + $transamount,
			// 								'updatedby' => auth()->user()->id,
			// 								'updateddatetime' => RegistrarModel::getServerDateTime()
			// 							]);

			// 						db::table('chrngtransitems')
			// 							->insert([
			// 								'chrngtransid' => $trans->chrngtransid,
			// 								'chrngtransdetailid' => $trans->chrngtransdetailid,
			// 								'ornum' => $trans->ornum,
			// 								'itemid' => $item->itemid,
			// 								'classid' => $item->classificationid,
			// 								'amount' => $transamount,
			// 								'studid' => $item->studid,
			// 								'syid' => $trans->syid,
			// 								'semid' => $trans->semid,
			// 								'createdby' => auth()->user()->id,
			// 								'createddatetime' => RegistrarModel::getServerDateTime()
			// 							]);

			// 						$transamount = 0;	
			// 					}

			// 					if($transamount > 0)
			// 					{
			// 						db::table('studledgeritemized')
			// 							->where('id', $item->id)
			// 							->update([
			// 								'excessamount' => $transamount,
			// 								'updatedby' => auth()->user()->id,
			// 								'updateddatetime' => RegistrarModel::getServerDateTime()
			// 							]);
			// 					}
			// 				}
			// 			}
			// 		}
			// 	}
			// }

	  	}	  	

	  }

	 	public function viewEnrollment(Request $request)
	 	{
	 		if($request->ajax())
	 		{
	 			$studid = $request->get('studid');
	 			$glevel = $request->get('glevel');
	 			$syid = RegistrarModel::getSYID();
	 			$semid = RegistrarModel::getSemID();

	 			$stud = db::table('studinfo')
	 					->where('id', $studid)
	 					->first();

	 			$lrn = $stud->lrn;
	 			$sid = $stud->sid;
	 			$name = $stud->lastname . ' ' . $stud->firstname . ' ' . $stud->middlename;


	 			if($glevel == 14 || $glevel == 15)
	 			{
	 				$enrollment = db::table('sh_enrolledstud')
	 						->where('studid', $studid)
	 						->where('syid', $syid)
	 						->where('semid', $semid)
	 						->first();
	 			}
	 			else
	 			{
	 				$enrollment = db::table('enrolledstud')
	 						->where('studid', $studid)
	 						->where('syid', $syid)
	 						->first();	
	 			}

	 			$dateenrolled = $enrollment->dateenrolled;

	 			$data = array(
	 				'lrn' => $lrn,
	 				'sid' => $sid,
	 				'name' =>strtoupper($name),
	 				'dateenrolled' => $dateenrolled
	 			);

	 			echo json_encode($data);
	 		}
	 	}

	 	public function addReligion(Request $request)
	 	{
	 		if($request->ajax())
	 		{
	 			$religion = $request->get('religion');

	 			$addRel = db::table('religion')
	 					->insertGetId([
	 						'religionname' => $religion,
	 						'deleted' => 0
	 					]);

	 			$getReligion = db::table('religion')
	 					->where('deleted', 0)
	 					->get();

	 			$output = '';

	 			foreach($getReligion as $rel)
	 			{

	 				if($rel->id == $addRel)
	 				{
		 				$output .= '
		 						<option value="'.$rel->id.'" selected="">'.$rel->religionname.'</option>
		 				';
		 			}
		 			else
		 			{
		 				$output .= '
		 						<option value="'.$rel->id.'">'.$rel->religionname.'</option>
		 				';
		 			}
	 			}

	 			$data = array(
	 				'output' => $output
	 			);

	 			echo json_encode($data);

	 		}
	 	}

	 	public function addMT(Request $request)
	 	{
	 		if($request->ajax())
	 		{
	 			$mt = $request->get('mt');
	 			$mtid = db::table('mothertongue')
	 					->insertGetId([
	 						'mtname' => $mt,
	 						'deleted' => 0
	 					]);

	 			$getmt = db::table('mothertongue')
	 					->where('deleted', 0)
	 					->get();

	 			$output = '';
	 			foreach($getmt as $gmt)
	 			{
	 				if($gmt->id == $mtid)
	 				{
	 					$output .= '
		 						<option value="'.$gmt->id.'" selected="">'.$gmt->mtname.'</option>
		 				';	
	 				}
	 				else
	 				{
	 					$output .= '
		 						<option value="'.$gmt->id.'">'.$gmt->mtname.'</option>
		 				';	
	 				}
	 			}

	 			$data = array(
	 				'output' => $output
	 			);

	 			echo json_encode($data);
	 		}
	 	}

	 	public function addEG(Request $request)
	 	{
	 		if($request->ajax())
	 		{
	 			$eg = $request->get('eg');

	 			$egid = db::table('ethnic')
	 					->insertGetId([
	 						'egname' => $eg,
	 						'deleted' => 0
	 					]);

	 			$geteg = db::table('ethnic')
	 					->where('deleted', 0)
	 					->get();

	 			$output = '';

	 			foreach($geteg as $e)
	 			{
	 				if($e->id == $egid)
	 				{
	 					$output .= '
		 						<option value="'.$e->id.'" selected="">'.$e->egname.'</option>
		 				';		
	 				}
	 				else
	 				{
	 					$output .= '
		 						<option value="'.$e->id.'">'.$e->egname.'</option>
		 				';			
	 				}
	 			}
	 			$data = array(
	 				'output' => $output
	 			);

	 			echo json_encode($data);
	 		}
	 	}

		public function viewtrack(Request $request)
		{
			return view('/enrollment/track');
		}

		public function searchTrack(Request $request)
		{
			if($request->ajax())
			{
				$query = $request->get('query');

				$tracks = db::table('sh_track')
						->where('deleted', 0)
						->where('trackname', 'like', '%'.$query.'%')
						->get();

				$output = '';
				foreach($tracks as $track)
				{
					$output .= '
						<tr>
							<td>'.$track->trackname.'</td>
							<td style="width:10px">
								<button class="btn btn-primary btn-sm btn-edit" data-id="'.$track->id.'"><i class="fas fa-edit"></i></button>
							</td>
							<td>
								<button class="btn btn-danger btn-sm btn-del" data-id="'.$track->id.'"><i class="fas fa-trash"></i></button></td>
							</td>
						</tr>

					';
				}

				$data = array(
					'output' => $output
				);

				echo json_encode($data);

			}
		}


		public function savetrack(Request $request)
		{
			if($request->ajax())
			{
				$trackname = $request->get('trackname');


				$savetrack = db::table('sh_track')
						->insert([
							'trackname' => $trackname,
							'deleted' => 0,
							'createdby' => auth()->user()->id
						]);
			}
		}

		public function edittrack(Request $request)
		{
			if($request->ajax())
			{
				$dataid = $request->get('dataid');

				$track = db::table('sh_track')
					->where('id', $dataid)
					->first();

				$trackname = $track->trackname;

				$data = array(
					'trackname' => $trackname
				);

				echo json_encode($data);
			}
		}

		public function updatetrack(Request $request)
		{
			if($request->ajax())
			{
				$dataid = $request->get('dataid');
				$trackname = $request->get('trackname');

				$updtrack = db::table('sh_track')
					->where('id', $dataid)
					->update([
						'trackname' => $trackname,
						'updatedby' => auth()->user()->id,
						'updateddatetime' => RegistrarModel::getServerDateTime()
					]);
			}
		}


		public function loadtrack(Request $request)
		{
			$loadtrack = RegistrarModel::loadtrack();
			
			$loadtrack[0]->trackname;
			
			$output = '<option></option>';

			foreach($loadtrack as $track)
			{
				$output .= '
					<option value="'.$track->id.'">'.$track->trackname.'</option>
				';
			}

			$data = array(
				'output' => $output
			);

			echo json_encode($data);
		}

		public function viewstrand()
		{
			return view('/enrollment/strand');
		}

		public function insertstrand(Request $request)
		{
			if($request->ajax())
			{
				$code = $request->get('code');
				$strandname = $request->get('strandname');
				$trackid = $request->get('trackid');
				$isactive = $request->get('isactive');

				$insertStrand = db::table('sh_strand')
						->insert([
							'strandname' => $strandname,
							'strandcode' => $code,
							'trackid' => $trackid,
							'active' => $isactive,
							'createdby' => auth()->user()->id
						]);
			}
		}

		public function searchstrand(Request $request)
		{
			if($request->ajax())
			{
				$query = $request->get('query');

				$searchstrand = db::table('sh_strand')
						->select('strandcode', 'strandname', 'trackname', 'sh_strand.active')
						->join('sh_track', 'sh_strand.trackid', '=', 'sh_track.id')
						->where('sh_strand.deleted', 0)
						->where('strandname', 'like', '%'.$query.'%')
						->orWhere('sh_strand.deleted', 0)
						->where('strandcode', 'like', '%'.$query.'%')
						->get();


				$output ='';

				foreach ($searchstrand as $strand) 
				{
					if($strand->active == 1)
					{
						$output .= '
							<tr>
								<td>'.strtoupper($strand->strandcode).'</td>
								<td>'.strtoupper($strand->strandname).'</td>
								<td>'.strtoupper($strand->trackname).'</td>
								<td><i class="fas fa-check"></i></td>
								<td  class="btn-group"><button class="btn btn-primary">Edit</button><button class="btn btn-danger">Delete</button></td>
							</tr>
						';
					}
					else
					{
						$output .= '
							<tr>
								<td>'.strtoupper($strand->strandcode).'</td>
								<td>'.strtoupper($strand->strandname).'</td>
								<td>'.strtoupper($strand->trackname).'</td>
								<td></td>
								<td><button class="btn btn-primary">Edit</button><button class="btn btn-danger prepend">Delete</button></td>
							</tr>
						';
					}
				}

				$data = array(
					'output' => $output
				);

				echo json_encode($data);
			}
		}

		public function loadstrand(Request $request)
		{
			$semesters = db::table('semester')
					->where('isactive', 1)
					->get();

			$sem = '';

			foreach($semesters as $semester)
			{
				$sem .= '
					<option value="'.$semester->id.'">'.$semester->semester.'</option>
				';
			}

			$strand = db::table('sh_strand')
					->where('deleted', 0)
					->where('active', 1)
					->get();


			$str = '<option></option>';

			foreach($strand as $s)
			{
				$str .= '
					<option value="'.$s->id.'">'.strtoupper($s->strandname).'</option>
				';
			}

			$data = array(
				'strand' => $str,
				'sem' => $sem
			);

			echo json_encode($data);
		}

		public function loadblock(Request $request)
		{
			if($request->ajax())
			{
				$strandid = $request->get('strandid');

				$getblock = db::table('sh_block')
						->where('strandid', $strandid)
						->where('deleted', 0)
						->get();

				$blk = '<option></option>';

				foreach($getblock as $block)
				{
					$blk .= '
						<option value="'.$block->id.'">'.$block->blockname.'</option>
					';
				}

				$data = array(
					'block' => $blk
				);

				echo json_encode($data);
			}
		}

		public function saveEnroll(Request $request)
		{
			if($request->ajax())
			{
				$studid = $request->get('studid');
				$levelid = $request->get('levelid');
				$sectionid = $request->get('sectionid');
				$syid = $request->get('syid');
				$enrollstatusid = $request->get('enrollstatusid');
				$strand = $request->get('strand');
				$block = $request->get('block');
				$semid = $request->get('semid');

				$grantee = $request->get('grantee');
				$mol = $request->get('mol');
				$studclass = $request->get('studclass');

				$studstatdate = '';

				if($levelid == 14 or $levelid == 15)
				{
					$estud = db::table('sh_enrolledstud')
							->where('studid', $studid)
							->where('syid', $syid)
							->get();
				
					if(count($estud) > 0)
					{
						$updEnroll = db::table('sh_enrolledstud')
							->where('studid', $studid)
							->where('syid', $syid)
							->update([
								'sectionid' => $sectionid,
								'syid' => $syid,
								'studstatus' => $enrollstatusid,
								'strandid' => $strand,
								'blockid' => $block,
								'semid' => $semid,
								'updatedby' => auth()->user()->id,
								'updateddatetime' => RegistrarModel::getServerDateTime()
							]);
					}
					else
					{
						$sEnroll = db::table('sh_enrolledstud')
								->insert([
									'studid' => $studid,
									'syid' => $syid,
									'levelid' => $levelid,
									'sectionid' => $sectionid,
									'studstatus' => 0,
									'strandid' => $strand,
									'blockid' => $block,
									'semid' => $semid,
									'deleted' => 0,
									'createdby' => auth()->user()->id,
									'createddatetime' => RegistrarModel::getServerDateTime()
								]);
					}
				}
				else
				{

					$estud = db::table('enrolledstud')
							->where('studid', $studid)
							->where('syid', $syid)
							->get();

					if(count($estud) > 0)
					{	
						$updEnroll = db::table('enrolledstud')
							->where('studid', $studid)
							->where('syid', $syid)
							->update([
								'sectionid' => $sectionid,
								'syid' => $syid,
								'studstatus' => $enrollstatusid,
								'updatedby' => auth()->user()->id,
								'updateddatetime' => RegistrarModel::getServerDateTime()
							]);
					}
					else
					{
						$sEnroll = db::table('enrolledstud')
								->insert([
									'studid' => $studid,
									'syid' => $syid,
									'levelid' => $levelid,
									'sectionid' => $sectionid,
									'studstatus' => 0,
									'deleted' => 0,
									'createdby' => auth()->user()->id,
									'createddatetime' => RegistrarModel::getServerDateTime()
								]);	
					}
				}

				$section = db::table('sections')
						->where('id', $sectionid)
						->first();

				$studinfo = db::table('studinfo')
					->where('id', $studid)
					->first();

				

				if($studinfo->studstatus != $enrollstatusid)
				{
					if($enrollstatusid != 1)
					{
						$studstatdate = RegistrarModel::getServerDateTime();
						$updStudInfo = db::table('studinfo')
						->where('id', $studid)
						->update([
							'levelid' => $levelid,
							'sectionid' => $sectionid,
							'studstatus' => $enrollstatusid,
							'sectionname' => $section->sectionname,
							'grantee' => $grantee,
							'mol' => $mol,
							'studclass' => $studclass,
							'studstatdate' => $studstatdate
						]);
					}
				}
				else
				{
					$updStudInfo = db::table('studinfo')
						->where('id', $studid)
						->update([
							'levelid' => $levelid,
							'sectionid' => $sectionid,
							'studstatus' => $enrollstatusid,
							'sectionname' => $section->sectionname,
							'grantee' => $grantee,
							'mol' => $mol,
							'studclass' => $studclass
						]);
				}
			}
		}

		public function spclass(Request $request)
		{
			return view('enrollment.spclass');
		}

		public function spsearch(Request $request)
		{
			if($request->ajax())
			{

				$sval = $request->get('sval');
				$levelid = $request->get('levelid');
				$syid = $request->get('syid');
				$semid = $request->get('semid');

				// return $sval;


				if($levelid == 0)
				{
					if($semid == 0)
					{
						$lists = db::table('gradesspclass')
								->select('studid', 'gradesspclass.levelid', 'gradesspclass.syid', 'gradesspclass.semid', 'sid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname', 'semester', 'sydesc')
								->join('studinfo', 'gradesspclass.studid', '=', 'studinfo.id')
								->join('gradelevel', 'gradesspclass.levelid', '=', 'gradelevel.id')
								->leftjoin('semester', 'gradesspclass.semid', '=', 'semester.id')
								->join('sy', 'gradesspclass.syid', '=', 'sy.id')
								->where('lastname', 'like', '%'. $sval .'%')
								->where('gradesspclass.syid', $syid)
								->where('gradesspclass.deleted', 0)
								->orWhere('sid', 'like', '%'. $sval .'%')
								->where('gradesspclass.deleted', 0)
								->groupBy('studid', 'syid')
								->orderBy('lastname', 'ASC')
								->orderBy('firstname', 'ASC')
								->get();
					}
					else
					{
						$lists = db::table('gradesspclass')
								->select('studid', 'gradesspclass.levelid', 'gradesspclass.syid', 'gradesspclass.semid', 'sid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname', 'semester', 'sydesc')
								->join('studinfo', 'gradesspclass.studid', '=', 'studinfo.id')
								->join('gradelevel', 'gradesspclass.levelid', '=', 'gradelevel.id')
								->leftjoin('semester', 'gradesspclass.semid', '=', 'semester.id')
								->join('sy', 'gradesspclass.syid', '=', 'sy.id')
								->where('lastname', 'like', '%'. $sval .'%')
								->where('gradesspclass.syid', $syid)
								->where('gradesspclass.semid', $semid)
								->where('gradesspclass.deleted', 0)
								->orWhere('sid', 'like', '%'. $sval .'%')
								->where('gradesspclass.deleted', 0)
								->where('gradesspclass.semid', $semid)
								->groupBy('studid', 'syid')
								->orderBy('lastname', 'ASC')
								->orderBy('firstname', 'ASC')
								->get();	
					}
				}
				else
				{
					if($semid == 0)
					{
						$lists = db::table('gradesspclass')
								->select('studid', 'gradesspclass.levelid', 'gradesspclass.syid', 'gradesspclass.semid', 'sid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname', 'semester', 'sydesc')
								->join('studinfo', 'gradesspclass.studid', '=', 'studinfo.id')
								->join('gradelevel', 'gradesspclass.levelid', '=', 'gradelevel.id')
								->leftjoin('semester', 'gradesspclass.semid', '=', 'semester.id')
								->join('sy', 'gradesspclass.syid', '=', 'sy.id')
								->where('lastname', 'like', '%'. $sval .'%')
								->where('gradesspclass.syid', $syid)
								->where('gradesspclass.deleted', 0)
								->where('gradesspclass.levelid', $levelid)
								->orWhere('sid', 'like', '%'. $sval .'%')
								->where('gradesspclass.syid', $syid)
								->where('gradesspclass.levelid', $levelid)
								->where('gradesspclass.deleted', 0)
								->groupBy('studid', 'syid')
								->orderBy('lastname', 'ASC')
								->orderBy('firstname', 'ASC')
								->get();	
					}
					else
					{
						$lists = db::table('gradesspclass')
								->select('studid', 'gradesspclass.levelid', 'gradesspclass.syid', 'gradesspclass.semid', 'sid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname', 'semester', 'sydesc')
								->join('studinfo', 'gradesspclass.studid', '=', 'studinfo.id')
								->join('gradelevel', 'gradesspclass.levelid', '=', 'gradelevel.id')
								->leftjoin('semester', 'gradesspclass.semid', '=', 'semester.id')
								->join('sy', 'gradesspclass.syid', '=', 'sy.id')
								->where('lastname', 'like', '%'. $sval .'%')
								->where('gradesspclass.syid', $syid)
								->where('gradesspclass.deleted', 0)
								->where('gradesspclass.levelid', $levelid)
								->where('gradesspclass.semid', $semid)
								->orWhere('sid', 'like', '%'. $sval .'%')
								->where('gradesspclass.syid', $syid)
								->where('gradesspclass.levelid', $levelid)
								->where('gradesspclass.semid', $semid)
								->where('gradesspclass.deleted', 0)
								->groupBy('studid', 'syid')
								->orderBy('lastname', 'ASC')
								->orderBy('firstname', 'ASC')
								->get();		
					}
				}

				$spList = '';

				foreach($lists as $list)
				{
					$studname = $list->lastname . ', ' . $list->firstname . ' ' . $list->middlename . ' ' . $list->suffix;

					$spList .= '
						<tr>
							<td>'.$list->sid.'</td>
							<td>'.$studname.'</td>
							<td>'.$list->levelname.'</td>
							<td>'.$list->semester.'</td>
							<td>'.$list->sydesc.'</td>
							<td class="text-right">
								<button class="btn btn-primary btn-flat btn-edit" s-id="'.$list->studid.'" l-id="'.$list->levelid.'" sy-id="'.$list->syid.'" sem-id="'.$list->semid.'" data-toggle="modal" data-target="#modal-spclass-edit">
									<i class="fas fa-edit"></i>
								</button>
							</td>
							<td>
								<button class="btn btn-danger btn-flat" style="margin-left:-1px;">
									<i class="fas fa-trash-alt"></i>
								</button>
							</td>
							
						</tr>
					';

				}

				$data = array(
					'spList' => $spList
				);

				echo json_encode($data);

			}
		}

		public function LoadLists()
		{
			$levelList = RegistrarModel::loadGradeLevel(0);

			$syList = RegistrarModel::loadSY(RegistrarModel::getSYID());

			$semList = RegistrarModel::loadSEM(RegistrarModel::getSemID(), 0);

			// return $semList;

			$data = array(
				'glevelList' => $levelList,
				'sylist' => $syList,
				'semlist' =>$semList
			);


			echo json_encode($data);
		}

		public function loadStud(Request $request)
		{
			if($request->ajax())
			{
				$studlist = db::table('studinfo')
						->where('deleted', 0)
						->orderBy('lastname', 'ASC')
						->orderBy('firstname', 'ASC')
						->get();

				$list = '';
				foreach($studlist as $stud)
				{
					$studname = strtoupper($stud->lastname . ', ' . $stud->firstname . ' ' . $stud->middlename . ' ' . $stud->suffix);
					$list .='
						<option value="'.$stud->id.'">'.$studname.'</option>
					';
				}

				$data = array(
					'studlist' => $list
				);
				echo json_encode($data);
			}
		}

		public function loadDetail(Request $request)
		{
			if($request->ajax())
			{
				$glevel = $request->get('glevel');

				$level = db::table('gradelevel')
						->where('id', $glevel)
						->get();

				if(count($level) > 0)
				{
					$subjlist = db::table('subjects')
							->where('acadprogid', $level[0]->acadprogid)
							->where('deleted', 0)
							->orderBy('subjdesc', 'ASC')
							->get();
				}

				$sList = '';

				foreach($subjlist as $subj)
				{
					$sList .= '
						<option value="'.$subj->id.'">'.$subj->subjdesc.'</option>
					';
				}


				$teacherList = db::table('teacher')
						->where('deleted', 0)
						->where('isactive', 1)
						->where('usertypeid', 1)
						->get();

				$tList = '';

				foreach($teacherList as $teacher)
				{
					$teachername = $teacher->lastname . ', ' . $teacher->firstname . ' ' . $teacher->middlename . ' ' . $teacher->suffix;

					$tList .= '
						<option value="'.$teacher->id.'">'.$teachername.'</option>
					';
				}

				$data = array(
					'students' => $sList,
					'teachers' => $tList
				);

				echo json_encode($data);
			}
		}

		public function appendDetail(Request $request)
		{
			$studid = $request->get('studid');
			$levelid = $request->get('levelid');
			$semid = $request->get('semid');
			$syid = $request->get('syid');
			$subjid = $request->get('subjid');
			$teacherid = $request->get('teacherid');


			if($levelid != 14 || $levelid != 15)
			{
				$semid = NULL;
			}


			$spclassid = db::table('gradesspclass')
					->insertGetId([
						'studid' => $studid,
						'subjid' => $subjid,
						'teacherid' => $teacherid,
						'levelid' => $levelid,
						'syid' => $syid,
						'semid' => $semid,
						'deleted' => 0,
						'createdby' => auth()->user()->id,
						'createddatetime' => RegistrarModel::getServerDateTime()
					]);

			if($semid == 0 || $semid == '')
			{
				$spclassDetail = db::table('gradesspclass')
						->select('gradesspclass.id', 'subjects.id as subjid', 'teacher.id as teacherid', 'subjdesc', 'lastname', 'firstname', 'middlename', 'suffix')
						->join('subjects', 'gradesspclass.subjid', '=', 'subjects.id')
						->join('teacher', 'gradesspclass.teacherid', '=', 'teacher.id')
						->where('gradesspclass.studid', $studid)
						->where('gradesspclass.syid', $syid)
						->where('gradesspclass.deleted', 0)
						->get();
			}
			else
			{
				$spclassDetail = db::table('gradesspclass')
						->select('gradesspclass.id', 'subjects.id as subjid', 'teacher.id as teacherid', 'subjdesc', 'lastname', 'firstname', 'middlename', 'suffix')
						->join('subjects', 'gradesspclass.subjid', '=', 'subjects.id')
						->join('teacher', 'gradesspclass.teacherid', '=', 'teacher.id')
						->where('gradesspclass.studid', $studid)
						->where('gradesspclass.syid', $syid)
						->where('gradesspclass.semid', $semid)
						->where('gradesspclass.deleted', 0)
						->get();	
			}

			$dTail = '';

			foreach($spclassDetail as $spclass)
			{
				$tname = $spclass->lastname . ', ' . $spclass->firstname . ' ' . $spclass->middlename . ' ' . $spclass->suffix;
				$dTail .= '
					<tr id="'.$spclass->id.'">
						<td class="subj" data-id="'.$spclass->subjid.'">'. $spclass->subjdesc .'</td>
						<td clas="tinfo" data-id="'.$spclass->teacherid.'">'.$tname.'</td>
						
					</tr>
				';	
			}

			$data = array(
				'detail' => $dTail
			);		

			echo json_encode($data);


		}

		public function updateDetail(Request $request)
		{
			if($request->ajax())
			{
				$dataid = $request->get('dataid');
				$studid = $request->get('studid');
				$syid = $request->get('syid');
				$semid = $request->get('semid');
				$subjid = $request->get('subjid');
				$teacherid = $request->get('teacherid');

				$spclassid = db::table('gradesspclass')
						->where('id', $dataid)
						->update([
							'subjid' => $subjid,
							'teacherid' => $teacherid
						]);

				if($semid == 0 || $semid == '')
				{
					$spclassDetail = db::table('gradesspclass')
							->select('gradesspclass.id', 'subjects.id as subjid', 'teacher.id as teacherid', 'subjdesc', 'lastname', 'firstname', 'middlename', 'suffix')
							->join('subjects', 'gradesspclass.subjid', '=', 'subjects.id')
							->join('teacher', 'gradesspclass.teacherid', '=', 'teacher.id')
							->where('studid', $studid)
							->where('syid', $syid)
							->get();
				}
				else
				{
					$spclassDetail = db::table('gradesspclass')
							->select('gradesspclass.id', 'subjects.id as subjid', 'teacher.id as teacherid', 'subjdesc', 'lastname', 'firstname', 'middlename', 'suffix')
							->join('subjects', 'gradesspclass.subjid', '=', 'subjects.id')
							->join('teacher', 'gradesspclass.teacherid', '=', 'teacher.id')
							->where('studid', $studid)
							->where('syid', $syid)
							->where('semid', $semid)
							->get();	
				}

				$dTail = '';

				foreach($spclassDetail as $spclass)
				{
					$tname = $spclass->lastname . ', ' . $spclass->firstname . ' ' . $spclass->middlename . ' ' . $spclass->suffix;
					$dTail .= '
						<tr id="'.$spclass->id.'">
							<td class="subj" data-id="'.$spclass->subjid.'">'. $spclass->subjdesc .'</td>
							<td clas="tinfo" data-id="'.$spclass->teacherid.'">'.$tname.'</td>
							
						</tr>
					';	
				}

				$data = array(
					'detail' => $dTail
				);		

				echo json_encode($data);
			}
		}

		public function savespClass(Request $request)
		{
			if($request->ajax())
			{
				$studid = $request->get('studid');
				$levelid = $request->get('levelid');
				$syid = $request->get('syid');
				$semid = $request->get('semid');
				// return $semid;

				if($semid != 0 || $semid == '')
				{
					$spClass = db::table('gradesspclass')
							->where('studid', $studid)
							->where('syid', $syid)
							->update([
								'studid'  => $studid,
								'levelid' => $levelid,
								'syid' => $syid
							]);		
				}
				else
				{
					$spClass = db::table('gradesspclass')
							->where('studid', $studid)
							->where('syid', $syid)
							->where('semdid', $semid)
							->update([
								'studid'  => $studid,
								'levelid' => $levelid,
								'syid' => $syid,
								'semid' => $semid
							]);
				}
			}
		}

		public function editspClass(Request $request)
		{
			if($request->ajax())
			{
				$studid = $request->get('studid');
				$levelid = $request->get('levelid');
				$syid = $request->get('syid');
				$semid = $request->get('semid');

				

				if($semid == '')
				{
					$spclass = db::table('gradesspclass')
							->select('gradesspclass.*', 'lastname', 'firstname', 'middlename', 'suffix', 'subjdesc')
							->join('subjects', 'gradesspclass.subjid', '=', 'subjects.id')
							->join('teacher', 'gradesspclass.teacherid', '=', 'teacher.id')
							->where('studid', $studid)
							->where('gradesspclass.levelid', $levelid)
							->where('gradesspclass.syid', $syid)
							->where('gradesspclass.deleted', 0)
							->get();
				}
				else
				{
					$spclass = db::table('gradesspclass')
							->select('gradesspclass.*', 'lastname', 'firstname', 'middlename', 'suffix', 'subjdesc')
							->join('subjects', 'gradesspclass.subjid', '=', 'subjects.id')
							->join('teacher', 'gradesspclass.teacherid', '=', 'teacher.id')
							->where('studid', $studid)
							->where('gradesspclass.levelid', $levelid)
							->where('gradesspclass.syid', $syid)
							->where('gradesspclass.semid')
							->where('gradesspclass.deleted', 0)
							->get();
				}

				$dTail = '';
				foreach($spclass as $sp)
				{
					$tname = $sp->lastname . ', ' . $sp->firstname . ' ' . $sp->middlename . ' ' . $sp->suffix;
					$dTail .= '
						<tr id="'.$sp->id.'">
							<td class="subj" data-id="'.$sp->subjid.'">'. $sp->subjdesc .'</td>
							<td clas="tinfo" data-id="'.$sp->teacherid.'">'.$tname.'</td>
							
						</tr>
					';	
				}

				$data = array(
					'studid' => $spclass[0]->studid,
					'levelid' => $spclass[0]->levelid,
					'syid' => $spclass[0]->syid,
					'semid' => $spclass[0]->semid,
					'dTail' => $dTail
				);


				echo json_encode($data);

			}
		}

		public function editDetail(Request $request)
		{
			if($request->ajax())
			{

				$dataid = $request->get('dataid');

				$dtail = db::table('gradesspclass')
						->where('id', $dataid)
						->get();


				if(count($dtail) > 0)
				{
					$data = array(
						'subjid' => $dtail[0]->subjid,
						'teacherid' => $dtail[0]->teacherid
					);

					echo json_encode($data);
				}

			}
		}

		public function deleteDetail(Request $request)
		{
			if($request->ajax())
			{
				$dataid = $request->get('dataid');

				$del = db::table('gradesspclass')
						->where('id', $dataid)
						->update([
							'deleted' => 1,
							'deletedby' => auth()->user()->id,
							'deleteddatetime' => RegistrarModel::getServerDateTime()
						]);



			}
		}

		public function loadDtail(Request $request)
		{
			if($request->ajax())
			{
				$studid = $request->get('studid');
				$levelid = $request->get('levelid');
				$syid = $request->get('syid');
				$semid = $request->get('semid');
				// return $studid;
				
				if($semid == '')
				{
					$spclass = db::table('gradesspclass')
							->select('gradesspclass.*', 'lastname', 'firstname', 'middlename', 'suffix', 'subjdesc')
							->join('subjects', 'gradesspclass.subjid', '=', 'subjects.id')
							->join('teacher', 'gradesspclass.teacherid', '=', 'teacher.id')
							->where('studid', $studid)
							->where('gradesspclass.levelid', $levelid)
							->where('gradesspclass.syid', $syid)
							->where('gradesspclass.deleted', 0)
							->get();
				}
				else
				{
					$spclass = db::table('gradesspclass')
							->select('gradesspclass.*', 'lastname', 'firstname', 'middlename', 'suffix', 'subjdesc')
							->join('subjects', 'gradesspclass.subjid', '=', 'subjects.id')
							->join('teacher', 'gradesspclass.teacherid', '=', 'teacher.id')
							->where('studid', $studid)
							->where('gradesspclass.levelid', $levelid)
							->where('gradesspclass.syid', $syid)
							->where('gradesspclass.semid')
							->where('gradesspclass.deleted', 0)
							->get();
				}
				// return $spclass;
				$dTail = '';
				foreach($spclass as $sp)
				{
					$tname = $sp->lastname . ', ' . $sp->firstname . ' ' . $sp->middlename . ' ' . $sp->suffix;
					$dTail .= '
						<tr id="'.$sp->id.'">
							<td class="subj" data-id="'.$sp->subjid.'">'. $sp->subjdesc .'</td>
							<td clas="tinfo" data-id="'.$sp->teacherid.'">'.$tname.'</td>
							
						</tr>
					';	
				}

				$data = array(
					'dTail' => $dTail
				);


				echo json_encode($data);

			}
		}

		public function getDP(Request $request)
		{
			if($request->ajax())
			{
				$studid = $request->get('studid');
				$levelid = $request->get('levelid');
				// return $levelid;
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

				if($ok == 0)
				{
					if($levelid == 14 || $levelid == 15)
					{
						$getdp = db::table('chrngtransdetail')				
								->select('studid', 'chrngtrans.syid', 'chrngtrans.semid', 'itemkind', 'payschedid', 'items.isdp')
								->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
								->join('items', 'chrngtransdetail.payschedid', '=', 'items.id')
								->where('studid', $studid)
								->where('chrngtrans.syid', RegistrarModel::getSYID())
								->where('chrngtrans.semid', RegistrarModel::getSemID())
								->where('itemkind', 1)
								->where('items.isdp', 1)
								->where('cancelled', 0)
								->get();
					}
					if($levelid >= 17 || $levelid <= 21)
					{
						$getdp = db::table('chrngtransdetail')				
								->select('studid', 'chrngtrans.syid', 'chrngtrans.semid', 'itemkind', 'payschedid', 'items.isdp')
								->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
								->join('items', 'chrngtransdetail.payschedid', '=', 'items.id')
								->where('studid', $studid)
								->where('chrngtrans.syid', RegistrarModel::getSYID())
								->where('chrngtrans.semid', RegistrarModel::getSemID())
								->where('itemkind', 1)
								->where('items.isdp', 1)
								->where('cancelled', 0)
								->get();	
					}
					else
					{
						$getdp = db::table('chrngtransdetail')				
								->select('studid', 'chrngtrans.syid', 'chrngtrans.semid', 'itemkind', 'payschedid', 'items.isdp')
								->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
								->join('items', 'chrngtransdetail.payschedid', '=', 'items.id')
								->where('studid', $studid)
								->where('chrngtrans.syid', RegistrarModel::getSYID())
								->where('itemkind', 1)
								->where('items.isdp', 1)
								->where('chrngtrans.cancelled', 0)
								->get();
					}

					if(count($getdp) > 0)
					{
						$ok = 1;
						$dp = 1;
					}
					else
						$ok = 0;
				}

				$data = array(
					'ok' => $ok,
					'dp' => $dp
				);

				echo json_encode($data);
			}
		}

		public function sync(Request $request)
		{
			$tablename = 'studinfo';

			return SyncModel::execsync($tablename);

		}

		public function registered()
		{
			$acadprog = RegistrarModel::activeacadprog();

			$level = db::table('gradelevel')
				->whereIn('acadprogid', $acadprog)
	  			->where('deleted', 0)
	  			->orderBy('sortid')
	  			->get();

	  		$schoolinfo = db::table('schoolinfo')
	  			->first();


	  		$data = array(
	  			'level' => $level,
	  			'schoolinfo' => $schoolinfo
	  		);

			return view('/enrollment/registered')
				->with($data);
		}

		public function searchRegStud(Request $request)
		{
			if($request->ajax())
	  		{
		  		$glevel = $request->get('glevel');
		  		$query = $request->get('query');
		  		$skip = $request->get('skip');
		  		$take = $request->get('take');
		  		$curpage = $request->get('curpage');

		  		$skip = ((int)$skip - 1) * $take;

		  		$acadprog = RegistrarModel::activeacadprog();
		  		
	    		$student = db::table('studinfo')
    				->select('studinfo.*', 'gradelevel.levelname', 'sections.sectionname as secname', 'grantee.description as grantee')
    				->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
    				->leftJoin('sections', 'studinfo.sectionid', '=', 'sections.id')
    				->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
    				->where(function($q) use($glevel, $acadprog){
    					if($glevel > 0)
    					{
    						$q->where('studinfo.levelid', $glevel);
    					}
    					else
    					{
    						$q->whereIn('gradelevel.acadprogid', $acadprog);
    					}
    				})
    				->where('lastname', 'like', '%'.$query.'%')
    				->where('studstatus', 0)
    				->where('studinfo.deleted', 0)
    				->orWhere('firstname', 'like', '%'.$query.'%')
    				->where('studinfo.levelid', $glevel)
    				->where('studstatus', 0)
    				->where('studinfo.deleted', 0)
    				->orderBy('lastname','ASC')
    				->orderBy('firstname','ASC')
    				->skip($skip)
    				->take($take)
    				->get();

    				// return $student;

	    		$recCount = db::table('studinfo')
    				->select('studinfo.*', 'gradelevel.levelname', 'sections.sectionname as secname')
    				->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
    				->leftJoin('sections', 'studinfo.sectionid', '=', 'sections.id')
    				->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
    				->where(function($q) use($glevel, $acadprog){
    					if($glevel > 0)
    					{
    						$q->where('studinfo.levelid', $glevel);
    					}
    					else
    					{
    						$q->whereIn('gradelevel.acadprogid', $acadprog);
    					}
    				})
    				->where('lastname', 'like', '%'.$query.'%')
    				->where('studstatus', 0)
    				->where('studinfo.deleted', 0)
    				->orWhere('firstname', 'like', '%'.$query.'%')
    				->where('studinfo.levelid', $glevel)
    				->where('studstatus', 0)
    				->where('studinfo.deleted', 0)
    				->count();
	  		

		  		$output = '';
		  		$paginate = '';
		  		$paginate .= '
		  			<li class="paginate_button page-item previous" id="example2_previous">
							<a href="#" aria-controls="example2" data-page="0" tabindex="0" class="page-link">Previous</a>
						</li>
		  		';
	  			
	  			$count = count($student);

		  		foreach($student as $s)
		  		{
		  			if($s->studstatus == 1)
		  			{
		  				$status = '<span style="width:1px height:100%" class="bg-success">&nbsp;</span>';
		  			}
		  			elseif($s->studstatus == 2)
		  			{
		  				$status = '<span style="width:1px height:100%" class="bg-primary">&nbsp;</span>';	
		  			}
		  			elseif($s->studstatus == 3)
		  			{
		  				$status = '<span style="width:1px height:100%" class="bg-danger">&nbsp;</span>';
		  			}
		  			elseif($s->studstatus == 4)
		  			{
		  				$status = '<span style="width:1px height:100%" class="bg-warning">&nbsp;</span>';	
		  			}
		  			elseif($s->studstatus == 5)
		  			{
		  				$status = '<span style="width:1px height:100%" class="bg-secondary">&nbsp;</span>';	
		  			}
		  			elseif($s->studstatus == 6)
		  			{
		  				$status = '<span style="width:1px height:100%" class="bg-orange">&nbsp;</span>';	
		  			}
		  			else
		  			{
		  				$status = '';		
		  			}
		  			
		  			if($s->levelid == 14 || $s->levelid == 15)
		  			{
		  				$chkEnroll = db::table('sh_enrolledstud')
		  						->where('studid', $s->id)
		  						->where('syid', RegistrarModel::getSYID())
		  						->where('semid', RegistrarModel::getSemID())
		  						->where('studstatus', '>', '0')
		  						->where('deleted', 0)
		  						->get();
		  			}
		  			else
		  			{
		  				$chkEnroll = db::table('enrolledstud')
		  						->where('studid', $s->id)
		  						->where('syid', RegistrarModel::getSYID())
		  						->where('studstatus', '>', '0')
		  						->where('deleted', 0)
		  						->get();
		  			}

		  			// if($s->studstatus == 0)
		  			// return $chkEnroll;
		  			if(count($chkEnroll) == 0)
		  			{
			  			$output .= '

			  				<tr>
			  					<td> '.$status.' '.$s->sid.'</td>
			  					<td id="studname"'.$s->id.'">'.strtoupper($s->lastname.' '.$s->firstname).'</td>
			  					<td>'.$s->gender.'</td>
			  					<td>'.$s->levelname.'</td>
			  					<td>'.$s->secname.'</td>
			  					<td>'.$s->grantee.'</td>
			  					<td><button id="cmdenroll" class="btn btn-info btn-block" data-toggle="modal" data-glevel="'.$s->levelid.'" data-value="'.$s->id.'" data-sid="'.$s->sid.'" data-target="#enrollstud">Enroll</button></td>
			  					<td><button id="deleteStud" class="btn btn-danger btn-block" data-id="'.$s->id.'">Delete</button></td>
			  				</tr>

			  			';
			  		}
			  		else
			  		{
			  			$output .= '

			  				<tr>
			  					<td> '.$status.' '.$s->sid.'</td>
			  					<td id="studname"'.$s->id.'">'.strtoupper($s->lastname.' '.$s->firstname).'</td>
			  					<td>'.$s->gender.'</td>
			  					<td>'.$s->levelname.'</td>
			  					<td>'.$s->secname.'</td>
			  					<td>'.$s->grantee.'</td>
			  					<td colspan="2"><button id="cmdViewEnrollment" class="btn btn-info btn-block" data-id="'.$s->id.'" data-glevel="'.$s->levelid.'" data-toggle="modal" data-target="#enrollstud">View Enrollment</button></td>
			  				</tr>

			  			';	
			  		}
		  		}
		  		// return $output;
		  		$data = array(
		  			'output' => $output,
		  			
		  			'glevel' => $glevel,
		  			'recCount' => $recCount
		  		);

		  		echo json_encode($data);
	  		}
	  	}			
		
		public function deleteStud(Request $request)
		{
			if($request->ajax())
			{
				$studid = $request->get('studid');

				$studinfo = db::table('studinfo')
						->where('id', $studid)
						->first();

				$studledger = db::table('studledger')
						->where('studid', $studid)
						->where('deleted', 0)
						->count('id');

				$onlinepayments = db::table('onlinepayments')
					->where('queingcode', $studinfo->qcode)
					->orWhere('queingcode', $studinfo->sid)
					->count();

				if($studinfo->levelid == 14 || $studinfo->levelid == 15)
				{
					$enrolledstud = db::table('enrolledstud')
							->where('studid', $studid)
							->where('deleted', 0)
							->count();
				}
				else
				{
					$enrolledstud = db::table('sh_enrolledstud')
							->where('studid', $studid)
							->where('deleted', 0)
							->count();
				}

				// return $studledger . ' ' . $onlinepayments . ' ' . $enrolledstud;

				if($studledger == 0 && $onlinepayments == 0 & $enrolledstud == 0)
				{
					$delStudinfo = db::table('studinfo')
							->where('id', $studid)
							->update([
								'deleted' => 1,
								'deletedby' => auth()->user()->id,
								'deleteddatetime' => RegistrarModel::getServerDateTime()
							]);

					return 1;
				}
				else
				{
					return 0;
				}

			}
		}

		public function enrolled()
		{
			$acadprog = RegistrarModel::activeacadprog();

			$level = db::table('gradelevel')
				->whereIn('acadprogid', $acadprog)
	  			->where('deleted', 0)
	  			->orderBy('sortid')
	  			->get();


	  		$schoolinfo = db::table('schoolinfo')
	  			->first();

	  		$data = array(
	  			'level' => $level,
	  			'schoolinfo' => $schoolinfo
	  		);

			return view('/enrollment/enrolled')
				->with($data);
		}

		public function searchEnrolledStud(Request $request)
		{
			if($request->ajax())
	  		{
		  		$glevel = $request->get('glevel');
		  		$query = $request->get('query');
		  		$skip = $request->get('skip');
		  		$take = $request->get('take');
		  		$curpage = $request->get('curpage');

		  		$skip = ((int)$skip - 1) * $take;

		  		$acadprog = RegistrarModel::activeacadprog();

	    		$student = db::table('studinfo')
					->select('studinfo.*', 'gradelevel.levelname', 'sections.sectionname as secname', 'grantee.description as grantee')
					->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
					->leftJoin('sections', 'studinfo.sectionid', '=', 'sections.id')
					->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
					->where(function($q) use($glevel, $acadprog){
						if($glevel > 0)
						{
							$q->where('studinfo.levelid', $glevel);
						}
						else
						{
							$q->whereIn('gradelevel.acadprogid', $acadprog);
						}
					})
					->where('lastname', 'like', '%'.$query.'%')
					->where('studstatus', '!=', 0)
					->where('studinfo.deleted', 0)
					->orWhere('firstname', 'like', '%'.$query.'%')
					->where('studinfo.levelid', $glevel)
					->where('studstatus', '!=', 0)
					->where('studinfo.deleted', 0)
					->orderBy('lastname','ASC')
					->orderBy('firstname','ASC')
					->skip($skip)
					->take($take)
					->get();

	    				// return $student;

	    		$recCount = db::table('studinfo')
					->select('studinfo.*', 'gradelevel.levelname', 'sections.sectionname as secname')
					->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
					->leftJoin('sections', 'studinfo.sectionid', '=', 'sections.id')
					->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
					->where(function($q) use($glevel, $acadprog){
						if($glevel > 0)
						{
							$q->where('studinfo.levelid', $glevel);
						}
						else
						{
							$q->whereIn('gradelevel.acadprogid', $acadprog);
						}
					})
					->where('lastname', 'like', '%'.$query.'%')
					->where('studstatus', '!=', 0)
					->where('studinfo.deleted', 0)
					->orWhere('firstname', 'like', '%'.$query.'%')
					->where('studinfo.levelid', $glevel)
					->where('studstatus', '!=', 0)
					->where('studinfo.deleted', 0)
					->count();

		  		$output = '';
		  		$paginate = '';
		  		$paginate .= '
		  			<li class="paginate_button page-item previous" id="example2_previous">
							<a href="#" aria-controls="example2" data-page="0" tabindex="0" class="page-link">Previous</a>
						</li>
		  		';
		  		$count = count($student);

		  		foreach($student as $s)
		  		{
		  			if($s->studstatus == 1)
		  			{
		  				$status = '<span style="width:1px height:100%" class="bg-success">&nbsp;</span>';
		  			}
		  			elseif($s->studstatus == 2)
		  			{
		  				$status = '<span style="width:1px height:100%" class="bg-primary">&nbsp;</span>';	
		  			}
		  			elseif($s->studstatus == 3)
		  			{
		  				$status = '<span style="width:1px height:100%" class="bg-danger">&nbsp;</span>';
		  			}
		  			elseif($s->studstatus == 4)
		  			{
		  				$status = '<span style="width:1px height:100%" class="bg-warning">&nbsp;</span>';	
		  			}
		  			elseif($s->studstatus == 5)
		  			{
		  				$status = '<span style="width:1px height:100%" class="bg-secondary">&nbsp;</span>';	
		  			}
		  			elseif($s->studstatus == 6)
		  			{
		  				$status = '<span style="width:1px height:100%" class="bg-orange">&nbsp;</span>';	
		  			}
		  			else
		  			{
		  				$status = '';		
		  			}


		  			
		  			if($s->levelid == 14 || $s->levelid == 15)
		  			{
		  				$chkEnroll = db::table('sh_enrolledstud')
		  						->where('studid', $s->id)
		  						->where('syid', RegistrarModel::getSYID())
		  						->where('semid', RegistrarModel::getSemID())
		  						->where('studstatus', '>', '0')
		  						->where('deleted', 0)
		  						->get();

		  				$secname = $s->secname;

		  			}
		  			elseif($s->levelid >= 17 && $s->levelid <= 21)
		  			{
		  				// return $s->levelid;
		  				$chkEnroll = db::table('college_enrolledstud')
		  						->where('studid', $s->id)
		  						->where('syid', RegistrarModel::getSYID())
		  						->where('semid', RegistrarModel::getSemID())
		  						->where('studstatus', '>', '0')
		  						->where('deleted', 0)
		  						->get();

		  				$col_section = db::table('college_sections')
		  						->where('id', $s->sectionid)
		  						->first();


		  				if(!empty($col_section))
		  				{
		  					$secname = $col_section->sectionDesc;
		  				}
		  				else
		  				{
		  					$secname = '';
		  				}
		  				
		  			}
		  			else
		  			{
		  				$chkEnroll = db::table('enrolledstud')
		  						->where('studid', $s->id)
		  						->where('syid', RegistrarModel::getSYID())
		  						->where('studstatus', '>', '0')
		  						->where('deleted', 0)
		  						->get();

		  				$secname = $s->secname;
		  			}

		  			// if($s->studstatus == 0)
		  			// return $chkEnroll;
		  			if(count($chkEnroll) == 0)
		  			{
			  			$output .= '

			  				<tr>
			  					<td> '.$status.' '.$s->sid.'</td>
			  					<td id="studname"'.$s->id.'">'.strtoupper($s->lastname.' '.$s->firstname).'</td>
			  					<td>'.$s->gender.'</td>
			  					<td>'.$s->levelname.'</td>
			  					<td>'.$secname.'</td>
			  					<td>'.$s->grantee.'</td>
			  					<td><button id="cmdenroll" class="btn btn-info btn-block" data-toggle="modal" data-value="'.$s->id.'" data-sid="'.$s->sid.'" data-target="#enrollstud">Enroll</button></td>
			  					<td><button id="deleteStud" class="btn btn-danger btn-block" data-id="'.$s->id.'">Delete</button></td>
			  				</tr>

			  			';
			  		}
			  		else
			  		{
			  			$output .= '

			  				<tr>
			  					<td> '.$status.' '.$s->sid.'</td>
			  					<td id="studname"'.$s->id.'">'.strtoupper($s->lastname.' '.$s->firstname).'</td>
			  					<td>'.$s->gender.'</td>
			  					<td>'.$s->levelname.'</td>
			  					<td>'.$secname.'</td>
			  					<td>'.$s->grantee.'</td>
			  					<td colspan="2"><button id="cmdViewEnrollment" class="btn btn-info btn-block" data-id="'.$s->id.'" data-glevel="'.$s->levelid.'" data-toggle="modal" data-target="#enrollstud">View Enrollment</button></td>
			  				</tr>

			  			';	
			  		}
		  		}
	  		// return $output;
		  		$data = array(
		  			'output' => $output,
		  			
		  			'glevel' => $glevel,
		  			'recCount' => $recCount
		  		);

	  			echo json_encode($data);
	  		}
	 	}

	  	public function preenrolled()
		{

			$acadprog = RegistrarModel::activeacadprog();

			$level = db::table('gradelevel')
				->whereIn('acadprogid', $acadprog)
	  			->where('deleted', 0)
	  			->orderBy('sortid')
	  			->get();

	  		$schoolinfo = db::table('schoolinfo')
	  			->first();

	  		$data = array(
	  			'level' => $level,
	  			'schoolinfo' => $schoolinfo
	  		);

			return view('/enrollment/preenrolled')
				->with($data);
		}

	  	public function searchPreEnrolledStud(Request $request)
		{
			if($request->ajax())
	  		{
		  		$glevel = $request->get('glevel');
		  		$query = $request->get('query');
		  		$skip = $request->get('skip');
		  		$take = $request->get('take');
		  		$curpage = $request->get('curpage');

		  		$skip = ((int)$skip - 1) * $take;


		  		$acadprog = RegistrarModel::activeacadprog();

    			$student = db::table('studinfo')
    				->select('studinfo.*', 'gradelevel.levelname', 'sections.sectionname as secname', 'grantee.description as grantee')
    				->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
    				->leftJoin('sections', 'studinfo.sectionid', '=', 'sections.id')
    				->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
    				->where(function($q) use($glevel, $acadprog){
    					if($glevel > 0)
    					{
    						$q->where('studinfo.levelid', $glevel);
    					}
    					else
    					{
    						$q->whereIn('gradelevel.acadprogid', $acadprog);
    					}
    				})
    				->where('lastname', 'like', '%'.$query.'%')
    				->where('studstatus', 0)
    				->where('preEnrolled', 1)
    				->where('studinfo.deleted', 0)
    				->orWhere('firstname', 'like', '%'.$query.'%')
    				->where(function($q) use($glevel, $acadprog){
    					if($glevel > 0)
    					{
    						$q->where('studinfo.levelid', $glevel);
    					}
    					else
    					{
    						$q->whereIn('gradelevel.acadprogid', $acadprog);
    					}
    				})
    				->where('studstatus', 0)
    				->where('preEnrolled', 1)
    				->where('studinfo.deleted', 0)
    				->orWhere('sid', 'like', '%'.$query.'%')
    				->where(function($q) use($glevel, $acadprog){
    					if($glevel > 0)
    					{
    						$q->where('studinfo.levelid', $glevel);
    					}
    					else
    					{
    						$q->whereIn('gradelevel.acadprogid', $acadprog);
    					}
    				})
    				->where('studstatus', 0)
    				->where('preEnrolled', 1)
    				->where('studinfo.deleted', 0)
    				->orderBy('lastname','ASC')
    				->orderBy('firstname','ASC')
    				->skip($skip)
    				->take($take)
    				->get();


    			$recCount = db::table('studinfo')
    				->select('studinfo.*', 'gradelevel.levelname', 'sections.sectionname as secname')
    				->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
    				->leftJoin('sections', 'studinfo.sectionid', '=', 'sections.id')
    				->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
    				->where(function($q) use($glevel, $acadprog){
    					if($glevel > 0)
    					{
    						$q->where('studinfo.levelid', $glevel);
    					}
    					else
    					{
    						$q->whereIn('gradelevel.acadprogid', $acadprog);
    					}
    				})
    				->where('lastname', 'like', '%'.$query.'%')
    				->where('studstatus', 0)
    				->where('preEnrolled', 1)
    				->where('studinfo.deleted', 0)
    				->orWhere('firstname', 'like', '%'.$query.'%')
    				->where('studinfo.levelid', $glevel)
    				->where('studstatus', 0)
    				->where('preEnrolled', 1)
    				->where('studinfo.deleted', 0)
    				->count();
	  			

		  		$output = '';
		  		$paginate = '';
		  		$paginate .= '
		  			<li class="paginate_button page-item previous" id="example2_previous">
							<a href="#" aria-controls="example2" data-page="0" tabindex="0" class="page-link">Previous</a>
						</li>
		  		';
		  		$count = count($student);

		  		foreach($student as $s)
		  		{
		  			if($s->studstatus == 1)
		  			{
		  				$status = '<span style="width:1px height:100%" class="bg-success">&nbsp;</span>';
		  			}
		  			elseif($s->studstatus == 2)
		  			{
		  				$status = '<span style="width:1px height:100%" class="bg-primary">&nbsp;</span>';	
		  			}
		  			elseif($s->studstatus == 3)
		  			{
		  				$status = '<span style="width:1px height:100%" class="bg-danger">&nbsp;</span>';
		  			}
		  			elseif($s->studstatus == 4)
		  			{
		  				$status = '<span style="width:1px height:100%" class="bg-warning">&nbsp;</span>';	
		  			}
		  			elseif($s->studstatus == 5)
		  			{
		  				$status = '<span style="width:1px height:100%" class="bg-secondary">&nbsp;</span>';	
		  			}
		  			elseif($s->studstatus == 6)
		  			{
		  				$status = '<span style="width:1px height:100%" class="bg-orange">&nbsp;</span>';	
		  			}
		  			else
		  			{
		  				$status = '';		
		  			}
		  			
		  			$secname = '';

		  			if($s->levelid == 14 || $s->levelid == 15)
		  			{
		  				$chkEnroll = db::table('sh_enrolledstud')
		  						->where('studid', $s->id)
		  						->where('syid', RegistrarModel::getSYID())
		  						->where('semid', RegistrarModel::getSemID())
		  						->where('studstatus', '>', '0')
		  						->where('deleted', 0)
		  						->get();

		  				$secname = $s->secname;
		  			}
		  			elseif($s->levelid >= 17 && $s->levelid <= 21)
		  			{
		  				$chkEnroll = db::table('college_enrolledstud')
		  						->where('studid', $s->id)
		  						->where('syID', RegistrarModel::getSYID())
		  						->where('semid', RegistrarModel::getSemID())
		  						->where('studstatus', '>', '0')
		  						->where('deleted', 0)
		  						->get();	

		  				$secname = $s->sectionname;


		  			}
		  			else
		  			{
		  				$chkEnroll = db::table('enrolledstud')
		  						->where('studid', $s->id)
		  						->where('syid', RegistrarModel::getSYID())
		  						->where('studstatus', '>', '0')
		  						->where('deleted', 0)
		  						->get();

		  				$secname = $s->secname;
		  			}

		  			// if($s->studstatus == 0)
		  			// return $chkEnroll;
		  			if(count($chkEnroll) == 0)
		  			{
			  			$output .= '

			  				<tr>
			  					<td> '.$status.' '.$s->sid.'</td>
			  					<td id="studname"'.$s->id.'">'.strtoupper($s->lastname.' '.$s->firstname).'</td>
			  					<td>'.$s->gender.'</td>
			  					<td>'.$s->levelname.'</td>
			  					<td>'.$secname.'</td>
			  					<td>'.$s->grantee.'</td>
			  					<td colspan="2"><button id="cmdenroll" class="btn btn-info btn-block" data-toggle="modal" data-value="'.$s->id.'" data-sid="'.$s->sid.'" data-target="#enrollstud">Enroll</button>
			  					</td>
			  				</tr>

			  			';
			  		}
			  		else
			  		{
			  			$output .= '

			  				<tr>
			  					<td> '.$status.' '.$s->sid.'</td>
			  					<td id="studname"'.$s->id.'">'.strtoupper($s->lastname.' '.$s->firstname).'</td>
			  					<td>'.$s->gender.'</td>
			  					<td>'.$s->levelname.'</td>
			  					<td>'.$secname.'</td>
			  					<td>'.$s->grantee.'</td>
			  					<td colspan="2"><button id="cmdViewEnrollment" class="btn btn-info btn-block" data-id="'.$s->id.'" data-glevel="'.$s->levelid.'" data-toggle="modal" data-target="#enrollstud">View Enrollment</button></td>
			  				</tr>

			  			';	
			  		}
		  		}
		  		$data = array(
		  			'output' => $output,
		  			
		  			'glevel' => $glevel,
		  			'recCount' => $recCount
		  		);

		  		echo json_encode($data);
	  		}
	  	}

	public function viewreq(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');

			$studinfo = db::table('studinfo')
				->where('id', $studid)
				->first();

			// return $studinfo->qcode;

			$qcode = $studinfo->qcode;
			$lrn = $studinfo->lrn;
			$sid = $studinfo->sid;

			$requirements = db::table('preregistrationrequirements')
				->select('preregistrationrequirements.id', 'qcode', 'description', 'picurl')
				->join('preregistrationreqlist', 'preregistrationrequirements.preregreqtype', '=', 'preregistrationreqlist.id')
				->where('qcode', $qcode)
				->orWhere('qcode', $lrn)
				->orWhere('qcode', $sid)
				->get();

			$list = '';
			$url = '';
			foreach($requirements as $req)
			{
				$url = str_replace('/', '/' . $req->qcode . '/', $req->picurl);
				$list .='
					<div class="col-md-3 view-img" style="cursor:pointer">
		    		<div class="card">
		    			<div class="card-body">
			            	<a data-magnify="gallery" data-src="" data-caption="" data-group="a" href="'.url($url).'">
			              		<img class="w-100 req-img" src="'. url($url) .'">
			              	</a>	
			             </div>
		              	<div class="card-footer bg-warning">
		              		'.$req->description.'
		              	</div>
		          	</div>
		        </div>
				';
			}

			$data = array(
				'list' => $list
			);

			echo json_encode($data);
		}
	}

	

	public function preregreq(Request $request)
	{
		if($request->ajax())		
		{
			$qcode = $request->get('qcode');
			// return $qcode;
			// $prereg = db::table('preregistration')
			// 	->where('queing_code', $qcode)
			// 	->first();
			// return $studinfo->qcode;
			// $qcode = $studinfo->qcode;

			$requirements = db::table('preregistrationrequirements')
				->select('preregistrationrequirements.id', 'qcode', 'description', 'picurl')
				->join('preregistrationreqlist', 'preregistrationrequirements.preregreqtype', '=', 'preregistrationreqlist.id')
				->where('qcode', $qcode)
				->get();

			$list = '';
			$url = '';
			foreach($requirements as $req)
			{
				$url = str_replace('/', '/' . $req->qcode . '/', $req->picurl);
				$list .='
					<div class="col-md-3 view-img" style="cursor:pointer">
		    		<div class="card">
		    			<div class="card-body">
			            	<a data-magnify="gallery" data-src="" data-caption="" data-group="a" href="'.url($url).'">
			              		<img class="w-100 req-img" src="'. url($url) .'">
			              	</a>	
			             </div>
		              	<div class="card-footer bg-warning">
		              		'.$req->description.'
		              	</div>
		          	</div>
		        </div>
				';
			}

			$data = array(
				'list' => $list
			);

			echo json_encode($data);
		}
	}

	public function getstudpaid(Request $request)
	{
		if($request->ajax())
		{

			$list = '';
			$stat = '';
			$count = 0;
			$class = '';

			$onlinepayments = db::table('onlinepayments')
				->where('isapproved', '=', 5)
				->get();

			foreach($onlinepayments as $ol)
			{
				$studname = '';
				$studinfo = db::table('studinfo')
					->select('studinfo.id as studinfo', 'sid', 'lrn', 'sid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname')
					->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
					->where('studinfo.deleted', 0)
					->where('studstatus', 0)
					->where('preenrolled', 1)
					->where(function($q) use ($ol){
						$q->where('sid', $ol->queingcode);
						$q->orWhere('lrn', $ol->queingcode);
						$q->orWhere('qcode', $ol->queingcode);
					})
					->first();

				if($studinfo)
				{
					$count += 1;
					if($ol->isapproved == 1)
					{
						$stat = 'APPROVED';
						$class = 'text-primary';
					}
					elseif($ol->isapproved == 2)
					{
						$stat = 'DISAPPROVED';
						$class = 'text-danger';
					}
					elseif($ol->isapproved == 3)
					{
						$stat = 'CANCELLED';
						$class = 'text-warning';
					}
					elseif($ol->isapproved == 5)
					{
						$stat = 'PAID';
						$class = 'text-success';
					}
					elseif($ol->isapproved == 6)
					{
						$stat = 'NO DOWNPAYMENT';
						$class = 'text-info';
					}

					$studname = $studinfo->lastname . ', ' . $studinfo->firstname . ' ' . $studinfo->middlename. ' ' . $studinfo->suffix;
					$list .='
						<tr class="'.$class.'" data-id="'.$studinfo->sid.'">
							<td>'.$studinfo->lrn.'</td>
							<td>'.$studname.'</td>
							<td>'.$studinfo->levelname.'</td>
							<td>'.$stat.'</td>
						</tr>
					';
				}
			}

			$data = array(
				'studlist' => $list,
				'studcount' => $count
			);

			echo json_encode($data);
		}
	}

	public function tvcourses()
	{
		return view('enrollment/tvcourses');
	}

	public function saveTVCourse(Request $request)
	{
		if($request->ajax())
		{
			$code = $request->get('code');
			$description = $request->get('description');
			$duration = $request->get('duration');

			$checkdouble = db::table('tv_courses')
				->where('description', $description)
				->count();

			if($checkdouble > 0)
			{
				return 0;
			}
			else
			{
				db::table('tv_courses')
					->insert([
						'description' => $description,
						'duration' => $duration,
						'createdby' => auth()->user()->id,
						'createddatetime' => RegistrarModel::getServerDateTime()
					]);

				return 1;
			}
		}
	}

	public function tvsearch(Request $request)
	{
		if($request->ajax())
		{
			$filter = $request->get('filter');

			$courses = db::table('tv_courses')
				// ->select('*', 'asd')
				->where('deleted', 0)
				->where('description', 'like', '%'.$filter.'%')
				->get();


			$list = '';

			foreach($courses as $course)
			{
				$list .='
					<tr data-id="'.$course->id.'">
						<td>'.$course->description.'</td>
						<td>'.$course->duration.' Months</td>
						<td style="width:30px"><button data-id="'.$course->id.'" class="btn btn-primary btn-edit">View</button></td>
						<td><button data-id="'.$course->id.'" class="btn btn-danger btn-delete">Delete</button></td>
					</tr>
				';
			}

			$data = array(
				'list' => $list
			);

			echo json_encode($data);
		}
	}

	public function editTVCourse(Request $request)
	{
		if($request->ajax())
		{
			$dataid = $request->get('dataid');

			$course = db::table('tv_courses')
				->where('id', $dataid)
				->first();

			$data = array(
				'description' => $course->description,
				'duration' => $course->duration
			);


			echo json_encode($data);

		}
	}

	public function updateTVCourse(Request $request)
	{
		if($request->ajax())
		{
			$description = $request->get('description');
			$duration = $request->get('duration');
			$dataid = $request->get('dataid');

			db::table('tv_courses')
				->where('id', $dataid)
				->update([
					'description' => $description,
					'duration' => $duration
				]);

		}
	}

	public function deleteTVCourse(Request $request)
	{
		if($request->ajax())
		{
			$dataid = $request->get('dataid');

			db::table('tv_courses')
				->where('id', $dataid)
				->update([
					'deleted' => 1,
					'deletedby' => auth()->user()->id,
					'deleteddatetime' => RegistrarModel::getServerDateTime()
				]);
		}
	}

	public function tvbatch()
	{
		return view('enrollment/tvbatch');
	}

	public function loadbatch(Request $request)
	{
        $courseid = $request->get('courseid');

        $batches = db::table('tv_batch')
            ->where('courseid', $courseid)
            ->orderBy('id', 'DESC')
            ->get();

        $list = '';

        foreach($batches as $batch)
        {
            $start = date_create($batch->startdate);
            $start = date_format($start, 'm/d/Y');

            $end = date_create($batch->enddate);
            $end = date_format($end, 'm/d/Y');

            if($batch->isactive == 0)
            {
                $list .='
                    <tr>
                        <td>'.$start.' - '.$end.'</td>
                        <td style="width:150px">
                            <button class="btn btn-primary btn-block btn-view" data-id="'.$batch->id.'">View</button>
                        </td>
                        <td style="width:150px">
                            <button class="btn btn-outline-primary btn-block btn-activate" data-id="'.$batch->id.'">Activate</button>
                        </td>
                    </tr>
                ';
            }
            else
            {
                $list .='
                    <tr>
                        <td>'.$start.' - '.$end.'</td>
                        <td style="width:150px">
                            <button class="btn btn-primary btn-block btn-view" data-id="'.$batch->id.'">View</button>
                        </td>
                        <td style="width:150px">
                            <button class="btn btn-success btn-block btn-activate" data-id="'.$batch->id.'">Activated</button>
                        </td>
                    </tr>
                ';	
            }
        }

        $data = array(
            'list' => $list
        );

        echo json_encode($data);
	}

	public function createbatch(Request $request)
	{
        $courseid = $request->get('courseid');
        $start = $request->get('startdate');
        $end = $request->get('enddate');

        $checkdouble = db::table('tv_batch')
            ->where('courseid', $courseid)
            ->where('startdate', $start)
            ->where('enddate', $end)
            ->count();


        if($checkdouble > 0)
        {
            return 0;
        }
        else
        {
            db::table('tv_batch')
                ->insert([
                    'courseid' => $courseid,
                    'startdate' => $start,
                    'enddate' => $end,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => RegistrarModel::getServerDateTime()
                ]);
            return 1;
        }
	}

	public function activatebatch(Request $request)
	{
		if($request->ajax())
		{
			$batchid = $request->get('batchid');
			$courseid = $request->get('courseid');

			$batch = db::table('tv_batch')
				->where('id', $batchid)
				->first();

			if($batch->isactive == 1)
			{
				return 0;
			}
			else
			{
				db::table('tv_batch')
					->where('courseid', $courseid)
					->update([
						'isactive' => 0,
						'updatedby' => auth()->user()->id,
						'updateddatetime' => RegistrarModel::getServerDateTime()
					]);


				db::table('tv_batch')
					->where('id', $batchid)
					->update([
						'isactive' => 1,
						'updatedby' => auth()->user()->id,
						'updateddatetime' => RegistrarModel::getServerDateTime()
					]);

				return 1;
			}
		}
	}
	public function getbatchdates(Request $request)
	{
		if($request->ajax())
		{
			$batchinfo = DB::table('tv_batch')
				->where('id', $request->get('batchid'))
				->first();
	
			return collect($batchinfo);
		}
	}
	public function updatebatchdates(Request $request)
	{
		if($request->ajax())
		{
			DB::table('tv_batch')
				->where('id', $request->get('batchid'))
				->update([
					'startdate' 		=> $request->get('startdate'),
					'enddate'			=> $request->get('enddate'),
					'updatedby'			=> auth()->user()->id,
					'updateddatetime'	=> RegistrarModel::getServerDateTime() 
				]);
		}
	}
	public function tvdeletebatch(Request $request)
	{
		if($request->ajax())
		{
			DB::table('tv_batch')
				->where('id', $request->get('batchid'))
				->update([
					'deleted' 			=> 1,
					'deletedby'			=> auth()->user()->id,
					'deleteddatetime'	=> RegistrarModel::getServerDateTime() 
				]);
		}
	}

	public function tvstudinfo()
	{
		$studinfo = db::table('studinfo')
			->select('id', 'lastname', 'firstname', 'middlename', 'suffix')
			->where('deleted', 0)
			->orderBy('lastname', 'ASC')
			->orderBy('firstname', 'ASC')
            ->get();
            
		$nationality = db::table('nationality')
			->get();

		$data = array(
			'studinfo' => $studinfo,
			'nationality' => $nationality
        );
        
        $techvocstudents = DB::table('tv_enrolledstud')
            ->select(
                'tv_enrolledstud.id as techvocid',
                'tv_enrolledstud.status',
                'studinfo.id as studid',
                'studinfo.sid',
                'studinfo.lastname',
                'studinfo.middlename',
                'studinfo.firstname',
                'studinfo.suffix',
                'studinfo.gender',
                'tv_courses.id as courseid',
                'tv_courses.description as coursename',
                'tv_batch.id as batchid',
                'tv_batch.startdate',
                'tv_batch.enddate'
                )
            ->join('studinfo','tv_enrolledstud.studid','=','studinfo.id')
            ->join('tv_courses','tv_enrolledstud.courseid','=','tv_courses.id')
            ->join('tv_batch','tv_enrolledstud.batchid','=','tv_batch.id')
            ->where('tv_enrolledstud.deleted','0')
            ->where('tv_courses.deleted','0')
            ->where('tv_batch.deleted','0')
            ->get();
			
		$courses = Db::table('tv_courses')
				->where('deleted','0')
				->get();

		return view('enrollment/tvstudinfo')->with($data)->with('techvocstudents',$techvocstudents)->with('courses',$courses);
	}

	public function tvgetbatches(Request $request)
	{
		$batches = DB::table('tv_batch')
			->where('courseid', $request->get('id'))
			->where('deleted','0')
			->get();

		if(count($batches)>0)
		{
			foreach($batches as $batch)
			{
				if($batch->startdate!=null && $batch->enddate!=null)
				{
					$batch->batchdates = date('M d, Y', strtotime($batch->startdate)).' - '.date('M d, Y', strtotime($batch->enddate));
				}else{
					$batch->batchdates = "";
				}
			}
		}
		return collect($batches);
	}
	public function tvupdateenrolmentinfo(Request $request)
	{
		if($request->ajax())
		{
			$checkifexists = DB::table('tv_enrolledstud')
				->where('studid', $request->get('studentid'))
				->where('courseid', $request->get('courseid'))
				->where('batchid', $request->get('batchid'))
				->where('deleted','0')
				->count();
			
			if($checkifexists == 0)
			{
				DB::table('tv_enrolledstud')
					->where('id', $request->get('enrolledstudid'))
					->update([
						'courseid'	=> $request->get('courseid'),
						'batchid'	=> $request->get('batchid')
					]);

				return 1;
			}else{
				return 0;
			}
		}
	}
	public function tvstudsearch(Request $request)
	{

		// DB::table('testing')
		// 		->where('deleted',0)
		// 		->where('firstname',0)
		// 		->orWhere('lastname',0)
		// 		->get();
		// $st
		// return $request->all();
		$students = array();
		if($request->get('courseid') == null && $request->get('batchid') == null)
		{
			$techvocstudents = DB::table('tv_enrolledstud')
				->select(
					'tv_enrolledstud.id as techvocid',
					'tv_enrolledstud.status',
					'studinfo.id as studid',
					'studinfo.sid',
					'studinfo.lastname',
					'studinfo.middlename',
					'studinfo.firstname',
					'studinfo.suffix',
					'studinfo.gender',
					'tv_courses.id as courseid',
					'tv_courses.description as coursename',
					'tv_batch.id as batchid',
					'tv_batch.startdate',
					'tv_batch.enddate'
					)
				->join('studinfo','tv_enrolledstud.studid','=','studinfo.id')
				->join('tv_courses','tv_enrolledstud.courseid','=','tv_courses.id')
				->join('tv_batch','tv_enrolledstud.batchid','=','tv_batch.id')
				->where('tv_enrolledstud.deleted','0')
				->where('tv_courses.deleted','0')
				->where('tv_batch.deleted','0')
				->get();
		}elseif($request->get('courseid') != null && $request->get('batchid') == null){
			$techvocstudents = DB::table('tv_enrolledstud')
				->select(
					'tv_enrolledstud.id as techvocid',
					'tv_enrolledstud.status',
					'studinfo.id as studid',
					'studinfo.sid',
					'studinfo.lastname',
					'studinfo.middlename',
					'studinfo.firstname',
					'studinfo.suffix',
					'studinfo.gender',
					'tv_courses.id as courseid',
					'tv_courses.description as coursename',
					'tv_batch.id as batchid',
					'tv_batch.startdate',
					'tv_batch.enddate'
					)
				->join('studinfo','tv_enrolledstud.studid','=','studinfo.id')
				->join('tv_courses','tv_enrolledstud.courseid','=','tv_courses.id')
				->join('tv_batch','tv_enrolledstud.batchid','=','tv_batch.id')
				->where('tv_enrolledstud.deleted','0')
				->where('tv_courses.deleted','0')
				->where('tv_batch.deleted','0')
				->where('tv_courses.id',$request->get('courseid'))
				->get();

		}elseif($request->get('courseid') != null && $request->get('batchid') != null){
			$techvocstudents = DB::table('tv_enrolledstud')
				->select(
					'tv_enrolledstud.id as techvocid',
					'tv_enrolledstud.status',
					'studinfo.id as studid',
					'studinfo.sid',
					'studinfo.lastname',
					'studinfo.middlename',
					'studinfo.firstname',
					'studinfo.suffix',
					'studinfo.gender',
					'tv_courses.id as courseid',
					'tv_courses.description as coursename',
					'tv_batch.id as batchid',
					'tv_batch.startdate',
					'tv_batch.enddate'
					)
				->join('studinfo','tv_enrolledstud.studid','=','studinfo.id')
				->join('tv_courses','tv_enrolledstud.courseid','=','tv_courses.id')
				->join('tv_batch','tv_enrolledstud.batchid','=','tv_batch.id')
				->where('tv_enrolledstud.deleted','0')
				->where('tv_courses.deleted','0')
				->where('tv_batch.deleted','0')
				->where('tv_batch.id',$request->get('batchid'))
				->get();
		}
		if(count($techvocstudents) > 0)
		{
			foreach($techvocstudents as $techvocstudent){
				array_push($students, $techvocstudent);
			}
		}

		$name = $request->get('name');
		if($name == null)
		{
			$filteredstudents = $students;
		}else{
			$filteredstudents = collect($students)->filter(function ($value, $key) use($name) {
				// dd($value->mol);
				if(strpos($value->lastname,$name) !== false || strpos( $value->middlename,$name) !== false || strpos( $value->firstname,$name) !== false){
					return $value;
				}
			});
			$filteredstudents = $filteredstudents->flatten();
		}
		$displaytable = "";
		if(count($filteredstudents) == 0)
		{
			$display = '<tr>'.
							'<td coslpan="6" class="text-center">No student found</td>'.
						'</tr>';

			$displaytable = $display;
		}else{
			foreach($filteredstudents as $student)
			{
				$display = '<tr class="studentrow" data-toggle="modal" data-target="#viewei">';
				if($student->status == 1)
				{
					$buttoncontent = 'Enrolled';
				}else{
					$buttoncontent = '&nbsp;';
				}
				$display .= '<td>'.$student->sid.'</td>'.
							'<td>'.$student->lastname.', '.$student->firstname.' '.$student->middlename.' '.$student->suffix.'</td>'.
							'<td>'.$student->gender.'</td>'.
							'<td>'.$student->coursename.'</td>'.
							'<td>'.$student->startdate.' - '.$student->enddate.'</td>'.
							'<td><button id="'.$student->techvocid.'" type="button" class="btn btn-sm btn-success btn-block viewinfo">'.$buttoncontent.'</button></td>'.
							'</tr>';
				$displaytable.=$display;
			}
		}
		return $displaytable;

	}

	public function tvloadstudinfo(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');


			$stud = db::table('studinfo')
				->where('id', $studid)
				->first();

			$dob = date_create($stud->dob);
			$dob = date_format($dob, 'm/d/Y');

			$data = array(
				'gender' => $stud->gender,
				'dob' => $dob,
				'nationality' => $stud->nationality,
				'contactno' => $stud->contactno,
				'street' => $stud->street,
				'barangay' => $stud->barangay,
				'city' => $stud->city,
				'province' => $stud->province

			);


			echo json_encode($data);

		}
	}

	public function tvcreatestudinfo(Request $request)
	{
        $lastname = $request->get('lastname');
        $firstname = $request->get('firstname');
        $middlename = $request->get('middlename');
        $suffix = $request->get('suffix');
        $gender = $request->get('gender');
        $dob = $request->get('dob');
        $nationality = $request->get('nationality');
        $contactno = $request->get('contactno');

        $street = $request->get('street');
        $barangay = $request->get('barangay');
        $city = $request->get('city');
        $province = $request->get('province');

        $fname = $request->get('fname');
        $foccupation = $request->get('foccupation');
        $fcontactno = $request->get('fcontactno');
        $fnum = $request->get('fnum');

        $mname = $request->get('mname');
        $moccupation = $request->get('moccupation');
        $mcontactno = $request->get('mcontactno');
        $mnum = $request->get('mnum');

        $gname = $request->get('gname');
        $grelation = $request->get('grelation');
        $gcontactno = $request->get('gcontactno');
        $gnum = $request->get('gnum');

        $studinfo = db::table('studinfo')
            ->where('lastname', $lastname)
            ->where('firstname', $firstname)
            ->where('middlename', $middlename)
            ->where('dob', $dob)
            ->count();
        // return $studinfo;
        if($studinfo > 0)
        {
            return 0;
        }
        else
        {
            $studid = db::table('studinfo')
                ->insertGetId([
                    'lastname' => $lastname,
                    'firstname' => $firstname,
                    'middlename' => $middlename,
                    'suffix' => $suffix,
                    'gender' => $gender,
                    'dob' => $dob,
                    'nationality' => $nationality,
                    'contactno' => $contactno,
                    'street' => $street,
                    'barangay' => $barangay,
                    'city' => $city,
                    'province' => $province,
                    'fathername' => $fname,
                    'foccupation' => $foccupation,
                    'fcontactno' => $fcontactno,
                    'isfathernum' => $fnum,
                    'mothername' => $mname,
                    'moccupation' => $moccupation,
                    'mcontactno' => $mcontactno,
                    'ismothernum' => $mnum,
                    'guardianname' => $gname,
                    'guardianrelation' => $grelation,
                    'gcontactno' => $fcontactno,
                    'isguardannum' => $gnum,
                    'deleted' => '0'
                ]);

            // $idprefix = db::table('idprefix')->first();
        
            $id = sprintf('%06d', $studid);

            $yr = date_create(RegistrarModel::getServerDateTime());
            $yr = date_format($yr, 'y');

            $sid = 1 . $yr . 7 .  $id;

            DB::table('studinfo')
                ->where('id', $studid)
                ->update([
                    'sid' => $sid
                ]);

            return '1';
        }
        
    }
    public function tvenrollstudent(Request $request)
    {
        
        $checkifexists = DB::table('tv_enrolledstud')
            ->where('studid', $request->get('id'))
            ->where('courseid', $request->get('courseid'))
            ->where('batchid', $request->get('batchid'))
            ->where('deleted','0')
            ->count();

        if($checkifexists == 0)
        {
            $newstudid = DB::table('tv_enrolledstud')
                ->insertGetId([
                    'studid'            => $request->get('id'),
                    'courseid'          => $request->get('courseid'),
                    'batchid'           => $request->get('batchid'),
                    'dateenrolled'      => RegistrarModel::getServerDateTime(),
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => RegistrarModel::getServerDateTime()
                ]);

            $studentinfo = DB::table('studinfo')
                ->select(
                    'id',
                    'sid',
                    'lastname',
                    'middlename',
                    'firstname',
                    'suffix',
                    'gender'
                    )
                ->where('id', $request->get('id'))
                ->first();

            $studentinfo->courseinfo = DB::table('tv_courses')
                    ->where('id', $request->get('courseid'))
                    ->first();

            $studentinfo->batchinfo = DB::table('tv_batch')
                    ->where('id', $request->get('batchid'))
                    ->first();

            $studentinfo->techvocid = $newstudid;

            if($studentinfo->middlename == null)
            {
                $studentinfo->middlename = "";
            }
            if($studentinfo->suffix == null)
            {
                $studentinfo->suffix = "";
            }
            return collect($studentinfo);

        }else{
            return '1';
        }
    }
    public function tvgetbatch(Request $request)
    {
		// return $request->all();
		if($request->get('id') == null)
		{
			$empty = [];
			$data = array($empty);
			$students = DB::table('tv_enrolledstud')
				->select(
					'tv_enrolledstud.id as techvocid',
					'tv_enrolledstud.status',
					'studinfo.id as studid',
					'studinfo.sid',
					'studinfo.lastname',
					'studinfo.middlename',
					'studinfo.firstname',
					'studinfo.suffix',
					'studinfo.gender',
					'tv_enrolledstud.courseid',
					'tv_enrolledstud.batchid'
				)
				->leftjoin('studinfo','tv_enrolledstud.studid','=','studinfo.id')
				// ->leftjoin('tv_courses','tv_enrolledstud.courseid','=','tv_courses.id')
				// ->leftjoin('tv_batch','tv_enrolledstud.batchid','=','tv_batch.id')
				// ->where('tv_courses.deleted','0')
				// ->where('tv_batch.deleted','0')
				->get();
			if(count($students)>0)
			{
				foreach($students as $student)
				{
					$studcourse = DB::table('tv_courses')
						->where('id', $student->courseid)
						->where('deleted','0')
						->get();
	
					if(count($studcourse)>0)
					{
						$student->coursename = $studcourse[0]->description;
					}else{
						$student->coursename = null;
					}
	
					$studbatch = DB::table('tv_batch')
						->where('id', $student->batchid)
						->where('deleted','0')
						->get();
						
					if(count($studbatch)>0)
					{
						$student->startdate = $studbatch[0]->startdate;
						$student->enddate = $studbatch[0]->enddate;
					}else{
						$student->startdate = null;
						$student->enddate = null;
					}
					
					if($student->startdate!=null && $student->enddate!=null)
					{
						$student->batchdates = date('M d, Y', strtotime($student->startdate)).' - '.date('M d, Y', strtotime($student->enddate));
					}else{
						$student->batchdates = "";
					}

					if($student->middlename == null)
					{
						$student->middlename = "";
					}
					if($student->suffix == null)
					{
						$student->suffix = "";
					}
				}
			}
			array_push($data, $students);
			return $data;
		}else{
			$batches = Db::table('tv_batch')
				->select('id','startdate','enddate')
				->where('courseid', $request->get('id'))
				->where('isactive', 1)
				->where('deleted', 0)
				->get();
			if(count($batches)>0)
			{
				foreach($batches as $batch)
				{
					$batch->startdate_str = date('M d, Y', strtotime($batch->startdate));
					$batch->enddate_str = date('M d, Y', strtotime($batch->enddate));
				}
			}
			if($request->get('withstudents'))
			{
				$data = array();
				array_push($data, $batches);
	
				$students = DB::table('tv_enrolledstud')
					->select(
						'tv_enrolledstud.id as techvocid',
						'studinfo.sid',
						'studinfo.lastname',
						'studinfo.middlename',
						'studinfo.firstname',
						'studinfo.suffix',
						'studinfo.gender',
						'tv_courses.description as coursename',
						'tv_batch.startdate',
						'tv_batch.enddate',
						'tv_enrolledstud.status'
					)
					->leftjoin('studinfo','tv_enrolledstud.studid','=','studinfo.id')
					->leftjoin('tv_courses','tv_enrolledstud.courseid','=','tv_courses.id')
					->leftjoin('tv_batch','tv_enrolledstud.batchid','=','tv_batch.id')
					->where('tv_enrolledstud.courseid',$request->get('id'))
					->where('tv_courses.deleted','0')
					->where('tv_batch.deleted','0')
					->get();
				if(count($students)>0)
				{
					foreach($students as $student)
					{
						if($student->middlename == null)
						{
							$student->middlename = "";
						}
						if($student->suffix == null)
						{
							$student->suffix = "";
						}
					}
				}
				array_push($data, $students);
				return $data;
			}else{
				return collect($batches);
			}
		}
	}
	public function tvexport(Request $request)
	{
		// return $request->all();
		$techvocids = explode(",",$request->get('techvocids')); 

		$techvocids = collect($techvocids);

		$data = array();
		foreach($techvocids as $id)
		{
			$info = DB::table('tv_enrolledstud')
				->select(
					'studinfo.sid',
					'studinfo.lastname',
					'studinfo.middlename',
					'studinfo.firstname',
					'studinfo.suffix',
					'studinfo.gender',
					'tv_courses.description as coursename',
					'tv_batch.startdate',
					'tv_batch.enddate',
					'tv_enrolledstud.status'
				)
				->leftjoin('studinfo','tv_enrolledstud.studid','=','studinfo.id')
				->leftjoin('tv_courses','tv_enrolledstud.courseid','=','tv_courses.id')
				->leftjoin('tv_batch','tv_enrolledstud.batchid','=','tv_batch.id')
				->where('tv_enrolledstud.id',$id)
				->where('tv_courses.deleted','0')
				->where('tv_batch.deleted','0')
				->first();

			array_push($data, $info);
		}
        $schoolinfo = DB::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'refcitymun.citymunDesc as division',
                'schoolinfo.district',
                'schoolinfo.address',
                'schoolinfo.picurl',
                'refregion.regDesc as region'
            )
            ->join('refregion','schoolinfo.region','=','refregion.regCode')
            ->join('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->first();
		$coursename = "";
		$batch = "";
		if($request->get('courseid') != null)
		{
			$coursename = DB::table('tv_courses')
				->where('id', $request->get('courseid'))
				->first()
				->description;
		}
		if($request->get('batchid') != null)
		{
			$batchinfo = DB::table('tv_batch')
				->where('id', $request->get('batchid'))
				->first();
			$batch = $batchinfo->startdate.' - '.$batchinfo->enddate;
		}
		
		if($request->get('exporttype') == 'pdf')
		{
			$pdf = PDF::loadview('enrollment/pdf/techvocstudents',compact('data','schoolinfo','coursename','batch'))->setPaper('a4');
			$pdf->getDomPDF()->set_option("enable_php", true);
			 return $pdf->stream('Tech-Voc Students.pdf');
		}
		elseif($request->get('exporttype') == 'excel')
		{
			// return 'excel';
			$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			if($coursename == "")
			{
				$sheet
					->setCellValue('A1', 'NO')
					->setCellValue('B1', 'SID')
					->setCellValue('C1', 'NAME')
					->setCellValue('D1', 'GENDER')
					->setCellValue('E1', 'COURSE')
					->setCellValue('F1', 'BATCH')
					->setCellValue('G1', 'STATUS');
					
				$count = 2;
			}else{
				$sheet->mergeCells('A1:G1');
				$sheet->getCell('A1')
					->setValue($coursename);
				$sheet->getStyle('A1')
					->getAlignment()->applyFromArray( [ 'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE ] );
				if($batch == "")
				{
					$sheet
						->setCellValue('A2', 'NO')
						->setCellValue('B2', 'SID')
						->setCellValue('C2', 'NAME')
						->setCellValue('D2', 'GENDER')
						->setCellValue('E2', 'COURSE')
						->setCellValue('F2', 'BATCH')
						->setCellValue('G2', 'STATUS');
						
					$count = 3;
				}else{
					$sheet->mergeCells('A2:G2');
					$sheet->getCell('A2')
						->setValue($batch);
					$sheet->getStyle('A2')
						->getAlignment()->applyFromArray( [ 'horizontal' =>  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE ] );
					$sheet
						->setCellValue('A3', 'NO')
						->setCellValue('B3', 'SID')
						->setCellValue('C3', 'NAME')
						->setCellValue('D3', 'GENDER')
						->setCellValue('E3', 'COURSE')
						->setCellValue('F3', 'BATCH')
						->setCellValue('G3', 'STATUS');
						
					$count = 4;
				}
			}
			$no = 1;

			foreach($data as $studentinfo)
			{
				if($studentinfo->status == 1)
				{
					$status = 'ENROLLED';
				}else{
					$status = '';
				}
				$sheet
					->setCellValue('A'.$count, $no)
					->setCellValue('B'.$count, ' '.$studentinfo->sid.' ')
					->setCellValue('C'.$count, $studentinfo->lastname.', '.$studentinfo->firstname.' '.$studentinfo->middlename.' '.$studentinfo->suffix)
					->setCellValue('D'.$count, $studentinfo->gender)
					->setCellValue('E'.$count, $studentinfo->coursename)
					->setCellValue('F'.$count, $studentinfo->startdate.' - '.$studentinfo->enddate)
					->setCellValue('G'.$count, $status);

				$count+=1;
				$no+=1;
			}
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="Tech-Voc Students.xlsx"');
			$writer->save("php://output");

		}
	}
	public function tvgetstudinfo(Request $request)
	{
		// if($request->ajax())
		// {
			if($request->has('action'))
			{
				if($request->get('action') == 'info-edit')
				{
					$studinfo = DB::table('tv_studinfo')
						->where('id', $request->get('id'))
						->first();

					$nationalities = DB::table('nationality')
						->where('deleted','0')
						->get();

					// return collect($studinfo);
					return view('enrollment.techvoc.infoedit')
						->with('nationalities', $nationalities)
						->with('studinfo', $studinfo);
				}
			}else{
				$info = DB::table('tv_enrolledstud')
					->select(
						'tv_enrolledstud.id as enrolledstudid',
						'tv_studinfo.id as studid',
						'tv_studinfo.sid',
						'tv_studinfo.lastname',
						'tv_studinfo.middlename',
						'tv_studinfo.firstname',
						'tv_studinfo.suffix',
						'tv_studinfo.gender',
						'tv_enrolledstud.courseid',
						'tv_enrolledstud.batchid',
						// 'tv_courses.id as courseid',
						// 'tv_courses.description as coursename',
						// 'tv_batch.id as batchid',
						// 'tv_batch.startdate',
						// 'tv_batch.enddate',
						'tv_enrolledstud.status',
						'tv_enrolledstud.createddatetime',
						'users.name'
					)
					->join('tv_studinfo','tv_enrolledstud.studid','=','tv_studinfo.id')
					->join('tv_courses','tv_enrolledstud.courseid','=','tv_courses.id')
					->join('tv_batch','tv_enrolledstud.batchid','=','tv_batch.id')
					->leftjoin('users','tv_enrolledstud.createdby','=','users.id')
					->where('tv_enrolledstud.id',$request->get('id'))
					->where('tv_enrolledstud.deleted','0')
					->where('tv_courses.deleted','0')
					->where('tv_batch.deleted','0')
					->first();
		
				$studcourse = DB::table('tv_courses')
					->where('id', $info->courseid)
					->where('deleted','0')
					->get();
	
				if(count($studcourse)>0)
				{
					$info->coursename = $studcourse[0]->description;
				}else{
					$info->coursename = null;
				}
				

				$studbatch = DB::table('tv_batch')
					->where('id', $info->batchid)
					->where('deleted','0')
					->get();
					
				if(count($studbatch)>0)
				{
					$info->startdate = $studbatch[0]->startdate;
					$info->enddate = $studbatch[0]->enddate;
				}else{
					$info->startdate = null;
					$info->enddate = null;
				}
	
				if($info->middlename == null)
				{
					$info->middlename = "";
				}
				if($info->suffix == null)
				{
					$info->suffix = "";
				}
				if($info->startdate!=null && $info->enddate!=null)
				{
					$info->batchdates = date('M d, Y', strtotime($info->startdate)).' - '.date('M d, Y', strtotime($info->enddate));
				}else{
					$info->batchdates = "";
				}
				$info->createddatetime = date('M d, Y', strtotime($info->createddatetime));
	
				$courses = DB::table('tv_courses')
					->where('deleted','0')
					->get();
	
					if(count($courses)>0)
					{
						foreach($courses as $course)
						{
							if($course->id == $info->courseid)
							{
								$course->selected = 'selected';
							}else{
	
								$course->selected = '';
							}
						}
					}
				$batches = DB::table('tv_batch')
				->where('courseid', $info->courseid)
				->where('deleted','0')
				->get();
					
				if(count($batches)>0)
				{
					foreach($batches as $batch)
					{
						if($batch->startdate!=null && $batch->enddate!=null)
						{
							$batch->batchdates = date('M d, Y', strtotime($batch->startdate)).' - '.date('M d, Y', strtotime($batch->enddate));
						}else{
							$batch->batchdates = "";
						}
						if($batch->id == $info->batchid)
						{
							$batch->selected = 'selected';
						}else{

							$batch->selected = '';
						}
					}
				}
				$data = (object)array(
					'enrollmentinfo' 	=> $info,
					'courses'			=> $courses,
					'batches'			=> $batches
				);
				return collect($data);
			}
		// }
	}
	public function tvgetstudbybatch(Request $request)
	{
		$students = DB::table('tv_enrolledstud')
			->select(
				'tv_enrolledstud.id as techvocid',
				'studinfo.sid',
				'studinfo.lastname',
				'studinfo.middlename',
				'studinfo.firstname',
				'studinfo.suffix',
				'studinfo.gender',
				'tv_courses.description as coursename',
				'tv_batch.startdate',
				'tv_batch.enddate',
				'tv_enrolledstud.status'
			)
			->leftjoin('studinfo','tv_enrolledstud.studid','=','studinfo.id')
			->leftjoin('tv_courses','tv_enrolledstud.courseid','=','tv_courses.id')
			->leftjoin('tv_batch','tv_enrolledstud.batchid','=','tv_batch.id')
			->where('tv_enrolledstud.batchid',$request->get('id'))
			->where('tv_courses.deleted','0')
			->where('tv_batch.deleted','0')
			->get();
	}

}
