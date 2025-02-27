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
        {{-- <td>{{date('M d', strtotime($datefrom))}} - {{date('d, Y', strtotime($dateto))}}</td> --}}
    </tr>
</table>
@if(count($employees)>0)
    <table style="width: 100%;">
        @foreach($employees as $employee)
            <tr>
                <td width="100%" style="vertical-align: top;">
                    <table style="width: 100%;" border="1">
                        <thead style="font-size: 10px;">
                            <tr>
                                <th colspan="8">{{$employee->lastname}}, {{$employee->firstname}}</th>
                            </tr>
                            <tr>
                                <th style="text-align: left; width: 15%;">&nbsp;Date</th>
                                <th>AM.I</th>
                                <th>AM.O</th>
                                <th>PM.I</th>
                                <th>PM.O</th>
                                {{-- <th>L AM</th>
                                <th>L PM</th> --}}
                                <th width="10%">L</th>
                                {{-- <th>U AM</th>
                                <th>U PM</th> --}}
                                <th width="10%">U</th>
                                {{-- <th>AM T</th>
                                <th>PM T</th> --}}
                                <th>TWH</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        @if(count($employee->logs) > 0)
                            @foreach($employee->logs as $log)
                                @if ($log->day != 'Sunday')
                                    @php
                                        $morningtapp = 0;
                                        $finalamin = '08:00:00';
                                        $finalamout = '12:00:00';
                                        $finalpmin = '13:00:00';
                                        $finalpmout = '17:00:00';
                                    @endphp
                                    
                                    <tr  style="font-size: 10px;">
                                        <td style="text-align: left;" @if($log->remarks) rowspan="2" @endif>
                                            &nbsp;{{$log->dateint}}&nbsp;{{date('M', strtotime($log->date))}}&nbsp;{{date('Y', strtotime($log->date))}}&nbsp;{{date('D', strtotime($log->date))}}
                                        </td>
                                        <td>
                                            {{$log->timeinam}}
                                        </td>
                                        <td>
                                            @if ($log->timeoutam != null || $log->timeoutpm != null)
                                                {{$log->timeoutam}}
                                            @endif
                                        </td>
                                        <td>
                                            {{$log->timeinpm != null ? date('h:i:s', strtotime($log->timeinpm)) : ''}}
                                        </td>
                                        <td>
                                            {{$log->timeoutpm != null ? date('h:i:s', strtotime($log->timeoutpm)) : ''}}
                                        </td>
                                        {{-- <td>{{ $log->lateamhours == 0 ? '' : $log->lateamhours }}</td>
                                        <td>{{ $log->latepmhours == 0 ? '' : $log->latepmhours }}</td> --}}
                                        <td>
                                            @if ($log->totalworkinghours > 0)
                                                @php
                                                    $totalMinutes = $log->latehours;
                                                    $hours = intdiv($totalMinutes, 60);
                                                    $minutes = $totalMinutes % 60;
                                                @endphp
        
                                                @if ($totalMinutes > 0)
                                                    {{ "{$hours}h {$minutes}m" }}
                                                @endif
                                            @endif
                                        </td>
                                        {{-- <td>{{$log->undertimeamhours == 0 ? '' : $log->undertimeamhours }}</td>
                                        <td>{{$log->undertimepmhours == 0 ? '' : $log->undertimepmhours }}</td> --}}
                                        <td>
                                            @if ($log->totalworkinghours > 0)
                                                @php
                                                    $totalMinutes = $log->undertimehours;
                                                    $hours = intdiv($totalMinutes, 60);
                                                    $minutes = $totalMinutes % 60;
                                                @endphp
                
                                                @if ($totalMinutes > 0)
                                                    {{ "{$hours}h {$minutes}m" }}
                                                @endif
                                            @endif
                                        </td>
                                        {{-- <td>{{$log->amtotalminutes == 0 ? '' : $log->amtotalminutes }}</td>
                                        <td>{{$log->pmtotalminutes == 0 ? '' : $log->pmtotalminutes }}</td> --}}
                                        <td style="">
                                            @php
                                                $totalMinutes = $log->totalworkinghours;
                                                $hours = intdiv($totalMinutes, 60);
                                                $minutes = $totalMinutes % 60;
                                            @endphp
            
                                            @if ($totalMinutes > 0)
                                                {{ "{$hours}h {$minutes}m" }}
                                            @else
                                                <span style="color: red">{{ "A" }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($log->remarks)
                                        <tr>
                                            <td colspan="8" style="font-size: 9px; text-align: left !important;">Remarks: {{$log->remarks}}</td>
                                        </tr>
                                    @endif
                                    @if($log->leaveremarks)
                                        <tr style="background-color: rgb(229, 239, 229);">
                                            <td colspan="8" style="font-size: 9px; text-align: left !important;">Leave: {{$log->leaveremarks}} ({{$log->leavetype}})</td>
                                        </tr>
                                    @endif
                                    @if($log->holiday)
                                        <tr style="background-color: rgb(229, 239, 229);">
                                            <td colspan="8" style="font-size: 9px; text-align: left !important;">Holiday: ({{$log->holidayname}})</td>
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                        
                        <tr style="font-size: 10px;">
                            <td colspan="5" style="text-align: center;">
                                <span>
                                    <b>T O T A L :
                                        <span>
                                            @php
                                                $totaldays = collect($employee->logs)->where('day', '!=', 'Sunday')->count();
                                            @endphp
                                            {{$totaldays}} X {{ $log->customworkinghours }} = {{ $totaldays * $log->customworkinghours }} hours
                                        </span>
                                    </b>
            
                                <span style="color: rgba(243, 12, 12, 0.879)">
                                <b>
                                        A B S E N T :
                                        @php
                                            $totalabsent = collect($employee->logs)->where('totalworkinghours', 0)->where('day', '!=', 'Sunday')->count();
                                        @endphp
            
                                        {{$totalabsent * $log->customworkinghours}} HOURS
                                    </b>
                                </span>
                            </td>
                            {{-- <td></td>
                            <td></td> --}}
                            <td>
                                @php
                                    $totalMinutes = collect($employee->logs)->where('day', '!=', 'Sunday')->where('totalworkinghours','!=', 0)->sum('latehours');
                                    $hours = intdiv($totalMinutes, 60);
                                    $minutes = $totalMinutes % 60;
                                @endphp
            
                                {{ $hours }}h {{ $minutes }}m
                            </td>
                            {{-- <td></td>
                            <td></td> --}}
                            <td>
                                @php
                                    $totalMinutes = collect($employee->logs)->sum('undertimehours');
                                    $hours = intdiv($totalMinutes, 60);
                                    $minutes = $totalMinutes % 60;
                                @endphp
            
                                {{ $hours }}h {{ $minutes }}m
                            </td>
                            {{-- <td></td>
                            <td></td> --}}
                            <td>
                                @php
                                    $totalMinutes = collect($employee->logs)->where('day', '!=', 'Sunday')->sum('totalworkinghours');
                                    $hours = intdiv($totalMinutes, 60);
                                    $minutes = $totalMinutes % 60;
                                @endphp
            
                                {{ $hours }}h {{ $minutes }}m
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table style="width: 100%; margin-top: 2em; margin-bottom: 2em; font-size: 15px;">
                    <tr>
                        <td style="text-align: left"><b>SUNDAY : </b>
                        
                            @php
                                $totalMinutes = collect($employee->logs)->where('day', '=', 'Sunday')->sum('totalworkinghours');
                                $hours = intdiv($totalMinutes, 60);
                                $minutes = $totalMinutes % 60;
                            @endphp
        
                            {{ $hours }}h {{ $minutes }}m
                        </td>
                    </tr>
                </table>
                </td>
            </tr>
        @endforeach
    </table>
@endif
