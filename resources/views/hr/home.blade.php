<link rel="stylesheet" href="{{ asset('plugins/fullcalendar/main.min.css') }}">
{{-- <link rel="stylesheet" href="{{asset('plugins/fullcalendar-interaction/main.min.css')}}"> --}}
<link rel="stylesheet" href="{{ asset('plugins/fullcalendar-daygrid/main.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fullcalendar-timegrid/main.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fullcalendar-bootstrap/main.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

@extends('hr.layouts.app')
@section('content')
    <style>
        .chartjs-render-monitor {
            /* display: none !important; */
        }

        .donutTeachers {
            margin-top: 90px;
            margin: 0 auto;
            background: transparent url("{{ asset('assets/images/corporate-grooming-20140726161024.jpg') }}") no-repeat 28% 60%;
            background-size: 30%;
        }

        .donutStudents {
            margin-top: 90px;
            margin: 0 auto;
            /* background: transparent url("{{ asset('assets/images/student-cartoon-png-2.png') }}") no-repeat  28% 60%; */
            background-size: 30%;
        }

        .fc-header-toolbar {
            padding: 1px;
        }

        .card {
            box-shadow: unset !important;
            box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%) !important;
        }

        .form-control {
            padding: 0px !important;
            height: unset !important;
        }

        .btn-group-vertical>.btn.active,
        .btn-group-vertical>.btn:active,
        .btn-group-vertical>.btn:focus,
        .btn-group>.btn.active,
        .btn-group>.btn:active,
        .btn-group>.btn:focus {
            z-index: unset;
        }

        .fc-view,
        .fc-view>table {
            z-index: unset;
        }

        .chart-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 400px;
        }

        /* Adjust canvas size */
        #attendanceChart {
            max-width: 300px;
        }
    </style>
    @php
        use Carbon\Carbon;
        use App\Models\HR\HREmployeeAttendance;
        $now = Carbon::now();
        $comparedDate = $now->toDateString();

        $employees = DB::table('teacher')
            ->where('deleted', '0')
            ->where('isactive', '1')
            ->orderBy('lastname', 'asc')
            ->get();

        if (count($employees) > 0) {
            foreach ($employees as $employee) {
                $employee->lastactivity = HREmployeeAttendance::getattendance(date('Y-m-d'), $employee)->lastactivity;
            }
        }

        $departments = Db::table('hr_departments')
            ->where('hr_departments.deleted', '0')
            ->orderBy('department', 'asc')
            ->get();

        $designations = Db::table('usertype')
            ->select('usertype.id', 'usertype.utype as designation')
            ->where('usertype.deleted', '0')
            ->where('usertype.utype', '!=', 'PARENT')
            ->where('usertype.utype', '!=', 'STUDENT')
            ->where('usertype.utype', '!=', 'SUPER ADMIN')
            ->get();
    @endphp
    <style>
        h1 {
            opacity: 0.5;
        }

        .noselect {
            -webkit-touch-callout: none;
            /* iOS Safari */
            -webkit-user-select: none;
            /* Safari */
            -khtml-user-select: none;
            /* Konqueror HTML */
            -moz-user-select: none;
            /* Old versions of Firefox */
            -ms-user-select: none;
            /* Internet Explorer/Edge */
            user-select: none;
            /* Non-prefixed version, currently supported by Chrome, Opera and Firefox */
        }

        .card {
            border: none;
        }
    </style>
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-lg"
                style="color: #004085;
            background-color: #cce5ff;
            border-color: #b8daff;">
                <span class="info-box-icon"><i class="fa fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Employees</span>
                    <span class="info-box-number">{{ count($employees) }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-lg"
                style="color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;">
                <span class="info-box-icon"><i class="far fa-bookmark"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Attendance</span>
                    {{-- <span
                        class="info-box-number">{{ collect($employees)->where('lastactivity', '!=', '')->where('lastactivity', '!=', null)->count() }}/{{ count($employees) }}
                        Present</span> --}}
                    <span class="info-box-number" id="attendancecount"></span>

                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-lg"
                style="color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;">
                <span class="info-box-icon"><i class="far fa-bookmark"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Departments</span>
                    <span class="info-box-number">{{ count($departments) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-lg"
                style="color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;">
                <span class="info-box-icon"><i class="far fa-bookmark"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Designations</span>
                    <span class="info-box-number">{{ count($designations) }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card" style="border-radius: 10px 10px 0px 0px">
                    <div class="card-header bg-secondary">
                        <div class="row">
                            <div class="col-md-2 m-auto">
                                <div class="image">
                                    @php
                                        $hr_profile = Db::table('teacher')
                                            ->select(
                                                'teacher.id',
                                                'teacher.lastname',
                                                'teacher.middlename',
                                                'teacher.firstname',
                                                'teacher.suffix',
                                                'teacher.licno',
                                                'teacher.tid',
                                                'teacher.deleted',
                                                'teacher.isactive',
                                                'teacher.picurl',
                                                'usertype.utype',
                                                'usertype.refid',
                                            )
                                            ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                                            ->where('teacher.userid', auth()->user()->id)
                                            ->first();

                                        $hr_info = Db::table('employee_personalinfo')
                                            ->select(
                                                'employee_personalinfo.id as employee_personalinfoid',
                                                'employee_personalinfo.nationalityid',
                                                'employee_personalinfo.religionid',
                                                'employee_personalinfo.dob',
                                                'employee_personalinfo.gender',
                                                'employee_personalinfo.address',
                                                'employee_personalinfo.contactnum',
                                                'employee_personalinfo.email',
                                                'employee_personalinfo.maritalstatusid',
                                                'employee_personalinfo.spouseemployment',
                                                'employee_personalinfo.numberofchildren',
                                                'employee_personalinfo.emercontactname',
                                                'employee_personalinfo.emercontactrelation',
                                                'employee_personalinfo.emercontactnum',
                                                'employee_personalinfo.departmentid',
                                                'employee_personalinfo.designationid',
                                                'employee_personalinfo.date_joined',
                                            )
                                            ->where('employee_personalinfo.employeeid', $hr_profile->id)
                                            ->get();

                                        $number = rand(1, 3);
                                        if (count($hr_info) == 0) {
                                            $avatar = 'assets/images/avatars/unknown.png';
                                        } else {
                                            if (strtoupper($hr_info[0]->gender) == 'FEMALE') {
                                                $avatar = 'avatar/T(F) ' . $number . '.png';
                                            } else {
                                                $avatar = 'avatar/T(M) ' . $number . '.png';
                                            }
                                        }

                                        $refid = DB::table('usertype')
                                            ->where('id', Session::get('currentPortal'))
                                            ->first()->refid;

                                        $countapproval = DB::table('hr_leavesappr')
                                            ->where('appuserid', auth()->user()->id)
                                            ->where('deleted', '0')
                                            ->count();

                                        $countundertimeapproval = DB::table('undertime_approval')
                                            ->where('appruserid', auth()->user()->id)
                                            ->where('deleted', '0')
                                            ->count();
                                    @endphp
                                    <img src="{{ asset($hr_profile->picurl) }}"
                                        onerror="this.onerror = null, this.src='{{ asset($avatar) }}'"
                                        class="img-circle elevation-2" alt="User Image" width="80px" height="80px">
                                </div>
                            </div>
                            <div class="col-md-6 m-auto" style="line-height: 10px;">
                                <span>
                                    <h2>Welcome, {{ strtoupper($hr_profile->firstname) }} !</h2>
                                </span>
                                <span>
                                    <p>Employee ID: {{ strtoupper($hr_profile->tid) }}</p>
                                </span>
                                <span>
                                    <p>Designation: {{ strtoupper($hr_profile->utype) }}</p>
                                </span>
                            </div>
                            <div class="col-md-4 bg-white" style="border-radius: 10px 10px 0px 0px; display: grid;">
                                <span style="font-size: 18px">Your Tap In</span>
                                <input type="text" id="uid" value="{{ $hr_profile->id }}" hidden><span
                                    class="text-primary" id="user_timein"></span>
                                <span class="text-success">
                                    {{ date('F j, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h4>You have!</h4>
                                <span><i class="far fa-calendar"></i> <span id="leavecount"></span> Pending Leave
                                    Request</span><br>
                            </div>
                            <div class="col-md-3 text-center" style="border-left: 1px solid #b5b5b5;">
                                <h4>Attendance</h4>
                                <canvas id="attendanceChart" width="400" height="400"></canvas>

                                <div class="text-left pl-4 mt-3">
                                    <i class="fas fa-square" style="color:#80c4fc"></i> PRESENT <br>
                                    <i class="fas fa-square" style="color:#c76d06"></i> ABSENT<br>
                                </div>
                            </div>
                            <div class="col-md-5 text-center">
                                <h4>Leave Application</h4>
                                <canvas id="leaveApplicationChart"
                                    style="width: 100px !important; height: 60px !important"></canvas>

                                <div class="leave_container text-left pl-4 mt-3">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- EMPLOYEES --}}
                <div class="card" style="border-radius: 10px 10px 0px 0px">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title text-bold">Employee Tapping Attendance</h3>
                    </div>
                    <div class="card-body">
                        <table width="100%" class="table table-bordered table-sm"
                            style="font-size: 16px; table-layout: fixed;" id="list_employee_table">
                            <thead>
                                <tr>
                                    <th width="60%" class="">Name</th>
                                    <th width="20%" class="text-center">Status</th>
                                    <th width="20%" class="text-center">Time IN</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @if (count($employees) > 0)
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td class="eachemployee" data-string="{{$employee->lastname}}, {{$employee->firstname}} {{$employee->suffix}}" style="text-transform: uppercase">
                                            <small><strong>{{strtoupper($employee->lastname)}}</strong>,</small> <small>{{ucwords(strtolower($employee->firstname))}}</small>
                                        </td>
                                        <td class="text-center">
                                            <small>@if ($employee->lastactivity == '' || $employee->lastactivity == null)<span class="badge badge-warning">ABSENT</span>@else<span class="float-right badge badge-success">PRESENT</span>@endif</small>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <small><span class="badge badge-info float-right">{{$employee->lastactivity}}</span></small>
                                        </td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            @endif --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-primary tschoolcalendar"
                    style="box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title text-bold">Calendar of Activities</h3>
                    </div>
                    <div class="card-body p-1">
                        <div class="calendarHolder">
                            <div id='newcal'></div>
                        </div>
                    </div>
                </div>

                {{-- ON LEAVE --}}
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title text-bold">ON - Leave Employee</h3>
                    </div>
                    <div class="card-body">
                        <table width="100%" class="table table-bordered table-sm"
                            style="font-size: 16px; table-layout: fixed;" id="leave_employee_table">
                            <thead>
                                <tr>
                                    <th width="40%" class="">Name</th>
                                    <th width="20%" class="text-center">Leave Type</th>
                                    <th width="20%" class="text-center">Leave From</th>
                                    <th width="20%" class="text-center">Leave To</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">

            </div>
            <div class="col-md-7">


            </div>
        </div>
    @endsection
    @section('footerjavascript')
        <script src="{{ asset('plugins/fullcalendar/main.min.js') }}"></script>
        <script src="{{ asset('plugins/fullcalendar-daygrid/main.min.js') }}"></script>
        <script src="{{ asset('plugins/fullcalendar-timegrid/main.min.js') }}"></script>
        <script src="{{ asset('plugins/fullcalendar-interaction/main.min.js') }}"></script>
        <script src="{{ asset('plugins/fullcalendar-bootstrap/main.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
        <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
        {{-- <script>
            document.addEventListener("DOMContentLoaded", function() {
                
            });
        </script> --}}
        <script>
            $(document).ready(function() {
                var employee_details = []
                var todayleaveemployee = []
                var leaves = []

                gettap()
                getleaves()
                $("#filter-attendance").on("keyup", function() {
                    var input = $(this).val().toUpperCase();
                    var visibleCards = 0;
                    var hiddenCards = 0;

                    $(".container").append($("<div class='card-group card-group-filter'></div>"));


                    $(".eachemployee").each(function() {
                        if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                            $(".card-group.card-group-filter:first-of-type").append($(this));
                            $(this).hide();
                            hiddenCards++;

                        } else {

                            $(".card-group.card-group-filter:last-of-type").prepend($(this));
                            $(this).show();
                            visibleCards++;

                            if (((visibleCards % 4) == 0)) {
                                $(".container").append($(
                                    "<div class='card-group card-group-filter'></div>"));
                            }
                        }
                    });

                });

                if ($(window).width() < 500) {

                    $('.fc-left').css('font-size', '12px')
                    $('.fc-toolbar').css('margin', '0')
                    $('.fc-toolbar').css('padding-top', '0')

                    var header = {
                        left: 'title',
                        center: '',
                        right: 'today prev,next'
                    }
                } else {
                    var header = {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    }
                }

                var date = new Date()
                var d = date.getDate(),
                    m = date.getMonth(),
                    y = date.getFullYear()

                var schedule = [];

                @foreach ($schoolcalendar as $item)

                    @if ($item->noclass == 1)
                        var backgroundcolor = '#dc3545';
                    @else
                        var backgroundcolor = '#00a65a';
                    @endif

                    schedule.push({
                        title: '{{ $item->description }}',
                        start: '{{ $item->datefrom }}',
                        end: '{{ $item->dateto }}',
                        backgroundColor: backgroundcolor,
                        borderColor: backgroundcolor,
                        allDay: true,
                        id: '{{ $item->id }}'
                    })
                @endforeach


                var Calendar = FullCalendar.Calendar;

                var calendarEl = document.getElementById('newcal');

                var calendar = new Calendar(calendarEl, {
                    plugins: ['bootstrap', 'interaction', 'dayGrid', 'timeGrid'],
                    header: header,
                    events: schedule,
                    height: 'auto',
                    themeSystem: 'bootstrap',
                    eventStartEditable: false
                });

                calendar.render();
                $('.fc-header-toolbar').find('button').addClass('btn-sm')
                $('.dropdown-item').on('click', function() {
                    window.open($(this).attr('href'), "_self")
                })
                // $('.schoolforms').on('click', function(){
                //     window.open($(this).attr('href'),"_self")
                // })
                // $('#stud-manage-enrolled').on('click', function(){
                //     window.open('/registrar/enrolled',"_self")
                // })
                // $('#stud-manage-registered').on('click', function(){
                //     window.open('/registrar/registered',"_self")
                // })
                // $('#stud-manage-enrolled-online').on('click', function(){
                //     window.open('/registrar/oe?syid={{ DB::table('sy')->where('isactive', '1')->first()->id }}&semid={{ DB::table('semester')->where('isactive', '1')->first()->id }}',"_self")
                // })

                let leaveColors = []; // Global array to store colors for each leave type

                function getleaves() {
                    $.ajax({
                        type: "GET",
                        url: "/hr/employees/profile/getleaves",
                        success: function(data) {
                            leaves = data;
                            console.log(data, 'leaves');
                            var leavecount = data.applied_leaves.filter(x => x.leavestatus == 0 && x.disapprovercount == 0 && x.approvercount == 0).length;
                            $(".leave_container").empty();

                            // Predefined color array
                            const staticColors = [
                                'rgba(128, 196, 252, 1)', // light blue
                                'rgba(196, 108, 4, 1)', // orange
                                'rgba(243, 156, 18, 1)', // yellow
                                'rgba(52, 152, 219, 1)', // blue
                                'rgba(231, 76, 60, 1)', // red
                                'rgba(46, 204, 113, 1)', // green
                                'rgba(155, 89, 182, 1)', // purple
                                'rgba(241, 196, 15, 1)', // gold
                                'rgba(26, 188, 156, 1)', // teal
                                'rgba(127, 140, 141, 1)' // gray
                                // Add more colors as needed
                            ];

                            leaveColors = []; // Reset colors array

                            // Assign static colors to leave types
                            leaves.leave.forEach(function(leave, index) {
                                // Cycle colors if there are more leaves than colors in the array
                                var color = staticColors[index % staticColors.length];

                                leaveColors.push(color); // Store color for chart
                                $(".leave_container").append($(
                                    "<i class='fas fa-square' style='color:" +
                                    color + "'></i> " +
                                    "<span class='text-left'>" + leave.type +
                                    "&nbsp;&nbsp;&nbsp;</span>"
                                ));
                            });

                            $('#leavecount').html(leavecount);

                            // Call the chart function with updated data and colors
                            leaveApplicationChart(leaves, leaveColors);
                        }
                    });
                }



                function attendanceChart() {
                    console.log(employee_details, 'attendanceChart');
                    var countPresent = employee_details.filter(x => x.attendance.taphistorystatus == 1).length;
                    var countAbsent = employee_details.filter(x => x.attendance.taphistorystatus == 2).length;

                    const ctx = document.getElementById('attendanceChart').getContext('2d');
                    const attendanceChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            // labels: ['Present', 'Absent', 'Late'],
                            datasets: [{
                                data: [countPresent,
                                    countAbsent
                                ], // Number of occurrences for each category
                                backgroundColor: [
                                    'rgba(128, 196, 252, 255)',
                                    'rgba(196,108,4)'
                                    // 'rgba(205, 185, 185, 255)'
                                ],
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top', // Position legend below the chart
                                    labels: {
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            label += context.raw;
                                            return label;
                                        }
                                    }
                                }
                            },
                            cutout: '70%'
                        }
                    });
                }

                function leaveApplicationChart(leaves, leaveColors) {
                    const ctx = document.getElementById('leaveApplicationChart').getContext('2d');
                    const attendanceChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: leaves.count_per_leave.map(item => item.count),
                                backgroundColor: leaveColors
                            }],
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            label += context.raw;
                                            return label;
                                        }
                                    }
                                }
                            },
                            cutout: '70%'
                        }
                    });
                }


                function gettap() {
                    $.ajax({
                        type: "GET",
                        url: "/attendance/getemployeeattendance",
                        success: function(data) {
                            var uid = $('#uid').val();
                            var timein = ''

                            employee_details = data.filter(x => x.leavestoday.approvercount == null)
                            todayleaveemployee = data.filter(x => x.leavestoday.approvercount > 0)
                            userdetails = data.filter(x => x.employeeinfo.id == uid)[0]

                            timein = userdetails.attendance.in_am ? moment(userdetails.attendance.in_am,
                                    "HH:mm:ss").format("h:mm A") : userdetails.attendance.in_pm ? moment(
                                    userdetails.attendance.in_pm, "HH:mm:ss").format("h:mm A") :
                                '<span class="text-danger">ABSENT</span>';
                            $('#user_timein').html(timein);

                            var attendancecount = data.filter(x => x.attendance.taphistorystatus === 1).length;
                            var overallcount = data.length;
                            
                            $('#attendancecount').text(`${attendancecount}/${overallcount}`);
                            employee_datatable()
                            leave_employee_datatable()
                            attendanceChart()
                        }
                    });
                }


                function employee_datatable() {

                    console.log(employee_details, 'list_employee_table');
                    $('#list_employee_table').DataTable({
                        lengthMenu: true,
                        info: true,
                        paging: true,
                        searching: true,
                        destroy: true,
                        lengthChange: false,
                        autoWidth: true,
                        order: false,
                        paging: true,
                        data: employee_details,
                        columns: [{
                                "data": null
                            },
                            {
                                "data": null
                            },
                            {
                                "data": null
                            }
                        ],
                        columnDefs: [{
                                'targets': 0,
                                'orderable': false,
                                createdCell: function(td, cellData, rowData, row, col) {
                                    var content = '<span><b>' + rowData.employeeinfo.lastname +
                                        '</b></span>, <span>' + rowData.employeeinfo.firstname +
                                        '</span>';
                                    $(td).html(content);
                                    $(td).addClass('text-left align-middle');
                                }
                            },
                            {
                                'targets': 1,
                                'orderable': false,
                                createdCell: function(td, cellData, rowData, row, col) {
                                    var status = ''

                                    if (rowData.attendance.taphistorystatus == 1) {
                                        status = '<span class="badge badge-success">Present</span>'
                                    } else {
                                        status = '<span class="badge badge-danger">Absent</span>'
                                    }

                                    var content = status;
                                    $(td).html(content);
                                    $(td).addClass('text-center align-middle');
                                    $(td).css('vertical-align', 'middle!important');
                                }
                            },
                            {
                                'targets': 2,
                                'orderable': false,
                                createdCell: function(td, cellData, rowData, row, col) {

                                    var timein = ''

                                    if (rowData.attendance.in_am != null) {
                                        var time = rowData.attendance.in_am.split(':')
                                        timein = (time[0] % 12 || 12) + ':' + time[1] + (time[0] >= 12 ?
                                            ' PM' : ' AM')
                                    } else if (rowData.attendance.in_pm != null) {
                                        var time = rowData.attendance.in_pm.split(':')
                                        timein = (time[0] % 12 || 12) + ':' + time[1] + (time[0] >= 12 ?
                                            ' PM' : ' AM')
                                    } else {
                                        timein = ''
                                    }

                                    var content = timein;
                                    $(td).html(content);
                                    $(td).addClass('text-center align-middle');
                                    $(td).css('padding', '0 !important');
                                }
                            }
                        ]
                    });
                }

                function leave_employee_datatable() {
                    $('#leave_employee_table').DataTable({
                        lengthMenu: true,
                        info: true,
                        paging: true,
                        searching: true,
                        destroy: true,
                        lengthChange: false,
                        autoWidth: true,
                        order: false,
                        paging: true,
                        data: todayleaveemployee,
                        columns: [{
                                "data": null
                            },
                            {
                                "data": null
                            },
                            {
                                "data": null
                            },
                            {
                                "data": null
                            }
                        ],
                        columnDefs: [{
                                'targets': 0,
                                'orderable': false,
                                createdCell: function(td, cellData, rowData, row, col) {
                                    var content = '<span><b>' + rowData.employeeinfo.lastname +
                                        '</b></span>, <span>' + rowData.employeeinfo.firstname +
                                        '</span>';
                                    $(td).html(content);
                                    $(td).addClass('text-left align-middle');
                                }
                            },
                            {
                                'targets': 1,
                                'orderable': false,
                                createdCell: function(td, cellData, rowData, row, col) {
                                    var content = '<span style="text-transform:capitalize">' + rowData
                                        .leavestoday.leave_type + '</span>';
                                    $(td).html(content);
                                    $(td).addClass('text-center align-middle');
                                    $(td).css('vertical-align', 'middle!important');
                                }
                            },
                            {
                                'targets': 2,
                                'orderable': false,
                                createdCell: function(td, cellData, rowData, row, col) {
                                    var content = '<span style="text-transform:capitalize">' + rowData
                                        .leavestoday.datefrom + '</span>';
                                    $(td).html(content);
                                    $(td).addClass('text-center align-middle');
                                    $(td).css('padding', '0 !important');
                                }
                            },
                            {
                                'targets': 3,
                                'orderable': false,
                                createdCell: function(td, cellData, rowData, row, col) {
                                    var content = '<span style="text-transform:capitalize">' + rowData
                                        .leavestoday.dateto + '</span>';
                                    $(td).html(content);
                                    $(td).addClass('text-center align-middle');
                                    $(td).css('padding', '0 !important');
                                }
                            }
                        ]
                    });
                }

            });
        </script>
    @endsection
