<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Summary</title>
    <style>
        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size: 11px;
        }

        table {
            border-collapse: collapse;
        }

        .table thead th {
            vertical-align: bottom;
        }

        .table td,
        .table th {
            padding: .75rem;
            vertical-align: top;
        }

        .table td,
        .table th {
            padding: .75rem;
            vertical-align: top;
        }

        .table-bordered {
            border: 1px solid #00000;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #00000;
        }

        .table-sm td,
        .table-sm th {
            padding: .3rem;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-left {
            text-align: left !important;
        }

        .p-0 {
            padding: 0 !important;
        }

        .p-1 {
            padding: .25rem !important;
        }


        .mb-0 {
            margin-bottom: 0;
        }

        .border-bottom {
            border-bottom: 1px solid black;
        }

        .mb-1,
        .my-1 {
            margin-bottom: .25rem !important;
        }

        body {
            font-family: Calibri, sans-serif;
        }

        .align-middle {
            vertical-align: middle !important;
        }


        .grades td {
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: 11px !important;
            font-family: Arial, sans-serif;
            /* font-family: "Lucida Console", "Courier New", monospace; */
        }

        .grades-header td {
            font-size: .6rem !important;
        }

        .studentinfo td {
            padding-top: .1rem;
            padding-bottom: .1rem;

        }

        .text-red {
            color: red;
            border: solid 1px black;
        }


        .page_break {
            page-break-before: always;
        }

        @page {
            size: 8.5in 11in;
            margin: .25in;
        }
    </style>
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
    <table class="table grades" width="100%">
        <tr>
            <td class="text-center p-0">OFFICIAL GRADE SHEET</td>
        </tr>
        <tr>
            <td class="text-center p-0">[ {{ $schedinfo->subjCode ?? 'N/A' }} - {{ $schedinfo->subjDesc ?? 'N/A' }} ]
            </td>
        </tr>
    </table>
    <table class="table grades" width="100%">
        <tr>
            <td width="15%">Instructor:</td>
            <td width="35%">{{ $instructor }}</td>
            <td width="35%" class="text-right">School Year:</td>
            <td width="15%" class="text-right">{{ $syinfo->sydesc }}</td>
        </tr>
        <tr>
            <td>Schedule:</td>
            <td>
                @foreach ($time_list as $item)
                    [{{ $item->day }}] {{ $item->curtime }}
                    @if (count($time_list) > 0)
                        <br>
                    @endif
                @endforeach
            </td>
            <td class="text-right">Semester:</td>
            <td class="text-right">{{ $seminfo->semester }}</td>
        </tr>
    </table>
    <table class="table grades table-bordered mb-0" width="100%">

        @php
            $col_count = 0;
            if ($gradesetup->prelim == 1) {
                $col_count += 1;
            }

            if ($gradesetup->midterm == 1) {
                $col_count += 1;
            }

            if ($gradesetup->prefi == 1) {
                $col_count += 1;
            }

            if ($gradesetup->final == 1) {
                $col_count += 1;
            }

        @endphp

        <tr class="grades-header">
            <td width="2%" class="p-0 align-middle text-center"><b>#</b></td>
            <td width="19%" class=" p-1"><b>STUDENT</b></td>
            {{-- <td width="19%" class=" p-1"><b>FIRST NAME</b></td> --}}
            <td width="15%" class=" p-1"><b>COURSE</b></td>
            @if (isset($gradesetup))
                @if ($gradesetup->prelim == 1)
                    <td width="6%" class="p-0 align-middle text-center"><b>
                            PRELIM
                        </b></td>
                @endif
                @if ($gradesetup->midterm == 1)
                    <td width="8%" class="p-0 align-middle text-center"><b>
                            MIDTERM
                        </b></td>
                @endif
                @if ($gradesetup->prefi == 1)
                    <td width="6%" class="p-0 align-middle text-center"><b>
                            PREFI
                        </b></td>
                @endif
                @if ($gradesetup->final == 1)
                    <td width="6%" class="p-0 align-middle text-center"><b>
                            FINAL
                        </b></td>
                @endif
                {{-- <td width="9%" style="font-size:.55rem !important" class="p-0 align-middle text-center"><b>
                        TERM GRADE
                    </b></td> --}}
                <td width="9%" style="font-size:.55rem !important" class="p-0 align-middle text-center"><b>
                        REMARKS
                    </b></td>
            @else
                <td></td>
                <td></td>
            @endif
        </tr>
        @php
            $count = 1;
        @endphp
        @foreach (collect($students)->sortBy('student')->values() as $item)
            @php
                $temp_grades = collect($grades)
                    ->where('studid', $item->studid)
                    ->first();
                $prelim = null;
                $mid = null;
                $prefi = null;
                $final = null;
                $fg = null;
                $remarks = null;

                $prelimstat = null;
                $midstat = null;
                $prefistat = null;
                $finalstat = null;

                if (isset($temp_grades)) {
                    $prelim = $temp_grades->prelemgrade;
                    $mid = $temp_grades->midtermgrade;
                    $final = $temp_grades->finalgrade;
                    $prefi = $temp_grades->prefigrade;
                    $fg = $temp_grades->fg;
                    $remarks = $temp_grades->fgremarks;

                    $prelimstat = $temp_grades->prelemstatus;
                    $midstat = $temp_grades->midtermstatus;
                    $finalstat = $temp_grades->finalstatus;
                    $prefistat = $temp_grades->prefistatus;

                    // if($final > 3.0){
                    //     $remarks = 'FAILED';
                    // }else{
                    //     $remarks = 'PASSED';
                    // }
                }
            @endphp
            <tr class="grades-header">
                <td class="align-middle">{{ $count }}</td>
                <td class="align-middle p-1">{{ $item->lastname }}, {{ $item->firstname }}</td>

                <td class="align-middle  p-1">{{ $item->courseabrv }} -
                    {{ $item->levelid <= 21 ? $item->levelid - 16 : $item->levelid - 21 }}</td>

                @if (isset($gradesetup))
                    @if ($gradesetup->prelim == 1)
                        <td class="p-0 align-middle text-center" style="font-size:.75rem !important">
                            {{ $prelimstat == 8 ? 'INC' : $prelim }}</td>
                    @endif
                    @if ($gradesetup->midterm == 1)
                        <td class="p-0 align-middle text-center" style="font-size:.75rem !important">
                            {{ $midstat == 8 ? 'INC' : $mid }}</td>
                        {{-- {{ $remarks == 'INC' ? $midstat : ($midstat == 8 ? 'INC' : $mid) }}</td> --}}
                    @endif
                    @if ($gradesetup->prefi == 1)
                        <td class="p-0 align-middle text-center" style="font-size:.75rem !important">
                            {{ $prefistat == 8 ? 'INC' : $prefi }}</td>
                        {{-- {{ $remarks == 'INC' ? $prefistat : ($prefistat == 8 ? 'INC' : $prefi) }}</td> --}}
                    @endif
                    @if ($gradesetup->final == 1)
                        <td class="p-0 align-middle text-center" style="font-size:.75rem !important">
                            {{ $finalstat == 8 ? 'INC' : $final }}</td>
                        {{-- {{ $remarks == 'INC' ? $finalstat : ($finalstat == 8 ? 'INC' : $final) }}</td> --}}
                    @endif

                    <td class="p-0 align-middle text-center" style="font-size:.75rem !important">{{ $remarks }}
                    </td>
                @else
                    <td></td>
                    <td></td>
                @endif

            </tr>
            {{-- <tr class="grades-header">
                <td class="align-middle">{{ $count }}</td>
                <td class="align-middle p-1">{{ $item->lastname }}, {{ $item->firstname }}</td>

                <td class="align-middle  p-1">{{ $item->courseabrv }} -
                    {{ $item->levelid <= 21 ? $item->levelid - 16 : $item->levelid - 21 }}</td>

                @if (isset($gradesetup))
                    @if ($gradesetup->prelim == 1)
                        <td class="p-0 align-middle text-center" style="font-size:.75rem !important">
                            {{ $prelim }}</td>
                    @endif
                    @if ($gradesetup->midterm == 1)
                        <td class="p-0 align-middle text-center" style="font-size:.75rem !important">
                            {{ $mid }}</td>
                    @endif
                    @if ($gradesetup->prefi == 1)
                        <td class="p-0 align-middle text-center" style="font-size:.75rem !important">
                            {{ $prefi }}</td>
                    @endif
                    @if ($gradesetup->final == 1)
                        <td class="p-0 align-middle text-center" style="font-size:.75rem !important">
                            {{ $final }}</td>
                    @endif

                    <td class="p-0 align-middle text-center" style="font-size:.75rem !important">{{ $remarks }}
                    </td>
                @else
                    <td></td>
                    <td></td>
                @endif

            </tr> --}}
            @php
                $count += 1;
            @endphp
        @endforeach

    </table>
    <table class="table grades" width="100%">
        <tr>
            <td width="100%" class="text-right"> <i style="font-size:.5rem !important">Date Generated:
                    {{ \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM DD, YYYY hh::mm a') }}</i></td>
        </tr>
    </table>

    <br>
    <br>
    <br>
    <table class="table grades" width="100%">
        <tr>
            <td width="5%"></td>
            <td width="40%" class="text-center border-bottom">{{ $instructor }}</td>
            <td width="10%"></td>
            <td width="40%" class="text-center border-bottom">{{ $dean_text }}</td>
            <td width="5%"></td>
        </tr>
        <tr>
            <td></td>
            <td class="text-center"><b>Instructor</b></td>
            <td></td>
            <td class="text-center"><b>Dean</b></td>
            <td></td>
        </tr>
    </table>
    </div>

</body>

</html>
