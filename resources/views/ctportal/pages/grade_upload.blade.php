
@extends('ctportal.layouts.app2')

@section('pagespecificscripts')
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <style>
          
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }

      </style>
@endsection

@section('content')

@php
      $sy = DB::table('sy')->orderBy('sydesc','desc')->get(); 
      $semester = DB::table('semester')->get(); 
      $schoolinfo = DB::table('schoolinfo')->first()->abbreviation;
    

      $gradesetup = DB::table('semester_setup')
                        ->where('deleted',0)
                        ->first();

@endphp

<div class="modal fade" id="upload_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
                  <div class="modal-header bg-primary p-1">
                  </div>
                  <div class="modal-body">
                        <form 
                              action="/college/grade/ecr/upload" 
                              id="upload_ecr" 
                              method="POST" 
                              enctype="multipart/form-data"
                              >
                              @csrf
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Term</label>
                                          <select name="input_term" id="input_term" class="form-control">
                                                {{-- <option value="PRE-MIDTERM">PRE-MIDTERM</option>
                                                <option value="PRELIM">PRELIM</option>
                                                <option value="MIDTERM">MIDTERM</option>
                                                <option value="SEMI-FINAL">SEMI-FINAL</option>
                                                <option value="PRE-FINAL">PRE-FINAL</option>
                                                <option value="FINAL">FINAL</option> --}}

                                                <option value="PRE-MIDTERM">PRE-MIDTERM</option>
                                                <option value="PRELIM">PRELIM</option>
                                                <option value="MIDTERM">MIDTERM</option>
                                                <option value="CR MIDTERM SUMMARY">CR MIDTERM SUMMARY</option>
                                                <option value="SEMI-FINAL">SEMI-FINAL</option>
                                                <option value="PRE-FINAL">PRE-FINAL</option>
                                                <option value="FINAL">FINAL</option>
                                                <option value="CR FINAL SUMMARY">CR FINAL SUMMARY</option>
                                                <option value="CR SEMESTER SUMMARY">CR SEMESTER SUMMARY</option>
                                          </select>
                                    </div>
                              </div>
                              <div class="row" >
                                    <div class="col-md-12">
                                         
                                                <div class="input-group input-group-sm">
                                                      <input type="file" class="form-control" name="input_ecr" id="input_ecr">
                                                      <span class="input-group-append">
                                                      <button class="btn btn-info btn-flat" id="upload_ecr_button" >Upload ECR</button>
                                                      </span>
                                                </div>
                                    </div>
                              </div>
                        </form>
                  </div>
            </div>
      </div>
</div>




