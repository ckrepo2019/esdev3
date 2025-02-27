@php
    $extends = 'tesda.layouts.app2';

    $check_refid = DB::table('usertype')->where('deleted', 0)->where('id', Session::get('currentPortal'))->first();
    // dd($check_refid);

    if (Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15) {
        $extends = 'finance.layouts.app';
    }
    // dd(Session::get('currentPortal'));
@endphp

@extends($extends)

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    {{-- <link rel="stylesheet" id="css-main" href="{{ asset('/css/oneui.css') }}"> --}}
    <style>
        /* .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: unset !important;
        } */

        .late-enrolled {
            border-color: #58715f;
            color: #58715f;
            background-color: transparent;
            border-style: solid;
            border-width: 1px;
        }

        .transferred-out {
            color: #fd7e14;
            border-color: #fd7e14;
            background-color: transparent;
            border-style: solid;
            border-width: 1px;
        }

        .scrollable-content {
            max-height: calc(100vh - 240px);
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .scrollable-content::-webkit-scrollbar {
            display: none;
        }
    </style>

    <style>
        .modal-header {
            background-color: #f8f9fa;
        }

        .modal-title {
            font-weight: bold;
        }

        .img-fluid {
            border: 3px solid #dcdcdc;
        }

        .modal-footer {
            justify-content: center;
        }

        .modal-footer .btn-primary {
            width: 120px;
        }

        .text-danger {
            font-weight: bold;
        }

        .form-label {
            font-weight: bold;
        }

        /* #enrollmentForm select,
        #enrollmentForm input {
            font-size: 0.9rem;
            padding: 0.4rem 0.6rem;
        } */

        #enrollmentForm input[disabled] {
            background-color: #e9ecef;
            color: #6c757d;
        }

        #studentList {
            font-size: 12px !important;
        }
        .card {
            box-shadow: 0 0 1px rgba(0,0,0,0.08), 0 1px 1px rgba(0,0,0,0.12) !important;
            border: unset !important;
        }
        
    </style>

    <style>
        /* Spinner styles */
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            /* Light border */
            border-top: 4px solid #3498db;
            /* Blue border on top */
            border-radius: 50%;
            /* Circular shape */
            width: 40px;
            /* Width of spinner */
            height: 40px;
            /* Height of spinner */
            animation: spin 1s linear infinite;
            /* Infinite spinning */
        }

        /* Keyframes for spinning effect */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection

@php
    $courses = DB::table('tesda_courses')
        ->where('tesda_course_series.deleted', 0)
        ->where('tesda_course_series.active', 1)
        ->where('tesda_courses.deleted', 0)
        ->join('tesda_course_type', 'tesda_courses.course_type', '=', 'tesda_course_type.id')
        ->join('tesda_course_series', 'tesda_courses.id', '=', 'tesda_course_series.course_id')
        ->select(
            'tesda_courses.id',
            'tesda_courses.course_name',
            'tesda_courses.course_code',
            'tesda_course_type.description',
            'tesda_courses.course_duration',
        )
        ->get();

    $studentStatus = DB::table('studentstatus')->get();

    // dd($courses);

