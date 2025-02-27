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
            padding-bottom: .4px !important;
            padding-top: .4px !important;
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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;

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
            color: #000;
            line-height: 15px;
            height: 35px;
            border: 1px solid #000!important;
            
        }
        .aside span {
            top: 0;
            left: 0;
            background: none;
            transform-origin: 14 26;
            transform: rotate(-90deg);
        }
        .asidetotal {
            color: #000;
            line-height: 15px;
            height: 35px;
            border: 1px solid #000!important;
        }
    
        .trhead {
            background-color: rgb(167, 223, 167); 
            color: #000; font-size;
        }
        .trhead td {
            border: 1px solid #000;
        }
		 .check_mark {
               font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            }
        @page { size: 8.5in 11in; margin: 20px 40px 0px 40px;}
        .secondpage {
            position: absolute;
            top: 0%;
            left: 10%;
            width: 27cm;
            transform: rotate(-90deg);
            -webkit-transform: rotate(-90deg); 
            -moz-transform:rotate(-90deg);
        }
    </style>
</head>
<body>
    <table width="100%" style="table-layout: fixed;">
        <tr>
            <td width="33.3333333333" class="text-left" style="font-size: 8px; vertical-align: top;"><b>SF9 Kindergarten</b></td>
            <td width="33.3333333333" class="text-center">
                <div>
                    <img style="padding-top: 4px;" src="{{base_path()}}/public/assets/images/hchs/head.png" alt="school" width="250px">
                </div>
                <div style="font-size: 15px; padding-top: 10px;">
                    <b>Kindergarten Progress Report</b>
                </div>
                <div style="font-size: 13.5px;">
                    <b>School Year {{$schoolyear->sydesc}}</b>
                </div>
            </td>
            <td width="33.3333333333"></td>
        </tr>
    </table>
    <table width="100%" style="table-layout: fixed; font-size: 14px;">
        <tr>
            <td width="5%" class="p-0">LRN:</td>
            <td width="25%" class="p-0 border-bottom"></td>
            <td width="70%" class="p-0"></td>
        </tr>
    </table>
    <table width="100%" style="table-layout: fixed; font-size: 14px;">
        <tr>
            <td width="6%" class="p-0">Name:</td>
            <td width="48%" class="p-0 border-bottom"></td>
            <td width="16%" class="p-0 text-right">Grade & Section:</td>
            <td width="30%" class="p-0 border-bottom"></td>
        </tr>
    </table>
    <table width="100%" style="table-layout: fixed; font-size: 14px;">
        <tr>
            <td width="5%" class="p-0">Age:</td>
            <td width="10%" class="p-0 border-bottom"></td>
            <td width="5%" class="p-0 text-center">Sex:</td>
            <td width="17%" class="p-0 border-bottom"></td>
            <td width="63%" class="p-0"></td>
        </tr>
    </table>
    <table width="100%" style="table-layout: fixed; font-size: 12px; margin-top: 10px;">
        <tr>
            <td class="p-0 text-left"><b>Dear Parent</b></td>
        </tr>
    </table>
    <table width="100%" style="table-layout: fixed; font-size: 12px;">
        <tr>
            <td class="p-0" style="font-size: 10px!important; text-indent: 48px; text-align: justify;">
                When you enrolled your child in our school, we got ourselves committed to the task of 
                helping him/her develop his/her potentials so that he/she become to be according to the 
                gifts that God has given him/her. This development can be maximized only when the 
                home and school work are intently unified toward the best interest of the child.
            </td>
        </tr>
        <tr>
            <td class="p-0" style="font-size: 10px!important; text-indent: 48px; text-align: justify;">
                This report card is issued four times a year. It is requested that you give a careful study so 
                that adequate and relevant help can be given to the child whenever needed.
            </td>
        </tr>
        <tr>
            <td class="p-0" style="font-size: 10px!important; text-indent: 48px; text-align: justify;">
                Please feel free to visit the school and confer with the Directress, Principal, and Classroom 
                Teacher over the difficulty pertaining the school of your child.
            </td>
        </tr>
    </table>
    <table width="100%" style="table-layout: fixed; font-size: 13px; margin-top: 20px; padding: 0px 20px 0px 20px;">
        <tr>
            <td width="30%" class="p-0 text-left"><u><b>JUVY BENDISULA LINGUIZ</b></u></td>
            <td width="40%" class="p-0"></td>
            <td width="30%" class="p-0 text-right"><u><b>{{$adviser}}</b></u></td>
        </tr>
        <tr>
            <td width="30%" class="p-0 text-left" style="text-indent: 16%;">School Principal</td>
            <td width="40%" class="p-0"></td>
            <td width="30%" class="p-0 text-right" style="padding-right: 23%!important;">Adviser</td>
        </tr>
    </table>
    <table width="100%" style="table-layout: fixed; font-size: 14px; margin-top: 10px;">
        <tr>
            <td width="100%" class="p-0 text-center"><b>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</b></td>
        </tr>
        <tr>
            <td width="100%" class="p-0 text-center" style="font-size: 11.5px!important;">Grading System Used: <i><b>Averaging</b></i></td>
        </tr>
    </table>
    <table width="100%" style="table-layout: fixed; font-size: 14px; margin-top: 10px;">
        <tr>
            <td width="45%" style="padding-right: 15px; vertical-align: top;">
                <table width="100%" class="table table-sm table-bordered grades mb-0" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <td rowspan="2"  class="align-middle text-center" width="40%" style="font-size: 9px!important;"><b>Learning Areas</b></td>
                            <td colspan="4"  class="text-center align-middle" style="font-size: 9px!important;"><b>QUARTERLY RATING</b></td>
                            <td rowspan="2" class="text-center align-middle" width="14%"  style="font-size: 9px!important;"><b>FINAL <br> GRADE</b></b></td>
                            <td rowspan="2"  class="text-center align-middle" width="16%" style="font-size: 9px!important;"><b>ACTION <br> TAKEN</b></span></td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle" width="7.5%" style="font-size: 9px!important;"><b>1</b></td>
                            <td class="text-center align-middle" width="7.5%" style="font-size: 9px!important;"><b>2</b></td>
                            <td class="text-center align-middle" width="7.5%" style="font-size: 9px!important;"><b>3</b></td>
                            <td class="text-center align-middle" width="7.5%" style="font-size: 9px!important;"><b>4</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studgrades as $item)
                            <tr>
                                <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}; font-size: 9px!important; padding-top: 10px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                <td class="text-center align-middle" style="font-size: 9px!important;">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                                <td class="text-center align-middle" style="font-size: 9px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" class="text-right" style="font-size: 10px!important;"><b>General Average:</b></td>
                            <td colspan="2" class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}" style="font-size: 10px!important;">{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</td>
                            {{-- <td class="text-center align-middle" style="font-size: 10px!important;">{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</td> --}}
                        </tr>
                    </tbody>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 11px; margin-top: -2px; border: 1px solid #000;">
                    <tr>
                        <td  class="text-center" style="padding-top: 10px;"><b>PARENT'S/GUARDIAN'S SIGNATURE</b></td>
                    </tr>
                    <tr>
                        <td class="p-0" style="padding-left: 5px!important;">
                            <table width="100%" style="table-layout: fixed; font-size: 11px; padding: 10px 0px 0px 0px;">
                                <tr>
                                    <td width="30%" class="text-left p-0" style="font-size: 10px!important;"><b>1<sup>st</sup> Quarter</b></td>
                                    <td width="70%" class="text-left p-0 border-bottom"></td>
                                </tr>
                            </table>
                            <table width="100%" style="table-layout: fixed; font-size: 11px; padding: 10px 0px 0px 0px;">
                                <tr>
                                    <td width="30%" class="text-left p-0" style="font-size: 10px!important;"><b>2<sup>nd</sup> Quarter</b></td>
                                    <td width="70%" class="text-left p-0 border-bottom"></td>
                                </tr>
                            </table>
                            <table width="100%" style="table-layout: fixed; font-size: 11px; padding: 10px 0px 0px 0px;">
                                <tr>
                                    <td width="30%" class="text-left p-0" style="font-size: 10px!important;"><b>3<sup>rd</sup> Quarter</b></td>
                                    <td width="70%" class="text-left p-0 border-bottom"></td>
                                </tr>
                            </table>
                            <table width="100%" style="table-layout: fixed; font-size: 11px; padding: 10px 0px 0px 0px;">
                                <tr>
                                    <td width="30%" class="text-left p-0" style="font-size: 10px!important;"><b>4<sup>th</sup> Quarter</b></td>
                                    <td width="70%" class="text-left p-0 border-bottom"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    {{-- <tr>
                        <td width="30%" class="text-left p-0" style="font-size: 10px!important;"><b>1<sup>st</sup> Quarter</b></td>
                        <td width="70%" class="text-left p-0 border-bottom"></td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-left p-0" style="font-size: 10px!important;"><b>2<sup>nd</sup> Quarter</b></td>
                        <td width="70%" class="text-left p-0 border-bottom"></td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-left p-0" style="font-size: 10px!important;"><b>3<sup>rd</sup> Quarter</b></td>
                        <td width="70%" class="text-left p-0 border-bottom"></td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-left p-0" style="font-size: 10px!important;"><b>4<sup>th</sup> Quarter</b></td>
                        <td width="70%" class="text-left p-0 border-bottom"></td>
                    </tr>
                    <br> --}}
                </table>
            </td>
            <td width="55%" style="vertical-align: top;">
                <table class="table-sm table table-bordered mb-0 mt-0" width="100%"  style="table-layout: fixed; font-size: 11px;">
                    <tr>
                        <td colspan="7" class="p-0 text-center" style="font-size: 9px!important;"><b>GROWTH IN VALUES AND ATTITUDES</b></td>
                    </tr>
                    <tr>
                        <td rowspan="2" width="40%" class="text-center align-middle" style="font-size: 9px!important;"><b>AREAS</b></td>
                        <td colspan="4" class="text-center" style="font-size: 9px!important;"><b>QUARTERLY EVALUATION</b></td>
                        <td rowspan="2" width="10%" class="text-center align-middle" style="font-size: 9px!important;"><b>FINAL</b></td>
                        <td rowspan="2" width="10%" class="text-center align-middle" style="font-size: 9px!important;"><b>ACTION <br> TAKEN</b></td>

                    </tr>
                    <tr>
                        <td width="7.5%" class="text-center p-0 align-middle" style=""><b>1</b></td>
                        <td width="7.5%" class="text-center p-0 align-middle" style=""><b>2</b></td>
                        <td width="7.5%" class="text-center p-0 align-middle" style=""><b>3</b></td>
                        <td width="7.5%" class="text-center p-0 align-middle" style=""><b>4</b></td>
                    </tr>
                    @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                        @php
                            $count = 0;
                        @endphp
                        @foreach ($groupitem as $item)
                            @if($item->value == 0)
                            @else
                                {{-- <tr>
                                    @if($count == 0)
                                        <td class="align-middle" style="" rowspan="{{count($groupitem)}}">{{$item->group}}</td>
                                        @php
                                            $count = 1;
                                        @endphp
                                    @endif
                                </tr> --}}
                                <tr>
                                    <td class="align-middle p-0" style="font-size: 8.5px!important; padding-left: 5px!important;">
                                        {{$item->description}}
                                    </td>
                                    <td class="text-center align-middle p-0" style="font-size: 10px;">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q1eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                    <td class="text-center align-middle p-0" style="font-size: 10px;">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q2eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                    <td class="text-center align-middle p-0" style="font-size: 10px;">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q3eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                    <td class="text-center align-middle p-0" style="font-size: 10px;">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q4eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                </table>
                <table class="table-sm table mb-0 mt-0" width="100%"  style="table-layout: fixed; font-size: 9px; margin-top: 5px;">
                    <tr>
                        <td colspan="4" class="text-center p-0"><b>EVALUATION CODE:</b></td>
                    </tr>
                    <tr>
                        <td width="21%" class="text-left p-0"><b>O</b> - Outstanding</td>
                        <td width="25%" class="text-left p-0"><b>VS</b> - Very Satisfactory</td>
                        <td width="23%" class="text-left p-0"><b>S</b> - Satisfactory</td>
                        <td width="32%" class="text-left p-0"><b>MS</b> - Minimally Satisfactory</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-center p-0"><b>NI</b> - Needs Improvement</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" style="table-layout: fixed; font-size: 14px; margin-top: 10px;">
        <tr>
            <td width="100%" class="p-0 text-center"><b>REPORT ON ATTENDANCE</b></td>
        </tr>
    </table>
    <table class="table table-sm table-bordered mb-0" width="100%" style="font-size: 12px !important">
        @php
             $width = count($attendance_setup) != 0? 65 / count($attendance_setup) : 0;
        @endphp
        <tr>
            <th width="25%"></th>
            @foreach ($attendance_setup as $item)
                <th class="text-center align-middle"  width="{{$width}}%">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
            @endforeach
            <th class="text-center text-center"  width="10%">Total </th>
        </tr>
        <tr>
            <td>No. of school Days</td>
            @foreach ($attendance_setup as $item)
                <td class="text-center align-middle">{{$item->days}}</td>
            @endforeach
            <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
        </tr>
        <tr >
            <td>No. of days Present</td>
            @foreach ($attendance_setup as $item)
                <td class="text-center align-middle">{{$item->present}}</td>
            @endforeach
            <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('present')}}</th>
        </tr>
        <tr>
            <td  >No. of times Absent</td>
            @foreach ($attendance_setup as $item)
                <td class="align-middle text-center p-0">{{$item->absent}}</td>
            @endforeach
            <th class="text-center align-middle" >{{ collect($attendance_setup)->sum('absent')}}</td>
        </tr>
    </table>
    <table class="table mb-0" width="100%" style="table-layout: fixed; margin-top: 10px;">
        <tr>
            <td width="60%" class="p-0" style="vertical-align: top;">
                <table class="table mb-0" width="100%" style="table-layout: fixed; font-size: 14px; margin-top: 10px;">
                    <tr>
                       <td class="text-left" style="text-indent: 105px;"><b>Certificate of Transfer</b></td>
                    </tr>
                </table>
                <table class="table mb-0" width="100%" style="table-layout: fixed; font-size: 13px; margin-top: 10px;">
                    <tr>
                       <td width="27%" class="text-left p-0" style="">Admitted to Grade: </td>
                       <td width="18.5%" class="text-left p-0 border-bottom" style=""></td>
                       <td width="12%" class="text-center p-0" style="">Section:</td>
                       <td width="20.5%" class="text-left p-0 border-bottom" style=""></td>
                       <td width="22%" class="text-left p-0" style=""></td>
                    </tr>
                </table>
                <table class="table mb-0" width="100%" style="table-layout: fixed; font-size: 13px;">
                    <tr>
                       <td width="50%" class="text-left p-0" style="">Eligibility for Admission to Grade: </td>
                       <td width="38%" class="text-left p-0 border-bottom" style=""></td>
                       <td width="22%" class="text-left p-0" style=""></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 13px; margin-top: 30px; padding: 0px 0px 0px 0px;">
                    <tr>
                        <td width="40%" class="p-0 text-left"><u><b>JUVY BENDISULA LINGUIZ</b></u></td>
                        <td width="3%" class="p-0"></td>
                        <td width="35%" class="p-0 text-center"><u><b>{{$adviser}}</b></u></td>
                        <td width="22%" class="p-0"></td>
                    </tr>
                    <tr>
                        <td width="40%" class="p-0 text-center" style="">School Principal</td>
                        <td width="3%" class="p-0"></td>
                        <td width="35%" class="p-0 text-center" style="">Adviser</td>
                        <td width="22%" class="p-0"></td>
                    </tr>
                </table>
            </td>
            <td width="40%" class="p-0" style="vertical-align: top;">
                <table class="table mb-0" width="100%" style="table-layout: fixed; font-size: 14px; margin-top: 10px;">
                    <tr>
                       <td class="text-right" style="padding-right: 20px!important;"><b>Cancellation of Eligibility of Transfer</b></td>
                    </tr>
                </table>
                <table class="table mb-0" width="100%" style="table-layout: fixed; font-size: 13px; margin-top: 10px;">
                    <tr>
                       <td width="26%" class="text-left p-0" style="">Admitted in: </td>
                       <td width="37%" class="text-left p-0 border-bottom" style=""></td>
                       <td width="37%" class="text-left p-0" style=""></td>
                    </tr>
                </table>
                <table class="table mb-0" width="100%" style="table-layout: fixed; font-size: 13px;">
                    <tr>
                       <td width="11%" class="text-left p-0" style="">Date:</td>
                       <td width="53%" class="text-left p-0 border-bottom" style=""></td>
                       <td width="36%" class="text-left p-0" style=""></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 13px; margin-top: 30px; padding: 0px 0px 0px 0px;">
                    <tr>
                        <td width="40%" class="p-0"></td>
                        <td width="60%" class="p-0 text-left"><u><b>JUVY BENDISULA LINGUIZ</b></u></td>
                        
                    </tr>
                    <tr>
                        <td width="40%" class="p-0"></td>
                        <td width="60%" class="p-0 text-center" style="">School Principal</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>