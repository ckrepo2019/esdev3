@extends('guidanceV2.layouts.app2')

@section('pagespecificscripts')
    <style>
        .align-middle td {
            vertical-align: middle;
        }
    </style>
@endsection



@section('content')
    @include('guidanceV2.components.counselModals')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Counseling Information</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item "> <a href="#">Home</a> </li>
                        <li class="breadcrumb-item active">{{ $current_page }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline card-outline-tabs shadow">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                        {{-- <li class="nav-item">
                            <a class="nav-link {{ $current_page == 'Counseling Form Setup' ? 'active' : '' }}"
                                id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home"
                                role="tab" aria-controls="custom-content-below-home" aria-selected="">Counseling Form
                                Setup</a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link {{ $current_page == 'Referral Form Setup' ? 'active' : '' }}"
                                id="custom-content-below-profile-tab" data-toggle="pill"
                                href="#custom-content-below-profile" role="tab"
                                aria-controls="custom-content-below-profile" aria-selected=" ">Referral
                                form Setup</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $current_page == 'Counseling Appointments' ? 'active' : '' }}"
                                id="custom-content-below-messages-tab" data-toggle="pill"
                                href="#custom-content-below-messages" role="tab"
                                aria-controls="custom-content-below-messages" aria-selected=" ">Counseling
                                Appointments</a>
                        </li>

                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-content-below-tabContent">
                        <div class="tab-pane fade {{ $current_page == 'Referral Form Setup' ? 'show active' : '' }}"
                            id="custom-content-below-profile" role="tabpanel"
                            aria-labelledby="custom-content-below-profile-tab">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label class="mb-1">Data Privacy Clause</label>
                                    <textarea class="form-control" id="privacyclause"></textarea>
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Privacy Clause is required!</strong>
                                    </span>
                                </div>
                                <div class="form-group col-12">
                                    <label class="mb-2">Reason/s for Referral</label>
                                    <div class="listofreasons"></div>

                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control rounded-0" name="reasons" id="reasons"
                                            placeholder="Add New Reason Here">
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-info btn-flat btn_add_reason">Add</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-12">
                                    <label class="mb-1">Other Remarks</label>
                                    <div class="listofremarks"></div>

                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control rounded-0" name="remarks" id="remarks"
                                            placeholder="Add New Remarks Here">
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-info btn-flat btn_add_remark">Add</button>
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <button type="button" class="btn btn-success" id="save_setup"><i
                                    class="far fa-paper-plane mr-1"></i>
                                SAVE SETUP</button>
                        </div>
                        <div class="tab-pane fade {{ $current_page == 'Counseling Appointments' ? 'show active' : '' }}"
                            id="custom-content-below-messages" role="tabpanel"
                            aria-labelledby="custom-content-below-messages-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-primary mr-2 btn_new_appointment">
                                        <i class="fas fa-plus"></i> Add New Appointment
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table-hover  table table-striped align-middle" id="tbl_appointment"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Student No.</th>
                                            <th>Student Name</th>
                                            <th>Date Filled Up</th>
                                            <th>Date Scheduled for Counseling</th>
                                            <th>Processing Officer</th>
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

        </div>
    </div>
@endsection

