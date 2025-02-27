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
            /* text-transform: uppercase; */

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

        $numlimit = count($students) / 2;
        if (strpos($numlimit, '.') !== false) {
            $numlimit += 0.5;
        }

        $scinfo = DB::table('schoolinfo')->first();
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
                <div style="width: 100%; font-size: 12px;"></div>
            </td>
            <td width="15%">

            </td>
        </tr>
    </table>
    <table class="table grades" width="100%" style="font-size: 12px">
        <tr>
            <td class="text-center p-0">EXAMINATION LIST</td>
        </tr>
        <tr>
            <td class="text-center p-0">[ {{ $schedinfo->subjCode }} -
                {{ $schedinfo->subjDesc }} ]</td>
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
    {{-- <div style="width: 100%; text-align: center; font-size: 12px;">
        {{DB::table('schoolinfo')->first()->schoolname}}
        <br/>
           {{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}
        <br/>
        <br/>
        @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')
            GRADING SHEET
        @else
            OFFICIAL CLASS LIST
        @endif
        <br/>
        {{$semester}} S.Y. {{$sydesc}}
    </div> --}}
    @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')
        <table style="width: 100%; font-size: 11px; border-collapse: collapse; text-align: left !important;">
            <tr>
                <th style="width: 15%;">Subjects Code:</th>
                <td><u>{{ collect($sched_list)->first()->subjCode }}</u></td>
                <th style="width: 15%;">Credit Units:</th>
                <td>{{ collect($sched_list)->first()->lecunits + collect($sched_list)->first()->labunits }}</td>
            </tr>
            <tr>
                <th>Descriptive Title:</th>
                <td><u>{{ collect($sched_list)->first()->subjDesc }}</u></td>
                <th>Time:</th>
                <td><u>{{ date('h:i A', strtotime(collect($sched_list)->first()->stime)) }} -
                        {{ date('h:i A', strtotime(collect($sched_list)->first()->etime)) }}</u></td>
            </tr>
        </table>
        <br>

        <table style="width:100%; font-size: 11px; border-collapse: collapse;" cellpadding="0" cellspacing="0">
            <thead style="text-align: left !important;">
                <tr>
                    <th width="3%" class="text-center">NO</th>
                    <th style="width: 2%;">&nbsp;</th>
                    <th style="width: 35%;">Name</th>
                    <th style="width: 2%;">&nbsp;</th>
                    <th width="10%">Program</th>
                    <th style="width: 2%;">&nbsp;</th>
                    <th width="10%">Year Level</th>
                    <th style="width: 2%;">&nbsp;</th>
                    <th width="10%">Midterm</th>
                    <th style="width: 2%;">&nbsp;</th>
                    <th width="10%">Final</th>
                    <th style="width: 2%;">&nbsp;</th>
                    <th width="10%">Sem Grade</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $num = 1;
                @endphp
                @foreach ($students as $key => $student)
                    <tr>
                        <td class="text-center">{{ $num }}</td>
                        <td></td>
                        <td>{{ $student->lastname }}, {{ $student->firstname }} {{ $student->middlename }}
                            {{ $student->suffix }}</td>
                        <td></td>
                        <td>{{ $student->courseabrv }}</td>
                        <td></td>
                        <td>{{ str_replace('COLLEGE', '', $student->levelname) }}</td>
                        <td></td>
                        <td style="border-bottom: 1px solid black;"></td>
                        <td></td>
                        <td style="border-bottom: 1px solid black;"></td>
                        <td></td>
                        <td style="border-bottom: 1px solid black;"></td>
                    </tr>
                    @php
                        $num += 1;
                    @endphp
                @endforeach
            </tbody>
        </table>
        <br />
        <br />
        <br />
        <table style="width: 100%; font-size: 12px;">
            <tr>
                <td style="border-bottom: 1px solid black;"></td>
                <td style="width: 10%;"></td>
                <td style="border-bottom: 1px solid black;"></td>
                <td style="width: 10%;"></td>
                <td style="border-bottom: 1px solid black;"></td>
            </tr>
            <tr>
                <td style="text-align: center;">Instructor</td>
                <td></td>
                <td style="text-align: center;">Dean</td>
                <td></td>
                <td style="text-align: center;">Registrar</td>
            </tr>
            <tr>
                <td>Date Signed:</td>
                <td></td>
                <td>Date Signed:</td>
                <td></td>
                <td>Date Signed:</td>
            </tr>
        </table>
    @else
        {{-- <table style="width: 100%; font-size: 12px; border-collapse: collapse; text-align: left !important;">
            <tr> 
                <th width="15%">Subject:</th>     
                <td width="85%">{{collect($sched_list)->first()->subjCode}} - <i>{{collect($sched_list)->first()->subjDesc}}</i></td>
            </tr>
        </table>
        <table style="width: 100%; font-size: 12px; border-collapse: collapse; text-align: left !important;">
            <tr> 
                <th width="15%">Section / Course:</th>     
                <td width="85%">{{collect($sched_list)->first()->sectionDesc}} - <i>{{collect($sched_list)->first()->courseabrv}}</i></td>
            </tr>
        </table> --}}
        {{-- <table style="width: 100%; font-size: 12px; border-collapse: collapse; text-align: left !important;">
            <tr> 
                <th width="15%" class="align-top">Schedule:</th>     
                <td width="85%">
                    @foreach ($sched_list[0]->schedule as $item)
                        {{$item->time}} - <i>{{$item->day}}</i> <br>
                    @endforeach
                </td>
            </tr>
        </table> --}}
        @php
            $num = 1;
            $num2 = $numlimit + 1;
            $studarray = $students;
        @endphp
        {{-- @if (count($students) <= 40) --}}
        <table class="table grades table-bordered">
            {{-- <thead  class="grades-header"> --}}
            <tr class="grades-header" style="font-size: 10px">
                <td class="text-left"><b>Student ID No.</b></td>
                <td style="width: 20%" class="text-left"><b>Student's Name</b></td>
                <td style="width: 10%" class="text-left"><b>Academic Level</b></td>
                <td style="width: 10%" class="text-left"><b>Course</b></td>
                <td class="dashed-border text-left"><b>Status</b></td>
            </tr>
            {{-- </thead> --}}
            {{-- <tbody> --}}
            @php
                $num = 1;
            @endphp
            @php
                $students = collect($students); // Convert array to collection
                // dd($students);
                $males = $students->filter(function ($student) {
                    return $student->gender === 'MALE';
                });

                $females = $students->filter(function ($student) {
                    return $student->gender === 'FEMALE';
                });

                $num = 1;
            @endphp
        
            @if ($males->count() > 0)
            
                <tr>
                    <th colspan="5" style="text-align:left; background-color: #ffffff">Male Students</th>
                </tr>
                @foreach ($males as $key => $students)
                {{-- @dd($students) --}}
                    <tr style="font-size: 9px">
                        <td>{{ $students->sid }}</td>
                        <td>{{ $students->lastname }}, {{ $students->firstname }} {{ $students->middlename }}
                            {{ $students->suffix }}</td>
                        <td>{{ $students->levelname }}</td>
                        <td>{{ $students->courseabrv }}</td>
                        <td style="border: 1px solid black;">
                            <span style="color: {{ $students->examstatus === 'a' ? 'green' : 'red' }}">
                                {{ $students->examstatus === 'a' ? 'Permitted' : 'Not Permitted' }}
                            </span>
                        </td>
                        
                        {{-- @foreach ($students as $student)
                            <tr>
                                <td>{{ $student->sid ?? ''}}</td>
                                <td>{{ $student->student_name ?? ''}}</td>
                                <td>{{ $student->examstatus === 'a' ? 'Permitted' : 'Not Permitted' ?? ''}}</td>
                            </tr>
                        @endforeach --}}
                    </tr>
                    @php
                        $num += 1;
                    @endphp
                @endforeach
            @endif

            @if ($females->count() > 0)
                <tr>
                    <th colspan="5" style="text-align:left;  background-color: #ffffff">Female Students</th>
                </tr>
                @foreach ($females as $key => $students)
                    <tr style="font-size: 9px">
                        <td>{{ $students->sid }}</td>
                        <td>{{ $students->lastname }}, {{ $students->firstname }} {{ $students->middlename }}
                            {{ $students->suffix }}</td>
                        <td>{{ $students->levelname }}</td>
                        <td>{{ $students->courseabrv }}</td>
                        <td style="border: 1px solid black;">
                            <span style="color: {{ $students->examstatus === 'a' ? 'green' : 'red' }}">
                                {{ $students->examstatus === 'a' ? 'Permitted' : 'Not Permitted' }}
                            </span>
                        </td>
                        
                         {{-- @foreach ($students as $student)
                            <tr>
                                <td>{{ $student->sid }}</td>
                                <td>{{ $student->student_name }}</td>
                                <td>{{ $student->examstatus === 'a' ? 'Permitted' : 'Not Permitted' }}</td>
                            </tr>
                        @endforeach --}}
                    
                    </tr>
                    @php
                        $num += 1;
                    @endphp
                @endforeach
            @endif

            {{-- </tbody> --}}

        </table>

        <br>
        <table class="table grades">
            <tr>
                <td width="45%" style="text-align:center">
                    <span class="teacher-info"
                        style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:12px">
                        {{-- {{ optional($teacher)->name ?? 'No teacher assigned' }} --}}
                        {{$teacher->firstname}} {{$teacher->middlename}} {{$teacher->lastname}}
                    </span>
                    <br>
                    <span class="position-title">Instructor</span>
                </td>
                <td width="45%" style="text-align:center;">
                    <span class="dean-info"
                        style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:12px">
                        {{ optional($dean)->name ?? 'No dean assigned' }}
                    </span>
                    <br>
                    <span class="position-title">Dean</span>
                    <br>
                    <span class="college-name">{{ optional($schedinfo)->collegeDesc ?? 'No college information' }}</span>
                </td>
            </tr>
        </table>
        
    @endif

</body>

</html>
