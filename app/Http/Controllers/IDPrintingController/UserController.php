<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return \DB::table('users')->get();
    }

    public function edit_user(Request $request)
    {
        return \DB::table('users')->where('id', $request->id)->get();
    }

    public function store(Request $request)
    {
        \DB::table('users')->insert([
            'name' => $request->name,
            'utype' => $request->utype,
            'email' => $request->email,
            'password' => \Hash::make($request->password),
        ]);

        return array(
            (object) [
                'status' => 200,
                'statusCode' => "success",
                'message' => 'Added User!'
            ]
        );
    }

    public function update_user(Request $request)
    {
        \DB::table('users')
            ->where('id', $request->id)
            ->update([
                'name' => $request->name,
                'utype' => $request->utype,
                'email' => $request->email,
            ]);

        return array(
            (object) [
                'status' => 200,
                'statusCode' => "success",
                'message' => 'Updated User!'
            ]
        );
    }

    public function destroy(Request $request)
    {
        \DB::table('users')->where('id', $request->id)->delete();

        return array(
            (object) [
                'status' => 200,
                'statusCode' => "success",
                'message' => 'Deleted Successfully!',
            ]
        );
    }

}
