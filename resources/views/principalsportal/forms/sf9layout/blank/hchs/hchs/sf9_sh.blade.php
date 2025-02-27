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
            font-size: 8px ;
        }
        
        .grades-7 td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: 8px ;
        }
        
        
        
        .border-blue td, .border-blue th{
            border:solid 1px blue;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        .bg-red{
            color: red;
            border: solid 1px black !important;
        }

        /*@page {  */
        /*    margin:0;*/
            
        /*}*/
        /*body { */
        /*    margin:0;*/
            
        /*}*/

        @page { size: 4.3in 10in; margin: .2in .1in;  }
        
    </style>
</head>
<body>  

  
      <table class="table table-sm grades mb-1"  width="100%" style="font-size:13px ; ">
            <tr>
                <td class="p-0" style="font-size:8px !important" colspan="3">
                    DepEd Form 138 -  SHS
                </td>
            </tr>
             <tr>
                   <td width="20%" rowspan="6" class="text-center align-middle">
                        <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="50px">
                   </td>
                   <td width="60%" style="text-align: center;">
                        <b>Republic of the Philippines</b>
                   </td>
                   <td width="20%" rowspan="6" class="text-center align-middle">
                        <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="50px">
                   </td>
                   
                  
             </tr>
            <tr>
                <td class="text-center p-0">
                    Department of Education
                </td>
            </tr>
            <tr>
                <td class="text-center p-0">
                    Region X, Northern Mindanao
                </td>
            </tr>
            <tr>
                <td class="text-center p-0">
                    DIVISION OF LANAO DEL NORTE
                </td>
            </tr>
            <tr>
                <td class="text-center p-0">
                   <b>DISTRICT OF KOLAMBUGAN</b>
                </td>
            </tr>
            <tr>
                <td class="text-center"  style="padding-top:5px !important; font-size:12px !important">
                 <b>HOLY CROSS HIGH SCHOOL</b>
                </td>
            </tr>
        </table>
        <table class="table table-sm grades mb-1" width="100%">
            <tr>
                <td width="100%" class="text-center" style=" font-size:12px !important"><b>PROGRESS REPORT CAR</b>D</td>
            </tr>
            <tr>
                <td width="100%" class="text-center">School Year 2020 - 2021</td>
            </tr>
        </table>
                       
        <table class="table table-sm grades mb-1" width="100%" style="font-size: 10px !important">
            <tr>
                <td width="7%"><b>LRN:</b></td>
                <td width="83%" class="border-bottom">{{$student->lrn}}</td>
            </tr>
        </table>
        
        <table class="table table-sm grades mb-1" width="100%" style="font-size: 10px !important">
            @php
                $middle = '';
                if($student->middlename != null){
                    $temp_middle = explode(" ",$student->middlename);
                    foreach($temp_middle as $item){
                        $middle .=  $item[0].'.';
                    }
                }
            @endphp
            <tr>
                <td width="8%"><b>Name:</b></td>
                <td width="82%" class="border-bottom">{{$student->lastname}}, {{$student->firstname}} {{$middle}}</td>
            </tr>
        </table>
        
        <table class="table table-sm grades mb-1" width="100%" style="font-size: 10px !important">
            <tr>
                <td width="5%"><b>Age:</b></td>
                <td width="10%" class="border-bottom">{{\Carbon\Carbon::parse($student->dob)->age}}</td>
                <td width="5%"><b>Sex:</b></td>
                <td width="10%" class="border-bottom">{{$student->gender}}</td>
                <td width="23%" style="font-size: 8px !important"><b>Grade and Section:</b></td>
                <td width="47%" style="font-size: {{strlen($student->levelname.' - '.$student->sectionname) < 26 ? '8px' : '7px'}} !important" class="border-bottom">{{$student->levelname}} - {{$student->sectionname}}</td>
            </tr>
        </table>
         <table class="table table-sm grades mb-1" width="100%" style="font-size: 10px !important">
            <tr>
                <td width="7%"><b>Track:</b></td>
                <td width="43%" class="border-bottom" style="font-size: {{strlen($strandInfo[0]->trackname) < 29 ? '10px' : '9px'}} !important">{{$strandInfo[0]->trackname}}</td>
                <td width="7%"><b>Strand:</b></td>
                <td width="43%" class="border-bottom">{{$strandInfo[0]->strandcode}}</td>
            </tr>
        </table>
        
         <table class="table table-sm grades mb-1" width="100%" style="font-size: 10px !important">
            <tr>
                <td width="39%"><b>Specialization (for TVL only): </b></td>
                <td width="61%" class="border-bottom">@if($strandInfo[0]->strandcode == 'TVL-ICT') CSS @elseif($strandInfo[0]->strandcode == 'TVL-IA')SMAW @endif</td>
            </tr>
        </table>
        
        <table class="table table-sm grades" width="100%">
            <tr>
                <td width="100%">
                    <p style="font-size:9px;margin:5px !important">Dear Parents,</p>
                    <p style="font-size:9px!important; text-indent: 50px;margin:5px !important">Education is lifetime process. It depends not only on the school or child but also upon the interest of the parents in what the child and school are doing.</p>
                    <p style="font-size:9px!important; text-indent: 50px;margin:5px !important">We wish to extend to you the challenge and invitation of continued education. Also we extend to you the opportunity to confer with us concerning your child’s progress.</p>
                </td>
            </tr>
        </table>
        <table class="table table-sm grades mt-2" width="100%">
            <tr>
                <td class="border-bottom text-center" width="45%">MRS. JUVY B. LINGUIZ</td>
                <td width="10%"></td>
                <td class="border-bottom text-center" width="45%">{{$adviser}}</td>
            </tr>
            <tr>
                <td class="text-center" width="45%"><i>School Principal</i></td>
                <td width="10%"></td>
                <td class="text-center" width="45%"><i>Adviser</i></td>
            </tr>
        </table>
        <table class="table table-sm grades mb-1" width="100%">
            <tr>
                <td class="text-center"><b>CERTIFICATE OF TRANSFER</b></td>
            </tr>
        </table>
        <table class="table table-sm grades mb-1" width="100%">
            <tr>
                <td width="28%">Admitted to Grade</td>
                <td width="22%" class="border-bottom"></td>
                <td width="10%">Section</td>
                <td width="40%" class="border-bottom"></td>
            </tr>
        </table>
        <table class="table table-sm grades" width="100%">
            <tr>
                <td width="40%">Eligibility for admission to Grade</td>
                <td width="60%" class="border-bottom"></td>
            </tr>
        </table>
        <table class="table table-sm grades" width="100%">
            <tr>
                <td width="33%">Approved: </td>
                <td width="67%"></td>
            </tr>
        </table>
        <br>
        <table class="table table-sm grades mt-2" width="100%">
            <tr>
                <td class="border-bottom text-center" width="45%">MRS. JUVY B. LINGUIZ</td>
                <td width="10%"></td>
                <td class="border-bottom text-center" width="45%">{{$adviser}}</td>
            </tr>
            <tr>
                <td class="text-center" width="45%"><i><i>School Principal</i></td>
                <td width="10%"></td>
                <td class="text-center" width="45%"><i>Adviser</i></td>
            </tr>
        </table>
       
        <table class="table table-sm grades" width="100%">
            <tr>
                <td class="text-center"><b>CANCELLATION FOR TRANSFER AND ELIGIBILITY</b></td>
            </tr>
        </table>
        <table class="table table-sm grades" width="100%">
            <tr>
                <td width="15%">Admitted in </td>
                <td width="50%" class="border-bottom"></td>
                <td width="9%">Date:</td>
                <td width="26%" class="border-bottom"></td>
            </tr>
        </table>
        <br>
         <table class="table table-sm grades" width="100%">
            <tr>
               
                <td width="20%"></td>
                <td class="border-bottom text-center" width="40%"></td>
                <td width="20%"></td>
            </tr>
            <tr>
              
                <td ></td>
                <td class="text-center"><i>Principal</i></td>
                <td ></td>
            </tr>
        </table>
                


                    
                <table class="table table-sm mb-0 table-bordered" width="100%" style="font-size:8px !important;background-color:blue; ">
                    <tr>
                        <td class="p-1" style="color:white"><center>REPORT ON ATTENDANCE</center></td>
                    </tr>
                </table >
                <table class="table table-sm table-bordered mb-0" width="100%" style="font-size:8px !important">
                    @php
                         $width = count($attendance_setup) != 0? 65 / count($attendance_setup) : 0;
                    @endphp
                    <tr>
                        <th width="25%"></th>
                        @foreach ($attendance_setup as $item)
                            <th class="text-center align-middle"  width="{{$width}}%">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
                        @endforeach
                        <th class="text-center text-center"  width="10%">Total </th>
                    </tr>
                    <tr>
                        <td>No. of School Days</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->days}}</td>
                        @endforeach
                        <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                    </tr>
                    <tr >
                        <td>No. of School Days Present</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->present}}</td>
                        @endforeach
                        <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('present')}}</th>
                    </tr>
                    <tr>
                        <td  >No. of Times Absent</td>
                        @foreach ($attendance_setup as $item)
                            <td class="align-middle text-center p-0">{{$item->absent}}</td>
                        @endforeach
                        <th class="text-center align-middle" >{{ collect($attendance_setup)->sum('absent')}}</td>
                    </tr>
                </table>
              
                <table class="table table-sm table-bordered mb-0" width="100%" style="font-size:8px !important;background-color:blue; color:white;">
                    <tr clas=" border-blue">
                        <td class="text-center p-1" width="25%" >QUARTER</td>
                        <td class="text-center p-1" width="50%" >PARENT’S / GUARDIAN’S SIGNATURE</td>
                        <td class="text-center p-1" width="25%" >DATE</td>
                    </tr>
                </table>
                <table class="table table-sm table-bordered" width="100%" style="font-size:8px !important;">
                    <tr>
                        <td class="p-1"  width="25%">First</td>
                        <td class="p-1"  width="50%"></td>
                        <td class="p-1"  width="25%"></td>
                    </tr>
                    <tr>
                        <td class="p-1">Second</td>
                        <td class="p-1"></td>
                        <td class="p-1"></td>
                    </tr>
                    <tr>
                        <td class="p-1">Third</td>
                        <td class="p-1"></td>
                        <td class="p-1"></td>
                    </tr>
                    <tr>
                        <td class="p-1">Fourth</td>
                        <td class="p-1"></td>
                        <td class="p-1"></td>
                    </tr>
                </table >
                <br>
                <br>
                @for ($x=1; $x <= 2; $x++)
                    <table  class="table table-sm grades mb-0 table-bordered order-blue" width="100%" style="background-color:blue; color:white; page-break-inside:avoid; ">
                        <tr >
                            <td colspan="5"  class="align-middle text-center" width="100%"><b>REPORT ON PROGRESS AND ACHIEVEMENT  - Sem. {{$x}}</b></td>
                        </tr>
                    </table>
                    <table class="table table-sm table-bordered  table-bordered border-blue mb-0 grades-7" width="100%">
                        <tr>
                            <td width="60%" rowspan="2"  class="align-middle text-center"><b>Learning Areas</b></td>
                            <td width="20%" colspan="2"  class="text-center align-middle" ><b>Quarter</b></td>
                            <td width="10%" rowspan="2"  class="text-center align-middle" ><b>Final Rating</b></td>
                            <td width="10%" rowspan="2"  class="text-center align-middle" style="font-size:6px !important"><b>Remarks</b></td>
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
                                <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                @if($x == 1)
                                    <td class="text-center align-middle {{$item->quarter1 != null ? $item->quarter1 < 75 ? 'bg-red':'':'' }}">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                    <td class="text-center align-middle {{$item->quarter2 != null ? $item->quarter2 < 75 ? 'bg-red':'':'' }}">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                @elseif($x == 2)
                                    <td class="text-center align-middle {{$item->quarter3 != null ? $item->quarter3 < 75 ? 'bg-red':'':'' }}">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                    <td class="text-center align-middle {{$item->quarter4 != null ? $item->quarter4 < 75 ? 'bg-red':'':'' }}">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                @endif
                                <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                <td class="text-center align-middle" style="font-size:6px !important">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            @php
                                $genave = collect($finalgrade)->where('semid',$x)->first();
                            @endphp
                            <td colspan="3"><b>GENERAL AVERAGE</b></td>
                            {{-- @if($x == 1)
                                <td class="text-center align-middle">{{isset($genave->quarter1) ? $genave->quarter1 != null ? $genave->quarter1:'' : ''}}</td>
                                <td class="text-center align-middle">{{isset($genave->quarter2) ? $genave->quarter2 != null ? $genave->quarter2:'' : ''}}</td>
                            @elseif($x == 2)
                                <td class="text-center align-middle">{{isset($genave->quarter3) ? $genave->quarter3 != null ? $genave->quarter3:'' : ''}}</td>
                                <td class="text-center align-middle">{{isset($genave->quarter4) ? $genave->quarter4 != null ? $genave->quarter4:'' : ''}}</td>
                            @endif --}}
                            <td class="text-center align-middle {{ isset($genave->finalrating) ? $genave->finalrating != null ? number_format($genave->fcomp,2) < 75 ? 'bg-red':'' :'' :''}}">{{ isset($genave->finalrating) ? $genave->finalrating != null ? number_format($genave->fcomp,2):'' :''}}</td>
                            <td class="text-center align-middle" style="font-size:6px !important">{{ isset($genave->actiontaken) ? $genave->actiontaken != null ? $genave->actiontaken:'' :''}}</td>
                        </tr>
                    </table>
                @endfor
                <table class="table table-sm grades" width="100%" style="font-size:8px !important">
                    <tr>
                        <td width="10%" class="p-0"></td>
                        <td width="30%" class="p-0"><b>DESCRIPTORS</b></td>
                        <td width="30%" class=" text-center p-0"><b>GRADING SCALE</b></td>
                        <td width="30%" class=" text-center p-0"><b>REMARKS</b></td>
                    </tr>
                    <tr>
                        <td class="p-0"></td>
                        <td class="p-0">Outstanding</td>
                        <td class=" text-center p-0">90-100</td>
                        <td class=" text-center p-0">Passed</td>
                    </tr>
                    <tr> 
                        <td class="p-0"></td>
                        <td class="p-0">Very Satisfactory</td>
                        <td class=" text-center p-0">85-89</td>
                        <td class=" text-center p-0">Passed</td>
                    </tr>
                    <tr>
                        <td class="p-0"></td>
                        <td class="p-0">Satisfactory</td>
                        <td class=" text-center p-0">80-84</td>
                        <td class=" text-center p-0">Passed</td>
                    </tr>
                    <tr>
                        <td class="p-0"></td>
                        <td class="p-0">Fairly Satisfactory</td>
                        <td class=" text-center p-0">75-79</td>
                        <td class=" text-center  p-0">Passed</td>
                    </tr>
                    <tr>
                        <td class="p-0"></td>
                        <td class="p-0">Did Not Meet Expectations</td>
                        <td class=" text-center  p-0">Below 75</td>
                        <td class=" text-center  p-0">Failed</td>
                    </tr>
                </table>
                <table  class="table table-sm grades mb-0  table-bordered border-blue" width="100%" style="background-color:blue; color:white">
                    <tr>
                        <td class="text-center "><b>REPORT ON LEARNER'S OBSERVED VALUES</b></td>
                    </tr>
                </table>
                <table class="table table-bordered grades border-blue mb-0"  width="100%" style="font-size:8px !important">
                
                        <tr>
                            <td rowspan="2" width="18%" class="align-middle p-1 text-center">Core Values</td>
                            <td rowspan="2" width="46%" class="align-middle  p-1  text-center">Behavior Statements</td>
                            <td colspan="2" class="cellRight  p-1  text-center" width="20%">1<sup>st</sup> Sem.</td>
                            <td colspan="2" class="cellRight  p-1  text-center" width="20%">2<sup>nd</sup> Sem.</td>
                        </tr>
                        <tr>
                            <td width="7%" class="p-1 align-middle text-center">Q1</td>
                            <td width="7%" class="p-1 align-middle text-center">Q2</td>
                            <td width="7%" class="p-1 align-middle text-center">Q3</td>
                            <td width="7%" class="p-1 align-middle text-center">Q4</td>
                        </tr>
                   
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
                                                    <td class="align-middle p-1" rowspan="{{count($groupitem)}}" style="font-size: 7px !important">
                                                        {{$item->group}}
                                                    </td>
                                                    @php
                                                        $count = 1;
                                                    @endphp
                                            @endif
                                            <td class="align-middle p-1" style="font-size:7px !important" >
                                                {{$item->description}}
                                            </td>
                                            <td colspan="4" style="font-size:8px !important" class="align-middle text-center p-1">Learning Modality: Modular (Printed)</td>
                                          
                                        </tr>
                                @endif
                            @endforeach
                        @endforeach
                        
                </table>
                <table class="table table-sm grades" width="100%" style="font-size:8px !important">
                 
                        <tr>
                            <td width="25%" class="p-0"></td>
                            <td width="20%" class="p-0 text-center"><b>Marking</b></td>
                            <td width="30%" class="p-0 text-center"><b>Non- Numerical Rating</b></td>
                            <td width="25%" class="p-0"></td>
                        </tr>
                    
                         @foreach ($rv as $key=>$rvitem)
                            @if($rvitem->value != null)
                                <tr>
                                    <td class="p-0"></td>
                                    <td class="text-center p-0">{{$rvitem->value}}</td>
                                    <td class="p-0 text-center">{{$rvitem->description}}</td>
                                    <td class="p-0"></td>
                                 </tr>
                            @endif
                        @endforeach 
                    
                </table>
              
          
</div>

</body>
</html>