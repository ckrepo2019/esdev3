<?php

namespace App\Http\Controllers\QueuingController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueuingViewController extends Controller
{
    public function show(){

        $ques_size = DB::table('queuing_transaction')
            ->where('deleted', 0)
            ->where('isDone', 0)
            ->get();

        $ques = DB::table('queuing_transaction')
            ->where('deleted', 0)
            ->where('isDone', 0)
            ->take(5)
            ->get();

        return view('superadmin.pages.queuing.queuing-view', [
            'ques'=>$ques,
            'size'=>count($ques_size)
        ]);
        
    }

    public function get_ques(){

        $ques = DB::table('queuing_transaction')
            ->where('deleted', 0)
            ->where('isDone', 0)
            ->where('window_number', 0)
            ->take(5)
            ->get();

        return $ques;
    }
}
