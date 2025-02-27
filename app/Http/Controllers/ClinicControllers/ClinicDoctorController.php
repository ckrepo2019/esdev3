<?php

namespace App\Http\Controllers\ClinicControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use File;
use Image;

class ClinicDoctorController extends Controller
{

public function index()
    {

        // $personnel = SchoolClinic::personnel();
        // return $personnel;
        $info = DB::table('clinic_doctorsinfo')
            ->where('docid',auth()->user()->id)
            ->first();

        return view('clinic_doctor.profile.index')
                ->with('info', $info);
    }

public function saveDoctor(Request $request)
{
    $name = $request->input('name');
    $degree = $request->input('degree');
    if (is_array($degree)) {
    $degree = implode(', ', $request->input('degree')); // convert array to comma-separated string
    } else {
    $degree = $request->input('degree');
    }
    
    $specialty = null;
    if ($request->has('specialty')) {
    $specialty = $request->input('specialty');
}
    
    $reg = $request->input('reg');
    $phone = $request->input('phone');
    $email = $request->input('email');
    $address = $request->input('address');
    $signature = null;
	$name = '';
    
    if ($request->hasFile('signature')) {
		
		
				$urlFolder = str_replace('http://','',$request->root());

                  if (! File::exists(public_path().'signatures')) {
                      $path = public_path('signatures');
                      if(!File::isDirectory($path)){
                          File::makeDirectory($path, 0777, true, true);
                      }
                  }
                  if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/signatures')) {
                      $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/signatures';
                      if(!File::isDirectory($cloudpath)){
                          File::makeDirectory($cloudpath, 0777, true, true);
                      }
                  }
				  
				$file = $request->file('signature');
				$name = $file->getClientOriginalName();
				$despath = $name;
				$destinationPath = public_path('signatures');
                $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/signatures';
				
				$file->move($destinationPath, $despath);
				  
		 $signature = '/signatures/'.$name;
		 
    }else{
		
		
        $signature = DB::table('clinic_doctorsinfo')
            ->select('signature')
            ->where('docid',auth()->user()->id)
            ->value('signature');
    }

    $data = [
        'degree' => $degree,
        'specialty' => $specialty,
        'regnum'  => $reg,
        'phone' => $phone,
        'email' => $email,
        'address' => $address,
        'signature' => $signature,
        'docid' => auth()->user()->id,
        'createddatetime'   => date('Y-m-d H:i:s'),
        'updatedatetime' => date('Y-m-d H:i:s')
    ];
    $check = DB::table('clinic_doctorsinfo')
    ->where('docid',auth()->user()->id)
    ->get();

    if(count($check)){
        DB::table('clinic_doctorsinfo')->where('docid',auth()->user()->id)->update($data);
    }
    else{
    DB::table('clinic_doctorsinfo')->insert($data);
    }

    return redirect()->back()->with('success', 'Doctor data saved successfully');
}






}