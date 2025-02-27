
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
        .shadow {
              box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
              border: 0;
        }
  </style>
@endsection

@section('content')

@php
   $sy = DB::table('sy')->orderBy('sydesc')->get(); 
   $semester = DB::table('semester')->get(); 
   $schoolinfo = DB::table('schoolinfo')->first()->abbreviation;
@endphp

<div class="modal fade" id="modal_1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-xl">
            <div class="modal-content">
                  <div class="modal-header pt-2 border-0 pb-0">
                        <h4 class="modal-title"><span class="mt-1">Student List</span>  </h4>
                        <a class="btn btn-primary btn-sm ml-2" id="view_pdf" href="#"><i class="far fa-file-pdf"></i> PDF</a>
                        <button type="button" class="close pb-2" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0" style="font-size:.8rem">
                        <table class="table table-sm table-striped" id="datatable_2"  width="100%">
                              <thead>
                                    <tr>
                                          <th width="30%">Student</th>
                                          <th width="10%">SID</th>
                                          <th width="12%">Year Level</th>
                                          <th width="10%">Course</th>
                                          <th width="8%">Gender</th>
                                          <th width="10%">Contact</th>
                                          <th width="20%">Email</th>
                                    </tr>
                              </thead>
                        </table>
                  </div>
            </div>
      </div>
</div>   

<div class="modal fade" id="modal_3" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Student List</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body" style="font-size:.9rem">
						   <div class="row">
								  
								  <div class="col-md-3">
										<select class="form-control form-control-sm select2"  id="month_filter">
											  <option value="">Select Month</option>
											  @for($x = 1 ; $x <= 12 ; $x++)
													<option value="{{strtoupper(\Carbon\Carbon::create($x.'/01/1999')->isoFormat('MMMM'))}}">{{\Carbon\Carbon::create($x.'/01/1999')->isoFormat('MMMM')}}</option>
											  @endfor
										</select>
								  </div>
							</div>
							<div class="row mt-2">
								  <div class="col-md-12">
										<table class="table table-sm table-striped" id="datatable_3"  width="100%">
											  <thead>
													<tr>
														  <th width="45%">Student</th>
														  <th width="20%">SID</th>
														  <th width="40%">Status</th>
														  {{-- <th width="10%">Midterm</th>
														  <th width="10%">PreFinal</th>
														  <th width="10%">Final</th> --}}
													</tr>
											  </thead>
										</table>
								  </div>
							</div>
                  </div>
            </div>
      </div>
</div>   

<div class="modal fade" id="modal_2" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Grades</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12">
                                    <table class="table table-sm table-striped mb-0  table-striped"  style="font-size:.9rem">
                                          <tr>
                                                <th id="section" width="30%"></th>
                                                <th id="subject" width="70%"></th>
                                          </tr>
                                    </table>
                              </div>
                              <div class="col-md-12">
                                    <table class="table table-sm table-striped table-bordered "  style="font-size:.9rem">
                                          <thead>
                                                <tr>
                                                      <th id="section" width="40%">Student</th>
                                                      <th id="subject" width="15%" class="text-center">Prelim</th>
                                                      <th id="subject" width="15%" class="text-center">Midterm</th>
                                                      <th id="subject" width="15%" class="text-center">PreFinal</th>
                                                      <th id="subject" width="15%" class="text-center">Final</th>
                                                </tr>
                                          </thead>
                                          <tbody id="student_list_grades">
            
                                          </tbody>
                                    </table>
                              </div>
                              <div class="col-md-12">
                                    <button id="save_grades" class="btn btn-primary btn-sm" disabled hidden>Save Grades</button>
                              </div>
                        </div>
                  </div>
                  <div class="modal-footer pt-1 pb-1"  style="font-size:.7rem">
                        <i id="message_holder"></i>
                  </div>
            </div>
      </div>
</div>   

