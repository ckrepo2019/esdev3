{{-- <style>
        @page{
            size: 3.5in 7in;
            padding: 0px;
            margin: 5px;
        }
        * {
            font-family: Arial, Helvetica, sans-serif;
        }
        table{
            border-collapse: collapse;
        }
    </style>
    <table style="width: 100%; margin: 5px;">
        <thead>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <th>{{DB::table('schoolinfo')->first()->schoolname}}</th>
            </tr>
            <tr>
                <td style="font-size: 10px; text-align: center;">{{DB::table('schoolinfo')->first()->address}}</td>
            </tr>
            <tr>
                <td></td>
            </tr>
        </thead>
        <tr>
            <td style="background-color: #eb9bb1; text-align: center; font-size: 25px">Daily Time Record</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr style="font-size: 12px;">
            <td>Name: <u>{{$info->lastname}}, {{$info->firstname}} {{$info->middlename[0]}}. {{$info->suffix}}</u></td>
        </tr>
        <tr style="font-size: 12px;">
            <td>Address: </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr style="font-size: 12px; font-weight: bold;">
            <td>{{date('M d', strtotime($datefrom))}} - {{date('d, Y', strtotime($dateto))}}</td>
        </tr>
    </table>
    <table style="width: 100%; font-size: 12px;margin: 5px;" border="1" >
        <thead>
            <tr>
                <th style="background-color: #e5f095;" rowspan="2">{{substr(date('M', strtotime($datefrom)), 0, 3)}}</th>
                <th colspan="2">AM</th>
                <th colspan="2">PM</th>
                <th rowspan="2">Hours<br/>Worked</th>
            </tr>
            <tr>
                <th style="width: 15%;">In</th>
                <th style="width: 15%;">Out</th>
                <th style="width: 15%;">In</th>
                <th style="width: 15%;">Out</th>
            </tr>
        </thead>
        @foreach ($summarylogs as $summarylog)
            <tr>
                <td @if ($summarylog->remarks) rowspan="2" @endif>&nbsp; {{date('d', strtotime($summarylog->date))}} <span style="opacity: 0.4;">{{substr(date('l', strtotime($summarylog->date)),0,3)}}</span></td>
                <td style="text-align: center;">
                    @if (collect($summarylog->logs)->where('tapstate', 'IN')->where('ttime', '<', '12:00:00')->count() > 0)
                    <span style="opacity: 0.5;">{{date('h:i', strtotime(collect($summarylog->logs)->where('tapstate','IN')->where('ttime','<','12:00:00')->first()->ttime))}}</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if (collect($summarylog->logs)->where('tapstate', 'OUT')->where('ttime', '<', '12:00:00')->count() > 0)
                    <span style="opacity: 0.5;">{{date('h:i', strtotime(collect($summarylog->logs)->where('tapstate','OUT')->where('ttime','<','12:00:00')->first()->ttime))}}</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if (collect($summarylog->logs)->where('tapstate', 'IN')->where('ttime', '>', '11:59:00')->count() > 0)
                    <span style="opacity: 0.5;">{{date('h:i', strtotime(collect($summarylog->logs)->where('tapstate','IN')->where('ttime','>','11:59:00')->first()->ttime))}}</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if (collect($summarylog->logs)->where('tapstate', 'OUT')->where('ttime', '>', '11:59:00')->count() > 0)
                    <span style="opacity: 0.5;">{{date('h:i', strtotime(collect($summarylog->logs)->where('tapstate','OUT')->where('ttime','>','11:59:00')->last()->ttime))}}</span>
                    @endif
                </td>
                <td style="text-align: center;">
					<span style="opacity: 0.4;">{{$summarylog->hours}}h {{$summarylog->minutes}}m</span>
                </td>
            </tr>
            @if ($summarylog->remarks)
                <tr>
                    <td colspan="5" style="font-size: 9px;">Remarks: {{$summarylog->remarks->remarks}}</td>
                </tr>
            @endif
        @endforeach
        <tr>
            <td>&nbsp;</td>
            <td colspan="4" style="text-align: right;">Total Working Hours: </td>
            <td style="text-align: center;">
                @php
                    $totalhours = collect($summarylogs)->sum('hours');
                    $totalminutes = collect($summarylogs)->sum('minutes');

                    while($totalminutes>=60)
                    {
                        $totalhours+=1;
                        $totalminutes-=60;
                    }
                @endphp
                    <span style="opacity: 0.4;">{{$totalhours}}h {{$totalminutes}}m</span>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="padding-top: 20px; padding-bottom: 20px;">Signature: </td>
        </tr>
    </table> --}}
