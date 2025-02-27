<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use File;
use Image;
use Session;
use Validator;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class APIMobileEmployeesController extends Controller
{

public function api_login(Request $request)
	{
	  $user = $request->get('username');
	  $pword = $request->get('pword');
	  $token = $request->get('token');

    $user = DB::table('users')
        ->where(function ($query) {
            $query->where('type', '!=', 7)
                ->orWhere('type', '=', 9);
        })
        ->where('email', $user)
        ->where('deleted', 0)
        ->first();


	  if($user)
	  {
		  if(Hash::check($pword, $user->password))
		  {
			  db::table('users')
                ->where(function ($query) {
                $query->where('type', '!=', 7)
                    ->orWhere('type', '=', 9);
            })
				  ->where('id', $user->id)
				  ->update([
					  'remember_token' => $token
				  ]);


			  $sy = db::table('sy')
				  ->where('isactive', 1)
				  ->orderBy('sydesc')
				  ->get();

			  $semester = db::table('semester')
				  ->where('isactive', 1)
				  ->get();

			  if($user)
			  {
				  $data = array(
					  'user' => $user,
					  'sy' => $sy,
					  'sem' => $semester,
					  'user_id'=>$user->id,
					  'token' => $token

				  );

				  return json_encode($data);
			  }



		  }
	  }
	}
  
    public function api_add_attendance(Request $request)
    {
        $userID = $request->get('userID');
        $location = $request->get('location');
        $tdate = $request->get('tdate');
        $ttime = $request->get('ttime');
        $timeshift = $request->get('timeshift');
        $tapstate = $request->get('tapstate');
        
        $users = DB::table('users')
            ->where('id', $userID)
            ->first();

        if ($users) {
            DB::table('hr_taphistory')
                ->insert([
                    'userid' => $userID,
                    'location' => $location,
                    'tdate' => $tdate,
                    'ttime' => $ttime,
                    'timeshift' => $timeshift,
                    'tapstate' => $tapstate,
                ]);
        }  
        
        return response()->json([
            'status' => 'success',
            'message' => 'Attendance added successfully',
        ], 200);
    }
  
  	public function api_employee_attendance(Request $request)
	{
		$userid = $request->get('userid');

		$taphistory = DB::table('hr_taphistory')
            ->where('userid', $userid)
            ->get();
	

		return response()->json([
			'taphistory' => $taphistory
		]);


	}
   
}