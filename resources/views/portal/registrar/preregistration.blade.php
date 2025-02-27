<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pre-registation</title>

    <link href="{{asset('assets/css/gijgo.min.css')}}" rel="stylesheet" />

    <link href="{{asset('assets/css/main.css')}}" rel="stylesheet">

    <script type="text/javascript" src="{{asset('assets/scripts/jquery-3.3.1.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>

    <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>

    <script src="{{asset('assets/scripts/bootstrap.min.js')}}" ></script>

    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">


    {{-- <script type="text/javascript" src="{{ asset('js/dataTable/jquery.dataTables.min.js')}}"></script> --}}

    {{-- <script type="text/javascript" src="{{ asset('js/dataTable/dataTables.fixedColumns.min.js')}}"></script> --}}

    {{-- <link rel="stylesheet" href="{{ asset('css/dataTable/jquery.dataTables.min.css')}}" type="text/css" media="all"> --}}

    {{-- <link rel="stylesheet" href="{{ asset('css/dataTable/fixedColumns.dataTables.min.css')}}" type="text/css" media="all"> --}}

    <style>
        label{ font-size: 12px; }

        .chevron { display: inline-block; min-width: 150px; text-align: center; padding: 15px 0; margin-right: -30px; /* background: #9e5b5b  ; */ background: rgb(158,91,91); background: linear-gradient(90deg, rgba(158,91,91,1) 10%, rgba(241,168,168,1) 100%); -webkit-clip-path: polygon(0 0, 100% 0%, 75% 100%, 0% 100%); clip-path: polygon(0 0, 100% 0%, 75% 100%, 0% 100%); }

        .fixed-top{ position: sticky; padding-top: 0px; }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { /* display: none; <- Crashes Chrome on hover */ -webkit-appearance: none; margin: 0; /* <-- Apparently some margin are still there even though it's hidden */ }

        input[type=number] { -moz-appearance:textfield; /* Firefox */ }

        .card-header{ background: rgb(158,91,91); background: linear-gradient(90deg, rgba(158,91,91,1) 10%, rgba(241,168,168,1) 100%); }

        @media screen and (max-width: 1000px) {
            h1{ font-size: 20px !important; }

            .fixed-top{ position: relative; }

            .chevron { display: inline-block;  min-width: 150px; text-align: center; padding:0px; margin: 0px !important; margin-right: -30px; -webkit-clip-path: polygon(0 0, 100% 0%, 100% 100%, 0% 100%); clip-path: polygon(0 0, 100% 0%, 100% 100%, 0% 100%); }

            .next { display: inline-block; min-width: 150px; text-align: center; padding: 15px 0; margin: 0px !important; margin-right: -30px; -webkit-clip-path: polygon(0 0, 100% 0%, 100% 100%, 0% 100%); clip-path: polygon(0 0, 100% 0%, 100% 100%, 0% 100%); }
        }

    </style>
