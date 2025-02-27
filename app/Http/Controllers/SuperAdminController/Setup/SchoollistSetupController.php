<?php

namespace App\Http\Controllers\SuperAdminController\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Facades\File;
class SchoollistSetupController extends Controller
{   

    public static function loadschools(Request $request){
        $allschools = DB::table('schoollist')
            ->where('deleted', 0)
            ->get();

        return $allschools;
    }

    public static function addschoolinfo(Request $request) {
        $schoolname = $request->get('schoolname');
        $abbr = $request->get('abbr');
        $eslink = $request->get('eslink');
        $db = $request->get('db');
        $schoolid = $request->get('schoolid');
        $file = $request->file('file');


        $localfolder = 'schoollist';

        $validator = Validator::make($request->all(), [
            'schoolname' => 'required', 
            'abbr' => 'required', 
            'eslink' => 'required', 
            'db' => 'required', 
            'schoolid' => 'required',
            'file' => 'required'
        ]);

        // $validatedData = $request->validate([
        //     'schoolname' => 'required', 
        //     'abbr' => 'required', 
        //     'eslink' => 'required', 
        //     'db' => 'required', 
        //     'file' => 'required'
        // ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } 

        $filename =  date('d-m-y-H-i-s') . '@' . $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        // $filenametime = time();
        // $ifexist = DB::table('schoollist')
        //     ->where('schoollogo', $localfolder.'/'.$filename)
        //     ->count();

        // return $filenametime;
        // if ($ifexist) {
        //     FileFacade::delete(public_path($localfolder . $filename));
        // }

        $ifexist = DB::table('schoollist')
            ->where('schoolname', $schoolname)
            // ->where('abbr', $abbr)
            ->where('deleted', 0)
            ->count();

        if ($ifexist) {

            return array((object)[
                'status'=> 0,
                'message'=> 'School Already Exist',
            ]);

        } else {

            $request->file->move(public_path($localfolder), $filename);

            $data = DB::table('schoollist')
            
                ->insert([
                    'schoolname' => $schoolname,
                    'abbr' => $abbr,
                    'schoolid' => $schoolid,
                    'eslink' => $eslink,
                    'schoollogo' => $localfolder.'/'.$filename,
                    'db' => $db
                ]);
            
            return array((object)[
                'status'=>1,
                'message'=>'School Added Successfully',
            ]);

        }

        
    }


    public function updatelogo(Request $request){
        $logoid = $request->get('logoid');
        $imagefile = $request->file('image_logo');
        
        $localfolder = 'schoollist';

        $validator = Validator::make($request->all(), [
            'image_logo' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } 

       
        $filename =  date('d-m-y-H-i-s') . '@' . $imagefile->getClientOriginalName();
        $extension = $imagefile->getClientOriginalExtension();
        
         // return public_path().'/'.$localfolder;
         if (! File::exists(public_path().'/'.$localfolder)) {
        
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
        
        $destinationPath = public_path($localfolder.'/');

        $newname = pathinfo($filename,PATHINFO_FILENAME).'.'.$extension;

        // return $newname;
        try{

            $imagefile->move($destinationPath,$newname);

        }
        catch(\Exception $e){
            // file_put_contents($destinationPath, $filename);
    
        }


        $ifexist = DB::table('schoollist')
            ->where('id', $logoid)
            ->count();

        if ($ifexist) {
            
            $data = DB::table('schoollist')
                ->where('id', $logoid)
                ->update([
                    'schoollogo' => $localfolder.'/'.$newname
                ]);
            
            // if ($data) {
            //     $request->file->move(public_path($localfolder), $filename);
            // }
            return array((object)[
                'status'=>1,
                'message'=>'Logo Updated Successfully',
            ]);

        } else {
            return array((object)[
                'status'=>0,
                'message'=>'Failed',
            ]);
        }

        
        
        
        
        
    }

    public static function delete_school(Request $request){
        $schoolid = $request->get('schooldata_id');
        
        $data = DB::table('schoollist')
            ->where('id', $schoolid)
            ->where('deleted', 0)
            ->update([
                'deleted' => 1,
                'deleted_at' => \Carbon\Carbon::now('Asia/Manila')
            ]);

        return array((object)[
            'status'=>1,
            'message'=>'School Deleted Successfully',
        ]);
    }

    public static function edit_school(Request $request){
        $schooldataid = $request->get('schooldata_id');

        $data = DB::table('schoollist')
            ->where('id', $schooldataid)
            ->where('deleted', 0)
            ->get();

        return $data;
    }

    public static function update_schoolinfo(Request $request){
        $id = $request->get('idschool');
        $schoolname = $request->get('schoolname');
        $abbr = $request->get('abbr');
        $eslink = $request->get('eslink');
        $db = $request->get('db');
        $schoolid = $request->get('schoolid');

        $ifexist = DB::table('schoollist')
            ->where('schoolname', $schoolname)
            ->where('id', '!=', $id)
            ->count();

        if ($ifexist) {
            return array((object)[
                'status'=>0,
                'message'=>'School Already Exist',
            ]);
        } else {
            $data = DB::table('schoollist')
                ->where('id', $id)
                ->where('deleted', 0)
                ->update([
                    'schoolname' => $schoolname,
                    'abbr' => $abbr,
                    'schoolid' => $schoolid,
                    'eslink' => $eslink,
                    'db' => $db,
                    'updated_at' => \Carbon\Carbon::now('Asia/Manila'),
            ]);

            return array((object)[
                'status'=>1,
                'message'=>'School Updated Successfully',
            ]);
        }
    }

}
