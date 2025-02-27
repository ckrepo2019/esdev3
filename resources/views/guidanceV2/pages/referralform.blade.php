{{-- @extends('guidanceV2.layouts.app2') --}}

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
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Fill Up Form </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item "> <a href="/home">Home</a> </li>
                        <li class="breadcrumb-item "> <a href="/guidance/referral">Referral</a> </li>
                        <li class="breadcrumb-item active">{{ $current_page }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title"><i class="far fa-paper-plane mr-1"></i> Referral Form</h5>
                </div>
                <div class="card-body">
                    <div class="p-2" style="border: 1px solid rgb(204, 201, 201); border-radius: 2px;">
                        <span><strong>Data Privacy Clause:</strong></span> <em id="privacyclause"></em>
                    </div>

                    <div class="row justify-content-end mt-3">
                        <div class="form-group col-md-3">
                            <label class="mb-1">Date of Filling</label>
                            <input class="form-control" id="filleddate" type="date">
                        </div>
                    </div>

                    <label class="">NAME OF REFERRED STUDENT</label>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="" class="mb-1">Search Name</label>
                            <select name="" id="select_stud" class="form-control" style="width:100%;">
                                <option value="">Select Student</option>
                                @foreach (DB::table('studinfo')->get() as $item)
                                    <option value="{{ $item->id }}"> {{ $item->sid }} - {{ $item->lastname }},
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
                                <input class="form-control" id="sy" value="{{ $sy->sydesc }}" type="text"
                                    disabled>
                            </div>
                        </div>
                    </div>

                    <label class="mt-2"> REASON/S FOR REFERRAL </label>
                    <div class="row listofreasons"></div>
                    <div class="row listofremarks"></div>

                    <div class="row mt-4">
                        <div class="form-group col-md-4">
                            <label for="" class="mb-1">Referred by</label>
                            {{-- <input type="text" class="form-control"> --}}
                            <select name="" id="select_teacher" class="form-control" style="width:100%;">
                                <option value="">Select Person</option>
                                @foreach (DB::table('teacher')->where('deleted', 0)->get() as $item)
                                    <option value="{{ $item->id }}"> {{ $item->lastname }},
                                        {{ $item->firstname }} </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="" class="mb-1">Position/Designation</label>
                            <input type="text" id="position" class="form-control">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="" class="mb-1">Email/Contact No.</label>
                            <input type="text" id="phone" class="form-control">
                        </div>
                    </div>
                    <strong> NOTE: <em class="text-danger"> Please be informed that, when necessary, we will
                            contact you for
                            more
                            information regarding your referral. </em>
                    </strong>
                </div>
                <div class="card-footer justify-content-between">
                    <a type="button" class="btn btn-default" href="/guidance/referral">Cancel</a>
                    <button type="button" class="btn btn-primary" id="submitBtn">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
        var listofreasons = [];
        var listofremarks = [];
        var checkedItems = [];
        $(document).ready(function() {
            getreferralsetup()
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
        })

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
                    $('#phone').val(response.phonenumber ?? 'Not Specified')
                }
            })
        }

        function getreferralsetup() {
            $.ajax({
                type: 'GET',
                url: '{{ route('get.referralsetup') }}',
                success: function(response) {
                    console.log(response)
                    $('#privacyclause').text(response.privacyclause)
                    listofreasons = response.reasons != null ? response.reasons.split(',') : []
                    listofremarks = response.remarks != null ? response.remarks.split(',') : []

                    $('.listofreasons').empty()
                    listofreasons.forEach((element, index) => {
                        $('.listofreasons').append(
                            `<div class="form-group col-12 ">
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" id="checkbox_reason${index}">  
                                    <label for="checkbox_reason${index}">
                                        ${element}
                                    </label>
                                </div>
                            <input type="text" class="form-control" id="input_reason${index}" placeholder="Please describe further...">
                        </div>`
                        )

                    });

                    $('.listofremarks').empty()
                    listofremarks.forEach((element, index) => {
                        $('.listofremarks').append(
                            `<div class="form-group col-12 ">
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" id="checkbox_remark${index}">  
                                    <label for="checkbox_remark${index}">
                                        ${element}
                                    </label>
                                </div>
                            <input type="text" class="form-control" placeholder="Please describe further...">
                        </div>`
                        )
                    });


                    $('#submitBtn').on('click', function() {
                        var isvalidinputs = true
                        checkedItems = []

                        if (!$('#filleddate').val()) {
                            notify('error', 'Filled Date is required!')
                            $('#filleddate').addClass('is-invalid')
                            isvalidinputs = false
                            return
                        } else {
                            $('#filleddate').removeClass('is-invalid')
                        }

                        if (!$('#select_stud').val()) {
                            notify('error', 'Select Student Pls')
                            $('#select_stud').addClass('is-invalid')
                            isvalidinputs = false
                            return
                        } else {
                            $('#select_stud').removeClass('is-invalid')
                        }

                        $('.listofreasons .form-group').each(function(index) {
                            var checkboxId = 'checkbox_reason' + index;
                            var inputId = 'input_reason' + index;

                            // Check if the checkbox is checked
                            if ($('#' + checkboxId).is(':checked')) {
                                var checkboxText = $('#' + checkboxId).next('label').text()
                                    .trim(); // Get the checkbox text and trim whitespace
                                var inputValue = $('#' + inputId).val(); // Get the input value

                                // Store the checked checkbox text and input value in the checkedItems array
                                checkedItems.push({
                                    checkboxText: checkboxText,
                                    inputValue: inputValue
                                });
                            }
                        });
                        console.log(checkedItems);

                        if (checkedItems.length < 1) {
                            notify('warning', 'Choose 1 or more Reasons!');
                            isvalidinputs = false
                            return
                        }

                        if (!$('#select_teacher').val()) {
                            notify('error', 'Referred Person is required!')
                            $('#select_teacher').addClass('is-invalid')
                            isvalidinputs = false
                            return
                        } else {
                            $('#select_teacher').removeClass('is-invalid')
                        }

                        if (isvalidinputs) {
                            $.ajax({
                                type: 'POST',
                                url: '{{ route('store.referral') }}',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    studid: $('#select_stud').val(),
                                    reasons: JSON.stringify(checkedItems),
                                    referredby: $('#select_teacher').val(),
                                    studname: $('#lastname').val() + " " + $('#firstname')
                                        .val(),
                                    // counselingdate: $('#counselingdate').val(),
                                    filleddate: $('#filleddate').val(),
                                    // counselor: $('#select-counselor').val(),
                                    // processingofficer: 'Sample Officer'
                                },
                                success: function(response) {
                                    console.log(response);
                                    if (response.status == 'success') {
                                        notify(response.status, response.message)
                                        // getreferralsetup()
                                        window.history.back();
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.log(xhr.responseJSON);
                                }
                            });
                        }

                    });

                }
            })
        }

        function notify(status, message) {
            Toast.fire({
                type: status,
                title: message,
            });
        }
    </script>
@endsection
