<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use Session;

class ChangePasswordController extends Controller
{

      public static function changepasswordchange(Request $request){

            $curpass = $request->get('curpass');
            $newpass = $request->get('newpass');
            $passcon = $request->get('passcon');

            $password = DB::table('users')->where('id',auth()->user()->id)->first();

            if(Hash::check($curpass,$password->password)){

                  DB::table('users')
                        ->take(1)
                        ->where('id',auth()->user()->id)
                        ->update([
                              'password'=>Hash::make($newpass),
                              'passwordstr'=>null,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Password Updated.'
                  ]);

            }else{
                  return array((object)[
                        'status'=>0,
                        'input'=>'#curpass',
                        'message'=>'Current Password is invalid.'
                  ]);
            }

      }

     

}
