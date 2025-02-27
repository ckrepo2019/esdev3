<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use File;
class MyDocumentsController extends Controller
{
    public function index(){
        
        if(auth()->user()->type == '1' ||  Session::get('currentPortal') == 1){

            $extends = "teacher.layouts.app";
            
        }elseif(auth()->user()->type == '2' ||  Session::get('currentPortal') == 2){

            $extends = "principalsportal.layouts.app2";

        }elseif(auth()->user()->type == '3' || auth()->user()->type == '8'  ||  Session::get('currentPortal') == 3  ||  Session::get('currentPortal') == 8){

            $extends = "registrar.layouts.app";

        }elseif(auth()->user()->type == '4' || auth()->user()->type == '15' ||  Session::get('currentPortal') == 4  ||  Session::get('currentPortal') == 15){

            $extends = "finance.layouts.app";

        }elseif(auth()->user()->type == '6' ||  Session::get('currentPortal') == 6){

            $extends = "adminPortal.layouts.app2";

        }elseif(auth()->user()->type == '10' || Session::get('currentPortal') == 10){

            $extends = "hr.layouts.app";

        }elseif(auth()->user()->type == '12' ||  Session::get('currentPortal') == 12){

            $extends = "adminITPortal.layouts.app";

        }elseif(auth()->user()->type == '14' ||  Session::get('currentPortal') == 14){

            $extends = "deanportal.layouts.app2";

        }elseif(auth()->user()->type == '16' ||  Session::get('currentPortal') == 16){

            $extends = "chairpersonportal.layouts.app2";

        }elseif(auth()->user()->type == '18' ||  Session::get('currentPortal') == 18){

            $extends = "ctportal.layouts.app2";

        }else{

            $extends = "general.defaultportal.layouts.app";

        }
            
        $myfolders = DB::table('employee_folders')
            ->select('id','foldername','folderabbrv','color','visible','createddatetime','updateddatetime')
            ->where('userid', auth()->user()->id)
            ->where('deleted','0')
            ->get();

        if(count($myfolders)>0)
        {
            foreach($myfolders as $folder)
            {
                $folder->filescount = DB::table('employee_folderdetail')
                        ->where('headerid', $folder->id)
                        ->where('deleted','0')
                        ->count();
            }
        }

        $sharedfolders = DB::table('employee_folders')
            ->select('employee_folders.*','teacher.lastname','teacher.firstname')
            ->leftJoin('teacher','employee_folders.createdby','=','teacher.userid')
            ->where('employee_folders.visible','1')
            ->where('employee_folders.createdby','!=',auth()->user()->id)
            ->where('employee_folders.deleted','0')
            ->orderBy('employee_folders.createddatetime','desc')
            ->get();

        $customsharedfolders = DB::table('employee_folderaudience')
            ->select('employee_folders.*','teacher.lastname','teacher.firstname')
            ->join('employee_folders','employee_folderaudience.folderid','=','employee_folders.id')
            ->leftJoin('teacher','employee_folders.createdby','=','teacher.userid')
            ->where('employee_folderaudience.userid', auth()->user()->id)
            ->where('employee_folderaudience.deleted','0')
            ->where('employee_folders.deleted','0')
            ->where('employee_folderaudience.docutype','0')
            ->get();

        $sharedfolders = collect($sharedfolders)->merge($customsharedfolders);
        $sharedfolders = collect($sharedfolders)->unique('id');
        
        $sharedfiles = DB::table('employee_folderdetail')
            ->select('employee_folderdetail.*','teacher.lastname','teacher.firstname')
            ->leftJoin('employee_folders','employee_folderdetail.headerid','=','employee_folders.id')
            ->leftJoin('teacher','employee_folderdetail.createdby','=','teacher.userid')
            ->where('employee_folders.visible','0')
            ->where('employee_folders.createdby','!=',auth()->user()->id)
            ->where('employee_folderdetail.deleted','0')
            ->where('employee_folderdetail.visible','1')
            ->orderBy('employee_folders.createddatetime','desc')
            ->get();
            
        return view('general.mydocuments.index')
            ->with('myfolders', $myfolders)
            ->with('sharedfolders', $sharedfolders)
            ->with('sharedfiles', $sharedfiles)
            ->with('extends', $extends);
    }
    public function sharedfoldergetfiles(Request $request)
    {
        $files = DB::table('employee_folderdetail')
            ->where('headerid', $request->get('folderid'))
            ->where('deleted','0')
            ->where('visible','!=','0')
            ->get();

        return view('general.mydocuments.sharedgetfiles')
            ->with('files', $files);
    }
    public function createfolder(Request $request)
    {
        // return $request->all();
        $foldername     = $request->get('name');
        $folderabbrv    = $request->get('abbrv');
        $color          = $request->get('color');
        $visible        = $request->get('audience');

        $checkifexists = DB::table('employee_folders')
            ->where('userid', auth()->user()->id)
            ->where('foldername', $foldername)
            ->where('deleted','0')
            ->count();

        if($checkifexists == 0)
        {
            try{
                $folderid = DB::table('employee_folders')
                    ->insertGetId([
                        'userid'            => auth()->user()->id,
                        'foldername'        => $foldername,
                        'folderabbrv'       => $folderabbrv,
                        'color'             => $color,
                        'visible'           => $visible,
                        'createdby'         => auth()->user()->id,
                        'createddatetime'   => date('Y-m-d H:i:s')
                    ]);

                $folderinfo = DB::table('employee_folders')
                        ->where('id', $folderid)
                        ->first();
                        
                return collect((object)array(
                    'status'    => 1,
                    'info'      => collect($folderinfo),
                ));
            }catch(\Exception $error)
            {
                        
                return collect([
                    'status'    => 'error',
                ]);
            }

        }else{ 
            return collect([
                'status'    => '0',
            ]);
        }
    }
    public function folderedit(Request $request)
    {
        if(!$request->has('visible'))
        {
            if($request->has('selectedusers'))
            {
                
                DB::table('employee_folders')
                    ->where('id', $request->get('folderid'))
                    ->update([
                        'visible'           => 2,
                        'downloadable'      => $request->get('candownload'),
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);

                $selectedusers = json_decode($request->get('selectedusers'));
                
                $getaudiences = DB::table('employee_folderaudience')
                    ->where('folderid', $request->get('folderid'))
                    ->where('deleted','0')
                    ->get();
                    
                if(count($getaudiences)>0)
                {
                    foreach($getaudiences as $aud)
                    {
                        if(!in_array($aud->userid, $selectedusers))
                        {
                            DB::table('employee_folderaudience')
                                ->where('id', $aud->id)
                                ->update([
                                    'deleted'           => 1,
                                    'deletedby'         => auth()->user()->id,
                                    'deleteddatetime'   => date('Y-m-d H:i:s')
                                ]);
                        }
                    }
                }
                
                foreach($selectedusers as $selecteduser)
                {
                    $countcheckifexists = DB::table('employee_folderaudience')
                        ->where('folderid', $request->get('folderid'))
                        ->where('userid', $selecteduser)
                        ->where('deleted','0')
                        ->count();
                        
                    
                    if($countcheckifexists == 0)
                    {
                        DB::table('employee_folderaudience')
                            ->where('#')
                            ->insert([
                                'folderid'        => $request->get('folderid'),
                                'download'        => $request->get('candownload'),
                                'userid'          => $selecteduser,
                                'docutype'        => 0,
                                'createdby'       => auth()->user()->id,
                                'createddatetime' => date('Y-m-d H:i:s')
                            ]);
                    }else{
                        DB::table('employee_folderaudience')
                            ->where('folderid', $request->get('folderid'))
                            ->where('userid', $selecteduser)
                            ->where('deleted','0')
                            ->update([
                                'download'          => $request->get('candownload'),
                                'updatedby'         => auth()->user()->id,
                                'updateddatetime'   => date('Y-m-d H:i:s')
                            ]);
                    }
                }
                return 1;

            }else{
                try{
                    DB::table('employee_folders')
                        ->where('id', $request->get('folderid'))
                        ->update([
                            'foldername'        => $request->get('foldername'),
                            'folderabbrv'        => $request->get('folderabbr'),
                            'color'             => $request->get('foldercolor'),
                            'updatedby'         => auth()->user()->id,
                            'updateddatetime'   => date('Y-m-d H:i:s')
                        ]);
                    return 1;
                }catch(\Exception $error)
                {
                    return 0;
                }
            }
        }else{
            try{
                DB::table('employee_folders')
                    ->where('id', $request->get('folderid'))
                    ->update([
                        'visible'           => $request->get('visible'),
                        'updatedby'         => auth()->user()->id,
                        'updateddatetime'   => date('Y-m-d H:i:s')
                    ]);
                return 1;
            }catch(\Exception $error)
            {
                return 0;
            }
        }
    }
    public function folderdelete(Request $request)
    {
        DB::table('employee_folders')
            ->where('id', $request->get('folderid'))
            ->update([
                'deleted'           => 1,
                'deletedby'         => auth()->user()->id,
                'deleteddatetime'   => date('Y-m-d H:i:s')
            ]);
    }
    public function filesindex(Request $request)
    {
        $folderinfo = DB::table('employee_folders')
            ->where('id', $request->get('folderid'))
            ->first();

        $files = DB::table('employee_folderdetail')
            ->where('headerid', $request->get('folderid'))
            ->where('deleted','0')
            ->get();
            
        $users = DB::table('teacher')
            ->select('userid','lastname','firstname')
            ->where('deleted','0')
            ->where('isactive','1')
            ->where('userid','!=', auth()->user()->id)
            ->get();

        if(count($users)>0)
        {
            foreach($users as $user)
            {
                $user->selected = 0;

                $customaudience = DB::table('employee_folderaudience')
                    ->where('folderid', $folderinfo->id)
                    ->where('userid', $user->userid)
                    ->where('deleted','0')
                    ->count();
                if($customaudience > 0)
                {
                    $user->selected = 1;
                }
            }
        }
        
        return view('general.mydocuments.folderview')
            ->with('folderinfo', $folderinfo)
            ->with('files', $files)
            ->with('users', $users)
            ->with('extends',  $request->get('extends'));
    }
    public function uploadfiles(Request $request)
    {
        $folderinfo = DB::table('schoolfolder')
            ->where('id', $request->get('folderid'))
            ->first();

        $userlastname = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first();

        if($userlastname)
        {
            $lastname = str_replace(' ', '_', $userlastname->lastname);
        }else
        {            
            $lastname = '';
        }
        
        $localfolder = 'MyDocuments/'.auth()->user()->email.'/'.$folderinfo->id;
        
        $file = $request->file('file');
    
        $filename = $file->getClientOriginalName();

        $extension = $file->getClientOriginalExtension();

        if (! File::exists(public_path().$localfolder)) {

            $path = public_path($localfolder);

            if(!File::isDirectory($path)){
                
                File::makeDirectory($path, 0777, true, true);

            }
            
        }
        if (strpos($request->root(),'http://') !== false) {
            $urlFolder = str_replace('http://','',$request->root());
        } else {
            $urlFolder = str_replace('https://','',$request->root());
        }
            
        if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder)) {

            $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder;
            
            if(!File::isDirectory($cloudpath)){

                File::makeDirectory($cloudpath, 0777, true, true);
                
            }
            
        }
        

