@extends('ctportal.layouts.app2')

@section('pagespecificscripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowgroup/1.1.0/css/rowGroup.dataTables.min.css">
    <style>
        .tableFixHead thead th {
            position: sticky;
            top: 0;
            background-color: #fff;
            outline: 2px solid #dee2e6;
            outline-offset: -1px;


        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .grade_td {
            cursor: pointer;
            vertical-align: middle !important;
        }

        #dropdown-item {
            background-color: green;
            color: white;
            cursor: pointer;
            border-radius: 5%;
            margin-bottom: 2px;
            width: 120px;
            font-size: 13px;
            padding: 6px;
            text-align: center;
        }

        .sort-icon {
            font-size: 14px;
            color: black;
            /* Example color */
        }

        .sort-icon:hover {
            color: blue;
            /* Example hover color */
        }

        #grade_submissions {
            cursor: pointer;
            background-color: rgb(60, 114, 181);
            color: white;
        }

        #grade_submissions:hover {
            background-color: rgba(29, 62, 103, 0.859);
        }
    </style>
@endsection

@section('content')
    @php
        $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
        $semester = DB::table('semester')->get();
        $schoolinfo = DB::table('schoolinfo')->first()->abbreviation;
        $dean = DB::table('college_colleges')
            ->join('teacher', function ($join) {
                $join->on('college_colleges.dean', '=', 'teacher.id');
                $join->where('teacher.deleted', 0);
            })
            ->where('college_colleges.deleted', 0)
            ->select('teacher.id', DB::raw("CONCAT(teacher.lastname,', ',teacher.firstname) as text"))
            ->distinct()
            ->get();

        $gradesetup = DB::table('semester_setup')->where('deleted', 0)->first();

    @endphp



    <div class="modal fade" id="modal_1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0 bg-dark">
                    <h5 class="modal-title">
                        <span class="mt-1" id="sectionNameHeader"></span>
                    </h5>
                    <div class="d-flex align-items-center ml-auto">
                        <a class="btn btn-primary btn-sm ml-2" id="view_pdf" data-id = "" href="#">
                            <i class="far fa-file-pdf mr-1"></i> PRINT PDF
                        </a>
                        <button type="button" class="close pb-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
                <div class="container-fluid headerText p-3">
                    <div class="row py-3">
                        <!-- Teacher -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-chalkboard-teacher"></i> Teacher</span>
                                <span id="teacherName">{{ auth()->user()->name }}</span>
                                <a class="text-primary" id="teacherID">{{ auth()->user()->email }}</a>
                            </div>
                        </div>
                        <!-- Subject -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-book"></i> Subject</span>
                                <span id="subjectDescs"></span>
                                <a class="text-primary" id="subjectCodes"></a>
                            </div>
                        </div>
                        <!-- Level -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-graduation-cap"></i> Level</span>
                                <span id="collegeLevels"></span>
                            </div>
                        </div>
                        <!-- Section -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-building"></i> Section</span>
                                <span id="sections"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body" style="font-size:.8rem">
                    <table class="table table-sm table-striped" width="100%">
                        <thead>
                            <tr id="datatable_2_row">
                                <th width="10%">Student ID No.</th>
                                <th width="15%">Student Name</th>
                                <th width="10%">Academic Level</th>
                                <th width="12%">Course</th>
                                <th width="10%">Contact No.</th>

                            </tr>
                        </thead>
                        <tbody id="datatable_2">

                        </tbody>
                    </table>

                </div>


            </div>
        </div>
    </div>


    <div class="modal fade" id="modal_2" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0 bg-dark">
                    <h5 class="modal-title">
                        <span class="mt-1" id="sectionNameHeader_viewgrades"></span>
                    </h5>
                    <div class="d-flex align-items-center ml-auto">

                        <button type="button" class="close pb-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body pt-0">
                    <div class="container-fluid headerText pl-0" style="font-size: .8rem">
                        <div class="row py-3">
                            <!-- Teacher -->
                            <div class="col-md-3">
                                <div class="d-flex flex-column">
                                    <span class="font-weight-bold"><i class="fas fa-chalkboard-teacher"></i>
                                        Teacher</span>
                                    <span id="teacherName">{{ auth()->user()->name }}</span>
                                    <a class="text-primary" id="teacherID">{{ auth()->user()->email }}</a>
                                </div>
                            </div>
                            <!-- Subject -->
                            <div class="col-md-3">
                                <div class="d-flex flex-column">
                                    <span class="font-weight-bold"><i class="fas fa-book"></i> Subject</span>
                                    <span id="subjectDesc"></span>
                                    <a class="text-primary" id="subjectCode"></a>
                                </div>
                            </div>
                            <!-- Level -->
                            <div class="col-md-3">
                                <div class="d-flex flex-column">
                                    <span class="font-weight-bold"><i class="fas fa-graduation-cap"></i> Level</span>
                                    <span id="collegeLevel"></span>
                                </div>
                            </div>
                            <!-- Section -->
                            <div class="col-md-3">
                                <div class="d-flex flex-column">
                                    <span class="font-weight-bold"><i class="fas fa-building"></i> Section</span>
                                    <span id="section"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>



                    <div class="row">
                        <div class="col-md-8">
                            <p class="mb-2"><i>Note: Press <b class="text-danger">i</b> to mark the student as
                                    Incomplete (INC).</i>
                                {{-- Press <b class="text-danger">D</b> to mark student as Dropped.</i> --}}
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            <button class="btn btn-primary btn-sm" id="print_grades_to_modal"
                                style="font-size:.7rem !important">Print</button>
                        </div>
                    </div>

                    <div class="row">



                        <div class="col-md-12 table-responsive tableFixHead" style="height: 420px;">
                            <table class="table table-sm table-striped table-bordered mb-0 table-head-fixed table-hover"
                                style="font-size:.8rem" width="100%">
                                <thead style="position: sticky; top: 0; background-color: white; z-index: 10;">
                                    <tr id="deadline_holder">

                                    </tr>
                                    <tr>
                                        <th width="3%" class="text-center">#</th>
                                        <th width="30%">Student <input type="checkbox"
                                                style="margin-top:1px; float:right;" id="studentFilter">

                                            <i class="sort-iconn sort-asc" data-all-gender="all-gender" data-sort="name"
                                                style="cursor: pointer;float:right;color:black;margin-right:10px;"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                    fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                                </svg></i>
                                            <i class="sort-iconn sort-desc" data-all-gender="all-gender" data-sort="name"
                                                style="cursor: pointer; float:right; display: none; color:black;margin-right:10px;"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                    fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                                                </svg></i>

                                        </th>
                                        {{-- <th width="17%">Course</th> --}}
                                        <th width="8%" class="text-center term_holder grade_submissions_prelim"
                                            id="grade_submissions" data-value="1" data-term="1">Submit
                                        </th>
                                        <th width="8%" class="text-center term_holder grade_submissions_midterm"
                                            id="grade_submissions" data-value="2" data-term="2">Submit
                                        </th>
                                        <th width="8%" class="text-center term_holder grade_submissions_semifinal"
                                            id="grade_submissions" data-value="3" data-term="3">Submit
                                        </th>
                                        <th width="8%" class="text-center term_holder grade_submissions_final"
                                            id="grade_submissions" data-value="4" data-term="4">Submit
                                        </th>
                                        {{-- 
                                        <th width="8%" class="text-center term_holder " id="grade_submissions"
                                            data-value="1" data-term="1">
                                        </th>
                                        <th width="8%" class="text-center term_holder " id="grade_submissions"
                                            data-value="2" data-term="2">
                                        </th>
                                        <th width="8%" class="text-center term_holder " id="grade_submissions"
                                            data-value="3" data-term="3">
                                        </th>
                                        <th width="8%" class="text-center term_holder " id="grade_submissions"
                                            data-value="4" data-term="4">
                                        </th> --}}

                                        <th width="14%" class="text-center term_holder" data-term="5" rowspan="2"
                                            style="padding: 12px;">General Average
                                        </th>
                                        <th width="10%" class="text-center term_holder" data-term="6" rowspan="2"
                                            style="padding: 12px;">Remarks</th>
                                    </tr>
                                    <tr>
                                        <th width="3%" class="text-center"></th>
                                        <th width="30%"></th>
                                        <th width="8%" class="text-center term_holder" data-term="1">PRELIM
                                        </th>
                                        <th width="8%" class="text-center term_holder" data-term="2">MIDTERM</th>
                                        <th width="8%" class="text-center term_holder" data-term="3">SEMI-FINAL</th>
                                        <th width="8%" class="text-center term_holder" data-term="4">FINAL</th>

                                    </tr>
                                </thead>
                                <tbody id="student_list_grades">

                                </tbody>
                            </table>
                            <div class="d-flex">
                                <div class="enrolled_students_holder" style="margin-left: 0%;">
                                    <br>
                                    <span class="font-weight-bold enrolled_students_label" style="font-size: 15px;"><i
                                            class="fas fa-book"></i>
                                        Number
                                        of Students
                                        Enrolled</span>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center badge"
                                            style="background-color:rgba(26, 108, 215, 0.967);">
                                            <p class="count_male_label mb-0 mr-1" style="font-size:13px;color:white;">
                                                Male:</p>
                                            <span class="count_male" style="font-size:13px;color:white;"
                                                id="maleCount"></span>
                                        </div>
                                        |
                                        <div class="d-flex align-items-center badge" style="background-color: #e56dad;">
                                            <p class="count_female_label mb-0 mr-1" style="font-size:13px;color:white;">
                                                Female:</p>
                                            <span class="count_female" style="font-size:13px;color:white;"
                                                id="femaleCount"></span>
                                        </div>
                                        |
                                        <div class="d-flex align-items-center badge">
                                            <p class="count_total_gender_label mb-0 mr-1" style="font-size:13px;">Total:
                                            </p>
                                            <span class="count_total_gender" style="font-size:13px;"
                                                id="totalCount"></span>
                                        </div>
                                    </div>

                                </div>

                                {{-- <div class="grade_remarks_holder" style="margin-left: 7%;">
                                    <br>
                                    <span class="font-weight-bold grade_remarks_label" style="font-size: 15px;"><i
                                            class="fas fa-book"></i>
                                        Grade Remarks</span>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center badge">
                                            <p class="count_passed_label mb-0 mr-1" style="font-size:13px;">
                                                Passed:</p>
                                            <span class="count_passed" style="font-size:13px;" id="passedCount"></span>
                                        </div>
                                        &nbsp;&nbsp;|&nbsp;&nbsp;
                                        <div class="d-flex align-items-center badge">
                                            <p class="count_failed_label mb-0 mr-1" style="font-size:13px;">
                                                Failed:</p>
                                            <span class="count_failed" style="font-size:13px;" id="failedCount"></span>
                                        </div>
                                        &nbsp;&nbsp;|&nbsp;&nbsp;
                                        <div class="d-flex align-items-center badge">
                                            <p class="count_inc_label mb-0 mr-1" style="font-size:13px;">
                                                INC:
                                            </p>
                                            <span class="count_inc" style="font-size:13px;" id="incCount"></span>
                                        </div>
                                    </div>


                                </div> --}}


                                <div class="grade_status_holder" style="margin-left: 7%;">
                                    <br>
                                    <span class="font-weight-bold grade_remarks_label" style="font-size: 15px;"><i
                                            class="fas fa-book"></i>
                                        Grade Status Legends</span>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center badge badge-success">
                                            <p class="count_passed_label mb-0" style="font-size:13px;">
                                                Submitted</p>

                                        </div>
                                        &nbsp;&nbsp;|&nbsp;&nbsp;
                                        <div class="d-flex align-items-center badge badge-danger">
                                            <p class="count_failed_label mb-0" style="font-size:13px;">
                                                Dropped</p>

                                        </div>
                                        &nbsp;&nbsp;|&nbsp;&nbsp;
                                        <div class="d-flex align-items-center badge badge-warning">
                                            <p class="count_inc_label mb-0" style="font-size:13px;">
                                                Pending
                                            </p>
                                        </div>
                                        &nbsp;&nbsp;|&nbsp;&nbsp;
                                        <div class="d-flex align-items-center badge badge-primary">
                                            <p class="count_passed_label mb-0 " style="font-size:13px;">
                                                Approved</p>

                                        </div>
                                        &nbsp;&nbsp;|&nbsp;&nbsp;
                                        <div class="d-flex align-items-center badge badge-info">
                                            <p class="count_failed_label mb-0" style="font-size:13px;">
                                                Posted</p>

                                        </div>
                                    </div>
                                    {{-- <div class="d-flex ">
                                        <div class="d-flex align-items-center badge badge-primary">
                                            <p class="count_passed_label mb-0 " style="font-size:13px;">
                                                Approved</p>

                                        </div>
                                        &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;
                                        <div class="d-flex align-items-center badge badge-info">
                                            <p class="count_failed_label mb-0 mr-1" style="font-size:13px;">
                                                Posted</p>

                                        </div>

                                    </div> --}}


                                </div>



                            </div>
                            <br>
                            <div class="col-md-12">



                                <div class="dropdown" style="float: right;">
                                    <button id="save_grades" class="btn btn-primary btn-sm" data-schedid="0" disabled
                                        hidden>Save
                                        Grades</button>

                                    <button class="btn btn-primary btn-sm submit_all_btn" id="submit-all-btn"
                                        data-value="5" data-term="5">
                                        Submit All
                                    </button>
                                </div>

                            </div>


                        </div>




                    </div>
                    <div class="row">
                    </div>
                </div>
                <div class="modal-footer pt-1 pb-1" style="font-size:.7rem">
                    <i id="message_holder"></i>
                </div>
            </div>
        </div>
    </div>



    <div class="row" style="display: none;">
        <div class="col-md-12 table-responsive tableFixHead" style="height: 422px;">
            <table class="table table-sm table-striped table-bordered mb-0 table-head-fixed" style="font-size:.9rem"
                width="100%">
                <thead>
                    <tr>
                        <th width="5%"><input type="checkbox" disabled checked="checked" class="select_all"> </th>
                        <th width="20%">SID</th>
                        <th width="60%">Student</th>
                        <th width="15%" class="text-centerv">Grade</th>
                    </tr>
                </thead>
                <tbody id="datatable_4">

                </tbody>
            </table>
        </div>
    </div>


    <div class="modal fade" id="dean_holder_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" style="font-size:.9rem">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Dean</label>
                            <select class="form-control select2" id="printable_dean">

                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary btn-sm" id="print_grades">Print</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Student Grades</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Grades</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">

            <!-- Tab Navigation -->
            <div class="col-md-12">
                {{-- <div class="row"> --}}
                {{-- <div class="col-md-12"> --}}
                <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                    <li class="nav-item col-md-2 ">
                        <a class="nav-link" href="{{ url('college/teacher/student/collegesystemgrading') }}">
                            System Grading
                        </a>
                    </li>
                    <li class="nav-item col-md-2 ">
                        <a class="nav-link" href="{{ url('college/teacher/student/excelupload') }}">
                            Excel Upload
                        </a>
                    </li>
                    <li class="nav-item col-md-2 ">
                        <a class="nav-link active" href="{{ url('college/teacher/student/grades') }}">
                            Final Grading
                        </a>
                    </li>
                </ul>
                {{-- </div> --}}
                {{-- <div class="col-md-3"></div> --}}
                {{-- </div> --}}
                <!-- Data Table -->
                {{-- <div class="row"> --}}
                {{-- <div class="col-md-12"> --}}
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="filter_sy">School Year</label>
                                <select class="form-control form-control-sm select2" id="filter_sy">
                                    @foreach ($sy as $item)
                                        <option value="{{ $item->id }}" {{ $item->isactive == 1 ? 'selected' : '' }}>
                                            {{ $item->sydesc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="filter_semester">Semester</label>
                                <select class="form-control form-control-sm select2" id="filter_semester">
                                    <option value="">Select semester</option>
                                    @foreach ($semester as $item)
                                        <option value="{{ $item->id }}" {{ $item->isactive == 1 ? 'selected' : '' }}>
                                            {{ $item->semester }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12" style="font-size: .9rem">
                                <table class="table table-sm" id="datatable_1" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">Section</th>
                                            <th style="text-align: center;">Subject</th>
                                            <th style="text-align: center;">Level</th>
                                            <th style="text-align: center;">Time Schedule</th>
                                            <th style="text-align: center;">Day</th>
                                            <th style="text-align: center;">Room</th>
                                            <th style="text-align: center;">Enrolled</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    {{-- Uncomment and populate this section for table body --}}
                                    {{-- <tbody>
                                                </tbody> --}}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- </div> --}}
                {{-- </div> --}}
            </div>

        </div>
    </section>
@endsection

@section('footerscript')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    {{-- <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script> --}}
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    {{-- <script src="https://cdn.datatables.net/rowgroup/1.1.0/js/dataTables.rowGroup.min.js"></script> --}}


    <script>
        $('#filter_sy').select2()
        $('#filter_semester').select2()
    </script>

    <script>
        $(document).ready(function() {

            var school = @json($schoolinfo);



            var isSaved = false;
            var isvalidHPS = true;
            var hps = []
            var currentIndex
            var can_edit = true

            $(document).on('click', '.input_grades', function() {

                var term = $(this).attr('data-term')
                var checkDateSetup = []

                if (term == 1) {
                    checkDateSetup = inputperiod.filter(x => x.term == 'prelim')
                } else if (term == 2) {
                    checkDateSetup = inputperiod.filter(x => x.term == 'midterm')
                } else if (term == 3) {
                    checkDateSetup = inputperiod.filter(x => x.term == 'prefi')
                } else if (term == 4) {
                    checkDateSetup = inputperiod.filter(x => x.term == 'finalterm')
                }

                if (checkDateSetup.length > 0) {
                    if (!checkDateSetup[0].isended) {
                        Toast.fire({
                            type: 'warning',
                            title: 'Deadline Ended.'
                        })
                        return false
                    }
                }



                if (school == 'spct'.toUpperCase() && $(this).attr('data-term') == 'finalgrade') {
                    return false;
                }

                if (currentIndex != undefined) {
                    if (isvalidHPS) {
                        if (can_edit) {
                            string = $(this).text();
                            currentIndex = this;
                            $('#start').length > 0 ? dotheneedful(this) : false
                            $('td').removeAttr('style');
                            $('#start').removeAttr('id')
                            $(this).attr('id', 'start')
                            $(currentIndex).removeClass('bg-danger')
                            $(currentIndex).removeClass('bg-warning')
                            var start = document.getElementById('start');
                            start.focus();
                            start.style.backgroundColor = 'green';
                            start.style.color = 'white';
                        }
                    }
                } else {
                    if (can_edit) {
                        string = $(this).text();
                        currentIndex = this;
                        $('#start').length > 0 ? dotheneedful(this) : false
                        $('td').removeAttr('style');
                        $('#start').removeAttr('id')
                        $(this).attr('id', 'start')
                        $(currentIndex).removeClass('bg-danger')
                        $(currentIndex).removeClass('bg-warning')
                        var start = document.getElementById('start');
                        start.focus();
                        start.style.backgroundColor = 'green';
                        start.style.color = 'white';

                    }
                }
            })


            $(document).on('click', '#download_ecr', function() {

                window.open('/college/grade/ecr/download?syid=' + $('#filter_sy').val() + '&semid=' + $(
                        '#filter_semester').val() + '&schedid=' + temp_id + '&subjid=' + $(
                        '#subjectDesc').data('id') +
                    '&levelid=' + $('#collegeLevel').data('id') + '&sectionid=' + $('#section').data(
                        'id'), '_blank'
                );

            })

            $('#input_ecr').on('change', function() {
                var fileInput = $(this)[0]; // Get file input element
                var uploadButton = $('#upload_ecr_button'); // Get the button
                var file = fileInput.files[0]; // Get the selected file

                // Check if a file is selected
                if (file) {
                    var fileName = file.name; // Get the file name
                    var validExtensions = ['xlsx', 'xls']; // Valid Excel extensions

                    // Get the file extension and check if it's valid
                    var fileExtension = fileName.split('.').pop().toLowerCase();

                    // Enable the button if valid Excel file, otherwise disable and alert
                    if ($.inArray(fileExtension, validExtensions) !== -1) {
                        uploadButton.prop('disabled', false); // Enable button
                    } else {
                        uploadButton.prop('disabled', true); // Disable button
                        Swal.fire({
                            type: 'error',
                            title: 'Invalid File',
                            text: 'Please select a valid Excel file (.xlsx or .xls)',
                        });
                    }
                } else {
                    // No file selected, disable the button
                    uploadButton.prop('disabled', true);
                }
            });
            // $('#upload_ecr').submit(function(e) {
            //     // temp_id = $(this).attr('data-id')
            //     // var students = all_subject.filter(x => x.schedid == temp_id)

            //     if ($('#filter_quarter').val() == "") {
            //         Toast.fire({
            //             type: 'warning',
            //             title: 'Please select quarter'
            //         })
            //         return false;
            //     } else if ($('#input_ecr').val() == "") {
            //         Toast.fire({
            //             type: 'warning',
            //             title: 'Please attach a file'
            //         })
            //         return false;
            //     }

            //     var inputs = new FormData(this)

            //     inputs.append('input_ecr', $('#input_ecr').val())
            //     inputs.append('syid', $('#filter_sy').val())
            //     inputs.append('levelid', $('#collegeLevel').data('id'))
            //     inputs.append('sectionid', $('#section').data('id'))
            //     inputs.append('subjid', $('#subjectDesc').data('id'))
            //     inputs.append('input_term', $('#excel_termid').val())
            //     inputs.append('input_coordinates', $('#excel_cell_coordinates').val())
            //     // inputs.append('ecrformat', $('#ecr_format').val())

            //     $('#upload_ecr_button').text('Uploading...')
            //     $('#upload_ecr_button').attr('disabled', 'disabled')

            //     // Show the loading spinner
            //     Swal.fire({
            //         title: 'Uploading Excel File...',
            //         text: 'Please wait while we upload and process your Excel file.',
            //         allowOutsideClick: false,
            //         allowEscapeKey: false,
            //         onBeforeOpen: () => {
            //             Swal.showLoading();
            //         }
            //     });


            //     $.ajax({
            //         url: '/college/grade/ecr/upload',
            //         type: 'POST',
            //         data: inputs,
            //         processData: false,
            //         contentType: false,
            //         success: function(data) {
            //             Swal.close();
            //             if (data[0].status == 0) {
            //                 Toast.fire({
            //                     type: 'warning',
            //                     title: data[0].message
            //                 })

            //                 $('#upload_ecr_button').text('Update ECR')
            //                 $('#upload_ecr_button').removeAttr('disabled')
            //                 $('#input_ecr').val("")
            //             } else {
            //                 Toast.fire({
            //                     type: 'success',
            //                     title: data[0].message
            //                 })
            //                 $('#upload_ecr_button').text('Update ECR')
            //                 $('#upload_ecr_button').removeAttr('disabled')
            //                 $('#input_ecr').val("")
            //                 // load_ecr()
            //                 // get_grades(temp_id, true, students);



            //             }

            //         },
            //         error: function() {
            //             Swal.close();
            //             Toast.fire({
            //                 type: 'error',
            //                 title: 'Something went wrong'
            //             });
            //             $('#upload_ecr_button').text('Update ECR')
            //             $('#upload_ecr_button').removeAttr('disabled')
            //             $('#input_ecr').val("")
            //         }
            //     })
            //     e.preventDefault();
            // })


            function dotheneedful(sibling) {

                var term = $(sibling).attr('data-term')

                if (term == 1) {
                    checkDateSetup = inputperiod.filter(x => x.term == 'prelim')
                } else if (term == 2) {
                    checkDateSetup = inputperiod.filter(x => x.term == 'midterm')
                } else if (term == 3) {
                    checkDateSetup = inputperiod.filter(x => x.term == 'prefi')
                } else if (term == 4) {
                    checkDateSetup = inputperiod.filter(x => x.term == 'finalterm')
                }

                if (checkDateSetup.length > 0) {
                    if (!checkDateSetup[0].isended) {
                        Toast.fire({
                            type: 'warning',
                            title: 'Deadline Ended.'
                        })
                        return false
                    }
                }

                if (sibling != null) {
                    currentIndex = sibling
                    $(sibling).removeClass('bg-danger')
                    $(sibling).removeClass('bg-warning')

                    if ($(start).text() == 'DROPPED') {
                        $(start).addClass('bg-danger')
                    } else if ($(start).text() == 'INC' || $(start).attr('data-status') == 3) {
                        $(start).addClass('bg-warning')
                    }

                    start.style.backgroundColor = '';
                    start.style.color = '';
                    sibling.focus();
                    sibling.style.backgroundColor = 'green';
                    sibling.style.color = 'white';
                    start = sibling;



                    $('#message').empty();
                    string = $(currentIndex)[0].innerText
                }
            }

            document.onkeydown = checkKey;

            function checkKey(e) {

                e = e || window.event;
                if (e.keyCode == '38' && currentIndex != undefined) {
                    var idx = start.cellIndex;
                    var nextrow = start.parentElement.previousElementSibling;
                    if (nextrow == null || !$(nextrow.cells[idx]).hasClass('input_grades')) {
                        return false;
                    }
                    if (school == 'spct'.toUpperCase() && $(nextrow.cells[idx]).attr('data-term') == 'finalgrade') {
                        return false;
                    } else {
                        $('#curText').text(string)
                        var sibling = nextrow.cells[idx];
                        if (sibling == undefined) {
                            return false;
                        }
                        string = sibling.innerText;
                        dotheneedful(sibling);
                    }
                } else if (e.keyCode == '40' && currentIndex != undefined) {
                    var idx = start.cellIndex;
                    var nextrow = start.parentElement.nextElementSibling;
                    if (nextrow == null || !$(nextrow.cells[idx]).hasClass('input_grades')) {
                        return false;
                    }
                    if (school == 'spct'.toUpperCase() && $(nextrow.cells[idx]).attr('data-term') == 'finalgrade') {
                        return false;
                    } else {
                        $('#curText').text(string)
                        var sibling = nextrow.cells[idx];
                        if (sibling == undefined) {
                            return false;
                        }
                        string = sibling.innerText;
                        dotheneedful(sibling);
                    }
                } else if (e.keyCode == '37' && currentIndex != undefined) {
                    var sibling = start.previousElementSibling;
                    if (sibling == null || !$(sibling).hasClass('input_grades')) {
                        return false;
                    } else if ($(sibling)[0].nodeName != "TD") {
                        return false;
                    }
                    if (school == 'spct'.toUpperCase() && $(sibling).attr('data-term') == 'finalgrade') {
                        return false;
                    }
                    $('#curText').text(string)
                    if ($(sibling)[0].cellIndex != 0) {
                        string = sibling.innerText;
                        dotheneedful(sibling);
                    }

                } else if (e.keyCode == '39' && currentIndex != undefined) {
                    var sibling = start.nextElementSibling;
                    if (sibling == null || !$(sibling).hasClass('input_grades')) {
                        return false;
                    } else if ($(sibling)[0].nodeName != "TD") {
                        return false;
                    }
                    if (school == 'spct'.toUpperCase() && $(sibling).attr('data-term') == 'finalgrade') {
                        return false;
                    }
                    $('#curText').text(string)
                    if ($(sibling)[0].cellIndex != 0) {
                        string = sibling.innerText;
                        dotheneedful(sibling);
                    }
                } else if (e.keyCode == '73' && currentIndex != undefined) {
                    $(currentIndex).text("INC")
                    $(currentIndex).addClass('updated')
                    $('#save_grades').removeAttr('disabled')
                    $('#grade_submit').attr('disabled', 'disabled')
                }
                // else if (e.keyCode == '68' && currentIndex != undefined) {
                //     $(currentIndex).text("DROPPED").addClass('updated');
                //     $('#save_grades').removeAttr('disabled');
                //     $('#grade_submit').attr('disabled', 'disabled');
                //     $(currentIndex).off('keydown').on('keydown', function(e) {
                //         // Prevent input of numbers
                //         if (e.key.match(/[0-9]/)) { // Prevent number keys
                //             e.preventDefault();
                //         }
                //     });
                // }
                else if (e.keyCode == '68' && currentIndex != undefined) {
                    $(currentIndex).text("DROPPED")
                    $(currentIndex).addClass('updated')
                    $('#save_grades').removeAttr('disabled')
                    $('#grade_submit').attr('disabled', 'disabled')

                } else if (e.key == "Backspace" && currentIndex != undefined) {



                    if (currentIndex.innerText == 'INC' || currentIndex.innerText == 'DROPPED') {
                        string = ''
                    } else {
                        string = currentIndex.innerText
                        string = string.slice(0, -1);
                    }



                    if (string.length == 0) {
                        string = '';
                        currentIndex.innerText = string
                    } else {
                        currentIndex.innerText = parseInt(string)
                        inputIndex = currentIndex
                    }



                    $(currentIndex).addClass('updated')
                    $('#save_grades').removeAttr('disabled')
                    $('#grade_submit').attr('disabled', 'disabled')

                    $(currentIndex).text(string)
                    $('#curText').text(string)

                    var temp_studid = $(currentIndex).attr('data-studid')
                    var prelim = parseFloat($('.grade_td[data-studid="' + temp_studid + '"][data-term="1"]')
                        .text());
                    var midterm = parseFloat($('.grade_td[data-studid="' + temp_studid + '"][data-term="2"]')
                        .text());
                    var prefi = parseFloat($('.grade_td[data-studid="' + temp_studid + '"][data-term="3"]').text());
                    var final = parseFloat($('.grade_td[data-studid="' + temp_studid + '"][data-term="4"]').text());

                    if (gradesetup.f_frontend != '' || gradesetup.f_frontend != null) {

                        var fg = eval(gradesetup.f_frontend).toFixed(gradesetup.decimalPoint)
                        if (!isNaN(fg)) {
                            $('th[data-studid="' + temp_studid + '"][data-term="5"]').text(fg)
                            $('th[data-studid="' + temp_studid + '"][data-term="5"]').addClass('updated')
                            $('th[data-studid="' + temp_studid + '"][data-term="6"]').addClass('updated')

                            if (gradesetup.isPointScaled == 0) {
                                if (fg >= gradesetup.passingRate) {
                                    $('th[data-studid="' + temp_studid + '"][data-term="6"]').text('Passed')
                                } else {
                                    $('th[data-studid="' + temp_studid + '"][data-term="6"]').text('Failed')
                                }
                            } else {
                                if (fg <= gradesetup.passingRate) {
                                    $('th[data-studid="' + temp_studid + '"][data-term="6"]').text('Passed')
                                } else {
                                    $('th[data-studid="' + temp_studid + '"][data-term="6"]').text('Failed')
                                }
                            }

                        } else {
                            $('th[data-studid="' + temp_studid + '"][data-term="5"]').addClass('updated')
                            $('th[data-studid="' + temp_studid + '"][data-term="6"]').addClass('updated')

                            $('th[data-studid="' + temp_studid + '"][data-term="5"]').text(null)
                            $('th[data-studid="' + temp_studid + '"][data-term="6"]').text(null)
                        }
                    }

                } else if (((e.key >= 0 && e.key <= 9) || e.key == '.') && currentIndex != undefined) {




                    //check ForPoint
                    if (e.key == '.') {
                        if (gradesetup.decimalPoint == 0) {
                            return false
                        }
                        var checkForPoint = string.includes('.')
                        if (checkForPoint) {
                            return false
                        }
                    }

                    var check_string = string + e.key;
                    var decimalcount = count_decimal(check_string)



                    if (decimalcount <= gradesetup.decimalPoint) {
                        string += e.key;
                    } else {
                        string = string;
                    }




                    if (gradesetup.isPointScaled == 0) {
                        if (check_string > 100) {
                            string = 100
                        }
                    } else {
                        if (check_string > 5) {
                            return false
                        }
                    }


                    if (currentIndex.innerText == 'INC' || currentIndex.innerText == 'DROPPED') {
                        string = ''
                    }

                    $(currentIndex).addClass('updated')
                    $('#save_grades').removeAttr('disabled')
                    $('#grade_submit').attr('disabled', 'disabled')

                    $(currentIndex).text(string)
                    $('#curText').text(string)

                    var temp_studid = $(currentIndex).attr('data-studid')
                    var prelim = parseFloat($('.grade_td[data-studid="' + temp_studid + '"][data-term="1"]')
                        .text());
                    var midterm = parseFloat($('.grade_td[data-studid="' + temp_studid + '"][data-term="2"]')
                        .text());
                    var prefi = parseFloat($('.grade_td[data-studid="' + temp_studid + '"][data-term="3"]').text());
                    var final = parseFloat($('.grade_td[data-studid="' + temp_studid + '"][data-term="4"]').text());

                    if (gradesetup.f_frontend != '' || gradesetup.f_frontend != null) {

                        var fg = eval(gradesetup.f_frontend).toFixed(gradesetup.decimalPoint)

                        if (!isNaN(fg)) {
                            $('th[data-studid="' + temp_studid + '"][data-term="5"]').text(fg)
                            $('th[data-studid="' + temp_studid + '"][data-term="5"]').addClass('updated')
                            $('th[data-studid="' + temp_studid + '"][data-term="6"]').addClass('updated')

                            // if (gradesetup.isPointScaled == 0) {
                            //     if (fg >= gradesetup.passingRate) {
                            //         $('th[data-studid="' + temp_studid + '"][data-term="6"]').text('Passed')
                            //     } else {
                            //         $('th[data-studid="' + temp_studid + '"][data-term="6"]').text('Failed')
                            //     }
                            // } else {
                            //     if (fg <= gradesetup.passingRate) {
                            //         $('th[data-studid="' + temp_studid + '"][data-term="6"]').text('Passed')
                            //     } else {
                            //         $('th[data-studid="' + temp_studid + '"][data-term="6"]').text('Failed')
                            //     }
                            // }


                        } else {
                            $('th[data-studid="' + temp_studid + '"][data-term="5"]').text('')
                            $('th[data-studid="' + temp_studid + '"][data-term="6"]').text('')
                            $('th[data-studid="' + temp_studid + '"][data-term="5"]').text(null)
                            $('th[data-studid="' + temp_studid + '"][data-term="6"]').text(null)
                        }
                    }

                }

            }

        })

        function count_decimal(num) {
            const converted = num.toString();
            if (converted.includes('.')) {
                return converted.split('.')[1].length;
            };
            return 0;
        }
    </script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        })

        var gradesetup = [];

        getgradesetup()

        function getgradesetup() {
            $.ajax({
                type: 'GET',
                url: '/semester-setup/getactive-setup',
                async: false,
                data: {
                    syid: $('#filter_sy').val(),
                    semid: $('#filter_semester').val(),
                },
                success: function(data) {
                    gradesetup = data
                    if (gradesetup.length == 0) {
                        $('#grades_setup_holder').attr('hidden', 'hidden')
                        $('#grades_setup_holder')[0].innerHTML =
                            '<div class="col-md-12"><p class="mb-0 text-danger">* No available grade setup.</p></div>'
                    } else {

                        $('#grades_setup_holder').removeAttr('hidden', 'hidden')
                        gradesetup = gradesetup[0]

                        var termtext = ''
                        if (gradesetup.prelim == 1) {
                            termtext += '<span class="badge badge-primary ml-1">Prelim</span>'
                        }
                        if (gradesetup.midterm == 1) {
                            termtext += '<span class="badge badge-primary ml-1">Midterm</span>'
                        }
                        if (gradesetup.prefi == 1) {
                            termtext += '<span class="badge badge-primary ml-1">Prefi</span>'
                        }
                        if (gradesetup.final == 1) {
                            termtext += '<span class="badge badge-primary ml-1">Final</span>'
                        }
                        $('#setup_term_holder')[0].innerHTML = termtext
                        $('#setup_fgc_holder').text(gradesetup.f_frontend)
                        $('#setup_dp_holder').text(gradesetup.decimalPoint)


                        if (gradesetup.isPointScaled == 1) {
                            $('#setup_gs_holder').text('Decimal Point Scale ( 1 - 5 )')
                        } else {
                            $('#setup_gs_holder').text('Numerical Point Scale ( 60 - 100 )')
                        }

                    }
                }
            })
        }

        getgradesetupS();

        function getgradesetupS() {
            $.ajax({
                type: 'GET',
                url: '/semester-setup/getactive-setup/v4',
                async: false,
                data: {
                    syid: $('#filter_sy').val(),
                    semid: $('#filter_semester').val(),
                },
                success: function(data) {
                    gradesetupS = data
                    console.log('getgradesetup', gradesetupS);
                    if (gradesetupS.length == 0) {
                        console.log('No available grade setup.');
                        $('#grades_setup_holder').attr('hidden', 'hidden');
                        $('#grades_setup_holder')[0].innerHTML =
                            '<div class="col-md-12"><p class="mb-0 text-danger">* No available grade setup.</p></div>';
                    } else {
                        $('#grades_setup_holder').removeAttr('hidden', 'hidden');
                        var termtext = '';
                        $.each(gradesetupS, function(index, grade) {
                            if (grade.quarter == 1) {
                                console.log('Adding Prelim badge');
                                termtext += '<span class="badge badge-primary ml-1">Prelim</span>';
                            }
                            if (grade.quarter == 2) {
                                console.log('Adding Midterm badge');
                                termtext += '<span class="badge badge-primary ml-1">Midterm</span>';
                            }
                            if (grade.quarter == 3) {
                                console.log('Adding Prefi badge');
                                termtext += '<span class="badge badge-primary ml-1">Prefi</span>';
                            }
                            if (grade.quarter == 4) {
                                console.log('Adding Final badge');
                                termtext += '<span class="badge badge-primary ml-1">Final</span>';
                            }
                        });
                        $('#setup_term_holder')[0].innerHTML = termtext;

                        // if (gradesetup[0].isPointScaled == 1) {
                        //     console.log('Using Decimal Point Scale');
                        //     $('#setup_gs_holder').text('Decimal Point Scale ( 1 - 5 )');
                        // } else {
                        //     console.log('Using Numerical Point Scale');
                        //     $('#setup_gs_holder').text('Numerical Point Scale ( 60 - 100 )');
                        // }
                    }
                }
            });
        }


        // function getgradesetup() {
        //     $.ajax({
        //         type: 'GET',
        //         url: '/semester-setup/getactive-setup',
        //         async: false,
        //         data: {
        //             syid: $('#filter_sy').val(),
        //             semid: $('#filter_semester').val(),
        //         },
        //         success: function(data) {
        //             gradesetup = data
        //             if (gradesetup.length == 0) {
        //                 $('#grades_setup_holder').attr('hidden', 'hidden')
        //                 $('#grades_setup_holder')[0].innerHTML =
        //                     '<div class="col-md-12"><p class="mb-0 text-danger">* No available grade setup.</p></div>'
        //             } else {

        //                 $('#grades_setup_holder').removeAttr('hidden', 'hidden')
        //                 gradesetup = gradesetup[0]

        //                 var termtext = ''
        //                 if (gradesetup.prelim == 1) {
        //                     termtext += '<span class="badge badge-primary ml-1">Prelim</span>'
        //                 }
        //                 if (gradesetup.midterm == 1) {
        //                     termtext += '<span class="badge badge-primary ml-1">Midterm</span>'
        //                 }
        //                 if (gradesetup.prefi == 1) {
        //                     termtext += '<span class="badge badge-primary ml-1">Prefi</span>'
        //                 }
        //                 if (gradesetup.final == 1) {
        //                     termtext += '<span class="badge badge-primary ml-1">Final</span>'
        //                 }
        //                 $('#setup_term_holder')[0].innerHTML = termtext
        //                 $('#setup_fgc_holder').text(gradesetup.f_frontend)
        //                 $('#setup_dp_holder').text(gradesetup.decimalPoint)


        //                 if (gradesetup.isPointScaled == 1) {
        //                     $('#setup_gs_holder').text('Decimal Point Scale ( 1 - 5 )')
        //                 } else {
        //                     $('#setup_gs_holder').text('Numerical Point Scale ( 60 - 100 )')
        //                 }

        //             }
        //         }
        //     })
        // }

        var inputperiod = []
        getinputperiod()

        function getinputperiod() {
            $.ajax({
                type: 'GET',
                url: '/college/inputperiods/get/active',
                async: false,
                data: {
                    syid: $('#filter_sy').val(),
                    semid: $('#filter_semester').val(),
                },
                success: function(data) {
                    inputperiod = data

                    $.each(inputperiod, function(a, b) {
                        var pastDate = moment(b.dateend);
                        var dDiff = moment().isBefore(pastDate);
                        b.isended = dDiff
                    })



                }
            })
        }



        $(document).ready(function() {



            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $(document).on('click', '.select_all', function() {
                if ($(this).prop('checked') == true) {
                    $('.select').prop('checked', true)
                } else {
                    $('.select').each(function() {
                        if ($(this).attr('disabled') == undefined) {
                            $(this).prop('checked', false)
                        }
                    })
                }
            })


            $(document).on('change', '#quarter_select', function() {
                var term = $(this).val()
                if (term == "") {
                    $('.select_all').attr('disabled', 'disabled')
                    $('.select').attr('disabled', 'disabled')
                    $('.grade_submission_student').text()
                    $('#submit_selected_grade').attr('disabled', 'disabled')
                    $('.select').removeAttr('data-id')
                    $('.grade_submission_student').empty()
                    return false
                }
                $('#submit_selected_grade').removeAttr('disabled')
                $('.select_all').removeAttr('disabled')
                $('.select').removeAttr('disabled')
                $('.grade_td[data-term="' + term + '"]').each(function(a, b) {
                    if ($(this).attr('data-status') == 1 || $(this).attr('data-status') == 7 || $(
                            this).attr('data-status') == 8 || $(this).attr('data-status') == 9 || $(
                            this).attr('data-status') == 2 || $(this).attr('data-status') == 4) {
                        $('.select[data-studid="' + $(this).attr('data-studid') + '"]').attr(
                            'disabled', 'disabled')
                    }
                    $('.grade_submission_student[data-studid="' + $(this).attr('data-studid') +
                        '"]').text($(this).text())
                    $('.select[data-studid="' + $(this).attr('data-studid') + '"]').attr('data-id',
                        $(this).attr('data-id'))
                })
            })



            function get_term(term) {
                if (term == 1) {
                    return "prelim_transmuted"
                } else if (term == 2) {
                    return "midterm_transmuted"
                } else if (term == 3) {
                    return "prefinal_transmuted"
                } else if (term == 4) {
                    return "final_transmuted"
                } else if (term == 5) {
                    return "submitall"
                }

            }
            //v2
            // function submit_grade(clickedElement) {

            //     var selected = []

            //     var term = $(clickedElement).data('value'); // Get data-value from clicked element

            //     console.log(term);

            //     var dterm = term
            //     term = get_term(term)

            //     $('.select').each(function() {
            //         if ($(this).prop('checked') && !$(this).attr('disabled') && $(this).attr('data-id')) {
            //             selected.push($(this).attr('data-id'))
            //         }
            //     })

            //     if (selected.length === 0) {
            //         Swal.fire({
            //             icon: 'warning',
            //             text: 'Please select at least one student before submitting.',
            //             confirmButtonColor: '#3085d6',
            //             confirmButtonText: 'OK'
            //         });
            //         return; // Exit the function if no items are selected
            //     }

            //     Swal.fire({
            //         html: '<h4>Are you sure you want <br>' +
            //             'to submit grades?</h4>',
            //         type: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Submit Grades!'
            //     }).then((result) => {
            //         if (result.value) {
            //             var postData = {
            //                 syid: $('#filter_sy').val() || undefined,
            //                 semid: $('#filter_semester').val() || undefined,
            //                 term: term || undefined,
            //                 selected: selected.length > 0 ? selected : undefined
            //             };

            //             // Remove properties with undefined values
            //             Object.keys(postData).forEach(key => postData[key] === undefined && delete postData[
            //                 key]);

            //             $.ajax({
            //                 type: 'POST',
            //                 url: '/college/teacher/student/new/grades/submit',
            //                 data: postData,
            //                 success: function(data) {
            //                     if (data.status === 0) {
            //                         Toast.fire({
            //                             // icon: 'warning',
            //                             title: '<i class="fas fa-exclamation-circle" style="color: #ff9800;"></i> &nbsp; ' +
            //                                 data.message
            //                         });
            //                     } else {

            //                         Toast.fire({

            //                             title: '<i class="fas fa-check" style="color: #4CAF50;"></i> &nbsp; Submitted!'
            //                         });



            //                         $.each(selected, function(a, b) {
            //                             var inputSelector = '.input_grades[data-id="' +
            //                                 b + '"][data-term="' + dterm +
            //                                 '"]';

            //                             // if (!$(inputSelector).hasClass('bg-danger')) {
            //                             //     $(inputSelector).addClass('bg-success')
            //                             //         .removeClass('input_grades bg-warning');

            //                             //     $(inputSelector).attr('data-status', 1);
            //                             //     $('th[data-studid="' + b +
            //                             //             '"][data-term="5"]')
            //                             //         .addClass('bg-success');

            //                             //     var temp_id = all_grades.findIndex(x => x
            //                             //         .id ==
            //                             //         b);
            //                             //     if (dterm == 1) {
            //                             //         all_grades[temp_id].prelemstatus = 1;
            //                             //     } else if (dterm == 2) {
            //                             //         all_grades[temp_id].midtermstatus = 1;
            //                             //     } else if (dterm == 3) {
            //                             //         all_grades[temp_id].prefistatus = 1;
            //                             //     } else if (dterm == 4) {
            //                             //         all_grades[temp_id].finalstatus = 1;
            //                             //     } else if (dterm == 5) {
            //                             //         all_grades[temp_id].prelemstatus = 1;
            //                             //         all_grades[temp_id].midtermstatus = 1;
            //                             //         all_grades[temp_id].prefistatus = 1;
            //                             //         all_grades[temp_id].finalstatus = 1;
            //                             //     }
            //                             // }
            //                             $(inputSelector).addClass('bg-success')
            //                                 .removeClass('input_grades bg-warning');

            //                             $(inputSelector).attr('data-status', 1);
            //                             $('th[data-studid="' + b + '"][data-term="5"]')
            //                                 .addClass('bg-success');


            //                             var temp_id = all_grades.findIndex(x => x.id ==
            //                                 b);
            //                             if (dterm == 1) {
            //                                 all_grades[temp_id].prelemstatus = 1;
            //                             } else if (dterm == 2) {
            //                                 all_grades[temp_id].midtermstatus = 1;
            //                             } else if (dterm == 3) {
            //                                 all_grades[temp_id].prefistatus = 1;
            //                             } else if (dterm == 4) {
            //                                 all_grades[temp_id].finalstatus = 1;
            //                             } else if (dterm == 5) {
            //                                 all_grades[temp_id].prelemstatus = 1;
            //                                 all_grades[temp_id].midtermstatus = 1;
            //                                 all_grades[temp_id].prefistatus = 1;
            //                                 all_grades[temp_id].finalstatus = 1;
            //                             }

            //                             plot_subject_grades(all_grades);
            //                         });
            //                         // plot_subject_grades(all_grades);

            //                     }

            //                     // else {
            //                     //     Toast.fire({
            //                     //         icon: 'warning',
            //                     //         title: data.message
            //                     //     });
            //                     // }
            //                 },
            //                 error: function() {
            //                     Toast.fire({
            //                         icon: 'error',
            //                         title: 'Something went wrong!'
            //                     });
            //                 }
            //             });
            //         }
            //     });
            // }

            function submit_grade(clickedElement) {

                var selected = []

                var term = $(clickedElement).data('value'); // Get data-value from clicked element

                console.log(term);

                var dterm = term
                term = get_term(term)

                $('.select').each(function() {
                    if ($(this).prop('checked') && !$(this).attr('disabled') && $(this).attr('data-id')) {
                        selected.push($(this).attr('data-id'))
                    }
                })

                if (selected.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please select at least one student before submitting.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                    return; // Exit the function if no items are selected
                }

                Swal.fire({
                    html: '<h4>Are you sure you want <br>' +
                        'to submit grades?</h4>',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Submit Grades!'
                }).then((result) => {
                    if (result.value) {
                        var postData = {
                            syid: $('#filter_sy').val() || undefined,
                            // semid: $('#filter_semester').val() || undefined,
                            term: term || undefined,
                            selected: selected.length > 0 ? selected : undefined
                        };

                        // Remove properties with undefined values
                        Object.keys(postData).forEach(key => postData[key] === undefined && delete postData[
                            key]);

                        $.ajax({
                            type: 'POST',
                            url: '/college/teacher/student/new/grades/submit',
                            data: postData,
                            success: function(data) {
                                if (data.status === 0) {
                                    Toast.fire({
                                        // icon: 'warning',
                                        title: '<i class="fas fa-exclamation-circle" style="color: #ff9800;"></i> &nbsp; ' +
                                            data.message
                                    });
                                } else {

                                    Toast.fire({

                                        title: '<i class="fas fa-check" style="color: #4CAF50;"></i> &nbsp; Submitted!'
                                    });



                                    $.each(selected, function(a, b) {
                                        var inputSelector = '.input_grades[data-id="' +
                                            b + '"][data-term="' + dterm +
                                            '"]';

                                        // if (!$(inputSelector).hasClass('bg-danger')) {
                                        //     $(inputSelector).addClass('bg-success')
                                        //         .removeClass('input_grades bg-warning');

                                        //     $(inputSelector).attr('data-status', 1);
                                        //     $('th[data-studid="' + b +
                                        //             '"][data-term="5"]')
                                        //         .addClass('bg-success');

                                        //     var temp_id = all_grades.findIndex(x => x
                                        //         .id ==
                                        //         b);
                                        //     if (dterm == 1) {
                                        //         all_grades[temp_id].prelemstatus = 1;
                                        //     } else if (dterm == 2) {
                                        //         all_grades[temp_id].midtermstatus = 1;
                                        //     } else if (dterm == 3) {
                                        //         all_grades[temp_id].prefistatus = 1;
                                        //     } else if (dterm == 4) {
                                        //         all_grades[temp_id].finalstatus = 1;
                                        //     } else if (dterm == 5) {
                                        //         all_grades[temp_id].prelemstatus = 1;
                                        //         all_grades[temp_id].midtermstatus = 1;
                                        //         all_grades[temp_id].prefistatus = 1;
                                        //         all_grades[temp_id].finalstatus = 1;
                                        //     }
                                        // }
                                        $(inputSelector).addClass('bg-success')
                                            .removeClass('input_grades bg-warning');

                                        $(inputSelector).attr('data-status', 1);
                                        $('th[data-studid="' + b + '"][data-term="5"]')
                                            .addClass('bg-success');


                                        var temp_id = all_grades.findIndex(x => x.id ==
                                            b);
                                        if (dterm == 1) {
                                            all_grades[temp_id].prelim_status = 1;
                                        } else if (dterm == 2) {
                                            all_grades[temp_id].midterm_status = 1;
                                        } else if (dterm == 3) {
                                            all_grades[temp_id].prefinal_status = 1;
                                        } else if (dterm == 4) {
                                            all_grades[temp_id].final_status = 1;
                                        } else if (dterm == 5) {
                                            all_grades[temp_id].prelim_status = 1;
                                            all_grades[temp_id].midterm_status = 1;
                                            all_grades[temp_id].prefinal_status = 1;
                                            all_grades[temp_id].final_status = 1;
                                        }

                                        plot_subject_grades(all_grades);
                                    });
                                    // plot_subject_grades(all_grades);

                                }

                                // else {
                                //     Toast.fire({
                                //         icon: 'warning',
                                //         title: data.message
                                //     });
                                // }
                            },
                            error: function() {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Something went wrong!'
                                });
                            }
                        });
                    }
                });
            }
            //working code 
            // function submit_grade(clickedElement) {

            //     var selected = []

            //     var term = $(clickedElement).data('value'); // Get data-value from clicked element

            //     console.log(term);

            //     var dterm = term
            //     term = get_term(term)

            //     $('.select').each(function() {
            //         if ($(this).prop('checked') && !$(this).attr('disabled') && $(this).attr('data-id')) {
            //             selected.push($(this).attr('data-id'))
            //         }
            //     })

            //     if (selected.length === 0) {
            //         Swal.fire({
            //             icon: 'warning',
            //             text: 'Please select at least one student before submitting.',
            //             confirmButtonColor: '#3085d6',
            //             confirmButtonText: 'OK'
            //         });
            //         return; // Exit the function if no items are selected
            //     }

            //     Swal.fire({
            //         html: '<h4>Are you sure you want <br>' +
            //             'to submit grades?</h4>',
            //         type: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Submit Grades!'
            //     }).then((result) => {
            //         if (result.value) {
            //             var postData = {
            //                 syid: $('#filter_sy').val() || undefined,
            //                 semid: $('#filter_semester').val() || undefined,
            //                 term: term || undefined,
            //                 selected: selected.length > 0 ? selected : undefined
            //             };

            //             // Remove properties with undefined values
            //             Object.keys(postData).forEach(key => postData[key] === undefined && delete postData[
            //                 key]);

            //             $.ajax({
            //                 type: 'POST',
            //                 url: '/college/teacher/student/new/grades/submit',
            //                 data: postData,
            //                 success: function(data) {
            //                     if (data.status === 0) {
            //                         Toast.fire({
            //                             // icon: 'warning',
            //                             title: '<i class="fas fa-exclamation-circle" style="color: #ff9800;"></i> &nbsp; ' +
            //                                 data.message
            //                         });
            //                     } else {

            //                         Toast.fire({

            //                             title: '<i class="fas fa-check" style="color: #4CAF50;"></i> &nbsp; Submitted!'
            //                         });

            //                         $.each(selected, function(a, b) {
            //                             var inputSelector = '.input_grades[data-id="' +
            //                                 b + '"][data-term="' + dterm +
            //                                 '"]';
            //                             $(inputSelector).addClass('bg-success')
            //                                 .removeClass('input_grades bg-warning');

            //                             $(inputSelector).attr('data-status', 1);
            //                             $('th[data-studid="' + b + '"][data-term="5"]')
            //                                 .addClass('bg-success');


            //                             var temp_id = all_grades.findIndex(x => x.id ==
            //                                 b);
            //                             if (dterm == 1) {
            //                                 all_grades[temp_id].prelemstatus = 1;
            //                             } else if (dterm == 2) {
            //                                 all_grades[temp_id].midtermstatus = 1;
            //                             } else if (dterm == 3) {
            //                                 all_grades[temp_id].prefistatus = 1;
            //                             } else if (dterm == 4) {
            //                                 all_grades[temp_id].finalstatus = 1;
            //                             } else if (dterm == 5) {
            //                                 all_grades[temp_id].prelemstatus = 1;
            //                                 all_grades[temp_id].midtermstatus = 1;
            //                                 all_grades[temp_id].prefistatus = 1;
            //                                 all_grades[temp_id].finalstatus = 1;
            //                             }

            //                             plot_subject_grades(all_grades);
            //                         });
            //                         // plot_subject_grades(all_grades);

            //                     }

            //                     // else {
            //                     //     Toast.fire({
            //                     //         icon: 'warning',
            //                     //         title: data.message
            //                     //     });
            //                     // }
            //                 },
            //                 error: function() {
            //                     Toast.fire({
            //                         icon: 'error',
            //                         title: 'Something went wrong!'
            //                     });
            //                 }
            //             });
            //         }
            //     });
            // }
            // function submit_grade(clickedElement) {

            //     var selected = []

            //     var term = $(clickedElement).data('value'); // Get data-value from clicked element

            //     console.log(term);

            //     var dterm = term
            //     term = get_term(term)

            //     $('.select').each(function() {
            //         if ($(this).prop('checked') && !$(this).attr('disabled') && $(this).attr('data-id')) {
            //             selected.push($(this).attr('data-id'))
            //         }
            //     })

            //     if (selected.length === 0) {
            //         Swal.fire({
            //             icon: 'warning',
            //             text: 'Please select at least one student before submitting.',
            //             confirmButtonColor: '#3085d6',
            //             confirmButtonText: 'OK'
            //         });
            //         return; // Exit the function if no items are selected
            //     }

            //     Swal.fire({
            //         html: '<h4>Are you sure you want <br>' +
            //             'to submit grades?</h4>',
            //         type: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Submit Grades!'
            //     }).then((result) => {
            //         if (result.value) {
            //             var postData = {
            //                 syid: $('#filter_sy').val() || undefined,
            //                 semid: $('#filter_semester').val() || undefined,
            //                 term: term || undefined,
            //                 selected: selected.length > 0 ? selected : undefined
            //             };

            //             // Remove properties with undefined values
            //             Object.keys(postData).forEach(key => postData[key] === undefined && delete postData[
            //                 key]);

            //             $.ajax({
            //                 type: 'POST',
            //                 url: '/college/teacher/student/new/grades/submit',
            //                 data: postData,
            //                 success: function(data) {
            //                     if (data[0].status == 1) {

            //                         Toast.fire({
            //                             icon: 'success',
            //                             title: 'Submitted!'
            //                         });

            //                         $.each(selected, function(a, b) {
            //                             var inputSelector = '.input_grades[data-id="' +
            //                                 b + '"][data-term="' + dterm +
            //                                 '"]';
            //                             $(inputSelector).addClass('bg-success')
            //                                 .removeClass('input_grades');

            //                             $(inputSelector).attr('data-status', 1);
            //                             $('th[data-studid="' + b + '"][data-term="5"]')
            //                                 .addClass('bg-success');


            //                             var temp_id = all_grades.findIndex(x => x.id ==
            //                                 b);
            //                             if (dterm == 1) {
            //                                 all_grades[temp_id].prelemstatus = 1;
            //                             } else if (dterm == 2) {
            //                                 all_grades[temp_id].midtermstatus = 1;
            //                             } else if (dterm == 3) {
            //                                 all_grades[temp_id].prefistatus = 1;
            //                             } else if (dterm == 4) {
            //                                 all_grades[temp_id].finalstatus = 1;
            //                             } else if (dterm == 5) {
            //                                 all_grades[temp_id].prelemstatus = 1;
            //                                 all_grades[temp_id].midtermstatus = 1;
            //                                 all_grades[temp_id].prefistatus = 1;
            //                                 all_grades[temp_id].finalstatus = 1;
            //                             }

            //                             plot_subject_grades(all_grades);
            //                         });
            //                         // plot_subject_grades(all_grades);

            //                     } else {
            //                         Toast.fire({
            //                             icon: 'error',
            //                             title: 'Something went wrong!'
            //                         });
            //                     }
            //                 },
            //                 error: function() {
            //                     Toast.fire({
            //                         icon: 'error',
            //                         title: 'Something went wrong!'
            //                     });
            //                 }
            //             });
            //         }
            //     });
            // }



            function inc_grade() {

                var selected = []
                var students = []
                var term = $('#quarter_select').val()
                term = get_term(term)

                $('.select').each(function() {
                    if ($(this).prop('checked') == true && $(this).attr('disabled') == undefined) {
                        selected.push($(this).attr('data-id'))
                        students.push($(this).attr('data-id'))
                    }
                })

                Swal.fire({
                    html: '<h4>Are you sure you want <br>' +
                        'to mark student as INC?</h4>',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Submit Grades!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: '/college/teacher/student/grades/inc',
                            data: {
                                syid: $('#filter_sy').val(),
                                // semid: $('#filter_semester').val(),
                                term: term,
                                selected: selected,
                            },
                            success: function(data) {
                                if (data[0].status == 1) {
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Submitted Successfully!'
                                    })
                                    $.each(selected, function(a, b) {
                                        $('.select[data-id="' + b + '"]').attr(
                                            'disabled', 'disabled')
                                        $('.input_grades[data-id="' + b +
                                            '"][data-term="' + term + '"]').attr(
                                            'data-status', 1)
                                        $('.input_grades[data-id="' + b +
                                                '"][data-term="' + term + '"]')
                                            .addClass('bg-success')
                                        $('.input_grades[data-id="' + b +
                                                '"][data-term="' + term + '"]')
                                            .removeClass('input_grades')
                                        var temp_id = all_grades.findIndex(x => x.id ==
                                            b)

                                        if (term == 'prelim_transmuted') {
                                            all_grades[temp_id].prelim_status = 1
                                        } else if (term == 'midterm_transmuted') {
                                            all_grades[temp_id].midterm_status = 1
                                        } else if (term == 'prefinal_transmuted') {
                                            all_grades[temp_id].prefinal_status = 1
                                        } else if (term == 'final_transmuted') {
                                            all_grades[temp_id].final_status = 1
                                        }
                                        plot_subject_grades(all_grades)

                                    })
                                } else {
                                    Toast.fire({
                                        type: 'error',
                                        title: 'Something went wrong!'
                                    })
                                }
                            },
                            error: function() {
                                Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong!'
                                })
                            }
                        })
                    }
                })

            }

            $(document).on('click', '#save_grades', function() {

                $('#save_grades').text('Saving Grades...')
                $('#save_grades').removeClass('btn-primary')
                $('#save_grades').addClass('btn-secondary')
                $('#save_grades').attr('disabled', 'disabled')

                var schedid = $(this).attr('data-schedid');

                if ($('.updated[data-term="1"]').length == 0) {
                    save_midterm(schedid)
                }

                $('.updated[data-term="1"]').each(function(a, b) {
                    var studid = $(this).attr('data-studid')
                    var term = $(this).attr('data-term')
                    var courseid = $(this).attr('data-course')
                    var sectionid = $(this).attr('data-section')
                    var pid = $(this).attr('data-pid')
                    var termgrade = $(this).text()
                    var td = $(this)
                    $.ajax({
                        type: 'POST',
                        url: '/college/teacher/student/grades/save',
                        data: {
                            syid: $('#filter_sy').val(),
                            semid: $('#filter_semester').val(),
                            term: "prelim_transmuted",
                            sectionid: sectionid,
                            termgrade: termgrade,
                            studid: studid,
                            courseid: courseid,
                            pid: pid,
                            schedid: schedid,
                        },
                        success: function(data) {
                            $(td).removeClass('updated')
                            if ($('.updated[data-term="1"]').length == 0) {
                                save_midterm(schedid)
                            }

                        }
                    })
                })


            })

            function save_midterm(schedid) {
                if ($('.updated[data-term="2"]').length == 0) {
                    save_prefi(schedid)
                }
                $('.updated[data-term="2"]').each(function(a, b) {
                    var studid = $(this).attr('data-studid')
                    var term = $(this).attr('data-term')
                    var courseid = $(this).attr('data-course')
                    var sectionid = $(this).attr('data-section')
                    var pid = $(this).attr('data-pid')
                    var termgrade = $(this).text()
                    var td = $(this)
                    $.ajax({
                        type: 'POST',
                        url: '/college/teacher/student/grades/save',
                        data: {
                            syid: $('#filter_sy').val(),
                            semid: $('#filter_semester').val(),
                            term: "midterm_transmuted",
                            sectionid: sectionid,
                            termgrade: termgrade,
                            studid: studid,
                            courseid: courseid,
                            pid: pid,
                            schedid: schedid,
                        },
                        success: function(data) {
                            $(td).removeClass('updated')
                            if ($('.updated[data-term="2"]').length == 0) {
                                save_prefi(schedid)
                            }
                        }
                    })
                })

            }

            function save_prefi(schedid) {
                if ($('.updated[data-term="3"]').length == 0) {
                    save_final(schedid)
                }
                $('.updated[data-term="3"]').each(function(a, b) {
                    var studid = $(this).attr('data-studid')
                    var term = $(this).attr('data-term')
                    var courseid = $(this).attr('data-course')
                    var sectionid = $(this).attr('data-section')
                    var pid = $(this).attr('data-pid')
                    var termgrade = $(this).text()
                    var td = $(this)
                    $.ajax({
                        type: 'POST',
                        url: '/college/teacher/student/grades/save',
                        data: {
                            syid: $('#filter_sy').val(),
                            semid: $('#filter_semester').val(),
                            term: "prefinal_transmuted",
                            sectionid: sectionid,
                            termgrade: termgrade,
                            studid: studid,
                            courseid: courseid,
                            pid: pid,
                            schedid: schedid,
                        },
                        success: function(data) {
                            $(td).removeClass('updated')
                            if ($('.updated[data-term="3"]').length == 0) {
                                save_final(schedid)
                            }
                        }
                    })
                })

            }

            function save_final(schedid) {
                if ($('.updated[data-term="4"]').length == 0) {
                    save_fg(schedid)
                }
                $('.updated[data-term="4"]').each(function(a, b) {
                    var studid = $(this).attr('data-studid')
                    var term = $(this).attr('data-term')
                    var courseid = $(this).attr('data-course')
                    var sectionid = $(this).attr('data-section')
                    var pid = $(this).attr('data-pid')
                    var termgrade = $(this).text()
                    var td = $(this)
                    $.ajax({
                        type: 'POST',
                        url: '/college/teacher/student/grades/save',
                        data: {
                            syid: $('#filter_sy').val(),
                            semid: $('#filter_semester').val(),
                            term: "final_transmuted",
                            sectionid: sectionid,
                            termgrade: termgrade,
                            studid: studid,
                            courseid: courseid,
                            pid: pid,
                            schedid: schedid,
                        },
                        success: function(data) {
                            $(td).removeClass('updated')
                            if ($('.updated[data-term="4"]').length == 0) {
                                save_fg(schedid)
                            }
                        }
                    })
                })
            }

            function save_fg() {
                if ($('.updated[data-term="5"]').length == 0) {
                    save_fgremarks()
                }
                $('.updated[data-term="5"]').each(function(a, b) {
                    var studid = $(this).attr('data-studid')
                    var term = $(this).attr('data-term')
                    var courseid = $(this).attr('data-course')
                    var sectionid = $(this).attr('data-section')
                    var pid = $(this).attr('data-pid')
                    var termgrade = $(this).text()
                    var td = $(this)
                    $.ajax({
                        type: 'POST',
                        url: '/college/teacher/student/grades/save',
                        data: {
                            syid: $('#filter_sy').val(),
                            semid: $('#filter_semester').val(),
                            term: "final_grade_transmuted",
                            sectionid: sectionid,
                            termgrade: termgrade,
                            studid: studid,
                            courseid: courseid,
                            pid: pid,
                        },
                        success: function(data) {
                            $(td).removeClass('updated')
                            if ($('.updated[data-term="5"]').length == 0) {
                                save_final()
                            }
                        }
                    })
                })
            }

            function save_fgremarks() {
                if ($('.updated[data-term="6"]').length == 0) {
                    Toast.fire({
                        type: 'success',
                        title: 'Saved Successfully!'
                    })
                    $('#save_grades').attr('disabled', 'disabled')
                    $('#save_grades').removeClass('btn-secondary')
                    $('#save_grades').addClass('btn-primary')
                    $('#save_grades').text('Save Grades')
                    $('#grade_submit').removeAttr('disabled')

                    var temp_students = all_students.filter(x => x.schedid == schedid)
                    get_grades(schedid, false, temp_students[0].students)

                }
                $('.updated[data-term="6"]').each(function(a, b) {
                    var studid = $(this).attr('data-studid')
                    var term = $(this).attr('data-term')
                    var courseid = $(this).attr('data-course')
                    var sectionid = $(this).attr('data-section')
                    var pid = $(this).attr('data-pid')
                    var termgrade = $(this).text()
                    var td = $(this)
                    $.ajax({
                        type: 'POST',
                        url: '/college/teacher/student/grades/save',
                        data: {
                            syid: $('#filter_sy').val(),
                            semid: $('#filter_semester').val(),
                            term: "final_remarks",
                            sectionid: sectionid,
                            termgrade: termgrade,
                            studid: studid,
                            courseid: courseid,
                            pid: pid,
                        },
                        success: function(data) {
                            $(td).removeClass('updated')
                            if ($('.updated[data-term="6"]').length == 0) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Saved Successfully!'
                                })
                                $('#save_grades').attr('disabled', 'disabled')
                                $('#save_grades').removeClass('btn-secondary')
                                $('#save_grades').addClass('btn-primary')
                                $('#save_grades').text('Save Grades')
                                $('#grade_submit').removeAttr('disabled')
                                var temp_students = all_students.filter(x => x.schedid ==
                                    schedid)
                                get_grades(schedid, false, temp_students[0].students)
                                // get_grades(schedid, false, temp_students[0].students)
                            }
                        }
                    })
                })
            }




            var school = @json($schoolinfo);



            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var all_subject = []
            get_subjects()
            get_subjectsforselected()

            var schedid = null;
            $(document).on('click', '.submit_grade', function() {
                var temp_button = $(this)
                temp_button.attr('disabled', 'disabled')
                var term = $(this).attr('data-term')
                $.ajax({
                    type: 'POST',
                    url: '/college/teacher/student/new/grades/submit',
                    data: {
                        schedid: schedid,
                        term: term,
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Submitted Successfully!'
                            })

                        } else {
                            temp_button.removeAttr('disabled')
                            Toast.fire({
                                type: 'danger',
                                title: 'Something went wrong!'
                            })
                        }
                    },
                    error: function() {
                        temp_button.removeAttr('disabled')
                        Toast.fire({
                            type: 'danger',
                            title: 'Something went wrong!'
                        })
                    }
                })
            })


            $(document).on('change', '#filter_sy , #filter_semester', function(data) {
                all_gradestatus = []
                // datatable_3()
                all_subject = []
                getinputperiod()
                getgradesetup()
                datatable_1(data)
                get_subjects()
                get_subjectsforselected()
            })

            $(document).on('change', '#term', function(data) {
                // datatable_3()
                datatable_1(data)
            })

            function get_subjects() {
                $.ajax({
                    type: 'GET',
                    url: '/college/teacher/schedule/get',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        teacherid: 73
                    },
                    success: function(data) {
                        if (data.length == 0) {
                            Toast.fire({
                                type: 'warning',
                                title: 'No records Found!'
                            })
                        } else {
                            all_subject = data
                            // all_students = data



                            get_student_all(data)
                            // get_selected_student(data)
                        }
                    }
                })
            }

            function get_subjectsforselected() {
                $.ajax({
                    type: 'GET',
                    url: '/college/teacher/schedule/get',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        teacherid: 73
                    },
                    success: function(data) {
                        if (data.length == 0) {
                            Toast.fire({
                                type: 'warning',
                                title: 'No records Found!'
                            })
                        } else {
                            // all_subject = data
                            all_students = data



                            // get_student_all(data)
                            get_selected_student(data)
                        }
                    }
                })
            }

            // get_subjectsforselected()




            function get_selected_student(data) {
                let requests = [];

                $.each(data, function(a, persched) {
                    let request = $.ajax({
                        type: 'GET',
                        url: `/college/teacher/student-list-for-all/${$('#filter_sy').val()}/${$('#filter_semester').val()}/` +
                            persched.schedid,
                        success: function(studentData) {
                            persched.students = studentData.students;
                            all_students = persched



                        }
                    });
                    requests.push(request);
                });

                // Wait for all requests to complete
                $.when.apply($, requests).then(function() {

                    all_students = data;


                });
            }

            function get_student_all(data) {
                let requests = [];

                $.each(data, function(a, b) {
                    let request = $.ajax({
                        type: 'GET',
                        url: `/college/teacher/student-list-for-all/${$('#filter_sy').val()}/${$('#filter_semester').val()}/` +
                            b.schedid,
                        success: function(studentData) {
                            b.students = studentData;
                            all_subject = b
                        }
                    });
                    requests.push(request);
                });

                // Wait for all requests to complete
                $.when.apply($, requests).then(function() {
                    datatable_1(data); // Pass the full schedule data with students
                    all_subject = data;
                });
            }

            var all_gradestatus = []

            function grade_status() {
                $.ajax({
                    type: 'GET',
                    url: '/college/teacher/student/grades/status/get',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                    },
                    success: function(data) {
                        all_gradestatus = data
                        datatable_3()
                    }
                })


            }

            $(document).on('click', '.view_students', function() {
                $('#modal_1').modal()
                temp_id = $(this).attr('data-id')
                var students = all_subject.filter(x => x.schedid == temp_id)
                $('#subjectDescs').text(students[0].subjDesc)
                $('#subjectCodes').text(students[0].subjCode)
                $('#collegeLevels').text(students[0].yearDesc)
                $('#sections').text(students[0].sectionDesc)

                $('#sectionNameHeader').text(students[0].subjCode + ' - ' + students[0].subjDesc)

                datatable_2(students[0].students)
            })


            $(document).on('click', '.view_grades', function() {

                setTimeout(function() {
                    $('.select').prop('checked', true);
                }, 0);

                if (gradesetup.length == 0) {
                    $('.term_holder[data-term=1]').remove()
                    $('.term_holder[data-term=2]').remove()
                    $('.term_holder[data-term=3]').remove()
                    $('.term_holder[data-term=4]').remove()
                }

                $('#message_holder').text('')
                $('#save_grades').attr('hidden', 'hidden')
                $('#modal_2').modal()
                temp_id = $(this).attr('data-id')
                schedid = temp_id

                $('#save_grades').attr('data-schedid', $(this).attr('data-id'))

                $('.with_submission_info').remove()
                $('.submit_grade').attr('hidden', 'hidden')

                var students = all_subject.filter(x => x.schedid == temp_id)
                var selected_students = []

                if (all_students.length != 0) {
                    var selected_students = all_students.filter(x => x.schedid == temp_id)
                }

                $('#subjectDesc').text(students[0].subjDesc)
                $('#subjectDesc').attr('data-id', students[0].subjectid)
                $('#subjectCode').text(students[0].subjCode)
                $('#collegeLevel').text(students[0].yearDesc)
                $('#collegeLevel').attr('data-id', students[0].levelid)
                console.log(students[0].levelid, 'levelid DAW NA');
                $('#section').text(students[0].sectionDesc)
                $('#section').attr('data-id', students[0].sectionid)

                $('#sectionNameHeader_viewgrades').text(students[0].subjCode + ' - ' + students[0].subjDesc)

                $('#student_list_grades').empty()

                var maleCount = 0;
                var femaleCount = 0;
                var female = 0;
                var male = 0;
                var pid = students[0].subjectid
                var sectionid = students[0].sectionid

                if (students[0].students.length == 0) {
                    Toast.fire({
                        type: 'warning',
                        title: 'No student Found!'
                    })
                    $('.enrolled_students_holder, .grade_remarks_holder, .submit_all_btn, .enrolled_students_label, .count_male_label, .count_male, .count_female_label, .count_female, .count_total_gender_label, .count_total_gender, .grade_remarks_label, .count_passed_label, .count_passed, .count_failed_label, .count_failed, .grade_status_label, .count_submitted_label, .count_submitted, .count_approved_label, .count_approved, .count_pending_label, .count_pending, .count_posted_label, .count_posted')
                        .hide()
                    return false
                } else {
                    $('.submit_all_btn').removeAttr('hidden')
                    $('#save_grades').removeAttr('hidden')
                    $('.enrolled_students_holder, .grade_remarks_holder, .submit_all_btn, .enrolled_students_label, .count_male_label, .count_male, .count_female_label, .count_female, .count_total_gender_label, .count_total_gender, .grade_remarks_label, .count_passed_label, .count_passed, .count_failed_label, .count_failed, .grade_status_label, .count_submitted_label, .count_submitted, .count_approved_label, .count_approved, .count_pending_label, .count_pending, .count_posted_label, .count_posted')
                        .show()
                }

                selected_students[0].students.sort((a, b) => {
                    if (a.gender === 'MALE' && b.gender === 'FEMALE') {
                        return -1;
                    } else if (a.gender === 'FEMALE' && b.gender === 'MALE') {
                        return 1;
                    } else {
                        return a.gender.localeCompare(b.gender);
                    }
                });

                $('#datatable_4').empty()

                $('.student_count').text(students[0].students.length)
                var colspan = 9;

                var disprelim = 0
                var dismidterm = 0
                var disprefi = 0
                var disfinal = 0

                // if (gradesetup != null) {
                //     disprelim = gradesetup.prelim
                //     dismidterm = gradesetup.midterm
                //     disprefi = gradesetup.prefi
                //     disfinal = gradesetup.final
                // }

                // var gradesetup = students[0].gradesetup


                if (gradesetupS.length > 0) {
                    gradesetupS.forEach(grade => {
                        if (grade.quarter == 1) disprelim = 1;
                        if (grade.quarter == 2) dismidterm = 1;
                        if (grade.quarter == 3) disprefi = 1;
                        if (grade.quarter == 4) disfinal = 1;
                    });
                }

                if (disprelim == 0) {
                    $('#quarter_select option[value="1"]').attr('hidden', 'hidden')
                    $('.term_holder[data-term=1]').remove()
                    colspan -= 1
                }
                if (dismidterm == 0) {
                    $('#quarter_select option[value="2"]').attr('hidden', 'hidden')
                    $('.term_holder[data-term=2]').remove()
                    colspan -= 1
                }
                if (disprefi == 0) {
                    $('#quarter_select option[value="3"]').attr('hidden', 'hidden')
                    $('.term_holder[data-term=3]').remove()
                    colspan -= 1
                }
                if (disfinal == 0) {
                    $('#quarter_select option[value="4"]').attr('hidden', 'hidden')
                    $('.term_holder[data-term=4]').remove()
                    colspan -= 1
                }

                $('#excel_termid').select2({
                    width: '100%'
                })

                $('#quarter_select').select2()

                $('#deadline_holder').empty()
                var deadlinetext =
                    '<td colspan="2" class="text-left align-middle p-0 ml-2"><b>Date Deadline</b></td>'

                function appendDeadline(term) {
                    var checkDateSetup = inputperiod.filter(x => x.term == term)
                    if (checkDateSetup.length > 0) {
                        var bg = checkDateSetup[0].isended ? 'bg-success' : 'bg-danger'
                        deadlinetext += '<td class="p-0 text-center ' + bg + '">' + checkDateSetup[0]
                            .endformat2 + '</td>'
                    } else {
                        deadlinetext += '<td class="p-0 text-center align-middle">Not Set</td>'
                    }
                }

                if (disprelim == 1) appendDeadline('prelim')
                if (dismidterm == 1) appendDeadline('midterm')
                if (disprefi == 1) appendDeadline('prefi')
                if (disfinal == 1) appendDeadline('finalterm')

                deadlinetext += '<td class="p-0"></td><td class="p-0"></td>'
                $('#deadline_holder').append(deadlinetext)

                var maleCount = 1;
                var femaleCount = 1;

                $.each(selected_students[0].students, function(a, b) {
                    if (male == 0 && b.gender == 'MALE') {
                        $('#student_list_grades').append(`
                        <tr class="gender_label male_section" id="male_section" style="background-color: #8ec9fd;">
                            <th colspan="${colspan - 8}" id="male_label">
                                <span>MALE</span>
                            </th>
                            <th>
                               <div style="display: flex; justify-content: flex-end; width: 100%;" id="male_sort_check">
                                    <i class="sort-icon sort-asc" data-gender="MALE" data-sort="name" style="cursor: pointer;margin-right: 10px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
                                        </svg>
                                    </i>
                                    <i class="sort-icon sort-desc" data-gender="MALE" data-sort="name" style="cursor: pointer; display: none;margin-right: 10px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
                                        </svg>
                                    </i>
                                    <input type="checkbox" class="select" id="malecheckbox" />
                                </div>
                            </th>
                            <th colspan="${colspan}"></th>
                           
                        </tr>   
                        `);
                        male = 1;
                        maleCount = 1;
                    } else if (female == 0 && b.gender == 'FEMALE') {
                        $('#student_list_grades').append(`
                        <tr class="gender_label female_section" id="female_section" style="background-color: #fd8ec9;">
                            <th colspan="${colspan - 8}" id="female_label">
                                <span>FEMALE</span>
                            </th>
                            <th>
                                <div style="display: flex; justify-content: flex-end; width: 100%;" id="female_sort_check">
                                    <i class="sort-icon sort-asc" data-gender="FEMALE" data-sort="name" style="cursor: pointer;margin-right: 10px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4 a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
                                        </svg>
                                    </i>
                                    <i class="sort-icon sort-desc" data-gender="FEMALE" data-sort="name" style="cursor: pointer; display: none;margin-right: 10px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
                                        </svg>
                                    </i>
                                    <input type="checkbox" class="select" id="femalecheckbox" />
                                </div>
                            </th>
                            <th colspan="${colspan}"></th>
                        </tr>`);
                        female = 1;
                        femaleCount = 1;
                    }

                    var count = b.gender === 'MALE' ? maleCount++ : femaleCount++;

                    var text = '<tr data-gender="' + b.gender + '"><td class="text-center count">' +
                        count + '</td><td>' + b
                        .student_name +
                        '<input type="checkbox" class="select student-checkbox" data-studid="' +
                        b.studid + '" data-id="' + b.id + '" style="float:right;"></td>';

                    if (disprelim == 1) {
                        text += '<td data-studid="' + b.studid + '" data-course="' + b.courseid +
                            '" data-pid="' + pid + '" data-section="' + sectionid +
                            '" class="grade_td term_holder" data-term="1" ></td>'
                    }
                    if (dismidterm == 1) {
                        text += '<td data-studid="' + b.studid + '" data-course="' + b.courseid +
                            '" data-pid="' + pid + '" data-section="' + sectionid +
                            '" class="grade_td term_holder" data-term="2"></td>'
                    }
                    if (disprefi == 1) {
                        text += '<td data-studid="' + b.studid + '" data-course="' + b.courseid +
                            '" data-pid="' + pid + '" data-section="' + sectionid +
                            '" class="grade_td term_holder" data-term="3"></td>'
                    }
                    if (disfinal == 1) {
                        text += '<td data-studid="' + b.studid + '" data-course="' + b.courseid +
                            '" data-pid="' + pid + '" data-section="' + sectionid +
                            '" class="grade_td term_holder" data-term="4" ></td>'
                    }

                    text += '<th data-studid="' + b.studid + '" data-course="' + b.courseid +
                        '" data-pid="' + pid + '" data-section="' + sectionid +
                        '" class="term_holder text-center" data-term="5"></th>'

                    text += '<th data-studid="' + b.studid + '" data-course="' + b.courseid +
                        '" data-pid="' + pid + '" data-section="' + sectionid +
                        '" class="term_holder text-center" data-term="6"></th>'

                    text += '</tr>'

                    $('#student_list_grades').append(text)
                    $('#datatable_4').append(
                        '<tr><td><input checked="checked" type="checkbox" class="select2" data-studid="' +
                        b.studid + '" data-id="' + b.id + '"></td><td>' + b.studid +
                        '</td><td>' +
                        b.student + '</td><td data-studid="' + b.studid +
                        '" class="grade_submission_student text-center"></td></tr>')
                })

                $('#maleCount').text(maleCount - 1);
                $('#femaleCount').text(femaleCount - 1);
                $('#totalCount').text(selected_students[0].students.length)
                $('.grade_td').addClass('text-center align-middle')

                // Disable checkbox if remark is 'DROPPED'
                // const remarkElement = $('#datatable_4').find('tr:last-child').find('th[data-term="6"]');
                // if (remarkElement.text().trim() === 'DROPPED') {
                //     $('#datatable_4').find('tr:last-child').find('input[type="checkbox"]').prop('disabled',
                //         true);
                // }

                // const remarkElement = $(this).find('th[data-term="6"]');
                // const remark = remarkElement.text().trim();

                // if (remark === 'DROPPED' || remark === 'Dropped') {
                //     // $(this).find('input[type="checkbox"]').prop('disabled', true);
                //     $('.select').prop('checked', false);
                // }



                function updatePassFailCounts() {
                    let passedCount = 0;
                    let failedCount = 0;
                    let incCount = 0;

                    $('#student_list_grades tr').each(function() {
                        const remarkElement = $(this).find('th[data-term="6"]');
                        const remark = remarkElement.text().trim();
                        if (remark === 'PASSED' || remark === 'Passed') {
                            passedCount++;
                            remarkElement.addClass('bg-success').removeClass('bg-danger');
                        } else if (remark === 'FAILED' || remark === 'Failed') {
                            failedCount++;
                            remarkElement.addClass('bg-danger').removeClass('bg-success');
                        } else if (remark === 'INC' || remark === 'Inc') {
                            incCount++;
                            remarkElement.addClass('bg-warning').removeClass(
                                'bg-success bg-danger');
                        }
                        //  else if (remark === 'DROPPED' || remark === 'Dropped') {
                        //     $(this).find('input[type="checkbox"]').prop('disabled', true);

                        // }
                    });

                    $('#passedCount').text(passedCount);
                    $('#failedCount').text(failedCount);
                    $('#incCount').text(incCount);
                }

                setTimeout(updatePassFailCounts, 500); // Call the function once after 500ms

                $(document).on('click', '#save_grades', function() {
                    setTimeout(updatePassFailCounts, 500); // Call the function once after 500ms
                });

                get_grades(schedid, true, students);

                $('#upload_ecr').submit(function(e) {

                    // temp_id = $(this).attr('data-id')
                    // var students = all_subject.filter(x => x.schedid == temp_id)

                    if ($('#filter_quarter').val() == "") {
                        Toast.fire({
                            type: 'warning',
                            title: 'Please select quarter'
                        })
                        return false;
                    } else if ($('#input_ecr').val() == "") {
                        Toast.fire({
                            type: 'warning',
                            title: 'Please attach a file'
                        })
                        return false;
                    }

                    var inputs = new FormData(this)

                    inputs.append('input_ecr', $('#input_ecr').val())
                    inputs.append('syid', $('#filter_sy').val())
                    inputs.append('levelid', $('#collegeLevel').data('id'))
                    inputs.append('sectionid', $('#section').data('id'))
                    inputs.append('subjid', $('#subjectDesc').data('id'))
                    inputs.append('input_term', $('#excel_termid').val())
                    inputs.append('input_coordinates', $('#excel_cell_coordinates').val())
                    // inputs.append('ecrformat', $('#ecr_format').val())

                    $('#upload_ecr_button').text('Uploading...')
                    $('#upload_ecr_button').attr('disabled', 'disabled')

                    // Show the loading spinner
                    Swal.fire({
                        title: 'Uploading Excel File...',
                        text: 'Please wait while we upload and process your Excel file.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        onBeforeOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '/college/grade/ecr/upload',
                        type: 'POST',
                        data: inputs,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            Swal.close();
                            if (data[0].status == 0) {
                                Toast.fire({
                                    type: 'warning',
                                    title: data[0].message
                                })

                                $('#upload_ecr_button').text('Update ECR')
                                $('#upload_ecr_button').removeAttr('disabled')
                                $('#input_ecr').val("")
                                $('#excel_cell_coordinates').val("AI16")
                                $('#excel_termid').val('').trigger('change');
                            } else {
                                Toast.fire({
                                    type: 'success',
                                    title: data[0].message
                                })
                                $('#upload_ecr_button').text('Update ECR')
                                $('#upload_ecr_button').removeAttr('disabled')
                                $('#input_ecr').val("")
                                $('#excel_cell_coordinates').val("AI16")
                                $('#excel_termid').val('').trigger('change');
                                // load_ecr()
                                // get_grades(temp_id, true, students);



                            }

                            get_grades(schedid, true, students);
                            setTimeout(updatePassFailCounts, 500);
                        },
                        error: function() {
                            Swal.close();
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong'
                            });
                            $('#upload_ecr_button').text('Update ECR')
                            $('#upload_ecr_button').removeAttr('disabled')
                            $('#input_ecr').val("")
                            $('#excel_cell_coordinates').val("AI16")
                            $('#excel_termid').val('').trigger('change');
                        }
                    })
                    e.preventDefault();
                })

            });

            $(document).on('click', '.sort-iconn', function() {
                var colspan = 9;
                var $this = $(this);
                var sortDirection = $this.hasClass('sort-asc') ? 'desc' : 'asc';

                $this.toggle();
                $this.siblings('.sort-iconn').toggle();

                var sortAttempts = $this.data('sort-attempts') || 0;
                sortAttempts++;
                $this.data('sort-attempts', sortAttempts);

                $('#male_sort_check, #female_sort_check').hide();
                $('#male_label, #female_label').hide();
                $('#male_section, #female_section').hide();
                $('#appended_male_label, #appended_female_label').hide();

                var rows = $('#student_list_grades tr').get();
                rows.sort(function(a, b) {
                    var keyA = $(a).find('td').eq(1).text().toUpperCase();
                    var keyB = $(b).find('td').eq(1).text().toUpperCase();
                    if (sortDirection === 'asc') {
                        return keyA.localeCompare(keyB);
                    } else {
                        return keyB.localeCompare(keyA);
                    }
                });

                $('#student_list_grades').empty().append(rows);

                if (sortAttempts >= 2) {
                    var maleRows = [];
                    var femaleRows = [];
                    $('#student_list_grades tr').each(function() {
                        var gender = $(this).data('gender');
                        if (gender === 'MALE') {
                            maleRows.push(this);
                        } else if (gender === 'FEMALE') {
                            femaleRows.push(this);
                        }
                    });

                    $('#student_list_grades').empty()
                        .append(
                            `<tr class="gender_label male_section" id="male_section" style="background-color: #8ec9fd;">
                                    <th colspan="${colspan - 8}" id="appended_male_label">
                                        <span>MALE</span>
                                    </th>
                                    <th>
                                        <div style="display: flex; justify-content: flex-end; width: 100%;" id="male_sort_check">
                                            <i class="sort-icon sort-asc" data-gender="MALE" data-sort="name" style="cursor: pointer; margin-right: 10px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
                                                </svg>
                                            </i>
                                            <i class="sort-icon sort-desc" data-gender="MALE" data-sort="name" style="cursor: pointer; display: none; margin-right: 10px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
                                                </svg>
                                            </i>
                                            <input type="checkbox" class="select" id="malecheckbox" />
                                        </div>
                                    </th>
                                    <th colspan="${colspan}"></th>
                                </tr>`)
                        .append(maleRows)
                        .append(
                            `<tr class="gender_label female_section" id="female_section" style="background-color: #fd8ec9;">
                                    <th colspan="${colspan - 8}" id="appended_female_label">
                                        <span>FEMALE</span>
                                    </th>
                                    <th>
                                        <div style="display: flex; justify-content: flex-end; width: 100%;" id="female_sort_check">
                                            <i class="sort-icon sort-asc" data-gender="FEMALE" data-sort="name" style="cursor: pointer; margin-right: 10px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
                                                </svg>
                                            </i>
                                            <i class="sort-icon sort-desc" data-gender="FEMALE" data-sort="name" style="cursor: pointer; display: none; margin-right: 10px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
                                                </svg>
                                            </i>
                                            <input type="checkbox" class="select" id="femalecheckbox" />
                                        </div>
                                    </th>
                                    <th colspan="${colspan}"></th>
                                </tr>`
                        )
                        .append(femaleRows);
                    $this.data('sort-attempts', 0);
                }


            });

            $(document).on('click', '.sort-icon', function() {
                var $this = $(this);
                var sortDirection = $this.hasClass('sort-asc') ? 'desc' : 'asc';
                var column = $this.data('sort');
                var gender = $this.data('gender');

                $this.toggle();
                $this.siblings('.sort-icon').toggle();

                var $headerRow = $this.closest('tr');
                var $nextRow = $headerRow.next();
                var rowsToSort = [];

                while ($nextRow.length && !$nextRow.hasClass('gender_label')) {
                    rowsToSort.push($nextRow.get(0));
                    $nextRow = $nextRow.next();
                }

                rowsToSort.sort(function(a, b) {
                    var keyA = $(a).find('td').eq(1).text().toUpperCase();
                    var keyB = $(b).find('td').eq(1).text().toUpperCase();

                    if (sortDirection === 'asc') {
                        return keyA.localeCompare(keyB);
                    } else {
                        return keyB.localeCompare(keyA);
                    }
                });

                $.each(rowsToSort, function(index, row) {
                    $headerRow.after(row);
                    $headerRow = $(row);
                });


            });



            var all_grades = []
            var dean = @json($dean)

            $('#printable_dean').select2({
                'data': dean,
                'placeholder': 'Select Dean'
            })


            $(document).on('click', '#print_grades_to_modal', function() {
                $('#dean_holder_modal').modal()
            })


            $(document).on('click', '#print_grades', function() {
                print_grades()
            })

            function print_grades() {

                var pid = []
                var sectionid = []
                // var students = all_subject.filter(x => x.schedid == schedid)[0].students
                var students = all_subject.filter(x => x.schedid == temp_id)
                // var temp_pid = [...new Map(students.map(item => [item['pid'], item])).values()]
                var temp_pid = [...new Map(students.map(item => [item['pid'], item])).values()]
                var temp_sectionid = [...new Map(students.map(item => [item['sectionid'], item])).values()]


                $.each(temp_pid, function(a, b) {
                    pid.push(b.subjectid)
                    // pid.push(b.pid)
                })
                $.each(temp_sectionid, function(a, b) {
                    sectionid.push(b.sectionid)
                })

                var temp_subjid = temp_pid[0].subjectid


                var syid = $('#filter_sy').val()
                var semid = $('#filter_semester').val()
                var pid = pid
                var sectionid = sectionid
                var dean = $('#printable_dean').val()

                window.open('/college/teacher/student/grades/print?&syid=' + syid + '&semid=' + semid + '&pid=' +
                    pid + '&sectionid=' + sectionid + '&schedid=' + temp_id + '&subjid=' + temp_subjid +
                    '&dean=' + dean, '_blank');

            }


            //v2
            // function get_grades(schedid, prompt = true, students) {
            //     var pid = [];
            //     var sectionid = [];
            //     var temp_pid = [...new Map(students.map(item => [item['pid'], item])).values()];
            //     var temp_sectionid = [...new Map(students.map(item => [item['sectionid'], item])).values()];

            //     // temp_pid.forEach(b => pid.push(b.pid));
            //     // temp_sectionid.forEach(b => sectionid.push(b.sectionid));

            //     var failedCount = 0;
            //     var passedCount = 0;

            //     $('.p_count').text(0);
            //     $('.f_count').text(0);
            //     $('.ng_count').text(0);

            //     $('.drop_count').text(0);
            //     $('.inc_count').text(0);
            //     $('.pen_count').text(0);
            //     $('.sub_count').text(0);
            //     $('.app_count').text(0);

            //     $.ajax({
            //         type: 'GET',
            //         url: '/college/teacher/student/new/grades/get',
            //         data: {
            //             syid: $('#filter_sy').val(),
            //             semid: $('#filter_semester').val(),
            //             pid: pid,
            //             sectionid: sectionid,
            //             schedid: schedid,
            //         },
            //         success: function(data) {
            //             $('.grade_td').addClass('input_grades');
            //             all_grades = data;


            //             plot_subject_grades(data);


            //             ///////////////////////////////
            //             if ('[data-term="1"]') {
            //                 if (data.some(x => x.prelim_excel_status === 1)) {
            //                     // Toast.fire({
            //                     //     type: 'success',
            //                     //     title: 'Prelim Excel grades!'
            //                     // });
            //                     var prelim = $('.grade_td[data-term="1"]');
            //                     prelim.removeClass('input_grades');
            //                 } else {
            //                     var prelim = $('.grade_td[data-term="1"]');
            //                     prelim.addClass('input_grades');

            //                 }
            //             }

            //             if ('[data-term="2"]') {
            //                 if (data.some(x => x.midterm_excel_status === 1)) {
            //                     // Toast.fire({
            //                     //     type: 'success',
            //                     //     title: 'Prelim Excel grades!'
            //                     // });
            //                     var midterm = $('.grade_td[data-term="2"]');
            //                     midterm.removeClass('input_grades');
            //                 } else {
            //                     var midterm = $('.grade_td[data-term="2"]');
            //                     midterm.addClass('input_grades');

            //                 }
            //             }

            //             if ('[data-term="3"]') {
            //                 if (data.some(x => x.prefi_excel_status === 1)) {
            //                     // Toast.fire({
            //                     //     type: 'success',
            //                     //     title: 'Prelim Excel grades!'
            //                     // });
            //                     var prefi = $('.grade_td[data-term="3"]');
            //                     prefi.removeClass('input_grades');
            //                 } else {
            //                     var prefi = $('.grade_td[data-term="3"]');
            //                     prefi.addClass('input_grades');

            //                 }
            //             }

            //             if ('[data-term="4"]') {
            //                 if (data.some(x => x.final_excel_status === 1)) {
            //                     // Toast.fire({
            //                     //     type: 'success',
            //                     //     title: 'Prelim Excel grades!'
            //                     // });
            //                     var final = $('.grade_td[data-term="4"]');
            //                     final.removeClass('input_grades');
            //                 } else {
            //                     var final = $('.grade_td[data-term="4"]');
            //                     final.addClass('input_grades');

            //                 }
            //             }




            //         },
            //         error: function() {
            //             Toast.fire({
            //                 type: 'error',
            //                 title: 'Something went wrong!'
            //             });
            //             $('#message_holder').text('Unable to load grades.');
            //         }
            //     });
            // }

            function get_grades(schedid, prompt = true, students) {
                var pid = [];
                var sectionid = [];
                var temp_pid = [...new Map(students.map(item => [item['pid'], item])).values()];
                var temp_sectionid = [...new Map(students.map(item => [item['sectionid'], item])).values()];

                // temp_pid.forEach(b => pid.push(b.pid));
                // temp_sectionid.forEach(b => sectionid.push(b.sectionid));

                var failedCount = 0;
                var passedCount = 0;

                $('.p_count').text(0);
                $('.f_count').text(0);
                $('.ng_count').text(0);

                $('.drop_count').text(0);
                $('.inc_count').text(0);
                $('.pen_count').text(0);
                $('.sub_count').text(0);
                $('.app_count').text(0);

                $.ajax({
                    type: 'GET',
                    url: '/college/teacher/student/new/gradesv3/get',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        pid: pid,
                        sectionid: sectionid,
                        schedid: schedid,
                    },
                    success: function(data) {
                        $('.grade_td').addClass('input_grades');
                        all_grades = data;


                        plot_subject_grades(data);


                        ///////////////////////////////
                        if ('[data-term="1"]') {
                            if (data.some(x => x.prelim_excel_status === 1)) {
                                // Toast.fire({
                                //     type: 'success',
                                //     title: 'Prelim Excel grades!'
                                // });
                                var prelim = $('.grade_td[data-term="1"]');
                                prelim.removeClass('input_grades');
                            } else {
                                var prelim = $('.grade_td[data-term="1"]');
                                prelim.addClass('input_grades');

                            }
                        }

                        if ('[data-term="2"]') {
                            if (data.some(x => x.midterm_excel_status === 1)) {
                                // Toast.fire({
                                //     type: 'success',
                                //     title: 'Prelim Excel grades!'
                                // });
                                var midterm = $('.grade_td[data-term="2"]');
                                midterm.removeClass('input_grades');
                            } else {
                                var midterm = $('.grade_td[data-term="2"]');
                                midterm.addClass('input_grades');

                            }
                        }

                        if ('[data-term="3"]') {
                            if (data.some(x => x.prefi_excel_status === 1)) {
                                // Toast.fire({
                                //     type: 'success',
                                //     title: 'Prelim Excel grades!'
                                // });
                                var prefi = $('.grade_td[data-term="3"]');
                                prefi.removeClass('input_grades');
                            } else {
                                var prefi = $('.grade_td[data-term="3"]');
                                prefi.addClass('input_grades');

                            }
                        }

                        if ('[data-term="4"]') {
                            if (data.some(x => x.final_excel_status === 1)) {
                                // Toast.fire({
                                //     type: 'success',
                                //     title: 'Prelim Excel grades!'
                                // });
                                var final = $('.grade_td[data-term="4"]');
                                final.removeClass('input_grades');
                            } else {
                                var final = $('.grade_td[data-term="4"]');
                                final.addClass('input_grades');

                            }
                        }




                    },
                    error: function() {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        });
                        $('#message_holder').text('Unable to load grades.');
                    }
                });
            }

            // function get_grades(schedid, prompt = true, students) {


            //     var pid = []
            //     var sectionid = []
            //     var temp_pid = [...new Map(students.map(item => [item['pid'], item])).values()]
            //     var temp_sectionid = [...new Map(students.map(item => [item['sectionid'], item])).values()]
            //     $.each(temp_pid, function(a, b) {
            //         pid.push(b.pid)
            //     })
            //     $.each(temp_sectionid, function(a, b) {
            //         sectionid.push(b.sectionid)
            //     })
            //     // }
            //     var failedCount = 0;
            //     var passedCount = 0;

            //     $('.p_count').text(0)
            //     $('.f_count').text(0)
            //     $('.ng_count').text(0)

            //     $('.drop_count').text(0)
            //     $('.inc_count').text(0)
            //     $('.pen_count').text(0)
            //     $('.sub_count').text(0)
            //     $('.app_count').text(0)

            //     $.ajax({
            //         type: 'GET',
            //         url: '/college/teacher/student/new/grades/get',
            //         data: {

            //             syid: $('#filter_sy').val(),
            //             semid: $('#filter_semester').val(),
            //             pid: pid,
            //             sectionid: sectionid,
            //             schedid: schedid,
            //         },
            //         success: function(data) {

            //             $('.grade_td').addClass('input_grades')
            //             all_grades = data

            //             if (data.length == 0) {

            //             } else {

            //                 $('.drop_count[data-stat="1"]').text(data.filter(x => x.prelemstatus == 9)
            //                     .length)
            //                 $('.drop_count[data-stat="2"]').text(data.filter(x => x.midtermstatus == 9)
            //                     .length)
            //                 $('.drop_count[data-stat="3"]').text(data.filter(x => x.prefistatus == 9)
            //                     .length)
            //                 $('.drop_count[data-stat="4"]').text(data.filter(x => x.finalstatus == 9)
            //                     .length)

            //                 $('.inc_count[data-stat="1"]').text(data.filter(x => x.prelemstatus == 8)
            //                     .length)
            //                 $('.inc_count[data-stat="2"]').text(data.filter(x => x.midtermstatus == 8)
            //                     .length)
            //                 $('.inc_count[data-stat="3"]').text(data.filter(x => x.prefistatus == 8)
            //                     .length)
            //                 $('.inc_count[data-stat="4"]').text(data.filter(x => x.finalstatus == 8)
            //                     .length)

            //                 $('.pen_count[data-stat="1"]').text(data.filter(x => x.prelemstatus == 3)
            //                     .length)
            //                 $('.pen_count[data-stat="2"]').text(data.filter(x => x.midtermstatus == 3)
            //                     .length)
            //                 $('.pen_count[data-stat="3"]').text(data.filter(x => x.prefistatus == 3)
            //                     .length)
            //                 $('.pen_count[data-stat="4"]').text(data.filter(x => x.finalstatus == 3)
            //                     .length)

            //                 $('.sub_count[data-stat="1"]').text(data.filter(x => x.prelemstatus == 1)
            //                     .length)
            //                 $('.sub_count[data-stat="2"]').text(data.filter(x => x.midtermstatus == 1)
            //                     .length)
            //                 $('.sub_count[data-stat="3"]').text(data.filter(x => x.prefistatus == 1)
            //                     .length)
            //                 $('.sub_count[data-stat="4"]').text(data.filter(x => x.finalstatus == 1)
            //                     .length)

            //                 $('.app_count[data-stat="1"]').text(data.filter(x => x.prelemstatus == 2 ||
            //                     x.prelemstatus == 7).length)
            //                 $('.app_count[data-stat="2"]').text(data.filter(x => x.midtermstatus == 2 ||
            //                     x.midtermstatus == 7).length)
            //                 $('.app_count[data-stat="3"]').text(data.filter(x => x.prefistatus == 2 || x
            //                     .prefistatus == 7).length)
            //                 $('.app_count[data-stat="4"]').text(data.filter(x => x.finalstatus == 2 || x
            //                     .finalstatus == 7).length)


            //                 $('.p_count[data-stat="1"]').text(data.filter(x => x.prelemgrade != null &&
            //                     x.prelemgrade >= 75).length)
            //                 $('.p_count[data-stat="2"]').text(data.filter(x => x.midtermgrade != null &&
            //                     x.midtermgrade >= 75).length)
            //                 $('.p_count[data-stat="3"]').text(data.filter(x => x.prefigrade != null && x
            //                     .prefigrade >= 75).length)
            //                 $('.p_count[data-stat="4"]').text(data.filter(x => x.finalgrade != null && x
            //                     .finalgrade >= 75).length)

            //                 $('.f_count[data-stat="1"]').text(data.filter(x => x.prelemgrade != null &&
            //                     x.prelemgrade < 75).length)
            //                 $('.f_count[data-stat="2"]').text(data.filter(x => x.midtermgrade != null &&
            //                     x.midtermgrade < 75).length)
            //                 $('.f_count[data-stat="3"]').text(data.filter(x => x.prefigrade != null && x
            //                     .prefigrade < 75).length)
            //                 $('.f_count[data-stat="4"]').text(data.filter(x => x.finalgrade != null && x
            //                     .finalgrade < 75).length)

            //                 if (school == 'spct'.toUpperCase()) {
            //                     $('.ng_count[data-stat="2"]').text(parseInt($(
            //                         '.student_count[data-stat="2"]').text()) - (parseInt($(
            //                         '.p_count[data-stat="2"]').text()) + parseInt($(
            //                         '.f_count[data-stat="2"]').text())))
            //                     $('.ng_count[data-stat="3"]').text(parseInt($(
            //                         '.student_count[data-stat="2"]').text()) - (parseInt($(
            //                         '.p_count[data-stat="3"]').text()) + parseInt($(
            //                         '.f_count[data-stat="3"]').text())))
            //                     $('.ng_count[data-stat="4"]').text(parseInt($(
            //                         '.student_count[data-stat="2"]').text()) - (parseInt($(
            //                         '.p_count[data-stat="4"]').text()) + parseInt($(
            //                         '.f_count[data-stat="4"]').text())))
            //                 } else {
            //                     $('.ng_count[data-stat="1"]').text(parseInt($(
            //                         '.student_count[data-stat="1"]').text()) - (parseInt($(
            //                         '.p_count[data-stat="1"]').text()) + parseInt($(
            //                         '.f_count[data-stat="1"]').text())))
            //                     $('.ng_count[data-stat="2"]').text(parseInt($(
            //                         '.student_count[data-stat="1"]').text()) - (parseInt($(
            //                         '.p_count[data-stat="2"]').text()) + parseInt($(
            //                         '.f_count[data-stat="2"]').text())))
            //                     $('.ng_count[data-stat="3"]').text(parseInt($(
            //                         '.student_count[data-stat="1"]').text()) - (parseInt($(
            //                         '.p_count[data-stat="3"]').text()) + parseInt($(
            //                         '.f_count[data-stat="3"]').text())))
            //                     $('.ng_count[data-stat="4"]').text(parseInt($(
            //                         '.student_count[data-stat="1"]').text()) - (parseInt($(
            //                         '.p_count[data-stat="4"]').text()) + parseInt($(
            //                         '.f_count[data-stat="4"]').text())))
            //                 }

            //                 plot_subject_grades(data)



            //             }

            //         },
            //         error: function() {
            //             Toast.fire({
            //                 type: 'error',
            //                 title: 'Something went wrong!'
            //             })
            //             $('#message_holder').text('Unable to load grades.')
            //         }
            //     })
            // }

            //v2
            // function plot_subject_grades(data) {
            //     let passedCount = 0;
            //     let failedCount = 0;
            //     let notSubmittedCount = 0;
            //     let submittedCount = 0;
            //     let approvedCount = 0;
            //     let pendingCount = 0;
            //     let postedCount = 0;

            //     const statuses = {
            //         1: 'bg-success',
            //         2: 'bg-primary',
            //         3: 'bg-warning',
            //         4: 'bg-info',
            //         7: 'bg-secondary',
            //         8: 'bg-warning',
            //         9: 'bg-danger'
            //     };



            //     $.each(data, function(index, record) {
            //         let hasFailedStatus = false;

            //         ['prelemgrade', 'midtermgrade', 'prefigrade', 'finalgrade'].forEach((term, idx) => {
            //             const termIndex = idx + 1;
            //             const grade = record[term];
            //             const status = record[term.replace('grade', 'status')];



            //             if (grade && grade.trim() !== '') {
            //                 const gradeElement = $('.grade_td[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 gradeElement.text(grade).removeClass('bg-danger bg-warning');

            //                 const finalTermElement = $('th[data-studid="' + record.studid +
            //                     '"][data-term="5"]');
            //                 finalTermElement.text('---').css('text-align',
            //                     'center'); // Set text and alignment
            //                 if (!finalTermElement.hasClass('bg-primary') && !finalTermElement
            //                     .hasClass('bg-info')) {
            //                     finalTermElement.removeClass().addClass(statuses[status]);
            //                 }
            //                 updateStatus(termIndex.toString(), status, grade);
            //             } else if (status === 8) {
            //                 const incElement = $('.grade_td[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 incElement.addClass('bg-warning');
            //                 updateStatus(termIndex.toString(), 8, 'INC');
            //             } else if (status === 9) {
            //                 const droppedElement = $('.grade_td[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 droppedElement.addClass('bg-danger');
            //                 updateStatus(termIndex.toString(), 9, '');
            //             } else if (status !== 9) {
            //                 const droppedElement = $('.grade_td[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 droppedElement.text('').removeClass('bg-danger');
            //                 if (droppedElement.hasClass('bg-warning')) {
            //                     droppedElement.text('INC');
            //                 }
            //                 $('th[data-studid="' + record.studid + '"][data-term="6"]').text('');
            //             } else {
            //                 gradeElement.removeClass('bg-success bg-danger');

            //             }
            //         });

            //         function updateStatus(term, status, grade) {
            //             const element = $('.grade_td[data-studid="' + record.studid + '"][data-term="' +
            //                 term + '"]');

            //             if (status in statuses) {
            //                 if (!element.hasClass('bg-primary') && !element.hasClass('bg-info')) {
            //                     element.addClass(statuses[status]);
            //                 }
            //                 if (status === 9) {
            //                     element.text('DROPPED');
            //                     hasFailedStatus = true;
            //                 }
            //                 if (status === 8) {
            //                     element.text('INC');
            //                     hasFailedStatus = true;
            //                 }
            //             }
            //             // if (element.hasClass('bg-danger') && element.text().trim() === 'DROPPED') {
            //             //     element.prop('disabled', false).off('keydown').on('keydown', function(event) {
            //             //         // Prevent input of numbers and allow Backspace
            //             //         if (event.key.match(/[0-9]/) && event.key !== 'Backspace') {
            //             //             event.preventDefault();
            //             //             $('#save_grades').prop('disabled', true); // Disable save button
            //             //         } else {
            //             //             $('#save_grades').prop('disabled', false); // Enable save button
            //             //         }
            //             //     });
            //             // }
            //             if (element.hasClass('bg-success')) {
            //                 element.removeClass('bg-warning');

            //                 element.prop('disabled', true).on('click', function() {
            //                     Toast.fire({
            //                         type: 'warning',
            //                         title: 'Grade Submitted.'
            //                     });
            //                 });
            //             }
            //             if (element.hasClass('bg-primary')) {
            //                 element.prop('disabled', true).on('click', function() {
            //                     Toast.fire({
            //                         type: 'warning',
            //                         title: 'Grade Approved.'
            //                     });
            //                 });
            //             }
            //             if (element.hasClass('bg-info')) {
            //                 element.prop('disabled', true).on('click', function() {
            //                     Toast.fire({
            //                         type: 'warning',
            //                         title: 'Grade Posted.'
            //                     });
            //                 });
            //             }


            //         }

            //         $('.select[data-studid="' + record.studid + '"]').attr('data-id', record.id);

            //         const finalStatusText = record.fgremarks || '';
            //         const finalStatusElement = $('th[data-studid="' + record.studid + '"][data-term="6"]');

            //         if (hasFailedStatus) {
            //             $('th[data-studid="' + record.studid + '"][data-term="5"]').text('---');
            //             $('th[data-studid="' + record.studid + '"][data-term="6"]').text('---');
            //             if (record.finalstatus === 8 || record.prelemstatus === 8 || record
            //                 .midtermstatus === 8 || record.prefistatus === 8) {
            //                 finalStatusElement.text('INC').addClass('bg-warning');
            //                 finalStatusElement.removeClass('bg-success bg-danger');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').addClass(
            //                     'bg-warning').removeClass('bg-danger bg-success');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').text('---');
            //                 $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled',
            //                     false);

            //             } else if (record.finalstatus === 9 || record.prelemstatus === 9 || record
            //                 .midtermstatus === 9 || record.prefistatus === 9) {
            //                 finalStatusElement.text('DROPPED').addClass('bg-danger');
            //                 finalStatusElement.removeClass('bg-success bg-warning');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').addClass(
            //                     'bg-danger').removeClass('bg-warning bg-success');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').text('DROPPED');
            //                 $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled',
            //                     true);

            //                 // $('.input_grades[data-studid="' + record.studid +
            //                 //     '"][data-term="' + termIndex + '"]').text('DROPPED').addClass(
            //                 //     'bg-danger');
            //             } else {
            //                 finalStatusElement.text('FAILED').addClass('bg-danger');
            //                 finalStatusElement.removeClass('bg-success bg-warning');
            //             }

            //             failedCount++;
            //         } else {
            //             if (record.fg) {
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').text(record.fg);
            //             }
            //             finalStatusElement.text(finalStatusText);

            //             if (finalStatusText === 'PASSED' || finalStatusText === 'Passed') {
            //                 finalStatusElement.removeClass('bg-danger bg-warning').addClass('bg-success');
            //                 passedCount++;
            //             } else if (finalStatusText === 'INC' || finalStatusText === 'Inc') {
            //                 finalStatusElement.removeClass('bg-success bg-danger').addClass('bg-warning');
            //             } else if (finalStatusText === 'FAILED' || finalStatusText === 'Failed') {
            //                 finalStatusElement.removeClass('bg-success bg-warning').addClass('bg-danger');
            //                 failedCount++;
            //             } else {
            //                 finalStatusElement.removeClass('bg-success bg-danger bg-warning');
            //                 failedCount++;
            //             }

            //             $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled', false);
            //         }
            //     });
            // }

            //working v3 code
            // function plot_subject_grades(data) {
            //     let passedCount = 0;
            //     let failedCount = 0;
            //     let notSubmittedCount = 0;
            //     let submittedCount = 0;
            //     let approvedCount = 0;
            //     let pendingCount = 0;
            //     let postedCount = 0;

            //     const statuses = {
            //         1: 'bg-success',
            //         2: 'bg-primary',
            //         3: 'bg-warning',
            //         4: 'bg-info',
            //         7: 'bg-secondary',
            //         8: 'bg-warning',
            //         9: 'bg-danger'
            //     };



            //     $.each(data, function(index, record) {
            //         let hasFailedStatus = false;

            //         ['prelim_transmuted', 'midterm_transmuted', 'prefinal_transmuted', 'final_transmuted']
            //         .forEach((term, idx) => {
            //             const termIndex = idx + 1;
            //             const grade = record[term];
            //             const status = record[term.replace('grade', 'status')];



            //             if (grade && grade.trim() !== '') {
            //                 const gradeElement = $('.grade_td[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 gradeElement.text(grade).removeClass('bg-danger bg-warning');

            //                 const finalTermElement = $('th[data-studid="' + record.studid +
            //                     '"][data-term="5"]');
            //                 finalTermElement.text('---').css('text-align',
            //                     'center'); // Set text and alignment
            //                 if (!finalTermElement.hasClass('bg-primary') && !finalTermElement
            //                     .hasClass('bg-info')) {
            //                     finalTermElement.removeClass().addClass(statuses[status]);
            //                 }
            //                 updateStatus(termIndex.toString(), status, grade);
            //             } else if (status === 8) {
            //                 const incElement = $('.grade_td[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 incElement.addClass('bg-warning');
            //                 updateStatus(termIndex.toString(), 8, 'INC');
            //             } else if (status === 9) {
            //                 const droppedElement = $('.grade_td[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 droppedElement.addClass('bg-danger');
            //                 updateStatus(termIndex.toString(), 9, '');
            //             } else if (status !== 9) {
            //                 const droppedElement = $('.grade_td[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 droppedElement.text('').removeClass('bg-danger');
            //                 if (droppedElement.hasClass('bg-warning')) {
            //                     droppedElement.text('INC');
            //                 }
            //                 $('th[data-studid="' + record.studid + '"][data-term="6"]').text('');
            //             } else {
            //                 gradeElement.removeClass('bg-success bg-danger');

            //             }
            //         });

            //         function updateStatus(term, status, grade) {
            //             const element = $('.grade_td[data-studid="' + record.studid + '"][data-term="' +
            //                 term + '"]');

            //             if (status in statuses) {
            //                 if (!element.hasClass('bg-primary') && !element.hasClass('bg-info')) {
            //                     element.addClass(statuses[status]);
            //                 }
            //                 if (status === 9) {
            //                     element.text('DROPPED');
            //                     hasFailedStatus = true;
            //                 }
            //                 if (status === 8) {
            //                     element.text('INC');
            //                     hasFailedStatus = true;
            //                 }
            //             }

            //             if (element.hasClass('bg-success')) {
            //                 element.removeClass('bg-warning');

            //                 element.prop('disabled', true).on('click', function() {
            //                     Toast.fire({
            //                         type: 'warning',
            //                         title: 'Grade Submitted.'
            //                     });
            //                 });
            //             }
            //             if (element.hasClass('bg-primary')) {
            //                 element.prop('disabled', true).on('click', function() {
            //                     Toast.fire({
            //                         type: 'warning',
            //                         title: 'Grade Approved.'
            //                     });
            //                 });
            //             }
            //             if (element.hasClass('bg-info')) {
            //                 element.prop('disabled', true).on('click', function() {
            //                     Toast.fire({
            //                         type: 'warning',
            //                         title: 'Grade Posted.'
            //                     });
            //                 });
            //             }


            //         }

            //         $('.select[data-studid="' + record.studid + '"]').attr('data-id', record.id);

            //         const finalStatusText = record.fgremarks || '';
            //         const finalStatusElement = $('th[data-studid="' + record.studid + '"][data-term="6"]');

            //         if (hasFailedStatus) {
            //             $('th[data-studid="' + record.studid + '"][data-term="5"]').text('---');
            //             $('th[data-studid="' + record.studid + '"][data-term="6"]').text('---');
            //             if (record.final_status === 8 || record.prelim_status === 8 || record
            //                 .midterm_status === 8 || record.prefinal_status === 8) {
            //                 finalStatusElement.text('INC').addClass('bg-warning');
            //                 finalStatusElement.removeClass('bg-success bg-danger');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').addClass(
            //                     'bg-warning').removeClass('bg-danger bg-success');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').text('---');
            //                 $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled',
            //                     false);

            //             } else if (record.final_status === 9 || record.prelim_status === 9 || record
            //                 .midterm_status === 9 || record.prefinal_status === 9) {
            //                 finalStatusElement.text('DROPPED').addClass('bg-danger');
            //                 finalStatusElement.removeClass('bg-success bg-warning');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').addClass(
            //                     'bg-danger').removeClass('bg-warning bg-success');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').text('DROPPED');
            //                 $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled',
            //                     true);

            //                 // $('.input_grades[data-studid="' + record.studid +
            //                 //     '"][data-term="' + termIndex + '"]').text('DROPPED').addClass(
            //                 //     'bg-danger');
            //             } else {
            //                 finalStatusElement.text('FAILED').addClass('bg-danger');
            //                 finalStatusElement.removeClass('bg-success bg-warning');
            //             }

            //             failedCount++;
            //         } else {
            //             if (record.fg) {
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').text(record.fg);
            //             }
            //             finalStatusElement.text(finalStatusText);

            //             if (finalStatusText === 'PASSED' || finalStatusText === 'Passed') {
            //                 finalStatusElement.removeClass('bg-danger bg-warning').addClass('bg-success');
            //                 passedCount++;
            //             } else if (finalStatusText === 'INC' || finalStatusText === 'Inc') {
            //                 finalStatusElement.removeClass('bg-success bg-danger').addClass('bg-warning');
            //             } else if (finalStatusText === 'FAILED' || finalStatusText === 'Failed') {
            //                 finalStatusElement.removeClass('bg-success bg-warning').addClass('bg-danger');
            //                 failedCount++;
            //             } else {
            //                 finalStatusElement.removeClass('bg-success bg-danger bg-warning');
            //                 failedCount++;
            //             }

            //             $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled', false);
            //         }
            //     });
            // }

            // v2 code
            // function plot_subject_grades(data) {
            //     let passedCount = 0;
            //     let failedCount = 0;
            //     let notSubmittedCount = 0;
            //     let submittedCount = 0;
            //     let approvedCount = 0;
            //     let pendingCount = 0;
            //     let postedCount = 0;

            //     const statuses = {
            //         1: 'bg-success',
            //         2: 'bg-primary',
            //         3: 'bg-warning',
            //         4: 'bg-info',
            //         7: 'bg-secondary',
            //         8: 'bg-warning',
            //         9: 'bg-danger'
            //     };

            //     $.each(data, function(index, record) {
            //         let hasFailedStatus = false;

            //         const statusKeys = ['prelim_status', 'midterm_status', 'prefinal_status',
            //             'final_status'
            //         ];
            //         const gradeKeys = ['prelim_transmuted', 'midterm_transmuted', 'prefinal_transmuted',
            //             'final_transmuted'
            //         ];

            //         statusKeys.forEach((statusKey, idx) => {
            //             const termIndex = idx + 1;
            //             const grade = record[gradeKeys[idx]];
            //             const status = record[statusKey];

            //             if (grade && grade.trim() !== '') {
            //                 const gradeElement = $('.grade_td[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 gradeElement.text(grade).removeClass('bg-danger bg-warning');

            //                 const finalTermElement = $('th[data-studid="' + record.studid +
            //                     '"][data-term="5"]');
            //                 finalTermElement.text('---').css('text-align', 'center');
            //                 if (!finalTermElement.hasClass('bg-primary') && !finalTermElement
            //                     .hasClass('bg-info')) {
            //                     finalTermElement.removeClass().addClass(statuses[status]);
            //                 }
            //                 updateStatus(termIndex.toString(), status, grade);
            //             } else if (status === 8) {
            //                 const incElement = $('.grade_td[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 incElement.addClass('bg-warning');
            //                 updateStatus(termIndex.toString(), 8, 'INC');
            //             } else if (status === 9) {
            //                 const droppedElement = $('.grade_td[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 droppedElement.addClass('bg-danger');
            //                 updateStatus(termIndex.toString(), 9, '');
            //             } else if (status !== 9) {
            //                 const droppedElement = $('.grade_td[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 droppedElement.text('').removeClass('bg-danger');
            //                 if (droppedElement.hasClass('bg-warning')) {
            //                     droppedElement.text('INC');
            //                 }
            //                 $('th[data-studid="' + record.studid + '"][data-term="6"]').text('');
            //             } else {
            //                 gradeElement.removeClass('bg-success bg-danger');
            //             }
            //         });

            //         function updateStatus(term, status, grade) {
            //             const element = $('.grade_td[data-studid="' + record.studid + '"][data-term="' +
            //                 term + '"]');

            //             if (status in statuses) {
            //                 if (!element.hasClass('bg-primary') && !element.hasClass('bg-info')) {
            //                     element.addClass(statuses[status]);
            //                 }
            //                 if (status === 9) {
            //                     element.text('DROPPED');
            //                     hasFailedStatus = true;
            //                 }
            //                 if (status === 8) {
            //                     element.text('INC');
            //                     hasFailedStatus = true;
            //                 }
            //             }

            //             if (element.hasClass('bg-success')) {
            //                 element.removeClass('bg-warning');

            //                 element.prop('disabled', true).on('click', function() {
            //                     Toast.fire({
            //                         type: 'warning',
            //                         title: 'Grade Submitted.'
            //                     });
            //                 });
            //             }
            //             if (element.hasClass('bg-primary')) {
            //                 element.prop('disabled', true).on('click', function() {
            //                     Toast.fire({
            //                         type: 'warning',
            //                         title: 'Grade Approved.'
            //                     });
            //                 });
            //             }
            //             if (element.hasClass('bg-info')) {
            //                 element.prop('disabled', true).on('click', function() {
            //                     Toast.fire({
            //                         type: 'warning',
            //                         title: 'Grade Posted.'
            //                     });
            //                 });
            //             }
            //         }


            //         $('.select[data-studid="' + record.studid + '"]').attr('data-id', record.id);

            //         const finalStatusText = record.fgremarks || '';
            //         const finalStatusElement = $('th[data-studid="' + record.studid + '"][data-term="6"]');

            //         if (hasFailedStatus) {
            //             $('th[data-studid="' + record.studid + '"][data-term="5"]').text('---');
            //             $('th[data-studid="' + record.studid + '"][data-term="6"]').text('---');
            //             if (record.final_status === 8 || record.prelim_status === 8 || record
            //                 .midterm_status === 8 || record.prefinal_status === 8) {
            //                 finalStatusElement.text('INC').addClass('bg-warning');
            //                 finalStatusElement.removeClass('bg-success bg-danger');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').addClass(
            //                     'bg-warning').removeClass('bg-danger bg-success');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').text('---');
            //                 $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled',
            //                     false);

            //             } else if (record.final_status === 9 || record.prelim_status === 9 || record
            //                 .midterm_status === 9 || record.prefinal_status === 9) {
            //                 finalStatusElement.text('DROPPED').addClass('bg-danger');
            //                 finalStatusElement.removeClass('bg-success bg-warning');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').addClass(
            //                     'bg-danger').removeClass('bg-warning bg-success');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').text('DROPPED');
            //                 $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled',
            //                     true);
            //             } else {
            //                 finalStatusElement.text('FAILED').addClass('bg-danger');
            //                 finalStatusElement.removeClass('bg-success bg-warning');
            //             }

            //             failedCount++;
            //         } else {
            //             if (record.fg) {
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').text(record.fg);
            //             }
            //             finalStatusElement.text(finalStatusText);

            //             if (finalStatusText === 'PASSED' || finalStatusText === 'Passed') {
            //                 finalStatusElement.removeClass('bg-danger bg-warning').addClass('bg-success');
            //                 passedCount++;
            //             } else if (finalStatusText === 'INC' || finalStatusText === 'Inc') {
            //                 finalStatusElement.removeClass('bg-success bg-danger').addClass('bg-warning');
            //             } else if (finalStatusText === 'FAILED' || finalStatusText === 'Failed') {
            //                 finalStatusElement.removeClass('bg-success bg-warning').addClass('bg-danger');
            //                 failedCount++;
            //             } else {
            //                 finalStatusElement.removeClass('bg-success bg-danger bg-warning');
            //                 failedCount++;
            //             }

            //             $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled', false);
            //         }
            //     });
            // }


            function plot_subject_grades(data) {
                let passedCount = 0;
                let failedCount = 0;
                let notSubmittedCount = 0;
                let submittedCount = 0;
                let approvedCount = 0;
                let pendingCount = 0;
                let postedCount = 0;

                const statuses = {
                    1: 'bg-success',
                    2: 'bg-primary',
                    3: 'bg-warning',
                    4: 'bg-info',
                    7: 'bg-secondary',
                    8: 'bg-warning',
                    9: 'bg-danger'
                };

                $.each(data, function(index, record) {
                    let hasFailedStatus = false;

                    const statusKeys = ['prelim_status', 'midterm_status', 'prefinal_status',
                        'final_status'
                    ];
                    const gradeKeys = ['prelim_transmuted', 'midterm_transmuted', 'prefinal_transmuted',
                        'final_transmuted'
                    ];

                    statusKeys.forEach((statusKey, idx) => {
                        const termIndex = idx + 1;
                        const grade = record[gradeKeys[idx]];
                        const status = record[statusKey];

                        if (grade && grade.trim() !== '') {
                            const gradeElement = $('.grade_td[data-studid="' + record.studid +
                                '"][data-term="' + termIndex + '"]');
                            gradeElement.text(grade).removeClass('bg-danger bg-warning');

                            const finalTermElement = $('th[data-studid="' + record.studid +
                                '"][data-term="5"]');
                            finalTermElement.text('---').css('text-align', 'center');
                            if (!finalTermElement.hasClass('bg-primary') && !finalTermElement
                                .hasClass('bg-info')) {
                                finalTermElement.removeClass().addClass(statuses[status]);
                            }
                            updateStatus(termIndex.toString(), status, grade);
                        } else if (status === 8) {
                            const incElement = $('.grade_td[data-studid="' + record.studid +
                                '"][data-term="' + termIndex + '"]');
                            incElement.addClass('bg-warning');
                            updateStatus(termIndex.toString(), 8, 'INC');
                        } else if (status === 9) {
                            const droppedElement = $('.grade_td[data-studid="' + record.studid +
                                '"][data-term="' + termIndex + '"]');
                            droppedElement.addClass('bg-danger');
                            updateStatus(termIndex.toString(), 9, '');
                        } else if (status !== 9) {
                            const droppedElement = $('.grade_td[data-studid="' + record.studid +
                                '"][data-term="' + termIndex + '"]');
                            droppedElement.text('').removeClass('bg-danger');
                            if (droppedElement.hasClass('bg-warning')) {
                                droppedElement.text('INC');
                            }
                            $('th[data-studid="' + record.studid + '"][data-term="6"]').text('');
                        } else {
                            gradeElement.removeClass('bg-success bg-danger');
                        }
                    });

                    function updateStatus(term, status, grade) {
                        const element = $('.grade_td[data-studid="' + record.studid + '"][data-term="' +
                            term + '"]');

                        if (status in statuses) {
                            if (!element.hasClass('bg-primary') && !element.hasClass('bg-info')) {
                                element.addClass(statuses[status]);
                            }
                            if (status === 9) {
                                element.text('DROPPED');
                                hasFailedStatus = true;
                            }
                            if (status === 8) {
                                element.text('INC');
                                hasFailedStatus = true;
                            }
                        }

                        if (element.hasClass('bg-success')) {
                            element.removeClass('bg-warning');

                            element.prop('disabled', true).on('click', function() {
                                Toast.fire({
                                    type: 'warning',
                                    title: 'Grade Submitted.'
                                });
                            });
                        }
                        if (element.hasClass('bg-primary')) {
                            element.prop('disabled', true).on('click', function() {
                                Toast.fire({
                                    type: 'warning',
                                    title: 'Grade Approved.'
                                });
                            });
                        }
                        if (element.hasClass('bg-info')) {
                            element.prop('disabled', true).on('click', function() {
                                Toast.fire({
                                    type: 'warning',
                                    title: 'Grade Posted.'
                                });
                            });
                        }
                    }


                    $('.select[data-studid="' + record.studid + '"]').attr('data-id', record.id);

                    const finalStatusText = record.final_remarks || '';
                    const finalStatusElement = $('th[data-studid="' + record.studid + '"][data-term="6"]');

                    if (hasFailedStatus) {
                        $('th[data-studid="' + record.studid + '"][data-term="5"]').text('---');
                        $('th[data-studid="' + record.studid + '"][data-term="6"]').text('---');
                        if (record.final_status === 8 || record.prelim_status === 8 || record
                            .midterm_status === 8 || record.prefinal_status === 8) {
                            finalStatusElement.text('INC').addClass('bg-warning');
                            finalStatusElement.removeClass('bg-success bg-danger');
                            $('th[data-studid="' + record.studid + '"][data-term="5"]').addClass(
                                'bg-warning').removeClass('bg-danger bg-success');
                            $('th[data-studid="' + record.studid + '"][data-term="5"]').text('---');
                            $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled',
                                false);

                        } else if (record.final_status === 9 || record.prelim_status === 9 || record
                            .midterm_status === 9 || record.prefinal_status === 9) {
                            finalStatusElement.text('DROPPED').addClass('bg-danger');
                            finalStatusElement.removeClass('bg-success bg-warning');
                            $('th[data-studid="' + record.studid + '"][data-term="5"]').addClass(
                                'bg-danger').removeClass('bg-warning bg-success');
                            $('th[data-studid="' + record.studid + '"][data-term="5"]').text('DROPPED');
                            $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled',
                                true);
                        } else {
                            finalStatusElement.text('FAILED').addClass('bg-danger');
                            finalStatusElement.removeClass('bg-success bg-warning');
                        }

                        failedCount++;
                    } else {
                        if (record.final_grade_transmuted) {
                            $('th[data-studid="' + record.studid + '"][data-term="5"]').text(record
                                .final_grade_transmuted);
                        }
                        finalStatusElement.text(finalStatusText);

                        if (finalStatusText === 'PASSED' || finalStatusText === 'Passed') {
                            finalStatusElement.removeClass('bg-danger bg-warning').addClass('bg-success');
                            passedCount++;
                        } else if (finalStatusText === 'INC' || finalStatusText === 'Inc') {
                            finalStatusElement.removeClass('bg-success bg-danger').addClass('bg-warning');
                        } else if (finalStatusText === 'FAILED' || finalStatusText === 'Failed') {
                            finalStatusElement.removeClass('bg-success bg-warning').addClass('bg-danger');
                            failedCount++;
                        } else {
                            finalStatusElement.removeClass('bg-success bg-danger bg-warning');
                            failedCount++;
                        }

                        $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled', false);
                    }
                });
            }



            // function plot_subject_grades(data) {
            //     let passedCount = 0;
            //     let failedCount = 0;
            //     let notSubmittedCount = 0;
            //     let submittedCount = 0;
            //     let approvedCount = 0;
            //     let pendingCount = 0;
            //     let postedCount = 0;

            //     const statuses = {
            //         1: 'bg-success',
            //         2: 'bg-primary',
            //         3: 'bg-warning',
            //         4: 'bg-info',
            //         7: 'bg-secondary',
            //         8: 'bg-warning',
            //         9: 'bg-danger'
            //     };



            //     $.each(data, function(index, record) {
            //         let hasFailedStatus = false;

            //         ['prelemgrade', 'midtermgrade', 'prefigrade', 'finalgrade'].forEach((term, idx) => {
            //             const termIndex = idx + 1;
            //             const grade = record[term];
            //             const status = record[term.replace('grade', 'status')];



            //             if (grade && grade.trim() !== '') {
            //                 const gradeElement = $('.grade_td[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 gradeElement.text(grade).removeClass('bg-danger bg-warning');

            //                 const finalTermElement = $('th[data-studid="' + record.studid +
            //                     '"][data-term="5"]');
            //                 finalTermElement.text('---').css('text-align',
            //                     'center'); // Set text and alignment
            //                 if (!finalTermElement.hasClass('bg-primary') && !finalTermElement
            //                     .hasClass('bg-info')) {
            //                     finalTermElement.removeClass().addClass(statuses[status]);
            //                 }
            //                 updateStatus(termIndex.toString(), status, grade);
            //             } else if (status === 8) {
            //                 const incElement = $('.grade_td[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 incElement.addClass('bg-warning');
            //                 updateStatus(termIndex.toString(), 8, 'INC');
            //             } else if (status === 9) {
            //                 const droppedElement = $('.grade_td[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 droppedElement.addClass('bg-danger');
            //                 updateStatus(termIndex.toString(), 9, '');
            //             } else if (status !== 9) {
            //                 const droppedElement = $('.grade_td[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 droppedElement.text('').removeClass('bg-danger');
            //                 if (droppedElement.hasClass('bg-warning')) {
            //                     droppedElement.text('INC');
            //                 }
            //                 $('th[data-studid="' + record.studid + '"][data-term="6"]').text('');
            //             } else {
            //                 gradeElement.removeClass('bg-success bg-danger');

            //             }
            //         });

            //         function updateStatus(term, status, grade) {
            //             const element = $('.grade_td[data-studid="' + record.studid + '"][data-term="' +
            //                 term + '"]');

            //             if (status in statuses) {
            //                 if (!element.hasClass('bg-primary') && !element.hasClass('bg-info')) {
            //                     element.addClass(statuses[status]);
            //                 }
            //                 if (status === 9) {
            //                     element.text('DROPPED');
            //                     hasFailedStatus = true;
            //                 }
            //                 if (status === 8) {
            //                     element.text('INC');
            //                     hasFailedStatus = true;
            //                 }
            //             }
            //             // if (element.hasClass('bg-danger') && element.text().trim() === 'DROPPED') {
            //             //     element.prop('disabled', false).off('keydown').on('keydown', function(event) {
            //             //         // Prevent input of numbers and allow Backspace
            //             //         if (event.key.match(/[0-9]/) && event.key !== 'Backspace') {
            //             //             event.preventDefault();
            //             //             $('#save_grades').prop('disabled', true); // Disable save button
            //             //         } else {
            //             //             $('#save_grades').prop('disabled', false); // Enable save button
            //             //         }
            //             //     });
            //             // }
            //             if (element.hasClass('bg-success')) {
            //                 element.removeClass('bg-warning');

            //                 element.prop('disabled', true).on('click', function() {
            //                     Toast.fire({
            //                         type: 'warning',
            //                         title: 'Grade Submitted.'
            //                     });
            //                 });
            //             }
            //             if (element.hasClass('bg-primary')) {
            //                 element.prop('disabled', true).on('click', function() {
            //                     Toast.fire({
            //                         type: 'warning',
            //                         title: 'Grade Approved.'
            //                     });
            //                 });
            //             }
            //             if (element.hasClass('bg-info')) {
            //                 element.prop('disabled', true).on('click', function() {
            //                     Toast.fire({
            //                         type: 'warning',
            //                         title: 'Grade Posted.'
            //                     });
            //                 });
            //             }


            //         }

            //         $('.select[data-studid="' + record.studid + '"]').attr('data-id', record.id);

            //         const finalStatusText = record.fgremarks || '';
            //         const finalStatusElement = $('th[data-studid="' + record.studid + '"][data-term="6"]');

            //         if (hasFailedStatus) {
            //             $('th[data-studid="' + record.studid + '"][data-term="5"]').text('---');
            //             $('th[data-studid="' + record.studid + '"][data-term="6"]').text('---');
            //             if (record.finalstatus === 8 || record.prelemstatus === 8 || record
            //                 .midtermstatus === 8 || record.prefistatus === 8) {
            //                 finalStatusElement.text('INC').addClass('bg-warning');
            //                 finalStatusElement.removeClass('bg-success bg-danger');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').addClass(
            //                     'bg-warning').removeClass('bg-danger bg-success');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').text('---');
            //                 $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled',
            //                     false);

            //             } else if (record.finalstatus === 9 || record.prelemstatus === 9 || record
            //                 .midtermstatus === 9 || record.prefistatus === 9) {
            //                 finalStatusElement.text('DROPPED').addClass('bg-danger');
            //                 finalStatusElement.removeClass('bg-success bg-warning');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').addClass(
            //                     'bg-danger').removeClass('bg-warning bg-success');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').text('DROPPED');
            //                 $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled',
            //                     true);

            //                 // $('.input_grades[data-studid="' + record.studid +
            //                 //     '"][data-term="' + termIndex + '"]').text('DROPPED').addClass(
            //                 //     'bg-danger');
            //             } else {
            //                 finalStatusElement.text('FAILED').addClass('bg-danger');
            //                 finalStatusElement.removeClass('bg-success bg-warning');
            //             }

            //             failedCount++;
            //         } else {
            //             if (record.fg) {
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').text(record.fg);
            //             }
            //             finalStatusElement.text(finalStatusText);

            //             if (finalStatusText === 'PASSED' || finalStatusText === 'Passed') {
            //                 finalStatusElement.removeClass('bg-danger bg-warning').addClass('bg-success');
            //                 passedCount++;
            //             } else if (finalStatusText === 'INC' || finalStatusText === 'Inc') {
            //                 finalStatusElement.removeClass('bg-success bg-danger').addClass('bg-warning');
            //             } else if (finalStatusText === 'FAILED' || finalStatusText === 'Failed') {
            //                 finalStatusElement.removeClass('bg-success bg-warning').addClass('bg-danger');
            //                 failedCount++;
            //             } else {
            //                 finalStatusElement.removeClass('bg-success bg-danger bg-warning');
            //                 failedCount++;
            //             }

            //             $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled', false);
            //         }
            //     });
            // }

            //second working
            // function plot_subject_grades(data) {
            //     let passedCount = 0;
            //     let failedCount = 0;
            //     let notSubmittedCount = 0;
            //     let submittedCount = 0;
            //     let approvedCount = 0;
            //     let pendingCount = 0;
            //     let postedCount = 0;

            //     const statuses = {
            //         1: 'bg-success',
            //         2: 'bg-primary',
            //         3: 'bg-warning',
            //         4: 'bg-info',
            //         7: 'bg-secondary',
            //         8: 'bg-warning',
            //         9: 'bg-danger'
            //     };



            //     $.each(data, function(index, record) {
            //         let hasFailedStatus = false;

            //         ['prelemgrade', 'midtermgrade', 'prefigrade', 'finalgrade'].forEach((term, idx) => {
            //             const termIndex = idx + 1;
            //             const grade = record[term];
            //             const status = record[term.replace('grade', 'status')];

            //             if (grade && grade.trim() !== '') {
            //                 const gradeElement = $('.input_grades[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 gradeElement.text(grade).removeClass('bg-danger');

            //                 const finalTermElement = $('th[data-studid="' + record.studid +
            //                     '"][data-term="5"]');
            //                 finalTermElement.text('---').css('text-align',
            //                     'center'); // Set text and alignment
            //                 if (!finalTermElement.hasClass('bg-primary') && !finalTermElement
            //                     .hasClass('bg-info')) {
            //                     finalTermElement.removeClass().addClass(statuses[status]);
            //                 }
            //                 updateStatus(termIndex.toString(), status, grade);
            //             } else if (status === 8) {
            //                 const incElement = $('.input_grades[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 incElement.addClass('bg-warning');
            //                 updateStatus(termIndex.toString(), 8, 'INC');
            //             } else if (status === 9) {
            //                 const droppedElement = $('.input_grades[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 droppedElement.addClass('bg-danger');
            //                 updateStatus(termIndex.toString(), 9, '');
            //             } else if (status !== 9) {
            //                 const droppedElement = $('.input_grades[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 droppedElement.text('').removeClass('bg-danger');
            //                 $('th[data-studid="' + record.studid + '"][data-term="6"]').text('');
            //             } else {
            //                 gradeElement.removeClass('bg-success bg-danger');
            //             }
            //         });

            //         function updateStatus(term, status, grade) {
            //             const element = $('.input_grades[data-studid="' + record.studid + '"][data-term="' +
            //                 term + '"]');

            //             if (status in statuses) {
            //                 if (!element.hasClass('bg-primary') && !element.hasClass('bg-info')) {
            //                     element.addClass(statuses[status]);
            //                 }
            //                 if (status === 9) {
            //                     element.text('DROPPED');
            //                     hasFailedStatus = true;
            //                 }
            //                 if (status === 8) {
            //                     element.text('INC');
            //                     hasFailedStatus = true;
            //                 }
            //             }
            //             // if (element.hasClass('bg-danger') && element.text().trim() === 'DROPPED') {
            //             //     element.prop('disabled', false).off('keydown').on('keydown', function(event) {
            //             //         // Prevent input of numbers and allow Backspace
            //             //         if (event.key.match(/[0-9]/) && event.key !== 'Backspace') {
            //             //             event.preventDefault();
            //             //             $('#save_grades').prop('disabled', true); // Disable save button
            //             //         } else {
            //             //             $('#save_grades').prop('disabled', false); // Enable save button
            //             //         }
            //             //     });
            //             // }
            //             if (element.hasClass('bg-success')) {
            //                 element.removeClass('bg-warning');

            //                 element.prop('disabled', true).on('click', function() {
            //                     Toast.fire({
            //                         type: 'warning',
            //                         title: 'Grade Submitted.'
            //                     });
            //                 });
            //             }
            //             if (element.hasClass('bg-primary')) {
            //                 element.prop('disabled', true).on('click', function() {
            //                     Toast.fire({
            //                         type: 'warning',
            //                         title: 'Grade Approved.'
            //                     });
            //                 });
            //             }
            //             if (element.hasClass('bg-info')) {
            //                 element.prop('disabled', true).on('click', function() {
            //                     Toast.fire({
            //                         type: 'warning',
            //                         title: 'Grade Posted.'
            //                     });
            //                 });
            //             }


            //         }

            //         $('.select[data-studid="' + record.studid + '"]').attr('data-id', record.id);

            //         const finalStatusText = record.fgremarks || '';
            //         const finalStatusElement = $('th[data-studid="' + record.studid + '"][data-term="6"]');

            //         if (hasFailedStatus) {
            //             $('th[data-studid="' + record.studid + '"][data-term="5"]').text('---');
            //             $('th[data-studid="' + record.studid + '"][data-term="6"]').text('---');
            //             if (record.finalstatus === 8 || record.prelemstatus === 8 || record
            //                 .midtermstatus === 8 || record.prefistatus === 8) {
            //                 finalStatusElement.text('INC').addClass('bg-warning');
            //                 finalStatusElement.removeClass('bg-success bg-danger');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').addClass(
            //                     'bg-warning').removeClass('bg-danger bg-success');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').text('INC');
            //             } else if (record.finalstatus === 9 || record.prelemstatus === 9 || record
            //                 .midtermstatus === 9 || record.prefistatus === 9) {
            //                 finalStatusElement.text('DROPPED').addClass('bg-danger');
            //                 finalStatusElement.removeClass('bg-success bg-warning');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').addClass(
            //                     'bg-danger').removeClass('bg-warning bg-success');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').text('DROPPED');

            //                 // $('.input_grades[data-studid="' + record.studid +
            //                 //     '"][data-term="' + termIndex + '"]').text('DROPPED').addClass(
            //                 //     'bg-danger');
            //             } else {
            //                 finalStatusElement.text('FAILED').addClass('bg-danger');
            //                 finalStatusElement.removeClass('bg-success bg-warning');
            //             }

            //             failedCount++;
            //         } else {
            //             if (record.fg) {
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').text(record.fg);
            //             }
            //             finalStatusElement.text(finalStatusText);

            //             if (finalStatusText === 'PASSED' || finalStatusText === 'Passed') {
            //                 finalStatusElement.removeClass('bg-danger bg-warning').addClass('bg-success');
            //                 passedCount++;
            //             } else if (finalStatusText === 'INC' || finalStatusText === 'Inc') {
            //                 finalStatusElement.removeClass('bg-success bg-danger').addClass('bg-warning');
            //             } else if (finalStatusText === 'FAILED' || finalStatusText === 'Failed') {
            //                 finalStatusElement.removeClass('bg-success bg-warning').addClass('bg-danger');
            //                 failedCount++;
            //             } else {
            //                 finalStatusElement.removeClass('bg-success bg-danger bg-warning');
            //                 failedCount++;
            //             }

            //             $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled', false);
            //         }
            //     });
            // }


            // function plot_subject_grades(data) {
            //     let passedCount = 0;
            //     let failedCount = 0;
            //     let notSubmittedCount = 0;
            //     let submittedCount = 0;
            //     let approvedCount = 0;
            //     let pendingCount = 0;
            //     let postedCount = 0;

            //     const statuses = {
            //         1: 'bg-success',
            //         2: 'bg-primary',
            //         3: 'bg-warning',
            //         4: 'bg-info',
            //         7: 'bg-secondary',
            //         8: 'bg-warning',
            //         9: 'bg-danger'
            //     };

            //     $.each(data, function(index, record) {
            //         let hasFailedStatus = false;

            //         ['prelemgrade', 'midtermgrade', 'prefigrade', 'finalgrade'].forEach((term, idx) => {
            //             const termIndex = idx + 1;
            //             const grade = record[term];
            //             const status = record[term.replace('grade', 'status')];

            //             if (grade && grade.trim() !== '') {
            //                 const gradeElement = $('.input_grades[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 gradeElement.text(grade);
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').addClass(
            //                     'bg-success');
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').text('---');
            //                 updateStatus(termIndex.toString(), status, grade);
            //             } else if (status === 8) {
            //                 const incElement = $('.input_grades[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 incElement.addClass('bg-warning');
            //                 updateStatus(termIndex.toString(), 8, 'INC');

            //             }
            //         });


            //         function updateStatus(term, status, grade) {
            //             const element = $('.input_grades[data-studid="' + record.studid + '"][data-term="' +
            //                 term + '"]');


            //             if (status in statuses) {
            //                 element.addClass(statuses[status]);
            //                 if (status === 9) {
            //                     element.text('DROPPED');
            //                     hasFailedStatus = true;
            //                 } else if (status === 8) {
            //                     element.text('INC');
            //                     hasFailedStatus = true;

            //                 }
            //             }
            //             if (element.hasClass('bg-success')) {
            //                 element.removeClass('bg-warning');
            //                 element.prop('disabled', true).on('click', function() {
            //                     Toast.fire({
            //                         type: 'warning',
            //                         title: 'Grade Submitted.'
            //                     });
            //                 });
            //             }
            //             // if (element.hasClass('bg-success')) {
            //             //     element.removeClass('bg-warning');
            //             //     element.prop('disabled', true).on('click', function() {
            //             //         Toast.fire({
            //             //             type: 'warning',
            //             //             title: 'Grade Submitted.'
            //             //         });
            //             //     });
            //             // }
            //         }

            //         function checkStatus(status) {
            //             switch (status) {
            //                 case 0:
            //                     notSubmittedCount++;
            //                     break;
            //                 case 1:
            //                     submittedCount++;
            //                     break;
            //                 case 2:
            //                     approvedCount++;
            //                     break;
            //                 case 3:
            //                     pendingCount++;
            //                     break;
            //                 case 7:
            //                     postedCount++;
            //                     break;
            //             }
            //         }

            //         ['prelemstatus', 'midtermstatus', 'prefistatus', 'finalstatus'].forEach(status => {
            //             checkStatus(record[status]);
            //         });

            //         $('.select[data-studid="' + record.studid + '"]').attr('data-id', record.id);

            //         const finalStatusText = record.fgremarks || '';
            //         const finalStatusElement = $('th[data-studid="' + record.studid + '"][data-term="6"]');

            //         if (hasFailedStatus) {
            //             $('th[data-studid="' + record.studid + '"][data-term="5"]').text('---');
            //             $('th[data-studid="' + record.studid + '"][data-term="6"]').text('---');
            //             if (record.finalstatus === 8 || record.prelemstatus === 8 || record
            //                 .midtermstatus === 8 || record.prefistatus === 8) {
            //                 finalStatusElement.text('INC').addClass('bg-warning');
            //                 finalStatusElement.removeClass('bg-success bg-danger');
            //             } else {
            //                 finalStatusElement.text('FAILED').addClass('bg-danger');
            //                 finalStatusElement.removeClass('bg-success bg-warning');
            //             }
            //             failedCount++;
            //         } else {
            //             if (record.fg) {
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').text(record.fg);
            //             }
            //             finalStatusElement.text(finalStatusText);

            //             if (finalStatusText === 'PASSED' || finalStatusText === 'Passed') {
            //                 finalStatusElement.removeClass('bg-danger bg-warning').addClass('bg-success');
            //                 passedCount++;
            //             } else if (finalStatusText === 'INC' || finalStatusText === 'Inc') {
            //                 finalStatusElement.removeClass('bg-success bg-danger').addClass('bg-warning');
            //             } else if (finalStatusText === 'FAILED' || finalStatusText === 'Failed') {
            //                 finalStatusElement.removeClass('bg-success bg-warning').addClass('bg-danger');
            //                 failedCount++;
            //             } else {
            //                 finalStatusElement.removeClass('bg-success bg-danger bg-warning');
            //                 failedCount++;
            //             }

            //             $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled', false);
            //         }
            //     });
            // }

            // function plot_subject_grades(data) {
            //     let passedCount = 0;
            //     let failedCount = 0;
            //     let notSubmittedCount = 0;
            //     let submittedCount = 0;
            //     let approvedCount = 0;
            //     let pendingCount = 0;
            //     let postedCount = 0;

            //     const statuses = {
            //         1: 'bg-success',
            //         2: 'bg-secondary',
            //         3: 'bg-warning',
            //         4: 'bg-info',
            //         7: 'bg-primary',
            //         8: 'bg-warning',
            //         9: 'bg-danger'
            //     };

            //     $.each(data, function(index, record) {
            //         let hasFailedStatus = false;

            //         ['prelemgrade', 'midtermgrade', 'prefigrade', 'finalgrade'].forEach((term, idx) => {
            //             const termIndex = idx + 1;
            //             const grade = record[term];
            //             const status = record[term.replace('grade', 'status')];

            //             if (grade && grade.trim() !== '') {
            //                 const gradeElement = $('.input_grades[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 gradeElement.text(grade);
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').addClass(
            //                     'bg-info');
            //                 // $('th[data-studid="' + record.studid + '"][data-term="5"]').text('---');
            //                 updateStatus(termIndex.toString(), status, grade);
            //             } else if (status === 8) {
            //                 const incElement = $('.input_grades[data-studid="' + record.studid +
            //                     '"][data-term="' + termIndex + '"]');
            //                 incElement.addClass('bg-warning');
            //                 updateStatus(termIndex.toString(), 8, 'INC');

            //             }
            //         });


            //         function updateStatus(term, status, grade) {
            //             const element = $('.input_grades[data-studid="' + record.studid + '"][data-term="' +
            //                 term + '"]');


            //             if (status in statuses) {
            //                 element.addClass(statuses[status]);
            //                 if (status === 9) {
            //                     element.text('DROPPED');
            //                     hasFailedStatus = true;
            //                 } else if (status === 8) {
            //                     element.text('INC');
            //                     hasFailedStatus = true;

            //                 }
            //             }
            //             if (element.hasClass('bg-success')) {
            //                 element.prop('disabled', true).on('click', function() {
            //                     Toast.fire({
            //                         type: 'warning',
            //                         title: 'Grade Submitted.'
            //                     });
            //                 });
            //             }
            //         }

            //         function checkStatus(status) {
            //             switch (status) {
            //                 case 0:
            //                     notSubmittedCount++;
            //                     break;
            //                 case 1:
            //                     submittedCount++;
            //                     break;
            //                 case 2:
            //                     approvedCount++;
            //                     break;
            //                 case 3:
            //                     pendingCount++;
            //                     break;
            //                 case 7:
            //                     postedCount++;
            //                     break;
            //             }
            //         }

            //         ['prelemstatus', 'midtermstatus', 'prefistatus', 'finalstatus'].forEach(status => {
            //             checkStatus(record[status]);
            //         });

            //         $('.select[data-studid="' + record.studid + '"]').attr('data-id', record.id);

            //         const finalStatusText = record.fgremarks || '';
            //         const finalStatusElement = $('th[data-studid="' + record.studid + '"][data-term="6"]');

            //         if (hasFailedStatus) {
            //             $('th[data-studid="' + record.studid + '"][data-term="5"]').text('---');
            //             $('th[data-studid="' + record.studid + '"][data-term="6"]').text('---');
            //             if (record.finalstatus === 8 || record.prelemstatus === 8 || record
            //                 .midtermstatus === 8 || record.prefistatus === 8) {
            //                 finalStatusElement.text('INC').addClass('bg-warning');
            //                 finalStatusElement.removeClass('bg-success bg-danger');
            //             } else {
            //                 finalStatusElement.text('FAILED').addClass('bg-danger');
            //                 finalStatusElement.removeClass('bg-success bg-warning');
            //             }
            //             failedCount++;
            //         } else {
            //             if (record.fg) {
            //                 $('th[data-studid="' + record.studid + '"][data-term="5"]').text(record.fg);
            //             }
            //             finalStatusElement.text(finalStatusText);

            //             if (finalStatusText === 'PASSED') {
            //                 finalStatusElement.removeClass('bg-danger bg-warning').addClass('bg-success');
            //                 passedCount++;
            //             } else if (finalStatusText === 'INC') {
            //                 finalStatusElement.removeClass('bg-success bg-danger').addClass('bg-warning');
            //             } else if (finalStatusText === 'FAILED') {
            //                 finalStatusElement.removeClass('bg-success bg-warning').addClass('bg-danger');
            //                 failedCount++;
            //             } else {
            //                 finalStatusElement.removeClass('bg-success bg-danger bg-warning');
            //                 failedCount++;
            //             }

            //             $('.student-checkbox[data-studid="' + record.studid + '"]').prop('disabled', false);
            //         }
            //     });
            // }


            $(document).on('click', '#view_pdf', function() {
                var temp_id = $(this).attr('data-id');
                window.open(
                    `/college/teacher/student-list/print/${$('#filter_sy').val()}/${$('#filter_semester').val()}/${temp_id}`,
                    '_blank');

            })

            function datatable_2(data) {
                // Check if data is an object and contains the students array
                if (typeof data === 'object' && Array.isArray(data.students)) {
                    // Extract students array
                    const students = data.students;


                    // Group students by gender
                    const groupedByGender = students.reduce((acc, student) => {
                        // Fallback for undefined gender
                        const gender = student.gender || 'Unspecified';
                        if (!acc[gender]) {
                            acc[gender] = [];
                        }
                        acc[gender].push(student);
                        return acc;
                    }, {});



                    // Clear the table body
                    $("#datatable_2").empty();

                    // Create an array of gender keys and sort it to prioritize 'Male'
                    const genderKeys = Object.keys(groupedByGender);
                    genderKeys.sort((a, b) => {
                        if (a.toLowerCase() === 'male') return -1;
                        if (b.toLowerCase() === 'male') return 1;
                        return 0;
                    });
                    // Loop through each gender group in the sorted order
                    for (let gender of genderKeys) {
                        // Determine background color based on gender
                        let backgroundColor = gender.toLowerCase() === 'male' ? '#8ec9fd' : '#fd8ec9';

                        // Add a header row for each gender
                        $("#datatable_2").append(`
                            <tr style="background-color: ${backgroundColor};">
                                <td colspan="7"><strong>${gender.toUpperCase()}</strong></td>
                            </tr>
                        `);

                        // Add rows for each student in the gender group
                        groupedByGender[gender].forEach(student => {
                            $("#datatable_2").append(`
                    <tr>
                        <td>${student.sid || ''}</td>
                        <td>${student.lastname || ''}, ${student.firstname || ''}, ${student.middlename || ''}, ${student.suffix || ''}</td>
                        <td>${student.levelname || ''}</td>
                        <td>${student.courseabrv || ''}</td>
                        <td>${student.contactno || ''}</td>
                    </tr>
                `);
                        });
                    }

                    // Initialize DataTable
                    $("#datatable_2_table").DataTable({
                        destroy: true,
                        lengthChange: false,
                        autoWidth: false,
                        ordering: true,
                        columnDefs: [{
                                targets: [0, 2, 5, 6],
                                orderable: true
                            },
                            {
                                targets: [3, 4],
                                orderable: false
                            }
                        ]
                    });

                } else {
                    console.error('Invalid data format:', data);
                }
            }



            function get_student(temp_id) {
                $.ajax({
                    type: 'GET',
                    url: `/college/teacher/student-list-for-all/${$('#filter_sy').val()}/${$('#filter_semester').val()}/${temp_id}`,
                    success: function(data) {
                        // Call datatable_2 with the fetched data
                        datatable_2(data);
                    }
                });
            }

            $(document).on('click', '.view_students', function() {
                temp_id = $(this).attr('data-id')
                $('#view_pdf').attr('data-id', temp_id);
                $('#modal_1').modal()
                get_student(temp_id)
            })


            function datatable_1(data) {
                var temp_subjects = data;

                $("#datatable_1").DataTable({
                    destroy: true,
                    data: temp_subjects,
                    // lengthChange: false,
                    // scrollX: true,
                    // autoWidth: false,
                    columns: [{
                            "data": "sectionDesc"
                        },
                        {
                            "data": "subjDesc"
                        },
                        {
                            "data": "yearDesc"
                        },
                        {
                            "data": "schedtime"
                        },
                        {
                            "data": "days"
                        },
                        {
                            "data": "roomname"
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
                            'orderable': true,
                            'createdCell': function(td, cellData, rowData) {

                                var text = '<a class="badge badge-primary text-white mb-0">' +
                                    rowData.sectionDesc + '</a>';
                                $(td).html(text).addClass('align-middle');
                            }
                        },


                        {
                            'targets': 1,
                            'orderable': true,
                            'createdCell': function(td, cellData, rowData) {
                                var text = '<a class="mb-0">' + rowData.subjDesc + '</a>';
                                $(td).html(text).addClass('align-middle');
                            }
                        },



                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData) {

                                var text = '<a class="mb-0">' + rowData.yearDesc + '</a>';
                                $(td).html(text).addClass('align-middle');
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData) {

                                var conflictIcon =
                                    '<i class="fa fa-exclamation-triangle text-warning" data-toggle="tooltip" title="Conflict Schedule"></i>';


                                var schedotherclass = rowData.schedotherclass || '';
                                var scheduleTime = rowData.schedtime;
                                var time = rowData.schedtime;
                                var day = rowData.days.split('/').map(function(day) {
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
                                }).filter(Boolean);
                                var room = rowData.roomname;

                                var subjDesc = rowData.subjDesc;
                                var semid = rowData.semid; // Assume schedule contains day
                                var schedid = rowData.schedid;


                                $.ajax({
                                    url: '/college/teacher/schedule/conflict',
                                    method: 'GET',
                                    data: {

                                        syid: $('#filter_sy').val(),
                                        semid: $('#filter_semester').val(),
                                        room: room,
                                        schedid: schedid,
                                        time: time,
                                        day: day // Assume schedule contains day


                                    },

                                    success: function(conflictData) {
                                        var hasConflict = conflictData.length > 0;
                                        var conflictIcon =
                                            '<i class="fa fa-exclamation-triangle text-warning" data-toggle="tooltip" title="Conflict Schedule"></i>';

                                        var text = '<div>' +
                                            '<a class="mb-0" style="color:#1583fc; font-style:italic;">' +
                                            schedotherclass + '</a><br>' +
                                            '<span style="font-size: 12px;">' +
                                            scheduleTime + '&nbsp&nbsp' +
                                            (hasConflict ? ' ' + conflictIcon : '') +
                                            '</span>';

                                        text += '</div>';

                                        $(td).html(text).addClass('align-middle');
                                        $(td).find('[data-toggle="tooltip"]').tooltip();
                                    }
                                });


                            }
                        },
                        {
                            'targets': 4,
                            'orderable': true,
                            'createdCell': function(td, cellData, rowData) {

                                var text =
                                    '<a class="mb-0" style="display: block; text-align: center;">' +
                                    rowData.days + '</a>';
                                $(td).html(text).addClass('align-middle');
                            }
                        },
                        {
                            'targets': 5,
                            'orderable': true,
                            'createdCell': function(td, cellData, rowData) {

                                var roomname = rowData.roomname || 'Not assigned';
                                var text = '<a class="mb-0" style="' + (roomname ===
                                        'Not assigned' ? 'color:red; font-style:italic;' : '') +
                                    '">' +
                                    roomname + '</a>';
                                $(td).html(text).addClass('align-middle');
                            }
                        },
                        {
                            'targets': 6,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData) {

                                // var studentCount = rowData.students ? rowData.students.length : 0;
                                // var studentCount = rowData.students && Array.isArray(rowData
                                //     .students) ? rowData.students.length.toString() : '0';
                                // var studentCount = rowData.students.length ? String(rowData.students
                                //     .length) : '0';
                                var studentCount = rowData.students.studentCount;
                                var text =
                                    '<a href="#" class="text-primary view_students" data-id="' +
                                    rowData.schedid +
                                    '" style="font-size: 20px; display: block; text-align: center; font-weight:bold">' +
                                    studentCount + '</a>';
                                $(td).html(text).addClass('align-middle');
                            }
                        },
                        {
                            'targets': 7,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData) {
                                var link =
                                    '<a href="#" style="color: #blue; text-decoration: underline;font-weight: bold;" class="view_grades" data-id="' +
                                    rowData.schedid +
                                    '"> Grading</a>';
                                $(td)[0].innerHTML = link;
                                $(td).addClass('text-center align-middle');
                            }
                        }
                    ]
                });
            }



            // function datatable_3() {

            //     var all_data = all_gradestatus
            //     if ($('#term').val() != "") {
            //         if ($('#term').val() == "Whole Sem") {
            //             all_data = all_gradestatus.filter(x => x.schedotherclass == null)
            //         } else {
            //             all_data = all_gradestatus.filter(x => x.schedotherclass == $('#term').val())
            //         }
            //     }

            //     $("#datatable_3").DataTable({
            //         destroy: true,
            //         data: all_data,
            //         lengthChange: false,
            //         scrollX: true,
            //         autoWidth: false,
            //         columns: [{
            //                 "data": "sectionDesc"
            //             },
            //             {
            //                 "data": "subjDesc"
            //             },
            //             {
            //                 "data": null
            //             },
            //             {
            //                 "data": null
            //             },
            //             {
            //                 "data": null
            //             },
            //             {
            //                 "data": null
            //             }
            //         ],
            //         columnDefs: [{
            //                 'targets': 0,
            //                 'orderable': true,
            //                 'createdCell': function(td, cellData, rowData, row, col) {
            //                     var text = '<a class="mb-0">' + rowData.sectionDesc +
            //                         '</a><p class="text-muted mb-0" style="font-size:.7rem">' +
            //                         rowData.levelname.replace('COLLEGE', '') + ' - ' + rowData
            //                         .courseabrv + '</p>';
            //                     $(td)[0].innerHTML = text
            //                     $(td).addClass('align-middle')
            //                 }
            //             },
            //             {
            //                 'targets': 1,
            //                 'orderable': true,
            //                 'createdCell': function(td, cellData, rowData, row, col) {


            //                     var schedotherclass = ''

            //                     var text = '<a class="mb-0">' + rowData.subjDesc +
            //                         '</a><p class="text-muted mb-0" style="font-size:.7rem">' +
            //                         rowData.subjCode +
            //                         ' - <i class="mb-0 text-danger" style="font-size:.7rem">' +
            //                         schedotherclass + '</i></p>';
            //                     $(td)[0].innerHTML = text
            //                     $(td).addClass('align-middle')
            //                 }
            //             },
            //             {
            //                 'targets': 2,
            //                 'orderable': false,
            //                 'createdCell': function(td, cellData, rowData, row, col) {
            //                     if (rowData.gradestatus.length == 0) {
            //                         var text = '<a class="mb-0">Not Submitted</a>';
            //                     } else {
            //                         var status = ''
            //                         if (rowData.gradestatus[0].prelimstatus == null) {
            //                             status = 'Not Submitted'
            //                         } else if (rowData.gradestatus[0].prelimstatus == 1) {
            //                             status = 'Submitted'
            //                         }
            //                         var text = '<a class="mb-0">' + status +
            //                             '</a><p class="text-muted mb-0" style="font-size:.7rem">' +
            //                             rowData.gradestatus[0].prelimdate + '</p>';
            //                     }

            //                     $(td)[0].innerHTML = text
            //                     $(td).addClass('align-middle')
            //                     $(td).addClass('text-center')
            //                     if (school == 'spct'.toUpperCase()) {
            //                         $(td).attr('hidden', 'hidden')
            //                     }
            //                 }
            //             },
            //             {
            //                 'targets': 3,
            //                 'orderable': false,
            //                 'createdCell': function(td, cellData, rowData, row, col) {
            //                     if (rowData.gradestatus.length == 0) {
            //                         var text = '<a class="mb-0">Not Submitted</a>';
            //                     } else {
            //                         var status = ''
            //                         if (rowData.gradestatus[0].midtermstatus == null) {
            //                             status = 'Not Submitted'
            //                         } else if (rowData.gradestatus[0].midtermstatus == 1) {
            //                             status = 'Submitted'
            //                         }
            //                         var text = '<a class="mb-0">' + status +
            //                             '</a><p class="text-muted mb-0" style="font-size:.7rem">' +
            //                             rowData.gradestatus[0].midtermdate + '</p>';
            //                     }

            //                     $(td)[0].innerHTML = text
            //                     $(td).addClass('align-middle')
            //                     $(td).addClass('text-center')
            //                     if (school == 'spct'.toUpperCase()) {
            //                         $(td).attr('hidden', 'hidden')
            //                     }
            //                 }
            //             },
            //             {
            //                 'targets': 4,
            //                 'orderable': false,
            //                 'createdCell': function(td, cellData, rowData, row, col) {
            //                     if (rowData.gradestatus.length == 0) {
            //                         var text = '<a class="mb-0">Not Submitted</a>';
            //                     } else {
            //                         var status = ''
            //                         if (rowData.gradestatus[0].prefistatus == null) {
            //                             status = 'Not Submitted'
            //                         } else if (rowData.gradestatus[0].prefistatus == 1) {
            //                             status = 'Submitted'
            //                         }
            //                         var text = '<a class="mb-0">' + status +
            //                             '</a><p class="text-muted mb-0" style="font-size:.7rem">' +
            //                             rowData.gradestatus[0].prefidate + '</p>';
            //                     }

            //                     $(td)[0].innerHTML = text
            //                     $(td).addClass('align-middle')
            //                     $(td).addClass('text-center')
            //                 }
            //             },
            //             {
            //                 'targets': 5,
            //                 'orderable': false,
            //                 'createdCell': function(td, cellData, rowData, row, col) {
            //                     if (rowData.gradestatus.length == 0) {
            //                         var text = '<a class="mb-0">Not Submitted</a>';
            //                     } else {
            //                         var status = ''
            //                         if (rowData.gradestatus[0].finalstatus == null) {
            //                             status = 'Not Submitted'
            //                         } else if (rowData.gradestatus[0].finalstatus == 1) {
            //                             status = 'Submitted'
            //                         }
            //                         var text = '<a class="mb-0">' + status +
            //                             '</a><p class="text-muted mb-0" style="font-size:.7rem">' +
            //                             rowData.gradestatus[0].finaldate + '</p>';
            //                     }
            //                     $(td)[0].innerHTML = text
            //                     $(td).addClass('align-middle')
            //                     $(td).addClass('text-center')
            //                 }
            //             }

            //         ]
            //     })
            // }

            // Event listener for the studentFilter checkbox
            $('#studentFilter').on('change', function() {
                // Check or uncheck all student checkboxes based on the state of studentFilter
                if ($(this).is(':checked')) {
                    // Check all student checkboxes
                    $('.select').prop('checked', true);
                } else {
                    // Uncheck all student checkboxes
                    $('.select').prop('checked', false);
                }
            });



            // Update malecheckbox event to only affect checkboxes in the male section
            $(document).on('change', '#malecheckbox', function() {
                var isChecked = $(this).is(':checked');
                // Only affect checkboxes within the male section
                $('#student_list_grades').find('tr.male_section').nextUntil('tr.female_section').find(
                    'input.student-checkbox').prop('checked', isChecked);
            });

            // // Update malecheckbox event to only affect checkboxes in the male section
            // $(document).on('change', '#malecheckbox', function() {
            //     var isChecked = $(this).is(':checked');
            //     // Only affect checkboxes within the male section
            //     $('#student_list_grades').find('tr.male_section').nextUntil('tr.bg-secondary').find(
            //         'input.student-checkbox').prop('checked', isChecked);
            // });

            // Update femalecheckbox event to only affect checkboxes in the female section
            $(document).on('change', '#femalecheckbox', function() {
                var isChecked = $(this).is(':checked');
                // Only affect checkboxes within the female section
                $('#student_list_grades').find('tr.female_section').nextUntil('tr.male_section').find(
                    'input.student-checkbox').prop('checked', isChecked);
            });



            $(document).on('click',
                '.submit_all_btn, .grade_submissions_midterm, .grade_submissions_semifinal, .grade_submissions_final',
                function() {
                    // submit_grade();
                    submit_grade(this); // Pass the clicked element to the function
                }
            );

            $(document).on('click',
                '.grade_submissions_prelim, .submit_all_btn, .grade_submissions_midterm, .grade_submissions_semifinal, .grade_submissions_final',
                function() {
                    // submit_grade();
                    submit_grade(this); // Pass the clicked element to the function
                }
            );



        })
    </script>
@endsection
