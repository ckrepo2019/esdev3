<?php

namespace App\Http\Controllers\enrollment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\RegistrarModel;
use App\Models\Grading\GradingReport;
use App\SyncModel;
use PDF;
use Illuminate\Support\Facades\Hash;

class EnrollmentController extends Controller
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
	  		

	  		

	  		$acadprog = RegistrarModel::activeacadprog();
	  		// return $glevel;

	  		
    		$student = db::table('studinfo')
				->select('studinfo.*', 'gradelevel.levelname', 'studinfo.sectionname as secname', 'grantee.description as grantee', 'studentstatus.description')
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
				->where(function($q) use($query){
					$q->orWhere('lastname', 'like', '%'.$query.'%')
						->orWhere('firstname', 'like', '%'.$query.'%')
						->orWhere('sid', 'like', '%'.$query.'%');
				})
				->where('studinfo.deleted', 0)
				->orderBy('lastname','ASC')
				->orderBy('firstname','ASC')
				->get();
	  		
	  		$output = '';

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
		  					<td>'.strtoupper($s->gender).'</td>
		  					<td>'.$s->levelname.'</td>
		  					<td>'.mb_strimwidth($s->secname, 0, 11, "...").'</td>
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
		  					<td>'.strtoupper($s->gender).'</td>
		  					<td>'.$s->levelname.'</td>
		  					<td data-toggle="tooltip" title="'.$s->secname.'">'.mb_strimwidth($s->secname, 0, 11, "...").'</td>
		  					<td>'.$s->grantee.'</td>
		  					<td>'.$s->description.'</td>
		  				</tr>

		  			';	
		  		}
	  		}
	  		// return $output;
	  		$data = array(
	  			'output' => $output,
	  			
	  			'glevel' => $glevel
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
	  		$lastschoolsy = $request->get('lastschoolsy');

	  		$isfather = $request->get('isfather');
	  		$ismother = $request->get('ismother');
	  		$isguardian = $request->get('isguardian');
	  		$studtype = $request->get('studtype');
	  		$pantawid = $request->get('pantawid');

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
  					'lastschoolsy' => $lastschoolsy,
  					'updateddatetime' => RegistrarModel::getServerDateTime(),
  					'updatedby' => auth()->user()->id,
  					'studtype' => $studtype,
  					'pantawid' => $pantawid
  				]);
	  	}
	 }
		public function studentprint1(Request $request)
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
	  
		public function studentprint(Request $request)
		{
		//   return $request->all();
			date_default_timezone_set('Asia/Manila');

			$studentinfo = DB::table('studinfo')
							->leftJoin('gradelevel','studinfo.levelid','=','gradelevel.id')
							->leftJoin('sh_strand','studinfo.strandid','=','sh_strand.id')
							->leftJoin('ethnic','studinfo.egid','=','ethnic.id')
							->leftJoin('religion','studinfo.religionid','=','religion.id')
							->leftJoin('nationality','studinfo.nationality','=','nationality.id')
							->leftJoin('mothertongue','studinfo.mtid','=','mothertongue.id')
							->leftJoin('grantee','studinfo.grantee','=','grantee.id')
							// ->leftJoin('sections','studinfo.sectionid','=','sections.id')
							->where('studinfo.id', $request->get('studid'))
							->where('gradelevel.deleted', '0')
							// ->where('sections.deleted', '0')
							->first();
							
							// return collect($studentinfo);
			$schoolinfo = DB::table('schoolinfo')
				->select(
					'schoolinfo.schoolid',
					'schoolinfo.schoolname',
					'schoolinfo.authorized',
					'refcitymun.citymunDesc as division',
					'schoolinfo.district',
					'schoolinfo.picurl',
					'schoolinfo.address',
					'refregion.regDesc as region',
					'schoolinfo.abbreviation'
				)
				->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
				->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
				->first();

			$studentinfo->dob = date('F d, Y', strtotime($studentinfo->dob));

			$checkmoreinfo = DB::table('studinfo_more')
				->where('studid', $request->get('studid'))
				->where('deleted','0')
				->first();

			$schoollastattended = '';
			if($checkmoreinfo)
			{
				$studentinfo->ffname  = $checkmoreinfo->ffname;
				$studentinfo->fmname  = $checkmoreinfo->fmname;
				$studentinfo->flname  = $checkmoreinfo->flname;
				$studentinfo->fsuffix = $checkmoreinfo->fsuffix;
				$studentinfo->mfname  = $checkmoreinfo->mfname;
				$studentinfo->mmname  = $checkmoreinfo->mmname;
				$studentinfo->mlname  = $checkmoreinfo->mlname;
				$studentinfo->msuffix = $checkmoreinfo->msuffix;
				$studentinfo->gfname  = $checkmoreinfo->gfname;
				$studentinfo->gmname  = $checkmoreinfo->gmname;
				$studentinfo->glname  = $checkmoreinfo->glname;
				$studentinfo->gsuffix = $checkmoreinfo->gsuffix;

				$studentinfo->fha  = $checkmoreinfo->fha;
				$studentinfo->mha  = $checkmoreinfo->mha;
				$studentinfo->gha = $checkmoreinfo->gha;

				$studentinfo->fea  = $checkmoreinfo->fea;
				$studentinfo->mea  = $checkmoreinfo->mea;
				$studentinfo->gea = $checkmoreinfo->gea;
				
				$studentinfo->nocitf = $checkmoreinfo->nocitf;
				$studentinfo->noce  = $checkmoreinfo->noce;
				$studentinfo->oitfitf  = $checkmoreinfo->oitfitf;
				
				if($checkmoreinfo->glits != null){
					$studentinfo->glits = DB::table('gradelevel')->where('id',$checkmoreinfo->glits)->first()->levelname;
				}else{
					$studentinfo->glits = null;
				}
		
				$studentinfo->scn = $checkmoreinfo->scn;
				$studentinfo->lsah = $checkmoreinfo->lsah;
				$studentinfo->cmaosla = $checkmoreinfo->cmaosla;

				if(strtolower($studentinfo->studtype) == 'old')
				{
					$schoollastattended = DB::table('schoolinfo')
						->first()->schoolname;
				}else{
					
					if($checkmoreinfo->gsschoolname != null)
					{
						$schoollastattended = $checkmoreinfo->gsschoolname;
					}
					if($checkmoreinfo->jhsschoolname != null)
					{
						$schoollastattended = $checkmoreinfo->jhsschoolname;
					}
					if($checkmoreinfo->shsschoolname != null)
					{
						$schoollastattended = $checkmoreinfo->shsschoolname;
					}

					if($studentinfo->lastschoolatt != null){
						$schoollastattended = $studentinfo->lastschoolatt;
					}
				}
			}else{
				$studentinfo->ffname  = null;
				$studentinfo->fmname  = null;
				$studentinfo->flname  = null;
				$studentinfo->fsuffix = null;
				$studentinfo->mfname  = null;
				$studentinfo->mmname  = null;
				$studentinfo->mlname  = null;
				$studentinfo->msuffix = null;
				$studentinfo->gfname  = null;
				$studentinfo->gmname  = null;
				$studentinfo->glname  = null;
				$studentinfo->gsuffix = null;
				
				$studentinfo->nocitf = null;
				$studentinfo->noce  = null;
				$studentinfo->oitfitf  = null;
				$studentinfo->glits = null;
				$studentinfo->lsah = null;
				$studentinfo->scn = null;
				$studentinfo->cmaosla = null;

				$studentinfo->fha  = null;
				$studentinfo->mha  = null;
				$studentinfo->gha = null;

				$studentinfo->fea  = null;
				$studentinfo->mea  = null;
				$studentinfo->gea = null;
				
			}
			$studentinfo->schoollastattended = $schoollastattended;

			

							
			// foreach($studentinfo as $studinfo){

			// 	foreach($studinfo as $key => $value){

			// 		if($key == 'dob'){

			// 			$studinfo->dob = date('F d, Y', strtotime($value));

			// 		}					

			// 	}

			// }


			if(DB::table('schoolinfo')->first()->schoolid == '404834') //apmc
			{
				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				// set document information
				$pdf->SetCreator('CK');
				$pdf->SetAuthor('CK Children\'s Publishing');
				// $pdf->SetTitle($schoolinfo->schoolname.' - Number of Enrollees');
				$pdf->SetSubject('Number of Enrollees');
				
				// set header and footer fonts
				$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
				$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
				
				// set default monospaced font
				$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
				
				// set margins
				$pdf->SetMargins(20, 9, 20);
				// $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
				// $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				
				// $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(255, 0, 0)));
				// set auto page breaks
				$pdf->SetAutoPageBreak(TRUE, 0);
				
				// set image scale factor
				$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
				
				// set some language-dependent strings (optional)
				if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
					require_once(dirname(__FILE__).'/lang/eng.php');
					$pdf->setLanguageArray($l);
				}
				
				// ---------------------------------------------------------
				
				// set font
				$pdf->SetFont('dejavusans', '', 10);
				
				
				// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				// Print a table
				
				// add a page
				$pdf->AddPage('P','GOVERNMENTLEGAL');
				
				$view = \View::make('enrollment/pdf/studentinformation_apmc',compact('studentinfo','schoolinfo'));
				$html = $view->render();

				$pdf->writeHTML($html, true, false, false, false, '');
				
				$pdf->lastPage();
				
				// ---------------------------------------------------------
				//Close and output PDF document
				$pdf->Output('Enrollment Form '.$studentinfo->lastname.' - '.$studentinfo->firstname.'.pdf', 'I');
			}
			// else if(DB::table('schoolinfo')->first()->snr == 'ccsa') //apmc
			// {
			// 	$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			// 	$spreadsheet = $reader->load("CCSA-Student-Personal-Data.xlsx"); 


			// 	$sheet = $spreadsheet->setActiveSheetIndex(0);

				

			// 	$sheet->setCellValue('B6',$studentinfo->lastname);
			// 	$sheet->setCellValue('H6',$studentinfo->firstname);
			// 	$sheet->setCellValue('P6',$studentinfo->middleame);
			// 	$sheet->setCellValue('P6',$studentinfo->gender);

			// 	return collect($studentinfo);


			// 	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

			// 	header('Content-Type: application/vnd.ms-excel');
			// 	header('Content-Disposition: attachment; filename="Student-Personal-Data ('.$studentinfo->lastname.' - '.$studentinfo->firstname.').xlsx"');
			// 	$writer->save("php://output");
			// 	exit();

			// }
			else{
				// return collect($studentinfo);
				$pdf = PDF::loadview('enrollment/pdf/studentinformation_spct',compact('studentinfo','schoolinfo')); 
				return $pdf->stream('Student Information - '.$studentinfo->lastname.' - '.$studentinfo->firstname.'.pdf');
			// }else{
				// $pdf = PDF::loadview('enrollment/pdf/studentinformation',compact('studentinfo','schoolinfo'))->setPaper('8.5x11','portrait'); 
				// return $pdf->stream('Student Information - '.$studentinfo->lastname.' - '.$studentinfo->firstname.'.pdf');
			}

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

	// public function studentinsert(Request $request)
	// {
	  	// if($request->ajax())
	  	// {
	  		// $lrn = $request->get('lrn');
	  		// $grantee = $request->get('grantee');
	  		// $glevel = $request->get('glevel');
	  		// $fname = $request->get('fname');
	  		// $mname = $request->get('mname');
	  		// $lname = $request->get('lname');
	  		// $suffix = $request->get('suffix');
	  		// $dob = $request->get('dob');
	  		// $gender = $request->get('gender');
	  		// $contactno = $request->get('contactno');
	  		// $religion = $request->get('religion');
	  		// $mt = $request->get('mt');
	  		// $eg = $request->get('eg');
	  		// $street = $request->get('street');
	  		// $barangay = $request->get('barangay');
	  		// $city = $request->get('city');
	  		// $province = $request->get('province');
	  		// $fathername = $request->get('fathername');
	  		// $foccupation = $request->get('foccupation');
	  		// $fcontactno = $request->get('fcontactno');
	  		// $mothername = $request->get('mothername');
	  		// $moccupation = $request->get('moccupation');
	  		// $mcontactno = $request->get('mcontactno');
	  		// $guardianname = $request->get('guardianname');
	  		// $guardianrelation = $request->get('guardianrelation');
	  		// $gcontactno = $request->get('gcontactno');
	  		// $bloodtype = $request->get('bloodtype');
	  		// $allergy = $request->get('allergy');
	  		// $others = $request->get('others');
	  		// $rfid = $request->get('rfid');
	  		// $mol = $request->get('mol');
	  		// $nationality = $request->get('nationality');
	  		// $lastschool = $request->get('lastschool');
	  		// $lastschoolsy = $request->get('lastschoolsy');

	  		// $isfather = $request->get('isfather');
	  		// $ismother = $request->get('ismother');
	  		// $isguardian = $request->get('isguardian');
	  		// $studtype = $request->get('studtype');
	  		// $pantawid = $request->get('pantawid');


	  		// $stud = db::table('studinfo')
	  			// ->where('lastname', $lname)
	  			// ->where('firstname', $fname)
	  			// ->first();
	  		
	  		// if(!$stud)
	  		// {
		  		// $studid = DB::table('studinfo')
	  				// ->insertGetId([
	  					// 'lrn' => $lrn,
	  					// 'grantee' => $grantee,
	  					// 'levelid' => $glevel,
	  					// 'firstname' => $fname,
	  					// 'middlename' => $mname,
	  					// 'lastname' => $lname,
	  					// 'suffix' => $suffix,
	  					// 'dob' => $dob,
	  					// 'gender' => $gender,
	  					// 'contactno' => $contactno,
	  					// 'religionid' => $religion,
	  					// 'mtid' => $mt,
	  					// 'egid' => $eg,
	  					// 'street' => $street,
	  					// 'barangay' => $barangay,
	  					// 'city' => $city,
	  					// 'province' => $province,
	  					// 'fathername' => $fathername,
	  					// 'foccupation' => $foccupation,
	  					// 'fcontactno' => $fcontactno,
	  					// 'isfathernum' => $isfather,
	  					// 'mothername' => $mothername,
	  					// 'moccupation' => $moccupation,
	  					// 'mcontactno' => $mcontactno,
	  					// 'ismothernum' => $ismother,
	  					// 'guardianname' => $guardianname,
	  					// 'guardianrelation' => $guardianrelation,
	  					// 'gcontactno' => $gcontactno,
	  					// 'isguardannum' => $isguardian,
	  					// 'bloodtype' => $bloodtype,
	  					// 'allergy' => $allergy,
	  					// 'others' => $others,
	  					// 'rfid' => $rfid,
	  					// 'mol' => $mol,
	  					// 'nationality' => $nationality,
	  					// 'lastschoolatt' => $lastschool,
	  					// 'lastschoolsy' => $lastschoolsy,
	  					// 'deleted' => 0,
	  					// 'studtype' => $studtype,
	  					// 'pantawid' => $pantawid,
	  					// 'createddatetime' => RegistrarModel::getServerDateTime(),
	  					// 'createdby' => auth()->user()->id
	  				// ]);

		  		// $acadid = db::table('gradelevel')
		  			// ->where('id', $glevel)
		  			// ->first()
		  			// ->acadprogid;	  		
		  		
		  		// $sid = RegistrarModel::idprefix($acadid, $studid);

		  		// $sid = $idprefix->prefix . $id;

		  		// $upd = db::table('studinfo')
		  			// ->where('id', $studid)
		  			// ->update([
		  				// 'sid' => $sid
		  			// ]);

		  		// return 'done';

	  		// }
	  		// else
	  		// {
	  			// return 'exist';
	  		// }
	  	// }
	// }

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

	  		

	  		$syid = $request->get('syid');
	  		$semid = $request->get('semid');

	  		$studentstatus = db::table('studentstatus')
	  				->get();

	  		$student = db::table('studinfo')
	  				->where('id', $studid)
	  				->first();

	  		$studlevelid = $student->levelid;

	  		// $glvl = $student->levelid;
	  		$glvl = $curlevelid;

	  		// return $syid . ' ' .  $semid;

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
					->where('deleted', 0)
					->where(function($q) use($studlevelid) {
						$gradelevel = db::table('gradelevel')
							->where('id', $studlevelid)
							->where('deleted', 0)
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
		  			$studCount = db::table('sh_enrolledstud')
		  				->select(db::raw('rooms.capacity, COUNT(DISTINCT sh_enrolledstud.studid) AS studcount'))
		  				->join('sections', 'sh_enrolledstud.sectionid', 'sections.id')
		  				->join('rooms', 'sections.roomid', '=', 'rooms.id')
		  				->where('sh_enrolledstud.deleted', 0)
		  				->where('sectionid', $sec->id)
		  				->where('syid', $syid)
		  				->where(function($q) use($semid) {
		  					if(db::table('schoolinfo')->first()->shssetup == 0)
		  					{
		  						$q->where('semid', $semid);
		  					}
		  				})
		  				->get();

		  			$strandid = $student->strandid;
	  			}
	  			elseif($lID >= 17 && $lID <= 21)
	  			{
	  				$studCount = db::select('SELECT rooms.capacity, COUNT(DISTINCT sh_enrolledstud.studid) AS studcount 
		  					FROM college_enrolledstud 
		  					INNER JOIN sections ON sh_enrolledstud.sectionid = sections.id 
		  					INNER JOIN rooms ON sections.roomid = rooms.id 
		  					WHERE college_enrolledstud.deleted = 0 and sectionid = ? and semid = ? and syid = ?', [$sec->id, $semid, $syid]);

	  			}
	  			else
	  			{
	  				$studCount = db::select('SELECT rooms.capacity, COUNT(DISTINCT enrolledstud.studid) AS studcount 
		  					FROM enrolledstud 
		  					INNER JOIN sections ON enrolledstud.sectionid = sections.id 
		  					INNER JOIN rooms ON sections.roomid = rooms.id 
		  					WHERE enrolledstud.deleted = 0 and sectionid = ? and syid = ?', [$sec->id, $syid]);	
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
					// ->where('isactive', 1)
					->get();

				$enrollment = db::table('sh_enrolledstud')	
					->where('studid',  $student->id)
					->where('deleted', 0) 
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

				// $getblock = db::table('sh_block')
				// 	->where('strandid', $strandid)
				// 	->where('levelid', $studlevelid)
				// 	->where('deleted', 0)
				// 	->get();

				$getblock = RegistrarModel::getshblock($sectionid, $strandid);

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
				
				$temp_section = DB::table('studinfo')->where('id',$student->id)->first();
				$temp_sections = RegistrarModel::loadcollegeSection($courseid);
				
				
				if(isset($temp_section->sectionid) ){
					$temp_count = collect($temp_sections)->where('id',$temp_section->sectionid)->count();
					
					if($temp_count == 0){
						$temp_college_section = DB::table('college_sections')->where('id',$temp_section->sectionid)->first();
						if(isset($temp_college_section->id)){
							$sectionlist .='
								<option value="'.$temp_college_section->id.'">'.$temp_college_section->sectionDesc.'</option>
							';
						}
					}
				}

				

				$semesters = db::table('semester')
					// ->where('isactive', 1)
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
						->where('college_classsched.syID' , $syid)
						->where('college_classsched.semesterID' , $semid)
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
			  // \App\Models\Grading\GradingReport::student_promotion_filtered($studid = null, $syid = null,$semid = null)
			$promotion_sem = 0;
			$inc_list = '';
			$incomplete = false;

			// if($curlevelid < 17)
			// {
			// 	if(RegistrarModel::getSemID() == 2)
			// 	{
			// 		$promotion_sem = 1;
			// 	}

			// 	$promotion = GradingReport::student_promotion_filtered($studid, RegistrarModel::getSYID(), $promotion_sem);

			// 	// $promotion = json_encode($promotion);
			// 	// return $studid;
			// 	// return $promotion;

			// 	$incomplete = false;
			// 	// return $promotion;
			// 	if($promotion[0]->status == 1)
			// 	{
			// 		foreach($promotion[0]->incomplete_list as $incomplete_list)
			// 		{
			// 			// array_push($promotionlist, (object)[
			// 			// 	'subject' => $incomplete_list->subject,
			// 			// 	'quarter' => $incomplete_list->quarter,
			// 			// 	'grade' => $incomplete_list->grade
			// 			// ]);

			// 			$inc_list .= '
			// 				<tr>
			// 					<td>'.$incomplete_list->subject.'</td>
			// 					<td>'.$incomplete_list->quarter.'</td>
			// 					<td>'.$incomplete_list->grade.'</td>
			// 				</tr>
			// 			';
			// 		}

			// 		$incomplete = $promotion[0]->incomplete;
			// 	}
			// }

			// return $promotionlist;

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
	  			'sectionname' => $student->sectionname,
	  			'promotion_incomplete' => $incomplete,
	  			'inclist' => $inc_list
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

	  		$syid = $request->get('syid');
	  		$semid = $request->get('semid');

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
					->where('deleted', 0) 
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
					->where('deleted', 0) 
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
					->where('deleted', 0) 
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
			        // ->where('isactive', 1)
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
						->where('college_classsched.syID' , $syid)
						->where('college_classsched.semesterID' , $semid)
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
	  			if($s->id == $syid)
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
						// ->where('isactive', 1)
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

				$temp_section = DB::table('studinfo')->where('id',$student->id)->first();
				$temp_sections = RegistrarModel::loadcollegeSection($courseid);
				
				if(isset($temp_section->sectionid) ){
					$temp_count = collect($temp_sections)->where('id',$temp_section->sectionid)->count();
					
					if($temp_count == 0){
						$temp_college_section = DB::table('college_sections')->where('id',$temp_section->sectionid)->first();
						if(isset($temp_college_section->id)){
							$sectionlist .='
								<option value="'.$temp_college_section->id.'">'.$temp_college_section->sectionDesc.'</option>
							';
						}
					}
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
			$syid = $request->get('sy');
			$semid = $request->get('semid');
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


			if($glevel == 14 || $glevel == 15)
			{

				$enrollstud = db::table('sh_enrolledstud')
						->where('studid', $studid)
						->where('syid', $syid)
						->where('semid', $semid)
						->where('deleted', 0)
						->get();
				
				if(count($enrollstud) > 0)
				{
					$enrollid = $enrollstud[0]->id;
					$updEnrollStud = db::table('sh_enrolledstud')
							->where('id', $enrollstud[0]->id)
							->update([
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
		  					'syid' => $syid,
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
						->where('syid', $syid)
						->where('semid', $semid)
						->where('deleted', 0)
						->get();

				if(count($enrollstud) > 0)
				{
					$enrollid = $enrollstud[0]->id;
					$updEnrollStud = db::table('college_enrolledstud')
						->where('id', $enrollid)
						->update([
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
		  					'syid' => $syid,
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
						->where('syid', $syid)
						->where('deleted', 0)
						->get();

				if(count($enrollstud) > 0)
				{
					$enrollid = $enrollstud[0]->id;
					$updEnrollStud = db::table('enrolledstud')
						->where('id', $enrollid)
						->update([
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
	  					'syid' => $syid,
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

			$chkee_enroll = db::table('earlybirds')
				->where('studid', $studid)
				->where('syid', $syid)
				->where('semid', $semid)
				->first();

			if($chkee_enroll)
			{
				db::table('earlybirds')
					->where('id', $chkee_enroll->id)
					->update([
						'enrolled' => 1,
						'updatedby' => auth()->user()->id,
						'updateddatetime' => RegistrarModel::getServerDateTime()
					]);
			}

			$stud = db::table('studinfo')
				->where('id', $studid)
				->first();

			if(db::table('schoolinfo')->first()->paymentplan == 0)
			{
				if($glevel == 14 || $glevel == 15)
				{

			  		// $tuition = db::table('tuitionheader')
			  		// 	->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'grantee', 'levelid', 'grantee.description', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid', 'semid')
			  		// 	->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')	
			  		// 	->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
			  		// 	->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
			  		// 	->where('levelid', $glevel)
			  		// 	->where('syid', $syid)
			  		// 	->where('semid', $semid)
			  		// 	->where('grantee', $stud->grantee)
			  		// 	->where('strandid', $strandid)
			  		// 	->where('tuitionheader.deleted', 0)
			  		// 	->where('tuitiondetail.deleted', 0)
			  		// 	->get();

			  		$seltui = db::table('tuitionheader')
						->where('levelid', $glevel)
						->where('syid', $syid)
						->where('semid', $semid)
						->where('grantee', $stud->grantee)
			  			->where('strandid', $strandid)
			  			->where('deleted', 0)
						->first();

					if($seltui)
					{
						$tuition = db::table('tuitionheader')
							->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'grantee', 'levelid', 'grantee.description', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid', 'semid')
							->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')	
							->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
							->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
							->where('tuitionheader.id', $seltui->id)
							->where('tuitiondetail.deleted', 0)
							->get();
					}
					else
					{
						$seltui = db::table('tuitionheader')
							->where('levelid', $glevel)
							->where('syid', $syid)
							->where('semid', $semid)
							->where('deleted', 0)
							->first();

						if($seltui)
						{
							$tuition = db::table('tuitionheader')
								->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'grantee', 'levelid', 'grantee.description', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid', 'semid')
								->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')	
								->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
								->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
								->where('tuitionheader.id', $seltui->id)
								->where('tuitiondetail.deleted', 0)
								->get();
						}
					}
			  	}
			  	elseif($glevel >= 17 && $glevel <=20)
			  	{
			  		$seltui = db::table('tuitionheader')
						->where('levelid', $glevel)
						->where('syid', $syid)
						->where('semid', $semid)
						->where('courseid', $courseid)
						->where('deleted', 0)
						->first();					

					if($seltui)
					{
						$tuition = db::table('tuitionheader')
							->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'grantee', 'levelid', 'grantee.description', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid', 'semid', 'istuition')
							->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')	
							->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
							->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
							->where('tuitionheader.id', $seltui->id)
							->where('tuitiondetail.deleted', 0)
							->get();
					}
					else
					{
						$seltui = db::table('tuitionheader')
							->where('levelid', $glevel)
							->where('syid', $syid)
							->where('semid', $semid)
							->where('deleted', 0)
							->first();

						if($seltui)
						{
							$tuition = db::table('tuitionheader')
								->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'grantee', 'levelid', 'grantee.description', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid', 'semid', 'istuition')
								->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')	
								->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
								->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
								->where('tuitionheader.id', $seltui->id)
								->where('tuitiondetail.deleted', 0)
								->get();
						}
					}

			  		$totalunits = db::table('college_studsched')
						->select(db::raw('SUM(lecunits) + SUM(labunits) AS totalunits'))
						->join('college_classsched', 'college_studsched.schedid', '=', 'college_classsched.id')
						->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
						->where('college_studsched.studid', $studid)
						->where('college_studsched.deleted', 0)
						->where('college_classsched.syID', $syid)
						->where('college_classsched.semesterID', $semid)
						->first();

			  		if($totalunits)
		  			{
		  				$units = $totalunits->totalunits;
		  			}
		  			else
		  			{
		  				$units = 0;
		  			}

			  	}
			  	else
			  	{
			  		// $tuition = db::table('tuitionheader')
			  		// 	->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'syid', 'levelid', 'grantee.description', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid')
			  		// 	->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')	
			  		// 	->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
			  		// 	->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
			  		// 	->where('levelid', $glevel)
			  		// 	->where('syid', $syid)
			  		// 	->where('grantee', $stud->grantee)
			  		// 	->where('tuitionheader.deleted', 0)
			  		// 	->where('tuitiondetail.deleted', 0)
			  		// 	->get();	

			  		$seltui = db::table('tuitionheader')
						->where('levelid', $glevel)
						->where('syid', $syid)
						->where('grantee', $stud->grantee)
						->where('deleted', 0)
						->first();

					if($seltui)
					{
						$tuition = db::table('tuitionheader')
							->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'grantee', 'levelid', 'grantee.description', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid', 'semid')
							->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')	
							->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
							->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
							->where('tuitionheader.id', $seltui->id)
							->where('tuitiondetail.deleted', 0)
							->get();
					}
					else
					{
						$seltui = db::table('tuitionheader')
							->where('levelid', $glevel)
							->where('syid', $syid)
							->where('deleted', 0)
							->first();

						if($seltui)
						{
							$tuition = db::table('tuitionheader')
								->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'grantee', 'levelid', 'grantee.description', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid', 'semid')
								->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')	
								->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
								->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
								->where('tuitionheader.id', $seltui->id)
								->where('tuitiondetail.deleted', 0)
								->get();
						}
					}
			  	}
			}
			else
			{
				if($stud->feesid != null)
				{
					$tuition = db::table('tuitionheader')
			  			->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'syid', 'levelid', 'grantee.description', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid', 'semid')
			  			->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')	
			  			->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
			  			->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
			  			->where('tuitionheader.id', $stud->feesid)
			  			->where('tuitiondetail.deleted', 0)
			  			->get();
				}
				else
				{
					$seltui = db::table('tuitionheader')
						->where('levelid', $glevel)
						->where('syid', $syid)
						->where('deleted', 0)
						->first();

					if($seltui)
					{

						$tuition = db::table('tuitionheader')
							->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'grantee', 'levelid', 'grantee.description', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid', 'semid', 'istuition')
							->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')	
							->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
							->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
							->where('tuitionheader.id', $seltui->id)
							->where('tuitiondetail.deleted', 0)
							->get();
					}
				}


				if($glevel >= 17 && $glevel <=20)
				{
					$totalunits = db::table('college_studsched')
						->select(db::raw('SUM(lecunits) + SUM(labunits) AS totalunits'))
						->join('college_classsched', 'college_studsched.schedid', '=', 'college_classsched.id')
						->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
						->where('college_studsched.studid', $studid)
						->where('college_studsched.deleted', 0)
						->where('college_classsched.syID', $syid)
						->where('college_classsched.semesterID', $semid)
						->first();

			  		if($totalunits)
		  			{
		  				$units = $totalunits->totalunits;
		  			}
		  			else
		  			{
		  				$units = 0;
		  			}
		  		}
			}

			if(count($tuition) > 0)
			{
				$feesid = $tuition[0]->id;

				db::table('studinfo')
					->where('id', $studid)
					->update([
						'feesid' => $feesid
					]);
			}
				

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
						'syid' => $syid,
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
								'syid' => $syid,
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
		  							'syid' => $syid,
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
			  							'syid' => $syid,
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
		  							'syid' => $syid,
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
		  			$curAmount = $tuitionamount;

		  			foreach($paymentsetup as $pay)
		  			{
		  				$paycount +=1;
		  				if($paycount < count($paymentsetup))
		  				{
		  					if($curAmount > 0)
		  					{
			  					$pAmount = round($pay->percentamount * ($tuitionamount/100), 2);
			  					$curAmount = (round($curAmount - $pAmount, 2));

			  					$scheditem = db::table('studpayscheddetail')
			  					->insert([
			  							'studid' => $studid,
			  							'enrollid' => $enrollid,
			  							'syid' => $syid,
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
			  							'syid' => $syid,
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
			$ee_amount = 0;


			if($glevel == 14 || $glevel == 15)
			{
				$levelid = $glevel;

				if(db::table('schoolinfo')->first()->cashierversion == 1)
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
				}
				else
				{
					$ee_amount = RegistrarModel::checkEE($studid, $syid, $semid);

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

					$dpclass_array = array();

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
						->groupBy('classid')
						->get();

					foreach($getdp2 as $_dp1)
					{
						array_push($getdp, $_dp1);	
					}
					
				}



				if(count($getdp) > 0)
				{
					// return $getdp;

					foreach($getdp as $dp)
					{
						$dpBal = $dp->amount;

						// $balforward = db::table('balforwardsetup')
						// 	->first();

						// $_dp = db::table('studpayscheddetail')
						// 	->select(db::raw('sum(amountpay) as paid'))
						// 	->where('studid', $studid)
						// 	->where('syid', $syid)
						// 	->where('semid', $semid)
						// 	->where('classid', $balforward->classid)
						// 	->where('deleted', 0)
						// 	->first();

						// if($_dp)						
						// {
						// 	$dpBal -= $_dp->paid;
						// }
						///////////////////////////////////

						// echo '(' . $dpBal . ')';



						// $balforward = db::table('balforwardsetup')
						// 	->first();


						// $getpaySched = db::table('studpayscheddetail')
						// 		->where('studid', $studid)
						// 		->where('syid', RegistrarModel::getSYID())
						// 		->where('semid', RegistrarModel::getSemID())
						// 		->where('classid', $balforward->classid)
						// 		->get();							

						// if(count($getpaySched) > 0)
						// {
						// 	foreach($getpaySched as $sched)
						// 	{
						// 		if($dpBal > 0)
						// 		{
						// 			$schedbal = $sched->amount - $sched->amountpay;

						// 			if($dpBal > $schedbal)
						// 			{
						// 				$deductDP = db::table('studpayscheddetail')
						// 					->where('id', $sched->id)
						// 					->update([
						// 						'amountpay' => $schedbal + $sched->amountpay,
						// 						'balance' => 0,
						// 						'updateddatetime' => RegistrarModel::getServerDateTime(),
						// 						'updatedby' => auth()->user()->id
						// 						]);

						// 				$dpBal -= $schedbal;

						// 			}
						// 			else
						// 			{
						// 				$tDP = $sched->amount - $dpBal;
						// 				$aPay = $sched->amountpay + $dpBal;

						// 				$deductDP = db::table('studpayscheddetail')
						// 					->where('id', $sched->id)
						// 					->update([
						// 						'amountpay' => $aPay,
						// 						'balance' => $tDP,
						// 						'updateddatetime' => RegistrarModel::getServerDateTime(),
						// 						'updatedby' => auth()->user()->id
						// 					]);
						// 				$dpBal = 0;

						// 			}
						// 		}
						// 	}
						// }

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
												'updateddatetime' => RegistrarModel::getServerDateTime(),
												'updatedby' => auth()->user()->id
												]);

										RegistrarModel::chrngdistlogs($studid, $dp->chrngtransid, $dp->chrngtransdetailid, $sched->id, $sched->classid, $schedbal);
										RegistrarModel::procItemized($studid, $sched->classid, $schedbal, $syid, $semid);

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
										
										RegistrarModel::chrngdistlogs($studid, $dp->chrngtransid, $dp->chrngtransdetailid, $sched->id, $sched->classid, $dpBal);
										RegistrarModel::procItemized($studid, $sched->classid, $dpBal, $syid, $semid);

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
			elseif($glevel >= 17 && $glevel <=20)
			{
				$levelid = $glevel;

				if(db::table('schoolinfo')->first()->cashierversion == 1)
				{
					$getdp = db::table('chrngtransdetail')				
						->select('studid', 'syid', 'semid', 'itemkind', 'payschedid', 'isdp', 'chrngtransdetail.classid', 'chrngtransdetail.amount')
						->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
						->join('items', 'chrngtransdetail.payschedid', '=', 'items.id')
						->where('studid', $studid)
						->where('syid', $syid)
						->where('semid', $semid)
						->where('itemkind', 1)
						->where('isdp', 1)
						->where('chrngtrans.cancelled', 0)
						->get();
				}
				else
				{
					// $dpsetup = db::table('dpsetup')
					// 	->where('levelid', $levelid)
					// 	->where('deleted', 0)
					// 	->where('syid', RegistrarModel::getSYID())
					// 	->where('semid', RegistrarModel::getSemID())
					// 	->groupBy('classid')
					// 	->first();


					$ee_amount = RegistrarModel::checkEE($studid, $syid, $semid);

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

					$dpclass_array = array();

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
						->groupBy('classid')
						->get();

					foreach($getdp2 as $_dp1)
					{
						array_push($getdp, $_dp1);	
					}
					
				}

				// return $getdp;

				if(count($getdp) > 0)
				{
					foreach($getdp as $dp)
					{
						$dpBal = $dp->amount;

						// $balforward = db::table('balforwardsetup')
						// 	->first();

						// $_dp = db::table('studpayscheddetail')
						// 	->select(db::raw('sum(amountpay) as paid'))
						// 	->where('studid', $studid)
						// 	->where('syid', $syid)
						// 	->where('semid', $semid)
						// 	->where('classid', $balforward->classid)
						// 	->where('deleted', 0)
						// 	->first();

						// if($_dp)						
						// {
						// 	$dpBal -= $_dp->paid;
						// }
						////////////////////////////////////////////

						// echo '(' . $dpBal . ')';



						// $balforward = db::table('balforwardsetup')
						// 	->first();


						// $getpaySched = db::table('studpayscheddetail')
						// 		->where('studid', $studid)
						// 		->where('syid', RegistrarModel::getSYID())
						// 		->where('semid', RegistrarModel::getSemID())
						// 		->where('classid', $balforward->classid)
						// 		->get();							

						// if(count($getpaySched) > 0)
						// {
						// 	foreach($getpaySched as $sched)
						// 	{
						// 		if($dpBal > 0)
						// 		{
						// 			$schedbal = $sched->amount - $sched->amountpay;

						// 			if($dpBal > $schedbal)
						// 			{
						// 				$deductDP = db::table('studpayscheddetail')
						// 					->where('id', $sched->id)
						// 					->update([
						// 						'amountpay' => $schedbal + $sched->amountpay,
						// 						'balance' => 0,
						// 						'updateddatetime' => RegistrarModel::getServerDateTime(),
						// 						'updatedby' => auth()->user()->id
						// 						]);

						// 				RegistrarModel::chrngdistlogs($studid, $dp->chrngtransid, $dp->chrngtransdetailid, $sched->id, $sched->classid, $schedbal);

						// 				$dpBal -= $schedbal;
						// 			}
						// 			else
						// 			{
						// 				$tDP = $sched->amount - $dpBal;
						// 				$aPay = $sched->amountpay + $dpBal;

						// 				$deductDP = db::table('studpayscheddetail')
						// 					->where('id', $sched->id)
						// 					->update([
						// 						'amountpay' => $aPay,
						// 						'balance' => $tDP,
						// 						'updateddatetime' => RegistrarModel::getServerDateTime(),
						// 						'updatedby' => auth()->user()->id
						// 					]);

						// 				RegistrarModel::chrngdistlogs($studid, $dp->chrngtransid, $dp->chrngtransdetailid, $sched->id, $sched->classid, $dpBal);
						// 				$dpBal = 0;
						// 			}
						// 		}
						// 	}
						// }



						$getpaySched = db::table('studpayscheddetail')
							->where('studid', $studid)
							->where('syid', $syid)
							->where('semid', $semid)
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
												'updateddatetime' => RegistrarModel::getServerDateTime(),
												'updatedby' => auth()->user()->id
												]);

										RegistrarModel::chrngdistlogs($studid, $dp->chrngtransid, $dp->chrngtransdetailid, $sched->id, $sched->classid, $schedbal);
										RegistrarModel::procItemized($studid, $sched->classid, $schedbal, $syid, $semid);

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
										
										RegistrarModel::chrngdistlogs($studid, $dp->chrngtransid, $dp->chrngtransdetailid, $sched->id, $sched->classid, $dpBal);
										RegistrarModel::procItemized($studid, $sched->classid, $dpBal, $syid, $semid);

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
									->where('balance', '>', 0)
									->whereIn('classid', $setuparray)
									->groupBy('classid')
									->get();

								if(count($gpaydetail) == 0)
								{
									dptoempty_col:
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
										goto dptoempty_col;
										
									}
								}
							}
						}
					}	
				}
			}
			else //PRESCHOOL - GRADE 10
			{
				// return $glevel;
				if(db::table('schoolinfo')->first()->cashierversion == 1)
				{
					$getdp = db::table('chrngtransdetail')				
						->select('studid', 'syid', 'semid', 'itemkind', 'payschedid', 'isdp', 'chrngtransdetail.classid', 'chrngtransdetail.amount')
						->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
						->join('items', 'chrngtransdetail.payschedid', '=', 'items.id')
						->where('studid', $studid)
						->where('syid', $syid)
						->where('itemkind', 1)
						->where('isdp', 1)
						->where('chrngtrans.cancelled', 0)
						->get();
				}
				else
				{
					$levelid = $glevel;
					
					$ee_amount = RegistrarModel::checkEE($studid, $syid, 1);

					$getdp = array();
					
					if($ee_amount > 0)
					{
						$getdp1 = db::table('chrng_earlyenrollmentpayment')
				            ->select(db::raw('chrng_earlyenrollmentpayment.studid, chrngtransdetail.`classid`, chrng_earlyenrollmentpayment.amount, chrngtrans.`ornum`, payschedid, chrng_earlyenrollmentpayment.chrngtransid, chrngtransdetail.`id` AS chrngtransdetailid, chrngtransdetail.items, paytype, transdate'))
				            ->join('chrngtrans', 'chrng_earlyenrollmentpayment.chrngtransid', '=', 'chrngtrans.id')
				            ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
				            ->where('chrng_earlyenrollmentpayment.syid', $syid)
				            // ->where('chrng_earlyenrollmentpayment.semid', $semid)
				            ->where('chrng_earlyenrollmentpayment.studid', $studid)
				            ->where('deleted', 0)
				            ->get();



				     	foreach($getdp1 as $_dp)
				     	{
				     		$transdate = date_create($_dp->transdate);
				     		$transdate = date_format($transdate, 'Y-m-d');

				     		// return $transdate;

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
						->groupBy('classid')
						->get();

					$dpclass_array = array();

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
						->where('void', 0)
						->groupBy('classid')
						->get();

					// return $getdp2;
					foreach($getdp2 as $_dp1)
					{
						array_push($getdp, $_dp1);	
					}
				}

				// return $getdp;

				if(count($getdp) > 0)
				{
					foreach($getdp as $dp)
					{
						$dpBal = $dp->amount;

						// $balforward = db::table('balforwardsetup')
						// 	->first();

						// $_dp = db::table('studpayscheddetail')
						// 	->select(db::raw('sum(amountpay) as paid'))
						// 	->where('studid', $studid)
						// 	->where('syid', $syid)
						// 	->where('semid', $semid)
						// 	->where('classid', $balforward->classid)
						// 	->where('deleted', 0)
						// 	->first();

						// if($_dp)						
						// {
						// 	$dpBal -= $_dp->paid;
						// }

						/////////////////////////
						// return $dpBal;

						// if(count($getpaySched) > 0)
						// {
						// 	foreach($getpaySched as $sched)
						// 	{
						// 		if($dpBal > 0)
						// 		{
						// 			$schedbal = $sched->amount - $sched->amountpay;

						// 			if($dpBal > $schedbal)
						// 			{
						// 				$deductDP = db::table('studpayscheddetail')
						// 					->where('id', $sched->id)
						// 					->update([
						// 						'amountpay' => $schedbal + $sched->amountpay,
						// 						'balance' => 0,
						// 						'updateddatetime' => RegistrarModel::getServerDateTime(),
						// 						'updatedby' => auth()->user()->id
						// 						]);

						// 				$dpBal -= $schedbal;
						// 			}
						// 			else
						// 			{
						// 				$tDP = $sched->amount - $dpBal;
						// 				$aPay = $sched->amountpay + $dpBal;
										
						// 				$deductDP = db::table('studpayscheddetail')
						// 					->where('id', $sched->id)
						// 					->update([
						// 						'amountpay' => $aPay,
						// 						'balance' => $tDP,
						// 						'updateddatetime' => RegistrarModel::getServerDateTime(),
						// 						'updatedby' => auth()->user()->id
						// 					]);
						// 				$dpBal = 0;
						// 			}
						// 		}
						// 	}
						// }

						// echo ' classid: ' . $dp->classid;

						$getpaySched = db::table('studpayscheddetail')
							->where('studid', $studid)
							->where('syid', $syid)
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

										RegistrarModel::chrngdistlogs($studid, $dp->chrngtransid, $dp->chrngtransdetailid, $sched->id, $sched->classid, $schedbal);
										RegistrarModel::procItemized($studid, $sched->classid, $schedbal, $syid, $semid);

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
										
										RegistrarModel::chrngdistlogs($studid, $dp->chrngtransid, $dp->chrngtransdetailid, $sched->id, $sched->classid, $dpBal);
										RegistrarModel::procItemized($studid, $sched->classid, $dpBal, $syid, $semid);
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
									->where('balance', '>', 0)
									->whereIn('classid', $setuparray)
									->groupBy('classid')
									->get();

								if(count($gpaydetail) == 0)
								{
									dptoempty:
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

												RegistrarModel::chrngdistlogs($studid, 0, 0, $sched->id, $sched->classid, $sched->balance);
												RegistrarModel::procItemized($studid, $sched->classid, $sched->balance, $syid, $semid);
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
												RegistrarModel::procItemized($studid, $sched->classid, $dpBal, $syid, $semid);
												$dpBal = 0;
											}
										}
									}

								}

								if($dpBal > 0)
								{
									echo 'dpbal: ' . $dpBal . 'retdp: ' . $retdp . '<br>';
									if($retdp == 0)
									{
										$retdp = 1;
										goto dptoempty;
										
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

											RegistrarModel::chrngdistlogs($studid, $dp->chrngtransid, $dp->chrngtransdetailid, $sched->id, $sched->classid, $schedbal);
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
											
											RegistrarModel::chrngdistlogs($studid, $dp->chrngtransid, $dp->chrngtransdetailid, $sched->id, $sched->classid, $dpBal);
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
												// ->where('classid', $detail->classid)
												->where('studid', $studid)
												->where('syid', $syid)
												->where('semid', $syid)
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
													RegistrarModel::chrngdistlogs($studid, 0, 0, $sched->id, $sched->classid, $sched->balance);
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
													RegistrarModel::chrngdistlogs($studid, 0, 0, $sched->id, $sched->classid, $dpBal);
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



			// RegistrarModel::ledgeritemizedreset($studid, $syid, $semid);
			// RegistrarModel::transitemsreset($studid, $syid, $semid);


			$schedgroup = db::select('SELECT duedate, paymentno, particulars, classid, SUM(amount) AS amount FROM studpayscheddetail where studid = ? and syid = ? group by month(duedate), duedate, paymentno order by duedate', [$studid, $syid]);
			
			// return $schedgroup;

			foreach($schedgroup as $sched)
			{
				if(empty($sched->duedate))
				{
					$paysched = db::table('studpaysched')
						->insert([
							'enrollid' => $enrollid,
							'studid' => $studid,
							'syid' =>$syid,
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
							'syid' =>$syid,
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

			$schoolinfo = db::table('schoolinfo')
				->first();

			$abbr = $schoolinfo->abbreviation;
			
			

			// $ucheck = db::table('users')
			// 		->where('id', $studinfo->userid)
			// 		->get();

			// if(count($ucheck) == 0)
			// {	
	  // 			$studuser = db::table('users')
	  // 				->insertGetId([
	  // 					'name' => $sname,
	  // 					'email' => 'S'.$sid,
	  // 					'type' => 7,
	  // 					'password' => Hash::make('123456')
	  // 				]);

	  // 			$studpword = RegistrarModel::generatepassword($studuser);


	  // 			$putUserid = db::table('studinfo')
	  // 				->where('id', $studid)
	  // 				->update([
	  // 					'userid' => $studuser,
	  // 					'updateddatetime' => RegistrarModel::getServerDateTime(),
	  // 					'updatedby' => auth()->user()->id
	  // 				]);


		 //  		if(substr($contactno, 0,1)=='0')
		 //  		{
		 //  			$contactno = '+63' . substr($contactno, 1);
		 //  		}

	  // 			$smsStud = db::table('smsbunker')
	 	// 				->insert([
	 	// 					'message' =>$abbr . ' message: ' . $studinfo->firstname .' you are already enrolled. Portal Credential - Username:S'.$sid . ' Password: ' . $studpword->code,
	 	// 					'receiver' => $contactno,
	 	// 					'smsstatus' => 0
	 	// 				]);

	  // 			$parentuser = db::table('users')
	  // 				->insertGetId([
	  // 					'name' => $pname,
	  // 					'email' => 'P'.$sid,
	  // 					'type' => 9,
	  // 					'password' => Hash::make('123456')
	  // 				]);

	  // 			$parentpword = RegistrarModel::generatepassword($parentuser);

		 //  		if(substr($substr, 0,1)=='0')
		 //  		{
		 //  			$substr = '+63' . substr($substr, 1);
		 //  		}

		 //  		$smsParent = db::table('smsbunker')
			// 		->insert([
			// 			'message' => $abbr . ' message: Your student '. $studinfo->firstname .' is already enrolled. Portal Credential - Username:P'.$sid . ' Password: ' . $parentpword->code,
			// 			'receiver' => $substr,
			// 			'smsstatus' => 0
			// 		]);
	 	// 	}

	 		$_sy = db::table('sy')
	 			->where('isactive', 1)
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
							->where('deleted', 0) 
	 						->first();
	 			}
	 			else
	 			{
	 				$enrollment = db::table('enrolledstud')
	 						->where('studid', $studid)
							->where('syid', $syid)
							->where('deleted', 0) 
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
				$dataid = $request->get('dataid');

				if($dataid == 0)
				{
					$insertStrand = db::table('sh_strand')
							->insert([
								'strandname' => $strandname,
								'strandcode' => $code,
								'trackid' => $trackid,
								'active' => $isactive,
								'createdby' => auth()->user()->id
							]);
				}
				else
				{
					$insertStrand = db::table('sh_strand')
						->where('id', $dataid)
						->update([
							'strandname' => $strandname,
							'strandcode' => $code,
							'trackid' => $trackid,
							'active' => $isactive,
							'createdby' => auth()->user()->id
						]);	
				}

				return 1;
			}
		}

		public function editstrand(Request $request)
		{
			if($request->ajax())
			{
				$dataid = $request->get('dataid');

				$loadtrack = RegistrarModel::loadtrack();
			
				$loadtrack[0]->trackname;
				
				$output = '<option></option>';

				foreach($loadtrack as $track)
				{
					$output .= '
						<option value="'.$track->id.'">'.$track->trackname.'</option>
					';
				}

				$strand = db::table('sh_strand')
					->where('id', $dataid)
					->first();

				$data = array(
					'strandname' => $strand->strandname,
					'strandcode' => $strand->strandcode,
					'trackid' => $strand->trackid,
					'active' => $strand->active,
					'tracklist' => $output,
					'strandid' => $strand->id
				);

				echo json_encode($data);
			}
		}

		public function searchstrand(Request $request)
		{
			if($request->ajax())
			{
				$query = $request->get('query');

				$searchstrand = db::table('sh_strand')
						->select('sh_strand.id', 'strandcode', 'strandname', 'trackname', 'sh_strand.active')
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
							<tr">
								<td>'.strtoupper($strand->strandcode).'</td>
								<td>'.strtoupper($strand->strandname).'</td>
								<td>'.strtoupper($strand->trackname).'</td>
								<td><i class="fas fa-check"></i></td>
								<td  class="btn-group"><button class="btn btn-primary btn-edit" data-id="'.$strand->id.'">Edit</button><button class="btn btn-danger">Delete</button></td>
							</tr>
						';
					}
					else
					{
						$output .= '
							<tr data-id="'.$strand->id.'">
								<td>'.strtoupper($strand->strandcode).'</td>
								<td>'.strtoupper($strand->strandname).'</td>
								<td>'.strtoupper($strand->trackname).'</td>
								<td></td>
								<td class="btn-group"><button class="btn btn-primary btn-edit" data-id="'.$strand->id.'">Edit</button><button class="btn btn-danger prepend">Delete</button></td>
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

				DB::table('studinfo')
					->where('id', $studid)
					->update([
						'sectionid' => $sectionid,
						'strandid' => $strand,
						'blockid' => $block,
						'semid' => $semid,
						'updatedby' => auth()->user()->id,
						'updateddatetime' => RegistrarModel::getServerDateTime()
					]);

				if($levelid == 14 or $levelid == 15)
				{
					$estud = db::table('sh_enrolledstud')
							->where('studid', $studid)
							->where('syid', RegistrarModel::getSYID())
							->where(function($q) use($levelid, $semid){
							    if(db::table('schoolinfo')->first()->shssetup == 0)
							    {
							        $q->where('semid', RegistrarModel::getSemID());
							    }
							})
							->where('deleted', 0) 
							->get();
				
					if(count($estud) > 0)
					{
						$updEnroll = db::table('sh_enrolledstud')
							->where('studid', $studid)
							->where('syid', RegistrarModel::getSYID())
							->where('semid', RegistrarModel::getSemID())
							->where('deleted', 0) 
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
							->where('deleted', 0) 
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


				$_sectionname = '';
				
				$section = db::table('sections')
					->where('id', $sectionid)
					->first();

				if($section)
				{
					$_sectionname = $section->sectionname;
				}
				else
				{
					$_sectionname = '';
				}



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
							'sectionname' => $_sectionname,
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
							'sectionname' => $_sectionname,
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
				$syid = $request->get('syid');
				$semid = $request->get('semid');
				
				$stud = db::table('studinfo')
					->where('id', $studid)
					->first();

				$dp = 0;
				$ok = 0;

				$gradelevel = db::table('gradelevel')
					->select('gradelevel.id', 'nodp', 'esc', 'voucher')
					->where('gradelevel.id', $levelid)
					->first();

				// return 'syid: ' . $syid . ' sem' . $semid;


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
				if(db::table('schoolinfo')->first()->cashierversion == 1) //DP on cashierversion 2
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
								->where('cancelled', 0)
								->get();
					}
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
						->where('studledger.syid', $syid)
						->where(function($q) use($levelid, $semid){
							if($levelid == 14 || $levelid == 15)
							{
								$q->where('studledger.semid', $semid);
							}
							if($levelid >= 17 && $levelid <= 20)
							{
								$q->where('studledger.semid', $semid);
							}
						})
						->get();
				}

				$ee_amount = RegistrarModel::checkEE($studid, $syid, $semid);

				if(count($getdp) > 0)
				{
					// $ok = 1;
					$dp = 1;
				}
				elseif($ee_amount > 0)
				{
					$dp = 1;
				}
				else
				{
					// $ok = 0;
				}
				// }

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
		  		$syid = $request->get('syid');
		  		$semid = $request->get('semid');

		  		$acadprog = RegistrarModel::activeacadprog();
		  		
	    		$student = db::table('studinfo')
					->select(db::raw('studinfo.id, sid, CONCAT(lastname, ", ", firstname) AS fullname, gender, levelname, UPPER(sectionname), grantee.`description` AS grantee, sectionname, levelid'))
					->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
					->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
					->where('studinfo.deleted', 0)
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
					->having('sid', 'LIKE', '%'.$query.'%')
					->orHaving('fullname', 'LIKE', '%'.$query.'%')
					->orderBy('lastname','ASC')
					->orderBy('firstname','ASC')
					->limit(300)
					->get();

				$output = '';
				$levelid = 0;
				$status = '';
	  			// $count = count($student);

		  		foreach($student as $s)
		  		{
		  			$secname = $s->sectionname;

		  			$_estud = '';
		  			$levelid = $s->levelid;

		  			if($levelid == 14 || $levelid == 15)
		  			{
		  				$_estud = 'sh_enrolledstud';
		  			}
		  			elseif($levelid >= 17 && $levelid <= 20)
		  			{
		  				$_estud = 'college_enrolledstud';
		  			}
		  			else
		  			{
		  				$_estud = 'enrolledstud';	
		  			}

		  			$checkEnroll = DB::table($_estud)
		  				->where('studid', $s->id)
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
		  					elseif($levelid >= 17 && $levelid <= 20)
		  					{
		  						$q->where('semid', $semid);
		  					}
		  				})
		  				->where('studstatus', '>', 0)
		  				->first();
		  			
		  			if(!$checkEnroll)
		  			{
		  				// if($checkEnroll->studstatus == 1)
			  			// {
			  			// 	$status = '<span style="width:1px height:100%" class="bg-success">&nbsp;</span>';
			  			// }
			  			// elseif($checkEnroll->studstatus == 2)
			  			// {
			  			// 	$status = '<span style="width:1px height:100%" class="bg-primary">&nbsp;</span>';	
			  			// }
			  			// elseif($checkEnroll->studstatus == 3)
			  			// {
			  			// 	$status = '<span style="width:1px height:100%" class="bg-danger">&nbsp;</span>';
			  			// }
			  			// elseif($checkEnroll->studstatus == 4)
			  			// {
			  			// 	$status = '<span style="width:1px height:100%" class="bg-warning">&nbsp;</span>';	
			  			// }
			  			// elseif($checkEnroll->studstatus == 5)
			  			// {
			  			// 	$status = '<span style="width:1px height:100%" class="bg-secondary">&nbsp;</span>';	
			  			// }
			  			// elseif($checkEnroll->studstatus == 6)
			  			// {
			  			// 	$status = '<span style="width:1px height:100%" class="bg-orange">&nbsp;</span>';	
			  			// }
			  			// else
			  			// {
			  			// 	$status = '';		
			  			// }

			  			$checkdp = RegistrarModel::checkdp($s->id, $s->levelid);

			  			$dpstatus = '';

			  			if($checkdp == 1)
			  			{
			  				$dpstatus = '<i class="fas fa-check"></i>';
			  			}
			  			else
			  			{
			  				$dpstatus = '';
			  			}

			  			if($semid == 3)
			  			{
			  				if($levelid >= 17)
			  				{
				  				$output .= '
					  				<tr>
					  					<td> '.$status.' '.$s->sid.'</td>
					  					<td id="studname"'.$s->id.'">'.strtoupper($s->fullname).'</td>
					  					<td>'.strtoupper($s->gender).'</td>
					  					<td>'.$s->levelname.'</td>
					  					<td>'.$secname.'</td>
					  					<td>'.$s->grantee.'</td>
					  					<td class="text-center text-success">'.$dpstatus.'</td>
					  					<td><button id="cmdenroll" class="btn btn-info btn-block" data-toggle="modal" data-value="'.$s->id.'" data-sid="'.$s->sid.'" data-glevel="'.$levelid.'">Enroll</button></td>
					  					<td><button id="deleteStud" class="btn btn-danger btn-block" data-id="'.$s->id.'">Delete</button></td>
					  				</tr>

					  			';
					  		}
			  			}
			  			else
			  			{
			  				$output .= '
				  				<tr>
				  					<td> '.$status.' '.$s->sid.'</td>
				  					<td id="studname"'.$s->id.'">'.strtoupper($s->fullname).'</td>
				  					<td>'.strtoupper($s->gender).'</td>
				  					<td>'.$s->levelname.'</td>
				  					<td>'.$secname.'</td>
				  					<td>'.$s->grantee.'</td>
				  					<td class="text-center text-success">'.$dpstatus.'</td>
				  					<td><button id="cmdenroll" class="btn btn-info btn-block" data-toggle="modal" data-value="'.$s->id.'" data-sid="'.$s->sid.'" data-glevel="'.$levelid.'">Enroll</button></td>
				  					<td><button id="deleteStud" class="btn btn-danger btn-block" data-id="'.$s->id.'">Delete</button></td>
				  				</tr>

				  			';
			  			}
		  			}

		  			// if($s->studstatus == 0)
		  			// return $chkEnroll;
		  			
		  		}
		  		// return $output;
		  		$data = array(
		  			'output' => $output,
		  			'glevel' => $glevel,
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
					->where('isapproved', '!=', 3)
					->orWhere('queingcode', $studinfo->sid)
					->where('isapproved', '!=', 3)
					->count();

				if($studinfo->levelid == 14 || $studinfo->levelid == 15)
				{
					$enrolledstud = db::table('enrolledstud')
							->where('studid', $studid)
							->where('deleted', 0)
							->count();
				}
				elseif($studinfo->levelid >= 17 && $studinfo->levelid <= 21)
				{
					$enrolledstud = db::table('college_enrolledstud')
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

		// public function searchEnrolledStud(Request $request)
		// {
		// 	if($request->ajax())
	 //  		{
		//   		$glevel = $request->get('glevel');
		//   		$query = $request->get('query');
		//   		$syid = $request->get('syid');
		//   		$semid = $request->get('semid');

		//   		// $skip = $request->get('skip');
		//   		// $take = $request->get('take');
		//   		// $curpage = $request->get('curpage');

		//   		// $skip = ((int)$skip - 1) * $take;

		//   		$acadprog = RegistrarModel::activeacadprog();

	 //    		$student = db::table('studinfo')
		// 			->select('studinfo.*', 'gradelevel.levelname', 'sections.sectionname as secname', 'grantee.description as grantee')
		// 			->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
		// 			->leftJoin('sections', 'studinfo.sectionid', '=', 'sections.id')
		// 			->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
		// 			->where(function($q) use($glevel, $acadprog){
		// 				if($glevel > 0)
		// 				{
		// 					$q->where('studinfo.levelid', $glevel);
		// 				}
		// 				else
		// 				{
		// 					$q->whereIn('gradelevel.acadprogid', $acadprog);
		// 				}
		// 			})
		// 			->where('lastname', 'like', '%'.$query.'%')
		// 			->where('studstatus', '!=', 0)
		// 			->where('studinfo.deleted', 0)
		// 			->orWhere('firstname', 'like', '%'.$query.'%')
		// 			->where('studinfo.levelid', $glevel)
		// 			->where('studstatus', '!=', 0)
		// 			->where('studinfo.deleted', 0)
		// 			->orderBy('lastname','ASC')
		// 			->orderBy('firstname','ASC')
		// 			->get();



	 //    				// return $student;

	 //    		$recCount = db::table('studinfo')
		// 			->select('studinfo.*', 'gradelevel.levelname', 'sections.sectionname as secname')
		// 			->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
		// 			->leftJoin('sections', 'studinfo.sectionid', '=', 'sections.id')
		// 			->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
		// 			->where(function($q) use($glevel, $acadprog){
		// 				if($glevel > 0)
		// 				{
		// 					$q->where('studinfo.levelid', $glevel);
		// 				}
		// 				else
		// 				{
		// 					$q->whereIn('gradelevel.acadprogid', $acadprog);
		// 				}
		// 			})
		// 			->where('lastname', 'like', '%'.$query.'%')
		// 			->where('studstatus', '!=', 0)
		// 			->where('studinfo.deleted', 0)
		// 			->orWhere('firstname', 'like', '%'.$query.'%')
		// 			->where('studinfo.levelid', $glevel)
		// 			->where('studstatus', '!=', 0)
		// 			->where('studinfo.deleted', 0)
		// 			->count();

		//   		$output = '';
		//   		$paginate = '';
		//   		$paginate .= '
		//   			<li class="paginate_button page-item previous" id="example2_previous">
		// 					<a href="#" aria-controls="example2" data-page="0" tabindex="0" class="page-link">Previous</a>
		// 				</li>
		//   		';
		//   		$count = count($student);

		//   		foreach($student as $s)
		//   		{
		//   			if($s->studstatus == 1)
		//   			{
		//   				$status = '<span style="width:1px height:100%" class="bg-success">&nbsp;</span>';
		//   			}
		//   			elseif($s->studstatus == 2)
		//   			{
		//   				$status = '<span style="width:1px height:100%" class="bg-primary">&nbsp;</span>';	
		//   			}
		//   			elseif($s->studstatus == 3)
		//   			{
		//   				$status = '<span style="width:1px height:100%" class="bg-danger">&nbsp;</span>';
		//   			}
		//   			elseif($s->studstatus == 4)
		//   			{
		//   				$status = '<span style="width:1px height:100%" class="bg-warning">&nbsp;</span>';	
		//   			}
		//   			elseif($s->studstatus == 5)
		//   			{
		//   				$status = '<span style="width:1px height:100%" class="bg-secondary">&nbsp;</span>';	
		//   			}
		//   			elseif($s->studstatus == 6)
		//   			{
		//   				$status = '<span style="width:1px height:100%" class="bg-orange">&nbsp;</span>';	
		//   			}
		//   			else
		//   			{
		//   				$status = '';		
		//   			}


		  			
		//   			if($s->levelid == 14 || $s->levelid == 15)
		//   			{
		//   				$chkEnroll = db::table('sh_enrolledstud')
		//   						->where('studid', $s->id)
		//   						->where('syid', $syid)
		//   						->where('semid', $semid)
		//   						->where('studstatus', '>', '0')
		//   						->where('deleted', 0)
		//   						->get();

		//   				$secname = $s->secname;

		//   			}
		//   			elseif($s->levelid >= 17 && $s->levelid <= 21)
		//   			{
		//   				// return $s->levelid;
		//   				$chkEnroll = db::table('college_enrolledstud')
		//   						->where('studid', $s->id)
		//   						->where('syid', $syid)
		//   						->where('semid', $semid)
		//   						->where('studstatus', '>', '0')
		//   						->where('deleted', 0)
		//   						->get();

		//   				$col_section = db::table('college_sections')
		//   						->where('id', $s->sectionid)
		//   						->first();


		//   				if(!empty($col_section))
		//   				{
		//   					$secname = $col_section->sectionDesc;
		//   				}
		//   				else
		//   				{
		//   					$secname = '';
		//   				}
		  				
		//   			}
		//   			else
		//   			{
		//   				$chkEnroll = db::table('enrolledstud')
		//   						->where('studid', $s->id)
		//   						->where('syid', $syid)
		//   						->where('studstatus', '>', '0')
		//   						->where('deleted', 0)
		//   						->get();

		//   				$secname = $s->secname;
		//   			}

		//   			// if($s->studstatus == 0)
		//   			// return $chkEnroll;
		//   			if(count($chkEnroll) == 0)
		//   			{
		// 	  			$output .= '
		// 	  				<tr>
		// 	  					<td> '.$status.' '.$s->sid.'</td>
		// 	  					<td id="studname"'.$s->id.'">'.strtoupper($s->lastname.' '.$s->firstname).'</td>
		// 	  					<td>'.strtoupper($s->gender).'</td>
		// 	  					<td>'.$s->levelname.'</td>
		// 	  					<td>'.$secname.'</td>
		// 	  					<td>'.$s->grantee.'</td>
		// 	  					<td><button id="cmdenroll" class="btn btn-info btn-block" data-toggle="modal" data-value="'.$s->id.'" data-sid="'.$s->sid.'">Enroll</button></td>
		// 	  					<td><button id="deleteStud" class="btn btn-danger btn-block" data-id="'.$s->id.'">Delete</button></td>
		// 	  				</tr>

		// 	  			';
		// 	  		}
		// 	  		else
		// 	  		{
		// 	  			$output .= '

		// 	  				<tr>
		// 	  					<td> '.$status.' '.$s->sid.'</td>
		// 	  					<td id="studname"'.$s->id.'">'.strtoupper($s->lastname.' '.$s->firstname).'</td>
		// 	  					<td>'.strtoupper($s->gender).'</td>
		// 	  					<td>'.$s->levelname.'</td>
		// 	  					<td>'.$secname.'</td>
		// 	  					<td>'.$s->grantee.'</td>
		// 	  					<td colspan="2"><button id="cmdViewEnrollment" class="btn btn-info btn-block" data-id="'.$s->id.'" data-crypt="'.\Crypt::encrypt($s->id).'" data-glevel="'.$s->levelid.'" data-toggle="modal">View Enrollment</button></td>
		// 	  				</tr>

		// 	  			';	
		// 	  		}
		//   		}
	 //  		// return $output;
		//   		$data = array(
		//   			'output' => $output,
		  			
		//   			'glevel' => $glevel,
		//   			'recCount' => $recCount
		//   		);

	 //  			echo json_encode($data);
	 //  		}
	 // 	}


		public function searchEnrolledStud(Request $request)
		{
			if($request->ajax())
	  		{
		  		$glevel = $request->get('glevel');
		  		$query = $request->get('query');
		  		$syid = $request->get('syid');
		  		$semid = $request->get('semid');

		  		$acadprog = RegistrarModel::activeacadprog();

	    		$student = db::table('studinfo')
					->select(db::raw('studinfo.id, sid, CONCAT(lastname, ", ", firstname) AS fullname, gender, levelname, UPPER(sectionname), grantee.`description` AS grantee, sectionname, levelid'))
					->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
					->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
					->where('studinfo.deleted', 0)
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
					->having('sid', 'LIKE', '%'.$query.'%')
					->orHaving('fullname', 'LIKE', '%'.$query.'%')
					->orderBy('lastname','ASC')
					->orderBy('firstname','ASC')
					// ->limit(300)
					->get();

		  		// $count = count($student);
				$output = '';
				$levelid = 0;
		  		foreach($student as $s)
		  		{
		  			$secname = $s->sectionname;

		  			$_estud = '';
		  			$levelid = $s->levelid;

		  			if($levelid == 14 || $levelid == 15)
		  			{
		  				$_estud = 'sh_enrolledstud';
		  			}
		  			elseif($levelid >= 17 && $levelid <= 20)
		  			{
		  				$_estud = 'college_enrolledstud';
		  			}
		  			else
		  			{
		  				$_estud = 'enrolledstud';	
		  			}



		  			$checkEnroll = DB::table($_estud)
		  				->where('studid', $s->id)
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
		  					elseif($levelid >= 17 && $levelid <= 20)
		  					{
		  						$q->where('semid', $semid);
		  					}
		  				})
		  				->where('studstatus', '>', 0)
		  				->first();

		  			if($checkEnroll)
		  			{
		  				// echo 'studid: ' . $s->id . '<br>';
		  				if($checkEnroll->studstatus == 1)
			  			{
			  				$status = '<span style="width:1px height:100%" class="bg-success">&nbsp;</span>';
			  			}
			  			elseif($checkEnroll->studstatus == 2)
			  			{
			  				$status = '<span style="width:1px height:100%" class="bg-primary">&nbsp;</span>';	
			  			}
			  			elseif($checkEnroll->studstatus == 3)
			  			{
			  				$status = '<span style="width:1px height:100%" class="bg-danger">&nbsp;</span>';
			  			}
			  			elseif($checkEnroll->studstatus == 4)
			  			{
			  				$status = '<span style="width:1px height:100%" class="bg-warning">&nbsp;</span>';	
			  			}
			  			elseif($checkEnroll->studstatus == 5)
			  			{
			  				$status = '<span style="width:1px height:100%" class="bg-secondary">&nbsp;</span>';	
			  			}
			  			elseif($checkEnroll->studstatus == 6)
			  			{
			  				$status = '<span style="width:1px height:100%" class="bg-orange">&nbsp;</span>';	
			  			}
			  			else
			  			{
			  				$status = '';		
			  			}

			  			if($semid == 3)
			  			{
			  				if($levelid >= 17)
			  				{
				  				$output .= '
					  				<tr>
					  					<td> '.$status.' '.$s->sid.'</td>
					  					<td id="studname"'.$s->id.'">'.strtoupper($s->fullname).'</td>
					  					<td>'.strtoupper($s->gender).'</td>
					  					<td>'.$s->levelname.'</td>
					  					<td>'.$secname.'</td>
					  					<td>'.$s->grantee.'</td>
					  					<td colspan="2"><button id="cmdViewEnrollment" class="btn btn-info btn-block" data-id="'.$s->id.'" data-crypt="'.\Crypt::encrypt($s->id).'" data-glevel="'.$s->levelid.'" data-toggle="modal">View Enrollment</button></td>
					  				</tr>
					  			';	
					  		}
			  			}
			  			else
			  			{
			  				$output .= '
				  				<tr>
				  					<td> '.$status.' '.$s->sid.'</td>
				  					<td id="studname"'.$s->id.'">'.strtoupper($s->fullname).'</td>
				  					<td>'.strtoupper($s->gender).'</td>
				  					<td>'.$s->levelname.'</td>
				  					<td>'.$secname.'</td>
				  					<td>'.$s->grantee.'</td>
				  					<td colspan="2"><button id="cmdViewEnrollment" class="btn btn-info btn-block" data-id="'.$s->id.'" data-crypt="'.\Crypt::encrypt($s->id).'" data-glevel="'.$s->levelid.'" data-toggle="modal">View Enrollment</button></td>
				  				</tr>
				  			';	
			  			}
		  			}
		  			// else
		  			// {
		  			// 	echo 'studid-not enrolled: ' . $s->id . '<br>';
		  			// }
		  		}
	  		// return $output;
		  		$data = array(
		  			'output' => $output,
		  			'glevel' => $glevel
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

		  			$checkdp = RegistrarModel::checkdp($s->id, $s->levelid);

		  			$dpstatus = '';

		  			if($checkdp == 1)
		  			{
		  				$dpstatus = '<i class="fas fa-check"></i>';
		  			}
		  			else
		  			{
		  				$dpstatus = '';
		  			}

		  			// if($s->studstatus == 0)
		  			// return $chkEnroll;
		  			if(count($chkEnroll) == 0)
		  			{
			  			$output .= '

			  				<tr>
			  					<td> '.$status.' '.$s->sid.'</td>
			  					<td id="studname"'.$s->id.'">'.strtoupper($s->lastname.' '.$s->firstname).'</td>
			  					<td>'.strtoupper($s->gender).'</td>
			  					<td>'.$s->levelname.'</td>
			  					<td>'.$secname.'</td>
			  					<td>'.$s->grantee.'</td>
			  					<td class="text-center text-success">'.$dpstatus.'</td>
			  					<td colspan="2"><button id="cmdenroll" class="btn btn-info btn-block" data-glevel="'.$s->levelid.'" data-value="'.$s->id.'" data-sid="'.$s->sid.'">Enroll</button>
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
			  					<td>'.strtoupper($s->gender).'</td>
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
		if($request->ajax())
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
	}

	public function createbatch(Request $request)
	{
		if($request->ajax())
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

		return view('enrollment/tvstudinfo')->with($data);
	}

	public function tvstudsearch(Request $request)
	{
		if($request->ajax())
		{
				
		}
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
		if($request->ajax())
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
						'isguardannum' => $gnum
					]);

				// $idprefix = db::table('idprefix')->first();
	  		
	  			$id = sprintf('%06d', $studid);

	  			$yr = date_create(RegistrarModel::getServerDateTime());
	  			$yr = date_format($yr, 'y');

	  			$sid = 1 . $yr . 7 .  $id;

	  			db::table('studinfo')
	  				->where('id', $studid)
	  				->update([
	  					'sid' => $sid
	  				]);

	  			return 1;
			}
		}
	}

	public function getshblock(Request $request)
	{
		if($request->ajax())
		{
			$sectionid = $request->get('sectionid');
			$strandid = $request->get('strandid');
			$blist = '';
			// return $blist;
			// return RegistrarModel::getshblock($sectionid);

			foreach(RegistrarModel::getshblock($sectionid, $strandid) as $block)
			{
				$blist .='
					<option value="'.$block->blockid.'">'.$block->blockname.'</option>
				';
			}

			// return $blist;

			$data = array(
				'blocklist' => $blist
			);

			echo json_encode($data);
		}
	}

	public function histinfo(Request $request)
	{
		if($request->ajax())
		{
			$semid = $request->get('semid');
			$syid = $request->get('syid');
			$studid = $request->studid;
			$_enrollstud = '';

			$studinfo = db::table('studinfo')
				->where('id', $studid)
				->first();


			$levelid = $studinfo->levelid;

			if($levelid == 14 || $levelid == 15)
			{
				$_enrollstud = 'sh_enrolledstud';
			}
			elseif($levelid >= 17 && $levelid <= 20)
			{
				$_enrollstud = 'college_enrolledstud';	
			}
			else
			{
				$_enrollstud = 'enrolledstud';
			}

			$de = '';
			$sectionname = '';
			$strandname = '';
			$blockname ='';
			$sectionid ='';

			$enrollinfo = db::table($_enrollstud)
				->select($_enrollstud .'.id', 'dateenrolled', 'sectionname', 'blockname', 'strandname', 'sections.id as sectionid')
				->join('sections', $_enrollstud .'.sectionid', '=', 'sections.id')
				->leftjoin('sh_strand', $_enrollstud . '.strandid', '=', 'sh_strand.id')
				->leftjoin('sh_block', $_enrollstud. '.blockid', '=', 'sh_block.id')
				->where('studid', $studid)
				->where('syid', $syid)
				->where(function($q) use($levelid, $semid){
					if($levelid == 14 || $levelid == 15)
					{
						$q->where('semid', $semid);
					}
					if($levelid >= 17 || $levelid <= 20)
					{
						$q->where('semid', $semid);
					}
				})
				->where($_enrollstud. '.deleted', 0)
				->first();

			if($enrollinfo)	
			{
				$de = date_create($enrollinfo->dateenrolled);
				$de = date_format($de, 'm-d-Y');

				$sectionname = $enrollinfo->sectionname;
				$strandname = $enrollinfo->strandname;
				$blockname = $enrollinfo->blockname;
				$sectionid = $enrollinfo->sectionid;

			}

			$data = array(
				'dateenrolled' => $de,
				'sectionname' => $sectionname,
				'blockname' => $blockname,
				'strandname' => $strandname,
				'sectionid' => $sectionid
			);

			echo json_encode($data);

		}
	}
	
	public function earlyregistration()
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


		return view('/enrollment/earlyregistration')
			->with($data);
	}

	public function searchearlyenrollment(Request $request)
	{
		if($request->ajax())
		{
			$syid = $request->get('syid');
			$semid = $request->get('semid');
			$query = $request->get('query');
			$levelid = $request->get('levelid');

			$studlist = db::table('earlybirds')
				->select(db::raw('studinfo.id as studid, sid, CONCAT(lastname, ", ", firstname) AS fullname, gender, levelname, earlybirds.levelid'))
				->join('studinfo', 'earlybirds.studid', '=', 'studinfo.id')
				->join('gradelevel', 'earlybirds.levelid', '=', 'gradelevel.id')
				->where('earlybirds.deleted', 0)
				->where(function($q) use($levelid){
					if($levelid > 0)
					{
						$q->where('earlybirds.levelid', $levelid);
					}
				})
				->where('syid', $syid)
				->where(function($q) use($levelid, $semid){
					if($levelid == 14 || $levelid == 15)
					{
						if(db::table('schoolinfo')->first()->shssetup == 0)
						{
							$q->where('earlybirds.semid', $semid);
						}
					}
					if($levelid >= 17 && $levelid <= 20)
					{
						$q->where('earlybirds.semid', $semid);
					}
				})
				->where('enrolled', 0)
				->having('fullname', 'like', '%'.$query.'%')
				->orHaving('sid', 'like', '%'. $query . '%')
				->orderBy('lastname', 'ASC')
				->orderBy('firstname', 'ASC')
				->get();

			$list = '';
			$payment = '';
			$payment_color;
			$enroll_v = '';

			if($syid == RegistrarModel::getSYID())
			{
				if($levelid == 14 || $levelid == 15)
				{
					if(db::table('schoolinfo')->first()->shssetup == 0)
					{
						if($semid == RegistrarModel::getSemID())
						{
							$enroll_v = '';
						}
						else
						{
							$enroll_v = 'disbaled';
						}
					}
					else
					{
						$enroll_v = '';
					}
				}
				else if($levelid >= 17 && $levelid <= 20)
				{
					if($semid == RegistrarModel::getSemID())
					{
						$enroll_v = '';
					}
					else
					{
						$enroll_v = 'disabled';
					}
				}
			}
			else
			{
				$enroll_v = 'disabled';
			}

			foreach($studlist as $stud)
			{

				$ee_payment = db::table('chrng_earlyenrollmentpayment')
					->select('id')
					->where('studid', $stud->studid)
					->where('deleted', 0)
					->where('syid', $syid)
					->where(function($q) use($stud, $semid){
						if($stud->levelid == 14 || $stud->levelid == 15)
						{
							if(db::table('schoolinfo')->first()->shssetup == 0)
							{
								$q->where('semid', $semid);
							}
						}
						if($stud->levelid >= 17 && $stud->levelid <= 20)
						{
							$q->where('semid', $semid);
						}
					})
					->get();

				if(count($ee_payment) > 0)
				{
					// $payment = '<i class="fas fa-check"></i>';	
					$payment = 'Paid';
					$payment_color = 'badge-success';
				}
				else
				{
					$payment = '';
					$payment_color ='';
				}

				$tb_enrolled = '';

				if($stud->levelid == 14 || $stud->levelid == 15)
				{
					$tb_enrolled = 'sh_enrolledstud';
				}
				elseif($stud->levelid >= 17 && $stud->levelid <= 20)
				{
					$tb_enrolled = 'college_enrolledstud';	
				}
				else
				{
					$tb_enrolled = 'enrolledstud';
				}


				$name = $stud->fullname;


				$_enroll = db::table($tb_enrolled)
					->where('studid', $stud->studid)
					->where('deleted', 0)
					->where('studstatus', '!=', 0)
					->where('syid', $syid)
					->where(function($q) use($stud, $semid){
						if($stud->levelid == 14 || $stud->levelid == 15)
						{
							if(db::table('schoolinfo')->first()->shssetup == 0)
							{
								$q->where('semid', $semid);
							}
						}
						if($stud->levelid >= 17 && $stud->levelid <= 20)
						{
							$q->where('semid', $semid);
						}
					})
					->get();

					// return $_enroll;

					if(count($_enroll) == 0)
					{
						$list .='
							<tr data-id="'.$stud->studid.'">
								<td>'.$stud->sid.'</td>
								<td>'.$name.'</td>
								<td>'.$stud->gender.'</td>
								<td>'.$stud->levelname.'</td>
								<td><span class="badge '.$payment_color.' text-md mt-1">'.$payment.'</span></td>
								<td>
									<button class="btn btn-primary btn-sm btn-block enroll" data-id="'.$stud->studid.'" '.$enroll_v.' data-level="'.$stud->levelid.'">Enroll</button>
								</td>
							</tr>
						';	
					}

					
			}

			$data = array(
				'list' => $list
			);

			echo json_encode($data);
			// <td class="dp-paid text-center">'.$payment.'</td>
		}
	}

	public function ee_getstudpaid(Request $request)
	{
		if($request->ajax())
		{
			$studid = $request->get('studid');
			$syid = $request->get('syid');
			$semid = $request->get('semid');

			$levelid = db::table('studinfo')->where('id', $studid)->first()->levelid;

			$ee_payment = db::table('chrng_earlyenrollmentpayment')
				->select(db::raw('COUNT(chrng_earlyenrollmentpayment.id) AS paymentcount'))
				->where('deleted', 0)
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
				->first();

			$payment = '';

			if($ee_payment->paymentcount > 0)
			{
				$payment = '<i class="fas fa-check"></i>';
			}
			else
			{
				$payment = '';
			}

			return $payment;

		}
	}

	public function resitemized(Request $request)
	{
		if($request->ajax())
		{
			RegistrarModel::ledgeritemizedreset(1431, 3, 1);
			return RegistrarModel::transitemsreset(1431, 3, 1);
		}
	}
	
	public function studentinsert(Request $request)
	{
		$dataid = $request->get('dataid');
		$studid = 0;

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
		$sid = 0;

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
	  					// 'createdby' => auth()->user()->id
	  				]);

	  			$acadid = db::table('gradelevel')
		  			->where('id', $levelid)
		  			->first()
		  			->acadprogid;	  		
		  		
		  		$sid = RegistrarModel::idprefix($acadid, $studid);

		  		// $sid = $idprefix->prefix . $id;

		  		$upd = db::table('studinfo')
		  			->where('id', $studid)
		  			->update([
		  				'sid' => $sid
		  			]);

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
	  					// 'updatedby' => auth()->user()->id
	  				]);

	  			$studid = $dataid;
	  			$return = 'done';
			}

		}

		endsave:

		$data = array(
			'studid' => $studid,
			'return' => $return,
			'sid' => $sid
		);

		echo json_encode($data);
	}

	public function tvv2courses(Request $request)
	{

		$courses = db::table('tv_courses')
		// ->select('*', 'asd')
		->where('deleted', 0)
		->get();

		if(!$request->has('action'))
		{
			$courses = db::table('tv_courses')
				// ->select('*', 'asd')
				->where('deleted', 0)
				->get();
				
			return view('enrollment/techvoc/courses')
				->with('courses', $courses);
		}else{
			if($request->get('action') == 'getcourseinfo')
			{
				$courseinfo = collect($courses)->where('id', $request->get('courseid'))->first();

				$batches = DB::table('tv_batch')
					->where('courseid', $request->get('courseid'))
					->where('deleted',0)
					->get();
					
				if(count($batches)>0)
				{
					foreach($batches as $batch)
					{
						$batch->enrolled = DB::table('tv_enrolledstud')
							->where('batchid', $batch->id)
							->where('deleted','0')
							->where('status',1)
							->count();
					}
				}
				return view('enrollment/techvoc/courseinfo')
					->with('batches', $batches)
					->with('courseinfo', $courseinfo);
			}
			elseif($request->get('action') == 'updatecourseinfo')
			{
				
				$courseinfo = collect($courses)->where('id', $request->get('courseid'))->first();

				if($courseinfo->description != $request->get('coursetitle') || $courseinfo->duration != $request->get('courseduration'))
				{
					DB::table('tv_courses')
						->where('id', $courseinfo->id)
						->update([
							'description'		=> $request->get('coursetitle'),
							'duration'			=> $request->get('courseduration'),
							'updatedby'			=> auth()->user()->id,
							'updateddatetime'	=> date('Y-m-d H:i:s')
						]);
				}
				return 1;
			}
			elseif($request->get('action') == 'coursedelete')
			{
				$courseinfo = collect($courses)->where('id', $request->get('courseid'))->first();
				DB::table('tv_courses')
					->where('id', $courseinfo->id)
					->update([
						'deleted'			=> 1,
						'deletedby'			=> auth()->user()->id,
						'deleteddatetime'	=> date('Y-m-d H:i:s')
					]);
				return 1;
			}
		}
	}
	public function tvv2batches(Request $request)
	{
		if($request->has('action'))
		{
			if($request->get('action') == 'batchedit')
			{
				DB::table('tv_batch')
					->where('id', $request->get('batchid'))
					->update([
						'startdate'			=> $request->get('startdate'),
						'enddate'			=> $request->get('enddate'),
						'updatedby'			=> auth()->user()->id,
						'updateddatetime'	=> date('Y-m-d H:i:s')
					]);
			}
			elseif($request->get('action') == 'batchdelete')
			{
				DB::table('tv_batch')
					->where('id', $request->get('batchid'))
					->update([
						'deleted'			=> 1,
						'deletedby'			=> auth()->user()->id,
						'deleteddatetime'	=> date('Y-m-d H:i:s')
					]);
			}elseif($request->get('action') == 'batchupdateactivation')
			{
				DB::table('tv_batch')
					->where('id', $request->get('batchid'))
					->update([
						'isactive'			=> $request->get('newstatus'),
						'updatedby'			=> auth()->user()->id,
						'updateddatetime'	=> date('Y-m-d H:i:s')
					]);
			}

			return 1;
		}
	}
	public function tvv2enrollment(Request $request)
	{
		$courses = db::table('tv_courses')
			// ->select('*', 'asd')
			->where('deleted', 0)
			->get();
		if(!$request->has('action'))
		{
				
			return view('enrollment/techvoc/index')
				->with('courses', $courses);
		}else{
			if($request->get('action') == 'getbatches')
			{
				$batches = DB::table('tv_batch')
					->where('courseid', $request->get('courseid'))
					->where('deleted',0)
					->where('isactive',1)
					->get();

				if(count($batches)>0)
				{
					foreach($batches as $batch)
					{
						$batch->startdatestring = date('M d, Y', strtotime($batch->startdate));
						$batch->enddatestring = date('M d, Y', strtotime($batch->enddate));
					}
				}
				return $batches;
			}
			elseif($request->get('action') == 'generate')
			{
				$allstudents = DB::table('tv_studinfo')->where('deleted','0')->get();
				$students = DB::table('tv_enrolledstud')
					->select('tv_enrolledstud.id','tv_enrolledstud.studid','tv_enrolledstud.courseid','tv_enrolledstud.batchid','tv_studinfo.sid','tv_studinfo.lastname','tv_studinfo.firstname','tv_studinfo.middlename','tv_studinfo.suffix','tv_studinfo.gender','tv_batch.startdate','tv_batch.enddate','tv_enrolledstud.dateenrolled','tv_enrolledstud.status')
					->join('tv_studinfo','tv_enrolledstud.studid','=','tv_studinfo.id')
					->join('tv_batch','tv_enrolledstud.batchid','=','tv_batch.id')
					// ->where('tv_enrolledstud.courseid', $request->get('courseid'))
					->where('tv_enrolledstud.deleted','0')
					// ->where('tv_enrolledstud.status',1)
					->get();
					
				if($request->get('courseid')>0)
				{
					$students = collect($students)->where('courseid', $request->get('courseid'))->values();
				}
				if($request->get('batchid')>0)
				{
					$students = collect($students)->where('batchid', $request->get('batchid'))->values();
				}
				if(count($students)>0)
				{
					foreach($students as $student)
					{
						$student->sortname = $student->lastname.', '.$student->firstname.' '.$student->middlename;
					}
				}

				$students = collect($students)->sortBy('sortname')->all();
				
				$nationality = db::table('nationality')
					// ->select('*', 'asd')
					->where('deleted', 0)
					->get();

				return view('enrollment/techvoc/results')
					->with('batchid', $request->get('batchid'))
					->with('courseid', $request->get('courseid'))
					->with('nationality', $nationality)
					->with('students', $students)
					->with('allstudents', $allstudents);
			}
			elseif($request->get('action') == 'getcoursesenrolled')
			{
				$coursesenrolled = DB::table('tv_enrolledstud')
					->select('tv_enrolledstud.id','tv_enrolledstud.studid','tv_enrolledstud.courseid','tv_enrolledstud.batchid','tv_batch.startdate','tv_batch.enddate','tv_enrolledstud.dateenrolled','tv_courses.description as coursename')
					->join('tv_batch','tv_enrolledstud.batchid','=','tv_batch.id')
					->join('tv_courses','tv_batch.courseid','=','tv_courses.id')
					->where('tv_enrolledstud.studid', $request->get('studid'))
					->where('tv_enrolledstud.deleted','0')
					->where('tv_enrolledstud.status',1)
					->get();

				if(count($coursesenrolled)>0)
				{
					foreach($coursesenrolled as $eachcoursenrolled)
					{
						$eachcoursenrolled->dateenrolled = date('M d, Y', strtotime($eachcoursenrolled->dateenrolled));
						$eachcoursenrolled->startdate = date('M d, Y', strtotime($eachcoursenrolled->startdate));
						$eachcoursenrolled->enddate = date('M d, Y', strtotime($eachcoursenrolled->enddate));
					}
				}
				return $coursesenrolled;
			}elseif($request->get('action') == 'getstudinfo'){
				
					// if($request->ajax())
					// {
						$studid = $request->get('studid');


						$stud = db::table('tv_studinfo')
							->where('id', $studid)
							->first();

						// $dob = date_create($stud->dob);
						// $dob = date_format($dob, 'm/d/Y');

						$data = array(
							'gender' => $stud->gender,
							'dob' => $stud->dob,
							'nationality' => $stud->nationalityid,
							'contactno' => $stud->contactno,
							'street' => $stud->street,
							'barangay' => $stud->barangay,
							'city' => $stud->city,
							'province' => $stud->province

						);


						echo json_encode($data);

					// }
			}elseif($request->get('action') == 'createstudent'){
				
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

				$studinfo = db::table('tv_studinfo')
					->where('lastname', $lastname)
					->where('firstname', $firstname)
					->where('middlename', $middlename)
					->where('dob', $dob)
					->count();

				if($studinfo > 0)
				{
					return 0;
				}
				else
				{
					$studid = db::table('tv_studinfo')
						->insertGetId([
							'lastname' => $lastname,
							'firstname' => $firstname,
							'middlename' => $middlename,
							'suffix' => $suffix,
							'gender' => $gender,
							'dob' => $dob,
							'nationalityid' => $nationality,
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
							'deleted'			=> 0,
							'createdby'			=> auth()->user()->name,
							'createddatetime'	=> date('Y-m-d H:i:s'),
							'isguardiannum' => $gnum
						]);

					// $idprefix = db::table('idprefix')->first();
				

					$sid = \App\RegistrarModel::idprefix(7,$studid);

					db::table('tv_studinfo')
						->where('id', $studid)
						->update([
							'sid' => $sid
						]);
				}

				return collect(db::table('tv_studinfo')->where('id', $studid)->first());
			}elseif($request->get('action') == 'enrollstudent')
			{
				$studid 		= $request->get('studid');
				$sid = \App\RegistrarModel::idprefix(7,$request->get('studid'));
				$courseid 		= $request->get('courseid');
				$batchid 		= $request->get('batchid');
				$lastname 		= $request->get('lastname');
				$firstname 		= $request->get('firstname');
				$middlename 	= $request->get('middlename');
				$suffix 		= $request->get('suffix');
				$gender 		= $request->get('gender');
				$dob 			= $request->get('dob');
				$nationality 	= $request->get('nationality');
				$contactnum 	= $request->get('contactnum');
				$street 		= $request->get('street');
				$barangay 		= $request->get('barangay');
				$city 			= $request->get('city');
				$province 		= $request->get('province');


					$checkifexists  = DB::table('tv_studinfo')
									->where('id',$studid)
									->where('deleted','0')
									->first();
	
					if($checkifexists)
					{
						$tvstudid = $checkifexists->id;
						if($checkifexists->gender != $gender || $checkifexists->dob != $dob ||  $checkifexists->nationalityid != $nationality || $checkifexists->contactno != $contactnum || $checkifexists->street != $street || $checkifexists->barangay != $barangay || $checkifexists->city != $city || $checkifexists->province != $province)
						{
							DB::table('tv_studinfo')
								->where('id',$checkifexists->id)
								->update([
									'gender'			=> $gender,
									'dob'				=> $dob,
									'gender'			=> $gender,
									'nationalityid'		=> $nationality,
									'contactno'			=> $contactnum,
									'street'			=> $street,
									'barangay'			=> $barangay,
									'city'				=> $city,
									'province'			=> $province,
									'updatedby'			=> auth()->user()->name,
									'updateddatetime'	=> date('Y-m-d H:i:s')
								]);
						}
					}else{
						$tvstudid = DB::table('tv_studinfo')
							->insertGetId([
								// 'studid'			=> $studid,
								'sid'				=> $sid,
								'lastname'			=> $lastname,
								'firstname'			=> $firstname,
								'middlename'		=> $middlename,
								'suffix'			=> $suffix,
								'gender'			=> $gender,
								'dob'				=> $dob,
								'gender'			=> $gender,
								'nationalityid'		=> $nationality,
								'contactno'			=> $contactnum,
								'street'			=> $street,
								'barangay'			=> $barangay,
								'city'				=> $city,
								'province'			=> $province,
								// 'fromstudinfo'		=> $existingstud,
								'deleted'			=> 0,
								'createdby'			=> auth()->user()->name,
								'createddatetime'	=> date('Y-m-d H:i:s')
							]);
	
						
					}
				
				$checkenrollstud = DB::table('tv_enrolledstud')
					->where('studid', $tvstudid)
					->where('courseid',$courseid)
					->where('batchid',$batchid)
					->where('deleted',0)
					->first();

				if($checkenrollstud)
				{
					return 0;
				}else{
					DB::table('tv_enrolledstud')
						->insert([
							'studid'			=> $tvstudid,
							'courseid'			=> $courseid,
							'batchid'			=> $batchid,
							'dateenrolled'		=> date('Y-m-d H:i:s'),
							'status'			=> 1,
							'deleted'			=> 0,
							'createdby'			=> auth()->user()->id,
							'createddatetime'	=> date('Y-m-d H:i:s')
						]);
					return 1;
				}
			}elseif($request->get('action') == 'unenrollstudent')
			{
				DB::table('tv_enrolledstud')
					->where('id', $request->get('enrolledstudid'))
					->update([
						'deleted'			=> 1,
						'deletedby'			=> auth()->user()->id,
						'deleteddatetime'	=> date('Y-m-d H:i:s')
					]);
				return 1;
			}
			elseif($request->get('action') == 'export')
			{
				$courseid = $request->get('courseid');
				$courseid = $request->get('courseid');
				$batchid = $request->get('batchid');
				
				
				if($courseid>0)
				{
					$courses =collect($courses)->where('id', $courseid)->values()->all();
				}
				
				$batches = DB::table('tv_batch')
					->where('deleted',0)
					->where('isactive',1)
					->get();
					

				if($batchid>0)
				{
					$batches =collect($batches)->where('id', $batchid)->values()->all();
				}

				foreach($courses as $course)
				{
					$course->batches = collect($batches)->where('courseid', $course->id)->values()->all();

					if(count($course->batches)>0)
					{
						foreach($course->batches as $eachbatch)
						{
							$eachbatch->students = DB::table('tv_enrolledstud')
								->select('tv_studinfo.*','tv_enrolledstud.dateenrolled')
								->join('tv_studinfo','tv_enrolledstud.studid','=','tv_studinfo.id')
								->where('batchid', $eachbatch->id)
								->where('tv_enrolledstud.status','1')
								->where('tv_enrolledstud.deleted','0')
								->get();

							if(count($eachbatch->students) > 0)
							{
								foreach($eachbatch->students as $eachstudent)
								{
									$eachstudent->gender = strtolower($eachstudent->gender);
								}
							}
						}
					}
				}


                $pdf = PDF::loadview('enrollment/techvoc/pdf_results',compact('courses')); 
                return $pdf->stream('Tech Voc Enrollment Report.pdf');
				
			}
		}
	}
	public function tvv2studinfo(Request $request)
	{
		
		if($request->get('action') == 'studinfoupdate')
		{
			DB::table('tv_studinfo')
				->where('id', $request->get('studid'))
				->update([
					'firstname'				=> $request->get('firstname'),
					'middlename'			=> $request->get('middlename'),
					'lastname'				=> $request->get('lastname'),
					'suffix'				=> $request->get('suffix'),
					'gender'				=> $request->get('gender'),
					'dob'					=> $request->get('dob'),
					'nationalityid'			=> $request->get('nationalityid'),
					'contactno'				=> str_replace("(+63) ", "", $request->get('contactno')),
					'street'				=> $request->get('street'),
					'barangay'				=> $request->get('barangay'),
					'city'					=> $request->get('city'),
					'province'				=> $request->get('province'),
					'fathername'			=> $request->get('fathername'),
					'foccupation'			=> $request->get('foccupation'),
					'fcontactno'			=> str_replace("(+63) ", "", $request->get('fcontactno')),
					'mothername'			=> $request->get('mothername'),
					'moccupation'			=> $request->get('moccupation'),
					'mcontactno'			=> str_replace("(+63) ", "", $request->get('mcontactno')),
					'guardianname'			=> $request->get('guardianname'),
					'guardianrelation'		=> $request->get('guardianrelation'),
					'gcontactno'			=> str_replace("(+63) ", "", $request->get('gcontactno')),
					'ismothernum'			=> $request->get('ismother'),
					'isfathernum'			=> $request->get('isfather'),
					'isguardiannum'			=> $request->get('isguardian')
				]);
			return 1;
		}
	}
}
