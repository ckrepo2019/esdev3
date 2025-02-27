<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
class SignatoriesController extends Controller
{
    public function index(Request $request)
    {
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;
        if(Session::get('currentPortal') == '1'){

            $extends = "teacher.layouts.app";
            
        }elseif(Session::get('currentPortal') == '2'){

            $extends = "principalsportal.layouts.app2";

        }elseif(Session::get('currentPortal') == '3' || Session::get('currentPortal') == '8'){

            $extends = "registrar.layouts.app";

        }elseif(Session::get('currentPortal') == '4' || Session::get('currentPortal') == '15'){

            $extends = "finance.layouts.app";

        }elseif(Session::get('currentPortal') == '6'){

            $extends = "adminPortal.layouts.app2";

        }elseif(Session::get('currentPortal') == '10' || $refid == 26){

            $extends = "hr.layouts.app";

        }elseif(Session::get('currentPortal') == '12'){

            $extends = "adminITPortal.layouts.app";

        }elseif(Session::get('currentPortal') == 14){

            $extends = "deanportal.layouts.app2";

        }else{
            $extends = "general.defaultportal..layouts.app";
        }
        
        return view('signatories.index')
            ->with('extends', $extends);
            
    }
    public function getacadprogs(Request $request)
    {
        $academicprograms = DB::table('academicprogram')
            ->where('id','!=','7') //exclude tech-voc
            ->get();

        if($request->get('formid') == 'form5')
        {
            $academicprograms = collect($academicprograms)->where('id','!=','5')->where('id','!=','6')->values();
        }
        elseif($request->get('formid') == 'form5a')
        {
            $academicprograms = collect($academicprograms)->where('id',5)->values();
        }
        elseif($request->get('formid') == 'form5b')
        {
            $academicprograms = collect($academicprograms)->where('id',5)->values();
        }
        elseif($request->get('formid') == 'form10')
        {
            $academicprograms = collect($academicprograms)->where('id','!=','6')->values();
        }

        return $academicprograms;
    }
    public function getlevelids(Request $request)
    {
        $gradelevels = DB::table('gradelevel')
            ->where('acadprogid', $request->get('acadprogid'))
            ->where('deleted','0')
            ->get();

        return $gradelevels;
    }
    public function getsignatories(Request $request)
    {
        $signatories = DB::table('signatory')
            ->where('deleted','0')
            ->where('syid',$request->get('syid'))
            // ->where('acadprogid',$request->get('acadprogid'))
            ->get();

        if($request->get('formid') == 'form4')        
        {
            $signatories = collect($signatories)->where('form', $request->get('formid'))->values();
        }else{
            $signatories = collect($signatories)->where('acadprogid', $request->get('acadprogid'))->values();
            if($request->get('levelid') != 0)
            {
                $signatories = collect($signatories)->where('levelid', $request->get('levelid'))->values();
            }
    
            if($request->get('formid') == 'form9')
            {
                $signatories = collect($signatories)->where('form', 'report_card')->values();
            }else{
                $signatories = collect($signatories)->where('form', $request->get('formid'))->values();
            }
        }
        return view('signatories.signatories')
            ->with('formid', $request->get('formid'))
            ->with('syid', $request->get('syid'))
            ->with('acadprogid', $request->get('acadprogid'))
            ->with('levelid', $request->get('levelid'))
            ->with('signatories', $signatories);
    }
    public function savechanges(Request $request)
    {
        $formid = $request->get('formid');
        $syid = $request->get('syid');
        $acadprogid = $request->get('acadprogid');
        $levelid = $request->get('levelid');

        $dataid = $request->get('dataid');
        $title = $request->get('title');
        $name = $request->get('name');
        $label = $request->get('label');
        // $signatories = json_decode($request->get('signatories'));
        
        if($formid == 'form9')
        {
            $formid = 'report_card';
        }
        if($dataid == 0)
        {
            $id = DB::table('signatory')
                ->insertgetId([
                    'form'              => $formid,
                    'name'              => $name,
                    'title'             => $title,
                    'description'       => $label,
                    'syid'              => $syid,
                    'acadprogid'        => $acadprogid,
                    'levelid'           => $levelid,
                    'deleted'           => 0,
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);

        }else{
            $id = $dataid;
            DB::table('signatory')
                ->where('id', $dataid)
                ->update([
                    'name'              => $name,
                    'title'             => $title,
                    'description'       => $label,
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);
    
        }
        return $id;
        // if(count($signatories) > 0)
        // {
        //     foreach($signatories as $signatory)
        //     {
        //         $checkifexisits = DB::table('signatory')
        //             ->where('title', $signatory->title)
        //             ->where('deleted','0')
        //             ->first();
                
        //         if($checkifexisits)
        //         {
        //             DB::table('signatory')
        //                 ->where('id', $signatory->id)
        //                 ->update([
        //                     'name'              => $signatory->name,
        //                     'title'             => $signatory->title,
        //                     'description'       => $signatory->description,
        //                     'updatedby'         => auth()->user()->id,
        //                     'updateddatetime'   => date('Y-m-d H:i:s')
        //                 ]);


        //         }else{
        //             if($signatory->id == 0)
        //             {
        //                 DB::table('signatory')
        //                     ->insert([
        //                         'form'              => $formid,
        //                         'name'              => $signatory->name,
        //                         'title'             => $signatory->title,
        //                         'description'       => $signatory->description,
        //                         'syid'              => $syid,
        //                         'acadprogid'        => $acadprogid,
        //                         'levelid'           => $levelid,
        //                         'deleted'           => 0,
        //                         'createdby'         => auth()->user()->id,
        //                         'createddatetime'   => date('Y-m-d H:i:s')
        //                     ]);
        //             }else{
        //                 DB::table('signatory')
        //                     ->where('id', $signatory->id)
        //                     ->update([
        //                         'name'              => $signatory->name,
        //                         'title'             => $signatory->title,
        //                         'description'       => $signatory->description,
        //                         'updatedby'         => auth()->user()->id,
        //                         'updateddatetime'   => date('Y-m-d H:i:s')
        //                     ]);
    
        //             }
        //         }
        //     }
        // }
        // return 1;
    }
    public function deletesignatory(Request $request)
    {
        DB::table('signatory')
            ->where('id', $request->get('id'))
            ->update([
                'deleted'           => 1,
                'deletedby'         => auth()->user()->id,
                'deleteddatetime'   => date('Y-m-d H:i:s')
            ]);

        return 1;
    }
}
