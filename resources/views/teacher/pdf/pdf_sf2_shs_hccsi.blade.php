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
        /* vertical-align: top !important; */
        /* font-size: 11px; */
        padding: 0px !important;
        page-break-inside: avoid !important;
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
                if($todayatt->combinedstatus === 1)
                {
                    $presentmale+=1;
                    $present+=1;
                }
                elseif($todayatt->combinedstatus === 'presentam')
                {
                    // if( strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                    // {
                    $presentmale+=0.5;
                    $present+=0.5;
                    $absentmale+=0.5;
                    $absent+=0.5;
                    // }else{
                    // $presentmale+=1;
                    // $present+=1;
                    // }
                }
                elseif($todayatt->combinedstatus === 'presentpm')
                {
                    // if( strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                    // {
                    $presentmale+=0.5;
                    $present+=0.5;
                    $absentmale+=0.5;
                    $absent+=0.5;
                    // }else{
                    // $presentmale+=1;
                    // $present+=1;
                    // }
                }
                elseif($todayatt->combinedstatus === 0)
                {
                    $absentmale+=1;
                    $absent+=1;
                }
                elseif($todayatt->combinedstatus === 'absentam')
                {
                    $absentmale+=0.5;
                    $presentmale+=0.5;
                    $present+=0.5;
                    $absent+=0.5;
                }
                elseif($todayatt->combinedstatus === 'absentpm')
                {
                    $absentmale+=0.5;
                    $presentmale+=0.5;
                    $present+=0.5;
                    $absent+=0.5;
                }elseif($todayatt->combinedstatus === 2 || $todayatt->combinedstatus === 3)
                {
                    $tardymale+=1;
                    $tardy+=1;
                    $presentmale+=1;
                    $present+=1;
                }
                elseif($todayatt->combinedstatus === 'lateam' || $todayatt->combinedstatus === 'latepm' || $todayatt->combinedstatus === 'ccam' || $todayatt->combinedstatus === 'ccpm')
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
                if($todayatt->combinedstatus === 1)
                {
                    $presentfemale+=1;
                    $present+=1;
                }
                elseif($todayatt->combinedstatus === 'presentam')
                {
                    // if( strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                    // {
                    $presentfemale+=0.5;
                    $present+=0.5;
                    $absentfemale+=0.5;
                    $absent+=0.5;
                    // }else{
                    // $presentfemale+=1;
                    // $present+=1;
                    // }
                }
                elseif($todayatt->combinedstatus === 'presentpm')
                {
                    // if( strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
                    // {
                    $presentfemale+=0.5;
                    $present+=0.5;
                    $absentfemale+=0.5;
                    $absent+=0.5;
                    // }else{
                    // $presentfemale+=1;
                    // $present+=1;
                    // }
                }
                elseif($todayatt->combinedstatus === 0)
                {
                    $absentfemale+=1;
                    $absent+=1;
                }
                elseif($todayatt->combinedstatus === 'absentam')
                {
                    $absentfemale+=0.5;
                    $absent+=0.5;
                    $presentfemale+=0.5;
                    $present+=0.5;
                }
                elseif($todayatt->combinedstatus === 'absentpm')
                {
                    $absentfemale+=0.5;
                    $absent+=0.5;
                    $presentfemale+=0.5;
                    $present+=0.5;
                }elseif($todayatt->combinedstatus === 2 || $todayatt->combinedstatus === 3)
                {
                    $tardyfemale+=1;
                    $tardy+=1;
                    $presentfemale+=1;
                    $present+=1;
                }
                elseif($todayatt->combinedstatus === 'lateam' || $todayatt->combinedstatus === 'latepm' || $todayatt->combinedstatus === 'ccam' || $todayatt->combinedstatus === 'ccpm')
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

    $absentMale = 0;
    $absentFemale = 0;
    $presentMale = 0;
    $presentFemale = 0;
    $totalmaleattcount = 0;
    $totalfemaleattcount = 0;

