<?php

namespace App\Http\Controllers\LibraryController;

use Illuminate\Http\Request;

use DB;

class GenreController extends \App\Http\Controllers\Controller
{
    public function load_graphs(Request $request)
    {
        $labels = [];
        $borrowed = [];
        $returned = [];

        $genres = DB::table('library_genres')->get();
        foreach ($genres as $genre) {
            $labels[] = $genre->genre_name;
            $borrowed[] = DB::table('library_circulation')
                ->where('circulation_status', 2)
                ->where('library_books.book_genre', $genre->id)
                ->whereMonth('library_circulation.circulation_date_borrowed', '=', date('m', strtotime($request->month)))
                ->join('library_books', 'library_circulation.circulation_book_id', '=', 'library_books.id')
                ->count();
            $returned[] = DB::table('library_circulation')
                ->where('circulation_status', 3)
                ->where('library_books.book_genre', $genre->id)
                ->whereMonth('library_circulation.circulation_date_returned', '=', date('m', strtotime($request->month)))
                ->join('library_books', 'library_circulation.circulation_book_id', '=', 'library_books.id')
                ->count();
        }

        $datasets = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Borrowed',
                    'data' => $borrowed
                ],
                [
                    'label' => 'Returned',
                    'data' => $returned
                ],
            ]
        ];

        return response()->json($datasets);

    }
    public function index()
    {
        return DB::table('library_genres')
            ->where('deleted', 0)
            ->get();
    }

    public function store(Request $request)
    {
        $result = DB::table('library_genres')->insertGetId([
            'genre_name' => $request->genre_name,
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
    public function update(Request $request)
    {
        DB::table('library_genres')
            ->where('id', $request->id)
            ->update([
                'genre_name' => $request->genre_name,
            ]);

        return array(
            (object) [
                'status' => 200,
                'statusCode' => "success",
                'message' => 'Updated Successfully!',
            ]
        );
    }

    public function get_genre(Request $request)
    {
        $cat = DB::table('library_genres')
            ->where('id', $request->id)
            ->where('deleted', 0)
            ->first();

        return response()->json($cat);
    }

    public function destroy(Request $request)
    {
        DB::table('library_genres')
            ->where('id', $request->id)
            ->update([
                'deleted' => 1,
            ]);

        return array(
            (object) [
                'status' => 200,
                'statusCode' => "success",
                'message' => 'Deleted Successfully!',
            ]
        );
    }
}
