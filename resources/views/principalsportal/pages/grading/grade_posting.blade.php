@php
    $refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->where('deleted',0)->select('refid')->first();
    $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;
    
    if(Session::get('currentPortal') == 3){
        $xtend = 'registrar.layouts.app';
        $acadprogid = DB::table('teacheracadprog')
                        ->where('teacherid',$teacherid)
                        ->where('acadprogutype',3)
                        ->select('acadprogid','syid')
                        ->where('deleted',0)
                        ->orderBy('acadprogid')
                        ->get();
    }
    else if(Session::get('currentPortal') == 2){
        $acadprogid = DB::table('teacheracadprog')
                        ->where('teacherid',$teacherid)
                        ->where('acadprogutype',2)
                        ->select('acadprogid','syid')
                        ->where('deleted',0)
                        ->orderBy('acadprogid')
                        ->get();

        $xtend = 'principalsportal.layouts.app2';
    }
    else{
        if( $refid->refid == 20){
            $xtend = 'principalassistant.layouts.app2';
        }elseif( $refid->refid == 22){
            $xtend = 'principalcoor.layouts.app2';
        }else if(Session::get('currentPortal') == 3){
            $xtend = 'registrar.layouts.app';
        }else{
                if(isset($refid->refid)){
                    if($refid->refid == 27){
                            $xtend = 'academiccoor.layouts.app2';
                    }
                }else{
                    $xtend = 'general.defaultportal.layouts.app';
                }
        }

        $acadprogid = DB::table('teacheracadprog')
                        ->where('teacherid',$teacherid)
                        ->where('acadprogutype',Session::get('currentPortal'))
                        ->select('acadprogid','syid')
                        ->where('deleted',0)
                        ->orderBy('acadprogid')
                        ->get();
    }
    
    $all_acad = array();

    foreach( $acadprogid as $item){
        if($item->acadprogid != 6){
            array_push($all_acad,$item->acadprogid);
        }
    }

    $gradelevel = DB::table('gradelevel')
                    ->where('deleted',0)
                    ->whereIn('acadprogid', $all_acad)
                    ->orderBy('sortid')
                    ->select(
                        'id',
                        'levelname',
                        'levelname as text',
                        'acadprogid'
                    )
                    ->get();

    $allsections = DB::table('sectiondetail')
                    ->join('sections',function($join) use($gradelevel){
                        $join->on('sectiondetail.sectionid','=','sections.id');
                        $join->where('sections.deleted',0);
                        $join->whereIn('sections.levelid',collect($gradelevel)->pluck('id'));
                    })
                    ->where('sectiondetail.deleted',0)
                    ->orderBy('sectionname')
                    ->select(
                        'sections.sectionname as text',
                        'syid',
                        'sections.id',
                        'sections.sectionname',
                        'sections.levelid'
                    )
                    ->get();

    $schoolinfo = DB::table('schoolinfo')->first();

@endphp

@extends($xtend)

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    <style>
        .subj_tr {
            vertical-align: middle!important;
            cursor: pointer;
        }
        .stud_subj_tr {
            vertical-align: middle!important;
            cursor: pointer;
        }
        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            border: 0 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }
    </style>
@endsection

