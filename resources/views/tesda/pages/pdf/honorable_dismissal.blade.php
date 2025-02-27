<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Honorable Dismissal</title>
    <style>
        body {
            font-family: 'Times New Roman', serif, sans-serif;
            text-align: center;
            font-size: 16px;
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


        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 100px;
        }

        .footer table {
            width: 100%;
            border-collapse: collapse;
        }

        .certificate {
            text-align: left;
            /* border: 2px solid black; */
            /* padding: 30px; */
            max-width: 700px;
            margin: auto;
        }

        .bold {
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
            font-weight: bold;
        }

        .note {
            font-style: italic;
        }
    </style>

    <style>
        .certificate-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 500px;
            /* Full page height */
            text-align: center;
            padding: 20px;
        }

        .certificate {
            width: 80%;
            /* Adjust width as needed */
            max-width: 600px;
            font-size: 16px;
            line-height: 2.0;
        }

        .bold {
            font-weight: bold;
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

        <div class="footer">
            <table width="100%" style="table-layout: fixed;">
                <tr>
                    <td width="50%">

                    </td>
                    <td width="50%" style="text-align: right">
                        <b>SEC. ISIDRO S. LAPEÑA PhD, CSEE</b> <br>
                        <span style="font-weight: normal; text-align: center;">Director General</span>
                    </td>
                </tr>
                <tr>
                    <td width="50%">
                        <p class="note">Not Valid without School seal</p>
                    </td>
                    <td wdith="50%">

                    </td>
                </tr>
            </table>
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
                    <br>
                    <br>
                    <h1
                        style="margin-top: 20px; font-size:30px; font-family: Times New Roman, serif; font-weight:
                                normal; color: darkblue;">
                        Certificate of Honorable Dismissal
                    </h1>
                </td>
            </tr>
        </table>
        <br>
        <br>


        <div class="certificate-container">
            <div class="certificate">
                <p>To Whom It May Concern</p>

                <p>
                    This is to certify that <span class="bold">{{ $student->firstname }}
                        {{ $student->middlename != null ? $student->middlename[0] . '.' : '' }} {{ $student->lastname }}
                        {{ $student->suffix }}</span>,
                    a student of this institution during school year {{ $student->series_desc }} in the course of
                    <span class="bold">{{ $student->course_name }} ({{ $student->course_duration }})</span>, is
                    hereby
                    granted Honorable Dismissal effective today, {{ date('F d, Y') }}.
                </p>

                <p>
                    This certificate of honorable dismissal is issued upon request of the interested party
                    for any legal purposes it may serve.
                </p>
            </div>
        </div>
    @endforeach

</body>

</html>
