<?php

namespace App\Http\Controllers\RegistrarControllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class RegistrarFunctionController extends Controller
{
    public function studentinfo()
    {
    $level = db::table('gradelevel')
            ->orderBy('sortid')
            ->get();

    
    return view('registrar.functions.studinfo')
        ->with('level', $level);
    }
    public function enrollgetinfo(Request $request)
    {
	  	if($request->ajax())
	  	{
	  		$studid = $request->get('studid');

	  		$glevel = db::table('gradelevel')
	  				->where('deleted', 0)
	  				->orderBy('sortid')
	  				->get();

	  		// $syid = RegistrarModel::getSYID();

	  		$sy = db::table('sy')
	  				->get();

	  		$studentstatus = db::table('studentstatus')
	  				->get();

	  		$student = db::table('studinfo')
	  				->where('id', $studid)
	  				->first();

	  		$sections = db::table('sections')
	  				->where('deleted', 0)
	  				->where('levelid', $student->levelid)
	  				->get();

	  		$level = '';

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

	  		$section = '<option></option>';

	  		foreach($sections as $sec)
	  		{
	  			$section .= '
	  				<option value="'.$sec->id.'">'.$sec->sectionname.'</option>
	  			';
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


	  		$studstatus = '';
	  		foreach($studentstatus as $stat)
	  		{
	  			if($stat->id == 1)
	  			{
	  				$studstatus .= '
	  				<option selected value="'.$stat->id.'">'.$stat->description.'</option>
	  			';	
	  			}
	  			$studstatus .= '
	  				<option value="'.$stat->id.'">'.$stat->description.'</option>
	  			';
	  		}
	  		// return $section;
	  		$data = array(
	  			'studid' =>$student->id,
	  			'lrn' => $student->lrn,
	  			'name' => $student->firstname . ' ' . $student->middlename . ' ' . $student->lastname . ' ' . $student->suffix,
	  			'dob' => $student->dob,
	  			'sid' => $student->sid,
	  			'level' => $level,
	  			'section' => $section,
	  			'syear' => $syear,
	  			'studstatus' => $studstatus
	  		);

	  		echo json_encode($data);

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

	  	$data = array(
	  		'glevel' => $glevel,
	  		'religion' => $religion,
	  		'mothertongue' => $mothertongue,
	  		'ethnic' => $ethnic
	  	);
	  	return view('registrar.functions.studinfo_create')->with($data);
    }
    public function studentinsert(Request $request)
    {
        if($request->ajax())
        {
            $lrn = $request->get('lrn');
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

            $isfather = $request->get('isfather');
            $ismother = $request->get('ismother');
            $isguardian = $request->get('isguardian');


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
                        'rfid' => $rfid,
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
    public function studentsearch(Request $request)
    {
	  	if($request->ajax())
	  	{
	  		$glevel = $request->get('glevel');
	  		$query = $request->get('query');

	  		if($glevel > 0)
	  		{
	    		$student = db::table('studinfo')
	    				->select('studinfo.*', 'gradelevel.levelname')
	    				->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
	    				->where('levelid', $glevel)
	    				->where('lastname', 'like', '%'.$query.'%')
	    				->orWhere('firstname', 'like', '%'.$query.'%')
	    				->where('levelid', $glevel)
	    				->orderBy('lastname','ASC')
	    				->orderBy('firstname','ASC')
	    				->take(10)
	    				->get();
	  		}
	  		else
	  		{
	  			$student = db::table('studinfo')
	  					->select('studinfo.*', 'gradelevel.levelname')
	    				->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
	    				->where('lastname', 'like', '%'.$query.'%')
	    				->where('studinfo.deleted', 0)
	    				->orWhere('firstname', 'like', '%'.$query.'%')
	    				->where('studinfo.deleted', 0)
	    				->orderBy('lastname','ASC')
	    				->orderBy('firstname','ASC')
	    				->take(10)
	    				->get();		
	  		}

	  		$output = '';

	  		foreach($student as $s)
	  		{
	  			$esc = '';
	  			if($s->esc == 1)
	  			{
	  				$esc = '<i class="fas fa-check text-primary"></i>';
	  			}

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
	  			else
	  			{
	  				$status = '<span style="width:1px height:100%" class="bg-light">&nbsp;</span>';		
	  			}

	  			if($s->studstatus == 0)
	  			{
		  			$output .= '

		  				<tr>
		  					<td> '.$status.' '.$s->sid.'</td>
		  					<td id="studname"><a href="studentinfo/edit/'.$s->id.'">'.$s->firstname.' '.$s->lastname.'</a></td>
		  					<td>'.$s->gender.'</td>
		  					<td>'.$s->levelname.'</td>
		  					<td>'.$s->sectionname.'</td>
		  					<td>'.$esc.'</td>
		  					<td><button id="cmdenroll" class="btn btn-info btn-block" data-toggle="modal" data-value="'.$s->id.'" data-sid="'.$s->sid.'" data-target="#enrollstud">Enroll</button></td>
		  					<td><button class="btn btn-danger btn-block">Delete</button></td>
		  				</tr>

		  			';
		  		}
		  		else
		  		{
		  			$output .= '

		  				<tr>
		  					<td> '.$status.' '.$s->sid.'</td>
		  					<td id="studname"><a href="studentinfo/edit/'.$s->id.'">'.$s->firstname.' '.$s->lastname.'</a></td>
		  					<td>'.$s->gender.'</td>
		  					<td>'.$s->levelname.'</td>
		  					<td>'.$s->sectionname.'</td>
		  					<td>'.$esc.'</td>
		  					<td colspan="2"><button class="btn btn-info btn-block">View Enrollment</button></td>
		  					
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
	  	
	  	$data = array(
	  		'stud' => $stud,
	  		'glevel' => $glevel,
	  		'mothertongue' => $mothertongue,
	  		'religion' => $religion,
	  		'ethnic' => $ethnic
	  	);
	  	return view('registrar.functions.studinfo_edit')->with($data);
	  }

	  public function studentupdate(Request $request)
	  {
	  	if($request->ajax())
	  	{
	  		$studid = $request->get('studid');
	  		$lrn = $request->get('lrn');
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

	  		$isfather = $request->get('isfather');
	  		$ismother = $request->get('ismother');
	  		$isguardian = $request->get('isguardian');

	  		$upd = DB::table('studinfo')
	  				->where('id', $studid)
	  				->update([
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
	  					'rfid' => $rfid,
	  				]);
	  	}
	  }
    public function admission(Request $request)
    {
        return view('registrar.functions.admission');
    }
    public function searchPreReg(Request $request)
    {
        if($request->ajax())
        {
            $code = $request->get('code');

            $prereg = db::table('preregistration')
                ->where('queing_code', 'like', '%'.$code.'%')
                ->where('status', 0)
                ->get();

            $output = '';

            foreach($prereg as $reg)
            {
                $output .= '
                    <tr>
                        <td>'.$reg->first_name.' '.$reg->middle_name.' ' .$reg->last_name. ' ' .$reg->suffix.'</td>
                        <td>'.$reg->dob.'</td>
                        <td>'.$reg->contact_number.'</td>
                        <td>'.$reg->queing_code.'</td>
                        <td><a href="/admission/edit/'.$reg->queing_code.'" class="btn btn-primary btn-block text-white">Register</td>
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

	  	$data = array(
	  		'glevel' => $glevel,
	  		'religion' => $religion,
	  		'mothertongue' => $mothertongue,
	  		'ethnic' => $ethnic,
	  		'stud' => $stud,
	  		'code' => $code
	  	);

	  	return view('registrar.functions.admission_edit')->with($data);
	  }

	  public function admissionregister(Request $request)
	  {
	  	if($request->ajax())	
	  	{	
	  		$code = $request->get('code');
	  		$lrn = $request->get('lrn');
	  		$glevel = $request->get('glevel');
	  		$code = $request->get('code');
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
	  		

	  		$isfather = $request->get('isfather');
	  		$ismother = $request->get('ismother');
	  		$isguardian = $request->get('isguardian');


	  		$validate = db::table('studinfo')
	  				->where('lastname', $lname)
	  				->where('firstname', $fname)
	  				->where('middlename', $mname)
	  				->where('dob', $dob)
	  				->where('deleted', 0)
	  				->get();

	  		if(count($validate) > 0)
	  		{

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
	  					'studstatus' => 0
	  				]);	

	  			$idprefix = db::table('idprefix')->first();
	  		
		  		$id = sprintf('%06d', $studid);

		  		$sid = $idprefix->prefix . $id;

		  		$upd = db::table('studinfo')
		  			->where('id', $studid)
		  			->update([
		  				'sid' => $sid
		  			]);

		  		$updstat = db::table('preregistration')
		  				->where('queing_code', $code)
		  				->update([
		  					'status' => 1
		  				]);
	  		}
	  	}
	  }
	public function forms_sf2(Request $request)
	{
		if(!$request->has('action'))
		{
			return view('registrar.setup.form2.index');
		}else{

			if($request->get('action') == 'getsetups')
			{
				$sections = DB::table('sectiondetail')
					->select('sections.id','sections.sectionname','teacher.id as teacherid','teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix','gradelevel.id as levelid','gradelevel.levelname','gradelevel.sortid as levelsortid')
					->join('sections','sectiondetail.sectionid','=','sections.id')
					->join('gradelevel','sections.levelid','=','gradelevel.id')
					->join('teacher','sectiondetail.teacherid','=','teacher.id')
					->where('sectiondetail.syid',$request->get('selectsy'))
					->where('sectiondetail.deleted','0')
					->where('sections.deleted','0')
					->orderBy('sectionname','asc')
					->get();
					
				$sections = collect($sections)->sortBy('levelsortid')->values()->all();
				
				if(count($sections)>0)
				{
					foreach($sections as $section)
					{
						$setups = DB::table('sf2_setup')
						->select(
							'id',
							'teacherid',
							'syid',
							'strandid',
							'sectionid',
							'month',
							'year',
							'course',
							'createddatetime'
						)
						->where('syid',$request->get('selectsy'))
						->where('month',$request->get('selectmonth'))
						->where('year',$request->get('selectyear'))
						->where('sectionid',$section->id)
						->where('deleted','0')
						->orderBy('month','asc')
						->distinct()
						->get();
						if(count($setups)>0)
						{
							foreach($setups as $eachsetup)
							{
								$eachsetup->lockstatus = 0;

								if(DB::getSchemaBuilder()->hasTable('sf2_setuplock'))
								{
									$lockstatus = DB::table('sf2_setuplock')
										->where('setupid',$eachsetup->id)
										->where('deleted','0')
										->first();
									if($lockstatus)
									{
										$eachsetup->lockstatus = $lockstatus->lockstatus;
									}
								}
								
							}
						}
	
						$section->setups = $setups;
					}
				}
				
				return view('registrar.setup.form2.results')
					->with('sections',$sections);
			}else{
				
				if(DB::getSchemaBuilder()->hasTable('sf2_setuplock'))
				{
					$checkifexists = DB::table('sf2_setuplock')
						->where('setupid',$request->get('setupid'))
						->where('deleted','0')
						->first();
	
					if($checkifexists)
					{
						$newstatus = $checkifexists->lockstatus == 0 ? 1 : 0;
						DB::table('sf2_setuplock')
							->where('id',$checkifexists->id)
							->update([
								'lockstatus'		=> $newstatus,
								'updatedby'			=> auth()->user()->id,
								'updateddatetime'	=> date('Y-m-d H:i:s')
							]);
	
					}else{
						DB::table('sf2_setuplock')
							->insert([
								'setupid'			=> $request->get('setupid'),
								'lyear'				=> $request->get('selectyear'),
								'lmonth'			=> $request->get('selectmonth'),
								'createdby'			=> auth()->user()->id,
								'createddatetime'	=> date('Y-m-d H:i:s')
							]);
					}
				}
				
				return 1;
			}
		}
	}
}