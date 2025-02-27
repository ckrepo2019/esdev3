@php
      if(!Auth::check()){
            header("Location: " . URL::to('/'), true, 302);
            exit();
      }


      $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }
      else if(Session::get('currentPortal') == 3){
        $extend = 'registrar.layouts.app';
      }
      else if(Session::get('currentPortal') == 4){
         $extend = 'finance.layouts.app';
      }else if(Session::get('currentPortal') == 15){
            $extend = 'finance.layouts.app';
      }else if(Session::get('currentPortal') == 14){
            $extend =  'deanportal.layouts.app2';
      }else if(Session::get('currentPortal') == 8){
            $extend =  'admission.layouts.app2';
      }
      else if(auth()->user()->type == 6 ){
            $extend =  'adminportal.layouts.app2';
      }else{
            if(isset($check_refid->refid)){
                  if($check_refid->refid == 26){
                        $extend = 'registrar.layouts.app';
                  }else if($check_refid->refid == 28){
                        $extend = 'officeofthestudentaffairs.layouts.app2';
                  }else if($check_refid->refid == 29){
                        $extend = 'idmanagement.layouts.app2';
                  }else if($check_refid->refid == 31){
                        $extend = 'guidance.layouts.app2';
                  }else if($check_refid->refid == 30){
                        $extend = 'encoder.layouts.app2';
                  }
            }
            
      }

      $refid = $check_refid->refid;
     

@endphp


@extends($extend)

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
                                          <select name="" id="filter_term" class="form-control form-control-sm" disabled="disabled">
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
                              {{-- <div class="row">
                                    <div class="col-md-12 form-group">
                                          <button class="btn btn-primary btn-sm btn-block" id="ecr_filter"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                              </div> --}}
                           
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
                                          <strong><i class="fas fa-book mr-1"></i> Status Submitted</strong>
                                          <p class="text-muted" id="label_datesubmitted">
                                                --
                                          </p>
                                    </div>
                              </div>
                        </div>
                        <div class="col-md-10">
                              <div class="row" >
                                    <div class="col-md-6">
                                          <button class="btn btn-secondary btn-sm" id="logs_button">Logs</button>
                                    </div>
                                    <div class="col-md-6 text-right">
                                          <button class="btn btn-primary btn-sm btn-warning status_button" data-status="3">Pending</button>
                                          <button class="btn btn-primary btn-sm btn-primary status_button"  data-status="2">Approve</button>
                                          <button class="btn btn-primary btn-sm btn-info status_button"  data-status="4">Post</button>
                                          <button class="btn btn-primary btn-sm btn-danger status_button">Unpost</button>
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
                  <div class="modal-header bg-primary">
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
                  <h1>Grade Monitoring</h1>
              </div>
              <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="/home">Home</a></li>
                  <li class="breadcrumb-item active">Grade Monitoring</li>
              </ol>
              </div>
          </div>
      </div>
