<?php

namespace App\Http\Controllers\RegistrarControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class SchoolLastAttendedController extends Controller
{
    public function slaindex()
    {
        // return 'asdasda';
        $gradelevels = DB::table('gradelevel')
            ->where('deleted','0')
            ->orderBy('sortid','asc')
            ->get();

        return view('registrar.summaries.schoollastattended.index')
            ->with('gradelevels',$gradelevels);
    }
    public function slafilter(Request $request)
    {
        // return $request->all();
        // $students = collect();

        $students = DB::table('studinfo')
            ->select('studinfo.id','sid','lrn','lastname','firstname','middlename','suffix','gender','levelid','levelname','studstatus','lastschoolatt')
            ->join('gradelevel','studinfo.levelid','=','gradelevel.id')
            ->where('studinfo.levelid',$request->get('levelid'))   
            ->where('studinfo.deleted','0')   
            ->get();
        
        if(!$request->has('exporttoexcel'))
        {
            return view('registrar.summaries.schoollastattended.filter_tablestudents')
                ->with('students',$students);
        }else{
            $schoolinfo = Db::table('schoolinfo')
                ->select(
                    'schoolinfo.schoolid',
                    'schoolinfo.schoolname',
                    'schoolinfo.authorized',
                    'schoolinfo.picurl',
                    'refcitymun.citymunDesc',
                    'schoolinfo.district',
                    'schoolinfo.address',
                    'refregion.regDesc'
                )
                ->join('refregion','schoolinfo.region','=','refregion.regCode')
                ->join('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
                ->first();
                    
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();;
            $sheet = $spreadsheet->getActiveSheet();
            $border    = [
                                'borders' => [
                                    'top' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                    'bottom' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                    'left' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                    'right' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                ]
                            ];
            $font_bold = [
                    'font' => [
                        'bold' => true,
                    ]
                ];
                
            $sheet->getColumnDimension('A')->setWidth(5);
            $sheet->getColumnDimension('B')->setWidth(17);
            $sheet->getColumnDimension('C')->setWidth(15);

            $sheet->mergeCells('E2:I2');
            $sheet->mergeCells('E3:I3');
            $sheet->mergeCells('E4:I4');
            $sheet->mergeCells('E6:I6');
            $sheet->mergeCells('E7:I7');
            $sheet->setCellValue('E2', $schoolinfo->schoolname);
            $sheet->setCellValue('E3', $schoolinfo->address);
            $sheet->setCellValue('E4', 'Office of the Registrar');
            
            $sheet->setCellValue('E6', 'Last School Attended');            

            $sheet->getStyle('E2:I7')->getAlignment()->setHorizontal('center');
            $sheet->getStyle('E2:I2')->getFont()->setBold(true);

            $startcellno = 9;

            
            $sheet->getStyle('A'.$startcellno.':N'.$startcellno)->getAlignment()->setHorizontal('center');

            $sheet->setCellValue('A'.$startcellno, 'No.');
            $sheet ->getStyle('A'.$startcellno)->applyFromArray($border);

            $sheet->setCellValue('B'.$startcellno, 'LRN.');
            $sheet ->getStyle('B'.$startcellno)->applyFromArray($border);
            $sheet->setCellValue('C'.$startcellno, 'SID.');
            $sheet ->getStyle('C'.$startcellno)->applyFromArray($border);

            $sheet->mergeCells('D'.$startcellno.':G'.$startcellno);
            $sheet->setCellValue('D'.$startcellno, 'Students');
            $sheet ->getStyle('D'.$startcellno.':G'.$startcellno)->applyFromArray($border);

            $sheet->mergeCells('H'.$startcellno.':I'.$startcellno);
            $sheet->setCellValue('H'.$startcellno, 'Grade Level');
            $sheet ->getStyle('H'.$startcellno.':I'.$startcellno)->applyFromArray($border);

            $sheet->mergeCells('J'.$startcellno.':N'.$startcellno);
            $sheet->setCellValue('J'.$startcellno, 'Last School Attended');
            $sheet ->getStyle('J'.$startcellno.':N'.$startcellno)->applyFromArray($border);

            $startcellno+=1;

            // $sheet->getStyle('A'.$startcellno.':N'.$startcellno)->getAlignment()->setHorizontal('center');
            
            foreach($students as $studentkey => $student)
            {
                $sheet->setCellValue('A'.$startcellno, ($studentkey+1));
                $sheet ->getStyle('A'.$startcellno)->applyFromArray($border);
                
                $sheet->setCellValue('B'.$startcellno, $student->lrn);
                $sheet->getStyle('B')->getNumberFormat()->setFormatCode('0');
                $sheet ->getStyle('B'.$startcellno)->applyFromArray($border);

                $sheet->setCellValue('C'.$startcellno, $student->sid);
                $sheet ->getStyle('C'.$startcellno)->applyFromArray($border);
                $sheet->getStyle('C')->getNumberFormat()->setFormatCode('0');

                $sheet->mergeCells('D'.$startcellno.':G'.$startcellno);
                $sheet->setCellValue('D'.$startcellno, $student->lastname.', '.$student->firstname.' '.$student->suffix.' '.$student->middlename);
                $sheet ->getStyle('D'.$startcellno.':G'.$startcellno)->applyFromArray($border);

                $sheet->mergeCells('H'.$startcellno.':I'.$startcellno);
                $sheet->setCellValue('H'.$startcellno, $student->levelname);
                $sheet ->getStyle('H'.$startcellno.':I'.$startcellno)->applyFromArray($border);

                $sheet->mergeCells('J'.$startcellno.':N'.$startcellno);
                $sheet->setCellValue('J'.$startcellno, $student->lastschoolatt);
                $sheet ->getStyle('J'.$startcellno.':N'.$startcellno)->applyFromArray($border);

                $startcellno+=1;
            }
                

            
            
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="List of Students - Last School Attended.xlsx"');
            $writer->save("php://output");
        }
    }
    public function slaupdateschoolatt(Request $request)
    {
        // return $request->all();
        try{
            DB::table('studinfo')
                ->where('id', $request->get('studentid'))
                ->update([
                    'lastschoolatt'     => $request->get('lastschoolatt')
                ]);
            return 1;
        }catch(\Exception $error){
            return 2;
        }
    }
}
