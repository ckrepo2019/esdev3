@extends('studentPortal.layouts.app2')


@section('pagespecificscripts')

    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        fieldset.scheduler-border {
            border: 2px groove #ddd !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            -webkit-box-shadow:  0px 0px 0px 0px #000;
                        box-shadow:  0px 0px 0px 0px #000;
            background-color: #fbfbfb;
            min-height: 400px; 
            text-align:center;
        }

        .select2-container--default .select2-selection--single {
            height: 38px;
        }
      
    </style>

@endsection


@section('content')

    @php
        $schoolyear = DB::table('sy')->orderBy('sydesc')->get();
        $schoolinfo = DB::table('schoolinfo')->first();
        $semester = DB::table('semester')->get();
        $gradelevel = DB::table('gradelevel')->where('deleted',0)->get();
        $student = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->select('id','sid','levelid','contactno','fcontactno','gcontactno','mcontactno')->first();
        $studid =  $student->id;
        $studsid =  $student->sid;
        $current_gradelevel =  $student ->levelid;
        
    @endphp

<div class="modal fade" id="fees_modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
          <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                      <h4 class="modal-title" style="font-size: 1.1rem !important">Fees</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0" id="fees_holder">
                     
                </div>
          </div>
    </div>
</div>   

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Enrollment</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Enrollment</li>
            </ol>
            </div>
        </div>
    </div>
