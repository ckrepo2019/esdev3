<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;
class FinanceDashboardController extends Controller
{
    public function dashboard()
    {
        $gradeLevel = DB::table('gradelevel')
            ->select('gradelevel.id','gradelevel.levelname')
            // ->where('gradelevel.deleted','0')
            ->where('deleted', 0)
            ->orderBy('gradelevel.sortid','asc')
            ->get();
          return $gradeLevel;
        $setup = array();
        foreach($gradeLevel as $gradelevelid){
            $tuition = Db::table('tuitionheader')
                ->select('tuitionheader.description')
                ->join('sy','tuitionheader.syid','=','sy.id')
                ->where('tuitionheader.levelid',$gradelevelid->id)
                ->where('sy.isactive','1')
                ->where('tuitionheader.deleted','0')
                ->get();
            if(count($tuition)==0){
                array_push($setup, (object)array(
                    'levelname' => $gradelevelid->levelname,
                    'setup' => 0
                ));
            }
            else{
                array_push($setup, (object)array(
                    'levelname' => $gradelevelid->levelname,
                    'setup' => 1
                ));
            }
        }
        // -----------------------------------------------------------------------------------------
        $escstudents = DB::table('studinfo')
            ->select('studinfo.id')
            ->join('sectiondetail','studinfo.sectionid','=','sectiondetail.sectionid')
            ->join('sy','sectiondetail.syid','=','sy.id')
            ->where('sy.isactive','1')
            ->where('sectiondetail.deleted','0')
            ->where('studinfo.esc','1')
            ->get();
        // ----------------------------------------------------------------------------------------
        $current_schoolyear = Db::table('sy')
            ->where('sy.isactive','1')
            ->get();
        // ----------------------------------------------------------------------------------------
        $getTeachersLower = DB::table('enrolledstud')
            ->select('teacher.id','academicprogram.acadprogcode')
            ->join('sections','enrolledstud.sectionid','=','sections.id')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->join('teacher','sections.teacherid','=','teacher.id')
            ->where('enrolledstud.syid',$current_schoolyear[0]->id)
            ->where('enrolledstud.studstatus','!=',0)
            ->where('teacher.deleted','0')
            ->distinct()
            ->get();
        $preschoolTeachers = count($getTeachersLower->where('acadprogcode','PRE-SCHOOL'));
        $elemTeachers = count($getTeachersLower->where('acadprogcode','ELEM'));
        $juniorHighTeachers = count($getTeachersLower->where('acadprogcode','HS'));

        $getTeachersHigher = DB::table('sh_enrolledstud')
            ->select('teacher.id','academicprogram.acadprogcode')
            ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->join('teacher','sections.teacherid','=','teacher.id')
            ->where('sh_enrolledstud.syid',$current_schoolyear[0]->id)
            ->where('sh_enrolledstud.studstatus','!=',0)
            ->distinct()
            ->get();
        $seniorHighTeachers = count($getTeachersHigher->where('acadprogcode','SHS'));
        // ----------------------------------------------------------------------------------------
        $getStudentsLower = DB::table('enrolledstud')
            ->select('studinfo.id','academicprogram.acadprogcode')
            ->join('studinfo','enrolledstud.id','=','studinfo.id')
            ->join('sections','enrolledstud.sectionid','=','sections.id')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('enrolledstud.syid',$current_schoolyear[0]->id)
            ->where('enrolledstud.studstatus','!=',0)
            ->distinct()
            ->get();
        $preschoolStudents = count($getStudentsLower->where('acadprogcode','PRE-SCHOOL'));
        $elemStudents = count($getStudentsLower->where('acadprogcode','ELEM'));
        $juniorHighStudents = count($getStudentsLower->where('acadprogcode','HS'));

        $getStudentsHigher = DB::table('sh_enrolledstud')
            ->select('studinfo.id','academicprogram.acadprogcode')
            ->join('studinfo','sh_enrolledstud.id','=','studinfo.id')
            ->join('sections','sh_enrolledstud.sectionid','=','sections.id')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('sh_enrolledstud.syid',$current_schoolyear[0]->id)
            ->where('sh_enrolledstud.studstatus','!=',0)
            ->distinct()
            ->get();
        $seniorHighStudents = count($getStudentsHigher->where('acadprogcode','SHS'));
        // ----------------------------------------------------------------------------------------
        $monthsArray = array();
        $type = CAL_GREGORIAN;
        $months = 12;
        $currentYear = Carbon::now()->year;

        $workdays = array();
        for ($m = 1; $m <= $months; $m++) {
            $day_count = cal_days_in_month($type, $months, $currentYear); // Get the amount of days
            // return $day_count;
            for ($i = 1; $i <= $day_count; $i++) {
                $date = $currentYear.'/'.$m.'/'.$i; //format date
                $get_name = Carbon::create($date)->isoFormat('ddd'); //get week day
                $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars
        
                //if not a weekend add day to array
                // if($day_name != 'Sun' && $day_name != 'Sat'){
                    $workdays[] = $currentYear.'-'.$m.'-'.$i;
                    // array_push($monthsArray,$workdays);
                // }
            }
        }
        // return $workdays;
        $holidays = DB::table('schoolcal')
            ->select('datefrom','dateto')
            ->where('noclass','1')
            ->where('deleted','0')
            ->get();
        $holidaysArray = array();
        foreach($holidays as $twodates){
            $period = CarbonPeriod::create($twodates->datefrom, $twodates->dateto);
            foreach ($period as $date) {
                $date->format('Y-m-d');
            }
            array_push($holidaysArray, $period);
        }
        $days_num = array();
        $days_str = array();
        $daysMonth = array();
        foreach ($workdays as $date) {
            $noMatch = true;
            foreach(array_unique($holidaysArray) as $holi){
                foreach($holi as $holiday){
                if(date('M d Y',strtotime($date)) == date('M d Y',strtotime($holiday))){
                    $noMatch = false;
                }
                }
            }

            if($noMatch){
                array_push($days_num,date('d',strtotime($date)));
                array_push($days_str,date('D',strtotime($date)));
                array_push($daysMonth,date('M d Y',strtotime($date)));
            }
            // array_push($daysMonth,$date->isoFormat('MMM DD YYYY'));
        } 
        // $amountPaidArray = array();
        $jan = array();
        $feb = array();
        $mar = array();
        $apr = array();
        $may = array();
        $jun = array();
        $jul = array();
        $aug = array();
        $sep = array();
        $oct = array();
        $nov = array();
        $dec = array();
        foreach($daysMonth as $daysinmonth){
            // return date('Y-m-d',strtotime($daysinmonth));
            $amountpaid = DB::table('chrngtrans')
                ->select('amountpaid')
                ->whereDate('transdate', date('Y-m-d',strtotime($daysinmonth)))
                ->where('syid',$current_schoolyear[0]->id)
                ->sum('amountpaid');
                
            if(date('M',strtotime($daysinmonth)) == 'Jan'){
                array_push($jan,(object) array(
                    'day' => date('d',strtotime($daysinmonth)),
                    'amount' => $amountpaid
                ));
            }
            if(date('M',strtotime($daysinmonth)) == 'Feb'){
                array_push($feb,(object) array(
                    'day' => date('d',strtotime($daysinmonth)),
                    'amount' => $amountpaid
                ));
            }
            if(date('M',strtotime($daysinmonth)) == 'Mar'){
                array_push($mar,(object) array(
                    'day' => date('d',strtotime($daysinmonth)),
                    'amount' => $amountpaid
                ));
            }
            if(date('M',strtotime($daysinmonth)) == 'Apr'){
                array_push($apr,(object) array(
                    'day' => date('d',strtotime($daysinmonth)),
                    'amount' => $amountpaid
                ));
            }
            if(date('M',strtotime($daysinmonth)) == 'May'){
                array_push($may,(object) array(
                    'day' => date('d',strtotime($daysinmonth)),
                    'amount' => $amountpaid
                ));
            }
            if(date('M',strtotime($daysinmonth)) == 'Jun'){
                array_push($jun,(object) array(
                    'day' => date('d',strtotime($daysinmonth)),
                    'amount' => $amountpaid
                ));
            }
            if(date('M',strtotime($daysinmonth)) == 'Jul'){
                array_push($jul,(object) array(
                    'day' => date('d',strtotime($daysinmonth)),
                    'amount' => $amountpaid
                ));
            }
            if(date('M',strtotime($daysinmonth)) == 'Aug'){
                array_push($aug,(object) array(
                    'day' => date('d',strtotime($daysinmonth)),
                    'amount' => $amountpaid
                ));
            }
            if(date('M',strtotime($daysinmonth)) == 'Sep'){
                array_push($sep,(object) array(
                    'day' => date('d',strtotime($daysinmonth)),
                    'amount' => $amountpaid
                ));
            }
            if(date('M',strtotime($daysinmonth)) == 'Oct'){
                array_push($oct,(object) array(
                    'day' => date('d',strtotime($daysinmonth)),
                    'amount' => $amountpaid
                ));
            }
            if(date('M',strtotime($daysinmonth)) == 'Nov'){
                array_push($nov,(object) array(
                    'day' => date('d',strtotime($daysinmonth)),
                    'amount' => $amountpaid
                ));
            }
            if(date('M',strtotime($daysinmonth)) == 'Dec'){
                array_push($dec,(object) array(
                    'day' => date('d',strtotime($daysinmonth)),
                    'amount' => $amountpaid
                ));
            }
        }
        // return $jan;
        return view('finance.dashboard')
            ->with('setup', $setup)
            ->with('escstudents', count($escstudents))
            ->with('current_schoolyear',$current_schoolyear[0]->sydesc)
            ->with('preschoolTeachers',$preschoolTeachers)
            ->with('elemTeachers',$elemTeachers)
            ->with('juniorHighTeachers',$juniorHighTeachers)
            ->with('seniorHighTeachers',$seniorHighTeachers)
            ->with('preschoolStudents',$preschoolStudents)
            ->with('elemStudents',$elemStudents)
            ->with('juniorHighStudents',$juniorHighStudents)
            ->with('seniorHighStudents',$seniorHighStudents)
            ->with('jan',$jan)
            ->with('feb',$feb)
            ->with('mar',$mar)
            ->with('apr',$apr)
            ->with('may',$may)
            ->with('jun',$jun)
            ->with('jul',$jul)
            ->with('aug',$aug)
            ->with('sep',$sep)
            ->with('oct',$oct)
            ->with('nov',$nov)
            ->with('dec',$dec);
    }
}
