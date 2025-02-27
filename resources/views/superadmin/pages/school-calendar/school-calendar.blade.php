@php

    $check_refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->select('refid')->first();

    if (Session::get('currentPortal') == 3) {
        $extend = 'registrar.layouts.app';
    } elseif (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (Session::get('currentPortal') == 10) {
        $extend = 'hr.layouts.app';
    } elseif (Session::get('currentPortal') == 7) {
        $extend = 'studentPortal.layouts.app2';
    } elseif (Session::get('currentPortal') == 6) {
        $extend = 'adminPortal.layouts.app2';
    } elseif (Session::get('currentPortal') == 9) {
        $extend = 'parentsportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 2) {
        $extend = 'principalsportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 1) {
        $extend = 'teacher.layouts.app';
    } elseif (Session::get('currentPortal') == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 11) {
        $extend = 'general.defaultportal.layouts.app';
    } elseif (Session::get('currentPortal') == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } else {
        if (isset($check_refid->refid)) {
            if ($check_refid->refid == 27) {
                $extend = 'academiccoor.layouts.app2';
            } elseif ($check_refid->refid == 35) {
                $extend = 'tesda.layouts.app2';
            } elseif ($check_refid->refid == 36) {
                $extend = 'tesda_trainer.layouts.app2';
            }
        } else {
            $extend = 'general.defaultportal.layouts.app';
        }
    }
@endphp

@extends($extend)

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <style>
        .nav-link.active {
            color: #5498e1 !important;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }

        /* select2 */


        .select2-container--default .select2-selection--multiple .select2-selection__choice {

            background-color: #007bff;
            border: 1px solid #007bff;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {

            color: white;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0;
        }

        input[type=search] {
            height: calc(1.7em + 2px) !important;
        }


        /* calendar */
        #calendar td {
            cursor: pointer;
        }

        #weekList td {
            cursor: pointer;
        }

        #dayList td {
            cursor: pointer;
        }

        .fc-daygrid-day-frame:hover {
            box-shadow: inset 0 0 5px #66aad5;
        }

        .fc-h-event:hover {
            background: #17a2b8 !important;
            border: 1px solid #17a2b8 !important;
        }

        .fc-day-today {
            background: #ffc10776 !important;
        }

        .fc-list-empty-cushion {

            font-size: 20px !important;
        }

        .fc-highlight {

            background: #007bff86 !important;
            background-color: #007bff86 !important;
        }

        .fc-timegrid-event-harness {

            width: 20%;
        }

        .calendar-table,
        .drp-buttons {
            display: none !important;
        }
    </style>
@endsection


