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

        @page {  
            margin:30px 50px;
            
        }
        body { 
            /* margin:0px 10px; */
            
        }

      

        /* @page { size: 5.5in 8.5in; margin: 10px 40px;  } */
        
    </style>
</head>
<body>  
	@php
		$acadtext = '';
		if($student->acadprogid == 3){
			$acadtext = 'GRADE SCHOOL';
		}else if($student->acadprogid == 4){
			$acadtext = 'JUNIOR HIGH SCHOOL';
		}
	@endphp
    <table style="width: 100%; table-layout: fixed;">
        <tr>
            <td style="text-align: right; vertical-align: top;">
                <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px">
            </td>
            <td style="width: 50%; text-align: center;">
                <div style="width: 100%; font-weight: bold; font-size: 18px;">{{$schoolinfo[0]->schoolname}}</div>
                <div style="width: 100%; font-size: 12px;">{{$schoolinfo[0]->address}}</div>
                <div style="width: 100%; font-weight: bold; font-size: 18px;">{{$acadtext}}</div>
                <div style="width: 100%; font-size: 12px;"><i>(Government Recognition No. 79, s. 1950)</i></div>
                <div style="width: 100%; font-weight: bold; font-size: 15px;">SCHOOL ID - {{$schoolinfo[0]->schoolid}}</div>
                <div style="width: 100%; font-weight: bold; font-size: 13px;">(PAASCU ACCREDITED)</div>
                <div style="width: 100%; font-weight: bold; font-size: 13px; line-height: 5px;">&nbsp;</div>
                <div style="width: 100%; font-weight: bold; font-size: 18px;">REPORT CARD (SF 9)</div>
                <div style="width: 100%; font-weight: bold; font-size: 13px;">School Year: {{$schoolyear->sydesc}}</div>
            </td>
            <td></td>
        </tr>
    </table>
    <br/>
    <table class="table table-sm mb-0" width="100%">
        <tbody>
            <tr>
                <th width="13%" class="text-left">Student Name:</th>
                <td width="37%" class="text-left">{{$student->student}}</td>
                <th width="15%" class="text-left">Grade & Section: </th>
                <td width="35%" class="text-left">{{$student->levelname}} - {{$student->sectionname}}</td>
            </tr>
        </tbody>
    </table>
    <table class="table table-sm" width="100%">
        <tbody>
            <tr>
                <th width="8%" class="text-left">Gender:</th>
                <td width="12%" class="text-left">{{$student->gender}}</td>
                <th width="5%" class="text-left">LRN:</th>
                <td width="25%" class="text-left">{{$student->lrn}}</td>
                <th width="8%" class="text-left">Adviser:</th>
                <td width="42%" class="text-left">{{$adviser}}</td>
            </tr>
        </tbody>
    </table>

    <table class="table table-sm table-bordered grades" width="100%">
        <thead>
            <tr>
                <td rowspan="2"  class="align-middle text-center" width="40%"><b>ACADEMIC ACHIEVEMENT LEARNING AREAS</b></td>
                <td colspan="4"  class="text-center align-middle">Quarter</b></td>
                <td rowspan="2"  class="text-center align-middle">AVERAGE</b></td>
                <td rowspan="2"  class="text-center align-middle">REMARKS</b></span></td>
            </tr>
            <tr>
                <td class="text-center align-middle" width="10%">1</td>
                <td class="text-center align-middle" width="10%">2</td>
                <td class="text-center align-middle" width="10%">3</td>
                <td class="text-center align-middle" width="10%">4</td>
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
                <td class="text-right" colspan="5">GENERAL AVERAGE</td>
                <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}">{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</td>
                <td class="text-center align-middle" >{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</td>
            </tr>
        </tbody>
    </table>

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

  
    
    <div style="margin: 0px 40px; font-size: 12px; font-weight: bold;">REPORT ON ATTENDANCE</div>
    @php
        $width = count($attendance_setup) != 0? 55 / count($attendance_setup) : 0;
    @endphp
    <table class="table table-bordered table-sm" width="100%">
        <tr class=" ">
            <th width="25%"></th>
            @foreach ($attendance_setup as $item)
                <th class="text-center align-middle" width="{{$width}}%">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
            @endforeach
            <th class="text-center text-center" width="10%">Total</th>
        </tr>
        <tr class="table-bordered">
            <td class="p-1">No. of School Days</td>
            @foreach ($attendance_setup as $item)
                <td class="p-1 text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
            @endforeach
            <th class="p-1 text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
        </tr>
        <tr class="table-bordered">
            <td>No. of Days Present</td>
            @foreach ($attendance_setup as $item)
                <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
            @endforeach
            <th class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</th>
        </tr>
        <tr class="table-bordered">
            <td>No. of Day Absent</td>
            @foreach ($attendance_setup as $item)
                <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
            @endforeach
            <th class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
        </tr>
    </table>
    <br/>
    <table style="width: 100%; font-size: 11px;">
        <tr style=" font-weight: bold;">
            <td style="width: 60%;">Academic Rating</td>
            <td style="width: 2%;"></td>
            <td>Observed Values</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; vertical-align: top;">
                <table style="width: 100%;">
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
                        <td style="text-align: center;">Beloww 75</td>
                        <td style="text-align: center;">Failed</td>
                    </tr>
                </table>
            </td>
            <td></td>
            <td style="border: 1px solid black; vertical-align: top;">
                <table style="width: 100%;">
                    <tr style=" font-style: italic;">
                        <th style="width: 40%;">Marking</th>
                        <th>Non-Numerical Ratings</th>
                    </tr>
                    <tr>
                        <td style="text-align: center;">AO</td>
                        <td style="text-align: center;">Always Observed</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">SO</td>
                        <td style="text-align: center;">Sometimes Observed</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">RO</td>
                        <td style="text-align: center;">Rarely Observed</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">NO</td>
                        <td style="text-align: center;">Not Observed</td>
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