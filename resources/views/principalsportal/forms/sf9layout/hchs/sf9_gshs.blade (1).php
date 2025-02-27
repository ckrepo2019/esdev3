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

        @page { size: 4.3in 8.5in; margin: .2in .1in;  }
        
        
        .watermark {
            position: absolute;
            color: lightgray;
            opacity: 20;
            font-size: 11px !important;
            width: 100%;
            top: 700px;    
            text-align: center;
            z-index: -50;
        }
        
    </style>
</head>
<body>  

  
      <table class="table table-sm grades mb-1"  width="100%" style="font-size:13px ; ">
            <tr>
                <td class="p-0" style="font-size:8px !important" colspan="3">
                    DepEd Form 138 - 
                    @if($acad == 3)
                        Elementary
                    @elseif($acad == 4)
                        JHS
                    @elseif($acad == 4)
                        Pre-school
                    @endif
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
        
        <table class="table table-sm grades mb-1" width="100%" style="font-size: 10px !important; margin-bottom:10px !important">
            <tr>
                <td width="5%"><b>Age:</b></td>
                <td width="10%" class="border-bottom">{{$student->age}}</td>
                <td width="5%"><b>Sex:</b></td>
                <td width="10%" class="border-bottom">{{$student->gender}}</td>
                <td width="23%" style="font-size: 8px !important"><b>Grade and Section:</b></td>
                <td width="47%" style="font-size: 8px !important" class="border-bottom">{{$student->levelname}}{{$student->levelid != 3 ?  ' - '.$student->sectionname:''}}</td>
            </tr>
        </table>
           <table class="table table-sm grades" width="100%">
            <tr>
                <td width="100%">
                    <p style="font-size:9px;margin:0 !important">Dear Parents,</p>
                    <p style="font-size:9px!important; text-indent: 50px;margin:0 !important">This report card shows the ability and progress your child has made in the different learning areas as well as his/her core values.</p>
                    <p style="font-size:9px!important; text-indent: 50px;margin:0 !important">The school welcomes you if you desire to know more about the progress of your child.</p>
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
                <td width="22%">Admitted to Grade</td>
                <td width="30%" class="border-bottom"></td>
                <td width="10%">Section</td>
                <td width="40%" class="border-bottom"></td>
            </tr>
        </table>
        <table class="table table-sm grades" width="100%">
            <tr>
                <td width="35%">Eligibility for admission to Grade</td>
                <td width="65%" class="border-bottom"></td>
            </tr>
        </table>
        <table class="table table-sm grades mb-1" width="100%">
            <tr>
                <td width="33%">Approved: </td>
                <td width="67%"></td>
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
         <table class="table table-sm grades mt-2" width="100%">
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
                <table class="table table-sm table-bordered" width="100%" style="font-size:8px !important">
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
                <!--<div class="watermark">Learning Modality: Modular (Printed)</div>-->
                <table  class="table table-sm grades mb-0 table-bordered order-blue" width="100%" style="background-color:blue; color:white">
                    <tr>
                        <th class="text-center p-0" style="border-bottom:0">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
                    </tr>
                    <tr>
                        <th class="text-center p-0" style="font-size:7px !important; border-top:0"><i></i>Grading System Used: Averaging</i></th>
                    </tr>
                </table>  
                <table class="table table-bordered table-sm grades border-blue" width="100%" >
                    <thead>
                        <tr>
                            <td rowspan="2"  class="align-middle text-center" width="40%"><b>SUBJECTS</b></td>
                            <td colspan="4"  class="text-center align-middle"><b>PERIODIC RATINGS</b></td>
                            <td rowspan="2"  class="text-center align-middle"><b>Final Rating</b></td>
                            <td rowspan="2"  class="text-center align-middle"><b>Action Takent</b></span></td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle" width="10%"><b>1</b></td>
                            <td class="text-center align-middle" width="10%"><b>2</b></td>
                            <td class="text-center align-middle" width="10%"><b>3</b></td>
                            <td class="text-center align-middle" width="10%"><b>4</b></td>
                        </tr>
                    </thead>
                    
                  
                    <tbody>
                        @foreach ($grades as $item)
                            <tr>
                                <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}" >{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                <td class="text-center align-middle {{$item->quarter1 != null ? $item->quarter1 < 75 ? 'bg-red':'':'' }}">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle {{$item->quarter2 != null ? $item->quarter2 < 75 ? 'bg-red':'':'' }}">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                <td class="text-center align-middle {{$item->quarter3 != null ? $item->quarter3 < 75 ? 'bg-red':'':'' }}">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle {{$item->quarter4 != null ? $item->quarter4 < 75 ? 'bg-red':'':'' }}">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                <td class="text-center align-middle {{$item->finalrating != null ? $item->finalrating < 75 ? 'bg-red':'':'' }}">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                <td class="text-center align-middle">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5"><b>GENERAL AVERAGE</b></td>
                            <td class="text-center align-middle {{$finalgrade[0]->finalrating != null ? $finalgrade[0]->finalrating < 75 ? 'bg-red':'':'' }}">{{$finalgrade[0]->finalrating != null ? number_format($finalgrade[0]->fcomp,2):''}}</td>
                            <td class="text-center align-middle">{{$finalgrade[0]->actiontaken != null ? $finalgrade[0]->actiontaken:''}}</td>
                        </tr>
                </table>  
                <table class="table table-sm grades" width="100%" style="font-size:8px !important">
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
                <table  class="table table-sm grades mb-0  table-bordered border-blue" width="100%" style="background-color:blue; color:white">
                    <tr>
                        <th class="text-center ">REPORT ON LEARNER'S OBSERVED VALUES</th>
                    </tr>
                </table>
                <table class="table table-bordered grades border-blue"  width="100%" style="font-size:9px !important">
                    <thead>
                        <tr>
                            <th rowspan="2" width="18%" class="align-middle p-0"><center >Core Values</center></th>
                            <th rowspan="2" width="46%" class="align-middle  p-0"><center>Behavior Statements</center></th>
                            <th colspan="4" class="cellRight  p-0" width="40%"><center>Quarter</center></th>
                        </tr>
                        <tr>
                            <th width="8%" class="p-0 align-middle text-center">1</th>
                            <th width="8%" class="p-0 align-middle text-center">2</th>
                            <th width="8%" class="p-0 align-middle text-center">3</th>
                            <th width="8%" class="p-0 align-middle text-center">4</th>
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
                                            <th colspan="6" >{{$item->description}}</th>
                                        </tr>
                                @else
                                        <tr>
                                            @if($count == 0)
                                                    <td class="align-middle" rowspan="{{count($groupitem)}}" style="font-size: 8px !important">
                                                        {{$item->group}}
                                                    </td>
                                                    @php
                                                        $count = 1;
                                                    @endphp
                                            @endif
                                            <td class="align-middle" >
                                                {{$item->description}}
                                            </td>
                                            <td colspan="4" style="font-size:8px !important" class="align-middle text-center p-1">Learning Modality: Modular (Printed)</td>
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
              
          
</div>

</body>
</html>