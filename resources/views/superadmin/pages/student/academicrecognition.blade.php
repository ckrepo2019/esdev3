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

        .highlighted-tab {
            background-color: #ca4102;
            border-radius: 5px;
            padding: 10px;
            font-weight: bold;
        }

        #studentInfoTabs .nav-link.active {
            background-color: #892019;
            color: white !important;
            border: 2px solid #892019 !important;
            border-radius: 5px;
        }

        #studentInfoTabs .nav-link {
            color: #892019;
        }

        #studentInfoTabs .nav-link:hover {
            background-color: #892019;
            color: #ffffff;
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

        /* Align both Certification Date and Search */
        /* .dataTables_filter {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            display: flex;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            align-items: center;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            justify-content: space-between;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            width: 100%;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        } */

        /* Adjust input styles for consistent height */
        /* #certification-date,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        .dataTables_filter input {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            height: 30px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            margin-left: 5px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        } */
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
        // $gradelevel = DB::table('gradelevel')->where('deleted', 0)->where('acadprogid', 6)->orderBy('sortid')->get();
        $gradelevel = DB::table('gradelevel')
            ->where('deleted', 0)
            ->whereIn('acadprogid', [6, 8])
            ->orderBy('sortid')
            ->get();

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

        $colleges1 = DB::table('college_colleges')
            ->join('teacherdean', function ($join) {
                $join->on('college_colleges.id', '=', 'teacherdean.collegeid')->where('teacherdean.deleted', 0);
            })
            ->where('college_colleges.cisactive', 1)
            ->where('college_colleges.deleted', 0)
            ->get();
        $college_gradelevel = DB::table('gradelevel')->where('acadprogid', 6)->where('deleted', 0)->get();
    @endphp

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Academic Recognition</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Academic Recognition</li>
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
                <div class="card shadow">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="studentInfoTabs" role="tablist">

                            <li class="nav-item mr-3" role="presentation">
                                <a class="nav-link active" href="{{ url('college/grade/academicrecognition') }}">Dean's
                                    List</a>
                            </li>

                            <li class="nav-item" role="presentation">
                                <a class="nav-link" href="#">Latin Honors
                                    Distinction</a>
                            </li>
                        </ul>
                        <br>


                        <div class="row" style="font-size:.9rem !important">
                            <div class="col-md-2">
                                <label for="" class="mb-1">School Year</label>
                                <select name="filter_sy" id="filter_sy" class="form-control form-control-sm select2">
                                    @foreach ($sy as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->isactive == 1 ? 'selected="selected"' : '' }}>{{ $item->sydesc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
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
                                <label for="" class="mb-1">College</label>

                                <select class="form-control form-control-sm select2 " id="filter_college">

                                    @foreach ($colleges1 as $item)
                                        @if ($item->deleted == 0)
                                            <option value="{{ $item->collegeid }}" style="word-wrap: break-word!important">
                                                {{ $item->collegeDesc }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="" class="mb-1">Course</label>

                                <select class="form-control form-control-sm select2 " id="filter_course">

                                    @foreach ($courses as $item)
                                        @if ($item->deleted == 0)
                                            <option value="{{ $item->id }}" style="word-wrap: break-word!important">
                                                {{ $item->courseDesc }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-md-2">
                                <label for="" class="mb-1">Academic Level</label>
                                <select class="form-control form-control-sm select2 academic" id="filter_academic"
                                    style="width: 100%;">


                                </select>
                            </div> --}}

                            <div class="col-md-2">
                                <label for="" class="mb-1">Academic Level</label>
                                <select name="academic" id="academic" class="form-control form-control-sm select2">
                                    <option value="">All</option>
                                    @foreach ($gradelevel as $item)
                                        <option value="{{ $item->id }}">{{ $item->levelname }}</option>
                                    @endforeach
                                </select>
                            </div>


                        </div>
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
                                <div class="container mt-4">
                                    <h6 class="mb-3">Dean's List</h6>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <button class="btn btn-success btn-sm" id="addGradePointEquivalency"><i
                                                    class="fas fa-cog fa-lg"></i>
                                                Recognition Setting</button>
                                            <button class="btn btn-success btn-sm" id=certificateSetting type="button">
                                                <i class="fas fa-cog fa-lg"></i> Certification Setting
                                            </button>
                                        </div>


                                        <button class="btn btn-primary btn-sm" type="button">
                                            <i class="fas fa-print fa-lg"></i> Print
                                        </button>

                                    </div>

                                    <table class="table table-bordered table-hover" id="academicRecognitionTable"
                                        style="font-size:12px;">
                                        <thead>
                                            <tr>
                                                <th scope="col">Student ID</th>
                                                <th scope="col">Students Name</th>
                                                <th scope="col">Gender</th>
                                                <th scope="col">Academic Level</th>
                                                <th scope="col">Course</th>
                                                <th scope="col">Grade Percentage</th>
                                                <th scope="col">Grade Equivalence</th>
                                                <th scope="col">Ranking</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="academicRecognitionBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
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
                    <h5 class="modal-title" id="gradingComponentsModalLabel">Edit Recognition Type</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Components Row -->
                    <section>
                        <input type="text" class="form-control form-control-sm" id="editrecognitionTypeId" hidden
                            required>

                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Recognition Type Description</label>
                            </div>

                        </div>
                        <div class="row mb-3">

                            <div class="col-12">
                                <input type="text" class="form-control form-control-sm"
                                    id="editrecognitionTypeDescription" required>
                            </div>

                        </div>
                    </section>


                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Minimum Grade Requirement</label>
                            </div>

                        </div>
                        <div class="row mb-3">

                            <div class="col-6 ">
                                <select class="form-select" id="editminGradeRequirement">
                                    <option value="" selected disabled>Choose Grade</option>
                                    <option value="1.00">1.00</option>
                                    <option value="1.25">1.25</option>
                                    <option value="1.50">1.50</option>
                                    <option value="1.75">1.75</option>
                                    <option value="2.00">2.00</option>
                                    <option value="2.25">2.25</option>
                                    <option value="2.50">2.50</option>
                                    <option value="2.75">2.75</option>
                                    <option value="3.00">3.00</option>
                                </select>
                            </div>

                            <div class="col-6">
                                <div class="col-md-12 d-flex" style="margin-left: 106px;">
                                    <input type="checkbox" id="edit_input_Active_recognitiontype"
                                        class="form-check-input me-5">
                                    <label for="setActive" class="form-check-label">Set as Active</label>
                                </div>
                            </div>
                        </div>
                    </section>
                    <br>
                    <br>

                    <!-- Add button -->
                    <div class="text-center">
                        <button type="button" class="btn btn-success btn-sm"
                            id="updateRecognitonTypeBtn">Update</button>
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
                    <h5 class="modal-title" id="addECRModalLabel"><strong>Recognition Type</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <!-- Form Content -->

                    <!-- Grade Point Equivalency Description Field -->

                    <!-- Add Grading Components Button -->
                    <button type="button" id="addRecognitionTpeButton" class="btn btn-success mb-3"
                        data-bs-toggle="modal">
                        <i class="fas fa-plus"></i> Recognition Type
                    </button>
                    <!-- Grading Components Table -->
                    <div class="table-responsive">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-bordered" id="academicRecognitiontypeTable"
                                style="font-size:12px;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Academic Recognition Type</th>
                                        <th>Minimum Grade</th>
                                        <th colspan="2" style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>


                                </tbody>
                            </table>
                        </div>
                    </div>



                </div>



            </div>
        </div>
    </div>







    <!-- Modal -->
    <div class="modal fade" id="NewRecognitionTypeModal" tabindex="-1" aria-labelledby="gradingComponentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gradingComponentsModalLabel">New Recognition Type</h5>
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
                                <label for="gradingDescription" class="form-label">Recognition Type Description</label>
                            </div>

                        </div>
                        <div class="row mb-3">

                            <div class="col-12">
                                <input type="text" class="form-control form-control-sm"
                                    id="recognitionTypeDescription" required>
                            </div>

                        </div>
                    </section>


                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Minimum Grade Requirement</label>
                            </div>

                        </div>
                        <div class="row mb-3">

                            <div class="col-6 ">
                                <select class="form-select" id="minGradeRequirement">
                                    <option value="" selected disabled>Choose Grade</option>
                                    <option value="1.00">1.00</option>
                                    <option value="1.25">1.25</option>
                                    <option value="1.50">1.50</option>
                                    <option value="1.75">1.75</option>
                                    <option value="2.00">2.00</option>
                                    <option value="2.25">2.25</option>
                                    <option value="2.50">2.50</option>
                                    <option value="2.75">2.75</option>
                                    <option value="3.00">3.00</option>
                                </select>
                            </div>

                            <div class="col-6">
                                <div class="col-md-12 d-flex" style="margin-left: 106px;">
                                    <input type="checkbox" id="input_Active_recognitiontype"
                                        class="form-check-input me-5">
                                    <label for="setActive" class="form-check-label">Set as Active</label>
                                </div>
                            </div>
                        </div>
                    </section>
                    <br>
                    <br>

                    <!-- Add button -->
                    <div class="text-center">
                        <button type="button" class="btn btn-success btn-sm" id="addRecognitonTypeBtn">Add</button>
                    </div>
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
            $('#minGradeRequirement').select2()
            $('#editminGradeRequirement').select2()

            $('#addRecognitonTypeBtn').on('click', function(event) {
                // event.preventDefault();
                create_recognitiontype()

            });

            function create_recognitiontype() {
                var minGradeRequirement = $('#minGradeRequirement').val();
                var setActivePointEquivalency = $('#input_Active_recognitiontype').is(':checked') ? 1 : 0;
                var recognitionTypeDescription = $('#recognitionTypeDescription').val();

                $.ajax({
                    type: 'GET',
                    url: '/setup/academicrecognition/create',
                    data: {
                        recognitionTypeDesc: recognitionTypeDescription,
                        minGradeRequirement: minGradeRequirement,
                        setActive: setActivePointEquivalency

                    },
                    success: function(data) {
                        if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                            academicRecognitionTable();

                        } else if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully created'
                            })
                            $("#recognitionTypeDescription").val("");
                            $("#minGradeRequirement").val(null).trigger('change');
                            $("#input_Active_recognitiontype").prop('checked', false);
                            academicRecognitionTable();
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }


            academicRecognitionTable()

            function academicRecognitionTable() {

                $("#academicRecognitiontypeTable").DataTable({
                    destroy: true,
                    // data:temp_subj,
                    // bInfo: true,
                    autoWidth: false,
                    // lengthChange: true,
                    // stateSave: true,
                    // serverSide: true,
                    // processing: true,
                    ajax: {
                        url: '/setup/academicrecognition/fetch',
                        type: 'GET',
                        dataSrc: function(json) {
                            return json;
                        }
                    },
                    columns: [{
                            "data": "isactive"
                        },
                        {
                            "data": "academic_recognition_desc"
                        },
                        {
                            "data": "minimum_grade"
                        },
                        {
                            "data": null
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
                                var iconHtml = rowData.isactive == 1 ? checkIcon : wrongIcon;

                                // Construct the HTML with the conditional icon only
                                var text = '<div style="text-align:center;">' + iconHtml + '</div>';

                                // Apply the constructed HTML and initialize tooltips
                                $(td).html(text).addClass('align-middle');
                                $(td).find('[data-toggle="tooltip"]').tooltip();
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.academic_recognition_desc).addClass(
                                    'align-middle');
                            }
                        },

                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // $(td).html('');
                                $(td).html(rowData.minimum_grade).addClass('align-middle');

                            }
                        },

                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var buttons =
                                    '<a href="javascript:void(0)" class="edit_academic_recognition" id="edit_academic_recognition" data-id="' +
                                    rowData.id +
                                    '"><i class="far fa-edit text-primary"></i></a>';
                                $(td)[0].innerHTML = buttons;
                                $(td).addClass('text-center align-middle');
                            }
                        },

                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var buttons =
                                    '<a href="javascript:void(0)" class="delete_academic_recognition" data-id="' +
                                    rowData.id +
                                    '"><i class="far fa-trash-alt text-danger"></i></a>';
                                $(td)[0].innerHTML = buttons;
                                $(td).addClass('text-center align-middle');
                            }
                        }

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

            $(document).on('click', '#updateRecognitonTypeBtn', function() {
                update_academic_recognition()
            })

            function update_academic_recognition() {
                var editrecognitionTypeId = $('#editrecognitionTypeId').val();
                var minGradeRequirement = $('#editminGradeRequirement').val();
                var setActivePointEquivalency = $('#edit_input_Active_recognitiontype').is(':checked') ? 1 : 0;
                var recognitionTypeDescription = $('#editrecognitionTypeDescription').val();

                $.ajax({
                    type: 'GET',
                    url: '/setup/academicrecognition/update',
                    data: {
                        recognitionTypeId: editrecognitionTypeId,
                        recognitionTypeDesc: recognitionTypeDescription,
                        minGradeRequirement: minGradeRequirement,
                        setActive: setActivePointEquivalency

                    },
                    success: function(data) {
                        if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                            academicRecognitionTable();

                        } else if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully updated'
                            })

                            academicRecognitionTable();
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }



            $('#editgradingComponentsModal').on('hidden.bs.modal', function(e) {
                // Clear search query
                $('#gradePointEquivalencyAddModal').modal()
            })




            $(document).on('click', '.edit_academic_recognition', function() {


                var academic_recognition_id = $(this).attr('data-id')

                $('#editgradingComponentsModal').modal()
                $('#gradePointEquivalencyAddModal').modal('hide')

                $.ajax({
                    type: 'GET',
                    url: '/setup/selectacademicrecognition/fetch',
                    data: {
                        academic_recognition_id: academic_recognition_id
                    },
                    success: function(response) {



                        var academic_recognition_selected = response.academicRecognition;


                        $("#editrecognitionTypeId").val(academic_recognition_selected[0].id);
                        $("#editrecognitionTypeDescription").val(
                            academic_recognition_selected[0].academic_recognition_desc);

                        if (parseFloat(academic_recognition_selected[0].isactive) === 1) {
                            $('#edit_input_Active_recognitiontype').prop('checked', true);
                        } else if (parseFloat(academic_recognition_selected[0].isactive) ===
                            0) {
                            $('#edit_input_Active_recognitiontype').prop('checked', false);
                        }

                        $("#editminGradeRequirement").val(academic_recognition_selected[0]
                            .minimum_grade).trigger('change');


                    }
                });




            });

            $(document).on('click', '.delete_academic_recognition', function() {

                var deleterecognitionTypeId = $(this).attr('data-id')
                delete_academic_recognition(deleterecognitionTypeId)

            });


            function delete_academic_recognition(deleterecognitionTypeId) {

                $.ajax({
                    type: 'GET',
                    url: '/setup/academicrecognition/delete',
                    data: {
                        recognitionTypeId: deleterecognitionTypeId

                    },
                    success: function(data) {
                        if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                            academicRecognitionTable();

                        } else if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully deleted'
                            })

                            academicRecognitionTable();
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }





            academic_select()

            function academic_select() {
                $.ajax({
                    type: 'GET',
                    url: '/college/get/yearlevel',
                    success: function(data) {
                        $('#filter_academic').append(`<option value="">Select Level</option>`);
                        $.each(data, function(index, item) {
                            $('#filter_academic').append(`
                        <option value="${item.id}">${item.levelname}</option>
                    `);
                        });
                    }
                })
            }

            $(document).on('change',
                '#filter_sy , #filter_semester, #filter_college, #filter_course, #filter_academic',
                function(data) {
                    get_student(data)

                })


            function get_student(data) {
                const sy = $('#filter_sy').val();
                const semester = $('#filter_semester').val();
                const college = $('#filter_college').val();

                const course = $('#filter_course').val();
                const academic = $('#filter_academic').val();


                $.ajax({
                    type: 'GET',
                    url: `/college/teacher/academicrecognition/student-list-for-all-grade-summary/${sy}/${semester}/${college}/${course}/${academic}`,
                    success: function(data) {
                        // datatable_2(data);
                        datatable_2_head(data);
                        // plot_subject_grades(data)
                    }
                });
            }


            function datatable_2_head(data) {
                const students = data.students || [];
                const studentGrades = data.studentsgrades || [];

                $("#academicRecognitionBody").empty();

                const sortedStudents = students.sort((a, b) => {
                    const aAvg = studentGrades.filter(grade => grade.id === a.id).reduce((acc, cur) => acc +
                        cur.avg_fg, 0) / studentGrades.filter(grade => grade.id === a.id).length;
                    const bAvg = studentGrades.filter(grade => grade.id === b.id).reduce((acc, cur) => acc +
                        cur.avg_fg, 0) / studentGrades.filter(grade => grade.id === b.id).length;
                    return aAvg.toString().localeCompare(bAvg.toString());
                    // return aAvg - bAvg;
                });

                let p = 1;
                sortedStudents.forEach(student => {
                    const studentGradesFiltered = studentGrades.filter(
                        grade => grade.id === student.id
                    );
                    const totalFG = studentGradesFiltered.reduce((acc, cur) => acc + cur.avg_fg, 0);

                    // const avg_fg = studentGradesFiltered.length > 0 && studentGradesFiltered[0]
                    //     .loadsubject_count_has_grades == studentGradesFiltered[0].loadsubject_count ?
                    //     (totalFG / studentGradesFiltered[0].loadsubject_count) :
                    //     "INC";
                    const avg_fg = studentGradesFiltered.length > 0 && studentGradesFiltered[0]
                        .loadsubject_count_has_grades == studentGradesFiltered[0].loadsubject_count ?
                        (totalFG / studentGradesFiltered[0].loadsubject_count) :
                        "INC";
                    // <td>${avg_fg !== "INC" && avg_fg ? avg_fg.toFixed(2) : 'INC'}</td>
                    let row = `
                <tr style="font-size: 11.5px;">
                    <td>${student.sid || ''}</td>
                    <td>${student.lastname || ''}, ${student.firstname || ''} ${student.middlename || ''} ${student.suffix || ''}</td>
                    <td>${student.gender || ''}</td>
                    <td>${student.levelname || ''}</td>
                    <td>${student.courseabrv || ''}</td>
                    <td></td>
                    <td>${avg_fg !== "INC" && avg_fg ? avg_fg.toFixed(2) : 'INC'}</td>
                    <td style="text-align: center;">${p}</td>
                    <td><a href="#" style="color: #blue; text-decoration: underline;" class="printCertificate" data-id="' +
                            student.id +
                            '">Print Certificate</a></td>
                </tr>`;
                    $("#academicRecognitionBody").append(row);
                    p++;
                });
            }





            // function datatable_2_head(data) {
            //     const students = data.students || [];
            //     const studentGrades = data.studentsgrades || [];

            //     $("#academicRecognitionBody").empty();

            //     // const students = Object.values(groupedByGender).flat();
            //     let p = 1;
            //     students.forEach(student => {
            //         let row = `
        //             <tr style="font-size: 11.5px;">
        //                 <td>${student.sid || ''}</td>
        //                 <td>${student.lastname || ''}, ${student.firstname || ''} ${student.middlename || ''} ${student.suffix || ''}</td>
        //                 <td>${student.gender || ''}</td>
        //                 <td>${student.levelname || ''}</td>
        //                 <td>${student.courseabrv || ''}</td>
        //                 <td></td>
        //                 <td>${studentGrades.avg_fg || ''}</td>
        //                 <td style="text-align: center;">${p}</td>
        //                 <td><a href="#" style="color: #blue; text-decoration: underline;" class="printCertificate" data-id="' +
        //                         student.id +
        //                         '">Print Certificate</a></td>
        //             </tr>`;
            //         $("#academicRecognitionBody").append(row);
            //         p++;
            //     });
            // }



            $('#addGradePointEquivalency').click(function() {
                $('#gradePointEquivalencyAddModal').modal({
                    keyboard: false
                });
                $('#gradePointEquivalencyAddModal').css('z-index', '1050');
                var table = $("#gradingPointsTable");
                // table.find("tbody tr:not(.default-row)").remove();
                table.find("tbody tr:not(.default-row)").remove();
                table.find("tbody .default-row").show();
                $("#gradePointEquivalencyDescription").val("");

                gradePointEquivalencyAddModal.show();
            });


            $('#certificateSetting').click(function() {
                $('#certficationTemplateModal').modal({
                    keyboard: false
                });

                $("#gradePointEquivalencyDescription").val("");

                certficationTemplateModal.show();
            });


            // $('#certificationtemplateButton').click(function() {
            //     $('#certTemplateComponentsModal').modal({
            //         show: true,
            //         backdrop: 'static',
            //         keyboard: false
            //     });
            //     $('#certTemplateComponentsModal').css('z-index', '1100');
            // });

            $('#certificationtemplateButton').click(function() {
                var certTemplateComponentsModal = new bootstrap.Modal($('#certTemplateComponentsModal')[
                    0], {
                    keyboard: false
                });
                certTemplateComponentsModal.show();
                $('#certTemplateComponentsModal').css('z-index', '1100');
            });


            $('#addRecognitionTpeButton').click(function() {
                var NewRecognitionTypeModal = new bootstrap.Modal($('#NewRecognitionTypeModal')[0], {
                    keyboard: false
                });
                NewRecognitionTypeModal.show();
            });




        });
    </script>
@endsection
