<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use PDF;
class SchoolFormsController extends Controller
{
    public static function capitalize_word($request_string)
    {
        $vowels_regex = "/(?=([aeiou]){3}([^aeiou])$)/";
        $vowels = array('a','e','i','o','u','A','E','I','O','U');
        $re = "/(\b[ivx]+)\b/i";
        // function checkRomanNumeral($_string){
        //     $matched = preg_match_all($re, $_string, $matches);
        //     return $matched;
        // }
    
        $word = $request_string;
        // function capitalize($word)
        // {
            $wordstring = '';
            if (strpos($word, ' ')) { 
                $word = explode(' ', $word);
                foreach($word as $key=>$eachword)
                {
                    $finalword = '';
                    if(strlen($eachword) > 2)
                    {
                        if (str_contains($eachword, '-')) { 
                            $words = explode('-', $eachword);
                            if(count($words)>0)
                            {
                                foreach($words as $keyword=>$each_word)
                                {
                                    if(preg_match_all($re, strtoupper($each_word), $matches) > 0)
                                    {
                                        $uppercase =   preg_replace_callback(
                                                        "/(\b[ivx]+)\b/i",                                   // Pattern to match Roman Numberals; case insensitive
                                                        function($matches){return strtoupper($matches[1]);}, // Function to return numerals in uppercase
                                                        $each_word                                              // Subject to run pattern against
                                            );
        
                                        if(strtoupper($each_word) == $uppercase)
                                        {
                                            $finalword.=$uppercase;
                                        }
                                    }else{
                                        $finalword.=ucwords(mb_convert_case($each_word, MB_CASE_LOWER, "UTF-8"));
                                    }
                                    if(isset($words[$keyword+1]))
                                    {
                                        $finalword.='-';
                                    }
                                }
                            }
                        }else{
                            if(preg_match_all($re, strtoupper($eachword), $matches) > 0)
                            {
                                $uppercase =   preg_replace_callback(
                                                "/(\b[ivx]+)\b/i",                                  
                                                function($matches){return strtoupper($matches[1]);},
                                                $eachword                                           
                                    );
        
                                if(strtoupper($eachword) == $uppercase)
                                {
                                    $finalword.=$uppercase;
                                }
                            }else{
                                $finalword.=ucwords(mb_convert_case($eachword, MB_CASE_LOWER, "UTF-8"));
                            }
                        }
                    }else{
                        try{
                            if(in_array($eachword[1], $vowels))
                            {
                                $finalword.=ucwords(mb_convert_case($eachword, MB_CASE_LOWER, "UTF-8"));
                            }else{
                                $finalword.=strtoupper($eachword);
                            }
                        }catch(\Exception $error)
                        {
                            $finalword.=strtoupper($eachword);
                        }
                    }
                    
                    if(isset($word[$key+1]))
                    {
                        $finalword.=' ';
                    }
                    $wordstring.=$finalword;
                }
            }else{
                $wordstring.=ucwords(mb_convert_case($word, MB_CASE_LOWER, "UTF-8"));
            }
            return $wordstring;
    }
    
