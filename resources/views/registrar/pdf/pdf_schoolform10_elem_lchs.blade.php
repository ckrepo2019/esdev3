
<style>
    * { font-family: Arial, Helvetica, sans-serif; }
    .header{ width: 100%; table-layout: fixed;  /* border: 1px solid black; */ }

    .student_info{ width: 100%; font-family: Arial, Helvetica, sans-serif; font-size: 60%; text-transform:uppercase; }

    .student_data{ width: 100%; font-family: Arial, Helvetica, sans-serif; font-size: 60%; text-transform:uppercase; border-collapse: collapse;table-layout:fixed;}

    .student_data td{
        border: 1px solid black;
    }
    .student_data th{
        border: 1px solid black;
        text-align: center;
    }
    footer {
        
        position: fixed; 
        bottom: -60px; 
        left: 0px; 
        right: 0px;
        height: 150px; 
        font-size: 60%;
        /** Extra personal styles **/
        /* background-color: #03a9f4; */
        color: black;
        /* text-align: center; */
        line-height: 20px;
    }
</style>
<table class="header">
    <tr>
        <td width="30%" style="text-align:right;"><img src="{{base_path()}}/public/{{$school->picurl}}" alt="school" width="70px"></td>
        <td>
            <center>
                <span style="font-size: 11px;">{{$school->division}}</span>
                <br>
                <strong>{{$school->schoolname}}</strong>
                <br>
                <span style="font-size: 11px;">{{$school->address}}</span>
            </center>
        </td>
        <td width="30%"></td>
    </tr>
</table>
&nbsp;
<table class="student_info">
    <tr>
        <td style="width:7%;">NAME :</td>
        <td style="border-bottom: 1px solid black;">{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename[0]}}. {{$studinfo->suffix}}</td>
        <td style="width:5%;">SEX :</td>
        <td style="border-bottom: 1px solid black;width:10%;">{{$studinfo->gender}}</td>
        <td style="width:15%;">DATE OF BIRTH :</td>
        <td style="border-bottom: 1px solid black; width:15%;">{{$studinfo->dob}}</td>
    </tr>
</table>
<table class="student_info">
    <tr>
        <td style="width:20%">PLACE OF BIRTH : <small>(Street/Barangay)</small></td>
        <td style="border-bottom: 1px solid black;">{{$studinfo->street}} / {{$studinfo->barangay}}</td>
        <td style="width:10%"><small>(Municipal)</small></td>
        <td style="border-bottom: 1px solid black;">{{$studinfo->city}}</td>
        <td style="width:10%"><small>(Province)</small></td>
        <td style="border-bottom: 1px solid black;">{{$studinfo->province}}</td>
    </tr>
</table>
<table class="student_info">
    <tr>
        @if ($studinfo->mothername != ',')
            <td style="width:5%">PARENT / GUARDIAN : </td>
            <td style="width:10%;border-bottom: 1px solid black;">{{$studinfo->mothername}}</td>
            <td style="width:3%">OCCUPATION : </td>
            <td style="width:10%;border-bottom: 1px solid black;">{{$studinfo->moccupation}}</td>
        @elseif ($studinfo->fathername != ',')
            <td style="width:5%">PARENT / GUARDIAN : </td>
            <td style="width:10%;border-bottom: 1px solid black;">{{$studinfo->fathername}}</td>
            <td style="width:3%">OCCUPATION : </td>
            <td style="width:10%;border-bottom: 1px solid black;">{{$studinfo->foccupation}}</td>
        @elseif ($studinfo->guardianname != ',')
            <td style="width:5%">PARENT / GUARDIAN : </td>
            <td style="width:10%;border-bottom: 1px solid black;">{{$studinfo->guardianname}}</td>
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
        <td style="border-bottom: 1px solid black;">{{$studinfo->address}}</td>
    </tr>
