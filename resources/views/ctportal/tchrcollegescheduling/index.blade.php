
@extends('ctportal.layouts.app2')

@section('pagespecificscripts')
      
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      {{-- <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}"> --}}
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
      <style>
            /* .select2-selection{
                height: calc(2.25rem + 2px) !important;
            } */
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0;
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
            .form-control-sm-form {
                  height: calc(1.4rem + 1px);
                  padding: 0.75rem 0.3rem;
                  font-size: .875rem;
                  line-height: 1.5;
                  border-radius: 0.2rem;
            }
            input[type=search]{
                  height: calc(1.7em + 2px) !important;
            }

            .btn-group-sm>.btn, .btn-sm {
                  padding: 0.25rem 0.5rem;
                  font-size: .7rem;
                  line-height: 1.5;
                  border-radius: 0.2rem;
            }

            .tooltip > .arrow {
                  visibility: hidden;
            }
      </style>


@endsection

@section('content')

@php
      $sy = DB::table('sy')->orderBy('sydesc','desc')->get(); 
      $semester = DB::table('semester')->get(); 
      $schoolinfo = DB::table('schoolinfo')->first()->abbreviation;
      $dean = DB::table('college_colleges')
                  ->join('teacher',function($join){
                        $join->on('college_colleges.dean','=','teacher.id');
                        $join->where('teacher.deleted',0);
                  })
                  ->where('college_colleges.deleted',0)
                  ->select(
                        'teacher.id',
                        DB::raw("CONCAT(teacher.lastname,', ',teacher.firstname) as text")
                  )
                  ->distinct()
                  ->get();

      $gradesetup = DB::table('semester_setup')
                        ->where('deleted',0)
                        ->first();

@endphp


<section class="content-header">
      <div class="container-fluid">
          <div class="row">
              <div class="col-sm-6">
                  <h1>Scheduling</h1>
              </div>
              <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="/home">Home</a></li>
                  <li class="breadcrumb-item active">Scheduling</li>
              </ol>
              </div>
          </div>
      </div>
</section>
<section class="content pt-0">

<!-- Modal when button view sched is click -->
<div class="modal fade" id="modal-schedules">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header jhssched">
                <h5 class="modal-title" id="exportmodalLabel">Add Schedule<span class="text-primary" id="jhs_section" style="font-weight: bold; text-decoration: underline;"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body">
                

                <div class="row">
                    <div class="col-md-12">
                        <table class="table-hover table table-striped table-sm table-bordered" id="collegesection_datatable" width="100%" style="font-size: 12px;">
                              <thead class="thead-light">
                                    <tr>
                                          <th width="3%" rowspan="2"></th>
                                          <th width="12%" rowspan="2" class="p-0 align-middle pl-2 text-center" class="p-0 align-middle pl-2">Section</th>
                                          <th width="28%" rowspan="2" class="align-middle">Subject Description</th>
                                          <th colspan="2" class="p-0 align-middle text-center" width="6%" colspan="2">Units</th>
                                          <th rowspan="2" class="text-center p-0 align-middle" width="4%">Cap.</th>
                                          <th rowspan="2" class="text-center p-0 align-middle" width="6%">Students</th>
                                          <th rowspan="2" width="20%" class="text-center align-middle">Schedule</th>
                                          <th rowspan="2" width="15%" class="text-center align-middle">Instructor</th>
                                          <th rowspan="2" width="6%"></th>
                                    </tr>
                                    <tr>
                                          <th class="text-center p-1 border-right-1" style="font-size:.6rem !important">Lec</th>
                                          <th class="text-center p-1" style="font-size:.6rem !important; border-right: 1px solid #dee2e6;font-size:.6rem !important">Lab</th>
                                    </tr>
                              </thead>
                        </table>
                       
                    </div>
                    <div class="col-md-12"  style="font-size: 12px;">
                            <span class="badge badge-primary">RS</span> - Regular Schedule <span class="ml-2 mr-2"> | </span> <span class="badge badge-warning">SP</span> - Special Schedule
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

{{-- =========================================================================================================== --}}


<!-- Modal when button + Add is click -->

<div class="modal fade" id="modal-add-instructor">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header jhssched">
                <h5 class="modal-title" id="exportmodalLabel">Schedule Instructor<span class="text-primary" id="jhs_section" style="font-weight: bold; text-decoration: underline;"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body">
                
                <div class="form-group">
                    <label for="">Subject</label>
                    <p class="text-muted label-csl-subject"></p>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Teacher</label>
                    <input type="text" class="form-control" id="input_teachername">
                </div>

       
            
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-sm btn-primary btn-block" id="btn-proceedadd-instructor">Proceed</button>
                    </div>
                    <div class="col-md-12 cnflctButtonHolder" hidden>
                        <button class="btn btn-info btn-sm btn-block cnflctBttn" >Conflict List</button>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="conflict_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
          <div class="modal-content modal-sm">
                <div class="modal-header pb-2 pt-2 border-0" >
                      <h4 class="modal-title" style="font-size: 1.1rem !important">Schedule Conflict</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true" >×</span></button>
                </div>
                <div class="modal-body pt-1" style="font-size:.8rem !important" id="cnflctLstHldr">
                      
                </div>
          </div>
    </div>
</div>
{{-- <div class="modal fade" id="modal-add-instructor" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
            <div class="modal-content modal-sm">
                <div class="modal-header border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Schedule Instructor</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" >×</span></button>
                </div>
                <div class="modal-body" style="font-size:.8rem !important" >
                        <div class="row">
                            <div class="col-md-12">
                                    <strong>Subject</strong>
                                    <p class="text-muted label-csl-subject"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                    <label for="">Teacher</label>
                                    <select name="csl_teacher" id="input-csl-teacher" class="form-control select2 form-control-sm"></select>
                            </div>
                        </div>
                    
                        <div class="row">
                            <div class="col-md-7">
                                    <button class="btn btn-sm btn-primary btn-block" id="btn-csl-update-instructor">Update</button>
                            </div>
                            <div class="col-md-5 cnflctButtonHolder" hidden>
                                    <button class="btn btn-info btn-sm btn-block cnflctBttn" >Conflict List</button>
                            </div>
                        </div>
                </div>
            </div>
    </div>
</div> --}}
{{-- =========================================================================================================== --}}





<div class="container-fluid">
    <div class="row">
            <div class="col-md-12">
            <div class="row">
                    <div class="col-md-12">
                        <div class="info-box shadow-lg">
                                <div class="info-box-content">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <h1 class="card-title text-center"><b>Your Schedules</b></h1>
                                        </div>
                                        <div class="col-md-2">
                                            {{-- <label for="">School Year</label> --}}
                                            <select class="form-control form-control-sm select2" id="filter_sy">
                                                    @foreach ($sy as $item)
                                                        @if($item->isactive == 1)
                                                                <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                        @else
                                                                <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                        @endif
                                                    @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            {{-- <label for="">Semester</label> --}}
                                            <select class="form-control form-control-sm  select2" id="filter_semester">
                                                    <option value="">Select semester</option>
                                                    @foreach ($semester as $item)
                                                        <option {{$item->isactive == 1 ? 'selected' : ''}} value="{{$item->id}}">{{$item->semester}}</option>
                                                    @endforeach
                                            </select>
                                            <div class="col-md-2 form-group" hidden>
                                                <label for="">Term</label>
                                                <select class="form-control form-control-sm select2" id="term">
                                                        <option value="">All</option>
                                                        <option value="Whole Sem">Whole Sem</option>
                                                        <option value="1st Term">1st Term</option>
                                                        <option value="2nd Term">2nd Term</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-7 text-right">
                                            <button class="btn btn-primary btn-sm ml-2" id="btn-viewsched">View Sched</button>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 15px;">
                                        <div class="col-md-12" style="font-size:.9rem">
                                            <table class="table table-sm table-striped display table-bordered" id="datatable_1" width="100%" style="font-size: 15px;">
                                                <thead>
                                                        <tr>
                                                            <th width="25%">Schedule</th>
                                                            <th width="25%">Section</th>
                                                            <th width="43%">Subject</th>
                                                            <th width="7%" class="text-center">Remove</th>
                                                        </tr>
                                                </thead>
                                                <tbody  style="font-size: 15px;"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                            <div class="col-md-4">
                                                <button class="btn btn-primary btn-block btn-sm" id="filter_button_1"><i class="fas fa-filter"></i> Filter</button>
                                            </div>
                                    </div> --}}
                                </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
