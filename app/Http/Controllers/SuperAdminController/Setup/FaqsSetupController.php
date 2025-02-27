<?php

namespace App\Http\Controllers\SuperAdminController\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use TCPDF;
use Illuminate\Support\Facades\Validator;
class FaqsSetupController extends Controller
{
    public function generatepdf(Request $request)
    {   
       
    }


    public function loadfaqs(Request $request){
        $usertype = $request->get('usertype');
        $filetype = $request->get('filetype');
        $portals = $request->get('portals');

        // return $portals;
        
        $data = DB::table('userguide_detail')   
            
            ->join('userguide', 'userguide_detail.descriptionid', '=', 'userguide.id')
            // ->where('userguide_detail.utype', $usertype)
            ->whereIn('userguide_detail.utype', $portals)
            ->where('userguide.filetype', $filetype)
            ->where('userguide_detail.deleted', 0)
            ->get();

        return $data;
    }

    public function loadusersmanual(Request $request){
        $usertype = $request->get('usertype');
        $filetype = $request->get('filetype');
        $portals = $request->get('portals');

        $data = DB::table('userguide_detail')
            ->join('userguide', 'userguide_detail.descriptionid', '=', 'userguide.id')
            // ->where('userguide_detail.utype', $usertype)
            ->whereIn('userguide_detail.utype', $portals)
            ->where('userguide.filetype', $filetype)
            ->where('userguide_detail.deleted', 0)
            ->get();

        return $data;
    }
    // function ni nga gi execute pag click sa create button (didto sa "Add Topic / Description" nga modal)
    public function createfaq(Request $request){
        $topdesc = $request->get('topdesc');
        $ftype = $request->get('ftype');



        $localfolder = 'faqsv2';
        // return $localfolder;
        $file = $request->file('file');
    
        $filename = $file->getClientOriginalName();

        $extension = $file->getClientOriginalExtension();

        
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

            $file->move($destinationPath,$newname);

        }
        catch(\Exception $e){
            // file_put_contents($destinationPath, $filename);
    
        }

        // if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder)) {

        //     $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/'.$localfolder;
            
        //     if(!File::isDirectory($cloudpath)){

        //         File::makeDirectory($cloudpath, 0777, true, true);
                
        //     }
            
        // }

        $ifexist = DB::table('userguide')
            ->where('description', $topdesc)
            ->where('filetype', $ftype)
            ->where('deleted', 0)
            ->count();

        

        if ( $ifexist) {
            return array((object)[
                'status'=>0,
                'message'=>'Already Exist',
            ]);
        } else {
            $data = DB::table('userguide')
                ->insert([
                    'description' => $topdesc,
                    'filetype' => $ftype,
                    'filepath' =>  $localfolder.'/'.$newname,
                    'created_at' => \Carbon\Carbon::now('Asia/Manila')
                ]);

            return array((object)[
                'status'=>1,
                'message'=>'successfully created',
            ]);
        }
        // <td width="60%" style="text-align: center; font-size: 12px; border-bottom: 1px solid #000; "><b>SR. MA. MINDA D. DERILO, MCST</b></td>
        
