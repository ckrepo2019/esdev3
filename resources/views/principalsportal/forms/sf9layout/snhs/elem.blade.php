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
            transform-origin: 14 26;
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
            transform-origin: 24 16;
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
        @page { size: 5.5in 8.5in; margin: .8cm .4cm 0cm;}
        
    </style>
</head>
<body>
    <table width="100%" style="table-layout: fixed;">
        <tr>
            <td width="100%">
                <table width="100%" style="table-layout: fixed; font-size: 11px;">
                    <tr>
                        <td width="100%" class="text-left p-0">&nbsp;&nbsp;&nbsp;&nbsp;SF 9</td>
                    </tr>
                    <tr>
                        <td width="100%" class="text-left p-0">&nbsp;&nbsp;&nbsp;&nbsp;LRN: <u>{{$student->lrn}}</u></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 11px;">
                    <tr>
                        <td width="22%" class="text-right">
                            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="50px" style="padding-left: 25px!important;">
                        </td>
                        <td width="56%" class="text-center" style="vertical-align: top;">
                            <div class="p-0" style="font-size: 14px;"><b>Santo Niño High School of Bacolod, Inc.</b></div>
                            <div class="p-0" style="font-size: 12px;">Bacolod, Lanao del Norte</div>
                            <div class="p-0" style="font-size: 12px; padding-top: 1px!important;"><b>REPORT CARD</b></div>
                            <div class="p-0" style="font-size: 12px; padding-top: 2px!important;">School Year <u>{{$schoolyear->sydesc}}</u></div>
                        </td>
                        <td width="22%"></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 10px; margin-top: 10px;">
                    <tr>
                        <td class="p-0" width="9%"><b>&nbsp;&nbsp;&nbsp;&nbsp;Name:</b></td>
                        <td class="p-0" width="56%" style="border-bottom: 1px solid #000;">{{$student->student}}</td>
                        <td class="p-0" width="35%"></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 10px;">
                    <tr>
                        <td class="p-0" width="7%"><b>&nbsp;&nbsp;&nbsp;&nbsp;Age:</b></td>
                        <td class="p-0" width="11%" style="border-bottom: 1px solid #000;">{{$student->age}}</td>
                        <td class="p-0" width="53%"></td>
                        <td class="p-0" width="9%"><b>Gender:</b></td>
                        <td class="p-0" width="11%" style="border-bottom: 1px solid #000;">{{$student->gender[0]}}</td>
                        <td class="p-0" width="9%"></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 10px;">
                    <tr>
                        <td class="p-0" width="9%"><b>&nbsp;&nbsp;&nbsp;&nbsp;Grade:</b></td>
                        <td class="p-0" width="10%" style="border-bottom: 1px solid #000;">{{str_replace('GRADE', '', $student->levelname)}}</td>
                        <td class="p-0" width="9%"><b>&nbsp;Section:</b></td>
                        <td class="p-0" width="34%" style="border-bottom: 1px solid #000;">{{$student->sectionname}}</td>
                        <td class="p-0" width="9%"></td>
                        <td class="p-0" width="12%"><b>Curriculum:</b></td>
                        <td class="text-center p-0" width="9%" style="border-bottom: 1px solid #000;"><b>K to 12</b></td>
                        <td class="p-0" width="8%"></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 9.5px; margin-top: 1px;">
                    <tr>
                        <td class="text-center" width="100%"><b>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</b></td>
                    </tr>
                </table>
                <table width="100%" class="table table-sm table-bordered" style="margin-right: .67cm!important;">
                    <thead>
                        <tr>
                            <td rowspan="2" width="32.5%" class="align-middle text-center p-0" style="font-size: 9.5px!important; height: 40px; vertical-align: top!important;"><b>LEARNING AREAS</b></td>
                            <td width="31.5%" colspan="4"  class="text-center align-middle p-0" style="font-size: 9.5px!important; padding-bottom: 10px!important;"><b>QUARTER</b></td>
                            <td width="15%" rowspan="2" class="text-center p-0" style="font-size: 9.5px!important; vertical-align: middle!important;"><div><b>FINAL <br> GRADE</b></div></td>
                            <td width="20%" rowspan="2"  class="text-center p-0" style="font-size: 9.5px!important; vertical-align: middle!important;"><div><b>REMARKS</b></div></td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle p-0" style="font-size: 9.5px!important;"><b>1</b></td>
                            <td class="text-center align-middle p-0" style="font-size: 9.5px!important;"><b>2</b></td>
                            <td class="text-center align-middle p-0" style="font-size: 9.5px!important;"><b>3</b></td>
                            <td class="text-center align-middle p-0" style="font-size: 9.5px!important;"><b>4</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studgrades as $item)
                            <tr>
                                <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}; font-size: 8px!important;"><b>{{$item->subjdesc!=null ? $item->subjdesc : null}}</b></td>
                                <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                <td class="text-center align-middle" style="font-size: 8px!important;">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                                <td class="text-center align-middle" style="font-size: 8px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-left" style="font-size: 11px!important; vertical-align: top; padding-top: 0px;"><b>General Average</b></td>
                            <td class="text-left" style="font-size: 10px!important;"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}" style="vertical-align: top; font-size: 10px!important;"><b>{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</b></td>
                            <td class="text-center" style="font-size: 10px!important; vertical-align: top;"><b>{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</b></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 10px!important;">
                    <tr>
                        <td width="10%" class="text-center"></td>
                        <td width="30%" class="text-left p-0"><b>Descriptors</b></td>
                        <td width="20%" class="text-left p-0"><b>Grading Scale</b></td>
                        <td width="10%" class="text-left p-0"><b>Remarks</b></td>
                        <td width="30%" class="text-center"></td>
                    </tr>
                    <tr>
                        <td width="10%" class="text-center"></td>
                        <td width="30%" class="text-left p-0">Outstanding</td>
                        <td width="20%" class="text-left p-0">90-100</td>
                        <td width="10%" class="text-left p-0">Passed</td>
                        <td width="30%" class="text-center"></td>
                    </tr>
                    <tr>
                        <td width="10%" class="text-center"></td>
                        <td width="30%" class="text-left p-0">Very Satisfactory</td>
                        <td width="20%" class="text-left p-0">85-89</td>
                        <td width="10%" class="text-left p-0">Passed</td>
                        <td width="30%" class="text-center"></td>
                    </tr>
                    <tr>
                        <td width="10%" class="text-center"></td>
                        <td width="30%" class="text-left p-0">Satisfactory</td>
                        <td width="20%" class="text-left p-0">80-84</td>
                        <td width="10%" class="text-left p-0">Passed</td>
                        <td width="30%" class="text-center"></td>
                    </tr>
                    <tr>
                        <td width="10%" class="text-center"></td>
                        <td width="30%" class="text-left p-0">Fairly Satisfactory</td>
                        <td width="20%" class="text-left p-0">75-79</td>
                        <td width="10%" class="text-left p-0">Passed</td>
                        <td width="30%" class="text-center"></td>
                    </tr>
                    <tr>
                        <td width="10%" class="text-center"></td>
                        <td width="30%" class="text-left p-0">Did Not Meet Expectations</td>
                        <td width="20%" class="text-left p-0">Below 75</td>
                        <td width="10%" class="text-left p-0">Failed</td>
                        <td width="30%" class="text-center"></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 9.5px; margin-top: 10px;">
                    <tr>
                        <td class="text-left" width="100%"><b>&nbsp;REPORT ON ATTENDANCE</b></td>
                    </tr>
                </table>
                @php
                    $width = count($attendance_setup) != 0? 80 / count($attendance_setup) : 0;
                @endphp
                <table width="100%" class="table table-bordered table-sm grades mb-0" style="margin-right: .67cm!important;">
                    <tr>
                        <td width="11%" style="border: 1px solid #000; text-align: center;"></td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center" width="{{$width}}%"><span style="font-size: 10px!important"><b>{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</b></span></td>
                        @endforeach
                        <td width="9%" class="text-center" style="font-size: 10px!important;"><span style=""><b>Total</b></span></td>
                    </tr>
                    <tr class="table-bordered">
                        <td class="" style="font-size: 10px!important;"><b>No. of <br>school <br>days</span></b></td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                        @endforeach
                        <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td class="" style="font-size: 10px!important;"><b>No. of <br>days <br> Present</span></b></td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                        @endforeach
                        <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td class="" style="font-size: 10px!important;"><b>No. of <br>days <br>absent</span></b></td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                        @endforeach
                        <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" style="table-layout: fixed;">
        <tr>
            <td width="100%" style="padding-left: 1cm!important;">
                <table width="100%" style="table-layout: fixed;">
                    <tr>
                        <td class="text-left" colspan="6" style="font-size: 9.5px;"><b>REPORTS ON LEARNER'S OBSERVED VALUES</b></td>
                    </tr>
                </table>
                <table class="table-sm table table-bordered mb-0 mt-0" width="100%"  style="table-layout: fixed;">
                    <tr>
                        <td width="24%" rowspan="2" class="text-left" style="font-size: 9px;"><b>Core Values</b></td>
                        <td width="44%" rowspan="2"><b>Behavior Statements</b></td>
                        <td class="text-center" colspan="4" style="font-size: 9px;"><b>Quarter</b></td>
                    </tr>
                    <tr>
                        <td width="8%" class="text-center p-0" style=""><b>1</b></td>
                        <td width="8%" class="text-center p-0" style=""><b>2</b></td>
                        <td width="8%" class="text-center p-0" style=""><b>3</b></td>
                        <td width="8%" class="text-center p-0" style=""><b>4</b></td>
                    </tr>
                    @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                        @php
                            $count = 0;
                        @endphp
                        @foreach ($groupitem as $item)
                            @if($item->value == 0)
                            @else
                                
                                <tr>
                                    @if($count == 0)
                                            <td class="text-left p-0" style="font-size: 10px; vertical-align: top;" rowspan="{{count($groupitem)}}"><b>&nbsp;&nbsp;{{$item->group}}</b></td>
                                            @php
                                                $count = 1;
                                            @endphp
                                    @endif
                                    {{-- <td class="align-middle" style="font-size: 10px;"><b>{{$item->group}}</b></td> --}}
                                    <td class="align-middle p-0" style="font-size: 10px;">{{$item->description}}</td>
                                    <td class="text-center p-0 align-middle" style="font-size: 10px;">
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
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed;">
                    <tr>
                        <td width="15%" class="text-left p-0" style="font-size: 9px;"></td>
                        <td width="32%" class="text-left p-0" style="font-size: 9px;"><b>Marking</b></td>
                        <td width="34%" class="text-left p-0" style="font-size: 9px;"><b>Non-Numerical Rating</b></td>
                        <td width="19%" class="text-left p-0" style="font-size: 9px;"></td>
                    </tr>
                    <tr>
                        <td width="15%" class="text-left p-0" style="font-size: 9px;"></td>
                        <td width="32%" class="text-left p-0" style="font-size: 9px;">AO</td>
                        <td width="34%" class="text-left p-0" style="font-size: 9px;">Always Observed</td>
                        <td width="19%" class="text-left p-0" style="font-size: 9px;"></td>
                    </tr>
                    <tr>
                        <td width="15%" class="text-left p-0" style="font-size: 9px;"></td>
                        <td width="32%" class="text-left p-0" style="font-size: 9px;">SO</td>
                        <td width="34%" class="text-left p-0" style="font-size: 9px;">Sometimes Observed</td>
                        <td width="19%" class="text-left p-0" style="font-size: 9px;"></td>
                    </tr>
                    <tr>
                        <td width="15%" class="text-left p-0" style="font-size: 9px;"></td>
                        <td width="32%" class="text-left p-0" style="font-size: 9px;">RO</td>
                        <td width="34%" class="text-left p-0" style="font-size: 9px;">Rarely Observed</td>
                        <td width="19%" class="text-left p-0" style="font-size: 9px;"></td>
                    </tr>
                    <tr>
                        <td width="15%" class="text-left p-0" style="font-size: 9px;"></td>
                        <td width="32%" class="text-left p-0" style="font-size: 9px;">NO</td>
                        <td width="34%" class="text-left p-0" style="font-size: 9px;">Not Observed</td>
                        <td width="19%" class="text-left p-0" style="font-size: 9px;"></td>
                    </tr>
                </table>
                <table class="table table-bordered table-sm mb-0" style="table-layout: fixed; font-size: 10px;">
                    <tr>
                        <td width="30%" class="text-center"><b>Quarter</b></td>
                        <td width="35%" class="text-center"><b>Achievement</b></td>
                        <td width="35%" class="text-center"><b>Parent's / Guardian's Signature</b></td>
                    </tr>
                    <tr>
                        <td class="text-left">First Quarter</td>
                        <td class="text-left"></td>
                        <td class="text-left"></td>
                    </tr>
                    <tr>
                        <td class="text-left">Second Quarter</td>
                        <td class="text-left"></td>
                        <td class="text-left"></td>
                    </tr>
                    <tr>
                        <td class="text-left">Third Quarter</td>
                        <td class="text-left"></td>
                        <td class="text-left"></td>
                    </tr>
                    <tr>
                        <td class="text-left">Fourth Quarter</td>
                        <td class="text-left"></td>
                        <td class="text-left"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 9px; margin-top: 15px;">
                    <tr>
                        <td width="30%" class="text-center"><b>Note to Parent or Guardian</b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 10px; margin-top: 7px;">
                    <tr>
                        <td width="100%" class="" style="text-align: justify;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Education is a lifelong process. It depends not only on the school or child but also upon the 
                            interest of the parents. Then school seeks your cooperation so both you and the teacher may work
                            together for the good of your child and the school. You are invited to visit our school anf confer us 
                            concerning your child's progress and difficulty.
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 9px; margin-top: 15px;">
                    <tr>
                        <td width="100%" class="text-center p-0" style="border-bottom: 2px solid #000; padding-bottom: 1px!important;">THE ADMINISTRATION</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 9px; margin-top: 15px;">
                    <tr>
                        <td width="49%" class="text-left p-0" style=""><b>Eligible for transfer and admission to / Retained in </b></td>
                        <td width="28%" class="text-left p-0" style="border-bottom: 1px solid"></td>
                        <td width="23%" class="text-left p-0" style=""></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 9px;">
                    <tr>
                        <td width="29%" class="text-left p-0" style=""><b>Has advanced/lacked units in</b></td>
                        <td width="48%" class="text-left p-0" style="border-bottom: 1px solid"></td>
                        <td width="23%" class="text-left p-0" style=""></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 9px; margin-top: 20px;">
                    <tr>
                        <td width="28%" class="text-center p-0" style="border-bottom: 1px solid"><b>{{$adviser}}</b></td>
                        <td width="33%" class="text-left p-0" style=""></td>
                        <td width="28%" class="text-center p-0" style="border-bottom: 1px solid"><b>ANNA RUTH O. SALIBAY</b></td>
                        <td width="10%" class="text-left p-0" style=""></td>
                    </tr>
                    <tr>
                        <td width="28%" class="text-center p-0" style=""><b>Class Adviser</b></td>
                        <td width="33%" class="text-left p-0" style=""></td>
                        <td width="28%" class="text-center p-0" style=""><b>Principal</b></td>
                        <td width="10%" class="text-left p-0" style=""></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 9px; margin-top: 2px;">
                    <tr>
                        <td width="100%" class="text-center p-0" style="border-bottom: 2px solid #000; padding-bottom: 1px!important;"></td>
                    </tr>
                    <tr>
                        <td width="100%" class="text-center p-0" style=""><b>CANCELLATION OF ELIGIBILITY TO TRANSFER</b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 9px; margin-top: 5px;">
                    <tr>
                        <td width="23%" class="text-center p-0" style=""><b>Has been admitted to</b></td>
                        <td width="43%" class="text-center p-0" style="border-bottom: 1px solid"></td>
                        <td width="34%" class="text-left p-0" style=""></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 9px; margin-top: 20px;">
                    <tr>
                        <td width="5%" class="text-center p-0" style=""><b>Date</b></td>
                        <td width="22%" class="text-center p-0" style="border-bottom: 1px solid"><b></b></td>
                        <td width="34%" class="text-left p-0" style=""></td>
                        <td width="28%" class="text-center p-0" style="border-bottom: 1px solid"><b>ANNA RUTH O. SALIBAY</b></td>
                        <td width="11%" class="text-left p-0" style=""></td>
                    </tr>
                    <tr>
                        <td width="5%" class="text-center p-0" style=""></td>
                        <td width="22%" class="text-center p-0" style=""><b></b></td>
                        <td width="34%" class="text-left p-0" style=""></td>
                        <td width="28%" class="text-center p-0" style=""><b>Principal</b></td>
                        <td width="11%" class="text-left p-0" style=""></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>