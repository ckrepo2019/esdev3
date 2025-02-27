@extends('guidanceV2.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    </script>
    <style>
        .btn_custom_group {
            padding: 3px 8px !important;
        }

        .bootstrap-switch .bootstrap-switch-handle-off,
        .bootstrap-switch .bootstrap-switch-handle-on,
        .bootstrap-switch .bootstrap-switch-label {
            padding: .1rem .1rem;
        }

        .fw_semi_bold,
        .btn_new_examdate {
            font-weight: 600;
        }

        .txt-center {
            text-align: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: unset !important;
        }
    </style>
@endsection

@section('content')
    @include('guidanceV2.components.admissionModals')

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="criteriaModal" tabindex="-1" role="dialog" aria-labelledby="criteriaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="criteriaModalLabel">Add Criteria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="criteriaForm">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="criteriaName">Criteria Name</label>
                                <input type="text" class="form-control" id="criteriaName" name="name"
                                    placeholder="Enter criteria name" required>
                                <span class="invalid-feedback" role="alert">
                                    <strong>Criteria Name is required!</strong>
                                </span>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="criteriaPercentage">Percentage (%)</label>
                                <input type="number" class="form-control" id="criteriaPercentage" name="percentage"
                                    placeholder="Enter percentage" min="0" max="100" required>
                                <span class="invalid-feedback" role="alert">
                                    <strong>Criteria Percentage is required!</strong>
                                </span>
                            </div>
                            <div class="col-md-12">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="primary">
                                    <label for="primary">Primary</label>
                                </div>
                                <div class="icheck-info">
                                    <input type="checkbox" id="subcriteria">
                                    <label for="subcriteria">With Subcriteria</label>
                                </div>
                            </div>

                            <strong class="text-danger "><em>Note: Click on "Primary" if it's for the School Entrance
                                    Exam
                                    Result total Ratio.</em></strong>

                            <div class="col-md-12 mt-1">
                                <table style="width: 100%;" class="table table-sm table-bordered table-valign-middel"
                                    id="subcriteriaTableContainer" hidden>
                                    <thead>
                                        <th>#</th>
                                        <th>Subcriteria</th>
                                        <th>(%)</th>
                                        <th></th>
                                    </thead>
                                    <tbody id="subcriteriaTable">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4">
                                                <button type="button" class="btn btn-sm btn-primary new_subcriteria"
                                                    style="width: 100%;">
                                                    + New Subcriteria
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <input type="hidden" id="criteriaId" name="id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="saveCriteria">Save</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="subCriteriaModal" tabindex="-1" role="dialog" aria-labelledby="subCriteriaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subCriteriaModalLabel">Add Subcriteria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="subcriteriaForm">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label for="subcriteriaName">Subcriteria Name</label>
                                <input type="text" class="form-control" id="subCriteriaName" name="subCriteriaName"
                                    placeholder="Enter Subcriteria name" required>
                                <div class="invalid-feedback">
                                    Subcriteria Name is required!
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="subcriteriaPercentage">Percentage (%)</label>
                                <input type="number" class="form-control" id="subCriteriaPercentage"
                                    name="subCriteriaPercentage" placeholder="e.g.75" min="0" max="100"
                                    required>
                                <div class="invalid-feedback">
                                    Percent is required!
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="addSubcriteria">Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL VIEW ENTRANCE EXAM SETUP OR PASSING RATE SETUP -->
    <div class="modal fade" id="viewEntranceSetupModal" tabindex="-1" role="dialog"
        aria-labelledby="viewEntranceSetupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewEntranceSetupModalLabel">View Entrance Exam Setup</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1">Exam Name</label>
                                <input type="text" class="form-control" id="viewEntranceName" placeholder=""
                                    readonly>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1">Exam Date</label>
                                <input type="datetime-local" class="form-control" id="viewEntranceDate" placeholder=""
                                    readonly>
                            </div>
                        </div> --}}
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1">Academic Program</label>
                                <input type="text" class="form-control" id="viewEntranceAcademicProgram"
                                    placeholder="" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="mb-1">Average</label>
                            <div class="form-group input-group">
                                <input type="number" class="form-control" id="viewEntranceAverage" placeholder=""
                                    readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1">Date Created</label>
                                <input type="datetime-local" class="form-control" id="viewEntranceDateCreated"
                                    placeholder="" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1">Status</label>
                                <input type="text" class="form-control" id="viewEntranceStatus" placeholder=""
                                    readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 mt-4">
                <div class="col-sm-6">
                    <h1 class="m-0">Admission Management</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item "> <a href="#">Home</a> </li>
                        <li class="breadcrumb-item active">{{ $page_desc }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline card-outline-tabs shadow">

                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ $current_page == 1 ? 'active' : '' }} fw_semi_bold"
                                id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home"
                                role="tab" aria-controls="custom-content-below-home"
                                aria-selected="{{ $current_page == 1 ? 'true' : 'false' }}">Examination Setup</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ $current_page == 3 ? 'active' : '' }} fw_semi_bold"
                                id="custom-content-below-messages-tab" data-toggle="pill"
                                href="#custom-content-below-messages" role="tab"
                                aria-controls="custom-content-below-messages"
                                aria-selected="{{ $current_page == 3 ? 'true' : 'false' }}">Exam Dates Setup
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link fw_semi_bold" id="custom-content-below-criteria-tab" data-toggle="pill"
                                href="#custom-content-below-criteria" role="tab"
                                aria-controls="custom-content-below-criteria" aria-selected="false">Criteria Setup
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="custom-content-below-tabContent">
                        <div class="tab-pane fade {{ $current_page == 1 ? 'show active' : '' }}"
                            id="custom-content-below-home" role="tabpanel"
                            aria-labelledby="custom-content-below-home-tab">
                            <a type="button" href="/guidance/addpassingrate"
                                class="btn btn-sm btn-primary shadow fw_semi_bold">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                    <path
                                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4">
                                    </path>
                                </svg>
                                CREATE EXAM
                            </a>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-valign-middle" id="tbl_category"
                                    style="width: 100%;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th width="3%">#</th>
                                            <th width="25%">Examination Description</th>
                                            <th width="15%">Academic Program</th>
                                            <th width="10%">Average</th>
                                            <th width="15%">Date Created</th>
                                            <th width="10%">Status</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade {{ $current_page == 3 ? 'show active' : '' }}"
                            id="custom-content-below-messages" role="tabpanel"
                            aria-labelledby="custom-content-below-messages-tab">
                            <button type="button" class="btn btn-sm btn-primary btn_new_examdate shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                    <path
                                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4">
                                    </path>
                                </svg>
                                CREATE SCHEDULE
                            </button>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-valign-middle"
                                    id="tbl_entranceexamdates" style="width: 100%">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th width="10%"></th>
                                            <th width="20%">Setup</th>
                                            <th width="20%">Dates</th>
                                            <th width="20%">Campus</th>
                                            <th width="10%">Slot</th>
                                            <th width="10%">Status</th>
                                            <th width="10%"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="custom-content-below-criteria" role="tabpanel"
                            aria-labelledby="custom-content-below-criteria-tab">
                            <button type="button" class="btn btn-sm btn-primary mb-3 shadow fw_semi_bold add_criteria"
                                data-toggle="modal" data-target="#criteriaModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                    <path
                                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4">
                                    </path>
                                </svg>
                                CREATE CRITERIA
                            </button>
                            <table class="table table-striped table-hover table-valign-middle" id="tbl_criteria"
                                style="width: 100%;">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="width: 30%;">Criteria Name</th>
                                        <th style="width: 20%;">Percentage</th>
                                        <th style="width: 20%;">Date Added</th>
                                        <th style="width: 20%;">Subcriteria</th>
                                        <th style="width: 10%;"></th>
                                    </tr>
                                </thead>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('footerjavascript')
    <!-- Bootstrap Switch -->
    <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script>
        var jsonCategories = {!! json_encode($jsonCategories) !!};
        var jsonDiagnosticTest = {!! json_encode($jsonDiagnosticTest) !!};
        var jsonExamDates = {!! json_encode($jsonExamDates) !!};
        var listCategory = [];
        var filteredCourses = []
        var listCoursePercentage = []
        var isCollege = false;
        var listOfSubCriteria = []

        $(document).ready(function() {
            console.log('hellfsldfj');
            console.log(jsonExamDates)
            load_category_datatable(jsonCategories);
            loadExamDatesDataTable(jsonExamDates);
            load_criteria()

            $('.view_category').on('click', function() {
                var id = $(this).data('id')
                var desc = $(this).data('desc')
                var row = $(this).data('json')
                $('#viewEntranceSetupModalLabel').text(row.description)
                console.log(row)
                $('#viewEntranceName').val(row.description)
                // $('#viewEntranceDate').val(moment(row.exam_date).format('YYYY-MM-DDTHH:mm'))
                $('#viewEntranceAcademicProgram').val(row.progname)
                $('#viewEntranceAverage').val(row.average)
                $('#viewEntranceDateCreated').val(moment(row.created_at).format('YYYY-MM-DDTHH:mm'))
                $('#viewEntranceStatus').val(row.isactive ? 'Active' : 'Inactive')


                $('#viewEntranceSetupModal').modal()

            })

            $('.new_subcriteria').on('click', function() {
                $('#subCriteriaModal').modal();
            })

            $('#addSubcriteria').on('click', function() {
                var isvalid = true;
                var subCriteriaName = $('#subCriteriaName').val();
                var subCriteriaPercentage = $('#subCriteriaPercentage').val();

                $('#subCriteriaName').removeClass('is-invalid')
                $('#subCriteriaPercentage').removeClass('is-invalid')

                if (!subCriteriaName) {
                    isvalid = false
                    $('#subCriteriaName').addClass('is-invalid')
                    notify('error', 'SubCriteria is required!')
                }

                if (!subCriteriaPercentage) {
                    isvalid = false
                    $('#subCriteriaPercentage').addClass('is-invalid')
                    notify('error', 'Percent % is required!')
                }

                if (isvalid) {
                    var CritObj = {
                        name: subCriteriaName,
                        percentage: subCriteriaPercentage
                    }

                    listOfSubCriteria.push(CritObj)
                    saveSubCriteria()
                }
            })

            $('#subcriteria').on('click', function() {
                $('#subCriteriaModal').modal();
            })

            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));

                $(this).on('switchChange.bootstrapSwitch', function(event, state) {
                    // Call handleCheckboxChange function when checkbox state changes
                    handleCheckboxChange($(this));
                });
            })
            $('#select-level').on('change', function() {
                generate_headers()
                var selectedValues = $(this).val();
                // Check if selectedValues is empty
                if (!selectedValues || selectedValues.length === 0) {
                    $('.college_modal').prop('hidden', true);
                    return; // Exit the function if no values are selected
                }
                // Check each selected value
                $.each(selectedValues, function(index, value) {
                    if (value >= 17 && value <= 21) {
                        isCollege = true;
                        $('.college_modal').prop('hidden', false);
                        console.log('Selected value ' + value + ' is between 18 and 21');
                        return;
                    } else {
                        isCollege = false
                        $('.college_modal').prop('hidden', true);
                        console.log('Selected value ' + value + ' is not in the range of 18-21');
                    }
                });

                if (isCollege) {
                    $('.college_modal').prop('hidden', false);
                } else {
                    $('.college_modal').prop('hidden', true);
                }

            });

            $('#select-exam').select2({
                // theme: 'bootstrap4',
                allowClear: true,
                placeholder: 'Select Exam'
            })

            $('#select-course').on('change', function() {
                var selectedOptions = $(this).find('option:selected');

                var courses = selectedOptions.map(function() {
                    return {
                        id: $(this).val(),
                        text: $(this).text()
                    };
                }).get();

                var nonEmptyCourses = courses.filter(course => course.value !== '');
                filteredCourses = nonEmptyCourses;
                console.log(filteredCourses);
                // console.log(courses);
                onChangeCourse(nonEmptyCourses);
            });

            $('#select-level').select2({
                allowClear: true,
                placeholder: "Select Level",
                // theme: 'bootstrap4',
                multiple: true
            });

            $('#select-course').select2({
                allowClear: true,
                placeholder: "Select Course",
                // theme: 'bootstrap4',
                multiple: true
            });

            $('#select-year').select2({
                allowClear: true,
                placeholder: "Select School Year",
                // theme: 'bootstrap4',
            });

            $('#edit-select-year').select2({
                allowClear: true,
                placeholder: "Select School Year",
                // theme: 'bootstrap4',
            });

            $('#acadprog').select2({
                allowClear: true,
                placeholder: "Select Academic Program",
                // theme: 'bootstrap4',
            });

            $('#edit-acadprog').select2({
                allowClear: true,
                placeholder: "Select Academic Program",
                // theme: 'bootstrap4',
            });


            $('.btn_new_examdate').on('click', function() {
                $('#modalAddExamDate').modal();
            })

            $(document).on('click', '.btn_edit_examdate', function() {
                var id = $(this).data('id')
                console.log(id);
                editExamDate(id)
            })

            $(document).on('click', '.btn_delete_examdate', function() {
                var id = $(this).data('id')
                console.log(id);
                Swal.fire({
                    type: 'info',
                    title: 'Delete this Schedule ?',
                    text: `You won't be able to revert this! `,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "GET",
                            url: '{{ route('delete.examdate') }}',
                            data: {
                                id: id
                            },
                            success: function(response) {
                                console.log(response)
                                notify(response.status, response.message);
                                if (response.status == "success") {
                                    load_examdates_datatable(response.examdates)
                                }
                            }
                        });
                    }
                });
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
                            $('#select-exam').empty()
                            $('#select-exam').select2({
                                data: data,
                                allowClear: true,
                                // theme: 'bootstrap4',
                                placeholder: 'Select Exam Setup'
                            })


                        }
                    })
                }
            });

            $('#edit-acadprog').on('change', function() {
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
                            $('#edit-select-exam').empty()
                            $('#edit-select-exam').select2({
                                data: data,
                                allowClear: true,
                                // theme: 'bootstrap4',
                                placeholder: 'Select Exam Setup'
                            })


                        }
                    })
                }
            });

            $('#btn_save_examdate').on('click', function() {
                var isvalidinputs = true;


                if (!$('#select-year').val()) {
                    notify('error', 'School Year is required!')
                    $('#select-year').addClass('is-invalid')
                    isvalidinputs = false
                    return
                } else {
                    $('#select-year').removeClass('is-invalid')
                }

                if (!$('#acadprog').val()) {
                    notify('error', 'Acad Prog is required!')
                    $('#acadprog').addClass('is-invalid')
                    isvalidinputs = false
                    return
                } else {
                    $('#acadprog').removeClass('is-invalid')
                }

                if (!$('#select-exam').val()) {
                    notify('error', 'Exam type is required!')
                    $('#select-exam').addClass('is-invalid')
                    isvalidinputs = false
                    return
                } else {
                    $('#select-exam').removeClass('is-invalid')
                }
                if (!$('#examinationdate').val()) {
                    notify('error', 'Date is required!')
                    $('#examinationdate').addClass('is-invalid')
                    isvalidinputs = false
                    return
                } else {
                    $('#examinationdate').removeClass('is-invalid')
                }

                if (!$('#venue').val()) {
                    notify('error', 'Venue is required!')
                    $('#venue').addClass('is-invalid')
                    isvalidinputs = false
                    return
                } else {
                    $('#venue').removeClass('is-invalid')
                }
                if (!$('#takers').val()) {
                    notify('error', 'Takers is required!')
                    $('#takers').addClass('is-invalid')
                    isvalidinputs = false
                    return
                } else {
                    $('#takers').removeClass('is-invalid')
                }
                if (!$('#building').val()) {
                    notify('error', 'Building is required!')
                    $('#building').addClass('is-invalid')
                    isvalidinputs = false
                    return
                } else {
                    $('#building').removeClass('is-invalid')
                }
                if (!$('#room').val()) {
                    notify('error', 'Room is required!')
                    $('#room').addClass('is-invalid')
                    isvalidinputs = false
                    return
                } else {
                    $('#room').removeClass('is-invalid')
                }

                if (isvalidinputs) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('store.examdate') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            schoolyear: $('#select-year').val(),
                            examinationdate: $('#examinationdate').val(),
                            takers: $('#takers').val(),
                            venue: $('#venue').val(),
                            acadprog: $('#acadprog').val(),
                            examid: $('#select-exam').val(),
                            building: $('#building').val(),
                            room: $('#room').val()
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status == 'success') {
                                notify(response.status, response.message)
                                load_examdates_datatable(response.examdates)
                                $('#modalAddExamDate').modal('hide')
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseJSON);
                        }
                    });
                }

            })

            $('#btn_update_examdate').on('click', function() {
                var isvalidinputs = true;

                if (!$('#edit-select-year').val()) {
                    notify('error', 'School Year is required!')
                    $('#edit-select-year').addClass('is-invalid')
                    isvalidinputs = false
                    return
                } else {
                    $('#edit-select-year').removeClass('is-invalid')
                }

                if (!$('#edit-acadprog').val()) {
                    notify('error', 'Acad Prog is required!')
                    $('#edit-acadprog').addClass('is-invalid')
                    isvalidinputs = false
                    return
                } else {
                    $('#edit-acadprog').removeClass('is-invalid')
                }

                if (!$('#edit_examid').val()) {
                    notify('error', 'Exam type is required!')
                    $('#edit_examid').addClass('is-invalid')
                    isvalidinputs = false
                    return
                } else {
                    $('#edit_exam').removeClass('is-invalid')
                }
                if (!$('#edit-examinationdate').val()) {
                    notify('error', 'Date is required!')
                    $('#edit-examinationdate').addClass('is-invalid')
                    isvalidinputs = false
                    return
                } else {
                    $('#edit-examinationdate').removeClass('is-invalid')
                }

                if (!$('#edit-venue').val()) {
                    notify('error', 'Venue is required!')
                    $('#edit-venue').addClass('is-invalid')
                    isvalidinputs = false
                    return
                } else {
                    $('#edit-venue').removeClass('is-invalid')
                }
                if (!$('#edit-takers').val()) {
                    notify('error', 'Takers is required!')
                    $('#edit-takers').addClass('is-invalid')
                    isvalidinputs = false
                    return
                } else {
                    $('#edit-takers').removeClass('is-invalid')
                }
                if (!$('#edit-building').val()) {
                    notify('error', 'Building is required!')
                    $('#edit-building').addClass('is-invalid')
                    isvalidinputs = false
                    return
                } else {
                    $('#edit-building').removeClass('is-invalid')
                }
                if (!$('#edit-room').val()) {
                    notify('error', 'Room is required!')
                    $('#edit-room').addClass('is-invalid')
                    isvalidinputs = false
                    return
                } else {
                    $('#edit-room').removeClass('is-invalid')
                }

                if (isvalidinputs) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('update.examdate') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id: $('#idToUpdateExamDate').val(),
                            schoolyear: $('#edit-select-year').val(),
                            examinationdate: $('#edit-examinationdate').val(),
                            takers: $('#edit-takers').val(),
                            venue: $('#edit-venue').val(),
                            acadprog: $('#edit-acadprog').val(),
                            examid: $('#edit_examid').val(),
                            building: $('#edit-building').val(),
                            room: $('#edit-room').val()
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status == 'success') {
                                notify(response.status, response.message)
                                load_examdates_datatable(response.examdates)
                                $('#modalEditExamDate').modal('hide')
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseJSON);
                        }
                    });
                }

            })


            $('#btn_add_category').on('click', function() {
                var obj = {};
                var category = $('#category_name').val();
                var timelimit_hrs = $('#timelimit_hrs').val();
                var timelimit_min = $('#timelimit_min').val();
                // var timeUnit = $('#time_unit').val();


                if (!category || !timelimit_min) {
                    $('#category_name').addClass('is-invalid');
                    // $('#timelimit_hrs').addClass('is-invalid');
                    $('#timelimit_min').addClass('is-invalid');
                    notify('error', 'Fill all fields!');
                } else {
                    var timeInMinutes = 0
                    if (timelimit_hrs) {
                        // Convert hours to minutes
                        timeInMinutes = (timelimit_hrs * 60) + parseInt(timelimit_min);
                        // $('#timelimit').val(timeInMinutes);
                    } else {
                        timeInMinutes = parseInt(timelimit_min)
                    }

                    obj.category = category;
                    obj.timelimit = timeInMinutes;

                    $('.list_category').empty();
                    let exists = listCategory.some(item => item.category === obj.category)
                    if (!exists) {
                        listCategory.push(obj);
                        var selectedOptions = $('#select-course').find('option:selected');
                        var courses = selectedOptions.map(function() {
                            return {
                                id: $(this).val(),
                                text: $(this).text()
                            };
                        }).get();
                        onChangeCourse(courses.filter(course => course.value !== ''))

                    } else {
                        console.log('EXIST')
                    }
                    $('#category_name').removeClass('is-invalid');
                    // $('#timelimit_hrs').removeClass('is-invalid');
                    $('#timelimit_min').removeClass('is-invalid');
                    $('#category_name').val("");
                    $('#timelimit_hrs').val("");
                    $('#timelimit_min').val("");
                    updateListCategory();
                }

            });

            $('.btn_delete_passing_rate').on('click', function() {
                var itemToDelete = $(this).data('id');
                console.log(itemToDelete);
                Swal.fire({
                    type: 'info',
                    title: 'Delete this Setup ?',
                    text: `You won't be able to revert this! `,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "GET",
                            url: '{{ route('delete.passingrate') }}',
                            data: {
                                id: itemToDelete
                            },
                            success: function(response) {
                                console.log(response)
                                notify(response.status, response.message);
                                getAllAdmissionSetup()
                            }
                        });
                    }
                });

            })

            // Function to update the UI with the listCategory items
            function updateListCategory() {
                listCategory.forEach(element => {
                    var htmlToAppend = `
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <input type="text" class="form-control" value="${element.category}" disabled>
                        </div>
                        <input type="number" class="form-control" value="${element.timelimit}" disabled>
                        <input type="text" class="form-control" value="Minutes" disabled>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-default btn_edit">
                                <i class="far fa-edit mr-1 text-info"></i>
                            </button>
                            <button type="button" class="btn btn-default btn_delete" data-id="${element.category}">
                                <i class="far fa-trash-alt mr-1 text-danger"></i>
                            </button>
                        </div>
                    </div>
                `;
                    $('.list_category').append(htmlToAppend);
                });

                // Attach click event handler for delete buttons
                $('.btn_delete').on('click', function() {
                    var idToDelete = $(this).attr('data-id');
                    listCategory = listCategory.filter(item => item.category !== idToDelete);
                    $('.list_category').empty();
                    updateListCategory();
                    var selectedOptions = $('#select-course').find('option:selected');
                    var courses = selectedOptions.map(function() {
                        return {
                            id: $(this).val(),
                            text: $(this).text()
                        };
                    }).get();
                    onChangeCourse(courses.filter(course => course.value !== ''))
                });
            }

            // Initial update of listCategory UI
            updateListCategory();

            $('#btn_save_course').on('click', function() {
                if (!$('#description').val()) {
                    $('#description').addClass('is-invalid');
                    return
                } else {
                    $('#description').removeClass('is-invalid');
                }
                if (!$('#select-level').val().length > 0) {
                    $('#select-level').addClass('is-invalid');
                    return
                } else {
                    $('#select-level').removeClass('is-invalid');
                }

                if (isCollege) {
                    var isvalid = true;
                    listCoursePercentage = [];
                    $('#tbl_listCoursePercentage').find('tr').each(function() {
                        var rowInputs = $(this).find('input');
                        var courseid = 0;
                        var general_percentage = 0;
                        var isprovision = false;
                        var ArrCategory = [];
                        rowInputs.each(function() {
                            var inputId = $(this).attr('id');
                            var inputValue = $(this).val();
                            var isChecked = $(this).prop('checked');
                            var inputClass = $(this).attr('class');

                            if (!inputValue) {
                                $(this).closest('input').addClass(
                                    'is-invalid'
                                );
                                notify('error', 'Inputs are required!')
                                isvalid = false;
                            } else {
                                $(this).closest('input').removeClass(
                                    'is-invalid'
                                ); // Find closest ancestor input and remove class
                            }


                            if (inputClass && inputClass.includes('courseid')) {
                                courseid = inputId;
                            }
                            if (inputClass && inputClass.includes('isprovision')) {
                                isprovision = isChecked
                            }
                            if (inputClass && inputClass.includes('input_gen')) {
                                general_percentage = inputValue;
                            }
                            if (inputClass && inputClass.includes('percent')) {
                                var obj = {
                                    percent: inputValue,
                                    categoryname: $(this).attr('hidden-info')
                                }
                                ArrCategory.push(obj);
                            }
                        });

                        if (isvalid) {
                            ArrCategory.forEach(each => {
                                var obj = {
                                    courseid: courseid,
                                    percentage: each.percent,
                                    categoryname: each.categoryname,
                                    isprovision: isprovision,
                                    general_percentage: general_percentage,
                                }
                                listCoursePercentage.push(obj)

                            });
                        }
                    });
                }

                $.ajax({
                    type: 'POST',
                    url: '{{ route('store.passingrate') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        jhs_percent: $('#jhs_percent').val(),
                        shs_percent: $('#shs_percent').val(),
                        description: $('#description').val(),
                        gradelevel: $('#select-level').val().join(','),
                        categories: JSON.stringify(listCategory),
                        coursepercentage: JSON.stringify(listCoursePercentage),
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.status == 'success') {
                            notify(response.status, response.message)
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                    }
                });
            })

            $('#saveCriteria').click(function() {
                var isvalid = true;

                $('#criteriaForm').find('input[required]').each(function() {
                    var $this = $(this);
                    if (!$this.val()) {
                        isvalid = false;
                        $this.addClass('is-invalid');
                        notify('error', $this.siblings('.invalid-feedback').text().trim());
                    } else {
                        $this.removeClass('is-invalid');
                    }
                });

                var id = $('#criteriaId').val();

                var formData = {
                    id: id,
                    primary: $('#primary').prop('checked') ? 1 : 0,
                    with_subcriteria: $('#subcriteria').prop('checked') ? 1 : 0,
                    name: $('#criteriaName').val(),
                    percentage: $('#criteriaPercentage').val(),
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    subcriteria: JSON.stringify(listOfSubCriteria)
                };

                if (isvalid) {
                    $.post(
                            '{{ route('criteria.store') }}',
                            formData
                        )
                        .done(function(response) {
                            notify(response.status, response.message);
                            if (response.status == 'success') {
                                $('#criteriaForm')[0].reset();
                                $('#criteriaModal').modal('hide');
                                load_criteria();
                            }
                        })
                        .fail(function() {
                            notify('error', 'An error occurred. Please try again.');
                        });
                }
            });


            $(document).on('click', '.btn_edit_criteria', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var percentage = $(this).data('percentage');
                var primary = $(this).data('primary');
                var subcriteria = $(this).data('subcriteria');
                var subArr = $(this).data('subArr');

                console.log(primary, subArr);

                $('#criteriaId').val(id);
                $('#criteriaName').val(name);
                $('#criteriaPercentage').val(percentage);
                $('#criteriaModalLabel').text('Edit Criteria');
                $('#primary').prop('checked', primary);
                $('#subcriteria').prop('checked', subcriteria);

                $.get('{{ route('subcriteria.show') }}', {
                        id: id
                    })
                    .done(function(response) {
                        console.log(response)
                        if (response.length > 0) {
                            listOfSubCriteria = response;
                            saveSubCriteria();
                        } else {
                            $('#subcriteriaTable').empty()
                            $('#subcriteriaTableContainer').prop('hidden', true);
                        }
                    });

                $('#criteriaModal').modal('show');
            });

            $(document).on('click', '.btn_delete_criteria', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route('criteria.delete') }}',
                            type: 'POST',
                            data: {
                                id: id,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.status == 'success') {
                                    Swal.fire(
                                        'Deleted!',
                                        response.message,
                                        'success'
                                    );
                                    load_criteria();
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        'An error occurred. Please try again.',
                                        'error'
                                    );
                                }
                            },
                            error: function(response) {
                                Swal.fire(
                                    'Error!',
                                    'An error occurred. Please try again.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            $('#criteriaModal').on('hidden.bs.modal', function() {
                $('#criteriaForm')[0].reset();
                $('#criteriaId').val('');
                $('#criteriaModalLabel').text('Add Criteria');
            });

            $(document).on('click', '.delete_subcriteria', function() {
                var id = $(this).data('id');
                listOfSubCriteria = listOfSubCriteria.filter((element) => element.name != id);
                saveSubCriteria()
            })

            $('.btn_start_exam').click(function() {
                var id = $(this).data('id');
                // $.ajax({        
                //     type: "POST",
                //     url: '',
                //     data: {
                //         id: id
                //     },
                //     success: function(response) {
                //         console.log(response)
                //         if (response.status == 'success') {
                //             window.location.href = response.url;
                //         }
                //     }
                // });
            })

        });

        function editExamDate(id) {
            $('#edit_examid').select2({
                // theme: 'bootstrap4'
            })
            $.ajax({
                type: "GET",
                url: '{{ route('edit.examdate') }}',
                data: {
                    id: id
                },
                success: function(response) {
                    console.log(response)
                    console.log(response.examid);
                    $('#idToUpdateExamDate').val(response.id)
                    $('#edit-select-year').val(response.schoolyear).change()
                    $('#edit-acadprog').val(response.acadprog).change()
                    $('#edit-examinationdate').val(response.examinationdate)
                    $('#edit_examid').val(response.examid).change()
                    $('#edit-takers').val(response.takers)
                    $('#edit-venue').val(response.venue)
                    $('#edit-building').val(response.building)
                    $('#edit-room').val(response.room)


                    $('#modalEditExamDate').modal();

                }
            });
        }

        function saveSubCriteria() {
            console.log(listOfSubCriteria);
            $('#subcriteriaTableContainer').prop('hidden', false);
            $('#subcriteriaTable').empty()
            listOfSubCriteria.forEach((element, key) => {
                var renderHtml = `<tr>
                    <td class="text-center">${key+1}</td>
                    <td>${element.name}</td> 
                    <td>${element.percentage}</td>
                    <td class="text-center"> <span data-id=${element.name} class="delete_subcriteria"> <i class="fas fa-times text-danger"></i> </span> </td>
                </tr>`;

                $('#subcriteriaTable').append(
                    renderHtml
                );
            });
        }

        function handleCheckboxChange(checkbox) {
            const rowId = checkbox.data('id');
            const newIsActive = checkbox.prop('checked');
            console.log(newIsActive, rowId)
            $.ajax({
                url: '/guidance/activatePassingrate', // Replace with your server endpoint
                method: 'GET',
                data: {
                    id: rowId,
                    isactive: newIsActive ? 1 : 0
                },
                success: function(response) {
                    // Handle successful response from the server if needed
                    console.log('Server updated successfully');
                    notify(response.status, response.message)
                    getAllAdmissionSetup()
                },
                error: function(xhr, status, error) {
                    // Handle error response from the server if needed
                    console.error('Error updating server:', error);
                }
            });
        }

        function getAllAdmissionSetup() {
            $.ajax({
                type: "GET",
                url: '{{ route('get.all.setups') }}',
                success: function(response) {
                    console.log(response)
                    load_category_datatable(response.jsonCategories);
                    loadExamDatesDataTable(response.jsonExamDates);
                    load_criteria()
                    $("input[data-bootstrap-switch]").each(function() {
                        $(this).bootstrapSwitch('state', $(this).prop('checked'));

                        $(this).on('switchChange.bootstrapSwitch', function(event, state) {
                            // Call handleCheckboxChange function when checkbox state changes
                            handleCheckboxChange($(this));
                        });
                    })
                }
            })
        }

        function generate_headers() {
            $('.container_categoryhead').empty()
            $('.container_categoryhead').append(`<th></th>`)
            listCategory.map(each =>
                $('.container_categoryhead').append(`
            <th>${each.category}</th>
            `));
            $('.container_categoryhead').append(`<th>GEN%</th>`)
            $('.container_categoryhead').append(`<th>PROVISION</th>`)
            // onChangeCourse();
        }

        function onChangeCourse(ll) {
            $('#tbl_listCoursePercentage').empty();
            generate_headers();

            if (ll.length === 0) {
                return;
            }

            ll.forEach(element => {
                if (element !== '') {
                    var renderHtml = `
                        <tr>
                            <td style="white-space:nowrap;">${element.text}</td>
                            <td hidden> <input id="${element.id}" class="courseid" value="${element.id}"> </td>
                            ${listCategory.map(elem => `<td> <input class="form-control percent" hidden-info="${elem.category}" type="number" placeholder="90%-99%"></td>`).join('')}
                            <td><input class="form-control input_gen" type="number" placeholder="90%-99%"></td>
                            <td style="text-align: center">
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" id="isprovision_${element.id}" class="isprovision">
                                     <label for="isprovision_${element.id}"></label>
                                </div>
                            </td>
                        </tr>
                    `;
                    $('#tbl_listCoursePercentage').append(renderHtml);
                }
            });


            // Assuming you want to bind the change event to the checkboxes
            $('.isprovision').on('change', function() {
                // Handle checkbox change here
                var isChecked = $(this).prop('checked');
                console.log('Checkbox checked:', isChecked);
            });
        }

        function load_category_datatable(data) {
            $("#tbl_category").DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                lengthChange: false,
                columns: [{
                        data: "index",
                        render: (data, type, row) => `<span class="fw_semi_bold"> ${row.index}.</span>`
                    },
                    {
                        data: "description",
                        render: function(type, data, row) {
                            return `<a href="#" class="fw_semi_bold view_category"  data-json='${JSON.stringify(row)}' data-id="${row.id}"> ${row.description}</a>`
                        }
                    },
                    {
                        data: "progname",
                    },
                    {
                        data: "average",
                        render: function(type, data, row) {
                            return `<span class="text-primary fw_semi_bold"> ${row.average}% </span>`
                        }
                    },
                    {
                        data: 'formatted_created_at',
                    },
                    {
                        data: null,
                        render: function(type, data, row) {
                            return `<input type="checkbox" data-bootstrap-switch data-off-color="danger" data-id="${row.id}"  data-on-color="success" ${row.isactive ? "checked" : ""}>`
                        }
                    },
                    {
                        data: null,
                        render: function(type, data, row) {
                            return `
                                <div class="btn-group">
                                    <a type="button" href="/guidance/edit/passingrate?id=${row.id}" data-id="${row.id}" class="btn btn-outline-secondary btn_edit_passing_rate btn_custom_group">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" href="javascript:void(0)" data-id="${row.id}" class="btn btn-outline-danger btn_delete_passing_rate btn_custom_group">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
            });
        }

        // function load_diagnostictest_datatable(data) {
        //     console.log(data)
        //     $("#tbl_diagnosticsetup").DataTable({
        //         autowidth: false,
        //         destroy: true,
        //         data: data,
        //         lengthChange: false,
        //         columns: [{
        //                 data: "id",
        //             },
        //             {
        //                 data: "category_name",
        //             },
        //             {
        //                 data: "examtitle",
        //             },
        //             {
        //                 data: 'checkingoption',
        //             },
        //             {
        //                 data: 'formatted_created_at',
        //             },
        //             {
        //                 data: null,
        //                 className: 'text-right',
        //                 render: function(type, data, row) {
        //                     return `
    //                         <div class="btn-group">
    //                             <a type="button" href="javascript:void(0)" class="btn btn-default"> <i class="far fa-edit text-primary"></i> </a>
    //                             <a type="button" href="javascript:void(0)" class="btn btn-default"> <i class="far fa-trash-alt text-danger"></i> </a> 
    //                         </div>
    //                     `;
        //                 }
        //             },
        //         ],
        //     });
        // }

        function loadDataTable(tableId, data, columns) {
            var table = $(tableId).DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                lengthChange: false,
                columns: columns
            });
        }

        function loadExamDatesDataTable(data) {
            console.log(data);
            var columns = [{
                    data: null,
                    orderable: false,
                    className: "text-center",
                    render: (data, type, row) => !row.status ?
                        '<button class="btn btn-xs btn-danger btn_end_exam">END</button>' :
                        '<button class="btn btn-xs btn-success btn_start_exam">START</button>'
                },
                {
                    data: "description",
                    render: (data, type, row) =>
                        `<span ><a href="javascript:void(0)" onclick="editExamDate(${row.id})">  ${row.description}</a></span>`
                },
                {
                    data: "",
                    render: (data, type, row) => new Date(row.formatted_examdate).toLocaleDateString()
                },
                {
                    data: "venue",
                    render: (data, type, row) => `
                    <span class="">${row.venue}</span> <br>
                    <span class="badge badge-secondary">${row.building} - ${row.room}</span>`

                },

                {
                    data: "available",
                    render: (data, type, row) => `<span class="fw_semi_bold available-takers">
                        <span class="badge badge-success">${row.available}</span> /
                        <span class="badge badge-secondary">${row.takers}</span>
                    </span>`
                },

                {
                    data: null,
                    className: "text-center",
                    render: (data, type, row) => `<span class="badge badge-success">Active</span>`
                },

                {
                    data: null,
                    orderable: false,
                    className: "text-center text-nowrap",
                    render: (type, data, row) => `
                          <a href="javascript:void(0)" class="text-decoration-none text-body mr-2 btn_edit_examdate" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                            <i class="fas fa-edit text-primary me-2"></i>
                          </a>
                          <a href="javascript:void(0)" class="text-decoration-none text-body btn_delete_examdate" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                            <i class="fas fa-trash text-danger me-2"></i>
                          </a>
                    `
                }
            ];

            $("#tbl_entranceexamdates").DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                lengthChange: false,
                columns: columns
            });
        }


        function load_criteria() {
            $.get('{{ route('criteria.all') }}')
                .done(function(data) {
                    initDataTable(data, [{
                            data: 'criteria_name',
                            render: (data, type, row) =>
                                `<span class="fw_semi_bold"> ${row.criteria_name} </span>`
                        },
                        {
                            data: 'criteria_percentage',
                            render: (data, type, row) =>
                                `<span class="fw_semi_bold text-primary"> ${row.criteria_percentage}% </span>`
                        },
                        {
                            data: 'created_at',
                            render: (data, type, row) => new Date(row.created_at).toLocaleDateString()
                        },
                        {
                            data: 'subcriteria',
                            render: (data, type, row) => {
                                if (row.subcriteria.length == 0) {
                                    return `<div class="fw_semi_bold text-muted">No Subcriteria</div>`;
                                }
                                return `<div class="d-flex flex-column fw_semi_bold text-primary" style="font-size:0.8em;line-height:1.2em;">${row.subcriteria.map(subcriteria =>
                                    `${subcriteria.name} (${subcriteria.percentage}%)`
                                ).join('<br>')}</div>`
                            }
                        },

                        {
                            data: null,
                            render: (data, type, row) => `
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary btn_edit_criteria btn_custom_group"
                                   data-subcriteria="${row.with_subcriteria}"
                                   data-primary="${row.primary}"
                                   data-id="${row.id}"
                                   data-name="${row.criteria_name}"
                                   data-percentage="${row.criteria_percentage}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger btn_delete_criteria btn_custom_group"
                                   data-id="${row.id}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        `
                        }

                    ]);
                })
                .fail(error => {
                    console.error('Failed to load criteria:', error);
                    notify('error', 'Failed to load criteria. Please try again.');
                });
        }

        function initDataTable(data, columns) {
            console.log(data);
            $("#tbl_criteria").DataTable({
                autoWidth: false,
                destroy: true,
                data,
                lengthChange: false,
                columns
            });
        }
    </script>
@endsection
