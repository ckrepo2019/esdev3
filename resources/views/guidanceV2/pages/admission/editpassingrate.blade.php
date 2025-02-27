@extends('guidanceV2.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    </script>
    <style>
        .shadow-none {
            box-shadow: none !important;
            border: 1px solid rgb(224, 222, 222) !important;
        }

        .btn-purple {
            background-color: #6f42c1;
            color: #fff;
        }

        .card {
            border: none !important;
        }

        .radius-custom-header {
            color: white;
            border-top-left-radius: .0rem !important;
            border-top-right-radius: .0rem !important;
            background-color: rgba(0, 0, 0, .03);
            border: none;
        }

        #description.form-control {
            /* border: 1px solid #414242; */
            border-radius: 6px;
        }

        .new_input_new_option,
        #new_input_question,
        .input_question,
        .input_new_option,
        #input_question {
            background-color: #dee2e6;
        }

        .select2-container--bootstrap4 .select2-selection {
            /* border: 1px solid #414242; */
            border-radius: 6px;

        }

        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff !important;
            border-color: #007bff !important;
            color: #fff;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
            padding: 0.5rem;
            vertical-align: middle;
        }

        .table-bordered thead th {
            border-bottom-width: 2px;
        }

        .table-borderless td,
        .table-borderless th {
            border: 0;
        }

        .table-purple td,
        .table-purple th {
            background-color: #f3e7ff;
        }

        .table-purple tfoot td,
        .table-purple tfoot th {
            background-color: #f3e7ff;
        }
    </style>
@endsection

@section('content')
@php
$courses1 = DB::table('college_courses')
            ->join('college_colleges', function($join){
                $join->on('college_courses.collegeid', '=', 'college_colleges.id');
                $join->where('college_colleges.acadprogid', 6);
                $join->where('college_colleges.deleted', 0);
            })
            ->where('college_courses.deleted', 0)
            ->select('college_courses.id as id', 'college_courses.courseabrv')
            ->get();
$courses2 =  DB::table('college_courses')
            ->join('college_colleges', function($join){
                $join->on('college_courses.collegeid', '=', 'college_colleges.id');
                $join->where('college_colleges.acadprogid', 8);
                $join->where('college_colleges.deleted', 0);
            })
            ->where('college_courses.deleted', 0)
            ->select('college_courses.id as id', 'college_courses.courseabrv')
            ->get();
