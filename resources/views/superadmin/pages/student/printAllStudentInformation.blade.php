<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Loading Report</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .table th,
        .table td {
            padding: 8px;
            border: 1px solid black;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="page-header" style="width: 100%; text-align: center; margin-bottom: 20px;">
            <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
                <tr>
                    <td width="40%" class=" text-right" style=" vertical-align: middle; text-align: right; margin: 60px;">
                        <img src="{{ public_path($schoolInfo->picurl) }}" alt="school" width="90px" />
                    </td>
                    <td width="25%" class="align-middle">
                        {{-- <span style="font-size: 12px;">Republic of the Philippines</span><br> --}}
                        <span style="font-size: 21px;">{{ $schoolInfo->schoolname }}</span><br>
                        {{-- <span style="font-size: 13px;">TECHNICAL EDUCATION AND SKILLS DEVELOPMENT AUTHORITY</span><br> --}}
                        <span style="font-size: 12px;">{{ $schoolInfo->address }}</span><br>
                    </td>
                    <td width="35%" class="align-middle"> </td>
                </tr>
            </table>

            <div style="text-align: center; margin-top: 50px;">
                <span style="font-size: 16px; font-weight: 600;">Student Information List</span><br>
                @php
                    $firstStudent = $students->first();
                @endphp
                @if ($firstStudent)
                    <span style="font-size: 12px; font-weight: normal;">{{ $firstStudent->sydesc }},
                        {{ $firstStudent->semester }}</span>
                @endif
            </div>
        </div>


        <div>
            @php
                $firstStudent = $students->first(); // Get the first student in the collection
            @endphp

            @if ($firstStudent)
                <span style="font-size: 12px;font-family: Arial, sans-serif; margin-bottom:50px">{{ $firstStudent->levelname }},
                    {{ $firstStudent->courseDesc }}</span>
            @endif
        </div><br>

        <table class="table table-bordered text-center" style="width: 100%;">
            <thead style="background-color: #e4e4e4;">
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Year Level</th>
                    <th>Course</th>
                    <th>Contact #</th>
                    <th>Email Ad</th>
                    <th>Date of Birth</th>
                    <th>Address</th>
                    <th>Incase of Emergency</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $student)
                    <tr>
                        <td>{{ $student->sid }}</td>
                        <td>
                            {{ $student->lastName }},
                            {{ $student->firstName }}
                            @if ($student->middleName)
                                {{ $student->middleName }}
                            @endif
                            @if ($student->suffix)
                                {{ $student->suffix }}
                            @endif
                        </td>
                        <td>{{ $student->levelname }}</td>
                        <td>{{ $student->courseDesc }}</td>
                        <td>{{ $student->contactno }}</td>
                        <td>{{ $student->semail }}</td>
                        <td>{{ $student->dob }}</td>
                        <td>{{ $student->fulladdress }}</td>
                        <td>{{ $student->emergency_contact }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No students found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</html>
