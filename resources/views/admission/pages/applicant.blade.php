@extends('admission.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v3.10.2/main.min.css') }}" />
    <style>
        .list_style li {
            list-style: circle !important;
        }

        .align-middle td {
            vertical-align: middle;
        }

        .radius-custom-header {
            color: white;
            /* background-color: #d3d3d3; */
            border-top-left-radius: .0rem !important;
            border-top-right-radius: .0rem !important;
        }

        .card {
            border-radius: 0px !important;
        }

        .btn_custom_group {
            padding: 3px 8px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: unset !important;
        }
    </style>
@endsection

@section('content')
    <!-- MODAL PREREGISTER -->
    <div class="modal fade" id="modalNewApplicant" tabindex="-1" role="dialog" aria-labelledby="modalNewApplicant"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content shadow">
                <div class="modal-header ">
                    <h5 class="modal-title">New Student Application Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <form id="regForm101" action="{{ route('student.info.save') }}" method="POST"
                        enctype="multipart/form-data" class="was-validated" autocomplete="off">
                        @csrf
                        <div class="card shadow-none mb-0">
                            {{-- <div class="card-header" style="font-size: 17px; color: #000000">
                                <i class="fas fa-layer-group" style="padding-right: 5px;"></i>STUDENT INFORMATION
                            </div> --}}
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label class="mb-1">First Name <span class="text-danger">*</span> </label>
                                        <input type="text" class="form-control" id="fname" name="fname" required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>First Name is required!</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="mb-1">Middle Name </label>
                                        <input type="text" class="form-control" id="mname" name="mname">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="mb-1">Last Name <span class="text-danger">*</span> </label>
                                        <input type="text" class="form-control" id="lname" name="lname" required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Last Name is required!</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label class="mb-1">Suffix</label>
                                        <select class="form-control select2" id="suffix" name="suffix"
                                            style="width: 100%;">
                                            <option value="Jr.">Jr.</option>
                                            <option value="II">II</option>
                                            <option value="III">III</option>
                                            <option value="IV">IV</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="mb-1">Date of Birth<span class="text-danger">*</span> </label>
                                        <input type="date" class="form-control" id="dob" name="dob" required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Date of birth is required!</strong>
                                        </span>
                                    </div>
                                    {{-- <div class="form-group col-md-3">
                                        <label class="mb-1">Place of Birth<span class="text-danger">*</span> </label>
                                        <input type="text" class="form-control" id="pob" name="pob" required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Place of birth is required!</strong>
                                        </span>
                                    </div> --}}
                                    <div class="form-group col-md-3">
                                        <label class="mb-1">Gender <span class="text-danger">*</span> </label>
                                        <select class="form-control select2" id="select-gender" name="gender"
                                            style="width: 100%;" required>
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Gender is required!</strong>
                                        </span>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label class="mb-1">Age<span class="text-danger">*</span> </label>
                                        <input type="number" min="0" class="form-control" id="age"
                                            name="age" required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Age is required!</strong>
                                        </span>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label class="mb-1"><b>Phone Number <span class="text-danger">*<span></b></label>
                                        <input class="form-control" placeholder="09XX-XXXX-XXXX " name="contact_number"
                                            id="contact_number" minlength="13" maxlength="13" autocomplete="off"
                                            required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong id="mobileError">Mobile number is required</strong>
                                        </span>
                                    </div>

                                    {{-- <div class="form-group col-md-6">
                                        <label class="mb-1">Religion</label>
                                        <select class="form-control select2" id="select-religion" name="religion_id"
                                            style="width: 100%;">
                                            @foreach (DB::table('religion')->where('deleted', 0)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->religionname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="mb-1">Nationality</label>
                                        <select class="form-control select2" id="select-nationality"
                                            name="nationality_id" style="width: 100%;">
                                            @foreach (DB::table('nationality')->where('deleted', 0)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->nationality }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    {{-- <div class="form-group col-md-3">
                                        <label class="mb-1">House No.</label>
                                        <input type="text" class="form-control" id="houseno" name="houseno">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="mb-1">Street</label>
                                        <input type="text" class="form-control" id="street" name="street">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="mb-1">Barangay</label>
                                        <input type="text" class="form-control" id="brgy" name="brgy">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="mb-1">City/Municipality</label>
                                        <input type="text" class="form-control" id="city" name="city">
                                    </div> --}}
                                    {{-- <div class="form-group col-md-3">
                                        <label class="mb-1">Province</label>
                                        <input type="text" class="form-control" id="province" name="province">
                                    </div> --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-control-label"><b>Present Address <span
                                                    class="text-danger">*<span></b></label>
                                        <input type="text" class="form-control " placeholder="Present address"
                                            name="address" autocomplete="off" required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Present Address is required</strong>
                                        </span>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label class="form-control-label"><b>Email Address <span
                                                    class="text-danger">*<span></b></label>
                                        <input type="email" class="form-control " placeholder="Email address"
                                            name="email" autocomplete="off" required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong id="mobileError">Email Address is required</strong>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card shadow-none mb-0">
                            <div class="card-header" style="font-size: 17px; color: #000000">
                                <i class="fas fa-layer-group" style="padding-right: 5px;"></i>SCHOOL INFORMATION
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label class="mb-1">Last School Attended</label>
                                        <input type="text" class="form-control" id="last_school_attended"
                                            name="last_school_attended" required>

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="mb-1">Last Grade Level Completed </label>
                                        <input type="text" class="form-control" id="last_grade_level_completed"
                                            name="last_grade_level_completed">

                                    </div>
                                    {{-- <div class="form-group col-md-4">
                                        <label class="mb-1">Schools Contact No. </label>
                                        <input type="text" class="form-control" id="school_contact_number"
                                            name="school_contact_number">
                                    </div> --}}
                                    <div class="form-group col-md-4">
                                        <label class="mb-1"> Last School Address </label>
                                        <input type="text" class="form-control" id="last_school_mailing_address"
                                            name="last_school_mailing_address">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label class="mb-1">Level to Enroll </label>
                                        <select class="form-control select2" id="select-gradelevel" name="gradelevel_id"
                                            style="width: 100%;" required>
                                            @foreach (DB::table('gradelevel')->where('deleted', 0)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->levelname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-8" id="course-wrapper">
                                        <label class="mb-1"> Desired Course </label>
                                        <select class="form-control select2" id="select-course" name="course_id"
                                            style="width: 100%;">
                                            @foreach (DB::table('college_courses')->where('deleted', 0)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->courseDesc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <div class="form-group col-md-2">
                                        <label class="mb-1"> JHS GWA </label>
                                        <input type="number" class="form-control" id="jhs_gwa" name="jhs_gwa">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label class="mb-1"> SHS GWA </label>
                                        <input type="number" class="form-control" id="shs_gwa" name="shs_gwa">
                                    </div> --}}

                                </div>
                            </div>
                        </div>

                        {{-- <div class="card shadow-none mb-0">
                            <div class="card-header" style="font-size: 17px; color: #000000">
                                <i class="fas fa-layer-group" style="padding-right: 5px;"></i>ENTRANCE EXAM SCHEDULING
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card shadow-none">

                                            <div class="card-body p-0">
                                                <div id="calendar"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class=""
                                            style="border: 0.5px solid rgb(221, 219, 219);border-radius: 4px;">
                                            <div class="card-body">
                                                <div>
                                                    <label class="mr-2"><i
                                                            class="fas fa-square-full text-success mr-1"></i>
                                                        Available
                                                        Slot</label>
                                                    <label><i class="fas fa-square-full text-danger mr-1"></i> Fully Booked
                                                    </label>
                                                </div>
                                                <strong>
                                                    Instructions:
                                                </strong>
                                                <ul class="list_style">
                                                    <li>Select Date of your preferred
                                                        Entrance Exam.</li>
                                                    <li>Pay your Entrance Examination fee to
                                                        the Finance/Cashier.</li>
                                                    <li>Present your Receipts on the day of
                                                        the Exam.</li>
                                                </ul>
                                                <strong>Notes: <em class="text-danger"> Remember No Receipts, No
                                                        Exam!</em>
                                                </strong>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <input type="number" class="form-control" id="examdate_id" hidden>
                                            <div class="form-group col-md-6">
                                                <label for="" class="mb-1">Campus</label>
                                                <input type="text" class="form-control" id="venue" disabled>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="" class="mb-1">Slot Available</label>
                                                <input type="number" class="form-control" id="slot" disabled>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="" class="mb-1">Exam Date</label>
                                                <input type="date" class="form-control" id="examdate"
                                                    name="examdate" readonly required>
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Exam Date is required!</strong>
                                                </span>
                                            </div>
                                        </div>

                                    </div>


                                </div>
                            </div>
                        </div> --}}

                </div>
                <div class="modal-footer d-flex">
                    <button tyoe="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary ml-auto" id="btn_save_examdate_0000"><i
                            class="far fa-paper-plane mr-1"></i>Submit</button>
                </div>
                </form>


            </div>

        </div>
    </div>

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-4">
                <div class="col-sm-6">
                    <h1 class="m-0">APPLICANT INFORMATION</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item "> <a href="/home">Home</a> </li>
                        <li class="breadcrumb-item active">{{ $page_desc }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="content">
        <div class="container-fluid">

            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-filter"></i> Filter
                    </h5>
                    <div class="card-tools">
                        Active SY : {{ DB::table('sy')->where('isactive', 1)->value('sydesc') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="" class="mb-1">School Year</label>
                            <select name="" id="schoolyear" class="form-control form-control-sm select2"
                                style="width: 100%;">
                                <option value="">Select School Year</option>
                                @foreach (DB::table('sy')->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->sydesc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="" class="mb-1">Grade Levels</label>
                            <select name="" id="gradelevels" class="form-control select2" style="width: 100%;">
                                <option value="">Select Level</option>
                                @foreach (DB::table('gradelevel')->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->levelname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="" class="mb-1">College Courses</label>
                            <select name="" id="college_courses" class="form-control select2"
                                style="width: 100%;">
                                <option value="">Select Course</option>
                                @foreach (DB::table('college_courses')->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->courseDesc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-primary card-outline card-outline-tabs shadow">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ $current_page == 1 ? 'active' : '' }}" id="content-prereg-tab"
                                data-toggle="pill" href="#content-prereg" role="tab" aria-controls="content-prereg"
                                aria-selected="">Pre-Registered Applicants</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $current_page == 2 ? 'active' : '' }}" id="content-verified-tab"
                                data-toggle="pill" href="#content-verified" role="tab"
                                aria-controls="content-verified" aria-selected=" ">Verified Applicants</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $current_page == 3 ? 'active' : '' }}" id="content-accepted-tab"
                                data-toggle="pill" href="#content-accepted" role="tab"
                                aria-controls="content-accepted" aria-selected=" ">Accepted Applicants
                            </a>
                        </li>

                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-content-below-tabContent">
                        <div class="tab-pane fade {{ $current_page == 1 ? 'show active' : '' }}" id="content-prereg"
                            role="tabpanel" aria-labelledby="content-prereg-tab">
                            <div class="table-responsive">
                                <table class="table-hover table table-striped table-valign-middle"
                                    id="tbl_applicant_preregistered" style="width: 100%;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Date Registered</th>
                                            <th>Applicant Name</th>
                                            <th>School Attended</th>
                                            <th>Course</th>
                                            <th>Pooling No.</th>
                                            <th>Exam Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade {{ $current_page == 2 ? 'show active' : '' }}" id="content-verified"
                            role="tabpanel" aria-labelledby="content-verified-tab">
                            <div class="table-responsive">
                                <table class="table-hover table table-striped table-valign-middle"
                                    id="tbl_applicant_verified" style="width: 100%;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Date Registered</th>
                                            <th>Applicant Name</th>
                                            <th>Last School Attended</th>
                                            <th>Desired Course</th>
                                            <th>Pooling No.</th>
                                            <th>Date of Exam</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade {{ $current_page == 3 ? 'show active' : '' }}" id="content-accepted"
                            role="tabpanel" aria-labelledby="content-accepted-tab">
                            <div class="table-responsive">
                                <table class="table-hover table table-striped table-valign-middle"
                                    id="tbl_applicant_accepted" style="width: 100%;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Date Registered</th>
                                            <th>Code</th>
                                            <th>Applicant Name</th>
                                            <th>Last School Attended</th>
                                            <th>Exam Result</th>
                                            <th>Fitted Course</th>
                                            <th>Assigned Course</th>
                                            <th>Status</th>
                                            <th>Actions</th>
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

    <div class="modal fade" id="modalShowVerify">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVerifyName">Verify Student Application</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <form id="verifyForm">
                        @csrf
                        <div class="card shadow-none mb-0">
                            {{-- <div class="card-header radius-custom-header" style="font-size: 17px; color: #000000">
                                <i class="fas fa-layer-group" style="padding-right: 5px;"></i>ASSIGN EXAM SETUP
                            </div> --}}
                            <div class="card-body">
                                <div class="row">

                                    <div class="form-group col-md-6">
                                        <label class="mb-1"><i class="fas fa-filter"></i> Academic Program </label>
                                        <select class="form-control " id="acadprog" style="width: 100%;">
                                            <option value=""> Select AcadProg</option>
                                            @foreach (DB::table('academicprogram')->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->progname }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="mb-1"><i class="fas fa-scroll"></i> Select Exam</label>
                                        <select class="form-control select2" id="select-category" style="width: 100%;">
                                            <option value="">Select Category</option>
                                            @foreach (DB::table('guidance_passing_rate')->where('deleted', 0)->get() as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->description }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="mb-1"><i class="far fa-calendar-alt"></i> Select Dates</label>
                                        <select class="form-control " id="select-dates" style="width: 100%;">
                                            <option value="">Select Date</option>
                                            {{-- @foreach (DB::table('guidance_examdate')->where('deleted', 0)->get() as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->examinationdate }}</option>
                                            @endforeach --}}

                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- <div class="card shadow-none mb-0">
                            <div class="card-header radius-custom-header" style="font-size: 17px; color: #000000">
                                <i class="fas fa-layer-group" style="padding-right: 5px;"></i>ENTRANCE EXAM SCHEDULING
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card shadow-none">

                                            <div class="card-body p-0">
                                                <div id="calendar"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class=""
                                            style="border: 0.5px solid rgb(221, 219, 219);border-radius: 4px;">
                                            <div class="card-body">
                                                <div>
                                                    <label class="mr-2"><i
                                                            class="fas fa-square-full text-success mr-1"></i>
                                                        Available
                                                        Slot</label>
                                                    <label><i class="fas fa-square-full text-danger mr-1"></i> Fully Booked
                                                    </label>
                                                </div>
                                                <strong>
                                                    Instructions:
                                                </strong>
                                                <ul class="list_style">
                                                    <li>Select Date of your preferred
                                                        Entrance Exam.</li>
                                                    <li>Pay your Entrance Examination fee to
                                                        the Finance/Cashier.</li>
                                                    <li>Present your Receipts on the day of
                                                        the Exam.</li>
                                                </ul>
                                                <strong>Notes: <em class="text-danger"> Remember No Receipts, No
                                                        Exam!</em>
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <input type="number" class="form-control" id="examdate_id" hidden>
                                            <div class="form-group col-md-6">
                                                <label for="" class="mb-1">Campus</label>
                                                <input type="text" class="form-control" id="venue" disabled>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="" class="mb-1">Slot Available</label>
                                                <input type="number" class="form-control" id="slot" disabled>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="" class="mb-1">Exam Date</label>
                                                <input type="date" class="form-control" id="examdate"
                                                    name="examdate" readonly required>
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Exam Date is required!</strong>
                                                </span>
                                            </div>
                                        </div>

                                    </div>


                                </div>
                            </div>
                        </div> --}}

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn_save_examdate"><i
                            class="far fa-paper-plane mr-1"></i>Confirm</button>
                </div>
                </form>


            </div>

        </div>
    </div>

    <div class="modal fade" id="modalDateList" tabindex="-1" role="dialog" aria-labelledby="modalDateList"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header">
                    {{-- <h5 class="modal-title" id="modalVerifyName">Verify Student Application</h5> --}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="mb-1"><i class="fas fa-filter"></i> Select Date </label>
                        <select class="form-control " id="select-listdate" style="width: 100%;">
                            <option value=""> Select Date</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn btn-primary btn_apply">Apply</div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('footerjavascript')
    <!-- fullCalendar 2.2.5 -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-v3.10.2/main.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script>
        var jsonPreregistered = {!! json_encode($jsonPreregistered) !!}
        var jsonVerified = {!! json_encode($jsonVerified) !!}
        var jsonAccepted = {!! json_encode($jsonAccepted) !!}
        var jsonexamdates = {!! json_encode($examdates) !!}

        var sy = 0
        var level = 0
        var course = 0
        var currentApplicantID = 0

        $(document).ready(function() {
            console.log(jsonexamdates);
            checklevelid()
            load_preregistered(jsonPreregistered)
            load_verified(jsonVerified)
            load_accepted(jsonAccepted)
            initCalendar()

            $('#acadprog').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: "Select AcadProg"
            })
            $('#select-dates').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: "Select Dates"
            })
            $('#acadprog').on('change', function() {
                var id = $(this).val()
                console.log(id);
                if (id > 0) {
                    $.ajax({
                        type: 'GET',
                        data: {
                            id: id
                        },
                        url: '{{ route('filter.examsetup') }}',
                        success: function(data) {
                            console.log(data);
                            $('#select-category').empty()
                            $('#select-category').select2({
                                data: data,
                                allowClear: true,
                                theme: 'bootstrap4',
                                placeholder: 'Select Exam Setup'
                            })


                        }
                    })
                }
            });

            $('#select-category').on('change', function() {
                var id = $(this).val()
                console.log(id);
            })

            $('#schoolyear').on('change', function() {
                sy = $(this).val()
                console.log(sy);
                get_applicants()
            })

            $('#schoolyear').select2({
                allowClear: true,
                // theme: 'bootstrap4',
                placeholder: "Select School Year"
            })
            $('#gradelevels').select2({
                allowClear: true,
                // theme: 'bootstrap4',
                placeholder: "Select GradeLevel"
            })
            $('#college_courses').select2({
                allowClear: true,
                // theme: 'bootstrap4',
                placeholder: "Select Course"
            })

            $('#gradelevels').on('change', function() {
                level = $(this).val()
                console.log(level);
                get_applicants()
            })

            $('#college_courses').on('change', function() {
                course = $(this).val()
                console.log(course);
                get_applicants()
            })

            $('#select-gradelevel').on('change', function() {
                var id = $(this).val()
                console.log(id);

                if (!id) {
                    $('#course-wrapper').prop('hidden', true);
                    return; // Exit the function if no values are selected
                }

                if (id >= 17 && id <= 21) {
                    $('#course-wrapper').prop('hidden', false);
                    console.log('Selected value ' + id + ' is between 18 and 21');
                } else {
                    $('#select-course').val("").change();
                    $('#course-wrapper').prop('hidden', true);
                    console.log('Selected value ' + id + ' is not in the range of 18-21');
                }
            })

            //VERIFY MODAL
            $(document).on('click', '.btn_verify', function() {
                var id = $(this).data('id')
                var name = $(this).data('name')
                // $('#modalVerifyName').text(name);
                // // verify_student(id)
                // $('#modalShowVerify').modal()
                console.log(id, name)
                currentApplicantID = id
                if (id) {
                    verify_student(id)
                }

            })

            $("#contact_number").inputmask({
                mask: "9999-999-9999"
            });

            $('#select-category').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select Exam Setup'
            })

            $('.btn_new_applicant').on('click', function() {
                $('#modalNewApplicant').modal({
                    backdrop: 'static',
                    keyboard: false
                })
            });


            $('.btn_delete').on('click', function() {
                var id = $(this).data('id')
                Swal.fire({
                    type: 'warning',
                    title: 'Delete Applicant?',
                    text: `You cant undo this.`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirm'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'GET',
                            url: '{{ route('delete.applicant') }}',
                            data: {
                                id: id,
                            },
                            success: function(response) {
                                console.log(response);
                                notify(response.status, response.message)
                                get_applicants()
                            },
                            error: function(xhr, status, error) {
                                console.log(xhr.responseJSON);
                            }
                        });
                    }
                });
            })

        });

        function verify_student(id) {
            Swal.fire({
                type: 'question',
                title: 'Verify Student?',
                text: `Student will be verified and will be able to take the entrance exam! `,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Verify'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "GET",
                        url: "/admission/verify-student",
                        data: {
                            id: id,
                            // acadprog_id: $('#acadprog').val(),
                            // exam_setup_id: $('#select-category').val(),
                            // examdate: $('#examdate').val(),
                            // examdate_id: $('#examdate_id').val()
                        },
                        success: function(response) {
                            console.log(response)
                            notify(response.status, response.message)
                            get_applicants()
                        },
                        error: function(xhr, status, error) {
                            // Handle error response from the server if needed
                            console.error('Error updating server:', error);
                        }
                    })
                }
            });

        }

        function initCalendar() {
            var calendarEl = $('#calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                contentHeight: 'auto', // Set the height of the content area (events, day cells)
                initialView: 'dayGridMonth',
                selectable: true, // Enable selection
                select: function(info) {
                    handleDateClick(info)
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: jsonexamdates
                // events: [{
                //         title: 'Booked',
                //         start: '2024-04-05',
                //         end: '2024-04-05',
                //         color: 'red'
                //     },
                //     {
                //         title: 'Available',
                //         start: '2024-04-08',
                //         end: '2024-04-08',
                //         color: 'green'
                //     },
                //     // Add more events as needed
                // ],

            });
            calendar.render();

            function handleDateClick(info) {
                var duplicateDates = []
                // console.log(info);
                const timestamp = info._i; // Assuming this is your timestamp
                const date = new Date(timestamp);
                const realDate = date.toISOString().split('T')[0];
                // console.log(realDate);
                var events = calendar.clientEvents()

                var title = ''
                var exam_event

                var evenArr = []
                jsonexamdates.forEach(element => {
                    if (element.examinationdate == realDate) {
                        console.log(element);
                        console.log(element.venue);

                        var objJSON = {}
                        objJSON.id = element.id
                        objJSON.text = `${element.formatted_examdate} - ${element.description}`

                        evenArr.push(objJSON)
                    }
                });
                console.log(evenArr);

                if (evenArr.length >= 2) {
                    $('#select-listdate').empty()
                    $('#select-listdate').select2({
                        data: evenArr,
                        theme: 'bootstrap4',
                        allowClear: true,
                        placeholder: "Select Date"
                    })

                    $('.btn_apply').on('click', function() {
                        var id = $('#select-listdate').val()

                        let current = jsonexamdates.find(function(element) {
                            return element.id == id;
                        });

                        console.log(id);
                        console.log(current)

                        try {
                            if (current.expired) {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Expired Slot',
                                    text: `Please find another latest slots!`,
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Okay'
                                })
                            } else {
                                if (current.title == 'Available') {
                                    console.log('Available!')
                                    available_dialogue(realDate, current)
                                } else {
                                    console.log('Sorry Fully Booked!')
                                    fullybook_dialogue()
                                }
                            }

                        } catch (error) {
                            console.log('Something goes wrong!')
                        }
                    })
                    $('#modalDateList').modal()
                } else {

                    let current = jsonexamdates.find(function(element) {
                        return element.examinationdate == realDate;
                    });

                    // console.log(realDate);
                    console.log(current);

                    try {
                        if (current.expired) {
                            Swal.fire({
                                type: 'error',
                                title: 'Expired Slot',
                                text: `Please find another latest slots!`,
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Okay'
                            })
                        } else {
                            if (current.title == 'Available') {
                                console.log('Available!')
                                available_dialogue(realDate, current)
                            } else {
                                console.log('Sorry Fully Booked!')
                                fullybook_dialogue()
                            }
                        }

                    } catch (error) {
                        console.log('Something goes wrong!')
                    }

                }


            }

            function available_dialogue(date, event) {

                Swal.fire({
                    type: 'question',
                    title: 'Are you sure to booked this date ?',
                    text: `[Acad Prog: ${event.progname}], Please ensure you attend on the designated date assigned to you.`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirm'
                }).then((result) => {
                    if (result.value) {
                        console.log(event);
                        $('#examdate_id').val(event.id)
                        $('#examdate').val(date)
                        $('#slot').val(event.slot_available)
                        $('#venue').val(event.venue)
                        notify('success', 'Selected Successfully!')
                        $('#modalDateList').modal('hide')
                        $('#modalShowVerify').modal('hide')
                    }
                });
            }

            function fullybook_dialogue() {
                Swal.fire({
                    type: 'warning',
                    title: 'I\'m sorry, but this slot is currently fully booked!',
                    text: `Please choose another date.`,
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Okay'
                })
                $('#modalDateList').modal('hide')

            }

            function dialogue_pooling(pooling) {
                Swal.fire({
                    type: 'success',
                    title: pooling,
                    text: `Remember to copy and save your Pooling Number to be used for the Entrance Examination.`,
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Okay',
                    allowOutsideClick: false, // Prevents closing when clicking outside
                    backdrop: 'static',
                    customClass: {
                        confirmButton: 'copy-button-class' // Custom class for the confirm button
                    },
                    buttonsStyling: false, // Disable default button styling
                    onOpen: (modal) => {
                        // Add event listener to the custom copy button
                        modal.querySelector('.copy-button-class').addEventListener('click', () => {
                            // Create a hidden textarea element
                            const textarea = document.createElement('textarea');
                            textarea.value = pooling; // Set the text to be copied
                            textarea.setAttribute('readonly',
                                ''); // Make it readonly to prevent focus
                            textarea.style.position = 'absolute';
                            textarea.style.left = '-9999px'; // Move off-screen
                            document.body.appendChild(textarea);

                            // Select and copy the text
                            textarea.select();
                            document.execCommand('copy');

                            // Clean up
                            document.body.removeChild(textarea);

                            // Show a message to the user that the text has been copied
                            Swal.fire({
                                type: 'success',
                                title: 'Copied!',
                                text: 'The Code has been copied to the clipboard.',
                                showConfirmButton: false,
                                timer: 1500 // Auto-close the success message after 1.5 seconds
                            });
                        });
                    }
                });
                get_applicants()

            }

            $(document).on('click', '#btn_save_examdate', function(e) {
                e.preventDefault(); // Prevent the default form submission

                var isvalid = true;

                if (!$('#acadprog').val()) {
                    $('#acadprog').addClass('is-invalid')
                    notify('error', 'AcadProg is required!')
                    isvalid = false;
                    return
                } else {
                    $('#acadprog').removeClass('is-invalid')
                }

                if (!$('#select-category').val()) {
                    $('#select-category').addClass('is-invalid')
                    notify('error', 'Exam Setup is required!')
                    isvalid = false;
                    return
                } else {
                    $('#select-category').removeClass('is-invalid')
                }

                // Validate Exam Date
                if ($('#examdate').val() == null || $('#examdate').val() == '') {
                    $('#examdate').addClass('is-invalid');
                    notify('error', 'Exam Date is required!');
                    isvalid = false;
                    return
                } else {
                    $('#examdate').removeClass('is-invalid'); // Remove validation error if present
                }

                // var id = $('#select-gradelevel').val();
                // if (id >= 17 && id <= 21) {
                //     $('#select-course').attr('required', true);
                //     if ($('#select-course').val() == null || $('#select-course').val() == '') {
                //         $('#select-course').addClass('is-invalid')
                //         notify('error', 'Course is required!')
                //         isvalid = false;
                //         return
                //     } else {
                //         $('#select-course').removeClass('is-invalid')
                //         $('#select-course').attr('required', false);
                //     }
                // } else {
                //     $('#select-course').removeClass('is-invalid')
                //     $('#select-course').attr('required', false);
                // }


                // if ($('#select-gradelevel').val() == null || $('#select-gradelevel').val() == '') {
                //     $('#select-gradelevel').addClass('is-invalid')
                //     notify('error', 'Grade Level is required!')
                //     isvalid = false;
                //     return
                // } else {
                //     $('#select-gradelevel').removeClass('is-invalid')
                // }
                if (isvalid) {
                    verify_student(currentApplicantID)
                }
            });


        }

        function checklevelid() {
            var id = $('#select-gradelevel').val();
            if (id) {
                $('#course-wrapper').prop('hidden', true);
                return; // Exit the function if no values are selected
            }

            if (id >= 17 && id <= 21) {
                $('#course-wrapper').prop('hidden', false);
                console.log('Selected value ' + id + ' is between 18 and 21');
            } else {
                $('#select-course').val("").change();
                $('#course-wrapper').prop('hidden', true);
                console.log('Selected value ' + id + ' is not in the range of 18-21');
            }
        }

        function get_applicants() {
            $.ajax({
                type: 'GET',
                url: '{{ route('get.applicants') }}',
                data: {
                    sy: sy,
                    level: level,
                    course: course
                },
                success: function(response) {
                    console.log(response);
                    load_preregistered(response.jsonPreregistered)
                    load_verified(response.jsonVerified)
                    load_accepted(response.jsonAccepted)
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON);
                }
            });
        }

        function load_preregistered(data) {
            console.log(data)
            $('#tbl_applicant_preregistered').DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                lengthChange: true,
                columns: [{
                        data: "formatted_created_at",
                        render: function(data, type, row) {
                            return new Date(row.formatted_created_at)
                                .toLocaleDateString(); // Format the date
                        }
                    },
                    {
                        data: "studname",
                        render: function(type, data, row) {
                            return `<span style="font-weight: 600;"> ${ row.studname ? row.studname : 'Not Specified'} </span>`
                        }
                    },
                    {
                        data: "last_school_attended",
                    },
                    {
                        data: null,
                        render: (data, type, row) =>
                            row.acadprog_id == 7 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                            <br> <span class="badge badge-secondary"> Technical Vocational </span>` : row
                            .acadprog_id == 6 ?
                            `<span> <strong> ${row.courseDesc} </strong> </span>
                            <br> <span class="badge badge-primary"> College </span>` : row
                            .acadprog_id == 5 ?
                            `<span> <strong> ${row.strandname} </strong></span> <br> 
                            <span class="badge badge-danger" > SHS Strand </span>` : row.acadprog_id == 4 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                            <br> <span class="badge badge-secondary"> High School </span>` : row.acadprog_id == 3 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                            <br> <span class="badge badge-secondary"> Elementary </span>` : row.acadprog_id == 2 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                            <br> <span class="badge badge-secondary"> Pre-School </span>` :
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>`
                    },
                    {
                        data: 'poolingnumber',
                        render: function(type, data, row) {
                            return `<span class="badge badge-primary"> ${ row.poolingnumber ? row.poolingnumber : 'N/A'} </span>`
                        }
                    },
                    {
                        data: 'formatted_examdate',
                    },
                    {
                        data: null,
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<div class="btn-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-default btn_verify btn_custom_group" data-toggle="tooltip" title="Verify" data-name="${row.studname}" data-id="${row.id}">
                                            <i class="fas fa-check text-success"></i> 
                                        </button>
                                    </div>
                                    <a href="/admission/edit-applicant?id=${row.id}" class="btn btn-default btn_custom_group"> <i class="fas fa-pen-fancy text-primary"></i> </a>
                                    <a type="button" href="javascript:void(0)" class="btn btn-default btn_delete btn_custom_group" data-id="${row.id}"> <i class="fas fa-trash-alt text-danger"></i> </a> 
                                </div>`;
                        }
                    }

                ],
            })
        }

        function load_verified(data) {
            console.log(data)
            $('#tbl_applicant_verified').DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                lengthChange: false,
                columns: [{
                        data: "formatted_created_at",
                        render: function(data, type, row) {
                            return new Date(row.formatted_created_at)
                                .toLocaleDateString(); // Format the date
                        }
                    },
                    {
                        data: "studname",
                        render: function(type, data, row) {
                            return `<span style="font-weight: 600;"> ${ row.studname ? row.studname : 'Not Specified'} </span>`
                        }
                    },
                    {
                        data: "last_school_attended",
                    },
                    {
                        data: null,
                        render: (data, type, row) =>
                            row.acadprog_id == 7 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                            <br> <span class="badge badge-secondary"> Technical Vocational </span>` : row
                            .acadprog_id == 6 ?
                            `<span> <strong> ${row.courseDesc} </strong> </span>
                            <br> <span class="badge badge-primary"> College </span>` : row
                            .acadprog_id == 5 ?
                            `<span> <strong> ${row.strandname} </strong></span> <br> 
                            <span class="badge badge-danger" > SHS Strand </span>` : row.acadprog_id == 4 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                            <br> <span class="badge badge-secondary"> High School </span>` : row.acadprog_id == 3 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                            <br> <span class="badge badge-secondary"> Elementary </span>` : row.acadprog_id == 2 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                            <br> <span class="badge badge-secondary"> Pre-School </span>` :
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>`
                    },
                    {
                        data: 'poolingnumber',
                        render: function(type, data, row) {
                            return `<span class="badge badge-primary"> ${ row.poolingnumber ? row.poolingnumber : 'N/A'} </span>`
                        }

                    },
                    {
                        data: 'formatted_examdate',
                    },
                    {
                        data: 'status',
                        className: 'text-center',
                        render: function(data, type, row) {
                            var renderHtml = '';
                            if (row.status == 1) {
                                renderHtml = `<span class="badge bg-purple text-center">Verified</span>`
                            } else if (row.status == 2) {
                                renderHtml = `<span class="badge bg-success text-center">Accepted</span>`
                            } else if (row.status == 3) {
                                renderHtml = `<span class="badge bg-danger text-center">Rejected</span>`
                            } else {
                                renderHtml = `<span class="badge bg-warning text-center">Pending</span>`
                            }

                            return renderHtml;
                        }

                    },
                    {
                        data: null,
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<div class="btn-group">
                                    <a href="/admission/edit-applicant?id=${row.id}" class="btn btn-default btn_custom_group"> <i class="fas fa-pen-fancy text-primary"></i> </a>
                                    <a type="button" href="javascript:void(0)" class="btn btn-default btn_delete btn_custom_group" data-id="${row.id}"> <i class="fas fa-trash-alt text-danger"></i> </a> 
                                </div>`;
                        }
                    }

                ],
            })
        }

        function load_accepted(data) {
            console.log(data)
            $('#tbl_applicant_accepted').DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                lengthChange: false,
                columns: [{
                        data: 'formatted_created_at',
                        render: function(data, type, row) {
                            return new Date(row.formatted_created_at)
                                .toLocaleDateString(); // Format the date
                        }
                    },
                    {
                        data: "poolingnumber",
                        render: function(type, data, row) {
                            return `<span class="badge badge-primary"> ${ row.poolingnumber ? row.poolingnumber : 'Not Specified'} </span>`
                        }
                    },
                    {
                        data: "studname",
                        render: function(type, data, row) {
                            return `<span style="font-weight: 600;"> ${ row.studname ? row.studname : 'Not Specified'} </span>`
                        }
                    },
                    {
                        data: "last_school_attended",
                    },
                    // {
                    //     data: 'courseDesc',
                    // },
                    {
                        data: null,
                        render: function(type, data, row) {
                            return ` <span class="badge bg-success"> <i class="fas fa-check"></i>  Passed </span> `;
                        }
                    },
                    // {
                    //     data: 'probation',
                    //     className: 'text-center',
                    //     render: function(type, data, row) {
                    //         return ` <span> ${row.probation ? '<i class="fas fa-check-square text-success"></i>' : '<i class="fas fa-window-close text-danger"></i>'  } </span> `;
                    //     }
                    // },
                    // {
                    //     data: 'fitted_course',
                    //     render: function(type, data, row) {
                    //         return ` <span class="text-success"> <strong> ${row.fitted_course} </strong> </span> <br> 
                //         ${ row.strandname ? `<span class="badge badge-danger"> SHS Strand </span>` : ''  } `;
                    //     }

                    // },
                    {
                        data: null,
                        render: (data, type, row) =>
                            row.acadprog_id == 7 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                            <br> <span class="badge badge-secondary"> Technical Vocational </span>` : row
                            .acadprog_id == 6 ?
                            `<span> <strong> ${row.courseDesc} </strong> </span>
                            <br> <span class="badge badge-primary"> College </span>` : row
                            .acadprog_id == 5 ?
                            `<span> <strong> ${row.strandname} </strong></span> <br> 
                            <span class="badge badge-danger" > SHS Strand </span>` : row.acadprog_id == 4 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                            <br> <span class="badge badge-secondary"> High School </span>` : row.acadprog_id == 3 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                            <br> <span class="badge badge-secondary"> Elementary </span>` : row.acadprog_id == 2 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                            <br> <span class="badge badge-secondary"> Pre-School </span>` :
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>`
                    },
                    {
                        data: null,
                        render: function(type, data, row) {
                            return ` <span class="text-success"> <strong>${row.final_courseabrv ?? 'N/A'} - ${row.final_courseDesc ?? 'N/A'} </strong> </span> 
                            <br> ${ row.strandname ? `<span class="badge badge-danger"> Final Strand </span>` : ''  } `;
                        }
                    },
                    {
                        data: 'status',
                        className: 'text-center',
                        render: function(data, type, row) {
                            var renderHtml = '';
                            if (row.status == 2) {
                                renderHtml = `<span class="badge bg-success text-center">Accepted</span>`
                            } else if (row.status == 3) {
                                renderHtml = `<span class="badge bg-danger text-center">Rejected</span>`
                            } else {
                                renderHtml = `<span class="badge bg-warning text-center">Pending</span>`
                            }

                            return renderHtml;
                        }

                    },
                    {
                        data: null,
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<div class="btn-group">
                                    <a href="/admission/edit-applicant?id=${row.id}" class="btn btn-default btn_custom_group"> <i class="fas fa-pen-fancy text-primary"></i> </a>
                                    <a type="button" href="javascript:void(0)" class="btn btn-default btn_delete btn_custom_group" data-id="${row.id}"> <i class="fas fa-trash-alt text-danger"></i> </a> 
                                </div>`;
                        }
                    }

                ],
            })
        }
    </script>
@endsection
