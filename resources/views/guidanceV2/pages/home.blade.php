@extends('guidanceV2.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v3.10.2/main.min.css') }}" />
@endsection

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="studentListModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">List of Students</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Average</th>
                            </tr>
                        </thead>
                        <tbody id="studentList">
                            <tr>
                                <td>No Records</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Home</li>
                        <li class="breadcrumb-item active">Dashbaord</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            @if (DB::table('schoolinfo')->first()->admission == 1)
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow-sm">
                            <div class="card-header pb-1">
                                <h5><i class="fas fa-trophy mr-1 text-warning"></i> Top
                                    Course
                                    with High Passers</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row highscore_container">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success shadow">
                            <div class="inner">
                                <h3>53<sup style="font-size: 20px">%</sup></h3>

                                <p>BSIT</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning shadow">
                            <div class="inner">
                                <h3>44</h3>

                                <p>AB-Philo</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-atlas"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger shadow">
                            <div class="inner">
                                <h3>65</h3>

                                <p>BS-HRM</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                </div>
            @endif


            <div class="row">
                <div class="col-lg-7">
                    <div class="card card-widget widget-user-2 shadow">
                        <div class="widget-user-header" style="background-color: antiquewhite">
                            <div class="widget-user-image">
                                <img class="img-circle elevation-2" src="../dist/img/user7-128x128.jpg" alt="User Avatar">
                            </div>
                            <!-- /.widget-user-image -->
                            <h3 class="widget-user-username">Welcome <b>{{ auth()->user()->name }}</b>!</h3>
                            <h5 class="widget-user-desc">Guidance Counselor</h5>
                        </div>
                        <div class="p-2">
                            <label class="ml-1">Appointment</label>
                            <table class="table table-sm table-striped table-borderless">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (DB::table('guidance_counseling_appointment')->where('deleted', 0)->get() as $appointment)
                                        <tr>
                                            <td>{{ $appointment->studname }}</td>
                                            <td>{{ \Carbon\Carbon::parse($appointment->created_at)->format('F d, Y') }}</td>
                                            <td>{{ $appointment->time }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card shadow">
                        <div class="card-header">
                            <h3 class="card-title"><i class="far fa-paper-plane mr-1"></i> Number of Appointment
                                Report</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="bar_appointment_report" style="width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card shadow">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="far fa-calendar-alt mr-1"></i>
                                Calendar
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body pt-0">
                            <!-- The calendar -->
                            <div id="calendar" style="width: 100%; height: 310px; overflow: auto;"></div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <div class="card shadow">
                        <div class="card-header">
                            <h3 class="card-title"><i class="far fa-flag mr-1"></i> Number of Reported
                                Cases</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="doughnut_reported_case" style="width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>

    </section>
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-v3.10.2/main.min.js') }}"></script>
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Initialize FullCalendar -->
    <script>
        var courses = [];
        $(document).ready(function() {
            load_bar_appointment_report()
            load_doughnut_reported_case()
            $('#calendar').fullCalendar();
            loadTopScores()

            $(document).on('click', '.more_info', function() {
                var key = $(this).data('key')
                var list = $('#studentList');
                list.empty();
                if (courses[key]['students'].length == 0) {
                    list.append('<tr><td colspan="2" class="text-center">No students enrolled</td></tr>');
                }
                courses[key]['students'].forEach(function(student) {
                    var item = $('<tr><td>' + student.studname + '</td><td>' +
                        student.totalScore + '%' + '</td></tr>');
                    list.append(item);
                });

                $('#studentListModal').modal('show');
            })
        });

        function loadTopScores() {
            let iconArr = [{
                    icon: '<i class="fas fa-chalkboard-teacher"></i>',
                    color: 'bg-info'
                },
                {
                    icon: '<i class="fas fa-atlas"></i>',
                    color: 'bg-success'
                },
                {

                    icon: '<i class="fas fa-briefcase"></i>',
                    color: 'bg-warning'
                },
                {
                    icon: '<i class="fas fa-laptop"></i>',
                    color: 'bg-danger'
                }

            ];

            $.ajax({
                type: 'GET',
                url: '{{ route('high.passers') }}',
                success: function(data) {
                    console.log(data);
                    courses = data;
                    $('.highscore_container').empty()
                    data.forEach((element, key) => {
                        var renderDiv = `
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="small-box ${iconArr[key]['color']} shadow">
                                <div class="inner">
                                    <h3>${element.average} %</h3>
    
                                    <p>${element.courseabrv}</p>
                                </div>
                                <div class="icon">
                                    ${iconArr[key]['icon']}
                                </div>
                                <a href="javascript:void(0);" class="small-box-footer more_info" data-key="${key}">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        `
                        $('.highscore_container').append(renderDiv)
                    });
                },
            })
        }

        function load_bar_appointment_report() {
            // Dummy data
            var dummyData = {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                    'October', 'November', 'December'
                ],
                datasets: [{
                    label: 'Appointments',
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    data: Array.from({
                        length: 12
                    }, () => Math.floor(Math.random() * 100) + 1), // Generate random data for each month
                }],
            };


            var barChartCanvas = $('#bar_appointment_report').get(0).getContext('2d');
            var existingChart = barChartCanvas.chart;

            // Destroy the existing chart if it exists
            if (existingChart) {
                existingChart.destroy();
            }

            var barChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                datasetFill: false,
            };

            new Chart(barChartCanvas, {
                type: 'bar',
                data: dummyData, // Use the dummy data
                options: barChartOptions,
            });
        }

        function load_doughnut_reported_case() {
            var doughnutChartData = {
                labels: ['Bullying', 'Depression', 'Stressed', 'Addiction', 'Lying'],
                datasets: [{
                    data: [25, 30, 20, 25, 10],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50'],
                    hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50'],
                }],
            };

            // Get the canvas element
            var doughnutChartCanvas = document.getElementById('doughnut_reported_case');

            // Create the doughnut chart
            var doughnutChart = new Chart(doughnutChartCanvas, {
                type: 'doughnut', // Set the chart type to doughnut
                data: doughnutChartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                },
            });
        }
    </script>
@endsection