@section('content')

    @php
        $subj_strand = DB::table('sh_sectionblockassignment')
                        ->join('sh_block',function($join){
                            $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                            $join->where('sh_block.deleted',0);
                        })
                        ->join('sh_strand',function($join){
                            $join->on('sh_block.strandid','=','sh_strand.id');
                            $join->where('sh_strand.deleted',0);
                        })
                        ->where('sh_sectionblockassignment.deleted',0)
                        ->select(
                            'syid',
                            'sectionid',
                            'strandid',
                            'strandcode'
                        )->get();
    @endphp
    

    <div class="modal fade" id="grade_info" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Student Grade Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>   
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="">Student</label>
                            <input class="form-control form-control-sm" readonly id="student_name_modal">
                        </div>
                    </div>
                   
                    <div class="row mt-3 border-1 p-0">
                        <div class="col-md-12 table-responsive" style="height: 350px">
                            <table class="table table-head-fixed table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="80%">Subject</th>
                                        <th width="10%" class="text-center">Grade</th>
                                        <th width="10%" class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="grade_holder">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6"></div>
                        <div class="col-md-3">
                            <button class="btn btn-info btn-block btn-sm" id="post_grade" >POST ALL GRADES</button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-danger btn-block  btn-sm" id="unpost_grade" >UNPOST ALL GRADES</button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>


    <div class="modal fade" id="subject_list" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Subject List</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                <div class="modal-body pt-2">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="">Grade Level</label>
                            <input class="form-control form-control-sm" readonly id="sl_grade_level">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="">Section</label>
                            <input class="form-control form-control-sm" readonly id="sl_section">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-responsive" style="height: 368px">
                            <table class="table table-sm table-head-fixed table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="15%">Code</th>
                                        <th width="55%">Title</th>
                                        <th width="15%" class="text-center">Status</th>
                                        <th width="15%" class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody id="subject_holder">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <button class="btn btn-primary btn-sm btn-block" id="sl_approve">APPROVE ALL</button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info btn-sm btn-block" id="sl_post">POST ALL</button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-danger btn-sm btn-block" id="sl_unpost">UNPOST ALL</button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="subject_option" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-success p-2">
                   
                </div>
                <div class="modal-body">
                    <div class="row">
                        <button class="btn btn-info col-md-12" id="subj_post">POST SUBJECT</button>
                    </div>
                    <div class="row mt-2">
                        <button class="btn btn-danger col-md-12" id="subj_unpost">UNPOST SUBJECT</button>
                    </div>
                     <hr>
                    <div class="row">
                        <button class="btn btn-warning col-md-12" id="subj_pending">ADD SUBJECT TO PENDING</button>
                    </div>
                    <div class="row mt-2">
                        <button class="btn btn-primary col-md-12" id="subj_approve">APPROVE SUBJECT</button>
                    </div>
                    <hr>
                    <div class="row mt-2">
                        <button class="btn btn-danger col-md-12" data-dismiss="modal">CLOSE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="student_subject_option" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-success p-2">
                </div>
                <div class="modal-body">
                    <div class="row">
                        <button class="btn btn-info col-md-12" id="stud_subj_post">POST SUBJECT</button>
                    </div>
                    <div class="row mt-2">
                        <button class="btn btn-danger col-md-12" id="stud_subj_unpost">UNPOST SUBJECT</button>
                    </div>
                     <hr>
                    <div class="row">
                        <button class="btn btn-warning col-md-12" id="stud_subj_pending">ADD SUBJECT TO PENDING</button>
                    </div>
                    <hr>
                    <div class="row ">
                        <button class="btn btn-danger col-md-12" data-dismiss="modal">CLOSE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="proccess_count_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-success p-2">
                    <h4 class="modal-title" id="proccess_message"></h4>
                </div>
                <div class="modal-body">
                    <div class="progress">
                        <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%" id="progress_bar">
                        </div>
                    </div>
                    <p class="mb-1"><code id="percentage">0%</code></p>
                    <div class="text-right">
                        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="proccess_done" hidden>Done</button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>


    <section class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Grade Summary</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item"><a href="/principalPortalSchedule">Grade Summary</a></li>
            </ol>
            </div>
        </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header bg-primary p-1">
                            <div class="row">
                            </div>
                        </div>
                        <div class="card-body" >
                            <div class="row">
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Grade Level</label>
                                        <select class="form-control select2" id="gradelevel"></select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Section </label>
                                        <select name="section" id="section" class="form-control select2">
                                            <option selected value="" >Select Section</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group" id="strand_holder" hidden>
                                        <label for="">Strand</label>
                                        <select name="strand" id="strand" class="form-control select2">
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                
                                </div>
                                <div class="col-md-2">
                                    <label for="">School Year</label>
                                    <select name="syid" id="syid" class="form-control select2">
                                        @foreach(DB::table('sy')->select('id','sydesc','isactive')->orderBy('sydesc','desc')->get() as $item)
                                            @if($item->isactive == 1)
                                                <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                            @else
                                                <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Semester</label>
                                    <select name="semester" id="semester" class="form-control select2">
                                        @foreach(DB::table('semester')->select('id','semester','isactive')->get() as $item)
                                            @if($item->isactive == 1)
                                                <option value="{{$item->id}}" selected="selected">{{$item->semester}}</option>
                                            @else
                                                <option value="{{$item->id}}">{{$item->semester}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <div class="row master_sheet_option" >
                                <div class="col-md-2">
                                    <button hidden class="btn btn-primary btn-block btn-success btn-sm" id="view_subjects" disabled="disabled">VIEW SUBJECTS</button>
                                </div>
                                <div class="col-md-2">
                                    <button hidden class="btn btn-primary btn-block btn-sm" id="approve_all" disabled="disabled">APPROVE ALL</button>
                                </div>
                                <div class="col-md-2">
                                    <button hidden class="btn btn-info btn-block btn-sm" id="post_all" disabled="disabled">POST ALL</button>
                                </div>
                                <div class="col-md-2">
                                    <button hidden class="btn btn-danger btn-block btn-sm" id="unpost_all" disabled="disabled">UNPOST ALL</button>
                                </div>
                            </div>
                            

                            <div class="row grading_sheet_option" hidden="hidden">
                                <div class="col-md-3">
                                    <div class="form-group">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Section</label>
                                        <select name="grading_sheet_section" id="grading_sheet_section" class="form-control select2">
                                            <option selected value="" >SELECT SECTION</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row grading_sheet_option" hidden="hidden">
                                <div class="col-md-2">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary btn-block btn-sm" id="grading_sheet_print"> <i class="fas fa-print"></i></i> PRINT</button>
                                </div>
                            </div>
                          

                            <div class="row sf9_sheet_option" hidden="hiddens">
                                <div class="col-md-3">x
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Section</label>
                                        <select name="section" id="sf9_sheet_section" class="form-control select2">
                                            <option selected value="" >SELECT SECTION</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row sf9_sheet_option" hidden="hidden">
                                <div class="col-md-2">
                                    <button class="btn btn-primary btn-block btn-sm" id="sf9_sheet__print"> <i class="fas fa-print"></i></i> PRINT</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow">
                        <div class="card-header p-1 bg-primary">
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-0 pt-1">Master Sheet 1</h5>
                                    <div class=" master_sheet_option">
                                        <span class="badge badge-warning">PENDING</span>
                                        <span class="badge badge-primary">APPROVED</span>
                                        <span class="badge badge-info">POSTED</span>
                                        <span class="badge badge-success">SUBMITTED</span>
                                        <span class="badge badge-secondary">NOT SUBMITTED</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                        <select name="quarter" id="quarter" class="form-control select2 ">
                                            <option selected value="" >Select Quarter</option>
                                        </select>
                                </div>
                                <div class="col-md-2">
                                        <button class="btn btn-primary btn-block btn-sm" id="filter" >VIEW MASTER SHEET</button>
                                </div>
                                <div class="col-md-2">
                                    <div class="btn-group btn-block">
                                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle printablebutton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled="disabled">
                                            Printables
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" style="font-size: 14px;">
                                            <button class="dropdown-item" id="print_ms" >
                                                <i class="fas fa-file-pdf"></i> Master Sheet (PDF)
                                            </button>
                                           
                                            <button class="dropdown-item print_ms_excel" id="" data-format="1" >
                                                <i class="fas fa-file-excel"></i>Master Sheet (EXCEL Template 1)
                                            </button>
                                            <button class="dropdown-item print_ms_excel" id="" data-format="2"  >
                                                <i class="fas fa-file-excel"></i> Master Sheet (EXCEL Template 2)
                                            </button>
                                            <button class="dropdown-item print_ms_comp" id="" data-format="2"  >
                                                <i class="fas fa-file-excel"></i> Master Sheet Consolidated
                                            </button>
                                            <button class="dropdown-item print_finalcomp" >
                                                <i class="fas fa-file-excel"></i> Final Composite
                                            </button>
                                            <button class="dropdown-item print_gsa"  >
                                                <i class="fas fa-file-excel"></i> General Scholastic Average
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <table class="table table-bordered table-head-fixed nowrap display table-sm p-0" id="student_list" id="ms_header" width="100%">
                                 {{-- <thead>
                                    <tr >
                                       <th>Student Name</th>
                                        <th class="text-center  align-middle">S1</th>
                                        <th class="text-center align-middle">S2</th>
                                        <th class="text-center align-middle">S3</th>
                                        <th class="text-center align-middle">S4</th>
                                        <th class="text-center align-middle">S5</th>
                                        <th class="text-center align-middle">S6</th>
                                        <th class="text-center align-middle">S7</th>
                                        <th class="text-center align-middle">S8</th>
                                        <th class="text-center align-middle">S9</th>
                                        <th class="text-center align-middle">S10</th>
                                        <th class="text-center align-middle">S11</th>
                                        <th class="text-center align-middle">S12</th>
                                        <th class="text-center align-middle">S13</th>
                                        <th class="text-center align-middle">S14</th>
                                        <th class="text-center align-middle">S15</th>
                                        <th class="text-center align-middle">S16</th>
                                        <th class="text-center align-middle">S17</th>
                                        <th class="text-center align-middle">S18</th>
                                        <th class="text-center align-middle">S19</th>
                                    </tr>
                                </thead> --}}
                            </table>
                            <div class="row mt-2">
                                <div class="col-md-12 text-right"></div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="card shadow">
                        <div class="card-header p-1 bg-primary">
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3"><h5 class="mb-0 pt-1">Grading Sheet</h5></div>
                                <div class="col-md-1"></div>
                                <div class="col-md-4">
                                    <select name="grading_sheet_subject" id="grading_sheet_subject" class="form-control select2">
                                        <option selected value="" >SELECT SUBJECT</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary btn-block  btn-sm" id="grading_sheet_filter">GENERATE GRADING SHEET</button>
                                </div>
                            </div>
                           
                            <hr>
                            
                            <table class="table table-bordered table-head-fixed nowrap display table-sm p-0" id="grading_sheet_table_list" style="width:100%" >
                                <thead>
                                    <th>Student Name</th>
                                    <th class="text-center gs_q1">Q1</th>
                                    <th class="text-center gs_q2">Q2</th>
                                    <th class="text-center gs_q3">Q3</th>
                                    <th class="text-center gs_q4">Q4</th>
                                    <th class="text-center">FINAL GRADE</th>
                                    <th class="text-center">REMARK</th>
                                </thead>
                            </table>

                            <div class=" master_sheet_option mt-2">
                                <button class="btn btn-default float-right ml-2" id="print_con" hidden>
                                    <i class="fas fa-print"></i> PRINT CONSOLIDATED
                                </button>
                                <button class="btn btn-default float-right " id="print_gs" disabled>
                                    <i class="fas fa-print"></i> PRINT GRADING SHEET
                                </button>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footerjavascript')


    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>

    <script>

          $(document).ready(function(){


            var school_setup = @json($schoolinfo);

            var enable_button = true

            if(school_setup.projectsetup == 'offline' &&  school_setup.processsetup == 'hybrid1'){
                    enable_button = false;
            }

            if(enable_button){
                $('#view_subjects').removeAttr('hidden')
                $('#approve_all').removeAttr('hidden')
                $('#post_all').removeAttr('hidden')
                $('#unpost_all').removeAttr('hidden')
            }

            $('.select2').select2()

            var students = [];

            // loaddatatable(students)
            reset_data_table()

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })

            toastr.options = {
                "timeOut": "2000",
            }

            var strand = @json($subj_strand);
            var sections = @json($allsections);
            var all_gradelevel = @json($gradelevel);
            var all_acadprogid = @json($acadprogid);

            update_gradelevel()

            function update_gradelevel(){

                var temp_acad = all_acadprogid.filter(x=>x.syid == $('#syid').val())
                var temp_gradelevel = [];
        
                $.each(temp_acad,function(a,b){
                    var temp_gradelevel_list = all_gradelevel.filter(x=>x.acadprogid == b.acadprogid)
                    $.each(temp_gradelevel_list,function(c,d){
                        temp_gradelevel.push(d)
                    })
                })

                $('#gradelevel').empty()
                $('#gradelevel').append('<option value="">Select Grade Level</option>')
                $("#gradelevel").select2({
                        data: temp_gradelevel,
                        allowClear: true,
                        placeholder: "Select Grade Level",
                })

                $('#section').empty()
                $('#section').append('<option value="">Select Section</option>')
                $("#section").select2({
                        data: [],
                        allowClear: true,
                        placeholder: "Select Section",
                })

            }


            $(document).on('change','#gradelevel',function(){

                $('#print_ms').attr('disabled','disabled')
                $('.print_ms_excel').attr('disabled','disabled')
                $('.printtablebutton').attr('disabled','disabled')

                $('#section').empty()
        

                selectedGradeLevel = $(this).val()
                var temp_sections = sections.filter(x=>x.levelid == selectedGradeLevel && x.syid == $('#syid').val())

                console.log(temp_sections)

                $('#section').empty()
                $('#section').append('<option value="">Select Section</option>')
                $("#section").select2({
                        data: temp_sections,
                        allowClear: true,
                        placeholder: "Select Section",
                })

              
                $('#quarter').empty();
                $('#quarter').append('<option value="">Select Quarter</option>')
                if($(this).val() == 14 || $(this).val() == 15){
                    if($('#semester').val() == 1){
                        $('#quarter').append('<option value="1">1st Quarter</option>')
                        $('#quarter').append('<option value="2">2nd Quarter</option>')
                        $('#quarter').append('<option value="5">Final Rating</option>')
                    }else{
                        $('#quarter').append('<option value="3">3rd Quarter</option>')
                        $('#quarter').append('<option value="4">4th Quarter</option>')
                        $('#quarter').append('<option value="5">Final Rating</option>')
                    }
                }else{
                    $('#quarter').append('<option value="1">1st Quarter</option>')
                    $('#quarter').append('<option value="2">2nd Quarter</option>')
                    $('#quarter').append('<option value="3">3rd Quarter</option>')
                    $('#quarter').append('<option value="4">4th Quarter</option>')
                    $('#quarter').append('<option value="5">Final Rating</option>')
                }

                if($(this).val() == 14 || $(this).val() == 15){
                    $('#strand_holder').removeAttr('hidden')
                }else{
                    $('#strand_holder').attr('hidden','hidden')
                }

            })

            $(document).on('change','#gradelevel, #section',function(){
                $('#print_ms').attr('disabled','disabled')
                $('.print_ms_excel').attr('disabled','disabled')
                $('.printablebutton').attr('disabled','disabled')
                reset_data_table()
                disablebutton()
            })

            $(document).on('change','#syid',function(){
                $('#print_ms').attr('disabled','disabled')
                $('.print_ms_excel').attr('disabled','disabled')
                $('.printablebutton').attr('disabled','disabled')
                $('#section').empty()
                update_gradelevel()
                reset_data_table()
                disablebutton()
            })

            $(document).on('change','#section',function(){
                $('#print_ms').attr('disabled','disabled')
                $('.print_ms_excel').attr('disabled','disabled')
                $('.printablebutton').attr('disabled','disabled')
                var temp_section = $(this).val()
                var temp_syid = $('#syid').val()
                var temp_strand = strand.filter(x=>x.sectionid == temp_section && x.syid == temp_syid)
                $("#strand").empty()
                $.each(temp_strand,function(a,b){
                        b.text = b.strandcode
                        b.id = b.strandid
                })
                $("#strand").select2({
                        data: temp_strand,
                        placeholder: "Select a strand",
                })
            })

            $(document).on('change','#semester',function(){
                $('#print_ms').attr('disabled','disabled')
                $('.print_ms_excel').attr('disabled','disabled')
                $('.printablebutton').attr('disabled','disabled')
                $('#quarter').empty();
                $('#quarter').append('<option value="">Select Quarter</option>')
                if($('#gradelevel').val() == 14 || $('#gradelevel').val() == 15){
                    if($('#semester').val() == 1){
                        $('#quarter').append('<option value="1">1st Quarter</option>')
                        $('#quarter').append('<option value="2">2nd Quarter</option>')
                        $('#quarter').append('<option value="5">Final Rating</option>')
                    }else{
                        $('#quarter').append('<option value="3">3rd Quarter</option>')
                        $('#quarter').append('<option value="4">4th Quarter</option>')
                        $('#quarter').append('<option value="5">Final Rating</option>')
                    }
                }else{
                    $('#quarter').append('<option value="1">1st Quarter</option>')
                    $('#quarter').append('<option value="2">2nd Quarter</option>')
                    $('#quarter').append('<option value="3">3rd Quarter</option>')
                    $('#quarter').append('<option value="4">4th Quarter</option>')
                    $('#quarter').append('<option value="5">Final Rating</option>')
                }
            })


            $(document).on('change','#quarter',function(){
                
                disablebutton()
            })

            function disablebutton(){

                $('#view_subjects').attr('disabled','disabled')
                $('#post_all').attr('disabled','disabled')
                $('#unpost_all').attr('disabled','disabled')
                $('#approve_all').attr('disabled','disabled')
            }

            var public_quarter;

            $(document).on('click','#filter',function(){

                var valid_input = true

                if($('#gradelevel').val() == ''){

                    valid_input = false;
                    Swal.fire({
                        type: 'info',
                        text: "Please select gradelevel!"
                    });

                }
                else if($('#section').val() == ''){

                    valid_input = false;
                    Swal.fire({
                        type: 'info',
                        text: "Please select section!"
                    });

                }
                else if($('#quarter').val() == ''){

                    valid_input = false;
                    Swal.fire({
                        type: 'info',
                        text: "Please select quarter!"
                    });

                }

                if(valid_input){

                    $('#print_ms').removeAttr('disabled','disabled')
                    $('.print_ms_excel').removeAttr('disabled','disabled')
                    $('.printablebutton').removeAttr('disabled','disabled')
                    reset_data_table()
                    var gradelevel = $('#gradelevel').val();
                    var section = $('#section').val();
                    var quarter  = $('#quarter').val(); 
                    var syid  = $('#syid').val(); 
                    var semid  = 1
                    if(gradelevel == 14 || gradelevel == 15){
                        var semid  = $('#semester').val(); 
                    }
                    var strand  = $('#strand').val(); 

                    public_quarter = quarter

                    $.ajax({
                        type:'GET',
                        url:'/posting/grade/getstudents',
                        data:{
                                gradelevel:gradelevel,
                                section:section,
                                quarter:quarter,
                                sy:syid,
                                semid:semid,
                                strand:strand,
                                isSF9:false
                        },
                        success:function(data) {
                            all_data_grading_sheet = data
                            students = data
                            student_count = students.filter(x=>x.student != 'SUBJECTS').length

                            if(student_count > 0){
                                $('#view_subjects').removeAttr('disabled')
                                $('#post_all').removeAttr('disabled')
                                $('#unpost_all').removeAttr('disabled')
                                $('#approve_all').removeAttr('disabled')
                                $('#grading_sheet_filter').removeAttr('disabled')
                                $('#sf9_sheet_filter').removeAttr('disabled')

                                loaddatatable(students)
                            
                                if(gradelevel == 14 || gradelevel == 15){
                                    var grading_sheet_subjects = data.filter(x=>x.student == 'SUBJECTS' )[0].grades.filter(x=>x.subjid != "" && x.semid == semid)
                                }else{
                                    var grading_sheet_subjects = data.filter(x=>x.student == 'SUBJECTS' )[0].grades.filter(x=>x.subjid != "")
                                }
                                
                                $('#grading_sheet_subject').empty()
                                $('#grading_sheet_subject').append('<option value="">SELECT SUBJECT</option>')
                                $.each(grading_sheet_subjects,function(a,b){
                                    $('#grading_sheet_subject').append('<option value="'+b.subjid+'"> <span style="color:gray"">'+b.subjdesc+'</span> - '+b.subjtitle+'</option>')
                                })
                            }
                            else{
                                students = []
                                loaddatatable(students)
                                Swal.fire({
                                    type: 'info',
                                    text: "No Enrolled Students!"
                                });
                                $('#view_subjects').attr('disabled','disabled')
                                $('#post_all').attr('disabled','disabled')
                                $('#unpost_all').attr('disabled','disabled')
                                $('#approve_all').attr('disabled','disabled')
                                $('#grading_sheet_filter').attr('disabled','disabled')
                                $('#sf9_sheet_filter').attr('disabled','disabled')
                            }
                        }
                    })
                }
            })

            function reset_data_table(){

                var temp_reset_data = []

                $("#sf9_sheet_table_list").DataTable({
                            destroy: true,
                            data:temp_reset_data,
                            "scrollX": true,
                            "columnDefs": [
                                {"title":"Student Name","targets":0},
                                {"title":"Q1","targets":1},
                                {"title":"Q2","targets":2},
                                {"title":"Q3","targets":3},
                                {"title":"Q4","targets":4},
                                {"title":"Final Rating","targets":5},
                                {"title":"Remarks","targets":6},
                            ]
                        })
                        
                $("#grading_sheet_table_list").DataTable({
                    destroy: true,
                    data:temp_reset_data,
                    "scrollX": true,
                    "columnDefs": [
                        {"title":"Student Name","targets":0},
                        {"title":"Q1","targets":1},
                        {"title":"Q2","targets":2},
                        {"title":"Q3","targets":3},
                        {"title":"Q4","targets":4},
                        {"title":"Final Rating","targets":5},
                        {"title":"Remarks","targets":6},
                    ]
                })

                if(withfilter){
                    var table = $('#student_list').DataTable();
                    table.destroy();
                    $('#student_list').empty()
                }
                
         
                var tempcolumns = []
                var tempcoldef = []

                for(var x = 0; x <= 20 ; x++){
                    tempcolumns.push({"title":"Col"+x,"targets":x})
                    tempcolumns.push({"data":null})
                }

                $("#student_list").DataTable({
                    data:temp_reset_data,
                    destroy: true,
                    scrollX: true,
                    order: [[ 0, "desc" ]],
                    columns:tempcolumns,
                    columnDefs:tempcoldef
                })
                withfilter = false

                disablebutton()
                $('#sf9_grade_table').empty()
                $('#sf9_student_name').empty()
                $('#grading_sheet_subject').empty()
                
                $('#grading_sheet_subject').append('<option value="">SELECT SUBJECT</option>')
                $('#sf9_student_name').append('<option value="">SELECT STUDENT</option>')

            }

            var withfilter = false

            function loaddatatable(data){

                withfilter = true
                var header = data[0];
                var temp_levelid = $('#gradelevel').val()
                if(temp_levelid == 14 || temp_levelid == 15){
                    var temp_strand = $('#strand').val()
                    var data = data.filter(x => x.student != 'SUBJECTS' && x.strand == temp_strand)
                }else{
                    var data = data.filter(x => x.student != 'SUBJECTS')
                }
               
                
                if(data.length == 0){

                    var table = $('#student_list').DataTable();
                    table.destroy();
                    $('#student_list').empty()

                    var tempcolumns = []
                    var tempcoldef = []

                    for(var x = 0; x <= 20 ; x++){
                        tempcolumns.push({"title":"","targets":x})
                        tempcolumns.push({"data":null})
                    }

                    $("#student_list").DataTable({
                        data:data,
                        destroy: true,
                        scrollX: true,
                        order: [[ 0, "desc" ]],
                        columns:tempcolumns,
                        columnDefs:tempcoldef
                    })
                        
                }
                else{


                    // $('#ms_header').empty()
                    var subjheader = header.grades.filter(x=>x.subjid != '')

                    var tempcolumns = [
                        { "data": "gender" },
                        { "data": "student" }
                    ]

                    var tempcoldef = [
                        { 
                            "title": "Gender" ,
                            "targets": 0,
                        },
                        { 
                            "title": "Student" ,
                            "targets": 1,
                        }
                    ]

                    $.each(subjheader,function(a,b){
                        tempcolumns.push({
                            "data":null
                        })
                    })

                        
                    var table = $('#student_list').DataTable();
                    table.destroy();
                    $('#student_list').empty()
                  

                    var temptarget = 2;
                    $.each(subjheader,function(a,b){
                        tempcoldef.push({
                            "title":b.subjcode,
                            "targets": temptarget,
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td).text(rowData.grades[a].qg)
                                    if(rowData.grades[a].status == 4){
                                        $(td).addClass('bg-info text-center')
                                    }
                                    else if(rowData.grades[a].status == 1){
                                        $(td).addClass('bg-success text-center')
                                    }
                                    else if(rowData.grades[a].status == 2){
                                        $(td).addClass('bg-primary text-center')
                                    }
                                    else if(rowData.grades[a].status == 3){
                                        $(td).addClass('bg-warning text-center')
                                    }
                                    else if(rowData.grades[a].status == 5){
                                        $(td).addClass('bg-danger text-center')
                                    }
                                    else if(rowData.grades[a].status == 6){
                                        $(td).addClass('bg-indigo text-center')
                                    }
                                    else{
                                        $(td).text(null)
                                        $(td).addClass('bg-secondary text-center')
                                    }
                                    $(td).addClass('text-center')
                            } 
                        })
                        temptarget += 1
                    })

                    // console.log(tempcoldef)

                    // return "sdfsdf";
                    // return false;
                

                    $("#student_list").DataTable({
                        destroy: true,
                        data:data,
                        scrollX: true,
                        autoWidth:false,
                        columns:tempcolumns,
                        order: [[ 0, "desc" ]],
                        columnDefs:tempcoldef
                        // columns: [
                        //             { "data": "gender" },
                        //             { "data": "student" },
                        //             { "data": null },
                        //             { "data": null },
                        //             { "data": null },
                        //             { "data": null },
                        //             { "data": null },
                        //             { "data": null },
                        //             { "data": null },
                        //             { "data": null },
                        //             { "data": null },
                        //             { "data": null },
                        //             { "data": null },
                        //             { "data": null },
                        //             { "data": null },
                        //             { "data": null },
                        //             { "data": null },
                        //             { "data": null },
                        //             { "data": null },
                        //             { "data": null },
                        //     ],
                            

                        // "columnDefs": [
                        //     { "title": "STUDENT", 
                        //         "targets": 0,
                        //         'createdCell':  function (td, cellData, rowData, row, col) {
                        //             $(td)[0].innerHTML = '<span class="badge badge-success mr-2" style="font-size:.5rem !important"> '+rowData.gender+'</span>'+rowData.student
                                    
                        //         } 
                            
                        //     },
                        //     { "title": header.grades[0].subjdesc, "targets": 1,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {

                        //             $(td).removeAttr('class')

                        //             if(rowData.grades[0].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[0].qg)
                        //             }
                        //             else if(rowData.grades[0].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[0].qg)

                        //             }
                        //             else if(rowData.grades[0].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[0].qg)

                        //             }
                        //             else if(rowData.grades[0].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[0].qg)

                        //             }
                        //             else if(rowData.grades[0].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[0].qg)
                        //             }
                        //             else if(rowData.grades[0].status == 6){
                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[0].qg)
                        //             }
                        //             else{
                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')
                        //             }

                        //             $(td).addClass('text-center')
                        //         } 
                        //     },
                        //     { "title": header.grades[1].subjdesc, "targets": 2,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {
                        //             if(rowData.grades[1].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[1].qg)
                        //             }
                        //             else if(rowData.grades[1].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[1].qg)
                        //             }
                        //             else if(rowData.grades[1].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[1].qg)
                        //             }
                        //             else if(rowData.grades[1].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[1].qg)
                        //             }
                        //             else if(rowData.grades[1].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[1].qg)
                        //             }
                        //             else if(rowData.grades[1].status == 6){

                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[1].qg)
                        //             }
                        //             else{
                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')
                        //             }
                        //             $(td).addClass('text-center')
                        //         } 
                        //     },
                        //     { "title": header.grades[2].subjdesc, "targets": 3,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {

                        //             if(rowData.grades[2].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[2].qg)
                        //             }
                        //             else if(rowData.grades[2].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[2].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[2].qg

                        //             }
                        //             else if(rowData.grades[2].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[2].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[2].qg

                        //             }
                        //             else if(rowData.grades[2].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[2].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[2].qg

                        //             } 
                        //             else if(rowData.grades[2].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[2].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[2].qg

                        //             }
                        //             else if(rowData.grades[2].status == 6){

                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[2].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[0].qg

                        //             }
                        //             else{
                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')
                        //             }

                        //             $(td).addClass('text-center')
                        //         } 
                        //     },
                        //     { "title": header.grades[3].subjdesc, "targets": 4,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {

                                   
                        //             if(rowData.grades[3].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[3].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[3].qg

                        //             }
                        //             else if(rowData.grades[3].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[3].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[3].qg

                        //             }
                        //             else if(rowData.grades[3].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[3].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[3].qg

                        //             }
                        //             else if(rowData.grades[3].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[3].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[3].qg

                        //             }
                        //             else if(rowData.grades[3].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[3].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[3].qg

                        //             }
                        //             else if(rowData.grades[3].status == 6){

                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[3].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[0].qg

                        //             }
                        //             else{

                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')
                        //             }


                        //             $(td).addClass('text-center')
                        //         } 
                        //     },
                        //     { "title": header.grades[4].subjdesc, "targets": 5,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {

                        //             if(rowData.grades[4].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[4].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[4].qg

                        //             }
                        //             else if(rowData.grades[4].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[4].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[4].qg

                        //             }
                        //             else if(rowData.grades[4].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[4].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[4].qg

                        //             }
                        //             else if(rowData.grades[4].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[4].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[4].qg

                        //             }
                        //             else if(rowData.grades[4].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[4].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[4].qg

                        //             }
                        //             else if(rowData.grades[4].status == 6){
                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[4].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[0].qg
                        //             }
                        //             else{

                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')
                        //             }


                        //             $(td).addClass('text-center')
                        //         } 
                        //     },
                        //     { "title": header.grades[5].subjdesc, "targets": 6,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {

                        //             if(rowData.grades[5].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[5].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[5].qg

                        //             }
                        //             else if(rowData.grades[5].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[5].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[5].qg

                        //             }
                        //             else if(rowData.grades[5].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[5].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[5].qg

                        //             }
                        //             else if(rowData.grades[5].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[5].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[5].qg

                        //             }
                        //             else if(rowData.grades[5].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[5].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[5].qg

                        //             }
                        //             else if(rowData.grades[5].status == 6){
                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[5].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[0].qg
                        //             }
                        //             else{

                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')
                        //             }

                        //             $(td).addClass('text-center')
                        //         } 
                        //     },
                        //     { "title": header.grades[6].subjdesc, "targets": 7,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {

                        //             if(rowData.grades[6].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[6].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[6].qg

                        //             }
                        //             else if(rowData.grades[6].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[6].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[6].qg

                        //             }
                        //             else if(rowData.grades[6].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[6].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[6].qg

                        //             }
                        //             else if(rowData.grades[6].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[6].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[6].qg

                        //             }
                        //             else if(rowData.grades[6].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[6].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[6].qg

                        //             }
                        //             else if(rowData.grades[6].status == 6){
                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[6].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[0].qg
                        //             }
                        //             else{

                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')
                        //             }

                        //             $(td).addClass('text-center')
                        //         } 
                        //     },
                        //     { "title": header.grades[7].subjdesc, "targets": 8,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {

                        //             if(rowData.grades[7].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[7].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[7].qg

                        //             }
                        //             else if(rowData.grades[7].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[7].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[7].qg

                        //             }
                        //             else if(rowData.grades[7].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[7].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[7].qg

                        //             }
                        //             else if(rowData.grades[7].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[7].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[7].qg

                        //             }
                        //             else if(rowData.grades[7].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[7].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[7].qg

                        //             }
                        //             else if(rowData.grades[7].status == 6){
                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[7].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[0].qg
                        //             }
                        //             else{

                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')
                        //             }

                        //             $(td).addClass('text-center')
                        //         } 
                        //     },
                        //     { "title": header.grades[8].subjdesc, "targets": 9,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {

                        //             if(rowData.grades[8].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[8].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[8].qg

                        //             }
                        //             else if(rowData.grades[8].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[8].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[8].qg

                        //             }
                        //             else if(rowData.grades[8].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[8].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[8].qg

                        //             }
                        //             else if(rowData.grades[8].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[8].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[8].qg

                        //             }
                        //             else if(rowData.grades[8].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[8].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[8].qg

                        //             }
                        //             else if(rowData.grades[8].status == 6){
                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[8].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[0].qg
                        //             }
                        //             else{

                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')
                        //             }


                        //             $(td).addClass('text-center')
                        //         } 
                        //     },
                        //     { "title": header.grades[9].subjdesc, "targets": 10,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {

                        //             if(rowData.grades[9].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[9].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[9].qg

                        //             }
                        //             else if(rowData.grades[9].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[9].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[9].qg

                        //             }
                        //             else if(rowData.grades[9].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[9].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[9].qg

                        //             }
                        //             else if(rowData.grades[9].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[9].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[9].qg

                        //             }
                        //             else if(rowData.grades[9].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[9].qg)
                        //                 //  $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[9].qg

                        //             }
                        //             else if(rowData.grades[9].status == 6){
                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[9].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[0].qg
                        //             }
                        //             else{

                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')
                        //             }


                        //             $(td).addClass('text-center')
                        //         } 
                        //     },
                        //     { "title": header.grades[10].subjdesc, "targets": 11,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {

                        //             if(rowData.grades[10].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[10].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[10].qg

                        //             }
                        //             else if(rowData.grades[10].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[10].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[10].qg

                        //             }
                        //             else if(rowData.grades[10].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[10].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[10].qg

                        //             }
                        //             else if(rowData.grades[10].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[10].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[10].qg

                        //             }
                        //             else if(rowData.grades[10].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[10].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[10].qg

                        //             }
                        //             else if(rowData.grades[10].status == 6){
                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[10].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[0].qg
                        //             }
                        //             else{

                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')
                        //             }


                        //             $(td).addClass('text-center')

                        //         } 
                        //     },
                        //     { "title": header.grades[11].subjdesc, "targets": 12,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {

                        //             if(rowData.grades[11].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[11].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[11].qg

                        //             }
                        //             else if(rowData.grades[11].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[11].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[11].qg

                        //             }
                        //             else if(rowData.grades[11].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[11].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[11].qg

                        //             }
                        //             else if(rowData.grades[11].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[11].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[11].qg

                        //             }
                        //             else if(rowData.grades[11].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[11].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[11].qg

                        //             }
                        //             else if(rowData.grades[11].status == 6){
                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[11].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[0].qg
                        //             }
                        //             else{

                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')
                        //             }


                        //             $(td).addClass('text-center')

                        //         } 
                        //     },
                        //     { "title": header.grades[12].subjdesc, "targets": 13,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {
                                    

                        //             if(rowData.grades[12].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[12].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[12].qg

                        //             }
                        //             else if(rowData.grades[12].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[12].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[12].qg

                        //             }
                        //             else if(rowData.grades[12].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[12].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[12].qg

                        //             }
                        //             else if(rowData.grades[12].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[12].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[12].qg

                        //             }
                        //             else if(rowData.grades[12].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[12].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[12].qg

                        //             }
                        //             else if(rowData.grades[12].status == 6){
                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[12].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[0].qg
                        //             }
                        //             else{

                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')
                        //             }


                        //             $(td).addClass('text-center')
                        //         } 
                        //     },
                        //     { "title": header.grades[13].subjdesc, "targets": 14,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {
                        //             if(rowData.grades[13].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[13].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[13].qg

                        //             }
                        //             else if(rowData.grades[13].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[13].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[13].qg

                        //             }
                        //             else if(rowData.grades[13].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[13].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[13].qg

                        //             }
                        //             else if(rowData.grades[13].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[13].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[13].qg

                        //             }
                        //             else if(rowData.grades[13].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[13].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[13].qg

                        //             }
                        //             else if(rowData.grades[13].status == 6){
                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[13].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[0].qg
                        //             }
                        //             else{

                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')
                        //             }


                        //             $(td).addClass('text-center')
                        //         } 
                        //     },
                        //     { "title": header.grades[14].subjdesc, "targets": 15,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {


                        //             if(rowData.grades[14].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[14].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[14].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[14].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[14].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[14].status == 6){
                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[0].qg
                        //             }
                        //             else{

                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')

                        //             }


                        //             $(td).addClass('text-center')
                        //         } 
                        //     },
                        //     { "title": header.grades[15].subjdesc, "targets": 16,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {


                        //             if(rowData.grades[15].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[15].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[15].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[15].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[15].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[15].status == 6){
                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[15].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[0].qg
                        //             }
                        //             else{

                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')

                        //             }


                        //             $(td).addClass('text-center')
                        //         } 
                        //     },
                        //     { "title": header.grades[16].subjdesc, "targets": 17,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {


                        //             if(rowData.grades[16].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[16].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[16].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[16].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[16].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[16].status == 6){
                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[16].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[0].qg
                        //             }
                        //             else{

                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')

                        //             }


                        //             $(td).addClass('text-center')
                        //         } 
                        //     },
                        //     { "title": header.grades[17].subjdesc, "targets": 18,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {


                        //             if(rowData.grades[17].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[17].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[17].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[17].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[17].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[17].status == 6){
                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[17].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[0].qg
                        //             }
                        //             else{

                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')

                        //             }


                        //             $(td).addClass('text-center')
                        //         } 
                        //     },
                        //     { "title": header.grades[18].subjdesc, "targets": 19,
                        //         'orderable': false, 
                        //         'createdCell':  function (td, cellData, rowData, row, col) {


                        //             if(rowData.grades[18].status == 4){
                        //                 $(td).addClass('bg-info text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[18].status == 1){
                        //                 $(td).addClass('bg-success text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[18].status == 2){
                        //                 $(td).addClass('bg-primary text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[18].status == 3){
                        //                 $(td).addClass('bg-warning text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[18].status == 5){
                        //                 $(td).addClass('bg-danger text-center')
                        //                 $(td).text(rowData.grades[14].qg)
                        //                 $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[14].qg

                        //             }
                        //             else if(rowData.grades[18].status == 6){
                        //                 $(td).addClass('bg-indigo text-center')
                        //                 $(td).text(rowData.grades[18].qg)
                        //                 // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[0].qg
                        //             }
                        //             else{

                        //                 $(td).text(null)
                        //                 $(td).addClass('bg-secondary text-center')

                        //             }


                        //             $(td).addClass('text-center')
                        //         } 
                        //     },
                            // { "title": header.grades[19].subjdesc, "targets": 20,
                            //     'createdCell':  function (td, cellData, rowData, row, col) {


                            //         if(rowData.grades[19].status == 4){
                            //             $(td).addClass('bg-info text-center')
                            //             $(td).text(rowData.grades[14].qg)
                            //             // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[14].qg

                            //         }
                            //         else if(rowData.grades[19].status == 1){
                            //             $(td).addClass('bg-success text-center')
                            //             $(td).text(rowData.grades[14].qg)
                            //             // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[14].qg

                            //         }
                            //         else if(rowData.grades[19].status == 2){
                            //             $(td).addClass('bg-primary text-center')
                            //             $(td).text(rowData.grades[14].qg)
                            //             // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[14].qg

                            //         }
                            //         else if(rowData.grades[19].status == 3){
                            //             $(td).addClass('bg-warning text-center')
                            //             $(td).text(rowData.grades[14].qg)
                            //             // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[14].qg

                            //         }
                            //         else if(rowData.grades[19].status == 5){
                            //             $(td).addClass('bg-danger text-center')
                            //             $(td).text(rowData.grades[14].qg)
                            //             $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[14].qg

                            //         }
                            //         else if(rowData.grades[19].status == 6){
                            //             $(td).addClass('bg-indigo text-center')
                            //             $(td).text(rowData.grades[19].qg)
                            //             // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[0].qg
                            //         }
                            //         else{

                            //             $(td).text(null)
                            //             $(td).addClass('bg-secondary text-center')

                            //         }


                            //         $(td).addClass('text-center')
                            //     } 
                            // },
                            
                        // ]
                        
                });

                }

        
            }

            var select_student;

            function update_student_detail_option(){

                var data = students.filter(x => x.studid == select_student)
                var count_posted = data[0].grades.filter(x=>x.status != 0 && x.status == 4).length
                var count_unposted = data[0].grades.filter(x=>x.status != 0 && x.status == 5).length
                var subj_count = data[0].grades.filter(x=>x.status != 0).length

                $('#post_grade').removeAttr('disabled')
                $('#unpost_grade').removeAttr('disabled')

                if(count_posted == subj_count){
                    $('#post_grade').attr('disabled','disbled')
                }
                else if(count_unposted == subj_count){
                    $('#unpost_grade').attr('disabled','disbled')
                }

            }


            $(document).on('click','.view_info',function(){
                $('#grade_info').modal()
                select_student = $(this).attr('data-studid')
                var data = students.filter(x => x.studid == select_student)
                update_student_detail_option()
                $('#student_name_modal').val(data[0].student)
                load_grade_detail(data)
            })

            $(document).on('click','#view_subjects',function(){
                $('#subject_list').modal()
                subject_info_table()
                $('.subj_tr').removeClass('bg-secondary')
            })


            $(document).on('click','.view_pl',function(){

                var gradelevel = $('#gradelevel').val();
                var section = $('#section').val();
                var quarter  = $('#quarter').val(); 
                var syid  = $('#syid').val(); 
                var semid  = 1
                if(gradelevel == 14 || gradelevel == 15){
                    var semid  = $('#semester').val(); 
                }
                var strand  = $('#strand').val(); 
                var  tempsubjid = $(this).attr('data-subjid')

                if(section == null){
                    Swal.fire({
                            type: 'info',
                            title: 'Something went wrong!',
                            text: 'Please reload the page',
                            showConfirmButton: false,
                            timer: 1500
                    });
                }
                else{
                    window.open("/grades/report/mastersheet/lp?gradelevel="+gradelevel+"&section="+section+"&quarter="+quarter+"&sy="+syid+"&semid="+semid+"&strand="+strand+"&subjid="+tempsubjid);
                }
            })

            function subject_info_table(){
                var temp_data = students.filter( x => x.student == 'SUBJECTS')
                $('#sl_grade_level').val($('#gradelevel option:selected').text())
                $('#sl_section').val($('#section option:selected').text())
                $('#subject_holder').empty()
                $.each(temp_data[0].grades,function(a,b){
                    var bg = ''
                    if(selected_subj == b.gsdid){
                        bg = 'bg-secondary'
                    }

                    if(b.subjid != ""){

                        console.log(b)

                        var proflvl =  `<td class="text-center">
                                            <button class="btn btn-default view_pl p-1" data-subjid="`+b.subjid+`" style="font-size:.8rem !important">
                                                <i class="fas fa-file-excel"></i> PL & GSA
                                            </button>
                                        </td>`

                        if(b.isCon == 1){
                            proflvl = '<td></td>'
                        }

                        if(b.isCon == 1){
                            $('#subject_holder').append('<tr ><td class="align-middle" >'+b.subjdesc+'</td><td>'+b.subjtitle+'</td><td class="text-center align-middle"></td>'+proflvl+'</tr>')
                        }else{
                            if(b.status == 0){
                                $('#subject_holder').append('<tr><td class="align-middle">'+b.subjdesc+'</td><td>'+b.subjtitle+'</td><td class="text-center align-middle"><span class="badge badge-secondary d-block ">NOT SUBMITTED</span></td>'+proflvl+'</tr>')
                            }
                            else if(b.status == 1){
                                $('#subject_holder').append('<tr><td class=" align-middle">'+b.subjdesc+'</td><td  data-id="'+b.gsdid+'" class="subj_tr subject_option '+bg+'">'+b.subjtitle+'</td><td class="text-center  align-middle"><span class="badge badge-success d-block"> SUBMITTED</span></td>'+proflvl+'</tr>')
                            }
                            else if(b.status == 4){
                                $('#subject_holder').append('<tr><td class=" align-middle"">'+b.subjdesc+'</td><td  data-id="'+b.gsdid+'" class="subj_tr subject_option '+bg+'">'+b.subjtitle+'</td><td class="text-center align-middle"><span class="badge badge-info d-block"> POSTED</span></td>'+proflvl+'</tr>')
                            }
                            else if(b.status == 3){
                                $('#subject_holder').append('<tr><td class=" align-middle"">'+b.subjdesc+'</td><td  data-id="'+b.gsdid+'" class="subj_tr subject_option '+bg+'">'+b.subjtitle+'</td><td class="text-center align-middle"><span class="badge badge-warning d-block"> PENDING</span></td>'+proflvl+'</tr>')
                            }
                            else if(b.status == 2){
                                $('#subject_holder').append('<tr><td class=" align-middle"">'+b.subjdesc+'</td><td  data-id="'+b.gsdid+'" class="subj_tr subject_option '+bg+'">'+b.subjtitle+'</td><td class="text-center align-middle"><span class="badge badge-primary d-block"> APPROVED</span></td>'+proflvl+'</tr>')
                            }
                            else if(b.status == 5){
                                $('#subject_holder').append('<tr><td class="align-middle" >'+b.subjdesc+'</td><td  data-id="'+b.gsdid+'" class="subj_tr subject_option '+bg+'">'+b.subjtitle+'</td><td class="text-center align-middle"><span class="badge badge-danger d-block"> UNPOSTED</span></td>'+proflvl+'</tr>')
                            }

                        }

                        // else if(b.status == 6){
                        //     $('#subject_holder').append('<tr data-id="'+b.gsdid+'" class="subj_tr '+bg+'"><td class="align-middle" >'+b.subjdesc+'</td><td>'+b.subjtitle+'</td><td class="text-center"><span class="badge badge-indigo d-block"> CONFLICT</span></td></tr>')
                        // }
                    }
                    
                })
            }

            function default_subj_option(){

                $('#subj_unpost').removeAttr('disabled')
                $('#subj_approve').removeAttr('disabled')
                $('#subj_pending').removeAttr('disabled')
                $('#subj_post').removeAttr('disabled')

            }

            function check_subj_option(){
                var checkStatus = students.filter(x=>x.student == 'SUBJECTS')[0].grades.filter(x=>x.gsdid == selected_subj)[0].status
                if(checkStatus == 5){
                    $('#subj_unpost').attr('disabled','disabled')
                    $('#subj_approve').attr('disabled','disabled')
                }
                else if(checkStatus == 4){
                    $('#subj_post').attr('disabled','disabled')
                    $('#subj_approve').attr('disabled','disabled')
                    $('#subj_pending').attr('disabled','disabled')
                }
                else if(checkStatus == 1){
                    $('#subj_unpost').attr('disabled','disabled')
                }
                else if(checkStatus == 2){
                    $('#subj_approve').attr('disabled','disabled')
                    $('#subj_unpost').attr('disabled','disabled')
                }
                else if(checkStatus == 3){
                    $('#subj_approve').attr('disabled','disabled')
                    $('#subj_pending').attr('disabled','disabled')
                    $('#subj_unpost').attr('disabled','disabled')
                    $('#subj_post').attr('disabled','disabled')
                }
            }



            $(document).on('click','.subject_option',function(){

                default_subj_option()
                selected_subj = $(this).attr('data-id')
                check_subj_option()
                $('#subject_option').modal()
                $('.subj_tr').removeClass('bg-secondary')
                $('.subj_tr[data-id="'+selected_subj+'"]').addClass('bg-secondary')

            })

            var selected_subj;

            $(document).on('click','#subj_pending',function(){
                reset_progress_bar()
                check_item_count_by_subject(3)
                Swal.fire({
                    html:
                        'Are you sure you want <br>' +
                        'to add grades to pending for this subject?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, add grades to pending!'
                }).then((result) => {
                    if (result.value) {
                        pending_subject_grade_ajax()
                    }
                })
            })

            $(document).on('click','#subj_ecr',function(){
                window.open("/principalPortalGradeInformation/"+selected_subj);
            })

            $(document).on('click','#subj_approve',function(){
                reset_progress_bar()
                check_item_count_by_subject(2)
                Swal.fire({
                    html:
                        'Are you sure you want <br>' +
                        'to approve grades for this subject?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve grades!'
                }).then((result) => {
                    if (result.value) {
                        approve_subject_grade_ajax()
                    }
                })

            })

            $(document).on('click','#subj_post',function(){
                reset_progress_bar()
                check_item_count_by_subject(4)
                Swal.fire({
                    html:
                        'Are you sure you want <br>' +
                        'to post grades for this subject?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, post grades!'
                }).then((result) => {
                    if (result.value) {
                        post_subject_grade_ajax()
                    }
                })
            })

            $(document).on('click','#subj_unpost',function(){
                reset_progress_bar()
                check_item_count_by_subject(5)
                Swal.fire({
                    html:
                        'Are you sure you want <br>' +
                        'to unpost grades for this subject?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, unpost grades!'
                }).then((result) => {
                    if (result.value) {
                        unpost_subject_grade_ajax()
                    }
                })
            })

            function check_item_count_by_subject(status){

                var filter_subject  = students.filter(x => x.student == 'SUBJECTS')[0].grades
                var gradeid = filter_subject.findIndex(x => x.gsdid == selected_subj)
                var new_status = status

                students.filter(function(x){
                    if(x.grades[gradeid].gdid != ""){
                        if(x.student != 'SUBJECT' && x.grades[gradeid].status != new_status){
                            temp_item_count += 1;
                        }else if(x.student == 'SUBJECTS'){
                            temp_item_count += 1;
                        }

                    }
                })

            }

            //all subjects
            function post_subject_grade(){
                var temp_students = students.filter(x => x.student == 'SUBJECTS' )
                selected_subj = null
                $.each(temp_students[0].grades, function(a,b){
                    if(b.subjid != ""){
                        selected_subj = b.gsdid
                        post_subject_grade_ajax()
                    }
                })
            }

            //all subjects
            function unpost_subject_grade(){
                var temp_students = students.filter(x => x.student == 'SUBJECTS')
                $.each(temp_students[0].grades, function(a,b){
                    if(b.subjid != ""){
                        selected_subj = b.gsdid
                        unpost_subject_grade_ajax()
                    }
                })
            }

            function reset_progress_bar(){
                temp_percentage = 0;
                temp_proccess_count = 0;
                temp_item_count = 0;
                $('#progress_bar').css('width','0'+'%')
                $('#percentage').text("0%")
                $('#proccess_done').attr('hidden','hidden')
            }


            
            var temp_percentage
            var temp_item_count


            
           

            $(document).on('click','.view_info',function(){

                $('#grade_info').modal()

                select_student = $(this).attr('data-studid');

                var data = students.filter(x => x.studid == select_student)

                $('#student_name_modal').val(data[0].student)

                load_grade_detail(data)

            })

            function load_grade_detail(data){
                $('#grade_holder').empty()
                var arrayID = students.findIndex(x => x.studid == data[0].studid)
                $.each(data[0].grades,function(a,b){
                  
                    var bg = ''
                    if(selected_subj == b.gdid){
                        bg = 'bg-secondary'
                    }

                    if(b.subjid != ""){
                        var status_string = '';
                        var this_class = 'stud_subj_tr'
                        var qg = ''
                        if(b.status == 1){
                            var status_string = '<span class="badge badge-success d-block">SUBMITTED</span>';
                        }
                        else  if(b.status == 2){
                            var status_string = '<span class="badge badge-primary d-block">APPROVED</span>';
                        }
                        else  if(b.status == 3){
                            var status_string = '<span class="badge badge-warning d-block">PENDING</span>';
                        }
                        else  if(b.status == 4){
                            var status_string = '<span class="badge badge-info d-block">POSTED</span>';
                        }
                        else  if(b.status == 5){
                            var status_string = '<span class="badge badge-danger d-block">UNPOSTED</span>';
                        }
                        else  if(b.status == 6){
                            var status_string = '<span class="badge bg-indigo d-block">CONFLICT</span>';
                        }else{
                            var status_string = '<span class="badge badge-secondary d-block">NOT SUBMITTED</span>';
                            this_class = ''
                        }

                        if(b.status != 0 ){
                            qg = b.qg
                        }

                        $('#grade_holder').append('<tr class="'+this_class+' '+bg+'" data-id="'+b.gdid+'" data-studid="'+data[0].studid+'" data-index="'+a+'" data-studindex="'+arrayID+'"  data-tid="'+b.teacherid+'"><td>'+b.subjdesc+'</td><td class="text-center">'+qg+'</td><td class="text-center">'+status_string+'</td></tr>')
                    }
                })
            }

            var temp_proccess_count = 0;
            var temp_item_count = 0;
            var selected_subjects 

            function post_subject_grade_ajax(with_notification){
                var temp_selected_subj = selected_subj
                var temp_student = students.filter(x => x.student != 'SUBJECTS')
                var arrayID = students.findIndex(x => x.student == 'SUBJECTS')
                var filter_subject  = students.filter(x => x.student == 'SUBJECTS')[0].grades
                var gradeid = filter_subject.findIndex(x => x.gsdid == temp_selected_subj)
                var teacherid = filter_subject[gradeid].teacherid
                $.ajax({
                    type:'GET',
                    url:'/posting/grade/subject/post',
                    data:{
                        gdid:temp_selected_subj,
                        quarter:public_quarter,
                        teacherid:teacherid
                    },
                    success:function(data) {
                        students[arrayID].grades[gradeid].status = 4
                        default_subj_option()
                        check_subj_option()
                        toastr.info(students[arrayID].grades[gradeid].subjcode+' grades Posted')
                        $.each(temp_student,function(a,b){
                            temp_student[a].grades[gradeid].status = 4
                        })
                        update_student_detail_option()
                        loaddatatable(students)
                        subject_info_table()
                    },
                })
            }

            function unpost_subject_grade_ajax(){
                var temp_selected_subj = selected_subj
                var temp_student = students.filter(x => x.student != 'SUBJECTS')
                var arrayID = students.findIndex(x => x.student == 'SUBJECTS')
                var filter_subject  = students.filter(x => x.student == 'SUBJECTS')[0].grades
                var gradeid = filter_subject.findIndex(x => x.gsdid == temp_selected_subj)
                var teacherid = filter_subject[gradeid].teacherid
                $.ajax({
                    type:'GET',
                    url:'/posting/grade/subject/unpost',
                    data:{
                            gdid:temp_selected_subj,
                            quarter:public_quarter,
                            teacherid:teacherid
                    },
                    success:function(data) {
                        students[arrayID].grades[gradeid].status = 2
                        default_subj_option()
                        check_subj_option()
                        toastr.error( students[arrayID].grades[gradeid].subjcode+' grades Unposted!')
                        $.each(temp_student,function(a,b){
                            temp_student[a].grades[gradeid].status = 2
                        })
                        update_student_detail_option()
                        loaddatatable(students)
                        subject_info_table()
                    },
                })
            }

            function pending_subject_grade_ajax(){
                var temp_selected_subj = selected_subj
                var temp_student = students.filter(x => x.student != 'SUBJECTS')
                var arrayID = students.findIndex(x => x.student == 'SUBJECTS')
                var filter_subject  = students.filter(x => x.student == 'SUBJECTS')[0].grades
                var gradeid = filter_subject.findIndex(x => x.gsdid == temp_selected_subj)
                var teacherid = filter_subject[gradeid].teacherid
                $.ajax({
                    type:'GET',
                    url:'/posting/grade/subject/pending',
                    data:{
                            gdid:temp_selected_subj,
                            quarter:public_quarter,
                            teacherid:teacherid
                    },
                    success:function(data) {
                        students[arrayID].grades[gradeid].status = 3
                        default_subj_option()
                        check_subj_option()
                        toastr.warning(students[arrayID].grades[gradeid].subjcode+ ' grades added to pending!')
                        $.each(temp_student,function(a,b){
                            temp_student[a].grades[gradeid].status = 3
                        })
                        update_student_detail_option()
                        loaddatatable(students)
                        subject_info_table()
                    },
                })
            }

            function approve_subject_grade_ajax(){
                var temp_selected_subj = selected_subj
                var temp_student = students.filter(x => x.student != 'SUBJECTS')
                var arrayID = students.findIndex(x => x.student == 'SUBJECTS')
                var filter_subject  = students.filter(x => x.student == 'SUBJECTS')[0].grades
                var gradeid = filter_subject.findIndex(x => x.gsdid == temp_selected_subj)
                var teacherid = filter_subject[gradeid].teacherid
                $.ajax({
                    type:'GET',
                    url:'/posting/grade/subject/approve',
                    data:{
                            gdid:temp_selected_subj,
                            quarter:public_quarter,
                            teacherid:teacherid
                    },
                    success:function(data) {
                        students[arrayID].grades[gradeid].status = 2
                        default_subj_option()
                        check_subj_option()
                        toastr.info(students[arrayID].grades[gradeid].subjcode+' grades Approved!')
                        $.each(temp_student,function(a,b){
                            temp_student[a].grades[gradeid].status = 2
                        })
                        update_student_detail_option()
                        loaddatatable(students)
                        subject_info_table()
                    },
                })
            }

            $(document).on('click','#print_ms',function(){
                var gradelevel = $('#gradelevel').val();
                var section = $('#section').val();
                var quarter  = $('#quarter').val(); 
                var syid  = $('#syid').val(); 
                var semid  = 1
                if(gradelevel == 14 || gradelevel == 15){
                    var semid  = $('#semester').val(); 
                }
                var strand  = $('#strand').val(); 
                if(section == null){
                    Swal.fire({
                            type: 'info',
                            title: 'Something went wrong!',
                            text: 'Please reload the page',
                            showConfirmButton: false,
                            timer: 1500
                    });
                }
                else{
                    window.open("/grades/report/mastersheet?gradelevel="+gradelevel+"&section="+section+"&quarter="+quarter+"&sy="+syid+"&semid="+semid+"&strand="+strand);
                }
            })

            $(document).on('click','.print_finalcomp',function(){
                var gradelevel = $('#gradelevel').val();
                var section = $('#section').val();
                var quarter  = $('#quarter').val(); 
                var syid  = $('#syid').val(); 
                var semid  = 1

                var isallowed = false

                if(gradelevel == 14 || gradelevel == 15){
                    isallowed = true
                }

                if(!isallowed){
                    Swal.fire({
                            type: 'info',
                            text: 'This report is for grade 12 students only!',
                            showConfirmButton: false,
                            timer: 1500
                    });
                    return false
                }

                var strand  = $('#strand').val(); 
                if(section == null){
                    Swal.fire({
                            type: 'info',
                            title: 'Something went wrong!',
                            text: 'Please reload the page',
                            showConfirmButton: false,
                            timer: 1500
                    });
                }
                else{
                    window.open("/grades/report/gradingsheet/finalcomposite?gradelevel="+gradelevel+"&section="+section+"&quarter="+quarter+"&sy="+syid+"&semid="+semid+"&strand="+strand);
                }
            })

            $(document).on('click','.print_gsa',function(){
                var gradelevel = $('#gradelevel').val();
                var section = $('#section').val();
                var quarter  = $('#quarter').val(); 
                var syid  = $('#syid').val(); 
                var semid  = 1

                if(section == null){
                    Swal.fire({
                            type: 'info',
                            title: 'Something went wrong!',
                            text: 'Please reload the page',
                            showConfirmButton: false,
                            timer: 1500
                    });
                }
                else{
                    window.open("/grades/report/mastersheet/gsa?gradelevel="+gradelevel+"&section="+section+"&quarter="+quarter+"&sy="+syid+"&semid="+semid+"&strand="+strand);
                }
            })

            


            $(document).on('click','.print_ms_excel',function(){
                var gradelevel = $('#gradelevel').val();
                var section = $('#section').val();
                var quarter  = $('#quarter').val(); 
                var syid  = $('#syid').val(); 
                var semid  = 1
                var format = $(this).attr('data-format')
                if(gradelevel == 14 || gradelevel == 15){
                    var semid  = $('#semester').val(); 
                }
                var strand  = $('#strand').val(); 
                if(section == null){
                    Swal.fire({
                            type: 'info',
                            title: 'Something went wrong!',
                            text: 'Please reload the page',
                            showConfirmButton: false,
                            timer: 1500
                    });
                }
                else{
                    window.open("/grades/report/mastersheet/excel?gradelevel="+gradelevel+"&section="+section+"&quarter="+quarter+"&sy="+syid+"&semid="+semid+"&strand="+strand+"&format="+format);
                }
            })

            $(document).on('click','.print_ms_comp',function(){
                var gradelevel = $('#gradelevel').val();
                var section = $('#section').val();
                var quarter  = $('#quarter').val(); 
                var syid  = $('#syid').val(); 
                var semid  = 1
                if(gradelevel == 14 || gradelevel == 15){
                    var semid  = $('#semester').val(); 
                }
                var strand  = $('#strand').val(); 
                if(section == null){
                    Swal.fire({
                            type: 'info',
                            title: 'Something went wrong!',
                            text: 'Please reload the page',
                            showConfirmButton: false,
                            timer: 1500
                    });
                }
                else{
                    window.open("/grades/report/mastersheet/excel/composite?gradelevel="+gradelevel+"&section="+section+"&quarter="+quarter+"&sy="+syid+"&semid="+semid+"&strand="+strand);
                }
            })



            

            $(document).on('click','#print_gs',function(){
                var gradelevel = $('#gradelevel').val();
                var section = $('#section').val();
                var quarter  = $('#quarter').val(); 
                var syid  = $('#syid').val(); 
                var semid  = 1
                if(gradelevel == 14 || gradelevel == 15){
                    var semid  = $('#semester').val(); 
                }
                var subjid  = $('#grading_sheet_subject').val(); 
                var strand  = $('#strand').val(); 
                if(section == null){
                    Swal.fire({
                            type: 'info',
                            title: 'Something went wrong!',
                            text: 'Please reload the page',
                            showConfirmButton: false,
                            timer: 1500
                    });
                }
                else{
                    window.open("/grades/report/gradingsheet/bysubject?gradelevel="+gradelevel+"&section="+section+"&quarter="+quarter+"&sy="+syid+"&semid="+semid+"&subjid="+subjid+"&strand="+strand);
                }
            })

            $(document).on('click','#print_con',function(){
                var gradelevel = $('#gradelevel').val();
                var section = $('#section').val();
                var quarter  = $('#quarter').val(); 
                var syid  = $('#syid').val(); 
                var semid  = 1
                if(gradelevel == 14 || gradelevel == 15){
                    var semid  = $('#semester').val(); 
                }
                var subjid  = $('#grading_sheet_subject').val(); 
                var strand  = $('#strand').val(); 
                if(section == null){
                    Swal.fire({
                            type: 'info',
                            title: 'Something went wrong!',
                            text: 'Please reload the page',
                            showConfirmButton: false,
                            timer: 1500
                    });
                }
                else{
                    window.open("/grades/report/consolodiated?gradelevel="+gradelevel+"&section="+section+"&quarter="+quarter+"&sy="+syid+"&semid="+semid+"&subjid="+subjid+"&strand="+strand);
                }
            })



            $(document).on('click','#post_all, #sl_post',function(){
                reset_progress_bar()
                var item_count = 0;
                var posted_count = students.filter(function(x){
                    var withData  = false;
                    if( x.student != 'SUBJECTS'){
                        $.each(x.grades,function(a,b){
                            if( b.status != 4   && b.status != "" && b.status != 0){
                                withData = true;
                                temp_item_count += 1;
                            }
                        })
                    }
                    else  if( x.student == 'SUBJECTS'){
                        $.each(x.grades,function(a,b){
                            if(b.subjid != "" && b.gsdid != null && b.status != 0 ){
                                temp_item_count += 1;
                            }
                        })
                    }
                    if(withData){
                        return x;
                    }
                })

                if(posted_count.length == 0){

                    Swal.fire({
                        type: 'info',
                        text: "No available grades for posting!"
                    });

                    return false
                }

                Swal.fire({
                    // html:
                    //     'Are you sure you want <br>' +
                    //     'to post all the grades from this section?',
                    title:'Are you sure?',
                    text:'This will Post all grades from this section',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, post all grades!'
                }).then((result) => {
                    if (result.value) {
                        var subjects = students.filter(x => x.student == 'SUBJECTS')
                        $.each(subjects[0].grades,function(a,b){
                            if(b.subjid != "" && b.gsdid != null && b.status != 0){
                                selected_subj = b.gsdid
                                post_subject_grade_ajax() 
                            }
                        })
                    }
                })
            })
            
            $(document).on('click','#unpost_all, #sl_unpost',function(){

                reset_progress_bar()
                var unposted_count = students.filter(function(x){
                                        var withData  = false;
                                        if( x.student != 'SUBJECTS'){
                                            $.each(x.grades,function(a,b){
                                                if( b.status != 5 && b.status != "" && b.status == 4 && b.status != 0){
                                                    temp_item_count += 1;
                                                    withData = true;
                                                }
                                            })
                                        }
                                        else  if( x.student == 'SUBJECTS'){
                                            $.each(x.grades,function(a,b){
                                                if(b.subjid != ""  && b.gsdid != null && b.status != 0  ){
                                                    temp_item_count += 1;
                                                }
                                            })
                                        }
                                        if(withData){
                                            return x;
                                        }
                                    })

                if(unposted_count.length == 0){
                    Swal.fire({
                        type: 'info',
                        text: "No posted grades!"
                    });
                    return false
                }

                Swal.fire({
                    // html:
                    //     'Are you sure you want <br>' +
                    //     'to unpost all the grades from this section?',
                    title:'Are you sure?',
                    text:'This will Unpost all grades from this section',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, unpost all grades!'
                }).then((result) => {
                    if (result.value) {
                        var subjects = students.filter(x => x.student == 'SUBJECTS')
                        $.each(subjects[0].grades,function(a,b){
                            
                            if(b.subjid != "" && b.gsdid != null && b.status != 0){
                                selected_subj = b.gsdid
                                unpost_subject_grade_ajax(b.gsdid)
                            }
                        })
                    }
                })
            })

            $(document).on('click','#approve_all, #sl_approve',function(){
                reset_progress_bar()
                var unposted_count = students.filter(function(x){
                                        var withData  = false;
                                        if( x.student != 'SUBJECTS'){
                                            $.each(x.grades,function(a,b){
                                                if( b.status != 2 && b.status != "" && b.status != 4 && b.status != 5 && b.status != 0){
                                                    temp_item_count += 1;
                                                    withData = true;
                                                }
                                            })
                                        }
                                        else  if( x.student == 'SUBJECTS'){
                                            $.each(x.grades,function(a,b){
                                                if(b.subjid != ""  && b.gsdid != null && b.status != 0 ){
                                                    temp_item_count += 1;
                                                }
                                            })
                                        }
                                        if(withData){
                                            return x;
                                        }
                                    })

                if(unposted_count.length == 0){
                    Swal.fire({
                        type: 'info',
                        text: "No available grades for approval!"
                    });
                    return false
                }

                Swal.fire({
                    title:'Are you sure?',
                    text:'This will Approve all grades from this section',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve all grades!'
                }).then((result) => {
                    if (result.value) {
                        var subjects = students.filter(x => x.student == 'SUBJECTS')
                        $.each(subjects[0].grades,function(a,b){
                            
                            if(b.subjid != "" && b.gsdid != null && b.status != 0){
                                selected_subj = b.gsdid
                                approve_subject_grade_ajax(b.gsdid)
                            }
                        })
                    }
                })
            })


            var stud_index
            var gdid
            var data_index
            var stud_index
            var teacherid

            function default_stud_subj_option(){

                $('#stud_subj_unpost').removeAttr('disabled')
                $('#stud_subj_pending').removeAttr('disabled')
                $('#stud_subj_post').removeAttr('disabled')

            }
          

            function check_stud_subj_option(){
                
                var checkStatus = students.filter(x=>x.studid == studid)[0].grades.filter(x=>x.gdid == gdid)[0].status

                if(checkStatus == 5){
                    $('#stud_subj_unpost').attr('disabled','disabled')
                }
                else if(checkStatus == 4){
                    $('#stud_subj_post').attr('disabled','disabled')
                    $('#stud_subj_pending').attr('disabled','disabled')
                }
                else if(checkStatus == 1){
                    $('#stud_subj_unpost').attr('disabled','disabled')
                }
                else if(checkStatus == 2){
                    $('#stud_subj_unpost').attr('disabled','disabled')
                }
                else if(checkStatus == 3){
                    $('#stud_subj_pending').attr('disabled','disabled')
                    $('#stud_subj_unpost').attr('disabled','disabled')
                    $('#stud_subj_post').attr('disabled','disabled')
                }
            }



            $(document).on('click','.stud_subj_tr',function(){
               
                $('#student_subject_option').modal()
                studid = $(this).attr('data-studid')
                gdid = $(this).attr('data-id')
                data_index = $(this).attr('data-index')
                stud_index = $(this).attr('data-studindex') - 1 
                teacherid = $(this).attr('data-tid')
                default_stud_subj_option()
                check_stud_subj_option()

            })

            var select_subject;
            var select_gl;
            var all_data_grading_sheet = [];

            $(document).on('change','#grading_sheet_subject',function(){
                var temp_subjid= $(this).val()
                var subj_info = all_data_grading_sheet.filter(x=>x.student == 'SUBJECTS')[0].grades.filter(x=>x.subjid == temp_subjid)
                console.log("sdsdfsf")
                if(subj_info[0].isCon == 1){
                    $('#print_con').removeAttr('hidden')
                }else{
                    $('#print_con').attr('hidden','hidden')
                }
            })

            $(document).on('click','#grading_sheet_filter',function(){

              
                var valid_input = true
                select_subject = $('#grading_sheet_subject').val()
               
                if($('#grading_sheet_subject').val() == ""){
                    Swal.fire({
                        type: 'info',
                        text: "Please select a subject!"
                    });
                    valid_input = false
                    return false;
                }
              
                select_gl = $('#gradelevel').val()

                if(valid_input){

                    var subj_info = all_data_grading_sheet.filter(x=>x.student == 'SUBJECTS')[0].grades.filter(x=>x.subjid == select_subject)

                    if(subj_info[0].isCon == 1){
                        $('#print_con').removeAttr('hidden')
                    }else{
                        $('#print_con').attr('hidden','hidden')
                    }

                    $('#print_gs').removeAttr('disabled','disabled')

                    var gradelevel = $('#gradelevel').val()
                    var section = $('#section').val()
                    var syid = $('#syid').val()
                    var semid  = 1
                    if(gradelevel == 14 || gradelevel == 15){
                        var semid  = $('#semester').val(); 
                    }
                  

                    if(all_data_grading_sheet.length == 0){
                        $.ajax({
                            type:'GET',
                            url:'/posting/grade/getstudents',
                            data:{
                                    gradelevel:gradelevel,
                                    section:section,
                                    sy:syid,
                                    semid:semid
                            },
                            success:function(data) {
                                all_data_grading_sheet = data
                                loaddatatable_gradingsheet(all_data_grading_sheet)
                            }
                        })
                    }else{
                        loaddatatable_gradingsheet(all_data_grading_sheet)
                    }
                }
            })

            function loaddatatable_gradingsheet(data){
                var header = data[0];
                var data = data.filter(x => x.student != 'SUBJECTS')

                if(data.length == 0){

                    $("#grading_sheet_table_list").DataTable({
                        destroy: true,
                        data:data,
                        "scrollX": true,
                        "columnDefs": [
                            {"title":"Student Name","targets":0},
                            {"title":"Q1","targets":1},
                            {"title":"Q2","targets":2},
                            {"title":"Q3","targets":3},
                            {"title":"Q4","targets":4},
                            {"title":"Final Rating","targets":5},
                            {"title":"Remarks","targets":6},
                        ]
                    })
                        
                }
                else{


                    var header = data[0];
                    var temp_levelid = $('#gradelevel').val()
                    if(temp_levelid == 14 || temp_levelid == 15){
                        var temp_strand = $('#strand').val()
                        var data = data.filter(x => x.student != 'SUBJECTS' && x.strand == temp_strand)
                    }else{
                        var data = data.filter(x => x.student != 'SUBJECTS')
                    }

                    $("#grading_sheet_table_list").DataTable({
                        destroy: true,
                        data:data,
                        "scrollX": true,
                        order: [[ 0, "desc" ]],
                        columns: [
                                    { "data": "gender" },
                                    { "data": "student" },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                            ],
                        "columnDefs": [
                            { "title": "STUDENT", 
                                "targets": 0,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                   $(td)[0].innerHTML = '<span class="badge badge-success mr-2" style="font-size:.5rem !important"> '+rowData.gender+'</span>'+rowData.student
                                 } 
                            
                            },
                            { "title": "Q1", 
                                "targets": 1,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                   $(td).addClass('text-center')
                                   var qg = rowData.grades.filter(x=>x.subjid == select_subject)
                                   if(select_gl == 14 || select_gl == 15){
                                        if($('#semester').val() == 2){
                                            $(td).attr('hidden','hidden')
                                        }
                                    }else{
                                        $(td).removeAttr('hidden')
                                    }
                                   if(qg.length != 0){
                                        $(td).text(qg[0].q1)
                                   }else{
                                        $(td).text(null)
                                   }
                         
                                } 
                            
                            },
                            { "title": "Q2", 
                                "targets": 2,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td).addClass('text-center')
                                    var qg = cellData.grades.filter(x=>x.subjid == select_subject)
                                    if(select_gl == 14 || select_gl == 15){
                                        if($('#semester').val() == 2){
                                            $(td).attr('hidden','hidden')
                                        }
                                    }else{
                                        $(td).removeAttr('hidden')
                                    }
                                    if(qg.length != 0){
                                            $(td).text(qg[0].q2)
                                    }else{
                                            $(td).text(null)
                                    }
                                } 
                            
                            },
                            { "title": "Q3", 
                                "targets": 3,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td).addClass('text-center')
                                    var qg = cellData.grades.filter(x=>x.subjid == select_subject)
                                    if(select_gl == 14 || select_gl == 15){
                                        if($('#semester').val() == 1){
                                            $(td).attr('hidden','hidden')
                                        }
                                    }else{
                                        $(td).removeAttr('hidden')
                                    }
                                    if(qg.length != 0){
                                            $(td).text(qg[0].q3)
                                    }else{
                                            $(td).text(null)
                                    }
                                } 
                            
                            },
                            { "title": "Q4", 
                                "targets": 4,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td).addClass('text-center')
                                    if(select_gl == 14 || select_gl == 15){
                                        if($('#semester').val() == 1){
                                            $(td).attr('hidden','hidden')
                                        }
                                    }
                                    else{
                                        $(td).removeAttr('hidden')
                                    }
                                    var qg = cellData.grades.filter(x=>x.subjid == select_subject)
                                    if(qg.length != 0){
                                            $(td).text(qg[0].q4)
                                    }else{
                                            $(td).text(null)
                                    }
                                } 
                            },
                            { "title": "FINAL GRADE", 
                                "targets": 5,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td).addClass('text-center')
                                    var qg = cellData.grades.filter(x=>x.subjid == select_subject)
                                    if(qg.length > 0){
                                        $(td).text(qg[0].finalrating)
                                    }else{
                                        $(td).text(null)
                                    }
                                    
                                } 
                            
                            },
                            { "title": "REMARK", 
                                "targets": 6,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td).addClass('text-center')
                                    var qg = cellData.grades.filter(x=>x.subjid == select_subject)
                                    if(qg.length > 0){
                                        $(td).text(qg[0].actiontaken)
                                    }else{
                                        $(td).text(null)
                                    }
                                  
                                } 
                            },

                        ]
                    });

                    if(select_gl == 14 || select_gl == 15){
                        if($('#semester').val() == 1){
                            $('.gs_q3').attr('hidden','hidden')
                            $('.gs_q4').attr('hidden','hidden')
                        }else{
                            $('.gs_q1').attr('hidden','hidden')
                            $('.gs_q2').attr('hidden','hidden')
                        }
                    }
                    else{
                        $('.gs_q3').removeAttr('hidden')
                        $('.gs_q4').removeAttr('hidden')
                        $('.gs_q1').removeAttr('hidden')
                        $('.gs_q2').removeAttr('hidden')
                    }

                }

            }

            


            // var all_data_sf9
            // var select_gl
            // $(document).on('click','#sf9_sheet_filter',function(){
            //     var valid_input = true
            //     all_data_sf9 = all_data_grading_sheet
            //     // loaddatatable_sf9(all_data_sf9)
            //     student_count = all_data_sf9.filter(x=>x.student != 'SUBJECTS')
            //     if(student_count.length > 0){
            //         $('#sf9_student_name').empty()
            //         $('#sf9_student_name').append('<option value="">SELECT STUDENT</option>')
            //         $.each(student_count,function(a,b){
            //             $('#sf9_student_name').append('<option value="'+b.studid+'">'+b.student+'</option>')
            //         })
            //         // $('#sf9_report_button').removeAttr('disabled','disabled')
            //         loaddatatable(all_data)
            //     }
            //     else{
            //         all_data_sf9 = []
            //         loaddatatable(all_data_sf9)
            //         Swal.fire({
            //             type: 'info',
            //             text: "No Enrolled Students!"
            //         });
            //     }
                  
            // })
            // var seleted_student_for_sf9
            // $(document).on('click','#sf9_report_button',function(){
            //       if($('#sf9_student_name').val() == ""){
            //             Swal.fire({
            //                   type: 'info',
            //                   text: "Please select a student!"
            //             });
            //             $('#sf9_grade_table').empty()
            //             return false      
            //       }
            //       if($('#sf9_student_name').val() == seleted_student_for_sf9){
            //         return false
            //       }else{
            //         seleted_student_for_sf9 = $('#sf9_student_name').val()
            //       }

                 
            //       var temp_student_grade = all_data_sf9.filter(x=>x.studid == $('#sf9_student_name').val())[0].grades.filter(x=>x.subjid != "")
            //       var q1final;
            //       var q2final;
            //       var q3final;
            //       var q4final;
            //       $('#sf9_grade_table').empty()
            //       var tem_gen_ava = 0;
            //       var gen_ave = 0;
            //       var gen_ave_count = 0;
            //       $.each(temp_student_grade,function(a,b){

            //             if(b.q1 != null){
            //                 q1final += parseInt(b.q1);
            //             }
            //             if(b.q2 != null){
            //                 q2final += parseInt(b.q2);
            //             }
            //             if(b.q3 != null){
            //                 q3final += parseInt(b.q3);
            //             }
            //             if(b.q4 != null){
            //                 q4final += parseInt(b.q4);
            //             }
                        
            //             if(select_gl == 14 || select_gl == 15){
            //                 var final_rating = true
            //                 if(b.q1 == null || b.q1 == ""){
            //                     q1final += "";
            //                     b.q1 = "";
            //                     final_rating = false
            //                 }
            //                 if(b.q2 == null || b.q2 == ""){
            //                     q2final += "";
            //                     b.q2 = "";
            //                     final_rating = false
            //                 }
            //                 var gradefinale;
            //                 var remark = "";
            //                 if(final_rating){
            //                     gradefinale = ( b.q1 + b.q2 ) / 2;
            //                     if(gradefinale.toFixed() >= 75){
            //                         remark = 'PASSED'
            //                     }else{
            //                         remark = 'FAILED'
            //                     }
            //                     gradefinale = gradefinale.toFixed()
            //                     gen_ave += gradefinale;
            //                     gen_ave_count+=1
            //                 }else{
            //                     gradefinale = ""
            //                 }

            //                 $('#sf9_grade_table').append('<tr><td>'+b.subjdesc+'</td><td class="text-center">'+b.q1+'</td><td class="text-center">'+b.q2+'</td><td class="text-center">'+gradefinale+'</td><td class="text-center">'+remark+'</td></tr>')
            //             }
            //             else{
            //                 var final_rating = true
            //                 if(b.q1 == null || b.q1 == ""){
            //                     q1final += "";
            //                     b.q1 = "";
            //                     final_rating = false
            //                 }
            //                 if(b.q2 == null || b.q2 == ""){
            //                     q2final += "";
            //                     b.q2 = "";
            //                     final_rating = false
            //                 }
            //                 if(b.q3 == null || b.q3 == ""){
            //                     q3final += "";
            //                     b.q3 = "";
            //                     final_rating = false
            //                 }
            //                 if(b.q4 == null || b.q4 == ""){
            //                     q4final += "";
            //                     b.q4 = "";
            //                     final_rating = false
            //                 }
            //                 var gradefinale;
            //                 var remark = "";
            //                 if(final_rating){
            //                     gradefinale =  ( b.q1 + b.q2 + b.q3 + b.q4 ) / 4 ;
            //                     if(gradefinale.toFixed() >= 75){
            //                         remark = 'PASSED'
            //                     }else{
            //                         remark = 'FAILED'
            //                     }
            //                     gradefinale = gradefinale.toFixed()
            //                     gen_ave += parseInt(gradefinale);
            //                     gen_ave_count+=1
            //                 }else{
            //                     gradefinale = ""
            //                 }
            //                 var mapeh_cs = ''
            //                 if(b.inMAPEH == 1 || b.inTLE == 1){
            //                     mapeh_cs = 'pl-5'
            //                 }
                          
            //                 $('#sf9_grade_table').append('<tr><td class="'+mapeh_cs+'">'+b.subjdesc+'</td><td class="text-center">'+b.q1+'</td><td class="text-center">'+b.q2+'</td><td class="text-center">'+b.q3+'</td><td class="text-center">'+b.q4+'</td><td class="text-center">'+gradefinale+'</td><td class="text-center">'+remark+'</td></tr>')
            //             }
            //             if((a+1) ==  temp_student_grade.length){
            //                 if(gen_ave_count == (a+1)){
            //                   if(select_gl == 14 || select_gl == 15){
            //                         var gradefinale = gen_ave / gen_ave_count
            //                         var remark = "";
            //                         if(gradefinale.toFixed() >= 75){
            //                             remark = 'PASSED'
            //                         }else{
            //                              remark = 'FAILED'
            //                         }
            //                         $('#sf9_grade_table').append('<tr><td class="text-right">GENERAL AVERAGE</td><td class="text-center" colspan="2"></td><td class="text-center">'+gradefinale.toFixed()+'</td><td class="text-center">'+remark+'</td></tr>')
            //                   }
            //                   else{
            //                     $('#sf9_grade_table').append('<tr><td class="text-right">GENERAL AVERAGE</td><td class="text-center" colspan="2"></td><td class="text-center">'+gradefinale.toFixed()+'</td><td class="text-center">'+remark+'</td></tr>')
            //                   }
                              
            //                 }
            //                 else{
            //                     if(select_gl == 14 || select_gl == 15){
            //                         $('#sf9_grade_table').append('<tr><td class="text-right">GENERAL AVERAGE</td><td class="text-center" colspan="2"></td></td><td class="text-center"></td><td class="text-center"></td></tr>')
            //                     }else{
            //                         $('#sf9_grade_table').append('<tr><td class="text-right">GENERAL AVERAGE</td><td class="text-center" colspan="4"></td></td><td class="text-center"></td><td class="text-center"></td></tr>')
            //                     }
            //                 }
            //             }
            //       })
            // })

            // function loaddatatable_sf9(data){
                
            //     if(select_gl == 14 || select_gl == 15){
            //         $('.for_gs').attr('hidden','hidden')
            //         $('.pr').attr('colspan',2)
            //     }
            //     else{
            //         $('.for_gs').removeAttr('hidden')
            //         $('.pr').attr('colspan',4)
            //     }

            //     var header = data[0];
            //     var data = data.filter(x => x.student != 'SUBJECTS')

            //     if(select_gl == 14 || select_gl == 15){
            //         $('.for_gs').attr('hidden','hidden')
            //     }
            //     else{
            //         $('.for_gs').removeAttr('hidden')
            //     }
               
            //     if(data.length == 0){

            //         $("#sf9_sheet_table_list").DataTable({
            //             destroy: true,
            //             data:data,
            //             "scrollX": true,
            //             "columnDefs": [
            //                 {"title":"Student Name","targets":0},
            //                 {"title":"Q1","targets":1},
            //                 {"title":"Q2","targets":2},
            //                 {"title":"Q3","targets":3},
            //                 {"title":"Q4","targets":4},
            //                 {"title":"Final Rating","targets":5},
            //                 {"title":"Remarks","targets":6},
            //             ]
            //         })
                        
            //     }
            //     else{
            //         $("#sf9_sheet_table_list").DataTable({
            //             destroy: true,
            //             data:data,
            //             "scrollX": true,
            //             columns: [
            //                         { "data": "student" },
            //                         { "data": null },
            //                         { "data": null },
            //                         { "data": null },
            //                         { "data": null },
            //                         { "data": null },
            //                         { "data": null },
            //                 ],
            //             "columnDefs": [
            //                 { "title": "STUDENT", 
            //                     "targets": 0,
            //                     'createdCell':  function (td, cellData, rowData, row, col) {
            //                         $(td)[0].innerHTML = rowData.student
            //                     } 
            //                 },
            //                 { "title": "Q1", 
            //                     "targets": 1,
            //                     'createdCell':  function (td, cellData, rowData, row, col) {
            //                     } 
            //                 },
            //                 { "title": "Q2", 
            //                     "targets": 2,
            //                     'createdCell':  function (td, cellData, rowData, row, col) {
            //                         var length = 0
            //                         var with_grade_count = 0
            //                         var student_subject = rowData.grades.filter(x=>x.subjid != "")
            //                         $.each(student_subject,function(a,b){
            //                             if(b.inMAPEH == 0){
            //                                 length += 1;
            //                             }
            //                         })
            //                         var with_grades = rowData.grades.filter(x=>x.q2 != "" && x.q2 != null && x.subjid != "" )
            //                         $.each(with_grades,function(a,b){
            //                             if(b.inMAPEH == 0){
            //                                 with_grade_count += 1;
            //                             }
            //                         })

            //                         var fg = 0
            //                         if(length != with_grade_count){
            //                             $(td).text(null)
            //                         }else{
            //                             $.each(with_grades,function(a,b){
            //                                 if(b.inMAPEH == 0){
            //                                     fg += parseInt(b.q2)
            //                                 }
            //                             })
            //                             fg = fg / length
            //                             if(fg != 0){
            //                                 if(fg.toFixed() >= 75){
            //                                     $(td).text( fg.toFixed() )
            //                                 }else{
            //                                     $(td).text(null)
            //                                 }
            //                             }
            //                             else{
            //                                 $(td).text(null)
            //                             }
            //                         }
            //                     } 
                            
            //                 },
            //                 { "title": "Q3", 
            //                     "targets": 3,
            //                     'createdCell':  function (td, cellData, rowData, row, col) {
            //                         if(select_gl == 14 || select_gl == 15){
            //                             $(td).attr('hidden','hidden')
            //                         }
            //                         else{
            //                             $(td).removeAttr('hidden')
            //                         }
            //                         $(td).addClass('text-center')
            //                         var length = 0
            //                         var with_grade_count = 0
            //                         var student_subject = rowData.grades.filter(x=>x.subjid != "")
            //                         $.each(student_subject,function(a,b){
            //                             if(b.inMAPEH == 0){
            //                                 length += 1;
            //                             }
            //                         })
            //                         var with_grades = rowData.grades.filter(x=>x.q3 != "" && x.q3 != null && x.subjid != "" )
            //                         $.each(with_grades,function(a,b){
            //                             if(b.inMAPEH == 0){
            //                                 with_grade_count += 1;
            //                             }
            //                         })
            //                         var fg = 0
            //                         if(length != with_grade_count){
            //                             $(td).text(null)
            //                         }else{
            //                             $.each(with_grades,function(a,b){
            //                                 if(b.inMAPEH == 0){
            //                                     fg += parseInt(b.q3)
            //                                 }
            //                             })
            //                             fg = fg / length
            //                             if(fg != 0){
            //                                 if(fg.toFixed() >= 75){
            //                                     $(td).text( fg.toFixed() )
            //                                 }else{
            //                                     $(td).text(null)
            //                                 }
            //                             }
            //                             else{
            //                                 $(td).text(null)
            //                             }
            //                         }
            //                     } 
                            
            //                 },
            //                 { "title": "Q4", 
            //                     "targets": 4,
            //                     'createdCell':  function (td, cellData, rowData, row, col) {
            //                         if(select_gl == 14 || select_gl == 15){
            //                             $(td).attr('hidden','hidden')
            //                         }
            //                         else{
            //                             $(td).removeAttr('hidden')
            //                         }
            //                         $(td).addClass('text-center')
            //                         var length = 0
            //                         var with_grade_count = 0
            //                         var student_subject = rowData.grades.filter(x=>x.subjid != "")
            //                         $.each(student_subject,function(a,b){
            //                             if(b.inMAPEH == 0){
            //                                 length += 1;
            //                             }
            //                         })
            //                         var with_grades = rowData.grades.filter(x=>x.q4 != "" && x.q4 != null && x.subjid != "" )
            //                         $.each(with_grades,function(a,b){
            //                             if(b.inMAPEH == 0){
            //                                 with_grade_count += 1;
            //                             }
            //                         })
            //                         var fg = 0
            //                         if(length != with_grade_count){
            //                             $(td).text(null)
            //                         }else{
            //                             $.each(with_grades,function(a,b){
            //                                 if(b.inMAPEH == 0){
            //                                     fg += parseInt(b.q4)
            //                                 }
            //                             })
            //                             fg = fg / length
            //                             if(fg != 0){
            //                                 if(fg.toFixed() >= 75){
            //                                     $(td).text( fg.toFixed() )
            //                                 }else{
            //                                     $(td).text(null)
            //                                 }
            //                             }
            //                             else{
            //                                 $(td).text(null)
            //                             }
            //                         }
            //                     } 
                            
            //                 },
            //                 { "title": "Final Grade", 
            //                     "targets": 5,
            //                     'createdCell':  function (td, cellData, rowData, row, col) {
            //                         $(td).addClass('text-center')
            //                         var length = rowData.grades.length

            //                         var q1_grades = rowData.grades.filter(x=>x.q1 != "" && x.q1 != null).length
            //                         var q2_grades = rowData.grades.filter(x=>x.q2 != "" && x.q2 != null).length
            //                         var q3_grades = rowData.grades.filter(x=>x.q3 != "" && x.q3 != null).length
            //                         var q4_grades = rowData.grades.filter(x=>x.q4 != "" && x.q4 != null).length

            //                         var fg1 = 0;
            //                         var fg2 = 0;
            //                         var fg3 = 0;
            //                         var fg4 = 0;

            //                         var fg = 0;

            //                         if(q1_grades == length){
            //                             $.each(rowData.grades,function(a,b){
            //                                 fg1 += parseInt(b.q1)
            //                             })

            //                             fg1 = fg1 / length;
            //                         }
            //                         if(q2_grades == length){
            //                             $.each(rowData.grades,function(a,b){
            //                                 fg2 += parseInt(b.q2)
            //                             })
            //                             fg2 = fg2 / length
            //                         }
            //                         if(q3_grades == length){
            //                             $.each(rowData.grades,function(a,b){
            //                                 fg3 += parseInt(b.q3)
            //                             })
            //                             fg3 = fg3 / length
            //                         }
            //                         if(q4_grades == length){
            //                             $.each(rowData.grades,function(a,b){
            //                                 fg4 += parseInt(b.q4)
            //                             })
            //                             fg4 = fg4 / length;
            //                         }

            //                         if(select_gl == 14 || select_gl == 15){
            //                             if(fg1 != 0 && fg2 != 0){
            //                                 fg = ( fg1 + fg2) / 2
            //                             }
            //                         }else{

            //                             if(fg1 != 0 && fg2 != 0 && fg3 != 0 && fg4 != 0){
            //                                 fg = ( fg1 + fg2 + fg3 + fg4 ) /4
            //                             }
            //                         }
                                  
            //                         if(fg != 0){
            //                             $(td).text( fg.toFixed() )
            //                         }
            //                         else{
            //                             $(td).text(null)
            //                         }
                                  
            //                     } 
                            
            //                 },
            //                 { "title": "Remark", 
            //                     "targets": 6,
            //                     'createdCell':  function (td, cellData, rowData, row, col) {
            //                         $(td).addClass('text-center')
            //                         var length = rowData.grades.length

            //                         var q1_grades = rowData.grades.filter(x=>x.q1 != "" && x.q1 != null).length
            //                         var q2_grades = rowData.grades.filter(x=>x.q2 != "" && x.q2 != null).length
            //                         var q3_grades = rowData.grades.filter(x=>x.q3 != "" && x.q3 != null).length
            //                         var q4_grades = rowData.grades.filter(x=>x.q4 != "" && x.q4 != null).length

            //                         var fg1 = 0;
            //                         var fg2 = 0;
            //                         var fg3 = 0;
            //                         var fg4 = 0;

            //                         var fg = 0;

            //                         if(q1_grades == length){
            //                             $.each(rowData.grades,function(a,b){
            //                                 fg1 += b.q1
            //                             })
            //                             fg1 = fg1 / length;
            //                         }
            //                         if(q2_grades == length){
            //                             $.each(rowData.grades,function(a,b){
            //                                 fg2 += b.q2
            //                             })
            //                             fg2 = fg2 / length
            //                         }
            //                         if(q3_grades == length){
            //                             $.each(rowData.grades,function(a,b){
            //                                 fg3 += b.q3
            //                             })
            //                             fg3 = fg3 / length
            //                         }
            //                         if(q4_grades == length){
            //                             $.each(rowData.grades,function(a,b){
            //                                 fg4 += b.q4
            //                             })
            //                             fg4 = fg4 / length;
            //                         }

            //                         if(select_gl == 14 || select_gl == 15){
            //                             if(fg1 != 0 && fg2 != 0){
            //                                 fg = ( fg1 + fg2) / 2
            //                             }
            //                         }else{
            //                             if(fg1 != 0 && fg2 != 0 && fg3 != 0 && fg4 != 0){
            //                                 fg = ( fg1 + fg2 + fg3 + fg4 ) /4
            //                             }
            //                         }
                                    
            //                         if(fg != 0){
            //                             if(fg >= 75){
            //                                 $(td).text('PASSED')
            //                             }else{
            //                                 $(td).text('FAILED')
            //                             }
            //                         }
            //                         else{
            //                             $(td).text(null)
            //                         }
            //                     } 
                            
            //                 },

            //             ]
            //         });

                 

            //     }

            // }



        })
    </script>

@endsection


