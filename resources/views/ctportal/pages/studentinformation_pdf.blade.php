<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Document</title>
    <style>
        html {
            font-family: Arial, Helvetica, sans-serif
        }

        .logo {
            width: 100%;
            table-layout: fixed;
        }

        .header {
            width: 100%;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size: 10px;
        }

        table {
            border-collapse: collapse;
        }

        .table thead th {
            vertical-align: bottom;
        }

        .table td,
        .table th {
            padding: 5px;
            vertical-align: top;
        }

        .table-bordered {
            border: 1px solid #00000;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #00000;
        }

        .text-center {
            text-align: center !important;
        }

        .text-left {
            text-align: left !important;
        }

        .align-top {
            vertical-align: top !important;
        }

        .dashed-border {
            border-top: 1px dashed black !important;
            border-bottom: 1px dashed black !important;
        }

        .grades td {
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-family: Arial, Helvetica, sans-serif
        }

        .grades td:last-child {
            text-align: left;
        }

        .teacher-info,
        .dean-info {
            font-size: 16px;
            font-weight: bold;
            color: #000000;
        }

        .position-title {
            font-size: 14px;
            font-weight: normal;
            font-style: italic;
            color: #1289eb;
        }

        .college-name {
            font-size: 14px;
            color: #030303;
        }
    </style>
    @php
        $scinfo = DB::table('schoolinfo')->first();
        $students = collect($students); // Convert array to collection

        // Sort students alphabetically by their full name
        $sortedStudents = $students->sortBy(function($student) {
            return $student->lastname . ' ' . $student->firstname . ' ' . $student->middlename;
        });
    @endphp
</head>

<body>
    <table class="table grades " width="100%">
        <tr>
            <td style="text-align: right !important; vertical-align: top;" width="15%">
                <img src="{{ base_path() }}/public/{{ $scinfo->picurl }}" alt="school" width="70px">
            </td>
            <td style="width: 70%; text-align: center;" class="align-middle">
                <div style="width: 100%; font-weight: bold; font-size: 19px !important;">{{ $scinfo->schoolname }}</div>
                <div style="width: 100%; font-size: 12px;">{{ $scinfo->address }}</div>
            </td>
            <td width="15%"></td>
        </tr>
    </table>
    
    <table class="table grades" width="100%" style="font-size: 12px">
        <tr>
            <td class="text-center p-0">OFFICIAL CLASS LIST</td>
        </tr>
        <tr>
            <td class="text-center p-0">[ {{ $schedinfo->subjCode }} - {{ $schedinfo->subjDesc }} ]</td>
        </tr>
        <tr>
            <td width="16%" class="text-center">{{ $sydesc }} - {{ $semester }}</td>
        </tr>
    </table>

    <table class="table grades" width="100%" style="font-size: 12px">
        <tr>
            <td>Schedule:
                @foreach ($sched_list as $sched_item)
                    {{ $sched_item->time }} | <i>{{ $sched_item->day }}</i> <br>
                @endforeach
            </td>
            <td style="text-align: right;">
                <span class="college-name">{{ optional($schedinfo)->collegeDesc ?? 'No college information' }}</span>
            </td>
        </tr>
    </table>

    <table class="table grades table-bordered">
        <tr class="grades-header" style="font-size: 10px">
            <td class="text-left"><b>Student ID No.</b></td>
            <td style="width: 20%" class="text-left"><b>Student's Name</b></td>
            <td style="width: 10%" class="text-left"><b>Academic Level</b></td>
            <td style="width: 10%" class="text-left"><b>Course</b></td>
            <td class="dashed-border text-left"><b>Contact No.</b></td>
            <td style="width: 5%" class="dashed-border text-left"><b>Email Address</b></td>
            <td style="width: 50%" class="dashed-border text-left"><b>Address</b></td>
        </tr>

        <!-- Display All Students Sorted Alphabetically -->
        @foreach ($sortedStudents as $student)
            <tr style="font-size: 9px"> 
                <td>{{ $student->sid }}</td>
                <td>{{ $student->lastname }}, {{ $student->firstname }} {{ $student->middlename }} {{ $student->suffix }}</td>
                <td>{{ $student->levelname }}</td>
                <td>{{ $student->courseabrv }}</td>
                <td>{{ $student->contactno }}</td>
                <td>{{ $student->semail }}</td>
                <td>{{ $student->street }} {{ $student->barangay }} {{ $student->city }} {{ $student->province }}</td>
            </tr>
        @endforeach
    </table>

    <br>
    <table class="table grades">
        <tr>
            <td width="45%" style="text-align:center">
                <span class="teacher-info">{{ $teacher->firstname }} {{ $teacher->middlename }} {{ $teacher->lastname }}</span>
                <br>
                <span class="position-title">Instructor</span>
            </td>
            <td width="45%" style="text-align:center;">
                <span class="dean-info">{{ optional($dean)->name ?? 'No dean assigned' }}</span>
                <br>
                <span class="position-title">Dean</span>
            </td>
        </tr>
    </table>
</body>

</html>
