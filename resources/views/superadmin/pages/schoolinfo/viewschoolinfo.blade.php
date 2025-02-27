@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css') }}">
    <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <style>
        .dropdown-toggle::after {
            display: none;
            margin-left: .255em;
            vertical-align: .255em;
            content: "";
            border-top: .3em solid;
            border-right: .3em solid transparent;
            border-bottom: 0;
            border-left: .3em solid transparent;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1>School Information Setup</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">School Info</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content pt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div>
                                        <label for="">Database name</label>
                                        <input class="form-control form-control-sm" readonly value="{{ $databasename }}">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <form action="{{ isset($schoolinfo) ? '/updateschoolinfo' : '/insertinfo' }}" id="update_info"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-9">
                                        
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="">School Email</label>
                                                <input type="email" name="schoolemail" class="form-control form-control-sm"
                                                    value="{{ $schoolinfo->schoolemail }}">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="">School ID</label>
                                                <input name="schoolid" class="form-control form-control-sm"
                                                    value="{{ $schoolinfo->schoolid }}">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="">Abbreviation</label>
                                                <input name="abbreviation" class="form-control form-control-sm"
                                                    value="{{ $schoolinfo->abbreviation }}">
                                            </div>
                                            <div class="form-group col-md-8">
                                                <label for="">School Name</label>
                                                <input name="schoolname" class="form-control form-control-sm"
                                                    value="{{ $schoolinfo->schoolname }}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="">School Region</label>
                                                <input name="region" class="form-control form-control-sm"
                                                    value="{{ $schoolinfo->regiontext }}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="">School Division</label>
                                                <input name="division" class="form-control form-control-sm"
                                                    value="{{ $schoolinfo->divisiontext }}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="">School District</label>
                                                <input name="district" class="form-control form-control-sm"
                                                    value="{{ $schoolinfo->districttext }}">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="">School Address</label>
                                                <input name="address" class="form-control form-control-sm"
                                                    value="{{ $schoolinfo->address }}">
                                            </div>
                                            <div class="form-group col-md-12 ">
                                                <label for="">School Tagline</label>
                                                <textarea name="schooltagline" class="form-control text-area" rows="3">{{ $schoolinfo->tagline }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for=""><b>School Logo</b></label>
                                            <input type="file" name="schoollogo" id="schoollogo"
                                                class="form-control @error('schoollogo') is-invalid @enderror">
                                            @if ($errors->has('schoollogo'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('schoollogo') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        @if (isset($schoolinfo->picurl))
                                            <img id="logoDisplay" src="{{ asset($schoolinfo->picurl) }}" alt=""
                                                class="w-100">
                                        @else
                                            <img id="logoDisplay" src="{{ asset($schoolinfo->picurl) }}" alt=""
                                                class="w-100">
                                        @endif
                                    </div>

                                    <div class="col-md-12">
                                        <button class="btn btn-primary btn-sm"><i class="far fa-edit"></i> Update School
                                            Information</button>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <div class="row form-group">
                                <div class=" col-md-2">
                                    <label for="">Lock Fees</label>
                                    <br>
                                    <input type="checkbox" name="lockfees" class="update_module" data-bootstrap-switch>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Accounting</label>
                                    <br>
                                    <input type="checkbox" name="accountingmodule" class="update_module"
                                        data-bootstrap-switch>
                                </div>
                                <div class="col-md-2">
                                    <label for="">SHS Setup</label>
                                    <br>
                                    <input type="checkbox" name="shssetup" class="update_module" data-bootstrap-switch>
                                </div>
                                <div class="col-md-2">
                                    <label for="">ESC</label>
                                    <br>
                                    <input type="checkbox" name="withESC" class="update_module" data-bootstrap-switch>
                                </div>
                                <div class="col-md-2">
                                    <label for="">MOL</label>
                                    <br>
                                    <input type="checkbox" name="withMOL" class="update_module" data-bootstrap-switch>
                                </div>
                                <div class="col-md-2">
                                    <label for="">School Folder</label>
                                    <br>
                                    <input type="checkbox" name="withschoolfolder" class="update_module"
                                        data-bootstrap-switch>
                                </div>
                            </div>
                            <div class="row">
                                <div class=" col-md-2">
                                    <label for="">Online Payt</label>
                                    <br>
                                    <input type="checkbox" name="onlinepayment" class="update_module"
                                        data-bootstrap-switch>
                                </div>
                                <div class=" col-md-2">
                                    <label for="">Teacher Eval.</label>
                                    <br>
                                    <input type="checkbox" name="teachereval" class="update_module"
                                        data-bootstrap-switch>
                                </div>
                                {{-- <div class=" col-md-2">
                                    <label for="">Accounting</label>
                                    <br>
                                    <input type="checkbox" name="accountingmodule" class="update_module"
                                        data-bootstrap-switch>
                                </div> --}}
                                <div class=" col-md-2">
                                    <label for="">Teacher SF10</label>
                                    <br>
                                    <input type="checkbox" name="teachersf10" class="update_module"
                                        data-bootstrap-switch>
                                </div>
                                <div class=" col-md-2">
                                    <label for="">MS Teams</label>
                                    <br>
                                    <input type="checkbox" name="withmsteams" class="update_module"
                                        data-bootstrap-switch>
                                </div>
                                <div class=" col-md-2">
                                    <label for="">RFID Renewal</label>
                                    <br>
                                    <input type="checkbox" name="RFIDRenewal" class="update_module"
                                        data-bootstrap-switch>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class=" col-md-2">
                                    <label for="">Admission</label>
                                    <br>
                                    <input type="checkbox" name="admission" class="update_module" data-bootstrap-switch>
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class=" row mb-0 col-md-12 pt-2 pb-2 pr-0 ">
                                    <label for="inputEmail3" class="col-md-3 col-form-label">
                                        Admin IT Password
                                    </label>
                                    <div class="col-md-9 p-0 input-group">
                                        <input class="form-control " id="password" placeholder="Admin IT Password"
                                            value="{{ isset(collect($admin_pass)->where('type', 6)->first()->passwordstr) ? collect($admin_pass)->where('type', 6)->first()->passwordstr : '' }}">
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-primary btn-sm update_adminpass"
                                                date-id="6" id="generateAdmin"><i class="far fa-edit"></i>
                                                GENERATE</button>
                                        </span>
                                    </div>
                                </div>
                                <div class=" row mb-0 col-md-12 pt-2 pb-2 pr-0 border-bottom border-top">
                                    <label for="inputEmail3" class="col-md-3 col-form-label">
                                        Admin Admin Password
                                    </label>
                                    <div class="col-md-9 p-0 input-group">
                                        <input class="form-control " id="adminadmin_password"
                                            placeholder="Admin IT Password"
                                            value="{{ isset(collect($admin_pass)->where('type', 12)->first()->passwordstr) ? collect($admin_pass)->where('type', 12)->first()->passwordstr : '' }}">
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-primary btn-sm update_adminpass"
                                                id="generateAdminAdmin"><i class="far fa-edit"></i> GENERATE</button>
                                        </span>
                                    </div>
                                </div>
                                <div id="updateschoolcolor"
                                    class="form-group row col-md-12 pt-2 pb-2 border-bottom pr-0 border-bottom">
                                    <label for="inputEmail3" class="col-md-3 col-form-label">School Color</label>
                                    <div class="input-group col-md-9 p-0">
                                        <input class="form-control"
                                            name="schoolcolor"value="{{ $schoolinfo->schoolcolor }}">
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-primary btn-sm updateinfo"
                                                data-id="schoolcolor"><i class="far fa-edit"></i> UPDATE</button>
                                        </span>
                                    </div>
                                </div>
                                <div id="updateschoolwebsite"
                                    class="form-group row col-md-12  pb-2 border-bottom pr-0 border-bottom">
                                    <label for="inputEmail3" class="col-md-3 col-form-label">School Website</label>
                                    <div class="input-group col-md-9 p-0">
                                        <input class="form-control" name="schoolwebsite"
                                            value="{{ $schoolinfo->websitelink }}">
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-primary btn-sm updateinfo"
                                                data-id="schoolwebsite"><i class="far fa-edit"></i> UPDATE</button>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group row col-md-12  pb-2 border-bottom pr-0 border-bottom">
                                    <label for="inputEmail3" class="col-md-3 col-form-label">ES Cloud URL</label>
                                    <div class="input-group col-md-9 p-0">
                                        <input class="form-control" name="escloudurl"
                                            value="{{ $schoolinfo->es_cloudurl }}">
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-primary btn-sm updateinfo"
                                                data-id="escloudurl"><i class="far fa-edit"></i> UPDATE</button>
                                        </span>
                                    </div>
                                </div>

                                <div class="card-body pad p-2 col-md-12">
                                    <label>Terms and Agreements</label>
                                    <div class="mb-3">
                                        <textarea class="textarea" placeholder="Place some text here" name="schoolterms" 2.>{!! html_entity_decode($schoolinfo->terms) !!}</textarea>
                                        <button type="button" class="btn btn-primary btn-sm updateinfo"
                                            data-id="schoolterms"><i class="far fa-edit"></i> UPDATE TERMS AND
                                            AGREEMENTS</button>
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
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>

    <script>
        $(function() {
            $('.textarea').summernote({
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'hr']],
                    ['view', ['fullscreen', 'codeview']],
                    ['help', ['help']],
                    ['fontsize', ['fontsize']],
                ],
            })
        })
    </script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#logoDisplay').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#schoollogo").change(function() {
            readURL(this);
        });


        $('#update_info')
            .submit(function(e) {

                var inputs = new FormData(this)
                $.ajax({
                    url: '/updateschoolinfo',
                    type: 'POST',
                    data: inputs,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                })

                // inputs.append('_token', "{{ csrf_token() }}");

                // $.ajax( {

                //       url: link,
                //       type: 'POST',
                //       data: inputs,
                //       processData: false,
                //       contentType: false,
                //       success:function(data) {

                //       }
                // })

                e.preventDefault();
            })
    </script>

    <script>
        var schoolinfo = @json($schoolinfo);

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        })

        $(document).on('switchChange.bootstrapSwitch', '.update_module', function() {
            var setupmodule = $(this).attr('name')
            var status = 0;
            if ($(this).prop('checked')) {
                status = 1
            }
            update_information(setupmodule, status)
        })

        function update_information(field, value) {
            $.ajax({
                type: 'POST',
                url: '/sysadmin/updatemodulestatus',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    status: value,
                    module: field
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }

                }
            })
        }

        if (schoolinfo.lockfees == 1) {
            $('.update_module[name="lockfees"]').attr('checked', 'checked')
        }

        if (schoolinfo.accountingmodule == 1) {
            $('.update_module[name="accountingmodule"]').attr('checked', 'checked')
        }

        if (schoolinfo.shssetup == 1) {
            $('.update_module[name="shssetup"]').attr('checked', 'checked')
        }

        if (schoolinfo.withESC == 1) {
            $('.update_module[name="withESC"]').attr('checked', 'checked')
        }

        if (schoolinfo.withMOL == 1) {
            $('.update_module[name="withMOL"]').attr('checked', 'checked')
        }

        if (schoolinfo.withschoolfolder == 1) {
            $('.update_module[name="withschoolfolder"]').attr('checked', 'checked')
        }

        if (schoolinfo.onlinepayment == 1) {
            $('.update_module[name="onlinepayment"]').attr('checked', 'checked')
        }

        if (schoolinfo.teachereval == 1) {
            $('.update_module[name="teachereval"]').attr('checked', 'checked')
        }

        if (schoolinfo.accountingmodule == 1) {
            $('.update_module[name="accountingmodule"]').attr('checked', 'checked')
        }

        if (schoolinfo.teachersf10 == 1) {
            $('.update_module[name="teachersf10"]').attr('checked', 'checked')
        }


        if (schoolinfo.withmsteams == 1) {
            $('.update_module[name="withmsteams"]').attr('checked', 'checked')
        }

        if (schoolinfo.RFIDRenewal == 1) {
            $('.update_module[name="RFIDRenewal"]').attr('checked', 'checked')
        }
        if (schoolinfo.admission == 1) {
            $('.update_module[name="admission"]').attr('checked', 'checked')
        }

        $("input[data-bootstrap-switch]").each(function() {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });

        $(document).on('click', '.updateinfo', function() {
            if ($(this).attr('data-id') == 'schoolcolor') {
                update_information('schoolcolor', $('input[name="schoolcolor"]').val())
            } else if ($(this).attr('data-id') == 'schoolwebsite') {
                update_information('websitelink', $('input[name="schoolwebsite"]').val())
            } else if ($(this).attr('data-id') == 'schoolterms') {
                update_information('terms', $('textarea[name="schoolterms"]').val())
            } else if ($(this).attr('data-id') == 'escloudurl') {
                update_information('es_cloudurl', $('input[name="escloudurl"]').val())
            }

        })
        $(document).on('click', '#generateAdmin', function() {

            console.log("sdfsdf")
            Swal.fire({
                    title: 'Are you sure?',
                    text: "This will override the ADMIN password.",
                    type: 'primary',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'GENERATE'
                })
                .then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'GET',
                            url: '/generateAdminPass',
                            success: function(data) {
                                $('#password').val(data[0].code)
                                $('#hashed').val(data[0].hash)
                            }
                        })
                    }
                })
        })


        $(document).on('click', '#generateAdminAdmin', function() {

            Swal.fire({
                    title: 'Are you sure?',
                    text: "This will override the ADMIN ADMIN password.",
                    type: 'primary',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'GENERATE'
                })
                .then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'GET',
                            url: '/generateAdminAdminPass',
                            success: function(data) {
                                $('#adminadmin_password').val(data[0].code)
                                $('#hashed').val(data[0].hash)
                            }
                        })
                    }

                })

        })

        $(document).on('click', '#udpateckpass', function() {

            Swal.fire({
                    title: 'Are you sure?',
                    text: "This will revert the ckgroup password to default.",
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'UPDATE'
                })
                .then((result) => {

                    if (result.value) {

                        $.ajax({
                            type: 'GET',
                            url: '/generateckpass',
                            success: function(data) {

                                Swal.fire({
                                    type: 'success',
                                    title: 'Update Successfully',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            }
                        })

                    }

                })

        })
    </script>
@endsection
