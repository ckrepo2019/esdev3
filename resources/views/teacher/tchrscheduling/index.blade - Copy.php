
@extends('teacher.layouts.app')
@section('pagespecificscripts')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        margin-top: -9px;
    }
    #section-list-datatable td.dataTables_empty {
        text-align: center;  
        padding-left: 30px;
    }
    .smfont{
        font-size:14px;
    }
    .calendar-table{
        display: none;
    }
    .drp-buttons{
        display: none !important;
    }
    #et{
        height: 10px;
        visibility: hidden;
    }

    .isHPS {
        position: sticky;
        top: 27px !important;
        background-color: #fff;
        outline: 2px solid #dee2e6 ;
        outline-offset: -1px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
    }
</style>
@endsection
@section('content')

{{-- <div>
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb"
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="active breadcrumb-item">Attendance</li>
            <li class="active breadcrumb-item" aria-current="page">Advisory</li>
        </ol>
    </nav>
</div> --}}
<section class="content-header p-0" style="padding-top: 15px!important">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                @php
                    $sysdesc = DB::table('sy')->select('sydesc')->where('isactive', 1)->get();
                @endphp
                <span style="font-size: 1.2rem"><b>Active School Year</b></span> <br>
                <span><i class="fas fa-circle text-success"></i> {{$sysdesc[0]->sydesc}}</span> 
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Scheduling</li>
                </ol>
            </div>
        </div>

        <div class="row" style="padding-top: 5px!important">
            <div class="col-md-6">
                {{-- @php
                    $sysdesc = DB::table('sy')->select('sydesc')->where('isactive', 1)->get();
                @endphp
                <span style="font-size: 1.2rem"><b>Active School Year</b></span> <br>
                <span><i class="fas fa-circle text-success"></i> {{$sysdesc[0]->sydesc}}</span>  --}}
            </div>
            <div class="col-sm-6">
                {{-- <div class="float-sm-right" style="font-size: .9rem">
                    <ul>
                        <li><span class="p-0"><span class="badge badge-dark">SP</span> - Subject Component Created by Principal/Registrar</span></li>
                        <li><span class="p-0"><span class="badge badge-dark">T</span> - Subject Component Created by Teacher</span> </li>
                    </ul>
                </div> --}}
            </div>
        </div>
    </div>
</section>

<!-- Modal when button view sched is click -->
<div class="modal fade" id="modal-subjectcomponent">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportmodalLabel">Subject Component</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body p-0">

                <div class="card shadow-none p-0" style="border: none;">
                    <div class="card-body shadow-none">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="id_asc_levelid">
                                <input type="hidden" id="id_asc_sectionid">
                                <input type="hidden" id="id_asc_subjid">
                                <input type="hidden" id="id_asc_syid">
                                <input type="hidden" id="id_asc_semid">
                                <input type="hidden" id="id_asc_techerid">


                                <input type="hidden" id="id_asc_teachersubjcomid">
                                <input type="hidden" id="id_asc_headerid">
                                {{-- <input type="text" id="subjcomidd"> --}}
                                
                                <label for="">Component Percentage</label>
                                {{-- <select class="form-control form-control-sm select2" id="select-subjcom">
                                </select> --}}
                                {{-- pin componentpercentage v1 --}}

                                <select class="form-control form-control-sm select2" id="select-subjcomv2">
                                </select>
                            </div>
                        </div> <br>
                        <div class="row p-0">
                            <div class="col-md-6">
                                <button class="btn btn-sm" id="btn-addsubjcom"><i class="fas fa-plus"></i> Add</button>
                            </div>
                            <div class="col-md-6 text-right">
                                <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                                {{-- <button class="btn btn-danger btn-sm" id="btn-removesubjcom"><i class="fas fa-trash"></i> Remove</button> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- =========================================================================================================== --}}

<!-- Modal when button view sched is click -->
<div class="modal fade" id="modal-viewallschedules">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <input type="hidden" id="schedulesectionid">
                <div class="card shadow-none" style="border: none;">
                    <div class="card-body shadow-none" style="padding-bottom: 0px !important;">
                        <div class="row">
                            <div class="col-md-4">
                                <h1 class="card-title text-center"><b>View All Schedules</b></h1>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control form-control-sm select2 syselect" id="select-syid-section">
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control form-control-sm select2" id="select-gradelevel">
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="margin-top: 15px!important;">
                                <table width="100%" class="table table-sm table-bordered table-head-fixed" id="section-list-datatable" style="font-size: 13px;">
                                    <thead>
                                        <tr>
                                            <th width="40%">Section Name</th>
                                            <th width="40%">Grade Level</th>
                                            <th class="text-center" width="20%">Schedule</th>
                                        </tr>
                                    </thead>
                                    <tbody class="sectiontbody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-6">
                   
                </div>
                <div class="col-md-6 text-right">
                      <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                </div>
          </div>
        </div>
    </div>
</div>

{{-- =========================================================================================================== --}}


<!-- Modal when button view sched is click -->
<div class="modal fade" id="modal-schedules">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header shssched">
                <div class="col-md-6">
                    <h5 class="modal-title" id="exportmodalLabel">Schedules in Section <span class="text-primary" id="as_section" style="font-weight: bold; text-decoration: underline;"></span><input type="hidden" id="as_glevel_id"></h5>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-sm-4">
                            <label>Select Sem:</label>
                        </div>
                        <div class="col-sm-8">
                            <select class="p-0 form-control form-control-sm select2 semselect" id="select-sem">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
            </div>
            <div class="modal-header jhssched">
                <h5 class="modal-title" id="exportmodalLabel">Schedules in Section <span class="text-primary" id="jhs_section" style="font-weight: bold; text-decoration: underline;"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body p-0">
                
                <input type="hidden" id="schedulesectionid">
                <div class="card shadow-none" style="border: none;">
                    <div class="card-body shadow-none">
                        <div class="row">
                            <div class="col-md-12">
                                <table width="100%" class="table table-bordered" id="view-sched-datatable" style="font-size: 13px;">
                                    <thead>
                                        <tr>
                                            <th width="50%">Subjects</th>
                                            <th width="30%">Teacher</th>
                                            <th width="20%"></th>
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
    </div>
</div>

{{-- =========================================================================================================== --}}





<!-- Modal When button Add Sched is click -->
<div class="modal fade" id="modal-addschedule">
    <div class="modal-dialog modal-md"  id="audsched">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="headaddsched">Add Schedules</h5>
            <h5 class="modal-title" id="headupdatesched">Update Schedules</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span>×</span>
            </button>
        </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Grade Level</label>
                            <input type="input" class="form-control form-control-sm" id="as_gradelevel" readonly placeholder="Grade Level">
                            <input type="hidden" id="id_as_gradelevel">
                            <input type="hidden" id="id_as_acadprogid">
                            <input type="hidden" id="id_as_semid">

                            {{-- update sched --}}
                            <input type="hidden" id="id_up_gradelevel">
                            <input type="hidden" id="id_up_acadprogid">
                            
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group p-0">
                            <label for="exampleInputPassword1">Section</label>
                            <input type="text" class="form-control form-control-sm" id="input_as_section" readonly>
                            <input type="hidden" id="id_as_section">

                            {{-- update sched --}}
                            <input type="hidden" id="id_up_section">
                            <input type="hidden" id="id_up_detailid">
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Subject</label>
                            <input type="input" class="form-control form-control-sm" id="as_subject" readonly>
                            <input type="hidden" id="id_as_subject">

                            {{-- update sched --}}
                            <input type="hidden" id="id_up_subject">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Room</label>
                            <select class="form-control form-control-sm select2 room-select"  id="as_room">
                            </select>
                            {{-- <input type="input" class="form-control form-control-sm" id="as_room"> --}}
                            <input type="hidden" id="id_as_room">

                            {{-- update sched --}}
                            <input type="hidden" id="id_up_room">
                            <input type="hidden" id="id_up_strandid">
                            
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="">Time</label>
                            <input type="text" class="form-control  form-control-sm reservationtime" name="time" id="time">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline mr-3">
                                <input type="checkbox" id="Mon" class="day" value="1">
                                <label for="Mon">Mon</label>
                            </div>
                            <div class="icheck-primary d-inline mr-3">
                                    <input type="checkbox" id="Tue" class="day" value="2">
                                    <label for="Tue">Tue</label>
                            </div>
                            <div class="icheck-primary d-inline mr-3">
                                    <input type="checkbox" id="Wed" class="day" value="3">
                                    <label for="Wed">Wed</label>
                            </div>
                            <div class="icheck-primary d-inline mr-3">
                                    <input type="checkbox" id="Thu" class="day" value="4">
                                    <label for="Thu">Thu</label>
                            </div>
                            <div class="icheck-primary d-inline mr-3">
                                    <input type="checkbox" id="Fri" class="day" value="5">
                                    <label for="Fri">Fri</label>
                            </div>
                            <div class="icheck-primary d-inline mr-3">
                                <input type="checkbox" id="Sat" class="day" value="6">
                                <label for="Sat">Sat</label>
                            </div>
                            <div class="retun-message mt-1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {{-- <label for="exampleInputPassword1">Schedule Classification</label> --}}
                            <select class="form-control form-control-sm select2" id="select-schedclass">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {{-- <label for="exampleInputPassword1">Conflict</label> --}}
                            {{-- <span class="" id="conflicts"></span> --}}

                        <div id="conflict_holder" hidden>
                            <hr class="mb-2">
                            <a href="#" id="view_conflict">Conflict</a>
                            <hr class="mt-2">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer justify-content-between">
                <button class="btn btn-primary btn-sm eval" id="btn_proceed">Proceed</button>
                <button class="btn btn-success btn-sm update_sched" id="btn_updatesched">Update Sched</button>
                <button class="btn btn-danger btn-sm remove_sched" id="btn_removesched">Remove Sched</button>
            </div>
        </div>
    </div>
