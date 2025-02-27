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
    {{-- <tr>
        <th style="width: 20%;">Date</td>
        <th>Late Hours</th>
        <th>Undertime Hours</th>
        <th></th>
    </tr> --}}
    @foreach($employee->records as $key => $eachday)
        <tr>
            <th style="width: 20%; text-align: center;">{{$eachday->daystring}}</th>
            <td style="text-align: center;">Late Hours : {{$eachday->latehours}}</td>
            <td style="text-align: center;">Undertime Hours : {{$eachday->undertimehours}}</td>
            <td style="text-align: center;"></td>
        </tr>
        {{-- <tr>
            <th colspan="2">AM</th>
            <th colspan="2">PM</th>
        </tr> --}}
        <tr>
            <td>AM IN : @if($eachday->amtimein != null){{$eachday->amtimein}}@endif</td>
            <td>AM OUT : @if($eachday->amtimeout != null){{date('h:i:s', strtotime($eachday->amtimeout))}}@endif</td>
            <td>PM IN : @if($eachday->pmtimein != null){{date('h:i:s', strtotime($eachday->pmtimein))}}@endif</td>
            <td>PM OUT : @if($eachday->pmtimeout != null){{date('h:i:s', strtotime($eachday->pmtimeout))}}@endif</td>
        </tr>
        {{-- <tr>
            <td>{{date('M d, Y - l', strtotime($eachday->date))}}</td>
            <td>{{$eachday->remarks}}</td>
            @if($key == 0)
            <td rowspan="{{count($employee->daysabsent)}}" style="text-align: center;">
                @foreach($employee->offenses as $eachoffense)
                    <div style="width: 100%;">{{collect($offenses)->where('id', $eachoffense->offenseid)->first()->title}} - {{collect($offenses)->where('id', $eachoffense->offenseid)->first()->description}}</div>
                @endforeach
            </td>
            @endif
        </tr> --}}
    @endforeach
    <tr>
        <td colspan="4" style="text-align: right; padding-right: 5px;">
            Total Late Hours: {{collect($employee->records)->sum('latehours')}}
            <br/>
            Total Undertime Hours: {{collect($employee->records)->sum('undertimehours')}}
        </td>
    </tr>
</table>