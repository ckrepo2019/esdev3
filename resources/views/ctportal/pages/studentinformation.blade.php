@extends('ctportal.layouts.app2')

@section('pagespecificscripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0;
        }

        .headerText {
            font-size: 12px;
        }

        .viewPDF {
            width: 50px;
            padding: 10px;
        }

        #datatable_3 {
            border-collapse: collapse;
            width: 100%;
        }

        #datatable_3 th,
        #datatable_3 td {
            border: 1px solid #ddd;
            text-align: left;
        }

        #datatable_2 th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        #datatable_2 {
            border-collapse: collapse;
            width: 100%;
        }

        #datatable_2 th,
        #datatable_2 td {
            border: 1px solid #ddd;
            text-align: left;
        }

        #datatable_2 th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    @php
        $sy = DB::table('sy')->orderBy('sydesc')->get();
        $semester = DB::table('semester')->get();
        $schoolinfo = DB::table('schoolinfo')->first()->abbreviation;
    @endphp

    <div class="modal fade" id="modal_1" data-backdrop="static" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl mw-100 mh-100" style="width: 90vw; height: 93vh;">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0 bg-dark">
                    <h4 class="modal-title">
                        <span class="mt-1" id="sectionNameHeader"></span>
                    </h4>
                    <div class="d-flex align-items-center ml-auto">
                        <a class="btn btn-primary btn-sm ml-2" id="view_pdf" data-id = "" href="#">
                            <i class="far fa-file-pdf mr-1"></i> PRINT PDF
                        </a>
                        <button type="button" class="close pb-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
                <div class="container-fluid headerText">
                    <div class="row py-3">
                        <!-- Teacher -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-chalkboard-teacher"></i>
                                    Instructor</span>
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
                <div class="col-md-12">
                    <div class="d-flex justify-content-end mb-1">
                        <input type="text" id="commonSearchBarStud" class="form-control w-25"
                            placeholder="Search for students...">
                    </div>
                </div>
                <div class="modal-body" style="font-size:12px;">
                    <div style="overflow-y: scroll; height: 400px; width: 100%">
                        <table class="table table-sm table-striped">
                            <thead style="width: 100%">
                                <tr id="datatable_2_row">
                                    <th>Student ID No.</th>
                                    <th>Student Name
                                        <i class="sort-icon fas fa-sort"
                                            style="font-size: 12px; color: #3e3e3e; cursor: pointer;"></i>
                                    </th>
                                    <th>Academic Level</th>
                                    <th>Course</th>
                                    <th>Contact No.</th>
                                    <th>Email Address</th>
                                    <th>Address</th>
                                </tr>
                            </thead>
                            <tbody id="datatable_2">
                                <!-- Rows will be dynamically inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_3" data-backdrop="static" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl mw-100 mh-100" style="width: 90vw; height: 93vh;">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0 bg-dark">
                    <h4 class="modal-title">
                        <span class="mt-1" id="sectionNameHeaderPermit"></span>
                    </h4>
                    <div class="d-flex align-items-center ml-auto">
                        <a class="btn btn-primary btn-sm ml-2" id="view_permitpdf" data-id = "" href="#">
                            <i class="far fa-file-pdf mr-1"></i> PRINT PDF
                        </a>
                        <button type="button" class="close pb-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <h4 class="modal-title">
                        <span class="mt-1" id="sectionNameHeader"></span>
                    </h4>
                </div>

                <div class="container-fluid headerText">
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
                                <span id="subjectDescPerm"></span>
                                <a class="text-primary" id="subjCodepPerm"></a>
                            </div>
                        </div>
                        <!-- Level -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-graduation-cap"></i> Level</span>
                                <span id="collegeLevelPerm"></span>
                            </div>
                        </div>
                        <!-- Section -->
                        <div class="col-md-3">
                            <div class="d-flex flex-column text-center">
                                <span class="font-weight-bold h6"><i class="fas fa-building"></i> Section</span>
                                <span id="sectionPerm"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body" style="font-size:.9rem">
                    <div class="row">
                        <div class="col-md-3">
                            <select class="form-control form-control-sm select2" id="month_filter" disabled>
                                <option value="">Select Term</option>
                                <option value="TERM">TERM</option>
                                <option value="PRELIM">PRELIM</option>
                                <option value="MIDTERM">MIDTERM</option>
                                <option value="FINAL">FINAL</option>
                                <option value="SEMI-FINAL">SEMI-FINAL</option>
                            </select>
                        </div>
                        <!-- Common Search Bar (Right-Aligned) -->
                        <div class="col-md-9">
                            <div class="d-flex justify-content-end mb-3" style="width: 100%">
                                <input type="text" id="commonSearchBar" class="form-control w-25"
                                    placeholder="Search for students...">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12" id="datatable_3" style="height: 300px; overflow-y: scroll;">
                            <table class="table table-sm table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10%">Student ID</th>
                                        <th style="width: 15%">Student Name
                                            <i class="sort-icon-permit fas fa-sort"
                                                style="font-size: 15px; color: #3e3e3e; cursor: pointer;"></i>
                                        </th>
                                        <th style="width: 15%">Academic Level</th>
                                        <th style="width: 10%">Course</th>
                                        <th style="width: 10%">Status</th>
                                    </tr>
                                </thead>
                            </table>

                            <table class="table table-sm table-striped" id="datatable_male" width="100%">
                                <thead>
                                    <tr>
                                        <th colspan="5" style="background-color: #8ec9fd" class="p-2">Male</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Male students data here -->
                                </tbody>
                            </table>

                            <table class="table table-sm table-striped" id="datatable_female" width="100%">
                                <thead>
                                    <tr>
                                        <th colspan="5" style="background-color: #fd8ec9" class="p-2"> Female</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Female students data here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_2" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title">Grades</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-sm table-striped mb-0  table-striped" style="font-size:.9rem">
                                <tr>
                                    <th id="section" width="30%"></th>
                                    <th id="subject" width="70%"></th>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-sm table-striped table-bordered " style="font-size:.9rem">
                                <thead>
                                    <tr>
                                        <th id="section" width="40%">Student</th>
                                        <th id="subject" width="15%" class="text-center">Prelim</th>
                                        <th id="subject" width="15%" class="text-center">Midterm</th>
                                        <th id="subject" width="15%" class="text-center">PreFinal</th>
                                        <th id="subject" width="15%" class="text-center">Final</th>
                                    </tr>
                                </thead>
                                <tbody id="student_list_grades">

                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <button id="save_grades" class="btn btn-primary btn-sm" disabled hidden>Save
                                Grades</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-1 pb-1" style="font-size:.7rem">
                    <i id="message_holder"></i>
                </div>
            </div>
        </div>
    </div>

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Student Information</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Information</li>
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
                            <div class="info-box shadow-lg">
                                <div class="info-box-content">
                                    <div class="row">
                                        <div class="col-md-4  form-group">
                                            <label for="">School Year</label>
                                            <select class="form-control form-control-sm select2" id="filter_sy">
                                                @foreach ($sy as $item)
                                                    @if ($item->isactive == 1)
                                                        <option value="{{ $item->id }}" selected="selected">
                                                            {{ $item->sydesc }}</option>
                                                    @else
                                                        <option value="{{ $item->id }}">{{ $item->sydesc }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label for="">Semester</label>
                                            <select class="form-control form-control-sm  select2" id="filter_semester">
                                                <option value="">Select semester</option>
                                                @foreach ($semester as $item)
                                                    <option {{ $item->isactive == 1 ? 'selected' : '' }}
                                                        value="{{ $item->id }}">{{ $item->semester }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-md-4">
                                            <button class="btn btn-info btn-block btn-sm" id="filter_button_1"><i
                                                    class="fas fa-filter"></i> Filter</button>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12" style="font-size:.9rem">
                                    <table class="table table-striped" id="datatable_1" width="100%">
                                        <thead>
                                            <tr>
                                                <th width= "10%">Section</th>
                                                <th width= "30%">Subject</th>
                                                <th width= "13%">Level</th>
                                                <th width= "15%">Time Schedule</th>
                                                <th>Day</th>
                                                <th width= "13%">Room</th>
                                                <th>Enrolled</th>
                                                <th width= "16%"></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>
@endsection

@section('footerscript')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#month_filter').select2()

            var all_subject = []
            var all_permits = []
            get_schedule()


            $(document).on('click', '#filter_button_1', function() {
                get_schedule()
            })

            $('#filter_sy, #filter_semester').on('change', function () {
                get_schedule();
            });

            // function get_subjects() {
            //     $.ajax({
            //         type: 'GET',
            //         url: '/college/teacher/student/grades/subject',
            //         data: {
            //             syid: $('#filter_sy').val(),
            //             semid: $('#filter_semester').val(),
            //             teacherid: 73

            //         },
            //         success: function(data) {
            //             all_subject = data
            //             get_exampermit()
            //             datatable_1()
            //         }
            //     })
            // }

            function get_schedule() {
                $.ajax({
                    type: 'GET',
                    url: '/college/teacher/schedule/get',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        teacherid: 73

                    },
                    success: function(data) {
                        console.log('asdadasdasdasda')
                        // get_exampermit()
                        get_student_all(data)

                    }
                })
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

            var students;

            $(document).on('click', '.view_students, .view_permit', function() {
                // var schedId = $(this).data('id');
                var temp_id = $(this).attr('data-id');
                $('#view_pdf').attr('data-id', temp_id);
                $('#view_permitpdf').attr('data-id', temp_id);

                var isPermitView = $(this).hasClass(
                    'view_permit'); // Check if the clicked element has the class 'view_permit'

                $.ajax({
                    type: 'GET',
                    url: `/college/teacher/student-list-for-all/${$('#filter_sy').val()}/${$('#filter_semester').val()}/${temp_id}`,
                    success: function(data) {
                        var sched = all_subject.filter(x => x.schedid == temp_id);
                        console.log(sched, 'Schedule Data');

                        var students = data.students;

                        if (isPermitView) {
                            // Logic for opening modal_3
                            var permits = data.permits || []; // Ensure permits is an array

                            console.log('Opening modal_3 with permits:',
                                permits); // Debugging statement
                            datatable_3(students,
                                permits
                            ); // Initialize datatable_3 with student data and permits

                            // Update modal_3 header with subject information
                            $('#sectionNameHeaderPermit').text(sched[0].subject || '');
                            $('#subjectDescPerm').text(sched[0].subjDesc || '');
                            $('#subjCodepPerm').text(sched[0].subjCode || '');
                            $('#collegeLevelPerm').text(sched[0].yearDesc || '');
                            $('#sectionPerm').text(sched[0].sectionDesc || '');

                            $('#modal_3').modal(
                                'show'); // Ensure the modal is triggered to show
                        } else {
                            // Logic for opening modal_1
                            console.log('Opening modal_1 with students:',
                                students); // Debugging statement
                            datatable_2(students); // Initialize datatable_2 with student data

                            // Update modal_1 header with subject information
                            $('#sectionNameHeader').text(sched[0].subject || '');
                            $('#subjectDesc').text(sched[0].subjDesc || '');
                            $('#subjectCode').text(sched[0].subjCode || '');
                            $('#collegeLevel').text(sched[0].yearDesc || '');
                            $('#section').text(sched[0].sectionDesc || '');

                            $('#modal_1').modal(
                                'show'); // Ensure the modal is triggered to show
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', error); // Log any AJAX errors
                    }
                });
            });


            // function get_exampermit(temp_id) {
            //     $.ajax({
            //         type: 'GET',
            //         url: '/college/teacher/student/exampermit',
            //         data: {
            //             syid: $('#filter_sy').val(),
            //             semid: $('#filter_semester').val(),
            //             schedid: temp_id
            //         },
            //         success: function(data) {
            //             console.log(data, 'permit id');
            //             students = data.students

            //             // Filter to find the relevant subject from the returned data
            //             var subject = all_subject.find(x => x.schedid == temp_id);
            //             console.log(students, 'subject id');

            //             // Assign the exam permits to all_permits and populate the datatable
            //             all_permits = subject.exampermit;
            //             datatable_3(subject.students, temp_id);
            //         },
            //         error: function(xhr, status, error) {
            //             console.error('AJAX request failed:', error);
            //         }
            //     });
            // }


            var temp_id = null

            // $(document).on('click', '.view_students', get_student, function() {
            //     temp_id = $(this).attr('data-id')
            //     console.log(temp_id)
            //     var schedId = $(this).data('id');
            //     get_student(schedId)
            // })

            // $(document).on('click', '.view_permit', function() {
            //     $('#modal_3').modal();

            //     temp_id = $(this).attr('data-id');

            //     // Call the function to get the exam permits
            //     get_exampermit(temp_id);
            // });

            function get_grades(schedid) {
                var sched = all_subject.filter(x => x.schedid == schedid)
                var pid = sched[0].pid
                var sectionid = sched[0].sectionID

                $.ajax({
                    type: 'GET',
                    url: '/college/teacher/student/grades/get',
                    data: {

                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        pid: pid,
                        sectionid: sectionid
                    },
                    success: function(data) {

                        $('.grade_td').addClass('input_grades')

                        if (data.length == 0) {
                            Toast.fire({
                                type: 'warning',
                                title: 'No grades found!'
                            })
                            $('#message_holder').text('No grades found. Please input student grades.')
                        } else {
                            $.each(data, function(a, b) {
                                $('.input_grades[data-studid="' + b.studid +
                                    '"][data-term="prelemgrade"]').text(b.prelemgrade !=
                                    null ? parseInt(b.prelemgrade) : '')
                                $('.input_grades[data-studid="' + b.studid +
                                    '"][data-term="midtermgrade"]').text(b.midtermgrade !=
                                    null ? parseInt(b.midtermgrade) : '')
                                $('.input_grades[data-studid="' + b.studid +
                                    '"][data-term="prefigrade"]').text(b.prefigrade !=
                                    null ? parseInt(b.prefigrade) : '')
                                $('.input_grades[data-studid="' + b.studid +
                                    '"][data-term="finalgrade"]').text(b.finalgrade !=
                                    null ? parseInt(b.finalgrade) : '')
                            })
                            Toast.fire({
                                type: 'success',
                                title: 'Grades found!'
                            })
                            $('#message_holder').text('Grades found.')
                        }
                    },
                    error: function() {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                        $('#message_holder').text('Unable to load grades.')
                    }
                })
            }

            // $(document).on('click', '.view_students', function() {
            //     $('#modal_1').modal();
            //     var temp_id = $(this).attr('data-id');
            //     // Filter the `all_subject` array to find the matching subject

            //     // If the students array has at least one matching item, display its details

            // });
            // $(document).on('click', '.view_students', function() {
            //     $('#modal_1').modal()
            //     temp_id = $(this).attr('data-id')
            //     var students = all_subject.filter(x => x.schedid == temp_id)
            //     $('#subjectDesc').text(students[0].subjDesc);
            //     $('#subjectCode').text(students[0].subjCode);
            //     $('#collegeLevel').text(students[0].yearDesc);
            //     $('#section').text(students[0].sectionDesc);
            //     $('#sectionNameHeader').text(students[0].subject);
            //     console.log(students, 'asdasdasdas');
            // })



            // $(document).on('click', '.view_permit', function() {
            //     $('#modal_3').modal()
            //     temp_id = $(this).attr('data-id')
            //     var students = all_subject.filter(x => x.schedid == temp_id)
            //     $('#subjectDescPerm').text(students[0].subjDesc);
            //     $('#subjCodepPerm').text(students[0].subjCode);
            //     $('#collegeLevelPerm').text(students[0].yearDesc);
            //     $('#sectionPerm').text(students[0].sectionDesc);
            //     // $('#sectionNameHeader').text(students[0].subjDesc);
            //     console.log(students, 'na oy!');
            // })

            // $(document).on('click', '.page-link', function() {
            //     $('.view_permit').removeAttr('disabled')
            //     $('#subjectDesc').text(students[0].subjDesc);
            //     $('#subjectCode').text(students[0]
            //         .subjectCode); // Assuming subjectCode is part of the response

            //     // Update Level Info
            //     $('#collegeLevel').text(students[0].levelname);

            //     // Update Section Info
            //     $('#section').text(students[0].sectionDesc);

            //     // Update any additional section name headers (if relevant)
            //     $('#sectionNameHeader').text(students[0].subjDesc);
            //     datatable_2_row(students[0].students)

            // })

            $(document).on('click', '#view_pdf', function() {
                var temp_id = $(this).attr('data-id');
                window.open(
                    `/college/teacher/student-list/print/${$('#filter_sy').val()}/${$('#filter_semester').val()}/${temp_id}`,
                    '_blank');

            })

            function datatable_2(students) {
                const groupedByGender = students.reduce((acc, student) => {
                    const gender = student.gender || 'Unspecified';
                    if (!acc[gender]) {
                        acc[gender] = [];
                    }
                    acc[gender].push(student);
                    return acc;
                }, {});

                const genderOrder = ['Male', 'Female', 'Unspecified'];
                const getGenderIndex = gender => genderOrder.indexOf(gender) !== -1 ? genderOrder.indexOf(gender) :
                    genderOrder.length;

                $("#datatable_2").empty();

                function renderTable(view, sortedBy = {
                    column: null,
                    order: 'asc'
                }) {
                    $("#datatable_2").empty();

                    if (view === 'grouped') {
                        const sortedGenders = Object.keys(groupedByGender).sort((a, b) => getGenderIndex(a) -
                            getGenderIndex(b));

                        sortedGenders.forEach(gender => {
                            const backgroundColor = gender.toLowerCase() === 'male' ? '#8ec9fd' : '#fd8ec9';

                            $("#datatable_2").append(
                                `<tr class="gender-header" style="background-color: ${backgroundColor};">
                                    <td colspan="7">
                                        <strong>${gender.toUpperCase()}</strong>
                                        <i class="sort-icon fas fa-sort" style="font-size: 12px; color: #3e3e3e; cursor: pointer;" data-gender="${gender}" data-order="default"></i>
                                    </td>
                                </tr>`
                            );

                            let studentsToDisplay = groupedByGender[gender];

                            if (sortedBy.column === 'student-name') {
                                studentsToDisplay.sort((a, b) => {
                                    const nameA =
                                        `${a.lastname}, ${a.firstname} ${a.middlename} ${a.suffix}`;
                                    const nameB =
                                        `${b.lastname}, ${b.firstname} ${b.middlename} ${b.suffix}`;
                                    if (sortedBy.order === 'asc') {
                                        return nameA.localeCompare(nameB);
                                    } else {
                                        return nameB.localeCompare(nameA);
                                    }
                                });
                            }

                            studentsToDisplay.forEach(student => {
                                $("#datatable_2").append(
                                    `<tr class="student-row" data-gender="${gender}">
                            <td>${student.sid}</td>
                            <td>${student.lastname}, ${student.firstname} ${student.middlename ? student.middlename : ''} ${student.suffix ? student.suffix : ''}</td>
                            <td>${student.levelname}</td>
                            <td>${student.courseabrv}</td>
                            <td>${student.contactno}</td>
                            <td>${student.semail ? student.semail : ''}</td>
                            <td>${student.street ? student.street + (student.barangay ? ', ' + student.barangay : '') + (student.city ? ', ' + student.city : '') + (student.province ? ', ' + student.province : '') : ''}</td>
                        </tr>`
                                );
                            });
                        });
                    } else {
                        if (sortedBy.column === 'student-name') {
                            students.sort((a, b) => {
                                const nameA = `${a.lastname}, ${a.firstname} ${a.middlename} ${a.suffix}`;
                                const nameB = `${b.lastname}, ${b.firstname} ${b.middlename} ${b.suffix}`;
                                if (sortedBy.order === 'asc') {
                                    return nameA.localeCompare(nameB);
                                } else {
                                    return nameB.localeCompare(nameA);
                                }
                            });
                        }

                        students.forEach(student => {
                            $("#datatable_2").append(
                                `<tr class="student-row">
                        <td>${student.sid}</td>
                        <td>${student.lastname}, ${student.firstname} ${student.middlename ? student.middlename : ''} ${student.suffix ? student.suffix : ''}</td>
                        <td>${student.levelname}</td>
                        <td>${student.courseabrv}</td>
                        <td>${student.contactno}</td>
                        <td>${student.semail ? student.semail : ''}</td>
                        <td>${student.street ? student.street + (student.barangay ? ', ' + student.barangay : '') + (student.city ? ', ' + student.city : '') + (student.province ? ', ' + student.province : '') : ''}</td>
                    </tr>`
                            );
                        });
                    }

                    $("#datatable_2_table").DataTable({
                        destroy: true,
                        lengthChange: false,
                        autoWidth: false,
                        ordering: true,
                        columnDefs: [{
                            targets: [0, 2, 5, 6],
                            orderable: true
                        }, {
                            targets: [3, 4],
                            orderable: false
                        }]
                    });
                }

                // Initial render as grouped by gender
                renderTable('grouped');

                // Handle sorting when sort button is clicked
                $(document).on('click', '.sort-icon', function() {
                    let gender = $(this).data('gender'); // Check if gender is clicked
                    let currentOrder = $(this).data('order'); // Get the current order state
                    let newOrder;

                    // Toggle between 'asc', 'desc', and 'default'
                    if (currentOrder === 'default') {
                        newOrder = 'asc';
                        $(this).data('order', 'asc');
                    } else if (currentOrder === 'asc') {
                        newOrder = 'desc';
                        $(this).data('order', 'desc');
                    } else {
                        newOrder = 'default';
                        $(this).data('order', 'default');
                    }

                    // If a gender header is clicked
                    if (gender) {
                        if (newOrder === 'default') {
                            // Re-group by gender
                            renderTable('grouped');
                        } else {
                            // Sort students within the selected gender
                            let sortedStudents = groupedByGender[gender].sort((a, b) => {
                                const nameA =
                                    `${a.lastname}, ${a.firstname} ${a.middlename} ${a.suffix}`;
                                const nameB =
                                    `${b.lastname}, ${b.firstname} ${b.middlename} ${b.suffix}`;
                                return newOrder === 'asc' ? nameA.localeCompare(nameB) : nameB
                                    .localeCompare(nameA);
                            });

                            // Re-render only the rows for the selected gender
                            let genderRow = $(`.sort-icon[data-gender="${gender}"]`).closest('tr');
                            genderRow.nextUntil('tr:has(td[colspan="7"])')
                                .remove(); // Clear existing student rows

                            sortedStudents.forEach(student => {
                                genderRow.after(`
                    <tr class="student-row">
                        <td>${student.sid}</td>
                        <td>${student.lastname}, ${student.firstname} ${student.middlename} ${student.suffix}</td>
                        <td>${student.levelname}</td>
                        <td>${student.courseabrv}</td>
                        <td>${student.contactno}</td>
                        <td>${student.semail ? student.semail : ''}</td>
                        <td>${student.street}, ${student.barangay}, ${student.city}, ${student.province}</td>
                    </tr>
                `);
                            });
                        }
                    } else {
                        // If the global sort is clicked (no gender specified)
                        if (newOrder === 'default') {
                            // Re-group by gender
                            renderTable('grouped');
                        } else {
                            // Sort all students
                            let sortedStudents = students.sort((a, b) => {
                                const nameA =
                                    `${a.lastname}, ${a.firstname} ${a.middlename} ${a.suffix}`;
                                const nameB =
                                    `${b.lastname}, ${b.firstname} ${b.middlename} ${b.suffix}`;
                                return newOrder === 'asc' ? nameA.localeCompare(nameB) : nameB
                                    .localeCompare(nameA);
                            });

                            // Re-render the entire table
                            renderTable('all', {
                                column: 'student-name',
                                order: newOrder
                            });
                        }
                    }
                });


                $(document).on('click', '.student-row', function() {
                    $(this).toggleClass('selected');

                    $(this).css('background-color', $(this).hasClass('selected') ? '#e0e0e0' : '#ffffff');
                });

                $(document).on('click', '#toggleView', function() {
                    let currentView = $(this).data('view');

                    if (currentView === 'grouped') {
                        $(this).data('view', 'all').text('Show Grouped by Gender');
                        renderTable('all');
                    } else {
                        $(this).data('view', 'grouped').text('Show All Students');
                        renderTable('grouped');
                    }
                });
                $('#commonSearchBarStud').on('keyup', function() {
                    var searchTerm = $(this).val().toLowerCase();

                    $("#datatable_2 tr.student-row").each(function() {
                        var rowText = $(this).text().toLowerCase();
                        $(this).toggle(rowText.indexOf(searchTerm) > -1);
                    });
                });
            }



            $(document).on('click', '#view_exampdf', function() {
                window.open('/college/teacher/student/information/pdf?syid=' + $('#filter_sy').val() +
                    '&semid=' + $('#filter_semester').val() + '&schedid=' + temp_id, '_blank');
            })

            $(document).on('click', '#view_permitpdf', function() {
                var temp_id = $(this).attr('data-id');
                window.open(
                    `/college/teacher/student-list/permit/print/${$('#filter_sy').val()}/${$('#filter_semester').val()}/${temp_id}`,
                    '_blank');

            })

            $(document).on('change', '#month_filter', function() {
                var students = all_subject.filter(x => x.schedid == temp_id)
                var permits = all_permits.filter(x => x.schedid == temp_id)

                datatable_3(students[0].students, permits[0].exampermit)

            })

            function datatable_3(students) {
                const groupedByGender = students.reduce((acc, student) => {
                    const gender = student.gender || 'Unspecified';
                    if (!acc[gender]) {
                        acc[gender] = [];
                    }
                    acc[gender].push(student);
                    return acc;
                }, {});

                // Clear the table body
                $("#datatable_3").empty();

                function getGenderIndex(gender) {
                    switch (gender.toLowerCase()) {
                        case 'male':
                            return 1;
                        case 'female':
                            return 2;
                        default:
                            return 3;
                    }
                }

                function renderTable(view, sortedBy = {
                    column: null,
                    order: 'asc'
                }) {
                    $("#datatable_3").empty();

                    $("#datatable_3").append(`
                                <tr>
                                    <th style="width: 10%">Student ID</th>
                                    <th style="width: 15%">Student Name
                                        <i class="sort-icon-permit fas fa-sort"
                                            style="font-size: 15px; color: #3e3e3e; cursor: pointer;"
                                            data-column="student-name" data-order="asc"></i>
                                    </th>
                                    <th style="width: 15%">Academic Level</th>
                                    <th style="width: 10%">Course</th>
                                    <th style="width: 10%">Status</th>
                                </tr>
                            `);

                    if (view === 'grouped') {
                        const sortedGenders = Object.keys(groupedByGender).sort((a, b) => {
                            return getGenderIndex(a) - getGenderIndex(b);
                        });

                        sortedGenders.forEach(gender => {
                            let backgroundColor = gender.toLowerCase() === 'male' ? '#8ec9fd' : '#fd8ec9';

                            $("#datatable_3").append(`
                            <tr class="gender-header" style="background-color: ${backgroundColor};">
                                <td colspan="7">
                                    <strong>${gender.toUpperCase()}</strong>
                                    <i class="sort-icon fas fa-sort" style="font-size: 12px; color: #3e3e3e; cursor: pointer;" data-gender="${gender}" data-order="default"></i>
                                </td>
                            </tr>
                        `);

                            let studentsToDisplay = groupedByGender[gender];

                            if (sortedBy.column === 'student-name') {
                                studentsToDisplay.sort((a, b) => {
                                    const nameA =
                                        `${a.lastname}, ${a.firstname}, ${a.middlename}, ${a.suffix}`;
                                    const nameB =
                                        `${b.lastname}, ${b.firstname}, ${b.middlename}, ${b.suffix}`;
                                    return sortedBy.order === 'asc' ? nameA.localeCompare(nameB) :
                                        nameB.localeCompare(nameA);
                                });
                            }

                            studentsToDisplay.forEach(student => {
                                let examStatus = student.examstatus === 'a' ?
                                    '<span class="badge badge-success">Permitted</span>' :
                                    '<span class="badge badge-danger">Not Permitted</span>';
                                $("#datatable_3").append(`
                        <tr class="student-row">
                            <td>${student.sid}</td>
                                    <td>${student.lastname}, ${student.firstname} ${student.middlename ? student.middlename : ''} ${student.suffix ? student.suffix : ''}</td>
                            <td>${student.levelname}</td>
                            <td>${student.courseabrv}</td>
                            <td>${examStatus}</td>
                        </tr>
                    `);
                            });
                        });
                    } else {
                        if (sortedBy.column === 'student-name') {
                            students.sort((a, b) => {
                                const nameA = `${a.lastname}, ${a.firstname}, ${a.middlename}, ${a.suffix}`;
                                const nameB = `${b.lastname}, ${b.firstname}, ${b.middlename}, ${b.suffix}`;
                                console.log(`Comparing: ${nameA} vs ${nameB} - Order: ${sortedBy.order}`);
                                return sortedBy.order === 'asc' ? nameA.localeCompare(nameB) : nameB
                                    .localeCompare(nameA);
                            });
                        }


                        students.forEach(student => {
                            let examStatus = student.examstatus === 'a' ?
                                '<span class="badge badge-success">Permitted</span>' :
                                '<span class="badge badge-danger">Not Permitted</span>';

                            $("#datatable_3").append(`
                                <tr class="student-row">
                                    <td>${student.sid}</td>
                                    <td>${student.lastname}, ${student.firstname} ${student.middlename ? student.middlename : ''} ${student.suffix ? student.suffix : ''}</td>
                                    <td>${student.levelname}</td>
                                    <td>${student.courseabrv}</td>
                                    <td>${examStatus}</td>
                                </tr>
                            `);
                        });
                    }

                    $("#datatable_3_table").DataTable({
                        destroy: true,
                        lengthChange: false,
                        autoWidth: false,
                        ordering: true,
                        columnDefs: [{
                            targets: [0, 2, 4],
                            orderable: true
                        }, {
                            targets: [1, 3],
                            orderable: false
                        }],
                        dom: '<"top"f>rt<"bottom"ip><"clear">'
                    });
                }

                renderTable('grouped');

                $(document).on('click', '.sort-icon-permit', function() {
                    let column = $(this).data('column');
                    let currentOrder = $(this).data('order');

                    // Handle sorting states: default -> asc -> desc
                    let newOrder;
                    if (currentOrder === 'default') {
                        newOrder = 'asc'; // First click sets to ascending
                    } else if (currentOrder === 'asc') {
                        newOrder = 'desc'; // Second click sets to descending
                    } else {
                        newOrder = 'asc'; // Third click sets back to ascending (or reset to default)
                    }

                    $(this).data('order', newOrder); // Update the icon data with the new order

                    renderTable('all', {
                        column: column,
                        order: newOrder
                    });
                });


                $(document).on('click', '.sort-icon', function() {
                    let gender = $(this).data('gender');

                    let currentOrder = $(this).data('order');
                    let newOrder = currentOrder === 'default' ? 'asc' : currentOrder === 'asc' ? 'desc' :
                        'default';
                    $(this).data('order', newOrder);

                    let studentsToDisplay = groupedByGender[gender];

                    if (newOrder !== 'default') {
                        studentsToDisplay.sort((a, b) => {
                            const nameA =
                                `${a.lastname}, ${a.firstname}, ${a.middlename}, ${a.suffix}`;
                            const nameB =
                                `${b.lastname}, ${b.firstname}, ${b.middlename}, ${b.suffix}`;
                            return newOrder === 'asc' ? nameA.localeCompare(nameB) : nameB
                                .localeCompare(nameA);
                        });
                    }

                    let genderRow = $(`.sort-icon[data-gender="${gender}"]`).closest('tr');
                    genderRow.nextUntil(`tr:has(td[colspan="7"])`)
                        .remove();

                    studentsToDisplay.forEach(student => {
                        let examStatus = student.examstatus === 'a' ?
                            '<span class="badge badge-success">Permitted</span>' :
                            '<span class="badge badge-danger">Not Permitted</span>';

                        genderRow.after(`
                <tr class="student-row">
                    <td>${student.sid}</td>
                    <td>${student.lastname}, ${student.firstname}, ${student.middlename}, ${student.suffix}</td>
                    <td>${student.levelname}</td>
                    <td>${student.courseabrv}</td>
                    <td>${examStatus}</td>
                </tr>
            `);
                    });
                });

                $('#commonSearchBar').on('keyup', function() {
                    var searchTerm = $(this).val().toLowerCase();
                    $("#datatable_3 tr.student-row").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(searchTerm) > -1);
                    });
                });

                $(document).on('click', '#toggleView', function() {
                    let currentView = $(this).data('view');

                    if (currentView === 'grouped') {
                        $(this).data('view', 'all').text('Show Grouped by Gender');
                        renderTable('all');
                    } else {
                        $(this).data('view', 'grouped').text('Show All Students');
                        renderTable('grouped');
                    }
                });
            }



            $(document).on('change', '#term', function() {
                datatable_1()
            })


            function datatable_1(data) {
                var all_data = data;


                console.log(all_data, 'trish')
                $("#datatable_1").DataTable({
                    destroy: true,
                    data: all_data,
                    lengthChange: false,
                    scrollX: true,
                    autoWidth: false,
                    columns: [{
                            "data": 'sectionDesc'
                        },
                        {
                            "data": "subjDesc"
                        },
                        {
                            "data": 'yearDesc'
                        },
                        {
                            "data": 'schedtime'
                        },
                        {
                            "data": 'days'
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
                            'orderable': true,
                            'createdCell': function(td, cellData, rowData) {
                                var text = '<a class="mb-0">' + rowData.yearDesc + '</a>';
                                $(td).html(text).addClass('align-middle');
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': true,
                            'createdCell': function(td, cellData, rowData) {
                                var schedotherclass = rowData.schedotherclass || '';
                                var scheduleTime = rowData.schedtime.length > 0 ? rowData
                                    .schedtime : '';
                                var text =
                                    '<a class="mb-0" style="color:#1583fc; font-style:italic;">' +
                                    schedotherclass + '</a><br>' +
                                    '<span style="font-size: 12px;">' + scheduleTime + '</span>';
                                $(td).html(text).addClass('align-middle');
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
                                var buttons =
                                    '<button class="btn btn-sm btn-secondary mr-1 view_permit" data-id="' +
                                    rowData.schedid +
                                    '"><i class="fas fa-sign-in-alt"></i> Exam Permit</button>';
                                $(td).html(buttons).addClass('text-right align-middle');
                            }
                        }
                    ]
                });
            }
            // Bind the click event for view_students dynamically created buttons
        })
    </script>
@endsection
