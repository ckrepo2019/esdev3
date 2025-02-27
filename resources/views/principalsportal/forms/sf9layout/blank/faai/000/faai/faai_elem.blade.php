<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title>
    <style>
        @page{
            margin: 20px 50px;
        }
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
            font-size: 9px !important;
        }
        
        .grades_v2 td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: 11px !important;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        .bg-red{
            color: red;
            border: solid 1px black !important;
        }

        body { 
            margin:0;
            
        }

        @page { size: 8.5in 11in; }
        
        
        .text-center{
            text-align: center;
        }
        
    </style>
</head>
<body>  

    <table class="table table-sm grades " width="100%">
        <thead>
            <tr>
                <td width="30%" rowspan="2" class="text-left align-middle">
                    <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="60px">
                </td>
                <td width="40%" class="p-0 text-center" >
                    <br/>
                    <h5 class="mb-0" style="font-size:15px !important">{{$schoolinfo[0]->schoolname}}</h5>
                </td>
                <td width="30%" class="p-0" >
                    <!--<h3 class="mb-0" style="font-size:20px !important">{{$schoolinfo[0]->schoolname}}</h3>-->
                </td>
            </tr>
            <tr>
                <td class="p-0">
                    {{$schoolinfo[0]->address}}
                </td>
            </tr>
        </thead>
        <tr>
            <th class="text-center" colspan="3" style="font-size: 14px !important">OFFICIAL REPORT CARD (SF 9)</th>
        </tr>
        <tr>
            <td class="text-center" colspan="3"  style="font-size: 12px !important">(Government Recognition #01,s.1999)</td>
        </tr>
        <tr>
            <td class="text-center" colspan="3" style="font-size: 12px !important">SCHOOL ID No. 405555</td>
        </tr>
        <tr>
            <th class="text-center" colspan="3" style="font-size: 11px !important">A Y {{$schoolyear->sydesc}}</th>
        </tr>
    </table>


        <table class="table table-sm grades_v2" width="100%" style="font-size: 12px !important">
            <thead>
                <tr>
                    <td style="width: 15%;">Name:</td>
                    <td colspan="3">
                        @php
                            $temp_middle = explode(" ",$student->middlename);
                            $middle = '';
                            foreach($temp_middle as $item){
                                $middle .=  $item;
                            }
                        @endphp
                        <u><b>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</b></u>
                    </td>
                </tr>
                <tr>
                    <td style="width: 15%;">Grade/Section:</td>
                    <td>
                        <b><u>{{$student->levelname}} - {{$student->sectionname}}</u></b>
                    </td>
                    <td style="width: 10%;">Gender:</td>
                    <td><u>{{$student->gender}}</u></td>
                </tr>
                <tr>
                    <td style="width: 15%;">LRN:</td>
                    <td>
                        <b><u>{{$student->lrn}}</u></b>
                    </td>
                  
                    <td>Age:</td>
                    <td><u>{{\Carbon\Carbon::parse($student->dob)->age}}</u></td>
                </tr>
            </thead>
        </table>
    
                <table class="table table-bordered table-sm grades_v2" width="100%">
                    <thead>
                        <tr>
                            <td rowspan="2"  class="align-middle text-center" width="40%"><b>Learning Areas</b></td>
                            <td colspan="4"  class="text-center align-middle"><b>GRADING PERIOD</b></td>
                            <td rowspan="2"  class="text-center align-middle"><b>FINAL</b></td>
                            <td rowspan="2"  class="text-center align-middle"><b>REMARKS</b></span></td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle" width="10%"><b>1<sup>st</sup></b></td>
                            <td class="text-center align-middle" width="10%"><b>2<sup>nd</sup></b></td>
                            <td class="text-center align-middle" width="10%"><b>3<sup>rd</sup></b></td>
                            <td class="text-center align-middle" width="10%"><b>4<sup>th</sup></b></td>
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
                                        <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                        <td class="text-center align-middle">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td><b>AVERAGE</b></td>
                                    <td class="text-center align-middle"></td>
                                    <td class="text-center align-middle"></td>
                                    <td class="text-center align-middle"></td>
                                    <td class="text-center align-middle"></td>
                                    <td class="text-center align-middle">{{$finalgrade[0]->finalrating != null ? $finalgrade[0]->finalrating:''}}</td>
                                    <td class="text-center align-middle">{{$finalgrade[0]->actiontaken != null ? $finalgrade[0]->actiontaken:''}}</td>
                        </tr>
                    </tbody>
                </table>  
                <table class="table-sm table table-bordered" width="100%">
                    <tr>
                        <td colspan="7" class="align-middle text-center" style="text-align: center; font-size: 15px;"><u><b>REPORT ON LEARNER'S VALUES AND ATTITUDES</b></u></td>
                    </tr>
                    {{-- <tr>
                        <td rowspan="2" colspan="2" class="align-middle text-center">Observed Values and Attitudes</td>
                    </tr> --}}
                    <tr style="font-size: 9px;">
                        <th width="22%" class="align-middle text-center">Core Values</th>
                        <th width="12%"><center>1st Quarter</center></th>
                        <th width="12%"><center>2nd Quarter</center></th>
                        <th width="12%"><center>3rd Quarter</center></td>
                        <th width="12%"><center>4th Quarter</center></th>
                        <th width="12%"><center>Marking</center></th>
                        <th width="20%"><center>Non-numerical Rating</center></th>
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
        {{-- <div style="width: 100%; text-align: center; font-size: 11px; font-weight: bold;">REPORT ON LEARNER'S VALUES AND ATTITUDES</div>
                <table class="table table-bordered table-sm grades_v2" width="100%">
                    <thead>
                        <tr>
                            <td class="align-middle text-center" width="22%"><b>Core Values</b></td>
                            <td class="text-center align-middle" width="12%"><b>1<sup>st</sup> Quarter</b></td>
                            <td class="text-center align-middle" width="12%"><b>2<sup>nd</sup> Quarter</b></td>
                            <td class="text-center align-middle" width="12%"><b>3<sup>rd</sup> Quarter</b></td>
                            <td class="text-center align-middle" width="12%"><b>4<sup>th</sup> Quarter</b></td>
                            <td class="text-center align-middle" width="8%"><b>Marking</b></td>
                            <td class="align-middle" width="20%"><b>Non-numerical Rating</b></span></td>
                        </tr>
                    </thead>
                    
                  
                    <tbody>
                        <tr>
                            <td>Maka-Diyos</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center">AO</td>
                            <td>Always Observed	</td>
                        </tr>
                        <tr>
                            <td>Makatao</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center">SO</td>
                            <td>Sometimes Observed</td>
                        </tr>
                        <tr>
                            <td>Makakalikasan</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center">RO</td>
                            <td>Rarely Observed</td>
                        </tr>
                        <tr>
                            <td>Makabansa</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center">NO</td>
                            <td>Not Observed</td>
                        </tr>
                    </tbody>
                </table>  --}}
                {{-- <table class="table table-bordered table-sm grades_v2" width="100%">
                    <thead>
                        <tr>
                            <td width="40%" class="align-middle text-center">ATTENDANCE RECORD</td>
                            <td class="text-center align-middle">July</td>
                            <td class="text-center align-middle">Aug</td>
                            <td class="text-center align-middle">Sept</td>
                            <td class="text-center align-middle">Oct</td>
                            <td class="text-center align-middle">Nov</td>
                            <td class="text-center align-middle">Dec</td>
                            <td class="text-center align-middle">Jan</td>
                            <td class="text-center align-middle">Feb</td>
                            <td class="text-center align-middle">Mar</td>
                            <td class="text-center align-middle">Apr</td>
                            <td class="text-center align-middle">TOTAL</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Number of School Days</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Number of Days Present</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Number of Days Tardy</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table> --}}
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
                        <td >Number of School Days</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                        @endforeach
                        <th class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td>Number of School Day Present</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                        @endforeach
                        <th class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</th>
                    </tr>
                    <tr class="table-bordered">
                        <td>Number of School Days Absent</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                        @endforeach
                        <th class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                    </tr>
                </table>
        <div style="width: 100%; text-align: left; font-size: 11px; font-weight: bold;">Eligible to transfer and admission to Grade  </div>
                <br/>
                <br/>
                <br/>
                <table class="table table-sm grades_v2" width="100%" style="font-size: 12px !important">
                    <tr>
                        <td style="width: 5%;"></td>
                        <td class="text-center border-bottom" style="width: 35%;"><b>{{$adviser}}</b></td>
                        <td style="width: 20%;"></td>
                        <td class="text-center border-bottom" style="width: 35%;"><b>{{$principal_info[0]->name}}</b></td>
                        <td style="width: 5%;"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-center">Adviser</td>
                        <td></td>
                        <td class="text-center">Principal</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th colspan="5">&nbsp;</th>
                    </tr>
                    <tr>
                        <th colspan="5">&nbsp;</th>
                    </tr>
                </table>
                

                
          
</div>

</body>
</html>