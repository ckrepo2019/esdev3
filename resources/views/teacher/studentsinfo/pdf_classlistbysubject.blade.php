
<title>CLASS LIST - {{$levelname}}_{{$sectionname}}_{{strtoupper($subjectname)}}</title>
<style>
    * {
        
        font-family: Arial, Helvetica, sans-serif;
    }
    .header{
        width: 100%;
        table-layout: fixed;
        font-family: Arial, Helvetica, sans-serif;
        /* border: 1px solid black; */
    }
    .studentstable{
        width: 100%;
        /* table-layout: fixed; */
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        border: 1px solid black;
        border-collapse: collapse;
    }
    .studentstable td, .enrollees th{
        border: 1px solid black;
        padding: 5px;
    }
    .clear:after {
        clear: both;
        content: "";
        display: table;
        border: 1px solid black;
    }
    .total{
        text-align: left;
        font-size: 11px;
        width: 20%;
        table-layout: fixed;
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
    }
    .total td{
        border: 1px solid black;
        
    } table td{
        padding: 0px;
    }
    table{
        border-collapse: collapse;
    }
</style>
{{-- <table style="width: 100%;">
    <tr>
        <td width="15%" rowspan="2"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px"></td>
        <td style="width: 50%; font-size: 18px;"><strong>{{DB::table('schoolinfo')->first()->schoolname}}</strong></td>
        <td style="text-align:right;"><strong>Class List</strong></td>
    </tr>
    <tr>
        <td style="font-size: 12px; vertical-align: top;">
            {{DB::table('schoolinfo')->first()->address}}
            <br/>
            <br/>
            <strong>SUBJECT: {{strtoupper($subjectname)}}</strong>
        </td>
        <td style="font-size: 12px; text-align:right; font-weight: bold; vertical-align: top;">
            {{$levelname}} - {{$sectionname}}
        </td>
    </tr>
</table> --}}
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <th><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px"></th>
        <td style="width: 80%; text-align: center;">
            <h3 style="font-weight: bold; margin: 0px;">{{DB::table('schoolinfo')->first()->schoolname}}</h3>
            <span style="font-size: 13px;">{{DB::table('schoolinfo')->first()->address}}</span>
        </td>
        <th></th>
    </tr>
</table>

<br>
<br>
<h3 style="text-align: center; margin: 0px;">CLASS LIST</h3>
@php
    $malecount = 0;
    $femalecount = 0;

    if(count($students)>0)
    {
        foreach($students as $student)
        {
            if(strtolower($student->gender) == 'male')
            {
                $malecount+=1;
            }
            if(strtolower($student->gender) == 'female')
            {
                $femalecount+=1;
            }
            $student->genderlower = strtolower($student->gender);
        }
    }
    $width = '100%';
    if($malecount == 0 || $malecount == 0)
    {
        $width = '100%'; 
    }
    elseif($malecount > 0 && $malecount > 0)
    {
        $width = '50%'; 
    }
    $max = max(array(collect($students)->where('genderlower','male')->count(),collect($students)->where('genderlower','female')->count()));
    $countmale = 0;
    $countfemale = 0;
@endphp

<table style="width: 100%; font-size: 13px;">
    <tr>
        <td style="font-weight: bold;">{{$levelname}} - {{$sectionname}}</td>
        <td style="font-weight: bold; text-align: right;">SUBJECT: {{strtoupper($subjectname)}}</td>
    </tr>
