
<style>
    .header{ width: 100%; table-layout: fixed; font-family: Arial, Helvetica, sans-serif; /* border: 1px solid black; */ }

    .student_info{ width: 100%; font-family: Arial, Helvetica, sans-serif; font-size: 60%; text-transform:uppercase; }

    .student_data{ width: 100%; font-family: Arial, Helvetica, sans-serif; font-size: 60%; text-transform:uppercase; border-collapse: collapse;}

    .student_data td{
        border: 1px solid black;
    }
    .student_data th{
        border: 1px solid black;
        text-align: center;
    }
</style>
<table class="header">
    <tr>
        <td width="30%" style="text-align:right;"><img src="{{base_path()}}/public/assets/images/harvard.png" alt="school" width="70px"></td>
        <td>
            <center>
                <span style="font-size: 11px;">{{$schoolinfo[0]->division}}</span>
                <br>
                <strong>{{$schoolinfo[0]->schoolname}}</strong>
                <br>
                <span style="font-size: 11px;">{{$schoolinfo[0]->address}}</span>
            </center>
        </td>
        <td width="30%"></td>
    </tr>
</table>
&nbsp;
<table class="student_info">
    <tr>
        <td style="width:7%;">NAME :</td>
        <td style="border-bottom: 1px solid black;">{{$student_info[0]->lastname}}, {{$student_info[0]->firstname}} {{$student_info[0]->middlename[0]}}. {{$student_info[0]->suffix}}</td>
        <td style="width:5%;">SEX :</td>
        <td style="border-bottom: 1px solid black;width:10%;">{{$student_info[0]->gender}}</td>
        <td style="width:15%;">DATE OF BIRTH :</td>
        <td style="border-bottom: 1px solid black; width:15%;">{{$student_info[0]->dob}}</td>
    </tr>
</table>
<table class="student_info">
    <tr>
        <td style="width:20%">PLACE OF BIRTH : <small>(Street/Barangay)</small></td>
        <td style="border-bottom: 1px solid black;">{{$student_info[0]->street}} / {{$student_info[0]->barangay}}</td>
        <td style="width:10%"><small>(Municipal)</small></td>
        <td style="border-bottom: 1px solid black;">{{$student_info[0]->city}}</td>
        <td style="width:10%"><small>(Province)</small></td>
        <td style="border-bottom: 1px solid black;">{{$student_info[0]->province}}</td>
    </tr>
</table>
<table class="student_info">
    <tr>
        @if ($student_info[0]->mothername != ',')
            <td style="width:5%">PARENT / GUARDIAN : </td>
            <td style="width:10%;border-bottom: 1px solid black;">{{$student_info[0]->mothername}}</td>
            <td style="width:3%">OCCUPATION : </td>
            <td style="width:10%;border-bottom: 1px solid black;">{{$student_info[0]->moccupation}}</td>
        @elseif ($student_info[0]->fathername != ',')
            <td style="width:5%">PARENT / GUARDIAN : </td>
            <td style="width:10%;border-bottom: 1px solid black;">{{$student_info[0]->fathername}}</td>
            <td style="width:3%">OCCUPATION : </td>
            <td style="width:10%;border-bottom: 1px solid black;">{{$student_info[0]->foccupation}}</td>
        @elseif ($student_info[0]->guardianname != ',')
            <td style="width:5%">PARENT / GUARDIAN : </td>
            <td style="width:10%;border-bottom: 1px solid black;">{{$student_info[0]->guardianname}}</td>
            <td style="width:3%">OCCUPATION : </td>
            <td style="width:10%;border-bottom: 1px solid black;">&nbsp;</td>
        @endif
    </tr>
</table>
<table class="student_info">
    <tr>
        <td style="width:35%">INTERMEDIATE COURSE COMPLETED : </td>
        <td style="border-bottom: 1px solid black;"></td>
        <td style="width:10%">YEAR : </td>
        <td style="border-bottom: 1px solid black; width:15%">&nbsp;</td>
    </tr>
</table>
<table class="student_info">
    <tr>
        <td style="width:10%">ADDRESS :</td>
        <td style="border-bottom: 1px solid black;">{{$student_info[0]->street}}, {{$student_info[0]->barangay}}, {{$student_info[0]->city}}, {{$student_info[0]->province}}</td>
    </tr>
