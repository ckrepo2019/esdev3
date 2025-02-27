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
            
             .text-right {
                  text-align: right !important;
            }

            .table-bordered {
                  border: 1px solid black;
            }

            .table-bordered th,
            .table-bordered td {
                  border: 1px solid black;
            }

            .pl-4, .px-4 {
                padding-left: 1.5rem!important;
            }
            
            @page {
                margin: 20px !important;
            }
            
            body{
                font-family: Calibri, sans-serif;
            }
         
    </style>
    
   
</head>
<body>
      <table width="100%">
            <tr>
                  <td width="50%" style="padding-right: 20px;" valign="top">
                    <sup style="font-size: 10px;">School Form 9</sup>
                        <table width="100%" style="font-size:13px ; ">
                             <tr>
                                   <td width="15%" >
                                        <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="60px">
                                   </td>
                                   <td width="70%" style="text-align: center;">
                                        <sup style="font-size: 10px;">Republic of the Philippines</sup>
                                        <br/>
                                        <sup style="font-size: 10px;">Department of Education</sup>
                                        <br/>
                                        <sup style="font-size: 15px;font-weight: bold;">HOLY CROSS OF BUNAWAN, INC.</sup>
                                        <br/>
                                        <sup style="font-size: 10px;">Km. 23 Bunawan, Davao City</sup>
                                        <br/>
                                        <sup style="font-size: 10px;">Government Recognition # 183 S. 1968</sup>
                                   </td>
                                   <td width="15%" style="float:right">
                                        <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="60px">
                                   </td>
                             </tr>
                        </table>
                        <table width="100%" style="font-size:13px ; ">
                              <tr ><td style="border-bottom:solid 2px black !important"><center>REPORT CARD </center><td></td></tr>
                        </table>
                        <table width="100%" style="font-size:11px; margin-top:10px">
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
                        <table width="100%"  style="font-size:11px; margin-top:10px" >
                              <tr>
                                    <td width="20%">Grade: </td>
                                    <td width="30%" style="border-bottom:solid 1px  black">{{$student[0]->levelname}}</td>
                                    <td width="15%">Section: </td>
                                    <td width="30%" style="border-bottom:solid 1px  black">{{$student[0]->ensectname}}</td>
                              </tr>
                              <tr>
                                    <td >Curriculum:</td>
                                    <td style="border-bottom:solid 1px  black">K-12</td>
                                    <td >Sex: </td>
                                    <td style="border-bottom:solid 1px  black">{{$student[0]->gender}}<</td>
                              </tr>
                              <tr>
                                    <td >Birthdate: </td>
                                    <td  style="border-bottom:solid 1px  black">{{\Carbon\Carbon::create($student[0]->dob)->isoFormat('MMMM DD, YYYY')}}</td>
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
                        {{-- <table class="table table-sm" width="100%" style="font-size:11px; border:solid 1px black">
                              <tr class="table-bordered">
                                  <td width="40%" rowspan="2" style="border:solid 1px  black"><center>Learning Areas</center></td>
                                  <td width="40%" colspan="4"  style="border:solid 1px  black"><center>Quarter</center></td>
                                    <td width="10%" rowspan="2" style="border:solid 1px  black"><center>Final Grade</center></td>
                                    <td width="10%" rowspan="2" style="border:solid 1px  black"><center>Remarks</center></td>
                              </tr>
                              <tr class="table-bordered" style="font-size:11px !important;">
                                    <td style="border:solid 1px  black"><center>1</center></td>
                                    <td style="border:solid 1px  black"><center>2</center></td>
                                    <td style="border:solid 1px  black"><center>3</center></td>
                                    <td style="border:solid 1px  black"><center>4</center></td>
                              </tr>
                              
                              
                          @php
                              $quarter1complete = true;
                              $quarter2complete = true;
                              $quarter3complete = true;
                              $quarter4complete = true;
                          @endphp
                      
                          @if( count($grades) != 0)
                              @foreach ($grades as $item)

                                  @if($item->quarter1 == null)
                                      @php
                                          $quarter1complete = false;
                                      @endphp
                                  @endif

                                  @if($item->quarter2 == null)
                                      @php
                                          $quarter2complete = false;
                                      @endphp
                                  @endif

                                  @if($item->quarter3 == null)
                                      @php
                                          $quarter3complete = false;
                                      @endphp
                                  @endif

                                  @if($item->quarter4 == null)
                                      @php
                                          $quarter4complete = false;
                                      @endphp
                                  @endif

                                  @php
                                      $average = ($item->quarter1 + $item->quarter2 + $item->quarter3 + $item->quarter4) / 4 ;
                                  @endphp

                                  <tr class="table-bordered" style="font-size:10px">
                                      
                                      @if($item->subjectcode!=null)
                                          <td class="p-1 @if($item->mapeh == 1) pl-4 @endif" style="text-align: left !important" >
                                              {{$item->subjectcode}}
                                          </td>
                                      @else
                                          <td class="p-1" style="text-align: left !important;" >
                                              &nbsp;
                                          </td>
                                      @endif

                                      @if($item->quarter1 != null)
                                          <td class="text-center p-0 align-middle" >{{$item->quarter1}}</td>
                                      @else
                                          <td class="text-center p-0 align-middle" >&nbsp;</td>
                                      @endif

                                  

                                      @if($item->quarter2 != null)
                                          <td class="text-center p-0 align-middle" >{{$item->quarter2}}</td>
                                      @else
                                          <td class="text-center p-0 align-middle" >&nbsp;</td>
                                      @endif

                                      @if($item->quarter3 != null)
                                          <td class="text-center p-0 align-middle" >{{$item->quarter3}}</td>
                                      @else
                                          <td class="text-center p-0 align-middle"  >&nbsp;</td>
                                      @endif

                                      <td class="text-center p-0 align-middle" >{{$item->quarter4}}</td>
                                      
                                      @if($item->quarter1 != null && $item->quarter2 != null && $item->quarter3 != null && $item->quarter4 != null)
                                          <td class="text-center p-0 align-middle" >{{number_format( ($item->quarter1+$item->quarter2+$item->quarter3+$item->quarter4)/4)}}</td>
                                      @else
                                          <td class="text-center p-0 align-middle" ></td>
                                      @endif

                                      @if($item->quarter1 != null && $item->quarter2 != null && $item->quarter3 != null && $item->quarter4 != null)
                                          <td class="text-center p-0 align-middle" ><i>@if($average >= 75) Passed @else Failed  @endif</i></td>
                                      @else
                                          <td class="text-center p-0 align-middle" ></td>
                                      @endif
                                  </tr>
                              @endforeach
                          @else
                              @php
                                  $average = null;
                              @endphp
                          @endif
                          @if( count($grades) != 0)
                              @php
                                  $genaverage =  (collect($grades)->where('mapeh',0)->avg('quarter1') + collect($grades)->where('mapeh',0)->avg('quarter2') + collect($grades)->where('mapeh',0)->avg('quarter3') + collect($grades)->where('mapeh',0)->avg('quarter4')) / 4 ;
                              @endphp
                          @else
                              @php
                                  $genaverage = null;    
                              @endphp
                          @endif
                          <tr>
                              <td style="border:solid 1px  black"></td>
                              <td colspan="4" style="border:solid 1px  black; text-align: right;">
                                  General Average
                              </td>
                              <td style="border:solid 1px  black"></td>
                              <td style="border:solid 1px  black"></td>
                          </tr>
                          </table>--}}
                            <table class="table table-bordered table-sm grades" width="100%" style="font-size:11px; border:solid 1px black">
                                <thead>
                                    <tr>
                                        <td rowspan="2"  class="align-middle text-center" width="40%"><b>SUBJECTS</b></td>
                                        <td colspan="4"  class="text-center align-middle"><b>PERIODIC RATINGS</b></td>
                                        <td rowspan="2"  class="text-center align-middle"><b>Final Rating</b></td>
                                        <td rowspan="2"  class="text-center align-middle"><b>Action Taken</b></span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center align-middle" width="10%"><b>1</b></td>
                                        <td class="text-center align-middle" width="10%"><b>2</b></td>
                                        <td class="text-center align-middle" width="10%"><b>3</b></td>
                                        <td class="text-center align-middle" width="10%"><b>4</b></td>
                                    </tr>
                                </thead>
                                
                              
                                <tbody>
                                    @foreach ($studgrades as $item)
                                       
                                            <tr>
                                                <td style="padding-left: {{$item->inTLE == 1 || $item->inMAPEH == 1? '1.5rem':'.5rem'}} !important;">{{$item->subjdesc}}</td>
                                                <td class="text-center {{$item->quarter1 < 75 ? 'bg-red':''}}">{{$item->quarter1 >= 60 ? $item->quarter1 : ''}}</td>
                                                <td class="text-center {{$item->quarter2 < 75 ? 'bg-red':''}}">{{$item->quarter2 >= 60 ? $item->quarter2 : ''}}</td>
                                                <td class="text-center {{$item->quarter3 < 75 ? 'bg-red':''}}">{{$item->quarter3 >= 60 ? $item->quarter3 : ''}}</td>
                                                <td class="text-center {{$item->quarter4 < 75 ? 'bg-red':''}}">{{$item->quarter4 >= 60 ? $item->quarter4 : ''}}</td>
                                                <td class="text-center {{$item->finalrating < 75 ? 'bg-red':''}}"  width="10%">{{$item->finalrating >= 60 ? $item->finalrating : ''}}</td>
                                                <td class="text-center"  width="10%" style="font-size: 8px !important">{{$item->actiontaken}}</td>
                                            </tr>
                                       
                                    @endforeach
                                    <tr>
                                        <td class="text-right" colspan="5">GENERAL AVERAGE</td>
                                        <td class="text-center {{collect($finalgrade)->first()->finalrating < 75 ? 'bg-red':''}}">{{collect($finalgrade)->first()->finalrating}}</td>
                                        <td class="text-center" style="font-size: 8px !important">{{collect($finalgrade)->first()->actiontaken}}</td>
                                    </tr>
                                </tbody>
                            </table>  
                          <table width="100%"  style="font-size:12px !important; margin-top:5px;">
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
                            <div style="width: 100%; text-align:center; font-size:12px !important;padding-top: 5px;">
                                <strong>Certificate of Transfer</strong>
                            </div>
                            <div style="width: 100%; font-size:12px !important;padding-top: 5px;">
                                <span style="float:left; width: 20%;test-align: left; padding-left: 0px;">
                                    Admitted to: 
                                </span>
                                <span style="float:right; width: 80%; padding-left: 0px;border-bottom: 1px solid black;">
                                    @php
                                        $gradelevel = DB::table('gradelevel')->where('id',$student[0]->levelid)->first();
                                        $next_grade_level = DB::table('gradelevel')
                                                            ->where('sortid','>',$gradelevel->sortid)
                                                            ->where('deleted',0)
                                                            ->orderBy('sortid')
                                                            ->first();
                                    @endphp
                                    @if(collect($finalgrade)->first()->actiontaken == 'PASSED')
                                        @if(isset($next_grade_level->levelname))
                                            {{$next_grade_level->levelname}}
                                        @endif
                                    @elseif(collect($finalgrade)->first()->actiontaken == 'FAILED')
                                        {{$gradelevel->levelname}}
                                    @else
                                    @endif
                                </span>
                            </div>
                            <br/>
                            <div style="width: 100%; font-size:12px !important;padding-top: 5px;">
                                <span style="float:left; width: 25%;test-align: left; padding-left: 0px;">
                                    Advance unit in:
                                </span>
                                <span style="float:right; width: 75%; padding-left: 0px;border-bottom: 1px solid black;">
                                    @if(collect($finalgrade)->first()->actiontaken == 'PASSED')
                                        NONE
                                    @elseif(collect($finalgrade)->first()->actiontaken == 'FAILED')
                                        
                                    @endif
                                </span>
                            </div>
                            <br/>
                            <div style="width: 100%; font-size:12px !important;padding-top: 5px;">
                                <span style="float:left; width: 20%;test-align: left; padding-left: 0px;">
                                    Lacks unit in:
                                </span>
                                <span style="float:right; width: 80%; padding-left: 0px;border-bottom: 1px solid black;">
                                    @if(collect($finalgrade)->first()->actiontaken == 'PASSED')
                                        NONE
                                    @elseif(collect($finalgrade)->first()->actiontaken == 'FAILED')
                                        
                                    @endif
                                </span>
                            </div>
                            <br/>
                            <div style="width: 100%; font-size:12px !important;padding-top: 5px;text-align: center;">
                                <u>SR. EDITHA D. DISMAS, TDM</u> 
                                <br>
                               <strong>Directress/Principal</strong>
                            </div>
                            <div style="width: 100%; font-size:12px !important;padding-top: 5px;">
                                <span style="float:left; width: 45%;text-align: center; padding-left: 0px;">
                                    <u>{{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM DD, YYYY')}}</u> 
                                    <br>
                                   <strong>Date</strong>
                                </span>
                                <span style="float:right; width: 45%; padding-right: 0px;;text-align: center; font-size:10px !important;">
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
                                    <td width="20%">
                                        Admitted in:
                                    </td>
                                    <td width="30%" style="padding-right: 5px; border-bottom: 1px solid black; ">
                                    
                                    </td>
                                    <td width="50%" rowspan="2" style="padding-right: 5px; text-align:center;">
                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Date:
                                    </td>
                                    <td style="border-bottom: 1px solid black;">
                                        &nbsp;
                                    </td>
                                </tr>
                            </table>

                  </td>   
                  <td width="50%" style="padding-left: 20px;" valign="top">
                     @if(count($coreValues) != 0)
                    <table id="values" style="border-bottom: hidden !important;">
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
                    <table id="values" style="border-top: hidden;">
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
                    <table style="width:100% !important" class="report2">
                        <thead>
                            <tr>
                                <th colspan="6" class="cellRight" style="border: 1px solid black; font-size: 10px;"><center>REPORT ON LEARNER'S OBSERVED VALUES</center></th>
                            </tr>
                            <tr>
                                <th rowspan="2" width="15%" class="align-middle" style="border: 1px solid black; font-size: 10px;"><center >Core Values</center></th>
                                <th rowspan="2" width="45%" class="align-middle" style="border: 1px solid black; font-size: 10px;"><center>Behavior Statements</center></th>
                                <th colspan="4" class="cellRight" width="40%" style="border: 1px solid black; font-size: 10px;"><center>Quarter</center></th>
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
                                                <td class="text-center lign-middle" style="border: 1px solid black; font-size: 10px;">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        @if($item->q1eval == $rvitem->id)
                                                            {{$rvitem->value}}
                                                        @endif
                                                    @endforeach 
                                                </td>
                                                <td class="text-center lign-middle" style="border: 1px solid black; font-size: 10px;">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        @if($item->q2eval == $rvitem->id)
                                                            {{$rvitem->value}}
                                                        @endif
                                                    @endforeach 
                                                </td>
                                                {{-- @if($acad != 5) --}}
                                                <td class="text-center lign-middle" style="border: 1px solid black; font-size: 10px;">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        @if($item->q3eval == $rvitem->id)
                                                            {{$rvitem->value}}
                                                        @endif
                                                    @endforeach 
                                                </td>
                                                <td class="text-center lign-middle" style="border: 1px solid black; font-size: 10px;">
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
                        </tbody>
                    </table>
                @endif
                    <br/>
                    <table style="width: 100%; table-layout: fixed;">
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
                    <div class="width: 100%;top: 0; margin-bottom: 0px;">
                        <h5 style=" margin-bottom: 10px;">
                            <strong>PARENTS/GUARDIANS SIGNATURE</strong>
                        </h5>
                    </div>
                    <table style="width: 100%; font-size: 12px;">
                        <tr>
                            <th style="width: 20%; text-align: left;">
                            1st Quarter</th>
                            <td style="border-bottom: 1px solid black;"></td>
                        </tr>
                        <tr>
                            <th style="width: 20%; text-align: left;">
                            2nd Quarter</th>
                            <td style="border-bottom: 1px solid black;"></td>
                        </tr>
                        <tr>
                            <th style="width: 20%; text-align: left;">
                            3rd Quarter</th>
                            <td style="border-bottom: 1px solid black;"></td>
                        </tr>
                        <tr>
                            <th style="width: 20%; text-align: left;">
                            4th Quarter</th>
                            <td style="border-bottom: 1px solid black;"></td>
                        </tr>
                    </table>
                    <div class="width: 100%;top: 0; margin-bottom: 0px;">
                        <h5 style=" margin-bottom: 10px; text-align: center !important;">
                            <strong>NOTICE</strong>
                        </h5>
                    </div>
                    <div class="width: 100%;top: 0; margin-bottom: 0px;">
                        <div style="font-weight: bold; font-size: 12px; text-align: justify;">
                            This report card is issued to the students at the end of each grading period.
                            <br/>
                            <br/>
                            This report must be free from alterations or erasures. If there is any, please verify it at the Principal's office.
                            <br/>
                            <br/>
                            Parents or guardians should examine the report carefully, sign and return to the adviser through the student.
                            <br/>
                            <br/>
                            Parents or guardians are requested to visit the school and confer with the Teachers or Principal over any problems.
                        </div>
                    </div>
                  </td>  
            </tr>
      </table>
</body>
</html>