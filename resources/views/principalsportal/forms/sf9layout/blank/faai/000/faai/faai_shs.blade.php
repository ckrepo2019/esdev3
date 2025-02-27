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
            font-size: 10px !important;
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
            /* line-height: 15px; */
            /* height: 35px; */
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
            /* transform-origin: 10 10; */
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
        body { 
            /* margin:0px 10px; */
            
        }
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 8.5in 11in; margin: 10px 15px;  }
        
    </style>
</head>
<body>  
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="20%" style="text-align: left; vertical-align: top;">
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px">
        </td>
        <td style="width: 60%; text-align: center;">
            <div style="width: 100%; font-weight: bold; font-size: 20px;"><b>{{$schoolinfo[0]->schoolname}}</b></div>
            <div style="width: 100%; font-size: 12px;">(Learning Center of the Arts)</div>
            <div style="width: 100%; font-size: 12px;">{{$schoolinfo[0]->address}}</div>
            <div style="width: 100%; font-size: 12px;">Government Permit (R-XI)</div>
            <div style="width: 100%; font-size: 12px;">No.165 s.2021</div>
            <div style="width: 100%; font-size: 12px;">SCHOOL ID NO. 405555</div>
            <div style="width: 100%; font-size: 13px;"><b>ACADEMIC REPORT CARD</b></div>
            {{-- <div style="width: 100%; font-weight: bold; font-size: 11px; font-style: italic;">SENIOR HIGH SCHOOL (GRADE)</div>
            <div style="width: 100%; font-size: 11px;">Government Permit No. 096, s. 2020</div>
            <div style="width: 100%; font-weight: bold; font-size: 13px; line-height: 5px;">&nbsp;</div>
            <div style="width: 100%; font-weight: bold; font-size: 11px;">OFFICIAL REPORT CARD (SF 9)</div>
            <div style="width: 100%; font-size: 12px; margin-top: 15px;"><b>Report on Learning Progress and Achievements</b></div>
            <div style="width: 100%; font-size: 12px;">AY: {{$schoolyear->sydesc}}</div> --}}
        </td>
        <td width="20%" style="text-align: center; vertical-align: top;" class="align-middle">
            <img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="100px">
        </td>
    </tr>
    {{-- <hr>
    <tr>
        <table style="width: 100%; font-size: 11px; margin-top: 5px;" >
            <tr>
                <td width="50%" class="text-left p-0"><b>{{$student->student}}</b></td>
                <td width="10%" class="text-left p-0"></td>
                <td width="20%" class="text-right p-0">LRN1:</td>
                <td width="20%" class="text-right p-0"><b>{{$student->lrn}}</b></td>
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
            <tr>
                <td colspan="2" style="width: 20%;">Name : {{$student->student}}</td>
                <td  style="width: 20%;">Gender : {{$student->gender}}</td>
                <td width="60%">Grade & Section : {{$student->levelname}} - {{$student->sectionname}}</td>
            </tr>
            <tr>
                <td width="25%">LRN : {{$student->lrn}}</td>
                <td width="25%">Track :  Academic</td>
                <td width="15%">Strand : {{$strandInfo->strandcode}}</td>
                <td width="35%">Adviser : {{$adviser}}</td>
            </tr>
        </table>
    </tr> --}}
</table>


