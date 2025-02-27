<?php

namespace App\Http\Controllers\AdministratorControllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Principal\SPP_SchoolYear;
use App\Models\Principal\SPP_Teacher;
use Illuminate\Support\Facades\Validator;
use File;
use Image;

class AdministratorController extends \App\Http\Controllers\Controller
{

    public function manageaccounts()
    {

        $activesy = SPP_SchoolYear::getActiveSchoolyear();

        if (!isset($activesy)) {
            return view('generalPages.adminErrorBlade')
                ->with('message', 'No active school year.')
                ->with('link', 'manageschoolyear');

        }

        $teachersinfo = SPP_Teacher::filterTeacherFaculty(null, 6, null, null, null);

        $utype = DB::table('usertype')
            ->where('deleted', 0)
            ->whereNotIn('id', [7, 9, 17, 12, 6])
            ->get();

        $nationality = DB::table('nationality')
            ->where('deleted', 0)
            ->get();

        $civilstatus = DB::table('civilstatus')
            ->where('deleted', 0)
            ->get();

        return view('adminPortal.pages.account')
            ->with('usertype', $utype)
            ->with('nationality', $nationality)
            ->with('civilstatus', $civilstatus)
            ->with('data', $teachersinfo);

    }

    public static function insertinfo(Request $request)
    {
        DB::table('schoolinfo')->update([
            'schoolid' => $request->get('schoolid'),
            'schoolname' => $request->get('schoolname'),
            'divisiontext' => $request->get('division'),
            'regiontext' => $request->get('region'),
            'districttext' => $request->get('district'),
            'address' => $request->get('address'),
            'abbreviation' => $request->get('abbreviation'),
            'tagline' => $request->get('schooltagline'),
            'updatedby' => auth()->user()->id,
            'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
        ]);
        toast('Successfull', 'success')->autoClose(2000)->toToast($position = 'top-right');
        return back();
    }

    public static function admingetcity(Request $request)
    {
        return DB::table('refcitymun')->where('regDesc', $request->get('data'))->get();
    }

    public function viewschoolinfo()
    {
        $schoolInfo = DB::table('schoolinfo')->first();
        return view('adminPortal.pages.schoolinformation')->with('schoolInfo', $schoolInfo);
    }

    public function updateschoolinfo(Request $request)
    {
        try {
            DB::table('schoolinfo')->update([
                'schoolid' => $request->get('schoolid'),
                'schoolname' => $request->get('schoolname'),
                'divisiontext' => $request->get('division'),
                'regiontext' => $request->get('region'),
                'districttext' => $request->get('district'),
                'address' => $request->get('address'),
                'abbreviation' => $request->get('abbreviation'),
                'tagline' => $request->get('schooltagline'),
                'updatedby' => auth()->user()->id,
                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);

            if ($request->has('schoollogo')) {
                $message = [
                    'schoollogo.mimes' => 'School Logo must be a file of type: jpeg, png.',
                ];

                $validator = Validator::make($request->all(), [
                    'schoollogo' => ['mimes:jpeg,png']
                ], $message);

                if ($validator->fails()) {
                    return array(
                        (object) [
                            'status' => 0,
                            'message' => 'School Logo must be a file of type: jpeg, png!'
                        ]
                    );
                } else {
                    self::adminuploadlogo($request->file('schoollogo'), $request);
                }
            }

            return array(
                (object) [
                    'status' => 1,
                    'message' => 'Updated successfully!'
                ]
            );
        } catch (\Exception $e) {
            return self::store_error($e);
        }
    }

    // public static function adminuploadlogo($schoollogoimage, Request $request)
    // {

    //     $urlFolder = str_replace('http://', '', $request->root());
    //     $urlFolder = str_replace('https://', '', $urlFolder);

    //     if (!File::exists(public_path() . 'schoollogo/')) {
    //         $path = public_path('schoollogo');
    //         if (!File::isDirectory($path)) {
    //             File::makeDirectory($path, 0777, true, true);
    //         }
    //     }

    //     if (!File::exists(dirname(base_path(), 1) . '/' . $urlFolder . '/schoollogo/')) {
    //         $cloudpath = dirname(base_path(), 1) . '/' . $urlFolder . '/schoollogo/';
    //         if (!File::isDirectory($cloudpath)) {
    //             File::makeDirectory($cloudpath, 0777, true, true);
    //         }
    //     }

    //     $file = $schoollogoimage;
    //     $extension = $file->getClientOriginalExtension();
    //     $img = Image::make($file->path());
    //     $fileName = 'schoolLogo';
    //     $destinationPath = public_path('schoollogo/schoollogo.' . $extension);
    //     $clouddestinationPath = dirname(base_path(), 1) . '/' . $urlFolder . '/' . 'schoollogo/schoollogo.' . $extension;

    //     $img->resize(500, 500, function ($constraint) {
    //         $constraint->aspectRatio();
    //     })->resizeCanvas(500, 500, 'center')->save($destinationPath);

    //     $img->resize(500, 500, function ($constraint) {
    //         $constraint->aspectRatio();
    //     })->resizeCanvas(500, 500, 'center')->save($clouddestinationPath);

    //     DB::table('schoolinfo')
    //         ->update([
    //             'picurl' => 'schoollogo/schoollogo.' . $extension . '?random="' . \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss') . '"',
    //             'updatedby' => auth()->user()->id,
    //             'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
    //         ]);
    // }
    public static function adminuploadlogo($schoollogoimage, Request $request)
    {
        $urlFolder = str_replace(['http://', 'https://'], '', $request->root());
    
        $publicPath = public_path('schoollogo/');
        $cloudPath = dirname(base_path(), 1) . '/' . $urlFolder . '/schoollogo/';
    
        // Ensure the directories exist
        if (!File::exists($publicPath)) {
            File::makeDirectory($publicPath, 0777, true, true);
        }
    
        if (!File::exists($cloudPath)) {
            File::makeDirectory($cloudPath, 0777, true, true);
        }
    
        $file = $schoollogoimage;
        $extension = $file->getClientOriginalExtension();
        $fileName = 'schoollogo.' . $extension;
        $destinationPath = $publicPath . $fileName;
        $cloudDestinationPath = $cloudPath . $fileName;
    
        // Check if the image already exists and delete it
        if (File::exists($destinationPath)) {
            File::delete($destinationPath);
        }
    
        if (File::exists($cloudDestinationPath)) {
            File::delete($cloudDestinationPath);
        }
    
        // Resize and save the new image
        $img = Image::make($file->path());
        $img->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();
        })->resizeCanvas(500, 500, 'center')->save($destinationPath);
    
        $img->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();
        })->resizeCanvas(500, 500, 'center')->save($cloudDestinationPath);
    
        // Update the database record
        DB::table('schoolinfo')
            ->update([
                'picurl' => 'schoollogo/' . $fileName . '?random=' . \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'),
                'updatedby' => auth()->user()->id,
                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
    }
    public static function store_error($e)
    {
        DB::table('zerrorlogs')
            ->insert([
                'error' => $e,
                'createdby' => auth()->user()->id,
                'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
            ]);
        return array(
            (object) [
                'status' => 0,
                'data' => 'Something went wrong!'
            ]
        );
    }

}
