@php
      if(auth()->user()->type == 17){
           $extend = 'superadmin.layouts.app2';
      }elseif(auth()->user()->type == 6 || Session::get('currentPortal') == 6){
            $extend = 'adminPortal.layouts.app2';
      }else if(auth()->user()->type == 3 || Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }
@endphp
@extends($extend)


@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
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
            .no-border-col{
                  border-left: 0 !important;
                  border-right: 0 !important;
            }
            input[type=search]{
                  height: calc(1.7em + 2px) !important;
            }
      </style>
@endsection

@section('content')


<div class="modal fade" id="contactinfo_form_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Contact Information Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Student</label>
                                    <input id="input_info" class="form-control form-control-sm" readonly>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Student Contact</label>
                                    <input id="input_scontact" class="form-control form-control-sm" placeholder="09XX-XXXX-XXXX">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Father Contact #</label>
                                    <input id="input_fcontact" class="form-control form-control-sm" placeholder="09XX-XXXX-XXXX">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Mother Contact #</label>
                                    <input id="input_mcontact" class="form-control form-control-sm" placeholder="09XX-XXXX-XXXX">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Guardian Contact #</label>
                                    <input id="input_gcontact" class="form-control form-control-sm" placeholder="09XX-XXXX-XXXX">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 ">
                                      <label style="font-size: 13px !important" class="text-danger"><b>In case of emergency ( Recipient for News, Announcement and School Info)</b></label>
                              </div>
                              <div class="col-md-4">
                                      <div class="icheck-success d-inline">
                                          <input class="form-control" type="radio" id="father" name="incase" value="1" required>
                                          <label for="father">Father
                                          </label>
                                      </div>
                              </div>
                              <div class="col-md-4">
                                      <div class="icheck-success d-inline">
                                          <input class="form-control" type="radio" id="mother" name="incase" value="2" required>
                                          <label for="mother">Mother
                                          </label>
                                      </div>
                              </div>
                              <div class="col-md-4">
                                      <div class="icheck-success d-inline">
                                          <input class="form-control" type="radio" id="guardian" name="incase" value="3" required >
                                          <label for="guardian">Guardian
                                          </label>
                                      </div>
                              </div>
                          </div>
                          <div class="row mt-3">
                              <div class="col-md-12">
                                    <button class="btn btn-sm btn-success" id="contact_info_button">Update</button>
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
                        <h1>Contact Information</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Contact Information</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>

@php
      $sy = DB::table('sy')->orderBy('sydesc')->get(); 
      $semester = DB::table('semester')->get(); 
      $strand = DB::table('sh_strand')->orderBy('strandname')->where('deleted',0)->get(); 
      $gradelevel = DB::table('gradelevel')->where('deleted',0)->orderBy('sortid')->get(); 
@endphp

