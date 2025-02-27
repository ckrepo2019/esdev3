
<style>
    html{
        /* text-transform: uppercase; */
        
    font-family: Arial, Helvetica, sans-serif;
    }
.logo{
    width: 100%;
    table-layout: fixed;
}
.header{
    width: 100%;
}
table tr {
    page-break-inside: always !important;
}

</style>
<div style="width: 100%; text-align: center; font-size: 12px;">
    {{DB::table('schoolinfo')->first()->schoolname}}
    <br/>
    McArthur Highway, {{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}
    <br/>
    <br/>
    GRADING SHEET
    <br/>
    {{$semester}} S.Y. {{$sydesc}}
    
</div>
{{-- <table class="logo">
    <tr>
        <td width="15%"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px"></td>
        <td>
                <strong>{{DB::table('schoolinfo')->first()->schoolname}}</strong>
                <br>
                <span style="font-size: 11px;">{{DB::table('schoolinfo')->first()->address}}</span>
        </td>
        <td width="15%"></td>
    </tr>
</table> --}}
<br>
<table style="width: 100%; font-size: 11px; border-collapse: collapse; text-align: left !important;">
    <tr> 
        <th style="width: 15%;">Subjects Code:</th>     
        <td><u>{{collect($schedules)->first()->subjcode}}</u></td>
        <th style="width: 15%;">Credit Units:</th>
        <td>{{collect($schedules)->first()->lecunits + collect($schedules)->first()->labunits}}</td>
    </tr>
    <tr> 
        <th>Descriptive Title:</th>     
        <td><u>{{collect($schedules)->first()->subjDesc}}</u></td>
        <th>Time:</th>
        <td><u>{{date('h:i A',strtotime(collect($schedules)->first()->stime))}} - {{date('h:i A',strtotime(collect($schedules)->first()->etime))}}</u></td>
    </tr>
    <tr> 
        <th>Term:</th>     
        <td><u></u></td>
        <th>Instructor:</th>
        <td><u>{{$instructor}}</u></td>
    </tr>
    {{-- <tr> 
        <th>Subject</th>     
        <td>: {{collect($schedules)->first()->subjcode}}</td>
        <th>Description</th>      
        <td colspan="3">: {{collect($schedules)->first()->subjDesc}}</td>
    </tr>
    <tr>
        <th>TimeBegin</th>   
        <td>: {{date('h:i A',strtotime(collect($schedules)->first()->stime))}}</td> 
        <th>TimeEnd</th>    
        <td>: {{date('h:i A',strtotime(collect($schedules)->first()->etime))}}</td>
        <th>&nbsp;</th>    
        <td>&nbsp;</td>
    </tr> --}}
</table>
<br>
{{-- <span style="font-size: 12px;"><center><strong>List of Students</strong></center></span> --}}

<table  style="width:100%; font-size: 11px; border-collapse: collapse;" cellpadding="0" cellspacing="0" >
    <thead style="text-align: left !important;">
        <tr>
            <th>NO</th>
            <th style="width: 2%;">&nbsp;</th>
            <th style="width: 35%;">Name</th>
            <th style="width: 2%;">&nbsp;</th>
            <th>Program</th>
            <th style="width: 2%;">&nbsp;</th>
            <th>Year Level</th>
            <th style="width: 2%;">&nbsp;</th>
            <th>Midterm</th>
            <th style="width: 2%;">&nbsp;</th>
            <th>Final</th>
            <th style="width: 2%;">&nbsp;</th>
            <th>Sem Grade</th>
        </tr>
    </thead>
    <tbody>
        @php
            $num = 1;
        @endphp
            @foreach ($students as $key => $student)
                    <tr>
                        <td style="text-align: left;">{{$num}}</td>
                        <td></td>
                        <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</td>
                        <td></td>
                        <td>{{$student->coursename}}</td>
                        <td></td>
                        <td style="text-align: center;">{{isset($student->yearlevel) ? $student->yearlevel : ''}}</td>
                        <td></td>
                        <td style="border-bottom: 1px solid black;"></td>
                        <td></td>
                        <td style="border-bottom: 1px solid black;"></td>
                        <td></td>
                        <td style="border-bottom: 1px solid black;"></td>
                    </tr>
                    @php
                        $num += 1;
                    @endphp
            @endforeach

    </tbody>
</table>
<br/>
<br/>
<br/>
<table style="width: 100%; font-size: 12px;">
    <tr>
        <td style="border-bottom: 1px solid black;"></td>
        <td style="width: 10%;"></td>
        <td style="border-bottom: 1px solid black;"></td>
        <td style="width: 10%;"></td>
        <td style="border-bottom: 1px solid black;"></td>
    </tr>
    <tr>
        <td style="text-align: center;">Instructor</td>
        <td></td>
        <td style="text-align: center;">Dean</td>
        <td></td>
        <td style="text-align: center;">Registrar</td>
    </tr>
    <tr>
        <td>Date Signed:</td>
        <td></td>
        <td>Date Signed:</td>
        <td></td>
        <td>Date Signed:</td>
    </tr>
</table>
{{-- <div class="label" style="display:inline-block;
background-color:White;
width: auto; text-align:center; font-size: 12px;">
    <div class="label-text" style=" float:left;
    text-align: center;
    line-height: 30px;
    vertical-align: center;
    white-space: nowrap;
    overflow: hidden;">
    <span style="text-align:center;border-bottom: 1px solid black;">&nbsp;{{$teacher}}</span>
    <br/>
    <sup style="text-align:center">CLASS ADVISER</sup>
    
</div>
</div> --}}
{{-- <p class="total">Male = <u>{{$countMale}}</u><br>Female = <u>{{$countFemale}}</u><br>Total = <u>{{$countMale + $countFemale}}</u></p> --}}
