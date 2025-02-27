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
            padding-top: 5px !important;
            padding-bottom: 5px !important;
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
            /*font-family: Calibri, sans-serif;*/
        }
        
        .align-middle{
            vertical-align: middle !important;    
        }

         
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
            transform-origin: 35 19;
            /* transform: rotate(-90deg); */
        }
        .trhead {
            background-color: rgb(167, 223, 167); 
            color: #000; font-size;
        }
        .trhead td {
            border: 1px solid #000;
        }
        @page {  
            margin:20px 20px;
            
        }
        
		.centerborder {
            position: absolute;
            height: 117.5px;
            transform-origin: 24.2  37.7;
            transform: rotate(126.2deg);
        }
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 11in 8.5in; margin: 10px 15px;  }
        
    </style>
</head>
<body style="">
<table class="table table-sm mb-0" style="table-layout: fixed;">
    <tr>
        <td width="50%">
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px;">
                <tr>
                    <td width="100%" class="text-center"><b>REMARKS</b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px;">
                <tr>
                    <td width="100%" class="text-left p-0">First Grading</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; padding-top: 0px;">
                <tr>
                    <td width="100%" class="" style="border-bottom: 2px solid #000; padding-top: 3px!important; padding-bottom: 3px!important;">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100%" class="" style="border-bottom: 2px solid #000; padding-top: 3px!important; padding-bottom: 3px!important;">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100%" class="" style="border-bottom: 2px solid #000; padding-top: 3px!important; padding-bottom: 3px!important;">&nbsp;</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; margin-top: 20px;">
                <tr>
                    <td width="100%" class="text-left p-0">Second Grading</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px;">
                <tr>
                    <td width="100%" class="" style="border-bottom: 2px solid #000; padding-top: 3px!important; padding-bottom: 3px!important;">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100%" class="" style="border-bottom: 2px solid #000; padding-top: 3px!important; padding-bottom: 3px!important;">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100%" class="" style="border-bottom: 2px solid #000; padding-top: 3px!important; padding-bottom: 3px!important;">&nbsp;</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; margin-top: 20px;">
                <tr>
                    <td width="100%" class="text-left p-0">Third Grading</td>
                </tr>
                <tr>
                    <td width="100%" class="" style="border-bottom: 2px solid #000; padding-top: 3px!important; padding-bottom: 3px!important;">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100%" class="" style="border-bottom: 2px solid #000; padding-top: 3px!important; padding-bottom: 3px!important;">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100%" class="" style="border-bottom: 2px solid #000; padding-top: 3px!important; padding-bottom: 3px!important;">&nbsp;</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; margin-top: 20px;">
                <tr>
                    <td width="100%" class="text-left p-0">Fourth Grading</td>
                </tr>
                <tr>
                    <td width="100%" class="" style="border-bottom: 2px solid #000; padding-top: 3px!important; padding-bottom: 3px!important;">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100%" class="" style="border-bottom: 2px solid #000; padding-top: 3px!important; padding-bottom: 3px!important;">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100%" class="" style="border-bottom: 2px solid #000; padding-top: 3px!important; padding-bottom: 3px!important;">&nbsp;</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; margin-top: 20px;">
                <tr>
                    <td width="100%" class="text-center p-0"><b>Signature of Parents or Guardian</b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; margin-top: 10px;">
                <tr>
                    <td width="18%" class="text-left p-0">First Grading</td>
                    <td width="82%" class="p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; margin-top: 10px;">
                <tr>
                    <td width="22%" class="text-left p-0">Second Grading</td>
                    <td width="78%" class="p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; margin-top: 10px;">
                <tr>
                    <td width="19%" class="text-left p-0">Third Grading</td>
                    <td width="81%" class="p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; margin-top: 10px;">
                <tr>
                    <td width="21%" class="text-left p-0">Fourth Grading</td>
                    <td width="79%" class="p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>
            </table>
        </td>
        <td width="50%">
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="45%" class="p-0"></td>
                    <td width="7%" class="p-0" style="font-size: 15px;"><b>LRN</b></td>
                    <td width="48%" class="text-center p-0" style="border-bottom: 1px solid #000;font-size: 15px;">&nbsp;</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="p-0" style="font-size: 14px;"><b>DepEd Form 138</b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 15px;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 16px;">
                        <div><b>REPUBLIC OF THE PHILIPPINES</b></div>
                        <div><b>DEPARTMENT OF EDUCATION</b></div>
                        <div><b>REGION IX, ZAMBOANGA PENINSULA</b></div>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 15px;">
                        <div class="p-0" style="width: 100%;"><img style="padding-top: 10px;" src="{{base_path()}}/public/assets/images/sjsfi/logo.png" alt="school" width="90px"></div>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 8px;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 18px; color: green;">
                        <b>Saint Joseph School Foundation, Inc.</b>
                    </td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 18px;">
                        <b>Gov. Camins Avenue, Canelar, Zamboanga City</b>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; margin-top: 15px;">
                <tr>
                    <td width="10%" class="p-0"><b>Name:</b></td>
                    <td width="54%" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 14px!important;">&nbsp;</td>
                    <td width="9%" class="text-center p-0"><b>Age:</b></td>
                    <td width="9%" class="text-center p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    <td width="9%" class="text-center p-0"><b>Sex:</b></td>
                    <td width="9%" class="text-center p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; margin-top: 15px;">
                <tr>
                    <td width="18%" class="p-0"><b>Curriculum:</b></td>
                    <td width="37%" class="text-left p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    <td width="12%" class="text-right p-0"><b>Grade:&nbsp;</b></td>
                    <td width="21%" class="text-center p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    <td width="12%" class="p-0"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; margin-top: 15px;">
                <tr>
                    <td width="19%" class="p-0"><b>School Year:</b></td>
                    <td width="36%" class="p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    <td width="17%" class="text-right p-0"><b>Section:&nbsp;</b></td>
                    <td width="17%" class="p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    <td width="11%" class="p-0"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; margin-top: 25px; padding-left: 40px; padding-right: 20px;">
                <tr>
                    <td width="100%" class="text-center p-0">
                        <div style="height: 50px; border: 1px solid #000; border-radius: 10px!important; padding-top: 8px; background-color: #000; color: #fff;">
                            <div style="font-size: 18px;"><b>REPORT CARD</b></div>
                            <div><b>Elementary Department</b></div>
                        </div>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; margin-top: 10px;">
                <tr>
                    <td width="100%" class="text-center p-0"><b><i>CERTIFICATE OF TRANSFER</i></b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; margin-top: 10px;">
                <tr>
                    <td width="64%" class="text-left p-0"><b><i>Eligible for transfer and admission to Grade</i></b></td>
                    <td width="26%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="p-0"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; margin-top: 20px;">
                <tr>
                    <td width="4%" class="p-0"></td>
                    <td width="45%" class="text-center p-0" style="border-bottom: 1px solid #000; text-transform: uppercase!important;">&nbsp;</td>
                    <td width="51%" class="p-0"></td>
                </tr>
                <tr>
                    <td width="4%" class="p-0"></td>
                    <td width="45%" class="text-center p-0" style="padding-top: 5px!important;"><b><i>Teacher</i></b></td>
                    <td width="51%" class="p-0"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; margin-top: 20px;">
                <tr>
                    <td width="4%" class="p-0"></td>
                    <td width="45%" class="p-0"></td>
                    <td width="51%" class="text-center p-0" style="border-bottom: 1px solid #000;">Sr. Josephine C. Sambrano, O.P., MAEd</td>
                </tr>
                <tr>
                    <td width="4%" class="p-0"></td>
                    <td width="40%" class="p-0"></td>
                    <td width="56%" class="text-center p-0" style="padding-top: 5px!important;"><b><i>Principal</i></b></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table class="table table-sm mb-0" style="table-layout: fixed;">
    <tr>
        <td width="50%" class="p-0" style="padding-right: 15px!important;">
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center" style="font-size: 15px;">
                        <b>PERIODIC RATING</b>
                    </td>
                </tr>
            </table>
            <table width="100%" class="table table-sm table-bordered grades">
                <thead>
                    <tr>
                        <td class="align-middle text-center" width="47%" style="font-size: 13px!important;">Learning Areas</td>
                        <td class="text-center align-middle" width="9.5%" style="font-size: 13px!important;">1</td>
                        <td class="text-center align-middle" width="9.5%" style="font-size: 13px!important;">2</td>
                        <td class="text-center align-middle" width="9.5%" style="font-size: 13px!important;">3</td>
                        <td class="text-center align-middle" width="9.5%" style="font-size: 13px!important;">4</td>
                        <td class="text-center align-middle" width="15%"  style="font-size: 13px!important;">Final <br> Rating</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($studgrades as $item)
                        <tr>
                            <td class="p-0" style="font-size: 11.5px!important; padding: 4px 4px !important;padding-left:{{$item->subjCom != null ? '3rem':'.25rem'}};">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            <td class="text-center align-middle p-0" style="font-size: 12px!important;">&nbsp;</td>
                            <td class="text-center align-middle p-0" style="font-size: 12px!important;">&nbsp;</td>
                            <td class="text-center align-middle p-0" style="font-size: 12px!important;">&nbsp;</td>
                            <td class="text-center align-middle p-0" style="font-size: 12px!important;">&nbsp;</td>
                            <td class="text-center align-middle p-0" style="font-size: 12px!important;">&nbsp;</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-left" style="font-size: 12px!important; font-weight: bold;"><b>GENERAL AVERAGE</b></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-center" style="font-size: 12px!important;"><b></b></td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-sm table-bordered mb-0" style="table-layout: fixed; margin-top: 10px; font-size: 12px; padding-left: 30px; padding-right: 30px;">
                <tr>
                    <td class="text-left p-0" width="45%"><b>&nbsp;&nbsp;Descriptors:</b></td>
                    <td class="text-left p-0" width="30%"><b>&nbsp;&nbsp;Grading Scale</b></td>
                    <td class="text-left p-0" width="25%"><b>&nbsp;&nbsp;Remarks</b></td>
                </tr>
                <tr>
                    <td class="text-left p-0" width="45%">&nbsp;&nbsp;Outstanding</td>
                    <td class="text-left p-0" width="30%" style="padding-left: 30px!important;">90-100</td>
                    <td class="text-left p-0" width="25%" style="padding-left: 20px!important;"><b>Passed</b></td>
                </tr>
                <tr>
                    <td class="text-left p-0" width="45%">&nbsp;&nbsp;Very Satisfactory</td>
                    <td class="text-left p-0" width="30%" style="padding-left: 30px!important;">85-89</td>
                    <td class="text-left p-0" width="25%" style="padding-left: 20px!important;"><b>Passed</b></td>
                </tr>
                <tr>
                    <td class="text-left p-0" width="45%">&nbsp;&nbsp;Satisfactory</td>
                    <td class="text-left p-0" width="30%" style="padding-left: 30px!important;">80-84</td>
                    <td class="text-left p-0" width="25%" style="padding-left: 20px!important;"><b>Passed</b></td>
                </tr>
                <tr>
                    <td class="text-left p-0" width="45%">&nbsp;&nbsp;Fairly Satisfactory</td>
                    <td class="text-left p-0" width="30%" style="padding-left: 30px!important;">75-79</td>
                    <td class="text-left p-0" width="25%" style="padding-left: 20px!important;"><b>Passed</b></td>
                </tr>
                <tr>
                    <td class="text-left p-0" width="45%">&nbsp;&nbsp;Did not Meet Expectation</td>
                    <td class="text-left p-0" width="30%" style="padding-left: 30px!important;">Below 75</td>
                    <td class="text-left p-0" width="25%" style="padding-left: 20px!important;"><b>Failed</b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-left" style="font-size: 13px;">
                        ATTENDANCE RECORD
                    </td>
                </tr>
            </table>
            @php
                $width = count($attendance_setup) != 0? 71 / count($attendance_setup) : 0;
            @endphp
            <table class="table table-bordered table-sm grades mb-0" width="100%">
                <tr>
                    <td class="diagonal" style="padding-top: 13px!important; text-align: center;font-size: 11px!important;">
                        <div style="padding-top: -11px; padding-left: 5px!important;"><b>Month</b></div><br>
                        <div class="centerborder" style="border-right: 1px solid #000">&nbsp;</div>
                        <div style="padding-top: 23px;"><b>Day</b></div>
                    </td>
                    @foreach ($attendance_setup as $item)
                        <td class="aside text-center align-middle;" width="{{$width}}%"><span style="font-size: 12px!important; "><b>{{\Carbon\Carbon::create(null, $item->month)->format('F')}}</b></span></td>
                    @endforeach
                    <td class="text-center p-0" width="9%" style="vertical-align: middle; font-size: 12px!important;"><span style="transform-origin: 18 27; transform: rotate(-90deg); top: 0; bottom: 10;"><b>TOTAL</b></span></td>
                </tr>
                <tr class="table-bordered">
                    <td width="19%" class="text-center" style="font-size: 11px!important;"><b>No. of School <br> Days</b></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-0" style="font-size: 12px!important;">&nbsp;</td>
                    @endforeach
                    <td class="text-center align-middle p-0" style="font-size: 8px!important;">&nbsp;</td>
                </tr>
                <tr class="table-bordered">
                    <td class="text-center"  style="font-size: 11px!important;"><b>No. of School <br> Days Present</b></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-0" style="font-size: 12px!important;">&nbsp;</td>
                    @endforeach
                    <td class="text-center align-middle p-0" style="font-size: 12px!important;">&nbsp;</td>
                </tr>
                <tr class="table-bordered">
                    <td class="text-center" style="font-size: 11px!important;"><b>No. of Times <br> Tardy</b></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-0" style="font-size: 12px!important;">&nbsp;</td>
                    @endforeach
                    <td class="text-center align-middle p-0" style="font-size: 12px!important;">&nbsp;</td>
                </tr>
            </table>
        </td>
        <td width="50%" class="p-0" style="padding-left: 15px!important;">
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 35px;">
                <tr>
                    <td width="100%" class="text-center" style="font-size: 15px;">
                        <b>CHARACTER BUILDING ACTIVITIES</b>
                    </td>
                </tr>
            </table>
            <table class="table-sm table table-bordered mb-0 mt-0" width="100%"  style="table-layout: fixed; margin-top: 3px; font-size: 15px;">
                <tr>
                    <td width="50%" class="text-center" style=""><b>Character Traits</b></td>
                    <td width="12.5%" class="text-center" style=""><b>1</b></td>
                    <td width="12.5%" class="text-center" style=""><b>2</b></td>
                    <td width="12.5%" class="text-center" style=""><b>3</b></td>
                    <td width="12.5%" class="text-center" style=""><b>4</b></td>
                </tr>
                {{-- @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                    @php
                        $count = 0;
                    @endphp
                    @foreach ($groupitem as $item)
                        @if($item->value == 0)
                        @else
                            <tr>
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
                @endforeach --}}
                <tr>
                    <td class="align-middle" style="font-size: 14px; font-weight: bold;">1. Honesty</td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                </tr>
                <tr>
                    <td class="align-middle" style="font-size: 14px; font-weight: bold;">2. Courteousness</td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                </tr>
                <tr>
                    <td class="align-middle" style="font-size: 14px; font-weight: bold;">3. Responsibility</td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                </tr>
                <tr>
                    <td class="align-middle" style="font-size: 14px; font-weight: bold;">4. Resourcefulness and Creativity</td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                </tr>
                <tr>
                    <td class="align-middle" style="font-size: 14px; font-weight: bold;">5. Fairness</td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                </tr>
                <tr>
                    <td class="align-middle" style="font-size: 14px; font-weight: bold;">6. Leadership</td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                </tr>
                <tr>
                    <td class="align-middle" style="font-size: 14px; font-weight: bold;">7. Obedience</td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                </tr>
                <tr>
                    <td class="align-middle" style="font-size: 14px; font-weight: bold;">8. Self-Reliance</td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                </tr>
                <tr>
                    <td class="align-middle" style="font-size: 14px; font-weight: bold;">9. Industry</td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                </tr>
                <tr>
                    <td class="align-middle" style="font-size: 14px; font-weight: bold;">10. Cleanliness and Orderliness</td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                    <td class="text-center align-middle" style="font-size: 10px;"></td>
                </tr>
            </table>
            <!--<table class="table table-sm mb-0 table-bordered" style="table-layout: fixed; margin-top: 3px; font-size: 15px;">-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-center" style=""><b>Character Traits</b></td>-->
            <!--        <td width="12.5%" class="text-center" style=""><b>1</b></td>-->
            <!--        <td width="12.5%" class="text-center" style=""><b>2</b></td>-->
            <!--        <td width="12.5%" class="text-center" style=""><b>3</b></td>-->
            <!--        <td width="12.5%" class="text-center" style=""><b>4</b></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">1. Honesty</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">2. Courteousness</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">3. Responsibility</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">4. Resourcefulness and <br> Creativity</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">5. Fairness</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">6. Leadership</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">7. Obedience</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">8. Self-Reliance</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">9. Industry</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">10. Cleanliness and Orderliness</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--</table>-->
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="100%" class="text-center" style="font-size: 15px;">
                        <b>GUIDELINES FOR RATING</b>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0 table-bordered" style="table-layout: fixed; padding-left: 170px; padding-right: 170px; font-size: 15px;">
                <tr>
                    <td width="40%" class="text-center p-1" style=""><b>A</b></td>
                    <td width="60%" class="text-center p-1" style=""><b>Very Good</b></td>
                </tr>
                <tr>
                    <td width="40%" class="text-center p-1" style=""><b>B</b></td>
                    <td width="60%" class="text-center p-1" style=""><b>Good</b></td>
                </tr>
                <tr>
                    <td width="40%" class="text-center p-1" style=""><b>C</b></td>
                    <td width="60%" class="text-center p-1" style=""><b>Fair</b></td>
                </tr>
                <tr>
                    <td width="40%" class="text-center p-1" style=""><b>D</b></td>
                    <td width="60%" class="text-center p-1" style=""><b>Poor</b></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>