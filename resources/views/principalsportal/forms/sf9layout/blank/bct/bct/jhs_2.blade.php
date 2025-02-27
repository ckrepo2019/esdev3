<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$student[0]->firstname.' '.$student[0]->middlename[0].'. '.$student[0]->lastname}}</title>
    
    
    <style>
            .table {
                width: 100%;
                margin-bottom: 1rem;
                background-color: transparent;
                font-size:10px !important;
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
                padding: .10rem !important;
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


            /*.p-0{*/
            /*    padding: 0 !important;*/
            /*}*/

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
            
            .mt-0{
                margin-top: 0;    
            }

           
         
        </style>
    
</head>
<body>
<table class="table table-sm mb-0" width="100%">
    <tr>
        <td style="width: 25%;" class="p-0" style="padding: 0 !important">School Form 9</td>
        <td style="width: 50%; " class="text-center p-0" style="padding: 0 !important"><b>Republic of the Philippines</b></td>
        <td style="width: 25%;"  class="text-center align-middle p-0" rowspan="7" style="padding: 0 !important">
            <img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="90px">
        </td>
    </tr>
    <tr>
        <td  class="text-center align-middle p-0" rowspan="6" style="padding: 0 !important">
            <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="70px">
            </td>
        <td class="text-center p-0" style="padding: 0 !important"><b>Department of Education - Region XI</b></td>
    </tr>
    <tr>
        <td class="text-center p-0" style="padding: 0 !important"><b>Davao City - District III</b></td>
    </tr>
    <tr>
        <td class="text-center p-0" style="padding: 0 !important"><b>BROKENSHIRE COLLEGE TORIL, DAVAO CITY, INC.</b></td>
    </tr>
    <tr>
        <td class="text-center p-0" style="padding: 0 !important"><b>Toril, Davao City, Philippines 8000</b></td>
    </tr>
    <tr>
        <td class="text-center p-0" style="padding: 0 !important"><b>School ID: 405500</b></td>
    </tr> 
    <tr>
        <td class="text-center" style="padding: 0 !important"><b>Tel. # 291-2556</b></td>
    </tr>
</table>
<table class="table table-sm table-bordered mb-0" width="100%" style="margin-top:1rem !important">
    <tr>
        <td class="text-center">School Year 2021-2022</td>
    </tr>
</table>
<table class="table table-sm mb-0" width="100%" style="background-color:#92d050">
    <tr>
        <td class="text-center">
            @if($acad == 2)
            
            @elseif($acad == 3)
                GRADE SCHOOL DEPARTMENT
            @elseif($acad == 4)
                JUNIOR HIGH DEPARTMENT
            @endif
        </td>
    </tr>
</table>
<table class="table table-sm mb-0" width="100%">
      <tr>
        <td width="10%" >NAME</td>
        <td width="40%" colspan="4" style="border-bottom: 1px solid">{{$student[0]->lastname}}, {{$student[0]->firstname}} {{$student[0]->middlename}} </td>
        <td width="25%" colspan="3">Learner's Reference Number (LRN):</td>
        <td width="25%" style="border-bottom: 1px solid">{{$student[0]->lrn}}</td>
      </tr>
</table>
<table class="table table-sm" width="100%">
      <tr>
        <td width="10%">AGE:</td>
        <td width="20%" class="border-bottom">{{\Carbon\Carbon::parse($student[0]->dob)->age}}</td>
        <td width="5%">SEX:</td>
        <td width="10%" class="border-bottom">{{$student[0]->gender}}</td>
        <td width="14%">GRADE / SECTION :</td>
        <td width="43%" class="border-bottom">{{$student[0]->levelname}} / {{$student[0]->sectionname}}</td>
    </tr>
</table>

<table class="table table-sm table-bordered"  width="100%">
    <thead>
        <tr>
            <td colspan="7">
                <center><strong>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</strong></center>
            </td>
        </tr>
        <tr>
            <th width="40%"><center>Subjects</center></th>
            <th width="10%" class="text-center align-middle"><center>Q1</center></th>
            <th width="10%" class="text-center align-middle"><center>Q2</center></th>
            <th width="10%" class="text-center align-middle"><center>Q3</center></th>
            <th width="10%" ="text-center align-middle"><center>Q4</center></th>
            <th width="10%" class="text-center align-middle"><center>FINAL RATING</center></th>
            <th width="10%" class="text-center align-middle"><center>REMARKS</center></th>
        </tr>
        
    </thead>
    <tbody>
         @foreach ($grades as $item)
            <tr>
                <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}} !important;" >{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                <td class="text-center align-middle">{{$item->finalrating != null && $item->subjCom == null ? $item->finalrating:''}}</td>
                <td class="text-center align-middle">{{$item->actiontaken != null && $item->subjCom == null ? $item->actiontaken:''}}</td>
            </tr>
        @endforeach
        <tr>
            <td class="text-right" colspan="5">GENERAL AVERAGE</td>
            <td class="text-center">{{collect($finalgrade)->first()->finalrating}}</td>
            <td class="text-center align-middle" >{{collect($finalgrade)->first()->actiontaken}}</td>
        </tr>
    </tbody>