@endphp

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @if (Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15)
                        <h1> <i class="nav-icon fas fa-cog"></i> Tesda Students</h1>
                    @else
                        <h1> <i class="nav-icon fas fa-cog"></i> Enroll Student</h1>
                    @endif
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Enrollment</li>
                    </ol>
                </div>

            </div>
        </div>
    </section>

    <div class="modal fade" id="studentInfoTab" tabindex="-1" aria-labelledby="studentInfoTab" aria-hidden="true"
        data-backdrop="static">
        <div class="modal-dialog modal-xl mw-100 mh-100" style="width: 90vw; height: 93vh;">
            <div class="modal-content">
                <div class="card">
                    <!-- Card Header for Student Name -->
                    <div class="card-header d-flex align-items-center">
                        <h5 class="mb-0" id="studentName">Azuncion, Ricardo John Michael L.</h5>
                        <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Profile Section -->
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card shadow">
                                            {{-- <img id="studentPhoto" src="{{ asset('avatar/S(F) 1.png') }}" alt="Student Photo"
                                                class="card-img-top"
                                                style="height: 240px; object-fit: cover; object-position: center; border-radius: 10%; padding:10px"> --}}
                                            <div class="card-body">
                                                <div class="text-center" id="image_holder">
                                                </div>
                                                <table class="table table-sm" style="font-size:12px">
                                                    <tr>
                                                        <th>ID Number</th>
                                                        <td id="studentIdNumber2">[Not set]</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Specialization</th>
                                                        <td id="specializationText2">[Not set]</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Student Status</th>
                                                        <td id="studentStatus2">
                                                            {{ $studentStatus->where('id', 0)->first()->description }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="card-footer">
                                                <div class="text-center">
                                                    <button data-toggle="modal" data-target="#image-modal" class="btn btn-success btn-sm">Update Profile
                                                        Picture</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- <div class="col-md-12">
                                        <div class="card shadow">
                                            <div class="card-body box-profile">
                                                <div class="text-center" id="image_holder">
                                                </div>
                                                <p></p>
                                                <ul class="list-group list-group-unbordered mb-3">
                                                    <li class="list-group-item">
                                                        <b>Student ID</b> <a class="float-right" id="label_sid"></a>
                                                    </li>
                                                </ul>
                                                <button data-toggle="modal" data-target="#image-modal"
                                                    class="btn btn-primary btn-block mt-2"><b>Update Profile Picture</b></button>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>


                            <!-- Tabbing Card Section -->
                            <div class="col-md-9">
                                <div class="card">
                                    <!-- Tabs Header -->
                                    <div class="card-header p-0">

                                    </div>

                                    <!-- Tabs Content -->
                                    <div class="card-body">
                                        <!-- Student Information Tab -->
                                        <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="student-info-tab" data-toggle="tab"
                                                    href="#student-info" role="tab" aria-controls="student-info"
                                                    aria-selected="true">Student Information</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="enrollment-history-tab" data-toggle="tab"
                                                    href="#enrollment-history" role="tab"
                                                    aria-controls="enrollment-history" aria-selected="false">Enrollment
                                                    History</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="registration-tab" data-id="" data-toggle="tab"
                                                    href="#registration" role="tab" aria-controls="registration"
                                                    aria-selected="false">Certificate of
                                                    Registration</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="student-info" role="tabpanel"
                                                aria-labelledby="student-info-tab">
                                                <h4>Student Information</h4>
                                                <div class="scrollable-content">
                                                    <form style="font-size: 12px;">
                                                        <div class="row mb-3">
                                                            <div class="col-md-4">
                                                                <label for="studentIdNo2">Student ID No.</label>
                                                                <input class="form-control form-control-sm" readonly
                                                                    id="sid2" placeholder="Student ID No.">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="specializationSelect2">Specialization</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="specializationInput2"
                                                                    placeholder="Enter Specialization" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-3">
                                                                <label for="fName" class="form-label-sm">First
                                                                    Name</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="fName" placeholder="Enter First Name"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="middleName" class="form-label-sm">Middle
                                                                    Name</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="mName" placeholder="Enter Middle Name"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="lastName" class="form-label-sm">Last
                                                                    Name</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="lName" placeholder="Enter Last Name" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="suffix" class="form-label-sm">Suffix</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="studsuffix" placeholder="Enter Suffix" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-3">
                                                                <label for="dateOfBirth" class="form-label-sm">Date of
                                                                    Birth</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="dob" placeholder="Enter Date of Birth"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="placeOfBirth" class="form-label-sm">Place of
                                                                    Birth</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="pob" placeholder="Enter Place of Birth"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="gender2" class="form-label-sm">Gender</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="gender2" placeholder="Enter Gender" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="age" class="form-label-sm">Age</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="age2" placeholder="Enter Age" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-4">
                                                                <label for="mobileNumber" class="form-label-sm">Mobile
                                                                    Number</label>
                                                                <input type="number" class="form-control form-control-sm"
                                                                    id="mobileNumber2" placeholder="Enter Mobile Number"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <label for="emailAddress" class="form-label-sm">Email
                                                                    Address</label>
                                                                <input type="email" class="form-control form-control-sm"
                                                                    id="emailAddress2" placeholder="Enter Email Address"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-4">
                                                                <label for="religion"
                                                                    class="form-label-sm">Religion</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="studReligion2" placeholder="Enter Religion"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="nationality"
                                                                    class="form-label-sm">Nationality</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="nationality2" placeholder="Enter Nationality"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="street">Street</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="street2" placeholder="Enter Street" readonly>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="barangay">Barangay</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="barangay2" placeholder="Enter Barangay" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="City">City/Municipality</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="City2" placeholder="Enter City/Municipality"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="province ">Province </label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="province2" placeholder="Enter Province" readonly>
                                                            </div>
                                                        </div>

                                                        <hr style="border-color: black" class="mt-3 md-3">
                                                        <h5 class="mb-4 mt-3" style="color: black">Parents / Guardian
                                                            Information</h5>
                                                        <div class="row mb-3">
                                                            <div class="col-md-4">
                                                                <label for="fatherFirstName">Father's First Name</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="fatherFirstName2"
                                                                    placeholder="Enter Father's First Name" readonly>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="fatherMiddleName">Father's Middle Name</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="fatherMiddleName2"
                                                                    placeholder="Enter Father's Middle Name" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="fatherLastName">Father's Last Name</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="fatherLastName2"
                                                                    placeholder="Enter Father's Last Name" readonly>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label for="fatherSuffix">Suffix</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="fatherSuffix2" placeholder="Enter Suffix"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-md-3">
                                                                <label for="fContactNumber">Contact Number</label>
                                                                <input type="number" class="form-control form-control-sm"
                                                                    id="fContactNumber2"
                                                                    placeholder="Enter Contact Number" readonly>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="fOccupation">Occupation</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="fOccupation2" placeholder="Enter Occupation"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <label for="fHAE">High Educational Attainment</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="fHAE2"
                                                                    placeholder="Enter High Educational Attainment"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="fEthnicity">Ethnicity</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="fEthnicity2" placeholder="Enter Ethnicity"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-4">
                                                                <label for="motherFirstName">Mother's First Name</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="motherFirstName2"
                                                                    placeholder="Enter Mother's First Name" readonly>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="motherMiddleName">Mother's Middle Name</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="motherMiddleName2"
                                                                    placeholder="Enter Mother's Middle Name" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="motherLastName">Mother's Last Name</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="motherLastName2"
                                                                    placeholder="Enter Mother's Last Name" readonly>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label for="motherSuffix">Suffix</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="motherSuffix2" placeholder="Enter Suffix"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-md-3">
                                                                <label for="mContactNumber">Contact Number</label>
                                                                <input type="number" class="form-control form-control-sm"
                                                                    id="mContactNumber2"
                                                                    placeholder="Enter Contact Number" readonly>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="mOccupation">Occupation</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="mOccupation2" placeholder="Enter Occupation"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <label for="mHAE">High Educational Attainment</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="mHAE2"
                                                                    placeholder="Enter High Educational Attainment"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="mEthnicity">Ethnicity</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="mEthnicity2" placeholder="Enter Ethnicity"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <hr style="border-color: black" class="mt-3 md-3">
                                                        <h5 class="mb-4 mt-3">Guardian Information</h5>
                                                        <div class="row mb-3">
                                                            <div class="col-md-4">
                                                                <label for="guardianFirstName">Guardian's First
                                                                    Name</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="guardianFirstName2"
                                                                    placeholder="Enter Guardian's First Name" readonly>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="guardianMiddleName">Guardian's Middle
                                                                    Name</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="guardianMiddleName2"
                                                                    placeholder="Enter Guardian's Middle Name" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="guardianLastName">Guardian's Last Name</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="guardianLastName2"
                                                                    placeholder="Enter Guardian's Last Name" readonly>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label for="guardianSuffix">Suffix</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="guardianSuffix2" placeholder="Enter Suffix"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-md-3">
                                                                <label for="gContactNumber">Contact Number</label>
                                                                <input type="number" class="form-control form-control-sm"
                                                                    id="gContactNumber2"
                                                                    placeholder="Enter Contact Number" readonly>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="gOccupation">Occupation</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="gOccupation2" placeholder="Enter Occupation"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <label for="gHAE">High Educational Attainment</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="gHAE2"
                                                                    placeholder="Enter High Educational Attainment"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="gEthnicity">Ethnicity</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="gEthnicity"2 placeholder="Enter Ethnicity"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label style="font-size: 13px !important"
                                                                    class="text-danger mb-0"><span
                                                                        class="text-danger">*</span><b>In case of
                                                                        emergency ( Recipient for
                                                                        News, Announcement and School
                                                                        Information)</b></label>
                                                            </div>
                                                            <div class="col-md-4 pt-1">
                                                                <div class="icheck-success d-inline">
                                                                    <input class="form-control" type="radio"
                                                                        id="father2" name="incase2" value="1"
                                                                        required>
                                                                    <label for="father2">Father
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 pt-1">
                                                                <div class="icheck-success d-inline">
                                                                    <input class="form-control" type="radio"
                                                                        id="mother2" name="incase2" value="2"
                                                                        required>
                                                                    <label for="mother2">Mother
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 pt-1">
                                                                <div class="icheck-success d-inline">
                                                                    <input class="form-control" type="radio"
                                                                        id="guardian2" name="incase2" value="3"
                                                                        required>
                                                                    <label for="guardian2">Guardian
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr style="border-color: black" class="mt-3 md-3">
                                                        <h5 class="mb-4 mt-3">Educational Information</h5>
                                                        <div class="row mb-3" style="font-size: 12px">
                                                            <div class="col-md-3">
                                                                <label for="lastSchoolAttended">Last School
                                                                    Attended</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="lastSchoolAttended2"
                                                                    placeholder="Enter Last School Attended" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="lastGradeLevelCompleted">Last Grade Level
                                                                    Completed</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="lastGradeLevelCompleted2"
                                                                    placeholder="Enter Last Grade Level Completed"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="schoolContactNo">School's Contact No.</label>
                                                                <input type="number" class="form-control form-control-sm"
                                                                    id="schoolContactNo2"
                                                                    placeholder="Enter School's Contact No." readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="schoolMailingAddress">Last School Mailing
                                                                    Address</label>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="schoolMailingAddress2"
                                                                    placeholder="Enter Last School Mailing Address"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-3">
                                                                <label for="schoolTypeLabel"></label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="schoolName">School Name</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="schoolYearGraduated">School Year
                                                                    Graduated</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="schoolType">School Type</label>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-3">
                                                                <label for="preSchool">Pre-School</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="preSchoolName2"
                                                                    placeholder="Enter Pre-School Name" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="preSchoolYearGraduated2"
                                                                    placeholder="Enter Pre-School Year Graduated" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="preSchoolType2"
                                                                    placeholder="Enter Pre-School Type" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-3">
                                                                <label for="gradeSchool">Grade School</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="gradeSchoolName2"
                                                                    placeholder="Enter Grade School Name" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="gradeSchoolYearGraduated2"
                                                                    placeholder="Enter Grade School Year Graduated"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="gradeSchoolType2"
                                                                    placeholder="Enter Grade School Type" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-3">
                                                                <label for="juniorHighSchool">Junior High School</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="juniorHighSchoolName2"
                                                                    placeholder="Enter Junior High School Name" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="juniorHighSchoolYearGraduated2"
                                                                    placeholder="Enter Junior High School Year Graduated"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="juniorHighSchoolType2"
                                                                    placeholder="Enter Junior High School Type" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-3">
                                                                <label for="seniorHighSchool">Senior High School</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="seniorHighSchoolName2"
                                                                    placeholder="Enter Senior High School Name" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="seniorHighSchoolYearGraduated2"
                                                                    placeholder="Enter Senior High School Year Graduated"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="seniorHighSchoolType2"
                                                                    placeholder="Enter Senior High School Type" readonly>
                                                            </div>
                                                        </div>
                                                        <hr style="border-color: black" class="mt-3 md-3">
                                                        <h5 class="mb-4 mt-3">Medical Information</h5>
                                                        <div class="row mb-3">
                                                            <div class="col-md-2">
                                                                <label for="Height2">Height (Meters)</label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="Height2" placeholder="Enter Height (Meters)"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="Weight">Weight (kgs)</label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="Weight2" placeholder="Enter Weight (kgs)"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-4">
                                                                <label for="otherMed">Any Current Medications, Specify
                                                                </label>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="otherMed2"
                                                                    placeholder="Enter Medications, Specify" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-2">
                                                                <label for="anyAllergies">Any Allergies</label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="anyAllergies2" placeholder="Enter Any Allergies"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="medAllergies"> Medications to
                                                                    allergies</label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="medAllergies2"
                                                                    placeholder="Enter Medications to allergies" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-2">
                                                                <label for="medHistory">Medical History</label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="medHistory2" placeholder="Enter Medical History"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="otherMedInfo">Other Medical
                                                                    Information</label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control form-control-sm"
                                                                    id="otherMedInfo2"
                                                                    placeholder="Enter Other Medical Information" readonly>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="enrollment-history" role="tabpanel"
                                                aria-labelledby="enrollment-history-tab">
                                                <h4>Enrollment History</h4>
                                                <!-- Enrollment history details -->
                                            </div>

                                            <!-- Certificate of Registration Tab -->
                                            <div class="tab-pane fade" id="registration" role="tabpanel"
                                                aria-labelledby="registration-tab">
                                                <!-- Registration details -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="studentInfoModalLabel"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-xl mw-100 mh-100" style="width: 90vw; height: 93vh;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentInfoModalLabel">Student Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card">
                                <img id="studentPhoto" src="{{ asset('avatar/S(F) 1.png') }}" alt="Student Photo"
                                    class="card-img-top"
                                    style="height: 240px; object-fit: cover; object-position: center; border-radius: 10%; padding:10px">
                                <div class="card-body">
                                    <table class="table table-sm" style="font-size:12px">
                                        <tr>
                                            <th>ID Number</th>
                                            <td id="studentIdNumber">[Not set]</td>
                                        </tr>
                                        <tr>
                                            <th>Specialization</th>
                                            <td id="specializationText">[Not set]</td>
                                        </tr>
                                        <tr>
                                            <th>Student Status</th>
                                            <td id="studentStatus">
                                                {{ DB::table('studentstatus')->where('id', 0)->first()->description }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    <div class="text-center">
                                        <button id="updateCourse" class="btn btn-success btn-sm">Update Profile
                                            Picture</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="scrollable-content">
                                <form id="newStudForm" style="font-size: 12px;">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="studentIdNo">Student ID No.</label>
                                            <input type="number" class="form-control form-control-sm" id="studentIdNo"
                                                placeholder="Student ID No." disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="specializationSelect">Specialization</label>
                                            <select class="form-control select2 specialization" style="width: 100%;"
                                                id="specializationSelect">
                                                <option value="">Select</option>
                                                @foreach ($courses as $spec)
                                                    <option value="{{ $spec->id }}">{{ $spec->course_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="firstName" class="form-label-sm">First Name</label>
                                            <input type="text" class="form-control form-control-sm" id="firstName"
                                                placeholder="Enter First Name" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="middleName" class="form-label-sm">Middle Name</label>
                                            <input type="text" class="form-control form-control-sm" id="middleName"
                                                placeholder="Enter Middle Name">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="lastName" class="form-label-sm">Last Name</label>
                                            <input type="text" class="form-control form-control-sm" id="lastName"
                                                placeholder="Enter Last Name" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="suffix" class="form-label-sm">Suffix</label>
                                            <input type="text" class="form-control form-control-sm" id="suffix"
                                                placeholder="Enter Suffix">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="dateOfBirth" class="form-label-sm">Date of Birth</label>
                                            <input type="date" class="form-control form-control-sm" id="dateOfBirth"
                                                placeholder="Enter Date of Birth" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="placeOfBirth" class="form-label-sm">Place of Birth</label>
                                            <input type="text" class="form-control form-control-sm" id="placeOfBirth"
                                                placeholder="Enter Place of Birth">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="gender" class="form-label-sm">Gender</label>
                                            <select class="form-select select2" id="gender" style="width: 100%;"
                                                required>
                                                <option value="">Select Gender</option>
                                                <option value="Female">Female</option>
                                                <option value="Male">Male</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="age" class="form-label-sm">Age</label>
                                            <input type="text" class="form-control form-control-sm" id="age"
                                                placeholder="Enter Age" disabled>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="mobileNumber" class="form-label-sm">Mobile Number</label>
                                            <input type="text" class="form-control form-control-sm" id="mobileNumber"
                                                placeholder="Enter Mobile Number" required>
                                        </div>
                                        <div class="col-md-8">
                                            <label for="emailAddress" class="form-label-sm">Email Address</label>
                                            <input type="email" class="form-control form-control-sm" id="emailAddress"
                                                placeholder="Enter Email Address">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="religion" class="form-label-sm">Religion</label>
                                            <select class="form-control form-control-sm select2" id="religion" required
                                                style="width: 100%">
                                                <option value="">Select Religion</option>
                                                @foreach (DB::table('religion')->get() as $religion)
                                                    <option value="{{ $religion->id }}">{{ $religion->religionname }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="nationality" class="form-label-sm">Nationality</label>
                                            <select class="form-control form-control-sm select2" id="nationality"
                                                style="width: 100%">
                                                <option value="">Select Nationality</option>
                                                @foreach (DB::table('nationality')->get() as $nationality)
                                                    <option value="{{ $nationality->id }}">{{ $nationality->nationality }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="street">Street</label>
                                            <input type="text" class="form-control form-control-sm" id="street"
                                                placeholder="Enter Street" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="barangay">Barangay</label>
                                            <input type="text" class="form-control form-control-sm" id="barangay"
                                                placeholder="Enter Barangay" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="City">City/Municipality</label>
                                            <input type="text" class="form-control form-control-sm" id="City"
                                                placeholder="Enter City/Municipality" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="province ">Province </label>
                                            <input type="text" class="form-control form-control-sm" id="province "
                                                placeholder="Enter Province" required>
                                        </div>
                                    </div>

                                    <hr style="border-color: black" class="mt-3 md-3">
                                    <h5 class="mb-4 mt-3" style="color: black">Parents / Guardian Information</h5>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="fatherFirstName">Father's First Name</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="fatherFirstName" placeholder="Enter Father's First Name">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="fatherMiddleName">Father's Middle Name</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="fatherMiddleName" placeholder="Enter Father's Middle Name">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="fatherLastName">Father's Last Name</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="fatherLastName" placeholder="Enter Father's Last Name">
                                        </div>
                                        <div class="col-md-1">
                                            <label for="fatherSuffix">Suffix</label>
                                            <input type="text" class="form-control form-control-sm" id="fatherSuffix"
                                                placeholder="Enter Suffix">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <label for="fContactNumber">Contact Number</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="fContactNumber" placeholder="Enter Contact Number">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="fOccupation">Occupation</label>
                                            <input type="text" class="form-control form-control-sm" id="fOccupation"
                                                placeholder="Enter Occupation">
                                        </div>
                                        <div class="col-md-5">
                                            <label for="fHAE">High Educational Attainment</label>
                                            <input type="text" class="form-control form-control-sm" id="fHAE"
                                                placeholder="Enter High Educational Attainment">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="fEthnicity">Ethnicity</label>
                                            <input type="text" class="form-control form-control-sm" id="fEthnicity"
                                                placeholder="Enter Ethnicity">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="motherFirstName">Mother's First Name</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="motherFirstName" placeholder="Enter Mother's First Name">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="motherMiddleName">Mother's Middle Name</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="motherMiddleName" placeholder="Enter Mother's Middle Name">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="motherLastName">Mother's Last Name</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="motherLastName" placeholder="Enter Mother's Last Name">
                                        </div>
                                        <div class="col-md-1">
                                            <label for="motherSuffix">Suffix</label>
                                            <input type="text" class="form-control form-control-sm" id="motherSuffix"
                                                placeholder="Enter Suffix">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <label for="mContactNumber">Contact Number</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="mContactNumber" placeholder="Enter Contact Number">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="mOccupation">Occupation</label>
                                            <input type="text" class="form-control form-control-sm" id="mOccupation"
                                                placeholder="Enter Occupation">
                                        </div>
                                        <div class="col-md-5">
                                            <label for="mHAE">High Educational Attainment</label>
                                            <input type="text" class="form-control form-control-sm" id="mHAE"
                                                placeholder="Enter High Educational Attainment">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="mEthnicity">Ethnicity</label>
                                            <input type="text" class="form-control form-control-sm" id="mEthnicity"
                                                placeholder="Enter Ethnicity">
                                        </div>
                                    </div>
                                    <hr style="border-color: black" class="mt-3 md-3">
                                    <h5 class="mb-4 mt-3">Guardian Information</h5>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="guardianFirstName">Guardian's First Name</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="guardianFirstName" placeholder="Enter Guardian's First Name">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="guardianMiddleName">Guardian's Middle Name</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="guardianMiddleName" placeholder="Enter Guardian's Middle Name">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="guardianLastName">Guardian's Last Name</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="guardianLastName" placeholder="Enter Guardian's Last Name">
                                        </div>
                                        <div class="col-md-1">
                                            <label for="guardianSuffix">Suffix</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="guardianSuffix" placeholder="Enter Suffix">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <label for="gContactNumber">Contact Number</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="gContactNumber" placeholder="Enter Contact Number">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="gOccupation">Occupation</label>
                                            <input type="text" class="form-control form-control-sm" id="gOccupation"
                                                placeholder="Enter Occupation">
                                        </div>
                                        <div class="col-md-5">
                                            <label for="gHAE">High Educational Attainment</label>
                                            <input type="text" class="form-control form-control-sm" id="gHAE"
                                                placeholder="Enter High Educational Attainment">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="gEthnicity">Ethnicity</label>
                                            <input type="text" class="form-control form-control-sm" id="gEthnicity"
                                                placeholder="Enter Ethnicity">
                                        </div>
                                    </div>
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

                                    <hr style="border-color: black" class="mt-3 md-3">
                                    <h5 class="mb-4 mt-3">Educational Information</h5>
                                    <div class="row mb-3" style="font-size: 12px">
                                        <div class="col-md-3">
                                            <label for="lastSchoolAttended">Last School Attended</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="lastSchoolAttended" placeholder="Enter Last School Attended">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="lastGradeLevelCompleted">Last Grade Level Completed</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="lastGradeLevelCompleted"
                                                placeholder="Enter Last Grade Level Completed">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="schoolContactNo">School's Contact No.</label>
                                            <input type="number" class="form-control form-control-sm"
                                                id="schoolContactNo" placeholder="Enter School's Contact No.">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="schoolMailingAddress">Last School Mailing Address</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="schoolMailingAddress" placeholder="Enter Last School Mailing Address">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="schoolTypeLabel"></label>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="schoolName">School Name</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="schoolYearGraduated">School Year Graduated</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="schoolType">School Type</label>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="preSchool">Pre-School</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control form-control-sm" id="preSchoolName"
                                                placeholder="Enter Pre-School Name">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control form-control-sm"
                                                id="preSchoolYearGraduated" placeholder="Enter Pre-School Year Graduated">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control form-control-sm" id="preSchoolType"
                                                placeholder="Enter Pre-School Type">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="gradeSchool">Grade School</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control form-control-sm"
                                                id="gradeSchoolName" placeholder="Enter Grade School Name">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control form-control-sm"
                                                id="gradeSchoolYearGraduated"
                                                placeholder="Enter Grade School Year Graduated">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control form-control-sm"
                                                id="gradeSchoolType" placeholder="Enter Grade School Type">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="juniorHighSchool">Junior High School</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control form-control-sm"
                                                id="juniorHighSchoolName" placeholder="Enter Junior High School Name">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control form-control-sm"
                                                id="juniorHighSchoolYearGraduated"
                                                placeholder="Enter Junior High School Year Graduated">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control form-control-sm"
                                                id="juniorHighSchoolType" placeholder="Enter Junior High School Type">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="seniorHighSchool">Senior High School</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control form-control-sm"
                                                id="seniorHighSchoolName" placeholder="Enter Senior High School Name">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control form-control-sm"
                                                id="seniorHighSchoolYearGraduated"
                                                placeholder="Enter Senior High School Year Graduated">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control form-control-sm"
                                                id="seniorHighSchoolType" placeholder="Enter Senior High School Type">
                                        </div>
                                    </div>
                                    <hr style="border-color: black" class="mt-3 md-3">
                                    <h5 class="mb-4 mt-3">Medical Information</h5>
                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <label for="Height">Height (Meters)</label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" class="form-control form-control-sm" id="Height"
                                                placeholder="Enter Height (Meters)">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="Weight">Weight (kgs)</label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" class="form-control form-control-sm" id="Weight"
                                                placeholder="Enter Weight (kgs)">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="otherMed">Any Current Medications, Specify </label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control form-control-sm" id="otherMed"
                                                placeholder="Enter Medications, Specify">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <label for="anyAllergies">Any Allergies</label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control form-control-sm"
                                                id="anyAllergies" placeholder="Enter Any Allergies">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="medAllergies"> Medications to allergies</label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control form-control-sm"
                                                id="medAllergies" placeholder="Enter Medications to allergies">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <label for="medHistory">Medical History</label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control form-control-sm" id="medHistory"
                                                placeholder="Enter Medical History">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="otherMedInfo">Other Medical Information</label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control form-control-sm"
                                                id="otherMedInfo" placeholder="Enter Other Medical Information">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mx-3 mb-3">
                    <button type="button" class="btn btn-success" id="add_student_info">Add Student</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="enrollmentModal" tabindex="-1" aria-labelledby="enrollmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enrollmentModalLabelStudinfo">
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Student Info Section -->
                        <div class="card col-md-5">
                            <img id="studentPhoto" src="{{ asset('avatar/S(F) 1.png') }}" alt="Student Photo"
                                class="card-img-top"
                                style="height: 240px; object-fit: cover; object-position: center; border-radius: 10%; padding:10px">
                            <div class="card-body">
                                <table class="table table-sm" style="font-size:12px">
                                    <tr>
                                        <th>ID Number</th>
                                        <td id="studentIdNumberStudinfo"></td>
                                    </tr>
                                    <tr>
                                        <th>Specialization</th>
                                        <td id="specializationStudinfo"> Not Specified</td>
                                    </tr>
                                    <tr>
                                        <th>Student Status</th>
                                        <td id="studentStatusStudinfo">Not Enrolled</td>
                                    </tr>
                                </table>
                                <div class="text-center">
                                    <button id="updateCourse" class="btn btn-success btn-sm">Update Profile
                                        Picture</button>
                                </div>
                            </div>
                        </div>
                        <!-- Form Section -->
                        <div class="col-md-7">
                            <div class="ml-2">
                                <form id="enrollmentForm">
                                    <h5>Academic Year : {{ DB::table('sy')->where('isactive', 1)->first()->sydesc }}
                                        {{ DB::table('semester')->where('isactive', 1)->first()->semester }} </h5>
                                    <div class="mb-3">
                                        <label for="specialization" class="form-label">Specialization</label>
                                        <select class="form-control select2" id="input_specialization" required>
                                            <option value="" selected disabled>Select...</option>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="input_batch" class="form-label">Batch</label>
                                        <select class="form-control select2" id="input_batch" required>
                                            {{-- <option value="" selected disabled>Select...</option>
                                            @foreach (DB::table('tesda_batches')->get() as $batch)
                                                <option value="{{ $batch->id }}">{{ $batch->batch_desc }}</option>
                                            @endforeach --}}
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="enrollmentDate" class="form-label">Date of Enrollment</label>
                                        <input type="text" class="form-control form-control-sm" id="enrollmentDate"
                                            value="N/A" disabled>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" id="enrollButton" class="btn btn-primary">Enroll</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="enrollmentModalEdit" tabindex="-1" aria-labelledby="enrollmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enrollmentModalLabelEdit">

                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Student Info Section -->
                        <div class="card col-md-5">
                            <img id="studentPhoto" src="{{ asset('avatar/S(F) 1.png') }}" alt="Student Photo"
                                class="card-img-top"
                                style="height: 240px; object-fit: cover; object-position: center; border-radius: 10%; padding:10px">
                            <div class="card-body">
                                <table class="table table-sm" style="font-size:12px">
                                    <tr>
                                        <th>ID Number</th>
                                        <td id="studentIdNumberEdit"></td>
                                    </tr>
                                    <tr>
                                        <th>Specialization</th>
                                        <td id="specializationEdit"></td>
                                    </tr>
                                    <tr>
                                        <th>Student Status</th>
                                        <td id="studentStatusEdit"></td>
                                    </tr>
                                </table>
                                <div class="text-center">
                                    <button id="printCOR" class="btn btn-primary btn-sm">Print COR</button>
                                </div>
                            </div>
                        </div>
                        <!-- Form Section -->
                        <div class="col-md-7">
                            <div class="ml-2">
                                <form id="enrollmentForm">
                                    <h5>Academic Year : {{ DB::table('sy')->where('isactive', 1)->first()->sydesc }}
                                        {{ DB::table('semester')->where('isactive', 1)->first()->semester }} </h5>
                                    <div class="mb-3">
                                        <label for="specialization" class="form-label">Specialization</label>
                                        <select class="form-control select2" id="input_specializationEdit" required @if(Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15) disabled @endif>
                                            <option value="" selected disabled>Select...</option>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="batch" class="form-label">Batch</label>
                                        <select class="form-control select2" id="batchEdit" required @if(Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15) disabled @endif>
                                            <option value="" selected disabled>Select...</option>
                                            @foreach (DB::table('tesda_batches')->get() as $batch)
                                                <option value="{{ $batch->id }}">{{ $batch->batch_desc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="enrollmentDateEdit" class="form-label">Date of Enrollment</label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="enrollmentDateEdit" value="N/A" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-control select2" id="statusEdit" required @if(Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15) disabled @endif>
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach (DB::table('studentstatus')->get() as $status)
                                                <option value="{{ $status->id }}">{{ $status->description }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="mb-3">
                                        <label for="dateUpdated" class="form-label">Date Updated</label>
                                        <input type="text" class="form-control form-control-sm" id="dateUpdated"
                                            value="N/A" disabled>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    @if(Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15)
                        <button type="button" id="allowNoDp" 
                        class="btn btn-info">Allow No DP</button>
                    @else
                        <button type="button" id="updateEnrollment" data-purpose="update"
                            class="btn btn-success">Update</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

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




    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="info-box shadow-sm">
                        <div class="info-box-content" style="font-size:15px !important">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5><i class="fa fa-filter"></i> Filter</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3  form-group  mb-0">
                                    <label for="" class="mb-1">Courses</label>
                                    <select class="form-control select2 form-control-sm" id="filterCourse"
                                        style="width:100%;">
                                        <option value="">All</option>
                                        @foreach ($courses as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->course_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-0">
                                    <label for="" class="mb-1">Course Type</label>
                                    <select name="" id="filterCourseType"
                                        class="form-control select2 form-control-sm" style="width: 100%;">
                                        <option value="">All</option>
                                        @foreach (DB::table('tesda_course_type')->get() as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-0">
                                    <label for="" class="mb-1">Status</label>
                                    <select name="" id="filterStatus"
                                        class="form-control select2 form-control-sm" style="width: 100%;">
                                        <option value="">All</option>
                                        @foreach (DB::table('studentstatus')->get() as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-0">
                                    <label for="" class="mb-1">Course Duration</label>
                                    <select name="" id="filterCourseDuration"
                                        class="form-control select2 form-control-sm" style="width: 100%;">
                                        <option value="">All</option>
                                        @foreach (DB::table('tesda_batches')->select('tesda_batches.id', DB::raw("CONCAT_WS(' - ', DATE_FORMAT(tesda_batches.date_from, '%m/%d/%Y'), DATE_FORMAT(tesda_batches.date_to, '%m/%d/%Y')) AS date_range"))->get() as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->date_range }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-gray">
                            <h3 class="card-title"><i class="fas fa-list"></i> Student List</h3>
                        </div>
                        <div class="card-body">
                            @if (isset($check_refid->refid) && $check_refid->refid == 35)
                                <div class="row">
                                    <div class="col-md-6 mb-21">
                                        <button class="btn btn-sm btn-success" id="addNewStudent" data-toggle="modal"
                                            data-target="#addStudentModal">+ Add New Student</button>
                                    </div>
                                    <div class="col-md-6 mb-2 text-right">
                                        <button class="btn btn-sm btn-primary" id="enrollStudent"> Enroll All
                                            Student</button>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="studentList"
                                            class="table table-bordered table-valign-middle table-hover" width="100%">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th width="5%"></th>
                                                    <th width="10%">Student ID</th>
                                                    <th width="25%">Student Name</th>
                                                    <th width="25%">Specialization</th>
                                                    <th width="20%">Batch</th>
                                                    <th width="5%">DP</th>
                                                    <th width="5%" class="text-center">Status</th>
                                                    <th width="5%" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="studentListBody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12" style="font-size: 12px">
                                    |
                                    @foreach (DB::table('studentstatus')->get() as $item)
                                        @switch($item->id)
                                            @case(0)
                                                <a href="#" class="badge_stat text-secondary"
                                                    data-id="{{ $item->id }}"><span
                                                        class="badge">{{ $item->description }}
                                                        (<span class="badge_stat_count totalNotEnrolled"
                                                            data-id="{{ $item->id }}">0</span>)
                                                    </span> </a> |
                                            @break

                                            @case(1)
                                                <a href="#" class="badge_stat text-success"
                                                    data-id="{{ $item->id }}"><span
                                                        class="badge">{{ $item->description }}
                                                        (<span class="badge_stat_count totalEnrolled"
                                                            data-id="{{ $item->id }}">0</span>)
                                                    </span> </a> |
                                            @break

                                            @case(2)
                                                <a href="#" class="badge_stat " data-id="{{ $item->id }}"
                                                    style="color: #58715f;"><span class="badge">{{ $item->description }} (
                                                        <span class="badge_stat_count totalLateEnrolled"
                                                            data-id="{{ $item->id }}">0</span>
                                                        )</span> </a> |
                                            @break

                                            @case(3)
                                                <a href="#" class="badge_stat text-danger"
                                                    data-id="{{ $item->id }}"><span
                                                        class="badge">{{ $item->description }}
                                                        (<span class="badge_stat_count totalDropped"
                                                            data-id="{{ $item->id }}">0</span>)
                                                    </span> </a> |
                                            @break

                                            @case(4)
                                                <a href="#" class="badge_stat text-primary"
                                                    data-id="{{ $item->id }}"><span
                                                        class="badge">{{ $item->description }}
                                                        ( <span class="badge_stat_count totalTransferredIn"
                                                            data-id="{{ $item->id }}">0</span>
                                                        )</span> </a> |
                                            @break

                                            @case(5)
                                                <a href="#" class="badge_stat" data-id="{{ $item->id }}"
                                                    style="color: #fd7e14;"><span class="badge">{{ $item->description }} (
                                                        <span class="badge_stat_count totalTransferredOut"
                                                            data-id="{{ $item->id }}">0</span>
                                                        )</span> </a> |
                                            @break

                                            @case(6)
                                                <a href="#" class="badge_stat text-warning"
                                                    data-id="{{ $item->id }}"><span
                                                        class="badge">{{ $item->description }}
                                                        ( <span class="badge_stat_count totalWithdrawn"
                                                            data-id="{{ $item->id }}">0</span>
                                                        )</span> </a> |
                                            @break

                                            @case(7)
                                                <a href="#" class="badge_stat text-dark"
                                                    data-id="{{ $item->id }}"><span
                                                        class="badge">{{ $item->description }}
                                                        ( <span class="badge_stat_count totalDeceased"
                                                            data-id="{{ $item->id }}">0</span>
                                                        )</span> </a> |
                                            @break

                                            @default
                                                <a href="#" class="badge_stat text-secondary"
                                                    data-id="{{ $item->id }}"><span
                                                        class="badge">{{ $item->description }}
                                                        ( <span class="badge_stat_count totalNotEnrolled"
                                                            data-id="{{ $item->id }}">0</span>
                                                        )</span> </a> |
                                        @endswitch
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script>
        var refid = {!! json_encode($check_refid->refid) !!}
        var currentStudId = '';
        var course_id;
        var storedTab = localStorage.getItem("activeTab");
        var selected = '';

        if (typeof Toast === 'undefined') {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }


        $(document).ready(function() {
            $('.select2').select2();
            fetchStudentList();


            $('#allowNoDp').on('click', function() {

                LoadingModal.show();

               $.ajax({
                   type: 'POST',
                   url: '/tesda/allownodp',
                   data: {
                        studid: currentStudId,
                       _token: $('meta[name="csrf-token"]').attr('content'),
                   },
                   success: function(data) {
                       LoadingModal.hide();
                       console.log('no_dp..', data);
                       if(data[0].status == 1){
                           Toast.fire({
                               type: 'success',
                               title: data[0].message
                           });

                           $(this).text('Cancel No DP');
                           $(this).addClass('btn-danger');
                           $(this).removeClass('btn-info');

                           $('#enrollmentModalEdit').modal('hide');

                           fetchStudentList();
                       }
                   },error: function(data) {
                       LoadingModal.hide();
                       Toast.fire({
                           type: 'error',
                           title: data[0].message
                       });
                   }
               })
                
            })

            if (storedTab) {
                activateTab(storedTab);
            } else {
                activateTab("#student-info");
            }
            $('#custom-tabs a').on('click', function() {
                var tabId = $(this).attr('href');
                activateTab(tabId);
            });


            $(document).on('click', '.enroll_student', function() {
                const studentId = $(this).data('id');

                getStudentInfo(studentId).then(function(data) {
                    if (data != null) {
                        $('#input_specialization').val(data.courseid ?? data.initialcourseid)
                            .change();
                        $.ajax({
                            type: 'get',
                            url: '/tesda/batch_setup/get/batches',
                            data: {
                                course_id: $('#input_specialization').val()
                            },
                            success: function(data) {
                                console.log('batches..', data);
                                $('#input_batch').empty()
                                    .append(
                                        '<option selected disabled value="">Select Batch</option>'
                                    )
                                    .select2({
                                        data: data.map(batch => ({
                                            id: batch.id,
                                            text: batch.batch_desc
                                        }))
                                    });
                            }
                        });

                        var fullname =
                            `${data.lastname}, ${data.firstname} ${data.middlename ? data.middlename[0].toUpperCase() : ''}.`;
                        $('#enrollmentModalLabelStudinfo').text(fullname);
                        $('#studentIdNumberStudinfo').text(data.sid);
                        $('#specializaitonStudinfo').text(data.course_name ?? data
                            .initialcoursename);

                        $('#input_batch').val(data.batchid ?? '')
                            .change();
                        $('#enrollButton').attr('data-id', data.id);

                    }

                    $('#enrollmentModal').modal('show');
                });

            })

            $('#input_specializationEdit').on('change', function() {
                var text = $(this).find(':selected').text();
                $('#specializationEdit').text(text);

                $.ajax({
                    type: 'get',
                    url: '/tesda/batch_setup/get/batches',
                    data: {
                        course_id: $(this).val()
                    },
                    success: function(respo) {
                        console.log('edit batches..', respo);
                        $('#batchEdit')
                            .empty()
                            .append(
                                '<option selected disabled value="">Select Batch</option>'
                            )
                            .select2({
                                data: respo.map(batch => ({
                                    id: batch.id,
                                    text: batch.batch_desc
                                }))
                            });
                    }
                });
            })

            // $(document).on('click', '#printCOR', function() {
            //     if (currentStudId != '') {
            //         console.log('CORid...', currentStudId);
            //         window.open(`/tesda/printCOR/${currentStudId}`, '_blank')
            //     }
            // })

            $(document).on('click', '.edit_student', function() {
                const studentId = $(this).data('id');
                const can_enroll = $(this).data('can_enroll');
                console.log('can_enroll..', can_enroll);

                getStudentInfo(studentId).then(function(data) {
                    if (data != null) {

                        $('#input_specializationEdit').val(data.courseid ?? data.initialcourseid)
                            .change();

                        $.ajax({
                            type: 'get',
                            url: '/tesda/batch_setup/get/batches',
                            data: {
                                course_id: data.courseid ?? data.initialcourseid
                            },
                            success: function(respo) {
                                console.log('edit batches..', respo);
                                $('#batchEdit')
                                    .empty()
                                    .append(
                                        '<option selected disabled value="">Select Batch</option>'
                                    )
                                    .select2({
                                        data: respo.map(batch => ({
                                            id: batch.id,
                                            text: batch.batch_desc
                                        }))
                                    });

                                // Set the value after populating the select options
                                $('#batchEdit').val(data.batchid ?? '').change();
                            },
                        });



                        var fullname =
                            `${data.lastname}, ${data.firstname} ${data.middlename ? data.middlename[0].toUpperCase() : ''}.`;
                        $('#enrollmentModalLabelEdit').text(fullname);
                        $('#studentIdNumberEdit').text(data.sid);
                        $('#specializationEdit').text(data.course_name ?? data.initialcoursename);
                        $('#statusEdit').val(data.studentstatusid ?? 1).change();
                        $('#studentStatusEdit').text(data.studentstatus ?? 'Not Enrolled');
                        $('#enrollmentDateEdit').val(data.first_enrollment ?? data.enrollmentdate);
                        $('#dateUpdated').val(data.enrollmentdate);
                        $('#updateEnrollment').attr('data-id', data.id);
                        $('#updateEnrollment').attr('data-status', data.studentstatusid);
                        $('#printCOR').attr('data-id', data.id)
                        currentStudId = data.id

                        if (can_enroll === 1) {
                            const allowNoDpButton = $('#allowNoDp');
                            allowNoDpButton.text('Cancel No DP');
                            allowNoDpButton.addClass('btn-danger').removeClass('btn-info');
                        } else {
                            const allowNoDpButton = $('#allowNoDp');
                            allowNoDpButton.text('Allow No DP');
                            allowNoDpButton.addClass('btn-info').removeClass('btn-danger');
                        }

                    }

                    $('#enrollmentModalEdit').modal('show');
                });

            })


            $(document).on('click', '.delete_student', function() {
                const studentId = $(this).data('id');

                if(refid == 0 || refid == null){
                    Toast.fire({
                        type: 'error',
                        title: 'You are not authorized to delete a student'
                    })
                    return
                }

                Swal.fire({
                    title: 'Delete Student?',
                    text: "This action cannot be undone!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        // Proceed with the deletion
                        $.ajax({
                            url: '/tesda/enrollment/delete',
                            type: 'POST',
                            data: {
                                id: studentId,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Deleted!',
                                        'The student has been deleted.',
                                        'success'
                                    );
                                    fetchStudentList(); // Refresh the list
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'An error occurred while deleting the student.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            })

            $('#enrollButton').click(function(e) {
                e.preventDefault();

                enrollmentData = {
                    studid: $(this).attr('data-id'), // Assuming this holds the student ID
                    courseid: $('#input_specialization').val(),
                    batchid: $('#input_batch').val(),
                    status: 1,
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF Token
                };
                saveEnrollment(enrollmentData)

            });
            $('#updateEnrollment').click(function(e) {
                e.preventDefault();

                if (($(this).attr('data-status') == 0 || $(this).attr('data-status') == null) &&
                    $('#statusEdit').val() == 0) {
                    Swal.fire(
                        'Error!',
                        'Please Enroll the student. Select Enrolled status.',
                        'error'
                    );
                    return;
                }

                var enrollmentData = {
                    studid: $(this).attr('data-id'),
                    courseid: $('#input_specializationEdit').val(),
                    batchid: $('#batchEdit').val(),
                    status: $('#statusEdit').val(),
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF Token
                };
                saveEnrollment(enrollmentData)
            });


            $('#input_specialization').on('change', function() {
                var text = $(this).find(':selected').text();
                $('#specializationStudinfo').text(text);

                $.ajax({
                    type: 'get',
                    url: '/tesda/batch_setup/get/batches',
                    data: {
                        course_id: $(this).val()
                    },
                    success: function(data) {
                        console.log('batches..', data);

                        $('#input_batch').empty()
                            .append(
                                '<option selected disabled value="">Select Batch</option>'
                            )
                            .select2({
                                data: data.map(batch => ({
                                    id: batch.id,
                                    text: batch.batch_desc
                                }))
                            });
                    }
                });
            })

            $('#statusEdit').on('change', function() {
                var text = $(this).find(':selected').text();
                $('#studentStatusEdit').text(text);
            })

            $('#filterCourseType, #filterStatus, #filterCourseDuration').on('change', function() {
                fetchStudentList();
            })

            $('#filterCourse').on('change', function() {
                fetchStudentList();

                $.ajax({
                    type: 'get',
                    url: '/tesda/batch_setup/get/batches',
                    data: {
                        course_id: $(this).val()
                    },
                    success: function(data) {
                        console.log('batches..', data);

                        $('#filterCourseDuration').empty()
                            .append(
                                '<option selected value="">All</option>'
                            )
                            .select2({
                                data: data.map(batch => ({
                                    id: batch.id,
                                    text: batch.date_range
                                }))
                            });
                    }
                });
            })

            $('#dateOfBirth').on('change', function() {
                const dob = new Date($(this).val());
                if (!isNaN(dob.getTime())) {
                    const today = new Date();
                    let age = today.getFullYear() - dob.getFullYear();
                    const monthDiff = today.getMonth() - dob.getMonth();
                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                        age--;
                    }
                    $('#age').val(age);
                    console.log('Age:', age);
                } else {
                    $('#age').val('');
                    console.log('Invalid date of birth');
                }
            });


            $('#add_student_info').on('click', function() {
                var valid = true;

                const studentData = {
                    // studentIdNo: $('#studentIdNo').val(),
                    firstName: $('#firstName').val(),
                    middleName: $('#middleName').val(),
                    lastName: $('#lastName').val(),
                    suffix: $('#suffix').val(),
                    dateOfBirth: $('#dateOfBirth').val(),
                    placeOfBirth: $('#placeOfBirth').val(),
                    gender: $('#gender').val(),
                    age: $('#age').val(),
                    mobileNumber: $('#mobileNumber').val(),
                    emailAddress: $('#emailAddress').val(),
                    religion: $('#religion').val(),
                    nationality: $('#nationality').val(),
                    street: $('#street').val(),
                    barangay: $('#barangay').val(),
                    city: $('#city').val(),
                    province: $('#province').val(),
                    fatherFirstName: $('#fatherFirstName').val(),
                    fatherMiddleName: $('#fatherMiddleName').val(),
                    fatherLastName: $('#fatherLastName').val(),
                    fatherSuffix: $('#fatherSuffix').val(),
                    fContactNumber: $('#fContactNumber').val(),
                    fOccupation: $('#fOccupation').val(),
                    fHAE: $('#fHAE').val(),
                    fEthnicity: $('#fEthnicity').val(),
                    motherFirstName: $('#motherFirstName').val(),
                    motherMiddleName: $('#motherMiddleName').val(),
                    motherLastName: $('#motherLastName').val(),
                    motherSuffix: $('#motherSuffix').val(),
                    mContactNumber: $('#mContactNumber').val(),
                    mOccupation: $('#mOccupation').val(),
                    mHAE: $('#mHAE').val(),
                    mEthnicity: $('#mEthnicity').val(),
                    guardianFirstName: $('#guardianFirstName').val(),
                    guardianMiddleName: $('#guardianMiddleName').val(),
                    guardianLastName: $('#guardianLastName').val(),
                    guardianSuffix: $('#guardianSuffix').val(),
                    gContactNumber: $('#gContactNumber').val(),
                    gOccupation: $('#gOccupation').val(),
                    gHAE: $('#gHAE').val(),
                    gEthnicity: $('#gEthnicity').val(),
                    lastSchoolAttended: $('#lastSchoolAttended').val(),
                    lastGradeLevelCompleted: $('#lastGradeLevelCompleted').val(),
                    schoolContactNo: $('#schoolContactNo').val(),
                    schoolMailingAddress: $('#schoolMailingAddress').val(),
                    schoolType: $('#schoolType').val(),
                    schoolName: $('#schoolName').val(),
                    schoolYearGraduated: $('#schoolYearGraduated').val(),
                    preSchool: $('#preSchool').val(),
                    preSchoolName: $('#preSchoolName').val(),
                    preSchoolYearGraduated: $('#preSchoolYearGraduated').val(),
                    preSchoolType: $('#preSchoolType').val(),
                    gradeSchool: $('#gradeSchool').val(),
                    gradeSchoolName: $('#gradeSchoolName').val(),
                    gradeSchoolYearGraduated: $('#gradeSchoolYearGraduated').val(),
                    gradeSchoolType: $('#gradeSchoolType').val(),
                    juniorHighSchool: $('#juniorHighSchool').val(),
                    juniorHighSchoolName: $('#juniorHighSchoolName').val(),
                    juniorHighSchoolYearGraduated: $('#juniorHighSchoolYearGraduated').val(),
                    juniorHighSchoolType: $('#juniorHighSchoolType').val(),
                    seniorHighSchool: $('#seniorHighSchool').val(),
                    seniorHighSchoolName: $('#seniorHighSchoolName').val(),
                    seniorHighSchoolYearGraduated: $('#seniorHighSchoolYearGraduated').val(),
                    seniorHighSchoolType: $('#seniorHighSchoolType').val(),
                    Height: $('#Height').val(),
                    Weight: $('#Weight').val(),
                    otherMed: $('#otherMed').val(),
                    anyAllergies: $('#anyAllergies').val(),
                    medAllergies: $('#medAllergies').val(),
                    medHistory: $('#medHistory').val(),
                    otherMedInfo: $('#otherMedInfo').val(),
                    specialization: $('#specializationSelect').val(),
                    incase: $('input[name="incase"]:checked').val(),

                };

                $('#newStudForm input[required], #newStudForm select[required]').each(function() {
                    if ($(this).val() == '') {
                        $(this).addClass('is-invalid');
                        valid = false;
                        Swal.fire({
                            title: "Error!",
                            text: "Please fill in all required fields!",
                            type: "error",
                            confirmButtonText: "OK"
                        });

                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                if (!valid) {
                    return;
                }


                // Send AJAX request to add the student
                $.ajax({
                    url: '/tesda/addStudent',
                    type: 'POST',
                    data: studentData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    beforeSend: function() {
                        LoadingModal.show();
                    },
                    success: function(response) {
                        LoadingModal.hide();
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Student information added successfully!',
                                type: 'success',
                                confirmButtonText: 'Okay',
                            }).then((result) => {
                                if (result.value) {
                                    $('input').val('');
                                    fetchStudentList();
                                }
                            });
                        } else {
                            // alert('Failed to add student information.');
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                type: 'error',
                                confirmButtonText: 'OK'
                            })
                        }
                    },
                    // complete: function() {
                    //     LoadingModal.hide();
                    // },
                    error: function(xhr) {
                        LoadingModal.hide();
                        console.error(xhr.responseJSON);
                        alert('An error occurred. Please try again.');
                    },
                });
            });

            $(document).on('click', '.clickableStudentInformation', function() {
                const studentId = $(this).data('id') ?? studId;
                $('#studentInfoTab').modal('show');
                $('#registration-tab').attr('data-id', studentId);
                $('#enrollment-history-tab').attr('data-id', studentId);
                $('#student-info-tab').attr('data-id', studentId);
                activateTab("#student-info");
            });

            $(document).on('click', '.clickableStudentInformation', function() {
                const studentId = $(this).data('id') ?? studId;
                $('#studentInfoTab').modal('show');
                $('#registration-tab').attr('data-id', studentId);
                $('#registration-tab').on('click', function() {
                    $('#registration').attr('data-id', studentId);
                    // $('#input_specialization').attr('data-id', courseid);
                    // $('#batchs').attr('data-id', batchid);
                })
                $('#enrollment-history-tab').attr('data-id', studentId);
                $('#student-info-tab').attr('data-id', studentId);

            })

            $(document).on('click', '.clickableStudentInformation', function() {
                var studentId = $(this).data('id') ?? studId;
                console.log(studentId, 'clicked student ID');
                selected = studentId;

                if (studentId !== undefined) {
                    $.ajax({
                        url: '/tesda/studentInformation/' + studentId,
                        type: 'GET',
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                var student = response.student;
                                course_id = student.courseid;

                                console.log('Father:', student.isfathernum, 'Mother:', student
                                    .ismothernum, 'Guardian:', student.isguardannum);

                                // Reset all radio buttons first
                                $('input[name="incase"]').prop('checked', false);

                                // Emergency Contact Selection
                                if (student.isfathernum == 1) {
                                    $('#father2').prop('checked', true);
                                } else if (student.ismothernum == 1) {
                                    $('#mother2').prop('checked', true);
                                } else if (student.isguardannum == 1) {
                                    $('#guardian2').prop('checked', true);
                                }

                                $('#father2, #mother2, #guardian2').prop('disabled', true);

                                $('#father').prop('checked', true);
                                $('#mother').prop('checked', true);
                                $('#guardian').prop('checked', true);

                                console.log(student, 'student');
                                $('#studentStatus2').text(student.description ||
                                    'NOT ENROLLED');
                                $('#sid2').val(student.sid);
                                $('#fName').val(student.firstname);
                                $('#mName').val(student.middlename);
                                $('#lName').val(student.lastname);
                                $('#studsuffix').val(student.suffix || '');
                                $('#dob').val(student.dob);
                                $('#pob').val(student.pob);
                                $('#gender2').val(student.gender).trigger('change');
                                $('#specializationInput2').val(student.course_name);
                                $('#studReligion2').val(student.religionname);
                                $('#nationality2').val(student.nationality);

                                // $('#gender2').find('option').filter(function() {
                                //         return $(this).text().toLowerCase() === student.gender
                                //             .toLowerCase();
                                //     }).prop('selected', true).trigger('change');
                                $('#age2').val(student.age);
                                $('#mobileNumber2').val(student.contactno);
                                $('#emailAddress2').val(student.semail);
                                $('#street2').val(student.street);
                                $('#barangay2').val(student.barangay);
                                $('#City2').val(student.city);
                                $('#province2').val(student.province);

                                // Populate the specialization select field
                                // $('#specializationSelect2').val(student.course_name).trigger('change');
                                $('#studReligion').val(student.religionname).trigger('change');
                                $('#nationality').val(student.nationality_id).trigger('change');

                                //Father Information
                                $('#fatherFirstName2').val(student.ffname);
                                $('#fatherMiddleName2').val(student.fmname);
                                $('#fatherLastName2').val(student.flname);
                                $('#fatherSuffix2').val(student.fsuffix);
                                $('#fContactNumber2').val(student.fcontactno);
                                $('#fOccupation2').val(student.foccupation);
                                $('#fHAE2').val(student.fea);
                                $('#fEthnicity2').val(student.fethnicity);

                                //Mother Information
                                $('#motherFirstName2').val(student.mfname);
                                $('#motherMiddleName2').val(student.mmname);
                                $('#motherLastName2').val(student.mlname);
                                $('#motherSuffix2').val(student.msuffix);
                                $('#mContactNumber2').val(student.mcontactno);
                                $('#mOccupation2').val(student.moccupation);
                                $('#mHAE2').val(student.mea);
                                $('#mEthnicity2').val(student.methnicity);

                                //Guardian Information
                                $('#guardianFirstName2').val(student.gfname);
                                $('#guardianMiddleName2').val(student.gmname);
                                $('#guardianLastName2').val(student.glname);
                                $('#guardianSuffix2').val(student.gsuffix);
                                $('#gContactNumber2').val(student.gcontactno);
                                $('#gOccupation2').val(student.goccupation);
                                $('#gHAE2').val(student.gea);
                                $('#gEthnicity2').val(student.gethnicity);

                                //Education Information
                                $('#lastSchoolAttended2').val(student.lastschoolatt);
                                $('#lastGradeLevelCompleted2').val(student.glits);
                                $('#schoolContactNo2').val(student.scn);
                                $('#schoolMailingAddress2').val(student.cmaosla);

                                $('#preSchoolName2').val(student.psschoolname);
                                $('#preSchoolYearGraduated2').val(student.pssy);
                                $('#preSchoolType2').val(student.psschooltype);

                                $('#gradeSchoolName2').val(student.gsschoolname);
                                $('#gradeSchoolYearGraduated2').val(student.gssy);
                                $('#gradeSchoolType2').val(student.gsschooltype);

                                $('#juniorHighSchoolName2').val(student.jhsschoolname);
                                $('#juniorHighSchoolYearGraduated2').val(student.jhssy);
                                $('#juniorHighSchoolType2').val(student.jhsschooltype);

                                $('#seniorHighSchoolName2').val(student.shsschoolname);
                                $('#seniorHighSchoolYearGraduated2').val(student.shssy);
                                $('#seniorHighSchoolType2').val(student.shsschooltype);

                                //Med Information
                                $('#Height2').val(student.height);
                                $('#Weight2').val(student.weight);
                                $('#otherMed2').val(student.ACM);
                                $('#anyAllergies2').val(student.Allergy);
                                $('#medAllergies2').val(student.MedAllergy);
                                $('#medHistory2').val(student.MedhicalHistory);
                                $('#otherMedInfo2').val(student.OtherMedInfo);

                                $('#studentIdNumber2').text(student.sid);
                                $('#specializationText2').text(student.course_name);
                                $('#studentName').text(
                                    `${student.lastName}, ${student.firstName} ${student.middleName}${student.suffix ? ', ' + student.suffix : ''}`
                                );
                            } else {
                                alert('Student not found.');
                            }
                        },
                        error: function() {
                            alert('Error fetching student data.');
                        }
                    });

                    $('#studentInfoTab').modal('show');
                } else {
                    console.log('Student ID is undefined');
                }
            });

            $(document).on('click', '#registration-tab', function(e) {
                e.preventDefault();

                var studentId = $(this).data('id');

                $.ajax({
                    url: `/tesda/studentCOR/${studentId}`,
                    method: 'GET',
                    success: function(response) {
                        renderRegistrationContent(response, studentId);
                    },
                    error: function(xhr, status, error) {
                        $('#registration').html(
                            `<p>An error occurred: ${xhr.responseText || error}</p>`
                        );
                    }
                });
            });

            $(document).on('click', '#printCOR', function(e) {
                e.preventDefault();
                var studentId = $(this).data('id');
                var link = '/tesda/printCOR/' + studentId;
                var a = document.createElement('a');
                a.href = link;
                a.target = '_blank';
                a.click();
            })



            $(document).on('click', '#update-signatories', function() {
                // Enable edit mode
                $('.signatory-name').removeAttr('readonly').addClass('border');
                $('.signatory-title').removeAttr('readonly').addClass('border');
                $('.signatory-description').removeAttr('readonly').addClass('border');
                $('#edit-actions').show();
                $('#save-signatories').show();
                $('#update-signatories').hide();
                $('.delete-signatory').show();
            });


            $(document).on('click', '#add-signatories', function() {
                // Add new input fields dynamically
                var newSignatoryHTML = `
                    <div class="col-md-3 text-left signatory-item">
                        <p style="font-size: 12px;">Signatory:</p>
                        <input type="text" class="form-control signatory-description" placeholder="Enter description" required>
                        <input type="text" class="form-control signatory-name" data-id="0" placeholder="Enter name" required>
                        <hr style="width: 90%; margin: auto; border-color: black;">
                        <input type="text" class="form-control signatory-title" placeholder="Enter title" required>
                        <hr style="width: 90%; margin: auto; border-color: black;">
                    </div>
                `;

                $('#signatory-list').append(newSignatoryHTML);
            });

            $(document).on('click', '#save-signatories', function() {
                var signatories = [];

                $('.signatory-item').each(function() {
                    var array = [];
                    var name = $(this).find('.signatory-name').val() || '';
                    var title = $(this).find('.signatory-title').val() || '';
                    var description = $(this).find('.signatory-description').val() ||
                        ''; // Get description
                    var id = $(this).find('.signatory-name').data('id');

                    if (id == 0) {
                        if (name != '' && title != '' && description != '') {
                            array.push(id);
                            array.push(name);
                            array.push(title);
                            array.push(description);
                            signatories.push(array);
                        }
                    } else {
                        array.push(id);
                        array.push(name);
                        array.push(title);
                        array.push(description);
                        signatories.push(array);
                    }
                });

                $.ajax({
                    url: '/tesda/update/signatories',
                    method: 'GET',
                    data: {
                        signatories: signatories,
                        course_id: course_id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Successfully saved signatories!',
                                type: 'success',
                                confirmButtonText: 'Okay'
                            });
                            fetchSignatories();
                        } else {
                            Swal.fire({
                                title: 'Failed to save signatories.',
                                icon: 'error',
                                confirmButtonText: 'Okay'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error adding signatories:', error);
                    }
                });
            });


            $(document).on('click', '.delete-signatory', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/tesda/delete-signatories/' + id,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: 'The signatory has been successfully deleted.',
                                        icon: 'success',
                                        confirmButtonText: 'Okay'
                                    }).then(() => {
                                        fetchSignatories();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Failed!',
                                        text: 'Failed to delete signatory. Please try again.',
                                        icon: 'error',
                                        confirmButtonText: 'Okay'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error deleting signatory:', error);
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'An error occurred while deleting the signatory. Please try again.',
                                    icon: 'error',
                                    confirmButtonText: 'Okay'
                                });
                            }
                        });
                    }
                });
            });


            fetchSignatories();

            function clearEnrollmentHistory() {
                $('#enrollment-history').html('');
            }

            $(document).on("click", ".view-details", function(e) {
                e.preventDefault();
                var courseId = $(this).data("course-id");
                $("#registration-tab").trigger("click");
                $("#registration-tab").attr("data-id", courseId);
            });


            $(document).on('click', '#printTOR', function(e) {
                e.preventDefault();
                var studentId = $(this).data('id');
                var link = '/tesda/printTOR/' + studentId;
                var a = document.createElement('a');
                a.href = link;
                a.target = '_blank';
                a.click();
            })


            $(document).on('click', '#enrollment-history-tab', function(e) {
                e.preventDefault();

                var studentId = $(this).attr('data-id');
                console.log("Clicked Student ID:", studentId);

                if (!studentId) {
                    $('#enrollment-history').html('<p>No student ID provided.</p>');
                    return;
                }

                clearEnrollmentHistory();

                $.ajax({
                    url: `/tesda/enrollment-history/${studentId}`,
                    method: 'GET',
                    cache: false,
                    beforeSend: function() {
                        $('#enrollment-history').html('<p>Fetching enrollment history...</p>');
                    },
                    success: function(response) {
                        console.log("Response Data:", response);
                        renderEnrollmentHistory(response, studentId);

                        // Fetch signatories only if there is enrollment history
                        if (response.success && response.history.length > 0) {
                            fetchSignatories();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                        $('#enrollment-history').html(
                            `<p>An error occurred: ${xhr.responseText || error}</p>`);
                    },
                });
            });


        });



        function fetchSignatories() {
            $.ajax({
                url: '/tesda/signatories',
                method: 'GET',
                data: {
                    course_id: course_id
                },
                success: function(response) {
                    console.log(response, 'fetchsignatories');

                    renderSignatories(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching signatories:', error);
                }
            });
        }

        function renderSignatories(response) {
            // Get the course_id from the input field
            var course_id = $('#input_specialization').val() || 0;

            if (response.success && response.signatory.length > 0) {
                var signatoryHTML = `
                                <div class="d-flex justify-content-between m-2 align-items-center">
                                    <h6 style="font-size: 12px">Update TOR Signatories 
                                        <i class="fa fa-edit text-info" id="update-signatories" style="cursor: pointer;"></i>
                                        <button class="btn btn-success btn-sm" id="save-signatories" style="display:none;">Save</button>
                                    </h6>
                                    <div id="edit-actions" style="display: none;font-size: 12px;" class="text-success">Add Signatory
                                        <button class="btn btn-sm btn-success" id="add-signatories" style="font-size: 12px;">
                                            <i class="fa fa-plus"></i> 
                                        </button>
                                    </div>
                                </div>
                                <div class="row text-center mt-3" id="signatory-list">
                        `;

                // Loop through the returned signatories and generate HTML
                response.signatory.forEach((signatory, index) => {
                    const signatoryName = signatory.signatory_name || '';
                    const designation = signatory.signatory_title || '';
                    const label = signatory.description || 'Signatory';

                    signatoryHTML += `
                            <div class="col-md-3 text-left signatory-item" course-id="${signatory.course_id}" data-id="${signatory.id}">
                                <!-- Delete button for this specific signatory (initially hidden) -->
                                <button class="btn text-danger btn-sm delete-signatory float-right" data-id="${signatory.id}" style="font-size: 12px; display: none;">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                                <input type="text" class="form-control border-0 bg-transparent form-control signatory-description" style="font-size: 12px;" value="${label}" readonly>
                                <input type="text" class="form-control border-0 bg-transparent signatory-name" style="font-weight: bold; font-size: 12px;" value="${signatoryName}" data-id="${signatory.id}" readonly>
                                <hr style="width: 90%; margin: auto; border-color: black;">
                                <input type="text" class="form-control border-0 bg-transparent signatory-title" style="font-size: 12px;" value="${designation}" readonly>
                            </div>
                        `;
                });

                signatoryHTML += `</div>`;

                $('#tor-signatories').html(signatoryHTML);

            } else {
                $('#tor-signatories').html('<p>No signatories found for this course.</p>');
            }
        }


        function clearEnrollmentHistory() {
            // $('#enrollment-history').empty().html('<p>Loading enrollment history...</p>'); // Ensure previous content is fully removed
            $('#history-body').empty()
            $('#input_specialization').val(null).trigger('change');
            $('#batchs').val(null).trigger('change');
        }

        // Define the success callback function
        function renderEnrollmentHistory(response, studentId) {
            if (response.success && response.history.length > 0) {
                var tableHTML = `
                        <h4>Enrollment History</h4>
                        <button class="btn btn-primary btn-sm mb-3 mt-3" id='printTOR' data-id="${studentId}">Print TOR</button>
                        <table class="table table-striped table-bordered" style="font-size:12px">
                            <thead>
                                <tr>
                                    <th>Batch</th>
                                    <th>Course/Specialization</th>
                                    <th>Hrs</th>
                                    <th>Course Duration</th>
                                    <th>Date Enrolled</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="history-body">
                    `;

                response.history.forEach((record) => {
                    const batch = record.batch_desc || '';
                    const courseName = record.course_name || '';
                    const courseDuration = record.course_duration || '';
                    const dateEnrolled = record.dateenrolled || '';

                    tableHTML += `
                            <tr>
                                <td>${batch}</td>
                                <td>${courseName}</td>
                                <td>${courseDuration}</td>
                                <td>${record.date_from || ''} - ${record.date_to || ''}</td>
                                <td>${dateEnrolled}</td>
                                <td>
                                    <a href="#" class="view-details" data-course-id="${record.course_id}">View</a>
                                </td>
                            </tr>
                        `;
                });

                tableHTML += `
                                </tbody>
                            </table>
                            <div id="tor-signatories" class="mt-4" style="border: 1px solid #ccc; border-radius: 20px;">
                                <a href="#" style="font-size: 12px" class="m-3 update-signatories" role="button">
                                    Update TOR Signatories <i class="text-info fas fa-pencil-alt"></i>
                                </a>
                            </div>
                        `;

                $('#enrollment-history').html(tableHTML);
            } else {
                $('#enrollment-history').html(
                    '<h4>Enrollment History</h4><p>No enrollment history found for this student.</p>');
            }
        }

        function activateTab(tabId = "#student-info") {
            $('#custom-tabs a[href="' + tabId + '"]').tab('show');
            localStorage.setItem("activeTab", tabId);
        }
        // Define the success callback function
        function renderRegistrationContent(response, studentId) {
            if (response.success) {
                var studentData = response.student;
                var schedules = response.schedules;

                var registrationContent = `
                        <button class="btn btn-primary btn-sm mb-3 mt-3" id='printCOR' data-id="${studentId}">Print COR</button>
                        <table class="table table-bordered" style="font-size:12px">
                            <thead>
                                <tr>
                                    <th>Competency Description</th>
                                    <th>Hours</th>
                                    <th>Schedule</th>
                                    <th>Trainer</th>
                                    <th>Room</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                var totalHours = 0;

                // Check if there are schedules
                if (schedules.length > 0) {
                    schedules.forEach((schedule, index) => {
                        var scheduleDetails = schedule.scheddetails.length > 0 ? schedule.scheddetails[
                            0] : null;

                        registrationContent += `
                                <tr>
                                    <td>${schedule.competency_desc ?? 'No Competency'}</td>
                                    <td>${schedule.hours ?? 'TBA'}</td>
                                    <td>
                                        ${scheduleDetails && scheduleDetails.date_from && scheduleDetails.date_to
                                            ? `${scheduleDetails.date_from} to ${scheduleDetails.date_to}`
                                            : 'No schedule'} 
                                        (${scheduleDetails && scheduleDetails.stime && scheduleDetails.etime
                                            ? `${scheduleDetails.stime} - ${scheduleDetails.etime}`
                                            : ''})
                                    </td>
                                    <td>${scheduleDetails && scheduleDetails.trainer_name ? scheduleDetails.trainer_name : 'No trainer'}</td>
                                    <td>${scheduleDetails && scheduleDetails.roomname ? scheduleDetails.roomname : 'No room assigned'}</td>
                                </tr>
                            `;

                        totalHours += parseInt(schedule.hours) || 0;
                    });
                } else {
                    // If no schedules exist
                    registrationContent += `
                            <tr>
                                <td colspan="5" class="text-center">No schedules available</td>
                            </tr>
                        `;
                }

                registrationContent += `
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="1" class="text-right">Total Hours</th>
                                    <th>${totalHours}</th>
                                </tr>
                            </tfoot>
                        </table>
                    `;

                $('#registration').html(registrationContent);
            } else {
                $('#registration').html(`<p>${response.message}</p>`);
            }
        }

        function saveEnrollment(enrollmentData) {
            console.log(enrollmentData, 'eeeehhhh');

            // Validate client-side
            if (!enrollmentData.courseid || !enrollmentData.batchid || !enrollmentData.status) {
                Swal.fire('Error', 'Please select both specialization, status and batch.', 'error');
                return;
            }

            // Send AJAX request
            $.ajax({
                url: '/tesda/enrollment/save',
                type: 'POST',
                data: enrollmentData,
                beforeSend: function() {
                    LoadingModal.show('Saving enrollment...');
                },
                success: function(response) {
                    LoadingModal.hide();
                    if (response.success) {
                        $('#enrollmentModal').modal('hide');
                        $('#enrollmentModalEdit').modal('hide');
                        fetchStudentList();
                        Toast.fire({
                            type: 'success',
                            title: response.message
                        });
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: response.message
                        });
                    }
                },
                error: function(xhr) {
                    LoadingModal.hide();
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessages = '';
                        for (const key in errors) {
                            errorMessages += `${errors[key][0]}<br>`;
                        }
                        Toast.fire({
                            type: 'error',
                            title: 'Validation Error',
                            html: errorMessages
                        });
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again later.'
                        });
                    }
                }
            });
        }

        function getStudentInfo(id) {
            LoadingModal.show();

            return $.ajax({
                type: 'GET',
                url: `/tesda/studinfo/get?id=${id}`,
                type: 'GET',
                success: function(data) {
                    console.log('studinfo...', data);
                    LoadingModal.hide();
                },
                error: function(xhr) {
                    console.error(xhr.responseJSON);
                    alert('An error occurred. Please try again.');
                    LoadingModal.hide();
                }
            });
        }

        function fetchStudentList() {
            LoadingModal.show();
            $.ajax({
                url: '/tesda/enrollment/get',
                type: 'POST',
                data: {
                    course_id: $('#filterCourse').val(),
                    course_type: $('#filterCourseType').val(),
                    status: $('#filterStatus').val(),
                    batch_id: $('#filterCourseDuration').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    console.log(data);
                    loadStudentTable(data);
                    LoadingModal.hide();

                    Toast.fire({
                        type: 'success',
                        title: `${data.length} student(s) found`
                    })
                }
            });
        }

        function loadStudentTable(data) {
         
            var totalNotEnrolled = 0;
            var totalEnrolled = 0;
            var totalLateEnrolled = 0;
            var totalDropped = 0;
            var totalTransferredIn = 0;
            var totalTransferredOut = 0;
            var totalWithdrawn = 0;
            var totalDeceased = 0;

            const tbody = $('#studentListBody');
            tbody.empty();

            // Group data by gender
            const groupedData = {
                Male: data.filter(student => student.gender === 'Male'),
                Female: data.filter(student => student.gender === 'Female')
            };

            let tableData = [];

            // Loop through each gender group (Male/Female)
            for (const gender in groupedData) {
                const students = groupedData[gender];

                // Add gender category header row
                tableData.push({
                    genderRow: true,
                    gender: gender,
                    genderText: `<tr>
                                    <td class="padding: 0px"></td>
                                    <td class="${gender == 'Male' ? 'text-primary' : 'text-purple'} text-center">${gender}</td>
                                    <td colspan="6"></td>
                                 </tr>`
                });

                // Add student rows
                students.forEach(student => {
                    let html = '';
                    var statusText = '';
                    var statusColor = 'btn-outline-secondary disabled';
                    switch (student.studentstatusid) {
                        case 0:
                        case null:
                            totalNotEnrolled++;
                            statusColor = refid != 0 && refid == 35 ? 'btn-primary enroll_student' :
                                'btn-outline-primary';
                            statusText = refid != 0 && refid == 35 ? 'Enroll' : 'Not Enrolled';
                            html = '<div class="bg-secondary" style="width:20px;height:20px;margin:0 auto;"></div>';
                            break;
                        case 1:
                            totalEnrolled++;
                            statusColor = 'btn-outline-success disabled';
                            statusText = 'Enrolled';
                            html =
                                '<div style="width:20px;height:20px;background-color:green;margin:0 auto;"></div>';
                            break;
                        case 2:
                            totalLateEnrolled++;
                            statusColor = 'late-enrolled disabled';
                            statusText = 'Late Enrolled';
                            html =
                                '<div style="width:20px;height:20px;background-color:#58715f;margin:0 auto;"></div>';
                            break;
                        case 3:
                            totalDropped++;
                            statusColor = 'btn-outline-danger disabled';
                            statusText = 'Dropped';
                            html = '<div style="width:20px;height:20px;background-color:red;margin:0 auto;"></div>';
                            break;
                        case 4:
                            totalTransferredIn++;
                            statusColor = 'btn-outline-primary disabled';
                            statusText = 'Transferred In';
                            html = '<div class="bg-primary" style="width:20px;height:20px;margin:0 auto;"></div>';
                            break;
                        case 5:
                            totalTransferredOut++;
                            statusColor = 'transferred-out disabled';
                            statusText = 'Transferred Out';
                            html =
                                '<div style="width:20px;height:20px;background-color:#fd7e14;margin:0 auto;"></div>';
                            break;
                        case 6:
                            totalWithdrawn++;
                            statusColor = 'btn-outline-warning disabled';
                            statusText = 'Withdrawn';
                            html = '<div class="bg-warning" style="width:20px;height:20px;margin:0 auto;"></div>';
                            break;
                        case 7:
                            totalDeceased++;
                            statusColor = 'btn-outline-dark disabled';
                            statusText = 'Deceased';
                            html =
                                '<div style="width:20px;height:20px;background-color:black;margin:0 auto;"></div>';
                            break;
                    }

                    // student.can_enroll = 1;

                    tableData.push({
                        id: student.id,
                        genderRow: false,
                        html: html,
                        canEnroll: student.can_enroll,
                        sid: student.sid,
                        batch: student.batch_desc,
                        gender: student.gender,
                        name: `${student.lastname}, ${student.firstname} ${student.middlename}`,
                        course: student.course_name ?? student.initialcoursename,
                        action: refid != 0 && refid == 35 
                            ? `<button class="btn btn-sm ${statusColor}" style="width: 100%; margin:0px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" ${student.can_enroll ? '' : 'disabled'} data-id="${student.id}">${statusText}</button>` 
                            : `<button class="btn btn-sm ${statusColor} disabled" style="width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" data-id="${student.id}">${statusText}</button>`,

                        options: `
                            <div class="row text-center">
                                <div class="col-md-6" style="border-right: 1px solid #ddd">
                                    <a href="#"><i class="text-info fas fa-pencil-alt ${student.can_enroll && refid == 35 || refid == 0 ? 'edit_student' : ''}" data-id="${student.id}" ${student.can_enroll && refid == 35 ? 'disabled' : ''} data-can_enroll="${student.can_enroll}" data-refid="${refid}"></i></a>
                                </div>
                                <div class="col-md-6">
                                    <a href="#"><i class="text-danger far fa-trash-alt delete_student" data-id="${student.id}"></i></a>
                                </div>
                            </div>`
                    });
                });
            }

            $('.totalNotEnrolled').html(totalNotEnrolled);
            $('.totalEnrolled').html(totalEnrolled);
            $('.totalLateEnrolled').html(totalLateEnrolled);
            $('.totalDropped').html(totalDropped);
            $('.totalTransferredIn').html(totalTransferredIn);
            $('.totalTransferredOut').html(totalTransferredOut);
            $('.totalWithdrawn').html(totalWithdrawn);
            $('.totalDeceased').html(totalDeceased);

            // Initialize DataTable
            $('#studentList').DataTable({
                data: tableData,
                destroy: true,
                paging: true,
                searching: true,
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return row.genderRow ? '' :
                                `<div class="text-center">${row.html}</div>`;
                        }
                    }, {
                        data: 'sid',
                        orderable: false,
                        //searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return row.genderRow && row.gender !== undefined ?
                                `<span ${row.gender == 'Male' ? 'class="text-primary"' : 'class="text-purple"'}>${row.gender.toUpperCase()}</span>` :
                                `<div class="text-center">${data}</div>`;
                        }
                    },

                    {
                        data: 'name',
                        orderable: false,
                        //searchable: false,
                        render: function(data, type, row) {
                            return row.genderRow ? '' :
                                `<a href="#" class="clickableStudentInformation" data-id="${row.id}">${data}</a>`;
                        }
                    },
                    {
                        data: 'course',
                        orderable: false,
                        // searchable: false,
                        render: function(data, type, row) {
                            return row.genderRow ? '' : row.course ? row.course :
                                'Not Specified';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        // searchable: false,
                        render: function(data, type, row) {
                            return row.genderRow ? '' : row.batch ?? 'Not Specified';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center p-0',
                        render: function(data, type, row) {
                            return row.genderRow ? '' : row.canEnroll && !row.withpayment ? 
                                `<span class="badge badge-success">Allowed No DP</span>` :  row.canEnroll && row.withpayment ?
                                 `<span class="badge badge-success">PAID DP</span>` :
                                `<span class="badge badge-secondary">N/A</span>`;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'p-1',
                        render: function(data, type, row) {
                            if(refid != 0 && refid == 35){
                                return row.genderRow ? '' : row.canEnroll ?  row.action : 
                                `<button class="btn btn-sm btn-primary" style="width: 100%; margin:0px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" disabled>Not Eligible</button>`;
                            }else{
                                return row.genderRow ? '' : row.action;
                            }
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return row.genderRow ? '' : row.options;
                        }
                    }
                  
                ],
                order: [] // Disable initial sorting
            });
        }
    </script>

    <script>
        const LoadingModal = {
            show: function(message = 'Please wait...') {
                Swal.fire({
                    title: message,
                    html: `
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <!-- Circular Loading Animation -->
                    <div class="spinner"></div>
                    <small style="margin-top: 10px;"><strong>Please wait a moment while the process completes.</strong></small>
                </div>`,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    showCloseButton: false
                });
            },

            hide: function() {
                Swal.close();
            }
        };

        //      Swal.fire({
        //             title: 'Please wait...',
        //             html: `
    // <div class="row" style="justify-content: center !important; display: grid !important;">
    //     <div class="loader1holder2">
    //         <div class="loader2">
    //             <img src="{{ asset('assets/images/plane.gif') }}" alt="Loading..." style="width: 150px; height: 150px;">    
    //         </div>
    //     </div>
    //     <div class="loaderholder">
    //         <div class="loader"></div>
    //     </div>

    //     <div class="note"><small><strong>Please wait a moment while the process completes.</strong></small></div>
    // </div>`,
        //             showConfirmButton: false,
        //             allowOutsideClick: false,
        //             showCloseButton: false
        //         });
    </script>
@endsection
