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
        
        .plot_10 td{
            padding-top: .2rem;
            padding-bottom: .2rem;
            font-size: 13px !important;
        }
        
        .plot_11 td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: 11px !important;
        }
        
        .plot_10-1 td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: 10px !important;
        }
        


        
        .page_break { page-break-before: always; }
        @page { margin: .5in;  }
        
    </style>
</head>
<body>
    
        <img src="{{base_path()}}/public/header_xai_shs.PNG" alt="school" width="100%">
        @php
            $track = 'TVL';
        @endphp
        @if($strandInfo->trackname == 'Academic Track')
            @php
                $track = 'Academic';
            @endphp
        @endif
    
        <table class="table table-sm plot_10-1" width="100%" style="margin-top:2rem !important">
            <tr>
                <td>NAME:</td>
                <td><b>{{$student->student}}</b></td>
                <td>BIRTH DATE:</td>
                <td colspan="3"><b>{{\Carbon\Carbon::create($student->dob)->isoFormat('MMMM DD, YYYY')}}</b></td>
            </tr>
            <tr>
                <td width="15%">LRN:</td>
                <td width="35%"><b>{{$student->lrn}}</b></td> 
                <td width="15%">AGE:</td>
                <td width="10%"><b>{{$student->age}}</b></td>
                <td width="5%">SEX:</td>
                <td width="20%"><b>{{$student->gender}}</b></td>
            </tr>
            <tr>
                <td>CURRICULUM:</td>
                <td><b>K TO 12 </b></td>
                <td>GRADE & SECTION:</td>
                <td colspan="3"><b>{{$student->levelname}} - {{$student->sectionname}}</b></td>
            </tr>
             <tr>
                <td>TRACK/STRAND:</td>
                <td><b>{{$track}} - {{$strandInfo->strandcode}}</b></td>
                <td>SCHOOL YEAR:</td>
                <td colspan="3"><b>{{$schoolyear->sydesc}}</b></td>
            </tr>
        </table>
        
        <table  class="table table-sm grades" width="100%">
            <tr>
                <th class="text-center">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
            </tr>
        </table>    
    
        <table class="table" width="100%">
            <tr>
                <td width="50%" class="p-0" style="padding-right:.10in !important">
                    @php
                        $x = 1;
                    @endphp
                    <table class="table table-sm table-bordered grades" width="100%">
                        <tr>
                            <td colspan="5"  class="align-middle text-center"><b>{{$x == 1 ? 'First Semester' : 'Second Semester'}}</b></td>
                        </tr>
                        <tr>
                            <td width="40%" rowspan="2"  class="align-middle text-center"><b>SUBJECTS</b></td>
                            <td width="20%" colspan="2"  class="text-center align-middle" ><b>Quarter</b></td>
                            <td width="15%" rowspan="2"  class="text-center align-middle" ><b>Semester<br>Final<br>Grade</b></td>
                            <td width="15%" rowspan="2"  class="text-center align-middle"><b>Remarks</b></td>
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
                        @foreach (collect($studgrades)->where('semid',$x) as $item)
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
                                <td></td>
                                <td class="text-center align-middle">&nbsp;</td>
                                <td class="text-center align-middle"></td>
                                <td class="text-center align-middle"></td>
                                <td class="text-center align-middle"></td>
                            </tr>
                        <tr>
                            @php
                                $genave = collect($finalgrade)->where('semid',$x)->first();
                            @endphp
                            <td colspan="3"><b>GENERAL AVERAGE</b></td>
                            <td class="text-center align-middle">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                            <td class="text-center align-middle">{{ isset($genave->actiontaken) ? $genave->actiontaken != null ? $genave->actiontaken:'' :''}}</td>
                        </tr>
                    </table>
                </td>
                <td width="50%" class="p-0" style="padding-left:.10in !important">
                    @php
                        $x = 2;
                    @endphp
                    <table class="table table-sm table-bordered grades" width="100%">
                        <tr>
                            <td colspan="5"  class="align-middle text-center"><b>{{$x == 1 ? 'First Semester' : 'Second Semester'}}</b></td>
                        </tr>
                        <tr>
                            <td width="40%" rowspan="2"  class="align-middle text-center"><b>SUBJECTS</b></td>
                            <td width="20%" colspan="2"  class="text-center align-middle" ><b>Quarter</b></td>
                            <td width="15%" rowspan="2"  class="text-center align-middle" ><b>Semester<br>Final<br>Grade</b></td>
                            <td width="15%" rowspan="2"  class="text-center align-middle"><b>Remarks</b></td>
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
                        @foreach (collect($studgrades)->where('semid',$x) as $item)
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
                </td>
            </tr>
            
        </table>
        <table class="table table-sm plot_11" width="100%">
            <tr>
                <td width="12%">Dear Parents:</td>
                <td width="78%"></td>
                <td width="10%"></td>
            </tr>
            <tr>
                <td></td>
                <td style=" text-indent: 5%;"> This report card shows the ability and progress of your child has made in the different learning areas as well as his/her core value. The school welcomes your desire to know more about your child's progress.</td>
                <td></td>
            </tr>
        </table>
        <table  class="table table-sm" width="100%" style="margin-top:4rem !important">
            <tr>
                <td width="50%" class="p-0"></td>
                <td width="50%" class="text-center p-0"><u><b>{{$adviser}}</b></u></td>
            </tr>
            <tr>
                <td class="p-0"></td>
                <td class="text-center p-0"><i>Adviser</i></td>
            </tr>
             <tr>
                <td class="text-center p-0"><u><b>REV. FR. MARK HARVEY T. ELLOREN</b></u></td>
                <td class="p-0"></td>
            </tr>
             <tr>
                <td class="text-center p-0"><i>School Director/School Principal</i></td>
                <td class="p-0"></td>
            </tr>
        </table>
        <div class="page_break"></div>
        <table  class="table mb-0" width="100%" >
                    <tr>
                        <th class="text-center" style="font-size: 15px !important">REPORT ON LEARNER'S OBSERVED VALUES</th>
                    </tr>
                </table>
                <table class="table table-bordered plot_10"  width="100%">
                    <thead>
                        <tr>
                            <td colspan="2"  class="align-middle" rowspan="2"><center >Core Values</center></td>
                            <td rowspan="2" width="44%" class="align-middle"><center>Behavior Statements</center></td>
                            <td colspan="4" class="cellRight" width="32%"><center>Quarter</center></td>
                        </tr>
                        <tr>
                            <td width="8%" class="align-middle text-center">1</td>
                            <td width="8%" class="align-middle text-center">2</td>
                            <td width="8%" class="align-middle text-center">3</td>
                            <td width="8%" class="align-middle text-center">4</td>
                        </tr>
                    </thead>
                    @php
                        $row_count = 1;
                    @endphp
                    <tbody>
                        @php
                            $count = 1;
                        @endphp
                        @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                            @php
                                $count = 0;
                            @endphp
                            @foreach ($groupitem as $item)
                                @if($item->value == 0)
                                        <tr>
                                   
                                            <th colspan="6" >{{$item->description}}</th>
                                        </tr>
                                @else
                                        <tr>
                                            @if($count == 0)
                                                    <td  width="5%" class="align-middle text-center" rowspan="{{count($groupitem)}}">{{$row_count}}</td>
                                                    <td  width="19%" class="align-middle" rowspan="{{count($groupitem)}}">
                                                        {{$item->group}}
                                                    </td>
                                                    @php
                                                       
                                                        $count = 1;
                                                    @endphp
                                            @endif
                                            <td class="align-middle" >
                                                {{$item->description}}
                                            </td>
                                            <td class="text-center align-middle" >
                                                @foreach ($rv as $key=>$rvitem)
                                                    {{$item->q1eval == $rvitem->id ? $rvitem->value : ''}}
                                                @endforeach 
                                            </td>
                                            <td class="text-center align-middle p-0" >
                                                @foreach ($rv as $key=>$rvitem)
                                                    {{$item->q2eval == $rvitem->id ? $rvitem->value : ''}}
                                                @endforeach 
                                            </td>
                                            <td class="text-center align-middle p-0">
                                                @foreach ($rv as $key=>$rvitem)
                                                    {{$item->q3eval == $rvitem->id ? $rvitem->value : ''}} 
                                                @endforeach
                                            </td>
                                            <td class="text-center align-middle  p-0">
                                                @foreach ($rv as $key=>$rvitem)
                                                    {{$item->q4eval == $rvitem->id ? $rvitem->value : ''}}
                                                @endforeach 
                                            </td>
                                        </tr>
                                @endif
                               
                            @endforeach
                             @php
                                     $row_count += 1;
                                @endphp
                        @endforeach
                        
                    </tbody>
                </table>
              
                <table class="table table-sm" width="100%">
                    <thead>
                        <tr>
                            <td width="10%"></td>
                            <td width="40%" class="text-center"><b>Marking</b></td>
                            <td width="40%" class="text-center"><b>Non- Numerical Rating</b></td>
                            <td width="10%"></td>
                        </tr>
                    </thead>
                    <tbody>
                         @foreach ($rv as $key=>$rvitem)
                            @if($rvitem->value != null)
                                <tr>
                                    <td class="p-0"></td>
                                    <td class="text-center p-0">{{$rvitem->value}}</td>
                                    <td class="text-center p-0">{{$rvitem->description}}</td>
                                    <td class="p-0"></td>
                                 </tr>
                            @endif
                        @endforeach 
                    </tbody>
                </table>
                  @php
                    $width = count($attendance_setup) != 0? 55 / count($attendance_setup) : 0;
                @endphp
                <table class="table table-bordered table-sm plot_11" width="100%">
                    <tr class=" ">
                        <td width="25%">ATTENDANCE</td>
                        @foreach ($attendance_setup as $att_item)
                            <td class="text-center align-middle" width="{{$width}}%">{{\Carbon\Carbon::create(null, $att_item->month)->isoFormat('MMM')}}</td>
                        @endforeach
                        <td class="text-center text-center" width="10%">Total</td>
                    </tr>
                    <tr class="table-bordered">
                        <td >School Days</td>
                        @foreach ($attendance_setup as $att_item)
                            <td class="text-center align-middle">{{$att_item->days != 0 ? $att_item->days : '' }}</td>
                        @endforeach
                        <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td>Days Present</td>
                        @foreach ($attendance_setup as $att_item)
                            <td class="text-center align-middle">{{$att_item->days != 0 ? $att_item->present : ''}}</td>
                        @endforeach
                        <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td>Day Tardy</td>
                        @foreach ($attendance_setup as $att_item)
                            <td class="text-center align-middle" >{{$att_item->days != 0 ? $att_item->absent : ''}}</td>
                        @endforeach
                        <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                    </tr>
                </table>
                <table class="table table-sm grades"  width="100%">
                    <tr class="text-center">
                        <td colspan="2"><b></b>PARENT/GUARDIAN SIGNATURE</b></td>
                    </tr>
                    <tr>
                        <td width="15%" class="align-middle" style="font-size:11px !important">1<sup>st</sup> Quarter</td>
                        <td width="85%" class="border-bottom align-middle" style="padding:.75rem !important"></td>
                    </tr>
                    <tr>
                        <td class="align-middle" style="font-size:11px !important">2<sup>nd</sup> Quarter</td>
                        <td class="border-bottom align-middle" style="padding:.75rem !important"></td>
                    </tr>
                    <tr>
                        <td class="align-middle" style="font-size:11px !important">3<sup>rd</sup> Quarter</td>
                        <td class="border-bottom align-middle" style="padding:.75rem !important"></td>
                    </tr>
                    <tr>
                        <td class="align-middle" style="font-size:11px !important">4<sup>th</sup> Quarter</td>
                        <td class="border-bottom align-middle" style="padding:.75rem !important"></td>
                    </tr>
                </table> 
                
                <table  class="table table-sm" width="100%" style="margin-top:2rem !important">
                    <tr>
                        <td width="50%" class="p-0"></td>
                        <td width="50%" class="border-bottom text-center p-0"><b>{{$adviser}}</b></td>
                    </tr>
                    <tr>
                        <td class="p-0"></td>
                        <td class="text-center p-0"><i>Adviser</i></td>
                    </tr>
                     <tr>
                        <td class="p-0">Approved:</td>
                        <td>&nbsp;</td>
                    </tr>
                     <tr>
                        <td class="border-bottom text-center p-0"><b>REV. FR. MARK HARVEY T. ELLOREN</b></td>
                        <td class="p-0"></td>
                    </tr>
                   
                     <tr>
                        <td class="text-center p-0"><i>School Director/School Principal</i></td>
                        <td class="p-0"></td>
                    </tr>
                </table>
                <table class="table mb-0"  width="100%">
                    <tr class="text-center">
                        <td><b>CANCELLATION OF ELIGIBILITY TO TRANSFER</b></td>
                    </tr>
                </table>
                <table class="table table-sm" width="100%">
                    <tr>
                        <td width="10%" class="">Admitted in:</td>
                        <td width="90%" class="border-bottom p-0"></td>
                    </tr>
                </table>
                <table  class="table table-sm" width="100%" style="margin-top:0 !important">
                    <tr>
                 
                        <td class="text-center p-0" width="5%">Date:</td>
                        <th class="text-center p-0 border-bottom" width="25%"></th>
                        <th class="text-center p-0 " width="20%"></th>
                        <th class="text-center p-0 border-bottom" width="50%">REV. FR. MARK HARVEY T. ELLOREN</th>
                      
                    </tr>
                    <tr>
                        <td class="text-center p-0" ></td>
                        <td class="text-center p-0" ></td>
                         <td class="text-center p-0"></td>
                        <td class="text-center p-0" >Principal</td>
                    </tr>
                </table>
    
    
    
</div>

</body>
</html>