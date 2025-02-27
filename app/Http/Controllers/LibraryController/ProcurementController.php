<?php

namespace App\Http\Controllers\LibraryController;

use Illuminate\Http\Request;
use DB;

class ProcurementController extends \App\Http\Controllers\Controller
{

    public function get_procurement(Request $request)
    {
        $categories = DB::table('library_categories')->where('deleted', 0)->get();

        $usedNumbers = []; // Array to store used random numbers

        foreach ($categories as $item) {
            $item->total_proc_purchase = DB::table('library_procurements')
                ->where('book_category', $item->id)
                ->where('procurement_type', 'purchase')
                ->when(isset($request->library_branch), function ($query) use ($request) {
                    return $query->where('library_branch', $request->library_branch);
                })
                ->count();

            $item->total_proc_donation = DB::table('library_procurements')
                ->where('book_category', $item->id)
                ->where('procurement_type', 'donation')
                ->count();

            do {
                // Generate a random number for the image URL
                $randomNumber = mt_rand(1, 5); // Adjust the range based on the number of images you have
            } while (in_array($randomNumber, $usedNumbers));

            // Add the generated number to the used numbers array
            $usedNumbers[] = $randomNumber;

            // Set the imageUrl property based on the random number
            $item->imageUrl = asset('assets/lms/procurement' . $randomNumber . '.png');
        }
        return view(
            'library.pages.procurements',
            [
                'action' => $request->action,
                'jsonData' => $categories,
            ]
        );
    }


    public function storeProcurement(Request $request)
    {
        $imageName = '';
        $request->validate([
            'image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Check if the request contains the 'image' file
        if ($request->hasFile('image')) {
            // Process image upload
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('books'), $imageName);
            $imageName = 'books/' . $imageName;
        } else {
            // Handle case where no image file is provided
            $imageName = ''; // Set a default or handle accordingly
        }

        $result = DB::table('library_procurements')
            ->insertGetId([
                'book_img' => $imageName,
                'book_isbn' => $request->isbn,
                'book_description' => $request->description,
                'book_received' => $request->datereceived,
                'book_edition' => $request->edition,
                'book_callnum' => $request->callnumber,
                'book_copyright' => $request->copyright,
                'book_qty' => $request->quantity,
                'book_genre' => $request->genre,
                'book_price' => $request->price,
                'book_title' => $request->title,
                'book_author' => $request->author,
                'book_available' => $request->available,
                'book_publisher' => $request->publisher,
                'book_category' => $request->category,
                'library_branch' => $request->branch,
                'procurement_type' => $request->procurement_type,
                'procurement_donor' => $request->procurement_donor,
            ]);

        if ($result) {
            return array(
                (object) [
                    'status' => 200,
                    'statusCode' => "success",
                    'message' => 'Added Successfully!',
                ]
            );
        } else {
            return array(
                (object) [
                    'status' => 400,
                    'statusCode' => "error",
                    'message' => 'Adding Failed!',
                ]
            );
        }
    }
}