<table style="width: 100%; font-size: 12px; margin-top: 5px;" >
    <tr>
        <td width="15" class="text-left p-0">Name :</td>
        <td width="45%" class="text-left p-0"><u><b>{{$student->student}}</b></u></td>
        <td width="15%" class="text-left p-0">Gender:</td>
        <td width="25%" class="text-left p-0"><u><b>{{$student->gender}}</b></u></td>
    </tr>
    <tr>
        <td width="15%" class="text-left p-0">Grade/Section :</td>
        <td width="45%" class="text-left p-0"><u><b>{{$student->levelname}} - {{$student->sectionname}}</b></u></td>
        <td width="15%" class="text-left p-0">Academic Year :</td>
        <td width="25%" class="text-left p-0"><u><b>{{$schoolyear->sydesc}}</b></u></td>
    </tr>
    <tr>
        <td width="15%" class="text-left p-0">TRACK/STRAND:</td>
        <td width="45%" class="text-left p-0"><u><b>{{$strandInfo->strandcode}}</b></u></td>
        <td width="15%" class="text-left p-0">LRN :</td>
        <td width="25%" class="text-left p-0"><u><b>{{$student->lrn}}</b></u></td>
    </tr>
    <!-- <tr>
        <td colspan="2" style="width: 20%;">Name : {{$student->student}}</td>
        <td  style="width: 20%;">Gender : {{$student->gender}}</td>
        <td width="60%">Grade & Section : {{$student->levelname}} - {{$student->sectionname}}</td>
    </tr>
    <tr>
        <td width="25%">LRN : {{$student->lrn}}</td>
        <td width="25%">Track :  Academic</td>
        <td width="15%">Strand : {{$strandInfo->strandcode}}</td>
        <td width="35%">Adviser : {{$adviser}}</td>
    </tr> -->
</table>
<br>

<table style="width: 100%; font-size: 12px; margin-top: 5px;" >
    <tr>
        <td style="text-align: center; font-size: 15px;"><u><b>REPORT ON LEARNER'S PROGRESS AND ACHIEVEMENTS</b></u></td>
    </tr>