<div class="modal fade" id="view_ecr_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0 bg-primary">
                  <h4 class="modal-title" style="font-size: 1.1rem !important"></h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
            </div>
              <div class="modal-body">
                 <div class="row">
                        <div class="col-md-2">
                              {{-- <div class="row">
                                    <div class="col-md-12">
                                          <button class="btn btn-primary btn-sm btn-block download_ecr"><i class="fas fa-file-excel"></i> Download ECR</button>
                                    </div>
                              </div> --}}
                              <hr>
                              <div class="row mt-3" style=" font-size:14px !important" hidden>
                                    <div class="col-md-5">
                                          <strong><i class="fas fa-book mr-1"></i> Grade Level</strong>
                                          <p class="text-muted" id="label_gradelevel">
                                                --
                                           </p>
                                    </div>
                                    <div class="col-md-7">
                                          <strong><i class="fas fa-book mr-1"></i> Section</strong>
                                          <p class="text-muted" id="label_section">
                                                --
                                           </p>
                                    </div>
                                   
                              </div>
                              <div class="row" style=" font-size:14px !important">
                                    <div class="col-md-12">
                                          <strong><i class="fas fa-book mr-1"></i> Subject</strong>
                                          <p class="text-muted mb-0" id="label_subject">
                                                --
                                          </p>
                                          <p class="text-danger mb-0" >
                                                <i id="label_subjectcode"> -- </i>
                                          </p>
                                    </div>
                              </div>
                              <hr>
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Quarter</label>
                                          <select name="" id="filter_term" class="form-control form-control-sm">
                                                <option value="PRE-MIDTERM">PRE-MIDTERM</option>
                                                <option value="PRELIM">PRELIM</option>
                                                <option value="MIDTERM">MIDTERM</option>
                                                <option value="CR MIDTERM SUMMARY">CR MIDTERM SUMMARY</option>
                                                <option value="SEMI-FINAL">SEMI-FINAL</option>
                                                <option value="PRE-FINAL">PRE-FINAL</option>
                                                <option value="FINAL">FINAL</option>
                                                <option value="CR FINAL SUMMARY">CR FINAL SUMMARY</option>
                                                <option value="CR SEMESTER SUMMARY">CR SEMESTER SUMMARY</option>
                                          </select>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <button class="btn btn-primary btn-sm btn-block" id="ecr_filter"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <button disabled="disabled" class="btn btn-success btn-sm btn-block" id="ecr_submit" ><i class="far fa-share-square"></i> Submit</button>
                                    </div>
                              </div>
                              <hr>
                              <div class="row mt-3" style=" font-size:14px !important">
                                    <div class="col-md-12  form-group">
                                          <strong><i class="fas fa-book mr-1"></i> Last date Uploaded</strong>
                                          <p class="text-muted" id="label_dateuploaded">
                                                --
                                           </p>
                                    </div>
                                    <div class="col-md-12 form-group">
                                          <strong><i class="fas fa-book mr-1"></i> Grade Status</strong>
                                          <p class="text-muted" id="label_status">
                                                --
                                          </p>
                                    </div>
                                    <div class="col-md-12 form-group">
                                          <strong><i class="fas fa-book mr-1"></i> Grade Submitted</strong>
                                          <p class="text-muted" id="label_datesubmitted">
                                                --
                                          </p>
                                    </div>
                              </div>
                        </div>
                        <div class="col-md-10">
                              <div class="row" >
                                    <div class="col-md-6">
                                        
                                    </div>
                                    <div class="col-md-6 text-right">
                                          {{-- <form 
                                                action="/ecr/upload" 
                                                id="upload_ecr" 
                                                method="POST" 
                                                enctype="multipart/form-data"
                                                >
                                                @csrf
                                                <div class="row">
                                                      <div class="input-group input-group-sm">
                                                            <input type="file" class="form-control" name="input_ecr" id="input_ecr">
                                                            <span class="input-group-append">
                                                            <button class="btn btn-info btn-flat" id="upload_ecr_button" >Update ECR</button>
                                                            </span>
                                                      </div>
                                                </div>
                                          </form> --}}
                                          <button class="btn btn-secondary btn-sm" id="logs_button">Logs</button>
                                    </div>
                                    
                              </div>
                              <div class="row" >
                                    <div class="col-md-12" id="ecr_view_holder" style="font-size:14px !important">
                                         <table class="table table-sm table-bordered">
                                               <tr>
                                                     <th class="text-center">Select Term</th>
                                               </tr>
                                         </table>
                                    </div>
                              </div>
                        </div>
                 </div>
              </div>
              
          </div>
      </div>
  </div>


  <div class="modal fade" id="grade_logs_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
                  <div class="modal-header bg-primary p-1">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row" >
                              <div class="col-md-12">
                                    <table class="table table-sm table-bordered">
                                          <thead>
                                                <tr>
                                                      <th>Status</th>
                                                      <th>Date</th>
                                                </tr>
                                          </thead>
                                          <tbody id="log_holder">

                                          </tbody>
                                     </table>
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
                  <h1>Student Grades</h1>
              </div>
              <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="/home">Home</a></li>
                  <li class="breadcrumb-item active">Student Grades</li>
              </ol>
              </div>
          </div>
      </div>