    public static function lowercase_word($given_coursename)
    {
        $exclude = array('and','in','of','the','on','at','or','for','as','sa','ng','from','to');
        $course_desc = strtolower($given_coursename);
        $words = explode(' ', $course_desc);
        foreach($words as $key => $word) {
            
            if (str_contains($word, '-')) { 
                $word = explode('-', $word);
                $word = ucfirst($word[0]).'-'.ucfirst($word[1]);
                }
            if($key == 0)
            {
                $words[$key] = ucwords($word, ' [{(/');
            }else{
                if(in_array($word, $exclude)) {
                    if($key == 0)
                    {
                        $word = ucwords(strtolower($word));
                    }else{
                        
                        $word = strtolower($word);
                    }
                    continue;
                }
                $words[$key] = ucwords($word, ' [{(/');
            }
        }
        return $coursename = implode(' ', $words);
    }
    public function sf7(Request $request)
    {
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;

        if(Session::get('currentPortal') == 1){

            $extends = "teacher.layouts.app";
            
        }elseif(Session::get('currentPortal') == 2){

            $extends = "principalsportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 3){

            $extends = "registrar.layouts.app";

        }elseif( Session::get('currentPortal') == 8){

            $extends = "admission.layouts.app2";

        }elseif(Session::get('currentPortal') == 4  ||  Session::get('currentPortal') == 15){

            $extends = "finance.layouts.app";

        }elseif(Session::get('currentPortal') == 6){

            $extends = "adminPortal.layouts.app2";

        }elseif(Session::get('currentPortal') == 10 || $refid == 26){

            $extends = "hr.layouts.app";

        }elseif(Session::get('currentPortal') == 12){

            $extends = "adminITPortal.layouts.app";

        }elseif(Session::get('currentPortal') == 14){

            $extends = "deanportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 16){

            $extends = "chairpersonportal.layouts.app2";

        }elseif(Session::get('currentPortal') == 18){

            $extends = "ctportal.layouts.app2";

        }else{

            $extends = "general.defaultportal.layouts.app";

        }
        if(!$request->has('action'))
        {
            $departments = DB::table('hr_departments')
                ->where('deleted','0')
                ->get();

            $gradelevels = DB::table('gradelevel')
				->where('acadprogid','!=',6)
                ->where('deleted','0')
                ->orderBy('sortid','asc')
                ->get();

            return view('schoolforms.form7.index')
                ->with('departments', $departments)
                ->with('gradelevels', $gradelevels)
                ->with('extends', $extends);
        }else{
            $syid = $request->get('syid');
            $monthname = $request->get('month');
            $monthint = date_parse($monthname)['month'];
            // return $request->get('length');
            $sydesc = DB::table('sy')
                ->where('id', $syid)
                ->first()->sydesc;

            $departmentids = json_decode($request->get('departmentids'));
            $levelids = json_decode($request->get('levelids'));
            if($request->get('action') == 'filter')
            {
                // return $request->all();
                $search = $request->get('search');
                $search = $search['value'];
    
                $employees = DB::table('teacher')
                ->select(
                    'teacher.*',
                    'employee_personalinfo.dob',
                    'employee_personalinfo.gender',
                    'employee_personalinfo.address',
                    'employee_personalinfo.primaryaddress',
                    'employee_personalinfo.presstreet',
                    'employee_personalinfo.presbarangay',
                    'employee_personalinfo.prescity',
                    'employee_personalinfo.presprovince',
                    'employee_personalinfo.contactnum',
                    'employee_personalinfo.email',
                    'employee_personalinfo.spouseemployment',
                    'employee_personalinfo.numberofchildren',
                    'employee_personalinfo.emercontactname',
                    'employee_personalinfo.emercontactrelation',
                    'employee_personalinfo.emercontactnum',
                    'hr_empstatus.description as employmentstatus',
                    'employee_personalinfo.departmentid',
                    'usertype.utype as designation'
                    )
                ->leftJoin('hr_empstatus','teacher.employmentstatus','=','hr_empstatus.id')
                ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
                ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
                ->where('teacher.deleted','0');
                if(count($departmentids)>0)
                {
                    $employees = $employees->whereIn('departmentid',$departmentids);
                }
                if($search != null){
                        $employees = $employees->where(function($query) use($search){
                                            $query->orWhere('firstname','like','%'.$search.'%');
                                            $query->orWhere('lastname','like','%'.$search.'%');
                                    });
                }
                
                if(count($levelids) > 0)
                {
                    $employees = $employees
                        ->orderBy('lastname','asc')
                        // ->whereIn('studinfo.studstatus',[1,2,4])
                        ->get();
                }else{
                    $employees = $employees->take($request->get('length'))
                    ->skip($request->get('start'))
                        ->orderBy('lastname','asc')
                        // ->whereIn('studinfo.studstatus',[1,2,4])
                        ->get();
                }
                    
                $employeescount = DB::table('teacher')
                ->select(
                    'teacher.*',
                    'employee_personalinfo.dob',
                    'employee_personalinfo.gender',
                    'employee_personalinfo.address',
                    'employee_personalinfo.primaryaddress',
                    'employee_personalinfo.presstreet',
                    'employee_personalinfo.presbarangay',
                    'employee_personalinfo.prescity',
                    'employee_personalinfo.presprovince',
                    'employee_personalinfo.contactnum',
                    'employee_personalinfo.email',
                    'employee_personalinfo.spouseemployment',
                    'employee_personalinfo.numberofchildren',
                    'employee_personalinfo.emercontactname',
                    'employee_personalinfo.emercontactrelation',
                    'employee_personalinfo.emercontactnum',
                    'hr_empstatus.description as employmentstatus',
                    'employee_personalinfo.departmentid',
                    'usertype.utype as designation'
                    )
                ->leftJoin('hr_empstatus','teacher.employmentstatus','=','hr_empstatus.id')
                ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
                ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
                ->where('teacher.deleted','0');
                    
                if(count($departmentids)>0)
                {
                    $employeescount = $employeescount->whereIn('departmentid',$departmentids);
                }
                if($search != null){
                        $employeescount = $employeescount->where(function($query) use($search){
                                        $query->orWhere('firstname','like','%'.$search.'%');
                                        $query->orWhere('lastname','like','%'.$search.'%');
                                    });
                }
                
                $employeescount = $employeescount
                    ->orderBy('lastname','asc')
                    // ->whereIn('studinfo.studstatus',[1,2,4])
                    ->count();
            }else{
                $employees = DB::table('teacher')
                    ->select(
                        'teacher.*',
                        'employee_personalinfo.dob',
                        'employee_personalinfo.gender',
                        'employee_personalinfo.address',
                        'employee_personalinfo.primaryaddress',
                        'employee_personalinfo.presstreet',
                        'employee_personalinfo.presbarangay',
                        'employee_personalinfo.prescity',
                        'employee_personalinfo.presprovince',
                        'employee_personalinfo.contactnum',
                        'employee_personalinfo.email',
                        'employee_personalinfo.spouseemployment',
                        'employee_personalinfo.numberofchildren',
                        'employee_personalinfo.emercontactname',
                        'employee_personalinfo.emercontactrelation',
                        'employee_personalinfo.emercontactnum',
                        'hr_empstatus.description as employmentstatus',
                        'employee_personalinfo.departmentid',
                        'usertype.utype as designation'
                        )
                    ->leftJoin('hr_empstatus','teacher.employmentstatus','=','hr_empstatus.id')
                    ->leftJoin('employee_personalinfo','teacher.id','=','employee_personalinfo.employeeid')
                    ->leftJoin('usertype','teacher.usertypeid','=','usertype.id')
                    ->where('teacher.deleted','0')
                    // ->where('employee_personalinfo.deleted','0')
                    ->get();
                if(count($departmentids)>0)
                {
                    $employees = collect($employees)->whereIn('departmentid',$departmentids)->values();
                }
            }

                // scheduling/teacher/schedule?syid=1&semid=2&teacherid=8

            if(count($employees)>0)
            {
                foreach($employees as $employee)
                {
                    $employee->sortname = $employee->lastname.', '.$employee->firstname.' '.$employee->suffix.' '.$employee->middlename;
                    $personalaccounts = DB::table('employee_accounts')
                        ->where('employeeid', $employee->id)
                        ->where('deleted','0')
                        ->get();

                    $employee->accounts = $personalaccounts;
                    $educationalbackground = DB::table('employee_educationinfo')
                        ->where('employeeid', $employee->id)
                        ->where('deleted','0')
                        ->get();

                    $educationalbackground = collect($educationalbackground)->sortByDesc('schoolyear')->values();
                    $employee->educationalbackground = $educationalbackground;

                    $request->request->add(['semid' => DB::table('semester')->where('isactive','1')->first()->id]);
                    $request->request->add(['teacherid' => $employee->id]);

                    $schedules = \App\Http\Controllers\SuperAdminController\TeacherProfileController::schedule($request);
					$schedules = collect($schedules)->where('acadprogid','!=',6)->values();
					
                    $employee->schedules = $schedules;
                    $employee->numofscheds = count($schedules);
                    $employee->numofschedsleft = (count($schedules)-8) > 0 ? (count($schedules)-8) : 0;

                    // if(count($schedules)>0)
                    // {
                    //     return $schedules;
                    // }
                    $portals = DB::table('employee_accounts')
                        ->where('employeeid', $employee->id)
                        ->where('deleted','0')
                        ->get();
                    $employee->portals = $portals;
                    $employee->withschedules = 0;
                    if(count($levelids)>0)
                    {
                        $employee->withschedules = collect($schedules)->whereIn('levelid', $levelids)->count() > 0 ? 1 : 0;
                    }else{
                        $employee->withschedules = 1;
                    }
                }
            }
            // /
            $employees = collect($employees)->where('withschedules','1')->values();
            $withschedemployees = collect($employees)->where('withschedules','1')->values();
            
            if($request->get('action') == 'filter')
            {
                if(count($levelids) > 0)
                {
                    if($request->get('start') == null)
                    {
                        $employees = collect($employees)
                        ->take($request->get('length'))
                        ->skip(($request->get('start') == null ? 0 : $request->get('start')))
                        ->values();
                    }else{
                        $employees = collect($employees)
                        ->skip(($request->get('start') == null ? 0 : $request->get('start')))
                        ->take($request->get('length'))
                        ->values();
                    }
                }
                // return count($employees);
                // return ($request->get('start') == null ? 0 : $request->get('start'));
                $array_data = array();
                if(count($employees)>0)
                {
                    foreach($employees as $employee)
                    {
                        if(count($employee->schedules)==0)
                        {
                            array_push($array_data, array(
                                $employee->firstname.' '.$employee->middlename.' '.$employee->lastname.' '.$employee->suffix.' - '.$employee->tid,
                                collect($employee->accounts)->where('accountdescription','TIN')->first()->accountnum ?? $employee->tid,
                                $employee->gender != null ? strtoupper($employee->gender[0]) : '',
                                '',
                                $employee->designation,
                                $employee->employmentstatus,
                                '',
                                '',
                                '',
                                '',
                                '',
                                '',
                                '',
                                '',
                                ''
                            ));
                        }else{
                            foreach($employee->schedules as $eachschedule)
                            {
                                array_push($array_data, array(
                                    $employee->firstname.' '.$employee->middlename.' '.$employee->lastname.' '.$employee->suffix.' - '.$employee->tid,
                                    collect($employee->accounts)->where('accountdescription','TIN')->first()->accountnum ?? $employee->tid,
                                    $employee->gender != null ? strtoupper($employee->gender[0]) : '',
                                    '',
                                    $employee->designation,
                                    $employee->employmentstatus,
                                    '',
                                    '',
                                    '',
                                    $eachschedule->subjcode.' / '.$eachschedule->levelname.' - '.$eachschedule->sectionname,
                                    collect($eachschedule->schedule)->pluck('day'),
                                    collect($eachschedule->schedule)->pluck('start'),
                                    collect($eachschedule->schedule)->pluck('end'),
                                    '',
                                    ''
                                ));
                            }
                        }
                    }
                }
                    
                if(count($levelids) > 0)
                {
                    return @json_encode((object)[
                        'data'=>$array_data,
                        'recordsTotal'=> count($withschedemployees),
                        'recordsFiltered'=> count($withschedemployees) // $employeescount
                    ]);
                }else{
                    return @json_encode((object)[
                        'data'=>$array_data,
                        'recordsTotal'=> $employeescount,
                        'recordsFiltered'=> $employeescount // $employeescount
                    ]);
                }
                // return view('schoolforms.form7.results')
                //     ->with('employees', $employees);
            }else{   
                
                $designations = Db::table('usertype')
                    ->select(
                        'usertype.id',
                        'usertype.sortid',
                        'usertype.utype as designation',
                        'departmentid',
                        'usertype.constant',
                        'hr_school_department.department as departmentname',
                        'teacher.lastname',
                        'teacher.firstname',
                        'teacher.middlename',
                        'teacher.suffix'
                        )
                    ->where('usertype.deleted','0')
                    ->leftJoin('teacher','usertype.created_by','=','teacher.userid')
                    ->leftJoin('hr_school_department','usertype.departmentid','=','hr_school_department.id')
                    ->where('usertype.utype','!=','PARENT')
                    ->where('usertype.utype','!=','STUDENT')
                    ->where('usertype.utype','!=','SUPER ADMIN')
                    ->orderBy('id','asc')
                    ->get();
                    
                foreach($designations as $key=>$designation){
                    if($designation->sortid == null)
                    {
                        $designation->sortid = $key;
                    }
                    if($designation->departmentid == null){
                        
                        $designation->departmentid = 0;
                        $designation->departmentname = "";

                    }                
                }
                $designations = collect($designations)->sortBy('sortid')->values()->all();
                 
                $allemployees = array();
                foreach($designations as $designation)
                {
                    $des_employees = collect($employees)->where('usertypeid', $designation->id)->values()->all();
                    if(count($des_employees)>0)
                    {
                        // return $des_employees;
                        $des_employees = collect($des_employees)->sortBy('sortname')->values()->all();
                        foreach($des_employees as $eachemp)
                        {
                            array_push($allemployees, $eachemp);
                        }
                    }
                }
                $employees = $allemployees;
                // return $employees;
                $schoolinfo = Db::table('schoolinfo')
                    ->select(
                        'schoolinfo.schoolid',
                        'schoolinfo.schoolname',
                        'schoolinfo.authorized',
                        'refcitymun.citymunDesc as division',
                        'schoolinfo.district',
                        'schoolinfo.address',
                        'schoolinfo.picurl',
                        'refregion.regDesc as region',
                        'schoolinfo.divisiontext',
                        'schoolinfo.districttext',
                        'schoolinfo.regiontext'
                    )
                    ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
                    ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
                    ->first();         
                    
                // $pdf = PDF::loadview('schoolforms/form7/pdf_schoolform7', compact('schoolinfo','sydesc','monthname','employees'))->setPaper('8.5x14', 'landscape');
                // $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
                // return $pdf->stream('School Form 7 - School Personnel Assignment List and Basic Profile.pdf');     

                $inputFileType = 'Xlsx';
                $inputFileName = base_path().'/public/excelformats/schoolform7final.xlsx';
                // $sheetname = 'Front';

                /**  Create a new Reader of the type defined in $inputFileType  **/
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                /**  Advise the Reader of which WorkSheets we want to load  **/
                $reader->setLoadAllSheets();
                /**  Load $inputFileName to a Spreadsheet Object  **/
                $spreadsheet = $reader->load($inputFileName);
                $sheet = $spreadsheet->getSheet(0);
                
                $borderstyle = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                        ]
                    ]
                ];
                
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setWorksheet($sheet);
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                
                $drawing->setPath(base_path().'/public/assets/images/department_of_Education.png');
                $drawing->setHeight(105);
                $drawing->setCoordinates('A1');
                $drawing->setOffsetX(24);
                $drawing->setOffsetY(16);
                
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setWorksheet($sheet);
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                
                $drawing->setPath(base_path().'/public/assets/images/deped_logo.png');
                $drawing->setHeight(100);
                $drawing->setCoordinates('P1');
                $drawing->setOffsetX(10);
                $drawing->setOffsetY(5);

