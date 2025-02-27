
@extends('chairpersonportal.layouts.app2')

<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/gijgo.min.css') }}"> 
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    

    

@section('pagespecificscripts')

      <style>
            .dropdown-toggle::after {
                  display: none;
                  margin-left: .255em;
                  vertical-align: .255em;
                  content: "";
                  border-top: .3em solid;
                  border-right: .3em solid transparent;
                  border-bottom: 0;
                  border-left: .3em solid transparent;
            }

            .table th{
                  border: 1px solid #dee2e6 !important;
            }
            .gj-picker{
                  top: 20px !important;
            }
      </style>
      
@endsection

@section('content')

      <div class="modal fade" id="schedule_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-header bg-primary">
                              <h4 class="modal-title">Add Section Detail</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                              </button>
                        </div>
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Subject</label>
                                          <input type="text" class="form-control" id="selected_subject" disabled>
                                    </div>
                                    <div class="col-md-12 form-group">
                                          <label for="">Schedule Specification</label>
                                          <select name="sched_class" id="sched_class" class="select2 form-control" disabled>
                                                <option value="1">Lecture Schedule</option>
                                                <option value="2">Laboratory Schedule</option>
                                          </select>
                                    </div>
                                     <div class="col-md-12 form-group">
                                          <label for="">Room</label>
                                          <select name="roomid" id="roomid" class="select2 form-control">
                                                <option value="">Select a room</option>
                                                @foreach (DB::table('rooms')->where('deleted',0)->select('id','roomname')->get() as $item)
                                                      <option value="{{$item->id}}">{{$item->roomname}}</option>
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                          <label>FROM</label>
                                          <input name="t_from" id="t_from" value="07:30" type="timepicker" class="form-control"/>
                                    </div>
                                    <div class="col-md-6 form-group">
                                          <label>TO</label>
                                          <input name="t_to" id="t_to" value="05:00"  type="timepicker" class="form-control"/>
                                    </div>
                                    <div class="icheck-success d-inline col-md-3">
                                          <input type="checkbox" id="Mon" value="1" class="day_list">
                                          <label for="Mon">Monday</i>
                                          </label>
                                    </div>
                                    <div class="icheck-success d-inline col-md-3">
                                          <input type="checkbox" id="Tue" value="2" class="day_list">
                                          <label for="Tue">Tuesday</i>
                                          </label>
                                    </div>
                                    <div class="icheck-success d-inline col-md-3">
                                          <input type="checkbox" id="Wed" value="3" class="day_list">
                                          <label for="Wed">Wednesday</i>
                                          </label>
                                    </div>
                                    <div class="icheck-success d-inline col-md-3">
                                          <input type="checkbox" id="Thu" value="4" class="day_list">
                                          <label for="Thu">Thursday</i>
                                          </label>
                                    </div>
                                    <div class="icheck-success d-inline col-md-3">
                                          <input type="checkbox" id="Fri" value="5" class="day_list">
                                          <label for="Fri">Friday</i>
                                          </label>
                                    </div>
                                    <div class="icheck-success d-inline col-md-3">
                                          <input type="checkbox" id="Sat" value="6" class="day_list">
                                          <label for="Sat">Saturday</i>
                                          </label>
                                    </div>
                                    <div class="icheck-success d-inline col-md-3">
                                          <input type="checkbox" id="Sun" value="7" class="day_list">
                                          <label for="Sun">Sunday</i>
                                          </label>
                                    </div>
                              </div>
                              <hr>
                              {{-- <div class="row">
                                    <div class="col-md-12">
                                          <label for="">Subject Code</label>
                                          <input id="filter_schedulecoding" class="form-control" autocomplete="off">
                                    </div>
                                    <div class="col-md-12  pt-4" id="subjectcoding_holder">

                                    </div>
                              </div> --}}
                              {{-- <div class="row">
                                    <div class="col-md-12">
                                          <label for="">Available Schedule</label>
                                          <table class="table table-sm" width="100%">
                                                <thead>
                                                      <tr>
                                                            <th>Day</th>
                                                            <th>Time</th>
                                                            <th>Room</th>
                                                            <th>Course</th>
                                                      </tr>
                                                </thead>
                                                <tbody id="avail_sched">
                                                      

                                                </tbody>
                                          </table>
                                    </div>
                              </div> --}}
                        </div>
                        <div class="modal-footer">
                              <button class="btn btn-primary savebutton" id="create_schedule">Create Schedule</button>
                        </div>
                  </div>
            </div>
      </div>

      <div class="modal fade" id="update_section_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-header bg-primary">
                              <h4 class="modal-title">Create Section</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                              </button>
                        </div>
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">SECTION NAME</label>
                                          <input class="form-control" id="section_name_update" value="{{$sectionInfo->sectionDesc}}">
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button class="btn btn-success" id="upate_section_save">Update Section</button>
                        </div>
                  </div>
            </div>
      </div>

      <div class="modal fade" id="add_teacher" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-header bg-primary">
                              <h4 class="modal-title">Create Section</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                              </button>
                        </div>
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Instructor</label>
                                          <select name="college_instructor" id="college_instructor" class="form-control select2">
                                          </select>
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button class="btn btn-primary" id="add_instructor">Add Instructor</button>
                        </div>
                  </div>
            </div>
      </div>


      <div class="modal fade" id="add_subject_schedule_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-header bg-primary">
                              <h4 class="modal-title">Create Section</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                              </button>
                        </div>
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Subject</label>
                                          <select name="subject_schedule_subject" id="subject_schedule_subject" class="form-control select2">
                                          </select>
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button class="btn btn-primary" id="submit_subject_schedule">Add Subject Schedule</button>
                        </div>
                  </div>
            </div>
      </div>

      <div class="modal fade" id="add_subject_schedule_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-header bg-primary">
                              <h4 class="modal-title">Create Section</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                              </button>
                        </div>
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Subject</label>
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button class="btn btn-primary" id="submit_subject_schedule">Add Subject Schedule</button>
                        </div>
                  </div>
            </div>
      </div>

      <div class="modal fade" id="schedule_coding_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                        <div class="modal-header bg-primary">
                              <h4 class="modal-title">Get Schedule Coding</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                              </button>
                        </div>
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-12">
                                          <label for="">Schedule Code</label>
                                          <input id="filter_schedulecoding" class="form-control" autocomplete="off">
                                    </div>
                                    <div class="col-md-12  pt-4" id="subjectcoding_holder">
                                          <table class="table table-striped table-bordered table-head-fixed nowrap display table-sm p-0" id="schedulecoding_table" width="100%">
                                                <thead>
                                                      <tr>
                                                            <th widht="5%"></th>
                                                            <th  width="10%">School Year</th>
                                                            <th  width="10%">Semester</th>
                                                            <th  width="10%">Code</th>
                                                            <th  width="45%">Subject</th>
                                                            <th  width="20%">Sched</th>
                                                      </tr>
                                                </thead>
                                                <tbody >
                                                      
                                                </tbody>
                                          </table>
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button class="btn btn-primary" id="submit_subject_schedule">Add Subject Schedule</button>
                        </div>
                  </div>
            </div>
      </div>

      <section class="content">
            <div class="row">
                  <div class="col-md-12">
                        <div class="card">
                              <div class="card-header bg-danger p-1"></div>
                              <div class="card-body">
                                    {{-- <div class="row"> --}}
                                          
                                          {{-- <button class="btn btn-primary" id="add_subject_schedule_to_modal"><i class="fas fa-plus-square"></i> ADD SUBJECT SCHEDULE</button> --}}
                                          <button class="btn btn-danger float-right mr-1 ml-1" id="remove_section_button"><i class="fas fa-trash-alt"></i> Remove</button>
                                          <button class="btn btn-success float-right mr-1 ml-1" id="update_section_button"><i class="fas fa-edit"></i> Update</button>
                                         
                                    {{-- </div> --}}
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card">
                              <div class="card-head bg-info p-1"></div>
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-4 form-group">
                                                <label for="">Section Name</label>
                                                <input class="form-control" disabled="disabled" id="section_name_info" value="{{$sectionInfo->sectionDesc}}">
                                          </div>
                                          <div class="col-md-4 form-group">
                                                <label for="">Grade Level</label>
                                                <input class="form-control" disabled="disabled" value="{{$sectionInfo->levelname}}">
                                          </div>
                                          <div class="col-md-4 form-group">
                                                <label for="">Curriculum</label>
                                                <input class="form-control" disabled="disabled" value="{{$sectionInfo->curriculumname}}">
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-2 form-group">
                                                <label for="">Total Units</label>
                                                <input class="form-control" disabled="disabled" id="section_total_units_info">
                                          </div>
                                          <div class="col-md-2 form-group">
                                                <label for="">Subject Count</label>
                                                <input class="form-control" disabled="disabled" id="subject_count_info" >
                                          </div>
                                          <div class="col-md-2 form-group">
                                                <label for="">With Schedule</label>
                                                <input class="form-control" disabled="disabled" id="with_sched_info" >
                                          </div>
                                          <div class="col-md-2 form-group">
                                                <label for="">Without Schedule</label>
                                                <input class="form-control" disabled="disabled" id="without_sched_info" >
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card ">
                              <div class="card-header bg-primary p-1">
                                    
                              </div>
                              <div class="card-body p-0 table-responsive" id="college_sched_holder">
                                  
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row" id="unloaded_subjects_holder">
                  
            </div>
      </section>