</table>
<br>
@foreach($gradesArray as $data )
    <table width="100%" class="student_info" style="padding-bottom: 2px;">
        <tr>
            <td style="width:10%;">CLASSIFIED AS:</td>
            <td style="width:5%;">School:</td>
            <td style="width:20%;border-bottom: 1px solid black;"><center>{{$data->schoolinformation->schoolname}}</center></td>
            <td style="width:7%">School Year:</td>
            <td style="width:10%;border-bottom: 1px solid black;"><center>{{$data->gradedetails->sydesc}}</center></td>
        </tr>
        <tr>
            <td style="width:10%;"><u>{{$data->gradedetails->levelname}}</u></td>
            <td style="width:5%;">Adress:</td>
            <td style="width:20%;border-bottom: 1px solid black;"><center>{{$data->schoolinformation->address}}</center></td>
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
            <tr>
                <td><center>{{$datagrades->subjtitle}}</center></td>
                <td><center>{{$datagrades->quarter1}}</center></td>
                <td><center>{{$datagrades->quarter2}}</center></td>
                <td><center>{{$datagrades->quarter3}}</center></td>
                <td><center>{{$datagrades->quarter4}}</center></td>
                <td><center>{{$datagrades->finalrating}}</center></td>
                <td><center>{{$datagrades->remarks}}</center></td>
                <td><center></center></td>
            </tr>
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
                    <center>{{$monthlyAttendance->numDays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numDays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Jul')
                    <center>{{$monthlyAttendance->numDays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numDays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Aug')
                    <center>{{$monthlyAttendance->numDays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numDays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Sep')
                    <center>{{$monthlyAttendance->numDays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numDays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Oct')
                    <center>{{$monthlyAttendance->numDays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numDays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Nov')
                    <center>{{$monthlyAttendance->numDays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numDays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Dec')
                    <center>{{$monthlyAttendance->numDays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numDays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Jan')
                    <center>{{$monthlyAttendance->numDays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numDays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Feb')
                    <center>{{$monthlyAttendance->numDays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numDays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Mar')
                    <center>{{$monthlyAttendance->numDays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numDays;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Apr')
                    <center>{{$monthlyAttendance->numDays}}</center>
                    @php
                        $schooldays+=$monthlyAttendance->numDays;
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
                    <center>{{$monthlyAttendance->present}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->present;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Jul')
                    <center>{{$monthlyAttendance->present}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->present;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Aug')
                    <center>{{$monthlyAttendance->present}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->present;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Sep')
                    <center>{{$monthlyAttendance->present}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->present;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Oct')
                    <center>{{$monthlyAttendance->present}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->present;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Nov')
                    <center>{{$monthlyAttendance->present}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->present;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Dec')
                    <center>{{$monthlyAttendance->present}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->present;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Jan')
                    <center>{{$monthlyAttendance->present}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->present;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Feb')
                    <center>{{$monthlyAttendance->present}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->present;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Mar')
                    <center>{{$monthlyAttendance->present}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->present;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Apr')
                    <center>{{$monthlyAttendance->present}}</center>
                    @php
                        $dayspresent+=$monthlyAttendance->present;
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
                    <center>{{$monthlyAttendance->absent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->absent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Jul')
                    <center>{{$monthlyAttendance->absent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->absent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Aug')
                    <center>{{$monthlyAttendance->absent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->absent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Sep')
                    <center>{{$monthlyAttendance->absent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->absent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Oct')
                    <center>{{$monthlyAttendance->absent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->absent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Nov')
                    <center>{{$monthlyAttendance->absent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->absent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Dec')
                    <center>{{$monthlyAttendance->absent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->absent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Jan')
                    <center>{{$monthlyAttendance->absent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->absent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Feb')
                    <center>{{$monthlyAttendance->absent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->absent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Mar')
                    <center>{{$monthlyAttendance->absent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->absent;
                    @endphp
                    @else
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($data->attendance as $monthlyAttendance)
                    @if($monthlyAttendance->month == 'Apr')
                    <center>{{$monthlyAttendance->absent}}</center>
                    @php
                        $daysabsent+=$monthlyAttendance->absent;
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
            <td style="width:5%;border-bottom: 1px solid black;"><center></center></td>
            <td style="width:25%;">&nbsp;</td>
        </tr>
        <tr>
            <td style="width:15%;"><strong>TOTAL NUMBER OF YEARS IN SCHOOL TO DATE:</strong></td>
            <td style="width:5%;border-bottom: 1px solid black;"><center></center></td>
            <td style="width:25%;">&nbsp;</td>
        </tr>
    </table>
    <br>
@endforeach
@foreach($tor as $torrecord )
    <table width="100%" class="student_info" style="padding-bottom: 2px;">
        <tr>
            <td style="width:10%;">CLASSIFIED AS:</td>
            <td style="width:5%;">School:</td>
            <td style="width:20%;border-bottom: 1px solid black;"><center>{{$torrecord->schoolinfo->schoolname}}</center></td>
            <td style="width:7%">School Year:</td>
            <td style="width:10%;border-bottom: 1px solid black;"><center>{{$torrecord->schoolyear->schoolyear}}</center></td>
        </tr>
        <tr>
            <td style="width:10%;"><u>{{$torrecord->levelname->levelname}}</u></td>
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
        @foreach ($torrecord->grades as $datagrades)
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
        @endforeach
        @if(count($torrecord->generalaverage) == 0)
            <tr>
                <td colspan="5" style="background-color: lightgray;"></td>
                <td><center></center></td>
                <td><center></center></td>
                <td></td>
            </tr>
        @else
            <tr>
                <td colspan="5" style="background-color: lightgray;"><center>GENERAL AVERAGE</center></td>
                <td><center>{{$torrecord->generalaverage[0]->genave}}</center></td>
                <td><center></center></td>
                <td></td>
            </tr>
        @endif
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
                @foreach ($torrecord->attendance as $monthlyAttendance)
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
            <td style="width:5%;border-bottom: 1px solid black;"><center>{{$torrecord->schoolyear->unitsearned}}</center></td>
            <td style="width:25%;">&nbsp;</td>
        </tr>
        <tr>
            <td style="width:15%;"><strong>TOTAL NUMBER OF YEARS IN SCHOOL TO DATE:</strong></td>
            <td style="width:5%;border-bottom: 1px solid black;"><center>{{$torrecord->schoolyear->yearsinschool}}</center></td>
            <td style="width:25%;">&nbsp;</td>
        </tr>
    </table>
    <br>
@endforeach

<footer>
    <em>
        I certify that this is a true record of ____________ He/She is elligible for transfer and admission to ___________ and has no property responsibility in this school. This record is issued on the ____________ upon the request of whatever legal purpose it may serve him/her best.
    </em>
    <br/>
    <table style="width: 100%; padding-top: 2px;">
        <thead>
            <tr>
                <th rowspan="2" style="text-align:left">Not valid without School seal</th>
                <th style="text-align:center;border-bottom: 1px solid black;"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align:center;">School Principal</td>
            </tr>
        </tbody>
    </table>
</footer>