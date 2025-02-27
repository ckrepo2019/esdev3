<?php

namespace App\Http\Controllers\ClinicControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\SchoolClinic\SchoolClinic;
class MedicineController extends Controller
{
    public function index()
    {

        return view('clinic.medicine.index');
    }
    public function add(Request $request)
    {
        // return $request->all();
        $checkifexists = DB::table('clinic_medicines')
            ->where('brandname','like','%'.$request->get('brandname').'%')
            ->where('genericname','like','%'.$request->get('genericname').'%')
            ->where('deleted','0')
            ->first();

        if($checkifexists)
        {
            return 0;
        }else{
            DB::table('clinic_medicines')
                ->insert([
                    'brandname'         => $request->get('brandname'),
                    'genericname'       => $request->get('genericname'),
                    'dosage'            => $request->get('dosage'),
                    'quantity'          => $request->get('quantity'),
                    'expirydate'        => $request->get('expirydate'),
                    'description'       => $request->get('description'),
                    'createdby'         => auth()->user()->id,
                    'createddatetime'   => date('Y-m-d H:i:s')
                ]);
            return 1;
        }
    }
    public function showmedicines()
    {
        $medicines = SchoolClinic::drugs();
        return view('clinic.medicine.result_meds')
            ->with('medicines',$medicines);
    }
    public function delete(Request $request)
    {
        try{
            DB::table('clinic_medicines')
                ->where('id', $request->get('id'))
                ->update([
                    'deleted'           => 1,
                    'deletedby'         => auth()->user()->id,
                    'deleteddatetime'   => date('Y-m-d H:i:s')
                ]);

            return 1;
        }catch(\Exception $error)
        {   
            return $error;
        }
    }
    public function getmedinfo(Request $request)
    {
        $info = DB::table('clinic_medicines')
            ->where('id', $request->get('id'))
            ->first();

        return collect($info);
    }
    public function edit(Request $request)
    {
        try{
            DB::table('clinic_medicines')
                ->where('id', $request->get('id'))
                ->update([
                    'brandname'         => $request->get('brandname'),
                    'genericname'       => $request->get('genericname'),
                    'dosage'            => $request->get('dosage'),
                    'quantity'          => $request->get('quantity'),
                    'expirydate'        => $request->get('expirydate'),
                    'description'       => $request->get('description'),
                    'updatedby'         => auth()->user()->id,
                    'updateddatetime'   => date('Y-m-d H:i:s')
                ]);

            return 1;
        }catch(\Exception $error)
        {   
            return $error;
        }
    }
}
