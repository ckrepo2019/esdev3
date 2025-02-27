<style>
    * {
        /* text-transform: uppercase; */
        font-family: Arial, Helvetica, sans-serif;
    }
</style>
<table style="width: 100%;">
    <tr>
        <td rowspan="2" style="width: 70%; font-size: 20px; text-align: left; font-weight: bold;">{{$employee->title}} {{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}}</td>
        <td style="font-size: 15px; text-align: right;">{{$weekid}}</td>
    </tr>
    <tr>
        <td style="text-align: right;">{{date('M d', strtotime(collect($days)->first()))}} - {{date('d', strtotime(collect($days)->last()))}}</td>
    </tr>
</table>
<br/>
<table style="width: 100%; font-size: 12px; border-collapse: collapse;" border="1">
    <tr>
        <th style="width: 25%:">Date</th>
        <th style="width: 40%:">Remarks</th>
        <th>Offense</th>
    </tr>
    @foreach($employee->daysabsent as $key => $eachday)
        <tr>
            <td>{{date('M d, Y - l', strtotime($eachday->date))}}</td>
            <td>{{$eachday->remarks}}</td>
            @if($key == 0)
            <td rowspan="{{count($employee->daysabsent)}}" style="text-align: center;">
                @foreach($employee->offenses as $eachoffense)
                    <div style="width: 100%;">{{collect($offenses)->where('id', $eachoffense->offenseid)->first()->title}} - {{collect($offenses)->where('id', $eachoffense->offenseid)->first()->description}}</div>
                @endforeach
            </td>
            @endif
        </tr>
    @endforeach
</table>