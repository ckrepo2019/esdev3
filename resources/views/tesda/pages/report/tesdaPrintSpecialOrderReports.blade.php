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
        <p>Dear Ms. Roa:</p>

        @php
            $student = $students->first(); // Get the first student
        @endphp

        @if ($student)
            <p>
                I am writing to express our intent to submit the Special Order Request for the training program
                conducted by our institution.
                <br>
                Training Program Details:
                <br>
            </p>
        @endif
    </div>


    <!-- Students Table -->
    <table style="width:80%; margin: 0 auto;" class="table-bordered">
        <thead>
            <tr style="background-color: #e3e1e1;">
                <th>No.</th>
                <th>Student Name</th>
                <th>Gender</th>
                <th>Qualification</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $index => $student)
                <tr>
                    <td style="text-align:center">{{ $index + 1 }}</td>
                    <td>{{ $student->lastname }}, {{ $student->firstname }} {{ $student->middlename }}
                        {{ $student->suffix ?? '' }}</td>
                    <td>{{ $student->gender }}</td>
                    <td>{{ $student->course_duration }} {{ $student->course_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary -->
    <p style="text-align:left;">Enclosed are the required documents.</p>

    <br>
    <p style="text-align:left">Respectfully yours,</p>
    <br>
    <p style="text-align:left"><strong>ARABELLA R. MEDIDA</strong></p>
    <p style="text-align:left">School Registrar</p>

</body>

</html>
