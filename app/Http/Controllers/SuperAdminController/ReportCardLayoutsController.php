<?php

namespace App\Http\Controllers\SuperAdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
class ReportCardLayoutsController extends Controller
{

    // return Report Card Layout view
    // public function index(){
    //     $schools = DB::table('school_list')
    //         ->select('id', 'schoolname as text')
    //         ->where('deleted', 0)
    //         ->get();

    //     $acadprog = DB::table('academicprogram')
    //     ->select('id', 'progname as text', 'acadprogcode')
    //         ->where('id', '<=', 5)
    //         ->get(); 

    //     $reportcards = DB::table('reportcard_layouts')
    //         ->where('deleted', 0)
    //         ->where('isactive', 1)
    //         ->get(); 

    //     $lasttemplate = DB::table('reportcard_layouts')
    //         ->select('id', 'description')
    //         ->orderBy('createdtime', 'desc')
    //         ->first();
        
    //     if ($lasttemplat = null) {
    //         $lasttemplate = '';
    //     }

    //     return view('superadmin.pages.reportcard.index')
    //         ->with('schools', $schools)
    //         ->with('acadprog', $acadprog)
    //         ->with('reportcards', $reportcards)
    //         ->with('lasttemplate', $lasttemplate);
    //         // ->with('path', $path);
    // }

  
    public function activatetemplate(Request $request){
     
        $templates = DB::table('reportcard_layouts')
                        ->where('deleted', 0)
                        ->where('isactive',1)
                        ->join('academicprogram',function($query){
                            $query->on('reportcard_layouts.acadprogid','=','academicprogram.id');
                        })
                        ->select(
                            'reportcard_layouts.*',
                            'progname'
                        )
                        ->get();

        return $templates;
    }

 
  
    // Add Template
    public function addtemplate(Request $request){

        try{

            $schoolname = $request->get('schoolname');
            $acadprogid = $request->get('acadprogid');
            $description = $request->get('description');
            $filename = $request->get('filename');
            $templatepath = $request->get('templatepath');

            $remove_existing = DB::table('reportcard_layouts')
                                    ->where('isactive',1)
                                    ->where('acadprogid',$acadprogid)
                                    ->update([
                                        'deleted'=>1,
                                        'deletedtime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

            $data = DB::table('reportcard_layouts')
                ->insert([
                    'description' => $description,
                    'templatepath' => $templatepath,
                    'schoolname' => $schoolname,
                    'filename' => $filename,
                    'createdtime' => \Carbon\Carbon::now('Asia/Manila'),
                    'acadprogid' => $acadprogid,
                    'isactive' => 1
                ]);

            return array((object)[
                'status'=>1,
                'data'=>'Assigned Successfully!',
            ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }

       
    }

    public static function store_error($e){
        DB::table('zerrorlogs')
        ->insert([
                    'error'=>$e,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
        return array((object)[
              'status'=>0,
              'data'=>'Something went wrong!'
        ]);
  }


    
}
