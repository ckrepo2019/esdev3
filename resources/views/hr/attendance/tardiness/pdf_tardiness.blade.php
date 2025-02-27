<style>
    * {
        /* text-transform: uppercase; */
        font-family: Arial, Helvetica, sans-serif;
    }
</style>
<table style="width: 100%;">
    <tr>
        <td rowspan="2" style="width: 70%; font-size: 30px; text-align: left; font-weight: bold;">TARDINESS/UNDERTIME</td>
        <td style="font-size: 15px; text-align: right;">{{$weekid}}</td>
    </tr>
    <tr>
        <td style="text-align: right;">{{date('M d', strtotime(collect($days)->first()))}} - {{date('d', strtotime(collect($days)->last()))}}</td>
    </tr>
</table>
<br/>
@foreach($employees as $employee)
    @if(count($employee->records)>0)
        <table style="width: 100%; font-size: 12px; border-collapse: collapse; margin-bottom: 10px;">
            <tr>
                <td colspan="4" style="text-align: left; padding-right: 5px; font-weight: bold;">
                    {{strtoupper($employee->lastname)}}, {{ucwords(strtolower($employee->firstname))}} @if($employee->middlename != null){{$employee->middlename[0]}}.@endif {{$employee->suffix}}
                </td>
            </tr>
            @foreach($employee->records as $key => $eachday)
                <tr>
                    <th style="width: 20%; text-align: left; border-bottom: 1px solid black;">{{$eachday->daystring}}</th>
                    <td style="text-align: center; border-bottom: 1px solid black;">Late Hours : {{$eachday->latehours}}</td>
                    <td style="text-align: center; border-bottom: 1px solid black;">Undertime Hours : {{$eachday->undertimehours}}</td>
                    <td style="text-align: center; border-bottom: 1px solid black;"></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid black;">AM IN : @if($eachday->amtimein != null){{$eachday->amtimein}}@endif</td>
                    <td style="border-bottom: 1px solid black;">AM OUT : @if($eachday->amtimeout != null){{date('h:i:s', strtotime($eachday->amtimeout))}}@endif</td>
                    <td style="border-bottom: 1px solid black;">PM IN : @if($eachday->pmtimein != null){{date('h:i:s', strtotime($eachday->pmtimein))}}@endif</td>
                    <td style="border-bottom: 1px solid black;">PM OUT : @if($eachday->pmtimeout != null){{date('h:i:s', strtotime($eachday->pmtimeout))}}@endif</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" style="text-align: right; padding-right: 5px;">
                    Total Late Hours: <u>{{collect($employee->records)->sum('latehours')}}</u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Undertime Hours: <u>{{collect($employee->records)->sum('undertimehours')}}</u>
                </td>
            </tr>
        </table>
    @endif
@endforeach