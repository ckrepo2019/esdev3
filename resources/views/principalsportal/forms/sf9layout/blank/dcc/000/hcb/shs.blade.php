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
    
   
</head>
<body>
      <table width="100%">
            <tr>
                  <td width="50%" style="padding-right: 20px;padding-left: 20px;" valign="top">
                    <sup style="font-size: 10px;">Form 138-A</sup>
                        <table width="100%" style="font-size:13px ;">
                             <tr>
                                   <td width="15%" >
                                        <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="60px">
                                   </td>
                                   <td width="70%" style="text-align: center;">
                                        <sup style="font-size: 16px;font-weight: bold;">HOLY CROSS OF BUNAWAN, INC.</sup>
                                        <br/>
                                        <sup style="font-size: 11px;">Km. 23 Bunawan, Davao City</sup>
                                        <br/>
                                        <sup style="font-size: 11px;">Government Recognition # 183 S. 1968</sup>
                                   </td>
                                   <td width="15%" style="float:right">
                                        <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="60px">
                                   </td>
                             </tr>
                        </table>
                        <table width="100%" style="font-size:16px ; ">
                              <tr ><td style="border-bottom:solid 2px black !important; font-weight: bold;"><center>SENIOR HIGH SCHOOL REPORT CARD</center><td></td></tr>
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
                        <sup style="font-size: 10px;font-weight: bold;">
                            <center>REPORT ON LEARNER'S PROGRESS AND ACHIEVEMENT</center>
                        </sup>
                        <p style="font-size: 10px;margin-bottom:0px;">First Semester</p>
                        <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="border: 1px solid black;width: 15%;"></th>
                                    <th rowspan="2" valign="middle" style="border: 1px solid black;width: 50%;">
                                        SUBJECTS
                                    </th>   
                                    <th colspan="2" style="border: 1px solid black;">
                                        QUARTER
                                    </th>
                                    <th rowspan="2" style="border: 1px solid black;width: 20%;">
                                        SEMESTER<br/>FINAL GRADE
                                    </th>
                                </tr>
                                <tr>
                                    <th style="border: 1px solid black;">1</th>
                                    <th style="border: 1px solid black;">2</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @if( count($grades) != 0 && collect($grades)->where('semid',1)->count() > 0)

                                        @foreach (collect($grades)->where('semid',1) as $item)

                                            @php
                                                $average = ($item->quarter1 + $item->quarter2 ) / 2 ;
                                            @endphp

                                            <tr class="table-bordered">
                                                <td style="padding-left: 5px"><b><i>
                                                            @if($item->type == 1)
                                                                Core
                                                            @elseif($item->type == 2)
                                                                Specialized
                                                            @elseif($item->type == 3)
                                                                Applied
                                                            @endif
                                                        </i>
                                                    </b>
                                                </td>
                                                @if($item->subjectcode!=null)
                                                    <td class="p-1" style="text-align: left !important; padding-left: 5px; font-size:9px" >
                                                        {{$item->subjectcode}}
                                                    </td>
                                                @else
                                                    <td class="p-1" style="text-align: left !important;" >
                                                        &nbsp;
                                                    </td>
                                                @endif

                                                @if($item->quarter1 != null )
                                                    <td class="text-center p-0 align-middle" style="font-size:10px !important">{{$item->quarter1}}</td>
                                                @else
                                                    <td class="text-center p-0 align-middle" style="font-size:10px !important">&nbsp;</td>
                                                @endif

                                                @if($item->quarter2 != null)
                                                    <td class="text-center p-0 align-middle" style="font-size:10px !important">{{$item->quarter2}}</td>
                                                @else
                                                    <td class="text-center p-0 align-middle" style="font-size:10px !important">&nbsp;</td>
                                                @endif

                                                @if($item->quarter1 != null && $item->quarter2 != null)
                                                    <td class="text-center p-0 align-middle" style="font-size:10px !important; font-family: Arial, Helvetica, sans-serif;"><i>@if($average >= 75) Passed @else Failed  @endif</i></td>
                                                @else
                                                    <td class="text-center p-0 align-middle" style="font-size:10px !important; font-family: Arial, Helvetica, sans-serif;"></td>
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
                                            $genaverage = (collect($grades)->avg('quarter1') + collect($grades)->avg('quarter2') ) / 2 ;
                                        @endphp
                                    @else
                                        @php
                                            $genaverage = null;    
                                        @endphp
                                    @endif
                                    
                                    <tr class="table-bordered genave">
                                        <th class="p-1" style="text-align: left !important"></th>
                                        <th></th>
                                        @if(collect($grades)->where('semid',1)->where('quarter1',null)->count() == 0)
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important;"></td>
                                        @else
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important;">&nbsp;</td>
                                        @endif

                                        @if(collect($grades)->where('semid',1)->where('quarter2',null)->count() == 0)
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important;"></td>
                                        @else
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important;">&nbsp;</td>
                                        @endif
                                    
                                        
                                        <th style="border: 1px solid black;">
                                          
                                        </th>

                                    </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" style="text-align: right;">General Average for the Semester&nbsp;&nbsp;</th>
                                    <th style="border: 1px solid black;">
                                        @if( collect($grades)->where('semid',1)->where('quarter1',null)->count() == 0 && collect($grades)->where('semid',1)->where('quarter2',null)->count() == 0 )
                                           {{number_format($genaverage, 2)}}
                                        @else
                                            &nbsp;
                                        @endif
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                        <p style="font-size: 10px;margin-bottom:0px;">Second Semester</p>
                        <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="border: 1px solid black;width: 15%;"></th>
                                    <th rowspan="2" valign="middle" style="border: 1px solid black;width: 50%;">
                                        SUBJECTS
                                    </th>   
                                    <th colspan="2" style="border: 1px solid black;">
                                        QUARTER
                                    </th>
                                    <th rowspan="2" style="border: 1px solid black;width: 20%;">
                                        SEMESTER<br/>FINAL GRADE
                                    </th>
                                </tr>
                                <tr>
                                    <th style="border: 1px solid black;">3</th>
                                    <th style="border: 1px solid black;">4</th>
                                </tr>
                                    @if( count($grades) != 0 && collect($grades)->where('semid',1)->count() > 0)

                                        @foreach (collect($grades)->where('semid',2) as $item)

                                            @php
                                                $average = ($item->quarter1 + $item->quarter2 ) / 2 ;
                                            @endphp

                                            <tr class="table-bordered">
                                                <td></td>
                                                @if($item->subjectcode!=null)
                                                    <td class="p-1" style="text-align: left !important" >
                                                        {{$item->subjectcode}}
                                                    </td>
                                                @else
                                                    <td class="p-1" style="text-align: left !important;" >
                                                        &nbsp;
                                                    </td>
                                                @endif

                                                @if($item->quarter1 != null)
                                                    <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->quarter1}}</td>
                                                @else
                                                    <td class="text-center p-0 align-middle" style="font-size:13px !important">&nbsp;</td>
                                                @endif

                                                @if($item->quarter2 != null)
                                                    <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->quarter2}}</td>
                                                @else
                                                    <td class="text-center p-0 align-middle" style="font-size:13px !important">&nbsp;</td>
                                                @endif

                                                @if($item->quarter1 != null && $item->quarter2 != null )
                                                    <td class="text-center p-0 align-middle" style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"><i>@if($average >= 75) Passed @else Failed  @endif</i></td>
                                                @else
                                                    <td class="text-center p-0 align-middle" style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"></td>
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
                                            $genaverage = (collect($grades)->avg('quarter3') + collect($grades)->avg('quarter4') ) / 2 ;
                                        @endphp
                                    @else
                                        @php
                                            $genaverage = null;    
                                        @endphp
                                    @endif
{{--                                     
                                    <tr class="table-bordered genave">
                                        <th class="p-1" style="text-align: left !important"></th>
                                        <th></th>
                                        @if(collect($grades)->where('semid',1)->where('quarter1',null)->count() == 0)
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important;">{{round(collect($grades)->where('semid',1)->avg('quarter1'))}}</td>
                                        @else
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important;">&nbsp;</td>
                                        @endif

                                        @if(collect($grades)->where('semid',1)->where('quarter2',null)->count() == 0)
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important;">{{round(collect($grades)->where('semid',1)->avg('quarter2'))}}</td>
                                        @else
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important;">&nbsp;</td>
                                        @endif
                                    
                                        
                                        @if( collect($grades)->where('semid',1)->where('quarter1',null)->count() == 0 && collect($grades)->where('semid',1)->where('quarter2',null)->count() == 0 )

                                            <td class="text-center p-0 align-middle"  style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important">{{number_format(collect($grades)->where('semid',1)->avg('finalRating'))}}</td>
                                        @else
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:10px !important;">&nbsp;</td>
                                        @endif

                                        @if(collect($grades)->where('semid',1)->where('finalRating',null)->count() == 0)
                                            <td class="text-center p-0 align-middle"  style="font-size:10px !important; font-family: Arial, Helvetica, sans-serif;"><i>@if($genaverage >= 75) Passed @elseif($genaverage == null) @else Failed  @endif</i></td>
                                        @else
                                            <td class="text-center p-0 align-middle"  style="font-size:10px !important; font-family: Arial, Helvetica, sans-serif;"></td>
                                        @endif
                                    </tr> --}}
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="4" style="text-align: right;">General Average for the Semester&nbsp;&nbsp;</th>
                                    <th style="border: 1px solid black;">
                                        @if( collect($grades)->where('semid',2)->where('quarter3',null)->count() == 0 && collect($grades)->where('semid',2)->where('quarter4',null)->count() == 0 )
                                           {{number_format($genaverage)}}
                                        @else
                                            &nbsp;
                                        @endif
                                    </th>
                                </tr>
                            </tfoot>
                            
                        </table>
                        <br/>
                        <br/>
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
                    <div class="width: 100%;top: 0; margin-bottom: 0px;">
                        <p style=" margin-bottom: 10px;font-size: 11px; text-align: center;">
                            <strong>PARENTS/GUARDIANS SIGNATURE</strong>
                        </p>
                    </div>
                    <table style="width: 100%; font-size: 12px;">
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
                    <div style="width: 100%; text-align:center; font-size:12px !important;padding-top: 5px;">
                        <strong>Certificate of Transfer</strong>
                    </div>
                    <div style="width: 100%; font-size:12px !important;padding-top: 5px;padding-left: 10%;padding-right: 10%;">
                        <span style="float:left; width: 25%;test-align: left; padding-left: 0px;">
                            Admitted to:
                        </span>
                        <span style="float:right; width: 75%; padding-left: 0px;border-bottom: 1px solid black;">
                            &nbsp;
                        </span>
                    </div>
                    <br/>
                    <div style="width: 100%; font-size:12px !important;padding-top: 5px;padding-left: 10%;padding-right: 10%;">
                        <span style="float:left; width: 25%;test-align: left; padding-left: 0px;">
                            Advance unit in:
                        </span>
                        <span style="float:right; width: 75%; padding-left: 0px;border-bottom: 1px solid black;">
                            &nbsp;
                        </span>
                    </div>
                    <br/>
                    <div style="width: 100%; font-size:12px !important;padding-top: 5px;padding-left: 10%;padding-right: 10%;">
                        <span style="float:left; width: 25%;test-align: left; padding-left: 0px;">
                            Lacks unit in:
                        </span>
                        <span style="float:right; width: 75%; padding-left: 0px;border-bottom: 1px solid black;">
                            &nbsp;
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
                                Principal
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