</table>
{{-- {{collect($students)->where('genderlower','male')->count()}} --}}
@if(count($students)>0)
<table style="width: 100%; table-layout: fixed; font-size: 12px;" border="1">
    @for($x = 0; $x < $max; $x++)
        <tr>
            <td style="width: 5%; text-align: center;">
                {{-- {{collect($students)->where('genderlower','male')->values()[$x]->lastname ?? $x}} --}}
                @if(isset(collect($students)->where('genderlower','male')->values()[$x]))
                    @php
                    $countmale+=1;
                    @endphp
                    {{$countmale}}
                @else
                {{$x+1}}
                @endif
            </td>
            <td>
                @if(isset(collect($students)->where('genderlower','male')->values()[$x]))
                <span style="padding-left: 10px;">{{collect($students)->where('genderlower','male')->values()[$x]->lastname}}, {{collect($students)->where('genderlower','male')->values()[$x]->firstname}} {{collect($students)->where('genderlower','male')->values()[$x]->suffix}} {{collect($students)->where('genderlower','male')->values()[$x]->middlename}}</span>
                @endif
            </td>
            <td style="width: 5%; text-align: center;">
                @if(isset(collect($students)->where('genderlower','female')->values()[$x]))
                    @php
                    $countfemale+=1;
                    @endphp
                    {{$countfemale}}
                @else
                {{$x+1}}
                @endif
            </td>
            <td>
                @if(isset(collect($students)->where('genderlower','female')->values()[$x]))
                <span style="padding-left: 10px;">{{collect($students)->where('genderlower','female')->values()[$x]->lastname}}, {{collect($students)->where('genderlower','female')->values()[$x]->firstname}} {{collect($students)->where('genderlower','female')->values()[$x]->suffix}} {{collect($students)->where('genderlower','female')->values()[$x]->middlename}}</span>
                @endif
            </td>
        </tr>
    @endfor
    <tr>
        <th colspan="2" style="border-right: hidden; text-align: center;">Male: {{$countmale}}</th>
        <th colspan="2" style="border-left: hidden; text-align: center;">Female: {{$countfemale}}</td>
    </tr>
    <tr>
        <th colspan="4" style=" text-align: center;"> Total: {{count($students)}}</td>
    </tr>
</table>
@endif
{{-- @if($malecount > 0)
    <table  style="width:{{$width}}; font-size: 12px; float: left;">
        <tr>
            <th width="10%">No.</th>
            <th>MALE</th>
        </tr>
        @foreach (collect($students)->where('genderlower','male')->values() as $studentkey=>$student)
                <tr>
                    <td style="text-align: center;">{{$studentkey+1}}</td>
                    <td><span style="padding-left: 10px;">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</span></td>
                </tr>
        @endforeach
    </table>
@endif

@if($femalecount > 0)
    <table  style="width:{{$width}}; font-size: 12px; float: right;">
        <tr>
            <th width="10%">No.</th>
            <th>FEMALE</th>
        </tr>
        @foreach (collect($students)->where('genderlower','female')->values() as $studentkey=>$student)
                <tr>
                    <td style="text-align: center;">{{$studentkey+1}}</td>
                    <td><span style="padding-left: 10px;">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</span></td>
                </tr>
        @endforeach
    </table>
@endif --}}


<div style="clear: both;"></div>
<br>
<br>
@if(count($classsched)>0)
<table style="width: 50%; font-size: 11px; border: 1px solid black;">
    <tr>
        <th colspan="3" style="text-align: left;">&nbsp;Class Schedule</th>
    </tr>
    
@foreach($classsched as $eachkey => $eachday)
        <tr>
            <th style="text-align: left; width: 30%;">&nbsp;{{$eachkey}}</th>
            <td></td>
            <td></td>
        </tr>
        
        @foreach($eachday as $each)
        <tr>
            <td></td>
            <td>{{date('h:i A', strtotime($each->stime))}} - {{date('h:i A', strtotime($each->etime))}}</td>
            <td>{{ucwords(strtolower($each->schedclassification))}}</td>
        </tr>
        @endforeach
@endforeach
</table>
<br>
<br>
@endif

<table style="width: 40%; font-size: 13px; break-page-inside: avoid;">
    <tr>
        <td>Prepared by:</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid black; text-align: center;">{{$subjectteacher->title != null ? $subjectteacher->title.'.' : ''}} {{$subjectteacher->firstname}} {{isset($subjectteacher->middlaneme[0]) ? $subjectteacher[0].'.' : ''}} {{$subjectteacher->lastname}} {{$subjectteacher->suffix}}</td>
    </tr>
    <tr>
        <td style="text-align: center;">Subject Teacher</td>
    </tr>
</table>




{{-- <table class="total">
    <tr>
        <td>&nbsp;&nbsp;&nbsp;Male</td>
        <td style="text-align: center;">{{$malecount}}</td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;Female</td>
        <td style="text-align: center;">{{$femalecount}}</td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;Total</td>
        <td style="text-align: center;">{{$malecount+$femalecount}}</td>
    </tr>
</table> --}}