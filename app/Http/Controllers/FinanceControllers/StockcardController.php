<?php

namespace App\Http\Controllers\FinanceControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;


class StockcardController extends Controller
{
    public function getStockCard(Request $request)
    {



        $data = DB::table('stock_card')
            ->join('items', function ($join) {
                $join->on('items.id', '=', 'stock_card.itemid');
                $join->where('items.deleted', 0);
            })
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'stock_card.transacted_by');
            })
            ->leftjoin('hr_departments', function ($join) {
                $join->on('hr_departments.id', '=', 'stock_card.department');
            })
            ->where('stock_card.deleted', 0)
            ->orderBy('stock_card.created_at', 'desc')
            ->select(
                'items.description',
                'stock_card.*',
                'hr_departments.department as deparment_name',
                'users.name'
            )
            ->get();


        return $data;

    }

    public function print(Request $request)
    {


        $schoolinfo = Db::table('schoolinfo')
            ->get();

        $sy = DB::table('sy')
            ->where('isactive', '1')
            ->get();

        $data = DB::table('stock_card')
            ->join('items', function ($join) {
                $join->on('items.id', '=', 'stock_card.itemid');
                $join->where('items.deleted', 0);
            })
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'stock_card.transacted_by');
            })
            ->leftjoin('hr_departments', function ($join) {
                $join->on('hr_departments.id', '=', 'stock_card.department');
            })
            ->where('stock_card.deleted', 0)
            ->orderBy('stock_card.created_at', 'desc')
            ->select(
                'items.description',
                'stock_card.*',
                'hr_departments.department as deparment_name',
                'users.name'
            )
            ->get();


        $printedby = DB::table('teacher')
            ->select(
                'teacher.lastname',
                'teacher.firstname',
                'teacher.middlename',
                'teacher.suffix'
            )
            ->where('userid', auth()->user()->id)
            ->first();

        $printeddatetime = date('F d, Y h:i:s A');

        return view('finance/reports/pdf/pdf_stock_card')
            ->with('data', $data)
            ->with('schoolinfo', $schoolinfo)
            ->with('sy', $sy)
            ->with('printeddatetime', $printeddatetime)
            ->with('printeddatetime', $printeddatetime)
            ->with('printedby', $printedby);


    }
}

