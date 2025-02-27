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
            transform-origin: 10 10;
            transform: rotate(-90deg);
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
        body { 
            /* margin:0px 10px; */
            
        }
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 11in 8.5in; margin: 10px 15px;  }
        
    </style>
</head>
<body>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="50%" style="vertical-align: top;"></td>
        <td width="50%" style="vertical-align: top;">
            <table class="table mb-0" style="width: 100%; table-layout: fixed; margin-top: 30px;">
                <tr>
                    <td width="100%" class="text-left" style="font-size: 12px;">Form 138-A(Report Card)</td>
                </tr>
            </table>
            <table class="table" style="width: 100%; table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center p-0" style=""><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="150px"></td>
                </tr>
            </table>
            <table class="table" style="width: 100%; table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 20px;"><b>HOLY CROSS COLLEGE OF SASA, INC</b></td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 12px;">KM 9, SASA, Davao City</td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 12px;">(082)235-0409</td>
                </tr>
            </table>
            <table class="table" style="width: 100%; table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 15px;">SY: {{$schoolyear->sydesc}}</td>
                </tr>
                <tr>
                    <!-- <td width="100%" class="text-center p-0" style="font-size: 15px;">MODIFIED WORKS AND STUDY PROGRAM</td> -->
                    <td width="100%" class="text-center p-0" style="font-size: 15px;">JUNIOR HIGH SCHOOL PROGRAM</td>
                </tr>
            </table>
            <table class="table" style="width: 100%; table-layout: fixed; margin-top: 40px!important;">
                <tr>
                    <td width="10%" class="text-center p-0" style="font-size: 15px;">Name:</td>
                    <td width="90%" class="text-left p-0" style="font-size: 15px; border-bottom: 1px solid #000;">{{$student->lastname.', '.$student->firstname.' '.$student->middlename}}</td>
                </tr>
            </table>
            <table class="table" style="width: 100%; table-layout: fixed; margin-top: 40px!important;">
                <tr>
                    <td width="27%" class="text-left p-0" style="font-size: 15px;">Grade and Section:</td>
                    <td width="58%" class="text-left p-0" style="font-size: 15px; border-bottom: 1px solid #000;">{{$student->levelname}} - {{$student->sectionname}}</td>
                    <td width="15%" class="text-left p-0" style="font-size: 15px;"></td>
                </tr>
            </table>
            <table class="table" style="width: 100%; table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 15px;">Dear Parent:</td>
                </tr>
            </table>
            <table class="table" style="width: 100%; table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="12%" class="text-left p-0" style="font-size: 15px;"></td>
                    <td width="88%" class="text-left p-0" style="font-size: 15px;">This report card shows the ability and progress your</td>
                </tr>
                <tr>
                    <td width="12%" class="text-left p-0" style="font-size: 15px;"></td>
                    <td width="88%" class="text-left p-0" style="font-size: 15px;">child has made in the different learning areas as well</td>
                </tr>
                <tr>
                    <td width="12%" class="text-left p-0" style="font-size: 15px;"></td>
                    <td width="88%" class="text-left p-0" style="font-size: 15px;">as his/her core values.</td>
                </tr>
            </table>
            <table class="table" style="width: 100%; table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="12%" class="text-left p-0" style="font-size: 15px;"></td>
                    <td width="88%" class="text-left p-0" style="font-size: 15px;">The school welcomes you if you desire to know more</td>
                </tr>
                <tr>
                    <td width="12%" class="text-left p-0" style="font-size: 15px;"></td>
                    <td width="88%" class="text-left p-0" style="font-size: 15px;">about your child's progress.</td>
                </tr>
            </table>
        </td>
    </tr> 
