
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
    .header td {
        /* border: 1px solid black; */
    }
    .enrollees{
        width: 100%;
        table-layout: fixed;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        border: 1px solid black;
        border-collapse: collapse;
    }
    .enrollees td, .enrollees th{
        border: 1px solid black;
        padding: 2px;
    }
    .clear:after {
        clear: both;
        content: "";
        display: table;
        border: 1px solid black;
    }
    .persection td{
        border: 1px solid black;
    }
</style>
<h5 style="font-weight: bold;">
    AS OF : {{strtoupper($from)}} TO {{strtoupper($to)}}
</h5>
<h5 style="font-weight: bold;">
    TOTAL NO. OF STUDENTS : {{$totalno}}
</h5>
{{-- <table class="header">
    <tr>
        <td width="15%" rowspan="2"><img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="70px"></td>
        <td>
            <strong>{{$schoolinfo[0]->schoolname}}</strong> 
            <br>
            <span style="font-size: 11px;">{{$schoolinfo[0]->division}}</span> | 
            <span style="font-size: 11px;">{{$schoolinfo[0]->district}}</span> | 
            <span style="font-size: 11px;">{{$schoolinfo[0]->region}}</span>
            <br/>
            <span style="font-size: 11px;">{{$schoolinfo[0]->address}}</span>
        </td>
        <td style="text-align:right;"><strong>NUMBER OF ENROLLEES</strong></td>
    </tr>
    <tr>
        <td>
            <br>
        </td>
        <td style="text-align:right;vertical-align:bottom">
            <span style="font-size: 11px;">
                @if($from == $to)
                    {{$from}}
                @else
                    {{$from}}&nbsp;to&nbsp;{{$to}}
                @endif
            </span>
        </td>
    </tr>
</table>
<hr style="border: 2px solid gray;"/> --}}
@foreach($enrollees as $enrollee)
        {{-- <table style="width: 100%; ">
            <thead>
                <tr>
                    <th>{{$enrollee->levelname}}</th>
                </tr>
            </thead>
            <tbody> --}}
                <div style="width: 100%; text-align: center;font-weight: bold;" border="1">{{$enrollee->levelname}}</div>
                @foreach($enrollee->students as $sectionname => $students)
                        {{-- <tr>
                            <td> --}}
                                <table style="font-size: 11px;" class="persection" border="1" cellpadding="2" >
                                    <thead>
                                        <tr>
                                            <th colspan="2">
                                                SECTION: {{$sectionname}}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalpersection = 0;
                                        @endphp
                                        @foreach($students as $student)
                                        @php
                                            $totalpersection+=1;
                                        @endphp
                                        <tr>
                                            <td style="width: 5%;text-align: center;">
                                                {{$totalpersection}}
                                            </td>
                                            <td style="width: 95%;">
                                                {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td style="text-align: right;" colspan="2">
                                                No. of students: {{$totalpersection}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            {{-- </td>
                        </tr> --}}
                @endforeach
            {{-- </tbody>
        </table> --}}
    <br/>
@endforeach
{{-- <table class="enrollees">
    <tr>
        <th>GRADE LEVEL</th>
        <th>No. of enrollees</th>
    </tr>
    @php
        $total = 0;   
    @endphp
    @foreach ($numOfEnrollees as $value)
        <tr>
            <td>{{$value[0]}}</td>
            <td><center>{{$value[1]}}</center></td>
        </tr>
    @php
        $total+=$value[1];
    @endphp
    @endforeach
    <tr>
        <td><center><strong>TOTAL</strong></center></td>
        <td><center><strong>{{$total}}</strong></center></td>
    </tr>
</table> --}}
<div style="clear: both;"></div>