$signatories = DB::table('signatory')
    ->where('form','form2')
    ->where('syid', $syid)
    ->where('deleted','0')
    ->whereIn('acadprogid',[$acadprogid,0])
    ->get();

    $signatory_name = '';
    $headerdesc = '';
    $bottomdesc = '';


if(count($signatories) == 0)
{
    $principalname = DB::table('academicprogram')
        ->select('teacher.*')
        ->where('academicprogram.id', $acadprogid)
        ->join('teacher','academicprogram.principalid','=','teacher.id')
        ->first();

    if($principalname)
    {
    $signatory_name = $principalname->firstname.' '.($principalname->middlename ? $principalname->middlename[0].'. ' : '').$principalname->lastname.' '.$principalname->suffix;
    }else{
    $signatory_name = DB::table('schoolinfo')->first()->authorized;
    }
}else{
    if(collect($signatories)->where('acadprogid', $acadprogid)->count()>0)
    {
        $signatories = collect($signatories)->where('acadprogid', $acadprogid)->values();
    }
    
    $signatory_name = $signatories[0]->name;
    $headerdesc = $signatories[0]->title;
    $bottomdesc = $signatories[0]->description;
}
if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
{
    $attendance = collect($attendance)->where('display','1')->values();
}

/////// Word Detection

    function capitalize($word)
    {
        return \App\Http\Controllers\SchoolFormsController::capitalize_word($word);
    }
@endphp
<div style="page-break-inside: avoid;">
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td colspan="2">
            &nbsp;
            <img src='{{base_path()}}/public/assets/images/department_of_Education.png' alt='school' width='70px'>
            {{-- <img src='{{base_path()}}/public/assets/images/deped_logo.png' alt='school' width='65px'> --}}
            {{-- <img src='{{base_path()}}/public/{{$schoolinfo->picurl}}' alt='school' width='65px'> --}}
        </td>
        <th colspan="13" style="font-size: 20px; text-align: center; font-weight: bold;">School Form 2 Daily Attendance Report of Learners For Senior High School (SF2-SHS)
        </th>
        <td colspan="2">
            &nbsp;
            <img src='{{base_path()}}/public/assets/images/deped_logo.png' alt='school' width='90px'>
        </td>
    </tr>
