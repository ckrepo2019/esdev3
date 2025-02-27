<html>
    <head>
        <style>
            @page{
                margin: 0.5in 0.5in;
                font-family: Arial, Helvetica, sans-serif;
            }
            table {
                border-collapse: collapse;
            }
            *{
                font-size: 14px;
            }
        </style>
    </head>
    <body>
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center;">{{DB::table('schoolinfo')->first()->schoolname}}</td>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 11px;">{{DB::table('schoolinfo')->first()->address}}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 15px; font-weight: bold;">Technical Vocational<br/>Enrollment Report</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </table>
        <table style="width: 100%; table-layout: fixed;" border="1">
            @foreach($courses as $course)
                <tr>
                    <td colspan="6" style=" font-weight: bold;">Course : {{$course->description}}</td>
                </tr>
                @if(count($course->batches) > 0)
                    @foreach($course->batches as $eachbatch)
                        <tr style=" font-weight: bold;">
                            <td colspan="6">Batch : {{date('M d, Y',strtotime($eachbatch->startdate))}} - {{date('M d, Y',strtotime($eachbatch->enddate))}}</td>
                        </tr>
                        @if(count($eachbatch->students) > 0)
                            @php
                                $maxcount = max(collect($eachbatch->students)->where('gender','male')->count(), collect($eachbatch->students)->where('gender','female')->count());

                                $malestudents = collect($eachbatch->students)->where('gender','male')->values();
                                $femalestudents = collect($eachbatch->students)->where('gender','female')->values();
                            @endphp
                            <tr style="text-align: center; font-weight: bold;">
                                <td colspan="3">MALE</td>
                                <td colspan="3">FEMALE</td>
                            </tr>
                            @for($x = 0; $x < $maxcount; $x++)
                                <tr style="font-size: 11px !important;">
                                    <td style="width: 5%; text-align: center;">@if(isset($malestudents[$x])){{$x+1}}@endif</td>
                                    <td style="width: 10%; text-align: center;">@if(isset($malestudents[$x])) {{$malestudents[$x]->sid}} @endif</td>
                                    <td>
                                        @if(isset($malestudents[$x]))
                                            {{$malestudents[$x]->lastname}}, {{$malestudents[$x]->firstname}} {{$malestudents[$x]->middlename}} {{$malestudents[$x]->suffix}}
                                        @endif
                                    </td>
                                    <td style="width: 5%; text-align: center;">@if(isset($femalestudents[$x])) {{$x+1}}@endif</td>
                                    <td style="width: 10%; text-align: center;">@if(isset($femalestudents[$x])) {{$femalestudents[$x]->sid}} @endif</td>
                                    <td>
                                        @if(isset($femalestudents[$x]))
                                            {{$femalestudents[$x]->lastname}}, {{$femalestudents[$x]->firstname}} {{$femalestudents[$x]->middlename}} {{$femalestudents[$x]->suffix}}
                                        @endif
                                    </td>
                                </tr>
                            @endfor
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    Total
                                </td>
                                <td colspan="4">&nbsp;&nbsp;&nbsp;{{count($eachbatch->students)}}</td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </table>
    </body>
</html>