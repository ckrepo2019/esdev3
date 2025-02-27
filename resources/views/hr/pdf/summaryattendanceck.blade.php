
<style>
    @page {
        /* size: 11in 8.5in; */
        size: 8.5in 11in;
        padding: 0px;
        margin: 30px 30px;
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
</style>
{{-- @dd($employees); --}}
@if (count($employees) > 0)
    @foreach ($employees as $employee)
        @php
            $overalltotalhours = 0;
            $overalltotalminutes = 0;
        @endphp
        <div class="row watermark">
            <div class="col-md-12">
                <img src="{{ base_path() }}/public/{{ DB::table('schoolinfo')->first()->picurl }}"
                    alt="school" width="400px">
            </div>
        </div>
        <table style="width: 100%;">
            <tr>
                <td style="width: 20%; text-align: left">

                </td>
                <td style="width: 60%;">
                    <table>
                        <tr style=" font-size: 16px;">
                            <th style="color: #e83e8c;">CK CHILDREN'S PUBLISHING</th>
                        </tr>
                        <tr style=" font-size: 16px;">
                            <th>DAILY TIME RECORD</th>
                        </tr>
                        <tr>
                            <td style=" font-size: 10px; text-align: center;">
                                <span>“YOUR ACCESS TO VISUAL LEARNING AND INTEGRATION”</span><br>
                                <span>Old Road, Tipolohon, Upper Camaman-an, Cagayan de Oro City, Misamis
                                    Oriental</span><br>
                                <span>Email: ckcpublishingofficial@gmail.com | Website: www.ckgroup.ph | FB:
                                    CK
                                    Children’s Publishing</span><br>
                                <span>Contact/s: +63-917-718-7665 (Globe) | +63-939-939-5643
                                    (Smart)
                                </span><br>

                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 20%;">

                </td>
            </tr>
        </table>
        {{-- @dd($employee); --}}
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
        <table style="width: 100%; font-size: 13px; margin-top: 20px;" border="1">
            <tr>
                <th style="width: 15%;">Date</th>
                <th style="width: 15%;">Day</th>
                <th>Time IN</th>
                <th>TIME OUT</th>
                <th>Total Hours</th>
                @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hchs')
                    <th>Ttl. Late</th>
                @endif
                <th>Remarks</th>
            </tr>
            
            @foreach ($employee->logs as $summarylog)
            {{-- @dd($summarylog); --}}
                <tr>
                    <td>{{ date('m/d/Y', strtotime($summarylog->date)) }}</td>
                    <td>{{ date('l', strtotime($summarylog->date)) }}</td>

                    <td style="text-align: center;">
                        {{ $summarylog->timeinam }}
                    </td>
                    <td style="text-align: center;">
                        @if ($summarylog->timeoutpm != null)
                            {{ date('h:i:s', strtotime($summarylog->timeoutpm)) }}
                        @elseif($summarylog->timeinam != null && $summarylog->timeoutpm == null)
                            @if ($summarylog->timeoutam == null)
                                {{ $summarylog->timeoutam }}
                            @else
                                {{ date('h:i:s', strtotime($summarylog->timeoutam)) }}
                            @endif
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
                        <td colspan="6" style="font-size: 9px; text-align: left !important;">
                            Leave:
                            {{ $summarylog->leaveremarks }} ({{ $summarylog->leavetype }})
                        </td>
                    </tr>
                @endif
                @if ($summarylog->holiday)
                    <tr style="background-color: rgb(229, 239, 229);">
                        <td colspan="6" style="font-size: 9px; text-align: left !important;">
                            Holiday:
                            ({{ $summarylog->holidayname }})
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>
        <table class=""
            style="width: 100%; font-size: 13px; padding-top: 10px; page-break-after: always;">
            <tr>
                <td class="" style="text-align: right">
                    @php
                        $totalhr = $overalltotalhours;
                        $totalmin = $overalltotalminutes / 60;
                        $totaltimecomputed = $totalhr + $totalmin;
                        $wholeNumberPart = floor($totaltimecomputed);

                        $decimalPart = fmod($totaltimecomputed, 1);

                        $decimalMinutes = $decimalPart * 60;
                    @endphp
                    <b>TOTAL HOURS RENDERED: </b>{{ $wholeNumberPart }}hrs
                    {{ $decimalMinutes }}mins
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
            </tr>
        </table>
    @endforeach
@endif

