
@php
      if(Session::get('currentPortal') == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(Session::get('currentPortal') == 14){    
		$extend = 'deanportal.layouts.app2';
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
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0;
            }
      </style>
@endsection


@section('content')


@php
      $sy = DB::table('sy')->orderBy('sydesc','desc')->get(); 
      $semester = DB::table('semester')->get(); 
      $teachers = DB::table('teacher')
                        ->where('usertypeid',14)
                        ->where('deleted',0)
                        ->where('isactive',1)
                        ->select(
                              'tid',
                              'teacher.id',
                              'lastname',
                              'firstname',
                              'middlename',
                              'suffix',
                              'title'
                        )
                        ->get();
            
      $faspriv = DB::table('faspriv')
                        ->where('usertype',14)
                        ->where('faspriv.deleted',0)
                        ->join('teacher',function($join){
                              $join->on('faspriv.userid','=','teacher.userid');
                              $join->where('teacher.deleted',0);
                        })
                        ->where('teacher.isactive',1)
                        ->select(
                              'tid',
                              'teacher.id',
                              'lastname',
                              'firstname',
                              'middlename',
                              'suffix',
                              'title'
                        )
                        ->get();

      $dean_list = array();

      foreach($teachers as $item){
            $temp_title = '';
            $temp_middle = '';
            $temp_suffix = '';
            if(isset($item->middlename)){
                  $temp_middle = $item->middlename[0].'.';
            }
            if(isset($item->title)){
                  $temp_title = $item->title.'. ';
            }
            if(isset($item->suffix)){
                  $temp_suffix = ', '.$item->suffix;
            }
            $item->text = $item->tid.' - '.$item->firstname.' '.$temp_middle.' '.$item->lastname.$temp_suffix.', '.$temp_title;
            array_push($dean_list , $item);      
      }

      foreach($faspriv as $item){
            $temp_title = '';
            $temp_middle = '';
            $temp_suffix = '';
            if(isset($item->middlename)){
                  $temp_middle = ' '.$item->middlename[0].'.';
            }
            if(isset($item->title)){
                  $temp_title = ', '.$item->title.'. ';
            }
            if(isset($item->suffix)){
                  $temp_suffix = ', '.$item->suffix;
            }
            $item->text = $item->tid.' - '.$item->firstname.$temp_middle.' '.$item->lastname.$temp_suffix.$temp_title;
            array_push($dean_list , $item);      
      }
      
@endphp



<div class="modal fade" id="dean_assignment_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-xl">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0 nav-bg">
                        <h4 class="modal-title" style="font-size: 1.1rem !important"><i class="fas fa-users-cog"></i> Dean Assignment</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body ">
                        <div class="row">
                              <div class="col-md-4">
                                    <h6 class="mb-1"><i class="fa fa-filter"></i> Filter</h6> 
                              </div>
                              <div class="col-md-8">
                                    
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-2  form-group mb-1">
                                    <label for="" class="mb-0">School Year</label>
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
                              <div class="col-md-2 form-group mb-1" hidden>
                                    <label for="">Semester</label>
                                    <select class="form-control select2" id="filter_semester">
                                          @foreach ($semester as $item)
                                                <option {{$item->isactive == 1 ? 'checked' : ''}} value="{{$item->id}}">{{$item->semester}}</option>
                                          @endforeach
                                    </select>
                              </div>
                        </div>
                        <div class="row" >
                              <div class="col-md-12">
                                    <table class="table-hover table-sm table table-striped table-bordered " id="dean_assignment_table" width="100%" >
                                          <thead>
                                                <tr>
                                                      <th width="14%">Abbrv.</th>
                                                      <th width="25%">College Description</th>
                                                      <th width="28%">Head Dean</th>
                                                      <th width="28%">Assistant Dean</th>
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


<div class="modal fade" id="college_form_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0 nav-bg">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">College Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body ">
                        <div class="row deaninfo">
                              <div class="col-md-12 form-group">
                                    <label for="">College Description</label>
                                    <textarea id="input_collegedesc" class="form-control form-control-sm"></textarea>
                              </div>
                        </div>
                        <div class="row deaninfo">
                              <div class="col-md-12 form-group">
                                    <label for="">College Abbreviation</label>
                                    <input id="input_collegeabrv" class="form-control form-control-sm">
                              </div>
                        </div>
                        <div class="row deaninfo ">
                              <div class="col-md-12 form-group">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="checkbox" id="isactive" checked>
                                        <label for="isactive">Active
                                        </label>
                                    </div>
                              </div>
                        </div>
                        <div class="row collegeinfo">
                              <div class="col-md-12 form-group">
                                    <label for="">Head Dean</label>
                                    <select id="input_head_dean" class="form-control form-control-sm">
                                    </select>
                              </div>
                        </div>
                        <div class="row collegeinfo">
                              <div class="col-md-12 form-group">
                                    <label for="">Assistant Dean</label>
                                    <select id="input_dean" class="form-control form-control-sm" multiple>
                                    </select>
                              </div>
                        </div>
                       
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary" id="college_form_button"><i class="fas fa-save"></i> Save</button>
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
                        <h1>Colleges</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Colleges</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow" style="">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12" >
                                                <table class="table-hover table-sm table table-striped table-bordered " id="college_datatable" width="100%" >
                                                      <thead>
                                                            <tr>
                                                                  <th width="1%"></th>
                                                                  <th width="12%">Abbrv.</th>
                                                                  <th width="31%">College Description</th>
                                                                  <th width="18%">Head Dean</th>
                                                                  <th width="18%">Assistant Dean</th>
                                                                  <th width="10%" class="text-center p-0 align-middle">Course(s)</th>
                                                                  <th width="4%"></th>
                                                                  <th width="4%"></th>
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
</section>

@endsection

@section('footerjavascript')
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>

      <script>

            var dean_assignment_list = []
            var allsy = @json($sy)

            $(document).on('click','#dean_assignment',function(){
                  $('#dean_assignment_modal').modal()
                  get_college_deanassignment_list()
            })
            
            $(document).on('change','#filter_sy',function(){
                  get_college_deanassignment_list()
            })

            function get_college_deanassignment_list(){
                  var syid = $('#filter_sy').val()
                  var semid = $('#filter_semester').val()
                  $.ajax({
                        type:'GET',
                        url:'/setup/college/list',
                              data:{
                              syid:syid,
                              withEnrollmentCount:true
                        },
                        success:function(data) {
                              if(data.length == 0){
                                    Toast.fire({
                                          type: 'warning',
                                          title: 'No college found.'
                                    })
                              }else{
                                    var total_enrolled = 0;
                                    $.each(data,function(a,b){
                                          total_enrolled += parseInt(b.enrolled)
                                    })
                                    // Toast.fire({
                                    //       type: 'warning',
                                    //       title: total_enrolled+' student(s) enrolled!'
                                    // })
                                    dean_assignment_list = data
                                    load_college_deanassignment_datatable()
                              }
                        }
                  })
            }

            function load_college_deanassignment_datatable(){


                  
                  var buttonenabled = true

                  var checkended = allsy.filter(x=>x.id == $('#filter_sy').val())
                  if(checkended[0].ended == 1){
                        buttonenabled = false
                  }


                  $("#dean_assignment_table").DataTable({
                        destroy: true,
                        data:dean_assignment_list,
                        lengthChange : false,
                        stateSave: true,
                        columns: [
                                    { "data": "collegeabrv" },
                                    { "data": "collegeDesc" },
                                    { "data": "dean" },
                                    { "data": "dean" },
                                    { "data": "search" },
                              ],
                        columnDefs: [
                              {
                                    'targets': 0,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle')
                                          
                                    }
                              },
                              {
                                    'targets': 1,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle')
                                          
                                    }
                              },
                              {
                                    'targets': 2,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var temp_dean = rowData.dean
                                          var temp_text = ''
                                          if(temp_dean.filter(x=>x.type == 'head').length > 0){
                                                      $.each(temp_dean.filter(x=>x.type == 'head'),function(a,b){
                                                            temp_text += b.deanname
                                                            if(temp_dean.length-1 != a){
                                                                  temp_text += ' <br> '
                                                            }
                                                      })
                                                      $(td)[0].innerHTML  = temp_text
                                          }else{
                                                      $(td).text(null)
                                          }
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 3,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var temp_dean = rowData.dean
                                          var temp_text = ''

                                          if(temp_dean.filter(x=>x.type == 'assistant').length > 0){
                                                      $.each(temp_dean.filter(x=>x.type == 'assistant'),function(a,b){
                                                            temp_text += b.deanname
                                                            if(temp_dean.length-1 != a){
                                                                  temp_text += ' <br> '
                                                            }
                                                      })
                                                      $(td)[0].innerHTML  = temp_text
                                          }else{
                                                      $(td).text(null)
                                          }
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 4,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                          if(buttonenabled){
                                                var buttons = '<a href="javascript:void(0)" class="update_college" data-id="'+rowData.id+'" data-type="deaninfo"><i class="far fa-edit"></i></a>';
                                          }else{
                                                var buttons = ''
                                          }
                                         
                                         
                                          $(td)[0].innerHTML =  buttons
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                          
                                    }
                              },
                              
                        ],
                        initComplete:function( settings, json){
                              $('.update_college').attr('data-toggle', 'popover').attr('data-html', 'true');
                              $('.update_college').popover({
                                    trigger: 'hover',
                                    offset: '0 5',
                                    content: `<span>Update Dean</span>`,
                              });

                              $('.delete_college').attr('data-toggle', 'popover').attr('data-html', 'true');
                              $('.delete_college').popover({
                                    trigger: 'hover',
                                    offset: '0 5',
                                    content: `<span>Delete College</span>`,
                              });
                        }
                        
                  });

               

            }

      </script>

      <script>
            $(document).ready(function(){

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  $('.select2').select2()
                  
                  var dean_list = @json($dean_list)

                  $("#input_dean").empty()
                  $("#input_dean").select2({
                        data: dean_list,
                        placeholder: "Select Dean",
                        theme: 'bootstrap4'
                  })

                  $("#input_head_dean").empty()
                  $("#input_head_dean").append('<option value="">Select Dean</option')
                  $("#input_head_dean").select2({
                        data: dean_list,
                        allowClear:true,
                        placeholder: "Select Dean",
                        theme: 'bootstrap4'
                  })
          
                  var college_list = []
                  var selected_id = null

                  load_college_datatable()
                  get_college_list()
                

                  $(document).on('click','#button_to_modal_college',function(){
                        $('#input_collegedesc').val("")
                        $('#input_collegeabrv').val("")
                        $('#input_dean').val("").change()
                        $("#input_head_dean").val("").change()

                        dean_list = @json($dean_list)

                        $("#input_dean").empty()
                        $("#input_dean").select2({
                              data: dean_list,
                              placeholder: "Select Dean",
                              theme: 'bootstrap4'
                        })

                        $("#input_head_dean").empty()
                        $("#input_head_dean").append('<option value="">Select Dean</option')
                        $("#input_head_dean").select2({
                              data: dean_list,
                              allowClear:true,
                              placeholder: "Select Dean",
                              theme: 'bootstrap4'
                        })

                        $('.deaninfo').removeAttr('hidden')
                        $('.collegeinfo').removeAttr('hidden')

                        $('#input_collegedesc').removeAttr('disabled','disabled')
                        $('#input_collegeabrv').removeAttr('disabled','disabled')
                        $('#isactive').attr('disabled','disabled')

                        $('#college_form_modal').modal()
                        $('#college_form_button').removeClass('btn-success')
                        $('#college_form_button').addClass('btn-primary')
                        $('#college_form_button')[0].innerHTML = '<i class="fas fa-save"></i> Save'
                        $('#college_form_button').attr('data-proccess','create')
                  })

                  var update_type = null

                  $(document).on('click','.update_college',function(){
                        selected_id = $(this).attr('data-id')

                        update_type = $(this).attr('data-type')

                        $('.deaninfo').removeAttr('hidden')
                        $('.collegeinfo').removeAttr('hidden')

                     

                        if(update_type == 'deaninfo'){
                              var temp_college_info = dean_assignment_list.filter(x=>x.id == selected_id)
                              $('#isactive').attr('disabled','disabled')

                              $('#input_collegedesc').attr('disabled','disabled')
                              $('#input_collegeabrv').attr('disabled','disabled')

                        }else if(update_type == 'collegeinfo'){
                              var temp_college_info = college_list.filter(x=>x.id == selected_id)
                              $('.collegeinfo').attr('hidden','hidden')
                              $('#isactive').removeAttr('disabled','disabled')

                              if(temp_college_info[0].withenrollees){
                                    $('#input_collegedesc').attr('disabled','disabled')
                                    $('#input_collegeabrv').attr('disabled','disabled')
                              }else{
                                    $('#input_collegedesc').removeAttr('disabled','disabled')
                                    $('#input_collegeabrv').removeAttr('disabled','disabled')
                              }
                             
                        }


                        $('#input_collegedesc').val(temp_college_info[0].collegeDesc)
                        $('#input_collegeabrv').val(temp_college_info[0].collegeabrv)


                        

                       

                        if(temp_college_info[0].cisactive == 0){
                              $('#isactive').prop('checked',false)
                        }else{
                              $('#isactive').prop('checked',true)
                        }

                        var temp_dean = []
                        $.each(temp_college_info[0].dean.filter(x=>x.type == 'assistant'),function(a,b){
                              var temp_check = dean_list.filter(x=>x.id == b.id)
                              if(temp_check.length == 0){
                                    dean_list.push(b)
                              }
                              temp_dean.push(b.id)
                        })

                        $("#input_dean").empty()
                        $("#input_dean").select2({
                              data: dean_list,
                              placeholder: "Select Dean",
                              theme: 'bootstrap4'
                        })

                        $("#input_head_dean").empty()
                        $("#input_head_dean").append('<option value="">Select Dean</option')
                        $("#input_head_dean").select2({
                              data: dean_list,
                              allowClear:true,
                              placeholder: "Select Dean",
                              theme: 'bootstrap4'
                        })

                        var checkheaddean = temp_college_info[0].dean.filter(x=>x.type == 'head')

                        if(checkheaddean.length > 0){
                              $("#input_head_dean").val(checkheaddean[0].id).change()
                        }else{
                              $("#input_head_dean").val("").change()
                        }
                        

                        $('#input_dean').val(temp_dean).change()
                        $('#college_form_modal').modal()
                        $('#college_form_button').removeClass('btn-primary')
                        $('#college_form_button').addClass('btn-success')
                        $('#college_form_button')[0].innerHTML = '<i class="fas fa-save"></i> Update'
                        $('#college_form_button').attr('data-proccess','update')
                  })

                  $(document).on('click','.delete_college',function(){
                        selected_id = $(this).attr('data-id')
                        Swal.fire({
                              title: 'Do you want to remove college?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {
                                     delete_college()
                              }
                        })
                  })

                  $(document).on('click','#college_form_button',function(){
                        if($(this).attr('data-proccess') == 'update'){
                              update_college()
                        }else if($(this).attr('data-proccess') == 'create'){
                              create_college()
                        }
                  })


                  $(document).on('change','#filter_semester',function(){
                        get_college_list()
                  })


                  function get_college_list(){
                        var syid = $('#filter_sy').val()
                        var semid = $('#filter_semester').val()
                        $.ajax({
                              type:'GET',
                              url:'/setup/college/list',
                                    data:{
                                    // syid:syid,
                                    // semid:semid,
                                    withEnrollmentCount:true
                              },
                              success:function(data) {
                                    if(data.length == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'No college found.'
                                          })
                                    }else{
                                          var total_enrolled = 0;
                                          $.each(data,function(a,b){
                                                total_enrolled += parseInt(b.enrolled)
                                          })
                                          // Toast.fire({
                                          //       type: 'warning',
                                          //       title: total_enrolled+' student(s) enrolled!'
                                          // })
                                          college_list = data
                                          load_college_datatable()
                                    }
                              }
                        })
                  }

                  function create_college(){

                        var collegedesc = $('#input_collegedesc').val()
                        var collegeabrv = $('#input_collegeabrv').val()
                        var syid = $('#filter_sy').val()
                        var semid = $('#filter_semester').val()

                        if(collegedesc == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: "College Description is empty!"
                              })
                              return false
                        }

                        $.ajax({
                              type:'GET',
                              url:'/setup/college/create',
                              data:{
                                    syid:syid,
                                    semid:semid,
                                    dean:$('#input_dean').val(),
                                    headdean:$('#input_head_dean').val(),
                                    collegedesc:collegedesc,
                                    collegeabrv:collegeabrv
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }else{
                                          get_college_list()
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                    }
                              }
                        })

                  }

                  function update_college(){

                        var id = selected_id
                        var collegedesc = $('#input_collegedesc').val()
                        var collegeabrv = $('#input_collegeabrv').val()
                        var syid = $('#filter_sy').val()
                        var semid = $('#filter_semester').val()
                        var isactive = 1

                     
                        
                        if(update_type == 'collegeinfo'){
                              syid = null
                              semid = null
                        }


                        if(collegedesc == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Course Description is empty!"
                              })
                              return false
                        }

                        if(collegeabrv == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Course Abbreviation is empty!"
                              })
                              return false
                        }

                        if($('#isactive').prop('checked') == false){
                              isactive = 0
                        }
                        
                        $.ajax({
                              type:'GET',
                              url:'/setup/college/update',
                              data:{
                                    syid:syid,
                                    semid:semid,
                                    dean:$('#input_dean').val(),
                                    headdean:$('#input_head_dean').val(),
                                    collegedesc:collegedesc,
                                    collegeabrv:collegeabrv,
                                    id:id,
                                    isactive:isactive,
                                    updatetype:update_type
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else{
                                         

                                          if(update_type == 'deaninfo'){
                                                get_college_deanassignment_list()
                                                get_college_list()
                                          }else{
                                                get_college_list()
                                          }
                                          

                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  }

                  function delete_college(){
                        var id = selected_id
                        $.ajax({
                              type:'GET',
                              url:'/setup/college/delete',
                              data:{
                                    id:id
                              },
                              success:function(data) {
                                    if(data[0].status == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else{
                                          get_college_list()
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  }

                  function load_college_datatable(){


                        $("#college_datatable").DataTable({
                              destroy: true,
                              data:college_list,
                              lengthChange : false,
                              stateSave: true,
                              columns: [
                                          { "data": "search" },
                                          { "data": "collegeabrv" },
                                          { "data": "collegeDesc" },
                                          { "data": "dean" },
                                          { "data": "dean" },
                                          { "data": "courses" },
                                          { "data": null },
                                          { "data": null },
                                    ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                
                                                if(rowData.cisactive == 0){
                                                      $(td).addClass('bg-danger')
                                                }else{
                                                      $(td).addClass('bg-success')
                                                }

                                                console.log(rowData.cisactive)
                                                $(td).text(null)
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                               var temp_dean = rowData.dean
                                               var temp_text = ''

                                               if(temp_dean.filter(x=>x.type=='head').length > 0){
                                                      $.each(temp_dean.filter(x=>x.type=='head'),function(a,b){
                                                            temp_text += b.deanname
                                                            if(temp_dean.length-1 != a){
                                                                  temp_text += ' <br> '
                                                            }
                                                      })
                                                      $(td)[0].innerHTML = temp_text
                                               }else{
                                                      $(td).text(null)
                                               }
                                               $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var temp_dean = rowData.dean
                                                var temp_text = ''

                                                if(temp_dean.filter(x=>x.type=='assistant').length > 0){
                                                      $.each(temp_dean.filter(x=>x.type=='assistant'),function(a,b){
                                                            temp_text += b.deanname
                                                            if(temp_dean.length-1 != a){
                                                                  temp_text += ' <br> '
                                                            }
                                                      })
                                                      $(td)[0].innerHTML = temp_text
                                                }else{
                                                      $(td).text(null)
                                                }
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
                                                var buttons = '<a href="javascript:void(0)" class="update_college" data-id="'+rowData.id+'"  data-type="collegeinfo"><i class="far fa-edit"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    },
                                    {
                                          'targets': 7,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var disabled = '';
                                                var buttons = '<a href="javascript:void(0)" '+disabled+' class="delete_college" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                              ],
                              initComplete:function( settings, json){
                                    $('.update_college').attr('data-toggle', 'popover').attr('data-html', 'true');
                                    $('.update_college').popover({
                                          trigger: 'hover',
                                          offset: '0 5',
                                          content: `<span>Update College</span>`,
                                    });

                                    $('.delete_college').attr('data-toggle', 'popover').attr('data-html', 'true');
                                    $('.delete_college').popover({
                                          trigger: 'hover',
                                          offset: '0 5',
                                          content: `<span>Delete College</span>`,
                                    });
                              }
                              
                        });

                        var label_text = $($("#college_datatable_wrapper")[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm mt-1" id="button_to_modal_college"><i class="fas fa-plus"></i> Create College</button><button class="btn btn-success btn-sm mt-1 ml-2" id="dean_assignment"><i class="fas fa-users-cog"></i> Dean Assignment</button>'
                  
                  }

            })
      </script>

      {{-- IU --}}
      <script>

            $(document).ready(function(){

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

                  $(document).on('input','#per',function(){
                        if($(this).val() > 100){
                              $(this).val(100)
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Subject percentage exceeds 100!'
                              })
                        }
                  })
            })
      </script>

@endsection


