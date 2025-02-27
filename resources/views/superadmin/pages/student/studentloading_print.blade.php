<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin: 0;
        }

        .header h2 {
            font-size: 20px;
            margin: 0;
        }

        .student-info {
            margin-bottom: 20px;
        }

        .student-info table {
            width: 100%;
            border-collapse: collapse;
        }

        /* .student-info td {
            padding: 5px;
        } */

        .subject-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .subject-table th,
        .subject-table td {
            border: 1px solid #000;
            padding: 1px;
            text-align: left;
        }

        .subject-table th {
            background-color: #f2f2f2;
        }

        .total-units {
            font-weight: bold;
            text-align: right;
            margin-top: 10px;
        }

        /* Style for the dashed line */
        .dashed-line {
            border-top: 1px dashed black;
            margin: 30px 0;
        }
    </style>
</head>

<body>
    <!-- First Copy of the Certificate -->
    <div class="certificate">
        <table class="table grades " width="100%">
            <tr>
                @php
                    $picurl = explode('?', $scinfo->picurl);
                    $picurl = $picurl[0];

                @endphp
                <td style="text-align: right !important; vertical-align: top;" width="15%">
                    <img src="{{ public_path($picurl) }}" alt="school" width="70px">
                </td>
                <td style="width: 70%; text-align: center;" class="align-middle">
                    <div style="width: 100%; font-weight: bold; font-size: 15px !important;">{{ $scinfo->schoolname }}
                    </div>
                    <div style="width: 100%; font-size: 10px;">{{ $scinfo->address }}</div>
                    <div style="width: 100%; font-size: 18px; margin-top: 15px; margin-bottom:10px">CERTIFICATE OF
                        REGISTRATION</div>
                    <div style="width: 100%; font-size: 12px; margin-bottom:20px">
                        {{ strtoupper($student->studtype) }} STUDENT</div>
                <td width="15%"></td>
            </tr>
        </table>

        <div class="student-info">
            <table style="font-size: 8px">
                <tr>
                    <td><strong>ID No.:</strong> {{ $student->sid }}</td>
                    <td><strong>Name:</strong> {{ $student->lastname }}, {{ $student->firstname }}
                        {{ $student->middlename ?? '' }}</td>
                    <td><strong>Contact #:</strong> {{ $student->contactno }}</td>
                    <td style="text-align: right;">{{ now()->format('m/d/Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Year:</strong> {{ $student->levelname }} College</td>
                    <td><strong>Course:</strong> {{ $student->courseDesc }}</td>
                    <td><strong>Semester:</strong> {{ $student->semester }}, S.Y
                        {{ $student->sydesc }}</td>
                </tr>
                <tr>
                    <td><strong>Date of Birth:</strong> {{ date('m/d/Y', strtotime($student->dob)) }}</td>
                    <td><strong>Address:</strong>
                        @if ($student->street != '' && $student->barangay != '' && $student->city != '')
                            {{ $student->street }}, {{ $student->barangay }},
                            {{ $student->city }}
                        @else
                        @endif
                    </td>
                    <td><strong>Section/Block:</strong> {{ $student->sectionDesc }}</td>
                </tr>
            </table>
        </div>

        <table class="subject-table" style="font-size: 8px; width: 100%;">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Lec</th>
                    <th>Lab</th>
                    <th>Cr. Unit</th>
                    <th>Class</th>
                    <th>Time & Day Schedule</th>
                    <th>Instructor</th>
                    <th>Room</th>
                </tr>
            </thead>
            <tbody style="font-size: 8px;">
                @php
                    $totalLecUnits = 0;
                    $totalLabUnits = 0;
                    $totalCredUnits = 0;
                @endphp
                @foreach ($studentLoading as $subject)
                    <tr>
                        <td><span style="color: green;">{{ $subject->subjCode }}</span> {{ $subject->subjDesc }}</td>
                        <td>{{ $subject->lecunits }}</td>
                        <td>{{ $subject->labunits }}</td>
                        <td>{{ $subject->credunits }}</td>
                        <td>{{ $subject->schedotherclass }}</td>
                        <td>{{ $subject->stime }} - {{ $subject->etime }} / {{ $subject->description }}</td>
                        <td>{{ $subject->firstname }} {{ $subject->middlename ?? '' }} {{ $subject->lastname }}</td>
                        <td>{{ $subject->roomname }}</td>
                    </tr>
                    @php
                        $totalLecUnits += $subject->lecunits;
                        $totalLabUnits += $subject->labunits;
                        $totalCredUnits += $subject->credunits;
                    @endphp
                @endforeach
                <tr>
                    <td colspan="3"><strong>Total Units:</strong></td>
                    <td><strong>{{ $totalCredUnits }}</strong></td>
                    <td colspan="4"></td>>
                </tr>
            </tbody>
        </table>

        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 40%; vertical-align: top;">
                    <table class="subject-table"
                            style="font-size: 8px; width: 80%; border: 1px solid black; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <td colspan="2" style="border: 1px solid black; text-align: center;">
                                        <strong>BILLING ASSESSMENT</strong>
                                    </td>
                                </tr>
                            </thead>
                            <tbody style="font-size: 6px;">
                                @php
                                    $totalAmount = 0;
                                @endphp
                                @foreach ($billingAssessment as $assessment)
                                    <tr>
                                        <td style="border: 1px solid black;">{{ $assessment->particulars }}</td>
                                        <td style="border: 1px solid black; text-align: right;">
                                            {{ number_format($assessment->amount, 2) }}
                                        </td>
                                    </tr>
                                    @php
                                        $totalAmount += $assessment->amount;
                                    @endphp
                                @endforeach
                                <tr>
                                    <td style="border: 1px solid black;"><strong>TOTAL AMOUNT:</strong></td>
                                    <td style="border: 1px solid black; text-align: right;">
                                        <strong>{{ number_format($totalAmount, 2) }}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                </td>

                <td style="width: 60%; vertical-align: top; padding-left: 50px; margin-top: 30px">
                    <table style="width: 100%; border: none;">
                        <tbody style="font-size: 8px;">
                            <tr>
                                <td colspan="3" style="padding-top: 10px; text-align: left;">
                                    This is your official Certificate of Registration.
                                    Please check and verify thoroughly the correctness of these data.
                                    If you have questions or verification on the data found in this report,
                                    you may visit the RECORDS AND ADMISSION OFFICE.
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 20px; text-align: center;">
                                    <strong>Generated By:</strong> <br>{{ auth()->user()->name }}
                                    ________________________________<br>
                                    <span>REGISTRAR STAFF</span>
                                </td>
                                <td style="padding-top: 20px; text-align: center;">
                                    <strong>Approved By:</strong>{{$scinfo->authorized}}<br>
                                    ________________________________<br>
                                    <span>REGISTRAR</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- Dashed Line Separator -->
    <div class="dashed-line"></div>

    <!-- Second Copy of the Certificate -->
    <div class="certificate">
        <div class="certificate">
            <table class="table grades" width="100%" style="margin-top: 30px !important">
                <tr>
                    <td style="text-align: right !important; vertical-align: top;" width="15%">
                        <img src="{{ base_path() }}/public/{{ $scinfo->picurl }}" alt="school" width="70px">
                    </td>
                    <td style="width: 70%; text-align: center;" class="align-middle">
                        <div style="width: 100%; font-weight: bold; font-size: 15px !important;">
                            {{ $scinfo->schoolname }}</div>
                        <div style="width: 100%; font-size: 10px;">{{ $scinfo->address }}</div>
                        <div style="width: 100%; font-size: 18px; margin-top: 15px; margin-bottom:10px">CERTIFICATE OF
                            REGISTRATION</div>
                        <div style="width: 100%; font-size: 12px; margin-bottom:20px">
                            {{ strtoupper($student->studtype) }} STUDENT</div>
                    <td width="15%"></td>
                </tr>
            </table>

            <div class="student-info">
                <table style="font-size: 8px">
                    <tr>
                        <td><strong>ID No.:</strong> {{ $student->sid }}</td>
                        <td><strong>Name:</strong> {{ $student->lastname }},
                            {{ $student->firstname }} {{ $student->middlename ?? '' }}</td>
                        <td><strong>Contact #:</strong> {{ $student->contactno }}</td>
                        <td style="text-align: right;">{{ now()->format('m/d/Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Year:</strong> {{ $student->levelname }} College</td>
                        <td><strong>Course:</strong> {{ $student->courseDesc }}</td>
                        <td><strong>Semester:</strong> {{ $student->semester }}, S.Y
                            {{ $student->sydesc }}</td>
                    </tr>
                    <tr>
                        <td><strong>Date of Birth:</strong> {{ date('m/d/Y', strtotime($student->dob)) }}</td>
                        <td><strong>Address:</strong>
                            @if ($student->street != '' && $student->barangay != '' && $student->city != '')
                                {{ $student->street }}, {{ $student->barangay }},
                                {{ $student->city }}
                            @else
                            @endif
                        </td>
                        <td><strong>Section/Block:</strong> {{ $student->sectionDesc }}</td>
                    </tr>
                </table>
            </div>

            <table class="subject-table" style="font-size: 8px; width: 100%;">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Lec</th>
                        <th>Lab</th>
                        <th>Cr. Unit</th>
                        <th>Class</th>
                        <th>Time & Day Schedule</th>
                        <th>Instructor</th>
                        <th>Room</th>
                    </tr>
                </thead>
                <tbody style="font-size: 8px;">
                    @php
                        $totalLecUnits = 0;
                        $totalLabUnits = 0;
                        $totalCredUnits = 0;
                    @endphp
                    @foreach ($studentLoading as $subject)
                        <tr>
                            <td><span style="color: green;">{{ $subject->subjCode }}</span> {{ $subject->subjDesc }}
                            </td>
                            <td>{{ $subject->lecunits }}</td>
                            <td>{{ $subject->labunits }}</td>
                            <td>{{ $subject->credunits }}</td>
                            <td>{{ $subject->schedotherclass }}</td>
                            <td>{{ $subject->stime }} - {{ $subject->etime }} / {{ $subject->description }}</td>
                            <td>{{ $subject->firstname }} {{ $subject->middlename ?? '' }} {{ $subject->lastname }}
                            </td>
                            <td>{{ $subject->roomname }}</td>
                        </tr>
                        @php
                            $totalLecUnits += $subject->lecunits;
                            $totalLabUnits += $subject->labunits;
                            $totalCredUnits += $subject->credunits;
                        @endphp
                    @endforeach
                    <tr>
                        <td colspan="3"><strong>Total Units:</strong></td>
                        <td><strong>{{ $totalCredUnits }}</strong></td>
                        <td colspan="4"></td>>
                    </tr>
                </tbody>
            </table>

            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 40%; vertical-align: top;">
                        <table class="subject-table"
                            style="font-size: 8px; width: 80%; border: 1px solid black; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <td colspan="2" style="border: 1px solid black; text-align: center;">
                                        <strong>BILLING ASSESSMENT</strong>
                                    </td>
                                </tr>
                            </thead>
                            <tbody style="font-size: 6px;">
                                @php
                                    $totalAmount = 0;
                                @endphp
                                @foreach ($billingAssessment as $assessment)
                                    <tr>
                                        <td style="border: 1px solid black;">{{ $assessment->particulars }}</td>
                                        <td style="border: 1px solid black; text-align: right;">
                                            {{ number_format($assessment->amount, 2) }}
                                        </td>
                                    </tr>
                                    @php
                                        $totalAmount += $assessment->amount;
                                    @endphp
                                @endforeach
                                <tr>
                                    <td style="border: 1px solid black;"><strong>TOTAL AMOUNT:</strong></td>
                                    <td style="border: 1px solid black; text-align: right;">
                                        <strong>{{ number_format($totalAmount, 2) }}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="width: 60%; vertical-align: top; padding-left: 50px; margin-top: 30px">
                        <table style="width: 100%; border: none;">
                            <tbody style="font-size: 8px;">
                                <tr>
                                    <td colspan="3" style="padding-top: 10px; text-align: left;">
                                        This is your official Certificate of Registration.
                                        Please check and verify thoroughly the correctness of these data.
                                        If you have questions or verification on the data found in this report,
                                        you may visit the RECORDS AND ADMISSION OFFICE.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 20px; text-align: center;">
                                        <strong>Generated By:</strong> <br>{{ auth()->user()->name }}
                                        ________________________________<br>
                                        <span>REGISTRAR STAFF</span>
                                    </td>
                                    <td style="padding-top: 20px; text-align: center;">
                                        <strong>Approved By:</strong>{{$scinfo->authorized}}<br>
                                        ________________________________<br>
                                        <span>REGISTRAR</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
