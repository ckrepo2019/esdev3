<?php

namespace App\Http\Controllers\SuperAdminController;

use Illuminate\Http\Request;
use DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use App\Models\Grading\GradeStatus;
use App\Models\Subjects\Subjects;

class ExcelGenerator extends \App\Http\Controllers\Controller
{
    
     
    public static function ms_team_account(){

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // $students = DB::table('studinfo')
        //             ->whereIn('levelid',[16,17,18,19])
        //             ->select('lastname','firstname','middlename')
        //             ->where('deleted',0)
        //             ->orderBy('firstname')
        //             ->get();

        // $data = DB::table('teacher')
        //             ->where('deleted',0)
        //             ->select('lastname','firstname','middlename')
        //             ->get();
        
        
        //basic ed
        $data = DB::table('studinfo')
                        ->whereNotIn('levelid',[16,17,18,19])
                        ->select('lastname','firstname','middlename')
                        ->where('deleted',0)
                        ->orderBy('firstname')
                        ->get();
     
        $sheet
            ->setCellValue('A1', 'Username')
            ->setCellValue('B1', 'First name')
            ->setCellValue('C1', 'Last name')
            ->setCellValue('D1', 'Display name')
            ->setCellValue('E1', 'Job title')
            ->setCellValue('F1', 'Department')
            ->setCellValue('G1', 'Office number')
            ->setCellValue('H1', 'Office phone')
            ->setCellValue('I1', 'Mobile phone')
            ->setCellValue('J1', 'Fax')
            // ->setCellValue('K1', 'Alternate email address')
            ->setCellValue('K1', 'Address')
            ->setCellValue('L1', 'City')
            ->setCellValue('M1', 'State or province')
            ->setCellValue('N1', 'ZIP or postal code')
            ->setCellValue('O1', 'Country or region');

        $counter = 2;
        $sheetCounter = 1;

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Batch 1');


        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);

       
        foreach($data as $items){

            $middlename = substr($items->middlename, 0 ,1);
            $firstname = ucwords( strtolower( $items->firstname .' '.$middlename.'.' ) );
            $username =  strtolower( trim($items->firstname.$items->lastname, " ").'@sait.edu.ph' )  ;

            $sheet->setCellValue('A'.$counter, str_replace(' ','',$username) )
                    ->setCellValue('B'.$counter, $firstname )
                    ->setCellValue('C'.$counter,  ucwords( strtolower( $items->lastname ) ) )
                    ->setCellValue('D'.$counter,  $firstname.' '. ucwords( strtolower( $items->lastname ) ) )
                    ->setCellValue('P'.$counter, 'Philippines');

            $counter += 1;

            if( $counter == 121){

                $spreadsheet->createSheet();
                $spreadsheet->setActiveSheetIndex($sheetCounter);
                $spreadsheet->getActiveSheet()->setTitle('Batch '. $sheetCounter);
                $sheet =$spreadsheet->getActiveSheet();

                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);

                $sheet
                    ->setCellValue('A1', 'Username')
                    ->setCellValue('B1', 'First name')
                    ->setCellValue('C1', 'Last name')
                    ->setCellValue('D1', 'Display name')
                    ->setCellValue('E1', 'Job title')
                    ->setCellValue('F1', 'Department')
                    ->setCellValue('G1', 'Office number')
                    ->setCellValue('H1', 'Office phone')
                    ->setCellValue('I1', 'Mobile phone')
                    ->setCellValue('J1', 'Fax')
                    ->setCellValue('K1', 'Alternate email address')
                    ->setCellValue('L1', 'Address')
                    ->setCellValue('M1', 'City')
                    ->setCellValue('N1', 'State or province')
                    ->setCellValue('O1', 'ZIP or postal code')
                    ->setCellValue('P1', 'Country or region');
    

                $counter = 2;
                $sheetCounter += 1;

            }

        }

        $schoolName = DB::table('schoolinfo')->select('abbreviation')->first();
    
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.$schoolName->abbreviation.' - '.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYY HHMM').'.xlsx"');
        $writer->save("php://output");

    }

    public static function acadprog(){
        return 5;
    }

    public function grades_excel(Request $request){

        $sectionid = $request->get('sectionid');
        $subjectid = $request->get('subjectid');
        $quarter = $request->get('quarter');
        $track = $request->get('track');

        $students = DB::table('sh_enrolledstud')
                        ->where('sh_enrolledstud.deleted',0)
                        ->whereIn('sh_enrolledstud.studstatus',[1,2,4])
                        ->join('studinfo',function($join){
                            $join->on('sh_enrolledstud.studid','=','studinfo.id');
                            $join->where('studinfo.deleted',0);
                        })
                        ->join('sh_strand',function($join){
                            $join->on('studinfo.strandid','=','sh_strand.id');
                            $join->where('sh_strand.deleted',0);
                        })
                        ->select('studinfo.id','sh_enrolledstud.sectionid','lastname','firstname','gender')
                        ->orderBy('gender','desc')
                        ->orderBy('lastname')
                        ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // $checkStatus = GradeStatus::check_grade_status_sh($sectionid, $subjectid, $quarter,$syid,$semid);

        $subjectStatus = Subjects::get_sh_subject($subjectid);

        if(count($subjectStatus) == 0){
                
                $data = array((object)[
                    'status'=>0,
                    'data'=>"Subject does not exist"
                ]);

                return $data;
        }

        $grading_system = self::subject_grading_system($subjectid);

        if($grading_system[0]->status == 0){
            return $grading_system;
        }
        else{
            $grading_system = collect($grading_system[0]->data)->where('trackid',1)->first();
        }

        // if($syid == null){
        //         $syid = DB::table('sy')->where('isactive',1)->first()->id;
        // }
        // if($semid == null){
        //         $semid = DB::table('semester')->where('isactive',1)->first()->id;
        // }


        $counter = 1;
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $male = 0;
        $female = 0;

        $counter += 1;
        $column = 66;
        // $newcolumn = 

        for($x = 0; $x<2;$x++){

            $xy = 1;
           
            for($y=$xy; $y<=10;$y++){

                $sheet->setCellValue(chr($column).$counter, $y);
                $column += 1;

            }

            $spreadsheet->getActiveSheet()->getColumnDimension(chr($column))->setAutoSize(true);

            $sheet->setCellValue(chr($column).$counter, 'TOTAL');
            $column += 1;
            $sheet->setCellValue(chr($column).$counter, 'PS');
            $column += 1;
            $sheet->setCellValue(chr($column).$counter, 'WS');
            if($column > 90){


            }
        }

        $counter += 1;

        $column = 66;

        $grading_system_detail = DB::table('grading_system')
                            ->join('grading_system_detail',function($join){
                                $join->on('grading_system.id','=','grading_system_detail.headerid');
                                $join->where('grading_system_detail.deleted',0);
                            })
                            ->where('acadprogid',self::acadprog())
                            ->where('grading_system.id',$grading_system->id)
                            ->where('grading_system.deleted',0)
                            ->select('grading_system_detail.*')
                            ->get();

        foreach($grading_system_detail as $item){

            $xy = 1;
            $total_string = '';

            for($y=$xy; $y<=10;$y++){
                $total_string .= chr($column).$counter.'+';
                $column += 1;
                
            }

            $total_string = substr($total_string, 0, -1);

            $spreadsheet->getActiveSheet()->getColumnDimension(chr($column))->setAutoSize(true);

            $sheet->setCellValue(chr($column).$counter, '=('.$total_string.')');
            $total_cell = $counter;
            $column += 1;
            $sheet->setCellValue(chr($column).$counter, '100');
            $column += 1;
            $sheet->setCellValue(chr($column).$counter, $item->value);
       

        }

        

        $counter += 1;

        foreach($students as $item){

            if($male == 0 && ( $item->gender == 'MALE' || $item->gender == 'Male' ) ){
                $sheet->setCellValue('A'.$counter, 'MALE' );
                $counter +=1;
                $male = 1;
            }
            if($female == 0 && ( $item->gender == 'FEMALE' || $item->gender == 'Female' )){
                $sheet->setCellValue('A'.$counter, 'FEMALE' );
                $counter +=1;
                $female = 1;
            }

            $sheet->setCellValue('A'.$counter, $item->lastname.', '.$item->firstname );
            $counter +=1;
      


        }

        // $students = DB::table('studinfo')
        //             ->whereIn('levelid',[16,17,18,19])
        //             ->select('lastname','firstname','middlename')
        //             ->where('deleted',0)
        //             ->orderBy('firstname')
        //             ->get();

        // $sheet
        //     ->setCellValue('A1', 'Username')
        //     ->setCellValue('B1', 'First name')
        //     ->setCellValue('C1', 'Last name')
        //     ->setCellValue('D1', 'Display name')
        //     ->setCellValue('E1', 'Job title')
        //     ->setCellValue('F1', 'Department')
        //     ->setCellValue('G1', 'Office number')
        //     ->setCellValue('H1', 'Office phone')
        //     ->setCellValue('I1', 'Mobile phone')
        //     ->setCellValue('J1', 'Fax')
        //     ->setCellValue('K1', 'Alternate email address')
        //     ->setCellValue('L1', 'Address')
        //     ->setCellValue('M1', 'City')
        //     ->setCellValue('N1', 'State or province')
        //     ->setCellValue('O1', 'ZIP or postal code')
        //     ->setCellValue('P1', 'Country or region');

        // $counter = 2;
        // $sheetCounter = 1;

        // $sheet = $spreadsheet->getActiveSheet();
        // $sheet->setTitle('Batch 1');

        // foreach($students as $items){

        //     $middlename = substr($items->middlename, 0 ,1);
        //     $firstname = ucwords( strtolower( $items->firstname .' '.$middlename.'.' ) );
        //     $username =  strtolower( trim($items->firstname.$items->lastname, " ").'@sait.edu.ph' )  ;

        //     $sheet->setCellValue('A'.$counter, str_replace(' ','',$username) )
        //             ->setCellValue('B'.$counter, $firstname )
        //             ->setCellValue('C'.$counter,  ucwords( strtolower( $items->lastname ) ) )
        //             ->setCellValue('D'.$counter,  $firstname.' '. ucwords( strtolower( $items->lastname ) ) )
        //             ->setCellValue('P'.$counter, 'Philippines');

        //     $counter += 1;

        //     if( $counter == 41){

        //         $spreadsheet->createSheet();
        //         $spreadsheet->setActiveSheetIndex($sheetCounter);
        //         $spreadsheet->getActiveSheet()->setTitle('Batch '. $sheetCounter);
        //         $sheet =$spreadsheet->getActiveSheet();

        //         $sheet
        //             ->setCellValue('A1', 'Username')
        //             ->setCellValue('B1', 'First name')
        //             ->setCellValue('C1', 'Last name')
        //             ->setCellValue('D1', 'Display name')
        //             ->setCellValue('E1', 'Job title')
        //             ->setCellValue('F1', 'Department')
        //             ->setCellValue('G1', 'Office number')
        //             ->setCellValue('H1', 'Office phone')
        //             ->setCellValue('I1', 'Mobile phone')
        //             ->setCellValue('J1', 'Fax')
        //             ->setCellValue('K1', 'Alternate email address')
        //             ->setCellValue('L1', 'Address')
        //             ->setCellValue('M1', 'City')
        //             ->setCellValue('N1', 'State or province')
        //             ->setCellValue('O1', 'ZIP or postal code')
        //             ->setCellValue('P1', 'Country or region');
    

        //         $counter = 2;
        //         $sheetCounter += 1;

        //     }

        // }

        $schoolName = DB::table('schoolinfo')->select('abbreviation')->first();
    
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.$schoolName->abbreviation.' - '.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYY HHMM').'.xlsx"');
        $writer->save("php://output");

    }

    public static function subject_grading_system($subject = null){

        $grading_system = DB::table('sh_subjects')
                    ->join('grading_system_subjassignment',function($join){
                          $join->on('sh_subjects.id','=','grading_system_subjassignment.subjid');
                          $join->where('grading_system_subjassignment.deleted',0);
                    })
                    ->join('grading_system',function($join){
                          $join->on('grading_system_subjassignment.gsid','=','grading_system.id');
                          $join->where('grading_system.deleted',0);
                          $join->where('grading_system.acadprogid',self::acadprog());
                    })
                    ->where('inSF9',1)
                    ->where('sh_subjects.id',$subject)
                    ->select('grading_system.*','sh_subjects.type')
                    ->get();

        if(count($grading_system ) == 1 && $grading_system[0]->id != null){

              $gsdetail =  DB::table('grading_system_detail')
                                ->where('headerid',$grading_system[0]->id)
                                ->where('deleted',0)
                                ->count();

              if($gsdetail == 0){

                    $data = array((object)[
                          'status'=>0,
                          'data'=>"This grading system does not contain any detail. \n Please add details to continue.",
                    ]);

                    return $data;

              }

        }
        else if(count($grading_system ) == 1 && $grading_system[0]->id == null){

              $data = array((object)[
                    'status'=>0,
                    'data'=>"This subject is not yet assigned to a grading system",
              ]);

              return $data;

        }
        else if(count($grading_system) == 0){

              $data =  array((object)[
                    'status'=>0,
                    'data'=>"No available grading for this subject."
              ]);

              return $data;

        }
        
        return    $data =  array((object)[
                          'status'=>1,
                          'data'=> $grading_system
                    ]);
                    
  }

      
}
