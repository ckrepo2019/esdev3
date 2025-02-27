
<style>
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
</style>
<table class="header">
    <tr>
        <td width="15%" rowspan="2"><img src="{{base_path()}}/public/assets/images/harvard.png" alt="school" width="70px"></td>
        <td><strong>{{$schoolinfo[0]->schoolname}}</strong> </td>
        <td style="text-align:right;"><strong>NUMBER OF ENROLLEES</strong></td>
    </tr>
    <tr>
        <td>
            <span style="font-size: 11px;">{{$schoolinfo[0]->address}}</span>
            <br>
            <span style="font-size: 11px;">{{$schoolinfo[0]->division}}</span>
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
<hr style="border: 2px solid gray;"/>
<table class="enrollees">
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
</table>
<div style="clear: both;"></div>
