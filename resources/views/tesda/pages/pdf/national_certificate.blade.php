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
            bottom: 30px;
            width: 100%;
            text-align: center;
            font-size: 14px;
            height: 150px;
        }

        .footer table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer img {
            width: 60px;
            /* Adjust image size */
        }
    </style>



</head>

<body>

    @foreach ($students as $index => $student)
        @if ($index > 0)
            <div style="page-break-before: always"></div>
        @endif

        <img src="{{ public_path('assets/images/tesda/tesdalogo.png') }}"
            style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); width: 100%; height: auto; background-position: center; opacity: 0.1; z-index: -1;">

        </div>
        <table
            style="width: 100%; table-layout: fixed; border: none !important;margin-bottom: 0px; font-family: Arial, sans-serif !important;">
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

                <td rowspan="2" style="width: 10%; text-align: center;vertical-align: top">
                    <img src="{{ public_path('assets/images/tesda/tesdalogo.png') }}" alt="student pic"
                        width="90px" />
                </td>
            </tr>
            <tr>
                <td colspan="3"style="text-align: center; font-family: 'Times New Roman', serif !important;">
                    <h1 style="margin-bottom: 0px; font-weight: normal;">NATIONAL CERTIFICATE II</h1><br>
                    <span class="title">in</span>
                </td>
            </tr>
        </table>

        <table width="100%" style="table-layout: fixed; border: none !important; text-align: center;">
            <tr>
                <td>
                    <p class="title">{{ $student->course_name }}</p>
                    <p>is awarded to</p>
                    <p class="recipient">{{ $student->firstname }}
                        {{ $student->middlename != null ? $student->middlename[0] : '' }}. {{ $student->lastname }}
                        {{ $student->suffix ?? '' }}</p>
                    </p>
                    <p>for having completed the competency requirements under the Philippine TVET Competency Assessment
                        and
                        Certifications System in the following units of competency:</p>
                </td>
            </tr>
        </table>

        <div class="footer">

            <table width="100%" style="table-layout: fixed; margin-bottom: 20px;">
                <tr>
                    <td>
                        <table width="100%" style="table-layout: fixed;">
                            <tr>
                                <td>Signature of the Certification holder</td>
                            </tr>
                            <tr>
                                <td>Certificate No.: <b>0212321</b></td>
                            </tr>
                            <tr>
                                <td><b>UL: RBB-a5-165-13097-001</b></td>
                            </tr>
                        </table>
                    </td>
                    <td style="text-align: right; table-layout: fixed;">
                        <table width="100%">
                            <tr>
                                <td style="text-align: right">Issued On: <b>{{ date('F d, Y') }}</b> </td>
                            </tr>
                            <tr>
                                <td style="text-align: right">Valid Until: <b>December 17, 2030</b> </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table>
                @php
                    if (strtoupper($student->gender) == 'FEMALE') {
                        $avatar = 'avatar/S(F) 1.png';
                    } else {
                        $avatar = 'avatar/S(M) 1.png';
                    }
                    $picExists = !empty($student->picurl) && file_exists(public_path($student->picurl));
                    // dd(URL::asset($student->picurl . '?random="' . \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss')));
                @endphp
                <tr>
                    <td style="vertical-align: center;">
                        <div>
                            {{-- <img src="{{ $avatar }}" alt="student avatar"
                                style="border: 2px solid black ; margin-top: 10px"> --}}
                            <img src="{{ $qrCodeBase64 }}" alt="QR Code">
                            <br>
                            CLN NO - 3327438
                        </div>
                    </td>
                    <td style="text-align: center; vertical-align: bottom; " width="50%">
                        <b>SEC. ISIDRO S. LAPENA, PhD, CSEE</b><br>Director General
                    </td>
                    <td style="text-align: right !important; vertical-align: top; " width="25%">

                        @if ($picExists)
                            @php
                                $picurl =
                                    str_replace('jpg', 'png', $student->picurl) .
                                    '?random="' .
                                    \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss') .
                                    '"';
                                // dd(asset($picurl));
                            @endphp
                            <img src="{{ $picurl }}"
                                onerror="this.onerror = null, this.src='{{ $avatar }}'" alt="student pic"
                                style="margin: 0px; position: absolute;border: 1px solid black; height: 1in; width: 1in; background-size: cover !important; background-position: center; background-repeat: no-repeat;">

                            {{-- <img src="{{ asset($picurl) }}" alt="student pic" style="width: 100%; margin: 0px; position: absolute;border: 1px solid black; height: 146px;" onerror="this.onerror=null;this.src='{{ asset('avatar/unknown.png') }}'"> --}}
                        @else
                            <img src="{{ $avatar }}" alt="student avatar"
                                style="margin: 0px; border: 2px solid black ; height: 1in; width: 1in; position: absolute; right: 0;">
                        @endif

                    </td>
                </tr>
            </table>
        </div>

        <table width="100%" style="table-layout: fixed;font-size: 14px;">

            @foreach ($student->competencies as $competency)
                @if (count($competency) > 0)
                    @php
                        // dd($competency);
                        $firstcol = $competency[0] ?? [];
                        $secondcol = $competency[1] ?? [];
                        // dd($firstcol, $secondcol);
                    @endphp
                    <tr>
                        @if (count($firstcol) > 0)
                            <td width="45%">
                                <table width="100%" style="table-layout: fixed; ">
                                    <tr style="font-weight: bold">
                                        <td>UNIT CODE</td>
                                        <td width="60%" style="text-transform: uppercase;">
                                            {{ $firstcol[0]->competency_type ?? 'UNSPECIFIED' }} </td>
                                    </tr>
                                    @foreach ($firstcol as $item)
                                        <tr>
                                            <td>{{ $item->competency_code ?? '' }}</td>
                                            <td>{{ $item->competency_desc ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        @endif
                        <td width="10%"></td>

                        @if (isset($secondcol) && count($secondcol) > 0)
                            <td width="45%">
                                <table width="100%" style="table-layout: fixed; ">
                                    <tr style="font-weight: bold">
                                        <td>UNIT CODE</td>
                                        <td width="60%" style="text-transform: uppercase;">
                                            {{ $secondcol[0]->competency_type ?? 'UNSPECIFIED' }} </td>
                                    </tr>
                                    @foreach ($secondcol as $item)
                                        <tr>
                                            <td>{{ $item->competency_code ?? '' }}</td>
                                            <td>{{ $item->competency_desc ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        @endif
                    </tr>
                @endif
            @endforeach
        </table>
    @endforeach

</body>

</html>