        // $usersid = [];
        // foreach ($userid as $uid) {
        //     $usersid[] = ['userid' => $uid, 'description' => 'masaya'];
        // }
        // $data = DB::table('userguide')->insert($usersid);
        // return response()->json($data);
    }

    public function update_details(Request $request){
        $userguideid = $request->get('userguideid');
        $description = $request->get('description');
        $filetype = $request->get('filetype');
        $file = $request->file('file');
        // return $userguideid;
        // return $filetype;

        $localfolder = 'faqsv2';
        // return $localfolder;
        

        if ($file == null || $file == '') {

            
            $ifexistdescrip = DB::table('userguide')
                ->where('description', $description)
                ->where('id', '!=', $userguideid)
                ->where('deleted', 0)
                ->count();

            if ($ifexistdescrip) {
                return array((object)[
                    'status'=>0,
                    'message'=>'Description Already Exist!',
                ]);
            } else {
                $data = DB::table('userguide')
                    ->where('id', $userguideid)
                    ->where('filetype', $filetype)
                    ->where('deleted', 0)
                    ->update([
                        'description' => $description,
                        'updated_at' => \Carbon\Carbon::now('Asia/Manila')
                    ]);

                return array((object)[
                    'status'=>1,
                    'message'=>'Topic Description updated Successfully!',
                ]);
            }
            
        } else {
            // $filename = $file->getClientOriginalName();
            $filename =  date('d-m-y-H-i-s') . '@' . $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            
            $ifexistdescrip = DB::table('userguide')
                ->where('description', $description)
                ->where('id', '!=', $userguideid)
                ->where('deleted', 0)
                ->count();

            if ($ifexistdescrip) {
                return array((object)[
                    'status'=>0,
                    'message'=>'Description Already Exist!',
                ]);
            } else {
                $data = DB::table('userguide')
                        ->where('id', $userguideid)
                        ->where('filetype', $filetype)
                        ->where('deleted', 0)
                        ->update([
                            'description' => $description,
                            'filepath' => $localfolder.'/'.$filename,
                            'updated_at' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                    if ($data) {
                        $request->file->move(public_path($localfolder), $filename);
                    }

                    return array((object)[
                        'status'=>1,
                        'message'=>'Topic Description updated Successfully!',
                    ]);
            }
            
        }


        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 400);
        // } else {

        //     // $filename = $file->getClientOriginalName();
        //     $filename =  date('d-m-y-H-i-s') . '@' . $file->getClientOriginalName();
        //     $extension = $file->getClientOriginalExtension();

        //     $ifexistdesc = DB::table('userguide')
        //         ->where('description', $description)
        //         ->where('deleted', 0)
        //         ->count();
    
            
        //     if ($ifexistdesc) {
                
        //         $ifexistuserdesc = DB::table('userguide')
        //             ->where('id', $userguideid)
        //             ->where('description', $description)
        //             ->where('deleted', 0)
        //             ->count();
    
        //         if ($ifexistuserdesc) {
                    
                    

        //             $data = DB::table('userguide')
        //                 ->where('id', $userguideid)
        //                 ->where('filetype', $filetype)
        //                 ->where('deleted', 0)
        //                 ->update([
        //                     'description' => $description,
        //                     'filepath' => $localfolder.'/'.$filename,
        //                     'updated_at' => \Carbon\Carbon::now('Asia/Manila')
        //                 ]);

        //             if ($data) {
        //                 $request->file->move(public_path($localfolder), $filename);
        //             }

        //             return array((object)[
        //                 'status'=>1,
        //                 'message'=>'Topic Description updated Successfully!',
        //             ]);


        //         } else {
    
        //             $ifexisttouser = DB::table('userguide')
        //                 ->where('id','!=', $userguideid)
        //                 ->where('description', $description)
        //                 ->where('deleted', 0)
        //                 ->count();
    
        //             if ($ifexisttouser) {
        //                 return array((object)[
        //                     'status'=>0,
        //                     'message'=>'Description Already Exist!',
        //                 ]);
        //             }
    
        //         }
    
        //     }

        // }

        
    }

    public function loadtopdesc(Request $request){
        $topicdesc = DB::table('userguide')
            ->where('deleted', 0)
            ->get();

        return $topicdesc;
    }

    public function assignfaqs(Request $request){
        $utype = $request->get('utypeid');
        $descriptionid = $request->get('descriptionid');

        $faqsexist = DB::table('userguide_detail')
            ->where('utype', $utype)
            ->where('descriptionid', $descriptionid)
            ->count();

        if ($faqsexist) {
            $faqsexistt = DB::table('userguide_detail')
            ->where('utype', $utype)
            ->where('descriptionid', $descriptionid)
            ->get();

            if ($faqsexistt[0]->deleted == 1) {
                $data = DB::table('userguide_detail')
                ->where('utype', $utype)
                ->where('descriptionid', $descriptionid)
                ->where('deleted', 1)
                ->update([
                    'deleted' => 0,
                    'created_at' => \Carbon\Carbon::now('Asia/Manila')
                ]);
        
                return array((object)[
                    'status'=>1,
                    'message'=>'FAQs Assigned Successfully!',
                ]);
            }  else {
                $faqsexistt = DB::table('userguide_detail')
                    ->where('utype', $utype)
                    ->where('descriptionid', $descriptionid)
                    ->where('deleted', 0)
                    ->count();

                if ($faqsexistt) {

                    return array((object)[
                        'status' => 0,
                        'message'=>'Already Assigned!',
                    ]);
                } else {

                    $data = DB::table('userguide_detail')
                    ->insert([
                        'utype' => $utype,
                        'descriptionid' => $descriptionid,
                        'created_at' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
            
                    return array((object)[
                        'status'=>1,
                        'message'=>'FAQs Assigned Successfully!',
                    ]);
                }
            }
        } else {
            $data = DB::table('userguide_detail')
            ->insert([
                'utype' => $utype,
                'descriptionid' => $descriptionid,
                'created_at' => \Carbon\Carbon::now('Asia/Manila')
            ]);
    
            return array((object)[
                'status'=>1,
                'message'=>'FAQs Assigned Successfully!',
            ]);
        }

    }

    public function loadassignfaqs(Request $request){
        $utype = $request->get('utypeid');

        $topdescdetail = DB::table('userguide_detail')
            ->select(
                'userguide.description',
                'userguide_detail.descriptionid',
                'userguide.filetype',
                'userguide.filepath'
                
            )
            ->join('userguide', 'userguide_detail.descriptionid', '=', 'userguide.id')
            ->where('userguide_detail.utype', $utype)
            ->where('userguide_detail.deleted', 0)
            ->where('userguide.filetype', 1)
            ->get();
        
        return $topdescdetail;
    }

    public function loadassignmanual(Request $request){
        $utype = $request->get('utypeid');

        $topdescdetail = DB::table('userguide_detail')
            ->select(
                'userguide.description',
                'userguide_detail.descriptionid',
                'userguide.filetype',
                'userguide.filepath'
                
            )
            ->join('userguide', 'userguide_detail.descriptionid', '=', 'userguide.id')
            ->where('userguide_detail.utype', $utype)
            ->where('userguide_detail.deleted', 0)
            ->where('userguide.filetype', 2)
            ->get();
        
        return $topdescdetail;
    }

    public function loadmanuals(Request $request){
        $loadmanuals = DB::table('userguide')
            ->where('filetype', 2)
            ->where('deleted', 0)
            ->get();
        
        return $loadmanuals;
    }

    public function assignmanual(Request $request){
        $utype = $request->get('utypeid');
        $descriptionid = $request->get('descriptionid');

        $manualexist = DB::table('userguide_detail')
            ->where('utype', $utype)
            ->where('descriptionid', $descriptionid)
            ->where('deleted', 0)
            ->count();

        if ($manualexist) {
            return array((object)[
                'status' => 0,
                'message'=>'Already Assigned!',
            ]);
        } else {

            $data = DB::table('userguide_detail')
            ->insert([
                'utype' => $utype,
                'descriptionid' => $descriptionid,
                'created_at' => \Carbon\Carbon::now('Asia/Manila')
            ]);

            return array((object)[
                'status'=>1,
                'message'=>'FAQs Assigned Successfully!',
            ]);
        }

        // return array((object)[
        //     'status'=>1,
        //     'message'=>'successfully created',
        // ]);
    }

    // assign manualv2 modal 2
    public function assignmanualv2(Request $request){
        $manualid = $request->get('manualid');
        $usertypeid = $request->get('usertypeid');

        $manualexist = DB::table('userguide_detail')
        ->where('utype', $usertypeid)
        ->where('descriptionid', $manualid)
        ->count();

        if ($manualexist) {
            // ========================

            $manualexist = DB::table('userguide_detail')
            ->where('utype', $usertypeid)
            ->where('descriptionid', $manualid)
            ->get();
        
            if ($manualexist[0]->deleted == 1) {
                $data = DB::table('userguide_detail')
                ->where('utype', $usertypeid)
                ->where('descriptionid', $manualid)
                ->where('deleted', 1)
                ->update([
                    'deleted' => 0,
                    'created_at' => \Carbon\Carbon::now('Asia/Manila')
                ]);
        
                return array((object)[
                    'status'=>1,
                    'message'=>'Manual Assigned Successfully!',
                ]);
            } else {
                $manualexist = DB::table('userguide_detail')
                    ->where('utype', $usertypeid)
                    ->where('descriptionid', $manualid)
                    ->where('deleted', 0)
                    ->count();

                if ($manualexist) {

                    return array((object)[
                        'status' => 0,
                        'message'=>'Already Assigned!',
                    ]);
                } else {

                    $data = DB::table('userguide_detail')
                    ->insert([
                        'utype' => $usertypeid,
                        'descriptionid' => $manualid,
                        'created_at' => \Carbon\Carbon::now('Asia/Manila')
                    ]);
            
                    return array((object)[
                        'status'=>1,
                        'message'=>'Manual Assigned Successfully!',
                    ]);
                }
            }

            // ========================
        } else {
            $data = DB::table('userguide_detail')
                ->insert([
                    'utype' => $usertypeid,
                    'descriptionid' => $manualid,
                    'created_at' => \Carbon\Carbon::now('Asia/Manila')
                ]);
        
                return array((object)[
                    'status'=>1,
                    'message'=>'Manual Assigned Successfully!',
                ]);
        }

        
    }

    public function loaduserguidedetail(Request $request){

        $loaduserguidedetail = DB::table('userguide_detail')
            // ->select(
            //     'userguide.description',
            //     'userguide.filetype'
                
            // )
            ->join('userguide', 'userguide_detail.descriptionid', '=', 'userguide.id')
            ->where('userguide_detail.deleted', 0)
            ->get();
        
        return $loaduserguidedetail;
    }

    public function loadusertype(Request $request){
        $data = DB::table('usertype')
            // ->select(
            //     'usertype.id',
            //     'usertype.utype as usertype_desc',
            //     'userguide_detail.utype',
            //     'userguide.filetype'

            //     )
            // ->leftJoin('userguide_detail', 'usertype.id', '=', 'userguide_detail.utype')
            // ->leftJoin('userguide', 'userguide_detail.descriptionid', '=', 'userguide.id')
            ->where('usertype.deleted', 0)
            ->orderBy('usertype.id')
            ->get();

        return $data;
    }

    // delete Topic Description in Table 1
    public function deletetopdesc(Request $request){
        $topdescid = $request->get('topdescid');
        

        $topdesccomexist = DB::table('userguide_detail')
            ->where('descriptionid', $topdescid)
            ->count();

        if ($topdesccomexist) {
            
            $topdesccomexist = DB::table('userguide_detail')
            ->where('descriptionid', $topdescid)
            ->get();
            
            if ($topdesccomexist[0]->deleted == 1) {
                
                $data = DB::table('userguide')
                ->where('id', $topdescid)
                ->update([
                    'deleted' => 1,
                    'deleted_at' => \Carbon\Carbon::now('Asia/Manila')
                ]);

                return array((object)[
                    'status'=>1,
                    'message'=>'Topic Description Deleted!',
                ]);
            } else {

                return array((object)[
                    'status' => 0,
                    'message'=>'Already Assigned!',
                ]);
            }

        } else{
            $data = DB::table('userguide')
            ->where('id', $topdescid)
            ->update([
                'deleted' => 1,
                'deleted_at' => \Carbon\Carbon::now('Asia/Manila')
            ]);

            return array((object)[
                'status'=>1,
                'message'=>'Topic Description Deleted!',
            ]);
        }
    }


    // delete manualv2 modal 1

    function deletemanualv2(Request $request){
        $userid = $request->get('usertypeid');
        $descid = $request->get('descriptionid');

        $data = DB::table('userguide_detail')
            ->where('descriptionid', $descid)
            ->where('utype', $userid)
            ->update([
                'deleted' => 1,
                'deleted_at' => \Carbon\Carbon::now('Asia/Manila')
            ]);

        return array((object)[
            'status'=>1,
            'message'=>'Manual Successfully Deleted!'
        ]);
        
    }

    // delete manualv2 modal 1

    function deletefaqs(Request $request){
        $userid = $request->get('utypeid');
        $descid = $request->get('descriptionid');

        $data = DB::table('userguide_detail')
            ->where('descriptionid', $descid)
            ->where('utype', $userid)
            ->update([
                'deleted' => 1,
                'deleted_at' => \Carbon\Carbon::now('Asia/Manila')
            ]);

        return array((object)[
            'status'=>1,
            'message'=>'FAQs Successfully Deleted!'
        ]);
        
    }
    
}
