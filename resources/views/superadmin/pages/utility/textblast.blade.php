
@php

      $check_refid = DB::table('usertype')
        ->where('id', Session::get('currentPortal'))
        ->select('refid', 'resourcepath')
        ->first();


      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(auth()->user()->type == 2 || Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
      }else if(auth()->user()->type == 3 || Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 6 || Session::get('currentPortal') == 6){
            $extend = 'adminPortal.layouts.app2';
      }else if($check_refid->refid == 35){
            $extend = 'tesda.layouts.app2';
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
                  border: 0 !important;
            }
            input[type=search]{
                  height: calc(1.7em + 2px) !important;
            }
            .alert-warning {
                  color: #856404;
                  background-color: #fff3cd;
                  border-color: #ffeeba;
            }
            .alert-success {
                  color: #155724;
                  background-color: #d4edda;
                  border-color: #c3e6cb;
            }
            .alert-info {
                  color: #0c5460;
                  background-color: #d1ecf1;
                  border-color: #bee5eb;
            }
            .alert-danger {
                  color: #721c24;
                  background-color: #f8d7da;
                  border-color: #f5c6cb;
            }
     
      </style>
@endsection


@section('content')

@php
   $schoolinfo = DB::table('schoolinfo')->first();
   $sy = DB::table('sy')->orderBy('sydesc')->get(); 
   $semester = DB::table('semester')->get(); 

     

      if(auth()->user()->type == 2 || Session::get('currentPortal') == 2){

            $teacherid = DB::table('teacher')->where('tid',auth()->user()->email)->select('id')->first()->id;

            $acad = DB::table('academicprogram')
                        ->where('principalid',$teacherid)
                        ->select('id','progname as text')
                        ->get(); 

            $acad_array = array();

            foreach($acad as $item){
                  array_push($acad_array,$item->id);
            }
            
            $gradelevel = DB::table('gradelevel')
                  ->where('deleted',0)
                  ->whereIn('acadprogid',$acad_array)
                  ->orderBy('sortid')
                  ->select(
                        'id',
                        'levelname as text',
                        'levelname',
                        'acadprogid'
                  )
                  ->get(); 
            

      }else{

            $acad = DB::table('academicprogram')
                        ->select('id','progname as text')
                        ->get(); 

            $gradelevel = DB::table('gradelevel')
                  ->where('deleted',0)
                  ->orderBy('sortid')
                  ->select(
                        'id',
                        'levelname as text',
                        'levelname',
                        'acadprogid'
                  )
                  ->get(); 
      }
@endphp


<div class="modal fade" id="modal_1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" id="modal_1_title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body" style="font-size:12px !important">
                        <table class="table table-sm display table-striped" id="student_list" width="100%" ">
                              <thead>
                                    <tr>
                                          <th width="100%">Student Name</th>
                                          <th width="100%">Contact No.</th>
                                    </tr>
                              </thead>
                        </table>
                  </div>
            </div>
      </div>
