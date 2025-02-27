<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Principal\SPP_Gradelevel;
use Illuminate\Support\Facades\Hash;
use File;
use Mail;


class SPP_Truncanator extends Model
{


    public static function trunclevel1(){
    }

    
 
    public static function generate_string($input, $strength = 16) {
       
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
    
        return $random_string;
    }

    public static function trunclevel4(
        $principal = true,
        $admin = true,
        $registrar = true,
        $finance = true,
        $cashier = true,
        $humanresourse = true,
       $request
    ){

        //self::backupdb();

        $urlFolder = str_replace('http://','',$request->root());

        if($principal){

            self::truncprincipal();

        }
        else if($admin){
            
            $schoollogo = public_path('schoollogo');
            $storage = public_path('storage');
            $outsideSchoollogo = dirname(base_path(), 1).'/'.$urlFolder.'/schoollogo';
            $outsidestorage = dirname(base_path(), 1).'/'.$urlFolder.'/storage';
            File::deleteDirectory($schoollogo); 
            File::deleteDirectory($storage); 
            File::deleteDirectory($outsideSchoollogo); 
            File::deleteDirectory($outsidestorage); 

            $advertisements = public_path('advertisements');
            $outsideAdvertisements = dirname(base_path(), 1).'/'.$urlFolder.'/advertisements';

            File::deleteDirectory($advertisements); 
            File::deleteDirectory($outsideAdvertisements); 

            self::truncateadmin();
           
        }
        else if($registrar){
          
            self::truncregistrar();

        }
        else if($cashier){

            // $onlinepayments = public_path('onlinepayments');
            // $outsideOnlinePayment = dirname(base_path(), 1).'/'.$urlFolder.'/onlinepayments';
            // File::deleteDirectory($onlinepayments); 
            // File::deleteDirectory($outsideOnlinePayment); 

            self::trunccashier();

        }
        else if($humanresourse){

            self::truncatehr();

        }
        else if($finance){
       
            self::truncatefinance();

        }
      
        toast('Successfull','success')->autoClose(2000)->toToast($position = 'top-right');
        return back();

    }


    public static function truncprincipal(){

        self::truncsubj();

    }


    public static function truncate(
        $teacher = false,
        $sections = false,
        $grades = false,
        $enrolledstud = false,
        $testing = false,
        $schedule = false,
        $humanresourse = false,
        $finance = false,
        $admin = false,
        $cashier = false,
        $room = false
    ){

        //self::backupdb();

        if($enrolledstud){

            self::truncgrades();
            self::truncenstud();

        }

        else if($grades){

            self::truncgrades();

        }
  
        else if($schedule){

            self::truncschedule();
            self::truncgrades();

        }
        
        else if($humanresourse){

            self::truncatehr();

        }

        
        else if($finance){

            self::truncatefinance();

        }

        
        else if($admin){

            self::truncateadmin();
           
        }
        else if($cashier){

            self::trunccashier();

        }

        else if($teacher){

            self::truncteacher();
            self::truncschedule();
            self::truncgrades();

        }

        else if($sections){

            self::truncschedule();
            self::truncgrades();
            self::truncsection();

            DB::table('enrolledstud')->update(['sectionid'=>null,'teacherid'=>null]);
            DB::table('studinfo')->update([
                'sectionname'=>null,
                'sectionid'=>null]);

        }
        else if($room){

            self::truncroom();
            self::truncschedule();
            self::truncgrades();
            self::truncsection();

        }
     

        else if($testing){

            DB::table('users')->truncate();
            DB::table('schoolcal')->truncate();

            DB::table('perreq')->truncate();
            DB::table('perreqdetail')->truncate();

            DB::table('sy')->truncate();
            DB::table('teacherattendance')->truncate();
      
           
            DB::table('unpostrequest')->truncate();
            DB::table('schoolinfo')->truncate();

            DB::table('users')->insert([
                'name'=>'ADMIN',
                'email'=>'ADMIN',
                'password'=>Hash::make('123456789'),
                'type'=>'6'
            ]);

            DB::table('users')->insert([
                'name'=>'ADMINADMIN',
                'email'=>'ADMINADMIN',
                'password'=>Hash::make('123456789'),
                'type'=>'12'
            ]);

            DB::table('users')->insert([
                'name'=>'SUPER ADMIN',
                'email'=>'ckgroup',
                'password'=>Hash::make('123456789'),
                'type'=>'17'
            ]);

            DB::table('semester')->update(['isactive'=>'0']);
            DB::table('semester')->where('id','1')->update(['isactive'=>'1']);

            self::truncsubj();
            self::truncroom();
            self::truncgrades();
            self::truncschedule();
            self::truncteacher();
            self::truncenstud();
            self::truncsection();
            self::truncatehr();
            self::truncatefinance();
            self::truncatecashier();
            

        }

      
        
        toast('Successfull','success')->autoClose(2000)->toToast($position = 'top-right');
        return back();

    }