</table>
<table style="width: 100%; font-size: 12px;">
    <tr>
        <td style="text-align: right;">School Name &nbsp;</td>
        <td colspan="2" style="border: 1px solid black; text-align: center;">{{$schoolinfo->schoolname}}</td>
        <td colspan="2" style="text-align: right;">School ID &nbsp;</td>
        <td colspan="2" style="border: 1px solid black; text-align: center;">{{$schoolinfo->schoolid}}</td>
        <td colspan="2" style="text-align: right;">District &nbsp;</td>
        <td colspan="2" style="border: 1px solid black; text-align: center;">{{$schoolinfo->district}}</td>
        <td colspan="2" style="text-align: right;">Division &nbsp;</td>
        <td colspan="2" style="border: 1px solid black; text-align: center;">@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndm')NORTH COTABATO @else{{$schoolinfo->division}}@endif</td>
        <td style="text-align: right;">Region &nbsp;</td>
        <td style="border: 1px solid black; text-align: center;">{{$schoolinfo->region}}</td>
    </tr>
    <tr>
        <td colspan="17" style="line-height: 5px;">&nbsp;</td>
    </tr>
    <tr>
        <td style="text-align: right;">Semester &nbsp;</td>
        <td style="border: 1px solid black; text-align: center;">@if($semester == 1) 1st @else 2nd @endif</td>
        <td colspan="2" style="text-align: right;">School Year &nbsp;</td>
        <td colspan="2" style="border: 1px solid black; text-align: center;">{{$sydesc}}</td>
        <td colspan="2" style="text-align: right;">Grade Level &nbsp;</td>
        <td colspan="2" style="border: 1px solid black; text-align: center;">{{$levelname}}</td>
        <td colspan="2" style="text-align: right;">Track and Strand &nbsp;</td>
        <td colspan="5" style="border: 1px solid black; text-align: center;">{{$strandinfo->trackname}} - {{$strandinfo->strandcode}}</td>
    </tr>
    <tr>
        <td colspan="17" style="line-height: 5px;">&nbsp;</td>
    </tr>
    <tr>
        <td style="text-align: right;">Section &nbsp;</td>
        <td colspan="2" style="border: 1px solid black; text-align: center;">{{$sectionname}}</td>
        <td colspan="3" style="text-align: right;">{{-- Course (for TVL only) &nbsp; --}}</td>
        <td colspan="5" style="{{-- border: 1px solid black;--}} text-align: center;"> </td>
        <td colspan="3" style="text-align: right;">Month of &nbsp;</td>
        <td colspan="3" style="border: 1px solid black; text-align: center;">{{$selectedmonth}}</td>
    </tr>
    <tr>
        <td colspan="17" style="line-height: 5px;">&nbsp;</td>
    </tr>
    <tr style="font-weight: bold; text-align: center;">
        <td colspan="11"></td>
        <td colspan="5" style="border: 1px solid black;">Learner Attendance Conversion Tool</td>
        <td style="border: 1px solid black;">LACT{{$selectedlact}}</td>
    </tr>
    {{-- <tr style="">
        <td style="font-size: 12px; width: 15%; text-align: right;">School ID &nbsp;</td>
        <td style="font-size: 12px width: 10%; text-align: center; border: 1px solid black;">{{$schoolinfo->schoolid}}</td>
        <td style="font-size: 12px; width: 10%; text-align: right;">School Year &nbsp;</td>
        <td style="font-size: 12px width: 10%; text-align: center; border: 1px solid black;">{{$sydesc}}</td>
        <td style="font-size: 12px; width: 13%; text-align: right;">Report for the Month of &nbsp;</td>
        <td style="font-size: 12px width: 10%; text-align: center; border: 1px solid black;">{{$selectedmonth}}</td>
        <td style="font-size: 12px;width: 2%; text-align: right;"></td>
        <td colspan="2" style="font-size: 12px; width: 20%; text-align: right; border: 1px solid black;">Learner Attendance Conversion Tool &nbsp;</td>
        <td style="font-size: 12px width: 10%; text-align: center; border: 1px solid black;">LACT1</td>
    </tr>
    <tr style="">
        <td style="font-size: 12px; text-align: right;">Name of School &nbsp;</td>
        <td style="font-size: 12px; text-align: center; border: 1px solid black;" colspan="3">{{$schoolinfo->schoolname}}</td>
        <td style="font-size: 12px; text-align: right;">Grade Level &nbsp;</td>
        <td style="font-size: 12px; text-align: center; border: 1px solid black;">{{$levelname}}</td>
        <td style="font-size: 12px; text-align: right;" colspan="2">Section &nbsp;</td>
        <td style="font-size: 12px; text-align: center; border: 1px solid black;" colspan="2">{{$sectionname}}</td>
    </tr> --}}
</table>

