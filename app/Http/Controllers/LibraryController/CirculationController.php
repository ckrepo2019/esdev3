<?php

namespace App\Http\Controllers\LibraryController;

use Illuminate\Http\Request;
use DB;

class CirculationController extends \App\Http\Controllers\Controller
{
    public function circulation(Request $request)
    {
        $data = [];
        $str_status = '';
        $data = DB::table('library_circulation')
            ->leftJoin('library_books', 'library_circulation.circulation_book_id', '=', 'library_books.id')
            ->join('library_status', 'library_circulation.circulation_status', '=', 'library_status.id')
            ->when(isset($request->action), function ($query) use ($request) {
                return $query->where('circulation_status', $request->action);
            })
            ->where('circulation_deleted', 0)
            ->select(
                'library_circulation.*',
                'library_books.book_title',
                'library_status.status_name'
            )->get();


        foreach ($data as $item) {
            if ($item->circulation_due_date) {
                $item->circulation_due_date = $this->parse_date($item->circulation_due_date);
            }
            if ($item->circulation_date_borrowed) {
                $item->circulation_date_borrowed = $this->parse_date($item->circulation_date_borrowed);
            }
            if ($item->circulation_date_returned) {
                $item->circulation_date_returned = $this->parse_date($item->circulation_date_returned);
            }
        }

        switch ($request->action) {
            case 1:
                $str_status = 'Issued';
                break;
            case 2:
                $str_status = 'Borrowed';
                break;
            case 3:
                $str_status = 'Returned';
                break;
            case 4:
                $str_status = 'Lost';
                break;
            default;
                $str_status = 'All';
        }


        return view('library.pages.circulation', ['jsonData' => $data, 'action' => $request->action, 'text' => $str_status]);
    }
    public function parse_date($dateString)
    {
        $dateTime = new \DateTime($dateString);
        $formattedDate = $dateTime->format('F d, Y');

        return $formattedDate;
    }
    public function circulations(Request $request)
    {


        $data = DB::table('library_circulation')
            ->leftJoin('library_books', 'library_circulation.circulation_book_id', '=', 'library_books.id')
            ->leftJoin('studinfo', 'library_circulation.circulation_members_id', '=', 'studinfo.userid')
            ->join('library_status', 'library_circulation.circulation_status', '=', 'library_status.id')
            ->where('circulation_status', $request->action)
            ->where('circulation_deleted', 0)
            ->select(
                'library_circulation.*',
                DB::raw('CONCAT(studinfo.lastname, " ", studinfo.firstname) as borrower_name'),
                'library_books.book_title',
                'library_status.status_name'
            )
            ->get();

        foreach ($data as $item) {
            if ($item->circulation_due_date) {
                $item->circulation_due_date = $this->parse_date($item->circulation_due_date);
            }
            if ($item->circulation_date_borrowed) {
                $item->circulation_date_borrowed = $this->parse_date($item->circulation_date_borrowed);
            }
            if ($item->circulation_date_returned) {
                $item->circulation_date_returned = $this->parse_date($item->circulation_date_returned);
            }
        }

        return response()->json($data);
    }
    public function getCirculationByBorrower(Request $request)
    {

        $data = DB::table('library_circulation')
            ->leftJoin('library_books', 'library_circulation.circulation_book_id', '=', 'library_books.id')
            ->leftJoin('library_borrowers', 'library_circulation.circulation_members_id', '=', 'library_borrowers.id')
            ->join('library_status', 'library_circulation.circulation_status', '=', 'library_status.id')
            ->where('library_borrowers.borrower_username', auth()->user()->email)
            ->where('circulation_status', $request->action)
            ->where('circulation_deleted', 0)
            ->select(
                'library_circulation.*',
                'library_borrowers.borrower_name',
                'library_books.book_title',
                'library_status.status_name'
            )
            ->get();

        foreach ($data as $item) {
            if ($item->circulation_due_date) {
                $item->circulation_due_date = $this->parse_date($item->circulation_due_date);
            }
            if ($item->circulation_date_borrowed) {
                $item->circulation_date_borrowed = $this->parse_date($item->circulation_date_borrowed);
            }
            if ($item->circulation_date_returned) {
                $item->circulation_date_returned = $this->parse_date($item->circulation_date_returned);
            }
        }

        return response()->json($data);
    }

