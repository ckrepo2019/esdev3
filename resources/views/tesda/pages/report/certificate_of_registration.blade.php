@extends('tesda.layouts.app2')

@section('pagespecificscripts')
@endsection

@section('content')
    <style>
        .scrollable-content {
            max-height: calc(100vh - 240px);
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .scrollable-content::-webkit-scrollbar {
            display: none;
        }

        #studentList {
            font-size: 12px !important;
        }
    </style>
    @php
        $nationality = DB::table('nationality')->where('deleted', 0)->get();
        $religion = DB::table('religion')->where('deleted', 0)->get();
        $studentStatus = DB::table('studentstatus')->get();
        $batch = DB::table('tesda_batches')->where('deleted', 0)->get();
        $specialization = DB::table('tesda_courses')->where('deleted', 0)->get();

    @endphp
    <section class="content-header">
        <div class="container-fluid">
            <h1><i class="fas fa-cog"></i>Certificate of Registration</h1>
            <ol class="breadcrumb float-sm-left">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">{{ isset($page) ? $page : 'Certificate of Registration' }}</li>
            </ol>
        </div>
    </section><br>

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
                                        @foreach (DB::table('tesda_courses')->get() as $item)
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
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-gray">
                    <h3 class="card-title"><i class="fas fa-list"></i> Student List</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12 text-right">
                            <button style="border-radius: 7px" class="btn btn-sm btn-primary float-right" id="btn_print_all_cor">
                                <i class="fas fa-print"></i> Print All COR
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="studentList" class="table table-bordered table-valign-middle table-hover w-100">
                                    <thead class="thead-light">
                                        <tr>
                                            {{-- <th></th> --}}
                                            <th width="5">Student ID</th>
                                            <th width="35">Student Name</th>
                                            <th width="35">Specialization</th>
                                            <th width="20">Batch</th>
                                            {{-- <th class="text-center">Status</th> --}}
                                            <th width="5" class="text-center">Action</th>
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
                                        <a href="#" class="badge_stat text-secondary" data-id="{{ $item->id }}"><span
                                                class="badge">{{ $item->description }}
                                                (<span class="badge_stat_count totalNotEnrolled"
                                                    data-id="{{ $item->id }}">0</span>)
                                            </span> </a> |
                                    @break

                                    @case(1)
                                        <a href="#" class="badge_stat text-success" data-id="{{ $item->id }}"><span
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
                                        <a href="#" class="badge_stat text-danger" data-id="{{ $item->id }}"><span
                                                class="badge">{{ $item->description }}
                                                (<span class="badge_stat_count totalDropped"
                                                    data-id="{{ $item->id }}">0</span>)
                                            </span> </a> |
                                    @break

                                    @case(4)
                                        <a href="#" class="badge_stat text-primary" data-id="{{ $item->id }}"><span
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
                                        <a href="#" class="badge_stat text-warning" data-id="{{ $item->id }}"><span
                                                class="badge">{{ $item->description }}
                                                ( <span class="badge_stat_count totalWithdrawn"
                                                    data-id="{{ $item->id }}">0</span>
                                                )</span> </a> |
                                    @break

                                    @case(7)
                                        <a href="#" class="badge_stat text-dark" data-id="{{ $item->id }}"><span
                                                class="badge">{{ $item->description }}
                                                ( <span class="badge_stat_count totalDeceased"
                                                    data-id="{{ $item->id }}">0</span>
                                                )</span> </a> |
                                    @break

                                    @default
                                        <a href="#" class="badge_stat text-secondary" data-id="{{ $item->id }}"><span
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
                                                {{ $studentStatus->where('id', 0)->first()->description }}</td>
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
                                <form style="font-size: 12px;">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="studentIdNo">Student ID No.</label>
                                            <input type="number" class="form-control form-control-sm" id="studentIdNo"
                                                placeholder="Student ID No.">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="specializationSelect">Specialization</label>
                                            <select class="form-control select2 specialization" style="width: 100%;"
                                                id="specializationSelect">
                                                <option value="">Select</option>
                                                @foreach ($specialization as $spec)
                                                    <option value="{{ $spec->id }}">{{ $spec->course_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="firstName" class="form-label-sm">First Name</label>
                                            <input type="text" class="form-control form-control-sm" id="firstName"
                                                placeholder="Enter First Name">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="middleName" class="form-label-sm">Middle Name</label>
                                            <input type="text" class="form-control form-control-sm" id="middleName"
                                                placeholder="Enter Middle Name">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="lastName" class="form-label-sm">Last Name</label>
                                            <input type="text" class="form-control form-control-sm" id="lastName"
                                                placeholder="Enter Last Name">
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
                                                placeholder="Enter Date of Birth">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="placeOfBirth" class="form-label-sm">Place of Birth</label>
                                            <input type="text" class="form-control form-control-sm" id="placeOfBirth"
                                                placeholder="Enter Place of Birth">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="gender" class="form-label-sm">Gender</label>
                                            <select class="form-select select2" id="gender" style="width: 100%;">
                                                <option value="">Select Gender</option>
                                                <option value="Female">Female</option>
                                                <option value="Male">Male</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="age" class="form-label-sm">Age</label>
                                            <input type="text" class="form-control form-control-sm" id="age"
                                                placeholder="Enter Age">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="mobileNumber" class="form-label-sm">Mobile Number</label>
                                            <input type="number" class="form-control form-control-sm" id="mobileNumber"
                                                placeholder="Enter Mobile Number">
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
                                            <select class="form-control form-control-sm select2" id="religion"
                                                style="width: 100%">
                                                <option value="">Select Religion</option>
                                                @foreach ($religion as $religion)
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
                                                @foreach ($nationality as $nationality)
                                                    <option value="{{ $nationality->id }}">
                                                        {{ $nationality->nationality }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="street">Street</label>
                                            <input type="text" class="form-control form-control-sm" id="street"
                                                placeholder="Enter Street">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="barangay">Barangay</label>
                                            <input type="text" class="form-control form-control-sm" id="barangay"
                                                placeholder="Enter Barangay">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="City">City/Municipality</label>
                                            <input type="text" class="form-control form-control-sm" id="City"
                                                placeholder="Enter City/Municipality">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="province ">Province </label>
                                            <input type="text" class="form-control form-control-sm" id="province "
                                                placeholder="Enter Province">
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
                                            <input type="number" class="form-control form-control-sm"
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
                                            <input type="number" class="form-control form-control-sm"
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
                                            <input type="number" class="form-control form-control-sm"
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
    {{-- <!-- Modal -->
    <div class="modal fade" id="enrollmentModal" tabindex="-1" aria-labelledby="enrollmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enrollmentModalLabel">Balancar, Marie Boy L.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
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
                                        <td id="studentIdNumbers"></td>
                                    </tr>
                                    <tr>
                                        <th>Specialization</th>
                                        <td id="specializations"></td>
                                    </tr>
                                    <tr>
                                        <th>Student Status</th>
                                        <td id="studentstatus"></td>
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
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach (DB::table('tesda_courses')->get() as $course)
                                                <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="batch" class="form-label">Batch</label>
                                        <select class="form-control select2" id="batchs" required>
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach ($batch as $batch)
                                                <option value="{{ $batch->id }}">{{ $batch->batch_desc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="enrollmentDate" class="form-label">Date of Enrollment</label>
                                        <input type="text" class="form-control form-control-sm" id="enrollmentDate"
                                            value="N/A" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <div id="specializationContainer">
                                            <label for="enrollmentDate" class="form-label">Status</label>
                                            <select class="form-control select2" id="status" style="width: 100%">
                                                <option value="" selected disabled>Choose...</option>
                                                @foreach (DB::table('studentstatus')->get() as $status)
                                                    <option value="{{ $status->id }}">{{ $status->description }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" data-id="" id="enrollButton" class="btn btn-primary">Enroll</button>
                    <button type="button" data-id="" id="updateEnrollButton"
                        class="btn btn-success">Update</button>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="modal fade" id="studentInfoTab" tabindex="-1" aria-labelledby="studentInfoTab" aria-hidden="true">
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
                                <div class="card">
                                    <img id="studentPhoto" src="{{ asset('avatar/S(F) 1.png') }}" alt="Student Photo"
                                        class="card-img-top"
                                        style="height: 240px; object-fit: cover; object-position: center; border-radius: 10%; padding:10px">
                                    <div class="card-body">
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
                                            <button id="updateCourse" class="btn btn-success btn-sm">Update Profile
                                                Picture</button>
                                        </div>
                                    </div>
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
                                                <a class="nav-link" id="registration-tab" data-id=""
                                                    data-toggle="tab" href="#registration" role="tab"
                                                    aria-controls="registration" aria-selected="false">Certificate of
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
                                                                <label for="studentIdNo">Student ID No.</label>
                                                                <input type="number" class="form-control form-control-sm"
                                                                    readonly id="sid" placeholder="Student ID No.">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="specializationSelect">Specialization</label>
                                                                <select class="form-control select2 specialization"
                                                                    style="width: 100%;" id="specializationSelect2"
                                                                    readonly>
                                                                    <option value="">Select</option>
                                                                    @foreach ($specialization as $spec)
                                                                        <option value="{{ $spec->id }}">
                                                                            {{ $spec->course_name }}</option>
                                                                    @endforeach
                                                                </select>
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
                                                                <select class="form-select select2" id="gender2"
                                                                    style="width: 100%;">
                                                                    <option value="">Select Gender</option>
                                                                    <option value="Female">Female</option>
                                                                    <option value="Male">Male</option>
                                                                </select>
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
                                                                <select class="form-control form-control-sm select2"
                                                                    id="studReligion2" style="width: 100%" readonly>
                                                                    <option value="">Select Religion</option>
                                                                    {{-- @foreach ($religion as $religion)
                                                                        <option value="{{ $religion->id }}">{{ $religion->religionname }}
                                                                        </option>
                                                                    @endforeach --}}
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="nationality"
                                                                    class="form-label-sm">Nationality</label>
                                                                <select class="form-control form-control-sm select2"
                                                                    id="nationality2" style="width: 100%" readonly>
                                                                    <option value="">Select Nationality</option>
                                                                    {{-- @foreach ($nationality as $nationality)
                                                                        <option value="{{ $nationality->id }}">{{ $nationality->nationality }}
                                                                        </option>
                                                                    @endforeach --}}
                                                                </select>
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
                                                        <hr style="border-color: black" class="mt-3 md-3">
                                                        <h5 class="mb-4 mt-3">Educational Information</h5>
                                                        <div class="row mb-3" style="font-size: 12px">
                                                            <div class="col-md-3">
                                                                <label for="lastSchoolAttended">Last School
                                                                    Attended</label>
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    id="lastSchoolAttended2"
                                                                    placeholder="Enter Last School Attended" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="lastGradeLevelCompleted">Last Grade Level
                                                                    Completed</label>
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    id="lastGradeLevelCompleted2"
                                                                    placeholder="Enter Last Grade Level Completed"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="schoolContactNo">School's Contact No.</label>
                                                                <input type="number"
                                                                    class="form-control form-control-sm"
                                                                    id="schoolContactNo2"
                                                                    placeholder="Enter School's Contact No." readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="schoolMailingAddress">Last School Mailing
                                                                    Address</label>
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
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
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    id="preSchoolName2"
                                                                    placeholder="Enter Pre-School Name" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    id="preSchoolYearGraduated2"
                                                                    placeholder="Enter Pre-School Year Graduated"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    id="preSchoolType2"
                                                                    placeholder="Enter Pre-School Type" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-3">
                                                                <label for="gradeSchool">Grade School</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    id="gradeSchoolName2"
                                                                    placeholder="Enter Grade School Name" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    id="gradeSchoolYearGraduated2"
                                                                    placeholder="Enter Grade School Year Graduated"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    id="gradeSchoolType2"
                                                                    placeholder="Enter Grade School Type" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-3">
                                                                <label for="juniorHighSchool">Junior High School</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    id="juniorHighSchoolName2"
                                                                    placeholder="Enter Junior High School Name" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    id="juniorHighSchoolYearGraduated2"
                                                                    placeholder="Enter Junior High School Year Graduated"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    id="juniorHighSchoolType2"
                                                                    placeholder="Enter Junior High School Type" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-3">
                                                                <label for="seniorHighSchool">Senior High School</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    id="seniorHighSchoolName2"
                                                                    placeholder="Enter Senior High School Name" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    id="seniorHighSchoolYearGraduated2"
                                                                    placeholder="Enter Senior High School Year Graduated"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
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
                                                                <input type="text"
                                                                    class="form-control form-control-sm" id="Height2"
                                                                    placeholder="Enter Height (Meters)" readonly>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="Weight">Weight (kgs)</label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="text"
                                                                    class="form-control form-control-sm" id="Weight2"
                                                                    placeholder="Enter Weight (kgs)" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-4">
                                                                <label for="otherMed">Any Current Medications, Specify
                                                                </label>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="text"
                                                                    class="form-control form-control-sm" id="otherMed2"
                                                                    placeholder="Enter Medications, Specify" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-2">
                                                                <label for="anyAllergies">Any Allergies</label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    id="anyAllergies2" placeholder="Enter Any Allergies"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="medAllergies"> Medications to
                                                                    allergies</label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    id="medAllergies2"
                                                                    placeholder="Enter Medications to allergies" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-2">
                                                                <label for="medHistory">Medical History</label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    id="medHistory2" placeholder="Enter Medical History"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="otherMedInfo">Other Medical
                                                                    Information</label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    id="otherMedInfo2"
                                                                    placeholder="Enter Other Medical Information"
                                                                    readonly>
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
                                            @foreach (DB::table('tesda_courses')->get() as $course)
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
                                    <button id="updateCourse" class="btn btn-primary btn-sm">Print COR</button>
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
                                            @foreach (DB::table('tesda_courses')->get() as $course)
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
@endsection

@section('footerjavascript')
    <script>
        document.getElementById('studentIdNo').addEventListener('input', function() {
            const studentIdInput = this.value.trim();
            document.getElementById('studentIdNumber').textContent = studentIdInput || '[Not set]';
        });

        $(document).ready(function() {
            $('.specialization').select2();

            $('#specializationSelect').on('change', function() {
                const specializationDropdown = $(this);
                const specializationText = specializationDropdown.find('option:selected').text();
                const specializationValue = specializationDropdown.val();
                document.getElementById('specializationText').textContent = specializationValue ?
                    specializationText : '[Not set]';
            });
        });
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

        $(document).ready(function() {
            $('.select2').select2();
            getStudents();

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
                                    getStudents(); // Refresh the list
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
                getStudents();
            })

            $('#filterCourse').on('change', function() {
                getStudents();

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
                            getStudents();
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


            function activateTab(tabId = "#student-info") {
                $('#custom-tabs a[href="' + tabId + '"]').tab('show');
                localStorage.setItem("activeTab", tabId);
            }

            let storedTab = localStorage.getItem("activeTab");
            if (storedTab) {
                activateTab(storedTab);
            } else {
                activateTab("#student-info");
            }
            $('#custom-tabs a').on('click', function() {
                let tabId = $(this).attr('href');
                activateTab(tabId);
            });

            $(document).on('click', '.clickableStudentInformation', function() {
                const studentId = $(this).data('id');
                $('#studentInfoTab').modal('show');
                $('#registration-tab').attr('data-id', studentId);
                $('#enrollment-history-tab').attr('data-id', studentId);
                $('#student-info-tab').attr('data-id', studentId);
                activateTab("#student-info");
            });

            $(document).on('click', '.student-row', function() {
                let newStudentId = $(this).data('id');
                if (newStudentId) {
                    activateTab("#student-info");
                    console.log("Switched to student ID:", newStudentId);
                }
            });
            // $(document).on('click', '.enroll_student', function() {
            //     const studentId = $(this).data('id');
            //     $('#enrollmentModal').modal('show');
            //     $('#enrollButton').attr('data-id', studentId);
            //     $('#updateEnrollButton').attr('data-id', studentId);
            // })

            $(document).on('click', '.clickableStudentInformation', function() {
                const studentId = $(this).data('id');
                $('#studentInfoTab').modal('show');
                $('#registration-tab').attr('data-id', studentId);
                $('#registration-tab').on('click', function() {
                    $('#registration').attr('data-id', studentId);
                    $('#input_specialization').attr('data-id', courseid);
                    $('#batchs').attr('data-id', batchid);
                })
                $('#enrollment-history-tab').attr('data-id', studentId);
                $('#student-info-tab').attr('data-id', studentId);

            })

            $('.select2').select2()

            $('#add_student_info').on('click', function() {
                let isValid = true;

                const studentData = {
                    studentIdNo: $('#studentIdNo').val(),
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

                };

                // Send AJAX request to add the student
                $.ajax({
                    url: '/tesda/addStudent',
                    type: 'POST',
                    data: studentData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Student information added successfully!',
                                icon: 'success',
                                confirmButtonText: 'Okay',
                            }).then((result) => {
                                if (result.value) {
                                    $('input').val('');
                                    getStudents();
                                }
                            });
                        } else {
                            alert('Failed to add student information.');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseJSON);
                        alert('An error occurred. Please try again.');
                    },
                });
            });

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
                                html =
                                    '<div class="bg-secondary" style="width:20px;height:20px;margin:0 auto;"></div>';
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
                                html =
                                    '<div style="width:20px;height:20px;background-color:red;margin:0 auto;"></div>';
                                break;
                            case 4:
                                totalTransferredIn++;
                                statusColor = 'btn-outline-primary disabled';
                                statusText = 'Transferred In';
                                html =
                                    '<div class="bg-primary" style="width:20px;height:20px;margin:0 auto;"></div>';
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
                                html =
                                    '<div class="bg-warning" style="width:20px;height:20px;margin:0 auto;"></div>';
                                break;
                            case 7:
                                totalDeceased++;
                                statusColor = 'btn-outline-dark disabled';
                                statusText = 'Deceased';
                                html =
                                    '<div style="width:20px;height:20px;background-color:black;margin:0 auto;"></div>';
                                break;
                        }

                        tableData.push({
                            id: student.id,
                            genderRow: false,
                            html: html,
                            sid: student.sid,
                            batch: student.batch_desc,
                            gender: student.gender,
                            name: `${student.lastname}, ${student.firstname} ${student.middlename}`,
                            course: student.course_name ?? student.initialcoursename,
                            action: `
                            <button class="btn btn-sm ${statusColor}" style="width: 100%;" data-id="${student.id}">${statusText}</button>`,
                            options: `
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <a href="#"><p class="text-primary mb-0 print-cor" data-id="${student.id}">Print COR</p></a>
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
                    columns: [
                        // {
                        //     data: null,
                        //     orderable: false,
                        //     searchable: false,
                        //     render: function(data, type, row) {
                        //         return row.genderRow ? '' :
                        //             `<div class="text-center">${row.html}</div>`;
                        //     }
                        // },
                        {
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
                                    `<a href="#" class="clickableStudentInformation" data-id="${row.id}">${data}</a>`;
                            }
                        },
                        {
                            data: 'course',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                return row.genderRow ? '' : row.course ? row.course :
                                    'Not Specified';
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
                        // {
                        //     data: null,
                        //     orderable: false,
                        //     searchable: false,
                        //     render: function(data, type, row) {
                        //         return row.genderRow ? '' : row.action;
                        //     }
                        // },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                return row.genderRow ? '' :
                                    `<span style="white-space: nowrap">${row.options}</span>`;
                            }
                        }
                    ],
                    order: [] // Disable initial sorting
                });
            }

            function getStudents() {
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
                        console.log('Enrolledstud...', data);
                        loadStudentTable(data);
                        LoadingModal.hide();

                        Toast.fire({
                            type: 'success',
                            title: `${data.length} student(s) found`
                        })
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching enrolled students:', error);
                        LoadingModal.hide();
                    }
                });
            }


            function getEnrollStudents(student_id) {
                console.log('Student ID sent to server:', student_id);

                $.ajax({
                    url: `/tesda/enroll/${student_id}`,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        console.log('Response from server:', response);

                        if (response.success) {
                            const student = response.student;

                            $('#enrollmentModal .modal-title').text(
                                `${student.firstName} ${student.lastName}`);
                            $('#studentIdNumbers').text(student.student_id);
                            $('#specializations').text(student.course_name);
                            $('#studentstatus').text(student.studstatus || 'N/A');
                            $('#studentPhoto').attr('src', `/path/to/photos/${student.student_id}.jpg`);

                            $('#input_specialization').find('option').filter(function() {
                                return $(this).text().toLowerCase() === student.course_name
                                    .toLowerCase();
                            }).prop('selected', true).trigger('change');

                            $('#batchs').find('option').filter(function() {
                                return $(this).text().toLowerCase() === student.batch_desc
                                    .toLowerCase();
                            }).prop('selected', true).trigger('change');

                            $('#enrollmentDate').val('N/A');

                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching student information:', xhr.responseJSON);
                        alert(
                            'An error occurred while fetching student information. Please try again.'
                        );
                    },
                });
            }

            // $(document).on('click', '.enroll_student', function() {
            //     const student_id = $(this).data('id');
            //     $('#enrollmentModal').modal('show');
            //     $('#updateEnrollButton').hide();
            //     $('#enrollButton').show();
            //     $('#specializationContainer').hide();
            //     if (student_id) {
            //         getEnrollStudents(student_id);
            //     } else {
            //         console.error('Student ID is undefined.');
            //     }
            // });

            $(document).on('click', '.clickableStudentInformation', function() {
                var studentId = $(this).data('id');
                console.log(studentId, 'clicked student ID');

                if (studentId !== undefined) {
                    $.ajax({
                        url: '/tesda/studentInformation/' + studentId,
                        type: 'GET',
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                var student = response.student;
                                console.log(student, 'student');

                                $('#sid').val(student.student_id);
                                $('#fName').val(student.firstname);
                                $('#mName').val(student.middlename);
                                $('#lName').val(student.lastname);
                                $('#studsuffix').val(student.suffix || '');
                                $('#dob').val(student.dob);
                                $('#pob').val(student.pob);
                                $('#gender2').val(student.gender).trigger('change');
                                $('#specializationSelect2').val(student.course_name).trigger(
                                    'change');
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
                                $('#studReligion').val(student.religion_id).trigger('change');
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

                                $('#studentIdNumber2').text(student.student_id);
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

            // Define the success callback function
            // Define the success callback function
            function renderRegistrationContent(response, studentId) {
                if (response.success) {
                    let studentData = response.student;
                    let schedules = response.schedules;

                    let registrationContent = `
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

                    let totalHours = 0;

                    // Check if there are schedules
                    if (schedules.length > 0) {
                        schedules.forEach((schedule, index) => {
                            let scheduleDetails = schedule.scheddetails.length > 0 ? schedule.scheddetails[
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

            // Event listener for the registration tab
            $(document).on('click', '#registration-tab', function(e) {
                e.preventDefault();

                let studentId = $(this).data('id');

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
                let studentId = $(this).data('id');
                let link = '/tesda/printCOR/' + studentId;
                let a = document.createElement('a');
                a.href = link;
                a.target = '_blank';
                a.click();
            })

            $(document).on('click', '#btn_print_all_cor', function(e) {
                e.preventDefault();
                let link = '/tesda/printCORall';
                window.open(link, '_blank');
            });

            // $(document).on('click', '#btn_print_all_cor', function(e) {
            //     e.preventDefault();
            //     let link = '/tesda/printCOR/all';
            //     let a = document.createElement('a');
            //     a.href = link;
            //     a.target = '_blank';
            //     a.click();
            // })


            // $('#enrollButton').click(function(e) {
            //     e.preventDefault();

            //     const enrollmentData = {
            //         studid: $(this).attr('data-id'),
            //         courseid: $('#input_specialization').val(),
            //         batchid: $('#batchs').val(),
            //         _token: $('meta[name="csrf-token"]').attr('content')
            //     };
            //     console.log(enrollmentData, 'eeeehhhh');
            //     if (!enrollmentData.courseid || !enrollmentData.batchid) {
            //         Swal.fire('Error', 'Please select both specialization and batch.', 'error');
            //         return;
            //     }

            //     $.ajax({
            //         url: '/tesda/enrollment/save',
            //         type: 'POST',
            //         data: enrollmentData,
            //         success: function(response) {
            //             if (response.success) {
            //                 Swal.fire('Success', response.message, 'success');
            //                 $('#enrollmentModal').modal('hide');
            //                 getStudents();
            //             } else {
            //                 Swal.fire('Error', response.message, 'error');
            //             }
            //         },
            //         error: function(xhr) {
            //             if (xhr.status === 422) {
            //                 const errors = xhr.responseJSON.errors;
            //                 let errorMessages = '';
            //                 for (const key in errors) {
            //                     errorMessages += `${errors[key][0]}<br>`;
            //                 }
            //                 Swal.fire('Validation Error', errorMessages, 'error');
            //             } else {
            //                 Swal.fire('Error', 'Something went wrong. Please try again later.',
            //                     'error');
            //             }
            //         }
            //     });
            // });

            function clearEnrollmentHistory() {
                // $('#enrollment-history').empty().html('<p>Loading enrollment history...</p>'); // Ensure previous content is fully removed
                $('#history-body').empty()
                $('#input_specialization').val(null).trigger('change'); // Reset specialization dropdown
                $('#batchs').val(null).trigger('change'); // Reset batch dropdown
            }

            // Define the success callback function
            function renderEnrollmentHistory(response, studentId) {
                if (response.success && response.history.length > 0) {
                    let tableHTML = `
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
        `;

                    $('#enrollment-history').html(tableHTML);
                } else {
                    $('#enrollment-history').html('<p>No enrollment history found for this student.</p>');
                }
            }

            $(document).on('click', '#enrollment-history-tab', function(e) {
                e.preventDefault();

                let studentId = $(this).attr('data-id');
                console.log("Clicked Student ID:", studentId);

                // if (!studentId) {
                //     $('#enrollment-history').html('<p>No student ID provided.</p>');
                //     return;
                // }

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
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                        $('#enrollment-history').html(
                            `<p>An error occurred: ${xhr.responseText || error}</p>`);
                    },
                });
            });


            $(document).on('click', '#printTOR', function(e) {
                e.preventDefault();
                let studentId = $(this).data('id');
                let link = '/tesda/printCOR/' + studentId;
                let a = document.createElement('a');
                a.href = link;
                a.target = '_blank';
                a.click();
            })

            $(document).on('click', '.print-cor', function() {

                const studentId = $(this).data('id');

                $('#registration-tab').attr('data-id', studentId);
                $('#registration-tab').on('click', function() {
                    $('#registration').attr('data-id', studentId);
                    $('#input_specialization').attr('data-id', courseid);
                    $('#batchs').attr('data-id', batchid);
                })
                $('#enrollment-history-tab').attr('data-id', studentId);
                $('#student-info-tab').attr('data-id', studentId);
                activateTab("#student-info");

                if (studentId !== undefined) {
                    $.ajax({
                        url: '/tesda/studentInformation/' + studentId,
                        type: 'GET',
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                var student = response.student;
                                course_id = student.courseid;

                                console.log(student, 'student');
                                $('#studentStatus2').text(student.description ||
                                    'NOT ENROLLED');
                                $('#sid').val(student.student_id);
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

                                $('#studentIdNumber2').text(student.student_id);
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

            // $(document).on('click', '.delete_student', function(e) {
            //     e.preventDefault();
            //     let studentId = $(this).data('id');

            //     if (!studentId) {
            //         alert("Student ID is undefined.");
            //         return;
            //     }

            //     Swal.fire({
            //         title: 'Delete Student?',
            //         text: "This action cannot be undone!",
            //         type: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Yes, delete it!'
            //     }).then((result) => {
            //         if (result.value) {
            //             $.ajax({
            //                 url: `/tesda/delete-student/${studentId}`,
            //                 type: 'DELETE',
            //                 headers: {
            //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
            //                         'content'),
            //                 },
            //                 success: function(response) {
            //                     if (response.success) {
            //                         Swal.fire(
            //                             'Deleted!',
            //                             response.message,
            //                             'success'
            //                         );
            //                         $(`button[data-id="${studentId}"]`).closest('tr')
            //                             .remove();
            //                     } else {
            //                         Swal.fire(
            //                             'Error!',
            //                             response.message,
            //                             'error'
            //                         );
            //                     }
            //                 },
            //                 error: function(xhr) {
            //                     console.error(xhr.responseJSON);
            //                     Swal.fire(
            //                         'Error!',
            //                         'An error occurred while deleting the student.',
            //                         'error'
            //                     );
            //                 },
            //             });
            //         }
            //     });
            // });

            // $('#specializationContainer').hide(); // Initially hide it

            // $(document).on('click', '.edit_student', function(e) {
            //     e.preventDefault();
            //     let studentId = $(this).data('id');
            //     $('#updateEnrollButton').attr('data-id', studentId);
            //     getEnrollStudents(studentId);

            //     $('#enrollmentModal').modal('show');
            //     $('#enrollButton').hide();
            //     $('#updateEnrollButton').show();
            // });

            // $(document).on('click', '#updateEnrollButton', function(e) {
            //     e.preventDefault();
            //     let studentId = $(this).data('id');
            //     let courseId = $('#input_specialization').val();
            //     let batchId = $('#batchs').val();
            //     let statusId = $('#status').val();


            //     $.ajax({
            //         type: 'PUT',
            //         url: `/tesda/update-student-enrolled/${studentId}`,
            //         data: {
            //             _token: $('meta[name="csrf-token"]').attr('content'),
            //             student_id: studentId,
            //             course_id: courseId,
            //             batch_id: batchId,
            //             status_id: statusId
            //         },
            //         success: function(response) {
            //             if (response.success) {
            //                 alert('Student enrollment updated successfully!');
            //                 $('#enrollmentModalEdit').modal('hide');
            //                 // location.reload();

            //                 // Show specialization container after successful update
            //                 $('#specializationContainer').show();
            //             } else {
            //                 alert(response.message);
            //             }
            //         },
            //         error: function(xhr) {
            //             console.error(xhr.responseJSON);
            //             alert('Error updating student enrollment.');
            //         }
            //     });
            // });
        })
    </script>
@endsection