</div>

{{-- =========================================================================================================== --}}


<!-- Modal When theres is conflict -->
<div class="modal fade" id="conflict_info_modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                <h4 class="modal-title" style="font-size: 1.1rem !important">Conflict Information</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
          </div>
            <div class="modal-body pt-0" id="conflict_holder_detail">
                <div class="row">
                    <div class="col-md-12 rc_holder">
                        <label for="">Room Conflict:</label>
                        <div class="row" id="room_conflict" >
                        </div>
                    </div>
                    <div class="col-md-12 mt-3 sc_holder">
                        <label for="">Section Conflict:</label>
                        <div class="row" id="section_conflict" >
                        </div>
                    </div>
                    
                    <div class="col-md-12 mt-3 tc_holder">
                        <label for="">Teacher Conflict:</label>
                        <div class="row" id="teacher_conflict">
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>
{{-- ========================================================================================================= --}}


<!-- Modal When component percentage button is click -->
<div class="modal fade" id="modal-componentpercentage">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{-- <div class="modal-header">
                <div class="col-md-6">
                    <h5 class="modal-title" id="exportmodalLabel">Subject Component Percentage</h5>
                </div>
                <div class="col-md-5 text-right">
                    <button class="btn btn-info btn-sm" id="btn-addcomponentpercentage"><i class="fas fa-plus"></i> Create Component Percentage</button>
                </div>
                <div class="col-md-1">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
            </div> --}}
            <div class="modal-header">
                <h5 class="modal-title" id="exportmodalLabel">Subject Component Percentage</h5>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12" style="font-size:.7rem">
                        <table class="table table-sm table-bordered" id="subjectcomponent_table" width="100%">
                            <thead>
                                  <tr>
                                        <th width="42%">Description</th>
                                        <th class="text-center" width="12%" style="padding-right: 0!important;">WW</th>
                                        <th class="text-center"  width="12%" style="padding-right: 0!important;">PT</th>
                                        <th class="text-center"  width="12%" style="padding-right: 0!important;">QA</th>
                                        <th class="text-center"  width="12%" style="padding-right: 0!important;">CG</th>
                                        <th class="text-center"  width="5%"></th>
                                        <th class="text-center"  width="5%"></th>
                                  </tr>
                            </thead>
                      </table>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
{{-- ========================================================================================================= --}}





<!-- Modal When component percentage button is click -->
<div class="modal fade" id="modal-addcomponentpercentage">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                        <div class="col-md-12 form-group">
                                <label for="">Written Works</label>
                                <input class="form-control form-control-sm" id="comp1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                        </div>
                        <div class="col-md-12 form-group">
                                <label for="">Performance Task</label>
                                <input class="form-control form-control-sm" id="comp2" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                        </div>
                        <div class="col-md-12 form-group">
                                <label for="">Quarterly Assesment</label>
                                <input class="form-control form-control-sm" id="comp3" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                        </div>
                        <div class="col-md-12 form-group">
                                <label for="">Character Grade</label>
                                <input class="form-control form-control-sm" id="comp4" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                        </div>
                </div>
          </div>
          <div class="modal-footer border-0">
                <div class="col-md-6">
                      <button class="btn btn-primary btn-sm" id="create_component_percentage"> Create</button>
                      <input type="hidden" id="id_comper">
                      <input type="hidden" id="id_teacherid">
                </div>
                <div class="col-md-6 text-right">
                      <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                </div>
          </div>
        </div>
    </div>
</div>
{{-- ========================================================================================================= --}}







