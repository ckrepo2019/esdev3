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
        @page {  
            margin:30px 30px;
            
        }
        body { 
            /* margin:0px 10px; */
            
        }
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }

        /* @page { size: 5.5in 8.5in; margin: 10px 40px;  } */
        
    </style>
</head>
<body>  

    <table style="width: 100%; table-layout: fixed;">
        <tr>
            <td style="text-align: right; vertical-align: top;">
                <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px">
            </td>
            <td style="width: 50%; text-align: center;">
                <div style="width: 100%; font-weight: bold; font-size: 18px;">{{$schoolinfo[0]->schoolname}}</div>
                <div style="width: 100%; font-size: 11px;">{{$schoolinfo[0]->address}}</div>
                <div style="width: 100%; font-weight: bold; font-size: 11px; font-style: italic;">SENIOR HIGH SCHOOL (GRADE)</div>
                <div style="width: 100%; font-size: 11px;">Government Permit No. 096, s. 2020</div>
                {{-- <div style="width: 100%; font-weight: bold; font-size: 13px; line-height: 5px;">&nbsp;</div> --}}
                <div style="width: 100%; font-weight: bold; font-size: 11px;">OFFICIAL REPORT CARD (SF 9)</div>
                <div style="width: 100%; font-size: 11px;">School Year: {{$schoolyear->sydesc}}</div>
            </td>
            <td></td>
        </tr>
    </table>
    <table style="width: 100%; font-size: 11px; margin-top: 5px;" border="1">
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
    <br/>
	@php
        $x = $semid;
    @endphp
	@if($x == 1)
		<div style="font-size: 11px; width: 100%; font-weight: bold; font-style: italic;"><u>FIRST SEMESTER</u></div> 
	@else
		<div style="font-size: 11px; width: 100%; font-weight: bold; font-style: italic;"><u>SECOND SEMESTER</u></div> 
    @endif
	
	
    <table class="table table-sm table-bordered grades" width="100%; margin-top: 7px !important">
       
        <tr>
            <td width="60%" rowspan="2"  class="align-middle text-center"><b>LEARNING AREAS</b></td>
            <td width="20%" colspan="2"  class="text-center align-middle" ><b>QUARTER</b></td>
            <td width="10%" rowspan="2"  class="text-center align-middle" ><b>FINAL<br>GRADE</b></td>
        </tr>
        <tr>
            @if($x == 1)
                <td class="text-center align-middle"><b>1</b></td>
                <td class="text-center align-middle"><b>2</b></td>
            @elseif($x == 2)
                <td class="text-center align-middle"><b>3</b></td>
                <td class="text-center align-middle"><b>4</b></td>
            @endif
        </tr>
        <tr>
            <td style="text-align: left; font-style: italic;"><b>CORE SUBJECTS</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
            <tr>
                <td>( {{$item->subjcode}} ) {{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
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
            <td style="text-align: left; font-style: italic;"><b>APPLIED SUBJECTS</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
            <tr>
                <td>( {{$item->subjcode}} ) {{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
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
            <td style="text-align: left; font-style: italic;"><b>SPECIALIZED SUBJECTS</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
            <tr>
                <td>( {{$item->subjcode}} ) {{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
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
            <th style="text-align: left; font-style: italic;"></th>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            @php
                $genave = collect($finalgrade)->where('semid',$x)->first();
            @endphp
            <td colspan="3"><b>GENERAL AVERAGE</b></td>
            <td class="text-center align-middle">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
        </tr>
    </table>
    
    <table style="width: 100%; margin-top: 5px;">
        <tr>
            <td  width="60%" class="p-0">
                @php
                    $width = count($attendance_setup) != 0? 55 / count($attendance_setup) : 0;
                @endphp
                <table class="table table-bordered table-sm grades" width="100%">
                    <tr>
                        <th colspan="{{count($attendance_setup)+2}}" class="text-left">REPORT ON ATTENDANCE</th>
                    </tr>
                    <tr class=" ">
                        <td width="25%">Months of the S.Y</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle" width="{{$width}}%">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</td>
                        @endforeach
                        <td class="text-center text-center" width="10%">Total</td>
                    </tr>
                    <tr class="table-bordered">
                        <td >Total No. of Days</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                        @endforeach
                        <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td>No. of Days Present </td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                        @endforeach
                        <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td>Tardiness</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                        @endforeach
                        <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                    </tr>
                </table>
            </td>
            <td width="2%" class="p-0"></td>
            <td width="38%" style="vertical-align: top;" class="p-0">
				<table class="table table-bordered table-sm grades" width="100%">
                    <tr>
                        <td class="text-center">LEARNING MODALITY</td>
						@if($x == 1)
							<td class="text-center">1st Quarter</td>
							<td class="text-center">2nd Quarter</td>
						@else
							<td class="text-center">3rd Quarter</td>
							<td class="text-center">4th Quarter</td>
						@endif
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
						<td class="text-center"></td>
						<td class="text-center"></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
						<td class="text-center"></td>
						<td class="text-center"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
	<table class="table table-sm" style="font-size: 10px; margin-top: 3px;" width="100%">
		<tr>
            <td  width="60%" class="p-0">
				<table class="table-sm table table-bordered"  width="100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" width="23%" class="align-middle"><center >Core Values</center></th>
                                    <th rowspan="2" width="45%" class="align-middle"><center>Behavior Statements</center></th>
                                    <th colspan="4" class="cellRight"><center>Quarter</center></th>
                                </tr>
                                <tr>
                                    <th width="8%"><center>1</center></th>
                                    <th width="8%"><center>2</center></th>
                                    <th width="8%"><center>3</center></th>
                                    <th width="8%"><center>4</center></th>
                                </tr>
                            </thead>
							<tbody>
                                @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                                    @php
                                        $count = 0;
                                    @endphp
                                    @foreach ($groupitem as $item)
                                        @if($item->value == 0)
                                                <tr>
                                                    <th colspan="6">{{$item->description}}</th>
                                                </tr>
                                        @else
                                                <tr>
                                                    @if($count == 0)
                                                            <td class="align-middle" rowspan="{{count($groupitem)}}">{{$item->group}}</td>
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
                                
                            </tbody>
                        </table>
                
            </td>
            <td width="2%"></td>
            <td width="38%" style="padding: 0px; vertical-align: top;">
				<table style="width: 100%; border: 1px solid black; vertical-align: top;" >
                    <tr>
                        <th colspan="2">Observed Values</th>
                    </tr>
                    <tr style=" font-style: italic;">
                        <th>Non-Numerical Ratings</th>
                        <th style="width: 40%;">Marking</th>
                    </tr>
                    <tr>
                        <td style="text-align: center;">Always Observed</td>
                        <td style="text-align: center;">AO</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">Sometimes Observed</td>
                        <td style="text-align: center;">SO</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">Rarely Observed</td>
                        <td style="text-align: center;">RO</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">Not Observed</td>
                        <td style="text-align: center;">NO</td>
                    </tr>
                </table>
				<br>
				<table style="border: 1px solid black; vertical-align: top;" width="100%">
                    <tr>
                        <th colspan="3">Academic Rating</th>
                    </tr>
                    <tr style=" font-style: italic;">
                        <th style="text-align: left !mportant; width: 40%;">Descriptors</th>
                        <th>Grading Scale</th>
                        <th>Remark</th>
                    </tr>
                    <tr>
                        <td>Outstanding</td>
                        <td style="text-align: center;">90-100</td>
                        <td style="text-align: center;">Passed</td>
                    </tr>
                    <tr>
                        <td>Very Satisfactory</td>
                        <td style="text-align: center;">85-89</td>
                        <td style="text-align: center;">Passed</td>
                    </tr>
                    <tr>
                        <td>Satisfactory</td>
                        <td style="text-align: center;">80-84</td>
                        <td style="text-align: center;">Passed</td>
                    </tr>
                    <tr>
                        <td>Fairly Satisfactory</td>
                        <td style="text-align: center;">75-79</td>
                        <td style="text-align: center;">Passed</td>
                    </tr>
                    <tr>
                        <td>Did Not Meet Expectations</td>
                        <td style="text-align: center;">Below 75</td>
                        <td style="text-align: center;">Failed</td>
                    </tr>
                </table>
            </td>
        </tr>
	
	</table>
    
	
	<table style="width: 100%; margin: 20px 120px; font-size: 12px;">
        <tr>
            <td style="width: 45%;">Eligible for transfer and admission to</td>
            <td style="border-bottom: 1px solid black;"></td>
        </tr>
    </table>
    <table style="width: 100%; margin: 40px 120px; font-size: 12px;">
        <tr>
            <td style="width: 45%; border-bottom: 1px solid black; text-align: center;">{{$principal_info[0]->name}}</td>
            <td style="width: 5%;"></td>
            <td style="border-bottom: 1px solid black;" class="text-center">{{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM DD, YYYY')}}</td>
        </tr>
        <tr>
            <td style="text-align: center;">{{$principal_info[0]->title}}</td>
            <td></td>
            <td style="text-align: center;">Date</td>
        </tr>
    </table>
</body>
</html>