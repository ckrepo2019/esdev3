<link rel="stylesheet" href="{{ asset('plugins/fullcalendar/main.min.css') }}">
{{-- <link rel="stylesheet" href="{{asset('plugins/fullcalendar-interaction/main.min.css')}}"> --}}
<link rel="stylesheet" href="{{ asset('plugins/fullcalendar-daygrid/main.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fullcalendar-timegrid/main.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fullcalendar-bootstrap/main.min.css') }}">
@extends('deanportal.layouts.app2')

@section('pagespecificscripts')
    <style>
        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }

        .card {
            font-size: 10px;
        }

        .card-body {
            padding: 0.5rem;
        }
    </style>
@endsection

@section('content')
    @php
        $term = DB::table('college_termgrading')->where('deleted', 0)->get();
        $colleges = DB::table('college_colleges')->where('cisactive', 1)->where('deleted', 0)->get();
        $college_gradelevel = DB::table('gradelevel')->where('acadprogid', 6)->where('deleted', 0)->get();

        $syid = request()->get('syid');
        $semid = request()->get('semid');

        // Fetch the teacher information and their role
        $teacher = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first();

        $teacherName = $teacher->firstname . ' ' . $teacher->lastname;

        $role = '';
        $acadprogQuery = null;

        if (Session::get('currentPortal') == 16) {
            $role = 'PROGRAM HEAD';
            $acadprogQuery = DB::table('teacherprogramhead')
                ->where('teacherprogramhead.deleted', 0)
                ->where('teacherprogramhead.syid', $syid)
                ->where('teacherid', $teacher->id)
                ->join('college_courses', 'teacherprogramhead.courseid', '=', 'college_courses.id')
                ->where('college_courses.deleted', 0)
                ->join('college_colleges', 'college_courses.collegeid', '=', 'college_colleges.id')
                ->where('college_colleges.deleted', 0);
        } elseif (Session::get('currentPortal') == 14) {
            $role = 'DEAN';
            $acadprogQuery = DB::table('teacherdean')
                ->where('teacherdean.deleted', 0)
                ->where('teacherdean.syid', $syid)
                ->where('teacherid', $teacher->id)
                ->join('college_colleges', 'teacherdean.collegeid', '=', 'college_colleges.id')
                ->where('college_colleges.deleted', 0)
                ->join('college_courses', 'college_colleges.id', '=', 'college_courses.collegeid')
                ->where('college_courses.deleted', 0);
        }

        $courses = DB::table('teacherdean')
            ->where('teacherdean.deleted', 0)
            ->where('college_colleges.deleted', 0)
            ->where('college_courses.deleted', 0)
            ->where('teacherid', $teacher->id)
            ->join('college_colleges', function ($join) {
                $join->on('teacherdean.collegeid', '=', 'college_colleges.id')->where('college_colleges.deleted', 0);
            })
            ->join('college_courses', function ($join) {
                $join->on('college_colleges.id', '=', 'college_courses.collegeid')->where('college_courses.deleted', 0);
            })
            ->select('college_courses.*')
            ->get();

        $teacher = DB::table('users')
            ->join('teacher', 'users.id', '=', 'teacher.userid')
            ->where('teacher.userid', auth()->user()->id)
            ->first();

        // Fetch the dean based on the teacher's ID
$deanColleges = DB::table('teacherdean')
    ->join('college_colleges', 'teacherdean.teacherid', '=', 'college_colleges.dean')
    ->where('teacherdean.teacherid', $teacher->id)
    ->where('college_colleges.deleted', 0)
    ->select('teacherdean.teacherid as deanid')
    ->first();

$collegesDean = DB::table('teacherdean')
    ->join('college_colleges', 'teacherdean.teacherid', '=', 'college_colleges.dean')
    ->where('teacherdean.teacherid', $teacher->id)
    ->where('college_colleges.deleted', 0)
    ->select('college_colleges.*')
    ->groupBy('college_colleges.id')
    ->get();

$tapHistory = DB::table('taphistory')
    ->where('studid', $teacher->id)
    ->where('deleted', 0)
    ->select('taphistory.ttime')
    ->get();

// Fetch courses handled
// $courses = $acadprogQuery
//     ? $acadprogQuery->select('college_colleges.collegeDesc', 'college_courses.courseDesc', 'college_courses.courseabrv')->distinct()->get()
//     : collect();

