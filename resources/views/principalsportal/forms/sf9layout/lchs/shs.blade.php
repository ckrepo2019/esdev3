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
               font-family:'Times New Roman', Times, serif;
            }
        @page { size: 297mm 210mm; margin: 20px 20px;}
        
    </style>
</head>
<body style="font-family: 'Times New Roman', Times, serif">
    <table class="table table-sm mb-0" style="table-layout: fixed;">
        <tr>
            <td width="50%" style="padding-right: 50px;">
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16px;">
                    <tr>
                        <td width="100%" class="text-center"><b>REPORT ON ATTENDANCE</b></td>
                    </tr>
                </table>
                @php
                    $width = count($attendance_setup) != 0? 78 / count($attendance_setup) : 0;
                @endphp
                <table class="table table-bordered table-sm grades mb-0" width="100%" style="margin-top: 15px; padding-right: 30px;">
                    <tr>
                        <td width="10%" class="p-0" style="border: 1px solid #000; text-align: center; height: 30px;"></td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle p-0" width="{{$width}}%" style="padding-top: 10px!important;"><span style="text-transform: uppercase; font-size: 9px!important;">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                        @endforeach
                        <td class="text-center p-0" width="10%" style="vertical-align: middle; font-size: 10px!important; padding-top: 10px!important;"><span>Total</span></td>
                    </tr>
                    <tr class="table-bordered">
                        <td class="text-left">&nbsp;&nbsp;<b>No. of <br>&nbsp;&nbsp;School <br>&nbsp;&nbsp;days</b></td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                        @endforeach
                        <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td class="text-left">&nbsp;&nbsp;<b>No. of <br>&nbsp;&nbsp;days <br>&nbsp;&nbsp;present</b></td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                        @endforeach
                        <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td class="text-left">&nbsp;&nbsp;<b>No. of <br>&nbsp;&nbsp;days <br> &nbsp;&nbsp;absent</b></td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                        @endforeach
                        <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 13px; margin-top: 50px;">
                    <tr>
                        <td width="100%" class="text-center"><b>Homeroom Remarks & Parent's Signature</b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 12px; margin-top: 30px; padding-left: 10px!important;">
                    <tr>
                        <td width="13%" class="text-left p-0"><b>1<sup>st</sup> Quarter</b></td>
                        <td width="62%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="25%" class="text-left p-0"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 12px; margin-top: 15px; padding-left: 10px!important;">
                    <tr>
                        <td width="14%" class="text-left p-0"><b>2<sup>nd</sup> Quarter</b></td>
                        <td width="61%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="25%" class="text-left p-0"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 12px; margin-top: 15px; padding-left: 10px!important;">
                    <tr>
                        <td width="13%" class="text-left p-0"><b>3<sup>rd</sup> Quarter</b></td>
                        <td width="62%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="25%" class="text-left p-0"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 12px; margin-top: 15px; padding-left: 10px!important;">
                    <tr>
                        <td width="13%" class="text-left p-0"><b>4<sup>th</sup> Quarter</b></td>
                        <td width="62%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="25%" class="text-left p-0"></td>
                    </tr>
                </table>
            </td>
            <td width="50%" style="padding-left: 20px;padding-right: 20px;">
                <table class="table table-sm mb-0" style="table-layout: fixed;">
                    <tr>
                        <td width="20%" style="">
                            <img style="padding-top: 5px;" src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="90px">
                        </td>
                        <td width="60%" class="text-center" style="font-size: 16px;">
                            <div><b>REPUBLIKA NG PILIPINAS</b></div>
                            <div><b>KAGAWARAN NG EDUKASYON</b></div>
                            <div><b>REHIYON X</b></div>
                            <div><b>Sangay ng Lanao del Norte</b></div>
                            <div><b>Distrito ng Lala</b></div>
                        </td>
                        <td width="20%" class="" style="">
                            <img style="padding-top: 5px;" src="{{base_path()}}/public/assets/images/lchs/kagawaran.png" alt="school" width="90px">
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16px; margin-top: 20px;">
                    <tr>
                        <td class="text-center p-0"><b>LANIPAO CATHOLIC HIGH SCHOOL, INC.</b></td>
                    </tr>
                    <tr>
                        <td class="text-center p-0" style="font-family: Arial, Helvetica, sans-serif; font-size: 14.5px;"><b>Lanipao, Lala, Lanao del Norte</b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 12px; margin-top: 70px;">
                    <tr>
                        <td width="10%" class="text-left p-0">Surname:</td>
                        <td width="25%" class="text-center p-0" style="border-bottom: 1px solid #000;"><b>{{$student->lastname}}</b></td>
                        <td width="13%" class="text-center p-0">First Name:</td>
                        <td width="27%" class="text-center p-0" style="border-bottom: 1px solid #000;"><b>{{$student->firstname}}</b></td>
                        <td width="5%" class="text-center p-0">MI:</td>
                        <td width="20%" class="text-left p-0"><b><u>{{$student->middlename[0]}}</u></b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 12px;">
                    <tr>
                        <td width="6%" class="text-left p-0">LRN:</td>
                        <td width="19%" class="text-center p-0" style="border-bottom: 1px solid #000;"><b>{{$student->lrn}}</b></td>
                        <td width="12%" class="text-left p-0" style=""></td>
                        <td width="5%" class="text-left p-0">Age:</td>
                        <td width="6%" class="text-center p-0" style="border-bottom: 1px solid #000;"><b>{{$student->age}}</b></td>
                        <td width="10%" class="text-left p-0" style=""></td>
                        <td width="5%" class="text-left p-0" style="">Sex:</td>
                        <td width="16%" class="text-center p-0" style="border-bottom: 1px solid #000;"><b>{{$student->gender}}</b></td>
                        <td width="21%" class="text-left p-0" style=""></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 12px;">
                    <tr>
                        <td width="17%" class="text-left p-0">Year & Section: </td>
                        <td width="34%" class="text-left p-0" style=""><b><u>{{$student->levelname}}-{{$student->sectionname}}</u></b></td>
                        <td width="14%" class="text-left p-0" style="">School Year:</td>
                        <td width="35%" class="text-left p-0" style=""><u>{{$schoolyear->sydesc}}</u></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 12px;">
                    <tr>
                        <td width="17%" class="text-left p-0">Track/Strand: </td>
                        <td width="83%" class="text-left p-0"><b><u>{{$strandInfo->strandname}}</u></b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 12px; margin-top: 20px;">
                    <tr>
                        <td width="100%" class="text-left p-0"><b>Dear Parents</b></td>
                    </tr>
                    <tr>
                        <td width="100%" class="text-left p-0" style="padding-left: 45px!important;">This report card shows the ability and progress your child has made in the different</td>
                    </tr>
                    <tr>
                        <td width="100%" class="text-left p-0">learning areas as well as his/her core values.</td>
                    </tr>
                    <tr>
                        <td width="100%" class="text-left p-0" style="padding-left: 45px!important;">The School welcomes you should desire to know more about your child's progress.</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 12px; margin-top: 20px;">
                    <tr>
                        <td width="50%" class="text-left p-0"><u>{{$adviser}}</u></td>
                        <td width="50%" class="text-left p-0"><u>ANN V. MARQUEZ</u></td>
                    </tr>
                    <tr>
                        <td width="50%" class="text-left p-0" style="padding-left: 25px!important;">Class Adviser</td>
                        <td width="50%" class="text-left p-0" style="padding-left: 12px!important;">School Principal</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 12px; margin-top: 30px;">
                    <tr>
                        <td width="100%" class="text-center p-0"><b>Certificate of Transfer</b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 12px;">
                    <tr>
                        <td width="20%" class="text-left p-0">Admitted to Grade: </td>
                        <td width="30%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="10%" class="text-center p-0">Section: </td>
                        <td width="20%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="20%" class="text-left p-0" style=""></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 12px;">
                    <tr>
                        <td width="35%" class="text-left p-0">Eligibility for Admission to Grade: </td>
                        <td width="45%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="20%" class="text-left p-0" style=""></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 12px; margin-top: 40px;">
                    <tr>
                        <td width="50%" class="text-left p-0"><u>{{$adviser}}</u></td>
                        <td width="50%" class="text-left p-0"><u>ANN V. MARQUEZ</u></td>
                    </tr>
                    <tr>
                        <td width="50%" class="text-left p-0" style="padding-left: 25px!important;">Class Adviser</td>
                        <td width="50%" class="text-left p-0" style="padding-left: 12px!important;">School Principal</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 12px; margin-top: 50px;">
                    <tr>
                        <td width="100%" class="text-center p-0">Cancellation of Eligibility to Transfer</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 12px;">
                    <tr>
                        <td width="13%" class="text-left p-0">Admitted in: </td>
                        <td width="40%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="47%" class="text-left p-0" style=""></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 12px;">
                    <tr>
                        <td width="100%" class="text-left p-0">Date: </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="table table-sm mb-0" style="table-layout: fixed;">
        <tr>
            <td width="50%" style="padding-right: 40px;">
                @php
                    $x = 1;
                @endphp
                <table class="table-sm" width="100%" style="font-size: 14px!important;">
                    <tr>
                        <td class="text-center"><b>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT <br> Track: </b></td>
                    </tr>
                </table>
                <table class="table table-sm grades mb-0" width="100%">
                    <tr>
                        <td class="text-left" style="font-size: 12px!important;"><b>First Semester</b></td>
                    </tr>
                </table>
                <table class="table table-bordered table-sm grades mb-0" width="100%">
                    <tr>
                        <td width="52%" rowspan="2"  class="text-center" style="vertical-align: middle!important;"><b>SUBJECTS</b></td>
                        <td width="20%" colspan="2"  class="text-center align-middle" style=""><b>Quarter</b></td>
                        <td width="14%" rowspan="2" class="text-center align-middle" style=""><b>Semester <br>Final Grade</b></td>
                        <td width="14%" rowspan="2"  class="text-center align-middle" style="font-size: 10px!important;"><b>Remarks</b></td>
                    </tr>
                    <tr>
                        @if($x == 1)
                            <td width="10%" class="text-center align-middle" style=""><b>1</b></td>
                            <td width="10%" class="text-center align-middle" style=""><b>2</b></td>
                        @elseif($x == 2)
                            <td width="10%" class="text-center align-middle" style=""><b>3</b></td>
                            <td width="10%" class="text-center align-middle" style=""><b>4</b></td>
                        @endif
                    </tr>
                    {{-- <tr>
                        <td style="text-align: left; padding-bottom: 10px!important;" colspan="4"><b>Core Subjects</b></td>
                    </tr> --}}
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
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                        </tr>
                    @endforeach
                    {{-- <tr>
                        <td style="text-align: left; padding-bottom: 10px!important;" colspan="4"><b>Applied and Specialized Subjects</b></td>
                    </tr> --}}
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
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
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
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                        </tr>
                    @endforeach
                
                    <tr>
                        @php
                            $genave = collect($finalgrade)->where('semid',$x)->first();
                        @endphp
                        <td class="text-left"><b>General Average for the Semester</b></td>
                        <td></td>
                        <td></td>
                        <td class="text-center" style="font-weight: bold; font-size: 13px!important;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                        <td class="text-center align-middle" style="font-size: 8px!important;"><b>{{isset($item->actiontaken) ? $item->actiontaken:''}}</b></td>

                    </tr>
                </table>

                @php
                    $x = 2;
                @endphp
                <table class="table table-sm grades mb-0" width="100%" style="margin-top: 30px;">
                    <tr>
                        <td class="text-left" style="font-size: 12px!important;"><b>Second Semester</b></td>
                    </tr>
                </table>
                <table class="table table-bordered table-sm grades mb-0" width="100%">
                    <tr>
                        <td width="52%" rowspan="2"  class="text-center" style="vertical-align: middle!important;"><b>SUBJECTS</b></td>
                        <td width="20%" colspan="2"  class="text-center align-middle" style=""><b>Quarter</b></td>
                        <td width="14%" rowspan="2" class="text-center align-middle" style=""><b>Semester <br>Final Grade</b></td>
                        <td width="14%" rowspan="2"  class="text-center align-middle" style="font-size: 10px!important;"><b>Remarks</b></td>
                    </tr>
                    <tr>
                        @if($x == 1)
                            <td width="10%" class="text-center align-middle" style=""><b>1</b></td>
                            <td width="10%" class="text-center align-middle" style=""><b>2</b></td>
                        @elseif($x == 2)
                            <td width="10%" class="text-center align-middle" style=""><b>3</b></td>
                            <td width="10%" class="text-center align-middle" style=""><b>4</b></td>
                        @endif
                    </tr>
                    {{-- <tr>
                        <td style="text-align: left; padding-bottom: 10px!important;" colspan="4"><b>Core Subjects</b></td>
                    </tr> --}}
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
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                        </tr>
                    @endforeach
                    {{-- <tr>
                        <td style="text-align: left; padding-bottom: 10px!important;" colspan="4"><b>Applied and Specialized Subjects</b></td>
                    </tr> --}}
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
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
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
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                        </tr>
                    @endforeach
                
                    <tr>
                        @php
                            $genave = collect($finalgrade)->where('semid',$x)->first();
                        @endphp
                        <td class="text-left"><b>General Average for the Semester</b></td>
                        <td></td>
                        <td></td>
                        <td class="text-center" style="font-weight: bold; font-size: 13px!important;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                        <td class="text-center align-middle" style="font-size: 8px!important;"><b>{{isset($item->actiontaken) ? $item->actiontaken:''}}</b></td>

                    </tr>
                </table>
            </td>
            <td width="50%" style="padding-right: 30px!important;">
                <table class="table-sm" width="100%">
                    <tr>
                        <td class="align-middle text-center" style="font-size: 14px!important;"><b>REPORT ON LEARNER'S OBSERVED VALUES</b></td>
                    </tr>
                </table>
                <table class="table-sm table table-bordered" width="100%" style="margin-top: 5px; font-size: 12px;">
                    <tr>
                        <td rowspan="2" class="align-middle text-center" style="vertical-align: middle!important;"><b>Core Values</b></td>
                        <td rowspan="2" class="align-middle text-center" style="vertical-align: middle!important;"><b>Behavior Statements</b></td>
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
                                            <td width="24%" class="text-left align-middle" rowspan="{{count($groupitem)}}"><b>&nbsp;&nbsp;{{$item->group}}</b></td>
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
                <table width="100%" class="table-sm mb-0 mt-0" style="margin-top: 20px;padding-left: 20px!important; font-size: 12px;">
                    <tr>
                        <td width="30%" class="text-left p-0"><b>Grading Scale</b></td>
                        <td width="35%" class="text-left p-0"><b>Descriptors</b></td>
                        <td width="35%" class="text-left p-0"><b>Remarks</b></td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-left p-0">&nbsp;&nbsp;&nbsp;&nbsp;A- 90-100</td>
                        <td width="35%" class="text-left p-0">Outstanding</td>
                        <td width="35%" class="text-left p-0">Passed</td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-left p-0">&nbsp;&nbsp;&nbsp;&nbsp;B- 85-89</td>
                        <td width="35%" class="text-left p-0">Satisfactory</td>
                        <td width="35%" class="text-left p-0">Passed</td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-left p-0">&nbsp;&nbsp;&nbsp;&nbsp;C- 80-84</td>
                        <td width="35%" class="text-left p-0">Needs Improvement</td>
                        <td width="35%" class="text-left p-0">Passed</td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-left p-0">&nbsp;&nbsp;&nbsp;&nbsp;D- 75-79</td>
                        <td width="35%" class="text-left p-0">Fairly Satisfactory</td>
                        <td width="35%" class="text-left p-0">Passed</td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-left p-0">&nbsp;&nbsp;&nbsp;&nbsp;Below 75</td>
                        <td width="35%" class="text-left p-0">Did Not Meet Expectation</td>
                        <td width="35%" class="text-left p-0">Failed</td>
                    </tr>
                </table>
                <table width="100%" class="table-sm mb-0 mt-0" style="margin-top: 30px;padding-left: 20px!important; font-size: 12px;">
                    <tr>
                        <td class="text-left p-0"><b>OBSERVED VALUES</b></td>
                    </tr>
                </table>
                <table width="100%" class="table-sm mb-0 mt-0" style="margin-top: 10px;padding-left: 20px!important; font-size: 12px;">
                    <tr>
                        <td width="30%" class="text-left p-0"><b>Marking</b></td>
                        <td width="35%" class="text-left p-0"><b>Non-Numerical Rating</b></td>
                        <td width="35%" class="text-left p-0"><b></b></td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-left p-0">AO</td>
                        <td width="35%" class="text-left p-0">Always Observed</td>
                        <td width="35%" class="text-left p-0"></td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-left p-0">SO</td>
                        <td width="35%" class="text-left p-0">Sometimes Observed</td>
                        <td width="35%" class="text-left p-0"></td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-left p-0">RO</td>
                        <td width="35%" class="text-left p-0">Rarely Observed</td>
                        <td width="35%" class="text-left p-0"></td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-left p-0">NO</td>
                        <td width="35%" class="text-left p-0">Not Observed</td>
                        <td width="35%" class="text-left p-0"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>