{{-- @php
    if (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (Session::get('currentPortal') == 3) {
        $extend = 'registrar.layouts.app';
    } elseif (Session::get('currentPortal') == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } elseif (auth()->user()->type == 3) {
        $extend = 'registrar.layouts.app';
    } elseif (auth()->user()->type == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (auth()->user()->type == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    }
@endphp


@extends($extend) --}}

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

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0;
        }

        input[type=search] {
            height: calc(1.7em + 2px) !important;
        }



        /* .custom-select2-dropdown {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            z-index: 1059 !important;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            } */
    </style>
@endsection


@section('content')
    @php
        $yearlevel = DB::table('gradelevel')
            ->where('acadprogid', 6)
            ->where('deleted', 0)
            ->select('id', 'levelname as text')
            ->get();

        foreach ($yearlevel as $item) {
            $item->text = str_replace(' COLLEGE', '', $item->text);
        }

        $yearlevel2 = DB::table('gradelevel')
            ->where('acadprogid', 8)
            ->where('deleted', 0)
            ->select('id', 'levelname as text')
            ->get();

        $semester = DB::table('semester')->where('deleted', 0)->select('id', 'semester as text')->get();

    @endphp


    {{-- <div class="modal fade" id="newsubject_form_modal" style="display: none;z-index: 1058;" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header mb-3 pb-2 pt-2 border-0" style="background-color: rgb(218, 215, 215);">
                    <h4 class="modal-title" style="font-size: 1rem !important; color: #333;" id="add_subject_label">Add
                        New Subject</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="background: none; border: none; color: #333;">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-12 form-group">

                            Subject Name
                            <textarea class="form-control form-control-sm mt-1" id="input_subj_desc" rows="2"
                                placeholder="Subject Description"></textarea>
                        </div>
                    </div>
                    <div class="row" style="display: flex; align-items: center; margin-bottom: 15px;">
                        <div class="col-md-6 form-group" style="flex: 1; margin-right: 15px;">
                            Subject Code
                            <input class="form-control form-control-sm mt-1" id="input_subj_code" placeholder="Subject Code"
                                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        </div>

                        <div style="display: flex; align-items: center;margin-left: 45px; ">
                            <input type="checkbox" id="input_labunit" name="input_labunit"
                                style="margin-right: 16px;cursor:pointer;">
                          
                            <p style="font-size: 14px;margin-top:15px;"> With Laboratory </p>
                            
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-12 ">
                        
                            <center><button class="btn btn-sm btn-primary " id="newsubj-f-btn"><i class="fas fa-save"></i>
                                    Save</button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="modal fade" id="newsubject_form_modal" style="display: none;z-index: 1058;" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header mb-3 pb-2 pt-2 border-0" style="background-color: rgb(218, 215, 215);">
                    <h4 class="modal-title" style="font-size: 1rem !important; color: #333;" id="add_subject_label">
                        Add New Subject
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="background: none; border: none; color: #333;">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            Subject Name
                            <textarea class="form-control form-control-sm mt-1" id="input_subj_desc" rows="2"
                                placeholder="Subject Description"></textarea>
                        </div>
                    </div>

                    <div class="row" style="display: flex; align-items: center; margin-bottom: 15px;">
                        <div class="col-md-6 form-group" style="flex: 1; margin-right: 15px;">
                            Subject Code
                            <input class="form-control form-control-sm mt-1" id="input_subj_code" placeholder="Subject Code"
                                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        </div>

                        <div style="display: flex; align-items: center;margin-left: 45px;">
                            <input type="checkbox" id="input_labunit" name="input_labunit"
                                style="margin-right: 16px;cursor:pointer;">
                            <p style="font-size: 14px;margin-top:15px;"> With Laboratory </p>
                        </div>
                    </div>

                    <!-- Note Section -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-info p-2">
                                <p style="font-size: 14px;">
                                    <strong>Note:</strong> "Please double-check your spelling before saving.<br>
                                    Once this subject has been set in the prospectus, it can no longer be edited."
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <center>
                                <button class="btn btn-sm btn-primary" id="newsubj-f-btn">
                                    <i class="fas fa-save"></i> Save
                                </button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <div class="modal fade" id="prospectus_modal" style="display: none;z-index: 1054;" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: .9rem !important">Prospectus: <span id="course_label"></span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="">Curriculum
                                {{-- <a href="javascript:void(0)" hidden class="edit_curriculum pl-2"><i
                                        class="far fa-edit"></i></a>
                                <a href="javascript:void(0)" hidden class="delete_curriculum pl-2"><i
                                        class="far fa-trash-alt text-danger"></i></a> --}}
                            </label>
                            <select name="" id="curriculum_filter" class=" form-control select2"></select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="">Year Level</label>
                            <select name="" id="year_level_filter" class=" form-control select2"></select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="">Semester</label>
                            <select name="" id="semester_filter" class=" form-control select2"></select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="">Subjects</label>
                            <select name="" id="subject_filter" class="form-control select2" disabled></select>
                        </div>
                        {{-- <div class="col-md-4">
                                    <button class="btn btn-primary btn-sm float-right" style="font-size:.7rem !important; margin-top: 31px !important;" >Add Subject</button>
                              </div> --}}
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <button class="btn btn-primary btn-sm print" data-id="prospectus_pdf" disabled
                                style="font-size:.7rem !important"><i class="fas fa-print"></i> PDF</button>
                            {{-- <button class="btn btn-primary btn-sm print" data-id="prospectus_excel" disabled><i class=" fa fa-file-excel"></i> Print Excel</button> --}}
                        </div>
                    </div>
                    <div class="row table-responsive " style="height: 450px;" id="prospectus_tables">
                        {{-- <div class="col-md-12" >
                            @foreach ($yearlevel as $key => $item)
                                @if ($key == 0)
                                    <hr class="div-holder div-{{ $item->id }} mt-0" hidden>
                                @else
                                    <hr class="div-holder div-{{ $item->id }}" hidden>
                                @endif
                                @foreach ($semester as $sem_item)
                                    <div class="row div-holder sy-{{ $item->id }} sem-{{ $sem_item->id }} div-{{ $item->id }}-{{ $sem_item->id }}"
                                        style="font-size:.8rem !important" hidden>
                                        <div class="col-md-12">
                                            <table class="table-hover table table-striped table-sm table-bordered"
                                                id="table-{{ $item->id }}-{{ $sem_item->id }}" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="5%" class="align-middle">Sort</th>
                                                        <th width="10%" class="align-middle">Code</th>
                                                        <th width="50%">Subject Description</th>
                                                        <th width="15%">Prerequisite</th>
                                                        <th width="5%" class="align-middle text-center p-0">Lect.</th>
                                                        <th width="5%" class="align-middle text-center p-0">Lab.</th>
                                                        <th width="5%" class="align-middle"></th>
                                                        <th width="5%" class="align-middle"></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div> --}}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="curriculum_modal" style="display: none; z-index: 1050;" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0 mb-3 p-3" style="background-color: rgb(238, 234, 234)">
                    <h4 class="modal-title" style="font-size: .9rem !important">
                        <h6 id="course_labels"></h6>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">

                    {{-- button add new curriculum --}}
                    <div class="row">
                        <button class="btn btn-sm btn-primary datatable_add_curriculum mb-5" data-type="list"><i
                                class="fas fa-plus"></i>
                            Add
                            New Curriculum</button>
                    </div>

                    {{-- table area --}}

                    <table id="curriculum_table" class="table table-sm table-striped" width="100%">
                        <thead>
                            <tr id="datatable_2_row">
                                <th> Curriculum Description</th>
                                <th> # of Enrolled</th>
                                <th style="text-align: center;"> Action</th>

                                {{-- <th width="10%">Email Address</th> --}}
                                {{-- <th width="auto">Address</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows will be dynamically inserted here -->
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="curriculum_form_modal" style="display: none; z-index: 1060;" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Curriculum Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Curriculum Description</label>
                            <input class="form-control form-control-sm" id="input_curriculum">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary" id="curriculum-btn">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="display_subjectsPersubjgroup_modal" style="display: none; z-index: 1060;"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: .9rem !important">
                        <h6 id="course_labels"></h6>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">

                        <div class="col-md-6 text-left">

                            {{-- <button class="btn btn-primary btn-sm mt-3" style="font-size:.7rem !important"
                                id="new_subject_btn"><i class="fas fa-print"></i> PRINT</button> --}}

                        </div>
                    </div>
                    <div class="row mt-2" style="font-size:.8rem !important">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table-hover table table-striped table-sm table-bordered"
                                    id="displaySubjperSubjgroup_datatable" style="width: 100%;">


                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addsubject_form_modal" style="display: none;" aria-hidden="true" data-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Subject List</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">

                        <div class="col-md-6 text-left">

                            <button class="btn btn-primary btn-sm mt-3" style="font-size:.7rem !important"
                                id="new_subject_btn"><i class="fas fa-plus"></i> Add New Subject</button>

                        </div>
                    </div>
                    <div class="row mt-2" style="font-size:.8rem !important">
                        <div class="col-md-12">
                            <table class="table-hover table table-striped table-sm table-bordered"
                                id="availsubj_datatable" width="100%">
                                <thead>
                                    <tr style="text-align: center;">

                                        <th width="7%" class="align-middle">Subject Code</th>
                                        <th width="20%">Subject Name</th>

                                        <th width="13%">With Laboratory</th>
                                        <th width="4%" colspan="2" class="align-middle text-center p-2">Action
                                        </th>

                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>






    <div class="modal fade" id="editsubject_form_modal" style="display: none;" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header mb-3 pb-2 pt-2 border-0" style="background-color: rgb(218, 215, 215);">
                    <h4 class="modal-title" style="font-size: 1rem !important; color: #333;" id="add_subject_label">Add
                        New Subject</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="background: none; border: none; color: #333;">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-12 form-group">

                            <input class="form-control form-control-sm mt-1" id="input_subj_id"
                                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" hidden>
                            Subject Name
                            <textarea class="form-control form-control-sm mt-1" id="edit_input_subj_desc" rows="2"
                                placeholder="Subject Description"></textarea>
                        </div>
                    </div>
                    <div class="row" style="display: flex; align-items: center; margin-bottom: 15px;">
                        <div class="col-md-6 form-group" style="flex: 1; margin-right: 15px;">
                            Subject Code
                            <input class="form-control form-control-sm mt-1" id="edit_input_subj_code"
                                placeholder="Subject Code"
                                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        </div>

                        <div style="display: flex; align-items: center;margin-left: 45px; ">
                            <input type="checkbox" id="edit_input_labunit" name="input_labunit"
                                style="margin-right: 16px;cursor:pointer;">
                            {{-- <label for="withlaboratory" style="margin: 0; color: #333; margin-left:8px;"> --}}
                            <p style="font-size: 14px;margin-top:15px;"> With Laboratory </p>
                            {{-- </label> --}}
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-12 ">
                            {{-- id="newsubj-f-btn" --}}
                            <center><button class="btn btn-sm btn-success " id="updatesubj_f_btn"><i
                                        class="fas fa-save"></i> Update</button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_prospectus_subj_modal" style="display: none;z-index: 1055 !important;"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content">
                <div class="modal-header mb-3 pb-2 pt-2 border-0" style="background-color: rgb(218, 215, 215);">
                    <div class="d-flex align-items-center">
                        <h4 class="modal-title mr-2" style="font-size: 1rem !important; color: #333;"
                            id="add_subject_labels">Add Prospectus Subject</h4>
                        <h4 class="modal-title mr-2" style="font-size: 1rem !important; color: #333;"
                            id="prospectus_subject_name" hidden>ddd</h4>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="background: none; border: none; color: #333;" id="closeModalBtn">
                        <span aria-hidden="true">×</span>
                    </button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="background: none; border: none; color: #333;" id="editcloseModalBtn" hidden>
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body pt-0">

                    <div class="row" id="subj_holder"
                        style=" border: 2px solid lightgray; padding:2%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width:97%; margin-left:1.5%;">
                        <div class="col-md-12 form-group d-flex align-items-center">
                            <label for="" style="margin-right: 2%;margin-bottom:-.5%;">Subject
                            </label>
                            <select name="" id="select_subj" class="form-control select2 form-control-sm ml-4"
                                style="width:80%;"></select>
                        </div>
                    </div>
                    {{-- <div class="row" id="subj_name_holder"
                        style=" border: 2px solid lightgray; padding:2%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width:97%; margin-left:1.5%;">
                        <div class="col-md-12 form-group d-flex align-items-center">
                            <label for="" style="margin-right: 2%;margin-bottom:-.5%;">Subject
                            </label>
                            <select name="" id="select_subj" class="form-control select2 form-control-sm ml-4"
                                style="width:80%;"></select>
                        </div>
                    </div> --}}


                    <div class="row" style="margin-left:3% ;">
                        <div class="col-md-6 form-group d-flex align-items-center mt-3" style="margin-right: 15px;">
                            <input class="form-control form-control-sm mt-1" id="input_subj_id_prospectus"
                                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" hidden>
                            <label for="input_subj_code_prospectus" class="mb-0" style="margin-right: 10px;">Subject
                                Code</label>
                            <input class="form-control form-control-sm mt-1" id="input_subj_code_prospectus"
                                placeholder="Subject Code"
                                style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px;background-color:transparent;"
                                readonly>
                        </div>
                    </div>

                    <div class="row" style="margin-left:3% ;">
                        <div class="col-md-7 form-group d-flex align-items-center">
                            <label for="input_subj_desc_prospectus" class="mb-0"
                                style="margin-right: 10px;">Subject</label>
                            <input class="form-control form-control-sm mt-1 " id="input_subj_desc_prospectus"
                                placeholder="Subject"
                                style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px;background-color:transparent;"
                                readonly>
                        </div>
                    </div>



                    <div class="row" style="margin-left:3% ;">
                        <div class="col-md-4 form-group">
                            <label for="">Lecture Units</label>
                            <input class="form-control form-control-sm" id="input_subj_lecunit" min="1"
                                oninput="this.value=this.value.replace(/[^0-9\.]/g,'');" placeholder="Lecture Units">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="">Laboratory Units</label>
                            <input class="form-control form-control-sm" id="input_subj_labunit" min="1"
                                oninput="this.value=this.value.replace(/[^0-9\.]/g,'');" placeholder="Laboratory Units">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="">Credited Units</label>
                            <input class="form-control form-control-sm" id="input_subj_credunit" min="1"
                                oninput="this.value=this.value.replace(/[^0-9\.]/g,'');" placeholder="Credited Units"
                                readonly style="background-color:transparent;">
                        </div>
                    </div>

                    <div class="row" id="subj_prereq_holder" style="margin-left:3% ;">
                        <div class="col-md-7 form-group">
                            <label for="">Prerequiste Subjects</label>
                            <select class=" form-control-sm form-control select2" multiple id="subj_prereq"></select>

                        </div>
                    </div>

                    <div class="row" id="subjgroup_holder" style="margin-left:3% ;">
                        <div class="col-md-7 form-group">
                            <label for="">Subject Group

                            </label>
                            <select name="" id="input_subj_group"
                                class=" form-control select2 form-control-sm"></select>
                        </div>
                    </div>




                    <br>

                    <div class="row">
                        <div class="col-md-12 ">
                            {{-- id="newsubj-f-btn" --}}
                            <center><button class="btn btn-sm btn-success" id="add_subject_to_prospectus"><i
                                        class="fas fa-save"></i>
                                    SAVE</button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="set_subjgroup_form_modal" style="display: none;" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Subject Group Assignment</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Subject Code</label>
                            <input class="form-control form-control-sm" id="input_subj_code_holder"
                                placeholder="Subject Code" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Subject Description</label>
                            <textarea class="form-control form-control-sm" id="input_subj_desc_holder" rows="2"
                                placeholder="Subject Description" disabled></textarea>
                        </div>
                    </div>
                    <div class="row" id="subjgroup_holder">
                        <div class="col-md-12 form-group">
                            <label for="">Subject Group </label>
                            <select name="" id="input_subj_group_assign"
                                class=" form-control select2 form-control-sm"></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-success" id="subjgroup-assign-f-btn"><i class="fa fa-save"></i>
                                Update</button>
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
                    <h1>Prospectus Setup</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Prospectus Setup</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- <section class="content pt-0">
        <div class="container-fluid"> --}}


    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12" style="font-size:.9rem">
                            {{-- <table
                                        class="table-hover table table-striped table-sm table-bordered table-head-fixed nowrap display compact"
                                        id="college_datatable" width="100%"> --}}
                            <table class="table table-sm" id="college_datatable" width="100%">
                                <thead>
                                    <tr>
                                        <th>College</th>
                                        <th>Course</th>
                                        <th>Abbreviation</th>
                                        <th>Curr. Count</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- </div>
    </section> --}}
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    {{-- <script src="{{ asset('js/setupjs/college-subjgroup.js') }}"></script> --}}


    <script>
        var acadprogid;
        $(document).ready(function() {

            $(document).on('click', '#subjgroup_to_modal', function() {
                subjgroup_datatable()
            })

            var subjgroup_selectedsubj = null

            subjgroup_select('#input_subj_group')
            subjgroup_select('#input_subj_group_assign')

            $(document).on('click', '.assign_subjgroup', function() {
                var temp_id = $(this).attr('data-id')
                subjgroup_selectedsubj = temp_id
                var temp_info = all_students.filter(x => x.id == temp_id)
                $('#input_subj_code_holder').val(temp_info[0].subjCode)
                $('#input_subj_desc_holder').val(temp_info[0].subjDesc)
                $('#input_subj_group_assign').val(temp_info[0].subjgroup).change()
                console.log(temp_info)
                $('#set_subjgroup_form_modal').modal()
            })

            $(document).on('click', '#subjgroup-assign-f-btn', function() {
                var temp_subjgroup = $('#input_subj_group_assign').val()
                get_all_subjects(subjgroup_selectedsubj, temp_subjgroup)
            })

            $(document).on('change', '#curriculum_filter', function() {
                // Check if #curriculum_filter has a selected value
                if ($(this).val()) {
                    console.log(acadprogid, 'wewewe')
                    plot_subjects(acadprogid, name)
                    // Show the .add_prospectus_subject button if there is a value
                    $(".add_prospectus_subject").removeAttr("hidden");
                    $("#subject_filter").removeAttr("disabled");
                    $(".print").removeAttr("hidden");
                } else {
                    plot_subjects()
                    // Hide the .add_prospectus_subject button if there is no value
                    $(".add_prospectus_subject").attr("hidden", true);
                    $(".print").attr("hidden", true);
                }
            });

            // var currifilter = $('#curriculum_filter').val();
            // if (!currifilter === "") {
            //     $(".add_prospectus_subject").hide();
            // } else {
            //     $(".add_prospectus_subject").show();
            // }



            function get_all_subjects(subjid, subjgroup) {

                $.ajax({
                    type: 'GET',
                    url: '/setup/prospectus/update/subjgroup',
                    data: {
                        subjid: subjid,
                        subjgroup: subjgroup,
                    },
                    success: function(data) {
                        if (data[0].status == 1) {
                            available_subject_datatable()
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
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
        var selected_course = null
        var selected_curri = null
        var yearlevel = @json($yearlevel);
        var yearlevel2 = @json($yearlevel2);
        var semester = @json($semester);
        var course_list = []
        var subject_list = []
        var all_subjects = []
        var subject_prereq = []
        var selected_pid = null



        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        })


        // function get_all_subjects(){
        //       available_subject_datatable()
        //       return false;
        //       $.ajax({
        //             type:'GET',
        //             url: '/setup/prospectus/subjets/all',
        //             data:{
        //                   courseid:selected_course,
        //                   curriculumid:$('#curriculum_filter').val(),
        //             },
        //             success:function(data) {
        //                   all_subjects = data
        //                   available_subject_datatable()
        //             }
        //       })
        // }

        function display_subjects_select(temp_subj) {
            $('#subject_filter').empty()
            $('#subject_filter').append('<option value="">All</option>')
            $("#subject_filter").select2({
                data: temp_subj,
                allowClear: true,
                placeholder: "All",
                templateResult: function(data) {
                    var $result = $("<span style='color: black;'></span>");
                    $result.text(data.text);
                    return $result;
                }
            }).on('select2:open', function() {
                // Target the dropdown container and apply inline style
                $('.select2-dropdown').attr('style', 'z-index: 1055 !important;');
            });
        }

        // function get_subjects(prompt = false) {
        //     $.ajax({
        //         type: 'GET',
        //         url: '/setup/prospectus/courses/curriculum/subjects',
        //         data: {
        //             courseid: selected_course,
        //             curriculumid: $('#curriculum_filter').val(),
        //         },
        //         success: function(data) {
        //             subject_list = data[0].subjects
        //             subject_prereq = data[0].prereq

        //             $('#subj_prereq').empty()
        //             $("#subj_prereq").select2({
        //                 data: subject_list,
        //                 theme: 'bootstrap4',
        //                 placeholder: "Select Prerequisite",
        //             })

        //             if (selected_pid != null) {
        //                 $('#subj_prereq_holder').removeAttr('hidden', 'hidden')
        //                 var check_prereq = subject_prereq.filter(x => x.subjID == selected_pid)
        //                 var prereq = []

        //                 $.each(check_prereq, function(a, b) {
        //                     prereq.push(b.prereqsubjID)
        //                 })

        //                 if (prereq.length > 0) {
        //                     $('#subj_prereq').val(prereq).change()
        //                 } else {
        //                     $("#subj_prereq").val("").change();
        //                 }

        //             }

        //             if ($('#curriculum_filter').val() == "" || $('#curriculum_filter').val() == null) {
        //                 prompt = false
        //             }

        //             if (prompt) {
        //                 Toast.fire({
        //                     type: 'info',
        //                     title: subject_list.length + ' subject(s) found'
        //                 })
        //             }


        //             display_subjects_select(subject_list)
        //             plot_subjects()
        //             available_subject_datatable()
        //         }
        //     })
        // }

        function get_subjects(prompt = false) {
            var courseid = selected_course;
            var curriculumid = $('#curriculum_filter').val();
            $.ajax({
                type: 'GET',
                url: `/setup/prospectus/courses/curriculum/subjects__/${courseid}/${curriculumid}`,

                success: function(data) {
                    subject_list = data[0].subjects
                    subject_prereq = data[0].prereq

                    $('#subj_prereq').empty()
                    $("#subj_prereq").select2({
                        data: subject_list,
                        theme: 'bootstrap4',
                        placeholder: "Select Prerequisite",
                        templateResult: function(data) {
                            var $result = $("<span style='color: black;'></span>");
                            $result.text(data.text);
                            return $result;
                        }
                    })

                    if (selected_pid != null) {
                        $('#subj_prereq_holder').removeAttr('hidden', 'hidden')
                        var check_prereq = subject_prereq.filter(x => x.subjID == selected_pid)
                        var prereq = []

                        $.each(check_prereq, function(a, b) {
                            prereq.push(b.prereqsubjID)
                        })

                        if (prereq.length > 0) {
                            $('#subj_prereq').val(prereq).change()
                        } else {
                            $("#subj_prereq").val("").change();
                        }

                    }

                    if ($('#curriculum_filter').val() == "" || $('#curriculum_filter').val() == null) {
                        prompt = false
                    }

                    if (prompt) {
                        Toast.fire({
                            type: 'info',
                            title: subject_list.length + ' subject(s) found'
                        })
                    }

                    display_subjects_select(subject_list)
                    plot_subjects(acadprogid, name)
                    available_subject_datatable()
                }
            })
        }




        $(document).on('click', '.delete_subject', function() {
            var tempsubjid = $(this).attr('data-id')

            Swal.fire({
                text: 'Are you sure you want to remove subject?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    delete_general_subject(tempsubjid)
                }
            })


        })

        function delete_general_subject(subjid) {
            $.ajax({
                type: 'GET',
                url: '/setup/prospectus/subjets/remove',
                data: {
                    subjid: subjid,
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        available_subject_datatable()
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }
            })
        }

        function available_subject_datatable() {

            // var temp_subj = all_subjects


            // $.ajax({
            //       type:'GET',
            //       url: '/setup/prospectus/subjets/all',
            //       data:{
            //             courseid:selected_course,
            //             curriculumid:$('#curriculum_filter').val(),
            //       },
            //       success:function(data) {
            //             all_subjects = data
            //             available_subject_datatable()
            //       }
            // })





            $("#availsubj_datatable").DataTable({
                destroy: true,
                // data:temp_subj,
                bInfo: true,
                autoWidth: false,
                lengthChange: true,
                stateSave: true,
                serverSide: true,
                processing: true,
                ajax: {
                    url: '/setup/prospectus/subjets/all',
                    type: 'GET',
                    data: {
                        courseid: selected_course,
                        curriculumid: $('#curriculum_filter').val(),
                    },
                    dataSrc: function(json) {
                        all_students = json.data
                        return json.data;
                    }
                },
                columns: [
                    // {
                    //     "data": null
                    // },
                    // {
                    //     "data": null
                    // },
                    {
                        "data": "subjCode"
                    },
                    {
                        "data": "subjDesc"
                    },
                    // {
                    //     "data": "description"
                    // },
                    // {
                    //     "data": "lecunits"
                    // },
                    {
                        "data": "labunits"
                    },
                    {
                        "data": null
                    },
                    {
                        "data": null
                    },
                ],
                columnDefs: [
                    // {
                    //     'targets': 0,
                    //     'orderable': false,
                    //     'createdCell': function(td, cellData, rowData, row, col) {

                    //         var temp_subjinfo = subject_list.filter(x => x.subjectID == rowData.id)
                    //         if (temp_subjinfo.length > 0) {

                    //             temp_subjinfo = temp_subjinfo[0]
                    //             var temp_yearlevel = ''

                    //             if (temp_subjinfo.yearID == 17) {
                    //                 temp_yearlevel = '1ST YEAR';
                    //             } else if (temp_subjinfo.yearID == 18) {
                    //                 temp_yearlevel = '2ND YEAR';
                    //             } else if (temp_subjinfo.yearID == 19) {
                    //                 temp_yearlevel = '3RD YEAR';
                    //             } else if (temp_subjinfo.yearID == 20) {
                    //                 temp_yearlevel = '4TH YEAR';
                    //             } else if (temp_subjinfo.yearID == 21) {
                    //                 temp_yearlevel = '5TH YEAR';
                    //             }

                    //             $(td)[0].innerHTML = temp_yearlevel
                    //         } else {
                    //             $(td)[0].innerHTML = null
                    //         }
                    //         $(td).addClass('text-center')
                    //         $(td).addClass('align-middle')
                    //     }
                    // },
                    // {
                    //     'targets': 1,
                    //     'orderable': false,
                    //     'createdCell': function(td, cellData, rowData, row, col) {
                    //         var temp_subjinfo = subject_list.filter(x => x.subjectID == rowData.id)
                    //         if (temp_subjinfo.length > 0) {

                    //             temp_subjinfo = temp_subjinfo[0]

                    //             var temp_semester = ''

                    //             if (temp_subjinfo.semesterID == 1) {
                    //                 temp_semester = '1st Sem.';
                    //             } else if (temp_subjinfo.semesterID == 2) {
                    //                 temp_semester = '2nd Sem.';
                    //             } else if (temp_subjinfo.semesterID == 3) {
                    //                 temp_semester = 'Summer';
                    //             }

                    //             $(td)[0].innerHTML = temp_semester
                    //         } else {
                    //             $(td)[0].innerHTML = null
                    //         }
                    //         $(td).addClass('text-center')
                    //         $(td).addClass('align-middle')
                    //     }
                    // },
                    // {
                    //       'targets': 2,
                    //       'orderable': true, 
                    //       'createdCell':  function (td, cellData, rowData, row, col) {

                    //             // var temp_subjinfo = subject_list.filter(x=>x.subjectID == rowData.id)
                    //             // if(temp_subjinfo.length > 0){

                    //             //       var text = rowData.subjDesc';
                    //             //       $(td)[0].innerHTML = text
                    //             // }else{
                    //             //       $(td)[0].innerHTML = '<span class="all_subj_info" id="'+rowData.id+'">'+rowData.subjDesc+'</span>'
                    //             // }

                    //             var text = rowData.subjDesc;
                    //             $(td)[0].innerHTML = text
                    //             $(td).addClass('align-middle')
                    //       }
                    // },
                    {
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
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            // Define the icon
                            var checkIcon =
                                '<i class="fa fa-check checked-lab" data-toggle="tooltip" title="laboratory is available"></i>';

                            // Determine whether to display the icon
                            var iconHtml = rowData.labunits == 1 ? checkIcon : '';

                            // Construct the HTML with the conditional icon only
                            var text = '<div style="text-align:center;">' + iconHtml + '</div>';

                            // Apply the constructed HTML and initialize tooltips
                            $(td).html(text).addClass('align-middle');
                            $(td).find('[data-toggle="tooltip"]').tooltip();
                        }
                    },


                    // {
                    //     'targets': 2,
                    //     'orderable': false,
                    //     'createdCell': function(td, cellData, rowData, row, col) {
                    //         if (rowData.description == null) {
                    //             $(td)[0].innerHTML =
                    //                 '<a href="javascript:void(0)" class="assign_subjgroup text-danger" data-id="' +
                    //                 rowData.id + '">Not Assigned</a>'
                    //         } else {
                    //             $(td)[0].innerHTML =
                    //                 '<a href="javascript:void(0)" class="assign_subjgroup" data-id="' +
                    //                 rowData.id + '">' + rowData.description + '</a>'
                    //         }
                    //         $(td).addClass('align-middle')
                    //     }
                    // },
                    // {
                    //     'targets': 3,
                    //     'orderable': false,
                    //     'createdCell': function(td, cellData, rowData, row, col) {
                    //         $(td).addClass('text-center')
                    //         $(td).addClass('align-middle')
                    //     }
                    // },
                    // {
                    //     'targets': 2,
                    //     'orderable': false,
                    //     'createdCell': function(td, cellData, rowData, row, col) {
                    //         $(td).addClass('text-center')
                    //         $(td).addClass('align-middle')
                    //     }
                    // },

                    {
                        'targets': 3,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {

                            var buttons =
                                '<a href="javascript:void(0)" class="edit_subject_list" data-id="' +
                                rowData.id + '"><i class="fas fa-edit text-primary"></i></a>';
                            $(td)[0].innerHTML = buttons

                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 4,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var buttons =
                                '<a href="javascript:void(0)" class="delete_subject" data-id="' +
                                rowData.id + '"><i class="far fa-trash-alt text-danger"></i></a>';
                            $(td)[0].innerHTML = buttons
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    },

                ],
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                pageLength: 10,
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

            });

            // var label_text = $($("#availsubj_datatable_wrapper")[0].children[0])[0].children[0]
            // $(label_text)[0].innerHTML = '<label for="" class="mb-0 pt-2">Available Subjects</label>'

        }

        $('#curriculum_filter').empty()
        $('#curriculum_filter').append('<option value="">Select curriculum</option>')
        $('#curriculum_filter').append('<option value="add">Add curriculum</option>')
        $("#curriculum_filter").select2({
            data: [],
            allowClear: true,
            placeholder: "Select curriculum",

        })


        function get_curriculum(selected_course) {
            $.ajax({
                type: 'GET',
                url: '/setup/prospectus/courses/curriculum',
                data: {
                    courseid: selected_course
                },
                success: function(data) {
                    $('#curriculum_filter').empty();
                    $('#curriculum_filter').append(
                        '<option value="" style="color: black;">Select curriculum</option>');
                    $('#curriculum_filter').append(
                        '<option value="add" style="color: blue;"><b>+</b> Add Curriculum</option>'
                    );

                    $("#curriculum_filter").select2({
                        data: data,
                        // allowClear: true,
                        placeholder: "Select curriculum",
                        templateResult: function(data) {
                            if (data.id === 'add') {
                                return $(
                                    '<span style="  color:rgb(12, 113, 186);"> <b>+</b> Add Curriculum</span>'
                                );
                            }
                            return $("<span style='color: black;'>" + data.text + "</span>");
                        }
                    }).on('select2:open', function() {
                        $('.select2-dropdown').attr('style', 'z-index: 1055 !important;');
                    });

                    if (selected_curri != null) {
                        $('#curriculum_filter').val(selected_curri).trigger('change');
                    }

                    if (prompt) {
                        Toast.fire({
                            type: 'info',
                            title: data.length + ' curriculum(s) found.'
                        });
                    }

                }
            });
        }

        function plot_subjects(acadprogid, name) {
            var yearlvl;
            var temp_subjid = $('#subject_filter').val()
            if (acadprogid == 8) {
                yearlvl = yearlevel2
            } else {
                yearlvl = yearlevel
            }

            $.each(yearlvl, function(a, b) {

                $.each(semester, function(c, d) {

                    if (temp_subjid != null && temp_subjid != "") {
                        var temp_subjects = subject_list.filter(x => x.yearID == b.id && x.semesterID == d
                            .id && x.id == temp_subjid)
                    } else {
                        var temp_subjects = subject_list.filter(x => x.yearID == b.id && x.semesterID == d
                            .id)
                    }



                    var lecunits = 0
                    var labunits = 0

                    $.each(temp_subjects, function(e, f) {
                        lecunits += parseFloat(f.lecunits)
                        labunits += parseFloat(f.labunits)
                    })

                    temp_subjects.push({
                        'psubjsort': 'Z999',
                        'subjDesc': '',
                        'subjCode': 'Z999',
                        'lecunits': lecunits,
                        'labunits': labunits,
                        'id': 'total'
                    })

                    if ($('#curriculum_filter').val() == null || $('#curriculum_filter').val() == "add" ||
                        $('#curriculum_filter').val() == "") {} else {
                        temp_subjects.push({
                            'psubjsort': 'Z998',
                            'subjDesc': '',
                            'subjCode': 'Z998',
                            'lecunits': '',
                            'labunits': '',
                            'yearID': b.id,
                            'semesterID': d.id,
                            'id': 'addnew'
                        })
                    }

                    if ($('#year_level_filter').val() == "" && $('#semester_filter').val() == "") {
                        $(".div-" + b.id + '-' + d.id).removeAttr('hidden')
                        $(".div-" + b.id).removeAttr('hidden')
                    } else if ($('#year_level_filter').val() != "" && $('#semester_filter').val() != "") {
                        $(".div-" + $('#year_level_filter').val() + '-' + $('#semester_filter').val())
                            .removeAttr('hidden')
                    } else if ($('#year_level_filter').val() != "") {
                        $(".sy-" + $('#year_level_filter').val()).removeAttr('hidden')
                    } else if ($('#semester_filter').val() != "") {
                        $(".div-" + b.id).removeAttr('hidden')
                        $(".sem-" + $('#semester_filter').val()).removeAttr('hidden')
                    }

                    if (temp_subjid != null && temp_subjid != "") {
                        if (temp_subjects.length - 2 > 0) {
                            $(".div-" + b.id + '-' + d.id).removeAttr('hidden')
                        } else {
                            if (d.id == 3) {
                                $(".div-" + b.id).attr('hidden', 'hidden')
                            }
                            $(".div-" + b.id + '-' + d.id).attr('hidden', 'hidden')
                        }
                    }

                    $("#table-" + b.id + '-' + d.id).DataTable({
                        destroy: true,
                        data: temp_subjects,
                        paging: false,
                        bInfo: false,
                        lengthChange: false,
                        columns: [{
                                "data": "psubjsort"
                            },
                            {
                                "data": "subjCode"
                            },
                            {
                                "data": "subjDesc"
                            },
                            {
                                "data": null
                            },
                            {
                                "data": "lecunits"
                            },
                            {
                                "data": "labunits"
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
                                'createdCell': function(td, cellData, rowData, row, col) {
                                    if (rowData.id == 'total') {
                                        $(td).text(null)
                                    } else if (rowData.id == 'addnew') {
                                        $(td).text(null)
                                    }
                                    $(td).addClass('text-center')
                                    $(td).addClass('align-middle')
                                }
                            },
                            {
                                'targets': 1,
                                'orderable': false,
                                'createdCell': function(td, cellData, rowData, row, col) {
                                    if (rowData.id == 'total') {
                                        $(td).text(null)
                                    } else if (rowData.id == 'addnew') {
                                        $(td).text("");
                                        // var buttons =
                                        //     '<a href="javascript:void(0)" class="add_subject" data-yearlevel="' +
                                        //     rowData.yearID + '" data-sem="' + rowData
                                        //     .semesterID +
                                        //     '"><i class="fas fa-plus"></i> Add Subject</a>';
                                        // $(td)[0].innerHTML = buttons
                                    }

                                    $(td).addClass('align-middle')
                                }
                            },
                            {
                                'targets': 2,
                                'orderable': false,
                                'createdCell': function(td, cellData, rowData, row, col) {
                                    if (rowData.id == 'total') {
                                        $(td)[0].innerHTML = '<b>Total</b>'
                                        $(td).addClass('text-right')
                                        $(td).addClass('pr-3')
                                    }

                                    $(td).addClass('align-middle')
                                }
                            },
                            {
                                'targets': 3,
                                'orderable': false,
                                'createdCell': function(td, cellData, rowData, row, col) {
                                    var check_prereq = subject_prereq.filter(x => x
                                        .subjID == rowData.id)

                                    if (check_prereq.length > 0) {
                                        var text = ''
                                        $.each(check_prereq, function(a, b) {
                                            var temp_subj_info = subject_list
                                                .filter(x => x.id == b.prereqsubjID)
                                            if (temp_subj_info.length > 0) {
                                                text += temp_subj_info[0].subjCode
                                                if (check_prereq.length - 1 != a) {
                                                    text += ', '
                                                }
                                            }

                                        })
                                        $(td).text(text)
                                    } else {
                                        $(td).text(null)
                                    }

                                    $(td).addClass('align-middle')
                                }
                            },
                            {
                                'targets': 4,
                                'orderable': false,
                                'createdCell': function(td, cellData, rowData, row, col) {
                                    $(td).addClass('text-center')
                                    $(td).addClass('align-middle')
                                }
                            },
                            {
                                'targets': 5,
                                'orderable': false,
                                'createdCell': function(td, cellData, rowData, row, col) {
                                    $(td).addClass('text-center')
                                    $(td).addClass('align-middle')
                                }
                            },
                            {
                                'targets': 6,
                                'orderable': false,
                                'createdCell': function(td, cellData, rowData, row, col) {
                                    if (rowData.id != 'total' && rowData.id != 'addnew') {
                                        var buttons =
                                            '<a href="javascript:void(0)" class="edit_prospectus" data-id="' +
                                            rowData.id +
                                            '"><i class="far fa-edit"></i></a>';
                                        $(td)[0].innerHTML = buttons
                                        $(td).addClass('text-center')
                                        $(td).addClass('align-middle')
                                    } else {
                                        $(td)[0].innerHTML = null
                                    }
                                }
                            },
                            {
                                'targets': 7,
                                'orderable': false,
                                'createdCell': function(td, cellData, rowData, row, col) {
                                    if (rowData.id != 'total' && rowData.id != 'addnew') {
                                        var buttons =
                                            '<a href="javascript:void(0)" class="delete_prospectus" data-id="' +
                                            rowData.id +
                                            '"><i class="far fa-trash-alt text-danger"></i></a>';
                                        $(td)[0].innerHTML = buttons
                                        $(td).addClass('text-center')
                                        $(td).addClass('align-middle')
                                    } else {
                                        $(td)[0].innerHTML = null
                                    }
                                }
                            },
                        ]

                    });
                    var label_text = $($("#table-" + b.id + '-' + d.id + '_wrapper')[0].children[0])[0]
                        .children[0];
                    if (acadprogid == 8) {
                        newyear = b.text.replace('HE', '').trim();
                        $(label_text).html(
                            '<div class="d-flex align-items-center col-md-7">' +
                            '<label class="mb-0">' + name + ' ' + newyear + ' - ' + d.text +
                            '</label>' +
                            '<a href="javascript:void(0)" ' +
                            '   class="add_prospectus_subject btn btn-sm btn-success ml-auto" ' +
                            '   data-yearlevel="' + b.id + '" ' +
                            '   data-sem="' + d.id + '" ' +
                            '   style="font-size: 12px; padding: 0.25rem 0.5rem; white-space: nowrap;"' +
                            '   >' +
                            '   <i class="fas fa-plus mr-1" style="color:white;"></i>' +
                            '   <span style="color:white;">Add Subject</span>' +
                            '</a>' +
                            '</div>'
                        );
                    } else {
                        $(label_text).html(
                            '<div class="d-flex align-items-center col-md-7">' +
                            '<label class="mb-0">' + b.text + ' - ' + d.text + '</label>' +
                            '<a href="javascript:void(0)" ' +
                            '   class="add_prospectus_subject btn btn-sm btn-success ml-auto" ' +
                            '   data-yearlevel="' + b.id + '" ' +
                            '   data-sem="' + d.id + '" ' +
                            '   style="font-size: 12px; padding: 0.25rem 0.5rem; white-space: nowrap;"' +
                            '   >' +
                            '   <i class="fas fa-plus mr-1" style="color:white;"></i>' +
                            '   <span style="color:white;">Add Subject</span>' +
                            '</a>' +
                            '</div>'
                        );
                    }

                })
            })


        }

        //curriculum
        $(document).ready(function() {

            $(document).on('click', '.print', function() {
                console.log($('#input_curriculum').val())
                window.open('/setup/prospectus/courses/print?filetype=' + $(this).attr('data-id') +
                    '&curriculumid=' + $('#curriculum_filter').val() + '&courseid=' + selected_course,
                    '_blank');
            })

            display_subjects_select([])

            $(document).on('change', '#curriculum_filter', function() {
                $('.edit_curriculum').attr('hidden', 'hidden')
                $('.delete_curriculum').attr('hidden', 'hidden')

                if ($(this).val() == "add") {
                    $('#curriculum-f-btn').text('Create')
                    $('#curriculum-f-btn').removeClass('btn-success')
                    $('#curriculum-f-btn').addClass('btn-primary')
                    $('#input_curriculum').val("").change()
                    $('#curriculum_form_modal').modal()
                    $('#curriculum_filter').val("").change()

                    $('.print').attr('disabled', 'disabled')


                    selected_curri = null
                    subject_list = []
                    plot_subjects(acadprogid, name)
                } else if ($(this).val() != "") {
                    selected_curri = $(this).val()
                    $('.edit_curriculum').removeAttr('hidden')
                    $('.delete_curriculum').removeAttr('hidden')
                    $('.print').removeAttr('disabled')
                    get_subjects(true)
                    // $(".add_prospectus_subject").removeAttr("hidden");
                }
            })


            // $(document).on('click', '#curriculum-f-btn', function() {
            //     if ($('#input_curriculum').val() == "") {
            //         Toast.fire({
            //             type: 'warning',
            //             title: 'Curriculum Name is empty'
            //         })
            //         return false;
            //     }
            //     if (selected_curri == null) {
            //         create_curriculum()
            //     } else {
            //         update_curriculum()
            //     }

            // })

            $(document).on('click', '.delete_curriculum', function() {
                Swal.fire({
                    text: 'Are you sure you want to remove curriculum?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
                }).then((result) => {
                    if (result.value) {
                        delete_curriculum()
                    }
                })
            })

            // $(document).on('click', '.edit_curriculum', function() {
            //     $('#input_curriculum').val($("#curriculum_filter option:selected").text())
            //     $('#curriculum-f-btn').text('Update')
            //     $('#curriculum-f-btn').addClass('btn-success')
            //     $('#curriculum-f-btn').removeClass('btn-primary')
            //     $('#curriculum_form_modal').modal()
            // })








        })
        //curriculum

        //prospectus

        $(document).ready(function() {

            var available_subj = []
            var temp_yearid = null
            var temp_semid = null
            var temp_subjid = null

            $(document).on('click', '#reload_list', function() {
                available_subject_datatable()
            })

            $(document).on('click', '.add_subject', function() {

                var yearid = $(this).attr('data-yearlevel')
                var semid = $(this).attr('data-sem')

                $(this).attr('data-type')

                if ($(this).attr('data-type') == 'list') {
                    sydesc = ""
                    temp_yearid = null
                    semdesc = ""
                    temp_semid = null
                    $('.add_subject_to_prospectus').attr('hidden', 'hidden')
                } else {
                    if (acadprogid == 8) {
                        var sydesc = yearlevel2.filter(x => x.id == yearid)[0].text

                    } else {
                        var sydesc = yearlevel.filter(x => x.id == yearid)[0].text
                    }
                    temp_yearid = yearid
                    var semdesc = semester.filter(x => x.id == semid)[0].text
                    temp_semid = semid
                    $('.add_subject_to_prospectus').removeAttr('hidden')
                }

                $('#sy_label').text(sydesc)
                $('#sem_label').text(semdesc)
                $('#subj_prereq_holder').attr('hidden', 'hidden')

                available_subject_datatable()
                $('#addsubject_form_modal').modal()
            })

            // $(document).on('click', '#new_subject_btn', function() {
            //     selected_pid = null
            //     $('#sort_holder').attr('hidden', 'hidden')
            //     $('#subjgroup_holder').removeAttr('hidden')
            //     $('#input_subj_desc').val("")
            //     $('#input_subj_code').val("")
            //     $('#input_subj_labunit').val("")
            //     $('#input_subj_lecunit').val("")
            //     $('#newsubj-f-btn').text('Create')
            //     $('#newsubj-f-btn').addClass('btn-primary')
            //     $('#newsubj-f-btn').removeClass('btn-success')
            //     $('#newsubject_form_modal').modal()
            // })

            $(document).on('click', '.delete_prospectus', function() {
                selected_pid = $(this).attr('data-id')
                Swal.fire({
                    text: 'Are you sure you want to remove subject?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
                }).then((result) => {
                    if (result.value) {
                        delete_prospectus()
                    }
                })

            })

            $(document).on('click', '#add_subject_to_prospectus', function() {



                if ($('#curriculum_filter').val() == "") {

                    Toast.fire({
                        type: 'warning',
                        title: "No Curriculum Selected"
                    })

                    return false
                }

                // temp_subjid = $(this).attr('data-id')
                // $(this).attr('hidden', 'hidden')



                temp_subjid = $('#input_subj_id_prospectus').val();
                SubjDesc = $('#input_subj_desc_prospectus').val();
                SubjCode = $('#input_subj_code_prospectus').val();
                LecUnits = $('#input_subj_lecunit').val();
                LabUnits = $('#input_subj_labunit').val();
                CredUnits = $('#input_subj_credunit').val();
                preRequisite = $('#subj_prereq').val();
                SubjGroup = $('#input_subj_group').val();

                if (!SubjDesc) {
                    Toast.fire({
                        type: 'warning',
                        title: 'Subject Desctiption cannot be empty.'
                    });
                    return; // Exit the function if validation fails
                }
                if (!SubjCode) {
                    Toast.fire({
                        type: 'warning',
                        title: 'Subject Code cannot be empty.'
                    });
                    return; // Exit the function if validation fails
                }
                if (!LecUnits) {
                    Toast.fire({
                        type: 'warning',
                        title: 'Lecture Units cannot be empty.'
                    });
                    return; // Exit the function if validation fails
                }
                if (!LabUnits) {
                    Toast.fire({
                        type: 'warning',
                        title: 'Lab Units cannot be empty.'
                    });
                    return; // Exit the function if validation fails
                }

                if (!SubjGroup) {
                    Toast.fire({
                        type: 'warning',
                        title: 'Subject Group cannot be empty.'
                    });
                    return; // Exit the function if validation fails
                }
                // var hasData = (LabUnits === 0 || LecUnits === 0);
                var hasData = (LabUnits == 0 || LecUnits == 0);

                if (hasData) {
                    // Confirm with the user before proceeding
                    Swal.fire({
                        text: 'You have set the lecture and laboratory units to 0. Are you sure you want to proceed?',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Proceed',
                        // reverseButtons: true
                    }).then((result) => {
                        if (result.value) { // Corrected here
                            add_subject_to_prospectus();
                            // $('#add_prospectus_subj_modal').modal('hide');
                        } else {
                            $('#add_prospectus_subj_modal').modal('hide');
                            // add_subject_to_prospectus();
                        }
                    });
                } else {
                    // Hide modal
                    add_subject_to_prospectus();
                }

                //  else if (!LecUnits || !LabUnits) {
                //     Toast.fire({
                //         type: 'warning',
                //         title: 'Lecture Units and Lab Units cannot be empty.'
                //     });
                //     return; // Exit the function if validation fails
                // }



                // add_subject_to_prospectus()
            })

            $(document).on('click', '#update_subject_to_prospectus', function() {
                var yearid = $(this).attr('data-yearlevel')
                var semid = $(this).attr('data-sem')

                update_subject()
            });



            $(document).on('click', '.edit_prospectus', function() {
                selected_pid = $(this).attr('data-id')
                var temp_pid = $(this).attr('data-id')
                console.log(temp_pid);
                console.log(selected_pid);
                var temp_subject = subject_list.filter(x => x.id == temp_pid)
                $('#input_subj_desc_prospectus').val(temp_subject[0].subjDesc)
                $('#input_subj_code_prospectus').val(temp_subject[0].subjCode)
                $('#input_subj_labunit').val(temp_subject[0].labunits)
                $('#input_subj_lecunit').val(temp_subject[0].lecunits)
                $('#input_subj_credunit').val(temp_subject[0].credunits)
                $('#input_subj_sort').val(temp_subject[0].psubjsort)
                $('#subj_holder').attr('hidden', 'hidden')

                $('#add_subject_to_prospectus').attr('id', 'update_subject_to_prospectus');

                $('#closeModalBtn').attr('hidden', 'hidden');
                $('#editcloseModalBtn').removeAttr('hidden');

                $('#add_subject_labels').attr('id', 'edit_subject_label');

                $('#edit_subject_label').text('Update: ');
                $('#update_subject_to_prospectus').text('Update')
                $('#prospectus_subject_name').removeAttr('hidden')
                $('#prospectus_subject_name').text(temp_subject[0].subjDesc)

                $('#select_subj').attr('disabled', 'true')

                $('#input_subj_desc_prospectus').attr('readonly', true);
                $('#input_subj_code_prospectus').attr('readonly', true);

                // Populate and select the correct subject group
                $('#input_subj_group').empty(); // Clear existing options
                $.each(temp_subject, function(index, group) {
                    $('#input_subj_group').append($('<option>', {
                        value: group.subjgroup,
                        text: group.subject_group_name
                    }));
                });
                // Append all subject groups
                $.ajax({
                    url: '/setup/prospectus/subjgroup',
                    method: 'GET',
                    success: function(response) {
                        $.each(response, function(index, group) {
                            if (!$('#input_subj_group option[value="' + group.id + '"]')
                                .length) {
                                $('#input_subj_group').append($('<option>', {
                                    value: group.id,
                                    text: group.description,
                                    'data-sort': group.sort,
                                    'data-sortnum': group.sortnum
                                }));
                            }
                        });
                        // $('#input_subj_group').val(temp_subject[0].subjgroup).change();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching subject groups:", error);
                    }
                });

                $('#subj_prereq_holder').removeAttr('hidden', 'hidden')
                // var check_prereq = subject_prereq.filter(x => x.subjID == selected_pid)
                // var prereq = []
                // $.each(check_prereq, function(a, b) {
                //     prereq.push(b.prereqsubjID)
                // })

                // if (prereq.length > 0) {
                //     $('#subj_prereq').val(prereq).change()
                // } else {
                //     $("#subj_prereq").val("").change();
                var check_prereq = subject_prereq.filter(x => x.subjID == selected_pid)
                var prereq = []
                $.each(check_prereq, function(a, b) {
                    prereq.push(b.prereqsubjID)
                })

                // Filter out the selected_pid from the subj_prereq dropdown options
                var filteredSubjects = subject_list.filter(x => x.id != selected_pid);
                $('#subj_prereq').empty();
                $("#subj_prereq").select2({
                    data: filteredSubjects,
                    theme: 'bootstrap4',
                    placeholder: "Select Prerequisite",
                    templateResult: function(data) {
                        var $result = $("<span style='color: black;'></span>");
                        $result.text(data.text);
                        return $result;
                    }
                });

                if (prereq.length > 0) {
                    $('#subj_prereq').val(prereq).change()
                } else {
                    $("#subj_prereq").val("").change();
                }

                $('#add_prospectus_subj_modal').modal()
            })

            // $(document).on('click', '#newsubj-f-btn', function() {
            //     $('#edit_subject_label').attr('id', 'add_subject_label');
            //     $('#add_subject_label').text('Add New Subject');
            //     $('#updatesubj_f_btn').attr('id', 'newsubj-f-btn');
            //     $('#newsubj-f-btn').text('Create');
            //     $('#newsubj-f-btn').removeClass('btn-success').addClass('btn-primary');
            //     $('#newsubject_form_modal').modal()



            // })

            $(document).on('click', '#newsubj-f-btn', function() {
                if ($('#input_subj_desc').val() == "") {
                    Toast.fire({
                        type: 'warning',
                        title: "Subject Description is empty"
                    })
                    return false
                }
                if ($('#input_subj_code').val() == "") {
                    Toast.fire({
                        type: 'warning',
                        title: "Subject Code is empty"
                    })
                    return false
                }

                add_new_subject()

            })



            function add_new_subject() {

                // Check if the checkbox is checked
                var labunitValue = $('#input_labunit').is(':checked') ? 1 : 0;

                $.ajax({
                    type: 'GET',
                    url: '/setup/prospectus/subjets/new',
                    data: {
                        subjgroup: $('#input_subj_group').val(),
                        subjdesc: $('#input_subj_desc').val(),
                        subjcode: $('#input_subj_code').val(),
                        labunit: labunitValue, //$('#input_subj_labunit').val(),
                        lecunit: $('#input_subj_lecunit').val(),
                        curriculumid: $('#curriculum_filter').val(),
                        semid: temp_semid,
                        levelid: temp_yearid,
                        courseid: selected_course,
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
                                title: data[0].message
                            })
                            subject_list = subject_list.filter(x => x.id != selected_pid)
                            available_subject_datatable()
                            get_subjects()
                            subj_select('#select_subj')
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                })
            }


            function update_subject() {

                $.ajax({
                    type: 'GET',
                    url: '/setup/prospectus/subjets/update',
                    data: {
                        subjgroup: $('#input_subj_group').val(),
                        pid: selected_pid,
                        // subjdesc:$('#input_subj_desc').val(),
                        // subjcode:$('#input_subj_code').val(),
                        labunit: $('#input_subj_labunit').val(),
                        lecunit: $('#input_subj_lecunit').val(),
                        credunit: $('#input_subj_credunit').val(),
                        curriculumid: $('#curriculum_filter').val(),
                        sort: $('#input_subj_sort').val(),
                        semid: temp_semid,
                        levelid: temp_yearid,
                        courseid: selected_course,
                        prereq: $('#subj_prereq').val(),
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
                                title: data[0].message
                            })
                            available_subject_datatable()
                            get_subjects()
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                })
            }



            function add_subject_to_prospectus() {


                $.ajax({
                    type: 'GET',
                    url: '/setup/prospectus/add',
                    data: {
                        curriculumid: $('#curriculum_filter').val(),
                        semid: temp_semid,
                        levelid: temp_yearid,
                        courseid: selected_course,
                        subjid: temp_subjid,
                        subjDesc: SubjDesc,
                        subjCode: SubjCode,
                        lecUnits: LecUnits,
                        labUnits: LabUnits,
                        credUnits: CredUnits,
                        subjGroup: SubjGroup,
                        prereq: preRequisite
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
                                title: data[0].message
                            })
                            // subject_list = subject_list.filter(x=>x.id != selected_pid)
                            // $('.all_subj_info[id="'+temp_subjid+'"]')[0].innerHTML = $('.all_subj_info[id="'+temp_subjid+'"]').text() + '<span class="text-primary"> ( '+$('#sy_label').text() + ' : '+ $('#sem_label').text() +' )</span>'

                            // $('.add_subject_to_prospectus[data-id="' + temp_subjid + '"]').remove()
                            subject_list.push(data[0].data)
                            $('#select_subj').val("").trigger('change');
                            $('#input_subj_group').val("").trigger('change');
                            $('#input_subj_code_prospectus').val("")
                            $('#input_subj_desc_prospectus').val("")
                            $('#input_subj_labunit').val("0")
                            $('#input_subj_lecunit').val("0")
                            $('#input_subj_credunit').val("0")
                            // plot_subjects()
                            available_subject_datatable()
                            // display_subjects_select(subject_list)
                            // $('#add_prospectus_subj_modal').find('input').val('');

                            get_subjects()
                            // get_subjects()
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                })

            }

            $(document).on('click', '.add_prospectus_subject', function() {
                $('#update_subject_to_prospectus').attr('id', 'add_subject_to_prospectus');
                $('#edit_subject_label').attr('id', 'add_subject_labels');
                $('#add_subject_labels').text('Add Prospectus Subject');
                $('#add_subject_to_prospectus').text('Save')
                $('#subj_holder').removeAttr('hidden')
                $('#prospectus_subject_name').attr('hidden', 'hidden')

                $('#editcloseModalBtn').attr('hidden', 'hidden');
                $('#closeModalBtn').removeAttr('hidden');

                // $('#select_subj').attr('hidden', false);
                var yearid = $(this).attr('data-yearlevel')
                var semid = $(this).attr('data-sem')

                $(this).attr('data-type')

                if ($(this).attr('data-type') == 'list') {
                    sydesc = ""
                    temp_yearid = null
                    semdesc = ""
                    temp_semid = null
                    $('#add_subject_to_prospectus').attr('hidden', 'hidden')
                } else {
                    if (acadprogid == 8) {
                        var sydesc = yearlevel2.filter(x => x.id == yearid)[0].text

                    } else {
                        var sydesc = yearlevel.filter(x => x.id == yearid)[0].text
                    }
                    temp_yearid = yearid
                    var semdesc = semester.filter(x => x.id == semid)[0].text
                    temp_semid = semid
                    $('#add_subject_to_prospectus').removeAttr('hidden')
                }

                $('#select_subj').val("").trigger('change');
                $('#subj_prereq').val("").trigger('change');
                $('#input_subj_group').val("").trigger('change');
                $('#subjgroup_holder').removeAttr('hidden')
                $('#sy_label').text(sydesc)
                $('#sem_label').text(semdesc)
                $('#select_subj').attr('disabled', false);
                $(this).find(':input').val('')
                $('#input_subj_desc_prospectus').val("")
                $('#input_subj_code_prospectus').val("")
                $('#input_subj_labunit').val("0")
                $('#input_subj_lecunit').val("0")
                $('#input_subj_credunit').val("0")
                $('#input_subj_labunit').attr('readonly', false);
                $('#input_subj_lecunit').attr('readonly', false);
                // $('#subj_prereq_holder').attr('hidden', 'hidden')

                available_subject_datatable()
                // $('#addsubject_form_modal').modal()
                $('#add_prospectus_subj_modal').modal()
            })

            function delete_prospectus() {
                $.ajax({
                    type: 'GET',
                    url: '/setup/prospectus/delete',
                    data: {
                        curriculumid: $('#curriculum_filter').val(),
                        id: selected_pid
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
                                title: data[0].message
                            })
                            subject_list = subject_list.filter(x => x.id != selected_pid)
                            display_subjects_select(subject_list)
                            plot_subjects(acadprogid, name)
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

        //prospectus

        $(document).ready(function() {

            function year_datatable() {
                if (acadprogid == 8) {
                    var year = yearlevel2
                } else {
                    var year = yearlevel
                }
                $('#year_level_filter').empty()
                $('#year_level_filter').append('<option value="">All</option>')
                $("#year_level_filter").select2({
                    data: year,
                    allowClear: true,
                    placeholder: "All",
                    templateResult: function(data) {
                        var $result = $("<span style='color: black;'></span>");
                        $result.text(data.text);
                        return $result;
                    }
                }).on('select2:open', function() {
                    // Target the dropdown container and apply inline style
                    $('.select2-dropdown').attr('style', 'z-index: 1055 !important;');
                });
            }



            $('#semester_filter').empty()
            $('#semester_filter').append('<option value="">All</option>')
            $("#semester_filter").select2({
                data: semester,
                allowClear: true,
                placeholder: "All",
                templateResult: function(data) {
                    var $result = $("<span style='color: black;'></span>");
                    $result.text(data.text);
                    return $result;
                }
            }).on('select2:open', function() {
                // Target the dropdown container and apply inline style
                $('.select2-dropdown').attr('style', 'z-index: 1055 !important;');
            });

            load_course_datatable()
            get_course()

            $(document).on('change', '#year_level_filter , #semester_filter ', function() {
                $('.div-holder').attr('hidden', 'hidden')

                var temp_subjlist = subject_list

                if ($('#year_level_filter').val() != "") {
                    temp_subjlist = temp_subjlist.filter(x => x.yearID == $('#year_level_filter').val())
                }
                if ($('#semester_filter').val() != "") {
                    temp_subjlist = temp_subjlist.filter(x => x.semesterID == $('#semester_filter').val())
                }

                display_subjects_select(temp_subjlist)

                plot_subjects(acadprogid, name)
            })

            $(document).on('change', '#subject_filter', function() {
                plot_subjects(acadprogid, name)
            })

            function append_yearlevel(acadprogid, name) {
                if (acadprogid == 8) {
                    $('#prospectus_tables').append(
                        `
                        <div class="col-md-12 appended_yearlevel">
                            @foreach ($yearlevel2 as $key => $item)
                                @if ($key == 0)
                                    <hr class="div-holder div-{{ $item->id }} mt-0" hidden>
                                @else
                                    <hr class="div-holder div-{{ $item->id }}" hidden>
                                @endif
                                @foreach ($semester as $sem_item)
                                    <div class="row div-holder sy-{{ $item->id }} sem-{{ $sem_item->id }} div-{{ $item->id }}-{{ $sem_item->id }}"
                                        style="font-size:.8rem !important" hidden>
                                        <div class="col-md-12">
                                            <table class="table-hover table table-striped table-sm table-bordered"
                                                id="table-{{ $item->id }}-{{ $sem_item->id }}" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="5%" class="align-middle">Sort</th>
                                                        <th width="10%" class="align-middle">Code</th>
                                                        <th width="50%">Subject Description</th>
                                                        <th width="15%">Prerequisite</th>
                                                        <th width="5%" class="align-middle text-center p-0">Lect.</th>
                                                        <th width="5%" class="align-middle text-center p-0">Lab.</th>
                                                        <th width="5%" class="align-middle"></th>
                                                        <th width="5%" class="align-middle"></th>
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
                } else {
                    $('#prospectus_tables').append(
                        `
                        <div class="col-md-12 appended_yearlevel">
                            @foreach ($yearlevel as $key => $item)
                                @if ($key == 0)
                                    <hr class="div-holder div-{{ $item->id }} mt-0" hidden>
                                @else
                                    <hr class="div-holder div-{{ $item->id }}" hidden>
                                @endif
                                @foreach ($semester as $sem_item)
                                    <div class="row div-holder sy-{{ $item->id }} sem-{{ $sem_item->id }} div-{{ $item->id }}-{{ $sem_item->id }}"
                                        style="font-size:.8rem !important" hidden>
                                        <div class="col-md-12">
                                            <table class="table-hover table table-striped table-sm table-bordered"
                                                id="table-{{ $item->id }}-{{ $sem_item->id }}" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="5%" class="align-middle">Sort</th>
                                                        <th width="10%" class="align-middle">Code</th>
                                                        <th width="50%">Subject Description</th>
                                                        <th width="15%">Prerequisite</th>
                                                        <th width="5%" class="align-middle text-center p-0">Lect.</th>
                                                        <th width="5%" class="align-middle text-center p-0">Lab.</th>
                                                        <th width="5%" class="align-middle"></th>
                                                        <th width="5%" class="align-middle"></th>
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

            $(document).on('click', '.view_prospetus', function() {
                selected_curri = null
                acadprogid = $(this).attr('data-acadprog')
                name = $(this).closest('tr').find('td:eq(1)').text()
                append_yearlevel(acadprogid, name)
                year_datatable()
                $('.edit_curriculum').attr('hidden', 'hidden')
                $('.delete_curriculum').attr('hidden', 'hidden')
                selected_course = $(this).attr('data-id')
                var temp_course_info = course_list.filter(x => x.id == selected_course)
                $('#course_label').text(temp_course_info[0].courseDesc)
                $('#prospectus_modal').modal()
                subject_list = []
                $('.div-holder').attr('hidden', 'hidden')
                $('#curriculum_filter').empty();
                // available_subject_datatable()
                plot_subjects(acadprogid, name)
                get_curriculum(selected_course)
                get_curriculum2(selected_course)
                $(".add_prospectus_subject").css("display", "none");

            })

            $(document).on('click', '.displaySubjectsperSubjgroup', function() {

                subjgroupId = $(this).attr('data-id')
                $('#display_subjectsPersubjgroup_modal').modal()
                displaySubjperSubjgroup_datatable(subjgroupId)

            })

            function get_course() {
                $.ajax({
                    type: 'GET',
                    url: '/setup/prospectus/courses',

                    success: function(data) {
                        console.log('courses..', data);
                        if (data.length == 0) {
                            Toast.fire({
                                type: 'warning',
                                title: 'You\'re not assign to any college.'
                            })
                        } else {
                            Toast.fire({
                                type: 'success',
                                title: data.length + ' courses found.'
                            })
                        }

                        course_list = data

                        load_course_datatable()
                    }
                })
            }


            $('#prospectus_modal').on('hidden.bs.modal', function() {

                $("#subject_filter").val("").change();
                $("#subject_filter").attr("disabled", "disabled");
                get_course()
            });

            $('#curriculum_form_modal').on('hidden.bs.modal', function() {

                $("#subject_filter").attr("disabled", "disabled");
                get_course()
            });

            $('#curriculum_modal').on('hidden.bs.modal', function() {

                get_course()
            });

            function load_course_datatable() {
                $("#college_datatable").DataTable({
                    destroy: true,
                    data: course_list,
                    // lengthChange: false,
                    // scrollX: true,

                    columns: [{
                            "data": "collegeDesc"
                        },
                        {
                            "data": "courseDesc"
                        },
                        {
                            "data": "courseabrv"
                        },
                        {
                            "data": "curriculumcount"
                        },
                        {
                            "data": null
                        }
                    ],
                    columnDefs: [{
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // $(td).html('');
                                // var text =
                                //     '<a class="text-muted" data-id="' +
                                //     rowData.id + '">' + rowData.collegeDesc + '</a>';
                                // $(td)[0].innerHTML = text;
                                // $(td).addClass('align-middle text-left');
                                // $(td).css('vertical-align', 'middle !important', 'padding:10px;');
                                // $(td).addClass('text-center')
                                var text =
                                    '<span style="white-space: normal; max-width: 100px; break-word; color: inherit;">' +
                                    rowData.collegeDesc.toUpperCase() + '</span>';
                                $(td)[0].innerHTML = text;
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var text =
                                    '<span style="white-space: normal; max-width: 100px; break-word; color: inherit;">' +
                                    rowData.courseDesc.toUpperCase() + '</span>';
                                $(td)[0].innerHTML = text;

                            }
                        },
                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var text =
                                    '<span style="white-space: normal; max-width: 100px; break-word; color: inherit;">' +
                                    rowData.courseabrv.toUpperCase() + '</span>';
                                $(td)[0].innerHTML = text;
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {

                                var link =
                                    '<a href="#" style="color: #blue; text-decoration: none;font-size: 18px;font-weight:bold;" class="view_curriculum" data-id="' +
                                    rowData.id +
                                    '">' + rowData.curriculumcount + '</a>';
                                $(td)[0].innerHTML = link;
                                $(td).addClass('text-center align-middle');

                            }
                        },
                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var link =
                                    '<a href="#" style="color: #blue; text-decoration: none;" class="view_prospetus" data-id="' +
                                    rowData.id +
                                    '" data-acadprog="' + rowData.acadprogid +
                                    '""> View Prospectus</a>';
                                $(td)[0].innerHTML = link;
                                $(td).addClass('text-center align-middle');
                            }
                        },
                    ]

                });

                // Set max-width and overflow 
                $("#college_datatable").css({
                    'max-width': '100px', // Adjust this value as needed
                    'margin': '0 auto',
                    'overflow-x': 'auto'
                });

                // Select the target element
                var label_text = $($('#college_datatable_wrapper')[0].children[0])[0].children[0];

                // Update the innerHTML with both buttons
                $(label_text)[0].innerHTML = `
                    <button class="btn btn-sm btn-primary add_subject mb-2" data-type="list"><i class="fas fa-list"></i> Subject List</button>
                    <button class="btn btn-primary btn-sm ml-1 mb-2"  id="subjgroup_to_modal">
                        <i class="fas fa-list"></i> Subject Groupings
                    </button>
                `;

            }


        })
        $(document).ready(function() {

            var keysPressed = {};

            document.addEventListener('keydown', (event) => {
                keysPressed[event.key] = true;
                if (keysPressed['p'] && event.key == 'v') {
                    Toast.fire({
                        type: 'warning',
                        title: 'Date Version: 07/26/2021 16:34'
                    })
                }
            });

            document.addEventListener('keyup', (event) => {
                delete keysPressed[event.key];
            });


            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })

            $(document).on('input', '#per', function() {
                if ($(this).val() > 100) {
                    $(this).val(100)
                    Toast.fire({
                        type: 'warning',
                        title: 'Subject percentage exceeds 100!'
                    })
                }
            })
        })

        //new

        $(document).on('click', '.view_curriculum', function() {
            selected_curri = null
            $('.edit_curriculum').attr('hidden', 'hidden')
            $('.delete_curriculum').attr('hidden', 'hidden')
            selected_course = $(this).attr('data-id')
            var temp_course_info = course_list.filter(x => x.id == selected_course)
            $('#course_labels').text(temp_course_info[0].courseDesc)
            $('#curriculum_modal').modal()
            subject_list = []
            $('.div-holder').attr('hidden', 'hidden')
            $('#curriculum_filter').empty();
            // available_subject_datatable()
            // plot_subjects(acadprogid, name)
            get_curriculum2(selected_course)
        })

        function curriculum_table(data) {
            // Initialize DataTable
            $('#curriculum_table').DataTable({
                data: data,
                lengthMenu: false,
                searching: false,
                destroy: true,
                lengthChange: false,
                autoWidth: false,
                order: false,
                scrollX: false,
                language: {
                    search: "",
                    searchPlaceholder: "",
                    emptyTable: "",
                    zeroRecords: "No matching records found",
                },
                columns: [{
                        data: 'text'
                    },
                    {
                        data: null
                    },
                    {
                        data: null
                    } // For actions column
                ],
                columnDefs: [{
                        'targets': 0,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            //     var link =
                            //         '<a href="#" style="color: #blue; text-decoration: underline;" class="view_prospetus" data-id="' +
                            //         rowData.id +
                            //         '">' + rowData.text + '</a>';
                            //     $(td)[0].innerHTML = link;
                            //     $(td).addClass('align-middle text-left').css('vertical-align',
                            //         'middle');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            // $(td).addClass('align-middle text-left').css('vertical-align',
                            //     'middle');

                            var enrolled = "0";
                            $(td).html(enrolled);
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var text = `<div class="btn-group">
                            <a href="javascript:void(0)"  class="datatable_edit_curriculum" data-id="${rowData.id}"><i
                                        class="far fa-edit"></i></a>
                                    <a href="javascript:void(0)"  class="datatable_delete_curriculum pl-4" data-id="${rowData.id}"><i
                                        class="far fa-trash-alt text-danger"></i></a>
                                </div>`;
                            $(td).html(text).addClass('align-middle text-center').css(
                                'vertical-align', 'middle');
                        }
                    }
                ]
            });
        }

        function get_curriculum2(selected_course) {
            $.ajax({
                type: 'GET',
                url: '/setup/prospectus/courses/curriculum2',
                data: {
                    courseid: selected_course
                },
                success: function(data) {

                    curriculum_table(data)

                    $('#curriculum_filter').empty();
                    $('#curriculum_filter').append(
                        '<option value="" style="color: black;">Select curriculum</option>');
                    $('#curriculum_filter').append(
                        '<option value="add" style="color: blue;"><b>+</b> Add Curriculum</option>'
                    );

                    $("#curriculum_filter").select2({
                        data: data,
                        // allowClear: true,
                        placeholder: "Select curriculum",
                        templateResult: function(data) {
                            if (data.id === 'add') {
                                return $(
                                    '<span style="  color:rgb(12, 113, 186);"><b>+</b> Add Curriculum</span>'
                                );
                            }
                            return $("<span style='color: black;'>" + data.text + "</span>");
                        }
                    }).on('select2:open', function() {
                        $('.select2-dropdown').attr('style', 'z-index: 1055 !important;');
                    });

                    if (selected_curri != null) {
                        $('#curriculum_filter').val(selected_curri).trigger('change');
                    }

                    if (prompt) {
                        Toast.fire({
                            type: 'info',
                            title: data.length + ' curriculum(s) found.'
                        });
                    }

                }
            });
        }

        $(document).on('click', '.datatable_add_curriculum', function() {
            $('#curriculum-update-btn').attr('id', 'curriculum-btn')
            $('#input_curriculum').val("")
            $('#curriculum-btn').text('Save')
            $('#curriculum-btn').addClass('btn-primary')
            $('#curriculum-btn').removeClass('btn-success')
            $('#curriculum_form_modal').modal()

        });

        $(document).on('click', '#curriculum-btn', function() {

            create_curriculum()

        })


        $(document).on('click', '#curriculum-update-btn', function() {

            update_curriculum()

        })

        $(document).on('click', '.datatable_edit_curriculum', function() {
            $('#curriculum-btn').attr('id', 'curriculum-update-btn')
            $('#curriculum-update-btn').text('Update')
            $('#curriculum-update-btn').addClass('btn-success')
            $('#curriculum-update-btn').removeClass('btn-primary')
            $('#curriculum_form_modal').modal()


            var table = $('#curriculum_table').DataTable();
            var rowId = $(this).data('id'); // Retrieves the ID stored in data-id
            var rowData = table.row($(this).closest('tr')).data();

            $('#curriculum_form_modal').data('edit-id', rowId); // Store ID for later use
            // Update the form with the data from the row
            $('#input_curriculum').val(rowData.text ||
                ""); //  'text' is the field that want to edit



        });



        $(document).on('click', '.datatable_delete_curriculum', function() {
            var rowId = $(this).data('id');

            Swal.fire({
                text: 'Are you sure you want to remove curriculum?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    delete_curriculum(rowId)
                }
            })
        })

        function create_curriculum() {
            $.ajax({
                type: 'GET',
                url: '/setup/prospectus/courses/curriculum/create',
                data: {
                    courseid: selected_course,
                    curriculumname: $('#input_curriculum').val()
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
                            title: data[0].message
                        })
                        get_curriculum(selected_course)
                        // get_curriculum2(selected_course)
                        // get_curriculum()
                        // load_course_datatable()
                        // get_curriculum2(true)
                        // get_course()
                        // get_curriculum(true)
                        // get_curriculum()
                        // load_course_datatable()



                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }

                    get_curriculum2(selected_course)
                    // get_curriculum()
                    // load_course_datatable()
                    // get_curriculum2(true)
                    // get_course()
                }
            })
        }

        function update_curriculum() {

            var editId = $('#curriculum_form_modal').data('edit-id');

            $.ajax({
                type: 'GET',
                url: '/setup/prospectus/courses/curriculum/update',
                data: {
                    courseid: selected_course,
                    // id: $('#curriculum_filter').val(),
                    id: editId,
                    curriculumname: $('#input_curriculum').val()
                },
                success: function(data) {
                    // if (data[0].status == 2) {
                    //     Toast.fire({
                    //         type: 'warning',
                    //         title: data[0].message
                    //     })
                    // } 
                    if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                        get_curriculum2()
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }
            })
        }

        function delete_curriculum(id) {

            $.ajax({
                type: 'GET',
                url: '/setup/prospectus/courses/curriculum/delete',
                data: {
                    courseid: selected_course,
                    // id: $('#curriculum_filter').val()
                    id: id
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
                            title: data[0].message
                        })
                        get_curriculum2(selected_course)
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }
            })
        }

        $('#newsubject_form_modal').on('hide.bs.modal', function(e) {
            $(this).find(':input').val('')
        })




        $(document).on('click', '#new_subject_btn', function() {


            selected_pid = null
            // $('#sort_holder').attr('hidden', 'hidden')
            // $('#subjgroup_holder').removeAttr('hidden')
            $('#input_subj_desc').val("")
            $('#input_subj_code').val("")
            $('#input_subj_labunit').val("")
            $('#input_subj_lecunit').val("")
            // $('#updatesubj_f_btn').attr('id', 'newsubj-f-btn');
            // $('#newsubj-f-btn').text('Create')
            // $('#newsubj-f-btn').addClass('btn-primary')
            // $('#newsubj-f-btn').removeClass('btn-success')
            $('#newsubject_form_modal').modal()



        })

        $(document).on('click', '.edit_subject_list', function() {
            var rowid = $(this).attr('data-id');
            // $('#newsubj-f-btn').attr('id', 'updatesubj_f_btn');
            // $('#updatesubj_f_btn').text('Update');
            // $('#add_subject_label').attr('id', 'edit_subject_label');
            // $('#edit_subject_label').text('Edit Subject');
            // $('#updatesubj_f_btn').removeClass('btn-primary').addClass('btn-success');

            // $('#input_subj_desc').attr('id', 'edit_input_subj_desc');
            // $('#input_subj_code').attr('id', 'edit_input_subj_code');
            // $('#input_labunit').attr('id', 'edit_input_labunit');


            $.ajax({
                type: "GET",
                url: '/setup/prospectus/subject/' + rowid,
                dataType: "json",
                success: function(response) {

                    console.log(response);

                    $('#input_subj_id').val(response.id);
                    $('#edit_input_subj_desc').val(response.subjDesc);
                    $('#edit_input_subj_code').val(response.subjCode);
                    if (parseFloat(response.labunits) === 1.0) {
                        $('#edit_input_labunit').prop('checked', true);
                    } else if (parseFloat(response.labunits) === 0.0) {
                        $('#edit_input_labunit').prop('checked', false);
                    }



                    $('#editsubject_form_modal').modal();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });

        $(document).on('click', '#updatesubj_f_btn', function() {

            var subj_id = $('#input_subj_id').val();
            var subj_desc = $('#edit_input_subj_desc').val();
            var subj_code = $('#edit_input_subj_code').val();
            var labunit = $('#edit_input_labunit').is(':checked') ? 1.0 : 0.0;

            if (subj_desc == "") {
                Toast.fire({
                    type: 'warning',
                    title: "Subject Description is empty"
                })
                return false
            }
            if (subj_code == "") {
                Toast.fire({
                    type: 'warning',
                    title: "Subject Code is empty"
                })
                return false
            }

            $.ajax({
                type: "POST",
                url: '/setup/prospectus/subject/update',
                data: {
                    id: subj_id,
                    subj_desc: subj_desc,
                    subj_code: subj_code,
                    labunit: labunit,
                    _token: '{{ csrf_token() }}'
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        available_subject_datatable()
                        Toast.fire({
                            icon: 'success',
                            title: 'Subject updated successfully!'
                        }).then(() => {

                            $('#editsubject_form_modal').modal('hide');

                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to update subject. ' + response.message,
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while updating the subject.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });



        prereq_subj_select('#subj_prereq')


        function prereq_subj_select(select_id) {
            $.ajax({
                type: "GET",
                url: "/setup/prospectus/subjects",
                success: function(data) {
                    $(select_id).empty(); // Clear existing options
                    $(select_id).append('<option value="">Select Subject</option>'); // Placeholder

                    // Initialize Select2 with the data
                    $(select_id).select2({
                        data: data, // Use the `data` property from the JSON response
                        allowClear: true,
                        placeholder: "Select Subject",
                        templateResult: function(item) {

                            // Use jQuery to parse HTML for styling
                            var $result = $(
                                '<span style="display: inline-flex; align-items: center; width: 400px;">' +
                                '<span style="flex: 0 0 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' +
                                item.code + '</span>' +
                                '<span style="flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' +
                                item.desc + '</span>' +
                                '</span>'
                            );
                            return $result;
                        },
                        templateSelection: function(item) {
                            // Customize how selected items are displayed
                            if (!item.id) {
                                return 'Select Subject'; // Placeholder text
                            }

                            var $selection = $(
                                '<span style="flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' +
                                item.desc + '</span>' +
                                '</span>'
                            );
                            return $selection;
                        }
                    }).on('select2:open', function() {
                        // Target the dropdown container and apply inline style
                        $('.select2-dropdown').attr('style', 'z-index: 1055 !important;');
                    });

                },
                error: function(xhr, status, error) {
                    console.error("Failed to fetch subjects:", error);
                }
            });
        }


        $(document).on('change', '#select_subj', function() {
            // Get the selected value (id of the subject)
            var selectedId = $(this).val();


            if (selectedId) {
                $.ajax({
                    type: "GET",
                    url: '/setup/prospectus/subject/' + selectedId,
                    dataType: "json",
                    success: function(response) {
                        // console.log(response);

                        // Populate fields with the response data
                        // $('#select_subj').val(response.subjDesc).trigger('change'); //dropdown select

                        $('#input_subj_id_prospectus').val(response.id); // input text

                        $('#input_subj_desc_prospectus').val(response.subjDesc); // input text
                        $('#input_subj_code_prospectus').val(response.subjCode); // input text
                    },
                    error: function(xhr, status, error) {
                        console.error("Failed to fetch subject details:", error);
                    }
                });
            }
        });


        // subj_select('#select_subj')


        // function subj_select(select_id) {
        //     $.ajax({
        //         type: "GET",
        //         url: "/setup/prospectus/subjects",
        //         success: function(data) {
        //             $(select_id).empty();
        //             $(select_id).append('<option value="">Select Subject</option>'); // Placeholder


        //             $(select_id).select2({
        //                 data: data,
        //                 allowClear: true,
        //                 placeholder: "Select Subject",
        //                 minimumResultsForSearch: 0,
        //                 templateResult: function(item) {


        //                     var $result = $(
        //                         '<span style="display: inline-flex; align-items: center; width: 400px;">' +
        //                         '<span style="flex: 0 0 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: black;">' +
        //                         item.code + '</span>' +
        //                         '<span style="flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: black;">' +
        //                         item.desc + '</span>' +
        //                         '</span>'
        //                     );
        //                     return $result;
        //                 },
        //                 templateSelection: function(item) {
        //                     // Customize how selected items are displayed
        //                     if (!item.id) {
        //                         return 'Select Subject';
        //                     }

        //                     var $selection = $(
        //                         '<span style="flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: black;">' +
        //                         item.desc + '</span>' +
        //                         '</span>'
        //                     );
        //                     return $selection;
        //                 }

        //             }).on('select2:open', function() {
        //                 $('.select2-dropdown').attr('style', 'z-index: 1055 !important;');
        //             });

        //             // Add hover effect to change font color to white
        //             $(document).on('mouseenter', '.select2-results__option', function() {
        //                 $(this).css('background-color', 'lightblue');
        //             }).on('mouseleave', '.select2-results__option', function() {
        //                 $(this).css('background-color', 'white');
        //             });

        //         },
        //         error: function(xhr, status, error) {
        //             console.error("Failed to fetch subjects:", error);
        //         }
        //     });
        // }

        //working v2
        // subj_select('#select_subj')

        //working v2
        // function subj_select(select_id) {
        //     $.ajax({
        //         type: "GET",
        //         url: "/setup/prospectus/subjects",
        //         success: function(data) {
        //             $(select_id).empty().append('<option value="">Select Subject</option>'); // Placeholder

        //             $(select_id).select2({
        //                 data: data,
        //                 allowClear: true,
        //                 placeholder: "Select Subject",
        //                 minimumResultsForSearch: 1, // Enable search box
        //                 templateResult: createResultTemplate,
        //                 templateSelection: createSelectionTemplate,
        //                 matcher: function(params, data) {
        //                     // If there are no search terms, return all results
        //                     if ($.trim(params.term) === '') {
        //                         return data;
        //                     }

        //                     // Check if the data object has a 'text' property
        //                     if (typeof data.text === 'undefined') {
        //                         return null; // No match
        //                     }

        //                     // Search in both code and description
        //                     var term = params.term.toLowerCase();
        //                     var codeMatch = data.code && data.code.toLowerCase().indexOf(term) > -1;
        //                     var descMatch = data.desc && data.desc.toLowerCase().indexOf(term) > -1;

        //                     if (codeMatch || descMatch) {
        //                         return data; // Return matched data
        //                     }

        //                     return null; // No match
        //                 }
        //             }).on('select2:open', function() {
        //                 $('.select2-dropdown').attr('style', 'z-index: 1055 !important;');
        //             });

        //             addHoverEffect();
        //         },
        //         error: function(xhr, status, error) {
        //             console.error("Failed to fetch subjects:", error);
        //         }
        //     });
        // }

        subj_select('#select_subj')

        function subj_select(select_id) {
            $.ajax({
                type: "GET",
                url: "/setup/prospectus/subjects",
                success: function(data) {
                    $(select_id).empty().append('<option value="">Select Subject</option>'); // Placeholder
                    $(select_id).empty().append('<option value="add"> Add Subject</option>');

                    $(select_id).select2({
                        data: data,
                        allowClear: true,
                        placeholder: "Select Subject",
                        minimumResultsForSearch: 1, // Enable search box
                        templateResult: createResultTemplate,
                        templateSelection: createSelectionTemplate,
                        matcher: function(params, data) {
                            if ($.trim(params.term) === '') {
                                return data;
                            }

                            if (typeof data.text === 'undefined') {
                                return null; // No match
                            }

                            var term = params.term.toLowerCase();
                            var codeMatch = data.code && data.code.toLowerCase().indexOf(term) > -1;
                            var descMatch = data.desc && data.desc.toLowerCase().indexOf(term) > -1;

                            if (codeMatch || descMatch) {
                                return data; // Return matched data
                            }

                            return null; // No match
                        }
                    }).on('select2:open', function() {
                        $('.select2-dropdown').attr('style', 'z-index: 1055 !important;');
                    });

                    addHoverEffect();
                },
                error: function(xhr, status, error) {
                    console.error("Failed to fetch subjects:", error);
                }
            });
        }

        function createResultTemplate(item) {
            if (item.id === 'add') {
                // $(select_id).val("").trigger('change');
                // return '';
                $("#select_subj").val("").change();
                return $(
                    '<span style="color:rgb(12, 113, 186);"><i class="fas fa-plus"></i> Add Subject</span>'
                );
            }
            return $(
                '<span style="display: inline-flex; align-items: center; width: 400px;">' +
                '<span style="flex: 0 0 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: black;">' +
                item.code + '</span>' +
                '<span style="flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: black;">' +
                item.desc + '</span>' +
                '</span>'
            );
        }

        function createSelectionTemplate(item) {
            if (item.id === 'add') {
                return ''; // Return empty string for 'add' option
            }
            if (!item.id) {
                return 'Select Subject'; // Placeholder text
            }
            return $(
                '<span style="flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: black;">' +
                item.desc + '</span>');
        }

        // working v2
        // function createResultTemplate(item) {
        //     return $('<span style="display: inline-flex; align-items: center; width: 400px;">' +
        //         '<span style="flex: 0 0 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: black;">' +
        //         item.code + '</span>' +
        //         '<span style="flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: black;">' +
        //         item.desc + '</span>' +
        //         '</span>');
        // }


        //working v2
        // function createSelectionTemplate(item) {
        //     if (!item.id) {
        //         return 'Select Subject'; // Placeholder text
        //     }
        //     return $(
        //         '<span style="flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: black;">' +
        //         item.desc + '</span>');
        // }

        function addHoverEffect() {
            $(document).on('mouseenter', '.select2-results__option', function() {
                $(this).css('background-color', 'lightblue');
            }).on('mouseleave', '.select2-results__option', function() {
                $(this).css('background-color', 'white');
            });
        }


        $('#input_subj_lecunit, #input_subj_labunit').on('input', updateCredUnits);



        function updateCredUnits() {

            var lecUnits = parseFloat($('#input_subj_lecunit').val()) || 0;
            var labUnits = parseFloat($('#input_subj_labunit').val()) || 0;


            var totalUnits = lecUnits + labUnits;


            $('#input_subj_credunit').val(totalUnits);
        }

        updateCredUnits();


        function displaySubjperSubjgroup_datatable(subjgroupId) {
            var courseid = selected_course;
            var curriculumid = $('#curriculum_filter').val();
            var table = $("#displaySubjperSubjgroup_datatable").DataTable({
                destroy: true,
                lengthChange: false,
                autoWidth: true,
                scrollX: true,
                ajax: {
                    url: "/setup/prospectus/subjgroup/subjectsDatatable",
                    type: "GET",
                    data: function(data) {
                        data.subjgroup_id = subjgroupId;
                        data.courseid = courseid;
                        data.curriculumid = curriculumid;
                    },
                    dataSrc: function(json) {
                        table.recordsTotal = json.recordsTotal;
                        table.recordsFiltered = json.recordsFiltered;
                        return json.data;
                    }
                },
                columns: [{
                        title: "Subject Code",
                        "data": "subjCode"
                    },
                    {
                        title: "Subject Description",
                        "data": "subjDesc"
                    },
                    {
                        title: "Pre-requisite",
                        "data": "prerequisites"

                    },
                    {
                        title: "Lecture",
                        "data": "lecunits"
                    },
                    {
                        title: "Laboratory",
                        "data": "labunits"
                    },
                    {
                        title: "Credited Units",
                        "data": "credunits"
                    }
                ],
                columnDefs: [{
                        'targets': 0,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html('<span>' + cellData +
                                ' <p class="subjgroup_subjectscount" hidden>' +
                                table.recordsFiltered +
                                '</p></span>');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html('<span>' + cellData + '</span>');
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false
                        // 'createdCell': function(td, cellData, rowData, row, col) {
                        //     $(td).html('');
                        // }
                    },
                    {
                        'targets': 3,
                        'orderable': false
                    },
                    {
                        'targets': 4,
                        'orderable': false
                    },
                    {
                        'targets': 5,
                        'orderable': false
                    }
                ],
                initComplete: function() {
                    // Ensure footer exists
                    if ($('#displaySubjperSubjgroup_datatable tfoot').length === 0) {
                        $('#displaySubjperSubjgroup_datatable').append(
                            '<tfoot>' +
                            '<tr>' +
                            '<th colspan="3" style="text-align:right;">Total Units</th>' +
                            '<th id="total_lecture"></th>' +
                            '<th id="total_laboratory"></th>' +
                            '<th id="total_credited_units"></th>' +
                            '</tr>' +
                            '</tfoot>'
                        );
                    }
                    // Initial calculation
                    calculateTotalUnits();
                },
                drawCallback: function() {
                    // Recalculate total on each draw
                    calculateTotalUnits();
                }
            });

            // Function to calculate and update the totals
            function calculateTotalUnits() {
                // Ensure `table` is correctly initialized
                if (table) {
                    var totalLecture = 0;
                    var totalLaboratory = 0;
                    var totalCreditedUnits = 0;

                    // Loop through each row and sum the units
                    table.rows().every(function() {
                        var data = this.data();
                        totalLecture += parseFloat(data.lecunits) || 0;
                        totalLaboratory += parseFloat(data.labunits) || 0;
                        totalCreditedUnits += parseFloat(data.credunits) || 0;
                    });

                    // Update the footer with the total units
                    $('#total_lecture').html(totalLecture.toFixed(2));
                    $('#total_laboratory').html(totalLaboratory.toFixed(2));
                    $('#total_credited_units').html(totalCreditedUnits.toFixed(2));
                } else {
                    console.error("DataTable instance not found.");
                }
            }
        }

        var subjectgroup_datatable = [];
        var subjectgroup_select = [];
        var all_subjgroup = [];
        var seleted_id = null;

        function subjgroup() {
            var subjgroup_list = [];

            $.ajax({
                type: "GET",
                url: "/setup/prospectus/subjgroup",
                async: false,
                success: function(data) {
                    subjgroup_list = data;
                },
            });

            return subjgroup_list;
        }

        // function subjgroup_select(select_id) {
        //     $.ajax({
        //         type: "GET",
        //         url: "/setup/prospectus/subjgroup",
        //         async: false,

        //         success: function(data) {
        //             $(select_id).addClass("is_subjgroup_select");
        //             $(select_id).empty();
        //             // $(select_id).append(
        //             //     '<option value="">Select Subject Group</option>'
        //             // );
        //             $(select_id).append(
        //                 '<option value="add" style="color: blue;"><i class="fas fa-plus"></i> Add Subject Group</option>'
        //             )
        //             // $(select_id).append('<option value="add">Add curriculum</option>')
        //             $(select_id).select2({
        //                     data: data,
        //                     allowClear: true,
        //                     placeholder: "Select Subject Group",
        //                     templateResult: function(data) {
        //                         var $result = $("<span style='color: black;'></span>");
        //                         $result.text(data.text);
        //                         return $result;
        //                     }
        //                 })
        //                 .on('select2:open', function() {
        //                     // Target the dropdown container and apply inline style
        //                     $('.select2-dropdown').css('z-index', '1055');
        //                 });
        //         },
        //     });
        // }

        function subjgroup_select(select_id) {
            $.ajax({
                type: "GET",
                url: "/setup/prospectus/subjgroup",
                async: false,

                success: function(data) {
                    $(select_id).addClass("is_subjgroup_select");
                    $(select_id).empty();
                    // $(select_id).append(
                    //     '<option value="">Select Subject Group</option>'
                    // );
                    $(select_id).append(
                        '<option value="add"> Add Subject Group</option>'
                    )
                    // $(select_id).append('<option value="add">Add curriculum</option>')
                    $(select_id).select2({
                            data: data,
                            allowClear: true,
                            placeholder: "Select Subject Group",
                            templateResult: function(data) {
                                if (data.id === 'add') {
                                    return $(
                                        '<span style="  color:rgb(12, 113, 186);"><i class="fas fa-plus"></i> Add Subject Group</span>'
                                    );
                                }
                                return $("<span style='color: black;'>" + data.text +
                                    "</span>");
                            }
                        })
                        .on('select2:open', function() {
                            // Target the dropdown container and apply inline style
                            $('.select2-dropdown').css('z-index', '1055');
                        });
                },
            });
        }

        $(document).on('change', '#input_subj_group', function() {


            if ($(this).val() == "add") {
                // $('#curriculum-f-btn').text('Create')
                // $('#curriculum-f-btn').removeClass('btn-success')
                // $('#curriculum-f-btn').addClass('btn-primary')
                // $('#input_curriculum').val("").change()
                // $('#curriculum_form_modal').modal()
                // $('#curriculum_filter').val("").change()

                // $('.print').attr('disabled', 'disabled')


                // selected_curri = null
                // subject_list = []
                // plot_subjects(acadprogid, name)
                $("#subjgroup_input_sort").val("");
                $("#subjgroup_input_numorder").val("");
                $("#subjgroup_input_description").val("");

                $("#subjgroup_create_button").removeAttr("hidden");
                $("#subjgroup_update_button").attr("hidden", "hidden");
                $("#subjgroup_form_modal").modal();

            } else if ($(this).val() != "") {
                selected_curri = $(this).val()
                $('.edit_curriculum').removeAttr('hidden')
                $('.delete_curriculum').removeAttr('hidden')
                $('.print').removeAttr('disabled')
                get_subjects(true)
                // $(".add_prospectus_subject").removeAttr("hidden");
            }
        })

        function subjgroup_datatable() {
            $("#subjgroup_modal").modal();

            var table = $("#subjgroup_datatable").DataTable({
                destroy: true,
                bInfo: false,
                autoWidth: false,
                lengthChange: true,
                stateSave: true,
                serverSide: true,
                processing: true,
                ajax: {
                    url: "/setup/prospectus/subjgroup/datatable",
                    type: "GET",
                    dataSrc: function(json) {
                        all_subjgroup = json.data;
                        return json.data;
                    },
                    // dataSrc: function(json) {
                    //     table.recordsTotal = json.recordsTotal;
                    //     table.recordsFiltered = json.recordsFiltered;
                    //     return json.data;
                    // },
                },
                columns: [

                    {
                        data: "description"
                    },
                    {
                        data: "totalcredunits"
                    },
                    {
                        data: null
                    },
                    {
                        data: null
                    },
                ],
                order: [
                    [0, "asc"]
                ],
                columnDefs: [

                    {
                        'targets': 0,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            // $(td).addClass('text-center')
                            // $(td).addClass('align-middle')

                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            // $(td).html('');

                            var link =
                                '<a href="#" style="color: #blue; text-decoration: underline;" class="displaySubjectsperSubjgroup" data-id="' +
                                rowData.id +
                                '">' + cellData + '</a>';
                            $(td)[0].innerHTML = link;
                        }
                    },
                    {
                        targets: 2,
                        orderable: false,
                        createdCell: function(td, cellData, rowData, row, col) {
                            var buttons =
                                '<a href="javascript:void(0)" class="subjgroup_edit" data-id="' +
                                rowData.id +
                                '"><i class="far fa-edit"></i></a>';
                            $(td)[0].innerHTML = buttons;
                            $(td).addClass("text-center");
                            $(td).addClass("align-middle");
                        },
                    },
                    {
                        targets: 3,
                        orderable: false,
                        createdCell: function(td, cellData, rowData, row, col) {
                            var buttons =
                                '<a href="javascript:void(0)" class="subjgroup_delete" data-id="' +
                                rowData.id +
                                '"><i class="far fa-trash-alt text-danger"></i></a>';
                            $(td)[0].innerHTML = buttons;
                            $(td).addClass("text-center");
                            $(td).addClass("align-middle");
                        },
                    },
                ],
            });

            var label_text = $($("#subjgroup_datatable_wrapper")[0].children[0])[0]
                .children[0];
            $(label_text)[0].innerHTML =
                '<button class="btn btn-sm btn-primary" id="subjgroup_to_create_modal"> <i class="fas fa-plus"></i> Add Subject Grouping</button>';
        }

        function subjgroup_create() {
            if ($("#subjgroup_input_numorder").val() == "") {
                Toast.fire({
                    type: "info",
                    title: "Num. Order is empty!",
                });
                return false;
            }

            if ($("#subjgroup_input_description").val() == "") {
                Toast.fire({
                    type: "info",
                    title: "Decription is empty!",
                });
                return false;
            }

            $.ajax({
                type: "GET",
                url: "/setup/prospectus/subjgroup/create",
                data: {
                    sort: $("#subjgroup_input_sort").val(),
                    numorder: $("#subjgroup_input_numorder").val(),
                    description: $("#subjgroup_input_description").val(),
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        $("#subjgroup_form_modal").modal('hide');
                        subjgroup_select(".is_subjgroup_select");
                        $(".is_subjgroup_select").val(null).trigger("change");
                        subjgroup_datatable();
                    }
                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message,
                    });
                },
            });
        }

        function subjgroup_update() {
            if ($("#subjgroup_input_numorder").val() == "") {
                Toast.fire({
                    type: "info",
                    title: "Num. Order is empty!",
                });
                return false;
            }

            if ($("#subjgroup_input_description").val() == "") {
                Toast.fire({
                    type: "info",
                    title: "Decription is empty!",
                });
                return false;
            }

            $.ajax({
                type: "GET",
                url: "/setup/prospectus/subjgroup/update",
                data: {
                    id: seleted_id,
                    sort: $("#subjgroup_input_sort").val(),
                    numorder: $("#subjgroup_input_numorder").val(),
                    description: $("#subjgroup_input_description").val(),
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        subjgroup_select(".is_subjgroup_select");
                        subjgroup_datatable();
                    }
                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message,
                    });
                },
            });
        }

        function subjgroup_delete() {
            $.ajax({
                type: "GET",
                url: "/setup/prospectus/subjgroup/delete",
                data: {
                    id: seleted_id,
                },
                success: function(data) {
                    if (data[0].status == 1) {
                        subjgroup_select(".is_subjgroup_select");
                        subjgroup_datatable();
                    }
                    Toast.fire({
                        type: data[0].icon,
                        title: data[0].message,
                    });
                },
            });
        }

        var modal_html =
            '<div class="modal fade" id="subjgroup_modal" style="display: none; z-index: 1050; aria-hidden: true;">' +
            '<div class="modal-dialog modal-lg">' +
            '<div class="modal-content" style="margin-top:20px;max-height: 600px;overflow-y: auto;" >' +
            '<div class="modal-header pb-2 pt-2 border-0">' +
            '<h4 class="modal-title" style="font-size: 1.1rem !important">Subject Grouping</h4>' +
            '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
            '<span aria-hidden="true">×</span></button>' +
            "</div>" +
            '<div class="modal-body pt-0">' +
            '<div class="row mt-2" style="font-size:.8rem !important">' +
            '<div class="col-md-12">' +
            '<table class="table-hover table table-striped table-sm table-bordered" id="subjgroup_datatable" width="100%" >' +
            "<thead>" +
            "<tr>" +
            // '<th width="8%" class="align-middle p-0 text-center">Sort</th>' +
            // '<th width="8%"  class="align-middle p-0 text-center">Code</th>' +
            '<th width="64%" class="align-middle">Grouping Name</th>' +
            '<th width="18%" class="align-middle">Units</th>' +
            '<th width="13%" class="align-middle text-center p-0" colspan="2">Action</th>' +
            // '<th width="5%" class="align-middle text-center p-0"></th>' +
            "</tr>" +
            "</thead>" +
            "</table>" +
            "<div>" +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>";

        $("#subjgroup_modal").remove();
        $("body").append(modal_html);

        var modal_html =
            '<div class="modal fade" id="subjgroup_form_modal" style="display: none;z-index: 1060; " aria-hidden="true" >' +
            '<div class="modal-dialog modal-sm">' +
            '<div class="modal-content" >' +
            '<div class="modal-header pb-2 pt-2 border-0">' +
            '<h4 class="modal-title" style="font-size: 1.1rem !important">Subject Group Form</h4>' +
            '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
            '<span aria-hidden="true">×</span></button>' +
            "</div>" +
            '<div class="modal-body pt-0">' +
            // '<div class="row">' +
            // '<div class="col-md-12 form-group">' +
            // '<label for="">Sort</label>' +
            // '<input class="form-control form-control-sm" id="subjgroup_input_sort" onkeyup="this.value = this.value.toUpperCase();" >' +
            // "</div>" +
            // "</div>" +
            // '<div class="row">' +
            // '<div class="col-md-12 form-group">' +
            // '<label for="">Num. Order</label>' +
            // '<input class="form-control form-control-sm" id="subjgroup_input_numorder">' +
            // "</div>" +
            // "</div>" +
            '<div class="row">' +
            '<div class="col-md-12 form-group">' +
            '<label for="">Group Description</label>' +
            '<input class="form-control form-control-sm" id="subjgroup_input_description">' +
            "</div>" +
            "</div>" +
            '<div class="row">' +
            '<div class="col-md-12">' +
            '<button class="btn btn-sm btn-primary" id="subjgroup_create_button"><i class="fa fa-save"></i> Save</button>' +
            '<button class="btn btn-success btn-success btn-sm" id="subjgroup_update_button" hidden><i class="fa fa-save"></i> Update</button>' +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>";

        $("#subjgroup_form_modal").remove();
        $("body").append(modal_html);

        $(document).on("click", "#subjgroup_to_create_modal", function() {
            $("#subjgroup_input_sort").val("");
            $("#subjgroup_input_numorder").val("");
            $("#subjgroup_input_description").val("");

            $("#subjgroup_create_button").removeAttr("hidden");
            $("#subjgroup_update_button").attr("hidden", "hidden");
            $("#subjgroup_form_modal").modal();
        });

        $(document).on("click", ".subjgroup_edit", function() {
            seleted_id = $(this).attr("data-id");
            var temp_info = all_subjgroup.filter((x) => x.id == seleted_id);
            $("#subjgroup_input_sort").val(temp_info[0].sort);
            $("#subjgroup_input_numorder").val(temp_info[0].sortnum);
            $("#subjgroup_input_description").val(temp_info[0].description);

            $("#subjgroup_create_button").attr("hidden", "hidden");
            $("#subjgroup_update_button").removeAttr("hidden");

            $("#subjgroup_form_modal").modal();
        });

        $(document).on("click", "#subjgroup_create_button", function() {
            subjgroup_create();
        });

        $(document).on("click", "#subjgroup_update_button", function() {
            subjgroup_update();
        });

        $(document).on("click", ".subjgroup_delete", function() {
            seleted_id = $(this).attr("data-id");
            var temp_info = all_subjgroup.filter((x) => x.id == seleted_id);
            var subjgroup_subjects = parseFloat(temp_info[0].totalprospectus);
            if (subjgroup_subjects == 0.0) {
                Swal.fire({
                    text: "Are you sure you want to remove Subject Group?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Remove",
                }).then((result) => {
                    if (result.value) {
                        subjgroup_delete();
                    }
                });
            } else {
                Toast.fire({
                    type: "warning",
                    title: "Cannot delete: Subject group contains subjects",
                });
            }
        });

        // $(document).on("click", ".subjgroup_delete", function() {
        //     seleted_id = $(this).attr("data-id");
        //     Swal.fire({
        //         text: "Are you sure you want to remove Subject Group?",
        //         type: "warning",
        //         showCancelButton: true,
        //         confirmButtonColor: "#3085d6",
        //         cancelButtonColor: "#d33",
        //         confirmButtonText: "Remove",
        //     }).then((result) => {
        //         if (result.value) {
        //             subjgroup_delete();
        //         }
        //     });
        // });

        $('#prospectus_modal').on('hidden.bs.modal', function() {
            $('.appended_yearlevel').remove();
        })


        $('#closeModalBtn').on('click', function() {
            resetAddProspectus();
        });

        function resetAddProspectus() {
            if ($("#input_subj_desc_prospectus").val().trim() != "" && $("#input_subj_code_prospectus").val()
                .trim() !=
                "") {


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
                                title: 'Prospectus has been created.',
                                confirmButtonText: 'OK'
                            })



                            $('#add_prospectus_subj_modal').modal({
                                keyboard: false
                            });
                            // var table = $("#gradingPointsTable");
                            // // table.find("tbody tr:not(.default-row)").remove();
                            // table.find("tbody tr:not(.default-row)").remove();
                            // table.find("tbody .default-row").show();
                            // $("#gradePointEquivalencyDescription").val("");
                            $('#add_subject_to_prospectus').click();

                            // prospectusAddModal.show();
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
        }

        $(document).on('change', '#select_subj', function() {
            if ($(this).val() == "add") {
                $("#newsubject_form_modal").modal();
            }
        })

        // $('#employeecloseModalBtn').on('click', function() {
        //     var hasData =

        //         $("#rfid").val().trim() !== "" ||
        //         $("#firstName").val().trim() !== "" ||
        //         $("#middleName").val().trim() !== "" ||
        //         $("#lastName").val().trim() !== "" ||
        //         $("#suffix").val().trim() !== "" ||
        //         $("#civilStatus").val().trim() !== "" ||
        //         $("#birthdate").val().trim() !== "" ||
        //         $("#cellphone").val().trim() !== "" ||
        //         $("#email").val().trim() !== "" ||
        //         $("#address").val().trim() !== "" ||

        //         $("#contactFirstname").val().trim() !== "" ||
        //         $("#contactMiddlename").val().trim() !== "" ||
        //         $("#contactLastname").val().trim() !== "" ||
        //         $("#contactSuffix").val().trim() !== "" ||
        //         $("#Relationship").val().trim() !== "" ||
        //         $("#Telephone").val().trim() !== "" ||
        //         $("#Cellphone").val().trim() !== "" ||
        //         $("#Email").val().trim() !== "" ||

        //         $("#sss").val().trim() !== "" ||
        //         $("#pagibig").val().trim() !== "" ||
        //         $("#philhealth").val().trim() !== "" ||
        //         $("#tin").val().trim() !== "" ||

        //         //employment details
        //         $("#date_hired").val().trim() !== "" ||
        //         $("#accumulated_years_service").val().trim() !== "" ||
        //         $("#probationary_start_date").val().trim() !== "" ||
        //         $("#probationary_end_date").val().trim() !== ""


        //     ;

        //     $(".highest_education_section .highest_education_row:visible").each(function() {
        //         if (
        //             $(this).find("#school_name").val().trim() != "" ||
        //             $(this).find("#year_graduated").val().trim() != "" ||
        //             $(this).find("#course").val().trim() != "" ||
        //             $(this).find("#award").val().trim() != ""
        //         ) {
        //             hasData = true;
        //             return false;
        //         }
        //     })

        //     $(".work_experience_section .work_experience_row:visible").each(function() {
        //         if (
        //             $(this).find("#Company").val().trim() != "" ||
        //             $(this).find("#Designation").val().trim() != "" ||
        //             $(this).find("#Datefrom").val().trim() != "" ||
        //             $(this).find("#Dateto").val().trim() != ""
        //         ) {
        //             hasData = true;
        //             return false;
        //         }
        //     })

        //     $(".addbank_account_section .addbank_account_row:visible").each(function() {
        //         if (
        //             $(this).find("#append_bank_name").val().trim() != "" ||
        //             $(this).find("#append_bank_number").val().trim() != ""
        //         ) {
        //             hasData = true;
        //             return false;
        //         }
        //     })

        //     //employment details

        //     if ($('#select-designation').val() !== "") {
        //         hasData = true;
        //     }
        //     if ($('#select-department').val() !== "") {
        //         hasData = true;
        //     }
        //     if ($('#select-office').val() !== "") {
        //         hasData = true;
        //     }
        //     if ($('#select-jobstatus').val() !== "") {
        //         hasData = true;
        //     }


        //     //workday
        //     if ($('#select-generalworkdays').val() !== "") {
        //         hasData = true;
        //     }


        //     if (hasData) {
        //         // Confirm with the user before deleting all attendance data
        //         Swal.fire({
        //             // title: 'Create Grade Point Equivalency Reset',
        //             text: 'You have unsaved changes. Would you like to save your work before leaving?',
        //             type: 'warning',
        //             showCancelButton: true,
        //             cancelButtonText: 'Save Changes',
        //             confirmButtonColor: '#d33',
        //             cancelButtonColor: '#3085d6',
        //             confirmButtonText: 'Discard Changes',
        //             reverseButtons: true
        //         }).then((result) => {
        //             if (result.value) {

        //                 $("#profile_picture").attr('src', "/avatar/S(F) 1.png");
        //                 $("#employee_picture").val("");

        //                 $("#rfid").val("");
        //                 $("#firstName").val("");
        //                 $("#middleName").val("");
        //                 $("#lastName").val("");
        //                 $("#suffix").val("");
        //                 $("#civilStatus").val("");
        //                 $("#birthdate").val("");
        //                 $("#cellphone").val("");
        //                 $("#email").val("");
        //                 $("#address").val("");

        //                 $("#contactFirstname").val("");
        //                 $("#contactMiddlename").val("");
        //                 $("#contactLastname").val("");
        //                 $("#contactSuffix").val("");
        //                 $("#Relationship").val("");
        //                 $("#Telephone").val("");
        //                 $("#Cellphone").val("");
        //                 $("#Email").val("");

        //                 $('#sss').val("");
        //                 $('#pagibig').val("");
        //                 $('#philhealth').val("");
        //                 $('#tin').val("");

        //                 //employment details
        //                 $('#select-designation').val("").trigger('change');
        //                 $('#select-department').val("").trigger('change');
        //                 $('#select-office').val("").trigger('change');
        //                 $('#select-jobstatus').val("").trigger('change');
        //                 $('#date_hired').val("");
        //                 $('#accumulated_years_service').val("");
        //                 $('#probationary_start_date').val("");
        //                 $('#probationary_end_date').val("");

        //                 //workday
        //                 $('#select-generalworkdays').val("").trigger('change');
        //                 $('#generalworkdaysTable').empty();



        //                 $(".highest_education_row").each(function() {
        //                     $(this).find('input').val("");
        //                     $(this).find('select').val("").trigger('change');
        //                 });

        //                 $(".highest_education_row:not(:first-of-type)").remove();

        //                 $(".work_experience_row").each(function() {
        //                     $(this).find('input').val("");
        //                     $(this).find('select').val("").trigger('change');
        //                 });
        //                 $(".work_experience_row:not(:first-of-type)").remove();


        //                 $(".addbank_account_row").each(function() {
        //                     $(this).find('input').val("");
        //                     $(this).find('select').val("").trigger('change');
        //                 });
        //                 $(".addbank_account_row:not(:first-of-type)").remove();


        //             } else {
        //                 // Save employee
        //                 $('#save_employeebtn__employeeInformation').click();
        //                 $('#newEmployeeModal').modal('show');

        //                 $("#profile_picture").attr('src', "/avatar/S(F) 1.png");
        //                 $("#employee_picture").attr('src', "/avatar/S(F) 1.png");

        //             }
        //         });
        //     } else {
        //         // Hide modal
        //         $('#newEmployeeModal').modal('hide');

        //         $("#profile_picture").attr('src', "/avatar/S(F) 1.png");
        //         $("#employee_picture").val("");
        //     }
        // });
    </script>
@endsection
