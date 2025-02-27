<style>
    #header, #header td{
        font-size: 13px;
        width: 100%;
        table-layout: fixed;
    }
    @page{
        size: 13in 8.5in;
        font-family: Arial, Helvetica, sans-serif;
    }
    #header th{
    }
    .contentTable{
        font-size: 12px;
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        font-family: Arial, Helvetica, sans-serif;
        margin-bottom: 5px !important;

    }
    .contentTable th{
        text-align: center;
        border:1px solid black !important;
        border-collapse: collapse;
    }
    .contentTable td{
        text-align: center;
        border:1px solid black !important;
        border-collapse: collapse;
    }
    h2{
        margin:5px;
    }
    div.box{
        border: 1px solid black;
        padding: 3px;
        text-align: center;
        margin-top: 3px;
        text-transform: uppercase;
    }
    .cellRight{
        text-align: right;
    }
    .rotate {
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        width: 1.5em;
        padding: 10px !important;
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
    .num{
        width:30px;
        border-right: 1px solid black;
        text-align: center;
        padding: 4px;
    }
    .small, small {
        font-size: 80%;
        font-weight: 400;
    }
    /* ol {
        display: block;
        list-style-type: decimal;
        margin-block-start: 1em;
        margin-block-end: 1em;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
        padding-inline-start: 40px;
    } */
    li {
        display: list-item;
        text-align: -webkit-match-parent;
    }
    .container {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr; /* fraction*/
    }
    .guidelines{
        /* border: 1px solid black; */
        /* text-align: */
        table-layout: fixed;
        vertical-align: top !important;
        /* font-size: 11px; */
        padding: 0px !important;
        page-break-inside: auto !important;
        font-family: Arial, Helvetica, sans-serif;
                   
    }
    .guidelines th{
        border: 1px solid black;
        /* text-align: */
        vertical-align: top left !important;
        /* font-size: 11px; */
        
    }
    .summary{
        width: 100%;
        border-collapse: collapse;
    }
    .summary th{
        font-size: 11px;
        text-align: center;
        vertical-align: middle !important;
    }
    .summary td{
        font-size: 12px;
        text-align: center;
        vertical-align: middle !important;
        border: 1px solid black;
        /* padding: 2px; */
    }
    .late{
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 20px 20px 0 0;
        border-color: #b5afaf transparent transparent transparent;
    }
    .cc{
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 0 20px 20px;
        border-color: transparent transparent #b5afaf transparent;
    }
    .contentTable thead{
        text-transform: uppercase;
    }
    
    #pSpan span         { display: block; visibility: hidden; }
    .crashedout{
        color: red;
        text-decoration: line-through;
    }
    table{
        border-collapse: collapse;
    }
</style>
@php
$col = count($currentdays);   

$countMale = 0;
$countMaleAbsent = 0;
$countMaleTardy = 0;
$countMalePresent = 0;

$averagedailyatt_male = 0;

foreach($currentdays as $totalatt)
{
    $presentmale = 0;
    $absentmale = 0;
    $tardymale = 0;

    $presentfemale = 0;
    $absentfemale = 0;
    $tardyfemale = 0;

    $present = 0;
    $absent = 0;
    $tardy = 0;


    foreach($attendance as $att)
    {

        if(strtolower($att->gender) == 'male')
        {
            $todayatt = collect($att->attendance)->where('day', $totalatt->daynum)->first();
            if($todayatt)
            {
                if($todayatt->status ==2)
                {
                    $presentmale+=1;
                    $present+=1;
                }
                elseif($todayatt->status == 1)
                {
                    $absentmale+=1;
                    $absent+=1;
                }elseif($todayatt->status == 3 || $todayatt->status == 4)
                {
                    $tardymale+=1;
                    $tardy+=1;
                    $presentmale+=1;
                    $present+=1;
                }
            }
        }
        if(strtolower($att->gender) == 'female')
        {
            $todayatt = collect($att->attendance)->where('day', $totalatt->daynum)->first();
            if($todayatt)
            {
                if($todayatt->status ==2)
                {
                    $presentfemale+=1;
                    $present+=1;
                }
                elseif($todayatt->status == 1)
                {
                    $absentfemale+=1;
                    $absent+=1;
                }elseif($todayatt->status == 3 || $todayatt->status == 4)
                {
                    $tardyfemale+=1;
                    $tardy+=1;
                    $presentfemale+=1;
                    $present+=1;
                }
            }
        }
    }
    $totalatt->presentmale = $presentmale;
    $totalatt->absentmale = $absentmale;
    $totalatt->tardymale = $tardymale;
    $totalatt->presentfemale = $presentfemale;
    $totalatt->absentfemale = $absentfemale;
    $totalatt->tardyfemale = $tardyfemale;
    $totalatt->present = $present;
    $totalatt->absent = $absent;
    $totalatt->tardy = $tardy;
}
$signatories = DB::table('signatory')
    ->where('form','form2')
    ->where('syid', $syid)
    ->where('deleted','0')
    ->where('acadprogid',$acadprogid)
    ->get();

    $signatory_name = '';
if(count($signatories) == 0)
{
    $signatories = DB::table('signatory')
        ->where('form','form2')
        ->where('syid', $syid)
        ->where('deleted','0')
        ->where('acadprogid',0)
        ->get();

    if(count($signatories) == 0)
    {
            
        $signatory_name = DB::table('schoolinfo')->first()->authorized;
    }else{
    $signatory_name = $signatories[0]->name;
    }

}
elseif(count($signatories) == 1)
{
    $signatory_name = $signatories[0]->name;
}else{
    $signatory_name = collect($signatories)->where('levelid',$forms[0]->levelid)->first()->name;
}
@endphp
<div style="page-break-inside: avoid;">
<table style="width: 100%; table-layout: fixed;">
    <tr style="line-height: 15px;">
        <td rowspan="3" style="width: 15%;">
            &nbsp;
            <img src='{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}' alt='school' width='80px'>
        </td>
        <th  style="font-size: 20px; text-align: center; font-weight: bold; width: 70%">School Form 2 (SF2) Daily Attendance Report of Learners
        </th>
        <td rowspan="3" style="width: 15%; text-align: right;">
            &nbsp;
            <img src='{{base_path()}}/public/assets/images/deped_logo.png' alt='school' width='80px'>
        </td>
    </tr>
    <tr>
        <th style="font-size: 12px; text-align: center; font-weight: bold;">
            <em><center>(This replaces Form 1, Form 2 & STS Form 4 - Absenteeism and Dropout Profile)</center></em>
        </th>
    </tr>
    <tr>
        <td colspan="10"></td>
    </tr>
</table>
<table style="width: 100%;">
    <tr style="">
        <td style="font-size: 12px; width: 15%; text-align: right;">School ID &nbsp;</td>
        <td style="font-size: 12px width: 10%; text-align: center; border: 1px solid black;">{{$schoolinfo->schoolid}}</td>
        <td style="font-size: 12px; width: 10%; text-align: right;">School Year &nbsp;</td>
        <td style="font-size: 12px width: 10%; text-align: center; border: 1px solid black;">{{$sydesc}}</td>
        <td style="font-size: 12px; width: 13%; text-align: right;">Report for the Month of &nbsp;</td>
        <td style="font-size: 12px width: 10%; text-align: center; border: 1px solid black;">{{$selectedmonth}}</td>
        <td style="font-size: 12px;width: 2%; text-align: right;"></td>
        <td colspan="2" style="font-size: 12px; width: 20%; text-align: right; border: 1px solid black;">Learner Attendance Conversion Tool &nbsp;</td>
        <td style="font-size: 12px width: 10%; text-align: center; border: 1px solid black;">LACT{{$selectedlact}}</td>
    </tr>
    <tr style="">
        <td style="font-size: 12px; text-align: right;">Name of School &nbsp;</td>
        <td style="font-size: 12px; text-align: center; border: 1px solid black;" colspan="3">{{$schoolinfo->schoolname}}</td>
        <td style="font-size: 12px; text-align: right;">Grade Level &nbsp;</td>
        <td style="font-size: 12px; text-align: center; border: 1px solid black;">{{$levelname}}</td>
        <td style="font-size: 12px; text-align: right;" colspan="2">Section &nbsp;</td>
        <td style="font-size: 12px; text-align: center; border: 1px solid black;" colspan="2">{{$sectionname}}</td>
    </tr>
</table>
<br/>

<table class="contentTable">
    <thead>
        <tr>
            <th rowspan="3" >NO</th>
            
            <th rowspan="3" style="width:25%">LEARNER'S NAME<br>(Last Name, First Name, Middle Name)</th>
            <th colspan="{{$col}}" style="width:43%">(1st row for date)</th>
            <th colspan="2" rowspan="2" style="width:10%">Total for the Month</th>
            <th rowspan="3" style="width:19%">REMARKS (If NLPA, state reason, please refer to legend number 2. If TRANSFERRED IN/OUT, write the name of School.)</th>
        </tr>
        <tr>
            @foreach ($currentdays as $numdays)
                <th>{{$numdays->daynum}}</th>
            @endforeach
        </tr>
        <tr>
            @foreach ($currentdays as $currentday)
                <th class='rotate'><div>{{$currentday->daystr}}</div></th>
            @endforeach
            <th>ABSENT</th>
            <th>TARDY</th>
        </tr>
    </thead>
    <tbody>
        @foreach($attendance as $student)

            @if(strtolower($student->gender) == "male")
                @php
                    $countMale+=1;
                    $absent = 0;
                    $tardy = 0;
                @endphp
                <tr>
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjhs')
                    <td>{{$countMale}}</td>
                    <td width="20%" style="text-align: left;">
                       &nbsp;{{ucwords(strtolower($student->lastname))}}, {{$student->firstname}} @if($student->middlename != null) {{ucwords(strtolower($student->middlename[0]))}}. @endif{{$student->suffix}}
                       
                    </td>
                    @else
                    <td @if($student->display == 0) style="color: red;" @endif>{{$countMale}}</td>
                    <td @if($student->display == 0) class="crashedout" @endif width="20%" style="text-align: left;">
                       &nbsp;{{ucwords(strtolower($student->lastname))}}, {{$student->firstname}} @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') {{ucwords(strtolower($student->middlename))}} @else @if($student->middlename != null) {{ucwords(strtolower($student->middlename[0]))}}. @endif @endif{{$student->suffix}}
                       
                       @if($student->display == 0)
                       @if($student->studstatus == 3 ) D/O @elseif($student->studstatus == 5) T/O @endif
                       @endif
                    </td>
                    @endif
                    @foreach ($student->attendance as $attday)
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                        @if($attday->status == null) 
                            <td>
                            </td>                                                  
                        @elseif($attday->status == 1)
                            <td>          
                                @php
                                    $absent+=1;
                                @endphp
                                
                            </td>                   
                        @elseif($attday->status == 2)
                            <td>
                                X
                            </td>                             
                        @elseif($attday->status == 3)  
                            <td>                
                                @php
                                    $tardy+=1;
                                @endphp      
                                <div class="late"></div>
                            </td>                          
                        @elseif($attday->status == 4)   
                            <td>                
                                @php
                                    $tardy+=1;
                                @endphp  
                                <div class="cc"></div>    
                            </td> 
                        @endif
                    @else
                        @if($attday->status == null) 
                            <td>
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
                            <td>                
                                @php
                                    $tardy+=1;
                                @endphp      
                                <div class="late"></div>
                            </td>                          
                        @elseif($attday->status == 4)   
                            <td>                
                                @php
                                    $tardy+=1;
                                @endphp  
                                <div class="cc"></div>    
                            </td> 
                        @endif
                    @endif
                    @endforeach
                    <td>{{$absent}}</td>
                    <td>{{$tardy}}</td>
                    <td>{{$student->remarks}}</td>
                </tr>
            @endif
        @endforeach
        <tr>
            <th colspan="2">
                <i class="fa fa-arrow-left pr-3"></i> MALE | TOTAL Per Day <i class="fa fa-arrow-right pl-3"></i>
            </th>
            @php
                $maleDailyAttendance = 0;   
            @endphp
            @foreach ($currentdays as $maletotal)
                <td>{{$maletotal->presentmale}}</td>
            @endforeach
            <td>
                {{collect($currentdays)->sum('absentmale')}}
            </td>
            <td>
                {{collect($currentdays)->sum('tardymale')}}
            </td>
            <td></td>
        </tr>
        @php
            $countFemale = 0;
            $countFemaleAbsent = 0;
            $countFemaleTardy = 0;
            $countFemalePresent = 0;

            $averagedailyatt_female = 0;
            $totalfemaleabsent = 0;
            $totalfemaletardy = 0;
        @endphp
        @foreach($attendance as $student)

            @if(strtolower($student->gender) == "female" )
                @php
                    $countFemale+=1;
                    $absent = 0;
                    $tardy = 0;
                @endphp
                <tr>
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjhs')
                    <td>{{$countFemale}}</td>
                    <td width="20%" style="text-align: left;">
                       &nbsp;{{ucwords(strtolower($student->lastname))}}, {{$student->firstname}} @if($student->middlename != null) {{ucwords(strtolower($student->middlename[0]))}}. @endif{{$student->suffix}}
                       
                    </td>
                    @else
                    <td @if($student->display == 0) style="color: red;" @endif>{{$countFemale}}</td>
                    <td @if($student->display == 0) class="crashedout" @endif width="20%" style="text-align: left;">
                        &nbsp;{{ucwords(strtolower($student->lastname))}}, {{$student->firstname}} @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') {{ucwords(strtolower($student->middlename))}} @else @if($student->middlename != null) {{ucwords(strtolower($student->middlename[0]))}}. @endif @endif{{$student->suffix}}
                       
                       @if($student->display == 0)
                       @if($student->studstatus == 3 ) D/O @elseif($student->studstatus == 5) T/O @endif
                       @endif
                    </td>
                    @endif
                    @foreach ($student->attendance as $attday)
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                        @if($attday->status == null) 
                            <td>
                            </td>                                                  
                        @elseif($attday->status == 1)
                            <td>          
                                @php
                                    $absent+=1;
                                @endphp
                            </td>                   
                        @elseif($attday->status == 2)
                            <td>
                                X
                            </td>                             
                        @elseif($attday->status == 3)  
                            <td>                
                                @php
                                    $tardy+=1;
                                @endphp      
                                <div class="late"></div>
                            </td>                          
                        @elseif($attday->status == 4)   
                            <td>                
                                @php
                                    $tardy+=1;
                                @endphp  
                                <div class="cc"></div>    
                            </td> 
                        @endif
                    @else
                        @if($attday->status == null) 
                            <td>
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
                            <td>                
                                @php
                                    $tardy+=1;
                                @endphp      
                                <div class="late"></div>
                            </td>                          
                        @elseif($attday->status == 4)   
                            <td>                
                                @php
                                    $tardy+=1;
                                @endphp  
                                <div class="cc"></div>    
                            </td> 
                        @endif
                    @endif
                    @endforeach
                    <td>{{$absent}}</td>
                    <td>{{$tardy}}</td>
                    <td>{{$student->remarks}}</td>
                </tr>
                @php    
                    $totalfemaleabsent += $absent;
                    $totalfemaletardy += $tardy;
                @endphp
            @endif
        @endforeach
        <tr>
            
            <th colspan="2">
                <i class="fa fa-arrow-left pr-3"></i> FEMALE | TOTAL Per Day <i class="fa fa-arrow-right pl-3"></i>
            </th>
            @php
                $femaleDailyAttendance = 0;   
            @endphp
            @foreach ($currentdays as $femaletotal)
                <td>{{$femaletotal->presentfemale}}</td>
            @endforeach
            <td>
                {{collect($currentdays)->sum('absentfemale')}}
            </td>
            <td>
                {{collect($currentdays)->sum('tardyfemale')}}
            </td>
            <td></td>
        </tr>
        <tr>
            <th colspan="2">
                <i class="fa fa-arrow-left pr-3"></i> COMBINED TOTAL PER DAY <i class="fa fa-arrow-right pl-3"></i>
            </th>
            @php
                $totalDailyAttendance = 0;
            @endphp
            @foreach ($currentdays as $daystotal)
                <td>{{$daystotal->present}}</td>
            @endforeach
            <td>
                {{collect($currentdays)->sum('absent')}}
            </td>
            <td>
                {{collect($currentdays)->sum('tardy')}}
            </td>
            <td></td>
        </tr>
    </tbody>
</table>

{{-- <p id="pSpan"><span>hello</span><span>How are you</span></p> --}}
<table class="guidelines" style="width:100%;margin-top: 2px !important;border-spacing:0;">
    <tr>
        <td style="width:25%;" valign="top">
            <div style="padding:0px;">
                        <small><strong>GUIDELINES:</strong></small>
                        {{-- <p> --}}
                            <ol style="font-size: 11px; display: block;padding-left: 15px;">
                                <li style="display: list-item;list-style: decimal; list-style-position: inside;"> The attendance shall be accomplished daily. Refer to the codes for checking learners' attendance.</li>
                                <li style="display: list-item;list-style: decimal; list-style-position: inside;"> Dates shall be written in the preceding columns beside Learner's Name.</li>
                                <li>
                                    To compute the following:
                                    <br>
                                    <table style="width:100%;page-break-inside: avoid;table-layout: fixed;">
                                        <tr>
                                            <td style="width:45%">
                                                <div>
                                                    a. Percentage of Enrolment = 
                                                </div>
                                            </td>
                                            <td style="text-align:center;width:45%">
                                                <div class="row" style="border-bottom: 1px solid black;width:100%;">Registered Learner as of End of the Month</div> 
                                                <div class="row">Enrolment as of 1st Friday of the schoolyear</div>
                                            </td>
                                            <td>
                                                <span>x100</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div>
                                                    b. Average Daily Attendance = 
                                                </div>
                                            </td>
                                            <td style="text-align:center;">
                                                <div class="row" style="border-bottom: 1px solid black;width:100%;">Total Daily Attendance</div> 
                                                <div class="row">Number of School Days in Reporting Month</div>
                                            </td>
                                            <td>
                                                &nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div>
                                                    c. Pecentage of Attendance for the month =
                                                </div>
                                            </td>
                                            <td style="text-align:center;">
                                                <div class="row" style="border-bottom: 1px solid black;width:100%;">Average Daily Attendance</div> 
                                                <div class="row">Registered Learner as of End of the Month</div>
                                            </td>
                                            <td>
                                                <span>x100</span>
                                            </td>
                                        </tr>
                                    </table>
                                </li>
                                <br>
                                <li> Every End of the month, the class adviser will submit this form to the office of the principal fo recording of summary table into the School Form 4. Once signed by the principal, this form should be returned to the adviser.</li>
                                <li> The adviser will extend neccessary intervention including but not limited to home visitation to learner/s that committed 5 consecutive days of absences or those with potentials of dropping out.</li>
                                <li> Attendance performance of learner is expected to reflect in Form 137 and Form 138 every grading period<br> * Beginning of School Year cut-off report is every 1st Friday of School Calendar Days</li>
                            </ol>
                        {{-- </p> --}}
                    </div>
        </td>
        <td style="width: 10%;border:1px solid black;" valign="top">
                <div style="padding:0px;font-size:8px;">
                        <div style="border-bottom: 1px solid black;padding:2px; ">
                            <small style="font-size: 11px;">
                                <strong>1. CODES FOR CHECKING ATTENDANCE</strong>
                            </small>
                        </div>
                        <div style="font-size: 11px;padding:3px;">
                                <strong>blank</strong> - Present; (x)- Absent; Tardy (half shaded = Upper for Late Comer, Lower for Cutting Classes)
                        </div>
                        <div style="padding:2px;">
                            <span>
                                <small style="font-size: 11px;">
                                    <strong>2. REASONS/CAUSES OF DROP-OUTS</strong>
                                </small>
                            </span>
                            <br>
                            <div style="padding-bottom:3px;padding:2px;">
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
                            <div style="padding-bottom:3px;padding:2px;">
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
                            <div style="padding-bottom:3px;padding:2px;">
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
                            <div style="padding-bottom:3px;padding:2px;">
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
                            <div style="padding-bottom:3px;padding:2px;">
                                <span style="font-size: 11px;">
                                    <strong>e. Financial-Related</strong>
                                </span>
                                <br>
                                <span style="font-size: 11px;">
                                    e.1. Child labor, work
                                </span>
                            </div>
                            <div style="padding-bottom:3px;padding:2px;">
                                <span style="font-size: 11px;">
                                    <strong>f. Others</strong>
                                </span>
                            </div>
                        </div>
                    </div>                           
        </td>
        <td style="width:20%;padding-left:10px;" valign="top">
            <table class="summary" style="font-size:8px;table-layout:fixed;">
                <tr>
                    <th rowspan="2" >Month: 
                        <span class="month">
                            {{$selectedmonth}}
                        </span>
                    </th>
                    <th rowspan="2" >No. of Days of Classes:
                        
                        {{count($currentdays)}}
                    </th>
                    <th colspan="3" style="width:50%;">Summary for the Month</th>
                </tr>
                <tr>
                    <th>M</th>
                    <th>F</th>
                    <th>TOTAL</th>
                </tr>
                <tr>
                    <td colspan="2"><em>* Enrolment as of (1st Friday of {{$enrollmentmonth}})</em></td>
                    <td>
                        {{$enrollmentasof_male}}
                    </td>
                    <td>
                        {{$enrollmentasof_female}}
                    </td>
                    <td>
                        {{$enrollmentasof_total}}
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><em>Late Enrollment <strong>during the month</strong><br>(beyond cut-off)</em></td>
                    <td>
                        {{$lateenrolled_male}}
                    </td>
                    <td>
                        {{$lateenrolled_female}}
                    </td>
                    <td>
                        {{$lateenrolled_total}}
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><em>Registered Learner as of <strong>end of the month</strong></em></td>
                    <td>
                        {{$registered_male}}
                    </td>
                    <td>
                        {{$registered_female}}
                    </td>
                    <td>
                        {{$registered_total}}
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><em>Percentage of Enrollment as of <strong>end of the month</strong></em></td>
                    <td>
                        {{$enrollmentpercentage_male}}
                    </td>
                    <td>
                        {{$enrollmentpercentage_female}}
                        
                    </td>
                    <td>
                        {{$enrollmentpercentage_total}}
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><em>Average Daily Attendance</em></td>
                    <td id="ada_male">
                        @php
                            if(count($currentdays) == 0)
                            {
                                $avedailyatt_male = 0;
                            }else{
                                $avedailyatt_male = number_format((collect($studentstotalperday)->sum('withrecordsmale')/count($currentdays)),2);
                            }
                        @endphp
                        {{ round($avedailyatt_male,2)}}
                    </td>
                    <td id="ada_female">
                        @php
                        if(count($currentdays) == 0)
                        {
                            $avedailyatt_female = 0;
                        }else{
                            $avedailyatt_female =  number_format((collect($studentstotalperday)->sum('withrecordsfemale')/count($currentdays)),2);
                        }
                        @endphp
                        {{round($avedailyatt_female,2)}}
                    </td>
                    <td  id="ada_total">
                        {{round($avedailyatt_male,2)+round($avedailyatt_female,2)}}
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><em>Percentage of Attendance for the month</em></td>
                    <td>
                        @php
                            if($avedailyatt_male>0 || $registered_male>0)
                            {
                                if(number_format(($avedailyatt_male/$registered_male)*100,1)>100)
                                {
                                    $pammale = 100;
                                }
                                else{
                                    $pammale = number_format(($avedailyatt_male/$registered_male)*100,1);
                                }
                            }else{
                                    $pammale = 0;
                            }
                        @endphp
                        @if($avedailyatt_male > 0 && $registered_male > 0)
                            {{$pammale}}
                        @else
                        0
                        @endif
                    </td>
                    <td>
                        @php
                            if($avedailyatt_female>0 || $registered_female>0)
                            {
                                if(number_format(($avedailyatt_female/$registered_female)*100,1)>100)
                                {
                                    $pamfemale = 100;
                                }
                                else{
                                    $pamfemale = number_format(($avedailyatt_female/$registered_female)*100,1);
                                }
                            }else{
                                    $pamfemale = 0;
                            }
                        @endphp
                        @if($avedailyatt_female > 0 && $registered_female > 0)
                            {{$pamfemale}}
                        @else
                        0
                        @endif
                    </td>
                    <td>
                        {{($pammale+$pamfemale)/2}}
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><em>Number of students with 5 consecutive days of absences:</em></td>
                    <td>{{$countconsecutive_male}}</td>
                    <td>{{$countconsecutive_female}}</td>
                    <td>{{$countconsecutive_total}}</td>
                </tr>
                <tr>
                    <td colspan="2"><em><strong>NLPA</strong></em></td>
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
            <br>
            <div style="font-size: 11px;">
                <span>
                    <em>I certify that this is a true and correct report:</em>
                </span>
            </div>
            <div style="font-size: 11px; text-align:center;padding-top: 2px;">
                <center>
                    <span>
                        
                    {{$teachername->firstname}} {{$teachername->middlename[0]}}. {{$teachername->lastname}} {{$teachername->suffix}}

                    </span>
                    <hr class="col-md-8 p-0 m-0" style="border-color: black;"/>
                    <em class="p-0">(Signature of Teacher over Printed Name)</em>
                </center>
            </div>
            <br>
            <div style="font-size: 11px;">
                <span>
                    <em>Attested by:</em>
                </span>
            </div>
            <div style="font-size: 11px; text-align:center;">
                <center>
                    <span>
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndm')
                        SR ELVIE GUTIERREZ BORLADO PM PHD
                        @else
                        {{$signatory_name}}
                        @endif
                    </span>
                    <hr class="col-md-8 p-0 m-0" style="border-color: black;"/>
                    <em class="p-0">(Signature of School Head over Printed Name)</em>
                </center>
            </div>
        </td>
    </tr>
</table>
</div>