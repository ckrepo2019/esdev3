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
        height: 60px;
        border: 1px solid #000!important;
        text-align: left !important;
        float: left !important;
        
    }
    .aside span {
        /* Abs positioning makes it not take up vert space */
        /* position: absolute; */
        -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
        -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
        -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
                filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
            margin-left: -1em;
            margin-right: -1em;
            vertical-align: left!important;
        /* top: 0;
        left: 0; */

        /* Border is the new background */
        background: none;

        /* Rotate from top left corner (not default) */
        transform-origin: 34 8;
        transform: rotate(-90deg);
    }
    .trhead {
        background-color: #cfcfcf; 
        color: #000; font-size;
    }
    .trhead td {
        border: 1px solid #000;
    }
        .trhead td {
            border: 1px solid #000;
        }
        /* @page {  
            margin:20px 20px;
            
        } */
       
		 .check_mark {
            font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            }
        @page { size: 11in 8.5in; margin: .5in .5in;}
        .arials {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif !important;
        }
        .calibris {
            font-family: 'Times New Roman', Times, serif !important;
        }
    </style>
</head>
<body>
<table width="100%" style="table-layout: fixed; page-break-after: always;">
    <tr>
        <td width="50%" class="p-0" style="vertical-align: top; padding-right: .25in!important;">
           <div style="height: 98%; border: 1px solid #000;">
                <table width="100%" class="mb-0 mt-0" style="table-layout: fixed; border: 1px solid #000; padding: 10px; font-size: 12px;">
                    <tr>
                        <td colspan="4" class="text-center calibris" style="font-size: 16px; padding: 10px 10px;"><u>EVALUATION CODE</u></td>
                    </tr>
                    <tr>
                        <td width="6%" class="text-left">O</td>
                        <td width="19%" class="text-center">-</td>
                        <td width="27%" class="text-left">(90-100)</td>
                        <td width="48%" class="text-left">Outstanding</td>
                    </tr>
                    <tr>
                        <td class="text-left">VS</td>
                        <td class="text-center">-</td>
                        <td class="text-left">(85-89)</td>
                        <td class="text-left">Very Satisfactory</td>
                    </tr>
                    <tr>
                        <td class="text-left">S</td>
                        <td class="text-center">-</td>
                        <td class="text-left">(80-84)</td>
                        <td class="text-left">Satisfactory</td>
                    </tr>
                    <tr>
                        <td class="text-left">FS</td>
                        <td class="text-center">-</td>
                        <td class="text-left">(75-79)</td>
                        <td class="text-left">Fairly Satisfactory</td>
                    </tr>
                    <tr>
                        <td class="text-left">D</td>
                        <td class="text-center">-</td>
                        <td class="text-left">(Below 75)</td>
                        <td class="text-left">Did Not Meet Expectations</td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                    </tr>
                </table>
                <table width="100%" class="mb-0 mt-0" style="table-layout: fixed; padding: 10px; font-size: 12px;">
                    <tr>
                        <td colspan="4" class="text-center calibris" style="font-size: 16px;"><u>ATTENDANCE RECORD</u></td>
                    </tr>
                </table>
                @php
                    $width = count($attendance_setup) != 0? 80 / count($attendance_setup) : 0;
                @endphp
                <table class="table table-bordered table-sm grades mb-0 calibris" width="100%" style="table-layout: fixed; padding: 3px; margin-top: 5px;">
                    <tr>
                        <td class="align-middle" style="text-align: center;"><b>Month</b></td>
                        @foreach ($attendance_setup as $item)
                            <td class="aside text-center align-middle;" width="{{$width}}%"><span style="font-size: 12px!important;">{{\Carbon\Carbon::create(null, $item->month)->format('F')}}</span></td>
                        @endforeach
                        <td class="text-center p-0" width="8%" style="vertical-align: middle; font-size: 14px!important;"><span style="transform-origin: 18 28; transform: rotate(-90deg); top: 0; bottom: 10;">Total</span></td>
                    </tr>
                    <tr class="table-bordered">
                        <td width="12%" class="text-center"  style="font-size: 10px!important;">Days <br> of <br> School</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle p-0" style="font-size: 10px!important;">{{$item->days != 0 ? $item->days : '' }}</td>
                        @endforeach
                        <td class="text-center align-middle p-0" style="font-size: 10px!important;">{{collect($attendance_setup)->sum('days')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td class="text-center" style="font-size: 10px!important;">Days Present</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle p-0" style="font-size: 10px!important;">{{$item->days != 0 ? $item->present : ''}}</td>
                        @endforeach
                        <td class="text-center align-middle p-0" style="font-size: 10px!important;">{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td class="text-center" style="font-size: 10px!important;">Days Absent</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle p-0" style="font-size: 10px!important;">{{$item->days != 0 ? $item->absent : ''}}</td>
                        @endforeach
                        <td class="text-center align-middle p-0" style="font-size: 10px!important;">{{collect($attendance_setup)->sum('absent')}}</td>
                    </tr>
                </table>
                <table width="100%" class="mb-0 mt-0 calibris" style="table-layout: fixed; padding: 10px; font-size: 14px; margin-top: 10px;">
                    <tr>
                        <td width="30%" class="p-0 ext-center border-bottom" style="">&nbsp;</td>
                        <td width="34%" class="p-0 text-left" style="">&nbsp;is ready to be admitted to</td>
                        <td width="28%" class="p-0 text-right border-bottom" style="">&nbsp;</td>
                        <td width="8%" class="p-0 text-left" style="">.</td>
                    </tr>
                </table>
                <table class="table mb-0 mt-0" style="width: 100%; table-layout: fixed;">
                    <tr>
                        <td width="100%" class="p-0 text-center calibris" style="font-size: 16px; padding-top: 35px!important;">PARENT'S SIGNATURE</td>
                    </tr>
                </table>
                <table class="table mb-0 mt-0 calibris" style="width: 100%; table-layout: fixed;">
                    <tr>
                        <td width="15%" class="p-0"></td>
                        <td width="45%" class="p-0 text-left" style="font-size: 12px; padding-top: 10px!important;">First Quarter</td>
                        <td width="30%" class="p-0 text-right border-bottom" style="">&nbsp;</td>
                        <td width="10%" class="p-0 text-left" style=""></td>
                    </tr>
                    <tr>
                        <td class="p-0"></td>
                        <td class="p-0 text-left" style="font-size: 12px; padding-top: 5px!important;">Second Quarter</td>
                        <td class="p-0 text-right border-bottom" style="">&nbsp;</td>
                        <td class="p-0 text-left" style=""></td>
                    </tr>
                    <tr>
                        <td class="p-0"></td>
                        <td class="p-0 text-left" style="font-size: 12px; padding-top: 5px!important;">Third Quarter</td>
                        <td class="p-0 text-right border-bottom" style="">&nbsp;</td>
                        <td class="p-0 text-left" style=""></td>
                    </tr>
                    <tr>
                        <td class="p-0"></td>
                        <td class="p-0 text-left" style="font-size: 12px; padding-top: 5px!important;">Fourth Quarter</td>
                        <td class="p-0 text-right border-bottom" style="">&nbsp;</td>
                        <td class="p-0 text-left" style=""></td>
                    </tr>
                </table>
                <table class="table mb-0 mt-0 calibris" style="width: 100%; table-layout: fixed; margin-top: 60px;">
                    <tr>
                        <td width="50%" class="p-0 text-center" style="font-size: 15px;"><b>{{$adviser}}</b></td>
                        <td width="50%" class="p-0"></td>
                    </tr>
                    <tr>
                        <td width="50%" class="p-0 text-center" style="font-size: 12px;"><i>Adviser</i></td>
                        <td width="50%" class="p-0"></td>
                    </tr>
                </table>
                <table class="table mb-0 mt-0 calibris" style="width: 100%; table-layout: fixed;">
                    <tr>
                        <td width="45%" class="p-0"></td>
                        <td width="50%" class="p-0 text-center" style="font-size: 15px;"><b>GLENNE A. RIVERA, PhD</b></td>
                        <td width="5%" class="p-0"></td>
                    </tr>
                    <tr>
                        <td class="p-0"></td>
                        <td class="p-0 text-center" style="font-size: 12px;"><i>Principal</i></td>
                        <td class="p-0"></td>
                    </tr>
                </table>
           </div>
        </td>
        <td width="50%" class="p-0" style="vertical-align: top; padding-left: .20in !important;">
            <div style="height: 98%; border: 1px solid #000; padding: 0px 40px 0px 10px;">
                <table class="table mb-0 mt-0" style="width: 100%; table-layout: fixed;">
                    <tr>
                        <td width="100%" class="p-0 text-center calibris" style="font-size: 16px; padding-top: 30px!important;">CHILD'S PROFILE</td>
                    </tr>
                    <tr>
                        <td width="100%" class="p-0 text-center" style="font-size: 18px; padding-top: 10px!important;">
                            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="150px" height="140px">
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" class="p-0 text-center calibris" style="font-size: 18px; padding-top: 20px!important;"><i>KINDERGARTEN</i></td>
                    </tr>
                    <tr>
                        <td width="100%" class="p-0 text-center" style="font-size: 12px; padding-top: 20px!important;">Recognition No. 018 s. 1981</td>
                    </tr>
                    <tr>
                        <td width="100%" class="p-0 text-center" style="font-size: 11px; padding-top: 20px!important;"><b>PROGRESS REPORT CARD</b></td>
                    </tr>
                    <tr>
                        <td width="100%" class="p-0 text-center" style="font-size: 12px; padding-top: 5px!important;">S.Y. {{$schoolyear->sydesc}}</td>
                    </tr>
                </table>
                <table class="table mb-0 mt-0" style="width: 100%; table-layout: fixed; font-size: 12px; margin-top: 70px;">
                    <tr>
                        <td class="p-0" width="9%">Name:</td>
                        <td class="p-0 border-bottom text-center" width="91%"><b>{{$student->student}}</b></td>
                    </tr>
                </table>
                <table width="100%" class="table mb-0 mt-0" style="table-layout: fixed; font-size: 12px; margin-top: 20px;">
                    <tr>
                        <td class="p-0" width="23%">Grade & Section:</td>
                        <td class="p-0 border-bottom text-center" width="77%"><b>{{str_replace('GRADE', '', $student->levelname)}} - {{$student->sectionname}}</b></td>
                    </tr>
                </table>
                <table width="100%" class="table mb-0 mt-0" style="table-layout: fixed; font-size: 12px; margin-top: 20px;">
                    <tr>
                        <td class="p-0" width="13%">LRN No.</td>
                        <td class="p-0 border-center border-bottom" width="30%"><b>{{$student->lrn}}</b></td>
                        <td class="p-0" width="57%">&nbsp;</td>
                    </tr>
                </table>
                <table width="100%" class="table mb-0 mt-0" style="table-layout: fixed; font-size: 12px; margin-top: 20px;">
                    <tr>
                        <td class="p-0" width="100%">
                            <span style="position: absolute; right: 8; bottom: 15;"><img src="{{base_path()}}/public/assets/images/sait/sait_iso.png" alt="school" width="160px" height="80px"></span>
                        </td>
                    </tr>
                </table>
           </div>
        </td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="50%" class="p-0" style="vertical-align: top; padding-right: .25in!important;">
            <table class="table-sm table mb-0 mt-0 calibris" width="100%"  style="table-layout: fixed; margin-top: 3px; font-size: 15px;">
                <tr>
                    <td colspan="5" class="p-0 calibris text-center"><b>ACADEMIC PROGRESS</b></td>
                </tr>
            </table>
            <table width="100%" class="table-bordered mb-0 calibris" style="table-layout: fixed; margin-top: 10px; font-size: 14px;">
                <thead>
                    <tr>
                        <td class="text-center" width="32%" style="" valign="top">Subject Areas</td>
                        {{-- <td colspan="4"  class="text-center align-middle" style="font-size: 12px!important;"><b>QUARTER</b></td> --}}
                        <td class="text-center" width="12%" style="" valign="top">1</td>
                        <td class="text-center" width="12%" style="" valign="top">2</td>
                        <td class="text-center" width="12%" style="" valign="top">3</td>
                        <td class="text-center" width="12%" style="" valign="top">4</td>
                        <td class="text-center" width="20%" style="">Final <br> Rating</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($studgrades as $item)
                        <tr>
                            <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}; font-size: 11px!important; padding: 1px 0px 1px 4px !important">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            <td class="text-center align-middle">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="table-sm table table-bordered mb-0 mt-0" width="100%"  style="table-layout: fixed; margin-top: 3px; font-size: 12px; margin-top: 30px;">
                <tr>
                    <td colspan="5" class="p-0 calibris text-center" style=" font-size: 15px;"><b>GROWTH IN DESIRABLE TRAITS AND HABITS</b></td>
                </tr>
                <tr>
                    <td width="66 %" class="p-0 text-left"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A. &nbsp;&nbsp;AWARENESS AND LOVE OF GOD</b></td>
                    <td width="8.5%" class="p-0"></td>
                    <td width="8.5%" class="p-0"></td>
                    <td width="8.5%" class="p-0"></td>
                    <td width="8.5%" class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Prays Independently</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Participates in prayers</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Attend mass regularly</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Shows Knowledge and love of God</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Shows willingness to pray</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Respects God when praying</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td colspan="5" class="p-0 text-left"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;B. &nbsp;&nbsp;HABITS AND ATTITUDES</b></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Comes to school on time and regularly</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Respects parents, teachers and classmates</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Works without disturbing others</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Take good care of books and other school materials</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Listen to stories attentively</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Obey authorities</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td colspan="5" class="p-0 text-left"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C. &nbsp;&nbsp;SOCIAL AND EMOTIONAL DEVELOPMENT</b></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Shows self-confidence</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Shows self-control</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Accepts responsibilities</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Can interact/play with adults</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Participates in group activities</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Respects the rights and properties of others</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>

                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Knows how to appreciate others</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Comforts playmates in distress</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Shares toys with others</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
            </table>
        </td>
        <td width="50%" class="p-0" style="vertical-align: top; padding-left: .25in!important;">
            <table class="table-sm table table-bordered mb-0 mt-0" width="100%"  style="table-layout: fixed; margin-top: 3px; font-size: 12px; margin-top: 30px;">
                <tr>
                    <td width="64%" class="p-0 text-left"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;D. &nbsp;&nbsp;INTELLECTUAL DEVELOPMENT</b></td>
                    <td width="9%" class="p-0">&nbsp;&nbsp;1</td>
                    <td width="9%" class="p-0">&nbsp;&nbsp;2</td>
                    <td width="9%" class="p-0">&nbsp;&nbsp;3</td>
                    <td width="9%" class="p-0">&nbsp;&nbsp;4</td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Can identify sounds of the animals</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Can identify the letters of the alphabet</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Can utter the vowel and consonant sounds</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Can trace letters and objects</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Can write without assistance</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Can follow instructions/speaks clearly and distinctly</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Recognizes numbers in symbol</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;a. One to ten</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;b. More than ten</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td colspan="5" class="p-0 text-left"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E. &nbsp;&nbsp;CREATIVE AND AESTHETIC DEVELOPMENT</b></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Enjoys art activities</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Participates in art ability</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Shows creative ability</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Shows interest in drawing and painting</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Enjoys rhymes and poems</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Participates in action songs</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Enjoys singing / dancing</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td colspan="5" class="p-0 text-left"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;F. &nbsp;&nbsp;HEALTH AND PHYSICAL GROWTH</b></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Does work carefully</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Kepps oneself neat and clean</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Keeps things in order</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left">&nbsp;&nbsp;Participates in simple exercises</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>