@section('footerjavascript')
    <script>
        var jsonData = {!! json_encode($jsonData) !!};
        var setups
        var listofreasons = [];
        var listofremarks = [];

        $(document).ready(function() {
            // console.log(jsonSetups)
            getreferralsetup()
            load_appointments(jsonData)
            $('#btn_save_appointment').on('click', function() {
                var isvalidinputs = true;
                if (!$('#filleddate').val()) {
                    $('#filleddate').addClass('is-invalid')
                    notify('error', 'Date is required!')
                    isvalidinputs = false
                    return
                } else {
                    $('#filleddate').removeClass('is-invalid')
                }
                if (!$('#select-student').val()) {
                    $('#select-student').addClass('is-invalid')
                    notify('error', 'Name is required!')
                    isvalidinputs = false
                    return
                } else {
                    $('#select-student').removeClass('is-invalid')
                }
                if (!$('#reason').val()) {
                    $('#reason').addClass('is-invalid')
                    notify('error', 'Reason is required!')
                    isvalidinputs = false
                    return
                } else {
                    $('#reason').removeClass('is-invalid')
                }
                if (!$('#counselingdate').val()) {
                    $('#counselingdate').addClass('is-invalid')
                    notify('error', 'Appointment is required!')
                    isvalidinputs = false
                    return
                } else {
                    $('#counselingdate').removeClass('is-invalid')
                }

                if (isvalidinputs) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('store.appointment') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            studid: $('#select-student').val(),
                            studname: $('#select-student option:selected').text(),
                            counselingdate: $('#counselingdate').val(),
                            filleddate: $('#filleddate').val(),
                            reason: $('#reason').val(),
                            // takers: $('#takers').val(),
                            counselor: $('#select-counselor').val(),
                            processingofficer: 'Officer'
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status == 'success') {
                                notify(response.status, response.message)
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseJSON);
                        }
                    });
                }
            });

            // Add reason button click event
            $('.btn_add_reason').on('click', function() {
                var inputval = $('#reasons').val();
                listofreasons.push(inputval);
                renderReasons();
                console.log(inputval);
            });

            // Delete button click event
            $(document).on('click', '.delete-btn-reason', function() {
                var indexToRemove = $(this).data('index');
                listofreasons.splice(indexToRemove, 1);
                renderReasons();
            });

            $(document).on('click', '.edit-btn-reason', function() {
                var indexToEdit = $(this).data('index');
                var inputGroup = $(this).closest('.input-group');
                var inputField = inputGroup.find('input');
                inputField.prop('disabled', false).focus(); // Enable and focus the input field for editing

                // When the input field loses focus, update the value in the array
                inputField.on('blur', function() {
                    listofreasons[indexToEdit] = $(this).val();
                    inputField.prop('disabled', true); // Disable the input field after editing
                    renderReasons(); // Re-render the list with the updated value
                });
            });

            // Add reason button click event
            $('.btn_add_remark').on('click', function() {
                var inputval = $('#remarks').val();
                listofremarks.push(inputval);
                renderRemarks();
                console.log(inputval);
            });

            // Delete button click event
            $(document).on('click', '.delete-btn-remark', function() {
                var indexToRemove = $(this).data('index');
                listofremarks.splice(indexToRemove, 1);
                renderRemarks();
            });

            $(document).on('click', '.edit-btn-remark', function() {
                var indexToEdit = $(this).data('index');
                var inputGroup = $(this).closest('.input-group');
                var inputField = inputGroup.find('input');
                inputField.prop('disabled', false).focus(); // Enable and focus the input field for editing

                // When the input field loses focus, update the value in the array
                inputField.on('blur', function() {
                    listofremarks[indexToEdit] = $(this).val();
                    inputField.prop('disabled', true); // Disable the input field after editing
                    renderRemarks(); // Re-render the list with the updated value
                });
            });

            $('#save_setup').on('click', function() {
                var isvalidinputs = true;
                console.log($('#privacyclause').val().trim())
                // return
                if (!$('#privacyclause').val().trim()) {
                    isvalidinputs = false
                    notify('error', 'Privacy Clause is required!');
                    $('#privacyclause').addClass('is-invalid')
                    return
                } else {
                    $('#privacyclause').removeClass('is-invalid')
                }

                if (isvalidinputs) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('store.referralsetup') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            privacyclause: $('#privacyclause').val().trim(),
                            reasons: listofreasons.length > 0 ? listofreasons.join(',') : null,
                            remarks: listofremarks.length > 0 ? listofremarks.join(',') : null,
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status == 'success') {
                                notify(response.status, response.message)
                                getreferralsetup()
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseJSON);
                        }
                    });
                }

            });
            $('#select-student').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select Student'
            })
            $('#select-counselor').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select Counselor'
            })
            $('.btn_new_appointment').on('click', function() {
                $('#modalAddAppointment').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            })
            $('.btn_add_form').on('click', function() {
                $('#modalAddForm').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            })
        });

        function getreferralsetup() {
            $('.listofreasons').empty();
            $.ajax({
                type: 'GET',
                url: '{{ route('get.referralsetup') }}',
                success: function(response) {
                    console.log(response)
                    setups = response
                    $('#privacyclause').val(setups.privacyclause)
                    listofreasons = setups.reasons != null ? setups.reasons.split(',') : []
                    listofremarks = setups.remarks != null ? setups.remarks.split(',') : []
                    renderReasons()
                    renderRemarks()
                }
            })
        }

        function renderReasons() {
            $('.listofreasons').empty();
            listofreasons.forEach((element, index) => {
                $('.listofreasons').append(`
            <div class="input-group mb-3">
                <input type="text" class="form-control rounded-0" value="${element}" disabled>
                <span class="input-group-append">
                    <button type="button" class="btn btn-default btn-flat edit-btn-reason" data-index="${index}"><i class="far fa-edit text-primary"></i></button>
                    <button type="button" class="btn btn-default btn-flat delete-btn-reason" data-index="${index}"><i class="far fa-trash-alt text-danger"></i></button>
                </span>
            </div>`);
            });
        }

        function renderRemarks() {
            $('.listofremarks').empty();
            listofremarks.forEach((element, index) => {
                $('.listofremarks').append(`
            <div class="input-group mb-3">
                <input type="text" class="form-control rounded-0" value="${element}" disabled>
                <span class="input-group-append">
                    <button type="button" class="btn btn-default btn-flat edit-btn-remark" data-index="${index}"><i class="far fa-edit text-primary"></i></button>
                    <button type="button" class="btn btn-default btn-flat delete-btn-remark" data-index="${index}"><i class="far fa-trash-alt text-danger"></i></button>
                </span>
            </div>`);
            });
        }

        function load_appointments(data) {
            console.log(data)
            $('#tbl_appointment').DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                lengthChange: false,
                columns: [{
                        data: "sid",
                        className: 'align-middle'
                    },
                    {
                        data: "studname",
                    },
                    {
                        data: "formatted_filleddate",
                    },
                    {
                        data: 'formatted_counselingdate',
                    },
                    {
                        data: 'processingofficer',
                    },
                    {
                        data: 'status',
                        render: function(type, data, row) {
                            return `<span class="badge ${row.status === 0 ? 'badge-primary' : row.status === 1 ? 'badge-warning' : 'badge-success'}">${row.status === 0 ? 'New' : row.status === 1 ? 'Pending' : 'Done'}</span>`;
                        }
                    },
                    {
                        data: null,
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<div class="btn-group">
                                    <a type="button" href="javascript:void(0)" class="btn btn-default"> <i class="far fa-edit text-primary"></i> </a>
                                    <a type="button" href="javascript:void(0)" class="btn btn-default"> <i class="far fa-trash-alt text-danger"></i> </a> 
                                </div>`;
                        }
                    }

                ],
            })
        }
    </script>
@endsection
