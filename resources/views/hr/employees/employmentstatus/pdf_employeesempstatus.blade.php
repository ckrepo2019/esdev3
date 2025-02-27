<style>
    * {
        /* text-transform: uppercase; */
        font-family: Arial, Helvetica, sans-serif;
    }
    @page{
        margin: 30px 20px;
    }
</style>
<table style="width: 100%;">
    <tr>
        <th rowspan="4" style="width: 15%; padding: 0px;">
		{{--<img src='{{base_path().'/public/'.substr(DB::table('schoolinfo')->first()->picurl, 0, strpos(DB::table('schoolinfo')->first()->picurl, "?"))}}' alt='school' width='100px'></th>--}}
        <th>{{DB::table('schoolinfo')->first()->schoolname}}</th>
		
        <th rowspan="4" style="width: 15%;">&nbsp;</th>
    </tr>
    <tr>
        <td style="text-align: center; font-size: 12px;">{{DB::table('schoolinfo')->first()->address}}</td>
    </tr>
    <tr>
        <th style="text-align: center; font-size: 12px;">Employees</th>
    </tr>
    <tr>
        <td style="text-align: center; font-size: 12px; font-style: italic;">Employment Status</td>
    </tr>
</table>
<table style="width: 100%; font-size: 12px; border-collapse: collapse; border: 1px solid black;">
    <tr>
        <th style="width: 2% !important; vertical-align: top; padding-top: 0px;"><input type="checkbox" @if(0 == $statusid) checked @endif/></th>
        <th style=" width: 5%; vertical-align: middle; padding: 0px;">All</th>
        @foreach($statustypes as $statustype)
            <th style="width: 2% !important; vertical-align: top; padding-top: 0px;"><input type="checkbox" @if($statustype->id == $statusid) checked @endif/></th>
            <th style="vertical-align: middle; padding-left: 1px; text-align: left;">{{$statustype->description}}</th>
        @endforeach
    </tr>
</table>
<br/>
<table style="width: 100%; font-size: 12px; border-collapse: collapse;" border="1">
    <thead>
        <tr>
            <th width="5%">#</th>
            <th width="42%">Employee</th>
            <th width="12%">Date Started</th>
            <th width="17%">Years in service</th>
            <th>Employment Status</th>
        </tr>
    </thead>
    @foreach($employees as $employeekey => $employee)
        <tr>
            <td style="text-align: center;">{{$employeekey+1}}</td>
            <td style="padding-left: 5px;"><span style="font-weight: bold;">{{strtoupper($employee->lastname)}}</span>, {{ucwords(strtolower($employee->firstname))}} {{ucwords(strtolower($employee->middlename))}} {{$employee->suffix}}</td>
            <td style="text-align: center;"> @if($employee->datehired != null){{ date('M d, Y', strtotime($employee->datehired))}}@endif</td>
            <td style="text-align: center; font-size: 11px;">{{$employee->yearsinservice}}</td>
            <td style="padding-left: 10px;">
                @if($employee->employmentstatus > 0 && $employee->employmentstatus != null)
                    @if(collect($statustypes)->where('id', $employee->employmentstatus)->count() > 0)
                        {{collect($statustypes)->where('id', $employee->employmentstatus)->first()->description}}
                    @endif
                @endif
            </td>
        </tr>
    @endforeach
</table>
<br/>
@if($statusid == 0)
<table style="width: 30%; border-collapse: collapse; font-size: 12px;" border="1">
    <tr>
        <th colspan="2">Summary</th>
    </tr>
    <tr>
        <td>Unset</td>
        <td style="text-align: center;">{{collect($employees)->where('employmentstatus', 0)->count()}}</td>
    </tr>
    @foreach($statustypes as $eachstatus)
        <tr>
            <td>{{$eachstatus->description}}</td>
            <td style="text-align: center;">{{collect($employees)->where('employmentstatus', $eachstatus->id)->count()}}</td>
        </tr>
    @endforeach
</table>
@endif
{{-- <table style="width: 100%;">
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
</table> --}}