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

  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">

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
                                            <h4>ENROLLMENT FORM FOR SENIOR HIGH SCHOOL</h4>
                                            <h5>GRADE 11 STUDENTS ONLY</h5>
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
                            <div class="col-md-12">
                                <div class="card">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="width:50%">Grade Level:</th>
                                                <th style="width:15%;">Sex:</th>
                                                <th>Previous School</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label>Previous School</label>
                                                    <input type="text" name="schoolname" class="form-control form-control-sm"/>
                                                </td>
                                                <td>
                                                    <div class="form-group clearfix">
                                                        <label for="radioPrimary1">
                                                          Male
                                                        </label>
                                                        <div class="icheck-primary d-inline">
                                                          <input type="radio" id="radioPrimary1" name="gender" checked="">
                                                          <label for="radioPrimary1">
                                                          </label>
                                                        </div>
                                                        <label for="radioPrimary2">
                                                          Female
                                                        </label>
                                                        <div class="icheck-primary d-inline">
                                                          <input type="radio" id="radioPrimary2" name="gender">
                                                          <label for="radioPrimary2">
                                                          </label>
                                                        </div>
                                                      </div>
                                                </td>
                                                <td>
                                                    
                                                    <div class="form-group clearfix">
                                                        <label for="radioPrimary1">
                                                            Public  
                                                        </label>
                                                        <div class="icheck-primary d-inline">
                                                          <input type="radio" id="radioPrimary1" name="school_type" checked="">
                                                          <label for="radioPrimary1">
                                                          </label>
                                                        </div>
                                                        <label for="radioPrimary2">
                                                            Private 
                                                        </label>
                                                        <div class="icheck-primary d-inline">
                                                          <input type="radio" id="radioPrimary2" name="school_type">
                                                          <label for="radioPrimary2">
                                                          </label>
                                                        </div>
                                                      </div>
                                                      {{-- <br> --}}
                                                      <div class="form-group clearfix">
                                                          <label for="radioPrimary1">
                                                            ESC GRANTEE   
                                                          </label>
                                                          <div class="icheck-primary d-inline">
                                                            <input type="radio" id="radioPrimary1" name="grantee" checked="">
                                                            <label for="radioPrimary1">
                                                            </label>
                                                          </div>
                                                          <label for="radioPrimary2">
                                                            NONE ESC GRANTEE 
                                                          </label>
                                                          <div class="icheck-primary d-inline">
                                                            <input type="radio" id="radioPrimary2" name="grantee">
                                                            <label for="radioPrimary2">
                                                            </label>
                                                          </div>
                                                        </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
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
                                            <div class="form-row">
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="mname" class="">Place of Birth</label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="pob" id="pob" type="text" class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- data-inputmask="'mask': ['9999-999-9999']" data-mask  --}}
                                                {{-- <div class="col-md-5">
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
                                                </div> --}}
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="dob" class="">Ethnic Group</label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="ethnicgroup" id="ethnicgroup"  class="form-control form-control-sm" min="1900-01-01" required>
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
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="mname" class="">Nationality</label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="nationality" id="nationality" type="text" class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="dob" class="">Last School Attended</label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="ethnicgroup" id="ethnicgroup"  class="form-control form-control-sm" min="1900-01-01" required>
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
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="mname" class="">Month/ Year of Completion</label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="nationality" id="nationality" type="text" class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="dob" class="">LEARNER'S REFERENCE NUMBER</label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="ethnicgroup" id="ethnicgroup"  class="form-control form-control-sm" min="1900-01-01" required>
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
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="mname" class="">Religion </label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="nationality" id="nationality" type="text" class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="dob" class="">Language Spoken (Mother Tongue)</label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="ethnicgroup" id="ethnicgroup"  class="form-control form-control-sm" min="1900-01-01" required>
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
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="mname" class="">Other Spoken Languages </label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="nationality" id="nationality" type="text" class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="clearfix" style="border: 1px solid #2b7bbf;"></div>
                                            <br>
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
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="dob" class="">Contact #</label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="ethnicgroup" id="ethnicgroup"  class="form-control form-control-sm" min="1900-01-01" required>
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
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="mname" class="">Tel. # </label>
                                                        <div class="input-group input-group-sm">
                                                            <input name="nationality" id="nationality" type="text" class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="clearfix" style="border: 1px solid #2b7bbf;"></div>
                                            <br>
                                            <strong>Note: If not Parent please fill in:</strong>
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
                                            <br>
                                            <div class="clearfix" style="border: 1px solid #2b7bbf;"></div>
                                            <br>
                                            <strong>COURSES OFFERS: (Check the box beside the chose Stand and Session)</strong>
                                            <br>
                                            <br>
                                            <div class="form-row">
                                                <table style="width: 100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:50%">ACADEMIC TRACK</th>
                                                            <th style="width:50%;">TECHNICAL-VOCATIONAL-LIVELIHOOD</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="form-group clearfix">
                                                                    <div class="icheck-primary d-inline">
                                                                        <input type="radio" id="radioPrimaryacademictrack1" name="academictrack">
                                                                        <label for="radioPrimaryacademictrack1">
                                                                            Humanities and Social Sciences
                                                                        </label>
                                                                    </div>
                                                                    <br>
                                                                    <div class="icheck-primary d-inline">
                                                                      <input type="radio" id="radioPrimaryacademictrack2" name="academictrack">
                                                                      <label for="radioPrimaryacademictrack2">
                                                                        Science, Technology, Engineering and Mathematics
                                                                      </label>
                                                                    </div>
                                                                    <br>
                                                                    <div class="icheck-primary d-inline">
                                                                      <input type="radio" id="radioPrimaryacademictrack3" name="academictrack">
                                                                      <label for="radioPrimaryacademictrack3">
                                                                        Accounting, Business and Management
                                                                      </label>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group clearfix">
                                                                    <div class="icheck-primary d-inline">
                                                                        <input type="radio" id="radioPrimarytvl1" name="tvl">
                                                                        <label for="radioPrimarytvl1">
                                                                            TVL 1 - Animation & Computer Programming
                                                                        </label>
                                                                    </div>
                                                                    <br>
                                                                    <div class="icheck-primary d-inline">
                                                                      <input type="radio" id="radioPrimarytvl2" name="tvl">
                                                                      <label for="radioPrimarytvl2">
                                                                        TVL 2 - Housekeeping, Bread and Pastry Production and Cookery
                                                                      </label>
                                                                    </div>
                                                                    <br>
                                                                    <div class="icheck-primary d-inline">
                                                                      <input type="radio" id="radioPrimarytvl3" name="tvl">
                                                                      <label for="radioPrimarytvl3">
                                                                        TVL 3 - Caregiving
                                                                      </label>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                {{-- <div class="col-md-3">
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
                                                </div> --}}
                                            </div>
                                            <br>
                                            <div class="clearfix" style="border: 1px solid #2b7bbf;"></div>
                                            <br>
                                            <strong>SESSION OFFERS:</strong>
                                            <br>
                                            <br>
                                            <div class="form-row">
                                                <strong>GRADE 11</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <div class="form-group clearfix">
                                                    <div class="icheck-primary d-inline">
                                                      <input type="checkbox" id="checkboxPrimary1" name="session[]" value="7:30 am - 2:30 pm">
                                                      <label for="checkboxPrimary1">7:30 am - 2:30 pm
                                                      </label>
                                                    </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <div class="icheck-primary d-inline">
                                                      <input type="checkbox" id="checkboxPrimary2" name="session[]" value="2:30 pm - 7:30 pm">
                                                      <label for="checkboxPrimary2" >2:30 pm - 7:30 pm
                                                      </label>
                                                    </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <div class="icheck-primary d-inline">
                                                      <input type="checkbox" id="checkboxPrimary3" name="session[]" value="4:30 pm - 9:30 pm">
                                                      <label for="checkboxPrimary3"> 4:30 pm - 9:30 pm
                                                      </label>
                                                    </div>
                                                  </div>
                                            </div>
                                            <br>
                                            <div class="clearfix" style="border: 1px solid #2b7bbf;"></div>
                                            <br>
                                            <div class="form-row">
                                                <p>I, <input type="text" size="50%"  style="border-radius: 0">(parent/guardian) wish to enroll my son/daughter at Brokenshire College Toril, Davao City, Inc. and that I have read and undestood all policy and agreed to comply with these policies.</p>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-6">
                                                    <center><input type="text" size="50%"  style="border-radius: 0"></center>
                                                    <center><label>Signature ove prnted name of Parent/Guardian</label></center>
                                                </div>
                                                <div class="col-6">
                                                    <center><input type="text" size="50%"  style="border-radius: 0"></center>
                                                    <center><label>Date Signed</label></center>
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
                                    <div class="card-header text-white" >
                                        
                                    </div>
                                    <div class="card-body">
                                        <u><center><strong>WITHDRAWAL POLICY</strong></center></u>
                                        <br>
                                        <div class="form-row">
                                            <p >I understand that withdrawal of enrolment may be necessary if there are well-founded circumstances linked to such action. Any unjustifiable withdrawal of enrolment is subject to payment of <b><u>Php 2,000</u></b> for enrolment, withdrawal and other related processing fees.</p>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-6">
                                                <center><input type="text" size="50%"  style="border-radius: 0"></center>
                                                <center><label>Signature ove prnted name of Parent/Guardian</label></center>
                                            </div>
                                            <div class="col-6">
                                                <center><input type="text" size="50%"  style="border-radius: 0"></center>
                                                <center><label>Date Signed</label></center>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header text-white" >
                                        (Please sign the statement of cooperation)
                                    </div>
                                    <div class="card-body">
                                        <center><p style="font-size:small;"><b>STATEMENT OF COOPERATION</b></p></center>
                                        <p align="justify">
                                            I understand that my child's attendance is a previledge and not a right that if anytime his/her conduct or academic performance in school is not consistent with the imposed standards, the school reserves the right to terminate my child's enrollment.
                                            <br><br>
                                            I also understand that it is my obligation to pay all demandable tuition and school fees on-time and that my failure to do so the school has the right to rescind this enrollment contract and deny my child's continued stay in school.
                                            <br><br>
                                            I also understand that as a parent/guardian it is my obligation to attend the academic and non academic affairs in school concerning my child's development, including the affairs of the Parent & Teacher Association.
                                            <br><br>
                                            I also give permission for my child to take part in school activities that enhance his/her learning except where there is a physician's advice for non-participation, which will be supported by a medical certificate.
                                            <br><br>
                                            I understand that this is an English-Filipino Zone Institution.
                                            <br><br>
                                            I further agree to read and understands the policies, rules and regulations of the school which are contained in the Student handbook. I agree to abide by them as long as my child's is enrolled in this institution.
                                            <br><br>
                                            I authorize the personnel of this institution to collect, process, retain, and dispose of personal information of the above-mentioned student in accordance with the Data Privacy Act of 2012.
                                        </p>
                                        <div class="form-row">
                                            <div class="col-6">
                                                <center><input type="text" size="50%"  style="border-radius: 0"></center>
                                                <center><label>Signature ove prnted name of Parent/Guardian</label></center>
                                            </div>
                                            <div class="col-6">
                                                <center><input type="text" size="50%"  style="border-radius: 0"></center>
                                                <center><label>Date Signed</label></center>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-6">
                                                <center><label>Noted by:</label><br>
                                                <input type="text" size="50%"  style="border-radius: 0"><br>
                                                <label>For school employee</label></center>
                                            </div>
                                            <div class="col-6">
                                                <center><label>Approved by:</label><br>
                                                <label><b><u>CHERRIE N. PANIAMOGAN, LPT, MAEM</u></b></label><br>
                                                <label>School Director</label></center>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-12">
                                            <center><label>Reieved by:<b><u>EMMANUEL P. LUZARES JR.</u></b></label><br>
                                                <label>School Records In-Charge</label></center>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header text-white" >
                                    </div>
                                    <div class="card-body">
                                        <h1><strong><u><center>A G R E E M E N T</center></u></strong></h1>
                                        <div class="row col-lg-12">
                                            <p>I, <input type="text" size="50%"  style="border-radius: 0"> (parent/guardian) of  <input type="text" size="50%" placeholder="Name of Student"  style="border-radius: 0"> swear to submit the lacking documents  <input type="text" size="100%"  style="border-radius: 0" placeholder="List of lacking requirements (if you are continuing student just put NONE.)"> on <input type="text" size="50%"  style="border-radius: 0" placeholder="Date of submission: 2 weeks after"></p>
                                        </div>
                                        <br>
                                        <p style="font-size: 10px;"><b>FAILURE TO SUBMIT THE SAID REQUIREMENT/S SHALL NOT BE PROMOTED TO THE NEXT GRADE? LEVEL AND SHALL BE ADVISED TO DROP/TRANSFER.</b></p>
                                        <br>
                                        <div class="row">
                                            <div class="col-6">
                                                <input type="text" size="50%"  style="border-radius: 0"><br>
                                                <label>Signature ove prnted name of Parent/Guardian</label>
                                            </div>
                                            <div class="col-6">
                                                <center><input type="text" size="50%"  style="border-radius: 0"></center>
                                                <center><label>Date Signed</label></center>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-6">
                                                <label>Noted by:</label><br>
                                                <label><b><u>JOHN REY T. NANCA, LPT</u></b></label><br>
                                                <label>Admission Officer</label>
                                            </div>
                                            <div class="col-6">
                                                <label>Approved by:</label><br>
                                                <label><b><u>CHERRIE N. PANIAMOGAN, LPT, MAEM</u></b></label><br>
                                                <label style="padding-left: 15%;">School Director</label>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="clearfix" style="border: 1px solid;"></div>
                                        <br>
                                        <div class="row col-lg-12">
                                            <input type="text" class="form-control" style="border-radius: 0">
                                            <div class="col-4 col-xs-4">
                                                <label>Verified by:</label>
                                            </div>
                                            <div class="col-4 col-xs-4">
                                                <label>Encoded by:</label>
                                            </div>
                                            <div class="col-4 col-xs-4">
                                                <center><label>Assessed by:</label></center>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <input type="text" size="40%"  style="border-radius: 0">
                                                <label>School Personnel:</label>
                                            </div>
                                            <div class="col-4 col-xs-4">
                                                <input type="text" size="45%"  style="border-radius: 0">
                                                <label>School Personnel:</label>
                                            </div>
                                            <div class="col-4 col-xs-4">
                                                <center><input type="text" size="40%"  style="border-radius: 0"></center>
                                                <center><label>School Personnel:</label></center>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 20px;">
                                            <div class="col-2">&nbsp;</div>
                                            <div class="col-4">
                                                <label>Noted by:</label><br>
                                                <label><b><u>JOHN REY T. NANCA, LPT</u></b></label><br>
                                                <label>Admission Officer</label>
                                            </div>
                                            <div class="col-4">
                                                <label>Approved by:</label><br>
                                                <label><b><u>CHERRIE N. PANIAMOGAN, LPT, MAEM</u></b></label><br>
                                                <label style="padding-left: 15%;">School Director</label>
                                            </div>
                                            <div class="col-2">&nbsp;</div>
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
