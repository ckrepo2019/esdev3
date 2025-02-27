<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
    <style>

        .table {
            width: 100%;
            margin-bottom: 0px;
            background-color: transparent;
            font-size:11px ;
        }

        table {
            border-collapse: collapse;
        }
        
        .table thead th {
            vertical-align: bottom;
        }
        
        .table td, .table th {
            padding: .75rem;
            vertical-align: top;
        }
        .table td, .table th {
            padding: .75rem;
            vertical-align: top;
        }
        
        .table-bordered {
            border: 1px solid #00000;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #00000;
        }

        .td-bordered {
            border-right: 1px solid #00000;
        }

        .table-sm td, .table-sm th {
            padding: .3rem;
        }

        .text-center{
            text-align: center !important;
        }
        
        .text-right{
            text-align: right !important;
        }
        
        .text-left{
            text-align: left !important;
        }
        
        .p-0{
            padding: 0 !important;
        }
        .p-1{
            padding-top: 10px!important;
            padding-bottom: 10px!important;
        }
        .pl-3{
            padding-left: 1rem !important;
        }

        .mb-0{
            margin-bottom: 0;
        }

        .border-bottom{
            border-bottom:1px solid black;
        }

        .mb-1, .my-1 {
            margin-bottom: .25rem!important;
        }

        body{
            font-family: Calibri, sans-serif;
        }
        
        .align-middle{
            vertical-align: middle !important;    
        }

         
        .grades td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: .7rem !important;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        .bg-red{
            color: red;
            border: solid 1px black !important;
        }

        td{
            padding-left: 5px;
            padding-right: 5px;
        }
        .aside {
            /* background: #48b4e0; */
            color: #000;
            line-height: 12px;
            height: 50px;
            border: 1px solid #000!important;
            
        }
        .aside span {
            /* Abs positioning makes it not take up vert space */
            /* position: absolute; */
            top: 0;
            left: 0;

            /* Border is the new background */
            background: none;

            /* Rotate from top left corner (not default) */
            transform-origin: 0 50%;
            transform: rotate(-90deg);
        }
        .finalratingside {
            vertical-align: middle;
        }
        .finalratingside div {
           -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
            -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
            -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
                    filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
                -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
                margin-left: -5em;
                margin-right: -5em;

                
                transform-origin: 85 20 ;
        }
        .remarksside {
            
        }
        .remarksside div {
            -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
            -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
            -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
                    filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
                -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
                margin-left: -5em;
                margin-right: -5em;
                transform-origin: 92 21 ;
        }
        .trhead {
            background-color: rgb(167, 223, 167); 
            color: #000; font-size;
        }
        .trhead td {
            border: 1px solid #000;
        }
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 11in 8.5in; margin: 10px 20px;}
        
    </style>
