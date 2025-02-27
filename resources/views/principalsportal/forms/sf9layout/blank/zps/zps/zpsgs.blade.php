<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$student->student}}</title>
    <style>

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size:12px ;
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
            text-align: left !important;f
        }
        
        .p-0{
            padding: 0 !important;
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
            font-size: 11px !important;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        .bg-orange{
            background-color: #d75933;
        }

        .bg-green{
            background-color: #6ba012;
        }

        .bg-yellow{
            background-color: #d5c300;
        }

        .bg-light-yellow{
            background-color: #d3d3b9;
        }

        .bg-light-gray{
            background-color: #bdbcc4;
        }

        .bg-light-orange{
            background-color: #d4a000;
        }

        .bg-light-blue{
            background-color: #acc2d0;
        }

        
        
    </style>
</head>
<body>
        <table class="table" width="100%">
                <tr>
                    <td width="50%">
                        <table class="table mb-0" width="100%" >
                            <tr>
                                <th width="10%"></th>
                                <th width="80%" class="bg-yellow" style="border:2px solid #254827"><center>REPORT ON ATTENDANCE</center></th>
                                <th width="10%"></th>
                            </tr>
                        </table >
                        <table class="table table-bordered table-sm" width="100%" style="border:2px solid #254827">
                            @php
                                 $width = count($attendance_setup) != 0? 75 / count($attendance_setup) : 0;
                            @endphp
                            <tr>
                                <th width="15%" class="text-center"></th>
                                @foreach ($attendance_setup as $item)
                                    <th class="text-center align-middle"  width="{{$width}}%">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
                                @endforeach
                                <th class="text-center"  width="10%">Total</th>
                            </tr>
                            <tr>
                                <td class="text-center">No. of School Days</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->days}}</td>
                                @endforeach
                                <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                            </tr>
                            <tr >
                                <td class="text-center">No. of days <b>Present</b></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->present}}</td>
                                @endforeach
                                <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('present')}}</th>
                            </tr>
                            <tr>
                                <td  class="text-center">No. of days  <b>Absent</b></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle" >{{$item->days - $item->present}}</td>
                                @endforeach
                                <th class="text-center align-middle" >{{ collect($attendance_setup)->sum('days') - collect($attendance_setup)->sum('present')}}</td>
                            </tr>
                        </table>
                        <table class="table table-sm" width="100%" style="font-size: 15px !important">
                            <tr class="text-center">
                                <td colspan="2">Parent's Signature</td>
                            </tr>
                            <tr>
                                <td width="20%">1<sup>st</sup> Quarter</td>
                                <td width="80%" style="border-bottom: solid 1px black"></td>
                            </tr>
                            <tr>
                                <td>2<sup>nd</sup> Quarter</td>
                                <td style="border-bottom: solid 1px black"></td>
                            </tr>
                            <tr>
                                <td>3<sup>rd</sup> Quarter</td>
                                <td style="border-bottom: solid 1px black"></td>
                            </tr>
                            <tr>
                                <td>4<sup>th</sup> Quarter</td>
                                <td style="border-bottom: solid 1px black"></td>
                            </tr>
                        </table> 
                    </td>
                    <td width="50%" >
                        <table class="table table-sm" width="100%">
                            <tr>
                                <td class="text-center  p-0 ">REPUBLIKA NG PILIPINAS</td>
                            </tr>
                            <tr>
                                <td class="p-2">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="text-center  p-0">KAGAWARAN NG EDUKASYON</td>
                            </tr>
                            <tr>
                                <td class="text-center  p-0">{{$schoolinfo[0]->regDesc}}</td>
                            </tr>
                            <tr>
                                <td class="text-center  p-0">Sangay ng {{$schoolinfo[0]->citymunDesc}}</td>
                            </tr>
                            <tr>
                                <td class="p-1">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="text-center p-0"><h2 class="mb-0">{{$schoolinfo[0]->schoolname}}</h2></td>
                            </tr>
                            <tr>
                                <td class="text-center">{{$schoolinfo[0]->address}}</td>
                            </tr>
                           
                            <tr>
                            <td class="text-center  p-2"><img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="80px"></td>
                            </tr>
                        </table>
                        <br>
                        <br>
                        <br>
                        <br>
                        <table class="table table-sm mb-0 studentinfo"  width="100%" style="font-size:11px !important">
                            <tr>
                                <td width="13%">Surname: </td>
                                <td width="27%" class="text-center border-bottom"><b>{{$student->lastname}}  {{$student->suffix != null ?  $student->suffix.'.' : '' }}</b></td>
                                <td width="15%">First Name:</td>
                                <td width="30%" class="text-center border-bottom"><b>{{$student->firstname}}</b></td>
                                <td width="5%">M.I.:</td>
                                <td width="10%" class="text-center border-bottom"><b>{{$student->middlename}}</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%" style="font-size:11px !important">
                            
                            <tr>
                                <td width="5%">LRN: </td>
                                <td width="95%" class="text-center border-bottom"><b>{{$student->lrn}}</b></td>
                               
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%" style="font-size:11px !important">
                            <tr>
                                <td width="5%">Age: </td>
                                <td width="10%" class="text-center border-bottom"><b>{{$student->age}}</b></td>
                                <td width="5%">Sex:</td>
                                <td width="10%" class="text-center border-bottom"><b>{{$student->gender}}</b></td>
                                <td width="18%">Year & Section:</td>
                                <td width="42%" class="text-center border-bottom"><b>{{$student->levelname}} - {{$student->sectionname}}</b></td>
                            </tr>
                        </table>
                            
                        <table class="table table-sm studentinfo"  width="100%" style="font-size:11px !important">
                            <tr>
                                <td width="18%">School Year:</td>
                                <td width="82%" class="text-center border-bottom"><b>{{$schoolyear->sydesc}}</b></td>
                            </tr>
                        </table>
                        <p><b>Dear Parents,</b></p>
                        <p style="font-size:12px!important; text-indent: 50px;">This report card shows the ability and progress of your child in the different learning areas including its core values.</p>
                        <p style="font-size:12px!important; text-indent: 50px;">Should you desire to inquire more about your child's performance, you may contact the undersigned below.</p>
                        <br>
                        <br>
                        <br><br>
                        <table class="table table-sm "  width="100%">
                            <tr>
                                <td colspan="3" class="text-center p-0 "><b>{{$adviser}}</b></td>
                                <td colspan="2" class="text-center p-0"><b>{{$principal_info[0]->name}}</b></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center p-0 ">Class Adviser</td>
                                <td colspan="2" class="text-center p-0 ">{{$principal_info[0]->title}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
        </table>
        <table class="table" width="100%" >
                <tr>
                    <td width="50%" style="padding-left:0 !important">
                            <table  class="table mb-0" width="100%">
                                <tr>
                                    <th class="text-center" width="10%"></th>
                                    <th class="text-center bg-yellow"  width="80%" style="border:2px solid #254827">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
                                    <th class="text-center"  width="10%"></th>
                                </tr>
                            </table>    
                            @if($student->acadprogid != 5)
                                <table class="table table-bordered table-sm" width="100%"  style="border:2px solid #254827;">
                                    <tr>
                                        <td width="42%" rowspan="2"  class="align-middle text-center bg-orange"><b>Learning Areas</b></td>
                                        <td colspan="4"  class="text-center align-middle bg-orange"><b>QUARTER</b></td>
                                        <td width="15%" rowspan="2"  class="text-center align-middle bg-orange"><b>Final Grade</b></td>
                                        <td width="15%" rowspan="2"  class="text-center align-middle bg-orange"><b>Remarks</b></span></td>
                                    </tr>
                                    <tr>
                                        <td width="7%" class="text-center align-middle bg-light-yellow"><b>1</b></td>
                                        <td width="7%" class="text-center align-middle bg-light-yellow"><b>2</b></td>
                                        <td width="7%" class="text-center align-middle bg-light-yellow"><b>3</b></td>
                                        <td width="7%" class="text-center align-middle bg-light-yellow"><b>4</b></td>
                                    </tr>
                                    @php
                                        $count = 0;
                                    @endphp
                                    @foreach ($studgrades as $key=>$item)
                                        @php
                                            $count += 1;
                                        @endphp
                                        <tr class="{{$count % 2 == 0 ? 'bg-light-yellow':'bg-light-gray'}}" style=" font-size:15px !important">
                                            <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}" >{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                            <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                            <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                            <td class="text-center align-middle">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-green" style=" font-size:15px !important">
                                        @php
                                            $genave = collect($finalgrade)->first();
                                        @endphp
                                        <td colspan="5"><b>GENERAL AVERAGE</b></td>
                                        <td class="text-center align-middle">{{$genave->finalrating != null ? $genave->finalrating:''}}</td>
                                        <td class="text-center align-middle">{{$genave->actiontaken != null ? $genave->actiontaken:''}}</td>
                                    </tr>
                                </table>
                            @else
                                @for ($x=1; $x <= 2; $x++)
                                    <table class="table table-sm table-bordered grades" width="100%">
                                        <tr>
                                            <td colspan="5"  class="align-middle text-center"><b>{{$x == 1 ? '1ST SEMESTER' : '2ND SEMESTER'}}</b></td>
                                        </tr>
                                        <tr>
                                            <td width="60%" rowspan="2"  class="align-middle text-center"><b>SUBJECTS</b></td>
                                            <td width="20%" colspan="2"  class="text-center align-middle" ><b>PERIODIC RATINGS</b></td>
                                            <td width="10%" rowspan="2"  class="text-center align-middle" ><b>FINAL RATING</b></td>
                                            <td width="10%" rowspan="2"  class="text-center align-middle"><b>ACTION TAKEN</b></td>
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
                                        @foreach (collect($grades)->where('semid',$x) as $item)
                                            <tr>
                                                <td>{{$item->subjectcode!=null ? $item->subjectcode : null}}</td>
                                                @if($x == 1)
                                                    <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                                    <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                                @elseif($x == 2)
                                                    <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                                    <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                                @endif
                                                <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                                <td class="text-center align-middle">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            @php
                                                $genave = collect($finalgrade)->where('semid',$x)->first();
                                            @endphp
                                            <td><b>GENERAL AVERAGE</b></td>
                                            @if($x == 1)
                                                <td class="text-center align-middle">{{isset($genave->quarter1) ? $genave->quarter1 != null ? $genave->quarter1:'' : ''}}</td>
                                                <td class="text-center align-middle">{{isset($genave->quarter2) ? $genave->quarter2 != null ? $genave->quarter2:'' : ''}}</td>
                                            @elseif($x == 2)
                                                <td class="text-center align-middle">{{isset($genave->quarter3) ? $genave->quarter3 != null ? $genave->quarter3:'' : ''}}</td>
                                                <td class="text-center align-middle">{{isset($genave->quarter4) ? $genave->quarter4 != null ? $genave->quarter4:'' : ''}}</td>
                                            @endif
                                            <td class="text-center align-middle">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                                            <td class="text-center align-middle">{{ isset($genave->actiontaken) ? $genave->actiontaken != null ? $genave->actiontaken:'' :''}}</td>
                                        </tr>
                                    </table>
                                @endfor
                            @endif
                            <table class="table table-sm" width="100%" style="font-size: 15px !important">
                                <tr>
                                    <td width="40%"><b>DESCRIPTORS</b></td>
                                    <td width="30%" class=" text-center"><b>GRADING SCALE</b></td>
                                    <td width="30%" class=" text-center"><b>REMARKS</b></td>
                                </tr>
                                <tr>
                                    <td>Outstanding</td>
                                    <td class=" text-center">90-100</td>
                                    <td class=" text-center">Passed</td>
                                </tr>
                                <tr> 
                                    <td>Very Satisfactory</td>
                                    <td class=" text-center">85-89</td>
                                    <td class=" text-center">Passed</td>
                                </tr>
                                <tr>
                                    <td>Satisfactory</td>
                                    <td class=" text-center">80-84</td>
                                    <td class=" text-center">Passed</td>
                                </tr>
                                <tr>
                                    <td>Fairly Satisfactory</td>
                                    <td class=" text-center">75-79</td>
                                    <td class=" text-center">Passed</td>
                                </tr>
                                <tr>
                                    <td>Did Not Meet Expectations</td>
                                    <td class=" text-center">Below 75</td>
                                    <td class=" text-center">Failed</td>
                                </tr>
                            </table>
                        </td>
                    <td width="50%" style="padding-right:0 !important">
                        <table  class="table mb-0" width="100%">
                            <tr>
                                <th width="10%"></th>
                                <th class="text-center bg-yellow"  width="80%" style="border:2px solid #254827">REPORT ON LEARNER'S OBSERVED VALUES</th>
                                <th width="10%"></th>
                            </tr>
                        </table>
                        <table class="table-sm table table-bordered"  width="100%" style="border:2px solid #254827">
                            <thead>
                                <tr>
                                    <th rowspan="2" width="23%" class="align-middle bg-orange"><center >Core Values</center></th>
                                    <th rowspan="2" width="45%" class="align-middle bg-orange"><center>Behavior Statements</center></th>
                                    <th colspan="4" class="cellRight  bg-orange"><center>Quarter</center></th>
                                </tr>
                                <tr>
                                    <th width="8%"><center>1</center></th>
                                    <th width="8%"><center>2</center></th>
                                    <th width="8%"><center>3</center></th>
                                    <th width="8%"><center>4</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $corecount = 0;
                                @endphp
                                @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                                    @php
                                        $count = 0;
                                      
                                    @endphp
                                    @foreach ($groupitem as $item)
                                        @if($item->value == 0)
                                                <tr >
                                                    <th colspan="6">{{$item->description}}</th>
                                                </tr>
                                        @else
                                                <tr class=" {{$corecount % 2 == 0 ? 'bg-light-orange':'bg-light-blue'}}">
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
                                    @php
                                        $corecount += 1;
                                    @endphp
                                @endforeach
                                
                            </tbody>
                        </table>
                      
                        <table class="table table-sm" width="100%" style="font-size: 15px !important">
                            <thead>
                                <tr>
                                    <td width="20%"></td>
                                    <td width="30%"><b>Marking</b></td>
                                    <td width="40%"><b>Non- Numerical Rating</b></td>
                                    <td width="10%"></td>
                                </tr>
                            </thead>
                            <tbody>lr
                                 @foreach ($rv as $key=>$rvitem)
                                    @if($rvitem->value != null)
                                        <tr>
                                            <td></td>
                                            <td clas="text-center">{{$rvitem->value}}</td>
                                            <td>{{$rvitem->description}}</td>
                                            <td></td>
                                         </tr>
                                    @endif
                                @endforeach 
                            </tbody>
                        </table>
                    </td>
                </tr>
        </table>

    
</div>

</body>
</html>