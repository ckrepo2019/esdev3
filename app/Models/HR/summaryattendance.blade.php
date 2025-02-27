
@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
    @if(count($employees)>0)
        @foreach($employees as $employee)
        
            @php
                $overalltotalhours = 0;
                $overalltotalminutes = 0;
            @endphp
            <style>
                * {
                    font-family: Arial, Helvetica, sans-serif;
                }
                table{
                    border-collapse: collapse;
                }
            </style>
            <div style="width: 100%; text-align: center;">
                    <img src='{{base_path()}}/public/assets/images/hchscp/employee_attsummary_header.jpg' alt='school' width='45%'>
            </div>
            <div style="width: 100%; text-align: center; border-bottom: 2px solid black; line-height: 10px;">
                &nbsp;
            </div>
            <br/>
            <table style="width: 100%;">
                <tr style=" font-size: 13px;">
                    <th colspan="5">DAILY TIME RECORD</th>
                </tr>
                <tr>
                    <th><br/></th>
                </tr>
                <tr style=" font-size: 11px;">
                    <td>Employee Name:</td>
                    <td style="width: 35%; border-bottom: 1px solid black;">{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}}</td>
                    <td>Month Starting:</td>
                    <td style="width: 20%; border-bottom: 1px solid black;"></td>
                    <td style="width: 20%; "></td>
                </tr>
                <tr style=" font-size: 11px;">
                    <td>Designation:</td>
                    <td style="border-bottom: 1px solid black;">{{$employee->utype}}</td>
                    <td>Month Ending:</td>
                    <td style="border-bottom: 1px solid black;"></td>
                    <td></td>
                </tr>
            </table>
            <br/>
            <table style="width: 100%; font-size: 11px;" border="1">
                <tr>
                    <th style="width: 15%;">Date</th>
                    <th style="width: 15%;">Day</th>
                    <th>Time IN</th>
                    <th>TIME OUT</th>
                    <th>Ttl. Hours</th>
                    <th>Ttl. Late</th>
                    <th>Remarks</th>
                </tr>
                @foreach($employee->logs as $summarylog)
                    <tr>
                        <td>{{date('m/dY', strtotime($summarylog->date))}}</td>
                        <td>{{date('l', strtotime($summarylog->date))}}</td>
                        <td style="text-align: center;">@if(collect($summarylog->logs)->where('tapstate','IN')->count()>0)
                            {{date('h:i A', strtotime(collect($summarylog->logs)->where('tapstate','IN')->first()->ttime))}}
                            @endif
                        </td>
                        <td style="text-align: center;">@if(collect($summarylog->logs)->where('tapstate','OUT')->count()>0)
                            {{date('h:i A', strtotime(collect($summarylog->logs)->where('tapstate','OUT')->last()->ttime))}}
                            @endif
                        </td>
                        <td style="text-align: center;">
                        @php
                        
                            $totalhours = $summarylog->hours;
                            $totalminutes = $summarylog->minutes;

                            while($totalminutes>=60)
                            {
                                $totalhours+=1;
                                $totalminutes-=60;
                            }
                            $overalltotalhours+=$totalhours;
                            $overalltotalminutes+=$totalminutes;
                        @endphp
                            {{$totalhours}}h {{$totalminutes}}m
                        </td>
                        <td style="text-align: center;">
                            
                        </td>
                        <td></td>
                    </tr>
                @endforeach
            </table>

            @php
                            

                while($overalltotalminutes>=60)
                {
                    $overalltotalhours+=1;
                    $overalltotalminutes-=60;
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
                    <td colspan="2" style="border-bottom: 1px solid black;">{{$overalltotalhours}}h {{$overalltotalminutes}}m</td>
                </tr>
                <tr>
                    <td colspan="7">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="2">Prepared By:</td>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;"></td>
                    <td colspan="2" rowspan="2" style="vertical-align: bottom;">&nbsp;&nbsp;&nbsp;&nbsp;Total Late Hours:</td>
                    <td colspan="2" rowspan="2" style="border-bottom: 1px solid black;"></td>
                </tr>
                <tr>
                    <td style="font-size: 9px;">DTR Monitoring In-Charge /
                        Principal's Office Clerk</td>
                </tr>
            </table>
            <div style="page-break-before: always;"></div>
        @endforeach
    @endif
@else
    <style>
        @page{
            /* size: 11in 8.5in; */
            size: 8.5in 11in;
            padding: 0px;
            margin: 10px 5px;
        }
        * {
            font-family: Arial, Helvetica, sans-serif;
        }
        table{
            border-collapse: collapse;
        }
        td{
            text-align: center;
        }
    </style>
    <table style="width: 100%; margin: 0px;">
        <thead>
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
        <tr style="font-size: 12px; font-weight: bold; text-align: left !important;">
            <td>{{date('M d', strtotime($datefrom))}} - {{date('d, Y', strtotime($dateto))}}</td>
        </tr>
    </table>
    @if(count($employees)>0)
        <table style="width: 100%;">
            @foreach($employees as $employee)
                <tr>
                    @if(count($employee)>0)
                        @foreach($employee as $emp)
                            <td style="vertical-align: top;">
                                <table style="width: 100%;" border="1">
                                    <thead style="font-size: 10px;">
                                        <tr>
                                            <th colspan="7">{{$emp->lastname}}, {{$emp->firstname}}</th>
                                        </tr>
                                        <tr>
                                            <th style="width: 15%;">Date</th>
                                            <th>AM.I</th>
                                            <th>AM.O</th>
                                            <th>PM.I</th>
                                            <th>PM.O</th>
                                            <th style="width: 16%;">L</th>
                                            <th style="width: 15%;">TWH</th>
                                        </tr>
                                    </thead>
                                        @if(count($emp->logs)>0)
                                            @foreach($emp->logs as $log)
                                                @php
                                                    $morningtapp = 0;
                                                    $finalamin = '08:00:00';
                                                    $finalamout = '12:00:00';
                                                    $finalpmin = '13:00:00';
                                                    $finalpmout = '17:00:00';
                                                @endphp
                                                <tr style="font-size: 10px;">
                                                    <td style="text-align: left;" @if($log->remarks) rowspan="2" @endif>{{date('d D', strtotime($log->wholedate))}}</td>
                                                    <td>
                                                        {{$log->amin}}
                                                        {{-- @if(count($log->logs)>0)
                                                            @if(collect($log->logs)->where('tapstate','IN')->where('ttime','<=','12:00:00')->count() > 0)
                                                                {{date('h:i', strtotime(collect($log->logs)->where('tapstate','IN')->where('ttime','<=','12:00:00')->first()->ttime))}}
                                                                @php
                                                                    $morningtapp+=1;
                                                                    $finalamin = collect($log->logs)->where('tapstate','IN')->where('ttime','<=','12:00:00')->first()->ttime;
                                                                @endphp
                                                            @endif
                                                        @endif --}}
                                                    </td>
                                                    <td>
                                                        {{$log->amout}}
                                                        {{-- @if(count($log->logs)>0)
                                                            @if(collect($log->logs)->where('ttime','<=',$finalpmin)->count() == 0)
                                                                @if(collect($log->logs)->where('tapstate','OUT')->where('ttime','<=',$finalpmin)->count() == 0)
                                                                    <span style="opacity: 0.4;">{{date('h:i', strtotime($emp->amout ?? $log->timeoutam))}}</span>
                                                                    @php
                                                                        $finalamout = $emp->amout ?? $log->timeoutam;
                                                                    @endphp
                                                                @else
                                                                    {{date('h:i', strtotime(collect($log->logs)->where('tapstate','OUT')->where('ttime','<=',$finalpmin)->last()->ttime))}}
                                                                    @php
                                                                        $finalamout = collect($log->logs)->where('tapstate','OUT')->where('ttime','<=',$finalpmin)->last()->ttime;
                                                                    @endphp
                                                                @endif
                                                            @else
                                                                @if(collect($log->logs)->where('tapstate','OUT')->where('ttime','<=',$finalpmin)->count() == 0)
                                                                    <span style="opacity: 0.4;">{{date('h:i', strtotime($emp->amout ?? $log->timeoutam))}}</span>
                                                                    @php
                                                                        $finalamout = $emp->amout ?? $log->timeoutam;
                                                                    @endphp
                                                                @else
                                                                    {{date('h:i', strtotime(collect($log->logs)->where('tapstate','OUT')->where('ttime','<=',$finalpmin)->last()->ttime))}}
                                                                    @php
                                                                        $finalamout = collect($log->logs)->where('tapstate','OUT')->where('ttime','<=',$finalpmin)->last()->ttime;
                                                                    @endphp
                                                                @endif
                                                            @endif
                                                        @endif --}}
                                                    </td>
                                                    <td>
                                                        {{$log->pmin != null ? date('h:i:s', strtotime($log->pmin)) : ''}}
                                                        {{-- @if(count($log->logs)>0)
                                                            @if(collect($log->logs)->where('ttime','>=',$finalamout)->count() == 0)
                                                                @if(collect($log->logs)->where('tapstate','IN')->where('ttime','>=',$finalamout)->count() == 0)
                                                                    <span style="opacity: 0.4;">{{date('h:i', strtotime($emp->pmin))}}</span>
                                                                    @php
                                                                        $finalpmin = $emp->pmin;
                                                                    @endphp
                                                                @else
                                                                    {{date('h:i', strtotime(collect($log->logs)->where('tapstate','IN')->where('ttime','>=',$finalamout)->first()->ttime))}}
                                                                    @php
                                                                        $finalpmin = collect($log->logs)->where('tapstate','IN')->where('ttime','>=',$finalamout)->first()->ttime;
                                                                    @endphp
                                                                @endif
                                                            @else
                                                                @if(collect($log->logs)->where('tapstate','IN')->where('ttime','>=',$finalamout)->count() == 0)
                                                                    <span style="opacity: 0.4;">{{date('h:i', strtotime($emp->pmin ?? $log->timeinpm))}}</span>
                                                                    @php
                                                                        $finalpmin = $emp->pmin ?? $log->timeinpm;
                                                                    @endphp
                                                                @else
                                                                    {{date('h:i', strtotime(collect($log->logs)->where('tapstate','IN')->where('ttime','>=',$finalamout)->first()->ttime))}}
                                                                    @php
                                                                        $finalpmin = collect($log->logs)->where('tapstate','IN')->where('ttime','>=',$finalamout)->first()->ttime;
                                                                    @endphp
                                                                @endif
                                                            @endif
                                                        @endif --}}
                                                    </td>
                                                    <td>
                                                        {{$log->pmout != null ? date('h:i:s', strtotime($log->pmout)) : ''}}
                                                        {{-- @if(count($log->logs)>0)
                                                            @if(collect($log->logs)->where('ttime','>=',$finalpmin)->count() == 0)
                                                                    <span style="opacity: 0.4;">{{date('h:i', strtotime($finalpmout))}}</span>
                                                            @else
                                                                @if(collect($log->logs)->where('tapstate','OUT')->where('ttime','>=',$finalpmin)->count() == 0)
                                                                    <span style="opacity: 0.4;">{{date('h:i', strtotime($emp->pmout ?? $log->timeoutpm))}}</span>
                                                                @else
                                                                    {{date('h:i', strtotime(collect($log->logs)->where('tapstate','OUT')->where('ttime','>=',$finalpmin)->last()->ttime))}}
                                                                @endif
                                                            @endif
                                                        @endif --}}
                                                    </td>
                                                    <td>
                                                        {{$log->latehours}}h{{$log->lateminutes}}m
                                                    </td>
                                                    <td>
                                                        @if($log->hours > 0) <span style="opacity: 0.8;">{{$log->hours}}h @endif @if($log->hours > 0){{$log->minutes}}m </span>@endif
                                                        {{-- @if(strtolower(date('l', strtotime($log->date))) == 'sunday')
                                                        @else
                                                            @if($log->hours > 0) <span style="opacity: 0.8;">{{$log->hours}}h @endif @if($log->hours > 0){{$log->minutes}}m </span>@endif
                                                        @endif --}}
                                                    </td>
                                                </tr>
                                                @if($log->remarks)
                                                    <tr>
                                                        <td colspan="6" style="font-size: 9px; text-align: left !important;">Remarks: {{$log->remarks}}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    <tr style="font-size: 10px;">
                                        <td colspan="5" style="text-align: center;">T O T A L</td>
                                        <td>
                                            @php
                                                $totallatehours = collect($emp->logs)->sum('latehours');
                                                $totallateminutes = collect($emp->logs)->sum('lateminutes');
                                                while($totallateminutes>=60)
                                                {
                                                    $totallatehours+=1;
                                                    $totallateminutes-=60;
                                                }
                                            @endphp
                                            {{$totallatehours}}h {{$totallateminutes}}m
                                        </td>
                                        <td>
                                            @php
                                                $totalworkedhours = collect($emp->logs)->sum('hours');
                                                $totalworkedminutes = collect($emp->logs)->sum('minutes');
                                                while($totalworkedminutes>=60)
                                                {
                                                    $totalworkedhours+=1;
                                                    $totalworkedminutes-=60;
                                                }
                                            @endphp
                                            {{$totalworkedhours}}h {{$totalworkedminutes}}m
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <td style="line-height: 5px;">&nbsp;</td>
                </tr>
            @endforeach
        </table>
    @endif
@endif
{{-- @else
<style>
    @page{
        /* size: 11in 8.5in; */
        size: 11in 8.5in ;
        padding: 0px;
        margin-top: 20px;
    }
    * {
        font-family: Arial, Helvetica, sans-serif;
    }
    table{
        border-collapse: collapse;
    }
    td{
        text-align: center;
    }
</style>
<table style="width: 100%; margin: 0px; font-size: 12px;">
<thead>
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
    <td style="text-align: center; font-size: 25px">Daily Time Record</td>
</tr>
<tr style="font-size: 12px; font-weight: bold; text-align: left !important;">
    <td>{{date('M d', strtotime($datefrom))}} - {{date('d, Y', strtotime($dateto))}}</td>
</tr>
</table>
@if(count($alldays)>0)
    @foreach($alldays as $eachkey => $eachchunk)
        <table style="width: 100%; font-size: 10px; margin-bottom: 10px; page-break-inside: always;" border="1">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 30%;">Employee</th>
                    @foreach($eachchunk as $dateval)
                            <th colspan="3" style="font-size: 10px;">
                                {{date('m/d/Y', strtotime($dateval))}}
                                <br/>
                                {{date('l', strtotime($dateval))}}
                            </th>
                    @endforeach
                </tr>
                <tr style="font-size: 10px; font-size: 10px;">
                    @foreach($eachchunk as $dateval)
                        <th >IN</th>
                        <th >OUT</th>
                        <th>T</th>
                    @endforeach
                </tr>
            </thead>
            @if(count($employees)==0)
                    <tr>
                        <td colspan="{{count($eachchunk)*5}}">No logs for the selected days</td>
                    </tr>
            @else
                @foreach($employees as $employee)
                    <tr style="font-size: 10px;">
                        <td style="text-align: left !important;">{{$employee->lastname}}, {{$employee->firstname}}</td>
                        @foreach(collect($employee->logs) as $logvalue)
                            @php
                                $logvalue->logs = collect($logvalue->logs)->sortBy('ttime')->values();
                            @endphp
                            @foreach($eachchunk as $dateval)
                                @if($logvalue->date == $dateval)
                                    <td>
                                        @if(collect($logvalue->logs)->where('tapstate','IN')->where('ttime','<=','12:00:00')->count() > 0)
                                            {{date('h:i', strtotime(collect($logvalue->logs)->where('tapstate','IN')->where('ttime','<=','12:00:00')->first()->ttime))}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(collect($logvalue->logs)->where('tapstate','OUT')->where('ttime','>','12:00:00')->count() > 0)
                                            {{date('h:i', strtotime(collect($logvalue->logs)->where('tapstate','OUT')->where('ttime','>','12:00:00')->last()->ttime))}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(strtolower(date('l', strtotime($logvalue->date))) == 'sunday')
                                        @else
                                            @if($logvalue->hours > 0) <span style="opacity: 0.4;">{{$logvalue->hours}}h @endif @if($logvalue->hours > 0) {{$logvalue->minutes}}m </span>@endif
                                        @endif
                                    </td>
                                @endif
                            @endforeach
                        @endforeach
                    </tr>
                @endforeach
            @endif
        </table>
    @endforeach
@endif
@endif --}}