    public function getCirculation(Request $request)
    {
        // ->when($request->filled('levelid'), function ($query) use ($request) {
        //     return $query->where('sh_enrolledstud.levelid', $request->levelid);
        // })


        if ($request->utype == 'STUDENT') {

            $result = DB::table('library_circulation')
                ->leftJoin('library_books', 'library_circulation.circulation_book_id', '=', 'library_books.id')
                ->leftJoin('studinfo', 'library_circulation.circulation_members_id', '=', 'studinfo.id')
                ->leftJoin('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->leftJoin('library_status', 'library_circulation.circulation_status', '=', 'library_status.id')
                ->where('library_circulation.id', $request->id)
                ->where('library_circulation.circulation_deleted', 0)
                ->select(
                    'studinfo.*',
                    'library_circulation.*',
                    'library_books.*',
                    'library_circulation.id',
                    'library_status.status_name',
                    DB::raw('library_books.id AS library_book_id'),
                    DB::raw('gradelevel.levelname AS class'),
                    DB::raw('studinfo.sid AS email')
                )
                ->first();

            return response()->json($result);
        } else {
            $result = DB::table('library_circulation')
                ->leftJoin('library_books', 'library_circulation.circulation_book_id', '=', 'library_books.id')
                ->leftJoin('teacher', 'library_circulation.circulation_members_id', '=', 'teacher.id')
                ->leftJoin('employee_personalinfo', 'library_circulation.circulation_members_id', '=', 'employee_personalinfo.id')
                ->leftJoin('library_status', 'library_circulation.circulation_status', '=', 'library_status.id')
                ->where('library_circulation.id', $request->id)
                ->where('library_circulation.circulation_deleted', 0)
                ->select(
                    'library_circulation.*',
                    'library_books.*',
                    'library_circulation.id',
                    'library_status.status_name',
                    DB::raw('library_books.id AS library_book_id'),
                    DB::raw('library_circulation.circulation_utype AS class'),
                    DB::raw('teacher.phonenumber AS contactno'),
                    DB::raw('employee_personalinfo.email AS email')
                )
                ->first();

            return response()->json($result);
        }

    }

    public function storeCirculation(Request $request)
    {
        $result = DB::table('library_circulation')
            ->insertGetId([
                'circulation_book_id' => $request->circulation_book_id,
                'circulation_members_id' => $request->circulation_members_id,
                'circulation_penalty' => $request->circulation_penalty,
                'circulation_due_date' => $request->circulation_due_date,
                'circulation_date_borrowed' => $request->circulation_date_borrowed,
                'circulation_date_returned' => $request->circulation_date_returned,
                'circulation_status' => $request->circulation_status,
                'circulation_utype' => $request->circulation_utype,
                'circulation_name' => $request->circulation_name,
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
                    'message' => 'Failed to add Circulation!',
                ]
            );
        }
    }
    public function updateCirculation(Request $request)
    {
        DB::table('library_circulation')
            ->where('id', $request->id)
            ->update([
                'circulation_book_id' => $request->circulation_book_id,
                'circulation_members_id' => $request->circulation_members_id,
                'circulation_penalty' => $request->circulation_penalty,
                'circulation_due_date' => $request->circulation_due_date,
                'circulation_date_borrowed' => $request->circulation_date_borrowed,
                'circulation_date_returned' => $request->circulation_date_returned,
                'circulation_status' => $request->circulation_status,
            ]);

        return array(
            (object) [
                'status' => 200,
                'statusCode' => "success",
                'message' => 'Updated Successfully!',
            ]
        );
    }

    public function deleteCirculation(Request $request)
    {
        DB::table('library_circulation')
            ->where('id', $request->id)
            ->update([
                'circulation_deleted' => 1,
            ]);

        return array(
            (object) [
                'status' => 200,
                'statusCode' => "success",
                'message' => 'Deleted Successfully!',
            ]
        );
    }

    public function viewborrower(Request $request)
    {
        $statuses = [1 => 'issued', 2 => 'borrowed', 3 => 'returned', 4 => 'lost'];
        $data = [];

        if ($request->utype == 'STUDENT') {
            $borrowerData = DB::table('studinfo')
                ->where('studinfo.id', $request->id)
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->select(
                    DB::raw('gradelevel.levelname AS utype'),
                    'studinfo.*',
                    DB::raw('CONCAT(studinfo.firstname, " " ,studinfo.lastname) AS name')
                )
                ->first();
        } else {
            $borrowerData = DB::table('teacher')
                ->where('teacher.id', $request->id)
                ->join('employee_personalinfo', 'teacher.id', 'employee_personalinfo.employeeid')
                ->join('usertype', 'teacher.usertypeid', 'usertype.id')
                ->select(
                    DB::raw('CONCAT(teacher.firstname, " " ,teacher.lastname) AS name'),
                    DB::raw('usertype.utype AS utype'),
                    'employee_personalinfo.*'
                )
                ->first();
        }
        $data['borrower'] = $borrowerData;

        // dd($data);

        foreach ($statuses as $status => $statusName) {
            $data[$statusName] = DB::table('library_circulation')
                ->leftJoin('library_books', 'library_circulation.circulation_book_id', '=', 'library_books.id')
                ->join('library_status', 'library_circulation.circulation_status', '=', 'library_status.id')
                ->where('library_circulation.circulation_members_id', $request->id)
                ->where('library_circulation.circulation_utype', $request->utype)
                ->where('circulation_status', $status)
                ->where('circulation_deleted', 0)
                ->select(
                    'library_circulation.*',
                    'library_books.book_title',
                    'library_status.status_name'
                )
                ->get();
        }

        // dd($data);

        return view('library.pages.borrowerdetails', $data);
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
            if ($book->book_available == 0) {
                return response()->json(['status' => 'error', 'message' => 'This book is out of stock!'], 200);
            }

            $book->book_description_short = \Str::limit($book->book_description, 100);
            $book->branch_index = explode(',', $book->library_branch);
            if ($book->book_img) {
                $book->book_img = asset($book->book_img);
            } else {
                $book->book_img = asset('books/default.png');
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

    public function get_borrower(Request $request)
    {

        if ($request->utype == 7)

            $lib = DB::table('users')
                ->where('users.deleted', 0)
                ->where('users.id', $request->id)
                ->join('studinfo', 'users.id', '=', 'studinfo.userid')
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->select(
                    'studinfo.*',
                    'users.*',
                    'gradelevel.levelname',
                    DB::raw('CONCAT(studinfo.lastname, " ", studinfo.firstname) as borrower_name')
                )
                ->first();

        $requestedbooks = DB::table('library_requested_books')
            ->join('library_books', 'library_requested_books.requested_bookid', '=', 'library_books.id')
            ->select(
                'library_requested_books.*',
                'library_books.*',
                DB::raw('library_books.book_title AS text')
            )
            ->where('requested_userid', $lib->userid)
            ->get();

        return response()->json(['lib' => $lib, 'request' => $requestedbooks]);
    }


}
