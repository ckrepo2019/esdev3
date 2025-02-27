<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Enrolled Students Summary</title>
        <style>
            .table {
                width: 100%;
                margin-bottom: 1rem;
                background-color: transparent;
                font-size: 11px;
            }

            table {
                border-collapse: collapse;
            }

            .table td,
            .table th {
                padding: .75rem;
                vertical-align: top;
            }

            .table-bordered {
                border: 1px solid #000;
            }

            .table-bordered td,
            .table-bordered th {
                border: 1px solid #000;
            }

            .table-sm td,
            .table-sm th {
                padding: .3rem;
            }

            .text-center {
                text-align: center !important;
            }

            .text-right {
                text-align: right !important;
            }

            .text-left {
                text-align: left !important;
            }

            .p-0 {
                padding: 0 !important;
            }

            .p-1 {
                padding: .25rem !important;
            }

            .mb-0 {
                margin-bottom: 0;
            }

            .border-bottom {
                border-bottom: 1px solid black;
            }

            .mb-1,
            .my-1 {
                margin-bottom: .25rem !important;
            }

            body {
                font-family: Arial, sans-serif;
            }

            .align-middle {
                vertical-align: middle !important;
            }

            .grades td {
                padding-top: .1rem;
                padding-bottom: .1rem;
                font-size: 9px !important;
            }

            .studentinfo td {
                padding-top: .1rem;
                padding-bottom: .1rem;
            }

            .text-red {
                color: red;
                border: solid 1px black;
            }

            .table .thead-light th {
                color: #495057;
                background-color: #e9ecef;
                border-color: #dee2e6;
            }

            .table-striped tbody tr:nth-of-type(odd) {
                background-color: rgba(0, 0, 0, .05);
            }

            .page_break {
                page-break-before: always;
            }

            @page {
                size: 8.5in 11in;
                margin: .25in;
            }
        </style>
    </head>

    <body>

        <table class="table mb-0 table-sm header" style="font-size:13px;">
            <tr>
                <td width="20%" rowspan="2" class="text-right align-middle p-0">
                    <img src="{{ base_path() }}/public/{{$schoolinfo->picurl}}" alt="school" width="80px">
                </td>
                <td width="60%" class="p-0 text-center">
                    <h3 class="mb-0" style="font-size:20px !important">{{$schoolinfo->schoolname}}</h3>
                </td>
                <td width="20%" rowspan="2" class="text-right align-middle p-0">
                </td>
            </tr>
            <tr>
                <td class="p-0 text-center">
                    {{$schoolinfo->address}}
                </td>
            </tr>
        </table>
        <table class="table mb-0 table-sm" style="font-size:13px;">
            <tr>
                <td width="100%" class="text-center p-0" style="font-size:15px; !important"><b>Official Class List</b>
                </td>
            </tr>
            <tr>
                <td width="100%" class="text-center p-0" style="font-size:15px; !important"><b>Programming Basic -
                        {{ $printdata->sectionDesc }}</b></td>
            </tr>
            <tr>
                <td width="100%" class="text-center p-0">{{$syinfo->sydesc}} {{ $sem }} Semester</td>
            </tr>
        </table>
        <br>
        <br>



        @foreach ($printdata->schedule as $sched)
        <div class="row">
            Schedule: {{ $sched->time }} / {{ $sched->day }}
        </div>
        @endforeach



        <br>

        <!-- Male Students Table -->
        <div id="maleStudentsContainer">
            <table id="maleStudentsTable"
                class="table table-bordered table-sm table-responsive-md table-vcenter text-center no-footer">
                <thead class="bg-gray">
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Academic Level</th>
                        <th>Course</th>
                        <th>Contact No.</th>
                        <th>Email Address</th>
                        <th>Address</th>
                    </tr>
                    <tr>
                        <th colspan="7" class="p-5">Male</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($maleArr as $male)
                    <tr>
                        <td>{{ $male->sid }}</td>
                        <td>{{ $male->lastname }}, {{ $male->firstname }} {{ $male->middlename }}</td>
                        <td>{{ $male->levelname }}</td>
                        <td>{{ $male->courseabrv }}</td>
                        <td>{{ $male->contactno }}</td>
                        <td>{{ $male->semail ?? 'Not Specified'}}</td>
                        <td>{{ $male->barangay }}, {{ $male->city }} {{ $male->province }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <th colspan="7" class="p-5">Female</th>
                    </tr>
                    @foreach ($femaleArr as $female)
                    <tr>
                        <td>{{ $female->sid }}</td>
                        <td>{{ $female->lastname }}, {{ $female->firstname }} {{ $female->middlename }}</td>
                        <td>{{ $female->levelname }}</td>
                        <td>{{ $female->courseabrv }}</td>
                        <td>{{ $female->contactno }}</td>
                        <td>{{ $female->semail ?? 'Not Specified'}}</td>
                        <td>{{ $female->barangay }}, {{ $female->city }} {{ $female->province }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br>
        <!-- Female Students Table -->
        {{-- <div id="femaleStudentsContainer">
        <table id="femaleStudentsTable" class="table table-bordered table-responsive-md table-vcenter text-center no-footer">
            <thead class="bg-gray">
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Academic Level</th>
                    <th>Course</th>
                    <th>Contact No.</th>
                    <th>Email Address</th>
                    <th>Address</th>
                </tr>
                <tr>
                    <th colspan="7" class="p-2 bg-pink">Female</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($femaleStudents as $student)
                    <tr>
                        <td>{{ $student->sid }}</td>
        <td>{{ $student->lastname }}, {{ $student->firstname }} {{ $student->middlename }}</td>
        <td>{{ $student->levelname }}</td>
        <td>{{ $student->courseDesc }}</td>
        <td>{{ $student->contactno }}</td>
        <td>{{ $student->semail }}</td>
        <td>{{ $student->barangay }}, {{ $student->city }} {{ $student->province }}</td>
        </tr>
        @endforeach
        </tbody>
        </table>
        </div> --}}
        <br>

        {{-- <table>
            <tr>
                <th>
                    <h4 class="mb-0">{{ $printdata->teacher_name }}</h4>
                    <p style="color: #0258af; font-style: italic;">Instructor</p>
                </th>
            </tr>
        </table> --}}

    </body>

</html>