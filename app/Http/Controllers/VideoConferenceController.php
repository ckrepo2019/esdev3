<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
// use App\Models\Meeting;

class VideoConferenceController extends  \App\Http\Controllers\Controller
{
    public function index()
    {
        $roomName = 'LaravelJitsiMeeting';
        // $meeting = Meeting::create(['room_name' => $roomName]);

        // return view('video-conference', compact('roomName', 'meeting'));
        return view('video_conference/video-conference', compact('roomName'));
    }

    public function virtualRooms(){
        return DB::table('virtual_rooms')
        ->where('deleted', 0)
        ->select('*', DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d %l:%i:%s %p') as created_at_formatted"))
        ->get();
    }

    public function createVirtualRoom(Request $request)
    {
        $existingRoom = DB::table('virtual_rooms')->where('deleted', 0)->where('room_name', $request->roomName)->first();
        if($existingRoom) {
            return response(['status' => 'error', 'message' => 'Room already exists!'], 400);
        }

        $meeting = DB::table('virtual_rooms')->insertGetId([
            'room_name' => $request->roomName,
            'creator' => $request->creator,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
      
        if($meeting) {
            return response(['status' => 'success', 'message' => 'Room created successfully.'], 200);
        }
    }

    public function deleteVirtualRoom(Request $request){
        DB::table('virtual_rooms')->where('id', $request->id)->update([
            'deleted' => 1,
            'updated_at' => now(),
        ]);

        return response(['status' => 'success', 'message' => 'Room deleted successfully.'], 200);
    }

    public function checkVirtualRoom(Request $request){
        $existingRoom = DB::table('virtual_rooms')->where('deleted', 0)->where('room_name', $request->roomName)->first();
        if(!$existingRoom) {
            return response(['status' => 'error', 'message' => 'Room does not exists!'], 200);
        }else{
            return response(['status' => 'success', 'message' => 'Redirecting!'], 200);
        }
    }


}

