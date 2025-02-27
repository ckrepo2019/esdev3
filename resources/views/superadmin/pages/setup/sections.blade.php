

@php

      $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

      if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
      }else if(Session::get('currentPortal') == 1){
        $extend = 'teacher.layouts.app';
      }else{
            if(isset($check_refid->refid)){
                  if($check_refid->refid == 27){
                        $extend = 'academiccoor.layouts.app2';
                  }
            }else{
                  $extend = 'general.defaultportal.layouts.app';
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
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<style>
       .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
      }

</style>
@endsection


@section('content')

@php
      $sy = DB::table('sy')->orderBy('sydesc','desc')->get(); 
      $strand = DB::table('sh_strand')
                  ->where('deleted',0)
                  ->where('active',1)
                  ->select(
                        'id',
                        'strandcode as text',
                        'strandcode'
                  )
                  ->get();
      $room = DB::table('rooms')
            ->where('rooms.deleted',0)
            ->select('rooms.id','roomname','roomname as text')
            ->get(); 

      $schoolinfo = DB::table('schoolinfo')->first();
@endphp



<div class="modal fade" id="section_list_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Section List</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row" style="font-size:11px !important">
                              <div class="col-md-12">
                                    <table class="table table-striped table-sm table-bordered" id="section_list" width="100%">
                                          <thead>
                                                <tr>
                                                      <th width="5%"></th>
                                                      <th width="50%">Section Name</th>
                                                      <th width="35%">Grade Level</th>
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

<div class="modal fade" id="enrolled_learners_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
      <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                  <h4 class="modal-title" style="font-size: 1.1rem !important">Student List</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body pt-0">
                  <div class="row" style="font-size:11px !important">
                        <div class="col-md-12">
                              <table class="table table-striped table-sm table-bordered" id="enrolled_learners_table" width="100%">
                                    <thead>
                                          <tr>
                                                <th width="45%">Student Name</th>
                                                <th width="25%">Status</th>
                                                <th width="30%">Strand</th>
                                          </tr>
                                    </thead>
                              </table>
                        </div>
                  </div>
            </div>
      </div>
</div>
</div>  


<div class="modal fade" id="copy_info_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog modal-sm">
      <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                  <h4 class="modal-title">Copy Section Info</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body pt-0">
                  <div class="row">
                        <div class="col-md-12 form-group">
                              <label for="">Copy Section Information<br>to School Year</label>
                              <select class="form-control select2 form-control-sm" id="copy_sy">
                                    @foreach (collect($sy)->where('ended',0)->where('isactive',0)->values() as $item)
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
                        <div class="col-md-12">
                              <button class="btn btn-primary btn-sm" id="copy_section_info"><i class="fas fa-copy"></i> Copy</button>
                        </div>
                  </div>
            </div>
      </div>
</div>
</div>   


<div class="modal fade" id="section_detail_info" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog modal-sm">
      <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                  <h5 class="modal-title">Section Info. Form</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body pt-0">
                  <div class="row">
                        <div class="col-md-12 form-group">
                              <label for="">Section</label>
                              <input name="" id="sectionname_label" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="col-md-12 form-group">
                              <label for="">Grade Level</label>
                              <input name="" id="levelname_label" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="col-md-12 form-group">
                              <div class="icheck-primary d-inline pt-2">
                                    <input type="checkbox" id="input_issp" >
                                    <label for="input_issp">Special Section
                                    </label>
                              </div>
                             
                        </div>
                        <div class="col-md-12">
                              <div class="form-group mb-0">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="input_mwsp" name="input_mwsp" value="1">
                                        <label for="input_mwsp">MWSP
                                        </label>
                                    </div>
                                </div>
                                <p class="text-danger text-sm"><i>*Modified Work and Study Program.</i></p>
                        </div>
                        <div class="col-md-12 form-group">
                              <label for="">Adviser</label>
                              <select name="" id="input_teacher" class="form-control select2"></select>
                        </div>
                        <div class="col-md-12 form-group">
                              <label for="">Room</label>
                              <select name="" id="input_room" class="form-control select2"></select>
                        </div>
                        <div class="col-md-12 form-group">
                              <label for="">Session</label>
                              <select name="input_session" id="input_session" class="form-control select2">
                                    <option value="">Whole Day</option>
                                    <option value="1">Morning Session</option>
                                    <option value="2">Afternoon Session</option>
                                    <option value="3">Night Session</option>
                                </select>
                        </div>
                        <div class="col-md-12" hidden>
                              <div class="form-group">
                                    <div class="icheck-success d-inline">
                                        <input type="checkbox" id="input_sundayclass" name="input_sundayclass" value="1">
                                        <label for="input_sundayclass">Sunday Class
                                        </label>
                                    </div>
                                </div>
                        </div>
                        
                        <div class="col-md-12">
                              <div class="form-group" id="input_strand_holder" hidden>
                                    <label>Strand</label>
                                    <select class="form-control select2 select2-danger" multiple="multiple" id="input_strand"  data-dropdown-css-class="select2-danger"> </select>
                              </div>
                        </div>
                       
                        <div class="col-md-12" hidden>
                              <div class="form-group">
                                    <div class="icheck-success d-inline">
                                        <input type="checkbox" id="input_active" name="input_active" value="1" checked>
                                        <label for="input_active">Active
                                        </label>
                                    </div>
                                </div>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-12">
                              <button class="btn btn-primary btn-sm" id="update_section_info">Update</button>
                        </div>
                  </div>
            </div>
      </div>
</div>
</div>   


<div class="modal fade" id="section_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Section Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Section Name</label>
                                    <input id="input_sectionname" class="form-control form-control-sm" placeholder="Section Name" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off">
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Grade Level</label>
                                    <select name="" id="input_gradelevel" class="form-control select2"></select>
                              </div>
                        
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="section_to_create">Add</button>
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
                        <h1>Sections</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Sections</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>

<section class="content pt-0">
<div class="container-fluid">
      <div class="row">
            <div class="col-md-12">
                  <div class="info-box shadow-lg">
                    <div class="info-box-content">
                        <div class="row">
                              <div class="col-md-2">
                                    <label for="">School Year</label>
                                    <select class="form-control select2 form-control-sm" id="filter_sy">
                                          @foreach ($sy as $item)
                                                @if($item->isactive == 1)
                                                      <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                @else
                                                      <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                @endif
                                          @endforeach
                                    </select>
                              </div>
                              <div class="col-md-8"></div>
                              <div class="col-md-2 text-right pt-4">
                                    
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
                              <div class="row">
                              </div>
                              <div class="row mt-2">
                                    <div class="col-md-12">
                                          <table class="table table-striped table-sm table-bordered table-hover  p-0" id="section_setup" width="100%" style="font-size:14px !important">
                                                <thead>
                                                      <tr>
                                                            <th width="2%"></th>
                                                            <th width="26%">Section Name</th>
                                                            <th width="12%">Grade Level</th>
                                                            <th width="25%">Current Adviser</th>
                                                            <th width="17%" >Room</th>
                                                            <th width="8%" class="text-center align-middle p-0">Enrolled</th>
                                                            <th width="5%"></th>
                                                            <th width="5%"></th>
                                                      </tr>
                                                </thead>
                                          </table>
                                    </div>
                                    <div class="col-md-12" style="font-size:.7rem !important">
                                          <span class="badge badge-secondary">WD</span> - Whole Day <span class="ml-2 mr-2"> | </span> <span class="badge badge-success">MS</span> - Morning Session <span class="ml-2 mr-2"> | </span> <span class="badge badge-warning">AS</span> - Night Session <span class="ml-2 mr-2"> | </span> <span class="badge badge-danger">NS</span> - Night Session
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
<script src="{{asset('plugins/moment/moment.min.js') }}"></script>

<script>

      var school_setup = @json($schoolinfo);
      var currentuserid = @json(auth()->user()->id);
      var utype = @json(auth()->user()->type);
      var with_new_record = true;
      var first_fetch_create = true
      var first_fetch_update = true
      var first_fetch_delete = true

      function get_last_index(alltable){
            var temptable = alltable.filter(x=>x.status == 0)

            if(temptable.length > 0){
                  $.ajax({
                        type:'GET',
                        url: '/monitoring/tablecount',
                        data:{
                              tablename: temptable[0].table
                        },
                        success:function(data) {
                              lastindex = data[0].lastindex
                              update_local_table_display(temptable[0].table,lastindex,alltable)
                        },
                  })
            }else{
                  if(!first_fetch_create){
                        section_info_list()
                  }else{
                        first_fetch_create = false
                  }
                  return false
            }
      }

      function update_local_table_display(tablename,lastindex,alltable){
            $.ajax({
                  type:'GET',
                  url: school_setup.es_cloudurl+'/monitoring/table/data',
                  data:{
                        tablename:tablename,
                        tableindex:lastindex
                  },
                  success:function(data) {
                        process_create(tablename,0,data,alltable)
                  },
                  error:function(){
                        $('td[data-tablename="'+tablename+'"]')[0].innerHTML = 'Error!'
                  }
            })
      }

      function process_create(tablename,process_count,createdata,alltable){
            if(createdata.length == 0){
                  $.each(alltable,function(a,b){
                        if(b.table == tablename){
                              b.status =1;
                        }
                  })
                  get_last_index(alltable)
                  return false;
            }
            if(createdata.length > 0){
                  $('#reload_data_holder').removeAttr('hidden')
            }
            var b = createdata[0]
            $.ajax({
                  type:'GET',
                  url: '/synchornization/insert',
                  data:{
                        tablename: tablename,
                        data:b
                  },
                  success:function(data) {
                        process_count += 1
                        createdata = createdata.filter(x=>x.id != b.id)
                        process_create(tablename,process_count,createdata,alltable)
                  },
                  error:function(){
                        process_count += 1
                        createdata = createdata.filter(x=>x.id != b.id)
                        process_create(tablename,process_count,createdata,alltable)
                  }
            })
      }

      //get_updated
      function get_updated(alltable){

            var temptable = alltable.filter(x=>x.status == 0)

            if(temptable.length > 0){
                  var date = moment().subtract(2, 'days').format('YYYY-MM-DD HH:mm:ss');
                  $.ajax({
                        type:'GET',
                        url: school_setup.es_cloudurl+'/monitoring/table/data/updated',
                        data:{
                              tablename: temptable[0].table,
                              date: date
                        },
                        success:function(data) {
                              process_update(temptable[0].table,data,alltable)
                        }
                  })
            }else{
                  // section_info_list()
                  if(!first_fetch_update){
                        section_info_list()
                  }else{
                        first_fetch_update = false
                  }
                  return false
            }
          
      }

      function process_update(tablename , updated_data,alltable){
           
            if (updated_data.length == 0){
                  $.each(alltable,function(a,b){
                        if(b.table == tablename){
                              b.status =1;
                        }
                  })
                  get_updated(alltable)
                  return false
            }

            var b = updated_data[0]
            var insert = false;
   
            if(tablename == 'sections'){
                  var check = all_sections.filter(x=>x.id == b.id)
                  if(check.length > 0){
                        insert  = true
                        var check = all_sections.filter(x=>x.id == b.id && x.updateddatetime == b.updateddatetime)
                        if(check.length > 0){
                              insert  = false
                        }
                  }
            }else if(tablename == 'sectiondetail'){
                  var check = sections.filter(x=>x.id == b.id)
                  if(check.length > 0){
                        insert  = true
                        var check = sections.filter(x=>x.id == b.id && x.sectiondetail_updateddatetime == b.updateddatetime)
                        if(check.length > 0){
                              insert  = false
                        }
                  }
            }

            if(!insert){
                  updated_data = updated_data.filter(x=>x.id != b.id)
                  process_update(tablename,updated_data,alltable)
            }else{
                  $('#reload_data_holder').removeAttr('hidden')
                  $.ajax({
                        type:'GET',
                        url:  '/synchornization/update',
                        data:{
                              tablename: tablename,
                              data:b
                        },
                        success:function(data) {
                              updated_data = updated_data.filter(x=>x.id != b.id)
                              process_update(tablename,updated_data,alltable)
                        },
                  })
            }
            
      }

      function get_deleted(alltable){
            var temptable = alltable.filter(x=>x.status == 0)

            if(temptable.length > 0){
                  var date = moment().subtract(2, 'days').format('YYYY-MM-DD HH:mm:ss');
                  $.ajax({
                        type:'GET',
                        url: school_setup.es_cloudurl+'/monitoring/table/data/deleted',
                        data:{
                                    tablename: temptable[0].table,
                                    date: date
                        },
                        success:function(data) {
                              process_deleted(temptable[0].table,data,alltable)
                        }
                  })
            }else{
                  // section_info_list()
                  if(!first_fetch_delete){
                        section_info_list()
                  }else{
                        first_fetch_delete = false
                  }
                  return false
            }
      }

      function process_deleted(tablename , deleted_data,alltable){
            if (deleted_data.length == 0){
                  $.each(alltable,function(a,b){
                        if(b.table == tablename){
                              b.status =1;
                        }
                  })
                  get_deleted(alltable)
                  return false
            }
            var insert = false;
            var b = deleted_data[0]

            if(tablename == 'sections'){
                  var check = all_sections.filter(x=>x.id == b.id)
                  if(check.length > 0){
                        insert  = true
                        var check = all_sections.filter(x=>x.id == b.id && x.deleteddatetime == b.deleteddatetime)
                        if(check > 0){
                              insert  = false
                        }
                  }else{
                        insert  = false
                  }
                
            }else if(tablename == 'sectiondetail'){
                  var check = sections.filter(x=>x.id == b.id)
                  if(check.length > 0){
                        insert  = true
                        var check = sections.filter(x=>x.id == b.id && x.sectiondetail_deleteddatetime == b.deleteddatetime)
                        if(check.length > 0){
                              insert  = false
                        }
                  }
            }
           
            if(!insert){
                  deleted_data = deleted_data.filter(x=>x.id != b.id)
                  process_deleted(tablename,deleted_data,alltable)
            }else{
                  $('#reload_data_holder').removeAttr('hidden')
                  $.ajax({
                        type:'GET',
                        url: '/synchornization/delete',
                        data:{
                              tablename: tablename,
                              data:b
                        },
                        success:function(data) {
                              deleted_data = deleted_data.filter(x=>x.id != b.id)
                              process_deleted(tablename,deleted_data,alltable)
                        },
                  })
            }
      }

      //get_updated
</script>

<script>

      var utype = @json(Session::get('currentPortal'));

      // $(document).ready(function(){

            $('.select2').select2();
          

            var room = @json($room);
            var strand = @json($strand);
            var all_sections = []
            var all_teachers = []
            var all_gradelevel = []
            var sections = []
            var selected_section
            var sy = @json($sy);

            $("#input_room").empty()
            $("#input_room").append('<option value="">Select Room<option>')
            $("#input_room").select2({
                  data: room,
                  allowClear: true,
                  placeholder: "Select Room",
            })


            $("#input_strand").empty()
            $("#input_strand").append('<option value="">Select Strand<option>')
            $("#input_strand").select2({
                  data: strand,
                  theme: 'bootstrap4',
                  placeholder: "Select Strand",
            })

            var schoolinfo = @json($schoolinfo);

           
            
            var process = 'create'

            // section_info_list()
            // loaddatatable()

            $(document).on('change','#filter_sy',function(){
                  sections = []
                  all_sections = []
                  loaddatatable()
                  section_info_list()
            })

          

            $(document).on('click','#section_to_create',function(){
                  if(process == 'create'){
                        section_create()           
                  }else if(process == 'edit'){
                        section_update()  
                  }
            })

            // $(document).on('click','#update_from_cloud',function(){
                 
            // })

            $(document).on('click','#reload_data',function(){
                  section_info_list()
            })

            $(document).on('click','.view_info',function(){

                  selected_section = $(this).attr('data-id')
                  var temp_section_info = sections.filter(x=>x.sectionid == selected_section)
                  $('#input_teacher').val(temp_section_info[0].teacherid).change()
                  $('#input_room').val(temp_section_info[0].roomid).change()
                  $('#input_session').val(temp_section_info[0].session).change()
                  $('#sectionname_label').val(temp_section_info[0].sectionname)
                  $('#levelname_label').val(temp_section_info[0].levelname)

                  if(temp_section_info[0].sd_issp == 1 ){
                        $('#input_issp').prop('checked',true)
                  }else{
                        $('#input_issp').prop('checked',false)
                  }


                  if(temp_section_info[0].sd_ismwsp == 1 ){
                        $('#input_mwsp').prop('checked',true)
                  }else{
                        $('#input_mwsp').prop('checked',false)
                  }

                  var temp_strand = []
                  if(temp_section_info[0].levelid == 14 || temp_section_info[0].levelid == 15 ){

                        $('#input_strand_holder').removeAttr('hidden')
                        $.each(temp_section_info[0].strand,function(a,b){
                              temp_strand.push(b.strandid)
                        })
                        $('#input_strand').val(temp_strand).change();
                  }else{
                        $('#input_strand_holder').attr('hidden','hidden')
                        $('#input_strand').val([]).change();
                  }
                  
                  if(temp_section_info[0].sundaySchool == 1){
                        $('#input_sundayclass').prop('checked',true)
                  }else{      
                        $('#input_sundayclass').prop('checked',false)
                  }

                  if(temp_section_info[0].sectactive == 1){
                        $('#input_active').prop('checked',true)
                  }else{      
                        $('#input_active').prop('checked',false)
                  }

                  if(all_teachers.length == 0){
                        section_teacher_list()
                  }

                  $('#section_detail_info').modal()

                  


            })

            $(document).on('click','.delete',function(){
                  selected_section = $(this).attr('data-id')
                  section_delete()
            })

            $(document).on('click','.delete_section',function(){
                  var temp_sectionid = $(this).attr('data-id')
                  var sectioninfo = all_sections.filter(x=>x.id == temp_sectionid)

                  var url = '/section/delete'
                  if(schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1'){
                        url = schoolinfo.es_cloudurl+'/section/delete'
                  }

                  $.ajax({
                        type:'GET',
                        url: url,
                        data:{
                              id:sectioninfo[0].id,
                              levelid: sectioninfo[0].levelid,
                              userid:currentuserid
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'info',
                                          title: data[0].data
                                    })
                                    if(schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1'){
                                          var temp_update_table = [{'table':'sections','status':0}]
                                          get_deleted(temp_update_table)
                                    }else{
                                          section_info_list()
                                    }
                              }else if(data[0].status == 2){
                                    Toast.fire({
                                          type: 'warning',
                                          title: data[0].data
                                    })
                              }else{
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].data
                                    })
                              }
                        },
                        error:function(){
                              Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong'
                              })
                        }
                  })
            })

            $(document).on('click','#update_section_info',function(){
                  update_section_info()
            })

            

            $(document).on('click','.edit_section',function(){
                  selected_section = $(this).attr('data-id')
                  var temp_sections = all_sections.filter(x=>x.id == selected_section)
                  if(all_gradelevel.length == 0){
                        gradelevel_list()
                  }

                  process = 'edit'
                  $('#input_sectionname').val(temp_sections[0].sectionname)
                  $('#input_gradelevel').val(temp_sections[0].levelid).change()
                  $('#section_modal').modal()       
                  $('#section_to_create')[0].innerHTML = '<i class="fas fa-save"></i> Update'    
                  
            })


            $(document).on('click','.add_section_to_detail',function(){

                  var temp_sectionid = $(this).attr('data-id')

                  var url = '/section/detail/create'
                  if(schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1'){
                        url = schoolinfo.es_cloudurl+'/section/detail/create'
                  }
                  
                  $.ajax({
                        type:'GET',
                        url: url,
                        data:{
                              syid:$('#filter_sy').val(),
                              sectionid: temp_sectionid,
                              userid:currentuserid
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    
                                    Toast.fire({
                                          type: 'info',
                                          title: data[0].data
                                    })
                                    if(schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1'){
                                          var temp_update_table = [{'table':'sectiondetail','status':0}]
                                          get_last_index(temp_update_table)
                                    }else{
                                          section_info_list()
                                    }
                                    // 
                                    // all_sections = all_sections.filter(x=>x.id != temp_sectionid)
                                    // section_list_datatable()
                              }else if(data[0].status == 2){
                                    Toast.fire({
                                          type: 'warning',
                                          title: data[0].data
                                    })
                              }else{
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].data
                                    })
                              }
                        },
                        error:function(){
                              Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong'
                              })
                        }
                  })
            })

          

           


            function fetch_all(){
                  get_last_index(getupdatetable())
                  get_updated(getupdatetable())
                  get_deleted(getupdatetable())
            }

            function getupdatetable(){
                  const temp_update_table = [     {'table':'sections','status':0},
                                                {'table':'sectiondetail','status':0},
                                                {'table':'sh_block','status':0},
                                                {'table':'sh_sectionblockassignment','status':0},
                                          ]

                  return temp_update_table
            }

       
            var first_run = true;
            section_info_list()

            function section_info_list(){
                  $.ajax({
                        type:'GET',
                        url: '/sections/info/list',
                        data:{
                              syid:$('#filter_sy').val()
                        },
                        success:function(data) {
                              sections = data
                             
                              section_list()
                              loaddatatable()
                        }
                  })
            }

           

            function update_section_info(){

                  var sundayclass = 0;
                  var active = 0;
                  var issp = 0;
                  var ismwsp = 0;

                  if($('#input_sundayclass').prop('checked') == true){
                        sundayclass = 1;
                  }

                  if($('#input_active').prop('checked') == true){
                        active = 1;
                  }

                  if($('#input_issp').prop('checked') == true){
                        issp = 1;
                  }

                  if($('#input_mwsp').prop('checked') == true){
                        ismwsp = 1;
                  }

                  var temp_info = sections.filter(x=>x.sectionid == selected_section)

                  var strand = []

                  if(temp_info[0].levelid)

                  var url = '/section/detial/update'

                  if(schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1'){
                        url = schoolinfo.es_cloudurl+'/section/detial/update'
                  }

                  $.ajax({
                        type:'GET',
                        url: url,
                        data:{
                              sectionid:selected_section,
                              syid:$('#filter_sy').val(),
                              teacherid:$('#input_teacher').val(),
                              roomid:$('#input_room').val(),
                              session:$('#input_session').val(),
                              sundayclass:sundayclass,
                              active:active,
                              issp:issp,
                              ismwsp:ismwsp,
                              levelid:temp_info[0].levelid,
                              strand:$('#input_strand').val(),
                              userid:currentuserid
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].data
                                    })
                                    if(schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1'){
                                         
                                          get_last_index([{'table':'sectiondetail','status':0},{'table':'sh_block','status':0},{'table':'sh_sectionblockassignment','status':0}])
                                          get_updated([{'table':'sections','status':0},{'table':'sectiondetail','status':0}])
                                          get_deleted([{'table':'sh_sectionblockassignment','status':0}])

                                    }else{
                                          section_info_list()
                                    }
                              }else{
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].data
                                    })
                              }
                        },
                        error:function(){
                              Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong!'
                              })
                        }
                  })
            }


            function section_create(){

                  var valid_input = true

                  if($('#input_sectionname').val() == ""){
                        Toast.fire({
                              type: 'warning',
                              title: 'Section name is required!'
                        })
                        return false
                  }

                  if($('#input_gradelevel').val() == ""){
                        Toast.fire({
                              type: 'warning',
                              title: 'Grade level is required!'
                        })
                        return false
                  }


                  var url = '/section/create'

                  if(schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1'){
                        url = schoolinfo.es_cloudurl+'/section/create'
                  }

                  if(valid_input){
                        $.ajax({
                              type:'GET',
                              url: url,
                              data:{
                                    syid:$('#filter_sy').val(),
                                    sectionname:$('#input_sectionname').val(),
                                    levelid:$('#input_gradelevel').val(),
                                    teacherid:$('#input_teacher').val()
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Created Successfully!'
                                          })
                                          if(schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1'){
                                                get_last_index([{'table':'sectiondetail','status':0},{'table':'sections','status':0}])
                                          }else{
                                                section_info_list()
                                          }
                                          
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].data
                                          })
                                    }
                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  }
            }

            function section_update(){

                  var check_duplications = sections.filter(x=>x.sectionname == $('#input_sectionname').val() && x.sectionid != selected_section)
                  valid_input = true
                  var sundayclass = 0;
                  var active = 0;

                  if(check_duplications.length > 0){
                        valid_input = false
                        Toast.fire({
                              type: 'warning',
                              title: 'Section already exist!'
                        })
                  }

                  if($('#input_sundayclass').prop('checked') == true){
                        sundayclass = 1;
                  }

                  if($('#input_active').prop('checked') == true){
                        active = 1;
                  }

                  var url = '/section/update'

                  if(schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1'){
                        url = schoolinfo.es_cloudurl+'/section/update'
                  }

                  if(valid_input){
                        $.ajax({
                              type:'GET',
                              url: url,
                              data:{
                                    id:selected_section,
                                    sectionname:$('#input_sectionname').val(),
                                    levelid:$('#input_gradelevel').val(),
                                    teacherid:$('#input_teacher').val()
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Updated Successfully!'
                                          })
                                          if(schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1'){
                                                get_updated([{'table':'sections','status':0}])
                                          }else{
                                                section_info_list()
                                          }
                                          // section_info_list()
                                          // loaddatatable() 
                                    }else if(data[0].status == 2){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].data
                                          })
                                    }
                                    else{
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong!'
                                          })
                                    }
                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  }
            }


            function section_delete(){

                  Swal.fire({
                        title: 'Do you want to remove section?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Remove'
                  }).then((result) => {
                        if (result.value) {

                              var url = '/section/detail/delete'

                              if(schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1'){
                                    url = schoolinfo.es_cloudurl+'/section/detail/delete'
                              }

                  
                              $.ajax({
                                    type:'GET',
                                    url: url,
                                    data:{
                                          id:selected_section,
                                          syid:$('#filter_sy').val(),
                                          userid:currentuserid
                                    },
                                    success:function(data) {
                                          if(data[0].status == 1){
                                                Toast.fire({
                                                      type: 'success',
                                                      title: 'Deleted Successfully!'
                                                })

                                                if(schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1'){
                                                      var temp_update_table = [{'table':'sections','status':0},{'table':'sectiondetail','status':0}]
                                                      get_deleted(temp_update_table)
                                                }else{
                                                      section_info_list()
                                                }
                                          }else if(data[0].status == 2){
                                                Toast.fire({
                                                      type: 'warning',
                                                      title: data[0].data
                                                })
                                          }
                                          else{
                                                Toast.fire({
                                                      type: 'error',
                                                      title: 'Something went wrong!'
                                                })
                                          }
                                    },
                                    error:function(){
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong!'
                                          })
                                    }
                              })
                        }
                  })

                  
            }

            function loaddatatable(){

                  var temp_sy = $('#filter_sy').val()
                  var check_sy = sy.filter(x=>x.id == temp_sy)
                  if(check_sy[0].ended == 1){
                        $('#add_section').remove()
                        $('.view_info').remove()
                        $('.delete').remove()
                  }

                  $('#total_sections').text(sections.length)
                  $('#total_active').text(sections.filter(x=>x.sectactive == 1).length)

                  $("#section_setup").DataTable({
                        destroy: true,
                        data:sections,
                        lengthChange: false,
                        stateSave: true,
                        columns: [
                              { "data": "session" },
                              { "data": "sectionname"  },
                              { "data": "sortid" },
                              { "data": "teacher" },
                              { "data": "roomname" },
                              { "data": "enrolled" },
                              { "data": null },
                              { "data": null },
                        ],
                        order: [
                                    [ 1, "asc" ]
                              ],
                        columnDefs: [
                                          {
                                                'targets': 0,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {

                                                      var session_text = '<span class="badge badge-secondary">WD</span>'

                                                      if(rowData.session == 1){
                                                            session_text = '<span class="badge badge-success">MS</span>'
                                                      }else if(rowData.session == 2){
                                                            session_text = '<span class="badge badge-warning">AS</span>'
                                                      }else if(rowData.session == 3){
                                                            session_text = '<span class="badge badge-danger">NS</span>'
                                                      }
                                                    
                                                      $(td)[0].innerHTML =  session_text
                                                      $(td).addClass('align-middle')
                                                     
                                                }
                                          },
                                          {
                                                'targets': 1,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {

                                                      var strand_text = '';
                                                      if(rowData.levelid == 14 || rowData.levelid == 15){
                                                            if(rowData.strand.length == 0){
                                                                  strand_text = '<p class="text-danger mb-0" style="font-size:.7rem">No Strand</p>'
                                                            }else{
                                                                  strand_text = '<p class="text-success mb-0" style="font-size:.7rem">'
                                                                  $.each(rowData.strand,function(a,b){
                                                                        strand_text+=b.blockname+', '
                                                                  })
                                                                  strand_text = strand_text.slice(0,-2)
                                                                  strand_text += '</p>'
                                                            }
                                                      }
                                                      
                                                      if(schoolinfo.projectsetup == 'online' ||  schoolinfo.processsetup == 'all' || schoolinfo.processsetup == 'hybrid2' ){
                                                            if(rowData.levelid == 14 || rowData.levelid == 15){
                                                                  var text = '<a class="mb-0" href="/principalPortalSectionProfile/'+rowData.sectionid+'">'+rowData.sectionname+' </a>'+strand_text;
                                                            }else{
                                                                  var text = '<a class="mb-0" href="/principalPortalSectionProfile/'+rowData.sectionid+'">'+rowData.sectionname+' </a>'
                                                            }
                                                      }else{
                                                            var text = rowData.sectionname+strand_text
                                                      }

                                                      if(rowData.sd_ismwsp){
                                                            text += ' <span class="badge badge-primary" style="font-size:.6rem !important">MSWP<span>'
                                                      }
                                                    
                                                      $(td)[0].innerHTML =  text
                                                     
                                                }
                                          },
                                          {
                                                'targets': 2,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      var text = '<a class="mb-0">'+rowData.levelname+'</a>';
                                                      if(rowData.sd_issp == 1){
                                                            text += '<p class="mb-0 text-primary" style="font-size:.7rem"><i>Special Section</i></p>'
                                                      }else{
                                                            text += '<p class="mb-0" style="font-size:.7rem"><i>Regular Section</i></p>'
                                                      }
                                                      
                                                      $(td)[0].innerHTML =  text
                                                      $(td).addClass('align-middle')
                                                     
                                                }
                                          },
                                          {
                                                'targets': 3,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      var text = '';
                                                     
                                                      if(rowData.teacher != "No adviser"){
                                                            var text = '<a class="mb-0">'+rowData.teacher+'</a>';
                                                            text += '<p class="mb-0 text-primary" style="font-size:.7rem"><i>'+rowData.tid+'</i></p>'
                                                      }

                                                      $(td)[0].innerHTML =  text
                                                      $(td).addClass('align-middle')
                                                     
                                                }
                                          },
                                          {
                                                'targets': 4,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {

                                                      var text = '';
                                                     
                                                      if(rowData.roomname != null){
                                                            text += '<a class="mb-0">'+rowData.roomname+'</a>'
                                                      }

                                                      if(rowData.capacity != null){
                                                            text += '<p class="mb-0 text-primary" style="font-size:.7rem"><i>Capacity: '+rowData.capacity+'</i></p>'
                                                      }

                                                      $(td)[0].innerHTML =  text
                                                      $(td).addClass('align-middle')
                                                     
                                                }
                                          },
                                          {
                                                'targets': 5,
                                                'orderable': false, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      var text = '<a data-sectionid="'+rowData.sectionid+'" data-levelid="'+rowData.levelid+'" class="view_enrolled" href="javascript:void(0)" >'+rowData.enrolled+' </a>'
                                                      $(td)[0].innerHTML =  text
                                                      $(td).addClass('text-center')
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 6,
                                                'orderable': false, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      if( ( check_sy[0].ended == 1 || (schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1') ) && utype != 17){
                                                            var buttons = null
                                                      }else{
                                                            var buttons = '<a href="#" class="view_info" data-id="'+rowData.sectionid+'"><i class="far fa-edit"></i></a>';
                                                      }

                                                      if(utype == 1){
                                                            buttons = ''
                                                      }
                                                      
                                                      $(td)[0].innerHTML =  buttons
                                                      $(td).addClass('text-center')
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 7,
                                                'orderable': false, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      if( ( check_sy[0].ended == 1 || (schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1') ) && utype != 17){
                                                            var buttons = null
                                                      }else{
                                                            var buttons = '<a href="#" class="delete" data-id="'+rowData.sectionid+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                      }

                                                      if(utype == 1){
                                                            buttons = ''
                                                      }

                                                      $(td)[0].innerHTML =  buttons
                                                      $(td).addClass('text-center')
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                    ]
                  });

                  $('.delete').attr('data-toggle','popover')
                  $('.delete').popover({
                        trigger: 'hover',
                        content: 'Delete'
                  })

                  $('.view_info').attr('data-toggle','popover')
                  $('.view_info').popover({
                        trigger: 'hover',
                        content: 'Update'
                  })

                  var update_text = ''
                  
                   var printable_options = 
                                    '<div class="btn-group ml-2">'+
                                         '<button type="button" class="btn btn-default btn-sm">Printables</button>'+
                                          '<button type="button" class="btn btn-default dropdown-toggle dropdown-icon btn-sm" data-toggle="dropdown">'+
                                          '<span class="sr-only">Toggle Dropdown</span>'+
                                          '</button>'+
                                          '<div class="dropdown-menu" role="menu">'+
                                                '<a class="dropdown-item print_sid" data-id="1" href="#">Student ID</a>'+
                                          '</div>'+
                                    '</div>'
                  
                  var label_text = ''
                  // if(schoolinfo.projectsetup == 'online' ||  schoolinfo.processsetup == 'all'){
                        var label_text = $($("#section_setup_wrapper")[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<div class="row"><div class="col-md-12" ><button class="btn btn-primary btn-sm" id="add_section" style="font-size: .8rem !important"><i class="fas fa-plus"></i> Add Section</button><button class="btn btn-primary btn-sm ml-2" hidden id="copy_section" style="font-size: .8rem !important"><i class="fas fa-copy"></i> Copy Section Information</button>'+update_text+printable_options+'</div></div>'
                  // }
                  
                  if(utype == 1){
                        $(label_text)[0].innerHTML = ''
                  }
                

                  if(check_sy[0].ended == 1 ){
                        $('#add_section').remove()
                        $('#copy_section').remove()
                  }
            
            }

      // })

      
</script>

<script>
      $(document).ready(function(){
         
            $(document).on('click','.print_sid',function(){
                  window.open('/section/printable/sid?syid='+$('#filter_sy').val(), '_blank');
            })
      })
</script>

<script>

 

      
      const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 2000,
            })

      function section_teacher_list(){
            $.ajax({
                  type:'GET',
                  url: '/sections/teachers',
                  data:{
                        syid:$('#filter_sy').val(),
                  },
                  success:function(data) {
                        all_teachers = data
                        $("#input_teacher").empty()
                        $("#input_teacher").append('<option value="">Select Adviser<option>')
                        $("#input_teacher").select2({
                              data: all_teachers,
                              allowClear: true,
                              placeholder: "Select Adviser",
                        })

                        var temp_section_info = sections.filter(x=>x.sectionid == selected_section)
                        $('#input_teacher').val(temp_section_info[0].teacherid).change()
                      
                  }
            })
      }

      function gradelevel_list(){
            $.ajax({
                  type:'GET',
                  url: '/sections/gradelevel',
                  data:{
                        syid:$('#filter_sy').val(),
                  },
                  success:function(data) {
                        all_gradelevel = data
                        $("#input_gradelevel").empty()
                        $("#input_gradelevel").append('<option value="">Select Grade Level<option>')
                        $("#input_gradelevel").select2({
                              data: all_gradelevel,
                              allowClear: true,
                              placeholder: "Select Grade Level",
                        })

                        var temp_section_info = all_sections.filter(x=>x.id == selected_section)
                        if(temp_section_info.length > 0){
                              $('#input_gradelevel').val(temp_section_info[0].levelid).change()
                        }
                  }
            })
      }

      function section_list(){
            $.ajax({
                  type:'GET',
                  url: '/sections/list',
                  data:{
                        syid:$('#filter_sy').val(),
                  },
                  success:function(data) {
                        all_sections = data
                        if( ( schoolinfo.projectsetup == 'offline' &&  schoolinfo.processsetup == 'hybrid1' ) && first_run){
                              fetch_all()
                              first_run = false
                        }
                        // section_info_list()
                        section_list_datatable()
                  }
            })
      }
      
      function section_list_datatable(){

            var temp_sections = []

            $.each(all_sections,function(a,b){
                  var check = sections.filter(x=>x.sectionid == b.id)
                  if(check == 0){
                        temp_sections.push(b)
                  }
            })

            $("#section_list").DataTable({
                  destroy: true,
                  data:temp_sections,
                  lengthChange: false,
                  stateSave: true,
                  autoWidth:false,
                  columns: [
                        { "data": null },
                        { "data": "sectionname"  },
                        { "data": "levelname" },
                        { "data": null },
                        { "data": null },
                  ],
                  columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="#" class="add_section_to_detail" data-id="'+rowData.id+'"><i class="fas fa-plus text-primary"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="#" class="edit_section" data-id="'+rowData.id+'"><i class="far fa-edit text-primary"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="#" class="delete_section" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                              ]
            });

            var label_text = $($("#section_list_wrapper")[0].children[0])[0].children[0]
            $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm" id="create_section" style="font-size: .8rem !important"><i class="fas fa-plus"></i> Create Section</button>'
      
      }

</script>

<script>
      $(document).ready(function(){

            var enrolled_learners = []

            $(document).on('click','.view_enrolled',function(){

                  $("#enrolled_learners_table").DataTable({
                        destroy: true,
                        data:[],
                        lengthChange: false,
                        stateSave: true,
                        autoWidth:false,
                  })

                  $('#enrolled_learners_modal').modal()

                  var levelid = $(this).attr('data-levelid')
                  var sectionid = $(this).attr('data-sectionid')

                  $.ajax({
                        type:'GET',
                        url: '/section/detail/enrolled',
                        data:{
                              syid:$('#filter_sy').val(),
                              sectionid:sectionid,
                              levelid:levelid
                        },
                        success:function(data) {
                             enrolled_learners = data 
                             enrolled_learners_datatable(levelid)
                        }
                  })

            })

            function enrolled_learners_datatable(levelid = null){




                  var table = $("#enrolled_learners_table").DataTable({
                        destroy: true,
                        data:enrolled_learners,
                        lengthChange: false,
                        stateSave: true,
                        autoWidth:false,
                        columns: [
                              { "data": "studentname" },
                              { "data": "description" },
                              { "data": null}
                        ],
                        columnDefs: [
                              {
                                    'targets': 2,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle')
                                          $(td).text(rowData.strandcode)
                                    }
                              },
                        ]
                  });


                  var column = table.column(2);
                  if(levelid == 14 || levelid == 15){
                        column.visible(true);
                  }else{
                        column.visible(false);
                  }

                  


            }
      })
</script>

<script>
      $(document).ready(function(){

            section_list_datatable()

            $(document).on('click','#create_section',function(){
                  if(all_gradelevel.length == 0){
                        gradelevel_list()
                  }
                  $('#input_sectionname').val("")
                  $('#input_gradelevel').val("").change()
                  process = 'create'
                  $('#section_to_create')[0].innerHTML = '<i class="fas fa-save"></i> Create'
                  $('#section_modal').modal()    
            })


            $(document).on('click','#add_section',function(){
                  $('#section_list_modal').modal()    
                  if(all_sections.length == 0){
                        section_list()
                  }
            })

            $(document).on('click','#copy_section',function(){
                  $('#copy_info_modal').modal()    
                  
            })
            $(document).on('click','#copy_section_info',function(){
                  $.ajax({
                        type:'GET',
                        url: '/section/detail/copy',
                        data:{
                              syid_from:$('#filter_sy').val(),
                              syid_to:$('#copy_sy').val()
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: data[0].data
                                    })
                              }else if(data[0].status == 2){
                                    Toast.fire({
                                          type: 'warning',
                                          title: data[0].data
                                    })
                              }else{
                                    Toast.fire({
                                          type: 'error',
                                          title: data[0].data
                                    })
                              }
                        },
                        error:function(){
                              Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong'
                              })
                        }
                  })
                  
            })
            
      })
</script>

@endsection