    public static function truncatefinance(){

        //expenses
        //expensedetail

        DB::table('itemclassification')->truncate();
        DB::table('items')->truncate();
        DB::table('items_dp')->truncate();

        DB::table('tuitiondetail')->truncate();
        DB::table('tuitionheader')->truncate();
        DB::table('tuitionitems')->truncate();
     
        // DB::table('acc_coa')->truncate();
        // DB::table('balforwardsetup')->truncate();


        DB::table('paymentdiscount')->truncate();
        DB::table('paymentpenalty')->truncate();
        DB::table('paymentsched')->truncate();
        DB::table('paymentsetup')->truncate();
        DB::table('paymentsetupdetail')->truncate();
        // DB::table('paymenttype')->truncate();

        DB::table('expense')->truncate();
        DB::table('expensedetail')->truncate();
        DB::table('discounts')->truncate();

    }

    public static function trunccashier(){
        

        DB::table('orcounter')->truncate();
        DB::table('adjustments')->truncate();
        DB::table('adjustmentdetails')->truncate();
        DB::table('chrngcashtrans')->truncate();
        DB::table('chrngcrs')->truncate();
        DB::table('chrngday')->truncate();
        DB::table('chrngpermission')->truncate();
        DB::table('chrngshift')->truncate();
        DB::table('chrngtrans')->truncate();
        DB::table('chrngtransdetail')->truncate();
        DB::table('chrngvoidtrans')->truncate();
        DB::table('announcements')->truncate();
        DB::table('allowance_standard')->truncate();
        DB::table('studpaysched')->truncate();
        DB::table('studpayscheddetail')->truncate();

        DB::table('chrngterminals')->update(['owner'=>null]);

        DB::table('onlinepayments')->truncate();
        DB::table('onlinepaymentdetails')->truncate();

    }