<table class="contentTable">
    <thead>
        <tr>
            <th rowspan="3" >#</th>
            <!--<th rowspan="3" style="width:8%">LRN</th>-->
            <th rowspan="3" style="width:18%">LEARNER'S NAME<br>(Last Name, First Name, Middle Name)</th>
            <th colspan="{{$col}}" style="width:50%">(1st row for date)</th>
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
                    <td @if($student->display == 0) style="color: red;" @endif>{{$countMale}}</td>
                    <!--<td @if($student->display == 0) class="crashedout" @endif>{{$student->lrn}}</td>-->
                    <td @if($student->display == 0) class="crashedout" @endif width="18%" style="text-align: left;">
                       &nbsp;{{capitalize($student->lastname)}}, {{capitalize($student->firstname)}} {{$student->suffix}} @if($student->middlename != null) {{capitalize($student->middlename[0])}}. @endif
                       
                       @if($student->display == 0)
                       @if($student->studstatus == 3 ) D/O @elseif($student->studstatus == 5) T/O @endif
                       @endif
                    </td>
                    @foreach ($student->attendance as $attday)
                        @if($attday->combinedstatus === null)
                        <td>          
                            @php
                                $totalmaleattcount+=1;
                            @endphp            
                        </td> 
                        @elseif($attday->combinedstatus === 0)
                        <td>          
                            @php
                                $absent+=1;
                                $absentMale+=1;
                            @endphp
                            X
                        </td> 
                        @elseif($attday->status === 1)
                        <td>
                            @php
                                $presentMale+=1;
                                $totalmaleattcount+=1;
                            @endphp     
                        </td>                   
                        @elseif($attday->combinedstatus === 2)
                        <td>                
                            @php
                                $tardy+=1;
                                $presentMale+=1;
                            $totalmaleattcount+=1;
                            @endphp      
                            <div class="late"></div>
                        </td>    
                        @elseif($attday->combinedstatus === 3)
                            <td>                
                                @php
                                    $tardy+=1;
                                    $presentMale+=1;
                                $totalmaleattcount+=1;
                                @endphp  
                                <div class="cc"></div>    
                            </td> 
                        @elseif($attday->combinedstatus === 'presentam')
                        <td>          
                            @php
                            $absent+=0.5;
                            $absentFemale+=0.5;
                            $presentFemale+=0.5;
                            $totalfemaleattcount+=0.5;
                            //     $presentMale+=1;
                            // $totalmaleattcount+=1;
                            
                            @endphp
                            /
                        </td>       
                        @elseif($attday->combinedstatus === 'presentpm')
                        <td>          
                            @php
                            $absent+=0.5;
                            $absentFemale+=0.5;
                            $presentFemale+=0.5;
                        $totalfemaleattcount+=0.5;
                            //     $presentMale+=1;
                            // $totalmaleattcount+=1;
                            @endphp
                            \
                        </td>       
                        @elseif($attday->combinedstatus === 'absentam')
                            <td>          
                                @php
                                //$tardy+=1;
                                    $absent+=0.5;
                                    $absentMale+=0.5;
                                    $presentMale+=0.5;
                                $totalmaleattcount+=0.5;
                                @endphp
                                \
                            </td>         
                        @elseif($attday->combinedstatus ==='absentpm')
                            <td>          
                                @php
                                //$tardy+=1;
                                    $absent+=0.5;
                                    $absentMale+=0.5;
                                    $presentMale+=0.5;
                                $totalmaleattcount+=0.5;
                                @endphp
                                /
                            </td>            
                        @elseif($attday->combinedstatus === 'lateam')
                            <td>                
                                @php
                                    $tardy+=1;
                                    $presentMale+=1;
                                $totalmaleattcount+=1;
                                @endphp  
                                <div class="late"></div>    
                            </td> 
                        @elseif($attday->combinedstatus === 'latepm')
                            <td>                
                                @php
                                    $tardy+=1;
                                    $presentMale+=1;
                                $totalmaleattcount+=1;
                                @endphp  
                                <div class="cc"></div>    
                            </td> 
                        @elseif($attday->combinedstatus === 'ccam')
                            <td>                
                                @php
                                    $tardy+=1;
                                    $presentMale+=1;
                                $totalmaleattcount+=1;
                                @endphp  
                                <div class="cc"></div>    
                            </td> 
                        @elseif($attday->combinedstatus === 'ccpm')
                            <td>                
                                @php
                                    $tardy+=1;
                                    $presentMale+=1;
                                $totalmaleattcount+=1;
                                @endphp  
                                <div class="cc"></div>    
                            </td> 
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
                {{$absentMale}}
            </td>
            <td>
                {{collect($currentdays)->sum('tardymale')}}
            </td>
            <td>{{$presentMale}}</td>
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
                    <td @if($student->display == 0) style="color: red;" @endif>{{$countFemale}}</td>
                    <!--<td @if($student->display == 0) class="crashedout" @endif>{{$student->lrn}}</td>-->
                    <td @if($student->display == 0) class="crashedout" @endif width="18%" style="text-align: left;">
                       &nbsp;{{ucwords(strtolower($student->lastname))}}, {{ucwords(strtolower($student->firstname))}} {{$student->suffix}} @if($student->middlename != null) {{ucwords(strtolower($student->middlename[0]))}}. @endif
                       @if($student->display == 0)
                       @if($student->studstatus == 3 ) D/O @elseif($student->studstatus == 5) T/O @endif
                       @endif
                    </td>
                    @foreach ($student->attendance as $attday)
                        @if($attday->combinedstatus === null)
                        <td>          
                            @php
                                $totalfemaleattcount+=1;
                            @endphp            
                        </td> 
                        @elseif($attday->combinedstatus === 0)
                        <td>          
                            @php
                                $absent+=1;
                                $absentFemale+=1;
                            @endphp
                            X
                        </td> 
                        @elseif($attday->combinedstatus === 1)
                        <td>
                            @php
                                $presentFemale+=1;
                                $totalfemaleattcount+=1;
                            @endphp     
                        </td>                   
                        @elseif($attday->combinedstatus === 2)
                        <td>                
                            @php
                                $tardy+=1;
                                $presentFemale+=1;
                            $totalfemaleattcount+=1;
                            @endphp      
                            <div class="late"></div>
                        </td>    
                        @elseif($attday->combinedstatus === 3)
                            <td>                
                                @php
                                    $tardy+=1;
                                    $presentFemale+=1;
                                $totalfemaleattcount+=1;
                                @endphp  
                                <div class="cc"></div>    
                            </td> 
                        @elseif($attday->combinedstatus === 'presentam')
                        <td>          
                            @php
                            $absent+=0.5;
                            $absentFemale+=0.5;
                            $presentFemale+=0.5;
                        $totalfemaleattcount+=0.5;
                            //     $presentFemale+=1;
                            // $totalfemaleattcount+=1;
                            @endphp
                            /
                        </td>       
                        @elseif($attday->combinedstatus === 'presentpm')
                        <td>          
                            @php
                            $absent+=0.5;
                            $absentFemale+=0.5;
                            $presentFemale+=0.5;
                        $totalfemaleattcount+=0.5;
                            //     $presentFemale+=1;
                            // $totalfemaleattcount+=1;
                            @endphp
                            \
                        </td>       
                        @elseif($attday->combinedstatus === 'absentam')
                            <td>          
                                @php
                                //$tardy+=1;
                                    $absent+=0.5;
                                    $absentFemale+=0.5;
                                    $presentFemale+=0.5;
                                $totalfemaleattcount+=0.5;
                                @endphp
                                \
                            </td>         
                        @elseif($attday->combinedstatus ==='absentpm')
                            <td>          
                                @php
                                //$tardy+=1;
                                    $absent+=0.5;
                                    $absentFemale+=0.5;
                                    $presentFemale+=0.5;
                                $totalmaleattcount+=0.5;
                                @endphp
                                /
                            </td>            
                        @elseif($attday->combinedstatus === 'lateam')
                            <td>                
                                @php
                                    $tardy+=1;
                                    $presentFemale+=1;
                                $totalfemaleattcount+=1;
                                @endphp  
                                <div class="late"></div>    
                            </td> 
                        @elseif($attday->combinedstatus === 'latepm')
                            <td>                
                                @php
                                    $tardy+=1;
                                    $presentFemale+=1;
                                $totalfemaleattcount+=1;
                                @endphp  
                                <div class="cc"></div>    
                            </td> 
                        @elseif($attday->combinedstatus === 'ccam')
                            <td>                
                                @php
                                    $tardy+=1;
                                    $presentFemale+=1;
                                $totalfemaleattcount+=1;
                                @endphp  
                                <div class="cc"></div>    
                            </td> 
                        @elseif($attday->combinedstatus === 'ccpm')
                            <td>                
                                @php
                                    $tardy+=1;
                                    $presentFemale+=1;
                                $totalfemaleattcount+=1;
                                @endphp  
                                <div class="cc"></div>    
                            </td> 
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
                {{$absentFemale}}
            </td>
            <td>
                {{collect($currentdays)->sum('tardyfemale')}}
            </td>
            <td>{{$presentFemale}}</td>
        </tr>
        <tr>
            <th colspan="2">
                <i class="fa fa-arrow-left pr-3"></i> COMBINED TOTAL PER DAY <i class="fa fa-arrow-right pl-3"></i>
            </th>
            @php
                $totalDailyAttendance = 0;
            @endphp
            @foreach ($currentdays as $alltotal)
                <td>{{$alltotal->presentmale + $alltotal->presentfemale}}</td>
            @endforeach
            {{-- @foreach($studentstotalperday as $atttotal)
                <td>{{$atttotal->withrecordsmale+$atttotal->withrecordsfemale}}</td>
            @endforeach --}}
            <td>
                {{$absentMale+$absentFemale}}
            </td>
            <td>
                {{collect($currentdays)->sum('tardy')}}
            </td>
            <td>{{$presentFemale+$presentMale}}</td>
        </tr>
    </tbody>
