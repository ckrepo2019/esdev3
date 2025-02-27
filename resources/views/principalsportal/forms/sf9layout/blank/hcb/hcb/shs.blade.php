<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
            table {
                  border-collapse: collapse;
            }

            .text-center {
                  text-align: center !important;
            }

            .table-bordered {
                  border: 1px solid black;
            }

            .table-bordered th,
            .table-bordered td {
                  border: 1px solid black;
            }
            @page {
            margin-left: 20px !important;
            margin-right: 20px !important;
            margin-top: 10px !important;
            margin-bottom: 10px !important;
        }
    </style>
    
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
                padding: 0;
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
            
            .pl-1{
                padding-left: .25rem !important;
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
            
        </style>
    
   
</head>
<body>
      <table width="100%">
            <tr>
                  <td width="50%" style="padding-right: 20px;padding-left: 20px;" valign="top">
                    <sup style="font-size: 10px;">School Form 9</sup>
                        <table class="table table-sm" width="100%" style="margin-bottom:1px solid black">
                             <tr>
                                   <td width="15%" rowspan="4">
                                        <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="60px">
                                   </td>
                                   <td width="70%" style="text-align: center;">HOLY CROSS OF BUNAWAN, INC.</td>
                                   <td width="15%" rowspan="4">
                                        <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="60px">
                                   </td>
                             </tr>
                             <tr>
                                 <td class="text-center">Km. 23 Bunawan, Davao City<td>
                             </tr>
                             <tr>
                                 <td class="text-center">Government Recognition # 183 S. 1968<td>
                             </tr>
                             <tr>
                                 <td class="text-center" style="font-size: 16px;font-weight: bold;">SENIOR HIGH SCHOOL REPORT CARD<td>
                             </tr>
                        </table>
                        <hr>
                        <sup style="font-size: 10px;font-weight: bold;">
                            <center></center>
                        </sup>
                        <table class="table table-sm" width="100%">
                            <tr>
                                  <td width="20%"></td>
                                  <td width="80%" class="text-center" style="border-bottom:solid 1px  black">{{$student[0]->firstname}} {{$student[0]->middlename[0]}}. {{$student[0]->lastname}}</td>
                                  <td width="20%"></td>
                            </tr>
                            <tr>
                                  <td></td>
                                  <td><center>Name of Student</center></td>
                                  <td></td>
                            </tr>
                        </table>
                        <table width="100%"  class="table table-sm mb-0" >
                                <tr>
                                      <td width="20%">Grade: </td>
                                      <td width="30%" style="border-bottom:solid 1px  black">{{$student[0]->levelname}}</td>
                                      <td width="15%">Section: </td>
                                      <td width="30%" style="border-bottom:solid 1px  black; font-size:{{strlen($student[0]->ensectname) > 20 ? '9px !important' : '11px !important'}}" >{{$student[0]->ensectname}}</td>
                                </tr>
                                <tr>
                                      <td >Curriculum:</td>
                                      <td style="border-bottom:solid 1px  black">K-12</td>
                                      <td >Sex: </td>
                                      <td style="border-bottom:solid 1px  black">{{$student[0]->gender}}<</td>
                                </tr>
                                <tr>
                                      <td >Birthdate: </td>
                                      <td  style="border-bottom:solid 1px  black">{{$student[0]->dob}}</td>
                                      <td >Age: </td>
                                      <td  style="border-bottom:solid 1px  black">{{\Carbon\Carbon::parse($student[0]->dob)->age}}</td>
                                </tr>
                                <tr>
                                      <td >School Year: </td>
                                      <td  style="border-bottom:solid 1px  black">{{$currentSchoolYear->sydesc}}</td>
                                      <td >LRN NO: </td>
                                      <td  style="border-bottom:solid 1px  black">{{$student[0]->lrn}}</td>
                                </tr>
                        </table>
                        <hr>
                        <sup style="font-size: 10px;font-weight: bold;">
                            <center></center>
                        </sup>
                        <table class="table table-sm" width="100%">
                            <tr>
                                <td class="text-center">REPORT ON LEARNER'S PROGRESS AND ACHIEVEMENT</td>
                            </tr>
                        </table>
                       
                        @for ($x=1; $x <= 2; $x++)
                            <table class="table table-sm mb-0" width="100%">
                                <tr>
                                    <td>{{$x == 1 ? '1ST SEMESTER' : '2ND SEMESTER'}}</td>
                                </tr>
                            </table>
                            <table class="table table-sm table-bordered grades" width="100%" style="font-size:10px !important">
                                <tr>
                                    <td rowspan="2" colspan="2"   class="align-middle text-center">SUBJECTS</td>
                                    <td width="20%" colspan="2"  class="text-center align-middle" >QUARTER</td>
                                    <td width="18%" rowspan="2"  class="text-center align-middle" >SEMESTER FINAL GRADE</td>
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
                                @foreach (collect($grades)->where('semid',$x)->where('type',1) as $item)
                                    <tr>
                                        <td width="15%" style="padding-left: .25rem !important" class="align-middle"><b><i>Core</i></b></td>
                                        <td width="47%" style="padding-left: .25rem !important; font-size:{{strlen($item->subjdesc) > 30 ? '9px !important' : '10px !important'}}">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                        @if($x == 1)
                                            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                        @elseif($x == 2)
                                            <td class="text-center align-middle">
                                                {{$item->quarter3 != null ? $item->quarter3:''}}
                                            </td>
                                            <td class="text-center align-middle">
                                                {{$item->quarter4 != null ? $item->quarter4:''}}
                                            </td>
                                        @endif
                                        <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                    </tr>
                                @endforeach
                                
                                @foreach (collect($grades)->where('semid',$x)->where('type',3) as $item)
                                    <tr>
                                        <td width="15%" style="padding-left: .25rem !important" class="align-middle"><b><i>Applied</i></b></td>
                                        <td width="47%" style="padding-left: .25rem !important; font-size:{{strlen($item->subjdesc) > 30 ? '9px !important' : '10px !important'}}">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                        @if($x == 1)
                                            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                        @elseif($x == 2)
                                            <td class="text-center align-middle">
                                                {{$item->quarter3 != null ? $item->quarter3:''}}
                                            </td>
                                            <td class="text-center align-middle">
                                                {{$item->quarter4 != null ? $item->quarter4:''}}
                                            </td>
                                        @endif
                                        <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                    </tr>
                                @endforeach
                                
                                @foreach (collect($grades)->where('semid',$x)->where('type',2) as $item)
                                    <tr>
                                        <td width="15%" style="padding-left: .25rem !important" class="align-middle"><b><i>Specialized</i></b></td>
                                        <td width="47%" style="padding-left: .25rem !important; font-size:{{strlen($item->subjdesc) > 30 ? '9px !important' : '10px !important'}}">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                        @if($x == 1)
                                            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                        @elseif($x == 2)
                                            <td class="text-center align-middle">
                                                {{$item->quarter3 != null ? $item->quarter3:''}}
                                            </td>
                                            <td class="text-center align-middle">
                                                {{$item->quarter4 != null ? $item->quarter4:''}}
                                            </td>
                                        @endif
                                        <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    @php
                                        $genave = collect($finalgrade)->where('semid',$x)->first();
                                    @endphp
                                    <td colspan="2" class="text-right"><b>GENERAL AVERAGE</b></td>
                                    <td colspan="2"></td>
                                    <td class="text-center align-middle">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                                </tr>
                            </table>
                        @endfor
                        <br/>
                        <table style="width: 100%;table-layout: fixed;text-align: center;">
                            <tr style="font-size: 13px;">
                                <th style="border-bottom: 1px solid black; font-size:10px !important;">SR. EDITHA D. DISMAS, TDM</th>
                                <th></th>
                                <th style="border-bottom: 1px solid black; font-size:10px !important;">{{$student[0]->teacherfirstname}} {{$student[0]->teacherlastname}}</th>
                            </tr>
                            <tr style="font-size: 12px;">
                                <td>Directress/Principal</td>
                                <td></td>
                                <td>Adviser</td>
                            </tr>
                        </table>
                  </td>   
                  <td width="50%" style="padding-left: 20px;padding-right: 20px;" valign="top">
                    @if(count($coreValues) != 0)
                        <table class="table table-sm mb-0" width="100%">
                            <thead>
                                <tr>
                                    <th colspan="6" class="cellRight"><center>REPORT ON LEARNER'S OBSERVED VALUES</center></th>
                                </tr>
                                <tr>
                                    <th rowspan="2" style="width:15%"><center>Core Values</center></th>
                                    <th rowspan="2"><center>Behavior Statements</center></th>
                                    <th colspan="4" class="cellRight"><center>Quarter</center></th>
                                </tr>
                                <tr>
                                    <th><center>1</center></th>
                                    <th><center>2</center></th>
                                    <th><center>3</center></th>
                                    <th class="cellRight"><center>4</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th rowspan="2">Maka-Diyos</th>
                                    <td id="behavior">Expresses one's spiritual beliefs while respecting the beliefs of others</td>
                                    <td>&nbsp;
                                    </td>
                                    <td>&nbsp;
                                    </td>
                                    <td>&nbsp;
                                    </td>
                                    <td class="cellRight">&nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td id="behavior">Shows adherence to ethical principles by upholding the truth in all undertakings</td>
                                    <td style="width: 5%;">&nbsp;
                                    </td>
                                    <td style="width: 5%;">&nbsp;
                                    </td>
                                    <td style="width: 5%;">&nbsp;
                                    </td style="width: 5%;">
                                    <td class="cellRight" style="width: 5%;">&nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <th>Makatao</th>
                                    <td id="behavior">Is sensitive to individual, social and cultural differences; resists stereotyping people</td>
                                    <td>&nbsp;
                                    </td>
                                    <td>&nbsp;
                                    </td>
                                    <td>&nbsp;
                                    </td>
                                    <td class="cellRight">&nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <th rowspan="2">Makakalikasan</th>
                                    <td id="behavior">Demonstrates contributions towards solidarity</td>
                                    <td>&nbsp;
                                    </td>
                                    <td>&nbsp;
                                    </td>
                                    <td>&nbsp;
                                    </td>
                                    <td class="cellRight">&nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td>Cares for the environment and utilizes resources wisely, judiciously and economically</td>
                                    <td>&nbsp;
                                    </td>
                                    <td>&nbsp;
                                    </td>
                                    <td>&nbsp;
                                    </td>
                                    <td class="cellRight">&nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <th class="cellBottom" rowspan="2">Makabansa</th>
                                    <td id="behavior">Demonstrates pride in being a Filipino, exercises the rights and responsibilities of a Filipino Citizen</td>
                                    <td>&nbsp;
                                    </td>
                                    <td>&nbsp;
                                    </td>
                                    <td>&nbsp;
                                    </td>
                                    <td class="cellRight">&nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td id="behavior" class="cellBottom">Demonstrates appropriate behavior in carrying out activities in the school, community and country</td>
                                    <td class="cellBottom">&nbsp;
                                    </td>
                                    <td class="cellBottom">&nbsp;
                                    </td>
                                    <td class="cellBottom">&nbsp;
                                    </td>
                                    <td class="cellBottom cellRight">&nbsp;
                                    </td>
                                </tr>
                            </tbody>
                        </table> 
                        <table class="table table-sm mb-0" width="100%">
                            <tr>
                                <td colspan="4" style="background-color:#a3d762 " style="border-top: hidden;" class="cellRight"><center>Observed Values</center></td>
                            </tr>
                            <tr>
                                <td class="cellBottom"><center>AO - Always Observed</center></td>
                                <td class="cellBottom"><center>SO - Sometimes Observed</center></td>
                                <td class="cellBottom"><center>RO - Rarely Observed</center></td>
                                <td class="cellBottom cellRight"><center>NO - Not Observed</center></td>
                            </tr>
                        </table>
                    @elseif(count($rv) != 0 && count($checkGrades) != 0)
                        <table class="table table-sm mb-0" width="100%">
                            <thead>
                                <tr>
                                    <th colspan="6" class="cellRight" style="border: 1px solid black; font-size: 10px;"><center>REPORT ON LEARNER'S OBSERVED VALUES</center></th>
                                </tr>
                                <tr>
                                    <th rowspan="2" width="15%" class="align-middle" style="border: 1px solid black; font-size: 10px;"><center >Core Values</center></th>
                                    <th rowspan="2" width="55%" class="align-middle" style="border: 1px solid black; font-size: 10px;"><center>Behavior Statements</center></th>
                                    <th colspan="4" class="cellRight" width="30%" style="border: 1px solid black; font-size: 10px;"><center>Quarter</center></th>
                                </tr>
                                <tr>
                                    <th style="border: 1px solid black; font-size: 10px;"><center>1</center></th>
                                    <th style="border: 1px solid black; font-size: 10px;"><center>2</center></th>
                                    <th style="border: 1px solid black; font-size: 10px;"><center>3</center></th>
                                    <th style="border: 1px solid black; font-size: 10px;" class="cellRight"><center>4</center></th>
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
                                                    <th colspan="6" style="border: 1px solid black; font-size: 10px;" style="border: 1px solid black; font-size: 10px;">{{$item->description}}</th>
                                                </tr>
                                        @else
                                                <tr>
                                                    @if($count == 0)
                                                            <td class="text-center align-middle" rowspan="{{count($groupitem)}}" style="border: 1px solid black; font-size: 10px;">{{$item->group}}</td>
                                                            @php
                                                                $count = 1;
                                                            @endphp
                                                    @endif
                                                    <td class="align-middle" style="border: 1px solid black; font-size: 10px;">{{$item->description}}</td>
                                                    <td class="text-center align-middle" style="border: 1px solid black; font-size: 10px;">
                                                        @foreach ($rv as $key=>$rvitem)
                                                            @if($item->q1eval == $rvitem->id)
                                                                {{$rvitem->value}}
                                                            @endif
                                                        @endforeach 
                                                    </td>
                                                    <td class="text-center align-middle" style="border: 1px solid black; font-size: 10px;">
                                                        @foreach ($rv as $key=>$rvitem)
                                                            @if($item->q2eval == $rvitem->id)
                                                                {{$rvitem->value}}
                                                            @endif
                                                        @endforeach 
                                                    </td>
                                                    <td class="text-center align-middle" style="border: 1px solid black; font-size: 10px;">
                                                        @foreach ($rv as $key=>$rvitem)
                                                            @if($item->q3eval == $rvitem->id)
                                                                {{$rvitem->value}}
                                                            @endif
                                                        @endforeach 
                                                    </td>
                                                    <td class="text-center align-middle" style="border: 1px solid black; font-size: 10px;">
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
                    @endif
                 
                    <table class="table table-sm mb-0" width="100%">
                        <tr style="font-size: 13px;">
                            <th style="text-align: center; ">
                                Observed Values
                            </th>
                            <th style="text-align: left;">
                                Non-numerical Rating
                            </th>
                        </tr>
                        <tr style="font-size: 13px;">
                            <th style="text-align: center;">
                                Markings
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                        <tr style="font-size: 12px;">
                            <th>AO</th>
                            <th style="text-align: left !important;">&nbsp;&nbsp;Always Observed</th>
                        </tr>
                        <tr style="font-size: 12px;">
                            <th>SO</th>
                            <th style="text-align: left !important;">&nbsp;&nbsp;Sometimes Observed</th>
                        </tr>
                        <tr style="font-size: 12px;">
                            <th>RO</th>
                            <th style="text-align: left !important;">&nbsp;&nbsp;Rarely Observed</th>
                        </tr>
                        <tr style="font-size: 12px;">
                            <th>NO</th>
                            <th style="text-align: left !important;">&nbsp;&nbsp;Not Observed</th>
                        </tr>
                    </table>
                    <table class="table table-sm mb-0">
                        <tr>
                            <td><center><strong>Report on Attendance</strong></center></td>
                        </tr>
                    </table >
                    @php
                        $width = count($attendance_setup) != 0? 75 / count($attendance_setup) : 0;
                    @endphp
                    <table class="table table-sm" width="100%" style="font-size:11px; border:solid 1px black">
                       <tr class="table-bordered">
                                    <th width="15%"></th>
                                    @foreach ($attendance_setup as $item)
                                        <th class="text-center align-middle" width="{{$width}}%" style="font-size:10px !important">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
                                    @endforeach
                                    <th class="text-center text-center" width="10%">Total</th>
                                </tr>
                                <tr class="table-bordered">
                                    <td style="font-size:9px !important">Days of School</td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle">{{$item->days}}</td>
                                    @endforeach
                                    <th class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                                </tr>
                                <tr class="table-bordered">
                                      <td style="font-size:9px !important">Days of Present</td>
                                        @php
                                            $totalpresent = 0;
                                        @endphp
                                        @foreach ($attendance_setup as $item)
                                            @php
                                                $totalpresent += $item->present;
                                            @endphp
                                            <td class="text-center align-middle">{{$item->present}}</td>
                                        @endforeach
                                        <th class="text-center align-middle" >{{$totalpresent}}</th>
                                </tr>
                                <tr class="table-bordered">
                                    <td style="font-size:9px !important">Times Tardy</td>
                                    @php
                                        $totalabsent = 0;
                                    @endphp
                                    @foreach ($attendance_setup as $item)
                                        @php
                                            $totalabsent += $item->absent;
                                        @endphp
                                        <td class="text-center align-middle" >{{$item->absent}}</td>
                                    @endforeach
                                    <th class="text-center align-middle" >{{$totalabsent}}</td>
                                </tr>
                    </table>
                    <div class="width: 100%;top: 0; margin-bottom: 0px;">
                        <p style=" margin-bottom: 10px;font-size: 11px; text-align: center;">
                           
                        </p>
                    </div>
                    <table class="table table-sm mb-0" width="100%">
                        <tr>
                            <td class="text-center">
                                 <strong>PARENTS/GUARDIANS SIGNATURE</strong>
                            </td>
                        </tr>
                    </table>
                    <table class="table table-sm mb-0" width="100%">
                        <tr>
                            <td style="width: 15%; "></td>
                            <th style="width: 25%; text-align: left;">
                                First Semester
                            </th>
                            <td style="width: 15%;">1st Quarter</td>
                            <td style="border-bottom: 1px solid black;"></td>
                            <td style="width: 15%;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="width: 15%;">2nd Quarter</td>
                            <td style="border-bottom: 1px solid black;"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width: 15%; "></td>
                            <th style="width: 25%; text-align: left;">
                                Second Semester
                            </th>
                            <td style="width: 15%;">1st Quarter</td>
                            <td style="border-bottom: 1px solid black;"></td>
                            <td style="width: 15%;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="width: 15%;">2nd Quarter</td>
                            <td style="border-bottom: 1px solid black;"></td>
                            <td></td>
                        </tr>
                    </table>
                    <table class="table table-sm mb-0" width="100%">
                        <tr>
                            <td class="text-center">
                                  <strong>Certificate of Transfer</strong>
                            </td>
                        </tr>
                    </table>
                    <div style="width: 100%; font-size:12px !important;padding-top: 5px;padding-left: 10%;padding-right: 10%;">
                        <span style="float:left; width: 25%;test-align: left; padding-left: 0px;">
                            Admitted to:
                        </span>
                        <span style="float:right; width: 75%; padding-left: 0px;border-bottom: 1px solid black;">
                            @if($studid == 1569)
                            
                            @else
                              @if(collect($finalgrade)->first()->actiontaken == 'PASSED')
                                    @if($student[0]->levelid == 14)
                                        GRADE 12
                                    @elseif($student[0]->levelid == 15)
                                        1ST YEAR COLLEGE
                                    @else
                                        &nbps;
                                    @endif
                                @elseif(collect($finalgrade)->first()->actiontaken == 'FAILED')
                                    &nbps;
                                @endif
                            @endif
                          
                        </span>
                    </div>
                    <br/>
                    <div style="width: 100%; font-size:12px !important;padding-top: 5px;padding-left: 10%;padding-right: 10%;">
                        <span style="float:left; width: 25%;test-align: left; padding-left: 0px;">
                            Advance unit in:
                        </span>
                        <span style="float:right; width: 75%; padding-left: 0px;border-bottom: 1px solid black;">
                            @if($studid == 1569)
                            
                            @else
                                @if(collect($finalgrade)->first()->actiontaken == 'PASSED')
                                    NONE
                                @elseif(collect($finalgrade)->first()->actiontaken == 'FAILED')
                                    
                                @endif
                            @endif
                        </span>
                    </div>
                    <br/>
                    <div style="width: 100%; font-size:12px !important;padding-top: 5px;padding-left: 10%;padding-right: 10%;">
                        <span style="float:left; width: 25%;test-align: left; padding-left: 0px;">
                            Lacks unit in:
                        </span>
                        <span style="float:right; width: 75%; padding-left: 0px;border-bottom: 1px solid black;">
                            @if($studid == 1569)
                            
                            @else
                                @if(collect($finalgrade)->first()->actiontaken == 'PASSED')
                                    NONE
                                @elseif(collect($finalgrade)->first()->actiontaken == 'FAILED')
                                    
                                @endif
                            @endif
                        </span>
                    </div>
                    <br/>
                    <div style="width: 100%; font-size:12px !important;padding-top: 5px;text-align: center;">
                        <u>SR. EDITHA D. DISMAS, TDM</u> 
                        <br>
                       <strong>Directress/Principal</strong>
                    </div>
                    <div style="width: 100%; font-size:12px !important;padding-top: 2px;">
                        <span style="float:left; width: 45%;text-align: center; padding-left: 0px;">
                            <u>{{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM DD, YYYY')}}</u> 
                            <br>
                           <strong>Date</strong>
                        </span>
                        <span style="float:right; width: 45%; padding-right: 0px;;text-align: center;">
                            <u>{{$student[0]->teacherfirstname}} {{$student[0]->teacherlastname}}</u> 
                            <br>
                           <strong>Adviser</strong>
                        </span>
                    </div>
                    <br/>
                    <br/>
                    <div style="width: 100%; text-align:center; font-size:12px !important;">
                        <strong>Cancellation of Eligibility to Transfer</strong>
                    </div>
                    <table style="width: 100%; font-size:12px !important;padding-top: 5px;">
                        <tr>
                            <td style="width:10%">&nbsp;</td>
                            <td style="width:15%">Admitted in:</td>
                            <td style="width:25%; border-bottom: 1px solid black">&nbsp;</td>
                            <td style="width:10%;">&nbsp;</td>
                            <td rowspan="2" style="width: 40%; text-align:center; vertical-align: middle !important; padding-top: 5px;">
                                <div style="border-bottom: 1px solid black; width: 100%">
                                   
                                </div>
                            
                            </td>
                        </tr>
                        <tr>
                            <td style="width:10%">&nbsp;</td>
                            <td style="width:15%">Date:</td>
                            <td style="width:25%; border-bottom: 1px solid black">&nbsp;</td>
                            <td style="width:10%;">&nbsp;</td>
                        </tr>
                    </table>
                  </td>  
            </tr>
      </table>
</body>
</html>