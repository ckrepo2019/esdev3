<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Registration</title>
    <style>
        .header-text {
            text-align: center;
        }

        .header-text h1,
        h2,
        h4 {
            margin: 0;
        }

        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .line {
            display: inline-block;
            width: 300px;
            border-bottom: 1px solid black;
        }

        .table {
            border: 1px solid #a8a8a8;
        }

        .table td {
            border: 1px solid #a8a8a8;
        }

        .table thead th {
            background-color: #e4e4e4;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        {{-- @foreach ($students as $student) --}}
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <table width="100%">
                    <tr>
                        <td style="width: 15%; text-align: left;">
                            {{-- <img src="{{ public_path($schoolInfo->picurl) }}" alt="Logo" class="logo"
                                style="width: 100px; height: 40px; position: absolute; top: 0;"> --}}
                        </td>
                        <td style="width: 70%; text-align: center;">
                            <div class="header-text" style="text-align: center; font-family: Arial, sans-serif;">
                                <br><br>
                                Republic of the Philippines
                                <h2 style="margin: 0; font-size: 21px; font-weight: bold;">
                                    {{ $schoolInfo->schoolname }}
                                    {{-- GABRIEL TABORIN COLLEGE OF DAVAO FOUNDATION, INC. --}}
                                </h2>
                                <span style="font-size: 13px;">
                                    TECHNICAL EDUCATION AND SKILLS DEVELOPMENT AUTHORITY
                                </span>
                                <span style="font-size: 12px;">
                                    {{ $schoolInfo->division }}<br>
                                    {{ $schoolInfo->district }} {{ $schoolInfo->address }}<br>
                                    {{ $schoolInfo->region }}
                                </span>
                            </div>
                            <br><br>
                            <h1
                                style="margin-top: 10px; font-size:40px; font-family: Times New Roman, serif; font-weight:
                                    normal; color: darkblue;">
                                CERTIFICATE OF COMPLETION
                            </h1>
                            <br>
                            <br>
                            <span style="font-size: 16px; display: block; margin-top: 5px;">This is to certify
                                that</span>
                            <br>
                            <br>
                            <span style="font-size: 35px; display: block;">{{ $student->firstname }}
                                {{ $student->middlename }}
                                {{ $student->lastname }}</span>
                            <br>
                            <br>
                            <br>
                            <span style="font-size: 18px; display: block; margin-top: 5px;">has completed the
                                course</span>
                            <br>
                            <span
                                style="font-size: 35px; display: block; margin-top: 5px;">{{ $student->course_name }}</span>
                            <br>
                            <span style="font-size: 18px; display: block; margin-top: 5px;">on</span>
                            <br>
                            <span style="font-size: 18px; display: block;">December 17, 2024</span>
                            <br><br>
                            <br><br>
                            <br><br>
                            <br>
                            <span style="font-size: 18px; display: block; font-weight: bold;">SEC. ISIDRO S. LAPENA
                                PhD, CSEE</span>
                            <span style="font-size: 18px; display: block;">Director General</span>


                        </td>
                        <td style="width: 15%; text-align: right;">
                            {{-- <img src="{{ public_path($schoolInfo->picurl) }}" alt="Logo" class="logo"
                                style="width: 100px; height: 40px; position: absolute; top: 0;"> --}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Student Info Section -->


        {{-- <div style="page-break-after: always;"></div> <!-- Page break after each student --> --}}

    </div>
</body>

</html>
