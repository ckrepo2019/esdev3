<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title>
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

        .p-1{
            padding: .25rem !important;
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

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        .text-red{
            color: red;
            border: solid 1px black;
        }


        

        @page { size: 11in 8.5in; margin: .25in;  }
        
    </style>
</head>
<body>
        <table class="table" width="100%">
                <tr>
                    <td width="50%" style="padding-right: .25in !important">
                         
                        <table class="table table-bordered table-sm" width="100%">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center" colspan="5" style="background-color: gainsboro">ATTENDANCE</th>
                                </tr>
                                <tr>
                                    <td width="40%" class="align-middle text-center">MONTH</td>
                                    <td width="15%" class="align-middle text-center">DAYS IN SCHOOL</td>
                                    <td width="15%" class="align-middle text-center">DAYS PRESENT</td>
                                    <td width="15%" class="align-middle text-center">DAYS ABSENT</td>
                                    <td width="15%" class="align-middle text-center">TIMES TARDY</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendance_setup as $item)
                                    <tr>
                                        <td class="text-center align-middle" >{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMMM')}}</td>
                                        <td class="text-center align-middle" >{{$item->days}}</td>
                                        <td class="text-center align-middle" >{{$item->present}}</td>
                                        <td class="text-center align-middle" >{{$item->absent}}</td>
                                        <td class="text-center align-middle" >0</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <table class="table table-sm" width="100%" style="border-right:  solid 1px black; border-left:  solid 1px black; border-bottom:  solid 1px black; ">
                            <tr>
                                <td  width="100%" class="p-0">
                                    <table class="table table-sm" width="100%">
                                        <tr>
                                            <td class="align-middle text-center table-bordered" colspan="4" style="background-color: gainsboro">CERTIFICATE OF TRANSFER</td>
                                        </tr>
                                    </table>
                                    <table class="table table-sm" width="100%">
                                        <tr>
                                            <td width="25%">Admitted to Grade: </td>
                                            <td width="25%" class="border-bottom"></td>
                                            <td width="10%">Section: </td>
                                            <td width="40%" class="border-bottom"></td>
                                        </tr>
                                    </table>
                                    <table class="table table-sm" width="100%">
                                        <tr>
                                            <td width="40%">Elegiblity of Admission to Grade: </td>
                                            <td width="60%" class="border-bottom"></td>
                                        </tr>
                                    </table>
                                    <table class="table table-sm" width="100%">
                                        <tr>
                                            <td width="100%">Approved by: </td>
                                        </tr>
                                    </table>
                                    <table class="table table-sm" width="100%">
                                        <tr>
                                            <td width="100%" class="text-center"><b><u>{{Session::get('prinInfo')->firstname}} {{Session::get('prinInfo')->lastname}}</u></b></td>
                                        </tr>
                                        <tr>
                                            <td width="100%" class="text-center"><b>SCHOOL PRINCIPAL</b></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                     
                    </td>
                    <td width="50%" style="padding-left: .25in !important" >
                        <table class="table table-sm" width="100%">
                            <tr>
                                <td class="text-center p-0" style="font-size: 30px !important"><b>{{$schoolinfo[0]->schoolname}}</b></td>
                            </tr>
                            <tr>
                                <td class="text-center">{{$schoolinfo[0]->address}}</td>
                            </tr>
                           
                            <tr>
                            <td class="text-center  p-2"><img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="120px"></td>
                            </tr>
                        </table>
                        <table class="table table-sm" width="100%">
                            <tr>
                                <td class="text-center p-0" width="100%" style="font-size: 15px !important"><b>SENIOR HIGH SCHOOL</b></td>
                            </tr>
                            <tr>
                                <td class="text-center p-0" width="100%" style="font-size: 15px !important"><b>PERFORMANCE REPORT</b></td>
                            </tr>
                              <tr>
                                <td class="text-center p-0" width="100%" style="font-size: 15px !important"><b>S.Y. {{$schoolyear->sydesc}}</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm" width="100%" style="font-size:11px !important;margin-top:40px !important">
                            <tr>
                                <td class="p-1" style="padding: .25rem !important" width="20%"><b>NAME:</b></td>
                                @php
                                    $middle = '';
                                    if($student->middlename != null){
                                        $temp_middle = explode(" ",$student->middlename);
                                        foreach($temp_middle as $item){
                                            $middle .=  $item[0].'.';
                                        }
                                    }
                                @endphp
                                <td class="p-1" style="padding: .25rem !important" width="30%"><b>{{$student->lastname}}, {{$student->firstname}} {{$middle}}</b></td>
                                <td class="p-1" style="padding: .25rem !important" width="20%"></td>
                                <td class="p-1" style="padding: .25rem !important" width="30%"></td>
                            </tr>
                            <tr>
                                <td class="p-1" style="padding: .25rem !important" width="20%"><b>AGE:</b></td>
                                <td class="p-1" style="padding: .25rem !important" width="30%">{{\Carbon\Carbon::parse($student->dob)->age}}</td>
                                <td class="p-1" style="padding: .25rem !important" width="20%"><b>SEX:</b></td>
                                <td class="p-1" style="padding: .25rem !important" width="30%">{{$student->gender}}</td>
                            </tr>
                          
                            <tr>
                                <td width="20%" style="padding: .25rem !important"><b>GRADE:</b></td>
                                <td width="30%" style="padding: .25rem !important">{{str_replace("GRADE ","",$student->levelname)}}</td>
                                <td width="20%" style="padding: .25rem !important"><b>SECTION:</b></td>
                                <td width="30%" style="padding: .25rem !important">{{$student->sectionname}}</td>
                            </tr>
                            <tr>
                                <td width="20%" style="padding: .25rem !important"><b>TRACK:</b></td>
                                <td width="30%" style="padding: .25rem !important"></td>
                                <td width="20%" style="padding: .25rem !important"><b>LRN:</b></td>
                                <td width="30%" style="padding: .25rem !important">{{$student->lrn}}</td>
                            </tr>
                            <tr>
                                <td width="20%"><b>STRAND:</b></td>
                                <td width="30%"><b></b></td>
                                <td width="20%"></td>
                                <td width="30%"></td>
                            </tr>
                        </table>
                        <table class="table table-sm grades" style="margin-top:60px !important; font-size: 14px !important">
                            <tbody>
                                <tr>
                                    <td width="50%" class="text-center align-middle" style="font-size: 12px !important"><b><u>{{$adviser}}</u></b></td>
                                    <td width="50%"></td>
                                </tr>
                                <tr>
                                    <td class="text-center align-middle" style="font-size: 12px !important"><b>Class Adviser</b></td>
                                    <td ></td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-sm grades" width="100%" style="margin-top:40px !important; ">
                            <tbody>
                                <tr>
                                    <td width="100%" class="text-center align-middle" style="font-size: 12px !important"><b><u>{{Session::get('prinInfo')->firstname}} {{Session::get('prinInfo')->lastname}}</u></b></td>
                                </tr>
                                <tr>
                                    <td class="text-center align-middle" style="font-size: 12px !important"><b>School Principal</b></td>
                                </tr>
                            </tbody>
                        </table>
                    
                    </td>
                </tr>
        </table>
        <table class="table" width="100%">
                <tr>
                    <td width="50%" style="padding-right: .25in !important">
                           <table  class="table table-sm grades" width="100%">
                                <tr>
                                    <th class="text-center">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
                                </tr>
                            </table>    
                            
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
                                    {{--Core--}}
                                    @php
                                        $temp_subjects = collect($studgrades)->where('semid',$x)->where('type',1)->values();
                                    @endphp
                                    <tr><td colspan="5" class="text-center" style="background-color:gray; color:white; border:solid 1px black">CORE SUBJECTS</td></tr>
                                    @foreach ($temp_subjects as $item)
                                         <tr>
                                                <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
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
                                    @php
                                        $temp_subjects = collect($studgrades)->where('semid',$x)->where('type',3)->values();
                                    @endphp
                                   <tr><td colspan="5" class="text-center" style="background-color:gray; color:white; border:solid 1px black">APPLIED SUBJECTS</td></tr>
                                    @foreach ($temp_subjects as $item)
                                        <tr>
                                                <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
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
                                    @php
                                        $temp_subjects = collect($studgrades)->where('semid',$x)->where('type',2)->values();
                                    @endphp
                                    <tr><td colspan="5" class="text-center" style="background-color:gray; color:white; border:solid 1px black">SPECIALIZED SUBJECTS</td></tr>
                                    @foreach ($temp_subjects as $item)
                                        <tr>
                                                <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
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
                                            <td colspan="3"><b>GENERAL AVERAGE</b></td>
                                            <td class="text-center align-middle">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                                            <td class="text-center align-middle">{{ isset($genave->actiontaken) ? $genave->actiontaken != null ? $genave->actiontaken:'' :''}}</td>
                                        </tr>
                                 
                                </table>
                            @endfor
                               
                          
                        </td>
                    <td width="50%" style="padding-left: .25in !important">
                        <table  class="table table-sm" width="100%">
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