<html>

<head>
    <style>
        @page {
            /* margin: 0.5in 1in; */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            text-align: center;
            table-layout: fixed;
            /* Added */
        }

        th,
        #table_results td {
            padding: 3px;
            border: 0.5px solid gray;
            word-wrap: break-word;
            /* Added */
        }

        thead {
            background-color: #eee;
        }
    </style>
</head>

<body>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="text-align: right; vertical-align: top; width: 15%;"><img
                    src="{{ base_path() }}/public/{{ DB::table('schoolinfo')->first()->picurl }}" alt="school"
                    width="110px"></td>
            <td style="wudth: 70%; text-align: center; vertical-align: top;">
                <div style="width: 100%; font-weight: bold; font-size: 20px !important;">
                    {{ DB::table('schoolinfo')->first()->schoolname }}</div>
                <div style="width: 100%; font-size: 14px !important;">{{ DB::table('schoolinfo')->first()->address }}
                </div>
                <div style="width: 100%; font-size: 16px !important;">Tel. No. (064) 572-4020</div>
                <div style="width: 100%; font-size: 17px !important;">ADMISSION WAITLIST REPORT</div>
            </td>
            <td style="vertical-align: middle; text-align: left; width: 15%;">
                <img src="{{ base_path() }}/public/assets/images/department_of_Education.png" alt="school"
                    width="80px">
            </td>
        </tr>
        <tr>
            <td style="font-size: 13px; text-align: center; vertical-align: top;">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" style="border-bottom: 1px solid black;">&nbsp;</td>
        </tr>
        {{-- <tr>
            <td colspan="3" style="border-bottom: 3px solid black; line-height: 2px;">&nbsp;</td>
        </tr> --}}
    </table>

    <table id="table_results">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="17%">Exam Date</th>
                <th width="20%">Applicant</th>
                <th width="13%">Score</th>
                <th width="15%">Desired Course</th>
                <th width="15%">Fitted Course</th>
                <th width="15%">Final Course</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->formatted_examdate }}</td>
                    <td>{{ $student->studname }}</td>
                    <td>{{ $student->totalScore }}%</td>
                    <td>{{ $student->acadprog_id == 6 ? $student->courseabrv : ($student->acadprog_id == 5 ? $student->strandcode : 'None') }}
                    </td>
                    <td>{{ $student->fitted_course_id ? $student->fitted_courseAbrv : 'None' }}</td>
                    <td>{{ ($student->final_courseabrv ? $student->final_courseabrv : $student->final_strandcode) ? $student->final_strandcode : 'None' }}
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