</table>

    <table class="table table-sm table-bordered">
        <tr>
            <th class="text-center">Descriptors</th>
            <th class="text-center">Grading Scale</th>
            <th class="text-center">Remarks</th>
        </tr>
        <tr>
            <td class="text-center">Outstanding</td>
            <td class="text-center" class="text-center">90-100</td>
            <td class="text-center">PASSED</td>
        </tr>
        <tr>
            <td class="text-center">Very Satisfactory</td>
            <td class="text-center">85-89</td>
            <td class="text-center">PASSED</td>
        </tr>
        <tr>
            <td class="text-center">Satisfactory</td>
            <td class="text-center" class="text-center">80-84</td>
            <td class="text-center">PASSED</td>
        </tr>
        <tr>
            <td class="text-center">Fairly Satisfactory</td>
            <td class="text-center">75-79</td>
            <td class="text-center">PASSED</td>
        </tr>
        <tr>
            <td class="text-center">Did Not Meet Expectations</td>
            <td class="text-center">Below 75</td>
            <td class="text-center">FAILED</td>
        </tr>
    </table>

    <table class="table table-sm table-bordered" width="100%">
        <thead>
            <tr>
                <td colspan="{{count($homeroomsetup)}}" class="text-center">HOMEROOM GUIDANCE SCALE VALUE</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach ($homeroomsetup as $item)
                    @if($item->value != null)
                        <td class="text-center align-middle">{{$item->value}} - {{$item->description}}</td>
                    @endif
                @endforeach 
            </tr>
        </tbody>
    </table>
    
    <table class="table table-sm table-bordered mb-0"  width="100%" >
        <thead>
            <tr>
                <th colspan="6" ><center>REPORT ON LEARNER'S OBSERVED VALUES</center></th>
            </tr>
            <tr>
                <th rowspan="2" width="15%"><center>Core Values</center></th>
                <th rowspan="2" width="45%"><center>Behavior Statements</center></th>
                <th colspan="4" class="cellRight" width="40%"><center>Quarter</center></th>
            </tr>
            <tr>
                <th><center>1</center></th>
                <th ><center>2</center></th>
                <th ><center>3</center></th>
                <th  class="cellRight"><center>4</center></th>
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
                                        <td class="text-center align-middle" rowspan="{{count($groupitem)}}">{{$item->group}}</td>
                                        @php
                                            $count = 1;
                                        @endphp
                                @endif
                                <td class="align-middle">{{$item->description}}</td>
                                <td class="text-center">
                                    @foreach ($rv as $key=>$rvitem)
                                        @if($item->q1eval == $rvitem->id)
                                            {{$rvitem->value}}
                                        @endif
                                    @endforeach 
                                </td>
                                <td class="text-center">
                                    @foreach ($rv as $key=>$rvitem)
                                        @if($item->q2eval == $rvitem->id)
                                            {{$rvitem->value}}
                                        @endif
                                    @endforeach 
                                </td>
                                {{-- @if($acad != 5) --}}
                                <td class="text-center">
                                    @foreach ($rv as $key=>$rvitem)
                                        @if($item->q3eval == $rvitem->id)
                                            {{$rvitem->value}}
                                        @endif
                                    @endforeach 
                                </td>
                                <td class="text-center">
                                    @foreach ($rv as $key=>$rvitem)
                                        @if($item->q4eval == $rvitem->id)
                                            {{$rvitem->value}}
                                        @endif
                                    @endforeach 
                                </td>
                                {{-- @endif --}}
                            </tr>
                    @endif
                @endforeach
            @endforeach
            <tr>
                <td colspan="6" style="background-color:#ed6c64;" class="text-center">Homeroom Guidance</td>
            </tr>
            <tr>
                <td class="text-center">HGLDA</td>
                <td>Homeroom Guidance Learner's Development Assessment</td>
                <td class="text-center">{{$finalhomeroom[0]->q1 != 0 ? $finalhomeroom[0]->q1 : ''}}</td>
                <td class="text-center">{{$finalhomeroom[0]->q2 != 0 ? $finalhomeroom[0]->q2 : ''}}</td>    
                <td class="text-center">{{$finalhomeroom[0]->q3 != 0 ? $finalhomeroom[0]->q3 : ''}}</td>
                <td class="text-center">{{$finalhomeroom[0]->q4 != 0 ? $finalhomeroom[0]->q4 : ''}}</td>
            </tr>
            <tr>
                <td colspan="6"><center>Observed Values</center></td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-sm" width="100%">
        <tbody>
            <tr>
                @foreach ($rv as $key=>$rvitem)
                    @if($rvitem->value != null)
                        <td style="text-center align-middle">{{$rvitem->value}} - {{$rvitem->description}}</td>
                    @endif
                @endforeach 
            </tr>
        </tbody>
    </table>

    
   
    <table class="table table-sm table-bordered mb-1" width="100%" style="font-size:8px !important" >
        <tr>
            <td class="text-center" colspan="{{count($attendance_setup) + 2}}"><b>REPORT ON ATTENDANCE</b></td>
        </tr>
        <tr >
            <th></th>
            @foreach ($attendance_setup as $item)
                <th class="text-center align-middle" >{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
            @endforeach
            <th class="text-center">Total</th>
        </tr>
        <tr >
            <td class="text-center"  >No. of School Days</td>
            @foreach ($attendance_setup as $item)
                <td class="text-center align-middle">{{$item->days}}</td>
            @endforeach
            <th class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
        </tr>
        <tr>
            <td  class="text-center">No. of School Days Present</td>
            @foreach ($attendance_setup as $item)
                <td class="text-center align-middle">{{$item->present}}</td>
            @endforeach
            <th class="text-center align-middle">{{collect($attendance_setup)->sum('present')}}</th>
        </tr>
        <tr>
            <td  class="text-center">No. of School Day Absent</td>
            @foreach ($attendance_setup as $item)
                <td class="text-center align-middle">{{$item->absent}}</td>
            @endforeach
            <th class="text-center align-middle" >{{ collect($attendance_setup)->sum('absent')}}</td>
        </tr>
    </table>
    <table class="table table-sm" width="100%">
        <tbody>
            <tr>
               <td class="text-center"><b>Certificate of Transfer</b></td>
            </tr>
        </tbody>
    </table>
    @php        
        $gradelevel = DB::table('gradelevel')->where('id',$student[0]->levelid)->first();
        $next_grade_level = DB::table('gradelevel')
                            ->where('sortid','>=',$gradelevel->sortid)
                            ->where('deleted',0)
                            ->orderBy('sortid')
                            ->skip(1)
                            ->take(1)
                            ->get();
    @endphp
    <table class="table table-sm" width="100%">
        <tbody>
            <tr>
                <td width="14%">Admitted to Grade:</td>
                <td width="11%" class="border-bottom text-center">{{$student[0]->levelname}}</td>
                <td width="6%" >Section: </td>
                <td width="14%" class="border-bottom  text-center">{{$student[0]->sectionname}}</td>
                <td width="21%" >Eligible for Admission to Grade:</td>
                <td width="34%" class="border-bottom text-center">
                     {{-- @if(number_format( $ge / collect($grades)->where('mapeh',0)->count() ) >= 75)
                        {{$next_grade_level[0]->levelname}}
                     @else
                     
                     @endif --}}
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <br>
     <table class="table table-sm" width="100%">
        <tbody>
            <tr>
                <td width="50%"  class="text-center"><u>{{$adviser}}</u></td>
                <td width="50%"  class="text-center"><u>ARTHELO P. PALMA, PhD</u></td>
            </tr>
             <tr>
                <td width="50%" class="text-center">Adviser</td>
                <td width="50%"  class="text-center">Academic Affairs Head</td>
            </tr>
        </tbody>
    </table>

</body>
</html>