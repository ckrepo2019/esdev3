<style>
    * {
        /* text-transform: uppercase; */
        font-family: Arial, Helvetica, sans-serif;
    }
</style>
<table style="width: 100%;">
    <tr>
        <td rowspan="2" style="width: 70%; font-size: 30px; text-align: left; font-weight: bold;">ABSENCES</td>
        <td style="font-size: 15px; text-align: right;">{{$weekid}}</td>
    </tr>
    <tr>
        <td style="text-align: right;">{{date('M d', strtotime(collect($days)->first()))}} - {{date('d', strtotime(collect($days)->last()))}}</td>
    </tr>
</table>
<br/>
@foreach($employees as $employee)
    @if(count($employee->daysabsent)>0)
        <table style="width: 100%; font-size: 12px; border-collapse: collapse; margin-bottom: 10px;">
            {{-- <thead> --}}
                <tr>
                    <td colspan="3" style="text-align: left; padding-right: 5px; font-weight: bold;">
                        {{strtoupper($employee->lastname)}}, {{ucwords(strtolower($employee->firstname))}} @if($employee->middlename != null){{$employee->middlename[0]}}.@endif {{$employee->suffix}}
                    </td>
                    <td></td>
                </tr>
            {{-- </thead> --}}
            @foreach($employee->daysabsent as $key => $eachday)
                <tr>
                    <td style="width: 20%; text-align: left; border-bottom: 1px solid black;">{{date('M d, Y - l', strtotime($eachday->date))}}</td>
                    <td style="text-align: center; border-bottom: 1px solid black; width: 10%;">Remarks:</td>
                    <td style="text-align: center; border-bottom: 1px solid black;">{{$eachday->remarks}}</td>                    
                    @if($key == 0)
                    <td rowspan="{{count($employee->daysabsent)}}" style="text-align: center; border: 1px solid black; width: 25%;">
                        @foreach($employee->offenses as $eachoffense)
                            <div style="width: 100%;">{{collect($offenses)->where('id', $eachoffense->offenseid)->first()->title}} - {{collect($offenses)->where('id', $eachoffense->offenseid)->first()->description}}</div>
                        @endforeach
                    </td>
                    @endif
                </tr>
            @endforeach
            {{-- <tr>
                <td colspan="4" style="text-align: right; padding-right: 5px;">
                    Total Late Hours: <u>{{collect($employee->records)->sum('latehours')}}</u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Undertime Hours: <u>{{collect($employee->records)->sum('undertimehours')}}</u>
                </td>
            </tr> --}}
        </table>
    @endif
@endforeach