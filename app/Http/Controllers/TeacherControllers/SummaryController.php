<?php

namespace App\Http\Controllers\TeacherControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function index()
    {

        return view('teacher.summaries.sumarriesdashboard');

    }
}
