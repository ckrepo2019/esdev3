<?php

namespace App\Http\Controllers\FinanceControllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\FinanceModel;
use App\DisplayModel;
use App\Models\Finance\FinanceUtilityModel;
use DB;
use NumConvert;
use PDF;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
use Image;
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class FinanceScholarshipController extends Controller
{
    public function __construct()
    {
          // $this->middleware('auth');
    }

    public function index()
    {
        // $applicants = db::table('scholarship_applicants')
        //     ->select(db::raw('scholarship_applicants.id as applicantid, lastname, firstname, middlename, suffix, scholarship_setup.description, scholarship_applicants.syid,
        //         scholarship_applicants.semid,scholarship_applicants.studid, sydesc, semester, scholarship_applicants.scholar_status'))
        //     ->join('studinfo', 'scholarship_applicants.studid', '=', 'studinfo.id')
        //     ->join('scholarship_setup', 'scholarship_applicants.scholarship_setup_id', '=', 'scholarship_setup.id')
        //     ->join('sy', 'scholarship_applicants.syid', '=', 'sy.id')
        //     ->join('semester', 'scholarship_applicants.semid', '=', 'semester.id')
        //     ->where('scholarship_applicants.deleted', 0)
        //     ->get();

        // $studarray = array();

        // $gradelevel = db::table('gradelevel')
        //     ->where('deleted', 0)
        //     ->get();

        // $gradelevel = collect($gradelevel);

        // foreach($applicants as $data)
        // {
        //     $levelid = 0;

        //     $einfo = db::table('enrolledstud')
        //         ->where('studid', $data->studid)
        //         ->where('syid', $data->syid)
        //         ->where('deleted', 0)
        //         ->where('studstatus', '>', 0)
        //         ->first();

        //     if($einfo)
        //     {
        //         $levelid = $einfo->levelid;
        //     }
        //     else{
        //         $einfo = db::table('sh_enrolledstud')
        //             ->where('studid', $data->studid)
        //             ->where('syid', $data->syid)
        //             ->where('deleted', 0)
        //             ->where('studstatus', '>', 0)
        //             ->first();
                
        //         if($einfo)
        //         {
        //             $levelid = $einfo->levelid;
        //         }
        //         else{
        //             $einfo = db::table('college_enrolledstud')
        //                 ->where('studid', $data->studid)
        //                 ->where('syid', $data->syid)
        //                 ->where('semid', $data->semid)
        //                 ->where('deleted', 0)
        //                 ->where('studstatus', '>', 0)
        //                 ->first();

        //             if($einfo)
        //             {
        //                 $levleid = $einfo->yearlevel;
        //             }
        //             else{
        //                 $levelid = db::table('studinfo')->where('id', $data->studid)->first()->levelid;
        //             }
        //         }
        //     }

        //     array_push($studarray, (object)[
        //         'id' => $data->applicantid,
        //         'studid' => $data->studid,
        //         'levelid' => $levelid,
        //         'description' => $data->description,
        //         'syid' => $data->syid,
        //         'semid' => $data->semid,
        //         'scholar_status' => $data->scholar_status,
        //         'lastname' => $data->lastname,
        //         'firstname' => $data->firstname,
        //         'middlename' => $data->middlename,
        //         'suffix' => $data->suffix,
        //         'sydesc' => $data->sydesc,
        //         'semester' => $data->semester

        //     ]);

        //     $info = array(
        //         'gradelevel' => $gradelevel,
        //         'applicants' => $studarray
        //     );
        // }

        return view('finance/scholarship');
    }

    public function scholarship_app_load(Request $request)
    {
        $filter = $request->get('filter');
        $status = $request->get('status');

        $applicants = db::table('scholarship_applicants')
            ->select(db::raw('scholarship_applicants.id as applicantid, lastname, firstname, middlename, suffix, scholarship_setup.description, scholarship_applicants.syid,
                scholarship_applicants.semid,scholarship_applicants.studid, sydesc, semester, scholarship_applicants.scholar_status'))
            ->join('studinfo', 'scholarship_applicants.studid', '=', 'studinfo.id')
            ->join('scholarship_setup', 'scholarship_applicants.scholarship_setup_id', '=', 'scholarship_setup.id')
            ->join('sy', 'scholarship_applicants.syid', '=', 'sy.id')
            ->join('semester', 'scholarship_applicants.semid', '=', 'semester.id')
            ->where('scholarship_applicants.deleted', 0)
            ->where(function($q) use($filter, $status){
                if($filter != '')
                {
                    $q->where('lastname', 'like', '%' . $filter . '%')
                        ->orWhere('firstname', 'like', '%' . $filter . '%');
                }
                
                if($status != '')
                {
                    $q->where('scholar_status', $status);
                }
            })
            ->get();

        $applist = '';

        $gradelevel = db::table('gradelevel')
            ->where('deleted', 0)
            ->get();

        $gradelevel = collect($gradelevel);

        foreach($applicants as $data)
        {
            $levelid = 0;
            $studarray = array();

            $einfo = db::table('enrolledstud')
                ->where('studid', $data->studid)
                ->where('syid', $data->syid)
                ->where('deleted', 0)
                ->where('studstatus', '>', 0)
                ->first();

            if($einfo)
            {
                $levelid = $einfo->levelid;
            }
            else{
                $einfo = db::table('sh_enrolledstud')
                    ->where('studid', $data->studid)
                    ->where('syid', $data->syid)
                    ->where('deleted', 0)
                    ->where('studstatus', '>', 0)
                    ->first();
                
                if($einfo)
                {
                    $levelid = $einfo->levelid;
                }
                else{
                    $einfo = db::table('college_enrolledstud')
                        ->where('studid', $data->studid)
                        ->where('syid', $data->syid)
                        ->where('semid', $data->semid)
                        ->where('deleted', 0)
                        ->where('studstatus', '>', 0)
                        ->first();

                    if($einfo)
                    {
                        $levelid = $einfo->yearLevel;
                    }
                    else{
                        $levelid = db::table('studinfo')->where('id', $data->studid)->first()->levelid;
                    }
                }
            }

            $sem = '';

            $levelname = $gradelevel->where('id', $levelid)->first()->levelname;

            if($levelid >= 17 && $levelid <= 21)
            {
                $sem = $data->semester;
            }

            if($data->scholar_status == 'APPROVED')
            {
                $bg = 'text-success';
            }
            elseif($data->scholar_status == 'DISAPPROVED')
            {
                $bg = 'text-danger';
            }
            else{
                $bg = 'text-default';
            }

            $applist .='
                <tr data-id="'.$data->applicantid.'">
                    <td class="'.$bg.'">'.$data->lastname.', '.$data->firstname.' ' . $data->middlename . ' ' . $data->suffix . '</td>
                    <td class="'.$bg.'">'.$levelname.'</td>
                    <td class="'.$bg.'">'.$data->description.'</td>
                    <td class="'.$bg.'">'.$data->sydesc.'</td>
                    <td class="'.$bg.'">'.$sem.'</td>
                    <td class="'.$bg.'">'.$data->scholar_status.'</td>
                </tr>
            ';

            

        }
        
        return $applist;

    }

    public function scholarship_app_read(Request $request)
    {
        $id = $request->get('id');
        $sectionid = 0;
        $courseid = 0;

        $applicant = db::table('scholarship_applicants')
            ->select(db::raw('scholarship_applicants.id as applicantid, lastname, firstname, middlename, suffix, scholarship_setup.description, scholarship_applicants.syid,
                scholarship_applicants.semid,scholarship_applicants.studid, sydesc, semester, scholarship_applicants.scholar_status'))
            ->join('studinfo', 'scholarship_applicants.studid', '=', 'studinfo.id')
            ->join('scholarship_setup', 'scholarship_applicants.scholarship_setup_id', '=', 'scholarship_setup.id')
            ->join('sy', 'scholarship_applicants.syid', '=', 'sy.id')
            ->join('semester', 'scholarship_applicants.semid', '=', 'semester.id')
            ->where('scholarship_applicants.deleted', 0)
            ->where('scholarship_applicants.id', $id)
            ->first();

        $einfo = db::table('enrolledstud')
            ->where('studid', $applicant->studid)
            ->where('syid', $applicant->syid)
            ->where('deleted', 0)
            ->where('studstatus', '>', 0)
            ->first();

        if($einfo)
        {
            $levelid = $einfo->levelid;
            $sectionid = $einfo->sectionid;
        }
        else{
            $einfo = db::table('sh_enrolledstud')
                ->where('studid', $applicant->studid)
                ->where('syid', $applicant->syid)
                ->where('deleted', 0)
                ->where('studstatus', '>', 0)
                ->first();
            
            if($einfo)
            {
                $levelid = $einfo->levelid;
                $sectionid = $einfo->sectionid;
            }
            else{
                $einfo = db::table('college_enrolledstud')
                    ->where('studid', $applicant->studid)
                    ->where('syid', $applicant->syid)
                    ->where('semid', $applicant->semid)
                    ->where('deleted', 0)
                    ->where('studstatus', '>', 0)
                    ->first();

                if($einfo)
                {
                    $levelid = $einfo->yearLevel;
                    $courseid = $einfo->courseid;
                }
                else{
                    $levelid = db::table('studinfo')->where('id', $data->studid)->first()->levelid;
                    $sectionid = '';
                }
            }
        }

        $sem = '';

        $levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname;
        $section = '';
        
        if($levelid >= 17 && $levelid <= 21)
        {
            $section = db::table('college_courses')->where('id', $courseid)->first()->courseabrv;
            $sem = $applicant->semester;
        }
        else{
            $section = db::table('sections')->where('id', $sectionid)->first()->sectionname;
            $sem = '';
        }

        $requirements = db::table('scholarship_applicant_details')
            ->select(db::raw('scholarship_applicant_details.id, scholarship_applicant_details.fileurl, description'))
            ->join('scholarship_setup_details', 'scholarship_applicant_details.requirement_id', '=', 'scholarship_setup_details.id')
            ->where('scholarship_applicant_id', $id)
            ->where('scholarship_applicant_details.deleted', 0)
            ->get();

        return array(
            'applicant' => $applicant,
            'levelname' => $levelname,
            'sem' => $sem,
            'section' => $section,
            'requirements' => $requirements
        );
            
    }

    public function scholarship_app_approve(Request $request)
    {
        $id = $request->get('id');

        db::table('scholarship_applicants')
            ->where('id', $id)
            ->update([
                'scholar_status' => 'APPROVED',
                'updatedby' => auth()->user()->id,
                'updateddatetime' => FinanceModel::getServerDateTime(),
                'approvedby' => auth()->user()->id,
                'approveddatetime' => FinanceModel::getServerDateTime()
            ]);
    }

    public function scholarship_app_disapprove(Request $request)
    {
        $id = $request->get('id');

        db::table('scholarship_applicants')
            ->where('id', $id)
            ->update([
                'scholar_status' => 'DISAPPROVED',
                'updatedby' => auth()->user()->id,
                'updateddatetime' => FinanceModel::getServerDateTime(),
                'disapprovedby' => auth()->user()->id,
                'disapproveddatetime' => FinanceModel::getServerDateTime()
            ]);
    }

    public function scholarship_setup_load(Request $request)
    {
        $filter = $request->get('filter');
        
        $setup = DB::table('scholarship_setup')
            ->where('description', 'like', '%' . $filter . '%')
            ->where('deleted', 0)
            ->get();

        $setuparray = array();

        foreach($setup as $data)
        {
            array_push($setuparray, $data->id);
        }

        $setupdetails = collect(db::table('scholarship_setup_details')
            ->where('deleted', 0)
            ->whereIn('scholarship_setup_id', $setuparray)
            ->get());

        
        
        $list = '';

        foreach($setup as $data)
        {
            $isactive_header = '';
            if($data->isactive == 1)
            {
                $isactive_header = '<i class="fas fa-check"></i>';
            }

            $details = $setupdetails
                ->where('scholarship_setup_id', $data->id)
                ->where('deleted', 0);

            $details->all();

            // dd($details);

            $list .= '
                <tr data-id="'.$data->id.'" style="font-weight: bold">
                    <td style="vertical-align: middle; background-color: #f5faff;">'.$data->description.'</td>
                    <td style="vertical-align: middle; background-color: #f5faff;">'.date_format(date_create($data->endofsubmission), 'm/d/Y').'</td>
                    <td style="vertical-align: middle; background-color: #f5faff;" class="text-center">'.$isactive_header.'</td>
                    <td style="vertical-align: middle; background-color: #f5faff;">
                        <button class="btn btn-sm btn-outline-primary btnedit" data-id="'.$data->id.'" data-toggle="tooltip" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btndelete" data-id="'.$data->id.'" data-toggle="tooltip" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary btnupload" data-id="'.$data->id.'" data-toggle="tooltip" title="Upload">
                            <i class="fas fa-file-upload"></i>
                        </button>
                    </td>
                </tr>
            ';
            

            foreach($details as $detail)
            {
                $isactive_detail = '';

                if($detail->isactive == 1)
                {
                    $isactive_detail = '<i class="fas fa-check"></i>';
                }

                $list.='
                    <tr detail-id="'.$detail->id.'">
                        <td class="pl-3" colspan="4>
                            <span class="text-bold">DESCRIPTION: </span> '.$detail->description.' | <span class="text-bold">ACTIVE</span> '.$isactive_detail.' |
                            <span class="detailedit text-primary" style="cursor:pointer" detail-id="'.$detail->id.'" data-toggle="tooltip" title="Edit">
                                <i class="fas fa-edit"></i>
                            </span>
                            <span class="detaildelete text-danger" style="cursor:pointer" detail-id="'.$detail->id.'" data-toggle="tooltip" title="Delete">
                                <i class="fas fa-trash"></i>
                            </span>
                        </td>
                    </tr>
                ';
            }
        }

        return $list;
    }

    public function scholarship_setup_create(Request $request)
    {
        $setup_description = $request->get('setup_description');
        $setup_endofsubmission = $request->get('setup_endofsubmission');
        $setup_active = $request->get('setup_active');
        $id = $request->get('dataid');

        if($setup_active == true)
        {
            $setup_active = 1;
        }
        else{
            $setup_active = 0;
        }
        
        if($id == 0)
        {
            $check = db::table('scholarship_setup')
                ->where('description', $setup_description)
                ->where('deleted', 0)
                ->count();

            if($check > 0)
            {
                return 'exists';
            }
            else{
                db::table('scholarship_setup')
                    ->insertGetId([
                        'description' => $setup_description,
                        'endofsubmission' => $setup_endofsubmission,
                        'isactive' => $setup_active
                    ]);
            }
        }
        else{
            $check = db::table('scholarship_setup')
                ->where('description', $setup_description)
                ->where('deleted', 0)
                ->where('id', '!=', $id)
                ->count();

            if($check > 0)
            {
                return 'exists';
            }
            else{
                db::table('scholarship_setup')
                    ->where('id', $id)
                    ->update([
                        'description' => $setup_description,
                        'endofsubmission' => $setup_endofsubmission,
                        'isactive' => $setup_active
                    ]);
            }
                
        }
    }

    public function scholarship_setup_read(Request $request)
    {
        $id = $request->get('dataid');

        return collect(db::table('scholarship_setup')
            ->where('id', $id)
            ->first());
    }

    public function scholarship_setup_upload(Request $request)
    {
        $urlFolder = str_replace('http://','',$request->root());
        $headerid = $request->get('headerid');
                  // $urlFolder = str_replace('http://','',$urlFolder);
        // return $urlFolder;
        // return $request->file('req_file');
        // return $urlFolder;

        $fileurl = '';

        if($request->file('req_file'))
        {
            if (! File::exists(public_path().'Scholarships/Template')) {
                $path = public_path('Scholarships/Template');
                if(!File::isDirectory($path)){
                    File::makeDirectory($path, 0777, true, true);
                }
            }
            if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/Scholarships/Template')) {
                $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/Scholarships/Template';
                if(!File::isDirectory($cloudpath)){
                    File::makeDirectory($cloudpath, 0777, true, true);
                }
            }
                    // return $sf9templateID;
            $file = $request->file('req_file');
        
            $extension = $file->getClientOriginalExtension();
            // $name = $file->getClientOriginalName();
            $name = date_format(date_create(FinanceModel::getServerDateTime()), 'mdYhis');
        
            $destinationPath = public_path('Scholarships/Template');
            $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/Scholarships/Template';
            

            $file->move($destinationPath, $name.'.'.$extension);

            $fileurl = 'Scholarships/Template/' . $name . '.' . $extension;
        }

        $isactive = 0;

        if($request->req_active == true)
        {
            $isactive = 1;
        }

        if($request->detailid == 0)
        {
            db::table('scholarship_setup_details')
                ->insert([
                    'scholarship_setup_id' => $headerid,
                    'description' => $request->description,
                    'fileurl' => $fileurl,
                    'isactive' => $isactive,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => FinanceModel::getServerDateTime()
                ]);

        }
        else{

            if($fileurl == '')
            {
                db::table('scholarship_setup_details')
                    ->where('id', $request->detailid)
                    ->update([
                        'description' => $request->description,
                        'isactive' => $isactive
                    ]);
            }
            else{
                db::table('scholarship_setup_details')
                    ->where('id', $request->detailid)
                    ->update([
                        'description' => $request->description,
                        'fileurl' => $fileurl,
                        'isactive' => $isactive
                    ]);
            }
            
        }
    }

    public function scholarship_setup_delete(Request $request)
    {
        $id = $request->get('dataid');

        $applicants = db::table('scholarship_applicants')
            ->where('deleted', 0)
            ->where('scholarship_setup_id', $id)
            ->count();
        
        if($applicants == 0)
        {
            db::table('scholarship_setup')
                ->where('id', $id)
                ->update([
                    'deleted' => 1,
                    'deletedby' => auth()->user()->id,
                    'deleteddatetime' => FinanceModel::getServerDateTime()
                ]);

            return 'done';
        }
        else{
            return 'hasApplicants';
        }

    }

    public function scholarship_setup_detail_read(Request $request)
    {
        $id = $request->get('id');

        $detail = db::table('scholarship_setup_details')
            ->where('id', $id)
            ->first();

        $description = $detail->description;
        $isactive = $detail->isactive;

        return array(
            'description' => $description,
            'isactive' => $isactive
        );
    }

    public function scholarship_setup_detail_delete(Request $request)
    {
        $id = $request->get('id');

        db::table('scholarship_setup_details')
            ->where('id', $id)
            ->update([
                'deleted' => 1,
                'deleteddatetime' => FinanceModel::getServerDateTime(),
                'deletedby' => auth()->user()->id
            ]);
    }

    public function scholarship_app_adj(Request $request)
    {
        $id = $request->get('id');
        
        $applicant = db::table('scholarship_applicants')
            ->where('id', $id)
            ->first();

        $studid = $applicant->studid;
        $syid = $applicant->syid;
        $semid = $applicant->semid;
        
        $payscheddetail = db::table('studpayscheddetail')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where('deleted', 0)
            ->get();

    }

    

}    

    

  
