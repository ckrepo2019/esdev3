<?php

namespace App\Http\Controllers\SurveyControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use DB;
use Storage;
use Response;
use File;

class SurveyController extends Controller
{
    public function viewSurveyForm(Request $request){

        $student = DB::table('studinfo')->where('studinfo.id','1')
                    ->join('gradelevel',function($join){
                        $join->on('studinfo.levelid','=','gradelevel.id');
                        $join->where('gradelevel.deleted','0');
                    })
                    ->first();

        $activeSy = DB::table('sy')->where('isactive','1')->first();

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $testWord = $phpWord->loadTemplate('LEAF.docx');

        $testWord->setValue('gradleveltoenroll', $student->levelname);
        
       
        foreach(str_split(str_replace('-','',$activeSy->sydesc)) as $key=>$item){
           
            $testWord->setValue('s'.$key, $item);
        }

        $c2 = 1;
        $c3 = 1;
        $c8 = 1;
        $c9 = 1;
        $c14 = 1;
        $c15 = 1;
        $c4 = 1;
        $c10 = 1;
        $c16 = 1;

        if($student->lrn == null){

            $testWord->setValue('nlrn', 'X');
            $testWord->setValue('wlrn', '');

        }
        else{

            $testWord->setValue('nlrn', '');
            $testWord->setValue('wlrn', 'X');

        }

        for($x = 1; $x < 3; $x++){

            if($x != $c4){
                $testWord->setValue('c4'.$x, '');
            }
            else{
                $testWord->setValue('c4'.$c4, 'X');
            }

        }

        for($x = 1; $x < 3; $x++){

            if($x != $c10){
                $testWord->setValue('c10'.$x, '');
            }
            else{
                $testWord->setValue('c10'.$c10, 'X');
            }

        }

        $testWord->setValue('c4', 'X');

        for($x = 1; $x < 3; $x++){

            if($x != $c16){
                $testWord->setValue('c16'.$x, '');
            }
            else{
                $testWord->setValue('c16'.$c16, 'X');
            }

        }

        for($x = 0; $x < 6; $x++){

            if($x != $c2){
                $testWord->setValue('c2'.$x, '');
            }
            else{
                $testWord->setValue('c2'.$c2, 'X');
            }

        }

        for($x = 0; $x < 6; $x++){

            if($x != $c3){
                $testWord->setValue('c3'.$x, '');
            }
            else{
                $testWord->setValue('c3'.$c3, 'X');
            }

        }

        for($x = 0; $x < 6; $x++){

            if($x != $c8){
                $testWord->setValue('c8'.$x, '');
            }
            else{
                $testWord->setValue('c8'.$c8, 'X');
            }

        }

        for($x = 0; $x < 6; $x++){

            if($x != $c9){
                $testWord->setValue('c9'.$x, '');
            }
            else{
                $testWord->setValue('c9'.$c9, 'X');
            }

        }

        for($x = 0; $x < 6; $x++){

            if($x != $c14){
                $testWord->setValue('c14'.$x, '');
            }
            else{
                $testWord->setValue('c14'.$c14, 'X');
            }

        }

        for($x = 0; $x < 6; $x++){

            if($x != $c15){
                $testWord->setValue('c15'.$x, '');
            }
            else{
                $testWord->setValue('c15'.$c15, 'X');
            }

        }

       

        foreach(str_split($student->lrn) as $key=>$item){
           
            $testWord->setValue('l'.$key, $item);
        }

        $dob = \Carbon\Carbon::create($student->dob)->isoFormat('MMDDYYYY');

        $sting = '';

        foreach(str_split($dob) as $key=>$item){

            $testWord->setValue('d'.$key, $item);
        }
        $sy = DB::table('sy')->where('isactive','1')->first();
  
        $testWord->saveAs('result.pdf');

        // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($testWord, 'HTML');
        // $testWord->save('result.html');

      
        // $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($testWord , 'PDF');    
        // $pdfWriter->save($filename.".pdf");
        // unlink($wordPdf);

        // $file = 'result.pdf' ;

        // $transformHTMLPlugin = new TransformDocAdvHTMLDefaultPlugin();

        // $transform = new TransformDocAdvHTML('document.docx');

        // $html = $transform->transform($transformHTMLPlugin);

        // $testWord->transformDocument('result.docx', 'result.pdf');


        // if (file_exists($file)) {
        
        //     $file = File::get($file);
        //     $response = Response::make($file, 200);
        //     $content_types = [
        //         'application/octet-stream', // txt etc
        //         'application/msword', // doc
        //         'application/vnd.openxmlformats-officedocument.wordprocessingml.document', //docx
        //         'application/vnd.ms-excel', // xls
        //         'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // xlsx
        //         'application/pdf', // pdf
        //     ];
          
        //     $response->header('Content-Type', $content_types);

        //     return $response;
       
        // }

        

        return view('survey.leasf')
                ->with('student',$student)
                ->with('sy',$sy);

        // return response()->download('result.docx');


        // $templateProcessor = new TemplateProcessor('Template.docx');
      
        // $templateProcessor->setValue('firstname', 'John');
        // $templateProcessor->setValue('lastname', 'Doe');

        
        // $sy = DB::table('sy')->where('isactive','1')->first();


        // $pdf = PDF::loadView('survey.leasf',compact('student','sy'))->setPaper('legal');

        // // return $student->middlename;

     
        // // var_dump($result);

        // // return serialize($student->middlename);

        // // return htmlspecialchars($student->middlename)[4];

        // return $pdf->stream();
    }
}


// eyJpdiI6IlJLekRZUEZwRTJHcTNReGVxS3cxVEE9PSIsInZhbHVlIjoic1FPc2dvMmljQWJ6VGVlMWVnU21sTVNFXC9PbHZEb3Y5NFwvOCtrSlhsM0xxZllrYjVQSnBJU01aUnRZdmRpVnROIiwib;