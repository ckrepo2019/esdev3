<style>
    @page {
        size: 8.5in 11in;
        margin: 10px;
    }

    .watermark {
        opacity: .09;
        position: absolute;
        left: 21%;
        bottom: 48%;
    }

    * {
        font-family: Arial, Helvetica, sans-serif;
    }

    table {
        border-collapse: collapse;
    }

    td {
        text-align: center;
    }
    .employee-table {
        page-break-after: always;
    }
</style>

@if (count($employees) > 0)
    @foreach ($employees as $employee)
        <div class="employee-table">
            @php
                $overalltotalhours = 0;
                $overalltotalminutes = 0;
            @endphp
            <div class="row watermark">
                <div class="col-md-12">
                    <img src="{{ base_path() }}/public/{{ DB::table('schoolinfo')->first()->picurl }}" alt="school" width="400px">
                </div>
            </div>
            <table style="width: 100%;">
                <tr>
                    <td style="width: 20%; text-align: left"></td>
                    <td style="width: 60%;">
                        <table>
                            <tr style="font-size: 16px;">
                                <th style="color: #e83e8c;">CK CHILDREN'S PUBLISHING</th>
                            </tr>
                            <tr style="font-size: 16px;">
                                <th>DAILY TIME RECORD</th>
                            </tr>
                            <tr>
                                <td style="font-size: 10px; text-align: center;">
                                    <span>“YOUR ACCESS TO VISUAL LEARNING AND INTEGRATION”</span><br>
                                    <span>Old Road, Tipolohon, Upper Camaman-an, Cagayan de Oro City, Misamis Oriental</span><br>
                                    <span>Email: ckcpublishingofficial@gmail.com | Website: www.ckgroup.ph | FB: CK Children’s Publishing</span><br>
                                    <span>Contact/s: +63-917-718-7665 (Globe) | +63-939-939-5643 (Smart)</span><br>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 20%;"></td>
                </tr>
            </table>

            <table style="width: 100%; margin-top: 20px;">
                <tr style=" font-size: 14px; text-align: left;">
                    <td rowspan="2" style="width: 10%;">
                        <div class="table-avatar">
                            @php
                                $number = rand(1, 3);
                                if (strtoupper($employee->gender) == 'FEMALE') {
                                    $avatar = 'avatar/T(F) ' . $number . '.png';
                                } else {
                                    $avatar = 'avatar/T(M) ' . $number . '.png';
                                }
                            @endphp
                            <a href="#" class="avatar">
                                <img src="{{ asset($employee->picurl) }}" alt=""
                                    onerror="this.onerror = null, this.src='{{ asset($avatar) }}'"
                                    style="width: 50px; height: 50px; position: absolute; top: -18; border-radius: 10px;" />
                            </a>
    
                        </div>
                    </td>
                    <td style="width: 20%; text-align: left;"><b>Employee Name:</b></td>
                    <td
                        style="width:
                        30%; border-bottom: 1px solid black; text-align: left;">
                        {{ $employee->lastname }},
                        {{ $employee->firstname }}
                        {{ $employee->middlename }} {{ $employee->suffix }}</td>
                    <td style="width: 20%; text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;<b>Month Starting:</b>
                    </td>
                    <td style="width: 20%; border-bottom: 1px solid black; text-align: left;"></td>
                </tr>
                <tr style=" font-size: 14px;">
                    <td style="text-align: left;"><b>Designation:</b></td>
                    <td style="border-bottom: 1px solid black; text-align: left;">{{ $employee->utype }}</td>
                    <td style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;<b>Month Ending:</b></td>
                    <td style="border-bottom: 1px solid black; text-align: left;"></td>
                </tr>
            </table>

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
        <table style="width: 100%; margin-top: 2em; font-size: 15px;">
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
        </div>
    @endforeach
@endif

