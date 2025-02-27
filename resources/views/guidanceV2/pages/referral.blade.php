@php
    $check_refid = DB::table('usertype')
        ->where('id', Session::get('currentPortal'))
        ->where('deleted', 0)
        ->select('refid', 'resourcepath')
        ->first();

    if (Session::get('currentPortal') == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (Session::get('currentPortal') == 3) {
        $extend = 'registrar.layouts.app';
    } elseif (Session::get('currentPortal') == 8) {
        $extend = 'admission.layouts.app2';
    } elseif (Session::get('currentPortal') == 1) {
        $extend = 'teacher.layouts.app';
    } elseif (Session::get('currentPortal') == 2) {
        $extend = 'principalsportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 4) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 15) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 18) {
        $extend = 'ctportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 10) {
        $extend = 'hr.layouts.app';
    } elseif (Session::get('currentPortal') == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } elseif (auth()->user()->type == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } else {
        if (isset($check_refid->refid)) {
            if ($check_refid->resourcepath == null) {
                $extend = 'general.defaultportal.layouts.app';
            } elseif ($check_refid->refid == 26) {
                $extend = 'hr.layouts.app';
            } elseif ($check_refid->refid == 27) {
                $extend = 'academiccoor.layouts.app2';
            } elseif ($check_refid->refid == 22) {
                $extend = 'principalcoor.layouts.app2';
            } elseif ($check_refid->refid == 29) {
                $extend = 'idmanagement.layouts.app2';
            } elseif ($check_refid->refid == 23) {
                $extend = 'clinic.index';
            } elseif ($check_refid->refid == 24) {
                $extend = 'clinic_nurse.index';
            } elseif ($check_refid->refid == 25) {
                $extend = 'clinic_doctor.index';
            } elseif ($check_refid->refid == 31) {
                $extend = 'guidanceV2.layouts.app2';
            } else {
                $extend = 'general.defaultportal.layouts.app';
            }
        } else {
            $extend = 'general.defaultportal.layouts.app';
        }
    }
@endphp
@extends($extend)



@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <style>
        .align-middle td {
            vertical-align: middle;
        }
    </style>
@endsection



@section('content')
    <!-- MODAL EDIT REFERRAL -->
    <div class="modal fade" id="modalEditReferral" tabindex="-1" role="dialog" aria-labelledby="modalEditReferral"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title"><strong>Referral Info</strong> </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="">
                            <div class="row justify-content-between">
                                <div class="form-group col-md-3">
                                    <label class="mb-1">Date of Filling</label>
                                    <input class="form-control" id="filleddate" type="text" disabled>
                                </div>
                                <div class="form-group">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success">Action</button>
                                        <button type="button" class="btn btn-success dropdown-toggle"
                                            data-toggle="dropdown" aria-expanded="false">
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu" style="">
                                            <a class="dropdown-item acknowledge" href="#">Acknowledge</a>
                                            <a class="dropdown-item btn_done" href="#">Done</a>
                                            {{-- <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Separated link</a> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="mt-2">NAME OF REFERRED STUDENT</label>
                            <div class="row">
                                <input type="number" class="form-control" id="referralid" hidden>
                                <div class="form-group col-md-4">
                                    <label for="" class="mb-1">Search Name</label>
                                    <select name="" id="select_stud" class="form-control" style="width:100%;"
                                        disabled>
                                        <option value="">Select Student</option>
                                        @foreach (DB::table('studinfo')->get() as $item)
                                            <option value="{{ $item->id }}"> {{ $item->sid }} -
                                                {{ $item->lastname }},
                                                {{ $item->firstname }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="mb-1">Last Name</label>
                                        <input class="form-control" id="lastname" type="text" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="mb-1">First Name</label>
                                        <input class="form-control" id="firstname" type="text" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="mb-1">Middle Name</label>
                                        <input class="form-control" id="middlename" type="text" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="mb-1">Year/Course</label>
                                        <input class="form-control" id="level" type="text" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        @php
                                            $sy = DB::table('sy')->where('isactive', 1)->first();
                                        @endphp
                                        <label class="mb-1">Semester/S.Y</label>
                                        <input class="form-control" id="sy" value="{{ $sy->sydesc }}"
                                            type="text" disabled>
                                    </div>
                                </div>
                            </div>

                            <label class="mt-2"> REASON/S FOR REFERRAL </label>
                            <div class="row listofreasons"></div>

                            <div class="row mt-1">
                                <div class="form-group col-md-4">
                                    <label for="" class="mb-1">Referred by</label>
                                    {{-- <input type="text" class="form-control"> --}}
                                    <select name="" id="select_teacher" class="form-control" style="width:100%;"
                                        disabled>
                                        <option value="">Select Person</option>
                                        @foreach (DB::table('teacher')->where('deleted', 0)->get() as $item)
                                            <option value="{{ $item->id }}"> {{ $item->lastname }},
                                                {{ $item->firstname }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="" class="mb-1">Position/Designation</label>
                                    <input type="text" id="position" class="form-control" disabled>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="" class="mb-1">Email/Contact No.</label>
                                    <input type="text" id="phone" class="form-control" disabled>
                                </div>
                            </div>

                            <div class="acknowledgement_wrapper" hidden>
                                <hr>
                                <h5><strong>Referral Acknowledgement Slip</strong> </h5>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label class="mb-1">Date Received</label>
                                        <input class="form-control" id="datereceived" type="date">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="mb-1">Received by</label>
                                        <select name="" id="select_teacher2" class="form-control "
                                            style="width:100%;">
                                            <option value="">Select Person</option>
                                            @foreach (DB::table('teacher')->where('deleted', 0)->get() as $item)
                                                <option value="{{ $item->id }}"> {{ $item->lastname }},
                                                    {{ $item->firstname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="mb-1">Position</label>
                                        <input type="text" class="form-control" id="position2">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="mb-1">Remarks</label>
                                        <textarea class="form-control" name="" id="remarks"></textarea>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="mb-1">Counseling Schedule</label>
                                        <input class="form-control" id="counselingdate" type="date">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btn_acknowledge">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Header (Page header) -->
    {{-- <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Referral</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item "> <a href="/home">Home</a> </li>
                        <li class="breadcrumb-item active">{{ $current_page }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div> --}}

    <div class="content">
        <div class="container-fluid">
            <div class="card shadow">
                <div class="card-header">
                    <h1 class="card-title">Referral Information</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <a type="button" class="btn btn-primary mr-2" href="/guidance/referral-form">
                                <i class="fas fa-plus"></i> Fill Up Form
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table-hover table table-striped align-middle" id="tbl_appointment" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student Name</th>
                                    <th>Date Filled Up</th>
                                    <th>Date Scheduled for Counseling</th>
                                    <th>Referredby</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script>
        var jsonData = {!! json_encode($jsonData) !!};
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
        $(document).ready(function() {
            console.log(jsonData);
            load_referral_datatable(jsonData);

            $('#select_stud').select2({
                allowClear: true,
                placeholder: 'Search Student',
                theme: 'bootstrap4'
            })

            $('#select_teacher').select2({
                allowClear: true,
                placeholder: 'Search Person',
                theme: 'bootstrap4'
            })

            $('#select_teacher2').select2({
                allowClear: true,
                placeholder: 'Search Person',
                theme: 'bootstrap4'
            })

            $('#select_stud').on('change', function() {
                var id = $(this).val()
                console.log(id)
                getstudent(id)
            })
            $('#select_teacher').on('change', function() {
                var id = $(this).val()
                console.log(id)
                getteacher(id)
            })

            $('#select_teacher2').on('change', function() {
                var id = $(this).val()
                console.log(id)
                getteacher(id)
            })

            $('.btn_fill_form').on('click', function() {
                $('#modalAddForm').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            })

            $(document).on('click', '.btn_delete', function() {
                var id = $(this).data('id');
                console.log(id)
                delete_referral(id)
            })

            $('.acknowledge').on('click', function() {
                console.log('hello')
                var id = $('#referralid').val();
                console.log(id)
                $('.acknowledgement_wrapper').prop('hidden', false)
            })

            $(document).on('click', '.btn_edit', function() {
                var id = $(this).data('id');
                console.log(id);
                $('#modalEditReferral').modal()
                $.ajax({
                    url: '{{ route('get.referral') }}',
                    type: "GET",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        console.log(data)
                        if (data.status == 1 || data.status == 2) {
                            $('.acknowledgement_wrapper').prop('hidden', false)
                            $('#counselingdate').prop('disabled', true)
                        } else {
                            $('.acknowledgement_wrapper').prop('hidden', true)
                            $('#counselingdate').prop('disabled', false)
                        }

                        $('#datereceived').val(data.datereceived)
                        $('#datereceived').val(data.datereceived)
                        $('#counselingdate').val(data.counselingdate)
                        $('#select_teacher2').val(data.counselor).change()
                        $('#remarks').val(data.remarks)

                        $('#select_stud').val(data.studid).change()
                        $('#select_teacher').val(data.referredby).change()
                        $('#filleddate').val(data.formatted_filleddate)
                        $('#referralid').val(data.id)
                        $('.listofreasons').empty()
                        data.reasons.forEach((element, index) => {
                            $('.listofreasons').append(`
                                <div class="form-group col-12">
                                    <label><i class="fas fa-check-square text-success mr-1"></i>${element.reason}</label>
                                    <input type="text" class="form-control" id="input_reason${index}" value="${element.description || "No Description"}" placeholder="" disabled>
                                </div>
                            `);
                        });
                        // load_referral_datatable(data);
                    }
                });
            });

            $(document).on('click', '#btn_acknowledge', function() {
                var isvalidinputs = true
                var id = $('#referralid').val();
                console.log(id);

                if (!$('#datereceived').val()) {
                    $('#datereceived').addClass('is-invalid')
                    notify('error', 'Date Received is required!')
                    isvalidinputs = false
                    return
                } else {
                    $('#datereceived').removeClass('is-invalid')
                }

                if (!$('#select_teacher2').val()) {
                    $('#select_teacher2').addClass('is-invalid')
                    notify('error', 'Received by is required!')
                    isvalidinputs = false
                    return
                } else {
                    $('#select_teacher2').removeClass('is-invalid')
                }

                if (!$('#counselingdate').val()) {
                    $('#counselingdate').addClass('is-invalid')
                    notify('error', 'Counseling date is required!')
                    isvalidinputs = false
                    return
                } else {
                    $('#counselingdate').removeClass('is-invalid')
                }

                console.log($('#datereceived').val());
                console.log($('#select_teacher2').val());
                console.log($('#counselingdate').val());
                console.log($('#remarks').val());

                $.ajax({
                    url: '{{ route('acknowledge.referral') }}',
                    type: "GET",
                    data: {
                        id: id,
                        datereceived: $('#datereceived').val(),
                        receivedby: $('#select_teacher2').val(),
                        counselingdate: $('#counselingdate').val(),
                        remarks: $('#remarks').val(),
                    },
                    success: function(data) {
                        console.log(data)
                        notify(data.status, data.message);
                        $('#modalEditReferral').modal('hide');
                        referrals()
                    }
                });
            });

            $('.btn_done').on('click', function() {
                var id = $('#referralid').val();
                console.log(id)
                $.ajax({
                    url: '{{ route('done.referral') }}',
                    type: "GET",
                    data: {
                        id: id,
                    },
                    success: function(data) {
                        notify(data.status, data.message);
                        referrals()
                    }
                });
            })
        });

        function getstudent(value) {
            $.ajax({
                type: 'GET',
                url: '{{ route('get.student') }}',
                data: {
                    id: value
                },
                success: function(response) {
                    console.log(response)
                    $('#lastname').val(response.lastname)
                    $('#firstname').val(response.firstname)
                    $('#middlename').val(response.middlename)
                    $('#level').val(response.levelname)
                    // $('#middlename').val(response.middlename)
                }
            })
        }

        function getteacher(value) {
            $.ajax({
                type: 'GET',
                url: '{{ route('get.teacher') }}',
                data: {
                    id: value
                },
                success: function(response) {
                    console.log(response)
                    $('#position').val(response.utype)
                    $('#position2').val(response.utype)
                    $('#phone').val(response.phonenumber ?? 'Not Specified')
                }
            })
        }

        function delete_referral(id) {
            Swal.fire({
                type: 'info',
                title: 'You want to delete this referral?',
                text: `You can't undo this process.`,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '{{ route('delete.referral') }}',
                        type: "GET",
                        data: {
                            id: id,
                        },
                        success: function(data) {
                            notify(data.status, data.message);
                            referrals()
                        }
                    });
                }
            });
        }

        function referrals() {
            $.ajax({
                url: '{{ route('referrals') }}',
                type: "GET",
                success: function(data) {
                    load_referral_datatable(data);
                }
            });
        }

        function load_referral_datatable(data) {
            $("#tbl_appointment").DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                lengthChange: false,
                columns: [{
                        data: "id",
                    },
                    {
                        data: "studname",
                    },
                    {
                        data: "formatted_filleddate",
                    },
                    {
                        data: "formatted_counselingdate",
                    },
                    {
                        data: 'referredby_fullname',
                    },
                    {
                        data: 'status',
                        render: function(type, data, row) {
                            return `<span class="p-2 badge ${row.status === 0 ? 'badge-primary' : row.status === 1 ? 'badge-warning' : 'badge-success'}">${row.status === 0 ? 'New' : row.status === 1 ? 'Pending' : 'Done'}</span>`;
                        }
                    },
                    {
                        data: null,
                        className: 'text-right',
                        render: function(type, data, row) {
                            return `
                                <div class="btn-group">
                                    <a type="button" href="javascript:void(0)" class="btn btn-default btn_edit" data-id="${row.id}"> <i class="far fa-edit text-primary"></i> </a>
                                    <a type="button" href="javascript:void(0)" class="btn btn-default btn_delete" data-id="${row.id}"> <i class="far fa-trash-alt text-danger"></i> </a> 
                                </div>
                            `;
                        }
                    },
                ],
            });
        }

        function notify(status, message) {
            Toast.fire({
                type: status,
                title: message,
            });
        }
    </script>
@endsection
