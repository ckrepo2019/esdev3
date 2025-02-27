@extends('tesda.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    {{-- <link rel="stylesheet" id="css-main" href="{{ asset('/css/oneui.css') }}"> --}}
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: unset !important;
        }

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

        #enrollmentForm select,
        #enrollmentForm input {
            font-size: 0.9rem;
            padding: 0.4rem 0.6rem;
        }

        #enrollmentForm input[disabled] {
            background-color: #e9ecef;
            color: #6c757d;
        }
        
        #studentList {
            font-size: 12px !important;
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

    $signatories = DB::table('tesda_course_signatories')
        ->where('deleted', 0)
        ->get()

        // dd($signatories);

@endphp

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> <i class="nav-icon fas fa-cog"></i> Enrollment Summary</h1>
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Enrollment Summary</li>
                    </ol>
                </div>

            </div>
        </div>
    </section>

    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="studentInfoModalLabel" aria-hidden="true"
        data-backdrop="static">
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
                                            <input type="text" class="form-control form-control-sm" id="anyAllergies"
                                                placeholder="Enter Any Allergies">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="medAllergies"> Medications to allergies</label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control form-control-sm" id="medAllergies"
                                                placeholder="Enter Medications to allergies">
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
                                            <input type="text" class="form-control form-control-sm" id="otherMedInfo"
                                                placeholder="Enter Other Medical Information">
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
                                        <select class="form-control select2" id="input_specializationEdit" required>
                                            <option value="" selected disabled>Select...</option>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="batch" class="form-label">Batch</label>
                                        <select class="form-control select2" id="batchEdit" required>
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
                                        <select class="form-control select2" id="statusEdit" required>
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
                    <button type="button" id="updateEnrollment" data-purpose="update"
                        class="btn btn-success">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="enrollmentSummaryModal" tabindex="-1" aria-labelledby="enrollmentSummaryLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enrollmentSummaryLabel"><i class="fas fa-chart-bar mr-2"></i>Enrollment
                        Summary</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="courseSpecialization" class="form-label">Course/Specialization</label>
                            <select id="courseSpecialization" class="form-control select2 form-control-sm"
                                style="width: 100%;">
                                <option value="">All</option>
                                @foreach ($courses as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->course_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="courseType" class="form-label">Course Type</label>
                            <select id="courseType" class="form-control select2 form-control-sm" style="width: 100%;">
                                <option value="">All</option>
                                @foreach (DB::table('tesda_course_type')->get() as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="courseDuration" class="form-label">Course Duration</label>
                            <select id="courseDuration" class="form-control select2 form-control-sm"
                                style="width: 100%;">
                                <option value="">All</option>
                                @foreach (DB::table('tesda_batches')->select('tesda_batches.id', DB::raw("CONCAT_WS(' - ', DATE_FORMAT(tesda_batches.date_from, '%m/%d/%Y'), DATE_FORMAT(tesda_batches.date_to, '%m/%d/%Y')) AS date_range"))->get() as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->date_range }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="dateRange" class="form-label">Date Range</label>
                            <input type="date" id="dateRange" class="form-control form-control-sm">
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Course/Specialization</th>
                                    <th>Course Type</th>
                                    <th>Batch</th>
                                    <th>Male</th>
                                    <th>Female</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="summaryTable">
                                <tr>
                                    <td colspan="6" class="text-center" style="color: gray"> Empty data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Signatories -->
                    <div class="mt-4">
                        <h6>Signatories</h6>
                        <div class="row">
                            @foreach ($signatories as $item)
                                <div class="col-md-4">
                                    <p><strong>{{ $item->description ?? 'Prepared By'}}:</strong><br>
                                        <span style="text-decoration: underline">{{ $item->signatory_name }}</span>
                                        <br><small>{{$item->signatory_title}}</small>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger">Import PDF</button>
                    <button type="button" class="btn btn-success">Import Excel</button>
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
                                    <select name="" id="filterStatus" class="form-control select2 form-control-sm"
                                        style="width: 100%;">
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
                        <div class="card-header">
                            <h3 class="card-title">Student List</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {{-- <div class="col-md-6 mb-21">
                                    <button class="btn btn-sm btn-success" id="addNewStudent" data-toggle="modal"
                                        data-target="#addStudentModal">+ Add New Student</button>
                                </div> --}}
                                <div class="col-md-12 mb-2 text-right">
                                    <button class="btn btn-sm btn-primary" id="summaryModal" data-toggle="modal"
                                        data-target="#enrollmentSummaryModal"> Enrollment Summary</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="studentList"
                                            class="table table-bordered table-valign-middle table-hover"
                                            width="100%">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th width="5%"></th>
                                                    <th width="10%">Student ID</th>
                                                    <th width="25%">Student Name</th>
                                                    <th width="25%">Specialization</th>
                                                    <th width="15%">Batch</th>
                                                    <th width="15%">Enrollment Date</th>
                                                    <th width="5%" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="studentListBody" a>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row" style="font-size: 12px">
                                <div class="col-md-12">
                                    |
                                    @foreach (DB::table('studentstatus')->get() as $item)
                                        @switch($item->id)
                                            @case(0)
                                                <a href="#" class="badge_stat text-secondary"
                                                    data-id="{{ $item->id }}"><span class="badge">{{ $item->description }}
                                                        (<span class="badge_stat_count totalNotEnrolled"
                                                            data-id="{{ $item->id }}">0</span>)
                                                    </span> </a> |
                                            @break

                                            @case(1)
                                                <a href="#" class="badge_stat text-success"
                                                    data-id="{{ $item->id }}"><span class="badge">{{ $item->description }}
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
                                                    data-id="{{ $item->id }}"><span class="badge">{{ $item->description }}
                                                        (<span class="badge_stat_count totalDropped"
                                                            data-id="{{ $item->id }}">0</span>)
                                                    </span> </a> |
                                            @break

                                            @case(4)
                                                <a href="#" class="badge_stat text-primary"
                                                    data-id="{{ $item->id }}"><span class="badge">{{ $item->description }}
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
                                                    data-id="{{ $item->id }}"><span class="badge">{{ $item->description }}
                                                        ( <span class="badge_stat_count totalWithdrawn"
                                                            data-id="{{ $item->id }}">0</span>
                                                        )</span> </a> |
                                            @break

                                            @case(7)
                                                <a href="#" class="badge_stat text-dark"
                                                    data-id="{{ $item->id }}"><span class="badge">{{ $item->description }}
                                                        ( <span class="badge_stat_count totalDeceased"
                                                            data-id="{{ $item->id }}">0</span>
                                                        )</span> </a> |
                                            @break

                                            @default
                                                <a href="#" class="badge_stat text-secondary"
                                                    data-id="{{ $item->id }}"><span class="badge">{{ $item->description }}
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
        var currentStudId = '';
        $(document).ready(function() {
            $('.select2').select2();
            fetchStudentList();

            $('#summaryModal').on('click', function() {
                fetchEnrollmentSummary();
            });

            $('#courseSpecialization, #courseType, #courseDuration').on('change', function() {
                fetchEnrollmentSummary();
            });

            function fetchEnrollmentSummary() {
                LoadingModal.show();
                $.ajax({
                    url: '/tesda/enrollmentsummary',
                    type: 'GET',
                    data: {
                        course_id: $('#courseSpecialization').val(),
                        course_type: $('#courseType').val(),
                        duration: $('#courseDuration').val(),
                        // _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        console.log(data);
                        LoadingModal.hide();
                        $('#summaryTable').empty()
                        data.forEach(element => {
                            $('#summaryTable').append(
                                ` <tr>
                                    <td>${element.course_name ?? ''}</td>
                                    <td>${element.description ?? ''}</td>
                                    <td>${element.batch ?? ''}</td>
                                    <td class="text-center">${element.male ?? 0}</td>
                                    <td class="text-center">${element.female ?? 0}</td>
                                    <td class="text-center">${element.total ?? 0}</td>
                                </tr>`
                            )
                        }) 

                        
                    }
                });
            }

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
                            `${data.lastname}, ${data.firstname} ${data.middlename[0].toUpperCase()}.`;
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

            $(document).on('click', '#printCOR', function() {
                if (currentStudId != '') {
                    console.log('CORid...', currentStudId);
                    window.open(`/tesda/printCOR/${currentStudId}`, '_blank')
                }
            })

            $(document).on('click', '.edit_student', function() {
                const studentId = $(this).data('id');

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
                            `${data.lastname}, ${data.firstname} ${data.middlename[0].toUpperCase()}.`;
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

                    }

                    $('#enrollmentModalEdit').modal('show');
                });

            })


            $(document).on('click', '.delete_student', function() {
                const studentId = $(this).data('id');

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

        });

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
                                    <td></td>
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
                            statusColor = 'btn-primary enroll_student';
                            statusText = 'Enroll';
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

                    student.can_enroll = 1;

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
                        action: new Date(student.dateenrolled).toLocaleString('default', {
                            month: 'short',
                            day: 'numeric',
                            year: 'numeric'
                        }),
                        options: `
                            <div class="row text-center">
                                <div class="col-md-6" style="border-right: 1px solid #ddd">
                                    <a href="#"><i class="text-info fas fa-pencil-alt ${student.can_enroll ? 'edit_student' : ''}" data-id="${student.id}" ${student.can_enroll ? '' : 'disabled'} ></i></a>
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
                        searchable: false,
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
                        searchable: false,
                        render: function(data, type, row) {
                            return row.genderRow ? '' :
                                `<a href="/tesda/studentInfo?id=${row.id}" class="clickableStudentInformation" data-id="${row.id}">${data}</a>`;
                        }
                    },
                    {
                        data: 'course',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return row.genderRow ? '' : row.course ? row.course : 'Not Specified';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return row.genderRow ? '' : row.batch ?? 'Not Specified';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return row.genderRow ? '' : row.action;
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