<section class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h1>Student Information</h1>
              </div>
              <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="/home">Home</a></li>
                  <li class="breadcrumb-item active">Student Information</li>
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
                                          <span class="info-box-icon bg-primary"><i class="fas fa-calendar-check"></i></span>
                                          <div class="info-box-content">
                                                <div class="row">
                                                      <div class="col-md-4  form-group">
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
                                                      <div class="col-md-4 form-group" >
                                                            <label for="">Semester</label>
                                                            <select class="form-control form-control-sm  select2" id="filter_semester">
                                                                  <option value="">Select semester</option>
                                                                  @foreach ($semester as $item)
                                                                        <option {{$item->isactive == 1 ? 'selected' : ''}} value="{{$item->id}}">{{$item->semester}}</option>
                                                                  @endforeach
                                                            </select>
                                                      </div>
                                                      {{-- <div class="col-md-4 form-group" >
                                                            <label for="">Term</label>
                                                            <select class="form-control form-control-sm select2" id="term">
                                                                  <option value="">All</option>
                                                                  <option value="Whole Sem">Whole Sem</option>
                                                                  <option value="1st Term">1st Term</option>
                                                                  <option value="2nd Term">2nd Term</option>
                                                            </select>
                                                      </div> --}}
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-4">
                                                            <button class="btn btn-info btn-block btn-sm" id="filter_button_1"><i class="fas fa-filter"></i> Filter</button>
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
                                                <table class="table table-sm table-striped" id="datatable_1" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="20%">Section</th>
                                                                  <th width="45%">Subject</th>
                                                                  <th width="30%" class="text-center"></th>
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

