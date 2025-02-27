<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> -->
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
        .tdleft {
            padding-left: 5px!important;
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
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
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
            /* line-height: 10px; */
            height: 30px;
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
            transform-origin: 25 15;
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
            transform-origin: 25 20;
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
            transform-origin: 25 16;
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
        @page { size: 11in 8.5in; margin: 10px 8px 0px;}
        
    </style>
</head>
<body>
    <table width="100%" class="table table-sm mb-0" style="table-layout: fixed;">
        <tr>
            <td width="50%">
            <div style="height: 96%">
                    <table width="100%" class="table tabl table-bordered table-sm mb-0" style="table-layout: fixed;">
                        <tr>
                            <td colspan="5" class="text-center" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;">HEATH AND SAFETY HABITS</td>
                        </tr>
                        <tr>
                            <td width="68%" class="p-0" style="padding-top: 1px!important; padding-bottom: 1px!important;"></td> 
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>1</b></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>2</b></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>3</b></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>4</b></td>
                        </tr>
                        <tr>
                            <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I am neat and clean.</td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        </tr>
                        <tr>
                            <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I have clean fingernails.</td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        </tr>
                        <tr>
                            <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I brush nt teeth every day.</td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        </tr>
                        <tr>
                            <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I make myself pleasant to look at by combing my hair.</td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        </tr>
                        <tr>
                            <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I have clean, healthy skin.</td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        </tr>
                        <tr>
                            <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I take care of my things.</td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        </tr>
                        <tr>
                            <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I am careful with what I do.</td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        </tr>
                        <tr>
                            <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I am careful not to hurt my classmates.</td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        </tr>
                        <tr>
                            <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I listen to directions.</td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        </tr>
                        <tr>
                            <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I make good use of time.</td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        </tr>
                        <tr>
                            <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I work without disturbing others.</td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        </tr>
                        <tr>
                            <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I work neatly.</td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        </tr>
                        <tr>
                            <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I work silently.</td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        </tr>
                        <tr>
                            <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I use my handkerchief properly.</td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        </tr>
                        <tr>
                            <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I keep materials out of my mouth.</td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        </tr>
                        <tr>
                            <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I rest quietly.</td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                            <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        </tr>
                    </table>
                    @php
                        $width = count($attendance_setup) != 0? 74 / count($attendance_setup) : 0;
                    @endphp
                    <table width="100%" class="text-center p-0 table table-bordered table-sm mb-0" style="table-layout: fixed; margin-top: 15px;">
                        <tr>
                            <td><b>ATTENDANCE RECORD</b></td>
                        </tr>
                    </table>
                    <table width="100%" class="table table-bordered table-sm grades mb-0">
                        <tr>
                            <td width="18%" class="p-0" style="border: 1px solid #000; text-align: left; height: 50px; padding-top: 10px!important;">&nbsp;&nbsp;<b>Month</b></td>
                            @foreach ($attendance_setup as $item)
                                <td class="aside p-0" width="{{$width}}%"><span style="font-size: 8px!important;"><b>{{\Carbon\Carbon::create(null,$item->month)->format('F')}}</b></span></td>
                            @endforeach
                            <td width="8%" class="text-center asidetotal p-0" style="font-size: 9px!important;"><span><b>Total</b></span></td>
                        </tr>
                        <tr class="table-bordered">
                            <td class="" style="font-size: 9px!important; height: 20px; vertical-align: middle;">No. of School Days</td>
                            @foreach ($attendance_setup as $item)
                                <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                            @endforeach
                            <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                        </tr>
                        <tr class="table-bordered">
                            <td class="" style="font-size: 9px!important; height: 20px; vertical-align: middle;">Days Present</td>
                            @foreach ($attendance_setup as $item)
                                <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                            @endforeach
                            <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                        </tr>
                        <tr class="table-bordered">
                            <td class="" style="font-size: 9px!important; height: 20px; vertical-align: middle;">No. of days absent</td>
                            @foreach ($attendance_setup as $item)
                                <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                            @endforeach
                            <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                        </tr>
                    </table>
                    <table width="100%" class="text-center p-0 table table-sm mb-0" style="table-layout: fixed; margin: 15px 0px 0px 20px;">
                        <tr>
                            <td>
                                <div style="border: 1px solid #000; border-radius: 10px; padding: 10px 15px 15px 10px;">
                                    <table width="100%" class="text-center p-0 table table-sm mb-0" style="table-layout: fixed;">
                                        <tr>
                                            <td class="text-right p-0" style="font-size: 13px;"><b>ACHIEVEMENT AWARD AND PARENT'S SIGNATURE&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
                                        </tr>
                                    </table> 
                                    <table width="100%" class="text-center p-0 table table-sm mb-0" style="table-layout: fixed; font-size: 12px;">
                                        <tr>
                                            <td width="24%" class="p-0 text-left"><b>First Grading</b></td>
                                            <td width="10%" class="p-0 text-left"><b>:</b></td>
                                            <td width="33%" class="p-0" style="border-bottom: 1px solid #000;"><b></b></td>
                                            <td width="4%" class="p-0"><b></b></td>
                                            <td width="29%" class="p-0" style="border-bottom: 1px solid #000;"><b></b></td>
                                        </tr>
                                        <tr>
                                            <td width="24%" class="p-0 text-left"><b>Second Grading</b></td>
                                            <td width="10%" class="p-0 text-left"><b>:</b></td>
                                            <td width="33%" class="p-0" style="border-bottom: 1px solid #000;"><b></b></td>
                                            <td width="4%" class="p-0"><b></b></td>
                                            <td width="29%" class="p-0" style="border-bottom: 1px solid #000;"><b></b></td>
                                        </tr>
                                        <tr>
                                            <td width="24%" class="p-0 text-left"><b>Third Grading</b></td>
                                            <td width="10%" class="p-0 text-left"><b>:</b></td>
                                            <td width="33%" class="p-0" style="border-bottom: 1px solid #000;"><b></b></td>
                                            <td width="4%" class="p-0"><b></b></td>
                                            <td width="29%" class="p-0" style="border-bottom: 1px solid #000;"><b></b></td>
                                        </tr>
                                        <tr>
                                            <td width="24%" class="p-0 text-left"><b>Fourth Grading</b></td>
                                            <td width="10%" class="p-0 text-left"><b>:</b></td>
                                            <td width="33%" class="p-0" style="border-bottom: 1px solid #000;"><b></b></td>
                                            <td width="4%" class="p-0"><b></b></td>
                                            <td width="29%" class="p-0" style="border-bottom: 1px solid #000;"><b></b></td>
                                        </tr>
                                    </table> 
                                </div>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" class="text-center p-0 table table-sm mb-0" style="table-layout: fixed; font-size: 12px;">
                        <tr>
                            <td width="35%"></td>
                            <td width="30%" style="border-bottom: 1px solid #000;"></td>
                            <td width="35%"></td>
                        </tr>
                        <tr>
                            <td width="35%"></td>
                            <td width="30%" class="text-center" style=""><b><i>Parents Signature</i></b></td>
                            <td width="35%"></td>
                        </tr>
                    </table>
                    <table width="100%" class="text-center p-0 table table-sm mb-0 mt-0" style="table-layout: fixed; font-size: 13px;">
                        <tr>
                            <td width="100%" class="p-0"><b>CERTIFICATE OF TRANSFER/PROMOTION</b></td>
                        </tr>
                    </table>
                    <table width="100%" class="text-center p-0 table table-sm mb-0 mt-0" style="table-layout: fixed; font-size: 12px;">
                        <tr>
                            <td width="36%" class="text-right p-0">Your child will be in&nbsp;</td>
                            <td width="28%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                            <td width="36%" class="text-left p-0">&nbsp;next school year</td>
                        </tr>
                    </table>
                    <table width="100%" class="text-center p-0 table table-sm mb-0" style="table-layout: fixed; font-size: 12px; margin-top: 15px;">
                        <tr>
                            <td width="6%" class="text-left p-0"></td>
                            <td width="19%" class="text-center p-0" style="border-bottom: 1px solid #000;"><b>EVELYN H. UY</b></td>
                            <td width="29%" class="p-0"></td>
                            <td width="29%" class="text-center p-0" style="border-bottom: 1px solid #000;"><b>ANNA RUTH O. SALIBAY</b></td>
                            <td width="17%" class="text-left p-0"></td>
                        </tr>
                        <tr>
                            <td width="6%" class="text-left p-0"></td>
                            <td width="19%" class="text-center p-0" style=""><i>Class Adviser</i></td>
                            <td width="29%" class="p-0"></td>
                            <td width="29%" class="text-center p-0" style=""><i>School Principal</i></td>
                            <td width="17%" class="text-left p-0"></td>
                        </tr>
                    </table>
                </div>
            </td>
            <td width="50%">
                <div style="border: 1px solid #000; height: 96%;">
                    <table width="100%" class="table table-sm mb-0" style="table-layout: fixed;">
                        <tr>
                            <td width="100%" class="text-center" style="font-size: 24px; font-family: 'Times New Roman', Times, serif"><b>Santo Niño High School of Bacolod, Inc.</b></td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="130px" height="120px" style="padding-left: 25px!important;">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center p-0" style="font-family:; font-size: 26px; "><b>MY PROGRESS IN</b></td>
                        </tr>
                        <tr>
                            <td class="text-center p-0" style="font-family:; font-size: 35px; "><b>KINDERGARTEN 2</b></td>
                        </tr>
                        <tr>
                            <td class="text-center p-0" style="font-family:; font-size: 14px; "><b>SCHOOL YEAR {{$schoolyear->sydesc}}</b></td>
                        </tr>
                    </table>
                    <table width="100%" class="table table-sm mb-0" style="table-layout: fixed; padding: 30px 35px 0px 30px;">
                        <tr>
                            <td width="11%" class="p-0" style="font-size: 13px;"><b>NAME:</b></td>
                            <td width="89%" class="p-0" style="font-size: 13px; border-bottom: 1px solid #000;"><b>{{$student->student}}</b></td>
                        </tr>
                    </table>
                    <table width="100%" class="table table-sm mb-0" style="table-layout: fixed; padding: 10px 35px 0px 30px; font-size: 13px;">
                        <tr>
                            <td width="13%" class="p-0" style=""><b>Gender:</b></td>
                            <td width="28%" class="p-0" style="border-bottom: 1px solid #000;"><b>{{$student->gender}}</b></td>
                            <td width="22%" class="p-0" style=""></td>
                            <td width="7%" class="p-0" style=""><b>Age:</b></td>
                            <td width="30%" class="p-0" style="border-bottom: 1px solid #000;"><b>{{$student->age}}</b></td>
                        </tr>
                    </table>
                    <table width="100%" class="table table-sm mb-0" style="table-layout: fixed; padding: 10px 35px 0px 30px; font-size: 13px;">
                        <tr>
                            <td width="11%" class="p-0" style=""><b>LRN:</b></td>
                            <td width="36%" class="p-0" style="border-bottom: 1px solid #000;"><b>{{$student->lrn}}</b></td>
                            <td width="16%" class="p-0" style=""></td>
                            <td width="37%" class="p-0" style=""><b>TEACHER: <u>EVELYN H. UY</u></b></td>
                        </tr>
                    </table>
                    <table width="100%" class="table table-sm mb-0" style="table-layout: fixed; padding: 60px 35px 0px 30px;">
                        <tr>
                            <td width="100%" class="text-center p-0" style="font-size: 13px;"><b>Goal for Achievement in</b></td>
                        </tr>
                        <tr>
                            <td width="100%" class="text-center p-0" style="font-size: 13px;"><b>Kindergarten</b></td>
                        </tr>
                    </table>
                    <table width="100%" class="table table-sm mb-0" style="table-layout: fixed; padding: 60px 35px 0px 30px;">
                        <tr>
                            <td width="50%" class="p-0" style="font-size: 11px;">
                                <div class="text-left">
                                    To the Parents
                                </div><br>
                                <div style="text-align: justify;">
                                    &nbsp;&nbsp;&nbsp;&nbsp;In the kindergarten your child will learn to live, to work, and to play with other children of his age. The kindergarten introduces him to school as a happy place.
                                </div><br>
                                <div style="text-align: justify;">
                                    &nbsp;&nbsp;&nbsp;&nbsp;Inside, you will find a list of goals for achievement desired by the end of the kindergarten year. Children who are past five years old when they enter school may attain these goals earlier in the school year than younger children.
                                </div>
                            </td>
                            <td width="50%" class="text-center p-0" style="font-size: 11px; vertical-align: middle;">
                                <img src="{{base_path()}}/public/assets/images/snhs/1.png" alt="school" width="230px" height="200px" style="padding-left: 25px!important;">
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
    <table width="100%" class="table table-sm mb-0" style="table-layout: fixed;">
        <tr>
            <td width="50%">
                <table width="100%" class="table table-sm mb-0" style="table-layout: fixed;">
                    <tr>
                        <td colspan="5" class="text-center" style="font-size: 14px;"><b>PUPIL'S PERFORMANCE PROGRESS</b></td>
                    </tr>
                </table>
                <table width="100%" class="table table-bordered table-sm mb-0" style="table-layout: fixed;">
                    <tr>
                        <td width="68%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>LEARNING AREAS</b></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>1</b></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>2</b></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>3</b></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>4</b></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>&nbsp;CHRISTIAN LIVING</b></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;Growth in knowledge of Christian Doctrines.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I am courteous and considerate of others.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I accept and respect authority.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I respect the rights and property of others.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I share my goods with others.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I listen while others speak.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>&nbsp;CHRISTIAN LIVING</b></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I can print my name.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I have a good penmanship.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I can successfully follow three verbal direction.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I enjoy books, stories and poems.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I can tell a story in proper sequence.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I can relate my experiences to the group.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I speak clearly and accurately in a pleasant voice.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I use complete sentences.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>&nbsp;MATHEMATICS</b></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I can see likenesses and differences in object, pictures and &nbsp;letters.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I can count objects.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                </table>
                <table width="100%" class="table table-bordered table-sm mb-0" style="table-layout: fixed; margin-top: 30px;">
                    <tr>
                        <td width="68%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>SUBJECTS</b></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>1</b></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>2</b></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>3</b></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>4</b></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>&nbsp;MAPEH</b></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I take part in group singing and recitation.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I try my best to dance with others.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I enjoy singing.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I respond to rhythms.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I can draw. I love artworks.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I have a good posture.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I use art materials correctly.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                </table>
                <table width="100%" class="table table-sm mb-0" style="table-layout: fixed; margin-top: 15px; margin-left: 10px; font-size: 12px;">
                    <tr>
                        <td colspan="2" class="text-left p-0"><b><u>LEGEND</u></b></td>
                    </tr>
                    <tr>
                        <td width="35%" class="text-left p-0"><b>AO - Always Observed</b></td>
                        <td width="65%" class="text-left p-0"><b>RO - Rarely Observed</b></td>
                    </tr>
                    <tr>
                        <td width="35%" class="text-left p-0"><b>SO - Sometimes Observed</b></td>
                        <td width="65%" class="text-left p-0"><b>NO - Not Observed</b></td>
                    </tr>
                </table>
            </td>
            <td width="50%" style="padding-left: 10px!important;">
                <table width="100%" class="table table-bordered table-sm mb-0" style="table-layout: fixed;">
                    <tr>
                        <td colspan="5" style="font-size: 13px;"><b>SOCIAL, PHYSICAL AND EMOTIONAL DEVELOPMENT</b></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I play well with others and am a good sport</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>1</b></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>2</b></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>3</b></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"><b>4</b></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I am dependable.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I take part in informal conversation.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I enjoy work or play.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I accept constructive criticism.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I claim only my share of attention.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I can be left alone.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I am friendly.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I talk with sense.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I refrain from going out often during class hour.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px;">&nbsp;I have confidence in my answers.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I am thoughtful.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I am considerate with my classmates’ fault.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I play well with others and am a good sport.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="68%" class="text-left p-0" style="font-size: 12px; padding-top: 1px!important; padding-bottom: 1px!important;">&nbsp;I am dependable.</td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                        <td width="8%" class="text-center p-0" style="font-size: 13px; padding-top: 1px!important; padding-bottom: 1px!important;"></td>
                    </tr>
                </table>
                <table width="100%" class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px; margin-bottom: 15px;">
                    <tr>
                        <td class="text-center" style="font-size: 16px; font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif"><b>ACADEMIC PROGRESS</b></td>
                    </tr>
                </table>
                <table width="100%" class="table table-sm table-bordered" style="margin-right: .67cm!important;">
                    <thead>
                        <tr>
                            <td width="34%" class="align-middle text-center p-0" style="font-size: 13px!important;"><b>LEARNING<br>AREAS</b></td>
                            <td width="25%" colspan="4"  class="text-center align-middle p-0" style="font-size: 13px!important;"><b>PERIODICAL <br> RATING</b></td>
                            <td width="20%" class="text-center p-0" style="font-size: 13px!important;"><div><b>FINAL <br> RATING</b></div></td>
                            <td width="20%" class="text-center p-0" style="font-size: 13px!important;"><div><b>ACTION <br>TAKEN</b></div></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studgrades as $item)
                            <tr>
                                <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}; font-size: 13px!important;"><b>{{$item->subjdesc!=null ? $item->subjdesc : null}}</b></td>
                                <td class="text-center align-middle" style="font-size: 13px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle" style="font-size: 13px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                <td class="text-center align-middle" style="font-size: 13px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle" style="font-size: 13px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                <td class="text-center align-middle" style="font-size: 13px!important;">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                                <td class="text-center align-middle" style="font-size: 13px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-left" style="font-size: 13px!important; vertical-align: top; padding-top: 0px;"><b>General <br> Average</b></td>
                            <td class="text-left" style="font-size: 10px!important;"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}" style="vertical-align: top; font-size: 10px!important;"><b>{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</b></td>
                            <td class="text-center" style="font-size: 10px!important; vertical-align: top;"><b>{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</b></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    
</body>
</html>