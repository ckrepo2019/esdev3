
<style>
    html{
        text-transform: uppercase;
        
    font-family: Arial, Helvetica, sans-serif;
    }
.logo{
    width: 100%;
    table-layout: fixed;
}
.header{
    width: 100%;
}
.studentsMale th, .studentsMale td, .studentsFemale th, .studentsFemale td{
    border: 1px solid black;
}
.logo td ,
.header td {
    /* border: 1px solid black; */
}
.studentsMale{
    font-size: 12px;
    table-layout: fixed;
    font-family: Arial, Helvetica, sans-serif;
    float: left;
    border-spacing: 0;
}
.studentsFemale{
    font-size: 12px;
    table-layout: fixed;
    font-family: Arial, Helvetica, sans-serif;
    float: right;
    border-spacing: 0;
}
.studentsFemale td, .studentsMale td{
    border-top: hidden;
}
.studentsFemale th, .studentsMale th{
    text-align: center;
}
.total{
    font-size: 12px;
    width: 100%;
    table-layout: fixed;
    font-family: Arial, Helvetica, sans-serif;
    border-spacing: 0;
}
.total td{
    border: 1px solid black;
    text-align: center;
}
.clear:after {
    clear: both;
    content: "";
    display: table;
    border: 1px solid black;
}
@media print {
   button.download {
      display:none;
   }
}  
</style>
<table class="logo">
    <tr>
        <td width="15%"><img src="{{base_path()}}/public/assets/images/harvard.png" alt="school" width="70px"></td>
        <td>
            {{-- <center> --}}
                <span style="font-size: 11px;">{{$schoolinfo[0]->division}}</span>
                <br>
                <strong>{{$schoolinfo[0]->schoolname}}</strong>
                <br>
                <span style="font-size: 11px;">{{$schoolinfo[0]->address}}</span>
                {{-- <br>
                <br> --}}
                
            {{-- </center> --}}
        </td>
        <td width="15%"></td>
    </tr>
</table>
<table class="header">
    <tr>
        <td width="15%"></td>
        <td>
            <span style="font-size: 11px;"><strong>Schoolyear: </strong></span>
        </td>
        <td>
            <span style="font-size: 11px;"><u>{{$schoolyear[0]->sydesc}}</u></span>
        </td>
    </tr>
    <tr>
        <td width="15%"></td>
        <td>
            <span style="font-size: 11px;"><strong>Grade Level & Section:</strong></span>
        </td>
        <td>
            <span style="font-size: 11px;"><u>{{$data[0]->gradelevelname}} - {{$data[0]->sectionname}}</u></span>
        </td>
    </tr>
    <tr>
        <td width="15%"></td>
        <td>
            <span style="font-size: 11px;"><strong>Adviser:</strong></span>
        </td>
        <td>
            <span style="font-size: 11px;"><u>{{$data[0]->teacher_firstname}} {{$data[0]->teacher_middlename[0]}}. {{$data[0]->teacher_lastname}}</u></span>
        </td>
    </tr>
</table>
<br>
<span style="font-size: 12px;"><center><strong>List of Students</strong></center></span>
<br>
@if($genderCount['maleCount'] == 0 || $genderCount['femaleCount'] == 0)
    @php
        $width = '100%';   
    @endphp
@elseif($genderCount['maleCount'] != 0 && $genderCount['femaleCount'] != 0)
    @php
        $width = '50%';   
    @endphp
@endif
@php
    $countMale =  count($data->where('student_gender','MALE'));
    $countFemale =  count($data->where('student_gender','FEMALE'));
    $male = 1;
    $female = 1;
@endphp
@if($genderCount['maleCount'] != 0)
    <table class="studentsMale" style="width:{{$width}}">
        <tr>
            <th width="10%">No.</th>
            <th>MALE</th>
        </tr>
        @foreach ($data as $student)
            @if ($student->student_gender=="MALE")
                <tr>
                    <td style="text-align: center;">{{$male}}.</td>
                    <td><span style="padding-left: 10px;">{{$student->student_lastname}}, {{$student->student_firstname}} {{$student->student_middlename[0]}}.</span></td>
                </tr>
                @php
                    $male+=1;
                @endphp
            @endif
        @endforeach
    </table>
@endif

@if($genderCount['femaleCount'] != 0)
<table class="studentsFemale" style="width:{{$width}}">
    <tr>
        <th width="10%">No.</th>
        <th>FEMALE</th>
    </tr>
    @foreach ($data as $student)
        @if ($student->student_gender=="FEMALE")
            <tr>
                <td style="text-align: center;">{{$female}}.</td>
                <td><span style="padding-left: 10px;">{{$student->student_lastname}}, {{$student->student_firstname}} {{$student->student_middlename[0]}}.</span></td>
            </tr>
            @php
                $female+=1;    
            @endphp
        @endif
    @endforeach
</table>
@endif
<div style="clear: both;"></div>
<br>
<br>
<table class="total">
    <tr>
        <td>
            <strong>Male = {{$countMale}}</strong>
        </td>
        <td>
            <strong>Female = {{$countFemale}}</strong>
        </td>
        <td>
            <strong>Total = {{$countMale + $countFemale}}</strong>
        </td>
    </tr>
</table>

{{-- <p class="total">Male = <u>{{$countMale}}</u><br>Female = <u>{{$countFemale}}</u><br>Total = <u>{{$countMale + $countFemale}}</u></p> --}}
