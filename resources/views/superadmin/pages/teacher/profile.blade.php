@php

    $check_refid = DB::table('usertype')
        ->where('id', Session::get('currentPortal'))
        ->select('refid', 'resourcepath')
        ->first();

    if (Session::get('currentPortal') == 14) {
        $extend = 'deanportal.layouts.app2';
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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/croppie/croppie.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        /* .shadow {
                              box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                              border: 0;
                        } */

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }

        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
            border: 0 !important;
        }

        .shadow-none {
            box-shadow: none !important;
            border: 1px solid rgb(224, 222, 222) !important;
        }
    </style>
@endsection

@section('content')
    @php
        $nationality = DB::table('nationality')->get();
    @endphp

    <div class="modal fade" id="image-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">CHANGE PHOTO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="demo"></div>
                            <input type="file" name="studpic" id="studpic" class="form-control mb-2"
                                accept=".png, .jpg, .jpeg" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="mt-4"><i><b>Allowed File Type:</b> png, jpg, jpeg</i></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button id="updateimage" class="btn btn-info savebutton">Update</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="profile-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title">Update Profile</h4>
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button> --}}
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="">First Name</label>
                            <input id="input_fname" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="">Middle Name</label>
                            <input id="input_mname" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="">Last Name</label>
                            <input id="input_lname" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="">Suffix</label>
                            <input id="input_suffix" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="">Date of Birth</label>
                            <input id="input_dob" type="date" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="">Gender</label>
                            <select class="form-control form-control-sm select2" id="input_gender">
                                <option value="">Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="">Nationality</label>
                            <select class="form-control form-control-sm select2" id="input_nationality">
                                <option value="">Nationality</option>
                                @foreach ($nationality as $item)
                                    <option value="{{ $item->id }}">{{ $item->nationality }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="">Marital Status</label>
                            <select class="form-control form-control-sm select2" id="input_maritalstatus">
                                <option value="">Marital Status</option>
                                <option value="1">Single</option>
                                <option value="2">Married</option>
                                <option value="3">Divorced</option>
                                <option value="4">Separated</option>
                                <option value="5">Widowed</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="">Mobile Number</label>
                            <input id="input_contact" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-5 form-group">
                            <label for="">Email</label>
                            <input id="input_email" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Address</label>
                            <input id="input_address" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button id="update_profile_button" class="btn btn-primary btn-sm">Update</button>
                </div>
            </div>
        </div>
    </div>

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">My Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content pt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="card shadow">
                        <div class="card-body box-profile">
                            <div class="text-center" id="image_holder">
                            </div>
                            <p></p>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Account ID</b> <a class="float-right" id="label_tid"></a>
                                </li>
                            </ul>
                            {{-- <button
                                class="btn btn-secondary btn-block mt-2"><b>Please contact the admin to change your profile picture.</b></button> --}}
                            <button data-toggle="modal" data-target="#image-modal"
                                class="btn btn-primary btn-block mt-2"><b>Update Profile Picture</b></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h5>Personal Information</h5>
                                </div>
                                <div class="col-md-6 mb-3 text-right">
                                    {{-- <button hidden class="btn btn-primary btn-sm" id="profile_modal_button">Update Profile</button> --}}
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
                                    <strong><i class="fas fa-book mr-1"></i>Marital Status</strong>
                                    <p class="text-muted" id="maritalstatus">--</p>
                                </div>
                                <div class="col-md-3">
                                    <strong><i class="fas fa-book mr-1"></i>Mobile Number</strong>
                                    <p class="text-muted" id="contact_number">--</p>
                                </div>
                                <div class="col-md-4">
                                    <strong><i class="fas fa-book mr-1"></i>Email Address</strong>
                                    <p class="text-muted" id="email">--</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong><i class="fas fa-book mr-1"></i>Address</strong>
                                    <p class="text-muted" id="address">--</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h5>Account Information</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong><i class="fas fa-book mr-1"></i>User Type</strong>
                                    <p class="text-muted" id="utype">--</p>
                                </div>
                                <div class="col-md-8">
                                    <strong><i class="fas fa-book mr-1"></i>Academic Program</strong>
                                    <p class="text-muted" id="acadprog">--</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <strong><i class="fas fa-book mr-1"></i>Privilege User</strong>
                                    <p class="text-muted" id="privuser">--</p>
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
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/croppie/croppie.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function() {


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $uploadCrop = $('#demo').croppie({
                enableExif: true,
                viewport: {
                    width: 304,
                    height: 289,
                },

                boundary: {
                    width: 304,
                    height: 289
                }
            });

            $("#studpic").change(function() {
                var selectedFile = this.files[0];
                var idxDot = selectedFile.name.lastIndexOf(".") + 1;
                var extFile = selectedFile.name.substr(idxDot, selectedFile.name.length).toLowerCase();
                if (extFile == "jpg" || extFile == "jpeg" || extFile == "png") {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $uploadCrop.croppie('bind', {
                            url: e.target.result
                        }).then(function() {
                            console.log('jQuery bind complete');
                        });
                    }
                    reader.readAsDataURL(this.files[0]);
                } else {
                    Swal.fire({
                        title: 'INVALID FORMAT',
                        type: 'error',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    $(this).val('')
                }
            });

            $(document).on('click', '#updateimage', function(ev) {
                $uploadCrop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function(resp) {
                    $.ajax({
                        url: "/teacher/profile/update/photo",
                        type: "POST",
                        data: {
                            "image": resp,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data[0].status == 0) {
                                $('#studpic').addClass('is-invalid')
                                $('.invalid-feedback').removeAttr('hidden')
                                $('.invalid-feedback')[0].innerHTML = '<strong>' + data[
                                    0].errors.image[0] + '</strong>'
                            } else {
                                window.location.reload(true);
                            }
                        },
                    });
                });
            });

            $(document).on('click', '#update_student_info', function() {
                $('#update_student_info_modal').modal()
            })
        })
    </script>
    <script>
        $(document).ready(function() {


            $("#input_contact").inputmask({
                mask: "9999-999-9999"
            });

            var teach_profile = []

            $('.select2').select2()

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })

            $(document).on('click', '#profile_modal_button', function() {
                $('#input_fname').val(teach_profile.firstname)
                $('#input_lname').val(teach_profile.lastname)
                $('#input_mname').val(teach_profile.middlename)
                $('#input_suffix').val(teach_profile.suffix)
                $('#input_dob').val(teach_profile.dob_format1)
                $('#input_gender').val(teach_profile.gender).change()
                $('#input_maritalstatus').val(teach_profile.maritalstatusid).change()
                $('#input_nationality').val(teach_profile.nationalityid).change()
                $('#input_contact').val(teach_profile.contactnum)
                $('#input_email').val(teach_profile.email)
                $('#input_address').val(teach_profile.address)

                $('#profile-modal').modal()
            })

            $(document).on('click', '#update_profile_button', function() {

                $.ajax({
                    type: 'GET',
                    url: '/teacher/profile/update/profile',
                    data: {
                        firstname: $('#input_fname').val(),
                        lastname: $('#input_lname').val(),
                        middlename: $('#input_mname').val(),
                        suffix: $('#input_suffix').val(),
                        nationalid: $('#input_nationality').val(),
                        dob: $('#input_dob').val(),
                        gender: $('#input_gender').val(),
                        email: $('#input_email').val(),
                        address: $('#input_address').val(),
                        maritalstat: $('#input_maritalstatus').val(),
                        contactno: $('#input_contact').val(),
                    },
                    success: function(data) {
                        if (data[0].status == 0) {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        } else if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                            get_profile()
                        }
                    }
                })

            })



            get_profile()

            function get_profile() {

                $.ajax({
                    type: 'GET',
                    url: '/teacher/profile/get',
                    success: function(data) {

                        teach_profile = data[0]

                        $('#first_name').text(data[0].firstname != null ? data[0].firstname : '--')
                        $('#middle_name').text(data[0].middlename != null ? data[0].middlename : '--')
                        $('#last_name').text(data[0].lastname != null ? data[0].lastname : '--')
                        $('#suffix').text(data[0].suffix != null ? data[0].suffix : '--')
                        $('#dob').text(data[0].dob != null ? data[0].dob : '--')
                        $('#gender').text(data[0].gender != null ? data[0].gender.toString()
                            .toUpperCase() : '--')
                        $('#nationality').text(data[0].nationality != null ? data[0].nationality : '--')
                        $('#contact_number').text(data[0].contactnum != null ? data[0].contactnum :
                            '--')
                        $('#email').text(data[0].email != null ? data[0].email : '--')
                        $('#address').text(data[0].address != null ? data[0].address : '--')
                        $('#maritalstatus').text(data[0].maritalstatus != null ? data[0].maritalstatus :
                            '--')
                        $('#label_tid').text(data[0].tid)
                        $('#utype').text(data[0].utype != null ? data[0].utype : '--')

                        var acadprog = '';
                        $.each(data[0].acadprog, function(a, b) {
                            acadprog += '<span class="badge badge-primary mr-2">' + b.progname +
                                '</span>'
                        })
                        $('#acadprog')[0].innerHTML = acadprog

                        var privuser = '';
                        $.each(data[0].faspriv, function(a, b) {
                            privuser += '<span class="badge badge-primary mr-2">' + b.utype +
                                '</span>'
                        })
                        $('#privuser')[0].innerHTML = privuser





                        var onerror_url = @json(asset('dist/img/download.png'));
                        var picurl = data[0].picurl.replace('jpg', 'png') + "?random=" + new Date()
                            .getTime()
                        var image = '<img width="100%" src="/' + picurl + '" onerror="this.src=\'' +
                            onerror_url + '\'" alt="" class="img-circle img-fluid" >'
                        $('#image_holder')[0].innerHTML = image


                    }
                })
            }
        })
    </script>

    <script>
        $(document).ready(function() {

            var keysPressed = {};

            document.addEventListener('keydown', (event) => {
                keysPressed[event.key] = true;
                if (keysPressed['p'] && event.key == 'v') {
                    Toast.fire({
                        type: 'warning',
                        title: 'Date Version: 05/04/2022'
                    })
                }
            });

            document.addEventListener('keyup', (event) => {
                delete keysPressed[event.key];
            });


            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })

            $(document).on('input', '#per', function() {
                if ($(this).val() > 100) {
                    $(this).val(100)
                    Toast.fire({
                        type: 'warning',
                        title: 'Subject percentage exceeds 100!'
                    })
                }
            })
        })
    </script>
@endsection
