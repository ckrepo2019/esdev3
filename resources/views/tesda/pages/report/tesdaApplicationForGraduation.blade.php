<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Graduation Recommendation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
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
    </style>
</head>

<body>
    <!-- Header Section -->
    <!-- <div class="row mb-4">
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
                        </div>
                    </td>
                    <td style="width: 15%; text-align: right;">
                        <img src="{{ public_path($schoolInfo->picurl) }}" alt="Logo" class="logo"
                            style="width: 100px; height: 40px; position: absolute; top: 0;">
                    </td>
                </tr>
            </table>
        </div>
    </div> -->
    
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
    <br>
    <!-- Recipient Info -->
    <div>
        <p>{{ \Carbon\Carbon::now()->format('F d, Y') }}</p>
        <br>
        <p>
            MYRNA C. ROA<br>
            Acting District Director<br>
            Technical Education and Skills Development Authority<br>
            616 Rimas St., Int. 2 Aquino Subdivision,<br>
            J.P. Laurel Ave, Davao City Philippines
        </p>
        <p>Dear Ma'am;</p>

        @php
            $student = $students->first(); // Get the first student
        @endphp

        @if ($student)
            <p>
                I have the honor to recommend for Graduation, upon satisfactory completion of the course
                <b>{{ $student->course_duration }} HOURS {{ $student->course_name }}</b>
                as of {{ \Carbon\Carbon::parse($student->date_to ?? 'N/A')->format('F, Y') }} the following candidate/s:
            </p>
        @endif
    </div>


    <!-- Students Table -->
    <table style="width:60%; margin: 0 auto;" class="table-bordered">
        <thead>
            <tr style="background-color: #e3e1e1;">
                <th>No.</th>
                <th>Student Name</th>
                <th>Gender</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $index => $student)
                <tr>
                    <td style="text-align:center">{{ $index + 1 }}</td>
                    <td>{{ $student->firstname }} {{ $student->middlename }} {{ $student->lastname }}</td>
                    <td>{{ $student->gender }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary -->
    <p style="text-align:center; font-weight: bold;">Valid for Ten (10) Students only</p>
    @php
        $femaleCount = $students->where('gender', 'Female')->count();
        $maleCount = $students->where('gender', 'Male')->count();
        $totalStudents = $students->count();
    @endphp
    <table style="width:20%; text-align:center; margin: 0 auto;">
        <thead>
            <tr>
                <th>Summary</th>
                <th></th>
            </tr>
        </thead>
        <tbody style="text-align: left">
            <tr>
                <td>Female</td>
                <td>{{ $femaleCount }}</td>
            </tr>
            <tr>
                <td>Male</td>
                <td>{{ $maleCount }}</td>
            </tr>
            <tr>
                <td><b>Total</b></td>
                <td><b>{{ $totalStudents }}</b></td>
            </tr>
        </tbody>
    </table>


    <!-- Footer -->
    <div class="footer">
        <p>
            I certify that the name of the above students has been checked and verified against School records, and
            the course is covered by <b>Certificate of TVET Program Registration WTR No. 0711042102</b>.
        </p>
    </div>
    <br>
    <p style="text-align:left">Respectfully yours,</p>
    <br>
    <p style="text-align:left"><strong>ARABELLA R. MEDIDA</strong></p>
    <p style="text-align:left">School Registrar</p>

</body>

</html>