</table>
@php
$x = 1;
@endphp
<table class="table table-bordered table-sm grades" width="100%" >
    <tr>
    <td rowspan="2" colspan="3"  class="align-middle text-left"><b>FIRST SEMESTER</b></td>
    <td colspan="2"  class="text-center align-middle" ><b>QUARTER</b></td>
    <td class="text-center align-middle" >Semester</td>
    </tr>
    <tr>
    @if($x == 1)
        <td width="6%" class="text-center align-middle">1</td>
        <td width="6%" class="text-center align-middle">2</td>
        <td width="8%" class="text-center align-middle" >Final Grade</td>
    @elseif($x == 2)
        <td width="6%" class="text-center align-middle">3</td>
        <td width="6%" class="text-center align-middle">4</td>
        <td width="8%" class="text-center align-middle" >Final Grade</td>
    @endif
    </tr>
    {{-- <tr class="trhead">
    <td style="text-align: left;" colspan="4"><b>Core</b></td>
    </tr> --}}
    @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
    <tr>
        <td width="13%" style="text-align: left;"><b>Core</b></td>
        <td width="5%" >{{$item->subjcode}}</td>
        <td width="62%" >{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
        @if($x == 1)
            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
        @elseif($x == 2)
            <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
            <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
        @endif
        <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
    </tr>
    @endforeach
    {{-- <tr class="trhead">
    <td style="text-align: left;" colspan="4"><b>Applied</b></td>
    </tr> --}}
    @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
    <tr>
        <td style="text-align: left;"><b>Applied</b></td>
        <td>{{$item->subjcode}}</td>
        <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
        @if($x == 1)
            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
        @elseif($x == 2)
            <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
            <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
        @endif
        <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
    </tr>
    @endforeach
    {{-- <tr class="trhead">
    <td style="text-align: left;" colspan="4"><b>CONTEXTUALIZED SUBJECTS</b></td>
    </tr> --}}
    {{-- <tr class="trhead">
    <td style="text-align: left;" colspan="4"><b>Specialized</b></td>
    </tr> --}}
    @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
    <tr>
        <td style="text-align: left;"><b>Specialized</b></td>
        <td>{{$item->subjcode}}</td>
        <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
        @if($x == 1)
            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
        @elseif($x == 2)
            <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
            <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
        @endif
        <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
    </tr>
    @endforeach

    <tr>
    @php
        $genave = collect($finalgrade)->where('semid',$x)->first();
    @endphp
    <td class="text-right" colspan="5"><i>General Average for the Semester :</i></td>
    <td class="text-center" style="font-weight: bold; font-size: 13px!important;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
    </tr>
</table>

@php
$x = 2;
@endphp
<table class="table table-bordered table-sm grades" width="100%" >
    <tr>
    <td rowspan="2" colspan="3"  class="align-middle text-left"><b>SECOND SEMESTER</b></td>
    <td colspan="2"  class="text-center align-middle" ><b>QUARTER</b></td>
    <td class="text-center align-middle" >Semester</td>
    </tr>
    <tr>
    @if($x == 1)
        <td width="6%" class="text-center align-middle">1</td>
        <td width="6%" class="text-center align-middle">2</td>
        <td width="8%" class="text-center align-middle" >Final Grade</td>
    @elseif($x == 2)
        <td width="6%" class="text-center align-middle">3</td>
        <td width="6%" class="text-center align-middle">4</td>
        <td width="8%" class="text-center align-middle" >Final Grade</td>
    @endif
    </tr>
    {{-- <tr class="trhead">
    <td style="text-align: left;" colspan="4"><b>Core</b></td>
    </tr> --}}
    @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
    <tr>
        <td width="13%" style="text-align: left;"><b>Core</b></td>
        <td width="5%" >{{$item->subjcode}}</td>
        <td width="62%" >{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
        @if($x == 1)
            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
        @elseif($x == 2)
            <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
            <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
        @endif
        <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
    </tr>
    @endforeach
    {{-- <tr class="trhead">
    <td style="text-align: left;" colspan="4"><b>Applied</b></td>
    </tr> --}}
    @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
    <tr>
        <td style="text-align: left;"><b>Applied</b></td>
        <td>{{$item->subjcode}}</td>
        <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
        @if($x == 1)
            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
        @elseif($x == 2)
            <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
            <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
        @endif
        <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
    </tr>
    @endforeach
    {{-- <tr class="trhead">
    <td style="text-align: left;" colspan="4"><b>CONTEXTUALIZED SUBJECTS</b></td>
    </tr> --}}
    {{-- <tr class="trhead">
    <td style="text-align: left;" colspan="4"><b>Specialized</b></td>
    </tr> --}}
    @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
    <tr>
        <td style="text-align: left;"><b>Specialized</b></td>
        <td>{{$item->subjcode}}</td>
        <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
        @if($x == 1)
            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
        @elseif($x == 2)
            <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
            <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
        @endif
        <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
    </tr>
    @endforeach

    <tr>
    @php
        $genave = collect($finalgrade)->where('semid',$x)->first();
    @endphp
    <td class="text-right" colspan="5"><i>General Average for the Semester :</i></td>
    <td class="text-center" style="font-weight: bold; font-size: 13px!important;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
    </tr>
</table>



<table class="table-sm table table-bordered" width="100%">
    <tr>
        <td colspan="7" class="align-middle text-center" style="text-align: center; font-size: 15px;"><u><b>REPORT ON LEARNER'S VALUES AND ATTITUDES</b></u></td>
    </tr>
    {{-- <tr>
        <td rowspan="2" colspan="2" class="align-middle text-center">Observed Values and Attitudes</td>
    </tr> --}}
    <tr style="font-size: 9px;">
        <td width="22%" class="align-middle text-center">Core Values</td>
        <td width="12%"><center>1st Quarter</center></td>
        <td width="12%"><center>2nd Quarter</center></td>
        <td width="12%"><center>3rd Quarter</center></td>
        <td width="12%"><center>4th Quarter</center></td>
        <td width="12%"><center>Marking</center></td>
        <td width="20%"><center>Non-numerical Rating</center></td>
    </tr>
    {{-- ========================================================== --}}
     @php
        $row_count = 0;
     @endphp
    @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
        @php
            $count = 0;
        @endphp
        @foreach ($groupitem as $item)
            
            @if($item->value == 0)
                {{-- <tr>
                    <th width="42%" colspan="6">{{$item->description}}</th>
                </tr> --}}
            @else
                <tr >
                    @if($count == 0)
                            <td width="21%" class="text-left align-middle" rowspan="{{count($groupitem)}}">{{$item->group}}</td>
                            @php
                                $count = 1;
                            @endphp
                    @endif
                 
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
                    <td class="text-center align-middle">
                        {{$rv[$row_count]->value}} 
                    </td>
                    <td>
                         {{$rv[$row_count]->description}}
                    </td>
                </tr>
            @endif
            @php
                $row_count += 1;
            @endphp
        @endforeach
    @endforeach
    {{-- ========================================================== --}}
    
</table>

<table style="width: 100%;">
               
    <tr>
        <td  width="100%" class="p-0">
            @php
                $width = count($attendance_setup) != 0? 55 / count($attendance_setup) : 0;
            @endphp
            <table class="table table-bordered table-sm grades mb-0" width="100%">
                <tr>
                    <td class="text-center" width="100%" colspan="{{count($attendance_setup) + 2}}" style="font-size: 15px!important; border: 1px solid #000;"><u><b>REPORT ON ATTENDANCE</b></u></td>
                </tr>
                <tr>
                    <td width="35%" style="border: 1px solid #000; text-align: center"></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle;" width="{{$width}}%"><span style="text-transform: uppercase;">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                    @endforeach
                    <td class="text-center" width="10%" style="vertical-align: middle; font-size: 12px!important;"><span>TOTAL</span></td>
                </tr>
                <tr class="table-bordered">
                    <td >Number of School Days</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                    @endforeach
                    <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td>Number of School Day Present</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td>Number of School Days Absent</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                </tr>
                {{-- <tr class="table-bordered">
                    <td>Cutting Classes</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                </tr> --}}
            </table>
        </td>
        {{-- <td width="6%" class="p-0"></td>
        <td width="29%" style="vertical-align: top;" class="p-0">
            <table class="table table-bordered table-sm grades" width="100%">
                <tr class="trhead">
                    <td class="text-center"  style="border: 1px solid #000;" colspan="2">RHGP/CONDUCT</td>
                </tr>
                <tr>
                    <td width="70%" class="text-center">DEVELOPED AND COMMENDABLE</td>
                    <td width="30%" class="text-center">DC</td>
                </tr>
                <tr>
                    <td class="text-center">SUFFICIENTLY DEVELOPED</td>
                    <td class="text-center">SD</td>
                </tr>
                <tr>
                    <td class="text-center">DEVELOPING</td>
                    <td class="text-center">D</td>
                </tr>
                <tr>
                    <td class="text-center">NEEDS IMPROVEMENT</td>
                    <td class="text-center">NI</td>
                </tr>
                <tr>
                    <td class="text-center">NO CHANCE TO OBSERVE</td>
                    <td class="text-center">N</td>
                </tr>
            </table>
        </td> --}}
    </tr>
</table>
<br>
<table class="table table-sm" style="font-size: 10px!important;" width="100%">
    <tr>
        <td>REMARKS; Eligible to transfer and admission to 
            @if($student->levelname == 'GRADE 11') GRADE 12 @else COLLEGE @endif
        </td>
    </tr>
</table>
<table style="width: 100%; font-size: 10px;">
    <tr>
        <td width="10%" class="p-0"></td>
        <td width="30%" class="p-0" style="width: 45%;text-align: center; border-bottom: 1px solid #000;"><b>{{$adviser}}</b></td>
        <td width="20%" class="p-0" style="width: 5%;"></td>
        <td width="30%" class="p-0 text-center" style="border-bottom: 1px solid #000;"><b>{{$principal_info[0]->name}}</b></td>
        <td width="10%" class="p-0"></td>
    </tr>
    <tr>
        <td width="10%" class="p-0"></td>
        <td width="30%" class="p-0" style="text-align: center;">Adviser</td>
        <td width="20%" class="p-0"></td>
        <td width="30%" class="p-0" style="text-align: center;">{{$principal_info[0]->title}}</td>
        <td width="10%" class="p-0"></td>
    </tr>
</table>
</body>
</html>