</section>
<section class="content pt-0">
    <div class="container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 form-group mb-0">
                            <label for="">School Year</label>
                            <select name="" id="filter_sy" class="form-control select2">
                                @foreach ($schoolyear as $item)
                                    <option value="{{$item->id}}" {{$item->isactive == 1 ? 'selected=selected':''}}>{{$item->sydesc}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 form-group mb-0" id="filter_sem_holder" hidden>
                            <label for="">Semester</label>
                            <select name="" id="filter_sem" class="form-control select2">
                                @foreach ($semester as $item)
                                    <option value="{{$item->id}}" {{$item->isactive == 1 ? 'selected=selected':''}}>{{$item->semester}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-10" hidden>
                            <button class="btn btn-primary btn-sm float-right" id="view_fees_modal">View Fees</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row with_enrollment" hidden>
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary">
                    <h3 class="card-title">
                        <i class="fas fa-info"></i>
                        Enrollment Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-2">
                                    School Year 
                                </div>
                                <div class="col-md-8">
                                    : <b><span class="enrollment_schoolyear"></span></b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    Enrollment Status 
                                </div>
                                <div class="col-md-8">
                                    : <b><span id="enrollment_status"></span></b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    Grade Level
                                </div>
                                <div class="col-md-8">
                                    : <b><span id="enrollment_gradelevel"></span></b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    Section
                                </div>
                                <div class="col-md-8">
                                    : <b><span id="enrollment_section"></span></b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row without_enrollment" hidden>
        <div class="col-md-12">
            <div class="callout callout-danger">
                <h5 id="without_enrollment_text">No enrollment record was found for School Year this school year!</h5>
            </div>
        </div>
    </div>

    <div class="row no_enrollment_period" hidden>
        <div class="col-md-12">
            <div class="callout callout-danger">
                <h5>No available enrollment period.</h5>
            </div>
        </div>
    </div>
    
    <div class="row is_enrolled_to_previous" hidden>
        <div class="col-md-12">
            <div class="callout callout-danger">
                <h5>You are currently enrolled to previous school year. Please contact the school registrar.</h5>
            </div>
        </div>
    </div>

    
    <div class="row" id="preregistration_flow" hidden>
        <div class="col-md-9" id="enrollment_setup_info">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">Available Enrollment</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table ">
                                        <thead>
                                            <tr>
                                                <th with="30%">Enrollment</th>
                                                <th with="30%">Academic Program</th>
                                                <th with="20%">Start Date</th>
                                                <th with="20%">End Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="available_enrollment">
    
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-red">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <h6 class="text-danger"><i>Please contact the school registrar for more information.</i></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9" hidden id="enrollment_submission">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <h6>Upload a soft copy of the following documents for verification.</h6>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <h6 class="text-danger"><i>Note: Please be reminded that you should submit a hard copy to the registrars office.</i></h6>
                            </div>
                        </div>
                        <form 
                        action="/student/preenrollment/submit" 
                        id="submit_enrollmentsetup" 
                        method="POST" 
                        enctype="multipart/form-data">
                        @csrf
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <table class="table table-bordered" >
                                            <tbody id="preregrequirements_holder">
    
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                          
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary" id="prereg_submit">SUBMIT</button>
                                </div>
                            </div>
                        </form>
                        <br>
                        <table class="table" >
                                <tbody id="submitted_requirements_holder">
                                    <tr>    
                                        <td width="20%"></td>
                                        <td width="60%"></td>
                                        <td width="20%"></td>
                                    </tr>
                                </tbody>
                        </table>
                                            
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9" hidden id="payment_upload">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row" hidden>
                            <div class="col-md-12">
                                <h5>To complete the enrollment process, you may pay the minimum upon enrollment fee :</h5>
                            </div>
                            <div class="col-md-12">
                                <h5 class="p-0 mb-2">Upon enrollment: <span style="font-size:25px !important" class="text-success">&#8369; </span><span id="dp_amount" style="font-size:25px !important" class="text-success">00.00</span><h5>
                            </div>
                            <div class="col-md-12">
                                <h5 class="p-0 mb-2">Balance forwarded from previous school year: <span style="font-size:25px !important" class="text-success">&#8369; </span><span id="bal_forward" style="font-size:25px !important" class="text-success">00.00</span><h5> 
                            </div>
                            <div class="col-md-12">
                                <h5 class="p-0 mb-2 text-danger"><i>Note: you may pay higher than the required amount.</i><h5>
                            </div>
                            
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <form action="/payment/online/submitreceipt" id="paymentInfo" method="POST" enctype="multipart/form-data">
                                    @csrf
                                        <div class=" row " >
                                            <div class="col-md-8">
                                                   
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                                <label for="">PAYMENT TYPE</label>
                                                                <select name="paymentType" id="paymentType" class="form-control ">
                                                                    <option value="">SELECT PAYMENT TYPE</option>
                                                                    @foreach(DB::table('paymenttype')->where('isonline','1')->where('deleted','0')->get() as $item)
                                                                            <option value="{{$item->id}}">{{$item->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong></strong>
                                                                </span>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                                <label for="">RECEIPT IMAGE</label>
                                                                <input type="file" class="form-control" name="recieptImage" id="recieptImage" accept=".png, .jpg, .jpeg">
                                                                <span class="invalid-feedback" role="alert" style="display:hidden">
                                                                    <strong>required</strong>
                                                                </span>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                                <label for="">REFERENCE NUMBER </label>
                                                                <input class="form-control" name="refNum" id="refNum" placeholder="REFERENCE NUMBER">
                                                                <span class="invalid-feedback" role="alert" style="display:hidden">
                                                                    <strong>required</strong>
                                                                </span>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                                <label for="">BANK NAME</label>
                                                                <select id="bankName" name="bankName" class="form-control" disabled>
                                                                    <option value="">SELECT BANK</option>
                                                                    @foreach (DB::table('onlinepaymentoptions')->where('paymenttype','3')->where('deleted','0')->where('isActive','1')->get() as $item)
                                                                            <option value="{{$item->optionDescription}}">{{$item->optionDescription}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="invalid-feedback" role="alert" style="display:hidden">
                                                                    <strong>required</strong>
                                                                </span>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                                <label for="">BANK TRANS. DATE</label>
                                                                <input type="date"  class="form-control" name="transDate" id="transDate" >
                                                                <span class="invalid-feedback" role="alert" style="display:hidden">
                                                                    <strong>required</strong>
                                                                </span>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                                <label for="">PAYMENT AMOUNT</label>
                                                                <input class="form-control" type="text" name="amount" id="amount"  value="" data-type="currency" placeholder="00.00">

                                                                <span class="invalid-feedback" role="alert" style="display:hidden">
                                                                    <strong id="amountError">required</strong>
                                                                </span>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <h5 for="">Message Reciever:</h5>
                                                                <select name="" id="input_receiver" class="form-control">
                                                                    <option value="1">Student</option>
                                                                    <option value="2">Mother</option>
                                                                    <option value="3">Father</option>
                                                                    <option value="4">Guardian</option>
                                                                </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <h5 for="">Contact #:</h5>
                                                            <input placeholder="09XX-XXXX-XXXX" id="input_number" name="input_number" class="form-control">
                                                            <span class="invalid-feedback" role="alert" style="display:hidden">
                                                                <strong id="opcontact">Contact is required/invalid</strong>
                                                            </span>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <button class="btn btn-success" id="proceedpayment" >
                                                                SUBMIT PAYMENT RECEIPT
                                                            </button>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="scheduler-border">
                                                        <legend class="scheduler-border">Receipt Image</legend>
                                                        <img class="mt-3 w-100" id="receipt"  />
                                                </fieldset>
                                            </div>
                                        </div>
                                    </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped" >
                                    <tbody id="uploaded_onlinepayments_holder">
                                        <thead>      
                                            <tr>
                                                  <th width="30%" class="text-center">PAYMENT RECEIPT</th>
                                                  <th width="20%">AMOUNT</th>
                                                  <th width="20%">STATUS</th>
                                                  <th width="30%">DATE UPLOADED</th>
                                            </tr>
                                        </thead>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9" hidden id="info_update_holder">
            <div class="card shadow">
                <div class="card-header bg-primary">
                    <h3 class="card-title">
                        <i class="fas fa-info"></i>
                        Update Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <i><h4>Please update information for school year <span class="enrollment_schoolyear text-primary"></span></h4></i>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 bg-info p-1">
                            <h4 class="mb-0">Enrollment Information</h4>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label><b>Grade level to Enroll</b></label>
                            <input disabled onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="First name"  name="grade_level" required>
                            <span class="invalid-feedback" role="alert">
                                  <strong>First Name is required</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label><b>LRN</b></label>
                            <input disabled onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="LRN"  name="lrn" required>
                            <span class="invalid-feedback" role="alert">
                                  <strong>LRN</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-4" hidden  id="strand_holder">
                            <label><b>Strand</b></label>
                            <select name="input_strand" id="input_strand" class="form-control select2" required>
                                <option value="">Select Strand</option>
                                @foreach (DB::table('sh_strand')->where('deleted',0)->get() as $item)
                                    <option value="{{$item->id}}">{{$item->strandcode}} - {{$item->strandname}}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback" role="alert">
                                  <strong>Strand is required</strong>
                            </span>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @if($schoolinfo->withMOL == 1 )
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
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12" hidden  id="course_holder">
                            <label><b>Course</b></label>
                            <select name="input_course" id="input_course" class="form-control select2" required disabled>
                                <option value="">Select Course</option>
                                @foreach (DB::table('college_courses')->where('deleted',0)->get() as $item)
                                    <option value="{{$item->id}}">{{$item->courseDesc}}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback" role="alert">
                                  <strong>Course is required</strong>
                            </span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 bg-info p-1">
                            <h4 class="mb-0">Personal Information</h4>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label><b>First Name</b></label>
                            <input disabled onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="First name"  name="first_name" required>
                            <span class="invalid-feedback" role="alert">
                                  <strong>First Name is required</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-3">
                                <label><b>Middle Name</b></label>
                                <input disabled  onkeyup="this.value = this.value.toUpperCase();" class="form-control" placeholder="Middle name"  name="middle_name" required>
                                <span class="invalid-feedback" role="alert">
                                    <strong>Middle Name is required</strong>
                                </span>
                        </div>
                        <div class="form-group col-md-4">
                                <label><b>Last Name</b></label>
                                <input disabled onkeyup="this.value = this.value.toUpperCase();" class="form-control" placeholder="Last name" id="last_name" name="last_name"  required>
                                <span class="invalid-feedback" role="alert">
                                    <strong>Last Name is required</strong>
                                </span>
                        </div>
                        <div class="form-group col-md-1">
                                <label><b>SUFFIX</b></label>
                                <input disabled onkeyup="this.value = this.value.toUpperCase();" class="form-control p-2" placeholder="SU" id="suffix" name="suffix" >
                                <span class="invalid-feedback" role="alert">
                                    <strong>Suffix is required</strong>
                                </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label><b>Date of birth *</b></label>
                            <input disabled type="date" class="form-control" placeholder="First name..."  name="dob" required>
                            <span class="invalid-feedback" role="alert">
                                  <strong>Date of birth is required</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-4">
                                <label><b>Gender *</b></label>
                                <select name="gender" id="gender"  class="form-control" required disabled>
                                    <option value="" selected>GENDER</option>
                                    <option value="FEMALE">FEMALE</option>
                                    <option value="MALE">MALE</option>
                                </select>
                                <span class="invalid-feedback" role="alert">
                                    <strong>Gender is required</strong>
                                </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label><b>Nationality *</b></label>
                            <select name="nationality" id="nationality" class="form-control select2"  required disabled>
                                <option value=""></option>
                                @foreach(DB::table('nationality')->where('deleted',0)->get() as $item)
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
                    <div class="row">
                        <div class="form-group col-md-6">
                              <label><b>Mobile Number *</b></label>
                              <input class="form-control" placeholder="09XX-XXXX-XXXX "  name="contact_number" id="contact_number" minlength="13" maxlength="13" autocomplete="off" required>
                              <span class="invalid-feedback" role="alert" >
                                    <strong id="mobileError">Mobile number is required</strong>
                              </span>
                        </div>
                        <div class="form-group col-md-6">
                              <label class="form-control-label"><b>Email Address</b></label>
                              <input type="email" class="form-control " placeholder="Email address" id="email"  name="email" autocomplete="off">
                              <span class="invalid-feedback" role="alert">
                                    <strong>Email address is required</strong>
                              </span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 bg-info p-1">
                            <h4 class="mb-0">Address</h4>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group col-md-6">
                              <label><b>Street</b></label>
                              <input class="form-control" name="street" id="street" autocomplete="off">
                              <span class="invalid-feedback" role="alert" >
                                    <strong id="streetError">Street is required</strong>
                              </span>
                        </div>
                        <div class="form-group col-md-6">
                            <label><b>Barangay</b></label>
                            <input class="form-control" name="barangay" id="barangay" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                            <span class="invalid-feedback" role="alert" >
                                  <strong id="barangayError">Barangay is required</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-6">
                            <label><b>City</b></label>
                            <input class="form-control" name="city" id="city" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                            <span class="invalid-feedback" role="alert" >
                                  <strong id="cityError">City is required</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-6">
                            <label><b>Province</b></label>
                            <input class="form-control" name="province" id="province" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                            <span class="invalid-feedback" role="alert" >
                                    <strong id="provinceError">Province is required</strong>
                            </span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 bg-info p-1">
                            <h4 class="mb-0">Parent / Guardian Information</h4>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group col-md-4">
                              <label><b>Father's Full Name</b><span></span></label>
                              <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="Father's Full Name"  name="father_name" id="father_name">
                              <div class="text-center">
                                   ( surname, full name, middle name )
                              </div>
                              <span class="invalid-feedback" role="alert">
                                    <strong>Father's Full Name is required</strong>
                              </span>
                        </div>
                        <div class="form-group col-md-4">
                              <label><b>Father's Occupation</b></label>
                              <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="Father's Occupation"  name="father_occupation"  id="father_occupation">
                              
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
                              <label><b>Mother's Full Maiden Name </b></label>
                              <input class="form-control " onkeyup="this.value = this.value.toUpperCase();" placeholder="Mother's Full Maiden Name"  name="mother_name" id="mother_name" >
                              <div class="text-center">
                                    ( surname, full name, middle name )
                              </div>
                              <span class="invalid-feedback" role="alert">
                                    <strong>Mother's Full Maiden Name is required</strong>
                              </span>
                        </div>
                        <div class="form-group col-md-4">
                              <label><b>Mother's Occupation</b></label>
                              <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="Mother's occupation"  name="mother_occupation"  id="mother_occupation">
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
                                <label><b>Guardian's Full Name </b></label>
                                <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="(surname, full name, middle name)"  name="guardian_name" id="guardian_name">
                                <div class="text-center">
                                    ( surname, full name, middle name )
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <strong>Guardian's Full is required</strong>
                                </span>
                        </div>
                        <div class="form-group col-md-4">
                                <label><b>Relationship to Student</b></label>
                                <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="Guardian's Relationship"  name="guardian_relation" id="guardian_relation">
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
                   <br>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button class="btn btn-primary" id="update_info">Update Information</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body p-2">
                    <div class="timeline">
                        <div class="time-label">
                          <span class="bg-danger">Step 1</span>
                          <span class="bg-success step1_com" hidden>Completed</span>
                        </div>
                        <div class="step1_com" hidden>
                            <i class="fas fa-users bg-blue"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header no-border"><a href="#" id="step1">Personal Information</a> was updated</h3>
                                <div class="timeline-body">
                                    <i class="fas fa-clock"></i> <span class="step1_datecom"></span>
                                </div>
                            </div>
                        </div>
                        <div class="step1_ncom">
                            <i class="fas fa-users bg-blue"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header no-border"><a href="#" id="step1">Update Personal Information</a></h3>
                            </div>
                        </div>

                        <div class="time-label">
                            <span class="bg-primary">Step 2</span>
                            <span class="bg-success step2_com" hidden>Completed</span>
                        </div>
                        <div class="step2_com" hidden>
                            <i class="fas fa-envelope bg-blue"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header"><a href="#" id="step2">Enrollment</a>  was submitted</h3>
                                <div class="timeline-body">
                                <i class="fas fa-clock"></i> <span class="step2_datecom"></span>
                                </div>
                            </div>
                        </div>
                        <div class="step2_ncom">
                            <i class="fas fa-envelope bg-blue"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header"><a href="#" id="step2">Submit Enrollment</a></h3>
                            </div>
                        </div>
                        <div class="time-label">
                            <span class="bg-primary">Step 3</span>
                        </div>
                        <div>
                            <i class="fas fa-money-bill-wave-alt bg-info"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header"><a href="#" id="step3">Upload Payment</a></h3>
                                <div class="timeline-body">
                                <span id="payment_count"> 0 </span> payment was uploaded
                                </div>
                            </div>
                        </div>
                        <div class="time-label">
                            <span class="bg-info">Step 4</span>
                        </div>
                        <div>
                            <i class="fas fa-money-bill-wave-alt bg-info"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">Process Enrollment</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
@endsection

@section('footerscript')
    <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function(){

            $(document).on('click','#view_fees_modal',function(){
                $('#fees_modal').modal();
                $.ajax({
                    type:'GET',
                    url: '/utilities/u_loadlevel',
                    success:function(data) {
                        $('#fees_holder').empty()
                        $('#fees_holder').append(data)
                    }
                })
            })

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })

            $('.select2').select2()
            get_student_type()
            var all_enrollment = []
            var all_mol = []
            var studtype = ""

            mode_of_learning()
            function mode_of_learning(){
                $.ajax({
                    type:'GET',
                    url: '/setup/modeoflearning/list',
                    data:{
                        syid:$('#filter_sy').val(),
                        status:1
                    },
                    success:function(data) {
                        all_mol = data
                    }
                })
            }

            function get_student_type(){
                $.ajax({
                    type:'GET',
                    url: '/student/preenrollment/checktype',
                    success:function(data) {
                        studtype = data
                        get_studentinfo()
					}
                })
            }

            $(document).on('click','#step1',function(){

                var if_available = enrollment_setup.filter(x=>x.acadprogid == studinfo[0].acadprogid)


                if(if_available.length > 0){

                    var temp_levelid = studinfo[0].levelid
               
                    var checkbox = ''
                    $.each(all_mol,function(a,b){
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
                                                '<input  type="radio" name="withMOL" id="withMOL'+b.id+'" value="'+b.id+'" disabled="disabled">'+
                                                '<label class="mr-5" for="withMOL'+b.id+'">'+b.description+'</label>'+
                                        '</div>'
                        }
                    })

                    if(schoolinfo.withMOL == 1){
                        $('#mol_holder').empty()
                        $('#mol_holder')[0].innerHTML = checkbox
                    }
                 


                    $('input[name="withMOL"][value='+ studinfo[0].mol+']').prop('checked',true)
                    
                    if(update_info.length == 0){
                        $('input[name="withMOL"]').removeAttr('disabled')
                    }
                    
                    $('#info_update_holder').removeAttr('hidden')
                    $('#enrollment_setup_info').attr('hidden','hidden')
                    $('#enrollment_submission').attr('hidden','hidden')
                    $('#payment_upload').attr('hidden','hidden')
                }else{
                    Toast.fire({
                        type: 'info',
                        title: 'Enrollment is not available!'
                    })
                }
            })

            var studinfo = @json($student);

            $(document).on('change','#input_receiver',function(){
                if($(this).val() == 1){
                    $('#input_number').val(studinfo.contactno)
                }else if($(this).val() == 3){
                    $('#input_number').val(studinfo.fcontactno)
                }else if($(this).val() == 2){
                    $('#input_number').val(studinfo.mcontactno)
                }else if($(this).val() == 4){
                    $('#input_number').val(studinfo.gcontactno)
                }
            })
            
            $(document).on('click','#step2',function(){
                var if_available = enrollment_setup.filter(x=>x.acadprogid == studinfo[0].acadprogid)
            
                var valid_step2 = true

                if(if_available.length == 0){
                    valid_step2 = false
                    Toast.fire({
                        type: 'info',
                        title: 'Enrollment is not avaible!'
                    })
                }

                if(update_info.length == 0){
                    valid_step2 = false
                    Toast.fire({
                        type: 'info',
                        title: 'Please update information!'
                    })
                }

                if(valid_step2){
                    $('#enrollment_submission').removeAttr('hidden')
                    $('#enrollment_setup_info').attr('hidden','hidden')
                    $('#info_update_holder').attr('hidden','hidden')
                    $('#payment_upload').attr('hidden','hidden')
                }
            })

            
            $(document).on('click','#step3',function(){

                var if_available = enrollment_setup.filter(x=>x.acadprogid == studinfo[0].acadprogid)

                var valid_step3 = true

                if(if_available.length == 0){
                    valid_step3 = false
                    Toast.fire({
                        type: 'info',
                        title: 'Enrollment is not avaible!'
                    })
                }

                if(update_info.length == 0){
                    valid_step3 = false
                    Toast.fire({
                        type: 'info',
                        title: 'Please update information!'
                    })
                }
                // if(studinfo[0].preEnrolled == 0){
                //     valid_step3 = false
                //     Toast.fire({
                //         type: 'info',
                //         title: 'Please submit enrollment!'
                //     })
                // }

                if(dp_setup.length == 0){
                    valid_step3 = false
                    Toast.fire({
                        type: 'info',
                        title: 'Payment is not yet avaible!'
                    })
                }

                if(valid_step3){
                    $('#payment_upload').removeAttr('hidden')
                    $('#enrollment_setup_info').attr('hidden','hidden')
                    $('#enrollment_submission').attr('hidden','hidden')
                    $('#info_update_holder').attr('hidden','hidden')
                }
            })

            function clear_display(){
                $('.prereg_status').attr('hidden','hidden')
                $('.with_approved_prereg').attr('hidden','hidden')
                $('.with_prereg_setup').attr('hidden','hidden')
                $('.prereg_submission').attr('hidden','hidden')
                $('.submitted_requirements').attr('hidden','hidden')
                $('.with_submited_prereg').attr('hidden','hidden')
                $('.with_disapproved_prereg').attr('hidden','hidden')
                $('#info_update_holder').attr('hidden','hidden')
                $('.with_enrollment').attr('hidden','hidden')
                $('.is_enrolled_to_previous').attr('hidden','hidden');
                $('.prereg_info').attr('hidden','hidden');
                $('.without_prereg').attr('hidden','hidden')
                $('.with_submited_prereg').attr('hidden','hidden');
            }

            function load_filter_data(){
                $('#uploaded_onlinepayments_holder').empty()
                $('#preregrequirements_holder').empty()
                $('#payment_upload').attr('hidden','hidden')
                $('.is_enrolled_to_previous').attr('hidden','hidden')
                $('#enrollment_submission').attr('hidden','hidden')
                $('#info_update_holder').attr('hidden','hidden')
                $('#preregistration_flow').attr('hidden','hidden')
                $('.with_enrollment').attr('hidden','hidden')
                $('#enrollment_setup_info').removeAttr('hidden')
                $('#submit_enrollmentsetup').attr('hidden','hidden')
                $('.without_enrollment').attr('hidden','hidden')
                get_studentinfo()
            }
            
            var schoolinfo = @json($schoolinfo);

            $(document).on('click','#update_info',function(){

                var fremovedDash =  $('#father_contact_number').val().replace(/-/g, '')
                var fremoveUnderscore =  fremovedDash.replace(/_/g, '')
                var mremovedDash =  $('#mother_contact_number').val().replace(/-/g, '')
                var mremoveUnderscore =  mremovedDash.replace(/_/g, '')
                var gremovedDash =  $('#guardian_contact_number').val().replace(/-/g, '')
                var gremoveUnderscore =  gremovedDash.replace(/_/g, '')

                var isvalid = true;

                if(studinfo[0].levelid == 14){
                    if($('#input_strand').val() == ""){
                        isvalid = false
                        $('#input_strand').addClass('is-invalid')
                        Toast.fire({
                            type: 'info',
                            title: 'Strand is required'
                        })
                    }
                }

                if(studinfo[0].levelid == 17){
                    if($('#input_course').val() == ""){
                        isvalid = false
                        $('#input_course').addClass('is-invalid')
                        Toast.fire({
                            type: 'info',
                            title: 'Course is required'
                        })
                    }
                }   

                if(schoolinfo.withMOL == 1 ){
                    if($('input[name="withMOL"]:checked').val() == 0){
                        $('#molErrorInput').addClass('is-invalid')
                        Toast.fire({
                            type: 'info',
                            title: 'Mode of Learning is required'
                        })
                        isvalid = false
                    }else{
                        $('#molErrorInput').removeClass('is-invalid')
                    }
                }
                        
                       
             


                if($('#father').prop('checked') == true){
                    if( ( fremoveUnderscore.length >= 1 && fremoveUnderscore.length < 11 ) || fremoveUnderscore == ''){
                            $('#father_contact_number').addClass('is-invalid')
                            $('#fmobileError').text('Invalid Mobile Number')
                            Toast.fire({
                                type: 'info',
                                title: 'Fathers Contact Number is require'
                            })
                            isvalid = false
                    }else{
                            $('#father_name').removeClass('is-invalid')
                            $('#fmobileError').text('Fathers Contact Number is required')
                    }
                }
                else{
                        $('#mother_name').removeClass('is-invalid')
                        $('#father_contact_number').removeClass('is-invalid')
                        $('#fmobileError').text('Fathers Contact Number is required')
                }

              
                if($('#mother').prop('checked') == true){
                    if( ( mremoveUnderscore.length >= 1 && mremoveUnderscore.length < 11 ) || mremoveUnderscore == ''){
                            $('#mother_contact_number').addClass('is-invalid')
                            $('#mmobileError').text('Invalid Mobile Number')
                            Toast.fire({
                                type: 'info',
                                title: 'Mother\'s Contact Number is required'
                            })
                            isvalid = false
                    }else{
                            $('#mother_contact_number').removeClass('is-invalid')
                            $('#mmobileError').text('Mother\'s Contact Number is required')
                    }
                }
                else{
                        $('#mother_name').removeClass('is-invalid')
                        $('#mother_contact_number').removeClass('is-invalid')
                        $('#mmobileError').text('Mother\'s Contact Number is required')
                }

                if($('#guardian').prop('checked') == true){
                    if( ( gremoveUnderscore.length >= 1 && gremoveUnderscore.length < 11  ) || gremoveUnderscore == ''){
                            $('#guardian_contact_number').addClass('is-invalid')
                            $('#gmobileError').text('Invalid Mobile Number')
                            Toast.fire({
                                type: 'info',
                                title: 'Guardian\'s Contact Number'
                            })
                            isvalid = false
                    }else if($('#guardian_name').val() == ""){
                            $('#guardian_name').addClass('is-invalid')
                            Toast.fire({
                                type: 'info',
                                title: 'Guardian\'s name is required'
                            })
                    }else{
                            $('#guardian_contact_number').removeClass('is-invalid')
                            $('#guardian_name').removeClass('is-invalid')
                            $('#gmobileError').text('Guardian\'s Contact Number is required')
                    }
                }
                else{
                        $('#guardian_contact_number').removeClass('is-invalid')
                        $('#gmobileError').text('Guardian\'s Contact Number is required')
                }

                
                if($('#barangay').val() == ""){
                    isvalid = false
                    $('#barangay').addClass('is-invalid')
                    Toast.fire({
                        type: 'info',
                        title: 'Barangay is required'
                    })
                }else{
                    $('#barangay').removeClass('is-invalid')
                }

                if($('#city').val() == ""){
                    isvalid = false
                    $('#city').addClass('is-invalid')
                    Toast.fire({
                        type: 'info',
                        title: 'City is required'
                    })
                }
                else{
                    $('#city').removeClass('is-invalid')
                }

                if($('#province').val() == ""){
                    isvalid = false
                    $('#province').addClass('is-invalid')
                    Toast.fire({
                        type: 'info',
                        title: 'Province is required'
                    })
                }else{
                    $('#province').removeClass('is-invalid')
                }

                if($('#contact_number').val() == ""){
                    isvalid = false
                    $('#contact_number').addClass('is-invalid')
                    Toast.fire({
                        type: 'info',
                        title: 'Contact Number is required'
                    })
                }else{
                    $('#contact_number').removeClass('is-invalid')
                }

                if(isvalid){
                    $('#update_info').attr('disabled','disabled')
                    $('#update_info').text('Processing...')
                    submit_info()
                }


            })
            
            function submit_info(){

                var ismothernum = 0
                var isfathernum = 0
                var isguardiannum = 0
                var strand = $('#input_strand').val()
                var course = $('#input_course').val()

                if($('#guardian').prop('checked') == true){
                    isguardiannum = 1
                }
                if($('#mother').prop('checked') == true){
                    ismothernum = 1
                }
                if($('#father').prop('checked') == true){
                    isfathernum = 1
                }

                if(studinfo[0].levelid == 15){
                    strand = studinfo[0].strandid
                }

                if(studinfo[0].levelid == 18 || studinfo[0].levelid == 19 || studinfo[0].levelid == 20){
                    course = studinfo[0].courseid
                }

                $.ajax({
                    type:'GET',
                    url: '/student/preenrollment/submitinfo',
                    data:{
                        syid:enrollment_setup[0].syid,
                        semid:enrollment_setup[0].semid,
                        contactno: $('#contact_number').val(),
                        email:  $('#email').val(),
                        foccupation:  $('#father_occupation').val(),
                        fcontactno:  $('#father_contact_number').val(),
                        moccupation:  $('#mother_occupation').val(),
                        mcontactno:  $('#mother_contact_number').val(),
                        guardianname:  $('#guardian_name').val(),
                        grelation:  $('#guardian_relation').val(),
                        gcontactno:  $('#guardian_contact_number').val(),
                        gradelevel: studinfo[0].levelid,
                        ismother: ismothernum,
                        isfather: isfathernum,
                        isguardian: isguardiannum,
                        street: $('#street').val(),
                        barangay: $('#barangay').val(),
                        city: $('#city').val(),
                        province: $('#province').val(),
                        strand: strand,
                        course: course,
                        fname: $('#father_name').val(),
                        mname: $('#mother_name').val(),
                        withMOL:$('input[name="withMOL"]:checked').val()
                    },
                    success:function(data){
                        if(data == 1){
                            load_filter_data()
                            Toast.fire({
                                type: 'success',
                                title: 'Updated Successfully!'
                            })
                        }else{
                            $('#update_info').removeAttr('disabled')
                            $('#update_info').text('Update Information')
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                        }
                    }
                })
            }

            // 1e
            var update_info = []
            function get_updateinfo(){
                $.ajax({
                    type:'GET',
                    url: '/student/preenrollment/infoupdate',
                    data:{
                        syid: enrollment_setup[0].syid,
                        semid: enrollment_setup[0].semid,
                        gradelevel: studinfo[0].levelid
                    },
                    success:function(data){
                        $('.prereg_until').text("")
                        update_info = data
                        if(data.length > 0){
                            $('.step1_com').removeAttr('hidden')
                            $('.step1_ncom').attr('hidden','hidden')
                            $('.step1_datecom').text(data[0].createddatetime)
                            $('select[name="input_strand"]').attr('readonly','readonly')
                            $('select[name="input_strand"]').attr('disabled','disabled')
							$('select[name="input_course"]').attr('readonly','readonly')
                            $('select[name="input_course"]').attr('disabled','disabled')
                            $('input[name="grade_level"]').attr('readonly','readonly')
                            $('input[name="father_name"]').attr('disabled','disabled')
                            $('input[name="mother_name"]').attr('readonly','readonly')
                            $('input[name="lrn"]').attr('readonly','readonly')
                            $('input[name="first_name"]').attr('readonly','readonly')
                            $('input[name="middle_name"]').attr('readonly')
                            $('input[name="last_name"]').attr('readonly','readonly')
                            $('input[name="dob"]').attr('readonly','readonly')
                            $('input[name="street"]').attr('readonly','readonly')
                            $('input[name="withMOL"]').attr('disabled','disabled')
                            $('input[name="barangay"]').attr('readonly','readonly')
                            $('input[name="city"]').attr('readonly','readonly')
                            $('input[name="province"]').attr('readonly','readonly')
                            $('input[name="suffix"]').attr('readonly','readonly')
                            $('#gender').attr('readonly','readonly')
                            $('input[name="email"]').attr('readonly','readonly')
                            $('input[name="contact_number"]').attr('readonly','readonly')
                            $('input[name="father_occupation"]').attr('readonly','readonly')
                            $('input[name="father_contact_number"]').attr('readonly','readonly')
                            $('input[name="mother_occupation"]').attr('readonly','readonly')
                            $('input[name="mother_contact_number"]').attr('readonly','readonly')
                            $('input[name="guardian_name"]').attr('readonly','readonly')
                            $('input[name="guardian_relation"]').attr('readonly','readonly')
                            $('input[name="guardian_contact_number"]').attr('readonly','readonly')
                            $('input[name="incase"]').attr('disabled','disabled')
                            $('#update_info').attr('hidden','hidden')
                        }else{
                            $('input[name="withMOL"]').removeAttr('disabled')
                            $('.step1_com').attr('hidden','hidden')
                            $('.step1_ncom').removeAttr('hidden')
                            $('select[name="input_strand"]').removeAttr('readonly')
                            $('select[name="input_strand"]').removeAttr('hidden')
							$('select[name="input_course"]').removeAttr('readonly')
                            $('select[name="input_course"]').removeAttr('hidden')
                            $('input[name="grade_level"]').removeAttr('readonly')
                            $('input[name="father_name"]').removeAttr('readonly')
                            $('input[name="mother_name"]').removeAttr('readonly')
                            $('input[name="lrn"]').removeAttr('readonly')
                            $('input[name="first_name"]').removeAttr('readonly')
                            $('input[name="middle_name"]').removeAttr('readonly')
                            $('input[name="last_name"]').removeAttr('readonly')
                            $('input[name="dob"]').removeAttr('readonly')
                            $('input[name="street"]').removeAttr('readonly')
                            $('input[name="withMOL"]').removeAttr('readonly')
                            $('input[name="barangay"]').removeAttr('readonly')
                            $('input[name="city"]').removeAttr('readonly')
                            $('input[name="province"]').removeAttr('readonly')
                            $('input[name="suffix"]').removeAttr('readonly')
                            $('#gender').removeAttr('readonly')
                            $('input[name="email"]').removeAttr('readonly')
                            $('input[name="contact_number"]').removeAttr('readonly')
                            $('input[name="father_occupation"]').removeAttr('readonly')
                            $('input[name="father_contact_number"]').removeAttr('readonly')
                            $('input[name="mother_occupation"]').removeAttr('readonly')
                            $('input[name="mother_contact_number"]').removeAttr('readonly')
                            $('input[name="guardian_name"]').removeAttr('readonly')
                            $('input[name="guardian_relation"]').removeAttr('readonly')
                            $('input[name="guardian_contact_number"]').removeAttr('readonly')
                            $('input[name="incase"]').removeAttr('disabled')
                            $('#update_info').removeAttr('hidden')
                        }
                    }
                })
            }

            var studinfo = []
           
            // 1d
            function get_studentinfo(){

                $.ajax({
                    type:'GET',
                    url: '/student/preenrollment/personalinfo',
                    data:{
                        syid:$('#filter_sy').val()
                    },
                    success:function(data){

                        studinfo = data

                        if(studinfo[0].levelid == 14 || studinfo[0].levelid == 15){
                            $('#strand_holder').removeAttr('hidden')
                            if(studinfo[0].courseid == null || studinfo[0].courseid == 0 ){
                                $('#input_strand').removeAttr('disabled')
                            }
                        }else{
                            $('#strand_holder').attr('hidden','hidden')
                        }
                        
                        if(studinfo[0].acadprogid == 6){
                            $('#course_holder').removeAttr('hidden')
                            if(studinfo[0].courseid == null || studinfo[0].courseid == 0 ){
                                $('#input_course').removeAttr('disabled')
                            }
                        }else{
                            $('#course_holder').attr('hidden','hidden')
                        }

                       

                        if(studinfo[0].levelid == 15){
                            $('#input_strand').val(studinfo[0].strandid).change()
                            $('#input_strand').attr('readonly','readonly')
                            $('#input_strand').attr('disabled','disabled')
                        }else if( studinfo[0].levelid == 14){
                            $('#input_strand').val(studinfo[0].strandid).change()
                        }

                        if(studinfo[0].levelid == 18 || studinfo[0].levelid == 19 || studinfo[0].levelid == 20){
                            $('#input_course').val(studinfo[0].courseid).change()
                            $('#input_course').attr('readonly','readonly')
                            $('#input_course').attr('disabled','disabled')
                        }else if( studinfo[0].levelid == 17){
                            $('#input_course').val(studinfo[0].courseid).change()
                        }

                        data[0].fcontactno = data[0].fcontactno != null ? data[0].fcontactno.replace("-", "") : '';
                        data[0].mcontactno = data[0].mcontactno != null ? data[0].mcontactno.replace("-", "")  : '';
                        data[0].gcontactno = data[0].gcontactno != null ? data[0].gcontactno.replace("-", "")  : '';
                        data[0].contactno = data[0].contactno != null ? data[0].contactno.replace("-", "")  : '';
                        
                        data[0].fcontactno = data[0].fcontactno.length > 11 ? data[0].fcontactno.substring( 0, 12) : data[0].fcontactno;
                        data[0].mcontactno = data[0].mcontactno.length  > 11 ? data[0].mcontactno.substring( 0, 12)  : data[0].mcontactno;
                        data[0].gcontactno = data[0].gcontactno.length  > 11 ? data[0].gcontactno.substring( 0, 12)  : data[0].gcontactno;
                        data[0].contactno = data[0].contactno.length  > 11 ? data[0].contactno.substring( 0, 12)  : data[0].contactno;

                        $('input[name="grade_level"]').val(data[0].levelname)
                        $('input[name="lrn"]').val(data[0].lrn)
                        $('input[name="first_name"]').val(data[0].firstname)
                        $('input[name="middle_name"]').val(data[0].middlename)
                        $('input[name="last_name"]').val(data[0].lastname)
                        $('input[name="dob"]').val(data[0].dob)
                        $('input[name="street"]').val(data[0].street)
                        $('input[name="barangay"]').val(data[0].barangay)
                        $('input[name="city"]').val(data[0].city)
                        $('input[name="province"]').val(data[0].province)
                        $('input[name="suffix"]').val(data[0].suffix)
                        $('#gender').val(data[0].gender)
                        $('input[name="email"]').val(data[0].semail)
                        $('input[name="contact_number"]').val(data[0].contactno)
                        $('input[name="father_name"]').val(data[0].fathername)
                        $('input[name="father_occupation"]').val(data[0].foccupation)
                        $('input[name="father_contact_number"]').val(data[0].fcontactno)
                        $('input[name="mother_name"]').val(data[0].mothername)
                        $('input[name="mother_occupation"]').val(data[0].moccupation)
                        $('input[name="mother_contact_number"]').val(data[0].mcontactno)
                        $('input[name="guardian_name"]').val(data[0].guardianname)
                        $('input[name="guardian_relation"]').val(data[0].guardianrelation)
                        $('input[name="guardian_contact_number"]').val(data[0].gcontactno)
                        if(data[0].nationality != null && data[0].nationality != 0){
                            $('#nationality').val(data[0].nationality).change()
                        }
                        if(data[0].ismothernum == 1){
                            $("#mother").prop("checked", true)
                            $('#mother_contact_number').attr('required')
                        }
                        else if(data[0].isfathernum == 1){
                            $("#father").prop("checked", true)
                            $('#father_contact_number').attr('required')
                        }
                        else{
                            $("#guardian").prop("checked", true)
                            $('#guardian_contact_number').attr('required')
                        }

                        $("#contact_number").inputmask({mask: "9999-999-9999"});
                        $("#mother_contact_number").inputmask({mask: "9999-999-9999"});
                        $("#father_contact_number").inputmask({mask: "9999-999-9999"});
                        $("#guardian_contact_number").inputmask({mask: "9999-999-9999"});
                        
                        get_enrollmentrecord()
                       
                    }
                })


            }


            var activesy = @json($schoolyear).filter(x=>x.isactive == 1);
            var gradelevel = @json($gradelevel);
            var current_gradelevel = @json($current_gradelevel);
            var current_gradelevel_detail = gradelevel.filter(x=>x.id == current_gradelevel );
            var gradelevel_to_enroll = gradelevel.filter(x=>x.sortid == parseInt(current_gradelevel_detail[0].sortid))
            var enrollmentrecord = []
            var all_preregrequirements = []
            var dp_setup = []
            var prereg_status = null
            var can_submit = false;

            
            
            //1a
            function get_enrollmentrecord(){
                $.ajax({
                    type:'GET',
                    url: '/student/enrollment/record',
                    data:{
                        syid:$('#filter_sy').val(),
                    },
                    success:function(data){
                        enrollmentrecord = data
                        // 1c
                        if(data.length > 0){
                            $('.no_enrollment_period').attr('hidden','hidden')
                            display_enrollmentrecord()
                        }
                        // 1d
                        else{
                            var selected_sy = $('#filter_sy').val()
                            var activesy_index = schoolyear.findIndex(x=>x.id == activesy[0].id)
                            var current_index = schoolyear.findIndex(x=>x.id == selected_sy)

                            if(activesy_index > current_index){
                                $('#without_enrollment_text').text('No enrollment record was found for S.Y. '+$( "#filter_sy option:selected" ).text()+'!')
                                $('.without_enrollment').removeAttr('hidden')
                                return false;
                            }else{
                                // if(studinfo[0].studstatus != 0){
                                //     $('.is_enrolled_to_previous').removeAttr('hidden')
                                //     return false;
                                // }else{
                                    get_enrollment_setup()
                                // }
                            }
                        }
                    }
                })
            }

            $(document).on('change','#filter_sy',function(){
                mode_of_learning()
                load_filter_data()
                $('.enrollment_schoolyear').text($('#filter_sy option:selected').text())
                $('#filter_sem_holder').attr('hidden','hidden')
            })

            $(document).on('change','#filter_sem',function(){
                display_enrollmentrecord()
            })

            function display_enrollmentrecord(){

                // get_enrollment_setup()
              
                var temp_enrollment = enrollmentrecord.filter(x=>x.syid == $('#filter_sy').val())

                if(temp_enrollment.length == 0){
                    $('#without_enrollment_text').text('No enrollment record was found for S.Y. '+$( "#filter_sy option:selected" ).text()+'!')
                    return false;
                }else{
                    $('#preregistration_flow').attr('hidden','hidden')
                }

                if(temp_enrollment[0].acadprogid == 6 || temp_enrollment[0].acadprogid == 5){
                    $('#filter_sem_holder').removeAttr('hidden')
                    var temp_enrollment = enrollmentrecord.filter(x=>x.syid == $('#filter_sy').val() && x.semid == $('#filter_sem').val())
                    if(temp_enrollment.length == 0){
                        $('#without_enrollment_text').text('No enrollment record was found for S.Y. '+$("#filter_sy option:selected" ).text()+' - '+$("#filter_sem option:selected" ).text()+'!')
                        $('.with_enrollment').attr('hidden','hidden')
                        $('.without_enrollment').removeAttr('hidden')
                        get_enrollment_setup()
                        return false;
                    }else{
                        $('.without_enrollment').attr('hidden','hidden')
                    }
                }else{
                    $('#filter_sem_holder').attr('hidden','hidden')
                }

                $('.submission_note').attr('hidden','hidden')
                $('.with_enrollment').removeAttr('hidden')
                $('#enrollment_status').text(temp_enrollment[0].description)
                $('.enrollment_schoolyear').text(temp_enrollment[0].sydesc)
                $('#enrollment_gradelevel').text(temp_enrollment[0].levelname)
                $('#enrollment_section').text(temp_enrollment[0].sectionname)
            }

            function get_downpayment(){
                $.ajax({
                    type:'GET',
                    url: '/student/get/downpayment',
                    data:{
                        levelid:gradelevel_to_enroll[0].id,
                        syid:enrollment_setup[0].syid,
                        semid:enrollment_setup[0].semid,
                    },
                    success:function(data){
                        dp_setup = data
                        if(data.length > 0){
                            $('#dp_amount').text(data[0].amount)
                        }
                    }
                })
            }

            function get_balforward(){
                $.ajax({
                    type:'GET',
                    url: '/student/get/balanceforward',
                    data:{
                        levelid:gradelevel_to_enroll[0].id,
                        syid:enrollment_setup[0].syid,
                        semid:enrollment_setup[0].semid,
                    },
                    success:function(data){
                        if(data.length > 0){
                            $('#bal_forward').text((data[0].amount).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"))
                        }
                    }
                })
            }

            var enrollment_setup = []
            var schoolyear = @json($schoolyear)

            // 1e
            function get_enrollment_setup(){

                $('.no_enrollment_period').attr('hidden','hidden')

                $.ajax({
                    type:'GET',
                    url: '/enrollmentsetup/list',
                    data:{
                        syid:$('#filter_sy').val(),
                        semid:$('#filter_sem').val(),
                        active:1,
                        acadprogid:studinfo[0].acadprogid
                    },
                    success:function(data){

                        var temp_data = [];

                        $.each(data,function(a,b){
                            if(studtype == "old"){
                                if(b.admission_studtype != 2){
                                    temp_data.push(b)
                                }
                            }else{
                                if(b.admission_studtype != 2){
                                    temp_data.push(b)
                                }
                            }
                           
                        })

                        data = temp_data

                        if(data.length == 0){
                             $('.no_enrollment_period').removeAttr('hidden')
                            return false;
                        }

                        if(data[0].status == 'Ended'){
                            $('#available_enrollment').empty()
                            $('#available_enrollment').append('<tr><td colspan="4">Enrollment Period Ended last '+data[0].enrollmentend+'</td></tr>')
                            $('#preregistration_flow').removeAttr('hidden')
                            return false
                        }else if(data[0].status == 'Not Yet Started'){
                            $('#available_enrollment').empty()
                            $('#available_enrollment').append('<tr><td colspan="4">Enrollment Period will start on '+data[0].enrollmentstart+'</td></tr>')
                            $('#preregistration_flow').removeAttr('hidden')
                            return false
                        }
                        
                        enrollment_setup = data
                        var can_submit = true;
                        if(enrollment_setup.length > 0){
                            if(enrollmentrecord.length > 0){
                                if(studinfo[0].acadprogid == 5 || enrollment_setup[0].acadprogid == 6){
                                    if(enrollment_setup[0].syid == enrollmentrecord[0].syid && enrollment_setup[0].semid == enrollmentrecord[0].semid){
                                        can_submit = false;
                                    }
                                }else{
                                    if(enrollment_setup[0].syid == enrollmentrecord[0].syid){
                                        can_submit = false;
                                    }
                                }
                            }

                            if(can_submit){
                                get_preregistration()
                                get_online_payments()
                                get_downpayment()
                                get_balforward()
                                get_updateinfo()
                                $('#available_enrollment').empty()
                                $.each(data,function(a,b){
                                    $('#available_enrollment').append('<tr><td>'+b.description+'</td><td> '+b.progname+' </td><td>'+b.enrollmentstart+'</td><td>'+b.enrollmentend+'</td></tr>')
                                })
                                $('#preregistration_flow').removeAttr('hidden')
                            }
                          
                        }else{
                            $('.no_enrollment_period').removeAttr('hidden')
                            $('.without_enrollment').removeAttr('hidden')
                        }
                    }
                })
              
            }

            function get_online_payments(){
             
                $.ajax({
                    type:'GET',
                    url: '/student/onlinepayment/list',
                    data:{
                        syid:enrollment_setup[0].syid,
                        semid:enrollment_setup[0].semid
                    },
                    success:function(data){
                        $('#uploaded_onlinepayments_holder').empty()
                        if(data.length > 0 ){
                            $('#payment_count').text(data.length)
                            $.each(data,function(a,b){
								var stat = b.isapproved

                                // if(b.remarks != ""){
                                //     stat += stat + ' "' + b.remarks + '"'
                                // }

                                // if(b.isapproved == 0){
                                //     stat = '<span class="badge badge-danger">On process</span> '
                                // }
								// else if(b.isapproved == 1){
                                //     stat = '<span class="badge badge-success">Approved</span> '
                                // }
								// else if(b.isapproved == 2){
                                //     stat = '<span class="badge badge-danger">Not approved</span><br> "'+b.remarks+'"'
                                // }
								// else if(b.isapproved == 3){
                                //     stat = '<span class="badge badge-danger">Canceled</span>  '
                                // }
								// else if(b.isapproved == 5){
                                //     stat = '<span class="badge badge-success">Paid</span> '
                                // }
                                var src = '{{asset('')}}'+b.picUrl+"?random="+new Date().getTime()
                                var html = '<tr><td class="text-center"><img style="width:50px" src="'+src+'"></td><td class="align-middle">&#8369; '+b.amount+'</td><td class="align-middle">'+stat+'</td><td class="align-middle">'+b.paymentDate+'</td></tr>'
                                $('#uploaded_onlinepayments_holder').append(html)
                            })    
                        }else{
                            var html = '<tr><td colspan="4" class="text-center">Payment reciept was uploaded.</td></tr>'
                            $('#uploaded_onlinepayments_holder').append(html)
                        }
                    }
                })

            }

            // 1g
            var preregistration = []
            function get_preregistration(){
                $.ajax({
                    type:'GET',
                    url: '/student/preenrollment/list',
                    data:{
                        syid:$('#filter_sy').val(),
                        semid:$('#filter_sem').val()
                    },
                    success:function(data){
                        preregistration = data
                        $('.step2_com').attr('hidden','hidden')
                        if(preregistration.length > 0){
                            $('.step2_com').removeAttr('hidden')
                            $('.step2_ncom').attr('hidden','hidden')
                            $('.step2_datecom').text(preregistration[0].createddatetime)
                            $('#prereg_submit').remove()
                            get_submitted_preregrequirements()
                        }else{
                            $('.step2_ncom').removeAttr('hidden')
                            $('#submit_enrollmentsetup').removeAttr('hidden')
                            get_preregrequirements()
                        }
                    }
                })
            }

            function get_preregrequirements(){
                $('#preregrequirements_holder').empty()
                $.ajax({
                    type:'GET',
                    url: '/superadmin/setup/document/list',
                    data:{
                            levelid:studinfo[0].levelid
                    },
                    success:function(data){
                            $('#preregreqbody').empty()
                            $.each(data, function(a,b){
                                var required = ''
                                var add = true;
                                var require_label = ''
                                if(b.isRequired == 1){
                                        required = 'required'
                                        require_label = ' <span class="text-danger">*</span>'
                                }
                                if(b.doc_studtype != null){
                                    if(studinfo[0].studtype == 'new' && b.doc_studtype != 'New'){
                                        add = false
                                    }else if(studinfo[0].studtype == ' transferee' && b.doc_studtype != 'Transferee'){
                                        add = false
                                    }
                                    else{
                                         add = false
                                    }
                                }
                                if(add){
                                        $('#preregrequirements_holder').append('<tr data-status="0"><td class="align-middle" width="30%">'+b.description+require_label+'</td><td class="align-middle" width="70%"><input class="form-control form-controm-sm" type="file" name="req'+b.id+'" '+required+'></td></tr>')
                                }
                            })
                    }
                })
            }

            function get_submitted_preregrequirements(){
                $('.submitted_requirements').removeAttr('hidden')
                $('#submitted_requirements_holder').empty()
                $.ajax({
                    type:'GET',
                    url: '/student/requirements/list',
                    data:{
                        syid:$('#filter_sy').val(),
                        semid:1,
                        levelid:studinfo[0].levelid,
                    },
                    success:function(data){
                        if(data.length > 0 ){
                            $.each(data,function(a,b){
                                var td_input = ''
                                var add = true;
                                var image = 'NO IMAGE UPLOADED'
                                if(b.picurl != ""){
                                    var src = '{{asset('')}}'+b.picurl+"?random="+new Date().getTime()
                                    image = '<img src="'+src+'" height="80px">'
                                }
                                if(b.doc_studtype != null){
                                    if(studinfo[0].studtype == 'new' && b.doc_studtype != 'New'){
                                        add = false
                                    }else if(studinfo[0].studtype == ' transferee' && b.doc_studtype != 'Transferee'){
                                        add = false
                                    }
                                    else{
                                         add = false
                                    }
                                }
                                if(add){
                                    var html = '<tr><td class="align-middle">'+b.description+'</td><td>'+td_input+'</td><td>'+image+'</td></tr>'
                                    $('#submitted_requirements_holder').append(html)
                                }
                            })    
                        }
                       
                    }
                })
            }

            


            $( '#submit_enrollmentsetup' )
                .submit( function( e ) {

                    var valid_submission = true;

                    $.each(all_preregrequirements,function(a,b){
                        if(b.isRequired == 1){
                           if($('input[name="req'+b.id+'"]').val() == null || $('input[name="req'+b.id+'"]').val() == '' || $('input[name="req'+b.id+'"]').val() == undefined){
                                valid_submission = false;
                                Toast.fire({
                                    type: 'info',
                                    title: 'Please fill in the required documents.'
                                })
                           }
                        }
                    })

                    if(valid_submission){

                        $('#prereg_submit').text('PROCESSING ...')
                        $('#prereg_submit').attr('disabled','disabled')

                        var inputs = new FormData(this)

                        inputs.append('input_setup_type',enrollment_setup[0].type)
                        inputs.append('semid',enrollment_setup[0].semid)
                        inputs.append('syid',enrollment_setup[0].syid)
                        inputs.append('gradelevelid',studinfo[0].levelid)
                        inputs.append('studstrand',$('#input_strand').val())
                        inputs.append('courseid',$('#input_course').val())
                        inputs.append('admissiontype',enrollment_setup[0].id)

                        $.ajax({
                            url: '/student/preenrollment/submit',
                            type: 'POST',
                            data: inputs,
                            processData: false,
                            contentType: false,
                            success:function(data) {
                                get_studentinfo()
                                load_filter_data()
                                $('#prereg_submit').text('SUBMIT')
                                $('#prereg_submit').removeAttr('disabled')
                                Toast.fire({
                                    type: 'success',
                                    title: 'Submitted in successfully'
                                })

                            },
                            error:function(){
                                Toast.fire({
                                    type: 'success',
                                    title: 'Failed to submit'
                                })
                                $('#prereg_submit').removeAttr('disabled')
                                $('#prereg_submit').text('SUBMIT')
                            }
                        })

                    }

              


                e.preventDefault();
            })

            $("#input_number").inputmask({mask: "9999-999-9999"});

            $( '#paymentInfo' )
                  .submit( function( e ) {

                        $('#proceedpayment').attr('disabled','disabled')
                        $('#proceedpayment').text('PROCESSING ...')

                        if($('#input_number').val() == "" || ($('#input_number').val()).toString().replace(/-|_/g,'').length != 11){
                            $('#input_number').addClass('is-invalid')
                            valid_input = false
                            $('#proceedpayment').text('SUBMIT PAYMENT RECEIPT')
                            $('#proceedpayment').removeAttr('disabled','disabled')
                            return false
                        }

                        var inputs = new FormData(this)

                        var length = $('.dassitem').length;
                        var summary = [];

                        if(dp_setup[0].uv == 1){
                            summary.push($('#amount').val().replace(',',''))
                            summary.push(dp_setup[0].description)
                            summary.push("1 " + dp_setup[0].classid + " "+dp_setup[0].id)
                            summary.push(1)
                        }else{
                            summary.push($('#amount').val().replace(',',''))
                            summary.push(dp_setup[0].description)
                            summary.push("1 " + dp_setup[0].classid + " "+dp_setup[0].itemid)
                            summary.push(1)
                        }

                        var sem = 1;

                        if(studinfo[0].acadprogid == 5 || studinfo[0].acadprogid == 6){
                            sem = preregistration[0].semid
                        }

                        inputs.append('info',summary)
                        inputs.append('syid',$('#filter_sy').val())
                        inputs.append('semid',sem)
                        inputs.append('studid',studinfo[0].sid)

                        $.ajax( {
                              url: '/payment/online/submitreceipt',
                              type: 'POST',
                              data: inputs,
                              processData: false,
                              contentType: false,
                              success:function(data) {

                                    if(data[0].status == 0){

                                          $('#proceedpayment').removeClass('disabled')
                                          $('#proceedpayment').removeClass('btn-default')
                                          $('#proceedpayment').addClass('btn-success')

                                          $('#bankName').removeClass('is-invalid')
                                          $('#bankName').css('display','hidden')

                                          $('.is-invalid').removeClass('is-invalid')
                                          $('.invalid-feedback').css('display','hidden')

                                          $.each(data[0].inputs,function(key,value){
                                                if(value != null){
                                                      $('#'+key).removeClass('is-invalid')
                                                      $('#'+key).css('display','hidden')
                                                }
                                          })

                                          $.each(data[0].errors,function(key,value){
                                                $('#'+key).addClass('is-invalid')
                                                $('#'+key).next('.invalid-feedback').text(value)
                                          })

                                          $('#proceedpayment').text('SUBMIT PAYMENT RECEIPT')
                                          $('#proceedpayment').removeAttr('disabled','disabled')
                                    
                                    }
                                    else if(data[0].status == 1){

                                            load_filter_data()
                                            get_online_payments()
                                            $('#proceedpayment').text('SUBMIT PAYMENT RECEIPT')
                                            $('#proceedpayment').removeAttr('disabled','disabled')

                                            Toast.fire({
                                                type: 'success',
                                                title: 'Submitted Successfully'
                                            })

                                            $('.is-invalid').removeClass('is-invalid')
                                            $('.invalid-feedback').css('display','hidden')

                                            $('#paymentType').val("").change();
                                            $('#bankName').val("").change();
                                            $('#recieptImage').val("");
                                            $('#refNum').val("");
                                            $('#transDate').val("");
                                            $('#amount').val("");
                                            $('#receipt').attr('src',"");
      
                                    }
                              
                              },
                              error:function(){

                                Toast.fire({
                                    type: 'success',
                                    title: 'Failed to upload payment. Please try again.'
                                })
                                $('#proceedpayment').text('SUBMIT PAYMENT RECEIPT')
                                $('#proceedpayment').removeAttr('disabled','disabled')

                              }
                        } );
                        e.preventDefault();
            } );


           
           
        })
    </script>

    <script>
        $(document).ready(function(){
            $(document).on('change','#paymentType',function(){
                if($(this).val() == 3){
                    $('#bankName').removeAttr('disabled')
                }else{
                    $('#bankName').attr('disabled','disabled')
                    $('#bankName').val("").change
                }

            })

        })


    </script>

    <script>
        $(document).ready(function(){

            function readURL(input) {
                  if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        
                        reader.onload = function (e) {
                        $('#receipt').attr('src', e.target.result);
                        }
                        
                        reader.readAsDataURL(input.files[0]);
                  }
            }
            
            $("#recieptImage").change(function(){
                  readURL(this);
            });


            $("input[data-type='currency']").on({
                    keyup: function() {
                        formatCurrency($(this));
                    },
                    blur: function() { 
                        formatCurrency($(this), "blur");
                    }
            });
            

            function formatNumber(n) {
            // format number 1000000 to 1,234,567
                    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            }


            function formatCurrency(input, blur) {
            // appends $ to value, validates decimal side
            // and puts cursor back in right position.
            
            // get input value
            var input_val = input.val();
            
            // don't validate empty input
            if (input_val === "") { return; }
            
            // original length
            var original_len = input_val.length;

            // initial caret position 
            var caret_pos = input.prop("selectionStart");
            
            // check for decimal
            if (input_val.indexOf(".") >= 0) {

            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");

            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            // add commas to left side of number
            left_side = formatNumber(left_side);

            // validate right side
            right_side = formatNumber(right_side);
            
            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                    right_side += "00";
            }
            
            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);

            // join number by .
            input_val =  left_side + "." + right_side;

            } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);
            input_val = input_val;
            
            // final formatting
            if (blur === "blur") {
                    input_val += ".00";
            }
            }
            
            // send updated string to input
            input.val(input_val);

            // put caret back in the right position
            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
            }

        })
    </script>


@endsection
