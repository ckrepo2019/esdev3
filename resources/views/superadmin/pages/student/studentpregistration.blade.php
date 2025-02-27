@php
    if (!Auth::check()) {
        header('Location: ' . URL::to('/'), true, 302);
        exit();
    }

    $check_refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->select('refid')->first();

    if (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (Session::get('currentPortal') == 3) {
        $extend = 'registrar.layouts.app';
    } elseif (Session::get('currentPortal') == 4) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 15) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 8) {
        $extend = 'admission.layouts.app2';
    }
    //else if(auth()->user()->type == 3 || auth()->user()->type == 8 ){
    //$extend = 'registrar.layouts.app';
    //}else if(auth()->user()->type == 4){
    //$extend = 'finance.layouts.app';
    //}else if(auth()->user()->type == 15 ){
    //$extend = 'finance.layouts.app';
    //}else if(auth()->user()->type == 14 ){
    //$extend =  'deanportal.layouts.app2';
    elseif (auth()->user()->type == 6) {
        $extend = 'adminportal.layouts.app2';
    } else {
        if (isset($check_refid->refid)) {
            if ($check_refid->refid == 26) {
                $extend = 'registrar.layouts.app';
            } elseif ($check_refid->refid == 28) {
                $extend = 'officeofthestudentaffairs.layouts.app2';
            } elseif ($check_refid->refid == 29) {
                $extend = 'idmanagement.layouts.app2';
            } elseif ($check_refid->refid == 31) {
                $extend = 'guidance.layouts.app2';
            } elseif ($check_refid->refid == 30) {
                $extend = 'encoder.layouts.app2';
            }
        }
    }

    $refid = $check_refid->refid;

@endphp

