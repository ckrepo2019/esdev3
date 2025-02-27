<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Registration</title>
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

        .page-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            text-align: center;
        }

        .footer {
            position: fixed;
            bottom: 60;
            width: 100%;
            text-align: center;
            font-size: 12px;
        }

        .footer table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-line {
            width: 100%;
            border-top: 1px solid black;
            margin-bottom: 5px;
        }
    </style>

    @php
        // dd($students);
    @endphp
</head>

<body style="padding-bottom: 50px; padding-top: 170px;">
    <img src="{{ public_path('assets/images/tesda/tesdalogo.png') }}"
        style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); width: 100%; height: auto; background-position: center; opacity: 0.1; z-index: -1;">

    <div class="page-header">
        <table
            style="width: 100%; table-layout: fixed; border: none !important; margin-bottom: 0px; font-family: Arial, sans-serif !important;">
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

                <td rowspan="2" style="width: 10%; text-align: center; vertical-align: top;">
                    <img src="{{ public_path('assets/images/tesda/tesdalogo.png') }}" alt="TESDA Logo" width="90px" />
                </td>
            </tr>
            <tr>
                <td colspan="3"style="text-align: center; font-family: 'Times New Roman', serif !important;">
                    <h1 style="margin-bottom: 0px; font-weight: normal;">TRANSCRIPT OF RECORDS</h1><br>
                </td>
            </tr>
        </table>
    </div>


    <table width="100%" class="table  table-sm mb-3 float-left" style="margin-bottom: 25px; font-size: 12px">
        <tr>
            <td width="20%">Name:</td>
            <td width="50%">
                {{ $students ? $students->firstname . ' ' . $students->middlename . ' ' . $students->lastname . ' ' . $students->suffix : '' }}
            </td>
            <td width="15%">Citizenship:</td>
            <td width="15%">{{ $students ? $students->nationality : '' }}
            </td>
        </tr>
        <tr>
            <td>Address:</td>
            <td>{{ $students ? $students->street . ' ' . $students->barangay . ' ' . $students->city . ' ' . $students->province : '' }}
            </td>
            <td>Gender:</td>
            <td>{{ $students ? $students->gender : '' }}
            </td>
        </tr>
        <tr>
            <td>Place of Birt:</td>
            <td>{{ $students ? $students->pob : '' }}</td>
            <td>Date of Birt:</td>
            <td>
                @if ($students)
                    {{ \Carbon\Carbon::parse($students->dob)->format('F j, Y') }}
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
                @if ($students)
                    {{ \Carbon\Carbon::parse($students->dateenrolled)->format('F j, Y') }}
                @endif
            </td>
            <td>Date of Admission:</td>
            <td>
                @if ($students)
                    {{ \Carbon\Carbon::parse($students->dateenrolled)->format('F j, Y') }}
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
                <th width="10%" style="border: 1px solid black;">Term</th>
                <th width="40%" style="border: 1px solid black;">Competency Description</th>
                <th width="10%" style="border: 1px solid black;">Ratings</th>
                <th width="10%" style="border: 1px solid black;">Hrs</th>
                <th width="15%" style="border: 1px solid black;">Description</th>
                <th width="15%" style="border: 1px solid black;">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php
                $competency_types = collect($schedules)->groupBy('competency_type'); // Group by competency type
            @endphp

            @if ($schedules->isNotEmpty())
                @foreach ($competency_types as $type => $competencies)
                    <tr style="text-align: left; font-family: Arial, sans-serif; text-transform: uppercase;">
                        @if ($loop->first)
                            <td rowspan="{{ max(count($schedules), 1) + count($competency_types) }}"
                                style="border: 1px solid black; vertical-align: middle;">
                                <div>{{ $students->course_duration }}</div><br>
                                <div>{{ $students->course_name }}</div><br>
                                <div>DURATION: {{ \Carbon\Carbon::parse($students->date_from)->format('F Y') }} -
                                    {{ \Carbon\Carbon::parse($students->date_to)->format('F Y') }}.
                                </div>
                            </td>
                        @endif
                        <td colspan="5" style="border: 1px solid black; font-weight: bold;">{{ $type }}</td>
                    </tr>

                    @foreach ($competencies as $schedule)
                        <tr>
                            <td style="border: 1px solid black; text-align: left;">{{ $schedule->competency_desc }}
                            </td>
                            <td style="border: 1px solid black;">{{ $schedule->rating ?? '2.9' }}</td>
                            <td style="border: 1px solid black;">{{ $schedule->hours }}</td>
                            <td style="border: 1px solid black;">{{ $schedule->description ?? 'Passed' }}</td>
                            <td style="border: 1px solid black;">{{ $schedule->remarks ?? 'Competent' }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @else
                <tr>
                    <td colspan="6" style="border: 1px solid black; text-align: center; padding: 10px;">
                        No Competencies Found
                    </td>
                </tr>
            @endif

            <tr>
                <td colspan="6" style="border: 1px solid black; padding: 8px; text-align: center;">
                    COMPLETED THE {{ $students->course_name }} AS OF
                    {{ \Carbon\Carbon::parse($students->date_from)->format('F Y') }} -
                    {{ \Carbon\Carbon::parse($students->date_to)->format('F Y') }}.
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <div class="row" style="font-size: 12px">
        <div class="col-3">
            <p style="text-align: left;"><strong>REMARKS: FOR EMPLOYMENT PURPOSE ONLY</strong></p>
            <p style="text-align: left;">{{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
        </div>
        <div class="col-9" style="margin-left: 10%; margin-right: 10%;">
            <p style="text-align: center;">This is to certify that the following records of
                {{ $students ? ($students->firstname . ' ' . $students->middlename != null ? $students->middlename : '' . ' ' . $students->lastname . ' ' . $students->suffix) : '' }},
                has been verified and true copies of the official records substantiating the same are kept in the
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
    {{-- <table style="width: 100%;font-size:14px">
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
    </table> --}}

    <div class="footer">
        @php
            // dd($signatory);
        @endphp
        <table width="100%" style="table-layout: fixed; text-align: center; margin-bottom: 20px;">
            <tr>
                @foreach ($signatory as $item)
                    <td style="padding-bottom: 20px;text-align: left !important;">
                        <small>{{ $item->description ?? 'Signatory:' }}</small>
                    </td>
                @endforeach
            </tr>

            <tr>
                {{-- <td width="33.3%">
                    <div class="signature-line"></div>
                    <small>Name & Signature Over Printed Name</small>
                </td> --}}
                @foreach ($signatory as $item)
                    <td>
                        <div class="signature-line"></div>
                        <strong>{{ $item->signatory_name }}</strong><br>
                        <small>{{ $item->signatory_title }}</small>
                    </td>
                @endforeach
                {{-- <td width="33.3%">
                    <div class="signature-line"></div>
                    <strong>CHARLES F. LEBANTE</strong><br>
                    <small>School Cashier</small>
                </td>
                <td width="33.3%">
                    <div class="signature-line"></div>
                    <strong>NESSA LIE C. ELEGUEN</strong><br>
                    <small>Health Care Program Coordinator</small>
                </td>
                <td width="33.3%">
                    <div class="signature-line"></div>
                    <strong>ARABELLA R. MEDIDA</strong><br>
                    <small>School Registrar</small>
                </td> --}}
            </tr>
        </table>
    </div>

</body>

</html>
