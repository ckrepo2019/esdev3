@extends('tesda.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
    <div class="content pt-2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header  bg-secondary">
                            <h3 class="card-title fw-1000">Enrollment Summary</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <div class="enrollment-box">
                                        <i class="fas fa-utensils"></i>
                                        <div>
                                            <b>Cookery</b>
                                            <h6>82</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <div class="enrollment-box">
                                        <i class="fas fa-plug"></i>
                                        <div>
                                            <b>Electrical</b>
                                            <h6>56</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <div class="enrollment-box">
                                        <i class="fas fa-desktop"></i>
                                        <div>
                                            <b>Computer Servicing</b>
                                            <h6>40</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <div class="enrollment-box">
                                        <i class="fas fa-car"></i>
                                        <div>
                                            <b>Automotive</b>
                                            <h6>36</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <div class="enrollment-box">
                                        <i class="fas fa-glass-cheers"></i>
                                        <div>
                                            <b>Bartending</b>
                                            <h6>58</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <div class="enrollment-box">
                                        <i class="fas fa-home"></i>
                                        <div>
                                            <b>Housekeeping</b>
                                            <h6>40</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-7">
                    <div class="card shadow-sm">
                        <div class="card-header  bg-secondary">
                            <h3 class="card-title fw-1000">Enrollment Summary</h3>
                        </div>
                        <div class="card-body p-2" style="max-height: 300px;">
                            <div class="chart">
                                <canvas id="EnrollmentSummary" style="height: 300px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="card shadow-sm">
                        <div class="card-header bg-secondary">
                            <h3 class="card-title fw-1000">Calendar of Actvities</h3>
                        </div>
                        <div class="card-body p-2" style="overflow-y: scroll; max-height: 300px;">
                            <div id="calendar" class="truncated " style="font-size:12px !important;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-7">
                    <div class="card shadow-sm">
                        <div class="card-header bg-secondary">
                            <h3 class="card-title fw-1000">Forms and Printables</h3>
                        </div>
                        <div class="card-body p-2 ">
                            <div class="row">
                                <div class="col-6">
                                    <div class="card shadow-sm">
                                        <div class="card-body p-2">
                                            <a href="#" class="btn btn-secondary btn-block">Enrollment Form</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card shadow-sm">
                                        <div class="card-body p-2">
                                            <a href="#" class="btn btn-secondary btn-block">Certificate of
                                                Completion</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card shadow-sm">
                                        <div class="card-body p-2">
                                            <a href="#" class="btn btn-secondary btn-block">Training Certificate</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card shadow-sm">
                                        <div class="card-body p-2">
                                            <a href="#" class="btn btn-secondary btn-block">Training Certificate</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-v5-11-3/main.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>

    <style>
        .enrollment-box {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            background-color: #fff;
            transition: transform 0.2s, box-shadow 0.2s;
            text-align: center;
            height: 150px;
        }

        .enrollment-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .enrollment-box i {
            font-size: 40px;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .fc-toolbar {
            margin-bottom: 0px;
            padding: 0%;
        }
    </style>

    <script>
        $(document).ready(function() {
                    var calendarEl = $('#calendar');
                    var calendar = new FullCalendar.Calendar(calendarEl[0], {
                        initialView: 'dayGridMonth',
                        selectable: true,
                        headerToolbar: {
                            left: 'prev,next today',
                            right: 'title',
                        },
                        height: 'auto',
                    });
                    calendar.render();


                    var ctx = $('#EnrollmentSummary');
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Cookery', 'Electrical', 'Computer Servicing', 'Automotive', 'Bartending',
                                'Housekeeping'
                            ],
                            datasets: [{
                                label: 'Enrollment Summary',
                                data: [12, 19, 3, 5, 2, 3],
                                backgroundColor: [],
                                borderColor: [],
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: 'Reported Cases by Gender'
                                }
                            }
                        }
                    });
                });
    </script>
@endsection
