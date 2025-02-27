@php
    $refid = DB::table('usertype')->where('id',auth()->user()->type)->where('deleted',0)->select('refid')->first();
    $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;
    if(Session::get('currentPortal') == 2){
        $syid = DB::table('sy')->where('isactive',1)->select('id')->first()->id;
        $acadprogid = DB::table('teacheracadprog')
                        ->join('academicprogram',function($join){
                            $join->on('teacheracadprog.acadprogid','=','academicprogram.id');
                        })
                        ->where('teacherid',$teacherid)
                        ->where('syid',$syid)
                        ->select('academicprogram.*')
                        ->where('deleted',0)
						->where('acadprogid','!=',6)
						->orderBy('acadprogid')
                        ->where('acadprogutype',2)
                        ->distinct()
                        ->get();

        $all_acad = array();

        foreach( $acadprogid as $item){
            array_push($all_acad,$item);
        }

        $extend = 'principalsportal.layouts.app2';
    }else if(Session::get('currentPortal') == 3){
        $extend = 'registrar.layouts.app';
    }else if(Session::get('currentPortal') == 3){
        $extend = 'registrar.layouts.app';
    }else{
        if( $refid->refid == 20){
            $extend = 'principalassistant.layouts.app2';
        }elseif( $refid->refid == 22){
            $extend = 'principalcoor.layouts.app2';
        }

        $syid = DB::table('sy')->where('isactive',1)->select('id')->first()->id;

        $acadprogid = DB::table('teacheracadprog')
                        ->where('teacherid',$teacherid)
                        ->where('syid',$syid)
                        ->select('acadprogid')
                        ->where('deleted',0)
						->where('acadprogid','!=',6)
						->orderBy('acadprogid')
                        ->get();

        $all_acad = array();

        foreach( $acadprogid as $item){
            array_push($all_acad,$item);
        }
    }

    if(Session::get('currentPortal') == 3){

        $syid = DB::table('sy')->where('isactive',1)->select('id')->first()->id;

        $acadprogid = DB::table('teacheracadprog')
                        ->join('academicprogram',function($join){
                            $join->on('teacheracadprog.acadprogid','=','academicprogram.id');
                        })
						->where('acadprogid','!=',6)
                        ->where('teacherid',$teacherid)
                        ->where('syid',$syid)
                        ->select('academicprogram.*')
                        ->where('deleted',0)
						->orderBy('acadprogid')
                        ->get();

        $all_acad = array();

        foreach( $acadprogid as $item){
            array_push($all_acad,$item);
        }
    }

   
@endphp

@extends($extend)

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <style>
         .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }
        .shadow {
                box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                border: 0 !important;
        }
    </style>
@endsection


@section('content')

@php
    $gradelevel_list = array();
    $section_list = array();
    foreach ($all_acad as $item){
        $gradelevel = DB::table('gradelevel')
                        ->where('acadprogid',$item->id)
                        ->where('deleted',0)
                        ->select('id','levelname as text')
                        ->orderBy('sortid')
                        ->get();
        foreach($gradelevel as $level_item){
            array_push($gradelevel_list,$level_item);
        }
    }       
    
    foreach ($gradelevel_list as $item){
        $section = DB::table('sections')
                        ->where('levelid',$item->id)
                        ->where('deleted',0)
                        ->select('id','sectionname as text','levelid')
                        ->get();
        foreach($section as $section_item){
            array_push($section_list,$section_item);
        }
    } 
@endphp

<div class="modal fade" id="signatory_form_modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
          <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                      <h4 class="modal-title">Signatory Form</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                      <div class="row">
                            <div class="col-md-12 form-group">
                                  <label for="">Name</label>
                                  <input id="input_name" class="form-control form-control-sm">
                            </div>
                      </div>
                      <div class="row">
                            <div class="col-md-12 form-group">
                                  <label for="">Title</label>
                                  <input id="input_title" class="form-control form-control-sm">
                            </div>
                      </div>
                      <div class="row">
                            <div class="col-md-12">
                                  <button class="btn btn-sm btn-primary" id="signatory_form_button">Create</button>
                            </div>
                      </div>
                </div>
          </div>
    </div>
