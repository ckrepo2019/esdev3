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
    .padtd td{
        padding: 5px !important;
    }
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
<style type="text/css" media="print">
    @page { size: landscape; }
    body {
        transform: scale(1);
        margin: 5mm 0mm 0mm 0mm;
        }
    #printBtn { visibility: hidden; }
    select {
    -webkit-appearance: none;
    -moz-appearance: none;
    text-indent: 1px;
    text-overflow: '';
    -ms-text-align-last: center;
    -moz-text-align-last: center;
    text-align-last: center;
    }
    #headerPrint{
        text-align:center;
        display: block;
    }
    #headerHtml{
        display: none;
    }
</style>

<div class="container-fluid">
    <div id="headerPrint">
        <h4>School Form 2 (SF2) Daily Attendance Report of Learners</h4>
        <em>This replaced Form 1, Fom 2 & STS Fom 4 - Absenteeism and Dropout Profile</em>
    </div>
    <div id="headerHtml">
        <div class="row mb-2">
          <div class="col-sm-10" id="header">
                <h4>School Form 2 (SF2) Daily Attendance Report of Learners</h4>
                <em>This replaced Form 1, Fom 2 & STS Fom 4 - Absenteeism and Dropout Profile</em>
          </div>
          <div class="col-sm-2">
            <ol class="breadcrumb float-sm-right">
                {{-- <a href="/schoolForm_2/preview/{{$selected_month}}" target="_blank" class="btn btn-success btn-sm text-white "> --}}
                        {{-- <i class="fa fa-upload"></i> --}}
                    {{-- Print{{$selected_month}} --}}
                    
                {{-- </a> --}}
                <button id="printBtn"  class="btn btn-success btn-sm text-white " target="_blank"><i class="fa fa-upload"></i> Print</button>
                <form name="formSubmitPrint" action="" method="GET" target="_blank">
                    <select name="m_id" class="col-md-12" style="text-transform:uppercase; display: none;">
                        @if(isset($months))
                            @foreach ($months as $month)
                                <option value="{{$month->month_id}}" {{$month->month == $selected_month_string ? 'selected' : ''}}>{{$month->month}} {{$month->month_id}}</option>
                            @endforeach
                        @endif
                    </select> 
                </form>
            </ol>
          </div>
        </div>
    </div>
{{-- <input type="text" class="saqdaqdas" value="{{$selected_month}}"/> --}}
</div>
    <div class="row">
        @php
            $hey = isset($days_num);
            $col = count($days_num);   
            // echo $months;
        @endphp
        <div class="col-md-12 col-xl-12">
            <div class="main-card mb-3 card ">
                <div class="card-body">
                    <table id="header" class="table table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2" style="padding:10px;">
                                    <center><img src="{{asset('assets/images/department_of_Education.png')}}" alt="school" width="70px"></center>
                                </th>
                                <th style="padding:0px 2px 0px 20px;">School ID</th>
                                <th><input type="text" value="{{$school[0]->schoolid}}" readonly/></th>
                                <th>School Year</th>
                                <th><input type="text" value="{{$sy}}" readonly/></th>
                                <th>Report for the Month of</th>
                                <th colspan="3">
                                    <form name="formSubmit" action="" method="GET">
                                        @csrf
                                        <select name="month_id" class="col-md-12" style="text-transform:uppercase;">
                                            @if(isset($months))
                                            {{-- <option value="" ></option> --}}
                                               @foreach ($months as $month)
                                               @if($month->month == 'January')
                                                @php
                                                    $month_id = 1;   
                                                @endphp
                                               @endif
                                               @if($month->month == 'February')
                                               @php
                                                   $month_id = 2;   
                                               @endphp
                                               @endif
                                               @if($month->month == 'March')
                                               @php
                                                   $month_id = 3;   
                                               @endphp
                                               @endif
                                               @if($month->month == 'April')
                                               @php
                                                   $month_id = 4;   
                                               @endphp
                                               @endif
                                               @if($month->month == 'May')
                                               @php
                                                   $month_id = 5;   
                                               @endphp
                                               @endif
                                               @if($month->month == 'June')
                                               @php
                                                   $month_id = 6;   
                                               @endphp
                                               @endif
                                               @if($month->month == 'July')
                                               @php
                                                   $month_id = 7;   
                                               @endphp
                                               @endif
                                               @if($month->month == 'August')
                                               @php
                                                   $month_id = 8;   
                                               @endphp
                                               @endif
                                               @if($month->month == 'September')
                                               @php
                                                   $month_id = 9;   
                                               @endphp
                                               @endif
                                               @if($month->month == 'October')
                                               @php
                                                   $month_id = 10;   
                                               @endphp
                                               @endif
                                               @if($month->month == 'November')
                                               @php
                                                   $month_id = 11;   
                                               @endphp
                                               @endif
                                               @if($month->month == 'December')
                                               @php
                                                   $month_id = 12;   
                                               @endphp
                                               @endif
                                            <option value="{{$month_id}}" {{($month->month) == ($selected_month_string) ? 'selected' : ''}}>{{$month->month}}</option>
                                                @endforeach
                                            @endif
                                        </select>   
                                        
                                    </form>
                                </th>
                                <th></th>
                            </tr>
                            <tr>
                                <th>School Name</th>
                                <th colspan="3"><input type="text" value="{{$school[0]->schoolname}}" readonly/></th>
                                <th>Grade Level</th>
                                <th><input type="text" value="{{$gradeAndLevel[0]->levelname}}" readonly/></th>
                                <th>Section</th>
                                <th colspan="2"><input type="text" value="{{$gradeAndLevel[0]->sectionname}}" readonly/></th>
                            </tr>
                        </thead>
                    </table>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            {{-- {{$col}} --}}
                            <table class="table table-bordered content" >   
                                <thead>
                                    <tr>
                                        <th colspan="2" rowspan="3" width="20%">LEARNER'S NAME<br>(Last Name, First Name, Middle Name)</th>
                                        <th colspan="{{$col}}">(1st row for date, 2nd row for Day: M, T, W, TH, F)</th>
                                        <th colspan="2" rowspan="2" width="10%">Total for the Month</th>
                                        <th rowspan="3" width="20%">REMARKS/S (If DROPPED OUT, state reason, please refer to legend number 2. If TRANSFERRED IN/OUT, write the name of School.) </th>
                                    </tr>
                                    <tr>
                                        @if(isset($days_num))
                                            @foreach ($days_num as $numdays)
                                                <th>{{$numdays}}</th>
                                            @endforeach
                                        @endif
                                    </tr>
                                    <tr>
                                        @if(isset($days_str))
                                        @foreach ($days_str as $strdays)
                                            <th class='rotate'><div>{{$strdays}}</div></th>
                                        @endforeach
                                        @endif
                                        <th>ABSENT</th>
                                        <th>TARDY</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($attendance))
                                        @php
                                            $countMale = 0;
                                            $countMaleAbsent = 0;
                                            $countMaleTardy = 0;
                                            $countMalePresent = 0;
                                        @endphp
                                        @foreach ($attendance as $perStudent)
                                            @if($perStudent->studentinfo->gender == "MALE" || $perStudent->studentinfo->gender == "Male" )
                                                @php
                                                    $countMale+=1;
                                                @endphp
                                                <tr>
                                                    <td>{{$countMale}}.</td>
                                                    <td width="20%" style="text-align: left;">
                                                        &nbsp;{{$perStudent->studentinfo->lastname}}, {{$perStudent->studentinfo->firstname}} {{$perStudent->studentinfo->middlename[0]}}. {{$perStudent->studentinfo->suffix}}
                                                    </td>
                                                    @foreach($perStudent->studentattendance as $numdays)
                                                    <td>
                                                        @if($numdays->status == "no record")
                                                            <div style="background-color:#afafaf">&nbsp;</div>
                                                        @elseif($numdays->status == "present")
                                                        <span>&nbsp;</span>
                                                        @php
                                                            $countMalePresent+=1;
                                                        @endphp
                                                        @elseif($numdays->status == "absent")
                                                        <span>x</span>
                                                        @php
                                                            $countMaleAbsent+=1;
                                                        @endphp
                                                        @elseif($numdays->status == "cuttingclasses")
                                                        <div class="cc"></div>
                                                        @php
                                                            $countMalePresent+=1;
                                                            $countMaleTardy+=1;
                                                        @endphp
                                                        @elseif($numdays->status == "late")
                                                        <div class="late"></div>
                                                        @php
                                                            $countMalePresent+=1;
                                                            $countMaleTardy+=1;
                                                        @endphp
                                                        @elseif($numdays->status == '')
                                                        <span>&nbsp;</span>
                                                        @endif
                                                    </td>
                                                    @endforeach
                                                    <td>{{$perStudent->numberOfAbsent}}</td>
                                                    <td>{{$perStudent->numberOfTardy}}</td>
                                                    <td></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                    <tr>
                                        <th colspan="2">
                                             MALE | TOTAL Per Day
                                        </th>
                                        @if(isset($countMaleDailyAttendance))
                                            @php
                                                $maleDailyAttendance = 0;   
                                            @endphp
                                            @foreach ($countMaleDailyAttendance as $totalMaleDaily)
                                                <td>{{$totalMaleDaily->total}}</td>
                                                @php
                                                    $maleDailyAttendance+=(int)$totalMaleDaily->total;
                                                @endphp
                                            @endforeach
                                        @endif
                                        <td>
                                            @if(isset($countMaleAbsent))
                                            {{$countMaleAbsent}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($countMaleTardy))
                                            {{$countMaleTardy}}
                                            @endif
                                        </td>
                                        <td></td>
                                    </tr>
                                    @if(isset($attendance))
                                        @php
                                            $countFemale = 0;
                                            $countFemaleAbsent = 0;
                                            $countFemaleTardy = 0;
                                            $countFemalePresent = 0;
                                        @endphp
                                        @foreach ($attendance as $perStudent)
                                            @if($perStudent->studentinfo->gender == "FEMALE" || $perStudent->studentinfo->gender == "Female")
                                                @php
                                                    $countFemale+=1;
                                                @endphp
                                                <tr>
                                                    <td>{{$countFemale}}.</td>
                                                    <td width="20%" style="text-align: left;">
                                                        &nbsp;{{$perStudent->studentinfo->lastname}}, {{$perStudent->studentinfo->firstname}} {{$perStudent->studentinfo->middlename[0]}}. {{$perStudent->studentinfo->suffix}}
                                                    </td>
                                                    @foreach ($perStudent->studentattendance as $numdays)
                                                    <td>
                                                        @if($numdays->status == "no record")
                                                            <div style="background-color:#afafaf">&nbsp;</div>
                                                        @elseif($numdays->status == "present")
                                                        <span>&nbsp;</span>
                                                        @php
                                                            $countFemalePresent+=1;
                                                        @endphp
                                                        @elseif($numdays->status == "absent")
                                                        <span>x</span>
                                                        @php
                                                            $countFemaleAbsent+=1;
                                                        @endphp
                                                        @elseif($numdays->status == "cuttingclasses")
                                                        <div class="cc"></div>
                                                        @php
                                                        $countFemalePresent+=1;
                                                        $countFemaleTardy+=1;
                                                        @endphp
                                                        @elseif($numdays->status == "late")
                                                        <div class="late"></div>
                                                        @php
                                                        $countFemalePresent+=1;
                                                        $countFemaleTardy+=1;
                                                        @endphp
                                                        @endif
                                                    </td>
                                                    @endforeach
                                                    <td>{{$perStudent->numberOfAbsent}}</td>
                                                    <td>{{$perStudent->numberOfTardy}}</td>
                                                    <td></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                    <tr>
                                        <th colspan="2">
                                             FEMALE | TOTAL Per Day
                                        </th>
                                        @if(isset($countFemaleDailyAttendance))
                                            @php
                                                $femaleDailyAttendance = 0;
                                            @endphp
                                            @foreach ($countFemaleDailyAttendance as $totalFemaleDaily)
                                                <td>{{$totalFemaleDaily->total}}</td>
                                                @php
                                                    $femaleDailyAttendance+=(int)$totalFemaleDaily->total;  
                                                @endphp
                                            @endforeach
                                        @endif
                                        <td>
                                            @if(isset($countFemaleAbsent))
                                            {{$countFemaleAbsent}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($countFemaleTardy))
                                            {{$countFemaleTardy}}
                                            @endif
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">
                                             COMBINED TOTAL PER DAY
                                        </th>
                                        @if(isset($countDailyAttendance))
                                            @php
                                                $totalDailyAttendance = 0;
                                            @endphp
                                            @foreach ($countDailyAttendance as $dailyAttendance)
                                                <td>{{$dailyAttendance->total}}</td>
                                                @php
                                                    $totalDailyAttendance+=(int)$dailyAttendance->total;
                                                @endphp
                                            @endforeach
                                        @endif
                                        <td>
                                            @if(isset($countMaleAbsent) && isset($countFemaleAbsent))
                                            {{$countMaleAbsent + $countFemaleAbsent}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($countMaleTardy) && isset($countFemaleTardy))
                                            {{$countMaleTardy + $countFemaleTardy}}
                                            @endif
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
                                    <li> Dates shall be witten in the preceding columns beside Leaner's Name.</li>
                                    <li>
                                        To compute the following:
                                        <br>
                                        <div class="row" style="">
                                            <div class="col-md-6"  style="">
                                                a. Percentage of Enrolment = 
                                            </div>
                                            <div class="col-md-5">
                                                <div class="row text-center" style="border-bottom: 1px solid black;">Registered Learner as of End of the Month</div> 
                                                <div class="row" style="">Enrolment as of 1st Fiday of June</div> 
                                            </div>
                                            <div class="col-md-1" style=" vertical-align:middle !important;text-align:center; padding: 5px;">
                                                 <span>x100</span>
                                            </div>
                                        </div>
                                        <div class="row" style="">
                                            <div class="col-md-6"  style="">
                                                b. Average Daily Attendance = 
                                            </div>
                                            <div class="col-md-5" style="">
                                                <div class="row" style="border-bottom: 1px solid black;">Total Daily Attendance</div> 
                                                <div class="row" style="">Number of School Days in reporting month</div> 
                                            </div>
                                            <div class="col-md-1" style=" vertical-align:middle !important;text-align:center;">
                                                &nbsp;
                                            </div>
                                        </div>
                                        <div class="row" style="">
                                            <div class="col-md-6"  style="">
                                                c. Pecentage of Attendance for the month = 
                                            </div>
                                            <div class="col-md-5" style="">
                                                <div class="row" style="border-bottom: 1px solid black;">Average daily attendance</div> 
                                                <div class="row" style="">Registered Learner as of End of the month</div> 
                                            </div>
                                            <div class="col-md-1" style=" vertical-align:middle !important;text-align:center; padding: 5px;">
                                                    <span>x100</span>
                                            </div>
                                        </div>
                                        <br>
                                    </li>
                                    <li> Every End of the month, the class adviser will submit this form to the office of the pincipal fo recording of summary table into the School Form 4. Once signed by the principal, this form should be returned to the adviser.</li>
                                    <li> The adviser will extend neccessary intervention including but not limited to home visitation to learner/s that committed 5 consecutive days of absences or those with potentials of dropping out.</li>
                                    <li> Attendance peformance of leaner is expected to reflect in Form 137 and Form 138 every grading period<br> * Beginning of School Year cut-off report is every 1st Fiday of School Calenda Days</li>
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
                                    <strong>blank</strong> - Pesent; (x)- Absent; Tardy (half shaded = Upper for Late Commer, Lowe for Cutting Classes)
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
                                        a.3. Parents' attitude towad schooling
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
                                        b.6. Lack of interest/Dsitractions
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
                                        d.2. Armed conflict (incl. Tribal wars & clanfeuds)
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
                                    <th rowspan="2" width="40%">No. of Days of Classes: 1</th>
                                    <th colspan="3" width="30%">Summary for the Month</th>
                                </tr>
                                <tr>
                                    <th>M</th>
                                    <th>F</th>
                                    <th>TOTAL</th>
                                </tr>
                                <tr>
                                    <td colspan="2"><em>* Enrolment as of (1st Friday of June)</em></td>
                                    <td>
                                        @if(isset($numberOfJuneEnrolleesMale))
                                        {{$numberOfJuneEnrolleesMale}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($numberOfJuneEnrolleesFemale))
                                        {{$numberOfJuneEnrolleesFemale}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($numberOfJuneEnrolleesTotal))
                                        {{$numberOfJuneEnrolleesTotal}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><em>Late Enrollment <strong>during the month</strong><br>(beyond cut-off)</em></td>
                                    <td>
                                        @if(isset($numberOfJuneLateEnrolleesMale))
                                        {{$numberOfJuneLateEnrolleesMale}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($numberOfJuneLateEnrolleesFemale))
                                        {{$numberOfJuneLateEnrolleesFemale}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($numberOfJuneLateEnrolleesTotal))
                                        {{$numberOfJuneLateEnrolleesTotal}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><em>Registered Learner as of <strong>end of the month</strong></em></td>
                                    <td>
                                        @if(isset($getNumberOfRegisteredEnrolleesMale))
                                        {{$getNumberOfRegisteredEnrolleesMale}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($getNumberOfRegisteredEnrolleesFemale))
                                        {{$getNumberOfRegisteredEnrolleesFemale}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($numberOfRegisteredEnrollees))
                                        {{$numberOfRegisteredEnrollees}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><em>Percentage of Enollment as of <strong>end of the month</strong></em></td>
                                    <td>
                                        @if(isset($totalPercentageMale))
                                        {{$totalPercentageMale}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($totalPercentageFemale))
                                        {{$totalPercentageFemale}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($totalPercentage))
                                        {{$totalPercentage}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><em>Average Daily Attendance</em></td>
                                    <td>
                                        @php
                                            $totalMaleDailyAtt = $maleDailyAttendance / count($days_num);
                                        @endphp
                                        {{number_format((float)$totalMaleDailyAtt, 2, '.','')}}
                                    </td>
                                    <td>
                                        @php
                                            $totalFemaleDailyAtt = $femaleDailyAttendance / count($days_num);
                                        @endphp
                                        {{number_format((float)$totalFemaleDailyAtt, 2, '.','')}}
                                    </td>
                                    <td>
                                        @if(isset($totalDailyAttendance))
                                        @php
                                            $totalDailyAtt = $totalDailyAttendance / count($days_num);
                                        @endphp
                                        @if(isset($totalDailyAtt))
                                        {{number_format((float)$totalDailyAtt, 2, '.','')}}
                                        @endif
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><em>Percentage of Attendance for the month</em></td>
                                    <td>
                                        @php
                                            if(number_format((float)$totalMaleDailyAtt, 2, '.','') == 0 || $totalPercentageMale == 0){
                                                $percentageOfMaleAtt = 0;
                                            }
                                            else{
                                                $percentageOfMaleAtt = (number_format((float)$totalMaleDailyAtt, 2, '.','')/$totalPercentageMale)*100;
                                            }
                                        @endphp
                                        {{$percentageOfMaleAtt}}
                                    </td>
                                    <td>
                                        @php
                                            if(number_format((float)$totalFemaleDailyAtt, 2, '.','') == 0 || $totalPercentageFemale == 0){
                                                $percentageOfFemaleAtt = 0;
                                            }
                                            else{
                                                $percentageOfFemaleAtt = (number_format((float)$totalFemaleDailyAtt, 2, '.','')/$totalPercentageFemale)*100;
                                            }
                                        @endphp
                                        {{$percentageOfFemaleAtt}}
                                    </td>
                                    <td>
                                        @if(isset($totalDailyAtt))
                                        @php
                                            if(number_format((float)$totalDailyAtt, 2, '.','') == 0 || $totalPercentage == 0){
                                                $totalPercentageAtt = 0;
                                            }
                                            else{
                                                $totalPercentageAtt = (number_format((float)$totalDailyAtt, 2, '.','')/$totalPercentage)*100;
                                            }
                                        @endphp
                                        {{$totalPercentageAtt}}
                                        @else
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><em>Number of students with 5 consecutive days of absences:</em></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><em><strong>Drop out</strong></em></td>
                                    <td>
                                        @if(isset($numberOfDroppedOutMale))
                                        {{$numberOfDroppedOutMale}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($numberOfDroppedOutFemale))
                                        {{$numberOfDroppedOutFemale}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($numberOfDroppedOut))
                                        {{$numberOfDroppedOut}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><em><strong>Tansferred out</strong></em></td>
                                    <td>
                                        @if(isset($numberOfTransferredOutMale))
                                        {{$numberOfTransferredOutMale}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($numberOfTransferredOutFemale))
                                        {{$numberOfTransferredOutFemale}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($numberOfTransferredOut))
                                        {{$numberOfTransferredOut}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><em><strong>Tansferred in</strong></em></td>
                                    <td>
                                        @if(isset($numberOfTransferredInMale))
                                        {{$numberOfTransferredInMale}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($numberOfTransferredInFemale))
                                        {{$numberOfTransferredInFemale}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($numberOfTransferredIn))
                                        {{$numberOfTransferredIn}}
                                        @endif
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
                                        {{$teachername[0]->firstname}} {{$teachername[0]->middlename[0]}}. {{$teachername[0]->lastname}} {{$teachername[0]->suffix}}
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
                                    
                                    <span>
                                        {{$principalname[0]->firstname}} {{$principalname[0]->middlename[0]}}. {{$principalname[0]->lastname}} {{$principalname[0]->suffix}}
                                    </span>
                                    
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

<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<script>
$(document).ready(function(){
    $("select[name=month_id]").on('change',function () {
        var month_id = $(this).val();
        console.log(month_id);
        console.log(month_id);
        // $('#saqdaqdas').val(month_id);
        $('form[name=formSubmit]').attr('action', '/schoolForm_2/show/'+month_id+'').submit();
    });
    $('#printBtn').on('click',function(){
        var month_id = $('select[name=m_id]').val();
        $('form[name=formSubmitPrint]').attr('action', '/schoolForm_2/preview/'+month_id+'').submit();
    })
});
</script>

@endsection

{{-- @foreach ($months as $month)
<option value="{{$month->month_id}} {{$month->month_id == $selected_month ? 'selected' : ''}}">{{$month->month}}</option>
@elseif ($month->month_id != $selected_month)
<option value="{{$month->month_id}}">{{$month->month}}</option>
@endif
@endforeach --}}