</section>
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-6">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="info-box shadow-lg">
                                          <div class="info-box-content">
                                                <div class="row">
                                                      <div class="col-md-4">
                                                            <label for="">School Year</label>
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
                                                      <div class="col-md-4" >
                                                            <label for="">Semester</label>
                                                            <select class="form-control form-control-sm  select2" id="filter_semester">
                                                                  <option value="">Select semester</option>
                                                                  @foreach ($semester as $item)
                                                                        <option {{$item->isactive == 1 ? 'selected' : ''}} value="{{$item->id}}">{{$item->semester}}</option>
                                                                  @endforeach
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
                        <div class="card shadow">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12" style="font-size:.9rem">
                                                <table class="table table-sm table-striped " id="datatable_1" >
                                                      <thead>
                                                            <tr>
                                                                  <th width="12%">Section</th>
                                                                  <th width="43%">Subject</th>
                                                                  <th width="40%" class="text-center"></th>
                                                            </tr>
                                                      </thead>
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
                                          <div class="col-md-12" style="font-size: .9rem !important">
                                                <table class="table table-sm table-striped table-bordered table-sm display nowrap"  id="sched_grade">
                                                      <thead>
                                                            <tr>
                                                                  <th style="min-width:300px !important; white-space: normal;">Schedule</th>
                                                                  <th >Section</th>
                                                                  <th class="text-center">PRE-MIDTERM</th>
                                                                  <th class="text-center">PRELIM</th>
                                                                  <th class="text-center">MIDTERM</th>
                                                                  <th class="text-center">CR MID SUM</th>
                                                                  <th class="text-center">SEMI-FINAL</th>
                                                                  <th class="text-center">PRE-FINAL</th>
                                                                  <th class="text-center">FINAL</th>
                                                                  <th class="text-center">CR FINAL SUM</th>
                                                                  <th class="text-center">CR SEM SUM</th>
                                                            </tr>
                                                      </thead>
                                                 
                                                </table>
                                          </div>
                                    </div>
                                    <div class="row mt-4">
                                          <div class="col-md-12" style="font-size:.9rem">
                                                <table class="table table-sm table-striped table-bordered"  >
                                                      <thead>
                                                            <tr>
                                                                  <th width="25%">Term</th>
                                                                  <th width="15%" class="text-center">Schedule</th>
                                                                  <th width="15%" class="text-center">Not Submitted</th>
                                                                  <th width="15%" class="text-center">Submitted</th>
                                                                  <th width="10%"class="text-center">Approved</th>
                                                                  <th width="10%" class="text-center">Posted</th>
                                                                  <th width="10%" class="text-center">Pending</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="term_sum_stat">

                                                      </tbody>
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

