@php
    if (Session::get('currentPortal') == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (Session::get('currentPortal') == 3) {
        $extend = 'registrar.layouts.app';
    }
@endphp

@extends($extend)

@section('pagespecificscripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }

        .no-border-col {
            border-left: 0 !important;
            border-right: 0 !important;
        }

        input[type=search] {
            height: calc(1.7em + 2px) !important;
        }
    </style>
@endsection


@section('content')
    @php
        $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
        $semester = DB::table('semester')->orderBy('semester')->get();
        $schoolinfo = DB::table('schoolinfo')->first()->abbreviation;

        //   $gradesetup = DB::table('semester_setup')->where('deleted', 0)->first();

    @endphp


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>College Grade (Teacher)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">College Grade (Teacher)</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <div class="modal fade" id="modal_7" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Grades</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-12">
                            <span class="badge badge-default">Unsubmitted</span>
                            <span class="badge badge-success">Submitted</span>
                            <span class="badge badge-primary">Program Head Approved</span>
                            <span class="badge badge-secondary">Dean Approved</span>
                            <span class="badge badge-info">Posted</span>
                            <span class="badge badge-warning">Pending</span>
                            <span class="badge badge-warning">INC</span>
                            <span class="badge badge-danger">Dropped</span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <table class="table table-sm table-striped mb-0" style="font-size:.9rem">
                                <tr>
                                    <th id="subject" width="70%"></th>
                                    <th id="section" width="30%" hidden></th>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-12 table-responsive tableFixHead" style="height: 420px;">
                            <table class="table table-sm table-striped table-bordered mb-0 table-head-fixed table-hover"
                                style="font-size:.8rem" width="100%">
                                <thead>
                                    <tr>

                                        <th width="3%" class="text-center">#</th>
                                        <th width="29%">Student</th>
                                        <th width="20%">Course</th>
                                        <th width="8%" class="text-center term_holder" data-term="1">Prelim</th>
                                        <th width="8%" class="text-center term_holder" data-term="2">Midterm</th>
                                        <th width="8%" class="text-center term_holder" data-term="3">PreFi</th>
                                        <th width="8%" class="text-center term_holder" data-term="4">Final Term</th>
                                        <th width="8%" class="text-center term_holder" data-term="5">Final Grade</th>
                                        <th width="10%" class="text-center term_holder" data-term="6">Remarks</th>

                                    </tr>
                                </thead>
                                <tbody id="student_list_grades">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <button id="grade_appove" class="btn btn-primary btn-sm">Approve Grades</button>
                            <button id="grade_pending" class="btn btn-warning btn-sm">Pending Grades</button>
                            <button id="grade_posting" class="btn btn-info btn-sm">Post Grades</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-1 pb-1" style="font-size:.7rem">
                    <i id="message_holder"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_8" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title">Grade Submission</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body " style="font-size:.9rem">
                    <div class="row">
                        <div class="col-md-6 form-group mb-0">
                            <select name="quarter_select" id="quarter_select"
                                class="form-control form-control-sm select2">
                                <option value="">Select Term</option>
                                <option value="1">Prelim</option>
                                <option value="2">Midterm</option>
                                <option value="3">PreFinal</option>
                                <option value="4">Final</option>
                            </select>
                            <small class="text-danger"><i>Select a term to view and submit grades.</i></small>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-primary float-right btn-sm" id="process_button">Approve</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-responsive tableFixHead" style="height: 422px;">
                            <table class="table table-sm table-striped table-bordered mb-0 table-head-fixed"
                                style="font-size:.9rem" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%"><input type="checkbox" disabled checked="checked"
                                                class="select_all"> </th>
                                        <th width="20%">SID</th>
                                        <th width="60%">Student</th>
                                        <th width="15%" class="text-centerv">Grade</th>
                                    </tr>
                                </thead>
                                <tbody id="datatable_8">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                                <option value="{{ $item->id }}" selected="selected">
                                                    {{ $item->sydesc }}</option>
                                            @else
                                                <option value="{{ $item->id }}">{{ $item->sydesc }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2  form-group mb-0">
                                    <label for="">Semester</label>
                                    <select class="form-control select2 form-control-sm" id="filter_sem">
                                        @foreach ($semester as $item)
                                            @if ($item->isactive == 1)
                                                <option value="{{ $item->id }}" selected="selected">
                                                    {{ $item->semester }}</option>
                                            @else
                                                <option value="{{ $item->id }}">{{ $item->semester }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5  form-group mb-0">
                                    <label for="">Teacher</label>
                                    <select class="form-control select2 form-control-sm" id="filter_teacher">
                                        <option value="">Select Teacher</option>
                                    </select>
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
                                <div class="col-md-12">
                                    <i>
                                        <p class="mb-0 text-danger" style="font-size:.8rem !important">Status: <span
                                                id="status_holder"></span></p>
                                    </i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Grades Status (Subject)</label>
                                    <select name="" class="form-control form-control-sm select2"
                                        id="filter_status_by_subject">
                                        <option value="uns">Unsubmitted</option>
                                        <option value="sub">Submitted</option>
                                        <option value="app">Program Head Approved</option>
                                        <option value="deanapp">Dean Approved</option>
                                        <option value="posted">Posted</option>
                                        <option value="pen">Pending</option>
                                        <option value="inc">INC</option>
                                        <option value="drop">Dropped</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <table class="table-hover table table-striped table-sm table-bordered"
                                        id="teacher_scheed" width="100%" style="font-size:.9rem !important">
                                        <thead>
                                            <tr>
                                                <th width="40%">Section</th>
                                                <th width="50%">Subject Description</th>
                                                <th width="10%"></th>
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

@section('footerjavascript')
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>


    <script>
        var syid = $('#filter_sy').val();
        var semid = $('#filter_sem').val();
        var gradessetup = [];
        $.ajax({
            url: '/semester-setup/getactive-setup-dean',
            type: 'GET',
            data: {
                syid: syid,
                semid: semid,
            },
            async: false,
            success: function(response) {
                gradessetup = response;
            }
        });
        console.log(gradessetup, 'gradessetup daw ni hehehe');
        var displaycolspan = 9

        if (gradessetup.length != 0) {
            if (gradessetup.prelim == 0) {
                displaycolspan - 1
                $('.term_holder[data-term=1]').remove();
                $('#quarter_select option[value="1"]').remove()
            }
            if (gradessetup.midterm == 0) {
                displaycolspan - 1
                $('.term_holder[data-term=2]').remove();
                $('#quarter_select option[value="2"]').remove()
            }
            if (gradessetup.prefi == 0) {
                displaycolspan - 1
                $('.term_holder[data-term=3]').remove();
                $('#quarter_select option[value="3"]').remove()
            }
            if (gradessetup.final == 0) {
                displaycolspan - 1
                $('.term_holder[data-term=4]').remove();
                $('#quarter_select option[value="4"]').remove()
            }
            $('#quarter_select').select2({
                placeholder: "Select Term"
            })
        } else {
            $('.term_holder[data-term=1]').remove();
            $('.term_holder[data-term=2]').remove();
            $('.term_holder[data-term=3]').remove();
            $('.term_holder[data-term=4]').remove();
            $('#quarter_select').empty()
            $('#quarter_select').append('<option value="">Select Term</option>')
            $('#quarter_select').select2({
                placeholder: "Select Term"
            })
            displaycolspan = 5
        }
    </script>



    <script>
        $(document).ready(function() {


            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })

            var utype = @json(Session::get('currentPortal'));

            var school = @json(strtoupper($schoolinfo));

            // $('.select2').select2()

            $("#filter_teacher").select2({
                data: [],
                placeholder: "Select Section",
            })

            $("#filter_status_by_subject").select2({
                data: [],
                placeholder: "Select Status",
            })

            $("#filter_sy").select2({
                data: [],
                placeholder: "Select Status",
            })

            $("#filter_sem").select2({
                data: [],
                placeholder: "Select Status",
            })

            var all_teacher_sched = []
            var all_teacher = []
            teacher_sched()
            teachers()

            // var teacherid = $('#filter_teacher').val()

            $(document).on('change', '#filter_teacher', function() {
                // if($(this).val() == ""){
                //       all_teacher_sched = []
                //       teacher_sched()
                // }else{
                //       all_teacher_sched = []
                //       teacher_sched()
                //       subjects()
                // }
                // teacher_sched()
                subjects()

            })

            $(document).on('change', '#filter_sy', function() {
                console.log($('#filter_sy').val(), 'sy');
                // teachers()
                // teacher_sched()
                subjects()
                teachers()
            })

            $(document).on('change', '#filter_sem', function() {
                console.log($('#filter_sem').val(), 'semid');
                // teachers()
                // teacher_sched()
                subjects()

            })

            $(document).on('change', '#filter_status_by_subject', function() {
                // console.log($('#filter_sy').val(), 'sy');
                // console.log($('#filter_sem').val(), 'semid');

                // teacher_sched()
                subjects()
            })

            $(document).on('click', '#grade_appove', function() {
                $('.select').attr('disabled', 'disabled')
                $('#quarter_select').val("").change()
                $('#process_button').text('Approve')
                $('#process_button').removeClass('btn-info')
                $('#process_button').removeClass('btn-warning')
                $('#process_button').addClass('btn-primary')
                $('#process_button').attr('data-id', 7)
                $('#modal_8').modal()
                $('.select').prop('checked', true)
                button_stat = 7
            })

            $(document).on('click', '#grade_pending', function() {
                $('#quarter_select').val("").change()
                $('.select').attr('disabled', 'disabled')
                $('#process_button').text('Pending')
                $('#process_button').removeClass('btn-info')
                $('#process_button').removeClass('btn-primary')
                $('#process_button').addClass('btn-warning')
                $('#modal_8').modal()
                $('#process_button').attr('data-id', 3)
                $('.select').prop('checked', true)
                button_stat = 3
            })

            $(document).on('click', '#grade_posting', function() {
                $('#quarter_select').val("").change()
                $('.select').attr('disabled', 'disabled')
                $('#process_button').text('Post')
                $('#process_button').removeClass('btn-primary')
                $('#process_button').removeClass('btn-warning')
                $('#process_button').addClass('btn-info')
                $('#modal_8').modal()
                $('#process_button').attr('data-id', 4)
                $('.select').prop('checked', true)
                button_stat = 4
            })

            $(document).on('click', '#process_button', function() {
                if ($(this).attr('data-id') == 7) {
                    approve_grade()
                } else if ($(this).attr('data-id') == 3) {
                    pending_grade()
                } else if ($(this).attr('data-id') == 4) {
                    post_grade()
                }

            })

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
                } else if (term == "1") {
                    selected_term = 1;
                } else if (term == "2") {
                    selected_term = 2;
                } else if (term == "3") {
                    selected_term = 3;
                } else if (term == "4") {
                    selected_term = 4;
                }

                $('#submit_selected_grade').removeAttr('disabled')
                $('.select_all').removeAttr('disabled')
                $('.select').removeAttr('disabled')

                $('.grade_td[data-term="' + term + '"]').each(function(a, b) {
                    var current
                    if (utype == 14) {
                        if ($(this).attr('data-status') == undefined) {
                            $('.select[data-studid="' + $(this).attr('data-studid') + '"]').attr(
                                'disabled', 'disabled')
                        } else if (button_stat == 7 && ($(this).attr('data-status') != 7 && $(this)
                                .attr('data-status') != 1)) {
                            $('.select[data-studid="' + $(this).attr('data-studid') + '"]').attr(
                                'disabled', 'disabled')
                        } else if ($(this).attr('data-status') == 3) {
                            $('.select[data-studid="' + $(this).attr('data-studid') + '"]').attr(
                                'disabled', 'disabled')
                        } else if ($(this).attr('data-status') == 9 || $(this).attr(
                                'data-status') == 8) {
                            // $('.select[data-studid="'+$(this).attr('data-studid')+'"]').attr('disabled','disabled')
                        }
                    } else {
                        if ($(this).attr('data-status') == undefined) {
                            $('.select[data-studid="' + $(this).attr('data-studid') + '"]').attr(
                                'disabled', 'disabled')
                        } else if ($(this).attr('data-status') == 3 || $(this).attr(
                                'data-status') == 0) {
                            $('.select[data-studid="' + $(this).attr('data-studid') + '"]').attr(
                                'disabled', 'disabled')
                        } else if ($(this).attr('data-status') == 9 || $(this).attr(
                                'data-status') == 8 || $(this).attr('data-status') == 2) {
                            // $('.select[data-studid="'+$(this).attr('data-studid')+'"]').attr('disabled','disabled')
                        }

                        if (button_stat == 7 && $(this).attr('data-status') == 7) {
                            $('.select[data-studid="' + $(this).attr('data-studid') + '"]').attr(
                                'disabled', 'disabled')
                        }
                    }

                    $('.grade_submission_student[data-studid="' + $(this).attr('data-studid') +
                        '"]').text($(this).text())
                    $('.select[data-studid="' + $(this).attr('data-studid') + '"]').attr('data-id',
                        $(this).attr('data-id'))
                })


            })

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function approve_grade() {

                var selected = []
                var term = selected_term
                var grade_term = ''

                if (term == 1) {
                    term = 'prelemstatus'
                    grade_term = 'prelemgrade'
                } else if (term == 2) {
                    term = 'midtermstatus'
                    grade_term = 'midtermgrade'
                } else if (term == 3) {
                    term = 'prefistatus'
                    grade_term = 'prefigrade'
                } else if (term == 4) {
                    term = 'finalstatus'
                    grade_term = 'finalgrade'
                }

                $('.select').each(function() {
                    if ($(this).prop('checked') == true && $(this).attr('disabled') == undefined && $(this)
                        .attr('data-id') != undefined) {
                        selected.push($(this).attr('data-id'))
                    }
                })

                if (selected.length == 0) {
                    Toast.fire({
                        type: 'info',
                        title: 'No student selected'
                    })
                    return false
                }

                $.ajax({
                    type: 'POST',
                    url: '/college/grades/approve/ph',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_sem').val(),
                        term: term,
                        selected: selected,
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Grades Approved!'
                            })
                            student_grades(selected_subj, selected_schedid)
                            subjects()
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

            function pending_grade() {

                var selected = []
                var term = selected_term
                var grade_term = ''

                if (term == 1) {
                    term = 'prelemstatus'
                    grade_term = 'prelemgrade'
                } else if (term == 2) {
                    term = 'midtermstatus'
                    grade_term = 'midtermgrade'
                } else if (term == 3) {
                    term = 'prefistatus'
                    grade_term = 'prefigrade'
                } else if (term == 4) {
                    term = 'finalstatus'
                    grade_term = 'finalgrade'
                }

                $('.select').each(function() {
                    if ($(this).prop('checked') == true && $(this).attr('disabled') == undefined && $(this)
                        .attr('data-id') != undefined) {
                        selected.push($(this).attr('data-id'))
                    }
                })

                if (selected.length == 0) {
                    Toast.fire({
                        type: 'info',
                        title: 'No student selected'
                    })
                    return false
                }



                $.ajax({
                    type: 'POST',
                    url: '/college/grades/pending/ph',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_sem').val(),
                        term: term,
                        selected: selected,
                        _token: '{{ csrf_token() }}' // Add the CSRF token here
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Added to pending!'
                            });
                            student_grades(selected_subj, selected_schedid);
                            subjects();
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            });
                        }
                    },
                    error: function() {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        });
                    }
                });


            }

            function post_grade() {

                var selected = []
                var term = selected_term
                var grade_term = ''

                if (term == 1) {
                    term = 'prelemstatus'
                    grade_term = 'prelemgrade'
                } else if (term == 2) {
                    term = 'midtermstatus'
                    grade_term = 'midtermgrade'
                } else if (term == 3) {
                    term = 'prefistatus'
                    grade_term = 'prefigrade'
                } else if (term == 4) {
                    term = 'finalstatus'
                    grade_term = 'finalgrade'
                }

                $('.select').each(function() {
                    if ($(this).prop('checked') == true && $(this).attr('disabled') == undefined && $(this)
                        .attr('data-id') != undefined) {
                        selected.push($(this).attr('data-id'))
                    }
                })

                if (selected.length == 0) {
                    Toast.fire({
                        type: 'info',
                        title: 'No student selected'
                    })
                    return false
                }



                $.ajax({
                    type: 'POST',
                    url: '/college/grades/post',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_sem').val(),
                        term: term,
                        selected: selected,
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Grades Posted!'
                            })
                            student_grades(selected_subj, selected_schedid)
                            subjects()
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

            var selected_subj = null
            var selected_schedid = null

            $(document).on('click', '.view_grade', function() {
                var subjid = $(this).attr('data-id')
                var schedid = $(this).attr('data-schedid')
                selected_subj = subjid
                selected_schedid = schedid
                var temp_subj_info = all_teacher_sched.filter(x => x.subjectID == subjid)
                $('#subject')[0].innerHTML = '<a class="mb-0">' + temp_subj_info[0].subjDesc +
                    '</a><p class="text-muted mb-0" style="font-size:.7rem">' + temp_subj_info[0].subjCode +
                    '</p>'
                student_grades(subjid, schedid)
                $('#modal_7').modal()
            })

            function student_grades(subjid, schedid) {
                $('#status_holder').text('fetching')
                $('#student_list_grades').empty()
                $('#datatable_8').empty()
                $.ajax({
                    type: 'GET',
                    url: '/college/grades/monitoring/teacher/subject/grade',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_sem').val(),
                        teacherid: $('#filter_teacher').val(),
                        subjid: subjid,
                        schedid: schedid
                    },
                    success: function(data) {

                        var temp_students = data[0].students
                        var grades = data[0].grades
                        var female = 0;
                        var male = 0;
                        var count = 0;
                        $('#student_list_grades').empty()
                        $.each(temp_students, function(a, b) {
                            var include = false
                            var check = grades.filter(x => x.studid == b.sid)
                            if (check.length > 0) {
                                var check = check[0]
                                if ($('#filter_status_by_subject').val() == "drop") {
                                    if (check.prelemstatus == 9 || check.midtermstatus == 9 ||
                                        check.prefistatus == 9 || check.finalstatus == 9) {
                                        include = true
                                    }
                                } else if ($('#filter_status_by_subject').val() == "sub") {
                                    if (check.prelemstatus == 1 || check.midtermstatus == 1 ||
                                        check.prefistatus == 1 || check.finalstatus == 1) {
                                        include = true
                                    }
                                } else if ($('#filter_status_by_subject').val() == "posted") {
                                    if (check.prelemstatus == 4 || check.midtermstatus == 4 ||
                                        check.prefistatus == 4 || check.finalstatus == 4) {
                                        include = true
                                    }
                                } else if ($('#filter_status_by_subject').val() == "deanapp") {
                                    if (check.prelemstatus == 2 || check.midtermstatus == 2 ||
                                        check.prefistatus == 2 || check.finalstatus == 2) {
                                        include = true
                                    }
                                } else if ($('#filter_status_by_subject').val() == "pen") {
                                    if (check.prelemstatus == 3 || check.midtermstatus == 3 ||
                                        check.prefistatus == 3 || check.finalstatus == 3) {
                                        include = true
                                    }
                                } else if ($('#filter_status_by_subject').val() == "app") {
                                    if (check.prelemstatus == 7 || check.midtermstatus == 7 ||
                                        check.prefistatus == 7 || check.finalstatus == 7) {
                                        include = true
                                    }
                                } else if ($('#filter_status_by_subject').val() == "uns") {
                                    if (check.prelemstatus == null && check.midtermstatus ==
                                        null && check.prefistatus == null && check
                                        .finalstatus == null) {
                                        include = true
                                    }
                                } else if ($('#filter_status_by_subject').val() == "inc") {
                                    if (check.prelemstatus == 8 || check.midtermstatus == 8 ||
                                        check.prefistatus == 8 || check.finalstatus == 8) {
                                        include = true
                                    }
                                }

                            } else {
                                if ($('#filter_status_by_subject').val() == "uns") {
                                    include = true
                                }
                            }


                            if (include) {



                                var colspan = 7

                                if (male == 0 && b.gender == 'MALE') {
                                    $('#student_list_grades').append(
                                        '<tr class="bg-secondary"><th colspan="' +
                                        displaycolspan + '">MALE</th></tr>')
                                    $('#datatable_4').append(
                                        '<tr class="bg-secondary"><th colspan="4">MALE</th></tr>'
                                    )
                                    male = 1
                                    count = 0
                                } else if (female == 0 && b.gender == 'FEMALE') {
                                    $('#student_list_grades').append(
                                        '<tr class="bg-secondary"><th colspan="' +
                                        displaycolspan + '">FEMALE</th></tr>')
                                    $('#datatable_4').append(
                                        '<tr class="bg-primary"><th colspan="4">FEMALE</th></tr>'
                                    )
                                    female = 1
                                    count = 0
                                }

                                var pid = b.pid;
                                var sectionid = b.sectionid;

                                count += 1

                                var gradelevel = null
                                if (b.levelid == 17) {
                                    gradelevel = 1
                                } else if (b.levelid == 18) {
                                    gradelevel = 2
                                } else if (b.levelid == 19) {
                                    gradelevel = 3
                                } else if (b.levelid == 20) {
                                    gradelevel = 4
                                } else if (b.levelid == 21) {
                                    gradelevel = 5
                                } else if (b.levelid == 22) {
                                    gradelevel = 1
                                } else if (b.levelid == 23) {
                                    gradelevel = 2
                                } else if (b.levelid == 24) {
                                    gradelevel = 3
                                } else if (b.levelid == 25) {
                                    gradelevel = 4
                                }


                                var display_text = '<tr><td class="text-center">' + count +
                                    '</td><td>' + b.studentname + '</td><td>' + b.courseabrv +
                                    ' ' + gradelevel + '</td>'

                                if (gradessetup != null) {

                                    if (gradessetup.prelim == 1) {
                                        display_text += '<td data-studid="' + b.sid +
                                            '" data-course="' + b.courseid + '" data-pid="' +
                                            pid + '" data-section="' + sectionid +
                                            '" class="grade_td input_grades" data-term="1">'
                                    }
                                    if (gradessetup.midterm == 1) {
                                        display_text += '<td data-studid="' + b.sid +
                                            '" data-course="' + b.courseid + '" data-pid="' +
                                            pid + '" data-section="' + sectionid +
                                            '" class="grade_td input_grades" data-term="2">'
                                    }
                                    if (gradessetup.prefi == 1) {
                                        display_text += '<td data-studid="' + b.sid +
                                            '" data-course="' + b.courseid + '" data-pid="' +
                                            pid + '" data-section="' + sectionid +
                                            '" class="grade_td input_grades" data-term="3">'
                                    }
                                    if (gradessetup.final == 1) {
                                        display_text += '<td data-studid="' + b.sid +
                                            '" data-course="' + b.courseid + '" data-pid="' +
                                            pid + '" data-section="' + sectionid +
                                            '" class="grade_td input_grades" data-term="4">'
                                    }

                                    display_text += '<td data-studid="' + b.sid +
                                        '" data-course="' + b.courseid + '" data-pid="' + pid +
                                        '" data-section="' + sectionid +
                                        '" class="grade_td input_grades" data-term="5">'


                                    display_text += '<td data-studid="' + b.sid +
                                        '" data-course="' + b.courseid + '" data-pid="' + pid +
                                        '" data-section="' + sectionid +
                                        '" class="grade_td input_grades" data-term="6">'

                                } else {
                                    display_text += '<td></td><td></td></tr>'
                                }

                                $('#student_list_grades').append(display_text)

                            }

                            $('#datatable_8').append(
                                '<tr><td><input disabled checked="checked" type="checkbox" class="select" data-studid="' +
                                b.sid + '"></td><td>' + b.sid + '</td><td>' + b
                                .studentname + '</td><td data-studid="' + b.sid +
                                '" class="grade_submission_student text-center"></td></tr>')

                        })

                        plot_subject_grades(data[0].grades)

                        function updatePassFailBanner() {
                            let passedCount = 0;
                            let failedCount = 0;
                            let incCount = 0;

                            $('#student_list_grades tr').each(function() {
                                const remarkElement = $(this).find('td[data-term="6"]');
                                const remark = remarkElement.text().trim();
                                if (remark === 'PASSED' || remark === 'Passed') {
                                    remarkElement.addClass('bg-success').removeClass(
                                        'bg-danger bg-warning bg-info');
                                    passedCount++;
                                } else if (remark === 'FAILED' || remark === 'Failed') {
                                    remarkElement.addClass('bg-danger').removeClass(
                                        'bg-success bg-warning bg-info');
                                    failedCount++;
                                } else if (remark === 'INC' || remark === 'Inc') {
                                    remarkElement.addClass('bg-warning').removeClass(
                                        'bg-success bg-danger bg-info');
                                    incCount++;
                                }
                            });

                            $('#passedCount').text(passedCount);
                            $('#failedCount').text(failedCount);
                            $('#incCount').text(incCount);
                        }

                        updatePassFailBanner();
                        setTimeout(updatePassFailBanner, 500);
                    }
                })
            }

            // function student_grades(subjid, schedid) {
            //     $('#status_holder').text('fetching')
            //     $('#student_list_grades').empty()
            //     $('#datatable_8').empty()
            //     $.ajax({
            //         type: 'GET',
            //         url: '/college/grades/monitoring/teacher/subject/grade',
            //         data: {
            //             syid: $('#filter_sy').val(),
            //             semid: $('#filter_sem').val(),
            //             teacherid: $('#filter_teacher').val(),
            //             subjid: subjid,
            //             schedid: schedid
            //         },
            //         success: function(data) {

            //             var temp_students = data[0].students
            //             var grades = data[0].grades
            //             var female = 0;
            //             var male = 0;
            //             var count = 0;
            //             $('#student_list_grades').empty()
            //             $.each(temp_students, function(a, b) {
            //                 var include = false
            //                 var check = grades.filter(x => x.studid == b.sid)
            //                 if (check.length > 0) {
            //                     var check = check[0]
            //                     if ($('#filter_status_by_subject').val() == "drop") {
            //                         if (check.prelemstatus == 9 || check.midtermstatus == 9 ||
            //                             check.prefistatus == 9 || check.finalstatus == 9) {
            //                             include = true
            //                         }
            //                     } else if ($('#filter_status_by_subject').val() == "sub") {
            //                         if (check.prelemstatus == 1 || check.midtermstatus == 1 ||
            //                             check.prefistatus == 1 || check.finalstatus == 1) {
            //                             include = true
            //                         }
            //                     } else if ($('#filter_status_by_subject').val() == "posted") {
            //                         if (check.prelemstatus == 4 || check.midtermstatus == 4 ||
            //                             check.prefistatus == 4 || check.finalstatus == 4) {
            //                             include = true
            //                         }
            //                     } else if ($('#filter_status_by_subject').val() == "deanapp") {
            //                         if (check.prelemstatus == 2 || check.midtermstatus == 2 ||
            //                             check.prefistatus == 2 || check.finalstatus == 2) {
            //                             include = true
            //                         }
            //                     } else if ($('#filter_status_by_subject').val() == "pen") {
            //                         if (check.prelemstatus == 3 || check.midtermstatus == 3 ||
            //                             check.prefistatus == 3 || check.finalstatus == 3) {
            //                             include = true
            //                         }
            //                     } else if ($('#filter_status_by_subject').val() == "app") {
            //                         if (check.prelemstatus == 7 || check.midtermstatus == 7 ||
            //                             check.prefistatus == 7 || check.finalstatus == 7) {
            //                             include = true
            //                         }
            //                     } else if ($('#filter_status_by_subject').val() == "uns") {
            //                         if (check.prelemstatus == null || check.midtermstatus ==
            //                             null || check.prefistatus == null || check
            //                             .finalstatus == null) {
            //                             include = true
            //                         }
            //                     } else if ($('#filter_status_by_subject').val() == "inc") {
            //                         if (check.prelemstatus == 8 || check.midtermstatus == 8 ||
            //                             check.prefistatus == 8 || check.finalstatus == 8) {
            //                             include = true
            //                         }
            //                     }

            //                 } else {
            //                     if ($('#filter_status_by_subject').val() == "uns") {
            //                         include = true
            //                     }
            //                 }


            //                 if (include) {



            //                     var colspan = 7

            //                     if (male == 0 && b.gender == 'MALE') {
            //                         $('#student_list_grades').append(
            //                             '<tr class="bg-primary"><th colspan="' +
            //                             displaycolspan + '">MALE</th></tr>')
            //                         $('#datatable_4').append(
            //                             '<tr class="bg-primary"><th colspan="4">MALE</th></tr>'
            //                         )
            //                         male = 1
            //                         count = 0
            //                     } else if (female == 0 && b.gender == 'FEMALE') {
            //                         $('#student_list_grades').append(
            //                             '<tr class="bg-primary"><th colspan="' +
            //                             displaycolspan + '">FEMALE</th></tr>')
            //                         $('#datatable_4').append(
            //                             '<tr class="bg-primary"><th colspan="4">FEMALE</th></tr>'
            //                         )
            //                         female = 1
            //                         count = 0
            //                     }

            //                     var pid = b.pid;
            //                     var sectionid = b.sectionid;

            //                     count += 1

            //                     var gradelevel = null
            //                     if (b.levelid == 17) {
            //                         gradelevel = 1
            //                     } else if (b.levelid == 18) {
            //                         gradelevel = 2
            //                     } else if (b.levelid == 19) {
            //                         gradelevel = 3
            //                     } else if (b.levelid == 20) {
            //                         gradelevel = 4
            //                     } else if (b.levelid == 21) {
            //                         gradelevel = 5
            //                     }

            //                     var display_text = '<tr><td class="text-center">' + count +
            //                         '</td><td>' + b.studentname + '</td><td>' + b.courseabrv +
            //                         ' ' + gradelevel + '</td>'

            //                     if (gradessetup != null) {

            //                         if (gradessetup.prelim == 1) {
            //                             display_text += '<td data-studid="' + b.sid +
            //                                 '" data-course="' + b.courseid + '" data-pid="' +
            //                                 pid + '" data-section="' + sectionid +
            //                                 '" class="grade_td input_grades" data-term="1">'
            //                         }
            //                         if (gradessetup.midterm == 1) {
            //                             display_text += '<td data-studid="' + b.sid +
            //                                 '" data-course="' + b.courseid + '" data-pid="' +
            //                                 pid + '" data-section="' + sectionid +
            //                                 '" class="grade_td input_grades" data-term="2">'
            //                         }
            //                         if (gradessetup.prefi == 1) {
            //                             display_text += '<td data-studid="' + b.sid +
            //                                 '" data-course="' + b.courseid + '" data-pid="' +
            //                                 pid + '" data-section="' + sectionid +
            //                                 '" class="grade_td input_grades" data-term="3">'
            //                         }
            //                         if (gradessetup.final == 1) {
            //                             display_text += '<td data-studid="' + b.sid +
            //                                 '" data-course="' + b.courseid + '" data-pid="' +
            //                                 pid + '" data-section="' + sectionid +
            //                                 '" class="grade_td input_grades" data-term="4">'
            //                         }

            //                         display_text += '<td data-studid="' + b.sid +
            //                             '" data-course="' + b.courseid + '" data-pid="' + pid +
            //                             '" data-section="' + sectionid +
            //                             '" class="grade_td input_grades" data-term="5">'


            //                         display_text += '<td data-studid="' + b.sid +
            //                             '" data-course="' + b.courseid + '" data-pid="' + pid +
            //                             '" data-section="' + sectionid +
            //                             '" class="grade_td input_grades" data-term="6">'

            //                     } else {
            //                         display_text += '<td></td><td></td></tr>'
            //                     }

            //                     $('#student_list_grades').append(display_text)

            //                 }

            //                 $('#datatable_8').append(
            //                     '<tr><td><input disabled checked="checked" type="checkbox" class="select" data-studid="' +
            //                     b.sid + '"></td><td>' + b.sid + '</td><td>' + b
            //                     .studentname + '</td><td data-studid="' + b.sid +
            //                     '" class="grade_submission_student text-center"></td></tr>')

            //             })

            //             plot_subject_grades(data[0].grades)
            //         }
            //     })
            // }

            // function plot_subject_grades(data) {
            //     // console.log(data, 'wewew')
            //     $.each(data, function(a, b) {

            //         var q1status = 'input_grades'
            //         var q2status = 'input_grades'
            //         var q3status = 'input_grades'
            //         var q4status = 'input_grades'

            //         var validStatuses = [1, 2, 4];

            //         if (validStatuses.includes(b.prelemstatus)) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').text(b
            //                 .prelemgrade != null ? b.prelemgrade : '');
            //         } else {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').text('');
            //         }

            //         if (validStatuses.includes(b.midtermstatus)) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').text(b
            //                 .midtermgrade != null ? b.midtermgrade : '');
            //         } else {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').text('');
            //         }

            //         if (validStatuses.includes(b.prefistatus)) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').text(b
            //                 .prefigrade != null ? b.prefigrade : '');
            //         } else {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').text('');
            //         }

            //         if (validStatuses.includes(b.finalstatus)) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').text(b
            //                 .finalgrade != null ? b.finalgrade : '');
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').text(b
            //                 .finalgrade != null ? b.fg : '');
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').text(b
            //                 .finalgrade != null ? b.fgremarks : '');
            //         } else {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').text('');
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').text('');
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').text('');
            //         }

            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').attr('data-id', b.id)
            //             .attr('data-status', b.prelemstatus);
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').attr('data-id', b.id)
            //             .attr('data-status', b.midtermstatus);
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').attr('data-id', b.id)
            //             .attr('data-status', b.prefistatus);
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').attr('data-id', b.id)
            //             .attr('data-status', b.finalstatus);
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').attr('data-id', b.id)
            //             .attr('data-status', b.finalstatus);
            //         $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').attr('data-id', b.id)
            //             .attr('data-status', b.finalstatus);

            //         // Remove any previous status classes
            //         for (var term = 1; term <= 6; term++) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="' + term + '"]')
            //                 .removeClass('bg-success bg-warning bg-primary bg-danger bg-info');
            //         }

            //         // Add status classes based on current status
            //         if (validStatuses.includes(b.prelemstatus)) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').addClass(
            //                 'bg-success');
            //         }
            //         if (validStatuses.includes(b.midtermstatus)) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').addClass(
            //                 'bg-success');
            //         }
            //         if (validStatuses.includes(b.prefistatus)) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').addClass(
            //                 'bg-success');
            //         }
            //         if (validStatuses.includes(b.finalstatus)) {
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').addClass(
            //                 'bg-success');
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').addClass(
            //                 'bg-success');
            //             $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').addClass(b
            //                 .fgremarks == 'FAILED' ? 'bg-danger' : 'bg-success');
            //         }
            //     });

            //     $('.grade_td').addClass('text-center');
            //     $('td[data-term="4"]').addClass('text-center');
            // }
            function plot_subject_grades(data) {
                // console.log(data, 'wewew')
                $.each(data, function(a, b) {

                    var q1status = 'input_grades'
                    var q2status = 'input_grades'
                    var q3status = 'input_grades'
                    var q4status = 'input_grades'

                    var validStatuses = [1, 2, 3, 4, 7];

                    if (validStatuses.includes(b.prelemstatus)) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').text(b
                            .prelemgrade != null ? b.prelemgrade : '')
                    } else {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').text('');
                    }

                    if (validStatuses.includes(b.midtermstatus)) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').text(b
                            .midtermgrade != null ? b.midtermgrade : '')
                    } else {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').text('');
                    }

                    if (validStatuses.includes(b.prefistatus)) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').text(b
                            .prefigrade != null ? b.prefigrade : '')
                    } else {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').text('');
                    }

                    if (validStatuses.includes(b.finalstatus)) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').text(b
                            .finalgrade != null ? b.finalgrade : '')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').text(b
                            .finalgrade != null ? b.fg : '')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').text(b
                            .finalgrade != null ? b.fgremarks : '')
                    }
                    //  else {
                    //     $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').text('');
                    //     $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').text('');
                    //     $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').text('');
                    // }

                    // $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').text(b.prelemgrade !=
                    //     null ? b.prelemgrade : '')
                    // $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').text(b.midtermgrade !=
                    //     null ? b.midtermgrade : '')
                    // $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').text(b.prefigrade !=
                    //     null ? b.prefigrade : '')
                    // $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').text(b.finalgrade !=
                    //     null ? b.finalgrade : '')
                    // $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').text(b.finalgrade !=
                    //     null ? b.fg : '')
                    // $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').text(b.finalgrade !=
                    //     null ? b.fgremarks : '')

                    $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').attr('data-id', b.id)
                    $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').attr('data-id', b.id)
                    $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').attr('data-id', b.id)
                    $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').attr('data-id', b.id)
                    $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').attr('data-id', b.id)
                    $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').attr('data-id', b.id)

                    $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').attr('data-status', b
                        .prelemstatus)
                    $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').attr('data-status', b
                        .midtermstatus)
                    $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').attr('data-status', b
                        .prefistatus)
                    $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').attr('data-status', b
                        .finalstatus)
                    $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').attr('data-status', b
                        .finalstatus)
                    $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').attr('data-status', b
                        .finalstatus)

                    if (b.prelemstatus == 1) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').addClass(
                            'bg-success')
                    } else if (b.prelemstatus == 3) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').addClass(
                            'bg-warning')
                        // $('.input_grades[data-studid="'+b.studid+'"][data-term="1"]').text("")
                    } else if (b.prelemstatus == 7) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').addClass(
                            'bg-primary')
                    } else if (b.prelemstatus == 9) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').addClass(
                            'bg-danger')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').text('DROPPED')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').text('DROPPED')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').text('DROPPED')
                    } else if (b.prelemstatus == 8) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').addClass(
                            'bg-warning')

                        $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').text('INC')

                        $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').addClass(
                            'bg-warning')

                        $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').text('INC')
                    } else if (b.prelemstatus == 4) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').addClass(
                            'bg-info')
                    } else if (b.prelemstatus == 2) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').addClass(
                            'bg-primary')
                    } else {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').removeAttr(
                            'class')
                        $('td[data-studid="' + b.studid + '"][data-term="1"]').addClass(
                            'grade_td text-center align-middle input_grades')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').text("")
                    }

                    if (b.midtermstatus == 1) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').addClass(
                            'bg-success')
                    } else if (b.midtermstatus == 3) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').addClass(
                            'bg-warning')
                        // $('.input_grades[data-studid="'+b.studid+'"][data-term="2"]').text("")
                    } else if (b.midtermstatus == 7) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').addClass(
                            'bg-primary')
                    } else if (b.midtermstatus == 9) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').addClass(
                            'bg-danger')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').text('DROPPED')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').text('DROPPED')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').text('DROPPED')
                    } else if (b.midtermstatus == 8) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').addClass(
                            'bg-warning')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').text('INC')

                        $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').addClass(
                            'bg-warning')

                        $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').text('INC')
                    } else if (b.midtermstatus == 4) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').addClass(
                            'bg-info')
                    } else if (b.midtermstatus == 2) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').addClass(
                            'bg-primary')
                    } else {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').removeAttr(
                            'class')
                        $('td[data-studid="' + b.studid + '"][data-term="2"]').addClass(
                            'grade_td text-center align-middle input_grades')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').text("")
                    }

                    if (b.prefistatus == 1) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').addClass(
                            'bg-success')
                    } else if (b.prefistatus == 3) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').addClass(
                            'bg-warning')
                        // $('.input_grades[data-studid="'+b.studid+'"][data-term="3"]').text("")
                    } else if (b.prefistatus == 7) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').addClass(
                            'bg-primary')
                    } else if (b.prefistatus == 9) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').addClass(
                            'bg-danger')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').text('DROPPED')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').text('DROPPED')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').text('DROPPED')
                    } else if (b.prefistatus == 4) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').addClass(
                            'bg-info')
                    } else if (b.prefistatus == 8) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').addClass(
                            'bg-warning')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').text('INC')

                        $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').addClass(
                            'bg-warning')

                        $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').text('INC')

                    } else if (b.prefistatus == 2) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').addClass(
                            'bg-primary')
                    } else {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').removeAttr(
                            'class')
                        $('td[data-studid="' + b.studid + '"][data-term="3"]').addClass(
                            'grade_td text-center align-middle input_grades')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').text("")
                    }

                    if (b.finalstatus == 1) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').addClass(
                            'bg-success')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').addClass(
                            'bg-success')
                        if (b.fgremarks == 'FAILED') {
                            $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').addClass(
                                'bg-danger')
                        } else {
                            $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').addClass(
                                'bg-success')
                        }
                    } else if (b.finalstatus == 7) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').addClass(
                            'bg-primary')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').addClass(
                            'bg-primary')
                        if (b.fgremarks == 'FAILED') {
                            $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').addClass(
                                'bg-danger')
                        } else {
                            $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').addClass(
                                'bg-primary')
                        }
                    } else if (b.finalstatus == 3) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').addClass(
                            'bg-warning')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').addClass(
                            'bg-warning')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').addClass(
                            'bg-warning')
                        // $('.input_grades[data-studid="'+b.studid+'"][data-term="4"]').text("")
                        // $('.input_grades[data-studid="'+b.studid+'"][data-term="5"]').text("")
                        // $('.input_grades[data-studid="'+b.studid+'"][data-term="6"]').text("")
                    } else if (b.finalstatus == 9) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').addClass(
                            'bg-danger')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').addClass(
                            'bg-danger')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').addClass(
                            'bg-danger')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').text('DROPPED')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').text('DROPPED')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').text('DROPPED')
                    } else if (b.finalstatus == 4) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').addClass(
                            'bg-info')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').addClass(
                            'bg-info')
                        if (b.fgremarks == 'FAILED') {
                            $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').addClass(
                                'bg-danger')
                        } else {
                            $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').addClass(
                                'bg-info')
                        }
                    } else if (b.finalstatus == 8) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').addClass(
                            'bg-warning')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').text('INC')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').addClass(
                            'bg-warning')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').text('INC')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').addClass(
                            'bg-warning')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').text('INC')
                    } else if (b.finalstatus == 2) {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').addClass(
                            'bg-primary')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="5"]').addClass(
                            'bg-primary')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="6"]').addClass(
                            'bg-primary')
                    } else {
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').removeAttr(
                            'class')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').text("")
                    }

                    if (b.prelemstatus == 1 || b.prelemstatus == 7) {
                        $('.select[data-studid="' + b.studid + '"]').removeAttr('disabled')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="1"]').removeClass(
                            'input_grades')
                    }

                    if (b.midtermstatus == 1 || b.midtermstatus == 7) {
                        $('.select[data-studid="' + b.studid + '"]').removeAttr('disabled')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="2"]').removeClass(
                            'input_grades')
                    }

                    if (b.prefistatus == 1 || b.prefistatus == 7) {
                        $('.select[data-studid="' + b.studid + '"]').removeAttr('disabled')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="3"]').removeClass(
                            'input_grades')
                    }

                    if (b.finalstatus == 1 || b.finalstatus == 7) {
                        $('.select[data-studid="' + b.studid + '"]').removeAttr('disabled')
                        $('.input_grades[data-studid="' + b.studid + '"][data-term="4"]').removeClass(
                            'input_grades')
                    }


                })

                $('.grade_td').addClass('text-center')
                $('td[data-term="4"]').addClass('text-center')

            }

            function subjects() {
                $('#teacher_scheed').empty()
                $('#status_holder').text('fetching')
                $.ajax({
                    type: 'GET',
                    url: '/college/grades/monitoring/teacher/subjects',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_sem').val(),
                        teacherid: $('#filter_teacher').val()
                    },
                    success: function(data) {
                        $('#status_holder').text('done')
                        all_teacher_sched = data

                        console.log(all_teacher_sched, 'all_teacher_sched');
                        teacher_sched()
                    }
                })
            }

            // function subjects(){
            //       $('#status_holder').text('fetching')
            //       $.ajax({
            //             type:'GET',
            //             url: '/college/grades/monitoring/teacher/subjects',
            //             data:{
            //                   syid:$('#filter_sy').val(),
            //                   semid:$('#filter_sem').val(),
            //                   teacherid:$('#filter_teacher').val()
            //             },
            //             success:function(data) {
            //                   if(data.length == 0){
            //                         console.log('No subjects found')
            //                   }
            //                   $('#status_holder').text('done')
            //                   all_teacher_sched = data

            //                   teacher_sched()
            //             }
            //       })
            // }


            function teachers() {
                $.ajax({
                    type: 'GET',
                    url: '/college/grades/monitoring/teachers',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_sem').val()
                    },
                    success: function(data) {
                        all_teacher = data
                        $("#filter_teacher").empty()
                        $("#filter_teacher").append('<option value="">Select Teacher</option>')
                        $("#filter_teacher").select2({
                            data: all_teacher,
                            allowClear: true,
                            placeholder: "Select Teacher",
                        })
                    }
                })
            }


            function teacher_sched() {
                var filter_teacher_id = $('#filter_teacher').val()
                var selected_status = $('#filter_status_by_subject').val()

                var uns_checked = selected_status == "uns" ? 'selected="selected"' : ''
                var sub_checked = selected_status == "sub" ? 'selected="selected"' : ''
                var app_checked = selected_status == "app" ? 'selected="selected"' : ''
                var pen_checked = selected_status == "pen" ? 'selected="selected"' : ''
                var inc_checked = selected_status == "inc" ? 'selected="selected"' : ''
                var drop_checked = selected_status == "drop" ? 'selected="selected"' : ''
                var deanapp_checked = selected_status == "deanapp" ? 'selected="selected"' : ''
                var posted_checked = selected_status == "posted" ? 'selected="selected"' : ''

                $('#filter_status_by_subject').empty();
                $('#filter_status_by_subject').append('<option value="uns" ' + uns_checked + '>Unsubmitted (' +
                    all_teacher_sched.filter(x => x.uns == true).length + ')</option>');
                $('#filter_status_by_subject').append('<option value="sub" ' + sub_checked + '>Submitted (' +
                    all_teacher_sched.filter(x => x.sub == true).length + ')</option>');
                $('#filter_status_by_subject').append('<option value="app" ' + app_checked +
                    '>Program Head Approved (' + all_teacher_sched.filter(x => x.app == true).length +
                    ')</option>');
                $('#filter_status_by_subject').append('<option value="deanapp" ' + deanapp_checked +
                    '>Dean Approved (' + all_teacher_sched.filter(x => x.deanapp == true).length + ')</option>');
                $('#filter_status_by_subject').append('<option value="posted" ' + posted_checked + '>Posted (' +
                    all_teacher_sched.filter(x => x.posted == true).length + ')</option>');
                $('#filter_status_by_subject').append('<option value="pen" ' + pen_checked + '>Pending (' +
                    all_teacher_sched.filter(x => x.pen == true).length + ')</option>');
                $('#filter_status_by_subject').append('<option value="inc" ' + inc_checked + '>INC (' +
                    all_teacher_sched.filter(x => x.inc == true).length + ')</option>');
                $('#filter_status_by_subject').append('<option value="drop" ' + drop_checked + '>Dropped (' +
                    all_teacher_sched.filter(x => x.drop == true).length + ')</option>');

                if ($('#filter_status_by_subject').val() == 'sub') {
                    temp_subjects = all_teacher_sched.filter(x => x.sub == true)
                } else if ($('#filter_status_by_subject').val() == 'pen') {
                    temp_subjects = all_teacher_sched.filter(x => x.pen == true)
                } else if ($('#filter_status_by_subject').val() == 'app') {
                    temp_subjects = all_teacher_sched.filter(x => x.app == true)
                } else if ($('#filter_status_by_subject').val() == 'uns') {
                    temp_subjects = all_teacher_sched.filter(x => x.uns == true)
                } else if ($('#filter_status_by_subject').val() == 'inc') {
                    temp_subjects = all_teacher_sched.filter(x => x.inc == true)
                } else if ($('#filter_status_by_subject').val() == 'drop') {
                    temp_subjects = all_teacher_sched.filter(x => x.drop == true)
                } else if ($('#filter_status_by_subject').val() == 'deanapp') {
                    temp_subjects = all_teacher_sched.filter(x => x.deanapp == true)
                } else if ($('#filter_status_by_subject').val() == 'posted') {
                    temp_subjects = all_teacher_sched.filter(x => x.posted == true)
                }

                if (filter_teacher_id == "" || filter_teacher_id == null) {
                    $("#teacher_scheed").DataTable({
                        destroy: true,
                        'language': {
                            'emptyTable': 'It looks like you haven&#39t set any filters yet. Start filtering to see data.' // Update 'dadadada' to appropriate language options
                        },
                        lengthChange: false,
                        scrollX: true,
                        autoWidth: false
                    });
                } else if (temp_subjects.length < 1) {
                    console.log(temp_subjects, 'temp_subjects', 'walay sulod');
                    console.log(filter_teacher_id, 'tid');


                    $("#teacher_scheed").DataTable({
                        destroy: true,
                        'language': {
                            'emptyTable': 'No entries available. Please check your filters or search again.' // Update 'dadadada' to appropriate language options
                        },
                        lengthChange: false,
                        scrollX: true,
                        autoWidth: false
                    });
                } else if (temp_subjects.length > 0) {
                    console.log(temp_subjects, 'temp_subjects', 'naay sulod');
                    console.log(filter_teacher_id, 'tid');

                    $("#teacher_scheed").DataTable({
                        destroy: true,
                        data: temp_subjects,
                        lengthChange: false,
                        scrollX: true,
                        autoWidth: false,
                        order: [
                            [0, "asc"]
                        ],
                        columns: [{
                                "data": "subjCode"
                            },
                            {
                                "data": "subjDesc"
                            },
                            {
                                "data": null
                            },
                        ],
                        columnDefs: [{
                                'targets': 0,
                                'orderable': true,
                                'createdCell': function(td, cellData, rowData, row, col) {
                                    var text = '<a class="mb-0">' + rowData.sectionDesc +
                                        '</a><p class="text-muted mb-0" style="font-size:.7rem">' +
                                        rowData.levelname.replace('COLLEGE', '') + ' - ' + rowData
                                        .courseabrv + '</p>';
                                    $(td)[0].innerHTML = text
                                    $(td).addClass('align-middle')
                                }
                            },
                            {
                                'targets': 1,
                                'orderable': true,
                                'createdCell': function(td, cellData, rowData, row, col) {
                                    if (school == 'spct'.toUpperCase() || school == 'gbbc'
                                        .toUpperCase()) {
                                        var text = rowData.subjDesc
                                    } else {
                                        var text = '<a class="mb-0">' + rowData.subjDesc +
                                            '</a><p class="text-muted mb-0" style="font-size:.7rem">' +
                                            rowData.subjCode;
                                    }

                                    $(td)[0].innerHTML = text
                                    $(td).addClass('align-middle')

                                }
                            },
                            {
                                'targets': 2,
                                'orderable': true,
                                'createdCell': function(td, cellData, rowData, row, col) {
                                    var button =
                                        '<button class="btn btn-sm btn-primary view_grade" data-schedid="' +
                                        rowData.schedid + '" data-id="' + rowData.subjectID +
                                        '" style="font-size:.7rem !important">View Grades</button>'
                                    $(td)[0].innerHTML = button
                                    $(td).addClass('align-middle')

                                }
                            },

                        ]

                    });
                }

            }



        })
    </script>
@endsection
