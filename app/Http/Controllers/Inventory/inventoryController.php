<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class inventoryController extends Controller
{
    public function getItems(Request $request)
    {

        $id = $request->get('id');

        $items = db::table('items')
            ->where('itemtype', 'inventory')
            ->where('deleted', 0);
        if ($id) {
            $items->where('id', $id);
        }
        $data = $items->get();


        return $data;

    }

    public function saveDepartment(Request $request)
    {

        try {

            // Validate the incoming request data
            $validatedData = $request->validate([
                'department' => 'required|string|max:255',
            ]);

            $insertData = [
                'department' => $validatedData['department'],
                'createddatetime' => now(),
            ];

            // Insert the data into the database and retrieve the ID
            $id = DB::table('hr_departments')->insertGetID($insertData);

            // Prepare the department details to return in the JSON response
            $department = [
                'id' => $id,
                'department' => $validatedData['department'],
            ];

            // Return the JSON response with success message and department details
            return response()->json(['message' => 'Department Added successfully', 'department' => $department], 201);


        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json(['message' => $e->validator->errors()->first()], 400);
        }
    }

    public static function stockcard($id, $department, $qty, $remarks)
    {

        $onhand = db::table('items')
            ->where('id', $id)
            ->value('qty');

        // Insert data into stockard table
        DB::table('stock_card')->insert([
            'initial_onhand' => $onhand,
            'onhand' => $onhand - $qty,
            'stock_out' => $qty,
            'itemid' => $id,
            'department' => $department,
            'remarks' => $remarks,
            'transacted_by' => auth()->user()->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Decrement onhand in warehouse_product table
        DB::table('items')
            ->where('id', $id)
            ->decrement('qty', $qty);



    }

    public function saveAssigned(Request $request)
    {

        try {

            // Validate the incoming request data
            $validatedData = $request->validate([
                'id' => 'required|integer',
                'department' => 'required|string|max:255',
                'qty' => 'required|integer|min:0',
                'remark' => 'nullable'
            ]);


            $onhand = db::table('items')
                ->where('id', $validatedData['id'])
                ->value('qty');


            if ($onhand < $validatedData['qty']) {

                return response()->json(['message' => 'Qty cannot Exceed Onhand'], 400);

            }


            $id = $validatedData['id'];
            $department = $validatedData['department'];
            $qty = $validatedData['qty'];
            $remarks = $validatedData['remark'];

            self::stockcard($id, $department, $qty, $remarks);



            // Return the JSON response with success message and department details
            return response()->json(['message' => 'Department Added successfully', 'department' => $department], 201);


        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json(['message' => $e->validator->errors()->first()], 400);
        }
    }

    public function getcardNumbers(Request $request)
    {

        // Calculate total purchase amount
        $totalPurchase = DB::table('purchasing_details')
            ->join('purchasing', function ($join) {
                $join->on('purchasing_details.headerid', '=', 'purchasing.id');
                $join->where('pstatus', 'POSTED');
                $join->where('purchasing.deleted', 0);
            })
            ->where('purchasing_details.deleted', 0)
            ->sum('purchasing_details.totalamount');

        // Calculate total quantity of items
        $totalItem = DB::table('items')
            ->where('itemtype', 'inventory')
            ->where('deleted', 0)
            ->sum('qty');

        $totalReceive = DB::table('receiving_details')
            ->join('receiving', function ($join) {
                $join->on('receiving_details.headerid', '=', 'receiving.id');
                $join->where('rstatus', 'POSTED');
                $join->where('receiving.deleted', 0);
            })
            ->where('receiving_details.deleted', 0)
            ->sum('receiving_details.receivedqty');

        $totalReceiveMoney = DB::table('receiving_details')
            ->join('receiving', function ($join) {
                $join->on('receiving_details.headerid', '=', 'receiving.id');
                $join->where('rstatus', 'POSTED');
                $join->where('receiving.deleted', 0);
            })
            ->where('receiving_details.deleted', 0)
            ->sum('receiving_details.rtotal');


        $totalPurchase = number_format($totalPurchase, 2, '.', ',');
        $totalReceiveMoney = number_format($totalReceiveMoney, 2, '.', ',');

        // Create an array with the calculated values
        $total = [
            'totalPurchase' => $totalPurchase,
            'totalItem' => $totalItem,
            'totalReceive' => $totalReceive,
            'totalReceiveMoney' => $totalReceiveMoney
        ];



        // Return the array
        return $total;


    }

    public function getStocks(Request $request)
    {



        $totalItem = DB::table('items')
            ->where('itemtype', 'inventory')
            ->where('deleted', 0)
            ->get();


        foreach ($totalItem as $item) {

            if ($item->qty < $item->minimum_qty) {

                $item->badge = 'badge badge-warning';

            } else {

                $item->badge = 'badge badge-primary';

            }

        }


        return $totalItem;



    }

    public function getDepartment(Request $request)
    {

        $department = DB::table('hr_departments')
            ->where('deleted', 0)
            ->get();


        return $department;

    }

    public function getStockSummary(Request $request)
    {



        $totalItem = DB::table('items')
            ->where('itemtype', 'inventory')
            ->where('deleted', 0)
            ->get();

        $stock = count($totalItem);
        $low_stock = 0;

        foreach ($totalItem as $item) {

            if ($item->qty < $item->minimum_qty) {

                $low_stock++;

            }

        }

        $stock = $stock - $low_stock;
        $data = array();

        array_push($data, $low_stock, $stock);




        return $data;



    }

    public function getReceivingSummary(Request $request)
    {

        // Get the month from the request
        $month = $request->get('month');

        // Get the current year
        $currentYear = date('Y');

        // Create a Carbon instance for the first day of January this year
        $firstDayOfYear = Carbon::createFromFormat('Y-n', $currentYear . '-1')->startOfMonth();

        // Create a Carbon instance for today's date
        $today = Carbon::today();

        // Initialize an array to store all the months in this year
        $monthsInYear = array();
        $formattedDate = array();

        // Loop through each month from January to the current month of this year
        $currentMonth = $firstDayOfYear->copy();
        while ($currentMonth <= $today) {
            // Add the month to the array
            $monthsInYear[] = $currentMonth->format('n');
            $formattedDate[] = $currentMonth->format('F Y');

            // Move to the next month
            $currentMonth->addMonth();
        }

        $data = array();
        foreach ($monthsInYear as $month) {

            $data[] = DB::table('receiving_details')
                ->join('receiving', function ($join) {
                    $join->on('receiving_details.headerid', '=', 'receiving.id');
                    $join->where('rstatus', 'POSTED');
                    $join->where('receiving.deleted', 0);
                })
                ->where('receiving_details.deleted', 0)
                ->whereYear('receiving.datedelivered', $currentYear) // Filter by this year
                ->whereMonth('receiving.datedelivered', $month) // Filter by month
                ->sum('receiving_details.rtotal');
        }

        // $formattedDate now contains all the months in this year

        return [
            'label' => $formattedDate,
            'data' => $data,
        ];


    }



}
