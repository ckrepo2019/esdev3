@extends('layouts.app')

@section('headerscript')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endsection

@php
    $studentStatus = DB::table('studentstatus')->get();
    $schoolinfo = DB::table('schoolinfo')->first();
@endphp

@section('content')
    <div class="container">

        <!-- Bootstrap Modal -->
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Enter Credentials</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form id="loginForm">
                        <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="row justify-content-center">
            <div class="col-md-2 mt-3">
                <img src="{{ asset($schoolinfo->picurl) }}" alt="School Logo"
                    style="height: 100px; object-fit: contain; object-position: center">
            </div>
            <div class="col-md-10 mt-3">
                <p class="text-white">{{ $schoolinfo->schoolname }} <br>
                    {{ $schoolinfo->address }}</p>
            </div>
        </div>

        <!-- Button to trigger modal -->
        <button type="button"id="openLoginModal" class="btn btn-success">
            <i class="fas fa-key"></i> Enter Credentials
        </button>
  

        <div class="card mt-3">
            <!-- Card Header for Student Name -->
            <div class="card-header d-flex align-items-center">
                <h5 class="mb-0" id="studentName"></h5>
                <span class="badge badge-primary ml-2">Old Student</span>
                <button type="button" class="btn btn-primary btn-sm ml-auto text-white" id="backToLogin">
                    <a href="/" class="text-white">
                    <span aria-hidden="true">Back to login</span>
                    </a>
                </button>
            </div>

            <div class="card-body">
                <div class="row">
                    <!-- Tabbing Card Section -->
                    <div class="col-md-12">
                        <div class="card shadow-none">
                            <!-- Tabs Header -->
                            <div class="card-header ">
                                STUDENT INFORMATION
                            </div>

                            <!-- Tabs Content -->
                            <div class="card-body">
                                <!-- Student Information Tab -->
                                <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="student-info-tab" data-toggle="tab"
                                            href="#student-info" role="tab" aria-controls="student-info"
                                            aria-selected="true">Personal Info</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="parents-tab" data-toggle="tab" href="#parents"
                                            role="tab" aria-controls="enrollment-history"
                                            aria-selected="false">Parents/Guardian Info</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="education-tab" data-id="" data-toggle="tab"
                                            href="#education" role="tab" aria-controls="education"
                                            aria-selected="false">Educational Background</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="medical-tab" data-id="" data-toggle="tab"
                                            href="#medical" role="tab" aria-controls="medical"
                                            aria-selected="false">Medical Info</a>
                                    </li>
                                </ul>
                                <div class="tab-content">

                                    <!-- Student Information -->
                                    <div class="tab-pane fade show active" id="student-info" role="tabpanel"
                                        aria-labelledby="student-info-tab">
                                        <br>
                                        <div class="scrollable-content">
                                            <form style="font-size: 12px;">
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label for="studentIdNo2">Student ID No.</label>
                                                        <input class="form-control form-control-sm" readonly id="sid2"
                                                            placeholder="Student ID No.">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="specializationSelect2">Specialization</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            id="specializationInput2" placeholder="Enter Specialization"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-3">
                                                        <label for="fName" class="form-label-sm">First
                                                            Name</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            id="fName" placeholder="Enter First Name" readonly>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="middleName" class="form-label-sm">Middle
                                                            Name</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            id="mName" placeholder="Enter Middle Name" readonly>
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
                                                            id="dob" placeholder="Enter Date of Birth" readonly>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="placeOfBirth" class="form-label-sm">Place of
                                                            Birth</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            id="pob" placeholder="Enter Place of Birth" readonly>
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
                                                            id="mobileNumber2" placeholder="Enter Mobile Number" readonly>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <label for="emailAddress" class="form-label-sm">Email
                                                            Address</label>
                                                        <input type="email" class="form-control form-control-sm"
                                                            id="emailAddress2" placeholder="Enter Email Address" readonly>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label for="religion" class="form-label-sm">Religion</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            id="studReligion2" placeholder="Enter Religion" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="nationality" class="form-label-sm">Nationality</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            id="nationality2" placeholder="Enter Nationality" readonly>
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
                                                            id="City2" placeholder="Enter City/Municipality" readonly>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="province ">Province </label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            id="province2" placeholder="Enter Province" readonly>
                                                    </div>
                                                </div>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-success" style="float: right;" >Update</button>
                                        </div>
                                    </div>

                                    <!-- Parents Information -->
                                    <div class="tab-pane fade" id="parents" role="tabpanel"
                                        aria-labelledby="parents-tab">
                                        <h5 class="mb-4 mt-3" style="color: black">Parents
                                            Information</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="fatherFirstName">Father's First Name</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="fatherFirstName2" placeholder="Enter Father's First Name"
                                                    readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="fatherMiddleName">Father's Middle Name</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="fatherMiddleName2" placeholder="Enter Father's Middle Name"
                                                    readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="fatherLastName">Father's Last Name</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="fatherLastName2" placeholder="Enter Father's Last Name" readonly>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="fatherSuffix">Suffix</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="fatherSuffix2" placeholder="Enter Suffix" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <label for="fContactNumber">Contact Number</label>
                                                <input type="number" class="form-control form-control-sm"
                                                    id="fContactNumber2" placeholder="Enter Contact Number" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="fOccupation">Occupation</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="fOccupation2" placeholder="Enter Occupation" readonly>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="fHAE">High Educational Attainment</label>
                                                <input type="text" class="form-control form-control-sm" id="fHAE2"
                                                    placeholder="Enter High Educational Attainment" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="fEthnicity">Ethnicity</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="fEthnicity2" placeholder="Enter Ethnicity" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="motherFirstName">Mother's First Name</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="motherFirstName2" placeholder="Enter Mother's First Name"
                                                    readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="motherMiddleName">Mother's Middle Name</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="motherMiddleName2" placeholder="Enter Mother's Middle Name"
                                                    readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="motherLastName">Mother's Last Name</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="motherLastName2" placeholder="Enter Mother's Last Name" readonly>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="motherSuffix">Suffix</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="motherSuffix2" placeholder="Enter Suffix" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <label for="mContactNumber">Contact Number</label>
                                                <input type="number" class="form-control form-control-sm"
                                                    id="mContactNumber2" placeholder="Enter Contact Number" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="mOccupation">Occupation</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="mOccupation2" placeholder="Enter Occupation" readonly>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="mHAE">High Educational Attainment</label>
                                                <input type="text" class="form-control form-control-sm" id="mHAE2"
                                                    placeholder="Enter High Educational Attainment" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="mEthnicity">Ethnicity</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="mEthnicity2" placeholder="Enter Ethnicity" readonly>
                                            </div>
                                        </div>

                                        <hr style="border-color: black" class="mt-3 md-3">
                                        <h5 class="mb-4 mt-3">Guardian Information</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="guardianFirstName">Guardian's First
                                                    Name</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="guardianFirstName2" placeholder="Enter Guardian's First Name"
                                                    readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="guardianMiddleName">Guardian's Middle
                                                    Name</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="guardianMiddleName2" placeholder="Enter Guardian's Middle Name"
                                                    readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="guardianLastName">Guardian's Last Name</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="guardianLastName2" placeholder="Enter Guardian's Last Name"
                                                    readonly>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="guardianSuffix">Suffix</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="guardianSuffix2" placeholder="Enter Suffix" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <label for="gContactNumber">Contact Number</label>
                                                <input type="number" class="form-control form-control-sm"
                                                    id="gContactNumber2" placeholder="Enter Contact Number" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="gOccupation">Occupation</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="gOccupation2" placeholder="Enter Occupation" readonly>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="gHAE">High Educational Attainment</label>
                                                <input type="text" class="form-control form-control-sm" id="gHAE2"
                                                    placeholder="Enter High Educational Attainment" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="gEthnicity">Ethnicity</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="gEthnicity"2 placeholder="Enter Ethnicity" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label style="font-size: 13px !important" class="text-danger mb-0"><span
                                                        class="text-danger">*</span><b>In case of
                                                        emergency ( Recipient for
                                                        News, Announcement and School
                                                        Information)</b></label>
                                            </div>
                                            <div class="col-md-4 pt-1">
                                                <div class="icheck-success d-inline">
                                                    <input class="form-control" type="radio" id="father2"
                                                        name="incase2" value="1" required>
                                                    <label for="father2">Father
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 pt-1">
                                                <div class="icheck-success d-inline">
                                                    <input class="form-control" type="radio" id="mother2"
                                                        name="incase2" value="2" required>
                                                    <label for="mother2">Mother
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 pt-1">
                                                <div class="icheck-success d-inline">
                                                    <input class="form-control" type="radio" id="guardian2"
                                                        name="incase2" value="3" required>
                                                    <label for="guardian2">Guardian
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Educational Background -->
                                    <div class="tab-pane fade" id="education" role="tabpanel"
                                        aria-labelledby="education-tab">
                                        <br>
                                        <div class="row mb-3" style="font-size: 12px">
                                            <div class="col-md-3">
                                                <label for="lastSchoolAttended">Last School
                                                    Attended</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="lastSchoolAttended2" placeholder="Enter Last School Attended"
                                                    readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="lastGradeLevelCompleted">Last Grade Level
                                                    Completed</label>
                                                <select class="form-control form-control-sm" id="lastGradeLevelCompleted2"
                                                    readonly>
                                                    <option value="" selected disabled hidden>Last Grade Level
                                                        Completed</option>
                                                    @foreach(DB::table('gradelevel')->orderBy('sortid')->where('deleted', '0')->get() as $gradelevel)
                                                        <option value="{{ $gradelevel->id }}">{{ $gradelevel->levelname }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="schoolContactNo">School's Contact No.</label>
                                                <input type="number" class="form-control form-control-sm"
                                                    id="schoolContactNo2" placeholder="Enter School's Contact No."
                                                    readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="schoolMailingAddress">Last School Mailing
                                                    Address</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="schoolMailingAddress2"
                                                    placeholder="Enter Last School Mailing Address" readonly>
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
                                                    id="preSchoolName2" placeholder="Enter Pre-School Name" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control form-control-sm"
                                                    id="preSchoolYearGraduated2"
                                                    placeholder="Enter Pre-School Year Graduated" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control form-control-sm"
                                                    id="preSchoolType2" placeholder="Enter Pre-School Type" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="gradeSchool">Grade School</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control form-control-sm"
                                                    id="gradeSchoolName2" placeholder="Enter Grade School Name" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control form-control-sm"
                                                    id="gradeSchoolYearGraduated2"
                                                    placeholder="Enter Grade School Year Graduated" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control form-control-sm"
                                                    id="gradeSchoolType2" placeholder="Enter Grade School Type" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="juniorHighSchool">Junior High School</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control form-control-sm"
                                                    id="juniorHighSchoolName2" placeholder="Enter Junior High School Name"
                                                    readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control form-control-sm"
                                                    id="juniorHighSchoolYearGraduated2"
                                                    placeholder="Enter Junior High School Year Graduated" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control form-control-sm"
                                                    id="juniorHighSchoolType2" placeholder="Enter Junior High School Type"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="seniorHighSchool">Senior High School</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control form-control-sm"
                                                    id="seniorHighSchoolName2" placeholder="Enter Senior High School Name"
                                                    readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control form-control-sm"
                                                    id="seniorHighSchoolYearGraduated2"
                                                    placeholder="Enter Senior High School Year Graduated" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control form-control-sm"
                                                    id="seniorHighSchoolType2" placeholder="Enter Senior High School Type"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Medical -->
                                    <div class="tab-pane fade" id="medical" role="tabpanel"
                                        aria-labelledby="medical-tab">
                                        <br>
                                        <div class="row mb-3">
                                            <div class="col-md-2">
                                                <label for="Height2">Height (Meters)</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control form-control-sm" id="Height2"
                                                    placeholder="Enter Height (Meters)" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="Weight">Weight (kgs)</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control form-control-sm" id="Weight2"
                                                    placeholder="Enter Weight (kgs)" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="otherMed">Any Current Medications, Specify
                                                </label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control form-control-sm" id="otherMed2"
                                                    placeholder="Enter Medications, Specify" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-2">
                                                <label for="anyAllergies">Any Allergies</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control form-control-sm"
                                                    id="anyAllergies2" placeholder="Enter Any Allergies" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="medAllergies"> Medications to
                                                    allergies</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control form-control-sm"
                                                    id="medAllergies2" placeholder="Enter Medications to allergies"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-2">
                                                <label for="medHistory">Medical History</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control form-control-sm"
                                                    id="medHistory2" placeholder="Enter Medical History" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="otherMedInfo">Other Medical
                                                    Information</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control form-control-sm"
                                                    id="otherMedInfo2" placeholder="Enter Other Medical Information"
                                                    readonly>
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
@endsection

@section('footerscript')
    <script>

        $(document).ready(function() {
            $('#loginModal').modal('show')

            $('#openLoginModal').on('click', function() {
                $('#loginModal').modal('show');
            });

            $('#loginForm').on('submit', function(event) {
                event.preventDefault();
                var username = $('#username').val();
                var password = $('#password').val();
                
                $.ajax({
                    url: '/checkcredentials',
                    type: 'POST',
                    contentType: 'application/json',
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify({ username: username, password: password }),
                    success: function(data) {
                        if (data.success) {
                            $('#loginModal').modal('hide');

                            Swal.fire({
                                type: 'success',
                                title: 'Login successful!',
                                text: 'Welcome, ' + data.user.name + '!'
                            }).then(function() {
                                console.log('User:', data.user);
                                populatePersonalInfo(data.user);
                            });
                        } else {
                            Swal.fire({
                                type: 'error',
                                title: 'Invalid credentials!',
                                text: 'Please try again.'
                            });
                        }
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            });
        });

        function populatePersonalInfo(data) {
            $('#studentName').text(data.name);
            $('#sid2').val(data.sid);
            $('#fName').val(data.firstname);
            $('#mName').val(data.middlename);
            $('#lName').val(data.lastname);
            $('#suffix').val(data.suffix);
            $('#dob').val(data.dob);
            $('#pob').val(data.pob);
            $('#age').val(data.age);
            $('#gender2').val(data.gender);
            $('#mobileNumber2').val(data.contactno);
            $('#emailAddress2').val(data.semail);
            $('#studReligion2').val(data.religionname);
            $('#nationality2').val(data.nationality);
            $('#street2').val(data.street);
            $('#barangay2').val(data.barangay);
            $('#City2').val(data.city);
            $('#province2').val(data.province);

            // Parent Info
            $('#fatherFirstName2').val(data.ffname);
            $('#fatherMiddleName2').val(data.fmname);
            $('#fatherLastName2').val(data.flname);
            $('#fatherSuffix2').val(data.fsuffix);
            $('#fContactNumber2').val(data.fcontactno);
            $('#fOccupation2').val(data.foccupation);
            $('#fHAE2').val(data.fea);
            $('#fEthnicity2').val(data.fethnicity);

            $('#motherFirstName2').val(data.mfname);
            $('#motherMiddleName2').val(data.mmname);
            $('#motherLastName2').val(data.mlname);
            $('#motherSuffix2').val(data.msuffix);
            $('#mContactNumber2').val(data.mcontactno);
            $('#mOccupation2').val(data.moccupation);
            $('#mHAE2').val(data.mea);
            $('#mEthnicity2').val(data.methnicity);

            $('#guardianFirstName2').val(data.gfname);
            $('#guardianMiddleName2').val(data.gmname);
            $('#guardianLastName2').val(data.glname);
            $('#guardianSuffix2').val(data.gsuffix);
            $('#gContactNumber2').val(data.gcontactno);
            $('#gOccupation2').val(data.goccupation);
            $('#gHAE2').val(data.gea);
            $('#gEthnicity2').val(data.gethnicity);

            // Incase Emergency
            $('#father2').prop('checked', data.isfathernum == 1);
            $('#mother2').prop('checked', data.ismothernum == 1);
            $('#guardian2').prop('checked', data.isguardannum == 1);
            

            // Educational Background
            $('#lastSchoolAttended2').val(data.lastschoolatt);
            $('#lastGradeLevelCompleted2').val(data.glits);
            $('#schoolContactNo2').val(data.scn);
            // $('#schoolMailingAddress2').val(data.saddress);

            // $('#preSchoolName2').val();
            $('#gradeSchoolName2').val(data.gsschoolname);
            $('#juniorHighSchoolName2').val(data.jhsschoolname);
            $('#seniorHighSchoolName2').val(data.shsschoolname);

            
            // Medical Background
            $('#allergies2').val(data.allergies);
            $('#medications2').val(data.medications);
            $('#diseases2').val(data.diseases);
        }


    </script>

@endsection
