@php
    if (Session::get('currentPortal') == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (Session::get('currentPortal') == 3) {
        $extend = 'registrar.layouts.app';
    } elseif (auth()->user()->type == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } elseif (auth()->user()->type == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (auth()->user()->type == 3) {
        $extend = 'registrar.layouts.app';
    }
@endphp

@extends($extend)

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css"> --}}
    <style>
        #edittermApplied option,
        #edittermApplied .select2-selection__choice {
            color: black !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }

        input[type=search] {
            height: calc(1.7em + 2px) !important;
        }

        select[name=students_male_length] {
            height: calc(1.7em + 2px) !important;
        }

        select[name=students_female_length] {
            height: calc(1.7em + 2px) !important;
        }

        .custom-select-sm {
            padding-top: 0.1rem;
        }

        .page-link {
            line-height: .6;
            font-size: .7rem !important;
        }

        div.dataTables_wrapper div.dataTables_info {
            padding-top: 0.4em;
            white-space: nowrap;
            font-size: .7rem !important;
        }

        .DROP {
            text-decoration: line-through !important;
        }

        .tooltip>.arrow {
            visibility: hidden;
        }

        .modal {
            overflow-y: auto;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }
        /* Custom styles to make text smaller (12px) */
        .modal-body {
            font-size: 12px;
        }

        .form-label {
            font-size: 12px;
        }

        .form-control,
        .form-select {
            font-size: 12px;
        }

        .form-check-label {
            font-size: 12px;
        }

        .modal-title {
            font-size: 14px;
            /* Slightly larger for modal header */
        }

        .btn-success {
            font-size: 12px;
        }

        .select2-container--default .select2-selection__choice {
            color: white !important;
            background-color: #007bff !important;
        }
    </style>
@endsection


@section('content')
    @php
        // $user = auth()->user()->id;
        // $type = auth()->user()->type;
        // if ($type != 3) {
        //     $collegeid = DB::table('teacher')
        //         ->join('teacherdean', 'teacherdean.teacherid', '=', 'teacher.id')
        //         ->where('teacher.userid', $user)
        //         ->where('teacher.deleted', 0)
        //         ->where('teacherdean.deleted', 0)
        //         ->pluck('teacherdean.collegeid')
        //         ->toArray();
        //     $course = DB::table('college_courses')->where('deleted', 0)->whereIn('collegeid', $collegeid)->get();
        // } else {
        //     $course = DB::table('college_courses')->where('deleted', 0)->get();
        // }

        // $terms = DB::table('college_gradingsetupecr')->where('deleted', 0)->get();
        $terms = DB::table('college_termgrading')->where('deleted', 0)->get();
        $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
        $semester = DB::table('semester')->get();
        $gradelevel = DB::table('gradelevel')->where('deleted', 0)->where('acadprogid', 6)->orderBy('sortid')->get();
        $course = DB::table('college_courses')->get();
        $schoolinfo = DB::table('schoolinfo')->value('abbreviation');
        // $StudentSection = DB::table('college_sections')->where('deleted', 0)->get();
        $studStatus = DB::table('studentstatus')->get();

        $teacher = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first();

        $courses = DB::table('teacherdean')
            ->where('teacherdean.deleted', 0)
            ->where('college_colleges.deleted', 0)
            ->where('college_courses.deleted', 0)
            ->where('teacherid', $teacher->id)
            ->join('college_colleges', function ($join) {
                $join->on('teacherdean.collegeid', '=', 'college_colleges.id')->where('college_colleges.deleted', 0);
            })
            ->join('college_courses', function ($join) {
                $join->on('college_colleges.id', '=', 'college_courses.collegeid')->where('college_courses.deleted', 0);
            })
            ->select('college_courses.*')
            ->get();

        $colleges = DB::table('college_colleges')->where('cisactive', 1)->where('deleted', 0)->get();

        $college_gradelevel = DB::table('gradelevel')->where('acadprogid', 6)->where('deleted', 0)->get();
    @endphp

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Grading Setup</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Loading</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    @php
        $profile = DB::table('studinfo')->first();
        // $student = DB::table('students')->first();
    @endphp

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs" id="studentInfoTabs" role="tablist">
                            <li class="nav-item mr-3" role="presentation">
                                <a class="nav-link" href="{{ url('setup/gradingsetup') }}">Grading Setup</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" href="{{ url('setup/gradepointequivalancy') }}">Grade
                                    Point Equivalancy</a>
                            </li>
                        </ul>
                <div class="card shadow">
                    <div class="card-body">



                        {{-- <div class="row" style="font-size:.9rem !important">
                            <div class="col-md-3">
                                <label for="" class="mb-1">School Year</label>
                                <select name="filter_sy" id="filter_sy" class="form-control form-control-sm select2">
                                    @foreach ($sy as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->isactive == 1 ? 'selected="selected"' : '' }}>{{ $item->sydesc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="" class="mb-1">Semester</label>
                                <select name="filter_semester" id="filter_semester"
                                    class="form-control form-control-sm select2">
                                    @foreach ($semester as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->isactive == 1 ? 'selected="selected"' : '' }}>
                                            {{ $item->semester }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="" class="mb-1">Course</label>

                                <select class="form-control form-control-sm select2 " id="course">

                                    @foreach ($courses as $item)
                                        @if ($item->deleted == 0)
                                            <option value="{{ $item->id }}" style="word-wrap: break-word!important">
                                                {{ $item->courseDesc }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="" class="mb-1">Academic Level</label>
                                <select class="form-control form-control-sm select2 academic" id="academic"
                                    style="width: 100%;">


                                </select>
                            </div>
                        </div> --}}
                        <div class="row" id="no_course_holder" hidden>
                            <div class="col-md-12">
                                <div class="card shadow bg-danger">
                                    <div class="card-body p-1">
                                        You are not assigned to a course or college.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="enrollment_setup_holder" hidden>
                            <div class="col-md-12">
                                <div class="card shadow bg-default">
                                    <div class="card-body p-1" id="enrollment_setup_holder_desc">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="tab-content" id="studentInfoTabContent">
                            <div class="tab-pane fade show active" id="student-info" role="tabpanel"
                                aria-labelledby="student-grades-tab">
                                <div class="container" style="max-width: 1500px;">
                                    <h6 class="mb-3">Grade Point Equivalency</h6>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <button class="btn btn-success" id="addGradePointEquivalency"><i
                                                    class="fas fa-list fa-lg"></i> +
                                                Add Grade Point
                                                Equivalency</button>
                                        </div>
                                        {{-- <div class="input-group" style="width: 200px;">
                                            <input type="text" class="form-control form-control-sm" placeholder="Search">
                                            <button class="btn btn-outline-secondary btn-sm" type="button">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div> --}}
                                    </div>

                                    <table class="table table-bordered table-hover" id="gradePointEquivalencyTable"
                                        style="font-size:12px; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <div class="d-flex justify-content-center">
                                                        <span class="badge bg-success"
                                                            style="font-size: 13px;">Active</span>
                                                        <span class="badge bg-danger ml-2"
                                                            style="font-size: 13px;">Inactive</span>
                                                    </div>
                                                </th>
                                                <th scope="col">Grade Point Equivalency Description</th>
                                                <th scope="col">Term Applied</th>
                                                <th scope="col" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="gradepointequivalancy" role="tabpanel"
                                aria-labelledby="gradepointequivalancy-tab">
                                <!-- Student Loading content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Grade Point Equivalency -->
    <div class="modal fade" id="gradePointEquivalencyAddModal" tabindex="-1" aria-labelledby="addECRModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addECRModalLabel"><strong>Grading Point Equivalence</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalBtn">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <!-- Form Content -->
                    <form id="gradePointEquivalencyForm">
                        <!-- Grade Point Equivalency Description Field -->
                        <div class="row mb-3">
                            <label for="gradePointEquivalencyDescription" class="form-label mt-2 col-md-3">Grade Point
                                Description</label>

                            <input type="text" class="form-control col-md-9" id="gradePointEquivalencyDescription"
                                placeholder="Enter Description" required>

                        </div>

                        <!-- Add Grading Components Button -->
                        <button type="button" id="addGradingComponentsButton" class="btn btn-success mb-3"
                            data-bs-toggle="modal">
                            <i class="fas fa-list fa-lg"></i> + Add Grade Point Scale
                        </button>
                        <!-- Grading Components Table -->
                        <div class="table-responsive">
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-bordered" id="gradingPointsTable" style="font-size:12px;">
                                    <thead>
                                        <tr>
                                            <th>Grading Point Equivalence</th>
                                            <th>Letter Grade Equivalence</th>
                                            <th>% Equivalence</th>
                                            <th>Remarks</th>
                                            <th colspan="2" style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="default-row">
                                            <td data-failed='0'>1.0</td>
                                            <td>A+</td>
                                            <td>96 - 100%</td>
                                            <td>Excellent</td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td data-failed='0'>1.25</td>
                                            <td>A</td>
                                            <td>94 - 95%</td>
                                            <td>Very Good</td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td data-failed='0'>1.50</td>
                                            <td>A-</td>
                                            <td>91 - 93%</td>
                                            <td>Very Good</td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td data-failed='0'>1.75</td>
                                            <td>B+</td>
                                            <td>88 - 90%</td>
                                            <td>Good</td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td data-failed='0'>2</td>
                                            <td>B+</td>
                                            <td>85 - 87%</td>
                                            <td>Good</td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td data-failed='0'>2.25</td>
                                            <td>B</td>
                                            <td>83 - 84%</td>
                                            <td>Good</td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td data-failed='0'>2.50</td>
                                            <td>B-</td>
                                            <td>80 - 82%</td>
                                            <td>Fair</td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td data-failed='0'>2.75</td>
                                            <td>C</td>
                                            <td>78 - 79%</td>
                                            <td>Fair</td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td data-failed='0'>2.75</td>
                                            <td>D</td>
                                            <td>75 - 77%</td>
                                            <td>Pass</td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td data-failed='0'>5.0</td>
                                            <td>F</td>
                                            <td>Below 75%</td>
                                            <td>Failure</td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td data-failed='0'>INC</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>Incomplete</td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td data-failed='0'>NA</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>No Attendance</td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td data-failed='0'>UW</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>Unauthorized Withdrawal</td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td data-failed='0'>DRP</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>Dropped</td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td data-failed='0'>NC</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>No Credit</td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mt-3">
                            <label for="termApplied" class="form-label mr-2">Term Applied</label>
                            <select style="width: 80%;" class="form-select" id="termApplied" required multiple>
                                @foreach ($terms as $term)
                                    <option value="{{ $term->id }}">{{ $term->description }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-cog fa-2x ml-3" style="cursor: pointer;" id="settingsButton"></i>
                        </div>



                        <br>
                        <div class="row justify-content-center m-1">
                            <div class="col-md-6 d-flex flex-row align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" id="input_Active" class="form-check-input">
                                    <label for="setActive" class="form-check-label" style="font-size: larger;">Set as
                                        Active</label>
                                </div>
                                {{-- <div class="form-check ml-3">
                                    <input type="checkbox" id="input_grade_category" class="form-check-input">
                                    <label for="setActive" class="form-check-label" style="font-size: larger;">Set as
                                        Failed Grade</label>
                                </div> --}}
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                {{-- <button type="button" class="btn btn-outline-secondary">
                                    <i class="fas fa-eye"></i> e-Class Record Preview
                                </button> --}}
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer with Save Button -->
                {{-- <div class="modal-footer">
                    <button type="submit" form="gradePointEquivalencyForm" id="createGradeEquivalencyBtn"
                        class="btn btn-success"><i class="fas fa-save fa-lg mr-1"></i> SAVE</button>
                </div> --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="createGradeEquivalencyBtn"><i
                            class="fas fa-save fa-lg mr-1"></i> SAVE</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="gradePointEquivalencyEditModal" tabindex="-1" aria-labelledby="addECRModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editECRModalLabel"><strong>Grading Point Equivalence</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <!-- Form Content -->
                    <form id="gradePointEquivalencyForm">
                        <!-- Grade Point Equivalency Description Field -->
                        <div class="row mb-3">
                            <label for="gradePointEquivalencyDescription" class="form-label mt-2 col-md-3">Grade Point
                                Description</label>

                            <input type="text" class="form-control col-md-9" id="gradePointEquivalencyID"
                                placeholder="Enter Description" required hidden>

                            <input type="text" class="form-control col-md-9"
                                id="edit_gradePointEquivalencyDescription" placeholder="Enter Description" required>

                        </div>

                        <!-- Add Grading Components Button -->
                        <button type="button" id="addGradingComponentsButton_edit" class="btn btn-success mb-3"
                            data-bs-toggle="modal">
                            <i class="fas fa-list fa-lg"></i> + Add Grade Point Scale
                        </button>
                        <!-- Grading Components Table -->
                        <div class="table-responsive">
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-bordered" id="editgradingPointsTable" style="font-size:12px;">
                                    <thead>
                                        <tr>
                                            <th>Grading Point Equivalence</th>
                                            <th>Letter Grade Equivalence</th>
                                            <th>% Equivalence</th>
                                            <th>Remarks</th>
                                            <th colspan="2" style="text-align: center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mt-3">
                            <label for="termApplied" class="form-label mr-2">Term Applied</label>
                            <select style="width: 80%;" class="form-select" id="edittermApplied" style="color:black;"
                                required multiple>

                            </select>

                            <i class="fas fa-cog fa-2x ml-3" style="cursor: pointer;" id="editsettingsButton"></i>
                        </div>



                        <div class="row justify-content-center m-3">
                            {{-- <div class="col-md-6 d-flex align-items-center">
                                <input type="checkbox" id="edit_input_Active" class="form-check-input me-3">
                                <label for="setActive" class="form-check-label">Set as Active</label>
                            </div> --}}

                            <div class="col-md-6 d-flex flex-row align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" id="edit_input_Active" class="form-check-input">
                                    <label for="setActive" class="form-check-label" style="font-size: larger;">Set as
                                        Active</label>
                                </div>
                                {{-- <div class="form-check ml-3">
                                    <input type="checkbox" id="edit_input_Category" class="form-check-input">
                                    <label for="setActive" class="form-check-label" style="font-size: larger;">Set as
                                        Failed Grade</label>
                                </div> --}}
                            </div>

                            <div class="col-md-6 d-flex justify-content-end">
                                {{-- <button type="button" class="btn btn-outline-secondary">
                                    <i class="fas fa-eye"></i> e-Class Record Preview
                                </button> --}}
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer with Save Button -->
                {{-- <div class="modal-footer">
                 <button type="submit" form="gradePointEquivalencyForm" id="createGradeEquivalencyBtn"
                     class="btn btn-success"><i class="fas fa-save fa-lg mr-1"></i> SAVE</button>
             </div> --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="updateGradeEquivalencyBtn"> UPDATE</button>
                </div>
            </div>
        </div>
    </div>




    <!-- Modal -->
    <div class="modal fade" id="gradingComponentsModal" tabindex="-1" aria-labelledby="gradingComponentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gradingComponentsModalLabel">New Grade point Scale</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Components Row -->
                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Grade Point Equivalence</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">% Equivalence</label>
                            </div>
                        </div>
                        <div class="row mb-3">

                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm" id="gradePointEquivalency"
                                    placeholder="e.i 4.0" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="percentEquivalency"
                                        placeholder="94 - 100"
                                        style="border-top-right-radius: 0; border-bottom-right-radius: 0;" required>
                                    <span class="input-group-text"
                                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;"><i
                                            class="fas fa-percent"></i></span>
                                </div>
                            </div>
                        </div>
                    </section>


                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Letter Grade Equivalence</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">Remarks</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm" id="letterGradeEquivalency"
                                    placeholder="A+" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="gradingRemarks"
                                        placeholder="Grading Remarks" required>

                                </div>
                            </div>


                        </div>
                    </section>

                    <br>
                    <div class="row justify-content-center m-0">
                        <div class="col-md-6 d-flex flex-row align-items-center">
                            <div class="form-check ml-3">
                                <input type="checkbox" id="input_grade_category" class="form-check-input">
                                <label for="setActive" class="form-check-label" style="font-size: larger;">Set as
                                    Failed Grade</label>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            {{-- <button type="button" class="btn btn-outline-secondary">
                                <i class="fas fa-eye"></i> e-Class Record Preview
                            </button> --}}
                        </div>
                    </div>

                    <!-- Add button -->
                    <div class="text-center">
                        <button type="button" class="btn btn-success" id="addGradeEquivalencyBtn">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="gradingComponentsModal2" tabindex="-1" aria-labelledby="gradingComponentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gradingComponentsModalLabel">New Grade point Scale</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Components Row -->
                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Grade Point Equivalence</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">% Equivalence</label>
                            </div>
                        </div>
                        <div class="row mb-3">

                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm" id="gradePointEquivalency2"
                                    placeholder="e.i 4.0" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="percentEquivalency2"
                                        placeholder="94 - 100"
                                        style="border-top-right-radius: 0; border-bottom-right-radius: 0;" required>
                                    <span class="input-group-text"
                                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;"><i
                                            class="fas fa-percent"></i></span>
                                </div>
                            </div>
                        </div>
                    </section>


                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Letter Grade Equivalence</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">Remarks</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm" id="letterGradeEquivalency2"
                                    placeholder="A+" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="gradingRemarks2"
                                        placeholder="Grading Remarks" required>

                                </div>
                            </div>
                        </div>
                    </section>

                    <br>
                    <div class="row justify-content-center m-0">
                        <div class="col-md-6 d-flex flex-row align-items-center">
                            <div class="form-check ml-3">
                                <input type="checkbox" id="input_grade_category2" class="form-check-input">
                                <label for="setActive" class="form-check-label" style="font-size: larger;">Set as
                                    Failed Grade</label>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            {{-- <button type="button" class="btn btn-outline-secondary">
                                <i class="fas fa-eye"></i> e-Class Record Preview
                            </button> --}}
                        </div>
                    </div>

                    <!-- Add button -->
                    <div class="text-center">
                        <button type="button" class="btn btn-success" id="addGradeEquivalencyBtn2">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editgradingComponentsModal" tabindex="-1" aria-labelledby="gradingComponentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gradingComponentsModalLabel">Edit Grade point Scale</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Components Row -->
                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Grade Point Equivalence</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">% Equivalence</label>
                            </div>
                        </div>
                        <div class="row mb-3">

                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm" id="editgradePointEquivalency"
                                    placeholder="e.i 4.0" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm"
                                        id="editpercentEquivalency" placeholder="94 - 100"
                                        style="border-top-right-radius: 0; border-bottom-right-radius: 0;" required>
                                    <span class="input-group-text"
                                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;"><i
                                            class="fas fa-percent"></i></span>
                                </div>
                            </div>
                        </div>
                    </section>


                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Letter Grade Equivalence</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">Remarks</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm"
                                    id="editletterGradeEquivalency" placeholder="A+" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="editgradingRemarks"
                                        placeholder="Grading Remarks" required>
                                </div>
                            </div>
                        </div>
                    </section>
                    <br>
                    <div class="row justify-content-center m-0">
                        <div class="col-md-6 d-flex flex-row align-items-center">
                            <div class="form-check ml-3">
                                <input type="checkbox" id="input_edit_grade_category" class="form-check-input">
                                <label for="setActive" class="form-check-label" style="font-size: larger;">Set as
                                    Failed Grade</label>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            {{-- <button type="button" class="btn btn-outline-secondary">
                                <i class="fas fa-eye"></i> e-Class Record Preview
                            </button> --}}
                        </div>
                    </div>

                    <!-- Add button -->
                    <div class="text-center">
                        <button type="button" class="btn btn-success" id="editGradeEquivalencyBtn">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editAppendgradingComponentsModal" tabindex="-1"
        aria-labelledby="gradingComponentsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gradingComponentsModalLabel">Edit Grade point Scale</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Components Row -->
                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Grade Point Equivalence</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">% Equivalence</label>
                            </div>
                        </div>
                        <div class="row mb-3">

                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm"
                                    id="editappendgradePointEquivalency" placeholder="e.i 4.0" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm"
                                        id="editappendpercentEquivalency" placeholder="94 - 100"
                                        style="border-top-right-radius: 0; border-bottom-right-radius: 0;" required>
                                    <span class="input-group-text"
                                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;"><i
                                            class="fas fa-percent"></i></span>
                                </div>
                            </div>
                        </div>
                    </section>


                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Letter Grade Equivalence</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">Remarks</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm"
                                    id="editappendletterGradeEquivalency" placeholder="A+" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm"
                                        id="editappendgradingRemarks" placeholder="Grading Remarks" required>

                                </div>
                            </div>
                        </div>
                    </section>

                    <br>
                    <div class="row justify-content-center m-0">
                        <div class="col-md-6 d-flex flex-row align-items-center">
                            <div class="form-check ml-3">
                                <input type="checkbox" id="input_append_edit_grade_category" class="form-check-input">
                                <label for="setActive" class="form-check-label" style="font-size: larger;">Set as
                                    Failed Grade</label>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            {{-- <button type="button" class="btn btn-outline-secondary">
                                <i class="fas fa-eye"></i> e-Class Record Preview
                            </button> --}}
                        </div>
                    </div>

                    <!-- Add button -->
                    <div class="text-center">
                        <button type="button" class="btn btn-success" id="editappendGradeEquivalencyBtn">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editgradingComponentsModal2" tabindex="-1"
        aria-labelledby="gradingComponentsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gradingComponentsModalLabel">Edit Grade point Scale</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Components Row -->
                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Grade Point Equivalence</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">% Equivalence</label>
                            </div>
                        </div>
                        <div class="row mb-3">

                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm"
                                    id="editgradePointEquivalency2" placeholder="e.i 4.0" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm"
                                        id="editpercentEquivalency2" placeholder="94 - 100"
                                        style="border-top-right-radius: 0; border-bottom-right-radius: 0;" required>
                                    <span class="input-group-text"
                                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;"><i
                                            class="fas fa-percent"></i></span>
                                </div>
                            </div>
                        </div>
                    </section>


                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Letter Grade Equivalence</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">Remarks</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm"
                                    id="editletterGradeEquivalency2" placeholder="A+" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="editgradingRemarks2"
                                        placeholder="Grading Remarks" required>

                                </div>
                            </div>
                        </div>
                    </section>

                    <br>
                    <div class="row justify-content-center m-0">
                        <div class="col-md-6 d-flex flex-row align-items-center">
                            <div class="form-check ml-3">
                                <input type="checkbox" id="input_edit_grade_category2" class="form-check-input">
                                <label for="setActive" class="form-check-label" style="font-size: larger;">Set as
                                    Failed Grade</label>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            {{-- <button type="button" class="btn btn-outline-secondary">
                                <i class="fas fa-eye"></i> e-Class Record Preview
                            </button> --}}
                        </div>
                    </div>

                    <!-- Add button -->
                    <div class="text-center">
                        <button type="button" class="btn btn-success" id="editGradeEquivalencyBtn2">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editAppendgradingComponentsModal2" tabindex="-1"
        aria-labelledby="gradingComponentsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gradingComponentsModalLabel">Edit Grade point Scale</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Components Row -->
                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Grade Point Equivalence</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">% Equivalence</label>
                            </div>
                        </div>
                        <div class="row mb-3">

                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm"
                                    id="editappendgradePointEquivalency2" placeholder="e.i 4.0" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm"
                                        id="editappendpercentEquivalency2" placeholder="94 - 100"
                                        style="border-top-right-radius: 0; border-bottom-right-radius: 0;" required>
                                    <span class="input-group-text"
                                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;"><i
                                            class="fas fa-percent"></i></span>
                                </div>
                            </div>
                        </div>
                    </section>


                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Letter Grade Equivalence</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">Remarks</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm"
                                    id="editappendletterGradeEquivalency2" placeholder="A+" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm"
                                        id="editappendgradingRemarks2" placeholder="Grading Remarks" required>

                                </div>
                            </div>
                        </div>
                    </section>

                    <br>
                    <div class="row justify-content-center m-0">
                        <div class="col-md-6 d-flex flex-row align-items-center">
                            <div class="form-check ml-3">
                                <input type="checkbox" id="input_append_edit_grade_category2" class="form-check-input">
                                <label for="setActive" class="form-check-label" style="font-size: larger;">Set as
                                    Failed Grade</label>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            {{-- <button type="button" class="btn btn-outline-secondary">
                                <i class="fas fa-eye"></i> e-Class Record Preview
                            </button> --}}
                        </div>
                    </div>

                    <!-- Add button -->
                    <div class="text-center">
                        <button type="button" class="btn btn-success" id="editappendGradeEquivalencyBtn2">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="settingsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsModalLabel">Term</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-left mb-3">
                        <button type="button" class="btn btn-primary btn-sm" id="addTermButton" data-toggle="modal"
                            data-target="#addTermModal">+ Add Term</button>
                    </div>

                    <table class="table table-bordered" id="termTable">
                        <thead>
                            <tr>
                                <th>Term</th>
                                <th>Term Frequency</th>
                                <th>Grading</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Table rows with data will go here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Term Modal Structure -->
    <div class="modal fade" id="addTermModal" tabindex="-1" role="dialog" aria-labelledby="addTermModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTermModalLabel">Add New Term</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form for Adding a New Term -->
                    <form id="addTermForm">
                        <div class="form-group">
                            <label for="termName">Term Description</label>
                            <input type="text" class="form-control" id="termName" placeholder="Term Description"
                                name="termName" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="select2Field" class="form-label">Term Frequency</label>
                                <select class="form-select select2" id="termFrequency" style="width: 100%;">
                                    <option value="">Select Term Frequency</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="formControl" class="form-label">Grading Percentage</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="formControl"
                                        placeholder="Enter value">
                                    <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="addTermForm" class="btn btn-primary" id="saveTerm">Add</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>



    <script>
        $(document).ready(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })

            $('#filter_sy').select2()
            $('#filter_semester').select2()
            $('#termApplied').select2()
            $('#edittermApplied').select2()
            $('#columnsInECR').select2();
            $('#subcolumnsInECR').select2();
            $('#termFrequency').select2();

            academic_select()

            function academic_select() {
                $.ajax({
                    type: 'GET',
                    url: '/college/get/yearlevel',
                    success: function(data) {
                        $.each(data, function(index, item) {
                            $('#academic').append(`
                        <option value="${item.id}">${item.levelname}</option>
                    `);
                        });
                    }
                })
            }
            var table = $("#gradingPointsTable");
            table.find("tbody").on('click', '.remove-row', function() {
                $(this).closest('tr').hide();
            });

            var table = $("#editgradingPointsTable");
            table.find("tbody").on('click', '.delete_gradepoint_equivalency', function() {
                $(this).closest('tr').hide();
            });



            // $('#gradePointEquivalencyAddModal').on('hidden.bs.modal', function() {

            //     // $("#subject_filter").val("").change();
            //     var table = $("#gradingPointsTable");
            //     table.find("tbody").on('click', '.remove-append-row', function() {
            //         $(this).closest('tr').remove();
            //     });
            // });


            // $('#addGradingComponentsButton').on('click', function() {
            //     // event.preventDefault();
            //     var table = $("#gradingPointsTable");
            //     table.find("tbody").empty();


            // });

            $("#addGradeEquivalencyBtn2").click(function(e) {
                e.preventDefault();
                var gradePointEquivalency = $("#gradePointEquivalency2").val();
                var percentEquivalency = $("#percentEquivalency2").val();
                var letterGradeEquivalency = $("#letterGradeEquivalency2").val();
                var gradingRemarks = $("#gradingRemarks2").val();
                var isFailedGrade = $("#input_grade_category2").is(":checked") ? 1 : 0;

                if (gradePointEquivalency.trim() == "" || percentEquivalency.trim() == "" ||
                    letterGradeEquivalency
                    .trim() == "" || gradingRemarks.trim() == "") {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'All fields are required',
                    });
                    return;
                }

                var table = $("#editgradingPointsTable");
                // var row = $("<tr data-id='" + (table.find("tbody tr").length + 1) + "'>");
                var row = $("<tr class='appended-row'>");
                if (isFailedGrade) {
                    row.append($("<td style='color:red' data-failed='1'>").text(gradePointEquivalency));
                    row.append($("<td style='color:red'>").text(letterGradeEquivalency));
                    row.append($("<td style='color:red'>").text(percentEquivalency));
                    row.append($("<td style='color:red'>").text(gradingRemarks));
                    row.append($("<td style='color:red;display:none'>").text(isFailedGrade));
                } else {
                    row.append($("<td data-failed='0'>").text(gradePointEquivalency));
                    row.append($("<td>").text(letterGradeEquivalency));
                    row.append($("<td>").text(percentEquivalency));
                    row.append($("<td>").text(gradingRemarks));
                    row.append($("<td style='display:none'>").text(isFailedGrade));

                }



                row.append($("<td>").html(
                    '<a href="javascript:void(0)" class="text-center align-middle ml-2 edit-append-row2"><i class="far fa-edit text-primary"></i></a>'
                ));
                row.append($("<td>").html(

                    // '<button type="button" class="btn btn-danger btn-xs remove-append-row"><i class="far fa-trash-alt text-danger"></i></button>'
                    '<a href="javascript:void(0)" class="text-center align-middle ml-2 remove-append-row"><i class="far fa-trash-alt text-danger"></i></a>'
                ));

                table.find("tbody").on('click', '.remove-append-row', function() {
                    $(this).closest('tr').remove();
                });
                table.find("tbody").append(row);

                Toast.fire({
                    type: 'success',
                    title: 'New grade point scale added successfully'
                })

                $("#gradePointEquivalency2").val("");
                $("#percentEquivalency2").val("");
                $("#letterGradeEquivalency2").val("");
                $("#gradingRemarks2").val("");

                let currentAppendRow2; // Variable to store the currently selected row

                $('.edit-append-row2').on('click', function() {
                    // Get the current row
                    currentAppendRow2 = $(this).closest('tr');

                    // Extract data from the row
                    const gradePointEquivalence = currentAppendRow2.find('td:eq(0)').text()
                        .trim();
                    const letterGradeEquivalence = currentAppendRow2.find('td:eq(1)').text()
                        .trim();
                    const percentEquivalence = currentAppendRow2.find('td:eq(2)').text().trim();
                    const remarks = currentAppendRow2.find('td:eq(3)').text().trim();
                    $("#input_append_edit_grade_category2").prop("checked", $(currentAppendRow2)
                        .find(
                            'td:eq(0)').attr(
                            'data-failed') == 1 ? true : false);

                    // Populate modal inputs
                    $('#editappendgradePointEquivalency2').val(gradePointEquivalence);
                    $('#editappendletterGradeEquivalency2').val(letterGradeEquivalence);
                    $('#editappendpercentEquivalency2').val(percentEquivalence);
                    $('#editappendgradingRemarks2').val(remarks);

                    // Show the modal
                    $('#editAppendgradingComponentsModal2').modal({
                        keyboard: false
                    }).modal('show');
                });

                $('#editappendGradeEquivalencyBtn2').on('click', function() {
                    if (currentAppendRow2) {
                        // Get updated values from modal inputs
                        const updatedGradePointEquivalence = $(
                                '#editappendgradePointEquivalency2')
                            .val().trim();
                        const updatedLetterGradeEquivalence = $(
                                '#editappendletterGradeEquivalency2')
                            .val()
                            .trim();
                        const updatedPercentEquivalence = $('#editappendpercentEquivalency2')
                            .val()
                            .trim();
                        const updatedRemarks = $('#editappendgradingRemarks2').val().trim();
                        const isFailedGrade = $("#input_append_edit_grade_category2").is(
                            ":checked") ? 1 : 0;

                        // Update only the selected table row with new data
                        if (isFailedGrade) {
                            currentAppendRow2.find('td:eq(0)').text(updatedGradePointEquivalence)
                                .css('color',
                                    'red').attr('data-failed', 1);
                            currentAppendRow2.find('td:eq(1)').text(updatedLetterGradeEquivalence)
                                .css(
                                    'color',
                                    'red');
                            currentAppendRow2.find('td:eq(2)').text(updatedPercentEquivalence).css(
                                'color',
                                'red');
                            currentAppendRow2.find('td:eq(3)').text(updatedRemarks).css('color',
                                'red');
                        } else {
                            currentAppendRow2.find('td:eq(0)').text(updatedGradePointEquivalence)
                                .css('color',
                                    '').attr('data-failed', 0);
                            currentAppendRow2.find('td:eq(1)').text(updatedLetterGradeEquivalence)
                                .css(
                                    'color',
                                    '');
                            currentAppendRow2.find('td:eq(2)').text(updatedPercentEquivalence).css(
                                'color',
                                '');
                            currentAppendRow2.find('td:eq(3)').text(updatedRemarks).css('color',
                                '');
                        }
                    }
                    Toast.fire({
                        type: 'success',
                        title: 'Successfully updated'
                    })
                    $('#editAppendgradingComponentsModal2').modal('hide');
                });

            });



            // let currentAppendRow; // Variable to store the currently selected row

            // $('.edit-append-row').on('click', function() {
            //     // Get the current row
            //     console.log('NA tuplokan niiiiiiii')
            //     currentAppendRow = $(this).closest('tr')[0];

            //     // Extract data from the row
            //     const gradePointEquivalence = $(currentAppendRow).find('td:eq(0)').text().trim();
            //     const letterGradeEquivalence = $(currentAppendRow).find('td:eq(1)').text().trim();
            //     const percentEquivalence = $(currentAppendRow).find('td:eq(2)').text().trim();
            //     const remarks = $(currentAppendRow).find('td:eq(3)').text().trim();

            //     // Populate modal inputs
            //     $('#editappendgradePointEquivalency').val(gradePointEquivalence);
            //     $('#editappendletterGradeEquivalency').val(letterGradeEquivalence);
            //     $('#editappendpercentEquivalency').val(percentEquivalence);
            //     $('#editappendgradingRemarks').val(remarks);

            //     // Show the modal
            //     $('#editAppendgradingComponentsModal').modal({
            //         keyboard: false
            //     }).modal('show');
            // });


            $("#addGradeEquivalencyBtn").click(function(e) {
                e.preventDefault();
                var gradePointEquivalency = $("#gradePointEquivalency").val();
                var percentEquivalency = $("#percentEquivalency").val();
                var letterGradeEquivalency = $("#letterGradeEquivalency").val();
                var gradingRemarks = $("#gradingRemarks").val();
                var isFailedGrade = $("#input_grade_category").is(":checked") ? 1 : 0;
                if (gradePointEquivalency.trim() == "" || percentEquivalency.trim() == "" ||
                    letterGradeEquivalency
                    .trim() == "" || gradingRemarks.trim() == "") {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'All fields are required',
                    });
                    return;
                }

                var table = $("#gradingPointsTable");
                // var row = $("<tr data-id='" + (table.find("tbody tr").length + 1) + "'>");

                var row = $("<tr class='appended-row'>");
                if (isFailedGrade) {
                    row.append($("<td style='color:red' data-failed='1'>").text(gradePointEquivalency));
                    row.append($("<td style='color:red'>").text(letterGradeEquivalency));
                    row.append($("<td style='color:red'>").text(percentEquivalency));
                    row.append($("<td style='color:red'>").text(gradingRemarks));
                    // row.append($("<td style='color:red'>").text(isFailedGrade));
                    row.append($("<td style='color:red;display:none'>").text(isFailedGrade));
                } else {
                    row.append($("<td data-failed='0'>").text(gradePointEquivalency));
                    row.append($("<td>").text(letterGradeEquivalency));
                    row.append($("<td>").text(percentEquivalency));
                    row.append($("<td>").text(gradingRemarks));
                    row.append($("<td style='display:none'>").text(isFailedGrade));
                }
                row.append($("<td>").html(

                    // '<button type="button" class="btn btn-danger btn-xs remove-append-row"><i class="far fa-trash-alt text-danger"></i></button>'
                    '<a href="javascript:void(0)" class="text-center align-middle ml-2 edit-append-row"><i class="far fa-edit text-primary"></i></a>'
                ));
                row.append($("<td>").html(

                    // '<button type="button" class="btn btn-danger btn-xs remove-append-row"><i class="far fa-trash-alt text-danger"></i></button>'
                    '<a href="javascript:void(0)" class="text-center align-middle ml-2 remove-append-row"><i class="far fa-trash-alt text-danger"></i></button>'
                ));

                table.find("tbody").on('click', '.remove-append-row', function() {
                    $(this).closest('tr').remove();
                });
                table.find("tbody").append(row);

                Toast.fire({
                    type: 'success',
                    title: 'New grade point scale added successfully'
                })

                $("#gradePointEquivalency").val("");
                $("#percentEquivalency").val("");
                $("#letterGradeEquivalency").val("");
                $("#gradingRemarks").val("");

                let currentAppendRow; // Variable to store the currently selected row

                $('.edit-append-row').on('click', function() {
                    // Get the current row
                    currentAppendRow = $(this).closest('tr');

                    // Extract data from the row
                    const gradePointEquivalence = currentAppendRow.find('td:eq(0)').text()
                        .trim();
                    const letterGradeEquivalence = currentAppendRow.find('td:eq(1)').text()
                        .trim();
                    const percentEquivalence = currentAppendRow.find('td:eq(2)').text().trim();
                    const remarks = currentAppendRow.find('td:eq(3)').text().trim();
                    $("#input_append_edit_grade_category").prop("checked", $(currentAppendRow).find(
                        'td:eq(0)').attr(
                        'data-failed') == 1 ? true : false);

                    // Populate modal inputs
                    $('#editappendgradePointEquivalency').val(gradePointEquivalence);
                    $('#editappendletterGradeEquivalency').val(letterGradeEquivalence);
                    $('#editappendpercentEquivalency').val(percentEquivalence);
                    $('#editappendgradingRemarks').val(remarks);

                    // Show the modal
                    $('#editAppendgradingComponentsModal').modal({
                        keyboard: false
                    }).modal('show');
                });

                $('#editappendGradeEquivalencyBtn').on('click', function() {
                    if (currentAppendRow) {
                        // Get updated values from modal inputs
                        const updatedGradePointEquivalence = $(
                                '#editappendgradePointEquivalency')
                            .val().trim();
                        const updatedLetterGradeEquivalence = $(
                                '#editappendletterGradeEquivalency')
                            .val()
                            .trim();
                        const updatedPercentEquivalence = $('#editappendpercentEquivalency')
                            .val()
                            .trim();
                        const updatedRemarks = $('#editappendgradingRemarks').val().trim();
                        const isFailedGrade = $("#input_append_edit_grade_category").is(
                            ":checked") ? 1 : 0;

                        // Update only the selected table row with new data
                        if (isFailedGrade) {
                            currentAppendRow.find('td:eq(0)').text(updatedGradePointEquivalence)
                                .css('color', 'red').attr('data-failed', 1);
                            currentAppendRow.find('td:eq(1)').text(updatedLetterGradeEquivalence)
                                .css('color', 'red');
                            currentAppendRow.find('td:eq(2)').text(updatedPercentEquivalence).css(
                                'color', 'red');
                            currentAppendRow.find('td:eq(3)').text(updatedRemarks).css('color',
                                'red');
                        } else {
                            currentAppendRow.find('td:eq(0)').text(updatedGradePointEquivalence)
                                .css('color', '').attr('data-failed', 0);
                            currentAppendRow.find('td:eq(1)').text(updatedLetterGradeEquivalence)
                                .css('color', '');
                            currentAppendRow.find('td:eq(2)').text(updatedPercentEquivalence).css(
                                'color', '');
                            currentAppendRow.find('td:eq(3)').text(updatedRemarks).css('color', '');
                        }
                    }
                    Toast.fire({
                        type: 'success',
                        title: 'Successfully updated'
                    })
                    $('#editAppendgradingComponentsModal').modal('hide');
                });

            });


            $('#updateGradeEquivalencyBtn').on('click', function(event) {
                // event.preventDefault();
                update_pointEquivalencyscale()

            });


            function update_pointEquivalencyscale() {

                var setActivePointEquivalency = $('#edit_input_Active').is(':checked') ? 1 : 0;

                var gradePointEquivalencyID = $('#gradePointEquivalencyID').val();

                var gradePointEquivalencyDescription = $('#edit_gradePointEquivalencyDescription').val();

                var gradePointEquivalencyTerms = $('#edittermApplied').val();
                console.log('gradePointEquivalencyTerms haysssss', gradePointEquivalencyTerms);

                var tableData = [];
                $("#editgradingPointsTable").find("tbody tr").each(function() {
                    if ($(this).is(":visible")) {
                        var isFailed = $(this).find("td:eq(0)").attr('data-failed');
                        var gradePointEquivalency = $(this).find("td:eq(0)").text().trim();
                        var letterGradeEquivalency = $(this).find("td:eq(1)").text().trim();
                        var percentEquivalency = $(this).find("td:eq(2)").text().trim();
                        var gradingRemarks = $(this).find("td:eq(3)").text().trim();

                        if (isFailed && gradePointEquivalency && letterGradeEquivalency &&
                            percentEquivalency &&
                            gradingRemarks) {
                            tableData.push({
                                gradePointEquivalency: gradePointEquivalency,
                                letterGradeEquivalency: letterGradeEquivalency,
                                percentEquivalency: percentEquivalency,
                                gradingRemarks: gradingRemarks,
                                isFailed: isFailed

                            });
                        }
                    }
                });

                $.ajax({
                    type: "POST",
                    url: '/setup/gradepointequivalency/update',
                    data: {
                        gradePointDesc: gradePointEquivalencyDescription,
                        gradePointScaleData: tableData,
                        gradePointEquivalencyID: gradePointEquivalencyID,
                        setActivePointEquivalency: setActivePointEquivalency,
                        gradePointEquivalencyTerms: gradePointEquivalencyTerms,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                            editgradingPointsTable(gradePointEquivalencyID)
                            gradePointEquivalencyTable()
                        } else if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully updated'
                            })

                            editgradingPointsTable(gradePointEquivalencyID)
                            gradePointEquivalencyTable()

                        } else if (data[0].status == 3) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully updated'
                            })

                            editgradingPointsTable(gradePointEquivalencyID)
                            gradePointEquivalencyTable()

                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }



            $('#createGradeEquivalencyBtn').on('click', function(event) {
                // event.preventDefault();
                create_pointEquivalencyscale()

            });

            $('#closeModalBtn').on('click', function() {
                resetGradepointEquivalence();
            });

            function resetGradepointEquivalence() {

                // Check if there is any attendance data in local storage with this key prefix
                let hasData = true;

                if (hasData) {
                    // Confirm with the user before deleting all attendance data
                    Swal.fire({
                        // title: 'Create Grade Point Equivalency Reset',
                        text: 'You have unsaved changes. Would you like to save your work before leaving?',
                        type: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Save Changes', // Close modal on cancel
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Discard Changes',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            // Show success message after reset
                            // Swal.fire({
                            //     title: 'Reset Successful',
                            //     text: 'Grade Point Equivalency has been reset.',
                            //     type: 'success',
                            //     confirmButtonText: 'OK'
                            // }).then(() => {
                            //     // Close the modal after the alert is acknowledged
                            //     $('#gradePointEquivalencyAddModal').modal('hide');
                            // });

                        } else {
                            // Save attendance data

                            Toast.fire({
                                type: 'success',
                                title: 'Grade Point Equivalency has been created.',
                                confirmButtonText: 'OK'
                            })



                            $('#gradePointEquivalencyAddModal').modal({
                                keyboard: false
                            });
                            // var table = $("#gradingPointsTable");
                            // // table.find("tbody tr:not(.default-row)").remove();
                            // table.find("tbody tr:not(.default-row)").remove();
                            // table.find("tbody .default-row").show();
                            // $("#gradePointEquivalencyDescription").val("");
                            $('#createGradeEquivalencyBtn').click();

                            gradePointEquivalencyAddModal.show();
                            // Toast.fire({
                            //     type: 'success',
                            //     title: 'Grade Point Equivalency has been created.',
                            //     confirmButtonText: 'OK'
                            // })

                            // .then(() => {
                            //     // Close the modal after saving
                            //     $('#gradePointEquivalencyAddModal').modal('show');
                            // });
                        }
                    });
                } else {
                    // No attendance data found, close the modal automatically
                    $('#attendancemodal').modal('hide');
                }
            }


            function create_pointEquivalencyscale() {
                var termApplied = $('#termApplied').val();
                var setActivePointEquivalency = $('#input_Active').is(':checked') ? 1 : 0;
                // var setCategoryPointEquivalency = $('#input_grade_category').is(':checked') ? 1 : 0;
                var gradePointEquivalencyDescription = $('#gradePointEquivalencyDescription').val();

                var tableData = [];
                $("#gradingPointsTable").find("tbody tr").each(function() {
                    if ($(this).is(":visible")) {
                        var isFailed = $(this).find("td:eq(0)").attr('data-failed');
                        var gradePointEquivalency = $(this).find("td:eq(0)").text().trim();
                        var letterGradeEquivalency = $(this).find("td:eq(1)").text().trim();
                        var percentEquivalency = $(this).find("td:eq(2)").text().trim();
                        var gradingRemarks = $(this).find("td:eq(3)").text().trim();

                        if (isFailed && gradePointEquivalency && letterGradeEquivalency &&
                            percentEquivalency &&
                            gradingRemarks) {
                            tableData.push({
                                gradePointEquivalency: gradePointEquivalency,
                                letterGradeEquivalency: letterGradeEquivalency,
                                percentEquivalency: percentEquivalency,
                                gradingRemarks: gradingRemarks,
                                isFailed: isFailed
                            });
                        }
                    }
                });

                $.ajax({
                    type: 'GET',
                    url: '/setup/gradepointequivalency/create',
                    data: {
                        gradePointDesc: gradePointEquivalencyDescription,
                        gradePointScaleData: tableData,
                        termApplied: termApplied,
                        setActive: setActivePointEquivalency,


                    },
                    success: function(data) {
                        if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                        } else if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully created'
                            })

                            var table = $("#gradingPointsTable");
                            table.find("tbody .appended-row").remove();
                            gradePointEquivalencyTable()
                            $("#gradePointEquivalencyDescription").val("");

                            $("#gradePointEquivalencyAddModal").modal('hide');
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }

            gradePointEquivalencyTable()

            function gradePointEquivalencyTable() {

                $("#gradePointEquivalencyTable").DataTable({
                    destroy: true,
                    // data:temp_subj,
                    // bInfo: true,
                    autoWidth: false,
                    // lengthChange: true,
                    // stateSave: true,
                    // serverSide: true,
                    // processing: true,
                    ajax: {
                        url: '/setup/gradepointequivalency/fetch',
                        type: 'GET',
                        dataSrc: function(json) {
                            return json;
                        }
                    },
                    columns: [{
                            "data": "isactive"
                        },
                        {
                            "data": "grade_description"
                        },
                        {
                            "data": "terms"
                        },
                        {
                            "data": null
                        },
                    ],
                    columnDefs: [

                        {
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // Define the icon
                                var checkIcon =
                                    '<i class="fa fa-check bg-success checked-active p-2" data-toggle="tooltip" title="Active"></i>';

                                var wrongIcon =
                                    '<i class="fa fa-times bg-danger checked-active p-2" data-toggle="tooltip" title="Inactive"></i>';

                                // Determine whether to display the icon
                                var iconHtml = rowData.isactive == 1 ? checkIcon :
                                    wrongIcon;

                                // Construct the HTML with the conditional icon only
                                var text = '<div style="text-align:center;">' + iconHtml +
                                    '</div>';

                                // Apply the constructed HTML and initialize tooltips
                                $(td).html(text).addClass('align-middle');
                                $(td).find('[data-toggle="tooltip"]').tooltip();
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.grade_description).addClass(
                                    'align-middle');
                            }
                        },

                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // $(td).html('');
                                var html = '';
                                $.each(rowData.terms.split(','), function(i, term) {
                                    html +=
                                        '<span style="font-size: 0.6rem;" class="badge badge-primary mr-1">' +
                                        term + '</span>';
                                });
                                $(td).html(html).addClass('align-middle');
                            }
                        },


                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // var buttons =
                                //     '<a href="javascript:void(0)" class="delete_subject" data-id="' +
                                //     rowData.id + '"><i class="far fa-trash-alt text-danger"></i></a>';
                                // $(td)[0].innerHTML = buttons
                                // $(td).addClass('text-center')
                                // $(td).addClass('align-middle')
                                var link =
                                    '<a href="#" style="color: #blue; text-decoration: underline;font-weight: bold;" class="view_gradepoint_equivalency" data-id="' +
                                    rowData.id +
                                    '"> View Grading Point Equivalence</a>';
                                $(td)[0].innerHTML = link;
                                $(td).addClass('text-center align-middle');
                            }
                        },

                    ],
                    // lengthMenu: [
                    //     [10, 25, 50, 100],
                    //     [10, 25, 50, 100]
                    // ],
                    // pageLength: 10,
                    // dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                    //     "<'row'<'col-sm-12'tr>>" +
                    //     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

                });
            }


            $(document).on('click', '.view_gradepoint_equivalency', function() {


                var point_equivalency_id = $(this).attr('data-id')
                console.log(point_equivalency_id, 'poinnnttttt equiiivalency idddd')
                $('#gradePointEquivalencyEditModal').modal()
                editgradingPointsTable(point_equivalency_id)

                document.getElementById('addGradingComponentsButton_edit').addEventListener('click',
                    function() {
                        var gradingComponentsModal = new bootstrap.Modal(document.getElementById(
                            'gradingComponentsModal2'), {
                            keyboard: false
                        });
                        gradingComponentsModal.show();
                    });



                $.ajax({
                    type: 'GET',
                    url: '/setup/selectedgradepointequivalency/fetch',
                    data: {
                        point_equivalency_id: point_equivalency_id
                    },
                    success: function(response) {


                        var grade_point_scale = response.grade_point_scale;
                        var grade_point_equivalency_terms = response
                            .grade_point_equivalency_terms;

                        // Split the selected terms from grade_point_scale[0]
                        // const selectedDescription = grade_point_scale[0].terms.split(",").map(
                        //     term => term.trim());

                        const selectedDescription = [];
                        grade_point_scale[0].terms.split(",").forEach(function(term) {
                            selectedDescription.push(term.trim());
                        });

                        // Split overall terms (IDs and Descriptions)
                        const overallTermsIds = grade_point_equivalency_terms[0].termsid
                            .split(
                                ",");
                        const overallTermsDescriptions = grade_point_equivalency_terms[0]
                            .terms
                            .split(",");

                        // Clear the dropdown before appending new options
                        $("#edittermApplied").empty();

                        // Loop through all terms
                        overallTermsDescriptions.forEach(function(description, index) {
                            // Check if the current description exists in selectedDescription array
                            const isSelected = selectedDescription.includes(
                                description
                                .trim());

                            // Append the option to the dropdown
                            $("#edittermApplied").append(
                                new Option(description, overallTermsIds[index],
                                    isSelected, isSelected)
                            );
                        });

                        // Optional: Trigger change event if necessary
                        $("#edittermApplied").trigger('change');







                        $("#gradePointEquivalencyID").val(grade_point_scale[0]
                            .grade_point_equivalency_id);
                        $("#edit_gradePointEquivalencyDescription").val(grade_point_scale[0]
                            .grade_description);

                        if (parseFloat(grade_point_scale[0].isactive) === 1) {
                            $('#edit_input_Active').prop('checked', true);
                        } else if (parseFloat(grade_point_scale[0].isactive) === 0) {
                            $('#edit_input_Active').prop('checked', false);
                        }

                        // if (parseFloat(grade_point_scale[0].grade_point_category) === 1) {
                        //     $('#edit_input_Category').prop('checked', true);
                        // } else if (parseFloat(grade_point_scale[0].grade_point_category) ===
                        //     0) {
                        //     $('#edit_input_Category').prop('checked', false);
                        // }
                    }
                });




            });

            // $(document).on('click', '.view_gradepoint_equivalency', function() {


            //     var point_equivalency_id = $(this).attr('data-id')
            //     console.log(point_equivalency_id, 'poinnnttttt equiiivalency idddd')
            //     $('#gradePointEquivalencyEditModal').modal()
            //     editgradingPointsTable(point_equivalency_id)

            //     document.getElementById('addGradingComponentsButton_edit').addEventListener('click',
            //         function() {
            //             var gradingComponentsModal = new bootstrap.Modal(document.getElementById(
            //                 'gradingComponentsModal2'), {
            //                 keyboard: false
            //             });
            //             gradingComponentsModal.show();
            //         });



            //     $.ajax({
            //         type: 'GET',
            //         url: '/setup/selectedgradepointequivalency/fetch',
            //         data: {
            //             point_equivalency_id: point_equivalency_id
            //         },
            //         success: function(data) {
            //             grade_point_scale = data;

            //             $("#gradePointEquivalencyID").val(grade_point_scale[0]
            //                 .grade_point_equivalency_id);


            //             $("#edit_gradePointEquivalencyDescription").val(grade_point_scale[0]
            //                 .grade_description);

            //             console.log('grade_point_scale terms', grade_point_scale[0]
            //                 .terms)

            //             const gradeDescription = grade_point_scale[0].terms.split(",");

            //             $("#edittermApplied").empty();

            //             gradeDescription.forEach(function(item) {
            //                 $("#edittermApplied").append(new Option(item, item));
            //             });

            //             $("#edittermApplied")
            //                 .val(gradeDescription)
            //                 .trigger('change')
            //                 .data('select2')
            //                 .$container
            //                 .addClass('bg-primary');



            //             // const gradeDescription = grade_point_scale[0].terms.split(",");

            //             // $("#edittermApplied").empty();

            //             // gradeDescription.forEach(function(item) {
            //             //     $("#edittermApplied").append(new Option(item, item));
            //             // });
            //             // $("#edittermApplied")
            //             //     .val(gradeDescription)
            //             //     .trigger('change');





            //             if (parseFloat(grade_point_scale[0].isactive) === 1) {
            //                 $('#edit_input_Active').prop('checked', true);
            //             } else if (parseFloat(grade_point_scale[0].isactive) === 0) {
            //                 $('#edit_input_Active').prop('checked', false);
            //             }


            //         }
            //     })




            // });

            /*************  ✨ Codeium Command 🌟  *************/
            function editgradingPointsTable(point_equivalency_id) {

                $("#editgradingPointsTable").DataTable({
                    destroy: true,
                    autoWidth: false,
                    ajax: {
                        url: '/setup/gradepointScale/fetch',
                        type: 'GET',
                        data: {
                            point_equivalency_id: point_equivalency_id
                        },
                        dataSrc: function(json) {
                            // return json.grade_point_scale;
                            return json;
                        }
                    },
                    columns: [{
                            "data": "grade_point"
                        },
                        {
                            "data": "letter_equivalence"
                        },
                        {
                            "data": "percent_equivalence"
                        },
                        {
                            "data": "grade_remarks"
                        },
                        {
                            "data": null
                        },
                        {
                            "data": null
                        }
                    ],
                    columnDefs: [{
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (rowData.is_failed == 1) {
                                    $(td).html(rowData.grade_point).addClass('align-middle').css(
                                        'color', 'red');
                                } else {
                                    $(td).html(rowData.grade_point).addClass('align-middle');
                                }
                                $(td).html(rowData.grade_point).addClass('align-middle').attr(
                                    'data-failed', (rowData.is_failed == 1) ? '1' : '0');
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (rowData.is_failed == 1) {
                                    $(td).html(rowData.letter_equivalence).addClass('align-middle')
                                        .css('color',
                                            'red');
                                } else {
                                    $(td).html(rowData.letter_equivalence).addClass('align-middle');
                                }
                                $(td).html(rowData.letter_equivalence).addClass(
                                    'align-middle');
                            }
                        },
                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (rowData.is_failed == 1) {
                                    $(td).html(rowData.percent_equivalence).addClass('align-middle')
                                        .css('color',
                                            'red');
                                } else {
                                    $(td).html(rowData.percent_equivalence).addClass(
                                        'align-middle');
                                }
                                $(td).html(rowData.percent_equivalence).addClass(
                                    'align-middle');
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (rowData.is_failed == 1) {
                                    $(td).html(rowData.grade_remarks).addClass('align-middle').css(
                                        'color', 'red');
                                } else {
                                    $(td).html(rowData.grade_remarks).addClass('align-middle');
                                }
                                $(td).html(rowData.grade_remarks).addClass('align-middle');
                            }
                        },
                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var buttons =
                                    '<a href="javascript:void(0)" class="edit_gradepoint_scale" id="edit_gradepoint_scale" data-id="' +
                                    rowData.id +
                                    '"><i class="far fa-edit text-primary"></i></a>';
                                $(td)[0].innerHTML = buttons;
                                $(td).addClass('text-center align-middle');
                            }
                        },

                        {
                            'targets': 5,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var buttons =
                                    '<a href="javascript:void(0)" class="delete_gradepoint_equivalency" data-id="' +
                                    rowData.id +
                                    '"><i class="far fa-trash-alt text-danger"></i></a>';
                                $(td)[0].innerHTML = buttons;
                                $(td).addClass('text-center align-middle');
                            }
                        }
                    ]
                });
            }
            /******  7915374e-cd87-42a5-ac7f-e65d5a773e03  *******/

            // function create_pointEquivalencyscale() {

            //     var gradePointEquivalencyDescription = $('#gradePointEquivalencyDescription').val();

            //     var tableData = [];
            //     $("#gradingPointsTable").find("tbody tr").each(function() {
            //         tableData.push({
            //             gradePointEquivalency: $(this).find("td:eq(0)").text(),
            //             letterGradeEquivalency: $(this).find("td:eq(1)").text(),
            //             percentEquivalency: $(this).find("td:eq(2)").text(),
            //             gradingRemarks: $(this).find("td:eq(3)").text()
            //         });
            //     });

            //     $.ajax({
            //         type: 'GET',
            //         url: '/setup/gradepointequivalency/create',
            //         data: {
            //             gradePointDesc: gradePointEquivalencyDescription,
            //             gradePointScaleData: tableData
            //         },
            //         success: function(data) {
            //             if (data[0].status == 2) {
            //                 Toast.fire({
            //                     type: 'warning',
            //                     title: data[0].message
            //                 })
            //             } else if (data[0].status == 1) {
            //                 Toast.fire({
            //                     type: 'success',
            //                     title: data[0].message
            //                 })


            //             } else {
            //                 Toast.fire({
            //                     type: 'error',
            //                     title: data[0].message
            //                 })
            //             }
            //         }
            //     })
            // }



            $('#addGradePointEquivalency').click(function() {
                $('#gradePointEquivalencyAddModal').modal({
                    keyboard: false
                });
                var table = $("#gradingPointsTable");
                // table.find("tbody tr:not(.default-row)").remove();
                table.find("tbody tr:not(.default-row)").remove();
                table.find("tbody .default-row").show();
                $("#gradePointEquivalencyDescription").val("");

                gradePointEquivalencyAddModal.show();
            });

            $('#addGradingComponentsButton').click(function() {
                var gradingComponentsModal = new bootstrap.Modal($('#gradingComponentsModal')[0], {
                    keyboard: false
                });
                gradingComponentsModal.show();
            });

            $('#edit-row').click(function() {
                var editgradingComponentsModal = new bootstrap.Modal($('#editgradingComponentsModal')[
                    0], {
                    keyboard: false
                });
                editgradingComponentsModal.show();
            });


            let currentRow; // Variable to store the currently selected row

            $('.edit-row').on('click', function() {
                // Get the current row
                currentRow = $(this).closest('tr')[0];

                // Extract data from the row
                const gradePointEquivalence = $(currentRow).find('td:eq(0)').text().trim();
                const letterGradeEquivalence = $(currentRow).find('td:eq(1)').text().trim();
                const percentEquivalence = $(currentRow).find('td:eq(2)').text().trim();
                const remarks = $(currentRow).find('td:eq(3)').text().trim();
                // const isFailedGrade = $(currentRow).find('td:eq(4)').text().trim();

                // Populate modal inputs
                $('#editgradePointEquivalency').val(gradePointEquivalence);
                $('#editletterGradeEquivalency').val(letterGradeEquivalence);
                $('#editpercentEquivalency').val(percentEquivalence);
                $('#editgradingRemarks').val(remarks);

                $("#input_edit_grade_category").prop("checked", $(currentRow).find('td:eq(0)').attr(
                    'data-failed') == 1 ? true : false);
                // $("#input_edit_grade_category").prop("checked", isFailedGrade == 1 ? true : false);

                // Show the modal
                $('#editgradingComponentsModal').modal('show');
            });

            // Update the table row with the new data
            $('#editGradeEquivalencyBtn').on('click', function() {
                if (currentRow) {
                    // Get updated values from modal inputs
                    const updatedGradePointEquivalence = $('#editgradePointEquivalency').val().trim();
                    const updatedLetterGradeEquivalence = $('#editletterGradeEquivalency').val().trim();
                    const updatedPercentEquivalence = $('#editpercentEquivalency').val().trim();
                    const updatedRemarks = $('#editgradingRemarks').val().trim();
                    const isFailedGrade = $("#input_edit_grade_category").is(":checked") ? 1 : 0;

                    // Update the table row with new data
                    if (isFailedGrade) {
                        $(currentRow).find('td:eq(0)').text(updatedGradePointEquivalence).css('color',
                            'red').attr('data-failed', 1);
                        $(currentRow).find('td:eq(1)').text(updatedLetterGradeEquivalence).css('color',
                            'red');
                        $(currentRow).find('td:eq(2)').text(updatedPercentEquivalence).css('color',
                            'red');
                        $(currentRow).find('td:eq(3)').text(updatedRemarks).css('color', 'red');

                    } else {
                        $(currentRow).find('td:eq(0)').text(updatedGradePointEquivalence).css('color',
                                '')
                            .attr('data-failed', 0);
                        $(currentRow).find('td:eq(1)').text(updatedLetterGradeEquivalence).css('color',
                            '');
                        $(currentRow).find('td:eq(2)').text(updatedPercentEquivalence).css('color', '');
                        $(currentRow).find('td:eq(3)').text(updatedRemarks).css('color', '');

                    }

                }

                Toast.fire({
                    type: 'success',
                    title: 'Successfully updated'
                })

                $('#editgradingComponentsModal').modal('hide');

            });


            $(document).on('click', '.edit_gradepoint_scale', function() {
                // var editgradingComponentsModal2 = new bootstrap.Modal($('#editgradingComponentsModal2')[
                //     0], {
                //     keyboard: false
                // });
                // editgradingComponentsModal2.show();
                $('#editgradingComponentsModal2').modal('show');
            });

            let updatecurrentRow; // Variable to store the currently selected row

            $(document).on('click', '.edit_gradepoint_scale', function() {
                // Get the current row
                updatecurrentRow = $(this).closest('tr')[0];

                // Extract data from the row
                const gradePointEquivalence = $(updatecurrentRow).find('td:eq(0)').text().trim();
                const letterGradeEquivalence = $(updatecurrentRow).find('td:eq(1)').text().trim();
                const percentEquivalence = $(updatecurrentRow).find('td:eq(2)').text().trim();
                const remarks = $(updatecurrentRow).find('td:eq(3)').text().trim();

                // Populate modal inputs
                $('#editgradePointEquivalency2').val(gradePointEquivalence);
                $('#editletterGradeEquivalency2').val(letterGradeEquivalence);
                $('#editpercentEquivalency2').val(percentEquivalence);
                $('#editgradingRemarks2').val(remarks);
                $("#input_edit_grade_category2").prop("checked", $(updatecurrentRow).find('td:eq(0)').attr(
                    'data-failed') == 1 ? true : false);


                // Show the modal
                $('#editgradingComponentsModal2').modal('show');
            });

            // Update the table row with the new data
            $('#editGradeEquivalencyBtn2').on('click', function() {
                if (updatecurrentRow) {
                    // Get updated values from modal inputs
                    const updatedGradePointEquivalence = $('#editgradePointEquivalency2').val().trim();
                    const updatedLetterGradeEquivalence = $('#editletterGradeEquivalency2').val()
                        .trim();
                    const updatedPercentEquivalence = $('#editpercentEquivalency2').val().trim();
                    const updatedRemarks = $('#editgradingRemarks2').val().trim();
                    const isFailedGrade = $("#input_edit_grade_category2").is(":checked") ? 1 : 0;

                    // Update the table row with new data
                    if (isFailedGrade) {
                        $(updatecurrentRow).find('td:eq(0)').text(updatedGradePointEquivalence).css('color',
                            'red').attr('data-failed', 1);
                        $(updatecurrentRow).find('td:eq(1)').text(updatedLetterGradeEquivalence).css(
                            'color',
                            'red');
                        $(updatecurrentRow).find('td:eq(2)').text(updatedPercentEquivalence).css('color',
                            'red');
                        $(updatecurrentRow).find('td:eq(3)').text(updatedRemarks).css('color', 'red');
                    } else {
                        $(updatecurrentRow).find('td:eq(0)').text(updatedGradePointEquivalence).css('color',
                            '').attr('data-failed', 0);
                        $(updatecurrentRow).find('td:eq(1)').text(updatedLetterGradeEquivalence).css(
                            'color',
                            '');
                        $(updatecurrentRow).find('td:eq(2)').text(updatedPercentEquivalence).css('color',
                            '');
                        $(updatecurrentRow).find('td:eq(3)').text(updatedRemarks).css('color', '');
                    }

                }

                Toast.fire({
                    type: 'success',
                    title: 'Successfully updated'
                })

                $('#editgradingComponentsModal2').modal('hide');

            });




            $('#settingsButton').on('click', function() {
                $('#settingsModal').modal('show');
                $.ajax({
                    url: '/terms',
                    method: 'GET',
                    success: function(response) {
                        let tbody = '';
                        response.forEach(term => {
                            tbody += `
                                <tr data-id="${term.id}">
                                    <td>${term.description}</td>
                                    <td>${term.Term_frequency}</td>
                                    <td>${term.grading_perc}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-term" data-id="${term.id}">Edit</button>
                                        <button class="btn btn-sm btn-danger delete-term" data-id="${term.id}">Delete</button>
                                    </td>
                                </tr>`;
                        });
                        $('#settingsModal tbody').html(tbody);
                    },
                    error: function(error) {
                        console.log("Error fetching terms:", error);
                    }
                });
            });

            $('#editsettingsButton').on('click', function() {
                $('#settingsModal').modal('show');
                $.ajax({
                    url: '/terms',
                    method: 'GET',
                    success: function(response) {
                        let tbody = '';
                        response.forEach(term => {
                            tbody += `
                                <tr data-id="${term.id}">
                                    <td>${term.description}</td>
                                    <td>${term.Term_frequency}</td>
                                    <td>${term.grading_perc}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-term" data-id="${term.id}">Edit</button>
                                        <button class="btn btn-sm btn-danger delete-term" data-id="${term.id}">Delete</button>
                                    </td>
                                </tr>`;
                        });
                        $('#settingsModal tbody').html(tbody);
                    },
                    error: function(error) {
                        console.log("Error fetching terms:", error);
                    }
                });
            });

            function fetchTermDetails(termId) {
                $.ajax({
                    url: '/edit/terms/' + termId,
                    type: 'GET',
                    success: function(data) {
                        $('#termName').val(data.description);
                        $('#termFrequency').val(data.Term_frequency);
                        $('#formControl').val(data.grading_perc);
                        $('#addTermModalLabel').text('Edit Term');
                        $('#saveTerm').text('Update');
                        $('#saveTerm').data('id', termId);
                        $('#addTermModal').modal('show');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }

            $('#addTermForm').on('submit', function(e) {
                e.preventDefault();
                let formData = {
                    termName: $('#termName').val(),
                    termFrequency: $('#termFrequency').val(),
                    gradingPercentage: $('#formControl').val(),
                    _token: '{{ csrf_token() }}'
                };

                $('.edit-term').prop('disabled', true);

                $.ajax({
                    url: '/add/terms',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#addTermModal').modal('hide');
                        Swal.fire({
                            title: 'Success!',
                            text: response.success,
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        });

                        let newRow = `<tr data-id="${response.term.id}">
                            <td>${formData.termName}</td>
                            <td>${formData.termFrequency}</td>
                            <td>${formData.gradingPercentage}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-term" data-id="${response.term.id}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-term" data-id="${response.term.id}">Delete</button>
                            </td>
                        </tr>`;

                        $('#termTable tbody').append(newRow);
                        $('.edit-term').prop('disabled', false);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        $('.edit-term').prop('disabled', false);
                    }
                });
            });

            $('#saveTerm').on('click', function() {
                let termId = $(this).data('id');
                let formData = {
                    termName: $('#termName').val(),
                    termFrequency: $('#termFrequency').val(),
                    gradingPercentage: $('#formControl').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                if ($('#saveTerm').text() === 'Update') {
                    formData._method = 'PUT';

                    $.ajax({
                        url: '/update/terms/' + termId,
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            $('#addTermModal').modal('hide');
                            Swal.fire({
                                title: 'Updated!',
                                text: response.success,
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            });

                            $(`tr[data-id="${termId}"]`).find('td').eq(0).text(formData
                                .termName);
                            $(`tr[data-id="${termId}"]`).find('td').eq(1).text(formData
                                .termFrequency);
                            $(`tr[data-id="${termId}"]`).find('td').eq(2).text(formData
                                .gradingPercentage);

                            $('#addTermModalLabel').text('Add New Term');
                            $('#saveTerm').text('Add');
                            $('#saveTerm').removeData('id');
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                }
            });

            function deleteTerm(termId) {
                $.ajax({
                    url: '/delete/terms/' + termId,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: response.success,
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        });

                        $(`tr[data-id="${termId}"]`).remove();
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }

            $(document).on('click', '.edit-term', function() {
                let termId = $(this).data('id');
                fetchTermDetails(termId);
            });

            $(document).on('click', '.delete-term', function() {
                let termId = $(this).data('id');
                deleteTerm(termId);
            });
        });
    </script>
@endsection
