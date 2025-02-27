@extends('layouts.app')

@section('headerscript')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    @php
        $schoolInfo = DB::table('schoolinfo')->first();
    @endphp


    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <style>
        * {
            box-sizing: border-box;
        }

        .tbl-education th {
            color: #646464;
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
            background-color: {{ $schoolInfo->schoolcolor }} !important;

        }

        .btn-success.disabled,
        .btn-success:disabled {
            background-color: #bbbbbb !important;
            border-color: #bbbbbb !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }



        /* input[required]:not(:valid),
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          select[required]:not(:valid) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                border: 2px solid red;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          } */
    </style>
@endsection

@section('content')
    @php
        $courses1 = DB::table('college_courses')
            ->join('college_colleges', function ($join) {
                $join->on('college_courses.collegeid', '=', 'college_colleges.id');
                $join->where('college_colleges.acadprogid', 6);
                $join->where('college_colleges.deleted', 0);
            })
            ->where('college_courses.deleted', 0)
            ->select('college_courses.*')
            ->get();
        $courses2 = DB::table('college_courses')
            ->join('college_colleges', function ($join) {
                $join->on('college_courses.collegeid', '=', 'college_colleges.id');
                $join->where('college_colleges.acadprogid', 8);
                $join->where('college_colleges.deleted', 0);
            })
            ->where('college_courses.deleted', 0)
            ->select('college_courses.*')
            ->get();
    @endphp

    <div class="modal fade overlay w-100" id="modalAlert" style="display: none;" aria-hidden="true" data-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h5>Unable to process transaction using this browser. Please click the button (<i
                            class="fas fa-ellipsis-h mr-2 ml-2"></i>) on the upper right corner of the screen and select
                        "Open in Chrome".</h5>
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
                            <span class="invalid-feedback" role="alert">
                                <strong>Student Birth Date is required</strong>
                            </span>
                        </div>
                        <div class="form-group" id="not_found_holder" hidden>
                            <h5 class="pl-3 text-danger">Student Not Found. The information does not match our records.
                                Please contact the office of registrar through this number
                                {{ DB::table('schoolinfo')->first()->scontactno }}.</h5>
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
    <div class="modal fade" id="schedtableModal" style="display: none;" aria-hidden="true">
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
                            <input id="fname" class="form-control" placeholder="FIRST NAME"
                                onkeyup="this.value = this.value.toUpperCase();">
                        </div>
                        <div class="form-group  col-md-12">
                            <label><strong>LAST NAME</strong></label>
                            <input id="lname" class="form-control" placeholder="LAST NAME"
                                onkeyup="this.value = this.value.toUpperCase();">
                        </div>
                        <div class="form-group col-md-12">
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

    <form id="regForm" action="preregistration/submit" method="POST" enctype="multipart/form-data" autocomplete="off">

        @csrf
        <div class="card">
            <div class="card-header" style="font-size: 17px; color: #000000">
                STUDENT APPLICATION FORM
                @if (DB::table('schoolinfo')->first()->admission == 1)
                    <a class="btn btn-primary shadow btn-sm ml-auto" href="/admission/prereg" target="_blank">Admission
                        Prereg <i class="fa fa-arrow-right" aria-hidden="true"></i>
                    </a>
                @endif
            </div>
            <div class="card-body p-0" style="min-height: 400px">
                {{-- <div class="tab"> --}}
                <div class="row">
                    <div class="form-group col-md-2">
                    </div>
                    <div class="form-group col-md-8">
                        <br>
                        <div class="row">
                            @if (DB::table('schoolinfo')->first()->admission == 1)
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for=""> <strong>Pooling Code</strong></label> <span
                                            class="text-danger mr-1"> <em>
                                                (This
                                                is only
                                                Applicable
                                                for Exam Passer with Pooling Number.)</em></span>
                                        <input class="form-control" id="input_poolingnumber" placeholder=" Enter Code">
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12">
                                @php
                                    $academicprogram = DB::table('academicprogram')->get();
                                @endphp
                                <label for="">Available Enrollment </label>
                                <select name="input_acadprog" id="input_acadprog" class=" form-control select2"
                                    style="width: 100%;">
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
                {{-- </div> --}}
                <div id="tabb" hidden>

                    <div class="card shadow-none">
                        <div class="card-header" style="font-size: 17px; color: #000000">
                            <i class="fas fa-layer-group" style="padding-right: 5px;"></i>ENROLLMENT INFORMATION
                        </div>
                        <div class="card-body" style="">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="row">
                                        <div class="form-group col-md-6" id="holder_schoolyear">
                                            <label for="">School Year</label>
                                            <input disabled="disabled" class="form-control" id="input_syid_label">
                                        </div>
                                        <div class="form-group col-md-6" id="holder_semester">
                                            <label for="">Semester</label>
                                            <input disabled="disabled" class="form-control" id="input_semid_label">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label><b>Pre-registration Type <span class="text-danger">*<span></b></label>
                                            <select name="studtype" id="studtype" class="form-control select2" required
                                                style="width: 100%;">
                                                <option value="">PRE-REGISTRATION TYPE</option>
                                                <option value="1">NEW STUDENT</option>
                                                <option value="2">TRANSFEREE</option>
                                            </select>
                                            <span class="invalid-feedback" role="alert">
                                                <strong>Application type is required</strong>
                                            </span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label><b>Grade Level to enroll <span class="text-danger">*<span></b></label>
                                            <select name="gradelevelid" id="gradelevelid" class="form-control select2"
                                                required style="width: 100%;">
                                                <option value="">GRADE LEVEL</option>
                                            </select>
                                            <span class="invalid-feedback" role="alert">
                                                <strong id="gradeLevelError">Grade level is required.</strong>
                                            </span>
                                        </div>
                                        <div class="form-group col-md-6" id="lrn_holder">
                                            <label><b>LRN</b></label>
                                            <input name="lrn" id="lrn" class="form-control " placeholder="LRN"
                                                oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="12">
                                            <span class="invalid-feedback" role="alert">
                                                <strong id="gradeLevelError">LRN is required.</strong>
                                            </span>
                                        </div>
                                    </div>


                                    @if ($schoolInfo->withMOL == 1)
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

                                    @if ($schoolInfo->withESC == 1)
                                        <div class="form-group" id="lastschoolattfromgroup">
                                            <label><b>Grantee</b></label>
                                            <br>
                                            @foreach (DB::table('grantee')->get() as $item)
                                                <div class="icheck-primary d-inline" dusk="withESC{{ $item->id }}">
                                                    <input type="radio" name="withESC" id="withESC{{ $item->id }}"
                                                        @if ($item->id == 1) checked @endif
                                                        value="{{ $item->id }}">
                                                    <label class="mr-5" for="withESC{{ $item->id }}">
                                                        {{ $item->description }}
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
                                        <select name="courseid" id="courseid" class="form-control select2">
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong id="gradeLevelError">Course is required.</strong>
                                        </span>
                                    </div>

                                    <div class="form-group" id="strand-formgroup" hidden>
                                        <label><b>Strand</b></label>
                                        <select name="studstrand" id="studstrand" class="form-control">
                                            <option value="">SELECT STRAND</option>
                                            @php
                                                $strand = DB::table('sh_strand')
                                                    ->where('deleted', '0')
                                                    ->where('active', '1')
                                                    ->get();
                                            @endphp
                                            @foreach ($strand as $item)
                                                <option value="{{ $item->id }}">{{ $item->strandname }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Strand is required</strong>
                                        </span>
                                    </div>

                                    <input type="hidden" id="hidden_studtype" name="studtype">
                                    <input type="hidden" id="hidden_gradelevelid" name="gradelevelid">
                                    <input type="hidden" id="hidden_courseid" name="courseid">
                                    <input type="hidden" id="hidden_studstrand" name="studstrand">
                                    <input type="hidden" id="hidden_input_acadprog" name="input_acadprog">

                                </div>
                                <div class="form-group col-md-2">
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--  --}}

                    <div class="card shadow-none">
                        <div class="card-header" style="font-size: 17px; color: #000000">
                            <i class="fas fa-layer-group" style="padding-right: 5px;"></i>STUDENT PERSONAL INFORMATION
                        </div>
                        <div class="card-body" style="">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="" id="dup_info" class="text-danger" hidden><b><i>Student name
                                                already exist please contact your school registar.</i></b></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label><b>First Name <span class="text-danger">*<span></b></label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                        class="form-control namef" id="first-name" placeholder="First name"
                                        data-name="First Name" name="first_name" required>
                                    <span class="invalid-feedback" role="alert">
                                        <strong>First Name is required</strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-3">
                                    <label><b>Middle Name <span class="text-danger"><span></b></label>
                                    <input onkeyup="this.value = this.value.toUpperCase();" class="form-control namef"
                                        placeholder="Middle name" id="middle-name" data-name="Middle Name"
                                        name="middle_name" style="margin-bottom: 5px;">
                                    {{-- <span class="invalid-feedback" role="alert">
                                        <strong>Middle Name is required</strong>
                                    </span> --}}
                                    <div class="icheck-primary d-inline">
                                        <input class="form-control" type="checkbox" id="nomiddlename"
                                            name="nomiddlename" value="1" style="transform: scale(0.9);">
                                        <label for="nomiddlename" style="font-size: 14px;">I don't have a middle
                                            name</label>
                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label><b>Last Name <span class="text-danger">*<span></b></label>
                                    <input onkeyup="this.value = this.value.toUpperCase();" class="form-control namef"
                                        placeholder="Last name" data-name="Last Name" id="last_name" name="last_name"
                                        required>
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Last Name is required</strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-1">
                                    <label><b>SUFFIX</b></label>
                                    <input onkeyup="this.value = this.value.toUpperCase();" class="form-control p-2"
                                        placeholder="SU" id="suffix" name="suffix">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Suffix is required</strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label><b>Date of birth <span class="text-danger">*<span></b></label>
                                    <input type="date" class="form-control" placeholder="date of birth..."
                                        data-name="Date of Birth" id="dobirth" name="dob" required>
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Date of birth is required</strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label><b>Place of birth <span class="text-danger">*<span></b></label>
                                    <input type="text" onkeyup="this.value = this.value.toUpperCase();"
                                        class="form-control" placeholder="Place of Birth..." data-name="Place of Birth"
                                        id="pobirth" name="pobirth" required>
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Place of birth is required</strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-4">
                                    <label><b> Name in Native Language Character </b></label>
                                    <input type="text" class="form-control" placeholder="" id="nativename"
                                        name="nativename">
                                </div>
                                <div class="form-group col-md-4">
                                    <label><b>Mother Tongue </b></label>
                                    <select name="motherTongue" id="motherTongue" class="form-control select2" required
                                        style="width: 100%;">
                                        <option value="" selected>Mother Tongue</option>
                                        @foreach (DB::table('mothertongue')->where('deleted', 0)->get() as $mothertongue)
                                            <option value="{{ $mothertongue->id }}">{{ $mothertongue->mtname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label><b>Gender <span class="text-danger">*<span></b></label>
                                    <select name="gender" id="gender" class="form-control select2" required
                                        style="width: 100%;">
                                        <option value="" selected>Gender</option>
                                        <option value="FEMALE">Female</option>
                                        <option value="MALE">Male</option>
                                    </select>
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Gender is required</strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-4">
                                    <label><b> Ethnic Group </b></label>
                                    <select name="ethnicity" id="ethnicity" class="form-control select2" required>
                                        <option value="" selected>Ethnic Group</option>
                                        @foreach (DB::table('ethnic')->where('deleted', 0)->get() as $ethnic)
                                            <option value="{{ $ethnic->id }}">{{ $ethnic->egname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label><b>Religion <span class="text-danger">*<span></b></label>
                                    <select name="religion" id="religion" class="form-control select2" required>
                                        <option value="" selected>Religion</option>
                                        @foreach (DB::table('religion')->where('deleted', 0)->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->religionname }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Religion is required</strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-4">
                                    <label><b>Nationality <span class="text-danger">*<span></b></label>
                                    <select name="nationality" id="nationality" class="form-control select2" required
                                        style="width: 100%;">
                                        <option value=""></option>
                                        @foreach (DB::table('nationality')->where('deleted', 0)->orderBy('nationality')->get() as $item)
                                            @if ($item->id == 77)
                                                <option value="{{ $item->id }}" selected="selected">
                                                    {{ $item->nationality }}</option>
                                            @else
                                                <option value="{{ $item->id }}">{{ $item->nationality }}</option>
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
                                    <input class="form-control" onkeyup="this.value = this.value.toUpperCase();"
                                        name="street" id="street" autocomplete="off" required>
                                    <span class="invalid-feedback" role="alert">
                                        <strong id="streetError">Street is required</strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label><b>Barangay</b> <span class="text-danger">*<span></label>
                                    <input class="form-control" onkeyup="this.value = this.value.toUpperCase();"
                                        name="barangay" id="barangay" autocomplete="off" required>
                                    <span class="invalid-feedback" role="alert">
                                        <strong id="barangayError">Barangay is required</strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label><b>City/Municipality</b> <span class="text-danger">*<span></label>
                                    <input class="form-control" onkeyup="this.value = this.value.toUpperCase();"
                                        name="city" id="city" autocomplete="off"required>
                                    <span class="invalid-feedback" role="alert">
                                        <strong id="cityError">City is required</strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label><b>Province</b> <span class="text-danger">*<span></label>
                                    <input class="form-control" onkeyup="this.value = this.value.toUpperCase();"
                                        name="province" id="province" autocomplete="off" required>
                                    <span class="invalid-feedback" role="alert">
                                        <strong id="provinceError">Province is required</strong>
                                    </span>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label><b>Mobile Number <span class="text-danger">*<span></b></label>
                                    <input class="form-control" placeholder="09XX-XXXX-XXXX " name="contact_number"
                                        id="contact_number" minlength="13" maxlength="13" autocomplete="off" required>
                                    <span class="invalid-feedback" role="alert">
                                        <strong id="mobileError">Mobile number is required</strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"><b>Email Address</b> (Optional)</label>
                                    <input type="email" class="form-control " placeholder="Email address"
                                        name="email" autocomplete="off">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Email address is required</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card shadow-none">
                        <div class="card-header" style="font-size: 17px; color: #000000">
                            <i class="fas fa-layer-group" style="padding-right: 5px;"></i>PARENTS | GUARDIAN INFORMATION
                        </div>
                        <div class="card-body" style="">
                            <div class="row">
                                <div class="col-md-12">
                                    <i style="font-size:12px!important" class="text-danger">Scroll right for more
                                        information</i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 table-responsive ">
                                    <table class="table table-sm table-bordered mb-0" width="100%"
                                        id="tableParentsInfo" style="width:1300px">
                                        <thead>
                                            <tr>
                                                <th class="p-0" width="6%"></th>
                                                <th class="p-0 text-center" width="11%">First Name</th>
                                                <th class="p-0 text-center" width="11%">Middle Name</th>
                                                <th class="p-0 text-center" width="11%">Last Name</th>
                                                <th class="p-0 text-center" width="4%">Suffix</th>
                                                <th class="p-0 text-center" width="9%">Contact #</th>
                                                <th class="p-0 text-center" width="13%" hidden>Occupation/Relation</th>
                                                <th class="p-0 text-center" width="17%" hidden>Educational Attainment
                                                </th>
                                                <th class="p-0 text-center" width="18%">Home Address</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">
                                                    Father <span class="text-danger">*</span></th>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_father_fname_new" name="ffname" autocomplete="off"
                                                        onkeyup="this.value = this.value.toUpperCase();"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_father_mname_new" name="fmname" autocomplete="off"
                                                        onkeyup="this.value = this.value.toUpperCase();"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_father_lname_new" name="flname" autocomplete="off"
                                                        onkeyup="this.value = this.value.toUpperCase();"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_father_sname_new" name="fsuffix" autocomplete="off"
                                                        onkeyup="this.value = this.value.toUpperCase();"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_father_contact_new" name="fcontactno"
                                                        placeholder="09XX-XXXX-XXXX" autocomplete="off"></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="fha" name="fha" autocomplete="off"
                                                        placeholder="Father Home Address"
                                                        onkeyup="this.value = this.value.toUpperCase();"></td>
                                            </tr>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">Mother<span class="text-danger">*</span>
                                                </th>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_mother_fname_new" name="mfname" autocomplete="off"
                                                        onkeyup="this.value = this.value.toUpperCase();"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_mother_mname_new" name="mmname" autocomplete="off"
                                                        onkeyup="this.value = this.value.toUpperCase();"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_mother_lname_new" name="mlname" autocomplete="off"
                                                        onkeyup="this.value = this.value.toUpperCase();"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_mother_sname_new" name="msuffix" autocomplete="off"
                                                        onkeyup="this.value = this.value.toUpperCase();"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_mother_contact_new" name="mcontactno"
                                                        placeholder="09XX-XXXX-XXXX" autocomplete="off"></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="mha" name="mha" autocomplete="off"
                                                        placeholder="Mother Home Address"
                                                        onkeyup="this.value = this.value.toUpperCase();"></td>
                                            </tr>
                                            <tr>
                                                <th class="p-1 align-middle pl-1" colspan="1"
                                                    style="font-size:7pt !important">Mother Maiden Name <span
                                                        class="text-danger">*</span></th>
                                                <td class="p-1" colspan="3"><input
                                                        class="form-control form-control-sm-form"
                                                        id="input_mothermaidename_new" name="maidenname"
                                                        autocomplete="off"
                                                        onkeyup="this.value = this.value.toUpperCase();"></td>
                                                <td class="p-1" colspan="2"></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1"></td>
                                            </tr>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">Guardian <span
                                                        class="text-danger">*</span></th>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_guardian_fname_new" name="gfname" autocomplete="off"
                                                        onkeyup="this.value = this.value.toUpperCase();"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_guardian_mname_new" name="gmname" autocomplete="off"
                                                        onkeyup="this.value = this.value.toUpperCase();"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_guardian_lname_new" name="glname" autocomplete="off"
                                                        onkeyup="this.value = this.value.toUpperCase();"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_guardian_sname_new" name="gsuffix" autocomplete="off"
                                                        onkeyup="this.value = this.value.toUpperCase();"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_guardian_contact_new" name="gcontactno"
                                                        placeholder="09XX-XXXX-XXXX" autocomplete="off"></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="gha" name="gha" autocomplete="off"
                                                        placeholder="Guardian Home Address"
                                                        onkeyup="this.value = this.value.toUpperCase();"></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label style="font-size: 13px !important" class="text-danger mb-0"><span
                                                    class="text-danger">*</span><b>In case of emergency ( Recipient for
                                                    News, Announcement and School Information)</b></label>
                                        </div>
                                        <div class="col-md-4 pt-1">
                                            <div class="icheck-success d-inline">
                                                <input class="form-control" type="radio" id="father" name="incase"
                                                    value="1" required>
                                                <label for="father">Father
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 pt-1">
                                            <div class="icheck-success d-inline">
                                                <input class="form-control" type="radio" id="mother" name="incase"
                                                    value="2" required>
                                                <label for="mother">Mother
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 pt-1">
                                            <div class="icheck-success d-inline">
                                                <input class="form-control" type="radio" id="guardian" name="incase"
                                                    value="3" required>
                                                <label for="guardian">Guardian
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-card">
                        <div class="card-header" style="font-size: 17px; color: #000000">
                            <i class="fas fa-layer-group" style="padding-right: 5px;"></i>SOCIO ECONOMIC PROFILE
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 table-responsive ">
                                    <table class="table table-sm table-bordered mb-0" width="100%"
                                        style="width:1300px">
                                        <thead>
                                            <tr>
                                                <th class="p-0" width="10%"></th>
                                                <th class="p-0 text-center" width="18%">Educational Attainment</th>
                                                <th class="p-0 text-center" width="18%">Occupation</th>
                                                <th class="p-0 text-center" width="18%">Monthly Income</th>
                                                <th class="p-0 text-center" width="18%">Other Source of Income</th>
                                                <th class="p-0 text-center" width="10%">Ethnicity</th>
                                                <th class="p-0 text-center" width="8%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th class="p-0" width="10%">Father</th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="fea"
                                                        name="fea" autocomplete="off"
                                                        placeholder="Father Educational Attainment">
                                                </th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" name="foccupation"
                                                        id="input_father_occupation_new" autocomplete="off"
                                                        placeholder="Occupation"></th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="fmi"
                                                        name="fmi" autocomplete="off"
                                                        placeholder="Father Monthly Income"></th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="fosoi"
                                                        name="fosoi" autocomplete="off"
                                                        placeholder="Father Other Source of Income">
                                                </th>
                                                <th class="p-0 text-center" width="10%"><input
                                                        class="form-control form-control-sm-form" id="fethnicity"
                                                        name="fethnicity" autocomplete="off"
                                                        placeholder="Father Ethnicity"></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th class="p-0" width="10%">Mother</th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="mea"
                                                        name="mea" autocomplete="off"
                                                        placeholder="Mother Educational Attainment">
                                                </th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" name="moccupation"
                                                        id="input_mother_occupation_new" autocomplete="off"
                                                        placeholder="Occupation"></th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="mmi"
                                                        name="mmi" autocomplete="off"
                                                        placeholder="Mother Monthly Income"></th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="mosoi"
                                                        name="mosoi" autocomplete="off"
                                                        placeholder="Mother Other Source of Income">
                                                </th>
                                                <th class="p-0 text-center" width="10%"><input
                                                        class="form-control form-control-sm-form" id="methnicity"
                                                        name="methnicity" autocomplete="off"
                                                        placeholder="Mother Ethnicity"></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th class="p-0" width="10%">Guardian</th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="gea"
                                                        name="gea" autocomplete="off"
                                                        placeholder="Guardian Educational Attainment">
                                                </th>
                                                <th class="p-0 text-center" width="18%">
                                                    <input class="form-control form-control-sm-form"
                                                        style="display: block; width: 100%;" name="goccupation"
                                                        id="input_guardian_occupation_new" autocomplete="off"
                                                        placeholder="Occupation">
                                                </th>

                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="gmi"
                                                        name="gmi" autocomplete="off"
                                                        placeholder="Guardian Monthly Income"></th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="gosoi"
                                                        name="gosoi" autocomplete="off"
                                                        placeholder="Guardian Other Source of Income">
                                                </th>
                                                <th class="p-0 text-center" width="10%"><input
                                                        class="form-control form-control-sm-form" id="gethnicity"
                                                        name="gethnicity" autocomplete="off"
                                                        placeholder="Guardian Ethnicity"></th>

                                                <th class="p-0 text-center" width="8%">
                                                    <input class="form-control form-control-sm-form" name="relation"
                                                        id="input_guardian_relation_new" autocomplete="off"
                                                        placeholder="Relation">
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-card">
                        <div class="card-header" style="font-size: 17px; color: #000000">
                            <i class="fas fa-layer-group" style="padding-right: 5px;"></i>EDUCATIONAL INFORMATION
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 form-group mb-2">
                                    <label class="mb-1"><b> Name of School Last Attended </b></label>
                                    <input type="text" class="form-control" name="lastschoolatt" id="last_school_att"
                                        placeholder="Name of School Last Attended"
                                        onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-2">
                                    <label for="" class="mb-1"><b> Grade Level in that School </b></label>
                                    {{-- <input type="text" class="form-control form-control-sm-form" id="last_school_lvlid" placeholder="Grade Level in that School"> --}}
                                    <select name="glits" id="last_school_lvlid"
                                        class="form-control form-control-sm-form select2">
                                        <option value="">Grade Level in that School</option>
                                        @foreach ($gradelevel as $item)
                                            <option value="{{ $item->id }}">{{ $item->levelname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mb-2">
                                    <label for="" class="mb-1"> <b> School’s Contact No. </b> </label>
                                    <input type="text" class="form-control form-control-sm-form" name="scn"
                                        id="last_school_no" placeholder="09XX-XXXX-XXXX">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group mb-2">
                                    <label for="" class="mb-1">
                                        <b>
                                            Complete Mailing Address of School last
                                            Attended
                                        </b>
                                    </label>
                                    <input type="text" class="form-control form-control-sm-form" name="cmaosla"
                                        id="last_school_add"
                                        placeholder="Complete Mailing Address of School last Attended"
                                        onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <table class="table tabl-sm table-bordered tbl-education">
                                        <thead>
                                            <tr>
                                                <th colspan="3" class="p-0  pl-1">Educational Background</th>
                                            </tr>
                                            <tr>
                                                <th class="p-0" width="20%"></th>
                                                <th class="p-0 text-center" width="50%"> School Name</th>
                                                <th class="p-0 text-center" width="15%">S.Y. Graduated</th>
                                                <th class="p-0 text-center" width="15%">School Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">Pre-school</th>
                                                <td class="p-1"> <input class="form-control form-control-sm-form"
                                                        placeholder="Pre-school School Name" name="psschoolname"
                                                        id="psschoolname" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        name="pssy" id="pssy" placeholder="____-_____"
                                                        autocomplete="off">
                                                </td>
                                                <td class="p-1">
                                                    <select class="form-control form-control-sm select2"
                                                        name="psschooltype" id="psschooltype">
                                                        <option value=""></option>
                                                        <option value="Public">Public</option>
                                                        <option value="Private">Private</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">Grade School</th>
                                                <td class="p-1"> <input class="form-control form-control-sm-form"
                                                        placeholder="Grade School School Name" id="gsschoolname"
                                                        name="gsschoolname" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        name="gssy" id="gssy" placeholder="____-_____"
                                                        autocomplete="off">
                                                </td>
                                                <td class="p-1">
                                                    <select class="form-control form-control-sm select2"
                                                        name="gsschooltype" id="gsschooltype">
                                                        <option value=""></option>
                                                        <option value="Public">Public</option>
                                                        <option value="Private">Private</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">Junior High School</th>
                                                <td class="p-1"> <input class="form-control form-control-sm-form"
                                                        placeholder="Junior High School School Name" id="jhsschoolname"
                                                        name="jhsschoolname" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        placeholder="____-_____" id="jhssy" name="jhssy"
                                                        autocomplete="off">
                                                </td>
                                                <td class="p-1">
                                                    <select class="form-control form-control-sm select2"
                                                        name="jhsschooltype" id="jhsschooltype">
                                                        <option value=""></option>
                                                        <option value="Public">Public</option>
                                                        <option value="Private">Private</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">Senior High School</th>
                                                <td class="p-1 d-flex">
                                                    <input class="form-control form-control-sm-form flex-grow-1 mr-1"
                                                        placeholder="Senior High School School Name" id="shsschoolname"
                                                        name="shsschoolname" autocomplete="off">
                                                    <input class="form-control form-control-sm-form flex-grow-1"
                                                        placeholder="SHS Strand" id="shsstrand" name="shsstrand"
                                                        autocomplete="off">
                                                </td>
                                                <td class="p-1 text-center"><input
                                                        class="form-control form-control-sm-form" placeholder="____-_____"
                                                        id="shssy" name="shssy" autocomplete="off">
                                                </td>
                                                <td class="p-1">
                                                    <select class="form-control form-control-sm select2"
                                                        name="shsschooltype" id="shsschooltype">
                                                        <option value=""></option>
                                                        <option value="Public">Public</option>
                                                        <option value="Private">Private</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">College</th>
                                                <td class="p-1 d-flex">
                                                    <input class="form-control form-control-sm-form flex-grow-1 mr-1"
                                                        placeholder="College School Name" id="collegeschoolname"
                                                        name="collegeschoolname" autocomplete="off">
                                                    <input class="form-control form-control-sm-form flex-grow-1"
                                                        placeholder="Course" id="collegecourse" name="collegecourse"
                                                        autocomplete="off">
                                                </td>

                                                <td class="p-1 text-center"><input
                                                        class="form-control form-control-sm-form" placeholder="____-_____"
                                                        id="collegesy" name="collegesy" autocomplete="off">
                                                </td>
                                                <td class="p-1">
                                                    <select class="form-control form-control-sm select2"
                                                        name="collegeschooltype" id="collegeschooltype">
                                                        <option value=""></option>
                                                        <option value="Public">Public</option>
                                                        <option value="Private">Private</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-none">
                        <div class="card-header" style="font-size: 17px; color: #000000">
                            <i class="fas fa-layer-group" style="padding-right: 5px;"></i>MEDICAL INFORMATION
                        </div>
                        <div class="card-body" style="">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Vaccinated:</label>
                                </div>
                                <div class="col-md-1 form-group  mb-2">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="radio" id="input_vacc_type_yes" name="vacc" class="vacc"
                                            value="1">
                                        <label for="input_vacc_type_yes">Yes</label>
                                    </div>

                                </div>
                                <div class="col-md-1">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="radio" id="input_vacc_type_no" name="vacc" class="vacc"
                                            value="0">
                                        <label for="input_vacc_type_no">No</label>
                                    </div>
                                </div>
                            </div>
                            <span class="invalid-feedback pl-3" role="alert" id="vaccineinvalid">
                                <strong>In case of emergency is required</strong>
                            </span>
                            <div class="row vaccineform" hidden>
                                <div class="col-md-3 form-group">
                                    <label class="mb-1">Vaccine (1st Dose) <span class="text-danger">*<span></label>
                                    <select id="vacc_type_1st" name="vacc_type_1st" class="form-control"></select>
                                    <span class="invalid-feedback" role="alert">
                                        <strong id="vaccType1stError">Vaccine (1st Dose) is required</strong>
                                    </span>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label class="mb-1">1st Dose Date <span class="text-danger">*<span></label>
                                    <input type="date" id="dose_date_1st" name="dose_date_1st"
                                        class="form-control">
                                    <span class="invalid-feedback" role="alert">
                                        <strong id="doseDate1stError">1st Dose Date number is required</strong>
                                    </span>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label class="mb-1">Vaccine(2nd Dose)</label>
                                    <select id="vacc_type_2nd" name="vacc_type_2nd" class="form-control">

                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label class="mb-1">2nd Dose Date</label>
                                    <input type="date" id="dose_date_2nd" name="dose_date_2nd"
                                        class="form-control">
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
                                    <input type="date" id="dose_date_booster" name="dose_date_booster"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="row vaccineform" hidden>
                                <div class="col-md-3 form-group">
                                    <label>Vaccination Card # <span class="text-danger">*<span></label>
                                    <input id="vacc_card_id" class="form-control" name="vacc_card_id">
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
                                    <label class="mb-1"> Allergy/ies: (food, medication, insects, plants, animals
                                        etc.)</label>
                                    <input name="allergies" id="allergies" class="form-control"
                                        placeholder="Allergies">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group mb-2">
                                    <label class="mb-1"> Are there any factor that make it advisable for your child to
                                        follow a limited program of physical acitivity? (i.e asthma, recent surgery, heart
                                        condition and etc.) if yes, specify the ways in which you wish his/her program
                                        limited. (W/ Physician’s Medical Certificate) </label>
                                    <input name="med_prog" id="med_prog" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group mb-2">
                                    <label class="mb-1">Allergies to Medication</label>
                                    <input name="allergy_to_med" id="allergy_to_med" class="form-control"
                                        placeholder="Allergies to Medication">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group mb-2">
                                    <label class="mb-1">Medical History</label>
                                    <input name="med_his" id="med_his" class="form-control"
                                        placeholder="Medical History">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="mb-1"> Any additional information of which MDA should be aware
                                        concerning student’s health </label>
                                    <input id="other_med_info" class="form-control" name="other_med_info"
                                        placeholder="Other Medical Information">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card shadow-none">
                        <div class="card-header" style="font-size: 17px; color: #000000">
                            <i class="fas fa-layer-group" style="padding-right: 5px;"></i>REQUIREMENTS
                        </div>
                        <div class="card-body" style="">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td>Requirement Description</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody id="preregreqbody">
                                    {{-- @foreach (DB::table('preregistrationreqlist')->where('deleted', '0')->where('acadprogid', null)->where('isActive', '1')->get() as $item) --}}
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
                                                    <li>Old/Continuing Students don’t have to upload requirements ( unless
                                                        specified and requested by the Registrar’s Office). You can skip
                                                        this step by clicking next. </li>
                                                    <li>For New/Transferee students please submit a hard copy of the
                                                        requirements to the registrars office.</li>
                                                </ul>
                                        </td>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                    </div>

                    <div class="card shadow-none">
                        <div class="card-header" style="font-size: 17px; color: #000000">
                            <i class="fas fa-layer-group" style="padding-right: 5px;"></i> TERMS AND AGREEMENT
                        </div>
                        <div class="card-body" style="">
                            <div class=" w-100" style="overflow: auto;max-height: 363px;" id="terms">
                                {!! html_entity_decode(DB::table('schoolinfo')->first()->terms) !!}
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="icheck-success d-inline">
                                            <input class="form-control" type="checkbox" id="agree"
                                                name="agree" value="2" required>
                                            <label for="agree">I confirm that I have read, understand and agree to the
                                                above terms and agreement for enrollment of
                                                {{ DB::table('schoolinfo')->first()->schoolname }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer" id="footer_submit" hidden>
                <div style="float:right;">
                    {{-- <button type="button" class="btn btn-secondary" id="prevBtn" onclick="nextPrev(-1)" hidden>Previous</button> --}}
                    {{-- <button type="submit" class="btn btn-success" id="submit">Submit</button> --}}
                    <button type="button" class="btn btn-md" id="btn_submit" style="background-color: #0275d8">
                        <span class="text-white"><i class="fas fa-paper-plane"></i> Submit</span></button>
                </div>

            </div>

        </div>
        {{-- <div style="text-align:center;margin-top:40px;" class="stepHolder">
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
            </div> --}}
    </form>

    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#pssy").inputmask({
                mask: "9999-9999"
            });
            $("#gssy").inputmask({
                mask: "9999-9999"
            });
            $("#jhssy").inputmask({
                mask: "9999-9999"
            });
            $("#shssy").inputmask({
                mask: "9999-9999"
            });
            $("#collegesy").inputmask({
                mask: "9999-9999"
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            $('#gradelevelid').on('change', function() {
                var val = $(this).val();
                console.log(val, 'wewew')
                if (val >= 17 && val <= 21) {
                    $("#courseid").empty()
                    $("#courseid").append(`
                        <option value="">SELECT COURSE</option>
                        @foreach ($courses1 as $item)
                            <option value="{{ $item->id }}">{{ $item->courseDesc }}</option>
                        @endforeach
                    `)
                } else if (val >= 22 && val <= 26) {
                    $("#courseid").empty()
                    $("#courseid").append(`
                        <option value="">SELECT COURSE</option>
                        @foreach ($courses2 as $item)
                            <option value="{{ $item->id }}">{{ $item->courseDesc }}</option>
                        @endforeach
                    `)
                }

            })

            $('#nomiddlename').on('change', function() {
                var isChecked = $(this).prop('checked');
                $('#middle-name').prop('disabled',
                    isChecked); // Disable when checked, enable when unchecked
                if (isChecked) {
                    $('#middle-name').val(''); // Clear the input if the checkbox is checked
                }
            });


            $("#input_father_contact_new").inputmask({
                mask: "9999-999-9999"
            });
            $("#input_mother_contact_new").inputmask({
                mask: "9999-999-9999"
            });
            $("#input_guardian_contact_new").inputmask({
                mask: "9999-999-9999"
            });
            $("#last_school_no").inputmask({
                mask: "9999-999-9999"
            });

            $('#height, #weight').on('input', function() {
                // Get the values of height and weight
                var height = parseFloat($('#height').val());
                var weight = parseFloat($('#weight').val());

                // Check if both height and weight are valid and not empty
                if (!isNaN(height) && !isNaN(weight) && height > 0 && weight > 0) {
                    // Calculate BMI
                    var bmi = calculateBMI(height, weight);

                    // Display the calculated BMI in the result input field
                    $('#bmiResult').val(bmi.toFixed(2));
                } else {
                    // If any of the inputs is invalid or empty, clear the BMI result field
                    $('#bmiResult').val('');
                }
            });

            // Function to calculate BMI
            function calculateBMI(height, weight) {
                // BMI formula: weight (kg) / (height (m) * height (m))
                return weight / (height * height);
            }

            $('.namef').on('keypress', function(event) {
                const keyCode = event.which;
                const keyValue = String.fromCharCode(keyCode);

                // Check if the pressed key is a number or a special character
                if (/^\d+$/.test(keyValue) || /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~1234567890-]/.test(
                    keyValue)) {
                event.preventDefault();
            }
        });

        $('.gnamef').on('keypress', function(event) {
            const keyCode = event.which;
            const keyValue = String.fromCharCode(keyCode);

            // Check if the pressed key is a number or a special character
            if (/^\d+$/.test(keyValue) || /[`!@#$%^&*()_+\-=\[\]{};':"\\|.<>\/?~]/.test(keyValue)) {
                    event.preventDefault();
                }
            });
        });
    </script>

    <script>
        var enrollmentsetup = []
        var all_mol = []
        var withMOL = {!! json_encode($schoolInfo->withMOL) !!};
        console.log(withMOL);

        $(document).ready(function() {

            $(document).on('click', 'input[name="incase"]', function() {
                console.log('clicked incase');

                $('#tableParentsInfo tbody input.form-control').each(function() {
                    $(this).removeAttr('required')
                    $(this).removeClass('is-invalid')
                })
            })

            $('#btn_submit').attr('disabled', 'disabled')
            $(document).on('change', '#gradelevelid', function() {
                console.log('gradelevel', $(this).val());
                if ($(this).val() == "") {
                    return false
                }


                var temp_levelid = $(this).val()



                var temp_enrollmentsetup = enrollmentsetup.filter(x => x.id == $('#input_acadprog').val())

                console.log(temp_enrollmentsetup);
                if (withMOL == 1) {
                    $.ajax({
                        type: 'GET',
                        url: '/setup/modeoflearning/list',
                        data: {
                            syid: temp_enrollmentsetup[0].syid,
                            status: 1
                        },
                        success: function(data) {

                            all_mol = data
                            $('#mol_holder').empty()
                            var checkbox = ''
                            $.each(data, function(a, b) {
                                included = false
                                if (b.all) {
                                    included = true
                                } else {
                                    var check = b.gradelevel.filter(x => x.levelid ==
                                        temp_levelid).length
                                    if (check > 0) {
                                        included = true
                                    }
                                }

                                if (included) {
                                    checkbox +=
                                        '<div class="icheck-primary d-inline" dusk="withMOL' +
                                        b.id + '">' +
                                        '<input  type="radio" name="withMOL" id="withMOL' +
                                        b.id + '" value="' + b.id + '" required>' +
                                        '<label class="mr-5" for="withMOL' + b.id +
                                        '">' + b.description + '</label>' +
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
        $(document).ready(function() {


            function isFacebookApp() {
                // amef
                var ua = navigator.userAgent || navigator.vendor || window.opera;
                return (ua.indexOf("FBAN") > -1) || (ua.indexOf("FBAV") > -1);
            }
            if (isFacebookApp()) {
                $('#modalAlert').modal('show')
            }


            $(document).on('change', '#question', function() {
                $('#answerholder').empty();
                $('#answerholder').append('<label><strong>ANSWER</strong></label>')
                if ($(this).val() == 1) {
                    $('#answerholder').append(
                        '<input id="answer" name="answer" class="form-control" placeholder="Guardian\'s Contact Number" minlength="11" maxlength="11" data-inputmask-clearmaskonlostfocus="true">'
                    )
                } else if ($(this).val() == 2) {
                    $('#answerholder').append(
                        '<input id="answer" name="answer" class="form-control" placeholder="Mother\'s Name" minlength="11" maxlength="11" data-inputmask-clearmaskonlostfocus="true">' +
                        '<label class="pl-2 strong"><em>format: lastname, firstname</em></label>'
                    )
                } else if ($(this).val() == 4) {
                    $('#answerholder').append(
                        '<input type="date" id="answer" name="answer" class="form-control" >')
                }
            })



            $(document).on('click', '#recCodeButton', function() {
                $.ajax({
                    type: 'GET',
                    url: '/proccess/recoverycode',
                    data: {
                        a: $("#fname").val(),
                        b: $("#lname").val(),
                        c: 4,
                        d: $("#answer").val()
                    },
                    success: function(data) {
                        $('#regCode').text(data[0].regCode)
                        $('#sid').text(data[0].sid)
                    },
                    error: function(data) {},
                })
            })


        })
    </script>

    <script>
        const selectedLanguages = [];
        const selectedMediaMarketing = [];
        const selectedReasonToStudyHere = [];
        var finalGradeLevel = 0;
        var finalAssignCourse = 0;
        var finalAssignStrand = 0;
        $(document).ready(function() {
            var birthorder = '';

            $('.language').change(function() {
                const language = $(this).next().text().trim();
                if ($(this).prop('checked')) {
                    selectedLanguages.push(language);
                } else {
                    const index = selectedLanguages.indexOf(language);
                    if (index !== -1) {
                        selectedLanguages.splice(index, 1);
                    }
                }
                console.log(selectedLanguages);
                $('#languagelist').val(selectedLanguages.join(","))
            });

            $('.media').change(function() {
                const medias = $(this).val().trim();
                if ($(this).prop('checked')) {
                    selectedMediaMarketing.push(medias);
                } else {
                    const index = selectedMediaMarketing.indexOf(medias);
                    if (index !== -1) {
                        selectedMediaMarketing.splice(index, 1);
                    }
                }
                console.log(selectedMediaMarketing);
                $('#medialist').val(selectedMediaMarketing.join(","))
                console.log($('#medialist').val())
            });

            $('.reason').change(function() {
                const reasons = $(this).val().trim();
                if ($(this).prop('checked')) {
                    selectedReasonToStudyHere.push(reasons);
                } else {
                    const index = selectedReasonToStudyHere.indexOf(reasons);
                    if (index !== -1) {
                        selectedReasonToStudyHere.splice(index, 1);
                    }
                }
                console.log(selectedReasonToStudyHere);
                $('#reasonlist').val(selectedReasonToStudyHere.join(","))
                console.log($('#reasonlist').val())
            });

            $('.form-control').on('input', function() {
                $(this).removeClass('is-invalid');
            });




            $('input[name="birthorder"]').change(function() {
                const selectedValue = $(this).val();
                birthorder = selectedValue;
                console.log(birthorder);
            });

            get_enrollment_setup()

            //check duplication
            var with_dup = false

            $(document).on('input', 'input[name="first_name"] , input[name="last_name"]', function() {
                if ($('#studtype').val() == 1 || $('#studtype').val() == 2) {
                    $('#studtype').removeClass('is-invalid')
                    student_information()
                } else {
                    $('#studtype').addClass('is-invalid')
                    Toast.fire({
                        type: 'warning',
                        title: 'Please Select Pre-Registration Type'
                    })
                }
            })

            function student_information() {
                $.ajax({
                    type: 'GET',
                    url: '/student/enrollment/check/duplication',
                    data: {
                        firstname: $('input[name="first_name"]').val(),
                        lastname: $('input[name="last_name"]').val()
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            $('#dup_info').removeAttr('hidden')
                            $('#dup_info').text(data[0].message)
                            Toast.fire({
                                type: 'warning',
                                title: 'Student Already Exist'
                            })
                            var with_dup = true
                        } else {
                            $('#dup_info').attr('hidden', 'hidden')
                            var with_dup = false
                        }
                    }
                })
            }
            //check duplication

            var gradelevel = @json($gradelevel)
            // console.log('gradelevel', gradelevel);




            function get_enrollment_setup() {
                $.ajax({
                    type: 'GET',
                    url: '/enrollmentsetup/list',
                    data: {
                        active: 1
                    },
                    success: function(data) {
                        console.log('enrollment..,', data)
                        enrollmentsetup = data
                        $('#input_acadprog').empty()
                        $('#input_acadprog').append(
                            '<option value="">Select Available Enrollment</option>')
                        $.each(data, function(a, b) {
                            if (b.admission_studtype == 0 || b.admission_studtype == 2) {
                                $('#input_acadprog').append('<option value="' + b.id + '">[' + b
                                    .sydesc + '] - ' + b.progname + ' - ' + b.description +
                                    '</option>')
                            }
                        })
                    },
                })
            }

            $(document).on('change', '#input_acadprog', function() {
                console.log($('#input_acadprog').val());
                $('#tabb').removeAttr('hidden', 'hidden')
                $('#footer_submit').removeAttr('hidden', 'hidden')
                $('#preregreqbody').empty()
                if ($(this).val() == "") {
                    $('#footer_submit').attr('hidden', 'hidden')
                    $('#tabb').attr('hidden', 'hidden')
                    $('#nextBtn').attr('hidden', 'hidden')
                    return
                }

                var temp_data = enrollmentsetup.filter(x => x.id == $('#input_acadprog').val())

                // if(temp_data[0].acadprogid == 5 || temp_data[0].acadprogid == 6){
                //       $('#holder_semester').removeAttr('hidden')
                // }else{
                //       $('#holder_semester').attr('hidden','hidden')
                // }

                if (temp_data[0].acadprogid == 6 || temp_data[0].acadprogid == 2) {
                    $('#lrn_holder').attr('hidden', 'hidden')
                } else {
                    $('#lrn_holder').removeAttr('hidden')
                }

                if (temp_data[0].acadprogid == 2) {
                    $('#lastschoolatt').removeAttr('required')
                } else {
                    $('#lastschoolatt').attr('required', 'required')
                }

                var temp_gradelevel = gradelevel.filter(x => x.acadprogid == temp_data[0].acadprogid)
                console.log('temp_gradelevel');
                console.log('temp_gradelevel', temp_gradelevel);

                $('#gradelevelid').empty()
                $('#gradelevelid').append('<option value="">GRADE LEVEL</option>')

                $.each(temp_gradelevel, function(a, b) {
                    $('#gradelevelid').append('<option value="' + b.id + '">' + b.levelname +
                        '</option>')
                })

                $('.form-control').removeClass('is-invalid')
                $('input').removeClass('is-invalid')


                $('#input_setup_type').empty()
                $('#input_setup_type').append('<option value="' + temp_data[0].type +
                    '" selected="selected"></option>')
                $('#input_semid').empty()
                $('#input_semid').append('<option value="' + temp_data[0].semid +
                    '" selected="selected"></option>')
                $('#input_syid').empty()
                $('#input_syid').append('<option value="' + temp_data[0].syid +
                    '" selected="selected"></option>')

                $('#input_semid_label').val(temp_data[0].semester)
                $('#input_syid_label').val(temp_data[0].sydesc)
                $('#nextBtn').removeAttr('hidden')
            })

            $(function() {
                $('.select2').select2({
                    theme: 'bootstrap4'
                })
            })

            $(document).on('change', 'input[type="file"]', function() {
                var validImage = false;
                if (this.files[0].type == 'image/png' || this.files[0].type == 'image/jpeg' || this.files[0]
                    .type == 'image/jpg') {
                    validImage = true
                }
                if (this.files[0].size >= 5767168) {
                    alert("File is too big!");
                    this.value = "";
                } else if (!validImage) {
                    alert("File is not an image!");
                    this.value = "";
                }
            })

            var selectedSched = []
            var selectedSubject = []

            // $('select[name="gradelevelid"] option:not(:selected)').prop("disabled", true);

            $(document).on('change', '#collegesection', function() {
                $.ajax({
                    type: 'GET',
                    url: '/chairperson/scheduling/sectscshed/' + $(this).val(),
                    success: function(data) {
                        $('#schedtable').empty();
                        $('#schedtable').append(data);
                        $('#schedtable input').each(function() {
                            if (jQuery.inArray($(this).attr('data-value'),
                                    selectedSched) != -1) {
                                $(this).prop('checked', true)
                            }
                        })
                    }
                })
            })

            $(document).on('click', '#schedtable input[type="checkbox"]', function() {
                var subjExist = false;
                var selectInput = $(this);
                $('#schedtable tr').each(function() {
                    if (
                        jQuery.inArray($(this)[0].children[2].innerText, selectedSubject) != -1 &&
                        jQuery.inArray($(this).attr('data-value'), selectedSched) == -1
                    ) {
                        subjExist = true
                    }
                })
                if (!subjExist) {
                    if ($(this).prop('checked') == true) {
                        selectedSched.push($(this).attr('data-value'))
                        selectedSubject.push($(this).closest('tr')[0].children[2].innerText)
                    } else {
                        var removeItem = $(this).attr('data-value');
                        var removeItemSubj = $(this).closest('tr')[0].children[2].innerText;
                        selectedSched = jQuery.grep(selectedSched, function(value) {
                            return value != removeItem;
                        });

                        selectedSubject = jQuery.grep(selectedSubject, function(value) {
                            return value != removeItemSubj;
                        });
                    }
                } else {
                    selectInput.prop('checked', false)
                    alert('SUBJECT ALREADY SELECTED!')
                }
            })

            $(document).on('click', '#recID', function() {
                $('#recoverCode').modal();
            })

            $(document).on('click', '#schedulingdon', function() {
                if (selectedSched.length > 0) {
                    $('#schedtableModal').modal('hide')
                    $('#schedVal').val(selectedSched)
                } else {
                    $('#schedtableModal').modal('hide')
                }
            })

            $(document).on('click', '#showSched', function() {
                if ($('#courseid').val() != '') {
                    $('#schedtableModal').modal();
                    $.ajax({
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        url: '/collegesections?info=info&course=' + $('#courseid').val(),
                        success: function(data) {

                            $('#collegesection').empty()
                            $('#collegesection').append(
                                '<option value="">SELECT SECTION</option>')

                            if (data.length > 0) {

                                $.each(data, function(a, b) {
                                    $('#collegesection').append('<option value="' + b
                                        .id + '">' + b.sectionDesc + '</option>')
                                })
                            }
                        },
                    })
                    $('#courseid').removeClass('is-invalid')
                } else {
                    $('#courseid').addClass('is-invalid')
                }
            })

            $(document).on('click', '#validatestudinfoinput', function() {
                if ($('#studid').val() == "") {
                    $('#studid').addClass('is-invalid');
                } else {
                    $('#studid').removeClass('is-invalid');
                    $('#validatestudentinfo').modal();

                }
                $('#not_found_holder').attr('hidden', 'hidden')
            })

            $(document).on('click', '#proceedvalidate', function() {
                if ($('#studentdob').val() == "") {
                    $('#studentdob').addClass('is-invalid');
                } else {
                    $('#studentdob').removeClass('is-invalid');
                    getStudentInfo($('#studid'))
                }
            })

            $(document).on('change', '#gradelevelid', function() {
                console.log('helloworld', $(this).val());
                checkGradeLevel()
                if ($('#gradelevelid').val() != '') {
                    $.ajax({
                        type: 'GET',
                        url: '/superadmin/setup/document/list',
                        data: {
                            levelid: $(this).val()
                        },
                        success: function(data) {
                            console.log('this is requirements');
                            console.log(data);
                            $('#preregreqbody').empty()
                            $.each(data, function(a, b) {
                                var required = ''
                                var add = true;
                                if (b.isRequired == 1) {
                                    required = 'required'
                                } else {
                                    required = ''
                                }
                                if (b.doc_studtype != null) {
                                    if ($('#studtype').val() == 1 && b.doc_studtype !=
                                        'New') {
                                        add = false
                                    } else if ($('#studtype').val() == 2 && b
                                        .doc_studtype != 'Transferee') {
                                        add = false
                                    }
                                }
                                if (add && b.isActive == 1) {
                                    $('#preregreqbody').append(
                                        '<tr data-status="0"><td>' + b.description +
                                        '</td><td><input class="form-control form-controm-sm" type="file" name="req' +
                                        b.id + '" ' + required + '>' +
                                        '<span class="invalid-feedback" role="alert" style="display:' +
                                        (required != '' ? 'block' : 'none') +
                                        '"> ' +
                                        '<strong>' + b.description +
                                        ' is required</strong> ' +
                                        '</span></td></tr>')
                                }
                            })
                        }
                    })
                }
            })

            $('#input_poolingnumber').on('keyup', function() {
                var value = $(this).val().toUpperCase();
                $(this).val(value);

                if (value.length < 6) {
                    $('#input_poolingnumber').addClass('is-invalid');
                    get_enrollment_setup2()
                } else if (value.length == 6) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('verify.pooling') }}',
                        data: {
                            code: value
                        },
                        success: function(data) {
                            var studdata = data.data
                            // console.log('studinfo...', data);
                            Toast.fire({
                                type: data.status,
                                title: data.message
                            })

                            if (data.status == 'success' && studdata.final_assign_course &&
                                studdata.status == 2) {
                                console.log('studinfo...', studdata.fname);

                                if (studdata.acadprog_id == 6 || studdata.acadprog_id == 5) {
                                    $('#input_poolingnumber').removeClass('is-invalid')
                                        .addClass('is-valid');
                                    fetchEnrollmentList(studdata.acadprog_id, studdata
                                        .gradelevel_id, studdata);
                                }
                            } else if (data.status == 'success' && !studdata
                                .final_assign_course && studdata.status == 2) {
                                if (studdata.acadprog_id >= 2 || studdata.acadprog_id <= 4) {
                                    $('#input_poolingnumber').removeClass('is-invalid')
                                        .addClass('is-valid');
                                    fetchEnrollmentList(studdata.acadprog_id, studdata
                                        .gradelevel_id, studdata);
                                }
                            } else {
                                Toast.fire({
                                    type: data.status,
                                    title: data.message
                                })
                                $('#input_poolingnumber').removeClass('is-valid').addClass(
                                    'is-invalid');
                            }



                            // if ((data.status == 'success' && data.data.final_assign_course &&
                            //         data.data.acadprog_id == 6) || (data.status == 'success' &&
                            //         data.data.final_assign_course && data.data.acadprog_id == 5
                            //     ) || (data.status == 'success' && !data.data
                            //         .final_assign_course && data.data.acadprog_id >= 2 && data
                            //         .data.acadprog_id <= 4 && data.data.status == 2)) {
                            //     var student = data.data;
                            //     $('#input_poolingnumber').removeClass('is-invalid').addClass(
                            //         'is-valid');

                            //     fetchEnrollmentList(student.acadprog_id, student.gradelevel_id,
                            //         student);

                            // } else {
                            //     if ((!data.data.final_assign_course && data.data.acadprog_id ==
                            //             6) || !data.data.final_assign_course && data.data
                            //         .acadprog_id == 5) {
                            //         Toast.fire({
                            //             type: 'warning',
                            //             title: 'No Course or Strand Assigned. Please contact your Guidance!'
                            //         })
                            //     } else if (!data.data.final_assign_course && data.data.status ==
                            //         1 && data.data.acadprog_id >= 2 && data.data.acadprog_id <=
                            //         4) {
                            //         Toast.fire({
                            //             type: 'warning',
                            //             title: 'No Course or Strand Assigned. Please contact your Guidance!'
                            //         })
                            //     }
                            //     $('#input_poolingnumber').removeClass('is-valid').addClass(
                            //         'is-invalid');
                            // }
                        }
                    })
                } else {
                    $('#input_poolingnumber').addClass('is-invalid');
                    get_enrollment_setup2()
                }
            })

            function get_enrollment_setup2() {
                $.ajax({
                    type: 'GET',
                    url: '/enrollmentsetup/list',
                    data: {
                        active: 1
                    },
                    success: function(data) {
                        console.log('enrollment..,', data)
                        enrollmentsetup = data
                        $('#input_acadprog').empty()
                        $('#input_acadprog').append(
                            '<option value="">Select Available Enrollment</option>')
                        $.each(data, function(a, b) {
                            if (b.admission_studtype == 0 || b.admission_studtype == 2) {
                                $('#input_acadprog').append('<option value="' + b.id + '">[' + b
                                    .sydesc + '] - ' + b.progname + ' - ' + b.description +
                                    '</option>')
                            }
                        })

                        $('#input_acadprog').val("").change();
                        $('#gradelevelid').prop('disabled', false);
                        $('#input_acadprog').prop('disabled', false);
                        $('#courseid').prop('disabled', false);
                        $('#studstrand').prop('disabled', false);
                    },
                })
            }

            function fetchEnrollmentList(acadprog, levelid, student) {
                $.ajax({
                    type: 'GET',
                    url: '/enrollmentsetup/list',
                    data: {
                        active: 1
                    },
                    success: function(data) {
                        console.log('enrollment..,', data)
                        enrollmentsetup = data
                        $('#input_acadprog').empty()
                        $('#input_acadprog').append(
                            '<option value="">Select Available Enrollment</option>'
                        )

                        if (acadprog != null || acadprog > 0) {
                            $.each(data, function(a, b) {
                                if (b.admission_studtype ==
                                    0 ||
                                    b.admission_studtype ==
                                    2) {
                                    if (b.acadprogid ==
                                        acadprog
                                    ) {
                                        $('#input_acadprog')
                                            .append(
                                                '<option value="' +
                                                b.id +
                                                '">[' + b
                                                .sydesc +
                                                '] - ' + b
                                                .progname +
                                                ' - ' +
                                                b
                                                .description +
                                                '</option>')
                                        $('#input_acadprog')
                                            .val(b.id)
                                            .change();

                                        finalGradeLevel = student.gradelevel_id;
                                        $('#gradelevelid').val(student.gradelevel_id).change();
                                        if (student.acadprog_id == 6) {
                                            finalAssignCourse = student.final_assign_course;
                                            $('#courseid').val(student.final_assign_course)
                                                .change();
                                        } else if (student.acadprog_id == 5) {
                                            finalAssignCourse = student.final_assign_strand;
                                            $('#studstrand').val(student.final_assign_course)
                                                .change();
                                        } else {
                                            finalAssignCourse = student.final_assign_course;
                                        }


                                        $('#courseid').val(student.final_assign_course)
                                            .change();


                                        $('#first-name').val(student.fname ? student.fname
                                            .toUpperCase() :
                                            '').prop('readonly', student.fname);
                                        $('#middle-name').val(student.mname ? student.mname
                                            .toUpperCase() :
                                            '').prop('readonly', student.mname);
                                        $('#last_name').val(student.lname ? student.lname
                                            .toUpperCase() :
                                            '').prop('readonly', student.lname);
                                        $('#suffix').val(student.suffix ? student.suffix : '')
                                            .change().prop('readonly', student.suffix);
                                        $('#dobirth').val(student.dob ? student.dob : '')
                                            .prop('readonly', student.dob);
                                        $('#pobirth').val(student.pob ? student.pob : '')
                                            .prop('readonly', student.pob);
                                        $('#contact_number').val(student.contact_number ?
                                            student.contact_number : '').prop('readonly',
                                            student.contact_number);
                                        $('input[name=email]').val(student.email ?
                                            student.email : '').prop('readonly', student
                                            .email);

                                        $('#last_school_att').val(student.last_school_attended ?
                                            student.last_school_attended : '').prop(
                                            'readonly', student.last_school_attended).prop(
                                            'readonly', student.last_school_attended);

                                        $('#last_school_add').val(student
                                            .last_school_mailing_address ? student
                                            .last_school_mailing_address : '').prop(
                                            'readonly', student.last_school_mailing_address);


                                        if (student.gender != null) {
                                            if (student.gender == 'male') {
                                                $('#gender').val('MALE').change().prop(
                                                    'readonly', student.gender);
                                            } else if (student.gender == 'female') {
                                                $('#gender').val('FEMALE').change().prop(
                                                    'readonly', student.gender);
                                            }
                                        }

                                        if (student.prereg_status == null || student
                                            .prereg_status == '' ||
                                            student.prereg_status == 'New Student' || student
                                            .prereg_status == 'Continuing Student') {
                                            $('#studtype').val(1).change();
                                        } else if (student.prereg_status == 'Transferee') {
                                            $('#studtype').val(2).change();
                                        } else {
                                            $('#studtype').val(1).change();
                                        }

                                        $('#studtype').prop('disabled', true);
                                        $('#gradelevelid').prop('disabled', true);
                                        $('#courseid').prop('disabled', true);
                                        $('#studstrand').prop('disabled', true);
                                        $('#input_acadprog').prop('disabled', true);
                                        return
                                    }
                                }
                            })
                        }

                    },
                })
            }

            function checkGradeLevel() {
                console.log('helooooooooooooo');
                $('#studstrand').removeAttr('required')
                $('#strand-formgroup').attr('hidden', 'hidden')
                $('#courseid').removeAttr('required')
                $('#courseid').removeAttr('disabled')
                $('#courseid').val("").change()
                $('.course-formgroup').attr('hidden', 'hidden')
                $('#schedVal').val('1')
                $('input[name="withMOL"][type="radio"]').removeAttr('disabled')
                $('input[name="withMOL"][value=0]').prop('checked', true)
                if (parseInt($('#gradelevelid').val()) == 14 || parseInt($('#gradelevelid').val()) == 15) {
                    $('#strand-formgroup').removeAttr('hidden')
                    $('#studstrand').attr('required', 'required')
                } else if (parseInt($('#gradelevelid').val()) >= 17 && parseInt($('#gradelevelid').val()) <= 21) {
                    $('.course-formgroup').removeAttr('hidden')
                    $('#courseid').attr('required', 'required')
                    $('#schedVal').val('')
                } else if (parseInt($('#gradelevelid').val()) >= 22) {
                    $('.course-formgroup').removeAttr('hidden')
                    $('#courseid').attr('required', 'required')
                    $('#schedVal').val('')
                } else {
                    $('#studstrand').removeAttr('required')
                    $('#strand-formgroup').attr('hidden', 'hidden')
                }




                if (finalAssignCourse > 0) {
                    $('#courseid').val(finalAssignCourse).change();
                }

            }

            $(document).on('change', '#studtype', function() {
                //studhere
                // $('#first-name').val('')
                $('#gradelevelid').val('').change()
                $('#studstrand').removeAttr('required')
                $('#strand-formgroup').attr('hidden', 'hidden')
                $('#courseid').removeAttr('required')
                $('.course-formgroup').attr('hidden', 'hidden')

                if ($(this).val() == 3) {
                    $('#lastschoolattfromgroup').attr('hidden', 'hidden')
                    $('#studid-formgroup').removeAttr('hidden')
                    $('#studinfoprereg-formgroup').removeAttr('hidden')
                    $('#studid').attr('required', 'required')
                    $('#studinfoprereg').removeAttr('disabled', 'disabled')
                    $('#gradeLevelError').text(
                        'Student information is not yet validated. Click the "VALIDATE STUDENT INFORMATION" button to validate student information.'
                    )
                    $('input[name="first_name"]').removeAttr('readonly')
                    $('input[name="middle_name"]').removeAttr('readonly')
                    $('input[name="last_name"]').removeAttr('readonly')
                    $('input[name="dob"]').removeAttr('readonly')
                    $('input[name="suffix"]').removeAttr('readonly')
                    $('#gender').removeAttr('readonly')
                    // $('select[name="gradelevelid"] option:not(:selected)').prop("disabled", true);
                } else {
                    $('#lastschoolattfromgroup').removeAttr('hidden')
                    $('#studid-formgroup').attr('hidden', 'hidde')
                    $('#studid').removeAttr('required')
                    $('#gradelevelid').removeAttr('disabled')
                    $('#gradeLevelError').text('Grade level is required.')
                    $('#studinfoprereg-formgroup').attr('hidden', 'hidden')
                    $('#studinfoprereg').removeAttr('required')
                    $('#courseid').removeAttr('required')
                    $('.course-formgroup').attr('hidden', 'hidden')
                    // $('select[name="gradelevelid"] option:not(:selected)').prop("disabled", false);
                }

                if (finalGradeLevel > 0) {
                    $('#gradelevelid').val(finalGradeLevel).trigger('change');
                    // $('#gradelevelid').val(finalGradeLevel).change();
                }
            })


            $(document).on('change', '#studinfoprereg', function() {
                $('#studid').removeAttr('disabled')
                if ($(this).val() == 1) {
                    $('#studidlabel')[0].innerHTML = '<b>Student ID</b>';
                    $('#validatestudinfoinput').removeAttr('disabled')
                    $('#studidError').text('Student ID is required')

                } else if ($(this).val() == 2) {
                    $('#studidlabel')[0].innerHTML = '<b>Student LRN</b>'
                    $('#validatestudinfoinput').removeAttr('disabled')
                    $('#studidError').text('Student LRN is required')
                } else {
                    $('#studid').val('')
                    $('#studid').attr('disabled', 'disabled')
                    $('#validatestudinfoinput').attr('disabled', 'disabled')
                }
            })



            function getStudentInfo(idValue) {
                $.ajax({
                    type: 'GET',
                    url: '/preenrollment/get/student/information/' + idValue.val() + '/' + $('#studentdob')
                        .val() + '/1',
                    success: function(data) {

                        var temp_gradelevel = @json($gradelevel)

                        var strand = @json($strand)

                        if (data[0].status == 1 || enrollmentsetup[0].type == 2) {

                            Swal.fire({
                                type: 'success',
                                title: 'Student Found!',
                                showConfirmButton: false,
                                timer: 1500
                            })

                            // $('#gradelevelid').val(data[0].studinfo.levelid)

                            var grade_level_to_enroll = temp_gradelevel.findIndex(x => x.id == data[0]
                                .studinfo.levelid)
                            $('#gradelevelid').empty()
                            if (enrollmentsetup[0].type == 2) {
                                $('#gradelevelid').append('<option value="' + temp_gradelevel[
                                        grade_level_to_enroll].levelname +
                                    '" selected="selected">' + temp_gradelevel[
                                        grade_level_to_enroll].levelname + '</option>')
                            } else {
                                // $('select[name="gradelevelid"] option').prop("disabled", false);
                            }
                            if (temp_gradelevel[grade_level_to_enroll + 1].acadprogid != $(
                                    '#input_acadprog').val()) {
                                var suggested_acadprog = '';
                                if (temp_gradelevel[grade_level_to_enroll + 1].acadprogid == 2) {
                                    suggested_acadprog = 'Pre-school'
                                } else if (temp_gradelevel[grade_level_to_enroll + 1].acadprogid == 3) {
                                    suggested_acadprog = 'Grade School'
                                } else if (temp_gradelevel[grade_level_to_enroll + 1].acadprogid == 4) {
                                    suggested_acadprog = 'Junior High School'
                                } else if (temp_gradelevel[grade_level_to_enroll + 1].acadprogid == 5) {
                                    suggested_acadprog = 'Senior High School'
                                } else if (temp_gradelevel[grade_level_to_enroll + 1].acadprogid == 5) {
                                    suggested_acadprog = 'College'
                                }
                                $('#gradeLevelError').text(
                                    'Grade level to enroll was not found from the selected academic program. Please select ' +
                                    suggested_acadprog + ' from the academic program selection.')
                                $('#gradelevelid').addClass('is-invalid')
                            }

                            $('#studstrand').val(data[0].studinfo.strandid)

                            checkGradeLevel()

                            $('#courseid').val(data[0].studinfo.courseid).change()
                            $('#courseid').attr('disabled', 'disabled')

                            $('input[type="radio"][value="' + data[0].studinfo.mol + '"]').prop(
                                'checked', true)

                            if (data[0].studinfo.mol != null) {
                                $('input[type="radio"][name="withMOL"]').attr('disabled', 'disabled')
                            }



                            $('input[name="first_name"]').val(data[0].studinfo.firstname)
                            $('input[name="middle_name"]').val(data[0].studinfo.middlename)
                            $('input[name="last_name"]').val(data[0].studinfo.lastname)
                            $('input[name="dob"]').val(data[0].studinfo.dob)
                            $('input[name="suffix"]').val(data[0].studinfo.suffix)

                            $('input[name="suffix"]').val(data[0].studinfo.suffix)
                            $('input[name="suffix"]').val(data[0].studinfo.suffix)

                            if (data[0].studinfo.nationality != null && data[0].studinfo.nationality !=
                                0) {
                                $('#nationality').val(data[0].studinfo.nationality).change()
                            }

                            $('input[name="first_name"]').attr('readonly', 'readonly')
                            $('input[name="middle_name"]').attr('readonly', 'readonly')
                            $('input[name="last_name"]').attr('readonly', 'readonly')
                            $('input[name="dob"]').attr('readonly', 'readonly')
                            $('#nationality').attr('readonly', 'readonly')
                            $('#gender').attr('readonly', 'readonly')
                            $('input[name="suffix"').attr('readonly', 'readonly')
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

                            if (data[0].studinfo.ismothernum == 1) {
                                $("#mother").prop("checked", true)
                                $('#mother_contact_number').attr('required')
                            } else if (data[0].studinfo.isfathernum == 1) {
                                $("#father").prop("checked", true)
                                $('#father_contact_number').attr('required')
                            } else {
                                $("#guardian").prop("checked", true)
                                $('#guardian_contact_number').attr('required')
                            }
                            $('#validatestudentinfo').modal('hide');
                        } else if (data[0].status == 2) {
                            Swal.fire({
                                type: 'error',
                                title: 'Student is already Enrolled or Preenrolled!',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        } else {
                            $('#gradelevelid').val()
                            Swal.fire({
                                type: 'error',
                                title: 'Student Not Found',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            $('#not_found_holder').removeAttr('hidden')
                            if ($('input[name="first_name"]').val() != "" && $(
                                    'input[name="last_name"]').val() != "") {
                                $('#regForm').trigger("reset");
                            }
                        }
                    },
                })
            }

            $("#contact_number").inputmask({
                mask: "9999-999-9999"
            });
            $("#mother_contact_number").inputmask({
                mask: "9999-999-9999"
            });
            $("#father_contact_number").inputmask({
                mask: "9999-999-9999"
            });
            $("#guardian_contact_number").inputmask({
                mask: "9999-999-9999"
            });

            $(document).on('click', '#agree', function() {
                if ($(this).prop("checked") == true) {
                    $('#btn_submit').removeAttr('disabled')
                } else {
                    $('#btn_submit').attr('disabled', 'disabled')
                }
            })
        })

        $(document).on('click', 'input[name="vacc"]', function() {
            console.log($(this).val())
            if ($(this).val() == 0) {
                $('.vaccineform').attr('hidden', 'hidden')
                $('.is-invalid').removeClass('is-invalid')
            } else {
                $('.vaccineform').removeAttr('hidden')
            }
        })

        function validateField(fieldId, errorMessage) {
            const field = $(fieldId);
            if (fieldId && field.length) {
                if (field.val() === '' || field.val() == null) {
                    field.addClass('is-invalid');
                    Toast.fire({
                        type: 'error',
                        title: errorMessage
                    });
                    return false;
                } else {
                    field.removeClass('is-invalid');
                    return true;
                }
            } else {
                field.addClass('is-invalid');
                Toast.fire({
                    type: 'error',
                    title: errorMessage
                });
                return false;
            }
        }

        function validateField(fieldId, errorMessage) {
            const field = $(fieldId);
            if (fieldId) {
                console.log(field.val());
                if (field.val()) {
                    if (field.val().trim() === '' || field.val() == null) {
                        field.addClass('is-invalid');
                        Toast.fire({
                            type: 'error',
                            title: errorMessage
                        });
                        return false;
                    } else {
                        field.removeClass('is-invalid');
                        return true;
                    }

                } else {
                    field.addClass('is-invalid');
                    Toast.fire({
                        type: 'error',
                        title: errorMessage
                    });

                    return false;
                }
            } else {
                field.addClass('is-invalid');
                Toast.fire({
                    type: 'error',
                    title: errorMessage
                });

                return false;
            }
        }

        $(document).on('click', '#btn_submit', function(e) {
            $(this).prop('disabled', true)
            // console.log($('#studtype').val());
            // return
            e.preventDefault()
            var valid_data = true
            $('#lrn').css('color', 'red');
            var ismothernum = 0
            var isfathernum = 0
            var isguardiannum = 0


            $('#hidden_studtype').val($('#studtype').val());
            $('#hidden_gradelevelid').val($('#gradelevelid').val());
            $('#hidden_courseid').val($('#courseid').val());
            $('#hidden_studstrand').val($('#studstrand').val());
            $('#hidden_input_acadprog').val($('#input_acadprog').val());

            if ($('#guardian').prop('checked') == true) {
                isguardiannum = 1
            }
            if ($('#mother').prop('checked') == true) {
                ismothernum = 1
            }
            if ($('#father').prop('checked') == true) {
                isfathernum = 1
            }

            if ($('#otherlanguage').val()) {
                $('#languagelist').val($('#languagelist').val() + ',' + $('#otherlanguage').val())
            } else {
                $('#languagelist').val(selectedLanguages.join(","))
            }

            if ($('#othermedia').val()) {
                $('#medialist').val($('#medialist').val() + ',' + $('#othermedia').val())
            } else {
                $('#medialist').val(selectedMediaMarketing.join(","))
            }

            if ($('#otherreason').val()) {
                $('#reasonlist').val($('#reasonlist').val() + ',' + $('#otherreason').val())
            } else {
                $('#reasonlist').val(selectedReasonToStudyHere.join(","))
            }

            // console.log($('#languagelist').val())
            // console.log($('#medialist').val())
            // console.log($('#reasonlist').val())
            // return
            // $('#regForm').submit();


            var hasRequiredInput = $('#preregreqbody input[required]').length > 0;

            if (hasRequiredInput) {
                $('#preregreqbody input[required][type="file"]').each(function() {
                    if ($(this)[0].files.length === 0) {
                        $(this).addClass('is-invalid');
                        valid_data = false;
                        Toast.fire({
                            type: 'error',
                            title: `${$(this).siblings('.invalid-feedback').text().trim()}!`
                        });
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                // if (!valid_data) {
                //     let text = $(this)
                //     Toast.fire({
                //         type: 'error',
                //         title: 'File is Required!'
                //     });
                // }
            } else {
                valid_data = true;
            }

            // var hasRequiredInput = false;
            // var inputs = $('#preregreqbody').find('input')
            // console.log('hello')

            // $(inputs).each(function() {

            //     if ($(this).prop('required')) {
            //         hasRequiredInput = true;
            //         return false; // Exit the loop early if a required input is found
            //     }

            // });

            // if (hasRequiredInput) {
            //     var value = $(this).val();
            //     console.log("At least one input has the 'required' attribute.");
            //     if (value == '' || value == null) {
            //         // $('#preregreqbody input').css('border', '1px solid red')
            //         $('#preregreqbody input').addClass('is-invalid')
            //         Toast.fire({
            //             type: 'error',
            //             title: 'File is Required!'
            //         })
            //         valid_data = false
            //     } else {
            //         $('#preregreqbody input').removeClass('is-invalid')
            //     }
            // } else {
            //     console.log("No input has the 'required' attribute.");
            //     valid_data = true
            // }

            //PLACE OF BIRTH
            if (!validateField('#pobirth', "Place of birth is Required!")) {
                valid_data = false;
            }
            // if (!validateField('#input_father_fname_new', "Father's firstname is Required!")) {
            //     valid_data = false;
            // }



            if ($('input[name="vacc"]:checked').val() == 1) {

                $('#vacc_type_1st').prop('required', true);
                $('#dose_date_1st').prop('required', true);
                $('#vacc_card_id').prop('required', true);

                if ($('#vacc_type_1st').val() == '' || $('#contact_number').val() == null) {
                    $('#vacc_type_1st').addClass('is-invalid')
                    Toast.fire({
                        type: 'error',
                        title: 'Vaccine (1st Dose) is Required!'
                    })
                    valid_data = false
                } else {
                    $('#vacc_type_1st').removeClass('is-invalid')
                }

                if ($('#dose_date_1st').val() == '' || $('#contact_number').val() == null) {
                    $('#dose_date_1st').addClass('is-invalid')
                    Toast.fire({
                        type: 'error',
                        title: '1st Dose Date is Required!'
                    })
                    valid_data = false
                } else {
                    $('#dose_date_1st').removeClass('is-invalid')
                }

                if ($('#vacc_card_id').val() == '' || $('#contact_number').val() == null) {
                    $('#vacc_card_id').addClass('is-invalid')
                    Toast.fire({
                        type: 'error',
                        title: 'Vaccination Card # is Required!'
                    })
                    valid_data = false
                } else {
                    $('#vacc_card_id').removeClass('is-invalid')
                }

                // valid_data = false

            } else {
                $('#vacc_type_1st').removeClass('is-invalid')
                $('#vacc_type_1st').prop('required', false);
                $('#dose_date_1st').removeClass('is-invalid')
                $('#dose_date_1st').prop('required', false);
                $('#vacc_card_id').removeClass('is-invalid')
                $('#vacc_card_id').prop('required', false);
            }
            // if ($('#gender').val() == '' || $('#gender').val() == null) {
            //     $('#gender').addClass('is-invalid')
            //     Toast.fire({
            //         type: 'error',
            //         title: 'Gender is Required!'
            //     })
            //     valid_data = false
            // } else {
            //     $('input[name="gender"]').removeClass('is-invalid')
            // }

            if (!validateField('#gender', "Gender is Required!")) {
                valid_data = false;
            }
            if (!validateField('#religion', "Religion is Required!")) {
                valid_data = false;
            }

            var contactNumber = $('#contact_number').val().replace(/[-_\s]/g, '');
            console.log(contactNumber);


            if (!contactNumber) {
                $('#contact_number').addClass('is-invalid');
                Toast.fire({
                    type: 'error',
                    title: 'Contact Number is Required!'
                });
                valid_data = false;
            } else if (contactNumber.length !== 11) {
                $('#contact_number').addClass('is-invalid');
                Toast.fire({
                    type: 'error',
                    title: 'Contact Number length must be 11!'
                });
                valid_data = false;
            } else {
                $('#contact_number').removeClass('is-invalid');
            }


            // if ($('#father_name').val() == '' || $('#father_name').val() == null) {
            //     $('#father_name').addClass('is-invalid')
            //     Toast.fire({
            //         type: 'error',
            //         title: 'Father\'s name is Required!'
            //     })
            //     valid_data = false
            // } else {
            //     $('#father_name').removeClass('is-invalid')
            // }

            // if ($('#mother_name').val() == '' || $('#mother_name').val() == null) {
            //     $('#mother_name').addClass('is-invalid')
            //     Toast.fire({
            //         type: 'error',
            //         title: 'Mother\'s name is Required!'
            //     })
            //     valid_data = false
            // } else {
            //     $('#mother_name').removeClass('is-invalid')
            // }



            if ($('#province').val() == '' || $('#province').val() == null) {
                $('#province').addClass('is-invalid')
                Toast.fire({
                    type: 'error',
                    title: 'Province is Required!'
                })
                valid_data = false
            } else {
                $('#province').removeClass('is-invalid')
            }

            if ($('#city').val() == '' || $('#city').val() == null) {
                $('#city').addClass('is-invalid')
                Toast.fire({
                    type: 'error',
                    title: 'City is Required!'
                })
                valid_data = false
            } else {
                $('#city').removeClass('is-invalid')
            }

            if ($('#barangay').val() == '' || $('#barangay').val() == null) {
                $('#barangay').addClass('is-invalid')
                Toast.fire({
                    type: 'error',
                    title: 'Barangay is Required!'
                })
                valid_data = false
            } else {
                $('#barangay').removeClass('is-invalid')
            }

            if ($('#street').val() == '' || $('#street').val() == null) {
                $('#street').addClass('is-invalid')
                Toast.fire({
                    type: 'error',
                    title: 'Street is Required!'
                })
                valid_data = false
            } else {
                $('#street').removeClass('is-invalid')
            }

            if ($('#nationality').val() == '' || $('#nationality').val() == null) {
                $('#nationality').addClass('is-invalid')
                Toast.fire({
                    type: 'error',
                    title: 'Nationality is Required!'
                })
                valid_data = false
            } else {
                $('#nationality').removeClass('is-invalid')
            }





            // PARENTS INFORMATION
            if ($('input[name=incase]:checked').length == 0) {
                $('input[name=incase]').className += " is-invalid"
                $('#incaseinvalid').css('display', 'block')
                $('#incaseholder').css('border', 'solid 1px red')
                $('#incaseholder').css('padding', '10px 0')
                valid_data = false;
                Toast.fire({
                    type: 'warning',
                    title: 'Please select a contact person'
                })
            } else {
                $('#incaseholder').css('border', 'none')
                $('#incaseholder').css('padding', '10px 10px')

                $('#input_father_fname_new').removeClass('is-invalid')
                $('#mother_name').removeClass('is-invalid')
                $('#guardian_name').removeClass('is-invalid')
                $('#father_contact_number').removeClass('is-invalid')
                // $('#mother_contact_number').removeClass('is-invalid')
                // $('#guardian_contact_number').removeClass('is-invalid')
                // $('#father_name').removeAttr('required')
                // $('#mother_name').removeAttr('required')
                // $('#guardian_name').removeAttr('required')
                if ($('input[name="incase"]:checked').val() == 1) {
                    $('#input_father_contact_new').attr('required', 'required');
                    $('#input_father_fname_new').attr('required', 'required');

                    // if ($('#input_father_contact_new').val() == '' || $('#input_father_fname_new').val() == '') {
                    //     $('#input_father_contact_new').attr('required', 'required');
                    //     $('#input_father_fname_new').attr('required', 'required');
                    //     $('#incasetext').text('Fathers\'s information is not complete.')


                    //     if ($('#input_father_contact_new').val() == '' || $('#input_father_contact_new').val() == null) {
                    //         $('#input_father_contact_new').addClass('is-invalid')
                    //         Toast.fire({
                    //             type: 'error',
                    //             title: 'Father Contact Number Required!'
                    //         })
                    //         valid_data = false
                    //     } else {
                    //         $('#father_contact_number').removeClass('is-invalid')
                    //     }
                    //     if ($('#father_name').val() == '' || $('#father_name').val() == null) {
                    //         $('#father_name').addClass('is-invalid')
                    //         Toast.fire({
                    //             type: 'error',
                    //             title: 'Father Name Required!'
                    //         })
                    //         valid_data = false
                    //     } else {
                    //         $('#father_name').removeClass('is-invalid')
                    //     }
                    // }

                    if ($('#input_father_fname_new').val() == '' || $('#input_father_fname_new').val() == null) {
                        $('#input_father_fname_new').addClass('is-invalid')
                        Toast.fire({
                            type: 'error',
                            title: 'Father\'s firstname is Required!'
                        })
                        valid_data = false
                    } else {
                        $('#input_father_fname_new').removeClass('is-invalid')
                    }

                    // if ($('#input_father_mname_new').val() == '' || $('#input_father_mname_new').val() == null) {
                    //     $('#input_father_mname_new').addClass('is-invalid')
                    //     Toast.fire({
                    //         type: 'error',
                    //         title: 'Father\'s middlename is Required!'
                    //     })
                    //     valid_data = false
                    // } else {
                    //     $('#input_father_mname_new').removeClass('is-invalid')
                    // }

                    if ($('#input_father_lname_new').val() == '' || $('#input_father_lname_new').val() == null) {
                        $('#input_father_lname_new').addClass('is-invalid')
                        Toast.fire({
                            type: 'error',
                            title: 'Father\'s lastname is Required!'
                        })
                        valid_data = false
                    } else {
                        $('#input_father_lname_new').removeClass('is-invalid')
                    }

                    if ($('#fha').val() == '' || $('#fha').val() == null) {
                        $('#fha').addClass('is-invalid')
                        Toast.fire({
                            type: 'error',
                            title: 'Father\'s home is Required!'
                        })
                        valid_data = false
                    } else {
                        $('#fha').removeClass('is-invalid')
                    }


                    var fcontactNumber = $('#input_father_contact_new').val().replace(/[-_\s]/g, '');
                    console.log(fcontactNumber);

                    if (!fcontactNumber) {
                        $('#input_father_contact_new').addClass('is-invalid');
                        Toast.fire({
                            type: 'error',
                            title: 'Father\'s contact no. is Required!'
                        });
                        valid_data = false;
                    } else if (fcontactNumber.length !== 11) {
                        $('#input_father_contact_new').addClass('is-invalid');
                        Toast.fire({
                            type: 'error',
                            title: 'Father\'s contact no. length must be 11!'
                        });
                        valid_data = false;
                    } else {
                        $('#input_father_contact_new').removeClass('is-invalid');
                    }

                    // if ($('#input_father_contact_new').val() == '' || $('#input_father_contact_new').val() ==
                    //     null) {
                    //     $('#input_father_contact_new').addClass('is-invalid')
                    //     Toast.fire({
                    //         type: 'error',
                    //         title: 'Father\'s contact no. is Required!'
                    //     })
                    //     valid_data = false
                    // } else {

                    //     if()
                    //     $('#input_father_contact_new').removeClass('is-invalid')
                    // }



                } else if ($('input[name="incase"]:checked').val() == 2) {
                    // $('#mother_contact_number').attr('required', 'required');
                    // $('#mother_name').attr('required', 'required');
                    // if ($('#mother_contact_number').val() == '') {
                    //     $('#mother_contact_number').attr('required', 'required');
                    //     $('#mother_name').attr('required', 'required');
                    //     $('#incasetext').text('Mothers\'s information is not complete.')
                    //     if ($('#mother_contact_number').val() == '' || $('#mother_contact_number').val() == null) {
                    //         $('#mother_contact_number').addClass('is-invalid')
                    //         Toast.fire({
                    //             type: 'error',
                    //             title: 'Mother Contact Number Required!'
                    //         })
                    //         valid_data = false
                    //     } else {
                    //         $('#mother_contact_number').removeClass('is-invalid')
                    //     }
                    //     if ($('#mother_name').val() == '' || $('#mother_name').val() == null) {
                    //         $('#mother_name').addClass('is-invalid')
                    //         mother_contact_number
                    //         Toast.fire({
                    //             type: 'error',
                    //             title: 'Mother Name Required!'
                    //         })
                    //         valid_data = false
                    //     } else {
                    //         $('#mother_name').removeClass('is-invalid')
                    //     }
                    // }


                    if ($('#input_mother_fname_new').val() == '' || $('#input_mother_fname_new').val() == null) {
                        $('#input_mother_fname_new').addClass('is-invalid')
                        Toast.fire({
                            type: 'error',
                            title: 'Mother\'s firstname is Required!'
                        })
                        valid_data = false
                    } else {
                        $('#input_mother_fname_new').removeClass('is-invalid')
                    }

                    // if ($('#input_mother_mname_new').val() == '' || $('#input_mother_mname_new').val() == null) {
                    //     $('#input_mother_mname_new').addClass('is-invalid')
                    //     Toast.fire({
                    //         type: 'error',
                    //         title: 'Mother\'s middlename is Required!'
                    //     })
                    //     valid_data = false
                    // } else {
                    //     $('#input_mother_mname_new').removeClass('is-invalid')
                    // }

                    if ($('#input_mother_lname_new').val() == '' || $('#input_mother_lname_new').val() == null) {
                        $('#input_mother_lname_new').addClass('is-invalid')
                        Toast.fire({
                            type: 'error',
                            title: 'Mother\'s lastname is Required!'
                        })
                        valid_data = false
                    } else {
                        $('#input_mother_lname_new').removeClass('is-invalid')
                    }

                    if ($('#mha').val() == '' || $('#mha').val() == null) {
                        $('#mha').addClass('is-invalid')
                        Toast.fire({
                            type: 'error',
                            title: 'Mother\'s home is Required!'
                        })
                        valid_data = false
                    } else {
                        $('#mha').removeClass('is-invalid')
                    }

                    var mcontactNumber = $('#input_mother_contact_new').val().replace(/[-_\s]/g, '');
                    console.log(mcontactNumber);

                    if (!mcontactNumber) {
                        $('#input_mother_contact_new').addClass('is-invalid');
                        Toast.fire({
                            type: 'error',
                            title: 'Mother\'s contact no. is Required!'
                        });
                        valid_data = false;
                    } else if (mcontactNumber.length !== 11) {
                        $('#input_mother_contact_new').addClass('is-invalid');
                        Toast.fire({
                            type: 'error',
                            title: 'Mother\'s contact no. length must be 11!'
                        });
                        valid_data = false;
                    } else {
                        $('#input_mother_contact_new').removeClass('is-invalid');
                    }

                    // if ($('#input_mother_contact_new').val() == '' || $('#input_mother_contact_new').val() ==
                    //     null) {
                    //     $('#input_mother_contact_new').addClass('is-invalid')
                    //     Toast.fire({
                    //         type: 'error',
                    //         title: 'Mother\'s contact no. is Required!'
                    //     })
                    //     valid_data = false
                    // } else {
                    //     $('#input_mother_contact_new').removeClass('is-invalid')
                    // }

                    // Mainden Name
                    if ($('#input_mothermaidename_new').val() == '' || $('#input_mothermaidename_new').val() ==
                        null) {
                        $('#input_mothermaidename_new').addClass('is-invalid')
                        Toast.fire({
                            type: 'error',
                            title: 'Mother\'s maioen name is Required!'
                        })
                        valid_data = false
                    } else {
                        $('#input_mothermaidename_new').removeClass('is-invalid')
                    }



                } else if ($('input[name="incase"]:checked').val() == 3) {
                    // $('#guardian_contact_number').attr('required', 'required');
                    // $('#guardian_name').attr('required', 'required');
                    // if ($('#guardian_contact_number').val() == '') {
                    //     $('#guardian_contact_number').attr('required', 'required');
                    //     $('#guardian_name').attr('required', 'required');
                    //     $('#incasetext').text('Guardian\'s information is not complete.')
                    //     if ($('#guardian_contact_number').val() == '' || $('#guardian_contact_number').val() ==
                    //         null) {
                    //         $('#guardian_contact_number').addClass('is-invalid')
                    //         Toast.fire({
                    //             type: 'error',
                    //             title: 'Guardian Contact Number Required!'
                    //         })
                    //         valid_data = false
                    //     } else {
                    //         $('#guardian_contact_number').removeClass('is-invalid')
                    //     }
                    //     if ($('#guardian_name').val() == '' || $('#guardian_name').val() == null) {
                    //         $('#guardian_name').addClass('is-invalid')
                    //         Toast.fire({
                    //             type: 'error',
                    //             title: 'Guardian Name Required!'
                    //         })
                    //         valid_data = false
                    //     } else {
                    //         $('#guardian_name').removeClass('is-invalid')
                    //     }
                    // }


                    // Guardian
                    if ($('#input_guardian_fname_new').val() == '' || $('#input_guardian_fname_new').val() ==
                        null) {
                        $('#input_guardian_fname_new').addClass('is-invalid')
                        Toast.fire({
                            type: 'error',
                            title: 'Guardian\'s firstname is Required!'
                        })
                        valid_data = false
                    } else {
                        $('#input_guardian_fname_new').removeClass('is-invalid')
                    }

                    // if ($('#input_guardian_mname_new').val() == '' || $('#input_guardian_mname_new').val() ==
                    //     null) {
                    //     $('#input_guardian_mname_new').addClass('is-invalid')
                    //     Toast.fire({
                    //         type: 'error',
                    //         title: 'Guardian\'s middlename is Required!'
                    //     })
                    //     valid_data = false
                    // } else {
                    //     $('#input_guardian_mname_new').removeClass('is-invalid')
                    // }

                    if ($('#input_guardian_lname_new').val() == '' || $('#input_guardian_lname_new').val() ==
                        null) {
                        $('#input_guardian_lname_new').addClass('is-invalid')
                        Toast.fire({
                            type: 'error',
                            title: 'Guardian\'s lastname is Required!'
                        })
                        valid_data = false
                    } else {
                        $('#input_guardian_lname_new').removeClass('is-invalid')
                    }

                    if ($('#gha').val() == '' || $('#gha').val() == null) {
                        $('#gha').addClass('is-invalid')
                        Toast.fire({
                            type: 'error',
                            title: 'Guardian\'s home is Required!'
                        })
                        valid_data = false
                    } else {
                        $('#gha').removeClass('is-invalid')
                    }

                    if ($('#input_guardian_contact_new').val() == '' || $('#input_guardian_contact_new').val() ==
                        null) {
                        $('#input_guardian_contact_new').addClass('is-invalid')
                        Toast.fire({
                            type: 'error',
                            title: 'Guardian\'s contact no. is Required!'
                        })
                        valid_data = false
                    } else {
                        $('#input_guardian_contact_new').removeClass('is-invalid')
                    }
                }

                if (!valid_data) {
                    $('input[name=incase]').className += " is-invalid"
                    $('#incaseinvalid').css('display', 'block')
                    $('#incaseholder').css('border', 'solid 1px red')
                    $('#incaseholder').css('padding', '10px 0')

                } else {
                    $('#incaseinvalid').removeAttr('style')
                    $('#incaseholder').removeAttr('style')
                }
            }
            // PARENTS INFORMATION END 







            if ($('input[name="dob"]').val() == '' || $('input[name="dob"]').val() == null) {
                $('input[name="dob"]').addClass('is-invalid')
                Toast.fire({
                    type: 'error',
                    title: 'Date of birth is Required!'
                })
                valid_data = false
            } else {
                $('input[name="dob"]').removeClass('is-invalid')
            }

            if ($('input[name="last_name"]').val() == '' || $('input[name="last_name"]').val() == null) {
                $('input[name="last_name"]').addClass('is-invalid')

                Toast.fire({
                    type: 'error',
                    title: 'Last Name is Required!'
                })
                valid_data = false
            } else {
                $('input[name="last_name"]').removeClass('is-invalid')
            }

            if ($('input[name="first_name"]').val() == '' || $('input[name="first_name"]').val() == null) {
                $('input[name="first_name"]').addClass('is-invalid')

                $('input[name="first_name"]').css('border', '1px solid red!important')
                Toast.fire({
                    type: 'error',
                    title: 'First Name is Required!'
                })
                valid_data = false
            } else {
                $('input[name="first_name"]').removeClass('is-invalid')
            }

            if (parseInt($('#gradelevelid').val()) == 14 || parseInt($('#gradelevelid').val()) == 15) {
                $('#strand-formgroup').removeAttr('hidden')
                $('#studstrand').attr('required', 'required')

                if ($('#studstrand').val() == '' || $('#studstrand').val() == null) {
                    $('#studstrand').addClass('is-invalid')
                    Toast.fire({
                        type: 'error',
                        title: 'Strand is Required!'
                    })
                    valid_data = false
                } else {
                    $('#studstrand').removeClass('is-invalid')
                }
            } else if (parseInt($('#gradelevelid').val()) >= 17 && parseInt($('#gradelevelid').val()) <= 21) {
                $('.course-formgroup').removeAttr('hidden')
                $('#courseid').attr('required', 'required')
                $('#schedVal').val('')

                if ($('#courseid').val() == '' || $('#courseid').val() == null) {

                    $('#courseid').addClass('is-invalid')
                    Toast.fire({
                        type: 'error',
                        title: 'Course is Required!'
                    })
                    valid_data = false
                } else {
                    $('#courseid').removeClass('is-invalid')
                }
            } else {
                $('#studstrand').removeAttr('required')
                $('#strand-formgroup').attr('hidden', 'hidden')
            }

            if ($('#studtype').val() == 1 || $('#studtype').val() == 2) {

                // if ($('#lastschoolatt').val() == '' || $('#lastschoolatt').val() == null) {

                //     $('#lastschoolatt').addClass('is-invalid')
                //     Toast.fire({
                //         type: 'error',
                //         title: 'Last School Attended is Required!'
                //     })
                //     valid_data = false
                // } else {
                //     $('#lastschoolatt').removeClass('is-invalid')
                // }

            }


            if ($('#gradelevelid').val() == '' || $('#gradelevelid').val() == null) {
                $('#gradelevelid').addClass('is-invalid')
                Toast.fire({
                    type: 'error',
                    title: 'No Grade Level selected!'
                })
                valid_data = false
            } else {
                $('#gradelevelid').removeClass('is-invalid')
            }

            if ($('#studtype').val() == '' || $('#studtype').val() == null) {
                $('#studtype').addClass('is-invalid')
                Toast.fire({
                    type: 'error',
                    title: 'No Pre-registration Type selected!'
                })
                valid_data = false

            } else {
                $('#studtype').removeClass('is-invalid')
            }


            if (valid_data) {
                $('#regForm').submit()
            } else {
                $(this).prop('disabled', false)
            }
        })

        function clearInput() {

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
            $('#studid-formgroup').attr('hidden', 'hidden')
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
            $('#strand-formgroup').attr('hidden', 'hidden')
            $('#courseid').removeAttr('required')
            $('.course-formgroup').attr('hidden', 'hidden')

            with_dup = false

        }
    </script>

    <script>
        var vaccine_list = []
        get_vaccine()

        function get_vaccine() {
            $.ajax({
                type: 'GET',
                url: '/setup/vaccinetype/list',
                success: function(data) {
                    vaccine_list = data
                    $('#vacc_type_1st').empty()
                    $('#vacc_type_1st').append('<option value="">Vaccine (1st Dose)</option>')
                    $('#vacc_type_1st').append(
                        '<option value="create"><i class="fas fa-plus"></i>Create Vaccine Type</option>')
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
