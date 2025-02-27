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
            line-height: 15px;
            height: 35px;
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
            transform-origin: 11 13;
            transform: rotate(-90deg);
        }
        .asidetotal {
            /* background: #48b4e0; */
            color: #000;
            line-height: 15px;
            height: 35px;
            border: 1px solid #000!important;
            
        }
        .asidetotal span {
            /* Abs positioning makes it not take up vert space */
            /* position: absolute; */
            top: 0;
            left: 0;

            /* Border is the new background */
            background: none;

            /* Rotate from top left corner (not default) */
            transform-origin: 16 9;
            transform: rotate(-90deg);
        }
        .asideno {
            /* background: #48b4e0; */
            color: #000;
            line-height: 15px;
            height: 35px;
            border: 1px solid #000!important;
            
        }
        .asideno span {
            /* Abs positioning makes it not take up vert space */
            /* position: absolute; */
            top: 0;
            left: 0;

            /* Border is the new background */
            background: none;

            /* Rotate from top left corner (not default) */
            transform-origin: 24 10;
            transform: rotate(-90deg);
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
        @page { size: 297mm 210mm; margin: .1in .3in;}
        
    </style>
</head>
<body>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="50%" style="height; 100px; vertical-align: top;font-size: 14px;">
            {{-- attendance --}}
            <table style="width: 100%; margin-top: 60px!important;">
                <tr>
                    <td class="p-0" style="font-size: 14px;"><b>REPORT ON ATTENDANCE</b></td>
                </tr>
            </table>
            <table style="width: 100%; margin-top: 40px;">
                <tr>
                    <td  width="100%" class="p-0">
                        @php
                            $width = count($attendance_setup) != 0? 76 / count($attendance_setup) : 0;
                        @endphp
                        <table width="100%" class="table table-bordered table-sm grades mb-0">
                            <tr>
                                <td width="18%" style="border: 1px solid #000; text-align: center; height: 35px;"></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle aside" width="{{$width}}%"><span style="font-size: 14px!important">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                                @endforeach
                                <td width="6%" class="text-center asidetotal" style="font-size: 13px!important;"><span style=""><b>Total</b></span></td>
                            </tr>
                            <tr class="table-bordered">
                                <td class="asideno" style="font-size: 13px!important; height: 45px;"><span>&nbsp;No. of <br> &nbsp;school <br> &nbsp;&nbsp;days</span></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                                @endforeach
                                <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                            </tr>
                            <tr class="table-bordered">
                                <td class="asideno" style="font-size: 13px!important; height: 45px;"><span>&nbsp;No. of <br> &nbsp;&nbsp;days <br> Present</span></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                                @endforeach
                                <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                            </tr>
                            <tr class="table-bordered">
                                <td class="asideno" style="font-size: 13px!important; height: 45px;"><span>&nbsp;No. of <br> &nbsp;&nbsp;days <br> &nbsp;absent</span></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                                @endforeach
                                <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 15px; margin-top: 56px;">
                <tr>
                    <td width="100%" class="p-0 text-left">PARENT/GUARDIAN'S SIGNATURE</td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 14px; margin-top: 56px;">
                <tr>
                    <td width="28%" class="p-0 text-left">1st Quarter</td>
                    <td width="50%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="22%" class="p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 14px; margin-top: 56px;">
                <tr>
                    <td width="28%" class="p-0 text-left">Second Quarter</td>
                    <td width="50%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="22%" class="p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 14px; margin-top: 56px;">
                <tr>
                    <td width="28%" class="p-0 text-left">Third Quarter</td>
                    <td width="50%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="22%" class="p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 14px; margin-top: 56px;">
                <tr>
                    <td width="28%" class="p-0 text-left">Fourth Quarter</td>
                    <td width="50%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="22%" class="p-0"></td>
                </tr>
            </table>
        </td>
        <td width="50%" style="vertical-align: top; padding-left: .5in!important;">
            <table width="100%" class="table mb-0 mt-0" style="table-layout: fixed;">
                <tr>
                    <td width="55%" class="p-0 text-left">DepEd Form 138</td>
                    <td width="45%" class="p-0" style="font-size: 16px; border: 2px solid #000;"><b style="padding-left: 1px;">LRN:</b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0 p-0" style="width: 100%; table-layout: fixed;">
                <tr>
                    <td class="p-0" width="25%" style="text-align: center; vertical-align: middle;">
                        <img style="" src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px">
                    </td>
                    <td width="50%" class="p-0" style="text-align: center; font-size: 12px;">
                        <div style="width: 100%;">Republic of the Philippines</div>
                        <div style="width: 100%;">Department of Education</div>
                        <div style="width: 100%;"><u><b>REGION XI</b></u></div>
                        <div style="width: 100%; padding-top: 3px;">Region</div>
                        <div style="width: 100%; padding-top: 1px;"><u><b>DAVAO ORIENTAL</b></u></div>
                        <div style="width: 100%; padding-top: 3px;">Division</div>
                        <div style="width: 100%; padding-top: 3px;"><u><b>LUPON WEST</b></u></div>
                        <div style="width: 100%; padding-top: 1px;">District</div>
                    </td>
                    <td width="25%">
                    </td>
                </tr>
                <tr>
                    <td colspan="3" width="100%" class="p-0" style="text-align: center; font-size: 12px; padding-top: 20px!important;">
                        <div style="width: 100%; font-size: 17px;"><u><b>{{$schoolinfo[0]->schoolname}}</b></u></div>
                        <div style="width: 100%;">School</div>
                    </td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px; margin-top: 10px;" >
                <tr>
                    <td width="16%" class="p-0">Name:</td>
                    <td width="84%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$student->student}}</b></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="16%" class="p-0">Age:</td>
                    <td width="23%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$student->age}}</b></td>
                    <td width="17%" class="p-0"></td>
                    <td width="9%" class="p-0">Sex:</td>
                    <td width="7%" class="p-0"></td>
                    <td width="28%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$student->gender}}</b></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="16%" class="p-0">Grade:</td>
                    <td width="23%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$student->levelname}}</b></td>
                    <td width="17%" class="p-0"></td>
                    <td width="12%" class="p-0">Section:</td>
                    <td width="4%" class="p-0"></td>
                    <td width="28%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$student->sectionname}}</b></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="16%" class="p-0">School Year:</td>
                    <td width="23%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$schoolyear->sydesc}}</b></td>
                    <td width="17%" class="p-0"></td>
                    <td width="8%" class="p-0">LRN:</td>
                    <td width="8%" class="p-0"></td>
                    <td width="28%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$student->lrn}}</b></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 13px; margin-top: 20px;">
                <tr>
                    <td class="p-0"><i>Dear Parent/Guardian:</i></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="11%" class="p-0"></td>
                    <td width="89%" class="p-0"><i>This report card shows the ability and progress your child has made in</i></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="89%" class="p-0"><i>the different learning areas as well as his/her core values.</i></td>
                    <td width="11%" class="p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="11%" class="p-0"></td>
                    <td width="89%" class="p-0"><i>We would appreciate very much your coming to Maryknoll School of</i></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="89%" class="p-0"><i>Lupon, Inc., to confer with us regarding the progress of your child.</i></td>
                    <td width="11%" class="p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; padding-top: 25px; text-align: center;">
                <tr>
                    <td class="p-0" width="45%" style="font-size: 13px;"></td>
                    <td class="p-0" width="19%" style=""></td>
                    <td class="p-0" width="35%" style="font-size: 13px; border-bottom: 1px solid #000;"><b>{{$adviser}}</b></td>
                </tr>
                <tr>
                    <td class="p-0" width="45%" style="text-align: center; font-size: 13px;"></td>
                    <td class="p-0" width="19%" style=""></td>
                    <td class="p-0" width="35%" style="text-align: center; font-size: 13px;">Adviser</td>
                </tr>
            </table>
            <table style="width: 100%; text-align: center;">
                <tr>
                    {{-- <td class="p-0" width="50%" style="border-bottom: 1px solid #000; font-size: 12px;"><b>{{$principal_info[0]->name}}</b></td> --}}
                    <td class="text-left p-0" width="50%" style="font-size: 13px;"><u><b>SR. MA. DOMITILLA B. SENDINO, O.P.</b></u></td>
                    <td class="p-0" width="5%" style=""></td>
                    <td class="p-0" width="45%" style="font-size: 13px;"></td>
                </tr>
                <tr>
                    <td class="p-0" width="50%" style="text-align: center; font-size: 13px;">Principal</td>
                    <td class="p-0" width="5%" style=""></td>
                    <td class="p-0" width="45%" style="text-align: center; font-size: 13px;"></td>
                </tr>
            </table>
            <table style="width: 100%; margin-top: 10px;">
                <tr>
                    <td class="text-center" style="font-size: 14px;"><b>CERTIFICATE OF TRANSFER</b></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px; margin-top: 5px;">
                <tr>
                    <td width="24%" class="p-0">Admitted to Grade:</td>
                    <td width="16%" class="p-0 text-center" style="border-bottom: 1px solid #000">&nbsp;<b></b></td>
                    <td width="10%" class="p-0">Section:</td>
                    <td width="28%" class="p-0 text-center" style="border-bottom: 1px solid #000"><b></b></td>
                    <td width="22%" class="p-0 text-center"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="41%" class="p-0 text-left">Eligibility for Admission to Grade:</td>
                    <td width="23%" class="p-0 text-center" style="border-bottom: 1px solid #000"><b></b></td>
                    <td width="36%" class="p-0 text-center"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="100%" class="p-0 text-left">Approved:</td>
                </tr>
            </table>
            <table style="width: 100%; padding-top: 15px; text-align: center;">
                <tr>
                    <td class="p-0" width="45%" style="font-size: 13px;"></td>
                    <td class="p-0" width="19%" style=""></td>
                    <td class="p-0" width="35%" style="font-size: 13px; border-bottom: 1px solid #000;"><b>{{$adviser}}</b></td>
                </tr>
                <tr>
                    <td class="p-0" width="45%" style="text-align: center; font-size: 13px;"></td>
                    <td class="p-0" width="19%" style=""></td>
                    <td class="p-0" width="35%" style="text-align: center; font-size: 13px;">Adviser</td>
                </tr>
            </table>
            <table style="width: 100%; text-align: center;">
                <tr>
                    {{-- <td class="p-0" width="50%" style="border-bottom: 1px solid #000; font-size: 12px;"><b>{{$principal_info[0]->name}}</b></td> --}}
                    <td class="text-left p-0" width="50%" style="font-size: 13px;"><u><b>SR. MA. DOMITILLA B. SENDINO, O.P.</b></u></td>
                    <td class="p-0" width="5%" style=""></td>
                    <td class="p-0" width="45%" style="font-size: 13px;"></td>
                </tr>
                <tr>
                    <td class="p-0" width="50%" style="text-align: center; font-size: 13px;">Principal</td>
                    <td class="p-0" width="5%" style=""></td>
                    <td class="p-0" width="45%" style="text-align: center; font-size: 13px;"></td>
                </tr>
            </table>
            <table style="width: 100%; margin-top: 10px;">
                <tr>
                    <td class="text-center" style="font-size: 14px;"><b>CANCELLATION OF ELIGIBILITY TO TRANSFER</b></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px; margin-top: 5px;">
                <tr>
                    <td width="15%" class="p-0 text-left">Admitted in:</td>
                    <td width="33%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="52%" class="p-0 text-center"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="7%" class="p-0 text-left">Date:</td>
                    <td width="27%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="66%" class="p-0 text-center"></td>
                </tr>
            </table>
            <table style="width: 100%; padding-top: 5px; text-align: center; margin-top: 15px;">
                <tr>
                    {{-- <td class="p-0" width="50%" style="border-bottom: 1px solid #000; font-size: 12px;"><b>{{$principal_info[0]->name}}</b></td> --}}
                    <td class="p-0" width="45%" style="font-size: 13px;"></td>
                    <td class="p-0" width="5%" style=""></td>
                    <td class="text-left p-0" width="50%" style="font-size: 13px; border-bottom: 1px solid #000;"><b>SR. MA. DOMITILLA B. SENDINO, O.P.</b></td>
                </tr>
                <tr>
                    <td class="p-0" width="45%" style="text-align: center; font-size: 13px;"></td>
                    <td class="p-0" width="5%" style=""></td>
                    <td class="p-0" width="50%" style="text-align: center; font-size: 13px;">Principal</td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
    <br>
    <table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="50%" style="height; 100px; vertical-align: top;">
            <table style="width: 100%; margin-top: 10px;">
                <tr>
                    <td class="text-center" style="font-size: 14px;"><b>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</b></td>
                </tr>
            </table>
            @php
                $x = 1;
            @endphp
            <table style="width: 100%; margin-top: 10px;">
                <tr>
                    <td class="text-left p-0" style="font-size: 14px;">First Semester</td>
                </tr>
            </table>
            <table width="100%" class="table table-bordered table-sm grades mb-0" style="margin-top: 5px;">
                <tr>
                    <td width="69%" rowspan="2"  class="text-center" style="font-size: 14px!important; vertical-align: middle;"><b>SUBJECTS</b></td>
                    <td width="18%" colspan="2"  class="text-center align-middle" style="font-size: 12px!important;"><b>QUARTER</b></td>
                    <td width="13%" rowspan="2"  class="text-center align-middle" style="font-size: 10px!important;"><b>Semester <br> Final Grade</b></td>
                </tr>
                <tr>
                    @if($x == 1)
                        <td class="text-center align-middle" style="font-size: 12px!important;"><b>1</b></td>
                        <td class="text-center align-middle" style="font-size: 12px!important;"><b>2</b></td>
                    @elseif($x == 2)
                        <td class="text-center align-middle" style="font-size: 12px!important;"><b>3</b></td>
                        <td class="text-center align-middle" style="font-size: 12px!important;"><b>4</b></td>
                    @endif
                </tr>
                {{-- <tr>
                    <td class="p-0" style="text-align: left;font-size: 7px!important;" colspan="4"><b>&nbsp;Core</b></td>
                </tr> --}}
                @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                    <tr>
                        <td style="font-size: 12px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                        @if($x == 1)
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @elseif($x == 2)
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endif
                        <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                    </tr>
                @endforeach
                {{-- <tr>
                    <td class="p-0" style="text-align: left;font-size: 7px!important;" colspan="4"><b>&nbsp;Applied</b></td>
                </tr> --}}
                @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                    <tr>
                        <td style="font-size: 12px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                        @if($x == 1)
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @elseif($x == 2)
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endif
                        <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                    </tr>
                @endforeach
                {{-- <tr>
                    <td class="p-0" style="text-align: left;font-size: 7px!important;" colspan="4"><b>&nbsp;Specialized</b></td>
                </tr> --}}
                @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                    <tr>
                        <td style="font-size: 12px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                        @if($x == 1)
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @elseif($x == 2)
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endif
                        <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                    </tr>
                @endforeach
            
                <tr>
                    @php
                        $genave = collect($finalgrade)->where('semid',$x)->first();
                    @endphp
                    <td class="text-left" colspan="3" style="font-size: 12px!important;">General Average for the Semester</td>
                    <td class="text-center" style="font-weight: bold; font-size: 12px!important;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                </tr>
            </table>

            {{-- second sem --}}
            @php
                $x = 2;
            @endphp
            <table class="mb-0 p-0" style="width: 100%; margin-top: 20px;">
                <tr>
                    <td class="text-left p-0" style="font-size: 14px;">Second Semester</td>
                </tr>
            </table>
            <table width="100%" class="table table-bordered table-sm grades mb-0" style="margin-top: 5px;">
                <tr>
                    <td width="69%" rowspan="2"  class="text-center" style="font-size: 14px!important; vertical-align: middle;"><b>SUBJECTS</b></td>
                    <td width="18%" colspan="2"  class="text-center align-middle" style="font-size: 12px!important;"><b>QUARTER</b></td>
                    <td width="13%" rowspan="2"  class="text-center align-middle" style="font-size: 10px!important;"><b>Semester <br> Final Grade</b></td>
                </tr>
                <tr>
                    @if($x == 1)
                        <td class="text-center align-middle" style="font-size: 12px!important;"><b>1</b></td>
                        <td class="text-center align-middle" style="font-size: 12px!important;"><b>2</b></td>
                    @elseif($x == 2)
                        <td class="text-center align-middle" style="font-size: 12px!important;"><b>3</b></td>
                        <td class="text-center align-middle" style="font-size: 12px!important;"><b>4</b></td>
                    @endif
                </tr>
                {{-- <tr>
                    <td class="p-0" style="text-align: left;font-size: 7px!important;" colspan="4"><b>&nbsp;Core</b></td>
                </tr> --}}
                @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                    <tr>
                        <td style="font-size: 12px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                        @if($x == 1)
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @elseif($x == 2)
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endif
                        <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                    </tr>
                @endforeach
                {{-- <tr>
                    <td class="p-0" style="text-align: left;font-size: 7px!important;" colspan="4"><b>&nbsp;Applied</b></td>
                </tr> --}}
                @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                    <tr>
                        <td style="font-size: 12px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                        @if($x == 1)
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @elseif($x == 2)
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endif
                        <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                    </tr>
                @endforeach
                {{-- <tr>
                    <td class="p-0" style="text-align: left;font-size: 7px!important;" colspan="4"><b>&nbsp;Specialized</b></td>
                </tr> --}}
                @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                    <tr>
                        <td style="font-size: 12px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                        @if($x == 1)
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @elseif($x == 2)
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endif
                        <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                    </tr>
                @endforeach
            
                <tr>
                    @php
                        $genave = collect($finalgrade)->where('semid',$x)->first();
                    @endphp
                    <td class="text-left" colspan="3" style="font-size: 12px!important;">General Average for the Semester</td>
                    <td class="text-center" style="font-weight: bold; font-size: 12px!important;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                </tr>
            </table>
            <table class="mb-0 p-0" style="width: 100%; margin-top: 15px;">
                <tr>
                    <td class="text-left p-0" style="font-size: 14px; padding-top: 5px!important;"><i><b>LEGEND:</b></i></td>
                </tr>
                <tr>
                    <td class="text-left p-0" style="font-size: 12px; padding-top: 5px!important;"><i>Core Subject</i></td>
                </tr>
                <tr>
                    <td class="text-left p-0" style="font-size: 12px; padding-top: 5px!important;"><i>Applied Subject</i></td>
                </tr>
                <tr>
                    <td class="text-left p-0" style="font-size: 12px; padding-top: 5px!important;"><i>Specialized Subject</i></td>
                </tr>
            </table>
        </td>
        <td width="50%" style="height; 100px; vertical-align: top; padding-left: 75px!important;">
            <table class="table table-sm" style="font-size: 12px; margin-top: 10px;" width="100%">
                <tr>
                    <td width="100%" class="p-0">
                        <table class="table-sm" width="100%">
                            <tr>
                                <td colspan="6" class="align-middle text-center p-0" style="font-size: 14px!important;"><b>REPORT ON LEARNER'S OBSERVED VALUES</b></td>
                            </tr>
                        </table>
                        <table class="table-sm table table-bordered mb-0" width="100%" style="margin-top: 10px; font-size: 13px!important;">
                                {{-- <tr>
                                    <td colspan="6" class="align-middle text-center">Reports on Learner's Observed Values</td>
                                </tr> --}}
                                <tr>
                                    <td width="26%" rowspan="2" class="align-middle text-center p-0"><b>Core Values</b></td>
                                    <td width="42%" rowspan="2" class="align-middle text-center p-0"><b>Behavior Statements</b></td>
                                    <td colspan="4" class="cellRight"><center><b>Quarter</b></center></td>
                                </tr>
                                <tr>
                                    <td class="" width="8%"><center><b>1</b></center></td>
                                    <td class="" width="8%"><center><b>2</b></center></td>
                                    <td class="" width="8%"><center><b>3</b></center></td>
                                    <td class="" width="8%"><center><b>4</b></center></td>
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
                                                        <td width="18%" class="align-middle" rowspan="{{count($groupitem)}}">{{$item->group}}</td>
                                                        @php
                                                            $count = 1;
                                                        @endphp
                                                @endif
                                                <td class="align-middle">{{$item->description}}</td>
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
                        </table>
                        <table class="table-sm mb-0 mt-0" width="100%" style="font-size: 13px;">
                            <tr>
                                <td width="100%" class="p-0 text-left"><b>Observed Values</b></td>
                            </tr>
                        </table>
                        <table class="table-sm mb-0 mt-0" width="100%" style="font-size: 12px; margin-top: 5px;">
                            <tr>
                                <td width="26%" class="p-0 text-center"><b>Marking</b></td>
                                <td width="46%" class="text-center p-0"><b>Non-numerical Rating</b></td>
                                <td width="28%" class="p-0"></td>
                            </tr>
                            <tr>
                                <td width="26%" class="p-0 text-center">AO</td>
                                <td width="46%" class="p-0">Always Observed</td>
                                <td width="28%" class="p-0"></td>
                            </tr>
                            <tr>
                                <td width="26%" class="p-0 text-center">SO</td>
                                <td width="46%" class="p-0">Sometimes Observed</td>
                                <td width="28%" class="p-0"></td>
                            </tr>
                            <tr>
                                <td width="26%" class="p-0 text-center">RO</td>
                                <td width="46%" class="p-0">Rarely Observed</td>
                                <td width="28%" class="p-0"></td>
                            </tr>
                            <tr>
                                <td width="26%" class="p-0 text-center">NO</td>
                                <td width="46%" class="p-0">Not Observed</td>
                                <td width="28%" class="p-0"></td>
                            </tr>
                        </table>
                        <table class="table-sm mb-0 mt-0" width="100%" style="font-size: 13px; margin-top: 20px!important;">
                            <tr>
                                <td width="100%" class="p-0 text-left"><b>Learner Progress and Achievement</b></td>
                            </tr>
                        </table>
                        <table class="table-sm mb-0 mt-0" width="100%" style="padding-top: 5px!important;">
                            <tr>
                                <td width="26%" class="p-0 text-center"><b>Descriptors</b></td>
                                <td width="46%" class="p-0 text-center"><b>Grading Scale</b></td>
                                <td width="15%" class="p-0 text-center"><b>Remarks</b></td>
                                <td width="13%" class="p-0"></td>
                            </tr>
                            <tr>
                                <td width="26%" class="p-0">Outstanding</td>
                                <td width="46%" class="p-0 text-center">90-100</td>
                                <td width="15%" class="p-0 text-center">Passed</td>
                                <td width="13%" class="p-0"></td>
                            </tr>
                            <tr>
                                <td width="26%" class="p-0">Very Satisfactory</td>
                                <td width="46%" class="p-0 text-center">85-89</td>
                                <td width="15%" class="p-0 text-center">Passed</td>
                                <td width="13%" class="p-0"></td>
                            </tr>
                            <tr>
                                <td width="26%" class="p-0">Satisfactory</td>
                                <td width="46%" class="p-0 text-center">80-84</td>
                                <td width="15%" class="p-0 text-center">Passed</td>
                                <td width="13%" class="p-0"></td>
                            </tr>
                            <tr>
                                <td width="26%" class="p-0">Fairly Satisfactory</td>
                                <td width="46%" class="p-0 text-center">75-79</td>
                                <td width="15%" class="p-0 text-center">Passed</td>
                                <td width="13%" class="p-0"></td>
                            </tr>
                            <tr>
                                <td width="26%" class="p-0">Did Not Meet Expectations</td>
                                <td width="46%" class="p-0 text-center">Below 75</td>
                                <td width="15%" class="p-0 text-center">Failed</td>
                                <td width="13%" class="p-0"></td>
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