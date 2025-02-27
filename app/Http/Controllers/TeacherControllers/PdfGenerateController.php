<?php

namespace App\Http\Controllers\TeacherControllers;

use Illuminate\Http\Request;
use DB;
use PDF;
class PdfGenerateController extends \App\Http\Controllers\Controller
{
    public function pdfview(Request $request)
    {
        $users = DB::table("users")->get();
        view()->share('users',$users);

        if($request->has('download')){
        	// Set extra option
        	PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        	// pass view file
            $pdf = PDF::loadView('pdfview')->setPaper('a4', 'landscape');
            // download pdf
            return $pdf->download('pdfview.pdf');
        }
        return view('pdfview');
    }
}