@section('footerscript')

      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
	    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>

      <script>
            $(document).ready(function(){

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  $.ajaxSetup({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                  });
				  
				  $('#month_filter').select2()

                  var all_subject = []
                  var all_permits = []
                  get_subjects()


                  $(document).on('click','#filter_button_1',function (){
                        get_subjects()
                  })

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
                                    all_subject = data
                                    get_exampermit()
                                    datatable_1()
                              }
                        })
                  }


                  function get_exampermit() {
                        $.ajax({
                              type:'GET',
                              url: '/college/teacher/student/exampermit',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                              },
                              success:function(data) {
                                    $('.view_permit').removeAttr('disabled')
                                    all_permits = data
                              }
                        })
                  }

                  var temp_id = null

                  $(document).on('click','.view_students',function(){
                        $('#modal_1').modal()
                        temp_id = $(this).attr('data-id')
                        var students = all_subject.filter(x=>x.schedid == temp_id)
                        datatable_2(students[0].students)
                  })

                  $(document).on('click','.view_permit',function(){
                        $('#modal_3').modal()
                        temp_id = $(this).attr('data-id')
                        var students = all_subject.filter(x=>x.schedid == temp_id)
                        var permits = all_permits.filter(x=>x.schedid == temp_id)
                        datatable_3(students[0].students,permits[0].exampermit)
                  })





                  function get_grades(schedid) {

                        var sched = all_subject.filter(x=>x.schedid == schedid)

                        var pid = sched[0].pid
                        var sectionid = sched[0].sectionID

                        $.ajax({
                              type:'GET',
                              url: '/college/teacher/student/grades/get',
                              data:{

                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    pid:pid,
                                    sectionid:sectionid
                              },
                              success:function(data) {

                                    $('.grade_td').addClass('input_grades')

                                    if(data.length == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'No grades found!'
                                          })
                                          $('#message_holder').text('No grades found. Please input student grades.')
                                    }else{
                                          $.each(data,function(a,b){
                                                $('.input_grades[data-studid="'+b.studid+'"][data-term="prelemgrade"]').text(b.prelemgrade != null ? parseInt(b.prelemgrade) : '')
                                                $('.input_grades[data-studid="'+b.studid+'"][data-term="midtermgrade"]').text(b.midtermgrade != null ? parseInt(b.midtermgrade) : '')
                                                $('.input_grades[data-studid="'+b.studid+'"][data-term="prefigrade"]').text(b.prefigrade != null ? parseInt(b.prefigrade) : '')
                                                $('.input_grades[data-studid="'+b.studid+'"][data-term="finalgrade"]').text(b.finalgrade != null ? parseInt(b.finalgrade) : '')
                                          })
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Grades found!'
                                          })
                                          $('#message_holder').text('Grades found.')
                                    }
                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                                    $('#message_holder').text('Unable to load grades.')
                              }
                        })
                  }



                  
                  $(document).on('click','.view_students',function(){
                        $('#modal_1').modal()
                        temp_id = $(this).attr('data-id')
                        var students = all_subject.filter(x=>x.schedid == temp_id)
                        datatable_2(students[0].students)
                  })

                  $(document).on('click','.page-link',function(){
                        $('.view_permit').removeAttr('disabled')
                  })

                  $(document).on('click','#view_pdf',function(){
                        window.open('/college/teacher/student/information/pdf?syid='+$('#filter_sy').val()+'&semid='+$('#filter_semester').val()+'&schedid='+temp_id, '_blank');
                  })


                  


                  function datatable_2(students){

                        $("#datatable_2").DataTable({
                              destroy: true,
                              data:students,
                              lengthChange: false,
                              autoWidth: false,
                              columns: [
                                    { "data": "search"},
                                    { "data": "sid"},
                                    { "data": "levelname"},
                                    { "data": "courseabrv"},
                                    { "data": "gender"},
                                    { "data": "contactno"},
                                    { "data": "semail"},
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td)[0].innerHTML =  rowData.student
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td)[0].innerHTML =  rowData.levelname.replace('COLLEGE','')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 5,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                          }
                                    },
                                    {
                                          'targets': 6,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                               
                                          }
                                    },
                              ]
                        })


                  }
				  
				  
				  $(document).on('change','#month_filter',function(){
					  
					  console.log("sdfsdf")
						var students = all_subject.filter(x=>x.schedid == temp_id)
                        var permits = all_permits.filter(x=>x.schedid == temp_id)
                        datatable_3(students[0].students,permits[0].exampermit)
					  
				  })

                  function datatable_3(students,permits){
					  
					  
                        $("#datatable_3").DataTable({
                              destroy: true,
                              data:students,
                              lengthChange: false,
                              autoWidth: false,
                              columns: [
                                    { "data": "search"},
                                    { "data": "sid"},
                                    { "data": null},
                                   
                              ],
                              columnDefs: [
									{
                                          'targets':0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td)[0].innerHTML =  rowData.student
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td)[0].innerHTML =  rowData.sid
                                                $(td).addClass('align-middle')
                                          }
                                    },
									{
                                          'targets': 2,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
												
												if(all_permits.length != 0){
													var check = permits.filter(x=>x.studid == rowData.studid && x.month == $('#month_filter').val())
													
													console.log(permits.filter(x=>x.studid == rowData.studid))
												
													if(check.length > 0){
														$(td)[0].innerHTML = '<span class="badge badge-success">Permitted</span>'
													}else{
														$(td)[0].innerHTML = '<span class="badge badge-danger">Not Permitted</span>'
													}
												}
                                                
                                          }
                                    },
                                    
                              ]
                        })
                  }

                  $(document).on('change','#term',function (){
                        datatable_1()
                  })


                  function datatable_1(){

                        var all_data = all_subject

                        $("#datatable_1").DataTable({
                              destroy: true,
                              data:all_data,
                              lengthChange: false,
                              scrollX: true,
                              autoWidth: false,
                              columns: [
                                    { "data": "sectionDesc"},
                                    { "data": "subjDesc" },
                                    { "data": null }
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<a class="mb-0">'+rowData.sectionDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.levelname.replace('COLLEGE','')+' - '+rowData.courseabrv+'</p>';
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var schedotherclass = rowData.schedotherclass != null ? rowData.schedotherclass : 'Whole Semester'

                                                var text = '<a class="mb-0">'+rowData.subjDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.subjCode+' - <i class="mb-0 text-danger" style="font-size:.7rem">'+schedotherclass+'</i></p>';
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<button class="btn btn-sm btn-primary mr-1 view_students" data-id="'+rowData.schedid+'"><i class="fas fa-user-circle"></i> Students <i>('+rowData.students.length+')</i></button>'

                                                buttons += '<button class="btn btn-sm btn-secondary mr-1 view_permit" disabled data-id="'+rowData.schedid+'"><i class="fas fa-sign-in-alt"></i> Exam Permit</button>'
                                                $(td)[0].innerHTML = buttons
                                                $(td).addClass('text-right')
                                                $(td).addClass('align-middle')
                                          }
                                    }

                              ]
                        })
                  }
            })
      </script>
@endsection

