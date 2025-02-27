<?php

namespace App\Http\Controllers\LibraryController;

use Illuminate\Http\Request;
use DB;

class ReportController extends \App\Http\Controllers\Controller
{
    public function reports(Request $request)
    {
        $data = [];
        if ($request->action == 'circulation') {
            $data = DB::table('library_circulation')
                ->leftJoin('library_books', 'library_circulation.circulation_book_id', '=', 'library_books.id')
                ->join('library_status', 'library_circulation.circulation_status', '=', 'library_status.id')
                ->join('libraries', 'library_books.library_branch', '=', 'libraries.id')
                ->where('circulation_deleted', 0)
                ->select(
                    'library_circulation.*',
                    'library_books.book_title',
                    'library_books.book_author',
                    'libraries.library_name',
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

                if ($item->circulation_utype == 'STUDENT') {
                    $info = DB::table('studinfo')
                        ->where('studinfo.id', $item->circulation_members_id)
                        ->first();
                    $item->cardid = 'S' . $info->sid;
                } else {
                    $info = DB::table('teacher')
                        ->where('teacher.id', $item->circulation_members_id)
                        ->select(
                            'teacher.*',
                            'employee_personalinfo.*'
                        )
                        ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                        ->first();
                    $userdetail = DB::table('users')->where('id', $info->userid)->first();
                    $item->cardid = $userdetail->email;
                }
            }

            // dd($data);
        } else if ($request->action == 'borrower') {

            $data = DB::table('library_circulation')
                ->where('circulation_deleted', 0)
                ->select(
                    DB::raw('MAX(id) as max_id'),
                    DB::raw('MAX(circulation_book_id) AS max_book_id'),
                    DB::raw('MAX(circulation_name) AS max_circulation_name'),
                    DB::raw('MAX(circulation_utype) AS max_circulation_utype'),
                    DB::raw('MAX(circulation_members_id) AS max_circulation_members_id'),
                    DB::raw('MAX(circulation_penalty) AS max_circulation_penalty'),
                    DB::raw('MAX(circulation_due_date) AS max_circulation_due_date'),
                    DB::raw('MAX(circulation_date_borrowed) AS max_circulation_date_borrowed'),
                    DB::raw('MAX(circulation_date_returned) AS max_circulation_date_returned'),
                    DB::raw('MAX(circulation_status) AS max_circulation_status'),
                    DB::raw('MAX(circulation_deleted) AS max_circulation_deleted')
                )
                ->groupBy('circulation_name')
                ->get();

            // dd($data);

            $lost = 0;
            $issued = 0;
            $returned = 0;

            foreach ($data as $item) {
                if ($item->max_circulation_utype == 'STUDENT') {
                    $info = DB::table('studinfo')
                        ->where('studinfo.id', $item->max_circulation_members_id)
                        ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                        ->select(
                            'studinfo.*',
                            'gradelevel.levelname'
                        )
                        ->first();


                    $item->email = $info->sid;
                    $item->class = $info->levelname;
                    $item->phone = $info->contactno;

                    $borrowed = DB::table('library_circulation')
                        ->where('circulation_deleted', 0)
                        ->where('circulation_members_id', $item->max_circulation_members_id)
                        ->where('circulation_utype', $item->max_circulation_utype)
                        ->where('circulation_status', 2)
                        ->count();
                    $returned = DB::table('library_circulation')
                        ->where('circulation_deleted', 0)
                        ->where('circulation_members_id', $item->max_circulation_members_id)
                        ->where('circulation_utype', $item->max_circulation_utype)
                        ->where('circulation_status', 3)
                        ->count();
                    $issued = DB::table('library_circulation')
                        ->where('circulation_deleted', 0)
                        ->where('circulation_members_id', $item->max_circulation_members_id)
                        ->where('circulation_utype', $item->max_circulation_utype)
                        ->where('circulation_status', 1)
                        ->count();
                    $lost = DB::table('library_circulation')
                        ->where('circulation_deleted', 0)
                        ->where('circulation_members_id', $item->max_circulation_members_id)
                        ->where('circulation_utype', $item->max_circulation_utype)
                        ->where('circulation_status', 4)
                        ->count();

                    $item->borrowed = $borrowed;
                    $item->returned = $returned;
                    $item->issued = $issued;
                    $item->lost = $lost;
                    $item->cardid = 'S' . $info->sid;


                } else {
                    $info = DB::table('teacher')
                        ->where('teacher.id', $item->max_circulation_members_id)
                        ->leftJoin('employee_personalinfo', 'teacher.id', '=', 'employee_personalinfo.employeeid')
                        ->select(
                            'teacher.*',
                            'employee_personalinfo.*'
                        )
                        ->first();

                    $item->email = $info->email;
                    $item->class = $item->max_circulation_utype;
                    $item->phone = $info->contactnum;

                    $borrowed = DB::table('library_circulation')
                        ->where('circulation_deleted', 0)
                        ->where('circulation_members_id', $item->max_circulation_members_id)
                        ->where('circulation_utype', $item->max_circulation_utype)
                        ->where('circulation_status', 2)
                        ->count();
                    $returned = DB::table('library_circulation')
                        ->where('circulation_deleted', 0)
                        ->where('circulation_members_id', $item->max_circulation_members_id)
                        ->where('circulation_utype', $item->max_circulation_utype)
                        ->where('circulation_status', 3)
                        ->count();
                    $issued = DB::table('library_circulation')
                        ->where('circulation_deleted', 0)
                        ->where('circulation_members_id', $item->max_circulation_members_id)
                        ->where('circulation_utype', $item->max_circulation_utype)
                        ->where('circulation_status', 1)
                        ->count();
                    $lost = DB::table('library_circulation')
                        ->where('circulation_deleted', 0)
                        ->where('circulation_members_id', $item->max_circulation_members_id)
                        ->where('circulation_utype', $item->max_circulation_utype)
                        ->where('circulation_status', 4)
                        ->count();

                    $item->borrowed = $borrowed;
                    $item->returned = $returned;
                    $item->issued = $issued;
                    $item->lost = $lost;

                    $email = DB::table('users')->where('id', $info->userid)->value('email');
                    $item->cardid = $email;
                }
            }

            // dd($data);

        } else if ($request->action == 'hardref') {
            $data = DB::table('library_books')
                ->where('library_books.book_deleted', 0)
                ->where('library_categories.category_reference', 'hard reference')
                ->join('library_categories', 'library_books.book_category', '=', 'library_categories.id')
                ->select(
                    'library_books.*',
                    'library_categories.category_name',
                    'library_categories.category_reference'
                )
                ->get();
        } else if ($request->action == 'eref') {
            $data = DB::table('library_books')
                ->where('library_books.book_deleted', 0)
                ->where('library_categories.category_reference', 'e-reference')
                ->join('library_categories', 'library_books.book_category', '=', 'library_categories.id')
                ->select(
                    'library_books.*',
                    'library_categories.category_name',
                    'library_categories.category_reference'
                )
                ->get();
        } else if ($request->action == 'overdue') {
            $data = DB::table('library_circulation')
                ->leftJoin('library_books', 'library_circulation.circulation_book_id', '=', 'library_books.id')
                ->join('library_status', 'library_circulation.circulation_status', '=', 'library_status.id')
                ->join('libraries', 'library_books.library_branch', '=', 'libraries.id')
                ->where('circulation_deleted', 0)
                ->where('circulation_status', '!=', 3)
                ->whereNotNull('library_circulation.circulation_due_date') // Ensure there is a due date
                ->whereDate('library_circulation.circulation_due_date', '<', now()) // Filter overdue items
                ->select(
                    'library_circulation.*',
                    'library_books.book_title',
                    'library_books.book_callnum',
                    'library_books.book_author',
                    'libraries.library_name',
                    'library_status.status_name'
                )
                ->get();

            foreach ($data as $item) {
                $item->circulation_due_date = $this->parse_date($item->circulation_due_date);
            }
        }

        return view('library.pages.reports', ['jsonData' => $data, 'action' => $request->action]);
    }

    public function parse_date($dateString)
    {
        $dateTime = new \DateTime($dateString);
        $formattedDate = $dateTime->format('F d, Y');

        return $formattedDate;
    }

}
