<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>National Certificate</title>
    <style>
        body {
            font-family: 'Times New Roman', serif, sans-serif;
            text-align: center;
        }

        h1 {
            color: #07166e;
        }

        .title {
            font-size: 24px;
            font-weight: normal;
        }

        .recipient {
            font-size: 28px;
            font-weight: normal;
            margin: 20px 0;
        }

        /* @page {
            margin: 50px 50px 80px 50px;
        } */

        body {
            font-family: 'Times New Roman', serif;
        }

        .footer {
            position: fixed;
            bottom: 20;
            width: 100%;
            text-align: center;
            font-size: 12px;
        }

        .footer table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer img {
            width: 60px;
            /* Adjust image size */
        }

        .signature-line {
            width: 100%;
            border-top: 1px solid black;
            margin-bottom: 5px;
        }

        .page-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            text-align: center;
        }
    </style>



</head>

<body style="padding-bottom: 50px; padding-top: 150px;">

    @foreach ($students as $index => $student)
        @if ($index > 0)
            <div style="page-break-before: always"></div>
        @endif

        <img src="{{ public_path('assets/images/tesda/tesdalogo.png') }}"
            style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); width: 100%; height: auto; background-position: center; opacity: 0.1; z-index: -1;">

        </div>
        <div class="page-header">
            <table
                style="width: 100%; table-layout: fixed; border: none !important; margin-bottom: 0px; font-family: Arial, sans-serif !important;">
                <tr>
                    <td style="width: 10%; vertical-align: top; text-align: center;">
                        <img src="{{ public_path($schoolinfo->picurl) }}" alt="school" width="90px" />
                    </td>
                    <td style="text-align: center;">
                        <span style="font-size: 12px;">Republic of the Philippines</span><br>
                        <span style="font-size: 21px;"><b>{{ $schoolinfo->schoolname }}</b></span>
                        <br>
                        <span style="font-size: 13px;"> TECHNICAL EDUCATION AND SKILLS DEVELOPMENT AUTHORITY</span> <br>
                        <span style="font-size: 12px;">{{ $schoolinfo->address }}</span><br>
                    </td>

                    <td rowspan="2" style="width: 10%; text-align: center; vertical-align: top;">
                        <img src="{{ public_path('assets/images/tesda/tesdalogo.png') }}" alt="TESDA Logo"
                            width="90px" />
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center; font-family: 'Times New Roman', serif !important;">
                        <h2 style="margin-bottom: 0px; font-weight: normal;">Certificate of Registration</h2>
                    </td>
                </tr>
            </table>
        </div>

        <table width="100%" style="table-layout: fixed; margin-bottom: 20px; margin-top: 10px;">
            <tr>
                <td width="8%">
                    NAME
                </td>
                <td style="border-bottom: 1px solid black" width="30%">
                    {{ $student->lastname }}, {{ $student->firstname }} {{ $student->middlename }}
                </td>
                <td></td>
                <td width="50%" style="text-align: right">
                    Course: {{ $student->course_name }} {{ $student->course_duration }}
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="font-size: 12px">Lastname Firstname Middlename</td>
                <td></td>
                <td width="50%" style="text-align: right">
                    ID Number: {{ $student->sid }} Gender: {{ $student->gender }}
                </td>
            </tr>
        </table>


        <div class="footer">
            <table width="100%" style="table-layout: fixed; text-align: center; margin-bottom: 20px;">
                <tr>
                    <td width="33.3%">
                        <div class="signature-line"></div>
                        <small>Name & Signature Over Printed Name</small>
                    </td>
                    <td width="33.3%">
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
                    </td>
                </tr>
            </table>
        </div>


        <table width="100%" style="table-layout: fixed; font-size: 14px;border-collapse: collapse" border="1">
            <tr style="background-color: lightgray">
                <th>Competency Description</th>
                <th>Hrs</th>
                <th>Days</th>
                <th>Time</th>
                <th>Trainer</th>
            </tr>

            @foreach ($student->competencies as $compete => $competency)
                @if (!empty($competency))
                    <tr>
                        <td style="padding: 5px !important"><b>{{ $compete }}</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @foreach ($competency as $item)
                        @if (!empty($item->scheddetails))
                            @foreach ($item->scheddetails as $detail)
                                <tr>
                                    <td width="40%" style="padding:5px !important">{{ $item->competency_desc }}</td>
                                    <td width="10%" class="text-center" style="text-align: center">
                                        {{ $item->hours }}</td>
                                    <td width="20%" class="text-center" style="text-align: center">
                                        <span>{{ $detail->date_from ? \Carbon\Carbon::parse($detail->date_from)->format('M d, Y') : '--' }}</span><br>
                                        <span>{{ $detail->date_to ? \Carbon\Carbon::parse($detail->date_to)->format('M d, Y') : '--' }}</span>
                                    </td>
                                    <td width="15%" class="text-center" style="text-align: center">
                                        <span>{{ $detail->stime ?? '--' }}</span><br>
                                        <span>{{ $detail->etime ?? '--' }}</span>
                                    </td>
                                    @php
                                        $fullname = $detail->firstname . ' ' . $detail->lastname;
                                        $fullname = $fullname ?? '--';
                                    @endphp
                                    <td width="15%" style="text-align: center">{{ $fullname ?? '--' }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach

        </table>
    @endforeach

</body>

</html>