</table>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="50%" style="vertical-align: top;">
            <table class="table mb-0" style="width: 100%; table-layout: fixed;">
                <tr>
                   <td class="p-0" valign="top">
                        <table class="mb-0">
                            <tr>
                                <td width="30%" style="text-align: left;">
                                    <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px">
                                </td>
                                <td style="width: 60%; text-align: center;">
                                    <div style="width: 100%; font-weight: bold; font-size: 12px;">{{$schoolinfo[0]->schoolname}}</div>
                                    <div style="width: 100%; font-size: 12px;">Basic Education Department</div>
                                    <div style="width: 100%; font-size: 12px;">{{$schoolinfo[0]->address}}</div>
                                    <div style="width: 100%; font-size: 12px; margin-top: 15px;"><b>Report on Learning Progress and Achievements</b></div>
                                    <div style="width: 100%; font-size: 12px;">AY: {{$schoolyear->sydesc}}</div>
                                </td>
                                <td width="10%"></td>
                            </tr>
                        </table>
                   </td>
                </tr>
                <tr>
                   <td class="p-0" valign="top">
                        <table style="width: 100%; font-size: 11px; border-top: 4px solid #6ebf76;">
                            <tr>
                                <td width="50%" class="text-left p-0" style="padding-top: 5px!important; font-size: 14px!important;"><b>{{$student->student}}</b></td>
                                <td width="10%" class="text-left p-0"></td>
                                <td width="20%" class="text-right p-0" style="padding-top: 5px!important; font-size: 14px!important;">LRN:</td>
                                <td width="20%" class="text-left p-0" style="padding-top: 5px!important; font-size: 14px!important;">&nbsp;&nbsp;{{$student->lrn}}</td>
                            </tr>
                            <tr>
                                <td width="50%" class="text-left p-0">{{$student->levelname}} - {{$student->sectionname}}</td>
                                <td width="10%" class="text-left p-0"></td>
                                <td width="20%" class="text-right p-0"></td>
                                <td width="20%" class="text-right p-0"></td>
                            </tr>
                            <tr>
                                <td width="30%" class="text-left p-0">{{$student->gender}} / Age:  {{$student->age}}</td>
                                <td width="10%" class="text-left p-0"></td>
                                <td width="60%" class="text-right p-0" colspan="2">Curriculum: K12 Basic Education Curriculum</td>
                            </tr>
                        </table>
                   </td>
                </tr>
            </table>
            
            @php
                $acadtext = '';
                if($student->acadprogid == 3){
                    $acadtext = 'GRADE SCHOOL';
                }else if($student->acadprogid == 4){
                    $acadtext = 'JUNIOR HIGH SCHOOL';
                }
            @endphp

            <table class="table table-sm table-bordered grades mb-0 mt-0" width="100%" style="padding-top: 3px!important;">
                <thead>
                    <tr>
                        <td colspan="7" class="align-middle text-center" style="background-color: #6ebf76;"><b>ACADEMIC ACHIEVEMENT</b></td>
                    </tr>
                    <tr>
                        <td rowspan="2"  class="align-middle text-center" width="40%"><b>LEARNING AREAS</b></td>
                        <td colspan="4"  class="text-center align-middle"><b>Quarter</b></td>
                        <td rowspan="2"  class="text-center align-middle" width="15%" ><b>FINAL<br>RATING</b></td>
                        <td rowspan="2"  class="text-center align-middle" width="15%" ><b>REMARKS</b></td>
                    </tr>
                    <tr>
                        <td class="text-center align-middle" width="7.5%"><b>1</b></td>
                        <td class="text-center align-middle" width="7.5%"><b>2</b></td>
                        <td class="text-center align-middle" width="7.5%"><b>3</b></td>
                        <td class="text-center align-middle" width="7.5%"><b>4</b></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($studgrades as $item)
                        <tr>
                            <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}" >{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            <td class="text-center align-middle">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                            <td class="text-center align-middle">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-right" colspan="5"><b>GENERAL AVERAGE</b></td>
                        <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}"><b>{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</b></td>
                        <td class="text-center align-middle" ><b>{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</b></td>
                    </tr>
                </tbody>
            </table>
			<table class="table-sm table mb-0" width="100%" style="border: 1px solid #000;font-size:9px!important;">
                <tr>
                    <td class="p-0" width="26%" style="margin-left: 10px!important;margin-top: 5px!important;">DESCRIPTORS</td>
                    <td width="2%"></td>
                    <td class="p-0" style="margin-top: 5px!important;">GRADING SCALE</td>
                    <td class="p-0" colspan="3" style="margin-top: 5px!important;">REMARKS</td>
                </tr>
                <tr>
                    <td class="p-0" style="margin-left: 10px!important;">Outstanding</td>
                    <td></td>
                    <td class="p-0">90-100</td>
                    <td class="p-0" colspan="3">Passed</td>
                </tr>
                <tr>
                    <td class="p-0" style="margin-left: 10px!important;">Very Satisfactory</td>
                    <td></td>
                    <td class="p-0">85-89</td>
                    <td class="p-0" colspan="3">Passed</td>
                </tr>
                <tr>
                    <td class="p-0" style="margin-left: 10px!important;">Satisfactory</td>
                    <td></td>
                    <td class="p-0">80-84</td>
                    <td class="p-0" colspan="3">Passed</td>
                </tr>
                <tr>
                    <td class="p-0" style="margin-left: 10px!important;">Fairly Satisfactory</td>
                    <td></td>
                    <td class="p-0">75-79</td>
                    <td class="p-0" colspan="3">Passed</td>
                </tr>
                <tr>
                    <td class="p-0" style="margin-left: 10px!important; margin-bottom: 5px!important;">Did Not Meet Expectation</td>
                    <td></td>
                    <td class="p-0" style="margin-bottom: 5px!important;">Below 75</td>
                    <td class="p-0" colspan="3" style="margin-bottom: 5px!important;">Failed</td>
                </tr>
            </table>
            {{-- attendance --}}
            <table style="width: 100%;">
                <tr>
                    <td  width="100%" class="p-0">
                        @php
                            $width = count($attendance_setup) != 0? 55 / count($attendance_setup) : 0;
                        @endphp
                        <table class="table table-bordered table-sm grades mb-0" width="100%">
                            <tr>
                                <td width="18%" style="padding-top: 13px!important;border: 1px solid #000; text-align: center;">ATTENDANCE</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="aside text-center align-middle;" width="{{$width}}%"><span style="text-transform: uppercase;">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                                @endforeach
                                <td class="text-center" width="10%" style="vertical-align: middle; font-size: 12px!important;"><span>TOTAL</span></td>
                            </tr>
                            <tr class="table-bordered">
                                <td >Days of School</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                                @endforeach
                                <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                            </tr>
                            <tr class="table-bordered">
                                <td>Days of Present </td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                                @endforeach
                                <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                            </tr>
                            <tr class="table-bordered">
                                <td>Days of Tardy</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                                @endforeach
                                <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </td>

        
        <td width="50%" style="height; 100px; vertical-align: top;">
            <table class="table table-sm" style="font-size: 10px!important; margin-top: 10px;" width="100%">
                <tr>
                    <td width="100%" class="p-0">
                        <table class="table-sm table table-bordered mb-0 mt-0" width="100%">
                                <tr>
                                    <td colspan="6" class="align-middle text-center" style="background-color: #6ebf76;"><b>Reports on Learner's Observed Values</b></td>
                                </tr>
                                <tr>
                                    <td rowspan="2" colspan="2" class="align-middle text-center">Observed Values and Attitudes</td>
                                    <td colspan="4" class="cellRight"><center>Quarter</center></td>
                                </tr>
                                <tr>
                                    <td width="7.5%"><center>1</center></td>
                                    <td width="7.5%"><center>2</center></td>
                                    <td width="7.5%"><center>3</center></td>
                                    <td width="7.5%"><center>4</center></td>
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
                                                        <td width="21%" class="align-middle" style="border-right: 1px solid #fff;" rowspan="{{count($groupitem)}}"><b>{{$item->group}}</b></td>
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
                                {{-- ========================================================== --}}
                                
                        </table>
                        <table class="table-sm table mb-0" width="100%" style="border: 1px solid #000;" >
                            <tr>
                                <td width="22%" class="p-0" style="margin-left: 10px!important;margin-top: 5px!important;">Marking Code</td>
                                <td width="78%" class="p-0" style="margin-top: 5px!important;"><b>AO</b> - Always Observed</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td width="78%" class="p-0"><b>AO</b> - Sometimes Observed</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td width="78%" class="p-0"><b>RO</b> - Rarely Observed</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td width="78%" class="p-0" style="margin-bottom: 5px!important;"><b>NO</b> - Not Observed</td>
                            </tr>
                        </table>
                        {{-- <table class="table-sm table mb-0" width="100%" style="border: 1px solid #000;font-size:9px!important;">
                            <tr>
                                <td class="p-0" width="26%" style="margin-left: 10px!important;margin-top: 5px!important;">DESCRIPTORS</td>
                                <td width="2%"></td>
                                <td class="p-0" style="margin-top: 5px!important;">GRADING SCALE</td>
                                <td class="p-0" colspan="3" style="margin-top: 5px!important;">REMARKS</td>
                            </tr>
                            <tr>
                                <td class="p-0" style="margin-left: 10px!important;">Outstanding</td>
                                <td></td>
                                <td class="p-0">90-100</td>
                                <td class="p-0" colspan="3">Passed</td>
                            </tr>
                            <tr>
                                <td class="p-0" style="margin-left: 10px!important;">Very Satisfactory</td>
                                <td></td>
                                <td class="p-0">85-89</td>
                                <td class="p-0" colspan="3">Passed</td>
                            </tr>
                            <tr>
                                <td class="p-0" style="margin-left: 10px!important;">Satisfactory</td>
                                <td></td>
                                <td class="p-0">80-84</td>
                                <td class="p-0" colspan="3">Passed</td>
                            </tr>
                            <tr>
                                <td class="p-0" style="margin-left: 10px!important;">Fairly Satisfactory</td>
                                <td></td>
                                <td class="p-0">75-79</td>
                                <td class="p-0" colspan="3">Passed</td>
                            </tr>
                            <tr>
                                <td class="p-0" style="margin-left: 10px!important; margin-bottom: 5px!important;">Did Not Meet Expectation</td>
                                <td></td>
                                <td class="p-0" style="margin-bottom: 5px!important;">Below 75</td>
                                <td class="p-0" colspan="3" style="margin-bottom: 5px!important;">Failed</td>
                            </tr>
                        </table> --}}
                        
                        <table width="100%" style="font-size:10px!important; margin-top: 5px!important;">
                            <tr>
                                <td width="17%" class="p-0"></td>
                                <td width="33%" class="p-0">Eligible for transfer and admission to</td>
                                <td width="17%" class="text-center p-0" style="border-bottom:1px solid #000;"></td>
                                <td width="6%" class="text-center p-0">Date</td>
                                <td width="17%" class="text-center p-0" style="border-bottom:1px solid #000;">{{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MM DD, YYYY')}}</td>
                                <td width="10%" class="p-0"></td>
                            </tr>
                        </table>
                        <br>
                        <br>
                        <table style="width: 100%; padding-top: 25px; text-align: center;">
                            <tr>
                                <td class="p-0" width="45%"><b>{{$adviser}}</b></td>
                                <td class="p-0" width="10%" style=""></td>
                                <td class="p-0" width="45%" style="font-size: 12px;"><b>SR. MA. JULIETA E. RELATIVO, TDM</b></td>
                            </tr>
                            <tr>
                                <td class="p-0" width="45%" style="text-align: center; font-size: 10px;">Class Adviser</td>
                                <td class="p-0" width="10%" style=""></td>
                                <td class="p-0" width="45%" style="text-align: center; font-size: 10px;">Principal</td>
                            </tr>
                        </table>
                        <br>
                        <table width="100%">
                            <tr style="font-size:10px;">
                                <td class="text-center" width="100%" style="border-top: 4px solid #6ebf76;"><b>CANCELLATION OF TRANSFER ELIGIBILITY</b></td>
                            </tr>
                        </table>
                        <table width="100%" style="margin-top: 20px!important; padding-left: 50px!important; padding-right: 50px!important;">
                            <tr style="font-size:10px;">
                                <td class="text-left p-0" width="35%" style="font-size: 10px">Has been admitted to (School)</td>
                                <td width="65%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                            </tr>
                        </table>
                        <table width="100%" style="margin-top: 20px!important; padding-left: 70px!important; padding-right: 70px!important;">
                            <tr style="font-size:10px;">
                                <td class="text-left p-0" width="25%" style="font-size: 10px">Principal / Registrar</td>
                                <td width="55%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                                <td class="text-left p-0" width="5%" style="font-size: 10px">Date</td>
                                <td width="15%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                            </tr>
                        </table>
                        {{-- <table width="100%">
                            <tr style="font-size:10px;">
                                <td width="19%"></td>
                                <td width="30%">Has been admitted to (School)</td>
                                <td width="41%" style="border-bottom:1px solid #000;" class="p-0"></td>
                                <td width="10%"></td>
                            </tr>
                        </table>
                        <table width="100%">
                            <tr style="font-size:10px;">
                                <td width="19%"></td>
                                <td class="text-right" width="25%">Principal / Registrar</td>
                                <td width="23%" style="border-bottom:1px solid #000;">{{$principal_info[0]->name}}</td>
                                <td width="5%">Date</td>
                                <td width="9%" style="border-bottom:1px solid #000;"></td>
                                <td width="19%"></td>
                            </tr>
                        </table> --}}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>