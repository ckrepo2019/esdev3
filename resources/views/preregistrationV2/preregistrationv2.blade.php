

@extends('layouts.app')

@section('headerscript')

<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

@php
      $schoolInfo = DB::table('schoolinfo')->first();
@endphp


<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<style>
      * {
        box-sizing: border-box;
      }
      
      body {
        background-color: #f1f1f1;
      }
      
      #regForm {
        /* background-color: #ffffff; */
        margin: 20px auto;
    
        /* padding: 40px; */
        width: 70%;
        min-width: 300px;
      }
      
      h1 {
        text-align: center;  
      }
      
      input {
        padding: 10px;
        /* width: 100%; */
        /* font-size: 17px; */
      
        border: 1px solid #aaaaaa;
      }
      
      /* Mark input boxes that gets an error on validation: */
      input.invalid {
        background-color: #ffdddd;
      }
      
      /* Hide all steps by default: */
      .tab {
        display: none;
      }
      
      button {
        background-color: #4CAF50;
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        /* font-size: 17px; */
      
        cursor: pointer;
      }
      
      button:hover {
        opacity: 0.8;
      }
      
      /* #prevBtn {
        background-color: #bbbbbb;
      } */
      
      /* Make circles that indicate the steps of the form: */
      .step {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none;  
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
      }
      
      .step.active {
        opacity: 1;
      }
      
    
      .step.finish {
        background-color: #4CAF50;
      }


      .bg-success {
            color: white !important;
            background-color: {{$schoolInfo->schoolcolor}} !important;

      }

      .btn-success.disabled, .btn-success:disabled {
            background-color: #bbbbbb !important;
            border-color: #bbbbbb !important;
      }

      .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }

      </style>

@endsection