</section>
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="info-box shadow-lg">
                                          <div class="info-box-content">
                                                <div class="row">
                                                      <div class="col-md-2">
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
                                                      <div class="col-md-2" >
                                                            <label for="">Semester</label>
                                                            <select class="form-control form-control-sm  select2" id="filter_semester">
                                                                  <option value="">Select semester</option>
                                                                  @foreach ($semester as $item)
                                                                        <option {{$item->isactive == 1 ? 'selected' : ''}} value="{{$item->id}}">{{$item->semester}}</option>
                                                                  @endforeach
                                                            </select>
                                                      </div>
                                                      <div class="col-md-2" >
                                                            <label for="">Status</label>
                                                            <select class="form-control form-control-sm  select2" id="filter_status">
                                                                  <option value="">All</option>
                                                                  <option value="0">Not Submitted</option>
                                                                  <option value="1">Submitted</option>
                                                                  <option value="2">Approved</option>
                                                                  <option value="4">Posted</option>
                                                                  <option value="3">Pending</option>
                                                            </select>
                                                      </div>
                                                      <div class="col-md-3 form-group">
                                                            <label for="">Term</label>
                                                            <select name="" id="filter_grade_term" class="form-control form-control-sm" >
                                                                  <option value="">All</option>
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
                                                      <div class="col-md-3 form-group mb-0" >
                                                            <label for="" >College Instructor</label>
                                                            <select class="form-control form-control-sm select2" id="filter_teacher">
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
                                          <div class="col-md-12" style="font-size: .9rem !important">
                                                <table class="table table-sm table-striped table-bordered table-sm display nowrap"  id="sched_grade">
                                                      <thead>
                                                            <tr>
                                                                  <th style="min-width:20px !important; white-space: normal;">Schedule</th>
                                                                  <th >Section</th>
                                                                  <th >Instructor</th>
                                                                  <th class="text-center mon_head" data-id="PRE-MIDTERM">PRE-MIDTERM</th>
                                                                  <th class="text-center mon_head" data-id="PRELIM">PRELIM</th>
                                                                  <th class="text-center mon_head" data-id="MIDTERM">MIDTERM</th>
                                                                  <th class="text-center mon_head" data-id="CR MIDTERM SUMMARY">CR MID SUM</th>
                                                                  <th class="text-center mon_head" data-id="SEMI-FINAL">SEMI-FINAL</th>
                                                                  <th class="text-center mon_head" data-id="PRE-FINAL">PRE-FINAL</th>
                                                                  <th class="text-center mon_head" data-id="FINAL">FINAL</th>
                                                                  <th class="text-center mon_head" data-id="CR FINAL SUMMARY">CR FINAL SUM</th>
                                                                  <th class="text-center mon_head" data-id="CR SEMESTER SUMMARY">CR SEM SUM</th>
                                                            </tr>
                                                      </thead>
                                                 
                                                </table>
                                          </div>
                                    </div>
                                    <div class="row mt-5">
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
  </section>
  
     
@endsection

