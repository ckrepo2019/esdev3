@extends('teacher.layouts.app')

@section('content')

<style>
    table,th,td{
        font-size: 12px;
        border:1px solid black !important;
        text-align: center;
        padding: 4px !important;
    }
    /* @font-face{
        font-family: "CKsf2";
        src: url('{{asset('fonts/CKsf2.otf')}}');
    } */
    .newFont{
        font-family: "CKsf2";
        font-size: 15px;
        margin: 0px;
    }
    th{
        vertical-align: middle !important;
        text-align: center;
    }
    #header td{
        padding-left: 1px;
    }
    #header, #header th, #header td{
        font-size: 12px;
        border: none !important;
        /* border:1px solid black !important; */
        padding:2px;
        text-align: left;
    }
    input[type=text]{
        text-align: center;
        width:100%;
    }
    .bottom {
        position: absolute;
        bottom: 0;
    }
    .rotate {
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    width: 1.5em;
    padding: 5px !important;
    font-size: 11px;
    }
    .rotate div {
        -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
        -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
    -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
                filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
            margin-left: -10em;
            margin-right: -10em;
    }
    .table td{
            padding: 0px !important;
        }
    .cc {
    /* background: rgba(240, 105, 93, 1); */
    background: linear-gradient(to top left, #b5afaf 50%, white 51%);
    /* color: #fff; */
    height: 20px;
    }
    .late {
    /* background: rgba(240, 105, 93, 1); */
    background: linear-gradient(to top left, white 50%, #b5afaf 51%);
    /* color: #fff; */
    height: 20px;
    }
    /* .padtd td{
        padding: 5px !important;
    } */
    #headerPrint{
        display: none;
    }
    .content td{
        text-transform: uppercase;
    }
    .padtd td{
        text-transform: none;
    }
</style>

<div>
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item"><a href="/forms/index/form2">School Forms</a></li>
            <li class="active breadcrumb-item" aria-current="page">School Form 2</li>
        </ol>
    </nav>
</div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="main-card mb-3 card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-10" id="header">
                            <h5><strong>School Form 2 (SF2) Daily Attendance Report of Learners</strong></h5>
                            <em>This replaced Form 1, Fom 2 & STS Fom 4 - Absenteeism and Dropout Profile</em>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <form method="get">
                                <select id="currentmonth" name="selectedmonth" class="col-md-12" style="text-transform:uppercase;">
                                    <option value="01" {{'01' == $selectedmonth ? 'selected' : ''}}>January</option>
                                    <option value="02" {{'02' == $selectedmonth ? 'selected' : ''}}>February</option>
                                    <option value="03" {{'03' == $selectedmonth ? 'selected' : ''}}>March</option>
                                    <option value="04" {{'04' == $selectedmonth ? 'selected' : ''}}>April</option>
                                    <option value="05" {{'05' == $selectedmonth ? 'selected' : ''}}>May</option>
                                    <option value="06" {{'06' == $selectedmonth ? 'selected' : ''}}>June</option>
                                    <option value="07" {{'07' == $selectedmonth ? 'selected' : ''}}>July</option>
                                    <option value="08" {{'08' == $selectedmonth ? 'selected' : ''}}>August</option>
                                    <option value="09" {{'09' == $selectedmonth ? 'selected' : ''}}>September</option>
                                    <option value="10" {{'10' == $selectedmonth ? 'selected' : ''}}>October</option>
                                    <option value="11" {{'11' == $selectedmonth ? 'selected' : ''}}>November</option>
                                    <option value="12" {{'12' == $selectedmonth ? 'selected' : ''}}>December</option>
                                </select>
                                <input type="hidden" name="action" value="show">
                                <input type="hidden" name="sectionid" value="{{$sectionid}}">
                                <input type="hidden" name="levelid" value="{{$levelid}}">
                            </form>
                        </div>
                        <div class="col-md-8">
                            @if(isset($attendance))
                            
                                <button type="button" class=" btn btn-sm btn-default dropdown-toggle dropdown-icon float-right" data-toggle="dropdown" aria-expanded="true">Export As <span class="sr-only">Toggle Dropdown</span>
                                    <div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item" href="#" id="exportpdf">PDF</a>
                                    {{-- <a class="dropdown-item" href="#" id="exportexcel">EXCEL</a> --}}
                                    </div>
                                </button>
                                {{-- <button id="printBtn"  class="btn btn-primary btn-sm text-white " target="_blank"><i class="fa fa-upload"></i> Print</button> --}}
                                <form id="printform" action="" method="get" target="_blank">
                                    <input type="hidden" name="action" value="print">
                                    <input type="hidden" name="selectedmonth" value="{{$selectedmonth}}">
                                    <input type="hidden" name="sectionid" value="{{$sectionid}}">
                                    <input type="hidden" name="levelid" value="{{$levelid}}">
                                    <input type="hidden" name="enrollmentasof_male" id="formenrollmentasof_male">
                                    <input type="hidden" name="enrollmentasof_female" id="formenrollmentasof_female">
                                    <input type="hidden" name="enrollmentasof_total" id="formenrollmentasof_total">
                                    <input type="hidden" name="lateenrolled_male" id="formlateenrolled_male">
                                    <input type="hidden" name="lateenrolled_female" id="formlateenrolled_female">
                                    <input type="hidden" name="lateenrolled_total" id="formlateenrolled_total">
                                    <input type="hidden" name="registered_male" id="formregistered_male">
                                    <input type="hidden" name="registered_female" id="formregistered_female">
                                    <input type="hidden" name="registered_total" id="formregistered_total">
                                    <input type="hidden" name="enrollmentpercentage_male" id="formenrollmentpercentage_male">
                                    <input type="hidden" name="enrollmentpercentage_female" id="formenrollmentpercentage_female">
                                    <input type="hidden" name="enrollmentpercentage_total" id="formenrollmentpercentage_total">
                                    <input type="hidden" name="pam_male" id="formpam_male">
                                    <input type="hidden" name="pam_female" id="formpam_female">
                                    <input type="hidden" name="pam_total" id="formpam_total">
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <br>
                    <div class="row">
                        <div class="col-md-12" style="overflow-x: scroll !important;">
                            {{-- {{$col}} --}}
                            
                            @php
                                $countMale = 0;
                                $countMaleAbsent = 0;
                                $countMaleTardy = 0;
                                $countMalePresent = 0;

                                $averagedailyatt_male = 0;
                            @endphp
                            
                            @php
                                $countFemale = 0;
                                $countFemaleAbsent = 0;
                                $countFemaleTardy = 0;
                                $countFemalePresent = 0;

                                $averagedailyatt_female = 0;
                            @endphp
                            <table class="table table-bordered content" >   
                                <thead>
                                    <tr>
                                        <th colspan="2" rowspan="3" width="20%">LEARNER'S NAME<br>(Last Name, First Name, Middle Name)</th>
                                        <th colspan="{{count($currentdays)}}">(1st row for date, 2nd row for Day: M, T, W, TH, F)</th>
                                        <th colspan="2" rowspan="2" width="10%">Total for the Month</th>
                                        <th rowspan="3" width="20%">REMARK/S (If DROPPED OUT, state reason, please refer to legend number 2. If TRANSFERRED IN/OUT, write the name of School.) </th>
                                    </tr>
                                    <tr>
                                        @foreach ($currentdays as $numdays)
                                            <th>{{$numdays->daynum}}</th>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        @foreach ($currentdays as $strdays)
                                            <th class='rotate'><div>{{$strdays->daystr}}</div></th>
                                        @endforeach
                                        <th>ABSENT</th>
                                        <th>TARDY</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalmaleabsent = 0;
                                        $totalmaletardy = 0;
                                    @endphp
                                    @foreach($attendance as $student)

                                        @if(strtolower($student->gender) == "male")
                                            @php
                                                $countMale+=1;
                                                $absent = 0;
                                                $tardy = 0;
                                            @endphp
                                            <tr>
                                                <td>{{$countMale}}.</td>
                                                <td width="20%" style="text-align: left;">
                                                    &nbsp;{{$student->lastname}}, {{$student->firstname}} @if($student->middlename != null) {{$student->middlename[0]}}. @endif{{$student->suffix}}
                                                </td>
                                                @foreach ($student->attendance as $attday)
                                                    @if($attday->status == null) 
                                                        <td style="background-color:#afafaf">
                                                        </td>                                                  
                                                    @elseif($attday->status == 1)
                                                        <td>          
                                                            @php
                                                                $absent+=1;
                                                            @endphp
                                                            X
                                                        </td>                   
                                                    @elseif($attday->status == 2)
                                                        <td>
                                                        </td>                             
                                                    @elseif($attday->status == 3)  
                                                        <td class="late">                
                                                            @php
                                                                $tardy+=1;
                                                            @endphp      
                                                        </td>                          
                                                    @elseif($attday->status == 4)   
                                                        <td class="cc">                
                                                            @php
                                                                $tardy+=1;
                                                            @endphp      
                                                        </td> 
                                                    @endif
                                                @endforeach
                                                <td>{{$absent}}</td>
                                                <td>{{$tardy}}</td>
                                                <td></td>
                                            </tr>
                                            @php    
                                                $totalmaleabsent += $absent;
                                                $totalmaletardy += $tardy;
                                            @endphp
                                        @endif
                                    @endforeach
                                    <tr>
                                        <th colspan="2">
                                             MALE | TOTAL Per Day
                                        </th>
                                        @php
                                            $maleDailyAttendance = 0;   
                                        @endphp
                                        @foreach ($maletotalperday as $maletotal)
                                            <td>{{$maletotal->total}}</td>
                                            @php
                                                $maleDailyAttendance+=(int)$maletotal->total;
                                                $averagedailyatt_male+=$maletotal->total;
                                            @endphp
                                        @endforeach
                                        <td>
                                            {{$totalmaleabsent}}
                                        </td>
                                        <td>
                                            {{$totalmaletardy}}
                                        </td>
                                        <td></td>
                                    </tr>
                                    @php
                                        $totalfemaleabsent = 0;
                                        $totalfemaletardy = 0;
                                    @endphp
                                    @foreach ($attendance as $student)
                                        @if(strtolower($student->gender) == "female")
                                            @php
                                                $countFemale+=1;
                                                $absent = 0;
                                                $tardy = 0;
                                            @endphp
                                            <tr>
                                                <td>{{$countFemale}}.</td>
                                                <td width="20%" style="text-align: left;">
                                                    &nbsp;{{$student->lastname}}, {{$student->firstname}}@if($student->middlename != null) {{$student->middlename[0]}}. @endif{{$student->suffix}}
                                                </td>
                                                @foreach ($student->attendance as $attday)
                                                    @if($attday->status == null) 
                                                        <td style="background-color:#afafaf">
                                                        </td>                                                  
                                                    @elseif($attday->status == 1)
                                                        <td>          
                                                            @php
                                                                $absent+=1;
                                                            @endphp
                                                            X
                                                        </td>                   
                                                    @elseif($attday->status == 2)
                                                        <td>
                                                        </td>                             
                                                    @elseif($attday->status == 3)  
                                                        <td class="late">                
                                                            @php
                                                                $tardy+=1;
                                                            @endphp      
                                                        </td>                          
                                                    @elseif($attday->status == 4)   
                                                        <td class="cc">                
                                                            @php
                                                                $tardy+=1;
                                                            @endphp      
                                                        </td> 
                                                    @endif
                                                @endforeach
                                                <td>{{$absent}}</td>
                                                <td>{{$tardy}}</td>
                                                <td></td>
                                            </tr>
                                            @php    
                                                $totalfemaleabsent += $absent;
                                                $totalfemaletardy += $tardy;
                                            @endphp
                                        @endif
                                    @endforeach
                                    <tr>
                                        <th colspan="2">
                                             FEMALE | TOTAL Per Day
                                        </th>
                                        @php
                                            $femaleDailyAttendance = 0;   
                                        @endphp
                                        @foreach ($femaletotalperday as $femaletotal)
                                            <td>{{$femaletotal->total}}</td>
                                            @php
                                                $femaleDailyAttendance+=(int)$femaletotal->total;
                                                $averagedailyatt_female+=$femaletotal->total;
                                            @endphp
                                        @endforeach
                                        <td>
                                            {{$totalfemaleabsent}}
                                        </td>
                                        <td>
                                            {{$totalfemaletardy}}
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">
                                             COMBINED TOTAL PER DAY
                                        </th>
                                        @php
                                            $totalDailyAttendance = 0;
                                        @endphp
                                        @foreach ($studentstotalperday as $totalperday)
                                            <td>{{$totalperday->total}}</td>
                                            @php
                                                $totalDailyAttendance+=(int)$totalperday->total;
                                            @endphp
                                        @endforeach
                                        <td>
                                            {{$totalmaleabsent + $totalfemaleabsent}}
                                        </td>
                                        <td>
                                            {{$totalmaletardy + $totalfemaletardy}}
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <small><strong>GUIDELINES:</strong></small>
                            <small>
                                <ol style="font-size: 11px; padding-left: 10px;">
                                    <li> The attendance shall be accomplished daily. Refer to the codes for checking learners' attendance.</li>
                                    <li> Dates shall be written in the preceding columns beside Learner's Name.</li>
                                    <li>
                                        To compute the following:
                                        <br>
                                        <div class="row" style="">
                                            <div class="col-md-5"  style="">
                                                a. Percentage of Enrollment = 
                                            </div>
                                            <div class="col-md-5">
                                                <div class="row text-center" style="border-bottom: 1px solid black;"><center>Registered Learner as of End of the Month</center></div> 
                                                <div class="row" style=""><center>Enrollment as of 1st Friday of June</center></div> 
                                            </div>
                                            <div class="col-md-2" style=" vertical-align:middle !important;text-align:center; ">
                                                 x100
                                            </div>
                                        </div>
                                        <div class="row" style="">
                                            <div class="col-md-5"  style="">
                                                b. Average Daily Attendance = 
                                            </div>
                                            <div class="col-md-5" style="">
                                                <div class="row" style="border-bottom: 1px solid black;"><center>Total Daily Attendance</center></div> 
                                                <div class="row" style=""><center>Number of School Days in Reporting Month</center></div> 
                                            </div>
                                            <div class="col-md-2" style=" vertical-align:middle !important;text-align:center;">
                                                &nbsp;
                                            </div>
                                        </div>
                                        <div class="row" style="">
                                            <div class="col-md-5"  style="">
                                                c. Pecentage of Attendance for the month = 
                                            </div>
                                            <div class="col-md-5" style="text-align:center;">
                                                <div class="row text-center" style="border-bottom: 1px solid black;"><center>Average Daily Attendance</center></div> 
                                                <div class="row" style=""><center>Registered Learner as of End of the Month</center></div> 
                                            </div>
                                            <div class="col-md-2" style=" vertical-align:middle !important;text-align:center;">
                                                    x100
                                            </div>
                                        </div>
                                        <br>
                                    </li>
                                    <li> Every End of the month, the class adviser will submit this form to the office of the principal for recording of summary table into the School Form 4. Once signed by the principal, this form should be returned to the adviser.</li>
                                    <li> The adviser will extend neccessary intervention including but not limited to home visitation to learner/s that committed 5 consecutive days of absences or those with potentials of dropping out.</li>
                                    <li> Attendance performance of learner is expected to reflect in Form 137 and Form 138 every grading period<br> * Beginning of School Year cut-off report is every 1st Friday of School Calendar Days</li>
                                </ol>
                            </small>
                        </div>
                        <div class="col-md-3" style="border: 1px solid black;padding:0px;">
                            <div class="col-md-12" style="border-bottom: 1px solid black;">
                                <small>
                                    <strong>1. CODES FOR CHECKING ATTENDANCE</strong>
                                </small>
                            </div>
                            <div class="col-md-12" style="font-size: 11px; padding-top:5px; padding-bottom:5px;">
                                    <strong>blank</strong> - Present; (<strong>x</strong>)- Absent; Tardy (<strong>half shaded</strong> = Upper for Late Comer, Lower for Cutting Classes)
                            </div>
                            <div class="col-md-12" >
                                <span>
                                    <small>
                                        <strong>2. REASONS/CAUSES OF DROP-OUTS</strong>
                                    </small>
                                </span>
                                <br>
                                <div style="padding-bottom:3px;">
                                    <span style="font-size: 11px;">
                                        <strong>a. Domestic-Related Factors</strong>
                                    </span>
                                    <br>
                                    <span style="font-size: 11px;">
                                        a.1. Had to take care of siblings
                                    </span>
                                    <br>
                                    <span style="font-size: 11px;">
                                        a.2. Early marriage/pregnancy
                                    </span>
                                    <br>
                                    <span style="font-size: 11px;">
                                        a.3. Parents' attitude toward schooling
                                    </span>
                                    <br>
                                    <span style="font-size: 11px;">
                                        a.4. Family problems
                                    </span>
                                </div>
                                <div style="padding-bottom:3px;">
                                    <span style="font-size: 11px;">
                                        <strong>b. Individual-Related Factors</strong>
                                    </span>
                                    <br>
                                    <span style="font-size: 11px;">
                                        b.1. Illness
                                    </span>
                                    <br>
                                    <span style="font-size: 11px;">
                                        b.2. Overage
                                    </span>
                                    <br>
                                    <span style="font-size: 11px;">
                                        b.3. Death
                                    </span>
                                    <br>
                                    <span style="font-size: 11px;">
                                        b.4. Drug Abuse
                                    </span>
                                    <br>
                                    <span style="font-size: 11px;">
                                        b.5. Poor academic performance
                                    </span>
                                    <br>
                                    <span style="font-size: 11px;">
                                        b.6. Lack of interest/Distractions
                                    </span>
                                    <br>
                                    <span style="font-size: 11px;">
                                        b.7. Hunger/Malnutrition
                                    </span>
                                </div>
                                <div style="padding-bottom:3px;">
                                    <span style="font-size: 11px;">
                                        <strong>c. School-Related Factors</strong>
                                    </span>
                                    <br>
                                    <span style="font-size: 11px;">
                                        c.1. Teacher Factor
                                    </span>
                                    <br>
                                    <span style="font-size: 11px;">
                                        c.2. Physical condition of classroom
                                    </span>
                                    <br>
                                    <span style="font-size: 11px;">
                                        c.3. Peer influence
                                    </span>
                                </div>
                                <div style="padding-bottom:3px;">
                                    <span style="font-size: 11px;">
                                        <strong>d. Geographic/Environmental</strong>
                                    </span>
                                    <br>
                                    <span style="font-size: 11px;">
                                        d.1. Distance between home and school
                                    </span>
                                    <br>
                                    <span style="font-size: 11px;">
                                        d.2. Armed conflict (incl. Tribal wars & clan feuds)
                                    </span>
                                    <br>
                                    <span style="font-size: 11px;">
                                        d.3. Calamities/Disasters
                                    </span>
                                </div>
                                <div style="padding-bottom:3px;">
                                    <span style="font-size: 11px;">
                                        <strong>e. Financial-Related</strong>
                                    </span>
                                    <br>
                                    <span style="font-size: 11px;">
                                        e.1. Child labor, work
                                    </span>
                                </div>
                                <div style="padding-bottom:3px;">
                                    <span style="font-size: 11px;">
                                        <strong>f. Others</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-bordered padtd">
                                <tr>
                                    <th rowspan="2" width="30%">Month:
                                        <span class="month">
                                            @if(isset($selected_month_string))
                                            {{$selected_month_string}}
                                            @endif
                                        </span>
                                    </th>
                                    <th rowspan="2" width="40%">No. of Days of Classes:
                                        @if(isset($days_num))
                                            {{count($days_num)}}
                                        @endif
                                     </th>
                                    <th colspan="3" width="30%">Summary for the Month</th>
                                </tr>
                                <tr>
                                    <th>M</th>
                                    <th>F</th>
                                    <th>TOTAL</th>
                                </tr>
                                <tr>
                                    <td colspan="2" class="p-0">
                                        <button type="button" class="btn btn-sm btn-block btn-warning p-1 m-0" id="selectenrollmentmonth" style="font-size: 12px;">Enrollment as of (1st Friday of {{date("F", mktime(0, 0, 0, $enrollmentmonth, 10))}})</button>
                                    </td>
                                    <td id="enrollmentasof_male">
                                        
                                    </td>
                                    <td id="enrollmentasof_female">
                                        
                                    </td>
                                    <td id="enrollmentasof_total">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><em>Late Enrollment <strong>during the month</strong><br>(beyond cut-off)</em></td>
                                    <td id="lateenrolled_male">
                                        
                                    </td>
                                    <td id="lateenrolled_female">
                                        
                                    </td>
                                    <td id="lateenrolled_total">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><em>Registered Learner as of <strong>end of the month</strong></em></td>
                                    <td id="registered_male">
                                        
                                    </td>
                                    <td id="registered_female">
                                        
                                    </td>
                                    <td id="registered_total">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><em>Percentage of Enrollment as of <strong>end of the month</strong></em></td>
                                    <td id="enrollmentpercentage_male">
                                        
                                    </td>
                                    <td id="enrollmentpercentage_female">
                                        
                                    </td>
                                    <td id="enrollmentpercentage_total">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><em>Average Daily Attendance</em></td>
                                    <td id="ada_male">
                                        @php
                                            if($averagedailyatt_male <=0 || count($currentdays) == 0)
                                            {
                                                $avedailyatt_male = 0;
                                            }else{
                                                $avedailyatt_male = round(($averagedailyatt_male/count($currentdays)),2);
                                            }
                                        @endphp
                                        {{$avedailyatt_male}}
                                    </td>
                                    <td id="ada_female">
                                        @php
                                            if($averagedailyatt_female <=0 || count($currentdays) == 0)
                                            {
                                                $avedailyatt_female = 0;
                                            }else{
                                                $avedailyatt_female = round(($averagedailyatt_female/count($currentdays)),2);
                                            }
                                        @endphp
                                        {{$avedailyatt_female}}
                                    </td>
                                    <td  id="ada_total">
                                        @php
                                            if($avedailyatt_male == 0 || $avedailyatt_female == 0)
                                            {
                                                $totalaveragedaily = $avedailyatt_male+$avedailyatt_female;
                                            }else{
                                                $totalaveragedaily = (round($avedailyatt_male,2))+(round($avedailyatt_female,2));
                                            }
                                        @endphp
                                        {{$totalaveragedaily}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><em>Percentage of Attendance for the month</em></td>
                                    <td id="pam_male">
                                        
                                    </td>
                                    <td id="pam_female">
                                        
                                    </td>
                                    <td id="pam_total">
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2"><em>Number of students with 5 consecutive days of absences:</em></td>
                                    <td>{{$countconsecutive_male}}</td>
                                    <td>{{$countconsecutive_female}}</td>
                                    <td>{{$countconsecutive_total}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"><em><strong>Drop out</strong></em></td>
                                    <td>
                                        {{$droppedout_male}}
                                    </td>
                                    <td>
                                        {{$droppedout_female}}
                                    </td>
                                    <td>
                                        {{$droppedout_total}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><em><strong>Transferred out</strong></em></td>
                                    <td>
                                        {{$transferredout_male}}
                                    </td>
                                    <td>
                                        {{$transferredout_female}}
                                    </td>
                                    <td>
                                        {{$transferredout_total}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><em><strong>Transferred in</strong></em></td>
                                    <td>
                                        {{$transferredin_male}}
                                    </td>
                                    <td>
                                        {{$transferredin_female}}
                                    </td>
                                    <td>
                                        {{$transferredin_total}}
                                    </td>
                                </tr>
                            </table>
                            <div style="font-size: 11px;">
                                <span>
                                    <em>I certify that this is a true and correct report.</em>
                                </span>
                            </div>
                            <div style="font-size: 11px; text-align:center;">
                                <center>
                                    
                                    <span>
                                        {{-- {{$teachername->firstname}} {{$teachername->middlename[0]}}. {{$teachername->lastname}} {{$teachername->suffix}} --}}
                                    </span>
                                    
                                    <hr class="col-md-8 p-0 m-0" style="border-color: black;"/>
                                    <em class="p-0">(Signature of Teacher over Printed Name)</em>
                                </center>
                            </div>
                            <div style="font-size: 11px;">
                                <span>
                                    <em>Attested by:</em>
                                </span>
                            </div>
                            <div style="font-size: 11px; text-align:center;">
                                <center>
                                    {{$schoolinfo->authorized}}
                                    <hr class="col-md-8 p-0 m-0" style="border-color: black;"/>
                                    <em class="p-0">(Signature of School Head over Printed Name)</em>
                                </center>
                            </div>
                        </div>
                    </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- </div> --}}

<div class="modal fade" id="enrollmonth" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Select Month</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="/forms/form2enrollmentmonth" method="get">
            <div class="modal-body">
                <label>Enrollment as of 1st Friday of</label>
                <select id="selectedenrollmentmonth" name="selectedenrollmentmonth" class="col-md-12" style="text-transform:uppercase;">
                    <option value="01" {{'01' == $enrollmentmonth ? 'selected' : ''}}>January</option>
                    <option value="02" {{'02' == $enrollmentmonth ? 'selected' : ''}}>February</option>
                    <option value="03" {{'03' == $enrollmentmonth ? 'selected' : ''}}>March</option>
                    <option value="04" {{'04' == $enrollmentmonth ? 'selected' : ''}}>April</option>
                    <option value="05" {{'05' == $enrollmentmonth ? 'selected' : ''}}>May</option>
                    <option value="06" {{'06' == $enrollmentmonth ? 'selected' : ''}}>June</option>
                    <option value="07" {{'07' == $enrollmentmonth ? 'selected' : ''}}>July</option>
                    <option value="08" {{'08' == $enrollmentmonth ? 'selected' : ''}}>August</option>
                    <option value="09" {{'09' == $enrollmentmonth ? 'selected' : ''}}>September</option>
                    <option value="10" {{'10' == $enrollmentmonth ? 'selected' : ''}}>October</option>
                    <option value="11" {{'11' == $enrollmentmonth ? 'selected' : ''}}>November</option>
                    <option value="12" {{'12' == $enrollmentmonth ? 'selected' : ''}}>December</option>
                </select>
            </div>
            <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<script>
$(document).ready(function(){
    $("#currentmonth").on('change',function () {
        // $('form[name=formSubmit]').submit();
        var thisform = $(this).closest('form');
        thisform.attr('action',"/forms/form2")
        thisform.submit()
    });
    $('#exportpdf').on('click',function(){
        var thisform =  $('#printform');
        $('#formenrollmentasof_male').val($('#enrollmentasof_male').text());
        $('#formenrollmentasof_female').val($('#enrollmentasof_female').text());
        $('#formenrollmentasof_total').val($('#enrollmentasof_total').text());
        $('#formlateenrolled_male').val($('#lateenrolled_male').text());
        $('#formlateenrolled_female').val($('#lateenrolled_female').text());
        $('#formlateenrolled_total').val($('#lateenrolled_total').text());
        $('#formregistered_male').val($('#registered_male').text());
        $('#formregistered_female').val($('#registered_female').text());
        $('#formregistered_total').val($('#registered_total').text());
        $('#formenrollmentpercentage_male').val($('#enrollmentpercentage_male').text());
        $('#formenrollmentpercentage_female').val($('#enrollmentpercentage_female').text());
        $('#formenrollmentpercentage_total').val($('#enrollmentpercentage_total').text());
        $('#formpam_male').val($('#pam_male').text());
        $('#formpam_female').val($('#pam_female').text());
        $('#formpam_total').val($('#pam_total').text());
        thisform.attr('action',"/forms/form2")
        thisform.submit()
    })
    $('body').addClass('sidebar-collapse')
    $('#selectenrollmentmonth').on('click', function(){
        $('#enrollmonth').modal('show')
    })
    $.ajax({
        url: '/forms/form2summarytable',
        type: 'GET',
        dataType: 'json',
        data: {
            selectedmonth: $('#currentmonth').val(),
            enrollmentmonth: $('#selectedenrollmentmonth').val(),
            sectionid: '{{$sectionid}}',
            levelid: '{{$levelid}}'
        },
        success:function(data)
        {
            $('#enrollmentasof_male').text(data.enrollmentasof_male)
            $('#enrollmentasof_female').text(data.enrollmentasof_female)
            $('#enrollmentasof_total').text((data.enrollmentasof_male)+(data.enrollmentasof_female))

            $('#lateenrolled_male').text(data.lateenrolled_male)
            $('#lateenrolled_female').text(data.lateenrolled_female)
            $('#lateenrolled_total').text((data.lateenrolled_male)+(data.lateenrolled_female))

            $('#registered_male').text(data.registered_male)
            $('#registered_female').text(data.registered_female)
            $('#registered_total').text(data.registered_total)

            $('#enrollmentpercentage_male').text(data.enrollmentpercentage_male+' %')
            $('#enrollmentpercentage_female').text(data.enrollmentpercentage_female+' %')
            $('#enrollmentpercentage_total').text(data.enrollmentpercentage_total+' %')
            
            var ada_male = parseFloat($('#ada_male').text())
            var ada_female = parseFloat($('#ada_female').text())
            var ada_total = parseFloat($('#ada_total').text())

            if(ada_male <= 0 || data.registered_male <= 0)
            {
                $('#pam_male').text('0 %');
            }
            else{
                $('#pam_male').text(((ada_male/data.registered_male)*100)+' %');
            }
            if(ada_female <= 0 || data.registered_female <= 0)
            {
                $('#pam_female').text('0 %');
            }
            else{
                $('#pam_female').text(((ada_female/data.registered_female)*100)+' %');
            }
            if(ada_total <= 0 || data.registered_total <= 0)
            {
                $('#pam_total').text('0 %');
            }
            else{
                $('#pam_total').text(((ada_total/data.registered_total)*100)+' %');
            }

        }
    })
});
</script>

@endsection