</div>   


<div class="modal fade" id="blnk_sf9_modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
          <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" style="font-size: 1.1rem !important">Blank SF9</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach($gradelevel_list as $item)
                                @if($item->id == 14 || $item->id == 15)
                                    <div class="col-md-12  mb-2">
                                        <button class="btn btn-sm btn-primary blnk_sf9 btn-block" data-id="{{$item->id}}" data-strand="1">{{$item->text}} - ABM</button>
                                    </div>
                                    <div class="col-md-12  mb-2">
                                        <button class="btn btn-sm btn-primary blnk_sf9 btn-block" data-id="{{$item->id}}" data-strand="2">{{$item->text}} - HUMSS</button>
                                    </div>
                                    <div class="col-md-12  mb-2">
                                        <button class="btn btn-sm btn-primary blnk_sf9 btn-block" data-id="{{$item->id}}" data-strand="4">{{$item->text}} - STEM</button>
                                    </div>
                                @else 

                                    <div class="col-md-6  mb-2">
                                        <button class="btn btn-sm btn-primary blnk_sf9 btn-block" data-id="{{$item->id}}" data-strand=null>{{$item->text}}</button>
                                    </div>
                                @endif
                        @endforeach
                    </div>
                </div>
          </div>
    </div>
</div>


<section class="content-header">
    <div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h1>Report Card (SF9)</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Report Card (SF9)</li>
        </ol>
        </div>
    </div>
    </div>
</section>
@php
   $sy = DB::table('sy')->orderBy('sydesc')->get(); 
   $semester = DB::table('semester')->orderBy('semester')->get(); 
@endphp

