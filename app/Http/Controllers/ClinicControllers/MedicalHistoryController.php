<?php

namespace App\Http\Controllers\ClinicControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\SchoolClinic\SchoolClinic;
class MedicalHistoryController extends Controller
{
    public function index(Request $request)
    {
        $extends = '';
        $refid = DB::table('usertype')
            ->where('id', Session::get('currentPortal'))
            ->first();
            
        if($refid->refid == '23')
        {
            $extends = 'clinic';
        }elseif($refid->refid == '24'){
            $extends = 'clinic_nurse';
        }elseif($refid->refid == '25'){
            $extends = 'clinic_doctor';
        }
        $users  = SchoolClinic::users();
        
        return view('clinic.medicalhistory.index')
            ->with('extends', $extends)
            ->with('users', $users);
    }
    public function gethistory(Request $request)
    {
        // return $request->all();

        $medical = DB::table('clinic_medicalhistory')
						->where('userid', $request->get('id'))
						->where('deleted','0')
						->get();

        return $medical;

        
    }

    public function gethistory2(Request $request)
    {
        // return $request->all();

        $appointments = DB::table('clinic_appointments')
            ->leftjoin('teacher', 'teacher.id', '=', 'clinic_appointments.docid')
            ->where('clinic_appointments.userid',$request->get('id'))
            ->where('clinic_appointments.deleted','0')
            //->where('label','1')
            ->where('clinic_appointments.admitted','1')
            ->select('clinic_appointments.*', 'teacher.lastname', 'teacher.firstname')
            ->get();

        return $appointments;

        
    }

    public function getuserComplaints(Request $request)
    {
    
        

        $complaints = DB::table('clinic_complaints')
            ->select('clinic_complaints.cdate','clinic_complaints.id','clinic_complaints.userid','clinic_complaints.description','clinic_complaints.actiontaken','clinic_complaints.actiontaken' ,'users.type')
            ->leftJoin('users','clinic_complaints.userid','=','users.id')
            ->where('clinic_complaints.deleted','0')
            ->where('clinic_complaints.userid',$request->get('id'))
            ->get();

        
            
        if(count($complaints)>0)
        {
        
            foreach($complaints as $complaint)
            {
                $medicineholder ="";
                $complaintmeds = DB::table('clinic_complaintmed')
                            ->select('clinic_medicines.genericname','clinic_complaintmed.headerid','clinic_complaintmed.quantity', 'clinic_complaintmed.createddatetime')
                            ->join('clinic_medicines','clinic_complaintmed.drugid','=','clinic_medicines.id')
                            ->where('clinic_complaintmed.headerid', $complaint->id)
                            ->where('clinic_complaintmed.deleted','0')
                            ->where('clinic_complaintmed.quantity','!=',0)
                            ->get();


                foreach($complaintmeds as $complaintmed){
                    $medicineholder.= $complaintmed->genericname;
                    $medicineholder.= '(';
                    $medicineholder.= $complaintmed->quantity;
                    $medicineholder.= '),';
                }

                $complaint->med = $medicineholder;
                if($complaint->type == 7)
                {
                    $info = Db::table('studinfo')
                        ->where('userid', $complaint->userid)
                        ->where('deleted','0')
                        ->first();

                    $info->title = null;
                    $info->utype = 'STUDENT';
                }else{
                    $info = Db::table('teacher')
                        ->select('teacher.*','usertype.utype','employee_personalinfo.gender')
                        ->join('usertype','teacher.usertypeid','=','usertype.id')
                        ->leftJoin('employee_personalinfo','teacher.usertypeid','=','usertype.id')
                        ->where('userid', $complaint->userid)
                        ->where('teacher.deleted','0')
                        ->first();
                }

                if(isset($info)){
                    
                    $complaint->picurl = $info->picurl;
                    $complaint->gender = $info->gender;
                    $complaint->utype = $info->utype;
                
                    $name_showfirst = "";
                    $name_showlast = "";
                
                    if($info->title != null)
                    {
                        $name_showfirst.=$info->title.' ';
                    }
                    $name_showfirst.=$info->firstname.' ';

                    if($info->middlename != null)
                    {
                        $name_showfirst.=$info->middlename[0].'. ';
                    }
                    $name_showfirst.=$info->lastname.' ';
                    $name_showfirst.=$info->suffix.' ';

                    $complaint->name_showfirst = $name_showfirst;

                    $name_showlast = "";

                    if($info->title != null)
                    {
                        $name_showlast.=$info->title.' ';
                    }
                    $name_showlast.=$info->lastname.', ';
                    $name_showlast.=$info->firstname.' ';

                    if($info->middlename != null)
                    {
                        $name_showlast.=$info->middlename[0].'. ';
                    }
                    $name_showlast.=$info->suffix.' ';

                    $complaint->name_showlast = $name_showlast;
                }
            }
        }
        // return($complaints);
        return $complaints;
    }

    public function get(Request $request)
    {
        // return $request->all();

        $medical = DB::table('clinic_medicalhistory')
            ->where('userid',$request->get('id'))
            ->where('deleted','0')
            ->get();

        return $medical;

        
    }

    public function update(Request $request)
    {
        // return $request->all();

        $checkifexists = DB::table('clinic_medicalhistory')
            ->where('userid', $request->get('id'))
            ->where('deleted','0')
            ->count();

        if($checkifexists>0){
            
        DB::table('clinic_medicalhistory')
            ->where('userid', $request->get('id'))
            ->update([
                'hospitalization'           => $request->get('hospitalization'),
                'familyhistory'         => $request->get('history'),
                'smoke'   => $request->get('smokedstatus'),
                'packs'  => $request->get('pack'),
                'agestarted'  => $request->get('age'),
                'agequit'  => $request->get('age1'),
                'alcohol'  => $request->get('drinkstatus'),
                'averagedrink'  => $request->get('average'),
                'currentMedications'  => $request->get('medication'),
                'allergies'  => $request->get('allergies')
            ]);
            return 0;
        }else{
            DB::table('clinic_medicalhistory')
                ->insert([
                    'userid' => $request->get('id'),
                    'hospitalization'           => $request->get('hospitalization'),
                    'familyhistory'         => $request->get('history'),
                    'smoke'   => $request->get('smokedstatus'),
                    'packs'  => $request->get('pack'),
                    'agestarted'  => $request->get('age'),
                    'agequit'  => $request->get('age1'),
                    'alcohol'  => $request->get('alcohol'),
                    'averagedrink'  => $request->get('average'),
                    'currentMedications'  => $request->get('medication'),
                    'allergies'  => $request->get('allergies')
                ]);

            return 1 ;
        }

        
    }

    public function get_experiences()
    {

        $experiences = DB::table('clinic_experiences')
        ->where('deleted', '0')
        ->select(
            'id',
            'description',
        )
        ->get();
        return $experiences;
    }

    public function update_experiences(Request $request){
        $option = $request->get('option');
        $id = $request->get('id');
        DB::table('clinic_experiences')
        ->where('id', $id)
        ->update([
            'description' => $option,
            'updatedby' => auth()->user()->id,
            'updateddatetime'=> \Carbon\Carbon::now('Asia/Manila')
        ]);
    }

    public function delete_experiences(Request $request)
    {

        DB::table('clinic_experiences')
        ->where('id', $request->get('id'))
        ->update([
            'deleted'    =>     1,
            'deletedby'      => auth()->user()->id,
            'deleteddatetime'=> \Carbon\Carbon::now('Asia/Manila')
        ]);
    }
}
