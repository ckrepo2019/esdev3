<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transcript of Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header-text {
            text-align: center;
        }

        .header-text h1,
        h2,
        h3 {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-bordered {
            border: 1px solid black;
        }

        .table-bordered td {
            border: 1px solid black;
        }

        .table-bordered th {
            border: 1px solid black;
        }

        .table-bordered tr:nth-child(even) {
            border: 1px solid black;
            font-style: normal;
            font-family: Arial, sans-serif;
        }

        /* th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .group-header {
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: left;
        }

        .group-row td {
            text-align: left;
            font-size: 11px;
        }

        .subheader {
            font-weight: bold;
            text-align: left;
            background-color: #e9ecef;
        } */
    </style>
</head>

<body>
    <div class="container mt-4">
        @foreach ($students as $student)
            <!-- Header Section -->
            {{-- <div class="row mb-4">
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
                                        OFFICIAL TRANSCRIPT OF RECORDS
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
            </div> --}}

                <img src="{{ public_path('assets/images/tesda/tesdalogo.png') }}"
                style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); width: 100%; height: auto; background-position: center; opacity: 0.1; z-index: -1;">

            </div>
            <table
                style="width: 100%; table-layout: fixed; border: none !important;margin-bottom: 0px; font-family: Arial, sans-serif !important;">
                <tr>
                    <td style="width: 10%; vertical-align: top; text-align: center;">
                        <img src="{{ public_path($schoolInfo->picurl) }}" alt="school" width="90px" />
                    </td>
                    <td style="text-align: center;">
                        <span style="font-size: 12px;">Republic of the Philippines</span><br>
                        <span style="font-size: 21px;"><b>{{ $schoolInfo->schoolname }}</b></span>
                        <br>
                        <span style="font-size: 13px;"> TECHNICAL EDUCATION AND SKILLS DEVELOPMENT AUTHORITY</span> <br>
                        <span style="font-size: 12px;">{{ $schoolInfo->address }}</span><br>
                    </td>

                    <td rowspan="2" style="width: 10%; text-align: center;vertical-align: top">
                        <img src="{{ public_path('assets/images/tesda/tesdalogo.png') }}" alt="student pic" width="90px" />
                    </td>
                </tr>
                <tr>
                    <td colspan="3"style="text-align: center; font-family: 'Times New Roman', serif !important;">
                        <h1 style="margin-bottom: 0px; font-weight: normal;">TRANSCRIPT OF RECORDS</h1><br>
                    </td>
                </tr>
            </table>

            <table width="100%" class="table  table-sm mb-3 float-left" style="margin-bottom: 25px; font-size: 12px">
                <tr>
                    <td width="20%">Name:</td>
                    <td width="50%">
                        {{ $student ? $student->firstname . ' ' . $student->middlename . ' ' . $student->lastname . ' ' . $student->suffix : '' }}
                    </td>
                    <td width="15%">Citizenship:</td>
                    <td width="15%">{{ $student ? $student->nationality : '' }}
                    </td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td>{{ $student ? $student->street . ' ' . $student->barangay . ' ' . $student->city . ' ' . $student->province : '' }}
                    </td>
                    <td>Gender:</td>
                    <td>{{ $student ? $student->gender : '' }}
                    </td>
                </tr>
                <tr>
                    <td>Place of Birt:</td>
                    <td>{{ $student ? $student->pob : '' }}</td>
                    <td>Date of Birt:</td>
                    <td>
                        @if ($student)
                            {{ \Carbon\Carbon::parse($student->dob)->format('F j, Y') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Elementary Completed at:</td>
                    <td></td>
                    <td>Year:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Secondary Completed at:</td>
                    <td></td>
                    <td>Year:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Date of Admission:</td>
                    <td>
                        @if ($student)
                            {{ \Carbon\Carbon::parse($student->dateenrolled)->format('F j, Y') }}
                        @endif
                    </td>
                    <td>Date of Admission:</td>
                    <td>
                        @if ($student)
                            {{ \Carbon\Carbon::parse($student->dateenrolled)->format('F j, Y') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Remarks:</td>
                    <td colspan="3"></td>
                </tr>
            </table>

            <table class="table-bordered" style="width: 100%; font-size: 12px; text-align: center;">
                <thead>
                    <tr style="background-color: #d9d9d9; font-weight: bold;">
                        <th width="10%" style="border: 1px solid black; ">Term</th>

                        <th width="40%" style="border: 1px solid black; ">Competency Description</th>
                        <th width="10%" style="border: 1px solid black; ">Ratings</th>
                        <th width="10%" style="border: 1px solid black; ">Hrs</th>
                        <th width="15%" style="border: 1px solid black; ">Description</th>
                        <th width="15%" style="border: 1px solid black; ">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $competency_types = collect($schedules)->groupBy('competency_type'); // Group by competency type
                    @endphp

                    @foreach ($competency_types as $type => $competencies)
                        <tr style="background-color: #f2f2f2; text-align: left; font-family: Arial, sans-serif;">
                            @if ($loop->first)
                                <td rowspan="{{ count($schedules) + count($competency_types) }}"
                                    style="border: 1px solid black;  vertical-align: top;">
                                    <div>{{ $student->course_duration }}</div>
                                    <div>{{ $student->course_name }}</div>

                                    <div>DURATION: {{ \Carbon\Carbon::parse($student->date_from)->format('F Y') }} -
                                        {{ \Carbon\Carbon::parse($student->date_to)->format('F Y') }}.</div>
                                </td>
                            @endif
                            <td colspan="5" style="border: 1px solid black; font-weight: bold;">{{ $type }}
                            </td>
                        </tr>

                        @foreach ($competencies as $schedule)
                            <tr style="text-align: left; padding-left: 10px;">
                                <td style="border: 1px solid black; ">{{ $schedule->competency_desc }}</td>
                                <td style="border: 1px solid black; ">{{ $schedule->rating ?? '2.9' }}</td>
                                <td style="border: 1px solid black; ">{{ $schedule->hours }}</td>
                                <td style="border: 1px solid black; ">{{ $schedule->description ?? 'Passed' }}</td>
                                <td style="border: 1px solid black; ">{{ $schedule->remarks ?? 'Competent' }}</td>
                            </tr>
                        @endforeach
                    @endforeach

                    <tr>
                        <td colspan="6" style="border: 1px solid black; padding: 8px; text-align: center;">
                            COMPLETED THE {{ $student->course_name }} AS OF
                            {{ \Carbon\Carbon::parse($student->date_from)->format('F Y') }} -
                            {{ \Carbon\Carbon::parse($student->date_to)->format('F Y') }}.
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr
                style="
            border-color: black;
            border-width: .5px;
            margin-top: 15px;
            margin-bottom: 15px;">

            <div class="row" style="font-size: 12px">
                <div class="col-3">
                    <p style="text-align: left;"><strong>REMARKS: FOR EMPLOYMENT PURPOSE ONLY</strong></p>
                    <p style="text-align: left;">{{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
                </div>
                <div class="col-9" style="margin-left: 10%; margin-right: 10%;">
                    <p style="text-align: center;">This is to certify that the following records of
                        {{ $student ? $student->firstname . ' ' . $student->middlename . ' ' . $student->lastname . ' ' . $student->suffix : '' }},
                        has been verified and true copies of the official records substantiating the same are kept in
                        the
                        files of our school.
                    </p>
                </div>
                <div>
                    <p style="text-align: center;"><strong>GRADING SYSTEM</strong></p>
                    <p style="text-align: center; margin-left: 20%; margin-right: 20%;">1.0=100; 1.1=99; 1.2=98; 1.3=97;
                        1.4=96; 1.5=95; 1.6=94; 1.7=93; 1.8=92; 1.9=91;
                        2.0=90; 2.1=89; 2.2=88; 2.3=87; 2.4=86; 2.5=85; 2.6=84; 2.7=83; 2.8=82; 2.9=81;3.0=80; 3.1=79;
                        3.2=78; 3.3=77; 3.4=76; 3.5=75; 5.0=FAILED; 9.0=DROPPED
                    </p>
                </div>
            </div>

            <br>

            {{-- <div width="100%" class="container mt-4" style="font-size: 12px;">
            <div class="row">
                @foreach ($signatory as $signatory)
                    <!-- Signatory -->
                    <div class="col-3 text-center" style="display: inline-block;">
                        <p>{{ $signatory->description }}:</p>
                        <p class="fw-bold text-uppercase mt-2 mb-1" style="text-decoration: underline;">
                            {{ $signatory->signatory_name }}</p>
                        <p class="fst-italic">{{ $signatory->signatory_title }}</p>
                    </div>
                @endforeach
            </div>
        </div> --}}

            <table style="width: 100%;font-size:14px">
                @php
                    // Group the signatories by description
                    $desc = $signatory->groupBy('description');
                @endphp

                <!-- Table header with descriptions -->
                <tr>
                    @foreach ($desc as $key => $items)
                        <td>{{ $key }}</td> <!-- Display description as header -->
                    @endforeach
                </tr>

                <!-- Table rows for signatory names and titles -->
                <tr>
                    @foreach ($desc as $key => $items)
                        <td>
                            @foreach ($items as $item)
                                <u>{{ $item->signatory_name }}<br> <!-- Signatory name --></u>
                            @endforeach
                        </td>
                    @endforeach
                </tr>

                <tr>
                    @foreach ($desc as $key => $items)
                        <td>
                            @foreach ($items as $item)
                                {{ $item->signatory_title }}<br> <!-- Signatory title -->
                            @endforeach
                        </td>
                    @endforeach
                </tr>
            </table>



            <div style="page-break-after: always;"></div> <!-- Page break after each student -->
        @endforeach
    </div>
</body>

</html>
