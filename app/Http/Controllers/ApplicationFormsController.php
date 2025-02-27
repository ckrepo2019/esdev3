<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use TCPDF;
use PDF;
class ApplicationFormsController extends Controller
{
    public function index(Request $request)
    {
        $applications = array();
        $tesapplication = DB::table('studapplication_tes')
            ->select('id','studid','semid')
            ->where('syid', DB::table('sy')->where('isactive','1')->first()->id)
            ->where('deleted','0')
            ->where('submitted','1')
            ->get();

            
        if(count($tesapplication)>0)
        {
            foreach($tesapplication as $eachtesapp)
            {
                // $eachtesapp->type = 'tes';
                array_push($applications, (object)array(
                    'id'    => $eachtesapp->id,
                    'studid'=> $eachtesapp->studid,
                    'semid' => $eachtesapp->semid,
                    'type'  => 'tes'
                ));
            }
            // array_push($applications, $tesapplication);
        }
        
        return view('finance.reports.applications.index')
            ->with('applications', collect($applications)->groupBy('type'));
    }
    public function select(Request $request)
    {
        return $request->all();
    }
    public function stufapindex(Request $request)
    {
        if($request->has('action'))
        {
            if($request->get('action') == 'addnewtype')
            {
                // return $request->all();
                $checkifexists = DB::table('list_stufap')
                    ->where('classid', $request->get('classid'))                
                    ->where('name', 'like','%'.$request->get('typename').'%')                
                    ->where('amountpersem', $request->get('amount'))       
                    ->where('deleted','0')
                    ->first();
                    
                if($checkifexists)
                {
                    return 0;
                }else{

                    try{
                        DB::table('list_stufap')
                            ->insert([
                                'classid'               => $request->get('classid'),
                                'name'                  => $request->get('typename'),
                                'amountpersem'          => $request->get('amount'),
                                'createdby'             => auth()->user()->id,
                                'createddatetime'       => date('Y-m-d H:i:s')
                            ]);
                    return 1;

                    }catch(\Exception $error)
                    {
                        return $error;
                    }

                }
            }
            elseif($request->get('action') == 'updatetype')
            {
                $amount = $request->get('typeamount');
                $b = str_replace( ',', '', $amount );

                if( is_numeric( $b ) ) {
                    $amount = $b;
                }
                try{
                    DB::table('list_stufap')
                        ->where('id', $request->get('typeid'))
                        ->update([
                            'name'                  => $request->get('typename'),
                            'amountpersem'          => $amount,
                            'updatedby'             => auth()->user()->id,
                            'updateddatetime'       => date('Y-m-d H:i:s')
                        ]);
                return 1;

                }catch(\Exception $error)
                {
                    return $error;
                }
            }

        }else{

            $stufaps = DB::table('list_stufap')
                ->where('deleted','0')
                ->get();

            return view('finance.reports.stufap.index')
                ->with('stufaps',$stufaps);

        }
    }
    // public function stufapaddtype(Request $request)
    // {
    //     return $request->all();
    // }
    public function tesindex(Request $request)
    {
        return view('finance.reports.applications.tes.index');
    }
    public function tesgenerate(Request $request)
    {

        // return $request->all();
        $syid           = $request->get('selectsyid');
        $semid          = $request->get('selectsemid');
        $levelid        = $request->get('selectlevelid');
        $tesapplication = DB::table('studapplication_tes')
            ->select('studapplication_tes.*','studinfo.lastname','studinfo.firstname','studinfo.middlename','studinfo.middlename','studinfo.suffix','studinfo.sid','lrn','dob','studinfo.gender','studapplication_tes.flastname','studapplication_tes.fmiddlename','studapplication_tes.ffirstname','studapplication_tes.mmlastname','studapplication_tes.mmmiddlename','studapplication_tes.mmfirstname','college_courses.courseDesc as coursename','college_courses.courseabrv as coursecode','gradelevel.levelname','numofsiblings','studinfo.guardianname','studinfo.gcontactno')
            ->join('studinfo','studapplication_tes.studid','=','studinfo.id')
            ->join('college_courses','studapplication_tes.courseid','=','college_courses.id')
            ->join('gradelevel','studapplication_tes.levelid','=','gradelevel.id')
            ->where('studapplication_tes.syid', $syid)
            // ->where('studapplication_tes.semid', $semid)
            ->where('studapplication_tes.levelid', $levelid)
            ->where('studapplication_tes.deleted','0')
            ->where('submitted','1')
            ->orderByDesc('createddatetime')
            ->get();

        // return $tesapplication;
        if(count($tesapplication)>0)
        {
            foreach($tesapplication as $eachtesapp)
            {
                $eachtesapp->balance = DB::table('studledger')
                    ->select(DB::raw('SUM(`amount`) - SUM(payment) AS balance') )
                    ->where('studid', $eachtesapp->studid)
                    ->where('semid', $semid)
                    ->where('syid', $syid)
                    ->where('deleted','0')
                    ->first()->balance;

                if($eachtesapp->units == null)
                {
                    $eachtesapp->totalunits = db::table('college_studsched')
                    ->select(db::raw('SUM(lecunits) + SUM(labunits) AS totalunits'))
                    ->join('college_classsched', 'college_studsched.schedid', '=', 'college_classsched.id')
                    ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                    ->where('college_studsched.studid', $eachtesapp->studid)
                    ->where('college_studsched.deleted', 0)
                    ->where('college_classsched.deleted', 0)
                    ->where('college_classsched.syID', $syid)
                    ->where('college_classsched.semesterID', $semid)
                    ->first()->totalunits;
                }else{
                    $eachtesapp->totalunits = $eachtesapp->units;
                }
            }
        }
        
        if($request->get('action') == 'generate')
        {
            return view('finance.reports.applications.tes.filtertable')
                ->with('applications', $tesapplication);
        }else{
            $applications = collect($tesapplication)->where('appstatus','1')->values();

            $sydesc = DB::table('sy')
                ->where('id', $syid)
                ->first()->sydesc;

            $semester = DB::table('semester')
                ->where('id', $semid)
                ->first()->semester;

            $levelname = DB::table('gradelevel')
                ->where('id', $levelid)
                ->first()->levelname;

                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path().'/public/excelformats/dcc/dcc_tesapplication.xlsx');
                $sheet = $spreadsheet->getActiveSheet();
                // return $applications;
                $startcellno = 7;
                $sequenceno  = 1;
                if(count($applications)>0)
                {
                    foreach($applications as $application)
                    {
                        // if($application->fathername == null)
                        // {
                        //     $application->fatherlastname = '';
                        //     $application->fatherfirstname = '';
                        // }else{
                        //     $fathernamearray = explode(',',$applications->fathername);
    
                        //     $application->fatherlastname  = $fathernamearray[0];
                        //     try{
                        //         $application->fatherfirstname = $fathernamearray[1];
                        //     }catch(\Exception $e)
                        //     {
                        //         $application->fatherfirstname = "";
                        //     }
                        // }
                        $application->fatherlastname = $application->flastname;
                        $application->fatherfirstname = $application->ffirstname;
                        $application->fathermiddlename = $application->fmiddlename;
                        $application->motherlastname = $application->mmlastname;
                        $application->motherfirstname = $application->mmfirstname;
                        $application->mothermiddlename = $application->mmmiddlename;


                        $sheet->setCellValue('B3', $sydesc);

                        $sheet->setCellValue('A'.$startcellno, $sequenceno);
                        $sheet->setCellValue('B'.$startcellno, $application->lrn);
                        $sheet->setCellValue('C'.$startcellno, $application->sid);
                        $sheet->setCellValue('D'.$startcellno, $application->lastname);
                        $sheet->setCellValue('E'.$startcellno, $application->firstname);
                        $sheet->setCellValue('F'.$startcellno, $application->suffix);
                        $sheet->setCellValue('G'.$startcellno, $application->middlename);
                        $sheet->setCellValue('H'.$startcellno, $application->gender);
                        $sheet->setCellValue('I'.$startcellno, date('d/m/Y',strtotime($application->dob)));

                        $sheet->setCellValue('J'.$startcellno, $application->coursecode);
                        $sheet->setCellValue('K'.$startcellno, $application->levelname);
                        $sheet->setCellValue('L'.$startcellno, $application->fatherlastname);
                        $sheet->setCellValue('M'.$startcellno, $application->fatherfirstname);

                        $sheet->setCellValue('O'.$startcellno, $application->motherlastname);
                        try{
                            $sheet->setCellValue('P'.$startcellno, $application->motherfirstname);
                        }catch(\Exception $e)
                        {

                        }
                        try{
                            $sheet->setCellValue('Q'.$startcellno, $application->mothermiddlename);
                        }catch(\Exception $e)
                        {
                            
                        }

                        $sheet->setCellValue('R'.$startcellno, $application->dswdhno);
                        $sheet->setCellValue('S'.$startcellno, $application->hpcincome);

                        $sheet->setCellValue('T'.$startcellno, $application->street.', '.$application->barangay);
                        $sheet->setCellValue('U'.$startcellno, $application->city);
                        $sheet->setCellValue('V'.$startcellno, $application->province);
                        $sheet->setCellValue('V'.$startcellno, $application->zipcode);

                        try{
                            $sheet->setCellValue('X'.$startcellno, number_format($application->balance,2));
                        }catch(\Exception $error)
                        {

                        }
                        $sheet->setCellValue('Y'.$startcellno, $application->disability);

                        $sheet->setCellValue('Z'.$startcellno, $application->contactno);
                        $sheet->setCellValue('AA'.$startcellno, $application->emailaddress);

                        $startcellno+=1;
                        $sequenceno+=1;
                    }
                }
            
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="TES Application Form.xlsx"');
                $writer->save("php://output");
        }
    }
    public function tesupdatestatus(Request $request)
    {
        // return $request->all();
        try{
            DB::table('studapplication_tes')
                ->where('id', $request->get('tesid'))
                ->update([
                    'billedamount'             => $request->get('billedamount'),
                    'stipend'             => $request->get('stipend'),
                    'disabilityamount'             => $request->get('disabilityamount'),
                    'units'             => $request->get('units'),
                    'disapprovalreason' => $request->get('tesreason'),
                    'appstatus'         => $request->get('appstatus'),
                    'appstatusby'       => $request->get('appstatusby'),
                    'appstatusdatetime' => date('Y-m-d H:i:s')
                ]);
            return 1;
        }catch(\Exception $error)
        {
            return 0;
        }
    }
    public function apptesindex(Request $request)
    {
        $info = DB::table('studapplication_tes')
            ->where('studid', DB::table('studinfo')->where('userid', auth()->user()->id)->first()->id)
            ->where('syid', DB::table('sy')->where('isactive','1')->first()->id)
            ->where('semid', DB::table('semester')->where('isactive','1')->first()->id)
            ->orderByDesc('id')
            ->first();
            
        $infostat = 0;
        $recentstatus = 0;
        if($info)
        {
            if($info->appstatus == 2)
            {
                $recentstatus = 2;

            }elseif($info->appstatus == 1){
                $infostat = 1;
                $recentstatus = 1;
            }elseif($info->appstatus == 0){
                $infostat = 1;
                $recentstatus = 0;
            }
        }
        if($infostat == 0)
        {
            
            if(DB::table('studinfo')->where('userid', auth()->user()->id)->first()->courseid == null || DB::table('studinfo')->where('userid', auth()->user()->id)->first()->courseid == 0)
            {
                $coursename = DB::table('college_courses')->where('id', DB::table('studinfo')->where('userid', auth()->user()->id)->first()->courseid)->first()->courseDesc;
            }else{
                $coursename = null;
            }
            
            $info = (object)array(
                'id'                => 0,
                'levelid'           => DB::table('studinfo')->where('userid', auth()->user()->id)->first()->levelid,
                'levelname'         => DB::table('gradelevel')->where('id', DB::table('studinfo')->where('userid', auth()->user()->id)->first()->levelid)->first()->levelname,
                'courseid'          => DB::table('studinfo')->where('userid', auth()->user()->id)->first()->courseid,
                'coursename'        => $coursename,
                'dswdhno'           => null,
                'hpcincome'         => null,
                'flastname'        => null,
                'ffirstname'       => null,
                'fmiddlename'      => null,
                'mmlastname'        => null,
                'mmfirstname'       => null,
                'mmmiddlename'      => null,
                'guardianaddress'   => null,
                'gcontactno'        => DB::table('studinfo')->where('userid', auth()->user()->id)->first()->gcontactno,
                'guardianname'      => DB::table('studinfo')->where('userid', auth()->user()->id)->first()->guardianname,
                'street'            => DB::table('studinfo')->where('userid', auth()->user()->id)->first()->street,
                'barangay'          => DB::table('studinfo')->where('userid', auth()->user()->id)->first()->barangay,
                'city'              => DB::table('studinfo')->where('userid', auth()->user()->id)->first()->city,
                'province'          => DB::table('studinfo')->where('userid', auth()->user()->id)->first()->province,
                'zipcode'           => null,
                'contactno'         => DB::table('studinfo')->where('userid', auth()->user()->id)->first()->contactno,
                'emailaddress'      => null,
                'disability'        => null,
                'numofsiblings'     => 0,
                'submitted'         => null
            );
        }else{
            $info->guardianname = DB::table('studinfo')->where('userid', auth()->user()->id)->first()->guardianname;
            $info->gcontactno = DB::table('studinfo')->where('userid', auth()->user()->id)->first()->gcontactno;
        }

        $applications = DB::table('studapplication_tes')
            ->select('studapplication_tes.*','studinfo.guardianname','studinfo.gcontactno')
            ->join('studinfo','studapplication_tes.studid','=','studinfo.id')
            ->where('studid', DB::table('studinfo')->where('userid', auth()->user()->id)->first()->id)
            ->where('studapplication_tes.deleted','0')
            ->orderByDesc('studapplication_tes.createddatetime')
            ->get();
        // return collect($info);
        return view('studentPortal.pages.application.tes.index')
            ->with('recentstatus', $recentstatus)
            ->with('info', $info)
            ->with('applications', $applications);
    }
    public function apptesupdate(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $infoid         = $request->get('infoid');
        $dswdhn         = $request->get('dswdhn');
        $income         = $request->get('household-income');
        $flastname     = $request->get('flastname');
        $ffirstname    = $request->get('ffirstname');
        $fmiddlename   = $request->get('fmiddlename');
        $mmlastname     = $request->get('mmlastname');
        $mmfirstname    = $request->get('mmfirstname');
        $mmmiddlename   = $request->get('mmmiddlename');
        $guardianname     = $request->get('guardianname');
        $guardianaddress    = $request->get('guardianaddress');
        $gcontactno   = $request->get('gcontactno');
        $street         = $request->get('street');
        $barangay       = $request->get('barangay');
        $city           = $request->get('city');
        $province       = $request->get('province');
        $zipcode        = $request->get('zipcode');
        $contactno      = $request->get('contactno');
        $emailaddress   = $request->get('emailaddress');
        $disability     = $request->get('disability');
        $numofsiblings  = $request->get('numofsiblings');
        if($infoid == 0)
        {
            DB::table('studapplication_tes')
                ->insert([
                    'studid'            => DB::table('studinfo')->where('userid', auth()->user()->id)->first()->id,
                    'syid'              => DB::table('sy')->where('isactive','1')->first()->id,
                    'semid'             => DB::table('semester')->where('isactive','1')->first()->id,
                    'levelid'           => DB::table('studinfo')->where('userid', auth()->user()->id)->first()->levelid,
                    // 'levelname'        => ,
                    'courseid'          => DB::table('studinfo')->where('userid', auth()->user()->id)->first()->courseid,
                    // 'coursename'        => ,
                    'dswdhno'          => $dswdhn,
                    'hpcincome'         => $income,
                    'flastname'        => $flastname,
                    'ffirstname'       => $ffirstname,
                    'fmiddlename'      => $fmiddlename,
                    'mmlastname'        => $mmlastname,
                    'mmfirstname'       => $mmfirstname,
                    'mmmiddlename'      => $mmmiddlename,
                    'guardianaddress'       => $guardianaddress,
                    'street'            => $street,
                    'barangay'          => $barangay,
                    'city'              => $city,
                    'province'          => $province,
                    'zipcode'           => $zipcode,
                    'contactno'         => $contactno,
                    'emailaddress'      => $emailaddress,
                    'disability'        => $disability,
                    'numofsiblings'     => $numofsiblings,
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s'),
                ]);
            DB::table('studinfo')
                ->where('deleted','0')
                ->where('userid',auth()->user()->id)
                ->update([
                    'guardianname'        => $guardianname,
                    'gcontactno'       => $gcontactno
                ]);

        }else{
            DB::table('studapplication_tes')
                ->where('id', $infoid)
                ->update([
                    'dswdhno'          => $dswdhn,
                    'hpcincome'         => $income,
                    'flastname'        => $flastname,
                    'ffirstname'       => $ffirstname,
                    'fmiddlename'      => $fmiddlename,
                    'mmlastname'        => $mmlastname,
                    'mmfirstname'       => $mmfirstname,
                    'mmmiddlename'      => $mmmiddlename,
                    'guardianaddress'       => $guardianaddress,
                    'street'            => $street,
                    'barangay'          => $barangay,
                    'city'              => $city,
                    'province'          => $province,
                    'zipcode'           => $zipcode,
                    'contactno'         => $contactno,
                    'emailaddress'      => $emailaddress,
                    'disability'        => $disability,
                    'numofsiblings'     => $numofsiblings,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s'),
                ]);
                DB::table('studinfo')
                    ->where('deleted','0')
                    ->where('userid',auth()->user()->id)
                    ->update([
                        'guardianname'        => $guardianname,
                        'gcontactno'       => $gcontactno
                    ]);
        }
        return back();
    }
    public function apptessubmit(Request $request)
    {
        if($request->ajax())
        {
            try{
                DB::table('studapplication_tes')
                    ->where('id', $request->get('id'))
                    ->update([
                        'submitted'         => 1,
                        'submitteddatetime' => date('Y-m-d H:i:s')
                    ]);
    
                return 1;
            }catch(\Exception $error)
            {
    
                return $error;
            }
        }
    }
    public function apptesexport(Request $request)
    {
        $tesinfo = DB::table('studapplication_tes')
            ->select('studapplication_tes.*','gradelevel.levelname','college_courses.courseDesc as coursename')
            ->where('studapplication_tes.id', $request->get('tesid'))
            ->join('gradelevel','studapplication_tes.levelid','=','gradelevel.id')
            ->join('college_courses','studapplication_tes.courseid','college_courses.id')
            ->first();

        $studinfo = DB::table('studinfo')
            ->where('userid', auth()->user()->id)
            ->where('deleted','0')
            ->first();

        if($studinfo->fathername == null)
        {
            $studinfo->fatherlastname = '';
            $studinfo->fatherfirstname = '';
        }else{
            $fathernamearray = explode(',',$studinfo->fathername);

            $studinfo->fatherlastname  = $fathernamearray[0];
            try{
                $studinfo->fatherfirstname = $fathernamearray[1];
            }catch(\Exception $e)
            {
                $studinfo->fatherfirstname = "";
            }
        }
        $pdf = PDF::loadview('studentPortal/pages/application/tes/pdf_tesapplication',compact('tesinfo','studinfo'))->setPaper('8.5x13');

        return $pdf->stream('Tes Application Form.pdf');

    }
}
