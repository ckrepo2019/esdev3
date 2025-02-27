
@extends('chairpersonportal.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <link rel="stylesheet" href="{{ asset('assets/css/gijgo.min.css') }}"> 

      <style>
            .select2-container .select2-selection--single {
                  height: 40px;
            }
      </style>

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
      </style>
@endsection



@section('content')

      {{-- @include('collegeportal.pages.forms.generalform')   --}}
      @php
            $sylist = DB::table('sy')->select('id','sydesc','isactive')->get();
            $semlist = DB::table('semester')->select('id','semester','isactive')->get()
                 
      @endphp


      <div class="modal fade" id="create_section" style="display: none;" aria-hidden="true">
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
                                          <label for="">School Year</label>
                                          <select name="section_sy" id="section_sy" class="select2 form-control" disabled>
                                                @foreach ($sylist as $item)
                                                      @if($item->isactive == 1)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                      @endif
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-12 form-group">
                                          <label for="">Semester</label>
                                          <select name="section_semid" id="section_semid" class="select2 form-control" disabled>
                                                @foreach ($semlist as $item)
                                                      @if($item->isactive == 1)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->semester}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->semester}}</option>
                                                      @endif
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-12 form-group">
                                          <label for="">Specification</label>
                                          <select name="section_specification" id="section_specification" class="form-control select2">
                                                <option value="1">Regular</option>
                                                <option value="2">Special</option>
                                          </select>
                                    </div>
                                    <div class="col-md-12 form-group">
                                          <label for="">Course</label>
                                          <select name="section_course" id="section_course" class="select2 form-control"></select>
                                    </div>
                                    <div class="col-md-12 form-group">
                                          <label for="">Curriculum</label>
                                          <select name="section_curriculum" id="section_curriculum" class="select2 form-control"></select>
                                    </div>
                                  
                               
                                    <div class="col-md-12 form-group">
                                          <label for="">Year Level</label>
                                          <select name="year_level" id="year_level" class="form-control select2">
                                                @foreach (DB::table('gradelevel')->where('acadprogid',6)->select('id','levelname')->get() as $item)
                                                       <option value="{{$item->id}}">{{$item->levelname}}</option>
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-12 form-group">
                                          <label for="">Section Name</label>
                                          <input id="section_name" class="form-control">
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button class="btn btn-primary" id="create_sections">Create Sections</button>
                        </div>
                  </div>
            </div>
      </div>

      <div class="modal fade" id="add_sched_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                        <div class="modal-header bg-primary">
                              <h4 class="modal-title">Create Section</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                              </button>
                        </div>
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-4 form-group">
                                          <label for="">Course</label>
                                          <select name="sched_course" id="sched_course" class="select2 form-control">
                                          </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                          <label for="">Subject</label>
                                          <select name="sched_subject" id="sched_subject" class="select2 form-control">
                                          </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                          <label for="">Section</label>
                                          <select name="sched_section" id="sched_section" class="select2 form-control">
                                          </select>
                                    </div>
                              </div>
                        </div>
                        <div class="row ">
                              <div class="col-md-12" > 
                                    <div class="table-responsive"  id="schedule_table">

                                    </div>
                              </div>
                        </div>
                        
                  </div>
            </div>
      </div>

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
                                          <label for="">SECTION</label>
                                          <select name="selected_section" id="selected_section" class="select2 form-control" disabled>
                                          </select>
                                    </div>
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
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button class="btn btn-primary savebutton" id="create_schedule">Create Schedule</button>
                        </div>
                  </div>
            </div>
      </div>

      <section class="content-header">
            <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <h4>SECTIONS</h4>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item"><a href="/principalPortalSchedule">Sections</a></li>
                </ol>
                </div>
            </div>
            </div>
      </section>


      <section class="content">
            <div class="row">
                  <div class="col-md-12">
                        <div class="card">
                              <div class="card-header bg-primary p-1"></div>
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-3">
                                              <label for="">School Year</label>
                                              <select name="syid" id="syid" class="form-control select2">
                                                @foreach ($sylist as $item)
                                                      @if($item->isactive == 1)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                      @endif
                                                @endforeach
                                              </select>
                                          </div>
                                          <div class="col-md-3">
                                              <label for="">Semester</label>
                                              <select name="semester" id="semester" class="form-control select2">
                                                      @foreach ($semlist as $item)
                                                            @if($item->isactive == 1)
                                                                  <option value="{{$item->id}}" selected="selected">{{$item->semester}}</option>
                                                            @else
                                                                  <option value="{{$item->id}}">{{$item->semester}}</option>
                                                            @endif
                                                      @endforeach
                                              </select>
                                          </div>
                                    </div>
                                    <div class="row mt-3">
                                          <div class="col-md-4">
                                                <label for="">Course</label>
                                                <select name="courses" id="courses" class="form-control select2">
                                                      <option value="">ALL</option>
                                                </select>
                                          </div>
                                          <div class="col-md-4">
                                                <label for="">Curriculum</label>
                                                <select name="curriculum_filter" id="curriculum_filter" class="form-control select2">
                                                      <option value="">ALL</option>
                                                </select>
                                          </div>
                                          <div class="col-md-4">
                                              <label for="">Specification</label>
                                              <select name="specification" id="specification" class="form-control select2">
                                                      <option value="1">Regular</option>
                                                      <option value="2">Special</option>
                                              </select>
                                          </div>
                                    </div>
                                    <div class="row mt-3">
                                          <div class="col-md-4">
                                                <button class="btn btn-primary" id="filter_sched"><i class="fas fa-filter"></i> FILTER</button>
                                                
                                          </div>
                                          <div class="col-md-3">

                                          </div>
                                          <div class="col-md-5">
                                                <button class="btn btn-primary float-right ml-1 mr-1" id="add_schedule"> <i class="fas fa-plus-square"></i> <b>ADD SCHEDULE</b></button>
                                                <button class="btn btn-primary float-right ml-1 mr-1" id="createSection"> <i class="fas fa-plus-square"></i> <b>CREATE SECTION</b></button>
                                          </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                          <div class="col-md-12">
                                          <table class="table table-bordered table-head-fixed nowrap display table-sm p-0" style="width:100%" id="section_table">
                                                <thead>
                                                      <tr>
                                                            <th width="25%">SECTION NAME</th>
                                                            <th width="20%">YEAR LEVEL</th>
                                                            <th width="15%">COURSE</th>
                                                            <th width="30%">CURRICULUM</th>
                                                            <th width="10%" class="text-center">ENROLLED</th>
                                                      </tr>
                                                </thead>
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

      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('assets/scripts/gijgo.min.js') }}"></script>



      <script>
            $(document).ready(function(){

                  var sections
                  var subject_list

                  $('.select2').select2()

                  //adding schedule

                  $(document).on('click','#add_schedule',function(){
                        $('#add_sched_modal').modal()
                  })

                  $(document).on('change','#sched_course',function(){
                        var temp_courseid = $(this).val()
                        loadsubject(temp_courseid)
                  })

                  $(document).on('change','#sched_subject',function(){
                        var temp_subject = $(this).val()
                        var subj_curriculum = subject_list.filter(x=>x.id == temp_subject)
                        var temp_sections = []
                        if(subj_curriculum.length > 0){
                              var temp_sections = sections.filter(x=>x.curriculumid == subj_curriculum[0].curriculumid )
                        }
                        $('#sched_section').empty()
                        $('#sched_section').append('<option value="">ALL</option>')
                        $('#selected_section').append('<option value="">SELECT A SECTION</option>')
                        $.each(temp_sections,function(a,b){
                              $('#sched_section').append('<option value="'+b.id+'">'+b.sectionDesc+'</option>')
                              $('#selected_section').append('<option value="'+b.id+'">'+b.sectionDesc+'</option>')
                              
                        })
                        loadsched()
                  })


                  $(document).on('change','#sched_section',function(){
                        loadsched()
                  })

                  function loadsched(){

                        $.ajax({
                              type:'GET',
                              url:"/subject/schedule/blade",
                              data:{
                                    syid:$('#syid').val(),
                                    semid:$('#semester').val(),
                                    subjectid:$('#sched_subject').val(),
                                    sectionid:$('#sched_section').val()
                              },
                              success:function(data) {
                                    $('#schedule_table').empty()
                                    $('#schedule_table').append(data)
                              },
                        })
                  }


                  

                  function loadsubject(courseid){
                        $.ajax({
                              type:'GET',
                              url:"/college/subjects",
                              data:{
                                    courseid:courseid
                              },
                              success:function(data) {
                                    subject_list = data
                                    $('#sched_subject').empty()
                                    $('#sched_subject').append('<option value="">SELECT SUBJECT</option>')
                                    $.each(data,function(a,b){
                                          $('#sched_subject').append('<option value="'+b.id+'">'+b.subjDesc+'</option>')
                                    })
                              },
                        })
                  }

                  var schedid;
                  var actionFrom;

                  $(document).on('click','.removesched',function(){

                        var selected_section = $(this).attr('data-sectionid')

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
                                          url:'/collegeschedule?scheddetail=scheddetail&remove=remove&scheddetailid='+$(this).attr('data-id')+'&sectionid='+selected_section+'&syid='+$('#syid').val()+'&semid='+$('#semester').val(),
                                          success:function(data) {
                                                if(data == 1){
                                                      Swal.fire({
                                                            type: 'success',
                                                            title: 'Deleted successfully!',
                                                      })
                                                      loadsched()
                                                }
                                                
                                          },
                                    })
                              }
                        })
                  })


                  $(document).on('click','#create_schedule',function(){

                        var selected_days = []

                        $('.day_list').each(function(a,b){
                              if($(this).prop('checked') == true){
                                    selected_days.push($(this).val())      
                              }
                        })

                        var sectionname = $( "#selected_section option:selected" ).text()
                        sectionname = sectionname.toLowerCase().replace(/\s+/g, '-')

                        $.ajax({
                              type:'GET',
                              url:"/chairperson/scheddetail/create/"+sectionname,
                              data:{
                                    syid:$('#syid').val(),
                                    semid:$('#semester').val(),
                                    subjid:selected_subject,
                                    roomid:$('#roomid').val(),
                                    t_to:$('#t_to').val(),
                                    t_from:$('#t_from').val(),
                                    scheddetialclass:$('#sched_class').val(),
                                    day:selected_days,
                                    sectionid:selected_section,
                                    schedid:selected_sched
                              },
                              success:function(data) {
                                    if(actionFrom == 1){
                                          Swal.fire({
                                                type: 'success',
                                                title: 'Created Successfully!',
                                          })
                                          loadsched()
                                    }
                                    else if(actionFrom == 2){
                                          Swal.fire({
                                                type: 'success',
                                                title: 'Updated Successfully!',
                                          })
                                          loadsched()
                                    }
                              
                              },
                        })

                  })

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
                             url:'/collegeschedule?scheddetail=scheddetail&info=info&scheddetailid='+$(this).attr('data-id')+'&sectionid='+selected_section+'&syid='+$('#syid').val()+'&semid='+$('#semester').val(),
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

                  $(document).on('click','.addsched',function(){
                        selected_subject = $(this).attr('data-subj')
                        selected_sched = $(this).attr('data-schedid')
                        selected_unit = $(this).attr('data-unit') 
                        selected_section = $(this).attr('data-sectionid') 
                        $('#selected_section').val(selected_section).change()
                        $('#sched_class option:not(:selected)').prop("disabled", true);
                        $('#selected_subject').val($(this).attr('data-value'))
                        actionFrom = 1
                        $('#schedule_modal').modal()
                  })

                  $(document).on('click','.removeIns', function() {
                        var schedid = $(this).attr('data-id')
                        var sectionid = $(this).attr('data-sectionid')
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
                                          url:'/collegeschedule?remove=remove&instructor=instructor&sectionid='+sectionid+'&schedid='+schedid+'&syid='+$('#syid').val()+'&semid='+$('#semester').val(),
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

                  
                  $('#t_from').timepicker();
                  $('#t_to').timepicker();
               
                  var timepicker = $('#t_from').timepicker({
                        format: 'HH.MM'
                  });
                 
                  // $(document).on('click','.editsched',function(){
                  //       actionFrom = 2;
                  //       $('select[name="sched_class"] option:not(:selected)').prop("disabled", false);
                  //       $('#schedule_modal').modal();
                  //       $('#schedule_modal .savebutton').removeAttr('onclick')
                  //       $('#schedule_modal .savebutton').text('UPDATE')
                  //       $('#schedule_modal .savebutton').addClass('btn-success')
                  //       $('#schedule_modal .modal-header').removeClass('bg-primary')
                  //       $('#schedule_modal .modal-header').addClass('bg-success')
                  //       $('#schedule_modal').modal('hide');

                  //       schedid = $(this).attr('data-id')

                  //       $.ajax({
                  //             type:'POST',
                  //             data: {'_token': '{{ csrf_token() }}'},
                  //             url:'/collegeschedule?scheddetail=scheddetail&info=info&scheddetailid='+$(this).attr('data-id')+'&sectionid='+'&syid='+$('#syid').val()+'&semid='+$('#semester').val(),
                  //             success:function(data) {
                  //                   console.log(data)
                  //                   $('input[type="checkbox"]').each(function(){
                  //                         $(this).prop( "checked", false );
                  //                   })

                  //                   $('input[type="checkbox"]').each(function(){
                  //                         var dayCheck = $(this)
                  //                         $.each(data[0].days,function(a,b){
                  //                               if(dayCheck.val() == b){
                  //                                     dayCheck.prop( "checked", true );
                  //                               }
                  //                         })
                  //                   })
                                    
                  //                   $('#schedDetailModal').modal();     
                                    
                  //                   selected_subject = data[0].subjectID
                  //                   $('#roomid').val(data[0].roomid).change()
                  //                   $('#sched_class').val(data[0].scheddetialclass).change()
                  //                   $('#t_from').val(data[0].stime)
                  //                   $('#selected_subject').val(data[0].subjDesc)
                  //                   $('#t_to').val(data[0].etime)
                  //                   $('select[name="sched_class"] option:not(:selected)').prop("disabled", true);
                         
                  //             },
                  //       })
                       
                  // })


                  //adding schedule

                  $(document).on('click','#filter_sched',function(){
                        if(selected_sy != $('#syid').val() || selected_sem != $('#semester').val()){
                              load_sections()
                             
                        }
                        else{
                              var temp_sections = sections

                              if($('#courses').val() != ""){
                                    temp_sections = temp_sections.filter(x=>x.courseID == $('#courses').val())
                              }
                             

                              if($('#curriculum_filter').val() != ""){
                                    temp_sections = temp_sections.filter(x=>x.curriculumid == $('#curriculum_filter').val())
                              }
                             
                            
                              section_datatable(temp_sections)
                        }
                  })

                  load_sections()
                  get_courses()
                  get_courses_curriculum()


                  var selected_sy
                  var selected_sem
                  var curriculum_list
                  var course_list

                  $(document).on('change','#section_specification',function(){
                        $('#section_curriculum').removeAttr('disabled','disabled')
                        $('#year_level').removeAttr('disabled','disabled')
                        if($(this).val() == 2){
                              $('#section_curriculum').attr('disabled','disabled')
                              $('#year_level').attr('disabled','disabled')
                              $('#section_curriculum').empty()
                        }
                  })
                 
                  function load_sections(){

                        selected_sy = $('#syid').val()
                        selected_sem = $('#semester').val()

                        $.ajax({
                              type:'GET',
                              url:'/college/chairpseron/sections',
                              data:{
                                    syid:selected_sy,
                                    semid:selected_sem
                              },
                              success:function(data) {
                                    $('#createSection').removeAttr('disabled')
                                    sections = data
                                    section_datatable(sections)
                              }
                        })
                  }

                  function get_courses(){
                        $('#courses').empty()
                        $('#courses').append('<option value="">ALL</option>')
                        $('#section_course').append('<option value="">SELECT COURSE</option>')
                        $('#sched_course').append('<option value="">SELECT COURSE</option>')
                        $.ajax({
                              type:'GET',
                              url:'/chairperson/courses/',
                              success:function(data) {
                                    course_list = data
                                    $.each(data,function(a,b){
                                          $('#sched_course').append('<option value="'+b.id+'">'+b.courseDesc+'</option>')
                                          $('#courses').append('<option value="'+b.id+'">'+b.courseDesc+'</option>')
                                          $('#section_course').append('<option value="'+b.id+'">'+b.courseDesc+'</option>')
                                    })
                              }
                        })
                  }

                  $(document).on('change','#section_course',function(){
                        if($('#section_specification').val() == 1){
                              var temp_curriculum = curriculum_list
                              if($('#section_course').val() != ""){
                                    var temp_curriculum = curriculum_list.filter(x=>x.courseID == $('#section_course').val())
                              }
                              $('#section_curriculum').empty()
                              $('#section_curriculum').append('<option value="">SELECT CURRICULUM</option>')
                              
                              $.each(temp_curriculum,function(a,b){
                                    selected = ""
                                    if(b.isactive == 1 && $('#section_course').val() != ""){
                                          selected="selected"
                                    } 
                                    $('#section_curriculum').append('<option '+selected+' value="'+b.id+'">'+b.curriculumname+'</option>')
                              })
                        }
                  })

                  $(document).on('change','#courses',function(){
                        var temp_curriculum = curriculum_list
                        if($('#courses').val() != ""){
                              var temp_curriculum = curriculum_list.filter(x=>x.courseID == $('#courses').val())
                        }
                        $('#curriculum_filter').empty()
                        $('#curriculum_filter').append('<option value="">SELECT CURRICULUM</option>')
                     
                        $.each(temp_curriculum,function(a,b){
                              selected = ""
                              if(b.isactive == 1 && $('#courses').val() != ""){
                                    selected="selected"
                              } 
                              $('#curriculum_filter').append('<option '+selected+' value="'+b.id+'">'+b.curriculumname+'</option>')
                        })
                  })

                  function get_courses_curriculum(){
                        $('#curriculum_filter').empty()
                        $('#curriculum_filter').append('<option value="">ALL</option>')
                        $.ajax({
                              type:'GET',
                              url:'/chairperson/courses/curriculum',
                              success:function(data) {
                                    curriculum_list = data
                                    $.each(data,function(a,b){
                                          var selected = ''
                                          
                                          $('#curriculum_filter').append('<option '+selected+' value="'+b.id+'">'+b.curriculumname+'</option>')
                                    })
                              }
                        })
                  }

                  $(document).on('change','#syid, #semester',function(){
                        $('#createSection').attr('disabled','disabled')
                  })

                  
                  function section_datatable(data){
                        $("#section_table").DataTable({
                              destroy: true,
                              data:data,
                              "scrollX": true,
                              "order": [[ 1, "asc" ]],
                              columns: [
                                          { "data": "sectionDesc" },
                                          { "data": "levelname" },
                                          { "data": "courseabrv" },
                                          { "data": "curriculumname" },
                                          { "data": "enrolled_stud" },
                                         
                                    ],
                              "columnDefs": [
                                    {  "targets": 0,
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var slug_string = rowData.sectionDesc.toLowerCase().replace(/\s+/g, '-')
                                                $(td)[0].innerHTML = '<a href="/chairperson/sections/show/'+slug_string+'?sectcode='+rowData.id+'" target="_blank">'+rowData.sectionDesc+'</a>'                                      
                                          } 
                                    },
                                 
                                    {  "targets": 4,
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('text-center')                                                
                                          } 
                                    }
                              ]
                        });
                  }

                  $('#createSection').click(function(){
                        $('#section_sy').val($('#syid').val()).change()
                        $('#section_semid').val($('#semester').val()).change()
                        $('#create_section').modal()
                  })

                  $('#create_sections').click(function(){

                        var courseabrv = course_list.filter(x=>x.id == $('#section_course').val())
                        var sectionExist = sections.filter(x=>x.sectionname == $('#section_name').val())
                        var valid_input = true
                        var message

                        if($('#section_course').val() == "" && $('#section_specification').val() == 1){
                              message = 'Please select a course!'
                              valid_input = false
                        }else if($('#section_curriculum').val() == "" && $('#section_specification').val() == 1){
                              message = 'Please select a curriculum!'
                              valid_input = false
                        }else if($('#section_specification').val() == ""){
                              message = 'Please select a specification!'
                              valid_input = false
                        }
                        else if($('#year_level').val() == ""){
                              message = 'Please select a grade level!'
                              valid_input = false
                        }
                        else if($('#section_name').val() == ""){
                              message = 'Specify a section name!'
                              valid_input = false
                        }
                        else if(sectionExist.length != 0){
                              message = 'Section already Exist!'
                              valid_input = false
                        }

                        if(!valid_input){
                              Swal.fire({
                                    type: 'warning',
                                    title: message,
                              })
                              return false
                        }
                    
                        $.ajax({
                              type:'GET',
                              url:'/chairperson/sections/createv2',
                              data:{
                                    syid:$('#section_sy').val(),
                                    semid:$('#section_semid').val(),
                                    levelid:$('#year_level').val(),
                                    courseid:$('#section_course').val(),
                                    curriculumid:$('#section_curriculum').val(),
                                    specification:$('#section_specification').val(),
                                    sectionname:$('#section_name').val(),
                              },
                              success:function(data) {

                                    var valid_input = true

                                    if( ( $('#section_sy').val() != $('#syid').val() ) && $('#syid').val() != ""){
                                          valid_input = false
                                    }

                                    if( ( $('#section_semid').val() != $('#semester').val() ) && $('#semester').val() != ""){
                                          valid_input = false
                                    }

                                    if( ( $('#section_course').val() != $('#courses').val() ) && $('#courses').val() != ""){
                                          valid_input = false
                                    }

                                    if( ( $('#section_curriculum').val() != $('#curriculum_filter').val() ) && $('#curriculum_filter').val() != ""){
                                          valid_input = false
                                    }

                                    if(data[0].status == 1 && valid_input){

                                          Swal.fire({
                                                title: 'Created Successfully!',
                                                type: 'success',
                                          })
                                          
                                          var level_name = $('#year_level option:selected').text();

                                          if($('#section_curriculum').val() == 2){
                                                level_name = null
                                          }

                                          sections.push({
                                                'levelname':level_name,
                                                'courseID':$('#section_course').val(),
                                                'courseabrv':courseabrv[0].courseabrv,
                                                'curriculumid':$('#section_curriculum').val(),
                                                'curriculumname':$("#section_curriculum option:selected" ).text(),
                                                'enrolled_stud':0,
                                                'id':data[0].data,
                                                'sectionDesc':$('#section_name').val()
                                          })

                                          $('#create_section').modal('hide')
                                          $('#section_specification').val(1)
                                          $('#section_course').val("").change()
                                          $('#section_curriculum option').remove()
                                          $('#section_curriculum').append('<option value="">SELECT CURRRICULUM</option>')
                                          $('#year_level').val("").change()
                                          $('#section_name').val("")

                                          section_datatable(sections)
                                    }
                              }
                        })
                        
                  })
                  

                  

                  
            })
      </script>