    public static function truncateadmin(){

        // $adminPass = '123456789';

        self::truncteacher();
        self::truncroom();

        DB::table('schoolcal')->truncate();
        DB::table('smsbunker')->truncate();
        DB::table('building')->truncate();

        DB::table('users')->truncate();

        DB::table('adimages')->truncate();
        DB::table('perreq')->truncate();
        DB::table('perreqdetail')->truncate();

        DB::table('sy')->truncate();
        DB::table('unpostrequest')->truncate();

        DB::table('semester')->update(['isactive'=>'0']);
        DB::table('semester')->where('id','1')->update(['isactive'=>'1']);

        DB::table('schoolinfo')->update([
            'schoolid'=>null,
            'schoolname'=>null,
            'region'=>null,
            'division'=>null,
            'district'=>null,
            'address'=>null,
            'picurl'=>null,
            'tagline'=>'SCHOOL TAGLINE',
            'tagline'=>null,
            'abbreviation'=>null
        ]);

        DB::table('users')->insert([
            'name'=>'SADMIN',
            'email'=>'ckgroup',
            'password'=>Hash::make('CK_publishin6'),
            'type'=>'17'
        ]);

        // $allcaps = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // $lowcaps = 'abcdefghijklmnopqrstuvwxyz';

        // $permitted_chars = '0123456789'.$lowcaps;

        // $adminitPass = self::generate_string($permitted_chars, 10);
        // $adminadminPass = self::generate_string($permitted_chars, 10);
        // $superAdminPass = self::generate_string($permitted_chars, 10);

        // $to_name = 'GRANT M SANCHEZ';

        // $to_email = 'grant.sanchez2015@gmail.com';

        // $data = array(
        //     'adminitaccount'=>'admin', 
        //     'adminitPass' => $adminitPass,
        //     'adminadminaccount'=>'adminadmin', 
        //     'adminadminPass' => $adminadminPass,
        //     'superAdminaccount'=>'ckgroup', 
        //     'superAdminPass' => $superAdminPass,
        // );

        // Mail::send('email.mail', $data, function($message) use ($to_name, $to_email) {

        // $message->to($to_email, $to_name)
        //                   ->subject('Laravel Test Mail');

        // $message->from('grant.sanchez2015@gmail.com','Test Mail');
        // });



        // DB::table('users')->insert([
        //     'name'=>'ADMIN',
        //     'email'=>'ADMIN',
        //     'password'=>Hash::make($adminitPass),
        //     'type'=>'6'
        // ]);

        // DB::table('users')->insert([
        //     'name'=>'ADMINADMIN',
        //     'email'=>'ADMINADMIN',
        //     'password'=>Hash::make($adminadminPass),
        //     'type'=>'12'
        // ]);

        // DB::table('users')->insert([
        //     'name'=>'SUPER ADMIN',
        //     'email'=>'ckgroup',
        //     'password'=>Hash::make($superAdminPass),
        //     'type'=>'17'
        // ]);

      

    }

    public static function truncroom(){

        DB::table('rooms')->truncate(); //rooms
        // DB::table('sections')->update(['roomid'=>0]);
        // DB::table('classscheddetail')->update(['roomid'=>0]);
        // DB::table('sh_classscheddetail')->update(['roomid'=>0]);
        // DB::table('sh_blockscheddetail')->update(['roomid'=>0]);

    }


    public static function truncatehr(){

        DB::table('employee_allowance')->truncate();
        DB::table('employee_allowanceinfo')->truncate();
        DB::table('employee_allowanceinfodetail')->truncate();
        DB::table('employee_allowanceother')->truncate();
        DB::table('employee_allowanceotherdetail')->truncate();
        DB::table('employee_allowancestandard')->truncate();
        DB::table('employee_basicsalaryinfo')->truncate();
        DB::table('employee_benefits')->truncate();
        DB::table('employee_cashadvanceinfo')->truncate();
        DB::table('employee_customtimesched')->truncate();
        DB::table('employee_deductioninfo')->truncate();
        DB::table('employee_deductionother')->truncate();
        DB::table('employee_deductionotherdetail')->truncate();
        DB::table('employee_deductionstandard')->truncate();
        DB::table('employee_educationinfo')->truncate();
        DB::table('employee_experience')->truncate();
        DB::table('employee_familyinfo')->truncate();
        DB::table('employee_leaves')->truncate();
        DB::table('employee_overtime')->truncate();
        DB::table('employee_overtimeattachments')->truncate();
        DB::table('employee_overtimedetail')->truncate();
        DB::table('employee_personalinfo')->truncate();
        DB::table('employee_salary')->truncate();
        DB::table('employee_salaryhistory')->truncate();
        DB::table('employee_salaryhistorydetail')->truncate();

        DB::table('deduction_standard')->truncate();
        DB::table('deduction_standarddetail')->truncate();
        DB::table('deduction_tardinessapplication')->truncate();
        DB::table('deduction_tardinessdetail')->truncate();
        DB::table('deduction_type')->truncate();
        DB::table('deduction_typedetail')->truncate();

        DB::table('job_deduction')->truncate();
        DB::table('job_leavesdetail')->truncate();
        DB::table('job_deductiondetail')->truncate();
        DB::table('job_description')->truncate();
        DB::table('job_overtime')->truncate();
        DB::table('job_payroll')->truncate();
        DB::table('job_payroll_history')->truncate();
        

        DB::table('payroll')->truncate();
        DB::table('payroll_history')->truncate();
        DB::table('payroll_historydetail')->truncate();
        DB::table('payrolldeductiondetail')->truncate();
        DB::table('payrolldetail')->truncate();
        DB::table('payrollearnings')->truncate();
        DB::table('payrollleavesdetail')->truncate();

        DB::table('hr_school_department')->truncate();
        DB::table('hr_school_department')
                    ->insert(
                        [
                            'department'=>'administrative department',
                            'constant'=>1
                        ]
                    );
    }

