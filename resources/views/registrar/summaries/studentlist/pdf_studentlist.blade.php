<style>
    html {
        font-family: Arial, Helvetica, sans-serif;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
        border: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
    }

    th.course-header {
        background-color: #e8e8e8;
        font-size: 13px;
    }

    th.year-level-header {
        background-color: #f9f9f9;
        font-size: 12px;
    }

    th,
    td.text-right {
        text-align: right;
    }

    th,
    td.text-center {
        text-align: center;
    }

    .summary-header {
        font-size: 15px;
        text-align: center;
        margin-top: 20px;
        margin-bottom: 10px;
    }

    .table-header td {
        border: none;
    }

    .course-total,
    .grand-total {
        font-weight: bold;
        border-top: 2px solid black;
    }
</style>

<table class="table-header">
    <tr>
        <td width="20%">
            <img src="{{ base_path() }}/public/{{ DB::table('schoolinfo')->first()->picurl }}" alt="school"
                width="80px">
        </td>
        <td style="width: 60%; text-align: center;">
            <strong>{{ DB::table('schoolinfo')->first()->schoolname }}</strong><br>
            <span style="font-size: 12px;">{{ ucwords(strtolower(DB::table('schoolinfo')->first()->address)) }}</span>
        </td>
        <td width="20%"></td>
    </tr>
</table>

<div class="summary-header">ENROLLMENT SUMMARY - {{ strtoupper($semester) }} {{ $sydesc }}</div>

<table>
    <thead>
        <tr>
            <th style="width: 5%;"></th>
            <th>Student ID</th>
            <th style="width: 50%;">Student Name</th>
            <th>Gender</th>
            <th class="text-right">Units Enrolled</th>
        </tr>
    </thead>
    <tbody>
        @php
            $gtotal = 0;
        @endphp
        @foreach ($students as $key => $eachcourse)
            <tr>
                <th colspan="5" class="course-header">{{ $key }} ({{ $eachcourse[0]->courseDesc }})</th>
            </tr>
            @php
                $gtotal += count($eachcourse);
                $totaleachcourse = $eachcourse;
                $levels = collect($eachcourse)->sortBy('sortid')->groupBy('levelname');
            @endphp
            @foreach ($levels as $levelkey => $eachlevel)
                <tr>
                    <th colspan="5" class="year-level-header">{{ $levelkey }}</th>
                </tr>
                @foreach (collect($eachlevel)->sortBy('lastname') as $eachstudent)
                    <tr>
                        <td></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $eachstudent->sid }}</td>
                        <td>{{ $eachstudent->lastname }}, {{ $eachstudent->firstname }}
                            {{ $eachstudent->middlename }}</td>
                        <td>{{ ucwords($eachstudent->gender) }}</td>
                        <td class="text-right">{{ number_format($eachstudent->totalunits) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2"></td>
                    <td class="course-total">Year Level Count: <strong>{{ count($eachlevel) }}</strong></td>
                    <td colspan="2"></td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td class="course-total">Course Total: <strong>{{ count($totaleachcourse) }}</strong></td>
                <td colspan="3"></td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2" class="grand-total">GRAND TOTAL: <strong>{{ $gtotal }}</strong></td>
            <td colspan="3"></td>
        </tr>
    </tbody>
</table>
