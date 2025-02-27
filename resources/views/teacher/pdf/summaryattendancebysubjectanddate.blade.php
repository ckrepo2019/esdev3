
<style>
    .header{
        width: 100%;
        table-layout: fixed;
        font-family: Arial, Helvetica, sans-serif;
        /* border: 1px solid black; */
    }
    .header td {
        font-size: 15px !important;
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
    tbody td {
        font-size: 11px !important;
    }
</style>
<table class="header">
    <tr>
        <td width="15%" rowspan="2"><img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="70px"></td>
        <td><strong>{{$schoolinfo[0]->schoolname}}</strong> </td>
        <td style="text-align:right;"><strong>Attendance Summary</strong><br><small>S.Y {{$sy[0]->sydesc}}</small></td>
    </tr>
</table>
<br>
@php
    $count = 1;   
@endphp
<table class="studentstable">
    <thead>
        <tr>
            <th colspan="4">
                {{$gradelevel[0]->levelname}} - {{$sectionname[0]->sectionname}}
                <br>
                {{$attendancedate}}
            </th>
        </tr>
        <tr>
            <th></th>
            <th>Name of Student</th>
            <th></th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody style="text-transform: uppercase;">
        @foreach ($attendance as $studatt)
            <tr>
                <td style="width: 10px;">{{$count}}</td>
                <td>{{$studatt->studentname}}</td>
                <td style="text-align:center; width: 20%">{{$studatt->status}}</td>
                <td>{{$studatt->remarks}}</td>
            </tr>
        @endforeach
    </tbody>
</table>