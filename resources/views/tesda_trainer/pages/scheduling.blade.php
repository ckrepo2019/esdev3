@extends('tesda_trainer.layouts.app2')

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
    @php
        $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
        $semester = DB::table('semester')->get();
        $levelname = DB::table('college_year')->get();
    @endphp

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Class Schedule</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active font-weight-bold">Class Schedule</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content pt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="info-box shadow">
                                {{-- <span class="info-box-icon bg-primary"><i class="fas fa-calendar-check"></i></span> --}}
                                <div class="info-box-content">
                                    <div class="row pb-2 d-flex">
                                        <div class="col-md-2 col-sm-12 ml-3">
                                            <label for="" class="mb-0">Courses/Specialization</label>
                                            <select class="form-control form-control-sm select2 course" id="course_filter" style="width: 100%;">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header  bg-secondary">
                            <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Class Schedule Details</h3>

                        </div>

                        <div class="card-body  p-2">
                            <div class="mt-2 p-2 row">
                                <div class="col-md-2">
                                    {{-- <button class="btn btn-success btn-sm" id="add_schedule_load" data-toggle="modal" data-target="#schedule_load">Add Schedule Load</button> --}}
                                </div>
                                <div class="col-md-10 d-flex flex-row justify-content-end">
                                    {{-- <button class="btn btn-primary btn-sm" id="print_schedule_load"><i class="fas fa-filter"></i> Print Schedule Load</button> --}}
                                </div>
                            </div>

                        </div>
                        <div class="row p-2">
                            <div class="col-md-12" style="font-size:.8rem">
                                <table class="table table-sm display table-bordered table-responsive-sm" id="datatable_1"
                                    width="100%">
                                    <thead>
                                        <tr class="font-20-baga">
                                            <th>Batch</th>
                                            <th>Specialization</th>
                                            <th>Competency Description</th>
                                            <th class="">Hours</th>
                                            <th>Schedule</th>
                                            <th class="">Room</th>
                                            <th class="">Enrolled</th>
                                            {{-- <th class="text-center">Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody id="table_1">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </section>




    {{-- <div class="modal fade" id="conflict_modal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="card-header  bg-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="text-center text-danger font-weight-bold mb-0"><i class="fa fa-exclamation-triangle"></i>
                        Conflict of Schedules</h5>
                    <div class="mt-4 text-center"><span class="font-weight-bold conflicted_subject"
                            style="text-decoration: underline;"></span> is in conflict with other schedules</div>

                    <div style="height: 300px; overflow-y: auto;" id="conflict_modal_body">

                    </div>

                </div>
            </div>
        </div>
    </div> --}}


    {{-- <div class="modal fade" id="modal_1" data-backdrop="static" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0 bg-dark">
                    <h4 class="modal-title">
                        <span class="mt-1" id="sectionNameHeader"></span>
                    </h4>
                    <div class="d-flex align-items-center ml-auto">
                        <a class="btn btn-primary btn-sm ml-2" id="view_pdf" href="#">
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
                                <span id="subjectDesc"></span>
                                <a class="text-primary" id="subjectCode"></a>
                            </div>
                        </div>
                        <!-- Level -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-graduation-cap"></i> Level</span>
                                <span id="collegeLevel"></span>
                            </div>
                        </div>
                        <!-- Section -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-building"></i> Section</span>
                                <span id="section"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body" style="font-size:.8rem;height: 450px; overflow-y: auto;">
                    <table class="table table-sm table-striped" width="100%">
                        <thead>
                            <tr id="enrolled_datatable_table">
                                <th width="10%">Student ID No.</th>
                                <th width="15%" class="text-center">
                                    <div class="d-flex flex-row justify-content-between">
                                        <p class="mb-0">Student Name</p>
                                        <a href="" id="sort"><i class="fa fa-sort"></i></a>
                                    </div>
                                </th>
                                <th width="10%">Academic Level</th>
                                <th width="12%">Course</th>
                                <th width="10%">Contact No.</th>
                                <th width="10%">Email Address</th>
                                <th width="auto">Address</th>
                            </tr>
                        </thead>
                        <tbody id="student_table">
                            <tr class="male_students">
                                <td colspan="7" class="font-weight-bold male_sort_arrow" style="background-color: #8ec9fd">Male <a
                                        href="" id="male_sort"><i class="fa fa-sort"></td>
                            </tr>
                            <tr class="female_students">
                                <td colspan="7" class=" font-weight-bold female_sort_arrow" style="background-color: #fd8ec9">Female <a
                                        href="" id="female_sort"><i class="fa fa-sort"></td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
         $(document).ready(function() {

            $('#course_filter').select2({
                placeholder: 'Select Course',
                allowClear: true
            })

            $('#course_filter').on('change', function(){
                get_trainer_schedule()
            })

            get_courses()
            function get_courses(){
                $.ajax({
                    type:'get',
                    url: '/tesda/course_setup/get/courses',
                    data: {

                    },
                    success: function(data) {
                        $('#course_filter').empty();
                        $('#course_filter').append('<option value=""></option>');
                        $.each(data, function(index, course) {
                            $('#course_filter').append('<option value="' + course.id + '">' + course.course_name + '</option>');
                        });
                    }
                })
            }
            get_trainer_schedule()
            function get_trainer_schedule(){
                var courseid = $('#course_filter').val()

                $.ajax({
                    type: 'get',
                    url: '/tesda/trainer/schedule/get',
                    data: {
                        courseid: courseid
                    },
                    success: function(data) {
                        display_schedule(data)
                    }
                })
            }

            function display_schedule(schedule){
                $('#datatable_1').DataTable({
                    destroy: true,
                    data: schedule,
                    columns: [
                        { data: 'batch_desc' },
                        { data: null },
                        { data: null },
                        { data: null },
                        { data: null },
                        { data: null },
                        { data: null },
                    ],
                    columnDefs: [
                        {
                            targets: 0,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<div href="#" class="mb-0" >${rowData.batch_desc}</div>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 1,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<div href="#" class="mb-0 align-middle">${rowData.course_name}<div class="mb-0 text-success" style="font-size:.5rem!important">${rowData.course_code}</div></div>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 2,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<div href="#" class="mb-0 align-middle" ><div class="mb-0" style="font-size:.5rem!important">${rowData.competency_type}</div>${rowData.competency_desc}<div class="mb-0 text-success" style="font-size:.5rem!important">${rowData.competency_code}</div></div>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 3,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<div href="#" class="mb-0 align-middle">${rowData.hours}</div>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 4,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<div href="#" class="mb-0 align-middle">${rowData.date_range || '--'}<div>${rowData.time_range || '--'}</div></div>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 5,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<div href="#" class="mb-0 align-middle">${rowData.roomname || '--'}<div class="mb-0 text-success" style="font-size:.5rem!important">${rowData.description}</div></div>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            targets: 6,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                $(td).html(`<div href="#" class="mb-0 align-middle text-primary">${rowData.enrolled || '--'}</div>`)
                                $(td).addClass('align-middle')
                            }
                        },
                    ]
                })
            }

         })
        
        
    </script>
@endsection