</head>
<body style="">
    <table class="table table-sm mb-0" style="table-layout: fixed;">
        <tr>
            <td width="50%" style="padding-right: 50px;">
                <table style="width: 100%; font-size: 13px; margin-top: 10px;">
                    <tr>
                        <td width="100%" class="p-0 text-center"><b>REPORT ON ATTENDANCE</b></td>
                    </tr>
                </table>
                <table style="width: 100%; margin-top: 10px;">
                    <tr>
                        <td width="100%" class="p-0">
                            @php
                                $width = count($attendance_setup) != 0? 73 / count($attendance_setup) : 0;
                            @endphp
                            <table class="table table-bordered table-sm grades mb-0" width="100%">
                                <tr>
                                    <td width="15%" style="border: 1px solid #000; text-align: center;"></td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle;" width="{{$width}}%"><span style="text-transform: uppercase; font-size: 9px!important">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                                    @endforeach
                                    <td class="text-center" width="10%" style="vertical-align: middle; font-size: 10px!important;"><span>Total</span></td>
                                </tr>
                                <tr class="table-bordered">
                                    <td class="text-center"><b>No. of <br> School <br> days</b></td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                                    @endforeach
                                    <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                                </tr>
                                <tr class="table-bordered">
                                    <td class="text-center"><b>No. of <br> days <br> present</b></td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                                    @endforeach
                                    <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                                </tr>
                                <tr class="table-bordered">
                                    <td class="text-center"><b>No. of <br> days <br> absent</b></td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                                    @endforeach
                                    <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                                </tr>
                            </table>
                            <br>
                            <table style="width: 100%; font-size: 13px; margin-top: 10px;">
                                <tr>
                                    <td width="100%" class="p-0 text-left"><b>PARENT/ GUARDIAN'S SIGNATURE</b></td>
                                </tr>
                            </table>
                            <table width="100%" style="font-size: 12px; margin-top: 10px;">
                                <tr>
                                    <td width="17%" class="p-0 text-left"><b>1<sup>st</sup> Quarter</b></td>
                                    <td width="45%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                                    <td width="38%" class="p-0"></td>
                                </tr>
                            </table>
                            <table width="100%" style="font-size: 12px; margin-top: 10px;">
                                <tr>
                                    <td width="17%" class="p-0 text-left"><b>2<sup>nd</sup> Quarter</b></td>
                                    <td width="45%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                                    <td width="38%" class="p-0"></td>
                                </tr>
                            </table>
                            <table width="100%" style="font-size: 12px; margin-top: 10px;">
                                <tr>
                                    <td width="17%" class="p-0 text-left"><b>3<sup>rd</sup> Quarter</b></td>
                                    <td width="45%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                                    <td width="38%" class="p-0"></td>
                                </tr>
                            </table>
                            <table width="100%" style=" font-size: 12px; margin-top: 10px;">
                                <tr>
                                    <td width="17%" class="p-0 text-left"><b>4<sup>th</sup> Quarter</b></td>
                                    <td width="45%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                                    <td width="38%" class="p-0"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table class="table table-sm mb-0" style="table-layout: fixed;">
                    <tr>
                        <td class="text-left" style="font-size: 10px;">DepED Form 138</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed;">
                    <tr>
                        <td class="text-center p-0" style="font-size: 12px;"><b>Republika ng Pilipinas</b></td>
                    </tr>
                    <tr>
                        <td class="text-center p-0" style="font-size: 13px;"><b>KAGAWARAN NG EDUKASYON</b></td>
                    </tr>
                    <tr>
                        <td class="text-center p-0" style="font-size: 12px;">Rehiyon XI</td>
                    </tr>
                    <tr>
                        <td class="text-center p-0" style="font-size: 12px;">Sangay <u>Davao Oriental</u></td>
                    </tr>
                    <tr>
                        <td class="text-center p-0">
                            <img style="padding-top: 5px;" src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center p-0" style="font-size: 13px; padding-top: 5px!important;"><b>MARYKNOLL SCHOOL OF LAMBAJON, INC.</b></td>
                    </tr>
                    <tr>
                        <td class="text-center p-0" style="font-size: 13px;"><b><i>(Gov. Reg.91, series 1972)</i></b></td>
                    </tr>
                    <tr>
                        <td class="text-center p-0" style="font-size: 12px;">Lambajon, Baganga, 8204 Davao Oriental</td>
                    </tr>
                </table>
                <table class="table table-bordered table-sm mb-0" style="table-layout: fixed; margin-top: 5px;">
                    <tr>
                        <td colspan="2" class="text-left p-0" style="font-size: 12px;">&nbsp;<b><i>Name:</i></b> {{$student->student}}</td>
                    </tr>
                    <tr>
                        <td width="50%" class="text-left p-0" style="font-size: 12px;">&nbsp;<b><i>Age:</i></b> {{$student->age}}</td>
                        <td width="50%" class="text-left p-0" style="font-size: 12px;">&nbsp;<b><i>Sex:</i></b> {{$student->gender}}</td>
                    </tr>
                    <tr>
                        <td width="50%" class="text-left p-0" style="font-size: 12px;">&nbsp;<b><i>Grade:</i></b> {{str_replace('GRADE', '', $student->levelname)}}</td>
                        <td width="50%" class="text-left p-0" style="font-size: 12px;">&nbsp;<b><i>Section:</i></b> {{$student->sectionname}}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-left p-0" style="font-size: 12px;">&nbsp;<b><i>LRN:</i></b> {{$student->lrn}}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-left p-0" style="font-size: 12px;">&nbsp;<b><i>LRN:</i></b> {{$student->lrn}}</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px;">
                    <tr>
                        <td class="text-center p-0" style="font-size: 13px;"><b><u>{{$schoolyear->sydesc}}</u></b></td>
                    </tr>
                    <tr>
                        <td class="text-center p-0" style="font-size: 12px;">School Year</td>
                    </tr>
                </table>
                <table width="100%" style="font-size: 12px;">
                    <tr>
                        <td width="15%" class="p-0">Dear Parent:</td>
                    </tr>
                </table>
                <table width="100%" style="font-size: 12px;">
                    <tr>
                        <td width="10%" class="p-0"></td>
                        <td width="90%" class="p-0">This report card shows the ability and progress your child has made in</td>
                    </tr>
                </table>
                <table width="100%" style="font-size: 12px;">
                    <tr>
                        <td width="100%" class="p-0">the different learning areas well as his/her core values.</td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 12px;">
                    <tr>
                        <td width="10%" class="p-0"></td>
                        <td width="90%" class="p-0">The school welcomes you should you desire to know more aboutyour child's</td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 12px;">
                    <tr>
                        <td width="100%" class="p-0">progress.</td>
                    </tr>
                </table>
                <table width="100%" style="padding-top: 25px; text-align: center;">
                    <tr>
                        <td class="p-0" width="45%" style="font-size: 12px;"></td>
                        <td class="p-0" width="10%" style=""></td>
                        <td class="p-0" width="45%" style="font-size: 12px;"><b><u>{{$adviser}}</u></b></td>
                    </tr>
                    <tr>
                        <td class="p-0" width="45%" style="text-align: center; font-size: 12px;"></td>
                        <td class="p-0" width="10%" style=""></td>
                        <td class="p-0" width="45%" style="text-align: center; font-size: 12px;">Class Adviser</td>
                    </tr>
                </table>
                <table width="100%" style="padding-top: 20px; text-align: center;">
                    <tr>
                        <td class="text-left p-0" width="45%" style="font-size: 12px;"><b><u>REV. FR. REY M. ARMASA, DCM</u></b></td>
                        <td class="p-0" width="10%" style=""></td>
                        <td class="p-0" width="45%" style="font-size: 12px;"></td>
                    </tr>
                    <tr>
                        <td class="text-left p-0" width="45%" style="text-align: center; font-size: 12px; padding-left: 65px!important;"><b><i>Principal</i></b></td>
                        <td class="p-0" width="10%" style=""></td>
                        <td class="p-0" width="45%" style="text-align: center; font-size: 12px;"></td>
                    </tr>
                </table>
                <br>
                <table width="100%">
                    <tr>
                        <td class="text-center" style="font-size: 12px;"><b>Certificate of Transfer</b></td>
                    </tr>
                </table>
                <table width="100%" style="font-size: 12px;">
                    <tr>
                        <td width="21%" class="p-0">Admitted to Grade:</td>
                        <td width="33%" class="p-0 text-center" style="border-bottom: 1px solid #000"><b></b></td>
                        <td width="10%" class="p-0">Section:</td>
                        <td width="36%" class="p-0 text-center" style="border-bottom: 1px solid #000"><b>{{$student->sectionname}}</b></td>
                    </tr>
                </table>
                <table width="100%" style="font-size: 12px;">
                    <tr>
                        <td width="37%" class="p-0 text-left">Eligibility for Admission to Grade:</td>
                        <td width="63%" class="p-0 text-center" style="border-bottom: 1px solid #000"><b></b></td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 12px;">
                    <tr>
                        <td width="100%" class="p-0 text-left">Approved:</td>
                    </tr>
                </table>
                <table width="100%" style="padding-top: 15px; text-align: center;">
                    <tr>
                        <td class="text-left p-0" width="45%" style="font-size: 12px;"><b><u>REV. FR. REY M. ARMASA, DCM</u></b></td>
                        <td class="p-0" width="10%" style=""></td>
                        <td class="p-0" width="45%" style="font-size: 12px;"><b><u>{{$adviser}}</u></b></td>
                    </tr>
                    <tr>
                        <td class="text-left p-0" width="45%" style="text-align: center; font-size: 12px; padding-left: 65px!important;"><b><i>Principal</i></b></td>
                        <td class="p-0" width="10%" style=""></td>
                        <td class="p-0" width="45%" style="text-align: center; font-size: 12px;">Class Adviser</td>
                    </tr>
                </table>
                <br>
                <table style="width: 100%; font-size: 12px;">
                    <tr>
                        <td width="100%" class="p-0 text-center"><b>Cancellation of Eligibility to Transfer</b></td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 12px;">
                    <tr>
                        <td width="14%" class="p-0">Admitted in:</td>
                        <td width="86%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 12px;">
                    <tr>
                        <td width="7%" class="p-0">Date:</td>
                        <td width="25%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                        <td width="28%" class="p-0"></td>
                        <td width="40%" class="p-0 text-center" style="border-bottom: 1px solid #000; font-size: 12px;"><b>{{$principal_info[0]->name}}</b></td>
                    </tr>
                    <tr>
                        <td width="7%" class="p-0"></td>
                        <td width="25%" class="p-0 text-center"></td>
                        <td width="28%" class="p-0"></td>
                        <td width="40%" class="p-0 text-center" style="font-size: 12px;"><b>Principal</b></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="table table-sm mb-0" style="table-layout: fixed;">
        <tr>
            <td width="50%" style="padding-right: 20px;">
                @php
                    $x = 1;
                @endphp
                <table class="table-sm" width="100%" style="font-size: 14px!important;">
                    <tr>
                        <td class="text-center"><b>REPORT ON LEARNING PROGRESS AND <br> ACHIEVEMENT</b></td>
                    </tr>
                </table>
                <table class="table table-sm grades mb-0" width="100%">
                    <tr>
                        <td class="text-left" style="font-size: 12px!important;"><b>First Semester</b></td>
                    </tr>
                </table>
                <table class="table table-bordered table-sm grades mb-0" width="100%">
                    <tr>
                        <td width="60%" rowspan="2"  class="text-center" style="vertical-align: middle!important;"><b>SUBJECTS</b></td>
                        <td width="24%" colspan="2"  class="text-center align-middle" style=""><b>Quarter</b></td>
                        <td width="16%" rowspan="2" class="text-center align-middle" style=""><b>Semester <br>Final Grade</b></td>
                        {{-- <td width="12%" rowspan="2"  class="text-center align-middle" style="font-size: 9px!important;background-color:#FDE9D9;"><b>Remarks</b></td> --}}
                    </tr>
                    <tr>
                        @if($x == 1)
                            <td width="12%" class="text-center align-middle" style=""><b>1</b></td>
                            <td width="12%" class="text-center align-middle" style=""><b>2</b></td>
                        @elseif($x == 2)
                            <td width="12%" class="text-center align-middle" style=""><b>3</b></td>
                            <td width="12%" class="text-center align-middle" style=""><b>4</b></td>
                        @endif
                    </tr>
                    <tr>
                        <td style="text-align: left; padding-bottom: 10px!important;" colspan="4"><b>Core Subjects</b></td>
                    </tr>
                    @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                        <tr>
                            <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                            {{-- <td></td> --}}
                        </tr>
                    @endforeach
                    <tr>
                        <td style="text-align: left; padding-bottom: 10px!important;" colspan="4"><b>Applied and Specialized Subjects</b></td>
                    </tr>
                    @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                        <tr>
                            <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                            {{-- <td></td> --}}
                        </tr>
                    @endforeach
                    {{-- <tr class="trhead">
                        <td style="text-align: left;" colspan="4"><b>CONTEXTUALIZED SUBJECTS</b></td>
                    </tr> --}}
                    {{-- <tr>
                        <td style="text-align: left;" colspan="4"><b>Specialized</b></td>
                    </tr> --}}
                    @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                        <tr>
                            <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                            {{-- <td></td> --}}
                        </tr>
                    @endforeach
                
                    <tr>
                        @php
                            $genave = collect($finalgrade)->where('semid',$x)->first();
                        @endphp
                        <td colspan="3" class="text-right"><b>General Average for the Semester</b></td>
                        <td class="text-right" style="font-weight: bold; font-size: 13px!important; padding-right: 32px;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                        {{-- <td></td> --}}
                    </tr>
                </table>

                {{-- second sem --}}
                @php
                $x = 2;
                @endphp
                <table class="table table-sm grades mb-0" width="100%" style="margin-top: 10px;">
                    <tr>
                        <td class="text-left" style="font-size: 12px!important;"><b>Second Semester</b></td>
                    </tr>
                </table>
                <table class="table table-bordered table-sm grades mb-0" width="100%">
                    <tr>
                        <td width="60%" rowspan="2"  class="text-center" style="vertical-align: middle!important;"><b>SUBJECTS</b></td>
                        <td width="24%" colspan="2"  class="text-center align-middle" style=""><b>Quarter</b></td>
                        <td width="16%" rowspan="2" class="text-center align-middle" style=""><b>Semester <br>Final Grade</b></td>
                        {{-- <td width="12%" rowspan="2"  class="text-center align-middle" style="font-size: 9px!important;background-color:#FDE9D9;"><b>Remarks</b></td> --}}
                    </tr>
                    <tr>
                        @if($x == 1)
                            <td width="12%" class="text-center align-middle" style=""><b>1</b></td>
                            <td width="12%" class="text-center align-middle" style=""><b>2</b></td>
                        @elseif($x == 2)
                            <td width="12%" class="text-center align-middle" style=""><b>3</b></td>
                            <td width="12%" class="text-center align-middle" style=""><b>4</b></td>
                        @endif
                    </tr>
                    <tr>
                        <td style="text-align: left; padding-bottom: 10px!important;" colspan="4"><b>Core Subjects</b></td>
                    </tr>
                    @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                        <tr>
                            <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                            {{-- <td></td> --}}
                        </tr>
                    @endforeach
                    <tr>
                        <td style="text-align: left; padding-bottom: 10px!important;" colspan="4"><b>Applied and Specialized Subjects</b></td>
                    </tr>
                    @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                        <tr>
                            <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                            {{-- <td></td> --}}
                        </tr>
                    @endforeach
                    {{-- <tr class="trhead">
                        <td style="text-align: left;" colspan="4"><b>CONTEXTUALIZED SUBJECTS</b></td>
                    </tr> --}}
                    {{-- <tr>
                        <td style="text-align: left;" colspan="4"><b>Specialized</b></td>
                    </tr> --}}
                    @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                        <tr>
                            <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                            {{-- <td></td> --}}
                        </tr>
                    @endforeach
                
                    <tr>
                        @php
                            $genave = collect($finalgrade)->where('semid',$x)->first();
                        @endphp
                        <td colspan="3" class="text-right"><b>General Average for the Semester</b></td>
                        <td class="text-right" style="font-weight: bold; font-size: 13px!important; padding-right: 32px;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                        {{-- <td></td> --}}
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table class="table-sm" width="100%">
                    <tr>
                        <td colspan="6" class="align-middle text-center" style="font-size: 14px!important;"><b>REPORTS ON LEARNER'S OBSERVED VALUES</b></td>
                    </tr>
                </table>
                <table class="table table-sm" style="font-size: 12px; margin-top: 5px;" width="100%">
                    <tr>
                        <td width="100%" class="p-0">
                            <table class="table-sm table table-bordered" width="100%" style="margin-top: 5px;">
                                <tr>
                                    <td rowspan="2" class="align-middle text-center" style="vertical-align: bottom!important;"><b>Core Values</b></td>
                                    <td rowspan="2" class="align-middle text-center" style="vertical-align: bottom!important;"><b>Behavior Statements</b></td>
                                    <td colspan="4" class="cellRight"><center><b>Quarter</b></center></td>
                                </tr>
                                <tr>
                                    <td width="9%"><center><b>1</b></center></td>
                                    <td width="9%"><center><b>2</b></center></td>
                                    <td width="9%"><center><b>3</b></center></td>
                                    <td width="9%"><center><b>4</b></center></td>
                                </tr>
                                {{-- ========================================================== --}}
                                @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                                    @php
                                        $count = 0;
                                    @endphp
                                    @foreach ($groupitem as $item)
                                        @if($item->value == 0)
                                        @else
                                            <tr >
                                                @if($count == 0)
                                                        <td width="24%" class="text-right align-middle" rowspan="{{count($groupitem)}}">{{$item->group}}&nbsp;&nbsp;&nbsp;</td>
                                                        @php
                                                            $count = 1;
                                                        @endphp
                                                @endif
                                                <td width="40%" class="align-middle">{{$item->description}}</td>
                                                <td class="text-center align-middle">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        {{$item->q1eval == $rvitem->id ? $rvitem->value : ''}}
                                                    @endforeach 
                                                </td>
                                                <td class="text-center align-middle">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        {{$item->q2eval == $rvitem->id ? $rvitem->value : ''}}
                                                    @endforeach 
                                                </td>
                                                <td class="text-center align-middle">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        {{$item->q3eval == $rvitem->id ? $rvitem->value : ''}}
                                                    @endforeach 
                                                </td>
                                                <td class="text-center align-middle">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        {{$item->q4eval == $rvitem->id ? $rvitem->value : ''}}
                                                    @endforeach 
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                                {{-- ========================================================== --}}
                                    
                            </table>
    
                            <table width="100%" class="table-sm mb-0 mt-0" style="margin-top: 20px;">
                                <tr>
                                    <td width="10%" class="p-0"></td>
                                    <td width="15%" class="p-0 text-center"><b>Marking</b></td>
                                    <td width="10%" class="p-0"></td>
                                    <td width="30%" class="p-0"><b>Non-numerical Rating</b></td>
                                    <td width="35%" class="p-0"></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0"></td>
                                    <td width="15%" class="p-0 text-center"><b>AO</b></td>
                                    <td width="10%" class="p-0"></td>
                                    <td width="30%" class="p-0"><b>Always Observed</b></td>
                                    <td width="35%" class="p-0"></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0"></td>
                                    <td width="15%" class="p-0 text-center"><b>SO</b></td>
                                    <td width="10%" class="p-0"></td>
                                    <td width="30%" class="p-0"><b>Sometimes Observed</b></td>
                                    <td width="35%" class="p-0"></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0"></td>
                                    <td width="15%" class="p-0 text-center"><b>RO</b></td>
                                    <td width="10%" class="p-0"></td>
                                    <td width="30%" class="p-0"><b>Rarely Observed</b></td>
                                    <td width="35%" class="p-0"></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0"></td>
                                    <td width="15%" class="p-0 text-center"><b>NO</b></td>
                                    <td width="10%" class="p-0"></td>
                                    <td width="30%" class="p-0"><b>Not Observed</b></td>
                                    <td width="35%" class="p-0"></td>
                                </tr>
                            </table>

                            <table width="100%" class="table-sm mb-0 mt-0" style="margin-top: 20px;">
                                <tr>
                                    <td class="text-left" style="font-size: 12px;"><b>Learner Progress and Achievement</b></td>
                                </tr>
                            </table>
                            <table width="100%" class="table-sm mb-0 mt-0" style="margin-top: 20px;">
                                <tr>
                                    <td width="30%" class="text-left p-0" style="font-size: 12px;"><b>Descriptors</b></td>
                                    <td width="35%" class="text-center p-0" style="font-size: 12px;"><b>Grading Scale</b></td>
                                    <td width="5%" class="p-0"></td>
                                    <td width="30%" class="text-left p-0" style="font-size: 12px;"><b>Remarks</b></td>
                                </tr>
                                <tr>
                                    <td width="30%" class="text-left p-0" style="font-size: 12px;">Outstanding</td>
                                    <td width="35%" class="text-left p-0" style="font-size: 12px; padding-left: 65px!important;">90-100</td>
                                    <td width="5%" class="p-0"></td>
                                    <td width="30%" class="text-left p-0" style="font-size: 12px; padding-left: 45px!important;">Passed</td>
                                </tr>
                                <tr>
                                    <td width="30%" class="text-left p-0" style="font-size: 12px;">Very Satisfactory</td>
                                    <td width="35%" class="text-left p-0" style="font-size: 12px; padding-left: 65px!important;">85-89</td>
                                    <td width="5%" class="p-0"></td>
                                    <td width="30%" class="text-left p-0" style="font-size: 12px; padding-left: 45px!important;">Passed</td>
                                </tr>
                                <tr>
                                    <td width="30%" class="text-left p-0" style="font-size: 12px;">Satisfactory</td>
                                    <td width="35%" class="text-left p-0" style="font-size: 12px; padding-left: 65px!important;">80-84</td>
                                    <td width="5%" class="p-0"></td>
                                    <td width="30%" class="text-left p-0" style="font-size: 12px; padding-left: 45px!important;">Passed</td>
                                </tr>
                                <tr>
                                    <td width="30%" class="text-left p-0" style="font-size: 12px;">Fairly Satisfactory</td>
                                    <td width="35%" class="text-left p-0" style="font-size: 12px; padding-left: 65px!important;">75-79</td>
                                    <td width="5%" class="p-0"></td>
                                    <td width="30%" class="text-left p-0" style="font-size: 12px; padding-left: 45px!important;">Passed</td>
                                </tr>
                                <tr>
                                    <td width="30%" class="text-left p-0" style="font-size: 12px;">Did Not Meet Expectations</td>
                                    <td width="35%" class="text-left p-0" style="font-size: 12px; padding-left: 65px!important;">Below 75</td>
                                    <td width="5%" class="p-0"></td>
                                    <td width="30%" class="text-left p-0" style="font-size: 12px; padding-left: 45px!important;">Failed</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>