@php
    $overalltotalhours = 0;
    $overalltotalminutes = 0;
@endphp
<style>
    * {
        font-family: Arial, Helvetica, sans-serif;
    }

    table {
        border-collapse: collapse;
    }
</style>
@if (DB::table('schoolinfo')->first()->schoolid == '405014')
    <div style="width: 100%; text-align: center;">
        <img src='{{ base_path() }}/public/assets/images/hchscp/employee_attsummary_header.jpg' alt='school'
            width='45%'>
    </div>
    <div style="width: 100%; text-align: center; border-bottom: 2px solid black; line-height: 10px;">
        &nbsp;
    </div>
    <br />
@endif
<table style="width: 100%;">
    <tr style=" font-size: 13px;">
        <th colspan="5">DAILY TIME RECORD</th>
    </tr>
    <tr>
        <th><br /></th>
    </tr>
    <tr style=" font-size: 11px;">
        <td>Employee Name:</td>
        <td style="width: 35%; border-bottom: 1px solid black;">{{ $info->lastname }}, {{ $info->firstname }}
            {{ $info->middlename }} {{ $info->suffix }}</td>
        <td>Month Starting:</td>
        <td style="width: 20%; border-bottom: 1px solid black;"></td>
        <td style="width: 20%; "></td>
    </tr>
    <tr style=" font-size: 11px;">
        <td>Designation:</td>
        <td style="border-bottom: 1px solid black;">{{ $info->utype }}</td>
        <td>Month Ending:</td>
        <td style="border-bottom: 1px solid black;"></td>
        <td></td>
    </tr>
