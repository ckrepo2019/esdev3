<?php

namespace App\Http\Controllers\LibraryController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Session;


class LibraryBookController extends \App\Http\Controllers\Controller
{
    public function getLibraryBooks(Request $request)
    {
        $books = DB::table('library_books')
            ->where('book_deleted', 0)
            ->when(isset($request->genreid), function ($query) use ($request) {
                return $query->where('library_books.book_genre', $request->genreid);
            })
            ->when(isset($request->categoryid), function ($query) use ($request) {
                return $query->where('library_books.book_category', $request->categoryid);
            })
            ->when(isset($request->params), function ($query) use ($request) {
                return $query->where('library_books.library_branch', $request->params);
            })
            ->join('library_genres', 'library_books.book_genre', '=', 'library_genres.id')
            ->join('library_categories', 'library_books.book_category', '=', 'library_categories.id')
            ->select(
                'library_books.*',
                'library_genres.genre_name',
                'library_categories.category_name',
                DB::raw('library_books.book_title AS text')
            )
            ->get();
        $filteredBooks = collect($books)
            ->filter(function ($item) use ($request) {
                if ($item->book_img) {
                    $item->book_img = asset($item->book_img);
                } else {
                    $item->book_img = asset('books/no-image-found.jpg');
                }
                $item->library_branch = DB::table('libraries')->whereIn('id', explode(',', $item->library_branch))->pluck('library_name')->toArray();
                $item->book_available = $this->countCirculationsForBook($item->book_qty, $item->id);
                if (isset($request->action) && $request->action === 'getall') {
                    return true;
                } else if ($request->action === 'available') {
                    return $item->book_available > 0;
                }
            })
            ->values();

        return response()->json($filteredBooks->toArray());
    }

    public function countCirculationsForBook($qty, $bookId)
    {
        $rowCount = DB::table('library_circulation')
            ->where('circulation_deleted', 0)
            ->where('circulation_status', '!=', 3)
            ->where('circulation_book_id', $bookId)
            ->count();

        return $qty - $rowCount;
    }

    public function getDropdowns()
    {
        $genres = DB::table('library_genres')
            ->where('deleted', 0)
            ->select(
                'library_genres.*',
                DB::raw('genre_name AS text')
            )
            ->get();

        $categories = DB::table('library_categories')
            ->where('deleted', 0)
            ->select(
                'library_categories.*',
                DB::raw('category_name AS text')
            )
            ->get();

        $branches = DB::table('libraries')
            ->where('deleted', 0)
            ->select(
                'libraries.*',
                DB::raw('library_name AS text')
            )
            ->get();


        $data = [
            'genres' => $genres,
            'categories' => $categories,
            'branches' => $branches,
        ];

        return response()->json($data);

    }

    public function storeBook(Request $request)
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

        $result = DB::table('library_books')
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

    public function updateBook(Request $request)
    {
        $imageName = '';
        $originalUrl = DB::table('library_books')->where('id', $request->id)->first()->book_img;

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
            $imageName = $originalUrl;
        }

        DB::table('library_books')
            ->where('id', $request->id)
            ->update([
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
            ]);

        return array(
            (object) [
                'status' => 200,
                'statusCode' => "success",
                'message' => 'Updated Successfully!',
            ]
        );
    }

    public function getBook(Request $request)
    {
        $book = DB::table('library_books')
            ->where('library_books.id', $request->id)
            ->where('book_deleted', 0)
            ->join('library_genres', 'library_books.book_genre', '=', 'library_genres.id')
            ->join('library_categories', 'library_books.book_category', '=', 'library_categories.id')
            ->select(
                'library_books.*',
                'library_genres.genre_name',
                'library_categories.category_name'
            )
            ->first();

        if ($book) {
            $book->book_available = $this->countCirculationsForBook($book->book_qty, $book->id);
            $book->book_description_short = Str::limit($book->book_description, 100);
            $book->branch_index = explode(',', $book->library_branch);
            if ($book->book_img) {
                $book->book_img = asset($book->book_img);
            } else {
                $book->book_img = asset('books/no-image-found.jpg');
            }

            $branchIds = explode(',', $book->library_branch);
            $branchNames = DB::table('libraries')->whereIn('id', $branchIds)->pluck('library_name')->toArray();
            $book->library_branch = $branchNames;

            return response()->json($book);
        } else {
            // Handle the case where the book with the given ID doesn't exist
            return response()->json(['error' => 'Book not found'], 404);
        }
    }

    public function deleteBook(Request $request)
    {
        DB::table('library_books')
            ->where('id', $request->id)
            ->update([
                'book_deleted' => 1,
            ]);

        return array(
            (object) [
                'status' => 200,
                'statusCode' => "success",
                'message' => 'Deleted Successfully!',
            ]
        );
    }

    public function pin_book(Request $request)
    {

        $isExist = DB::table('library_requested_books')
            ->where('requested_userid', $request->userid)
            ->where('requested_bookid', $request->bookid)
            ->exists();

        if ($isExist) {
            return response()->json(['status' => 'warning', 'message' => 'Already Pinned!']);
        }

        $insertedId = DB::table('library_requested_books')->insertGetId([
            'requested_userid' => $request->userid,
            'requested_bookid' => $request->bookid,
        ]);

        if ($insertedId > 0) {
            // Book pinned successfully
            return response()->json([
                'status' => 'success',
                'message' => 'Book pinned successfully!',
                'pin_id' => $insertedId,
            ]);
        } else {
            // Failed to pin book
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to pin the book.',
            ]);
        }
    }

    public function delete_pin(Request $request)
    {
        DB::table('library_requested_books')
            ->where('id', $request->id)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Removed!',
        ]);
    }

    public function get_all_pinned()
    {
        return DB::table('library_requested_books')
            ->where('requested_deleted', 0)
            ->where('requested_userid', auth()->user()->id)
            ->join('library_books', 'library_requested_books.requested_bookid', '=', 'library_books.id')
            ->select(
                'library_requested_books.*',
                'library_books.book_img',
                'library_books.book_title'
            )
            ->get();
    }


}