<?php

namespace App\Http\Controllers\AdministratorControllers;

use Illuminate\Http\Request;
use DB;
use Session;

class SchoolInformationController extends \App\Http\Controllers\Controller
{

    public static function getSchoolInfoProjectSetup(){

        return DB::table('schoolinfo')
                    ->select(
                        'abbreviation',
                        'processsetup',
                        'projectsetup',
                        'es_cloudurl'
                    )
                    ->get();
    
    }


}