@section('footerscript')

      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/moment/moment.min.js') }}"></script>
     

      <script>
            $('#filter_sy').select2()
            $('#filter_semester').select2()
            var schoolinfo = @json($schoolinfo);
      </script>

      <script>

            const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 2000,
            })
            var selected_schedid = null
            var selected_grade = null
            var all_subject = []
            var school = @json($schoolinfo);

            $.ajaxSetup({
                  headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
            });

            $(document).on('click','.upload_grade',function(){
                  $('#upload_modal').modal()
            })

            $(document).on('click','.view_grade',function(){
                  $('#view_ecr_modal').modal()
                  selected_schedid = $(this).attr('data-id')
                  var subjinfo = all_subject.filter(x=>x.schedid == selected_schedid)
                  $('#label_subject').text(subjinfo[0].subjDesc)
                  $('#label_subjectcode').text(subjinfo[0].subjCode)
                  view_ecr()
            })

            $(document).on('click','#logs_button',function(){
                  $('#grade_logs_modal').modal();
                  get_logs()
            })
            
           
            $(document).on('click','#ecr_filter',function(){
                  selected_grade = null
                  view_ecr()
            })

            $(document).on('click','#ecr_submit',function(){
                  submit_ecr()
            })

            $( '#upload_ecr' ).submit( function( e ) {
                  if($('#input_ecr').val() == ""){
                        Toast.fire({
                              type: 'warning',
                              title: 'No File Uploaded!'
                        });
                  }

                  var inputs = new FormData(this)
                  $('#upload_ecr_button').attr('disabled','disabled')
                  $('#upload_ecr_button').text('Uploading...')
                  $('#input_term').attr('readonly','readonly')
                  $('#input_ecr').attr('readonly','readonly')



                  $.ajax({
                        url: '/college/grade/ecr/upload',
                        type: 'POST',
                        data: inputs,
                        processData: false,
                        contentType: false,
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: 'Uploaded Successfully'
                                    });
                              }else{
                                    Toast.fire({
                                          type: 'warning',
                                          title: data[0].message
                                    });
                              }
                              $('#upload_ecr_button').removeAttr('disabled')
                              $('#upload_ecr_button').text('Upload ECR')
                              $('#input_term').removeAttr('readonly')
                              $('#input_ecr').removeAttr('readonly')
                        },error:function(){
                              Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong'
                              });
                              $('#upload_ecr_button').removeAttr('disabled')
                              $('#upload_ecr_button').text('Upload ECR')
                              $('#input_term').removeAttr('readonly')
                              $('#input_ecr').removeAttr('readonly')
                        }
                  })
                  e.preventDefault();
            })

            $(document).on('click','.download_ecr',function(){
                  var syid = $('#filter_sy').val()
                  var semid = $('#filter_semester').val()
                  window.open('/college/grade/ecr/download?syid='+syid+'&semid='+semid+'&schedid='+selected_schedid, '_blank');
            })
          
            get_subjects()

            function get_logs(){
                  $('#log_holder').empty()
                  $.ajax({
                        type:'GET',
                        url: '/college/grade/ecr/logs',
                        data:{
                              syid:$('#filter_sy').val(),
                              semid:$('#filter_semester').val(),
                              schedid:selected_schedid,
                              term:$('#filter_term').val()
                        },
                        success:function(data) {
                             $.each(data,function(a,b){
                                    $('#log_holder').append('<tr><td>'+b.status+'</td><td>'+b.date+'</td></tr>')
                             })
                        }
                  })
            }

            function submit_ecr(){
                  var tempid = $('#ecr_submit').attr('data-id')
                  $.ajax({
                        type:'GET',
                        url: '/college/grade/ecr/submit',
                        data:{
                              syid:$('#filter_sy').val(),
                              semid:$('#filter_semester').val(),
                              schedid:selected_schedid,
                              term:$('#filter_term').val(),
                              id:tempid
                        },
                        success:function(data) {
                              if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: 'Submitted Successfully!'
                                    })
                                    view_ecr()
                              }else{
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                              }
                        }
                  }) 
            }

            function view_ecr(){
                  $.ajax({
                        type:'GET',
                        url: '/college/grade/ecr/view',
                        data:{
                              syid:$('#filter_sy').val(),
                              semid:$('#filter_semester').val(),
                              schedid:selected_schedid,
                              term:$('#filter_term').val()
                        },
                        success:function(data) {
                              $('#ecr_view_holder').empty();
                              $('#ecr_view_holder').append(data)
                        }
                  })
            }

            function get_subjects() {
                  $.ajax({
                        type:'GET',
                        url: '/college/teacher/student/grades/subject',
                        data:{
                              syid:$('#filter_sy').val(),
                              semid:$('#filter_semester').val(),
                              teacherid:73
                        },
                        success:function(data) {
                              if(data.length == 0){
                                    Toast.fire({
                                          type: 'warning',
                                          title: 'No records Found!'
                                    })
                              }else{
                                    all_subject = data
                                    datatable_2()
                              }
                        }
                  })
            }

            function get_enrolled(){
                  $.each(all_subject,function(a,b){
                        $.ajax({
                              type:'GET',
                              url: '/college/teacher/student/grades/students',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    schedid:b.schedid,
                                    subjid:b.subjectID
                              },
                              success:function(data) {
                                    datatable_1()
                              }
                        })
                  })
            }

            function datatable_2(){
                  
                  if(school == 'sait'.toUpperCase()){
                        var temp_subjects = all_subject
                  }else{
                        var temp_subjects = all_subject
                  }
                  
                  $("#datatable_1").DataTable({
                        destroy: true,
                        data:temp_subjects,
                        lengthChange: false,
                        scrollX: true,
                        autoWidth: false,
                        columns: [
                              { "data": null},
                              { "data": "subjDesc" },
                              { "data": null }
                        ],
                        columnDefs: [
                              {
                                    'targets': 0,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var text = ''
                                          if(schoolinfo == 'DCC'){
                                                text = rowData.code
                                          }else{
                                                $.each(rowData.sections,function(a,b){
                                                      text += '<span class=" badge badge-primary  mt-1" style="font-size:.65rem !important; white-space:normal" >'+b.schedgroupdesc+'</span> <br>'
                                                })
                                          }
                                        
                                                
                                          $(td)[0].innerHTML =  text
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 1,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          
                                          var schedotherclass = ''
                                          if(school == 'spct'.toUpperCase() || school == 'gbbc'.toUpperCase()){
                                                var text = rowData.subjDesc
                                          }else{
                                                var text = '<a class="mb-0">'+rowData.subjDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.subjCode;
                                          }

                                          $(td)[0].innerHTML =  text
                                          $(td).addClass('align-middle')

                                          
                                    }
                              },
                              {
                                    'targets': 2,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var buttons = '<button class="btn btn-sm btn-success mr-1 upload_grade" data-id="'+rowData.schedid+'"><i class="fas fa-user-circle"></i> Upload </i><button class="btn btn-sm btn-secondary mr-1 view_grade" data-id="'+rowData.schedid+'"><i class="fas fa-user-circle"></i> View Grades </i></button>'
                                          $(td)[0].innerHTML = buttons
                                          $(td).addClass('text-right')
                                          $(td).addClass('align-middle')
                                          
                                    }
                              }

                        ]
                  })
            }
      
      </script>

      <script>
            view_monitoring_numbers()

            function view_monitoring_numbers(){
                  $.ajax({
                        type:'GET',
                        url: '/college/grade/ecr/monitoring/count',
                        data:{
                              syid:$('#filter_sy').val(),
                              semid:$('#filter_semester').val(),
                        },
                        success:function(data) {
                              
                              term_sum = data[0].terms 
                              schedcount = data[0].schedcount
                              $('#term_sum_stat').empty()
                              $.each(term_sum,function(a,b){
                                    $('#term_sum_stat').append(
                                          `<tr>
                                                <td>`+b.term+`</td>  
                                                <td class="text-center">`+schedcount+`</td>
                                                <td class="text-center">`+b.unsubmitted+`</td>    
                                                <td class="text-center">`+b.submitted+`</td>
                                                <td class="text-center">`+b.approved+`</td>
                                                <td class="text-center">`+b.posted+`</td>
                                                <td class="text-center">`+b.pending+`</td>
                                          </tr>`
                                    )
                              })
                        }
                  })
            }
      </script>

      <script>
            datatable_1()

            function datatable_1(){


                  var columnDefs = []

                  columnDefs.push({
                                    'targets': 0,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).css('white-space','normal')
                                          $(td).css('min-width','300px')
                                    }
                              })

                  columnDefs.push({
                                    'targets': 1,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          if(schoolinfo == 'DCC'){
                                                var text = rowData.code
                                          }else{
                                                var text = ''
                                                $.each(rowData.sections,function(a,b){
                                                      text += '<span class=" badge badge-primary  mt-1" style="font-size:.65rem !important; white-space:normal" >'+b.schedgroupdesc+'</span> <br>'
                                                })
                                          }
                                          $(td)[0].innerHTML =  text
                                          $(td).addClass('align-middle')
                                          
                                    }
                              })
                            
                              
                  var terms = [
                        'PRE-MIDTERM',
                        'PRELIM',
                        'MIDTERM',
                        'CR MIDTERM SUMMARY',
                        'SEMI-FINAL',
                        'PRE-FINAL',
                        'FINAL',
                        'CR FINAL SUMMARY',
                        'CR SEMESTER SUMMARY'
                  ];

                  var colcount = 2;
                  $.each(terms, function(a,b){
                        columnDefs.push( {
                                    'targets': colcount,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var tempinfo = gradeinfo.filter(x=>x.schedid == rowData.id && x.term == b)
                                          if(tempinfo.length > 0 
                                          && tempinfo[0].statusdate != ""
                                          ){
                                                var text = '<a href="javascript:void(0)" class="mb-0 view_grade" data-id="'+rowData.id+'" data-term="'+b+'">'+tempinfo[0].statustext+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+tempinfo[0].statusdate+'</p>'
                                                $(td)[0].innerHTML = text
                                          }else{
                                                $(td).text('Not Submitted')
                                          }
                                    }
                              })

                        colcount += 1;
                  })

                  $("#sched_grade").DataTable({
                        destroy: true,
                        // data:temp_subjects,
                        lengthChange: false,
                        scrollX: true,
                        autoWidth: false,
                        stateSave: true,
                        serverSide: true,
                        processing: true,
                        fixedColumns: {
                              leftColumns: 2,
                        },
                        ajax:{
                              url: '/college/grade/ecr/monitoring/list',
                              type: 'GET',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    status:$('#filter_status').val(),
                                    term:$('#filter_grade_term').val(),
                              },
                              dataSrc: function ( json ) {
                                    // term_sum = json.terms 
                                    gradeinfo = json.gradeinfo
                                    all_subject = json.sched;
                              
                                    return json.data;
                              }
                        },
                        columns: [
                              { "data": "subjDesc" },
                              { "data": null },
                              { "data": null },
                              { "data": null },
                              { "data": null },
                              { "data": null },
                              { "data": null },
                              { "data": null },
                              { "data": null },
                              { "data": null },
                              { "data": null }
                        ],
                        columnDefs: columnDefs
                  })
            }
      </script>
@endsection

