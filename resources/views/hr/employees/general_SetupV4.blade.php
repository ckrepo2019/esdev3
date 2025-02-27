@extends('hr.layouts.app')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

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
    $course = DB::table('college_courses')->get();
    $terms = DB::table('college_termgrading')->where('deleted', 0)->get();
    $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
    $semester = DB::table('semester')->get();
    $gradelevel = DB::table('gradelevel')->where('deleted', 0)->where('acadprogid', 6)->orderBy('sortid')->get();
    $leave_frequency = DB::table('hr_leave_frequency')->get();
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

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2">
                <div class="">
                    <h1><i class="fa fa-cog"></i> Leave Setup</h1>
                </div>
                <div class="ml-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active font-weight-bold">General Setup</li>
                    </ol>
                </div>
            </div>
        </div>
        <br>
        <div class="container-fluid">
            {{-- <ul class="nav nav-tabs" id="studentInfoTabs" role="tablist" style="border: solid 1px #dee2e6;">
                <li class="nav-item"
                    style="flex: 1 1 13%; border-right: solid 1px #dee2e6; padding-right: 10px; margin-right: 10px;">
                    <a class="nav-link active py-2" href="{{ url('tesda/gradeTransmutation') }}"
                        style="color: black;">Department</a>
                </li>

                <li class="nav-item"
                    style="flex: 1 1 13%; border-right: solid 1px #dee2e6; padding-right: 10px; margin-right: 10px;">
                    <a class="nav-link py-2" href="{{ url('tesda/setup/gradetransmutation/index') }}">Office</a>
                </li>
                <li class="nav-item"
                    style="flex: 1 1 13%; border-right: solid 1px #dee2e6; padding-right: 10px; margin-right: 10px;">
                    <a class="nav-link py-2" href="{{ url('tesda/setup/gradetransmutation/index') }}">Designation</a>
                </li>
                <li class="nav-item"
                    style="flex: 1 1 13%; border-right: solid 1px #dee2e6; padding-right: 10px; margin-right: 10px;">
                    <a class="nav-link py-2" href="{{ url('tesda/setup/gradetransmutation/index') }}">Requirements</a>
                </li>
                <li class="nav-item" style="flex: 1 1 13%; padding-left: 10px; margin-left: 10px;">
                    <a class="nav-link py-2" href="{{ url('tesda/setup/gradetransmutation/index') }}">Leave</a>
                </li>
            </ul> --}}

            <ul class="nav nav-tabs" style="border-bottom: 1px solid #dee2e6;">
                <li class="nav-item" style="flex: 1;">
                    <a class="nav-link" href="#"
                        style="border: 1px solid #dee2e6; border-bottom: none; border-top-left-radius: 5px; border-top-right-radius: 5px; color: black;">Department</a>
                </li>
                <li class="nav-item" style="flex: 1;">
                    <a class="nav-link" href="#"
                        style="border: 1px solid #dee2e6; border-bottom: none; border-top-left-radius: 5px; border-top-right-radius: 5px; color: black;">Office</a>
                </li>
                <li class="nav-item" style="flex: 1;">
                    <a class="nav-link" href="#"
                        style="border: 1px solid #dee2e6; border-bottom: none; border-top-left-radius: 5px; border-top-right-radius: 5px; color: black;">Designation</a>
                </li>
                <li class="nav-item" style="flex: 1;">
                    <a class="nav-link" href="#"
                        style="border: 1px solid #dee2e6; border-bottom: none; border-top-left-radius: 5px; border-top-right-radius: 5px; color: black;">Requirements</a>
                </li>
                <li class="nav-item" style="flex: 1;">
                    <a class="nav-link active" href="#"
                        style="border: 1px solid #dee2e6; border-bottom: none; border-top-left-radius: 5px;  border-top-right-radius: 5px; color: black;">Leave</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="content pt-0">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header  bg-secondary">
                            <h3 class="card-title fw-1000">Leave</h3>

                        </div>

                        <div class="card-body p-2 ">
                            <br>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="">
                                    <button class="btn btn-success btn-sm" id="addGradePointEquivalency"
                                        data-toggle="modal">Add Leave</button>
                                </div>
                                {{-- <div class="input-group" style="width: 200px;">
                                    <input type="text" class="form-control form-control-sm" placeholder="Search">
                                    <button class="btn btn-outline-secondary btn-sm" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div> --}}
                            </div>

                            <table class="table table-bordered table-hover" id="leaveTable"
                                style="font-size:12px; width: 100%;">
                                <thead>
                                    <tr>
                                        {{-- <th scope="col">
                                            <div class="d-flex justify-content-center">
                                                <span class="badge bg-success" style="font-size: 13px;">Active</span>
                                                <span class="badge bg-danger ml-2" style="font-size: 13px;">Inactive</span>
                                            </div>
                                        </th> --}}
                                        <th scope="col">Leave Name</th>
                                        <th scope="col">Leave Days</th>
                                        <th scope="col">Leave Frequency</th>
                                        <th scope="col">Applied to</th>
                                        <th scope="col">Years of Service</th>
                                        {{-- <th scope="col">Term Applied</th> --}}
                                        <th scope="col" class="text-center">Action</th>
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

    <!-- Modal Structure -->
    <div class="modal fade" id="gradePointEquivalencyAddModal" tabindex="-1" aria-labelledby="addECRModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addECRModalLabel"><i class="fas fa-plus"
                            style="font-size:16px;"></i>&nbsp;<strong>New
                            Leave</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalBtn">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="leave_name" class="form-label col-md-6 mt-2" style="font-size: 14px;">Leave Name</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" id="leave_name" placeholder="Enter Leave Name"
                                required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="leave_days" class="form-label mt-2" style="font-size: 14px;">Leave Days</label>
                            <input type="text" class="form-control" id="leave_days" placeholder="Enter Days Name"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="leave_frequency" class="form-label mt-2" style="font-size: 14px;">Leave
                                Frequency</label>
                            <select class="form-control" id="leave_frequency">
                                {{-- <option value="0">Select Frequency</option>
                                @foreach ($leave_frequency as $item)
                                    @if ($item->deleted == 0)
                                        <option value="{{ $item->id }}" style="word-wrap: break-word!important">
                                            {{ $item->leave_frequency_desc }}</option>
                                    @endif
                                @endforeach
                                <option value="add-frequency" class="text-primary add-frequency" id="add-frequency">Add
                                    Frequency</option> --}}
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="applied_to" class="form-label mt-2" style="font-size: 14px;">Applied To</label>
                            <select class="form-control" id="applied_to">
                                <option>Select Employment Status</option>
                                <option value="All Employee">All Employee</option>
                                <option value="Regular Employee">Regular Employee</option>
                                <option value="Administration">Administration</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="years_of_service" class="form-label mt-2" style="font-size: 14px;">Years of
                                Service</label>
                            <input type="text" class="form-control" id="years_of_service"
                                placeholder="Enter Years of Service">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="pay" class="form-label mt-2" style="font-size: 14px;">Pay</label>
                            <select class="form-control" id="pay">
                                <option>Select Mode</option>
                                <option value="1">With Pay</option>
                                <option value="2">Without Pay</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="leave_attachment" class="form-label mt-2" style="font-size: 14px;">Leave
                                Attachment</label>
                            <select class="form-control" id="leave_attachment">
                                <option>Select Mode</option>
                                <option value="1">Required</option>
                                <option value="2">Not Required</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="convert_to_cash" style="zoom: 1.3;">
                        <label class="form-check-label" for="convert_to_cash" style="font-size: 14px;">
                            If not used, Convert to Cash
                        </label>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-success" id="addLeaveBtn">
                            Save <i class="bi bi-save"></i>
                        </button>
                    </div>
                </div>


                <!-- Modal Footer with Save Button -->
                {{-- <div class="modal-footer">
                <button type="submit" form="gradePointEquivalencyForm" id="createGradeEquivalencyBtn"
                    class="btn btn-success"><i class="fas fa-save fa-lg mr-1"></i> SAVE</button>
            </div> --}}
                {{-- <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="createGradeEquivalencyBtn">SAVE</button>
                </div> --}}
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="leaveEditModal" tabindex="-1" aria-labelledby="addECRModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editECRModalLabel"><strong>Edit Leave</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" id="leave_id" placeholder="Enter Leave Name" hidden
                        required>
                    <div class="row mb-3">
                        <label for="leave_name" class="form-label col-md-6 mt-2" style="font-size: 14px;">Leave
                            Name</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" id="edit_leave_name"
                                placeholder="Enter Leave Name" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="leave_days" class="form-label mt-2" style="font-size: 14px;">Leave Days</label>
                            <input type="text" class="form-control" id="edit_leave_days"
                                placeholder="Enter Days Name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="leave_frequency" class="form-label mt-2" style="font-size: 14px;">Leave
                                Frequency</label>
                            <select class="form-control" id="edit_leave_frequency">

                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="applied_to" class="form-label mt-2" style="font-size: 14px;">Applied To</label>
                            <select class="form-control" id="edit_applied_to">
                                <option>Select Employment Status</option>
                                {{-- <option value="All Employee">All Employee</option>
                                <option value="Regular Employee">Regular Employee</option>
                                <option value="Administration">Administration</option> --}}
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="years_of_service" class="form-label mt-2" style="font-size: 14px;">Years of
                                Service</label>
                            <input type="text" class="form-control" id="edit_years_of_service"
                                placeholder="Enter Years of Service">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="pay" class="form-label mt-2" style="font-size: 14px;">Pay</label>
                            <select class="form-control" id="edit_pay">
                                {{-- <option>Select Mode</option> --}}
                                {{-- <option value="1">With Pay</option>
                                <option value="2">Without Pay</option> --}}
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="leave_attachment" class="form-label mt-2" style="font-size: 14px;">Leave
                                Attachment</label>
                            <select class="form-control" id="edit_leave_attachment">
                                {{-- <option>Select Mode</option>
                                <option value="1">Required</option>
                                <option value="2">Not Required</option> --}}
                            </select>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="edit_convert_to_cash" style="zoom: 1.3;">
                        <label class="form-check-label" for="edit_convert_to_cash" style="font-size: 14px;">
                            If not used, Convert to Cash
                        </label>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-success" id="updateLeaveBtn">
                            Update <i class="bi bi-save"></i>
                        </button>
                    </div>
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
                    <h5 class="modal-title" id="gradingComponentsModalLabel">New Leave Frequency</h5>
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
                                <label for="leave_frequency_desc" class="form-label">Leave Frequency Description</label>
                            </div>

                        </div>
                        <div class="row mb-3">

                            <div class="col-12">
                                <input type="text" class="form-control" id="leave_frequency_desc"
                                    placeholder="Enter Description" required>
                            </div>

                        </div>
                    </section>

                    <!-- Add button -->
                    <div class="text-center">
                        <button type="button" class="btn btn-success" id="addLeaveFrequencyBtn">Add</button>
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
                    <h5 class="modal-title" id="gradingComponentsModalLabel">Add Grading Transmutation</h5>
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
                                <label for="gradingDescription" class="form-label">Initial Grade</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">Transmutated Grade</label>
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
                    <h5 class="modal-title" id="gradingComponentsModalLabel">Edit Grade Transmutation</h5>
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
                                <label for="gradingDescription" class="form-label">Initial Grade</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">Transmutated Grade</label>
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
                                        id="editletterGradeEquivalency" placeholder="A+" required>
                                </div>
                            </div>
                        </div>
                    </section>


                    {{-- <section>
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
                    </section> --}}

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
                    <h5 class="modal-title" id="gradingComponentsModalLabel">Edit Grade Transmutation</h5>
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
                                <label for="gradingDescription" class="form-label">Initial Grade</label>
                            </div>
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Transmutated Grade</label>
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
                                        id="editappendletterGradeEquivalency" placeholder="A+" required>

                                </div>
                            </div>
                        </div>
                    </section>




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
                                <label for="gradingDescription" class="form-label">Initial Grade</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">Transmutated Grade</label>
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
                                        id="editletterGradeEquivalency2" placeholder="A+" required>
                                </div>
                            </div>
                        </div>
                    </section>


                    {{-- <section>
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
                    </section> --}}

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
                    <h5 class="modal-title" id="gradingComponentsModalLabel">Edit Grade Transmutation</h5>
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
                                <label for="gradingDescription" class="form-label">Initial Grade</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">Transmutated Grade</label>
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
                                        id="editappendletterGradeEquivalency2" placeholder="A+" required>
                                    <span class="input-group-text"
                                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;"><i
                                            class="fas fa-percent"></i></span>
                                </div>
                            </div>
                        </div>
                    </section>




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
    <script>
        $(document).ready(function() {


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




            leaveTable()

            function leaveTable() {

                $("#leaveTable").DataTable({
                    destroy: true,
                    autoWidth: false,

                    ajax: {
                        url: '/hr/leave/fetch',
                        type: 'GET',
                        dataSrc: function(json) {
                            return json;
                        }
                    },
                    columns: [

                        {
                            "data": "leave_type"
                        },
                        {
                            "data": "days"
                        },
                        {
                            "data": "leave_frequency"
                        },
                        {
                            "data": "applied_to"
                        },
                        {
                            "data": "yos"
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
                                $(td).html(rowData.grade_transmutation_desc).addClass(
                                    'align-middle');
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.grade_transmutation_desc).addClass(
                                    'align-middle');
                            }
                        },
                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.grade_transmutation_desc).addClass(
                                    'align-middle');
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.grade_transmutation_desc).addClass(
                                    'align-middle');
                            }
                        },
                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.grade_transmutation_desc).addClass(
                                    'align-middle');
                            }
                        },

                        {
                            'targets': 5,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {

                                var link =
                                    '<a href="#" style="color: #blue; text-decoration: underline; font-weight: bold;" class="edit_specific_leave mr-2" data-id="' +
                                    rowData.id +
                                    '"><i class="fas fa-pencil-alt"></i></a> ' +
                                    '<a href="#" style="color: red; text-decoration: underline; font-weight: bold;" class="delete_specific_leave" data-id="' +
                                    rowData.id +
                                    '"><i class="fas fa-trash-alt"></i></a>';
                                $(td)[0].innerHTML = link;
                                $(td).addClass('text-center align-middle');
                            }
                        },

                    ],


                });
            }

            $(document).on('click', '.edit_specific_leave', function() {

                var leave_id = $(this).attr('data-id')

                $('#leaveEditModal').modal()
                // editgradingPointsTable(grade_transmutation_id)


                $.ajax({
                    type: 'GET',
                    url: '/hr/leave/selected_leave/fetch',
                    data: {
                        leave_id: leave_id
                    },
                    success: function(response) {
                        console.log(response);
                        var specific_leave_details = response.leave;

                        $("#leave_id").val(specific_leave_details[0].id);

                        $("#edit_leave_name").val(specific_leave_details[0].leave_type);
                        $("#edit_leave_days").val(specific_leave_details[0].days);

                        $("#edit_years_of_service").val(specific_leave_details[0].yos);

                        $("#edit_convert_to_cash").prop('checked', specific_leave_details[0]
                            .cash == 1);

                        // Fetch the selected leave type
                        var selectedleave = specific_leave_details[0].applied_to;

                        // Initialize an array of objects containing the leave types
                        let applied_to = [{
                                value: 'All Employee',
                                text: 'All Employee'
                            },
                            {
                                value: 'Regular Employee',
                                text: 'Regular Employee'
                            },
                            {
                                value: 'Administration',
                                text: 'Administration'
                            }
                        ];

                        // Clear the select field
                        $('#edit_applied_to').empty().append(
                            '<option value="">Select Employment Status</option>'
                        );

                        // Loop through the array of leave types and append an option to the select field for each one
                        applied_to.forEach(applied_to => {
                            // Check if the current leave type matches the selected leave type
                            let isSelected = selectedleave === applied_to.value;

                            // Append an option to the select field
                            $('#edit_applied_to').append(
                                `<option value="${applied_to.value}" ${isSelected ? 'selected' : ''}>${applied_to.text}</option>`
                            );
                        });


                        var selectedPay = specific_leave_details[0].withpay;

                        // Initialize an array of objects containing the leave types
                        let withpay = [{
                                value: 1,
                                text: 'With Pay'
                            },
                            {
                                value: 2,
                                text: 'Without Pay'
                            }
                        ];

                        // Clear the select field
                        $('#edit_pay').empty().append(
                            '<option value="">Select Mode</option>'
                        );

                        // Loop through the array of leave types and append an option to the select field for each one
                        withpay.forEach(withpay => {
                            // Check if the current leave type matches the selected leave type
                            let isSelected = selectedPay === withpay.value;

                            // Append an option to the select field
                            $('#edit_pay').append(
                                `<option value="${withpay.value}" ${isSelected ? 'selected' : ''}>${withpay.text}</option>`
                            );
                        });


                        var selectedAttachment = specific_leave_details[0].leave_attachment;

                        // Initialize an array of objects containing the leave types
                        let leave_attachment = [{
                                value: 1,
                                text: 'Required'
                            },
                            {
                                value: 2,
                                text: 'Not Required'
                            }
                        ];

                        // Clear the select field
                        $('#edit_leave_attachment').empty().append(
                            '<option value="">Select Mode</option>'
                        );

                        // Loop through the array of leave types and append an option to the select field for each one
                        leave_attachment.forEach(leave_attachment => {
                            // Check if the current leave type matches the selected leave type
                            let isSelected = selectedAttachment === leave_attachment
                                .value;

                            // Append an option to the select field
                            $('#edit_leave_attachment').append(
                                `<option value="${leave_attachment.value}" ${isSelected ? 'selected' : ''}>${leave_attachment.text}</option>`
                            );
                        });

                        var selectedleavefrequencyid = specific_leave_details[0]
                            .leave_frequency;
                        var selectedleaveFrequency = specific_leave_details[0]
                            .leave_frequency_desc;
                        var leave_frequency = response.leave_frequency;


                        $('#edit_leave_frequency').empty().append(
                            '<option value="">Select Frequency</option>'
                        );

                        leave_frequency.forEach(leave_frequency => {
                            let isSelected = selectedleavefrequencyid && leave_frequency
                                .text ===
                                selectedleaveFrequency;
                            $('#edit_leave_frequency').append(
                                `<option value="${leave_frequency.id}" ${isSelected ? 'selected' : ''}>${leave_frequency.text}</option>`
                            );
                        });

                        // var grade_transmutation_scale = response.grade_transmutation_scale;

                        // $("#gradePointEquivalencyID").val(grade_transmutation
                        //     .grade_transmutation_id);

                    }
                });

            });

            $('#leave_frequency').on('change', function() {
                if ($(this).val() === 'add-frequency') {
                    $('#gradingComponentsModal').modal('show');
                    // Reset selection after modal opens
                    $(this).val('0');
                }
            });

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })

            $('#addLeaveFrequencyBtn').on('click', function(event) {
                // event.preventDefault();
                create_leavefrequency()

            });


            function create_leavefrequency() {
                var leave_frequency_desc = $('#leave_frequency_desc').val();


                $.ajax({
                    type: 'GET',
                    url: '/hr/leave_frequency/create',
                    data: {
                        leave_frequency_desc: leave_frequency_desc,


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
                            leaveTable()
                            select_frequency()
                            $("#leave_frequency_desc").val("");
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }

            select_frequency()

            function select_frequency() {
                $.ajax({
                    type: "GET",
                    url: "/hr/leave_frequency/fetch",
                    success: function(data) {
                        console.log(data);
                        $('#leave_frequency').empty();
                        $('#leave_frequency').append('<option value="0">Select Frequency</option>');
                        $('#leave_frequency').select2({
                            data: data,
                            allowClear: true,
                            placeholder: 'Select Frequency'
                        });
                        $('#leave_frequency').append(
                            '<option value="add-frequency" class="add-frequency" id="add-frequency">Add Frequency</option>'
                        );
                    }
                });
            }

            $('#addLeaveBtn').on('click', function(event) {
                // event.preventDefault();
                create_leave()

            });

            function create_leave() {
                var leave_name = $('#leave_name').val();
                var leave_days = $('#leave_days').val();
                var leave_frequency = $('#leave_frequency').val();
                var applied_to = $('#applied_to').val();
                var years_of_service = $('#years_of_service').val();
                var pay = $('#pay').val();
                var leave_attachment = $('#leave_attachment').val();
                var convert_to_cash = $('#convert_to_cash').is(':checked') ? 1 : 0;

                $.ajax({
                    type: 'GET',
                    url: '/hr/leave/create',
                    data: {
                        leave_name: leave_name,
                        leave_days: leave_days,
                        leave_frequency: leave_frequency,
                        applied_to: applied_to,
                        years_of_service: years_of_service,
                        pay: pay,
                        leave_attachment: leave_attachment,
                        convert_to_cash: convert_to_cash

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


                            $("#leave_name").val("");
                            $("#leave_days").val("");
                            $('#leave_frequency').val("").trigger('change');
                            $('#applied_to').val("").trigger('change');
                            $("#years_of_service").val("");
                            $("#pay").val("").trigger('change');
                            $("#leave_attachment").val("").trigger('change');
                            $("#convert_to_cash").prop('checked', false);

                            $("#leaveTable").DataTable({
                                destroy: true,
                                autoWidth: false,

                                ajax: {
                                    url: '/hr/leave/fetch',
                                    type: 'GET',
                                    dataSrc: function(json) {
                                        return json;
                                    }
                                },
                                columns: [

                                    {
                                        "data": "leave_type"
                                    },
                                    {
                                        "data": "days"
                                    },
                                    {
                                        "data": "leave_frequency"
                                    },
                                    {
                                        "data": "applied_to"
                                    },
                                    {
                                        "data": "yos"
                                    },

                                    {
                                        "data": null
                                    },
                                ],

                                columnDefs: [


                                    {
                                        'targets': 0,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row,
                                            col) {
                                            $(td).html(rowData.grade_transmutation_desc)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 1,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row,
                                            col) {
                                            $(td).html(rowData.grade_transmutation_desc)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 2,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row,
                                            col) {
                                            $(td).html(rowData.grade_transmutation_desc)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 3,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row,
                                            col) {
                                            $(td).html(rowData.grade_transmutation_desc)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 4,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row,
                                            col) {
                                            $(td).html(rowData.grade_transmutation_desc)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },

                                    {
                                        'targets': 5,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row,
                                            col) {

                                            var link =
                                                '<a href="#" style="color: #blue; text-decoration: underline; font-weight: bold;" class="edit_specific_leave mr-2" data-id="' +
                                                rowData.id +
                                                '"><i class="fas fa-pencil-alt"></i></a> ' +
                                                '<a href="#" style="color: red; text-decoration: underline; font-weight: bold;" class="delete_gradepoint_equivalency" data-id="' +
                                                rowData.id +
                                                '"><i class="fas fa-trash-alt"></i></a>';
                                            $(td)[0].innerHTML = link;
                                            $(td).addClass('text-center align-middle');
                                        }
                                    },

                                ],


                            });

                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }

                    }
                });
            }

            $(document).on('click', '#updateLeaveBtn', function() {

                var id = $('#leave_id').val();

                var edit_leave_name = $('#edit_leave_name').val();
                var edit_leave_days = $('#edit_leave_days').val();
                var edit_leave_frequency = $('#edit_leave_frequency').val();
                var edit_applied_to = $('#edit_applied_to').val();
                var edit_years_of_service = $('#edit_years_of_service').val();
                var edit_pay = $('#edit_pay').val();
                var edit_leave_attachment = $('#edit_leave_attachment').val();
                var edit_convert_to_cash = $('#edit_convert_to_cash').is(':checked') ? 1 : 0;

                var formData = new FormData();

                formData.append('id', id);
                formData.append('edit_leave_name', edit_leave_name);
                formData.append('edit_leave_days', edit_leave_days);
                formData.append('edit_leave_frequency', edit_leave_frequency);
                formData.append('edit_applied_to', edit_applied_to);
                formData.append('edit_years_of_service', edit_years_of_service);
                formData.append('edit_pay', edit_pay);
                formData.append('edit_leave_attachment', edit_leave_attachment);
                formData.append('edit_convert_to_cash', edit_convert_to_cash);


                $.ajax({
                    type: 'POST',
                    url: '/hr/leave_frequency/update',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data.message
                            });
                        } else if (data.status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully Updated'
                            });

                            $("#leaveTable").DataTable({
                                destroy: true,
                                autoWidth: false,

                                ajax: {
                                    url: '/hr/leave/fetch',
                                    type: 'GET',
                                    dataSrc: function(json) {
                                        return json;
                                    }
                                },
                                columns: [

                                    {
                                        "data": "leave_type"
                                    },
                                    {
                                        "data": "days"
                                    },
                                    {
                                        "data": "leave_frequency"
                                    },
                                    {
                                        "data": "applied_to"
                                    },
                                    {
                                        "data": "yos"
                                    },

                                    {
                                        "data": null
                                    },
                                ],

                                columnDefs: [


                                    {
                                        'targets': 0,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row,
                                            col) {
                                            $(td).html(rowData
                                                    .grade_transmutation_desc)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 1,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row,
                                            col) {
                                            $(td).html(rowData
                                                    .grade_transmutation_desc)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 2,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row,
                                            col) {
                                            $(td).html(rowData
                                                    .grade_transmutation_desc)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 3,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row,
                                            col) {
                                            $(td).html(rowData
                                                    .grade_transmutation_desc)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 4,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row,
                                            col) {
                                            $(td).html(rowData
                                                    .grade_transmutation_desc)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },

                                    {
                                        'targets': 5,
                                        'orderable': false,
                                        'createdCell': function(td, cellData,
                                            rowData, row,
                                            col) {

                                            var link =
                                                '<a href="#" style="color: #blue; text-decoration: underline; font-weight: bold;" class="edit_specific_leave mr-2" data-id="' +
                                                rowData.id +
                                                '"><i class="fas fa-pencil-alt"></i></a> ' +
                                                '<a href="#" style="color: red; text-decoration: underline; font-weight: bold;" class="delete_gradepoint_equivalency" data-id="' +
                                                rowData.id +
                                                '"><i class="fas fa-trash-alt"></i></a>';
                                            $(td)[0].innerHTML = link;
                                            $(td).addClass(
                                                'text-center align-middle');
                                        }
                                    },

                                ],


                            });


                        } else {
                            Toast.fire({
                                type: 'warning',
                                title: 'Please complete all fields or verify your input'
                            });
                        }

                    }
                });


            });

            $(document).on('click', '.delete_specific_leave', function() {
                var leaveid = $(this).attr('data-id')

                Swal.fire({
                    title: 'Delete Selected Leave',
                    text: 'Are you sure you want to delete selected leave?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<i class="fa fa-trash"></i> Delete',
                    cancelButtonText: '<i class="fa fa-times"></i> Cancel'
                }).then((result) => {
                    if (result.value) {
                        delete_specific_leave(leaveid)

                    }
                })

            })

            function delete_specific_leave(leaveid) {


                $.ajax({
                    type: 'GET',
                    url: '/hr/leave_frequency/delete',
                    data: {
                        leaveid: leaveid,
                    },
                    success: function(data) {
                        if (data.status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: data.message
                            })

                            $("#leaveTable").DataTable({
                                destroy: true,
                                autoWidth: false,

                                ajax: {
                                    url: '/hr/leave/fetch',
                                    type: 'GET',
                                    dataSrc: function(json) {
                                        return json;
                                    }
                                },
                                columns: [

                                    {
                                        "data": "leave_type"
                                    },
                                    {
                                        "data": "days"
                                    },
                                    {
                                        "data": "leave_frequency"
                                    },
                                    {
                                        "data": "applied_to"
                                    },
                                    {
                                        "data": "yos"
                                    },

                                    {
                                        "data": null
                                    },
                                ],

                                columnDefs: [


                                    {
                                        'targets': 0,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row,
                                            col) {
                                            $(td).html(rowData.grade_transmutation_desc)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 1,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row,
                                            col) {
                                            $(td).html(rowData.grade_transmutation_desc)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 2,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row,
                                            col) {
                                            $(td).html(rowData.grade_transmutation_desc)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 3,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row,
                                            col) {
                                            $(td).html(rowData.grade_transmutation_desc)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },
                                    {
                                        'targets': 4,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row,
                                            col) {
                                            $(td).html(rowData.grade_transmutation_desc)
                                                .addClass(
                                                    'align-middle');
                                        }
                                    },

                                    {
                                        'targets': 5,
                                        'orderable': false,
                                        'createdCell': function(td, cellData, rowData, row,
                                            col) {

                                            var link =
                                                '<a href="#" style="color: #blue; text-decoration: underline; font-weight: bold;" class="edit_specific_leave mr-2" data-id="' +
                                                rowData.id +
                                                '"><i class="fas fa-pencil-alt"></i></a> ' +
                                                '<a href="#" style="color: red; text-decoration: underline; font-weight: bold;" class="delete_gradepoint_equivalency" data-id="' +
                                                rowData.id +
                                                '"><i class="fas fa-trash-alt"></i></a>';
                                            $(td)[0].innerHTML = link;
                                            $(td).addClass('text-center align-middle');
                                        }
                                    },

                                ],


                            });




                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data.message
                            })
                        }
                    }
                })
            }
        });
    </script>
@endsection
