<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$student[0]->firstname.' '.$student[0]->middlename[0].' '.$student[0]->lastname}}</title>
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
       


        .mb-0{
            margin-bottom: 0;
        }

        .border-bottom{
            border-bottom:1px solid black;
        }

        .mb-1, .my-1 {
            margin-bottom: .25rem!important;
        }
        
        .mb-2, .my-2 {
            margin-bottom: .50rem!important;
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

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }
        
        .text-red{
            color: red;
            border: solid 1px black !important;
        }
        
    </style>
</head>
<body>
        <table class="table" width="100%">
                <tr>
                    <td width="50%">
                        <table class="table" width="100%">
                            <tr>
                                <td><center>REPORT ON ATTENDANCE</center></td>
                            </tr>
                        </table >
                        <table class="table table-sm table-bordered" width="100%">
                            @php
                                 $width = count($attendance_setup) != 0? 75 / count($attendance_setup) : 0;
                            @endphp
                            <tr>
                                <th width="15%"></th>
                                @foreach ($attendance_setup as $item)
                                    <th class="text-center align-middle"  width="{{$width}}%">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
                                @endforeach
                                <th class="text-center text-center"  width="10%">Total</th>
                            </tr>
                            <tr>
                                <td>No. of School Days</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->days}}</td>
                                @endforeach
                                <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                            </tr>
                            <tr >
                                <td>No. of Days Present</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">0</td>
                                @endforeach
                                <th class="text-center text-center align-middle">0</th>
                            </tr>
                            <tr>
                                <td>No. of School Day Absent</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">0</td>
                                @endforeach
                                <th class="text-center align-middle" >0</td>
                            </tr>
                        </table>
                        <table class="table table-sm" >
                            <tr class="text-center">
                                <td colspan="2">Homeroom Remarks & Parent's Signature</td>
                            </tr>
                            <tr>
                                <td>1<sup>st</sup> Quarter</td>
                                <td>____________________________________________</td>
                            </tr>
                            <tr>
                                <td>2<sup>nd</sup> Quarter</td>
                                <td>____________________________________________</td>
                            </tr>
                            <tr>
                                <td>3<sup>rd</sup> Quarter</td>
                                <td>____________________________________________</td>
                            </tr>
                            <tr>
                                <td>4<sup>th</sup> Quarter</td>
                                <td>____________________________________________</td>
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
                                <td class="text-center  p-0">Sangay ng Bukidnon</td>
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
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                <td width="13%">Surname: </td>
                                <td width="27%" class="text-center border-bottom">{{$student[0]->lastname}}</td>
                                <td width="15%">First Name:</td>
                                <td width="30%" class="text-center border-bottom" style="font-size:{{strlen($student[0]->firstname) > 15 ? '9px':'11px'}} !important">{{$student[0]->firstname}}</td>
                                <td width="5%">M.I.:</td>
                                <td width="10%" class="text-center border-bottom">{{$student[0]->middlename[0]}}</td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
                            
                            <tr>
                                <td width="5%">LRN: </td>
                                <td width="95%" class="text-center border-bottom">{{$student[0]->lrn}}</td>
                               
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 studentinfo"  width="100%">
                            <tr>
                                <td width="5%">Age: </td>
                                <td width="10%" class="text-center border-bottom">{{\Carbon\Carbon::parse($student[0]->dob)->age}}</td>
                                <td width="5%">Sex:</td>
                                <td width="10%" class="text-center border-bottom">{{$student[0]->gender}}</td>
                                <td width="18%">Year & Section:</td>
                                <td width="42%" class="text-center border-bottom">{{$student[0]->enlevelname}} - {{$student[0]->ensectname}}</td>
                            </tr>
                        </table>
                            
                        <table class="table table-sm studentinfo"  width="100%">
                            <tr>
                                <td width="18%">School Year:</td>
                                <td width="82%" class="text-center border-bottom">{{Session::get('schoolYear')->sydesc}}</td>
                            </tr>
                        </table>
                        <p><b>Dear Parents,</b></p>
                        <p style="font-size:11px!important; text-indent: 50px;">This report card shows the ability and progress your child in the different learning areas including its core values.</p>
                        <p style="font-size:11px!important; text-indent: 50px;">Should you desire to inquire more about your childs performance, you may contact the undersigned below.</p>
                        <br>
                        <br>
                        <br><br>
                        <table class="table table-sm "  width="100%">
                            <tr>
                                <td colspan="3" class="text-center p-0 ">{{$teacher[0]->firstname}} {{strtoupper(str_replace('.','',$teacher[0]->middlename[0]))}}. {{$teacher[0]->lastname}}</td>
                                <td colspan="2" class="text-center p-0">{{Session::get('prinInfo')->firstname}} {{Session::get('prinInfo')->middlename[0]}}. {{Session::get('prinInfo')->lastname}}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center p-0 ">Class Adviser</td>
                                <td colspan="2" class="text-center p-0 ">School Principal</td>
                            </tr>
                        </table>
                    </td>
                </tr>
        </table>
        <table class="table" width="100%">
                <tr>
                    <td width="50%" style="padding-top:0 !important;">
                            <table  class="table table-sm grades mb-0" width="100%">
                                <tr>
                                    <th class="text-center">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
                                </tr>
                            </table>    
                            @if($student[0]->acadprogid != 5)
                                <table class="table table-sm table-bordered grades" width="100%">
                                    <tr>
                                        <td width="50%" rowspan="2"  class="align-middle text-center"><b>SUBJECTS</b></td>
                                        <td width="30%" colspan="4"  class="text-center align-middle"><b>PERIODIC RATINGS</b></td>
                                        <td width="10%" rowspan="2"  class="text-center align-middle"><b>FINAL RATING</b></td>
                                        <td width="10%" rowspan="2"  class="text-center align-middle"><b>ACTION TAKEN</b></span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center align-middle"><b>1</b></td>
                                        <td class="text-center align-middle"><b>2</b></td>
                                        <td class="text-center align-middle"><b>3</b></td>
                                        <td class="text-center align-middle"><b>4</b></td>
                                    </tr>
                                    @foreach ($studgrades as $item)
                                        <tr>
                                            <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}" >{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                            <td class="text-center align-middle "></td>
                                            <td class="text-center align-middle "></td>
                                            <td class="text-center align-middle "></td>
                                            <td class="text-center align-middle "></td>
                                            <td class="text-center align-middle "></td>
                                            <td class="text-center align-middle"></td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="5"><b>GENERAL AVERAGE</b></td>
                                        <td class="text-center align-middle"></td>
                                        <td class="text-center align-middle"></td>
                                    </tr>
                                </table>
                            @else
                                @for ($x=1; $x <= 2; $x++)
                                    <table class="table table-sm table-bordered grades mb-2" width="100%">
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
                                        <tr><td colspan="5" class="text-center" style="background-color:gray; color:white; border:solid 1px black">CORE</td></tr>
                                        @foreach (collect($studgrades)->sortBy('sortid')->where('semid',$x)->where('type',1) as $item)
                                            <tr>
                                                <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                                @if($x == 1)
                                                    <td class="text-center align-middle"></td>
                                                    <td class="text-center align-middle"></td>
                                                @elseif($x == 2)
                                                    <td class="text-center align-middle"></td>
                                                    <td class="text-center align-middle"></td>
                                                @endif
                                                <td class="text-center align-middle"></td>
                                                <td class="text-center align-middle"></td>
                                            </tr>
                                        @endforeach
                                        <tr><td colspan="5" class="text-center" style="background-color:gray; color:white; border:solid 1px black">APPLIED</td></td></tr>
                                        @foreach (collect($studgrades)->sortBy('sortid')->where('semid',$x)->where('type',3) as $item)
                                            <tr>
                                                <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                                @if($x == 1)
                                                    <td class="text-center align-middle"></td>
                                                    <td class="text-center align-middle"></td>
                                                @elseif($x == 2)
                                                    <td class="text-center align-middle"></td>
                                                    <td class="text-center align-middle"></td>
                                                @endif
                                                <td class="text-center align-middle"></td>
                                                <td class="text-center align-middle"></td>
                                            </tr>
                                        @endforeach
                                        <tr><td colspan="5" class="text-center" style="background-color:gray; color:white; border:solid 1px black">SPECIALIZED</td></td></tr>
                                        @foreach (collect($studgrades)->sortBy('sortid')->where('semid',$x)->where('type',2) as $item)
                                        <tr>
                                            <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                            @if($x == 1)
                                                <td class="text-center align-middle"></td>
                                                <td class="text-center align-middle"></td>
                                            @elseif($x == 2)
                                                <td class="text-center align-middle"></td>
                                                <td class="text-center align-middle"></td>
                                            @endif
                                            <td class="text-center align-middle"></td>
                                            <td class="text-center align-middle"></td>
                                        </tr>
                                    @endforeach
                                        <tr>
                                            @php
                                                $genave = collect($subjects)->where('semid',$x)->first();
                                            @endphp
                                            <td colspan="3"><b>GENERAL AVERAGE</b></td>
                                            <td class="text-center align-middle"></td>
                                            <td class="text-center align-middle"></td>
                                        </tr>
                                    </table>
                                @endfor
                            @endif
                            <table class="table table-sm grades mb-0" width="100%">
                                <tr>
                                    <td width="15%" style="padding:0 !imporant"></td>
                                    <td width="35%"  style="padding:0 !imporant"><b>DESCRIPTORS</b></td>
                                    <td width="30%" class=" text-center"  style="padding:0 !imporant"><b>GRADING SCALE</b></td>
                                    <td width="30%" class=" text-center"  style="padding:0 !imporant"><b>REMARKS</b></td>
                                </tr>
                                <tr>
                                    <td style="padding:0 !imporant"></td>
                                    <td style="padding:0 !imporant">Outstanding</td>
                                    <td style="padding:0 !imporant" class=" text-center">90-100</td>
                                    <td style="padding:0 !imporant" class=" text-center">Passed</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 !imporant"></td>   
                                    <td style="padding:0 !imporant">Very Satisfactory</td>
                                    <td style="padding:0 !imporant" class=" text-center">85-89</td>
                                    <td style="padding:0 !imporant" class=" text-center">Passed</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 !imporant"></td>
                                    <td style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant">Satisfactory</td>
                                    <td style="padding:0 !imporant" style="padding:0 !imporant" class=" text-center">80-84</td>
                                    <td style="padding:0 !imporant" class=" text-center">Passed</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant"></td>
                                    <td style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant">Fairly Satisfactory</td>
                                    <td style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant" class=" text-center">75-79</td>
                                    <td style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant" class=" text-center">Passed</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant"></td>
                                    <td style="padding:0 !imporant" style="padding:0 !imporant" style="padding:0 !imporant">Did Not Meet Expectations</td>
                                    <td style="padding:0 !imporant" style="padding:0 !imporant" class=" text-center">Below 75</td>
                                    <td style="padding:0 !imporant" class=" text-center">Failed</td>
                                </tr>
                            </table>
                        </td>
                    <td width="50%" style="padding-top: 0 !important">
                        <table  class="table table-sm mb-0" width="100%">
                            <tr>
                                <th class="text-center ">REPORT ON LEARNER'S OBSERVED VALUES</th>
                            </tr>
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
                      
                        <table class="table table-sm grades" width="100%">
                            <thead>
                                <tr>
                                    <td width="25%"></td>
                                    <td width="20%"><b>Marking</b></td>
                                    <td width="30%"><b>Non- Numerical Rating</b></td>
                                    <td width="25%"></td>
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