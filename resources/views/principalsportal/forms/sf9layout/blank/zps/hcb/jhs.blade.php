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

            .pl-4, .px-4 {
                padding-left: 1.5rem!important;
            }
            @page {
                margin: 20px !important;
            }
    </style>
    
   
</head>
<body>
      <table width="100%">
            <tr>
                  <td width="50%" style="padding-right: 20px;" valign="top">
                    <sup style="font-size: 10px;">DepEd Form 138-A</sup>
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
                                    <td width="80%" class="text-center" style="border-bottom:solid 1px  black">{{$student[0]->firstname}} {{$student[0]->lastname}}</td>
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
                                    <td >Curriculum: </td>
                                    <td style="border-bottom:solid 1px  black"></td>
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
                                    <td  style="border-bottom:solid 1px  black">{{Session::get('schoolYear')->sydesc}}</td>
                                    <td >LRN NO: </td>
                                    <td  style="border-bottom:solid 1px  black">{{$student[0]->lrn}}</td>
                              </tr>

                        </table>
                        <hr>
                        <table class="table table-sm" width="100%" style="font-size:11px; border:solid 1px black">
                              <tr class="table-bordered">
                                  <td width="30%" rowspan="2" style="border:solid 1px  black"><center>Learning Areas</center></td>
                                  <td width=50%" colspan="4"  style="border:solid 1px  black"><center>Quarter</center></td>
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
                            <tr class="table-bordered genave">
                                    
                                <th class="p-1" style="text-align: left !important">
                                </th>

                                @if($quarter1complete)
                                    <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important;">{{round(collect($grades)->where('mapeh',0)->avg('quarter1'))}}</td>
                                @else   
                                    <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important;">&nbsp;</td>
                                @endif

                                @if($quarter2complete)
                                    <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important;">{{round(collect($grades)->where('mapeh',0)->avg('quarter2'))}}</td>
                                @else
                                    <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important;">&nbsp;</td>
                                @endif
                            
                                @if($quarter3complete)
                                    <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important;">{{round(collect($grades)->where('mapeh',0)->avg('quarter3'))}}</td>
                                @else
                                    <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important;">&nbsp;</td>
                                @endif

                                @if($quarter4complete)
                                    <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important;">{{round(collect($grades)->where('mapeh',0)->avg('quarter4'))}}</td>
                                @else
                                    <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important;">&nbsp;</td>
                                @endif

                                @if($item->quarter1 != null && $item->quarter2 != null && $item->quarter3 != null && $item->quarter4 != null)
                                    <td class="text-center p-0 align-middle"  style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important">{{number_format($average)}}</td>
                                @else
                                    <td class="text-center p-0 align-middle"  style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important"></td>
                                @endif

                                @if($item->quarter1 != null && $item->quarter2 != null && $item->quarter3 != null && $item->quarter4 != null)
                                    <td class="text-center p-0 align-middle"  style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"><i>@if($genaverage >= 75) Passed @elseif($genaverage == null) @else Failed  @endif</i></td>
                                @else
                                    <td class="text-center p-0 align-middle"  style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"></td>
                                @endif
                            </tr>
                        
                          <tr>
                              <td style="border:solid 1px  black"></td>
                              <td colspan="4" style="border:solid 1px  black; text-align: right;">
                                  General Average
                              </td>
                              <td style="border:solid 1px  black"></td>
                              <td style="border:solid 1px  black"></td>
                          </tr>
                          </table>
                          <table width="100%"  style="font-size:12px !important; margin-top:5px;">
                              <tr>
                                  <td><center><strong>Report on Attendance</strong></center></td>
                              </tr>
                          </table >
                          <table width="100%" style="border:solid 1px black; font-size:11px !important;" >
                              <tr class="table-bordered">
                                  <td></td>
                                  @foreach ($attSum as $item)
                                      <td class="text-center text-center" style="font-size:10px !important">{{\Carbon\Carbon::create($item->month)->isoFormat('MMM')}}</td>
                                  @endforeach
                                  <td class="text-center text-center" style="font-size:10px !important">Total</td>
                              </tr>
                              <tr class="table-bordered" >
                                  <td style="font-size:9px !important">Days of School</td>
                                  @foreach ($attSum as $item)
                                      <td class="align-middle text-center">{{$item->count}}</td>
                                  @endforeach
                                  <td class="align-middle text-center">{{collect($attSum)->sum('count')}}</td>
                              </tr>
                              <tr class="table-bordered">
                                  <td style="font-size:9px !important">Days of Present</td>
                                  @foreach ($attSum as $item)
                                      <td class="align-middle text-center">{{$item->countPresent}}</td>
                                  @endforeach
                                  <td class="align-middle text-center">{{collect($attSum)->sum('countPresent')}}</td>
                              </tr>
                              <tr class="table-bordered">
                                  <td style="font-size:9px !important">Times Tardy</td>
                                  @foreach ($attSum as $item)
                                      <td class="align-middle text-center">{{$item->countAbsent}}</td>
                                  @endforeach
                                  <td class="align-middle text-center">{{collect($attSum)->sum('countAbsent')}}</td>
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
                                    &nbsp;
                                </span>
                            </div>
                            <br/>
                            <div style="width: 100%; font-size:12px !important;padding-top: 5px;">
                                <span style="float:left; width: 25%;test-align: left; padding-left: 0px;">
                                    Advance unit in:
                                </span>
                                <span style="float:right; width: 75%; padding-left: 0px;border-bottom: 1px solid black;">
                                    &nbsp;
                                </span>
                            </div>
                            <br/>
                            <div style="width: 100%; font-size:12px !important;padding-top: 5px;">
                                <span style="float:left; width: 20%;test-align: left; padding-left: 0px;">
                                    Lacks unit in:
                                </span>
                                <span style="float:right; width: 80%; padding-left: 0px;border-bottom: 1px solid black;">
                                    &nbsp;
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
                                        <span style=" font-size:10px !important;">
                                            <u>{{Session::get('prinInfo')->lastname .' ,'.Session::get('prinInfo')->firstname}}</u>
                                        </span>
                                        <br/>
                                        Principal
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
                    <div class="width: 100%;top: 0; margin-bottom: 0px;">
                        <h5 style=" margin-bottom: 10px;">
                            <strong>REPORT ON LEARNER'S OBSERVED VALUES</strong>
                        </h5>
                    </div>
                    <table style="width: 100%;font-size: 11px; margin-top: 0px;">
                        <tr style="text-align: center;">
                            <th rowspan="2" style="border: 1px solid black; width: 20%;">
                                Core Values
                            </th>
                            <th rowspan="2" style="border: 1px solid black; width: 45%;">
                                Behavior Statement
                            </th>
                            <th colspan="4" style="border: 1px solid black;">
                                Quarter
                            </th>
                        </tr>
                        <tr>
                            <th style="border: 1px solid black;">1</th>
                            <th style="border: 1px solid black;">2</th>
                            <th style="border: 1px solid black;">3</th>
                            <th style="border: 1px solid black;">4</th>
                        </tr>
                        <tr>
                            <td rowspan="2" style="border: 1px solid black;">
                                1. Maka-Diyos
                            </td>
                            <td style="font-size: 10px;border: 1px solid black;">
                                Expresses one's spiritual beliefs while respecting the spiritual beliefs of others
                            </td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 10px;border: 1px solid black;">
                               Shows adherence to ethical principles by upholding truth
                            </td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                        </tr>
                        <tr>
                            <td rowspan="2" style="border: 1px solid black;">
                                2. Makatao
                            </td>
                            <td style="font-size: 10px;border: 1px solid black;">
                                Is sensitive to individual, social, and cultural differences
                            </td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 10px;border: 1px solid black;">
                               Demonstrates contributions toward solidarity
                            </td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black;">
                                3. Maka Kalikasan
                            </td>
                            <td style="font-size: 10px;border: 1px solid black;">
                                Cares for the environment and utilizes resources wisely, judiciously, and economically
                            </td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                        </tr>
                        <tr>
                            <td rowspan="2" style="border: 1px solid black;">
                                4. Makabansa
                            </td>
                            <td style="font-size: 10px;border: 1px solid black;">
                                Demonstrates pride in being a Filipino; exercises the rights and responsibilities of a Filipino citizen
                            </td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 10px;border: 1px solid black;">
                               Demonstrates appropriate behavior in carrying out activities in the school, community, and country
                            </td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                        </tr>
                    </table>
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