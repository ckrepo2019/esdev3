@php
    $check_refid = DB::table('usertype')
        ->where('id', Session::get('currentPortal'))
        ->select('refid', 'resourcepath')
        ->first();

    if (Session::get('currentPortal') == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (Session::get('currentPortal') == 3) {
        $extend = 'registrar.layouts.app';
    } elseif (Session::get('currentPortal') == 8) {
        $extend = 'admission.layouts.app2';
    } elseif (Session::get('currentPortal') == 1) {
        $extend = 'teacher.layouts.app';
    } elseif (Session::get('currentPortal') == 2) {
        $extend = 'principalsportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 4) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 6) {
        $extend = 'adminPortal.layouts.app2';
    } elseif (Session::get('currentPortal') == 15) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 18) {
        $extend = 'ctportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 10) {
        $extend = 'hr.layouts.app';
    } elseif (Session::get('currentPortal') == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } elseif (auth()->user()->type == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } else {
        if (isset($check_refid->refid)) {
            if ($check_refid->resourcepath == null) {
                $extend = 'general.defaultportal.layouts.app';
            } elseif ($check_refid->refid == 27) {
                $extend = 'academiccoor.layouts.app2';
            } elseif ($check_refid->refid == 22) {
                $extend = 'principalcoor.layouts.app2';
            } elseif ($check_refid->refid == 29) {
                $extend = 'idmanagement.layouts.app2';
            } elseif ($check_refid->refid == 23) {
                $extend = 'clinic.index';
            } elseif ($check_refid->refid == 24) {
                $extend = 'clinic_nurse.index';
            } elseif ($check_refid->refid == 25) {
                $extend = 'clinic_doctor.index';
            } elseif ($check_refid->refid == 31) {
                $extend = 'guidanceV2.layouts.app2';
            } elseif ($check_refid->refid == 33) {
                $extend = 'inventory.layouts.app2';
            } else {
                $extend = 'general.defaultportal.layouts.app';
            }
        } else {
            $extend = 'general.defaultportal.layouts.app';
        }
    }
@endphp
@extends($extend)

{{-- @extends('deanportal.layouts.app2') --}}

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">


    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .font-20 {
            font-size: 12px !important
        }

        /* body {
                                        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif
                                    } */

        .modal-backdrop-dark {
            background-color: rgba(0, 0, 0, 0.5) !important;
        }

        .display-none {
            display: none;
        }
        .tooltip-inner {
            max-width: 400px; /* Set your desired width (e.g., 400px) */
            width: auto; /* Ensures flexibility with smaller content */
            word-wrap: break-word; /* Prevents content overflow */
        }
    </style>
@endsection



