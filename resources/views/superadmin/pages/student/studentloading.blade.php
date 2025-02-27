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

        body.modal-open {
            overflow: hidden !important;
            padding-right: 0 !important;
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
        .loader {
            width: 100px;
            height: 100px;
            margin: 50px auto;
            position: relative;
        }

        .loader:before,
        .loader:after {
            content: "";
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: solid 8px transparent;
            position: absolute;
            -webkit-animation: loading-1 1.4s ease infinite;
            animation: loading-1 1.4s ease infinite;
        }

        .loader:before {
            border-top-color: #fffdfd;
            border-bottom-color: #fffdfd;
        }

        .loader:after {
            border-left-color: #fffdfd;
            border-right-color: #fffdfd;
            -webkit-animation-delay: 0.7s;
            animation-delay: 0.7s;
        }

        @-webkit-keyframes loading-1 {
            0% {
                -webkit-transform: rotate(0deg) scale(1);
                transform: rotate(0deg) scale(1);
            }

            50% {
                -webkit-transform: rotate(180deg) scale(0.5);
                transform: rotate(180deg) scale(0.5);
            }

            100% {
                -webkit-transform: rotate(360deg) scale(1);
                transform: rotate(360deg) scale(1);
            }
        }

        @keyframes loading-1 {
            0% {
                -webkit-transform: rotate(0deg) scale(1);
                transform: rotate(0deg) scale(1);
            }

            50% {
                -webkit-transform: rotate(180deg) scale(0.5);
                transform: rotate(180deg) scale(0.5);
            }

            100% {
                -webkit-transform: rotate(360deg) scale(1);
                transform: rotate(360deg) scale(1);
            }
        }
    </style>

    <style>
        .conflict-alert {
            color: #dc3545;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card {
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.4);
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .card-body {
            font-size: 12px;
        }

        .table-sm th {
            background-color: #f8f9fa;
            text-align: left;
        }

        .load-subjects-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 12px;
            font-size: 14px;
            border-radius: 4px;
            margin-top: 10px;
        }
    </style>
@endsection


@section('content')
    @php
        $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
        $semester = DB::table('semester')->get();
        $gradelevel = DB::table('gradelevel')
            ->where('deleted', 0)
            ->whereIn('acadprogid', [6, 8])
            ->orderBy('sortid')
            ->get();
        $course = DB::table('college_courses')->where('deleted', 0)->get();
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

        // dd( $studSection);

        // if(auth()->user()->type == 16 || Session::get('currentPortal') == 16){

        //       $teacher = DB::table('teacher')
        //                         ->where('tid',auth()->user()->email)
        //                         ->first();

        //       $colleges = DB::table('college_courses')
        //                   ->join('college_colleges',function($join){
        //                         $join->on('college_courses.collegeid','=','college_colleges.id');
        //                         $join->where('college_colleges.deleted',0);
        //                   })
        //                   ->where('courseChairman',$teacher->id)
        //                   ->where('college_courses.deleted',0)
        //                   ->select('college_colleges.*')
        //                   ->get();

        // }else if(auth()->user()->type == 14  || Session::get('currentPortal') == 14){

        //       $teacher = DB::table('teacher')
        //                         ->where('tid',auth()->user()->email)
        //                         ->first();

        //       $colleges = DB::table('college_colleges')
        //                         ->where('dean',$teacher->id)
        //                         ->where('college_colleges.deleted',0)
        //                         ->select('college_colleges.*')
        //                         ->get();

        // }else{
        //       $colleges = DB::table('college_colleges')->where('deleted',0)->get();
        // }

        $colleges = DB::table('college_colleges')->where('cisactive', 1)->where('deleted', 0)->get();
        $college_gradelevel = DB::table('gradelevel')->where('acadprogid', 6)->where('deleted', 0)->get();
    @endphp




    <div class="modal fade" id="student_evaluation_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Grade Evaluation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table-sm table table-bordered" style="font-size:.7rem !important">
                                <tr>
                                    <td colspan="2"><b>Student Name:</b> <span id="eval_studentname_label"></span><button
                                            class="btn btn-default btn-sm float-right" style="font-size:.7rem !important"
                                            id="print_student_evaluation"><i class="fa fa-print"></i> Print</button></td>
                                </tr>
                                <tr>
                                    <td width="50%"><b>Course:</b> <span id="eval_course_label"></span></td>
                                    <td width="50%"><b>Curriculum:</b> <span id="eval_curriculum_label"></span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div id="student_evaluation_holder">

                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="studentloading_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label for="">Sections</label>
                            <select name="filter_section" id="filter_section" class="form-control select2"></select>
                        </div>
                        <div class="col-md-5 form-group">
                            <label for="">Subjects</label>
                            <select name="filter_subjects" id="filter_subjects" class="form-control select2"></select>
                        </div>
                        <div class="col-md-5 form-group">
                            <label for="">All Subjects</label>
                            <select name="filter_all_subjects" id="filter_all_subjects"
                                class="form-control select2"></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered smfont  table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" width="5%"></th>
                                        <th class="align-middle" width="10%">Section</th>
                                        <th class="text-center align-middle" width="35%">Subject</th>
                                        <th class="text-center align-middle" width="5%">Units</th>
                                        <th class="text-center align-middle" width="10%">Day</th>
                                        <th class="text-center align-middle" width="7%">Time</th>
                                        <th class="text-center align-middle" width="8%">Room</th>
                                        <th class="text-center align-middle" width="15%">Teacher</th>
                                        <th class="text-center align-middle" width="5%">Enrolled</th>
                                    </tr>
                                </thead>
                                <tbody class="schedule" id="sched_plot_holder">
                                    <tr>
                                        <td colspan="8" class="text-center align-middle">NO AVAILABLE SCHEDULE</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <button class="btn btn-primary btn-sm" id="button_add_all" disabled><i class="fas fa-plus"></i>
                                Add All Subject</button>
                        </div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i>
                                Close</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- <div class="modal fade" id="available_sched_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-xl">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Schedule List</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-2">
                                    <label for="">Sections</label>
                                    <select class="form-control form-control-sm" id="filter_sched_section">

                                    </select>
                              </div>
                              <div class="col-md-2">
                                    <label for="">Subject Code</label>
                                    <select class="form-control form-control-sm" id="filter_sched_subjcode">

                                    </select>
                              </div>
                              <div class="col-md-2">
                                    <label for="">Subject Description</label>
                                    <select class="form-control form-control-sm" id="filter_sched_subjdesc">

                                    </select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <table class="table-hover table table-striped table-sm table-bordered" id="available_sched_datatable" width="100%" style="font-size:.9rem !important">
                                          <thead>
                                                <tr>
                                                      <th width="8%"></th>
                                                      <th width="10%">Section</th>
                                                      <th width="8%">Subj. Code</th>
                                                      <th width="24%">Subject Description</th>
                                                      <th class="text-center" width="5%" colspan="2">Units</th>
                                                      <th class="text-center p-0 align-middle" width="5%">Cap.</th>
                                                      <th class="text-center" width="6%">Enrolled</th>
                                                      <th width="20%"></th>
                                                      <th width="14%"></th>
                                                </tr>
                                          </thead>
                                    </table>
                              </div>
                        </div>

                  </div>
            </div>
      </div>
</div>  --}}


    <div class="modal fade" id="preenrolled_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title">Pre-enrolled List</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table-hover table table-striped table-sm" id="datatable_preenrolled"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th width="50%">Student</th>
                                        <th width="20%">Course / Year Level</th>
                                        <th width="20%">Pre-enrollment date</th>
                                        <th width="10%"></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>





    <div class="modal fade" id="remove_sched_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h4>Student is already Enrolled!</h4>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="mb-0">Subject Code</label> : <span class="subj_desc_holder_code"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="mb-0">Subject Title</label> : <span class="subj_desc_holder_title"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <button class="btn btn-primary btn-sm btn-block remove_subject" data-id="DROP"><i
                                    class="fas fa-ban"></i> Drop Subject</button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-danger btn-sm  btn-block remove_subject" data-id="delete"><i
                                    class="far fa-trash-alt"></i> Delete Subject</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_sched_modal" style="display: none; z-index:1500" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h4>Student is already Enrolled!</h4>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="mb-0">Subject Code</label> : <span class="subj_desc_holder_code"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="mb-0">Subject Title</label> : <span class="subj_desc_holder_title"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <button class="btn btn-primary btn-sm btn-block add_subject"
                                style="font-size:.8rem !important" data-status="regular">Regular Subject</button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-danger btn-sm  btn-block add_subject"
                                style="font-size:.8rem !important" data-status="additional">Additional Subject</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="sched_con_modal" style="display: none; z-index:1051" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.3rem !important">Schedule Conflict</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row" id="sched_con_holder">
                        <div class="col-md-12">
                            <strong>Section:</strong> <span id="con_sect"></span>
                        </div>
                        <div class="col-md-12">
                            <strong>Subject:</strong> <span id="con_subj"></span>
                        </div>
                        <div class="col-md-12">
                            <strong>Day:</strong> <span id="con_day"></span>
                        </div>
                        <div class="col-md-12">
                            <strong>Time:</strong> <span id="con_time"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary btn-block add_subject"
                                id="conflict_addsched_button">Conflict : Add Sched</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="modal fade" id="enrolled_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Student List <span id="student_list_type"></span></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-12">
                                    <label for="" id="list_label"></label>
                              </div>
                        </div>
                      <div class="row">
                            <div class="col-md-12" style="font-size:.7rem !important">
                              <table class="table table-striped table-sm table-bordered table-head-fixed p-0" id="student_list" width="100%"  >
                                        <thead>
                                              <tr>
                                                    <th width="50%">Students</th>
                                                    <th width="25%">Grade Level</th>
                                                    <th width="25%">Course</th>
                                              </tr>
                                        </thead>
                                  </table>
                            </div>
                      </div>
                  </div>
            </div>
      </div>