// // Fetch enrolled summary data
// $enrolledSummary = DB::table('college_enrolledstud')
//     ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
//     ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
//     ->where('syid', $syid)
//     ->where('semid', $semid)
//     ->select('college_courses.courseDesc', 'gradelevel.levelname',  DB::raw('COUNT(*) as total_students'))
        //     ->get();

        // dd($courses);

    @endphp


    {{-- <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card container-fluid bg-primary shadow">
                    <div class="card-body text-center ">
                        <b>WELCOME TO DEAN'S PORTAL</b>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    @if ($schoolinfo->projectsetup == 'offline' || $schoolinfo->processsetup == 'all')
                        <div id="terminalsetup" class="col-md-4" style="cursor: pointer;"
                            onclick="location.href='/setup/prospectus'">
                            <div class="small-box bg-info shadow">
                                <div class="inner">
                                    <h3 class="">Prospectus <br>Setup</h3>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-book"></i>
                                </div>
                                <a class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div id="terminalsetup" class="col-md-4" style="cursor: pointer;"
                            onclick="location.href='/college/section'">
                            <div class="small-box bg-success  shadow">
                                <div class="inner">
                                    <h3 class="">College<br>Sections</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-cubes"></i>
                                </div>
                                <a class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div id="terminalsetup" class="col-md-4" style="cursor: pointer;"
                            onclick="location.href='/student/loading'">
                            <div class="small-box bg-secondary shadow">
                                <div class="inner">
                                    <h3 class="">Student<br>Loading</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-truck-loading"></i>
                                </div>
                                <a class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if (($schoolinfo->projectsetup == 'online' && $schoolinfo->processsetup == 'hybrid1') || $schoolinfo->processsetup == 'all')
                        <div id="terminalsetup" class="col-md-4" style="cursor: pointer;" onclick="location.href='/college/grade/monitoring/teacher'">
                            <div class="small-box bg-danger  shadow">
                                <div class="inner">
                                    <h3 class="">Grade<br>Teacher</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <a class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                    <div id="terminalsetup" class="col-md-4" style="cursor: pointer;" onclick="location.href='/user/profile'">
                        <div class="small-box bg-warning  shadow">
                            <div class="inner">
                                <h3 class="">My<br>Profile</h3>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <a class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}


    <div class="container-fluid">
        <div class="row mt-3">
            <!-- Left Card: Welcome Section -->
            <div class="col-6 mt-3">
                <div class="card shadow" style="width: 100%; min-height: 173px;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="font-weight-bold">Welcome, {{ $teacherName }}!</h6>
                                <p class="font-weight-bold" style="font-size: 14px;">{{ $role }}</p>
                                <p id="deanAssignedCourse" class="font-weight-bold text-success">
                                    @foreach ($collegesDean->groupBy('id') as $dean_college_group)
                                        {{ $dean_college_group->first()->collegeDesc }}
                                        @if (!$loop->last)
                                            <br>
                                        @endif
                                    @endforeach
                                </p>
                            </div>
                            <div class="col-6 text-right" style="font-size: 18px;">
                                <p><strong>{{ \Carbon\Carbon::now()->format('F d, Y') }}</strong></p>
                                <p class="font-weight-bold text-success">
                                    Tap In:<br>
                                    @if (count($tapHistory) > 0)
                                        {{ $tapHistory[0]->ttime }}
                                    @else
                                        Not yet tap
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Right Card: Enrolled Summary Section -->
            <div class="col-12 col-md-6 mt-3">
                <div class="card shadow" style="width: 100%; overflow-y: auto;">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless" id="enrolledsummarytable">
                                <thead style="font-size: 12px;">
                                    <tr>
                                        <th>Enrolled Summary</th>
                                        <th>1st Year</th>
                                        <th>2nd Year</th>
                                        <th>3rd Year</th>
                                        <th>4th Year</th>
                                        <th>5th Year</th>
                                    </tr>
                                </thead>
                                <tbody id="enrolledsummarytablebody" style="font-size: 10px;" class="text-success">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="container-fluid mt-1">
        <div class="row">
            <!-- Left Card: Grade Status -->
            <div class="col-12 col-md-6">
                <input type="hidden" id="deanid" data-id=""> <!-- Set this value dynamically -->
                <div class="card shadow" style="height: 300px; width: 100%;">
                    <!-- Card Header -->
                    <div class="card-header bg-gray text-white d-flex align-items-center">
                        <h6 class="mb-0 me-3  m-2">College</h6>
                        <select class="form-control w-50 ms-3" id="collegeSelect1">
                            @foreach ($collegesDean as $college)
                                <option value="{{ $college->id }}">{{ $college->collegeDesc }}</option>
                            @endforeach
                        </select>

                        <h6 class="mb-0 me-3 ms-3 m-2">Term</h6>
                        <select class="form-control w-50 ms-1" id="termSelect">
                            @foreach ($term as $t)
                                <option value="{{ $t->id }}">{{ $t->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body d-flex justify-content-around align-items-center" style="height:100%; ">
                        <div class="text-left border-right"
                            style="width: 30%; font-size: 12px;">
                            <div class="border-bottom align-items-center text-center">
                                <h1 class="text-Primary d-inline-block p-1 submittedGrades fw-bold"></h1>
                                <p class=" ">Submitted Grades</p>
                            </div>
                            <div class="align-items-center text-center">
                                <h1 class="text-Primary d-inline-block p-1 pendingGrades "></h1>
                                <p class="">Pending Grades</p>
                            </div>
                        </div>
                        {{-- <canvas id="gradeStatusChart" style="width: 100px; height: 100px;"></canvas> --}}

                        <div class="card-body text-right"
                            style="width: 50%; font-size: 12px;">
                            <h5 class="text-center"><strong>Grade Status</strong></h5>

                            <div class="chart text-left mt-3">
                                <canvas id="doughnut_reported_case"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Card: Enrolled Summary -->
            <div class="col-12 col-md-6">
                <div class="card shadow" style="height: 300px; width: 100%;">
                    <!-- Card Header -->
                    <div class="card-header bg-gray text-white d-flex align-items-center">
                        <h6 class="mb-0 me-3 m-2">College</h6>
                        <select class="form-control w-auto ms-3" id="collegeSelect2">
                            @foreach ($collegesDean as $college)
                                <option value="{{ $college->id }}">{{ $college->collegeDesc }}</option>
                            @endforeach
                        </select>
                        <a href="#" class="ms-auto text-decoration-underline text-white ml-3" id="viewMoreInfo">View
                            More Info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        {{-- <h5 class="text-center"><strong>Enrolled Student By Course</strong></h5> --}}
                        <canvas id="enrollmentChart" style="width: 40%; height: 40%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Left Card: Welcome Section -->
            <div class="col-12">
                <div class="card shadow schoolcalendar" style="width: 100%;">
                    <!-- Displaying current school year and semester -->
                    {{-- <div class="card-header">
                        <h3 class="card-title text-bold">School Calendar</h3>
                    </div> --}}
                    <div class="card-body">
                        <div class="calendarHolder">
                            <div id='newcal'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>

    {{-- <----------------Calendar--------------------> --}}
    <script src="{{ asset('plugins/fullcalendar/main.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-daygrid/main.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-timegrid/main.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-interaction/main.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-bootstrap/main.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#collegeSelect1').select2()
            $('#collegeSelect2').select2()
            $('#termSelect').select2()
            $('#collegeSelect1').on('change', function() {
                load_doughnut_reported_case();
            });

            function load_doughnut_reported_case() {
                var syid = $('#syid').val();
                var semid = $('#semid').val();
                var deanid = $('#deanid').data('id');
                var collegeid = $('#collegeSelect1').val();

                $.ajax({
                    url: '/getGradeStatus',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        syid: syid,
                        semid: semid,
                        deanid: deanid,
                        collegeid: collegeid
                    },
                    success: function(response) {
                        $('.submittedGrades').text(response.studentGrades.Submitted || 0);
                        $('.pendingGrades').text(response.studentGrades.Pending || 0);

                        var pieChartData = {
                            labels: ['Not Submitted', 'Submitted', 'Pending', 'Approved', 'Posted'],
                            datasets: [{
                                data: [
                                    response.studentGrades['Not Submitted'] || 0,
                                    response.studentGrades['Submitted'] || 0,
                                    response.studentGrades['Pending'] || 0,
                                    response.studentGrades['Approved'] || 0,
                                    response.studentGrades['Posted'] || 0
                                ],
                                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56',
                                    '#4CAF50', '#FF9F40'
                                ],
                                hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56',
                                    '#4CAF50', '#FF9F40'
                                ]
                            }]
                        };

                        var pieChartCanvas = document.getElementById('doughnut_reported_case');
                        new Chart(pieChartCanvas, {
                            type: 'pie',
                            data: pieChartData,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                legend: {
                                    position: 'right',
                                    labels: {
                                        fontColor: '#333',
                                        fontSize: 10,
                                        boxWidth: 15,
                                        padding: 10
                                    }
                                }
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching grade status:', error);
                    }
                });
            }

            function load_bar_enrollment_report() {
                var syid = $('#syid').val();
                var semid = $('#semid').val();
                var collegeId = $('#collegeSelect2').val();

                $.ajax({
                    url: '/getTotalEnrollees',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        syid: syid,
                        semid: semid,
                        college_id: collegeId
                    },
                    success: function(response) {
                        if (response.success) {
                            var courseNames = [];
                            var studentCounts = [];

                            for (const [college, courses] of Object.entries(response.data)) {
                                for (const [course, count] of Object.entries(courses)) {
                                    courseNames.push(`${course}`);
                                    studentCounts.push(count);
                                }
                            }

                            var chartData = {
                                labels: courseNames,
                                datasets: [{
                                    label: 'Enrolled Students',
                                    backgroundColor: '#36A2EB',
                                    borderColor: '#36A2EB',
                                    borderWidth: 1,
                                    data: studentCounts,
                                }],
                            };

                            var barChartCanvas = document.getElementById('enrollmentChart').getContext(
                                '2d');

                            // Destroy previous chart if it exists
                            if (window.myBarChart) {
                                window.myBarChart.destroy();
                            }

                            // Create the bar chart
                            window.myBarChart = new Chart(barChartCanvas, {
                                type: 'bar',
                                data: chartData,
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            min: 100,
                                            max: 1000,
                                            stepSize: 100,
                                        }
                                    }
                                }
                            });
                        } else {
                            console.error('No data received.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }

            $('#collegeSelect2').change(load_bar_enrollment_report);

            load_bar_enrollment_report();



            fetchDeanCourses();
            load_doughnut_reported_case();

            function fetchDeanCourses(deanId) {
                $.ajax({
                    url: '/getCourse',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        dean: deanId
                    },
                    success: function(response) {
                        let output = ''
                        response.forEach(course => {
                            output += `${course.collegeDesc} <br>`;
                        });

                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching courses:', error);
                    }
                });
            }

            $.ajax({
                url: '/getEnrolledSummary/getEnrolledSummary',
                type: 'GET',
                data: {
                    syid: $('#syid').val(),
                    semid: $('#semid').val(),
                    college: $('#college').val(),
                    dean: $('#dean').val()
                },
                success: function(response) {
                    $('#enrolledsummarytablebody').empty();

                    const colleges = {};

                    response.enrolled.forEach(function(item) {
                        if (!colleges[item.collegeDesc]) {
                            colleges[item.collegeDesc] = {
                                "1ST YEAR COLLEGE": 0,
                                "2ND YEAR COLLEGE": 0,
                                "3RD YEAR COLLEGE": 0,
                                "4TH YEAR COLLEGE": 0,
                                "5TH YEAR COLLEGE": 0
                            };
                        }
                        colleges[item.collegeDesc][item.gradeLevel] = item.enrolled_count;
                    });

                    // Populate the table with grouped data
                    for (const [college, counts] of Object.entries(colleges)) {
                        $('#enrolledsummarytablebody').append(`
                            <tr>
                                <td><i class="fas fa-book-reader"></i> ${college}</td>
                                <td>${counts['1ST YEAR COLLEGE'] || 0}</td>
                                <td>${counts['2ND YEAR COLLEGE'] || 0}</td>
                                <td>${counts['3RD YEAR COLLEGE'] || 0}</td>
                                <td>${counts['4TH YEAR COLLEGE'] || 0}</td>
                                <td>${counts['5TH YEAR COLLEGE'] || 0}</td>
                            </tr>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });




            document.addEventListener("DOMContentLoaded", function() {
                const gradeStatusChart = document.getElementById('gradeStatusChart').getContext('2d');
                const enrollmentChart = document.getElementById('enrollmentChart').getContext('2d');

                // Grade Status (Donut Chart)
                var donutData = {
                    labels: ['Submitted', 'Pending'],
                    datasets: [{
                        data: [8, 2], // Update these numbers based on your data
                        backgroundColor: ['#36A2EB', '#FFCE56']
                    }]
                };

                var donutOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                };
                var gradeStatusChartInstance = new Chart(gradeStatusChart, {
                    type: 'doughnut',
                    data: donutData,
                    options: donutOptions
                });

                // Enrollment Chart (Bar or Doughnut, adjust as needed)
                var enrollmentData = {
                    labels: ["Course 1", "Course 2", "Course 3"],
                    datasets: [{
                        label: "Enrolled Students",
                        data: [50, 70, 90],
                        backgroundColor: "rgba(54, 162, 235, 0.7)",
                        borderColor: "rgba(54, 162, 235, 1)",
                        borderWidth: 1
                    }]
                };

                var enrollmentOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: "Course"
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: "Number of Students"
                            },
                            beginAtZero: true
                        }
                    }
                };
                var enrollmentChartInstance = new Chart(enrollmentChart, {
                    type: 'bar',
                    data: enrollmentData,
                    options: enrollmentOptions
                });
            });


            $(document).ready(function() {
                var syid = '<?php echo DB::table('sy')->where('isactive', 1)->first()->id; ?>';
                var currentportal = @json(Session::get('currentPortal'));

                // Adjust header layout based on screen size
                var header = ($(window).width() < 500) ? {
                    left: 'title',
                    center: '',
                    right: 'today prev,next'
                } : {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                };

                var schedule = [];

                @if ($schoolcalendar ?? ('' && $schoolcalendar ?? ('')->count() > 0))
                    @foreach ($schoolcalendar ?? '' as $item)
                        schedule.push({
                            title: '{{ $item->description }}',
                            start: '{{ $item->datefrom }}',
                            end: '{{ $item->dateto }}',
                            backgroundColor: '{{ $item->noclass == 1 ? '#dc3545' : '#00a65a' }}',
                            borderColor: '{{ $item->noclass == 1 ? '#dc3545' : '#00a65a' }}',
                            allDay: true,
                            id: '{{ $item->id }}'
                        });
                    @endforeach
                @else
                    console.log('No calendar events available');
                @endif

                // Initialize FullCalendar
                var calendarEl = document.getElementById('newcal');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    plugins: ['dayGrid', 'timeGrid', 'interaction'],
                    themeSystem: 'bootstrap',
                    initialView: 'dayGridMonth',
                    headerToolbar: header,
                    height: 'auto',
                    events: '/school-calendar/getall-event/' + currentportal + '/' + syid,
                    editable: false
                });

                // Render the calendar
                calendar.render();

                // Apply Bootstrap styling adjustments for buttons and text
                $('.fc-header-toolbar button').addClass('btn btn-sm btn-primary');
                $('.fc-today-button, .fc-dayGridMonth-button, .fc-timeGridWeek-button, .fc-timeGridDay-button')
                    .css('text-transform', 'capitalize');

                // Adjustments for smaller screens
                if ($(window).width() < 500) {
                    $('.fc-header-toolbar').css({
                        'font-size': '13px',
                        'margin': '0',
                        'padding-top': '0'
                    });
                }

                // Open links for student management
                $('.dropdown-item, .schoolforms').on('click', function() {
                    window.open($(this).attr('href'), "_self");
                });
                $('#stud-manage-enrolled').on('click', function() {
                    window.open('registrar/studentmanagement?sstatus=1', "_self");
                });
                $('#stud-manage-registered').on('click', function() {
                    window.open('/registrar/studentmanagement?sstatus=2', "_self");
                });
                $('#stud-manage-registered-online').on('click', function() {
                    window.open('registrar/studentmanagement?sstatus=3', "_self");
                });
                $('#stud-manage-enrolled-online').on('click', function() {
                    window.open(
                        '/registrar/oe?syid={{ DB::table('sy')->where('isactive', 1)->first()->id }}&semid={{ DB::table('semester')->where('isactive', 1)->first()->id }}',
                        "_blank");
                });
            });
        });
    </script>
@endsection