@section('footerjavascript')

      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/moment/moment.min.js') }}"></script>
     

      <script>
            $('#filter_sy').select2()
            $('#filter_semester').select2()
            $('#filter_status').select2({
                  'allowClear':true,
                  'placeholder':'All'
            })
            $('#filter_grade_term').select2({
                  'allowClear':true,
                  'placeholder':'All'
            })
            
      </script>

      <script>

            const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 2000,
            })

            var all_subject = []
            var term_sum = []
            var gradeinfo = []

            $('#filter_teacher').select2({
                  placeholder: "All",
                  allowClear:true,
                  ajax: {
                        url: '/college/subject/schedule/teachers',
                        data: function (params) {
                              var query = {
                                    search: params.term,
                                    page: params.page || 0
                              }
                              return query;
                        },
                        dataType: 'json',
                        processResults: function (data, params) {
                              params.page = params.page || 0;
                              return {
                                    results: data.results,
                                    pagination: {
                                          more: data.pagination.more
                                    }
                              };
                        }
                  }
            });

            // view_monitoring()
            // datatable_1([])

            $(document).on('click','.view_grade',function(){
                  $('#view_ecr_modal').modal()
                  selected_schedid = $(this).attr('data-id')
                  selecter_term = $(this).attr('data-term')


                  var subjinfo = all_subject.filter(x=>x.schedid == selected_schedid)
                  $('#label_subject').text(subjinfo[0].subjDesc)
                  $('#label_subjectcode').text(subjinfo[0].subjCode)
                  $('#filter_term').val(selecter_term).change()
                  var tempinfo = gradeinfo.filter(x=>x.schedid ==selected_schedid && x.term == selecter_term)

                  $('#label_status').text(tempinfo[0].statustext)
                  $('#label_datesubmitted').text(tempinfo[0].statusdate)
                  $('#label_dateuploaded').text(tempinfo[0].uploaddate)

                  view_ecr()
            })

          

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

              // display_summary(json.terms,json.sched)
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

            $(document).on('click','#logs_button',function(){
                  $('#grade_logs_modal').modal();
                  get_logs()
            })

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
            

              
            $(document).on('change','#filter_status , #filter_grade_term , #filter_teacher, #filter_semester, #filter_sy',function(){
                  datatable_1()
                  view_monitoring_numbers()

            })

            datatable_1()

            var schoolinfo = @json($schoolinfo);

            function datatable_1(){


                  var columnDefs = []

                  columnDefs.push({
                                    'targets': 0,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td)[0].innerHTML = '<b>'+rowData.subjCode+'</b> : '+rowData.subjDesc
                                          $(td).css('white-space','normal')
                                          $(td).css('min-width','250px')
                                    }
                              })

                  columnDefs.push({
                                    'targets': 1,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var text = ''
                                          if(schoolinfo == 'DCC'){
                                               text += rowData.code
                                          }else{
                                                $.each(rowData.sections,function(a,b){
                                                      text += '<span class=" badge badge-primary  mt-1" style="font-size:.65rem !important; white-space:normal" >'+b.schedgroupdesc+'</span> <br>'
                                                })
                                          }
                                          
                                                
                                          $(td)[0].innerHTML =  text
                                          $(td).addClass('align-middle')
                                    }
                              })

                  columnDefs.push({
                        'targets': 2,
                        'orderable': true, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                              var text = ''
                              if(rowData.teacherid != null){
                                    var text = '<a href="javascript:void(0)" class="mb-0" style="font-size: .8rem !important" >'+rowData.lastname+', '+rowData.firstname+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.tid+'</p>'
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

                  // if($('#filter_grade_term').val() != ""){
                  //       $('.mon_head').attr('hidden','hidden')
                  // }

                  // if($('#filter_grade_term').val() != ""){
                  //       $.each(terms, function(a,b){
                  //             if($('#filter_grade_term').val() == b){
                  //                   $('.mon_head[data-id="'+b+'"]').removeAttr('hidden')
                  //             }else{
                  //                   $('.mon_head[data-id="'+b+'"]').attr('hidden','hidden')
                  //             }
                  //       })
                  // }

                  var colcount = 3;
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
                                          if($('#filter_grade_term').val() != "" && $('#filter_grade_term').val() != b){
                                                $('.mon_head[data-id="'+b+'"]').attr('hidden','hidden')
                                                $(td).attr('hidden','hidden')
                                          }else{
                                                $('.mon_head[data-id="'+b+'"]').removeAttr('hidden')
                                                $(td).removeAttr('hidden')
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
                              leftColumns: 3,
                        },
                        ajax:{
                              url: '/college/grade/ecr/monitoring/list',
                              type: 'GET',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    status:$('#filter_status').val(),
                                    term:$('#filter_grade_term').val(),
                                    teacherid:$('#filter_teacher').val(),
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
                              { "data": null },
                              { "data": null }
                        ],
                        columnDefs: columnDefs
                  })
            }

            $(document).on('click','.status_button',function(){
                  var html = '';
                  if($(this).attr('data-status') == 3){
                        html =  'Are you sure you want <br>' +
                              'to add grades to pending?',
                        confirmButtonText = 'Yes, add grades to pending!'
                        url = '/college/grade/ecr/pending'
                  }else if($(this).attr('data-status') == 2){
                        html =  'Are you sure you want <br>' +
                              'to approve grades?',
                        confirmButtonText = 'Yes, approve grades!'
                        url = '/college/grade/ecr/approve'
                  }else if($(this).attr('data-status') == 4){
                        html =  'Are you sure you want <br>' +
                              'to post grades?',
                        confirmButtonText = 'Yes, post grades!'
                        url = '/college/grade/ecr/post'
                  }
                  Swal.fire({
                        html:html,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: confirmButtonText
                  }).then((result) => {
                        if (result.value) {
                              var tempid = $('.status_button').attr('data-id')
                              $.ajax({
                                    type:'GET',
                                    url: url,
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
                                                      title: data[0].message
                                                })
                                                datatable_1()
                                                view_monitoring_numbers()
                                                view_ecr()
                                          }else if(data[0].status == 2){
                                                Toast.fire({
                                                      type: 'warning',
                                                      title: data[0].message
                                                })
                                          }else{
                                                Toast.fire({
                                                      type: 'error',
                                                      title: 'Something went wrong!'
                                                })
                                          }
                                    }
                              }) 
                        }
                  })
            })

          
      </script>
@endsection

