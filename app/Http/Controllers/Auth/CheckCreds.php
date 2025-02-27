<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use DB;

class CheckCreds extends Controller
{
    public function checkcredentials(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = DB::table('users')
            ->where('email', $request->username)
            ->leftJoin('studinfo', 'users.id', '=', 'studinfo.userid')
            ->join('studinfo_more', 'studinfo.id', '=', 'studinfo_more.studid')
            ->leftJoin('college_courses', 'college_courses.id', '=', 'studinfo.courseid')
            ->leftJoin('nationality', 'nationality.id', '=', 'studinfo.nationality')
            ->select(
                'users.*', 
                'studinfo.*', 
                'studinfo_more.*',
                'college_courses.courseDesc',
                'nationality.nationality'
                )
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => true,
                'user' => $user,
            ]);
        }

        return response()->json(['success' => false], 200);
    }
}