        $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder;
        try{

            $file->move($clouddestinationPath, $filename);
        }
        catch(\Exception $e){
            
    
        }
        
        $destinationPath = public_path($localfolder.'/');
        
        try{

            $file->move($destinationPath,$filename);

        }
        catch(\Exception $e){
            
    
        }

        DB::table('employee_folderdetail')
            ->insert([
                'headerid'          => $request->get('folderid'),
                'filename'          => $filename,
                'filepath'          => $localfolder.'/'.$filename,
                'extension'         => $extension,
                'createdby'         => auth()->user()->id,
                'createddatetime'   => date('Y-m-d H:i:s')
            ]);
    }
    public function fileview(Request $request)
    {
        $fileinfo = DB::table('employee_folderdetail')
            ->where('id', $request->get('fileid'))
            ->first();

        return view('general.mydocuments.fileview')
            ->with('fileinfo', $fileinfo);
    }
    public function fileedit(Request $request)
    {
        DB::table('employee_folderdetail')
            ->where('id', $request->get('fileid'))
            ->update([
                'visible'           => $request->get('visible'),
                'updatedby'         => auth()->user()->id,
                'updateddatetime'   => date('Y-m-d H:i:s')
            ]);
    }
    public function filedelete(Request $request)
    {
        DB::table('employee_folderdetail')
            ->where('id', $request->get('fileid'))
            ->update([
                'deleted'           => 1,
                'deletedby'         => auth()->user()->id,
                'deleteddatetime'   => date('Y-m-d H:i:s')
            ]);
    }
}
