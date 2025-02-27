
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
<table style="width: 100%;">
    <tr>
        <td width="15%" rowspan="2"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px"></td>
        <td style="width: 50%; font-size: 20px;"><strong>{{DB::table('schoolinfo')->first()->schoolname}}</strong></td>
        <td style="text-align:right;"><strong>Class List</strong></td>
    </tr>
    <tr>
        <td style="font-size: 12px; font-weight: bold;">ADVISER: {{$teacherinfo->lastname}}, {{$teacherinfo->firstname}} @if($teacherinfo->middlename!= null){{$teacherinfo->middlename[0]}}.@endif {{$teacherinfo->suffix}}</td>
        <td style="font-size: 12px; text-align:right; font-weight: bold;">{{$sectioninfo->levelname}} - {{$sectioninfo->sectionname}}</td>
    </tr>
</table>

<br>
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
@endphp

@if($malecount > 0)
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
@endif


<div style="clear: both;"></div>
<br>
<br>
<table class="total">
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
</table>