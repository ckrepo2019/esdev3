<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HRBracketingController extends Controller
{
    public function hrbracketing(Request $request)
    {

        // return $request->all();
        return view('hr.settings.hrbracketing');

    }
}
