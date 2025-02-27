<?php

namespace App\Http\Controllers\FinanceControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\FinanceModel;
use PDF;
use Dompdf\Dompdf;
use Session;
use Auth;
use Hash;

class BookController extends Controller
{
    public function books()
    {
        return view('/finance/books');
    }

    public function book_search(Request $request)
    {
        $query = $request->get('query');

        $items = db::table('items')
                ->select('items.id','itemcode', 'items.description as itemdescription', 'itemclassification.description as classdesc', 'items.amount', 'items_classcode.description as classcode')
                ->leftjoin('itemclassification', 'items.classid', '=', 'itemclassification.id')
                ->leftjoin('items_classcode', 'items.classcode', '=', 'items_classcode.id')
                ->where(function($q) use($query){
                    $q->where('itemcode', 'like', '%' . $query . '%')
                        ->orWhere('items.description', 'like', '%'. $query . '%');
                })
                ->where('items.deleted', 0)
                ->where('book', 1)
                ->orderBy('items.description', 'asc')
                ->get();
                

        // return $items;
        
        $output = '';
        $array=[];
        foreach($items as $item)
        {
            array_push($array, $item->itemdescription);
            $output .= '
                <tr data-id="'.$item->id.'">
                    <td>'.$item->itemcode.'</td>
                    <td>'.$item->itemdescription.'</td>
                    <td>'.$item->classcode.'</td>
                    <td class="text-right">'.number_format($item->amount, 2).'</td>
              </tr>
            ';
        }

        $data = array(
            'output' => $output,
            'items' => $array
        );

        echo json_encode($data);   
    }

    public function book_append(Request $request)
    {
        $dataid = $request->get('dataid');
        $code = $request->get('code');
        $classcode = $request->get('classcode');
        $description = $request->get('description');
        $classid = $request->get('classid');
        $amount = $request->get('amount');
        $glid = $request->get('glid');
        $cash = $request->get('cash');
        $receivable = $request->get('receivable');
        $expense = $request->get('expense');

        if($dataid == 0)
        {
            $check =  db::table('items')
                ->where('description', $description)
                ->where('deleted', 0)
                ->count();

            if($check > 0)
            {
                return 'exist';
            }
            else
            {
                db::table('items')  
                    ->insert([
                        'itemcode' => $code,
                        'classcode' => $classcode,
                        'description' => $description,
                        'classid' => $classid,
                        'amount' => $amount,
                        'glid' => $glid,
                        'cash' => $cash,
                        'isreceivable' => $receivable,
                        'isexpense' => $expense,
                        'book' => 1,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => FinanceModel::getServerDateTime()
                    ]);

                return 'done';
            }
        }
        else
        {
            $check = db::table('items')
                ->where('description', $description)
                ->where('id', '!=', $dataid)
                ->where('deleted', 0)
                ->count();

            if($check > 0)
            {
                return 'exist';
            }
            else
            {
                db::table('items')
                    ->where('id', $dataid)
                    ->update([
                        'itemcode' => $code,
                        'classcode' => $classcode,
                        'description' => $description,
                        'classid' => $classid,
                        'amount' => $amount,
                        'glid' => $glid,
                        'cash' => $cash,
                        'isreceivable' => $receivable,
                        'isexpense' => $expense,
                        'book' => 1,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => FinanceModel::getServerDateTime()
                    ]);

                return 'done';
            }
        }
    }


}