@section('content')

      <div class="modal fade overlay w-100" id="modalAlert" style="display: none;" aria-hidden="true"    data-backdrop="static" data-keyboard="false"  >
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-body">
                              <h5>Unable to process transaction using this browser. Please click the button (<i class="fas fa-ellipsis-h mr-2 ml-2"></i>) on the upper right corner of the screen and select "Open in Chrome".</h5>
                        </div>
                  </div>
            
            </div>
      </div>

      <div class="modal fade" id="validatestudentinfo" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-header bg-success">
                              <h5 class="modal-title">VALIDATE STUDENT INFORMATION</h5>
                        </div>
                        <div class="modal-body">
                              <div class="row">
                                    <div class="form-group col-md-12">
                                          <label for=""><b>Student Birth Date</b></label>
                                          <input type="date" id="studentdob" class="form-control">
                                          <span class="invalid-feedback" role="alert" >
                                                <strong>Student Birth Date is required</strong>
                                          </span>
                                    </div>
                                    <div class="form-group" id="not_found_holder" hidden>
                                          <h5 class="pl-3 text-danger">Student Not Found. The information does not match our records. Please contact the office of registrar through this number {{DB::table('schoolinfo')->first()->scontactno}}.</h5>
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button class="btn btn-success" id="proceedvalidate">
                                    VALIDATE
                              </button>
                        </div>
                  </div>
            </div>
      </div>
      <div class="modal fade" id="schedtableModal" style="display: none;" aria-hidden="true" >
            <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                        <div class="modal-header bg-success">
                              <h5 class="modal-title">SCHEDULING</h5>
                            </div>
                        <div class="modal-body">
                              <div class="roW">
                                    <div class="form-group">
                                          <p>SELECTED SCHEDULES WILL BE VALIDATED BY THE SCHOOL DEAN</p>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="form-group">
                                          <select name="collegesection" id="collegesection" class="form-control">
                                                <option value="">SELECT SECTION</option>
                                          </select>
                                    </div>
                              </div>
                              <div class="row table-responsive" id="schedtable">
                                   
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button class="btn btn-success" id="schedulingdon">
                                    DONE
                              </button>
                        </div>
                  </div>
            </div>
      </div>

      <div class="modal fade" id="recoverCode" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-header bg-success">
                              <h5 class="modal-title">VALIDATE STUDENT INFORMATION</h5>
                            </div>
                        <div class="modal-body">
                              <div class="row">
                                    <div class="form-group  col-md-12">
                                          <label><strong>FIRST NAME</strong></label>
                                          <input id="fname" class="form-control" placeholder="FIRST NAME" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                    <div class="form-group  col-md-12">
                                          <label><strong>LAST NAME</strong></label>
                                          <input id="lname" class="form-control" placeholder="LAST NAME" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                    <div class="form-group col-md-12" >
                                          <label><strong>BIRTH DATE</strong></label>
                                          <input type="date" id="answer" class="form-control">
                                    </div>
                                    
                              </div>
                              <div class="row">
                                    <div class="col-md-6">
                                          <h4>Student ID:</h4>
                                    </div>
                                    <div class="col-md-6 text-right">
                                          <h4><span id="sid"></span></h4>
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button class="btn btn-success" id="recCodeButton">
                                    GET ID
                              </button>
                        </div>
                  </div>
            </div>
      </div>
      
      <form id="regForm" 
            action="preregistration/submit" 
            method="POST" 
            enctype="multipart/form-data"
            autocomplete="off"
            >
      
            @csrf
            <div class="card">
                  <div class="card-header">
                        APPLICATION INFORMATION
                  </div>
                  <div class="card-body" style="min-height: 400px">
                        <div class="tab">
                              <div class="row">
                                    <div class="form-group col-md-2">
                                    </div>
                                    <div class="form-group col-md-8">
                                          <div class="row">
                                                <div class="col-md-12">
                                                            @php
                                                                  $academicprogram = DB::table('academicprogram')->get();
                                                            @endphp
                                                            <label for="">Available Enrollment </label>
                                                            <select name="input_acadprog" id="input_acadprog" class=" form-control select2">
                                                                  <option value="">Select Available Enrollment</option>
                                                            </select>
                                                </div>
                                                <div class="col-md-12 mt-4">
                                                      <p id="enrollment_setup_status"></p>
                                                </div>
                                                      
                                          </div>
                                          <div class="row" hidden>
                                                <div class="form-group col-md-12">
                                                      <select name="input_setup_type" id="input_setup_type" class="form-control"></select>
                                                </div>
                                                <div class="form-group col-md-12">
                                                      <select name="input_syid" id="input_syid" class="form-control"></select>
                                                </div>
                                                <div class="form-group col-md-12">
                                                      <select name="input_semid" id="input_semid" class="form-control"></select>
                                                </div>
                                          </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                    </div>
                              </div>
                        </div>
                        <div class="tab">
                              <div class="row">
                                    <div class="form-group col-md-2">
                                    </div>
                                    <div class="form-group col-md-8">
                                          <div class="row">
                                                <div class="form-group col-md-6 id="holder_schoolyear">
                                                      <label for="">School Year</label>
                                                      <input disabled="disabled" class="form-control" id="input_syid_label">
                                                 </div>
                                                 <div class="form-group  col-md-6" id="holder_semester">
                                                      <label for="">Semester</label>
                                                      <input disabled="disabled" class="form-control" id="input_semid_label">
                                                </div>
                                          </div>
                                          <div class="form-group">
                                                <label><b>Pre-registration Type</b></label>
                                                <select name="studtype" id="studtype" class="form-control select2" required>
                                                      <option value="">PRE-REGISTRATION TYPE</option>
                                                      <option value="1">NEW STUDENT</option>
                                                      <option value="2">TRANSFEREE</option>
                                                </select>
                                                <span class="invalid-feedback" role="alert">
                                                      <strong>Application type is required</strong>
                                                </span>
                                          </div>
                                          <div class="form-group">
                                                <label><b>Grade Level to enroll</b></label>
                                                <select name="gradelevelid" id="gradelevelid" class="form-control select2"  required>
                                                      <option value="">GRADE LEVEL</option>
                                                </select>
                                                <span class="invalid-feedback" role="alert">
                                                      <strong id="gradeLevelError">Grade level is required.</strong>
                                                </span>
                                          </div>
                                          <div class="form-group" id="lrn_holder">
                                                <label><b>LRN</b></label>
                                                <input name="lrn" id="lrn" class="form-control " placeholder="LRN" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                                <span class="invalid-feedback" role="alert">
                                                      <strong id="gradeLevelError">LRN is required.</strong>
                                                </span>
                                          </div>
                                          <div class="form-group" id="lastschoolattfromgroup" hidden>
                                                <label><b>Last School Attended</b></label>
                                                <input name="lastschoolatt" id="lastschoolatt" class="form-control" placeholder="Last School Attended" required onkeyup="this.value = this.value.toUpperCase();">
                                                <span class="invalid-feedback" role="alert">
                                                      <strong id="gradeLevelError">Last school attended is required.</strong>
                                                </span>
                                          </div>

                                          @if($schoolInfo->withMOL == 1 )
                                                <div class="form-group">
                                                      <label><b>Mode of Learning</b></label>
                                                      <br>
                                                      <input type="radio" name="withMOL" value="0" hidden checked>
                                                      <div id="mol_holder"></div>
                                                      <input id="molErrorInput" hidden>
                                                      <span class="invalid-feedback" role="alert">
                                                            <strong id="molError">Mode of learning is required.</strong>
                                                      </span>
                                                </div>
                                          @endif

                                          @if($schoolInfo->withESC == 1 )
                                                <div class="form-group" id="lastschoolattfromgroup">
                                                      <label><b>Grantee</b></label>
                                                      <br>
                                                      @foreach(DB::table('grantee')->get() as $item)
                                                            <div class="icheck-primary d-inline" dusk="withESC{{$item->id}}">
                                                                  <input type="radio" name="withESC" id="withESC{{$item->id}}" @if($item->id == 1) checked @endif value="{{$item->id}}">
                                                                  <label class="mr-5" for="withESC{{$item->id}}"> {{$item->description}}
                                                                  </label>
                                                            </div>
                                                      @endforeach
                                                      <input id="molErrorInput" hidden>
                                                     
                                                      <span class="invalid-feedback" role="alert">
                                                            <strong id="molError">Mode of learning is required.</strong>
                                                      </span>
                                                </div>
                                          @endif

                                          <div class="form-group course-formgroup" id="course-formgroup" hidden>
                                                <label><b>Course</b></label>
                                                <select name="courseid" id="courseid" class="form-control select2"  required>
                                                      <option value="">SELECT COURSE</option>
                                                      @foreach (DB::table('college_courses')->where('deleted','0')->get() as $item)
                                                            <option value="{{$item->id}}">{{$item->courseDesc}}</option>
                                                      @endforeach
                                                </select>
                                                <span class="invalid-feedback" role="alert">
                                                      <strong id="gradeLevelError">Course is required.</strong>
                                                </span>
                                          </div>

                                          <div class="form-group" id="strand-formgroup" hidden>
                                                <label><b>Strand</b></label>
                                                <select name="studstrand" id="studstrand" class="form-control" required>
                                                      <option value="">SELECT STRAND</option>
                                                      @php
                                                            $strand = DB::table('sh_strand') 
                                                                        ->where('deleted','0')
                                                                        ->where('active','1')
                                                                        ->get();
                                                      @endphp
                                                      @foreach($strand as $item)
                                                            <option value="{{$item->id}}">{{$item->strandname}}</option>
                                                      @endforeach
                                                </select>
                                                <span class="invalid-feedback" role="alert">
                                                      <strong>Strand is required</strong>
                                                </span>
                                          </div>
                                         
                                    </div>
                                    <div class="form-group col-md-2">
                                    </div>
                              </div>
                        </div>
                        <div class="tab">
                              <div class="row">
                                    <div class="col-md-12">
                                          <label for="" id="dup_info" class="text-danger" hidden><b><i>Student name already exist please contact your school registar.</i></b></label>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="form-group col-md-4">
                                          <label><b>First Name <span class="text-danger">*<span></b></label>
                                          <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="First name"  name="first_name" required>
                                          <span class="invalid-feedback" role="alert">
                                                <strong>First Name is required</strong>
                                          </span>
                                    </div>
                                    <div class="form-group col-md-3">
                                          <label><b>Middle Name <span class="text-danger">*<span></b></label>
                                          <input onkeyup="this.value = this.value.toUpperCase();" class="form-control" placeholder="Middle name"  name="middle_name" required>
                                          <span class="invalid-feedback" role="alert">
                                                <strong>Middle Name is required</strong>
                                          </span>
                                    </div>
                                    <div class="form-group col-md-4">
                                          <label><b>Last Name <span class="text-danger">*<span></b></label>
                                          <input onkeyup="this.value = this.value.toUpperCase();" class="form-control" placeholder="Last name" id="last_name" name="last_name"  required>
                                          <span class="invalid-feedback" role="alert">
                                                <strong>Last Name is required</strong>
                                          </span>
                                    </div>
                                    <div class="form-group col-md-1">
                                          <label><b>SUFFIX</b></label>
                                          <input onkeyup="this.value = this.value.toUpperCase();" class="form-control p-2" placeholder="SU" id="suffix" name="suffix" >
                                          <span class="invalid-feedback" role="alert">
                                                <strong>Suffix is required</strong>
                                          </span>
                                    </div>
                                    <div class="form-group col-md-4">
                                          <label><b>Date of birth <span class="text-danger">*<span></b></label>
                                          <input type="date" class="form-control" placeholder="First name..."  name="dob" required>
                                          <span class="invalid-feedback" role="alert">
                                                <strong>Date of birth is required</strong>
                                          </span>
                                    </div>
                                    <div class="form-group col-md-4">
                                          <label><b>Gender <span class="text-danger">*<span></b></label>
                                          <select name="gender" id="gender"  class="form-control" required>
                                                <option value="" selected>GENDER</option>
                                                <option value="FEMALE">FEMALE</option>
                                                <option value="MALE">MALE</option>
                                          </select>
                                          <span class="invalid-feedback" role="alert">
                                                <strong>Gender is required</strong>
                                          </span>
                                    </div>
                                    <div class="form-group col-md-4">
                                          <label><b>Nationality <span class="text-danger">*<span></b></label>
                                          <select name="nationality" id="nationality" class="form-control select2"  required>
                                                <option value=""></option>
                                                @foreach(DB::table('nationality')->where('deleted',0)->orderBy('nationality')->get() as $item)
                                                      @if($item->id == 77)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->nationality}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->nationality}}</option>
                                                      @endif
                                                
                                                @endforeach
                                          </select>
                                          <span class="invalid-feedback" role="alert">
                                                <strong>Nationality is required</strong>
                                          </span>
                                    </div>
                              </div>
                              <hr>
                              <div class="row">
                                    <div class="form-group col-md-6">
                                          <label><b>Street</b> <span class="text-danger">*<span></label>
                                          <input class="form-control" onkeyup="this.value = this.value.toUpperCase();" name="street" id="street" autocomplete="off" required>
                                          <span class="invalid-feedback" role="alert" >
                                                <strong id="streetError">Street is required</strong>
                                          </span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label><b>Barangay</b> <span class="text-danger">*<span></label>
                                        <input class="form-control" onkeyup="this.value = this.value.toUpperCase();" name="barangay" id="barangay" autocomplete="off" required>
                                        <span class="invalid-feedback" role="alert" >
                                              <strong id="barangayError">Barangay is required</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label><b>City/Municipality</b> <span class="text-danger">*<span></label>
                                        <input class="form-control" onkeyup="this.value = this.value.toUpperCase();" name="city" id="city" autocomplete="off"required>
                                        <span class="invalid-feedback" role="alert" >
                                              <strong id="cityError">City is required</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label><b>Province</b> <span class="text-danger">*<span></label>
                                        <input class="form-control" onkeyup="this.value = this.value.toUpperCase();" name="province" id="province" autocomplete="off" required>
                                        <span class="invalid-feedback" role="alert" >
                                                <strong id="provinceError">Province is required</strong>
                                        </span>
                                    </div>
                              </div>
                              <hr>
                              <div class="row">
                                    <div class="form-group col-md-6">
                                          <label><b>Mobile Number <span class="text-danger">*<span></b></label>
                                          <input class="form-control" placeholder="09XX-XXXX-XXXX "  name="contact_number" id="contact_number" minlength="13" maxlength="13" autocomplete="off" required>
                                          <span class="invalid-feedback" role="alert" >
                                                <strong id="mobileError">Mobile number is required</strong>
                                          </span>
                                    </div>
                                    <div class="form-group col-md-6">
                                          <label class="form-control-label"><b>Email Address</b> (Optional)</label>
                                          <input type="email" class="form-control " placeholder="Email address"  name="email" autocomplete="off">
                                          <span class="invalid-feedback" role="alert">
                                                <strong>Email address is required</strong>
                                          </span>
                                    </div>
                              </div>
                        </div>
                        <div class="tab">
                              <div class="row">
                                    <div class="form-group col-md-4">
                                          <label><b>Father's Name</b><span></span></label>
                                          <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="Father's Full Name"  name="father_name" id="father_name">
                                          <div class="text-center" style="font-size: 1rem !important;">
                                               ( surname, full name, middle name )
                                          </div>
                                          <span class="invalid-feedback" role="alert">
                                                <strong>Father's Name is required</strong>
                                          </span>
                                    </div>
                                    <div class="form-group col-md-4">
                                          <label><b>Father's Occupation</b></label>
                                          <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="Father's Occupation"  name="father_occupation" >
                                          
                                          <span class="invalid-feedback" role="alert">
                                                <strong>Father's occupation is required</strong>
                                          </span>
                                    </div>
                                    <div class="form-group col-md-4">
                                          <label><b>Father's Contact Number</b></label>
                                          <input class="form-control " id="father_contact_number"  name="father_contact_number" placeholder="09XX-XXXX-XXXX " minlength="13" maxlength="13" >
                                          <span class="invalid-feedback" role="alert">
                                                <strong id="fmobileError">Father's Contact Number is required</strong>
                                          </span>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="form-group col-md-4">
                                          <label><b>Mother's Maiden Name </b></label>
                                          <input class="form-control " onkeyup="this.value = this.value.toUpperCase();" placeholder="Mother's Full Maiden Name"  name="mother_name" id="mother_name" >
                                          <div class="text-center" style="font-size: 1rem !important;">
                                                ( surname, full name, middle name )
                                          </div>
                                          <span class="invalid-feedback" role="alert">
                                                <strong>Mother's Maiden Name is required</strong>
                                          </span>
                                    </div>
                                    <div class="form-group col-md-4">
                                          <label><b>Mother's Occupation</b></label>
                                          <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="Mother's occupation"  name="mother_occupation" >
                                          <span class="invalid-feedback" role="alert">
                                                <strong>Mother's occupation is required</strong>
                                          </span>
                                    </div>
                                    <div class="form-group col-md-4">
                                          <label><b>Mother's Contact Number</b></label>
                                          <input class="form-control " id="mother_contact_number"  name="mother_contact_number" placeholder="09XX-XXXX-XXXX " minlength="13" maxlength="13" >
                                          <span class="invalid-feedback" role="alert">
                                                <strong id="mmobileError">Mother's contact number is required</strong>
                                          </span>
                                    </div>
                              </div>
                              <hr>
                              <div class="row">
                                    <div class="form-group col-md-4">
                                          <label><b>Guardian's Name </b></label>
                                          <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="(surname, full name, middle name)"  name="guardian_name" id="guardian_name">
                                          <div class="text-center" style="font-size: 1rem !important;">
                                                ( surname, full name, middle name )
                                          </div>
                                          <span class="invalid-feedback" role="alert">
                                                <strong>Guardian's Name is required</strong>
                                          </span>
                                    </div>
                                    <div class="form-group col-md-4">
                                          <label><b>Relationship to Student</b></label>
                                          <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="Guardian's Relationship"  name="guardian_relation" >
                                          <span class="invalid-feedback" role="alert">
                                                <strong>Guardian's relationship is required</strong>
                                          </span>
                                    </div>
                                    <div class="form-group col-md-4">
                                          <label><b>Guardian's Contact Number</b></label>
                                          <input class="form-control"  id="guardian_contact_number"  name="guardian_contact_number" placeholder="09XX-XXXX-XXXX " minlength="13" maxlength="13" >
                                          <span class="invalid-feedback" role="alert">
                                                <strong id="gmobileError">Guardian's contact number is required</strong>
                                          </span>
                                    </div>
                              </div>
                              <hr>
                              <div class="row" id="incaseholder">
                                    <div class="col-md-12 ">
                                          <label><b>In case of emergency ( Recipient for News, Announcement and School Info)</b></label>
                                         
                                    </div>
                                    <div class="col-md-4">
                                          <div class="icheck-success d-inline">
                                                <input class="form-control" type="radio" id="father" name="incase" value="1" required>
                                                <label for="father">Father
                                                </label>
                                          </div>
                                    </div>
                                    <div class="col-md-4">
                                          <div class="icheck-success d-inline">
                                                <input class="form-control" type="radio" id="mother" name="incase" value="2" required>
                                                <label for="mother">Mother
                                                </label>
                                          </div>
                                    </div>
                                    <div class="col-md-4">
                                          <div class="icheck-success d-inline">
                                                <input class="form-control" type="radio" id="guardian" name="incase" value="3" required>
                                                <label for="guardian">Guardian
                                                </label>
                                          </div>
                                    </div>
                                    
                              </div>
                              <span class="invalid-feedback pl-3" role="alert" id="incaseinvalid">
                                    <strong id="incasetext">In case of emergency is required</strong>
                              </span>
                        </div>
                        <div class="tab">
                              {{-- <div class="row">
                                    <div class="col-md-3">
                                          <div class="row">
                                                <div class="col-md-12">
                                                      <label>Vaccinated</label>
                                                </div>
                                          </div>
                                          <div class="row">
                                                <div class="col-md-6 form-group">
                                                       <div class="icheck-primary d-inline pt-2">
                                                             <input type="radio" id="input_vacc_type_yes"  name="vacc" class="vacc" value="1">
                                                             <label for="input_vacc_type_yes">Yes</label>
                                                       </div> 
                                                </div>
                                                 <div class="col-md-6">
                                                       <div class="icheck-primary d-inline pt-2">
                                                             <input type="radio" id="input_vacc_type_no"  name="vacc" class="vacc" value="0">
                                                             <label for="input_vacc_type_no">No</label>
                                                       </div> 
                                                 </div>
                                          </div>
                                     </div>
                              </div> --}}
                              <div class="row">
                                    <div class="col-md-2">
                                          <label>Vaccinated:</label>
                                    </div>
                                    <div class="col-md-1 form-group  mb-2">
                                                <div class="icheck-primary d-inline pt-2">
                                                      <input type="radio" id="input_vacc_type_yes"  name="vacc" class="vacc" value="1">
                                                      <label for="input_vacc_type_yes">Yes</label>
                                                </div> 
                                                
                                    </div>
                                    <div class="col-md-1">
                                          <div class="icheck-primary d-inline pt-2">
                                                <input type="radio" id="input_vacc_type_no"  name="vacc" class="vacc" value="0">
                                                <label for="input_vacc_type_no">No</label>
                                          </div> 
                                    </div>
                               </div>
                               <span class="invalid-feedback pl-3" role="alert" id="vaccineinvalid">
                                    <strong>In case of emergency is required</strong>
                              </span>
                               {{-- <div class="row">
                                     <div class="col-md-3 form-group">
                                           <label>Type of Vaccine</label>
                                           <input id="vacc_type" class="form-control" name="vacc_type">
                                     </div>
                                    
                                     <div class="col-md-3 form-group">
                                           <label>1st Dose Date</label>
                                           <input type="date" id="dose_date_1st" class="form-control"  name="dose_date_1st">
                                     </div>
                                     <div class="col-md-3 form-group">
                                           <label>2nd Dose Date</label>
                                           <input type="date" id="dose_date_2nd" class="form-control"  name="dose_date_2nd">
                                     </div>
                               </div> --}}
                               <div class="row vaccineform" hidden>
                                    <div class="col-md-3 form-group">
                                          <label class="mb-1">Vaccine (1st Dose) <span class="text-danger">*<span></label>
                                          <select id="vacc_type_1st"  name="vacc_type_1st" class="form-control" required></select>
                                          <span class="invalid-feedback" role="alert">
                                                <strong id="vaccType1stError">Vaccine (1st Dose) is required</strong>
                                          </span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                          <label class="mb-1">1st Dose Date <span class="text-danger">*<span></label>
                                          <input type="date" id="dose_date_1st" name="dose_date_1st" class="form-control" required>
                                          <span class="invalid-feedback" role="alert">
                                                <strong id="doseDate1stError">1st Dose Date number is required</strong>
                                          </span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                         <label class="mb-1">Vaccine(2nd Dose)</label>
                                         <select id="vacc_type_2nd" name="vacc_type_2nd"  class="form-control">

                                         </select>
                                   </div>
                                    <div class="col-md-3 form-group">
                                          <label class="mb-1">2nd Dose Date</label>
                                          <input type="date" id="dose_date_2nd"  name="dose_date_2nd" class="form-control">
                                    </div>
                              </div>
                              <div class="row vaccineform" hidden>
                                   <div class="col-md-3 form-group">
                                         <label class="mb-1">Vaccine (Booster Shot)</label>
                                         <select id="vacc_type_booster" name="vacc_type_booster" class="form-control">

                                        </select>
                                   </div>
                                   <div class="col-md-3 form-group">
                                         <label class="mb-1">Booster Dose Date</label>
                                         <input type="date" id="dose_date_booster" name="dose_date_booster" class="form-control">
                                   </div>
                              </div>
                               <div class="row vaccineform" hidden>
                                    <div class="col-md-3 form-group">
                                          <label>Vaccination Card # <span class="text-danger">*<span></label>
                                          <input id="vacc_card_id" class="form-control"  name="vacc_card_id" required>
                                          <span class="invalid-feedback" role="alert">
                                                <strong id="vaccineCard#Error">Vaccination Card # is required</strong>
                                          </span>
                                    </div>
                                     <div class="col-md-3 form-group">
                                           <label>Philhealth ID Number</label>
                                           <input id="philhealth" class="form-control" name="philhealth">
                                     </div>
                                     <div class="col-md-3 form-group">
                                           <label>Blood Type</label>
                                           <input id="bloodtype" class="form-control" name="bloodtype">
                                     </div>
                               </div>
                               <div class="row">
                                    <div class="col-md-12 form-group mb-2">
                                          <label class="mb-1">Allergies to Medication</label>
                                          <input name="allergy_to_med" id="allergy_to_med" class="form-control" placeholder="Allergies to Medication">
                                    </div>
                               </div>
                               <div class="row">
                                    <div class="col-md-12 form-group mb-2">
                                          <label class="mb-1">Medical History</label>
                                          <input name="med_his" id="med_his" class="form-control" placeholder="Medical History">
                                    </div>
                               </div>
                               <div class="row">
                                     <div class="col-md-12 form-group">
                                           <label>Other Medical Information</label>
                                           <input id="other_med_info" class="form-control" name="other_med_info" placeholder="Other Medical Information"> 
                                     </div>
                               </div>
                        </div>
                        <div class="tab">
                              <table class="table table-bordered">
                                    <thead >
                                          <tr>
                                                <td>Requirement Description</td>
                                                <td></td>
                                          </tr>
                                    </thead>
                                    <tbody id="preregreqbody">
                                          {{-- @foreach(DB::table('preregistrationreqlist')->where('deleted','0')->where('acadprogid',null)->where('isActive','1')->get() as $item) --}}
                                                {{-- <tr data-status="1">
                                                      <td class="align-middle">{{$item->description}}</td>
                                                      <td><input name="req{{$item->id}}" type="file" class="form-control form-control-sm" accept=".png, .jpg, .jpeg"></td>
                                                </tr> --}}
                                          {{-- @endforeach --}}
                                    </tbody>
                                    <tfoot>
                                          <tr>
                                                <td colspan="2">
                                                      <i><b>Note:</b>
                                                            <ul>
                                                                  <li>Old/Continuing Students don’t have to upload requirements ( unless specified and requested by the Registrar’s Office). You can skip this step by clicking next. </li>
                                                                  <li>For New/Transferee student please submit a hard copy of the requiremenst to the registars office.</li>
                                                            </ul>
                                                </td>
                                          </tr>
                                    </tfoot>
                                  
                              </table>
                        </div>
                        
                        <div class="tab">
                              <div class=" w-100" style="overflow: auto;max-height: 363px;" id="terms">
                                    {!! html_entity_decode(DB::table('schoolinfo')->first()->terms) !!}
                                    <div class="row mt-4">
                                          <div class="col-md-12">
                                                <div class="icheck-success d-inline">
                                                      <input class="form-control" type="checkbox" id="agree" name="agree" value="2" required>
                                                      <label for="agree">I confirm that I have read, understand and agree to the above terms and agreement for enrollment of {{DB::table('schoolinfo')->first()->schoolname}}
                                                      </label>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        
                  </div>
                  <div class="card-footer">
                        <div style="float:right;">
                              <button type="button" class="btn btn-secondary" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                              <button type="button" class="btn btn-success" id="nextBtn" onclick="nextPrev(1)" hidden>Next</button>
                        </div>
                     
                  </div>
                
            </div>
            <div style="text-align:center;margin-top:40px;" class="stepHolder">
                  <div class="float-right">
                        <p class="mb-0">Powered by:</p>
                        <img class="schoollogo " style="max-height: 95px" src="{{asset( DB::table('schoolinfo')->first()->picurl)}}">
                  </div>
                  <span class="step"></span>
                  <span class="step"></span>
                  <span class="step"></span>
                  <span class="step"></span>
                  <span class="step"></span>
                  <span class="step"></span>
                  <span class="step"></span>
            </div>
      </form>

      <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
      <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>

      <script>
            var enrollmentsetup = []
            var all_mol = [] 
            var withMOL = @json($schoolInfo->withMOL)
      </script>

      <script>
            $(document).ready(function(){
                  
                  $(document).on('change','#gradelevelid',function(){

                        if($(this).val() == ""){
                              return false
                        }


                        var temp_levelid = $(this).val()
                        var temp_enrollmentsetup = enrollmentsetup.filter(x=>x.id == $('#input_acadprog').val())
                        
                        if(withMOL == 1){
                              $.ajax({
                                    type:'GET',
                                    url: '/setup/modeoflearning/list',
                                    data:{
                                          syid:temp_enrollmentsetup[0].syid,
                                          status:1
                                    },
                                    success:function(data) {
                                          
                                          all_mol = data
                                          $('#mol_holder').empty()
                                          var checkbox = ''
                                          $.each(data,function(a,b){
                                                included = false
                                                if(b.all){
                                                      included = true
                                                }else{
                                                      var check = b.gradelevel.filter(x=>x.levelid == temp_levelid).length
                                                      if(check > 0){
                                                            included = true
                                                      }
                                                }

                                                if(included){
                                                      checkbox +=    '<div class="icheck-primary d-inline" dusk="withMOL'+b.id+'">'+
                                                                        '<input  type="radio" name="withMOL" id="withMOL'+b.id+'" value="'+b.id+'" required>'+
                                                                        '<label class="mr-5" for="withMOL'+b.id+'">'+b.description+'</label>'+
                                                                  '</div>'
                                                }
                                          
                                          })
                                          $('#mol_holder')[0].innerHTML = checkbox
                                    
                                    }
                              })
                        }
                  })

            })
      </script>


      <script>
            $(document).ready(function(){


                  function isFacebookApp() {
                        var ua = navigator.userAgent || navigator.vendor || window.opera;
                        return (ua.indexOf("FBAN") > -1) || (ua.indexOf("FBAV") > -1);
                  } 
                  if(isFacebookApp()){
                        $('#modalAlert').modal('show')
                  }

                  $(document).on('click','input[name="incase"]',function(){
                        $('#father_contact_number').removeAttr('required')
                        $('#mother_contact_number').removeAttr('required')
                        $('#guardian_contact_number').removeAttr('required')
                        if($(this).val() == 1){
                              $('#father_contact_number').attr('required')
                        }
                        else if($(this).val() == 2){
                              $('#mother_contact_number').attr('required')
                        }
                        else if($(this).val() == 3){
                              $('#guardian_contact_number').attr('required')
                        }
                  })
            
                  $(document).on('change','#question',function(){
                        $('#answerholder').empty();
                        $('#answerholder').append('<label><strong>ANSWER</strong></label>')
                        if($(this).val() == 1){
                              $('#answerholder').append(
                                    '<input id="answer" name="answer" class="form-control" placeholder="Guardian\'s Contact Number" minlength="11" maxlength="11" data-inputmask-clearmaskonlostfocus="true">'
                              )
                        }

                        else if($(this).val() == 2){
                              $('#answerholder').append(
                                    '<input id="answer" name="answer" class="form-control" placeholder="Mother\'s Name" minlength="11" maxlength="11" data-inputmask-clearmaskonlostfocus="true">'+
                                    '<label class="pl-2 strong"><em>format: lastname, firstname</em></label>'
                              )
                        }
                        else if($(this).val() == 4){
                              $('#answerholder').append('<input type="date" id="answer" name="answer" class="form-control" >')
                        }
                  })
                  
                  
                  
                  $(document).on('click','#recCodeButton',function(){
                        $.ajax({
                              type:'GET',
                              url:'/proccess/recoverycode',
                              data:{
                                    a:$("#fname").val(),
                                    b:$("#lname").val(),
                                    c:4,
                                    d:$("#answer").val()
                              },
                              success:function(data) {
                                    $('#regCode').text(data[0].regCode)
                                    $('#sid').text(data[0].sid)
                              },
                              error:function(data) {
                              },
                        })
                  })

                 
            })
      </script>

      <script>

            $(document).ready(function(){

                  //check duplication
                  var with_dup = false

                  $(document).on('input','input[name="first_name"] , input[name="last_name"]',function(){
                        if($('#studtype').val() == 1 || $('#studtype').val() == 2){
                              student_information()
                        }
                  })

                  function student_information(){
                        $.ajax({
					type:'GET',
					url: '/student/enrollment/check/duplication',
                              data:{
                                    firstname:$('input[name="first_name"]').val(),
                                    lastname:$('input[name="last_name"]').val()
                              },
					success:function(data) {
						if(data[0].status == 1){
                                          $('#dup_info').removeAttr('hidden')
                                          $('#dup_info').text(data[0].message)
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'Student Already Exist'
                                          })
                                          var with_dup = true
                                    }else{
                                          $('#dup_info').attr('hidden','hidden')
                                          var with_dup = false
                                    }
					}
				})
                  }
                  //check duplication
                  
                  var gradelevel = @json($gradelevel)
                  
                  

                  get_enrollment_setup()

                  function get_enrollment_setup(){
                        $.ajax({
                              type:'GET',
                              url:'/enrollmentsetup/list',
                              data:{
                                   active:1
                              },
                              success:function(data) {
                                    enrollmentsetup = data
                                    $('#input_acadprog').empty()
                                    $('#input_acadprog').append('<option value="">Select Available Enrollment</option>')
                                    $.each(data,function(a,b){
                                          if(b.admission_studtype == 0 || b.admission_studtype == 2){
                                                $('#input_acadprog').append('<option value="'+b.id+'">['+b.sydesc+'] - '+b.progname+' - '+b.description+'</option>')
                                          }
                                    })
                              },
                        })
                  }

                  $(document).on('change','#input_acadprog',function(){

                        if($(this).val() == ""){
                              $('#nextBtn').attr('hidden','hidden')
                        }

                        var temp_data = enrollmentsetup.filter(x=>x.id == $('#input_acadprog').val())

                        // if(temp_data[0].acadprogid == 5 || temp_data[0].acadprogid == 6){
                        //       $('#holder_semester').removeAttr('hidden')
                        // }else{
                        //       $('#holder_semester').attr('hidden','hidden')
                        // }

                        if(temp_data[0].acadprogid == 6 || temp_data[0].acadprogid == 2){
                             $('#lrn_holder').attr('hidden','hidden')
                        }else{
                            $('#lrn_holder').removeAttr('hidden')
                        }

                        if(temp_data[0].acadprogid == 2){
                              $('#lastschoolatt').removeAttr('required')
                        }else{
                              $('#lastschoolatt').attr('required','required')
                        }

                        var temp_gradelevel = gradelevel.filter(x=>x.acadprogid == temp_data[0].acadprogid)
                    

                        $('#gradelevelid').empty()
                        $('#gradelevelid').append('<option value="">GRADE LEVEL</option>')

                        $.each(temp_gradelevel,function (a,b) {
                              $('#gradelevelid').append('<option value="'+b.id+'">'+b.levelname+'</option>')
                        })

                        $('.form-control').removeClass('is-invalid')
                        $('input').removeClass('is-invalid')


                        $('#input_setup_type').empty()
                        $('#input_setup_type').append('<option value="'+temp_data[0].type+'" selected="selected"></option>')
                        $('#input_semid').empty()
                        $('#input_semid').append('<option value="'+temp_data[0].semid+'" selected="selected"></option>')
                        $('#input_syid').empty()
                        $('#input_syid').append('<option value="'+temp_data[0].syid+'" selected="selected"></option>')

                        $('#input_semid_label').val(temp_data[0].semester)
                        $('#input_syid_label').val(temp_data[0].sydesc)
                        $('#nextBtn').removeAttr('hidden')
                  })        

                  // function check_enrollment_setup(){
                  //       $.ajax({
                  //             type:'GET',
                  //             url: '/student/enrollment/setup',
                  //             data:{
                  //                   acadprogid:selected_acadprog,
                  //                   active:1
                                    
                  //             },
                  //             success:function(data) {
                  //                   if(data[0].status == 1){
                  //                         enrollmentsetup = data[0].data
                  //                         $('#nextBtn').removeAttr('hidden')
                  //                         var message = data[0].message + ' Please click next to proceed.'
                  //                         $('#enrollment_setup_status').text(message)
                  //                         $('#input_setup_type').empty()
                  //                         $('#input_setup_type').append('<option value="'+data[0].data[0].type+'" selected="selected"></option>')
                  //                         $('#input_semid').empty()
                  //                         $('#input_semid').append('<option value="'+data[0].data[0].semid+'" selected="selected"></option>')
                  //                         $('#input_syid').empty()
                  //                         $('#input_syid').append('<option value="'+data[0].data[0].syid+'" selected="selected"></option>')

                  //                         $('#input_semid_label').val(data[0].data[0].semester)
                  //                         $('#input_syid_label').val(data[0].data[0].sydesc)
                  //                   }
                  //                   else if(data[0].status == 0){
                  //                         $('#nextBtn').attr('hidden','hidden')
                  //                         $('#enrollment_setup_status').text(data[0].message)
                  //                   }
                                    
                  //             }
                  //       })
                  // }

                  $(function () {
                        $('.select2').select2({
                              theme: 'bootstrap4'
                        })
                  })

                  $(document).on('change','input[type="file"]',function(){
                        var validImage = false;
                        if(this.files[0].type == 'image/png' || this.files[0].type == 'image/jpeg' || this.files[0].type == 'image/jpg'  ){
                              validImage = true
                        }
                        if(this.files[0].size >= 5767168){
                              alert("File is too big!");
                              this.value = "";
                        }
                        else if(!validImage){
                              alert("File is not an image!");
                              this.value = "";
                        }
                  })

                  var selectedSched = []
                  var selectedSubject = []

                  // $('select[name="gradelevelid"] option:not(:selected)').prop("disabled", true);

                  $(document).on('change','#collegesection',function(){
                        $.ajax({
                              type:'GET',
                              url:'/chairperson/scheduling/sectscshed/'+$(this).val(),
                              success:function(data) {
                                    $('#schedtable').empty();
                                    $('#schedtable').append(data);
                                    $('#schedtable input').each(function(){
                                          if(jQuery.inArray( $(this).attr('data-value'), selectedSched) != -1){
                                                $(this).prop('checked',true)
                                          }
                                    })
                              }
                        })
                  })

                  $(document).on('click','#schedtable input[type="checkbox"]',function(){
                        var subjExist = false;
                        var selectInput = $(this);
                        $('#schedtable tr').each(function(){
                              if(
                                    jQuery.inArray( $(this)[0].children[2].innerText, selectedSubject) != -1
                                    && 
                                    jQuery.inArray( $(this).attr('data-value'), selectedSched) == -1
                              ){
                                    subjExist = true
                              }
                        })
                        if(!subjExist){
                              if($(this).prop('checked') == true){
                                    selectedSched.push($(this).attr('data-value'))
                                    selectedSubject.push($(this).closest('tr')[0].children[2].innerText)
                              }
                              else{
                                    var removeItem = $(this).attr('data-value');
                                    var removeItemSubj = $(this).closest('tr')[0].children[2].innerText;
                                    selectedSched = jQuery.grep(selectedSched, function(value) {
                                          return value != removeItem;
                                    });

                                    selectedSubject = jQuery.grep(selectedSubject, function(value) {
                                          return value != removeItemSubj;
                                    });
                              }
                        }
                        else{
                              selectInput.prop('checked',false)
                              alert('SUBJECT ALREADY SELECTED!')
                        }
                  })

                  $(document).on('click','#recID',function(){
                        $('#recoverCode').modal();
                  })

                  $(document).on('click','#schedulingdon',function(){
                        if(selectedSched.length > 0){
                              $('#schedtableModal').modal('hide')
                              $('#schedVal').val(selectedSched)
                        }
                        else{
                              $('#schedtableModal').modal('hide')
                        }
                  })

                  $(document).on('click','#showSched',function(){
                        if($('#courseid').val() != ''){
                              $('#schedtableModal').modal();
                              $.ajax({
                                    type:'POST',
                                    data: {'_token': '{{ csrf_token() }}'},
                                    url:'/collegesections?info=info&course='+$('#courseid').val(),
                                    success:function(data) {

                                          $('#collegesection').empty()
                                          $('#collegesection').append('<option value="">SELECT SECTION</option>')

                                          if(data.length > 0){
                                   
                                                $.each(data,function(a,b){
                                                      $('#collegesection').append('<option value="'+b.id+'">'+b.sectionDesc+'</option>')
                                                })
                                          }
                                    },
                              })
                              $('#courseid').removeClass('is-invalid')
                        }
                        else{
                              $('#courseid').addClass('is-invalid')
                        }
                  })

                  $(document).on('click','#validatestudinfoinput',function(){
                        if($('#studid').val() == ""){
                              $('#studid').addClass('is-invalid');
                        }
                        else{
                              $('#studid').removeClass('is-invalid');
                              $('#validatestudentinfo').modal();

                        }
                        $('#not_found_holder').attr('hidden','hidden')
                  })

                  $(document).on('click','#proceedvalidate',function(){
                        if($('#studentdob').val() == ""){
                              $('#studentdob').addClass('is-invalid');
                        }
                        else{
                              $('#studentdob').removeClass('is-invalid');
                              getStudentInfo($('#studid'))
                        }
                  })

                  $(document).on('change','#gradelevelid',function(){
                        checkGradeLevel()
                        if($('#gradelevelid').val() != ''){
                              $.ajax({
                                    type:'GET',
                                    url: '/superadmin/setup/document/list',
                                    data:{
                                          levelid:$(this).val()
                                    },
                                    success:function(data){
                                          $('#preregreqbody').empty()
                                          $.each(data, function(a,b){
                                                var required = ''
                                                var add = true;
                                                if(b.isRequired == 1){
                                                      required = 'required'
                                                }
                                                if(b.doc_studtype != null){
                                                      if($('#studtype').val() == 1 && b.doc_studtype != 'New'){
                                                            add = false
                                                      }else if($('#studtype').val() == 2 && b.doc_studtype != 'Transferee'){
                                                            add = false
                                                      }
                                                }
                                                if(add){
                                                      $('#preregreqbody').append('<tr data-status="0"><td>'+b.description+'</td><td><input class="form-control form-controm-sm" type="file" name="req'+b.id+'" '+required+'></td></tr>')
                                                }
                                          })
                                    }
                              })
                        }
                  })

                  function checkGradeLevel(){
                        $('#studstrand').removeAttr('required')
                        $('#strand-formgroup').attr('hidden','hidden')
                        $('#courseid').removeAttr('required')
                        $('#courseid').removeAttr('disabled')
                        $('#courseid').val("").change()
                        $('.course-formgroup').attr('hidden','hidden')
                        $('#schedVal').val('1')
                        $('input[name="withMOL"][type="radio"]').removeAttr('disabled')
                        $('input[name="withMOL"][value=0]').prop('checked',true)
                        if(parseInt($('#gradelevelid').val()) == 14 || parseInt($('#gradelevelid').val()) == 15){
                              $('#strand-formgroup').removeAttr('hidden')
                              $('#studstrand').attr('required','required')
                        }
                        else if( parseInt($('#gradelevelid').val()) >= 17 && parseInt($('#gradelevelid').val()) <= 21){
                              $('.course-formgroup').removeAttr('hidden')
                              $('#courseid').attr('required','required')
                              $('#schedVal').val('')
                        }
                        else{
                              $('#studstrand').removeAttr('required')
                              $('#strand-formgroup').attr('hidden','hidden')
                        }
                  }

                  $(document).on('change','#studtype',function(){

                        $('#gradelevelid').val('').change()
                        $('#studstrand').removeAttr('required')
                        $('#strand-formgroup').attr('hidden','hidden')
                        $('#courseid').removeAttr('required')
                        $('.course-formgroup').attr('hidden','hidden')

                        if($(this).val() == 3){
                              $('#lastschoolattfromgroup').attr('hidden','hidden')
                              $('#studid-formgroup').removeAttr('hidden')
                              $('#studinfoprereg-formgroup').removeAttr('hidden')
                              $('#studid').attr('required','required')
                              $('#studinfoprereg').removeAttr('disabled','disabled')
                              $('#gradeLevelError').text('Student information is not yet validated. Click the "VALIDATE STUDENT INFORMATION" button to validate student information.')
                              $('input[name="first_name"]').removeAttr('readonly')
                              $('input[name="middle_name"]').removeAttr('readonly')
                              $('input[name="last_name"]').removeAttr('readonly')
                              $('input[name="dob"]').removeAttr('readonly')
                              $('input[name="suffix"]').removeAttr('readonly')
                              $('#gender').removeAttr('readonly')
                              // $('select[name="gradelevelid"] option:not(:selected)').prop("disabled", true);
                        }
                        else{
                              $('#lastschoolattfromgroup').removeAttr('hidden')
                              $('#studid-formgroup').attr('hidden','hidde')
                              $('#studid').removeAttr('required')
                              $('#gradelevelid').removeAttr('disabled')
                              $('#gradeLevelError').text('Grade level is required.')
                              $('#studinfoprereg-formgroup').attr('hidden','hidden')
                              $('#studinfoprereg').removeAttr('required')
                              $('#courseid').removeAttr('required')
                              $('.course-formgroup').attr('hidden','hidden')
                              // $('select[name="gradelevelid"] option:not(:selected)').prop("disabled", false);
                        }
                  })


                  $(document).on('change','#studinfoprereg',function(){
                        $('#studid').removeAttr('disabled')
                        if($(this).val() == 1){
                              $('#studidlabel')[0].innerHTML = '<b>Student ID</b>';
                              $('#validatestudinfoinput').removeAttr('disabled')
                              $('#studidError').text('Student ID is required')

                        }
                        else if($(this).val() == 2){
                              $('#studidlabel')[0].innerHTML = '<b>Student LRN</b>'
                              $('#validatestudinfoinput').removeAttr('disabled')
                              $('#studidError').text('Student LRN is required')
                        }
                        else{
                              $('#studid').val('')
                              $('#studid').attr('disabled','disabled')
                              $('#validatestudinfoinput').attr('disabled','disabled')
                        }     
                  })



                  function getStudentInfo(idValue){
                        $.ajax({
                              type:'GET',
                              url:'/preenrollment/get/student/information/'+idValue.val()+'/'+$('#studentdob').val()+'/1',
                              success:function(data) {

                                    var temp_gradelevel = @json($gradelevel)
                                    
                                    var strand = @json($strand)

                                    if(data[0].status == 1 || enrollmentsetup[0].type == 2){

                                          Swal.fire({
                                                type: 'success',
                                                title: 'Student Found!',
                                                showConfirmButton: false,
                                                timer: 1500
                                          })

                                          // $('#gradelevelid').val(data[0].studinfo.levelid)

                                          var grade_level_to_enroll = temp_gradelevel.findIndex(x=>x.id == data[0].studinfo.levelid)
                                          $('#gradelevelid').empty()
                                          if(enrollmentsetup[0].type == 2){
                                                $('#gradelevelid').append('<option value="'+temp_gradelevel[grade_level_to_enroll].levelname+'" selected="selected">'+temp_gradelevel[grade_level_to_enroll].levelname+'</option>')
                                          }else{
                                                // $('select[name="gradelevelid"] option').prop("disabled", false);
                                          }
                                          if(temp_gradelevel[grade_level_to_enroll + 1].acadprogid != $('#input_acadprog').val()){
                                                var suggested_acadprog = '';
                                                if(temp_gradelevel[grade_level_to_enroll + 1].acadprogid == 2){
                                                      suggested_acadprog = 'Pre-school'
                                                }else if(temp_gradelevel[grade_level_to_enroll + 1].acadprogid == 3){
                                                      suggested_acadprog = 'Grade School'
                                                }else if(temp_gradelevel[grade_level_to_enroll + 1].acadprogid == 4){
                                                      suggested_acadprog = 'Junior High School'
                                                }else if(temp_gradelevel[grade_level_to_enroll + 1].acadprogid == 5){
                                                      suggested_acadprog = 'Senior High School'
                                                }
                                                else if(temp_gradelevel[grade_level_to_enroll + 1].acadprogid == 5){
                                                      suggested_acadprog = 'College'
                                                }
                                                $('#gradeLevelError').text('Grade level to enroll was not found from the selected academic program. Please select '+suggested_acadprog+' from the academic program selection.')
                                                $('#gradelevelid').addClass('is-invalid')
                                          }

                                          $('#studstrand').val(data[0].studinfo.strandid)

                                          checkGradeLevel()

                                          $('#courseid').val(data[0].studinfo.courseid).change()
                                          $('#courseid').attr('disabled','disabled')

                                          $('input[type="radio"][value="'+data[0].studinfo.mol+'"]').prop('checked',true)

                                          if(data[0].studinfo.mol != null){
                                                $('input[type="radio"][name="withMOL"]').attr('disabled','disabled')
                                          }

                                          

                                          $('input[name="first_name"]').val(data[0].studinfo.firstname)
                                          $('input[name="middle_name"]').val(data[0].studinfo.middlename)
                                          $('input[name="last_name"]').val(data[0].studinfo.lastname)
                                          $('input[name="dob"]').val(data[0].studinfo.dob)
                                          $('input[name="suffix"]').val(data[0].studinfo.suffix)
                                        
                                          $('input[name="suffix"]').val(data[0].studinfo.suffix)
                                          $('input[name="suffix"]').val(data[0].studinfo.suffix)
                                         
                                          if(data[0].studinfo.nationality != null && data[0].studinfo.nationality != 0){
                                                $('#nationality').val(data[0].studinfo.nationality).change()
                                          }

                                          $('input[name="first_name"]').attr('readonly','readonly')
                                          $('input[name="middle_name"]').attr('readonly','readonly')
                                          $('input[name="last_name"]').attr('readonly','readonly')
                                          $('input[name="dob"]').attr('readonly','readonly')
                                          $('#nationality').attr('readonly','readonly')
                                          $('#gender').attr('readonly','readonly')
                                          $('input[name="suffix"').attr('readonly','readonly')
                                          // $('#gradelevelid').attr('readonly','readonly')

                                          // $('select[name="gradelevelid"] option:not(:selected)').prop("disabled", true);
                                          
                                          $('#gender').val(data[0].studinfo.gender)
                                          // $('#nationality').val(data[0].studinfo.nationality).change()

                                          $('input[name="email"]').val(data[0].studinfo.semail)
                                          $('input[name="contact_number"]').val(data[0].studinfo.contactno)

                                          $('input[name="father_name"]').val(data[0].studinfo.fathername)
                                          $('input[name="father_occupation"]').val(data[0].studinfo.foccupation)
                                          $('input[name="father_contact_number"]').val(data[0].studinfo.fcontactno)

                                          $('input[name="mother_name"]').val(data[0].studinfo.mothername)
                                          $('input[name="mother_occupation"]').val(data[0].studinfo.moccupation)
                                          $('input[name="mother_contact_number"]').val(data[0].studinfo.mcontactno)

                                          $('input[name="guardian_name"]').val(data[0].studinfo.guardianname)
                                          $('input[name="guardian_relation"]').val(data[0].studinfo.guardianrelation)
                                          $('input[name="guardian_contact_number"]').val(data[0].studinfo.mcontactno)
                                          
                                          if(data[0].studinfo.ismothernum == 1){
                                                $("#mother").prop("checked", true)
                                                $('#mother_contact_number').attr('required')
                                          }
                                          else if(data[0].studinfo.isfathernum == 1){
                                                $("#father").prop("checked", true)
                                                $('#father_contact_number').attr('required')
                                          }
                                          else{
                                                $("#guardian").prop("checked", true)
                                                $('#guardian_contact_number').attr('required')
                                          }
                                          $('#validatestudentinfo').modal('hide');
                                    }
                                    else if(data[0].status == 2){
                                          Swal.fire({
                                                type: 'error',
                                                title: 'Student is already Enrolled or Preenrolled!',
                                                showConfirmButton: false,
                                                timer: 1500
                                          })
                                    }
                                    else{
                                          $('#gradelevelid').val()
                                          Swal.fire({
                                                type: 'error',
                                                title: 'Student Not Found',
                                                showConfirmButton: false,
                                                timer: 1500
                                          })
                                          $('#not_found_holder').removeAttr('hidden')
                                          if( $('input[name="first_name"]').val() != "" && $('input[name="last_name"]').val() != ""){
                                                $('#regForm').trigger("reset");
                                          }
                                    }
                              },
                        })  
                  }


                  $("#contact_number").inputmask({mask: "9999-999-9999"});
                  $("#mother_contact_number").inputmask({mask: "9999-999-9999"});
                  $("#father_contact_number").inputmask({mask: "9999-999-9999"});
                  $("#guardian_contact_number").inputmask({mask: "9999-999-9999"});

                  $(document).on('click','#agree',function(){
                        if($(this).prop("checked") == true){
                              $('#nextBtn').removeAttr('disabled') 
                        }
                        else{
                              $('#nextBtn').attr('disabled','disabled') 
                        }
                  })
            })

            $(document).on('click','input[name="vacc"]',function(){
                  console.log($(this).val())
                  if($(this).val() == 0){
                        $('.vaccineform').attr('hidden','hidden')
                        $('.is-invalid').removeClass('is-invalid')
                  }else{
                        $('.vaccineform').removeAttr('hidden')
                  }
            })


            var currentTab = 0; 
            showTab(currentTab); 
            
            function showTab(n) {
            var x = document.getElementsByClassName("tab");
            x[n].style.display = "block";
            if (n == 0) {
                  document.getElementById("prevBtn").style.display = "none";
            } else {
                  document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
                  document.getElementById("nextBtn").innerHTML = "Submit";
            } else {
                  document.getElementById("nextBtn").innerHTML = "Next";
            }
            fixStepIndicator(n)
            }
            
            function nextPrev(n) {
                  
                  if(currentTab == 2 && n != -1 && $('#studtype').val() != 3 ){
                        if(!($('#dup_info').attr('hidden') == 'hidden')){
                              return false;
                        }
                  }
                
              
                  var x = document.getElementsByClassName("tab");
              
                  if ( n == 1 && !validateForm()  ) return false;

                  if(currentTab == 1){
                        // if($('#studtype').val() == 3){
                        //       if($('input[name="first_name"]').val()==''){
                        //             alert('Some fields are empty. Please click validate student information.')
                        //             return false;
                                   
                        //       }
                        // }
                  }

                  if(currentTab == 2){
                        var removedDash =  $('#contact_number').val().replace(/-/g, '')
                        var removeUnderscore =  removedDash.replace(/_/g, '')
                        if(removeUnderscore.length >= 1 && removeUnderscore.length < 11){
                              $('#contact_number').addClass('is-invalid')
                              $('#mobileError').text('Invalid Mobile Number')
                              return false;
                        }
                        else{
                              $('#contact_number').removeClass('is-invalid')
                              $('#mobileError').text('Mobile number is required')
                        }
                  }

                  if(currentTab == 3){
                        var fremovedDash =  $('#father_contact_number').val().replace(/-/g, '')
                        var fremoveUnderscore =  fremovedDash.replace(/_/g, '')
                        var mremovedDash =  $('#mother_contact_number').val().replace(/-/g, '')
                        var mremoveUnderscore =  mremovedDash.replace(/_/g, '')
                        var gremovedDash =  $('#guardian_contact_number').val().replace(/-/g, '')
                        var gremoveUnderscore =  gremovedDash.replace(/_/g, '')
                        if(fremoveUnderscore.length >= 1 && fremoveUnderscore.length < 11){
                              $('#father_contact_number').addClass('is-invalid')
                              $('#fmobileError').text('Invalid Mobile Number')
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Invalid Mobile Number'
                              })
                              return false;
                        }
                        else if( $('input[name=incase]:checked').length == 0){
                              $('#contact_number').removeClass('is-invalid')
                              $('#fmobileError').text('Fathers Contact Number is required')
                              if($('input[name=incase]:checked').length > 0){
                                    Toast.fire({
                                          type: 'warning',
                                          title: 'Fathers Contact Number is required'
                                    })
                              }
                        }

                        if(mremoveUnderscore.length >= 1 && mremoveUnderscore.length < 11){
                              $('#mother_contact_number').addClass('is-invalid')
                              $('#mmobileError').text('Invalid Mobile Number')
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Invalid Mobile Number'
                              })
                              return false;
                        }
                        else if( $('input[name=incase]:checked').length == 0){
                              $('#mother_contact_number').removeClass('is-invalid')
                              $('#mmobileError').text('Mother\'s Contact Number is required')
                              if($('input[name=incase]:checked').length > 0){
                                    Toast.fire({
                                          type: 'warning',
                                          title: 'Mother\'s Contact Number is required'
                                    })
                              }
                        }

                        if(gremoveUnderscore.length >= 1 && gremoveUnderscore.length < 11){
                              $('#guardian_contact_number').addClass('is-invalid')
                              $('#gmobileError').text('Invalid Mobile Number')
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Invalid Mobile Number'
                              })
                              return false;
                        }
                        else if( $('input[name=incase]:checked').length == 0){
                              $('#guardian_contact_number').removeClass('is-invalid')
                              $('#gmobileError').text('Guardian\'s Contact Number is required')
                              if($('input[name=incase]:checked').length > 0){
                                    Toast.fire({
                                          type: 'warning',
                                          title: 'Guardian\'s Contact Number is required'
                                    })
                              }
                        }
                  }

                  if (currentTab+n < x.length) {
                        x[currentTab].style.display = "none";
                  }

                  currentTab = currentTab + n;

                  // if(currentTab == 1){
                  //       if($('#gradelevelid').val() == '14' || $('#gradelevelid').val() == '15'){
                  //             $('#gradelevelid').attr('disabled','disabled')
                  //       }
                  // }
                  // else{
                  //       $('#gradelevelid').removeAttr('disabled')
                  //       if($('#gradelevelid').val() == '14' || $('#gradelevelid').val() == '15'){
                  //             $('#gradelevelid').removeAttr('disabled')
                  //             $('#studstrand').removeAttr('disabled')
                  //       }
                  // }
                  
                  if(currentTab == 0){
                        $('.card-header')[0].innerText = 'ENROLLMENT FORM'
                  }
                  if(currentTab == 1){
                        $('.card-header')[0].innerText = 'ENROLLMENT INFORMATION'
                  }
                  else if(currentTab == 2){
                        $('.card-header')[0].innerText = 'STUDENT PERSONAL INFORMATION'
                  }
                  else if(currentTab == 3){
                        $('.card-header')[0].innerText = 'PARENTS | GUARDIAN INFORMATION'
                  }
                  else if(currentTab == 4){
                        $('.card-header')[0].innerText = 'MEDICAL INFORMATION'
                  }
                  else if(currentTab == 5){
                        $('.card-header')[0].innerText = 'ENROLLMENT REQUIREMENTS'
                  }
                  else if(currentTab == 6){
                        $('.card-header')[0].innerText = 'TERMS AND AGREEMENT'
                  }

                  if(currentTab == 6){
                        $('#agree').prop("checked",false)
                        $('#nextBtn').attr('disabled','disabled')

                  }else{
                        $('#nextBtn').removeAttr('disabled')
                  }
                
                  if (currentTab >= x.length) {
                        document.getElementById("regForm").submit();
                        return false;
                  }
                  else{
                        showTab(currentTab);
                  }
              
            }
            
            function validateForm() {
                  var x, y, i, valid = true;
                  x = document.getElementsByClassName("tab");
                  y = x[currentTab].getElementsByTagName("input");
                  if(currentTab == 3){
                        if($('input[name=incase]:checked').length == 0){
                              $('input[name=incase]').className += " is-invalid"
                              $('#incaseinvalid').css('display','block')
                              $('#incaseholder').css('border','solid 1px red')
                              $('#incaseholder').css('padding','10px 0')
                              valid = false;
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Please select a contact person'
                              })
                        }
                        else{
                              $('#father_name').removeClass('is-invalid')
                              $('#mother_name').removeClass('is-invalid')
                              $('#guardian_name').removeClass('is-invalid')
                              $('#father_contact_number').removeClass('is-invalid')
                              $('#mother_contact_number').removeClass('is-invalid')
                              $('#guardian_contact_number').removeClass('is-invalid')
                              $('#father_name').removeAttr('required')
                              $('#mother_name').removeAttr('required')
                              $('#guardian_name').removeAttr('required')
                              if($('input[name="incase"]:checked').val() == 1){
                                    $('#father_contact_number').attr('required','required');
                                    $('#father_name').attr('required','required');
                                    if($('#father_contact_number').val() == '' && $('#father_name').val() == ''){
                                          $('#father_contact_number').attr('required','required');
                                          $('#father_name').attr('required','required');
                                          $('#incasetext').text('Fathers\'s information is not complete.')
                                          valid = false;
                                    }
                              }
                              else if($('input[name="incase"]:checked').val() == 2){
                                    $('#mother_contact_number').attr('required','required');
                                    $('#mother_name').attr('required','required');
                                    if($('#mother_contact_number').val() == ''){
                                          $('#mother_contact_number').attr('required','required');
                                          $('#mother_name').attr('required','required');
                                          $('#incasetext').text('Mothers\'s information is not complete.')
                                          valid = false;
                                    }
                              }
                              else if($('input[name="incase"]:checked').val() == 3){
                                    $('#guardian_contact_number').attr('required','required');
                                    $('#guardian_name').attr('required','required');
                                    if($('#guardian_contact_number').val() == ''){
                                          $('#guardian_contact_number').attr('required','required');
                                          $('#guardian_name').attr('required','required');
                                          $('#incasetext').text('Guardian\'s information is not complete.')
                                          valid = false;
                                    }
                              }

                              if(!valid){
                                    $('input[name=incase]').className += " is-invalid"
                                    $('#incaseinvalid').css('display','block')
                                    $('#incaseholder').css('border','solid 1px red')
                                    $('#incaseholder').css('padding','10px 0')

                              }
                              else{
                                    $('#incaseinvalid').removeAttr('style')
                                    $('#incaseholder').removeAttr('style')
                              }
                        }

                  }else if(currentTab == 4){

                        if($('input[name="vacc"]:checked').length == 0){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Please select vaccine status.'
                              })
                              valid = false
                        }else{
                              valid = true
                        }
                  }


                  if(currentTab == 4){
                        if($('input[name="vacc"]:checked').val() == 1){
                              for (i = 0; i < y.length; i++) {
                                    if (y[i].value == "" && $(y[i]).attr('required') != undefined && ( $('#studtype').val() != 3 )) {
                                          y[i].className += " is-invalid";
                                          invalidCount += 1
                                          valid = false;
                                    }
                                    else{
                                          $(y[i]).removeClass('is-invalid')
                                    }
                              }

                              yselect = x[currentTab].getElementsByTagName("select");

                              for (i = 0; i < yselect.length; i++) {
                                    if (yselect[i].value == "" && $(yselect[i]).attr('required') != undefined) {
                                          yselect[i].className += " is-invalid";
                                          invalidCount += 1
                                          valid = false;
                                    }
                                    else{
                                          $(yselect[i]).removeClass('is-invalid')
                                    }
                              }
                        }
                  }else{
                        var invalidCount = 0;
            
                        for (i = 0; i < y.length; i++) {
                              if (y[i].value == "" && $(y[i]).attr('required') != undefined && ( $('#studtype').val() != 3 )) {
                                    y[i].className += " is-invalid";
                                    invalidCount += 1
                                    valid = false;
                              }
                              else{
                                    $(y[i]).removeClass('is-invalid')
                              }
                        }

                        yselect = x[currentTab].getElementsByTagName("select");

                        for (i = 0; i < yselect.length; i++) {
                              if (yselect[i].value == "" && $(yselect[i]).attr('required') != undefined) {
                                    yselect[i].className += " is-invalid";
                                    invalidCount += 1
                                    valid = false;
                              }
                              else{
                                    $(yselect[i]).removeClass('is-invalid')
                              }
                        }

                        if($('#studtype').val() == 3 && currentTab == 2){
                              if($('#contact_number').val() == ''){
                                    invalidCount += 1
                                    $('#contact_number').addClass('is-invalid')
                                    valid = false;  
                              }
                              else{
                                    $('#contact_number').removeClass('is-invalid')
                              }
                        }

                        if($('#studtype').val() == 3 && currentTab == 3){
                              for (i = 0; i < y.length; i++) {
                                    if (y[i].value == "" && $(y[i]).attr('required')) {
                                          invalidCount += 1
                                          y[i].className += " is-invalid";
                                          valid = false;
                                    }
                                    else{
                                          $(y[i]).removeClass('is-invalid')
                                    }
                              }

                        }
                  }

                  

                  if(currentTab == 2 && invalidCount != 0){
                        Toast.fire({
                              type: 'warning',
                              title: 'Please fill in necesssary fields'
                        })
                  }
                  
                  @if($schoolInfo->withMOL == 1 )
                        
                        if(currentTab == 1){
                              if($('input[name="withMOL"]:checked').val() == 0){
                                    $('#molErrorInput').attr('class','is-invalid')
                                    valid = false;
                              }else{
                                    $('#molErrorInput').removeClass('is-invalid')
                              }

                        }
                  @endif      

                  if (valid) {
                        document.getElementsByClassName("step")[currentTab].className += " finish";
                  }
                  return valid;
            }
            
            function fixStepIndicator(n) {
                  var i, x = document.getElementsByClassName("step");
                  for (i = 0; i < x.length; i++) {
                        x[i].className = x[i].className.replace(" active", "");
                  }
                  x[n].className += " active";
            }


            function clearInput(){
               
                  $('#studid').val('')
                  $('#gradelevelid').val('')
                  $('#studentdob').val('')
                  $('#gender').val('')
                  $('#lastschoolatt').val('')
                  $('#nationality').val(77).change()
                  $('input[name="email"]').val('')
                  $('input[name="contact_number"]').val('')
                  $('input[name="father_name"]').val('')
                  $('input[name="father_occupation"]').val('')
                  $('input[name="father_contact_number"]').val('')
                  $('input[name="mother_name"]').val('')
                  $('input[name="mother_occupation"]').val('')
                  $('input[name="mother_contact_number"]').val('')
                  $('input[name="guardian_name"]').val('')
                  $('input[name="guardian_relation"]').val('')
                  $('input[name="guardian_contact_number"]').val('')
                  $('#studid-formgroup').attr('hidden','hidden')
                  $('.form-control').removeClass('is-invalid')
                  $('input[name="first_name"]').removeAttr('readonly')
                  $('input[name="middle_name"]').removeAttr('readonly')
                  $('input[name="last_name"]').removeAttr('readonly')
                  $('input[name="dob"]').removeAttr('readonly')
                  $('input[name="suffix"]').removeAttr('readonly')
                  $('#gender').removeAttr('readonly')

                  $('input[name="first_name"]').val('')
                  $('input[name="middle_name"]').val('')
                  $('input[name="last_name"]').val('')
                  $('input[name="dob"]').val('')
                  $('input[name="suffix"]').val('')
                  $('#gender').val('')
                  $('#studstrand').val('').change()

                  $('#gradelevelid').val('').change()
                  $('#studstrand').removeAttr('required')
                  $('#strand-formgroup').attr('hidden','hidden')
                  $('#courseid').removeAttr('required')
                  $('.course-formgroup').attr('hidden','hidden')

                  with_dup = false

            }
      </script>

      <script>
            var vaccine_list = []
            get_vaccine()
            function get_vaccine(){
                  $.ajax({
                  type:'GET',
                  url:'/setup/vaccinetype/list',
                  success:function(data) {
                        vaccine_list = data
                        $('#vacc_type_1st').empty()
                        $('#vacc_type_1st').append('<option value="">Vaccine (1st Dose)</option>')
                        $('#vacc_type_1st').append('<option value="create"><i class="fas fa-plus"></i>Create Vaccine Type</option>')
                        $("#vacc_type_1st").select2({
                              data: vaccine_list,
                              allowClear: true,
                              placeholder: "Vaccine (1st Dose)",
                              theme: 'bootstrap4'
                        })

                        $('#vacc_type_2nd').empty()
                        $('#vacc_type_2nd').append('<option value="">Vaccine (2nd Dose)</option>')
                        $("#vacc_type_2nd").select2({
                              data: vaccine_list,
                              allowClear: true,
                              placeholder: "Vaccine (2nd Dose)",
                              theme: 'bootstrap4'
                        })

                        $('#vacc_type_booster').empty()
                        $('#vacc_type_booster').append('<option value="">Vaccine (Booster Shot)</option>')
                        $("#vacc_type_booster").select2({
                              data: vaccine_list,
                              allowClear: true,
                              placeholder: "Vaccine (Booster Shot)",
                              theme: 'bootstrap4'
                        })
                       
                  },
                  })
            }
      </script>
    

@endsection
      
            