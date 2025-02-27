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
                                {{-- Republic of the Philippines --}}
                                <h2 style="font-size: 28px; font-weight: bold;">
                                    {{-- {{ $schoolInfo->schoolname }} --}}
                                    {{-- GABRIEL TABORIN COLLEGE OF DAVAO FOUNDATION, INC. --}}
                                    GABRIEL TABORIN COLLEGE OF DAVAO FOUNDATION, INC.
                                </h2>

                                <p style="font-size: 20px; margin-bottom: 0px;">
                                    {{-- {{ $schoolInfo->division }}<br>
                                    {{ $schoolInfo->district }} {{ $schoolInfo->address }}<br>
                                    {{ $schoolInfo->region }} --}}
                                    Lasang, Davao City
                                </p>
                                {{-- <br> --}}
                                <p style="font-size: 20px;">
                                    TESDA
                                </p>
                            </div>
                            <br><br>
                            <p style="font-size:18px;text-align: left;">December 16, 2024</p>
                            <br>
                            <br>
                            <span
                                style="font-size: 16px; text-align: left; display: block; margin-top: 5px; font-weight: bold;">MYRNA
                                C. ROA</span>
                            <span style="font-size: 16px;text-align: left; display: block; margin-top: 5px;">Acting
                                District
                                Director</span>
                            <span style="font-size: 16px;text-align: left; display: block; margin-top: 5px;">Technical
                                Education Skills Developoment Authority</span>
                            <span style="font-size: 16px;text-align: left; display: block; margin-top: 5px;">616 Rimas
                                St., Int. 2 Aquino Subdivision,</span>
                            <span style="font-size: 16px;text-align: left; display: block; margin-top: 5px;">J.P Laurel
                                Ave, Davao City Philippines</span>
                            <br>
                            <br>
                            <span style="font-size: 35px; display: block;">
                                {{-- {{ $student->firstname }}
                                {{ $student->middlename }}
                                {{ $student->lastname }}</span> --}}
                                <span
                                    style="font-size: 16px; text-align: left; display: block; margin-top: 5px; font-weight: bold;">Dear
                                    Maam;</span>
                                <br>

                                <span style="font-size: 18px; display: block; margin-top: 5px; text-align: justify;">I
                                    have the honor to
                                    recommend for Graduation, upon satisfactory completion of the course 336 HOURS
                                    BARTENDING NC II as
                                    of January, 2009 the following candidate/s:</span>
                                <br>
                                <table
                                    style="font-size: 14px; border: 1px solid #a8a8a8; border-collapse: collapse; margin: auto;">
                                    <tr style="background-color: #e4e4e4;">
                                        <th style="width: 5%; border: 1px solid #a8a8a8; padding: 5px;">No.</th>
                                        <th style="width: 70%; border: 1px solid #a8a8a8; padding: 5px;">Student Name
                                        </th>
                                        <th style="width: 25%; border: 1px solid #a8a8a8; padding: 5px;">Gender</th>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; border: 1px solid #a8a8a8; padding: 5px;">1</td>
                                        <td style="border: 1px solid #a8a8a8; padding: 5px;">Adore, Lemuel L.</td>
                                        <td style="text-align: center; border: 1px solid #a8a8a8; padding: 5px;">Male
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; border: 1px solid #a8a8a8; padding: 5px;">2</td>
                                        <td style="border: 1px solid #a8a8a8; padding: 5px;">Balancar, Mariz L.</td>
                                        <td style="text-align: center; border: 1px solid #a8a8a8; padding: 5px;">Female
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; border: 1px solid #a8a8a8; padding: 5px;">3</td>
                                        <td style="border: 1px solid #a8a8a8; padding: 5px;">Conde, Jessie Jay K.</td>
                                        <td style="text-align: center; border: 1px solid #a8a8a8; padding: 5px;">Male
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; border: 1px solid #a8a8a8; padding: 5px;">4</td>
                                        <td style="border: 1px solid #a8a8a8; padding: 5px;">Dante, Michael G.</td>
                                        <td style="text-align: center; border: 1px solid #a8a8a8; padding: 5px;">Male
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; border: 1px solid #a8a8a8; padding: 5px;">5</td>
                                        <td style="border: 1px solid #a8a8a8; padding: 5px;">Gonzales, Ma. Patricia</td>
                                        <td style="text-align: center; border: 1px solid #a8a8a8; padding: 5px;">Female
                                        </td>
                                    </tr>
                                </table>
                                <span style="font-size: px; display: block; margin-top: 5px;">
                                    {{-- {{ $student->course_name }} --}}
                                    Valid for Ten (10) Students only
                                </span>
                                <span
                                    style="font-size: 16px; text-align: left; display: block; margin-top: 5px; font-weight: bold;">Summary</span>
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