@section('content')
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/eugz.css') }}">
    <link rel='stylesheet' href="{{ asset('plugins/fullcalendar-v5-11-3/main.css') }}" />

    <style>
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #aaa;
            border-radius: 4px;
            height: auto !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: 0px !important;
            line-height: 18px !important;
        }
    </style>




    <!-- View Event Modal -->
    <div class="modal fade" id="viewEvent" tabindex="-1" role="dialog" aria-labelledby="viewEventLabel"
        aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewEventLabel">Event Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <ul class="list-group datails_info">
                        <li class="list-group-item p-1 active">
                            <h6 class="m-0 text-center" id="year"></h6>
                        </li>
                        <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                            <h6 class="m-0"><span class="badge badge-info badge-pill" style="width: 100px">What</span>
                            </h6>
                            <h6 style="width: 280px" class="pr-2 m-0 text-right" id="title"></h6>

                        </li>
                        <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                            <h6 class="m-0"><span class="badge badge-info badge-pill" style="width: 100px">Where</span>
                            </h6>
                            <h6 style="width: 280px" class="pr-2 m-0 text-right" id="venue"></h6>

                        </li>
                        <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                            <h6 class="m-0"><span class="badge badge-info badge-pill" style="width: 100px">When</span>
                            </h6>
                            <h6 style="width: 280px" class="pr-2 m-0 text-right" id="time"></h6>

                        </li>
                        <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                            <h6 class="m-0"><span class="badge badge-info badge-pill" style="width: 100px">Whom</span>
                            </h6>
                            <h6 style="width: 280px" class="pr-2 m-0 text-right" id="involve"></h6>

                        </li>
                        <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                            <h6 class="m-0"><span class="badge badge-info badge-pill" style="width: 100px">Status</span>
                            </h6>
                            <h6 style="width: 280px" class="pr-2 m-0 text-right" id="isnoclass"></h6>

                        </li>
                        <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                            <h6 class="m-0"><span class="badge badge-info badge-pill" style="width: 100px">Holiday</span>
                            </h6>
                            <h6 style="width: 280px" class="pr-2 m-0 text-right" id="dayHoliday"></h6>

                        </li>
                    </ul>

                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <div id="viewfootermodifier">
                        <button type="button" data-id="" class="btn btn-sm btn-danger btn-sm delete_event"
                            id="delete_event">Delete</button>
                        <button type="button" data-id="" class="btn btn-sm btn-success btn-sm update_event"
                            id="update_event">Edit</button>
                    </div>
                    <button type="button" data-id="" class="btn btn-sm btn-danger btn-sm delete_event"
                        id="delete_event">Delete</button>
                    {{-- <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button> --}}
                </div>
            </div>
        </div>
    </div>


    <!-- View Event Modal -->


    <!-- Add Event Modal -->

    <div class="modal fade" id="addEvent" tabindex="-1" role="dialog" aria-labelledby="addEventLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content addEvent-modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventLabel">Add Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="form">
                        <input id="start" type="hidden">
                        <input id="end" type="hidden">
                        <input type="hidden" id="eventypeid">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="activitytab-tab" data-toggle="tab"
                                    data-target="#activitytab" type="button" role="tab" aria-controls="activitytab"
                                    aria-selected="true" style="outline: none!important;">Activity</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="holidaytab-tab" data-toggle="tab" data-target="#holidaytab"
                                    type="button" role="tab" aria-controls="holidaytab" aria-selected="false"
                                    style="outline: none!important;">Holiday</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="activitytab" role="tabpanel"
                                aria-labelledby="activitytab-tab">

                                {{-- <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="form-check pr-3">
                                            <input class="form-check-input eventtype" id="activitytype" type="checkbox" value="1" style="width: 18px; height: 18px;">
                                            <small style="font-size: 18px;"><label class="form-check-label">&nbsp;Activity</label></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check pr-3">
                                            <input class="form-check-input eventtype" id="holidaytype" type="checkbox" value="2" style="width: 18px; height: 18px;">
                                            <small style="font-size: 18px;"><label class="form-check-label">&nbsp;Holiday</label></small>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="mb-3 foractivity">
                                    <label for="act_desc" class="form-label">Event Description</label>
                                    <input type="text" class="form-control" id="act_desc" autocomplete="off">

                                </div>

                                <div class="mb-3 foractivity">
                                    <label for="act_venue" class="form-label">Event Venue</label>
                                    <input type="text" class="form-control" id="act_venue">
                                </div>

                                <div class="eventtimedate" hidden>
                                    <div class="d-flex justify-content-between">
                                        <div class="mb-3 mr-3">
                                            <label for="datetimeDate" class="form-label">Event Date</label>
                                            <input type="text" class="form-control p-2" id="datetimeDate"
                                                value="" disabled>

                                        </div>
                                        {{-- <div class="mb-3">
                                            <label for="datetimeTime" class="form-label">Time</label>
                                            <input type="text" class="form-control p-2" id="datetimeTime"  value="" >
                                        </div>
                                        <div class="mb-3">
                                            <label for="datetimeTime" class="form-label">Time</label>
                                            <input type="datetime" class="form-control p-2" id="datetimeTime"  value="" >
                                        </div> --}}



                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="">Time</label>
                                        <input type="text" class="form-control form-control-sm" name=""
                                            id="timepick" style="height: calc(1.619rem + 2px);">
                                    </div>
                                </div>

                                <div style="margin-bottom: 1rem!important" class="md-3 foractivity">
                                    <div class="select_container">
                                        <label for="person_involved">Person Involved</label>
                                        <select id="person_involved" name="person_involved" class=" form-control select2"
                                            style="height: auto!important;"></select>
                                    </div>
                                </div>


                                <!-- hidden student -->
                                <div class="md-3 hidden_student">

                                    <div class="md-3">
                                        <div class="select_container">
                                            <label for="acad_prog">Academic Program</label>
                                            <select id="acad_prog" name="acad_prog"
                                                class=" form-control select2"></select>

                                        </div>
                                    </div>

                                    <div class="md-3">
                                        <div class="select_container">
                                            <label for="grade_level">Grade Level</label>
                                            <select id="grade_level" name="grade_level"
                                                class=" form-control select2"></select>

                                        </div>
                                    </div>

                                    <div class="md-3">
                                        <div class="select_container college hidden">
                                            <label for="courses">Course</label>
                                            <select id="courses" name="courses" class=" form-control select2"></select>

                                        </div>
                                    </div>

                                    <div class="md-3">
                                        <div class="select_container college hidden">
                                            <label for="colleges">College</label>
                                            <select id="colleges" name="colleges"
                                                class=" form-control select2"></select>

                                        </div>
                                    </div>


                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="isNoClass">
                                            <label class="form-check-label font-weight-bold" for="isNoClass">
                                                No Class Event
                                            </label>
                                            <p class="m-0 text-sm">Will appear on student calendar as no class event.</p>
                                        </div>

                                    </div>

                                </div>


                                <!-- hidden faculty -->
                                <div class="hidden_faculty mt-3">

                                    <div class="md-3 hidden_div">
                                        <div class="select_container">
                                            <label for="faculty">Faculty & Staff
                                                <a type="button" class="edit_faculty_modal pl-2 hidden"><i
                                                        class="far fa-edit text-primary"></i></a>
                                                <a type="button" class="delete_faculty pl-1 hidden"><i
                                                        class="far fa-trash-alt text-danger"></i></a>
                                            </label>
                                            <select id="faculty" name="faculty" class=" form-control select2"></select>

                                        </div>
                                    </div>

                                </div>

                                <div class="mt-3">
                                    {{-- <label for="holiday">Holiday</label> --}}
                                    <label for="holiday" id="textdescholiday">Activity
                                        <a type="button" class="edit_activity_modal pl-2"><i
                                                class="far fa-edit text-primary"></i></a>
                                        <a type="button" class="delete_activity pl-1" data-id="4"><i
                                                class="far fa-trash-alt text-danger"></i></a>
                                    </label>
                                    <select id="holiday" name="holiday" class="form-control select2"></select>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="holidaytab" role="tabpanel" aria-labelledby="holidaytab-tab">
                                <div class="mt-3">
                                    {{-- <label for="holiday">Holiday</label> --}}
                                    <label for="holiday" id="textdescholiday">Holiday
                                        <a type="button" class="edit_holiday_modal pl-2"><i
                                                class="far fa-edit text-primary"></i></a>
                                        <a type="button" class="delete_holiday pl-1" data-id="4"><i
                                                class="far fa-trash-alt text-danger"></i></a>
                                    </label>
                                    <select id="holidaytab2" name="holidaytab2" class="form-control select2"></select>
                                </div>
                                <div class="mt-3">
                                    <label for="typeholiday" id="textdescholiday">Type of Holiday</label>
                                    <select id="typeholiday" name="typeholiday" class="form-control select2"></select>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn text-left btn-sm btn-success add_event">Add</button>
                    <button type="button" class="btn text-right  btn-sm btn-secondary"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Item Modal END-->

    <!-- Add Faculty Involve Modal -->

    <div class="modal fade" id="addfaculty" tabindex="-1" role="dialog" aria-labelledby="addfacultyLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addfacultyLabel">Add Faculty</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" id="faculty_id">
                        <label for="faculty_name" class="form-label">Faculty Name</label>
                        <input type="text" class="form-control" id="faculty_name">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-success add_faculty" id="add_faculty">Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Faculty Involve Modal -->


    <!-- Export Modal -->

    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Export File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" id="faculty_id">
                        <label for="export_select" class="form-label">Select file type to export.</label>
                        <select id="export_select" name="export_select" class="form-control form-control-sm">
                            <option value="1">PDF</option>
                            <option value="2">Excel</option>
                        </select>

                    </div>

                    <div class="mb-3">
                        <input type="hidden" id="faculty_id">
                        <label for="sy_export" class="form-label">School Year.</label>
                        <select id="sy_export" name="sy_export" class="form-control form-control-sm">
                            @foreach ($sy as $schoolyear)
                                @if ($schoolyear->isactive == 1)
                                    <option value="{{ $schoolyear->id }}" selected>{{ $schoolyear->sydesc }}</option>
                                @else
                                    <option value="{{ $schoolyear->id }}">{{ $schoolyear->sydesc }}</option>
                                @endif
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-success exportbtn" id="exportbtn">Export</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Activity Modal  --}}

    <div class="modal fade" id="modaladdactivity" tabindex="-1" role="dialog" aria-labelledby="addfacultyLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addfacultyLabel">Add Activity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" id="activity_id">
                        <label for="activity_name" class="form-label">Description</label>
                        <input id="activity_description" class="form-control form-control-sm" autocomplete="off">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-success add_activity" id="add_activity">Add</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Activity Modal END-->

    {{-- Add Holiday Modal  --}}

    <div class="modal fade" id="modaladdholiday" tabindex="-1" role="dialog" aria-labelledby="addfacultyLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addfacultyLabel">Add Holiday</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" id="holiday_id">
                        <label for="holiday_name" class="form-label">Description</label>
                        <input id="holiday_description" class="form-control form-control-sm" autocomplete="off">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-success add_holiday" id="add_holiday">Add</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Holiday Modal END-->


    <!-- BODY -->
    <div class="pt-3 px-2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <div class="card shadow" style="height: 100vh">

                        <div class="pb-2 pl-3 pr-3 pt-3 d-flex justify-content-between">
                            <h5>
                                <i class="fas fa-calendar-alt text-danger"></i>
                                School Calendar
                            </h5>
                            <div class="col-md-6" style="flex-basis: 35%">
                                <label for="filter_sy">School Year</label>
                                <select id="filter_sy" name="filter_sy" class="form-control form-control-sm">
                                    @foreach ($sy as $schoolyear)
                                        @if ($schoolyear->isactive == 1)
                                            <option value="{{ $schoolyear->id }}" selected>
                                                {{ $schoolyear->sydesc }}</option>
                                        @else
                                            <option value="{{ $schoolyear->id }}">{{ $schoolyear->sydesc }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="card-body" style="padding-top: 0px">
                            <div class="fc fc-ltr fc-bootstrap" style="font-size: 12px; max-width:100%; overflow-x:auto;"
                                id="calendar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 d-none d-md-block">
                    <!-- Sidebar Content for larger screens -->
                    <div class="card shadow" style="height: 47vh">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-exclamation-circle text-warning mr-2"></i>
                                Event This Week
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="fc fc-ltr fc-bootstrap" id="weekList"></div>
                        </div>
                    </div>

                    <div class="event-details-holder">
                        <div class="card shadow" style="height: 50vh">
                            <div class="p-2 d-flex justify-content-between"
                                style="border-bottom: 1px solid rgba(0,0,0,.125)">
                                <h5 class="card-title" style="line-height: 25px;">
                                    <i class="fas fa-calendar-day text-info mr-2"></i>
                                    Today's Events
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="fc fc-ltr fc-bootstrap" id="dayList"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 d-md-none d-lg-none d-sm-block">
                    <!-- Sidebar Content for larger screens -->
                    <div class="card shadow" style="height: 47vh">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-exclamation-circle text-warning mr-2"></i>
                                Event This Week
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="fc fc-ltr fc-bootstrap" id="weekList2"></div>
                        </div>
                    </div>

                    <div class="event-details-holder">
                        <div class="card shadow" style="height: 50vh">
                            <div class="p-2 d-flex justify-content-between"
                                style="border-bottom: 1px solid rgba(0,0,0,.125)">
                                <h5 class="card-title" style="line-height: 25px;">
                                    <i class="fas fa-calendar-day text-info mr-2"></i>
                                    Today's Events
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="fc fc-ltr fc-bootstrap" id="dayList2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BODY END-->
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-v5-11-3/main.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

    <script>
        load_schoolcaltype()
        var temp_gradelevelid;
        var calendar;

        var acadprog_g = @json($acad_prog);
        var gradelevel_g = @json($gradelevel);
        var courses_g = @json($courses);
        var colleges_g = @json($colleges);
        var faculty_g = @json($faculty);



        var selected_faculty = null;
        var set_college = null;

        var currentportal = @json(Session::get('currentPortal'));


        // Condition if ang current portal is pwede ba maka edit/setup or for viewing lang jod
        if (currentportal == 2 || currentportal == 3 || currentportal == 17 || currentportal == 24 || currentportal == 14 ||
            currentportal == 16 || currentportal == 10 || currentportal == 6) {

            // function para maka setup ang user
            setup(currentportal);

        } else {

            // function for viewing only (mostly for student)
            view(currentportal);

        }

        // event for cliking the export
        $(document).on('click', '.exportbtn', function() {

            var filetype = $('#export_select').val();
            var sy = $('#sy_export').val();

            if (filetype == 1) {

                window.open('/school-calendar/pdf/' + sy, '_blank');

            } else if (filetype == 2) {

                window.open('/school-calendar/excel/' + sy);
            }
        });


        //Loading the select2 data
        function load_person() {

            var person = [

                {
                    "id": 1,
                    "text": "Student"
                },
                {
                    "id": 2,
                    "text": "Faculty & Staff"
                },
                {
                    "id": 3,
                    "text": "Everyone"
                }
            ]

            $('#person_involved').empty()
            $('#person_involved').append('<option value="">Select Person Involved</option>')
            $('#person_involved').select2({
                data: person,
                allowClear: true,
                placeholder: "Select Person Involved",
            })
        }

        function load_acad_prog() {

            var acad_prog = @json($acad_prog);

            $('#acad_prog').empty()
            $('#acad_prog').append('<option value="">Select Person Involved</option>')
            $('#acad_prog').select2({
                data: acad_prog,
                allowClear: true,
                placeholder: "Select Person Involved",
            })
        }

        function load_gradelevel(grade_level) {

            $('#grade_level').empty()
            $('#grade_level').append('<option value="">Select Person Involved</option>')
            $('#grade_level').select2({
                data: grade_level,
                allowClear: true,
                placeholder: "Select Grade Level",
            })
            if (temp_gradelevelid != null) {

                $('#grade_level').val(temp_gradelevelid).trigger('change');

            }
        }

        function load_courses() {

            var courses = @json($courses);

            $('#courses').empty()
            $('#courses').append('<option value="">Select Person Involved</option>')
            $('#courses').select2({
                data: courses,
                multiple: true,
                allowClear: true,
                placeholder: "Select Person Involved",
            })
        }

        function load_colleges() {

            var colleges = @json($colleges);

            $('#colleges').empty()
            $('#colleges').append('<option value="">Select Person Involved</option>')
            $('#colleges').select2({
                data: colleges,
                multiple: true,
                allowClear: true,
                placeholder: "Select Person Involved",
            })
        }

        function load_faculty() {

            $.ajax({
                type: 'GET',
                url: '{{ route('get.select2.faculty') }}',

                success: function(data) {

                    $('#faculty').empty()
                    $('#faculty').append('<option value="">Select Faculty</option>')
                    $('#faculty').append('<option value="add">Add Faculty</option>')
                    $("#faculty").select2({
                        data: data.faculty,
                        allowClear: true,
                        // multiple: true,
                        placeholder: "Select Faculty",
                    })

                    if (selected_faculty != null) {
                        $('#faculty').val(selected_faculty).change()
                    }

                    faculty_g = data.faculty;
                }
            })
        }

        var holiday = []

        function load_holiday() {

            var activity = holiday.filter(x => x.type == 2);
            $('#holiday').empty()
            $('#holiday').append('<option value="">Select Holiday</option>')
            $('#holiday').append('<option value="val_activity" id="addholiday">Add Activity</option>');
            $('#holiday').select2({
                data: activity,
                allowClear: true,
                placeholder: "Activity",
            })
        }

        function load_holidaytab2() {
            var holidaytab2 = holiday.filter(x => x.type == 1);

            $('#holidaytab2').empty()
            $('#holidaytab2').append('<option value="">Select Holiday</option>')
            $('#holidaytab2').append('<option value="val_holiday" id="addholidaytab2">Add Holiday</option>');
            $('#holidaytab2').select2({
                data: holidaytab2,
                allowClear: true,
                placeholder: "Holiday",
            })
        }

        function typeholiday() {
            $('#typeholiday').empty()
            $('#typeholiday').append('<option value="">Select Type</option>')
            // $('#typeholiday').append('<option value="val_typeholiday" id="addtypeholiday">Add Holiday Type</option>');
            $('#typeholiday').select2({
                data: holidayTypes,
                allowClear: true,
                placeholder: "Holiday Type",
            })
        }

        var holidayTypes = []

        function loadalltypeholiday() {
            $.ajax({
                type: "GET",
                url: "/school-calendar/loadallholidaytype",
                success: function(data) {
                    holidayTypes = data
                    typeholiday()
                }
            });
        }

        function load_schoolcaltype() {
            $.ajax({
                type: "GET",
                url: "/school-calendar/oad_schoolcaltype",
                success: function(data) {
                    holiday = data
                    load_holiday()
                    load_holidaytab2()
                }
            });
        }


        // function load_holiday(){ 

        //var holiday = [

        //{"id":0, "text":"None"},
        //{"id":1, "text":"Holiday"},
        //{"id":2, "text":"Special Holiday"},
        //{"id":3, "text":"Non-working Holiday"}
        //]

        //$('#holiday').empty()
        //$('#holiday').append('<option value="">Select Holiday</option>')
        //$('#holiday').select2({
        //data: holiday,
        //allowClear: true,
        //placeholder: "Holiday",
        //})
        //}



        //function para ma get and data sa specific event daw i view sa UI 
        function view_event(id, start, end) {
            $.ajax({

                url: '{{ route('get.event') }}',
                type: "GET",
                data: {
                    id: id,
                },
                success: function(data) {
                    console.log(data, 'this is the data');
                    $('#viewEvent').modal();

                    // $('#viewEventLabel').text(data[0]['title']);

                    $('#nodata').addClass('hidden')
                    $('#event-details').removeClass('hidden');

                    $('#title').text(data[0]['title']);
                    $('#venue').text(data[0]['venue']);
                    $('#involve').text(data[0]['involve'])

                    if (data[0]['isnoclass'] == 0) {

                        $('#isnoclass').text("No Class Event")
                    } else {

                        $('#isnoclass').text("With Class Event")
                    }

                    if (data[0]['holiday'] == 0) {
                        $('#dayHoliday').text("None");
                    } else {
                        if (data[0].type == 1) {
                            $('#dayHoliday').text(data[0]['description']);
                        } else {
                            $('#dayHoliday').text(data[0]['typename']);
                        }
                        // $.each(data[0]['holiday'], function(key, value){
                        // 	$('#dayHoliday').text(value.text);
                        // })
                    }

                    $('#editstart').val(data[0]['start']);
                    $('#editend').val(data[0]['end']);

                    var title;

                    console.log(data[0].start, data[0].end);

                    // para ma format ang star date (ex. Feb 1)
                    var range1 = calendar.formatDate(data[0].start, {
                        month: 'short',
                        day: 'numeric',

                    });

                    // para ma format ang end date (ex. Feb 1)
                    var range2 = calendar.formatDate(data[0].end, {
                        month: 'short',
                        day: 'numeric',
                    });


                    console.log(range1, range2);


                    // para ma format ang star time (ex. 12:30pm)
                    var hoursStart = calendar.formatDate(data[0].start, {
                        hour: '2-digit',
                        minute: '2-digit',

                    });

                    // para ma format ang end time (ex. 12:30pm)
                    var hoursEnd = calendar.formatDate(data[0].end, {
                        hour: '2-digit',
                        minute: '2-digit',

                    });

                    // para ma format ang year (ex. 2023)
                    var year = calendar.formatDate(data[0].start, {
                        year: 'numeric'
                    });

                    if (range1 == range2) {

                        //Pag equal ang range1 and range2 pasabot whole day ang event
                        // Display ang date (ex. Febuary 1, 2023)
                        title = calendar.formatDate(data[0].start, {
                            month: 'short',
                            year: 'numeric',
                            day: 'numeric',
                        });

                    } else {
                        // Otherwise, display ang date sa ingani nga format (ex. Feb 1 - Feb2, 2023)
                        title = range1 + " - " + range2 + ", " + year;
                    }

                    // $('#time').text(hoursStart+" - "+hoursEnd);
                    $('#time').text(data[0]['stime'] + " - " + data[0]['etime']);
                    $('#year').text(title);




                }
            });
        }

        //setup(mostly for staff)
        function setup(type) {

            $(document).ready(function() {
                $('#timepick').daterangepicker({
                    timePicker: true,
                    startDate: '07:30 AM',
                    endDate: '08:30 AM',
                    timePickerIncrement: 5,
                    locale: {
                        format: 'hh:mm A',
                        cancelLabel: 'Clear'
                    }
                })

                $('#viewfootermodifier').css('visibility', 'visible')
                var sy_g = $('#filter_sy').val();
                $(".delete_holiday").hide();
                $(".edit_holiday_modal").hide();
                $(".delete_activity").hide();
                $(".edit_activity_modal").hide();
                $('#eventypeid').val(2);

                load_person();
                load_acad_prog();
                load_gradelevel(@json($gradelevel));
                load_courses();
                load_colleges();
                load_faculty();
                load_holiday();
                load_holidaytab2()
                renderCalendar(type, sy_g);
                loadalltypeholiday();
                // event clicks and event change

                $(document).on('change', '#filter_sy', function() {
                    var syid = $(this).val();
                    renderCalendar(type, syid);
                })

                // Hide ang unhide inputs depending sa Person Involved
                $(document).on('change', '#person_involved', function() {

                    var val = $(this).val();

                    if (val == 1) {

                        $('.hidden_faculty').css("display", "none");
                        $('.hidden_student').css("display", "block");

                    } else if (val == 2) {

                        $('.hidden_student').css("display", "none");
                        $('.hidden_faculty').css("display", "block");

                    } else {

                        $('.hidden_student').css("display", "none");
                        $('.hidden_faculty').css("display", "none");

                    }
                });

                $(document).on('click', '.add_event', function() {
                    var valid_data = true;
                    var eventypeid = $('#eventypeid').val();
                    var syid = @json($activeSY);
                    var acad_prog = $('#acad_prog').val();
                    var acadprogid = $('#acad_prog').val();
                    var grade_level = $('#grade_level').val();
                    var gradelevelid = $('#grade_level').val();
                    var courses = $('#courses').val();
                    var courseid = $('#courses').val();
                    var college = $('#colleges').val();
                    var collegeid = $('#colleges').val();
                    var ttime = $('#timepick').val();

                    var start = $('#start').val();
                    var end = $('#end').val();

                    var event_desc = $('#act_desc').val();
                    var act_venue = $('#act_venue').val();
                    var isNoClass = $('#isNoClass').is(":checked")
                    var type = 0;
                    var college_text = "";
                    var course_text = "";

                    if (eventypeid == 1) {
                        var holiday = $('#holidaytab2').val();
                        var selectedOption = $('#holidaytab2').find(':selected');
                        var event_desc = selectedOption.text();
                        var typeholiday = $('#typeholiday').val();

                        if (holiday == null || holiday == '') {
                            notify('error', "Holiday is Required!");
                            valid_data = false
                        }

                        if (typeholiday == null || typeholiday == '') {
                            notify('error', "Holiday Type is Required!");
                            valid_data = false
                        }

                        if (valid_data) {
                            $.ajax({
                                url: '{{ route('add.event') }}',
                                type: "GET",
                                data: {

                                    start: start,
                                    end: end,
                                    event_desc: event_desc,
                                    act_venue: act_venue,
                                    acadprogid: acadprogid,
                                    gradelevelid: gradelevelid,
                                    courseid: courseid,
                                    collegeid: collegeid,
                                    involve: involve,
                                    isNoClass: isNoClass,
                                    type: type,
                                    syid: syid.id,
                                    holiday: holiday,
                                    typeholiday: typeholiday,
                                    eventypeid: eventypeid
                                },
                                success: function(data) {

                                    notify(data[0]['statusCode'], data[0]['message']);
                                    // $('#form')[0].reset();   

                                    calendar.refetchEvents();
                                    weeklist.refetchEvents();
                                }
                            });
                        }

                    } else {

                        var holiday = $('#holiday').val();

                        if (isNoClass) {

                            isNoClass = 1;

                        } else {

                            isNoClass = 0;
                        }

                        var involve = $('#person_involved').val();

                        if (involve == 1) {

                            if (acad_prog == null || acad_prog == "") {

                                notify('error', "Academic Program is required!");
                                return false;

                            } else {

                                if (acad_prog == 6) {

                                    if (courses == null || courses == "") {

                                        notify('error', "Course is required!");
                                        return false;

                                    } else if (college == null || college == "") {

                                        notify('error', "College is required!");
                                        return false;

                                    } else {

                                        gradelevel_g.forEach(element => {

                                            if (grade_level == element.id) {

                                                grade_level = element.text;
                                            }

                                        });



                                        courses_g.forEach(element => {

                                            courseid.forEach(elementid => {

                                                if (elementid == element.id) {

                                                    course_text += element.text + ", ";
                                                }

                                            });


                                        });

                                        colleges_g.forEach(element => {

                                            collegeid.forEach(elementid => {

                                                if (elementid == element.id) {

                                                    college_text += element.text + ", ";
                                                }

                                            });

                                        });

                                        involve = college_text + " " + course_text + " " + grade_level;

                                    }

                                } else {

                                    gradelevel_g.forEach(element => {

                                        if (grade_level == element.id) {

                                            grade_level = element.text;
                                        }

                                    });

                                    acadprog_g.forEach(element => {

                                        if (acad_prog == element.id) {

                                            acad_prog = element.text;
                                        }

                                    });

                                    involve = acad_prog + " " + grade_level;
                                }


                            }

                            type = 1;

                        } else if (involve == 2) {

                            involve = $('#faculty').val();

                            if (involve == null || involve == "") {

                                notify('error', "Faculty and Staff is required!");

                                return false;

                            } else {

                                faculty_g.forEach(element => {

                                    if (involve == element.id) {

                                        involve = element.text;
                                    }

                                });

                            }

                            type = 2;

                        } else if (involve == 3) {

                            involve = "Everyone";
                            type = 0;

                        } else {

                            involve = null;
                        }


                        //validation
                        if (event_desc == null || event_desc == "") {

                            notify('error', 'Event Description is Required!')
                            $('#act_desc').css('box-shadow', 'red 0px 0px 7px');


                        } else if (involve == null || involve == "") {

                            notify('error', 'Person Involved is Required!')
                            $('#person_involved').css('box-shadow', 'red 0px 0px 7px');
                        } else {

                            if (act_venue == null || act_venue == "") {

                                act_venue = "N/A";

                            }

                            $.ajax({

                                url: '{{ route('add.event') }}',
                                type: "GET",
                                data: {

                                    start: start,
                                    end: end,
                                    event_desc: event_desc,
                                    act_venue: act_venue,
                                    acadprogid: acadprogid,
                                    gradelevelid: gradelevelid,
                                    courseid: courseid,
                                    collegeid: collegeid,
                                    involve: involve,
                                    isNoClass: isNoClass,
                                    type: type,
                                    syid: syid.id,
                                    holiday: holiday,
                                    eventypeid: eventypeid,
                                    ttime: ttime
                                },
                                success: function(data) {

                                    notify(data[0]['statusCode'], data[0]['message']);
                                    // $('#form')[0].reset();   

                                    calendar.refetchEvents();
                                    weeklist.refetchEvents();
                                }
                            });

                            $('#act_venue').css('box-shadow', 'red 0px 0px 0px');
                            $('#act_desc').css('box-shadow', 'red 0px 0px 0px');
                            $('#person_involved').css('box-shadow', 'red 0px 0px 0px');
                        }
                    }






                });

                $(document).on('click', '.edit', function() {

                    $.ajax({

                        url: '{{ route('get.event') }}',
                        type: "GET",
                        data: {

                            id: id,
                        },
                        success: function(data) {


                            $('#editstart').val(data[0]['start']);
                            $('#editend').val(data[0]['end']);
                            // $('#eventDetails').modal('toggle');

                        }
                    });
                })

                //Get event data daw i butang dayun sa mga inputs
                $(document).on('click', '#update_event', function() {
                    temp_gradelevelid = null;
                    var id = $(this).val();

                    $('#addEvent').modal();

                    $.ajax({
                        url: '{{ route('get.event') }}',
                        type: "GET",
                        data: {
                            id: id,
                        },
                        success: function(data) {
                            console.log(data, 'this is for update');

                            $('#addEventLabel').text("Edit Event");
                            $('#act_desc').val(data[0]['title']);
                            $('#act_venue').val(data[0]['venue']);
                            $('.add_event').addClass('update');
                            $('.eventtimedate').addClass('hidden');
                            $('.update').removeClass('add_event');
                            $('.update').text("Save");
                            $('.update').val(data[0]['id']);

                            // Set stime and etime to #timepick
                            if (data[0]['stime'] && data[0]['etime']) {
                                var timeRange = data[0]['stime'] + ' - ' + data[0]['etime'];
                                $('#timepick').val(timeRange);
                            }

                            if (data[0]['type'] == 1) {
                                $('#person_involved').val(1).trigger('change');

                                if (data[0]['acadprogid'] == 6) {
                                    var arrayCourseid = data[0]['courseid'].split(' ');
                                    var arrayCollegeid = data[0]['collegeid'].split(' ');

                                    $('#acad_prog').val(data[0]['acadprogid']).trigger(
                                        'change');

                                    temp_gradelevelid = data[0]['gradelevelid'];

                                    $('#courses').val(arrayCourseid).trigger('change');
                                    $('#colleges').val(arrayCollegeid).trigger('change');
                                } else {
                                    $('#acad_prog').val(data[0]['acadprogid']).trigger(
                                        'change');
                                    temp_gradelevelid = data[0]['gradelevelid'];
                                }

                                if (data[0]['isnoclass'] == 1) {
                                    $('#isNoClass').prop('checked', true);
                                } else {
                                    $('#isNoClass').prop('checked', false);
                                }
                            } else if (data[0]['type'] == 2) {
                                $('#person_involved').val(2).trigger('change');

                                faculty_g.forEach(element => {
                                    if (data[0]['involve'] == element.text) {
                                        $('#faculty').val(element.id).trigger('change');
                                    }
                                });
                            } else {
                                $('#person_involved').val(3).trigger('change');
                            }

                            $('#holiday').val(data[0]['holiday']).trigger('change');
                        }
                    });
                });


                //The actual update function
                $(document).on('click', '.update', function() {

                    var id = $(this).val();

                    var acad_prog = $('#acad_prog').val();
                    var acadprogid = $('#acad_prog').val();
                    var grade_level = $('#grade_level').val();
                    var gradelevelid = $('#grade_level').val();
                    var courses = $('#courses').val();
                    var courseid = $('#courses').val();
                    var college = $('#colleges').val();
                    var collegeid = $('#colleges').val();

                    var event_desc = $('#act_desc').val();
                    var act_venue = $('#act_venue').val();
                    var isNoClass = $('#isNoClass').is(":checked");
                    var holiday = $('#holiday').val();
                    var type = 0;
                    var college_text = "";
                    var course_text = "";

                    if (isNoClass) {

                        isNoClass = 1;

                    } else {

                        isNoClass = 0;
                    }


                    var involve = $('#person_involved').val();

                    if (involve == 1) {

                        if (acad_prog == null || acad_prog == "") {

                            notify('error', "Academic Program is required!");
                            return false;

                        } else {

                            if (acad_prog == 6) {

                                if (courses == null || courses == "") {

                                    notify('error', "Course is required!");
                                    return false;

                                } else if (college == null || college == "") {

                                    notify('error', "College is required!");
                                    return false;

                                } else {

                                    gradelevel_g.forEach(element => {

                                        if (grade_level == element.id) {

                                            grade_level = element.text;
                                        }

                                    });

                                    courses_g.forEach(element => {

                                        courseid.forEach(elementid => {

                                            if (elementid == element.id) {

                                                course_text += element.text + ", ";
                                            }

                                        });


                                    });

                                    colleges_g.forEach(element => {

                                        collegeid.forEach(elementid => {

                                            if (elementid == element.id) {

                                                college_text += element.text + ", ";
                                            }

                                        });

                                    });

                                    involve = college_text + " " + course_text + " " + grade_level;
                                }

                            } else {

                                gradelevel_g.forEach(element => {

                                    if (grade_level == element.id) {

                                        grade_level = element.text;
                                    }

                                });

                                acadprog_g.forEach(element => {

                                    if (acad_prog == element.id) {

                                        acad_prog = element.text;
                                    }

                                });

                                involve = acad_prog + " " + grade_level;
                            }


                        }

                        type = 1;

                    } else if (involve == 2) {

                        involve = $('#faculty').val();

                        if (involve == null || involve == "") {

                            notify('error', "Faculty and Staff is required!");

                            return false;

                        } else {

                            faculty_g.forEach(element => {

                                if (involve == element.id) {

                                    involve = element.text;
                                }

                            });

                        }

                        type = 2;

                    } else if (involve == 3) {

                        involve = "Everyone";
                        type = 0;

                    } else {

                        involve = null;
                    }


                    //validation
                    if (event_desc == null || event_desc == "") {

                        notify('error', 'Event Description is Required!')
                        $('#act_desc').css('box-shadow', 'red 0px 0px 7px');


                    } else if (involve == null || involve == "") {

                        notify('error', 'Person Involved is Required!')
                        $('#person_involved').css('box-shadow', 'red 0px 0px 7px');
                    } else {

                        if (act_venue == null || act_venue == "") {

                            act_venue = "N/A";

                        }

                        $.ajax({

                            url: '{{ route('update.event.details') }}',
                            type: "GET",
                            data: {
                                id: id,
                                event_desc: event_desc,
                                act_venue: act_venue,
                                acadprogid: acadprogid,
                                gradelevelid: gradelevelid,
                                courseid: courseid,
                                collegeid: collegeid,
                                involve: involve,
                                isNoClass: isNoClass,
                                type: type,
                                holiday: holiday
                            },
                            success: function(data) {

                                notify(data[0]['statusCode'], data[0]['message']);
                                view_event(data[0]['event'][0]['id'], data[0]['event'][0][
                                    'start'
                                ], data[0]['event'][0]['end'])

                                calendar.refetchEvents();
                                weeklist.refetchEvents();
                            }
                        });

                        $('#act_venue').css('box-shadow', 'red 0px 0px 0px');
                        $('#act_desc').css('box-shadow', 'red 0px 0px 0px');
                        $('#person_involved').css('box-shadow', 'red 0px 0px 0px');
                    }


                })

                $(document).on('click', '.delete_event', function() {

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to remove event?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Remove!'
                    }).then((result) => {

                        var id = $('#delete_event').val();

                        if (result.value) {

                            $.ajax({

                                url: '{{ route('delete.event') }}',
                                type: "GET",
                                data: {
                                    id: id,
                                },
                                success: function(data) {

                                    notify(data[0]['statusCode'], data[0]['message']);
                                    calendar.refetchEvents();
                                    weeklist.refetchEvents();
                                    $(".event-details-holder").load(location.href +
                                        " .event-details-holder");

                                    $('#viewEvent').modal('hide');
                                }
                            });

                        }

                    })

                });

                $(document).on('click', '.add_faculty', function() {
                    let name = $("#faculty_name").val();

                    if (name == null || name == "") {

                        notify('error', "Faculty Name is Required!")
                        $('#faculty_name').css('box-shadow', 'red 0px 0px 7px');
                    } else {

                        $.ajax({

                            url: '{{ route('add.faculty') }}',
                            type: "GET",
                            data: {
                                name: name,
                            },
                            success: function(data) {

                                notify(data[0]['statusCode'], data[0]['message']);
                                load_faculty();
                            }
                        });

                        $('#faculty_name').css('box-shadow', 'red 0px 0px 0px');
                    }

                });

                $(document).on('click', '.edit_faculty_modal', function() {

                    $('#addfacultyLabel').text("Edit Faculty");
                    $('#add_faculty').addClass("edit_faculty");
                    $('#add_faculty').removeClass("add_faculty");
                    $('#add_faculty').text("Save");
                    $('#addfaculty').modal();

                    involve = $('#faculty').val();

                    faculty_g.forEach(element => {

                        if (involve == element.id) {

                            involve = element.text;

                            $("#faculty_name").val(involve);
                        }

                    });

                });

                $(document).on('click', '.edit_faculty', function() {
                    let id = $('#faculty_id').val();
                    let name = $("#faculty_name").val();

                    if (selected_faculty != "add") {

                        selected_faculty = id;

                    }

                    $.ajax({

                        url: '{{ route('edit.faculty') }}',
                        type: "GET",
                        data: {
                            id: id,
                            name: name,
                        },
                        success: function(data) {

                            notify(data[0]['statusCode'], data[0]['message']);
                            load_faculty();
                            $('#faculty_id').val(data[0]['id']).change();
                        }
                    });

                });

                $(document).on('click', '.delete_faculty', function() {
                    let id = $(this).attr('data-id');
                    $.ajax({

                        url: '{{ route('delete.faculty') }}',
                        type: "GET",
                        data: {
                            id: id,
                        },
                        success: function(data) {

                            notify(data[0]['statusCode'], data[0]['message']);
                            load_faculty();
                        }
                    });

                });

                $(document).on('click', '.exportbtn', function() {

                    var filetype = $('#export_select').val();
                    var sy = $('#sy_export').val();

                    if (filetype == 1) {

                        window.open('/school-calendar/pdf/' + sy, '_blank');

                    } else if (filetype == 2) {

                        window.open('/school-calendar/excel/' + sy);
                    }
                });


                //acadprog condition
                $(document).on('change', '#acad_prog', function() {

                    var acad_prog = $(this).val();

                    if (acad_prog == 6) {

                        $('.college').removeClass('hidden')


                    } else {

                        $('.college').removeClass('hidden')
                        $('.college').addClass('hidden')
                        $('#colleges').val(0)
                        $('#courses').val(0)
                    }

                    $.ajax({

                        url: '{{ route('get.select2.gradelevel') }}',
                        type: "GET",
                        data: {

                            acad_prog: acad_prog,

                        },
                        success: function(data) {

                            load_gradelevel(data.gradelevel);
                        }
                    });
                });

                //faculty condition
                $(document).on('change', '#faculty', function() {

                    var faculty = $(this).val();


                    if (faculty == 'add') {

                        $('#faculty_name').val("");
                        $(".edit_faculty_modal").addClass('hidden');
                        $(".delete_faculty").addClass('hidden');
                        $('#addfaculty').modal();


                    } else if (faculty == null || faculty == "") {

                        $(".edit_faculty_modal").addClass('hidden');
                        $(".delete_faculty").addClass('hidden');
                    } else {

                        $('#faculty_id').val(faculty);
                        $(".delete_faculty").attr('data-id', faculty);

                        $(".edit_faculty_modal").removeClass('hidden');
                        $(".delete_faculty").removeClass('hidden');
                    }
                });

                $(document).on('hide.bs.modal', '#addfaculty', function(e) {

                    load_faculty();

                });

                // events for change holiday
                $(document).on('change', '#holiday', function() {
                    var activity = $(this).val();

                    if (activity == null || activity == '') {
                        $(".delete_activity").hide();
                        $(".edit_activity_modal").hide();
                    } else if (activity == 'val_activity') {
                        $('#modaladdactivity').modal('show');
                        $(".delete_activity").hide();
                        $(".edit_activity_modal").hide();
                    } else {
                        $(".delete_activity").show();
                        $(".edit_activity_modal").show();
                    }
                });

                $(document).on('change', '#holidaytab2', function() {
                    var holiday = $(this).val();
                    if (holiday == null || holiday == '') {
                        $(".delete_holiday").hide();
                        $(".edit_holiday_modal").hide();
                    } else if (holiday == 'val_holiday') {
                        $('#modaladdholiday').modal('show');
                        $(".delete_holiday").hide();
                        $(".edit_holiday_modal").hide();
                    } else {
                        $(".delete_holiday").show();
                        $(".edit_holiday_modal").show();
                    }
                })

                // close modal add holiday
                $(document).on('hide.bs.modal', '#modaladdholiday', function(e) {
                    load_schoolcaltype()

                });

                $(document).on('hide.bs.modal', '#addEvent', function(e) {
                    $('#holidaytype').prop('checked', false);
                    $(".delete_holiday").hide();
                    $(".edit_holiday_modal").hide();
                    $(".delete_activity").hide();
                    $(".edit_activity_modal").hide();
                    load_schoolcaltype()
                });

                // Click Add Activity
                $(document).on('click', '.add_activity', function() {
                    let desc = $("#activity_description").val();
                    var eventypeid = 2;

                    if (desc == null || desc == "") {

                        notify('error', "Description is Required!")
                        $('#activity_description').css('box-shadow', 'red 0px 0px 7px');
                    } else {

                        $.ajax({

                            url: '{{ route('add.activity') }}',
                            type: "GET",
                            data: {
                                desc: desc,
                                eventypeid: eventypeid
                            },
                            success: function(data) {
                                load_schoolcaltype()
                                $('#modaladdactivity').modal('hide')

                                notify(data[0]['statusCode'], data[0]['message']);
                            }
                        });
                        $('#activity_description').css('box-shadow', 'red 0px 0px 0px');
                    }
                });

                // Click Add Holiday
                $(document).on('click', '.add_holiday', function() {
                    let desc = $("#holiday_description").val();
                    var eventypeid = 1;

                    if (desc == null || desc == "") {

                        notify('error', "Description is Required!")
                        $('#holiday_description').css('box-shadow', 'red 0px 0px 7px');
                    } else {

                        $.ajax({

                            url: '{{ route('add.holiday') }}',
                            type: "GET",
                            data: {
                                desc: desc,
                                eventypeid: eventypeid
                            },
                            success: function(data) {
                                load_schoolcaltype()
                                $('#modaladdholiday').modal('hide')

                                notify(data[0]['statusCode'], data[0]['message']);
                            }
                        });
                        $('#holiday_description').css('box-shadow', 'red 0px 0px 0px');
                    }
                });

                // click Edit Icon for Holiday Edit
                $(document).on('click', '.edit_holiday_modal', function() {
                    $('#modaladdholiday').modal('show');
                })

                // click Delete Icon for Holiday 
                $(document).on('click', '.delete_holiday', function() {
                    console.log('Holiday delete');
                    let holiday = $('#holidaytab2').val()
                    if (holiday) {
                        $.ajax({
                            type: 'GET',
                            url: '{{ route('delete.holiday') }}',
                            data: {
                                id: holiday
                            },
                            success: function(respo) {
                                notify(respo.status, respo.message)
                                load_schoolcaltype()
                            }
                        })
                    } else {
                        notify('error', 'No Holiday Selected!')
                    }

                })

                // click Edit Icon for Activity Edit
                $(document).on('click', '.edit_activity_modal', function() {

                })

                // click Delete Icon for Activity
                $(document).on('click', '.delete_activity', function() {
                    console.log('Activty delete');
                })

                // Check or uncheck Holiday check box
                $(document).on('click', '#holidaytab-tab', function() {
                    $('#eventypeid').val(1)
                })

                // Check or uncheck Activity check box
                $(document).on('click', '#activitytab-tab', function() {
                    $('#eventypeid').val(2)
                })

            });
        }

        //viewing(mostly for student and parent)
        function view(type) {

            $(document).ready(function() {
                $('#viewfootermodifier').css('visibility', 'hidden')
                var sy_g = $('#filter_sy').val();

                load_person();
                load_acad_prog();
                load_gradelevel(@json($gradelevel));
                load_courses();
                load_colleges();
                load_faculty();
                load_holiday();
                load_holidaytab2()
                var calendarEl = document.getElementById('calendar');
                var weekListEl = document.getElementById('weekList');
                var dayListEl = document.getElementById('dayList');


                weeklist = new FullCalendar.Calendar(weekListEl, {

                    events: '/school-calendar/getall-event/' + type + '/' + sy_g,
                    height: '100%',
                    contentHeight: '100%',
                    timeZone: 'UTC',
                    themeSystem: 'bootstrap',
                    nowIndicator: true,
                    initialView: 'listWeek',
                    headerToolbar: false,

                    eventClick: function(info) {

                        var id = info.event.id;
                        var start = info.event.startStr;
                        var endVar = info.event.endStr;

                        var d = new Date(endVar);
                        var mon = d.getMonth() + 1 <= 9 ? "0" + (d.getMonth() + 1) : d.getMonth() + 1;
                        var day = d.getDate() - 1 <= 9 ? "0" + (d.getDate() - 1) : d.getDate() - 1;

                        var end = d.getFullYear() + "-" + mon + "-" + day + "T23:59:00";

                        console.log(start);


                        $('.delete_event').removeClass('hidden');
                        $('.update_event').removeClass('hidden');

                        $('#delete_event').val(id);
                        $('#update_event').val(id);
                        $('#editid').val(id);
                        // $('#eventDetails').modal('toggle');


                        view_event(id, start, end);

                    },


                });

                //Para sa Daily Calendar View
                daylist = new FullCalendar.Calendar(dayListEl, {

                    events: '/school-calendar/getall-event/' + type + '/' + sy_g,
                    height: '100%',
                    contentHeight: '100%',
                    timeZone: 'UTC',
                    themeSystem: 'bootstrap',
                    nowIndicator: true,
                    initialView: 'listDay',
                    headerToolbar: false,
                    eventClick: function(info) { //pag i-click sa event

                        var id = info.event.id;
                        var start = info.event.startStr;
                        var endVar = info.event.endStr;

                        var d = new Date(endVar);
                        var mon = d.getMonth() + 1 <= 9 ? "0" + (d.getMonth() + 1) : d.getMonth() + 1;
                        var day = d.getDate() - 1 <= 9 ? "0" + (d.getDate() - 1) : d.getDate() - 1;

                        var end = d.getFullYear() + "-" + mon + "-" + day + "T23:59:00";

                        console.log(start);


                        $('.delete_event').removeClass('hidden');
                        $('.update_event').removeClass('hidden');

                        $('#delete_event').val(id);
                        $('#update_event').val(id);
                        $('#editid').val(id);
                        // $('#eventDetails').modal('toggle');


                        view_event(id, start, end);

                    },


                });

                calendar = new FullCalendar.Calendar(calendarEl, {

                    // initialView: 'listWeek',
                    events: '/school-calendar/getall-event/' + type + '/' + sy_g,
                    height: '100%',
                    contentHeight: '100%',
                    timeZone: 'UTC',
                    themeSystem: 'bootstrap',
                    selectable: true,
                    nowIndicator: true,
                    dayMaxEvents: true,
                    displayEventTime: false,
                    customButtons: {
                        export: {
                            text: 'Export',
                            click: function() {

                                $('#exportModal').modal();
                            }
                        },

                    },
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay listMonth',

                    },
                    footerToolbar: {

                        right: 'export',

                    },
                    views: {
                        dayGridMonth: { // name of view
                            titleFormat: {
                                year: 'numeric',
                                month: 'long'
                            }
                        },

                        timeGridWeek: { // name of view
                            titleFormat: {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            }

                        },

                        timeGridDay: { // name of view
                            titleFormat: {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            }

                        },
                    },
                    businessHours: {

                        startTime: '06:00', // a start time (6am in this example)
                        endTime: '19:00', // an end time (6pm in this example)
                    },
                    eventClick: function(info) {

                        var id = info.event.id;
                        var start = info.event.startStr;
                        var endVar = info.event.endStr;

                        var d = new Date(endVar);
                        var mon = d.getMonth() + 1 <= 9 ? "0" + (d.getMonth() + 1) : d.getMonth() + 1;
                        var day = d.getDate() - 1 <= 9 ? "0" + (d.getDate() - 1) : d.getDate() - 1;

                        var end = d.getFullYear() + "-" + mon + "-" + day + "T23:59:00";

                        console.log(start);


                        $('.delete_event').removeClass('hidden');
                        $('.update_event').removeClass('hidden');

                        $('#delete_event').val(id);
                        $('#update_event').val(id);
                        $('#editid').val(id);
                        // $('#eventDetails').modal('toggle');


                        view_event(id, start, end);

                    },

                });



                calendar.render();
                daylist.render();
                weeklist.render();

                $('.fc-today-button').addClass('btn-sm')
                $('.fc-prev-button').addClass('btn-sm')
                $('.fc-next-button').addClass('btn-sm')
                $('.fc-export-button').addClass('btn-sm mt-3 bg-danger border-danger export')

                $('.fc-toolbar').css('margin', '0')
                $('.fc-toolbar').css('padding-top', '0')
                $('.fc-toolbar').css('font-size', '12px')
                $('.fc-list-event').css('cusor', 'pointer')
                $('#weekList').css('font-size', '12px')
                $('#dayList').css('font-size', '12px')

            });
        }

        //function to render the actual callendar
        function renderCalendar(type, syid) {

            var calendarEl = document.getElementById('calendar');
            var weekListEl = document.getElementById('weekList');
            var weekListEl2 = document.getElementById('weekList2');
            var dayListEl = document.getElementById('dayList');
            var dayListEl2 = document.getElementById('dayList2');



            //Para sa Weekly Calendar View
            weeklist = new FullCalendar.Calendar(weekListEl, {

                eventSources: [{
                    url: '/school-calendar/getall-event/' + type + '/' + syid, // Base URL
                    method: 'GET',
                    extraParams: function() {
                        // Dynamically return an object containing the additional parameters
                        return {
                            param1: 'thismonth', // Example of a static parameter
                        };
                    },
                    failure: function() {
                        alert('There was an error while fetching events!');
                    }
                }],
                height: '100%',
                contentHeight: '100%',
                timeZone: 'UTC',
                themeSystem: 'bootstrap',
                nowIndicator: true,
                initialView: 'listWeek',
                headerToolbar: false,
                eventClick: function(info) { //pag i-click sa event

                    var id = info.event.id;
                    var start = info.event.startStr;
                    var endVar = info.event.endStr;

                    var d = new Date(endVar);
                    var mon = d.getMonth() + 1 <= 9 ? "0" + (d.getMonth() + 1) : d.getMonth() + 1;
                    var day = d.getDate() - 1 <= 9 ? "0" + (d.getDate() - 1) : d.getDate() - 1;

                    var end = d.getFullYear() + "-" + mon + "-" + day + "T23:59:00";

                    console.log(start);


                    $('.delete_event').removeClass('hidden');
                    $('.update_event').removeClass('hidden');

                    $('#delete_event').val(id);
                    $('#update_event').val(id);
                    $('#editid').val(id);
                    // $('#eventDetails').modal('toggle');


                    view_event(id, start, end);

                },


            });
            //Para sa Weekly Calendar View
            weeklist2 = new FullCalendar.Calendar(weekListEl2, {

                eventSources: [{
                    url: '/school-calendar/getall-event/' + type + '/' + syid, // Base URL
                    method: 'GET',
                    extraParams: function() {
                        // Dynamically return an object containing the additional parameters
                        return {
                            param1: 'thismonth', // Example of a static parameter
                        };
                    },
                    failure: function() {
                        alert('There was an error while fetching events!');
                    }
                }],
                height: '100%',
                contentHeight: '100%',
                timeZone: 'UTC',
                themeSystem: 'bootstrap',
                nowIndicator: true,
                initialView: 'listWeek',
                headerToolbar: false,
                eventClick: function(info) { //pag i-click sa event

                    var id = info.event.id;
                    var start = info.event.startStr;
                    var endVar = info.event.endStr;

                    var d = new Date(endVar);
                    var mon = d.getMonth() + 1 <= 9 ? "0" + (d.getMonth() + 1) : d.getMonth() + 1;
                    var day = d.getDate() - 1 <= 9 ? "0" + (d.getDate() - 1) : d.getDate() - 1;

                    var end = d.getFullYear() + "-" + mon + "-" + day + "T23:59:00";

                    console.log(start);


                    $('.delete_event').removeClass('hidden');
                    $('.update_event').removeClass('hidden');

                    $('#delete_event').val(id);
                    $('#update_event').val(id);
                    $('#editid').val(id);
                    // $('#eventDetails').modal('toggle');


                    view_event(id, start, end);

                },


            });


            //Para sa Daily Calendar View
            daylist2 = new FullCalendar.Calendar(dayListEl2, {

                eventSources: [{
                    url: '/school-calendar/getall-event/' + type + '/' + syid, // Base URL
                    method: 'GET',
                    extraParams: function() {
                        // Dynamically return an object containing the additional parameters
                        return {
                            param2: 'thisday', // Example of a static parameter
                        };
                    },
                    failure: function() {
                        alert('There was an error while fetching events!');
                    }
                }],
                height: '100%',
                contentHeight: '100%',
                timeZone: 'UTC',
                themeSystem: 'bootstrap',
                nowIndicator: true,
                initialView: 'listDay',
                headerToolbar: false,
                eventClick: function(info) { //pag i-click sa event

                    var id = info.event.id;
                    var start = info.event.startStr;
                    var endVar = info.event.endStr;

                    var d = new Date(endVar);
                    var mon = d.getMonth() + 1 <= 9 ? "0" + (d.getMonth() + 1) : d.getMonth() + 1;
                    var day = d.getDate() - 1 <= 9 ? "0" + (d.getDate() - 1) : d.getDate() - 1;

                    var end = d.getFullYear() + "-" + mon + "-" + day + "T23:59:00";

                    console.log(start);


                    $('.delete_event').removeClass('hidden');
                    $('.update_event').removeClass('hidden');

                    $('#delete_event').val(id);
                    $('#update_event').val(id);
                    $('#editid').val(id);
                    // $('#eventDetails').modal('toggle');


                    view_event(id, start, end);

                },


            });
            //Para sa Daily Calendar View
            daylist = new FullCalendar.Calendar(dayListEl, {

                eventSources: [{
                    url: '/school-calendar/getall-event/' + type + '/' + syid, // Base URL
                    method: 'GET',
                    extraParams: function() {
                        // Dynamically return an object containing the additional parameters
                        return {
                            param2: 'thisday', // Example of a static parameter
                        };
                    },
                    failure: function() {
                        alert('There was an error while fetching events!');
                    }
                }],
                height: '100%',
                contentHeight: '100%',
                timeZone: 'UTC',
                themeSystem: 'bootstrap',
                nowIndicator: true,
                initialView: 'listDay',
                headerToolbar: false,
                eventClick: function(info) { //pag i-click sa event

                    var id = info.event.id;
                    var start = info.event.startStr;
                    var endVar = info.event.endStr;

                    var d = new Date(endVar);
                    var mon = d.getMonth() + 1 <= 9 ? "0" + (d.getMonth() + 1) : d.getMonth() + 1;
                    var day = d.getDate() - 1 <= 9 ? "0" + (d.getDate() - 1) : d.getDate() - 1;

                    var end = d.getFullYear() + "-" + mon + "-" + day + "T23:59:00";

                    console.log(start);


                    $('.delete_event').removeClass('hidden');
                    $('.update_event').removeClass('hidden');

                    $('#delete_event').val(id);
                    $('#update_event').val(id);
                    $('#editid').val(id);
                    // $('#eventDetails').modal('toggle');


                    view_event(id, start, end);

                },


            });


            //Para sa Monthly Calendar View
            calendar = new FullCalendar.Calendar(calendarEl, {

                events: '/school-calendar/getall-event/' + type + '/' + syid,

                height: '100%',
                contentHeight: '100%',
                timeZone: 'UTC',
                themeSystem: 'bootstrap',
                selectable: true,
                editable: true,
                nowIndicator: true,
                dayMaxEvents: true,
                displayEventTime: false,
                customButtons: {
                    export: {
                        text: 'Export',
                        click: function() {

                            $('#exportModal').modal();
                        }
                    },

                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay listMonth',

                },
                footerToolbar: {

                    right: 'export',

                },
                views: {
                    dayGridMonth: { // name of view
                        titleFormat: {
                            year: 'numeric',
                            month: 'long'
                        }
                    },

                    timeGridWeek: { // name of view
                        titleFormat: {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        }

                    },

                    timeGridDay: { // name of view
                        titleFormat: {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        }

                    },
                },
                businessHours: {

                    startTime: '06:00', // a start time (6am in this example)
                    endTime: '19:00', // an end time (6pm in this example)
                },

                select: function(info) {
                    console.log(info, 'selected');
                    var start = info.startStr;

                    // Adjust end date by subtracting one day
                    var end = new Date(info.endStr);
                    end.setDate(end.getDate() - 1); // Subtract 1 day to get the correct end date

                    // Format the adjusted end date as a string
                    var endStr = end.toISOString().split("T")[0];

                    var id = info.id;

                    var hoursStart = calendar.formatDate(start, {
                        hour: '2-digit',
                        minute: '2-digit',
                    });

                    var hoursEnd = calendar.formatDate(endStr, {
                        hour: '2-digit',
                        minute: '2-digit',
                    });

                    dateStart = calendar.formatDate(start, {
                        month: 'short',
                        year: 'numeric',
                        day: 'numeric',
                    });

                    dateEnd = calendar.formatDate(endStr, {
                        month: 'short',
                        year: 'numeric',
                        day: 'numeric',
                    });

                    console.log(dateStart);
                    console.log(dateEnd, 'd');

                    $('#addEventLabel').text("Add Event");
                    $('#act_desc').val("");
                    $('#act_venue').val("");
                    $('.update').addClass('add_event');
                    $('.add_event').removeClass('update');
                    $('.add_event').text("Add");
                    $('#person_involved').val(0).trigger('change');
                    $('#acad_prog').val(0).trigger('change');
                    $('#grade_level').val(0).trigger('change');
                    $('#courses').val(0).trigger('change');
                    $('#colleges').val(0).trigger('change');
                    $('#isNoClass').prop('checked', false);
                    $('#faculty').val(0).trigger('change');
                    $('#holiday').val(0).trigger('change');
                    $('.eventtimedate').removeClass('hidden');

                    if (dateStart == dateEnd) {
                        $('#datetimeDate').val(dateStart);
                    } else {
                        $('#datetimeDate').val(dateStart + " - " + dateEnd);
                    }

                    $('#datetimeTime').val(hoursStart + "-" + hoursEnd);

                    $('#addEvent').modal('toggle');

                    $('#start').val(start);
                    $('#end').val(endStr);
                },
                eventDrop: function(info) { //Pag mag click sa drag and drop

                    var id = info.event.id;
                    var start = info.event.startStr;
                    var end = info.event.endStr;

                    $.ajax({

                        url: '{{ route('update.event') }}',
                        type: "GET",
                        data: {

                            id: id,
                            start: start,
                            end: end,
                        },
                        success: function(data) {

                            weeklist.refetchEvents();
                            daylist.refetchEvents();


                        }
                    });
                },
                eventClick: function(info) { //Pag mag click sa event

                    var id = info.event.id;
                    var start = info.event.startStr;
                    var endVar = info.event.endStr;

                    var d = new Date(endVar);
                    var mon = d.getMonth() + 1 <= 9 ? "0" + (d.getMonth() + 1) : d.getMonth() + 1;
                    var day = d.getDate() - 1 <= 9 ? "0" + (d.getDate() - 1) : d.getDate() - 1;

                    var end = d.getFullYear() + "-" + mon + "-" + day + "T23:59:00";

                    console.log(start);


                    $('.delete_event').removeClass('hidden');
                    $('.update_event').removeClass('hidden');

                    $('#delete_event').val(id);
                    $('#update_event').val(id);
                    $('#editid').val(id);
                    // $('#eventDetails').modal('toggle');


                    view_event(id, start, end);

                },
                eventResize: function(info) { //Pag mag resize sa event time

                    var id = info.event.id;
                    var start = info.event.startStr;
                    var end = info.event.endStr;

                    $.ajax({

                        url: '{{ route('update.event') }}',
                        type: "GET",
                        data: {

                            id: id,
                            start: start,
                            end: end,
                        },
                        success: function(data) {

                            weeklist.refetchEvents();
                            daylist.refetchEvents();
                        }
                    });
                },


            });


            weeklist.render();
            weeklist2.render();
            daylist.render();
            daylist2.render();
            calendar.render();


            //Manual change sa css/add class sa calendar
            $('.fc-toolbar').addClass('d-flex flex-md-row flex-column')
            $('.fc-today-button').attr('title', 'Go to Today').addClass('btn-sm')
            $('.fc-prev-button').addClass('btn-sm')
            $('.fc-next-button').addClass('btn-sm')

            $('.fc-export-button').addClass('btn-sm mt-3 bg-danger border-danger export')

            $('.fc-toolbar').css('margin', '0')
            $('.fc-toolbar').css('padding-top', '0')
            $('.fc-toolbar').css('font-size', '12px')
            $('.fc-list-event').css('cusor', 'pointer')
            $('#weekList').css('font-size', '12px')
            $('#dayList').css('font-size', '12px')

            $('.fc-pdf-button').attr('formtarget', '_blank')
            $('.fc-excel-button').attr('formtarget', '_blank')



        }



        /////////////SWEET ALERT///////////////
        function notify(code, message) {
            Swal.fire({
                type: code,
                title: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
            });

        }
    </script>
@endsection