                $sheet->setcellValue('D5', $schoolinfo->schoolid);
                $sheet->setcellValue('H5', $schoolinfo->regiontext != null ? $schoolinfo->regiontext : $schoolinfo->region);
                $sheet->setcellValue('K5', $schoolinfo->divisiontext != null ? $schoolinfo->divisiontext : $schoolinfo->division);
                $sheet->setcellValue('D7', $schoolinfo->schoolname);
                $sheet->setcellValue('K7', $schoolinfo->districttext != null ? $schoolinfo->districttext : $schoolinfo->district);
                $sheet->setcellValue('R7', $sydesc);
                
                $signatories = DB::table('signatory')
                                ->where('form','form7')
                                ->where('syid', $syid)
                                ->where('deleted','0')
                                ->first();
                if($signatories)
                {
                    $sheet->setcellValue('O25', $signatories->title);
                    $sheet->setcellValue('O27', $signatories->name);
                    $sheet->setcellValue('Q28', $signatories->description);
                }
                // return $employees;
                // $employees = collect($employees)->take(3);
                // return $employees;
                $startcellno = 20;
                $defaultnumofrows = 9;
                if(count($employees)>0)
                {
                    $sheet->insertNewRowBefore(21, ((count($employees)*9)+collect($employees)->sum('numofschedsleft')));
                    
                    foreach($employees as $employee)
                    {
                        $mergedfromcell = $startcellno;
                        $sheet->setcellValue('A'.$startcellno,collect($employee->accounts)->where('accountdescription','T.I.N')->first() ? collect($employee->accounts)->where('accountdescription','T.I.N')->first()->accountnum : '');
                        $sheet->setcellValue('B'.$startcellno,$employee->firstname.' '.(isset($employee->middlename[0]) ? $employee->middlename[0].'.' : '').' '.$employee->lastname.' '.$employee->suffix);
                        $sheet->setcellValue('C'.$startcellno,isset($employee->gender[0]) ? strtoupper($employee->gender[0]) : '');
                        $sheet->getStyle('C'.$startcellno)->getAlignment()->setHorizontal('center');
                        $sheet->setcellValue('F'.$startcellno,$employee->designation);
                        $sheet->getStyle('F'.$startcellno)->getAlignment()->setHorizontal('center');
                        $sheet->getStyle('F'.$startcellno)->getAlignment()->setVertical('center');
                        $sheet->setcellValue('G'.$startcellno,$employee->employmentstatus);

                        if(count($employee->educationalbackground)>0)
                        {
                            $sheet->setcellValue('H'.$startcellno,$employee->educationalbackground[0]->coursetaken);
                            $sheet->setcellValue('I'.$startcellno,$employee->educationalbackground[0]->major);
                            // $sheet->setcellValue('J'.$startcellno,$employee->employmentstatus);
                        }
                        if(count($employee->schedules) == 0)
                        {
                            for($x = 0; $x<8; $x++)
                            {
                                $startcellno+=1;
                            }
                            $sheet->mergeCells('M'.$startcellno.':P'.$startcellno);
                            $sheet->setcellValue('M'.$startcellno,'Ave.  Minutes per Day');
                            $sheet->getStyle('M'.$startcellno)->getAlignment()->setHorizontal('right');

                        }else{
                            
                            foreach($employee->schedules as $keysched=>$eachschedule)
                            {
                                $sheet->setcellValue('M'.$startcellno,$eachschedule->subjcode.' / '.$eachschedule->levelname.' - '.$eachschedule->sectionname);
                                $sheet->setcellValue('N'.$startcellno,$eachschedule->schedule[0]->day);
                                $sheet->setcellValue('O'.$startcellno,$eachschedule->schedule[0]->start);
                                $sheet->setcellValue('P'.$startcellno,$eachschedule->schedule[0]->end);
                                $startcellno+=1;
                            }
                            for($x = count($employee->schedules); $x < 8; $x++)
                            {
                                $startcellno+=1;
                            }
                            $sheet->setcellValue('M'.$startcellno,'Ave.  Minutes per Day');
                            $sheet->getStyle('M'.$startcellno)->getAlignment()->setHorizontal('right');
                        }
                        $sheet->mergeCells('A'.$mergedfromcell.':A'.$startcellno);
                        $sheet->mergeCells('B'.$mergedfromcell.':B'.$startcellno);
                        $sheet->mergeCells('C'.$mergedfromcell.':C'.$startcellno);
                        $sheet->mergeCells('D'.$mergedfromcell.':E'.$startcellno);
                        $sheet->mergeCells('F'.$mergedfromcell.':F'.$startcellno);
                        $sheet->mergeCells('G'.$mergedfromcell.':G'.$startcellno);
                        $sheet->mergeCells('H'.$mergedfromcell.':H'.$startcellno);
                        $sheet->mergeCells('I'.$mergedfromcell.':J'.$startcellno);
                        $sheet->mergeCells('K'.$mergedfromcell.':L'.$startcellno);
                        $sheet->mergeCells('R'.$mergedfromcell.':S'.$startcellno);
                        $sheet->mergeCells('M'.$startcellno.':P'.$startcellno);
                        $mergedfromcell = 0;
                        $startcellno+=1;
                    }
                    for($x = 0; $x < 4; $x++)
                    {
                        $sheet->removeRow($startcellno);
                    }
                }

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="School Form 7 - ('.$sydesc.').xlsx"');
                $writer->save("php://output");
                exit();
            }
        }
    
	}
}
