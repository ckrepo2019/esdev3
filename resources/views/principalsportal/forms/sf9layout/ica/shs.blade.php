<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
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

        .pl-3 {
            padding-left: 1rem !important;
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
            font-size: .7rem !important;
        }

        .studentinfo td {
            padding-top: .1rem;
            padding-bottom: .1rem;

        }

        .bg-red {
            color: red;
            border: solid 1px black !important;
        }

        td {
            padding-left: 5px;
            padding-right: 5px;
        }

        .aside {
            /* background: #48b4e0; */
            color: #000;
            line-height: 15px;
            height: 35px;
            border: 1px solid #000 !important;

        }

        .aside span {
            /* Abs positioning makes it not take up vert space */
            /* position: absolute; */
            top: 0;
            left: 0;

            /* Border is the new background */
            background: none;

            /* Rotate from top left corner (not default) */
            transform-origin: 10 10;
            transform: rotate(-90deg);
        }

        .trhead {
            background-color: rgb(167, 223, 167);
            color: #000;
            font-size;
        }

        .trhead td {
            border: 1px solid #000;
        }

        /* @page {
            margin:20px 20px;
            
        } */
        body {
            /* margin:0px 10px; */

        }

        .check_mark {
            font-family: ZapfDingbats, sans-serif;
        }

        @page {
            size: 11in 8.5in;
            margin: .4in .40in;
        }
    </style>
</head>