</section>
  
     
@endsection

@section('footerscript')

<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
{{-- <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script> --}}
<script src="{{asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>

      <script>
            var allowButtons = true;
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })
            $('#filter_sy').select2()
            $('#filter_semester').select2()
      </script>

      <script>

            var teacherdata = @json($teacher);
            var allowconflict = 0;
            $('#input_teachername').val(teacherdata.teachername);
            console.log(teacherdata);
            console.log('masaya');
            $(document).ready(function () {
                
                
                // get_schedule();
                
                $(document).on('change', '#filter_sy', function(){
                    var syid = $('#filter_sy').val();
                    get_schedule();
                    console.log("sy"+ " "+ syid);
                })
                $(document).on('change', '#filter_semester', function(){
                    var semid = $('#filter_semester').val();
                    get_schedule();
                    console.log("sem"+ " "+ semid);
                })
                get_schedule();
                var all_sched_teacher = []
                

                $(document).on('click', '#btn-viewsched', function(){
                    $('#modal-schedules').modal('show');
                    console.log('click View Sched button');
                })





                //  ********************* get teacher own sched ***************************
                function get_schedule(){
                    
                    var syid = $('#filter_sy').val();
                    var semid = $('#filter_semester').val();

                    $.ajax({
                            type:'GET',
                            url:'/college/teacher/schedule/get',
                            data:{
                                syid : syid,
                                semid : semid
                            },
                            success:function(data) {
                                all_sched_teacher = data
                                
                                console.log(data)
                                // var d = new Date();
                                // var today = d.getDay()
                                // $('#filter_day').val(today).change()
                                datatable_1()
                            }
                    })
                }
                function datatable_1(){

                var temp_sched = all_sched_teacher
                if($('#term').val() != ""){
                    if($('#term').val() == "Whole Sem"){
                            temp_sched = all_sched_teacher.filter(x=>x.schedotherclass == null)
                    }else{
                            temp_sched = all_sched_teacher.filter(x=>x.schedotherclass == $('#term').val())
                    }
                }

                $("#datatable_1").DataTable({
                    destroy: true,
                    lengthChange: true,
                    scrollX: true,
                    autoWidth: true,
                    order: false,
                    data : temp_sched,
                    columns: [
                            { "data": "sort"},
                            { "data": "search" },
                            { "data": null },
                            { "data": null }
                    ],
                    columnDefs: [
                            {
                                'targets': 0,
                                'orderable': true, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    
                                        var temp_room = '<p class="mb-0">Room: Not Assigned</p>'
                                        
                                        if(rowData.roomname != null){
                                            temp_room = '<p class="mb-0">Room: '+rowData.roomname + '</p>'
                                        }
                                    
                                        

                                        var text = '<a class="mb-0">'+rowData.schedule[0].start+' - '+rowData.schedule[0].end+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.schedule[0].day+'</p>';
                                        $(td)[0].innerHTML =  text + temp_room
                                        $(td).addClass('align-middle')
                                }
                            },
                            {
                                'targets': 1,
                                'orderable': true, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                        var text = '<a class="mb-0">'+rowData.sectionDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.levelname+'<br>'+rowData.courseabrv+'</p>';
                                        $(td)[0].innerHTML =  text
                                        $(td).addClass('align-middle')
                                }
                            },
                            {
                                'targets': 2,
                                'orderable': true, 
                                'createdCell':  function (td, cellData, rowData, row, col) {

                                        var schedotherclass = rowData.schedotherclass != null ? rowData.schedotherclass : 'Whole Semester'

                                        var text = '<a class="mb-0">'+rowData.subjDesc+'</a><p class="text-muted mb-0" style="font-size:13px">'+rowData.subjCode+'</p><i class="mb-0 text-danger" style="font-size:15px">'+schedotherclass+'</i>';
                                        $(td)[0].innerHTML =  text
                                        $(td).addClass('align-middle')
                                }
                            },
                            {
                                'targets': 3,
                                'orderable': true, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                        var text = '<a class="btn btn-danger text-light" style="font-size: 14px;" id="btn-removesched" data-schedid="'+rowData.schedid+'"><i class="nav-icon fas fa-trash-alt text-light"></i> Remove</a>';
                                        $(td)[0].innerHTML =  text
                                        $(td).addClass('align-middle text-center')
                                }
                            }
                        ]
                    })
                }
                // ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------






                //  ********************* get all schedule when View Sched button is Click ***************************
                display_sched_collegesections()

                var all_sched = []
                var all_sched_section = []
                var all_sched_enrolled = []
                var all_sched_detail = []
                var all_sched_student = []
                var all_sched_groupdetail = []
                var seleted_id = null
                var selected_detail = []
                var firstPrompt = true

                function display_sched_collegesections(){

                    $("#collegesection_datatable").DataTable({
                        destroy: true,
                        autoWidth: false,
                        stateSave: true,
                        lengthChange : false,
                        serverSide: true,
                        processing: true,
                        ajax:{
                                url: '/student/loading/allsched',
                                type: 'GET',
                                data: {
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    filtersubjgroup:$('#filter_schedulegroup').val(),
                                    filterroom:$('#filter_room').val(),
                                    filterteacher:$('#filter_teacher').val(),
                                    filterclasstype:$('#filter_classtype').val(),
                                },
                                dataSrc: function ( json ) {
                                    all_sched = json.data[0].college_classsched
                                    all_sched_section = json.data[0].section
                                    all_sched_enrolled = json.data[0].enrolled
                                    all_sched_detail = json.data[0].scheddetail
                                    all_sched_student = json.data[0].all_stud_sched
                                    all_sched_groupdetail = json.data[0].sched_group_detail

                                    console.log(all_sched);
                                    if(seleted_id != null){
                                            empty_form()
                                            csl_sched_form_detail()
                                    }

                                    if(firstPrompt){
                                            
                                            Toast.fire({
                                                type: 'info',
                                                title: json.recordsTotal+' sections(s) found.'
                                            })

                                            firstPrompt = false
                                    }

                                    return all_sched;
                                }
                        },
                        order: [
                                [ 1, "asc" ]
                            ],
                        columns: [
                                    { "data": null },
                                    { "data": "schedgroupdesc" },
                                    // { "data": "subjCode" },
                                    { "data": "subjDesc" },
                                    { "data": "lecunits" },
                                    { "data": "labunits" },
                                    { "data": "capacity" },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                ],
                        columnDefs: [
                                {
                                    'targets': 8,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                            var temp_data = all_sched_detail.filter(x=>x.headerID == rowData.id)
                                            if(rowData.lastname != null){
                                                $(td)[0].innerHTML = rowData.lastname+', '+rowData.firstname+'<p class="mb-0" style="font-size:.7rem" data-se>'+rowData.tid+'</p>';

                                            }else{
                                                $(td)[0].innerHTML = null
                                                // $(td)[0].innerHTML = '<a style="font-size:.65rem !important" href="javascript:void(0)" class="add_teacher" data-id="'+rowData.id+'" data-subjdesc="Push-up"  data-text="'+rowData.subjCode+' : '+rowData.subjDesc+'">Add Teacher</a>'
                                            }
                                            
                                            $(td).addClass('align-middle')
                                            $(td).attr('style','font-size:.6rem !important')
                                    }
                                },
                                {
                                    'targets': 1,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                            var schedgroup_detail = all_sched_groupdetail.filter(x=>x.schedid == rowData.id)
                                            var text = '';
                                    
                                            $.each(schedgroup_detail,function(a,b){
                                                
                                                var collegecourse = b.courseabrv
                                                if(b.courseabrv == null){
                                                        collegecourse = b.collegeabrv
                                                }

                                                text += '<span class=" badge badge-primary  mt-1" style="font-size:.65rem !important; white-space:normal" >'+collegecourse+'-'+(b.levelid -  16 )+' '+b.schedgroupdesc+'</span> <br>'
                                            })
                                            // var sectiondesc = all_sched_section.filter(x=>x.id == rowData.sectionID)
                                            // if(sectiondesc.length > 0){
                                            //       $(td).text(sectiondesc[0].sectionDesc)
                                            // }else{
                                            //       $(td).text(null)
                                            // }
                                            
                                            $(td)[0].innerHTML = text
                                            $(td).addClass('align-middle')
                                            $(td).addClass('p-1')
                                    }
                                },
                                {
                                    'targets': 0,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                            var specificationBadge = '<span class="badge badge-primary">RS</span>'
                                            if(rowData.section_specification == 2){
                                                specificationBadge = '<span class="badge badge-warning">SP</span>'
                                            }
                                            $(td)[0].innerHTML = specificationBadge
                                            $(td).addClass('align-middle')
                                            $(td).addClass('text-center')
                                    }
                                },
                                {
                                    'targets': 2,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                            // var text = ''+rowData.subjDesc+'<p class="mb-0" style="font-size:.7rem" data-se>'+rowData.subjCode+'</p>';

                                            var text = rowData.subjDesc+'<p class="mb-0" style="font-size:.7rem" >'+rowData.subjCode+'</p>';
                                            $(td)[0].innerHTML = text
                                            
                                            $(td).addClass('align-middle')
                                    }
                                },
                                {
                                    'targets': 3,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                    
                                            $(td).addClass('text-center')
                                            $(td).addClass('align-middle')
                                    }
                                },
                                {
                                    'targets': 4,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                            $(td).addClass('text-center')
                                            $(td).addClass('align-middle')
                                    }
                                },
                                {
                                    'targets': 5,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                            $(td).addClass('text-center')
                                            $(td).addClass('align-middle')
                                    }
                                },
                                {
                                    'targets': 6,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                            var enrolled_count = all_sched_enrolled.filter(x=>x.schedid == rowData.id)
                                            var all_loaded_student_count = all_sched_student.filter(x=>x.schedid == rowData.id)
                                            var enrolled = 0
                                            var loaded = 0
                                            if(enrolled_count.length > 0){
                                                enrolled = enrolled_count[0].enrolled 
                                            }
                                            
                                            if(all_loaded_student_count.length > 0){
                                                loaded = all_loaded_student_count[0].enrolled
                                            }

                                            $(td)[0].innerHTML  = '<a href="javascript:void(0)" data-id="'+rowData.id+'"'+'" class="sched_list_students" data-text="'+rowData.subjCode+' - '+rowData.subjDesc+'" data-toggle="tooltip" data-offset="55%" data-placement="top" data-original-title="Enrolled Students">'+enrolled+'</a>' + ' / '+ '<a href="javascript:void(0)" data-id="'+rowData.id+'"'+'" class="sched_list_loaded_students" data-text="'+rowData.subjCode+' - '+rowData.subjDesc+'" data-toggle="tooltip" data-offset="55%" data-placement="top"  data-original-title="Loaded Students">'+loaded+'</a>'

                                            $(td).addClass('text-center')
                                            $(td).addClass('align-middle')
                                    }

                                    
                                },
                                {
                                    'targets': 7,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                            var temp_data = all_sched_detail.filter(x=>x.headerID == rowData.id)
                                            var temp_sched = []
                                            if(temp_data.length > 0){
                                                $.each(temp_data,function(a,b){
                                                        var check = temp_sched.filter(x=>x.stime == b.stime && x.etime == b.etime && x.schedotherclass == b.schedotherclass && x.roomid == b.roomid)
                                                        if(check.length == 0){
                                                            temp_sched.push({
                                                                    'schedotherclass':b.schedotherclass,
                                                                    'roomname':b.roomname,
                                                                    'etime':b.etime,
                                                                    'stime':b.stime,
                                                                    'days':[],
                                                                    'roomid':b.roomid
                                                            });
                                                            var get_index = temp_sched.findIndex(x=>x.stime == b.stime && x.etime == b.etime && x.schedotherclass == b.schedotherclass && x.roomid == b.roomid)
                                                            if(get_index != -1){
                                                                    temp_sched[get_index].days.push(b.day)
                                                            }
                                                        }else{
                                                            var get_index = temp_sched.findIndex(x=>x.stime == b.stime && x.etime == b.etime && x.schedotherclass == b.schedotherclass && x.roomid == b.roomid)
                                                            if(get_index != -1){
                                                                    temp_sched[get_index].days.push(b.day)
                                                            }
                                                        }
                                                })
                                                var text = ''
                                                $.each(temp_sched,function(a,b){
                                                        var temp_stime = moment(b.stime, 'HH:mm a').format('hh:mm a')
                                                        
                                                        if(b.schedotherclass != null){
                                                            text += b.schedotherclass.substring(0, 3)+'.: '
                                                        }
                                                        

                                                        text += moment(b.stime, 'HH:mm a').format('hh:mm A')+' - '+moment(b.etime, 'HH:mm a').format('hh:mm A') +' / '

                                                        var sorted_days = b.days.sort()

                                                        $.each(sorted_days,function(c,d){
                                                            text += d == 1 ? 'M' :''
                                                            text += d == 2 ? 'T' :''
                                                            text += d == 3 ? 'W' :''
                                                            text += d == 4 ? 'Th' :''
                                                            text += d == 5 ? 'F' :''
                                                            text += d == 6 ? 'Sat' :''
                                                            text += d == 7 ? 'Sun' :''
                                                        })

                                                        if(b.roomname != null){
                                                            text += ' / '+b.roomname
                                                        }
                                                        
                                                        if(temp_sched.length != a+1){
                                                            text += ' <br> '
                                                        }
                                                })

                                                
                                                $(td)[0].innerHTML = text
                                                // $(td)[0].innerHTML = text + '<br><a style="font-size:.75rem !important" href="#sched_plot_holder" class="add_sched " data-id="'+rowData.id+'" data-subjdesc="Push-up">Add Sched</a>'
                                            }else{
                                                $(td)[0].innerHTML = null
                                                // $(td)[0].innerHTML = '<a style="font-size:.75rem !important" href="#sched_plot_holder" class="add_sched " data-id="'+rowData.id+'" data-subjdesc="Push-up">Add Sched</a>'
                                            }
                                            $(td).addClass('align-middle')
                                    },
                                    
                                },
                                {
                                    'targets': 9,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        var text = '';
                                        $(td).addClass('text-center p-1')
                                        $(td).addClass('align-middle')
                                        if(rowData.teacherID == null){
                                            $(td)[0].innerHTML =  '<a style="font-size:.75rem !important" href="javascript:void(0)" class="btn-to-modal" data-id="'+rowData.id+'" data-selectedid="'+rowData.dataid+'" id="btn-addsched" data-subjdesc="Push-up" data-toggle="tooltip" data-placement="top" title="Assign Instructor"><i class="nav-icon fas fa-plus"></i> Add</a>'
                                        } else {
                                            $(td)[0].innerHTML =  '<a class="btn btn-success text-light" style="font-size:.8rem; href="javascript:void(0); padding: 2px 0px 2px 0px !important">Assigned</a>'

                                        // '<a style="font-size:.75rem !important" href="javascript:void(0)" class="btn-to-modal ml-2" data-id="'+rowData.id+'" data-subjdesc="Push-up" data-toggle="tooltip" data-placement="top" title="Update Schedule" data-modal="csl-schedule-form"><i class="nav-icon fa fa-calendar"></i></a> <br>' +
                                            
                                        // '<a style="font-size:.75rem !important" href="javascript:void(0)" class="btn-to-modal" data-id="'+rowData.id+'" data-toggle="tooltip" data-placement="top" title="Update Schedule Information" data-modal="csl-schedule-information-form"><i class="nav-icon fas fa-edit" ></i></a>'+

                                        // '<a style="font-size:.75rem !important" href="javascript:void(0)" class=" ml-2 btn-csl-remove-schedule" data-id="'+rowData.id+'" data-toggle="tooltip" data-placement="top" title="Remove Schedule" ><i class="nav-icon fas fa-trash-alt text-danger" ></i></a>'
                                        }
                                        
                                    }
                                }
                            ],
                            "initComplete": function(settings, json) {
                                $(function () {
                                    $('[data-toggle="tooltip"]').tooltip()
                                })

                            }

                        });

                        // var label_text = $($("#collegesection_datatable_wrapper")[0].children[0])[0].children[0]

                        // if(allowButtons){
                        //     $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm" id="button_to_createsection_modal" hidden>Create Section</button><button class="btn btn-primary btn-sm subjsched_form" >Create Subject Sched</button><button class="btn btn-primary btn-sm ml-2" id="schedgroup_to_modal">Sections</button><button hidden class="btn btn-primary btn-sm ml-2" id="button_to_studentloading_modal">Sched List</button><button class="btn btn-default btn-sm ml-2" id="printSched"><i class="fa fa-print"></i> Print Schedule</button>'
                        // }else{
                        //     $(label_text)[0].innerHTML = null
                        // }
                    }

                // ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                
                
                
                
                
                
                
                
                
                
                
                
                //  ********************* Display Modal when button Add Sched button is Click ***************************
                $(document).on('click', '#btn-addsched', function(){

                    // $('#modal-add-instructor').modal('show');

                    var data_id = $(this).attr('data-id');
                    var selected_id = $(this).attr('data-selectedid');
                    var teacherid = teacherdata.id;
                    console.log(selected_id);
                    Swal.fire({
                              title: 'Are you sure?',
                              text: "You want to add this Schedule?",
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Yes, Add Schedule!'
                        })
                        .then((result) => {
                              if (result.value) {
                                update_teacher(teacherid, selected_id);
                                console.log('added successfully');
                              }
                        })
                })
                // ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------



                //  ********************* When button proceed is click ***************************
                $(document).on('click', '#btn-proceedadd-instructor', function(){
                    var teachername = $('#input_teachername').val();
                    
                    // console.log(all_sched);
                })
                // ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------



                //  ********************* When button proceed is click ***************************
                function update_teacher(teacherid, selected_id){
                    $('#cnflctLstHldr').empty()
                    $('.cnflctButtonHolder').attr('hidden','hidden')
                    
                    $.ajax({
                        type:'GET',
                        url: '/college/schedule/list/update/teacher',
                        data:{
                                teacherid : teacherid,
                                schedid : selected_id,
                                allowconflict : allowconflict
                        },
                        success:function(data) {
                                if(data[0].status == 1){
                                    // $('#btn-csl-update-instructor').text('Update')
                                    // allowconflict = 0
                                    // display_sched_csl(p_url ,p_studid , p_entype)
                                    // if (typeof display_sched_collegesections === 'function') {
                                    //         display_sched_collegesections()
                                    // }
                                    get_schedule();
                                    display_sched_collegesections();
                                    console.log('masaya');
                                } else{
                                
                                    if(data[0].data == 'Conflict'){
                                            $('#btn-csl-update-instructor').text('Conflict : Proceed Update')
                                            allowconflict = 1
                                            $('.cnflctButtonHolder').removeAttr('hidden')

                                        
                                            var text = ``

                                            $.each(data[0].conflict,function(a,b){
                                                text += `<div class="row">
                                                            <div class="col-md-12">
                                                                    <p class="mb-0">Type: `+b.type+`</p>
                                                                    <p class="mb-0">Group: `+b.group+`</p>
                                                                    <p class="mb-0">Subject: `+b.subject+`</p>      
                                                                    <p class="mb-0">Days: `+b.days+`</p>
                                                                    <p class="mb-0">Time: `+b.time+`</p>
                                                            </div>
                                                        </div>`

                                                if(a != ( data[0].conflict.length - 1 )){
                                                        text += '<hr>'
                                                }
                                            })

                                            $('#cnflctLstHldr')[0].innerHTML = text

                                    }
                                }

                                Toast.fire({
                                    type: 'warning',
                                    title: data[0].message
                                })
                        }
                    })
                }
                // ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                

                //  ********************* Remove Teacher Own/Added Schedule  ***************************
                $(document).on('click', '#btn-removesched', function(){
                    var schedid = $(this).attr('data-schedid');
                    var teacherid = null;
                    console.log("------------------------");
                    Swal.fire({
                        title: 'Are you sure want to remove schedule?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete schedule!'
                    })
                    .then((result) => {
                        if (result.value) {
                            update_teacher(teacherid, schedid);
                        }

                    })
                })
                // ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


            });
        </script>
                 
@endsection

