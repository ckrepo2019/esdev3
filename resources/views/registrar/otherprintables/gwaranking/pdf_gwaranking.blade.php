<html>
    <header>
        <style>
            @page{
                margin: 0.5in 0.5in;
                size: 8.5in 13in
            }
            td, th{
                padding: 0px;
            }
            * {
                font-family: Arial, Helvetica, sans-serif;
            }
            table{
                
                border-collapse: collapse;
            }
        </style>
    </header>
    @php
        $numberofpages = count(array_chunk(collect($students)->toArray(),50));
    @endphp
    <body>
        <table style="width: 100%;">
            <tr style=" font-size:13px;  text-align: center;">
                <td colspan="3">{{DB::table('schoolinfo')->first()->schoolname}}</td>
            </tr>
            <tr style=" font-size:10px;  text-align: center;">
                <td colspan="3">{{DB::table('schoolinfo')->first()->address}}</td>
            </tr>
            <tr style=" font-size:13px;  text-align: center; font-weight: bold;">
                <td colspan="3" style="padding-top: 5px;">GRADE WEIGHTED AVERAGE RANKING</td>
            </tr>
            <tr style="text-align: right; font-size:13px;">
                <td colspan="3">Print Date: {{date('m/d/Y')}}</td>
            </tr>
            <tr style="font-size:12px;">
                <td>School Year: {{$sydesc}}</td>
                <td>Month: {{$monthname}}</td>
                <td></td>
            </tr>
        </table>
        <br/>
        <table style="width: 100%; font-size: 10px;">
            <thead>
                <tr>
                    <th style="width: 35%; border-bottom: 1px solid black; text-align: left;">STUDENT NAME</th>
                    <th style="width: 10%; border-bottom: 1px solid black; text-align: left;">COURSE</th>
                    <th style="width: 25%; border-bottom: 1px solid black; text-align: left;">MAJOR</th>
                    <th style="border-bottom: 1px solid black;">CREDIT</th>
                    <th style="border-bottom: 1px solid black;">HPA</th>
                    <th style="border-bottom: 1px solid black;">GWA</th>
                    <th style="border-bottom: 1px solid black;">RANK</th>
                </tr>
            </thead>
            @if(count($students)>0)
                @foreach($students as $key => $student)
                    <tr>
                        <td>{{strtoupper($student->lastname)}}, {{strtoupper($student->firstname)}} {{strtoupper($student->middlename)}} {{strtoupper($student->suffix)}}</td>
                        <td>{{$student->courseabrv}}</td>
                        <td>{{ucwords(strtolower($student->major))}}</td>
                        <td style="text-align: center;"></td>
                        <td style="text-align: center;"></td>
                        <td style="text-align: center;"></td>
                        <td style="text-align: center;"></td>
                    </tr>
                    @php
                        $student->display = 1;
                    @endphp
                    @if($key == 49)
                        <tr>
                            <td colspan="7" style="border-bottom: 1px solid black;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="7">Page 1 of {{$numberofpages}}</td>
                        </tr>
                        @php
                                break;
                        @endphp
                    @endif
                @endforeach
            @endif
        </table>
        @for($x = 0; $x <= $numberofpages; $x++)
            @if(collect($students)->where('display','0')->count()>0)
                <div style="page-break-before: always;">&nbsp;</div>
                @php
                    $students = collect($students)->where('display','0')->values();
                @endphp
                <table style="width: 100%; font-size: 10px;">
                    <thead>
                        <tr>
                            <th style="width: 35%; border-bottom: 1px solid black; text-align: left;">STUDENT NAME</th>
                            <th style="width: 15%; border-bottom: 1px solid black; text-align: left;">COURSE</th>
                            <th style="width: 20%; border-bottom: 1px solid black; text-align: left;">MAJOR</th>
                            <th style="border-bottom: 1px solid black;">CREDIT</th>
                            <th style="border-bottom: 1px solid black;">HPA</th>
                            <th style="border-bottom: 1px solid black;">GWA</th>
                            <th style="border-bottom: 1px solid black;">RANK</th>
                        </tr>
                    </thead>
                    @if(count($students)>0)
                        @foreach($students as $key => $student)
                            <tr>
                                <td>{{strtoupper($student->lastname)}}, {{strtoupper($student->firstname)}} {{strtoupper($student->middlename)}} {{strtoupper($student->suffix)}}</td>
                                <td>{{$student->courseabrv}}</td>
                                <td>{{ucwords(strtolower($student->major))}}</td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                            </tr>
                            @php
                                $student->display = 1;
                                $pagenum = $x+2;
                            @endphp
                            @if($key == 49)
                                <tr>
                                    <td colspan="7" style="border-bottom: 1px solid black;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="7">Page {{$pagenum}} of {{$numberofpages}}</td>
                                </tr>
                                @break
                            @endif
                        @endforeach
                    @endif
                    @if($pagenum == $numberofpages)
                        <tr>
                            <td colspan="7" style="border-bottom: 1px solid black;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="7">Page {{$pagenum}} of {{$numberofpages}}</td>
                        </tr>
                    @endif
                </table>
            @else
                @break
            @endif
        @endfor
    </body>
</html>