<section class="content pt-0">
      <div class="container-fluid">
            
            <div class="row">
                  <div class="col-md-12">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="info-box shadow-lg">
                                          <div class="info-box-content">
                                                <div class="row"><div class="col-md-2  form-group mb-0">
                                                      <label for="">Student Status</label>
                                                            <select class="form-control select2" id="filter_student">
                                                                 <option value="all">All</option>
                                                                 <option value="enrolled" selected>Enrolled</option>
                                                            </select>
                                                      </div>
                                                      <div class="col-md-2  form-group mb-0 enrolled_holder">
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
                                                      <div class="col-md-2  form-group mb-0 enrolled_holder">
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
                                                            <label for="">Grade Level</label>
                                                            <select class="form-control select2" id="filter_level">
                                                                  <option value="">All</option>
                                                                  @foreach ($gradelevel as $item)
                                                                        <option value="{{$item->id}}">{{$item->levelname}}</option>
                                                                  @endforeach
                                                            </select>
                                                      </div>
                                                      <div class="col-md-3  form-group mb-0">
                                                            <label for="">Contact Information Status</label>
                                                            <select class="form-control select2" id="filter_status">
                                                                  <option value="">All</option>
                                                                  <option value="nsc">No Student Contact #</option>
                                                                  <option value="npc">No Parent Contact #</option>
                                                            </select>
                                                      </div>
                                                </div>
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
                                          <div class="col-md-12"  style="font-size:14px">
                                                <table class="table table-striped table-sm table-bordered" id="students_table" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="25%">Student</th>
                                                                  <th width="20%">Mother Contact #</th>
                                                                  <th width="20%">Father Contact #</th>
                                                                  <th width="20%">Guardian Contact #</th>
                                                                  <th width="15%">Incase of Emergency</th>
                                                            </tr>
                                                      </thead>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row" hidden>
                  <div class="col-md-6">
                        <div class="card shadow" >
                              <div class="card-body" style="min-height: 587px">
                                    <div class="row">
                                          <div class="col-md-12" style="font-size:.7rem">
                                                <table class="table table-striped table-sm table-bordered" id="student_contact_info" width="100%"  >
                                                      <thead>
                                                            <tr>
                                                                  <th width="40%">Student</th>
                                                                  <th width="60%">Parent Contact #</th>
                                                            </tr>
                                                      </thead>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
                  <div class="col-md-6">
                        <div class="card shadow" style="">
                              <div class="card-body"  style="min-height: 587px">
                                  
                                    <div class="row">
                                          <div class="col-md-12" style="font-size:.7rem">
                                                <table class="table table-striped table-sm table-bordered " id="invalid_contact_info" width="100%" >
                                                      <thead>
                                                            <tr>
                                                                  <th width="40%">Student</th>
                                                                  <th width="60%">Status</th>
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
      <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>

      <script>

            $(document).ready(function(){

                  $('.select2').select2()
                 
                  var all_students = [];
                  var selected_student = [];

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                  })

                  get_students()

                  $(document).on('change','#filter_sy',function(){
                        all_students = [];
                        get_students()
                  })

                  $(document).on('change','#filter_student',function(){
                        if($(this).val() == "all"){
                              $('.enrolled_holder').attr('hidden','hidden')
                        }else{
                              $('.enrolled_holder').removeAttr('hidden')
                        }

                        all_students = [];
                        get_students()
                  })

                  $(document).on('change','#filter_sem',function(){
                        all_students = [];
                        get_students()
                  })


                  $(document).on('change','#filter_level',function(){
                        all_students = [];
                        get_students()
                  })

                  $(document).on('change','#filter_status',function(){
                        if($(this).val() == "nsc"){
                              var temp_students = all_students.filter(x=>x.contactno == null || x.contactno == "" );
                        }else if($(this).val() == "npc"){
                              var temp_students = all_students.filter(x=> ( x.ismothernum == 0 &&  x.isfathernum == 0 &&  x.isguardannum == 0 ));

                        }else{
                              var temp_students = null
                        }
                        loaddattable(temp_students)
                  })

                  $(document).on('click','#subjectplot_button',function(){
                        all_students = [];
                        get_students()
                  })

                  $(document).on('click','.update_contact_info',function(){
                        var studid = $(this).attr('data-id')
                        var temp_info = all_students.filter(x=>x.studid == studid)
                        selected_student = studid
                        
                        if(temp_info[0].ismothernum == 1){
                            $("#mother").prop("checked", true)
                        }
                        else if(temp_info[0].isfathernum == 1){
                            $("#father").prop("checked", true)
                        }
                        else if(temp_info[0].isguardannum == 1){
                            $("#guardian").prop("checked", true)
                        }else{
                              $('input[name="incase"]').prop("checked", false)
                        }

                        $("#input_info").val(temp_info[0].student)
                        $("#input_scontact").val(temp_info[0].contactno)
                        $("#input_mcontact").val(temp_info[0].mcontactno)
                        $("#input_fcontact").val(temp_info[0].fcontactno)
                        $("#input_gcontact").val(temp_info[0].gcontactno)

                        $('#contactinfo_form_modal').modal()
                  })
                  
                  $("#input_fcontact").inputmask({mask: "9999-999-9999"});
                  $("#input_mcontact").inputmask({mask: "9999-999-9999"});
                  $("#input_gcontact").inputmask({mask: "9999-999-9999"});
                  $("#input_scontact").inputmask({mask: "9999-999-9999"});


                  $(document).on('click','#contact_info_button',function(){
                        update_contact()
                  })

                  function update_contact(){

                        var ismothernum = 0
                        var isfathernum = 0
                        var isguardiannum = 0

                        if($('#guardian').prop('checked') == true){
                              isguardiannum = 1
                        }
                        if($('#mother').prop('checked') == true){
                              ismothernum = 1
                        }
                        if($('#father').prop('checked') == true){
                              isfathernum = 1
                        }

                       

                        if($('#input_scontact').val() != "" && ($('#input_scontact').val()).toString().replace(/-|_/g,'').length != 11 ){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Student contact # is invalid!"
                              })
                              return false
                        }
                        else if($('#input_fcontact').val() != "" && ($('#input_fcontact').val()).toString().replace(/-|_/g,'').length != 11 ){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Father contact # is invalid!"
                              })
                              return false
                        }
                        else if($('#input_mcontact').val() != "" && ($('#input_mcontact').val()).toString().replace(/-|_/g,'').length != 11 ){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Mother contact # is invalid!"
                              })
                              return false
                        }
                        else if($('#input_gcontact').val() != "" && ($('#input_gcontact').val()).toString().replace(/-|_/g,'').length != 11 ){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Guardian contact # is invalid!"
                              })
                              return false
                        }
                        else if(isguardiannum == 0 && ismothernum == 0 && isfathernum == 0){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Select in case of emergency!"
                              })
                              return false
                        }
                        if(isfathernum == 1 && $('#input_fcontact').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Father contact # is empty!"
                              })
                              return false
                        }
                        else if(ismothernum == 1 && $('#input_mcontact').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Mother contact # is empty!"
                              })
                              return false
                        }
                        else if(isguardiannum == 1 && $('#input_gcontact').val() == ""){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Guardian contact # is empty!"
                              })
                              return false
                        }
                        else if(isguardiannum == 1 && ($('#input_gcontact').val()).toString().replace(/-|_/g,'').length != 11 ){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Mother contact # is invalid!"
                              })
                              return false
                        } else if(ismothernum == 1 && ($('#input_mcontact').val()).toString().replace(/-|_/g,'').length != 11 ){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Mother contact # is invalid!"
                              })
                              return false
                        }else if(isfathernum == 1 && ($('#input_fcontact').val()).toString().replace(/-|_/g,'').length != 11 ){
                              Toast.fire({
                                    type: 'warning',
                                    title: "Mother contact # is invalid!"
                              })
                              return false
                        }

                        


                      

                        $.ajax({
                              type:'GET',
                              url: '/student/contactnumber/update',
                              data:{
                                    studid:selected_student,
                                    ismothernum: ismothernum,
                                    isfathernum: isfathernum,
                                    isguardiannum: isguardiannum,
                                    contactno:  $('#input_scontact').val(),
                                    fcontactno:  $('#input_fcontact').val(),
                                    mcontactno:  $('#input_mcontact').val(),
                                    gcontactno:  $('#input_gcontact').val(),

                              },
                              success:function(data) {
                                    if(data.length == 0){
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }else{

                                          
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })

                                          var index = all_students.findIndex(x=>x.studid == selected_student)
                                          all_students[index].contactno = ($('#input_scontact').val()).toString().replace(/-/g,'')
                                          all_students[index].fcontactno = ($('#input_fcontact').val()).toString().replace(/-/g,'')
                                          all_students[index].mcontactno = ($('#input_mcontact').val()).toString().replace(/-/g,'')
                                          all_students[index].gcontactno = ($('#input_gcontact').val()).toString().replace(/-/g,'')

                                          all_students[index].ismothernum = ismothernum
                                          all_students[index].isfathernum = isfathernum
                                          all_students[index].isguardannum = isguardiannum


                                          if($('#filter_status').val() == "nsc"){
                                                var temp_students = all_students.filter(x=>x.contactno == null);
                                          }else if($('#filter_status').val() == "npc"){
                                                var temp_students = all_students.filter(x=> ( x.ismothernum == 0 &&  x.isfathernum == 0 &&  x.isguardannum == 0 ) || ( x.ismothernum == 1 && ( x.mcontactno == null || x.mcontactno == "") ) || ( x.isfathernum == 1 && ( x.fcontactno == null || x.fcontactno == "" ) ) || ( x.isguardannum == 1 && ( x.gcontactno == null || x.gcontactno == "" ) ));
                                          }else{
                                                var temp_students = null
                                          }
                                          loaddattable(temp_students)
                                    }
                              },error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })

                  }

                  
                  function get_students(){
                        var levelid = $('#filter_level').val()
                        var semid = $('#filter_sem').val()
                        $.ajax({
                              type:'GET',
                              url: '/student/contactnumber/list',
                              data:{
                                    studentstatus:$('#filter_student').val(),
                                    syid:$('#filter_sy').val(),
                                    levelid:levelid,
                                    semid:semid
                              },
                              success:function(data) {
                                    if(data.length == 0){
                                          Toast.fire({
                                                type: 'info',
                                                title: 'No Enrolled Student Yet'
                                          })
                                          all_students = []
                                          loaddattable()
                                    }else{
                                          Toast.fire({
                                                type: 'info',
                                                title: data.length + ' Students Found'
                                          })
                                          all_students = data
                                          loaddattable()
                                    }
                                   
                              }
                        })
                  }
                  

                  function loaddattable(temp_students = null){

                        // var invalid_contacts = []

                        // var display_button = false;

                        // $.each(all_students,function(a,b){

                        //       var valid = true;
                             
                              
                        //       if(b.contactno != null){
                        //             if(b.contactno.includes('-')){
                        //                   valid = false 
                        //                   display_button = true;
                        //                   b.status = 'Student\'s contact contains (-)'
                        //             }else if(b.contactno.length > 12){
                        //                   valid = false 
                        //                   display_button = true;
                        //                   b.status = 'Student\'s contact # exceeds 11 digit'
                        //             }
                                    
                        //       }
                              
                        //       if(b.mcontactno != null){
                        //             if(b.mcontactno.includes('-')){
                        //                   valid = false;
                        //                   display_button = true;
                        //                   b.status = 'Mother\'s contact contains (-)'
                        //             }else if(b.mcontactno.length > 12){
                        //                   valid = false 
                        //                   display_button = true;
                        //                   b.status = 'Mother\'s contact # exceeds 11 digit'
                        //             }
                        //       }
                              
                        //       if(b.fcontactno != null){
                        //             if(b.fcontactno.includes('-')){
                        //                   valid = false 
                        //                   display_button = true;
                        //                   b.status = 'Father\'s contact contains (-)'
                        //             }else if(b.fcontactno.length > 12){
                        //                   valid = false 
                        //                   display_button = true;
                        //                   b.status = 'Father\'s contact # exceeds 11 digit'
                        //             }
                        //       }
                              
                        //       if(b.gcontactno != null){
                        //             if(b.gcontactno.includes('-')){
                        //                   valid = false 
                        //                   display_button = true;
                        //                   b.status = 'Guardian\'s contact contains (-)'
                        //             }else if(b.gcontactno.length > 12){
                        //                   display_button = true;
                        //                   valid = false 
                        //                   b.status = 'Guardian\'s contact # exceeds 11 digit'
                        //             }
                        //       }
                              
                        //       if(b.ismothernum == 0 && b.isfathernum == 0 && b.isguardannum == 0){
                        //             valid = false 
                        //             b.status = 'No assigned parent contact'
                        //       }else if(b.mcontactno == null && b.ismothernum == 1){
                        //             valid = false 
                        //             b.status = 'Mother\'s contact # is empty'
                        //       }else if(b.fcontactno == null && b.isfathernum == 1){
                        //             valid = false 
                        //             b.status = 'Father\'s contact # is empty'
                        //       }else if(b.gcontactno == null && b.isguardannum == 1){
                        //             valid = false 
                        //             b.status = 'Guardian\'s contact # is empty'
                        //       }

                        //       if(!valid){
                        //             invalid_contacts.push(b)
                        //       }

                        // })
                       
                        // $("#invalid_contact_info").DataTable({
                        //       destroy: true,
                        //       data:invalid_contacts,
                        //       scrollX: true,
                        //       columns: [
                        //             { "data": "student" },
                        //             { "data": "status" },
                        //       ],
                        //       columnDefs: [
                        //             {
                        //                   'targets': 0,
                        //                   'orderable': true, 
                        //                   'createdCell':  function (td, cellData, rowData, row, col) {
                        //                         var student = '&nbsp;'
                        //                         var sid = '&nbsp;'
                        //                         if(rowData.student != null){
                        //                               student = rowData.student
                        //                         }
                        //                         sid = rowData.sid
                        //                         var text = '<a class="mb-0">'+student+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+sid+'</p>';
                        //                         $(td)[0].innerHTML =  text
                        //                   }
                        //             },
                        //             {
                        //                   'targets': 1,
                        //                   'orderable': true, 
                        //                   'createdCell':  function (td, cellData, rowData, row, col) {
                        //                         var text = '<a class="mb-0">'+rowData.status+'</a><p class="text-muted mb-0" style="font-size:.7rem"><a href="javascript:void(0)" data-id="'+rowData.studid+'" class="update_contact_info">Update Contact</a></p>';
                        //                         $(td)[0].innerHTML =  text
                        //                   }
                        //             },
                        //       ]
                        // })

                        // var label_text = $($("#invalid_contact_info_wrapper")[0].children[0])[0].children[0]
                        // $(label_text)[0].innerHTML = '<h6 class="text-danger"><i class="fas fa-exclamation-triangle"></i> Invalid Contact</h6>'



                        

                        // $("#student_contact_info").DataTable({
                        //       destroy: true,
                        //       data:all_students,
                        //       scrollX: true,
                        //       columns: [
                        //             { "data": "student" },
                        //             { "data": "mothername" },
                        //       ],
                        //       columnDefs: [
                        //             {
                        //                   'targets': 0,
                        //                   'orderable': true, 
                        //                   'createdCell':  function (td, cellData, rowData, row, col) {
                        //                         var student = '&nbsp;'
                        //                         var contact = '&nbsp;'
                        //                         if(rowData.student != null){
                        //                               student = rowData.student
                        //                         }
                        //                         if(rowData.contactno != null){
                        //                               contact = rowData.contactno
                        //                         }else{
                        //                               contact = '<span class="badge badge-danger">No student Contact #</span>'
                        //                         }
                        //                         var text = '<a class="mb-0">'+student+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+contact+'</p>';
                        //                         $(td)[0].innerHTML =  text
                        //                   }
                        //             },
                        //             {
                        //                   'targets': 1,
                        //                   'orderable': true, 
                        //                   'createdCell':  function (td, cellData, rowData, row, col) {

                        //                         var pname = '&nbsp;'
                        //                         var pcontact = '&nbsp;'
                                                
                        //                         if(rowData.ismothernum == 1){
                        //                               pname = rowData.mothername != null ? rowData.mothername : '&nbsp;'
                        //                               if(rowData.mcontactno == null){
                        //                                     pcontact = '<span class="badge badge-danger">EMPTY</span> <span class="badge badge-success">MOTHER</span'
                        //                               }else{
                        //                                     pcontact = rowData.mcontactno + ' <span class="badge badge-success">MOTHER</span>' 
                        //                               }
                                                      
                        //                         }else if(rowData.isfathernum == 1){
                        //                               pname = rowData.fathername != null ? rowData.fathername : '&nbsp;'
                        //                               if(rowData.fcontactno == null){
                        //                                     pcontact = '<span class="badge badge-danger">EMPTY</span> <span class="badge badge-success">FATHER</span>'
                        //                               }else{
                        //                                     pcontact = rowData.fcontactno + ' <span class="badge badge-success">FATHER</span>'
                        //                               }
                                                      
                        //                         }else if(rowData.isguardannum == 1){
                        //                               pname = rowData.guardianname != null ? rowData.guardianname : '&nbsp;'

                        //                               if(rowData.gcontactno == null){
                        //                                     pcontact = '<span class="badge badge-danger">EMPTY</span> <span class="badge badge-success">GUARDIAN</span>'
                        //                               }else{
                        //                                     pcontact = rowData.gcontactno + ' <span class="badge badge-success">GUARDIAN</span>'
                        //                               }
                                                     
                        //                         }else{
                        //                               pcontact = '<span class="badge badge-danger">NOT ASSIGNED</span>'
                        //                         }

                                             
                        //                         var text = '<a class="mb-0">'+pname+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+pcontact+'</p>';
                        //                         $(td)[0].innerHTML =  text
                        //                   }
                        //             },
                        //       ]
                        // })

                        // var label_text = $($("#student_contact_info_wrapper")[0].children[0])[0].children[0]
                        // $(label_text)[0].innerHTML = ' <h6>Student Contact Information</h6>'
                       
                        if(temp_students == null){
                              var students = all_students
                        }else{
                              var students = temp_students
                        }


                        $("#students_table").DataTable({
                              destroy: true,
                              data:students,
                              autoWidth: false,
                              language: {
                                    emptyTable: "Currently, there are no Student records to show."
                              },
                              columns: [
                                    { "data": "student" },
                                    { "data": "mothername" },
                                    { "data": "fathername" },
                                    { "data": "guardianname" },
                                    { "data": null },
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var student = '&nbsp;'
                                                var contact = '&nbsp;'
                                                if(rowData.student != null){
                                                      student = rowData.student
                                                }
                                                if(rowData.contactno != null && rowData.contactno != 'null' ){
                                                      contact = rowData.contactno
                                                }else{
                                                      contact = '<span class="badge badge-danger">No Student Contact #</span>'
                                                }
                                                var text = '<a class="mb-0">'+student+'</a>'+'<p class="text-muted mb-0" style="font-size:.7rem">'+contact+'</p>';
                                                $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var mname = '&nbsp;'
                                                var mcontact = '&nbsp;'
                                                if(rowData.mothername != null){
                                                      mname = rowData.mothername
                                                }
                                                if(rowData.mcontactno){
                                                      mcontact = rowData.mcontactno
                                                }
                                                var text = '<a class="mb-0">'+mname+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+mcontact+'</p>';
                                                $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var fname = '&nbsp;'
                                                var fcontact = '&nbsp;'
                                                if(rowData.fathername != null){
                                                      fname = rowData.fathername
                                                }
                                                if(rowData.fcontactno){
                                                      fcontact = rowData.fcontactno
                                                }
                                                var text = '<a class="mb-0">'+fname+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+fcontact+'</p>';
                                                $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var gname = '&nbsp;'
                                                var gcontact = '&nbsp;'
                                                if(rowData.guardianname != null){
                                                      gname = rowData.guardianname
                                                }
                                                if(rowData.gcontactno){
                                                      gcontact = rowData.gcontactno
                                                }
                                          
                                                var text = '<a class="mb-0">'+gname+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+gcontact+'</p>';
                                                $(td)[0].innerHTML =  text
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var parent = '<span class="badge badge-danger">Not Set</span>'
                                                if(rowData.ismothernum == 1){
                                                      parent = '<span class="badge badge-primary">Mother</span>'
                                                }else if(rowData.isfathernum == 1){
                                                      parent = '<span class="badge badge-warning">Father</span>'
                                                }else if(rowData.isguardannum == 1){
                                                      parent = '<span class="badge badge-success">Guardian</span>'
                                                }
                                                var text = '<a class="mb-0">'+parent+'</a>'+'<p class="text-muted mb-0" style="font-size:.7rem">'+'<a href="javascript:void(0)" data-id="'+rowData.studid+'" class="update_contact_info ">Update Contact</a>'+'</p>';
                                                $(td)[0].innerHTML =  text
                                          }
                                    },
                              ]
                              
                        });

                        var con_options = 
                                    '<div class="btn-group">'+
                                         '<button type="button" class="btn btn-default btn-sm"><i class="fa fa-print"></i> Contact Information</button>'+
                                          '<button type="button" class="btn btn-default dropdown-toggle dropdown-icon btn-sm" data-toggle="dropdown">'+
                                          '<span class="sr-only">Toggle Dropdown</span>'+
                                          '</button>'+
                                          '<div class="dropdown-menu" role="menu">'+
                                                '<a class="dropdown-item print_contact" data-id="2" href="#">By Grade Level</a>'+
                                                '<a class="dropdown-item print_contact" data-id="3" href="#">By Section</a>'+
                                          '</div>'+
                                    '</div>'

                        var label_text = $($("#students_table_wrapper")[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = con_options

                       
                  }
                  
            })
      </script>
      
      <script>
            $(document).ready(function(){
            
                  $(document).on('click','.print_contact',function(){
                        window.open('/student/enrollment/report/contact/?datatype='+$(this).attr('data-id')+'&syid='+$('#filter_sy').val(), '_blank');
                  })
            })
      </script>


@endsection
