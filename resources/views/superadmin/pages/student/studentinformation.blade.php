@php
    if (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (auth()->user()->type == 1 || Session::get('currentPortal') == 1) {
        $extend = 'teacher.layouts.app';
    } elseif (auth()->user()->type == 2 || Session::get('currentPortal') == 2) {
        $extend = 'principalsportal.layouts.app2';
    }
@endphp

@extends($extend)

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }
    </style>
@endsection


@section('content')
    @php
        $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
        $semester = DB::table('semester')->get();
        $gradelevel = DB::table('gradelevel')->where('deleted', 0)->orderBy('sortid')->get();
    @endphp

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
                    <div class="info-box shadow-lg">
                        <div class="info-box-content">
                            <div class="row">
                                <div class="col-md-2  form-group mb-0">
                                    <label for="">School Year</label>
                                    <select class="form-control select2 form-control-sm" id="filter_sy">
                                        @foreach ($sy as $item)
                                            @if ($item->isactive == 1)
                                                <option value="{{ $item->id }}" selected="selected">{{ $item->sydesc }}
                                                </option>
                                            @else
                                                <option value="{{ $item->id }}">{{ $item->sydesc }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Student</label>
                                    <select class="form-control select2" id="filter_student">
                                        <option value="">Select a student</option>
                                    </select>
                                    <span class="text-danger" id="filter_student_error" hidden><small>Select a Student first
                                            to display the Student Information</small></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="card shadow">
                        <div class="card-body box-profile">
                            <div class="text-center" id="image_holder">

                            </div>
                            <h3 class="profile-username text-center" id="student_fullname">Student Name</h3>
                            <p class="text-muted text-center" id="cur_glevel" hidden>Grade Level</p>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>SID</b> <a class="float-right" id="label_sid"></a>
                                </li>
                                <li class="list-group-item">
                                    <b>LRN</b> <a class="float-right" id="label_lrn"></a>
                                </li>
                                <li class="list-group-item" id="view_portal" hidden>

                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card shadow card-primary card-outline card-tabs">
                        <div class="card-header  p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-one-info-tab" data-toggle="pill"
                                        href="#info" role="tab" aria-controls="custom-tabs-one-info"
                                        aria-selected="true">Information</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-enrollment-tab" data-toggle="pill"
                                        href="#enrollment" role="tab" aria-controls="custom-tabs-one-enrollment"
                                        aria-selected="false">Enrollment</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-grade-tab" data-toggle="pill" href="#grade"
                                        role="tab" aria-controls="custom-tabs-one-grade"
                                        aria-selected="false">Grades</a>
                                </li>
                                {{-- <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Schedule</a>
                                      </li> --}}
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-two-tabContent">
                                <div class="tab-pane fade" id="enrollment" role="tabpanel" aria-labelledby="enrollment">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table-hover table table-striped table-sm table-bordered"
                                                id="enrollment_table" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="15%">S.Y.</th>
                                                        <th width="10%">Semester</th>
                                                        <th width="15%">Grade Level</th>
                                                        <th width="10%">Strand</th>
                                                        <th width="60%">Section</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="grade" role="tabpanel" aria-labelledby="grade">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5>SF9 Grades</h5>
                                        </div>
                                    </div>
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="60%">Subject</th>
                                                <th width="10%" class="text-center q1">Q1</th>
                                                <th width="10%" class="text-center q2">Q2</th>
                                                <th width="10%" class="text-center q3">Q3</th>
                                                <th width="10%" class="text-center q4">Q4</th>
                                            </tr>
                                        </thead>
                                        <tbody id="subject_list">

                                        </tbody>
                                    </table>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table-hover table table-striped table-sm table-bordered"
                                                id="transferble_grades_table" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="40%">Subject</th>
                                                        <th width="15%">Q1</th>
                                                        <th width="15%">Q2</th>
                                                        <th width="15%">Q3</th>
                                                        <th width="15%">Q4</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade show active" id="info" role="tabpanel"
                                    aria-labelledby="info">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong><i class="fas fa-book mr-1"></i>Grade Level</strong>
                                            <p class="text-muted" id="grade_level"></p>
                                        </div>
                                        <div class="col-md-3" id="lrn_holder" hidden>
                                            <strong><i class="fas fa-book mr-1"></i>LRN</strong>
                                            <p class="text-muted" id="lrn"></p>
                                        </div>
                                        <div class="col-md-6" hidden id="strand_holder">
                                            <strong><i class="fas fa-book mr-1"></i>Strand</strong>
                                            <p class="text-muted" id="input_strand"></p>
                                        </div>
                                        <div class="col-md-6" hidden id="course_holder">
                                            <strong><i class="fas fa-book mr-1"></i>Course</strong>
                                            <p class="text-muted" id="course"></p>
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <h5>Personal Information</h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>First Name</strong>
                                            <p class="text-muted" id="first_name">--</p>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Middle Name</strong>
                                            <p class="text-muted" id="middle_name">--</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Last Name</strong>
                                            <p class="text-muted" id="last_name">--</p>
                                        </div>
                                        <div class="col-md-1">
                                            <strong>Suffix</strong>
                                            <p class="text-muted" id="suffix">--</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Date of birth</strong>
                                            <p class="text-muted" id="dob">--</p>
                                        </div>
                                        <div class="col-md-3">
                                            <strong><i class="fas fa-book mr-1"></i>Gender</strong>
                                            <p class="text-muted" id="gender">--</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong><i class="fas fa-book mr-1"></i>Nationality</strong>
                                            <p class="text-muted" id="nationality">--</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong><i class="fas fa-book mr-1"></i>Mobile Number</strong>
                                            <p class="text-muted" id="contact_number">--</p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong><i class="fas fa-book mr-1"></i>Email Address</strong>
                                            <p class="text-muted" id="email">--</p>
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="mb-3">Address</h5>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong><i class="fas fa-book mr-1"></i>Street</strong>
                                            <p class="text-muted" id="street">--</p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong><i class="fas fa-book mr-1"></i>Barangay</strong>
                                            <p class="text-muted" id="barangay">--</p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong><i class="fas fa-book mr-1"></i>City/Municipality</strong>
                                            <p class="text-muted" id="city">--</p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong><i class="fas fa-book mr-1"></i>Province</strong>
                                            <p class="text-muted" id="province">--</p>
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="mb-3">Parent / Guardian Information</h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong><i class="fas fa-book mr-1"></i>Father's Full Name</strong>
                                            <p class="text-muted" id="father_name">--</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong><i class="fas fa-book mr-1"></i>Father's Occupation</strong>
                                            <p class="text-muted" id="father_occupation">--</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong><i class="fas fa-book mr-1"></i>Father's Contact Number</strong>
                                            <p class="text-muted" id="father_contact_number">--</p>
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong><i class="fas fa-book mr-1"></i>Mother's Full Maiden Name</strong>
                                            <p class="text-muted" id="mother_name">--</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong><i class="fas fa-book mr-1"></i>Mother's Occupation</strong>
                                            <p class="text-muted" id="mother_occupation">--</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong><i class="fas fa-book mr-1"></i>Mother's Contact Number</strong>
                                            <p class="text-muted" id="mother_contact_number">--</p>
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong><i class="fas fa-book mr-1"></i>Guardian's Full Name</strong>
                                            <p class="text-muted" id="guardian_name">--</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong><i class="fas fa-book mr-1"></i>Relationship to Student</strong>
                                            <p class="text-muted" id="guardian_relation">--</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong><i class="fas fa-book mr-1"></i>Guardian's Contact Number</strong>
                                            <p class="text-muted" id="guardian_contact_number">--</p>
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="row" id="incaseholder">
                                        <div class="col-md-12 ">
                                            <label>In case of emergency ( Recipient for News, Announcement and School
                                                Info)</label>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="icheck-success d-inline">
                                                <input class="form-control" type="radio" id="father" name="incase"
                                                    value="1" disabled>
                                                <label for="father">Father
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="icheck-success d-inline">
                                                <input class="form-control" type="radio" id="mother" name="incase"
                                                    value="2" disabled>
                                                <label for="mother">Mother
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="icheck-success d-inline">
                                                <input class="form-control" type="radio" id="guardian" name="incase"
                                                    value="3" disabled>
                                                <label for="guardian">Guardian
                                                </label>
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
    </section>
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        })

        var studentid = $('#filter_student').val()

        if (studentid === '') {
            $('#filter_student_error').prop('hidden', false);
            Toast.fire({
                type: 'error',
                title: 'No filters have been applied yet',
            });
        }
    </script>

    <script>
        $(document).ready(function() {


            var all_grades = []
            var all_subjects = []

            $(document).on('click', '#custom-tabs-one-grade-tab', function() {
                load_grades()
            })

            $(document).on('click', '.tran_grade', function() {
                var headerid = $(this).attr('data-id')
                transfer_grades(headerid)
            })

            function load_grades() {
                $.ajax({
                    type: 'GET',
                    url: '/superadmin/student/information/grades',
                    data: {
                        studid: $('#filter_student').val(),
                        syid: $('#filter_sy').val(),
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            all_grades = data[0].transferable
                            all_subjects = data[0].subjects
                            sf9grades = data[0].sf9
                            enrollment_datatable()
                            plot_grades(sf9grades)
                        } else {
                            sf9grades = []
                            enrollment_datatable()
                            plot_grades(sf9grades)
                        }
                    }
                })
            }

            function transfer_grades(headerid) {
                $.ajax({
                    type: 'GET',
                    url: '/superadmin/student/information/grades/transfer',
                    data: {
                        studid: $('#filter_student').val(),
                        headerid: headerid
                    },
                    success: function(data) {
                        if (data[0].status == 0) {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })

                        } else {
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                            all_grades = all_grades.filter(x => x.headerid != headerid)
                            enrollment_datatable()
                        }

                    }
                })
            }

            function plot_grades(grades) {

                $('#subject_list').empty()

                if (grades.length == 0) {
                    $('#subject_list').append('<tr><td colspan="5" class="text-center">No data available</td></tr>')
                }

                $.each(grades, function(a, b) {
                    var pad = ''

                    if (b.subjCom != null) {
                        pad = 'pl-5'
                    }
                    var iscon = false

                    var q1 = b.q1 != null ? b.q1 : '';
                    var q2 = b.q2 != null ? b.q2 : '';
                    var q3 = b.q3 != null ? b.q3 : '';
                    var q4 = b.q4 != null ? b.q4 : '';

                    if (b.q1 != null) {
                        q1 = b.q1
                    }


                    if (!iscon) {
                        $('#subject_list').append('<tr><td class="' + pad + '">' + b.subjdesc +
                            '</td><td  class="text-center align-middle input_grades" data-q="1" data-subj="' +
                            b.subjid + '">' + q1 +
                            '</td><td class="text-center align-middle input_grades" data-q="2" data-subj="' +
                            b.subjid + '">' + q2 +
                            '</td><td  class="text-center align-middle input_grades" data-q="3" data-subj="' +
                            b.subjid + '">' + q3 +
                            '</td><td  class="text-center align-middle input_grades" data-q="4" data-subj="' +
                            b.subjid + '">' + q4 + '</td></tr>')
                    } else {
                        $('#subject_list').append('<tr><th class="' + pad + ' bg-secondary">' + b.subjdesc +
                            '</th><th  class="text-center align-middle bg-secondary"></th><th class="text-center align-middle bg-secondary"></th><th  class="text-center align-middle bg-secondary"></th><th  class="text-center align-middle bg-secondary"></th></tr>'
                            )
                    }

                })
            }

            function enrollment_datatable() {

                var temp_subjects = []

                $.each(all_subjects, function(a, b) {
                    var check = all_grades.filter(x => x.subjid == b.subjid)
                    if (check.length > 0) {
                        temp_subjects.push(b)
                    }
                })

                $("#transferble_grades_table").DataTable({
                    destroy: true,
                    data: temp_subjects,
                    lengthChange: false,
                    autoWidth: false,
                    columns: [{
                            "data": "subjdesc"
                        },
                        {
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
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var text = '<a class="mb-0">' + rowData.subjdesc +
                                    '</a><p class="text-muted mb-0"    style="font-size:.7rem">' +
                                    rowData.subjcode + '</p>';
                                $(td)[0].innerHTML = text
                            }
                        },
                        {
                            'targets': 1,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var subj_grade = all_grades.filter(x => x.subjid == rowData
                                    .subjid && x.quarter == 1)
                                if (subj_grade.length > 0) {
                                    if (subj_grade[0].qg != null) {
                                        var text = '<a class="mb-0">' + subj_grade[0].qg + ' - ' +
                                            '<a href="javascript:void(0)" style="font-size:.8rem" class="tran_grade" data-id="' +
                                            subj_grade[0].headerid + '">Transfer<a/>' +
                                            '</a><p class="text-muted mb-0"    style="font-size:.7rem">' +
                                            subj_grade[0].sectionname + '</p>';
                                    } else {
                                        var text = ''
                                    }

                                    $(td)[0].innerHTML = text
                                } else {
                                    $(td).text(null)
                                }

                            }
                        },
                        {
                            'targets': 2,
                            'createdCell': function(td, cellData, rowData, row, col) {

                                var subj_grade = all_grades.filter(x => x.subjid == rowData
                                    .subjid && x.quarter == 2)
                                if (subj_grade.length > 0) {
                                    if (subj_grade[0].qg != null) {
                                        var text = '<a class="mb-0">' + subj_grade[0].qg + ' - ' +
                                            '<a href="javascript:void(0)" style="font-size:.8rem" class="tran_grade" data-id="' +
                                            subj_grade[0].headerid + '">Transfer<a/>' +
                                            '</a><p class="text-muted mb-0"    style="font-size:.7rem">' +
                                            subj_grade[0].sectionname + '</p>';
                                    } else {
                                        var text = ''
                                    }

                                    $(td)[0].innerHTML = text
                                } else {
                                    $(td).text(null)
                                }

                            }
                        },
                        {
                            'targets': 3,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var subj_grade = all_grades.filter(x => x.subjid == rowData
                                    .subjid && x.quarter == 3)
                                if (subj_grade.length > 0) {
                                    if (subj_grade[0].qg != null) {
                                        var text = '<a class="mb-0">' + subj_grade[0].qg + ' - ' +
                                            '<a href="javascript:void(0)" style="font-size:.8rem" class="tran_grade" data-id="' +
                                            subj_grade[0].headerid + '">Transfer<a/>' +
                                            '</a><p class="text-muted mb-0"    style="font-size:.7rem">' +
                                            subj_grade[0].sectionname + '</p>';
                                    } else {
                                        var text = ''
                                    }

                                    $(td)[0].innerHTML = text
                                } else {
                                    $(td).text(null)
                                }
                            }
                        },
                        {
                            'targets': 4,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var subj_grade = all_grades.filter(x => x.subjid == rowData
                                    .subjid && x.quarter == 4)
                                if (subj_grade.length > 0) {
                                    if (subj_grade[0].qg != null) {
                                        var text = '<a class="mb-0">' + subj_grade[0].qg + ' - ' +
                                            '<a href="javascript:void(0)" style="font-size:.8rem" class="tran_grade" data-id="' +
                                            subj_grade[0].headerid + '">Transfer<a/>' +
                                            '</a><p class="text-muted mb-0"    style="font-size:.7rem">' +
                                            subj_grade[0].sectionname + '</p>';
                                    } else {
                                        var text = ''
                                    }
                                    $(td)[0].innerHTML = text
                                } else {
                                    $(td).text(null)
                                }

                            }
                        }
                    ]

                });

                var label_text = $($("#transferble_grades_table_wrapper")[0].children[0])[0].children[0]
                $(label_text)[0].innerHTML = '<h5 class="text-danger mb-0">Transferable Grades</h5>'

            }

        })
    </script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '#custom-tabs-one-enrollment-tab', function() {
                load_enrollment()
            })

            function load_enrollment() {
                $.ajax({
                    type: 'GET',
                    url: '/superadmin/student/information/enrollment',
                    data: {
                        studid: $('#filter_student').val()
                    },
                    success: function(data) {
                        enrollment_datatable(data)
                    }
                })
            }

            function enrollment_datatable(enrollment_info) {
                $("#enrollment_table").DataTable({
                    destroy: true,
                    data: enrollment_info,
                    lengthChange: false,
                    autoWidth: false,
                    columns: [{
                            "data": "sydesc"
                        },
                        {
                            "data": "semid"
                        },
                        {
                            "data": "levelname"
                        },
                        {
                            "data": "strandcode"
                        },
                        {
                            "data": "sectionname"
                        }
                    ],
                    columnDefs: [{
                            'targets': 1,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (rowData.levelid == 14 || rowData.levelid == 15) {
                                    var sem = '1st';
                                    if (rowData.semid == 2) {
                                        var sem = '2nd';
                                    }
                                    $(td).text(sem)
                                } else {
                                    $(td).text('--')
                                }
                            }
                        },
                        {
                            'targets': 3,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (rowData.levelid == 14 || rowData.levelid == 15) {
                                    $(td).text(rowData.strandcode)
                                } else {
                                    $(td).text('--')
                                }
                            }
                        }
                    ]

                });
            }
        })
    </script>

    <script>
        $(document).ready(function() {

            $('.select2').select2()

            var all_students = []
            load_all_student()

            $(document).on('change', '#filter_student', function() {
                $('#filter_student_error').prop('hidden', true);
                $('.nav-link[data-toggle="pill"] , .tab-pane').removeClass('active')
                $('.nav-link[data-toggle="pill"] , .tab-pane').removeClass('show')
                $('#custom-tabs-one-info-tab , #info').addClass('active')
                $('#custom-tabs-one-info-tab , #info').addClass('show')
                load_student_info()
            })

            $(document).on('change', '#filter_sy', function() {

                load_all_student()
            })



            var onerror_url = @json(asset('dist/img/download.png'));
            var picurl = "dist/img/download.png" + "?random=" + new Date().getTime()
            var image = '<img width="100%" src="/' + picurl + '" onerror="this.src=\'' + onerror_url +
                '\'" alt="" class="img-circle img-fluid" >'
            $('#image_holder')[0].innerHTML = image
            var usertype = @json(auth()->user()->type);

            function load_all_student() {
                $.ajax({
                    type: 'GET',
                    url: '/superadmin/student/information/all',
                    data: {
                        syid: $('#filter_sy').val()
                    },
                    success: function(data) {
                        // if(data.length == 0){
                        //       Toast.fire({
                        //             type: 'error',
                        //             title: 'No student found'
                        //       })
                        // }else{
                        //       Toast.fire({
                        //             type: 'success',
                        //             title: data.length+' students found!'
                        //       })
                        // }


                        all_students = data;
                        $("#filter_student").empty()
                        $("#filter_student").append('<option value="">Select a student</option>')
                        $("#filter_student").select2({
                            data: all_students,
                            placeholder: "Select a student",
                        })
                        $('.text-muted').text('--')

                        $('#student_fullname').text('')
                        $('#cur_glevel').text('')

                        $('#label_sid').text('')
                        $('#label_lrn').text('')

                        var onerror_url = @json(asset('dist/img/download.png'));
                        var picurl = "dist/img/download.png" + "?random=" + new Date().getTime()
                        var image = '<img width="100%" src="/' + picurl + '" onerror="this.src=\'' +
                            onerror_url + '\'" alt="" class="img-circle img-fluid" >'
                        $('#image_holder')[0].innerHTML = image

                    }
                })
            }

            function load_student_info() {
                $.ajax({
                    type: 'GET',
                    url: '/superadmin/student/information/info',
                    data: {
                        student: $('#filter_student').val()
                    },
                    success: function(data) {
                        $('#attr').removeAttr('hidden', 'hidden')
                        if (usertype == 17) {
                            if (data[0].userid != null) {
                                $('#view_portal').empty()
                                $('#view_portal').removeAttr('hidden')
                                access = '<a href="/changeUser/' + data[0].userid +
                                    '" class="mb-0">View Portal</a><p class="text-muted mb-0" style="font-size:.7rem">'
                                $('#view_portal').append(access)
                            }
                        }


                        $('#student_fullname').text(data[0].student)
                        $('#cur_glevel').text(data[0].levelname)

                        $('#grade_level').text(data[0].levelname)
                        $('#lrn').text(data[0].lrn)

                        $('#first_name').text(data[0].firstname != null ? data[0].firstname : '--')
                        $('#middle_name').text(data[0].middlename != null ? data[0].middlename : '--')
                        $('#last_name').text(data[0].lastname != null ? data[0].lastname : '--')
                        $('#dob').text(data[0].dob != null ? data[0].dob : '--')
                        $('#nationality').text(data[0].nationalitytext != null ? data[0]
                            .nationalitytext : '--')
                        $('#gender').text(data[0].gender != null ? data[0].gender : '--')
                        $('#suffix').text(data[0].suffix != null ? data[0].suffix : '--')
                        $('#email').text(data[0].semail != null ? data[0].semail : '--')
                        $('#contact_number').text(data[0].contactno != null ? data[0].contactno : '--')

                        $('#street').text(data[0].street != null ? data[0].street : '--')
                        $('#barangay').text(data[0].barangay != null ? data[0].barangay : '--')
                        $('#city').text(data[0].city != null ? data[0].city : '--')
                        $('#province').text(data[0].province != null ? data[0].province : '--')

                        $('#father_name').text(data[0].fathername != null ? data[0].fathername : '--')
                        $('#father_occupation').text(data[0].foccupation != null ? data[0].foccupation :
                            '--')
                        $('#father_contact_number').text(data[0].fcontactno != null ? data[0]
                            .fcontactno : '--')
                        $('#mother_name').text(data[0].mothername != null ? data[0].mothername : '--')
                        $('#mother_occupation').text(data[0].moccupation != null ? data[0].moccupation :
                            '--')
                        $('#mother_contact_number').text(data[0].mcontactno != null ? data[0]
                            .mcontactno : '--')
                        $('#guardian_name').text(data[0].guardianname != null ? data[0].guardianname :
                            '--')
                        $('#guardian_relation').text(data[0].guardianrelation != null ? data[0]
                            .guardianrelation : '--')
                        $('#guardian_contact_number').text(data[0].gcontactno != null ? data[0]
                            .gcontactno : '--')

                        $('#label_sid').text(data[0].sid)
                        $('#label_lrn').text(data[0].lrn)

                        if (data[0].levelid == 14 || data[0].levelid == 15) {
                            $('#strand_holder').removeAttr('hidden')
                            $('#input_strand').text(data[0].strandname != null ? data[0].strandname :
                                '--')
                        }

                        if (data[0].levelid >= 17 && data[0].levelid <= 21) {
                            $('#course_holder').removeAttr('hidden')
                            $('#course').text(data[0].courseDesc != null ? data[0].courseDesc : '--')
                        } else {
                            $('#lrn_holder').removeAttr('hidden')
                        }

                        var onerror_url = @json(asset('dist/img/download.png'));

                        if (data[0].picurl != null) {
                            var picurl = data[0].picurl.replace('jpg', 'png') + "?random=" + new Date()
                                .getTime()
                            var image = '<img width="100%" src="/' + picurl + '" onerror="this.src=\'' +
                                onerror_url + '\'" alt="" class="img-circle img-fluid" >'
                        } else {
                            var image = '<img width="100%" src="' + onerror_url +
                                '" onerror="this.src=\'' + onerror_url +
                                '\'" alt="" class="img-circle img-fluid" >'
                        }


                        $('#image_holder')[0].innerHTML = image

                        if (data[0].ismothernum == 1) {
                            $("#mother").prop("checked", true)
                            $('#mother_contact_number').attr('required')
                        } else if (data[0].isfathernum == 1) {
                            $("#father").prop("checked", true)
                            $('#father_contact_number').attr('required')
                        } else {
                            $("#guardian").prop("checked", true)
                            $('#guardian_contact_number').attr('required')
                        }
                    }
                })
            }



        })
    </script>
@endsection
