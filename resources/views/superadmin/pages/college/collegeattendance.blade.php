@php

    $check_refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->select('refid')->first();

    if (Session::get('currentPortal') == 3) {
        $extend = 'registrar.layouts.app';
    } elseif (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (auth()->user()->type == 10) {
        $extend = 'hr.layouts.app';
    } elseif (Session::get('currentPortal') == 7) {
        $extend = 'studentPortal.layouts.app2';
    } elseif (Session::get('currentPortal') == 9) {
        $extend = 'parentsportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 2) {
        $extend = 'principalsportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 18) {
        $extend = 'ctportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 1) {
        $extend = 'teacher.layouts.app';
    } else {
        if (isset($check_refid->refid)) {
            if ($check_refid->refid == 27) {
                $extend = 'academiccoor.layouts.app2';
            }
        } else {
            $extend = 'general.defaultportal.layouts.app';
        }
    }

@endphp

@extends($extend)

@php
    $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
    $semester = DB::table('semester')->get();
    $levelname = DB::table('college_year')->get();
    $students = DB::table('college_enrolledstud')
        ->selectRaw("concat(studinfo.lastname, ', ', studinfo.firstname, ' ', studinfo.middlename) as name")
        ->join('studinfo', 'college_enrolledstud.studid', '=', 'studinfo.id')
        ->get();

@endphp

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.min.css') }}">

    <style>
        /* select2 */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {

            background-color: #007bff;
            border: 1px solid #007bff;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {

            color: white;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0;
        }

        input[type=search] {
            height: calc(1.7em + 2px) !important;
        }


        /* calendar */
        #calendar td {
            cursor: pointer;
        }


        .attendance-table td .student_name {

            padding-right: 5px;
            padding-top: 2px;
            padding-bottom: 2px;
        }



        .tabledata {

            font-size: 15px;
            padding-top: 2px;
            padding-bottom: 2px;
            max-width: 100px;
            min-width: 100px;
            border: 1px solid rgb(228, 227, 227);
            user-select: none;
        }

        .tablenumber {

            width: 25px;
            font-size: 12px;
            padding-top: 2px;
            padding-bottom: 2px;
            max-width: 25px;
            min-width: 25px;
            border: 1px solid rgb(228, 227, 227);
            user-select: none;
            z-index: 10;
            background: white;
        }


        .attendance-table th {

            font-size: 15px;
            padding-top: 2px;
            padding-bottom: 2px;
            border: 1px solid white;
            background: #383b3d;
            color: white;
        }

        .attendance-table tr {

            background: white;
        }

        .attendance-table tr:hover {

            background: #fff5d4;
        }

        .badge {

            width: 15px;
        }

        .spanholder {
            cursor: pointer;
            transition: all 0.3s;
        }

        .spanholder:hover {
            padding-right: 6px;
        }

        .tooltip {
            position: absolute;
            width: 100px;
            height: 50px;
            transition: 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            opacity: 1;
            transform: scale(0);
        }

        .tooltip-active {
            transform: scale(1);
        }

        .tooltip-holder {
            position: absolute;
            border-radius: 5px;
        }

        .card>.list-group:first-child .list-group-item:first-child {
            border-top-left-radius: 2px;
            border-top-right-radius: 2px;
        }

        i.fas.fa-caret-down {
            width: 100%;
            height: 27px;
            line-height: 2;
        }

        i.fas.fa-caret-down:hover {

            background: #6183a7;
        }

        #calendar {
            max-width: 800px;
            /* Adjust as needed */
            height: 500px;
            /* Adjust as needed */
            margin: 0 auto;
            /* Center the calendar */
        }

        .fc-toolbar {
            font-size: 12px;
        }

        .fc-daygrid-day-number {
            font-size: 10px;
        }

        /* .student-dropdown-arrow {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    font-size: 16px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    padding-left: 8px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    color: black !important;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    background-color: transparent !important; */
        /* cursor: pointer; */
        /* transition: transform 0.3s ease, background-color 0.3s, color 0.3s; */
        /* } */

        /* .student-dropdown-arrow::after {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        content: '\25B6';
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        color: black !important;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        background-color: transparent !important; */
        /* Right-pointing triangle */
        /* display: inline-block;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        transition: transform 0.3s ease;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    }

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    .student-dropdown-arrow.pointing-left::after {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        content: '\25C0';
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        color: black !important;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        background-color: transparent !important; */
        /* Left-pointing triangle */
        /* } */

        .student-dropdown-arrow::after {
            content: '';
            /* padding-left: 1px; */
            display: inline-block;
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid black;
            margin-left: 7px;
            transform: rotate(-90deg);
            /* transition: transform 0.3s ease; */
        }

        .student-dropdown-arrow.pointing-left::after {
            transform: rotate(90deg);
        }


        /* .student-dropdown-arrow.pointing-left::after {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    content: '';
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    display: inline-block;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    width: 0;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    height: 0;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    border-top: 5px solid transparent;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    border-bottom: 5px solid transparent;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    border-left: 5px solid black;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    transition: transform 0.3s ease;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                } */




        .dropdown-menu {
            display: none;
            position: absolute;
            background-color: white;
            border: 1px solid #ccc;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            min-width: 100px;
        }

        .dropdown-item {
            padding: 5px 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .dropdown-item:hover {
            background-color: #f0f0f0;
        }

        .gender-header td {
            font-weight: bold;
            text-align: left !important;
            padding: 10px !important;
        }

        #guideinstructions:hover {
            color: #ffc107;
            transform: scale(1.1);
            transition: all 0.3s ease;
        }

        .modal.right .modal-dialog {
            position: fixed;
            margin: auto;
            width: 320px;
            height: 100%;
            -webkit-transform: translate3d(0%, 0, 0);
            -ms-transform: translate3d(0%, 0, 0);
            -o-transform: translate3d(0%, 0, 0);
            transform: translate3d(0%, 0, 0);
        }

        .modal.right .modal-content {
            height: 100%;
            overflow-y: auto;
        }

        .modal.right .modal-body {
            padding: 15px;
        }

        .modal.right.fade .modal-dialog {
            right: -320px;
            -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
            -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
            -o-transition: opacity 0.3s linear, right 0.3s ease-out;
            transition: opacity 0.3s linear, right 0.3s ease-out;
        }

        .modal.right.fade.show .modal-dialog {
            right: 0;
        }

        .modal-dialog-right {
            position: fixed;
            margin: auto;
            width: 320px;
            height: 100%;
            right: -320px;
        }

        .modal.fade .modal-dialog-right {
            -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
            -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
            -o-transition: opacity 0.3s linear, right 0.3s ease-out;
            transition: opacity 0.3s linear, right 0.3s ease-out;
        }

        .modal.show .modal-dialog-right {
            right: 0;
        }

        .table-responsive {
            overflow: auto;
            max-height: 70vh;
            /* Adjust this value as needed */
        }

        #studentsAttendanceTable {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        #studentsAttendanceTable th,
        #studentsAttendanceTable td {
            padding: 8px;
            border: 1px solid #dee2e6;
            white-space: nowrap;
        }

        #studentsAttendanceTable thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background-color: #6c757d;
            color: white;
        }

        #studentsAttendanceTable th.sticky-col,
        #studentsAttendanceTable td.sticky-col {
            position: sticky;
            background-color: #f8f9fa;
            z-index: 1;
        }

        #studentsAttendanceTable th.first-col,
        #studentsAttendanceTable td.first-col {
            left: 0;
        }

        #studentsAttendanceTable th.second-col,
        #studentsAttendanceTable td.second-col {
            left: 120px;
            /* Adjust based on the width of the first column */
        }

        #studentsAttendanceTable th.third-col,
        #studentsAttendanceTable td.third-col {
            left: 325px;
            /* Adjust based on the width of the first two columns */
        }

        #studentsAttendanceTable thead tr:first-child th {
            border-bottom: none;
        }

        #studentsAttendanceTable thead tr:last-child td {
            border-top: none;
            padding-top: 0;
        }

        .column-dropdown-arrow {
            width: 15px;
            height: 15px;
            /* background-color: #00a6ed; */
            background-color: transparent;
            border: none;
            border-radius: 4px;
            color: black;
            font-size: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            margin: 0 auto;
            margin-top: 10px;
            outline: none !important;
        }

        .column-dropdown-arrows {
            width: 15px;
            height: 15px;
            background-color: #00a6ed;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            padding-right: 13px;
            padding-left: 4px;
        }

        .column-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            min-width: 100px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .column-dropdown-item {
            padding: 5px 10px;
            cursor: pointer;
        }

        .column-dropdown-item:hover {
            background-color: #f0f0f0;
        }
    </style>
@endsection

