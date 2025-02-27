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
        @foreach ($students as $student)
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <table width="100%">
                        <tr>
                            <td style="width: 15%; text-align: left;">
                                <img src="{{ public_path($schoolInfo->picurl) }}" alt="Logo" class="logo"
                                    style="width: 100px; height: 40px; position: absolute; top: 0;">
                            </td>
                            <td style="width: 70%; text-align: center;">
                                <div class="header-text" style="text-align: center; font-family: Arial, sans-serif;">
                                    <h2 style="margin: 0; font-size: 20px; font-weight: bold;">
                                        {{ $schoolInfo->schoolname }}
                                    </h2>
                                    <span style="font-size: 14px; display: block;">
                                        {{ $schoolInfo->division }}<br>
                                        {{ $schoolInfo->district }} {{ $schoolInfo->address }}<br>
                                        {{ $schoolInfo->region }}
                                    </span>
                                    <span style="font-size: 14px; display: block; margin-top: 5px;">TESDA</span>
                                    <h3 style="margin-top: 10px; font-size: 18px; font-weight: normal;">
                                        Certificate of Registration
                                    </h3>
                                </div>
                            </td>
                            <td style="width: 15%; text-align: right;">
                                <img src="{{ public_path($schoolInfo->picurl) }}" alt="Logo" class="logo"
                                    style="width: 100px; height: 40px; position: absolute; top: 0;">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Student Info Section -->
            <table class="tables table-bordered table-sm mb-3" style="width: 100%; margin-bottom: 25px">
                <tr>
                    <td style="width: 45%"><strong>Name: </strong>{{ $student->firstname }} {{ $student->middlename }}
                        {{ $student->lastname }}</td>
                    <td style="width: 40%; text-align: right;"><strong>Course:
                        </strong>{{ $student->course_name }}&nbsp;
                        {{ $student->course_duration }}</td>
                </tr>
                <tr>
                    <td style="margin-left: 10px"></td>

                    {{-- <td><strong>ID Number: </strong>{{ $student->student_id }}</td> --}}
                    <td style="width: 40%; text-align: right;">
                        <strong>ID Number:</strong> {{ $student->student_id }} &nbsp;&nbsp;
                        <strong>Gender:</strong> {{ $student->gender == 'M' ? 'Male' : 'Female' }}
                    </td>
                </tr>
            </table>

            <!-- Table Section -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover"
                    style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="padding: 5px;">Competency Description</th>
                            <th style="padding: 5px;">Hours</th>
                            <th style="padding: 5px;">Days</th>
                            <th style="padding: 5px;">Time</th>
                            <th style="padding: 5px;">Trainer</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:12px; text-align: center;">
                        @if (isset($schedules[$student->series_id]))
                            @foreach ($schedules[$student->series_id] as $schedule)
                                <tr style="border: 1px solid #a8a8a8;">
                                    <td style="border: 1px solid #a8a8a8; padding: 8px; text-align: left;">
                                        {{ $schedule->competency_desc }}</td>
                                    <td style="border: 1px solid #a8a8a8; padding: 8px; text-align: left;">
                                        {{ $schedule->hours ?? 'N/A' }}</td>
                                    <td style="border: 1px solid #a8a8a8; padding: 8px; text-align: left;">
                                        @if (!empty($schedule->date_from) && !empty($schedule->date_to))
                                            {{ \Carbon\Carbon::parse($schedule->date_from)->format('M d, Y') }} -
                                            {{ \Carbon\Carbon::parse($schedule->date_to)->format('M d, Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td style="border: 1px solid #a8a8a8; padding: 8px; text-align: left;">
                                        @if (!empty($schedule->stime) && !empty($schedule->etime))
                                            {{ \Carbon\Carbon::parse($schedule->stime)->format('h:i A') }} -
                                            {{ \Carbon\Carbon::parse($schedule->etime)->format('h:i A') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td style="border: 1px solid #a8a8a8; padding: 8px; text-align: left;"></td>
                                    <!-- Replace with actual trainer if available -->
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" style="padding: 8px;">No schedule available</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div style="page-break-after: always;"></div> <!-- Page break after each student -->
        @endforeach
    </div>
</body>

</html>
