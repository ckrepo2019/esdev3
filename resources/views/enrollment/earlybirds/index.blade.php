
@extends('registrar.layouts.app')
@section('content')

<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">

<!-- Tempusdominus Bbootstrap 4 -->
<link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<!-- Bootstrap4 Duallistbox -->
<link rel="stylesheet" href="{{asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')}}">
<!-- Toastr -->
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
{{-- <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}"> --}}
<link rel="stylesheet" href="{{asset('plugins/bs-stepper/css/bs-stepper.min.css')}}">
<style>
    .select2-container .select2-selection--single {
            height: 40px;
        }
    select[readonly].select2-hidden-accessible + .select2-container {
        pointer-events: none;
        touch-action: none;
    }

    select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
        background: #eee;
        box-shadow: none;
    }

    select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow, select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
        display: none;
    }
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Early Registration</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item"><a href="/registrar/earlyregistration">Early Registration</a></li>
                    <li class="breadcrumb-item active">Register Students</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</section>
<section class="content">
    <div class="card">
        <div class="card-body" hidden>
            <div class="row mb-2">
                <div class="col-md-4 col-4">
                    <label>School Year</label>
                    <select class="form-control" id="selectedschoolyear">
                        @foreach ($schoolyears as $schoolyear)
                            <option value="{{$schoolyear->id}}" @if($schoolyear->isactive == 1) selected @endif>{{$schoolyear->sydesc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-4">
                    <label>Semester</label>
                    <select class="form-control" id="selectedsemester">
                        @foreach ($semesters as $semester)
                            <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-4">
                    <label>Grade Level</label>
                    <select class="form-control" id="selectedgradelevel">
                        @foreach ($gradelevels as $gradelevel)
                            <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-6">
                    <label>Strand</label>
                    <select class="form-control" id="selectedstrand">
        
                    </select>
                </div>
                <div class="col-md-4 col-6">
                    <label>Course</label>
                    <select class="form-control" id="selectedcourse">
                        
                    </select>
                </div>
                <div class="col-md-4 text-right">
                    <label>&nbsp;</label><br/>
                    <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                    <button type="button" class="btn btn-info" id="btn-addstudent"><i class="fa fa-plus"></i> Add Student</button>
                </div>
            </div>
        </div>
    </div>
    <div id="container-add"></div>
    <div id="container-results"></div>
</section>
<div class="modal fade" id="modal-program" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >New Scholarship Program</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" >
                <input type="text" class="form-control" placeholder="Program name" id="input-newprogram" required/>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="submit-newclose">Close</button>
                <button type="button" class="btn btn-primary" id="submit-newprogram">Submit</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-edit-program" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >Create Student Data</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btn-create-close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" >
                <div id="stepper1" class="bs-stepper">
                    <div class="bs-stepper-header" role="tablist">
                      <div class="step" data-target="#test-l-1">
                        <button type="button" class="btn btn-link step-trigger" role="tab" id="stepper1trigger1" aria-controls="test-l-1">
                          <span class="bs-stepper-circle">1</span>
                          <span class="bs-stepper-label">Personal Info</span>
                        </button>
                      </div>
                      <div class="line"></div>
                      <div class="step" data-target="#test-l-2">
                        <button type="button" class="btn btn-link step-trigger" role="tab" id="stepper1trigger2" aria-controls="test-l-2">
                          <span class="bs-stepper-circle">2</span>
                          <span class="bs-stepper-label">Contact Info</span>
                        </button>
                      </div>
                      <div class="line"></div>
                      <div class="step" data-target="#test-l-3">
                        <button type="button" class="btn btn-link step-trigger" role="tab" id="stepper1trigger3" aria-controls="test-l-3">
                          <span class="bs-stepper-circle">3</span>
                          <span class="bs-stepper-label">Others</span>
                        </button>
                      </div>
                    </div>
                    <div class="bs-stepper-content">
                        <div id="test-l-1" role="tabpanel" class="content" aria-labelledby="stepper1trigger1">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label>Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="create-lastname"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label>First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="create-firstname"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-8">
                                    <label>Middle Name</label>
                                    <input type="text" class="form-control" id="create-middlename"/>
                                </div>
                                <div class="col-md-4">
                                    <label>Suffix</label>
                                    <input type="text" class="form-control" id="create-suffix"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label>DOB <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="create-dob"/>
                                </div>
                                <div class="col-md-4">
                                    <label>Gender</label>
                                    <select class="form-control" id="create-gender">
                                        <option value="MALE">MALE</option>
                                        <option value="FEMALE">FEMALE</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Contact No.</label>
                                    <input type="number" class="form-control" id="create-contactno"/>
                                </div>
                            </div>
                            <hr/>
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <label>Street</label>
                                    <input type="text" class="form-control" id="create-street"/>
                                </div>
                                <div class="col-md-3">
                                    <label>Barangay</label>
                                    <input type="text" class="form-control" id="create-barangay"/>
                                </div>
                                <div class="col-md-3">
                                    <label>City</label>
                                    <input type="text" class="form-control" id="create-city"/>
                                </div>
                                <div class="col-md-3">
                                    <label>Province</label>
                                    <input type="text" class="form-control" id="create-province"/>
                                </div>
                            </div>
                            <hr/>
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <label>Blood Type</label>
                                    <select class="form-control" id="create-bloodtype">
                                        <option value=""></option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="AB">AB</option>
                                        <option value="O">O</option>
                                    </select>
                                </div>
                                <div class="col-md-9">
                                    <label>Allergies</label>
                                    <textarea type="text" class="form-control" id="create-allergies"></textarea>
                                </div>
                            </div>
                            <hr/>
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <label>Nationality</label>
                                    <select class="form-control" id="create-nationality">
                                        <option value=""></option>
                                        @foreach ($nationalities as $nationality)
                                            <option value="{{$nationality->id}}">{{$nationality->nationality}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Religion</label>
                                    <select class="form-control" id="create-religion">
                                        <option value=""></option>
                                        @foreach ($religions as $religion)
                                            <option value="{{$religion->id}}">{{$religion->religionname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Mother Tongue</label>
                                    <select class="form-control" id="create-mothertongue">
                                        <option value=""></option>
                                        @foreach ($mothertongues as $mothertongue)
                                            <option value="{{$mothertongue->id}}">{{$mothertongue->mtname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Ethnic Group</label>
                                    <select class="form-control" id="create-ethnicgroup">
                                        <option value=""></option>
                                        @foreach ($ethnics as $ethnicgroup)
                                            <option value="{{$ethnicgroup->id}}">{{$ethnicgroup->egname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-primary steppernext" >Next</button>
                                </div>
                            </div>
                        </div>
                        <div id="test-l-2" role="tabpanel" class="content" aria-labelledby="stepper1trigger2">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label>Father's Name</label>
                                    <input type="text" class="form-control" id="create-fathername"/>
                                </div>
                                <div class="col-md-4">
                                    <label>Occupation</label>
                                    <input type="text" class="form-control" id="create-foccupation"/>
                                </div>
                                <div class="col-md-4">
                                    <label>Contact No.</label>
                                    <input type="number" class="form-control" id="create-fcontactno"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label>Mother's Name</label>
                                    <input type="text" class="form-control" id="create-mothername"/>
                                </div>
                                <div class="col-md-4">
                                    <label>Occupation</label>
                                    <input type="text" class="form-control" id="create-moccupation"/>
                                </div>
                                <div class="col-md-4">
                                    <label>Contact No.</label>
                                    <input type="number" class="form-control" id="create-mcontactno"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label>Guardian's Name</label>
                                    <input type="text" class="form-control" id="create-guardianname"/>
                                </div>
                                <div class="col-md-4">
                                    <label>Relation</label>
                                    <input type="text" class="form-control" id="create-guardianrelation"/>
                                </div>
                                <div class="col-md-4">
                                    <label>Contact No.</label>
                                    <input type="number" class="form-control" id="create-gcontactno"/>
                                </div>
                            </div>
                            <hr/>
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label>Who to contact in case of emergency</label>
                                </div>
                            </div>
                            <hr/>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                        <input type="radio" id="radioPrimary1" name="whotocontact" checked="" value="f">
                                        <label for="radioPrimary1">
                                            Father
                                        </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                        <input type="radio" id="radioPrimary2" name="whotocontact" checked="" value="m">
                                        <label for="radioPrimary2">
                                            Mother
                                        </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                        <input type="radio" id="radioPrimary3" name="whotocontact" checked="" value="g">
                                        <label for="radioPrimary3">
                                            Guardian
                                        </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 text-left">
                                    <button type="button" class="btn btn-primary stepperprev" >Previous</button>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="button" class="btn btn-primary steppernext" >Next</button>
                                </div>
                            </div>
                        </div>
                        <div id="test-l-3" role="tabpanel" class="content" aria-labelledby="stepper1trigger3">
                          {{-- <div class="valid-feedback">
                            Form Looks good!
                          </div>
                          <div class="invalid-feedback">
                            Form invalid
                          </div> --}}
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <label>LRN</label>
                                    <input type="number" class="form-control" id="create-lrn"/>
                                </div>
                                <div class="col-md-3">
                                    <label>RFID</label>
                                    <input type="number" class="form-control" id="create-rfid"/>
                                </div>
                                <div class="col-md-3">
                                    <label>Grantee</label>
                                    <select class="form-control" id="create-grantee">
                                        @foreach ($grantees as $grantee)
                                            <option value="{{$grantee->id}}" @if($grantee->id == 1) selected @endif>{{$grantee->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Mode Of Learning</label>
                                    <select class="form-control" id="create-mol">
                                        @foreach ($mols as $mol)
                                            <option value="{{$mol->id}}">{{$mol->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> 
                            <div class="row mb-2">
                                <div class="col-md-8">
                                    <label>School Last Attended</label>
                                    <input type="text" class="form-control" id="create-schoollastatt"/>
                                </div>
                                <div class="col-md-4">
                                    <label>School Year Last Attended</label>
                                    <input type="number" class="form-control" id="create-schoolyearlastatt"/>
                                </div>
                            </div> 
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label>Grade Level</label>
                                    <select class="form-control" id="create-levelid">
                                        @foreach ($gradelevels as $gradelevel)
                                            <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Student Type</label>
                                    <select class="form-control" id="create-studtype">
                                        <option value="new">NEW</option>
                                        <option value="transferee">TRANSFEREE</option>
                                    </select>
                                    {{-- <input type="text" class="form-control" id="create-studtype"/> --}}
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <br/>
                                    <div class="form-group clearfix mt-2">
                                      <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="create-pantawid">
                                        <label for="create-pantawid">
                                            Pantawid
                                        </label>
                                      </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-md-6 text-left">
                                    <button type="button" class="btn btn-primary stepperprev mt-5" >Previous</button>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="button" class="btn btn-primary mt-5" id="btn-createsubmit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                  
                {{-- <div class="bs-stepper">
                    <div class="bs-stepper-header" role="tablist">
                      <!-- your steps here -->
                      <div class="step" data-target="#logins-part">
                        <button type="button" class="step-trigger" role="tab" aria-controls="logins-part" id="logins-part-trigger">
                          <span class="bs-stepper-circle">1</span>
                          <span class="bs-stepper-label">Logins</span>
                        </button>
                      </div>
                      <div class="line"></div>
                      <div class="step" data-target="#information-part">
                        <button type="button" class="step-trigger" role="tab" aria-controls="information-part" id="information-part-trigger">
                          <span class="bs-stepper-circle">2</span>
                          <span class="bs-stepper-label">Various information</span>
                        </button>
                      </div>
                    </div>
                    <div class="bs-stepper-content">
                      <!-- your steps content here -->
                      <div id="logins-part" class="content" role="tabpanel" aria-labelledby="logins-part-trigger">
                        <div class="form-group">
                          <label for="exampleInputEmail1">Email address</label>
                          <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                          <label for="exampleInputPassword1">Password</label>
                          <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                        </div>
                        <button class="btn btn-primary" id="steppernext">Next</button>
                      </div>
                      <div id="information-part" class="content" role="tabpanel" aria-labelledby="information-part-trigger">
                        <div class="form-group">
                          <label for="exampleInputFile">File input</label>
                          <div class="input-group">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="exampleInputFile">
                              <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                            </div>
                            <div class="input-group-append">
                              <span class="input-group-text">Upload</span>
                            </div>
                          </div>
                        </div>
                        <button class="btn btn-primary" id="stepperprev">Previous</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </div>
                  </div> --}}
                {{-- <div class="row mb-2">
                    <div class="col-md-12">
                        <label>LRN</label>
                        <input type="text" class="form-control" id="create-lrn"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <label>Last Name</label>
                        <input type="text" class="form-control" id="create-lastname"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <label>First Name</label>
                        <input type="text" class="form-control" id="create-firstname"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-8">
                        <label>Middle Name</label>
                        <input type="text" class="form-control" id="create-middlename"/>
                    </div>
                    <div class="col-md-4">
                        <label>Suffix</label>
                        <input type="text" class="form-control" id="create-suffix"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label>DOB</label>
                        <input type="date" class="form-control" id="create-dob"/>
                    </div>
                    <div class="col-md-4">
                        <label>Gender</label>
                        <select class="form-control" id="create-gender">
                            <option value="MALE">MALE</option>
                            <option value="FEMALE">FEMALE</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Contact No.</label>
                        <input type="number" class="form-control" id="create-contactno"/>
                    </div>
                </div>
                <hr/>
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label>Street</label>
                        <input type="text" class="form-control" id="create-street"/>
                    </div>
                    <div class="col-md-3">
                        <label>Barangay</label>
                        <input type="text" class="form-control" id="create-barangay"/>
                    </div>
                    <div class="col-md-3">
                        <label>City</label>
                        <input type="text" class="form-control" id="create-city"/>
                    </div>
                    <div class="col-md-3">
                        <label>Province</label>
                        <input type="text" class="form-control" id="create-province"/>
                    </div>
                </div>
                <hr/>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label>Religion</label>
                        <select class="form-control" id="create-religion">
                            <option value=""></option>
                            @foreach ($religions as $religion)
                                <option value="{{$religion->id}}">{{$religion->religionname}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Mother Tongue</label>
                        <select class="form-control" id="create-mothertongue">
                            <option value=""></option>
                            @foreach ($mothertongues as $mothertongue)
                                <option value="{{$mothertongue->id}}">{{$mothertongue->mtname}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Ethnic Group</label>
                        <select class="form-control" id="create-ethnicgroup">
                            <option value=""></option>
                            @foreach ($ethnics as $ethnicgroup)
                                <option value="{{$ethnicgroup->id}}">{{$ethnicgroup->egname}}</option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}
            </div>
            {{-- <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="submit-editclose">Close</button>
                <div class="text-right">
                    <button type="button" class="btn btn-danger" id="submit-deleteprogram">Delete</button>
                    <button type="button" class="btn btn-primary" id="submit-editprogram">Update</button>
                </div>
            </div> --}}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
{{-- <div class="modal fade" id="modal-add-scholarship" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >Scholarships Granted</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div id="selectcontainer">

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div> --}}
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- BS-Stepper -->
<script src="{{asset('plugins/bs-stepper/js/bs-stepper.min.js')}}"></script>
<!-- Toastr -->
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('dist/js/demo.js')}}"></script>
<script>
    $(document).ready(function(){
        // var stepper = new Stepper($('.bs-stepper')[0])
        var stepper1 = new Stepper(document.querySelector('#stepper1'));
        var form = document.querySelector('form');
        var validFormFeedback = document.querySelector('#test-l-3 .valid-feedback');
        var inValidFormFeedback = document.querySelector('#test-l-3 .invalid-feedback');

        $('.steppernext').on('click', function(){
            stepper1.next()
        })
        $('.stepperprev').on('click', function(){
            stepper1.previous()
        })
        form.addEventListener('submit', function(event) {
        form.classList.remove('was-validated');
        inValidFormFeedback.classList.remove('d-block');
        validFormFeedback.classList.remove('d-block');
        
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            inValidFormFeedback.classList.add('d-block');
        } else {
            validFormFeedback.classList.add('d-block');
        }

        form.classList.add('was-validated');
        }, false);
        // var stepper = new Stepper($('.bs-stepper')[0])
        // $('#steppernext').on('click', function(){
        //     stepper.next()
        // })
        // $('#stepperprev').on('click', function(){
        //     stepper.previous()
        // })
        
        $('#selectedgradelevel').select2();
        $('#selectedstrand').select2();
        $('#selectedcourse').select2();
        $('#create-religion').select2();
        $('#create-mothertongue').select2();
        $('#create-ethnicgroup').select2();
        $('#create-nationality').select2();
        $('#create-bloodtype').select2();
        $('#create-grantee').select2();
        $('#create-mol').select2();
        $('#create-levelid').select2();
        var selectedschoolyear      = $('#selectedschoolyear').val();
        var selectedsemester        = $('#selectedsemester').val();
        var selectedgradelevel      = $('#selectedgradelevel').val();
        var selectedstrand          = null;
        var selectedcourse          = null;

        $('#selectedschoolyear').on('change', function(){
            selectedschoolyear = $(this).val();
        })
        $('#selectedsemester').on('change', function(){
            selectedsemester = $(this).val();
        })
        $('#selectedgradelevel').on('change', function(){
            selectedgradelevel = $(this).val();
            $.ajax({
                url:        '/earlybirds/getotherfilter',
                type:       'GET',
                datatype:   'json',
                data :      {
                    selectedgradelevel : selectedgradelevel
                },
                success:function(data)
                {
                    selectedstrand = null;
                    selectedcourse = null;
                    $('#selectedstrand').empty()
                    $('#selectedcourse').empty()
                    // console.log(data)
                    // console.log(data.strands)
                    if(data.strands.length>0)
                    {
                        $('#selectedstrand').append(
                            '<option value="">Select a strand</option>'
                        )
                        $.each(data.strands, function(key,value){
                            // console.log(value.id)
                            $('#selectedstrand').append(
                                '<option value="'+value.id+'">'+value.strandname+'</option>'
                            )
                        })
                    }
                    if(data.courses.length>0)
                    {
                        $('#selectedcourse').append(
                            '<option value="">Select a course</option>'
                        )
                        $.each(data.courses, function(key,value){
                            // console.log(value.id)
                            $('#selectedcourse').append(
                                '<option value="'+value.id+'">'+value.courseabrv+'</option>'
                            )
                        })
                    }
// selectedcourse
                }
            })
        })
        $('#selectedstrand').on('change', function(){
            selectedstrand = $(this).val();
        })
        $('#selectedcourse').on('change', function(){
            selectedcourse = $(this).val();
        })
        $('#btn-generate').on('click', function(){
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })

            $.ajax({
                url: '/earlybirds/generatefilter',
                type: 'GET',
                data: {
                    selectedschoolyear         : selectedschoolyear,
                    selectedsemester           : selectedsemester,
                    selectedgradelevel         : selectedgradelevel,
                    selectedstrand             : selectedstrand,
                    selectedcourse             : selectedcourse
                },
                success:function(data){
                    
                    $('#container-add').empty();
                    $('#container-results').empty();
                    $('#container-results').append(data)
                    
                    var table = $("#table-results").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "bPaginate": false,
                        "bInfo" : false,
                        "bFilter" : false,
                        "order": [[ 1, 'asc' ]]
                    });
                    table.on( 'order.dt search.dt', function () {
                        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            })

        })
        $('#btn-addstudent').on('click', function(){
            $('#container-add').empty()
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')            
                $.ajax({
                    url: 'https://spct-essentiel.ckgroup.ph/api/earlybirds/getstudents',
                    type: 'GET',
                    success:function(data){

                        $('#container-add').append(data)
                        $('#selectedstudent').select2();
                        $('#selectedstudentsy').select2();
                        $('#selectedstudentsem').select2();
                        $('#selectedstudentlevel').select2();
                        $('#selectedstudentstrand').select2();
                        $('#selectedstudentcourse').select2();
                        $('#btn-addsubmit').prop('disabled', true)
                    }
                })
            @else
                $.ajax({
                    url: '/earlybirds/getstudents',
                    type: 'GET',
                    success:function(data){

                        $('#container-add').append(data)
                        $('#selectedstudent').select2();
                        $('#selectedstudentsy').select2();
                        $('#selectedstudentsem').select2();
                        $('#selectedstudentlevel').select2();
                        $('#selectedstudentstrand').select2();
                        $('#selectedstudentcourse').select2();
                        $('#btn-addsubmit').prop('disabled', true)
                    }
                })
            @endif
        })
        $('#btn-addstudent').click();
        var addselectstrandid = 0;
        var addselectcourseid = 0;
        $(document).on('change','#selectedstudent', function(){
            $('#selectedstudentstrand').attr('readonly');
            $('#btn-addsubmit').prop('disabled', true)
            addselectstrandid = 0;
            addselectcourseid = 0;
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')   
                $.ajax({
                    url: 'https://spct-essentiel.ckgroup.ph/api/earlybirds/getinfo',
                    type:       'GET',
                    dataType:   'json',
                    data :      {
                        studid : $(this).val(),
                        semid :$('#selectedstudentsem').val(),
                    },
                    success:function(data)
                    {
                        addselectstrandid = data.enrollstrandid;
                        addselectcourseid = data.enrollcourseid;

                        $('#selectedstudentlevel').select2("trigger", "select", {
                            data: { id: data.enrolllevelid }
                        })
                        $('#selectedstudentstrand').select2("trigger", "select", {
                            data: { id: data.enrollstrandid }
                        })
                        $('#selectedstudentcourse').select2("trigger", "select", {
                            data: { id: data.enrollcourseid }
                        })
                        $('#btn-addsubmit').prop('disabled', false)
                        if(data.acadprogid == 5)
                        {
                            if(data.enrollstrandid == 0)
                            {
                                $('#selectedstudentstrand').removeAttr('readonly');
                            }
                        }
                        else if(data.acadprogid == 6)
                        {
                            if(data.enrollcourseid == 0)
                            {
                                $('#selectedstudentcourse').removeAttr('readonly');
                            }
                        }else{
                                $('#selectedstudentstrand').attr('readonly');
                                $('#selectedstudentcourse').attr('readonly');
                        }
                    }
                })      
            @else
                $.ajax({
                    url:        '/earlybirds/getinfo',
                    type:       'GET',
                    dataType:   'json',
                    data :      {
                        studid : $(this).val(),
                        semid :$('#selectedstudentsem').val(),
                    },
                    success:function(data)
                    {
                        addselectstrandid = data.enrollstrandid;
                        addselectcourseid = data.enrollcourseid;

                        $('#selectedstudentlevel').select2("trigger", "select", {
                            data: { id: data.enrolllevelid }
                        })
                        $('#selectedstudentstrand').select2("trigger", "select", {
                            data: { id: data.enrollstrandid }
                        })
                        $('#selectedstudentcourse').select2("trigger", "select", {
                            data: { id: data.enrollcourseid }
                        })
                        $('#btn-addsubmit').prop('disabled', false)
                        if(data.acadprogid == 5)
                        {
                            if(data.enrollstrandid == 0)
                            {
                                $('#selectedstudentstrand').removeAttr('readonly');
                            }
                        }
                        else if(data.acadprogid == 6)
                        {
                            if(data.enrollcourseid == 0)
                            {
                                $('#selectedstudentcourse').removeAttr('readonly');
                            }
                        }else{
                                $('#selectedstudentstrand').attr('readonly');
                                $('#selectedstudentcourse').attr('readonly');
                        }
                    }
                })
            @endif
        })
        $(document).on('change', '#selectedstudentlevel', function(){
            $.ajax({
                url:        '/earlybirds/getotherfilter',
                type:       'GET',
                datatype:   'json',
                data :      {
                    selectedgradelevel : $(this).val()
                },
                success:function(data)
                {
                    $('#selectedstudentstrand').empty();
                    $('#selectedstudentstrand').append(
                        '<option value="">Select a strand</option>'
                    );
                    $('#selectedstudentcourse').empty();
                    $('#selectedstudentcourse').append(
                        '<option value="">Select a course</option>'
                    )
                    // console.log(data)
                    // console.log(data.strands)
                    if(data.strands.length>0)
                    {
                        $.each(data.strands, function(key,value){
                            var stringselectstrand = '';
                            if(addselectstrandid == value.id)
                            {
                                stringselectstrand = 'selected';
                            }
                            $('#selectedstudentstrand').append(
                                '<option value="'+value.id+'" '+stringselectstrand+'>'+value.strandname+'</option>'
                            )
                        })
                    }
                    if(data.courses.length>0)
                    {
                        $.each(data.courses, function(key,value){
                            var stringselectcourse = '';
                            if(addselectcourseid == value.id)
                            {
                                stringselectcourse = 'selected';
                            }

                            $('#selectedstudentcourse').append(
                                '<option value="'+value.id+'" '+stringselectcourse+'>'+value.courseabrv+'</option>'
                            )
                        })
                    }
                }
            })
        })
        $(document).on('click','#btn-addsubmit', function(){
            var selectedstudent         =   $('#selectedstudent').val();
            var selectedstudentsy       =   $('#selectedstudentsy').val();
            var selectedstudentsem      =   $('#selectedstudentsem').val();
            var selectedstudentlevel    =   $('#selectedstudentlevel').val();
            var selectedstudentstrand   =   $('#selectedstudentstrand').val();
            var selectedstudentcourse   =   $('#selectedstudentcourse').val();
            
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')
                $.ajax({
                    url: 'https://spct-essentiel.ckgroup.ph/api/earlyregistration/apiregister_checkifexists',
                    type: 'GET',
                    datatype:   'json',
                    data :      {
                        selectedstudent         :   selectedstudent,
                        selectedstudentsy       :   selectedstudentsy,
                        selectedstudentsem      :   selectedstudentsem,
                        selectedstudentlevel    :   selectedstudentlevel,
                        selectedstudentstrand   :   selectedstudentstrand,
                        selectedstudentcourse   :   selectedstudentcourse,
                        createdby               :   '{{auth()->user()->id}}'
                    },
                    success:function(data){

                        if(data == 1)
                        {
                            toastr.success('Added successfully!', 'Add student')
                            $('#container-add').empty()
                            $('#btn-addstudent').click();
                        }else if(data == 0)
                        {
                            toastr.warning('Student with the same form exists!', 'Add student')
                        }else{
                            toastr.error('Something went wrong!', 'Add student')
                        }
                    }
                })
            @else
                $.ajax({
                    url: '/earlybirds/addstudent',
                    type: 'GET',
                    datatype:   'json',
                    data :      {
                        selectedstudent         :   selectedstudent,
                        selectedstudentsy       :   selectedstudentsy,
                        selectedstudentsem      :   selectedstudentsem,
                        selectedstudentlevel    :   selectedstudentlevel,
                        selectedstudentstrand   :   selectedstudentstrand,
                        selectedstudentcourse   :   selectedstudentcourse
                    },
                    success:function(data){

                        if(data == 1)
                        {
                            toastr.success('Added successfully!', 'Add student')
                            $('#container-add').empty()
                            $('#btn-addstudent').click();
                        }else if(data == 0)
                        {
                            toastr.warning('Student with the same form exists!', 'Add student')
                        }else{
                            toastr.error('Something went wrong!', 'Add student')
                        }
                    }
                })
            @endif
        })
        $(document).on('click','#btn-createstudent', function(){
            $('#modal-edit-program').modal('show');
        })
        $(document).on('click','.btn-deleteearlybird', function(){
            var id = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure you want to delete this early bird?',
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete'
            })
            .then((result) => {
                if (result.value) {
                    $.ajax({
                        url:'/earlybirds/delete',
                        type:'GET',
                        data: {
                            id      :  id
                        },
                        complete:function() {
                            toastr.success('Deleted successfully!', 'Early Bird')
                            $('#earlybird'+id).remove()
                            $('#btn-generate').click();
                        }
                    })
                }
            })
        })
        $('#btn-createsubmit').on('click', function(){
            var checkvalidation = 0;
            var lastname        = $('#create-lastname').val();
            var firstname       = $('#create-firstname').val();
            var dob             = $('#create-dob').val();

            if(lastname.replace(/^\s+|\s+$/g, "").length == 0){
                checkvalidation = 1;
                $('#create-lastname').css('border','1px solid red')
            }else{
                $('#create-lastname').removeAttr('style')
            }
            if(firstname.replace(/^\s+|\s+$/g, "").length == 0){
                checkvalidation = 1;
                $('#create-firstname').css('border','1px solid red')
            }else{
                $('#create-firstname').removeAttr('style')
            }
            if(dob.replace(/^\s+|\s+$/g, "").length == 0){
                checkvalidation = 1;
                $('#create-dob').css('border','1px solid red')
            }else{
                $('#create-dob').removeAttr('style')
            }

            if(checkvalidation == 1)
            {
                toastr.warning('Please don\'t skip the required fields!', 'Create New Student')
            }else{
                var middlename      = $('#create-middlename').val();
                var suffix          = $('#create-suffix').val();
                var gender          = $('#create-gender').val();
                var contactno       = $('#create-contactno').val();
                var street          = $('#create-street').val();
                var barangay        = $('#create-barangay').val();
                var city            = $('#create-city').val();
                var province        = $('#create-province').val();
                var bloodtype       = $('#create-bloodtype').val();
                var allergies       = $('#create-allergies').val();
                var nationality     = $('#create-nationality').val();
                var religion        = $('#create-religion').val();
                var mothertongue    = $('#create-mothertongue').val();
                var ethnicgroup     = $('#create-ethnicgroup').val();
                var fathername      = $('#create-fathername').val();
                var foccupation     = $('#create-foccupation').val();
                var fcontactno      = $('#create-fcontactno').val();
                var mothername      = $('#create-mothername').val();
                var moccupation     = $('#create-moccupation').val();
                var mcontactno      = $('#create-mcontactno').val();
                var guardianname    = $('#create-guardianname').val();
                var guardianrelation = $('#create-guardianrelation').val();
                var gcontactno      = $('#create-gcontactno').val();
                var whotocontact    = $('input[name="whotocontact"]').val();
                var lrn             = $('#create-lrn').val();
                var rfid            = $('#create-rfid').val();
                var grantee         = $('#create-grantee').val();
                var mol             = $('#create-mol').val();
                var schoollastatt   = $('#create-schoollastatt').val();
                var schoolyearlastatt = $('#create-schoolyearlastatt').val();
                var levelid         = $('#create-levelid').val();
                var studtype        = $('#create-studtype').val();
                var pantawid        = 0;
                if($('#create-pantawid').prop('checked') == true)
                {
                    pantawid        = 1;
                }
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')
                    $.ajax({
                        url: 'https://spct-essentiel.ckgroup.ph/api/earlyregistration/apicreatestud_checkifexists',
                        type: 'GET',
                        datatype:   'json',
                        data :      {
                            lastname            :   lastname,
                            firstname           :   firstname,
                            middlename          :   middlename,
                            suffix              :   suffix,
                            dob                 :   dob,
                            gender              :   gender,
                            contactno           :   contactno,
                            street              :   street,
                            barangay            :   barangay,
                            city                :   city,
                            province            :   province,
                            bloodtype           :   bloodtype,
                            allergies           :   allergies,
                            nationality         :   nationality,
                            religion            :   religion,
                            mothertongue        :   mothertongue,
                            ethnicgroup         :   ethnicgroup,
                            fathername          :   fathername,
                            foccupation         :   foccupation,
                            fcontactno          :   fcontactno,
                            mothername          :   mothername,
                            moccupation         :   moccupation,
                            mcontactno          :   mcontactno,
                            guardianname        :   guardianname,
                            guardianrelation    :   guardianrelation,
                            gcontactno          :   gcontactno,
                            whotocontact        :   whotocontact,
                            lrn                 :   lrn,
                            rfid                :   rfid,
                            grantee             :   grantee,
                            mol                 :   mol,
                            schoollastatt       :   schoollastatt,
                            schoolyearlastatt   :   schoolyearlastatt,
                            levelid             :   levelid,
                            studtype            :   studtype,
                            pantawid            :   pantawid,
                            createdby               :   '{{auth()->user()->id}}'
                        },
                        success:function(data){

                            if(data == 0)
                            {
                                toastr.error('Something went wrong!', 'Create New Student')
                            }else if(data == 1){
                                toastr.warning('Student with the same form exists!', 'Create New Student')
                            }else
                            {
                                toastr.success('Created successfully!', 'Create New Student')
                                $('#btn-create-close').click();
                                // $('#stepper1 input,textarea,select').val('');
                                $('#stepper1').find('input').val('')
                                $('#stepper1').find('textarea').val('')
                                $('#stepper1').find('select').val('')
                                $('#selectedstudent').append(
                                    '<option value="'+data.id+'">'+data.lastname+', '+data.firstname+'</option>'
                                )
                                $('#selectedstudent').val(data.id)
                                $('#selectedstudentlevel').select2("trigger", "select", {
                                    data: { id: data.levelid }
                                })
                                $('#selectedstudentstrand').removeAttr('readonly');
                                $('#btn-addsubmit').prop('disabled', false)
                            }
                        }
                    })
                @else
                    $.ajax({
                        url: '/earlybirds/createstudent',
                        type: 'GET',
                        datatype:   'json',
                        data :      {
                            lastname            :   lastname,
                            firstname           :   firstname,
                            middlename          :   middlename,
                            suffix              :   suffix,
                            dob                 :   dob,
                            gender              :   gender,
                            contactno           :   contactno,
                            street              :   street,
                            barangay            :   barangay,
                            city                :   city,
                            province            :   province,
                            bloodtype           :   bloodtype,
                            allergies           :   allergies,
                            nationality         :   nationality,
                            religion            :   religion,
                            mothertongue        :   mothertongue,
                            ethnicgroup         :   ethnicgroup,
                            fathername          :   fathername,
                            foccupation         :   foccupation,
                            fcontactno          :   fcontactno,
                            mothername          :   mothername,
                            moccupation         :   moccupation,
                            mcontactno          :   mcontactno,
                            guardianname        :   guardianname,
                            guardianrelation    :   guardianrelation,
                            gcontactno          :   gcontactno,
                            whotocontact        :   whotocontact,
                            lrn                 :   lrn,
                            rfid                :   rfid,
                            grantee             :   grantee,
                            mol                 :   mol,
                            schoollastatt       :   schoollastatt,
                            schoolyearlastatt   :   schoolyearlastatt,
                            levelid             :   levelid,
                            studtype            :   studtype,
                            pantawid            :   pantawid
                        },
                        success:function(data){

                            if(data == 0)
                            {
                                toastr.error('Something went wrong!', 'Create New Student')
                            }else if(data == 1){
                                toastr.warning('Student with the same form exists!', 'Create New Student')
                            }else
                            {
                                toastr.success('Created successfully!', 'Create New Student')
                                $('#btn-create-close').click();
                                // $('#stepper1 input,textarea,select').val('');
                                $('#stepper1').find('input').val('')
                                $('#stepper1').find('textarea').val('')
                                $('#stepper1').find('select').val('')
                                $('#selectedstudent').append(
                                    '<option value="'+data.id+'">'+data.lastname+', '+data.firstname+'</option>'
                                )
                                $('#selectedstudent').val(data.id)
                                $('#selectedstudentlevel').select2("trigger", "select", {
                                    data: { id: data.levelid }
                                })
                                $('#selectedstudentstrand').removeAttr('readonly');
                                $('#btn-addsubmit').prop('disabled', false)
                            }
                        }
                    })
                @endif
            }
        })
    })
</script>
@endsection
