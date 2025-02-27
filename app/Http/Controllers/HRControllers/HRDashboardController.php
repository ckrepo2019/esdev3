<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class HRDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $numberofemployees = DB::table('teacher')
            ->select('id')
            ->where('isactive','1')
            ->count();
        // -----------------------------------------------------------------------------------
        $schoolyears = DB::table('sy')
            ->get();
        return view('hr.home')
            ->with('numberofemployees',$numberofemployees);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
}