@endphp
    <!-- MODAL ADD CATEGORY SETUP -->
    <div class="modal fade" id="modalAddCategory">
        <div class="modal-dialog ">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title">New Subject</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="" class="mb-1 ">Subject Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" placeholder="e.g. Math, English and IQ TEST"
                                    onkeyup="this.value = this.value.toUpperCase();" id="criteria_name">
                            </div>
                            <div class="form-group col-5">
                                <label for="" class="mb-1 ">Passing Rate <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control form-control-sm" placeholder="85"
                                        id="criteria_percent">
                                    <div class="input-group-append">
                                        <span class="input-group-text form-control-sm">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-4">
                                <label for="" class="mb-1 ">Total Items <span class="text-danger">*</span></label>
                                <input type="number" min="0" class="form-control form-control-sm" id="total_items">
                            </div>
                            <div class="form-group col-3">
                                <label for="" class="mb-1 ">Required</label>
                                <div class="icheck-success">
                                    <input type="checkbox" id="new_isrequired">
                                    <label for="new_isrequired"></label>
                                </div>
                            </div>

                            <label for="timelimit_hours" class="mb-1 col-md-12"> Time Limit</label>
                            <div class="form-group col-6">
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control form-control-sm" placeholder="Hours"
                                        id="timelimit_hours">
                                    <div class="input-group-append">
                                        <span class="input-group-text form-control-sm">hours</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-6">
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control form-control-sm" placeholder="Minutes"
                                        id="timelimit_minutes">
                                    <div class="input-group-append">
                                        <span class="input-group-text form-control-sm">minutes</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-purple" id="btn_add_category">Create</button>
                </div>

            </div>

        </div>

    </div>

    <!-- MODAL EDIT CATEGORY SETUP -->
    <div class="modal fade" id="modalEditCategory">
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Subject</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="" class="mb-1 ">Subject Name</label>
                                <input type="text" class="form-control form-control-sm" placeholder="e.g. Math, English and IQ TEST"
                                    onkeyup="this.value = this.value.toUpperCase();" id="edit_criteria_name">
                            </div>
                            <div class="form-group col-md-5">
                                <label for="" class="mb-1 ">Passing Rate</label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control form-control-sm" placeholder="85"
                                        id="edit_criteria_percent">
                                    <div class="input-group-append">
                                        <span class="input-group-text form-control-sm">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="" class="mb-1 ">Total Items</label>
                                <input type="number" min="0" class="form-control form-control-sm" id="edit_total_items">
                            </div>
                            <div class="form-group col-3">
                                <label for="" class="mb-1 ">Required</label>
                                <div class="icheck-success">
                                    <input type="checkbox" id="edit_isrequired">
                                    <label for="edit_isrequired"></label>
                                </div>
                            </div>

                            <label for="timelimit_hours" class="mb-1 col-md-12"> Time Limit</label>
                            <div class="form-group col-md-6">
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control form-control-sm" placeholder="Hours"
                                        id="edit_timelimit_hours">
                                    <div class="input-group-append">
                                        <span class="input-group-text form-control-sm">hours</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control form-control-sm" placeholder="Minutes"
                                        id="edit_timelimit_minutes">
                                    <div class="input-group-append">
                                        <span class="input-group-text form-control-sm">minutes</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-purple" id="btn_update_category">Update</button>
                </div>

            </div>

        </div>

    </div>

    <!-- MODAL VIEW CATEGORY QUESTION SETUP -->
    <div class="modal fade" id="modalViewTestQuestions">
        <div class="modal-dialog modal-xl">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title tesTitleHeader">Subject Questions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="input_instruction" class="mb-1">Instruction</label>
                                <div id="input_instruction_wrapper" class="d-flex align-items-center">
                                    <textarea id="input_instruction" class="form-control input_instruction" disabled></textarea>
                                    <button id="edit_instruction" class="btn btn-primary ml-2">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="button" data-toggle="modal" data-target="#modalAddTestQuestions"
                                class="btn btn-outline-info btn-sm btn_new_question">
                                + Add Question

                            </button>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="table-responsive">
                                <table class="table table-valign-middle table-sm" id="table_questions"
                                    style="width: 100%;font-size: .75rem">
                                    <thead>
                                        <tr>
                                            <th width="10px;"></th>
                                            <th width="40%;">Question</th>
                                            <th>Options</th>
                                            <th>Answer</th>
                                            <th>Points</th>
                                            <th width="20px;"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-purple" id="btn_update_category">Update</button> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT TEST QUESTION SETUP -->
    <div class="modal fade" id="modalEditTestQuestions" tabindex="-1" role="dialog"
        aria-labelledby="modalEditTestQuestions" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Edit Question</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 ">
                            <label for="">Question Image</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" data-purpose="edit" id="image-input2"
                                    onchange="previewFile(this)">
                                <label class="custom-file-label" for="image-input2">Choose file</label>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2 mb-2">
                            <img id="image_preview" src="" alt="" class="img-fluid img-thumbnail m-auto"
                                width="300">
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="input_question" class="mb-1">Question <span
                                        class="text-danger">*</span></label>
                                <textarea name="" id="input_question" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="select-answer" class="mb-1">Answer</label>
                                <select name="answer" class="form-control" id="select-answer"
                                    style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="select-pts" class="mb-1">Points</label>
                                <select name="pts" class="form-control" id="select-pts" style="width: 100%;">
                                    @for ($i = 1; $i <= 100; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" class="form-control input_new_option"
                                        placeholder="Enter Option Here">
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary" id="btn_add_option">Add Option</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-1">Options</label>
                            <div class="option_wrapper row"></div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-purple" id="btn_update_question">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL ADD TEST QUESTION SETUP -->
    <div class="modal fade" id="modalAddTestQuestions">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">New Question</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="" class="mb-1">Select Test Types</label> <br>
                                <div class="icheck-success d-inline m-2">
                                    <input type="radio" id="testtype1" name="testtype" class="radTestType"
                                        value="1" checked>
                                    <label for="testtype1">Multiple Choice</label>
                                </div>
                                <div class="icheck-success d-inline m-2">
                                    <input type="radio" id="testtype2" name="testtype" class="radTestType"
                                        value="2">
                                    <label for="testtype2">True or False</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 ">
                            <label for="">Question Image</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" data-purpose="add" id="image-input"
                                    onchange="previewFile(this)">
                                <label class="custom-file-label" for="image-input">Choose file</label>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2 mb-2">
                            <img id="preview-image" src="" alt="" class="img-fluid img-thumbnail m-auto"
                                width="300" hidden>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="new_input_question" class="mb-1">Question <span
                                        class="text-danger">*</span></label>
                                <textarea name="" id="new_input_question" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="new-select-answer" class="mb-1">Answer</label>
                                <select name="answer" class="form-control" id="new-select-answer"
                                    style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="new-select-pts" class="mb-1">Points</label>
                                <select name="pts" class="form-control" id="new-select-pts" style="width: 100%;">
                                    @for ($i = 1; $i <= 100; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2 option_container">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" class="form-control new_input_new_option"
                                        placeholder="Enter Option Here">
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary" id="new_btn_add_option">Add Option</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3 option_container">
                            <label class="mb-1">Options</label>
                            <div class="new_option_wrapper row"></div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-purple shadow" id="btn_save_question">
                        <i class="far fa-paper-plane mr-1"></i>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAddTitle">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header" style="background-color: #f3f2f2;">
                    <h5 class="modal-title">New Test Title And Direction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <em class="text-danger font-weight-bold">
                                <p>No existing Test! Pls add title and Instructions here to begin.</p>
                            </em>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="test_title" class="mb-1">Title <span class="text-danger">*</span></label>
                            <textarea name="" id="test_title" class="form-control"></textarea>
                            <span class="invalid-feedback" role="alert">
                                <strong>Test Title is required!</strong>
                            </span>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="" class="mb-1">Checking Option</label> <br>
                                <div class="icheck-success d-inline m-2">
                                    <input type="radio" id="automated" name="checkingoption" value="automated"
                                        checked>
                                    <label for="automated">Automated</label>
                                </div>
                                <div class="icheck-success d-inline m-2">
                                    <input type="radio" id="manual" name="checkingoption" value="manual">
                                    <label for="manual">Manual</label>
                                </div>
                            </div>

                        </div>
                        <div class="form-group col-md-12">
                            <label for="test_instruction" class="mb-1">Instruction <span
                                    class="text-danger">*</span></label>
                            <textarea name="" id="test_instruction" class="form-control"></textarea>
                            <span class="invalid-feedback" role="alert">
                                <strong>Test Direction is required!</strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-purple shadow" id="btnSaveTitleAndInstruction">
                        <i class="far fa-paper-plane mr-1"></i>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="content-header ">
        <div class="container-fluid">
            <div class="card shadow-none">
                <div class="card-header">
                    <div class="row px-2 py-4">
                        <div class="col-sm-6">
                            <h5 class="">EDIT EXAM SETUP</h5>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item "> <a href="#">Home</a> </li>
                                <li class="breadcrumb-item "><a
                                        href="/guidance/admission/percentagesetup?page=1">Admission
                                        Management</a> </li>
                                <li class="breadcrumb-item active">Edit Passing Rate</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>

    <div class="content">
        <div class="container-fluid">
            <form>
                <div class="card shadow">
                    <div class="card-header" style="font-size: 17px; color: #000000">
                        <h5 class="card-title d-flex align-items-center" style="width: 100%;font-weight: 600;">
                            <i class="fas fa-wrench mr-1" style="padding-right: 5px;"></i>SETUP INFO
                            <button type="button" class="btn btn-primary shadow ml-auto" id="btnModalAddCategory">
                                <i class="fas fa-plus mr-1"></i> <strong>Add Subject</strong>
                            </button>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-5 col-md-6 p-4">
                                <div class="row p-4" style="background-color: #e9ecef;border-radius: 10px;">
                                    <div class="form-group col-12">
                                        <label class="mb-1">Description</label>
                                        <input type="text" class="form-control" id="description"
                                            value="{{ $details->description }}"
                                            onkeyup="this.value = this.value.toUpperCase();"
                                            placeholder="Add Description Here">
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Description is required!</strong>
                                        </span>
                                    </div>

                                    <div class="form-group col-12">
                                        <label class="mb-1"> Academic Program </label>
                                        <select class="form-control select2 " id="acadprog" name="acadprog_id"
                                            style="width: 100%;" required>
                                            <option value=""> Select AcadProg</option>
                                            @foreach (DB::table('academicprogram')->get() as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $details->acadprog_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->progname }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="" class="mb-1">Select Level</label>
                                        <select class="form-control select2-purple" id="select-level"
                                            style="width: 100%;">
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Grade Level is required!</strong>
                                        </span>
                                    </div>

                                    <div class="form-group col-md-12 course_container" hidden>
                                        {{-- <label class="mb-1">Select Courses</label> --}}
                                        <div class="form-group row align-items-center mb-0">
                                            <div class="col-md-6 ">
                                                <label class="">Select Course </label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="icheck-success float-right">
                                                    <input type="checkbox" id="allCourse">
                                                    <label for="allCourse">Select all</label>
                                                </div>
                                            </div>
                                        </div>
                                        <select class="form-control" id="select-course" style="width: 100%;">
                                            {{-- @foreach (DB::table('college_courses')->where('deleted', 0)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->courseabrv }}
                                                </option>
                                            @endforeach --}}
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Course is required!</strong>
                                        </span>
                                    </div>


                                    <div class="form-group col-md-12 department_container" hidden>
                                        <hr>
                                        <label class="mb-1">Department</label>
                                        <div class="form-group">
                                            <select class="form-control" id="collegeDepartment" style="width: 100%;">
                                                @foreach (DB::table('college_colleges')->where('deleted', 0)->get() as $item)
                                                    <option value="{{ $item->id }}">{{ $item->collegeDesc }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group row align-items-center mb-0">
                                            <div class="col-md-6">
                                                <label class="">Alternative Courses </label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="icheck-danger float-right">
                                                    <input type="checkbox" id="allAlternate">
                                                    <label for="allAlternate">Select all</label>
                                                </div>
                                            </div>
                                        </div>

                                        <select class="form-control" id="alternate-course" style="width: 100%;" multiple>
                                            @foreach (DB::table('college_courses')->where('deleted', 0)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->courseabrv }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Alternative Courses is required!</strong>
                                        </span>
                                    </div>

                                    <div class="form-group col-md-12 strand_container" hidden>
                                        <div class="form-group row align-items-center mb-0">
                                            <div class="col-md-6">
                                                <label class="mb-1">Select Strand</label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="icheck-danger float-right">
                                                    <input type="checkbox" id="allStrand">
                                                    <label for="allStrand">Select all</label>
                                                </div>
                                            </div>
                                        </div>
                                        <select class="form-control" id="select-strand" style="width: 100%;">
                                            @foreach (DB::table('sh_strand')->where('deleted', 0)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->strandcode }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Strand is required!</strong>
                                        </span>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-7 col-md-6 p-4">
                                <div class="p-4" style="background-color: #e9ecef;border-radius: 10px;">
                                    <div class="table-responsive">
                                        <table class="table" id="table_test_categories" style="width: 100%;">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th></th>
                                                    <th>Subjects</th>
                                                    <th>Passing Rate</th>
                                                    <th>Time</th>
                                                    <th>Items</th>
                                                    <th>Required</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Your table rows will go here -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td class="text-right" colspan="2" style="font-weight: 600;">
                                                        AVERAGE:
                                                    </td>
                                                    <!-- Label for Average -->
                                                    <td> <span id="average_passing_rate"
                                                            class="text-success font-weight-bold">0%</span>
                                                    </td>

                                                    <td colspan="4"></td>
                                                    <!-- Empty cells for Time and Items columns -->
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex">
                        <button type="button" class="btn btn-default" onclick="goBack()">Cancel</button>
                        <button type="button" class="btn btn-purple shadow ml-auto" id="btn_update_exam_setup"><i
                                class="far fa-paper-plane mr-1"></i>Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <!-- Bootstrap Switch -->
    <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        var IdToUpdate = 0;
        var IdToUpdateQuestion = 0;
        var details = {!! json_encode($details) !!}
        var optionArray = []
        var queAnswer = '';
        var originalInstruction = '';
        var currentTestType = 1;

        $(document).ready(function() {
            console.log(details);

            $('#acadprog').on('change', function() {
                if($('#acadprog').val() == 6) {
                    $('#select-course').empty()
                    $('#select-course').append(`
                        @foreach($courses1 as $course)
                            <option value="{{ $course->id }}">{{ $course->courseabrv }}</option>
                        @endforeach
                    `)
                }else if($('#acadprog').val() == 8) {
                    $('#select-course').empty()
                    $('#select-course').append(`
                        @foreach($courses2 as $course)
                            <option value="{{ $course->id }}">{{ $course->courseabrv }}</option>
                        @endforeach
                    `)
                }
            })
            if (details.acadprog_id == 6 || details.acadprog_id == 8) {
                $('.strand_container').prop('hidden', true);
                $('.course_container').prop('hidden', false);
                $('.department_container').prop('hidden', false);
               
            } else if (details.acadprog_id == 5) {
                $('.strand_container').prop('hidden', false);
                $('.course_container').prop('hidden', true);
                $('.department_container').prop('hidden', true);
            } else {
                $('.strand_container').prop('hidden', true);
                $('.course_container').prop('hidden', true);
                $('.department_container').prop('hidden', true);
            }

            $('#select-strand').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select Strand',
                multiple: true
            })

            if (details.strand) {
                $('#select-strand').val(details.strand.split(',')).change()
            }

            $('#allStrand').on('click', function() {
                if ($('#allStrand').is(':checked')) {
                    $('#select-strand option').prop('selected', true);
                    $("#select-strand").trigger("change");
                } else {
                    $('#select-strand option').prop('selected', false);
                    $("#select-strand").trigger("change");
                }
            })

            $('#allCourse').on('click', function() {
                if ($('#allCourse').is(':checked')) {
                    $('#select-course option').prop('selected', true);
                    $("#select-course").trigger("change");
                } else {
                    $('#select-course option').prop('selected', false);
                    $("#select-course").trigger("change");
                }
            })

            $('#allAlternate').on('click', function() {
                if ($('#allAlternate').is(':checked')) {
                    $('#alternate-course option').prop('selected', true);
                    $("#alternate-course").trigger("change");
                } else {
                    $('#alternate-course option').prop('selected', false);
                    $("#alternate-course").trigger("change");
                }
            })

            $('#collegeDepartment').select2({
                theme: 'bootstrap4',
                placeholder: 'Select Department',
                allowClear: true

            });

            $('#collegeDepartment').on('change', function() {
                var selectedOptions = $(this).val();
                console.log(selectedOptions);

                if (!selectedOptions) {
                    $('#alternate-course').empty();
                    return
                }


                $.ajax({
                    type: 'GET',
                    data: {
                        department: selectedOptions
                    },
                    url: '{{ route('filter.coursebydepartment') }}',
                    success: function(data) {
                        console.log(data);
                        $('#alternate-course').empty()
                        $('#alternate-course').select2({
                            data: data,
                            allowClear: true,
                            theme: 'bootstrap4',
                            placeholder: 'Select Alternative Courses'
                        })
                    }
                })
            })

            $('#alternate-course').select2({
                multiple: true,
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select Alternate Courses'
            })

            if (details.alternate_course && details.alternate_course.length > 0) {
                $('#alternate-course').val(details.alternate_course.split(',')).trigger('change');
            }

            $('#alternate-course').on('change', function() {
                var selectedOptions = $(this).val();
                console.log(selectedOptions);
            })

            categories_datatable(details.criterias, details.average)

               
            
            if ($('#acadprog').val()) {
                var id = $('#acadprog').val()
                console.log(id);
                // $('#course_container').prop('hidden', true);
                if (id > 0) {

                    if (id == 6 || id == 8) {
                        if($('#acadprog').val() == 6) {
                            $('#select-course').empty()
                            $('#select-course').append(`
                                @foreach($courses1 as $course)
                                    <option value="{{ $course->id }}">{{ $course->courseabrv }}</option>
                                @endforeach
                            `)
                        }else if($('#acadprog').val() == 8) {
                            $('#select-course').empty()
                            $('#select-course').append(`
                                @foreach($courses2 as $course)
                                    <option value="{{ $course->id }}">{{ $course->courseabrv }}</option>
                                @endforeach
                            `)
                        }
                        $('#course_container').prop('hidden', false);
                        console.log('Selected acadprog ' + id + ' is college');

                    } else {
                        $('#select-course').val("").change();
                        $('#course_container').prop('hidden', true);
                        console.log('Selected acadprog ' + id + ' is not college');
                    }

                    $.ajax({
                        type: 'GET',
                        data: {
                            id: id
                        },
                        url: '{{ route('filter.acadprog') }}',
                        success: function(data) {
                            console.log(data);
                            $('#select-level').empty()
                            $('#select-level').select2({
                                data: data,
                                allowClear: true,
                                theme: 'bootstrap4',
                                placeholder: 'Select Level'
                            })

                            $('#select-level').val(details.gradelevel.split(',')).change()
                            acadprog();
                            if(details.courses && details.courses.length > 0){

                                $('#select-course').val(details.courses.split(',')).change()
                            }
                        }
                    })


                }
            }

            $(document).on('click', '.btn_view_category', function() {
                var id = $(this).data('id');
                IdToUpdate = id;
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: '{{ route('get.category.questions') }}',
                    success: function(response) {
                        console.log(response)
                        if (response.title == "" || response.title == null) {
                            $('#modalAddTitle').modal()
                            return
                        } else {
                            if (response.status == 'success') {
                                $('.tesTitleHeader').text(response.title.examtitle
                                    .toUpperCase())
                                $('.input_instruction').data('id', response.direction.id);
                                $('.input_instruction').val(response.direction.textdirection)
                                questions_datatable(response.questions)
                            } else {
                                questions_datatable([])
                                notify(response.status, response.message)
                            }
                            $('#modalViewTestQuestions').modal();
                        }
                    }
                })
            });

            $('#btnSaveTitleAndInstruction').on('click', function() {
                var isvalid = true
                if (!$('#test_title').val()) {
                    notify('error', 'Test Title is required!')
                    $('#test_title').addClass('is-invalid')
                    isvalid = false
                    return
                } else {
                    $('#test_title').removeClass('is-invalid')
                }

                if (!$('#test_instruction').val()) {
                    notify('error', 'Test Instruction is required!')
                    $('#test_instruction').addClass('is-invalid')
                    isvalid = false
                    return
                } else {
                    $('#test_instruction').removeClass('is-invalid')
                }

                var selectedValue = $('input[name="checkingoption"]:checked').val();
                console.log(selectedValue);

                if (isvalid) {
                    $.ajax({
                        type: "GET",
                        data: {
                            id: IdToUpdate,
                            examtitle: $('#test_title').val(),
                            direction: $('#test_instruction').val(),
                            checkingoption: selectedValue
                        },
                        url: '{{ route('store.titledirection') }}',
                        success: function(response) {
                            console.log(response)
                            notify(response.status, response.message);
                            if (response.status == 'success') {
                                $.ajax({
                                    type: "GET",
                                    data: {
                                        id: IdToUpdate,
                                    },
                                    url: '{{ route('get.category.questions') }}',
                                    success: function(response) {
                                        console.log(response)
                                        if (response.title == "" || response
                                            .title == null) {
                                            $('#modalAddTitle').modal()
                                            return
                                        } else {
                                            if (response.status == 'success') {
                                                $('.tesTitleHeader').text(response
                                                    .title
                                                    .examtitle.toUpperCase())
                                                $('.input_instruction').data('id',
                                                    response.direction.id);
                                                $('.input_instruction').val(response
                                                    .direction.textdirection)
                                                questions_datatable(response
                                                    .questions)
                                            } else {
                                                questions_datatable([])
                                                notify(response.status, response
                                                    .message)
                                            }
                                            $('#modalViewTestQuestions').modal();
                                        }
                                    }
                                })
                            }

                            $('#modalAddTitle').modal('hide');
                        }
                    })

                }
            })

            $('#edit_instruction').on('click', function() {
                var $textarea = $('#input_instruction');
                $textarea.prop('disabled', false).focus(); // Enable and focus the textarea
                originalInstruction = $textarea.val(); // Store the original text
                console.log('Editing instruction:', originalInstruction);
            });

            $('.input_instruction').on('blur', function() {
                var newInstruction = $(this).val();
                if (newInstruction !== originalInstruction) {
                    // Send AJAX request to update the instruction
                    $.ajax({
                        type: "GET",
                        url: '{{ route('update.instruction') }}', // Update this with your actual update URL
                        data: {
                            id: $('.input_instruction').data('id'),
                            instruction: newInstruction
                        },
                        success: function(response) {
                            console.log('Instruction updated successfully');
                            notify(response.status, response.message)
                            // Optionally update any UI elements based on the response
                        },
                        error: function(xhr, status, error) {
                            console.error('Error updating instruction:', error);
                            alert(
                                'An error occurred while updating the instruction. Please try again.'
                            );
                        }
                    });
                }
                $(this).prop('disabled', true); // Disable editing after update
            });

            $(document).on('click', '.btn_edit_category', function() {
                var id = $(this).data('id');
                IdToUpdate = id;
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: '/guidance/edit-category',
                    success: function(respo) {
                        if (respo.status == 'success') {
                            var response =  respo.result
                            console.log(response)
                            $('#edit_isrequired').prop('checked', response.required ? true : false);
                            $('#edit_criteria_name').val(response.category_name)
                            $('#edit_criteria_percent').val(response.category_percent)
                            $('#edit_total_items').val(response.category_totalitems)
                            $('#edit_timelimit_hours').val(response.category_timelimit_hrs)
                            $('#edit_timelimit_minutes').val(response.category_timelimit_min)
                            $('#modalEditCategory').modal();
                        }else{
                            notify(respo.status, respo.message)
                        }
                    }
                })


            });

            $(document).on('click', '.btn_delete_category', function() {
                var itemToDelete = $(this).data('id');
                console.log(itemToDelete);
                Swal.fire({
                    type: 'question',
                    title: 'Delete Category?',
                    text: `You won't be able to revert this! `,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "GET",
                            url: '{{ route('adm.delete.category') }}',
                            data: {
                                id: itemToDelete
                            },
                            success: function(response) {
                                console.log(response)
                                notify(response.status, response.message);
                                getAllCategories()
                            }
                        });
                    }
                });

            })

            $('#btn_update_category').on('click', function() {
                console.log(IdToUpdate);
                $.ajax({
                    type: "GET",
                    data: {
                        id: IdToUpdate,
                        exam_setup_id: details.id,
                        required: $('#edit_isrequired').prop('checked') ? 1 : 0,
                        category_name: $('#edit_criteria_name').val(),
                        category_percent: $('#edit_criteria_percent').val(),
                        category_totalitems: $('#edit_total_items').val(),
                        category_timelimit_hrs: $('#edit_timelimit_hours').val(),
                        category_timelimit_min: $('#edit_timelimit_minutes').val(),
                    },
                    url: '{{ route('adm.update.category') }}',
                    success: function(response) {
                        console.log(response)
                        notify(response.status, response.message)
                        $('#modalEditCategory').modal('hide');
                        getAllCategories()
                    }
                })
            })

            $('#btnModalAddCategory').on('click', function() {
                $('#modalAddCategory').modal();
            })

            $('#acadprog').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select AcadProg'
            })

            $('#acadprog').on('change', function() {
                var id = $(this).val()
                console.log(id);
                if (id == 6 || id == 8) {
                    $('.strand_container').prop('hidden', true);
                    $('.course_container').prop('hidden', false);
                    $('.department_container').prop('hidden', false);
                    console.log('Selected acadprog ' + id + ' is college');
                } else if (id == 5) {
                    $('.strand_container').prop('hidden', false);
                    $('.course_container').prop('hidden', true);
                    $('.department_container').prop('hidden', true);
                    $('#select-course').val("").change();
                    console.log('Selected acadprog ' + id + ' is SHS');
                } else {
                    $('.strand_container').prop('hidden', true);
                    $('.course_container').prop('hidden', true);
                    $('.department_container').prop('hidden', true);
                    console.log('Selected acadprog ' + id + ' is neither college nor SHS');
                }

                $.ajax({
                    type: 'GET',
                    data: {
                        id: id
                    },
                    url: '{{ route('filter.acadprog') }}',
                    success: function(data) {
                        console.log(data);
                        $('#select-level').empty()
                        $('#select-level').select2({
                            data: data,
                            allowClear: true,
                            theme: 'bootstrap4',
                            placeholder: 'Select Level'
                        })


                    }
                })
            });

            $('#select-pts').select2({
                theme: 'bootstrap4',
            });
            $('#new-select-pts').select2({
                theme: 'bootstrap4',
            });
            $('#new-select-answer').select2({
                theme: 'bootstrap4',
            });

            $('#select-level').select2({
                allowClear: true,
                placeholder: "Select Level",
                theme: 'bootstrap4',
                multiple: true
            });

            $('#select-course').select2({
                allowClear: true,
                placeholder: "Select Course",
                theme: 'bootstrap4',
                multiple: true
            });

            $('#btn_add_category').on('click', function() {
                var isvalid = true
                var category_name = $('#criteria_name').val();
                var total_items = $('#total_items').val();
                var criteria_percent = $('#criteria_percent').val();
                var timelimit_hrs = $('#timelimit_hours').val();
                var timelimit_min = $('#timelimit_minutes').val();
                var new_isrequired = $('#new_isrequired').prop('checked');

                console.log(new_isrequired);

                if (!category_name) {
                    isvalid = false
                    $('#criteria_name').addClass('is-invalid');
                    notify('error', 'Subject Name is required!');
                    return
                } else {
                    $('#criteria_name').removeClass('is-invalid');
                }

                if (!criteria_percent) {
                    isvalid = false
                    $('#criteria_percent').addClass('is-invalid');
                    notify('error', 'Subject Percent is required!');
                    return
                } else {
                    $('#criteria_percent').removeClass('is-invalid');
                }

                if (!total_items) {
                    isvalid = false
                    $('#total_items').addClass('is-invalid');
                    notify('error', 'Total Item is required!');
                    return
                } else {
                    $('#total_items').removeClass('is-invalid');
                }

                if (!timelimit_hrs) {
                    isvalid = false
                    $('#timelimit_hours').addClass('is-invalid');
                    notify('error', 'Time limit hrs is required!');
                    return
                } else {
                    $('#timelimit_hours').removeClass('is-invalid');
                }

                if (!timelimit_min) {
                    isvalid = false
                    $('#timelimit_minutes').addClass('is-invalid');
                    notify('error', 'Time limit min is required!');
                    return
                } else {
                    $('#timelimit_minutes').removeClass('is-invalid');
                }

                if (isvalid) {
                    $.ajax({
                        type: "GET",
                        data: {
                            exam_setup_id: details.id,
                            category_name: category_name,
                            category_percent: criteria_percent,
                            category_totalitems: total_items,
                            category_timelimit_hrs: timelimit_hrs,
                            category_timelimit_min: timelimit_min,
                            required: new_isrequired ? 1 : 0,
                        },
                        url: '{{ route('store.category') }}',
                        success: function(data) {
                            console.log(data)
                            notify(data.status, data.message)
                            $('#criteria_name').val('');
                            $('#total_items').val('');
                            $('#criteria_percent').val('');
                            $('#timelimit_hours').val('');
                            $('#timelimit_minutes').val('');
                            $('#modalAddCategory').modal('hide');
                            getAllCategories()
                        }
                    })
                }


            });

            $('#btn_update_exam_setup').on('click', function() {
                $('#btn_update_exam_setup').attr('disabled', true);
                var isvalid = true

                if (!$('#description').val()) {
                    isvalid = false
                    $('#description').addClass('is-invalid');
                    notify('error', 'Description is required!')
                    $('#btn_update_exam_setup').attr('disabled', false);
                    return
                } else {
                    $('#description').removeClass('is-invalid');
                }

                if (!$('#acadprog').val()) {
                    isvalid = false
                    $('#acadprog').addClass('is-invalid');
                    notify('error', 'AcadProg is required!')
                    $('#btn_update_exam_setup').attr('disabled', false);
                    return
                } else {
                    $('#acadprog').removeClass('is-invalid');
                }

                if (!$('#select-level').val().length > 0) {
                    isvalid = false
                    $('#select-level').addClass('is-invalid');
                    notify('error', 'Level is required!')
                    $('#btn_update_exam_setup').attr('disabled', false);
                    return
                } else {
                    $('#select-level').removeClass('is-invalid');
                }

                if ($('#acadprog').val() == 6 || $('#acadprog').val() == 8) {
                    if (!$('#select-course').val().length > 0) {
                        isvalid = false
                        $('#select-course').addClass('is-invalid');
                        notify('error', 'Course is required!')
                        $('#btn_update_exam_setup').attr('disabled', false);
                        return
                    } else {
                        $('#select-course').removeClass('is-invalid');
                    }
                } else if ($('#acadprog').val() == 5) {
                    if (!$('#select-strand').val().length > 0) {
                        isvalid = false
                        $('#select-strand').addClass('is-invalid');
                        notify('error', 'Strand is required!')
                        $('#btn_update_exam_setup').attr('disabled', false);
                        return
                    } else {
                        $('#select-strand').removeClass('is-invalid');
                    }
                }

                console.log($('#select-strand').val().join(
                    ','));

                if (isvalid) {
                    // var graders_passing = $('#graders_percent_passing').val().trim()
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('update.passingrate') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            // graders_percent_passing: graders_passing ?? 0,
                            exam_setup_id: details.id,
                            description: $('#description').val(),
                            gradelevel: $('#select-level').val().join(','),
                            courses: ($('#acadprog').val() == 6 || $('#acadprog').val() == 8) ? $('#select-course').val().join(
                                ',') : '',
                            alternateCourse: $('#alternate-course').val().join(','),
                            // coursepercentage: JSON.stringify(listCoursePercentage),
                            acadprog_id: $('#acadprog').val(),
                            strand: $('#acadprog').val() == 5 ? $('#select-strand').val().join(
                                ',') : '',
                            // average: FINAL_AVG
                        },
                        success: function(response) {
                            console.log(response);
                            notify(response.status, response.message)
                            // if (response.status == 'success') {
                            //     $('#description').val("")
                            // }
                            $('#btn_update_exam_setup').attr('disabled', false);
                            goBack()
                            // window.location.href =
                            //     `/guidance/admission/percentagesetup?page=1`;
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseJSON);
                            $('#btn_update_exam_setup').attr('disabled', false);
                        }
                    });
                } else {
                    $('#btn_update_exam_setup').attr('disabled', false);
                }

            })

            $(document).on('click', '#btn_add_option', function() {
                if ($('.input_new_option').val().trim()) {
                    $('.input_new_option').removeClass('is-invalid')
                    var isExist = optionArray.some(item => item.option == $('.input_new_option').val()
                        .trim())
                    if (isExist) {
                        notify('error', 'This option already exists.');
                        return;
                    } else {
                        addNewOption();
                    }
                } else {
                    $('.input_new_option').addClass('is-invalid')
                    notify('error', 'Option Text is required!')
                }

            });

            $(document).on('keyup', '.input_option', function() {
                var key = $(this).data('key')
                var text = $(this).val()
                console.log(key, text);
                optionArray[key].option = text
                console.log(optionArray);
                updateOptionsDisplay();
            })

            $(document).on('keyup', '.new_input_option', function() {
                var key = $(this).data('key')
                var text = $(this).val()
                console.log(key, text);
                optionArray[key].option = text
                console.log(optionArray);
                updateOptionsDisplay2();
            })

            $(document).on('click', '.btn_remove_option', function() {
                var key = $(this).data('key')
                if (optionArray[key].letter == $('#select-answer').val()) {
                    notify('error', 'Can\'t delete the answer!')
                } else {
                    optionArray.splice(key, 1)
                    console.log(key, optionArray);
                    updateOptionsDisplay();
                }
                console.log(key, optionArray);

            })

            $(document).on('click', '.new_btn_remove_option', function() {
                var key = $(this).data('key')
                if (optionArray[key].letter == $('#new-select-answer').val()) {
                    notify('error', 'Can\'t delete the answer!')
                } else {
                    optionArray.splice(key, 1)
                    console.log(key, optionArray);
                    updateOptionsDisplay2();
                }
                console.log(key, optionArray);

            })

            $(document).on('click', '.btn_edit_question', function() {
                var idToEdit = $(this).data('id');
                IdToUpdateQuestion = idToEdit
                console.log(idToEdit);

                $.ajax({
                    type: "GET",
                    data: {
                        id: idToEdit
                    },
                    url: '{{ route('edit.question') }}',
                    success: function(data) {
                        console.log(data)
                        optionArray = data.testoptions
                        queAnswer = data.testanswer
                        $('#input_question').val(data.testquestion)
                        updateOptionsDisplay();
                        $('#select-answer').val(data.testanswer).change()
                        $('#select-pts').val(data.points).change()


                        if (data.image && data.image != 'test_questions/') {
                            $('#image_preview').attr('src', data.image)
                        } else {
                            $('#image_preview').attr('src', '').prop('hidden', true).attr('src',
                                '');

                        }

                        $('#modalEditTestQuestions').modal();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching question data:', error);
                        alert(
                            'An error occurred while fetching the question data. Please try again.'
                        );
                    }
                })

            })

            $(document).on('click', '.btn_delete_question', function() {
                var idToDelete = $(this).data('id');
                console.log(idToDelete);
                Swal.fire({
                    type: 'question',
                    title: 'Delete Question?',
                    text: `You won't be able to revert this! `,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "GET",
                            data: {
                                id: idToDelete
                            },
                            url: '{{ route('delete.question') }}',
                            success: function(data) {
                                console.log(data)
                                notify(data.status, data.message)
                                questions_datatable(data.questions)
                            },
                            error: function(xhr, status, error) {
                                console.error('Error deleting question data:', error);
                                alert(
                                    'An error occurred while deleting the question data. Please try again.'
                                );
                            }
                        })
                    }
                });





            })

            $('#btn_update_question').on('click', function() {
                var isvalid = true;

                if (!$('#input_question').val()) {
                    notify('error', 'Question is required!')
                    $('#input_question').addClass('is-invalid')
                    isvalid = false
                    return
                } else {
                    $('#input_question').removeClass('is-invalid')
                }

                if (!$('#select-answer').val()) {
                    notify('error', 'Answer is required!')
                    $('#select-answer').addClass('is-invalid')
                    isvalid = false
                    return
                } else {
                    $('#select-answer').removeClass('is-invalid')
                }

                var filteredOpt = optionArray.map(item => item.option)


                if (isvalid) {
                    var formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('id', IdToUpdateQuestion);
                    // formData.append('testtype', currentTestType);
                    formData.append('points', $('#select-pts').val());
                    formData.append('question', $('#input_question').val());
                    formData.append('answer', $('#select-answer').val());
                    formData.append('options', filteredOpt.join('*^*'));
                    // Get the image data
                    var imageInput = $('#image-input2')[0];
                    if (imageInput.files.length > 0) {
                        var imageFile = imageInput.files[0];
                        formData.append('image', imageFile);
                    }
                    $.ajax({
                        type: "POST",
                        data: formData,
                        contentType: false, // Don't set contentType to "application/x-www-form-urlencoded"
                        processData: false, // Don't process the data as a form
                        url: '{{ route('update.question') }}',
                        success: function(data) {
                            console.log(data)
                            notify(data.status, data.message)
                            questions_datatable(data.questions)
                            $('#modalEditTestQuestions').modal('hide');
                        },
                        error: function(xhr, status, error) {
                            console.error('Error updating question data:', error);
                            alert(
                                'An error occurred while updating the question data. Please try again.'
                            );
                        }
                    })
                }

            })

            $('.btn_new_question').on('click', function() {
                // console.log('deleting...');
                $('#image-input').val('');
                $('#new_input_question').val('');
                $('#preview-image').attr('src', '');
                $('#image-input').next('.custom-file-label').text('Choose file');
                $('#select-answer').val('').change()

                $('.option_wrapper').empty();
                optionArray = [];
            })

            $('.radTestType').on('click', function() {
                currentTestType = $(this).val()
                console.log(currentTestType);

                if (currentTestType == 1) {
                    optionArray = []
                    updateOptionsDisplay2();
                } else if (currentTestType == 2) {
                    optionArray = []
                    var tf = ['true', 'false']

                    for (let i = 0; i < 2; i++) {
                        var newLetter = String.fromCharCode(65 + optionArray
                            .length); // Generate the next letter
                        var newOption = {
                            letter: newLetter,
                            option: tf[i] // Empty option text for the new option
                        };
                        optionArray.push(newOption);
                    }
                    console.log(optionArray);
                    updateOptionsDisplay2();
                }
            })

            $(document).on('click', '#new_btn_add_option', function() {
                if ($('.new_input_new_option').val().trim()) {
                    $('.new_input_new_option').removeClass('is-invalid')
                    var isExist = optionArray.some(item => item.option == $('.new_input_new_option').val()
                        .trim())
                    if (isExist) {
                        notify('error', 'This option already exists.');
                        return;
                    } else {
                        addNewOption2();
                    }
                } else {
                    $('.new_input_new_option').addClass('is-invalid')
                    notify('error', 'Option Text is required!')
                }

            });

            $('#btn_save_question').on('click', function() {
                var isvalid = true

                if (currentTestType == 0) {
                    notify('error', 'Pls Select Types');
                    isvalid = false
                    return
                }

                if (!$('#new_input_question').val().trim()) {
                    notify('error', 'Question is required!')
                    $('#new_input_question').addClass('is-invalid')
                    isvalid = false
                    return
                } else {
                    $('#new_input_question').removeClass('is-invalid')
                }

                if (!$('#new-select-answer').val()) {
                    notify('error', 'Answer is required!')
                    $('#new-select-answer').addClass('is-invalid')
                    isvalid = false
                    return
                } else {
                    $('#new-select-answer').removeClass('is-invalid')
                }

                var filteredOpt = optionArray.map(item => item.option)
                if (!filteredOpt.length > 0) {
                    isvalid = false
                    notify('Add atleast one option!')
                    return
                }

                if (isvalid) {

                    console.log(IdToUpdate)
                    console.log(currentTestType)
                    console.log($('#new-select-pts').val())
                    console.log($('#new_input_question').val())
                    console.log($('#new-select-answer').val())
                    console.log(filteredOpt.join('*^*'))

                    var formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('examid', IdToUpdate);
                    formData.append('testtype', currentTestType);
                    formData.append('points', $('#new-select-pts').val());
                    formData.append('testquestion', $('#new_input_question').val());
                    formData.append('answer', $('#new-select-answer').val());
                    formData.append('options', filteredOpt.join('*^*'));
                    // Get the image data
                    var imageInput = $('#image-input')[0];
                    if (imageInput.files.length > 0) {
                        var imageFile = imageInput.files[0];
                        formData.append('image', imageFile);
                    }

                    $.ajax({
                        type: "POST",
                        data: formData,
                        contentType: false, // Don't set contentType to "application/x-www-form-urlencoded"
                        processData: false, // Don't process the data as a form
                        url: '{{ route('store.testquestion') }}',
                        success: function(data) {
                            console.log(data)
                            notify(data.status, data.message)
                            questions_datatable(data.questions)

                            console.log('deleting...');
                            $('#image-input').val('');
                            $('#new_input_question').val('');
                            $('#preview-image').attr('src', '');
                            $('#image-input').next('.custom-file-label').text('Choose file');
                            $('#new-select-answer').val('').change()

                            $('.option_wrapper').empty();
                            $('.new_option_wrapper').empty();
                            optionArray = [];

                            $('#modalEditTestQuestions').modal('hide');
                        },
                        error: function(xhr, status, error) {
                            console.error('Error updating question data:', error);
                            alert(
                                'An error occurred while updating the question data. Please try again.'
                            );
                        }
                    })

                }
            })




        })

        function previewFile(input) {
            var purpose = $(input).data('purpose');
            console.log(purpose);
            var file = input.files[0];
            var preview = $(purpose == 'add' ? '#preview-image' : '#image_preview');
            var label = $(purpose == 'add' ? '.custom-file-label' : '.custom-file-label2');


            if (file) {
                preview.prop('hidden', false);
                var reader = new FileReader();

                reader.onload = function(e) {

                    preview.prop('src', e.target.result);
                    label.text(file.name);
                }

                reader.readAsDataURL(file);
            }
        }

        function addNewOption() {
            var newLetter = String.fromCharCode(65 + optionArray.length); // Generate the next letter
            var newOption = {
                letter: newLetter,
                option: $('.input_new_option').val().trim() // Empty option text for the new option
            };
            optionArray.push(newOption);
            updateOptionsDisplay();
        }

        function addNewOption2() {
            var newLetter = String.fromCharCode(65 + optionArray.length); // Generate the next letter
            var newOption = {
                letter: newLetter,
                option: $('.new_input_new_option').val().trim() // Empty option text for the new option
            };
            optionArray.push(newOption);
            console.log(optionArray);
            updateOptionsDisplay2();
        }

        function updateOptionsDisplay() {
            var optionForSelectAnswer = [];
            $('.option_wrapper').empty();

            // Update letters in the optionArray according to A-Z sequence
            optionArray.forEach((element, key) => {
                element.letter = String.fromCharCode(65 + key); // Generate letters A, B, C, etc.
                var ojbOpt = {
                    id: element.letter,
                    text: `${element.letter}.) ${element.option}`
                };
                optionForSelectAnswer.push(ojbOpt);

                var renderHtml = `
                    <div class="col-md-6 mb-1">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">${element.letter}</span>
                            </div>
                            <input type="text" class="form-control input_option" data-key="${key}" value="${element.option}">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-default btn_remove_option" data-key="${key}">
                                    <i class="fas fa-times text-danger"></i>
                                </button>
                            </div>
                        </div>
                    </div>`;
                $('.option_wrapper').append(renderHtml);
            });

            $('#select-answer').empty().select2({
                data: optionForSelectAnswer,
                theme: 'bootstrap4',
            });
            $('#select-answer').val(queAnswer).change();
        }

        function updateOptionsDisplay2() {
            var optionForSelectAnswer = [];
            $('.new_option_wrapper').empty();

            // Update letters in the optionArray according to A-Z sequence
            optionArray.forEach((element, key) => {
                element.letter = String.fromCharCode(65 + key); // Generate letters A, B, C, etc.
                var ojbOpt = {
                    id: element.letter,
                    text: `${element.letter}.) ${element.option}`
                };
                optionForSelectAnswer.push(ojbOpt);

                var renderHtml = `
                    <div class="col-md-6 mb-1">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">${element.letter}</span>
                            </div>
                            <input type="text" class="form-control new_input_option" data-key="${key}" value="${element.option}">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-default new_btn_remove_option" data-key="${key}">
                                    <i class="fas fa-times text-danger"></i>
                                </button>
                            </div>
                        </div>
                    </div>`;
                $('.new_option_wrapper').append(renderHtml);
            });

            $('#new-select-answer').empty().select2({
                data: optionForSelectAnswer,
                theme: 'bootstrap4',
            });
            $('#new-select-answer').val(queAnswer).change();
        }

        function getAllCategories() {
            $.ajax({
                type: "GET",
                data: {
                    exam_setup_id: details.id
                },
                url: '{{ route('getall.category') }}',
                success: function(data) {
                    console.log(data)
                    categories_datatable(data.data, data.average)
                }
            })
        }

        function categories_datatable(data, average) {
            console.log('updating table...');
            
            $('#average_passing_rate').text(`${average}%`)
            $("#table_test_categories").DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                lengthChange: true,
                columns: [{
                        data: "index",
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<span> ${row.index}.</span>`
                        }
                    },
                    {
                        data: "category_name",
                        render: function(type, data, row) {
                            return `<span style="font-weight:600;"> ${row.category_name} </span>`
                        }
                    },
                    {
                        data: "category_percent",
                        render: function(type, data, row) {
                            return `<span> <strong> ${row.category_percent}% </strong></span>`
                        }
                    },
                    {
                        data: null,
                        render: function(type, data, row) {
                            return `<span> ${row.category_timelimit_hrs}hrs ${row.category_timelimit_min}min</span>`
                        }
                    },
                    {
                        data: 'category_totalitems',
                        // className: 'text-center',
                        render: function(type, data, row) {
                            return `<span> ${row.category_totalitems} Items </span>`
                        }
                    },
                    {
                        data: null,
                        className: 'text-center',
                        orderable: false,
                        render: function(type, data, row) {

                            var renderHtml =
                                `${row.required ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>' }`

                            return renderHtml
                        }
                    },
                    {
                        data: null,
                        sortable: false,
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-info btn-sm btn_view_category btn_custom_group mx-1" data-id="${row.id}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-primary btn-sm btn_edit_category btn_custom_group mx-1" data-status="edit" data-id="${row.id}">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm btn_delete_category btn_custom_group mx-1" data-id="${row.id}">
                                    <i class="far fa-trash-alt"></i>
                                </button> </div>
                            `;
                        }
                    },
                ],
            });
        }

        function questions_datatable(data) {
            $("#table_questions").DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                lengthChange: false,
                columns: [{
                        data: 'index',
                        render: function(data, type, row) {
                            let renderSpan = `<span class="font-weight-bold">${row.index}.</span>`
                            return renderSpan;
                        }
                    },
                    {
                        data: "testquestion",

                    },
                    {
                        data: "testoptions",
                        render: function(data, type, row) {
                            if (Array.isArray(data)) {
                                let optionsHtml = data.map(option =>
                                        `<span><strong>${option.letter}</strong>:${option.option}</span>`)
                                    .join(', ');
                                return optionsHtml;
                            }
                            return data;
                        }
                    },

                    {
                        data: "testanswer",
                        // render: function(type, data, row) {
                        //     return `<span class="badge badge-success"> ${row.testanswer}</span>`
                        // }
                    },

                    {
                        data: 'points',
                        render: function(type, data, row) {
                            return `<span class="badge badge-success p-2" style="border-radius: 20px;width:60px;"> ${row.points} pts</span>`
                        }
                    },
                    {
                        data: null,
                        sortable: false,
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn_edit_question btn_custom_group" data-id="${row.id}">
                                    <i class="fas fa-pencil-alt text-primary mr-1"></i>
                                </button>
                                <button type="button" class="btn btn-default btn_delete_question btn_custom_group" data-id="${row.id}">
                                    <i class="fas fa-trash-alt mr-1 text-danger"></i> 
                                </button>
                            </div>
                        `;
                        }
                    },
                ],
            });
        }

        function goBack() {
            window.location.href =
                `/guidance/admission/percentagesetup?page=1`;
        }
    </script>
@endsection