@section('content')
    @php
        $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
        $semester = DB::table('semester')->get();
        $user = auth()->user()->id;
        $levelname = DB::table('college_year')->get();
        
        $type = auth()->user()->type;
        if ($type != 3) {
            $collegeid = DB::table('teacher')
                ->join('teacherdean', 'teacherdean.teacherid', '=', 'teacher.id')
                ->where('teacher.userid', $user)
                ->where('teacher.deleted', 0)
                ->where('teacherdean.deleted', 0)
                ->pluck('teacherdean.collegeid')
                ->toArray();
            $course = DB::table('college_courses')
                ->where('deleted', 0)
                ->whereIn('collegeid', $collegeid)
                ->get();
        } else {
            $course = DB::table('college_courses')->where('deleted', 0)->get();
        }

        $subjects = DB::table('college_prospectus')->where('deleted', 0)->get();
        $rooms = DB::table('rooms')->where('deleted', 0)->get();
        $buildings = DB::table('building')->where('deleted', 0)->get();
        $yearlevel = DB::table('college_year')->where('deleted', 0)->select('levelid as id', 'yearDesc as text')->get();

        foreach ($yearlevel as $item) {
            $item->text = str_replace(' COLLEGE', '', $item->text);
        }

        $yearlevel2 = DB::table('gradelevel')
            ->where('acadprogid', 8)
            ->where('deleted', 0)
            ->select('id', 'levelname as text')
            ->get();

        $gradelevel1  = DB::table('gradelevel')
            ->where('acadprogid', 6)
            ->where('deleted', 0)
            ->select('id', 'levelname as text')
            ->get();

        $gradelevel2  = DB::table('gradelevel')
            ->where('acadprogid', 8)
            ->where('deleted', 0)
            ->select('id', 'levelname as text')
            ->get();    
       
        $grading_template = DB::table('college_ecr')
                            ->where('deleted', 0)
                            ->where('is_active', 1)
                            ->select('id', 'ecrDesc as name')
                            ->get();
        

    @endphp

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Section Setup</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active font-weight-bold">Section Setup</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content pt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="info-box shadow">
                                {{-- <span class="info-box-icon bg-primary"><i class="fas fa-calendar-check"></i></span> --}}
                                <div class="info-box-content">
                                    <div class="row pb-2 d-flex">
                                        <div class="col-md-2 col-sm-12">
                                            <label for="">School Year</label>
                                            <select class="form-control form-control-sm select2" id="syid" style="width: 100%;">
                                                @foreach ($sy as $item)
                                                    @if ($item->isactive == 1)
                                                        <option value="{{ $item->id }}" selected="selected">
                                                            {{ $item->sydesc }}</option>
                                                    @else
                                                        <option value="{{ $item->id }}">{{ $item->sydesc }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <label for="">Semester</label>
                                            <select class="form-control form-control-sm select2" id="semester" style="width: 100%;">
                                                @foreach ($semester as $item)
                                                    <option {{ $item->isactive == 1 ? 'selected' : '' }}
                                                        value="{{ $item->id }}">{{ $item->semester }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <label for="">Course</label>
                                            <select class="form-control form-control-sm select2 course" id="course" style="width: 100%;">
                                                <option value="">All</option>
                                                @foreach ($course as $item)
                                                    <option value="{{ $item->id }}"
                                                        style="word-wrap: break-word!important">{{ $item->courseDesc }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <label for="">Academic Level</label>
                                            <select class="form-control form-control-sm select2 academic" id="academic" style="width: 100%;">
                                                <option value="">All</option>
                                                {{-- @foreach ($levelname as $item)
                                                    <option value="{{ $item->levelid }}">{{ $item->yearDesc }}</option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                                        <div class="col-md-2">
                                                                <button class="btn btn-info btn-block btn-sm" id="filter_sched"><i class="fas fa-filter"></i> Filter</button>
                                                        </div>
                                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header  bg-primary">
                            <h3 class="card-title"><i class="fas fa-clipboard-list"></i> College Sections</h3>
    
                        </div>
    
                        <div class="card-body p-2 ">
                            <div class="mt-2 p-2 d-flex flex-row">
                                <div class="">
                                    <button class="btn btn-success btn-sm" id="section_button" data-toggle="modal"
                                        data-target="#section">Add Section/Block</button>
                                </div>
                                <div class="ml-2">
                                    <button class="btn btn-primary btn-sm" id="section_list" data-toggle="modal"
                                        data-target="#section_list_modal">Section List</button>
                                </div>
                            </div>
                            <div class="row px-3">
                                <div class="col-md-12" style="font-size:.8rem">
                                    <table class="table table-sm table-striped display table-bordered table-responsive-sm"
                                        id="section_table" width="100%">
                                        <thead>
                                            <tr class="font-20">
                                                <th>Section/Block Name</th>
                                                <th>Course</th>
                                                <th class="text-center">Academic Level</th>
                                                <th class="text-center">Schedule Setup</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table_1">
    
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="section" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="card-header  bg-primary">
                        <h5 class="card-title mb-0" id="exampleModalLabel">Add Section/Block</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-header">

                    </div>
                    <div class="modal-body">
                        <div class="my-1">
                            <label for="" class="text-sm">Section/Block Name</label>
                            <input type="text" class="form-control form-control-sm" id="section_name" required>
                        </div>
                        <div class="my-1">
                            <label for="" class="text-sm">Course</label>
                            <select class="form-control form-control-sm select2 " id="course_modal">
                                <option value="">Select Course</option>
                                @foreach ($course as $item)
                                    @if ($item->deleted == 0)
                                        <option value="{{ $item->id }}" data-id="{{ $item->courseabrv }}" style="word-wrap: break-word!important">
                                            {{ $item->courseDesc }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="my-1">
                            <label for="" class="text-sm">Academic Level</label>
                            <select class="form-control form-control-sm select2 " id="academic_modal">
                                <option value="">Select Level</option>
                                {{-- @foreach ($levelname as $item)
                                        <option value="{{ $item->levelid }}">{{ $item->yearDesc }}</option>
                                    @endforeach --}}
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-sm btn-primary addsections1"
                            id="_add_sections">Save</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="schedule" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl " role="document">
                <div class="modal-content" style="height: 640px!important">
                    <div class="card-header  bg-primary">
                        <h5 class="card-title mb-0" id="schedule_modal_title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mx-2">
                            <div class="col-2">
                                <button class="btn btn-sm btn-primary" style="white-space: nowrap"
                                    id="load_prospectus_subject" data-toggle="modal" data-target="#prospectus_modal">Load
                                    Prospectus Subject</button>
                            </div>
                            <div class="col-2">
                                <button class="btn btn-sm btn-success" style="white-space: nowrap" data-toggle="modal"
                                    data-target="#subject_schedulemodal" id="subject_schedule">Add Subject
                                    Schedule</button>
                            </div>
                        </div>
                        <div class="table-responsive mt-2" style="max-height: 500px!important">
                            <table class="table table-sm display table-bordered" id="section_table" width="100%">
                                <thead>
                                    <tr class="font-20" style="font-size: 12px!important">
                                        <th>Code</th>
                                        <th>Subject</th>
                                        <th class="text-center">Lec</th>
                                        <th class="text-center">Lab</th>
                                        <th class="text-center">Cr. Unit</th>
                                        <th>Class</th>
                                        <th>Time Schedule</th>
                                        <th>Instructor</th>
                                        <th>Room</th>
                                        <th>Capacity</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="sched_table_1">

                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade " id="subject_schedulemodal" data-backdrop="static" tabindex="-2" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl " role="document">
                <div class="modal-content">
                    <div class="card-header  bg-primary">
                        <h5 class="card-title mb-0" id="">Add Subject Schedule Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class=" form-group">
                            <label for="">Curriculum</label>
                            <select width="100%" name="" id="curriculum_title2" class=" form-control select2 curriculum_select">
                            </select>
                        </div>
                        <div class="row border p-2">
                            <div class="col-sm-8 p-2">
                                <div class="my-1 ">
                                    <label for="" class="mb-0 text-sm" id="">Subjects</label>
                                    <select class="form-control form-control-sm modal_select2 " id="subjects">
                                        <option value="">Select Subject</option>
                                        {{-- @foreach ($subjects as $item)
                                            <option value="{{ $item->id }}">{{ $item->subjCode }}
                                                {{ $item->subjDesc }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-4">
                                        <label for="" class="mb-0 text-sm">Building</label>
                                        <select class="form-control form-control-sm modal_select2" id="buildingadd">
                                            <option value="">Select</option>
                                            <option value="add1">Add Building</option>
                                            @foreach ($buildings as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->description }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-sm-4">
                                        <label for="" class="mb-0 text-sm">Room</label>
                                        <select class="form-control form-control-sm modal_select2 " id="room">
                                            <option value="">Select</option>
                                            <option value="add"><i class="fa fa-plus"></i> Add Room</option>
                                            {{-- @foreach ($rooms as $item)
                                                <option value="{{ $item->id }}" data-id="{{ $item->capacity }}">
                                                    {{ $item->roomname }}</option>
                                            @endforeach --}}
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="" class="mb-0 text-sm">Subject Capacity</label>
                                        <input type="number" min="0" class="form-control form-control-sm"
                                            id="sched_capacity">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 p-2">
                                <div class="my-1 ">
                                    <label for="" class="mb-0 text-sm">Subject Code</label>
                                    <input type="text" class="form-control form-control-sm" disabled
                                        id="subject_code">
                                </div>
                                <div class="my-1 ">
                                    <div class="row" class="">
                                        <div class="col-4 mt-2 text-center" >
                                            <p for="" class=" mb-0 text-center font-weight-bold" style="font-size: 0.7rem">Lecture Units</p>
                                            <p class=" mb-0" id="subject_lec" style="font-size: 0.7rem" ></p>
                                        </div>
                                        <div class="col-4 mt-2 text-center">
                                            <p for="" class="mb-0  font-weight-bold" style="font-size: 0.7rem">Laboratory Units</p>
                                            <p class=" mb-0" id="subject_lab" style="font-size: 0.7rem"></p>
                                        </div>
                                        <div class="col-4 mt-2 text-center ">
                                            <p for="" class="mb-0  font-weight-bold" style="font-size: 0.7rem">Credited Units</p>
                                            <p class=" mb-0" id="subject_cred" style="font-size: 0.7rem"></p>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="row mt-4 p-2">
                            <div class="col-2">
                                <div class="form-check">
                                    <input class="form-check-input " type="radio" name="leclab" id="Lecture"
                                        value="Lecture" checked>
                                    <label for="Lecture" class="mb-0 text-sm">Lecture</label>
                                </div>

                            </div>
                            <div class="col-2">
                                <div class="form-check">
                                    <input class="form-check-input " type="radio" name="leclab" id="Laboratory"
                                        value="Laboratory">
                                    <label for="Laboratory" class="mb-0 text-sm">Laboratory</label>
                                </div>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-sm-6">
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <div class="">
                                            <label for="" class="mb-0 text-sm">Start Time</label>
                                            <input type="time" class="form-control form-control-sm" value="07:00"
                                                id="stime">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="">
                                            <label for="etime" class="mb-0 text-sm">End Time</label>
                                            <input type="time" class="form-control form-control-sm" min="07:00:00"
                                             id="etime">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex flex-column">
                                    <div class="form-group">
                                        <div class="row text-center">
                                            <div class="col">
                                                <label for="Mon" class="text-sm">Mon</label>
                                                <div>
                                                    <input type="checkbox" class="day" id="Mon" value="1">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="Tue" class="text-sm">Tue</label>
                                                <div>
                                                    <input type="checkbox" class="day" id="Tue" value="2">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="Wed" class="text-sm">Wed</label>
                                                <div>
                                                    <input type="checkbox" class="day" id="Wed" value="3">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="Thu" class="text-sm">Thu</label>
                                                <div>
                                                    <input type="checkbox" class="day" id="Thu" value="4">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="Fri" class="text-sm">Fri</label>
                                                <div>
                                                    <input type="checkbox" class="day" id="Fri" value="5">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="Sat" class="text-sm">Sat</label>
                                                <div>
                                                    <input type="checkbox" class="day" id="Sat" value="6">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="Sun" class="text-sm">Sun</label>
                                                <div>
                                                    <input type="checkbox" class="day" id="Sun" value="7">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div id="add_instructor_div" class="">
                            <button class="btn btn-sm  text-primary" id="add_instructor_button">+ Add Instructor</button>
                        </div>
                        <hr>
                        <div class="mt-3  p-2 ">
                            <div>
                                    <div class="row">
                                        <div  class="col-5">
                                        </div>
                                        <div class="col-2">

                                        </div>
                                        <div class="col-5">
                                                <label for="" class="mb-0 text-sm">Grading Template</label>
                                                <select class="form-control form-control-sm select2 " id="grading_template">
                                                    <option value="">Select Grading Template</option>
                                                    @foreach ($grading_template as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                    </div>
                            </div>
                            <div class="w-100 text-center mt-3">
                                <button class="btn btn-primary btn-sm w-50 save_subject_schedule"
                                    id="subject_create">Create</button>
                            </div>
                            <div class="w-100 text-center mt-3">
                                <button class="btn btn-success btn-sm w-50 d-none save_subject_schedule"
                                    id="subject_save">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="conflict_modal" data-backdrop="static" tabindex="-3" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="card-header  bg-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="">
                        <h5 class="text-center text-danger font-weight-bold mb-0"><i
                                class="fa fa-exclamation-triangle"></i>
                            Conflict of Schedules <span class="type_of_conflict"></span></h5>
                        <div class="mt-4 text-center"><span class="font-weight-bold conflicted_subject"
                                style="text-decoration: underline;"></span> is in conflict with other schedules:
                        </div>
                        <div style="height: 300px; overflow-y: auto;" id="conflict_modal_body">

                        </div>


                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary " id="proceed_conflict">Proceed Conflict</button>
                        <button class="btn btn-sm btn-danger" data-dismiss="modal" id="">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="conflict_modal_instructor" data-backdrop="static" tabindex="-3" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="card-header  bg-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="">
                        <h5 class="text-center text-danger font-weight-bold mb-0"><i
                                class="fa fa-exclamation-triangle"></i>
                            Conflict of Instructor Schedules <span class="type_of_conflict"></span></h5>
                        <div class="mt-4 text-center"><span class="font-weight-bold conflicted_subject"
                                style="text-decoration: underline;"></span> is in conflict with other schedules:
                        </div>

                        <div style="height: 300px; overflow-y: auto;" id="conflict_modal_body_instructor">

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary " id="proceed_conflict">Proceed Conflict</button>
                        <button class="btn btn-sm btn-danger" data-dismiss="modal" id="">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="section_list_modal" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="card-header  bg-primary">
                        <h5 class="card-title mb-0" id="exampleModalLabel">Section List</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-header">

                    </div>
                    <div class="modal-body">
                        <div class="d-flex flex-row w-50 p-1">
                            <div class="w-50">
                                <select class="form-control form-control-sm select2 w-100" id="syid_sectionlist">
                                    @foreach ($sy as $item)
                                        @if ($item->isactive == 1)
                                            <option value="{{ $item->id }}" selected="selected">{{ $item->sydesc }}
                                            </option>
                                        @else
                                            <option value="{{ $item->id }}">{{ $item->sydesc }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-50 ml-2">
                                <select class="form-control form-control-sm select2" id="semester_sectionlist">
                                    @foreach ($semester as $item)
                                        <option {{ $item->isactive == 1 ? 'selected' : '' }} value="{{ $item->id }}">
                                            {{ $item->semester }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="px-2 my-1">
                            <p class="text-danger mb-0" style="font-size: 12px">Select School Year & Semester to Display
                                Section List</p>
                        </div>
                        <table
                            class="table table-sm table-striped  table-bordered table-responsive-sm font-weight-bold w-100"
                            style="font-size:.8rem" id="section_list_table" width="100%">
                            <thead>
                                <tr>
                                    <th width="25%">Section</th>
                                    <th width="75%">Course</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center w-100 p-2">
                        <p class="text-danger mb-0" style="font-size: 15px" id="section_list_text">Cannot Copy Sections
                            to the Same School Year and Semester</p>
                        <button class="btn btn-sm btn-success w-50 d-none" id="add_sectionlist">Copy Sections</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="loadingModal" data-backdrop="static" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: rgba(0, 0, 0, 0.6); border: none;">
                    <div class="modal-body text-center">
                        <h2 class="mt-3 text-light">Loading...</h2>

                        <div div style="font-size:3rem" class="spinner-border text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="prospectus_modal" data-backdrop="static" style="display: none;z-index: 1054;"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="card-header  bg-primary">
                        <h5 class="card-title mb-0" id="prospectus_modal_title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-header pb-2 pt-2 border-0">
                        <div class="row w-100">
                            <div class="col-md-4 form-group">
                                <label for="">Curriculum</label>
                                <select name="" id="curriculum_title" class=" form-control form-control-sm select2 curriculum_select">
                                </select>
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="">Grade Level</label>
                                <select name="" id="gradelevel_prospectus" class=" form-control form-control-sm select2">

                                </select>
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="">Semester</label>
                                <select name="" id="semester_prospectus" class=" form-control form-control-sm select2">
                                    <option value="0">All</option>
                                    @foreach ($semester as $item)
                                        <option value="{{ $item->id }}">{{ $item->semester }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="row table-responsive " style="height: 450px;" id="prospectus_tables">

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal_1" data-backdrop="static" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header pb-2 pt-2 border-0 bg-dark">
                        <h4 class="modal-title">
                            <span class="mt-1" id="sectionNameHeader"></span>
                        </h4>
                        <div class="d-flex align-items-center ml-auto">
                            <a class="btn btn-primary btn-sm ml-2" id="view_pdf" >
                                <i class="far fa-file-pdf mr-1"></i> PRINT PDF
                            </a>
                            <button type="button" class="close pb-2" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                    {{-- <div class="container-fluid headerText p-3">
                        <div class="row py-3">
                            <!-- Teacher -->
                            <div class="col-md-3">
                                <div class="d-flex flex-column text-center">
                                    <span class="font-weight-bold h6"><i class="fas fa-chalkboard-teacher"></i> Teacher</span>
                                    <span id="teacherName">{{ auth()->user()->name }}</span>
                                    <a class="text-primary" id="teacherID">{{ auth()->user()->email }}</a>
                                </div>
                            </div>
                            <!-- Subject -->
                            <div class="col-md-3">
                                <div class="d-flex flex-column text-center">
                                    <span class="font-weight-bold h6"><i class="fas fa-book"></i> Subject</span>
                                    <span id="subjectDesc"></span>
                                    <a class="text-primary" id="subjectCode"></a>
                                </div>
                            </div>
                            <!-- Level -->
                            <div class="col-md-3">
                                <div class="d-flex flex-column text-center">
                                    <span class="font-weight-bold h6"><i class="fas fa-graduation-cap"></i> Level</span>
                                    <span id="collegeLevel"></span>
                                </div>
                            </div>
                            <!-- Section -->
                            <div class="col-md-3">
                                <div class="d-flex flex-column text-center">
                                    <span class="font-weight-bold h6"><i class="fas fa-building"></i> Section</span>
                                    <span id="section"></span>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="modal-body" style="font-size:.8rem;height: 450px; overflow-y: auto;">
                        <table class="table table-sm table-striped" width="100%">
                            <thead>
                                <tr id="enrolled_datatable_table">
                                    <th width="10%">Student ID No.</th>
                                    <th width="15%" class="text-center">
                                        <div class="d-flex flex-row justify-content-between">
                                            <p class="mb-0">Student Name</p>
                                            <a href="#" id="sort"><i class="fa fa-sort" style="color: black"></i></a>
                                        </div>
                                    </th>
                                    <th width="10%">Academic Level</th>
                                    <th width="12%">Course</th>
                                    <th width="10%">Contact No.</th>
                                    <th width="10%">Email Address</th>
                                    <th width="auto">Address</th>
                                </tr>
                            </thead>
                            <tbody id="student_table">
                                <tr class="male_students">
                                    <td colspan="7" class="font-weight-bold male_sort_arrow" style="background-color: #8ec9fd">Male <a
                                            href="#" id="male_sort"><i class="fa fa-sort" style="color: black"></td>
                                </tr>
                                <tr class="female_students">
                                    <td colspan="7" class=" font-weight-bold female_sort_arrow" style="background-color: #fd8ec9">Female <a
                                            href="#" id="female_sort"><i class="fa fa-sort" style="color: black"></td>
    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addroom" data-backdrop="static" style="display: none;" aria-hidden="true">
            <div class=" modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header pb-2 pt-2 border-0 bg-primary">
                        <h6 class="modal-title">
                            <span class="mt-1" id="">Create Room</span>
                        </h6>

                            <button type="button" class="close pb-2" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        
                    </div>
                    
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Select Building</label>
                            <select name="" id="select_building" class=" form-control form-control-sm select2">
                                @foreach ($buildings as $item)
                                    <option value="{{ $item->id }}">{{ $item->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Add Room</label>
                            <input type="text" id="add_room" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label for="">Capacity</label>
                            <input type="number" min="0" id="room_capacity" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-success" id="add_room_button">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addbuilding" data-backdrop="static" style="display: none;" aria-hidden="true">
            <div class=" modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header pb-2 pt-2 border-0 bg-primary">
                        <h6 class="modal-title">
                            <span class="mt-1" id="">Create Building</span>
                        </h6>

                            <button type="button" class="close pb-2" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Add Building</label>
                            <input type="text" id="add_building" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label for="">Capacity</label>
                            <input type="number" min="0" id="building_capacity" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-success" id="add_building_button">Save</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('qab_sript')
        <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

        <script>
            $('#syid').select2()
            $('#semester').select2()
            $('#course_modal').select2()
            $('#academic_modal').select2()
            $('#course').select2()
            $('#academic').select2()
            $('#subjects').select2()
            $('#academicly').select2()
            $('#room').select2({
                placeholder: 'Select Room',
                allowClear: true
            })
            $('#buildingadd').select2({
                placeholder: 'Select Building',
                allowClear: true
            })
            
            
        </script>
        <script>
            var yearlevel = @json($yearlevel);
            var yearlevel2 = @json($yearlevel2);
            var semester_count = @json($semester);
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            });

            $(document).ready(function() {
                academic_select()
                function academic_select(){
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
                

                $('#course_modal').on('change', function() {
                    var value = $(this).val();
                    var abrv = $(this).find('option:selected').data('id');
                    $.ajax({
                        type: 'GET',
                        url: '/college/get/acadprogid',
                        data:{
                            course:value
                        },
                        success: function(data){
                            if(data.acadprogid == 6){
                                $('#academic_modal').empty()
                                $('#academic_modal').append(`
                                    <option value="">Select Level</option>
                                    @foreach ($gradelevel1 as $item)
                                        <option value="{{ $item->id }}">{{ $item->text }}</option>
                                    @endforeach
                                `)
                            }else{
                                $('#academic_modal').empty()
                                // $('#academic_modal').append(`
                                //     <option value="">Select Level</option>
                                //     @foreach ($gradelevel2 as $item)
                                //         <option value="{{ $item->id }}">{{ $item->text }}</option>
                                //     @endforeach
                                // `)
                                $('#academic_modal').append(`<option value="">Select Level</option>`)
                                $.each(yearlevel2, function(index, item) {
                                    var text = item.text.replace('HE', '').trim();
                                    text = abrv + ' ' + text;
                                    $('#academic_modal').append(`
                                        <option value="${item.id}">${text}</option>
                                    `);
                                });
                            }
                        }
                    })
                    
                })

                var syid = $('#syid').val();
                var semester = $('#semester').val();
                var gradelevel_prospectus = $('#gradelevel_prospectus').val();
                var semester_prospectus = $('#semester_prospectus').val();

                $('#gradelevel_prospectus').on('change', function() {
                    gradelevel_prospectus = $('#gradelevel_prospectus').val()
                    prospectus_subjects()
                })
                $('#semester_prospectus').on('change', function() {
                    semester_prospectus = $('#semester_prospectus').val()
                    prospectus_subjects()
                })
                $('#syid').on('change', function() {
                    get_sections()
                    syid = $('#syid').val()
                })

                $('#semester').on('change', function() {
                    get_sections()
                    semester = $('#semester').val()
                })

                $('#course').on('change', function() {
                    get_sections()
                    course = $('#course').val()
                })
                $('#academic').on('change', function() {
                    get_sections()
                })

                $(document).on('change', '.teacher_change', function() {

                    let parentDiv = $(this).closest('.instructor_count');

                    parentDiv.find('.terms').prop('checked', true);
                })

                function show_conflicts(subjcode, subjdesc, stime, etime, room, teacher, description) {
                    $('#conflict_modal_body').append(`
                              <table class="table table-sm display table-bordered conflict_table " id="" width="100%">
                                    <tr class="conflict_details font-weight-bold" style="font-size: 14px" >
                                          <td class="font-weight-bold" colspan="2">Conflict Details</td>
                                    </tr>
                                    <tr>
                                          <td width="20%">Subject:</td>
                                          <td class="font-weight-bold conflict_subject">${subjcode} - ${subjdesc}</td>
                                    </tr>
                                    <tr>
                                          <td>Time:</td>
                                          <td class="font-weight-bold conflict_subject">${stime} - ${etime}</td>
                                    </tr>
                                    <tr>
                                          <td>Room:</td>
                                          <td class="font-weight-bold conflict_subject">${room}</td>
                                    </tr>
                                    <tr>
                                          <td>Teacher:</td>
                                          <td class="font-weight-bold conflict_subject">${teacher ? teacher : 'N/A'}</td>
                                    </tr>
                                    <tr>
                                          <td>Day:</td>
                                          <td class="font-weight-bold conflict_subject">${description}</td>
                                    </tr>
                              </table>
                             
                              
                              
                        `)
                }

                function show_conflicts_instructor(subjcode, subjdesc, stime, etime, room, teacher, description,sectionDesc) {
                    $('#conflict_modal_body_instructor').append(`
                             
                              <table class="table table-sm display table-bordered conflict_table_instructor" id="" width="100%">
                                    <tr class="conflict_details font-weight-bold" style="font-size: 14px" >
                                          <td class="font-weight-bold" colspan="2">Conflict Details</td>
                                    </tr>
                                    <tr>
                                          <td width="20%">Subject:</td>
                                          <td class="font-weight-bold conflict_subject">${subjcode} - ${subjdesc}</td>
                                    </tr>
                                    <tr>
                                          <td>Time:</td>
                                          <td class="font-weight-bold conflict_subject">${stime} - ${etime}</td>
                                    </tr>
                                    <tr>
                                          <td>Teacher:</td>
                                          <td class="font-weight-bold conflict_subject">${teacher}</td>
                                    </tr>
                                    <tr>
                                          <td>Day:</td>
                                          <td class="font-weight-bold conflict_subject">${description}</td>
                                    </tr>
                                    <tr>
                                          <td>Section:</td>
                                          <td class="font-weight-bold conflict_subject">${sectionDesc}</td>
                                    </tr>
                              </table>
                              
                        `)
                }

                function add_instructor() {
                    if ($('.instructor_count').length < 4) {
                        $('#add_instructor_div').before(`
                                    <div class="mt-1 row added_div instructor_count ">
                                          <div  class="col-6">
                                                <label for="" class="mb-0 text-sm">Instructor</label>
                                                <select class="form-control form-control-sm modal_select2 teacher teacher_change" id="">

                                                </select>
                                          </div>
                                          <div class="col-5">
                                                <div class="form-group">
                                                      <div class="row text-center">
                                                            <div class="col">
                                                                  <label for="Mon" class="text-sm">PRELIM</label>
                                                                  <div>
                                                                        <input type="checkbox" class="terms prelim" id="" value="1">
                                                                  </div>
                                                            </div>
                                                            <div class="col">
                                                                  <label for="Mon" class="text-sm">MIDTERM</label>
                                                                  <div>
                                                                        <input type="checkbox" class="terms midterm" id="" value="2">
                                                                  </div>
                                                            </div>
                                                            <div class="col">
                                                                  <label for="Mon" class="text-sm">SEMIFINAL</label>
                                                                  <div>
                                                                        <input type="checkbox" class="terms semifinal" id="" value="3">
                                                                  </div>
                                                            </div>
                                                            <div class="col">
                                                                  <label for="Mon" class="text-sm">FINAL</label>
                                                                  <div>
                                                                        <input type="checkbox" class="terms final" id="" value="4">
                                                                  </div>
                                                            </div>
                                                            
                                                      </div>
                                                </div>
                                          </div>
                                          <div class="col-1 text-center">
                                                <button class="btn btn-sm btn-danger  remove_teacher mt-4"><i class="fas fa-times"></i></button>
                                          </div>
                                    </div>
                              `)
                    }

                }


                function show_section_sched(schedid, subjectid, code, subject, lec, lab, otherclass, time, days,
                    teacher, room, capacity,credunits, buildingname) {
                    sched_conflict(schedid)
                    get_student(schedid, capacity)
                    let existingRow = $('#sched_table_1').find(`tr[data-id="${subjectid}"]`);
                    if (existingRow.length > 0) {
                        // Get the first row with the same subjectid
                        let firstRow = existingRow.first();

                        // Increment the rowspan value
                        let currentRowspan = parseInt(firstRow.find('td.rowspan').attr('rowspan')) || 1;
                        firstRow.find('td.rowspan').attr('rowspan', currentRowspan + 1);

                        // Append a new row after the existing row
                        firstRow.after(`
                              <tr class="appended_row" style="font-size: 12px!important" data-id="${subjectid}">
                                    <td class="text-center align-middle p-2">${lec}</td>
                                    <td class="text-center align-middle p-2">${lab}</td>
                                    <td class="text-center align-middle p-2">${credunits ? credunits : ''}</td> 
                                    <td class="p-2">${otherclass ? otherclass : ''}</td>
                                    <td class="p-2 "> <div class="sched_conflict sched_conflict_${schedid}"> </div> ${time ? time : ''} -  ${days ? days : ''}</td>
                                    <td class="p-2 align-middle">${teacher ? teacher : 'No Assigned Instructor'}</td>
                                    <td class="p-2" style="white-space: nowrap">${room ? room : 'No Assigned Room'}<br><span style="font-size: 10px"><i>${buildingname ? buildingname : ''}<i></span></td>
                                    <td class="p-2 text-center"><a href="#" class="show_studs" data-toggle="modal" data-target="#modal_1" data-id="${schedid}" data-subject="${subject}">0/${capacity ? capacity : 0}</a></td>
                                    <td class="text-center p-2" style="white-space: nowrap">
                                    <button class="btn btn-sm btn-primary edit_sched" data-id="${schedid}" data-toggle="modal" data-target="#subject_schedulemodal"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger delete_sched" data-subject-id="${subjectid}"  data-id="${schedid}"><i class="fa fa-trash"></i></button>
                                    </td>
                              </tr>
                        `);
                    } else {
                        // If the row does not exist, create a new one with rowspan set to 1
                        $('#sched_table_1').append(`
                              <tr class="appended_row" style="font-size: 12px!important" data-id="${subjectid}">
                                    <td class="p-2 rowspan align-middle" data-id="${subjectid}" rowspan="1">${code}</td>
                                    <td class="p-2 rowspan align-middle" rowspan="1"><div class="d-flex flex-row justify-content-between">${subject} <a href="javascript:void(0)" class="add_subject_sched" data-toggle="modal" data-target="#subject_schedulemodal" data-id="${subjectid}"><i class="fas fa-calendar-plus" title="Add Schedule"></i></a></div></td>
                                    <td class="text-center align-middle p-2">${lec}</td>
                                    <td class="text-center align-middle p-2">${lab}</td>
                                    <td class="text-center p-2 align-middle">${credunits ? credunits : ''}</td> 
                                    <td class="p-2 align-middle">${otherclass ? otherclass : ''}</td>
                                    <td class="p-2 align-middle"><div class="sched_conflict sched_conflict_${schedid}"></div> ${time ? time : ''} -  ${days ? days : ''}</td>
                                    <td class="p-2 align-middle">${teacher ? teacher : 'No Assigned Instructor'}</td>
                                    <td class="p-2 align-middle" style="white-space: nowrap">${room ? room : 'No Assigned Room'}<br><span style="font-size: 10px"><i>${buildingname ? buildingname : ''}</td>
                                    <td class="p-2 align-middle text-center"><a href="#" class="show_studs" data-toggle="modal" data-target="#modal_1" data-id="${schedid}" data-subject="${subject}">0/${capacity ? capacity : 0}</a></td>
                                    <td class="text-center p-2" style="white-space: nowrap">
                                    <button class="btn btn-sm btn-primary edit_sched" data-id="${schedid}" data-toggle="modal" data-target="#subject_schedulemodal"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger delete_sched" data-subject-id="${subjectid}"  data-id="${schedid}"><i class="fa fa-trash"></i></button>
                                    </td>
                              </tr>
                        `);
                    }

                }


                function append_yearlevel() {
                    if(acadprogid == 8){
                        $('#prospectus_tables').append(
                        `
                            <div class="col-md-12 appended_yearlevel ">
                                @foreach ($yearlevel2 as $key => $item)
                                    @if ($key == 0)
                                        <hr class="div-holder div-{{ $item->id }} d-none mt-0" >
                                    @else
                                        <hr class="div-holder div-{{ $item->id }} d-none" >
                                    @endif
                                    @foreach ($semester as $sem_item)
                                        <div class="row div-holder d-none sy-{{ $item->id }} sem-{{ $sem_item->id }} div-{{ $item->id }}-{{ $sem_item->id }}"
                                            style="font-size:.8rem !important" >
                                            <div class="col-md-12">
                                                <table class="table-hover table table-striped table-sm table-bordered"
                                                    id="table-{{ $item->id }}-{{ $sem_item->id }}" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="10%" class="align-middle">Code</th>
                                                            <th width="40%">Subject Description</th>
                                                            <th width="25%">Prerequisite</th>
                                                            <th width="5%" class="align-middle text-center p-0">Lect.
                                                            </th>
                                                            <th width="5%" class="align-middle text-center p-0">Lab.
                                                            </th>
                                                            <th width="15%" class="align-middle text-center p-0">
                                                                Credited Units</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        `
                    )
                    }else{
                        $('#prospectus_tables').append(
                        `
                            <div class="col-md-12 appended_yearlevel">
                                @foreach ($yearlevel as $key => $item)
                                    @if ($key == 0)
                                        <hr class="div-holder div-{{ $item->id }} mt-0" >
                                    @else
                                        <hr class="div-holder div-{{ $item->id }}" >
                                    @endif
                                    @foreach ($semester as $sem_item)
                                        <div class="row div-holder sy-{{ $item->id }} sem-{{ $sem_item->id }} div-{{ $item->id }}-{{ $sem_item->id }}"
                                            style="font-size:.8rem !important" >
                                            <div class="col-md-12">
                                                <table class="table-hover table table-striped table-sm table-bordered"
                                                    id="table-{{ $item->id }}-{{ $sem_item->id }}" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="10%" class="align-middle">Code</th>
                                                            <th width="40%">Subject Description</th>
                                                            <th width="25%">Prerequisite</th>
                                                            <th width="5%" class="align-middle text-center p-0">Lect.
                                                            </th>
                                                            <th width="5%" class="align-middle text-center p-0">Lab.
                                                            </th>
                                                            <th width="15%" class="align-middle text-center p-0">
                                                                Credited Units</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        `
                    )
                    }
                    
                }

                function year_datatable(){
                    if (acadprogid == 8) {
                        var year = yearlevel2
                    } else {
                        var year = yearlevel
                    }
                    $('#gradelevel_prospectus').empty()
                    $('#gradelevel_prospectus').append('<option value="0">All</option>')
                    $("#gradelevel_prospectus").select2({
                        data: year,
                        templateResult: function(data) {
                            var $result = $("<span style='color: black;'></span>");
                            $result.text(data.text);

                            return $result;
                        }
                    }).on('select2:open', function() {
                        // Target the dropdown container and apply inline style
                        $('.select2-dropdown').attr('style', 'z-index: 1055 !important;');
                    });
                    $('#gradelevel_prospectus').val(gradelevel_prospectus).trigger('change.select2');
                    
                }

                var student;

                function show_students_function(studschedid) {
                    var syid = $('#syid').val()
                    var semid = $('#semester').val()
                    $.ajax({
                        type: 'get',
                        url: '/college/teacher/student-list-for-all/' + syid + '/' + semid + '/' + studschedid,
                        success: function(data) {
                            student = data.students
                            show_students()
                        }
                    })
                }
                var studschedid;
                $(document).on('click', '.show_studs', function(event) {
                    event.preventDefault();
                    toggle = 1;
                    studschedid = $(this).attr('data-id')
                    var subject = $(this).attr('data-subject')
                    $('#sectionNameHeader').text(sectionNameHeader + ' - ' + subject);
                    show_students_function(studschedid)
                })

                $(document).on('click','#view_pdf', function(){
                    var syid = $('#syid').val()
                    var semester = $('#semester').val()
                    

                    window.open('teacher/student-list/print/' + syid + '/' + semester + '/' + studschedid,);
                })

                function show_students() {
                    $('.appended_row_stud').remove(); // Remove previously appended rows
                    var students = student;

                    if (toggle == 1) {
                        // Sort students by lastname (assuming students have a 'lastname' field)
                        students.sort(function(a, b) {
                            if (a.lastname.toLowerCase() < b.lastname.toLowerCase()) {
                                return -1;
                            }
                            if (a.lastname.toLowerCase() > b.lastname.toLowerCase()) {
                                return 1;
                            }
                            return 0;
                        });

                        // Separate male and female rows before appending
                        let maleRows = '';
                        let femaleRows = '';

                        $.each(students, function(k, student) {
                            let row = `
                                <tr class="appended_row_stud ${student.gender === 'MALE' ? 'male_row' : 'female_row'}">
                                    <td>${student.sid}</td>
                                    <td>${student.student_name}</td>
                                    <td>${student.levelname}</td>
                                    <td>${student.courseabrv}</td>
                                    <td>${student.contactno ? student.contactno : ''}</td>
                                    <td>${student.semail ? student.semail : ''}</td>
                                    <td>${student.street} ${student.barangay} ${student.city}</td>
                                </tr>
                            `;

                            if (student.gender == "MALE") {
                                maleRows += row; // Accumulate male student rows
                                $(row).addClass('appended_row_stud_male');
                            } else if (student.gender == "FEMALE") {
                                femaleRows += row; // Accumulate female student rows
                                $(row).addClass('appended_row_stud_female');
                            }
                        });

                        // Insert rows after male and female sections
                        $('.male_students').after(maleRows);
                        $('.female_students').after(femaleRows);

                        $('.male_students').removeClass('display-none');
                        $('.female_students').removeClass('display-none');

                    } else if (toggle == 2) {
                        // If toggle is not 1, append all students to the main table
                        let allRows = '';

                        $.each(students, function(k, student) {
                            allRows += `
                                <tr class="appended_row_stud">
                                    <td>${student.sid}</td>
                                    <td>${student.student_name}</td>
                                    <td>${student.levelname}</td>
                                    <td>${student.courseabrv}</td>
                                    <td>${student.contactno ? student.contactno : ''}</td>
                                    <td>${student.semail ? student.semail : ''}</td>
                                    <td>${student.street} ${student.barangay} ${student.city}</td>
                                </tr>
                            `;
                        });

                        $('#student_table').append(allRows);
                        $('.male_students').addClass('display-none');
                        $('.female_students').addClass('display-none');
                    } else {
                        let allRows = '';

                        students.sort(function(a, b) {
                            if (a.lastname.toLowerCase() > b.lastname.toLowerCase()) {
                                return -1;
                            }
                            if (a.lastname.toLowerCase() < b.lastname.toLowerCase()) {
                                return 1;
                            }
                            return 0;
                        });

                        $.each(students, function(k, student) {
                            allRows += `
                                <tr class="appended_row_stud">
                                    <td>${student.sid}</td>
                                    <td>${student.student_name}</td>
                                    <td>${student.levelname}</td>
                                    <td>${student.courseabrv}</td>
                                    <td>${student.contactno ? student.contactno : ''}</td>
                                    <td>${student.semail ? student.semail : ''}</td>
                                    <td>${student.street} ${student.barangay} ${student.city}</td>
                                </tr>
                            `;
                        });

                        $('#student_table').append(allRows);
                        $('.male_students').addClass('display-none');
                        $('.female_students').addClass('display-none');
                    }
                }



                let maleSortOrder = 'asc'; // Track sorting order for male
                let femaleSortOrder = 'asc'; // Track sorting order for female

                // Event listener for male sorting arrow click
                $('.male_sort_arrow').on('click', function(e) {
                    e.preventDefault();
                    maleSortOrder = maleSortOrder === 'asc' ? 'desc' : 'asc';
                    sortAndDisplayMaleStudents(maleSortOrder);
                });

                // Event listener for female sorting arrow click
                $('.female_sort_arrow').on('click', function(e) {
                    e.preventDefault();
                    femaleSortOrder = femaleSortOrder === 'asc' ? 'desc' : 'asc';
                    sortAndDisplayFemaleStudents(femaleSortOrder);
                });

                function sortAndDisplayMaleStudents(sortOrder) {
                    // Filter students by gender (Male)
                    let maleStudents = student.filter(s => s.gender === "MALE");

                    // Sort based on the order
                    maleStudents.sort(function(a, b) {
                        if (sortOrder === 'asc') {
                            return a.lastname.toLowerCase() < b.lastname.toLowerCase() ? -1 : 1;
                        } else {
                            return a.lastname.toLowerCase() > b.lastname.toLowerCase() ? -1 : 1;
                        }
                    });

                    // Clear only the male rows
                    $('.male_row').remove();

                    // Rebuild male rows after sorting
                    let maleRows = '';
                    $.each(maleStudents, function(k, student) {
                        maleRows += `
                            <tr class="appended_row_stud male_row">
                                <td>${student.sid}</td>
                                <td>${student.student_name}</td>
                                <td>${student.levelname}</td>
                                <td>${student.courseabrv}</td>
                                <td>${student.contactno ? student.contactno : ''}</td>
                                <td>${student.semail ? student.semail : ''}</td>
                                <td>${student.street} ${student.barangay} ${student.city}</td>
                            </tr>
                        `;
                    });

                    // Append sorted male rows
                    $('.male_students').after(maleRows);
                }

                function sortAndDisplayFemaleStudents(sortOrder) {
                    // Filter students by gender (Female)
                    let femaleStudents = student.filter(s => s.gender === "FEMALE");

                    // Sort based on the order
                    femaleStudents.sort(function(a, b) {
                        if (sortOrder === 'asc') {
                            return a.lastname.toLowerCase() < b.lastname.toLowerCase() ? -1 : 1;
                        } else {
                            return a.lastname.toLowerCase() > b.lastname.toLowerCase() ? -1 : 1;
                        }
                    });

                    // Clear only the female rows
                    $('.female_row').remove();

                    // Rebuild female rows after sorting
                    let femaleRows = '';
                    $.each(femaleStudents, function(k, student) {
                        femaleRows += `
                            <tr class="appended_row_stud female_row">
                                <td>${student.sid}</td>
                                <td>${student.student_name}</td>
                                <td>${student.levelname}</td>
                                <td>${student.courseabrv}</td>
                                <td>${student.contactno ? student.contactno : ''}</td>
                                <td>${student.semail ? student.semail : ''}</td>
                                <td>${student.street} ${student.barangay} ${student.city}</td>
                            </tr>
                        `;
                    });

                    // Append sorted female rows
                    $('.female_students').after(femaleRows);
                }





                $(document).on('click', '#sort', function(e) {
                    e.preventDefault()
                    toggle = toggle == 0 ? 1 : toggle == 1 ? 2 : 0;
                    show_students()
                })


                $(document).on('click', '#add_instructor_button', function() {
                    add_instructor();
                    teacher_select2()
                })

                $(document).on('click', '.remove_teacher', function() {
                    $(this).closest('.instructor_count').remove()
                })

                function teacher_select2() {
                    $('.teacher').select2({
                        placeholder: "All",
                        allowClear: true,
                        ajax: {
                            url: '/college/subject/schedule/teachers',
                            data: function(params) {
                                var query = {
                                    search: params.term,
                                    page: params.page || 0
                                }
                                return query;
                            },
                            dataType: 'json',
                            processResults: function(data, params) {
                                params.page = params.page || 0;
                                return {
                                    results: data.results,
                                    pagination: {
                                        more: data.pagination.more
                                    }
                                };
                            }
                        }
                    });

                }


                teacher_select2();

                $('.save_subject_schedule, #proceed_conflict').on('click', function() {
                    $('#conflict_modal_instructor').modal('hide')
                    $('#conflict_modal').modal('hide')
                    var subject = $('#subjects').val()
                    var subjDesc = $('#subjects').find('option:selected').text()
                    var stime = $('#stime').val()
                    var etime = $('#etime').val()
                    var day = []
                    var instructors = []
                    var teachterm = []
                    var building = $('#building').val()
                    var room = $('#room').val()
                    var capacity = $('#sched_capacity').val()
                    var grade_template = $('#grading_template').val()
                    $('.day').each(function() {
                        if ($(this).prop('checked')) {
                            day.push($(this).val())
                        }
                    })
                    var instructor_count = $('.instructor_count').length

                    $.each($('.instructor_count'), function() {
                        var teach = $(this).find('.teacher').val() || null

                        if (instructor_count == 1 && teach == null) {
                            teachterm = 0
                            instructors = 0

                        } else if (instructor_count == 0) {
                            teachterm = 0
                            instructors = 0


                        } else {
                            instructors.push(teach)
                            var terms = []

                            $(this).find('.terms').each(function() {
                                if ($(this).prop('checked')) {
                                    terms.push($(this).val())
                                }
                            })

                            var teacher = []

                            teacher.push($(this).find('.teacher').val())




                            teachterm.push([teacher, terms])
                        }

                    })
                    var leclab = $('input[name="leclab"]:checked').val()
                    $.ajax({
                        type: 'get',
                        url: '/college/section/checksched/conflict',
                        data: {
                            instructor: instructors,
                            conflict: conflict,
                            schedid: schedid,
                            sectionid: id,
                            leclab: leclab,
                            syid: syid,
                            semester: semester,
                            subject: subject,
                            stime: stime,
                            etime: etime,
                            day: day,
                            building: building,
                            room: room,
                            teachterm: teachterm,
                            capacity: capacity,
                            grade_template: grade_template
                        },
                        success: function(data) {
                            console.log(data,'data')
                            if (data.length > 0 && data[0].teacherid) {
                                $('#conflict_modal_instructor').modal('show')
                                $('.conflicted_subject').text(subjDesc)
                                conflict = 2;
                                $.each(data, function(a, b) {
                                    show_conflicts_instructor(b.subjCode, b.subjDesc, b
                                        .stime, b.etime, b.roomname, b.teachername, b
                                        .description, b.sectionDesc)
                                })
                            } else if (data.length > 0) {
                                $('#conflict_modal_instructor').modal('hide')
                                $('#conflict_modal').modal('show')
                                conflict = 1;

                                $('.conflicted_subject').text(subjDesc)
                                $.each(data, function(a, b) {
                                    show_conflicts(b.subjCode, b.subjDesc, b.stime, b.etime,
                                        b.roomname, b.teachername, b.description)
                                })



                            } else {
                                $('#conflict_modal').modal('hide')
                                $('#conflict_modal_instructor').modal('hide')
                                $('#subject_schedulemodal').modal('hide')
                                $('.appended_row').remove()
                                Toast.fire({
                                    type: 'success',
                                    title: 'Scheduling Successful!'
                                })
                                get_sched();
                            }

                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    Toast.fire({
                                        type: 'error',
                                        title: value[0]
                                    });
                                });
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Something went wrong!',
                                    text: 'Please try again later.'
                                });
                            }
                        }
                    })
                })
                var subject_lab;
                $('#subjects').change(function() {
                    var code = $(this).val()

                    $.ajax({
                        type: 'get',
                        data: {
                            id: code
                        },
                        url: '/college/section/get/subjects/units',
                        success: function(data) {
                            $('#subject_desc').val(data.subjDesc)
                            $('#subject_lec').text(data.lecunits == null ? '' : data.lecunits)
                            $('#subject_lab').text(data.labunits == null ? '' : data.labunits)
                            $('#subject_cred').text(data.credunits == null ? '' : data.credunits)
                            $('#subject_code').val(data.subjCode)
                            if(data.labunits != 0.0){
                                $('#Laboratory').removeAttr('disabled')
                            }else{
                                $('#Laboratory').attr('disabled','disabled')
                            }
                        }
                    })
                })
                var building_val;
                $('#buildingadd').change(function() {
                    var val = $(this).find(':selected').val()
                    building_val = val
                    console.log(val,'value')
                    if(val == 'add1'){
                        console.log(val,'selected')
                        $('#addbuilding').modal('show')
                        $(this).val('').trigger('change');
                    }else{
                        get_rooms()
                    }
                })

                $('#room').change(function() {
                    var value = $('#room').find(':selected').data('id')
                    var val = $('#room').find(':selected').val()
                    if(val == 'add'){
                    console.log(val,'selected')
                        $('#addroom').modal('show')
                        $('#room').val('').trigger('change');
                    }else{
                        $('#sched_capacity').val(value)
                    }
                })
                
                $('#addroom').on('hidden.bs.modal', function (e) {
                    $('#room').val('').trigger('change');
                    $('#room_capacity').val('')
                })

                $('#addroom').on('show.bs.modal', function (e) {
                    $('#room').val('').trigger('change');
                    $('#room_capacity').val('')
                    $('#select_building').val(building_val).trigger('change');
                })

                $(document).on('click', '#add_building_button', function() {
                    var building = $('#add_building').val()
                    var capacity = $('#building_capacity').val()
                    $.ajax({
                        url: "/college/section/add/building",
                        type: "GET",
                        data: {
                            building: building,
                            capacity: capacity,
                        },
                        success: function(data) {
                            $('#add_building').val('')
                            $('#building_capacity').val('')
                            $('#buildingadd').append(`<option value="${data.id}">${data.description}</option>`)
                            $('#buildingadd').trigger('change')
                            $('#select_building').append(`<option value="${data.id}" data-id="${data.capacity}">${data.description}</option>`)
                            $('#select_building').trigger('change')

                            Toast.fire({
                                type: 'success',
                                title: 'Building Added!'
                            })
                        }
                    })
                })

                $(document).on('click', '#add_room_button', function() {
                    var room = $('#add_room').val()
                    var capacity = $('#room_capacity').val()
                    var buildingid = $('#select_building').val()
                    $.ajax({
                        url: "/college/section/add/room",
                        type: "GET",
                        data: {
                            room: room,
                            capacity: capacity,
                            buildingid: buildingid
                        },
                        success: function(data) {
                            get_rooms()
                            Toast.fire({
                                type: 'success',
                                title: 'Room Added!'
                            })
                        }
                    })
                })

                function get_rooms(){
                    var buildingid = $('#buildingadd').val()
                    $.ajax({
                        type: 'get',
                        url: '/college/section/get/rooms',
                        data: {
                            buildingid: buildingid
                        },
                        success: function(data) {
                            $('.room_values').remove()
                            $.each(data, function(index, room) {
                                $('#room').append(`<option class="room_values" value="${room.id}" data-id="${room.capacity}">${room.roomname}</option>`)
                            })
                            $('#room').trigger('change')
                        }
                    })
                }

                

                var syid = $('#syid').val()
                var semid = $('#semester').val()
                var sched_stud= []
               

                function get_student(schedid, capacity){       
                    $.ajax({
                        type: 'get',
                        url: '/college/teacher/student-list-for-all/' + syid + '/' + semid +
                            '/' + schedid,
                        success: function(data) {
                            var show_studs = $('.show_studs[data-id="' + schedid + '"]');
                            show_studs.text(data.studentCount + '/' + (capacity == null ? '0' : capacity));
                            
                            
                        }
                    })


                }

                var loadsubj = [];
                function get_sched() {

                    $.ajax({
                        type: 'get',
                        url: '/college/section/get/sched',
                        data: {
                            id: id
                        },
                        success: function(data) {
                            $.each(data, function(index, sched) {
                                var array = []
                                array.push(sched.schedid, sched.subjectid)
                                show_section_sched(sched.schedid, sched.subjectid, sched.subjCode, sched.subjDesc, sched.lecunits, sched.labunits,
                                sched.schedotherclass, sched.schedtime, sched.days, sched.instructorname, sched.roomname, sched.capacity,sched.credunits,sched.buildingname)
                                loadsubj.push(array)

                            })

                        }
                    })
                }

                function sched_conflict(schedid){
                    $.ajax({
                        type: 'get',
                        url: '/college/schedule/conflicting',
                        data: {
                            syid: syid,
                            semid: semid,
                            schedid: schedid
                        }, 
                        success: function(data) {
                            if(data.length > 0){
                                var conflict_str = '';
                                $.each(data, function(index, sched) {
                                    conflict_str += `${sched.sectionDesc} - ${sched.subjDesc}<br>`;
                                });

                                // Initialize tooltip with enforced top placement
                                var badge = $(`
                                    <div class="badge badge-danger" style="display:block;" data-html="true">
                                        Conflict
                                    </div>
                                `);
                                badge.tooltip({
                                    title: conflict_str,
                                    html: true,
                                    placement: 'top', // Force tooltip above the element
                                    boundary: 'window', // Ensure it doesn't clip if close to viewport edges
                                    fallbackPlacement: [] // Disable automatic fallback to other placements
                                });

                                // Append to the relevant element
                                $('.sched_conflict_' + schedid).append(badge);
                            }
                        } 
                    }) 
                        

                }

                $(document).on('hover', '.sched_conflict', function() {
                    
                })

                var gradelevel_prospectus;
                var course_id;
                var sectionNameHeader;
                var acadprogid;
                var courseabrv
                $(document).on('click', '#view_schedule', function() {
                    acadprogid = $(this).attr('data-acadprogid')
                    course_id = $(this).attr('data-course-id')
                    courseabrv = $(this).attr('data-course-abrv')
                    gradelevel_prospectus = $(this).attr('data-level-id')
                    sectionNameHeader = $(this).attr('data-section')
                    id = $(this).data('id')
                    get_sched()
                    get_curriculum();
                })



                $(document).on('click', '#edit_section', function() {
                    id = $(this).data('id')
                    $.ajax({
                        type: 'get',
                        data: {
                            id: id
                        },
                        url: '/college/section/get/section',
                        success: function(data) {
                            $('#section_name').val(data.sectionDesc)
                            $('#course_modal').val(data.courseID).trigger('change');
                            setTimeout(function() {
                                $('#academic_modal').val(data.yearID).trigger('change');
                            }, 500);
                        }
                    })

                })

                $(document).on('click', '#subject_schedule', function() {
                    schedid = 0;
                    $('#subjects').removeAttr('disabled', false)
                    $('#curriculum_title2').removeAttr('disabled')
                    $('#subject_save').addClass('d-none')
                    $('#subject_create').removeClass('d-none')

                })

                var subject_data;
                $(document).on('click', '.add_subject_sched', function() {
                    show_subj = 1
                    var id = $(this).data('id')
                    schedid = 0;
                    $('#subject_save').addClass('d-none')
                    $('#subject_create').removeClass('d-none')
                    $.ajax({
                        type: 'get',
                        url: '/college/section/get/specific/subject',
                        data: {
                            id: id
                        },
                        success: function(data) {
                            subject_edit.push(data.id,data.subjCode,data.subjDesc)
                            
                        }
                    })
                })
                var subject_edit = [];
                var show_subj;
                $(document).on('click', '.edit_sched', function() {
                    $('#subjects').attr('disabled', true)
                    $('#curriculum_title2').attr('disabled',true)

                    schedid = $(this).data('id')
                    $('#subject_create').addClass('d-none')
                    $('#subject_save').removeClass('d-none')
                    show_subj = 1
                    $.ajax({
                        type: 'get',
                        data: {
                            id: schedid
                        },
                        url: '/college/section/get/specificsched',
                        success: function(data) {
                            var teacher = data.teachers
                            subject_edit.push(data.subjectid,data.subjCode,data.subjDesc)
                            $('#subjects').val(data.subjectid).trigger('change')
                            if (data.schedotherclass == 'Lecture') {
                                $('#Lecture').prop('checked', true)
                            } else if (data.schedotherclass == 'Laboratory') {
                                $('#Laboratory').prop('checked', true)
                            }
                            $('#stime').val(data.stime == null ? '07:00' : data.stime)
                            $('#etime').val(data.etime)
                            if (data.days != null) {
                                var days = data.days.split('/')
                                $.each(days, function(a, b) {
                                    $('.day[id="' + b.trim() + '"]').prop('checked', true)
                                })
                            }

                            
                            
                            $('#grading_template').val(data.ecr_template).trigger('change')
                            var count = 0;
                            for (var i = 0; i < data.teachers.length - 1; i++) {
                                add_instructor()
                                teacher_select2()
                            }
                            $.each($('.instructor_count'), function() {
                                if (data.teachers != null || data.teachers.length != 0) {
                                    var parent = $(this)
                                    $(this).find('.teacher').empty()
                                    $(this).find('.teacher').attr('data-id', teacher[count]
                                        .instid)
                                    if (teacher[count].teachername == null) {
                                        $(this).find('.teacher').append(
                                            '<option value="" selected >Select Teacher</option>'
                                        )
                                    } else {
                                        $(this).find('.teacher').append('<option value="' +
                                            teacher[count].teacherID + '" >' + teacher[
                                                count].teachername + '</option>')
                                    }
                                    $.each(teacher[count].terms, function(a, b) {
                                        parent.find('.terms[value="' + b + '"]')
                                            .prop('checked', true)
                                    })
                                    count++;
                                }

                            })
                            setTimeout(function() {
                                $('#buildingadd').val(data.buildingid).trigger('change')
                            }, 800);

                            setTimeout(function() {
                                $('#room').val(data.roomid).trigger('change')
                                $('#sched_capacity').val(data.capacity)
                            }, 1500);


                        }
                    })

                })



                $(document).on('click', '#section_button', function() {
                    id = 0;
                })
                $(document).on('click', '.addsections1 ', function() {

                    var section_name = $('#section_name').val()
                    var course = $('#course_modal').val()
                    var academic = $('#academic_modal').val()

                    $.ajax({
                        type: 'get',
                        url: '/college/section/create',
                        data: {
                            id: id,
                            syid: syid,
                            semester: semester,
                            section_name: section_name,
                            course: course,
                            academic: academic
                        },
                        success: function(data) {
                            if (data == "Section Already Exists") {
                                Toast.fire({
                                    type: 'error',
                                    title: 'Section Already Exists',
                                })
                            } else {
                                $('#section').modal('hide');
                                get_sections();
                                Toast.fire({
                                    type: 'success',
                                    title: 'Successfully Created',
                                })
                            }

                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    Toast.fire({
                                        type: 'error',
                                        title: value[0]
                                    });
                                });
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Something went wrong!',
                                    text: 'Please try again later.'
                                });
                            }
                        }


                    })

                })


                // $(document).on('click','.add_section_list ',function(){

                //       var syid = $('#syid').val()
                //       var semester = $('#semester').val()
                //       var section_name = $(this).attr('data-sectionname')
                //       var course = $(this).attr('data-courseid')
                //       var academic = $(this).attr('data-yearid')
                //       $.ajax({
                //             type:'get',
                //             url: '/college/section/create',
                //             data:{
                //                   id : id,
                //                   syid : syid,
                //                   semester : semester,
                //                   section_name : section_name,
                //                   course : course,
                //                   academic : academic
                //             },
                //             success:function(data){
                //                   if(data == "Section Already Exists"){
                //                         Toast.fire({
                //                               type: 'error',
                //                               title: 'Section Already Exists',
                //                         })
                //                   }else{
                //                         $('#section').modal('hide');
                //                         get_sections();
                //                         Toast.fire({
                //                               type: 'success',
                //                               title: 'Successfully Created',
                //                         })
                //                   }

                //             },
                //             error:function(xhr){
                //                   if (xhr.status === 422) {
                //                         var errors = xhr.responseJSON.errors;
                //                         $.each(errors, function(key, value) {
                //                         Toast.fire({
                //                               type: 'error',
                //                               title: value[0] 
                //                         });
                //                         });
                //                   } else {
                //                         Toast.fire({
                //                               icon: 'error',
                //                               title: 'Something went wrong!',
                //                               text: 'Please try again later.'
                //                         });
                //                   }
                //             }


                //       })

                // })



                $(document).on('click', '#delete_section', function() {
                    var id = $(this).data('id')
                    Swal.fire({
                        title: 'Delete Section',
                        text: "Are you sure you want to delete this section?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: 'get',
                                url: '/college/section/delete',
                                data: {
                                    sectionid: id,
                                    sy: $('#syid').val()
                                },
                                success: function(data) {
                                    get_sections();
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Succesfully Deleted',
                                    })
                                },error:function(){
                                    Toast.fire({
                                        type: 'error',
                                        title: 'Section has Enrolled Students!'
                                    })
                                }
                            })
                        }
                    })
                })

                $(document).on('click', '.delete_sched', function() {
                    var schedid = $(this).data('id');
                    var subjectid = $(this).data('subjectid');
                    var row = $(this).closest('tr'); // Target the specific row clicked

                    Swal.fire({
                        title: 'Delete Schedule',
                        text: "Are you sure you want to delete this schedule?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: 'get',
                                url: '/college/section/delete/sched',
                                data: {
                                    schedid: schedid,
                                    sy: $('#syid').val()
                                },
                                success: function(data) {
                                        loadsubj = loadsubj.filter(subj => subj[0] !== schedid)
                                        var rowspanCell = row.find('td.rowspan');
                                        if (rowspanCell.length > 0) {
                                            let currentRowspan = parseInt(rowspanCell.attr(
                                                'rowspan')) || 1;

                                            // Find the next row with the same subject ID
                                            var nextRow = row.nextUntil(
                                                `tr[data-id="${subjectid}"]`).first();
                                            var nxtrowspanCell = nextRow.find('td.rowspan');
                                            if (nextRow.length > 0) {
                                                if (nxtrowspanCell.length > 0 && nxtrowspanCell
                                                    .length > 1) {
                                                    rowspanCell.remove();

                                                } else {
                                                    nextRow.prepend(
                                                        rowspanCell
                                                    ); // Move the rowspan cell to the next row
                                                    rowspanCell.attr('rowspan', currentRowspan -
                                                        1); // Decrease the rowspan count
                                                }

                                            } else {
                                                rowspanCell.remove();
                                            }
                                        } else {
                                            // If this row doesn't have a rowspan, find the closest previous row with a rowspan
                                            var rowspanRow = row.prevAll('tr').has('td.rowspan')
                                                .first();
                                            if (rowspanRow.length > 0) {
                                                // Decrease the rowspan value by 1
                                                var rowspanCell = rowspanRow.find('td.rowspan');
                                                let currentRowspan = parseInt(rowspanCell.attr(
                                                    'rowspan')) || 1;

                                                // Only decrease if the current rowspan is greater than 1
                                                if (currentRowspan > 1) {
                                                    rowspanCell.attr('rowspan', currentRowspan -
                                                        1);
                                                }
                                            }
                                        }

                                        // Remove the current row
                                        row.remove();
                                    

                                },error:function(){
                                    Toast.fire({
                                        type: 'error',
                                        title: 'Schedule has Enrolled Students!'
                                    })
                                }
                            });
                        }
                    });
                });
                var prospectus_courseID;
                var section_id;
                var dataCourse
                $(document).on('click', '#view_schedule', function() {
                    section_id = $(this).data('id')
                    $.ajax({
                        type: 'get',
                        data: {
                            id: section_id
                        },
                        url: '/college/section/get/section',
                        success: function(data) {
                            dataCourse = data.courseDesc + ' - ' + data.sectionDesc
                            $('#schedule_modal_title').text(data.courseDesc + ' - ' + data
                                .sectionDesc)
                            prospectus_courseID = data.courseID;
                        }
                    })
                })





                function get_sections() {
                    var syid = $('#syid').val()
                    var semester = $('#semester').val()
                    var course = $('#course').val()
                    var academic = $('#academic').val()
                    return $.ajax({
                        type: 'get',
                        url: '/college/section/get',
                        data: {
                            syid: syid,
                            semester: semester,
                            course: course,
                            academic: academic
                        },
                        success: function(data) {
                            sections_table(data)
                        }
                    })
                }

                get_sections();

                $(document).on('change', '#syid_sectionlist, #semester_sectionlist ', function() {
                    var syid_sectionlist = $('#syid_sectionlist').val()
                    var semid_sectionlist = $('#semester_sectionlist').val()
                    if (syid_sectionlist == syid && semid_sectionlist == semester) {
                        $('#add_sectionlist').addClass('d-none')
                        $('#section_list_text').removeClass('d-none')
                    } else {
                        $('#add_sectionlist').removeClass('d-none')
                        $('#section_list_text').addClass('d-none')
                    }
                    section_list_function()
                })

                $(document).on('click', '#add_sectionlist', function() {
                    var syid_sectionlist = $('#syid_sectionlist').val()
                    var semid_sectionlist = $('#semester_sectionlist').val()
                    var section_list = []
                    $.each($('.section_list_row'), function() {
                        var sectiondesc = $(this).find('.section_desc').data('id')
                        var course = $(this).find('.section_course').data('id')
                        var yearID = $(this).find('.section_desc').attr('data-level-id')

                        var section = {
                            sectiondesc: sectiondesc,
                            course: course,
                            yearID: yearID
                        }
                        section_list.push(section)
                    })
                    if (syid == syid_sectionlist && semester == semid_sectionlist) {
                        Toast.fire({
                            type: 'error',
                            title: 'Cannot Add Sections to the Same School Year & Semester',
                        })
                    } else if (section_list.length == 0) {
                        Toast.fire({
                            type: 'error',
                            title: 'No Sections to be Added',
                        })
                    } else {
                        $.ajax({
                            type: 'get',
                            url: '/college/section/copy/list',
                            data: {
                                syid: syid,
                                semester: semester,
                                section_list: section_list
                            },
                            success: function(data) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Sections Added to Current School Year',
                                })
                                get_sections()
                            }
                        })
                    }
                })

                function section_list_function(sections) {
                    var syid_sectionlist = $('#syid_sectionlist').val()
                    var sem_sectionlist = $('#semester_sectionlist').val()
                    $.ajax({
                        type: 'get',
                        url: '/college/section/get/list',
                        data: {
                            syid: syid_sectionlist,
                            semid: sem_sectionlist
                        },
                        success: function(data) {
                            sections_list_table(data)
                        }
                    })
                }
                $(document).on('click', '#section_list', function() {
                    $('#syid_sectionlist').select2()
                    $('#semester_sectionlist').select2()
                    section_list_function()
                })


                function curriculum_option() {
                    $.ajax({
                        type: 'get',
                        url: '/college/section/curriculum/option',
                        data: {
                            course: prospectus_courseID
                        },
                        success: function(data) {
                            $.each(data, function(key, curriculum) {
                                $('.curriculum_select').append(`<option value="${curriculum.id}">${curriculum
                                    .curriculumname}</option>`)
                            })
                            $('#curriculum_title').val(curr_id).trigger('change')
                            $('#curriculum_title2').val(curr_id).trigger('change')
                        }
                    })
                }

                $('#curriculum_title').on('change', function() {
                    if(curr_id == null){
                        Toast.fire({
                            type: 'warning',
                            title: 'No Curriculum Found',
                        })
                    }
                    curr_id = $(this).val()
                    get_prospectus()
                    
                })

                $('#curriculum_title2').on('change', function() {
                    $('#subjects').empty()
                    $('#subjects').append(`<option value="" selected>Select Subject</option>`).trigger('change')
                    var curri_id = $(this).val()
                    change_subjects(curri_id)
                })

                function change_subjects(curri_id){
                    $.ajax({
                        type: 'get',
                        url: '/college/section/get/subjects',
                        data: {
                            curriculum: curri_id,
                            course: prospectus_courseID
                        },
                        success: function(data) {
                            $.each(data, function(key, subject) {
                                if(subject.id != subject_edit[0]){
                                    $('#subjects').append(`<option value="${subject.id}">${subject.subjCode} ${subject
                                    .subjDesc}</option>`)
                                }
                            })
                            if(show_subj == 1){
                                $('#subjects').append(`<option value="${subject_edit[0]}" selected>${subject_edit[1]} ${subject_edit[2]}</option>`).trigger('change')
                            }
                        }
                    })
                }
                var prospectus_subject;

                function get_prospectus() {
                    $.ajax({
                        type: 'get',
                        url: '/setup/prospectus/courses/curriculum/subjects',
                        data: {
                            curriculumid: curr_id,
                            courseid: prospectus_courseID
                        },
                        success: function(data) {
                            var prereqSubjects = data[0].prereq.map(prereqItem => {
                                let matchedSubject = data[0].subjects.find(subject =>
                                    subject.id === prereqItem.prereqsubjID);
                                return matchedSubject ? matchedSubject.subjDesc : null;
                            });

                            $.each(data[0].subjects, function(key, subject) {
                                subject.prereq = data[0].prereq
                                    .filter(prereqItem => prereqItem.subjID === subject.id)
                                    .map(prereqItem => {
                                        let matchedPrereq = data[0].subjects.find(
                                            prereqSubj => prereqSubj.id ===
                                            prereqItem.prereqsubjID);
                                        return matchedPrereq ? matchedPrereq.subjDesc :
                                            null;
                                    });
                            });

                            prospectus_subject = data[0].subjects
                            prospectus_subjects();
                            $('#semester_prospectus').val(semester).trigger('change')

                        }
                    })
                }
                
                $(document).on('click', '#load_prospectus_subject', function() {

                    append_yearlevel()
                    curriculum_option()
                    year_datatable()
                    $('#prospectus_modal_title').text(dataCourse)
                    
                })
                var curr_name;
                var curr_id;

                function get_curriculum() {
                    $.ajax({
                        type: 'get',
                        url: '/college/section/get/curriculum',
                        data: {
                            course_id: course_id
                        },
                        success: function(data) {
                            curr_name = data.curriculumname
                            curr_id = data.id
                        }
                    })
                }

                $(document).on('click', '.load_prospectus_subject_button', function() {
                    $('#prospectus_modal').modal('hide')
                    var yearID = $(this).attr('data-yearlevel')
                    var semID = $(this).attr('data-sem')
                    var load_subj = prospectus_subject.filter(x => x.yearID == yearID && x.semesterID == semID);
                    var load_subj_ids = load_subj.map(x => x.id);
                    var existingSubj = []
                    var willadd = []
                    var loadsched = []
                    $.each(loadsubj, function(key, subj) {
                        loadsched.push(subj[1])
                        if(load_subj_ids.includes(subj[1])){
                            existingSubj.push(subj[1])
                        }
                    })
                    $.each(load_subj_ids, function(key, id) {
                        if (!existingSubj.includes(id)) {
                            willadd.push(id); 
                        }
                    });
                    if (load_subj_ids.every(subj => loadsched.includes(subj))) {
                        Toast.fire({
                            type: 'warning',
                            title: 'All subjects already added',
                        })
                    }else{
                        $.ajax({
                            type: 'get',
                            url: '/college/section/load/prospectus',
                            data: {
                                syid: syid,
                                semester: semester,
                                load_subj: willadd,
                                section_id: section_id,
                                current_curr: curr_id
                            },
                            success: function(data) {
                                var count = willadd.length
                                $('.appended_row').remove()
                                get_sched();
                                Toast.fire({
                                    type: 'success',
                                    title: count +' subject/s successfully added',
                                })
                                existingSubj = []
                                willadd = []
                                loadsubj = []
                                loadsched = []
                            }
                        })
                    }
                    
                    
                    
                })

                function prospectus_subjects() {
                    var gradelevel = gradelevel_prospectus
                    var semester = semester_prospectus
                    var yearlvl;
                    if(acadprogid == 8){
                        yearlvl = yearlevel2
                    } else {
                        yearlvl = yearlevel
                    }
                    $.each(yearlvl, function(key, year) {
                        $.each(semester_count, function(key, sem) {
                            $('.div-' + year.id).addClass('d-none')
                            $('.div-' + year.id + '-' + sem.id).addClass('d-none')

                            if (gradelevel == "0" && semester == "0") {
                                $('.div-' + year.id).removeClass('d-none')
                                $('.div-' + year.id + '-' + sem.id).removeClass('d-none')
                            } else if (gradelevel != 0 && semester != 0) {
                                $('.div-' + gradelevel).removeClass('d-none')
                                $('.div-' + gradelevel + '-' + semester).removeClass('d-none')
                            } else if (gradelevel != 0) {
                                $('.sy-' + gradelevel).removeClass('d-none')
                            } else if (semester != 0) {
                                $('.sem-' + semester).removeClass('d-none')
                            }
                            let filteredSubjects = prospectus_subject.filter(subject => subject
                                .yearID == year.id && subject.semesterID == sem.id);
                            $('#table-' + year.id + '-' + sem.id).DataTable({
                                destroy: true,
                                order: false,
                                data: filteredSubjects,
                                lengthChange: false,
                                info: false,
                                paging: false,
                                columns: [{
                                        data: 'subjCode'
                                    },
                                    {
                                        data: 'subjDesc'
                                    },
                                    {
                                        data: null
                                    },
                                    {
                                        data: 'lecunits'
                                    },
                                    {
                                        data: 'labunits'
                                    },
                                    {
                                        data: null
                                    },
                                ],
                                columnDefs: [{
                                        targets: 0,
                                        orderable: false,
                                        createdCell: function(td, cellData, rowData) {
                                            $(td).addClass('align-middle')
                                        }
                                    },
                                    {
                                        targets: 1,
                                        orderable: false,
                                        createdCell: function(td, cellData, rowData) {
                                            $(td).addClass('align-middle')
                                        }
                                    },

                                    {
                                        targets: 2,
                                        orderable: false,
                                        createdCell: function(td, cellData, rowData) {
                                            var prereqList = rowData.prereq ? rowData
                                                .prereq.map(function(prereq) {
                                                    return prereq
                                                }).join(', ') : '';

                                            $(td).text(prereqList).addClass(
                                                'align-middle');
                                        }
                                    },

                                    {
                                        targets: 3,
                                        orderable: false,
                                        createdCell: function(td, cellData, rowData) {
                                            $(td).addClass('align-middle text-center')
                                        }
                                    },

                                    {
                                        targets: 4,
                                        orderable: false,
                                        createdCell: function(td, cellData, rowData) {
                                            $(td).addClass('align-middle text-center')
                                        }
                                    },

                                    {
                                        targets: 5,
                                        orderable: false,
                                        createdCell: function(td, cellData, rowData) {
                                            $(td).text('')

                                            $(td).addClass('align-middle text-center')
                                        }
                                    },


                                ]
                            });
                            var label_text = $($("#table-" + year.id + '-' + sem.id + '_wrapper')[0].children[0])[0]
                            .children[0];
                            if (acadprogid == 8) {
                                newyear = year.text.replace('HE', '').trim();
                                $(label_text).html(
                                    '<div class="d-flex align-items-center col-md-8">' +
                                    '<label class="mb-0">'+ courseabrv + ' ' + newyear + ' - ' + sem.semester + '</label>' +
                                    '<a href="javascript:void(0)" ' +
                                    '   class="load_prospectus_subject_button btn btn-sm btn-primary ml-auto" ' +
                                    '   data-yearlevel="' + year.id + '" ' +
                                    '   data-sem="' + sem.id + '" ' +
                                    '   style="font-size: 12px; padding: 0.25rem 0.5rem; white-space: nowrap;"' +
                                    '   >' +
                                    '   <i class="fas fa-plus mr-1" style="color:white;"></i>' +
                                    '   <span style="color:white;">Load Subjects</span>' +
                                    '</a>' +
                                    '</div>'
                                );
                            }else {
                                $(label_text).html(
                                    '<div class="d-flex align-items-center col-md-8">' +
                                    '<label class="mb-0">' + year.text + ' - ' + sem.semester + '</label>' +
                                    '<a href="javascript:void(0)" ' +
                                    '   class="load_prospectus_subject_button btn btn-sm btn-primary ml-auto" ' +
                                    '   data-yearlevel="' + year.id + '" ' +
                                    '   data-sem="' + sem.id + '" ' +
                                    '   style="font-size: 12px; padding: 0.25rem 0.5rem; white-space: nowrap;"' +
                                    '   >' +
                                    '   <i class="fas fa-plus mr-1" style="color:white;"></i>' +
                                    '   <span style="color:white;">Load Subjects</span>' +
                                    '</a>' +
                                    '</div>'
                                );
                            }


                        })
                    })

                }

                function sections_table(sections) {
                    $('#section_table').DataTable({
                        destroy: true,
                        order: false,
                        data: sections,
                        columns: [{
                                data: 'sectionDesc'
                            },
                            {
                                data: 'courseDesc'
                            },
                            {
                                data: 'yearDesc'
                            },
                            {
                                data: null
                            },
                            {
                                data: null
                            },
                        ],
                        columnDefs: [{
                                targets: 0,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {

                                    $(td).html(
                                            `<p  class="section_link mb-0 ">${rowData.sectionDesc}</p>`)
                                        .addClass('align-middle fw-900')
                                        .css('vertical-align', 'middle');

                                }
                            },
                            {
                                targets: 1,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {

                                    $(td).html(
                                            `<p class="section_link mb-0 ">${rowData.courseDesc}</p>`)
                                        .addClass('align-middle')
                                        .css('vertical-align', 'middle');

                                }
                            },
                            {
                                targets: 2,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {

                                    if(rowData.yearID >= 17 && rowData.yearID <= 21){
                                        $(td).html(
                                            `
                                            <p  class="section_link mb-0 " data-id="">${rowData.yearDesc}</p>
                                            `
                                        )
                                        .addClass('align-middle text-center')
                                        .css('vertical-align', 'middle');
                                    }else{
                                        newyear = rowData.yearDesc.replace('HE', '').trim();

                                        $(td).html(
                                            `
                                            <p  class="section_link mb-0 " data-id="">${rowData.courseabrv} - ${newyear}</p>
                                            `
                                        )
                                        .addClass('align-middle text-center')
                                        .css('vertical-align', 'middle');
                                    }
                                   

                                }
                            },
                            {
                                targets: 3,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {

                                    $(td).html(
                                            `<a href="javascript:void(0)" class="section_link mb-0" data-section="${rowData.sectionDesc}" data-course-abrv="${rowData.courseabrv}" data-course-id="${rowData.courseID}" data-level-id="${rowData.yearID}" data-id="${rowData.id}" data-acadprogid="${rowData.acadprogid}" id="view_schedule" data-toggle="modal" data-target="#schedule">View Schedule</a>`
                                        )
                                        .addClass('align-middle text-center')
                                        .css('vertical-align', 'middle');

                                }
                            },
                            {
                                targets: 4,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {

                                    $(td).html(
                                            `<a href="javascript:void(0)" class="section_link mb-0" style="white-space: nowrap" data-id="">
                                                      <button class="btn btn-sm btn-primary" id="edit_section" data-toggle="modal" data-target="#section" data-id="${rowData.id}"><i class="fas fa-edit"></i></button>
                                                       <button class="btn btn-sm btn-danger" id="delete_section"  data-id="${rowData.id}"><i class="fas fa-trash"></i></button></a>`
                                        )
                                        .addClass('align-middle text-center')
                                        .css('vertical-align', 'middle');

                                }
                            },
                        ]
                    })
                }

                


                function sections_list_table(sections) {

                    $('#section_list_table').DataTable({
                        destroy: true,
                        order: false,
                        data: sections,
                        columns: [{
                                data: 'sectionDesc'
                            },
                            {
                                data: 'courseDesc'
                            },
                        ],
                        columnDefs: [{
                                targets: 0,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {

                                    $(td).html(
                                            `<p  class="section_link mb-0 section_desc" data-level-id="${rowData.yearID}" data-id="${rowData.sectionDesc}">${rowData.sectionDesc}</p>`
                                        )
                                        .addClass('align-middle fw-900')
                                        .css('vertical-align', 'middle');

                                }
                            },
                            {
                                targets: 1,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {

                                    $(td).html(
                                            `<p class="section_link mb-0 section_course" data-id="${rowData.courseID}">${rowData.courseDesc}</p>`
                                        )
                                        .addClass('align-middle')
                                        .css('vertical-align', 'middle');

                                }
                            },
                        ],
                        createdRow: function(row, data, index) {
                            $(row).addClass('section_list_row');
                        }
                    })
                }
                $('#section_list_modal').on('hidden.bs.modal', function(e) {
                    $('#syid_sectionlist').val($('#syid_sectionlist option[selected="selected"]').val())
                        .trigger('change');
                    $('#semester_sectionlist').val($('#syid_sectionlist option[selected="selected"]').val())
                        .trigger('change');
                })

                $('#section').on('hidden.bs.modal', function(e) {
                    $('#section_name').val('');
                    $('#course_modal').val('').trigger('change');
                    $('#academic_modal').val('').trigger('change');
                })

                $('#schedule').on('hidden.bs.modal', function(e) {
                    $('.appended_row').remove()
                    $('#schedule_modal_title').text('')
                    loadsubj = []
                })

                $('#subject_schedulemodal').on('show.bs.modal', function(e) {
                    add_instructor()
                    teacher_select2()
                    $('#Lecture').prop('checked', true)
                    $('#stime').val('07:00')
                    curriculum_option()
                })

                $('#subject_schedulemodal').on('hidden.bs.modal', function(e) {

                  //   $('.form-control-sm').val('')
                    $('.modal_select2').val('').trigger('change')
                    $('.day').prop('checked', false)
                    $('.terms').prop('checked', false)
                    $('.added_div').remove()
                    $('#Lecture').prop('checked', false)
                    $('#Laboratory').prop('checked', false)
                    $('.curriculum_select').empty()
                    $('#etime').val('')
                    $('#grading_template').val('')
                    subject_data = ''
                    show_subj = 0
                    subject_edit = []
                })
                var conflict = 0;
                $('#conflict_modal').on('hidden.bs.modal', function(e) {
                    conflict = 0;
                    $('.conflict_table').remove()
                })

                $('#conflict_modal_instructor').on('hidden.bs.modal', function(e) {
                    conflict = 0;
                    $('.conflict_table_instructor').remove()
                })


                $('#prospectus_modal').on('hidden.bs.modal', function(e) {
                    $('.curriculum_select').empty()
                    $('.appended_yearlevel').remove()
                })

                $('#conflict_modal').on('hidden.bs.modal', function () {
                    
                    if ($('.modal.show').length > 0) {
                        $('body').addClass('modal-open');
                    }
                });

                $('#schedule, #subject_schedulemodal').on('hidden.bs.modal', function () {
                    if ($('.modal.show').length > 0) {
                        $('body').addClass('modal-open');
                    }
                });

                $('#schedule, #subject_schedulemodal').on('shown.bs.modal', function () {
                    $('body').addClass('modal-open');
                });
                $('#modal_1').on('hidden.bs.modal', function() {
                    $('.appended_row_stud').remove()
                })
            })
        </script>
    @endsection