    public static function truncenstud(){

        DB::table('observedvalues')->truncate();
        DB::table('observedvaluesdetail')->truncate();
        DB::table('enrolledstud')->truncate();
        DB::table('sh_enrolledstud')->truncate();
        DB::table('sh_studentsched')->truncate();
        DB::table('studattendance')->truncate();
        DB::table('studdiscounts')->truncate();
        DB::table('studentsubjectattendance')->truncate();
        DB::table('studledger')->truncate();
        DB::table('studledgeritemized')->truncate();
        DB::table('studpaysched')->truncate();
        DB::table('studpayscheddetail')->truncate();
        DB::table('notifications')->truncate();

        DB::table('studinfo')->truncate();
        DB::table('preregistration')->truncate();
       
        DB::table('users')
            ->whereIn('type',['7',9])
            ->delete();

        DB::table('sf10childattendance')->truncate();
        DB::table('sf10childgrades')->truncate();
        DB::table('sf10eligibility')->truncate();
        DB::table('sf10parent')->truncate();
        DB::table('sf10remedial')->truncate();
        DB::table('sf10schoolinfo')->truncate();
        DB::table('sf10_grades_sh')->truncate();
        DB::table('sf10_schoollist')->truncate();
        // DB::table('sf10_student_js')->truncate();
        DB::table('sf10_student_sh')->truncate();

    }


    public static function truncteacher(){

        DB::table('academicprogram')->update(['principalid'=>'0']);

        // DB::table('sections')->update(['teacherid'=>'0']);
        // DB::table('sectiondetail')->update(['teacherid'=>'0']);

        DB::table('notifications')->truncate();
        DB::table('teacher')->truncate();
        DB::table('announcements')->truncate();
        DB::table('teacheracadprog')->truncate();
        DB::table('faspriv')->truncate();
        DB::table('teacherattendance')->truncate();

        DB::table('users')->whereNotIn(
            'type',['6','7','9']
        )->delete();

        // DB::table('job_deduction')->truncate();
        // DB::table('job_deductiondetail')->truncate();
        // DB::table('job_description')->truncate();
        // DB::table('job_overtime')->truncate();
        // DB::table('job_payroll')->truncate();
        // DB::table('job_payroll_history')->truncate();

    }

    public static function truncsection(){

        DB::table('sections')->truncate();

        DB::table('sh_block')->truncate();

        DB::table('sectiondetail')->truncate();

        DB::table('college_sections')->truncate();

        DB::table('sh_sectionblockassignment')->truncate();

        self::truncschedule();

    }


    public static function truncschedule(){

        DB::table('assignsubj')->truncate();
        DB::table('assignsubjdetail')->truncate();

        DB::table('classsched')->truncate();
        DB::table('classscheddetail')->truncate();

        DB::table('sh_classsched')->truncate();
        DB::table('sh_classscheddetail')->truncate();

        DB::table('sh_blocksched')->truncate();
        DB::table('sh_blockscheddetail')->truncate();


        DB::table('college_classsched')->truncate();
        DB::table('college_scheddetail')->truncate();
        DB::table('college_studsched')->truncate();

        DB::table('classsubj')->truncate();

    }

