<?php

namespace App\Http\Controllers\HRControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use \Carbon\Carbon;
use Carbon\CarbonPeriod;
use Crypt;
use File;
use DateTime;
use DateInterval;
use DatePeriod;
use PDF;
use Session;

class HRAttendanceuploadController extends Controller
{

    public function upload_attendance(Request $request)
    {
        // Validate that the file is present and is an Excel file
        $request->validate([
            'input_attendance' => 'required|file|mimes:xlsx,xls',
        ]);

        $employeeNames = DB::table('teacher')
            ->select('id', DB::raw('UPPER(lastname) as lastname'))
            ->where('deleted', '0')
            ->where('isactive', '1')
            ->orderBy('lastname', 'asc')
            ->get()
            ->keyBy(function ($item) {
                return strtoupper($item->lastname);
            });
           
        // Extract and process data
        $path = $request->file('input_attendance')->getRealPath();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($path);

        // Get the first worksheet
        $worksheet = $spreadsheet->getActiveSheet();
        $data = $worksheet->toArray(null, true, true, true);

        $employees = [];
        $currentEmployee = null;
        $year = Carbon::now()->year; // Default to current year

        foreach ($data as $rowNum => $row) {
            if (isset($row['J']) && preg_match('/Date:(\d{4})-\d{2}-\d{2}/', $row['J'], $matches)) {
                $year = $matches[1];
            }

            if (isset($row['A']) && $row['A'] === 'Schedule') {
                $scheduleRow = $rowNum;
                continue;
            }

            if (isset($row['D']) && strpos($row['D'], 'Name:') === 0) {
                $name = trim(str_replace('Name:', '', $row['D']));
                $currentEmployee = ['name' => $name, 'dates' => []];
                continue;
            }

            if ($currentEmployee && ($rowNum === $scheduleRow + 5)) {
                while (isset($data[$rowNum])) {
                    $attendanceRow = $data[$rowNum];
                    if (empty($attendanceRow['A'])) break;

                    $date = $year . '-' . str_replace('/', '-', $attendanceRow['A']);
                    $attendanceDetails = [
                        'date' => $date,
                        'times' => array_filter([
                            $attendanceRow['C'] ?? null,
                            $attendanceRow['D'] ?? null,
                            $attendanceRow['E'] ?? null,
                            $attendanceRow['F'] ?? null,
                        ])
                    ];

                    $currentEmployee['dates'][] = $attendanceDetails;
                    $rowNum++;
                }
                $employees[] = $currentEmployee;
                $currentEmployee = null;
            }
        }

        foreach ($employees as $employeeData) {
            // $employeeId = $employeeNames[$employeeData['name']]->id ?? null;
            $employeeId = $employeeNames[strtoupper($employeeData['name'])]->id ?? null;

            if ($employeeId) {
                $insertData = [];
                foreach ($employeeData['dates'] as $attendance) {
                    // Delete existing attendance records
                    DB::table('taphistory')
                        ->where('tdate', $attendance['date'])
                        ->where('studid', $employeeId)
                        ->where('deleted', 0)
                        ->delete();
                    
                    foreach ($attendance['times'] as $time) {
                        $insertData[] = [
                            'tdate' => $attendance['date'],
                            'ttime' => $time,
                            'studid' => $employeeId,
                            'utype' => 1,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => now()
                        ];
                    }
                }
                if (!empty($insertData)) {
                    DB::table('taphistory')->insert($insertData);
                }
            }
        }

        return 1;
    }


}