<div class="row">
    {{-- Teacher own schedule --}}
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <h1 class="card-title text-center"><b>Your Schedule</b></h1>
                    </div>
                    <div class="col-md-2">
                        {{-- <label>Select S.Y.</label> --}}
                        <select class="form-control form-control-sm select2 syselect" id="select-syid-teachersched">
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control select2" id="teacherfilter_semester">
                        </select>
                    </div>
                    <div class="col-md-6 text-right">
                        {{-- <button type="button" class="btn btn-primary" id="btn-componentpercentage" style="font-size:.8rem" ><i class="fas fa-percent"></i> Component Percentage</button> --}}
                        <button type="button" class="btn btn-primary" id="btn_modalviewallsched" style="font-size:.8em">View All Schedules</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mt-10" style="margin-top: 15px!important;">
                        <table width="100%" class="table table-bordered table-sm table-head-fixed " id="schedule-list-datatable"  style="font-size:.8rem">
                            <thead>
                                <tr>
                                    <th width="15%">Section</th>
                                    <th width="35%">Subject</th>
                                    <th width="15%" class="text-center">Time & Day</th>
                                    <th width="10%" class="text-center">Room</th>
                                    <th width="8%" class="text-center align-middle">Enrolled</th>
                                    <th width="8%" class="text-center align-middle">Percentage</th>
                                    <th width="9%" class="text-center align-middle">Edit Sched</th>
                                </tr>
                            </thead>
                            <tbody class="teachersched">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        
    </div>
    {{-- end of Teacher own schedule --}}

    {{-- View Available Sched --}}
    {{-- <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                
                <div class="row">
                    <div class="col-md-4">
                        <h1 class="card-title text-center"><b>View All Schedules</b></h1>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control form-control-sm select2 syselect" id="select-syid-section">
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control form-control-sm select2" id="select-gradelevel">
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="margin-top: 15px!important;">
                        <table width="100%" class="table table-sm table-bordered table-head-fixed" id="section-list-datatable" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th width="40%">Section Name</th>
                                    <th width="40%">Grade Level</th>
                                    <th class="text-center" width="20%">Schedule</th>
                                </tr>
                            </thead>
                            <tbody class="sectiontbody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    {{--End of View Available Sched --}}
</div>



@endsection
@section('footjs')
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script src="{{asset('plugins/moment/moment.min.js') }}"></script>
<script>
    $(document).on('click','#view_conflict',function(){
        $('#conflict_info_modal').modal()
    })
</script>


<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
    })


    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
        $('#headaddsched').show();
        $('#headupdatesched').hide();
        $('#btn_proceed').show();
        $('#btn_updatesched').hide();
        var iscreate = true;
        var allowconflict = 0;
        var schedinfo = [];
        var scheddatadays = [];
        var sh_subjects = @json($sh_subjects);
        var teacherid = @json($teacherid[0]->id);
        var sem = @json($semester);
        var schedclass = @json($schedclassification);
        var gradelevel = @json($gradelevel);
        var allrooms = @json($rooms);
        var subjcom = @json($subjcom);
        var select2comper = [];
        // console.log(deletedsubjcom);
        var sections = [];
        var selectedsy = null;
        
		teacherfilter_semester();
        getsy();
        comper();
        loadprincipalcomponent()
        //getsubjcom(); // pin 
        teacherdeletedsubjcom();
        
        lodteachersubjcom();
        getallcomponentpercentage();
        
        getschedclassification();
        getrooms();
        // teachersubjectplot();
       
        function comper(){
            
            $.ajax({
                type: "GET",
                url: "/principal/setup/tchrgetallpercom",
                success: function (data) {
                    select2comper = data;
                }
            });
        }
        
        // pin
        // function getsubjcom(){
        //     comper();
        //     $('#select-subjcom').empty()
        //     $('#select-subjcom').append('<option value="">Select Subject Component</option>')
        //     $('#select-subjcom').select2({
        //         data: select2comper,
        //         allowClear : true,
        //         placeholder: 'Select Subject Component'
        //     });

        //     console.log(select2comper);
        // }
        
        function getsubjcom(){
            $('#select-subjcomv2').empty()
            $('#select-subjcomv2').append('<option value="">Select Subject Component</option>')
            $('#select-subjcomv2').append('<option value="addper">Add Component</option>')
            $('#select-subjcomv2').select2({
                data: principalcomponent,
                allowClear : true,
                placeholder: 'Select Subject Component'
            });

            // console.log(select2comper);
        }

        // select2 school year View all schedules
        function getsy(){
            var sy = @json($sy);
            var activesy = sy.filter(x=>x.isactive == 1)[0]
            $('.syselect').empty()
            $('.syselect').select2({
                data: sy,
                allowClear : true,
                placeholder: 'Select School Year'
            });
            $('.syselect').val(activesy.id).change();
            getallteachersched();
            getallsection();
            getgradelevel();
        }


        // select2 grade level
        function getgradelevel(){
            $('#select-gradelevel').empty()
            $('#select-gradelevel').append('<option value="">Select Grade Level</option>')
            $('#select-gradelevel').select2({
                data: gradelevel,
                allowClear : true,
                placeholder: 'Select Grade Level'
            });
        }

        function getrooms(){
            $('#as_room').empty()
            $('#as_room').append('<option value="">Select Room</option>')
            $('#as_room').select2({
                data: allrooms,
                allowClear : true,
                placeholder: 'Select Rooms'
            });
        }
        // select2 grade level
        function getsem(){
            $('#select-sem').empty()
            $('#select-sem').append('<option value="">Select Semester</option>')
            $('#select-sem').select2({
                data: sem,
                allowClear : true,
                placeholder: 'Select Semester'
            });
        }

        function teacherfilter_semester(){

            var activesem = sem.filter(x=>x.isactive == 1)[0];
            $('#teacherfilter_semester').empty()
            $('#teacherfilter_semester').append('<option value="">Select Semester</option>')
            $('#teacherfilter_semester').select2({
                data: sem,
                allowClear : true,
                placeholder: 'Select Semester'
            });
            $('#teacherfilter_semester').val(activesem.id).change();
        }

        // select2 grade level
        function getschedclassification(){
            
            $('#select-schedclass').empty()
            $('#select-schedclass').append('<option value="">Select Sched Classification</option>')
            $('#select-schedclass').select2({
                data: schedclass,
                allowClear : true,
                placeholder: 'Select Sched Classification'
            });
        }

        // load all percentage created by principal
        var principalcomponent = []
        function loadprincipalcomponent(){

            $.ajax({
                type: "GET",
                url: "/setup/subject/componentpercentage",
                success: function (data) {
                    console.log(data);
                    principalcomponent = data
                    getsubjcom()
					teachersched()
                }
            });
        }

        
        // get all the subject component of the teacher
        var teachersubjcom = [];

        function lodteachersubjcom(){

            $.ajax({
                type: "GET",
                url: "/scheduling/teacher/loadteachersubjcom",
                data: {
                    teacherid : teacherid
                },
                success: function (data) {
                    teachersubjcom = data;

                    //console.log('masaya');
                    //console.log(data);
                }
            });
        }
        // =====================================================================

        // get all deleted subject component added by teacher
        var deletedsubjcom = [];
        function teacherdeletedsubjcom(){
            $.ajax({
                type: "GET",
                url: "/scheduling/teacher/teacherdeletedsubjcom",
                // data: "data",
                // dataType: "dataType",
                success: function (data) {
                    deletedsubjcom = data;
                }
            });
        }
        
        // get all teacher added sched
        $(document).on('change', '#select-syid-teachersched', function(){
            getallteachersched();
        })
        $(document).on('change', '#teacherfilter_semester', function(){
            getallteachersched();
            
        })
        var teacherlevellist = [];
        var teacherschedule = [];
        
        function getallteachersched(){
            var syid = $('#select-syid-teachersched').val();
            var schedtype = 'teacher';
            var semid = $('#teacherfilter_semester').val();
			
			
			//console.log(syid);
			//console.log(schedtype);
			//console.log(semid);
			//console.log(teacherid);
            college = null;
            $.ajax({
                type: "GET",
                url: "/scheduling/teacher/schedule",
                data: {
                    syid : syid,
                    teacherid : teacherid,
                    schedtype : schedtype,
                    semid : semid
                },
                success: function (data) {
                    var databasiced = data.filter(x=>x.acadprogid != 6);
                    teacherschedule = databasiced;
					
					//console.log(data);
                    teacherschedule.forEach(function(item){
                        var levelid = item.levelid;
						
						//console.log(levelid)
                        if (!teacherlevellist.includes(levelid)) {
                            // If the id doesn't exist in the array, add it
                            teacherlevellist.push(levelid);
                        }
                        

                    });
                    
                    teachersubjectplot();
                    // teacherdeletedsubjcom();
                }
            });
        }

       
        
        // get all subject plot nga gi assign kang teacher
        var $tchersubjectplot = [];

        function teachersubjectplot(){
            var syid = $('#select-syid-teachersched').val();
            var semid = $('#teacherfilter_semester').val();

            $.ajax({
                type: "GET",
                url: "/scheduling/teacher/getteachersubjectplot",
                data: {
                    teacherlevellist : teacherlevellist,
                    syid : syid,
                    semid : semid
                },
                success: function (data) {
                    tchersubjectplot = data
					console.log(tchersubjectplot)
                    teachersched();
                }
            });
        }
       
        function teachersched(){
            //var teacherscheds = teacherschedule;
			//console.log(teacherschedule)
            $('#schedule-list-datatable').dataTable({
                destroy: true,
                lengthChange: false,
                // scrollX: false,
                autoWidth: false,
                order: false,
                data : teacherschedule,
                columns : [
                    {"data" : null},
                    {"data" : "subjdesc"},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null}
                ], 
                columnDefs : [
                    {
                        'targets': 0,
                        'orderable': true, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a class="mb-0">'+rowData.sectionname+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.levelname+'</p>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle')
                            $(td).css('padding-top', '0px !important')
                            $(td).css('padding-bottom', '0px !important')
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': true,
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var comp = '';
                            var consolidate = ''
                            var spec = ''
                            var type = ''
                            var percentage = ''
                            var visDis = ''
                            
                            if($('#filter_gradelevel').val() != 14 && $('#filter_gradelevel').val() != 15){
                                    if(rowData.isCon == 1){
                                    }

                                    if(rowData.isSP == 1){
                                        spec = '-  <i class="text-danger"> Specialization </i>'
                                    }

                                    if(rowData.subjCom != null){
                                    }

                                    if(rowData.subj_per != 0){
                                        percentage = '-  <i class="text-danger">'+rowData.subj_per+'%</i>'
                                    }

                                    var visDis = '<span class="badge badge-success">V</span>'
                                    if(rowData.isVisible == 0){
                                        visDis = '<span class="badge badge-danger badge-danger">V</span>'
                                    }

                            }else{
                                    if(rowData.type == 1){
                                        type = '-  <i class="text-danger">Core</i>'
                                    }else if(rowData.type == 2){
                                        type = '-  <i class="text-danger">Specialized</i>'
                                    }else if(rowData.type == 3){
                                        type = '-  <i class="text-danger">Applied</i>'
                                    }
                            }

                            var pending = ''
                            if(rowData.with_pending){
                                    pending = '<span class="badge badge-warning">With Pending</span>'
                            }

                            
                            var teacherdeletedsubjcom = deletedsubjcom.filter(x=>x.levelid == rowData.levelid && x.subjid == rowData.subjid)[0];
                            var teachersubjectplotted = tchersubjectplot.filter(x=>x.levelid == rowData.levelid && x.subjid == rowData.subjid)[0];
							//console.log(rowData.levelid)
							//console.log(rowData.subjid)
							//if (teachersubjectplotted.length > 0) {
                                //teachersubjectplotted = teachersubjectplotted[0]
                            //}
                            var principalgradessetup = teachersubjectplotted;
                            
                            // console.log(principalgradessetup);
                            // if (teachersubjectplotted == null) {
                            //     teachersubjectplotted = {
                            //         levelid : '', 
                            //         subjid : ''
                            //     }
                            // }
							//console.log(principalcomponent);
                            var subcomponent = teachersubjcom.filter(x=>x.levelid == rowData.levelid && x.sectionid == rowData.sectionid && x.subjid == rowData.subjid)[0];
							//var subcomponent = principalcomponent.filter(x=>x.id == rowData.subjCom)[0];
                            var subj_num = 'S'+('000'+rowData.subjid).slice (-3)

                            
                            // if (subcomponent == null) {
                            //     if (teacherdeletedsubjcom != null) {
                            //         var text = '<a class="mb-0">'+rowData.subjdesc+' '+comp+' '+pending+' </a><p class="text-muted mb-0" style="font-size:.7rem;">'+rowData.subjcode+' '+type+'</p><p class="text-muted mb-0" style="font-size:.9rem;"></p>';
                            //     } else {
                            //             subcomponent = {
                            //             id : '', 
                            //             description : principalgradessetup.description, 
                            //             levelid : principalgradessetup.levelid, 
                            //             pt : principalgradessetup.pt, 
                            //             qa : principalgradessetup.qa, 
                            //             sectionid : '', 
                            //             subjcomid : '', 
                            //             subjid : principalgradessetup.subjid, 
                            //             syid : principalgradessetup.syid, 
                            //             teacherid : '', 
                            //             ww : principalgradessetup.ww,
                            //             comp4 : principalgradessetup.comp4
                            //         }
                            //         var text = '<a class="mb-0">'+rowData.subjdesc+' '+comp+' '+pending+' </a><p class="text-muted mb-0" style="font-size:.7rem;">'+rowData.subjcode+' '+type+'</p><p class="text-muted mb-0" style="font-size:.9rem;"><span class="badge badge-info mr-2">WW : '+subcomponent.ww+'</span><span class="badge badge-success mr-2">PT : '+subcomponent.pt+'</span><span class="badge badge-warning mr-2">QA : '+subcomponent.qa+'</span><span class="badge badge-secondary mr-2">CG : '+subcomponent.comp4+'</span> <a href="javascript:void(0)" id="btn-removesubjcom"></a></p>';
                            //     }
                                
                            // } else {
                            //     var text = '<a class="mb-0">'+rowData.subjdesc+' '+comp+' '+pending+' </a><p class="text-muted mb-0" style="font-size:.7rem;">'+rowData.subjcode+' '+type+'</p><p class="text-muted mb-0" style="font-size:.9rem;"><span class="badge badge-info mr-2">WW : '+subcomponent.ww+'</span><span class="badge badge-success mr-2">PT : '+subcomponent.pt+'</span><span class="badge badge-warning mr-2">QA : '+subcomponent.qa+'</span><span class="badge badge-secondary mr-2">CG : '+subcomponent.comp4+'</span> <a href="javascript:void(0)" id="btn-removesubjcom" subjcomid="'+subcomponent.subjcomid+'" componentid="'+subcomponent.id+'"><i class="fas fa-trash text-danger"></i></a></p>';
                            // }

                            if (subcomponent == null) {

                                if (principalgradessetup == null) {
                                    var text = '<a class="mb-0">'+rowData.subjdesc+' '+comp+' '+pending+' </a><p class="text-muted mb-0" style="font-size:.7rem;">No Subject Component</p>';
                                } else {

                                    subcomponent = {
                                        id : '', 
                                        description : principalgradessetup.description, 
                                        levelid : principalgradessetup.levelid, 
                                        pt : principalgradessetup.pt, 
                                        qa : principalgradessetup.qa, 
                                        sectionid : '', 
                                        subjcomid : '', 
                                        subjid : principalgradessetup.subjid, 
                                        syid : principalgradessetup.syid, 
                                        teacherid : '', 
                                        ww : principalgradessetup.ww,
                                        comp4 : principalgradessetup.comp4
                                    }
                                    if (subcomponent.comp4 == null) {
                                        var text = '<a class="mb-0" style="font-weight: 400">'+rowData.subjdesc+' '+comp+' '+pending+' </a><p class="text-muted mb-0" style="font-size:.9rem; padding-top: 3px;"><span class="badge badge-dark">SP</span> <span class="badge badge-info mr-2">WW : '+subcomponent.ww+'</span><span class="badge badge-success mr-2">PT : '+subcomponent.pt+'</span><span class="badge badge-warning mr-2">QA : '+subcomponent.qa+'</span><a href="javascript:void(0)" id="btn-removesubjcom"></a></p>';
                                    } else {
                                        var text = '<a class="mb-0" style="font-weight: 400">'+rowData.subjdesc+' '+comp+' '+pending+' </a><p class="text-muted mb-0" style="font-size:.9rem; padding-top: 3px;"><span class="badge badge-dark">SP</span> <span class="badge badge-info mr-2">WW : '+subcomponent.ww+'</span><span class="badge badge-success mr-2">PT : '+subcomponent.pt+'</span><span class="badge badge-warning mr-2">QA : '+subcomponent.qa+'</span><span class="badge badge-secondary mr-2">CG : '+subcomponent.comp4+'</span> <a href="javascript:void(0)" id="btn-removesubjcom"></a></p>';
                                    }
                                    
                                }
                                
                            } else {
                                if (subcomponent.comp4 == null) {
                                    var text = '<a class="mb-0" style="font-weight: 400; font-size: .8rem;">'+rowData.subjdesc+' '+comp+' '+pending+' </a><p class="text-muted mb-0" style="font-size:.9rem; padding-top: 3px;"><span class="badge badge-dark">T</span> <span class="badge badge-info mr-2">WW : '+subcomponent.ww+'</span><span class="badge badge-success mr-2">PT : '+subcomponent.pt+'</span><span class="badge badge-warning mr-2">QA : '+subcomponent.qa+'</span><a href="javascript:void(0)" id="btn-removesubjcom" subjcomid="'+subcomponent.subjcomid+'" componentid="'+subcomponent.id+'"><i class="fas fa-trash text-danger"></i></a></p>';
                                } else {
                                    var text = '<a class="mb-0" style="font-weight: 400">'+rowData.subjdesc+' '+comp+' '+pending+' </a><p class="text-muted mb-0" style="font-size:.9rem; padding-top: 3px;"><span class="badge badge-dark">T</span> <span class="badge badge-info mr-2">WW : '+subcomponent.ww+'</span><span class="badge badge-success mr-2">PT : '+subcomponent.pt+'</span><span class="badge badge-warning mr-2">QA : '+subcomponent.qa+'</span><span class="badge badge-secondary mr-2">CG : '+subcomponent.comp4+'</span> <a href="javascript:void(0)" id="btn-removesubjcom" subjcomid="'+subcomponent.subjcomid+'" componentid="'+subcomponent.id+'"><i class="fas fa-trash text-danger"></i></a></p>';
                                }
                            }

                            // console.log(subcomponent);
							
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle')
                            $(td).css('padding-top', '0px !important')
                            $(td).css('padding-bottom', '0px !important')
                        }
                    },
                    {
                        'targets' : 2,
                        'orderable' : false,
                        'createdCell':  function (td, cellData, rowData, row, col) {
                        var table = 'table-borderless'
                        var multiple = ''

                        if(rowData.schedule.length > 1){
                                table = 'table-bordered'
                                multiple = 'no-border-col'
                        }

                        var text = '<table class="table table-sm mb-0 '+table+'">'
                        $.each(rowData.schedule,function(a,b){
                                text += '<tr style="background-color:transparent !important"><td width="50%" class="'+multiple+'" style="font-size:.7rem">'+b.start + ' - ' + b.end + '<p class="text-muted mb-0" style="font-size:.7rem">'+b.day+'</p></td></tr>'
                        })
                        text += '</table>'
                        $(td)[0].innerHTML =  text
                        $(td).addClass('align-middle')
                        $(td).addClass('p-0')
                        $(td).css('padding-top', '0px !important')
                        $(td).css('padding-bottom', '0px !important')
                        }
                    },
                    {
                        'targets' : 3,
                        'orderable' : false,
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = rowData.schedule[0].roomname
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle')
                            $(td).addClass('text-center')
                            $(td).css('padding-top', '0px !important')
                            $(td).css('padding-bottom', '0px !important')
                        }
                        
                    },
                    {
                        'targets' : 4,
                        'orderable' : false,
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = rowData.enrolled
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle')
                            $(td).addClass('text-center')
                            $(td).css('padding-top', '0px !important')
                            $(td).css('padding-bottom', '0px !important')
                        }
                        
                    },
                    {
                        'targets' : 5,
                        'orderable' : false,
                        'createdCell':  function (td, cellData, rowData, row, col) {

                            var subcomponent = teachersubjcom.filter(x=>x.levelid == rowData.levelid && x.sectionid == rowData.sectionid && x.subjid == rowData.subjid)[0];

                            //console.log(subcomponent);
                            var text = '';
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                            $(td).css('padding-top', '0px !important')
                            $(td).css('padding-bottom', '0px !important')
                            if (subcomponent == null) {
                                $(td)[0].innerHTML = '<div><a href="javascript:void(0)" data-acadprogid="'+rowData.acadprogid+'" data-detailed="'+rowData.schedule[0].detailid+'" data-levelid="'+rowData.levelid+'" data-sectionid="'+rowData.sectionid+'" data-subjid="'+rowData.subjid+'" id="addsubjectcomponent" class="pl-2"><i class="fas fa-percentage" data-toggle="tooltip" data-placement="top" title="Add/Edit Subject Component"></i></div>'
                            } else {
                                $(td)[0].innerHTML = '<div><a href="javascript:void(0)" data-acadprogid="'+rowData.acadprogid+'" data-detailed="'+rowData.schedule[0].detailid+'" data-levelid="'+rowData.levelid+'" data-sectionid="'+rowData.sectionid+'" data-subjid="'+rowData.subjid+'" subjcomid="'+subcomponent.subjcomid+'" headerid="'+subcomponent.headerid+'" teachersubjcomid="'+subcomponent.id+'" id="editsubjectcomponent" class="pl-2"><i class="fas fa-percentage" data-toggle="tooltip" data-placement="top" title="Add/Edit Subject Component"></i></div>'
                            }

                        }
                        
                    },
                    {
                        'targets' : 6,
                        'orderable' : false,
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '';
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                            $(td).css('padding-top', '0px !important')
                            $(td).css('padding-bottom', '0px !important')
                            $(td)[0].innerHTML =  
                            '<div><a href="javascript:void(0)" data-acadprogid="'+rowData.acadprogid+'" data-detailed="'+rowData.schedule[0].detailid+'" data-levelid="'+rowData.levelid+'" data-sectionid="'+rowData.sectionid+'" data-subjid="'+rowData.subjid+'" id="editteachersched" class="pl-2"><i class="text-primary far fa-edit"  data-toggle="tooltip" data-placement="top" title="Edit Schedule"></i></a></div>'
                            
                        }
                        
                    }

                ]
            })
            var label_text = $($('#schedule-list-datatable_wrapper')[0].children[0])[0].children[0]
            $(label_text)[0].innerHTML = '<div class="float-sm-left p-0" style="font-size: .9rem"> '+
                    ' <ul> '+
                        '<li><span class="p-0"><span class="badge badge-dark">SP</span> - Subject Component Created by Principal/Registrar</span></li> '+
                        '<li><span class="p-0"><span class="badge badge-dark">T</span> - Subject Component Created by Teacher</span> </li> '+
                    '</ul> '+
                '</div>'


            
        }
        // end of teacher get added sched



        // click edit in teacher own schedule
        $(document).on('click', '#editteachersched', function(){
            scheddatadays = [];
            $('#headaddsched').hide();
            $('#headupdatesched').show();
            $('#btn_proceed').hide();
            $('#btn_updatesched').show();
            $('#btn_removesched').show();
            $('.day').prop('checked',false);
            
            var update_teacherschedule = teacherschedule;
            
            var levelid = $(this).attr('data-levelid');
            var sectionid = $(this).attr('data-sectionid');
            var subjid = $(this).attr('data-subjid');
            var acadprog = $(this).attr('data-acadprogid');
            
            var scheddata = update_teacherschedule.filter(x=>x.levelid == levelid && x.sectionid == sectionid && x.subjid == subjid)[0];
            var data_detailid = scheddata.schedule[0].detailid; 
            if (scheddata.acadprogid == 5) {
                $('#id_up_strandid').val(scheddata.strand[0].strandid);
            } else {
                $('#id_up_strandid').val(null);
            }
            
            $('#as_gradelevel').val(scheddata.levelname);
            $('#input_as_section').val(scheddata.sectionname);
            $('#as_subject').val(scheddata.subjdesc);

            if (scheddata.schedule[0].roomname == null) {
                getrooms();
            } else {
                $('#as_room').val(scheddata.schedule[0].roomid).change();
            }
            $('#id_up_room').val(scheddata.schedule[0].roomid);

            $('.reservationtime').daterangepicker({
                timePicker: true,
                startDate: scheddata.schedule[0].start,
                endDate: scheddata.schedule[0].end,
                timePickerIncrement: 5,
                locale: {
                    format: 'hh:mm A',
                    cancelLabel: 'Clear'
                }
            })
            
            scheddatadays = update_teacherschedule.filter(x=>x.levelid == levelid && x.sectionid == sectionid && x.subjid == subjid)[0].schedule[0].days;

            console.log(scheddatadays);
            $.each(scheddatadays,function(a,b){
                $('.day[value="'+b+'"]').prop('checked',true)
                
            })

            
            var schedclassval = schedclass.filter(x=>x.text == scheddata.schedule[0].classification)[0].id;
            $('#select-schedclass').val(schedclassval).change();
            
            $('#id_up_gradelevel').val(scheddata.levelid);
            $('#id_up_section').val(scheddata.sectionid);
            $('#id_up_subject').val(scheddata.subjid);
            $('#id_up_room').val(scheddata.schedule[0].roomid);
            $('#id_up_acadprogid').val(acadprog);
            $('#id_up_detailid').val(data_detailid);

            get_sched(sectionid);
            $('#modal-addschedule').modal('show');
        })
        // end of click edit in teacher own schedule




        // teacher own sched update sched 
        $(document).on('click', '#btn_updatesched', function(){
            var valid_data = true;
            var detailid = $('#id_up_detailid').val();
            var acadprogid = $('#id_up_acadprogid').val();
            var strandid =  $('#id_up_strandid').val();
            var copy_com = 0;
            var sectionid = $('#id_up_section').val();
            var time = $('#time').val();
            var subjid = $('#id_up_subject').val();
            var roomid = $('#as_room').val();
            var syid = $('#select-syid-section').val();
            var days = [];
            $('.day').each(function (){
                if ($(this).is(":checked")){
                    days.push($(this).val());
                }
            })
            var schedclass = $('#select-schedclass').val();
            var iscreate = false;
            var levelid = $('#id_up_gradelevel').val();

            if (levelid == 14 || levelid == 15) {
                semid = sh_subjects.filter(x=>x.subjid == subjid && x.levelid == levelid && x.strandid == strandid)[0].semid;
            } else {
                semid = null;
            }
            
            var scheddata = teacherschedule.filter(x=>x.levelid == levelid && x.sectionid == sectionid && x.subjid == subjid)[0];
            var filterd_schedinfo = schedinfo.filter(x=>x.subjid == subjid && x.levelid == levelid && x.detailid == detailid);

            if (schedclass == "") {
                Toast.fire({
                    type: 'error',
                    title: 'No Classification Selected'
                })
                valid_data= false
            }

            if(days.length == 0) {
                Toast.fire({
                    type: 'error',
                    title: 'No days selected!'
                })
                valid_data= false
            }

            if(valid_data){
                var temp_url = acadprogid == 5 ? '/principal/setup/schedule/sh/add' : '/principal/setup/schedule/gshs/add'

                $.ajax({
                    type: "GET",
                    url: temp_url,
                    data: {
                        applcom : copy_com,
                        section : sectionid,
                        t : time,
                        tea : teacherid,
                        s : subjid,
                        r: roomid,
                        days : days,
                        class : schedclass,
                        syid : syid,
                        semid : semid,
                        iscreate : iscreate,
                        allowconflict : allowconflict,
                        schedinfo : filterd_schedinfo
                    },
                    success: function (data) {
                        // if(data[0].status == 1){
                        //     $('#modal-addschedule').modal('hide');
                        //     Toast.fire({
                        //         type: 'success',
                        //         title: 'Successful!'
                        //     })
                        //     getallteachersched();
                        // }else
                        // {
                        //     Toast.fire({
                        //         type: 'warning',
                        //         title: 'Conflict!'
                        //     })
                        // }
                        console.log(data);
                        
                        // $('#modal-schedules').modal('show');
                        if(data[0].status == 1){
                            getallteachersched();
                            getscheduleajax(sectionid, levelid, syid);
                        
                        
                                $('#modal-addschedule').modal('hide');
                                // if(iscreate){
                                //     $('.eval').text('Create Schedule')
                                // }else{
                                //     $('.eval').text('Update Schedule')
                                // }
                                
                                Toast.fire({
                                    type: 'success',
                                    title: 'Successful!'
                                })
                                allowconflict = 0
                                
                                // loadSched()
                                // get_sched()
                                
                                $('#sched_con_holder').attr('hidden','hidden')
                                $('#con_stat').text("")
                                $('#con_sect').text("")
                                $('#con_subj').text("")
                                $('#con_day').text("")
                                $('#con_time').text("")
                                   
                           }else{
                                if(data[0].data == 'conflict'){

                                    $('#conflict_holder').removeAttr('hidden')

                                    $('#sched_con_holder').removeAttr('hidden')

                                    $('.rc_holder').attr('hidden','hidden')
                                    $('.sc_holder').attr('hidden','hidden')
                                    $('.tc_holder').attr('hidden','hidden')
                                    
                                    $('#room_conflict').empty();
                                    $('#section_conflict').empty();
                                    $('#teacher_conflict').empty();

                                    $.each(data[0].details,function(a,b){
                                        console.log(b.conflict)
                                        if(b.conflict == 'TSC'){
                                            $('.tc_holder').removeAttr('hidden')
                                            var text = ` <div class="col-md-6" style="font-size:.7rem !important">
                                                            <div class="card shadow">
                                                                <div class="card-body p-1">
                                                                    <p class="mb-0">Subject: `+b.subject+`</p>
                                                                    <p class="mb-0">Section: `+b.section+`</p>
                                                                    <p class="mb-0">Day: `+b.days+`</p>
                                                                </div>
                                                            </div>
                                                        </div>`

                                            $('#teacher_conflict').append(text)
                                        }else if(b.conflict == 'RSC'){
                                            $('.rc_holder').removeAttr('hidden')
                                            var text = ` <div class="col-md-6" style="font-size:.7rem !important">
                                                            <div class="card shadow">
                                                                <div class="card-body p-1">
                                                                    <p class="mb-0">Subject: `+b.subject+`</p>
                                                                    <p class="mb-0">Section: `+b.section+`</p>
                                                                    <p class="mb-0">Day: `+b.days+`</p>
                                                                </div>
                                                            </div>
                                                        </div>`

                                            $('#room_conflict').append(text)
                                        }else if(b.conflict == 'SSC'){
                                            $('.sc_holder').removeAttr('hidden')
                                            var text = ` <div class="col-md-6" style="font-size:.7rem !important">
                                                            <div class="card shadow">
                                                                <div class="card-body p-1">
                                                                    <p class="mb-0">Subject: `+b.subject+`</p>
                                                                    <p class="mb-0">Day: `+b.days+`</p>
                                                                </div>
                                                            </div>
                                                        </div>`

                                            $('#section_conflict').append(text)
                                        }
                                    })
                                
                                    Toast.fire({
                                            type: 'error',
                                            title: 'Schedule Conflict'
                                    })
                                    if(iscreate){
                                        $('.eval').text('Conflict : Proceed Create')
                                    }else{
                                        $('.eval').text('Conflict : Proceed Update')
                                    }
                                    // $('.eval').text('Conflict : Proceed Update')
                                    allowconflict = 1
                                } else {
                                    Toast.fire({
                                            type: 'error',
                                            title: data[0].data
                                    })
                                    $('.eval').text('Update Schedule')
                                    allowconflict = 0
                                }
                        }
                        
                    }
                });
            }
            
            
        })


        function get_sched(sectionid){

        var syid = $('#select-syid-teachersched').val();
        var semid = sem.filter(x=>x.isactive == 1)[0].id;
        var sectionid = sectionid;

        console.log("sectionid" + sectionid);

        $.ajax({
            type: "GET",
            url: "/principal/setup/schedule/get/sched",
            data: {
                sectionid : sectionid,
                syid : syid,
                semid : semid,
                schedtype :'section',
                // timetemp : $('#filter_timetemplate').val()
            },
            success: function (data) {
                schedinfo = data[0].sched;
            }
        });

        }

        $(document).on('click', '#btn_removesched', function(){
            var detailid =  $('#id_up_detailid').val();
            var levelid = $('#id_up_gradelevel').val();

            var temp_schedinfo = schedinfo.filter(x=>x.detailid == detailid)
            // console.log(detailid);
            console.log(temp_schedinfo);
            var days = [];
            $('.day').each(function(){
                if($(this).is(":checked")){
                        days.push($(this).val())
                }
            })
            // console.log(days);
            var copy_com = 0;
            // return false;
            Swal.fire({
                text: 'Are you sure you want to remove schedule?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                    type:'GET',
                    url:'/principal/setup/schedule/removesched',
                    data:{
                        applycom : copy_com,
                        days : days,
                        dataid: detailid,
                        levelid: levelid
                    },
                    success:function(data) {
                        if(data[0].status == 1){
                            Toast.fire({
                                    type: 'success',
                                    title: data[0].message
                            })
                            getallteachersched();
                            $('#modal-addschedule').modal('hide')
                            
                        }else{
                            Toast.fire({
                                    type: 'success',
                                    title: data[0].message
                            })
                        }
                    }
                })
                }
            })
        })
        // end teacher own sched update sched 

        


        




        // display all the schedules
        $(document).on('change', '#select-syid-section', function(){
            getallsection();
        })
        $(document).on('change', '#select-gradelevel', function(){
            getallsection();
        })
        
        function getallsection(){
            var syid = $('#select-syid-section').val();
            var gradelevelid = $('#select-gradelevel').val();

            // console.log(syid);
            // console.log(gradelevelid);
            $.ajax({
                type: "GET",
                url: "/principal/setup/schedule/get/getallsections",
                data: {
                    syid : syid,
                    gradelevelid : gradelevelid
                },
                success: function(data) {
                    sections = data;
                    // console.log(sections);
                    sectionlist();
                }
            });
        }
        
        function sectionlist(){
            
            var sections_in_viewallschedules = sections;
            
            // console.log(sections_in_viewallschedules);
            $('#section-list-datatable').dataTable({
                destroy: true,
                lengthChange: false,
                scrollX: false,
                autoWidth: false,
                order: false,
                data : sections,
                columns : [
                    {"data" : "sectionname"},
                    {"data" : null},
                    {"data" : null}
                ], 
                columnDefs : [
                    {
                        'targets': 0,
                        'orderable': true, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            $(td).addClass('align-middle p-1')
                            var text = '<a class="mb-0" style="padding: 0px 5px 0px 5px !important">'+rowData.sectionname+'</a>';
                            $(td)[0].innerHTML =  text
                            
                            
                        }
                    },
                    {
                        'targets' : 1,
                        'orderable' : false,
                        'createdCell' : function (td, cellData, rowData, row, col){
                            $(td).addClass('align-middle p-1')
                            var text = '<a class="mb-0" style="padding: 0px 5px 0px 5px !important">'+rowData.levelname+'</a>';
                            $(td)[0].innerHTML =  text
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': true, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '';
                            $(td).addClass('text-center p-1')
                            $(td).addClass('align-middle')
                            $(td)[0].innerHTML =  '<a class="btn btn-primary text-light" id="btn_viewschedules_id" data-roomid="'+rowData.roomid+'" data-roomname="'+rowData.roomname+'" data-levelid="'+rowData.levelid+'" data-section="'+rowData.sectionname+'" data-id="'+rowData.sectionid+'" style="font-size:.8rem; href="javascript:void(0)" style="padding: 2px 0px 2px 0px !important">view sched</a>'
                            
                        }
                    }

                ]
            })
        }

        // end of data tables view all schedules 



        // when  view sched button is click
        var availableschedules = [];
        
        $(document).on('click', '#btn_viewschedules_id', function(){
            $('#headaddsched').show();
            $('#headupdatesched').hide();
            $('#btn_proceed').show();
            $('#btn_updatesched').hide();
            $('#conflict_holder').attr('hidden','hidden')
            var as_section = $(this).attr('data-section');
            var as_sectionid = $(this).attr('data-id');
            // var as_room = $(this).attr('data-roomname');
            // var as_roomid = $(this).attr('data-roomid');
            var as_glevel_id = $(this).attr('data-levelid');

           
           var namesection = sections.filter(x=>x.sectionid == as_sectionid)[0].sectionname;
            
           console.log("sections");
           console.log(namesection);
           console.log("sections");
            $('#as_section').text(as_section);
            $('#jhs_section').text(namesection);
            $('#input_as_section').val(as_section);
            $('#id_as_section').val(as_sectionid);
            // $('#id_as_room').val(as_roomid);
            // $('#as_room').val(as_room);
            $('#as_glevel_id').val(as_glevel_id);
            // console.log(as_room);
            

            
            
            var btn_viewschedules_id = $(this).attr('data-id');
            var levelid = sections.filter(x=>x.sectionid == btn_viewschedules_id)[0].levelid;
            var syid = $('#select-syid-section').val();
            if (levelid >= 14) {
                $('.shssched').show();
                $('.jhssched').hide();
                $('#modal-schedules').modal('show');
            } else {
                $('.shssched').hide();
                $('.jhssched').show();
                $('#modal-schedules').modal('show');
            }
            

            $('#schedulesectionid').val(btn_viewschedules_id);
            getscheduleajax(btn_viewschedules_id, levelid, syid);
            
        })

        function getscheduleajax(btn_viewschedules_id, levelid, syid){
            $.ajax({
                type: "GET",
                url: "/principal/setup/schedule/get/allsectionchedsules",
                data: {
                    btn_viewschedules_id : btn_viewschedules_id,
                    levelid : levelid,
                    syid : syid
                },
                success: function (data) {
                    availableschedules = data;
                    getsem();
                    availableschedulesdatatables();
                    
                }
            });
        }

        // if SHS get subjects by sem
        $(document).on('change', '#select-sem', function(){
            avschedinsection();
        }) 
        function avschedinsection(){
            var sectionid = $('#id_as_section').val();
            var semid = $('#select-sem').val();
            var levelid = $('#as_glevel_id').val();
            var syid = $('#select-syid-section').val();

            // console.log('semimd' + ' ' + semid);
            // console.log('sectionid' + ' ' + sectionid);
            // console.log('syid' + ' ' + syid);
            // console.log('level id' + ' ' + levelid);


            $.ajax({
                type: "GET",
                url: "/principal/setup/schedule/get/shssubjectbysem",
                data: {
                    sectionid : sectionid,
                    semid : semid,
                    levelid : levelid,
                    syid : syid
                },
                success: function (data) {
                    availableschedules = data;
                    availableschedulesdatatables();
                }
            });
        }
        // ===================================



        
        // available schedules datatables
        function availableschedulesdatatables(){
            var subjects_in_viewallschedules = availableschedules
            $('#view-sched-datatable').dataTable({
                destroy: true,
                lengthChange: true,
                scrollX: true,
                autoWidth: true,
                order: false,
                data : subjects_in_viewallschedules,
                columns : [
                    {"data" : "subjdesc"},
                    {"data" : "teacher"},
                    {"data" : null}
                ], 
                columnDefs : [
                    {
                        'targets': 0,
                        'orderable': true, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            $(td).addClass('align-middle p-1')
                            var text = '<a class="mb-0" style="padding: 0px 5px 0px 5px !important">'+rowData.subjdesc+'</a>';
                            $(td)[0].innerHTML =  text
                            
                            
                        }
                    },
                    {
                        'targets' : 1,
                        'orderable' : false,
                        'createdCell' : function (td, cellData, rowData, row, col){
                            $(td).addClass('align-middle p-1')
                            var text = '<a class="mb-0" style="padding: 0px 5px 0px 5px !important">'+rowData.teacher+'</a>';
                            $(td)[0].innerHTML =  text
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': true, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '';
                            $(td).addClass('text-center p-1')
                            $(td).addClass('align-middle')
                            if(rowData.teacher == "no teacher assign"){
                                if(rowData.subjdesc == 'MAPEH' || rowData.subjdesc == 'mapeh' || rowData.subjdesc == 'Mapeh'){
                                    $(td)[0].innerHTML =  text
                                } else {
                                    $(td)[0].innerHTML =  '<a class="btn btn-primary text-light add_sched" id="btn_addsched" data-acadid="'+rowData.acadprogid+'" data-gradelevelid="'+rowData.levelid+'" data-subjid="'+rowData.subjid+'" data-gradelevel="'+rowData.levelname+'" data-subject="'+rowData.subjdesc+'" data-semid="'+rowData.semid+'" style="font-size:.8rem; href="javascript:void(0); padding: 2px 0px 2px 0px !important">Add sched</a>'
                                }
                            } else {
                                if(rowData.subjdesc == 'MAPEH' || rowData.subjdesc == 'mapeh' || rowData.subjdesc == 'Mapeh'){
                                    $(td)[0].innerHTML =  text
                                } else {
                                    $(td)[0].innerHTML =  '<a class="btn btn-success text-light" style="font-size:.8rem; href="javascript:void(0); padding: 2px 0px 2px 0px !important">Assigned</a>'
                                }
                            }
                            
                        }
                    }

                ]
            });
        }
        // =====================================================================================================================






        // when button add sched is click
        $(document).on('click', '#btn_addsched', function(){
            $('.day').prop('checked',false);
            $('#select-schedclass').val();
            $('#headaddsched').show();
            $('#headupdatesched').hide();
            $('#btn_proceed').show();
            $('#btn_updatesched').hide();
            $('#btn_removesched').hide();
            
            // var semid = $(this).attr('data-semid');
            var gradelevel = $(this).attr('data-gradelevel');
            var subjectdesc = $(this).attr('data-subject');
            var gradelevelid = $(this).attr('data-gradelevelid');
            var subjid = $(this).attr('data-subjid');
            var acadprogid = $(this).attr('data-acadid');
            var levelid = $('#as_glevel_id').val();

            var roomid = availableschedules.filter(x=>x.levelid == gradelevelid && x.subjid == subjid)[0].roomid;

             console.log(roomid);
            if (roomid == null){
                // $('#as_room').attr("disabled", false); 
                getrooms();
            } else {
                // $('#as_room').attr("disabled", true); 
                var room_id = allrooms.filter(x=>x.id == roomid)[0].id;

                $('#as_room').val(room_id).change();
                
            }
            $('#id_as_room').val(roomid);


            if (gradelevelid == 14 || gradelevelid == 15) {
                var semid = availableschedules.filter(x=>x.levelid == gradelevelid && x.subjid == subjid)[0].semid;
            } else {
                var semid = null;
            }
            $('.eval').text('Proceed')
            // $('.eval').text('Conflict : Proceed Update')
            // console.log('add sched daw');
            // console.log(availableschedules);
            // console.log("semid" + " " +semid);
            // console.log('add sched daw');

            $('.reservationtime').daterangepicker({
                timePicker: true,
                startDate: '07:30 AM',
                endDate: '08:30 AM',
                timePickerIncrement: 5,
                locale: {
                    format: 'hh:mm A',
                    cancelLabel: 'Clear'
                }
            })
            
            $('#time').removeAttr('disabled')
            $('#as_gradelevel').val(gradelevel);
            $('#as_subject').val(subjectdesc);
            $('#id_as_gradelevel').val(gradelevelid);
            $('#id_as_subject').val(subjid);
            $('#id_as_acadprogid').val(acadprogid);
            $('#id_as_semid').val(semid);
            $('#modal-addschedule').modal('show');
            
        })



        // when proceed button is click
        $(document).on('click', '#btn_proceed', function(){
            var valid_data = true
            var gradelevelid = $('#id_as_gradelevel').val();
            var subjid = $('#id_as_subject').val();
            var sectionid = $('#id_as_section').val();
      
            var room_id = $('#id_as_room').val();
            
            if (room_id == '' || null) {
                var roomid = $('#as_room').val();
            }else {
                var roomid = $('#id_as_room').val();
            }
            var acadprogid = $('#id_as_acadprogid').val();
            var copy_com = 0;

            var schedulesectionid = $('#schedulesectionid').val();
            var syid = $('#select-syid-section').val();

            if (gradelevelid == 14 || gradelevelid == 15) {
                var semid = sem.filter(x=>x.isactive == 1)[0].id;
            } else {
                var semid = null;
            }
            
            var select_schedclass = $('#select-schedclass').val();
            var time = $('#time').val();
            var days = [];
            $('.day').each(function (){
                if ($(this).is(":checked")){
                    days.push($(this).val());
                }
            })

            if (select_schedclass == "") {
                Toast.fire({
                    type: 'error',
                    title: 'No Classification Selected'
                })
                valid_data= false
            }

            if(days.length == 0) {
                Toast.fire({
                    type: 'error',
                    title: 'No days selected!'
                })
                valid_data= false
            }
            // return false;
            if(valid_data){
                var temp_url = acadprogid == 5 ? '/principal/setup/schedule/sh/add' : '/principal/setup/schedule/gshs/add'

                $.ajax({
                    type: "GET",
                    url: temp_url,
                    data: {
                        applcom : copy_com,
                        section : sectionid,
                        t : time,
                        tea : teacherid,
                        s : $('#id_as_subject').val(),
                        r: roomid,
                        days : days,
                        class : select_schedclass,
                        syid : $('#select-syid-section').val(),
                        semid :  semid,
                        iscreate : iscreate,
                        allowconflict : allowconflict,
                        schedinfo : schedinfo
                    },
                    success: function (data) {
                        console.log(data);
                        
                        // $('#modal-schedules').modal('show');
                        if(data[0].status == 1){
                            getallteachersched();
                            getscheduleajax(schedulesectionid, gradelevelid, syid);
                        
                        
                                $('#modal-addschedule').modal('hide');
                                // if(iscreate){
                                //     $('.eval').text('Create Schedule')
                                // }else{
                                //     $('.eval').text('Update Schedule')
                                // }
                                
                                Toast.fire({
                                    type: 'success',
                                    title: 'Successful!'
                                })
                                allowconflict = 0
                                
                                // loadSched()
                                // get_sched()
                                
                                $('#sched_con_holder').attr('hidden','hidden')
                                $('#con_stat').text("")
                                $('#con_sect').text("")
                                $('#con_subj').text("")
                                $('#con_day').text("")
                                $('#con_time').text("")
                                   
                        }else{
                            if(data[0].data == 'conflict'){

                                $('#conflict_holder').removeAttr('hidden')

                                $('#sched_con_holder').removeAttr('hidden')

                                $('.rc_holder').attr('hidden','hidden')
                                $('.sc_holder').attr('hidden','hidden')
                                $('.tc_holder').attr('hidden','hidden')
                                
                                $('#room_conflict').empty();
                                $('#section_conflict').empty();
                                $('#teacher_conflict').empty();

                                $.each(data[0].details,function(a,b){
                                    console.log(b.conflict)
                                    if(b.conflict == 'TSC'){
                                        $('.tc_holder').removeAttr('hidden')
                                        var text = ` <div class="col-md-6" style="font-size:.7rem !important">
                                                        <div class="card shadow">
                                                            <div class="card-body p-1">
                                                                <p class="mb-0">Subject: `+b.subject+`</p>
                                                                <p class="mb-0">Section: `+b.section+`</p>
                                                                <p class="mb-0">Day: `+b.days+`</p>
                                                            </div>
                                                        </div>
                                                    </div>`

                                        $('#teacher_conflict').append(text)
                                    }else if(b.conflict == 'RSC'){
                                        $('.rc_holder').removeAttr('hidden')
                                        var text = ` <div class="col-md-6" style="font-size:.7rem !important">
                                                        <div class="card shadow">
                                                            <div class="card-body p-1">
                                                                <p class="mb-0">Subject: `+b.subject+`</p>
                                                                <p class="mb-0">Section: `+b.section+`</p>
                                                                <p class="mb-0">Day: `+b.days+`</p>
                                                            </div>
                                                        </div>
                                                    </div>`

                                        $('#room_conflict').append(text)
                                    }else if(b.conflict == 'SSC'){
                                        $('.sc_holder').removeAttr('hidden')
                                        var text = ` <div class="col-md-6" style="font-size:.7rem !important">
                                                        <div class="card shadow">
                                                            <div class="card-body p-1">
                                                                <p class="mb-0">Subject: `+b.subject+`</p>
                                                                <p class="mb-0">Day: `+b.days+`</p>
                                                            </div>
                                                        </div>
                                                    </div>`

                                        $('#section_conflict').append(text)
                                    }
                                })
                            
                                Toast.fire({
                                        type: 'error',
                                        title: 'Schedule Conflict'
                                })
                                if(iscreate){
                                    $('.eval').text('Conflict : Proceed Create')
                                }else{
                                    $('.eval').text('Conflict : Proceed Update')
                                }
                                // $('.eval').text('Conflict : Proceed Update')
                                allowconflict = 1
                            } else {
                                Toast.fire({
                                        type: 'error',
                                        title: data[0].data
                                })
                                $('.eval').text('Update Schedule')
                                allowconflict = 0
                            }
                        }
                    }
                });
            }
        })
        $('#modal-addschedule').on('hide.bs.modal', function (e) {
            // $("#audsched").load(window.location + " #audsched");
            $('#conflict_holder').attr('hidden','hidden');
        })

        // adding subject component
        $(document).on('click', '#addsubjectcomponent', function(){
            $('#btn-savesubjcom').html('<i class="fas fa-plus"></i> Add')
            $('#btn-savesubjcom').attr('id', 'btn-addsubjcom')
            $('#btn-addsubjcom').removeClass('btn-success')
            $('#btn-addsubjcom').addClass('btn-primary')

            
            var levelid = $(this).attr('data-levelid');
            var sectionid = $(this).attr('data-sectionid');
            var subjid = $(this).attr('data-subjid');
            var syid = $('#select-syid-teachersched').val();
            var acadprog = $(this).attr('data-acadprogid');
            if (levelid == 14 || levelid == 15) {
                var semid = sh_subjects.filter(x=>x.levelid == levelid && x.subjid == subjid)[0].semid;
            } else {
                var semid = null;
            }
            getsubjcom();
            $('#id_asc_levelid').val(levelid);
            $('#id_asc_sectionid').val(sectionid);
            $('#id_asc_subjid').val(subjid);
            $('#id_asc_syid').val(syid);
            $('#id_asc_semid').val(semid);
            $('#id_asc_techerid').val(teacherid);

            $('#modal-subjectcomponent').modal('show');
        })

        // when component percentage button is click
        $(document).on('click', '#btn-componentpercentage', function() {
            $('#modal-componentpercentage').modal('show');
            getallcomponentpercentage();
        })
        // pin componentpercentage v1
        

        // when create + Create Component Percentage is Click
        $(document).on('click', '#btn-addcomponentpercentage', function() {
            $('#edit_component_percentage').html('<i class="fas fa-plus"></i> Add')
            $('#edit_component_percentage').attr('id', 'create_component_percentage')
            $('#create_component_percentage').removeClass('btn-success')
            $('#create_component_percentage').addClass('btn-primary')
            
            $('#modal-addcomponentpercentage').modal('show');
            $('#comp1').val("")
            $('#comp2').val("")
            $('#comp3').val("")
            $('#comp4').val("")
            $('#id_comper').val("")
            $('#id_teacherid').val("")
        })

        // click edit icon in Subject Component Percentage
        $(document).on('click', '.edit_comp', function() {
            var percomdetailid = $(this).attr('data-id');
            var percom_data = tchrcompercentage.filter(x=>x.id == percomdetailid)

            console.log(percom_data);
            $('#create_component_percentage').html('<i class="fas fa-save"></i> Save')
            $('#create_component_percentage').attr('id', 'edit_component_percentage')
            $('#edit_component_percentage').removeClass('btn-primary')
            $('#edit_component_percentage').addClass('btn-success')

            $('#comp1').val(percom_data[0].ww)
            $('#comp2').val(percom_data[0].pt)
            $('#comp3').val(percom_data[0].qa)
            $('#comp4').val(percom_data[0].comp4)
            $('#id_comper').val(percomdetailid)
            $('#id_teacherid').val(percom_data[0].teacherid)

            
            $('#modal-addcomponentpercentage').modal('show');
        })

        $(document).on('click', '#edit_component_percentage', function() {
            
            var ww = $('#comp1').val()
            var pt = $('#comp2').val()
            var qa = $('#comp3').val()
            var ct = $('#comp4').val()
            var percomid = $('#id_comper').val()
            var teacherid = $('#id_teacherid').val()
            
            var total = parseInt(0)
                total += $('#comp1').val() != "" ? parseInt($('#comp1').val()) : 0
                total += $('#comp2').val() != "" ? parseInt($('#comp2').val()) : 0
                total += $('#comp3').val() != "" ? parseInt($('#comp3').val()) : 0
                total += $('#comp4').val() != "" ? parseInt($('#comp4').val()) : 0

            if(total != 100){
                Toast.fire({
                    type: 'warning',
                    title: 'Should equal to 100'
                })
                return false;
            }

            $.ajax({
                type: "GET",
                url: "/principal/setup/tchreditpercentagecomponent",
                data: {
                    ww : ww,
                    qa : qa,
                    pt : pt,
                    ct : ct,
                    percomid : percomid,
                    teacherid : teacherid
                },
                success: function (data) {
                    if(data[0].status == 0){
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }else{
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                        getallcomponentpercentage();
                        getallteachersched();
                        lodteachersubjcom();

                        $('#modal-addcomponentpercentage').modal('hide')
                    }
                }
            });
        })

        $(document).on('click', '.delete_comp', function() {
            var percomid = $(this).attr('data-id');
            var teacherid = $(this).attr('data-teacherid');

            $.ajax({
                type: "GET",
                url: "/principal/setup/tchrdeletepercentagecomponent",
                data: {
                    percomid : percomid,
                    teacherid : teacherid
                },
                success: function (data) {
                    if(data[0].status == 0){
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }else{
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                        getallcomponentpercentage();
                        getallteachersched();
                        lodteachersubjcom();

                        $('#modal-addcomponentpercentage').modal('hide')
                    }
                }
            });
        });
        
        // click create button
        $(document).on('click', '#create_component_percentage', function() {
            var ww_input = $('#comp1').val()
            var pt_input = $('#comp2').val()
            var qa_input = $('#comp3').val()
            var ct_input = $('#comp4').val()
            
            var total = parseInt(0)
                total += $('#comp1').val() != "" ? parseInt($('#comp1').val()) : 0
                total += $('#comp2').val() != "" ? parseInt($('#comp2').val()) : 0
                total += $('#comp3').val() != "" ? parseInt($('#comp3').val()) : 0
                total += $('#comp4').val() != "" ? parseInt($('#comp4').val()) : 0

            if(total != 100){
                    Toast.fire({
                        type: 'warning',
                        title: 'Should equal to 100'
                    })
                    return false;
            }

            $.ajax({
                type: "GET",
                // url: "/principal/setup/tchraddpercentagecomponent",
                url: "/setup/subject/componentpercentage/create",
                data: {
                    ww_input : ww_input,
                    qa_input : qa_input,
                    pt_input : pt_input,
                    ct_input : ct_input,
                    teacherid : teacherid
                },
                success:function(data) {
                    if(data[0].status == 0){
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }else{
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                        // pin
                        // getallcomponentpercentage()
                        // comper()
                        loadprincipalcomponent()
                        teachersched();
                        loadprincipalcomponent()
                        $('#modal-addcomponentpercentage').modal('hide')
                    }
                }
            });
                
        })
        
        //  get all Component Percentage Created by Teacher
        var tchrcompercentage = [];
        function getallcomponentpercentage(){
            
            $.ajax({
                type: "GET",
                url: "/principal/setup/tchrgetallpercentagecomponent",
                data: {
                    teacherid : teacherid
                },
                success: function (data) {
                    tchrcompercentage = data;
                    subjectcomponent_table();
                    // getallteachersched();
                }
            });

        }

        // Table for Subject Component Percentage

        function subjectcomponent_table(){

            var tchrcompercentagelist = tchrcompercentage

            $('#subjectcomponent_table').DataTable({
                destroy: true,
                lengthChange: false,
                scrollX: true,
                autoWidth: true,
                order: false,
                data : tchrcompercentagelist,
                columns : [
                    {"data" : "description"},
                    {"data" : "ww"},
                    {"data" : "pt"},
                    {"data" : "qa"},
                    {"data" : "comp4"},
                    {"data" : null},
                    {"data" : null}
                ], 
                columnDefs: [
                    {
                            'targets': 0,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td).addClass('align-middle')
                                $(td).text(rowData.description)
                            }
                    },
                    {
                            'targets': 1,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td).addClass('align-middle text-center')
                            }
                    },
                    {
                            'targets': 2,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td).addClass('align-middle text-center')
                            }
                    },
                    {
                            'targets': 3,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td).addClass('align-middle text-center')
                            }
                    },
                    {
                            'targets': 4,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td).addClass('align-middle text-center')
                            }
                    },
                    {
                            'targets': 5,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                var buttons = '<a href="javascript:void(0)" class="edit_comp" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                $(td)[0].innerHTML =  buttons
                                $(td).addClass('text-center')
                                $(td).addClass('align-middle')
                                
                            }
                    },
                    {
                            'targets': 6,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                var disabled = '';
                                var buttons = '<a href="javascript:void(0)" '+disabled+' class="delete_comp" data-teacherid="'+rowData.teacherid+'" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                $(td)[0].innerHTML =  buttons
                                $(td).addClass('text-center')
                                $(td).addClass('align-middle')
                            }
                    },
                ]
            })
            var label_text = $($('#subjectcomponent_table_wrapper')[0].children[0])[0].children[0]
            $(label_text)[0].innerHTML = '<button class="btn btn-info btn-sm" id="btn-addcomponentpercentage"><i class="fas fa-plus"></i> Create Component Percentage</button>'
           }



        // proceed adding subject component 
        $(document).on('click', '#btn-addsubjcom', function (){
            var headerid = $('#select-subjcomv2').select2('data')[0].id;

            var valid_data = true;
            var levelid = $('#id_asc_levelid').val();
            var sectionid = $('#id_asc_sectionid').val();
            var subjid = $('#id_asc_subjid').val();
            var syid = $('#id_asc_syid').val();
            var semid = $('#id_asc_semid').val();
            var teacherid = $('#id_asc_techerid').val();
            var subjcomid = $('#select-subjcomv2').val();
			
            //return false;
			console.log(subjcomid);
            Swal.fire({
                text: 'Are you sure you want to add Component?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Add'
            }).then((result) => {


                if (result.value) {

                    if (subjcomid == "") {
                    Toast.fire({
                        type: 'error',
                        title: 'No Subject Component Selected'
                    })
                    valid_data= false

                    } else {

                        $.ajax({
                            type: "GET",
                            url: "/principal/setup/tchraddsubjectcomponent",
                            data: {
                                levelid : levelid,
                                sectionid : sectionid,
                                subjid : subjid,
                                syid : syid,
                                semid : semid,
                                teacherid : teacherid,
                                headerid : headerid,
                                subjcomid : subjcomid
                            },
                            success: function (data) {
                                if(data){
                                    Toast.fire({
                                            type: 'success',
                                            title: 'Successfully Added'
                                    })
                                    getallteachersched();
                                    lodteachersubjcom();
                                    $('#modal-subjectcomponent').modal('hide')
                                    
                                }else{
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Already Exist'
                                    })
                                }
                            }
                        });

                    }
                  
                }
            })

        })
        // =====================================================================================================
        $(document).on('click', '#editsubjectcomponent', function (){
            // getallcomponentpercentage();
            // getsubjcom();
            // loadprincipalcomponent()
            $('#btn-addsubjcom').html('<i class="fas fa-save"></i> Save')
            $('#btn-addsubjcom').attr('id', 'btn-savesubjcom')
            $('#btn-savesubjcom').removeClass('btn-primary')
            $('#btn-savesubjcom').addClass('btn-success')
            // $('#btn-removesubjcom').show();
            var subjcomid = $(this).attr('subjcomid')
            var teachersubjcomid = $(this).attr('teachersubjcomid')
            var headerid = $(this).attr('headerid')
            
            console.log('sssssssssss' + ' '+ subjcomid);
            $('#id_asc_teachersubjcomid').val(teachersubjcomid);
            $('#id_asc_headerid').val(headerid);
            $('#subjcomidd').val(subjcomid);
            
            $('#select-subjcomv2').val(subjcomid).change();
            $('#modal-subjectcomponent').modal('show')
        });

        // save newly edited subject component added by teacher
        $(document).on('click', '#btn-savesubjcom', function (){
            var teachersubjcomid = $('#id_asc_teachersubjcomid').val();
            var subjcomid = $('#select-subjcomv2').val();
            var headerid = $('#id_asc_headerid').val();

            $.ajax({
                type: "GET",
                url: "/scheduling/teacher/editeachersubjcom",
                data: {
                    teachersubjcomid : teachersubjcomid,
                    subjcomid : subjcomid,
                    headerid : headerid
                },
                success: function (data) {
                    if(data){

                        if (subjcomid == "") {
                            Toast.fire({
                                type: 'error',
                                title: 'No Subject Component Selected'
                            })
                        } else {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully Added'
                            })
                            getallteachersched();
                            lodteachersubjcom();
                            $('#modal-subjectcomponent').modal('hide')
                        }
                        
                        
                    }else{
                        Toast.fire({
                            type: 'success',
                            title: 'Already Exist'
                        })
                    }
                }
            });
        });
    
        // remove teacher added subject component
        $(document).on('click', '#btn-removesubjcom', function(){
            var teachersubjcomid = $(this).attr('componentid');
            var subjcomid = $(this).attr('subjcomid');

            
            console.log(teachersubjcomid);
            console.log(subjcomid);

            Swal.fire({
                text: 'Are you sure you want to Remove Component?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {


                if (result.value) {

                    if (subjcomid == "") {
                    Toast.fire({
                        type: 'error',
                        title: 'No Subject Component Selected'
                    })
                    valid_data= false

                    } else {

                        $.ajax({
                            type: "GET",
                            url: "/scheduling/teacher/deleteteachersubjcom",
                            data: {
                                teachersubjcomid : teachersubjcomid,
                                subjcomid : subjcomid
                            },
                            success: function (data) {
                                if(data){
                                    Toast.fire({
                                            type: 'success',
                                            title: 'Removed Successfully'
                                    })
                                    // teacherdeletedsubjcom();
                                    getallteachersched();
                                    lodteachersubjcom();
                                    $("#tchrschedss").load(location.href + " #tchrschedss");
                                    // $('#modal-subjectcomponent').modal('hide')
                                    
                                }else{
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Already Exist'
                                    })
                                }
                            }
                        });

                    }
                  
                }
            })

        })


        $(document).on('click', '#btn_modalviewallsched', function(){
            $('#modal-viewallschedules').modal('show')
        })

        $('#modal-addcomponentpercentage').on('hidden.bs.modal', function (){
            
        })
        $(document).on('change', '#select-subjcomv2', function(){
            if ($(this).val() == 'addper') {
                $('#modal-addcomponentpercentage').modal('show')
                
                $('#btn-savesubjcom').prop('disabled', true)
                $('#select-subjcomv2').val("").change()
            } else if($(this).val() == '' || $(this).val() == null) {
                $('#btn-savesubjcom').prop('disabled', true)
            } else {
                $('#btn-savesubjcom').prop('disabled', false)
            }
        })
        
    })

    
</script>
@endsection