</head>
<body class="hold-transition lockscreen">
    <div class="app-container body-tabs-shadow" style="background-color: #eee">
        <div class="app-main">
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <form action="/prereg" method="POST" class="needs-validation" >
                        @csrf
                        <div class="app-page-title fixed-top">
                            <div class="page-title-wrapper " style="background-color:#bb7272 ;">
                                <div class="chevron col-md-10 col-xs-10 tag-wrap" >
                                    <div class="page-title-heading " style="padding:30px">
                                        <div class="page-title-icon" style="color:black">
                                            <i class="fa fa-align-justify" >
                                            </i>
                                        </div>
                                        <div class="text-white">
                                            <h1>Pre-Registration</h1>
                                            <div class="page-title-subheading">
                                                For queueing code recovery, please ask for assistance at the school's Computer Laboratory.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="next col-md-2 col-xs-2 ">
                                    <div class="page-title-heading " >
                                        <div class="d-inline-block dropdown">
                                            
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                            
                        </div>
                            
                        {{-- {{$name}} --}}
                        @if(isset($name)==0)
                            {{-- <div class="alert alert-danger fade show" role="alert">
                                These information already exists!
                            </div> --}}
                        @elseif(isset($name)!=false)
                        <div class="alert alert-danger fade show" role="alert">
                                <strong>{{$name}}</strong> already exist!
                                <a href="/prereg" class="close" aria-label="Close"><span aria-hidden="true">×</span></a>
                        </div>
                        @endif
                        <div class="alert alert-info fade show p-3" role="alert" id="review">
                            <div class="row">
                            <div class="col-md-12">
                                <span style="font-size:25px;">Please take time to review your form before submitting.</span>
                            <button id="subButton" class="btn-shadow btn btn-warning btn-outline-white btn-lg float-right " type="submit" >
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-upload"></i>
                                        Submit Form
                                </span>
                            </button>
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="main-card mb-3 card">
                                    <div class="card-header text-white" >
                                        Personal Information
                                    </div>
                                    <div class="card-body">
                                        <div>
                                            <div class="form-row">
                                                <div class="col-md-4">
                                                    <div class="position-relative form-group ">
                                                        <label for="fname" >First Name</label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="fname" id="fname" type="text" class="form-control form-control-sm" required>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fa fa-exclamation-circle"></i>
                                                                </span>
                                                            </div>
                                                            <div class="invalid-feedback">
                                                                This section is required!
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="position-relative form-group">
                                                        <label for="mname" class="">Middle Name</label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="mname" id="mname" type="text" class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="position-relative form-group">
                                                        <label for="lname" class="">Last Name</label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="lname" id="lname" type="text" class="form-control form-control-sm" required>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fa fa-exclamation-circle"></i>
                                                                </span>
                                                            </div>
                                                            <div class="invalid-feedback">
                                                                This section is required!
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="position-relative form-group"><label for="suffix" class="">Suffix</label><input name="suffix" id="suffix" type="text" class="form-control form-control-sm"></div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-4">
                                                    <div class="position-relative form-group">
                                                        <label for="dob" class="">Date of Birth</label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="dob" id="dob" type="date" class="form-control form-control-sm" min="1900-01-01" required>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fa fa-exclamation-circle"></i>
                                                                </span>
                                                            </div>
                                                            <div class="invalid-feedback">
                                                                This section is required!
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="position-relative form-group"><label for="gender" class="">Gender</label>
                                                        <div class="input-group input-group-sm">
                                                            <select name="gender" id="gender"  class="form-control form-control-sm" required>
                                                                <option value=""></option>
                                                                <option value="FEMALE">Female</option>
                                                                <option value="MALE">Male</option>
                                                            </select>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fa fa-exclamation-circle"></i>
                                                                </span>
                                                            </div>
                                                            <div class="invalid-feedback">
                                                                This section is required!
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- data-inputmask="'mask': ['9999-999-9999']" data-mask  --}}
                                                <div class="col-md-5">
                                                    <div class="position-relative form-group"><label for="stud_contact_no" class="">Contact No</label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="student_contact_no" type="text" id="stud_contact_num"  class="form-control form-control-sm" minlength="11" maxlength="11" required>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fa fa-exclamation-circle"></i>
                                                                </span>
                                                            </div>
                                                            <div class="invalid-feedback">
                                                                Your contact number is not valid.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="clearfix" style="border: 1px solid #2b7bbf;"></div>
                                            <br>
                                            <div class="form-row">
                                                <div class="col-md-3">
                                                    <div class="position-relative form-group"><label for="street" class="">Street</label><input name="street" id="street" type="text" class="form-control form-control-sm"></div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="position-relative form-group"><label for="barangay" class="">Barangay</label>
                                                        <input name="barangay" id="barangay" type="text" class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="position-relative form-group"><label for="city" class="">City</label>
                                                        <input name="city" id="city" type="text" class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="position-relative form-group"><label for="province" class="">Province</label>
                                                        <input name="province" id="province" type="text" class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="main-card mb-3 card">
                                    <div class="card-header" style="background-color:#2bbf74"></div>
                                    <div class="card-body">
                                        <div>                                           
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend"><span class="input-group-text">Religion</span></div>
                                                 <select name="religion" id="religion" class="form-control form-control-sm">
                                                    <option value="0" readonly></option>
                                                    @if(isset($religion))
                                                        @foreach ($religion as $showReligion)
                                                            <option value="{{$showReligion->id}}">{{$showReligion->religionname}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend"><span class="input-group-text">Blood Type</span></div>
                                                <input type="text" name="blood_type" class="form-control" >
                                            </div>
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend"><span class="input-group-text">Allergies</span></div>
                                                <textarea type="text" name="allergies" class="form-control" ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header text-white" >Parent's / Guardian's Information</div>
                                    <div class="card-body">
                                        <div>
                                            <div class="form-row">
                                                <div class="col-md-2">
                                                    <div class="position-relative form-group "><label for="father_fname" class="">Father's First Name</label><input name="father_fname" id="father_fname" type="text" class="form-control form-control-sm"></div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="position-relative form-group"><label for="father_mname" class="">Father's Middle Name</label><input name="father_mname" id="father_mname" type="text" class="form-control form-control-sm"></div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="position-relative form-group"><label for="father_lname" class="">Father's Last Name</label><input name="father_lname" id="father_lname" type="text" class="form-control form-control-sm"></div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="position-relative form-group"><label for="father_suffix" class="">Suffix</label><input name="father_suffix" id="father_suffix" type="text" class="form-control form-control-sm"></div>
                                                </div>
                                                <div class="col-md-1"></div>
                                                <div class="col-md-2">
                                                    <div class="position-relative form-group"><label for="father_contact_no" class="">Contact No</label><input name="father_contact_no" id="father_contact_no" type="number" class="form-control form-control-sm"></div>
                                                </div>
                                                <div class="col-md-2 pull-right">
                                                        <div class="position-relative form-group "><label for="father_occupation" class="">Occupation</label><input name="father_occupation" id="father_occupation" type="text" class="form-control form-control-sm"></div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="clearfix" style="border: 1px solid #2b7bbf;"></div>
                                            <br>
                                            <div class="form-row">
                                                <div class="col-md-2">
                                                    <div class="position-relative form-group "><label for="mother_fname" class="">Mother's First Name</label><input name="mother_fname" id="mother_fname" type="text" class="form-control form-control-sm"></div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="position-relative form-group"><label for="mother_mname" class=""> Mother's Middle Name</label><input name="mother_mname" id="mother_mname" type="text" class="form-control form-control-sm"></div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="position-relative form-group"><label for="mother_lname" class="">Mother's Last Name</label><input name="mother_lname" id="mother_lname" type="text" class="form-control form-control-sm"></div>
                                                </div>
                                                <div class="col-md-2"></div>
                                                <div class="col-md-2">
                                                    <div class="position-relative form-group"><label for="mother_contact_no" class="">Contact No</label><input name="mother_contact_no" id="mother_contact_no" type="number" class="form-control form-control-sm"></div>
                                                </div>
                                                <div class="col-md-2 pull-right">
                                                        <div class="position-relative form-group "><label for="mother_occupation" class="">Occupation</label><input name="mother_occupation" id="mother_occupation" type="text" class="form-control form-control-sm"></div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="clearfix" style="border: 1px solid #2b7bbf;"></div>
                                            <br>
                                            <div class="form-row">
                                                <div class="col-md-2">
                                                    <div class="position-relative form-group "><label for="guardian_fname" class="">Guardian's First Name</label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="guardian_fname" id="guardian_fname" type="text" class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="position-relative form-group"><label for="guardian_mname" class="">Guardian's Middle Name</label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="guardian_mname" id="guardian_mname" type="text" class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="position-relative form-group"><label for="guardian_lname" class="">Guardian's Last Name</label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="guardian_lname" id="guardian_lname" type="text" class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2"></div>
                                                <div class="col-md-2">
                                                    <div class="position-relative form-group"><label for="guardian_contact_no" class="">Contact No</label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="guardian_contact_no" id="guardian_contact_no" type="number" class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 pull-right">
                                                        <div class="position-relative form-group "><label for="guardian_relation" class="">Relation</label><input name="guardian_relation" id="guardian_relation" type="text" class="form-control form-control-sm"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <button class="btn btn-warning btn-block" id="previewValidate"><h2>Save</h2></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    
    {{-- <div class="lockscreen-wrapper">
        <div class="lockscreen-logo">
          <a href="../../index2.html"><b>Admin</b>LTE</a>
        </div>
        <!-- User name -->
        <div class="lockscreen-name">John Doe</div>
      
        <!-- START LOCK SCREEN ITEM -->
        <div class="lockscreen-item">
          <!-- lockscreen image -->
          <div class="lockscreen-image">
            <img src="../../dist/img/user1-128x128.jpg" alt="User Image">
          </div>
          <!-- /.lockscreen-image -->
      
          <!-- lockscreen credentials (contains the form) -->
          <form class="lockscreen-credentials">
            <div class="input-group">
              <input type="password" class="form-control" placeholder="password">
      
              <div class="input-group-append">
                <button type="button" class="btn"><i class="fas fa-arrow-right text-muted"></i></button>
              </div>
            </div>
          </form>
          <!-- /.lockscreen credentials -->
      
        </div>
        <!-- /.lockscreen-item -->
        <div class="help-block text-center">
          Enter your password to retrieve your session
        </div>
        <div class="text-center">
          <a href="login.html">Or sign in as a different user</a>
        </div>
        <div class="lockscreen-footer text-center">
          Copyright © 2014-2019 <b><a href="http://adminlte.io" class="text-black">AdminLTE.io</a></b><br>
          All rights reserved
        </div>
      </div> --}}
    </div>
    <script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
    <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script>
        $('.modal').hide();
        $('#review').hide();
        $('#previewValidate').show();
        (function($) {
            $.fn.inputFilter = function(inputFilter) {
                return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                    this.value = "";
                }
                });
            };
        }(jQuery));
        $(document).ready(function() {
            $('#review').hide();
            $("#stud_contact_num").inputFilter(function(value) {
                return /^\d*$/.test(value);    // Allow digits only, using a RegExp
            });
            dob.max = new Date().toISOString().split("T")[0];
        });
        function refreshPage(){
            window.location.reload();
        } 
    // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    $('#previewValidate').on('click',function(){
                        $("html").scrollTop(0);
                        $('#review').show();
                        $('#previewValidate').hide();
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    })
                });
            }, false);
        })();

    </script>
</body>
</html>
{{-- @endsection --}}
