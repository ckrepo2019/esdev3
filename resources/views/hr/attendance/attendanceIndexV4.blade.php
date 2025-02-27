@extends('hr.layouts.app')
@section('content')
    <style>
        /* ///////////////////////////////////////// */
        body {
            font-family: Arial, sans-serif;
        }

        .filter,
        .attendance {
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .table-container {
            overflow-x: auto;
            overflow-y: auto;
            max-height: 500px;
            width: 100%;
        }

        table {
            width: max-content;
            min-width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            white-space: nowrap;
        }

        th {
            background-color: #f4f4f4;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .attendance_status {
            background-color: #c8f7c5;
        }

        .late {
            background-color: #fce79d;
        }

        .absent {
            background-color: #f7c5c5;
        }

        .search-box {
            margin-bottom: 10px;
            width: 30%;
            padding: 5px;
        }

        .table-container {
            overflow: auto;
            max-height: 500px;
        }

        .status_present {
            background-color: #c8f7c5;
        }

        .status_tardy {
            background-color: #fce79d;
        }

        .status_absent {
            background-color: #f7c5c5;
        }

        .status_overtime {
            background-color: #F5DEB3;
        }
    </style>
    @php
        $nationality = DB::table('nationality')->where('deleted', 0)->get();
        $religion = DB::table('religion')->where('deleted', 0)->get();
        $studentStatus = DB::table('studentstatus')->get();
        $batch = DB::table('tesda_batches')->where('deleted', 0)->get();
        $specialization = DB::table('tesda_courses')->where('deleted', 0)->get();

    @endphp
    <section class="content-header">
        <div class="container-fluid">
            <h1><i class="fas fa-cog"></i>Attendance</h1>
            <ol class="breadcrumb float-sm-left">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">{{ isset($page) ? $page : 'Attendance' }}</li>
            </ol>
        </div>
    </section><br>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="info-box shadow-sm">
                        <div class="info-box-content" style="font-size:15px !important">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5><i class="fa fa-filter"></i> Filter</h5>
                                </div>
                            </div>
                            <div class="row">

                                {{-- <div class="col-md-4  form-group  mb-0">
                                    <label for="" class="mb-1">Date Range</label>
                                    <div class="input-group">
                                        <input type="date" id="startDate"
                                            class="form-control form-control-sm float-right" style="width: 45%;">
                                        <span class="" style="width: 10%;text-align: center;">-</span>
                                        <input type="date" id="endDate"
                                            class="form-control form-control-sm float-right" style="width: 45%;">
                                    </div>
                                </div> --}}
                                <div class="col-md-4  form-group  mb-0">
                                    <label for="" class="mb-1">Date Range</label>
                                    {{-- <div class="input-group">
                                        <input type="date" id="startDate"
                                            class="form-control form-control-sm float-right" style="width: 45%;">
                                        <span class="" style="width: 10%;text-align: center;">-</span>
                                        <input type="date" id="endDate"
                                            class="form-control form-control-sm float-right" style="width: 45%;">
                                    </div> --}}
                                    <input type="text" class="form-control form-control-sm float-right input-range"
                                        id="reservationnew" readonly>
                                    {{-- <div class="input-group-append">
                                        <button type="button" class="btn btn-sm btn-success btn-payroll-dates-submit"
                                            id="btn-payroll-dates-submit" data-action="new">
                                            <i class="fa fa-share"></i> Activate Range
                                        </button>
                                    </div><br> --}}
                                </div>
                                <div class="col-md-3 form-group mb-0">
                                    <label for="" class="mb-1">Attendance Status</label>
                                    <select name="" id="select-status" class="form-control select2 form-control-sm"
                                        style="width: 100%;">
                                        <option value="">All</option>
                                        <option value="Present">Present</option>
                                        <option value="Late">Late</option>
                                        <option value="Absent">Absent</option>
                                        <option value="Overtime">Overtime</option>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-0">
                                    <label for="" class="mb-1">Designation</label>
                                    <select name="" id="select-designation"
                                        class="form-control select2 form-control-sm" style="width: 100%;">
                                        <option value="">All</option>

                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-gray">
                    <h3 class="card-title">Attendance</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        {{-- <div class="col-md-12 d-flex justify-content-between">
                            <input type="text" id="manual_filter_date_range" class="form-control form-control-sm"
                                style="width: 25%;" />
                        </div> --}}
                        {{-- <div class="col-md-4  form-group  mb-0">
                            <label for="" class="mb-1">Manual Payroll Attendance</label>
                            <div class="d-flex">
                                <input type="date" id="startDate" class="form-control form-control-sm mr-2"
                                    style="width: 45%;">
                                <span class="align-self-center" style="width: 10%; text-align: center;">-</span>
                                <input type="date" id="endDate" class="form-control form-control-sm ml-2"
                                    style="width: 45%;">
                                <button class="btn btn-primary btn-sm ml-2"><i class="fa fa-plus"></i></button>
                            </div>

                        </div> --}}
                    </div>
                    <br>
                    {{-- <div class="attendance"> --}}
                    <input type="text" id="search" class="form-control float-right" placeholder="Search Employee..."
                        style="width: 25%;">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead id="dynamicHeader">

                            </thead>
                            <tbody id="attendanceBody">
                                {{-- <tr>
                                    <td>Harayo, Cristine R.</td>
                                </tr>
                                <tr>
                                    <td>Generalao, Dianne R.</td>
                                </tr> --}}
                            </tbody>
                        </table>
                    </div>
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="manual_attendance_modal" tabindex="-1" aria-labelledby="manual_attendance_label"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manual_attendance_label"><strong>Manual Attendance</strong></h5>
                    <button type="button" id="employee_attendance_closeModalBtn" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">

                    <button class="btn btn-success"><i class="fa fa-plus"></i> New Advance Attendance</button>

                    <input type="text" id="selected_employee_id" class="form-control form-control-sm" hidden>
                    <div class="table-wrapper">
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full  text-center">
                            <thead>
                                <tr>
                                    <th rowspan="2">Date/s</th>
                                    <th colspan="2">MORNING</th>
                                    <th colspan="2">AFTERNOON</th>
                                    <th rowspan="2">Remarks</th>
                                </tr>
                                <tr>
                                    <th>AM IN</th>
                                    <th>AM OUT</th>
                                    <th>PM IN</th>
                                    <th>PM OUT</th>

                                </tr>
                            </thead>
                            <tbody class="manual_attendance_dynamic_rows" id="manual_attendance_dynamic_rows">

                            </tbody>
                        </table>
                        <br>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <p class="add_row_manual_attendance ml-5" style="cursor: pointer;color:blue;"
                                id="add_row_manual_attendance">Add + Row</p>
                            <button class="btn btn-success mr-5" id="save_manual_attendance">Save <i
                                    class="fas fa-save"></i> </button>
                        </div>
                    </div>



                </div>



            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script>
        $(document).ready(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            });

            //work work
            // function generateTableHeaders(startDate, endDate) {
            //     let start = new Date(startDate);
            //     let end = new Date(endDate);
            //     let headerRow = $("#dynamicHeader");
            //     headerRow.html('<th>Employee Name</th>');

            //     let dateHeaders = [];

            //     while (start <= end) {
            //         let formattedDate = start.toISOString().split('T')[0];
            //         let displayDate = start.toLocaleDateString('en-US', {
            //             month: 'short',
            //             day: 'numeric'
            //         });
            //         headerRow.append(`<th data-date="${formattedDate}">${displayDate}</th>`);
            //         dateHeaders.push(formattedDate);
            //         start.setDate(start.getDate() + 1);
            //     }

            //     headerRow.append('<th style="position: sticky; right: 0;">Action</th>');

            //     getemployees_multiple_date(dateHeaders.length);
            // }

            //work work
            // function generateTableHeadersSingleday(startDate, endDate) {
            //     let headerRow = $("#dynamicHeader");
            //     headerRow.html(
            //         '<th>Employee Name</th><th>Status</th><th colspan="2">1st Shift</th><th colspan="2">2nd Shift</th><th colspan="2">Total</th><th>Remarks</th><th>Action</th>'
            //     );

            //     getemployees_single_date()

            // }

            // function getemployees_single_date() {
            //     $.ajax({
            //         type: "GET",
            //         url: '/hr/employees/attendance/V4fetch',
            //         data: {
            //             action: 'getactiveemployees'
            //         },
            //         success: function(response) {
            //             var employees = response.data;

            //             $("#attendanceBody").empty();
            //             let attendanceBody = $("#attendanceBody");
            //             employees.forEach(emp => {
            //                 attendanceBody.append(
            //                     `<tr>
        //                         <td id="employee_name_${emp.id}">${emp.lastname}, ${emp.firstname}</td>
        //                         <td id="status_${emp.id}" class="status"></td>
        //                         <td id="morning_in_${emp.id}" class="morning_in"></td>
        //                         <td id="morning_out_${emp.id}" class="morning_out"></td>
        //                         <td id="afternoon_in_${emp.id}" class="afternoon_in"></td>
        //                         <td id="afternoon_out_${emp.id}" class="afternoon_out"></td>
        //                         <td id="total_hours_${emp.id}" class="total_hours"></td>
        //                         <td id="total_minutes_${emp.id}" class="total_minutes"></td>
        //                         <td id="remarks_${emp.id}" class="remarks"></td>
        //                         <td data-id="${emp.id}" class="manual_attendance" style="text-decoration: underline; font-style: italic; color: blue;cursor:pointer;" onclick="$('#manual_attendance_modal').modal('show');">Manual Attendance</td>
        //                     </tr>`

            //                 );
            //             });
            //         }
            //     });
            // }

            //working for single date
            // function getemployees_single_date() {

            //     var dates = $('#reservationnew').val().split(' - ');
            //     var datefrom = moment(dates[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
            //     var dateto = moment(dates[1], 'MM/DD/YYYY').format('YYYY-MM-DD');

            //     var isSingleDay = (datefrom === dateto);
            //     var singleDay = isSingleDay ? datefrom : null;
            //     // console.log('isSingleDay', singleDay);

            //     $.ajax({
            //         type: "GET",
            //         url: '/hr/employees/attendance/V4fetch',
            //         data: {
            //             action: 'getactiveemployees',
            //             single_date: singleDay
            //         },
            //         success: function(response) {
            //             var employees = response.data;

            //             $("#attendanceBody").empty();
            //             let attendanceBody = $("#attendanceBody");
            //             employees.forEach(emp => {
            //                 attendanceBody.append(
            //                     `<tr>
        //                         <td id="employee_name_${emp.id}">${emp.lastname}, ${emp.firstname}</td>
        //                         <td id="status_${emp.id}" class="status"></td>
        //                         <td id="morning_in_${emp.id}" class="morning_in"></td>
        //                         <td id="morning_out_${emp.id}" class="morning_out"></td>
        //                         <td id="afternoon_in_${emp.id}" class="afternoon_in"></td>
        //                         <td id="afternoon_out_${emp.id}" class="afternoon_out"></td>
        //                         <td id="total_hours_${emp.id}" class="total_hours"></td>
        //                         <td id="total_minutes_${emp.id}" class="total_minutes"></td>
        //                         <td id="remarks_${emp.id}" class="remarks"></td>
        //                         <td data-id="${emp.id}" class="manual_attendance" style="text-decoration: underline; font-style: italic; color: blue;cursor:pointer;" onclick="$('#manual_attendance_modal').modal('show');">Manual Attendance</td>
        //                     </tr>`

            //                 );
            //             });
            //         }
            //     });
            // }

            // working code v2 for single date
            // function getemployees_single_date() {

            //     var dates = $('#reservationnew').val().split(' - ');
            //     var datefrom = moment(dates[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
            //     var dateto = moment(dates[1], 'MM/DD/YYYY').format('YYYY-MM-DD');

            //     var isSingleDay = (datefrom === dateto);
            //     var singleDay = isSingleDay ? datefrom : null;
            //     // console.log('isSingleDay', singleDay);

            //     $.ajax({
            //         type: "GET",
            //         url: '/hr/employees/attendance/V4fetch',
            //         data: {
            //             action: 'getactiveemployees',
            //             single_date: singleDay
            //         },
            //         success: function(response) {
            //             var employees = response.data;
            //             var employee_manual_attendance = response.employee_manual_attendance;

            //             $("#attendanceBody").empty();
            //             let attendanceBody = $("#attendanceBody");
            //             employees.forEach(emp => {
            //                 var morning_in = '',
            //                     morning_out = '',
            //                     afternoon_in = '',
            //                     afternoon_out = '',
            //                     total_hours = '',
            //                     total_minutes = '',
            //                     remarks = '';
            //                 if (employee_manual_attendance[emp.id]) {
            //                     employee_manual_attendance[emp.id].forEach(entry => {
            //                         switch (entry.tapstate) {
            //                             case "IN":
            //                                 if (entry.timeshift === "AM") {
            //                                     morning_in = moment(entry.ttime,
            //                                         'HH:mm:ss').format('hh:mm A');
            //                                 } else {
            //                                     afternoon_in = moment(entry.ttime,
            //                                         'HH:mm:ss').format('hh:mm A');
            //                                 }
            //                                 break;
            //                             case "OUT":
            //                                 if (entry.timeshift === "AM") {
            //                                     morning_out = moment(entry.ttime,
            //                                         'HH:mm:ss').format('hh:mm A');
            //                                 } else {
            //                                     afternoon_out = moment(entry.ttime,
            //                                         'HH:mm:ss').format('hh:mm A');
            //                                 }
            //                                 break;
            //                         }
            //                     });
            //                 }

            //                 attendanceBody.append(
            //                     `<tr>
        //                         <td id="employee_name_${emp.id}">${emp.lastname}, ${emp.firstname}</td>
        //                         <td id="status_${emp.id}" class="status"></td>
        //                         <td id="morning_in_${emp.id}" class="morning_in">${morning_in}</td>
        //                         <td id="morning_out_${emp.id}" class="morning_out">${morning_out}</td>
        //                         <td id="afternoon_in_${emp.id}" class="afternoon_in">${afternoon_in}</td>
        //                         <td id="afternoon_out_${emp.id}" class="afternoon_out">${afternoon_out}</td>
        //                         <td id="total_hours_${emp.id}" class="total_hours">${total_hours}</td>
        //                         <td id="total_minutes_${emp.id}" class="total_minutes">${total_minutes}</td>
        //                         <td id="remarks_${emp.id}" class="remarks">${remarks}</td>
        //                         <td data-id="${emp.id}" class="manual_attendance" style="text-decoration: underline; font-style: italic; color: blue;cursor:pointer;" onclick="$('#manual_attendance_modal').modal('show');">Manual Attendance</td>
        //                     </tr>`

            //                 );
            //             });
            //         }
            //     });
            // }

            // working code v3 for single date
            // function getemployees_single_date() {

            //     var dates = $('#reservationnew').val().split(' - ');
            //     var datefrom = moment(dates[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
            //     var dateto = moment(dates[1], 'MM/DD/YYYY').format('YYYY-MM-DD');

            //     var isSingleDay = (datefrom === dateto);
            //     var singleDay = isSingleDay ? datefrom : null;

            //     $.ajax({
            //         type: "GET",
            //         url: '/hr/employees/attendance/V4fetch',
            //         data: {
            //             action: 'getactiveemployees',
            //             single_date: singleDay
            //         },
            //         success: function(response) {
            //             var employees = response.data;
            //             var employee_manual_attendance = response.employee_manual_attendance;

            //             $("#attendanceBody").empty();
            //             let attendanceBody = $("#attendanceBody");
            //             employees.forEach(emp => {
            //                 var morning_in = '',
            //                     morning_out = '',
            //                     afternoon_in = '',
            //                     afternoon_out = '',
            //                     total_hours = '',
            //                     total_minutes = '',
            //                     remarks = '';
            //                 if (employee_manual_attendance[emp.id]) {
            //                     employee_manual_attendance[emp.id].forEach(entry => {
            //                         switch (entry.tapstate) {
            //                             case "IN":
            //                                 if (entry.timeshift === "AM") {
            //                                     morning_in = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 } else {
            //                                     afternoon_in = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 }
            //                                 break;
            //                             case "OUT":
            //                                 if (entry.timeshift === "AM") {
            //                                     morning_out = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 } else {
            //                                     afternoon_out = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 }
            //                                 break;
            //                         }
            //                     });

            //                     total_hours = 0;
            //                     total_minutes = 0;

            //                     if (morning_in && morning_out) {
            //                         let morning_duration = moment.duration(morning_out.diff(
            //                             morning_in));
            //                         total_hours += morning_duration.hours();
            //                         total_minutes += morning_duration.minutes();
            //                     }

            //                     if (afternoon_in && afternoon_out) {
            //                         let afternoon_duration = moment.duration(afternoon_out.diff(
            //                             afternoon_in));
            //                         total_hours += afternoon_duration.hours();
            //                         total_minutes += afternoon_duration.minutes();
            //                     }

            //                     // Convert total minutes to hours if more than 60
            //                     total_hours += Math.floor(total_minutes / 60);
            //                     total_minutes = total_minutes % 60;
            //                 }

            //                 attendanceBody.append(
            //                     `<tr>
        //         <td id="employee_name_${emp.id}">${emp.lastname}, ${emp.firstname}</td>
        //         <td id="status_${emp.id}" class="status"></td>
        //         <td id="morning_in_${emp.id}" class="morning_in">${morning_in ? morning_in.format('hh:mm A') : ''}</td>
        //         <td id="morning_out_${emp.id}" class="morning_out">${morning_out ? morning_out.format('hh:mm A') : ''}</td>
        //         <td id="afternoon_in_${emp.id}" class="afternoon_in">${afternoon_in ? afternoon_in.format('hh:mm A') : ''}</td>
        //         <td id="afternoon_out_${emp.id}" class="afternoon_out">${afternoon_out ? afternoon_out.format('hh:mm A') : ''}</td>
        //         <td id="total_hours_${emp.id}" class="total_hours">${total_hours}</td>
        //         <td id="total_minutes_${emp.id}" class="total_minutes">${total_minutes}</td>
        //         <td id="remarks_${emp.id}" class="remarks">${remarks}</td>
        //         <td data-id="${emp.id}" class="manual_attendance" style="text-decoration: underline; font-style: italic; color: blue;cursor:pointer;" onclick="$('#manual_attendance_modal').modal('show');">Manual Attendance</td>
        //     </tr>`
            //                 );
            //             });
            //         }
            //     });
            // }


            //working code v4 for single date
            // function getemployees_single_date() {

            //     var dates = $('#reservationnew').val().split(' - ');
            //     var datefrom = moment(dates[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
            //     var dateto = moment(dates[1], 'MM/DD/YYYY').format('YYYY-MM-DD');

            //     var isSingleDay = (datefrom === dateto);
            //     var singleDay = isSingleDay ? datefrom : null;

            //     $.ajax({
            //         type: "GET",
            //         url: '/hr/employees/attendance/V4fetch',
            //         data: {
            //             action: 'getactiveemployees',
            //             single_date: singleDay
            //         },
            //         success: function(response) {
            //             var employees = response.data;
            //             var employee_manual_attendance = response.employee_manual_attendance;

            //             $("#attendanceBody").empty();
            //             let attendanceBody = $("#attendanceBody");
            //             employees.forEach(emp => {
            //                 var morning_in = '',
            //                     morning_out = '',
            //                     afternoon_in = '',
            //                     afternoon_out = '',
            //                     attendance_total_hours = '',
            //                     attendance_total_minutes = '',
            //                     remarks = '';
            //                 if (employee_manual_attendance[emp.id]) {
            //                     employee_manual_attendance[emp.id].forEach(entry => {
            //                         switch (entry.tapstate) {
            //                             case "IN":
            //                                 if (entry.timeshift === "AM") {
            //                                     morning_in = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 } else {
            //                                     afternoon_in = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 }
            //                                 break;
            //                             case "OUT":
            //                                 if (entry.timeshift === "AM") {
            //                                     morning_out = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 } else {
            //                                     afternoon_out = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 }
            //                                 break;
            //                         }
            //                     });

            //                     attendance_total_hours = 0;
            //                     total_minutes = 0;

            //                     if (morning_in && morning_out) {
            //                         let morning_duration = moment.duration(morning_out.diff(
            //                             morning_in));
            //                         attendance_total_hours += morning_duration.hours();
            //                         attendance_total_minutes += morning_duration.minutes();
            //                     }

            //                     if (afternoon_in && afternoon_out) {
            //                         let afternoon_duration = moment.duration(afternoon_out.diff(
            //                             afternoon_in));
            //                         attendance_total_hours += afternoon_duration.hours();
            //                         attendance_total_minutes += afternoon_duration.minutes();
            //                     }

            //                     // Convert total minutes to hours if more than 60
            //                     attendance_total_hours += Math.floor(total_minutes / 60);
            //                     attendance_total_minutes = total_minutes % 60;
            //                 }

            //                 attendanceBody.append(
            //                     `<tr>
        //                         <td id="employee_name_${emp.id}">${emp.lastname}, ${emp.firstname}</td>
        //                         <td id="status_${emp.id}" class="status"></td>
        //                         <td id="morning_in_${emp.id}" class="morning_in">${morning_in ? morning_in.format('hh:mm A') : ''}</td>
        //                         <td id="morning_out_${emp.id}" class="morning_out">${morning_out ? morning_out.format('hh:mm A') : ''}</td>
        //                         <td id="afternoon_in_${emp.id}" class="afternoon_in">${afternoon_in ? afternoon_in.format('hh:mm A') : ''}</td>
        //                         <td id="afternoon_out_${emp.id}" class="afternoon_out">${afternoon_out ? afternoon_out.format('hh:mm A') : ''}</td>
        //                         <td id="total_hours_${emp.id}" class="total_hours">${attendance_total_hours}</td>
        //                         <td id="total_minutes_${emp.id}" class="total_minutes">${attendance_total_minutes}</td>
        //                         <td id="remarks_${emp.id}" class="remarks">${remarks}</td>
        //                         <td data-id="${emp.id}" class="manual_attendance" style="text-decoration: underline; font-style: italic; color: blue;cursor:pointer;" onclick="$('#manual_attendance_modal').modal('show');">Manual Attendance</td>
        //                     </tr>`
            //                 );
            //             });
            //         }
            //     });
            // }

            //flexi time 
            // function getemployees_single_date() {
            //     var dates = $('#reservationnew').val().split(' - ');
            //     var datefrom = moment(dates[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
            //     var dateto = moment(dates[1], 'MM/DD/YYYY').format('YYYY-MM-DD');

            //     var isSingleDay = (datefrom === dateto);
            //     var singleDay = isSingleDay ? datefrom : null;

            //     $.ajax({
            //         type: "GET",
            //         url: '/hr/employees/attendance/V4fetch',
            //         data: {
            //             action: 'getactiveemployees',
            //             single_date: singleDay
            //         },
            //         success: function(response) {
            //             var employees = response.data;
            //             var employee_manual_attendance = response.employee_manual_attendance;
            //             var employee_workdays = response.employee_workdays;

            //             $("#attendanceBody").empty();
            //             let attendanceBody = $("#attendanceBody");
            //             employees.forEach(emp => {
            //                 var morning_in = '',
            //                     morning_out = '',
            //                     afternoon_in = '',
            //                     afternoon_out = '',
            //                     attendance_total_hours = 0,
            //                     attendance_total_minutes = 0,
            //                     remarks = '';

            //                 if (employee_manual_attendance[emp.id]) {
            //                     employee_manual_attendance[emp.id].forEach(entry => {
            //                         switch (entry.tapstate) {
            //                             case "IN":
            //                                 if (entry.timeshift === "AM") {
            //                                     morning_in = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 } else {
            //                                     afternoon_in = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 }
            //                                 break;
            //                             case "OUT":
            //                                 if (entry.timeshift === "AM") {
            //                                     morning_out = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 } else {
            //                                     afternoon_out = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 }
            //                                 break;
            //                         }
            //                     });
            //                 }

            //                 if (employee_workdays[emp.id]) {
            //                     let workday = employee_workdays[emp.id][0];
            //                     let dayOfWeek = moment(singleDay).format('dddd').toLowerCase();

            //                     if (workday[dayOfWeek] === 1) {
            //                         if (morning_in && morning_out) {
            //                             let morning_duration = moment.duration(morning_out.diff(
            //                                 morning_in));
            //                             attendance_total_hours += morning_duration.hours();
            //                             attendance_total_minutes += morning_duration.minutes();
            //                         }

            //                         if (afternoon_in && afternoon_out) {
            //                             let afternoon_duration = moment.duration(afternoon_out
            //                                 .diff(afternoon_in));
            //                             attendance_total_hours += afternoon_duration.hours();
            //                             attendance_total_minutes += afternoon_duration
            //                         .minutes();
            //                         }

            //                         // Convert total minutes to hours if more than 60
            //                         attendance_total_hours += Math.floor(
            //                             attendance_total_minutes / 60);
            //                         attendance_total_minutes = attendance_total_minutes % 60;
            //                     }
            //                 }

            //                 attendanceBody.append(
            //                     `<tr>
        //                         <td id="employee_name_${emp.id}">${emp.lastname}, ${emp.firstname}</td>
        //                         <td id="status_${emp.id}" class="status"></td>
        //                         <td id="morning_in_${emp.id}" class="morning_in">${morning_in ? morning_in.format('hh:mm A') : ''}</td>
        //                         <td id="morning_out_${emp.id}" class="morning_out">${morning_out ? morning_out.format('hh:mm A') : ''}</td>
        //                         <td id="afternoon_in_${emp.id}" class="afternoon_in">${afternoon_in ? afternoon_in.format('hh:mm A') : ''}</td>
        //                         <td id="afternoon_out_${emp.id}" class="afternoon_out">${afternoon_out ? afternoon_out.format('hh:mm A') : ''}</td>
        //                         <td id="total_hours_${emp.id}" class="total_hours">${attendance_total_hours}</td>
        //                         <td id="total_minutes_${emp.id}" class="total_minutes">${attendance_total_minutes}</td>
        //                         <td id="remarks_${emp.id}" class="remarks">${remarks}</td>
        //                         <td data-id="${emp.id}" class="manual_attendance" style="text-decoration: underline; font-style: italic; color: blue;cursor:pointer;" onclick="$('#manual_attendance_modal').modal('show');">Manual Attendance</td>
        //                     </tr>`
            //                 );
            //             });
            //         }
            //     });
            // }

            //not flexi time
            // function getemployees_single_date() {
            //     var dates = $('#reservationnew').val().split(' - ');
            //     var datefrom = moment(dates[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
            //     var dateto = moment(dates[1], 'MM/DD/YYYY').format('YYYY-MM-DD');

            //     var isSingleDay = (datefrom === dateto);
            //     var singleDay = isSingleDay ? datefrom : null;

            //     $.ajax({
            //         type: "GET",
            //         url: '/hr/employees/attendance/V4fetch',
            //         data: {
            //             action: 'getactiveemployees',
            //             single_date: singleDay
            //         },
            //         success: function(response) {
            //             var employees = response.data;
            //             var employee_manual_attendance = response.employee_manual_attendance;
            //             var employee_workdays = response.employee_workdays;

            //             $("#attendanceBody").empty();
            //             let attendanceBody = $("#attendanceBody");
            //             employees.forEach(emp => {
            //                 var morning_in = '',
            //                     morning_out = '',
            //                     afternoon_in = '',
            //                     afternoon_out = '',
            //                     attendance_total_hours = 0,
            //                     attendance_total_minutes = 0,
            //                     remarks = '';

            //                 if (employee_manual_attendance[emp.id]) {
            //                     employee_manual_attendance[emp.id].forEach(entry => {
            //                         switch (entry.tapstate) {
            //                             case "IN":
            //                                 if (entry.timeshift === "AM") {
            //                                     morning_in = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 } else {
            //                                     afternoon_in = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 }
            //                                 break;
            //                             case "OUT":
            //                                 if (entry.timeshift === "AM") {
            //                                     morning_out = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 } else {
            //                                     afternoon_out = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 }
            //                                 break;
            //                         }
            //                     });
            //                 }

            //                 // if (employee_workdays[emp.id]) {
            //                 //     let workday = employee_workdays[emp.id][0];
            //                 //     let dayOfWeek = moment(singleDay).format('dddd').toLowerCase();

            //                 //     if (workday[dayOfWeek] === 1) {
            //                 //         if (morning_in && morning_out) {
            //                 //             let morning_duration = moment.duration(morning_out.diff(
            //                 //                 morning_in));
            //                 //             attendance_total_hours += morning_duration.hours();
            //                 //             attendance_total_minutes += morning_duration.minutes();
            //                 //         }

            //                 //         if (afternoon_in && afternoon_out) {
            //                 //             let afternoon_duration = moment.duration(afternoon_out
            //                 //                 .diff(afternoon_in));
            //                 //             attendance_total_hours += afternoon_duration.hours();
            //                 //             attendance_total_minutes += afternoon_duration
            //                 //                 .minutes();
            //                 //         }
            //                 //     }
            //                 // }

            //                 if (employee_workdays[emp.id]) {
            //                     let workday = employee_workdays[emp.id][0];
            //                     let dayOfWeek = moment(singleDay).format('dddd').toLowerCase();

            //                     if (workday[dayOfWeek] === 1) {
            //                         let morning_start = moment(workday[`${dayOfWeek}_amin`],
            //                             'HH:mm:ss');
            //                         let morning_end = moment(workday[`${dayOfWeek}_amout`],
            //                             'HH:mm:ss');
            //                         let afternoon_start = moment(workday[`${dayOfWeek}_pmin`],
            //                             'HH:mm:ss');
            //                         let afternoon_end = moment(workday[`${dayOfWeek}_pmout`],
            //                             'HH:mm:ss');

            //                         if (morning_in && morning_out && morning_in.isSameOrAfter(
            //                                 morning_start) && morning_out.isSameOrBefore(
            //                                 morning_end)) {
            //                             let morning_duration = moment.duration(morning_out.diff(
            //                                 morning_in));
            //                             attendance_total_hours += morning_duration.hours();
            //                             attendance_total_minutes += morning_duration.minutes();
            //                         }

            //                         if (afternoon_in && afternoon_out && afternoon_in
            //                             .isSameOrAfter(afternoon_start) && afternoon_out
            //                             .isSameOrBefore(afternoon_end)) {
            //                             let afternoon_duration = moment.duration(afternoon_out
            //                                 .diff(afternoon_in));
            //                             attendance_total_hours += afternoon_duration.hours();
            //                             attendance_total_minutes += afternoon_duration
            //                                 .minutes();
            //                         }
            //                     }
            //                 }


            //                 attendanceBody.append(
            //                     `<tr>
        //                         <td id="employee_name_${emp.id}">${emp.lastname}, ${emp.firstname}</td>
        //                         <td id="status_${emp.id}" class="status"></td>
        //                         <td id="morning_in_${emp.id}" class="morning_in">${morning_in ? morning_in.format('hh:mm A') : ''}</td>
        //                         <td id="morning_out_${emp.id}" class="morning_out">${morning_out ? morning_out.format('hh:mm A') : ''}</td>
        //                         <td id="afternoon_in_${emp.id}" class="afternoon_in">${afternoon_in ? afternoon_in.format('hh:mm A') : ''}</td>
        //                         <td id="afternoon_out_${emp.id}" class="afternoon_out">${afternoon_out ? afternoon_out.format('hh:mm A') : ''}</td>
        //                         <td id="total_hours_${emp.id}" class="total_hours">${attendance_total_hours}</td>
        //                         <td id="total_minutes_${emp.id}" class="total_minutes">${attendance_total_minutes}</td>
        //                         <td id="remarks_${emp.id}" class="remarks">${remarks}</td>
        //                         <td data-id="${emp.id}" class="manual_attendance" style="text-decoration: underline; font-style: italic; color: blue;cursor:pointer;" onclick="$('#manual_attendance_modal').modal('show');">Manual Attendance</td>
        //                     </tr>`
            //                 );
            //             });
            //         }
            //     });
            // }

            //final code v6
            // function getemployees_single_date() {
            //     var dates = $('#reservationnew').val().split(' - ');
            //     var datefrom = moment(dates[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
            //     var dateto = moment(dates[1], 'MM/DD/YYYY').format('YYYY-MM-DD');

            //     var isSingleDay = (datefrom === dateto);
            //     var singleDay = isSingleDay ? datefrom : null;

            //     $.ajax({
            //         type: "GET",
            //         url: '/hr/employees/attendance/V4fetch',
            //         data: {
            //             action: 'getactiveemployees',
            //             single_date: singleDay
            //         },
            //         success: function(response) {
            //             var employees = response.data;
            //             var employee_manual_attendance = response.employee_manual_attendance;
            //             var employee_workdays = response.employee_workdays;

            //             $("#attendanceBody").empty();
            //             let attendanceBody = $("#attendanceBody");
            //             employees.forEach(emp => {
            //                 var morning_in = '',
            //                     morning_out = '',
            //                     afternoon_in = '',
            //                     afternoon_out = '',
            //                     attendance_total_hours = 0,
            //                     attendance_total_minutes = 0,
            //                     remarks = '';

            //                 if (employee_manual_attendance[emp.id]) {
            //                     employee_manual_attendance[emp.id].forEach(entry => {
            //                         switch (entry.tapstate) {
            //                             case "IN":
            //                                 if (entry.timeshift === "AM") {
            //                                     morning_in = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 } else {
            //                                     afternoon_in = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 }
            //                                 break;
            //                             case "OUT":
            //                                 if (entry.timeshift === "AM") {
            //                                     morning_out = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 } else {
            //                                     afternoon_out = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 }
            //                                 break;
            //                         }
            //                     });
            //                 }


            //                 if (employee_workdays[emp.id]) {
            //                     let workday = employee_workdays[emp.id][0];
            //                     let dayOfWeek = moment(singleDay).format('dddd').toLowerCase();

            //                     if (workday[dayOfWeek] === 1) {
            //                         let morning_start = moment(workday[`${dayOfWeek}_amin`],
            //                             'HH:mm:ss');
            //                         let morning_end = moment(workday[`${dayOfWeek}_amout`],
            //                             'HH:mm:ss');
            //                         let afternoon_start = moment(workday[`${dayOfWeek}_pmin`],
            //                             'HH:mm:ss');
            //                         let afternoon_end = moment(workday[`${dayOfWeek}_pmout`],
            //                             'HH:mm:ss');

            //                         // if (morning_in && morning_out) {
            //                         //     if (morning_in.isBefore(morning_start)) {
            //                         //         morning_in = morning_start;
            //                         //     }
            //                         //     if (morning_out.isAfter(morning_end)) {
            //                         //         morning_out = morning_end;
            //                         //     }
            //                         //     let morning_duration = moment.duration(morning_out.diff(
            //                         //         morning_in));
            //                         //     attendance_total_hours += morning_duration.hours();
            //                         //     attendance_total_minutes += morning_duration.minutes();
            //                         // }

            //                         if (morning_in && morning_out) {
            //                             let morning_in_copy = moment(morning_in);
            //                             let morning_out_copy = moment(morning_out);
            //                             if (morning_in_copy.isBefore(morning_start)) {
            //                                 morning_in_copy = morning_start;
            //                             }
            //                             if (morning_out_copy.isAfter(morning_end)) {
            //                                 morning_out_copy = morning_end;
            //                             }
            //                             let morning_duration = moment.duration(morning_out_copy
            //                                 .diff(
            //                                     morning_in_copy));
            //                             attendance_total_hours += morning_duration.hours();
            //                             attendance_total_minutes += morning_duration.minutes();
            //                         }

            //                         if (afternoon_in && afternoon_out) {
            //                             let afternoon_in_copy = moment(afternoon_in);
            //                             let afternoon_out_copy = moment(afternoon_out);
            //                             if (afternoon_in_copy.isBefore(afternoon_start)) {
            //                                 afternoon_in_copy = afternoon_start;
            //                             }
            //                             if (afternoon_out_copy.isAfter(afternoon_end)) {
            //                                 afternoon_out_copy = afternoon_end;
            //                             }
            //                             let afternoon_duration = moment.duration(
            //                                 afternoon_out_copy
            //                                 .diff(
            //                                     afternoon_in_copy));
            //                             attendance_total_hours += afternoon_duration.hours();
            //                             attendance_total_minutes += afternoon_duration
            //                                 .minutes();
            //                         }
            //                     }
            //                 }


            //                 attendanceBody.append(
            //                     `<tr>
        //                     <td id="employee_name_${emp.id}">${emp.lastname}, ${emp.firstname}</td>
        //                     <td id="status_${emp.id}" class="status"></td>
        //                     <td id="morning_in_${emp.id}" class="morning_in">${morning_in ? morning_in.format('hh:mm A') : ''}</td>
        //                     <td id="morning_out_${emp.id}" class="morning_out">${morning_out ? morning_out.format('hh:mm A') : ''}</td>
        //                     <td id="afternoon_in_${emp.id}" class="afternoon_in">${afternoon_in ? afternoon_in.format('hh:mm A') : ''}</td>
        //                     <td id="afternoon_out_${emp.id}" class="afternoon_out">${afternoon_out ? afternoon_out.format('hh:mm A') : ''}</td>
        //                     <td id="total_hours_${emp.id}" class="total_hours">${attendance_total_hours}</td>
        //                     <td id="total_minutes_${emp.id}" class="total_minutes">${attendance_total_minutes}</td>
        //                     <td id="remarks_${emp.id}" class="remarks">${remarks}</td>
        //                     <td data-id="${emp.id}" class="manual_attendance" style="text-decoration: underline; font-style: italic; color: blue;cursor:pointer;" onclick="$('#manual_attendance_modal').modal('show');">Manual Attendance</td>
        //                 </tr>`
            //                 );
            //             });
            //         }
            //     });
            // }

            // working code v7
            // function getemployees_single_date() {
            //     var dates = $('#reservationnew').val().split(' - ');
            //     var datefrom = moment(dates[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
            //     var dateto = moment(dates[1], 'MM/DD/YYYY').format('YYYY-MM-DD');

            //     var isSingleDay = (datefrom === dateto);
            //     var singleDay = isSingleDay ? datefrom : null;

            //     $.ajax({
            //         type: "GET",
            //         url: '/hr/employees/attendance/V4fetch',
            //         data: {
            //             action: 'getactiveemployees',
            //             single_date: singleDay
            //         },
            //         success: function(response) {
            //             var employees = response.data;
            //             var employee_manual_attendance = response.employee_manual_attendance;
            //             var employee_manual_attendance_remarks = response
            //                 .employee_manual_attendance_remarks;
            //             var employee_workdays = response.employee_workdays;

            //             $("#attendanceBody").empty();
            //             let attendanceBody = $("#attendanceBody");
            //             employees.forEach(emp => {
            //                 var morning_in = '',
            //                     morning_out = '',
            //                     afternoon_in = '',
            //                     afternoon_out = '',
            //                     attendance_total_hours = '',
            //                     attendance_total_minutes = '',
            //                     remarks = '';

            //                 if (employee_manual_attendance[emp.id]) {
            //                     employee_manual_attendance[emp.id].forEach(entry => {
            //                         switch (entry.tapstate) {
            //                             case "IN":
            //                                 if (entry.timeshift === "AM") {
            //                                     morning_in = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 } else {
            //                                     afternoon_in = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 }
            //                                 break;
            //                             case "OUT":
            //                                 if (entry.timeshift === "AM") {
            //                                     morning_out = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 } else {
            //                                     afternoon_out = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 }
            //                                 break;
            //                         }
            //                     });
            //                 }


            //                 if (employee_workdays[emp.id]) {
            //                     let workday = employee_workdays[emp.id][0];
            //                     let dayOfWeek = moment(singleDay).format('dddd').toLowerCase();

            //                     if (workday[dayOfWeek] === 1) {
            //                         let morning_start = moment(workday[`${dayOfWeek}_amin`],
            //                             'HH:mm:ss');
            //                         let morning_end = moment(workday[`${dayOfWeek}_amout`],
            //                             'HH:mm:ss');
            //                         let afternoon_start = moment(workday[`${dayOfWeek}_pmin`],
            //                             'HH:mm:ss');
            //                         let afternoon_end = moment(workday[`${dayOfWeek}_pmout`],
            //                             'HH:mm:ss');

            //                         attendance_total_hours = 0;
            //                         attendance_total_minutes = 0;
            //                         if (morning_in && morning_out) {
            //                             let morning_in_copy = moment(morning_in);
            //                             let morning_out_copy = moment(morning_out);
            //                             if (morning_in_copy.isBefore(morning_start)) {
            //                                 morning_in_copy = morning_start;
            //                             }
            //                             if (morning_out_copy.isAfter(morning_end)) {
            //                                 morning_out_copy = morning_end;
            //                             }
            //                             let morning_duration = moment.duration(
            //                                 morning_out_copy
            //                                 .diff(
            //                                     morning_in_copy));
            //                             attendance_total_hours += morning_duration.hours();
            //                             attendance_total_minutes += morning_duration
            //                                 .minutes();
            //                         }

            //                         if (afternoon_in && afternoon_out) {
            //                             let afternoon_in_copy = moment(afternoon_in);
            //                             let afternoon_out_copy = moment(afternoon_out);
            //                             if (afternoon_in_copy.isBefore(afternoon_start)) {
            //                                 afternoon_in_copy = afternoon_start;
            //                             }
            //                             if (afternoon_out_copy.isAfter(afternoon_end)) {
            //                                 afternoon_out_copy = afternoon_end;
            //                             }
            //                             let afternoon_duration = moment.duration(
            //                                 afternoon_out_copy
            //                                 .diff(
            //                                     afternoon_in_copy));
            //                             attendance_total_hours += afternoon_duration.hours();
            //                             attendance_total_minutes += afternoon_duration
            //                                 .minutes();
            //                         }
            //                     }
            //                 }

            //                 let status = '';
            //                 if (attendance_total_hours === '') {
            //                     status = '';
            //                 } else if (attendance_total_hours === 8) {
            //                     status = 'Present';
            //                 } else if (attendance_total_hours === 0) {
            //                     status = '---';
            //                 } else if (attendance_total_hours < 8) {
            //                     status = 'Tardy';
            //                 } else if (attendance_total_hours > 8) {
            //                     status = 'Overtime';
            //                 }

            //                 attendanceBody.append(
            //                     `<tr>
        //                     <td id="employee_name_${emp.id}">${emp.lastname}, ${emp.firstname}</td>
        //                     <td id="status_${emp.id}" class="status">${status}</td>
        //                     <td id="morning_in_${emp.id}" class="morning_in">${morning_in ? morning_in.format('hh:mm A') : ''}</td>
        //                     <td id="morning_out_${emp.id}" class="morning_out">${morning_out ? morning_out.format('hh:mm A') : ''}</td>
        //                     <td id="afternoon_in_${emp.id}" class="afternoon_in">${afternoon_in ? afternoon_in.format('hh:mm A') : ''}</td>
        //                     <td id="afternoon_out_${emp.id}" class="afternoon_out">${afternoon_out ? afternoon_out.format('hh:mm A') : ''}</td>
        //                     <td id="total_hours_${emp.id}" class="total_hours">${attendance_total_hours}</td>
        //                     <td id="total_minutes_${emp.id}" class="total_minutes">${attendance_total_minutes}</td>
        //                     <td id="remarks_${emp.id}" class="remarks">${remarks}</td>
        //                     <td data-id="${emp.id}" class="manual_attendance" style="text-decoration: underline; font-style: italic; color: blue;cursor:pointer;" onclick="$('#manual_attendance_modal').modal('show');">Manual Attendance</td>
        //                 </tr>`
            //                 );
            //             });
            //         }
            //     });
            // }

            //working code v8
            // function getemployees_single_date() {
            //     var dates = $('#reservationnew').val().split(' - ');
            //     var datefrom = moment(dates[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
            //     var dateto = moment(dates[1], 'MM/DD/YYYY').format('YYYY-MM-DD');

            //     var isSingleDay = (datefrom === dateto);
            //     var singleDay = isSingleDay ? datefrom : null;

            //     $.ajax({
            //         type: "GET",
            //         url: '/hr/employees/attendance/V4fetch',
            //         data: {
            //             action: 'getactiveemployees',
            //             single_date: singleDay
            //         },
            //         success: function(response) {
            //             var employees = response.data;
            //             var employee_manual_attendance = response.employee_manual_attendance;
            //             var employee_manual_attendance_remarks = response
            //                 .employee_manual_attendance_remarks;
            //             var employee_workdays = response.employee_workdays;

            //             $("#attendanceBody").empty();
            //             let attendanceBody = $("#attendanceBody");
            //             employees.forEach(emp => {
            //                 var morning_in = '',
            //                     morning_out = '',
            //                     afternoon_in = '',
            //                     afternoon_out = '',
            //                     attendance_total_hours = '',
            //                     attendance_total_minutes = '',
            //                     remarks = '';

            //                 if (employee_manual_attendance[emp.id]) {
            //                     employee_manual_attendance[emp.id].forEach(entry => {
            //                         switch (entry.tapstate) {
            //                             case "IN":
            //                                 if (entry.timeshift === "AM") {
            //                                     morning_in = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 } else {
            //                                     afternoon_in = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 }
            //                                 break;
            //                             case "OUT":
            //                                 if (entry.timeshift === "AM") {
            //                                     morning_out = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 } else {
            //                                     afternoon_out = moment(entry.ttime,
            //                                         'HH:mm:ss');
            //                                 }
            //                                 break;
            //                         }
            //                     });
            //                 }

            //                 if (employee_manual_attendance_remarks[emp.id]) {
            //                     remarks = employee_manual_attendance_remarks[emp.id][0].remarks;
            //                 }

            //                 if (employee_workdays[emp.id]) {
            //                     let workday = employee_workdays[emp.id][0];
            //                     let dayOfWeek = moment(singleDay).format('dddd').toLowerCase();

            //                     if (workday[dayOfWeek] === 1) {
            //                         let morning_start = moment(workday[`${dayOfWeek}_amin`],
            //                             'HH:mm:ss');
            //                         let morning_end = moment(workday[`${dayOfWeek}_amout`],
            //                             'HH:mm:ss');
            //                         let afternoon_start = moment(workday[`${dayOfWeek}_pmin`],
            //                             'HH:mm:ss');
            //                         let afternoon_end = moment(workday[`${dayOfWeek}_pmout`],
            //                             'HH:mm:ss');

            //                         attendance_total_hours = 0;
            //                         attendance_total_minutes = 0;
            //                         if (morning_in && morning_out) {
            //                             let morning_in_copy = moment(morning_in);
            //                             let morning_out_copy = moment(morning_out);
            //                             if (morning_in_copy.isBefore(morning_start)) {
            //                                 morning_in_copy = morning_start;
            //                             }
            //                             if (morning_out_copy.isAfter(morning_end)) {
            //                                 morning_out_copy = morning_end;
            //                             }
            //                             let morning_duration = moment.duration(
            //                                 morning_out_copy
            //                                 .diff(
            //                                     morning_in_copy));
            //                             attendance_total_hours += morning_duration.hours();
            //                             attendance_total_minutes += morning_duration
            //                                 .minutes();
            //                         }

            //                         if (afternoon_in && afternoon_out) {
            //                             let afternoon_in_copy = moment(afternoon_in);
            //                             let afternoon_out_copy = moment(afternoon_out);
            //                             if (afternoon_in_copy.isBefore(afternoon_start)) {
            //                                 afternoon_in_copy = afternoon_start;
            //                             }
            //                             if (afternoon_out_copy.isAfter(afternoon_end)) {
            //                                 afternoon_out_copy = afternoon_end;
            //                             }
            //                             let afternoon_duration = moment.duration(
            //                                 afternoon_out_copy
            //                                 .diff(
            //                                     afternoon_in_copy));
            //                             attendance_total_hours += afternoon_duration.hours();
            //                             attendance_total_minutes += afternoon_duration
            //                                 .minutes();
            //                         }
            //                     }
            //                 }

            //                 let status = '';
            //                 if (attendance_total_hours === '') {
            //                     status = '';
            //                 } else if (attendance_total_hours === 8) {
            //                     status = 'Present';
            //                 } else if (attendance_total_hours === 0 && remarks === '') {
            //                     status = '---';
            //                 } else if (attendance_total_hours < 8 && attendance_total_hours !==
            //                     0) {
            //                     status = 'Tardy';
            //                 } else if (attendance_total_hours > 8) {
            //                     status = 'Overtime';
            //                 } else if (attendance_total_hours === 0 && remarks != '') {
            //                     status = 'Absent';
            //                 }

            //                 attendanceBody.append(
            //                     `<tr>
        //                     <td id="employee_name_${emp.id}">${emp.lastname}, ${emp.firstname}</td>
        //                     <td id="status_${emp.id}" class="status">${status}</td>
        //                     <td id="morning_in_${emp.id}" class="morning_in">${morning_in ? morning_in.format('hh:mm A') : ''}</td>
        //                     <td id="morning_out_${emp.id}" class="morning_out">${morning_out ? morning_out.format('hh:mm A') : ''}</td>
        //                     <td id="afternoon_in_${emp.id}" class="afternoon_in">${afternoon_in ? afternoon_in.format('hh:mm A') : ''}</td>
        //                     <td id="afternoon_out_${emp.id}" class="afternoon_out">${afternoon_out ? afternoon_out.format('hh:mm A') : ''}</td>
        //                     <td id="total_hours_${emp.id}" class="total_hours">${attendance_total_hours}</td>
        //                     <td id="total_minutes_${emp.id}" class="total_minutes">${attendance_total_minutes}</td>
        //                     <td id="remarks_${emp.id}" class="remarks">${remarks ? remarks : ''}</td>
        //                     <td data-id="${emp.id}" class="manual_attendance" style="text-decoration: underline; font-style: italic; color: blue;cursor:pointer;" onclick="$('#manual_attendance_modal').modal('show');">Manual Attendance</td>
        //                 </tr>`
            //                 );
            //             });
            //         }
            //     });
            // }


            // working code v3 for multiple date
            // function getemployees_multiple_date(columnCount) {
            //     $.ajax({
            //         type: "GET",
            //         url: '/hr/employees/attendance/V4fetch',
            //         data: {
            //             action: 'getactiveemployees'
            //         },
            //         success: function(response) {
            //             var employees = response.data;
            //             let attendanceBody = $("#attendanceBody");
            //             attendanceBody.empty();

            //             employees.forEach(emp => {
            //                 let row =
            //                     `<tr> <td data-id="${emp.id}">${emp.lastname}, ${emp.firstname}</td>`;

            //                 // Append empty tds based on dynamic headers count
            //                 for (let i = 0; i < columnCount; i++) {
            //                     row += '<td class="attendance_status">Present</td>';
            //                 }
            //                 row +=
            //                     `<td style="background-color: white;position: sticky; right: 0; text-decoration: underline; font-style: italic; color: blue;cursor:pointer;" onclick="$('#manual_attendance_modal').modal('show');">Manual Attendance</td>`;
            //                 row += '</tr>';
            //                 attendanceBody.append(row);
            //             });
            //         }
            //     });
            // }

            function generateTableHeadersSingleday(startDate, endDate) {
                let headerRow = $("#dynamicHeader");
                // headerRow.html(
                //     '<th>Employee Name</th><th>Status</th><th colspan="2">1st Shift</th><th colspan="2">2nd Shift</th><th colspan="2">Total</th><th>Remarks</th><th>Action</th>'
                // );
                headerRow.html(
                    '<tr>' +
                    '<th rowspan="2" style="font-size: 12px;">Employee Name</th>' +
                    '<th rowspan="2" style="font-size: 12px;">Attendance Status</th>' +
                    '<th colspan="2" style="font-size: 12px;">MORNING</th>' +
                    '<th colspan="2" style="font-size: 12px;">AFTERNOON</th>' +
                    '<th colspan="2" style="font-size: 12px;">TOTAL</th>' +
                    '<th rowspan="2" style="font-size: 12px;">REMARKS</th>' +
                    '<th rowspan="2" style="font-size: 12px;">Actions</th>' +
                    '</tr>' +
                    '<tr>' +
                    '<th style="font-size: 10px;">AM IN</th>' +
                    '<th style="font-size: 10px;">AM OUT</th>' +
                    '<th style="font-size: 10px;">PM IN</th>' +
                    '<th style="font-size: 10px;">PM OUT</th>' +
                    '<th colspan="2" style="font-size: 10px;">HOURS / MINUTES</th>' +
                    '</tr>'
                );

                getemployees_single_date()

            }

            function getemployees_single_date() {
                var dates = $('#reservationnew').val().split(' - ');
                var datefrom = moment(dates[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
                var dateto = moment(dates[1], 'MM/DD/YYYY').format('YYYY-MM-DD');

                var isSingleDay = (datefrom === dateto);
                var singleDay = isSingleDay ? datefrom : null;

                $.ajax({
                    type: "GET",
                    url: '/hr/employees/attendance/V4fetch',
                    data: {
                        action: 'getactiveemployees',
                        single_date: singleDay
                    },
                    success: function(response) {
                        var employees = response.data;
                        var employee_manual_attendance = response.employee_manual_attendance;
                        var employee_manual_attendance_remarks = response
                            .employee_manual_attendance_remarks;
                        var employee_workdays = response.employee_workdays;

                        $("#attendanceBody").empty();
                        let attendanceBody = $("#attendanceBody");
                        employees.forEach(emp => {
                            var morning_in = '',
                                morning_out = '',
                                afternoon_in = '',
                                afternoon_out = '',
                                attendance_total_hours = '',
                                attendance_total_minutes = '',
                                remarks = '';

                            if (employee_manual_attendance[emp.id]) {
                                employee_manual_attendance[emp.id].forEach(entry => {
                                    switch (entry.tapstate) {
                                        case "IN":
                                            if (entry.timeshift === "AM") {
                                                morning_in = moment(entry.ttime,
                                                    'HH:mm:ss');
                                            } else {
                                                afternoon_in = moment(entry.ttime,
                                                    'HH:mm:ss');
                                            }
                                            break;
                                        case "OUT":
                                            if (entry.timeshift === "AM") {
                                                morning_out = moment(entry.ttime,
                                                    'HH:mm:ss');
                                            } else {
                                                afternoon_out = moment(entry.ttime,
                                                    'HH:mm:ss');
                                            }
                                            break;
                                    }
                                });
                            }

                            if (employee_manual_attendance_remarks[emp.id]) {
                                remarks = employee_manual_attendance_remarks[emp.id][0].remarks;
                            }

                            if (employee_workdays[emp.id]) {
                                let workday = employee_workdays[emp.id][0];
                                let dayOfWeek = moment(singleDay).format('dddd').toLowerCase();

                                if (workday[dayOfWeek] === 2 || workday[dayOfWeek] === 1 ||
                                    workday[
                                        dayOfWeek] === 0) {
                                    let morning_start = moment(workday[`${dayOfWeek}_amin`],
                                        'HH:mm:ss');
                                    let morning_end = moment(workday[`${dayOfWeek}_amout`],
                                        'HH:mm:ss');
                                    let afternoon_start = moment(workday[`${dayOfWeek}_pmin`],
                                        'HH:mm:ss');
                                    let afternoon_end = moment(workday[`${dayOfWeek}_pmout`],
                                        'HH:mm:ss');

                                    attendance_total_hours = 0;
                                    attendance_total_minutes = 0;
                                    if (morning_in && morning_out) {
                                        let morning_in_copy = moment(morning_in);
                                        let morning_out_copy = moment(morning_out);
                                        if (morning_in_copy.isBefore(morning_start)) {
                                            morning_in_copy = morning_start;
                                        }
                                        if (morning_out_copy.isAfter(morning_end)) {
                                            morning_out_copy = morning_end;
                                        }
                                        let morning_duration = moment.duration(
                                            morning_out_copy
                                            .diff(
                                                morning_in_copy));
                                        attendance_total_hours += morning_duration.hours();
                                        attendance_total_minutes += morning_duration
                                            .minutes();
                                    }

                                    if (afternoon_in && afternoon_out) {
                                        let afternoon_in_copy = moment(afternoon_in);
                                        let afternoon_out_copy = moment(afternoon_out);
                                        if (afternoon_in_copy.isBefore(afternoon_start)) {
                                            afternoon_in_copy = afternoon_start;
                                        }
                                        if (afternoon_out_copy.isAfter(afternoon_end)) {
                                            afternoon_out_copy = afternoon_end;
                                        }
                                        let afternoon_duration = moment.duration(
                                            afternoon_out_copy
                                            .diff(
                                                afternoon_in_copy));
                                        attendance_total_hours += afternoon_duration.hours();
                                        attendance_total_minutes += afternoon_duration
                                            .minutes();
                                    }
                                }
                            }

                            let status = '';
                            if (attendance_total_hours === '') {
                                status = '';
                            } else if (attendance_total_hours === 8) {
                                status = 'Present';
                            } else if (attendance_total_hours === 0 && remarks === '') {
                                status = '---';
                            } else if (attendance_total_hours < 8 && attendance_total_hours !==
                                0) {
                                status = 'Tardy';
                            } else if (attendance_total_hours > 8) {
                                status = 'Overtime';
                            } else if (attendance_total_hours === 0 && remarks != '') {
                                status = 'Absent';
                            }

                            attendanceBody.append(
                                `<tr>
                                <td id="employee_name_${emp.id}">${emp.lastname}, ${emp.firstname}</td>
                                <td class="status_${status.toLowerCase()}" id="status_${emp.id}" class="status">${status}</td>
                                <td id="morning_in_${emp.id}" class="morning_in">${morning_in ? morning_in.format('hh:mm A') : ''}</td>
                                <td id="morning_out_${emp.id}" class="morning_out">${morning_out ? morning_out.format('hh:mm A') : ''}</td>
                                <td id="afternoon_in_${emp.id}" class="afternoon_in">${afternoon_in ? afternoon_in.format('hh:mm A') : ''}</td>
                                <td id="afternoon_out_${emp.id}" class="afternoon_out">${afternoon_out ? afternoon_out.format('hh:mm A') : ''}</td>
                                <td id="total_hours_${emp.id}" class="total_hours">${attendance_total_hours}</td>
                                <td id="total_minutes_${emp.id}" class="total_minutes">${attendance_total_minutes}</td>
                                <td id="remarks_${emp.id}" class="remarks">${remarks ? remarks : ''}</td>
                                <td data-id="${emp.id}" class="manual_attendance" style="text-decoration: underline; font-style: italic; color: blue;cursor:pointer;" onclick="$('#manual_attendance_modal').modal('show');">Manual Attendance</td>
                            </tr>`
                            );
                        });
                    }
                });
            }

            function generateTableHeaders(startDate, endDate) {
                let start = new Date(startDate);
                let end = new Date(endDate);
                let headerRow = $("#dynamicHeader");
                headerRow.html('<th>Employee Name</th>');

                let dateHeaders = [];

                while (start <= end) {
                    let formattedDate = start.toISOString().split('T')[0]; // YYYY-MM-DD format
                    let displayDate = start.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric'
                    });
                    headerRow.append(`<th data-date="${formattedDate}">${displayDate}</th>`);
                    dateHeaders.push(formattedDate);
                    start.setDate(start.getDate() + 1);
                }

                headerRow.append('<th style="position: sticky; right: 0;">Action</th>');

                getemployees_multiple_date(dateHeaders); // Pass actual dates, not length
            }

            //working code v4 finale
            // function getemployees_multiple_date(dateArray) {
            //     $.ajax({
            //         type: "GET",
            //         url: '/hr/employees/attendance/V4fetch_allattendance',
            //         data: {
            //             dateArray: dateArray
            //         },
            //         success: function(response) {
            //             let employees = response.data;
            //             let employee_manual_attendance = response.employee_manual_attendance;
            //             let employee_manual_attendance_remarks = response
            //                 .employee_manual_attendance_remarks;
            //             let employee_workdays = response.employee_workdays;

            //             console.log("Fetched employees: ", employees);
            //             console.log("Fetched employee_manual_attendance: ", employee_manual_attendance);
            //             console.log("Fetched employee_manual_attendance_remarks: ",
            //                 employee_manual_attendance_remarks);
            //             console.log("Fetched employee_workdays: ", employee_workdays);

            //             let attendanceBody = $("#attendanceBody");
            //             attendanceBody.empty();

            //             employees.forEach(emp => {
            //                 let row =
            //                     `<tr><td id="employee_name_${emp.id}">${emp.lastname}, ${emp.firstname}</td>`;

            //                 dateArray.forEach(date => {
            //                     let morning_in = '',
            //                         morning_out = '',
            //                         afternoon_in = '',
            //                         afternoon_out = '';
            //                     let attendance_total_hours = 0,
            //                         attendance_total_minutes = 0;
            //                     let remarks = "";

            //                     if (employee_manual_attendance[emp.id] &&
            //                         employee_manual_attendance[emp.id][date]) {
            //                         employee_manual_attendance[emp.id][date].forEach(
            //                             entry => {
            //                                 let time = moment(entry.ttime,
            //                                     'HH:mm:ss');
            //                                 if (entry.tapstate === "IN") {
            //                                     if (entry.timeshift === "AM") {
            //                                         morning_in = time;
            //                                     } else {
            //                                         afternoon_in = time;
            //                                     }
            //                                 } else if (entry.tapstate === "OUT") {
            //                                     if (entry.timeshift === "AM") {
            //                                         morning_out = time;
            //                                     } else {
            //                                         afternoon_out = time;
            //                                     }
            //                                 }
            //                             });
            //                     }

            //                     if (employee_workdays[emp.id]) {
            //                         let workday = employee_workdays[emp.id][0];
            //                         let dayOfWeek = moment(date).format('dddd')
            //                             .toLowerCase();

            //                         if (workday[dayOfWeek] === 1) {
            //                             let morning_start = moment(workday[
            //                                 `${dayOfWeek}_amin`], 'HH:mm:ss');
            //                             let morning_end = moment(workday[
            //                                 `${dayOfWeek}_amout`], 'HH:mm:ss');
            //                             let afternoon_start = moment(workday[
            //                                 `${dayOfWeek}_pmin`], 'HH:mm:ss');
            //                             let afternoon_end = moment(workday[
            //                                 `${dayOfWeek}_pmout`], 'HH:mm:ss');

            //                             if (morning_in && morning_out) {
            //                                 let morning_in_copy = moment(morning_in);
            //                                 let morning_out_copy = moment(morning_out);
            //                                 if (morning_in_copy.isBefore(morning_start))
            //                                     morning_in_copy = morning_start;
            //                                 if (morning_out_copy.isAfter(morning_end))
            //                                     morning_out_copy = morning_end;

            //                                 let morning_duration = moment.duration(
            //                                     morning_out_copy.diff(
            //                                         morning_in_copy));
            //                                 attendance_total_hours += morning_duration
            //                                     .hours();
            //                                 attendance_total_minutes += morning_duration
            //                                     .minutes();
            //                             }

            //                             if (afternoon_in && afternoon_out) {
            //                                 let afternoon_in_copy = moment(
            //                                     afternoon_in);
            //                                 let afternoon_out_copy = moment(
            //                                     afternoon_out);
            //                                 if (afternoon_in_copy.isBefore(
            //                                         afternoon_start))
            //                                     afternoon_in_copy = afternoon_start;
            //                                 if (afternoon_out_copy.isAfter(
            //                                         afternoon_end)) afternoon_out_copy =
            //                                     afternoon_end;

            //                                 let afternoon_duration = moment.duration(
            //                                     afternoon_out_copy.diff(
            //                                         afternoon_in_copy));
            //                                 attendance_total_hours += afternoon_duration
            //                                     .hours();
            //                                 attendance_total_minutes +=
            //                                     afternoon_duration.minutes();
            //                             }
            //                         }
            //                     }

            //                     console.log(
            //                         `Employee: ${emp.id}, Date: ${date}, Hours: ${attendance_total_hours}, Minutes: ${attendance_total_minutes}`
            //                     );

            //                     let status = '';
            //                     if (attendance_total_hours === 8) {
            //                         status = 'Present';
            //                     } else if (attendance_total_hours === 0 && remarks ===
            //                         '') {
            //                         status = '---';
            //                     } else if (attendance_total_hours < 8 &&
            //                         attendance_total_hours !== 0) {
            //                         status = 'Tardy';
            //                     } else if (attendance_total_hours > 8) {
            //                         status = 'Overtime';
            //                     } else if (attendance_total_hours === 0 && remarks !==
            //                         '') {
            //                         status = 'Absent';
            //                     }

            //                     row +=
            //                         `<td class="status_${status.toLowerCase()}" data-id="${emp.id}">${status}</td>`;
            //                 });

            //                 row += `<td style="background-color: white;position: sticky; right: 0; text-decoration: underline; font-style: italic; color: blue;cursor:pointer;" 
        //             onclick="$('#manual_attendance_modal').modal('show');">Manual Attendance</td>`;
            //                 row += `</tr>`;

            //                 attendanceBody.append(row);
            //             });
            //         }
            //     });
            // }


            function getemployees_multiple_date(dateArray) {
                $.ajax({
                    type: "GET",
                    url: '/hr/employees/attendance/V4fetch_allattendance',
                    data: {
                        dateArray: dateArray
                    },
                    success: function(response) {
                        var employees = response.data;
                        var employee_manual_attendance = response.employee_manual_attendance;
                        var employee_manual_attendance_remarks = response
                            .employee_manual_attendance_remarks;
                        var employee_workdays = response.employee_workdays;

                        console.log("Fetched employees: ", employees);
                        console.log("Fetched employee_manual_attendance: ", employee_manual_attendance);
                        console.log("Fetched employee_manual_attendance_remarks: ",
                            employee_manual_attendance_remarks);
                        console.log("Fetched employee_workdays: ", employee_workdays);

                        var attendanceBody = $("#attendanceBody");
                        attendanceBody.empty();

                        employees.forEach(emp => {
                            var row =
                                `<tr><td id="employee_name_${emp.id}">${emp.lastname}, ${emp.firstname}</td>`;

                            dateArray.forEach(date => {
                                var morning_in = '',
                                    morning_out = '',
                                    afternoon_in = '',
                                    afternoon_out = '';
                                var attendance_total_hours = 0,
                                    attendance_total_minutes = 0;
                                var remarks = '';

                                if (employee_manual_attendance[emp.id] &&
                                    employee_manual_attendance[emp.id][date]) {
                                    employee_manual_attendance[emp.id][date].forEach(
                                        entry => {
                                            let time = moment(entry.ttime,
                                                'HH:mm:ss');
                                            if (entry.tapstate === "IN") {
                                                if (entry.timeshift === "AM") {
                                                    morning_in = time;
                                                } else {
                                                    afternoon_in = time;
                                                }
                                            } else if (entry.tapstate === "OUT") {
                                                if (entry.timeshift === "AM") {
                                                    morning_out = time;
                                                } else {
                                                    afternoon_out = time;
                                                }
                                            }
                                        });
                                }

                                if (employee_manual_attendance_remarks[emp.id] &&
                                    employee_manual_attendance_remarks[emp.id][date]) {
                                    remarks = employee_manual_attendance_remarks[emp.id]
                                        [date][0].remarks;
                                }

                                if (employee_workdays[emp.id]) {
                                    var workday = employee_workdays[emp.id][0];
                                    var dayOfWeek = moment(date).format('dddd')
                                        .toLowerCase();

                                    if (workday[dayOfWeek] === 2 || workday[
                                            dayOfWeek] === 1 || workday[
                                            dayOfWeek] === 0) {
                                        var morning_start = moment(workday[
                                            `${dayOfWeek}_amin`], 'HH:mm:ss');
                                        var morning_end = moment(workday[
                                            `${dayOfWeek}_amout`], 'HH:mm:ss');
                                        var afternoon_start = moment(workday[
                                            `${dayOfWeek}_pmin`], 'HH:mm:ss');
                                        var afternoon_end = moment(workday[
                                            `${dayOfWeek}_pmout`], 'HH:mm:ss');

                                        if (morning_in && morning_out) {
                                            var morning_in_copy = moment(morning_in);
                                            var morning_out_copy = moment(morning_out);
                                            if (morning_in_copy.isBefore(morning_start))
                                                morning_in_copy = morning_start;
                                            if (morning_out_copy.isAfter(morning_end))
                                                morning_out_copy = morning_end;

                                            var morning_duration = moment.duration(
                                                morning_out_copy.diff(
                                                    morning_in_copy));
                                            attendance_total_hours += morning_duration
                                                .hours();
                                            attendance_total_minutes += morning_duration
                                                .minutes();
                                        }

                                        if (afternoon_in && afternoon_out) {
                                            var afternoon_in_copy = moment(
                                                afternoon_in);
                                            var afternoon_out_copy = moment(
                                                afternoon_out);
                                            if (afternoon_in_copy.isBefore(
                                                    afternoon_start))
                                                afternoon_in_copy = afternoon_start;
                                            if (afternoon_out_copy.isAfter(
                                                    afternoon_end)) afternoon_out_copy =
                                                afternoon_end;

                                            var afternoon_duration = moment.duration(
                                                afternoon_out_copy.diff(
                                                    afternoon_in_copy));
                                            attendance_total_hours += afternoon_duration
                                                .hours();
                                            attendance_total_minutes +=
                                                afternoon_duration.minutes();
                                        }
                                    }
                                }

                                console.log(
                                    `Employee: ${emp.id}, Date: ${date}, Hours: ${attendance_total_hours}, Minutes: ${attendance_total_minutes}`
                                );

                                let status = '';
                                if (attendance_total_hours === '') {
                                    status = '';
                                } else if (attendance_total_hours === 8) {
                                    status = 'Present';
                                } else if (attendance_total_hours === 0 && remarks ===
                                    '') {
                                    status = '';
                                } else if (attendance_total_hours < 8 &&
                                    attendance_total_hours !==
                                    0) {
                                    status = 'Tardy';
                                } else if (attendance_total_hours > 8) {
                                    status = 'Overtime';
                                } else if (attendance_total_hours === 0 && remarks !==
                                    '') {
                                    status = 'Absent';
                                }

                                row +=
                                    `<td class="status_${status.toLowerCase()}" data-remarks="${remarks ? remarks : ''}" data-id="${emp.id}" data-morning-in="${morning_in ? morning_in.format('h:mm a') : ''}" data-afternoon-out="${afternoon_out ? afternoon_out.format('h:mm a') : ''}">${status}</td>`;

                                $(document).on('mouseover',
                                    '.status_present, .status_tardy, .status_overtime',
                                    function(e) {
                                        var morning_in = $(this).data('morning-in');
                                        var afternoon_out = $(this).data(
                                            'afternoon-out');
                                        var html = `
                                        <div style="position: absolute; background-color: #f5f5f5; padding: 5px; border: 1px solid #ccc; z-index: 1; left: ${e.pageX}px; top: ${e.pageY}px;">
                                            Morning In: ${morning_in ? morning_in : '-'}<br>
                                            Afternoon Out: ${afternoon_out ? afternoon_out : '-'}<br>
                                        </div>
                                    `;
                                        var $html = $(html).appendTo('body');
                                        setTimeout(() => {
                                            $html.fadeOut(500, () => {
                                                $html.remove();
                                            });
                                        }, 500);
                                    });

                                $(document).on('mouseover',
                                    '.status_absent',
                                    function(e) {
                                        var remarks = $(this).data('remarks');
                                        var afternoon_out = $(this).data(
                                            'afternoon-out');
                                        var html = `
                                        <div style="position: absolute; background-color: #f5f5f5; padding: 5px; border: 1px solid #ccc; z-index: 1; left: ${e.pageX}px; top: ${e.pageY}px;">
                                            Reason: ${remarks ? remarks : '-'}<br>
                                        </div>
                                    `;
                                        var $html = $(html).appendTo('body');
                                        setTimeout(() => {
                                            $html.fadeOut(500, () => {
                                                $html.remove();
                                            });
                                        }, 500);
                                    });
                            });

                            row += `<td data-id="${emp.id}" class="manual_attendance" style="background-color: white;position: sticky; right: 0; text-decoration: underline; font-style: italic; color: blue;cursor:pointer;" 
                        onclick="$('#manual_attendance_modal').modal('show');">Manual Attendance</td>`;
                            row += `</tr>`;

                            attendanceBody.append(row);
                        });
                    }
                });
            }


            // function getemployees_multiple_date(dateArray) {
            //     $.ajax({
            //         type: "GET",
            //         url: '/hr/employees/attendance/V4fetch_allattendance',
            //         data: {
            //             action: 'getactiveemployees'
            //         },
            //         success: function(response) {
            //             let employees = response.data;
            //             let employee_manual_attendance = response.employee_manual_attendance;
            //             let employee_manual_attendance_remarks = response
            //                 .employee_manual_attendance_remarks;
            //             let employee_workdays = response.employee_workdays;

            //             console.log("Fetched employees: ", employees);
            //             console.log("Fetched employee_manual_attendance: ", employee_manual_attendance);
            //             console.log("Fetched employee_manual_attendance_remarks: ",
            //                 employee_manual_attendance_remarks);
            //             console.log("Fetched employee_workdays: ", employee_workdays);
            //             let attendanceBody = $("#attendanceBody");
            //             attendanceBody.empty();

            //             console.log("Fetched employees: ", employees);

            //             employees.forEach(emp => {
            //                 let row =
            //                     `<tr><td id="employee_name_${emp.id}">${emp.lastname}, ${emp.firstname}</td>`;

            //                 dateArray.forEach(date => {
            //                     let morning_in = '',
            //                         morning_out = '',
            //                         afternoon_in = '',
            //                         afternoon_out = '';
            //                     let attendance_total_hours = 0,
            //                         attendance_total_minutes = 0;
            //                     let remarks = "";

            //                     if (employee_manual_attendance[emp.id] &&
            //                         employee_manual_attendance[emp.id][date]) {
            //                         employee_manual_attendance[emp.id][date].forEach(
            //                             entry => {
            //                                 let time = moment(entry.ttime,
            //                                     'HH:mm:ss');
            //                                 if (entry.tapstate === "IN") {
            //                                     if (entry.timeshift === "AM") {
            //                                         morning_in = time;
            //                                     } else {
            //                                         afternoon_in = time;
            //                                     }
            //                                 } else if (entry.tapstate === "OUT") {
            //                                     if (entry.timeshift === "AM") {
            //                                         morning_out = time;
            //                                     } else {
            //                                         afternoon_out = time;
            //                                     }
            //                                 }
            //                             });
            //                     }

            //                     if (employee_workdays[emp.id]) {
            //                         let workday = employee_workdays[emp.id][0];
            //                         let dayOfWeek = moment(date).format('dddd')
            //                             .toLowerCase();

            //                         if (workday[dayOfWeek] === 1) {
            //                             let morning_start = moment(workday[
            //                                 `${dayOfWeek}_amin`], 'HH:mm:ss');
            //                             let morning_end = moment(workday[
            //                                 `${dayOfWeek}_amout`], 'HH:mm:ss');
            //                             let afternoon_start = moment(workday[
            //                                 `${dayOfWeek}_pmin`], 'HH:mm:ss');
            //                             let afternoon_end = moment(workday[
            //                                 `${dayOfWeek}_pmout`], 'HH:mm:ss');

            //                             if (morning_in && morning_out) {
            //                                 let morning_in_copy = moment(morning_in);
            //                                 let morning_out_copy = moment(morning_out);
            //                                 if (morning_in_copy.isBefore(
            //                                         morning_start)) {
            //                                     morning_in_copy = morning_start;
            //                                 }
            //                                 if (morning_out_copy.isAfter(morning_end)) {
            //                                     morning_out_copy = morning_end;
            //                                 }
            //                                 let morning_duration = moment.duration(
            //                                     morning_out_copy.diff(
            //                                         morning_in_copy));
            //                                 attendance_total_hours += morning_duration
            //                                     .hours();
            //                                 attendance_total_minutes += morning_duration
            //                                     .minutes();
            //                             }

            //                             if (afternoon_in && afternoon_out) {
            //                                 let afternoon_in_copy = moment(
            //                                     afternoon_in);
            //                                 let afternoon_out_copy = moment(
            //                                     afternoon_out);
            //                                 if (afternoon_in_copy.isBefore(
            //                                         afternoon_start)) {
            //                                     afternoon_in_copy = afternoon_start;
            //                                 }
            //                                 if (afternoon_out_copy.isAfter(
            //                                         afternoon_end)) {
            //                                     afternoon_out_copy = afternoon_end;
            //                                 }
            //                                 let afternoon_duration = moment.duration(
            //                                     afternoon_out_copy.diff(
            //                                         afternoon_in_copy));
            //                                 attendance_total_hours += afternoon_duration
            //                                     .hours();
            //                                 attendance_total_minutes +=
            //                                     afternoon_duration.minutes();
            //                             }
            //                         }
            //                     }

            //                     console.log(
            //                         `Employee: ${emp.id}, Date: ${date}, Hours: ${attendance_total_hours}, Minutes: ${attendance_total_minutes}`
            //                     );

            //                     let status = '';
            //                     if (attendance_total_hours === '') {
            //                         status = '';
            //                     } else if (attendance_total_hours === 8) {
            //                         status = 'Present';
            //                     } else if (attendance_total_hours === 0 && remarks ===
            //                         '') {
            //                         status = '---';
            //                     } else if (attendance_total_hours < 8 &&
            //                         attendance_total_hours !==
            //                         0) {
            //                         status = 'Tardy';
            //                     } else if (attendance_total_hours > 8) {
            //                         status = 'Overtime';
            //                     } else if (attendance_total_hours === 0 && remarks !=
            //                         '') {
            //                         status = 'Absent';
            //                     }

            //                     row +=
            //                         `<td class="status" data-id="${emp.id}">${status}</td>`;
            //                 });

            //                 row +=
            //                     `<td style="background-color: white;position: sticky; right: 0; text-decoration: underline; font-style: italic; color: blue;cursor:pointer;" onclick="$('#manual_attendance_modal').modal('show');">Manual Attendance</td>`;
            //                 row += '</tr>';
            //                 attendanceBody.append(row);
            //             });
            //         }
            //     });
            // }






            // work work
            // function getemployees_multiple_date(columnCount) {
            //     $.ajax({
            //         type: "GET",
            //         url: '/hr/employees/attendance/V4fetch',
            //         data: {
            //             action: 'getactiveemployees'
            //         },
            //         success: function(response) {
            //             var employees = response.data;
            //             let attendanceBody = $("#attendanceBody");
            //             attendanceBody.empty();

            //             employees.forEach(emp => {
            //                 let row =
            //                     `<tr> <td data-id="${emp.id}">${emp.lastname}, ${emp.firstname}</td>`;

            //                 // Append empty tds based on dynamic headers count
            //                 for (let i = 0; i < columnCount; i++) {
            //                     row += '<td class="attendance_status">Present</td>';
            //                 }
            //                 row +=
            //                     `<td style="background-color: white;position: sticky; right: 0; text-decoration: underline; font-style: italic; color: blue;cursor:pointer;" onclick="$('#manual_attendance_modal').modal('show');">Manual Attendance</td>`;
            //                 row += '</tr>';
            //                 attendanceBody.append(row);
            //             });
            //         }
            //     });
            // }


            // function getemployees_multiple_date(columnCount, data) {
            //     let attendanceBody = $("#attendanceBody");
            //     attendanceBody.empty();

            //     data.forEach(emp => {
            //         let row =
            //             `<tr> <td data-id="${emp.id}">${emp.lastname}, ${emp.firstname}</td>`;

            //         // Append empty tds based on dynamic headers count
            //         for (let i = 0; i < columnCount; i++) {
            //             row +=
            //             `<td id="status_${emp.id}_${i}" class="status">${emp.status}</td>
        //                     <td id="morning_in_${emp.id}_${i}" class="morning_in">${emp.morning_in ? emp.morning_in.format('hh:mm A') : ''}</td>
        //                     <td id="morning_out_${emp.id}_${i}" class="morning_out">${emp.morning_out ? emp.morning_out.format('hh:mm A') : ''}</td>
        //                     <td id="afternoon_in_${emp.id}_${i}" class="afternoon_in">${emp.afternoon_in ? emp.afternoon_in.format('hh:mm A') : ''}</td>
        //                     <td id="afternoon_out_${emp.id}_${i}" class="afternoon_out">${emp.afternoon_out ? emp.afternoon_out.format('hh:mm A') : ''}</td>
        //                     <td id="total_hours_${emp.id}_${i}" class="total_hours">${emp.attendance_total_hours}</td>
        //                     <td id="total_minutes_${emp.id}_${i}" class="total_minutes">${emp.attendance_total_minutes}</td>
        //                     <td id="remarks_${emp.id}_${i}" class="remarks">${emp.remarks ? emp.remarks : ''}</td>`;
            //         }
            //         row +=
            //             `<td style="background-color: white;position: sticky; right: 0; text-decoration: underline; font-style: italic; color: blue;cursor:pointer;" onclick="$('#manual_attendance_modal').modal('show');">Manual Attendance</td>`;
            //         row += '</tr>';
            //         attendanceBody.append(row);
            //     });
            // }


            $("#search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });


            ////////////////////////////////////////////////////
            $('#reservationnew').daterangepicker({
                opens: 'right',
                autoUpdateInput: true, // Set to true for automatic input update
                locale: {
                    format: 'MM/DD/YYYY'
                }
            }, function(start, end) {
                $('#reservationnew').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
            });

            // Event when the user applies a date range
            $('#reservationnew').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
                    'MM/DD/YYYY'));

                var datefrom = picker.startDate.format('YYYY-MM-DD');
                var dateto = picker.endDate.format('YYYY-MM-DD');

                if (datefrom !== dateto) {
                    generateTableHeaders(datefrom, dateto);
                } else {
                    generateTableHeadersSingleday(datefrom, dateto);
                }
            });

            // Trigger the above condition if reservationnew has value already
            if ($('#reservationnew').val() !== '') {
                var dates = $('#reservationnew').val().split(' - ');
                var datefrom = moment(dates[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
                var dateto = moment(dates[1], 'MM/DD/YYYY').format('YYYY-MM-DD');

                if (datefrom !== dateto) {
                    generateTableHeaders(datefrom, dateto);
                } else {
                    generateTableHeadersSingleday(datefrom, dateto);
                }
            }

            $('.add_row_manual_attendance').on('click', function() {
                let html = `<tr class="manual_attendance_appended_row">
                                <td><input type="date" class="form-control" name="date[]" id="attendance_date"></td>
                                <td><input type="time" class="form-control" name="morning_in[]" id="morning_in_time"></td>
                                <td><input type="time" class="form-control" name="morning_out[]" id="morning_out_time"></td>
                                <td><input type="time" class="form-control" name="afternoon_in[]" id="afternoon_in_time"></td>
                                <td><input type="time" class="form-control" name="afternoon_out[]" id="afternoon_out_time"></td>
                                <td><input type="text" class="form-control" name="remarks[]" id="attendance_remarks" value="---"></td>
                            </tr>`;
                $('#manual_attendance_dynamic_rows').append(html);
            });

            $('#employee_attendance_closeModalBtn').on('click', function() {
                $('.manual_attendance_appended_row').remove();
            });



            $(document).on('click', '.manual_attendance', function() {
                // Get the selected employee's id based on the data-id attribute of the clicked element
                var selected_employee_id = $(this).attr('data-id') || $(this).attr('id');
                // Store the selected employee's id in the hidden input field
                $('#selected_employee_id').val(selected_employee_id);

                // Call the function to fetch the employee's attendance data
                getemployees_single_date();

                // Function to fetch the employee's attendance data
                function getemployees_single_date() {
                    // Send an AJAX request to the server to fetch the employee's attendance data
                    $.ajax({
                        type: "GET",
                        url: '/employee/manual_attendance/fetch',
                        data: {
                            // Pass the selected employee's id as a parameter
                            selected_employee_id: selected_employee_id
                        },
                        success: function(response) {
                            // Get the attendance data from the response
                            var manual_attendance = response.employee_manual_attendance;
                            var manual_attendance_remarks = response
                                .employee_manual_attendance_remarks;

                            // Clear the dynamic rows container
                            $('#manual_attendance_dynamic_rows').empty();

                            // Group attendance by date
                            var attendanceByDate = {};
                            manual_attendance.forEach(function(entry) {
                                // If the date is not already in the attendanceByDate object, add it
                                if (!attendanceByDate[entry.tdate]) {
                                    attendanceByDate[entry.tdate] = {
                                        morning_in: '',
                                        morning_out: '',
                                        afternoon_in: '',
                                        afternoon_out: '',
                                        remarks: ''
                                    };
                                }
                                // If the entry is for the morning and the tapstate is "IN", store the time in the morning_in field
                                if (entry.timeshift === "AM" && entry.tapstate ===
                                    "IN") {
                                    attendanceByDate[entry.tdate].morning_in = entry
                                        .ttime;
                                    // If the entry is for the morning and the tapstate is "OUT", store the time in the morning_out field
                                } else if (entry.timeshift === "AM" && entry
                                    .tapstate === "OUT") {
                                    attendanceByDate[entry.tdate].morning_out = entry
                                        .ttime;
                                    // If the entry is for the afternoon and the tapstate is "IN", store the time in the afternoon_in field
                                } else if (entry.timeshift === "PM" && entry
                                    .tapstate === "IN") {
                                    attendanceByDate[entry.tdate].afternoon_in = entry
                                        .ttime;
                                    // If the entry is for the afternoon and the tapstate is "OUT", store the time in the afternoon_out field
                                } else if (entry.timeshift === "PM" && entry
                                    .tapstate === "OUT") {
                                    attendanceByDate[entry.tdate].afternoon_out = entry
                                        .ttime;
                                }
                            });

                            // Get the remarks for each date
                            manual_attendance_remarks.forEach(function(remarks) {
                                // Store the remarks in the attendanceByDate object
                                attendanceByDate[remarks.tdate].remarks = remarks
                                    .remarks;
                            });

                            // Append rows dynamically
                            Object.keys(attendanceByDate).forEach(function(date, index) {
                                // Get the attendance data for the current date
                                var attendance = attendanceByDate[date];
                                // Generate an id for the row
                                var rowId = 'manual_attendance_rows_' + (index + 1);

                                // Create the row body
                                var manual_attendance_rowBody = `
                                    <tr class="manual_attendance_appended_row" id="${rowId}">
                                        <td><input type="date" class="form-control" name="date[]" value="${date}" id="attendance_date"></td>
                                        <td><input type="time" class="form-control" name="morning_in[]" value="${attendance.morning_in}" id="morning_in_time"></td>
                                        <td><input type="time" class="form-control" name="morning_out[]" value="${attendance.morning_out}" id="morning_out_time"></td>
                                        <td><input type="time" class="form-control" name="afternoon_in[]" value="${attendance.afternoon_in}" id="afternoon_in_time"></td>
                                        <td><input type="time" class="form-control" name="afternoon_out[]" value="${attendance.afternoon_out}" id="afternoon_out_time"></td>
                                        <td><input type="text" class="form-control" name="remarks[]" value="${attendance.remarks}" id="attendance_remarks"></td>
                                    </tr>
                                `;

                                // Append the row to the dynamic rows container
                                $('#manual_attendance_dynamic_rows').append(
                                    manual_attendance_rowBody);
                            });
                        }
                    });
                }
            });




            $('#save_manual_attendance').on('click', function() {

                add_manual_attendance(selected_employee_id)
            });

            function add_manual_attendance() {
                manual_attendance_saves = [];

                $('#manual_attendance_dynamic_rows tr').each(function() {
                    var attendance_date = $(this).find('#attendance_date').val();
                    var morning_in_time = $(this).find('#morning_in_time').val();
                    var morning_out_time = $(this).find('#morning_out_time').val();
                    var afternoon_in_time = $(this).find('#afternoon_in_time').val();
                    var afternoon_out_time = $(this).find('#afternoon_out_time').val();
                    var attendance_remarks = $(this).find('#attendance_remarks').val();

                    var manual_attendance_s = {
                        attendance_date: attendance_date,
                        morning_in_time: morning_in_time,
                        morning_out_time: morning_out_time,
                        afternoon_in_time: afternoon_in_time,
                        afternoon_out_time: afternoon_out_time,
                        attendance_remarks: attendance_remarks
                    };

                    manual_attendance_saves.push(manual_attendance_s);
                });

                var formData = new FormData();

                formData.append('selected_employee_id', $('#selected_employee_id').val());

                manual_attendance_saves.forEach(function(manual_attendance_save, index) {

                    formData.append('manual_attendance_saves[' + index +
                        '][attendance_date]',
                        manual_attendance_save.attendance_date);
                    formData.append('manual_attendance_saves[' + index +
                        '][morning_in_time]',
                        manual_attendance_save.morning_in_time);
                    formData.append('manual_attendance_saves[' + index +
                        '][morning_out_time]',
                        manual_attendance_save.morning_out_time);
                    formData.append('manual_attendance_saves[' + index +
                        '][afternoon_in_time]',
                        manual_attendance_save.afternoon_in_time);
                    formData.append('manual_attendance_saves[' + index +
                        '][afternoon_out_time]',
                        manual_attendance_save.afternoon_out_time);
                    formData.append('manual_attendance_saves[' + index +
                        '][attendance_remarks]',
                        manual_attendance_save.attendance_remarks);

                });

                $.ajax({
                    type: 'POST',
                    url: '/empoloyee/attendance/create',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.state == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data.message
                            });
                        } else if (data.state == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully created'
                            });

                        } else {
                            Toast.fire({
                                type: 'warning',
                                title: 'Please verify your input'
                            });
                        }
                    }
                });

            }

            function select_designations() {
                $.ajax({
                    type: "GET",
                    url: "/payrollclerk/employees/profile/select_designations",
                    success: function(data) {
                        $('#select-designation').empty()
                        $('#select-designation').append('<option value="">Select Designation</option>')
                        $('#select-designation').select2({
                            data: data,
                            allowClear: true,
                            placeholder: 'Select Designation',
                            dropdownCssClass: 'custom-dropdown'
                        });
                    }
                });
            }

            select_designations()

            $('#select-status').select2({
                placeholder: 'Select Attendance Status'
            });


        });
    </script>
@endsection
