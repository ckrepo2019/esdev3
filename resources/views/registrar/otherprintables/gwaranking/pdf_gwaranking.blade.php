<html>
<header>
    <style>
        @page {
            margin: 0.5in 0.5in;
            size: 8.5in 13in;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1em;
        }

        th {
            padding: 10px;
            text-align: left;
            font-size: 12px;
            background-color: #f4f4f4;
            border-bottom: 2px solid #555;
            color: #444;
        }

        td {
            padding: 8px;
            font-size: 11px;
            border-bottom: 1px solid #ddd;
        }

        .header-table tr,
        .header-table td {
            border-bottom: none !important;
            text-align: center;
        }

        .header-table td {
            font-size: 13px;
            text-align: center;
        }

        .header-table .title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            padding-top: 5px;
        }

        .header-table .print-date {
            text-align: right;
            font-size: 12px;
        }

        .page-footer {
            text-align: center;
            font-size: 10px;
            padding-top: 8px;
            color: #777;
        }

        .student-name {
            text-transform: uppercase;
        }

        .major-course {
            font-style: italic;
            color: #666;
        }

        .centered {
            text-align: center;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</header>

<body>
    <table class="header-table" style="border-bottom: none !important;">
        <tr style="border-bottom: none;">
            <td colspan="3">{{ DB::table('schoolinfo')->first()->schoolname }}</td>
        </tr>
        <tr style="border-bottom: none;">
            <td colspan="3">{{ DB::table('schoolinfo')->first()->address }}</td>
        </tr>
        <tr style="border-bottom: none;">
            <td colspan="3" class="title">GRADE WEIGHTED AVERAGE RANKING</td>
        </tr>
        <tr class="print-date" style="border-bottom: none;">
            <td colspan="3">Print Date: {{ date('m/d/Y') }}</td>
        </tr>
        <tr style="border-bottom: none;">
            <td>School Year: {{ $sydesc }}</td>
            {{-- <td>Month: {{ $monthname }}</td> --}}
            <td></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>STUDENT NAME</th>
                <th>COURSE</th>
                <th>MAJOR</th>
                <th class="centered">CREDIT</th>
                <th class="centered">HPA</th>
                <th class="centered">GWA</th>
                <th class="centered">RANK</th>
            </tr>
        </thead>
        <tbody>
            @if (count($students) > 0)
                @foreach ($students as $key => $student)
                    <tr>
                        <td class="student-name">{{ $student->lastname }}, {{ $student->firstname }}
                            {{ $student->middlename }} {{ $student->suffix }}</td>
                        <td class="major-course">{{ $student->courseabrv }}</td>
                        <td class="major-course">
                            {{ $student->major ? ucwords(strtolower($student->major)) : $student->courseabrv }}
                        </td>
                        <td class="centered">{{ $student->totalcredit }}</td>
                        <td class="centered">{{ $student->hpa }}</td>
                        <td class="centered">{{ $student->gwa }}</td>
                        <td class="centered">{{ $key + 1 }}</td>
                    </tr>
                    @php
                        $student->display = 1;
                    @endphp
                    @if ($key == 49)
                        <tr>
                            <td colspan="7" class="page-footer">Page 1 of {{ $numberofpages }}</td>
                        </tr>
                        @php
                            break;
                        @endphp
                    @endif
                @endforeach
            @endif
        </tbody>
    </table>

    @for ($x = 0; $x <= $numberofpages; $x++)
        @if (collect($students)->where('display', '0')->count() > 0)
            <div class="page-break"></div>
            @php
                $students = collect($students)->where('display', '0')->values();
            @endphp
            <table>
                <thead>
                    <tr>
                        <th>STUDENT NAME</th>
                        <th>COURSE</th>
                        <th>MAJOR</th>
                        <th class="centered">CREDIT</th>
                        <th class="centered">HPA</th>
                        <th class="centered">GWA</th>
                        <th class="centered">RANK</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $key => $student)
                        <tr>
                            <td class="student-name">{{ $student->lastname }}, {{ $student->firstname }}
                                {{ $student->middlename }} {{ $student->suffix }}</td>
                            <td class="major-course">{{ $student->courseabrv }}</td>
                            <td class="major-course">{{ ucwords(strtolower($student->major)) }}</td>
                            <td class="centered">{{ $student->totalcredit }}</td>
                            <td class="centered">{{ $student->hpa }}</td>
                            <td class="centered">{{ $student->gwa }}</td>
                            <td class="centered">{{ $key + 1 }}</td>
                        </tr>
                        @php
                            $student->display = 1;
                        @endphp
                        @if ($key == 49)
                            <tr>
                                <td colspan="7" class="page-footer">Page {{ $x + 2 }} of
                                    {{ $numberofpages }}</td>
                            </tr>
                        @break
                    @endif
                @endforeach
                @if ($x + 1 == $numberofpages)
                    <tr>
                        <td colspan="7" class="page-footer">Page {{ $x + 2 }} of {{ $numberofpages }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    @else
    @break

@endif
@endfor
</body>

</html>
