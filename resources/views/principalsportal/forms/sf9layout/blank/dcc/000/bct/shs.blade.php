<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        html                        { font-family: Arial, Helvetica, sans-serif;  }
        /* html                        { font-family: Arial, Helvetica, sans-serif; } */
        .left                       { float: left; width : 45%; /* height : 100px; */ /* border: solid 1px black; */ /* display : inline-block; */ }
        .right                      { float: right; width : 45%; /* border: solid 1px black; */ /* background-color: red; */ }
        #logoTable                  { border-collapse: collapse; width:100%; font-size: 11px; }
        
        #studentInfo                { border-spacing: 0; width:100%; font-size: 11px; /* margin: 0px 20px 0px 0px; */ /* font-family: Arial, Helvetica, sans-serif; */ }
        #studentInfo td             { /* border:1px solid black; */ padding:3px; border-spacing: 0; /* margin: 0px 20px 0px 0px; */ /* font-family: Arial, Helvetica, sans-serif; */ }
        #report                     { /* border: 1px solid #ddd; */ border-collapse: collapse; width:100%; }
        .report2                     { /* border: 1px solid #ddd; */ border-collapse: collapse; width:100%; }
        #values                     { /* border: 1px solid #ddd; */ /* border-collapse: collapse; */ border-spacing: 0; width:100%; font-family: Arial, Helvetica, sans-serif; /* text-align: justify; */ }
        #behavior                   { width:45%; }
        #remarks                    { /* border: 1px solid #ddd; */ font-size: 11px; border-collapse: collapse; width:100%; font-family: Arial, Helvetica, sans-serif; }
        #remarksCells               { border-bottom: 1px solid black; }
        #report td, #report th      { border: 1px solid #111; border-collapse: collapse; padding: 4px; font-size: 10px; }
        .report2 td, .report2 th      { border: 1px solid #111; border-collapse: collapse; padding: 4px; font-size: 10px; }
        
        .cellBottom                 { border-bottom: 1px solid #111 !important; }
        .cellRight                  { border-right: 1px solid #111 !important;  }
        #values td, #values th      { border: 1px solid #111; border-bottom: hidden; border-right: hidden; border-collapse: collapse; padding: 4px; font-size: 11px; }
        #reportHeader               { margin:0px 5px 20px 5px; font-family: Arial, Helvetica, sans-serif; }
        #thquarter                  { width:20%; padding:10px; }
        #thsignature                { width:30%; padding:10px; }
        p#signature                 { font-family: Arial, Helvetica, sans-serif; }
        div#letter                  { font-size: 11px; }
        #firstParagraph             { text-indent: 10%; }
        #transfer                   { /* border: 1px solid #ddd; */ border-collapse: collapse; width:100%; margin: 0px 20px 0px 0px; /* font-family: Arial, Helvetica, sans-serif; */ }
        .page_break                 { page-break-before: always; }
    
        #logoTable2                  { border-collapse: collapse; width:100%; font-size: 11px; table-layout: fixed;/* border: 1px solid #ddd; */}
        #logoTable2 td                 {/* border: 1px solid #ddd;*/}
        .text-center {
                  text-align: center !important;
            }

    </style>
</head>
<body>
<table id="logoTable2">
    <tr>
        <td style="width: 25%;">
            <div >
                <br>
                
                <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" style="margin: 2px;" width="70px">
            </div>
        </td>
        <td style="width: 50%; line-height: 15px;">
            <center>
               Republic of the Philippines
                <br>
                    Department of Education - Rgion XI
                <br>
                    Davao City - District III
                <br>
                BROKENSHIRE COLLEGE TORIL, DAVAO CITY INC.
                <br>
                Torill, Davao City, Philippines 8000
                <br>
                Tel. # 291-2556
            </center>
        </td>
        <td style="width: 25%;"><img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="150px"></td>
    </tr>
</table>
<div style="width: 100%; background-color:#709c62; height: 15px;">
    <center>
        <small style="font-size: 11px;">S.Y. {{Session::get('schoolYear')->sydesc}}</small>
    </center>
</div>
<table style="width: 100%; font-size: 12px;">
   
      <tr>
        <td width="10%" >NAME</td>
        <td width="40%" colspan="4" style="border-bottom: 1px solid">{{$student[0]->firstname}} {{$student[0]->lastname}}</td>
        <td width="30%" colspan="3">Learner's Reference Number (LRN):</td>
        <td width="20%" style="border-bottom: 1px solid">{{$student[0]->lrn}}</td>
      </tr>
      <tr>
            <td >AGE:</td>
            <td style="border-bottom: 1px solid">{{\Carbon\Carbon::parse($student[0]->dob)->age}}</td>
            <td style="padding-left:30px">SEX:</td>
            <td style="border-bottom: 1px solid">{{$student[0]->gender}}</td>
            <td></td>
            
            <td colspan="2">GRADE / SECTION :</td>
            <td colspan="2" style="border-bottom: 1px solid">{{$student[0]->levelname}} / {{$student[0]->ensectname}}</td>
      </tr>
      <tr>
        <td >TRACK:</td>
        <td colspan="4" style="border-bottom: 1px solid">{{strtoupper($strandInfo[0]->trackname)}}</td>
        <td >STRAND:</td>
        <td colspan="3" style="border-bottom: 1px solid">{{$strandInfo[0]->strandname}}</td>
    </tr>
      {{-- <tr>
          
         
          
          
      </tr> --}}
</table>
{{-- <table style="width: 100%; font-size: 12px;">
      <tr>
         <td style="position: relative; width: 45%;">
            <table style="width: 100%; ">
                <tr>
                    <td style="width: 20%;">AGE: </td>
                    <td style="border-bottom: 1px solid black;">&nbsp;</td>
                    <td style="text-align: right;">SEX: </td>
                    <td style="border-bottom: 1px solid black;">&nbsp;</td>
                </tr>
            </table>
         </td>
         <td style="position: relative; width: 55%;padding-left: 45px;">
            &nbsp;GRADE/SECTION:
            <div style="width:60%;float:right">
               <div style="border-bottom: 1px solid black;">
                  &nbsp;
               </div>
            </div>
         </td>
      </tr>
</table> --}}
{{-- <table style="width: 100%; font-size: 12px;">
      <tr>
         <td style="position: relative; width: 45%;">
            <div style="width:15%;float:left">TRACK:</div>
            <div style="width:85%;float:right">
               <div style="width:50%; border-bottom: 1px solid black;">
                  &nbsp;
               </div>
            </div>
         </td>
         <td style="position: relative; width: 55%;">
            STRAND:
            <div style="width:70%;float:right">
               <div style="border-bottom: 1px solid black;">
                  &nbsp;
               </div>
            </div>
         </td>
      </tr>
</table> --}}
&nbsp;
<table class="report2"  style="width:100% !important">
    <thead>
        <tr>
            <td colspan="5" style="background-color:#709c62;font-size: 12px;">
                <center><strong>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</strong></center>
            </td>
        </tr>
        <tr>
            <th colspan="2" style="width:70%"><center>FIRST SEMESTER</center></th>
            <th rowspan="2"  style="width: 10%;"><center>1</center></th>
            <th rowspan="2"  style="width: 10%;"><center>2</center></th>
            <th rowspan="2" style="width: 10%;" ><center>FINAL</center></th>
        </tr>
        <tr>
            <th colspan="2"><center>Subjects</center></th>
        </tr>
    </thead>
</table>
<table class="report2"  style="width:100% !important">
    <tbody>
        <tr>
            <td style="background-color:#709c62;font-size: 11px;" width="10%"</td>
            <td style="background-color:#709c62;font-size: 11px;" width="60%">
                CORE SUBJECTS
            </td>
            <td width="10%" style="background-color:#709c62;font-size: 11px;"></td>
            <td width="10%" style="background-color:#709c62;font-size: 11px;"></td>
            <td width="10%" style="background-color:#709c62;font-size: 11px;"></td>
        </tr>
        @foreach (collect($grades)->where('semid',1)->where('type',1) as $item)

                @php
                    $average = ($item->quarter1 + $item->quarter2 ) / 2 ;
                @endphp

                <tr class="table-bordered">
                    <td style="padding-left: 5px">
                        <b>
                            {{-- <i>
                                @if($item->type == 1)
                                    Core
                                @elseif($item->type == 2)
                                    Specialized
                                @elseif($item->type == 3)
                                    Applied
                                @endif
                            </i> --}}
                            {{$item->sc}}
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
        <tr>
            <td style="background-color:#dece23;font-size: 11px;" width="10%"</td>
            <td style="background-color:#dece23;font-size: 11px;" width="60%">
                APPLIED SUBJECTS
            </td>
            <td width="10%" style="background-color:#dece23;font-size: 11px;"></td>
            <td width="10%" style="background-color:#dece23;font-size: 11px;"></td>
            <td width="10%" style="background-color:#dece23;font-size: 11px;"></td>
        </tr>
            @foreach (collect($grades)->where('semid',1)->where('type',3) as $item)

                @php
                    $average = ($item->quarter1 + $item->quarter2 ) / 2 ;
                @endphp

                <tr class="table-bordered">
                    <td style="padding-left: 5px">
                        <b>
                            {{-- <i>
                                @if($item->type == 1)
                                    Core
                                @elseif($item->type == 2)
                                    Specialized
                                @elseif($item->type == 3)
                                    Applied
                                @endif
                            </i> --}}
                            {{$item->sc}}
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
        <tr>
           
            <td style="background-color:#148fc4;font-size: 11px;" width="10%"</td>
                <td style="background-color:#148fc4;font-size: 11px;" width="60%">
                    SPECIALIZED SUBJECT/S
                </td>
                <td width="10%" style="background-color:#148fc4;font-size: 11px;"></td>
                <td width="10%" style="background-color:#148fc4;font-size: 11px;"></td>
                <td width="10%" style="background-color:#148fc4;font-size: 11px;"></td>
        </tr>
       
            @foreach (collect($grades)->where('semid',1)->where('type',2) as $item)
                @php
                    $average = ($item->quarter1 + $item->quarter2 ) / 2 ;
                @endphp

                <tr class="table-bordered">
                    <td style="padding-left: 5px">
                        <b>
                            {{-- <i>
                                @if($item->type == 1)
                                    Core
                                @elseif($item->type == 2)
                                    Specialized
                                @elseif($item->type == 3)
                                    Applied
                                @endif
                            </i> --}}
                            {{$item->sc}}
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

            <tr>
                <td style="font-size: 11px;" width="10%"</td>
                <td style="font-size: 11px;" width="60%">
                    HOMEROOM GUIDANCE LEARNER’S DEVELOPMENT ASSESSMENT
                </td>
                <td width="10%" style="font-size: 11px;"></td>
                <td width="10%" style="font-size: 11px;"></td>
                <td width="10%" style="font-size: 11px;"></td>
            </tr>
            <tr style="font-size:9px">
                <td style="padding-left: 5px"><b>HGLDA<b></td>
                <td>HOMEROOM GUIDANCE LEARNER’S DEVELOPMENT ASSESSMENT</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        <tr>
            <td colspan="2" style="text-align:right"><b>General Average for the Semester</b></td>
            <td colspan="3"></td>
        </tr>
    </tbody>
</table>
<br>
<table class="report2"  >
    <thead>
        <tr>
            <th colspan="2" style="width:70%"><center>SECOND SEMESTER</center></th>
            <th rowspan="2"  style="width: 10%;"><center>3</center></th>
            <th rowspan="2"  style="width: 10%;"><center>4</center></th>
            <th rowspan="2" style="width: 10%;" ><center>FINAL</center></th>
        </tr>
        <tr>
            <th colspan="2"><center>Subjects</center></th>
        </tr>
    </thead>
</table>
<table class="report2"  >
    <tbody>
        <tr>
            <td colspan="5" style="background-color:#709c62;font-size: 11px;">
                CORE SUBJECTS
            </td>
        </tr>
        <tr>
            <td colspan="5" style="background-color:#dece23;font-size: 11px;">
                APPLIED SUBJECTS
            </td>
        </tr>
        <tr>
            <td colspan="5" style="background-color:#148fc4;font-size: 11px;">
                SPECIALIZED SUBJECT/S
            </td>
        </tr>
        <tr>
            <td colspan="2"><center>General Average for the Semester</center></td>
            <td colspan="3"></td>
        </tr>
    </tbody>
</table>
    <table class="report2" style="width: 100%; font-size:12px; text-transform:none; margin-top:5px;table-layout: fixed; text-align:center">
        <tr>
            <th>Descriptors</th>
            <th>Grading Scale</th>
            <th>Remarks</th>
        </tr>
        <tr>
            <td>Outstanding</td>
            <td>90-100</td>
            <td>PASSED</td>
        </tr>
        <tr>
            <td>Very Satisfactory</td>
            <td>85-89</td>
            <td>PASSED</td>
        </tr>
        <tr>
            <td>Satisfactory</td>
            <td>80-84</td>
            <td>PASSED</td>
        </tr>
        <tr>
            <td>Fairly Satisfactory</td>
            <td>75-79</td>
            <td>PASSED</td>
        </tr>
        <tr>
            <td>Did Not Meet Expectations</td>
            <td>Below 75</td>
            <td>FAILED</td>
        </tr>
    </table>
    &nbsp;
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
        <table class="report2"  style="width:100% !important">
            <thead>
                <tr>
                    <th colspan="6" class="cellRight"><center>REPORT ON LEARNER'S OBSERVED VALUES</center></th>
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
                    <td colspan="6"><center>Observed Values</center></td>
                </tr>
            </tbody>
        </table>
        <table class="report2"  style="width:100% !important; ">
            <tbody>
                <tr>
                    @foreach ($rv as $key=>$rvitem)
                        @if($rvitem->value != null)
                            <td style="border-bottom:solid black 1px !important">{{$rvitem->value}} - {{$rvitem->description}}</td>
                        @endif
                    @endforeach 
                </tr>
            </tbody>
        </table>

    @endif
    <br>
    <center><span style="font-size: 11px;"><strong>Certificate of Transfer</strong></span></center>
    <br>
    <table style="width: 100%; table-layout: fixed; font-size: 11px;">
        <tr>
            <td style="width: 15%;">Admitted to Grade:</td>
            <td style="border-bottom: 1px solid black;"></td>
            <td style="width: 25%;">Eligibility for Admission to Grade:</td>
            <td style="border-bottom: 1px solid black;"></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%; table-layout: fixed; font-size: 11px;">
        <tr>
            <td style="padding-left: 10%; padding-right: 10%;text-align:center; width:">
                <div style="width: 100%; border-bottom: 1px solid;text-transform: uppercase;">
                    <strong>&nbsp;</strong>
                </div>
                <br>
                <sup>Adviser</sup>
            </td>
            <td style="padding-left: 10%; padding-right: 10%;text-align:center;">
                <div style="width: 100%; border-bottom: 1px solid;text-transform: uppercase;">
                    <strong>&nbsp;</strong>
                </div>
                <br>
                <sup>Senior High School Principal</sup>
            </td>
        </tr>
    </table>
</body>
</html>