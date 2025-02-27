<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
            .table {
                width: 100%;
                margin-bottom: 1rem;
                background-color: transparent;
                font-size:11px !important;
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
                padding: 0 ;
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
                margin-bottom: 0 !important;
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


            .coreval td, .coreval th{
                font-size: 9px !important;
                padding: .05rem ;
            }
            
            .mb-1{
                margin-bottom: .25rem !important;
            }
            
            .mb-2{
                margin-bottom: .5rem !important;
            }
            
          

            @page {  
                margin:0;
                
            }
            body { 
                margin:0;
              
            }
            
            

         
        </style>
    </head>
    <body>
          <table class="table table-bordered-invi" width="100%" >
            <tr>
                <td width="50%" style="padding-left:30px !important;padding-right:30px !important; padding-top:20px !important;">
                    <table  class="table table-sm mb-0" width="100%" style="font-size:8px !important">
                        <tr>
                            <td>DepEd Form 138</td>
                        </tr>
                    </table>
                    <table  class="table table-sm text-center" width="100%">
                        <tr>
                            <td width="30%" rowspan="4" class="align-middle text-right"> <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="60px"></td>
                            <td width="40%">Republic of the Philippines</td>
                            <td width="30%" rowspan="4" class="align-middle text-left"> <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="60px"></td>
                         </tr>
                        <tr>
                            <td><b>DEPARTMENT OF EDUCATION</b></td>
                        </tr>
                        <tr>
                            <td>Region X - Northern Mindanao</td>
                        </tr>
                        <tr>
                            <td><b>Division of Ozamiz City</b></td>
                        </tr>
                    </table>
                    <table  class="table table-sm text-center" width="100%">
                        <tr>
                            <td style="font-size: 20px !important"><b>{{$schoolinfo[0]->schoolname}}</b></td>
                        </tr>
                        <tr>
                            <td ><i>{{$schoolinfo[0]->address}}</i></td>
                        </tr>
                        <tr>
                            <td><i>School I.D. No. {{$schoolinfo[0]->schoolid}}</i></td>
                        </tr>
                    </table>
                    <table  class="table table-sm text-center" width="100%">
                        <tr>
                            <td style="font-size: 15px !important"><b>PROGRESS REPORT</b></td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px !important"><b>SY <u>{{Session::get('schoolYear')->sydesc}}</u><b></td>
                        </tr>
                    </table>
                    <br>
                    <table  class="table table-sm mb-0 mb-1" width="100%">
                        <tr>
                            <td width="10%">Name:</td>
                            <td width="90%" class="border-bottom  text-center"> {{$student->student}}</td>
                        </tr>
                    </table>
                    <table  class="table table-sm mb-0 mb-1" width="100%">
                        <tr>
                            <td width="5%">Age:</td>
                            <td width="20%" class="border-bottom text-center"> {{\Carbon\Carbon::parse($student->dob)->age}} </td>
                            <td width="5%">Sex:</td>
                            <td width="20%"  class="border-bottom text-center">{{$student->gender}}</td>
                            <td width="8%">LRN:</td>
                            <td width="62%"  class="border-bottom text-center">{{$student->lrn}}</td>
                        </tr>
                    </table>
                    <table  class="table table-sm mb-1" width="100%">
                        <tr>
                            <td width="7%">Grade:</td>
                            <td width="43%" class="border-bottom text-center"> {{str_replace("GRADE ","",$student->levelname)}}</td>
                            <td width="8%">Section:</td>
                            <td width="42%" class="border-bottom text-center"> 
                            {{-- {{str_replace("SECTION ","",$student->sectionname)}} --}}
                            1
                            </td>
                        </tr>
                    </table>
                    <br>
                    <label style="font-size:11px!important; margin-bottom:0 !important" ><b>Dear Parents</b></label>
                    <p style="font-size:11px!important; text-indent: 50px; margin-bottom:0 !important;" >This report shows the ability and the progress your child has made in the different learning areas as well as his/her progress in character development.</p>
                    <p style="font-size:11px!important; text-indent: 50px; margin-bottom:0 !important;" >The school welcomes you if you desire to know more about the progress of your child.</p>
                    <br>
                    <table  class="table table-sm mb-1" width="100%">
                        <tr>
                            @php
                                $midname = [];
                                $midnamestring = '';
                                if(count($midname) > 0){
                                    $midnamestring = substr($midname[0], 0, 1).'.';
                                }
                            @endphp
                            <td width="50%"></td>
                            <td width="50%" class="border-bottom text-center">{{$adviser}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center">Teacher</td>
                        </tr>
                        <tr>
                            @php
                                $midname = [];
                                $midnamestring = '';
                                if(count($midname) > 0){
                                    $midnamestring = substr($midname[0], 0, 1).'.';
                                }
                            @endphp
                            <td class="border-bottom text-center"><b>FELICIA C. MA ,M. Ed.</b></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="text-center">Principal</td>
                            <td></td>
                        </tr>
                    </table>
                    <hr>
                    <table  class="table table-sm text-center" width="100%">
                        <tr><td><b>CERTIFICATE OF TRANSFER</b></td></tr>
                    </table>
                    <Table class="table table-sm mb-0" width="100%">
                        <tr>
                            <td width="23%"><i>Admittted to Grade:<i></td>
                            <td width="27%" class="border-bottom text-center"></td>
                            <td width="10%"><i>Section:<i></td>
                            <td width="40%" class="border-bottom text-center"></td>
                        </tr>
                    </Table>
                    <Table class="table table-sm " width="100%">
                        <tr>
                            <td width="35%"><i>Eligible for admission to Grade:<i></td>
                            <td width="65%" class="border-bottom text-center"></td>
                        </tr>
                    </Table>
                    <table  class="table table-sm mb-1" width="100%">
                        <tr>
                            <td width="50%"><i>Approved:</i></td>
                            <td width="50%" class="border-bottom text-center">&nbsp;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center">Teacher</td>
                        </tr>
                        <tr>
                            @php
                                $midname = [];
                                $midnamestring = '';
                                if(count($midname) > 0){
                                    $midnamestring = substr($midname[0], 0, 1).'.';
                                }
                            @endphp
                            <td class="border-bottom text-center"><b>FELICIA C. MA ,M. Ed.</b></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="text-center">Principal</td>
                            <td></td>
                        </tr>
                    </table>
                    <hr>
                    <table  class="table-sm text-center p-0 table" width="100%">
                        <tr><td><b>CANCELATION OF ELIGIBILITY TO TRANSFER</b></td></tr>
                    </table>
                    <table  class="table-sm table p-0 table mb-0" width="100%">
                        <tr>
                            <td width="15%">Admitted in</td>
                            <td width="35%" class="border-bottom text-center"></td>
                            <td width="50%"></td>
                        </tr>
                    </table>
                    <table  class="table-sm table p-0" width="100%">
                        <tr>
                            <td width="5%">Date</td>
                            <td width="45%" class="border-bottom text-center"></td>
                            <td width="50%"></td>
                        </tr>
                    </table>
                    <table  class="table table-sm mb-1" width="100%">
                        <tr>
                            <td width="50%"><i></i></td>
                            <td width="50%" class="border-bottom text-center"><b>FELICIA C. MA ,M. Ed.</b></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center">Principal</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table class="table table-bordered-invi" width="100%" >
            <tr>
                <td width="100%" style="padding-left:30px !important;padding-right:30px !important; padding-top:20px !important">
                    <table class="table table-sm text-center mb-1">
                        <tr><td><b>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</b></td></tr>
                    </table>
                    <table class="table table-sm table-bordered mb-1" width="100%" style="font-size:10px !important">
                        <tr>
                            <th width="50%" rowspan="2"  class="align-middle text-center"><b>Learning Areas</b></th>
                        
                            <td width="30%" colspan="4"  class="text-center align-middle"><b>Quarter</td>
                        

                            <td width="10%" rowspan="2"  class="text-center align-middle p-0"  ><b>Final Grades</td>
                            <td width="10%" rowspan="2"  class="text-center align-middle p-0" ><b>Remarks</span></td>
                        </tr>
                        <tr style="font-size:11px !important;">z
                            <td class="text-center align-middle">1</td>
                            <td class="text-center align-middle">2</td>
                            <td class="text-center align-middle">3</td>
                            <td class="text-center align-middle">4</td>
                        </tr>
                        @foreach ($grades as $item)
                            <tr>
                                <td style="padding-left: {{$item->subjCom != null ? '1.5rem':'.5rem'}} !important;">{{$item->subjdesc}}</td>
                                <td class="text-center align-middle {{$item->quarter1 < 75 ? 'bg-red':''}}">{{$item->quarter1}}</td>
                                <td class="text-center align-middle  {{$item->quarter2 < 75 ? 'bg-red':''}}">{{$item->quarter2}}</td>
                                <td class="text-center align-middle  {{$item->quarter3 < 75 ? 'bg-red':''}}">{{$item->quarter3}}</td>
                                <td class="text-center align-middle  {{$item->quarter4 < 75 ? 'bg-red':''}}">{{$item->quarter4}}</td>
                                <td class="text-center align-middle  {{$item->finalrating < 75 ? 'bg-red':''}}"  width="10%">{{$item->finalrating}}</td>
                                <td class="text-center align-middle "  width="10%" >{{$item->actiontaken}}</td>
                            </tr>
                        @endforeach
                         <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                         <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="4" class="text-center"><b>General Average</b></td>
                            <td class="text-center">{{$finalgrade[0]->finalrating}}</td>
                            <td class="text-center">{{$finalgrade[0]->actiontaken}}</td>
                        </tr>
                    </table>
                    <table class="table table-sm mb-2" width="100%" style="font-size:9px !important">
                        <tr>
                            <td width="10%"></td>
                            <td width="30%"><b>DESCRIPTORS</b></td>
                            <td width="30%" class=" text-center"><b>GRADING SCALE</b></td>
                            <td width="30%" class=" text-center"><b>REMARKS</b></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Outstanding</td>
                            <td class=" text-center">90-100</td>
                            <td class=" text-center">Passed</td>
                        </tr>
                        <tr>
                            <td></td>   
                            <td>Very Satisfactory</td>
                            <td class=" text-center">85-89</td>
                            <td class=" text-center">Passed</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Satisfactory</td>
                            <td class=" text-center">80-84</td>
                            <td class=" text-center">Passed</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Fairly Satisfactory</td>
                            <td class=" text-center">75-79</td>
                            <td class=" text-center">Passed</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Did Not Meet Expectations</td>
                            <td class=" text-center">Below 75</td>
                            <td class=" text-center">Failed</td>
                        </tr>
                    </table>

                    <table class="table table-sm mb-1" width="100%"> 
                        <tr>
                            <th ><b>REPORT ON LEARNER'S OBSERVED VALUES</b></th>
                        </tr>
                    </table>
                    
                    <table class="table-sm table table-bordered coreval mb-2"  width="100%">
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
                                                        <td class="align-middle" rowspan="{{count($groupitem)}}" style="padding-left:.5rem !important">{{$item->group}}</td>
                                                        @php
                                                            $count = 1;
                                                        @endphp
                                                @endif
                                                <td class="align-middle" style="padding-left:.5rem !important">{{$item->description}}</td>
                                                <td class="text-center align-middle">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        @if($item->q1eval == $rvitem->id)
                                                            {{$rvitem->value}}
                                                        @endif
                                                    @endforeach 
                                                </td>
                                                <td class="text-center align-middle">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        @if($item->q2eval == $rvitem->id)
                                                            {{$rvitem->value}}
                                                        @endif
                                                    @endforeach 
                                                </td>
                                                 <td class="text-center align-middle">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        @if($item->q3eval == $rvitem->id)
                                                            {{$rvitem->value}}
                                                        @endif
                                                    @endforeach 
                                                </td>
                                                <td class="text-center align-middle">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        @if($item->q4eval == $rvitem->id)
                                                            {{$rvitem->value}}
                                                        @endif
                                                    @endforeach 
                                                </td>
                                            </tr>
                                    @endif
                                @endforeach
                            @endforeach
                            
                        </tbody>
                    </table>
                    <table  class="table table-sm mb-1" width="100%">
                        <tr>
                            <td class="text-center"><b>MARKING NON-NUMERICAL RATING</b></td>
                        </tr>
                    </table >
                    <table  class="table table-sm coreval mb-2" width="100%" style="font-size:9px !important;">
                        <tr>
                            <td width="10%">AO</td>
                            <td width="40%">Always Observed</td>
                            <td width="10%">RO</td>
                            <td width="40%">Rarely Observed</td>
                        </tr>
                        <tr>
                            <td width="10%">SO</td>
                            <td width="40%">Sometimes Observed</td>
                            <td width="10%">NO</td>
                            <td width="40%">Not Observed</td>
                        </tr>
                    </table >
                    <table  class="table table-sm mb-1" width="100%">
                        <tr>
                            <td class="text-center"><b>REPORT ON ATTENDANCE</b></td>
                        </tr>
                    </table >
                    <table class="table table-sm table-bordered coreval mb-2" width="100%">
                        <tr >
                            <th></th>
                            @foreach ($attendance_setup as $item)
                                <th class="text-center align-middle" >{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
                            @endforeach
                            <th class="text-center">Total</th>
                        </tr>
                        <tr >
                            <td style="padding-left: .25rem !important;">No. of School Days</td>
                            @foreach ($attendance_setup as $item)
                                <td class="text-center align-middle">{{$item->days}}</td>
                            @endforeach
                            <th class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left: .25rem !important;">No. of School Days Present</td>
                            @foreach ($attendance_setup as $item)
                                <td class="text-center align-middle">{{$item->present}}</td>
                            @endforeach
                            <th class="text-center align-middle">{{collect($attendance_setup)->sum('present')}}</th>
                        </tr>
                        <tr>
                            <td style="padding-left: .25rem !important;">No. of School Day Absent</td>
                            @foreach ($attendance_setup as $item)
                                <td class="text-center align-middle">{{$item->days - $item->present}}</td>
                            @endforeach
                            <th class="text-center align-middle" >{{ collect($attendance_setup)->sum('days') - collect($attendance_setup)->sum('present')}}</td>
                        </tr>
                    </table>
                    <table  class="table table-sm mb-0" width="100%">
                        <tr>
                            <td class="text-center"><b>PARENTS / GUARDIAN'S SIGNATURE</b></td>
                        </tr>
                    </table >
                    <table  class="table table-sm mb-0 coreval" width="100%">
                        <tr>
                            <td width="15%">1<sup>st</sup> Quarter</td>
                            <td width="35%" class="border-bottom"></td>
                            <td width="15%">3<sup>rd</sup> Quarter</td>
                            <td width="35%" class="border-bottom"></td>
                        </tr>
                        <tr>
                            <td>2<sup>nd</sup> Quarter</td>
                            <td class="border-bottom"></td>
                            <td>4<sup>th</sup> Quarter</td>
                            <td class="border-bottom"></td>
                        </tr>
                    </table >
                   
                </td>
                
            </tr>

        </table>
      
        
    </div>
</body>
</html>