@extends($extend)

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/jquery-image-viewer-magnify/css/jquery.magnify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/jquery-image-viewer-magnify/css/magnify-bezelless-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <style>
        .select2-container--default .select2-selection--single .select2-selection__clear {
            right: -8px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-left: 0 !important;
        }

        .table-hover tbody tr:hover td,
        .table-hover tbody tr:hover th {
            background-color: #99ccff;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }

        img {
            border-radius: 0 !important
        }

        .myFont {
            font-size: .8rem !important;
        }

        .tableFixHead {
            overflow: auto;
            height: 100px;
        }

        .tableFixHead thead th {
            position: sticky;
            top: 0;
            background-color: #fff;
            outline: 2px solid #dee2e6;
            outline-offset: -1px;

        }

        .ribbon-wrapper.ribbon-lg .ribbon {
            right: -16px;
            top: 4px;
            width: 160px;
        }

        .enroll,
        .view_enrollment {
            cursor: pointer;
        }

        .form-control-sm-form {
            height: calc(1.4rem + 1px);
            padding: 0.75rem 0.3rem;
            font-size: .875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        /* input[type=search]{
                                      height: calc(1.7em + 2px) !important;
                                } */
        .modal {
            overflow-y: auto;
        }

        .has-error .select2-selection {
            /* border: 1px solid;
                                      border-radius: 4px; */
            border-color: #dc3545 !important;
        }
    </style>
@endsection


@section('content')
    @php
        $schoolinfo = DB::table('schoolinfo')->first();
        $strand = DB::table('sh_strand')
            ->select('id', 'strandname', 'strandcode')
            ->where('deleted', 0)
            ->where('active', 1)
            ->get();
        $courses = DB::table('college_courses')->where('deleted', 0)->get();
        $active_sy = DB::table('sy')->where('isactive', 1)->first()->id;

        $grantee = DB::table('grantee')->select('id', 'description as text')->get();

        $mol = [];

        $sy = DB::table('sy')
            ->orderBy('sydesc', 'desc')
            ->select('id', 'sydesc', 'sydesc as text', 'isactive', 'ended')
            ->get();

        if (
            auth()->user()->type == 17 ||
            auth()->user()->type == 4 ||
            auth()->user()->type == 15 ||
            Session::get('currentPortal') == 4 ||
            Session::get('currentPortal') == 15 ||
            Session::get('currentPortal') == 6 ||
            Session::get('currentPortal') == 6 ||
            $refid == 28
        ) {
            $acadprog = DB::table('academicprogram')->select('academicprogram.*', 'progname as text')->get();
        } elseif (auth()->user()->type == 14 || Session::get('currentPortal') == 14) {
            $acadprog = DB::table('academicprogram')
                ->where('id', 6)
                ->select('academicprogram.*', 'progname as text')
                ->get();
        } else {
            $teacherid = DB::table('teacher')
                ->where('tid', auth()->user()->email)
                ->select('id')
                ->first()->id;

            if (auth()->user()->type == 2 || Session::get('currentPortal') == 2) {
                $acadprog = DB::table('academicprogram')
                    ->where('principalid', $teacherid)
                    ->select('academicprogram.*', 'progname as text')
                    ->get();
            } elseif ($check_refid->refid == 31 || $check_refid->refid == 30) {
                $acadprog = DB::table('teacheracadprog')
                    ->where('teacherid', $teacherid)
                    ->where('syid', $active_sy)
                    ->join('academicprogram', function ($join) {
                        $join->on('teacheracadprog.acadprogid', '=', 'academicprogram.id');
                    })
                    ->where('deleted', 0)
                    ->select('acadprogid as id', 'progname as text', 'academicprogram.acadprogcode')
                    ->distinct('acadprogid')
                    ->get();
            } else {
                $acadprog = DB::table('teacheracadprog')
                    ->where('teacherid', $teacherid)
                    ->where('syid', $active_sy)
                    ->whereIn('acadprogutype', [3, 8])
                    ->join('academicprogram', function ($join) {
                        $join->on('teacheracadprog.acadprogid', '=', 'academicprogram.id');
                    })
                    ->where('deleted', 0)
                    ->select('acadprogid as id', 'progname as text', 'academicprogram.acadprogcode')
                    ->distinct('acadprogid')
                    ->get();
            }
        }

        $acadprog_list = [];
        foreach ($acadprog as $item) {
            array_push($acadprog_list, $item->id);
        }

        $all_gradelevel = DB::table('gradelevel')
            ->where('deleted', 0)
            ->orderBy('sortid')
            ->select('id', 'levelname', 'levelname as text', 'acadprogid')
            ->get();

        $gradelevel = DB::table('gradelevel')
            ->where('deleted', 0)
            ->whereIn('acadprogid', $acadprog_list)
            ->orderBy('sortid')
            ->select('id', 'levelname', 'levelname as text', 'acadprogid')
            ->get();

        $studstatus = DB::table('studentstatus')->get();

        $semester = DB::table('semester')->select('id', 'semester', 'semester as text', 'isactive')->get();

        $sections = DB::table('sections')
            ->where('deleted', 0)
            ->select('id', 'sectionname', 'sectionname as text', 'levelid')
            ->get();

        $allstrand = DB::table('sh_sectionblockassignment')
            ->join('sh_block', function ($join) {
                $join->on('sh_sectionblockassignment.blockid', '=', 'sh_block.id');
                $join->where('sh_block.deleted', 0);
            })
            ->join('sh_strand', function ($join) {
                $join->on('sh_block.strandid', '=', 'sh_strand.id');
                $join->where('sh_strand.deleted', 0);
            })
            ->where('sh_sectionblockassignment.deleted', 0)
            ->select('strandid as id', 'sectionid', 'strandcode', 'strandcode as text', 'syid')
            ->distinct()
            ->get();

        $curriculum = DB::table('college_curriculum')
            ->where('deleted', 0)
            ->select('id', 'courseID', 'curriculumname as text')
            ->get();

        $enrollmentsetuptype = DB::table('early_enrollment_setup_type')
            ->where('deleted', 0)
            ->select('id', 'description', 'description as text')
            ->get();

        // $enrollmentsetup = DB::table('early_enrollment_setup')
        //                         ->where('isactive',1)
        //                         ->select('acadprogid')
        //                         ->get();

    @endphp


    <div class="modal shadow fade" id="enrollment_modal" style="display: none;" aria-hidden="true" data-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Enrollment Information (<span
                            id="student_name"></span>)</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card shadow">
                                        <div class="card-body p-2">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h6 for="">Uploaded Documents</h6>
                                                </div>
                                            </div>

                                            <div class="row" id="documents_holder">


                                            </div>
                                            <div class="row ">
                                                <div class="col-md-12">
                                                    <a id="view_requirements" target="blank"
                                                        style="font-size:.9rem !important;"><i class="fas fa-eye"></i> View
                                                        All</a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card shadow">
                                        <div class="card-body p-2">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h6 for="">Ledger</h6>
                                                    <div class="table-responsive tableFixHead" style="height: 150px;">
                                                        <table class="table table-sm table-bordered  mb-0"
                                                            style="font-size:.7rem !important" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="50%" class="p-1">School Year</th>
                                                                    <th width="50%" class="p-1">Balance</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="balance_history">

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
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card shadow">
                                        <div class="card-body p-2">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button class="btn btn-sm btn-default" id="view_update_student_info"><i
                                                            class="fa fa-user"></i> Student Information</button>
                                                    <button class="btn btn-danger btn-sm float-right ml-2"
                                                        id="mark_as_inactive"><i class="fa fa-ban mr-1"></i> Mark as
                                                        Inactive</button>
                                                    <button hidden class="btn btn-success btn-sm float-right ml-2"
                                                        id="mark_as_active"><i class="fa fa-ban mr-1"></i> Mark as
                                                        Active</button>
                                                    <button class="btn btn-danger btn-sm float-right"
                                                        id="delete_student_button"><i class="fa fa-trash-alt"></i> Delete
                                                        Student</button>
                                                    {{-- <a class="btn btn-sm btn-default is_college" href="#" id="student_cor"><i class="fa fa-print"></i> COR</a> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card shadow">
                                        <div class="card-body p-2">
                                            <h6 for="">Enrollment Message Reciever</h6>
                                            <table class="table table-sm table-bordered"
                                                style="font-size:.7rem !important; " width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="30%" class="p-1">Student</th>
                                                        <th width="70%" class="p-1">Parent</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="enrollment_message_receiever">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card shadow">
                                        <div class="card-body p-2">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h6 for="">New Information Available</h6>
                                                    <div class="table-responsive tableFixHead" style="height: 150px;">
                                                        <table class="table table-sm table-bordered"
                                                            style="font-size:.7rem !important; " width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="30%" class="p-1">Label</th>
                                                                    <th width="35%" class="p-1">Current</th>
                                                                    <th width="35%" class="p-1">New</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="new_update_history">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-12" id="info_footer_holder" hidden>
                                                    <button class="btn btn-primary btn-sm"
                                                        style="font-size:.7rem !important"
                                                        id="udpate_student_information">Update New Information</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card shadow mb-0 h-100">
                                        <div class="card-body p-2">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h6 for="">Enrollment History</h6>
                                                    <div class="table-responsive tableFixHead" style="height: 150px;">
                                                        <table class="table table-sm table-bordered  mb-0"
                                                            style="font-size:.7rem !important" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="21%" class="p-1">School Year</th>
                                                                    <th width="12%" class="p-1 align-middle"
                                                                        style="font-size:.6rem !important">Grade Level</th>
                                                                    <th width="47%" class="p-1">Section</th>
                                                                    <th width="14%" class="p-1 align-middle"
                                                                        style="font-size:.6rem !important">Date Enrolled
                                                                    </th>
                                                                    <th width="6%" class="p-1"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="enrollment_history">

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
                        <div class="col-md-3">
                            <div class="card shadow mb-0 h-100">
                                <div class="ribbon-wrapper ribbon-lg" id="ribbon_holder">
                                    <div class="ribbon bg-success" id="ribbon_text"></div>
                                </div>
                                <div class="card-body" style="font-size: .8rem! important">

                                    <label for="" id="sem_sy_label"></label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="" class="mb-1">Current Grade Level</label>
                                            <p id="label_currentgradelevel" class="mb-2"></p>
                                        </div>
                                    </div>
                                    <div class="row" hidden id="label_strand_holder">
                                        <div class="col-md-12">
                                            <label for="" class="mb-1">Strand to Enroll</label>
                                            <p id="label_strand" class="mb-2"></p>
                                        </div>
                                    </div>
                                    <div class="row" hidden>
                                        <div class="col-md-12 form-group mb-2">
                                            <label for="" class="mb-1">School Year</label>
                                            <select class="form-control select2 form-control-sm" id="input_sy">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group mb-2">
                                            <label for="" class="mb-1">Grade Level To Enroll</label>
                                            <select class="form-control select2 form-control-sm" id="input_gradelevel">
                                                @foreach ($gradelevel as $item)
                                                    <option value="{{ $item->id }}">{{ $item->levelname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" hidden>
                                        <div class="col-md-12 form-group mb-2">
                                            <label for="" class="mb-1">Semester</label>
                                            <select class="form-control select2 form-control-sm" id="input_sem">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row is_college is_dcc" hidden>
                                        <div class="col-md-12 form-group mb-2">
                                            <label for="" class="mb-1">Course</label>
                                            <select class="form-control select2 form-control-sm" id="input_course">
                                                <option value="">Select Course</option>
                                                @foreach ($courses as $item)
                                                    <option value="{{ $item->id }}">{{ $item->courseabrv }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div id="stdprgEnrollmentForm">
                                        <div class="row is_college" hidden>
                                            <div class="col-md-6 form-group mb-2">
                                                <label for="" class="mb-1">Enrolled Units</label>
                                                <p id="units_enrolled" class="mb-0"></p>
                                            </div>
                                            <div class="col-md-6 form-group mb-2">
                                                <label for="" class="mb-1">Enrolled Subjects</label>
                                                <p id="subjects_enrolled" class="mb-0"></p>
                                            </div>
                                            <div class="col-md-12 form-group mb-2">
                                                <label for="" class="mb-1">Regular Status</label>
                                                <select class="form-control select2 form-control-sm" id="input_regStatus">
                                                    <option value="REGULAR">REGULAR</option>
                                                    <option value="IRREGULAR">IRREGULAR</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" id="section_holder">
                                            <div class="col-md-12 form-group mb-2">
                                                <label for="" class="mb-1">Section</label>
                                                <select class="form-control select2 form-control-sm" id="input_section">

                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" id="input_grantee_holder">
                                            <div class="col-md-12 form-group mb-2">
                                                <label for="" class="mb-1">Grantee</label>
                                                <select class="form-control select2 form-control-sm" id="input_grantee">

                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" id="mol_holder" hidden>
                                            <div class="col-md-12 form-group mb-2">
                                                <label for="" class="mb-1">Mode of Learning</label>
                                                <select class="form-control select2 form-control-sm" id="input_mol">

                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" id="strand_holder" hidden>
                                            <div class="col-md-12 form-group mb-2">
                                                <label for="" class="mb-1">Strand</label>
                                                <select class="form-control select2 form-control-sm" id="input_strand">

                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 form-group mb-2">
                                                <label for="" class="mb-1">Student Status</label>
                                                <select class="form-control select2 form-control-sm" id="input_studstat">
                                                    @foreach ($studstatus as $item)
                                                        @if ($item->id == 1)
                                                            <option value="{{ $item->id }}" selected="selected">
                                                                {{ $item->description }}</option>
                                                        @else
                                                            <option value="{{ $item->id }}">{{ $item->description }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" id="remarks_holder" hidden>
                                            <div class="col-md-12 form-group mb-2">
                                                <label for="" class="mb-1">Remarks</label>
                                                <textarea class="form-control form-control-sm" id="en_remarks"></textarea>
                                            </div>
                                            <div class="col-md-12 form-group mb-2">
                                                <label for="" class="mb-1"><span
                                                        id="status_date_label">Status</span> Date</label>
                                                <input class="form-control form-control-sm" id="input_studstatdate"
                                                    type="date" max="9999-12-31">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 form-group mb-2">
                                                <label for="" class="mb-1">Enrollment Date</label>
                                                <input class="form-control form-control-sm" id="input_enrollmentdate"
                                                    type="date" max="9999-12-31">
                                            </div>
                                        </div>
                                        {{-- <div class="row">
                                                            <div class="col-md-12 form-group mb-2">
                                                                  <div class="icheck-primary d-inline pt-2">
                                                                        <input type="checkbox" id="isEarly" >
                                                                        <label for="isEarly">Early Enrollment
                                                                        </label>
                                                                  </div>
                                                            </div>
                                                      </div> --}}
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button class="btn btn-primary btn-sm mt-2"
                                                    id="readytoenroll_student_button">Approve</button>

                                                <button class="btn btn-danger btn-sm mt-2"
                                                    id="cancel_readytoenroll_student_button">Cancel Approve</button>
                                                <button class="btn btn-danger btn-sm mt-2"
                                                    id="cancel_nodp_student_button">Cancel No DP</button>
                                                <button class="btn btn-primary btn-sm mt-2" id="nodp_student_button">Allow
                                                    no DP</button>
                                                <button class="btn btn-primary btn-sm mt-2"
                                                    id="enroll_student_button">Enroll Student</button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mt-2">
                                                <p id="enrollment_button_text"></p>
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

    <div class="modal fade" id="add_stud_prereg_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Student to pregistration Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-primary btn-sm" id="create_new_student">Create New Student</button>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="fas fa-book mr-1"></i>School Year</strong>
                            <p class="text-muted" id="label_sy">--</p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-book mr-1"></i>Semester</strong>
                            <p class="text-muted" id="label_sem">--</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Student List (<span id="student_count"></span>)</label>
                            <select class="form-control select2 form-control-sm" id="input_add_student">
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Grade Level to Enroll</label>
                            <select class="form-control select2 form-control-sm" id="input_add_gradelevel" disabled>
                                <option value="">Select Grade Level</option>
                                @foreach ($all_gradelevel as $item)
                                    <option value="{{ $item->id }}">{{ $item->levelname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group" id="input_add_strand_holder" hidden>
                            <label for="">Strand to Enroll</label>
                            <select class="form-control select2 form-control-sm" id="input_add_strand">
                                <option value="">Select Strand</option>
                                @foreach ($strand as $item)
                                    <option value="{{ $item->id }}">{{ $item->strandname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group" id="input_add_course_holder" hidden>
                            <label for="">Course to Enroll</label>
                            <select class="form-control select2 form-control-sm" id="input_add_course">
                                <option value="">Select Course</option>
                                @foreach ($courses as $item)
                                    <option value="{{ $item->id }}">{{ $item->courseDesc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group mb-2">
                            <label for="" class="mb-1">Admission Type</label>
                            <select class="form-control select2 form-control-sm" id="input_addtype_old">

                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary btn-sm" id="add_stud_prereg_button">Register Student</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="reservation_list_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Reservation List</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="">Status</label>
                            <select class="form-control select2 form-control-sm" id="filter_status_reservation">
                                <option value="0">Pending</option>
                                <option value="1">Added to Prereg</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <table class="table-hover table table-striped table-sm table-bordered"
                                id="reservation_list_table" width="100%" style="font-size:.8rem !important">
                                <thead>
                                    <tr>
                                        <th width="15%" data-id="0">ID #</th>
                                        <th width="40%" data-id="1">Students</th>
                                        <th width="15%" data-id="1">Grade Level</th>
                                        <th width="15%">Payment</th>
                                        <th width="15%"></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="vac_list_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Vaccine Information</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-2 form-group mb-2">
                                <label for="" class="mb-1">Vaccince Status</label>
                                <select name="" id="filter_vac_vactype"
                                    class="form-control form-control-sm-form select2">
                                    <option value="">All</option>
                                    <option value="1">Vaccinated</option>
                                    <option value="0">Unvaccinated</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="font-size:15px !important">
                            <table class="table-hover table table-striped table-sm table-bordered" id="vac_list_datatable"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th width="20%">Student</th>
                                        <th width="10%">Status</th>
                                        <th width="10%" style="font-size:13px !important">Vacc. Card #</th>
                                        <th width="12%">1st Dose</th>
                                        <th width="8%" class="p-0 align-middle text-center"
                                            style="font-size:.7rem !important">1st Dose Date</th>
                                        <th width="12%">2nd Dose</th>
                                        <th width="8%" class="p-0 align-middle text-center"
                                            style="font-size:.7rem !important">2nd Dose Date</th>
                                        <th width="12%">Booster</th>
                                        <th width="8%" class="p-0 align-middle text-center"
                                            style="font-size:.75rem !important">Booster Date</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <hr class="mb-2">
                    <div class="row text-center">
                        <div class="col-md-12">
                            | <a href="#" class="badge_vacc" data-id="1"><span class="badge">Vaccinated (
                                    <span class="badge_vacc_count" data-id="1">0</span> )</span> </a> | <a
                                href="#" class="badge_vacc" data-id="0"><span class="badge">Unvaccinated (
                                    <span class="badge_vacc_count" data-id="0">0</span> )</span> </a> |
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="printable_list_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Printables</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0" id="printable_list_holder">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update_student_contactinformation" style="display: none;" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Student Contact Information Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="">Student Contact</label>
                            <input id="usci_scontact" class="form-control form-control-sm" placeholder="09XX-XXXX-XXXX">
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12 pt-2">
                                    <label style="font-size: 13px !important" class="text-danger mb-0"><b>In case of
                                            emergency ( Recipient for News, Announcement and School Information)</b></label>
                                </div>
                                <div class="col-md-4 pt-1">
                                    <div class="icheck-success d-inline">
                                        <input class="form-control" type="radio" id="usci_father" name="usci_incase"
                                            value="1" required>
                                        <label for="usci_father">Father
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 pt-1">
                                    <div class="icheck-success d-inline">
                                        <input class="form-control" type="radio" id="usci_mother" name="usci_incase"
                                            value="2" required>
                                        <label for="usci_mother">Mother
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 pt-1">
                                    <div class="icheck-success d-inline">
                                        <input class="form-control" type="radio" id="usci_guardian" name="usci_incase"
                                            value="3" required>
                                        <label for="usci_guardian">Guardian
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="">Father Contact #</label>
                            <input id="usci_fcontact" class="form-control form-control-sm" placeholder="09XX-XXXX-XXXX">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="">Mother Contact #</label>
                            <input id="usci_mcontact" class="form-control form-control-sm" placeholder="09XX-XXXX-XXXX">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="">Guardian Contact #</label>
                            <input id="usci_gcontact" class="form-control form-control-sm" placeholder="09XX-XXXX-XXXX">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary btn-sm" id="update_student_contact_info">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="student_info_modal" style="display: none;" aria-hidden="true" data-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Student Information</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0" style="font-size:.9rem !important">
                    <div class="row">
                        <div class="col-md-12 table-responsive " style="height: 476px;" id="studinfo_holder">
                            <div class="row mb-2">
                                <div class="col-md-12 bg-primary pt-1">
                                    <h6 class="mb-1">Student Information</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">SID</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_sid_new"
                                        placeholder="SID" style="height: calc(1.7rem + 1px);" autocomplete="off"
                                        disabled>
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">Alternative SID</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_altsid_new"
                                        placeholder="Alternate SID" style="height: calc(1.7rem + 1px);"
                                        autocomplete="off">
                                </div>
                            </div>
                            <hr class="mb-1 mt-1">
                            <div class="row">
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1"><span class="text-danger">*</span>Grade Level to
                                        enroll</label>
                                    <select name="" id="input_gradelevel_new"
                                        class="form-control form-control-sm-form select2 is-required is-invalid">
                                        <option value="">Select Grade Level</option>
                                        @foreach ($gradelevel as $item)
                                            <option value="{{ $item->id }}">{{ $item->levelname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">LRN</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_lrn_new"
                                        placeholder="LRN" style="height: calc(1.7rem + 1px);" autocomplete="off">
                                </div>
                                <div class="col-md-2 form-group mb-2">
                                    <label for="" class="mb-1">Student Type</label>
                                    <select name="" id="input_studtype_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off">
                                        <option value="new" selected="selected">New</option>
                                        <option value="old">Old</option>
                                        <option value="transferee">Transferee</option>
                                        <option value="returnee">Returnee</option>
                                    </select>
                                </div>
                                <div class="col-md-2 form-group mb-2">
                                    <label for="" class="mb-1">Grantee</label>
                                    <select name="" id="input_grantee_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off">

                                        <option value="1" selected="selected">REGULAR</option>
                                        <option value="2">ESC</option>
                                        <option value="3">VOUCHER</option>
                                    </select>
                                </div>
                                <div class="col-md-2 form-group mb-2">
                                    <label for="" class="mb-1">Gov. Assistance</label>
                                    <select name="" id="input_pantawid_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off">
                                        <option value="0" selected="selected">Select Government Assistance</option>
                                        <option value="1">4PS</option>
                                        <option value="2">IPS</option>
                                        <option value="3">UCT</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group mb-2" hidden>
                                    <label for="" class="mb-1">Admission Type</label>
                                    <select name="" id="input_addtype_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off"></select>
                                </div>

                                <div class="col-md-7 form-group mb-2" id="input_strand_holder" hidden>
                                    <label for="" class="mb-1"><span class="text-danger">*</span>Strand</label>
                                    <select name="" id="input_strand_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off">
                                        <option value="">Select Strand</option>
                                        @foreach ($strand as $item)
                                            <option value="{{ $item->id }}">{{ $item->strandname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-7 form-group mb-2 input_course_holder" hidden>
                                    <label for="" class="mb-1"><span class="text-danger">*</span>Course</label>
                                    <select name="" id="input_course_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off">
                                        <option value="">Select Course</option>
                                        @foreach ($courses as $item)
                                            <option value="{{ $item->id }}">{{ $item->courseDesc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5 form-group mb-2 input_course_holder" hidden>
                                    <label for="" class="mb-1"><span
                                            class="text-danger">*</span>Curriculum</label>
                                    <select name="" id="input_curriculum_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off">
                                        <option value="">Select Curriculum</option>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">Mode of learning</label>
                                    <select name="" id="input_mol_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off">
                                        <option value="">Select Mode of Learning</option>
                                    </select>
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1"><span class="text-danger">*</span>First
                                        Name</label>
                                    <input onkeyup="this.value = this.value.toUpperCase();" type="text"
                                        class="form-control form-control-sm-form is-required" id="input_fname_new"
                                        placeholder="First Name" autocomplete="off">
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">Middlename</label>
                                    <input onkeyup="this.value = this.value.toUpperCase();" type="text"
                                        class="form-control form-control-sm-form" id="input_mname_new"
                                        placeholder="Middle Name" autocomplete="off">
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1"><span class="text-danger">*</span>Last
                                        Name</label>
                                    <input onkeyup="this.value = this.value.toUpperCase();" type="text"
                                        class="form-control form-control-sm-form is-required" id="input_lname_new"
                                        placeholder="Last Name" autocomplete="off">
                                </div>
                                <div class="col-md-1 form-group mb-2">
                                    <label for="" class="mb-1">Suffix</label>
                                    <input onkeyup="this.value = this.value.toUpperCase();" type="text"
                                        class="form-control form-control-sm-form" id="input_suffix_new"
                                        placeholder="Suffix" autocomplete="off">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1"><span class="text-danger">*</span>Birth
                                        Date</label>
                                    <input class="form-control form-control-sm-form is-required" id="input_dob_new"
                                        type="date" autocomplete="off" max="9999-12-31">
                                </div>
                                <div class="col-md-2 form-group mb-2">
                                    <label for="" class="mb-1"><span class="text-danger">*</span>Gender</label>
                                    <select name="" id="input_gender_new"
                                        class="form-control form-control-sm-form select2 is-required" autocomplete="off">
                                        <option value="MALE">MALE</option>
                                        <option value="FEMALE">FEMALE</option>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1"><span
                                            class="text-danger">*</span>Nationality</label>
                                    <select name="" id="input_nationality_new"
                                        class="form-control form-control-sm-form select2 is-required" autocomplete="off">
                                        @foreach (DB::table('nationality')->where('deleted', 0)->get() as $item)
                                            @if ($item->id == 77)
                                                <option value="{{ $item->id }}" selected="selected">
                                                    {{ $item->nationality }}</option>
                                            @else
                                                <option value="{{ $item->id }}">{{ $item->nationality }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1"><span class="text-danger">*</span>Student
                                        Contact #</label>
                                    <input id="input_scontact_new" class="form-control form-control-sm-form is-required"
                                        placeholder="09XX-XXXX-XXXX" autocomplete="off"">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group mb-2">
                                    <label for="" class="mb-1">Student Email</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_semail_new"
                                        placeholder="Student Email" autocomplete="off">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">Religion</label><a href="javascript:void(0)"
                                        class="edit_religion pl-2" hidden><i class="far fa-edit"></i></a>
                                    <a href="javascript:void(0)" class="delete_religion pl-2" hidden><i
                                            class="far fa-trash-alt text-danger"></i></a></label>
                                    <select name="" id="input_religion_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off">
                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">Mother Tongue</label><a
                                        href="javascript:void(0)" class="edit_mothertongue pl-2" hidden><i
                                            class="far fa-edit"></i></a>
                                    <a href="javascript:void(0)" class="delete_mothertongue pl-2" hidden><i
                                            class="far fa-trash-alt text-danger"></i></a></label>
                                    <select name="" id="input_mt_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off"></select>
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">Ethnic Group</label><a href="javascript:void(0)"
                                        class="edit_ethnicgroup pl-2" hidden><i class="far fa-edit"></i></a>
                                    <a href="javascript:void(0)" class="delete_ethnicgroup pl-2" hidden><i
                                            class="far fa-trash-alt text-danger"></i></a></label>
                                    <select name="" id="input_egroup_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-2">
                                    <label for="" class="mb-1">Place of Birth</label>
                                    <input type="text" class="form-control form-control-sm-form" id="pob"
                                        placeholder="Place of Birth" autocomplete="off">
                                </div>
                                <div class="col-md-4 form-group mb-2">
                                    <label for="" class="mb-1">Marital Status</label>
                                    <select name="" id="input_marital_new"
                                        class="form-control form-control-sm-form select2" autocomplete="off">
                                        <option value="SINGLE">Single</option>
                                        <option value="MARRIED">Married</option>
                                        <option value="DIVORCED">Divorced</option>
                                        <option value="SEPARATED">Separated</option>
                                        <option value="WIDOWED">Widowed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-2">
                                    <label for="" class="mb-1">Number of Children in the Family</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_ncf_new"
                                        placeholder="Number of Children in the Family" autocomplete="off"
                                        oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                </div>
                                <div class="col-md-6 form-group mb-2">
                                    <label for="" class="mb-1">Number of Children Enrolled</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_nce_new"
                                        placeholder="Number of Children Enrolled" autocomplete="off"
                                        oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <label class="mb-0">Order in the Family (please check):</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group  mb-2">
                                    <div class="icheck-primary d-inline pt-2 mr-2">
                                        <input type="radio" id="input_oitf_eldest" name="oitf" class="oitf"
                                            value="eldest">
                                        <label for="input_oitf_eldest">eldest</label>
                                    </div>
                                    <div class="icheck-primary d-inline pt-2  mr-2">
                                        <input type="radio" id="input_oitf_2nd" name="oitf" class="oitf"
                                            value="2nd">
                                        <label for="input_oitf_2nd">2<sup>nd</sup></label>
                                    </div>
                                    <div class="icheck-primary d-inline pt-2  mr-2">
                                        <input type="radio" id="input_oitf_3rd" name="oitf" class="oitf"
                                            value="3rd">
                                        <label for="input_oitf_3rd">3<sup>rd</sup></label>
                                    </div>
                                    <div class="icheck-primary d-inline pt-2 mr-2">
                                        <input type="radio" id="input_oitf_youngest" name="oitf" class="oitf"
                                            value="youngest">
                                        <label for="input_oitf_youngest">youngest</label>
                                    </div>
                                </div>

                                <div class="col-md-1 form-group">
                                    <label for="" class="mb-1">Others:</label>
                                </div>
                                <div class="col-md-5 form-group">
                                    <input type="text" class="form-control form-control-sm-form" id="input_oitf_new"
                                        placeholder="Other" autocomplete="off">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-2">
                                    <label for="" class="mb-1">Monthly Family Income</label>
                                    <input type="text" data-type="currency" class="form-control form-control-sm-form"
                                        id="input_mfi_new" placeholder="Monthly Family Income" autocomplete="off">
                                </div>
                                <div class="col-md-6 form-group mb-2">
                                    <label for="" class="mb-1">Language/s spoken at home</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_lsah_new"
                                        placeholder="Language/s spoken at home" autocomplete="off">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-12 bg-primary pt-1">
                                    <h6 class="mb-1">Student Address</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">Street</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_street_new"
                                        placeholder="Street" autocomplete="off">
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">Barangay</label>
                                    <input type="text" class="form-control form-control-sm-form"
                                        id="input_barangay_new" placeholder="Barangay" autocomplete="off">
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">District</label>
                                    <input type="text" class="form-control form-control-sm-form"
                                        id="input_district_new" placeholder="District" autocomplete="off">
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label for="" class="mb-1">City/Municipality</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_city_new"
                                        placeholder="City" autocomplete="off">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="" class="mb-1">Province</label>
                                    <input type="text" class="form-control form-control-sm-form"
                                        id="input_province_new" placeholder="Province" autocomplete="off">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="" class="mb-1">Region</label>
                                    <input type="text" class="form-control form-control-sm-form" id="input_region_new"
                                        placeholder="Region" autocomplete="off">
                                </div>
                            </div>
                            <div class="row  mb-2">
                                <div class="col-md-12 bg-primary pt-1">
                                    <h6 class="mb-1">Parent/Guardian Information </h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <i style="font-size:12px!important" class="text-danger">Scroll right for more
                                        information</i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 table-responsive ">
                                    <table class="table table-sm table-bordered mb-0" width="100%"
                                        style="width:1300px">
                                        <thead>
                                            <tr>
                                                <th class="p-0" width="6%"></th>
                                                <th class="p-0 text-center" width="11%">First Name</th>
                                                <th class="p-0 text-center" width="11%">Middle Name</th>
                                                <th class="p-0 text-center" width="11%">Last Name</th>
                                                <th class="p-0 text-center" width="4%">Suffix</th>
                                                <th class="p-0 text-center" width="9%">Contact #</th>
                                                <th class="p-0 text-center" width="13%" hidden>Occupation/Relation</th>
                                                <th class="p-0 text-center" width="17%" hidden>Educational Attainment
                                                </th>
                                                <th class="p-0 text-center" width="18%">Home Address</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">Father</th>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_father_fname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_father_mname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_father_lname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_father_sname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_father_contact_new" placeholder="09XX-XXXX-XXXX"
                                                        autocomplete="off"></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="fha" autocomplete="off"
                                                        placeholder="Father Home Address"></td>
                                            </tr>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">Mother</th>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_mother_fname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_mother_mname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_mother_lname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_mother_sname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_mother_contact_new" placeholder="09XX-XXXX-XXXX"
                                                        autocomplete="off"></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="mha" autocomplete="off"
                                                        placeholder="Mother Home Address"></td>
                                            </tr>
                                            <tr>
                                                <th class="p-1 align-middle pl-1" colspan="1"
                                                    style="font-size:7pt !important">Mother Maiden Name </th>
                                                <td class="p-1" colspan="3"><input
                                                        class="form-control form-control-sm-form"
                                                        id="input_mothermaidename_new" autocomplete="off"></td>

                                                <td class="p-1" colspan="2"></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1"></td>
                                            </tr>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">Guardian</th>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_guardian_fname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_guardian_mname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_guardian_lname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_guardian_sname_new" autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="input_guardian_contact_new" placeholder="09XX-XXXX-XXXX"
                                                        autocomplete="off"></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1" hidden></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="gha" autocomplete="off"
                                                        placeholder="Guardian Home Address"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label style="font-size: 13px !important" class="text-danger mb-0"><span
                                                    class="text-danger">*</span><b>In case of emergency ( Recipient for
                                                    News, Announcement and School Information)</b></label>
                                        </div>
                                        <div class="col-md-4 pt-1">
                                            <div class="icheck-success d-inline">
                                                <input class="form-control" type="radio" id="father"
                                                    name="incase" value="1" required>
                                                <label for="father">Father
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 pt-1">
                                            <div class="icheck-success d-inline">
                                                <input class="form-control" type="radio" id="mother"
                                                    name="incase" value="2" required>
                                                <label for="mother">Mother
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 pt-1">
                                            <div class="icheck-success d-inline">
                                                <input class="form-control" type="radio" id="guardian"
                                                    name="incase" value="3" required>
                                                <label for="guardian">Guardian
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row  mb-2">
                                <div class="col-md-12 bg-primary pt-1">
                                    <h6 class="mb-1">SOCIO ECONOMIC PROFILE</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 table-responsive ">
                                    <table class="table table-sm table-bordered mb-0" width="100%"
                                        style="width:1300px">
                                        <thead>
                                            <tr>
                                                <th class="p-0" width="10%"></th>
                                                <th class="p-0 text-center" width="18%">Educational Attainment</th>
                                                <th class="p-0 text-center" width="18%">Occupation</th>
                                                <th class="p-0 text-center" width="18%">Monthly Income</th>
                                                <th class="p-0 text-center" width="18%">Other Source of Income</th>
                                                <th class="p-0 text-center" width="18%">Ethnicity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th class="p-0" width="10%">Father</th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="fea"
                                                        autocomplete="off" placeholder="Father Educational Attainment">
                                                </th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form"
                                                        id="input_father_occupation_new" autocomplete="off"
                                                        placeholder="Occupation"></th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="fmi"
                                                        autocomplete="off" placeholder="Father Monthly Income"></th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="fosoi"
                                                        autocomplete="off" placeholder="Father Other Source of Income">
                                                </th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="fethnicity"
                                                        autocomplete="off" placeholder="Father Ethnicity"></th>
                                            </tr>
                                            <tr>
                                                <th class="p-0" width="10%">Mother</th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="mea"
                                                        autocomplete="off" placeholder="Mother Educational Attainment">
                                                </th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form"
                                                        id="input_mother_occupation_new" autocomplete="off"
                                                        placeholder="Occupation"></th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="mmi"
                                                        autocomplete="off" placeholder="Mother Monthly Income"></th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="gosoi"
                                                        autocomplete="off" placeholder="Mother Other Source of Income">
                                                </th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="methnicity"
                                                        autocomplete="off" placeholder="Mother Ethnicity"></th>
                                            </tr>
                                            <tr>
                                                <th class="p-0" width="10%">Guardian</th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="gea"
                                                        autocomplete="off"
                                                        placeholder="Guardian Educational Attainment"></th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form"
                                                        id="input_guardian_relation_new" autocomplete="off"
                                                        placeholder="Occupation/Relation"></th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="gmi"
                                                        autocomplete="off" placeholder="Guardian Monthly Income"></th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="gosoi"
                                                        autocomplete="off"
                                                        placeholder="Guardian Other Source of Income"></th>
                                                <th class="p-0 text-center" width="18%"><input
                                                        class="form-control form-control-sm-form" id="gethnicity"
                                                        autocomplete="off" placeholder="Guardian Ethnicity"></th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row mb-2 mt-2">
                                <div class="col-md-12 bg-primary pt-1">
                                    <h6 class="mb-1">Educational Information</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group mb-2">
                                    <label for="" class="mb-1">Name of School Last Attended</label>
                                    <input type="text" class="form-control form-control-sm-form"
                                        id="last_school_att" placeholder="Name of School Last Attended">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-2">
                                    <label for="" class="mb-1">Grade Level in that School</label>
                                    {{-- <input type="text" class="form-control form-control-sm-form" id="last_school_lvlid" placeholder="Grade Level in that School"> --}}
                                    <select name="" id="last_school_lvlid"
                                        class="form-control form-control-sm-form select2">
                                        <option value="">Grade Level in that School</option>
                                        @foreach ($gradelevel as $item)
                                            <option value="{{ $item->id }}">{{ $item->levelname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mb-2">
                                    <label for="" class="mb-1">School’s Contact No.</label>
                                    <input type="text" class="form-control form-control-sm-form"
                                        id="last_school_no" placeholder="09XX-XXXX-XXXX">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group mb-2">
                                    <label for="" class="mb-1">Complete Mailing Address of School last
                                        Attended</label>
                                    <input type="text" class="form-control form-control-sm-form"
                                        id="last_school_add"
                                        placeholder="Complete Mailing Address of School last Attended">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <table class="table tabl-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th colspan="3" class="p-0  pl-1">Educational Background</th>
                                            </tr>
                                            <tr>
                                                <th class="p-0" width="20%"></th>
                                                <th class="p-0 text-center" width="50%"> School Name</th>
                                                <th class="p-0 text-center" width="15%">S.Y. Graduated</th>
                                                <th class="p-0 text-center" width="15%">School Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">Pre-school</th>
                                                <td class="p-1"> <input class="form-control form-control-sm-form"
                                                        placeholder="Pre-school School Name" id="psschoolname"
                                                        autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="pssy" placeholder="____-_____" autocomplete="off">
                                                </td>
                                                <td class="p-1">
                                                    <select class="form-control form-control-sm select2"
                                                        id="psschooltype">
                                                        <option value=""></option>
                                                        <option value="Public">Public</option>
                                                        <option value="Private">Private</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">Grade School</th>
                                                <td class="p-1"> <input class="form-control form-control-sm-form"
                                                        placeholder="Grade School School Name" id="gsschoolname"
                                                        autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        id="gssy" placeholder="____-_____" autocomplete="off">
                                                </td>
                                                <td class="p-1">
                                                    <select class="form-control form-control-sm select2"
                                                        id="gsschooltype">
                                                        <option value=""></option>
                                                        <option value="Public">Public</option>
                                                        <option value="Private">Private</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">Junior High School</th>
                                                <td class="p-1"> <input class="form-control form-control-sm-form"
                                                        placeholder="Junior High School School Name" id="jhsschoolname"
                                                        autocomplete="off"></td>
                                                <td class="p-1"><input class="form-control form-control-sm-form"
                                                        placeholder="____-_____" id="jhssy" autocomplete="off">
                                                </td>
                                                <td class="p-1">
                                                    <select class="form-control form-control-sm select2"
                                                        id="jhsschooltype">
                                                        <option value=""></option>
                                                        <option value="Public">Public</option>
                                                        <option value="Private">Private</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">Senior High School</th>
                                                <td class="p-1"> <input class="form-control form-control-sm-form"
                                                        placeholder="Senior High School School Name" id="shsschoolname"
                                                        autocomplete="off"></td>
                                                <td class="p-1 text-center"><input
                                                        class="form-control form-control-sm-form"
                                                        placeholder="____-_____" id="shssy" autocomplete="off">
                                                </td>
                                                <td class="p-1">
                                                    <select class="form-control form-control-sm select2"
                                                        id="shsschooltype">
                                                        <option value=""></option>
                                                        <option value="Public">Public</option>
                                                        <option value="Private">Private</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="p-1 align-middle pl-1">College</th>
                                                <td class="p-1"> <input class="form-control form-control-sm-form"
                                                        placeholder="College School Name" id="collegeschoolname"
                                                        autocomplete="off"></td>
                                                <td class="p-1 text-center"><input
                                                        class="form-control form-control-sm-form"
                                                        placeholder="____-_____" id="collegesy" autocomplete="off">
                                                </td>
                                                <td class="p-1">
                                                    <select class="form-control form-control-sm select2"
                                                        id="collegeschooltype">
                                                        <option value=""></option>
                                                        <option value="Public">Public</option>
                                                        <option value="Private">Private</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mb-2 mt-2" id="vacc_med_holder">
                                <div class="col-md-12 bg-primary pt-1">
                                    <h6 class="mb-1">COVID-19 Vaccine / Medical Information</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>Vaccinated:</label>
                                        </div>
                                        <div class="col-md-1 form-group  mb-2">
                                            <div class="icheck-primary d-inline pt-2">
                                                <input type="radio" id="input_vacc_type_yes" name="vacc"
                                                    class="vacc" value="1">
                                                <label for="input_vacc_type_yes">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="icheck-primary d-inline pt-2">
                                                <input type="radio" id="input_vacc_type_no" name="vacc"
                                                    class="vacc" value="0" checked="checked">
                                                <label for="input_vacc_type_no">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group mb-2">
                                    <label class="mb-1">Vaccine (1st Dose) <a href="javascript:void(0)"
                                            class="edit_vaccine pl-2" hidden><i class="far fa-edit"></i></a>
                                        <a href="javascript:void(0)" class="delete_vaccine pl-2" hidden><i
                                                class="far fa-trash-alt text-danger"></i></a></label>
                                    <select id="vacc_type_1st" class="form-control form-control-sm-form">

                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label class="mb-1">1st Dose Date</label>
                                    <input type="date" max="9999-12-31" id="dose_date_1st"
                                        class="form-control form-control-sm-form">
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label class="mb-1">Vaccine(2nd Dose)</label>
                                    <select id="vacc_type_2nd" class="form-control form-control-sm-form">

                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label class="mb-1">2nd Dose Date</label>
                                    <input type="date" max="9999-12-31" id="dose_date_2nd"
                                        class="form-control form-control-sm-form">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group mb-2">
                                    <label class="mb-1">Vaccine (Booster Shot)</label>
                                    <select id="vacc_type_booster" class="form-control form-control-sm-form">

                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label class="mb-1">Booster Dose Date</label>
                                    <input type="date" max="9999-12-31" id="dose_date_booster"
                                        class="form-control form-control-sm-form">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group mb-2">
                                    <label class="mb-1">Vaccination Card #</label>
                                    <input id="vacc_card_id" class="form-control form-control-sm-form"
                                        placeholder="Vaccination Card #e">
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label class="mb-1">Philhealth ID Number</label>
                                    <input id="philhealth" class="form-control form-control-sm-form"
                                        placeholder="Philhealth ID Number">
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label class="mb-1">Blood Type</label>
                                    <a href="javascript:void(0)" class="delete_bloodtype pl-2" hidden><i
                                            class="far fa-trash-alt text-danger"></i></a></label>
                                    <select name="" id="bloodtype"
                                        class="form-control form-control-sm-form select2" autocomplete="off">
                                    </select>
                                    {{-- <input id="bloodtype" class="form-control form-control-sm-form" placeholder="Blood Type"> --}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group mb-2">
                                    <label class="mb-1">Allergies to Medication</label>
                                    <input id="allergy_to_med" class="form-control form-control-sm-form"
                                        placeholder="Allergies to Medication">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group mb-2">
                                    <label class="mb-1">Medical History</label>
                                    <input id="med_his" class="form-control form-control-sm-form"
                                        placeholder="Medical History">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group mb-2">
                                    <label class="mb-1">Other Medical Information</label>
                                    <input id="other_med_info" class="form-control form-control-sm-form"
                                        placeholder="Other Medical Information">
                                </div>
                            </div>
                            <div class="row mb-2 mt-2" id="vacc_med_holder">
                                <div class="col-md-12 bg-primary pt-1">
                                    <h6 class="mb-1">Other Information</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group mb-2">
                                    <label class="mb-1">BEC Cell</label>
                                    <input id="bec_cell" class="form-control form-control-sm-form"
                                        placeholder="BEC Cell">
                                </div>
                                <div class="col-md-3 form-group mb-2">
                                    <label class="mb-1">Chapel / Zone</label>
                                    <input id="chapelzone" class="form-control form-control-sm-form"
                                        placeholder="Chapel / Zone">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label style="font-size: .7rem !important"><i>Scroll down for more information.</i></label>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <button class="btn btn-primary btn-sm" id="create_new_student_button"><i
                                    class="fa fa-save"></i> Save</button>
                            <button class="btn btn-success btn-sm" id="update_student_information_button" hidden><i
                                    class="fa fa-save"></i> Save</button>

                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-default btn-sm float-right" id="enrollment_form" hidden><i
                                    class="fa fa-print"></i> Student Information</button>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="vaccine_form_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Vaccine Type Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Vaccine Description</label>
                            <input class="form-control form-control-sm" id="vaccine_desc">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary" id="create_vaccine_button"><i
                                    class="fa fa-save"></i> Save</button>
                            <button class="btn btn-success btn-primary btn-sm" id="update_vaccine_button" hidden><i
                                    class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="religion_form_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Religion Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Religion Description</label>
                            <input class="form-control form-control-sm" id="religion_desc">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary" id="create_religion_button"><i
                                    class="fa fa-save"></i> Save</button>
                            <button class="btn btn-success btn-primary btn-sm" id="update_religion_button" hidden><i
                                    class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bloodtype_form_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Blood Type Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Blood Type Description</label>
                            <input class="form-control form-control-sm" id="bloodtype_desc">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary" id="create_bloodtype_button"><i
                                    class="fa fa-save"></i> Save</button>
                            <button class="btn btn-success btn-primary btn-sm" id="update_bloodtype_button" hidden><i
                                    class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mt_form_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Mother Tongue Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Mother Tongue Description</label>
                            <input class="form-control form-control-sm" id="mt_desc">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary" id="create_mothertongue_button"><i
                                    class="fa fa-save"></i> Save</button>
                            <button class="btn btn-success btn-primary btn-sm" id="update_mothertongue_button" hidden><i
                                    class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="print_enrollment_list" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Enrollment Printable</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary btn-block btn-sm print_to_filter"
                                data-id="enrollmentbyreligiousaffiliation" style="font-size:.9rem !important">Enrollment
                                by Religious Affiliation</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary btn-block btn-sm print_to_filter"
                                data-id="enrollmentbyethnicgroup" style="font-size:.9rem !important">Number of Students
                                Who Belong To Ethnic Groups</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary btn-block btn-sm print_to_filter"
                                data-id="enrollmentunenrolled" style="font-size:.9rem !important">Unenrolled
                                Learners</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary btn-block btn-sm print_to_filter" data-id="studentprofile"
                                style="font-size:.9rem !important">Student Profile</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="printable_filter_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Printable Filter</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-12">Academic Program</div>
                    </div>
                    <div class="row">
                        @foreach ($acadprog as $item)
                            <div class="col-md-4">
                                <div class="icheck-primary d-inline mr-3">
                                    <input type="checkbox" id="AP_{{ $item->id }}" class="print_filter_acadprog"
                                        value="{{ $item->id }}" checked>
                                    <label for="AP_{{ $item->id }}">{{ $item->acadprogcode }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-md-12">Grade Level</div>
                    </div>
                    <div class="row">
                        @foreach ($gradelevel as $item)
                            <div class="col-md-4">
                                <div class="icheck-primary d-inline mr-3">
                                    <input type="checkbox" id="glvl_{{ $item->id }}"
                                        class="print_filter_gradelevel" value="{{ $item->id }}" checked
                                        data-acad="{{ $item->acadprogid }}">
                                    <label
                                        for="glvl_{{ $item->id }}">{{ str_replace(' COLLEGE', '', $item->levelname) }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12 form-group">
                            <button class="btn btn-primary btn-block btn-sm" id="print_enrollment_proceed"
                                style="font-size:.9rem !important"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ethnicgroup_form_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Ethnic Group Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Ethnic Group Description</label>
                            <input class="form-control form-control-sm" id="ethnicgroup_desc">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary" id="create_ethnicgroup_button"><i
                                    class="fa fa-save"></i> Save</button>
                            <button class="btn btn-success btn-primary btn-sm" id="update_ethnicgroup_button" hidden><i
                                    class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Student Enrollment</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Enrollment</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content pt-0">

        <div class="container-fluid">
            <div class="row" id="no_acad_holder" hidden>
                <div class="col-md-12">
                    <div class="card shadow bg-danger">
                        <div class="card-body p-1">
                            No academic program assigned.
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="info-box shadow-lg">
                        <div class="info-box-content" style="font-size:15px !important">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5><i class="fa fa-filter"></i> Filter</h5>
                                </div>
                                <div class="col-md-8">
                                    <h5 class="float-right">Active S.Y.:
                                        {{ collect($sy)->where('isactive', 1)->first()->sydesc }}</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2  form-group  mb-0">
                                    <label for="" class="mb-1">School Year</label>
                                    <select class="form-control select2 form-control-sm" id="filter_sy">
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
                                <div class="col-md-2  form-group semester_holder">
                                    <label for="" class="mb-1">Semester</label>
                                    <select class="form-control select2 form-control-sm" id="filter_sem">
                                        @foreach ($semester as $item)
                                            @if ($item->isactive == 1)
                                                <option value="{{ $item->id }}" selected="selected">
                                                    {{ $item->semester }}</option>
                                            @else
                                                <option value="{{ $item->id }}">{{ $item->semester }}</option>
                                            @endif
                                            {{-- <option value="{{$item->id}}">{{$item->semester}}</option> --}}
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-md-2  form-group mb-0">
                                          <label for=""  class="mb-1">Student Status</label>
                                          <select class="form-control select2 form-control-sm-form" id="filter_studstatus">
                                                <option value="">All</option>
                                                @foreach ($studstatus as $item)
                                                      @if ($item->id == 0)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->description}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->description}}</option>
                                                      @endif
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-2  form-group mb-0" id="filter_gradelevel_holder">
                                          <label for=""  class="mb-1">Grade Level</label>
                                          <select class="form-control select2 form-control-sm" id="filter_gradelevel">
                                          </select>
                                    </div> --}}
                                {{-- <div class="col-md-2  form-group mb-0" id="filter_entype_holder" >
                                          <label for="" class="mb-1">Admission Type</label>
                                          <select class="form-control select2 form-control-sm" id="filter_entype">
                                          </select>
                                    </div> --}}
                                <div class="col-md-2 form-group mb-0">
                                    <label for="" class="mb-1">Payment Status</label>
                                    <select class="form-control select2 form-control-sm" id="filter_paymentstat">
                                        <option value="">All</option>
                                        <option value="1">Paid / No DP Allowed</option>
                                        <option value="2">Not Paid</option>
                                    </select>
                                </div>
                                <div class="col-md-2 form-group mb-0">
                                    <label for="" class="mb-1">Active Status</label>
                                    <select class="form-control select2 form-control-sm" id="filter_activestatus">
                                        <option value="">All</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>


                                <div class="col-md-2 form-group">
                                    <label for="" class="mb-1">Online Prereg. Type</label>
                                    <select class="form-control select2 form-control-sm" id="filter_process">
                                        <option value="">All</option>
                                        @foreach ($enrollmentsetuptype as $item)
                                            <option value="{{ $item->id }}">{{ $item->description }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-2  form-group mb-0">
                                    <label for="" class="mb-1">Student Status</label>
                                    <select class="form-control select2 form-control-sm-form" id="filter_studstatus">
                                        <option value="">All</option>
                                        @foreach ($studstatus as $item)
                                            @if ($item->id == 0)
                                                <option value="{{ $item->id }}" selected="selected">
                                                    {{ $item->description }}</option>
                                            @else
                                                <option value="{{ $item->id }}">{{ $item->description }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-md-2  form-group mb-0" hidden>
                                          <label for=""  class="mb-1">Academic Program</label>
                                          <select class="form-control select2 form-control-sm" id="filter_acadprog">
                                          </select>
                                    </div> --}}
                                <div class="col-md-2  form-group mb-0" id="filter_gradelevel_holder">
                                    <label for="" class="mb-1">Grade Level</label>
                                    <select class="form-control select2 form-control-sm" id="filter_gradelevel">
                                    </select>
                                </div>
                                <div class="col-md-3 form-group mb-0">
                                    <label for="" class="mb-1">Section </label>
                                    <select class="form-control select2 form-control-sm" id="filter_section"></select>
                                    {{-- <span id="exampleInputEmail1-error" class="error invalid-feedback" style="display: block"></span> --}}
                                </div>
                                <div class="col-md-2  form-group mb-0 transdate_holder" id="transdate_holder" hidden>
                                    <label for="" class="mb-1">Enrollment Date</label>
                                    <input class="form-control  form-control-sm-form" id="filter_enrollmentdate"
                                        style="height: calc(1.7rem + 1px);">
                                </div>
                                <div class="col-md-2  form-group mb-0 transdate_holder" id="transdate_holder" hidden>
                                    <label for="" class="mb-1">Transaction Date</label>
                                    <input class="form-control  form-control-sm-form" id="filter_transdate"
                                        style="height: calc(1.7rem + 1px);">
                                </div>

                            </div>
                            @if ($refid == 29)
                                <div class="row mt-2">
                                    <div class="col-md-12 text-right">
                                        <button type="button" class="btn btn-primary btn-sm" id="print_student_info">Print Student Information</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body" style="font-size:15px !important">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table-hover table table-striped table-sm table-bordered"
                                        id="update_info_request_table" width="100%">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="7%" class="align-middle prereg_head" data-id="0">ID
                                                    #</th>
                                                <th width="25%" class="align-middle prereg_head" data-id="1">
                                                    Students</th>
                                                <th width="6%" class="align-middle prereg_head" data-id="1">
                                                    Payment</th>
                                                <th width="8%" class="align-middle text-center p-0 prereg_head"
                                                    style="font-size:.6rem !important" data-id="2"></th>
                                                <th width="20%" class="align-middle prereg_head" data-id="3">
                                                    Section</th>
                                                <th width="11%" class="align-middle prereg_head" data-id="4">
                                                    Approval</th>
                                                <th width="10%" class="align-middle prereg_head" data-id="5"
                                                    style="font-size:.66rem !important" data-id="6">Enrollment Date
                                                </th>
                                                <th width="13%" class="align-middle text-center prereg_head"
                                                    data-id="7"></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <hr class="mb-2">
                            <div class="row text-center">
                                <div class="col-md-12">
                                    |
                                    @foreach ($studstatus as $item)
                                        <a href="#" class="badge_stat" data-id="{{ $item->id }}"><span
                                                class="badge">{{ $item->description }} ( <span
                                                    class="badge_stat_count" data-id="{{ $item->id }}">0</span>
                                                )</span> </a> |
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-image-viewer-magnify/js/jquery.magnify.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>


    <script>
        function scrolltoview(tempelement) {
            const element = document.getElementById(tempelement);
            element.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        $('#filter_transdate').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('#filter_transdate').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            var activeRequestsTable = $('#update_info_request_table').DataTable();
            activeRequestsTable.state.clear();
            activeRequestsTable.destroy();
            load_update_info_datatable()
        });

        $('#filter_transdate').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            var activeRequestsTable = $('#update_info_request_table').DataTable();
            activeRequestsTable.state.clear();
            activeRequestsTable.destroy();
            load_update_info_datatable()
        });


        $('#filter_enrollmentdate').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('#filter_enrollmentdate').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            var activeRequestsTable = $('#update_info_request_table').DataTable();
            activeRequestsTable.state.clear();
            activeRequestsTable.destroy();
            load_update_info_datatable()
        });

        $('#filter_enrollmentdate').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            var activeRequestsTable = $('#update_info_request_table').DataTable();
            activeRequestsTable.state.clear();
            activeRequestsTable.destroy();
            load_update_info_datatable()
        });

        $('#input_gradelevel_new').select2({
            placeholder: "Select Grade Level"
        })

        $('#input_pantawid_new').select2({
            // allClear:true,
            placeholder: "Government Assistance"
        })



        $('#input_grantee_new').select2({
            placeholder: "Grantee"
        })

        $('#input_gradelevel').select2({
            placeholder: "Grade level to enroll"
        })


        $('#input_studtype_new').select2({
            placeholder: "Student Type"
        })

        $('#input_regStatus').select2({
            placeholder: "Select Grade Level"
        })

        $('#input_nationality_new').select2({
            placeholder: "Nationality"
        })

        $('#input_gender_new').select2({
            placeholder: "Gender"
        })

        $('#input_course_new').select2({
            placeholder: "Course"
        })

        $('#input_strand_new').select2({
            placeholder: "Strand"
        })

        $('#psschooltype').select2({
            placeholder: "School Type",
            allowClear: true
        })

        $('#gsschooltype').select2({
            placeholder: "School Type",
            allowClear: true
        })

        $('#jhsschooltype').select2({
            placeholder: "School Type",
            allowClear: true
        })

        $('#shsschooltype').select2({
            placeholder: "School Type",
            allowClear: true
        })

        $('#collegeschooltype').select2({
            placeholder: "School Type",
            allowClear: true
        })


        var curriculum = @json($curriculum);
        $(document).on('change', '#input_course_new', function() {
            var tempcur = curriculum.filter(x => x.courseID == $(this).val());
            $('#input_curriculum_new').empty()
            $('#input_curriculum_new').append('<option value="">Select Curriculum</option>')
            $('#input_curriculum_new').select2({
                data: tempcur,
                allowClear: true,
                placeholder: "Select Curriculum"
            })

            if (studcurriculum != null) {
                $('#input_curriculum_new').val(studcurriculum).change()
            } else {
                $('#input_curriculum_new').val("")
            }

        })


        $('#filter_section').select2({
            allowClear: true,
            placeholder: "All",
            "language": {
                "noResults": function() {
                    if ($('#filter_gradelevel').val() == "") {
                        return '<span class="text-sm">No grade level selected<span>';
                    } else {
                        return "No results found";
                    }
                }
            },
            escapeMarkup: function(markup) {
                return markup;
            },
            ajax: {
                url: "{{ route('sctnSelect') }}",
                data: function(params) {
                    var query = {
                        syid: $('#filter_sy').val(),
                        levelid: $('#filter_gradelevel').val(),
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

                },
            }
        })
    </script>

    <script>
        $('.modal').css('overflow-y', 'auto');

        $(document).ready(function() {
            $(document).on('click', '.print_enrollment', function() {
                $('#print_enrollment_list').modal()
            })

            $(document).on('click', '.print_to_filter', function() {
                $('#print_enrollment_proceed').text('Print: ' + $(this).text())
                $('#print_enrollment_proceed').attr('data-id', $(this).attr('data-id'))
                $('#printable_filter_modal').modal()

            })

            $(document).on('click', '#print_enrollment_proceed', function() {
                var filter_gradelevel = [];
                $('.print_filter_gradelevel').each(function() {
                    if ($(this).is(":checked")) {
                        filter_gradelevel.push($(this).val())
                    }
                })

                if ($(this).attr('data-id') == "enrollmentbyreligiousaffiliation") {
                    window.open('/student/preregistration/print/enrollmentbyreligiousaffiliation/?syid=' +
                        $('#filter_sy').val() + '&semid=' + $('#filter_sem').val() + '&gradelevel=' +
                        filter_gradelevel, '_blank');
                } else if ($(this).attr('data-id') == "enrollmentbyethnicgroup") {
                    window.open('/student/preregistration/print/enrollmentbyethnicgroup/?syid=' + $(
                            '#filter_sy').val() + '&semid=' + $('#filter_sem').val() + '&gradelevel=' +
                        filter_gradelevel, '_blank');
                } else if ($(this).attr('data-id') == "enrollmentunenrolled") {
                    window.open('/student/preregistration/print/enrollmentunenrolled/?syid=' + $(
                            '#filter_sy').val() + '&semid=' + $('#filter_sem').val() + '&gradelevel=' +
                        filter_gradelevel, '_blank');
                } else if ($(this).attr('data-id') == "studentprofile") {
                    window.open('/student/preregistration/print/studentprofile/?syid=' + $('#filter_sy')
                        .val() + '&semid=' + $('#filter_sem').val() + '&gradelevel=' +
                        filter_gradelevel, '_blank');
                }
            })

            $(document).on('click', '.print_filter_acadprog', function() {
                if ($(this).prop('checked') == false) {
                    $('.print_filter_gradelevel[data-acad="' + $(this).val() + '"]').prop('checked', false)
                } else {
                    $('.print_filter_gradelevel[data-acad="' + $(this).val() + '"]').prop('checked', true)
                }
            })

            $(document).on('click', '.print_filter_gradelevel', function() {
                if ($(this).prop('checked') == false) {
                    $('.print_filter_acadprog[value="' + $(this).attr('data-acad') + '"]').prop('checked',
                        false)
                } else {
                    $('.print_filter_acadprog[value="' + $(this).attr('data-acad') + '"]').prop('checked',
                        true)
                }
            })
        })
    </script>


    <script>
        $(document).ready(function() {

            $(document).on('click', '.badge_stat', function() {
                $('#filter_studstatus').val($(this).attr('data-id')).change()
            })

            $(document).on('change', '#input_gradelevel_new', function() {

                var temp_id = $(this).val()
                var temp_acad = gradelevel.filter(x => x.id == temp_id)
                if (temp_acad.length > 0) {
                    var enrollment_type = all_admissionsetup.filter(x => x.acadprogid == temp_acad[0]
                        .acadprogid)
                    $("#input_addtype_new").empty();
                    $("#input_addtype_new").select2({
                        placeholder: 'Select Admission Type',
                        data: enrollment_type,
                    })
                }

                $('#input_strand_holder').attr('hidden', 'hidden')
                $('.input_course_holder').attr('hidden', 'hidden')
                if ($(this).val() == 14 || $(this).val() == 15) {
                    $('#input_strand_holder').removeAttr('hidden')
                } else if ($(this).val() == 17 || $(this).val() == 18 || $(this).val() == 19 || $(this)
                    .val() == 20 || $(this).val() == 21) {
                    $('.input_course_holder').removeAttr('hidden')
                }


            })

        })
    </script>

    <script>
        $(document).ready(function() {




            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })



            $("#input_father_contact_new").inputmask({
                mask: "9999-999-9999"
            });
            $("#input_mother_contact_new").inputmask({
                mask: "9999-999-9999"
            });
            $("#input_guardian_contact_new").inputmask({
                mask: "9999-999-9999"
            });
            $("#last_school_no").inputmask({
                mask: "9999-999-9999"
            });
            $("#input_scontact_new").inputmask({
                mask: "9999-999-9999"
            });

            $("#input_lrn_new").inputmask({
                mask: "999999999999"
            });

            $("#usci_scontact").inputmask({
                mask: "9999-999-9999"
            });
            $("#usci_fcontact").inputmask({
                mask: "9999-999-9999"
            });
            $("#usci_mcontact").inputmask({
                mask: "9999-999-9999"
            });
            $("#usci_gcontact").inputmask({
                mask: "9999-999-9999"
            });

            $("#pssy").inputmask({
                mask: "9999-9999"
            });
            $("#gssy").inputmask({
                mask: "9999-9999"
            });
            $("#jhssy").inputmask({
                mask: "9999-9999"
            });
            $("#shssy").inputmask({
                mask: "9999-9999"
            });
            $("#collegesy").inputmask({
                mask: "9999-9999"
            });

            // $('.select2').select2({ dropdownCssClass: "myFont" })
            $('#filter_sy').select2()
            $('#filter_sem').select2()

            $('#filter_paymentstat').select2({
                allowClear: true,
                placeholder: 'All'
            })

            $('#filter_studstatus ').select2()

            $('#input_mt_new').select2({
                allowClear: true,
                placeholder: 'Mother Toungue'
            })

            $('#filter_activestatus').select2({
                allowClear: true,
                placeholder: 'All'
            })


            $('#input_religion_new').select2({
                allowClear: true,
                placeholder: 'Religion'
            })

            $('#bloodtype').select2({
                allowClear: true,
                placeholder: 'Blood Type'
            })

            $('#input_egroup_new').select2({
                allowClear: true,
                placeholder: 'Ethnic Group'
            })

            $('#input_marital_new').select2({
                allowClear: true,
                placeholder: 'Marital Status'
            })

            $('#last_school_lvlid').select2({
                allowClear: true,
                placeholder: 'Grade Level in that School'
            })




            $('#filter_process').select2({
                allowClear: true,
                placeholder: 'All'
            })

            var acadprog = @json($acadprog_list);

            if (acadprog.filter(x => x == 5).length > 0 || acadprog.filter(x => x == 6).length > 0) {
                // $('.semester_holder').removeAttr('hidden')
            }


            var date = moment().format('YYYY-MM-DD');
            $('#input_enrollmentdate').val(date)

            // load_update_info_datatable()
            // load_all_preregstudent()



            // var all_sections = @json($sections);



            // var all_mol = @json($mol);

            $("#input_grantee").empty();
            $("#input_grantee").append('<option value="">Select Grantee</option>');
            $("#input_grantee").select2({
                data: all_grantee,
                placeholder: "Select a Grantee",
            })

            $('#input_grantee').val(1).change()



            $("#input_strand").empty();
            $("#input_strand").append('<option value="">Select Strand</option>');
            $("#input_strand").select2({
                data: [],
                allowClear: true,
                placeholder: "Select a Strand",
            })

            // $("#filter_gradelevel").empty();
            // $('#filter_gradelevel').append('<option value="">All</option>')
            // $("#filter_gradelevel").select2({
            //       data:gradelevel,
            //       allowClear: true,
            //       placeholder: "All",
            //       dropdownCssClass: "myFont"
            // })

            get_gradelvl()

            function get_gradelvl() {

                $('#no_acad_holder').attr('hidden', 'hidden')

                $.ajax({
                    type: 'GET',
                    url: '/student/preregistration/getgradelevel',
                    data: {
                        syid: $('#filter_sy').val()
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            gradelevel = data
                            $("#filter_gradelevel").empty();
                            $('#filter_gradelevel').append('<option value="">All</option>')
                            $("#filter_gradelevel").select2({
                                data: gradelevel,
                                allowClear: true,
                                placeholder: "All",
                                dropdownCssClass: "myFont"
                            })
                        } else {
                            $("#filter_gradelevel").empty();
                            $("#filter_gradelevel").empty();
                            $('#filter_gradelevel').append('<option value="">All</option>')
                            $("#filter_gradelevel").select2({
                                data: [],
                                allowClear: true,
                                placeholder: "All",
                                dropdownCssClass: "myFont"
                            })
                            $('#no_acad_holder').removeAttr('hidden')
                            Toast.fire({
                                type: 'error',
                                title: 'No academic program assigned'
                            })
                        }
                    },
                    error: function() {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                })
            }

            $("#filter_acadprog").empty();
            $('#filter_acadprog').append('<option value="">All</option>')
            $("#filter_acadprog").select2({
                data: acadprog_display,
                allowClear: true,
                placeholder: "All",
                dropdownCssClass: "myFont"
            })

            get_admission_setup()

            $(document).on('change', '#filter_sy', function() {
                all_students = []
                load_update_info_datatable()
                get_admission_setup()
                load_all_sections()
                load_enrollment_summary()
                // load_all_preregstudent()
                // load_all_student()
                get_gradelvl()
                get_mol()
            })

            $(document).on('change', '#filter_sem', function() {
                all_students = []
                load_update_info_datatable()
                load_all_sections()
                load_enrollment_summary()
                get_admission_setup()
                // load_all_preregstudent()
                // load_all_student()
            })

            $(document).on('change', '#filter_activestatus', function() {
                var activeRequestsTable = $('#update_info_request_table').DataTable();

                activeRequestsTable.state.clear();
                activeRequestsTable.destroy();
                load_update_info_datatable()
            })


            $(document).on('change', '#filter_studstatus', function() {
                var activeRequestsTable = $('#update_info_request_table').DataTable();

                if ($(this).val() == 0 || $(this).val() == null) {
                    $('.transdate_holder').attr('hidden', 'hidden')
                    $('#filter_transdate').val("")
                    $('#filter_enrollmentdate').val("")
                } else {
                    $('.transdate_holder').removeAttr('hidden')
                }

                activeRequestsTable.state.clear();
                activeRequestsTable.destroy();
                load_update_info_datatable()
            })


            $(document).on('click', '#update_contact_info', function() {

                var temp_studinfo = all_students.filter(x => x.studid == selected)

                $('#usci_fcontact').val(temp_studinfo[0].fcontactno)
                $('#usci_mcontact').val(temp_studinfo[0].mcontactno)
                $('#usci_gcontact').val(temp_studinfo[0].gcontactno)
                $('#usci_scontact').val(temp_studinfo[0].contactno)

                if (temp_studinfo[0].ismothernum == 1) {
                    $("#usci_mother").prop("checked", true)
                } else if (temp_studinfo[0].isfathernum == 1) {
                    $("#usci_father").prop("checked", true)
                } else if (temp_studinfo[0].isguardannum == 1) {
                    $("#usci_guardian").prop("checked", true)
                } else {
                    $('input[name="usci_incase"]').prop("checked", false)
                }

                $('#update_student_contactinformation').modal()
            })

            $(document).on('change', '#filter_gradelevel', function() {
                $('#filter_section').val("").change()
                var activeRequestsTable = $('#update_info_request_table').DataTable();
                activeRequestsTable.state.clear();
                activeRequestsTable.destroy();
                load_update_info_datatable(true)
            })

            $(document).on('change', '#filter_section', function() {
                var activeRequestsTable = $('#update_info_request_table').DataTable();
                activeRequestsTable.state.clear();
                activeRequestsTable.destroy();
                load_update_info_datatable(true)
            })


            $(document).on('change', '#filter_entype', function() {
                load_update_info_datatable(true)
            })

            $(document).on('change', '#filter_paymentstat', function() {
                var activeRequestsTable = $('#update_info_request_table').DataTable();
                activeRequestsTable.state.clear();
                activeRequestsTable.destroy();
                load_update_info_datatable(true)
            })

            $(document).on('change', '#filter_process', function() {
                var activeRequestsTable = $('#update_info_request_table').DataTable();
                activeRequestsTable.state.clear();
                activeRequestsTable.destroy();
                load_update_info_datatable(true)
            })


            $(document).on('click', '.add_student_to_prereg', function() {
                if (all_admissionsetup.length == 0) {
                    get_admission_setup()
                }
                $('#label_sy').text($('#filter_sy option:selected').text())
                $('#label_sem').text($('#filter_sem option:selected').text())
                $('#input_add_student').val("").change()
                $('#input_add_gradelevel').val("").change()
                $('#add_stud_prereg_modal').modal()
                $('#input_add_gradelevel').attr('disabled', 'disabled')

                if ($(this).attr('data-id') != undefined) {
                    $('#input_add_student').val($(this).attr('data-id')).change()
                }
            })

            $(document).on('change', '#input_add_gradelevel', function() {
                var temp_id = $(this).val()
                var temp_acad = gradelevel.filter(x => x.id == temp_id)
                if (temp_acad.length > 0) {
                    var enrollment_type = all_admissionsetup.filter(x => x.acadprogid == temp_acad[0]
                        .acadprogid)
                    $("#input_addtype_old").empty();
                    $("#input_addtype_old").select2({
                        placeholder: 'Select Admission Type',
                        data: enrollment_type,
                    })
                } else {
                    $("#input_addtype_old").empty();
                    $("#input_addtype_old").select2({
                        placeholder: 'Select Admission Type',
                        data: []
                    })
                }

                $('#input_add_strand_holder').attr('hidden', 'hidden')
                $('#input_add_course_holder').attr('hidden', 'hidden')

                $('#input_add_strand').val("").change()
                $('#input_add_course').val("").change()

                if ($(this).val() == 14 || $(this).val() == 15) {
                    var temp_studinfo = all_studinfo.filter(x => x.id == $('#input_add_student').val())
                    if (temp_studinfo.length > 0) {
                        $('#input_add_strand').val(temp_studinfo[0].strandid).change()
                    }
                    $('#input_add_strand_holder').removeAttr('hidden')
                }

                if ($(this).val() == 17 || $(this).val() == 18 || $(this).val() == 19 || $(this).val() ==
                    20 || $(this).val() == 21) {
                    var temp_studinfo = all_studinfo.filter(x => x.id == $('#input_add_student').val())
                    if (temp_studinfo.length > 0) {
                        $('#input_add_course').val(temp_studinfo[0].courseid).change()
                    }
                    $('#input_add_course_holder').removeAttr('hidden')
                }


            })

            $(document).on('click', '#enroll_student_button[data-p="create"]', function() {
                enroll_student()
            })

            $(document).on('click', '#enroll_student_button[data-p="update"]', function() {
                update_student()
            })


            // $(document).on('click','#readytoenroll_student_button',function(){

            //       $.ajax({
            //             type:'GET',
            //             url:'/student/preregistration/markasready',
            //             data:{
            //                   studid:selected,
            //                   syid:$('#filter_sy').val()
            //             },
            //             success:function(data) {

            //             }
            //       })

            // })

            $(document).on('change', '#input_add_student', function() {
                var selected_all_studinfo = $(this).val()
                var selected_all_studinfo = all_studinfo.filter(x => x.id == selected_all_studinfo)
                $('#input_add_gradelevel').removeAttr('disabled')

                if (selected_all_studinfo.length > 0) {
                    $('#input_add_gradelevel').val(selected_all_studinfo[0].levelid).change()
                } else {
                    $('#input_add_gradelevel').val("").change()
                }


            })




            $(document).on('click', '#add_stud_prereg_button', function() {

                if ($('#input_addtype_old').val() == "" || $('#input_addtype_old').val() == null) {
                    Toast.fire({
                        type: 'warning',
                        title: 'Please select Enrollment Type'
                    })
                    return false
                }

                add_student_to_prereg_proccess()
            })

            $("#input_mol_new").empty();
            $("#input_mol_new").append('<option value="">Select Mode of Learning</option>');
            $("#input_mol_new").select2({
                data: [],
                allowClear: true,
                placeholder: "Select a Mode of Learning",
            })

            $(document).on('change', '#input_gradelevel_new', function() {
                var temp_levelid = $(this).val()
                var temp_mol = []

                $.each(all_mol, function(a, b) {
                    included = false
                    if (b.all) {
                        included = true
                    } else {
                        var check = b.gradelevel.filter(x => x.levelid == temp_levelid).length
                        if (check > 0) {
                            included = true
                        }
                    }
                    if (included) {
                        temp_mol.push(b)
                    }

                })


                $("#input_mol_new").empty();
                $("#input_mol_new").append('<option value="">Select Mode of Learning</option>');
                $("#input_mol_new").select2({
                    data: temp_mol,
                    allowClear: true,
                    placeholder: "Select a Mode of Learning",
                })
            })


            $(document).on('change', '#input_gradelevel', function() {

                // $('.semester_holder_1').attr('hidden','hidden')
                var temp_levelid = $(this).val()
                var temp_section = $('#input_section').val()
                var temp_mol = []

                $.each(all_mol, function(a, b) {
                    included = false
                    if (b.all) {
                        included = true
                    } else {
                        var check = b.gradelevel.filter(x => x.levelid == temp_levelid).length
                        if (check > 0) {
                            included = true
                        }
                    }
                    if (included) {
                        temp_mol.push(b)
                    }

                })


                $("#input_mol").empty();
                $("#input_mol").append('<option value="">Select Mode of Learning</option>');
                $("#input_mol").select2({
                    data: temp_mol,
                    allowClear: true,
                    placeholder: "Select a Mode of Learning",
                })

                var temp_studinfo = all_students.filter(x => x.studid == selected)
                $("#input_mol").val(temp_studinfo[0].mol).change()

                update_gradelevel_input(temp_levelid, temp_section)
            })

            $(document).on('change', '#input_section', function() {
                if ($('#input_gradelevel').val() == 14 || $('#input_gradelevel').val() == 15) {
                    update_strand_input($(this).val())
                }
            })

            $(document).on('click', '#create_new_student', function() {

                $("#mother").prop("checked", false)
                $("#father").prop("checked", false)
                $("#guardian").prop("checked", false)

                $('#input_gradelevel_new').val("").change()
                $('#input_lrn_new').val("")

                $('#input_sid_new').val("")
                $('#input_altsid_new').val("")

                $('#input_studtype_new').val("new").change()
                $('#input_grantee_new').val(1).change()



                $("#input_mol_new").empty();
                $("#input_mol_new").append('<option value="">Select Mode of Learning</option>');
                $("#input_mol_new").select2({
                    data: [],
                    allowClear: true,
                    placeholder: "Select a Mode of Learning",
                })

                $('#input_mol_new').val("").change()


                $('#input_pantawid_new').val("").change()
                $('#input_addtype_new').val("")
                $('#input_strand_new').val("").change()
                $('#input_course_new').val("").change()

                $('#input_mothermaidename_new').val("")
                $('#input_fname_new').val("")
                $('#input_lname_new').val("")
                $('#input_mname_new').val("")
                $('#input_suffix_new').val("")
                $('#input_dob_new').val("")
                $('#input_semail_new').val("")
                $('#input_gender_new').val("MALE").change()
                $('#input_nationality_new').val(77).change()
                $('#input_scontact_new').val("")

                $('#input_religion_new').val("").change()
                $('#input_mt_new').val("").change()
                $('#input_egroup_new').val("").change()

                $('#input_street_new').val("")
                $('#input_barangay_new').val("")
                $('#input_district_new').val("")
                $('#input_city_new').val("")
                $('#input_province_new').val("")
                $('#input_region_new').val("")

                $('#input_father_fname_new').val("")
                $('#input_father_mname_new').val("")
                $('#input_father_lname_new').val("")
                $('#input_father_sname_new').val("")
                $('#input_father_contact_new').val("")
                $('#input_father_occupation_new').val("")

                $('#input_mother_fname_new').val("")
                $('#input_mother_mname_new').val("")
                $('#input_mother_lname_new').val("")
                $('#input_mother_sname_new').val("")
                $('#input_mother_contact_new').val("")
                $('#input_mother_occupation_new').val("")

                $('#input_guardian_fname_new').val("")
                $('#input_guardian_mname_new').val("")
                $('#input_guardian_lname_new').val("")
                $('#input_guardian_sname_new').val("")
                $('#input_guardian_contact_new').val("")
                $('#input_guardian_relation_new').val("")

                $('#studinfo_holder').animate({
                    scrollTop: 0
                }, 'slow');

                $('#psschoolname').val("")
                $('#pssy').val("")
                $('#gsschoolname').val("")
                $('#gssy').val("")
                $('#jhsschoolname').val("")
                $('#jhssy').val("")
                $('#shsschoolname').val("")
                $('#shssy').val("")
                $('#collegeschoolname').val("")
                $('#collegesy').val("")

                $('#last_school_att').val("")

                $('#pob').val("").change()
                $('#input_marital_new').val("")
                $('#input_ncf_new').val("")
                $('#input_nce_new').val("")
                $('#input_lsah_new').val("")

                $('#fea').val("")
                $('#mea').val("")
                $('#gea').val("")

                $('#fmi').val("")
                $('#mmi').val("")
                $('#gmi').val("")

                $('#fosoi').val("")
                $('#mosoi').val("")
                $('#gosoi').val("")

                $('#fethnicity').val("")
                $('#methnicity').val("")
                $('#gethnicity').val("")

                $('#last_school_lvlid').val("")
                $('#last_school_no').val("")
                $('#last_school_add').val("")

                $('input[name="vacc"]').prop('checked', false)
                $('#vacc_type_1st').val("").change()
                $('#vacc_type_2nd').val("").change()
                $('#vacc_type_booster').val("").change()
                $('#vacc_card_id').val("")
                $('#dose_date_1st').val("")
                $('#dose_date_2nd').val("")
                $('#dose_date_booster').val("")
                $('#philhealth').val("")
                $('#bloodtype').val("").change()

                $('#allergy_to_med').val("")
                $('#med_his').val("")
                $('#other_med_info').val("")

                $('#bec_cell').val("")
                $('#chapelzone').val("")

                $('#input_mfi_new').val("")
                $('#psschooltype').val("").change()
                $('#gsschooltype').val("").change()
                $('#jhsschooltype').val("").change()
                $('#shsschooltype').val("").change()
                $('#collegeschooltype').val("").change()

                $('#input_gradelevel_new').removeAttr('disabled')
                $('#input_studtype_new').removeAttr('disabled')

                $('#create_new_student_button').removeAttr('hidden')
                $('#update_student_information_button').attr('hidden', 'hidden')
                $('#enrollment_form').attr('hidden', 'hidden')

                $('#student_info_modal').modal()
            })

            $(document).on('click', '#udpate_student_information', function() {
                $.ajax({
                    type: 'GET',
                    url: '/student/preregistration/student/updateinfo',
                    data: {
                        studid: selected,
                        syid: $('#filter_sy').val()
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                            get_update_info_history()
                        } else if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    },
                    error: function() {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                })

            })

            $(document).on('click', '#update_student_contact_info', function() {

                var ismothernum = 0
                var isfathernum = 0
                var isguardiannum = 0

                if ($('#usci_guardian').prop('checked') == true) {
                    isguardiannum = 1
                }
                if ($('#usci_mother').prop('checked') == true) {
                    ismothernum = 1
                }
                if ($('#usci_father').prop('checked') == true) {
                    isfathernum = 1
                }

                if ($('#usci_scontact').val() != "" && ($('#usci_scontact').val()).toString().replace(
                        /-|_/g, '').length != 11) {
                    Toast.fire({
                        type: 'warning',
                        title: "Student contact # is invalid!"
                    })
                    return false
                } else if ($('#usci_fcontact').val() != "" && ($('#usci_fcontact').val()).toString()
                    .replace(/-|_/g, '').length != 11) {
                    Toast.fire({
                        type: 'warning',
                        title: "Father contact # is invalid!"
                    })
                    return false
                } else if ($('#usci_mcontact').val() != "" && ($('#usci_mcontact').val()).toString()
                    .replace(/-|_/g, '').length != 11) {
                    Toast.fire({
                        type: 'warning',
                        title: "Mother contact # is invalid!"
                    })
                    return false
                } else if ($('#usci_gcontact').val() != "" && ($('#usci_gcontact').val()).toString()
                    .replace(/-|_/g, '').length != 11) {
                    Toast.fire({
                        type: 'warning',
                        title: "Guardian contact # is invalid!"
                    })
                    return false
                } else if (isguardiannum == 0 && ismothernum == 0 && isfathernum == 0) {
                    Toast.fire({
                        type: 'warning',
                        title: "Select in case of emergency!"
                    })
                    return false
                }
                if (isfathernum == 1 && $('#usci_fcontact').val() == "") {
                    Toast.fire({
                        type: 'warning',
                        title: "Father contact # is empty!"
                    })
                    return false
                } else if (ismothernum == 1 && $('#usci_mcontact').val() == "") {
                    Toast.fire({
                        type: 'warning',
                        title: "Mother contact # is empty!"
                    })
                    return false
                } else if (isguardiannum == 1 && $('#usci_gcontact').val() == "") {
                    Toast.fire({
                        type: 'warning',
                        title: "Guardian contact # is empty!"
                    })
                    return false
                } else if (isguardiannum == 1 && ($('#usci_gcontact').val()).toString().replace(/-|_/g, '')
                    .length != 11) {
                    Toast.fire({
                        type: 'warning',
                        title: "Mother contact # is invalid!"
                    })
                    return false
                } else if (ismothernum == 1 && ($('#usci_mcontact').val()).toString().replace(/-|_/g, '')
                    .length != 11) {
                    Toast.fire({
                        type: 'warning',
                        title: "Mother contact # is invalid!"
                    })
                    return false
                } else if (isfathernum == 1 && ($('#usci_fcontact').val()).toString().replace(/-|_/g, '')
                    .length != 11) {
                    Toast.fire({
                        type: 'warning',
                        title: "Mother contact # is invalid!"
                    })
                    return false
                }

                $.ajax({
                    type: 'GET',
                    url: '/student/preregistration/student/update/contactinfo',
                    data: {
                        studid: selected,
                        ismothernum: ismothernum,
                        isfathernum: isfathernum,
                        isguardiannum: isguardiannum,
                        contactno: $('#usci_scontact').val(),
                        fcontactno: $('#usci_fcontact').val(),
                        mcontactno: $('#usci_mcontact').val(),
                        gcontactno: $('#usci_gcontact').val(),
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })

                            var info_index = all_students.findIndex(x => x.studid == selected)
                            all_students[info_index].ismothernum = ismothernum
                            all_students[info_index].isfathernum = isfathernum
                            all_students[info_index].isguardannum = isguardiannum
                            all_students[info_index].contactno = $('#usci_scontact').val()
                            all_students[info_index].fcontactno = $('#usci_fcontact').val()
                            all_students[info_index].mcontactno = $('#usci_mcontact').val()
                            all_students[info_index].gcontactno = $('#usci_gcontact').val()

                            var temp_studinfo = all_students.filter(x => x.studid == selected)
                            update_enrollment_message_reciever_display(temp_studinfo)

                        } else if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    },
                    error: function() {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                })

            })


            function load_college_subject_load() {
                $.ajax({
                    type: 'GET',
                    url: '/student/preregistration/student/collegesubjectload',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_sem').val(),
                        studid: selected,
                    },
                    success: function(data) {
                        if (data[0].units == 0) {
                            $('#units_enrolled').text(0)
                            $('#subjects_enrolled').text(0)
                        } else {
                            $('#units_enrolled').text(data[0].units)
                            $('#subjects_enrolled').text(data[0].subjcount)
                        }

                    }
                })

            }

            function load_documents() {
                var temp_studinfo = all_students.filter(x => x.studid == selected)
                $('#documents_holder').empty()

                $.ajax({
                    type: 'GET',
                    url: '/student/preregistration/student/documents',
                    data: {
                        syid: $('#filter_sy').val(),
                        levelid: temp_studinfo[0].curlevelid,
                        studid: selected,
                    },
                    success: function(data) {
                        console.log('this is documents');
                        console.log(data);
                        if (data[0].status == 1) {
                            data[0].data.forEach(function(b) {
                                console.log(b)
                                var image = 'No File Uploaded!<br>'
                                var herf = 'href="#"'
                                if (b.uploaded[0] && b.uploaded[0].picurl != '' && b.uploaded[0]
                                    .picurl != null) {
                                    image = '<img data-id="' + b.uploaded[0].id + '" src="' + b
                                        .uploaded[0].picurl +
                                        '" class="img-fluid req_image" style="border-radius:0!important" />'
                                    herf = 'href="' + b.uploaded[0].picurl + '"'
                                    $('#documents_holder').append(
                                        '<div class="col-sm-6 mb-3" >' +
                                        '<a ' + herf +
                                        'data-magnify="gallery" class="view_image" style="font-size:.55rem !important;">' +
                                        image +
                                        '</a><span style="font-size:11px !important; display:block">' +
                                        b.uploaded[0].description + '</span>' +
                                        '</div>'
                                    )
                                }
                            })


                        } else {
                            $('#documents_holder').append(
                                '<div class="col-sm-12 mb-3" >' +
                                '<p class="text-danger" style="font-size:.7rem !important"><i>No documents uploaded.</i></p>' +
                                '</div>'
                            )
                        }

                    }
                })
            }

            $(document).on('click', '.req_image', function() {

                $('.magnify-modal').remove()

            })

            $('[data-magnify=gallery]').magnify({
                draggable: true,
                resizable: true,
                movable: true,
                title: false,
                modalWidth: 520,
                modalHeight: 520
            });

            function get_college_info() {
                $.ajax({
                    type: 'GET',
                    url: '/student/preregistration/student/collegeinfo',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_sem').val(),
                        studid: selected,
                    },
                    success: function(data) {
                        if (data[0].section != null) {
                            var temp_college_section = [data[0].section]
                            $("#input_section").empty();
                            $("#input_section").select2({
                                data: temp_college_section
                            })
                        }
                    }
                })
            }

            function update_enrollment_message_reciever_display(temp_studinfo) {

                var parent_name = null;
                var parent_contact = null;
                var parent_label = null;

                $('#enrollment_message_receiever').empty()

                if (temp_studinfo[0].ismothernum == 1 && temp_studinfo[0].mcontactno != '') {
                    parent_contact = temp_studinfo[0].mcontactno
                    parent_label = 'Mother'
                } else if (temp_studinfo[0].isfathernum == 1 && temp_studinfo[0].fcontactno != '') {
                    parent_contact = temp_studinfo[0].fcontactno
                    parent_label = 'Father'
                } else if (temp_studinfo[0].isguardannum == 1 && temp_studinfo[0].gcontactno != '') {
                    parent_contact = temp_studinfo[0].gcontactno
                    parent_label = 'Guardian'
                }

                var student_contact = temp_studinfo[0].contactno == null || temp_studinfo[0].contactno == '' ?
                    'No Contact #' : temp_studinfo[0].contactno;
                var parent_contact = parent_contact == null ? 'No Contact #' : parent_label + ' : ' + parent_contact

                $('#enrollment_message_receiever').append('<tr><td>' + student_contact + '</td><td>' +
                    parent_contact + '</td></tr>')

                $('#enrollment_message_receiever').append(
                    '<tr><td><a href="#" id="update_contact_info">Update Contact #</a></td><td class="align-middle" style="font-size:.6rem !important"><i>Update Contact # to recieve News, Announcement and School Information</i></td></tr>'
                )

                if (usertype_session == 8 || usertype_session == 4 || usertype_session == 15 || usertype_session ==
                    14) {
                    $('#update_contact_info').attr('hidden', 'hidden')
                }
            }


            $(document).on('input', '#input_oitf_new', function() {
                $('input[name=oitf]').prop('checked', false);
            })



            $(document).on('click', '#create_new_student_button', function() {

                var check = check_studinfo_input()

                if (!check) {
                    return false
                }

                var ismothernum = 0
                var isfathernum = 0
                var isguardiannum = 0

                if ($('#guardian').prop('checked') == true) {
                    isguardiannum = 1
                }
                if ($('#mother').prop('checked') == true) {
                    ismothernum = 1
                }
                if ($('#father').prop('checked') == true) {
                    isfathernum = 1
                }


                var oitf = null

                if ($('input[name=oitf]:checked').length > 0) {
                    oitf = $('input[name=oitf]:checked').val()
                }

                if ($('#input_oitf_new').val() != "") {
                    oitf = $('#input_oitf_new').val()
                }


                if (school_setup.setup == 1) {
                    $.ajax({
                        type: 'GET',
                        url: school_setup.es_cloudurl + 'student/preregistration/createnewstudent',
                        data: {
                            userid: userid,
                            syid: $('#filter_sy').val(),
                            semid: $('#filter_sem').val(),

                            levelid: $('#input_gradelevel_new').val(),
                            lrn: $('#input_lrn_new').val(),

                            altsid: $('#input_altsid_new').val(),
                            mol: $('#input_mol_new').val(),

                            studtype: $('#input_studtype_new').val(),
                            studgrantee: $('#input_grantee_new').val(),
                            pantawid: $('#input_pantawid_new').val(),
                            admissiontype: $('#input_addtype_new').val(),
                            strandid: $('#input_strand_new').val(),
                            courseid: $('#input_course_new').val(),
                            curriculum: $('#input_curriculum_new').val(),
                            maidenname: $('#input_mothermaidename_new').val(),
                            firstname: $('#input_fname_new').val(),
                            lastname: $('#input_lname_new').val(),
                            middlename: $('#input_mname_new').val(),
                            suffix: $('#input_suffix_new').val(),
                            dob: $('#input_dob_new').val(),
                            semail: $('#input_semail_new').val(),
                            gender: $('#input_gender_new').val(),
                            nationality: $('#input_nationality_new').val(),
                            contactno: $('#input_scontact_new').val(),
                            ismothernum: ismothernum,
                            isfathernum: isfathernum,
                            isguardiannum: isguardiannum,

                            street: $('#input_street_new').val(),
                            barangay: $('#input_barangay_new').val(),
                            district: $('#input_district_new').val(),
                            city: $('#input_city_new').val(),
                            province: $('#input_province_new').val(),
                            region: $('#input_region_new').val(),

                            ffname: $('#input_father_fname_new').val(),
                            fmname: $('#input_father_mname_new').val(),
                            flname: $('#input_father_lname_new').val(),
                            fsuffix: $('#input_father_sname_new').val(),
                            fcontactno: $('#input_father_contact_new').val(),
                            foccupation: $('#input_father_occupation_new').val(),

                            mfname: $('#input_mother_fname_new').val(),
                            mmname: $('#input_mother_mname_new').val(),
                            mlname: $('#input_mother_lname_new').val(),
                            msuffix: $('#input_mother_sname_new').val(),
                            mcontactno: $('#input_mother_contact_new').val(),
                            moccupation: $('#input_mother_occupation_new').val(),

                            gfname: $('#input_guardian_fname_new').val(),
                            gmname: $('#input_guardian_mname_new').val(),
                            glname: $('#input_guardian_lname_new').val(),
                            gsuffix: $('#input_guardian_sname_new').val(),
                            gcontactno: $('#input_guardian_contact_new').val(),
                            relation: $('#input_guardian_relation_new').val(),

                            mtname: $('#input_mt_new option:selected').text(),
                            egname: $('#input_egroup_new option:selected').text(),
                            religionname: $('#input_religion_new option:selected').text(),

                            mtid: $('#input_mt_new').val(),
                            egid: $('#input_egroup_new').val(),
                            religionid: $('#input_religion_new').val(),

                            psschoolname: $('#psschoolname').val(),
                            pssy: $('#pssy').val(),
                            gsschoolname: $('#gsschoolname').val(),
                            gssy: $('#gssy').val(),
                            jhsschoolname: $('#jhsschoolname').val(),
                            jhssy: $('#jhssy').val(),
                            shsschoolname: $('#shsschoolname').val(),
                            shssy: $('#shssy').val(),
                            collegeschoolname: $('collegeschoolname').val(),
                            collegesy: $('#collegesy').val(),

                            vacc: $('input[name="vacc"]:checked').val(),
                            vacc_type_1st: $('#vacc_type_1st').val(),
                            vacc_type_2nd: $('#vacc_type_2nd').val(),
                            vacc_type_booster: $('#vacc_type_booster').val(),
                            vacc_type_text_1st: $('#vacc_type_1st option:selected').text(),
                            vacc_type_text_2nd: $('#vacc_type_2nd option:selected').text(),
                            vacc_type_text_booster: $('#vacc_type_booster option:selected').text(),
                            vacc_card_id: $('#vacc_card_id').val(),
                            dose_date_1st: $('#dose_date_1st').val(),
                            dose_date_2nd: $('#dose_date_2nd').val(),
                            dose_date_booster: $('#dose_date_booster').val(),
                            philhealth: $('#philhealth').val(),
                            bloodtype: $('#bloodtype').val(),
                            allergy: $('#allergy').val(),
                            allergy_to_med: $('#allergy_to_med').val(),
                            med_his: $('#med_his').val(),
                            other_med_info: $('#other_med_info').val(),

                            bec_cell: $('#bec_cell').val(),
                            chapelzone: $('#chapelzone').val(),

                            mfi: $('#input_mfi_new').val(),
                            psschooltype: $('#psschooltype').val(),
                            gsschooltype: $('#gsschooltype').val(),
                            jhsschooltype: $('#jhsschooltype').val(),
                            shsschooltype: $('#shsschooltype').val(),
                            collegeschooltype: $('#collegeschooltype').val(),

                            lastschoolatt: $('#last_school_att').val(),

                            pob: $('#pob').val(),
                            maritalstatus: $('#input_marital_new').val(),
                            nocitf: $('#input_ncf_new').val(),
                            noce: $('#input_nce_new').val(),
                            lsah: $('#input_lsah_new').val(),
                            oitf: oitf,

                            glits: $('#last_school_lvlid').val(),
                            scn: $('#last_school_no').val(),
                            cmaosla: $('#last_school_add').val(),

                            fea: $('#fea').val(),
                            mea: $('#mea').val(),
                            gea: $('#gea').val(),

                            moccupation: $('#input_mother_occupation_new').val(),
                            foccupation: $('#input_father_occupation_new').val(),
                            relation: $('#input_guardian_relation_new').val(),

                            fmi: $('#fmi').val(),
                            mmi: $('#mmi').val(),
                            gmi: $('#gmi').val(),

                            fosoi: $('#fosoi').val(),
                            mosoi: $('#mosoi').val(),
                            gosoi: $('#gosoi').val(),

                            fethnicity: $('#fethnicity').val(),
                            methnicity: $('#methnicity').val(),
                            gethnicity: $('#gethnicity').val(),


                        },
                        success: function(data) {
                            if (data[0].status == 1) {
                                Toast.fire({
                                    type: 'success',
                                    title: data[0].message
                                })
                                get_last_index('studinfo_more')
                                get_last_index('apmc_midinfo')
                                get_last_index('studinfo')
                                get_last_index('student_pregistration')
                            } else if (data[0].status == 2) {
                                Toast.fire({
                                    type: 'warning',
                                    title: data[0].message
                                })
                            } else {
                                Toast.fire({
                                    type: 'error',
                                    title: data[0].message
                                })
                            }
                        },
                        error: function() {
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                        }
                    })
                } else {
                    $.ajax({
                        type: 'GET',
                        url: '/student/preregistration/createnewstudent',
                        data: {
                            userid: userid,
                            syid: $('#filter_sy').val(),
                            semid: $('#filter_sem').val(),

                            levelid: $('#input_gradelevel_new').val(),
                            lrn: $('#input_lrn_new').val(),

                            altsid: $('#input_altsid_new').val(),
                            mol: $('#input_mol_new').val(),

                            studtype: $('#input_studtype_new').val(),
                            studgrantee: $('#input_grantee_new').val(),
                            pantawid: $('#input_pantawid_new').val(),
                            admissiontype: $('#input_addtype_new').val(),
                            strandid: $('#input_strand_new').val(),
                            courseid: $('#input_course_new').val(),
                            curriculum: $('#input_curriculum_new').val(),
                            maidenname: $('#input_mothermaidename_new').val(),
                            firstname: $('#input_fname_new').val(),
                            lastname: $('#input_lname_new').val(),
                            middlename: $('#input_mname_new').val(),
                            suffix: $('#input_suffix_new').val(),
                            dob: $('#input_dob_new').val(),
                            semail: $('#input_semail_new').val(),
                            gender: $('#input_gender_new').val(),
                            nationality: $('#input_nationality_new').val(),
                            contactno: $('#input_scontact_new').val(),
                            ismothernum: ismothernum,
                            isfathernum: isfathernum,
                            isguardiannum: isguardiannum,

                            street: $('#input_street_new').val(),
                            barangay: $('#input_barangay_new').val(),
                            district: $('#input_district_new').val(),
                            city: $('#input_city_new').val(),
                            province: $('#input_province_new').val(),
                            region: $('#input_region_new').val(),

                            ffname: $('#input_father_fname_new').val(),
                            fmname: $('#input_father_mname_new').val(),
                            flname: $('#input_father_lname_new').val(),
                            fsuffix: $('#input_father_sname_new').val(),
                            fcontactno: $('#input_father_contact_new').val(),
                            foccupation: $('#input_father_occupation_new').val(),

                            mfname: $('#input_mother_fname_new').val(),
                            mmname: $('#input_mother_mname_new').val(),
                            mlname: $('#input_mother_lname_new').val(),
                            msuffix: $('#input_mother_sname_new').val(),
                            mcontactno: $('#input_mother_contact_new').val(),
                            moccupation: $('#input_mother_occupation_new').val(),

                            gfname: $('#input_guardian_fname_new').val(),
                            gmname: $('#input_guardian_mname_new').val(),
                            glname: $('#input_guardian_lname_new').val(),
                            gsuffix: $('#input_guardian_sname_new').val(),
                            gcontactno: $('#input_guardian_contact_new').val(),
                            relation: $('#input_guardian_relation_new').val(),

                            mtname: $('#input_mt_new option:selected').text(),
                            egname: $('#input_egroup_new option:selected').text(),
                            religionname: $('#input_religion_new option:selected').text(),

                            mtid: $('#input_mt_new').val(),
                            egid: $('#input_egroup_new').val(),
                            religionid: $('#input_religion_new').val(),

                            psschoolname: $('#psschoolname').val(),
                            pssy: $('#pssy').val(),
                            gsschoolname: $('#gsschoolname').val(),
                            gssy: $('#gssy').val(),
                            jhsschoolname: $('#jhsschoolname').val(),
                            jhssy: $('#jhssy').val(),
                            shsschoolname: $('#shsschoolname').val(),
                            shssy: $('#shssy').val(),
                            collegeschoolname: $('collegeschoolname').val(),
                            collegesy: $('#collegesy').val(),

                            vacc: $('input[name="vacc"]:checked').val(),
                            vacc_type_1st: $('#vacc_type_1st').val(),
                            vacc_type_2nd: $('#vacc_type_2nd').val(),
                            vacc_type_booster: $('#vacc_type_booster').val(),
                            vacc_type_text_1st: $('#vacc_type_1st option:selected').text(),
                            vacc_type_text_2nd: $('#vacc_type_2nd option:selected').text(),
                            vacc_type_text_booster: $('#vacc_type_booster option:selected').text(),
                            vacc_card_id: $('#vacc_card_id').val(),
                            dose_date_1st: $('#dose_date_1st').val(),
                            dose_date_2nd: $('#dose_date_2nd').val(),
                            dose_date_booster: $('#dose_date_booster').val(),
                            philhealth: $('#philhealth').val(),
                            bloodtype: $('#bloodtype').val(),
                            allergy: $('#allergy').val(),
                            allergy_to_med: $('#allergy_to_med').val(),
                            med_his: $('#med_his').val(),
                            other_med_info: $('#other_med_info').val(),

                            bec_cell: $('#bec_cell').val(),
                            chapelzone: $('#chapelzone').val(),

                            mfi: $('#input_mfi_new').val(),
                            psschooltype: $('#psschooltype').val(),
                            gsschooltype: $('#gsschooltype').val(),
                            jhsschooltype: $('#jhsschooltype').val(),
                            shsschooltype: $('#shsschooltype').val(),
                            collegeschooltype: $('#collegeschooltype').val(),

                            lastschoolatt: $('#last_school_att').val(),

                            pob: $('#pob').val(),
                            maritalstatus: $('#input_marital_new').val(),
                            nocitf: $('#input_ncf_new').val(),
                            noce: $('#input_nce_new').val(),
                            lsah: $('#input_lsah_new').val(),
                            oitf: oitf,

                            glits: $('#last_school_lvlid').val(),
                            scn: $('#last_school_no').val(),
                            cmaosla: $('#last_school_add').val(),

                            //socio economic profile start
                            fea: $('#fea').val(),
                            mea: $('#mea').val(),
                            gea: $('#gea').val(),

                            moccupation: $('#input_mother_occupation_new').val(),
                            foccupation: $('#input_father_occupation_new').val(),
                            relation: $('#input_guardian_relation_new').val(),

                            fmi: $('#fmi').val(),
                            mmi: $('#mmi').val(),
                            gmi: $('#gmi').val(),

                            fosoi: $('#fosoi').val(),
                            mosoi: $('#mosoi').val(),
                            gosoi: $('#gosoi').val(),

                            fethnicity: $('#fethnicity').val(),
                            methnicity: $('#methnicity').val(),
                            gethnicity: $('#gethnicity').val(),
                            //socio economic profile end
                        },
                        success: function(data) {
                            if (data[0].status == 1) {
                                Toast.fire({
                                    type: 'success',
                                    title: data[0].message
                                })
                                load_update_info_datatable()
                                // load_all_preregstudent()
                            } else if (data[0].status == 2) {
                                Toast.fire({
                                    type: 'warning',
                                    title: data[0].message
                                })
                            } else if (data[0].status == 3) {
                                Toast.fire({
                                    type: 'error',
                                    title: data[0].message
                                })
                            }
                        },
                        error: function() {
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                        }
                    })
                }
            })


            // $("#input_sem").empty();
            // $("#input_sem").select2({
            //       data:all_sem,
            //       placeholder: "Select Semester",
            //       dropdownCssClass: "myFont"
            // })
            $(document).on('change', '#input_studstat', function() {
                if ($(this).val() == 3 || $(this).val() == 5 || $(this).val() == 6) {
                    $('#remarks_holder').removeAttr('hidden')
                    $('#status_date_label').text($('#input_studstat option:selected').text())

                } else {
                    $('#remarks_holder').attr('hidden', 'hidden')


                    $('#en_remarks').val("")
                    $('#input_studstatdate').val("")
                }


            })


            $(document).on('click', '.enroll', function() {

                $('#enrollment_button_text').text('')

                $('.has-error').removeClass('has-error')
                $('.is-invalid').removeClass('is-invalid')

                $('#enroll_student_button').text('Enroll Student')

                $('#delete_student_button').removeAttr('hidden')

                $('#enroll_student_button').attr('data-p', 'create')
                $('#enroll_student_button').addClass('btn-primary')
                $('#enroll_student_button').removeClass('btn-success')

                selected = $(this).attr('data-id')

                if (usertype == 6 || refid == 28 || refid == 31 || refid == 30) {
                    display_student_info()
                    return false;
                }



                selected_prereg = $(this).attr('data-preregid')
                var temp_studinfo = all_students.filter(x => x.studid == selected)
                selectedCourse = temp_studinfo[0].courseid
                selectedCollege = temp_studinfo[0].collegeid

                $('#view_requirements').attr('href', '/student/requirements?student=' + temp_studinfo[0]
                    .sid)

                $('#input_gradelevel').val(temp_studinfo[0].levelid).change()
                $('#input_course').val(temp_studinfo[0].courseid).change()
                $('#student_name').text(temp_studinfo[0].sid + ' : ' + temp_studinfo[0].student)
                $('#enroll_student_button').removeAttr('hidden')
                // $('#isEarly').prop('checked',false)
                $('#label_currentgradelevel').text(temp_studinfo[0].curlevelname)
                $('#input_grantee').val(temp_studinfo[0].grantee).change()
                $('#input_mol').val(temp_studinfo[0].mol).change()


                if (temp_studinfo[0].studisactive == 1) {
                    $('#mark_as_active').attr('hidden', 'hidden')
                    $('#mark_as_inactive').removeAttr('hidden')
                } else {
                    $('#mark_as_inactive').attr('hidden', 'hidden')
                    $('#mark_as_active').removeAttr('hidden')
                }

                var temp_sem = all_sem.filter(x => x.id == $('#filter_sem').val())
                var temp_sy = sy.filter(x => x.id == $('#filter_sy').val())
                $("#input_sem").empty();
                $("#input_sem").select2({
                    data: temp_sem,
                    placeholder: "Select Semester",
                })

                var date = moment().format('YYYY-MM-DD');
                $('#input_enrollmentdate').val(date)

                $('#enroll_student_button').removeAttr('hidden')
                $('#input_gradelevel').removeAttr('disabled')
                $('#input_section').removeAttr('disabled')
                $('#input_grantee').removeAttr('disabled')
                $('#input_studstat').removeAttr('disabled')
                $('#input_enrollmentdate').removeAttr('disabled')
                $('#input_mol').removeAttr('disabled')
                $('#input_strand').removeAttr('disabled')
                $('#input_course').removeAttr('disabled')

                if (usertype_session == 8 || usertype_session == 4 || usertype_session == 15 ||
                    usertype_session == 14 || temp_sy[0].ended == 1) {
                    $('#input_gradelevel').attr('disabled', 'disabled')
                    $('#input_section').attr('disabled', 'disabled')
                    $('#input_grantee').attr('disabled', 'disabled')
                    $('#input_studstat').attr('disabled', 'disabled')
                    $('#input_enrollmentdate').attr('disabled', 'disabled')
                    $('#input_mol').attr('disabled', 'disabled')
                    $('#input_strand').attr('disabled', 'disabled')
                    $('#input_course').attr('disabled', 'disabled')
                    $('#udpate_student_information').attr('hidden', 'hidden')
                }

                $('#input_section').val("").change()
                $('#input_strand').val("").change()
                $('#input_strand').val("").change()
                $('#en_remarks').val("")
                $('#input_studstatdate').val("")
                $('#label_strand_holder').attr('hidden', 'hidden')



                if (temp_studinfo[0].gradelvl_to_enroll == 14 || temp_studinfo[0].gradelvl_to_enroll ==
                    15) {
                    $('#strand_holder').removeAttr('hidden')
                } else {
                    $('#strand_holder').attr('hidden', 'hidden')
                }

                if (temp_studinfo[0].levelid == 14 || temp_studinfo[0].levelid == 15) {
                    if (temp_studinfo[0].strandname == null) {
                        var temp_strand = active_strand.filter(x => x.id == temp_studinfo[0]
                            .admission_strand)
                        if (temp_strand.length > 0) {
                            $('#label_strand').text(temp_strand[0].strandname)
                        } else {
                            $('#label_strand').text('No strand assigned')
                        }

                    } else {
                        $('#label_strand').text(temp_studinfo[0].strandname)
                    }

                    $('#label_strand_holder').removeAttr('hidden')
                }

                $('#readytoenroll_student_button').attr('hidden', 'hidden')
                $('#cancel_readytoenroll_student_button').attr('hidden', 'hidden')
                $('#nodp_student_button').attr('hidden', 'hidden')
                $('#cancel_nodp_student_button').attr('hidden', 'hidden')

                if (temp_studinfo[0].levelid == 14 || temp_studinfo[0].levelid == 15 || (temp_studinfo[0]
                        .levelid >= 17 && temp_studinfo[0].levelid <= 21)) {
                    $('#sem_sy_label').text('S.Y.: ' + $('#filter_sy option:selected').text() + ' (' + $(
                        '#filter_sem option:selected').text() + ')')
                } else {
                    $('#sem_sy_label').text('S.Y.: ' + $('#filter_sy option:selected').text())
                }

                if (temp_studinfo[0].levelid >= 17 && temp_studinfo[0].levelid <= 21) {
                    update_gradelevel_input($('#input_gradelevel').val(), temp_studinfo[0].sectionid)
                } else {
                    update_gradelevel_input($('#input_gradelevel').val(), null)
                }


                if (temp_studinfo[0].levelid >= 17 && temp_studinfo[0].levelid <= 21) {
                    $('#input_section').val(temp_studinfo[0].sectionid).change()
                    // $('#input_section').attr('disabled','disabled')
                    $('#input_grantee_holder').attr('hidden', 'hidden')
                } else {
                    // $('#input_section').removeAttr('disabled')
                    $('#input_grantee_holder').removeAttr('hidden')
                }


                var temp_sy = all_sy.filter(x => x.id == temp_studinfo[0].syid)

                $("#input_sy").empty();
                $("#input_sy").select2({
                    data: temp_sy,
                    placeholder: "Select School Year",
                    dropdownCssClass: "myFont"
                })

                var temp_sem = all_sem.filter(x => x.id == temp_studinfo[0].semid)

                $('#enrollment_message_receiever').empty()
                update_enrollment_message_reciever_display(temp_studinfo)

                $('#input_studstat').val(1).change()

                load_college_subject_load()
                load_documents()
                get_enrollment_history()
                get_update_info_history()
                balance_history()



                if (usertype_session == 4 || usertype_session == 15) {
                    $('#enroll_student_button').attr('hidden', 'hidden')
                    $('#delete_student_button').attr('hidden', 'hidden')
                    if (temp_studinfo[0].nodp == 1) {
                        $('#cancel_nodp_student_button').removeAttr('hidden')
                    } else {
                        $('#nodp_student_button').removeAttr('hidden')
                    }
                } else {
                    $('#cancel_nodp_student_button').attr('hidden', 'hidden')
                    $('#nodp_student_button').attr('hidden', 'hidden')
                }

                if (usertype_session == 4 || usertype_session == 15) {
                    $('#enroll_student_button').attr('hidden', 'hidden')
                    $('#delete_student_button').attr('hidden', 'hidden')
                    $('#readytoenroll_student_button').attr('hidden', 'hidden')
                    $('#cancel_readytoenroll_student_button').attr('hidden', 'hidden')

                    if (temp_studinfo[0].finance_status == 'APPROVED') {
                        $('#cancel_readytoenroll_student_button').removeAttr('hidden')
                    } else {
                        $('#readytoenroll_student_button').removeAttr('hidden')
                    }
                }

                if (usertype_session == 16 || usertype_session == 14) {
                    $('#enroll_student_button').attr('hidden', 'hidden')
                    $('#delete_student_button').attr('hidden', 'hidden')
                    $('#readytoenroll_student_button').attr('hidden', 'hidden')
                    $('#cancel_readytoenroll_student_button').attr('hidden', 'hidden')
                }

                if (temp_studinfo[0].levelid == 14) {
                    if (usertype_session == 8) {
                        $('#nodp_student_button').removeAttr('hidden')
                    }
                }

                if (usertype_session == 8) {
                    $('#delete_student_button').attr('hidden', 'hidden')
                    $('#readytoenroll_student_button').attr('hidden', 'hidden')
                    $('#cancel_readytoenroll_student_button').attr('hidden', 'hidden')
                    $('#enroll_student_button').attr('hidden', 'hidden')
                    if (temp_studinfo[0].admission_status == 'APPROVED') {
                        $('#cancel_readytoenroll_student_button').removeAttr('hidden')
                    } else {
                        $('#readytoenroll_student_button').removeAttr('hidden')
                    }
                }

                $('#enroll_student_button').attr('disabled', 'disabled')
                $('#ribbon_holder').attr('hidden', 'hidden')

                // if(usertype_session == 3){

                if (temp_studinfo[0].can_enroll == 1) {
                    $('#enroll_student_button').removeAttr('disabled')
                    $('#ribbon_holder').removeAttr('hidden')
                    if (temp_studinfo[0].withpayment == 1) {
                        $('#ribbon_text').removeClass('bg-primary')
                        $('#ribbon_text').addClass('bg-success')
                        $('#ribbon_text')[0].innerHTML = '&nbsp;&nbsp;&nbsp;  DP Paid'
                    }
                    if (temp_studinfo[0].nodp == 1) {
                        $('#ribbon_text').addClass('bg-primary')
                        $('#ribbon_text').removeClass('bg-success')
                        $('#ribbon_text')[0].innerHTML = 'NO DP<br>Allowed'
                    }
                } else {
                    $('#enrollment_button_text')[0].innerHTML =
                        '<b>No payment found.</b> <br><i>Please proceed to the cashier for student payment or finance to allow no Down Payment.</i>'
                }


                var temp_id = $('#input_gradelevel').val()
                var temp_acad = gradelevel.filter(x => x.id == temp_id)
                var check_admissionsetup = all_admissionsetup.filter(x => x.acadprogid == temp_acad[0]
                    .acadprogid)

                if (check_admissionsetup.length == 0) {
                    $('#enroll_student_button').attr('disabled', 'disabled')
                    $('#enrollment_button_text')[0].innerHTML =
                        '<b>No Active Admission Date.</b><br> <i><a href="/admission/setup" target="_blank">Click here</a> to create admission date.</i>'
                }

                if (usertype_session == 4 || usertype_session == 15) {
                    $('#view_update_student_info').attr('hidden', 'hidden')
                }

                // }

                if (temp_sy[0].ended == 1) {
                    $('#enroll_student_button').attr('disabled', 'disabled')
                } else {
                    // $('#enroll_student_button').removeAttr('disabled','disabled')
                }

                if (usertype_session == 16 || usertype_session == 14) {
                    $('#enroll_student_button').attr('hidden', 'hidden')
                    $('#delete_student_button').attr('hidden', 'hidden')
                    $('#readytoenroll_student_button').attr('hidden', 'hidden')
                    $('#cancel_readytoenroll_student_button').attr('hidden', 'hidden')
                    $('#nodp_student_button').attr('hidden', 'hidden')
                    $('#mark_as_inactive').attr('hidden', 'hidden')
                    $('#view_update_student_info').removeAttr('hidden')
                    $('#update_student_information_button').attr('hidden', 'hidden')
                }

                $('#student_cor').attr('hidden', 'hidden')
                $('#enrollment_modal').modal()
            })


            $(document).on('click', '.view_enrollment', function() {

                $('#enrollment_button_text').text('')

                $('#delete_student_button').attr('hidden', 'hidden')
                $('#mark_as_inactive').attr('hidden', 'hidden')

                $('#enroll_student_button').text('Update Enrollment')

                $('#enroll_student_button').attr('data-p', 'update')
                $('#enroll_student_button').addClass('btn-success')
                $('#enroll_student_button').removeClass('btn-primary')

                selected = $(this).attr('data-id')
                selected_prereg = $(this).attr('data-preregid')
                var temp_studinfo = all_students.filter(x => x.studid == selected)
                selectedCourse = temp_studinfo[0].courseid
                selectedCollege = temp_studinfo[0].collegeid

                if (usertype == 6 || refid == 28 || refid == 29 || refid == 31) {
                    display_student_info()
                    return false;
                }


                $('#input_gradelevel').val(temp_studinfo[0].enlevelid).change()


                $('#student_name').text(temp_studinfo[0].sid + ' : ' + temp_studinfo[0].student)
                $('#enroll_student_button').removeAttr('hidden')
                $('#enroll_student_button').removeAttr('disabled')
                // if(temp_studinfo[0].isearly == 1){
                //       $('#isEarly').prop('checked',true)
                // }else{
                //       $('#isEarly').prop('checked',false)
                // }



                $('#input_grantee').val(temp_studinfo[0].grantee).change()
                $('#input_mol').val(temp_studinfo[0].mol).change()
                $('#input_enrollmentdate').val(temp_studinfo[0].dateenrolled).change()
                $('#input_studstatdate').val(temp_studinfo[0].studstatdate).change()
                $('#label_currentgradelevel').text(temp_studinfo[0].curlevelname)
                $('#input_course').val(temp_studinfo[0].courseid).change()

                $('#input_section').val("").change()
                $('#input_strand').val("").change()

                if (temp_studinfo[0].levelid == 14 || temp_studinfo[0].levelid == 15 || (temp_studinfo[0]
                        .levelid >= 17 && temp_studinfo[0].levelid <= 21)) {
                    $('#sem_sy_label').text('S.Y.: ' + $('#filter_sy option:selected').text() + ' ( ' + $(
                        '#filter_sem option:selected').text() + ' )')
                } else {
                    $('#sem_sy_label').text('S.Y.: ' + $('#filter_sy option:selected').text())
                }

                var temp_sem = all_sem.filter(x => x.id == $('#filter_sem').val())
                var temp_sy = all_sy.filter(x => x.id == $('#filter_sy').val())

                $("#input_sem").empty();
                $("#input_sem").select2({
                    data: temp_sem,
                    placeholder: "Select Semester",
                })

                update_gradelevel_input($('#input_gradelevel').val(), temp_studinfo[0].ensectionid)

                if ($('#input_gradelevel').val() == 14 || $('#input_gradelevel').val() == 15) {
                    update_strand_input(temp_studinfo[0].ensectionid)
                }



                var temp_sy = all_sy.filter(x => x.id == temp_studinfo[0].syid)

                if ((temp_studinfo[0].promotionstatus != 0
                        // && (temp_studinfo[0].levelid != 14 && temp_studinfo[0].levelid != 15 ) 
                    ) ||
                    temp_sy[0].ended == 1 || usertype_session == 8) {
                    // if((temp_studinfo[0].levelid != 14 && temp_studinfo[0].levelid != 15 ) || temp_sy[0].ended == 1){
                    $('#delete_student_button').attr('hidden', 'hidden')
                    $('#enroll_student_button').attr('hidden', 'hidden')
                    $('#input_gradelevel').attr('disabled', 'disabled')
                    $('#input_section').attr('disabled', 'disabled')
                    $('#input_grantee').attr('disabled', 'disabled')
                    $('#input_studstat').attr('disabled', 'disabled')
                    $('#input_course').attr('disabled', 'disabled')
                    $('#input_enrollmentdate').attr('disabled', 'disabled')
                    $('#input_regStatus').attr('disabled', 'disabled')
                    $('#en_remarks').attr('disabled', 'disabled')
                    $('#input_studstatdate').attr('disabled', 'disabled')

                    // $('#isEarly').attr('disabled','disabled')
                    $('#input_mol').attr('disabled', 'disabled')
                    $('#input_strand').attr('disabled', 'disabled')
                    // }
                } else {

                    if (usertype_session == 3) {
                        $('#input_regStatus').removeAttr('disabled')
                        $('#enroll_student_button').removeAttr('hidden')
                        $('#input_gradelevel').removeAttr('disabled')
                        $('#input_section').removeAttr('disabled')
                        $('#input_grantee').removeAttr('disabled')
                        $('#input_studstat').removeAttr('disabled')
                        $('#input_course').removeAttr('disabled')
                        $('#input_enrollmentdate').removeAttr('disabled')

                        $('#en_remarks').removeAttr('disabled')
                        $('#input_studstatdate').removeAttr('disabled')

                        // $('#isEarly').removeAttr('disabled')
                        $('#input_mol').removeAttr('disabled')
                        $('#input_strand').removeAttr('disabled')
                    }
                }

                if (temp_studinfo[0].levelid >= 17 && temp_studinfo[0].levelid <= 21) {
                    $('#input_section').val(temp_studinfo[0].sectionid).change()
                    // $('#input_section').attr('disabled','disabled')
                    $('#input_grantee_holder').attr('hidden', 'hidden')
                    $('#student_cor').removeAttr('hidden')
                    $('#input_regStatus').val(temp_studinfo[0].regStatus).change()

                } else {
                    $('#input_grantee_holder').removeAttr('hidden')
                }




                $("#input_sy").empty();
                $("#input_sy").select2({
                    data: temp_sy,
                    placeholder: "Select School Year",
                    dropdownCssClass: "myFont"
                })

                $('#readytoenroll_student_button').attr('hidden', 'hidden')
                $('#nodp_student_button').attr('hidden', 'hidden')
                $('#cancel_nodp_student_button').attr('hidden', 'hidden')

                $('#cancel_readytoenroll_student_button').attr('hidden', 'hidden')

                $('#input_studstat').val(temp_studinfo[0].studstatus).change()
                $('#en_remarks').val(temp_studinfo[0].remarks).change()

                $('#enrollment_message_receiever').empty()
                update_enrollment_message_reciever_display(temp_studinfo)

                load_college_subject_load()
                load_documents()
                get_enrollment_history()
                get_update_info_history()
                balance_history()
                if (usertype_session == 4 || usertype_session == 15 || usertype_session == 8 ||
                    usertype_session == 14) {
                    $('#delete_student_button').attr('hidden', 'hidden')
                    $('#enroll_student_button').attr('hidden', 'hidden')
                }

                if (usertype_session == 4 || usertype_session == 15) {
                    $('#view_update_student_info').attr('hidden', 'hidden')
                }
                if (temp_studinfo[0].can_enroll == 1) {
                    $('#enroll_student_button').removeAttr('disabled')
                    $('#ribbon_holder').removeAttr('hidden')
                    if (temp_studinfo[0].withpayment == 1) {
                        $('#ribbon_text').removeClass('bg-primary')
                        $('#ribbon_text').addClass('bg-success')
                        $('#ribbon_text')[0].innerHTML = '&nbsp;&nbsp;&nbsp;  DP Paid'
                    }
                    if (temp_studinfo[0].nodp == 1) {
                        $('#ribbon_text').addClass('bg-primary')
                        $('#ribbon_text').removeClass('bg-success')
                        $('#ribbon_text')[0].innerHTML = 'NO DP<br>Allowed'
                    }
                }

                $('#enrollment_modal').modal()
            })

            $("#input_addtype_old").empty();
            $("#input_addtype_old").select2({
                placeholder: 'Select Admission Type',
                data: []
            })

            $(document).on('click', '#delete_student_button', function() {
                Swal.fire({
                    text: 'Are you sure you want to remove learner?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'GET',
                            url: '/student/preregistration/removelearner',
                            data: {
                                studid: selected
                            },
                            success: function(data) {
                                if (data[0].status == 1) {
                                    Toast.fire({
                                        type: 'success',
                                        title: data[0].message
                                    })
                                    $('#enrollment_modal').modal('hide')
                                    load_update_info_datatable()
                                } else {
                                    Toast.fire({
                                        type: 'error',
                                        title: data[0].message
                                    })
                                }
                            }
                        })
                    }
                })

            })

            $(document).on('click', '#mark_as_active', function() {
                Swal.fire({
                    text: 'Are you sure you want to mark learner as Active?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Mark as Active'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'GET',
                            url: '/student/preregistration/markActiveStatus',
                            data: {
                                studid: selected,
                                status: 1
                            },
                            success: function(data) {
                                if (data[0].status == 1) {
                                    Toast.fire({
                                        type: 'success',
                                        title: data[0].message
                                    })
                                    $('#mark_as_active').attr('hidden', 'hidden')
                                    $('#mark_as_inactive').removeAttr('hidden')
                                    $('#enrollment_modal').modal('hide')
                                    load_update_info_datatable()
                                } else {
                                    Toast.fire({
                                        type: 'error',
                                        title: data[0].message
                                    })
                                }
                            }
                        })
                    }
                })

            })

            $(document).on('click', '#mark_as_inactive', function() {
                Swal.fire({
                    text: 'Are you sure you want to mark learner as Inactive?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Mark as Inactive'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'GET',
                            url: '/student/preregistration/markActiveStatus',
                            data: {
                                studid: selected,
                                status: 0
                            },
                            success: function(data) {
                                if (data[0].status == 1) {
                                    Toast.fire({
                                        type: 'success',
                                        title: data[0].message
                                    })
                                    $('#mark_as_inactive').attr('hidden', 'hidden')
                                    $('#mark_as_active').removeAttr('hidden')
                                    $('#enrollment_modal').modal('hide')
                                    load_update_info_datatable()
                                } else {
                                    Toast.fire({
                                        type: 'error',
                                        title: data[0].message
                                    })
                                }
                            }
                        })
                    }
                })

            })

            function add_student_to_prereg_proccess() {

                if ($('#input_add_student').val() == "") {
                    Toast.fire({
                        type: 'warning',
                        title: "No student selected!"
                    })
                    return false
                }


                if ($('#input_add_gradelevel').val() == 14 || $('#input_add_gradelevel').val() == 15) {
                    if ($('#input_add_strand').val() == "" || $('#input_add_strand').val() == null) {
                        Toast.fire({
                            type: 'warning',
                            title: "No strand selelected!"
                        })
                        return false
                    }
                }

                if ($('#input_add_gradelevel').val() == 17 || $('#input_add_gradelevel').val() == 18 || $(
                        '#input_adinput_add_gradeleveld_course').val() == 19 || $('#input_add_gradelevel').val() ==
                    20) {
                    if ($('#input_add_course').val() == "" || $('#input_add_strand').val() == null) {
                        Toast.fire({
                            type: 'warning',
                            title: "No course selelected!"
                        })
                        return false
                    }
                }


                if ($('#input_addtype_old').val() == "") {
                    Toast.fire({
                        type: 'warning',
                        title: "No admission type selected!"
                    })
                    return false
                }


                if ($('#input_addtype_old').val() == "") {
                    Toast.fire({
                        type: 'warning',
                        title: "No admission type selected!"
                    })
                    return false
                }

                if (school_setup.setup == 1) {
                    $.ajax({
                        type: 'GET',
                        url: school_setup.es_cloudurl + 'student/preregistration/addstudenttoprereg',
                        data: {
                            userid: userid,
                            syid: $('#filter_sy').val(),
                            semid: $('#filter_sem').val(),
                            studid: $('#input_add_student').val(),
                            levelid: $('#input_add_gradelevel').val(),
                            admissiontype: $('#input_addtype_old').val(),
                            strand: $('#input_add_strand').val(),
                            course: $('#input_add_course').val()
                        },
                        success: function(data) {
                            if (data[0].status == 1) {




                                Toast.fire({
                                    type: 'success',
                                    title: data[0].message
                                })

                                var temp_info = all_studinfo.filter(x => x.id == $('#input_add_student')
                                    .val())
                                student_to_enroll = temp_info[0].sid



                                get_last_index('student_pregistration')

                                $('#add_stud_prereg_modal').modal('hide')
                            } else if (data[0].status == 2) {
                                Toast.fire({
                                    type: 'warning',
                                    title: data[0].message
                                })
                            } else if (data[0].status == 3) {
                                Toast.fire({
                                    type: 'error',
                                    title: data[0].message
                                })
                            }


                        },
                        error: function() {
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                        }
                    })
                } else {
                    $.ajax({
                        type: 'GET',
                        url: '/student/preregistration/addstudenttoprereg',
                        data: {
                            userid: null,
                            syid: $('#filter_sy').val(),
                            semid: $('#filter_sem').val(),
                            studid: $('#input_add_student').val(),
                            levelid: $('#input_add_gradelevel').val(),
                            admissiontype: $('#input_addtype_old').val(),
                            strand: $('#input_add_strand').val(),
                            course: $('#input_add_course').val()
                        },
                        success: function(data) {
                            if (data[0].status == 1) {
                                Toast.fire({
                                    type: 'success',
                                    title: data[0].message
                                })

                                var info_index = all_students.findIndex(x => x.studid == $(
                                    '#input_add_student').val())

                                all_students[info_index].admission_strand = $('#input_add_strand').val()
                                all_students[info_index].admission_course = $('#input_add_course').val()
                                all_students[info_index].admission_type = $('#input_addtype_old').val()
                                all_students[info_index].gradelvl_to_enroll = $('#input_add_gradelevel')
                                    .val()
                                all_students[info_index].levelid = $('#input_add_gradelevel').val()
                                all_students[info_index].admission_type_desc = $(
                                    '#input_addtype_old option:selected').text()
                                all_students[info_index].withprereg = 1
                                all_students[info_index].id = data[0].id

                                var temp_info = all_studinfo.filter(x => x.id == $('#input_add_student')
                                    .val())
                                student_to_enroll = temp_info[0].sid
                                $('#add_stud_prereg_modal').modal('hide')

                                load_update_info_datatable()

                                // load_all_preregstudent()
                            } else if (data[0].status == 2) {
                                Toast.fire({
                                    type: 'warning',
                                    title: data[0].message
                                })
                            } else if (data[0].status == 3) {
                                Toast.fire({
                                    type: 'error',
                                    title: data[0].message
                                })
                            }
                        },
                        error: function() {
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                        }
                    })
                }
            }

            function enroll_student() {



                if ($('#input_section').val() == "" || $('#input_section').val() == null) {
                    if (school_setup.abbreviation == 'DCC') {
                        if ($('#input_gradelevel').val() < 17) {
                            Toast.fire({
                                type: 'warning',
                                title: 'No Section Selected!'
                            })
                            return false
                        }
                    } else {
                        Toast.fire({
                            type: 'warning',
                            title: 'No Section Selected!'
                        })
                        return false
                    }
                }

                if ($('#input_gradelevel').val() == 14 || $('#input_gradelevel').val() == 15) {
                    if ($('#input_strand').val() == "" || $('#input_strand').val() == null) {
                        Toast.fire({
                            type: 'warning',
                            title: 'No Strand Selected!'
                        })
                        return false
                    }
                }

                if ($('#input_gradelevel').val() >= 17 && $('#input_gradelevel').val() <= 20) {
                    if ($('#units_enrolled').text() == 0) {
                        Toast.fire({
                            type: 'warning',
                            title: 'No Subject Loaded!'
                        })
                        return false
                    }
                }


                // var isearly = 0

                // if($('#isEarly').prop('checked') == true){
                //       isearly = 1
                // }

                var temp_studinfo = all_students.filter(x => x.studid == selected)

                if (temp_studinfo[0].can_enroll == 0) {
                    Toast.fire({
                        type: 'warning',
                        title: 'Cannot Enroll Learner!'
                    })
                    return false;
                }


                var parent_contact = null
                var student_contact = temp_studinfo[0].contactno;

                if (temp_studinfo[0].ismothernum == 1 && temp_studinfo[0].mcontactno != '') {
                    parent_contact = temp_studinfo[0].mcontactno
                    parent_label = 'Mother'
                } else if (temp_studinfo[0].isfathernum == 1 && temp_studinfo[0].fcontactno != '') {
                    parent_contact = temp_studinfo[0].fcontactno
                    parent_label = 'Father'
                } else if (temp_studinfo[0].isguardannum == 1 && temp_studinfo[0].gcontactno != '') {
                    parent_contact = temp_studinfo[0].gcontactno
                    parent_label = 'Mother'
                }

                if (student_contact == null || student_contact == '') {
                    Toast.fire({
                        type: 'warning',
                        title: 'No Student Contact #'
                    })
                    return false;
                } else if ((student_contact != null && parent_contact == '') && student_contact.toString().length !=
                    11) {
                    Toast.fire({
                        type: 'warning',
                        title: "Student contact # is invalid!"
                    })
                    return false
                } else if (parent_contact == null || student_contact == '') {
                    Toast.fire({
                        type: 'warning',
                        title: 'No Parent Contact #'
                    })
                    return false;
                } else if ((parent_contact != null && parent_contact == '') && parent_contact.toString().length !=
                    11) {
                    Toast.fire({
                        type: 'warning',
                        title: "Parent contact # is invalid!"
                    })
                    return false
                }

                if (school_setup.withMOL == 1) {
                    if ($('#input_mol').val() == "") {
                        Toast.fire({
                            type: 'warning',
                            title: 'Mode of learning is required!'
                        })
                        return false
                    }
                }

                $.ajax({
                    type: 'GET',
                    url: '/student/preregistration/student/enroll',
                    data: {
                        studid: selected,
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_sem').val(),
                        levelid: $('#input_gradelevel').val(),
                        sectionid: $('#input_section').val(),
                        courseid: $('#input_course').val(),
                        studstatus: $('#input_studstat').val(),
                        strandid: $('#input_strand').val(),
                        grantee: $('#input_grantee').val(),
                        mol: $('#input_mol').val(),
                        enrollmentdate: $('#input_enrollmentdate').val(),
                        studstatdate: $('#input_studstatdate').val(),
                        remarks: $('#en_remarks').val(),
                        preregid: temp_studinfo[0].id,
                        regStatus: $('#input_regStatus').val(),
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                            $('#enroll_student_button').attr('hidden', 'hidden')

                            if (school_setup.setup == 1) {
                                get_last_index('preregistrationrequirements')
                                get_last_index('student_pregistration')
                            }

                            var selected_section = $('#input_section').val()
                            var selected_strand = $('#input_strand').val()

                            var section_index = all_sections.findIndex(x => x.id == $('#input_section')
                                .val() && x.levelid == $('#input_gradelevel').val())

                            if (section_index == -1) {
                                var section_index = all_sections.findIndex(x => x.id == $(
                                    '#input_section').val() && x.levelid == null)
                            }

                            if (section_index != -1) {

                                if (all_sections[section_index].enrolled != undefined) {

                                    all_sections[section_index].enrolled += 1;




                                    var cap = all_sections[section_index].capacity == null ? 0 :
                                        all_sections[section_index].capacity
                                    all_sections[section_index].text = all_sections[section_index]
                                        .sectionname + ' (' + all_sections[section_index].enrolled +
                                        '/' + cap + ')'

                                    if ($('#input_gradelevel').val() == 17 || $('#input_gradelevel')
                                        .val() == 18 || $('#input_gradelevel').val() == 19 || $(
                                            '#input_gradelevel').val() == 20) {
                                        var temp_sections = all_sections.filter(x => x.levelid ==
                                            all_sections[section_index].levelid || x.levelid == null
                                        )
                                    } else {
                                        var temp_sections = all_sections.filter(x => x.levelid ==
                                            all_sections[section_index].levelid)
                                    }



                                    $("#input_section").empty();
                                    $("#input_section").append(
                                        '<option value="">Select Section</option>');
                                    $("#input_section").select2({
                                        data: temp_sections,
                                        allowClear: true,
                                        placeholder: "Select a section",
                                        dropdownCssClass: "myFont"
                                    })

                                    if (all_sections[section_index].acadprogid == 5) {
                                        $("#input_section").val(selected_section).change()
                                        $("#input_strand").val(selected_strand).change()
                                    } else {
                                        $("#input_section").val(selected_section).change()
                                    }
                                }
                            }

                            load_enrollment_summary()
                            load_update_info_datatable()
                            get_enrollment_history()
                        } else if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                        } else if (data[0].status == 3) {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    },
                    error: function() {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                })

            }

            function update_student() {

                if ($('#input_section').val() == "" || $('#input_section').val() == null) {
                    Toast.fire({
                        type: 'warning',
                        title: 'No Section Selected!'
                    })
                    return false
                }

                if ($('#input_gradelevel').val() == 14 || $('#input_gradelevel').val() == 15) {
                    if ($('#input_strand').val() == "" || $('#input_strand').val() == null) {
                        Toast.fire({
                            type: 'warning',
                            title: 'No Strand Selected!'
                        })
                        return false
                    }
                }

                //      var isearly = 0

                //      if($('#isEarly').prop('checked') == true){
                //            isearly = 1
                //      }

                var temp_studinfo = all_students.filter(x => x.studid == selected)
                var parent_contact = null
                var student_contact = temp_studinfo[0].contactno;

                if (temp_studinfo[0].ismothernum == 1 && temp_studinfo[0].mcontactno != '') {
                    parent_contact = temp_studinfo[0].mcontactno
                    parent_label = 'Mother'
                } else if (temp_studinfo[0].isfathernum == 1 && temp_studinfo[0].fcontactno != '') {
                    parent_contact = temp_studinfo[0].fcontactno
                    parent_label = 'Father'
                } else if (temp_studinfo[0].isguardannum == 1 && temp_studinfo[0].gcontactno != '') {
                    parent_contact = temp_studinfo[0].gcontactno
                    parent_label = 'Mother'
                }

                if (student_contact == null || student_contact == '') {
                    Toast.fire({
                        type: 'warning',
                        title: 'No Student Contact #'
                    })
                    return false;
                } else if ((student_contact != null && parent_contact == '') && student_contact.toString().length !=
                    11) {
                    Toast.fire({
                        type: 'warning',
                        title: "Student contact # is invalid!"
                    })
                    return false
                } else if (parent_contact == null || student_contact == '') {
                    Toast.fire({
                        type: 'warning',
                        title: 'No Parent Contact #'
                    })
                    return false;
                } else if ((parent_contact != null && parent_contact == '') && parent_contact.toString().length !=
                    11) {
                    Toast.fire({
                        type: 'warning',
                        title: "Parent contact # is invalid!"
                    })
                    return false
                }

                if (school_setup.withMOL == 1) {
                    if ($('#input_mol').val() == "" || $('#input_mol').val() == null) {
                        Toast.fire({
                            type: 'warning',
                            title: 'Mode of learning is required!'
                        })
                        return false
                    }
                }

                var temp_course = temp_studinfo[0].courseid

                if (temp_course != $('#input_course').val()) {
                    temp_course = $('#input_course').val()
                }

                $.ajax({
                    type: 'GET',
                    url: '/student/preregistration/student/enroll/update',
                    data: {
                        studid: selected,
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_sem').val(),
                        levelid: $('#input_gradelevel').val(),
                        sectionid: $('#input_section').val(),
                        studstatus: $('#input_studstat').val(),
                        strandid: $('#input_strand').val(),
                        // isearly:isearly,
                        dateenrolled: temp_studinfo[0].dateenrolled,
                        courseid: temp_course,
                        grantee: $('#input_grantee').val(),
                        mol: $('#input_mol').val(),
                        enrollmentdate: $('#input_enrollmentdate').val(),
                        preregid: temp_studinfo[0].id,
                        remarks: $('#en_remarks').val(),
                        regStatus: $('#input_regStatus').val(),
                        studstatdate: $('#input_studstatdate').val(),
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })

                            // if($('#input_studstat').val() == 0){
                            //       $('#enrollment_modal').modal('hide')
                            //       load_update_info_datatable()
                            //       return false
                            // }

                            var temp_date = moment($('#input_enrollmentdate').val()).format(
                                'MMM DD, YYYY');

                            var prereg_index = all_students.findIndex(x => x.studid == selected)

                            var section_index = all_sections.findIndex(x => x.id == temp_studinfo[0]
                                .ensectionid && x.levelid == $('#input_gradelevel').val())

                            if (section_index == -1) {
                                var section_index = all_sections.findIndex(x => x.id == temp_studinfo[0]
                                    .ensectionid && x.levelid == null)
                            }

                            if (section_index != -1) {
                                all_sections[section_index].enrolled -= 1;


                                var cap = all_sections[section_index].capacity == null ? 0 :
                                    all_sections[section_index].capacity
                                all_sections[section_index].text = all_sections[section_index]
                                    .sectionname + ' (' + all_sections[section_index].enrolled + '/' +
                                    cap + ')'
                            }

                            if (prereg_index != -1 && section_index != -1) {
                                all_students[prereg_index].studstatus = $('#input_studstat').val();
                                all_students[prereg_index].description = $(
                                    '#input_studstat option:selected').text();

                                all_students[prereg_index].ensectionid = $('#input_section').val();
                                all_students[prereg_index].sectionname = all_sections[section_index]
                                    .sectionname;

                                all_students[prereg_index].dateenrolled = $('#input_enrollmentdate')
                                    .val();
                                all_students[prereg_index].enrollment = temp_date;

                                all_students[prereg_index].enlevelid = $('#input_gradelevel').val();
                                all_students[prereg_index].levelname = $(
                                    '#input_gradelevel option:selected').text().replace(' COLLEGE',
                                    '');

                                if ($('#input_gradelevel').val() == 14 || $('#input_gradelevel')
                                    .val() == 15) {
                                    all_students[prereg_index].strandid = $('#input_strand').val();
                                } else {
                                    all_students[prereg_index].strandid = null
                                }


                                if ($('#input_studstat').val() == 0) {
                                    all_students[prereg_index].sectionname = '--';
                                    all_students[prereg_index].mol = 0
                                } else {
                                    all_students[prereg_index].mol = $('#input_mol').val()
                                }
                            }

                            var selected_section = $('#input_section').val()
                            var selected_strand = $('#input_strand').val()


                            var section_index = all_sections.findIndex(x => x.id == $('#input_section')
                                .val() && x.levelid == $('#input_gradelevel').val())

                            if (section_index == -1) {
                                var section_index = all_sections.findIndex(x => x.id == $(
                                    '#input_section').val() && x.levelid == null)
                            }

                            if (section_index == -1) {
                                if (all_sections[section_index] !== undefined) {
                                    if (all_sections[section_index].enrolled !== undefined) {
                                        if ($('#filter_studstatus').val() == 5 || $(
                                                '#filter_studstatus').val() == 6 || $(
                                                '#filter_studstatus').val() == 3 || $(
                                                '#filter_studstatus').val() == 0) {
                                            all_sections[section_index].enrolled -= 1;
                                        }

                                    }
                                }
                            }

                            // if(section_index != -1){
                            //       all_sections[section_index].enrolled += 1;

                            //       var cap  = all_sections[section_index].capacity == null ? 0 : all_sections[section_index].capacity
                            //       all_sections[section_index].text = all_sections[section_index].sectionname+' ('+all_sections[section_index].enrolled+'/'+cap+')'
                            // }

                            if ($('#input_gradelevel').val() == 17 || $('#input_gradelevel').val() ==
                                18 || $('#input_gradelevel').val() == 19 || $('#input_gradelevel')
                                .val() == 20) {
                                var temp_sections = all_sections.filter(x => x.levelid == $(
                                    '#input_gradelevel').val() || x.levelid == null)
                            } else {
                                var temp_sections = all_sections.filter(x => x.levelid == $(
                                    '#input_gradelevel').val())
                            }

                            if (section_index != -1) {

                                $("#input_section").empty();
                                $("#input_section").append('<option value="">Select Section</option>');
                                $("#input_section").select2({
                                    data: temp_sections,
                                    allowClear: true,
                                    placeholder: "Select a section",
                                    dropdownCssClass: "myFont"
                                })

                                if (all_sections[section_index].acadprogid == 5) {
                                    $("#input_section").val(selected_section).change()
                                    $("#input_strand").val(selected_strand).change()
                                } else {
                                    $("#input_section").val(selected_section).change()
                                }
                            }

                            // $('#enroll_student_button').attr('hidden','hidden')

                            // var enrollment_index = all_enrollment_history.findIndex(x=>x.syid == $('#filter_sy').val())
                            // all_enrollment_history[enrollment_index].levelid = $('#input_gradelevel').val()
                            // all_enrollment_history[enrollment_index].acadprogid = gradelevel.filter(x=>x.id == $('#input_gradelevel').val())[0].acadprogid
                            // all_enrollment_history[enrollment_index].semester = $('#filter_sem').val() == 1 ? '1st Sem' : '2nd Sem'

                            // all_enrollment_history[enrollment_index].dateenrolled = temp_date
                            // all_enrollment_history[enrollment_index].levelname = $('#input_gradelevel option:selected').text()
                            // all_enrollment_history[enrollment_index].sectionname = all_sections[section_index].sectionname
                            // all_enrollment_history[enrollment_index].strandcode = $('#input_strand option:selected').text()


                            // if($('#input_studstat').val() == 0){
                            //       var temp_enrollment_history = []
                            //       $.each(all_enrollment_history, function(a,b){
                            //             if($('#input_gradelevel').val() == 14 || $('#input_gradelevel').val() == 15){
                            //                   var temp_sem = '1st Sem'
                            //                   if($('#filter_sem').val() == 2){
                            //                         temp_sem = '2nd Sem'
                            //                   }
                            //                   if(b.syid == $('#filter_sy').val() && b.levelid == $('#input_gradelevel').val() && b.semester ==  temp_sem){}else{
                            //                         temp_enrollment_history.push(b)
                            //                   }
                            //             }else{
                            //                   if(b.syid == $('#filter_sy').val() && b.levelid == $('#input_gradelevel').val()){}else{
                            //                         temp_enrollment_history.push(b)
                            //                   }
                            //             }
                            //       })
                            //       all_enrollment_history = temp_enrollment_history
                            // }

                            $('#enrollment_modal').modal('hide')
                            load_update_info_datatable()
                            return false

                            // display_enrollment_history(all_enrollment_history)
                            // load_enrollment_summary()
                            // get_enrollment_history()
                            // load_update_info_datatable()

                        } else if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                        } else if (data[0].status == 3) {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    },
                    error: function() {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                })

            }



            function getcollegesection(sectionid) {
                $.ajax({
                    type: 'GET',
                    url: '/student/preregistration/collegesection',
                    async: false,
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_sem').val(),
                        studid: selected,
                    },
                    success: function(data) {
                        var tempdata = data
                        if (sectionid != null) {
                            tempdata = data.filter(x => x.id == sectionid && x.sectionid ==
                                selectedCourse)
                            if (tempdata.length == 0) {
                                tempdata = data.filter(x => x.courseid == selectedCourse)
                            }
                            if (tempdata.length == 0) {
                                tempdata = data.filter(x => x.collegeid == selectedCollege)
                            }
                            if (tempdata.length == 0) {
                                tempdata = tempdata.filter(x => x.courseid == selectedCourse)
                            }
                        } else {
                            tempdata = []
                        }

                        updateSectionOption(tempdata, sectionid)
                    }
                })
            }

            function updateSectionOption(temp_sections, sectionid) {

                if (sectionid != null) {

                    var unique_section = []
                    var selectedsection = temp_sections.filter(x => x.id == sectionid)

                    if (selectedsection.length > 0) {
                        unique_section.push(selectedsection[0])
                    }

                    $.each(temp_sections, function(a, b) {
                        var check = unique_section.filter(x => x.text == b.text)
                        if (check.length == 0) {
                            unique_section.push(b)
                        }
                    })

                    $("#input_section").empty();
                    $("#input_section").append('<option value="">Select Section</option>');
                    $("#input_section").select2({
                        data: unique_section,
                        allowClear: true,
                        placeholder: "Select a section",
                        dropdownCssClass: "myFont"
                    })

                    $('#input_section').val(sectionid).change()
                } else {

                    var unique_section = []

                    $.each(temp_sections, function(a, b) {
                        var check = unique_section.filter(x => x.text == b.text)
                        if (check.length == 0) {
                            unique_section.push(b)
                        }
                    })

                    $("#input_section").empty();
                    $("#input_section").append('<option value="">Select Section</option>');
                    $("#input_section").select2({
                        data: unique_section,
                        allowClear: true,
                        placeholder: "Select a section",
                        dropdownCssClass: "myFont"
                    })


                }
            }

            function update_gradelevel_input(gradelevel, sectionid = null) {


                if (gradelevel >= 17 && gradelevel <= 21) {
                    // var temp_sections = all_sections.filter( x=>  ( x.levelid >= 17 || x.levelid == null ) && x.semid == $('#filter_sem').val())
                    getcollegesection(sectionid)
                } else {
                    var temp_sections = all_sections.filter(x => x.levelid == gradelevel)
                    updateSectionOption(temp_sections, sectionid)
                }

                $('#input_strand').empty()



                if (gradelevel == 14 || gradelevel == 15) {
                    $('#strand_holder').removeAttr('hidden')
                } else {
                    $('#strand_holder').attr('hidden', 'hidden')
                }

                if (school_setup.abbreviation == 'DCC') {
                    if (gradelevel >= 17 && gradelevel <= 21) {
                        $('.is_dcc').removeAttr('hidden')
                        $('#section_holder').attr('hidden', 'hidden')
                        // get_college_info()
                    } else {
                        $('.is_dcc').attr('hidden', 'hidden')
                        $('#section_holder').removeAttr('hidden')
                    }
                } else {
                    if (gradelevel >= 17 && gradelevel <= 21) {
                        $('.is_college').removeAttr('hidden')
                        // get_college_info()
                    } else {
                        $('.is_college').attr('hidden', 'hidden')
                    }
                }




            }

            function update_strand_input(sectionid = null) {
                if (sectionid == null) {
                    $("#input_section").empty();
                    return false
                }

                var temp_strand = all_strand.filter(x => x.sectionid == sectionid && x.syid == $('#filter_sy')
                    .val())
                $("#input_strand").empty();
                $("#input_strand").append('<option value="">Select Strand</option>');
                $("#input_strand").select2({
                    data: temp_strand,
                    allowClear: true,
                    placeholder: "Select a Strand",
                    dropdownCssClass: "myFont"
                })
                var temp_studinfo = all_students.filter(x => x.studid == selected)
                $("#input_strand").val(temp_studinfo[0].strandid).change()
            }


            function get_enrollment_history() {
                all_enrollment_history = []
                $('#enrollment_history').empty()
                $.ajax({
                    type: 'GET',
                    url: '/student/preregistration/student/enrollmenthistory',
                    data: {
                        studid: selected,
                        courseid: selectedCourse
                    },
                    success: function(data) {
                        all_enrollment_history = data

                        all_enrollment_history.filter(x => x.syid == $('#filter_sy').val())

                        display_enrollment_history(data)
                    }
                })
            }

            function balance_history() {
                all_enrollment_history = []
                $('#balance_history').empty()
                $.ajax({
                    type: 'GET',
                    url: '/student/preregistration/student/balancehistory',
                    data: {
                        studid: selected
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            $.each(data, function(a, b) {
                                $('#balance_history').append('<tr><td> ' + b.sydesc +
                                    '</td><td>&#8369;  ' + parseFloat(b.balance).toFixed(2)
                                    .replace(/\d(?=(\d{3})+\.)/g, "$&,") + '</td></tr>')
                            })
                        }
                    }
                })
            }


            function display_enrollment_history(data) {
                $('#enrollment_history').empty()
                if (data.length == 0) {
                    $('#enrollment_history').append('<tr><td colspan="5">No Records Found.</td></tr>')
                } else {
                    $.each(data, function(a, b) {

                        var print_button =
                            '<button class="btn btn-default btn-sm print_button" style="font-size:.7rem !important; padding: 0rem 0.25rem !important;" data-sy="' +
                            b.syid + '" data-sem="' + b.semid + '" data-lvlid="' + b.levelid +
                            '"><i class="fas fa-print"></i></button>'

                        if (usertype_session == 15 || usertype_session == 4) {
                            print_button = ''
                        }

                        var other = ''
                        var semdesc = ''
                        if (b.acadprogid == 5) {
                            other = '<span class="text-success"> : ' + b.strandcode + '</span>'
                            semdesc = '<span class="text-success"> : ' + b.semester + '</span>'
                        }
                        if (b.acadprogid == 6) {
                            other = '<span class="text-success"> : ' + b.courseabrv + '</span>'
                            semdesc = '<span class="text-success"> : ' + b.semester + '</span>'
                        }
                        $('#enrollment_history').append('<tr><td class="align-middle">' + b.sydesc +
                            semdesc + '</td><td class="align-middle">' + b.levelname +
                            '</td><td class="align-middle">' + b.sectionname + other +
                            '</td><td style="font-size:.6rem !important;" class="align-middle">' + b
                            .dateenrolled + '</td><td class="text-center align-middle">' +
                            print_button + '</td></tr>')
                    })
                }
            }

            $(document).on('click', '.print_button', function() {
                var temp_lvl = $(this).attr('data-lvlid');
                var temp_syid = $(this).attr('data-sy');
                var temp_semid = $(this).attr('data-sem');
                var date = moment(new Date()).format("YYYY-MM-DD");

                $('#printable_list_holder').empty();
                if (temp_lvl >= 17 && temp_lvl <= 21) {
                    $('#printable_list_holder').append(
                        '<a class="btn btn-sm btn-default mt-2" target="_blank" href="/printcor/' +
                        selected + '?semid=' + temp_semid + '&syid=' + temp_syid + '&levelid=' +
                        temp_lvl + '&format=1' +
                        '"><i class="fas fa-print"></i> COR</a><a class="btn btn-sm btn-default ml-2  mt-2" target="_blank" href="/printcor/' +
                        selected + '?semid=' + temp_semid + '&syid=' + temp_syid + '&levelid=' +
                        temp_lvl + '&format=2' +
                        '"><i class="fas fa-print"></i> COR (Format 1)</a><hr><a class="btn btn-sm btn-default " target="_blank" href="/college/grades/summary/print/pdf?semid=' +
                        temp_semid + '&syid=' + temp_syid + '&studid=' + selected +
                        '"><i class="fas fa-print"></i> Grades</a>')
                } else if (temp_lvl == 14 || temp_lvl == 15) {
                    $('#printable_list_holder').append(
                        '<a class="btn btn-sm btn-default" target="_blank" href="/prinsf9print/' +
                        selected + '?semid=' + temp_semid + '&syid=' + temp_syid +
                        '"><i class="fas fa-print"></i> Report Card (SF9)</a><br><a class="btn btn-sm btn-default mt-2" target="_blank" href="/printable/certification/generate/?export=pdf&template=shs&syid=' +
                        temp_syid + '&semid=' + temp_semid + '&studid=' + selected + '&givendate=' +
                        date +
                        '"><i class="fas fa-print"></i> Certificate of Enrollment</a><a class="btn btn-sm btn-default mt-2" target="_blank" href="/printable/fees/generate/?export=pdf&template=shs&syid=' +
                        temp_syid + '&semid=' + temp_semid + '&studid=' + selected + '&givendate=' +
                        date + '"><i class="fas fa-print"></i> Print Fees</a>')
                } else {
                    $('#printable_list_holder').append(
                        '<a class="btn btn-sm btn-default" target="_blank" href="/prinsf9print/' +
                        selected + '?semid=' + temp_semid + '&syid=' + temp_syid +
                        '"><i class="fas fa-print"></i> Report Card (SF9)</a><br><a class="btn btn-sm btn-default mt-2 mr-2" target="_blank" href="/printable/certification/generate/?export=pdf&template=jhs&syid=' +
                        temp_syid + '&studid=' + selected + '&givendate=' + date +
                        '"><i class="fas fa-print"></i> Certificate of Enrollment</a><a class="btn btn-sm btn-default mt-2" target="_blank" href="/printable/fees/generate/?export=pdf&template=shs&syid=' +
                        temp_syid + '&semid=' + temp_semid + '&studid=' + selected + '&givendate=' +
                        date + '"><i class="fas fa-print"></i> Print Fees</a>')
                }
                $('#printable_list_modal').modal()
            })

            function get_update_info_history() {
                $('#new_update_history').empty()
                $('#info_footer_holder').attr('hidden', 'hidden')
                $.ajax({
                    type: 'GET',
                    url: '/student/preregistration/student/updatehistory',
                    data: {
                        studid: selected,
                        syid: $('#filter_sy').val()
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            $('#info_footer_holder').removeAttr('hidden')
                            $.each(data[0].data, function(a, b) {
                                var old = b.old == null ? 'No record' : b.old
                                var newdata = b.new == null ? 'No record' : b.new
                                $('#new_update_history').append('<tr><td>' + b.field +
                                    '</td><td>' + old + '</td><td>' + newdata + '</td></tr>'
                                )
                            })
                        } else {
                            $('#new_update_history').append(
                                '<tr><td colspan="3">No New Information Available</td></tr>')
                        }
                    }
                })
            }

        })
    </script>


    <script>
        //mode of learning
        var all_mol = []
        get_mol()

        function get_mol() {
            $.ajax({
                type: 'GET',
                url: '/setup/modeoflearning/list',
                data: {
                    syid: $('#filter_sy').val(),
                    status: 1
                },
                success: function(data) {
                    all_mol = data
                }
            })
        }
    </script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        })

        var school_setup = @json($schoolinfo);
        var all_students = []
        var all_studinfo = []
        var all_admissiontype = []
        var all_admissionsetup = []
        var all_enrollment_history = []
        // var gradelevel = @json($gradelevel);
        var acadprog_display = @json($acadprog);
        var userid = @json(auth()->user()->id);
        var usertype = @json(auth()->user()->type);
        var refid = @json($refid);
        var usertype_session = @json(Session::get('currentPortal'));
        var sy = @json($sy);
        var all_strand = @json($allstrand);
        var all_studstat = @json($studstatus);
        var active_strand = @json($strand);
        var all_sy = @json($sy);
        var all_sem = @json($semester);
        var all_grantee = @json($grantee);
        var all_sections = []
        var selected_prereg = null
        var selected = null
        var selectedCourse = null
        var selectedCollege = null
        var student_to_enroll = "";

        if (usertype_session == 15 || usertype_session == 4 || usertype_session == 14 || usertype_session == 16) {
            $('#delete_student_button').remove()
            $('#mark_as_inactive').remove()
            $('#mark_as_active').remove()
            $('#view_requirements').remove()
        }

        if (usertype == 6 || refid == 28 || refid == 29) {
            $('#filter_process').attr('disabled', 'disabled')
        }
        if (usertype == 6 || refid == 28) {
            $('#delete_student_button').remove()
            $('#mark_as_inactive').remove()
            $('#enroll_student_button').remove()
            $('#update_student_information_button').remove()
            $('#enrollment_form').remove()
            $('#enrollment_form').remove()

            $('input').attr('disabled', 'disabled')
            $('select').attr('disabled', 'disabled')

            $('#filter_studstatus').removeAttr('disabled')
            $('#filter_sy').removeAttr('disabled')
            $('#filter_sem').removeAttr('disabled')
            $('#filter_gradelevel').removeAttr('disabled')
            $('#filter_paymentstat').removeAttr('disabled')
            $('#filter_activestatus').removeAttr('disabled')

            $('#filter_studstatus').val("").change()

        }

        if (usertype_session == 14 || usertype_session == 16) {
            $('#update_student_information_button').remove()
            $('#enrollment_form').remove()
            $('input').attr('disabled', 'disabled')
            $('select').attr('disabled', 'disabled')

            $('#filter_studstatus').removeAttr('disabled')
            $('#filter_sy').removeAttr('disabled')
            $('#filter_sem').removeAttr('disabled')
            $('#filter_gradelevel').removeAttr('disabled')
            $('#filter_paymentstat').removeAttr('disabled')
            $('#filter_activestatus').removeAttr('disabled')

            $('#filter_studstatus').val("").change()

            $('#stdprgEnrollmentForm').attr('hidden', 'hidden')
        }


        if (school_setup.setup == 1) {
            get_last_index('preregistrationrequirements')
            get_last_index('apmc_midinfo')
            get_last_index('studinfo_more')
            get_last_index('studinfo')
            get_last_index('modeoflearning_student')
            get_last_index('student_pregistration')
            get_last_index('student_updateinformation')
        }

        if (school_setup.withMOL == 1) {
            $('#mol_holder').removeAttr('hidden')
        }



        load_enrollment_summary()

        function load_enrollment_summary() {
            $.ajax({
                type: 'GET',
                url: '/student/preregistration/enrollmentinfo',
                data: {
                    syid: $('#filter_sy').val(),
                    semid: $('#filter_sem').val()
                },
                success: function(data) {
                    $.each(data, function(a, b) {
                        $('.badge_stat_count[data-id="' + b.id + '"]').text(b.count)
                    })
                    // load_update_info_datatable(prompt)

                }
            })
        }

        load_all_sections()

        function load_all_sections() {
            $.ajax({
                type: 'GET',
                url: '/student/preregistration/sections',
                data: {
                    syid: $('#filter_sy').val()
                },
                success: function(data) {
                    all_sections = data
                }
            })
        }

        get_admission_type()
        get_admission_setup()

        function get_admission_type() {
            $.ajax({
                type: 'GET',
                url: '/student/preregistration/admissiontype',
                success: function(data) {
                    all_admissiontype = data
                    $("#filter_entype").empty();
                    $("#filter_entype").append('<option value="">All</option>');
                    $("#filter_entype").select2({
                        data: all_admissiontype,
                        allowClear: true,
                        placeholder: "All",
                    })
                    // load_all_preregstudent()
                }
            })
        }

        function get_admission_setup() {
            $.ajax({
                type: 'GET',
                url: '/student/preregistration/admissionsetup',
                data: {
                    syid: $('#filter_sy').val(),
                    semid: $('#filter_sem').val()
                },
                success: function(data) {
                    all_admissionsetup = data
                }
            })
        }

        function load_all_student() {

            all_studinfo = []
            // $.ajax({
            //       type:'GET',
            //       url:'/student/preregistration/allstudents',
            //       data:{
            //             syid:$('#filter_sy').val(),
            //             semid:$('#filter_sem').val()
            //       },
            //       success:function(data) {

            //             all_studinfo = data

            //             $('#student_count').text(all_studinfo.length)

            //             $("#input_add_student").empty();
            //             $("#input_add_student").append('<option value="">Select Student</option>');
            //             $("#input_add_student").select2({
            //                   data: all_studinfo,
            //                   allowClear: true,
            //                   placeholder: "Select a Student",
            //                   dropdownCssClass: "myFont"
            //             })
            //       }
            // })
        }

        function get_last_index(tablename) {
            $.ajax({
                type: 'GET',
                url: '/monitoring/tablecount',
                data: {
                    tablename: tablename
                },
                success: function(data) {
                    lastindex = data[0].lastindex
                    update_local_table_display(tablename, lastindex)
                },
                error: function() {
                    get_updated(tablename)
                }
            })
        }

        function update_local_table_display(tablename, lastindex) {
            $.ajax({
                type: 'GET',
                url: school_setup.es_cloudurl + '/monitoring/table/data',
                data: {
                    tablename: tablename,
                    tableindex: lastindex
                },
                success: function(data) {
                    if (data.length > 0) {
                        process_create(tablename, 0, data)
                    }
                },
                error: function() {
                    $('td[data-tablename="' + tablename + '"]')[0].innerHTML = 'Error!'
                }
            })
        }

        function process_create(tablename, process_count, createdata) {
            if (createdata.length == 0) {
                if (tablename == 'student_pregistration') {
                    load_update_info_datatable()
                }

                return false;
            }
            var b = createdata[0]
            $.ajax({
                type: 'GET',
                url: '/synchornization/insert',
                data: {
                    tablename: tablename,
                    data: b
                },
                success: function(data) {
                    process_count += 1
                    createdata = createdata.filter(x => x.id != b.id)
                    process_create(tablename, process_count, createdata)
                },
                error: function() {
                    process_count += 1
                    createdata = createdata.filter(x => x.id != b.id)
                    process_create(tablename, process_count, createdata)
                }
            })
        }


        var activeRequestsTable = $('#update_info_request_table').DataTable();
        activeRequestsTable.state.clear();
        activeRequestsTable.destroy();
        load_update_info_datatable(true)

        function load_update_info_datatable(withpromp = false) {

            // var temp_data = all_students
            var filter_status = $('#filter_studstatus').val()
            // var filter_gradelevel = $('#filter_gradelevel').val()
            // var filter_entype = $('#filter_entype').val()
            // var filter_ptype = $('#filter_paymentstat').val()
            // var filter_process = $('#filter_process').val()






            if (filter_status != "") {
                // temp_data = temp_data.filter(x=>x.studstatus == filter_status)
                if (filter_status == 0) {
                    $('.prereg_head[data-id="2"]')[0].innerHTML = 'Grade Level<br>To Enroll'
                    $('.prereg_head[data-id="5"]')[0].innerHTML = ''
                    $('.prereg_head[data-id="3"]')[0].innerHTML = 'Admission Type'
                    $('.prereg_head[data-id="4"]')[0].innerHTML = 'Approval'
                    // var temp_data = temp_data.filter(x=>x.status == 'SUBMITTED')
                } else {

                    $('.prereg_head[data-id="2"]')[0].innerHTML = 'Enrolled<br>Grade Level'
                    $('.prereg_head[data-id="3"]')[0].innerHTML = 'Section'
                    if (school_setup.withMOL == 1) {
                        $('.prereg_head[data-id="4"]')[0].innerHTML = 'MOL'
                    } else {
                        $('.prereg_head[data-id="4"]')[0].innerHTML = ''
                    }

                    $('.prereg_head[data-id="3"]').removeAttr('hidden')
                    // $('.prereg_head[data-id="4"]').removeAttr('hidden')
                    $('.prereg_head[data-id="2"]')[0].innerHTML = 'Grade Level'
                    $('.prereg_head[data-id="5"]')[0].innerHTML = 'Enrollment Date'
                }
            } else {
                // var temp_data = temp_data.filter(x=>x.status == 'SUBMITTED' || ( x.status == 'ENROLLED' && x.studstatus != 0))
                $('.prereg_head[data-id="5"]')[0].innerHTML = 'Date'
                $('.prereg_head[data-id="3"]').text('Section')
                $('.prereg_head[data-id="3"]').removeAttr('hidden')
                $('.prereg_head[data-id="4"]').removeAttr('hidden')
                $('.prereg_head[data-id="2"]')[0].innerHTML = 'Grade Level'
                $('.prereg_head[data-id="7"]')[0].innerHTML = 'Admission Type'
            }

            // if(filter_gradelevel != ""){
            //       temp_data = temp_data.filter(x=>x.levelid == filter_gradelevel)
            // }

            // if(filter_entype != ""){
            //       temp_data = temp_data.filter(x=>x.type == filter_entype)
            // }

            // if(filter_ptype != ""){
            //       if(filter_ptype == 1){
            //             temp_data = temp_data.filter(x=>x.withpayment == 1 || x.nodp == 1)
            //       }else if(filter_ptype == 2){
            //             temp_data = temp_data.filter(x=>x.withpayment == 0)
            //       }
            //       // else if(filter_ptype == 3){
            //       //       temp_data = temp_data.filter(x=>x.nodp == 1)
            //       // }
            // }

            // if(filter_process != ""){
            //       temp_data = temp_data.filter(x=>x.transtype == filter_process)
            // }

            // if(withpromp){
            //       if(temp_data.length == 0){
            //             Toast.fire({
            //                   type: 'error',
            //                   title: 'No student found'
            //             })
            //       }else{
            //             Toast.fire({
            //                   type: 'warning',
            //                   title: temp_data.length+' student(s) found'
            //             })
            //       }
            // }

            var temp_sy = sy.filter(x => x.id == $('#filter_sy').val())

            if (temp_sy.length == 0) {
                var temp_sy = sy.filter(x => x.active == 1)
            }

            temp_sy = temp_sy[0]
            // var firstPrompt = true


            $("#update_info_request_table").DataTable({
                destroy: true,
                // data:temp_data,
                autoWidth: false,
                stateSave: true,
                serverSide: true,
                processing: true,
                // ajax:'/student/preregistration/list',
                ajax: {
                    url: '/student/preregistration/list',
                    type: 'GET',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_sem').val(),
                        addtype: $('#filter_entype').val(),
                        paystat: $('#filter_paymentstat').val(),
                        procctype: $('#filter_process').val(),
                        studstat: $('#filter_studstatus').val(),
                        fillevelid: $('#filter_gradelevel').val(),
                        fillsectionid: $('#filter_section').val(),
                        activestatus: $('#filter_activestatus').val(),
                        transdate: $('#filter_transdate').val(),
                        enrollmentdate: $('#filter_enrollmentdate').val(),
                        processtype: $('#filter_process').val(),
                        action: 'generatedata'
                    },
                    dataSrc: function(json) {

                        all_students = json.data

                        console.log(all_students, 'all students');
                        if (withpromp) {

                            Toast.fire({
                                type: 'info',
                                title: json.recordsTotal + ' student(s) found.'
                            })

                            firstPrompt = false
                        }
                        return json.data;
                    }
                },
                // order: [[ 1, "asc" ]],
                columns: [{
                        "data": "sid"
                    },
                    {
                        "data": "student"
                    },
                    {
                        "data": null
                    },
                    {
                        "data": "sortid"
                    },
                    {
                        "data": "sectionname"
                    },
                    {
                        "data": "description"
                    },
                    {
                        "data": "enrollment"
                    },
                    {
                        "data": "search"
                    },
                ],
                columnDefs: [{
                        'targets': 0,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var text = rowData.student

                            // if(rowData.nodp == 1){
                            // //      text += '<br>'
                            //      text += ' <span class="badge-primary badge">No DP Allowed</span>'
                            // }

                            // if(rowData.withpayment == 1){
                            //      if(rowData.nodp == 1 == 0){
                            //       // text += '<br>'
                            //      }
                            //      text += ' <span class="badge-success badge">Payment : &#8369;'+rowData.payment+'</span>'
                            // }

                            $(td)[0].innerHTML = text

                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            if (usertype_session != 6 && usertype_session != 14 && usertype_session != 16 &&
                                refid != 28 && refid != 29) {
                                var text = ''

                                if (usertype == 6 || refid == 28) {
                                    $(td)[0].innerHTML = text
                                    $(td).addClass('align-middle')
                                    return false;
                                }

                                if (rowData.nodp == 1) {
                                    var text = ' <span class="badge-primary badge">No DP Allowed</span>'
                                    $(td).addClass('bg-primary')
                                    $(td).addClass('text-center')
                                }

                                if (rowData.withpayment == 1) {

                                    if (rowData.studstatus == 1 || rowData.studstatus == 2 || rowData
                                        .studstatus == 4) {
                                        var text = 'DP Paid'
                                        $(td).addClass('text-center')
                                    } else {
                                        var text =
                                            '<span style="font-size:.7rem !important"> &#8369; &nbsp;' +
                                            rowData.payment.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,") +
                                            '</span>'
                                        $(td).addClass('text-right')
                                    }



                                    $(td).addClass('bg-success')
                                }

                                $(td)[0].innerHTML = text
                                $(td).addClass('align-middle')
                            } else {
                                var text = null
                                $(td)[0].innerHTML = text
                                $(td).addClass('align-middle')
                            }

                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            if (rowData.studstatus == 0) {
                                // $(td).text(rowData.levelname)
                                $(td)[0].innerHTML = '<span  style="font-size:13px !important">' + rowData
                                    .levelname + '</span>'
                            } else {
                                $(td)[0].innerHTML = '<span style="font-size:13px !important">' + rowData
                                    .levelname + '</span>'
                            }
                            $(td).addClass('align-middle')
                            $(td).addClass('text-center')
                        }
                    },
                    {
                        'targets': 4,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).removeAttr('hidden')
                            if (filter_status == 0 && filter_status != '') {
                                if (rowData.withprereg == 1) {
                                    $(td)[0].innerHTML = rowData.admission_type_desc + ' : ' +
                                        '<span class="text-success" style="font-size:11px !important">' +
                                        rowData.submission + '</span>'
                                } else {
                                    $(td).text(null)
                                }

                            } else {
                                if (rowData.enlevelid == 14 || rowData.enlevelid == 15) {
                                    $(td)[0].innerHTML = rowData.sectionname +
                                        ' : <span class="text-success" style="font-size:11px !important">' +
                                        rowData.strandcode + '</span>'
                                }

                            }
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 5,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td)[0].innerHTML = '<span style="font-size:.7rem !important">' + rowData
                                .description + '</span>'
                            if (filter_status == 0 && filter_status != '') {

                                var text = ''
                                if (rowData.finance_status == 'APPROVED') {
                                    text +=
                                        '<span class="badge badge-success d-block mt-1">Finance Approved</span> '
                                }
                                if (rowData.admission_status == 'APPROVED') {
                                    text +=
                                        '<span class="badge badge-warning d-block mt-1">Admission Approved</span> '
                                }

                                $(td)[0].innerHTML = text
                            } else {

                                if (school_setup.withMOL == 1) {
                                    var temp_mol = all_mol.filter(x => x.id == rowData.mol)
                                    if (temp_mol.length > 0) {
                                        $(td).text(temp_mol[0].description)
                                    } else {
                                        $(td).text(null)
                                    }
                                } else {
                                    $(td).text(null)
                                }
                                // if(rowData.studstatus == 0){
                                //       $(td)[0].innerHTML = '<span style="font-size:.7rem !important">'+rowData.description+'</span>'
                                // }else{
                                //       // $(td)[0].innerHTML = '<a href="javascript:void(0)" data-preregid="'+rowData.id+'" class="view_enrollment" data-id="'+rowData.studid+'" style="font-size:.7rem !important">'+rowData.description+'</a>'
                                //       rowData.description
                                // }
                                $(td).removeAttr('hidden')

                            }

                            if (rowData.studstatus == 1 || rowData.studstatus == 2 || rowData.studstatus ==
                                4) {
                                // $(td).addClass('bg-success')
                            } else if (rowData.studstatus == 0) {

                            } else {
                                // $(td).addClass('bg-secondary')
                            }

                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 6,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            if (rowData.studstatus == 0) {
                                // $(td).text(rowData.submission)
                            } else {
                                $(td).text(rowData.enrollment)
                            }
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 7,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            // $(row).addClass('enroll')
                            // $(row).addClass('enroll')
                            // if(rowData.studstatus == 0 && temp_sy.ended == 0){
                            //       $(td).addClass('text-center')
                            //       if(usertype == 8 || usertype == 4 || usertype == 15 || usertype_session == 8 || usertype_session == 4 || usertype_session == 15){
                            //           var buttons = '<button data-preregid="'+rowData.id+'" data-id="'+rowData.studid+'" class="btn btn-sm btn-primary enroll btn-block" style="font-size:.5rem !important">VIEW INFO.</button>';
                            //       }else{
                            //             // if(rowData.withprereg == 1){
                            //                   var buttons = '<button data-preregid="'+rowData.id+'" data-id="'+rowData.studid+'" class="btn btn-sm btn-primary enroll btn-block" style="font-size:.5rem !important">ENROLL</button>';
                            //             // }else{
                            //             //       var buttons = '<button data-preregid="'+rowData.id+'" data-id="'+rowData.studid+'" class="btn btn-sm btn-secondary add_student_to_prereg btn-block" style="font-size:.5rem !important">ADD TO PREREG</button>';
                            //             // }
                            //       }
                            //       $(td)[0].innerHTML =  buttons

                            // }else if(rowData.studstatus == 1 || rowData.studstatus == 2 || rowData.studstatus == 4){
                            //       // if(rowData.isearly == 1){
                            //       //       $(td).addClass('bg-warning')
                            //       //       $(td).text('EARLY')
                            //       // }else{
                            //       //       $(td).addClass('bg-success')
                            //       //       $(td).text('REGULAR')
                            //       // }
                            //       $(td).text(null)
                            // }
                            // else if(temp_sy.ended == 1){
                            //       $(td).text(null)
                            // }else{
                            //       $(td).text(null)
                            // }

                            if (rowData.studstatus == 1 || rowData.studstatus == 2 || rowData.studstatus ==
                                4) {
                                var desc = all_admissiontype.filter(x => x.id == rowData.type)
                                if (desc.length > 0) {
                                    // $(td).text(desc[0].description)
                                    $(td)[0].innerHTML = '<span style="font-size:.7rem !important">' + desc[
                                        0].description + '</span>'
                                } else {
                                    $(td).text(null)
                                }

                            } else {
                                $(td).text(null)
                            }


                            $(td).addClass('align-middle')
                            $(td).addClass('text-center')
                        }
                    },
                ],
                createdRow: function(row, data, dataIndex) {


                    $(row).attr("data-id", data.studid);
                    $(row).attr("data-preregid", data.id);

                    // if(usertype == 8 || usertype == 4 || usertype == 15 || usertype_session == 8 || usertype_session == 4 || usertype_session == 15){
                    //       $(row).addClass("enroll");
                    // }

                    if (data.studstatus != 0) {
                        $(row).addClass("view_enrollment");
                    } else {
                        $(row).addClass("enroll");
                    }
                },

            });


            var mol_options =
                '<div class="btn-group ml-2">' +
                '<button type="button" class="btn btn-default btn-sm">Printables</button>' +
                '<button type="button" class="btn btn-default dropdown-toggle dropdown-icon btn-sm" data-toggle="dropdown">' +
                '<span class="sr-only">Toggle Dropdown</span>' +
                '</button>' +
                '<div class="dropdown-menu" role="menu">' +
                '<a class="dropdown-item print_mol" data-id="1" href="#">MOL By MOL</a>' +
                '<a class="dropdown-item print_mol" data-id="2" href="#">MOL By Grade Level</a>' +
                '<a class="dropdown-item print_mol" data-id="3" href="#">MOL By Section</a>' +
                '<a class="dropdown-item print_sf1" data-id="pdf" href="#">SF1(PDF)</a>' +
                '<a class="dropdown-item print_sf1" data-id="excel" href="#">SF1(EXCEL)</a>' +
                '<a class="dropdown-item print_enrollment"  href="#" >Enrollment</a>' +
                '</div>' +
                '</div>'



            if (school_setup.abbreviation == 'BCT') {
                if (usertype_session == 8) {
                    var label_text = $($('#update_info_request_table_wrapper')[0].children[0])[0].children[0]
                    // $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm add_student_to_prereg">Add Student to Preregistration</button><button class="btn btn-primary btn-sm ml-2" id="reservation_list">Reservation List</button>'
                    $(label_text)[0].innerHTML =
                        ' <button class="btn btn-primary btn-sm" id="create_new_student"><i class="fa fa-plus"></i> Create New Student</button> <button class="btn btn-default btn-sm ml-2" id="vac_info"><i class="fa fa-medkit"></i> Vaccine Information</button>' +
                        mol_options
                }

            } else {
                var label_text = $($('#update_info_request_table_wrapper')[0].children[0])[0].children[0]
                if (usertype_session == 3 || usertype_session == 17 || usertype_session == 8) {
                    // $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm add_student_to_prereg" >Add Student to Preregistration</button>'
                    $(label_text)[0].innerHTML =
                        ' <button class="btn btn-primary btn-sm" id="create_new_student"><i class="fa fa-plus"></i> Create New Student</button><button class="btn btn-default btn-sm ml-2" id="vac_info"><i class="fa fa-medkit"></i> Vaccine Information</button>' +
                        mol_options
                } else if (refid == 30) {
                    $(label_text)[0].innerHTML =
                        ' <button class="btn btn-primary btn-sm" id="create_new_student"><i class="fa fa-plus"></i> Create New Student</button>'
                } else {
                    $(label_text)[0].innerHTML = ''
                }

            }

            if (temp_sy.ended == 1) {
                $('.add_student_to_prereg').remove()
            }


            // if(usertype == 3 || usertype_session == 3 || usertype == 17 || usertype_session == 17){
            //       if(student_to_enroll != null && student_to_enroll != ""){
            //             var oTable = $('#update_info_request_table').DataTable();    
            //             oTable.search( student_to_enroll ).draw();
            //       }
            // }

            // if(student_to_enroll == null || student_to_enroll == ""){
            //       var oTable = $('#update_info_request_table').DataTable();    
            //       oTable.search("").draw();
            // }




        }

        $(document).on('click', '#print_student_info', function(){
            print_student_info()
        })

        function print_student_info() {
            var url = "/student/preregistration/list?" + $.param({
                syid: $('#filter_sy').val(),
                semid: $('#filter_sem').val(),
                addtype: $('#filter_entype').val(),
                paystat: $('#filter_paymentstat').val(),
                procctype: $('#filter_process').val(),
                studstat: $('#filter_studstatus').val(),
                fillevelid: $('#filter_gradelevel').val(),
                fillsectionid: $('#filter_section').val(),
                activestatus: $('#filter_activestatus').val(),
                transdate: $('#filter_transdate').val(),
                enrollmentdate: $('#filter_enrollmentdate').val(),
                processtype: $('#filter_process').val(),
                action: 'export'
            });

            // Open the URL in a new window or tab
            window.open(url, '_blank');
        }
    </script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '.print_mol', function() {
                window.open('/student/enrollment/report/mol/?datatype=' + $(this).attr('data-id') +
                    '&syid=' + $('#filter_sy').val(), '_blank');
            })

            $(document).on('click', '.print_sf1', function() {

                if (sf1_warning()) {
                    window.open('/registrar/forms/schoolform1/export/?schoolyear=' + $('#filter_sy').val() +
                        '&semid=' + $('#filter_sem').val() + '&sectionid=' + $('#filter_section')
                        .val() + '&levelid=' + $('#filter_gradelevel').val() + '&exporttype=' + $(this)
                        .attr('data-id'), '_blank');
                }
            })

            function sf1_warning() {
                if ($('#filter_gradelevel').val() == "") {
                    Toast.fire({
                        type: 'warning',
                        title: 'No grade level selected!'
                    })
                    return false
                } else if ($('#filter_section').val() == "" || $('#filter_section').val() == null) {
                    Toast.fire({
                        type: 'warning',
                        title: 'No section selected!'
                    })
                    return false
                } else {
                    return true
                }
            }
        })
    </script>

    <script>
        //reservation trasanction
        var reservation_list_data = []

        function get_reservation_list() {
            $.ajax({
                type: 'GET',
                url: '/student/preregistration/reservation/list',
                data: {
                    syid: $('#filter_sy').val(),
                    semid: $('#filter_sem').val()
                },
                success: function(data) {
                    reservation_list_data = data
                    reservation_list_datatable()
                }
            })
        }

        function reservation_list_datatable() {

            var temp_data = reservation_list_data.filter(x => x.isadded == $('#filter_status_reservation').val())

            $("#reservation_list_table").DataTable({
                destroy: true,
                data: temp_data,
                autoWidth: false,
                stateSave: true,
                lengthChange: false,
                columns: [{
                        "data": "sid"
                    },
                    {
                        "data": "student"
                    },
                    {
                        "data": "levelname"
                    },
                    {
                        "data": "amount"
                    },
                    {
                        "data": null
                    },
                ],
                columnDefs: [{
                    'targets': 4,
                    'orderable': false,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        if (rowData.isadded == 0) {
                            var buttons = '<button data-preregid="' + rowData.id + '" data-id="' +
                                rowData.studid +
                                '" class="btn btn-sm btn-primary reservation_to_prereg btn-block" style="font-size:.5rem !important">Add to Prereg.</button>';
                            $(td)[0].innerHTML = buttons
                        } else {
                            $(td).text(null)
                        }

                    }
                }, ]
            });
        }

        $(document).on('click', '#readytoenroll_student_button', function() {
            $.ajax({
                type: 'GET',
                url: '/student/preregistration/readytoenroll',
                data: {
                    syid: $('#filter_sy').val(),
                    semid: $('#filter_sem').val(),
                    studid: selected
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                        $('#readytoenroll_student_button').attr('hidden', 'hidden')
                        $('#cancel_readytoenroll_student_button').removeAttr('hidden')
                        if (usertype_session == 8) {
                            var info_index = all_students.findIndex(x => x.studid == selected)
                            all_students[info_index].admission_status = 'APPROVED'
                            load_update_info_datatable(false)
                        } else if (usertype_session == 4 || usertype_session == 15) {
                            var info_index = all_students.findIndex(x => x.studid == selected)
                            all_students[info_index].finance_status = 'APPROVED'
                            load_update_info_datatable(false)
                        }


                    } else {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                },
                error: function() {
                    Toast.fire({
                        type: 'error',
                        title: 'Something went wrong!'
                    })
                }
            })
        })


        $(document).on('click', '#cancel_readytoenroll_student_button', function() {
            $.ajax({
                type: 'GET',
                url: '/student/preregistration/readytoenroll/cancel',
                data: {
                    syid: $('#filter_sy').val(),
                    semid: $('#filter_sem').val(),
                    studid: selected
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                        $('#cancel_readytoenroll_student_button').attr('hidden', 'hidden')
                        $('#readytoenroll_student_button').removeAttr('hidden')
                        if (usertype_session == 8) {
                            var info_index = all_students.findIndex(x => x.studid == selected)
                            all_students[info_index].admission_status = 'SUBMITTED'
                            load_update_info_datatable(false)
                        } else if (usertype_session == 4 || usertype_session == 15) {
                            var info_index = all_students.findIndex(x => x.studid == selected)
                            all_students[info_index].finance_status = 'SUBMITTED'
                            load_update_info_datatable(false)
                        }
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                },
                error: function() {
                    Toast.fire({
                        type: 'error',
                        title: 'Something went wrong!'
                    })
                }
            })
        })

        $(document).on('change', '#filter_status_reservation', function() {
            reservation_list_datatable()
        })

        $(document).on('click', '.reservation_to_prereg', function() {
            var temp_id = $(this).attr('data-id')
            var studinfo = reservation_list_data.filter(x => x.studid == temp_id)
            var student_index = reservation_list_data.findIndex(x => x.studid == temp_id)
            var admission_setup = all_admissionsetup.filter(x => x.acadprogid == studinfo[0].acadprogid)

            if (studinfo.length == 0) {
                Toast.fire({
                    type: 'warning',
                    title: "Student not found!"
                })
                return false
            }

            if (studinfo.length == 0) {
                Toast.fire({
                    type: 'warning',
                    title: "No admission setup found!"
                })
                return false
            }

            $.ajax({
                type: 'GET',
                url: '/student/preregistration/addstudenttoprereg',
                data: {
                    userid: null,
                    syid: $('#filter_sy').val(),
                    semid: $('#filter_sem').val(),
                    studid: temp_id,
                    levelid: studinfo[0].levelid,
                    admissiontype: admission_setup[0].id
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                        reservation_list_data[student_index].isadded = 1
                        reservation_list_datatable()

                        reload_prereg()
                    } else if (data[0].status == 2) {
                        Toast.fire({
                            type: 'warning',
                            title: data[0].message
                        })
                    } else if (data[0].status == 3) {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                },
                error: function() {
                    Toast.fire({
                        type: 'error',
                        title: 'Something went wrong!'
                    })
                }
            })

            // function reload_prereg(prompt = true){
            //       $.ajax({
            //             type:'GET',
            //             url:'/student/preregistration/list',
            //             data:{
            //                   syid:$('#filter_sy').val(),
            //                   semid:$('#filter_sem').val()
            //             },
            //             success:function(data) {
            //                   all_students = data
            //                   load_update_info_datatable(false)
            //             }
            //       })
            // }


        })

        $(document).on('click', '#reservation_list', function() {
            get_admission_setup()
            get_reservation_list()
            $('#reservation_list_modal').modal()
        })
    </script>


    <script>
        //allow no dp
        $(document).on('click', '#nodp_student_button', function() {
            $.ajax({
                type: 'GET',
                url: '/student/preregistration/allownodp',
                data: {
                    syid: $('#filter_sy').val(),
                    semid: $('#filter_sem').val(),
                    studid: selected
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                        $('#nodp_student_button').attr('hidden', 'hidden')
                        $('#cancel_nodp_student_button').removeAttr('hidden')
                        var info_index = all_students.findIndex(x => x.studid == selected)

                        $('#ribbon_holder').removeAttr('hidden')
                        $('#ribbon_text')[0].innerHTML = 'NO DP<br>Allowed'
                        all_students[info_index].can_enroll = 1
                        all_students[info_index].nodp = 1
                        load_update_info_datatable(false)
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                },
                error: function() {
                    Toast.fire({
                        type: 'error',
                        title: 'Something went wrong!'
                    })
                }
            })
        })
    </script>

    <script>
        //forms
        $(document).on('click', '#enrollment_form', function() {
            window.open('/registrar/studentinfo/print?studid=' + selected, '_blank');
        })

        $(document).on('click', '#student_cor', function() {
            window.open('/printcor/' + selected + '?semid=' + $('#filter_sem').val() + '&syid=' + $('#filter_sy')
                .val(), '_blank');
        })
    </script>


    <script>
        //cancel allow no dp
        $(document).on('click', '#cancel_nodp_student_button', function() {
            $.ajax({
                type: 'GET',
                url: '/student/preregistration/allownodp/cancel',
                data: {
                    syid: $('#filter_sy').val(),
                    semid: $('#filter_sem').val(),
                    studid: selected
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                        $('#cancel_nodp_student_button').attr('hidden', 'hidden')
                        $('#nodp_student_button').removeAttr('hidden')
                        var info_index = all_students.findIndex(x => x.studid == selected)




                        if (all_students[info_index].withpayment == 0) {
                            $('#ribbon_holder').attr('hidden', 'hidden')
                            all_students[info_index].can_enroll = 0
                        } else {
                            $('#ribbon_text')[0].innerHTML = '&nbsp;&nbsp;&nbsp;  DP Paid'
                        }

                        all_students[info_index].nodp = 0
                        load_update_info_datatable(false)
                    } else {


                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })

                    }
                },
                error: function() {
                    Toast.fire({
                        type: 'error',
                        title: 'Something went wrong!'
                    })
                }
            })
        })
    </script>


    <script>
        var studcurriculum = null

        function display_student_info() {

            $("#mother").prop("checked", false)
            $("#father").prop("checked", false)
            $("#guardian").prop("checked", false)


            $.ajax({
                type: 'GET',
                url: '/student/preregistration/studinfo',
                data: {
                    studid: selected
                },
                success: function(data) {

                    var temp_studinfo = data[0].studinfo[0]
                    var temp_studinfomore = data[0].studinfomore[0]
                    var temp_vaccineinfo = data[0].vaccineinfo[0]
                    var temp_curriculum = data[0].curriculum

                    if (temp_curriculum.length > 0) {
                        studcurriculum = temp_curriculum[0].curriculumid
                    } else {
                        studcurriculum = null
                    }

                    if (temp_studinfo.studstatus != 0) {
                        $('#input_mol_new').attr('disabled', 'disabled')
                        $('#input_grantee_new').attr('disabled', 'disabled')
                    } else {
                        $('#input_mol_new').removeAttr('disabled')
                        $('#input_grantee_new').removeAttr('disabled')
                    }

                    if (temp_studinfo.ismothernum == 1) {
                        $("#mother").prop("checked", true)
                    } else if (temp_studinfo.isfathernum == 1) {
                        $("#father").prop("checked", true)
                    } else {
                        $("#guardian").prop("checked", true)
                    }

                    $('#input_gradelevel_new').val(temp_studinfo.levelid).change()
                    $('#input_lrn_new').val(temp_studinfo.lrn)

                    $('#input_altsid_new').val(temp_studinfo.altsid)
                    $('#input_sid_new').val(temp_studinfo.sid)


                    $('#input_mol_new').val(temp_studinfo.mol).change()

                    $('#input_studtype_new').val(temp_studinfo.studtype).change()
                    $('#input_grantee_new').val(temp_studinfo.grantee).change()
                    $('#input_pantawid_new').val(temp_studinfo.pantawid).change()
                    $('#input_addtype_new').val(temp_studinfo.levelid)
                    $('#input_strand_new').val(temp_studinfo.strandid).change()
                    $('#input_course_new').val(temp_studinfo.courseid).change()

                    $('#input_fname_new').val(temp_studinfo.firstname)
                    $('#input_lname_new').val(temp_studinfo.lastname)
                    $('#input_mname_new').val(temp_studinfo.middlename)
                    $('#input_suffix_new').val(temp_studinfo.suffix)
                    $('#input_dob_new').val(temp_studinfo.dob)
                    $('#input_semail_new').val(temp_studinfo.semail)
                    $('#input_gender_new').val(temp_studinfo.gender).change()
                    $('#input_nationality_new').val(temp_studinfo.nationality).change()
                    $('#input_scontact_new').val(temp_studinfo.contactno)

                    $('#input_street_new').val(temp_studinfo.street)
                    $('#input_barangay_new').val(temp_studinfo.barangay)
                    $('#input_district_new').val(temp_studinfo.district)
                    $('#input_city_new').val(temp_studinfo.city)
                    $('#input_province_new').val(temp_studinfo.province)
                    $('#input_region_new').val(temp_studinfo.region)

                    $('#input_mt_new').val(temp_studinfo.mtid).change()
                    $('#input_egroup_new').val(temp_studinfo.egid).change()
                    $('#input_religion_new').val(temp_studinfo.religionid).change()

                    $('#last_school_att').val(temp_studinfo.lastschoolatt)
                    $('#pob').val(temp_studinfo.pob)
                    $('#input_marital_new').val(temp_studinfo.civilstatus).change()

                    $('.oitf').prop('checked', false)
                    $('#input_oitf_new').val("")

                    $('#input_mothermaidename_new').val(temp_studinfo.mothername)


                    if (data[0].vaccineinfo.length > 0) {

                        $('input[name="vacc"][value="' + temp_vaccineinfo.vacc + '"]').prop('checked', true)
                        $('#vacc_type_1st').val(temp_vaccineinfo.vacc_type_id).change()
                        $('#vacc_type_2nd').val(temp_vaccineinfo.vacc_type_2nd_id).change()
                        $('#vacc_type_booster').val(temp_vaccineinfo.booster_type_id).change()
                        $('#vacc_card_id').val(temp_vaccineinfo.vacc_card_id)
                        $('#dose_date_1st').val(temp_vaccineinfo.dose_date_1st)
                        $('#dose_date_2nd').val(temp_vaccineinfo.dose_date_2nd)
                        $('#dose_date_booster').val(temp_vaccineinfo.dose_date_booster)
                        $('#philhealth').val(temp_vaccineinfo.philhealth)
                        $('#bloodtype').val(temp_vaccineinfo.bloodtype).change()
                        // $('#allergy').val(temp_vaccineinfo.vacc_type_id)
                        $('#allergy_to_med').val(temp_vaccineinfo.allergy_to_med)
                        $('#med_his').val(temp_vaccineinfo.med_his)
                        $('#other_med_info').val(temp_vaccineinfo.other_med_info)

                    } else {
                        $('input[name="vacc"][value="0"]').prop('checked', true)
                        $('#vacc_type_1st').val("").change()
                        $('#vacc_type_2nd').val("").change()
                        $('#vacc_type_booster').val("").change()
                        $('#vacc_card_id').val("")
                        $('#dose_date_1st').val("")
                        $('#dose_date_2nd').val("")
                        $('#dose_date_booster').val("")
                        $('#philhealth').val("")
                        $('#bloodtype').val("").change()
                        // $('#allergy').val("")
                        $('#allergy_to_med').val("")
                        $('#med_his').val("")
                        $('#other_med_info').val("")
                    }

                    if (data[0].studinfomore.length > 0) {
                        $('#input_father_fname_new').val(temp_studinfomore.ffname)
                        $('#input_father_mname_new').val(temp_studinfomore.fmname)
                        $('#input_father_lname_new').val(temp_studinfomore.flname)
                        $('#input_father_sname_new').val(temp_studinfomore.fsuffix)

                        $('#input_mother_fname_new').val(temp_studinfomore.mfname)
                        $('#input_mother_mname_new').val(temp_studinfomore.mmname)
                        $('#input_mother_lname_new').val(temp_studinfomore.mlname)
                        $('#input_mother_sname_new').val(temp_studinfomore.msuffix)

                        $('#input_mother_sname_new').val(temp_studinfomore.msuffix)

                        if (temp_studinfomore.mothermaiden != "" && temp_studinfomore.mothermaiden != null) {
                            $('#input_mothermaidename_new').val(temp_studinfomore.mothermaiden)
                        }

                        $('#input_guardian_fname_new').val(temp_studinfomore.gfname)
                        $('#input_guardian_mname_new').val(temp_studinfomore.gmname)
                        $('#input_guardian_lname_new').val(temp_studinfomore.glname)
                        $('#input_guardian_sname_new').val(temp_studinfomore.gsuffix)

                        $('#psschoolname').val(temp_studinfomore.psschoolname)
                        $('#pssy').val(temp_studinfomore.pssy)
                        $('#gsschoolname').val(temp_studinfomore.gsschoolname)
                        $('#gssy').val(temp_studinfomore.gssy)
                        $('#jhsschoolname').val(temp_studinfomore.jhsschoolname)
                        $('#jhssy').val(temp_studinfomore.jhssy)
                        $('#shsschoolname').val(temp_studinfomore.shsschoolname)
                        $('#shssy').val(temp_studinfomore.shssy)
                        $('#collegeschoolname').val(temp_studinfomore.collegeschoolname)
                        $('#collegesy').val(temp_studinfomore.collegesy)

                        $('#bec_cell').val(temp_studinfomore.bec_cell)
                        $('#chapelzone').val(temp_studinfomore.chapelzone)

                        $('#input_mfi_new').val(temp_studinfomore.mfi)
                        $('#psschooltype').val(temp_studinfomore.psschooltype).change()
                        $('#gsschooltype').val(temp_studinfomore.gsschooltype).change()
                        $('#jhsschooltype').val(temp_studinfomore.jhsschooltype).change()
                        $('#shsschooltype').val(temp_studinfomore.shsschooltype).change()
                        $('#collegeschooltype').val(temp_studinfomore.collegeschooltype).change()

                        $('#input_ncf_new').val(temp_studinfomore.nocitf)
                        $('#input_nce_new').val(temp_studinfomore.noce)
                        $('#input_lsah_new').val(temp_studinfomore.lsah)

                        //socio economic profile start
                        $('#fea').val(temp_studinfomore.fea)
                        $('#mea').val(temp_studinfomore.mea)
                        $('#gea').val(temp_studinfomore.gea)

                        $('#fmi').val(temp_studinfomore.fmi)
                        $('#mmi').val(temp_studinfomore.mmi)
                        $('#gmi').val(temp_studinfomore.gmi)

                        $('#fosoi').val(temp_studinfomore.fosoi)
                        $('#mosoi').val(temp_studinfomore.mosoi)
                        $('#gosoi').val(temp_studinfomore.gosoi)

                        $('#fethnicity').val(temp_studinfomore.fethnicity)
                        $('#methnicity').val(temp_studinfomore.methnicity)
                        $('#gethnicity').val(temp_studinfomore.gethnicity)

                        //socio economic profile end

                        $('#fha').val(temp_studinfomore.fha)
                        $('#mha').val(temp_studinfomore.mha)
                        $('#gha').val(temp_studinfomore.gha)

                        $('#last_school_lvlid').val(temp_studinfomore.glits).change()
                        $('#last_school_no').val(temp_studinfomore.scn)
                        $('#last_school_add').val(temp_studinfomore.cmaosla)

                        if (temp_studinfomore.oitfitf == 'eldest' || temp_studinfomore.oitfitf == '2nd' ||
                            temp_studinfomore.oitfitf == '3rd' || temp_studinfomore.oitfitf == 'youngest'
                        ) {
                            $('.oitf[value="' + temp_studinfomore.oitfitf + '"]').prop('checked', true)
                        } else {
                            $('#input_oitf_new').val(temp_studinfomore.oitfitf)
                        }


                    } else {

                        // $('#last_school_att').val("")

                        $('#pob').val("")
                        $('#input_marital_new').val("").change()
                        $('#input_ncf_new').val("")
                        $('#input_nce_new').val("")
                        $('#input_lsah_new').val("")

                        $('#last_school_lvlid').val("").change()
                        $('#last_school_no').val("")
                        $('#last_school_add').val("")


                        $('#input_father_fname_new').val("")
                        $('#input_father_mname_new').val("")
                        $('#input_father_lname_new').val("")
                        $('#input_father_sname_new').val("")

                        $('#input_mother_fname_new').val("")
                        $('#input_mother_mname_new').val("")
                        $('#input_mother_lname_new').val("")
                        $('#input_mother_sname_new').val("")

                        $('#input_guardian_fname_new').val("")
                        $('#input_guardian_mname_new').val("")
                        $('#input_guardian_lname_new').val("")
                        $('#input_guardian_sname_new').val("")

                        $('#psschoolname').val("")
                        $('#pssy').val("")
                        $('#gsschoolname').val("")
                        $('#gssy').val("")
                        $('#jhsschoolname').val("")
                        $('#jhssy').val("")
                        $('#shsschoolname').val("")
                        $('#shssy').val("")
                        $('#collegeschoolname').val("")
                        $('#collegesy').val("")

                        $('#bec_cell').val("")
                        $('#chapelzone').val("")

                        //socio economic profile start
                        $('#fea').val("")
                        $('#gea').val("")
                        $('#mea').val("")

                        $('#fmi').val("")
                        $('#mmi').val("")
                        $('#gmi').val("")

                        $('#fosoi').val("")
                        $('#mosoi').val("")
                        $('#gosoi').val("")

                        $('#fethnicity').val("")
                        $('#methnicity').val("")
                        $('#gethnicity').val("")
                        //socio economic profile end


                        $('#fha').val("")
                        $('#mha').val("")
                        $('#gha').val("")

                        $('#input_mfi_new').val("")
                        $('#psschooltype').val("").change()
                        $('#gsschooltype').val("").change()
                        $('#jhsschooltype').val("").change()
                        $('#shsschooltype').val("").change()
                        $('#collegeschooltype').val("").change()

                        $('#input_father_fname_new').val(temp_studinfo.fathername)
                        $('#input_mother_fname_new').val(temp_studinfo.mothername)
                        $('#input_guardian_fname_new').val(temp_studinfo.guardianname)
                    }



                    $('#input_father_contact_new').val(temp_studinfo.fcontactno)
                    $('#input_father_occupation_new').val(temp_studinfo.foccupation)



                    $('#input_mother_contact_new').val(temp_studinfo.mcontactno)
                    $('#input_mother_occupation_new').val(temp_studinfo.moccupation)



                    $('#input_guardian_contact_new').val(temp_studinfo.gcontactno)
                    $('#input_guardian_relation_new').val(temp_studinfo.guardianrelation)

                    if (all_enrollment_history.length > 0) {
                        $('#input_gradelevel_new').attr('disabled', 'disabled')
                        //$('#input_studtype_new').attr('disabled','disabled')
                    }

                    $('#create_new_student_button').attr('hidden', 'hidden')
                    $('#enrollment_form').removeAttr('hidden')
                    $('#update_student_information_button').removeAttr('hidden')

                    $('#student_info_modal').modal()

                    if (usertype_session == 16 || usertype_session == 14 || refid == 31) {
                        $('.form-control-sm-form').attr('disabled', 'disabled')
                        $('#update_student_information_button').attr('hidden', 'hidden')
                        $('#filter_studstatus').removeAttr('disabled')

                        $('.edit_ethnicgroup').remove();
                        $('.delete_ethnicgroup').remove();

                        $('.edit_religion').remove();
                        $('.delete_religion').remove();

                        $('.edit_mothertongue').remove();
                        $('.delete_mothertongue').remove();

                        $('input[type="radio"]').attr('disabled', 'disabled')
                    }


                    // $('#vacc_med_holder')[0].scrollIntoView({inline: 'center'});

                    element = document.getElementById("vacc_med_holder");
                    element.scrollIntoView();

                },
                error: function() {
                    Toast.fire({
                        type: 'error',
                        title: 'Something went wrong!'
                    })
                }
            })
        }

        //student information 
        $(document).on('click', '#view_update_student_info , .view_update_student_info_vac', function() {

            if ($(this).attr('data-id') != null) {

                selected = $(this).attr('data-id')
            }

            display_student_info()

        })


        $(document).on('click', '#update_student_information_button', function() {

            var valid_input = check_studinfo_input()

            if (!valid_input) {
                return false
            } else {

                var ismothernum = 0
                var isfathernum = 0
                var isguardiannum = 0

                if ($('#guardian').prop('checked') == true) {
                    isguardiannum = 1
                }
                if ($('#mother').prop('checked') == true) {
                    ismothernum = 1
                }
                if ($('#father').prop('checked') == true) {
                    isfathernum = 1
                }

                var oitf = null

                if ($('input[name=oitf]:checked').length > 0) {
                    oitf = $('input[name=oitf]:checked').val()
                }

                if ($('#input_oitf_new').val() != "") {
                    oitf = $('#input_oitf_new').val()
                }

                $.ajax({
                    type: 'GET',
                    url: '/student/preregistration/updatestudinfo',
                    data: {
                        userid: null,
                        userid: userid,
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_sem').val(),



                        levelid: $('#input_gradelevel_new').val(),
                        lrn: $('#input_lrn_new').val(),

                        altsid: $('#input_altsid_new').val(),

                        studtype: $('#input_studtype_new').val(),
                        studgrantee: $('#input_grantee_new').val(),
                        pantawid: $('#input_pantawid_new').val(),
                        admissiontype: $('#input_addtype_new').val(),
                        strandid: $('#input_strand_new').val(),
                        courseid: $('#input_course_new').val(),
                        curriculum: $('#input_curriculum_new').val(),
                        maidenname: $('#input_mothermaidename_new').val(),
                        firstname: $('#input_fname_new').val(),
                        lastname: $('#input_lname_new').val(),
                        middlename: $('#input_mname_new').val(),
                        suffix: $('#input_suffix_new').val(),
                        dob: $('#input_dob_new').val(),
                        semail: $('#input_semail_new').val(),
                        gender: $('#input_gender_new').val(),
                        nationality: $('#input_nationality_new').val(),
                        contactno: $('#input_scontact_new').val(),
                        ismothernum: ismothernum,
                        isfathernum: isfathernum,
                        isguardiannum: isguardiannum,

                        street: $('#input_street_new').val(),
                        barangay: $('#input_barangay_new').val(),
                        district: $('#input_district_new').val(),
                        city: $('#input_city_new').val(),
                        province: $('#input_province_new').val(),
                        region: $('#input_region_new').val(),

                        ffname: $('#input_father_fname_new').val(),
                        fmname: $('#input_father_mname_new').val(),
                        flname: $('#input_father_lname_new').val(),
                        fsuffix: $('#input_father_sname_new').val(),
                        fcontactno: $('#input_father_contact_new').val(),


                        mfname: $('#input_mother_fname_new').val(),
                        mmname: $('#input_mother_mname_new').val(),
                        mlname: $('#input_mother_lname_new').val(),
                        msuffix: $('#input_mother_sname_new').val(),
                        mcontactno: $('#input_mother_contact_new').val(),



                        gfname: $('#input_guardian_fname_new').val(),
                        gmname: $('#input_guardian_mname_new').val(),
                        glname: $('#input_guardian_lname_new').val(),
                        gsuffix: $('#input_guardian_sname_new').val(),
                        gcontactno: $('#input_guardian_contact_new').val(),





                        mtname: $('#input_mt_new option:selected').text(),
                        egname: $('#input_egroup_new option:selected').text(),
                        religionname: $('#input_religion_new option:selected').text(),

                        mtid: $('#input_mt_new').val(),
                        egid: $('#input_egroup_new').val(),
                        religionid: $('#input_religion_new').val(),

                        psschoolname: $('#psschoolname').val(),
                        pssy: $('#pssy').val(),
                        gsschoolname: $('#gsschoolname').val(),
                        gssy: $('#gssy').val(),
                        jhsschoolname: $('#jhsschoolname').val(),
                        jhssy: $('#jhssy').val(),
                        shsschoolname: $('#shsschoolname').val(),
                        shssy: $('#shssy').val(),
                        collegeschoolname: $('#collegeschoolname').val(),
                        collegesy: $('#collegesy').val(),

                        vacc: $('input[name="vacc"]:checked').val(),
                        vacc_type_1st: $('#vacc_type_1st').val(),
                        vacc_type_2nd: $('#vacc_type_2nd').val(),
                        vacc_type_booster: $('#vacc_type_booster').val(),
                        vacc_type_text_1st: $('#vacc_type_1st option:selected').text(),
                        vacc_type_text_2nd: $('#vacc_type_2nd option:selected').text(),
                        vacc_type_text_booster: $('#vacc_type_booster option:selected').text(),
                        vacc_card_id: $('#vacc_card_id').val(),
                        dose_date_1st: $('#dose_date_1st').val(),
                        dose_date_2nd: $('#dose_date_2nd').val(),
                        dose_date_booster: $('#dose_date_booster').val(),
                        philhealth: $('#philhealth').val(),
                        bloodtype: $('#bloodtype').val(),
                        allergy: $('#allergy').val(),
                        allergy_to_med: $('#allergy_to_med').val(),
                        med_his: $('#med_his').val(),
                        other_med_info: $('#other_med_info').val(),

                        bec_cell: $('#bec_cell').val(),
                        chapelzone: $('#chapelzone').val(),

                        mfi: $('#input_mfi_new').val(),
                        psschooltype: $('#psschooltype').val(),
                        gsschooltype: $('#gsschooltype').val(),
                        jhsschooltype: $('#jhsschooltype').val(),
                        shsschooltype: $('#shsschooltype').val(),
                        collegeschooltype: $('#collegeschooltype').val(),

                        lastschoolatt: $('#last_school_att').val(),

                        pob: $('#pob').val(),
                        maritalstatus: $('#input_marital_new').val(),
                        nocitf: $('#input_ncf_new').val(),
                        noce: $('#input_nce_new').val(),
                        lsah: $('#input_lsah_new').val(),
                        oitf: oitf,

                        glits: $('#last_school_lvlid').val(),
                        mol: $('#input_mol_new').val(),
                        scn: $('#last_school_no').val(),
                        cmaosla: $('#last_school_add').val(),

                        //socio economic profile start
                        fea: $('#fea').val(),
                        mea: $('#mea').val(),
                        gea: $('#gea').val(),

                        moccupation: $('#input_mother_occupation_new').val(),
                        foccupation: $('#input_father_occupation_new').val(),
                        relation: $('#input_guardian_relation_new').val(),

                        fmi: $('#fmi').val(),
                        mmi: $('#mmi').val(),
                        gmi: $('#gmi').val(),

                        fosoi: $('#fosoi').val(),
                        mosoi: $('#mosoi').val(),
                        gosoi: $('#gosoi').val(),

                        fethnicity: $('#fethnicity').val(),
                        methnicity: $('#methnicity').val(),
                        gethnicity: $('#gethnicity').val(),
                        //socio economic profile end

                        fha: $('#fha').val(),
                        mha: $('#mha').val(),
                        gha: $('#gha').val(),

                        studid: selected
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                            var temp_index = all_students.findIndex(x => x.studid == selected)

                            var temp_middle = '';
                            var temp_suffix = '';

                            if ($('#input_mname_new').val() != "") {
                                if ($('#input_mname_new').val().length > 0) {
                                    temp_middle = ' ' + $('#input_mname_new').val()[0] + '.';
                                }
                            }
                            if ($('#input_suffix_new').val() != "") {
                                temp_suffix = ' ' + $('#input_suffix_new').val();
                            }

                            var temp_studname = $('#input_lname_new').val() + ', ' + $(
                                '#input_fname_new').val() + temp_middle + temp_suffix;
                            all_students[temp_index].search = all_students[temp_index].sid + ' - ' +
                                temp_studname;
                            all_students[temp_index].text = all_students[temp_index].sid + ' - ' +
                                temp_studname;
                            all_students[temp_index].student = temp_studname
                            $('#student_name').text(all_students[temp_index].sid + ' : ' +
                                temp_studname)

                            all_students[temp_index].contactno = $('#input_scontact_new').val().replace(
                                /-/g, "")
                            all_students[temp_index].fcontactno = $('#input_father_contact_new').val()
                                .replace(/-/g, "")
                            all_students[temp_index].mcontactno = $('#input_mother_contact_new').val()
                                .replace(/-/g, "")
                            all_students[temp_index].gcontactno = $('#input_guardian_contact_new').val()
                                .replace(/-/g, "")

                            all_students[temp_index].isfathernum = null
                            all_students[temp_index].isguardannum = null
                            all_students[temp_index].ismothernum = null

                            all_students[temp_index].courseid = $('#input_course_new').val()
                            all_students[temp_index].levelid = $('#input_gradelevel_new').val()
                            $('#input_gradelevel').val($('#input_gradelevel_new').val())
                            $('#input_course').val($('#input_course_new').val())
                            $('#label_currentgradelevel').text($('#input_gradelevel option:selected')
                                .text())


                            if ($('#guardian').prop('checked') == true) {
                                all_students[temp_index].isguardannum = 1
                            }
                            if ($('#mother').prop('checked') == true) {
                                all_students[temp_index].ismothernum = 1
                            }
                            if ($('#father').prop('checked') == true) {
                                all_students[temp_index].isfathernum = 1
                            }


                            $('#enrollment_message_receiever').empty()

                            if (all_students[temp_index].ismothernum == 1 && all_students[temp_index]
                                .mcontactno != '') {
                                parent_contact = all_students[temp_index].mcontactno
                                parent_label = 'Mother'
                            } else if (all_students[temp_index].isfathernum == 1 && all_students[
                                    temp_index].fcontactno != '') {
                                parent_contact = all_students[temp_index].fcontactno
                                parent_label = 'Father'
                            } else if (all_students[temp_index].isguardannum == 1 && all_students[
                                    temp_index].gcontactno != '') {
                                parent_contact = all_students[temp_index].gcontactno
                                parent_label = 'Guardian'
                            }

                            var student_contact = all_students[temp_index].contactno == null ||
                                all_students[temp_index].contactno == '' ? 'No Contact #' :
                                all_students[temp_index].contactno;
                            var parent_contact = parent_contact == null ? 'No Contact #' :
                                parent_label + ' : ' + parent_contact

                            $('#enrollment_message_receiever').append('<tr><td>' + student_contact +
                                '</td><td>' + parent_contact + '</td></tr>')

                            $('#enrollment_message_receiever').append(
                                '<tr><td><a href="#" id="update_contact_info">Update Contact #</a></td><td class="align-middle" style="font-size:.6rem !important"><i>Update Contact # to receive News, Announcement and School Information.</i></td></tr>'
                            )

                            if (usertype_session == 8 || usertype_session == 4 || usertype_session ==
                                15 || usertype_session == 14) {
                                $('#update_contact_info').attr('hidden', 'hidden')
                            }


                            var vac_index = all_vac_list.findIndex(x => x.studid == selected)

                            if (vac_index.length != -1) {
                                if (all_vac_list[vac_index] != undefined) {
                                    all_vac_list[vac_index].vacc = $('input[name="vacc"]:checked').val()
                                    all_vac_list[vac_index].vacc_card_id = $('#vacc_card_id').val()
                                    all_vac_list[vac_index].vacc_type = $('#vacc_type_1st').val() !=
                                        '' ? $('#vacc_type_1st option:selected').text() : null
                                    all_vac_list[vac_index].vacc_type_2nd = $('#vacc_type_2nd').val() !=
                                        '' ? $('#vacc_type_2nd option:selected').text() : null
                                    all_vac_list[vac_index].vacc_type_booster = $('#vacc_type_booster')
                                        .val() != '' ? $('#vacc_type_booster option:selected').text() :
                                        null
                                    all_vac_list[vac_index].dose_date_1st = $('#dose_date_1st').val() !=
                                        '' ? $('#dose_date_1st').val() : null
                                    all_vac_list[vac_index].dose_date_2nd = $('#dose_date_2nd').val() !=
                                        '' ? $('#dose_date_2nd').val() : null
                                    all_vac_list[vac_index].dose_date_booster = $('#dose_date_booster')
                                        .val() != '' ? $('#dose_date_booster').val() : null
                                }
                            }

                            display_vaclist()
                            load_update_info_datatable(false)

                        } else if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                        } else if (data[0].status == 3) {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    },
                    error: function() {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                })
            }

        })



        function check_studinfo_input() {

            $('.has-error').removeClass('has-error')
            $('.is-invalid').removeClass('is-invalid')
            $('.is-required').each(function(a, b) {
                if ($(this).val() == "") {
                    $(this).addClass('is-invalid')
                } else {
                    $(this).removeClass('is-invalid')
                }
            })

            if ($('#input_gradelevel_new').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'Grade Level is Empty!'
                })

                $("#input_gradelevel_new").next().addClass("has-error");
                scrolltoview('input_gradelevel_new')
                return false;
            }

            var levelid = $('#input_gradelevel_new').val()

            if (levelid == 14 || levelid == 15) {
                if ($('#input_strand_new').val() == "") {
                    Toast.fire({
                        type: 'info',
                        title: 'Strand is Empty!'
                    })
                    scrolltoview('input_strand_new')
                    $("#input_strand_new").next().addClass("has-error");
                    return false;
                }
            } else if (levelid == 17 || levelid == 18 || levelid == 19 || levelid == 20) {
                if ($('#input_course_new').val() == "") {
                    Toast.fire({
                        type: 'info',
                        title: 'Course is Empty!'
                    })
                    scrolltoview('input_course_new')
                    $("#input_course_new").next().addClass("has-error");
                    return false;
                }
                if ($('#input_curriculum_new').val() == "") {
                    Toast.fire({
                        type: 'info',
                        title: 'Curriculum is Empty!'
                    })
                    scrolltoview('input_curriculum_new')
                    $("#input_curriculum_new").next().addClass("has-error");
                    return false;
                }
            } else {
                $('#input_strand_new').val("").change()
                $('#input_course_new').val("").change()
            }

            if ($('#input_fname_new').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'First Name is Empty!'
                })
                scrolltoview('input_fname_new')
                return false;
            } else if ($('#input_lname_new').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'Last Name is Empty!'
                })
                scrolltoview('input_lname_new')
                return false;
            } else if ($('#input_dob_new').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'Birth Date is Empty!'
                })
                scrolltoview('input_dob_new')
                return false;
            } else if ($('#input_gender').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'Gender is Empty!'
                })
                scrolltoview('input_gender')
                return false;
            } else if ($('#input_scontact_new').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'Student Contact # is Empty!'
                })
                scrolltoview('input_scontact_new')
                return false;
            }





            var ismothernum = 0
            var isfathernum = 0
            var isguardiannum = 0

            if ($('#guardian').prop('checked') == true) {
                isguardiannum = 1
            }
            if ($('#mother').prop('checked') == true) {
                ismothernum = 1
            }
            if ($('#father').prop('checked') == true) {
                isfathernum = 1
            }


            if ($('#input_scontact_new').val() != "" && ($('#input_scontact_new').val()).toString().replace(/-|_/g, '')
                .length != 11) {
                Toast.fire({
                    type: 'warning',
                    title: "Student contact # is invalid!"
                })
                $('#input_scontact_new').addClass('is-invalid')
                scrolltoview('input_scontact_new')
                return false
            } else if ($('#input_father_contact_new').val() != "" && ($('#input_father_contact_new').val()).toString()
                .replace(/-|_/g, '').length != 11) {
                Toast.fire({
                    type: 'warning',
                    title: "Father contact # is invalid!"
                })
                $('#input_father_contact_new').addClass('is-invalid')
                scrolltoview('input_father_contact_new')
                return false
            } else if ($('#input_mother_contact_new').val() != "" && ($('#input_mother_contact_new').val()).toString()
                .replace(/-|_/g, '').length != 11) {
                Toast.fire({
                    type: 'warning',
                    title: "Mother contact # is invalid!"
                })
                $('#input_mother_contact_new').addClass('is-invalid')
                scrolltoview('input_mother_contact_new')
                return false
            } else if ($('#input_guardian_contact_new').val() != "" && ($('#input_guardian_contact_new').val()).toString()
                .replace(/-|_/g, '').length != 11) {
                Toast.fire({
                    type: 'warning',
                    title: "Guardian contact # is invalid!"
                })
                $('#input_guardian_contact_new').addClass('is-invalid')
                scrolltoview('input_guardian_contact_new')
                return false
            } else if (isguardiannum == 0 && ismothernum == 0 && isfathernum == 0) {
                Toast.fire({
                    type: 'warning',
                    title: "Select in case of emergency!"
                })
                scrolltoview('father')
                return false
            }

            if (isfathernum == 1 && $('#input_father_contact_new').val() == "") {
                Toast.fire({
                    type: 'warning',
                    title: "Father contact # is empty!"
                })
                $('#input_father_contact_new').addClass('is-invalid')
                scrolltoview('input_father_contact_new')
                return false
            } else if (ismothernum == 1 && $('#input_mother_contact_new').val() == "") {
                Toast.fire({
                    type: 'warning',
                    title: "Mother contact # is empty!"
                })
                $('#input_mother_contact_new').addClass('is-invalid')
                scrolltoview('input_mother_contact_new')
                return false
            } else if (isguardiannum == 1 && $('#input_guardian_contact_new').val() == "") {
                Toast.fire({
                    type: 'warning',
                    title: "Guardian contact # is empty!"
                })
                $('#input_guardian_contact_new').addClass('is-invalid')
                scrolltoview('input_guardian_contact_new')
                return false
            } else if (isguardiannum == 1 && ($('#input_guardian_contact_new').val()).toString().replace(/-|_/g, '')
                .length != 11) {
                Toast.fire({
                    type: 'warning',
                    title: "Mother contact # is invalid!"
                })
                $('#input_guardian_contact_new').addClass('is-invalid')
                scrolltoview('input_guardian_contact_new')
                return false
            } else if (ismothernum == 1 && ($('#input_mother_contact_new').val()).toString().replace(/-|_/g, '').length !=
                11) {
                Toast.fire({
                    type: 'warning',
                    title: "Mother contact # is invalid!"
                })
                $('#input_mother_contact_new').addClass('is-invalid')
                scrolltoview('input_mother_contact_new')
                return false
            } else if (isfathernum == 1 && ($('#input_father_contact_new').val()).toString().replace(/-|_/g, '').length !=
                11) {
                Toast.fire({
                    type: 'warning',
                    title: "Mother contact # is invalid!"
                })
                $('#input_father_contact_new').addClass('is-invalid')
                scrolltoview('input_father_contact_new')
                return false
            }

            return true
        }
    </script>

    <script>
        //vaccine information
        var vaccine_list = []
        var selected_vaccine = null
        var selected_vaccine_2nd = null
        var selected_vaccine_booster = null
        get_vaccine()

        function get_vaccine() {
            $.ajax({
                type: 'GET',
                url: '/setup/vaccinetype/list',
                success: function(data) {
                    vaccine_list = data
                    $('#vacc_type_1st').empty()
                    $('#vacc_type_1st').append('<option value="">Vaccine (1st Dose)</option>')
                    $('#vacc_type_1st').append(
                        '<option value="create"><i class="fas fa-plus"></i>Create Vaccine Type</option>')
                    $("#vacc_type_1st").select2({
                        data: vaccine_list,
                        allowClear: true,
                        placeholder: "Vaccine (1st Dose)",
                    })

                    $('#vacc_type_2nd').empty()
                    $('#vacc_type_2nd').append('<option value="">Vaccine (2nd Dose)</option>')
                    $("#vacc_type_2nd").select2({
                        data: vaccine_list,
                        allowClear: true,
                        placeholder: "Vaccine (2nd Dose)",
                    })

                    $('#vacc_type_booster').empty()
                    $('#vacc_type_booster').append('<option value="">Vaccine (Booster Shot)</option>')
                    $("#vacc_type_booster").select2({
                        data: vaccine_list,
                        allowClear: true,
                        placeholder: "Vaccine (Booster Shot)",
                    })

                },
            })
        }

        function create_vaccine() {

            if ($('#schedclass_desc').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'Decription is empty!',
                })
                return false
            }

            $.ajax({
                type: 'GET',
                url: '/setup/vaccinetype/create',
                data: {
                    vaccine_name: $('#vaccine_desc').val()
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        vaccine_list = data[0].data

                        $('#vacc_type_1st').empty()
                        $('#vacc_type_1st').append('<option value="">Vaccine (1st Dose)</option>')
                        $('#vacc_type_1st').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Vaccine Type</option>'
                        )
                        $("#vacc_type_1st").select2({
                            data: vaccine_list,
                            allowClear: true,
                            placeholder: "Vaccine (1st Dose)",
                        })

                        $('#vacc_type_2nd').empty()
                        $('#vacc_type_2nd').append('<option value="">Vaccine (2nd Dose)</option>')
                        $("#vacc_type_2nd").select2({
                            data: vaccine_list,
                            allowClear: true,
                            placeholder: "Vaccine (2nd Dose)",
                        })

                        $('#vacc_type_booster').empty()
                        $('#vacc_type_booster').append('<option value="">Vaccine (Booster Shot)</option>')
                        $("#vacc_type_booster").select2({
                            data: vaccine_list,
                            allowClear: true,
                            placeholder: "Vaccine (Booster Shot)",
                        })
                    }

                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })

                },
            })
        }

        function update_vaccine() {
            $.ajax({
                type: 'GET',
                url: '/setup/vaccinetype/update',
                data: {
                    vaccine_name: $('#vaccine_desc').val(),
                    id: selected_vaccine
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        vaccine_list = data[0].data

                        $('#vacc_type_1st').empty()
                        $('#vacc_type_1st').append('<option value="">Vaccine (1st Dose)</option>')
                        $('#vacc_type_1st').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Vaccine Type</option>'
                        )
                        $("#vacc_type_1st").select2({
                            data: vaccine_list,
                            allowClear: true,
                            placeholder: "Vaccine (1st Dose)",
                        })

                        $('#vacc_type_2nd').empty()
                        $('#vacc_type_2nd').append('<option value="">Vaccine (2nd Dose)</option>')
                        $("#vacc_type_2nd").select2({
                            data: vaccine_list,
                            allowClear: true,
                            placeholder: "Vaccine (2nd Dose)",
                        })

                        $('#vacc_type_booster').empty()
                        $('#vacc_type_booster').append('<option value="">Vaccine (Booster Shot)</option>')
                        $("#vacc_type_booster").select2({
                            data: vaccine_list,
                            allowClear: true,
                            placeholder: "Vaccine (Booster Shot)",
                        })

                        $('#vacc_type_1st').val(selected_vaccine).change()
                        $('#vacc_type_2nd').val(selected_vaccine_2nd).change()
                        $('#vacc_type_booster').val(selected_vaccine_booster).change()
                    }

                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                },
            })
        }

        function delete_vaccine() {
            $.ajax({
                type: 'GET',
                url: '/setup/vaccinetype/delete',
                data: {
                    id: selected_vaccine
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        vaccine_list = data[0].data
                        $('#vacc_type_1st').empty()
                        $('#vacc_type_1st').append('<option value="">Vaccine (1st Dose)</option>')
                        $('#vacc_type_1st').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Vaccine Type</option>'
                        )
                        $("#vacc_type_1st").select2({
                            data: vaccine_list,
                            allowClear: true,
                            placeholder: "Vaccine (1st Dose)",
                        })

                        $('#vacc_type_2nd').empty()
                        $('#vacc_type_2nd').append('<option value="">Vaccine (2nd Dose)</option>')
                        $("#vacc_type_2nd").select2({
                            data: vaccine_list,
                            allowClear: true,
                            placeholder: "Vaccine (2nd Dose)",
                        })

                        $('#vacc_type_booster').empty()
                        $('#vacc_type_booster').append('<option value="">Vaccine (Booster Shot)</option>')
                        $("#vacc_type_booster").select2({
                            data: vaccine_list,
                            allowClear: true,
                            placeholder: "Vaccine (Booster Shot)",
                        })

                        $('#vacc_type_1st').val("").change()

                        if (selected_vaccine == selected_vaccine_2nd) {
                            $('#vacc_type_2nd').val("").change()
                        } else {
                            $('#vacc_type_2nd').val(selected_vaccine_2nd).change()
                        }

                        if (selected_vaccine == selected_vaccine_booster) {
                            $('#vacc_type_booster').val("").change()
                        } else {
                            $('#vacc_type_booster').val(selected_vaccine_booster).change()
                        }

                        $('.edit_schedclass').attr('hidden', 'hidden')
                        $('.delete_schedclass').attr('hidden', 'hidden')
                    }

                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                },
            })
        }

        $(document).on('change', '#vacc_type_2nd', function() {
            selected_vaccine_2nd = $(this).val()
        })

        $(document).on('change', '#vacc_type_booster', function() {
            selected_vaccine_booster = $(this).val()
        })
        $(document).on('change', '#vacc_type_1st', function() {
            if ($(this).val() != "" && $(this).val() != "create") {
                $('.edit_vaccine').removeAttr('hidden')
                $('.delete_vaccine').removeAttr('hidden')
            } else {
                if ($(this).val() == "create") {
                    selected_vaccine = null
                    $('#vaccine_desc').val("")
                    $('#vacc_type_1st').val("").change()
                    $('#create_vaccine_button').removeAttr('hidden')
                    $('#update_vaccine_button').attr('hidden', 'hidden')
                    $('#vaccine_form_modal').modal()
                }
                $('.edit_vaccine').attr('hidden', 'hidden')
                $('.delete_vaccine').attr('hidden', 'hidden')
            }
        })

        $(document).on('change', '#vacc_type_1st , #vacc_type_2nd , #vacc_type_booster , input[name="vacc"]', function() {
            $('input[name="vacc"][value="0"]').prop('checked', false)
            if ($('#vacc_type_1st').val() != "") {
                $('input[name="vacc"][value="1"]').prop('checked', true)
            } else if ($('#vacc_type_2nd').val() != "") {
                $('input[name="vacc"][value="1"]').prop('checked', true)
            } else if ($('#vacc_type_booster').val() != "") {
                $('input[name="vacc"][value="1"]').prop('checked', true)
            } else {
                $('input[name="vacc"][value="0"]').prop('checked', true)
            }
        })



        $(document).on('click', '#create_vaccine_button', function() {
            create_vaccine()
        })

        $(document).on('click', '#update_vaccine_button', function() {
            update_vaccine()
        })


        $(document).on('click', '.delete_vaccine', function() {
            selected_vaccine = $('#vacc_type_1st').val()

            Swal.fire({
                text: 'Are you sure you want to remove Vaccine Type?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    delete_vaccine()
                }
            })
        })

        $(document).on('click', '.edit_vaccine', function() {
            $('#create_vaccine_button').attr('hidden', 'hidden')
            $('#update_vaccine_button').removeAttr('hidden')
            $('#vaccine_desc').val($('#vacc_type_1st option:selected').text())
            selected_vaccine = $('#vacc_type_1st').val()
            $('#vaccine_form_modal').modal()
        })
    </script>


    <script>
        $(document).ready(function() {
            get_religion()

            function get_religion() {
                $.ajax({
                    type: 'GET',
                    url: '/setup/religion/list',
                    success: function(data) {
                        religion_list = data
                        $('#input_religion_new').empty()
                        $('#input_religion_new').append('<option value="">Religion</option>')
                        $('#input_religion_new').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Religion</option>'
                        )
                        $("#input_religion_new").select2({
                            data: religion_list,
                            allowClear: true,
                            placeholder: "Religion",
                        })
                    },
                })
            }
        })
        //religion
        var religion_list = []
        var selected_religion = null

        function create_religion() {

            if ($('#religion_desc').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'Decription is empty!',
                })
                return false
            }

            $.ajax({
                type: 'GET',
                url: '/setup/religion/create',
                data: {
                    religion_name: $('#religion_desc').val()
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        religion_list = data[0].data

                        $('#input_religion_new').empty()
                        $('#input_religion_new').append('<option value="">Religion</option>')
                        $('#input_religion_new').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Religion</option>')
                        $("#input_religion_new").select2({
                            data: religion_list,
                            allowClear: true,
                            placeholder: "Religion",
                        })
                    }

                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })

                },
            })
        }

        function update_religion() {
            $.ajax({
                type: 'GET',
                url: '/setup/religion/update',
                data: {
                    religion_name: $('#religion_desc').val(),
                    id: selected_religion
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        religion_list = data[0].data

                        $('#input_religion_new').empty()
                        $('#input_religion_new').append('<option value="">Religion</option>')
                        $('#input_religion_new').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Religion</option>')
                        $("#input_religion_new").select2({
                            data: religion_list,
                            allowClear: true,
                            placeholder: "Religion",
                        })

                        $('#input_religion_new').val(selected_religion).change()
                    }

                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                },
            })
        }

        function delete_religion() {
            $.ajax({
                type: 'GET',
                url: '/setup/religion/delete',
                data: {
                    id: selected_religion
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        religion_list = data[0].data

                        $('#input_religion_new').empty()
                        $('#input_religion_new').append('<option value="">Religion</option>')
                        $('#input_religion_new').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Religion</option>')
                        $("#input_religion_new").select2({
                            data: religion_list,
                            allowClear: true,
                            placeholder: "Religion",
                        })

                        $('.edit_religion').attr('hidden', 'hidden')
                        $('.delete_religion').attr('hidden', 'hidden')
                    }

                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                },
            })
        }


        $(document).on('change', '#input_religion_new', function() {
            if ($(this).val() != "" && $(this).val() != "create") {
                $('.edit_religion').removeAttr('hidden')
                $('.delete_religion').removeAttr('hidden')
            } else {
                if ($(this).val() == "create") {
                    selected_religion = null
                    $('#religion_desc').val("")
                    $('#input_religion_new').val("").change()
                    $('#edit_religion').removeAttr('hidden')
                    $('#delete_religion').attr('hidden', 'hidden')
                    $('#religion_form_modal').modal()
                }
                $('.edit_religion').attr('hidden', 'hidden')
                $('.delete_religion').attr('hidden', 'hidden')
            }
        })
        $(document).on('click', '#create_religion_button', function() {
            create_religion()
        })

        $(document).on('click', '#update_religion_button', function() {
            update_religion()
        })


        $(document).on('click', '.delete_religion', function() {
            selected_religion = $('#input_religion_new').val()

            Swal.fire({
                text: 'Are you sure you want to remove Religion?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    delete_religion()
                }
            })
        })

        $(document).on('click', '.edit_religion', function() {
            $('#create_religion_button').attr('hidden', 'hidden')
            $('#update_religion_button').removeAttr('hidden')
            $('#religion_desc').val($('#input_religion_new option:selected').text())
            selected_religion = $('#input_religion_new').val()
            $('#religion_form_modal').modal()
        })
    </script>

    <script>
        $(document).ready(function() {
            get_bloodtype()

            function get_bloodtype() {
                $.ajax({
                    type: 'GET',
                    url: '/setup/bloodtype/list',
                    success: function(data) {
                        bloodtype_list = data
                        $('#bloodtype').empty()
                        $('#bloodtype').append('<option value="">Blood Type</option>')
                        $('#bloodtype').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Blood Type</option>'
                        )
                        $("#bloodtype").select2({
                            data: bloodtype_list,
                            allowClear: true,
                            placeholder: "Blood Type",
                        })
                    },
                })
            }
        })
        //bloodtype
        var bloodtype_list = []
        var selected_bloodtype = null

        function create_bloodtype() {

            if ($('#bloodtype_desc').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'Decription is empty!',
                })
                return false
            }

            $.ajax({
                type: 'GET',
                url: '/setup/bloodtype/create',
                data: {
                    bloodtype_name: $('#bloodtype_desc').val()
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        bloodtype_list = data[0].data

                        $('#bloodtype').empty()
                        $('#bloodtype').append('<option value="">Blood Type</option>')
                        $('#bloodtype').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Blood Type</option>')
                        $("#bloodtype").select2({
                            data: bloodtype_list,
                            allowClear: true,
                            placeholder: "Blood Type",
                        })
                    }

                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })

                },
            })
        }

        function update_bloodtype() {
            $.ajax({
                type: 'GET',
                url: '/setup/bloodtype/update',
                data: {
                    bloodtype_name: $('#bloodtype_desc').val(),
                    id: selected_bloodtype
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        bloodtype_list = data[0].data

                        $('#bloodtype').empty()
                        $('#bloodtype').append('<option value="">Blood Type</option>')
                        $('#bloodtype').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Blood Type</option>')
                        $("#bloodtype").select2({
                            data: bloodtype_list,
                            allowClear: true,
                            placeholder: "Blood Type",
                        })

                        $('#bloodtype').val(selected_bloodtype).change()
                    }

                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                },
            })
        }

        function delete_bloodtype() {
            $.ajax({
                type: 'GET',
                url: '/setup/bloodtype/delete',
                data: {
                    id: selected_bloodtype
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        bloodtype_list = data[0].data

                        $('#bloodtype').empty()
                        $('#bloodtype').append('<option value="">Blood Type</option>')
                        $('#bloodtype').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Blood Type</option>')
                        $("#bloodtype").select2({
                            data: bloodtype_list,
                            allowClear: true,
                            placeholder: "Blood Type",
                        })

                        $('.edit_bloodtype').attr('hidden', 'hidden')
                        $('.delete_bloodtype').attr('hidden', 'hidden')
                    }

                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                },
            })
        }


        $(document).on('change', '#bloodtype', function() {
            if ($(this).val() != "" && $(this).val() != "create") {
                $('.edit_bloodtype').removeAttr('hidden')
                $('.delete_bloodtype').removeAttr('hidden')
            } else {
                if ($(this).val() == "create") {
                    selected_bloodtype = null
                    $('#bloodtype_desc').val("")
                    $('#bloodtype').val("").change()
                    $('#edit_bloodtype').removeAttr('hidden')
                    $('#delete_bloodtype').attr('hidden', 'hidden')
                    $('#bloodtype_form_modal').modal()
                }
                $('.edit_bloodtype').attr('hidden', 'hidden')
                $('.delete_bloodtype').attr('hidden', 'hidden')
            }
        })
        $(document).on('click', '#create_bloodtype_button', function() {
            create_bloodtype()
        })

        $(document).on('click', '#update_bloodtype_button', function() {
            update_bloodtype()
        })


        $(document).on('click', '.delete_bloodtype', function() {
            selected_bloodtype = $('#bloodtype').val()

            Swal.fire({
                text: 'Are you sure you want to remove Blood Type?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    delete_bloodtype()
                }
            })
        })

        $(document).on('click', '.edit_bloodtype', function() {
            $('#create_bloodtype_button').attr('hidden', 'hidden')
            $('#update_bloodtype_button').removeAttr('hidden')
            $('#bloodtype_desc').val($('#bloodtype option:selected').text())
            selected_bloodtype = $('#bloodtype').val()
            $('#bloodtype_form_modal').modal()
        })
    </script>

    <script>
        $(document).ready(function() {
            get_mothertongue()

            function get_mothertongue() {
                $.ajax({
                    type: 'GET',
                    url: '/setup/mothertongue/list',
                    success: function(data) {
                        mothertongue_list = data
                        $('#input_mt_new').empty()
                        $('#input_mt_new').append('<option value="">Mother Tongue</option>')
                        $('#input_mt_new').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Mother Tongue</option>'
                        )
                        $("#input_mt_new").select2({
                            data: mothertongue_list,
                            allowClear: true,
                            placeholder: "Mother Tongue",
                        })
                    },
                })
            }
        })

        //mothertongue
        var mothertongue_list = []
        var selected_mothertongue = null

        function create_mothertongue() {

            if ($('#mt_desc').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'Decription is empty!',
                })
                return false
            }

            $.ajax({
                type: 'GET',
                url: '/setup/mothertongue/create',
                data: {
                    mothertongue: $('#mt_desc').val()
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        mothertongue_list = data[0].data
                        $('#input_mt_new').empty()
                        $('#input_mt_new').append('<option value="">Mother Tongue</option>')
                        $('#input_mt_new').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Mother Tongue</option>'
                        )
                        $("#input_mt_new").select2({
                            data: mothertongue_list,
                            allowClear: true,
                            placeholder: "Mother Tongue",
                        })
                    }

                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })

                },
            })
        }

        function update_mothertongue() {
            $.ajax({
                type: 'GET',
                url: '/setup/mothertongue/update',
                data: {
                    mothertongue: $('#mt_desc').val(),
                    id: selected_mothertongue
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        mothertongue_list = data[0].data
                        $('#input_mt_new').empty()
                        $('#input_mt_new').append('<option value="">Mother Tongue</option>')
                        $('#input_mt_new').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Mother Tongue</option>'
                        )
                        $("#input_mt_new").select2({
                            data: mothertongue_list,
                            allowClear: true,
                            placeholder: "Mother Tongue",
                        })

                        $('#input_mt_new').val(selected_mothertongue).change()
                    }

                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                },
            })
        }

        function delete_mothertongue() {
            $.ajax({
                type: 'GET',
                url: '/setup/mothertongue/delete',
                data: {
                    id: selected_mothertongue
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        mothertongue_list = data[0].data
                        $('#input_mt_new').empty()
                        $('#input_mt_new').append('<option value="">Mother Tongue</option>')
                        $('#input_mt_new').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Mother Tongue</option>'
                        )
                        $("#input_mt_new").select2({
                            data: mothertongue_list,
                            allowClear: true,
                            placeholder: "Mother Tongue",
                        })
                        $('.edit_mothertongue').attr('hidden', 'hidden')
                        $('.delete_mothertongue').attr('hidden', 'hidden')
                    }

                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                },
            })
        }


        $(document).on('change', '#input_mt_new', function() {
            if ($(this).val() != "" && $(this).val() != "create") {
                $('.edit_mothertongue').removeAttr('hidden')
                $('.delete_mothertongue').removeAttr('hidden')
            } else {
                if ($(this).val() == "create") {
                    selected_mothertongue = null
                    $('#mt_desc').val("")
                    $('#input_mt_new').val("").change()
                    $('#edit_mothertongue').removeAttr('hidden')
                    $('#delete_mothertongue').attr('hidden', 'hidden')
                    $('#mt_form_modal').modal()
                }
                $('.edit_mothertongue').attr('hidden', 'hidden')
                $('.delete_mothertongue').attr('hidden', 'hidden')
            }
        })
        $(document).on('click', '#create_mothertongue_button', function() {
            create_mothertongue()
        })

        $(document).on('click', '#update_mothertongue_button', function() {
            update_mothertongue()
        })


        $(document).on('click', '.delete_mothertongue', function() {
            selected_mothertongue = $('#input_mt_new').val()

            Swal.fire({
                text: 'Are you sure you want to remove Mother Tongue?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    delete_mothertongue()
                }
            })
        })

        $(document).on('click', '.edit_mothertongue', function() {
            $('#create_mothertongue_button').attr('hidden', 'hidden')
            $('#update_mothertongue_button').removeAttr('hidden')
            $('#mt_desc').val($('#input_mt_new option:selected').text())
            selected_mothertongue = $('#input_mt_new').val()
            $('#mt_form_modal').modal()
        })
    </script>


    <script>
        $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() {
                formatCurrency($(this), "blur");
            }
        });


        function formatNumber(n) {
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }


        function formatCurrency(input, blur) {
            // appends $ to value, validates decimal side
            // and puts cursor back in right position.

            // get input value
            var input_val = input.val();

            // don't validate empty input
            if (input_val === "") {
                return;
            }

            // original length
            var original_len = input_val.length;

            // initial caret position 
            var caret_pos = input.prop("selectionStart");

            // check for decimal
            if (input_val.indexOf(".") >= 0) {

                // get position of first decimal
                // this prevents multiple decimals from
                // being entered
                var decimal_pos = input_val.indexOf(".");

                // split number by decimal point
                var left_side = input_val.substring(0, decimal_pos);
                var right_side = input_val.substring(decimal_pos);

                // add commas to left side of number
                left_side = formatNumber(left_side);

                // validate right side
                right_side = formatNumber(right_side);

                // On blur make sure 2 numbers after decimal
                if (blur === "blur") {
                    right_side += "00";
                }

                // Limit decimal to only 2 digits
                right_side = right_side.substring(0, 2);

                // join number by .
                input_val = left_side + "." + right_side;

            } else {
                // no decimal entered
                // add commas to number
                // remove all non-digits
                input_val = formatNumber(input_val);
                input_val = input_val;

                // final formatting
                if (blur === "blur") {
                    input_val += ".00";
                }
            }

            // send updated string to input
            input.val(input_val);

            // put caret back in the right position
            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }
    </script>

    <script>
        $(document).ready(function() {
            get_ethnicgroup()

            function get_ethnicgroup() {
                $.ajax({
                    type: 'GET',
                    url: '/setup/ethnicgroup/list',
                    success: function(data) {
                        ethnicgroup_list = data
                        $('#input_egroup_new').empty()
                        $('#input_egroup_new').append('<option value="">Ethnic Group</option>')
                        $('#input_egroup_new').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Ethnic Group</option>'
                        )
                        $("#input_egroup_new").select2({
                            data: ethnicgroup_list,
                            allowClear: true,
                            placeholder: "Ethnic Group",
                        })
                    },
                })
            }
        })
        //ethnicgroup
        var ethnicgroup_list = []
        var selected_ethnicgroup = null


        function create_ethnicgroup() {

            if ($('#ethnicgroup_desc').val() == "") {
                Toast.fire({
                    type: 'info',
                    title: 'Decription is empty!',
                })
                return false
            }

            $.ajax({
                type: 'GET',
                url: '/setup/ethnicgroup/create',
                data: {
                    ethnicgroup_name: $('#ethnicgroup_desc').val()
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        ethnicgroup_list = data[0].data
                        $('#input_egroup_new').empty()
                        $('#input_egroup_new').append('<option value="">Ethnic Group</option>')
                        $('#input_egroup_new').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Ethnic Group</option>'
                        )
                        $("#input_egroup_new").select2({
                            data: ethnicgroup_list,
                            allowClear: true,
                            placeholder: "Ethnic Group",
                        })
                    }

                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })

                },
            })
        }

        function update_ethnicgroup() {
            $.ajax({
                type: 'GET',
                url: '/setup/ethnicgroup/update',
                data: {
                    ethnicgroup_name: $('#ethnicgroup_desc').val(),
                    id: selected_ethnicgroup
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        ethnicgroup_list = data[0].data
                        $('#input_egroup_new').empty()
                        $('#input_egroup_new').append('<option value="">Ethnic Group</option>')
                        $('#input_egroup_new').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Ethnic Group</option>'
                        )
                        $("#input_egroup_new").select2({
                            data: ethnicgroup_list,
                            allowClear: true,
                            placeholder: "Ethnic Group",
                        })

                        $('#input_egroup_new').val(selected_ethnicgroup).change()
                    }

                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                },
            })
        }

        function delete_ethnicgroup() {
            $.ajax({
                type: 'GET',
                url: '/setup/ethnicgroup/delete',
                data: {
                    id: selected_ethnicgroup
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        ethnicgroup_list = data[0].data
                        $('#input_egroup_new').empty()
                        $('#input_egroup_new').append('<option value="">Ethnic Group</option>')
                        $('#input_egroup_new').append(
                            '<option value="create"><i class="fas fa-plus"></i>Create Ethnic Group</option>'
                        )
                        $("#input_egroup_new").select2({
                            data: ethnicgroup_list,
                            allowClear: true,
                            placeholder: "Ethnic Group",
                        })
                        $('.edit_ethnicgroup').attr('hidden', 'hidden')
                        $('.delete_ethnicgroup').attr('hidden', 'hidden')
                    }

                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message
                    })
                },
            })
        }


        $(document).on('change', '#input_egroup_new', function() {
            if ($(this).val() != "" && $(this).val() != "create") {
                $('.edit_ethnicgroup').removeAttr('hidden')
                $('.delete_ethnicgroup').removeAttr('hidden')
            } else {
                if ($(this).val() == "create") {
                    selectedethnicgroup = null
                    $('#ethnicgroup_desc').val("")
                    $('#input_egroup_new').val("").change()
                    $('#edit_ethnicgroup').removeAttr('hidden')
                    $('#delete_ethnicgroup').attr('hidden', 'hidden')
                    $('#ethnicgroup_form_modal').modal()
                }
                $('.edit_ethnicgroup').attr('hidden', 'hidden')
                $('.delete_ethnicgroup').attr('hidden', 'hidden')
            }
        })
        $(document).on('click', '#create_ethnicgroup_button', function() {
            create_ethnicgroup()
        })

        $(document).on('click', '#update_ethnicgroup_button', function() {
            update_ethnicgroup()
        })


        $(document).on('click', '.delete_ethnicgroup', function() {
            selected_ethnicgroup = $('#input_egroup_new').val()

            Swal.fire({
                text: 'Are you sure you want to remove Ethnic Group?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    delete_ethnicgroup()
                }
            })
        })

        $(document).on('click', '.edit_ethnicgroup', function() {
            $('#create_ethnicgroup_button').attr('hidden', 'hidden')
            $('#update_ethnicgroup_button').removeAttr('hidden')
            $('#mt_desc').val($('#input_egroup_new option:selected').text())
            selected_ethnicgroup = $('#input_egroup_new').val()
            $('#ethnicgroup_form_modal').modal()
        })
    </script>
    <script>
        var all_vac_list = []

        function display_vaclist() {

            var temp_data = all_vac_list
            if ($('#filter_vac_vactype').val() != '') {
                if ($('#filter_vac_vactype').val() == 0) {
                    temp_data = temp_data.filter(x => x.vacc != 1)
                } else {
                    temp_data = temp_data.filter(x => x.vacc == $('#filter_vac_vactype').val())
                }

            }


            $("#vac_list_datatable").DataTable({
                destroy: true,
                data: temp_data,
                autoWidth: false,
                stateSave: true,
                lengthChange: false,
                columns: [{
                        "data": "student"
                    },
                    {
                        "data": null
                    },
                    {
                        "data": null
                    },
                    {
                        "data": null
                    },
                    {
                        "data": null
                    },
                    {
                        "data": null
                    },
                    {
                        "data": null
                    },
                    {
                        "data": null
                    },
                    {
                        "data": null
                    },
                ],
                columnDefs: [{
                        'targets': 0,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td)[0].innerHTML =
                                '<a href="#" class="view_update_student_info_vac" data-id="' + rowData
                                .studid + '">' + rowData.student + '</a>'
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).text(rowData.vacc == 1 ? 'Vaccinated' : 'Unvaccinated')
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).text(rowData.vacc_card_id)
                            $(td).text(rowData.vacc_card_id)
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            // if(rowData.vacc_type != null){
                            //       if(rowData.vacc_type.length > 20){
                            //             $(td).text(rowData.vacc_type.substr(0, 20)+'...')
                            //       }else{
                            //             $(td).text(rowData.vacc_type)
                            //       }
                            // }else{
                            //       $(td).text(null)
                            // }
                            $(td).addClass('align-middle')
                            $(td).text(rowData.vacc_type)
                        }
                    },
                    {
                        'targets': 4,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).addClass('align-middle')
                            $(td).text(rowData.dose_date_1st != null ? moment(rowData.dose_date_1st).format(
                                'MM/DD/YYYY') : '')
                        }
                    },
                    {
                        'targets': 5,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            // if(rowData.vacc_type_2nd != null){
                            //       if(rowData.vacc_type_2nd.length > 20){
                            //             $(td).text(rowData.vacc_type_2nd.substr(0, 20)+'...')
                            //       }else{
                            //             $(td).text(rowData.vacc_type_2nd)
                            //       }
                            // }else{
                            //       $(td).text(null)
                            // }
                            $(td).addClass('align-middle')
                            $(td).text(rowData.vacc_type_2nd)
                        }
                    },
                    {
                        'targets': 6,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).addClass('align-middle')
                            $(td).text(rowData.dose_date_2nd != null ? moment(rowData.dose_date_2nd).format(
                                'MM/DD/YYYY') : '')
                        }
                    },
                    {
                        'targets': 7,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            // if(rowData.vacc_type_booster != null){
                            //       if(rowData.vacc_type_booster.length > 20){
                            //             $(td).text(rowData.vacc_type_booster.substr(0, 20)+'...')
                            //       }else{
                            //             $(td).text(rowData.vacc_type_booster)
                            //       }
                            // }else{
                            //       $(td).text(null)
                            // }
                            $(td).addClass('align-middle')
                            $(td).text(rowData.vacc_type_booster)
                        }
                    },
                    {
                        'targets': 8,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).addClass('align-middle')
                            $(td).text(rowData.dose_date_booster != null ? moment(rowData.dose_date_booster)
                                .format('MM/DD/YYYY') : '')
                        }
                    },
                ]
            });
        }


        $(document).ready(function() {

            $(document).on('click', '#vac_info', function() {
                $('#vac_list_modal').modal()
                get_vac_list()
            })

            $(document).on('click', '.badge_vacc', function() {
                $('#filter_vac_vactype').val($(this).attr('data-id')).change()
            })

            $(document).on('change', '#filter_vac_vactype', function() {
                display_vaclist()
            })

            function get_vac_list() {
                $.ajax({
                    type: 'GET',
                    url: '/student/preregistration/vacinfo',
                    data: {
                        syid: $('#filter_sy').val(),
                        semid: $('#filter_sem').val()
                    },
                    success: function(data) {
                        $('#filter_vac_vactype').select2({
                            allowClear: true,
                            placeholder: 'Vaccine Status'
                        })

                        all_vac_list = data

                        $('.badge_vacc_count[data-id="1"]').text(all_vac_list.filter(x => x.vacc == 1)
                            .length)
                        $('.badge_vacc_count[data-id="0"]').text(all_vac_list.filter(x => x.vacc != 1)
                            .length)

                        display_vaclist()
                    },
                })
            }



        })
    </script>
@endsection