<section class="content pt-0">
    <div class="container-fluid">
          <div class="row">
                <div class="col-md-3">
                    <div class="row">
                        <div class="col-md-12">
                              <div class="info-box shadow">
                                    <div class="info-box-content">
                                          <div class="row">
                                                <div class="col-md-12 form-group">
                                                    <label for="">School Year</label>
                                                    <select class="form-control select2" id="filter_sy">
                                                          @foreach ($sy as $item)
                                                                @if($item->isactive == 1)
                                                                      <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                                @else
                                                                      <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                                @endif
                                                          @endforeach
                                                    </select>
                                                </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-12 form-group mb-0">
                                                <label for="">Semester</label>
                                                <select class="form-control select2" id="filter_semester">
                                                      @foreach ($semester as $item)
                                                            @if($item->isactive == 1)
                                                                  <option value="{{$item->id}}" selected="selected">{{$item->semester}}</option>
                                                            @else
                                                                  <option value="{{$item->id}}">{{$item->semester}}</option>
                                                            @endif
                                                      @endforeach
                                                </select>
                                            </div>
                                      </div>
                                    </div>
                              </div>
                        </div>
                  </div>
                    <div class="row" hidden>
                        <div class="col-md-12">
                            <div class="card shadow" style="">
                                <div class="card-body  pt-2 pb-2">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-primary btn-sm btn-block"  id="button_to_blnksf9_lvlid"><i class="fas fa-file-pdf"></i> Blank Report Card (SF9)</button>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow" style="">
                                <div class="card-body">
                                    @php
                                        $count = 0;
                                    @endphp
                                    @foreach ($all_acad as $key=>$item)
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for=""><a href="javascript:void(0)" class="edit_signatory pr-2" data-acad="{{$item->id}}"><i class="far fa-edit" data-acad="2"></i></a> Signatory ({{$item->acadprogcode}})</label>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10 text-center signatory_name" style="border-bottom:1px solid black" data-acad="{{$item->id}}">
                                                --
                                            </div>
                                            <div class="col-md-1"></div>
                                            <div class="col-md-12 text-center">
                                                <p class="text-muted signatory_title" data-acad="{{$item->id}}">--</p>
                                            </div>
                                        </div>
                                        @php
                                            $count += 1;
                                        @endphp
                                        @if(count($all_acad) != $count)
                                            <hr class="mt-0">
                                        @endif
                                       
                                    @endforeach
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-12">

                        </div>
                        <div class="col-md-12">
                            <div class="card shadow" style="">
                                <div class="card-body">
                                      <div class="row">
                                           <div class="col-md-3 form-group">
                                               <label for="">Grade Level</label>
                                               <select name="" id="filter_levelid" class="form-control form-control-sm select2"></select>
                                           </div>
                                           <div class="col-md-4 form-group">
                                                <label for="">Sections</label>
                                                <select name="" id="filter_sectionid" class="form-control form-control-sm select2"></select>
                                            </div>
                                            <div class="col-md-5 text-right">
                                                
                                            </div>
                                            {{-- <div class="col-md-5 text-right">
                                                <button class="btn btn-primary btn-sm mt-4" style="font-size:.8rem !important; margin-top:30px !important" disabled  id="print_section_sf9"><i class="fas fa-file-pdf"></i> Print Section Report Card (SF9)</button>
                                            </div> --}}
                                      </div>
                                      <div class="row mt-2">
                                            <div class="col-md-12"  style="font-size:.8rem">
                                                  <table class="table-hover table table-striped table-sm table-bordered table-head-fixed nowrap display" id="student_list" width="100%">
                                                        <thead>
                                                              <tr>
                                                                    <th width="40%">Student</th>
                                                                    <th width="40%">Grade Level / Section</th>
                                                                    <th width="5%"></th>
                                                                    <th width="5%"></th>
                                                              </tr>
                                                        </thead>
                                                  </table>
                                            </div>
                                      </div>
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
    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script>
        $(document).ready(function(){

            $(document).on('click','.blnk_sf9',function(){
                var temp_lvlid = $(this).attr('data-id')
                var temp_strand = $(this).attr('data-strand')
                window.open("/prinsf9print/0?syid="+$('#filter_sy').val()+"&semid="+$('#filter_semester').val()+"&levelid="+temp_lvlid+"&strandid="+temp_strand);
            })


            $(document).on('click','#button_to_blnksf9_lvlid',function(){
                $('#blnk_sf9_modal').modal()
            })

            const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 2000,
            })

            var gradelevel = @json($gradelevel_list);
            var section = @json($section_list);

            $('.select2').select2()

            $('#filter_levelid').empty()
            $('#filter_levelid').append('<option value="">Select Grade Level</option>')
            $("#filter_levelid").select2({
                data: gradelevel,
                allowClear: true,
                placeholder: "Select Grade Level",
            })

            $('#filter_sectionid').empty()
            $('#filter_sectionid').append('<option value="">Select Section</option>')
            $("#filter_sectionid").select2({
                data: section,
                allowClear: true,
                placeholder: "Select Section",
            })

            var all_student = [];
            var all_signatories = []
            var selected_acad = null
            var selected_signatory = null

            subjectplot_datatable()
            get_signatories()
            get_students()

            $(document).on('change','#filter_sy , #filter_semester',function(){
                get_students()
                get_signatories()
            })

            $(document).on('change','#filter_levelid',function(){
                var levelid = $(this).val()
                if(levelid == ""){
                    $('#filter_sectionid').empty()
                    $('#filter_sectionid').append('<option value="">Select Section</option>')
                    $("#filter_sectionid").select2({
                        data: section,
                        allowClear: true,
                        placeholder: "Select Section",
                    })
                }else{
                    var temp_section = section.filter(x=>x.levelid == levelid)
                    $('#filter_sectionid').empty()
                    $('#filter_sectionid').append('<option value="">Select Section</option>')
                    $("#filter_sectionid").select2({
                        data: temp_section,
                        allowClear: true,
                        placeholder: "Select Section",
                    })
                }
                subjectplot_datatable()
            })


           

            $(document).on('change','#filter_sectionid',function(){
                console.log($(this).val())
                if($(this).val() != ""){
                    $('#print_section_sf9').removeAttr('disabled','disabled')
                    subjectplot_datatable()
                }else{
                    $('#print_section_sf9').attr('disabled','disabled')
                }
            })

            $(document).on('click','#print_section_sf9',function(){
                var sectionid = $('#filter_sectionid').val()
                var levelid = section.filter(x=>x.id == sectionid)[0].levelid
                var syid = $('#filter_sy').val()
                window.open("/section/report/reportcard?syid="+syid+"&levelid="+levelid+"&sectionid="+sectionid+"&setup="+'2'+"&type="+'pdf');
            })

            $(document).on('click','.edit_signatory',function(){
                var temp_id = $(this).attr('data-acad')
                var temp_info = all_signatories.filter(x=>x.acadprogid == temp_id)
                selected_acad = temp_id
                selected_signatory = null

                if(temp_info.length > 0){
                    selected_signatory = temp_info[0].id
                    $('#signatory_form_button').text('Update')
                    $('#signatory_form_button').removeClass('btn-primary')
                    $('#signatory_form_button').addClass('btn-success')
                    $('#signatory_form_button').attr('data-proccess',1)
                    $('#input_name').val(temp_info[0].name)
                    $('#input_title').val(temp_info[0].title)
                }else{
                    $('#signatory_form_button').text('Create')
                    $('#signatory_form_button').removeClass('btn-success')
                    $('#signatory_form_button').addClass('btn-primary')
                    $('#signatory_form_button').attr('data-proccess',2)
                    $('#input_name').val("")
                    $('#input_title').val("")
                }
                $('#signatory_form_modal').modal()
            })

            $(document).on('click','#signatory_form_button',function(){
                if($(this).attr('data-proccess') == 1){
                    update_signatories()
                }else if($(this).attr('data-proccess') == 2){
                    create_signatories()
                }
            })

            function get_signatories(){
                $.ajax({
                    type:'GET',
                    url:'/setup/signatories/list/sf9',
                    data:{
                        syid:$('#filter_sy').val(),
                    },
                    success:function(data) {
                        if(data.length == 0){
                            all_signatories = []
                            $('.signatory_name').text("--")
                            $('.signatory_title').text("--")
                        }else{
                            all_signatories = data
                            $.each(data,function(a,b){
                                $('.signatory_name[data-acad="'+b.acadprogid+'"]').text(b.name != null ? b.name : '--')
                                $('.signatory_title[data-acad="'+b.acadprogid+'"]').text(b.title != null ? b.title : '--')
                            })
                        }
                        
                    }
                })
            }

            function create_signatories(){
                $.ajax({
                    type:'GET',
                    url:'/setup/signatories/create/sf9',
                    data:{
                        syid:$('#filter_sy').val(),
                        name:$('#input_name').val(),
                        title:$('#input_title').val(),
                        acadprogid:selected_acad
                    },
                    success:function(data) {
                        if(data[0].status == 0){
                            Toast.fire({
                                    type: 'error',
                                    title: data[0].message
                            })
                        }else{
                            get_signatories()
                            Toast.fire({
                                    type: 'success',
                                    title: data[0].message
                            })
                        }
                    }
                })
            }

            function update_signatories(){
                $.ajax({
                    type:'GET',
                    url:'/setup/signatories/update/sf9',
                    data:{
                        syid:$('#filter_sy').val(),
                        name:$('#input_name').val(),
                        title:$('#input_title').val(),
                        acadprogid:selected_acad,
                        id:selected_signatory
                    },
                    success:function(data) {
                        if(data[0].status == 0){
                            Toast.fire({
                                    type: 'error',
                                    title: data[0].message
                            })
                        }else{
                            get_signatories()
                            Toast.fire({
                                    type: 'success',
                                    title: data[0].message
                            })
                        }
                    }
                })
            }

            function get_students(){
                $.ajax({
                    type:'GET',
                    url:'/prinsf9getstudent',
                    data:{
                            syid:$('#filter_sy').val(),
                            semid:$('#filter_semester').val()
                        },
                    success:function(data) {
                        all_student = data;
                        subjectplot_datatable()
                    }
                })
            }

            $(document).on('click','.view_sf9',function(){
                var temp_id = $(this).attr('data-id')
				var datatype = $(this).attr('data-type')
                window.open("/prinsf9print/"+temp_id+"?syid="+$('#filter_sy').val()+"&semid="+$('#filter_semester').val()+"&type="+datatype);
            })

            function subjectplot_datatable(){
                
                var temp_sy = $('#filter_sy').val()
                var temp_students = all_student
                var sectionid = $('#filter_sectionid').val()
                var levelid = $('#filter_levelid').val() 

                if(levelid != "" && sectionid != ""){
                    temp_students = temp_students.filter(x=>x.sectionid == sectionid && x.levelid == levelid)
                }else if(levelid != ""){
                    temp_students = temp_students.filter(x=>x.levelid == levelid)
                }else if(sectionid != ""){
                    temp_students = temp_students.filter(x=>x.sectionid == sectionid)
                }

                $("#student_list").DataTable({
                        destroy: true,
                        data:temp_students,
                        lengthChange: false,
                        scrollX: true,
                        autoWidth: false,
                        columns: [
                            { "data": "student" },
                            { "data": null },
                            { "data": null },
                            { "data": "search" }
                        ],  
                        columnDefs: [
                            {
                                    'targets': 0,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        var text = '<a class="mb-0">'+rowData.student+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.sid+'</p>';
                                        $(td)[0].innerHTML =  text
                                        
                                    }
                            },
                            {
                                    'targets': 1,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        var text = '<a class="mb-0">'+rowData.sectionname+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.levelname+'</p>';
                                        $(td)[0].innerHTML =  text
                                        
                                    }
                            },
                            {
                                    'targets': 2,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        var text = '';
                                        $(td).addClass('text-center')
                                        $(td).addClass('align-middle')
                                        $(td)[0].innerHTML =  '<a class="btn btn-sm btn-primary view_sf9"  data-type="pdf" data-id="'+rowData.id+'" style="font-size:.8rem" href="javascript:void(0)"><i class="fas fa-file-pdf"></i> SF9</a>'
                                        
                                    }
                            },
                            {
                                    'targets': 3,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        var text = '';
                                        
										$(td).addClass('text-center')
                                        $(td).addClass('align-middle')
                                        $(td)[0].innerHTML =  '<a class="btn btn-sm btn-primary view_sf9" data-type="excel" data-id="'+rowData.id+'" style="font-size:.8rem" href="javascript:void(0)"><i class="fas fa-file-pdf"></i> SF9 (Excel)</a>'
                                        
                                    }
                            }
                        ]
                        
                });
            }
        })
    </script>
{{-- <script src="{{asset('js/pagination.js')}}"></script>
<script>

    $(document).ready(function(){
        
   

        if($(window).width()<500){
            $('.form-group').addClass('w-100 mb-2')
            $('.input-group').addClass('w-100')
        }

        function pagination(itemCount,pagetype){
            var result = [];
            for (var i = 0; i < itemCount; i++) {
            result.push(i);
            }
            $('#data-container').pagination({
            dataSource: result,
            pageRange: 1,
            callback: function(data, pagination) {
                if(pagetype){
                $.ajax({
                    type:'GET',
                    url:'/prinsf9getstudent',
                    data:{
                        data:$("#search").val(),
                        pagenum:pagination.pageNumber,
                        apid:$("#acadid").val()
                    },
                    success:function(data) {
                    $('#studentholder').empty();
                    $('#studentholder').append(data);
                    }
                })
                }
                pagetype=true;
            },
                hideWhenLessThanOnePage: true,
                pageSize: 10,
            })
        }
        $("#search" ).keyup(function() {
            $.ajax({
            type:'GET',
            url:'/prinsf9getstudent',
            data:{
                    data:$(this).val(),
                    pagenum:'1',
                    apid:$("#acadid").val()
                },
            success:function(data) {
                $('#studentholder').empty();
                $('#studentholder').append(data);
                pagination($('#searchCount').val())
            }
            })
        });

        $(document).on('change','#acadid',function() {
    
            $.ajax({
            type:'GET',
            url:'/prinsf9getstudent',
            data:{
                    data:$("#search").val(),
                    pagenum:'1',
                    apid:$(this).val()
                },
            success:function(data) {
                $('#studentholder').empty();
                $('#studentholder').append(data);
                pagination($('#searchCount').val())
                console.log($('.table'))
            }
            })
            
        });

        

    })
</script> --}}

@endsection