@section('content')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/eugz.css') }}">
    <link rel='stylesheet' href="{{ asset('plugins/fullcalendar-v5-11-3/main.css') }}" />

    <!-- Export Modal -->
    <div class="modal fade" id="exportmodal" tabindex="-1" aria-labelledby="exportmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportmodalLabel">Export PDF</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span></span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group mb-3" id="exportsy">
                        <div class="export_select">
                            <label for="export_sy">School Year</label>
                            <select id="export_sy" name="export_sy" class=" form-control select2"></select>
                        </div>
                    </div>

                    <div class="form-group mb-3 hidden" id="byStudent">
                        <div class="export_select">
                            <label for="export_student">Student</label>
                            <select id="export_student" name="export_student" class=" form-control select2"></select>
                        </div>
                    </div>

                    <div class="form-group mb-3" id="byMonth">
                        <div class="export_select">
                            <label for="export_month">By Monthy</label>
                            <select id="export_month" name="export_month" class=" form-control select2"></select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success export" id="export">Export</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Export Modal END-->




    <!-- BODY -->

    <div class="pt-3 px-2">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">

                    <div class="card">
                        <div class="card-body p-0">

                            <div style="padding: 12px 12px 20px 12px">

                                <form id="selection_form">

                                    <div class="row">
                                        <div class="col-md-10">
                                            <h4 class="base-rating-title"><i class="fa fa-filter"></i>Filter</h4>
                                        </div>
                                    </div>

                                    <hr style="margin-top: 0.5rem;margin-bottom: 0.5rem;">

                                    <div class="row">
                                        {{-- Filters --}}
                                        <div class="col-md-2 form-group mb-0">
                                            <div class="select_container_sy">
                                                <label for="">School Year</label>
                                                <select class="form-control form-control-sm select2" id="sy">
                                                    @foreach ($sy as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $item->isactive == 1 ? 'selected' : '' }}>
                                                            {{ $item->sydesc }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 form-group mb-0">
                                            <div class="select_container_sem">
                                                <label for="">Semester</label>
                                                <select class="form-control form-control-sm select2" id="semester">
                                                    @foreach ($semester as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $item->isactive == 1 ? 'selected' : '' }}>
                                                            {{ $item->semester }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 form-group mb-0">
                                            <div class="select_container_acadlevel">
                                                <label for="">Year Level</label>
                                                <select class="form-control form-control-sm select2" id="gradelevel">
                                                    <option value="0">All</option>
                                                    @foreach ($levelname as $item)
                                                        <option value="{{ $item->levelid }}">
                                                            {{ $item->yearDesc }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>



                    <div class="card">
                        <div class="card-header bg-gray">
                            <strong>Class Attendance</strong>
                        </div>
                        <div class="card-body">

                            <table id="classattendancetable"
                                class="table table-bordered table-responsive-md table-vcenter text-center no-footer">
                                <thead>
                                    <tr>
                                        <th class="text-center bg-gray">Section</th>
                                        <th class="text-center bg-gray">Subject</th>
                                        <th class="text-center bg-gray">Level</th>
                                        <th class="text-center bg-gray">Time Schedule</th>
                                        <th class="text-center bg-gray">Day</th>
                                        <th class="text-center bg-gray">Room</th>
                                        <th class="text-center bg-gray">Enrolled</th>
                                        <th class="text-center bg-gray">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- class attendance --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- Modal enrolled details -->
    <div id="enrolledmodal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"
            style="max-width:1200px !important;" role="document">
            <div class="modal-content">
                <div class="modal-header bg-gray">
                    <h5 class="modal-title" id="headerenrolled">Enrollment Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    {{-- 
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-2">
                                <h4 class="text-center"><i class="fas fa-user-tie mr-1"></i> Teacher</h4>
                                <p class="text-muted text-center" id="teachersname">Ghana Mota<br><span
                                        style="font-style: italic;" id="teachersemail"> 234345456 </span></p>
                            </div>
                            <div class="col-md-2">
                                <h4 class="text-center"><i class="fas fa-book mr-1"></i> Subject</h4>
                                <p class="text-muted text-center" id="subjectdetails"><br><span id="subjectcodes"></span>
                                </p>
                            </div>
                            <div class="col-md-2">
                                <h4 class="text-center"><i class="fas fa-book mr-1"></i> Level</h4>
                                <p class="text-muted text-center" id="leveldetails"></p>
                            </div>
                            <div class="col-md-2">
                                <h4 class="text-center"><i class="fas fa-book mr-1"></i> Section</h4>
                                <p class="text-muted text-center" id="sectiondetails"></p>
                            </div>
                        </div>
                    </div> --}}


                    <div class="container-fluid headerText p-3">
                        <div class="row py-3">
                            <!-- Teacher -->
                            <div class="col-md-3">
                                <div class="d-flex flex-column text-center">
                                    <span class="font-weight-bold h6"><i class="fas fa-chalkboard-teacher"></i>
                                        Teacher</span>
                                    <span id="teacherName">{{ auth()->user()->name }}</span>
                                    <a class="text-primary" id="teacherID">{{ auth()->user()->email }}</a>
                                </div>
                            </div>
                            <!-- Subject -->
                            <div class="col-md-3">
                                <div class="d-flex flex-column text-center">
                                    <span class="font-weight-bold h6"><i class="fas fa-book"></i> Subject</span>
                                    <span id="subjectdetails"></span>
                                    <a class="text-primary" id="subjectCodes"></a>
                                </div>
                            </div>
                            <!-- Level -->
                            <div class="col-md-3">
                                <div class="d-flex flex-column text-center">
                                    <span class="font-weight-bold h6"><i class="fas fa-graduation-cap"></i> Level</span>
                                    <p id="leveldetails"></p>
                                </div>
                            </div>
                            <!-- Section -->
                            <div class="col-md-3">
                                <div class="d-flex flex-column text-center">
                                    <span class="font-weight-bold h6"><i class="fas fa-building"></i> Section</span>
                                    <p id="sectiondetails"></p>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Card Container -->
                    {{-- <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"><strong>Enrolled Students</strong></h4>
                        </div>
                        <div class="card-body">
                      
                            <div class="d-flex justify-content-end mb-3">
                                <input type="text" id="commonSearchBar" class="form-control w-25"
                                    placeholder="Search for students...">
                            </div>

                           
                            <div id="maleStudentsContainer">
                                <table id="maleStudentsTable"
                                    class="table table-bordered table-responsive-md table-sm table-vcenter text-center no-footer">
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

                                          
                                            <th colspan="7" class="p-2" style="background-color: #8ec9fd;">Male
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <div id="femaleStudentsContainer">
                                    <table id="femaleStudentsTable"
                                        class="table table-bordered table-responsive-md table-sm table-vcenter text-center no-footer">
                                        <thead class="bg-gray">
                                            <tr>
                                                <th colspan="7" class="p-2" style="background-color: #fd8ec9;">
                                                    Female
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <!-- Card Container -->
                    {{-- <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"><strong>Enrolled Students</strong></h4>
                        </div>
                        <div class="card-body"> --}}
                    <!-- Common Search Bar (Right-Aligned) -->
                    <div class="d-flex justify-content-end mb-3">
                        <input type="text" id="commonSearchBar" class="form-control w-25"
                            placeholder="Search for students...">
                    </div>
                    {{-- <div id="enrolledStudentsContainer">
                        <table id="enrolledStudentsTable" class="table table-sm no-footer" style="font-size: smaller;"
                            width="100%">
                            <thead>

                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Academic Level</th>
                                    <th>Course</th>
                                    <th>Contact No.</th>
                                </tr>
                            </thead>

                        </table>
                    </div> --}}

                    <!-- Male Students Table -->
                    <div id="maleStudentsContainer">
                        <table id="maleStudentsTable" class="table table-responsive-md table-sm no-footer"
                            style="font-size: smaller;" width="100%">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Academic Level</th>
                                    <th>Course</th>
                                    <th>Contact No.</th>
                                </tr>

                                <tr>
                                    <th colspan="5" class="p-2" style="background-color: #8ec9fd;">Male</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div id="femaleStudentsContainer">
                        <table id="femaleStudentsTable" class="table table-responsive-md table-sm no-footer"
                            style="font-size: smaller;" width="100%">
                            <thead>
                                <tr style="color: transparent; pointer-events: none;">
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Academic Level</th>
                                    <th>Course</th>
                                    <th>Contact No.</th>

                                </tr>
                                <tr>
                                    <th colspan="5" class="p-2" style="background-color: #fd8ec9;">Female</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <br>


                    {{-- </div>
                    </div> --}}


                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div> --}}
            </div>
        </div>
    </div>

    <!-- Modal attendance details -->
    <div id="attendancemodal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"
            style="max-width: 95% !important;" role="document">
            <div class="modal-content">
                <div class="modal-header bg-gray text-white py-2">
                    <h5 class="modal-title" id="attendanceheaderenrolled"></h5>
                    <button type="button" class="close text-white" id="closeModalBtn" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-2">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Controls Section -->
                                <div class="card shadow-sm mb-2">
                                    <div class="card-body py-2">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                <div class="form-group mb-0">
                                                    <label for="school_year" class="mb-0 small">School Year</label>
                                                    <select id="school_year" name="school_year"
                                                        class="form-control form-control-sm select2">
                                                        @foreach ($sy as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $item->isactive == 1 ? 'selected' : '' }}>
                                                                {{ $item->sydesc }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group mb-0">
                                                    <label for="monthsattendance" class="mb-0 small">Month</label>
                                                    <select id="monthsattendance" name="Months"
                                                        class="form-control form-control-sm select2"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-0">
                                                    <label for="students" class="mb-0 small">Student</label>
                                                    <select id="students" name="Students"
                                                        class="form-control form-control-sm select2"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 mt-4">
                                                <button type="button" class="btn btn-primary btn-sm mr-1 text-white"
                                                    id="calendarbtn">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </button>
                                                <button type="button" class="btn btn-warning btn-sm text-white"
                                                    id="guideinstructions" data-toggle="tooltip" data-placement="top"
                                                    title="View Guide and Instructions">
                                                    <i class="fas fa-question-circle"></i>
                                                </button>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted" id="sched"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Students Table -->
                                <div class="card shadow-sm">
                                    <div class="card-body p-0">

                                        {{-- <div class="col row mt-1 mb-3 justify-content-end sticky-top bg-white">
                                            <button type="button" class="btn btn-info btn-sm mr-2 text-white"
                                                id="attendancebtn_print">
                                                <i class="fas fa-print"></i> Print
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm"
                                                id="savebtnattendanceenrolled">
                                                <i class="fas fa-save"></i> Save Attendance
                                            </button>
                                        </div> --}}

                                        <div class="table-responsive">
                                            <div style="max-height: 20rem; overflow-y: auto;">
                                                <table id="studentsAttendanceTable"
                                                    class="table table-sm table-bordered table-hover mb-0">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th class="sticky-col first-col">Student ID</th>
                                                            <th class="sticky-col second-col">Student Name</th>
                                                            <th class="sticky-col third-col">Level Name</th>
                                                            <!-- Date columns will be added dynamically -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Students will be added here dynamically -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col row mt-1 mb-3 justify-content-end">
                                            <button type="button" class="btn btn-info btn-sm mr-2 text-white"
                                                id="attendancebtn_print">
                                                <i class="fas fa-print"></i> Print
                                            </button>
                                            {{-- <a class="btn btn-sm btn-info " target="_blank"
                                                href="/college/grades/summary/print/pdf?semid=' +
                        temp_semid + '&syid=' + temp_syid + '&studid=' + selected + '&registarid=' + userid +
                        '"><i
                                                    class="fas fa-print"></i> Print</a> --}}


                                            <button type="button" class="btn btn-primary btn-sm ml-1"
                                                id="savebtnattendanceenrolled">
                                                <i class="fas fa-save"></i> Save Attendance
                                            </button>
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

    {{-- guide and instructions --}}
    <div class="modal fade" id="guideModal" tabindex="-1" role="dialog" aria-labelledby="guideModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-right" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><strong>Guide Instructions</strong> <i class="fas fa-question-circle mr-2 text-warning"></i>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-4">
                        <ol class="pl-3 mt-2">
                            <li>
                                <strong> Click</strong> icon above to add date
                                <button type="button" class="btn btn-primary btn-sm" style="cursor: default;">
                                    <i class="fas fa-calendar-alt"></i>
                                </button>
                            </li>
                            <li class="mt-2">
                                <strong> Right</strong> and <strong>Left</strong>
                                click to assign attendance
                                <div class="d-inline-block ml-2">
                                    <button class="btn btn-sm btn-success" style="cursor: default;">Present</button>
                                    <button class="btn btn-sm btn-danger" style="cursor: default;">Absent</button>
                                    <button class="btn btn-sm"
                                        style="background-color: #ffff00; cursor: default;">Late</button>
                                </div>
                            </li>
                            <li class="mt-2">
                                <span class="mr-2" style="color: black;">
                                    <strong> Click</strong> the arrow to assign
                                    attendance for a student across all dates
                                </span>
                                <div class="d-inline-block">
                                    <h2 style="margin-top: -3px;">&#9656;</h2>
                                </div>
                            </li>
                            <li class="mt-2">
                                <span class="mr-2" style="color: black;">
                                    <strong>Click</strong> the dropdown arrow next to a date to apply attendance for all
                                    students on that specific day
                                </span>
                                <div class="d-inline-block">
                                    <h6>&#9660;</h6>
                                </div>
                            </li>
                            <li class="mt-2">
                                <strong> Click</strong> the sort icon to sort the students
                                <i class='fas fa-sort'></i>
                            </li>
                            <li class="mt-2">
                                <strong>Click</strong> save attendance after you assign attendance
                                <button type="button" class="btn btn-primary btn-sm" style="cursor: default;">
                                    <i class="fas fa-save" style="cursor: default;"></i> Save Attendance
                                </button>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Calendar -->
    <div id="calendarModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document"
            style="max-width: 600px !important;">
            <div class="modal-content" id="calendar_add_rem">
                <div class="modal-header bg-gray">
                    <h5 class="modal-title" id="calendarheaderenrolled">Calendar</h5>
                    <input type="hidden" id="counttd">
                    {{-- <button type="button" class="btn btn-primary btn-sm ml-3" id="openModalButton">
                        <i class="fas fa-list"></i> Added Dates
                    </button> --}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            class="text-white" aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-body p-2">
                                    <div id="calendar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info mt-3 mb-0" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong>Click</strong> to select date
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="AbsentModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document"
            style="max-width: 600px !important;">
            <div class="modal-content" id="calendar_add_rem">
                <div class="modal-header bg-gray">
                    <h5 class="modal-title" id="calendarheaderenrolled">Add Absent Remark for Selected Date</h5>
                    <input type="hidden" id="counttd">
                    {{-- <button type="button" class="btn btn-primary btn-sm ml-3" id="openModalButton">
                        <i class="fas fa-list"></i> Added Dates
                    </button> --}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            class="text-white" aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-body p-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-2">
                                                <label for="absent_studid" class="small">Student ID</label>
                                                <input type="text" name="absent_studid" id="absent_studid"
                                                    class="form-control form-control-sm" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-2">
                                                <label for="absent_studname" class="small">Student Name</label>
                                                <input type="text" name="absent_studname" id="absent_studname"
                                                    class="form-control form-control-sm" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="absent_date" class="small">Date</label>
                                        <input type="text" name="absent_date" id="absent_date"
                                            class="form-control form-control-sm" readonly>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="remarks" class="small">Remarks</label>
                                        <textarea name="remarks" id="absent_remarks" class="form-control form-control-sm" rows="3"></textarea>
                                    </div>
                                    <div class="form-group mb-2 text-center">
                                        <button type="button" class="btn btn-primary btn-sm"
                                            id="save_absentRemarks">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="alert alert-info mt-3 mb-0" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong>Click</strong> to select date
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- list of added dates with attendance Modal -->
    {{-- <div class="modal fade" id="addedDatesModal" tabindex="-1" aria-labelledby="addedDatesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addedDatesModalLabel">list of Added Dates</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <!-- list of added dates  -->
                </div>
            </div>
        </div>
    </div>> --}}



    <!-- BODY END-->


    <script src="{{ asset('plugins/fullcalendar-v5-11-3/main.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

    <script>
        var temp_sched;



        $(document).ready(function() {

            populateMonthsDropdown2();

            //store assigned attendance
            $('#savebtnattendanceenrolled').on('click', function() {
                var subjectid = $('#rowsubjectID').val();
                var temp_sched = window.all_sched.filter(x => x.subjectid == subjectid);

                if (temp_sched.length === 0) {
                    console.error('No schedule data found for subject ID:', subjectid);
                    return;
                }

                console.log(temp_sched, 'here');

                // Pass the students' data to the function
                college_attendance_store(temp_sched);
            });

            //open calendar modal
            $('#calendarbtn').on('click', function() {
                $('#calendarModal').modal('show');
            });

            // Open modal and fetch added dates
            // $('#openModalButton').on('click', function() {
            //     fetchAddedDates(); // Fetch and display the added dates
            //     $('#addedDatesModal').modal('show'); // Open the modal
            // });

            // Initialize Select2 for dropdowns
            $('.select2').select2();
            //students filter function
            async function load_students_select2(schedid) {
                try {
                    let studentsData = await fetchStudentList(schedid);
                    console.log('Fetched Student Data:', studentsData);

                    $('#students').empty().append('<option value="">All Students</option>');

                    const updatedStudents = studentsData.students.map(student => ({
                        id: student.sid, // Use 'sid' instead of 'id'
                        text: `${student.lastname}, ${student.firstname} ${student.middlename || ''}`
                            .trim()
                    }));

                    $("#students").select2({
                        data: updatedStudents
                        // off('change')
                    }).on('change', function() {
                        // Show loading indicator when selecting a student
                        // Swal.fire({
                        //     // title: 'Loading',
                        //     text: 'Please wait while we fetch the attendance data...',
                        //     allowOutsideClick: false,
                        //     didOpen: () => {
                        //         Swal.showLoading();
                        //     }
                        // });

                        var selectedStudentId = $(this).val();
                        var subjectId = $('#rowsubjectID').val();
                        var sectionId = $('#section_id').val();
                        var selectedMonth = parseInt($('#monthsattendance')
                            .val()); // Get the selected month

                        console.log('Student change - Selected month:', selectedMonth);

                        // Check if render_attendance_enrolled_students is defined
                        // if (typeof render_attendance_enrolled_students === 'function') {
                        //     render_attendance_enrolled_students(subjectId, selectedStudentId, sectionId,
                        //         schedid, selectedMonth).then(() => {
                        //         // Close loading indicator after 600ms
                        //         setTimeout(() => Swal.close(), 1000);
                        //     }).catch(error => {
                        //         console.error('Error rendering attendance:', error);
                        //         Swal.close();
                        //         Swal.fire({
                        //             title: 'Error!',
                        //             text: 'There was a problem loading attendance data.',
                        //             icon: 'error',
                        //             confirmButtonText: 'OK'
                        //         });
                        //     });
                        // } else {
                        //     console.error('render_attendance_enrolled_students is not a function');
                        //     Swal.close();
                        // }
                    });
                } catch (error) {
                    console.error('Error loading student list:', error);
                }
            }


            $(document).on('click', '#attendancebtn_print', function(event) {
                var type = $(this).attr('export-type');

                let sectionid = $('#section_id').val();
                let semid = $('#semester').val();
                let subjectid = $('#rowsubjectID').val();
                let syid = $('#school_year').val();
                let bymonthArray = $('#monthsattendance').val();


                // var exportCoverage = $('#export_coverage').val();

                // if (type == "pdf") {

                // let array = [];

                // for (let i = 0; i < dataArray.length; i++) {

                //     array.push(dataArray[i].monthid);
                // }

                // let arraymonth = dataArray.filter((element, index) => {
                //     return array.indexOf(element.monthid) === index;
                // });

                // arraymonth = JSON.stringify(arraymonth);

                let arraymonth = JSON.stringify(bymonthArray);

                window.open('/college/attendance/generate-pdf' + '/' + syid + '/' + subjectid + '/' +
                    sectionid + '/' + semid + '/' + arraymonth, '_blank');


                // } else {

                //     let arraymonth = JSON.stringify(bymonthArray);
                //     window.open('/college/attendance/generate-excel' + '/' + syid + '/' + subjectid + '/' +
                //         sectionid + '/' + semid + '/' + arraymonth);
                // }



            });


            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            });

            //students filter
            $('#students').on('change', function() {
                // Swal.fire({
                //     // title: 'Loading',
                //     text: 'Please wait while we fetch the attendance data...',
                //     allowOutsideClick: false,
                //     didOpen: () => {
                //         Swal.showLoading();
                //     }
                // });

                var sid = $(this).val();
                var id = $('#rowsubjectID').val();
                var sectionid = $('#section_id').val();
                var schedid = $('#for_schedid').val();
                var selectedMonth = parseInt($('#monthsattendance').val()); // Get the selected month

                console.log('Student change - Selected month:', selectedMonth);

                render_attendance_enrolled_students(id, sid, sectionid, schedid, selectedMonth)
                    .then(() => {
                        setTimeout(() => Swal.close(), 600);
                    })
                    .catch(error => {
                        console.error('Error rendering attendance:', error);
                        Swal.close();
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was a problem loading attendance data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
            });

            // Fetch sections initially
            fetchSections();

            // Fetch sections on filter change
            $('#sy, #semester, #gradelevel').on('change', function() {
                fetchSections();
            });


            // Event handler for prospectus course details
            $(document).on('click', '.enrolled_link', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                var schedid = $(this).data('schedid');
                $('#btn-print').data('id', id);

                // Fetch student list and render enrolled details
                fetchStudentList(schedid).then(function(studentsData) {
                    console.log('Fetched Students:', studentsData);
                    enrolledDetails(id);
                    render_sections_enrolled_students(id, studentsData);
                }).catch(function(error) {
                    console.error('Error fetching student list:', error);
                });
            });

            //open attendance modal
            $(document).on('click', '.attendance_link', async function(event) {
                event.preventDefault();

                var id = $(this).data('id');
                var sectionid = $(this).data('sectionid');
                var schedid = $(this).data('schedid');
                var studentCount = $(this).data('count-enrolled');

                console.log('Subject ID:', id);
                console.log('Section ID:', sectionid);
                console.log('Schedule ID:', schedid);
                console.log('Student Count:', studentCount);

                var temp_sched = window.all_sched.filter(function(x) {
                    return x.subjectid == id;
                });

                console.log('Filtered Schedule:', temp_sched);

                // Prepare the modal content
                attendancedetails(id, sectionid, studentCount, schedid);
                load_students_select2(schedid);
                populateMonthsDropdown();

                // Show loading indicator
                Swal.fire({
                    // title: 'Loading',
                    text: 'Please wait while we fetch the attendance data...',
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    await Promise.all([
                        new Promise(resolve => setTimeout(resolve,
                            900)), // Minimum 1 second delay
                        new Promise((resolve, reject) => {
                            $.ajax({
                                url: "/collegeattendancefetch/",
                                type: 'GET',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                        .attr('content')
                                },
                                data: {
                                    syid: $('#sy').val(),
                                    semid: $('#semester').val(),
                                    subjectid: id,
                                    sectionid: sectionid,
                                    monthid: new Date().getMonth() + 1,
                                    yearid: new Date().getFullYear()
                                },
                                success: function(attendanceData) {
                                    console.log('Loaded Attendance Data:',
                                        attendanceData);
                                    window.loadedAttendanceData =
                                        attendanceData;
                                    resolve();
                                },
                                error: function(error) {
                                    console.error(
                                        'Error loading attendance data:',
                                        error);
                                    reject(error);
                                }
                            });
                        })
                    ]);

                    // Operations completed successfully
                    $('#rowsubjectID').val(id);
                    loadAttendanceFromLocalStorage();
                    Swal.close();

                    // Open the modal after fetching data
                    $('#attendancemodal').modal('show');

                } catch (error) {
                    // Handle any errors
                    Swal.fire({
                        title: 'Error!',
                        text: 'There was a problem loading attendance data.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });

            $(document).on('click', '.attendance_link2', async function(event) {
                event.preventDefault();

                var id = $(this).data('id');
                var sectionid = $(this).data('sectionid');
                var schedid = $(this).data('schedid');
                var studentCount = $(this).data('count-enrolled');

                console.log('Subject ID:', id);
                console.log('Section ID:', sectionid);
                console.log('Schedule ID:', schedid);
                console.log('Student Count:', studentCount);

                var temp_sched = window.all_sched.filter(function(x) {
                    return x.subjectid == id;
                });

                console.log('Filtered Schedule:', temp_sched);

                // Prepare the modal content
                attendancedetails2(id, sectionid, studentCount, schedid);
                load_students_select2(schedid);
                populateMonthsDropdown2();

                // Show loading indicator
                Swal.fire({
                    // title: 'Loading',
                    text: 'Please wait while we fetch the attendance data...',
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    await Promise.all([
                        new Promise(resolve => setTimeout(resolve,
                            900)), // Minimum 1 second delay
                        new Promise((resolve, reject) => {
                            $.ajax({
                                url: "/collegeattendancefetch/",
                                type: 'GET',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                        .attr('content')
                                },
                                data: {
                                    syid: $('#sy').val(),
                                    semid: $('#semester').val(),
                                    subjectid: id,
                                    sectionid: sectionid,
                                    monthid: new Date().getMonth() + 1,
                                    yearid: new Date().getFullYear()
                                },
                                success: function(attendanceData) {
                                    console.log('Loaded Attendance Data:',
                                        attendanceData);
                                    window.loadedAttendanceData =
                                        attendanceData;
                                    resolve();
                                },
                                error: function(error) {
                                    console.error(
                                        'Error loading attendance data:',
                                        error);
                                    reject(error);
                                }
                            });
                        })
                    ]);

                    // Operations completed successfully
                    $('#rowsubjectID').val(id);
                    loadAttendanceFromLocalStorage();
                    Swal.close();

                    // Open the modal after fetching data
                    $('#attendancemodal').modal('show');

                } catch (error) {
                    // Handle any errors
                    Swal.fire({
                        title: 'Error!',
                        text: 'There was a problem loading attendance data.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });

            //close attendance modal
            $('#closeModalBtn').on('click', function() {
                let selectedMonth = $('#monthsattendance').val(); // Get the selected month
                resetAllAttendance(selectedMonth);
            });

            // Initialize calendar when modal is shown
            // $('#calendarModal').on('shown.bs.modal', function() {
            //     let selectedMonth = $('#monthsattendance').val();
            //     renderCalendar();
            //     // renderCalendar(selectedMonth);
            // });

            $('#attendancemodal').on('hidden.bs.modal', function() {
                $("#studentContainer").empty();
            });


            //print student attendance
            function printAttendance() {
                // Clone the table to avoid modifying the original
                var attendanceTable = $('#studentsAttendanceTable').clone();

                // Remove the click events and dropdown arrows from the cloned table
                attendanceTable.find('span').off(); // Remove event listeners from spans
                attendanceTable.find('.column-dropdown-arrow').parent()
                    .remove(); // Removes the entire <td> that contains the button


                // Get current month and year
                var currentDate = new Date();
                var month = currentDate.toLocaleString('default', {
                    month: 'long'
                });
                var year = currentDate.getFullYear();

                // Get class schedule information
                var scheduleInfo = $('#sched').clone();
                scheduleInfo.find('h5').remove(); // Remove the "Class Schedule:" heading
                scheduleInfo.find('ul').removeClass('list-unstyled').addClass('schedule-list');

                // Function to count attendance
                function countAttendance(table) {
                    var present = 0,
                        absent = 0,
                        late = 0;
                    table.find('td').each(function() {
                        var text = $(this).text().trim();
                        if (text === 'Present') present++;
                        else if (text === 'Absent') absent++;
                        else if (text === 'Late') late++;
                    });
                    return {
                        present,
                        absent,
                        late
                    };
                }

                // Count attendance
                var totalCounts = countAttendance(attendanceTable);

                // Create the content for the new window
                var printContent = `
				<html>
					<head>
						<title> ${month} Attendance Report</title>
						<style>
							@media print {
								body { font-family: Arial, sans-serif; font-size: 12px; }
								table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
								th, td { border: 1px solid black; padding: 6px; text-align: left; font-size: 8px; }
								h1 { text-align: center; margin: 10px 0; font-size: 20px; }
								h2 { text-align: center; margin: 10px 0; font-size: 18px; }
								h3 { color: white !important; padding: 8px; margin: 0; font-size: 16px; -webkit-print-color-adjust: exact; text-align: center; }
								.summary { margin-top: 20px; font-size: 14px; }
								.schedule { margin-bottom: 15px; text-align: left; font-size: 14px; }
								.schedule-list { padding-left: 0; list-style-type: none; }
								.present { background-color: #4CAF50 !important; color: white !important; -webkit-print-color-adjust: exact; }
								.absent { background-color: #F44336 !important; color: white !important; -webkit-print-color-adjust: exact; }
								.late { background-color: #FFEB3B !important; color: black !important; -webkit-print-color-adjust: exact; }
								th { background-color: #808080 !important; color: white !important; -webkit-print-color-adjust: exact; }
								.header-row th { background-color: #383b3d !important; color: white !important; -webkit-print-color-adjust: exact; }
								.student-table { table-layout: fixed; }
								.student-table th, .student-table td { width: 11%; }
								.student-table th:first-child, .student-table td:first-child { width: 15%; }
								.student-table th:nth-child(2), .student-table td:nth-child(2) { width: 25%; }
								.student-table th:nth-child(3), .student-table td:nth-child(3) { width: 20%; }
								.all-students td, .male-students td, .female-students td { 
									background-color: #000000 !important; 
									color: white !important;
									font-weight: bold;
									-webkit-print-color-adjust: exact; 
								}
								.total-present { color: #4CAF50; }
								.total-late { color: #FFEB3B; }
								.total-absent { color: #F44336; }
							}
						</style>
					</head>
					<body>
						<h1>${month} Attendance Report</h1>
						<h2>${$('#attendanceheaderenrolled').text()}</h2>
						<div class="schedule">
							<h5>Class Schedule:</h5>
							${scheduleInfo.html().replace('• Day:', 'Day:')}
						</div>
						${attendanceTable.prop('outerHTML').replace('<table', '<table class="student-table"')}
						<div class="summary">
							<p><strong>Month:</strong> ${month}</p>
							<p><strong>Year:</strong> ${year}</p>
							<p><strong>Total Attendance:</strong></p>
							<p class="total-present">Present: ${totalCounts.present}</p>
							<p class="total-late">Late: ${totalCounts.late}</p>
							<p class="total-absent">Absent: ${totalCounts.absent}</p>
						</div>
					</body>
				</html>
			`;

                // Open a new window and write the content
                var printWindow = window.open('', '_blank');
                printWindow.document.write(printContent);
                printWindow.document.close();

                // Apply styles to the cells based on their state
                printWindow.onload = function() {
                    var table = printWindow.document.getElementsByTagName('table')[0];

                    // Add a class to the first row (header row)
                    table.rows[0].className = 'header-row';

                    var cells = table.getElementsByTagName('td');
                    for (var i = 0; i < cells.length; i++) {
                        var cell = cells[i];
                        if (cell.textContent.trim() === 'Present') {
                            cell.className = 'present';
                        } else if (cell.textContent.trim() === 'Absent') {
                            cell.className = 'absent';
                        } else if (cell.textContent.trim() === 'Late') {
                            cell.className = 'late';
                        }
                    }

                    // Add separator styles
                    var rows = table.rows;
                    for (var i = 0; i < rows.length; i++) {
                        var firstCellText = rows[i].cells[0].textContent.trim();
                        if (firstCellText.includes('All Students')) {
                            rows[i].className = 'all-students';
                        } else if (firstCellText.includes('Male Students')) {
                            rows[i].className = 'male-students';
                        } else if (firstCellText.includes('Female Students')) {
                            rows[i].className = 'female-students';
                        }
                    }

                    printWindow.print();
                    printWindow.close();
                };
            }

            // function printAttendance() {
            //     // Clone the table to avoid modifying the original
            //     var attendanceTable = $('#studentsAttendanceTable').clone();

            //     // Remove the click events and dropdown arrows from the cloned table
            //     attendanceTable.find('span').off(); // Remove event listeners from spans
            //     attendanceTable.find('.column-dropdown-arrow').parent()
            //         .remove(); // Removes the entire <td> that contains the button


            //     // Get current month and year
            //     var currentDate = new Date();
            //     var month = currentDate.toLocaleString('default', {
            //         month: 'long'
            //     });
            //     var year = currentDate.getFullYear();

            //     // Get class schedule information
            //     var scheduleInfo = $('#sched').clone();
            //     scheduleInfo.find('h5').remove(); // Remove the "Class Schedule:" heading
            //     scheduleInfo.find('ul').removeClass('list-unstyled').addClass('schedule-list');

            //     // Function to count attendance
            //     function countAttendance(table) {
            //         var present = 0,
            //             absent = 0,
            //             late = 0;
            //         table.find('td').each(function() {
            //             var text = $(this).text().trim();
            //             if (text === 'Present') present++;
            //             else if (text === 'Absent') absent++;
            //             else if (text === 'Late') late++;
            //         });
            //         return {
            //             present,
            //             absent,
            //             late
            //         };
            //     }

            //     // Count attendance
            //     var totalCounts = countAttendance(attendanceTable);

            //     // Create the content for the new window
            //     var printContent = `
        // 	<html>
        // 		<head>
        // 			<title>Attendance Report</title>
        // 			<style>
        // 				@media print {
        // 					body { font-family: Arial, sans-serif; font-size: 12px; }
        // 					table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        // 					th, td { border: 1px solid black; padding: 6px; text-align: left; font-size: 8px; }
        // 					h1 { text-align: center; margin: 10px 0; font-size: 20px; }
        // 					h2 { text-align: center; margin: 10px 0; font-size: 18px; }
        // 					h3 { color: white !important; padding: 8px; margin: 0; font-size: 16px; -webkit-print-color-adjust: exact; text-align: center; }
        // 					.summary { margin-top: 20px; font-size: 14px; }
        // 					.schedule { margin-bottom: 15px; text-align: left; font-size: 14px; }
        // 					.schedule-list { padding-left: 0; list-style-type: none; }
        // 					.present { background-color: #4CAF50 !important; color: white !important; -webkit-print-color-adjust: exact; }
        // 					.absent { background-color: #F44336 !important; color: white !important; -webkit-print-color-adjust: exact; }
        // 					.late { background-color: #FFEB3B !important; color: black !important; -webkit-print-color-adjust: exact; }
        // 					th { background-color: #808080 !important; color: white !important; -webkit-print-color-adjust: exact; }
        // 					.header-row th { background-color: #383b3d !important; color: white !important; -webkit-print-color-adjust: exact; }
        // 					.student-table { table-layout: fixed; }
        // 					.student-table th, .student-table td { width: 11%; }
        // 					.student-table th:first-child, .student-table td:first-child { width: 15%; }
        // 					.student-table th:nth-child(2), .student-table td:nth-child(2) { width: 25%; }
        // 					.student-table th:nth-child(3), .student-table td:nth-child(3) { width: 20%; }
        // 					.all-students td, .male-students td, .female-students td { 
        // 						background-color: #000000 !important; 
        // 						color: white !important;
        // 						font-weight: bold;
        // 						-webkit-print-color-adjust: exact; 
        // 					}
        // 					.total-present { color: #4CAF50; }
        // 					.total-late { color: #FFEB3B; }
        // 					.total-absent { color: #F44336; }
        // 				}
        // 			</style>
        // 		</head>
        // 		<body>
        // 			<h1>Attendance Report</h1>
        // 			<h2>${$('#attendanceheaderenrolled').text()}</h2>
        // 			<div class="schedule">
        // 				<h5>Class Schedule:</h5>
        // 				${scheduleInfo.html().replace('• Day:', 'Day:')}
        // 			</div>
        // 			${attendanceTable.prop('outerHTML').replace('<table', '<table class="student-table"')}
        // 			<div class="summary">
        // 				<p><strong>Month:</strong> ${month}</p>
        // 				<p><strong>Year:</strong> ${year}</p>
        // 				<p><strong>Total Attendance:</strong></p>
        // 				<p class="total-present">Present: ${totalCounts.present}</p>
        // 				<p class="total-late">Late: ${totalCounts.late}</p>
        // 				<p class="total-absent">Absent: ${totalCounts.absent}</p>
        // 			</div>
        // 		</body>
        // 	</html>
        // `;

            //     // Open a new window and write the content
            //     var printWindow = window.open('', '_blank');
            //     printWindow.document.write(printContent);
            //     printWindow.document.close();

            //     // Apply styles to the cells based on their state
            //     printWindow.onload = function() {
            //         var table = printWindow.document.getElementsByTagName('table')[0];

            //         // Add a class to the first row (header row)
            //         table.rows[0].className = 'header-row';

            //         var cells = table.getElementsByTagName('td');
            //         for (var i = 0; i < cells.length; i++) {
            //             var cell = cells[i];
            //             if (cell.textContent.trim() === 'Present') {
            //                 cell.className = 'present';
            //             } else if (cell.textContent.trim() === 'Absent') {
            //                 cell.className = 'absent';
            //             } else if (cell.textContent.trim() === 'Late') {
            //                 cell.className = 'late';
            //             }
            //         }

            //         // Add separator styles
            //         var rows = table.rows;
            //         for (var i = 0; i < rows.length; i++) {
            //             var firstCellText = rows[i].cells[0].textContent.trim();
            //             if (firstCellText.includes('All Students')) {
            //                 rows[i].className = 'all-students';
            //             } else if (firstCellText.includes('Male Students')) {
            //                 rows[i].className = 'male-students';
            //             } else if (firstCellText.includes('Female Students')) {
            //                 rows[i].className = 'female-students';
            //             }
            //         }

            //         printWindow.print();
            //         printWindow.close();
            //     };
            // }


            // Add click event listener for the print button
            // $('#attendancebtn_print').on('click', function() {
            //     printAttendance();
            // });

            //fecth sections data
            function fetchSections() {
                let syid = $('#sy, #school_year').val();
                let semid = $('#semester').val();
                let levelid = $('#gradelevel').val(); // Get the gradelevel filter

                $.ajax({
                    type: 'GET',
                    url: '/college/teacher/schedule/get',
                    data: {
                        syid: syid,
                        semid: semid,
                        gradelevel: levelid, // Pass the gradelevel filter
                    },
                    success: function(data) {
                        console.log('Fetched schedule data:', data); // Debugging
                        window.all_sched = data; // Store the fetched data in a global variable
                        renderSections(); // Call renderSections to update the DataTable
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching schedule:', error);
                    }
                });
            }

            //First Table 
            async function renderSections() {
                let schedules = window.all_sched || [];

                if (schedules.length === 0) {
                    $('#classattendancetable tbody').html('<tr><td colspan="8">No data available</td></tr>');
                    return;
                }

                let tbodyHtml = schedules.map(async (schedule) => {
                    let {
                        studentCount
                    } = await fetchStudentList(schedule.schedid);
                    let totalEnrolled = schedule.total_enrolled ?? 0;
                    let conflictCheck = await checkScheduleConflict(schedule);

                    return `
                        <tr>
                            <td style="font-size: 12px;" class="text-left pl-3">${schedule.sectionDesc}</td>
                            <td style="font-size: 12px;" class="text-left pl-3">${schedule.subjDesc}</td>
                            <td style="font-size: 12px;" class="text-left pl-3">${schedule.yearDesc}</td>
                            <td style="font-size: 12px;" class="text-left pl-3">${conflictCheck}</td>
                            <td style="font-size: 12px;" class="text-left pl-3">${schedule.days || 'N/A'}</td>
                            <td style="font-size: 12px;" class="text-left pl-3">${schedule.roomname || 'N/A'}</td>
                            <td>${studentCount > 0
                                ? `<a href="#" class="enrolled_link" style="font-size: 20px; text-align: center; font-weight:bold;color: #007BFF; font-style: underline;" data-schedid="${schedule.schedid}" data-id="${schedule.subjectid}" ><strong>${studentCount}</strong></a>`
                                : `<span>${studentCount}</span>`
                            }</td>
                            <td style="font-size: 15px;">
                                <a href="#" class="attendance_link" 
                                    data-id="${schedule.subjectid}" 
                                    data-sectionid="${schedule.sectionid}" 
                                    data-schedid="${schedule.schedid}"
                                    data-count-enrolled="${studentCount || 0}" 
                                    style="text-decoration: underline; color: #007BFF;">
                                    <strong>Attendance</strong>
                                </a>

                                  <a href="#" class="attendance_link2" 
                                    data-id="${schedule.subjectid}" 
                                    data-sectionid="${schedule.sectionid}" 
                                    data-schedid="${schedule.schedid}"
                                    data-count-enrolled="${studentCount || 0}" 
                                    style="display: none;">
                                    <strong>Attendance</strong>
                                </a>
                            </td>
                        </tr>
                    `;
                });

                $('#classattendancetable tbody').html(await Promise.all(tbodyHtml));

                $('#classattendancetable').DataTable({
                    "destroy": true,
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true
                });
            }

            async function checkScheduleConflict(schedule) {
                let conflictData = await $.ajax({
                    url: '/college/teacher/schedule/conflict',
                    method: 'GET',
                    data: {
                        syid: $('#sy').val(),
                        semid: $('#semester').val(),
                        room: schedule.roomname,
                        schedid: schedule.schedid,
                        time: schedule.schedtime,
                        day: schedule.days.split('/').map(day => {
                            switch (day.trim().toLowerCase()) {
                                case 'mon':
                                    return '1';
                                case 'tue':
                                    return '2';
                                case 'wed':
                                    return '3';
                                case 'thu':
                                    return '4';
                                case 'fri':
                                    return '5';
                                case 'sat':
                                    return '6';
                                case 'sun':
                                    return '7';
                                default:
                                    return '';
                            }
                        }).filter(Boolean)
                    }
                });

                let conflictIcon = conflictData.length > 0 ?
                    '<i class="fa fa-exclamation-triangle text-warning" data-toggle="tooltip" title="Conflict Schedule"></i>' :
                    '';

                // $('body').tooltip({
                //     selector: '[data-toggle="tooltip"]'
                // });

                return `
                    <div class="text-danger"><i>${schedule.schedotherclass || 'Whole Semester'}</i></div>
                    <div class="d-flex">${schedule.schedtime || 'N/A'} <span style="margin-top: 2px;padding-right: 9px;">${conflictIcon}</span></div>
                    
                `;
            }
            // <div>${conflictIcon}</div>

            //fetch all students enrolled
            function fetchStudentList(schedid) {
                var syid = $('#sy').val();
                var semid = $('#semester').val();

                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: `/college/teacher/student-list-for-all/${syid}/${semid}/${schedid}`,
                        type: 'GET',
                        success: function(data) {
                            window.all_students = data;
                            console.log('Student List:', data);
                            resolve(data);
                        },
                        error: function(error) {
                            console.error('Error fetching student list:', error);
                            reject(error);
                        }
                    });
                });
            }

            // Function to show enrolled student details in a modal
            function enrolledDetails(id) {
                console.log('ID:', id);
                render_sections_enrolled_students(id);

                // Find the schedule based on the subject ID
                var sched = window.all_sched.find(x => x.subjectid == id);

                console.log('sched', sched);

                if (sched) {
                    // Populate modal content with schedule details
                    $('#headerenrolled').html(
                        `<h5 class="modal-title">${sched.subjCode} - ${sched.sectionDesc}</h5>`);
                    $('#teachersname').html(
                        `<p class="text-muted text-center">${sched.teacher_lastname}, ${sched.teacher_firstname} ${sched.teacher_middlename || ''}<br>${sched.teacher_email}</p>`
                    );
                    $('#subjectdetails').html(
                        `<p class="text-muted text-center">${sched.subjDesc}<br>${sched.subjCode}</p>`);
                    $('#leveldetails').html(`<p class="text-muted text-center">${sched.yearDesc}</p>`);
                    $('#sectiondetails').html(`<p class="text-muted text-center">${sched.sectionDesc}</p>`);

                    // Show the modal
                    $('#enrolledmodal').modal('show');
                } else {
                    console.error('Schedule not found for ID:', id);
                }
            }

            //working code
            //students header table attendance
            function attendancedetails(id, sectionid, studentCount, schedid) {
                var sid = '';

                // Clear previous data
                $("#studentsAttendanceTable tbody").empty();

                var sched = window.all_sched.find(x => x.subjectid == id);

                console.log('Schedule Details:', sched);

                if (sched) {
                    if (studentCount === 0) {
                        Swal.fire({
                            icon: 'info',
                            title: 'No Students Enrolled',
                            text: 'There are no students enrolled in this section.',
                        });
                        return;
                    }

                    $('#attendanceheaderenrolled').html(`
                        <h5 class="modal-title">${sched.subjCode} - ${sched.sectionDesc}</h5>
                        <input type="hidden" id="rowsubjectID" value="${id}">
                        <input type="hidden" id="section_id" value="${sectionid}">
                        <input type="hidden" id="for_schedid" value="${schedid}">
                    `);

                    var sched_data = [{
                        day: sched.days || "N/A",
                        time: sched.schedtime || `${sched.stime} - ${sched.etime}` || "N/A",
                        room: sched.roomname || "N/A",
                        otherClass: sched.schedotherclass || "N/A"
                    }];

                    renderSchedule(sched_data);
                    var currentMonth = new Date().getMonth() + 1; // January is 0, so we add 1
                    // Call render_attendance_enrolled_students with pre-loaded data
                    render_attendance_enrolled_students(id, sid, sectionid, schedid, currentMonth, window
                        .loadedAttendanceData);
                    loadAttendanceFromLocalStorage();

                    $('#attendancemodal').modal('show');
                } else {
                    console.error('Schedule not found for ID:', id);
                    $('#sched').html('<p>Schedule details not available.</p>');
                    $('#attendancemodal').modal('show');
                }
            }

            function attendancedetails2(id, sectionid, studentCount, schedid) {
                var sid = '';

                // Clear previous data
                $("#studentsAttendanceTable tbody").empty();

                var sched = window.all_sched.find(x => x.subjectid == id);

                console.log('Schedule Details:', sched);

                if (sched) {
                    if (studentCount === 0) {
                        Swal.fire({
                            icon: 'info',
                            title: 'No Students Enrolled',
                            text: 'There are no students enrolled in this section.',
                        });
                        return;
                    }

                    $('#attendanceheaderenrolled').html(`
					<h5 class="modal-title">${sched.subjCode} - ${sched.sectionDesc}</h5>
					<input type="hidden" id="rowsubjectID" value="${id}">
					<input type="hidden" id="section_id" value="${sectionid}">
					<input type="hidden" id="for_schedid" value="${schedid}">
				`);

                    var sched_data = [{
                        day: sched.days || "N/A",
                        time: sched.schedtime || `${sched.stime} - ${sched.etime}` || "N/A",
                        room: sched.roomname || "N/A",
                        otherClass: sched.schedotherclass || "N/A"
                    }];

                    renderSchedule(sched_data);
                    var selectedMonth = parseInt($('#monthsattendance').val()); // Get the selected month
                    // Call render_attendance_enrolled_students with pre-loaded data
                    render_attendance_enrolled_students(id, sid, sectionid, schedid, selectedMonth, window
                        .loadedAttendanceData);
                    loadAttendanceFromLocalStorage();

                    $('#attendancemodal').modal('show');
                } else {
                    console.error('Schedule not found for ID:', id);
                    $('#sched').html('<p>Schedule details not available.</p>');
                    $('#attendancemodal').modal('show');
                }
            }

            //students class schedule
            function renderSchedule(sched_data) {
                var scheduleHtml = `<div class="card shadow bg-light mb-2">
									<div class="card-body py-2">
										<h5 class="card-title mb-1"><strong>Class Schedule:</strong></h5>`;

                if (sched_data.length > 0) {
                    sched_data.forEach(item => {
                        scheduleHtml += `
						<p class="card-text mb-0 small">
							<span><strong>Day:</strong> ${item.day} | </span>
							<span><strong>Time:</strong> ${item.time} | </span>
							<span><strong>Room:</strong> ${item.room} | </span>
							<span><strong>Class Type:</strong> ${item.otherClass}</span>
						</p>`;
                    });
                } else {
                    scheduleHtml += `<p class="card-text mb-0 small">No schedule available.</p>`;
                }

                scheduleHtml += `</div></div>`;

                $('#sched').html(scheduleHtml);
            }

            // Add these event listeners outside of any function, preferably in a document.ready block
            $(document).ready(function() {
                // Add click event to the icon
                $(document).on('click', '#guideinstructions', function() {
                    $('#guideModal').modal('show');
                });

                // Close modal when clicking close button
                $('#guideModal .close').on('click', function() {
                    $('#guideModal').modal('hide');
                });

                // Close modal when clicking outside
                $('#guideModal').on('click', function(e) {
                    if ($(e.target).hasClass('modal')) {
                        $('#guideModal').modal('hide');
                    }
                });
            });

            //enrolled students
            function render_sections_enrolled_students(id, studentsData) {
                console.log('Received students data:', studentsData);

                if (!studentsData || !studentsData.students || studentsData.students.length === 0) {
                    console.error('No student data found for subject ID:', id);
                    return;
                }

                // Map the student data to a new array with the required fields
                var students_data = studentsData.students.map(function(student) {
                    return {
                        sid: student.sid,
                        fullname: `${student.lastname}, ${student.firstname} ${student.middlename || ''}`
                            .trim(),
                        levelname: student.levelname,
                        courseabrv: student.courseabrv,
                        courseDesc: student.courseDesc,
                        contactno: student.contactno || 'N/A',
                        semail: student.semail || 'N/A',
                        address: [
                            student.street,
                            student.barangay,
                            student.city,
                            student.province
                        ].filter(Boolean).join(', '),
                        gender: student.gender
                    };
                });

                console.log('Processed students_data:', students_data);

                // Separate male and female students
                var male_students = students_data.filter(student => student.gender === 'MALE');
                var female_students = students_data.filter(student => student.gender === 'FEMALE');

                // Common DataTable options to remove the default search box
                var dataTableOptions = {
                    destroy: true,
                    responsive: true,
                    autoWidth: false,
                    paging: false,
                    searching: true, // Keep searching enabled, but hide the search box
                    info: false,
                    dom: 't' // Only show the table (no search box, no pagination, etc.)
                };


                // Initialize DataTable for male students
                var maleTable = $('#maleStudentsTable').DataTable({
                    ...dataTableOptions,
                    data: male_students,
                    columns: [{
                            data: "sid"
                        },
                        {
                            data: "fullname"
                        },
                        {
                            data: "levelname"
                        },
                        {
                            data: "courseabrv"
                        },
                        {
                            data: "contactno"
                        },
                        // {
                        //     data: "semail"
                        // },
                        // {
                        //     data: "address"
                        // }
                    ]
                });

                // Initialize DataTable for female students
                var femaleTable = $('#femaleStudentsTable').DataTable({
                    ...dataTableOptions,
                    data: female_students,
                    columns: [{
                            data: "sid"
                        },
                        {
                            data: "fullname"
                        },
                        {
                            data: "levelname"
                        },
                        {
                            data: "courseabrv"
                        },
                        {
                            data: "contactno"
                        },
                        // {
                        //     data: "semail"
                        // },
                        // {
                        //     data: "address"
                        // }
                    ]
                });

                // Common search functionality for both tables
                $('#commonSearchBar').on('keyup', function() {
                    var searchTerm = this.value;

                    // Filter the male students table
                    maleTable.search(searchTerm).draw();

                    // Filter the female students table
                    femaleTable.search(searchTerm).draw();
                });

                // Update the student count display
                $('#studentCount').text(studentsData.studentCount);
            }

            /**
             * Returns an array of dates for a given day of the week in the current month.
             *
             * @param {number} dayOfWeek - The day of the week (0=Sunday, 1=Monday, etc.).
             * @return {Date[]} An array of Date objects representing the dates for the given day of the week in the current month.
             */
            //get all days
            function getAllDatesForDay(dayOfWeek, month, year) {
                var dates = [];
                var date = new Date(year, month - 1, 1);

                while (date.getMonth() === month - 1) {
                    if (date.getDay() === dayOfWeek) {
                        dates.push(new Date(date));
                    }
                    date.setDate(date.getDate() + 1);
                }

                return dates;
            }

            //get all dates 
            function getAllDates(daysOfWeek, month, year) {
                let dates = [];
                daysOfWeek.forEach(dayOfWeek => {
                    dates = dates.concat(getAllDatesForDay(dayOfWeek, month, year));
                });

                // Get calendar-added dates
                const syid = $('#sy').val();
                const semid = $('#semester').val();
                const subjectID = $('#rowsubjectID').val();
                const sectionID = $('#section_id').val();
                const localStorageKey = `calendarAddedDates_${syid}_${semid}_${subjectID}_${sectionID}`;
                const calendarAddedDates = JSON.parse(localStorage.getItem(localStorageKey) || '[]');

                // Filter calendar-added dates to only include those from the selected month and year
                const filteredCalendarDates = calendarAddedDates
                    .map(dateString => new Date(dateString))
                    .filter(date => date.getMonth() + 1 === month && date.getFullYear() === year);

                // Merge and sort all dates
                dates = [...new Set([...dates, ...filteredCalendarDates])];
                dates.sort((a, b) => a - b);

                return dates;
            }

            let isAllStudentsView = false; // Move this outside to maintain state

            //add dates to header base on day
            function addDatesToHeader(allDates, id, sid, sectionid, schedid, month) {
                var tableHead = $("#studentsAttendanceTable thead").css('position', 'relative').css('top', '0').css(
                    'background-color', 'white');
                tableHead.empty();

                var headerRow = $("<tr>");
                var iconRow = $("<tr>");

                headerRow.append($("<th>").text("Student ID").attr("style", "width: 10% !important; "));
                headerRow.append($("<th>").html(
                    "Student Name <i class='fas fa-sort' id='sortStudentName' style='cursor: pointer;'></i>"
                ).attr("style", "width: 20% !important;"));
                headerRow.append($("<th>").text("Level Name").attr("style", "width: 15% !important;"));
                iconRow.append($("<td>").attr("colspan", "3").css('background-color', 'white'));

                //     allDates.forEach(date => {
                //     var options = {
                //         month: 'long',
                //         day: 'numeric'
                //     };
                //     var formattedDate = date.toLocaleDateString('en-PH', options);

                //     var dateHeader = $('<th>', {
                //         'class': 'text-center date',
                //         'style': 'position: sticky;',
                //         'data-date': formattedDate
                //     }).text(formattedDate).append(
                //         $('<button>', {
                //             'class': 'column-dropdown-arrow',
                //             'data-date': formattedDate
                //         }).html('&#9660;')
                //     );

                //     headerRow.append(dateHeader);
                // });


                allDates.forEach(date => {
                    var options = {
                        month: 'long',
                        day: 'numeric'
                    };
                    var formattedDate = date.toLocaleDateString('en-PH', options);
                    // var dateString = date.toISOString().split('T')[0];

                    var dateHeader = $('<th>', {
                        'class': 'text-center date',
                        'style': 'position: sticky;',
                        'data-date': formattedDate
                    }).text(formattedDate);

                    headerRow.append(dateHeader);

                    var iconCell = $('<td>', {
                        'class': 'text-center date',
                        'style': 'position: relative;',
                        'data-date': formattedDate
                    }).append(
                        $('<button>', {
                            'class': 'column-dropdown-arrow',
                            'data-date': formattedDate
                        }).html('&#9660;')
                    );

                    iconRow.append(iconCell);
                });

                tableHead.append(headerRow);
                tableHead.append(iconRow);

                // Add click event to sort icon
                $('#sortStudentName').off('click').on('click',
                    function() { // Use .off() to prevent multiple bindings
                        // Show loading indicator
                        Swal.fire({
                            // title: 'Loading...',
                            html: 'Please wait while we fetch the attendance data...',
                            allowOutsideClick: false,
                            onBeforeOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Wrap all asynchronous operations in a Promise
                        Promise.all([
                            new Promise(resolve => setTimeout(resolve, 900)), // Minimum 1 second delay
                            new Promise((resolve, reject) => {
                                if (isAllStudentsView) {
                                    // Switch back to enrolled students view
                                    render_attendance_enrolled_students(id, sid, sectionid, schedid,
                                        month);
                                } else {
                                    // Switch to all students view
                                    render_attendance_all_students(id, sid, sectionid, schedid,
                                        month);
                                }
                                // Resolve the promise after rendering
                                resolve();
                            })
                        ]).then(() => {
                            Swal.close(); // Close the loading indicator
                            // Toggle the view state
                            isAllStudentsView = !isAllStudentsView;
                        }).catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'There was a problem loading the data.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                    });
            }

            //working code
            // function addDatesToHeader(allDates, id, sid, sectionid, schedid, month) {
            //     var tableHead = $("#studentsAttendanceTable thead").css('position', 'relative').css('top', '0').css(
            //         'background-color', 'white');
            //     tableHead.empty();

            //     var headerRow = $("<tr>");
            //     var iconRow = $("<tr>");

            //     headerRow.append($("<th>").text("Student ID").attr("style", "width: 10% !important; "));
            //     headerRow.append($("<th>").html(
            //         "Student Name <i class='fas fa-sort' id='sortStudentName' style='cursor: pointer;'></i>"
            //     ).attr("style", "width: 20% !important;"));
            //     headerRow.append($("<th>").text("Level Name").attr("style", "width: 15% !important;"));
            //     iconRow.append($("<td>").attr("colspan", "3").css('background-color', 'white'));

            //     allDates.forEach(date => {
            //         var options = {
            //             month: 'long',
            //             day: 'numeric'
            //         };
            //         var formattedDate = date.toLocaleDateString('en-PH', options);
            //         // var dateString = date.toISOString().split('T')[0];

            //         var dateHeader = $('<th>', {
            //             'class': 'text-center date',
            //             'style': 'position: sticky;',
            //             'data-date': formattedDate
            //         }).text(formattedDate);

            //         headerRow.append(dateHeader);

            //         var iconCell = $('<td>', {
            //             'class': 'text-center date',
            //             'style': 'position: relative;',
            //             'data-date': formattedDate
            //         }).append(
            //             $('<button>', {
            //                 'class': 'column-dropdown-arrow',
            //                 'data-date': formattedDate
            //             }).html('&#9660;')
            //         );

            //         iconRow.append(iconCell);
            //     });

            //     tableHead.append(headerRow);
            //     tableHead.append(iconRow);

            //     // Add click event to sort icon
            //     $('#sortStudentName').off('click').on('click',
            //         function() { // Use .off() to prevent multiple bindings
            //             // Show loading indicator
            //             Swal.fire({
            //                 // title: 'Loading...',
            //                 html: 'Please wait while we fetch the attendance data...',
            //                 allowOutsideClick: false,
            //                 onBeforeOpen: () => {
            //                     Swal.showLoading();
            //                 }
            //             });

            //             // Wrap all asynchronous operations in a Promise
            //             Promise.all([
            //                 new Promise(resolve => setTimeout(resolve, 900)), // Minimum 1 second delay
            //                 new Promise((resolve, reject) => {
            //                     if (isAllStudentsView) {
            //                         // Switch back to enrolled students view
            //                         render_attendance_enrolled_students(id, sid, sectionid, schedid,
            //                             month);
            //                     } else {
            //                         // Switch to all students view
            //                         render_attendance_all_students(id, sid, sectionid, schedid,
            //                             month);
            //                     }
            //                     // Resolve the promise after rendering
            //                     resolve();
            //                 })
            //             ]).then(() => {
            //                 Swal.close(); // Close the loading indicator
            //                 // Toggle the view state
            //                 isAllStudentsView = !isAllStudentsView;
            //             }).catch(error => {
            //                 Swal.fire({
            //                     title: 'Error!',
            //                     text: 'There was a problem loading the data.',
            //                     icon: 'error',
            //                     confirmButtonText: 'OK'
            //                 });
            //             });
            //         });
            // }
            // function addDatesToHeader(allDates, id, sid, sectionid, schedid, month) {
            //     var tableHead = $("#studentsAttendanceTable thead");
            //     tableHead.empty();

            //     var headerRow = $("<tr>");
            //     var iconRow = $("<tr>");

            //     headerRow.append($("<th>").text("Student ID").attr("style", "width: 10% !important; "));
            //     headerRow.append($("<th>").html(
            //         "Student Name <i class='fas fa-sort' id='sortStudentName' style='cursor: pointer;'></i>"
            //     ).attr("style", "width: 20% !important;"));
            //     headerRow.append($("<th>").text("Level Name").attr("style", "width: 15% !important;"));

            //     iconRow.append($("<td>").attr("colspan", "3"));

            //     allDates.forEach(date => {
            //         var options = {
            //             month: 'long',
            //             day: 'numeric'
            //         };
            //         var formattedDate = date.toLocaleDateString('en-PH', options);
            //         var dateString = date.toISOString().split('T')[0];

            //         var dateHeader = $('<th>', {
            //             'class': 'text-center date',
            //             'style': 'position: relative;',
            //             'data-date': dateString
            //         }).text(formattedDate);

            //         headerRow.append(dateHeader);

            //         var iconCell = $('<td>', {
            //             'class': 'text-center',
            //             'style': 'position: relative;'
            //         }).append(
            //             $('<button>', {
            //                 'class': 'column-dropdown-arrow',
            //                 'data-date': dateString
            //             }).html('&#9660;')
            //         );

            //         iconRow.append(iconCell);
            //     });

            //     tableHead.append(headerRow);
            //     tableHead.append(iconRow);

            //     // Add click event to sort icon
            //     $('#sortStudentName').off('click').on('click',
            //         function() { // Use .off() to prevent multiple bindings
            //             // Show loading indicator
            //             Swal.fire({
            //                 title: 'Loading...',
            //                 html: 'Please wait while we fetch the attendance data...',
            //                 allowOutsideClick: false,
            //                 onBeforeOpen: () => {
            //                     Swal.showLoading();
            //                 }
            //             });

            //             // Wrap all asynchronous operations in a Promise
            //             Promise.all([
            //                 new Promise(resolve => setTimeout(resolve, 900)), // Minimum 1 second delay
            //                 new Promise((resolve, reject) => {
            //                     if (isAllStudentsView) {
            //                         // Switch back to enrolled students view
            //                         render_attendance_enrolled_students(id, sid, sectionid, schedid,
            //                             month);
            //                     } else {
            //                         // Switch to all students view
            //                         render_attendance_all_students(id, sid, sectionid, schedid,
            //                             month);
            //                     }
            //                     // Resolve the promise after rendering
            //                     resolve();
            //                 })
            //             ]).then(() => {
            //                 Swal.close(); // Close the loading indicator
            //                 // Toggle the view state
            //                 isAllStudentsView = !isAllStudentsView;
            //             }).catch(error => {
            //                 Swal.fire({
            //                     title: 'Error!',
            //                     text: 'There was a problem loading the data.',
            //                     icon: 'error',
            //                     confirmButtonText: 'OK'
            //                 });
            //             });
            //         });
            // }

            //assigned attendance row
            function applyAttendanceToRow(row, attendanceType) {
                // Add confirmation message before applying attendance using SweetAlert2
                Swal.fire({
                    title: 'Confirm Attendance',
                    text: `Are you sure you want to mark this attendance as ${attendanceType}?`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, apply it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.value) {
                        // Apply attendance only if confirmed
                        row.find('td').not(':first-child').not(':nth-child(2)').not(':nth-child(3)').each(
                            function() {
                                var cell = $(this);
                                var state = attendanceType === 'present' ? 'present' :
                                    attendanceType === 'late' ? 'late' : 'absent';
                                var color = attendanceType === 'present' ? 'green' :
                                    attendanceType === 'late' ? 'yellow' : 'red';
                                var textColor = attendanceType === 'late' ? 'black' : 'white';

                                cell.data('state', state)
                                    .text(attendanceType.charAt(0).toUpperCase() + attendanceType.slice(
                                        1))
                                    .css('background-color', color)
                                    .css('color', textColor);
                            });
                        // Show success prompt
                        Swal.fire({
                            title: 'Success!',
                            text: `Attendance has been marked as ${attendanceType}.`,
                            type: 'success',
                            confirmButtonText: 'OK'
                        });

                        // Save attendance to local storage after applying changes
                        saveAttendanceToLocalStorage();
                    } // No action taken if canceled
                });
            }

            function getHoverColor(attendanceType) {
                switch (attendanceType) {
                    case 'present':
                        return '#4CAF50'; // A slightly darker green
                    case 'late':
                        return '#ffff00'; // A slightly darker yellow
                    case 'absent':
                        return '#F44336'; // A slightly darker red
                    default:
                        return '#E0E0E0'; // A light gray for default
                }
            }

            //attendance menu
            // $(document).on('click', '.column-dropdown-arrow', function(e) {
            //     e.stopPropagation();
            //     var $this = $(this);
            //     var dateString = $this.data('date');
            //     var isOpen = $this.hasClass('is-open');

            //     // Close all other open dropdowns
            //     $('.column-dropdown-arrow').not($this).each(function() {
            //         $(this).removeClass('is-open').html('&#9660;');
            //         $(this).next('.column-dropdown-menu').remove();
            //     });

            //     if (isOpen) {
            //         // Close this dropdown
            //         $this.removeClass('is-open').html('&#9660;');
            //         $this.next('.column-dropdown-menu').remove();
            //     } else {
            //         // Open this dropdown
            //         $this.addClass('is-open').html('&#9650;'); // Up arrow character

            //         // Create and show dropdown menu
            //         var dropdownMenu = $('<div>', {
            //             'class': 'column-dropdown-menu',
            //             'style': 'position: absolute; top: 100%; left: 50%; transform: translateX(-50%); background-color: white; border: 1px solid #ccc; z-index: 1000;'
            //         });

            //         ['Present', 'Late', 'Absent'].forEach(option => {
            //             var optionElement = $('<div>', {
            //                 'class': 'column-dropdown-item',
            //                 'text': option,
            //                 'style': 'padding: 5px 10px; cursor: pointer; transition: background-color 0.3s;'
            //             }).on('click', function(e) {
            //                 e.stopPropagation();
            //                 applyAttendanceToColumn(dateString, option.toLowerCase());
            //                 dropdownMenu.remove();
            //                 $this.removeClass('is-open').html('&#9660;');
            //             }).on('mouseenter', function() {
            //                 $(this).css('background-color', getHoverColor(option
            //                     .toLowerCase()));
            //             }).on('mouseleave', function() {
            //                 $(this).css('background-color', '');
            //             });

            //             dropdownMenu.append(optionElement);
            //         });

            //         $this.after(dropdownMenu);
            //     }
            // });

            $(document).on('click', '.column-dropdown-arrow', function(e) {
                e.stopPropagation();
                var $this = $(this);
                var dateString = $this.data('date');
                var isOpen = $this.hasClass('is-open');

                // Close all other open dropdowns
                $('.column-dropdown-arrow').not($this).each(function() {
                    $(this).removeClass('is-open').html('&#9660;');
                    $(this).next('.column-dropdown-menu').remove();
                });

                if (isOpen) {
                    // Close this dropdown
                    $this.removeClass('is-open').html('&#9660;');
                    $this.next('.column-dropdown-menu').remove();
                } else {
                    // Open this dropdown
                    $this.addClass('is-open').html('&#9650;'); // Up arrow character

                    // Create and show dropdown menu
                    var dropdownMenu = $('<div>', {
                        'class': 'column-dropdown-menu',
                        'style': 'position: absolute; top: 100%; left: 50%; transform: translateX(-50%); background-color: white; border: 1px solid #ccc; z-index: 1000;'
                    });

                    ['Present', 'Late', 'Absent'].forEach(option => {
                        var optionElement = $('<div>', {
                            'class': 'column-dropdown-item',
                            'text': option,
                            'style': 'padding: 5px 10px; cursor: pointer; transition: background-color 0.3s;'
                        }).on('click', function(e) {
                            e.stopPropagation();
                            applyAttendanceToColumn(dateString, option.toLowerCase());
                            dropdownMenu.remove();
                            $this.removeClass('is-open').html('&#9660;');
                        }).on('mouseenter', function() {
                            $(this).css('background-color', getHoverColor(option
                                .toLowerCase()));
                        }).on('mouseleave', function() {
                            $(this).css('background-color', '');
                        });

                        dropdownMenu.append(optionElement);
                    });

                    $this.after(dropdownMenu);
                }
            });

            // Close dropdown when clicking outside
            $(document).on('click', function() {
                $('.column-dropdown-arrow').each(function() {
                    $(this).removeClass('is-open').html('&#9660;');
                    $(this).next('.column-dropdown-menu').remove();
                });
            });

            //assigned attendance colmun
            function applyAttendanceToColumn(dateString, attendanceType) {
                // Find the corresponding <th> element and get its text content
                var thElement = $(`#studentsAttendanceTable thead th[data-date="${dateString}"]`);
                // Split the date string in case of concatenated dates and take only the first date
                var displayDate = thElement.text().trim().split(/(?=[A-Z])/)[0]; // Takes only first date

                Swal.fire({
                    title: 'Confirm Attendance',
                    text: `Are you sure you want to mark ${displayDate}'s attendance as ${attendanceType} for all students?`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, apply it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.value) {
                        var columnIndex = thElement.index();

                        $("#studentsAttendanceTable tbody tr").each(function() {
                            var cell = $(this).find('td').eq(columnIndex);
                            if (cell.length && !$(this).hasClass('gender-separator')) {
                                var state = attendanceType === 'present' ? 'present' :
                                    attendanceType === 'late' ? 'late' : 'absent';
                                var color = attendanceType === 'present' ? 'green' :
                                    attendanceType === 'late' ? 'yellow' : 'red';
                                var textColor = attendanceType === 'late' ? 'black' : 'white';

                                cell.data('state', state)
                                    .text(attendanceType.charAt(0).toUpperCase() + attendanceType
                                        .slice(1))
                                    .css('background-color', color)
                                    .css('color', textColor);
                            }
                        });
                        Swal.fire({
                            title: 'Success!',
                            text: `Attendance has been marked as ${attendanceType} for all students on ${displayDate}.`,
                            type: 'success',
                            confirmButtonText: 'OK'
                        });

                        // Save attendance to local storage after applying changes
                        saveAttendanceToLocalStorage();
                    }
                });
            }
            // function applyAttendanceToColumn(dateString, attendanceType) {
            //     // Find the corresponding <th> element and get its text content
            //     var thElement = $(`#studentsAttendanceTable thead th[data-date="${dateString}"]`);
            //     var displayDate = thElement.text().trim(); // This will give us "September 2" format

            //     Swal.fire({
            //         title: 'Confirm Attendance',
            //         text: `Are you sure you want to mark ${displayDate}'s attendance as ${attendanceType} for all students?`,
            //         type: 'warning',
            //         showCancelButton: true,
            //         confirmButtonText: 'Yes, apply it!',
            //         cancelButtonText: 'No, cancel!'
            //     }).then((result) => {
            //         if (result.value) {
            //             var columnIndex = thElement.index();

            //             $("#studentsAttendanceTable tbody tr").each(function() {
            //                 var cell = $(this).find('td').eq(columnIndex);
            //                 if (cell.length && !$(this).hasClass('gender-separator')) {
            //                     var state = attendanceType === 'present' ? 'present' :
            //                         attendanceType === 'late' ? 'late' : 'absent';
            //                     var color = attendanceType === 'present' ? 'green' :
            //                         attendanceType === 'late' ? 'yellow' : 'red';
            //                     var textColor = attendanceType === 'late' ? 'black' : 'white';

            //                     cell.data('state', state)
            //                         .text(attendanceType.charAt(0).toUpperCase() + attendanceType
            //                             .slice(1))
            //                         .css('background-color', color)
            //                         .css('color', textColor);
            //                 }
            //             });
            //             Swal.fire({
            //                 title: 'Success!',
            //                 text: `Attendance has been marked as ${attendanceType} for all students on ${displayDate}.`,
            //                 type: 'success',
            //                 confirmButtonText: 'OK'
            //             });

            //             // Save attendance to local storage after applying changes
            //             saveAttendanceToLocalStorage();
            //         }
            //     });
            // }

            //saved colmun and row assigned attendance 
            function saveAttendanceToLocalStorage() {
                const syid = $('#sy').val();
                const semid = $('#semester').val();
                const subjectID = $('#rowsubjectID').val();
                const sectionID = $('#section_id').val();
                const selectedMonth = $('#monthsattendance').val(); // Get the selected month

                // Create a dynamic key using the provided variables
                const localStorageKey =
                    `attendanceDatasaved_${syid}_${semid}_${subjectID}_${sectionID}_${selectedMonth}`;

                // Initialize an object to hold the attendance data
                let attendanceData = {};

                // Loop through each row in the table and collect the attendance data
                $("#studentsAttendanceTable tbody tr").each(function() {
                    const studentId = $(this).find('td').eq(0)
                        .text(); // Assuming the first cell is the student ID
                    attendanceData[studentId] = {};

                    $(this).find('td').each(function(index) {
                        if (index >= 3) { // Assuming attendance data starts from the 4th cell
                            const state = $(this).data('state');
                            if (state) {
                                attendanceData[studentId][index] =
                                    state; // Store the attendance state by index
                            }
                        }
                    });
                });

                // Store the collected attendance data in localStorage using the dynamic key
                localStorage.setItem(localStorageKey, JSON.stringify(attendanceData));
            }

            //months filter
            $('#monthsattendance').on('change', function() {
                var selectedMonth = parseInt($(this).val());
                var currentYear = new Date().getFullYear(); // Get the current year
                var id = $('#rowsubjectID').val();
                var sectionid = $('#section_id').val();
                var schedid = $('#for_schedid').val();
                var sid = $('#students').val();

                // Load attendance for the selected month
                loadAttendanceFromLocalStorage();

                // Swal.fire({
                //     // title: 'Loading',
                //     text: 'Please wait while we fetch and save the attendance data...',
                //     allowOutsideClick: false,
                //     didOpen: () => {
                //         Swal.showLoading();
                //     }
                // });

                var renderFunction = isAllStudentsView ? render_attendance_all_students :
                    render_attendance_enrolled_students;

                renderFunction(id, sid, sectionid, schedid, selectedMonth,
                        currentYear) // Pass currentYear here
                    .then(() => {
                        // setTimeout(() => {
                        //     Swal.close();
                        //     Swal.fire({
                        //         title: 'Success!',
                        //         text: 'Attendance data has been fetched and saved.',
                        //         type: 'success',
                        //         confirmButtonText: 'OK'
                        //     });
                        // }, 600);
                    })
                    .catch(error => {
                        console.error('Error rendering attendance:', error);
                        Swal.close();
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was a problem loading and saving attendance data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
            });

            //list of months filter
            function populateMonthsDropdown() {
                var months = [
                    "January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];
                var currentMonth = new Date().getMonth();
                var select = $('#monthsattendance');
                select.empty();

                months.forEach((month, index) => {
                    var option = $('<option>', {
                        value: index + 1,
                        text: month,
                        selected: index === currentMonth
                    });
                    select.append(option);
                });
            }

            function populateMonthsDropdown2() {
                var months = [
                    "January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];
                var select = $('#monthsattendance');
                // select.empty();

                var selectedMonth = parseInt($('#monthsattendance').val()) || 1; // Get the selected month value

                months.forEach((month, index) => {
                    var option = $('<option>', {
                        value: index + 1,
                        text: month,
                        selected: index + 1 === selectedMonth // Set the selected month as selected
                    });
                    select.append(option);
                });
            }


            //male and female students attendance
            // function render_attendance_enrolled_students(id, sid, sectionid, schedid, month) {
            //     console.log('section this', sectionid);
            //     console.log('Selected student ID:', sid);

            //     // Empty the table
            //     var tableBody = $("#studentsAttendanceTable tbody");
            //     tableBody.empty();

            //     var sched = window.all_sched.find(x => x.subjectid == id);
            //     if (!sched) {
            //         console.error('Schedule not found for ID:', id);
            //         return Promise.reject('Schedule not found');
            //     }

            //     console.log('sched', sched);

            //     // Use the provided month, or default to current month
            //     month = month || new Date().getMonth() + 1;
            //     var year = new Date().getFullYear();

            //     // Extract unique days from the schedule
            //     var daysOfWeek = sched.days.split(/[,/]/).map(day => day.trim().toLowerCase())
            //         .map(day => {
            //             switch (day) {
            //                 case 'sun':
            //                 case 'sunday':
            //                     return 0;
            //                 case 'mon':
            //                 case 'monday':
            //                     return 1;
            //                 case 'tue':
            //                 case 'tuesday':
            //                     return 2;
            //                 case 'wed':
            //                 case 'wednesday':
            //                     return 3;
            //                 case 'thu':
            //                 case 'thursday':
            //                     return 4;
            //                 case 'fri':
            //                 case 'friday':
            //                     return 5;
            //                 case 'sat':
            //                 case 'saturday':
            //                     return 6;
            //                 default:
            //                     return -1;
            //             }
            //         }).filter(index => index !== -1);

            //     var allDates = getAllDates(daysOfWeek, month, year);

            //     console.log('Days of Week:', daysOfWeek);

            //     addDatesToHeader(allDates, id, sid, sectionid, schedid, month);

            //     var headerdates = $('#studentsAttendanceTable thead th.date').map(function() {
            //         let dateText = $(this).text().trim();
            //         let day = dateText.split(' ')[1].replace(',', '');
            //         return day;
            //     }).get();

            //     console.log('Header Dates:', headerdates);

            //     return fetchStudentList(schedid).then(function(studentsData) { // Return the promise
            //         console.log('Fetched Students:', studentsData);

            //         // Use the pre-loaded student list
            //         var students = window.all_students.students;

            //         if (sid && sid !== '') {
            //             students = students.filter(x => x.sid == sid);
            //         }

            //         // Separate male and female students
            //         var maleStudents = students.filter(student => student.gender === 'MALE');
            //         var femaleStudents = students.filter(student => student.gender === 'FEMALE');

            //         // Sort each group independently
            //         function sortStudents(studentGroup) {
            //             return studentGroup.sort((a, b) => {
            //                 if (a.lastname !== b.lastname) {
            //                     return a.lastname.localeCompare(b.lastname);
            //                 }
            //                 return a.firstname.localeCompare(b.firstname);
            //             });
            //         }

            //         maleStudents = sortStudents(maleStudents);
            //         femaleStudents = sortStudents(femaleStudents);

            //         // Combine sorted groups, with males first
            //         var sortedStudents = [...maleStudents, ...femaleStudents];

            //         // Remove duplicates based on student ID
            //         var uniqueStudents = Array.from(new Set(sortedStudents.map(student => student.sid)))
            //             .map(id => {
            //                 return sortedStudents.find(student => student.sid === id);
            //             });

            //         // Fetch the attendance data
            //         return $.ajax({
            //             url: "/collegeattendancefetch/",
            //             type: 'GET',
            //             headers: {
            //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //             },
            //             data: {
            //                 syid: $('#sy').val(),
            //                 semid: $('#semester').val(),
            //                 subjectid: id,
            //                 sectionid: sectionid,
            //                 monthid: month,
            //                 yearid: year
            //             }
            //         }).then(function(attendance) {
            //             console.log('Fetched attendance data for month:', month);

            //             function populateTableRows(students, tableBody) {
            //                 if (students.length === 0) {
            //                     // No students found
            //                     var noDataRow = $("<tr>");
            //                     var noDataCell = $("<td>")
            //                         .attr("colspan", headerdates.length + 3)
            //                         .text("No student found")
            //                         .css({
            //                             "text-align": "center",
            //                         });
            //                     noDataRow.append(noDataCell);
            //                     tableBody.append(noDataRow);
            //                 } else {
            //                     var currentGender = null;
            //                     var maleCount = students.filter(s => s.gender === 'MALE').length;
            //                     var femaleCount = students.filter(s => s.gender === 'FEMALE')
            //                         .length;

            //                     students.forEach(function(student) {
            //                         // Add gender separator if gender changes
            //                         if (student.gender !== currentGender) {
            //                             currentGender = student.gender;
            //                             var genderText = currentGender === 'MALE' ?
            //                                 `Male Students (${maleCount})` :
            //                                 `Female Students (${femaleCount})`;
            //                             var genderRow = $("<tr>").append(
            //                                 $("<td>")
            //                                 .attr("colspan", headerdates.length + 3)
            //                                 .text(genderText)
            //                                 .css({
            //                                     "background-color": currentGender ===
            //                                         'MALE' ? "#000000" : "#000000",
            //                                     "color": "white",
            //                                     "font-weight": "bold",
            //                                     "text-align": "left"
            //                                 })
            //                             );
            //                             tableBody.append(genderRow);
            //                         }
            //                         var row = $("<tr>");

            //                         // Add student ID
            //                         row.append($("<td>").text(student.sid));

            //                         // Add student name
            //                         row.append($("<td>").css('text-align', 'left').text(
            //                             student.lastname + ", " + student
            //                             .firstname + " " +
            //                             (student.middlename || '')));

            //                         // Add level name with dropdown arrow
            //                         var levelNameCell = $("<td>").css({
            //                             'position': 'relative',
            //                             'text-align': 'left'
            //                         });
            //                         levelNameCell.append(student.levelname);

            //                         var dropdownArrow = $('<span>', {
            //                             'class': 'student-dropdown-arrow',
            //                             'style': 'cursor: pointer; '
            //                         });

            //                         // Toggle dropdown and arrow direction on click
            //                         dropdownArrow.on('click', function(e) {
            //                             e.stopPropagation();

            //                             // Close all other dropdowns and reset their arrows
            //                             $('.dropdown-menu').not(dropdownMenu)
            //                                 .hide();
            //                             $('.student-dropdown-arrow').not(this)
            //                                 .removeClass('pointing-left');

            //                             // Toggle current dropdown and arrow direction
            //                             dropdownMenu.toggle();
            //                             $(this).toggleClass('pointing-left');
            //                         });

            //                         var dropdownMenu = $('<div>', {
            //                             'class': 'dropdown-menu',
            //                             'style': 'display: none; position: absolute; top: 100%; right: 0; background-color: white; border: 1px solid #ccc; z-index: 1000;'
            //                         });

            //                         ['Present', 'Late', 'Absent'].forEach(option => {
            //                             var optionElement = $('<div>', {
            //                                 'text': option,
            //                                 'style': 'padding: 5px 10px; cursor: pointer; transition: background-color 0.3s;'
            //                             }).on('click', function(e) {
            //                                 e.stopPropagation();
            //                                 applyAttendanceToRow(row, option
            //                                     .toLowerCase());
            //                                 dropdownMenu.hide();
            //                                 dropdownArrow.removeClass(
            //                                     'rotated'
            //                                 ); // Reset arrow when option is selected
            //                             }).on('mouseenter', function() {
            //                                 $(this).css('background-color',
            //                                     getHoverColor(option
            //                                         .toLowerCase()));
            //                             }).on('mouseleave', function() {
            //                                 $(this).css('background-color',
            //                                     '');
            //                             });

            //                             dropdownMenu.append(optionElement);
            //                         });

            //                         levelNameCell.append(dropdownArrow).append(
            //                             dropdownMenu);
            //                         row.append(levelNameCell);

            //                         // Add dynamic attendance cells based on headerdates
            //                         allDates.forEach(function(date) {
            //                             var day = date.getDate();
            //                             var attendanceData = attendance.find(att =>
            //                                 att.studid == student.sid);
            //                             var dayColumn = `day${day}`;
            //                             var state = attendanceData ? attendanceData[
            //                                 dayColumn] : null;

            //                             var newCell = $("<td>", {
            //                                 'class': 'text-center day' +
            //                                     day,
            //                                 'data-state': state === 1 ?
            //                                     'present' : state === 0 ?
            //                                     'absent' : state === 2 ?
            //                                     'late' : 'present',
            //                                 'text': state === 1 ?
            //                                     'Present' : state === 0 ?
            //                                     'Absent' : state === 2 ?
            //                                     'Late' : 'Present',
            //                                 'style': state === 1 ?
            //                                     'background-color: green; color: white; cursor: pointer;' :
            //                                     state === 0 ?
            //                                     'background-color: red; color: white; cursor: pointer;' :
            //                                     state === 2 ?
            //                                     'background-color: yellow; color: black; cursor: pointer;' :
            //                                     'background-color: green; color: white; cursor: pointer;'
            //                             });

            //                             newCell.on('click', handleCellClick);
            //                             newCell.on('contextmenu',
            //                                 handleCellRightClick);

            //                             row.append(newCell);
            //                         });

            //                         tableBody.append(row);

            //                         $(document).on('click', function() {
            //                             $('.dropdown-menu').hide();
            //                             $('.student-dropdown-arrow').removeClass(
            //                                 'rotated');
            //                         });
            //                     });
            //                 }
            //             }

            //             // Populate the table
            //             // populateTableRows(sortedStudents, tableBody);
            //             populateTableRows(uniqueStudents, tableBody);
            //             addDatesToHeader(allDates, id, sid, sectionid, schedid, month);
            //             autoSaveAttendance(id, sectionid, month, year);
            //             renderCalendar();
            //         });
            //     }).catch(function(error) {
            //         console.error('Error fetching student list:', error);
            //         Swal.close(); // Ensure the loading indicator is closed
            //     });
            // }
            //
            // function render_attendance_enrolled_students(id, sid, sectionid, schedid, month) {
            //     console.log('section this', sectionid);
            //     console.log('Selected student ID:', sid);

            //     // Empty the table
            //     var tableBody = $("#studentsAttendanceTable tbody");
            //     tableBody.empty();

            //     var sched = window.all_sched.find(x => x.subjectid == id);
            //     if (!sched) {
            //         console.error('Schedule not found for ID:', id);
            //         return Promise.reject('Schedule not found');
            //     }

            //     console.log('sched', sched);

            //     // Use the provided month, or default to current month
            //     month = month || new Date().getMonth() + 1;
            //     var year = new Date().getFullYear();

            //     // Extract unique days from the schedule
            //     var daysOfWeek = sched.days.split(/[,/]/).map(day => day.trim().toLowerCase())
            //         .map(day => {
            //             switch (day) {
            //                 case 'sun':
            //                 case 'sunday':
            //                     return 0;
            //                 case 'mon':
            //                 case 'monday':
            //                     return 1;
            //                 case 'tue':
            //                 case 'tuesday':
            //                     return 2;
            //                 case 'wed':
            //                 case 'wednesday':
            //                     return 3;
            //                 case 'thu':
            //                 case 'thursday':
            //                     return 4;
            //                 case 'fri':
            //                 case 'friday':
            //                     return 5;
            //                 case 'sat':
            //                 case 'saturday':
            //                     return 6;
            //                 default:
            //                     return -1;
            //             }
            //         }).filter(index => index !== -1);

            //     var allDates = getAllDates(daysOfWeek, month, year);

            //     console.log('Days of Week:', daysOfWeek);

            //     addDatesToHeader(allDates, id, sid, sectionid, schedid, month);

            //     var headerdates = $('#studentsAttendanceTable thead th.date').map(function() {
            //         let dateText = $(this).text().trim();
            //         let day = dateText.split(' ')[1].replace(',', '');
            //         return day;
            //     }).get();

            //     console.log('Header Dates:', headerdates);

            //     return fetchStudentList(schedid).then(function(studentsData) { // Return the promise
            //         console.log('Fetched Students:', studentsData);

            //         // Use the pre-loaded student list
            //         var students = window.all_students.students;

            //         if (sid && sid !== '') {
            //             students = students.filter(x => x.sid == sid);
            //         }

            //         // Separate male and female students
            //         var maleStudents = students.filter(student => student.gender === 'MALE');
            //         var femaleStudents = students.filter(student => student.gender === 'FEMALE');

            //         // Sort each group independently
            //         function sortStudents(studentGroup) {
            //             return studentGroup.sort((a, b) => {
            //                 if (a.lastname !== b.lastname) {
            //                     return a.lastname.localeCompare(b.lastname);
            //                 }
            //                 return a.firstname.localeCompare(b.firstname);
            //             });
            //         }

            //         maleStudents = sortStudents(maleStudents);
            //         femaleStudents = sortStudents(femaleStudents);

            //         // Combine sorted groups, with males first
            //         var sortedStudents = [...maleStudents, ...femaleStudents];

            //         // Fetch the attendance data
            //         return $.ajax({
            //             url: "/collegeattendancefetch/",
            //             type: 'GET',
            //             headers: {
            //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //             },
            //             data: {
            //                 syid: $('#sy').val(),
            //                 semid: $('#semester').val(),
            //                 subjectid: id,
            //                 sectionid: sectionid,
            //                 monthid: month,
            //                 yearid: year
            //             }
            //         }).then(function(attendance) {
            //             console.log('Fetched attendance data for month:', month);

            //             function populateTableRows(students, tableBody) {
            //                 if (students.length === 0) {
            //                     // No students found
            //                     var noDataRow = $("<tr>");
            //                     var noDataCell = $("<td>")
            //                         .attr("colspan", headerdates.length + 3)
            //                         .text("No student found")
            //                         .css({
            //                             "text-align": "center",
            //                         });
            //                     noDataRow.append(noDataCell);
            //                     tableBody.append(noDataRow);
            //                 } else {
            //                     var currentGender = null;
            //                     var maleCount = students.filter(s => s.gender === 'MALE').length;
            //                     var femaleCount = students.filter(s => s.gender === 'FEMALE')
            //                         .length;

            //                     students.forEach(function(student) {
            //                         // Add gender separator if gender changes
            //                         if (student.gender !== currentGender) {
            //                             currentGender = student.gender;
            //                             var genderText = currentGender === 'MALE' ?
            //                                 `Male Students (${maleCount})` :
            //                                 `Female Students (${femaleCount})`;
            //                             var genderRow = $("<tr>").append(
            //                                 $("<td>")
            //                                 .attr("colspan", headerdates.length + 3)
            //                                 .text(genderText)
            //                                 .css({
            //                                     "background-color": currentGender ===
            //                                         'MALE' ? "#8ec9fd" : "#fd8ec9",
            //                                     "color": "black",
            //                                     "font-weight": "bold",
            //                                     "text-align": "left"
            //                                 })
            //                             );
            //                             tableBody.append(genderRow);
            //                         }
            //                         var row = $("<tr>");

            //                         // Add student ID
            //                         row.append($("<td>").text(student.sid));

            //                         // Add student name
            //                         row.append($("<td>").css('text-align', 'left').text(
            //                             student.lastname + ", " + student
            //                             .firstname + " " +
            //                             (student.middlename || '')));

            //                         // Add level name with dropdown arrow
            //                         var levelNameCell = $("<td>").css({
            //                             'position': 'relative',
            //                             'text-align': 'left'
            //                         });
            //                         levelNameCell.append(student.levelname);

            //                         var dropdownArrow = $('<span>', {
            //                             'class': 'student-dropdown-arrow text-secondary',
            //                             // 'style': 'cursor: pointer; '
            //                             //   'style': 'cursor: pointer; position: absolute; right: 10px; left: 90%; top: 50%; transform: translateY(-50%);'
            //                         });

            //                         // Toggle dropdown and arrow direction on click
            //                         dropdownArrow.on('click', function(e) {
            //                             e.stopPropagation();

            //                             // Close all other dropdowns and reset their arrows
            //                             $('.dropdown-menu').not(dropdownMenu)
            //                                 .hide();
            //                             $('.student-dropdown-arrow').not(this)
            //                                 .removeClass('pointing-left');

            //                             // Toggle current dropdown and arrow direction
            //                             dropdownMenu.toggle();
            //                             $(this).toggleClass('pointing-left');
            //                         });

            //                         var dropdownMenu = $('<div>', {
            //                             'class': 'dropdown-menu',
            //                             'style': 'display: none; position: absolute; top: 100%; right: 0; background-color: white; border: 1px solid #ccc; z-index: 1000;'
            //                         });

            //                         ['Present', 'Late', 'Absent'].forEach(option => {
            //                             var optionElement = $('<div>', {
            //                                 'text': option,
            //                                 'style': 'padding: 5px 10px; cursor: pointer; transition: background-color 0.3s;'
            //                             }).on('click', function(e) {
            //                                 e.stopPropagation();
            //                                 applyAttendanceToRow(row, option
            //                                     .toLowerCase());
            //                                 dropdownMenu.hide();
            //                                 dropdownArrow.removeClass(
            //                                     'rotated'
            //                                 ); // Reset arrow when option is selected
            //                             }).on('mouseenter', function() {
            //                                 $(this).css('background-color',
            //                                     getHoverColor(option
            //                                         .toLowerCase()));
            //                             }).on('mouseleave', function() {
            //                                 $(this).css('background-color',
            //                                     '');
            //                             });

            //                             dropdownMenu.append(optionElement);
            //                         });

            //                         levelNameCell.append(dropdownArrow).append(
            //                             dropdownMenu);
            //                         row.append(levelNameCell);

            //                         // Add dynamic attendance cells based on headerdates
            //                         allDates.forEach(function(date) {
            //                             var day = date.getDate();
            //                             var attendanceData = attendance.find(att =>
            //                                 att.studid == student.sid);
            //                             var dayColumn = `day${day}`;
            //                             var state = attendanceData ? attendanceData[
            //                                 dayColumn] : null;

            //                             var newCell = $("<td>", {
            //                                 'class': 'text-center attday day' +
            //                                     day,
            //                                 'data-state': state === 1 ?
            //                                     'present' : state === 3 ?
            //                                     'absent' : state === 2 ?
            //                                     'late' : 'present',
            //                                 'data-day': dayColumn,
            //                                 'text': state === 1 ?
            //                                     'Present' : state === 3 ?
            //                                     'Absent' : state === 2 ?
            //                                     'Late' : 'Present',
            //                                 'style': state === 1 ?
            //                                     'background-color: green; color: white; cursor: pointer;' :
            //                                     state === 3 ?
            //                                     'background-color: red; color: white; cursor: pointer;' :
            //                                     state === 2 ?
            //                                     'background-color: yellow; color: black; cursor: pointer;' :
            //                                     'background-color: green; color: white; cursor: pointer;'
            //                             });

            //                             newCell.on('click', handleCellClick);
            //                             newCell.on('contextmenu',
            //                                 handleCellRightClick);

            //                             row.append(newCell);
            //                         });

            //                         tableBody.append(row);

            //                         $(document).on('click', function() {
            //                             $('.dropdown-menu').hide();
            //                             $('.student-dropdown-arrow').removeClass(
            //                                 'rotated');
            //                         });
            //                     });
            //                 }
            //             }

            //             // Populate the table
            //             populateTableRows(sortedStudents, tableBody);
            //             addDatesToHeader(allDates, id, sid, sectionid, schedid, month);
            //             autoSaveAttendance(id, sectionid, month, year);
            //             renderCalendar();
            //             // }).catch(function(error) {
            //             //     Swal.fire({
            //             //         title: 'Error!',
            //             //         text: 'There was a problem fetching attendance data.',
            //             //         type: 'error',
            //             //         confirmButtonText: 'OK'
            //             //     });
            //         });
            //     }).catch(function(error) {
            //         console.error('Error fetching student list:', error);
            //         Swal.close(); // Ensure the loading indicator is closed
            //     });
            // }

            function render_attendance_enrolled_students(id, sid, sectionid, schedid, month) {
                console.log('section this', sectionid);
                console.log('Selected student ID:', sid);

                // Empty the table
                var tableBody = $("#studentsAttendanceTable tbody");
                tableBody.empty();

                var sched = window.all_sched.find(x => x.subjectid == id);
                if (!sched) {
                    console.error('Schedule not found for ID:', id);
                    return Promise.reject('Schedule not found');
                }

                console.log('sched', sched);

                // Use the provided month, or default to current month
                month = month || new Date().getMonth() + 1;
                var year = new Date().getFullYear();

                // Extract unique days from the schedule
                var daysOfWeek = sched.days.split(/[,/]/).map(day => day.trim().toLowerCase())
                    .map(day => {
                        switch (day) {
                            case 'sun':
                            case 'sunday':
                                return 0;
                            case 'mon':
                            case 'monday':
                                return 1;
                            case 'tue':
                            case 'tuesday':
                                return 2;
                            case 'wed':
                            case 'wednesday':
                                return 3;
                            case 'thu':
                            case 'thursday':
                                return 4;
                            case 'fri':
                            case 'friday':
                                return 5;
                            case 'sat':
                            case 'saturday':
                                return 6;
                            default:
                                return -1;
                        }
                    }).filter(index => index !== -1);

                var allDates = getAllDates(daysOfWeek, month, year);

                console.log('Days of Week:', daysOfWeek);

                addDatesToHeader(allDates, id, sid, sectionid, schedid, month);

                var headerdates = $('#studentsAttendanceTable thead th.date').map(function() {
                    let dateText = $(this).text().trim();
                    let day = dateText.split(' ')[1].replace(',', '');
                    return day;
                }).get();

                console.log('Header Dates:', headerdates);

                return fetchStudentList(schedid).then(function(studentsData) { // Return the promise
                    console.log('Fetched Students:', studentsData);

                    // Use the pre-loaded student list
                    var students = window.all_students.students;

                    if (sid && sid !== '') {
                        students = students.filter(x => x.sid == sid);
                    }

                    // Separate male and female students
                    var maleStudents = students.filter(student => student.gender === 'MALE');
                    var femaleStudents = students.filter(student => student.gender === 'FEMALE');

                    // Sort each group independently
                    function sortStudents(studentGroup) {
                        return studentGroup.sort((a, b) => {
                            if (a.lastname !== b.lastname) {
                                return a.lastname.localeCompare(b.lastname);
                            }
                            return a.firstname.localeCompare(b.firstname);
                        });
                    }

                    maleStudents = sortStudents(maleStudents);
                    femaleStudents = sortStudents(femaleStudents);

                    // Combine sorted groups, with males first
                    var sortedStudents = [...maleStudents, ...femaleStudents];

                    // Fetch the attendance data
                    return $.ajax({
                        url: "/collegeattendancefetch/",
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            syid: $('#sy').val(),
                            semid: $('#semester').val(),
                            subjectid: id,
                            sectionid: sectionid,
                            monthid: month,
                            yearid: year
                        }
                    }).then(function(attendance) {
                        console.log('Fetched attendance data for month:', month);

                        function populateTableRows(students, tableBody) {
                            if (students.length === 0) {
                                // No students found
                                var noDataRow = $("<tr>");
                                var noDataCell = $("<td>")
                                    .attr("colspan", headerdates.length + 3)
                                    .text("No student found")
                                    .css({
                                        "text-align": "center",
                                    });
                                noDataRow.append(noDataCell);
                                tableBody.append(noDataRow);
                            } else {
                                var currentGender = null;
                                var maleCount = students.filter(s => s.gender === 'MALE').length;
                                var femaleCount = students.filter(s => s.gender === 'FEMALE')
                                    .length;

                                students.forEach(function(student) {
                                    // Add gender separator if gender changes
                                    if (student.gender !== currentGender) {
                                        currentGender = student.gender;
                                        var genderText = currentGender === 'MALE' ?
                                            `Male Students (${maleCount})` :
                                            `Female Students (${femaleCount})`;
                                        var genderRow = $("<tr>").append(
                                            $("<td>")
                                            .attr("colspan", headerdates.length + 3)
                                            .text(genderText)
                                            .css({
                                                "background-color": currentGender ===
                                                    'MALE' ? "#8ec9fd" : "#fd8ec9",
                                                "color": "black",
                                                "font-weight": "bold",
                                                "text-align": "left"
                                            })
                                        );
                                        tableBody.append(genderRow);
                                    }
                                    var row = $("<tr>");

                                    // Add student ID
                                    row.append($("<td>").text(student.sid));

                                    // Add student name
                                    row.append($("<td>").css('text-align', 'left').text(
                                        student.lastname + ", " + student
                                        .firstname + " " +
                                        (student.middlename || '')));

                                    // Add level name with dropdown arrow
                                    var levelNameCell = $("<td>").css({
                                        'position': 'relative',
                                        'text-align': 'left'
                                    });
                                    levelNameCell.append(student.levelname);

                                    var dropdownArrow = $('<span>', {
                                        'class': 'student-dropdown-arrow text-secondary',
                                        // 'style': 'cursor: pointer; '
                                        //   'style': 'cursor: pointer; position: absolute; right: 10px; left: 90%; top: 50%; transform: translateY(-50%);'
                                    });

                                    // Toggle dropdown and arrow direction on click
                                    dropdownArrow.on('click', function(e) {
                                        e.stopPropagation();

                                        // Close all other dropdowns and reset their arrows
                                        $('.dropdown-menu').not(dropdownMenu)
                                            .hide();
                                        $('.student-dropdown-arrow').not(this)
                                            .removeClass('pointing-left');

                                        // Toggle current dropdown and arrow direction
                                        dropdownMenu.toggle();
                                        $(this).toggleClass('pointing-left');
                                    });

                                    var dropdownMenu = $('<div>', {
                                        'class': 'dropdown-menu',
                                        'style': 'display: none; position: absolute; top: 100%; right: 0; background-color: white; border: 1px solid #ccc; z-index: 1000;'
                                    });

                                    ['Present', 'Late', 'Absent'].forEach(option => {
                                        var optionElement = $('<div>', {
                                            'text': option,
                                            'style': 'padding: 5px 10px; cursor: pointer; transition: background-color 0.3s;'
                                        }).on('click', function(e) {
                                            e.stopPropagation();
                                            applyAttendanceToRow(row, option
                                                .toLowerCase());
                                            dropdownMenu.hide();
                                            dropdownArrow.removeClass(
                                                'rotated'
                                            ); // Reset arrow when option is selected
                                        }).on('mouseenter', function() {
                                            $(this).css('background-color',
                                                getHoverColor(option
                                                    .toLowerCase()));
                                        }).on('mouseleave', function() {
                                            $(this).css('background-color',
                                                '');
                                        });

                                        dropdownMenu.append(optionElement);
                                    });

                                    levelNameCell.append(dropdownArrow).append(
                                        dropdownMenu);
                                    row.append(levelNameCell);

                                    // Add dynamic attendance cells based on headerdates
                                    allDates.forEach(function(date) {
                                        // var day = date.getDate();
                                        var day = date.getDate();
                                        var monthId = date.getMonth() + 1;
                                        var yearId = date.getFullYear();
                                        var studentid = student.sid;
                                        var attendanceData = attendance.find(att =>
                                            att.studid == student.sid);
                                        var dayColumn = `day${day}`;
                                        var state = attendanceData ? attendanceData[
                                            dayColumn] : null;

                                        var newCell = $("<td>", {
                                            'class': 'text-center attday day' +
                                                day,
                                            'data-state': state === 1 ?
                                                'present' : state === 3 ?
                                                'absent' : state === 2 ?
                                                'late' : 'present',
                                            'data-month': monthId,
                                            'data-day': dayColumn,
                                            'data-year': yearId,
                                            'data-sid': studentid,
                                            'text': state === 1 ?
                                                'Present' : state === 3 ?
                                                'Absent' : state === 2 ?
                                                'Late' : 'Present',
                                            'style': state === 1 ?
                                                'background-color: green; color: white; cursor: pointer;' :
                                                state === 3 ?
                                                'background-color: red; color: white; cursor: pointer;' :
                                                state === 2 ?
                                                'background-color: yellow; color: black; cursor: pointer;' :
                                                'background-color: green; color: white; cursor: pointer;'
                                        });

                                        newCell.on('click', handleCellClick);
                                        newCell.on('contextmenu',
                                            handleCellRightClick);

                                        row.append(newCell);
                                    });

                                    tableBody.append(row);

                                    $(document).on('click', function() {
                                        $('.dropdown-menu').hide();
                                        $('.student-dropdown-arrow').removeClass(
                                            'rotated');
                                    });
                                });
                            }
                        }

                        // Populate the table
                        populateTableRows(sortedStudents, tableBody);
                        addDatesToHeader(allDates, id, sid, sectionid, schedid, month);
                        autoSaveAttendance(id, sectionid, month, year);
                        renderCalendar();
                        // }).catch(function(error) {
                        //     Swal.fire({
                        //         title: 'Error!',
                        //         text: 'There was a problem fetching attendance data.',
                        //         type: 'error',
                        //         confirmButtonText: 'OK'
                        //     });
                    });
                }).catch(function(error) {
                    console.error('Error fetching student list:', error);
                    Swal.close(); // Ensure the loading indicator is closed
                });
            }

            //all students attendance table
            function render_attendance_all_students(id, sid, sectionid, schedid, month) {
                console.log('section this', sectionid);
                console.log('Selected student ID:', sid);

                // Empty the table
                var tableBody = $("#studentsAttendanceTable tbody");
                tableBody.empty();

                var sched = window.all_sched.find(x => x.subjectid == id);
                if (!sched) {
                    console.error('Schedule not found for ID:', id);
                    return Promise.reject('Schedule not found');
                }

                console.log('sched', sched);

                // Use the provided month, or default to current month
                month = month || new Date().getMonth() + 1;
                var year = new Date().getFullYear();

                // Extract unique days from the schedule
                var daysOfWeek = sched.days.split(/[,/]/).map(day => day.trim().toLowerCase())
                    .map(day => {
                        switch (day) {
                            case 'sun':
                            case 'sunday':
                                return 0;
                            case 'mon':
                            case 'monday':
                                return 1;
                            case 'tue':
                            case 'tuesday':
                                return 2;
                            case 'wed':
                            case 'wednesday':
                                return 3;
                            case 'thu':
                            case 'thursday':
                                return 4;
                            case 'fri':
                            case 'friday':
                                return 5;
                            case 'sat':
                            case 'saturday':
                                return 6;
                            default:
                                return -1;
                        }
                    }).filter(index => index !== -1);

                var allDates = getAllDates(daysOfWeek, month, year);

                console.log('Days of Week:', daysOfWeek);

                addDatesToHeader(allDates, id, sid, sectionid, schedid, month);

                var headerdates = $('#studentsAttendanceTable thead th.date').map(function() {
                    let dateText = $(this).text().trim();
                    let day = dateText.split(' ')[1].replace(',', '');
                    return day;
                }).get();

                console.log('Header Dates:', headerdates);

                return fetchStudentList(schedid).then(function(studentsData) { // Return the promise
                    console.log('Fetched Students:', studentsData);

                    // Use the pre-loaded student list
                    var students = window.all_students.students;

                    if (sid && sid !== '') {
                        students = students.filter(x => x.sid == sid);
                    }

                    // Sort all students together
                    var sortedStudents = students.sort((a, b) => {
                        if (a.lastname !== b.lastname) {
                            return a.lastname.localeCompare(b.lastname);
                        }
                        return a.firstname.localeCompare(b.firstname);
                    });

                    // Fetch the attendance data
                    return $.ajax({
                        url: "/collegeattendancefetch/",
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            syid: $('#sy').val(),
                            semid: $('#semester').val(),
                            subjectid: id,
                            sectionid: sectionid,
                            monthid: month,
                            yearid: year
                        }
                    }).then(function(attendance) {
                        console.log('Fetched attendance data for month:', month);

                        function populateTableRows(students, tableBody) {
                            if (students.length === 0) {
                                // No students found
                                var noDataRow = $("<tr>");
                                var noDataCell = $("<td>")
                                    .attr("colspan", headerdates.length + 3)
                                    .text("No student found")
                                    .css({
                                        "text-align": "center",
                                    });
                                noDataRow.append(noDataCell);
                                tableBody.append(noDataRow);
                            } else {
                                // Add a single row for all students
                                var allCount = students.length;
                                var allRow = $("<tr>").append(
                                    $("<td>")
                                    .attr("colspan", headerdates.length + 3)
                                    .text(`All Students (${allCount})`)
                                    .css({
                                        "background-color": "#000000",
                                        "color": "white",
                                        "font-weight": "bold",
                                        "text-align": "left"
                                    })
                                );
                                tableBody.append(allRow);

                                students.forEach(function(student) {
                                    var row = $("<tr>");

                                    // Add student ID
                                    row.append($("<td>").text(student.sid));

                                    // Add student name
                                    row.append($("<td>").css('text-align', 'left').text(
                                        student.lastname + ", " + student
                                        .firstname + " " +
                                        (student.middlename || '')));

                                    // Add level name with dropdown arrow
                                    var levelNameCell = $("<td>").css({
                                        'position': 'relative',
                                        'text-align': 'left'
                                    });
                                    levelNameCell.append(student.levelname);

                                    // var dropdownArrow = $('<span>', {
                                    //     'class': 'student-dropdown-arrow',
                                    //     'style': 'cursor: pointer; position: absolute; right: 10px; left: 90%; top: 50%; transform: translateY(-50%);'
                                    // });

                                    var dropdownArrow = $('<span>', {
                                        'class': 'student-dropdown-arrow text-secondary',
                                        // 'style': 'background-color: black; cursor: pointer; '
                                        //   'style': 'cursor: pointer; position: absolute; right: 10px; left: 90%; top: 50%; transform: translateY(-50%);'
                                    });

                                    // Toggle dropdown and arrow direction on click
                                    dropdownArrow.on('click', function(e) {
                                        e.stopPropagation();

                                        // Close all other dropdowns and reset their arrows
                                        $('.dropdown-menu').not(dropdownMenu)
                                            .hide();
                                        $('.student-dropdown-arrow').not(this)
                                            .removeClass('pointing-left');

                                        // Toggle current dropdown and arrow direction
                                        dropdownMenu.toggle();
                                        $(this).toggleClass('pointing-left');
                                    });

                                    var dropdownMenu = $('<div>', {
                                        'class': 'dropdown-menu',
                                        'style': 'display: none; position: absolute; top: 100%; right: 0; background-color: white; border: 1px solid #ccc; z-index: 1000;'
                                    });

                                    ['Present', 'Late', 'Absent'].forEach(option => {
                                        var optionElement = $('<div>', {
                                            'text': option,
                                            'style': 'padding: 5px 10px; cursor: pointer; transition: background-color 0.3s;'
                                        }).on('click', function(e) {
                                            e.stopPropagation();
                                            applyAttendanceToRow(row, option
                                                .toLowerCase());
                                            dropdownMenu.hide();
                                            dropdownArrow.removeClass(
                                                'rotated'
                                            ); // Reset arrow when option is selected
                                        }).on('mouseenter', function() {
                                            $(this).css('background-color',
                                                getHoverColor(option
                                                    .toLowerCase()));
                                        }).on('mouseleave', function() {
                                            $(this).css('background-color',
                                                '');
                                        });

                                        dropdownMenu.append(optionElement);
                                    });

                                    levelNameCell.append(dropdownArrow).append(
                                        dropdownMenu);
                                    row.append(levelNameCell);

                                    // Add dynamic attendance cells based on headerdates
                                    allDates.forEach(function(date) {
                                        var day = date.getDate();
                                        var attendanceData = attendance.find(att =>
                                            att.studid == student.sid);
                                        var dayColumn = `day${day}`;
                                        var state = attendanceData ? attendanceData[
                                            dayColumn] : null;

                                        var newCell = $("<td>", {
                                            'class': 'text-center day' +
                                                day,
                                            'data-state': state === 1 ?
                                                'present' : state === 3 ?
                                                'absent' : state === 2 ?
                                                'late' : 'present',
                                            'text': state === 1 ?
                                                'Present' : state === 3 ?
                                                'Absent' : state === 2 ?
                                                'Late' : 'Present',
                                            'style': state === 1 ?
                                                'background-color: green; color: white; cursor: pointer;' :
                                                state === 3 ?
                                                'background-color: red; color: white; cursor: pointer;' :
                                                state === 2 ?
                                                'background-color: yellow; color: black; cursor: pointer;' :
                                                'background-color: green; color: white; cursor: pointer;'
                                        });

                                        newCell.on('click', handleCellClick);
                                        newCell.on('contextmenu',
                                            handleCellRightClick);

                                        row.append(newCell);
                                    });

                                    tableBody.append(row);

                                    $(document).on('click', function() {
                                        $('.dropdown-menu').hide();
                                        $('.student-dropdown-arrow').removeClass(
                                            'rotated');
                                    });
                                });
                            }
                        }

                        // Populate the table
                        populateTableRows(sortedStudents, tableBody);
                        addDatesToHeader(allDates, id, sid, sectionid, schedid, month);
                        autoSaveAttendance(id, sectionid, month, year);
                        renderCalendar();
                    }).catch(function(error) {
                        // Swal.fire({
                        //     title: 'Error!',
                        //     text: 'There was a problem fetching attendance data.',
                        //     type: 'error',
                        //     confirmButtonText: 'OK'
                        // });
                    });
                }).catch(function(error) {
                    console.error('Error fetching student list:', error);
                    Swal.close(); // Ensure the loading indicator is closed
                });
            }

            //assigned attendance left click
            // function handleCellClick() {
            //     var states = ['', 'present', 'absent', 'late'];
            //     var stateTexts = {
            //         'present': 'Present',
            //         'absent': 'Absent',
            //         'late': 'Late',
            //     };
            //     var colors = {
            //         'present': 'green',
            //         'absent': 'red',
            //         'late': 'yellow',
            //     };
            //     var textColors = {
            //         'present': 'white',
            //         'absent': 'white',
            //         'late': 'black'
            //     };

            //     var currentState = $(this).data('state');
            //     var currentIndex = states.indexOf(currentState);
            //     var nextIndex = (currentIndex + 1) % states.length;
            //     var nextState = states[nextIndex];

            //     $(this).data('state', nextState)
            //         .text(stateTexts[nextState])
            //         .css('background-color', colors[nextState])
            //         .css('color', textColors[nextState]);

            //     // Save attendance to local storage
            //     saveAttendanceToLocalStorage();
            // }

            function handleCellClick() {
                var states = ['', 'present', 'absent', 'late'];
                var stateTexts = {
                    'present': 'Present',
                    'absent': 'Absent',
                    'late': 'Late',
                };
                var colors = {
                    'present': 'green',
                    'absent': 'red',
                    'late': 'yellow',
                };
                var textColors = {
                    'present': 'white',
                    'absent': 'white',
                    'late': 'black'
                };

                var currentState = $(this).data('state');
                var currentIndex = states.indexOf(currentState);
                var nextIndex = (currentIndex + 1) % states.length;
                var nextState = states[nextIndex];

                $(this).data('state', nextState)
                    .text(stateTexts[nextState])
                    .css('background-color', colors[nextState])
                    .css('color', textColors[nextState]);

                if (nextState === 'absent') {
                    $('#AbsentModal').modal('show');
                    var dateHeader = $(this).closest('table').find('thead th').eq($(this).index()).text();
                    $('#absent_date').val(dateHeader);
                    // var studentName = $(this).closest('tr').find('td:nth-child(2)').text();
                    // $('#absent_studid').val(studentName);
                    var studid = $(this).closest('tr').find('td:first').text();
                    $('#absent_studid').val(studid);

                    var studname = $(this).closest('tr').find('td:nth-child(2)').text();
                    $('#absent_studname').val(studname);

                }

                // Save attendance to local storage
                saveAttendanceToLocalStorage();
            }

            //assigned attendance righ click
            function handleCellClick(event) {
                if ($(event.target).attr('id') === 'edit-remarks') {
                    $('#AbsentModal').modal('show');
                    $('#edit-remarks').hide('');
                    return false; // Prevent changing the state when clicking the edit icon
                }

                var states = ['', 'present', 'absent', 'late'];
                var stateTexts = {
                    'present': 'Present',
                    'absent': 'Absent',
                    'late': 'Late',
                };
                var colors = {
                    'present': 'green',
                    'absent': 'red',
                    'late': 'yellow',
                };
                var textColors = {
                    'present': 'white',
                    'absent': 'white',
                    'late': 'black'
                };

                var currentState = $(this).data('state');
                var currentIndex = states.indexOf(currentState);
                var nextIndex = (currentIndex + 1) % states.length;
                var nextState = states[nextIndex];

                $(this).data('state', nextState)
                    .text(stateTexts[nextState])
                    .css('background-color', colors[nextState])
                    .css('color', textColors[nextState]);

                if (nextState === 'absent') {
                    // Append the edit icon and properly position it in the center of the element
                    $(this).css('position', 'relative').append(
                        '<i class="fas fa-edit text-primary edit-remarks" id="edit-remarks" data-toggle="tooltip" data-placement="top" title="Add Remark" style="position:absolute; top:-36%; left:50%; transform:translate(-50%, -50%); font-size:1.2em; background-color:white; padding:5px; border-radius:20%;"></i>'
                    );
                    var dateHeader = $(this).closest('table').find('thead th').eq($(this).index()).text();
                    $('#absent_date').val(dateHeader);

                    var studid = $(this).closest('tr').find('td:first').text();
                    $('#absent_studid').val(studid);

                    var studname = $(this).closest('tr').find('td:nth-child(2)').text();
                    $('#absent_studname').val(studname);
                }

                // Save attendance to local storage
                saveAttendanceToLocalStorage();
            }

            //assigned attendance righ click
            function handleCellRightClick(event) {
                if ($(event.target).attr('id') === 'edit-remarks') {
                    $('#AbsentModal').modal('show');
                    $('#edit-remarks').hide('');
                    return false; // Prevent changing the state when clicking the edit icon
                }

                event.preventDefault();

                var states = ['', 'present', 'absent', 'late'];
                var stateTexts = {
                    'present': 'Present',
                    'absent': 'Absent',
                    'late': 'Late',
                };
                var colors = {
                    'present': 'green',
                    'absent': 'red',
                    'late': 'yellow',
                };
                var textColors = {
                    'present': 'white',
                    'absent': 'white',
                    'late': 'black'
                };

                var currentState = $(this).data('state');
                var currentIndex = states.indexOf(currentState);
                var previousIndex = (currentIndex - 1 + states.length) % states.length;
                var previousState = states[previousIndex];

                $(this).data('state', previousState)
                    .text(stateTexts[previousState])
                    .css('background-color', colors[previousState])
                    .css('color', textColors[previousState]);

                if (previousState === 'absent') {
                    // Append the edit icon and properly position it in the center of the element
                    $(this).css('position', 'relative').append(
                        '<i class="fas fa-edit text-primary edit-remarks" id="edit-remarks" data-toggle="tooltip" data-placement="top" title="Add Remark" style="position:absolute; top:-36%; left:50%; transform:translate(-50%, -50%); font-size:1.2em; background-color:white; padding:5px; border-radius:20%;"></i>'
                    );
                    var dateHeader = $(this).closest('table').find('thead th').eq($(this).index()).text();
                    $('#absent_date').val(dateHeader);

                    var studid = $(this).closest('tr').find('td:first').text();
                    $('#absent_studid').val(studid);

                    var studname = $(this).closest('tr').find('td:nth-child(2)').text();
                    $('#absent_studname').val(studname);

                }

                // Save attendance to local storage
                saveAttendanceToLocalStorage();
            }
            //load assigned attendance from loc storage
            function loadAttendanceFromLocalStorage() {
                const syid = $('#sy').val();
                const semid = $('#semester').val();
                const subjectID = $('#rowsubjectID').val();
                const sectionID = $('#section_id').val();
                const selectedMonth = $('#monthsattendance').val(); // Get the selected month

                // Create the dynamic key
                const localStorageKey =
                    `attendanceDatasaved_${syid}_${semid}_${subjectID}_${sectionID}_${selectedMonth}`;

                // Retrieve the stored data from localStorage
                const attendanceData = JSON.parse(localStorage.getItem(localStorageKey)) || {};

                // Loop through the table and populate the cells with the stored attendance data
                $("#studentsAttendanceTable tbody tr").each(function() {
                    const studentId = $(this).find('td').eq(0)
                        .text(); // Assuming the first cell is the student ID

                    if (attendanceData[studentId]) {
                        $(this).find('td').each(function(index) {
                            if (index >= 3) { // Assuming attendance data starts from the 4th cell
                                const state = attendanceData[studentId][index];
                                if (state) {
                                    $(this).data('state', state);
                                    $(this).text(state.charAt(0).toUpperCase() + state.slice(
                                        1)); // Capitalize first letter
                                    $(this).css('background-color', getHoverColor(state));
                                    $(this).css('color', state === 'late' ? 'black' : 'white');
                                }
                            }
                        });
                    }
                });
            }

            //reset assigned attendance
            function resetAllAttendance(selectedMonth) {
                const syid = $('#sy').val();
                const semid = $('#semester').val();
                const subjectID = $('#rowsubjectID').val();
                const sectionID = $('#section_id').val();

                // Create a dynamic key prefix using the provided variables
                const keyPrefix = `attendanceDatasaved_${syid}_${semid}_${subjectID}_${sectionID}_${selectedMonth}`;

                // Check if there is any attendance data in local storage with this key prefix
                let hasData = false;
                for (let i = 0; i < localStorage.length; i++) {
                    let key = localStorage.key(i);
                    if (key.startsWith(keyPrefix)) {
                        hasData = true;
                        break; // Exit loop if any attendance data is found
                    }
                }

                if (hasData) {
                    // Confirm with the user before deleting all attendance data
                    Swal.fire({
                        title: 'Assigned Attendance Reset',
                        // text: 'Are you sure you want to reset all attendance data? This action cannot be undone.',
                        text: 'You have unsaved changes. Would you like to save your work before leaving?',
                        type: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Apply Changes', // Close modal on cancel
                        // confirmButtonColor: '#3085d6',
                        // cancelButtonColor: '#d33',
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Discard Changes',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            // Iterate through all keys in local storage and remove attendance data with the matching key prefix
                            for (let i = localStorage.length - 1; i >= 0; i--) {
                                let key = localStorage.key(i);
                                if (key.startsWith(keyPrefix)) {
                                    localStorage.removeItem(key); // Remove the attendance data
                                }
                            }

                            // Show success message after reset
                            Swal.fire({
                                title: 'Reset Successful',
                                text: 'All attendance data has been reset.',
                                type: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Close the modal after the alert is acknowledged
                                $('#attendancemodal').modal('hide');
                            });
                        } else {
                            // Save attendance data
                            // college_attendance_store();
                            Toast.fire({
                                // title: 'Success!',
                                type: 'success',
                                title: 'Attendance data has been applied.',

                                // confirmButtonText: 'OK'
                            }).then(() => {
                                // Close the modal after saving
                                $('#attendancemodal').modal('hide');
                            });
                        }
                    });
                } else {
                    // No attendance data found, close the modal automatically
                    $('#attendancemodal').modal('hide');
                }
            }

            //calendar
            // function renderCalendar() {
            //     var calendarEl = document.getElementById('calendar');
            //     var addedDates = new Set();

            //     var calendar = new FullCalendar.Calendar(calendarEl, {
            //         timeZone: 'Asia/Manila',
            //         themeSystem: 'bootstrap',
            //         selectable: true,
            //         nowIndicator: true,
            //         dayMaxEvents: true,
            //         aspectRatio: 1.35,
            //         headerToolbar: {
            //             left: 'prev,next today',
            //             center: 'title',
            //             right: 'dayGridMonth,timeGridWeek,timeGridDay',
            //         },
            //         select: function(info) {
            //             var formattedDate = moment(info.startStr).format("YYYY-MM-DD");

            //             if (addedDates.has(formattedDate)) {
            //                 Swal.fire({
            //                     title: 'Date Already Exists',
            //                     text: 'This date has already been added.',
            //                     type: 'info',
            //                     confirmButtonText: 'OK',
            //                 });
            //             } else {
            //                 var displayDate = moment(formattedDate).format("MMMM D, YYYY");
            //                 addDateToAttendanceTable(formattedDate, addedDates, calendar);
            //             }
            //         },
            //         businessHours: {
            //             startTime: '06:00',
            //             endTime: '19:00',
            //         },
            //         customButtons: {
            //             todayButton: {
            //                 text: 'Today',
            //                 click: function() {
            //                     calendar.today();
            //                     Swal.fire({
            //                         title: 'Today',
            //                         text: 'Returned to today\'s date.',
            //                         type: 'info',
            //                         confirmButtonText: 'OK',
            //                     });
            //                 },
            //             },
            //         },
            //         events: function(info, successCallback, failureCallback) {
            //             // Get the dates from the table header
            //             var existingDates = [];
            //             $("#studentsAttendanceTable thead th").not(
            //                 ":first-child, :nth-child(2), :nth-child(3)").each(function() {
            //                 existingDates.push($(this).text().trim());
            //             });

            //             // Convert dates to the format used by the calendar (ISO string)
            //             var events = existingDates.map(function(dateString) {
            //                 // Parse date from table header using the format "MMMM D, YYYY"
            //                 var date = moment(dateString, "MMMM D, YYYY").format("YYYY-MM-DD");

            //                 // Add the date to the addedDates set
            //                 addedDates.add(date); // Ensure the date is added to the set

            //                 return {
            //                     title: 'Already Added',
            //                     start: date, // Convert to YYYY-MM-DD format
            //                     display: 'background',
            //                     backgroundColor: 'green', // Or another color you prefer
            //                     borderColor: 'green',
            //                     classNames: ['already-added'] // Add a custom class for styling
            //                 };
            //             });

            //             successCallback(events);
            //         },
            //     });

            //     calendar.render();

            //     $('.fc-prev-button, .fc-next-button').addClass('btn-sm p-1');
            //     $('.fc-toolbar-title').css('font-size', '20px');
            //     $('.fc-toolbar').css({
            //         'margin': '0',
            //         'padding-top': '0',
            //         'font-size': '12px',
            //     });


            // }

            /////////sakto japun ni pero dili ka select og month
            $('#calendarModal').on('shown.bs.modal', function() {
                let selectedMonth = $('#monthsattendance').val();
                renderCalendar(selectedMonth);
            });

            function renderCalendar(selectedMonth) {
                var calendarEl = document.getElementById('calendar');
                var addedDates = new Set();

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    timeZone: 'Asia/Manila',
                    themeSystem: 'bootstrap',
                    selectable: true,
                    nowIndicator: true,
                    dayMaxEvents: true,
                    aspectRatio: 1.35,
                    headerToolbar: {
                        right: 'dayGridMonth,timeGridWeek,timeGridDay',
                    },
                    initialView: 'dayGridMonth',
                    initialDate: moment().month(selectedMonth - 1).format('YYYY-MM-DD'),
                    selectAllow: function(selectInfo) {
                        return selectInfo.start.getMonth() === parseInt(selectedMonth) - 1;
                    },
                    select: function(info) {
                        var formattedDate = moment(info.startStr).format("YYYY-MM-DD");
                        var displayDate = moment(formattedDate).format("MMMM D, YYYY");
                        addDateToAttendanceTable(formattedDate, addedDates, calendar);
                    },
                    businessHours: {
                        startTime: '06:00',
                        endTime: '19:00',
                    },
                    customButtons: {
                        todayButton: {
                            text: 'Today',
                            click: function() {
                                calendar.today();
                                Swal.fire({
                                    title: 'Today',
                                    text: 'Returned to today\'s date.',
                                    type: 'info',
                                    confirmButtonText: 'OK',
                                });
                            },
                        },
                    },
                    events: function(info, successCallback, failureCallback) {
                        var existingDates = [];
                        $("#studentsAttendanceTable thead th").not(
                            ":first-child, :nth-child(2), :nth-child(3)").each(function() {
                            existingDates.push($(this).text().trim());
                        });

                        var events = existingDates.map(function(dateString) {
                            var date = moment(dateString, "MMMM D, YYYY").format("YYYY-MM-DD");
                            addedDates.add(date);

                            return {
                                start: date,
                                display: 'background',
                                backgroundColor: 'green',
                                borderColor: 'green',
                                textColor: 'blue'
                            };
                        });

                        successCallback(events);
                    },
                    dayCellDidMount: function(arg) {
                        var currentMonth = parseInt(selectedMonth) - 1;
                        var currentYear = moment().year();
                        var cellDate = arg.date;

                        // Disable dates not in the current month
                        if (cellDate.getMonth() !== currentMonth || cellDate.getFullYear() !==
                            currentYear) {
                            arg.el.style.backgroundColor = '#f0f0f0';
                            arg.el.style.opacity = '0.5';
                            // arg.el.style.cursor = 'not-allowed';
                            // arg.el.classList.add('fc-disabled-day');
                            arg.el.style.cursor = 'pointer';
                            arg.el.classList.add('fc-uneditable-day');
                        }


                        var dayOfWeek = cellDate.getDay();
                        if (dayOfWeek === 0 || dayOfWeek === 6) {
                            arg.el.style.backgroundColor = 'darkgray';
                        }
                    }
                    // dateClick: function(info) {
                    //     // Prevent click on disabled days
                    //     if (info.dayEl.classList.contains('fc-disabled-day')) {
                    //         return false;
                    //     }

                    //     var clickedDate = moment(info.date).format("YYYY-MM-DD");
                    //     var currentMonth = parseInt(selectedMonth) - 1;
                    //     var clickedMonth = moment(clickedDate).month();

                    //     //Only allow clicks for the current month
                    //     if (clickedMonth === currentMonth) {
                    //         addDateToAttendanceTable(clickedDate, addedDates, calendar);
                    //     }
                    // }
                });

                calendar.render();

                $('.fc-prev-button, .fc-next-button').addClass('btn-sm p-1');
                $('.fc-toolbar-title').css('font-size', '20px');
                $('.fc-toolbar').css({
                    'margin': '0',
                    'padding-top': '0',
                    'font-size': '12px',
                });

                $('.fc-daygrid-day-number, .fc-col-header-cell-cushion').css('color', 'black');

                return calendar;
            }


            function removeDateFromLocalStorage(dateToRemove, calendar, addedDates) {
                if (!(dateToRemove instanceof Date)) {
                    dateToRemove = new Date(dateToRemove);
                }
                const syid = $('#sy').val();
                const semid = $('#semester').val();
                const subjectID = $('#rowsubjectID').val();
                const sectionID = $('#section_id').val();
                const schedID = $('#for_schedid').val();
                const localStorageKey = `calendarAddedDates_${syid}_${semid}_${subjectID}_${sectionID}`;
                const attendanceDataKey = `attendanceData_${syid}_${semid}_${subjectID}_${sectionID}`;

                let calendarAddedDates = JSON.parse(localStorage.getItem(localStorageKey) || '[]');
                let attendanceData = JSON.parse(localStorage.getItem(attendanceDataKey) || '{}');

                const dateToRemoveISO = new Date(dateToRemove).toISOString().split('T')[0];
                const removedDate = moment(dateToRemove).format('MMMM D, YYYY');

                // Check if the date is in the calendarAddedDates array
                if (!calendarAddedDates.some(date => new Date(date).toISOString().split('T')[0] ===
                        dateToRemoveISO)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Default Date',
                        text: `The date ${removedDate} cannot be removed.`,
                        showConfirmButton: true
                    });
                    return; // Exit the function early
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to remove the date ${removedDate}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, remove it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        // Double-check again before removing
                        if (!attendanceData.hasOwnProperty(dateToRemoveISO)) {
                            // Remove the date from calendarAddedDates
                            calendarAddedDates = calendarAddedDates.filter(date =>
                                new Date(date).toISOString().split('T')[0] !== dateToRemoveISO
                            );

                            localStorage.setItem(localStorageKey, JSON.stringify(calendarAddedDates));

                            // Remove the table columns
                            $(`#studentsAttendanceTable th[data-date="${dateToRemoveISO}"]`).remove();
                            $(`#studentsAttendanceTable td[data-date="${dateToRemoveISO}"]`).remove();

                            // Remove the calendar event
                            calendar.getEvents().forEach(event => {
                                if (event.start.toISOString().split('T')[0] === dateToRemoveISO) {
                                    event.remove();
                                }
                            });

                            // Remove the date from addedDates set
                            addedDates.delete(dateToRemoveISO);

                            $(`.attendance_link2[data-schedid="${schedID}"]`)
                                .trigger('click');
                            Toast.fire({
                                // type: 'success',
                                title: `<i class="fas fa-check-circle" style="color: green;"></i> &nbsp; Date removed.`,
                                // timer: 3000 // Message will disappear after 3 seconds
                            });


                            // setTimeout(() => {
                            //     $(`.attendance_link2[data-schedid="${schedID}"]`)
                            //         .trigger('click');
                            //     Toast.fire({
                            //         type: 'success',
                            //         // title: 'Date Removed',
                            //         // text: `The date ${removedDate} has been successfully removed.`,
                            //         title: `${removedDate} has been successfully removed.`,
                            //         timer: 2000
                            //     });
                            // }, 500);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Cannot Remove Date',
                                text: `The date ${removedDate} has existing attendance data and cannot be removed.`,
                                showConfirmButton: true
                            });
                        }
                    }
                });
            }

            //working code
            // function removeDateFromLocalStorage(dateToRemove, calendar, addedDates) {
            //     if (!(dateToRemove instanceof Date)) {
            //         dateToRemove = new Date(dateToRemove);
            //     }
            //     const syid = $('#sy').val();
            //     const semid = $('#semester').val();
            //     const subjectID = $('#rowsubjectID').val();
            //     const sectionID = $('#section_id').val();
            //     const schedID = $('#for_schedid').val();
            //     const localStorageKey = `calendarAddedDates_${syid}_${semid}_${subjectID}_${sectionID}`;
            //     const attendanceDataKey = `attendanceData_${syid}_${semid}_${subjectID}_${sectionID}`;

            //     let calendarAddedDates = JSON.parse(localStorage.getItem(localStorageKey) || '[]');
            //     let attendanceData = JSON.parse(localStorage.getItem(attendanceDataKey) || '{}');

            //     const dateToRemoveISO = new Date(dateToRemove).toISOString().split('T')[0];
            //     const removedDate = moment(dateToRemove).format('MMMM D, YYYY');

            //     // // Check if the date exists in attendanceData
            //     // if (attendanceData.hasOwnProperty(dateToRemoveISO)) {
            //     //     Swal.fire({
            //     //         icon: 'error',
            //     //         title: 'Cannot Remove Date',
            //     //         text: `The date ${removedDate} has existing attendance data and cannot be removed.`,
            //     //         showConfirmButton: true
            //     //     });
            //     //     return; // Exit the function early
            //     // }

            //     // Check if the date is in the calendarAddedDates array
            //     if (!calendarAddedDates.some(date => new Date(date).toISOString().split('T')[0] ===
            //             dateToRemoveISO)) {
            //         Swal.fire({
            //             icon: 'error',
            //             title: 'Default Date',
            //             text: `The date ${removedDate} cannot be removed.`,
            //             showConfirmButton: true
            //         });
            //         return; // Exit the function early
            //     }

            //     Swal.fire({
            //         title: 'Are you sure?',
            //         text: `Do you want to remove the date ${removedDate}?`,
            //         icon: 'warning',
            //         showCancelButton: true,
            //         confirmButtonText: 'Yes, remove it!',
            //         cancelButtonText: 'Cancel'
            //     }).then((result) => {
            //         if (result.value) {
            //             // Double-check again before removing
            //             if (!attendanceData.hasOwnProperty(dateToRemoveISO)) {
            //                 // Remove the date from calendarAddedDates
            //                 calendarAddedDates = calendarAddedDates.filter(date =>
            //                     new Date(date).toISOString().split('T')[0] !== dateToRemoveISO
            //                 );

            //                 localStorage.setItem(localStorageKey, JSON.stringify(calendarAddedDates));

            //                 // Remove the table columns
            //                 $(`#studentsAttendanceTable th[data-date="${dateToRemoveISO}"]`).remove();
            //                 $(`#studentsAttendanceTable td[data-date="${dateToRemoveISO}"]`).remove();

            //                 // Remove the calendar event
            //                 calendar.getEvents().forEach(event => {
            //                     if (event.start.toISOString().split('T')[0] === dateToRemoveISO) {
            //                         event.remove();
            //                     }
            //                 });

            //                 // Remove the date from addedDates set
            //                 addedDates.delete(dateToRemoveISO);

            //                 Swal.fire({
            //                     icon: 'success',
            //                     title: 'Date Removed',
            //                     text: `The date ${removedDate} has been successfully removed.`,
            //                     showConfirmButton: true,
            //                     timer: 1000
            //                 }).then(() => {
            //                     // $('#attendancemodal').modal('hide');
            //                     // setTimeout(() => {
            //                     //     fetchAddedDates();
            //                     //     $('#closeModalBtn').trigger('click');
            //                     setTimeout(() => {
            //                         $(`.attendance_link[data-schedid="${schedID}"]`)
            //                             .trigger('click');
            //                     }, 500);
            //                     // }, 500);
            //                 });
            //             } else {
            //                 Swal.fire({
            //                     icon: 'error',
            //                     title: 'Cannot Remove Date',
            //                     text: `The date ${removedDate} has existing attendance data and cannot be removed.`,
            //                     showConfirmButton: true
            //                 });
            //             }
            //         }
            //     });
            // }

            // function removeDateFromLocalStorage(dateToRemove, calendar, addedDates) {

            //     if (!(dateToRemove instanceof Date)) {
            //         dateToRemove = new Date(dateToRemove);
            //     }
            //     const syid = $('#sy').val();
            //     const semid = $('#semester').val();
            //     const subjectID = $('#rowsubjectID').val();
            //     const sectionID = $('#section_id').val();
            //     const localStorageKey = `calendarAddedDates_${syid}_${semid}_${subjectID}_${sectionID}`;
            //     let calendarAddedDates = JSON.parse(localStorage.getItem(localStorageKey) || '[]');

            //     const dateToRemoveISO = new Date(dateToRemove).toISOString().split('T')[0];

            //     const removedDate = moment(dateToRemove).format('MMMM D, YYYY');

            //     Swal.fire({
            //         title: 'Are you sure?',
            //         text: `Do you want to remove the date ${removedDate}?`,
            //         icon: 'warning',
            //         showCancelButton: true,
            //         confirmButtonText: 'Yes, remove it!',
            //         cancelButtonText: 'Cancel'
            //     }).then((result) => {
            //         if (result.value) {


            //             CalendarAddedDates = calendarAddedDates.filter(date =>
            //                 new Date(date).toISOString().split('T')[0] !== dateToRemoveISO
            //             );

            //             localStorage.setItem(localStorageKey, JSON.stringify(CalendarAddedDates));

            //             $(`#studentsAttendanceTable th[data-date="${dateToRemoveISO}"]`).remove();
            //             // $(`#studentsAttendanceTable td[data-date="${dateToRemoveISO}"]`).remove();



            //             Swal.fire({
            //                 icon: 'success',
            //                 title: 'Date Removed',
            //                 text: `The date ${removedDate} has been successfully removed.`,
            //                 showConfirmButton: true,
            //                 timer: 2000
            //             }).then(() => {
            //                 $('#attendancemodal').modal('hide');
            //                 setTimeout(() => {
            //                     fetchAddedDates();
            //                     $('#closeModalBtn').trigger('click');
            //                     setTimeout(() => {
            //                         $(`.attendance_link[data-sectionid="${sectionID}"]`)
            //                             .trigger('click');
            //                     }, 500);
            //                 }, 500);
            //             });

            //             // calendar.getEvents().forEach(event => {
            //             //     if (event.start.toISOString().split('T')[0] === dateToRemoveISO) {
            //             //         event.remove();
            //             //     }
            //             // });

            //             // Remove the date from addedDates set
            //             addedDates.delete(dateToRemoveISO);

            //             // Refresh the calendar to reflect the changes
            //             calendar.refetchEvents();
            //         }
            //     });
            // }





            var addedDates = new Set(); // Initialize a set to keep track of added dates

            function addDateToAttendanceTable(date, addedDates, calendar) {
                if (!(date instanceof Date)) {
                    date = new Date(date);
                }

                var formattedDate = date.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                var dateISO = date.toISOString().split('T')[0];

                if (addedDates.has(dateISO)) {
                    removeDateFromLocalStorage(date, calendar, addedDates);
                } else {
                    Swal.fire({
                        title: 'Add Date to Attendance',
                        text: `Are you sure you want to add ${formattedDate} to the attendance table?`,
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, add it!',
                        cancelButtonText: 'No, cancel'
                    }).then((result) => {
                        if (result.value) {
                            addColumnHeaderAndIcon('#studentsAttendanceTable', date);
                            addCellsToRows('#studentsAttendanceTable', date);
                            storeCalendarAddedDate(date);

                            setDefaultAttendanceToPresent(date);

                            var id = $('#rowsubjectID').val();
                            var sectionid = $('#section_id').val();
                            var month = date.getMonth() + 1;
                            var year = date.getFullYear();

                            // Toast.fire({
                            //     type: 'success',
                            //     title: `${formattedDate} has been added to the attendance table.`
                            // });

                            addedDates.add(dateISO);
                            calendar.addEvent({
                                start: date,
                                display: 'background',
                                backgroundColor: 'green',
                                borderColor: 'green'
                            });
                            calendar.unselect();
                            calendar.refetchEvents();
                            renderCalendar();

                        }
                    });
                }
            }

            // Function to automatically set "Present" attendance for all students for a newly added date
            function setDefaultAttendanceToPresent(date) {
                const newDateFormatted = moment(date).format('MMMM D, YYYY');
                const attendanceState = 'present'; // Default state to "Present"

                $("#studentsAttendanceTable tbody tr").each(function() {
                    var studentId = $(this).find("td:eq(0)").text()
                        .trim(); // Assuming studentId is in the first column

                    // Skip rows that are gender headers or empty
                    if (/Students/.test(studentId) || studentId === "") {
                        return true; // Skip header rows
                    }

                    // Find the cell corresponding to the new date and set its attendance state to "Present"
                    $(this).find("td").last().attr('data-state', attendanceState).text('Present').css(
                        'background-color', '#c8e6c9'); // Green background for "Present"
                });

                // Automatically save updated attendance data for the new date
                college_attendance_store();
            }

            // Function to store added dates in localStorage
            function storeCalendarAddedDate(date) {
                const syid = $('#sy').val();
                const semid = $('#semester').val();
                const subjectID = $('#rowsubjectID').val();
                const sectionID = $('#section_id').val();
                const localStorageKey = `calendarAddedDates_${syid}_${semid}_${subjectID}_${sectionID}`;
                let calendarAddedDates = JSON.parse(localStorage.getItem(localStorageKey) || '[]');
                calendarAddedDates.push(date.toISOString()); // Store full ISO string
                localStorage.setItem(localStorageKey, JSON.stringify([...new Set(calendarAddedDates)]));
            }

            // Function to fetch and list added dates in the modal
            function fetchAddedDates() {
                const syid = $('#sy').val();
                const semid = $('#semester').val();
                const subjectID = $('#rowsubjectID').val();
                const sectionID = $('#section_id').val();
                const localStorageKey = `calendarAddedDates_${syid}_${semid}_${subjectID}_${sectionID}`;
                let calendarAddedDates = JSON.parse(localStorage.getItem(localStorageKey) || '[]');

                // Clear the modal body before appending new data
                $('#addedDatesModal .modal-body').empty();

                if (calendarAddedDates.length > 0) {
                    // Append each date to the modal body
                    calendarAddedDates.forEach(function(date, index) {
                        // Format date using moment.js to "MMMM D, YYYY"
                        let formattedDate = moment(date).format('MMMM D, YYYY');
                        // Append date with trash icon
                        $('#addedDatesModal .modal-body').append(`
						<div class="d-flex justify-content-between align-items-center mb-2">
							<p class="mb-0">${formattedDate}</p>
							<i class="fas fa-trash-alt text-danger cursor-pointer" data-index="${index}"></i>
						</div>
					`);
                    });
                    ///////////////////////////ayaw kalimti
                    // Add click event to trash icons
                    // $('#addedDatesModal .fa-trash-alt').on('click', function() {
                    //     const index = $(this).data('index');
                    //     removeDateFromLocalStorage(index);
                    // });
                } else {
                    // If no dates are stored, display a message
                    $('#addedDatesModal .modal-body').append('<p>No dates added yet.</p>');
                }
            }

            // Function to remove date from localStorage with Swal confirmation
            // function removeDateFromLocalStorage(index) {
            //     const syid = $('#sy').val();
            //     const semid = $('#semester').val();
            //     const subjectID = $('#rowsubjectID').val();
            //     const sectionID = $('#section_id').val(); // Store the current section ID being used
            //     const localStorageKey = `calendarAddedDates_${syid}_${semid}_${subjectID}_${sectionID}`;
            //     let calendarAddedDates = JSON.parse(localStorage.getItem(localStorageKey) || '[]');

            //     // Format the date to display in confirmation
            //     const removedDate = moment(calendarAddedDates[index]).format('MMMM D, YYYY');

            //     // Show Swal confirmation dialog
            //     Swal.fire({
            //         title: 'Are you sure?',
            //         text: `Do you want to remove the date ${removedDate}?`,
            //         icon: 'warning',
            //         showCancelButton: true,
            //         confirmButtonText: 'Yes, remove it!',
            //         cancelButtonText: 'Cancel'
            //     }).then((result) => {
            //         if (result.value) {
            //             // Remove the selected date from the array
            //             calendarAddedDates.splice(index, 1);

            //             // Update localStorage with the new array
            //             localStorage.setItem(localStorageKey, JSON.stringify(calendarAddedDates));

            //             // Show success message
            //             Swal.fire({
            //                 icon: 'success',
            //                 title: 'Date Removed',
            //                 text: `The date ${removedDate} has been successfully removed.`,
            //                 showConfirmButton: true,
            //                 timer: 2000
            //             }).then(() => {
            //                 // Close the modal first
            //                 $('#attendancemodal').modal('hide');

            //                 // Add a slight delay to ensure the modal closes completely
            //                 setTimeout(() => {
            //                     fetchAddedDates(); // Fetch updated dates
            //                     // Trigger the click event to reset attendance
            //                     $('#closeModalBtn').trigger('click');

            //                     // Reopen the modal with the same sectionID after the reset is done
            //                     setTimeout(() => {
            //                             // Find the attendance link with the corresponding sectionID
            //                             $('.attendance_link[data-sectionid="' +
            //                                 sectionID + '"]').trigger('click');
            //                         },
            //                         500
            //                     ); // Optional delay to give time for any reset process
            //                 }, 500); // Delay to ensure modal has closed properly
            //             });
            //         }
            //     });
            // }

            //add dates to table
            function addColumnHeaderAndIcon(tableSelector, date) {
                var options = {
                    month: 'long',
                    day: 'numeric'
                };
                var formattedDate = date.toLocaleDateString('en-PH', options);
                var dateString = date.toISOString().split('T')[0]; // YYYY-MM-DD format

                // Add the date header
                var dateHeader = $('<th>', {
                    'class': 'text-center date',
                    'style': 'position: relative;',
                    'data-date': dateString,
                    'text': formattedDate
                });

                // Add the icon cell
                var iconCell = $('<td>', {
                    'class': 'text-center',
                    'style': 'position: relative;'
                }).append(
                    $('<button>', {
                        'class': 'column-dropdown-arrow',
                        'data-date': dateString
                    }).html('&#9660;') // Down arrow character
                );

                // Append the new header to the first row (date row)
                $(`${tableSelector} thead tr:first-child`).append(dateHeader);

                // Append the new icon cell to the second row (icon row)
                $(`${tableSelector} thead tr:nth-child(2)`).append(iconCell);
            }

            //add present default to every attendance of dates added through calendar
            function addCellsToRows(tableSelector, date) {
                $(`${tableSelector} tbody tr`).each(function() {
                    if ($(this).find('td[colspan]').length > 0) {
                        var backgroundColor = $(this).find('td').css('background-color');
                        $('<td>', {
                            'style': `background-color: ${backgroundColor};`
                        }).appendTo(this);
                    } else {
                        var newCell = $('<td>', {
                            'class': 'text-center attendance-cell calendar-added',
                            'data-state': 'present',
                            'text': 'Present',
                            'style': 'background-color: green; color: white; cursor: pointer;'
                        });

                        newCell.on('click', handleCalendarCellClick);
                        newCell.on('contextmenu', handleCalendarCellRightClick);

                        newCell.appendTo(this);
                    }
                });
            }

            // function addColumnHeaderAndIcon(tableSelector, date) {
            //     var options = {
            //         month: 'long',
            //         day: 'numeric'
            //     };
            //     var formattedDate = date.toLocaleDateString('en-PH', options);
            //     var dateString = date.toISOString().split('T')[0]; // YYYY-MM-DD format

            //     // Add the date header
            //     var dateHeader = $('<th>', {
            //         'class': 'text-center date',
            //         'style': 'position: relative;',
            //         'data-date': dateString,
            //         'text': formattedDate
            //     });

            //     // Add the icon cell
            //     var iconCell = $('<td>', {
            //         'class': 'text-center',
            //         'style': 'position: relative;'
            //     }).append(
            //         $('<button>', {
            //             'class': 'column-dropdown-arrow',
            //             'data-date': dateString
            //         }).html('&#9660;') // Down arrow character
            //     );

            //     // Append the new header to the first row (date row)
            //     $(`${tableSelector} thead tr:first-child`).append(dateHeader);

            //     // Append the new icon cell to the second row (icon row)
            //     $(`${tableSelector} thead tr:nth-child(2)`).append(iconCell);
            // }

            // //add present default to every attendance of dates added through calendar
            // function addCellsToRows(tableSelector, date) {
            //     $(`${tableSelector} tbody tr`).each(function() {
            //         if ($(this).find('td[colspan]').length > 0) {
            //             var backgroundColor = $(this).find('td').css('background-color');
            //             $('<td>', {
            //                 'style': `background-color: ${backgroundColor};`
            //             }).appendTo(this);
            //         } else {
            //             var newCell = $('<td>', {
            //                 'class': 'text-center attendance-cell calendar-added',
            //                 'data-state': 'present',
            //                 'text': 'Present',
            //                 'style': 'background-color: green; color: white; cursor: pointer;'
            //             });

            //             newCell.on('click', handleCalendarCellClick);
            //             newCell.on('contextmenu', handleCalendarCellRightClick);

            //             newCell.appendTo(this);
            //         }
            //     });
            // }

            //assigning attendance 
            function handleCalendarCellClick() {
                updateCalendarCellState($(this), 'next');
                triggerAutoSaveForCell($(this));
            }

            function handleCalendarCellRightClick(e) {
                e.preventDefault();
                updateCalendarCellState($(this), 'previous');
                triggerAutoSaveForCell($(this));
            }

            function triggerAutoSaveForCell($cell) {
                var id = $('#rowsubjectID').val();
                var sectionid = $('#section_id').val();
                var dateString = $cell.closest('tr').find('th').data('date');
                var date = new Date(dateString);
                autoSaveAttendance(id, sectionid, date.getMonth() + 1, date.getFullYear(), date.getDate());
            }

            function updateCalendarCellState($cell, direction) {
                var states = ['present', 'late', 'absent'];
                var stateTexts = {
                    'present': 'Present',
                    'late': 'Late',
                    'absent': 'Absent'
                };
                var colors = {
                    'present': 'green',
                    'late': 'yellow',
                    'absent': 'red'
                };
                var textColors = {
                    'present': 'white',
                    'late': 'black',
                    'absent': 'white'
                };

                var currentState = $cell.data('state') || 'present';
                var currentIndex = states.indexOf(currentState);
                var newIndex;

                if (direction === 'next') {
                    newIndex = (currentIndex + 1) % states.length;
                } else {
                    newIndex = (currentIndex - 1 + states.length) % states.length;
                }

                var newState = states[newIndex];

                $cell.data('state', newState)
                    .text(stateTexts[newState])
                    .css('background-color', colors[newState])
                    .css('color', textColors[newState]);
            }

            // Update existing auto-date cells
            function updateAutoCellState($cell, direction) {
                var states = ['present', 'late'];
                var stateTexts = {
                    'present': 'Present',
                    'late': 'Late'
                };
                var colors = {
                    'present': 'green',
                    'late': 'yellow'
                };
                var textColors = {
                    'present': 'white',
                    'late': 'black'
                };

                var currentState = $cell.data('state') || 'present';
                var newState = (currentState === 'present') ? 'late' : 'present';

                $cell.data('state', newState)
                    .text(stateTexts[newState])
                    .css('background-color', colors[newState])
                    .css('color', textColors[newState]);
            }

            // Event handlers for auto-date cells
            $('#studentsAttendanceTable').on('click', '.attendance-cell:not(.calendar-added)', function() {
                updateAutoCellState($(this), 'next');
            });

            $('#studentsAttendanceTable').on('contextmenu', '.attendance-cell:not(.calendar-added)', function(e) {
                e.preventDefault();
                updateAutoCellState($(this), 'previous');
            });

            // Event handlers for calendar-added cells
            $('#studentsAttendanceTable').on('click', '.attendance-cell.calendar-added', handleCalendarCellClick);
            $('#studentsAttendanceTable').on('contextmenu', '.attendance-cell.calendar-added',
                handleCalendarCellRightClick);

            // Function to save attendance data to the server
            function college_attendance_store() {
                var syid = $('#sy').val();
                var semid = $('#semester').val();
                var subjectID = $('#rowsubjectID').val();
                var sectionID = $('#section_id').val();
                var attendanceData = [];

                $("#studentsAttendanceTable tbody tr").each(function() {
                    var studentId = $(this).find("td:eq(0)").text()
                        .trim(); // Assuming studentId is in the first column
                    var attendance = {};

                    // Skip rows that are gender headers or empty
                    if (/Students/.test(studentId) || studentId === "") {
                        return true; // This is equivalent to 'continue' in a loop
                    }

                    $(this).find("td").not(":first-child, :nth-child(2), :nth-child(3)").each(function(
                        index) {
                        var dateHeader = $("#studentsAttendanceTable thead th").eq(index + 3)
                            .text();
                        var state = $(this).data("state");

                        // Parse the date correctly
                        var dateParts = dateHeader.split(' ');
                        var day = dateParts[1].replace(',', '');
                        var month = dateParts[0]; // Get the month name

                        // Use the day number directly from the parsed date
                        attendance[`day${day}`] = state === 'present' ? 1 : state === 'absent' ? 3 :
                            2; // Present = 1, Absent = 0, Late = 2
                    });

                    // Check for days that do not exist in the date header
                    for (var dayIndex = 1; dayIndex <= 31; dayIndex++) {
                        if (!attendance[`day${dayIndex}`]) {
                            attendance[`day${dayIndex}`] = 0; // Set state to 0 if no date exists
                        }
                    }

                    // Use the parsed date for month and year
                    var firstDate = $("#studentsAttendanceTable thead th").eq(3).text();
                    var dateParts = firstDate.split(' ');
                    var monthId = moment().month(dateParts[0]).format(
                        "M"); // Get month ID from the month name
                    var yearId = new Date().getFullYear(); // Use current year

                    attendanceData.push({
                        studentId: studentId,
                        attendance: attendance,
                        monthid: parseInt(monthId),
                        yearid: parseInt(yearId) // Ensure year is correctly set
                    });
                });

                // Save to local storage
                var localStorageKey = `attendanceData_${syid}_${semid}_${subjectID}_${sectionID}`;
                localStorage.setItem(localStorageKey, JSON.stringify(attendanceData));

                // Retrieve and log the data from localStorage (optional)
                var savedData = JSON.parse(localStorage.getItem(localStorageKey));
                console.log("Saved Data:", savedData);

                // Send data to the server
                $.ajax({
                    url: "/collegeattendancestore/", // Adjust to your Laravel route
                    type: 'GET', // Use POST for data insertion
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        data: attendanceData,
                        syid: syid,
                        semid: semid,
                        subjectid: subjectID,
                        sectionid: sectionID
                    },
                    success: function(response) {
                        // Iterate through all keys in local storage and remove attendance data
                        for (var i = localStorage.length - 1; i >= 0; i--) {
                            var key = localStorage.key(i);
                            // Check if the key matches the attendance data pattern
                            if (key.startsWith('attendanceDatasaved_')) {
                                localStorage.removeItem(key); // Remove the attendance data
                            }
                        }
                        Toast.fire({
                            type: 'success',
                            title: 'Attendance data has been saved.'
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was a problem saving attendance data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }

            // function college_attendance_store() {
            //     var syid = $('#sy').val();
            //     var semid = $('#semester').val();
            //     var subjectID = $('#rowsubjectID').val();
            //     var sectionID = $('#section_id').val();
            //     var attendanceData = [];

            //     $("#studentsAttendanceTable tbody tr").each(function() {
            //         var studentId = $(this).find("td:eq(0)").text()
            //             .trim(); // Assuming studentId is in the first column
            //         var attendance = {};

            //         // Skip rows that are gender headers or empty
            //         if (/Students/.test(studentId) || studentId === "") {
            //             return true; // This is equivalent to 'continue' in a loop
            //         }

            //         $(this).find("td").not(":first-child, :nth-child(2), :nth-child(3)").each(function(
            //             index) {
            //             var dateHeader = $("#studentsAttendanceTable thead th").eq(index + 3)
            //                 .text();
            //             var state = $(this).data("state");

            //             // Parse the date correctly
            //             // var dateParts = dateHeader.split(' ');
            //             // var day = dateParts[1].replace(',', '');
            //             var dateParts = dateHeader.split(' ');
            //             var day = dateParts[1].replace(',', '');
            //             var month = dateParts[0]; // Get the month name

            //             // Use the day number directly from the parsed date
            //             attendance[`day${day}`] = state === 'present' ? 1 : state === 'absent' ? 3 :
            //                 2; // Present = 1, Absent = 0, Late = 2
            //         });

            //         // Use the parsed date for month and year
            //         var firstDate = $("#studentsAttendanceTable thead th").eq(3).text();
            //         var dateParts = firstDate.split(' ');
            //         var monthId = moment().month(dateParts[0]).format(
            //             "M"); // Get month ID from the month name
            //         var yearId = new Date().getFullYear(); // Use current year

            //         attendanceData.push({
            //             studentId: studentId,
            //             attendance: attendance,
            //             monthid: parseInt(monthId),
            //             yearid: parseInt(yearId) // Ensure year is correctly set
            //         });
            //     });

            //     // Save to local storage
            //     var localStorageKey = `attendanceData_${syid}_${semid}_${subjectID}_${sectionID}`;
            //     localStorage.setItem(localStorageKey, JSON.stringify(attendanceData));

            //     // Retrieve and log the data from localStorage (optional)
            //     var savedData = JSON.parse(localStorage.getItem(localStorageKey));
            //     console.log("Saved Data:", savedData);

            //     // Send data to the server
            //     $.ajax({
            //         url: "/collegeattendancestore/", // Adjust to your Laravel route
            //         type: 'GET', // Use POST for data insertion
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         data: {
            //             data: attendanceData,
            //             syid: syid,
            //             semid: semid,
            //             subjectid: subjectID,
            //             sectionid: sectionID
            //         },
            //         success: function(response) {
            //             // Iterate through all keys in local storage and remove attendance data
            //             for (var i = localStorage.length - 1; i >= 0; i--) {
            //                 var key = localStorage.key(i);
            //                 // Check if the key matches the attendance data pattern
            //                 if (key.startsWith('attendanceDatasaved_')) {
            //                     localStorage.removeItem(key); // Remove the attendance data
            //                 }
            //             }
            //             Toast.fire({
            //                 type: 'success',
            //                 title: 'Attendance data has been saved.'
            //             });
            //         },
            //         error: function(error) {
            //             Swal.fire({
            //                 title: 'Error!',
            //                 text: 'There was a problem saving attendance data.',
            //                 icon: 'error',
            //                 confirmButtonText: 'OK'
            //             });
            //         }
            //     });
            // }

            //auto save attendance to all months
            function autoSaveAttendance(id, sectionid, month, year, specificDay = null) {
                console.log('Auto-saving attendance for month:', month, 'day:', specificDay);
                var syid = $('#sy').val();
                var semid = $('#semester').val();
                var attendanceData = [];

                $("#studentsAttendanceTable tbody tr").each(function() {
                    var studentId = $(this).find("td:eq(0)").text().trim();

                    // Skip rows that are gender headers or empty
                    if (/Students/.test(studentId) || studentId === "") {
                        return true;
                    }

                    var attendance = {};

                    if (specificDay) {
                        // If a specific day is provided, only save data for that day
                        var cell = $(this).find("td").eq(specificDay +
                            2); // +2 to account for ID and name columns
                        var state = cell.data("state") || 'present';
                        attendance[`day${specificDay}`] = state === 'present' ? 1 : state === 'absent' ? 3 :
                            2;
                    } else {
                        // If no specific day, save data for all days in the month
                        $(this).find("td").not(":first-child, :nth-child(2), :nth-child(3)").each(function(
                            index) {
                            var dateHeader = $("#studentsAttendanceTable thead th").eq(index + 3)
                                .text();
                            var state = $(this).data("state") || 'present';
                            var day = dateHeader.split(' ')[1].replace(',', '');
                            attendance[`day${day}`] = state === 'present' ? 1 : state === 'absent' ?
                                3 : 2;
                        });
                    }

                    attendanceData.push({
                        studentId: studentId,
                        attendance: attendance,
                        monthid: parseInt(month),
                        yearid: parseInt(year)
                    });
                });

                // Save to local storage
                var localStorageKey = `attendanceData_${syid}_${semid}_${id}_${sectionid}`;
                localStorage.setItem(localStorageKey, JSON.stringify(attendanceData));

                // Send data to the server
                $.ajax({
                    url: "/collegeattendancestore/",
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        data: attendanceData,
                        syid: syid,
                        semid: semid,
                        subjectid: id,
                        sectionid: sectionid
                    },
                    success: function(response) {
                        loadAttendanceFromLocalStorage();
                        console.log('Auto-saved attendance data for month:', month, 'day:',
                            specificDay);
                        // Consider adding a subtle UI notification here
                    },
                    error: function(error) {
                        console.error('Error auto-saving attendance data:', error);
                        // Consider adding a subtle error notification to the UI
                    }
                });
            }

            // Event handlers for auto-date cells
            $(document).on('click', '#save_absentRemarks', function() {
                absent_remarks_store();
            });

            // $(document).on('mouseover', '.attday', function() {
            //     var selected_day = $(this).attr('data-day');
            //     console.log('Mouseover on auto-date cell:', selected_day);
            //     // $(this).css('background-color', 'yellow');
            // });

            // $(document).on('mouseover', '.attday', function() {
            //     var selected_day = $(this).attr('data-day');
            //     var selected_month = $(this).attr('data-month');
            //     var prevBgColor = $(this).css('background-color');
            //     var selected_year = $(this).attr('data-year');

            //     $(this).data('prevBgColor', prevBgColor);

            //     var syid = $('#sy').val();
            //     var semid = $('#semester').val();
            //     var subjectID = $('#rowsubjectID').val();
            //     var sectionID = $('#section_id').val();
            //     var studid = $(this).attr('data-sid');

            //     console.log('Mouseover on auto-date cell:', selected_day, 'Month:', selected_month, 'Year:',
            //         selected_year, 'Studid:', studid);

            //     absentremarks()

            //     function absentremarks() {
            //         $.ajax({
            //             url: "/absentremarkfetch/",
            //             type: 'GET',
            //             headers: {
            //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //             },
            //             data: {
            //                 studid: studid,
            //                 syid: syid,
            //                 semid: semid,
            //                 subjectid: subjectID,
            //                 sectionid: sectionID,
            //                 day: selected_day,
            //                 monthid: selected_month,
            //                 yearid: selected_year
            //             },
            //             success: function(response) {
            //                 console.log('Fetched absent remarks:', response);
            //                 var absent_remarks = response.absent_remarks;

            //                 if (absent_remarks.day == selected_day) {
            //                     Toast.fire({
            //                         icon: 'info',
            //                         title: `Absent remarks: ${absent_remarks.remarks}`
            //                     });
            //                 }
            //             },
            //             error: function(error) {
            //                 console.error('Error fetching absent remarks:', error);
            //             }
            //         });
            //     }
            // });

            $(document).on('mouseover', '.attday', function() {
                var $this = $(this); // Store reference to the current element
                var selected_day = $(this).attr('data-day');
                var selected_month = $(this).attr('data-month');
                var prevBgColor = $(this).css('background-color');
                var selected_year = $(this).attr('data-year');

                $(this).data('prevBgColor', prevBgColor);

                var syid = $('#sy').val();
                var semid = $('#semester').val();
                var subjectID = $('#rowsubjectID').val();
                var sectionID = $('#section_id').val();
                var studid = $(this).attr('data-sid');

                console.log('Mouseover on auto-date cell:', selected_day, 'Month:', selected_month, 'Year:',
                    selected_year, 'Studid:', studid);

                absentremarks()

                function absentremarks() {
                    $.ajax({
                        url: "/absentremarkfetch/",
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            studid: studid,
                            syid: syid,
                            semid: semid,
                            subjectid: subjectID,
                            sectionid: sectionID,
                            day: selected_day,
                            monthid: selected_month,
                            yearid: selected_year
                        },
                        success: function(response) {
                            console.log('Fetched absent remarks:', response);
                            var absent_remarks = response.absent_remarks;
                            var remarks = '';
                            if (absent_remarks.length > 0) {
                                absent_remarks.forEach(function(remark) {
                                    if (remark.day == selected_day) {
                                        remarks += remark.remarks + '\n';
                                    }
                                });

                                // Remove existing tooltips to avoid duplication
                                $this.tooltip('dispose').removeAttr('title');

                                $this.attr('data-original-title', `${remarks}`)
                                    .tooltip({
                                        placement: 'top',
                                        template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
                                    }).tooltip('show');

                                setTimeout(function() {
                                    $this.tooltip('hide');
                                }, 1000);
                            }
                        },
                        error: function(error) {
                            console.error('Error fetching absent remarks:', error);
                        }
                    });
                }
            });

            // $(document).on('mouseout', '.attday', function() {
            //     var prevBgColor = $(this).data('prevBgColor');
            //     console.log('Mouseout on auto-date cell:', prevBgColor);
            //     $(this).css('background-color', prevBgColor);
            // });


            // $(document).on('mouseout', '.attday', function() {
            //     $(this).css('background-color', '');
            // });

            // function absent_remarks_store() {

            //     var syid = $('#sy').val();
            //     var semid = $('#semester').val();
            //     var subjectID = $('#rowsubjectID').val();
            //     var sectionID = $('#section_id').val();
            //     var remarks = $('#absent_remarks').val();
            //     var studid = $('#absent_studid').val();

            //     // var firstDate = $(this).closest('table').find('thead th').eq($(this).index()).text();
            //     var firstDate = $('#absent_date').val();
            //     var dateParts = firstDate.split(' ');
            //     var monthId = moment().month(dateParts[0]).format(
            //         "M"); // Get month ID from the month name
            //     var yearId = new Date().getFullYear(); // Use current year
            //     // console.log('Clicked on auto-date cell:', firstDate, 'monthId:', monthId, 'yearId:', yearId);


            // }

            // function absent_remarks_store() {

            //     var syid = $('#sy').val();
            //     var semid = $('#semester').val();
            //     var subjectID = $('#rowsubjectID').val();
            //     var sectionID = $('#section_id').val();
            //     var remarks = $('#absent_remarks').val();
            //     var studid = $('#absent_studid').val();

            //     // var firstDate = $(this).closest('table').find('thead th').eq($(this).index()).text();
            //     var firstDate = $('#absent_date').val();
            //     var dateParts = firstDate.split(' ');
            //     var monthId = moment().month(dateParts[0]).format(
            //         "M"); // Get month ID from the month name
            //     var yearId = new Date().getFullYear(); // Use current year
            //     console.log('Clicked on auto-date cell:', firstDate, 'monthId:', monthId, 'yearId:', yearId);

            //     $.ajax({
            //         url: "/absentremarkstore/", // Adjust to your Laravel route
            //         type: 'GET', // Use POST for data insertion
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         data: {
            //             syid: syid,
            //             semid: semid,
            //             subjectid: subjectID,
            //             sectionid: sectionID,
            //             remarks: remarks,
            //             studid: studid,
            //             monthid: monthId,
            //             yearid: yearId
            //         },
            //         success: function(response) {

            //             Toast.fire({
            //                 type: 'success',
            //                 title: 'Absent Remarks has been saved.'
            //             });
            //         },
            //         error: function(error) {
            //             Swal.fire({
            //                 title: 'Error!',
            //                 text: 'There was a problem saving attendance data.',
            //                 icon: 'error',
            //                 confirmButtonText: 'OK'
            //             });
            //         }
            //     });

            // }

            function absent_remarks_store() {

                var syid = $('#sy').val();
                var semid = $('#semester').val();
                var subjectID = $('#rowsubjectID').val();
                var sectionID = $('#section_id').val();
                var remarks = $('#absent_remarks').val();
                var studid = $('#absent_studid').val();

                // var firstDate = $(this).closest('table').find('thead th').eq($(this).index()).text();
                var firstDate = $('#absent_date').val();
                var dateParts = firstDate.split(' ');
                var day = 'day' + dateParts[1]; // Get the day, e.g. "day1", "day2", etc.
                var monthId = moment().month(dateParts[0]).format("M"); // Get month ID from the month name
                var yearId = new Date().getFullYear(); // Use current year
                console.log('Clicked on auto-date cell:', firstDate, 'day:', day, 'monthId:', monthId,
                    'yearId:',
                    yearId);

                $.ajax({
                    url: "/absentremarkstore/", // Adjust to your Laravel route
                    type: 'GET', // Use POST for data insertion
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        syid: syid,
                        semid: semid,
                        subjectid: subjectID,
                        sectionid: sectionID,
                        remarks: remarks,
                        studid: studid,
                        day: day,
                        monthid: monthId,
                        yearid: yearId
                    },
                    success: function(response) {

                        $('#AbsentModal').modal('hide');
                        $('#absent_remarks').val('');

                        Toast.fire({
                            type: 'success',
                            title: 'Absent Remarks has been saved.'
                        });

                    },
                    error: function(error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was a problem saving attendance data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });

            }








            // Function to save attendance data to the server
            // function college_attendance_store() {
            //     var syid = $('#sy').val();
            //     var semid = $('#semester').val();
            //     var subjectID = $('#rowsubjectID').val();
            //     var sectionID = $('#section_id').val();
            //     var attendanceData = [];

            //     $("#studentsAttendanceTable tbody tr").each(function() {
            //         var studentId = $(this).find("td:eq(0)").text()
            //             .trim(); // Assuming studentId is in the first column
            //         var attendance = {};

            //         // Skip rows that are gender headers or empty
            //         if (/Students/.test(studentId) || studentId === "") {
            //             return true; // This is equivalent to 'continue' in a loop
            //         }

            //         $(this).find("td").not(":first-child, :nth-child(2), :nth-child(3)").each(function(
            //             index) {
            //             var dateHeader = $("#studentsAttendanceTable thead th").eq(index + 3)
            //                 .text();
            //             var state = $(this).data("state");

            //             // Parse the date correctly
            //             // var dateParts = dateHeader.split(' ');
            //             // var day = dateParts[1].replace(',', '');
            //             var dateParts = dateHeader.split(' ');
            //             var day = dateParts[1].replace(',', '');
            //             var month = dateParts[0]; // Get the month name

            //             // Use the day number directly from the parsed date
            //             attendance[`day${day}`] = state === 'present' ? 1 : state === 'absent' ? 0 :
            //                 2; // Present = 1, Absent = 0, Late = 2
            //         });

            //         // Use the parsed date for month and year
            //         var firstDate = $("#studentsAttendanceTable thead th").eq(3).text();
            //         var dateParts = firstDate.split(' ');
            //         var monthId = moment().month(dateParts[0]).format(
            //             "M"); // Get month ID from the month name
            //         var yearId = new Date().getFullYear(); // Use current year

            //         attendanceData.push({
            //             studentId: studentId,
            //             attendance: attendance,
            //             monthid: parseInt(monthId),
            //             yearid: parseInt(yearId) // Ensure year is correctly set
            //         });
            //     });

            //     // Save to local storage
            //     var localStorageKey = `attendanceData_${syid}_${semid}_${subjectID}_${sectionID}`;
            //     localStorage.setItem(localStorageKey, JSON.stringify(attendanceData));

            //     // Retrieve and log the data from localStorage (optional)
            //     var savedData = JSON.parse(localStorage.getItem(localStorageKey));
            //     console.log("Saved Data:", savedData);

            //     // Send data to the server
            //     $.ajax({
            //         url: "/collegeattendancestore/", // Adjust to your Laravel route
            //         type: 'GET', // Use POST for data insertion
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         data: {
            //             data: attendanceData,
            //             syid: syid,
            //             semid: semid,
            //             subjectid: subjectID,
            //             sectionid: sectionID
            //         },
            //         success: function(response) {
            //             // Iterate through all keys in local storage and remove attendance data
            //             for (var i = localStorage.length - 1; i >= 0; i--) {
            //                 var key = localStorage.key(i);
            //                 // Check if the key matches the attendance data pattern
            //                 if (key.startsWith('attendanceDatasaved_')) {
            //                     localStorage.removeItem(key); // Remove the attendance data
            //                 }
            //             }
            //             Swal.fire({
            //                 title: 'Success!',
            //                 text: 'Attendance data has been saved.',
            //                 type: 'success',
            //                 confirmButtonText: 'OK'
            //             });
            //         },
            //         error: function(error) {
            //             Swal.fire({
            //                 title: 'Error!',
            //                 text: 'There was a problem saving attendance data.',
            //                 icon: 'error',
            //                 confirmButtonText: 'OK'
            //             });
            //         }
            //     });
            // }

        });
    </script>
@endsection