    public static function truncgrades(){

        DB::table('grades')->truncate();
        DB::table('gradesspclass')->truncate();
        DB::table('gradesdetail')->truncate();
        DB::table('gradelogs')->truncate();
        DB::table('tempgradesum')->truncate();
        DB::table('notifications')->whereIn('type',['2','3'])->delete();

    }

    public static function truncregistrar(){

        self::truncenstud();

        DB::table('preregistration_answers')->truncate();
        DB::table('preregistration_examination')->truncate();
        DB::table('preregistration_questions')->truncate();
        DB::table('preregmedicalinfo')->truncate();
        DB::table('preregreligiousinfo')->truncate();
        DB::table('preregscholasticinfo')->truncate();

    }


    public static function truncsubj(){

        DB::table('subjects')->truncate();

        DB::table('sh_subjects')->truncate();
        DB::table('sh_prerequisite')->truncate();
        DB::table('sh_sh_corequisite')->truncate();

        DB::table('college_subjects')->truncate();
        DB::table('college_subjprereq')->truncate();
        DB::table('college_teachersubjects')->truncate();

        self::truncgrades();
        self::turncgradesetup();
        self::truncsection();

    }


    public static function turncgradesetup(){

        DB::table('gradessetup')->truncate();

    }


    public static function backupdb(){


        $stringToAppend = 'testing';

        if (! File::exists(public_path().'dbbackup/')) {

            $path = public_path('dbbackup');

            if(!File::isDirectory($path)){

                File::makeDirectory($path, 0777, true, true);
            }
        }

        $newLine = "\r\n";
        $targetTables = [];


        $queryTables = DB::select(DB::raw('SHOW TABLES'));

        $dbname = str_replace("Tables_in_","",collect($queryTables[0])->keys()[0]);

        $tableKey = collect($queryTables[0])->keys()[0];

        foreach($queryTables as $key=>$table){
           
            $targetTables[] = $table->$tableKey;

        }



        $content = "";

        foreach($targetTables as $table){

            try{
                $tableData = DB::select(DB::raw('SELECT * FROM '.$table));
            }
            catch (\Exception $e) {
                
            }

            $content .= "USE ".$dbname.';'.$newLine.$newLine;

            $content .= "DROP TABLE IF EXISTS `".$table."`".';'.$newLine.$newLine;

            $res = DB::select(DB::raw('SHOW CREATE TABLE '.$table));
         
            $content .= $res[0]->{'Create Table'}.';'.$newLine.$newLine;

           
            if(count($tableData)>0){

                $content .=" INSERT INTO "."`".$table."` (";

                $fields = array_keys(collect($tableData[0])->toArray());

                foreach($fields as $field){

                    $content .= "`".$field."`,";

                }

                $content = substr($content,0,-1);

                $content .= ") values ";

                foreach($tableData as $item){

                    $content.="(";
                    
                    foreach($item as $key=>$data){

                        if($data == ''){
                        
                            $content .= "NULL";

                        }
                        else{

                            if(gettype($data) == 'integer'){
                                $content .= $data;
                            }
                            else{
                                $content .= " '".$data."'";
                            }
                           

                        }
                        
                        $content .= ",";

                    }

                    $content = substr($content,0,-1);
                   
                    $content.='),';
                    
                }
                

                $content = substr($content,0,-1);

                $content.=';'.$newLine.$newLine;
               
            }

            


        }

        date_default_timezone_set('Asia/Manila');
        $date = date('mdY Hi');

        // DB::raw($content);

        file_put_contents('dbbackup/'.$dbname.' '.$date.'.sql', $content, FILE_APPEND);

        // return;

    }


}