</table>
<br>
@foreach($res_data as $data )
    <table width="100%" class="student_info" style="padding-bottom: 2px;">
        <tr>
            <td style="width:10%;">CLASSIFIED AS:</td>
            <td style="width:5%;">School:</td>
            <td style="width:20%;border-bottom: 1px solid black;"><center>{{$data->schoolname}}</center></td>
            <td style="width:7%">School Year:</td>
            <td style="width:10%;border-bottom: 1px solid black;"><center>{{$data->sy}}</center></td>
        </tr>
        <tr>
            <td style="width:10%;"><u>{{$data->levelname}}</u></td>
            <td style="width:5%;">Adress:</td>
            <td style="width:20%;border-bottom: 1px solid black;"><center>&nbsp;</center></td>
            <td colspan="2"></td>
        </tr>
    </table>
    <table width="100%" class="student_data">
        <tr>
            <th style="width:30%;">Subject</th>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>FINAL RATING</th>
            <th>ACTION TAKEN</th>
            <th>CREDITS EARNED</th>
        </tr>
        @foreach ($data->grades as $datagrades)
            @if($datagrades->subj_desc != 'General Average')
            <tr>
                <td><center>{{$datagrades->subj_desc}}</center></td>
                <td><center>{{$datagrades->quarter1}}</center></td>
                <td><center>{{$datagrades->quarter2}}</center></td>
                <td><center>{{$datagrades->quarter3}}</center></td>
                <td><center>{{$datagrades->quarter4}}</center></td>
                <td><center>{{$datagrades->finalrating}}</center></td>
                <td><center>{{$datagrades->action}}</center></td>
                <td><center>{{$datagrades->credits}}</center></td>
            </tr>
            @endif
        @endforeach
        @foreach ($data->grades as $datagrades)
            @if($datagrades->subj_desc == 'General Average')
            <tr>
                <td colspan="5" style="background-color: lightgray;"><center>{{$datagrades->subj_desc}}</center></td>
                <td><center>{{$datagrades->finalrating}}</center></td>
                <td><center>{{$datagrades->action}}</center></td>
                <td></td>
            </tr>
            @endif
        @endforeach
    </table>
    <br>
    @php
        $schooldays = 0;
        $dayspresent = 0;
        $daysabsent = 0;
    @endphp
    <table width="100%" class="student_data">
        <tr>
            <td style="width:20%;"></td>
            <th>Jun</th>
            <th>Jul</th>
            <th>Aug</th>
            <th>Sept</th>
            <th>Oct	</th>
            <th>Nov</th>
            <th>Dec</th>
            <th>Jan</th>
            <th>Feb</th>
            <th>Mar</th>
            <th>Apr</th>
            <th>Total</th>
        </tr>
        <tr>
            <td>No. of School Days</td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Jun')
                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numofschooldays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Jul')
                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numofschooldays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Aug')
                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numofschooldays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Sep')
                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numofschooldays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Oct')
                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numofschooldays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Nov')
                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numofschooldays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Dec')
                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numofschooldays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Jan')
                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numofschooldays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Feb')
                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numofschooldays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Mar')
                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numofschooldays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Apr')
                    <center>{{$monthlyAttendance->numofschooldays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numofschooldays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                <center>{{$schooldays}}</center>
            </td>
        </tr>
        <tr>
            <td>No. of Days present</td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Jun')
                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Jul')
                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Aug')
                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Sep')
                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Oct')
                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Nov')
                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Dec')
                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Jan')
                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Feb')
                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Mar')
                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Apr')
                    <center>{{$monthlyAttendance->numofdayspresent}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->numofdayspresent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                <center>{{$dayspresent}}
            </td>
        </tr>
        <tr>
            <td>No. of Days absent</td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Jun')
                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Jul')
                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Aug')
                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Sep')
                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Oct')
                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Nov')
                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Dec')
                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Jan')
                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Feb')
                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Mar')
                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Apr')
                    <center>{{$monthlyAttendance->numofdaysabsent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->numofdaysabsent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                <center>{{$daysabsent}}</center>
            </td>
        </tr>
    </table>
    <br>
    <table width="100%" class="student_info" >
        <tr>
            <td style="width:15%;"><strong>TOTAL NUMBER OF UNITS EARNED:</strong></td>
            <td style="width:5%;border-bottom: 1px solid black;"><center>{{$data->numUnits}}</center></td>
            <td style="width:25%;">&nbsp;</td>
        </tr>
        <tr>
            <td style="width:15%;"><strong>TOTAL NUMBER OF YEARS IN SCHOOL TO DATE:</strong></td>
            <td style="width:5%;border-bottom: 1px solid black;"><center>{{$data->numYears}}</center></td>
            <td style="width:25%;">&nbsp;</td>
        </tr>
    </table>
    <br>
@endforeach