@endsection

@section('footerjavascript')
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('assets/scripts/gijgo.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
	<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
	<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      
      <script>
              $(document).ready(function(){

                  $(document).on('input','#filter_schedulecoding',function(){
                        get_schedulecoding()
                        filter_schedulecoding = $('#filter_schedulecoding').val()
                  })

                
                  $(document).on('click','.get_schedcoding',function(){
                        var temp_id = $(this).attr('data-id')
                        var temp_schedcoding = all_schedulcoding.filter(x=>x.id == temp_id)
                        $('#t_from').val(temp_schedcoding[0].details[0].timestart)
                  })


                  $(document).on('click','.get_schedcode',function(){
                        $('#schedule_coding_modal').modal()
                  })

                  function loaddatatable_schedulecodings(){
                        $("#schedulecoding_table").DataTable({
                              destroy: true,
                              data:schedulecoding_list,
                              scrollX: true,
                              columns: [
                                    { "data": null },
                                    { "data": "sydesc" },
                                    { "data": "semester" },
                                    { "data": "code" },
                                    { "data": "subjDesc" },
                                    { "data": null },
                                
                              ],
                              columnDefs: [  {
                                                'targets': 0,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      var html = '<input type="checkbox" data-id="'+rowData.id+'" class="select_schedulecoding">'
                                                      $(td)[0].innerHTML = html
                                                      $(td).addClass('text-center')
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 5,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {

                                                      $(td).attr('data-id',rowData.id)
                                                      var html = ''
                                                      $.each(rowData.details,function(a,b) {
                                                            html += '<span data-id="'+rowData.id+'" data-timestart="'+b.timestart+'" data-timeend="'+b.timeend+'">'
                                                            html += b.days + '  ' + b.timestart + ' - ' + b.timeend + '<br></span>'
                                                      })
                                                      $(td)[0].innerHTML = html
                                                
                                                
                                                }
                                          },
                              ]
                        })
                  }

                 

                  var schedulecoding_list = []
                  var filter_schedulecoding = null
                  get_schedulecoding()
                  
                  function get_schedulecoding(){
                        $.ajax({
                              type:'GET',
                              url: '/chairperson/schedule/coding/list',
                              data:{
                                    code:filter_schedulecoding
                              },
                              success:function(data) {
                                    schedulecoding_list = data
                                    loaddatatable_schedulecodings()
                              }
                        })
                  }

                  var selected_schedulecoding
                  var selected_section_schedulecoding

                  var sectionid = @json($sectionInfo->id);

                 
                  

              })
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

                  var actionFrom;
                  var select_sched

                  $(document).on('click','#update_section_button',function(){
                        $('#update_section_modal').modal()
                  })

                  $(document).on('click','#upate_section_save',function(){
                        $.ajax({
                              type:'GET',
                              url:"/college/chairpseron/sections/update",
                              data:{
                                    sectionid:'{{$sectionInfo->id}}',
                                    sectionname:$('#section_name_update').val()
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Swal.fire({
                                                type: 'success',
                                                title: 'Updated Successfully!',
                                          })
                                          $('#section_name_info').val($('#section_name_update').val())
                                    }
                                    else{
                                          Swal.fire({
                                                type: 'warning',
                                                title: 'Something went wrong!',
                                          })
                                    }
                              }
                        })
                  })

                  $(document).on('click','#remove_section_button',function(){
                        $.ajax({
                              type:'GET',
                              url:"/college/chairpseron/sections/remove",
                              data:{
                                    sectionid:'{{$sectionInfo->id}}',
                                    sectionname:$('#section_name_update').val()
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Swal.fire({
                                                type: 'success',
                                                title: 'Deleted Successfully!',
                                          })
                                          $('#section_name_info').val($('#section_name_update').val())
                                          window.location.replace("http://"+window.location.hostname+'/chairperson/sections')
                                    }
                                    else{
                                          Swal.fire({
                                                type: 'warning',
                                                text: data[0].data,
                                          })
                                    }
                              }
                        })
                  })

                  

                  $(document).on('click','.addIns',function(){
                        $('#add_teacher').modal()
                        select_sched = $(this).attr('data-id')
                  })

                  $(document).on('click','#add_instructor',function(){
                        $.ajax({
                              type:'GET',
                              url:"/college/chairpseron/addinstructor",
                              data:{
                                    schedid:select_sched,
                                    teacherid:$('#college_instructor').val()
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Swal.fire({
                                                type: 'success',
                                                title: 'Updated Successfully!',
                                          })
                                          showsched()
                                    }
                                    else{
                                          Swal.fire({
                                                type: 'warning',
                                                title: 'Something went wrong!',
                                          })
                                    }
                              }
                        })
                  })

                  load_teacher()

                  function load_teacher(){
                        $.ajax({
                              type:'GET',
                              url:"/college/techer",
                              success:function(data) {
                                    $.each(data,function(a,b){
                                          $('#college_instructor').append('<option value="'+b.id+'">'+b.name+'</option>')
                                    })

                              },
                        })
                  }

                  $(document).on('click','#add_subject_schedule_to_modal',function(){
                        $('#add_subject_schedule_modal').modal()
                  })

                  $(document).on('click','#submit_subject_schedule',function(){
                        var selected_subject = $('#subject_schedule_subject').val()
                        $.ajax({
                              type:'GET',
                              url:'/college/section/add/subject',
                              data:{
                                    syid:'{{$sectionInfo->syID}}',
                                    semid:'{{$sectionInfo->semesterID}}',
                                    sectionid:'{{$sectionInfo->id}}',
                                    subjectid:selected_subject,
                              },
                              success:function(data) {
                                   
                              },
                        })
                  })

                  var selected_sched
                  var selected_unit

                  $(document).on('click','#create_schedule',function(){

                        var selected_days = []

                        $('.day_list').each(function(a,b){
                              if($(this).prop('checked') == true){
                                    selected_days.push($(this).val())      
                              }
                        })

                        var sectionname = '{{$sectionInfo->sectionDesc}}'
                        sectionname = sectionname.toLowerCase().replace(/\s+/g, '-')

                        $.ajax({
                              type:'GET',
                              url:"/chairperson/scheddetail/create/"+sectionname,
                              data:{
                                    syid:'{{$sectionInfo->syID}}',
                                    semid:'{{$sectionInfo->semesterID}}',
                                    subjid:selected_subject,
                                    roomid:$('#roomid').val(),
                                    t_to:$('#t_to').val(),
                                    t_from:$('#t_from').val(),
                                    scheddetialclass:$('#sched_class').val(),
                                    day:selected_days,
                                    sectionid:'{{$sectionInfo->id}}',
                                    schedid:selected_sched
                              },
                              success:function(data) {
                                    if(actionFrom == 1){
                                          Swal.fire({
                                                type: 'success',
                                                title: 'Created Successfully!',
                                          })
                                          showsched()
                                    }
                                    else if(actionFrom == 2){
                                          Swal.fire({
                                                type: 'success',
                                                title: 'Updated Successfully!',
                                          })
                                          showsched()
                                    }
                                   
                              },
                        })
                  })

                  $(document).on('click','.removesched',function(){
                        var schedid = $(this).attr('data-id')
                        Swal.fire({
                              title: 'Delete Schedule?',
                              type: 'info',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Yes, Delete Schedule'
                        })
                        .then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'POST',
                                          data: {'_token': '{{ csrf_token() }}'},
                                          url:'/collegeschedule?scheddetail=scheddetail&remove=remove&scheddetailid='+schedid+'&sectionid='+'{{$sectionInfo->id}}'+'&syid='+'{{$sectionInfo->syID}}'+'&semid='+'{{$sectionInfo->semesterID}}',
                                          success:function(data) {
                                                if(data == 1){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: 'Deleted successfully!'
                                                      })
                                                      showsched()
                                                }
                                                
                                          },
                                    })
                              }
                        })
                  })

                  $(document).on('click','.load_subject',function(){

                  

                        var subjData = {
                              'semesterID':'{{$sectionInfo->semesterID}}',
                              'sectionID':'{{$sectionInfo->id}}',
                              'subjectID':$(this).attr('data-id'),
                              'syid':2,
                              '_token': '{{ csrf_token() }}'
                        }

                        $.ajax({
                              type:'POST',
                              url:'/loadSubjetsToSection',
                              data:subjData,
                              success:function(data) {
                                    Toast.fire({
                                          type: 'success',
                                          title: 'Subject added successfully'
                                    })
                                    loadUnloadedSubjects()
                                    showsched()
                              }
                        })
                  })

                 

                  function loadUnloadedSubjects(){

                        var sectionData = {
                              'yearID':'{{$sectionInfo->yearID}}',
                              'courseID':'{{$sectionInfo->courseID}}',
                              'curriculumid':'{{$sectionInfo->curriculumid}}',
                              'semesterID':'{{$sectionInfo->semesterID}}',
                              'sectionID':'{{$sectionInfo->id}}',
                              '_token': '{{ csrf_token() }}'
                        }

                        $.ajax({
                              type:'POST',
                              url:'/unloadedSubjects',
                              data:sectionData,
                              success:function(data) {
                                    $('#unloaded_subjects_holder').empty()
                                    $('#unloaded_subjects_holder').append(data)
                              }
                        })

                  }

                  loadUnloadedSubjects()

                  var schedid;
                 
                  $(document).on('click','.editsched',function(){
                        actionFrom = 2;
                        $('select[name="sched_class"] option:not(:selected)').prop("disabled", false);
                        $('#schedule_modal').modal();
                        $('#schedule_modal .savebutton').removeAttr('onclick')
                        $('#schedule_modal .savebutton').text('UPDATE')
                        $('#schedule_modal .savebutton').addClass('btn-success')
                        $('#schedule_modal .modal-header').removeClass('bg-primary')
                        $('#schedule_modal .modal-header').addClass('bg-success')
                        $('#schedule_modal').modal('hide');

                        schedid = $(this).attr('data-id')
                        selected_subject = $(this).attr('data-subj')
                        selected_sched = $(this).attr('data-schedid')
                        selected_unit = $(this).attr('data-unit') 

                        $.ajax({
                              type:'POST',
                              data: {'_token': '{{ csrf_token() }}'},
                              url:'/collegeschedule?scheddetail=scheddetail&info=info&scheddetailid='+$(this).attr('data-id')+'&sectionid='+'{{$sectionInfo->id}}'+'&syid='+'{{$sectionInfo->syID}}'+'&semid='+'{{$sectionInfo->semesterID}}',
                              success:function(data) {
                                    $('input[type="checkbox"]').each(function(){
                                          $(this).prop( "checked", false );
                                    })

                                    $('input[type="checkbox"]').each(function(){
                                          var dayCheck = $(this)
                                          $.each(data[0].days,function(a,b){
                                                if(dayCheck.val() == b){
                                                      dayCheck.prop( "checked", true );
                                                }
                                          })
                                    })
                                    
                                    $('#schedDetailModal').modal();     
                                    
                                    selected_subject = data[0].subjectID
                                    $('#roomid').val(data[0].roomid).change()
                                    $('#sched_class').val(data[0].scheddetialclass).change()
                                    $('#t_from').val(data[0].stime)
                                    $('#selected_subject').val(data[0].subjDesc)
                                    $('#t_to').val(data[0].etime)
                                    $('select[name="sched_class"] option:not(:selected)').prop("disabled", true);
                         
                              },
                        })
                  })



                  showsched()
                  var subject_list = []

                  function showsched(){

                        $.ajax({
                              type:'POST',
                              url:'/collegeschedule?scheddetail=scheddetail&table=table&sectionid='+'{{$sectionInfo->id}}'+'&syid='+'{{$sectionInfo->syID}}'+'&semid='+'{{$sectionInfo->semesterID}}',
                              data: {'_token': '{{ csrf_token() }}'},
                              success:function(data) {
                                   
                                    $('#college_sched_holder').empty()
                                    $('#college_sched_holder').append(data)
                                    subject_list = []
                                    $('.addsched[data-id="1"]').each(function(a,b){
                                          var subject_list_length = subject_list.filter(x=>x.subjid==$(b).attr('data-subj'))
                                          if(subject_list_length.length == 0){


                                                $('#subject_schedule_subject').append('<option value="'+$(b).attr('data-subj')+'">'+$(b).attr('data-value')+'</option>')
                                                subject_list.push({
                                                      'subjid':$(b).attr('data-subj'),
                                                      'description':$(b).attr('data-value')
                                                })
                                          }
                                    })

                                    data_section_schedulecoding()
                              },
                        })

                  }

                  //section schedulecoding data
                  var sectionid = @json($sectionInfo->id);
                  
                  var selected_section_schedulecoding = null
                
                  function data_section_schedulecoding(){
                        $.ajax({
                              type:'GET',
                              url: '/chairperson/section/schedulecoding/list',
                              data:{
                                    sectionid:sectionid,
                              },
                              success:function(data) {
                                    $('.schedcode_holder').each(function(){
                                          
                                          var temp_subjid = $(this).attr('data-id')
                                          var temp_div = $(this)
                                          temp_data = data.filter(x=>x.subjid == temp_subjid)
                                          html = ''
                                          $.each(temp_data,function(a,b){
                                                html += '<a href="#" class="text-danger delete_section_schedulecoding" data-id="'+b.id+'"><i class="far fa-trash-alt" class="mr-2 "></i></a>  <span class="badge badge-primary"> '+b.code + '</span><br>'
                                                if(b.details.length > 0){
                                                      $.each(b.details,function(c,d){
                                                            html += '<div class="row">'
                                                            html += '<div class="col-md-2 text-right">'+ d.days + '</div><div class="col-md-10">  ' + d.timestart + ' - ' + d.timeend + '</div>'
                                                            html += '</div>'
                                                      })
                                                }else{
                                                      html += 'NO SCHEDULE ADDED<br>'
                                                }
                                          })

                                          temp_div.append(html)
                                    })
                                  
                              }
                        })
                  }

                  //section scheduelcoding data


                  $(document).on('click','.delete_section_schedulecoding',function(){
                        selected_section_schedulecoding = $(this).attr('data-id')
                        delete_section_schedulecoding()
                  })
                  
                  function delete_section_schedulecoding(){
                        $.ajax({
                              type:'GET',
                              url: '/chairperson/section/schedulecoding/delete',
                              data:{
                                    id:selected_section_schedulecoding
                              },
                              success:function(data) {
                                    showsched()
                              }
                        })
                  }

                  //section schedulecoding process
                  $(document).on('click','.select_schedulecoding',function(){
                        selected_schedulecoding = $(this).attr('data-id')
                        create_section_schedulecoding()
                  })

                  function create_section_schedulecoding(){
                        $.ajax({
                              type:'GET',
                              url: '/chairperson/section/schedulecoding/create',
                              data:{
                                    sectionid:sectionid,
                                    schedulecodingid:selected_schedulecoding
                              },
                              success:function(data) {
                                    showsched()
                              }
                        })
                  }
                  //section schedule coding process
             


                  $(document).on('click','.addIns',function(){

                        $('#insertInstructor').modal();
                        $('#insertInstructor .savebutton').removeAttr('onclick')
                        schedid = $(this).attr('data-id')

                  })

                  $('#t_from').on('change',function(){
                        var split_time = ($(this).val()).split(":")
                        stime = new Date();
                        stime.setHours(split_time[0] + selected_unit)
                        stime.setMinutes(split_time[1])
                        $('#t_to').val(stime.getHours() + ':'+stime.getMinutes())
                  })

                  $(document).on('click','.removeIns', function() {
                        Swal.fire({
                              title: 'Remove Instructor?',
                              type: 'info',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Yes, Remove Instructor'
                        })
                        .then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'POST',
                                          url:'/collegeschedule?remove=remove&instructor=instructor&sectionid='+'{{$sectionInfo->id}}'+'&schedid='+$(this).attr('data-id')+'&syid='+'{{$sectionInfo->syID}}'+'&semid='+'{{$sectionInfo->semesterID}}',
                                          data: {'_token': '{{ csrf_token() }}'},
                                          success:function(data) {
                                                if(data == 1){
                                                      showsched();
                                                      $('#insertInstructor').modal('hide');
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: 'Deleted successfully!'
                                                      })
                                                }
                                          
                                          },
                                    })
                              }
                        })
                  })

                  var avail_sched = []

                  $(document).on('click','.addsched',function(){
                        selected_subject = $(this).attr('data-subj')
                        selected_sched = $(this).attr('data-schedid')
                        selected_unit = $(this).attr('data-unit') 
                        actual_subjectid = $(this).attr('data-subjectid')
                        $('#sched_class option:not(:selected)').prop("disabled", true);
                        $('#selected_subject').val($(this).attr('data-value'))
                        actionFrom = 1
                        avail_sched = []
                        $('#schedule_modal').modal()
                        $('#avail_sched').empty()
                        $.ajax({
                              type:'GET',
                              url:'/subject/schedule',
                              data: {
                                    syid:'{{$sectionInfo->syID}}',
                                    semid:'{{$sectionInfo->semesterID}}',
                                    subjectid:actual_subjectid
                              },
                              success:function(data) {
                                    $.each(data,function(a,b){
                                          var check_if_exist = avail_sched.filter(x=>x.description==b[0].description&&x.ftime==b[0].ftime)
                                          if(check_if_exist.length == 0){
                                                if(b[0].description != "" && b[0].sectionid != '{{$sectionInfo->id}}'){
                                                      var temp_room = ""
                                                      var temp_stime = (new Date('2020-01-01T'+b[0].stime)).toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })
                                                      var temp_etime = (new Date('2020-01-01T'+b[0].etime)).toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })
                                                      if(b[0].roomname != null){
                                                            temp_room = b[0].roomname
                                                      }
                                                      avail_sched.push(b[0])
                                                      $('#avail_sched').append('<tr><td><a href="#" class="copy_sched" id="'+b[0].id+'">'+b[0].description+'</a></td><td>'+temp_stime+' - '+temp_etime+'</td><td>'+temp_room+'</td><td></td></tr>')
                                                      
                                                }
                                          }
                                    })
                              },
                        })
                  })

                  $(document).on('click','.copy_sched',function(){
                        var temp_subjid = $(this).attr('id')
                        $('input[type="checkbox"]').prop('checked',false)
                        var found = true
                        $.each(avail_sched,function(a,b){
                              if(b.id == temp_subjid && found){
                                    $('#roomid').val(b.roomid).change()
                                    $('#t_from').val(b.stime)
                                    $('#t_to').val(b.etime)
                                    $.each(b.days_list,function(c,d){
                                          $('input[type="checkbox"][value="'+d+'"]').prop('checked',true)
                                    })
                                    found = false
                              }
                        })
                  })

                  $('#t_from').timepicker();
                  $('#t_to').timepicker();
               
                  var timepicker = $('#t_from').timepicker({
                        format: 'HH.MM'
                  });

            })
      </script>

@endsection