@endsection
{{-- @section('footerjavascript')
      <script>
            $(document).ready(function(){
               
                  $('#courseID option').each(function(){
                      

                        if($(this).attr('value') != null){
                              $(this).attr('value',$(this)[0].innerText.toLowerCase().replace(/\s+/g, '-'))
                        }
                    
                  })

                  $(document).on('change','#courseID',function(){
                        

                        $.ajax( {
                              url: '/course?course='+$(this).val()+'&info=info',
                              type: 'GET',
                              success:function(data){

                                     $.ajax( {
                                          url: '/curriculum?courseid='+data[0].id+'&info=info',
                                          type: 'GET',
                                          success:function(data){

                                                $('#curriculum').empty()
                                                $('#curriculum').append('<option value="">SELECT CURRICULUM</option>')

                                                console.log(data)

                                                $.each(data,function(a,b){

                                                      if(b.isactive == 1){
                                                            $('#curriculum').append('<option style="color:green" value="'+b.id+'" selected>'+b.curriculumname+'</option>')
                                                      }
                                                      else{
                                                            $('#curriculum').append('<option style="color:red" value="'+b.id+'">'+b.curriculumname+'</option>')
                                                      }
                                                     

                                                })
                                                
                                          }
                                    
                                    });
                                    
                              }
                        
                        });

                       

                  })



                  @if ($errors->any())
                        $('#'+'{{ $modalInfo->modalName }}').modal('show');
                  @endif
                  })

                  loadSections()

                  var selectedSection;
                  var actiontype;

                  $(document).on('click','.udpateSection',function(){

                        selectedSection = $(this).attr('data-id');

                        $.ajax({
                              type:'POST',
                              data: {'_token': '{{ csrf_token() }}'},
                              url:'/collegesections?info=info&section='+$(this).attr('data-id'),
                              success:function(data){

                                    $('#schedDetailModal').modal()
                                    $('select[name="courseID"]').val(data[0].courseDesc.toLowerCase().replace(/ +/g,'-'))
                                    $('select[name="yearID"]').val(data[0].yearID)

                                    $('select[name="courseID"]').attr('disabled','disabled')
                                    $('select[name="yearID"]').attr('disabled','disabled')

                                    $('input[name="sectionDesc"]').val(data[0].sectionDesc)

                                    $('#schedDetailModal .savebutton').removeClass('btn-primary')
                                    $('#schedDetailModal .savebutton').addClass('btn-success')
                                    $('#schedDetailModal .savebutton').text('UPDATE')
                                    $('#schedDetailModal .savebutton').removeAttr('type')
                                    $('#schedDetailModal .savebutton').removeAttr('onclick')

                                    $('#schedDetailModal .modal-header').removeClass('bg-primary')
                                    $('#schedDetailModal .modal-header').addClass('bg-success')

                                    $('#schedDetailModalForm').removeAttr('action')

                                   
                              }
                            
                        })

                        actiontype = 1
                  })

                  $(document).on('click','#createSection',function(){
                        $('#schedDetailModal').modal()
                        $('#schedDetailModal .savebutton').addClass('btn-primary')
                        $('#schedDetailModal .savebutton').removeClass('btn-success')
                        $('#schedDetailModal .savebutton').text('CREATE')
                        $('#schedDetailModal .savebutton').removeAttr('type')
                        $('#schedDetailModal .savebutton').removeAttr('onclick')
                        $('#schedDetailModalForm').removeAttr('action')
                        $('#schedDetailModal .modal-header').addClass('bg-primary')
                        $('#schedDetailModal .modal-header').removeClass('bg-success')
                        $('#schedDetailModalForm')[0].reset()
                        $('select[name="courseID"]').removeAttr('disabled')
                        $('select[name="yearID"]').removeAttr('disabled')
                        actiontype = 2
                  })

                  $(document).on('click','.removeSection',function(){
                        Swal.fire({
                              title: 'Remove Section?',
                              type: 'info',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Yes, Remove Section'
                        })
                        .then((result) => {

                              if (result.value) {
                                    $.ajax({
                                          type:'POST',
                                          data: {'_token': '{{ csrf_token() }}'},
                                          url:'/collegesections?remove=remove&section='+$(this).attr('data-id'),
                                          success:function(data){
                                                Swal.fire({
                                                      title: 'Removed Successfully!',
                                                      type: 'success',
                                                      showConfirmButton: false,
                                                      timer: 1500
                                                })
                                                loadSections()
                                          }
                                    })
                              }

                        })

                        
                  })


                  $('#schedDetailModalForm').submit(function(e){

                        if(actiontype == 1){

                              var url = '/collegesections?update=update&section='+selectedSection+'&course='+$('select[name="courseID"]').val()+'&gradelevel='+$('select[name="yearID"]').val()+'&sectionDesc='+$('input[name="sectionDesc"]').val()+'&curriculum='+$('#curriculum').val()

                        }
                        else if(actiontype == 2){

                              var url = '/collegesections?create=create&course='+$('select[name="courseID"]').val()+'&gradelevel='+$('select[name="yearID"]').val()+'&sectionDesc='+$('input[name="sectionDesc"]').val()+'&curriculum='+$('#curriculum').val()
                              
                        }

                        $.ajax({
                              type:'POST',
                              data: {'_token': '{{ csrf_token() }}'},
                              url:url,
                              success:function(data){

                                    if(data[0].status == 0){

                                          $.each(data[0].errors,function(a,b){
                                                $('#'+a).addClass('is-invalid')
                                                $('#'+a+'Error strong').text(b)
                                          })

                                    }
                                    loadSections()
                              }
                        })

                        e.preventDefault();

                  })

                  function loadSections(){

                        $.ajax({
                              type:'POST',
                              data: {'_token': '{{ csrf_token() }}'},
                              url:'/collegesections?table=table',
                              success:function(data){
                                    $('#sectionTableHolder').empty()
                                    $('#sectionTableHolder').append(data)
                              }
                        })

                  }
            
      </script>
@endsection --}}