</div>    --}}

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ isset($page) ? $page : 'Student Loading' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">{{ isset($page) ? $page : 'Student Loading' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content pt-0">
        <div class="container-fluid">
            {{-- <div class="row">
                    <div class="col-md-3">
                        <div class="info-box shadow-lg">
                            <div class="info-box-content">
                                <div class="row">
                                    <div class="col-md-12">
                                            <label for="">Student</label>
                                            <select class="form-control select2" id="filter_student">
                                                <option value="">Select a student</option>
                                            </select>
                                    </div>

                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                            <button class="btn btn-primary btn-sm" id="button_filter"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div> --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body p-2">
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
                                <div class="col-md-4">
                                    <label for="" class="mb-1">Course</label>
                                    <select name="filter_course" id="filter_course"
                                        class="form-control form-control-sm select2">
                                        <option value="">All</option>
                                        @foreach ($course as $item)
                                            <option value="{{ $item->id }}">({{ $item->courseabrv }})
                                                {{ $item->courseDesc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="" class="mb-1">Academic Level</label>
                                    <select name="filter_gradelevel" id="filter_gradelevel"
                                        class="form-control form-control-sm select2">
                                        <option value="">All</option>
                                        @foreach ($gradelevel as $item)
                                            <option value="{{ $item->id }}">{{ $item->levelname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="" class="mb-1">Student Status</label>
                                    <select name="filter_students" id="filter_studentstatus"
                                        class="form-control form-control-sm select2">
                                        <option value="all">All</option>
                                        @foreach ($studStatus as $item)
                                            <option value="{{ $item->id }}">{{ $item->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-8 pt-1" hidden>
                                    <div class="card shadow">
                                        <div class="card-body p-2" style="font-size:.9rem !important">
                                            <div class="input-group-prepend float-right mt-2">
                                                <button type="button" class="btn btn-primary dropdown-toggle btn-sm"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                    Enrollment List
                                                </button>
                                                <div class="dropdown-menu" x-placement="bottom-start"
                                                    style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);"
                                                    id="enrollment_list_holder">
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
            {{-- <div class="row" >
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body p-2" style="font-size:.9rem !important">
                                    <div class="input-group-prepend">
                                          <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
                                            Enrollment List
                                          </button>
                                          <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);" id="enrollment_list_holder">
                                          </div>
                                    </div>

                              </div>
                        </div>
                  </div>
            </div> --}}

            <div class="row" hidden>
                <div class="col-md-3">
                    <div class="card shadow">
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm btn-block" id="pre_enrolled_button">Pre-Enrolled
                                        <span id="pre_enrollment_count"></span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="info-box shadow-lg  p-2">
                        <div class="info-box-content p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="" class="mb-1">Student</label>
                                    <select class="form-control select2" id="filter_student">
                                        <option value="">Select a student</option>
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="row mt-3">
                                    <div class="col-md-6">
                                          <button class="btn btn-primary btn-sm" id="button_filter"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                              </div> --}}
                        </div>
                    </div>


                    <div class="card shadow">
                        <div class="card-body box-profile">
                            <h3 class="profile-username text-center" id="student_fullname">Student Name</h3>
                            <p class="text-muted text-center" id="cur_glevel">Grade Level</p>
                        </div>
                    </div>

                    <div class="card shadow">
                        <div class="card-body" style="font-size:12px !important">
                            <strong><i class="fas fa-book mr-1"></i> Enrollment Status</strong>
                            <p class="text-muted" id="label_studstat">
                                --
                            </p>
                            <strong><i class="fas fa-book mr-1"></i> SID</strong>
                            <p class="text-muted" id="label_sid">
                                --
                            </p>
                            <strong><i class="fas fa-book mr-1"></i> Course</strong>
                            <p class="text-muted" id="label_course">
                                --
                            </p>
                            <strong><i class="fas fa-book mr-1"></i> Curriculum</strong>
                            <p class="text-muted" id="label_curriculum">
                                --
                            </p>
                            <strong><i class="fas fa-book mr-1"></i> College</strong>
                            <p class="text-muted" id="label_college">
                                --
                            </p>
                            <strong><i class="fas fa-book mr-1"></i> Contact Information</strong>
                            <p class="text-muted" id="label_contact">
                                --
                            </p>
                            <strong><i class="fas fa-book mr-1"></i> Incase of Emergency</strong>
                            <p class="text-muted" id="label_ic_contact">
                                --
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">


                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5>Loaded Subjects</h5>
                                </div>
                                <div class="col-md-8 text-right">
                                    <button class="btn btn-primary btn-sm" id="student_evaluation" disabled><i
                                            class="fas fa-file-pdf" hidden></i> Student Evaluation</button>
                                    <button class="btn btn-secondary btn-sm" disabled hidden><i
                                            class="fas fa-file-pdf"></i> Cor</button>
                                    <button class="btn btn-danger btn-sm" id="unload_all_subj" disabled hidden><i
                                            class="fas fa-ban"></i> Unload All Subjects</button>
                                    <button class="btn btn-primary btn-sm" id="button_to_studentloading_modal" disabled><i
                                            class="fas fa-plus"></i> Add Subjects</button>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12 text-danger"><i>Please click the section name to indicate the student
                                        section.</i></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-sm" style="font-size:12px !important">
                                        <thead>
                                            <tr>
                                                <th class="align-middle" width="10%">Section</th>
                                                <th class="text-center align-middle" width="40%">Subject</th>
                                                <th class="text-center align-middle" width="5%">Units</th>
                                                <th class="text-center align-middle" width="10%">Day</th>
                                                <th class="text-center align-middle" width="10%">Time</th>
                                                <th class="text-center align-middle" width="10%">Room</th>
                                                <th class="text-center align-middle" width="15%">Teacher</th>
                                                <th class="text-center align-middle" width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="schedule" id="studentsched_plot_holder">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid mt-1"> <!-- Use container-fluid for full-width responsiveness -->
        <div class="card">
            <div class="card-body">
                <div class="text-left mb-3">
                    <button class="btn btn-primary btn-sm" id="button_print_studentloading" data-id="0"><i
                            class="fas fa-print"></i>
                        Print Student Information</button>
                </div>
                <div class="table-responsive"> <!-- Add responsiveness to table -->
                    <table id="studentLoadingTable" class="table table-striped table-bordered table-sm"
                        style="font-size: 10px; width: 100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Student ID</th>
                                <th>Students Name</th>
                                <th>Academic Level</th>
                                <th>Course</th>
                                <th>Section</th>
                                <th>Date Enrolled</th>
                                <th>Date Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="Male_Table">
                                <td colspan="9"><b>MALE</b></td>
                            </tr>

                            <tr class="Female_Table">
                                <td colspan="9"><b>FEMALE</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Legend -->
                <div class="legend mt-3 text-center d-flex justify-content-start flex-wrap"
                    style="font-size:10px; gap: 15px; font-weight: bold;">
                    <div class="d-flex align-items-center mb-2">
                        <span class="legend-text text-secondary mr-1" id="notEnrolledCount">NOT ENROLLED (0)</span> |
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="legend-text text-success mr-1" id="enrolledCount">ENROLLED (0)</span> |
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="legend-text mr-1" style="color: #58715f;" id="lateEnrollmentCount">LATE ENROLLMENT
                            (0)</span> |
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="legend-text text-danger mr-1" id="droppedOutCount">DROPPED OUT (0)</span> |
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="legend-text text-warning mr-1" id="withdrawnCount">WITHDRAWN (0)</span> |
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="legend-text text-primary mr-1" id="transferredInCount">TRANSFERRED IN (0)</span> |
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="legend-text mr-1" style="color: #fd7e14;" id="transferredOutCount">TRANSFERRED OUT
                            (0)</span> |
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="legend-text text-dark mr-1" id="deceasedCount">DECEASED (0)</span> |
                    </div>

                </div>
            </div>
        </div>
    </div>
    @php
        $profile = DB::table('studinfo')->first();
        // $student = DB::table('students')->first();
    @endphp
    <!-- Student Loading Modal -->

    <!-- Section Schedule List Modal -->
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
        $('#filter_gradelevel').select2()
        $('#filter_course').select2()
        $('#studentSection').select2()
        $('#filter_studentstatus').select2()
        $('#updateCourseSelect').select2()
    </script>

    <script>
        var routes = {
            'cllgschdgrpList': '{{ route('cllgschdgrpList') }}',
            'cllgschdgrpSelect': '{{ route('cllgschdgrpSelect') }}',
            'cllgschdgrpDatatable': '{{ route('cllgschdgrpDatatable') }}',
            'cllgschdgrpCreate': '{{ route('cllgschdgrpCreate') }}',
            'cllgschdgrpUpdate': '{{ route('cllgschdgrpUpdate') }}',
            'cllgschdgrpDelete': '{{ route('cllgschdgrpDelete') }}',
        }

        const currentPortal = @json(Session::get('currentPortal'));
        const school = @json($schoolinfo);
    </script>

    @include('superadmin.pages.college.js.college-subjsched')
    @include('superadmin.pages.college.js.college-schedlist')
    @include('superadmin.pages.college.js.college-schedgroup')
    @include('superadmin.pages.student.studentinformationmodal')

    <script>
        // $(document).ready(function() {
        //     schdgrpLoadResources($('#filter_sy').val(), $('#filter_semester').val(), $('#filter_semester').val(), $(
        //         '#filter_course').val(), $('#filter_gradelevel').val()),
        // })
    </script>

    <script>
        getActiveEnrollmentSetup()
        var entype = null

        function getActiveEnrollmentSetup() {
            $.ajax({
                type: 'GET',
                url: '/student/loading/getActiveEnrollmentSetup',
                data: {
                    syid: $('#filter_sy').val(),
                    semid: $('#filter_semester').val(),
                },
                success: function(data) {
                    $('#enrollment_setup_holder').attr('hidden', 'hidden')
                    if (data.length > 0) {
                        entype = data[0].collegeentypedes.toUpperCase()
                        $('#enrollment_setup_holder_desc')[0].innerHTML = '<b>Enrollment Setup </b>: ' + data[0]
                            .collegeentypedes + '<b class="ml-5">Enrollment Period </b>: ' + data[0]
                            .enrollmentstartdesc + ' - ' + data[0].enrollmentenddesc
                    } else {
                        $('#enrollment_setup_holder_desc')[0].innerHTML = '<b>No Enrollment Setup</b>'
                    }
                }
            })
        }
    </script>


    <script>
        $(window).resize(function() {
            screenadjust();
        });

        screenadjust();

        function screenadjust() {
            var screen_height = $(window).height() - 324;

            // Adjust height for #studentLoadingDatatable
            $('#studentLoadingDatatable').css('height', screen_height);

            // Adjust the height of the modal's table container
            var modal_body_height = $(window).height() - 324; // Adjust the offset for modal header and footer
            $('#sectionTablesContainer').css('max-height', modal_body_height);

        }


        $(document).ready(function() {
            $('#studentTable').DataTable({
                "paging": true,
                "searching": true,
                "info": false,
                "ordering": false
            });
        });
        $(document).on('click', '#schedgroup_to_modal', function() {
            schedgroup_datatable()
        })

        $(document).ready(function() {
            load_all_student()
            $(document).on('click', '#button_to_studentloading_modal', function() {
                load_csl_resource()
                display_sched_csl('studentloading', stud_id, entype)
                $('#cap_holder').removeAttr('hidden', 'hidden')
                $('#createsection_input_capacity').val(50)
                $('#available_sched_modal').modal()
            })

            // $('.select2').select2()

            $(document).on('click', '.view_enrollment_list', function() {
                var temp_college = $(this).attr('data-id')
                var syid = $('#filter_sy').val()
                var semid = $('#filter_semester').val()
                window.open('/student/loading/report/enrollment?college=' + temp_college + '&syid=' + syid +
                    '&semid=' + semid, '_blank');
            })

            var all_students = [];
            var all_sections = [];
            var stud_course = null;
            var stud_id = null;
            var section = null;
            var student_info = [];
            var all_preenrolled = [];
            var all_sched = [];
            var all_sched_section = [];
            var all_sched_enrolled = [];
            var all_sched_detail = [];
            var all_sched_student = [];
            const studentData = []; // Assuming this array holds your original student data

            $(document).on('change', '#filter_semester , #filter_sy , #filter_course , #filter_gradelevel',
                function() {
                    $('#no_course_holder').attr('hidden', 'hidden');
                    $('#button_to_studentloading_modal').attr('disabled', 'disabled');
                    $('#student_evaluation').attr('disabled', 'disabled');
                    $("#filter_student").empty();
                    applyFilters();
                    get_course();
                    get_preenrolled();
                    get_all_sched();
                    load_all_student();
                    getActiveEnrollmentSetup();
                    populateSections()
                });

            function applyFilters() {
                const semester = $('#filter_semester').val();
                const schoolYear = $('#filter_sy').val();
                const course = $('#filter_course').val();
                const gradeLevel = $('#filter_gradelevel').val();

                const filteredStudents = studentData.filter(student => {
                    return (!semester || student.semester === semester) &&
                        (!schoolYear || student.syid === schoolYear) &&
                        (!course || student.courseabrv === course) &&
                        (!gradeLevel || student.levelname === gradeLevel);
                });

                studentLoadingTable(filteredStudents);
            }

            $(document).on('click', '#pre_enrolled_button', function() {
                $('#preenrolled_modal').modal()
            })

            $(document).on('click', '.select_student_from_preenrolled', function() {
                $('#filter_student').val($(this).attr('data-id')).change()
                $('#preenrolled_modal').modal('hide')
            })

            get_preenrolled()

            function get_preenrolled() {

                $.ajax({
                    type: 'GET',
                    url: '/student/loading/report/preenrolled',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                    },
                    success: function(data) {
                        $('#pre_enrollment_count').text('(' + data.length + ')')
                        all_preenrolled = data
                        datatable_preenrollment()
                    }
                })

            }

            var curriculum = null

            function check_enrollment() {

                $.ajax({
                    type: 'GET',
                    url: '/student/loading/student/enrollment',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        studid: $('#filter_student').val(),
                    },
                    success: function(data) {

                        var studid = $('#filter_student').val()
                        student_info = all_students.filter(x => x.id == studid)
                        var student_index = all_students.findIndex(x => x.id == studid)
                        all_students[student_index].studstatus = data[0].studstatus;
                        student_info[0].studstatus = data[0].studstatus

                        all_students[student_index].description = data[0].description;
                        student_info[0].description = data[0].description



                        if (data[0].curriculum != null) {
                            $('#updatestudentcourse_modal').modal('hide');
                            curriculum = [data[0].curriculum]
                        } else {
                            $('#input_course').val(all_students[student_index].courseid).change()
                            $('#input_level').val(all_students[student_index].levelid).change()
                            $('#updatestudentcourse_modal').modal();
                            curriculum = [{
                                'id': null,
                                'curriculum': null
                            }]
                        }

                        load_student_enrollment_info()
                    }
                })


            }

            function datatable_preenrollment() {

                $("#datatable_preenrolled").DataTable({
                    destroy: true,
                    data: all_preenrolled,
                    lengthChange: false,
                    autoWidth: false,
                    columns: [{
                            "data": "student"
                        },
                        {
                            "data": null
                        },
                        {
                            "data": "preenrollmentdatetime"
                        },
                        {
                            "data": null
                        },
                    ],
                    columnDefs: [{
                            'targets': 0,
                            //'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).addClass('align-middle')
                                $(td)[0].innerHTML =
                                    '<a href="javascript:void(0)" class="select_student_from_preenrolled" data-id="' +
                                    rowData.id + '">' + rowData.student + '</a>'
                            }
                        },
                        {
                            'targets': 1,
                            //'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).addClass('align-middle')

                                var year_level = null;
                                if (rowData.levelid == 17) {
                                    year_level = 1
                                } else if (rowData.levelid == 18) {
                                    year_level = 2
                                } else if (rowData.levelid == 19) {
                                    year_level = 3
                                } else if (rowData.levelid == 20) {
                                    year_level = 4
                                }

                                $(td).text(rowData.courseabrv + ' - ' + year_level)
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).addClass('align-middle')
                                if (rowData.with_sched) {
                                    $(td)[0].innerHTML =
                                        '<span class="badge badge-success">With Sched</span>'
                                } else {
                                    $(td).text(null)
                                }

                            }
                        },

                    ]
                });

            }


            $(document).on('change', '#filter_section', function() {
                var temp_section = $(this).val()
                if (temp_section == "") {
                    $('#sched_plot_holder').empty()
                    $('#button_add_all').attr('disabled', 'disabled')
                    return false
                }

                $('#filter_subjects').val("").change()
                $('#filter_all_subjects').val("").change()

                $.ajax({
                    type: 'GET',
                    url: '/student/loading/availablesched',
                    data: {
                        sectionid: temp_section,
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                    },
                    success: function(data) {
                        $('#sched_plot_holder').empty()
                        $('#sched_plot_holder').append(data)

                        $('.remove_schedule').each(function(a, b) {
                            $('.add_sched[data-id="' + $(b).attr('data-id') + '"]')
                                .remove()
                        })


                        if ($('.add_sched').length > 0) {
                            $('#button_add_all').removeAttr('disabled')
                        }
                    }
                })
            })

            $(document).on('change', '#filter_subjects , #filter_all_subjects', function() {
                var temp_subject = $(this).val()

                if (temp_subject == "") {
                    $('#sched_plot_holder').empty()
                    return false
                }

                $('#filter_section').val("").change()

                $.ajax({
                    type: 'GET',
                    url: '/student/loading/availablesched',
                    data: {
                        subjid: temp_subject,
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                    },
                    success: function(data) {
                        $('#sched_plot_holder').empty()
                        $('#sched_plot_holder').append(data)
                    }
                })
            })

            load_all_student()

            var userinfo = @json(auth()->user()->type)


            //add all schedule
            $(document).on('click', '#button_add_all', function() {

                $.ajax({
                    type: 'GET',
                    url: '/student/loading/student/schedule/add/all',
                    data: {
                        studid: stud_id,
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        sectionid: $('#filter_section').val()
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            student_sched()
                            Toast.fire({
                                type: 'success',
                                title: data[0].data
                            })
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].data
                            })
                        }
                    },
                    error: function() {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong.'
                        })
                    }
                })


            })

            $(document).on('click', '#button_to_updatestudentcourse_modal', function() {

                var check_sched = $('.stud_sect').length


                $('#input_course').val(student_info[0].courseid).change()
                $('#input_curriculum').val(curriculum[0].id).change()

                if (student_info[0].levelid == 15) {
                    $('#input_level').val(17).change();
                } else {
                    $('#input_level').val(student_info[0].levelid).change();
                }


                if (userinfo != 17) {
                    // if(student_info[0].studstatus == 1){
                    //       Toast.fire({
                    //             type: 'error',
                    //             title: 'Student is already enrolled.'
                    //       })
                    //       return false
                    // }else if(check_sched > 0){
                    //       Toast.fire({
                    //             type: 'error',
                    //             title: 'Please remove learners schedule'
                    //       })
                    //       return false
                    // }
                }

                $('#updatestudentcourse_modal').modal()

            })

            $(document).on('click', '#unload_all_subj', function() {

                // if(student_info[0].studstatus == 1 || student_info[0].studstatus == 2 || student_info[0].studstatus == 4){
                //       Swal.fire({
                //             title: 'Are you sure?',
                //             html: "You. <br>This subject will be marked as DROP!",
                //             type: 'warning',
                //             showCancelButton: true,
                //             confirmButtonColor: '#3085d6',
                //             cancelButtonColor: '#d33',
                //             confirmButtonText: 'Proceed!'
                //       })
                //       .then((result) => {
                //             if (result.value) {
                //                   unload_subjects()
                //             }
                //       })
                // }else{

                Swal.fire({
                        title: 'Are you sure?',
                        html: "You are about to remove all <br>the students schedule!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Proceed!'
                    })
                    .then((result) => {
                        if (result.value) {
                            unload_subjects()
                        }
                    })
                // }

            })

            function unload_subjects() {
                $.ajax({
                    type: 'GET',
                    url: '/student/loading/student/schedule/unload/all',
                    data: {
                        studid: stud_id,
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        status: 'delete'
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            student_sched()
                            Toast.fire({
                                type: 'success',
                                title: data[0].data
                            })
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].data
                            })
                        }
                    },
                    error: function() {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong.'
                        })
                    }
                })

            }


            $(document).on('change', '#filter_student', function() {

                // load_student_enrollment_info()

                if ($(this).val() == "") {
                    $('#label_studstat').text("--")
                    $('#student_fullname').text("--")
                    $('#cur_glevel').text("--")
                    $('#cur_glevel').text("--")
                    $('#label_course').text("--")
                    $('#label_contact').text("--")
                    $('#label_ic_contact').text("--")
                    $('#label_curriculum').text("--")
                    $('#label_college').text("--")
                    $('#label_sid').text("--")
                    $('#sched_plot_holder').empty()
                } else {
                    check_enrollment()
                }
            })

            function load_student_enrollment_info() {
                $('#button_to_studentloading_modal').removeAttr('disabled')
                $('#student_evaluation').removeAttr('disabled')

                var temp_id = $('#filter_student').val()

                student_info = all_students.filter(x => x.id == temp_id)

                // if(student_info.length == 0){
                //       Toast.fire({
                //             type: 'error',
                //             title: 'Student not found.'
                //       })
                // }else{
                //       Toast.fire({
                //             type: 'success',
                //             title: 'Student found.'
                //       })
                // }

                $('#label_studstat').text("--")
                $('#student_fullname').text("--")
                $('#cur_glevel').text("--")
                $('#cur_glevel').text("--")
                $('#label_course').text("--")
                $('#label_contact').text("--")
                $('#label_ic_contact').text("--")
                $('#label_curriculum').text("--")
                $('#label_college').text("--")
                $('#label_sid').text("--")
                $('#sched_plot_holder').empty()

                $('#filter_gradelevel').val(student_info[0].levelid).change()

                stud_course = student_info[0].courseid
                stud_id = student_info[0].id
                section = student_info[0].sectionid

                if (stud_course == null || stud_course == 0) {
                    $('#updatestudentcourse_modal').modal()
                }

                student_sched()
                // sections()
                // subjects()
                // all_college_subjects()
            }

            var all_subjects
            var all_college_subjects_list = []

            function subjects() {
                $.ajax({
                    type: 'GET',
                    url: '/student/loading/subjects',
                    data: {
                        courseid: stud_course,
                        curriculum: curriculum[0].id
                    },
                    success: function(data) {
                        all_subjects = data;
                        $("#filter_subjects").empty()
                        $("#filter_subjects").append('<option value="">Select Subject</option>')
                        $("#filter_subjects").val("")
                        $("#filter_subjects").select2({
                            allowClear: true,
                            data: all_subjects,
                            placeholder: "Select a subject",
                        })
                    }
                })
            }

            function all_college_subjects() {
                $.ajax({
                    type: 'GET',
                    url: '/student/loading/subjects/all',
                    success: function(data) {
                        all_college_subjects_list = data;
                        $("#filter_all_subjects").empty()
                        $("#filter_all_subjects").append('<option value="">Select Subject</option>')
                        $("#filter_all_subjects").val("")
                        $("#filter_all_subjects").select2({
                            allowClear: true,
                            data: all_college_subjects_list,
                            placeholder: "Select a subject",
                        })
                    }
                })
            }

            function load_all_student() {

                all_students = [];

                $.ajax({
                    type: 'GET',
                    url: '/student/loading/students',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        course: $('#filter_course').val(),
                        gradelevel: $('#filter_gradelevel').val(),
                        enrolled_status: $('#filter_studentstatus').val(),
                    },
                    success: function(data) {

                        studentLoadingTable(data);
                    }
                });
            }

            $('#filter_studentstatus').on('change', function() {

                load_all_student()
                // all_students = [];
                // let requestData = {
                //     syid: $('#filter_sy').val(),
                //     semid: $('#filter_semester').val(),
                //     course: $('#filter_course').val(),
                //     gradelevel: $('#filter_gradelevel').val(),
                //     studentstatus: $('#filter_studentstatus').val()
                // };
                // console.log(requestData, 'Sent filter data');

                // $.ajax({
                //     url: '/student/getFilteredStudents',
                //     method: 'GET',
                //     data: requestData,
                //     success: function(response) {
                //         console.log(response, 'Filtered students response');
                //         studentLoadingTable(response);
                //     },
                //     error: function(xhr) {
                //         console.log(xhr.responseText);
                //     }
                // });
            });


            var all_courses = []
            var all_colleges = @json($colleges);
            load_courses()

            function load_courses() {
                $.ajax({
                    type: 'GET',
                    url: '/student/loading/courses',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                    },
                    success: function(data) {
                        all_courses = data
                        $("#input_course").empty()
                        $("#input_course").append('<option value="">All</option>')
                        $("#input_course").val("")
                        $('#input_course').select2({
                            allowClear: true,
                            data: all_courses,
                            placeholder: "All",
                        })

                        var temp_colleges = []
                        $.each(all_courses, function(a, b) {

                            var check = temp_colleges.filter(x => x.id == b.collegeid)
                            if (check.length == 0) {
                                var get_college = all_colleges.filter(x => x.id == b.collegeid)
                                temp_colleges.push({
                                    'id': get_college[0].id,
                                    'collegeabrv': get_college[0].collegeabrv
                                })
                            }
                        })

                        var temp_text = ''
                        $.each(temp_colleges, function(a, b) {
                            temp_text +=
                                ' <a class="dropdown-item view_enrollment_list" href="#" data-id="' +
                                b.id + '">' + b.collegeabrv + '</a>'
                        })

                        $('#enrollment_list_holder')[0].innerHTML = temp_text


                    }
                })
            }

            $(document).on('change', '#input_course', function() {

                if ($(this).val() == "" || $(this).val() == null) {
                    $("#input_curriculum").empty()
                    return false
                }

                var temp_id = $(this).val()


                var curriculum = all_courses.filter(x => x.id == temp_id)[0].curriculum

                $("#input_curriculum").empty()
                $("#input_curriculum").append('<option value="">Select curriculum</option>')
                $("#input_curriculum").val("")
                $('#input_curriculum').select2({
                    allowClear: true,
                    data: curriculum,
                    placeholder: "Select curriculum",
                })
            })

            $(document).on('click', '#update_coures', function() {

                if ($('#input_course').val() == "") {
                    Toast.fire({
                        type: 'warning',
                        title: 'Please All'
                    })
                    return false
                }
                if ($('#input_curriculum').val() == "") {
                    Toast.fire({
                        type: 'warning',
                        title: 'Please select curriculum'
                    })
                    return false
                }


            })


            function student_sched() {
                $.ajax({
                    type: 'GET',
                    url: '/student/loading/student/schedule',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        studid: stud_id,
                        courseid: student_info[0].courseid
                    },
                    success: function(data) {


                        $('#label_studstat').text(student_info[0].description)
                        $('#student_fullname').text(student_info[0].student)
                        $('#cur_glevel').text(student_info[0].levelname)
                        $('#cur_glevel').text(student_info[0].levelname)
                        if (student_info[0].courseid == null) {
                            $('#label_course')[0].innerHTML =
                                '<a href="#" id="button_to_updatestudentcourse_modal"><i class="far fa-edit text-primary"></i> Update Course</a>'
                        } else {
                            $('#label_course')[0].innerHTML = '<p class="mb-0">' + student_info[0]
                                .courseDesc + '</p>' +
                                '<a href="#" id="button_to_updatestudentcourse_modal" hidden><i class="far fa-edit text-primary"></i> Update Course</a>'
                        }
                        if (student_info[0].contactno == null) {
                            $('#label_contact').text('No Contact Number')
                        } else {
                            $('#label_contact').text(student_info[0].contactno)
                        }
                        if (student_info[0].ismothernum == 1) {
                            $('#label_ic_contact').text(student_info[0].mcontactno + ' [ MOTHER ] ')
                        } else if (student_info[0].isfathernum == 1) {
                            $('#label_ic_contact').text(student_info[0].fcontactno + ' [ FATHER ] ')
                        } else if (student_info[0].isguardannum == 1) {
                            $('#label_ic_contact').text(student_info[0].gcontactno + ' [ GUARDIAN ] ')
                        } else {
                            $('#label_ic_contact').text('No Contact Number')
                        }
                        $('#label_college').text(student_info[0].collegeDesc)
                        $('#label_sid').text(student_info[0].sid)

                        if (curriculum.length != 0) {
                            $('#label_curriculum').text(curriculum[0].curriculumname)
                        } else {
                            $('#label_curriculum').text('')
                        }



                        $('#studentsched_plot_holder').empty()
                        $('#studentsched_plot_holder').append(data)

                        $('.stud_sect[data-id="' + section + '"]').append(
                            '<br><span class="badge badge-success">Student Section</span>')
                        $('#button_to_updatestudentcourse_modal').removeAttr('hidden')

                        if ($('.stud_sect').length > 0) {
                            $('#unload_all_subj').removeAttr('disabled')
                        } else {
                            $('#unload_all_subj').attr('disabled', 'disabled')
                        }


                    }
                })
            }

            get_course()

            function get_course() {
                $.ajax({
                    type: 'GET',
                    url: '/student/loading/getcourse',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        levelid: $('#filter_gradelevel').val(),
                        courseid: $('#filter_course').val(),

                    },
                    success: function(data) {
                        if (data.length > 0) {
                            $('#no_course_holder').attr('hidden', 'hidden')
                        } else {
                            $('#no_course_holder').removeAttr('hidden')

                        }
                    }
                })
            }

            function sections() {
                $.ajax({
                    type: 'GET',
                    url: '/student/loading/sections',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        courseid: $('#filter_course').val(),
                        levelid: $('#filter_gradelevel').val()
                    },
                    success: function(data) {
                        all_sections = data
                        $("#filter_section").empty()
                        $("#filter_section").append('<option value="">Select Section</option>')
                        $("#filter_section").val("")

                        $('#filter_section').select2({
                            allowClear: true,
                            data: all_sections,
                            placeholder: "Select sections",
                        })
                    }
                })
            }

            $(document).on('click', '#close_updatestudentcourse_modal', function() {
                if ($('#input_course').val() == "") {
                    Toast.fire({
                        type: 'warning',
                        title: 'No course selected'
                    })
                    return false
                }

                if ($('#input_curriculum').val() == "") {
                    Toast.fire({
                        type: 'warning',
                        title: 'No curriculum selected'
                    })
                    return false
                }
                $('#button_to_studentloading_modal').attr('disabled', 'disabled')
                $('#student_evaluation').attr('disabled', 'disabled')
                //$('#filter_student').val("").change()
                $('#updatestudentcourse_modal').modal('hide')
            })
            $(document).on('click', '#button_to_studentloading_modal', function() {
                // if(stud_course == null || stud_course == 0){
                //       $('#updatestudentcourse_modal').modal()
                //       return false
                // }

                // $('#button_add_all').attr('disabled','disabled')
                // if(student_info[0].studstatus == 1 || student_info[0].studstatus == 2 || student_info[0].studstatus == 4){
                //       Toast.fire({
                //             type: 'warning',
                //             title: 'Student is already enrolled'
                //       })
                //       return false
                // }
                // $('#studentloading_modal').modal();
                allowconflict = 0
                $('#available_sched_modal').modal()
            })

            var selected_sched


            $(document).on('click', '.remove_subject', function() {
                remove_schedule($(this).attr('data-id'))
            })

            $(document).on('click', '.remove_schedule', function() {

                var valid = false
                var schedstat = $(this).attr('data-schedstat')
                selected_sched = $(this).attr('data-id')
                var schedinfo = all_sched.filter(x => x.id == selected_sched)
                // $('.subj_desc_holder_code').text(schedinfo[0].subjCode)
                // $('.subj_desc_holder_title').text(schedinfo[0].subjDesc)

                // if(student_info[0].studstatus == 1 || student_info[0].studstatus == 2 || student_info[0].studstatus == 4){
                // $('#remove_sched_modal').modal()
                // if(schedstat == 'DROP'){
                //       Swal.fire({
                //             title: 'Are you sure you want to remove this subject?',
                //             html: "This subject is already marked as DROP. It will be remove from student load!",
                //             type: 'warning',
                //             showCancelButton: true,
                //             confirmButtonColor: '#3085d6',
                //             cancelButtonColor: '#d33',
                //             confirmButtonText: 'Proceed!'
                //       })
                //       .then((result) => {
                //             if (result.value) {
                //                   remove_schedule()
                //             }
                //       })
                // }else{
                Swal.fire({
                        title: 'Do you want to remove schedule?',
                        html: "This subject will be removed.",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Proceed!'
                    })
                    .then((result) => {
                        if (result.value) {
                            remove_schedule()
                        }
                    })
                // }

                // }else{
                // remove_schedule()
                // }

            })

            function remove_schedule(status = "deleted") {

                $.ajax({
                    type: 'GET',
                    url: '/student/loading/student/schedule/remove',
                    data: {
                        schedid: selected_sched,
                        studid: stud_id,
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        status: status
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            if (status == 'delete') {
                                $('#remove_sched_modal').modal('hide')
                            }
                            student_sched()
                            Toast.fire({
                                type: 'success',
                                title: data[0].data
                            })
                            // all_sched_student[loaded_allsched_student_index].enrolled -= 1;
                            display_sched_csl('studentloading', stud_id, entype)
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].data
                            })
                        }
                    },
                    error: function() {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong.'
                        })
                    }
                })
            }

            $('#sched_con_modal').on('hidden.bs.modal', function() {
                allowconflict = 0
            })

            var allowconflict = 0

            $(document).on('click', '.add_sched', function() {
                selected_sched = $(this).attr('data-id')
                $('#conflict_addsched_button').removeAttr('data-id')
                $('#conflict_addsched_button').removeAttr('data-status')

                var schedinfo = all_sched.filter(x => x.id == selected_sched)
                $('.subj_desc_holder_code').text(schedinfo[0].subjCode)
                $('.subj_desc_holder_title').text(schedinfo[0].subjDesc)
                // if(student_info[0].studstatus == 1 || student_info[0].studstatus == 2 || student_info[0].studstatus == 4){
                //       $('#add_sched_modal').modal()
                // }else{
                var isenrolled = 0
                if (student_info[0].studstatus == 1 || student_info[0].studstatus == 2 || student_info[0]
                    .studstatus == 4) {
                    isenrolled = 1
                }

                add_sched_function('regular', isenrolled);
                // }
            })

            $(document).on('click', '#addAllSched', function() {
                Swal.fire({
                        title: 'Are you sure you want to add all schedule from this section?',
                        // html: "You you sure you want to all schedule from this section?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Proceed!'
                    })
                    .then((result) => {
                        if (result.value) {
                            add_all_sched()
                        }
                    })
            })

            function add_all_sched() {

                if ($('.add_sched').length == 0) {
                    Toast.fire({
                        type: 'success',
                        title: 'Schedule Added.'
                    })
                    display_sched_csl('studentloading', stud_id, entype)
                    student_sched()
                    return false
                }

                schedid = $($('.add_sched')[0]).attr('data-id')

                var isenrolled = 0
                if (student_info[0].studstatus == 1 || student_info[0].studstatus == 2 || student_info[0]
                    .studstatus == 4) {
                    isenrolled = 1
                }

                allowconflict = 1

                $.ajax({
                    type: 'GET',
                    url: '/student/loading/student/schedule/add',
                    data: {
                        schedid: schedid,
                        studid: stud_id,
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        allowconflict: allowconflict,
                        status: status
                    },
                    success: function(data) {
                        $('.add_sched[data-id=' + schedid + ']').addClass('remove_schedule')
                        $('.add_sched[data-id=' + schedid + ']').addClass('btn-danger')
                        $('.add_sched[data-id=' + schedid + ']').text('Remove Schedule')
                        $('.add_sched[data-id=' + schedid + ']').removeClass('add_sched')
                        $('.add_sched[data-id=' + schedid + ']').removeClass('btn-primary')
                        add_all_sched()
                    }
                })



            }

            $(document).on('click', '.add_subject', function() {

                var schedinfo = all_sched.filter(x => x.id == selected_sched)
                var isenrolled = 0
                if (student_info[0].studstatus == 1 || student_info[0].studstatus == 2 || student_info[0]
                    .studstatus == 4) {
                    isenrolled = 1
                }

                add_sched_function($(this).attr('data-status'), isenrolled)
            })

            function add_sched_function(status = "regular", isenrolled = 0) {
                $('#conflict_addsched_button').removeAttr('data-id')
                $('#conflict_addsched_button').removeAttr('data-status')
                $.ajax({
                    type: 'GET',
                    url: '/student/loading/student/schedule/add',
                    data: {
                        schedid: selected_sched,
                        studid: stud_id,
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                        allowconflict: allowconflict,
                        status: status
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            if (data[0].section != null) {
                                section = data[0].section
                            }
                            student_sched()
                            Toast.fire({
                                type: 'success',
                                title: data[0].data
                            })
                            allowconflict = 0
                            $('#add_sched_modal').modal('hide')
                            $('#sched_con_modal').modal('hide')
                            student_sched()
                            display_sched_csl('studentloading', stud_id, entype)


                        } else {
                            if (data[0].data == 'conflict') {
                                allowconflict = 1
                                $('#sched_con_holder').removeAttr('hidden')
                                $('#sched_conflict_holder').empty()
                                $('#con_sect').text(data[0].section)
                                $('#con_subj').text(' ( ' + data[0].subjcode + ' ) ' + data[0].subjdesc)
                                $('#con_day').text(data[0].day)
                                $('#con_time').text(data[0].time)
                                $('#sched_con_modal').modal()
                                Toast.fire({
                                    type: 'error',
                                    title: 'conflict'
                                })
                                $('#conflict_addsched_button').attr('data-id', selected_sched)
                                $('#conflict_addsched_button').attr('data-status', status)
                            } else {
                                Toast.fire({
                                    type: 'error',
                                    title: data[0].data
                                })
                            }
                        }
                    },
                    error: function() {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong.'
                        })
                    }
                })

            }


            $(document).on('click', '.mark_as_setionid', function() {

                section = $(this).attr('data-id')

                $.ajax({
                    type: 'GET',
                    url: '/student/loading/student/set/section',
                    data: {
                        section: section,
                        studid: stud_id,
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_semester').val(),
                    },
                    success: function(data) {
                        var temp_idex = all_students.findIndex(x => x.id == stud_id)
                        all_students[temp_idex].sectionid = section
                        if (data[0].status == 1) {
                            student_sched()
                            Toast.fire({
                                type: 'success',
                                title: data[0].data
                            })
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].data
                            })
                        }
                    },
                    error: function() {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong.'
                        })
                    }
                })
            })



        })
    </script>


    <script>
        //studente evaluation


        $(document).on('click', '#print_student_evaluation', function() {
            var temp_studid = $('#filter_student').val()
            window.open('/student/grade/evaluation/print?studid=' + temp_studid, '_blank');
        })


        $(document).on('click', '#student_evaluation', function() {

            var temp_studid = $('#filter_student').val()

            $.ajax({
                type: 'GET',
                url: '/student/grade/evaluation',
                data: {
                    studid: temp_studid,
                },
                success: function(data) {

                    var temp_year = data[0].yearLevel
                    var temp_sem = data[0].semester
                    var temp_eval = data[0].evaluation

                    // $('#eval_course_label').text(temp_eval[0].curriculum[0]curriculumname)
                    $('#eval_course_label').text(data[0].course.courseDesc)
                    $('#eval_curriculum_label').text(data[0].curriculum.curriculumname)
                    $('#eval_studentname_label').text($('#student_fullname').text())

                    $('#student_evaluation_holder').empty()
                    $.each(temp_year, function(a, b) {

                        $.each(temp_sem, function(c, d) {

                            var temp_filtered_eval = temp_eval.filter(x => x.yearId == b
                                .id && x.semesterID == d.id)

                            if (temp_filtered_eval.length > 0) {

                                $('#student_evaluation_holder').append(
                                    '<div class="row"><div class="col-md-12"><table class="table table-sm table-bordered"  width="100%" style="font-size:.7rem !important"><thead ><tr><th colspan="6">' +
                                    b.levelname + ' - ' + d.semester +
                                    '</th></tr><tr><th width="10%">Code</th><th width="40%">Description</th><th wdith="8%"  class="text-center">Lec.</th><th  class="text-center" wdith="8%">Lab.</th><th wdith="8%" class="text-center">Total</th><th class="text-center" width="13%">Grades</th><th class="text-center" width="13%">Remarks</th></tr><thead><tbody id="' +
                                    b.id + '-' + d.id +
                                    '"></tbody></table></div></div>')

                                $.each(temp_filtered_eval, function(e, f) {

                                    var temp_final = f.fg != null ? f.fg : '';
                                    var bg = '';
                                    var remarks = f.fgremarks != null ? f
                                        .fgremarks : '';

                                    if (f.finalgrade != null) {
                                        if (f.fgremarks == 'PASSED') {
                                            bg = 'bg-success';
                                        } else {
                                            bg = 'bg-danger';
                                        }
                                    }

                                    if (f.fg == 'INC') {
                                        bg = 'bg-warning';
                                        remarks = 'INC'
                                    } else if (f.fg == 'DROP') {
                                        bg = 'bg-danger';
                                        remarks = 'DROP'
                                    }


                                    var units = (parseFloat(f.lecunits) +
                                        parseFloat(f.labunits)).toFixed(1)

                                    // if(f.finalgrade != null && f.finalgrade > 75){
                                    //       bg = 'bg-success'
                                    //       remaks = 'PASSED'
                                    // }else if(f.finalgrade != null && f.finalgrade  < 75){
                                    //       bg = 'bg-danger'
                                    //       remaks = 'FAILED'
                                    // }

                                    $('#' + b.id + '-' + d.id).append(
                                        '<tr class="' + bg + '"><td>' + f
                                        .subjCode + '</td><td>' + f
                                        .subjDesc +
                                        '</td><td  class="text-center align-middle">' +
                                        f.lecunits +
                                        '</td><td  class="text-center align-middle">' +
                                        f.labunits +
                                        '</td><td  class="text-center align-middle">' +
                                        units +
                                        '</td><td class="text-center align-middle">' +
                                        temp_final +
                                        '</td><td class="text-center align-middle">' +
                                        remarks + '</td></tr>')
                                })
                            }


                        })
                    })



                    $('#student_evaluation_modal').modal();
                },
                error: function() {
                    Toast.fire({
                        type: 'error',
                        title: 'Something went wrong.'
                    })
                }
            })
        })
    </script>

    <script>
        //studente evaluation

        // $(document).ready(function(){

        get_all_sched()

        function get_all_sched() {
            return false
            $.ajax({
                type: 'GET',
                url: '/student/loading/allsched',
                data: {
                    syid: $('#filter_sy').val(),
                    semid: $('#filter_semester').val()
                },
                success: function(data) {

                    all_sched = data[0].college_classsched
                    all_sched_section = data[0].section
                    all_sched_enrolled = data[0].enrolled
                    all_sched_detail = data[0].scheddetail
                    all_sched_student = data[0].all_stud_sched

                    $('#filter_sched_section').empty();
                    $('#filter_sched_section').append('<option value=""></option>')
                    $('#filter_sched_section').select2({
                        allowClear: true,
                        data: all_sched_section,
                        placeholder: 'All'
                    })


                    var temp_subjcode = [...new Map(all_sched.map(item => [item['subjCode'], item])).values()]
                    $.each(temp_subjcode, function(c, d) {
                        // d.id = d.subjCode
                        d.text = d.subjCode
                    })

                    // $('#filter_sched_subjcode').empty();
                    // $('#filter_sched_subjcode').append('<option value=""></option>')
                    // $('#filter_sched_subjcode').select2({
                    //       allowClear:true,
                    //       data:temp_subjcode,
                    //       placeholder:'All'
                    // })

                    var temp_subjects = [...new Map(all_sched.map(item => [item['subjDesc'], item])).values()]
                    $.each(temp_subjects, function(e, f) {
                        f.text = f.subjDesc
                    })

                    // $('#filter_sched_subjdesc').empty();
                    // $('#filter_sched_subjdesc').append('<option value=""></option>')
                    // $('#filter_sched_subjdesc').select2({
                    //       allowClear:true,
                    //       data:temp_subjects,
                    //       placeholder:'All'
                    // })

                    display_sched_csl('studentloading', stud_id, entype)

                    //      $('#available_sched_modal').modal()
                },
                error: function() {
                    Toast.fire({
                        type: 'error',
                        title: 'Something went wrong.'
                    })
                }
            })
        }

        // $(document).on('change','#filter_sched_section',function(){
        //       display_sched_csl('studentloading',stud_id)
        // })
        // $(document).on('change','#filter_sched_subjdesc',function(){
        //       display_sched_csl('studentloading',stud_id)
        // })
        // $(document).on('change','#filter_sched_subjcode',function(){
        //       display_sched_csl('studentloading',stud_id)
        // })
        // })

        var all_sched = []
        var all_sched_section = []
        var all_sched_enrolled = []
        var all_sched_detail = []
        var all_sched_student = []
        var subjectData = []

        const studentData = [];

        // Function to populate the table
        function studentLoadingTable(data) {

            console.log(data, 'sdfsdfsjdfsdfsdfsdfsdfsdfs');

            $('#studentLoadingTable').DataTable({
                destroy: true,
                data: data,
                columns: [{
                        data: null,
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (row.studstatus === 'ENROLLED') {
                                return '<i class="fas fa-square text-success" style="font-size:16px;"></i>';
                            } else if (row.studstatus == 'LATE ENROLLMENT') {
                                return '<i class="fas fa-square" style="color: #58715f; font-size:16px;"></i>';
                            } else if (row.studstatus === 'DROPPED OUT') {
                                return '<i class="fas fa-square text-danger" style="font-size:16px;"></i>';
                            } else if (row.studstatus === 'WITHDRAWN') {
                                return '<i class="fas fa-square text-warning" style="font-size:16px;"></i>';
                            } else if (row.studstatus === 'TRANSFERRED IN') {
                                return '<i class="fas fa-square text-primary" style="font-size:16px;"></i>';
                            } else if (row.studstatus === 'TRANSFERRED OUT') {
                                return '<i class="fas fa-square" style="color: #fd7e14; font-size:16px;"></i>';
                            } else if (row.studstatus === 'DECEASED') {
                                return '<i class="fas fa-square text-dark" style="font-size:16px;"></i>';
                            } else if (row.studstatus == 'NOT ENROLLED') {
                                return '<i class="fas fa-square text-secondary" style="font-size:16px;"></i>';
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'sid'
                    },
                    {
                        data: 'student'
                    },
                    {
                        data: 'levelname',
                        render: function(data, type, row) {
                            return data ? data : 'N/A'; // Show 'N/A' if null
                        }
                    },
                    {
                        data: 'courseabrv'
                    },
                    {
                        data: 'sectionDesc'
                    },
                    {
                        data: 'date_enrolled'
                    },
                    {
                        data: 'updateddatetime'
                    },
                    {
                        data: null,
                        defaultContent: ''
                    }
                ],
                columnDefs: [{
                        targets: 0,
                        orderable: false,
                        className: 'select-checkbox',
                        render: function(data, type, row) {
                            return '';
                        }
                    },
                    {
                        targets: 3,
                        orderable: false,
                        className: 'select-checkbox',
                        render: function(data, type, row) {
                            if (row.acadprogid == 6) {
                                return '<a>' + row.levelname + '</a>';
                            } else if (row.acadprogid == 8) {
                                return '<a>' + row.collegeDesc + ' - ' + row.levelname + '</a>';
                            }
                        }


                    },
                    {
                        targets: -1,
                        orderable: false,
                        render: function(data, type, row) {
                            if (grade_eval_page == 'Grade Evaluation') {
                                return '<a href="#" class="text-primary view-student-loading" data-id="' +
                                    row
                                    .id + '">View Grade Evaluation</a>';
                            } else if (grade_eval_page == 'Student Information') {
                                return '<a href="#" class="text-primary view-student-loading" data-id="' +
                                    row
                                    .id + '">View Student Info</a>';
                            } else {
                                return '<a href="#" class="text-primary view-student-loading" data-id="' +
                                    row
                                    .id + '">View Student Loading</a>';
                            }

                        }
                    }
                ],
                order: [
                    [2, 'asc']
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                rowGroup: {
                    dataSrc: function(row) {
                        return row.gender === 'MALE' ? 'MALE' : 'FEMALE';
                    },
                    startRender: function(rows, group) {
                        return group === 'MALE' ? 'MALE' : 'FEMALE';
                    }
                },
                drawCallback: function(settings) {

                    var enrolledCount = 0;
                    var lateEnrollmentCount = 0;
                    var droppedOutCount = 0;
                    var withdrawnCount = 0;
                    var transferredInCount = 0;
                    var transferredOutCount = 0;
                    var deceasedCount = 0;
                    var notEnrolledCount = 0;

                    // Loop through the data and update the legend counts
                    data.forEach(function(student) {
                        switch (student.studstatus) {
                            case 'ENROLLED':
                                enrolledCount++;
                                break;
                            case 'LATE ENROLLMENT':
                                lateEnrollmentCount++;
                                break;
                            case 'DROPPED OUT':
                                droppedOutCount++;
                                break;
                            case 'WITHDRAWN':
                                withdrawnCount++;
                                break;
                            case 'TRANSFERRED IN':
                                transferredInCount++;
                                break;
                            case 'TRANSFERRED OUT':
                                transferredOutCount++;
                                break;
                            case 'DECEASED':
                                deceasedCount++;
                                break;
                            case null:
                                notEnrolledCount++;
                                break;
                        }
                    });

                    // Update the legend with the new counts
                    $('#enrolledCount').text('ENROLLED (' + enrolledCount + ')');
                    $('#lateEnrollmentCount').text('LATE ENROLLMENT (' + lateEnrollmentCount + ')');
                    $('#droppedOutCount').text('DROPPED OUT (' + droppedOutCount + ')');
                    $('#withdrawnCount').text('WITHDRAWN (' + withdrawnCount + ')');
                    $('#transferredInCount').text('TRANSFERRED IN (' + transferredInCount + ')');
                    $('#transferredOutCount').text('TRANSFERRED OUT (' + transferredOutCount + ')');
                    $('#deceasedCount').text('DECEASED (' + deceasedCount + ')');
                    $('#notEnrolledCount').text('NOT ENROLLED (' + notEnrolledCount + ')');
                }
            });
        }


        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#StudentSection').select2({
                placeholder: 'Select a section',
                allowClear: true
            });

            $('#sectionSelect').select2({
                placeholder: 'All',
                allowClear: true
            })

            // $('#studentLoadingTable').on('click', 'a.view-student-loading', function(e) {
            //     e.preventDefault();
            //     currentStudentId = $(this).data('id');
            //     openStudentInformationModal(currentStudentId);
            // });

            // function openStudentInformationModal(studentId) {
            //     $.ajax({
            //         url: '/student/loading/Student-Information',
            //         method: 'GET',
            //         data: {
            //             studentId: studentId, // Use the studentId passed to the function
            //             syid: $('#filter_sy').val(),
            //             semid: $('#filter_semester').val()
            //         },
            //         dataType: 'json',
            //         success: function(response) {
            //             // Populate form fields with student data
            //             $('#studentIdNo').val(response.sid || '');
            //             $('#firstName').val(response.firstname || '');
            //             $('#middleName').val(response.middlename || '');
            //             $('#lastName').val(response.lastname || '');
            //             // $('#')
            //             // ... (populate other fields)

            //             // // Check if student has a section and year level
            //             // if (response.sectionid && response.levelid) {
            //             //     $('#StudentSection').val(response.sectionid).trigger('change');
            //             //     // $('#StudentSection').prop('disabled', true);
            //             //     loadStudentScheduleBySection(response.sectionid, response.studid);
            //             // } else {
            //             //     $('#StudentSection').val(null).trigger('change');
            //             //     // $('#StudentSection').prop('disabled', false);
            //             //     loadAvailableSections(response.yearLevel);
            //             // }

            //             // Show the modal
            //             var studentLoadingModal = new bootstrap.Modal(document.getElementById(
            //                 'studentLoadingModal'));
            //             studentLoadingModal.show();
            //         },
            //         error: function(xhr, status, error) {
            //             console.error("Error fetching student data:", error);
            //             alert("Failed to load student data. Please try again later.");
            //         }
            //     });
            // }

            // function loadAvailableSections(yearLevel) {
            //     $.ajax({
            //         url: '/student/loading/available-sections',
            //         method: 'GET',
            //         data: {
            //             yearLevel: yearLevel,
            //             syid: $('#filter_sy').val(),
            //             semid: $('#filter_semester').val()
            //         },
            //         success: function(response) {
            //             $('#StudentSection').empty();
            //             $('#StudentSection').append('<option></option>');
            //             // Check if sections exist in the response
            //             if (response.sections && response.sections.length) {
            //                 $.each(response.sections, function(i, section) {
            //                     $('#StudentSection').append($('<option>', {
            //                         value: section.id,
            //                         text: section.sectionDesc
            //                     }));
            //                 });
            //             } else {
            //                 console.error("No sections found in response.");
            //             }
            //         },
            //         error: function(xhr, status, error) {
            //             console.error("Error loading available sections:", error);
            //         }
            //     });
            // }





            // Populate the table with subjects



            function updateLedger(studid, syid, semid) {
                $.ajax({
                    url: '/api_updateledger',
                    method: 'GET',
                    data: {
                        studid: studid,
                        syid: syid,
                        semid: semid,
                    },
                    async: true, // Ensures the request runs in the background
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error updating ledger:", error);
                    }
                });
            }



            $('#studentSection').select2({
                theme: 'bootstrap4'
            });


            // Initialize Select2 for dropdown menus
            // getstudsection()

            // function getstudsection()
            // {
            //     $('#studentSection').select2({
            //     placeholder: 'Select a section',
            //     allowClear: true,
            //     ajax: {
            //         url: '/student/update-section',
            //         dataType: 'json',
            //         processResults: function(data) {
            //             return {
            //                 results: data.map(section => ({
            //                     id: section.id,
            //                     text: section.sectionDesc
            //                 }))
            //             }
            //         }
            //     }
            // });
            // }
        });

        studentLoadingTable(studentData);
        var studID;
        var grade_eval_page = @json(isset($page) ? $page : '');
        $(document).ready(function() {
            // Event delegation for dynamically created elements
            $('#studentLoadingTable').on('click', 'a.view-student-loading', function(e) {
                e.preventDefault();

                var studentId = $(this).data('id');
                studID = $(this).data('id');
                $('#saveStudentLoading').attr('data-id', studentId)
                $('#button_print_studentloading').attr('data-id', studentId)
                openStudentInformationModal(studentId);
                var sectionId = 'all';
                getUpdatedStudentLoading(studentId, sectionId, 0);
                loadEnrollmentDetailsContent()
                get_student_grades(studentId)
                show_curr();
                check_curriculum();
                show_print();
            });
        });

        $('#button_print_studentloading').hide();
        $('.nav-link').on('click', function() {
            show_print();
        });

        show_print();

        function check_curriculum() {
            $.ajax({
                type: 'GET',
                url: '/student/loading/Student-Information/curriculum',
                data: {
                    studentId: studID,
                },
                success: function(data) {
                    if (data && data.length > 0) {
                        if (grade_eval_page == 'Grade Evaluation') {
                            $('.nav-link').removeClass('active highlighted-tab');
                            $('#student-grades-tab').addClass('active');
                            $('.tab-pane').removeClass('show active d-none');
                            $('#student-grades').addClass('show active');
                        } else if (grade_eval_page == 'Student Information') {
                            $('.nav-link').removeClass('active highlighted-tab');
                            $('#student-info-tab').addClass('active');
                            $('.tab-pane').removeClass('show active d-none');
                            $('#student-info').addClass('show active');
                        } else {
                            $('.nav-link').removeClass('active highlighted-tab');
                            $('#student-loading-tab').addClass('active');
                            $('.tab-pane').removeClass('show active d-none');
                            $('#student-loading').addClass('show active');
                        }
                    } else {
                        $('.tab-pane').addClass('d-none')
                        $('#addCurriculumModal').modal('show')
                    }
                }
            })
        }

        function show_print() {
            if (grade_eval_page == 'Student Information') {
                $('#student-info').addClass('show active');
                $('#button_print_studentloading').show();
            } else {
                $('#button_print_studentloading').hide();
            }
        }

        $(document).on('click', '#button_print_studentloading', function() {
            var syid = $('#filter_sy').val();
            var semid = $('#filter_semester').val();
            var course = $('#filter_course').val();
            var gradelevel = $('#filter_gradelevel').val();

            var url = `/print/all_student_information?sy=${syid}&sem=${semid}&c=${course}&gradelevel=${gradelevel}`;
            window.open(url, '_blank'); // Opens the print page properly
        });



        document.addEventListener('DOMContentLoaded', function() {
            var tabList = document.querySelectorAll('#studentInfoTabs .nav-link');
            tabList.forEach(function(tabEl) {
                tabEl.addEventListener('click', function(event) {
                    event.preventDefault();
                    var tabId = this.getAttribute('data-bs-target');
                    var tabContent = document.querySelector(tabId);

                    // Hide all tab contents
                    document.querySelectorAll('#studentInfoTabsContent .tab-pane').forEach(function(
                        content) {
                        content.classList.remove('show', 'active');
                    });

                    // Deactivate all tabs and remove highlighted-tab class
                    tabList.forEach(function(tab) {
                        tab.classList.remove('active', 'highlighted-tab');
                    });

                    // Activate clicked tab and its content, and add highlighted-tab class
                    this.classList.add('active', 'highlighted-tab');
                    tabContent.classList.add('show', 'active');

                    // Logic to load content for each tab
                    if (tabId === '#student-loading') {
                        loadStudentLoadingContent();
                    } else if (tabId === '#enrollment-details') {
                        loadEnrollmentDetailsContent();
                    } else if (tabId === '#student-grades') {
                        loadStudentGradesContent();
                    } else if (tabId === '#student-info') {
                        loadStudentInformationContent();
                    }
                });
            });
        });


        function loadStudentInformationContent() {}

        function loadStudentLoadingContent() {}




        function loadStudentGradesContent() {}

        // Add event listeners to open the modal
        document.querySelectorAll('.open-student-modal').forEach(button => {
            button.addEventListener('click', function() {
                const studentId = this.getAttribute('data-student-id');
                openStudentLoadingModal(studentId);
            });
        });

        // $(document).ready(function() {


        // Initialize Select2 for dropdown menus
        // $('#StudentSection, #sectionSelect').select2({
        //     placeholder: 'Select a section',
        //     allowClear: true
        // });

        // Event listeners for various UI elements




        // Function to group data by section


        // Function to load section schedule

        // Function to group data by section





        // Function to check for schedule conflicts via AJAX





        // Helper function to format days as acronyms



        // Function to load all subjects in a section


        // Handle subject loading
        // $(document).ready(function() {
        // Array to keep track of loaded subjects






        // Event to clear loaded subjects when modal is closed



        // Refactored conflict check function based on your conflict_sched logic

        // });







        // Utility function to toggle the button state
        // function setButtonState($button, isLoading) {
        //     if (isLoading) {
        //         $button.html('Load Subject').removeClass('btn-danger').addClass('btn-success');
        //     } else {
        //         $button.html('Remove Sched').removeClass('btn-success').addClass('btn-danger');
        //     }
        // }


        // });
















        // Utility function to toggle the button state





        // $('#saveStudentLoading').click(function() {
        //     var studentId = $('#studentIdInput').val();
        //     var newSectionId = $('sectionselect').val();
        //     updateSection(studentId, newSectionId);

        // });




        // $(document).ready(function() {
        //     $('#studentLoadingModal').on('show.bs.modal', function(e) {
        //         var studentId = $(this).data('student-id');

        //         if (studentId) {
        //             $('#StudentSection').select2({
        //                 placeholder: 'Select a Section',
        //                 allowClear: true,
        //                 ajax: {
        //                     url: '/student/getSectionSelect',
        //                     method: 'GET',
        //                     dataType: 'json',
        //                     data: function(params) {
        //                         return {
        //                             studentId: studentId,
        //                             search: params.term
        //                         };
        //                     },
        //                     processResults: function(data) {
        //                         return {
        //                             results: data.map(item => ({
        //                                 id: item.id,
        //                                 text: item
        //                                     .sectionDesc
        //                             }))
        //                         };
        //                     }
        //                 }
        //             });
        //         }
        //     });
        // });



        // Initialize Select2 for dropdown menus
        // $('#studentSection').select2({
        //     placeholder: 'Select a section',
        //     allowClear: true,
        //     ajax: {
        //         url: '/student/update-section',
        //         dataType: 'json',
        //         processResults: function(data) {
        //             return {
        //                 results: data.map(section => ({
        //                     id: section.id,
        //                     text: section.sectionDesc
        //                 }))
        //             }
        //         }
        //     }
        // });




        // function load_all_student() {
        //     all_students = [];
        //     $.ajax({
        //         type: 'GET',
        //         url: '/student/loading/students',
        //         data: {
        //             syid: $('#filter_sy').val(),
        //             semid: $('#filter_semester').val(),
        //             course: $('#filter_course').val(),
        //             gradelevel: $('#filter_gradelevel').val(),
        //         },
        //         success: function(data) {

        //             studentLoadingTable(data);
        //         }
        //     });
        // }

        // Listen to the Select2 change event and filter the table by the selected status






        // AJAX function to change the student's course




        // Bind the modal event










































































        // function display_sched_csl('studentloading',stud_id){

        //       var temp_data = all_sched

        //       if($('#filter_sched_section').val() != ""){
        //             temp_data = temp_data.filter(x=>x.sectionID == $('#filter_sched_section').val())
        //       }
        //       if($('#filter_sched_subjdesc').val() != ""){
        //             var subjdesc = all_sched.filter(x=>x.id == $('#filter_sched_subjdesc').val())[0].subjDesc
        //             temp_data = temp_data.filter(x=>x.subjDesc == subjdesc)
        //       }
        //       if($('#filter_sched_subjcode').val() != ""){
        //             var subjcode = all_sched.filter(x=>x.id == $('#filter_sched_subjcode').val())[0].subjCode
        //             temp_data = temp_data.filter(x=>x.subjCode == subjcode)
        //       }

        //       $("#available_sched_datatable").DataTable({
        //             destroy: true,
        //             data:temp_data,
        //             autoWidth: false,
        //             stateSave: true,
        //             lengthChange : false,
        //             order: [
        //                               [ 1, "asc" ]
        //                         ],
        //             columns: [
        //                         { "data": null },
        //                         { "data": "id" },
        //                         { "data": "subjCode" },
        //                         { "data": "subjDesc" },
        //                         { "data": "lecunits" },
        //                         { "data": "labunits" },
        //                         { "data": "capacity" },
        //                         { "data": null },
        //                         { "data": null },
        //                         { "data": null },
        //                   ],
        //             columnDefs: [
        //                   {
        //                         'targets': 0,
        //                         'orderable': false,
        //                         'createdCell':  function (td, cellData, rowData, row, col) {

        //                               if(rowData.selected == 0){
        //                                     var all_loaded_student_count = all_sched_student.filter(x=>x.schedid == rowData.id)

        //                                     $(td)[0].innerHTML = '<button class="btn btn-sm btn-primary btn-block add_sched" style="font-size:.6rem !important; padding:.10rem .25rem !important" data-id="'+rowData.dataid+'">Add Sched</button>'

        //                                     if(all_loaded_student_count.length > 0){
        //                                           if(rowData.capacity <= all_loaded_student_count[0].enrolled){
        //                                                 $(td).text('Maximum')
        //                                                 $(td).addClass('text-center')
        //                                           }
        //                                     }



        //                               }else{
        //                                     $(td)[0].innerHTML = '<button class="btn btn-sm btn-danger btn-block remove_schedule" style="font-size:.6rem !important; padding:.10rem .25rem !important" data-id="'+rowData.dataid+'">Remove Sched</button>'
        //                               }

        //                               $(td).addClass('align-middle')
        //                         }
        //                   },
        //                   {
        //                         'targets': 1,
        //                         'orderable': true,
        //                         'createdCell':  function (td, cellData, rowData, row, col) {
        //                               var sectiondesc = all_sched_section.filter(x=>x.id == rowData.sectionID)
        //                               if(sectiondesc.length > 0){
        //                                     $(td).text(sectiondesc[0].sectionDesc)
        //                               }else{
        //                                     $(td).text(null)
        //                               }
        //                               $(td).addClass('align-middle')

        //                         }
        //                   },
        //                   {
        //                         'targets': 2,
        //                         'orderable': true,
        //                         'createdCell':  function (td, cellData, rowData, row, col) {
        //                               $(td).addClass('align-middle')
        //                         }
        //                   },
        //                   {
        //                         'targets': 3,
        //                         'orderable': true,
        //                         'createdCell':  function (td, cellData, rowData, row, col) {
        //                               $(td).addClass('align-middle')
        //                         }
        //                   },
        //                   {
        //                         'targets': 4,
        //                         'orderable': false,
        //                         'createdCell':  function (td, cellData, rowData, row, col) {
        //                               $(td).addClass('text-center')
        //                               $(td).addClass('align-middle')
        //                         }
        //                   },
        //                   {
        //                         'targets': 5,
        //                         'orderable': false,
        //                         'createdCell':  function (td, cellData, rowData, row, col) {
        //                               $(td).addClass('text-center')
        //                               $(td).addClass('align-middle')
        //                         }
        //                   },  {
        //                         'targets': 6,
        //                         'orderable': false,
        //                         'createdCell':  function (td, cellData, rowData, row, col) {
        //                               $(td).addClass('text-center')
        //                               $(td).addClass('align-middle')
        //                         }
        //                   },
        //                   {
        //                         'targets': 7,
        //                         'orderable': false,
        //                         'createdCell':  function (td, cellData, rowData, row, col) {
        //                               var enrolled_count = all_sched_enrolled.filter(x=>x.schedid == rowData.id)
        //                               var all_loaded_student_count = all_sched_student.filter(x=>x.schedid == rowData.id)
        //                               var enrolled = 0
        //                               var loaded = 0
        //                               if(enrolled_count.length > 0){
        //                                     enrolled = enrolled_count[0].enrolled
        //                               }

        //                               if(all_loaded_student_count.length > 0){
        //                                     loaded = all_loaded_student_count[0].enrolled
        //                               }

        //                               $(td)[0].innerHTML  = '<a href="javascript:void(0)" data-id="'+rowData.id+'"'+'" class="sched_list_students" data-text="'+rowData.subjCode+' : '+rowData.subjDesc+'">'+enrolled+'</a>' + ' / '+ '<a href="javascript:void(0)" data-id="'+rowData.id+'"'+'" class="sched_list_loaded_students" data-text="'+rowData.subjCode+' : '+rowData.subjDesc+'">'+loaded+'</a>'

        //                               $(td).addClass('text-center')
        //                               $(td).addClass('align-middle')
        //                         }


        //                   },
        //                   {
        //                         'targets': 8,
        //                         'orderable': false,
        //                         'createdCell':  function (td, cellData, rowData, row, col) {
        //                               var temp_data = all_sched_detail.filter(x=>x.headerID == rowData.id)
        //                               var temp_sched = []
        //                               if(temp_data.length > 0){
        //                                     $.each(temp_data,function(a,b){
        //                                           var check = temp_sched.filter(x=>x.stime == b.stime && x.etime == b.etime)
        //                                           if(check.length == 0){
        //                                                 temp_sched.push({
        //                                                       'roomname':b.roomname,
        //                                                       'etime':b.etime,
        //                                                       'stime':b.stime,
        //                                                       'days':[]
        //                                                 });
        //                                                 var get_index = temp_sched.findIndex(x=>x.stime == b.stime && x.etime == b.etime)
        //                                                 if(get_index != -1){
        //                                                       temp_sched[get_index].days.push(b.day)
        //                                                 }
        //                                           }else{
        //                                                 var get_index = temp_sched.findIndex(x=>x.stime == b.stime && x.etime == b.etime)
        //                                                 if(get_index != -1){
        //                                                       temp_sched[get_index].days.push(b.day)
        //                                                 }
        //                                           }
        //                                     })
        //                                     var text = ''
        //                                     $.each(temp_sched,function(a,b){
        //                                           var temp_stime = moment(b.stime, 'HH:mm a').format('hh:mm a')

        //                                           text += moment(b.stime, 'HH:mm a').format('hh:mm A')+' - '+moment(b.etime, 'HH:mm a').format('hh:mm A') +' / '
        //                                           $.each(b.days,function(c,d){
        //                                                 text += d == 1 ? 'M' :''
        //                                                 text += d == 2 ? 'T' :''
        //                                                 text += d == 3 ? 'W' :''
        //                                                 text += d == 4 ? 'Th' :''
        //                                                 text += d == 5 ? 'F' :''
        //                                                 text += d == 6 ? 'S' :''
        //                                           })

        //                                           if(b.roomname != null){
        //                                                 text += ' / '+b.roomname
        //                                           }


        //                                           if(temp_sched.length != a+1){
        //                                                 text += ' <br> '
        //                                           }
        //                                     })

        //                                     $(td)[0].innerHTML = text
        //                               }else{
        //                                     $(td).text('No Sched')
        //                               }
        //                               $(td).attr('style','font-size:.7rem !important')
        //                               // $(td).addClass('text-center')
        //                         }
        //                   },
        //                   {
        //                         'targets': 9,
        //                         'orderable': false,
        //                         'createdCell':  function (td, cellData, rowData, row, col) {
        //                               var temp_data = all_sched_detail.filter(x=>x.headerID == rowData.id)
        //                               if(rowData.lastname != null){
        //                                     $(td).text(rowData.lastname+', '+rowData.firstname)
        //                               }else{
        //                                     $(td).text(null)
        //                               }

        //                               $(td).addClass('align-middle')
        //                               $(td).attr('style','font-size:.7rem !important')
        //                         }


        //                   },
        //             ]
        //       });
        // }
    </script>

    <script>
        // $(document).ready(function(){
        //       $(document).on('click','.sched_list_students',function(){
        //             $('#list_label').text('Subject : ' + $(this).attr('data-text'))
        //             $('#student_list_type').text('(Enrolled)')
        //             var temp_schedid = $(this).attr('data-id')
        //             sched_enrolled_learners(temp_schedid)
        //       })

        //       $(document).on('click','.sched_list_loaded_students',function(){
        //             $('#list_label').text('Subject : ' + $(this).attr('data-text'))
        //             $('#student_list_type').text('(Loaded)')
        //             var temp_schedid = $(this).attr('data-id')
        //             sched_loaded_learners(temp_schedid)
        //       })

        // })

        // function sched_enrolled_learners(schedid){

        //       $.ajax({
        //             type:'GET',
        //             url:'/college/section/schedule/schedenrolledlearners',
        //             data:{
        //                   schedid:schedid,
        //                   syid:$('#filter_sy').val(),
        //                   semid:$('#filter_semester').val()
        //             },
        //             success:function(data) {
        //                   $('#enrolled_modal').modal()
        //                   sched_list_table(data)
        //             }
        //       })
        // }

        // function sched_loaded_learners(schedid){
        //       $.ajax({
        //             type:'GET',
        //             url:'/college/section/schedule/schedloadedlearners',
        //             data:{
        //                   schedid:schedid,
        //                   syid:$('#filter_sy').val(),
        //                   semid:$('#filter_semester').val()
        //             },
        //             success:function(data) {
        //                   $('#enrolled_modal').modal()
        //                   sched_list_table(data)

        //             }
        //       })
        // }

        // function sched_list_table(data){
        //       $("#student_list").DataTable({
        //             destroy: true,
        //             lengthChange : false,
        //             data:data,
        //             columns: [
        //                   { "data": "student" },
        //                   { "data": "levelname" },
        //                   { "data": "courseabrv" },
        //             ],
        //             columnDefs: [
        //                   {
        //                         'targets': 0,
        //                         'orderable': true,
        //                         'createdCell':  function (td, cellData, rowData, row, col) {
        //                               var enrolledstattus = ''
        //                               if(rowData.isenrolled == 1){
        //                                     enrolledstattus = '<span class="badge badge-success float-right">Enrolled</span>'
        //                               }
        //                               $(td)[0].innerHTML = rowData.student+enrolledstattus
        //                               $(td).addClass('align-middle')
        //                         }
        //                   },
        //             ]
        //       })
        // }
    </script>
@endsection