</table>
<br />
<table style="width: 100%; font-size: 11px;" border="1">
    <tr>
        <th style="width: 15%;">Date</th>
        <th style="width: 15%;">Day</th>
        <th>Time IN</th>
        <th>TIME OUT</th>
        <th>Ttl. Hours</th>
        @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
            <th>Ttl. Late</th>
        @endif
        <th>Remarks</th>
    </tr>
    {{-- @dd($summarylogs); --}}
    @foreach ($summarylogs as $summarylog)
        <tr>
            <td>{{ date('m/d/Y', strtotime($summarylog->date)) }}</td>
            <td>{{ date('l', strtotime($summarylog->date)) }}</td>
            {{-- <td style="text-align: center;">@if (collect($summarylog->logs)->where('tapstate', 'IN')->count() > 0)
                    {{date('h:i A', strtotime(collect($summarylog->logs)->where('tapstate','IN')->first()->ttime))}}
                    @endif
                </td>
                <td style="text-align: center;">@if (collect($summarylog->logs)->where('tapstate', 'OUT')->count() > 0)
                    {{date('h:i A', strtotime(collect($summarylog->logs)->where('tapstate','OUT')->last()->ttime))}}
                    @endif
                </td> --}}
            <td style="text-align: center;">
                {{ $summarylog->timeinam }}
            </td>
            <td style="text-align: center;">
                {{-- {{$summarylog->timeoutpm}} --}}
                @if ($summarylog->timeoutpm != null)
                    {{ date('h:i:s', strtotime($summarylog->timeoutpm)) }}
                @elseif($summarylog->timeinam != null && $summarylog->timeoutpm == null)
                    {{-- {{ $summarylog->timeoutam }} --}}
                    {{ date('h:i:s', strtotime($summarylog->timeoutam)) }}
                @endif
                {{-- {{$summarylog->timeoutpm != null ? date('h:i:s', strtotime($summarylog->timeoutpm)) : ''}} --}}
            </td>
            <td style="text-align: center;">
                @php

                    $totalhours = $summarylog->hours;
                    $totalminutes = $summarylog->minutes;

                    while ($totalminutes >= 60) {
                        $totalhours += 1;
                        $totalminutes -= 60;
                    }
                    $overalltotalhours += $totalhours;
                    $overalltotalminutes += $totalminutes;
                @endphp
                {{ floor($summarylog->totalworkinghours) }}h
                {{ number_format(($summarylog->totalworkinghours - floor($summarylog->totalworkinghours)) * 60, 0) }}m
            </td>
            @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
                <td style="text-align: center;">
                    {{-- {{$summarylog->latehours}}h{{$summarylog->lateminutes}}m --}}
                </td>
                <td>
                </td>
            @else
                <td>
                    {{ $summarylog->remarks ?? null }}
                </td>
            @endif
        </tr>
        @if ($summarylog->leaveremarks)
            <tr style="background-color: rgb(229, 239, 229);">
                <td colspan="6" style="font-size: 9px; text-align: left !important;">Leave:
                    {{ $summarylog->leaveremarks }} ({{ $summarylog->leavetype }})</td>
            </tr>
        @endif
        @if ($summarylog->holiday)
            <tr style="background-color: rgb(229, 239, 229);">
                <td colspan="6" style="font-size: 9px; text-align: left !important;">Holiday:
                    ({{ $summarylog->holidayname }})
                </td>
            </tr>
        @endif
    @endforeach
</table>
<table class="" style="width: 100%; font-size: 12px; padding-top: 10px;">
    <tr>
        <td class="" style="text-align: right">
            @php
                $totalhr = $overalltotalhours;
                $totalmin = $overalltotalminutes / 60;
                $totaltimecomputed = $totalhr + $totalmin;
                $wholeNumberPart = floor($totaltimecomputed);

                // Extract the decimal part
                $decimalPart = fmod($totaltimecomputed, 1);

                // Convert the decimal part to minutes
                $decimalMinutes = $decimalPart * 60;
            @endphp
            <b>TOTAL HOURS RENDERED: </b>{{ $wholeNumberPart }}hrs {{ $decimalMinutes }}mins
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
    </tr>
</table>
@if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
    @php

        while ($overalltotalminutes >= 60) {
            $overalltotalhours += 1;
            $overalltotalminutes -= 60;
        }
    @endphp
    <table style="width: 100%; font-size: 11px;">
        <tr>
            <td colspan="2" style="width: 20%;">Actions to be Taken</td>
            <td colspan="5" style="border-bottom: 1px solid black;"></td>
        </tr>
        <tr>
            <td colspan="7">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">Employee Signature:</td>
            <td style="width: 30%; border-bottom: 1px solid black;"></td>
            <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;Total Hours:</td>
            <td colspan="2" style="border-bottom: 1px solid black;">{{ $overalltotalhours }}h
                {{ $overalltotalminutes }}m</td>
        </tr>
        <tr>
            <td colspan="7">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" rowspan="2">Prepared By:</td>
            <td style="width: 30%; border-bottom: 1px solid black; text-align: center;"></td>
            <td colspan="2" rowspan="2" style="vertical-align: bottom;">&nbsp;&nbsp;&nbsp;&nbsp;Total Late Hours:
            </td>
            <td colspan="2" rowspan="2" style="border-bottom: 1px solid black;"></td>
        </tr>
        <tr>
            <td style="font-size: 9px;">DTR Monitoring In-Charge /
                Principal's Office Clerk</td>
        </tr>
    </table>
@endif
