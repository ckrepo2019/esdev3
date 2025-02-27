@php
    if (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (auth()->user()->type == 1 || Session::get('currentPortal') == 1) {
        $extend = 'teacher.layouts.app';
    } elseif (auth()->user()->type == 2 || Session::get('currentPortal') == 2) {
        $extend = 'teacher.layouts.app';
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

        .no-border-col {
            border-left: 0 !important;
            border-right: 0 !important;
        }
    </style>
@endsection


@section('content')
    <div class="modal fade" id="ecr_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0 bg-primary">
                    <h4 class="modal-title" style="font-size: 1.1rem !important"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="">ECR Format</label>
                                    <select name="" id="ecr_format" class="form-control form-control-sm">
                                        <option value="1">Format 1</option>
                                        <option value="2">Format 2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm btn-block" id="download_ecr"><i
                                            class="fas fa-file-excel"></i> Download ECR</button>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-3" style=" font-size:11px !important">
                                <div class="col-md-5">
                                    <strong><i class="fas fa-book mr-1"></i> Grade Level</strong>
                                    <p class="text-muted" id="label_gradelevel">
                                        --
                                    </p>
                                </div>
                                <div class="col-md-7">
                                    <strong><i class="fas fa-book mr-1"></i> Section</strong>
                                    <p class="text-muted" id="label_section">
                                        --
                                    </p>
                                </div>

                            </div>
                            <div class="row" style=" font-size:11px !important">
                                <div class="col-md-12">
                                    <strong><i class="fas fa-book mr-1"></i> Subject</strong>
                                    <p class="text-muted mb-0" id="label_subject">
                                        --
                                    </p>
                                    <p class="text-danger mb-0">
                                        <i id="label_subjectcode"> -- </i>
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="">Quarter</label>
                                    <select name="" id="filter_quarter" class="form-control form-control-sm">
                                        <option value="" selected>Select Quarter</option>
                                        <option value="1">1st Quarter</option>
                                        <option value="2">2nd Quarter</option>
                                        <option value="3">3rd Quarter</option>
                                        <option value="4">4th Quarter</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><button class="btn btn-primary btn-sm" id="ecr_filter"><i
                                            class="fas fa-filter"></i> Filter</button></div>
                                <div class="col-md-6"><button class="btn btn-success btn-sm btn-block" id="ecr_submit"
                                        disabled><i class="far fa-share-square"></i> Submit</button></div>
                            </div>
                            <hr>
                            <div class="row mt-3" style=" font-size:11px !important">
                                <div class="col-md-12">
                                    <strong><i class="fas fa-book mr-1"></i> Last date Uploaded</strong>
                                    <p class="text-muted" id="label_dateuploaded">
                                        --
                                    </p>
                                </div>
                                <div class="col-md-5">
                                    <strong><i class="fas fa-book mr-1"></i> Grade Status</strong>
                                    <p class="text-muted" id="label_status">
                                        --
                                    </p>
                                </div>
                                <div class="col-md-7">
                                    <strong><i class="fas fa-book mr-1"></i> Grade Submitted</strong>
                                    <p class="text-muted" id="label_datesubmitted">
                                        --
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Class Record</h5>
                                </div>
                                <div class="col-md-6">
                                    <form action="/ecr/upload" id="upload_ecr" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="input-group input-group-sm">
                                                <input type="file" class="form-control" name="input_ecr"
                                                    id="input_ecr">
                                                <span class="input-group-append">
                                                    <button class="btn btn-info btn-flat" id="upload_ecr_button">Update
                                                        ECR</button>
                                                </span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12" id="ecr_view_holder" style="font-size:11px !important">
                                    <table class="table table-sm table-bordered">
                                        <tr>
                                            <th class="text-center">Select Quarter</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="select_strand" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-primary p-1">
                </div>
                <div class="modal-body">
                    <div class="row" id="strand_holder">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Excel Upload</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Excel Upload</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>




    @php
        $sy = DB::table('sy')->orderBy('sydesc')->get();
        $semester = DB::table('semester')->get();
        $temp_teacherid = 0;
        if (auth()->user()->type != 17) {
            $temp_teacherid = DB::table('teacher')
                ->where('userid', auth()->user()->id)
                ->first();
            if (isset($temp_teacherid)) {
                $temp_teacherid = $temp_teacherid->id;
            }
        }

    @endphp

    <section class="content pt-0">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5">
                    <div class="info-box shadow-lg">
                        <div class="info-box-content">
                            <div class="row">
                                @if (auth()->user()->type == 17)
                                    <div class="col-md-12 form-group mb-0">
                                        <label for="">Teacher</label>
                                        <select class="form-control select2" id="filter_teacher">
                                        </select>
                                    </div>
                                @endif
                                <div class="col-md-5  form-group mb-0">
                                    <label for="">School Year</label>
                                    <select class="form-control select2" id="filter_sy">
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
                                <div class="col-md-5  form-group mb-0">
                                    <label for="">Semester</label>
                                    <select class="form-control select2  form-control-sm" id="filter_sem">
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
                                <div class="col-md-12" style="font-size:14px !important">
                                    <table class="table table-sm table-bordered table-striped" id="subjectplot_table"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th width="20%">Section</th>
                                                <th width="40%">Subject</th>
                                                <th width="22%" class="text-center">Time & Day</th>
                                                <th width="10%" class="text-center">Students</th>
                                                <th width="8%" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="schedule">
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
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>

    <script>
        $(document).ready(function() {

            $('.select2').select2()

            get_teachers()

            function get_teachers() {
                $.ajax({
                    type: 'GET',
                    url: '/teacher/list',
                    success: function(data) {
                        var all_teacher = data
                        $("#filter_teacher").empty()
                        $("#filter_teacher").append('<option value="">Select Teacher</option>')
                        $("#filter_teacher").val("")
                        $('#filter_teacher').select2({
                            allowClear: true,
                            data: all_teacher,
                            placeholder: "Select teacher",
                        })
                    }
                })


            }

        })
    </script>



    <script>
        $(document).ready(function() {

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
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

            $('#upload_ecr')
                .submit(function(e) {

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
                    inputs.append('levelid', temp_sched[0].levelid)
                    inputs.append('sectionid', temp_sched[0].sectionid)
                    inputs.append('subjid', temp_sched[0].subjid)
                    inputs.append('quarter', $('#filter_quarter').val())
                    inputs.append('ecrformat', $('#ecr_format').val())

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
                        url: '/ecr/upload',
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
                            } else {
                                Toast.fire({
                                    type: 'success',
                                    title: data[0].message
                                })
                                $('#upload_ecr_button').text('Update ECR')
                                $('#upload_ecr_button').removeAttr('disabled')
                                $('#input_ecr').val("")
                                load_ecr()
                            }
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
                        }
                    })
                    e.preventDefault();
                })

            $(document).on('click', '#ecr_submit', function() {

                var text = $('#label_subject').text()

                Swal.fire({
                    title: 'Are you sure you want to<br>submit ' + text + ' grades?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Submit ' + text + ' grades'
                }).then((result) => {
                    if (result.value) {
                        var excluded = []

                        $('.exclude').each(function() {
                            if ($(this).attr('disabled') == undefined && $(this).attr(
                                    'data-studid') != undefined) {
                                if ($(this).prop('checked') == false) {
                                    excluded.push($(this).attr('data-studid'))
                                }
                            }
                        })

                        $('#ecr_submit').attr('disabled', 'disabled')
                        $('#ecr_submit').text('Submitting...')

                        $.ajax({
                            url: '/ecr/submit',
                            type: 'GET',
                            data: {
                                syid: $('#filter_sy').val(),
                                quarter: $('#filter_quarter').val(),
                                levelid: temp_sched[0].levelid,
                                subjid: temp_sched[0].subjid,
                                sectionid: temp_sched[0].sectionid,
                                excluded: excluded
                            },
                            success: function(data) {
                                if (data[0].status == 0) {
                                    Toast.fire({
                                        type: 'warning',
                                        title: data[0].message
                                    })
                                    $('#ecr_submit')[0].innerHTML =
                                        '<i class="far fa-share-square"></i> Submit'
                                    $('#ecr_submit').removeAttr('disabled')
                                } else {
                                    Toast.fire({
                                        type: 'success',
                                        title: data[0].message
                                    })
                                    $('#ecr_submit')[0].innerHTML =
                                        '<i class="fas fa-check"></i> Submitted'
                                    update_sidenav()
                                    load_ecr()
                                }
                            },
                            error: function() {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Something went wrong!'
                                })
                            }
                        })
                    }
                })
            })

            function update_sidenav() {
                $.ajax({
                    url: '/teacher/get/pending',
                    type: "GET",
                    success: function(data) {
                        if (data[0].with_pending) {
                            $('.pending_status_holder').removeAttr('hidden')
                            if (data[0].student_pending_count > 0) {
                                $('.student_pending').removeAttr('hidden')
                                $('.student_pending').text(data[0].student_pending_count)
                            } else {
                                $('#student_pending').attr('hidden', 'hidden')
                            }
                            if (data[0].section_pending_count > 0) {
                                $('.section_pending').removeAttr('hidden')
                                $('.section_pending').text(data[0].section_pending_count)
                            } else {
                                $('#section_pending').attr('hidden', 'hidden')
                            }
                        } else {
                            $('.student_pending').attr('hidden', 'hidden')
                            $('.section_pending').attr('hidden', 'hidden')
                            $('#pending_status_holder').attr('hidden', 'hidden')
                        }
                    }
                });
            }

            $(document).on('click', '.select_all', function() {
                if ($(this).prop('checked') == false) {
                    $('.exclude').each(function() {
                        if ($(this).attr('disabled') == undefined) {
                            $(this).prop('checked', false)
                        }
                    })
                } else {
                    $('.exclude').each(function() {
                        if ($(this).attr('disabled') == undefined) {
                            $(this).prop('checked', true)
                        }
                    })
                }
            })

            var temp_sched = []

            $(document).on('click', '#ecr_filter', function() {
                if ($('#filter_quarter').val() == "") {
                    Toast.fire({
                        type: 'warning',
                        title: 'Select a quarter!'
                    });
                    return false;
                }
                load_ecr()
            })

            function load_ecr() {
                $.ajax({
                    url: '/ecr/view',
                    type: 'GET',
                    data: {
                        semid: $('#filter_sem').val(),
                        syid: $('#filter_sy').val(),
                        quarter: $('#filter_quarter').val(),
                        levelid: temp_sched[0].levelid,
                        subjid: temp_sched[0].subjid,
                        sectionid: temp_sched[0].sectionid,
                        ecrformat: $('#ecr_format').val()
                    },
                    success: function(data) {
                        try {
                            if (data[0].status == 0) {
                                Toast.fire({
                                    type: 'warning',
                                    title: data[0].message
                                })

                                if (data[0].message == 'Does not contain any detail.') {
                                    $('#ecr_view_holder').empty()
                                    $('#ecr_view_holder')[0].innerHTML =
                                        '<table class="table table-sm table-bordered"><tr><td class="text-center text-danger"><i>Please download the ECR first.</i></td></tr></table>';
                                }
                            } else {
                                $('#ecr_view_holder').empty()
                                $('#ecr_view_holder').append(data)
                            }
                        } catch (err) {
                            $('#ecr_view_holder').empty()
                            $('#ecr_view_holder').append(data)
                        }

                    }
                })
            }

            $(document).on('click', '.view_classrecord', function() {
                var temp_id = $(this).attr('data-id')
                selected_sched = temp_id
                temp_sched = all_sched.filter(x => x.temp_id == temp_id)

                $('#input_ecr').removeAttr('disabled')
                $('#input_ecr').prop('disabled', false);


                $('#upload_ecr_button').removeAttr('disabled')

                $('#label_gradelevel').text(temp_sched[0].levelname)
                $('#label_section').text(temp_sched[0].sectionname)
                $('#label_subject').text(temp_sched[0].subjdesc)
                $('#label_subjectcode').text(temp_sched[0].subjcode)
                $('#filter_quarter').val("").change()

                $('#ecr_view_holder')[0].innerHTML =
                    '<table class="table table-sm table-bordered"><tr><th class="text-center">Select Quarter</th></tr></table>';
                $('#ecr_submit')[0].innerHTML = '<i class="far fa-share-square"></i> Submit'

                $('#filter_quarter').empty();
                $('#filter_quarter').append('<option value="">Select Quarter</option>');
                $('#filter_quarter').append('<option value="1">1st Quarter</option>');
                $('#filter_quarter').append('<option value="2">2nd Quarter</option>');
                $('#filter_quarter').append('<option value="3">3rd Quarter</option>');
                $('#filter_quarter').append('<option value="4">4th Quarter</option>');

                if (temp_sched[0].levelid == 14 || temp_sched[0].levelid == 15) {
                    $('#filter_quarter').empty();
                    $('#filter_quarter').append('<option value="">Select Quarter</option>');
                    if ($('#filter_sem').val() == 1) {
                        $('#filter_quarter').append('<option value="1">1st Quarter</option>');
                        $('#filter_quarter').append('<option value="2">2nd Quarter</option>');
                    } else {
                        $('#filter_quarter').append('<option value="3">3rd Quarter</option>');
                        $('#filter_quarter').append('<option value="4">4th Quarter</option>');
                    }
                }


                $('#ecr_modal').modal();

            })

            $(document).on('click', '#download_ecr', function() {
                if (temp_sched[0].levelid == 14 || temp_sched[0].levelid == 15) {
                    $('#select_strand').modal()
                    $('#strand_holder').empty()
                    $.each(temp_sched[0].strand, function(a, b) {
                        $('#strand_holder').append(
                            '<div class="col-md-12 mt-2"><button class="btn btn-sm btn-primary btn-block download_ecr_sh" data-strand="' +
                            b.strandid + '">' + b.strandcode + '</button></div>')
                    })

                    $('#strand_holder').append(
                        '<div class="col-md-12 mt-2"><button class="btn btn-sm btn-primary btn-block download_ecr_sh" data-strand="">All</button></div>'
                    )


                } else {
                    window.open('ecr/download?syid=' + $('#filter_sy').val() + '&semid=' + $('#filter_sem')
                        .val() + '&levelid=' + temp_sched[0].levelid + '&subjid=' + temp_sched[0]
                        .subjid + '&sectionid=' + temp_sched[0].sectionid + '&ecrformat=' + $(
                            '#ecr_format').val(), '_blank');
                }
            })


            $(document).on('click', '.download_ecr_sh', function() {
                var strandid = $(this).attr('data-strand')
                window.open('ecr/download?syid=' + $('#filter_sy').val() + '&semid=' + $('#filter_sem')
                    .val() + '&levelid=' + temp_sched[0].levelid + '&subjid=' + temp_sched[0].subjid +
                    '&sectionid=' + temp_sched[0].sectionid + '&strandid=' + strandid + '&ecrformat=' +
                    $('#ecr_format').val(), '_blank');
            })


            var all_sched = [];

            var temp_teacher = @json($temp_teacherid);

            load_schedule()

            $(document).on('click', '#button_filter', function() {
                load_schedule()
            })

            $(document).on('change', '#filter_sy', function() {
                all_sched = []
                load_schedule()
            })

            $(document).on('change', '#filter_sem', function() {
                all_sched = []
                load_schedule()
            })


            $(document).on('change', '#filter_quarter', function() {
                $('#ecr_submit').attr('disabled', 'disabled')
                //     $('#input_ecr').attr('disabled', 'disabled')
                //     $('#input_ecr').prop('disabled', true);
                $('#input_ecr').val("")
                $('#upload_ecr_button').attr('disabled', 'disabled')
                $('#ecr_view_holder').empty()
                $('#ecr_view_holder')[0].innerHTML =
                    '<table class="table table-sm table-bordered"><tr><td class="text-center"><i>Click filter button</i></td></tr></table>';
            })

            $(document).on('change', '#filter_teacher', function() {
                load_schedule()
            })


            function load_schedule() {

                if (temp_teacher == 0) {
                    var teacherid = $('#filter_teacher').val()
                } else {
                    var teacherid = temp_teacher
                }

                if ($('#filter_teacher').val() != "") {
                    $.ajax({
                        type: 'GET',
                        url: '/teacher/schedule',
                        data: {
                            syid: $('#filter_sy').val(),
                            semid: $('#filter_sem').val(),
                            teacherid: teacherid
                        },
                        success: function(data) {
                            if (data.length == 0) {
                                Toast.fire({
                                    type: 'info',
                                    title: 'No schedule found!'
                                })
                                all_sched = []
                                load_gradesetup_datatable()
                            } else {
                                if (data[0].status == undefined) {
                                    all_sched = data
                                    load_gradesetup_datatable()
                                } else {
                                    all_sched = []
                                    load_gradesetup_datatable()
                                }
                            }
                        }
                    })
                } else {
                    Toast.fire({
                        type: 'info',
                        title: 'No student selected!'
                    })
                    all_sched = [];
                    load_gradesetup_datatable()
                }
            }

            function load_gradesetup_datatable() {

                $("#subjectplot_table").DataTable({
                    destroy: true,
                    data: all_sched,
                    lengthChange: false,
                    columns: [{
                            "data": "search"
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
                        },
                    ],
                    columnDefs: [{
                            'targets': 0,
                            'orderable': true,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var text = '<a class="mb-0">' + rowData.sectionname +
                                    '</a><p class="text-muted mb-0" >' + rowData.levelname + '</p>';
                                $(td)[0].innerHTML = text
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': true,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var comp = '';
                                var consolidate = ''
                                var spec = ''
                                var type = ''
                                var percentage = ''
                                var visDis = ''

                                if ($('#filter_gradelevel').val() != 14 && $('#filter_gradelevel')
                                    .val() != 15) {
                                    if (rowData.isCon == 1) {}

                                    if (rowData.isSP == 1) {
                                        spec = '-  <i class="text-danger"> Specialization </i>'
                                    }

                                    if (rowData.subjCom != null) {}

                                    if (rowData.subj_per != 0) {
                                        percentage = '-  <i class="text-danger">' + rowData
                                            .subj_per + '%</i>'
                                    }

                                    var visDis = '<span class="badge badge-success">V</span>'
                                    if (rowData.isVisible == 0) {
                                        visDis =
                                            '<span class="badge badge-danger badge-danger">V</span>'
                                    }

                                } else {
                                    if (rowData.type == 1) {
                                        type = '-  <i class="text-danger">Core</i>'
                                    } else if (rowData.type == 2) {
                                        type = '-  <i class="text-danger">Specialized</i>'
                                    } else if (rowData.type == 3) {
                                        type = '-  <i class="text-danger">Applied</i>'
                                    }
                                }

                                var pending = ''
                                if (rowData.with_pending) {
                                    pending =
                                        '<span class="badge badge-warning">With Pending</span>'
                                }

                                var subj_num = 'S' + ('000' + rowData.subjid).slice(-3)

                                var text = '<a class="mb-0">' + rowData.subjdesc + ' ' + comp +
                                    ' ' + pending + ' </a><p class="text-muted mb-0">' + rowData
                                    .subjcode + ' ' + type + '</p>';
                                $(td)[0].innerHTML = text
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            'targets': 2,
                            'orderable': true,
                            'createdCell': function(td, cellData, rowData, row, col) {

                                var table = 'table-borderless'
                                var multiple = ''

                                if (rowData.schedule.length > 1) {
                                    table = 'table-bordered'
                                    multiple = 'no-border-col'
                                }

                                var text = '<table class="table table-sm mb-0 ' + table + '" >'
                                $.each(rowData.schedule, function(a, b) {
                                    text +=
                                        '<tr style="background-color:transparent !important" ><td width="100%" class="' +
                                        multiple + '">' + b.start + ' - ' + b.end + '<br>' +
                                        b.day + '</td></tr>'
                                })
                                text += '</table>'
                                $(td)[0].innerHTML = text
                                $(td).addClass('align-middle')
                                $(td).addClass('p-0')
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': true,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var text = rowData.enrolled
                                $(td)[0].innerHTML = text
                                $(td).addClass('align-middle')
                                $(td).addClass('text-center')
                            }
                        },
                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var text =
                                    '<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                                    '<i class="fas fa-ellipsis-v"></i>' +
                                    '</button>' +
                                    '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">' +
                                    '<a class="dropdown-item view_classrecord" href="#" data-id="' +
                                    rowData.temp_id +
                                    '"><i class="fas fa-clipboard"></i>  Class Record</a>' +

                                    '</div>'
                                $(td)[0].innerHTML = text
                                $(td).addClass('align-middle')
                                $(td).addClass('text-center')
                            }
                        },

                    ]

                });

                var label_text = $($("#subjectplot_table_wrapper")[0].children[0])[0].children[0]
                $(label_text)[0].innerHTML = '<h4 class="mb-0">Grade Status</h4>'
            }
        })
    </script>
@endsection