<body>
    <table style="width: 100%; table-layout: fixed;">
        <tr>
            <td width="50%" class="p-0" style="vertical-align: top;font-size: 13px; padding-right: .40in!important;">
                {{-- attendance --}}
                <table style="width: 100%;">
                    <tr>
                        <td class="text-center"><b>ATTENDANCE RECORD</b></td>
                    </tr>
                </table>
                <table style="width: 100%; margin-top: 5px;">
                    <tr>
                        <td width="100%" class="p-0">
                            @php
                                $width = count($attendance_setup) != 0 ? 75 / count($attendance_setup) : 0;
                            @endphp
                            <table width="100%" class="table table-bordered table-sm grades mb-0"
                                style="table-layout: fixed;">
                                <tr>
                                    <td width="13%"
                                        style="border: 1px solid #000; text-align: center; height: 20px;"></td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle" width="{{ $width }}%"><span
                                                style="font-size: 9px!important">{{ \Carbon\Carbon::create(null, $item->month)->isoFormat('MMM') }}</span>
                                        </td>
                                    @endforeach
                                    <td class="text-center" width="10%"
                                        style="vertical-align: middle; font-size: 10px!important;"><span>Total</span>
                                    </td>
                                </tr>
                                <tr class="table-bordered">
                                    <td>&nbsp;No. of school days</td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle">{{ $item->days != 0 ? $item->days : '' }}
                                        </td>
                                    @endforeach
                                    <td class="text-center align-middle">{{ collect($attendance_setup)->sum('days') }}
                                    </td>
                                </tr>
                                <tr class="table-bordered">
                                    <td>&nbsp;No. of days present</td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle">
                                            {{ $item->days != 0 ? $item->present : '' }}</td>
                                    @endforeach
                                    <td class="text-center align-middle">
                                        {{ collect($attendance_setup)->where('days', '!=', 0)->sum('present') }}</td>
                                </tr>
                                <tr class="table-bordered">
                                    <td>&nbsp;No. of days absent</td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle">{{ $item->days != 0 ? $item->absent : '' }}
                                        </td>
                                    @endforeach
                                    <td class="text-center align-middle">{{ collect($attendance_setup)->sum('absent') }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table width="100%" style="font-size: 13px; margin-top: 50px;">
                    <tr>
                        <td width="100%" class="p-0 text-center"><b>PARENT/GUARDIAN'S SIGNATURE</b></td>
                    </tr>
                </table>
                <table width="100%" style="font-size: 14px; margin-top: 15px;">
                    <tr>
                        <td width="24%"class="p-0"></td>
                        <td width="20%" class="p-0 text-left">1<sup style="font-size: 11px;">st</sup> Quarter</td>
                        <td width="30%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                        <td width="26%" class="p-0"></td>
                    </tr>
                </table>
                <table width="100%" style="font-size: 14px; margin-top: 15px;">
                    <tr>
                        <td width="24%"class="p-0"></td>
                        <td width="20%" class="p-0 text-left">2<sup style="font-size: 11px;">nd</sup> Quarter</td>
                        <td width="30%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                        <td width="26%" class="p-0"></td>
                    </tr>
                </table>
                <table width="100%" style="font-size: 14px; margin-top: 15px;">
                    <tr>
                        <td width="24%"class="p-0"></td>
                        <td width="20%" class="p-0 text-left">3<sup style="font-size: 11px;">rd</sup> Quarter</td>
                        <td width="30%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                        <td width="26%" class="p-0"></td>
                    </tr>
                </table>
                <table width="100%" style="font-size: 14px; margin-top: 15px;">
                    <tr>
                        <td width="24%"class="p-0"></td>
                        <td width="20%" class="p-0 text-left">4<sup style="font-size: 11px;">th</sup> Quarter</td>
                        <td width="30%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                        <td width="26%" class="p-0"></td>
                    </tr>
                </table>
                <table width="100%" style="text-center; margin-top: 50px;">
                    <tr>
                        <td class="text-center" style="font-size: 13px;"><b>Certificate of Transfer</b></td>
                    </tr>
                </table>
                <table width="100%" style="font-size: 13px; margin-top: 25px;">
                    <tr>
                        <td width="27%" class="p-0">Admitted to Grade:</td>
                        <td width="35%" class="p-0 text-left" style="border-bottom: 1px solid #000"></td>
                        <td width="12%" class="p-0 text-center">Section:</td>
                        <td width="26%" class="p-0 text-left" style="border-bottom: 1px solid #000">
                            <b>{{ $student->sectionname }}</b>
                        </td>
                    </tr>
                </table>
                <table width="100%" style="font-size: 13px;">
                    <tr>
                        <td width="45%" class="p-0 text-left">Eligibility for Admission to Grade:</td>
                        <td width="55%" class="p-0 text-left" style="border-bottom: 1px solid #000"></td>
                    </tr>
                </table>
                <table width="100%" style="font-size: 13px; margin-top: 15px;">
                    <tr>
                        <td width="100%" class="p-0 text-left">Approved:</td>
                    </tr>
                </table>
                <table width="100%" style="margin-top: 15px;">
                    @php
                        $principal = DB::table('teacher')
                            ->where('usertypeid', 2)
                            ->where('deleted', 0)
                            ->where('isactive', 1)
                            ->first();

                        $principal_name =
                            $principal->firstname .
                            ' ' .
                            ($principal->middlename ? $principal->middlename[0] . '. ' : '') .
                            $principal->lastname .
                            ($principal->suffix ? ', ' . $principal->suffix : '');
                    @endphp
                    <tr>
                        <td class="p-0 text-center" width="45%"
                            style="border-bottom: 1px solid #000; font-size: 13px;">
                            <b>{{ $principal_info[0]->name ?? $principal_name }}</b>
                        </td>
                        <td class="p-0" width="10%" style=""></td>
                        <td class="p-0 text-center" width="45%"
                            style="border-bottom: 1px solid #000; font-size: 13px;"><b>{{ $adviser }}</b></td>
                    </tr>
                    <tr>
                        {{-- <td class="p-0" width="45 %" style="text-align: center; font-size: 12px;">{{$principal_info[0]->title}}</td> --}}
                        <td class="p-0" width="45 %" style="text-align: center; font-size: 13px;">Principal</td>
                        <td class="p-0" width="10%" style=""></td>
                        <td class="p-0" width="45%" style="text-align: center; font-size: 13px;">Class Adviser
                        </td>
                    </tr>
                </table>
                <table width="100%" style="text-center; margin-top: 30px;">
                    <tr>
                        <td class="text-center" style="font-size: 13px;"><b>Cancellation of Eligibility to
                                Transfer</b></td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 13px;">
                    <tr>
                        <td width="17%" class="p-0">Admitted in:</td>
                        <td width="24%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                        <td width="59%" class="p-0"></td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 13px;">
                    <tr>
                        <td width="9%" class="p-0">Date:</td>
                        <td width="33%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                        <td width="15%" class="p-0"></td>
                        <td width="43%" class="p-0 text-center"
                            style="border-bottom: 1px solid #000; font-size: 12px;">
                            <b>{{ $principal_info[0]->name ?? $principal_name }}</b>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 13px;">
                    <tr>
                        <td width="15%" class="p-0"></td>
                        <td width="27%" class="p-0"></td>
                        <td width="15%" class="p-0"></td>
                        {{-- <td width="43%" class="p-0">{{$principal_info[0]->title}}</td> --}}
                        <td width="43%" class="text-center p-0">Principal</td>
                    </tr>
                </table>
            </td>
            <td width="50%" class="p-0" style="vertical-align: top; padding-left: .40in!important;">
                <table class="table mb-0 mt-0" style="width: 100%; table-layout: fixed;">
                    <tr>
                        <td width="25%" class="p-0 text-center" style="font-size: 10px;"><b>REPORT CARD (SF9)</b>
                        </td>
                        <td width="50%" class="p-0"></td>
                        <td width="25%" class="p-0"></td>
                    </tr>
                </table>
                <table width="100%" class="table mt-0 mb-0" style="table-layout: fixed;">
                    <tr>
                        <td width="25%" style="text-align: center;">
                            <img src="{{ base_path() }}/public/{{ DB::table('schoolinfo')->first()->picurl }}"
                                alt="school" width="70px">
                        </td>
                        <td width="50%" style="text-align: center; font-size: 15px;">
                            <div>Republic of the Philippines</div>
                            <div>Department of Education</div>
                            <div>{{ $schoolinfo[0]->regiontext }}</div>
                            <div>DIVISION OF {{ $schoolinfo[0]->divisiontext }}</div>
                            <div>{{ $schoolinfo[0]->districttext }}</div>
                            {{-- <div style="width: 100%; font-size: 12px; margin-top: 7px;">Basic Education Department</div>
                        <div style="width: 100%; font-weight: bold; font-size: 12px;">{{$schoolinfo[0]->schoolname}}</div>
                        <div style="width: 100%; font-size: 12px; margin-top: 7px;">Basic Education Department</div>
                        <div style="width: 100%; font-size: 12px;">{{$schoolinfo[0]->address}}</div>
                        <div style="width: 100%; font-size: 12px; margin-top: 15px;"><b>Report on Learning Progress and Achievements</b></div>
                        <div style="width: 100%; font-size: 12px;">AY: {{$schoolyear->sydesc}}</div> --}}
                        </td>
                        <td width="25%" class="text-center">
                            <div><img src="{{ base_path() }}/public/assets/images/department_of_Education.png"
                                    alt="school" width="70px"></div>
                            <div style="padding-top: 5px;"><img
                                    src="{{ base_path() }}/public/assets/images/ica/deped.png" alt="school"
                                    width="60px" height="30px"></div>
                        </td>
                    </tr>
                </table>
                <table width="100%" class="table mt-0" style="table-layout: fixed;">
                    <tr>
                        <td class="text-center p-0" style="font-size: 15px;"><b>{{ $schoolinfo[0]->schoolname }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center p-0" style="font-size: 12px;">{{ $schoolinfo[0]->address }}</td>
                    </tr>
                </table>
                <table width="100%" class="table mt-0" style="table-layout: fixed; margin-top: 25px;">
                    <tr>
                        <td class="text-center p-0" style="font-size: 15px;"><b>LEARNER'S PROGRESS REPORT CARD</b>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 13px; margin-top: 35px;">
                    <tr>
                        <td width="12%" class="p-0">Name:</td>
                        <td width="88%" class="p-0 text-left" style="">
                            <b><u>{{ $student->student }}</u></b>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 13px; margin-top: 5px;">
                    <tr>
                        <td width="12%" class="p-0">LRN:</td>
                        <td width="88%" class="p-0 text-left" style=""><b><u>{{ $student->lrn }}</u></b>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 13px; margin-top: 5px;">
                    <tr>
                        <td width="12%" class="p-0">Age:</td>
                        <td width="38%" class="p-0 text-left" style=""><b><u>{{ $student->age }}</u></b>
                        </td>
                        <td width="15%" class="p-0">Sex:</td>
                        <td width="35%" class="p-0 text-left" style=""><b><u>{{ $student->gender }}</u></b>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 13px; margin-top: 5px;">
                    <tr>
                        <td width="12%" class="p-0">Grade:</td>
                        <td width="18%" class="p-0 text-left" style="">
                            <b><u>{{ str_replace('GRADE', '', $student->levelname) }}</u></b>
                        </td>
                        <td width="20%" class="p-0"></td>
                        <td width="15%" class="p-0">Section:</td>
                        <td width="35%" class="p-0 text-left" style="">
                            <b><u>{{ $student->sectionname }}</u></b>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 13px; margin-top: 5px;">
                    <tr>
                        <td width="19%" class="p-0">School Year:</td>
                        <td width="81%" class="p-0 text-left" style="">
                            <b><u>{{ $schoolyear->sydesc }}</u></b>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 13px; margin-top: 5px;">
                    <tr>
                        <td width="11%" class="p-0">Track:</td>
                        <td width="89%" class="p-0 text-left" style="text-transform: uppercase;">
                            <b><u>{{ $strandInfo->trackname }}</u></b>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 13px; margin-top: 5px;">
                    <tr>
                        <td width="11%" class="p-0">Strand:</td>
                        <td width="89%" class="p-0 text-left" style="text-transform: uppercase;">
                            <b><u>{{ $strandInfo->strandname }}</u></b>
                        </td>
                    </tr>
                </table>
                <br>
                <table style="font-size: 13px; margin-top: 40px;">
                    <tr>
                        <td width="15%" class="p-0">Dear Parent:</td>
                    </tr>
                </table>
                <table style="font-size: 13px; margin-top: 20px;">
                    <tr>
                        <td width="100%" class="p-0" style="text-indent: 50px;">This report card shows the
                            ability and progress your child has made in the different learning areas well as his/her
                            core values.</td>
                    </tr>
                </table>
                <table style="font-size: 13px; margin-top: 20px;">
                    <tr>
                        <td width="100%" class="p-0" style="text-indent: 50px;">The school welcomes you should
                            you desire to know more about your child's progress.</td>
                    </tr>
                </table>
                <br>

                <table width="100%" style="padding-top: 45px; text-align: center;">
                    <tr>
                        {{-- <td class="p-0" width="45%" style="font-size: 12px;"><b><u>{{$principal_info[0]->name}}</u></b></td> --}}
                        <td class="p-0" width="45%" style="font-size: 12px;">
                            <b><u>{{ $principal_info[0]->name ?? $principal_name }}</u></b>
                        </td>
                        <td class="p-0" width="10%" style=""></td>
                        <td class="p-0" width="45%" style="font-size: 12px;"><b><u>{{ $adviser }}</u></b>
                        </td>
                    </tr>
                    <tr>
                        {{-- <td class="p-0" width="45%" style="text-align: center; font-size: 12px;">{{$principal_info[0]->title}}</td> --}}
                        <td class="p-0" width="45%" style="text-align: center; font-size: 12px;">
                            Principal</td>
                        <td class="p-0" width="10%" style=""></td>
                        <td class="p-0" width="45%" style="text-align: center; font-size: 12px;">Class Adviser
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="width: 100%; table-layout: fixed; font-size: 10px !important; ">
        <tr>
            <td width="50%" class="p-0" style="vertical-align: top; padding-right: .30in!important;">
                <table width="100%" class="table table-sm" style="font-size: 11px !important;">
                    <tr>
                        <td width="100%" class="p-0">

                            <table class="table-sm" width="100%">
                                <tr>
                                    <td colspan="6" class="align-middle text-center"
                                        style="font-size: 13px!important;"><b>REPORTS ON LEARNER'S OBSERVED VALUES</b>
                                    </td>
                                </tr>
                            </table>
                            <table class="table-sm table table-bordered" width="100%" style="margin-top: 10px;">
                                <tr>
                                    <td rowspan="2" class="align-middle text-center" style="font-size: 12px;">
                                        <b>Core Values</b>
                                    </td>
                                    <td rowspan="2" class="align-middle text-center" style="font-size: 12px;">
                                        <b>Behavior Statements</b>
                                    </td>
                                    <td colspan="5" class="cellRight" style="font-size: 12px;">
                                        <center><b>QUARTER</b></center>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="7%">
                                        <center><b>1</b></center>
                                    </td>
                                    <td width="7%">
                                        <center><b>2</b></center>
                                    </td>
                                    <td width="7%">
                                        <center><b>3</b></center>
                                    </td>
                                    <td width="7%">
                                        <center><b>4</b></center>
                                    </td>
                                    <td width="7%">
                                        <center><b>FR</b></center>
                                    </td>
                                </tr>
                                {{-- ========================================================== --}}
                                @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                                    @php
                                        $count = 0;
                                    @endphp
                                    @foreach ($groupitem as $item)
                                        @if ($item->value == 0)
                                        @else
                                            <tr>
                                                @if ($count == 0)
                                                    <td width="27%" class="text-center align-middle p-0"
                                                        rowspan="{{ count($groupitem) }}">{{ $item->group }}</td>
                                                    @php
                                                        $count = 1;
                                                    @endphp
                                                @endif
                                                <td width="38%" class="align-middle">{{ $item->description }}</td>
                                                <td class="text-center align-middle">
                                                    @foreach ($rv as $key => $rvitem)
                                                        {{ $item->q1eval == $rvitem->id ? $rvitem->value : '' }}
                                                    @endforeach
                                                </td>
                                                <td class="text-center align-middle">
                                                    @foreach ($rv as $key => $rvitem)
                                                        {{ $item->q2eval == $rvitem->id ? $rvitem->value : '' }}
                                                    @endforeach
                                                </td>
                                                <td class="text-center align-middle">
                                                    @foreach ($rv as $key => $rvitem)
                                                        {{ $item->q3eval == $rvitem->id ? $rvitem->value : '' }}
                                                    @endforeach
                                                </td>
                                                <td class="text-center align-middle">
                                                    @foreach ($rv as $key => $rvitem)
                                                        {{ $item->q4eval == $rvitem->id ? $rvitem->value : '' }}
                                                    @endforeach
                                                </td>
                                                <td></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                                {{-- ========================================================== --}}
                            </table>
                            <table width="100%" class="table-bordered table-sm mb-0 mt-0 "
                                style="padding: 20px 100px 0px 100px; ">
                                <tr>
                                    <td width="35%" class="text-center p-0" style="font-size: 12px!important;">
                                        <b>MARKING</b>
                                    </td>
                                    <td width="65%" class="p-0" style="font-size: 12px!important;">
                                        <b>&nbsp;&nbsp;NON-NUMERICAL RATING</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="35%" class="text-center p-0">AO</td>
                                    <td width="65%" class="p-0">&nbsp;&nbsp;Always Observed</td>
                                </tr>
                                <tr>
                                    <td width="35%" class="text-center p-0 ">SO</td>
                                    <td width="30%" class="p-0">&nbsp;&nbsp;Sometimes Observed</td>
                                </tr>
                                <tr>
                                    <td width="35%" class="text-center p-0 ">RO</td>
                                    <td width="65%" class="p-0">&nbsp;&nbsp;Rarely Observed</td>
                                </tr>
                                <tr>
                                    <td width="35%" class="text-center p-0">NO</td>
                                    <td width="65%" class="p-0">&nbsp;&nbsp;Not Observed</td>
                                </tr>
                            </table>
                            <table class="table-bordered mb-0 mt-0" width="100%"
                                style="table-layout: fixed; font-size: 13px; margin: 35px 20px 0px 20px;">
                                <tr>
                                    <td width="38%" class="text-center p-0" style="font-size: 12px!important">
                                        <b>DESCRIPTORS</b>
                                    </td>
                                    <td width="32%" class="text-center p-0" style="font-size: 12px!important">
                                        <b>GRADING SCALE</b>
                                    </td>
                                    <td width="30%" class="text-center p-0" style="font-size: 12px!important">
                                        <b>REMARKS</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0">&nbsp;&nbsp;WITH HIGHEST HONORS</td>
                                    <td class="text-center p-0">98-100</td>
                                    <td class="text-center p-0">Passed</td>
                                </tr>
                                <tr>
                                    <td class="p-0">&nbsp;&nbsp;WITH HIGH HONORS</td>
                                    <td class="text-center p-0">95-97</td>
                                    <td class="text-center p-0">Passed</td>
                                </tr>
                                <tr>
                                    <td class="p-0">&nbsp;&nbsp;WITH HONORS</td>
                                    <td class="text-center p-0">90-94</td>
                                    <td class="text-center p-0">Passed</td>
                                </tr>
                                <tr>
                                    <td class="p-0">&nbsp;&nbsp;Did not Meet Expectations</td>
                                    <td class="text-center p-0">Below 75</td>
                                    <td class="text-center p-0">Failed</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

            </td>
            <td width="50%" class="p-0"
                style="height; 100px; vertical-align: top; padding-left: .30in!important;">
                <table class="table-sm" width="100%">
                    <tr>
                        <td class="align-middle text-center" style="font-size: 13px!important;"><b>REPORT ON LEARNING
                                PROGRESS AND ACHIEVEMENT</b></td>
                    </tr>
                </table>
                <table width="100%" class="table table-sm" style="font-size: 12px;">
                    <tr>
                        <td width="100%" class="p-0">
                            @if ($student->acadprogid != 5)
                                <table class="table table-sm mt-0 table-bordered" width="100%"
                                    style="table-layout: fixed;">
                                    <tr>
                                        <td width="48%" rowspan="2" class="align-middle text-center">
                                            <b>LEARNING AREAS</b>
                                        </td>
                                        <td width="30%" colspan="4" class="text-center align-middle">
                                            <b>QUARTER</b>
                                        </td>
                                        <td width="10%" rowspan="2" class="text-center align-middle"><b>FINAL
                                                GRADE</b></td>
                                        <td width="10%" rowspan="2" class="text-center align-middle"><b>ACTION
                                                TAKEN</b></span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center align-middle"><b>1</b></td>
                                        <td class="text-center align-middle"><b>2</b></td>
                                        <td class="text-center align-middle"><b>3</b></td>
                                        <td class="text-center align-middle"><b>4</b></td>
                                    </tr>
                                    @foreach ($studgrades as $item)
                                        <tr>
                                            <td
                                                style="padding-left:{{ $item->mapeh == 1 || $item->inTLE == 1 ? '2rem' : '.25rem' }}">
                                            </td>
                                            <td class="text-center align-middle">
                                                {{ $item->quarter1 != null ? $item->quarter1 : '' }}</td>
                                            <td class="text-center align-middle">
                                                {{ $item->quarter2 != null ? $item->quarter2 : '' }}</td>
                                            <td class="text-center align-middle">
                                                {{ $item->quarter3 != null ? $item->quarter3 : '' }}</td>
                                            <td class="text-center align-middle">
                                                {{ $item->quarter4 != null ? $item->quarter4 : '' }}</td>
                                            <td class="text-center align-middle">
                                                {{ $item->finalrating != null ? $item->finalrating : '' }}</td>
                                            <td class="text-center align-middle">
                                                {{ $item->actiontaken != null ? $item->actiontaken : '' }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        @php
                                            $genave = collect($finalgrade)->where('semid', 1)->first();
                                        @endphp
                                        <td colspan="5"><b>GENERAL AVERAGE</b></td>
                                        <td class="text-center align-middle">
                                            {{ $genave->finalrating != null ? $genave->finalrating : '' }}</td>
                                        <td class="text-center align-middle">
                                            {{ $genave->actiontaken != null ? $genave->actiontaken : '' }}</td>
                                    </tr>
                                </table>
                            @else
                                @for ($x = 1; $x <= 2; $x++)
                                    <table class="table mb-0" width="100%"
                                        style="table-layout: fixed; margin-top: 10px;">
                                        <tr>
                                            <td width="100%" class="align-middle text-left p-0"
                                                style="font-size: 12px; margin-top: {{ $x == 2 ? '70px' : '0px' }}!important;">
                                                <b>{{ $x == 1 ? 'First Semester' : 'Second Semester' }}</b>
                                            </td>
                                        </tr>
                                    </table>
                                    <table class="table table-bordered grades_2 mb-0" width="100%"
                                        style="table-layout: fixed;">
                                        {{-- <tr>
                                    <td colspan="6" class="align-middle text-left p-0" style="font-size: 12px;"><b>{{$x == 1 ? 'FIRST SEMESTER' : 'SECOND SEMESTER'}}</b></td>
                                </tr> --}}
                                        <tr>
                                            <td rowspan="2" class="align-middle text-center p-0"
                                                style="font-size: 11px!important;"><b>SUBJECTS</b></td>
                                            <td width="19%" colspan="2" class="text-center align-middle p-0"
                                                style="font-size: 11px!important;"><b>QUARTER</b></td>
                                            <td width="15%" rowspan="2" class="text-center align-middle p-0"
                                                style="font-size: 11px!important;"><b>Semester <br> Final Grade</b>
                                            </td>
                                            {{-- <td rowspan="2" width="10%" class="text-center align-middle p-0" style="font-size: 9px!important;"><b>ACTION <br> TAKEN</b></td> --}}
                                        </tr>
                                        <tr style="">
                                            @if ($x == 1)
                                                <td class="p-0 text-center align-middle"><b>1</b></td>
                                                <td class="p-0 text-center align-middle"><b>2</b></td>
                                            @elseif($x == 2)
                                                <td class="p-0 text-center align-middle"><b>3</b></td>
                                                <td class="p-0 text-center align-middle"><b>4</b></td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="p-0 text-left align-middle"
                                                style="background-color: #C0C0C0; font-size: 11px!important;">
                                                &nbsp;&nbsp;<b>CORE SUBJECTS</b></td>
                                        </tr>
                                        @foreach (collect($studgrades)->where('type', 1)->where('semid', $x) as $item)
                                            <tr>
                                                <td class="" width="66%"
                                                    style="padding: 3px 0px 3px 5px !important; font-size: 10px!important;">
                                                    <b>{{ $item->subjdesc != null ? $item->subjdesc : null }}</b>
                                                </td>
                                                @if ($x == 1)
                                                    <td class="p-0 text-center align-middle">
                                                        {{ $item->quarter1 != null ? $item->quarter1 : '' }}</td>
                                                    <td class="p-0 text-center align-middle">
                                                        {{ $item->quarter2 != null ? $item->quarter2 : '' }}</td>
                                                @elseif($x == 2)
                                                    <td class="p-0 text-center align-middle">
                                                        {{ $item->quarter3 != null ? $item->quarter3 : '' }}</td>
                                                    <td class="p-0 text-center align-middle">
                                                        {{ $item->quarter4 != null ? $item->quarter4 : '' }}</td>
                                                @endif
                                                <td class="text-center align-middle p-0">
                                                    {{ $item->finalrating != null ? $item->finalrating : '' }}</td>
                                                {{-- <td class="text-center align-middle p-0" style="font-size: 10px!important;">{{$item->actiontaken != null ? $item->actiontaken:''}}</td> --}}
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4" class="p-0 text-left align-middle"
                                                style="background-color: #C0C0C0; font-size: 11px!important;">
                                                &nbsp;&nbsp;<b>APPLIED SUBJECTS</b></td>
                                        </tr>
                                        @foreach (collect($studgrades)->where('type', 3)->where('semid', $x) as $item)
                                            <tr>
                                                <td class="" style="padding: 3px 0px 3px 5px !important;">
                                                    {{ $item->subjdesc != null ? $item->subjdesc : null }}</td>
                                                @if ($x == 1)
                                                    <td class="p-0 text-center align-middle">
                                                        {{ $item->quarter1 != null ? $item->quarter1 : '' }}</td>
                                                    <td class="p-0 text-center align-middle">
                                                        {{ $item->quarter2 != null ? $item->quarter2 : '' }}</td>
                                                @elseif($x == 2)
                                                    <td class="p-0 text-center align-middle">
                                                        {{ $item->quarter3 != null ? $item->quarter3 : '' }}</td>
                                                    <td class="p-0 text-center align-middle">
                                                        {{ $item->quarter4 != null ? $item->quarter4 : '' }}</td>
                                                @endif
                                                <td class="p-0 text-center align-middle">
                                                    {{ $item->finalrating != null ? $item->finalrating : '' }}</td>
                                                {{-- <td class="text-center align-middle p-0" style="font-size: 10px!important;">{{$item->actiontaken != null ? $item->actiontaken:''}}</td> --}}
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4" class="p-0 text-left align-middle"
                                                style="background-color: #C0C0C0; font-size: 11px!important;">
                                                &nbsp;&nbsp;<b>SPECIALIZED SUBJECTS</b></td>
                                        </tr>
                                        @foreach (collect($studgrades)->where('type', 2)->where('semid', $x) as $item)
                                            <tr>
                                                <td class="" style="padding: 3px 0px 3px 5px !important;">
                                                    {{ $item->subjdesc != null ? $item->subjdesc : null }}</td>
                                                @if ($x == 1)
                                                    <td class="p-0 text-center align-middle">
                                                        {{ $item->quarter1 != null ? $item->quarter1 : '' }}</td>
                                                    <td class="p-0 text-center align-middle">
                                                        {{ $item->quarter2 != null ? $item->quarter2 : '' }}</td>
                                                @elseif($x == 2)
                                                    <td class="p-0 text-center align-middle">
                                                        {{ $item->quarter3 != null ? $item->quarter3 : '' }}</td>
                                                    <td class="p-0 text-center align-middle">
                                                        {{ $item->quarter4 != null ? $item->quarter4 : '' }}</td>
                                                @endif
                                                <td class="text-center align-middle  p-0">
                                                    {{ $item->finalrating != null ? $item->finalrating : '' }}</td>
                                                {{-- <td class="text-center align-middle p-0" style="font-size: 10px!important;">{{$item->actiontaken != null ? $item->actiontaken:''}}</td> --}}
                                            </tr>
                                        @endforeach
                                        <tr>
                                            @php
                                                $genave = collect($finalgrade)->where('semid', $x)->first();
                                            @endphp
                                            <td class="p-0 text-right" style="font-size: 12px!important;"><b>General
                                                    Average for Semester</b>&nbsp;&nbsp;</td>
                                            @if ($x == 1)
                                                <td class="p-0 text-center align-middle">
                                                    {{ isset($genave->quarter1) ? ($genave->quarter1 != null ? $genave->quarter1 : '') : '' }}
                                                </td>
                                                <td class="p-0 text-center align-middle">
                                                    {{ isset($genave->quarter2) ? ($genave->quarter2 != null ? $genave->quarter2 : '') : '' }}
                                                </td>
                                            @elseif($x == 2)
                                                <td class="p-0 text-center align-middle">
                                                    {{ isset($genave->quarter3) ? ($genave->quarter3 != null ? $genave->quarter3 : '') : '' }}
                                                </td>
                                                <td class="p-0 text-center align-middle">
                                                    {{ isset($genave->quarter4) ? ($genave->quarter4 != null ? $genave->quarter4 : '') : '' }}
                                                </td>
                                            @endif
                                            <td class="text-center align-middle  p-0">
                                                {{ isset($genave->finalrating) ? ($genave->finalrating != null ? $genave->finalrating : '') : '' }}
                                            </td>
                                            {{-- <td class="text-center align-middle" style="font-size: 10px!important;">{{$genave->actiontaken != null ? $genave->actiontaken:''}}</td> --}}
                                        </tr>
                                    </table>
                                @endfor
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>
