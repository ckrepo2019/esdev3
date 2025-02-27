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
       .p-1 {
            padding-top: 3px!important;
            padding-bottom: 3px!important;
       }
       .p-2 {
            padding-top: 8px!important;
            padding-bottom: 8px!important;
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
            transform-origin: 10 10;
            transform: rotate(-90deg);
        }
        .trhead {
            background-color: rgb(167, 223, 167); 
            color: #000; font-size
        }
        .trhead td {
            border: 3px solid #000;
        }
    
        body { 
            margin: 0px 0px;
            padding: 0px 0px;
        }
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 4.9in 6.9in; margin: 0px 0px; 
        }
        
    </style>
</head>
<body style="border-left: 10px solid rgb(0, 56, 0); border-right: 10px solid rgb(0, 56, 0)">
<table width="100%" style="margin: 0px!important; padding: 0px!important;">
    <tr>
        <td>
            <table width="100%" style="table-layout: fixed; margin-top: 20px;">
                <tr>
                     <td width="65%" class="text-center"></td>
                     <td width="35%" class="text-center" style="font-size: 11px; background-color: rgb(0, 56, 0); color: #fff; padding-top: 10px; padding-bottom: 10px;">DepEd Form 138 - A</td>
                </tr>
             </table>
             <table width="100%" style="table-layout: fixed; margin-top: 40px; color: rgb(0, 56, 0);">
                <tr>
                     <td width="15%" class="text-center" style="padding-left: 20px;">
                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="90px">
                     </td>
                     <td width="85%" class="text-center" style="font-size: 12.5px; padding-top: 15px;" valign="middle">
                        <span style="letter-spacing: 1px;"><b>SAINT JOSEPH HIGH SCHOOL OF TALAKAG, INC. <br> BRGY. 2, TALAKAG BUKIDNON</b></span> <br>
                        <span style="font-size: 14px!important;">Academic Year : 2021 - 2022</span>
                    </td>
                </tr>
             </table>
             <table width="100%" style="table-layout: fixed; margin-top: 30px">
                <tr>
                     <td width="100%" class="text-center" style="padding-left: 20px;">
                        <img src="{{base_path()}}/public/assets/images/sjhst/SAINT JOSEPH.png" alt="school" width="140px">
                     </td>
                </tr>
             </table>
             <table width="100%" style="table-layout: fixed; margin-top: 40px; padding-left: 20px; padding-right: 20px; font-size: 16px;">
                <tr>
                     <td width="32%" class="text-left p-0" style="color: rgb(0, 56, 0);">Name of Student:</td>
                     <td width="68%" class="text-left p-0" style="border-bottom: 1px solid rgb(0, 56, 0); color: rgb(0, 56, 0);">{{$student->student}}</td>
                </tr>
             </table>
             <table width="100%" style="table-layout: fixed; margin-top: 8px; padding-left: 20px; padding-right: 20px; font-size: 16px;">
                <tr>
                     <td width="8%" class="text-left p-0" style="color: rgb(0, 56, 0);">LRN:</td>
                     <td width="82%" class="text-left p-0" style="border-bottom: 1px solid rgb(0, 56, 0); color: rgb(0, 56, 0);">{{$student->lrn}}</td>
                </tr>
             </table>
             <table width="100%" style="table-layout: fixed; margin-top: 8px; padding-left: 20px; padding-right: 20px; font-size: 16px;">
                <tr>
                     <td width="34%" class="text-left p-0" style="color: rgb(0, 56, 0);">Year and Section:</td>
                     <td width="67%" class="text-left p-0" style="border-bottom: 1px solid rgb(0, 56, 0); color: rgb(0, 56, 0);">{{$student->levelname}} - {{$student->sectionname}}</td>
                </tr>
             </table>
             <table width="100%" style="table-layout: fixed; margin-top: 8px; padding-left: 20px; padding-right: 20px; font-size: 16px;">
                <tr>
                     <td width="28%" class="text-left p-0" style="color: rgb(0, 56, 0);">Class Adviser:</td>
                     <td width="72%" class="text-left p-0" style="border-bottom: 1px solid #rgb(0, 56, 0); color: rgb(0, 56, 0);">{{$adviser}}</td>
                </tr>
             </table>
             <table width="100%" style="table-layout: fixed; padding-left: 20px; padding-right: 20px; font-size: 16px;">
                <tr>
                     <td width="100%" class="text-center p-0" style="padding-top: 15px!important; color: rgb(0, 56, 0);"><i>Curriculum : K to 12 Basic Education Curriculum</i></td>
                </tr>
             </table>
        </td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; margin-top: 10px;">
    <tr>
        <td valign="top">
            <table width="100%" style="table-layout: fixed;">
                <tr>
                     <td width="1%" class="text-center p-0"></td>
                     <td width="76%" class="text-center p-0">
                        <table class="table table-sm grades mb-0" width="100%"  style="margin-top: 2px;">
                            <thead style="background-color: rgb(0, 56, 0); color: #fff;">
                                <tr>
                                    <td class="align-middle text-left p-1" width="41%"><b>Subjects</b></td>
                                    <td class="text-center align-middle p-1" width="8.5%"><b>1</b></td>
                                    <td class="text-center align-middle p-1" width="8.5%"><b>2</b></td>
                                    <td class="text-center align-middle p-1" width="8.5%"><b>3</b></td>
                                    <td class="text-center align-middle p-1" width="8.5%"><b>4</b></td>
                                    <td class="text-center align-middle p-1" width="9%" style="font-size: 7px!important;"><b>Final <br> Rating </b></td>
                                    <td class="text-center align-middle p-1" width="16%" style="font-size: 8px!important;"><b>Remarks</b></td>
                                </tr>
                            </thead>
                            <tbody style="background-color: rgb(202, 255, 202);">
                                @foreach ($studgrades as $item)
                                <tr>
                                    <td class="text-left p-1" style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}; font-size: 9px!important; border-bottom: 1px solid #000;" >{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                    <td class="text-center align-middle" style="border-bottom: 1px solid rgb(0, 56, 0); border-left: 1px solid #fff;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                    <td class="text-center align-middle" style="border-bottom: 1px solid rgb(0, 56, 0); border-left: 1px solid #fff;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                    <td class="text-center align-middle" style="border-bottom: 1px solid rgb(0, 56, 0); border-left: 1px solid #fff;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                    <td class="text-center align-middle" style="border-bottom: 1px solid rgb(0, 56, 0); border-left: 1px solid #fff;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                    <td class="text-center align-middle" style="border-bottom: 1px solid rgb(0, 56, 0); border-left: 1px solid #fff;">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                                    <td class="text-center align-middle" style="border-bottom: 1px solid rgb(0, 56, 0); border-left: 1px solid #fff;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <table class="table table-sm grades" width="100%"  style="margin-top: 7px;">
                            <tr>
                                <td width="41%" class="text-left p-0" style="font-size: 14px!important; background-color: rgb(202, 255, 202);padding-top: 20px!important; padding-bottom: 5px!important;">
                                    <table class="table table-sm grades" width="100%"  style="">
                                        <tr>
                                            <td><b>General Average</b></td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="43%" class="text-center align-middle p-0" style="border-left: 1px solid #fff; background-color: rgb(202, 255, 202);padding-top: 20px!important;">
                                    <table class="table table-sm grades" width="100%"  style="">
                                        <tr>
                                           <td><b>{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</b></td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="16%" class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}  p-0" style="border: none!important;border-left: 1px solid #fff; background-color: rgb(202, 255, 202);padding-top: 20px!important;">
                                    <table class="table table-sm grades" width="100%"  style="">
                                        <tr>
                                            <td>{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                     </td>
                     <td width="2%" class="text-center p-0"></td>
                     <td width="21%" class="text-center p-0" style="" valign="top">
                        <table class="table table-sm grades" width="100%"  style="">
                            <tr>
                                <td class="align-middle text-right" width="100%" style="font-size: 8px!important;background-color:rgb(0, 56, 0); color: #fff; letter-spacing: 1px; padding-top: 8px!important; padding-bottom: 10px!important;"><b>Report Card</b></td>
                            </tr>
                        </table>
                    </td>
                </tr>
             </table>


             <table width="100%" class="mb-0" style="table-layout: fixed;">
                <tr>
                     <td width="1%" class="text-center p-0"></td>
                     <td width="69%" class="text-center p-0" valign="top">
                        @php
                            $width = count($attendance_setup) != 0? 55 / count($attendance_setup) : 0;
                        @endphp
                        <table style="width: 100%;" class="table table-sm" style="margin-top: 3px!important;">
                            <thead style="background-color: rgb(0, 56, 0); color: #fff;">
                                <tr>
                                    <td width="23%" class="text-left p-1">Attendance</td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle p-1" width="{{$width}}%" style="font-size: 7px!important; text-transform: uppercase;">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</td>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody style="background-color: rgb(202, 255, 202)">
                                <tr class="">
                                    <td class="p-1" style="font-size: 8px!important; border-bottom: 1px dotted rgb(0, 56, 0); border-left: 1px solid #fff;"><b>Total Days of School</b></td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="p-1 text-center align-middle" style="font-size: 8px!important; border-bottom: 1px dotted rgb(0, 56, 0); border-left: 1px solid #fff;">{{$item->days != 0 ? $item->days : '' }}</td>
                                    @endforeach
                                </tr>
                                <tr class="">
                                    <td style="font-size: 9px!important; border-bottom: 1px dotted rgb(0, 56, 0); border-left: 1px solid #fff;"><b>Days Attended</b></td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle" style="font-size: 8px!important; border-bottom: 1px dotted rgb(0, 56, 0); border-left: 1px solid #fff;">{{$item->days != 0 ? $item->present : ''}}</td>
                                    @endforeach
                                    {{-- <th class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</th> --}}
                                </tr>
                                <tr class="">
                                    <td style="font-size: 9px!important; border-left: 1px solid #fff;"><b>Days of Absent</b></td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle" style="font-size: 8px!important; border-left: 1px solid #fff;">{{$item->days != 0 ? $item->absent : ''}}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                        
                        <table style="width: 100%;" class="table table table-sm mb-0 mt-0" style="">
                            <tr>
                                <td class="text-left p-0" width="30%" style="font-size: 15px!important; color: rgb(0, 56, 0); padding-top: 8px!important;">Promoted to</td>
                                <td class="text-left p-0" width="70%" style="border-bottom: 1px solid rgb(0, 56, 0)"></td>
                            </tr>
                        </table>
                     </td>
                     <td width="3%" class="text-center p-0"></td>
                     <td width="27%" class="text-center p-0" style="" valign="top">
                        <table class="table table-sm grades mb-0" width="100%"  style="">
                            <tr>
                                <td class="align-middle text-center" width="100%" style="font-size: 8px!important;background-color:rgb(0, 56, 0); color: #fff; letter-spacing: 1px; padding-top: 4px!important; padding-bottom: 4px!important;">Signature of Parents / <br>Guardians</td>
                            </tr>
                        </table>
                        <table class="table table-sm grades mb-0" width="100%"  style="margin-top: 5px">
                            <tr>
                                <td class="align-middle text-left p-1" width="100%" style="font-size: 8px!important;background-color:rgb(202, 255, 202); color: rgb(0, 56, 0); letter-spacing: 1px;">1st</td>
                            </tr>
                        </table>
                        <table class="table table-sm grades mb-0" width="100%"  style="margin-top: 5px">
                            <tr>
                                <td class="align-middle text-left p-1" width="100%" style="font-size: 8px!important;background-color:rgb(202, 255, 202); color: rgb(0, 56, 0); letter-spacing: 1px;">2nd</td>
                            </tr>
                        </table>
                        <table class="table table-sm grades mb-0" width="100%"  style="margin-top: 5px">
                            <tr>
                                <td class="align-middle text-left p-1" width="100%" style="font-size: 8px!important;background-color:rgb(202, 255, 202); color: rgb(0, 56, 0); letter-spacing: 1px;">3rd</td>
                            </tr>
                        </table>
                        <table class="table table-sm grades mb-0" width="100%"  style="margin-top: 5px">
                            <tr>
                                <td class="align-middle text-left p-1" width="100%" style="font-size: 8px!important;background-color:rgb(202, 255, 202); color: rgb(0, 56, 0); letter-spacing: 1px;">4th</td>
                            </tr>
                        </table>
                    </td>
                </tr>
             </table>
             <table width="100%" style="margin-top: 0!important; margin-bottom: 0!important;">
                <tr>
                     <td width="1%" class="text-center p-0"></td>
                     <td width="92%" class="text-center p-0" valign="top">
                        <table width="100%" class="table mb-0 mt-0" style="margin-top: 3px!important;">
                            <tr>
                                <td class="text-left p-0" width="30%" style="font-size: 15px!important; color: rgb(0, 56, 0);">Advance unit/s in</td>
                                <td class="text-left p-0" width="70%" style="border-bottom: 1px solid rgb(0, 56, 0)"></td>
                            </tr>
                        </table>
                        <table width="100%" class="table mb-0 mt-0" style="margin-top: 3px!important;">
                            <tr>
                                <td class="text-left p-0" width="32%" style="font-size: 15px!important; color: rgb(0, 56, 0);">Deficiency unit/s in</td>
                                <td class="text-left p-0" width="68%" style="border-bottom: 1px solid rgb(0, 56, 0)"></td>
                            </tr>
                        </table>
                        <table width="100%" class="table mb-0 mt-0" style="margin-top: 3px!important;">
                            <tr>
                                <td class="text-left p-0" width="9%" style="font-size: 15px!important; color: rgb(0, 56, 0);">Date</td>
                                <td class="text-left p-0" width="55%" style="border-bottom: 1px solid rgb(0, 56, 0)"></td>
                                <td width="44%" class="text-center p-0"></td>
                            </tr>
                        </table>
                        <table width="100%" class="table mb-0 mt-0" style="margin-top: 20px;">
                            <tr>
                                <td width="6%" class="text-center p-0"></td>
                                <td class="text-center p-0" width="50%" style="border-bottom: 1px solid rgb(0, 56, 0); font-size: 11px; color: rgb(0, 56, 0);"><b>JUNRIEL L. DAROCA</b></td>
                                <td width="12%" class="text-center p-0"></td>
                                <td class="text-center p-0" width="40%" style="border-bottom: 1px solid rgb(0, 56, 0); font-size: 12px; color: rgb(0, 56, 0);">SR. GINA L. DAHAN, MCM</td>
                            </tr>
                            <tr>
                                <td width="6%" class="text-center p-0"></td>
                                <td class="text-center p-0" width="50%" style="font-size: 11px; margin-top: 5px!important; color: rgb(0, 56, 0);">ADVISER</td>
                                <td width="12%" class="text-center p-0"></td>
                                <td class="text-center p-0" width="40%" style="font-size: 12px; margin-top: 5px!important; color: rgb(0, 56, 0);">SCHOOL PRINCIPAL</td>
                            </tr>
                        </table>
                     </td>
                     <td width="7%" class="text-center p-0"></td>
                </tr>
             </table>
        </td>
    </tr>
 </table>
</body>
</html>