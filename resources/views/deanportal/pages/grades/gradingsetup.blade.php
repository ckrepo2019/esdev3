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

        .error-border {
            border: 1px solid red;
        }

        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }

        .select2-selection__choice {
            background-color: #fafafa !important;
            color: rgb(1, 1, 1) !important;
            font-family: Arial, Helvetica, sans-serif
        }
    </style>
@endsection


@section('content')
    @php
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
                <div class="col-md-12">
                    <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ url('setup/gradingsetup') }}">Grading Setup</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('setup/gradepointequivalancy') }}">Grade
                                Point Equivalancy</a>
                        </li>
                    </ul>
                </div>
                <div class="card shadow">
                    <div class="card-body">
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
                                            {{ $item->semester }}
                                        </option>
                                    @endforeach
                                    <option value="">All</option>

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
                        </section>
                        <div class="tab-content" id="studentInfoTabContent">
                            <div class="tab-pane fade show active" id="student-info" role="tabpanel"
                                aria-labelledby="student-grades-tab">
                                <div class="container-fluid mt-4">
                                    <div class="row d-flex justify-content-between align-items-center mb-3">
                                        <div class="col-auto">
                                            <button class="btn btn-success" id="addECRGrading" data-toggle="modal"
                                                data-target="#addECRModal">+ Add ECR Grading</button>
                                            <button class="btn btn-success ml-1" data-toggle="modal"
                                                data-target="#settingsModal">
                                                <i class="fas fa-cogs"></i> Add Term
                                            </button>
                                        </div>
                                        <div class="col-auto">
                                            <div class="input-group" style="width: 200px;">
                                                <input type="text" class="form-control form-control-sm" id="searchInput"
                                                    placeholder="Search">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    id="searchButton">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" style="font-size:12px;"
                                            id="ecrGradingTable">
                                            <thead>
                                                <tr>
                                                    <th scope="col">ECR Grading Description</th>
                                                    <th scope="col">Component Percentage</th>
                                                    <th scope="col">Term Applied</th>
                                                    <th scope="col">ECR Preview</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id = "ecrGradingBody">
                                            </tbody>
                                        </table>
                                    </div>
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

    <!-- Modal Structure -->

    <div class="modal fade" id="addECRModal" tabindex="-1" aria-labelledby="addECRModalLabel" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addECRModalLabel">e-Class Record Components Setup</h5>
                    <button type="button" id="closeModalBtn" class="close" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <!-- Form Content -->
                    <form id="ecrGradingForm">
                        <!-- ECR Description Field -->
                        <div class="mb-3">
                            <label for="ecrDescription" class="form-label">ECR Description</label>
                            <input type="text" class="form-control" id="ecrDescription"
                                placeholder="Enter Description" required>
                        </div>

                        <!-- Add Grading Components Button -->
                        <button type="button" id="addGradingComponentsButton" class="btn btn-success mb-3"
                            data-toggle="modal" data-target="#gradingComponentsModal">
                            + Add Grading Components
                        </button>
                        <!-- Grading Components Table -->
                        <div style="max-height: 200px; overflow-y: auto;">
                            <table class="table table-bordered table-sm" data-id="">
                                <thead>
                                    <tr>
                                        <th>Grading Component Description</th>
                                        <th>Component %</th>
                                        <th>Sub Component Name</th>
                                        <th>Comp. %</th>
                                        <th># of Columns in ECR</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="ecrComponentsBody">
                                    <!-- Table rows go here; dynamic content can be added here later -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Term Applied Dropdown and Set as Active Checkbox -->
                        <div class="d-flex align-items-center mt-3">
                            <label for="termApplied" class="form-label mr-2">Term Applied</label>
                            <select style="width: 80%;" class="form-select" id="termApplied" required multiple>
                                {{-- <option value="">Select Term</option> --}}
                                @foreach ($terms as $term)
                                    <option value="{{ $term->id }}">{{ $term->description }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row justify-content-center m-3">
                            <div class="col-md-6 d-flex align-items-center">
                                <input type="checkbox" id="setActive" class="form-check-input me-3" checked>
                                <label for="setActive" class="form-check-label">Set as Active</label>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-secondary ec-record" data-ecrid="">
                                    <i class="fas fa-eye"></i> e-Class Record Preview
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer with Save Button -->
                <div class="modal-footer">
                    <button type="submit" form="ecrGradingForm" class="btn btn-success" id="saveECR">SAVE</button>
                    <button type="button" class="btn btn-success d-none" id="updateECR" data-id="">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="gradingComponentsModal" tabindex="-1" aria-labelledby="gradingComponentsModalLabel"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gradingComponentsModalLabel">Add Grading Components</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-left mb-3">
                        <button type="button" class="btn btn-success btn-sm" id="addFrequencyButton">+ Add
                            Frequency</button>
                    </div>
                    <!-- Main Components Row -->
                    <div id="first_container"
                        style="background-color: #eaf6f5; border: .5px solid #efefef; box-shadow: 0 .5rem 0.5rem rgba(0, 0, 0, .15) !important;"
                        class="container gradingComponentsContainer">
                        <div class="row mb-2">
                            <div class="col-4">
                                <label for="gradingDescription" class="form-label">Grading Components
                                    Description</label>
                            </div>
                            <div class="col-4">
                                <label for="componentPercentage" class="form-label">Component %</label>
                            </div>
                            <div class="col-4">
                                <label for="columnsInECR" class="form-label"># of Columns in eCR</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <input type="text" class="form-control form-control-sm" id="gradingDescription"
                                    placeholder="Grading Component">
                            </div>
                            <div class="col-4">
                                <div class="input-group">
                                    <input type="number" class="form-control form-control-sm" id="componentPercentage"
                                        placeholder="0">
                                    <span class="input-group-text input-group-text-sm"><i
                                            class="fas fa-percentage"></i></span>
                                </div>
                            </div>
                            <div class="col-4">
                                <select class="form-select form-select-sm columnsInECR">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                </select>
                            </div>
                        </div>

                        <!-- With Sub-components checkbox -->
                        <div class="form-check ml-3 mb-3 p-2 ">
                            <input type="checkbox" class="form-check-input withSubComponents" id="">
                            <label class="form-check-label" for="withSubComponents">With Sub-components</label>
                            <button type="button" class="btn btn-link ml-3 addSubComponent" style="font-size: 10px"
                                disabled>+
                                Add Sub-Component</button>
                            <div class="subComponentContainer">
                            </div>
                        </div>
                    </div>

                    <!-- Add button -->
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-success" id="addGradingComponent">Add</button>
                        {{-- <button type="button" class="btn btn-success" id="updateGradingComponent"
                            style="display: none;">Update</button> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="settingsModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="settingsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsModalLabel">Term</h5>
                    <button type="button" id="closeSettingsModal" class="close" data-dismiss="modal"
                        aria-label="Close">
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
                                <th>Percentage %</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="termTableBody">
                            <!-- Table rows with data will go here -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-right"><strong>Total:</strong></td>
                                <td id="totalGradingCell">0</td>
                                <td></td>
                            </tr>
                        </tfoot>
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
                                    <option value="" disable>Select Term Frequency</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
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
                        <div class = "row mb-3">
                            <div class="col-6">
                                <label for="select2Field" class="form-label">Term Frequency</label>
                                <select class="form-select select2" id="quarter" style="width: 100%;">
                                    <option value="" disable>Select Term Frequency</option>
                                    <option value="1">1st Quarter</option>
                                    <option value="2">2nd Quarter</option>
                                    <option value="3">3rd Quarter</option>
                                    <option value="4">4th Quarter</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Modal footer with separate buttons for Add and Update -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="addTerm">Add</button>
                    {{-- <button type="button" class="btn btn-success d-none" id="updateTerm">Update</button> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" style="max-width: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">ECR Preview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body table-responsive" id="previewContent">
                    <!-- Fetched content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="gradingComponentsModalEdit" tabindex="-1" aria-labelledby="gradingComponentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gradingComponentsModalLabel">Add Grading Components</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Components Row -->
                    <div class="row mb-2">
                        <div class="col-4">
                            <label for="gradingDescription" class="form-label">Grading Components
                                Description</label>
                        </div>
                        <div class="col-4">
                            <label for="componentPercentage" class="form-label">Component %</label>
                        </div>
                        <div class="col-4">
                            <label for="columnsInECR" class="form-label"># of Columns in eCR</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <input type="text" class="form-control form-control-sm" id="editgradingDescription"
                                placeholder="Grading Component">
                        </div>
                        <div class="col-4">
                            <div class="input-group">
                                <input type="number" class="form-control form-control-sm" id="editcomponentPercentage"
                                    placeholder="0">
                                <span class="input-group-text input-group-text-sm"><i
                                        class="fas fa-percentage"></i></span>
                            </div>
                        </div>
                        <div class="col-4">
                            <select class="form-select form-select-sm" id="editcolumnsInECR">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                            </select>
                        </div>
                    </div>

                    <!-- With Sub-components checkbox -->
                    <div class="form-check ml-3 mb-3">
                        <input type="checkbox" class="form-check-input" id="withSubComponentsupdate">
                        <label class="form-check-label" for="withSubComponents">With Sub-components</label>
                    </div>

                    <!-- Sub-component Container -->
                    <div id="subComponentContainer" style="display: none;">
                        <button type="button" id="addSubComponentupdate" class="btn btn-link ml-3"
                            style="font-size: 10px">+
                            Add Sub-Component</button>
                    </div>

                    <!-- Add button -->
                    <div class="text-center">
                        {{-- <button type="button" class="btn btn-success" id="addGradingComponent">Add</button> --}}
                        <button type="button" class="btn btn-success" id="updateGradingComponent">Update</button>
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
        $('#filter_sy').select2()
        $('#filter_semester').select2()
        $('#termApplied').select2({
            placeholder: 'Select Term(s)',
            allowClear: true
        });
        $('.columnsInECR').select2();
        $('.subcolumnECR').select2();
        $('#termFrequency').select2();
        $('#subcolumnsInECR').select2();
        $('#quarter').select2();
        // $('#addECRGrading').on('click', function() {
        //     var addECRModal = new bootstrap.Modal($('#addECRModal')[0], {
        //         keyboard: false
        //     });
        //     addECRModal.show();

        // });

        // $('#addGradingComponentsButton').on('click', function() {
        //     var gradingComponentsModal = new bootstrap.Modal($('#gradingComponentsModal')[0], {
        //         keyboard: false
        //     });
        //     gradingComponentsModal.show();
        // });

        // $('#withSubComponents').on('change', function() {
        //     const isChecked = $(this).is(':checked');
        //     $('#subComponentContainer').css('display', isChecked ? 'block' : 'none');
        // });


        $(document).ready(function() {

            $.ajax({
                url: '/termIndex',
                method: 'GET',
                success: function(response) {
                    let termIds = response.map(term => term.id);

                    $('#termApplied').val(termIds).trigger('change');
                },
                error: function() {
                    console.error('Failed to fetch terms.');
                }
            });

            function calculateTotalGrading() {
                let total = 0;
                $('#termTableBody tr').each(function() {
                    total += parseFloat($(this).find('td:eq(2)').text()) || 0;
                });

                $('#totalGradingCell').text(total);
                return total;
            }

            // Prevent Modal from Closing If Total ≠ 100
            $('#settingsModal').on('hide.bs.modal', function(e) {
                let total = calculateTotalGrading();
                if (total !== 100) {
                    e.preventDefault(); // Stop modal from closing
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Total grading percentage must be exactly 100% before closing.',
                        icon: 'warning',
                        confirmButtonText: 'Okay'
                    });
                }
            });

            calculateTotalGrading();


            // Prevent Modal from Closing If Total ≠ 100
            $('#settingsModal').on('hide.bs.modal', function(e) {
                let total = calculateTotalGrading();
                if (total !== 100) {
                    e.preventDefault(); // Stop modal from closing
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Total grading percentage must be exactly 100% before closing.',
                        icon: 'warning',
                        confirmButtonText: 'Okay'
                    });
                }
            });

            $('#addTerm').on('click', function(e) {
                e.preventDefault();
                var quarter = $('#quarter').val();
                let valid = true;

                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');
                $('.border-danger').removeClass('border-danger');

                let termName = $('#termName');
                if (termName.val().trim() === '') {
                    termName.addClass('is-invalid');
                    termName.closest('.form-group').append(
                        '<div class="invalid-feedback">This field is required.</div>');
                    valid = false;
                }

                let termFrequency = $('#termFrequency');
                if (!termFrequency.val()) {
                    termFrequency.addClass('is-invalid');
                    termFrequency.closest('.form-group').append(
                        '<div class="invalid-feedback">Please select a valid option.</div>');
                    valid = false;
                }

                let gradingPercentage = $('#formControl');
                let gradingValue = parseFloat(gradingPercentage.val());
                if (gradingPercentage.val().trim() === '') {
                    gradingPercentage.addClass('is-invalid');
                    gradingPercentage.closest('.form-group').append(
                        '<div class="invalid-feedback">This field is required.</div>');
                    valid = false;
                } else if (isNaN(gradingValue) || gradingValue <= 0 || gradingValue > 100) {
                    gradingPercentage.addClass('is-invalid');
                    gradingPercentage.closest('.form-group').append(
                        '<div class="invalid-feedback">Please enter a valid number between 0 and 100.</div>'
                    );
                    valid = false;
                }

                let currentTotal = calculateTotalGrading();
                if (valid && currentTotal + gradingValue > 100) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Total grading cannot exceed 100%. Please adjust the values.',
                        icon: 'error',
                        confirmButtonText: 'Okay'
                    });
                    valid = false;
                }

                if (!valid) return;

                let formData = {
                    termName: termName.val(),
                    termFrequency: termFrequency.val(),
                    gradingPercentage: gradingPercentage.val(),
                    quarter: $('#quarter').val(),
                    _token: '{{ csrf_token() }}'
                };

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
                                <i class="fas fa-edit text-info edit-term" data-id="${response.term.id}" role="button"></i>
                                <i class="fas fa-trash-alt text-danger delete-term" data-id="${response.term.id}" role="button"></i>
                            </td>
                        </tr>`;

                        $('#termTableBody').append(newRow);

                        calculateTotalGrading();

                        $('#termName').val('');
                        $('#termFrequency').val('').trigger('change');
                        $('#formControl').val('');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });


            $('#updateTerm').on('click', function(e) {
                e.preventDefault();
                let termId = $(this).data('id');
                let newPercentage = parseFloat($('#formControl').val()) || 0;

                // Calculate total grading percentage excluding the current term
                let currentTotal = 0;
                $('table tbody tr').each(function() {
                    let id = $(this).data('id');
                    if (id != termId) {
                        currentTotal += parseFloat($(this).find('td:eq(2)').text()) || 0;
                    }
                });

                let newTotal = currentTotal + newPercentage;

                if (newTotal > 100) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Total grading percentage cannot exceed 100%.',
                        icon: 'error',
                        confirmButtonText: 'Okay'
                    });
                    return;
                }

                let formData = {
                    termName: $('#termName').val(),
                    termFrequency: $('#termFrequency').val(),
                    gradingPercentage: newPercentage,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'PUT'
                };

                $.ajax({
                    url: '/update/terms/' + termId,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        $('#addTermModal').modal('hide');
                        Swal.fire({
                            title: 'Updated!',
                            text: response.success,
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        });

                        // Update the table row
                        let row = $(`tr[data-id="${termId}"]`);
                        row.find('td').eq(0).text(formData.termName);
                        row.find('td').eq(1).text(formData.termFrequency);
                        row.find('td').eq(2).text(formData.gradingPercentage);

                        calculateTotalGrading(); // Recalculate total grading

                        $('#addTermModalLabel').text('Add New Term');
                        $('#addTerm').removeClass('d-none');
                        $('#updateTerm').addClass('d-none');
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON.error || 'Something went wrong.',
                            icon: 'error',
                            confirmButtonText: 'Okay'
                        });
                    }
                });
            });


            $('#addTermButton').on('click', function() {
                $('#termName').val('');
                $('#formControl').val('');

            });

            $('#termTableBody').on('click', '.edit-term', function() {
                let termId = $(this).data('id');
                let row = $(`tr[data-id="${termId}"]`);
                let termName = row.find('td').eq(0).text();
                let termFrequency = row.find('td').eq(1).text();
                let gradingPercentage = row.find('td').eq(2).text();

                $('#termName').val(termName);
                $('#termFrequency').val(termFrequency);
                $('#formControl').val(gradingPercentage);

                $('#addTermModalLabel').text('Update Term');
                $('#addTerm').addClass('d-none');
                $('#updateTerm').removeClass('d-none').data('id', termId);

                $('#addTermModal').modal('show');
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

                        calculateTotalGrading();
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }

            $('#termTable').on('click', '.edit-term', function() {
                let termId = $(this).data('id');
                fetchTermDetails(termId);
            });

            $('#termTable').on('click', '.delete-term', function() {
                let termId = $(this).data('id');
                deleteTerm(termId);
            });

            function fetchTermDetails(termId) {
                $.ajax({
                    url: '/edit/terms/' + termId,
                    type: 'GET',
                    success: function(data) {
                        console.log(data, 'yo breakitdown');

                        $('#termFrequency').val(data.Term_frequency).trigger('change');
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

            $('#settingsModal').on('show.bs.modal', function() {
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
                                        <i class="fas fa-edit text-info edit-term" data-id="${term.id}" role="button"></i>
                                        <i class="fas fa-trash-alt text-danger delete-term" data-id="${term.id}" role="button"></i>
                                    </td>
                                </tr>`;
                        });
                        $('#settingsModal tbody').html(tbody);
                        calculateTotalGrading();
                    },
                    error: function(error) {
                        console.log("Error fetching terms:", error);
                    }
                });
            });

            getECR();

            function getECR() {
                $.ajax({
                    url: '/display/ECR',
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        sy: $('#filter_sy').val(),
                        semester: $('#filter_semester').val()
                    },
                    success: function(response) {
                        populateECRTable(response);

                    },
                    error: function(xhr, status, error) {
                        console.log('Error fetching ECR grading data:', error);
                    }
                });
            }

            function populateECRTable(data) {
                let tbody = $('#ecrGradingBody');
                tbody.empty();

                if (!Array.isArray(data)) {
                    console.error('Data provided is not an array.');
                    return;
                }

                data.forEach(subject => {
                    console.log(subject);

                    const {
                        ecrID,
                        ecrDesc,
                        components = [],
                        terms = []
                    } = subject;

                    const termBadges = terms.map((term, index) => {
                        return `<span class="badge bg-${getBadgeColor(index)} me-1">${term.termDesc} ${term.gradingPerc}%</span>`;
                    }).join(' ');

                    const componentDetails = components.map((component, index) => {
                        const {
                            component: compPercentage,
                            subComponents = []
                        } = component;

                        const subComponentDetails = subComponents.map(subComponent => {
                            return subComponent.subComponent ? (
                                `(${subComponent.subComponent}%)`) : '';
                        }).filter(detail => detail !== '').join(' ');

                        return `<span style="color: ${getComponentColor(index)};">${compPercentage}% ${subComponentDetails}</span>`;
                    }).join(', ');

                    // Append the row to the table
                    let row = `
                        <tr data-id="${ecrID}">
                            <td>${ecrDesc}</td>
                            <td>${componentDetails}</td>
                            <td>${termBadges}</td>
                            <td>
                                <a href="#" style="text-decoration: underline;" id="printECR" data-id="${ecrID}">View ECR</a>
                            </td>
                            <td>
                                <i class="fas fa-edit text-info editECRGrading" role="button" data-id="${ecrID}"></i>
                                <i class="fas fa-trash-alt text-danger ms-2 deleteECRGrading" role="button" data-id="${ecrID}"></i>
                            </td>
                        </tr>`;
                    tbody.append(row);
                });

                function getBadgeColor(index) {
                    const colors = ['primary', 'success', 'danger', 'warning', 'info'];
                    return colors[index % colors.length];
                }

                function getComponentColor(index) {
                    const colors = ['blue', 'green', 'red'];
                    return colors[index % colors.length];
                }
            }

            $("#addFrequencyButton").click(function() {
                let newComponent = `
                    <div class="gradingComponentsContainer mt-3 p-2" style="background-color: #eaf6f5; border: .5px solid #efefef; box-shadow: 0 .5rem 0.5rem rgba(0, 0, 0, .15) !important;" class="container mt-3">
                        <div class="row mb-2">
                            <div class="col-4">
                                <label class="form-label">Grading Components Description</label>
                            </div>
                            <div class="col-4">
                                <label class="form-label">Component %</label>
                            </div>
                            <div class="col-4">
                                <label class="form-label"># of Columns in eCR</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <input type="text" class="form-control form-control-sm" placeholder="Grading Component">
                            </div>
                            <div class="col-4">
                                <div class="input-group">
                                    <input type="number" class="form-control form-control-sm" placeholder="0">
                                    <span class="input-group-text input-group-text-sm"><i class="fas fa-percentage"></i></span>
                                </div>
                            </div>
                            <div class="col-4">
                                <select class="form-select form-select-sm select columnsInECR" value="1">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-check ml-3 mb-3 p-2">
                            <input type="checkbox" class="form-check-input withSubComponents" id="">
                            <label class="form-check-label">With Sub-components</label>
                            <button type="button" class="btn btn-link ml-3 addSubComponent" style="font-size: 10px" disabled>+ Add Sub-Component</button>
                            <div class="subComponentContainer">
                            </div>
                        </div>
                    </div>`;

                $("#first_container").after(newComponent);

                $(newComponent).find('.select2').select2();
            });

            $(document).on("change", ".withSubComponents", function() {
                let subComponentContainer = $(this).closest(".gradingComponentsContainer").find(
                    ".subComponentContainer");
                let addSubComponentButton = $(this).siblings(".addSubComponent");

                if ($(this).is(":checked")) {
                    addSubComponentButton.prop("disabled", false);
                    let subComponentRow = `
                        <div class="row mb-2 subComponentRow">
                            <div class="col-4">
                                <input type="text" class="form-control form-control-sm" placeholder="Sub-Component Description">
                            </div>
                            <div class="col-4">
                                <div class="input-group">
                                    <input type="number" class="form-control form-control-sm" placeholder="0">
                                    <span class="input-group-text input-group-text-sm"><i class="fas fa-percentage"></i></span>
                                </div>
                            </div>
                            <div class="col-3">
                                <select class="form-select form-select-sm select2 subcolumnECR" style="width: 100%;">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                </select>
                            </div>
                            
                            <div class="col-1">
                                <i class="fas fa-trash-alt text-danger ms-2 deleteECRGrading" role="button" data-id="1"></i>
                        </div>
                    `;
                    subComponentContainer.append(subComponentRow);
                    $(subComponentContainer).find('.select2').select2();
                } else {
                    addSubComponentButton.prop("disabled", true);
                    subComponentContainer.empty();
                }
            });



            $(document).on("click", ".addSubComponent", function() {
                if ($(this).prop("disabled")) return;

                let subComponentRow = `
                        <div class="row mb-2 subComponentRow">
                            <div class="col-4">
                                <input type="text" class="form-control form-control-sm" placeholder="Sub-Component Description">
                            </div>
                            <div class="col-4">
                                <div class="input-group">
                                    <input type="number" class="form-control form-control-sm" placeholder="0">
                                    <span class="input-group-text input-group-text-sm"><i class="fas fa-percentage"></i></span>
                                </div>
                            </div>
                            <div class="col-2">
                                <select class="form-select form-select-sm select subcolumnECR">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                </select>
                            </div>
                        </div>
                    `;

                $(this).siblings(".subComponentContainer").append(subComponentRow);
            });


            $(document).on("click", "#addGradingComponent", function() {
                $(".gradingComponentsContainer").each(function() {
                    let gradingDescription = $(this).find("input[type='text']").val().trim();
                    let componentPercentage = $(this).find("input[type='number']").val().trim();
                    let columnsInECR = $(this).find(".columnsInECR").val();
                    let hasSubComponents = $(this).find(".withSubComponents").is(":checked");

                    if (gradingDescription === "" || componentPercentage === "") {
                        alert("Please fill out all fields.");
                        return;
                    }

                    let subComponents = [];
                    if (hasSubComponents) {
                        $(this).find(".subComponentRow").each(function() {
                            let subDescription = $(this).find("input[type='text']").val()
                                .trim();
                            let subPercentage = $(this).find("input[type='number']").val()
                                .trim();
                            let subColumnsInECR = $(this).find(".subcolumnECR").val();

                            subComponents.push({
                                description: subDescription,
                                percentage: subPercentage,
                                columns: subColumnsInECR
                            });
                        });
                    }

                    let totalRows = hasSubComponents ? subComponents.length : 1;

                    let newRow = `
                            <tr class="componentRow">
                                <td rowspan="${totalRows}">${gradingDescription}</td>
                                <td rowspan="${totalRows}">${componentPercentage}%</td>
                        `;

                    if (hasSubComponents && subComponents.length > 0) {
                        newRow += `
                            <td>${subComponents[0].description}</td>
                            <td>${subComponents[0].percentage}%</td>
                            <td>${subComponents[0].columns}</td>
                            <td rowspan="${totalRows}">
                                <button class="btn btn-sm btn-primary editRow"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger deleteRow"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        `;

                        for (let i = 1; i < subComponents.length; i++) {
                            newRow += `
                            <tr>
                                <td>${subComponents[i].description}</td>
                                <td>${subComponents[i].percentage}%</td>
                                <td>${subComponents[i].columns}</td>
                            </tr>
                            `;
                        }
                    } else {
                        newRow += `
                            <td></td>
                            <td></td>
                            <td>${columnsInECR}</td>
                            <td>
                                <button class="btn btn-sm btn-primary editRow"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger deleteRow"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        `;
                    }

                    $("#ecrComponentsBody").append(newRow);
                });

                $("#gradingComponentsModal").hide();
            });


            // Delete row functionality
            $(document).on("click", ".deleteRow", function() {
                $(this).closest("tr").nextUntil("tr:has(td[rowspan])").remove();
                $(this).closest("tr").remove();
            });

            $(document).on("click", "#saveECR", function() {
                let components = [];
                let totalComponentPercentage = 0; // Track the total percentage of components
                let isValid = true; // Flag to check validation

                $(".gradingComponentsContainer").each(function() {
                    let gradingDescription = $(this).find("input[type='text']").val().trim();
                    let componentPercentage = parseFloat($(this).find("input[type='number']").val()
                        .trim()) || 0; // Ensure it's a number
                    let columnsInECR = $(this).find(".columnsInECR").val();
                    let hasSubComponents = $(this).find(".withSubComponents").is(":checked");

                    // Validate if description and percentage are filled
                    if (gradingDescription === "" || componentPercentage <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please fill out all fields correctly (grading description and percentage).'
                        });
                        isValid = false; // Set isValid to false
                        return false; // Break out of the loop
                    }

                    let componentData = {
                        description: gradingDescription,
                        percentage: componentPercentage,
                        columns: columnsInECR,
                        subcomponents: []
                    };

                    if (hasSubComponents) {
                        $(this).find(".subComponentRow").each(function() {
                            let subDescription = $(this).find("input[type='text']").val()
                                .trim();
                            let subPercentage = parseFloat($(this).find(
                                "input[type='number']").val().trim()) || 0;
                            let subColumnsInECR = $(this).find(".subcolumnECR").val();

                            // Validate subcomponent description and percentage
                            if (subDescription === "" || subPercentage <= 0) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validation Error',
                                    text: 'Please fill out all subcomponent fields correctly.'
                                });
                                isValid = false; // Set isValid to false
                                return false; // Break out of the loop
                            }

                            componentData.subcomponents.push({
                                description: subDescription,
                                percentage: subPercentage,
                                columns: subColumnsInECR
                            });
                        });
                    }

                    totalComponentPercentage +=
                        componentPercentage; // Add to the total component percentage
                    components.push(componentData);
                });

                // Collect the form data to be sent in the request
                let requestData = {
                    syid: $("#filter_sy").val(),
                    semid: $("#filter_semester").val(),
                    termID: $('#termApplied').val() || [],
                    ecrDescription: $("#ecrDescription").val().trim(),
                    setActive: $("#setActive").is(":checked") ? 1 : 0,
                    components: components
                };

                // **Validation Checks**
                if (!requestData.ecrDescription) {
                    Swal.fire({
                        type: 'error',
                        title: 'No ECR Description',
                        text: 'ECR Description is required.'
                    });
                    isValid = false; // Set isValid to false
                }

                if (requestData.termID.length === 0) {
                    Swal.fire({
                        type: 'error',
                        title: 'No Term Selected',
                        text: 'Please select at least one term.'
                    });
                    isValid = false; // Set isValid to false
                }

                if (totalComponentPercentage !== 100) {
                    Swal.fire({
                        type: 'error',
                        title: 'Component Percentage Error',
                        text: 'The total component percentage must be exactly 100%.'
                    });
                    isValid = false; // Set isValid to false
                }

                // If any validation fails, stop the AJAX request
                if (!isValid) {
                    return;
                }

                // **Proceed with AJAX if validation passes**
                $.ajax({
                    url: "/ECR-grading/adding",
                    type: "POST",
                    data: JSON.stringify(requestData),
                    contentType: "application/json",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(response) {
                        getECR(); // Refresh ECR data
                        Swal.fire({
                            type: 'success',
                            title: 'Success',
                            text: 'ECR Grading Added Successfully',
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", xhr.responseText);
                        Swal.fire({
                            type: 'error',
                            title: 'Error',
                            text: 'Failed to save ECR Grading.'
                        });
                    }
                });
            });


            $(document).on("click", ".editRow", function() {
                console.log('asdasdasdasdasdasdasdasdasd');

                let row = $(this).closest("tr");
                let gradingDescription = row.find("td:eq(0)").text().trim();
                let componentPercentage = row.find("td:eq(1)").text().replace('%', '').trim();
                let columnsInECR = row.find("td:eq(4)").text().trim();

                let subComponents = [];
                let nextRow = row.next();

                while (nextRow.length && nextRow.hasClass("subComponent")) {
                    let subDescription = nextRow.find("td:eq(0)").text().trim();
                    let subPercentage = nextRow.find("td:eq(1)").text().replace('%', '').trim();
                    let subColumnsInECR = nextRow.find("td:eq(2)").text().trim();

                    subComponents.push({
                        description: subDescription,
                        percentage: subPercentage,
                        columns: subColumnsInECR
                    });

                    nextRow = nextRow.next();
                }

                $("#gradingDescriptionInput").val(gradingDescription);
                $("#componentPercentageInput").val(componentPercentage);
                $("#columnsInECRSelect").val(columnsInECR);

                if (subComponents.length > 0) {
                    $("#withSubComponentsCheckbox").prop("checked", true).trigger("change");
                    let subComponentContainer = $(".subComponentContainer");
                    subComponentContainer.empty();

                    subComponents.forEach((sub, index) => {
                        let subComponentRow = `
                            <div class="row mb-2 subComponentRow">
                                <div class="col-4">
                                    <input type="text" class="form-control form-control-sm subDescription" value="${sub.description}">
                                </div>
                                <div class="col-4">
                                    <div class="input-group">
                                        <input type="number" class="form-control form-control-sm subPercentage" value="${sub.percentage}">
                                        <span class="input-group-text input-group-text-sm"><i class="fas fa-percentage"></i></span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <select class="form-select form-select-sm subcolumnECR">
                                        <option value="1" ${sub.columns == 1 ? "selected" : ""}>1</option>
                                        <option value="2" ${sub.columns == 2 ? "selected" : ""}>2</option>
                                        <option value="3" ${sub.columns == 3 ? "selected" : ""}>3</option>
                                        <option value="4" ${sub.columns == 4 ? "selected" : ""}>4</option>
                                        <option value="5" ${sub.columns == 5 ? "selected" : ""}>5</option>
                                        <option value="10" ${sub.columns == 10 ? "selected" : ""}>10</option>
                                        <option value="15" ${sub.columns == 15 ? "selected" : ""}>15</option>
                                    </select>
                                </div>
                            </div>`;
                        subComponentContainer.append(subComponentRow);
                    });
                } else {
                    $("#withSubComponentsCheckbox").prop("checked", false).trigger("change");
                }

                $("#gradingComponentsModal").modal("show");

                $("#saveChangesButton").off("click").on("click", function() {
                    row.find("td:eq(0)").text($("#gradingDescriptionInput").val());
                    row.find("td:eq(1)").text($("#componentPercentageInput").val() + "%");
                    row.find("td:eq(4)").text($("#columnsInECRSelect").val());

                    let updatedSubComponents = $(".subComponentRow");
                    let nextRow = row.next();
                    updatedSubComponents.each(function() {
                        if (nextRow.length && nextRow.hasClass("subComponent")) {
                            nextRow.find("td:eq(0)").text($(this).find(".subDescription")
                                .val());
                            nextRow.find("td:eq(1)").text($(this).find(".subPercentage")
                                .val() + "%");
                            nextRow.find("td:eq(2)").text($(this).find(".subcolumnECR")
                                .val());

                            nextRow = nextRow.next();
                        }
                    });

                    $("#gradingComponentsModal").modal("hide");
                });
            });


            // $('#addGradingComponent').on('click', function() {
            //     const description = $('#gradingDescription').val().trim();
            //     const percentage = parseFloat($('#componentPercentage').val().trim()) || 0;
            //     const columns = $('#columnsInECR').val();
            //     const subComponentCheckbox = $('#withSubComponents');

            //     // Clear previous error messages
            //     $('.is-invalid').removeClass('is-invalid');
            //     $('.error-message').remove();

            //     // Basic validation for main component
            //     if (!description || percentage <= 0 || !columns) {
            //         Swal.fire('Validation Error', 'Please fill in all required fields with valid values.',
            //             'error');
            //         if (!description) $('#gradingDescription').addClass('is-invalid border-danger').after(
            //             '<div class="error-message text-danger">Required</div>');
            //         if (percentage <= 0) $('#componentPercentage').addClass('is-invalid border-danger')
            //             .after(
            //                 '<div class="error-message text-danger"></div>');
            //         return;
            //     }

            //     const componentID = `component-${Date.now()}`;
            //     const newRow = `
        //         <tr class="componentRow" id="${componentID}" data-id="${componentID}">
        //             <td>${description}</td>
        //             <td>${percentage}%</td>
        //             <td></td>
        //             <td></td>
        //             <td>${columns}</td>
        //             <td>
        //                 <i class="fas fa-edit text-info editGrading" role="button"></i>
        //                 <i class="fas fa-trash-alt text-danger deleteGrading" role="button"></i>
        //             </td>
        //         </tr>
        //     `;

            //     const $newRow = $(newRow);
            //     $('#ecrComponentsBody').append($newRow);

            //     // Adding subcomponents if checkbox is checked
            //     if (subComponentCheckbox.is(':checked')) {
            //         let subComponentsValid = true;
            //         let totalSubPercentage = 0;
            //         let subComponentRows = '';

            //         $('#subComponentContainer .subComponentRowInput').each(function(index) {
            //             const subDescription = $(this).find('.subComponentDescription').val()
            //                 .trim();
            //             const subPercentage = parseFloat($(this).find('.subComponentPercentage')
            //                 .val().trim()) || 0;
            //             const subColumns = $(this).find('.subcolumnsInECR').val().trim();

            //             // Validation for subcomponents
            //             if (!subDescription || subPercentage <= 0 || !subColumns) {
            //                 subComponentsValid = false;
            //                 if (!subDescription) $(this).find('.subComponentDescription').addClass(
            //                     'is-invalid').after(
            //                     '<div class="error-message text-danger">Required</div>');
            //                 if (subPercentage <= 0) $(this).find('.subComponentPercentage')
            //                     .addClass('is-invalid').after(
            //                         '<div class="error-message text-danger"></div>');
            //                 if (!subColumns) $(this).find('.subcolumnsInECR').addClass('is-invalid')
            //                     .after('<div class="error-message text-danger">Required</div>');
            //                 return;
            //             }

            //             totalSubPercentage += subPercentage;

            //             const subComponentID = `subComponent-${Date.now()}-${index}`;
            //             subComponentRows += `
        //                 <tr class="subComponentRow" id="${subComponentID}" data-parent-id="${componentID}">
        //                     <td colspan="2"></td>
        //                     <td>${subDescription}</td>
        //                     <td>${subPercentage}%</td>
        //                     <td>${subColumns}</td>
        //                     <td></td>
        //                 </tr>
        //             `;
            //         });

            //         if (totalSubPercentage !== 100) {
            //             Swal.fire('Validation Error', 'Subgrading components must total 100%!', 'error');
            //             $newRow.remove();
            //             return;
            //         }

            //         if (subComponentsValid) {
            //             $newRow.after(subComponentRows);
            //         } else {
            //             Swal.fire('Validation Error', 'Please fill in all subcomponent fields correctly.',
            //                 'error');
            //             $newRow.remove();
            //             return;
            //         }
            //     }

            //     // Clear the modal inputs after submission
            //     $('#gradingDescription').val('');
            //     $('#componentPercentage').val('');
            //     $('#columnsInECR').prop('disabled', false).val(null).trigger('change');
            //     $('.subComponentRowInput ').remove('');
            //     $('#withSubComponents').prop('checked', false);
            //     $('#subComponentDescription').val('');
            //     $('#subComponentPercentage').val('');
            //     $('#subcolumnsInECR').val(null).trigger('change');

            //     // Close the modal
            //     $('#gradingComponentsModal').modal('hide');
            // });

            // $('#gradingComponentsModal').on('hidden.bs.modal', function() {
            //     $('#gradingDescription').val('');
            //     $('#componentPercentage').val('');
            //     $('#columnsInECR').val(1).removeAttr('disabled').trigger('change');

            //     $('.subComponentRowInput ').remove('') // Hide and clear subcomponents
            //     $('#withSubComponents').prop('checked', false).prop('disabled',
            //         false);
            //     $('#subComponentDescription').val('');
            //     $('#subComponentPercentage').val('');
            //     $('#subcolumnsInECR').val(null).removeAttr('disabled').trigger('change');

            //     $('.error-message').remove();
            // });


            $('#addSubComponent').on('click', function() {
                const subComponentID = `subComponentInput-${Date.now()}`;
                const subComponentInput = `
                    <div class="row mb-2 subComponentRowInput d-flex align-items-center" data-sub-id="0" data-id="${subComponentID}">
                        <div class="col-5 d-flex justify-content-center">
                            <input type="text" class="form-control form-control-sm subComponentDescription" placeholder="Sub Component Name">
                        </div>
                        <div class="col-3">
                            <div class="input-group">
                                <input type="number" class="form-control form-control-sm subComponentPercentage" placeholder="Percentage">
                                <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                            </div>
                        </div>
                        <div class="col-3">
                            <select class="form-control form-control-sm select2 subcolumnsInECR">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                            </select>
                        </div>
                        <div class="col-1 text-end">
                            <button type="button" class="btn btn-sm btn-danger deleteSubComponent">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                `;
                $('#subComponentContainer').append(subComponentInput);
                $('#subComponentContainer .subcolumnsInECR').last().select2();

            });

            $('#withSubComponents').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#subComponentContainer').show();
                    $('#columnsInECR').attr('disabled', true);
                } else {
                    $('#subComponentContainer').hide();
                    $('#columnsInECR').removeAttr('disabled');
                }
            });

            $(document).on('click', '.deleteSubComponent', function() {
                const subComponentRow = $(this).closest('.subComponentRowInput');
                subComponentRow.remove();
            });

            $(document).on('click', '#closeModalBtn', function(event) {
                event.preventDefault();
                var unsavedChanges = false;

                if ($('#ecrDescription').val().trim()) {
                    unsavedChanges = true;
                }

                $('#ecrComponentsBody tr').each(function() {
                    const componentDescription = $(this).find('td').eq(0).text().trim();
                    const componentPercentage = parseFloat($(this).find('td').eq(1).text().replace(
                        '%', '').trim()) || 0;

                    if (componentDescription && componentPercentage > 0) {
                        unsavedChanges = true;
                    }
                });

                if (unsavedChanges) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Unsaved Changes Detected!',
                        text: 'You have unsaved changes. Do you really want to close the modal?',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Close it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.value) {
                            console.log('User confirmed to close the modal');
                            $('#addECRModal').removeClass('show');
                            $('#addECRModal').css('display', 'none');
                            $('.addECRModal').remove();
                            $('#addECRModal').modal('hide');
                        }
                    });
                } else {
                    $('#addECRModal').removeClass('show');
                    $('#addECRModal').css('display', 'none');
                    $('.addECRModal').remove();
                    $('#addECRModal').modal('hide');
                }
            });

            // $('#saveECR').on('click', function(event) {
            //     event.preventDefault();

            //     const formData = {
            //         ecrDescription: $('#ecrDescription').val(),
            //         termID: $('#termApplied').val() || [],
            //         setActive: $('#setActive').is(':checked') ? 1 : 0,
            //         semid: $('#filter_semester').val(),
            //         syid: $('#filter_sy').val(),
            //         components: [],
            //     };

            //     let totalPercentage = 0;
            //     let isValid = true;

            //     $('.error-border').removeClass('error-border');
            //     $('.error-message').remove();

            //     // Validate ECR Description
            //     const ecrDescription = formData.ecrDescription.trim();
            //     if (!ecrDescription) {
            //         $('#ecrDescription').addClass('error-border').after(
            //             '<div class="error-message">This field is required.</div>');
            //         Swal.fire('Validation Error', 'ECR Description is required.', 'error');
            //         isValid = false;
            //     }

            //     // Validate Term ID, Semester ID, and SY ID
            //     ['termID', 'semid', 'syid'].forEach(id => {
            //         if (!formData[id]) {
            //             $(`#${id}`).addClass('error-border').after(
            //                 '<div class="error-message">This field is required.</div>');
            //             isValid = false;
            //         }
            //     });

            //     // Validate components and subcomponents
            //     $('#ecrComponentsBody .componentRow').each(function() {
            //         const row = $(this);
            //         const componentDescription = row.find('td').eq(0).text().trim();
            //         const componentPercentage = parseFloat(row.find('td').eq(1).text().replace('%',
            //             '').trim()) || 0;
            //         const columnsInECR = parseInt(row.find('td').eq(4).text().trim()) || 0;

            //         if (!componentDescription || componentPercentage <= 0 || componentPercentage >
            //             100) {
            //             Swal.fire('Validation Error', 'Invalid component data detected.', 'error');
            //             row.find('td').eq(0).addClass('error-border');
            //             isValid = false;
            //         }

            //         totalPercentage += componentPercentage;

            //         const componentData = {
            //             description: componentDescription,
            //             componentPercentage: componentPercentage,
            //             columnsInECR: columnsInECR,
            //             subComponents: [],
            //         };

            //         $(`#ecrComponentsBody .subComponentRow[data-parent-id="${row.attr('data-id')}"]`)
            //             .each(function() {
            //                 const subRow = $(this);
            //                 const subDescription = subRow.find('td').eq(1).text().trim();
            //                 const subPercentage = parseFloat(subRow.find('td').eq(2).text()
            //                     .replace('%', '').trim()) || 0;
            //                 const subColumns = parseInt(subRow.find('td').eq(3).text()
            //                     .trim()) || 0;

            //                 if (!subDescription || subPercentage <= 0 || subPercentage > 100) {
            //                     Swal.fire('Validation Error',
            //                         'Invalid subcomponent data detected.', 'error');
            //                     subRow.find('td').eq(2).addClass('error-border');
            //                     isValid = false;
            //                 }

            //                 componentData.subComponents.push({
            //                     subDescription,
            //                     subPercentage,
            //                     subColumns
            //                 });
            //             });

            //         formData.components.push(componentData);
            //     });

            //     if (totalPercentage !== 100) {
            //         Swal.fire('Validation Error',
            //             `The total percentage must be 100%. Current: ${totalPercentage}%`, 'error');
            //         isValid = false;
            //     }

            //     if (!isValid) return;

            //     $.ajax({
            //         url: '/ECR-grading/adding',
            //         method: 'POST',
            //         contentType: 'application/json',
            //         data: JSON.stringify(formData),
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         success: function(response) {
            //             if (response.success) {
            //                 Swal.fire('Success', response.message, 'success').then(function() {
            //                     $('#ecrGradingForm')[0].reset();
            //                     $('#ecrComponentsBody').empty();
            //                     getECR
            //                         (); // Call your function to reload or refresh content
            //                     $('#addECRModal').modal(
            //                         'hide'); // Close modal upon successful save
            //                 });
            //             } else {
            //                 Swal.fire('Error', 'Failed to save ECR Grading.', 'error');
            //             }
            //         },
            //         error: function() {
            //             Swal.fire('Error', 'There was an error saving the data.', 'error');
            //         }
            //     });
            // });

            $('#addECRGrading').on('click', function() {
                console.log('123123');

                $('#addECRModal').modal('show');
                let allValues = [];
                $('#termApplied option').each(function() {
                    allValues.push($(this).val());
                });
                $('#termApplied').val(allValues).trigger('change');
                $('#setActive').prop('checked', true)
                $('#ecrComponentsBody').html('');
                $('#saveECR').removeClass('d-none');
                $('#updateECR').hide();
            });


            $(document).on('click', '#closeModalBtn', function() {
                // Remove 'show' class from the modal

                var hasLoadedSubjects = $('#subjectsTableBody tr').length > 0;

                if (hasLoadedSubjects) {
                    // Show confirmation dialog if there are unsaved subjects
                    Swal.fire({
                        icon: 'warning',
                        title: 'Unsaved Changes Detected!',
                        text: 'You have unsaved subjects loaded. Do you really want to close the modal?',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Close it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.value) {
                            $('#studentLoadingModal').removeClass('show');
                            $('#studentLoadingModal').css('display', 'none');
                            $('.modal-backdrop').remove();
                        }
                    });
                } else {
                    $('#studentLoadingModal').removeClass('show');
                    $('#studentLoadingModal').css('display', 'none');
                    $('.modal-backdrop').remove();

                }
            });

            function resetGradingForm() {
                $('#gradingDescription').val('');
                $('#componentPercentage').val('');
                $('#columnsInECR').prop('selectedIndex', 0);
                // $('#subComponentContainer').empty();
                $('#withSubComponents').prop('checked', false);
                // $('#subComponentContainer').hide();
                $('#subComponentDescription').val('');
                $('#subComponentPercentage').val('');
                $('#subColumnsInECR').prop('selectedIndex', 0);
            }

            $(document).on('click', '.deleteECRGrading', function() {
                const ecrID = $(this).data('id'); // Directly using the button's data-id attribute

                // SweetAlert confirmation prompt
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action will delete the ECR permanently!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            url: `/display/ECR/delete/` + ecrID,
                            method: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    $(`#ecrGradingBody tr[data-id="${ecrID}"]`)
                                        .remove();

                                    // SweetAlert success message
                                    Swal.fire(
                                        'Deleted!',
                                        'The ECR has been deleted successfully.',
                                        'success'
                                    );
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        'Failed to delete ECR.',
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log('Error deleting ECR:', error);

                                // SweetAlert error message
                                Swal.fire(
                                    'Error!',
                                    'There was an issue deleting the ECR.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });


            function deleteECRTable(data) {
                if (data.success) {
                    $(`#ecrGradingBody tr[data-id="${data.id}"]`).remove();
                    alert('ECR deleted successfully');
                } else {
                    alert('Failed to delete ECR');
                }
            }

            var ecrID;

            $(document).on('click', '.editECRGrading', function() {
                ecrID = $(this).data('id');
                $('#componentRow').val('');

                $('#updateECR').show();
                // $('#saveECR').hide();
                $('#addECRModal').on('hidden.bs.modal', function() {
                    $('#ecrDescription').val('');
                    $('#termApplied').val(null).trigger('change');
                    $('#setActive').prop('checked', false);
                });
                displayECR(ecrID);
            });

            $('#addGradingButton').on('click', function() {
                $('#gradingSetupList').empty();
                $('#gradingDescription').val('');
                $('#updateGradingComponent').removeAttr('data-id');
                $('#saveGrading').show();
                $('#gradingSetupModal').modal('show');
                $('#updateGrading').hide();

            });

            function displayECR(ecrID) {
                $.ajax({
                    url: `/display/ECR/edit/` + ecrID,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        showEditModal(response, ecrID);
                        console.log(response, 'Debugging Data');

                    },
                    error: function(xhr, status, error) {
                        console.log('Error fetching ECR for editing:', error);
                    }
                });
            }

            function showEditModal(data, ecrID) {
                $('#ecrDescription').val(data.ecr.ecrDesc);
                $('#setActive').prop('checked', data.ecr.is_active === 1);

                const termIDs = data.term.map(term => term.id);
                $('#termApplied').val(termIDs).trigger('change');

                console.log(data, 'Debugging Data');

                $('#ecrComponentsBody').html('');

                let componentRows = data.components.map(component => {
                    let componentRow = `
                    <tr class="componentRow" data-id="${component.id}">
                        <td>${component.descriptionComp}</td>
                        <td>${component.component} %</td>
                        <td></td>
                        <td></td>
                        <td>${component.column_ECR}</td>
                        <td>
                            <i role="button" class="fas fa-edit text-info editComponent" data-id="${component.id}"></i>
                            <i role="button" class="fas fa-trash-alt text-danger removeComponent" data-id="${component.id}"></i>
                        </td>
                    </tr>`;

                    let subcomponentRows = component.subComponents.map(sub => {
                        return `
                        <tr class="subComponentRow" data-id="${sub.id}" data-parent-id="${component.id}">
                            <td colspan="2"></td>
                            <td>${sub.subDescComponent}</td>
                            <td>${sub.subComponent} %</td>
                            <td>${sub.subColumnECR}</td>
                        </tr>`;
                    }).join('');

                    return componentRow + subcomponentRows;
                }).join('');

                $('#ecrComponentsBody').html(componentRows);
                $('#addECRModal').modal('show');
                $('#saveECR').addClass('d-none');
                $('#updateECR').removeClass('d-none').data('ecrID', ecrID);

                $('#termApplied').select2({
                    placeholder: 'Select Term(s)',
                    allowClear: true,
                });
                $('.ec-record').attr('data-ecrid', ecrID);
            }

            $('#withSubComponentsupdate').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#subComponentContainer').show();
                    if ($('#addSubComponentupdate').length === 0) {
                        $('#subComponentContainer').append(`
                    <button type="button" id="addSubComponentupdate" class="btn btn-link ml-3" style="font-size: 10px">+
                        Add Sub-Component
                    </button>
                `);
                    }
                } else {
                    $('#subComponentContainer').hide().empty();
                }
            });

            // Add new sub-component input fields when clicking add button
            $(document).on('click', '#addSubComponentupdate', function() {
                $('#subComponentContainer').append(`
            <div class="row mb-2 subComponentRowInput">
                <div class="col-4">
                    <input type="text" class="form-control form-control-sm subComponentDescription" placeholder="Sub Component Name">
                </div>
                <div class="col-4">
                    <input type="number" class="form-control form-control-sm subComponentPercentage" placeholder="Percentage">
                </div>
                <div class="col-3">
                    <select class="form-select form-select-sm editsubcolumnsInECR select2 w-100">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                    </select>
                </div>
                <div class="col-1">
                    <i class="fas fa-trash-alt text-danger btn btn-sm deleteSubComponentRow"></i>
            </div>
        `);
                $('.editsubcolumnsInECR').select2(); // Reinitialize Select2 for new elements
            });
            $(document).on('click', '.editComponent', function() {
                const componentID = $(this).data('id');

                $.ajax({
                    url: `/display/ECR/update/Component/${componentID}`,
                    method: 'GET',
                    success: function(response) {
                        console.log("Component Data Received:", response);

                        const {
                            component,
                            subcomponents
                        } = response;
                        console.log(component.descriptionComp);

                        $('#editgradingDescription').val(component.descriptionComp);
                        $('#editcomponentPercentage').val(component.component || 0);
                        $('#editcolumnsInECR').val(component.column_ECR || 1).select2();

                        $('#subComponentContainer').empty(); // Clear previous entries

                        if (subcomponents.length > 0) {
                            $('#withSubComponentsupdate').prop('checked', true).trigger(
                                'change');

                            subcomponents.forEach(sub => {
                                console.log(sub.subComponent, 'Debugging Data');

                                $('#subComponentContainer').append(`
                        <div class="row mb-2 subComponentRowInput" data-sub-id="${sub.id}">
                            <div class="col-4">
                                <input type="text" class="form-control form-control-sm subComponentDescription" 
                                    value="${sub.subDescComponent}" placeholder="Sub Component Name">
                            </div>
                            <div class="col-4">
                                <input type="number" class="form-control form-control-sm subComponentPercentage" 
                                    value="${sub.subComponent}" placeholder="Percentage">
                            </div>
                            <div class="col-4">
                                <select class="form-select form-select-sm editsubcolumnsInECR select2 w-100">
                                    <option value="1" ${sub.subColumnECR == 1 ? 'selected' : ''}>1</option>
                                    <option value="2" ${sub.subColumnECR == 2 ? 'selected' : ''}>2</option>
                                    <option value="3" ${sub.subColumnECR == 3 ? 'selected' : ''}>3</option>
                                    <option value="4" ${sub.subColumnECR == 4 ? 'selected' : ''}>4</option>
                                    <option value="5" ${sub.subColumnECR == 5 ? 'selected' : ''}>5</option>
                                    <option value="10" ${sub.subColumnECR == 10 ? 'selected' : ''}>10</option>
                                    <option value="15" ${sub.subColumnECR == 15 ? 'selected' : ''}>15</option>
                                </select>
                            </div>
                        </div>
                    `);
                            });

                            $('.editsubcolumnsInECR').select2(); // Reinitialize Select2
                            $('#subComponentContainer')
                                .show(); // Ensure the container is displayed
                        } else {
                            $('#withSubComponentsupdate').prop('checked', false);
                            $('#subComponentContainer').hide();
                        }

                        $('#updateGradingComponent').show().data('componentID', componentID);
                        $('#gradingComponentsModalEdit').modal('show');
                    },
                    error: function(error) {
                        console.error("Fetch Error:", error);
                        Swal.fire('Error', 'Failed to fetch component details', 'error');
                    }
                });
            });

            //<------------ECR UPDATE----------------->

            $('#updateECR').on('click', function() {
                const ecrID = $(this).data('ecrID');
                let isValid = true;
                let totalComponentPercentage = 0;

                const formData = {
                    ecrDesc: $('#ecrDescription').val(),
                    is_active: $('#setActive').is(':checked') ? 1 : 0,
                    terms: $('#termApplied').val(),
                    components: []
                };

                // Ensure ECR description is provided
                if (!formData.ecrDesc) {
                    Swal.fire('Error', 'ECR description is required.', 'error');
                    return;
                }

                // Ensure at least one term is selected
                if (!formData.terms || formData.terms.length === 0) {
                    Swal.fire('Error', 'Please select at least one term.', 'error');
                    return;
                }

                $('#ecrComponentsBody tr.componentRow').each(function() {
                    const componentID = $(this).data('id');
                    const componentPercentage = parseFloat($(this).find('td:eq(1)').text()) || 0;
                    totalComponentPercentage += componentPercentage;

                    if (componentPercentage <= 0) {
                        isValid = false;
                    }

                    const component = {
                        id: componentID || null,
                        descriptionComp: $(this).find('td:eq(0)').text(),
                        component: componentPercentage,
                        column_ECR: parseInt($(this).find('td:eq(4)').text()) || null,
                        subComponents: []
                    };

                    $(`#ecrComponentsBody tr.AddsubComponentRow[data-parent-id="${componentID}"]`)
                        .each(function() {
                            const subComponentID = $(this).data('id');
                            const subComponentPercentage = parseFloat($(this).find('td:eq(2)')
                                .text()) || 0;

                            if (subComponentPercentage <= 0) {
                                isValid = false;
                            }

                            component.subComponents.push({
                                id: subComponentID || null,
                                subDescComponent: $(this).find('td:eq(1)').text(),
                                subComponent: subComponentPercentage,
                                subColumnECR: parseInt($(this).find('td:eq(3)')
                                    .text()) || null,
                            });
                        });

                    formData.components.push(component);
                });

                if (totalComponentPercentage !== 100) {
                    Swal.fire('Error', 'The total component percentage must be exactly 100.', 'error');
                    return;
                }

                if (!isValid) {
                    Swal.fire('Error', 'Please ensure all components have valid values.', 'error');
                    return;
                }

                $.ajax({
                    url: `/display/ECR/update/${ecrID}`,
                    method: 'PUT',
                    data: JSON.stringify(formData),
                    contentType: "application/json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire('Success', response.message, 'success');
                        $('#addECRModal').modal('hide');
                        getECR();
                    },
                    error: function(error) {
                        Swal.fire('Error', 'Failed to update ECR', 'error');
                    }
                });
            });



            //<------------Subgrading UPDATE----------------->

            $('#updateGradingComponent').on('click', function() {
                const componentID = $(this).data('componentID');

                let formData = {
                    id: componentID, // Pass the component ID correctly
                    components: [{
                        id: componentID || null, // Ensure null if not set
                        descriptionComp: $('#editgradingDescription').val(),
                        component: parseFloat($('#editcomponentPercentage').val()) || 0,
                        column_ECR: parseInt($('#editcolumnsInECR').val()) || 1,
                        subComponents: []
                    }]
                };

                if (!formData.components[0].descriptionComp || formData.components[0].component <= 0) {
                    Swal.fire('Error', 'Component description and percentage are required.', 'error');
                    return;
                }

                let totalSubCompPercentage = 0;

                $('.subComponentRowInput').each(function() {
                    let subCompID = $(this).data('sub-id');
                    let subCompDesc = $(this).find('.subComponentDescription').val();
                    let subCompPercent = parseFloat($(this).find('.subComponentPercentage')
                        .val()) || 0;
                    let subCompColumn = parseInt($(this).find('.editsubcolumnsInECR').val()) || 1;

                    if (subCompDesc && subCompPercent > 0) {
                        formData.components[0].subComponents.push({
                            id: subCompID || null,
                            SubDescComponent: subCompDesc,
                            subComponent: subCompPercent,
                            subColumnECR: subCompColumn
                        });

                        totalSubCompPercentage += subCompPercent;
                    }
                });

                if ($('#withSubComponentsupdate').prop('checked')) {
                    if (totalSubCompPercentage !== 100) {
                        Swal.fire('Error', 'Total sub-component percentage must be exactly 100.', 'error');
                        return;
                    }
                }

                $.ajax({
                    url: `/display/ECR/Component/update/${componentID}`,
                    method: 'PUT',
                    data: JSON.stringify(formData),
                    contentType: "application/json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire('Success', 'Component Updated Successfully', 'success');
                        $('#gradingComponentsModalEdit').modal('hide');
                        displayECR(ecrID);
                        getECR();
                    },
                    error: function(error) {
                        Swal.fire('Error', 'Failed to update component', 'error');
                        console.error(error);
                    }
                });
            });



            //<------------Subgrading Delete----------------->

            $(document).on('click', '.removeComponent', function() {
                const componentID = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to delete this component?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: `/display/ECR/Component/delete/${componentID}`,
                            type: 'DELETE',
                            data: {
                                id: componentID
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    $(`tr[data-parent-id="${componentID}"]`).remove();
                                    $(`tr.componentRow[data-id="${componentID}"]`)
                                        .remove();
                                    $(`tr.subComponentRow[data-id="${componentID}"]`)
                                        .remove();
                                    Swal.fire('Deleted!', response.message, 'success');
                                    getECR(); // Update the ECR table
                                } else {
                                    Swal.fire('Error', response.message ||
                                        'Failed to delete component', 'error');
                                }
                            },
                            error: function(error) {
                                Swal.fire('Error', 'Failed to delete component',
                                    'error');
                            }
                        });
                    }
                });
            });

            //<------------Subgrading Remove----------------->

            // Event delegation for dynamically generated elements
            // $(document).on('click', '#removeSubComponent', function() {
            //     const subComponentID = $(this).data('id');

            //     Swal.fire({
            //         title: 'Are you sure?',
            //         text: "You want to delete this subcomponent?",
            //         icon: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Yes, delete it!',
            //         preConfirm: () => {
            //             return new Promise((resolve) => {
            //                 $.ajax({
            //                     type: 'DELETE',
            //                     url: `/display/ECR/Component/remove/${subComponentID}`,
            //                     data: {
            //                         id: subComponentID,
            //                         _token: $('meta[name="csrf-token"]').attr(
            //                             'content')
            //                     },
            //                     success: function(response) {
            //                         if (response.success) {
            //                             $(`.subComponentRow[data-id="${subComponentID}"]`)
            //                                 .remove();
            //                             $(`.AddsubComponentRow[data-id="${subComponentID}"]`)
            //                                 .remove();
            //                             resolve(response.message);
            //                         } else {
            //                             Swal.fire('Error', response
            //                                 .message ||
            //                                 'Failed to delete subcomponent',
            //                                 'error');
            //                         }
            //                     },
            //                     error: function() {
            //                         Swal.fire('Error',
            //                             'Failed to delete subcomponent',
            //                             'error');
            //                     }
            //                 });
            //             });
            //         }
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             Swal.fire('Deleted!', result.value, 'success');
            //         }
            //     });
            // });

            //<------------Subgrading/Component Delete----------------->
            // $(document).on('click', '.deleteGrading', function() {
            //     const row = $(this).closest('tr');
            //     const componentID = row.data('id');

            //     // Remove only the subgrading rows associated with the component
            //     $(`tr.subComponentRow[data-id="${componentID}"]`).remove();
            // });

            // <------------Subgrading/Component Edit----------------->
            // $('#gradingComponentsModal').on('hidden.bs.modal', function() {
            //     $('#gradingDescription').val('');
            //     $('#componentPercentage').val('');
            //     $('#columnsInECR').val('');
            //     $('#withSubComponents').prop('checked', false);
            //     $('#subComponentContainer').hide(); // Clear subcomponents only
            //     $('#addSubComponentContainer')
            //         .show(); // Ensure "Add Sub Component" button remains
            //     $('#addGradingComponent').show();
            //     $('#updateGradingComponent').hide();
            //     $('#subColumnECR').val('');
            //     $('#subPercentage').val('');
            //     $('#subDescription').val('');
            // });

            // $(document).on('click', '.editGrading', function(event) {
            //     const $row = $(this).closest('tr');
            //     const componentID = $row.attr('id');
            //     const description = $row.find('td').eq(0).text().trim();
            //     const percentage = $row.find('td').eq(1).text().trim().replace('%', '');
            //     const columns = $row.find('td').eq(4).text().trim();

            //     $('#gradingDescription').val(description);
            //     $('#componentPercentage').val(percentage);
            //     $('#columnsInECR').val(columns);

            //     const $subComponentRows = $(`tr[data-parent-id="${componentID}"]`);
            //     const subComponentContainer = $('#subComponentContainer');
            //     // subComponentContainer.empty(); // 🔹 Clear old subcomponents to prevent duplicates

            //     $subComponentRows.each(function() {
            //         const subDescription = $(this).find('td').eq(1).text().trim();
            //         const subPercentage = $(this).find('td').eq(2).text().trim().replace('%', '');
            //         const subColumns = $(this).find('td').eq(3).text().trim();

            //         const subComponentInput = `
        //             <div class="subComponentRowInput mb-2 row">
        //                 <div class="col-5">
        //                     <input type="text" class="form-control form-control-sm subComponentDescription" value="${subDescription}" placeholder="Subcomponent Description">
        //                 </div>
        //                 <div class="col-3">
        //                     <input type="number" class="form-control form-control-sm subComponentPercentage" value="${subPercentage}" placeholder="Percentage">
        //                 </div>
        //                 <div class="col-3">
        //                     <select class="form-select form-select-sm subcolumnsInECR select2">
        //                         <option value="1">1</option>
        //                         <option value="2">2</option>
        //                         <option value="3">3</option>
        //                         <option value="4">4</option>
        //                         <option value="5">5</option>
        //                         <option value="10">10</option>
        //                         <option value="15">15</option>
        //                     </select>
        //                 </div>
        //                 <div class="col-1 text-center">
        //                     <button type="button" class="btn btn-danger btn-sm deleteSubComponentRow">
        //                         <i class="fas fa-trash"></i>
        //                     </button>
        //                 </div>
        //             </div>`;

            //         // Append to the container
            //         const $subComponentElement = $(subComponentInput);
            //         $('#subComponentContainer').append($subComponentElement);

            //         // Set the selected value and initialize select2
            //         $subComponentElement.find('.subcolumnsInECR').val(subColumns).trigger('change')
            //             .select2();
            //     });


            //     $('#withSubComponents').prop('checked', $subComponentRows.length > 0).trigger('change');
            //     $('#subComponentContainer').toggle($subComponentRows.length > 0);

            //     // // Prevent multiple event bindings
            //     // $('#withSubComponents').off('change').on('change', function() {
            //     //     $('#subComponentContainer').toggle(this.checked);
            //     // });

            //     $('#gradingComponentsModal').modal('show');

            //     // Remove previous click event before binding a new one
            //     $('#addGradingComponent').off('click').on('click', function(event) {
            //         event.preventDefault();

            //         const gradingDescription = $('#gradingDescription').val();
            //         const componentPercentage = parseFloat($('#componentPercentage').val());
            //         const columnsInECR = $('#columnsInECR').val();

            //         if (!gradingDescription || componentPercentage <= 0 || !columnsInECR) {
            //             Swal.fire('Validation Error', 'Please fill in all required fields.',
            //                 'error');
            //             return;
            //         }

            //         $row.find('td').eq(0).text(gradingDescription);
            //         $row.find('td').eq(1).text(`${componentPercentage}%`);
            //         $row.find('td').eq(4).text(columnsInECR);

            //         // Remove previous subcomponent rows
            //         $(`tr[data-parent-id="${componentID}"]`).remove();

            //         if ($('#withSubComponents').is(':checked')) {
            //             let totalSubPercentage = 0;

            //             $('#subComponentContainer .subComponentRowInput').each(function(index) {
            //                 const subDescription = $(this).find('.subComponentDescription')
            //                     .val();
            //                 const subPercentage = parseFloat($(this).find(
            //                     '.subComponentPercentage').val());
            //                 const subColumns = $(this).find('.subcolumnsInECR').val();

            //                 if (!subDescription || subPercentage <= 0 || !subColumns) {
            //                     Swal.fire('Validation Error',
            //                         'Fill in all subcomponent fields.', 'error');
            //                     return false;
            //                 }

            //                 totalSubPercentage += subPercentage;

            //                 const subComponentID = `subComponent-${Date.now()}-${index}`;
            //                 const subRow = `
        //         <tr class="subComponentRow" id="${subComponentID}" data-parent-id="${componentID}">
        //             <td colspan="2"></td>
        //             <td>${subDescription}</td>
        //             <td>${subPercentage}%</td>
        //             <td>${subColumns}</td>
        //             <td></td>
        //         </tr>`;
            //                 $row.after(subRow);
            //             });

            //             if (totalSubPercentage !== 100) {
            //                 Swal.fire('Validation Error',
            //                     `Subcomponents must total 100%. Current: ${totalSubPercentage}%`,
            //                     'error');
            //                 return;
            //             }
            //         }

            //         resetGradingForm();
            //         $('#gradingComponentsModal').modal('hide');
            //     });
            // });



            $(document).on('click', '.deleteSubComponentRow', function() {
                $(this).closest('.subComponentRowInput').remove();
            });




            // $(document).on('click', '.deleteGrading', function() {
            //     const $row = $(this).closest('tr');
            //     const componentID = $row.attr('id');

            //     $(`tr[data-parent-id="${componentID}"]`).remove();

            //     $row.remove();
            // });

            // $('#filter_semester').on('change', function() {
            //     let activeSemester = $('#filter_semester').val();

            //     $.ajax({
            //         url: '/filter_semester/Ecr',
            //         method: 'GET',
            //         data: {
            //             semester: activeSemester
            //         },
            //         success: function(response) {
            //             console.log('Filtered Data:', response);
            //             populateECRTable(response);
            //         },
            //         error: function(xhr) {
            //             console.error('An error occurred:', xhr.responseText);
            //         }
            //     });
            // });

            // $('#filter_sy, #filter_semester').on('change', function() {
            //     // let activeSy = $('#filter_sy').val();
            //     // let activeSemester = $('#filter_semester').val();

            //     // $.ajax({
            //     //     url: '/filter_sy/Ecr',
            //     //     method: 'GET',
            //     //     data: {
            //     //         sy: activeSy,
            //     //         semester: activeSemester
            //     //     },
            //     //     success: function(response) {
            //     //         console.log(response, 'Filtered ECR Data');
            //     //         populateECRTable(response); // Function to populate your table
            //     //     },
            //     //     error: function(xhr) {
            //     //         console.error('An error occurred:', xhr.responseText);
            //     //     }
            //     // });
            //     getECR();
            // });

            // Function to search and filter the table rows
            $('#searchInput').on('keyup', function() {
                let searchTerm = $(this).val().toLowerCase();
                let tbody = $('#ecrGradingBody');

                tbody.find('tr').each(function() {
                    let row = $(this);
                    let rowText = row.text().toLowerCase();

                    if (rowText.indexOf(searchTerm) !== -1) {
                        row.show();
                    } else {
                        row.hide();
                    }
                });
            });

            // Optional: Trigger search with button click as well
            $('#searchButton').on('click', function() {
                $('#searchInput').trigger('keyup');
            });


            $('#ecrGradingBody').on('click', '#printECR', function(e) {
                e.preventDefault();
                const ecrID = $(this).data('id');
                fetchECR(ecrID);
            });

            $(document).on('click', '.ec-record', function(e) {
                e.preventDefault();
                const ecrID = $(this).data('ecrid');
                fetchECR(ecrID);
            });


            // Common function for fetching and displaying the ECR
            function fetchECR(ecrID) {
                $.ajax({
                    url: `/display/ECR/${ecrID}`,
                    method: 'GET',
                    success: function(response) {
                        $('#previewContent').html(response);
                        $('#previewModal').modal('show');
                    },
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.responseText);
                    }
                });
            }
        });

        // $('#ecrGradingBody').on('click', '#printECR', function(e) {
        //     e.preventDefault();
        //     const ecrID = $(this).data('id');
        //     if (ecrID) {
        //         $.ajax({
        //             url: `/print/ECRprint/${ecrID}`,
        //             method: 'GET',
        //             success: function(response) {
        //                 // Assuming the response returns the HTML for the print view
        //                 // You can either open it in a new window or display it in a modal
        //                 const printWindow = window.open('/print/ECRprint/${ecrID}', '_blank');
        //                 printWindow.document.write(response);
        //                 printWindow.document.close();
        //                 printWindow.print();
        //             },
        //             error: function(xhr) {
        //                 alert("An error occurred: " + xhr.responseText);
        //             }
        //         });
        //     } else {
        //         alert("ECR ID is missing.");
        //     }
        // });
    </script>
@endsection