</div>   

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Text Blast </h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Text Blast</li>
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
                                    <div class="col-md-2  form-group mb-0">
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
                                    <div class="col-md-2  form-group mb-0">
                                          <label for="">Semester</label>
                                          <select class="form-control select2 form-control-sm" id="filter_sem">
                                                @foreach ($semester as $item)
                                                      @if($item->isactive == 1)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->semester}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->semester}}</option>
                                                      @endif
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-2  form-group mb-0">
                                          <label for="">Academic Program</label>
                                          <select class="form-control select2 form-control-sm" id="filter_acad"></select>
                                    </div>
                                    <div class="col-md-2  form-group mb-0">
                                          <label for="">Grade Level</label>
                                          <select class="form-control select2 form-control-sm" id="filter_level"></select>
                                    </div>
                                    
                              </div>
                          </div>
                        </div>
                  </div>
            </div>
            <div class="row message_prompt" id="message1" hidden>
                  <div class="col-md-12">
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                              <strong>Composing Message.</strong> Please wait. This might take a few minutes, depending on the stability of the internet connectivity.
                        </div>
                  </div>
            </div>
            <div class="row message_prompt" id="message2">
                  {{-- <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                              <strong>Successfull.</strong> Message may take up 3 days to arrive to the recipient depending on the number of recipients.
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                        </div>
                  </div> --}}
            </div>
            <div class="row message_prompt" id="message3">
                  {{-- <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                              <strong>Something went wrong.</strong> Please try again or contact provider. 
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                        </div>
                  </div> --}}
            </div>
            <div class="row">
                  <div class="col-md-6">
                        <div class="card shadow">
                              <div class="card-header border-0 pb-0">
                                    <h5 class="card-title">Message Content</h5>
                              </div>
                              <div class="card-body pt-0">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <p class="text-danger"><i>Per message is only limited to 150 characters</i></p>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12 form-group" style=" font-size:11px !important">
                                                <label for="">Message 1</label>
                                                <textarea rows="3" class="form-control form-control-sm message" data-id="1"></textarea>
                                                <span>Character Count: <span class="char_count" data-id="1"></span></span>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12 form-group" style=" font-size:11px !important">
                                                <label for="">Message 2</label>
                                                <textarea tabindex="5" rows="3" class="form-control form-control-sm message" data-id="2"></textarea>
                                                <span>Character Count: <span class="char_count" data-id="2"></span></span>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
                  <div class="col-md-6">
                        <div class="card shadow">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <h5 class="card-title">Student Contact Information</h5>
                                          </div>
                                    </div>
                                    <div class="row mt-2">
                                          <div class="col-md-12" style="font-size:11px !important">
                                                <table class="table table-bordered mb-0">
                                                      <tr>
                                                            <td width="70%" class="align-middle p-1">Valid Student Contact Number</td>
                                                            <td width="15%" class="text-center p-1 align-middle">
                                                                  <a href="javascript:void(0)" id="valid_student_contact_count"></a>
                                                            </td>
                                                            <td width="15%" class="text-center  align-middle p-1"><button style="font-size:.6rem !important" class="btn btn-sm btn-primary send" data-type="student" hidden>SEND</button></td>
                                                      </tr>
                                                      <tr>
                                                            <td class="align-middle  p-1">Invalid Student Contact Number</td>
                                                            <td class="text-center p-1 align-middle">
                                                                  <a href="javascript:void(0)" id="invalid_student_contact_count"></a>
                                                            </td>
                                                            <td  class="text-center  align-middle  p-1"></td>
                                                      </tr>
                                                      <tr>
                                                            <td class="align-middle  p-1">No Student Contact Number</td>
                                                            <td class="text-center  p-1 align-middle">
                                                                  <a href="javascript:void(0)" id="no_student_contact_count"></a>
                                                            </td>
                                                            <td  class="text-center  align-middle  p-1"></td>
                                                      </tr>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>

                        <div class="card shadow">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12">
                                                <h5 class="card-title">Parent Contact Information</h5>
                                          </div>
                                    </div>
                                    <div class="row mt-2">
                                          <div class="col-md-12" style=" font-size:11px !important">
                                                <table class="table table-bordered mb-0">
                                                      <tr>
                                                            <td width="70%" class="align-middle p-1">Valid Parent Contact Number</td>
                                                            <td width="15%" class="text-center p-1 align-middle">
                                                                  <a href="javascript:void(0)" id="valid_parent_contact_count"></a>
                                                            </td>
                                                            <td width="15%" class="text-center p-1 align-middle"><button style="font-size:.6rem !important" class="btn btn-sm btn-primary send" data-type="parent" hidden>SEND</button></td>
                                                      </tr>
                                                      <tr>
                                                            <td class="align-middle p-1">Invalid Parent Contact Number</td>
                                                            <td class="text-center p-1 align-middle">
                                                                  <a href="javascript:void(0)" id="invalid_parent_contact_count"></a>
                                                            </td>
                                                            <td  class="text-center align-middle p-1"></td>
                                                      </tr>
                                                      <tr>
                                                            <td class="align-middle p-1">No Parent Contact Number</td>
                                                            <td  class="text-center p-1 align-middle"><a href="javascript:void(0)" id="no_parent_contact_count"></a></td>
                                                            <td  class="text-center p-1 align-middle"></td>
                                                      </tr>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
               
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-2">
                                                <select class="form-control select2 form-control-sm" id="filter_smsstatus">
                                                      <option value="0">Unsent</option>
                                                      <option value="1">Sent</option>
                                                </select>
                                          </div>
                                    </div>
                                    <div class="row mt-2">
                                          <div class="col-md-12" style=" font-size:11px !important">
                                                <table class="table table-bordered table-sm mb-0" id="textblast_datatable">
                                                      <thead>
                                                            <tr>
                                                                  <th width="45%">Message</th>
                                                                  <th width="10%">Reciever</th>
                                                                  <th width="12%">Date Created</th>
                                                                  <th width="7%">Type</th>
                                                                  <th width="26%">Student</th>
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

            // textbastDatatable()

            $(document).on('change','#filter_smsstatus',function(){
                  textbastDatatable()
            })

            var projectsetup = []
            var syncEnabled = false;
            var button_enable = null;

            getProjectSetup()

            function getProjectSetup(){
                  $.ajax({
                        type:'GET',
                        url:'/api/schoolinfo/projectsetup',
                        success:function(data) {
                              projectsetup = data
                              if(projectsetup[0].projectsetup == 'offline'){
                                    check_online_connection()
                              }else{
                                    get_students()
                                    textbastDatatable()
                                    $('.send').removeAttr('hidden')
                              }
                        }
                  })
            }

            function check_online_connection(){
                  $.ajax({
                        type:'GET',
                        url: projectsetup[0].es_cloudurl+'/checkconnection',
                        success:function(data) {
                              $('.send').removeAttr('hidden')
                              button_enable = true
                              get_students()
                              textbastDatatable()
                        }, 
                        error:function(){
                              $('#online_status').text('Not Connected')
                        }
                  })
            }

            function textbastDatatable(){

                  var url =  '/api/textblast/datatable'

                  if( button_enable){
                        url = projectsetup[0].es_cloudurl+'/api/textblast/datatable'
                  }

                  $('#textblast_datatable').DataTable({
                        destroy: true,
                        autoWidth: false,
                        lengthChange: false,
                        stateSave: true,
                        serverSide: true,
                        processing: true,
                        ajax:{
                              url: url,
                              type: 'GET',
                              data:{
                                    smsstatus:$('#filter_smsstatus').val()
                              },
                              dataSrc: function ( json ) {
                                    // buildings_datatable = json.data
                                    return json.data;
                              }
                        },
                        columns: [
                                    { "data": "message" },
                                    { "data": "receiver" },
                                    { "data": "createddatetime" },
                                    { "data": null },
                                    { "data": "studentname" },
                              ],
                        columnDefs: [
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
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 3,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          if(rowData.receivertype == 1){
                                                $(td)[0].innerHTML =  'Student' 
                                          }else{
                                                $(td)[0].innerHTML =  'Parent' 
                                          }
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 4,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle')
                                    }
                              },
                        ]
                  });

                  var label_text = $($("#textblast_datatable_wrapper")[0].children[0])[0].children[0]

                  $(label_text)[0].innerHTML = '<h6 class="mb-0">Text Blast Monitoring</h6>'
                 

                  

                  }
      </script>

      <script>

            // $(document).ready(function(){

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  var school_year = @json($sy);
                  $("#filter_sy").select2({
                        data: filter_sy,
                        placeholder: "School Year",
                  })


                  $("#filter_smsstatus").select2({
                        data: filter_sy,
                        placeholder: "Text Blast Status",
                  })

                  

                  var acad = @json($acad);
                  $("#filter_acad").append('<option value="">All</option>')
                  $("#filter_acad").select2({
                        data: acad,
                        allowClear: true,
                        placeholder: "All",
                  })


                  var gradelevel = @json($gradelevel);
                  $("#filter_level").append('<option value="">All</option>')
                  $("#filter_level").select2({
                        data: gradelevel,
                        allowClear: true,
                        placeholder: "All",
                  })


                  var sem = @json($semester);
                  $("#filter_sem").select2({
                        data: sem,
                        placeholder: "Semester",
                  })

                  $(document).on('change','#filter_acad',function(){
                        if($(this).val() == ""){
                              var temp_levelid = gradelevel
                        }else{
                              var temp_acad = $(this).val()
                              var temp_levelid = gradelevel.filter(x=>x.acadprogid == temp_acad)
                        }

                        $("#filter_level").empty()
                        $("#filter_level").append('<option value="">All</option>')
                        $("#filter_level").select2({
                              data: temp_levelid,
                              allowClear: true,
                              placeholder: "All",
                        })
                        get_students()
                  })

                  $(document).on('change','#filter_level',function(){
                        get_students()
                  })

                  $(document).on('change','#filter_sy',function(){
                        get_students()
                  })

                  $(document).on('change','#filter_sem',function(){
                        get_students()
                  })

                  var userid = @json(auth()->user()->id)


                  $(document).on('click','.send',function(){

                        $('.message_prompt').attr('hidden','hidden')

                        var type = $(this).attr('data-type')

                        var messages = []

                        $('.message').each(function(a,b){
                              if($(b).val() != ""){
                                    messages.push($(b).val())
                              }
                        })

                        if(messages.length == 0){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Message is empty'
                              });
                              return false;
                        }

                        var temp_acad = $('#filter_acad').val()
                        var levelid = $('#filter_level').val()
                        var selected_gradelevel = []
                        if(levelid != ""){
                              selected_gradelevel.push(levelid)
                        }else{
                              $('#filter_level option').each(function(){
                                    if($(this).val() != ""){
                                          selected_gradelevel.push($(this).val())
                                    }
                              })
                        }

                        var url =  '/textblast/send'

                        if( button_enable){
                              url = projectsetup[0].es_cloudurl+'/textblast/send'
                        }

                        $('#message1').removeAttr('hidden')
                        $('.send').attr('disabled','disabled')

                        $.ajax({
                              type:'GET',
                              url: url,
                              data:{
                                    syid:$('#filter_sy').val(),
                                    levelid:selected_gradelevel,
                                    semid:$('#filter_sem').val(),
                                    userid:userid,
                                    type:type,
                                    message:messages
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          });
                                          textbastDatatable()
                                          $('#message1').attr('hidden','hidden')
                                          $('#message2').removeAttr('hidden')
                                          $('#message2')[0].innerHTML = `<div class="col-md-12">
                                                                              <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                                                    <strong>Successfull.</strong> Message may take up 3 days to arrive to the recipient depending on the number of recipients.
                                                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                              </div>
                                                                        </div>`
                                          $('.send').removeAttr('disabled')

                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          });
                                          $('#message1').attr('hidden','hidden')
                                          $('#message3').removeAttr('hidden')
                                          $('#message3')[0].innerHTML = `<div class="col-md-12">
                                                                              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                                                    <strong>Something went wrong.</strong> Please try again or contact provider. 
                                                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                              </div>
                                                                        </div>`
                                          $('.send').removeAttr('disabled')
                                    }
                              },
                              error:function(){
                                    $('#message1').attr('hidden','hidden')
                                    $('#message3').removeAttr('hidden')
                                    $('#message3')[0].innerHTML = `<div class="col-md-12">
                                                                              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                                                    <strong>Something went wrong.</strong> Please try again or contact provider. 
                                                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                              </div>
                                                                        </div>`
                                    $('.send').removeAttr('disabled')
                              }
                        })
                  })

                  $(document).on('input','.message',function(){
                        if($(this).val().length > 150){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Maximum Characters'
                              });
                              var text = $(this).val().substring(0,150)
                              $(this).val(text)
                        }
                        $('.char_count[data-id="'+$(this).attr('data-id')+'"]').text($(this).val().length )
                  })

                 

                  function get_students(){
                        var temp_acad = $('#filter_acad').val()
                        var levelid = $('#filter_level').val()
                        var selected_gradelevel = []
                        if(levelid != ""){
                              selected_gradelevel.push(levelid)
                        }else{
                              $('#filter_level option').each(function(){
                                    if($(this).val() != ""){
                                          selected_gradelevel.push($(this).val())
                                    }
                              })
                        }

                        var url =  '/textblast/contactnumber'

                        if( button_enable){
                              url = projectsetup[0].es_cloudurl+'/textblast/contactnumber'
                        }

                        $.ajax({
                              type:'GET',
                              url: url,
                              data:{
                                    syid:$('#filter_sy').val(),
                                    levelid:selected_gradelevel,
                                    semid:$('#filter_sem').val()
                              },
                              success:function(data) {
                                    if(data.length == 0){
                                          Toast.fire({
                                                type: 'info',
                                                title: 'No student found.'
                                          })
                                          all_students = []
                                          datatable_1()
                                    }else{
                                          Toast.fire({
                                                type: 'info',
                                                title: data.length + ' student(s) found.'
                                          })
                                          all_students = data
                                          datatable_1()
                                    }
                                   
                              }
                        })
                  }

                  var valid_parent_contact = []
                  var invalid_parent_contact = []
                  var no_parent_contact = []

                  var valid_student_contact = []
                  var invalid_student_contact = []
                  var no_student_contact = []

                  function datatable_1(){

                        valid_parent_contact = all_students.filter(x=>x.contactNumber != "" && x.contactNumber.length == 11)
                        invalid_parent_contact = all_students.filter(x=>x.contactNumber.length != 11 && x.contactNumber != "")
                        no_parent_contact = all_students.filter(x=>x.contactNumber == "")

                        valid_student_contact = all_students.filter(x=>x.contactno != "" && x.contactno.length == 11)
                        invalid_student_contact = all_students.filter(x=>x.contactno.length != 11 && x.contactno != "")
                        no_student_contact = all_students.filter(x=>x.contactno == "")

                        $('#valid_parent_contact_count').text(valid_parent_contact.length)
                        $('#invalid_parent_contact_count').text(invalid_parent_contact.length)
                        $('#no_parent_contact_count').text(no_parent_contact.length)

                        $('#valid_student_contact_count').text(valid_student_contact.length)
                        $('#invalid_student_contact_count').text(invalid_student_contact.length)
                        $('#no_student_contact_count').text(no_student_contact.length)

                  }

                  $(document).on('click','#valid_student_contact_count',function(){
                        $('#modal_1').modal()
                        $('#modal_1_title').text('Valid Student Contact Number')
                        student_list_modal(valid_student_contact)
                  })

                  $(document).on('click','#invalid_student_contact_count',function(){
                        $('#modal_1').modal()
                        $('#modal_1_title').text('Invalid Student Contact Number')
                        student_list_modal(invalid_student_contact)
                  })

                  $(document).on('click','#no_student_contact_count',function(){
                        $('#modal_1').modal()
                        $('#modal_1_title').text('No Student Contact Number')
                        student_list_modal(no_student_contact)
                  })


                  $(document).on('click','#no_parent_contact_count',function(){
                        $('#modal_1_title').text('No Parent Contact Number')
                        $('#modal_1').modal()
                        student_list_modal(no_parent_contact)
                  })

                  $(document).on('click','#invalid_parent_contact_count',function(){
                        $('#modal_1_title').text('Invalid Parent Contact Number')
                        $('#modal_1').modal()
                        student_list_modal(invalid_parent_contact)
                  })

                  $(document).on('click','#valid_parent_contact_count',function(){
                        $('#modal_1_title').text('Valid Parent Contact Number')
                        $('#modal_1').modal()
                        student_list_modal(valid_parent_contact)
                  })

                

                  function student_list_modal(data){

                        console.log('STUDLIST',data);
                       
                        if ($('#modal_1_title').text() == 'Valid Student Contact Number') {
                              $("#student_list").DataTable({
                                    destroy: true,
                                    data:data,
                                    lengthChange : false,
                                    autoWidth: false,
                                    bInfo: false,
                                    columns: [
                                                { "data": "student" },
                                                { "data": "contactno" },
                                          ],
                                    
                                    
                              });
                              
                        }else{
                              $("#student_list").DataTable({
                                    destroy: true,
                                    data:data,
                                    lengthChange : false,
                                    autoWidth: false,
                                    bInfo: false,
                                    columns: [
                                                { "data": "student" },
                                                { "data": "contactNumber" },
                                          ],
                                    
                                    
                              });

                        }
                        

                  }

                  

            // })
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
                                          title: 'Date Version: 11/25/2022 04:00'
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

               
            })
      </script>

@endsection