</table>

{{-- <p id="pSpan"><span>hello</span><span>How are you</span></p> --}}
<table style="width:100%;margin-top: 20px !important;border-spacing:0; page-break-inside: avoid !important;">
    <tr nobr="true">
        <td style="width:40%; page-break-inside: avoid !important;" valign="top">
            <table style="width: 100%;page-break-inside: avoid !important; font-size: 11px;">
                <tr>
                    <td style="font-weight: bold; font-size: 14px;">GUIDELINES</td>
                </tr>
                <tr>
                    <td>
                        1. The attendance shall be accomplished daily. Refer to the codes for checking learners' attendance
                    </td>
                </tr>
                <tr>
                    <td>
                        2Dates shall be written in the preceding columns beside Learner's Name.
                    </td>
                </tr>
                <tr>
                    <td>
                        3. To compute the following: 
                        <br>
                        <table style="width:100%;page-break-inside: avoid;table-layout: fixed; margin-left: 10px;">
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
                    </td>
                </tr>
                <tr>
                    <td>4. Every End of the month, the class adviser will submit this form to the office of the principal fo recording
                        of summary table into the School Form 4. Once signed by the principal, this form should be returned to
                        the adviser.</td>
                </tr>
                <tr>
                    <td> 5. The adviser will extend neccessary intervention including but not limited to home visitation to learner/s
                        that committed 5 consecutive days of absences or those with potentials of dropping out.
                        </td>
                </tr>
                <tr>
                    <td>6. Attendance performance of learner is expected to reflect in Form 137 and Form 138 every grading
                        period
                        * Beginning of School Year cut-off report is every 1st Friday of School Calendar Days
                        </td>
                </tr>
            </table>
        </td>
        <td style="width: 25%;border:1px solid black;" valign="top">
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
        <td style="width:35%;padding-left:10px;" valign="top">
            <table style="width: 100%; font-size:12px;table-layout:fixed;" border="1">
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
                <tr style="text-align: center;">
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
                <tr style="text-align: center;">
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
                <tr style="text-align: center;">
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
                <tr style="text-align: center;">
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
                <tr style="text-align: center;">
                    <td colspan="2"><em>Average Daily Attendance</em></td>
                    <td id="ada_male">
                        @php
                            if(count($currentdays) == 0)
                            {
                                $avedailyatt_male = 0;
                            }else{
                                $avedailyatt_male = (collect($studentstotalperday)->sum('withrecordsmale')/count($currentdays));
                            }
                        @endphp
                        <!--{{ round($avedailyatt_male,2)}}-->
                        
                        @php
                            if(count($currentdays) == 0)
                            {
                                $avedailyatt_male = 0;
                            }else{
                                $avedailyatt_male = (collect($currentdays)->sum('presentmale')/count($currentdays));
                              $avedailyatt_male = bcdiv($avedailyatt_male,1,2);
                            }
                        @endphp
                        {{ bcdiv($avedailyatt_male,1,2)}}
                    </td>
                    <td id="ada_female">
                        @php
                        if(count($currentdays) == 0)
                        {
                            $avedailyatt_female = 0;
                        }else{
                            $avedailyatt_female = (collect($studentstotalperday)->sum('withrecordsfemale')/count($currentdays));
                        }
                        @endphp
                        <!--{{round($avedailyatt_female,2)}}-->
                        
                        @php
                            if(count($currentdays) == 0)
                            {
                                $avedailyatt_female = 0;
                            }else{
                                $avedailyatt_female = (collect($currentdays)->sum('presentfemale')/count($currentdays));
                            $avedailyatt_female = bcdiv($avedailyatt_female,1,2);
                            }
                        @endphp
                        {{ bcdiv($avedailyatt_female,1,2)}} 
                    </td>
                    <td  id="ada_total">
                        @php
                            if(count($currentdays) == 0)
                            {
                                $avedailyatt_total = 0;
                                $ada_total = 0;
                            }else{
                                // $avedailyatt_total = ((collect($currentdays)->sum('presentmale')+collect($currentdays)->sum('presentfemale'))/count($currentdays));
                        $avedailyatt_total = round(($avedailyatt_male+$avedailyatt_female),2);
                            }
                        @endphp
                        {{bcdiv($avedailyatt_total,1,2)}}
                    </td>
                </tr>
                @php
                $averagemale = 0;
                $averagefemale = 0;
                @endphp
                <tr style="text-align: center;">
                    <td colspan="2"><em>Percentage of Attendance for the month</em></td>
                    <td>
                        <?php try{ ?> 
                            {{bcdiv($pam_male,1,2)}}
                
                        <?php }catch(\Exception $e){ ?>
                            @php
                                if($avedailyatt_male>0 || $registered_male>0)
                                {
                                    if(number_format(($avedailyatt_male/$registered_male)*100,2)>100)
                                    {
                                        $pammale = 100;
                                    }
                                    else{
                                        $pammale = number_format(($avedailyatt_male/$registered_male)*100,2);
                                    }
                                }else{
                                        $pammale = 0;
                                }
                            @endphp
                            <!--@if($avedailyatt_male > 0 && $registered_male > 0)-->
                            <!--    {{$pammale}}-->
                            <!--@else-->
                            <!--0-->
                            <!--@endif-->
                            
                            @if($avedailyatt_male > 0 && $registered_male > 0)
                            @php
                            $averagemale = number_format((round($avedailyatt_male,2)/$registered_male)*100,2);
                            @endphp
                            @endif
                            {{$averagemale}}
                        <?php } ?>
                    </td>
                    <td>
                        <?php try{ ?> 
                            {{bcdiv($pam_female,1,2)}}
                
                        <?php }catch(\Exception $e){ ?>
                            @php
                                if($avedailyatt_female>0 || $registered_female>0)
                                {
                                    if(number_format(($avedailyatt_female/$registered_female)*100,2)>100)
                                    {
                                        $pamfemale = 100;
                                    }
                                    else{
                                        $pamfemale = number_format(($avedailyatt_female/$registered_female)*100,2);
                                    }
                                }else{
                                        $pamfemale = 0;
                                }
                            @endphp
                            <!--@if($avedailyatt_female > 0 && $registered_female > 0)-->
                            <!--    {{$pamfemale}}-->
                            <!--@else-->
                            <!--0-->
                            <!--@endif-->
                            @if($avedailyatt_female > 0 && $registered_female > 0)
                            @php
                            $averagefemale = number_format((round($avedailyatt_female,2)/$registered_female)*100,2);
                            @endphp
                            @endif
                            {{$averagefemale}}
                        <?php } ?>
                    </td>
                    <td>
                        <?php try{ ?> 
                            {{bcdiv($pam_total,1,2)}}
                        <?php }catch(\Exception $e){ ?>
                            @if($averagemale == 0 && $averagefemale > 0)
                            {{$averagefemale}}
                            @elseif($averagemale > 0 && $averagefemale == 0)
                            {{$averagemale}}
                            @else
                            {{number_format(($averagemale + $averagefemale)/2,2) }}
                            @endif
                            <!--{{number_format(($pammale+$pamfemale)/2,2)}}-->
                        <?php } ?>
                    </td>
                </tr>
                <tr style="text-align: center;">
                    <td colspan="2"><em>Number of students with 5 consecutive days of absences:</em></td>
                    <td>{{$countconsecutive_male}}</td>
                    <td>{{$countconsecutive_female}}</td>
                    <td>{{$countconsecutive_total}}</td>
                </tr>
                <tr style="background-color: gray;">
                    <td colspan="2">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr style="text-align: center;">
                    <td colspan="2"><em><strong>NLPA</strong></em></td>
                    <td>
                        {{$droppedout_male+$nlpamale}}
                    </td>
                    <td>
                        {{$droppedout_female+$nlpafemale}}
                    </td>
                    <td>
                        {{$droppedout_total+$nlpatotal}}
                    </td>
                </tr>
                <tr style="text-align: center;">
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
                <tr style="text-align: center;">
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
                <tr style="text-align: center;">
                    <td colspan="2"><em><strong>Shifted out</strong></em></td>
                    <td>
                        0
                    </td>
                    <td>
                        0
                    </td>
                    <td>
                        0
                    </td>
                </tr>
                <tr style="text-align: center;">
                    <td colspan="2"><em><strong>Shifted in</strong></em></td>
                    <td>
                        0
                    </td>
                    <td>
                        0
                    </td>
                    <td>
                        0
                    </td>
                </tr>
            </table>
            <br>
            <div style="font-size: 11px;">
                <span>
                    <em>I certify that this is a true and correct report:</em>
                </span>
            </div>
            <br/>
            <br/>
            <br/>
            <div style="font-size: 11px; text-align:center;padding-top: 2px;">
                <center>
                    <span>
                        
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sma' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndm')
                    {{$teachername->firstname}} {{isset($teachername->middlename[0]) ? $teachername->middlename[0].'.' : ''}} {{$teachername->lastname}} {{$teachername->suffix}}
                    @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                        {{$teachername->firstname}} {{isset($teachername->middlename[0]) ? $teachername->middlename[0].'.' : ''}} {{$teachername->lastname}} {{$teachername->suffix}}
                        @else
                        {{$teachername->firstname}} {{isset($teachername->middlename[0]) ? $teachername->middlename[0].'.' : ''}} {{$teachername->lastname}} {{$teachername->suffix}}
                        @endif
                    </span>
                    <hr class="col-md-8 p-0 m-0" style="border-color: black;"/>
                    <em class="p-0">(Signature of Adviser over Printed Name)</em>
                </center>
            </div>
            <br>
            <br>
            <div style="font-size: 11px;">
                <span>
                    @if(count($signatories) == 0)
                    <em>Attested by:</em>
                    @else
                    <em>{{$headerdesc}}</em>
                    @endif
                </span>
            </div>
            <br/>
            <br/>
            <br/>
            <div style="font-size: 11px; text-align:center;">
                <center>
                    <span>
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                        SR. MA. JULIETA E. RELATIVO, TDM
                        @else
                        {{$signatory_name}}
                        @endif
                    </span>
                    <hr class="col-md-8 p-0 m-0" style="border-color: black;"/>
                    @if(count($signatories) == 0)
                    <em class="p-0">(Signature of School Head over Printed Name)</em>
                    @else
                    <em class="p-0">{{$bottomdesc}} </em>
                    @endif
                </center>
            </div>
        </td>
    </tr>
    <!--<tr>-->
    <!--    <td></td>-->
    <!--    <td>&nbsp;</td>-->
    <!--    <td></td>-->
    <!--</tr>-->
    <!--<tr>-->
    <!--    <td></td>-->
    <!--    <td style="border-bottom: 1px solid black;">&nbsp;</td>-->
    <!--    <td></td>-->
    <!--</tr>-->
    <!--<tr>-->
    <!--    <td></td>-->
    <!--    <td style="text-align:center;">Generated thru LIS</td>-->
    <!--    <td></td>-->
    <!--</tr>-->
</table>
</div>