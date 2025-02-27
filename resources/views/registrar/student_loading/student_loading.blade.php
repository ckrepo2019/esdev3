
@extends('registrar.layouts.app')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
      <style>
            .select2-selection{
            height: calc(2.25rem + 2px) !important;
            }
      </style>
      <style>
          
            #section_holder td{
                  cursor: pointer;
            }
            .font-sm{
                  font-size: 13px;
            }
      </style>
@endsection


@section('modalSection')
      
@endsection

@section('content') 

      @php
            $sylist = DB::table('sy')->select('id','sydesc','isactive')->get();
            $semlist = DB::table('semester')->select('id','semester','isactive')->where('deleted',0)->get();
      @endphp


      <div class="modal fade" id="student_list" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Student Name</label>
                                          <select name="" id="student_list_select" class="form-control select2">
                                          </select>
                                    </div>
                              
                              </div>
                              <div class="row">
                                    <div class="col-md-4">
                                          <button class="btn btn-primary btn-sm" id="select_student_button">SELECT</button>
                                    </div>
                                    <div class="col-md-8 text-right">
                                          <button class="btn btn-default btn-sm" data-dismiss="modal" aria-label="Close">CLOSE</button>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>


        <div class="modal fade" id="shift_course_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                  <div class="col-md-12 form-group">
                                        <label for="">Course</label>
                                        <select name="s_course" id="s_course" class="form-control select2">

                                        </select>
                                  </div>
                                  <div class="col-md-12 form-group">
                                        <label for="">Curriculum</label>
                                        <select name="s_curriculum" id="s_curriculum" class="form-control select2">

                                        </select>
                                  </div>
                                  <div class="row col-md-12">
                                    <div class="col-md-8">
                                          <button class="btn btn-primary btn-sm" id="proccess_shift_course">CHANGE STUDENT COURSE</button>
                                    </div>
                                    <div class="col-md-4 text-right">
                                          <button class="btn btn-default btn-sm" data-dismiss="modal" aria-label="Close">CLOSE</button>
                                    </div>
                              </div>
                            </div>
                        </div>
                  </div>
            </div>
      </div>


      <div class="modal fade" id="pre_enrolled_student_list" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">Pre-Enrolled Student Name</label>
                                          <select name="" id="pre_enrolled_student_list_select" class="form-control select2">
                                          </select>
                                    </div>
                              
                              </div>
                              <div class="row">
                                    <div class="col-md-4">
                                          <button class="btn btn-primary btn-sm" id="select_pre_enrolled_student_button">SELECT</button>
                                    </div>
                                    <div class="col-md-8 text-right">
                                          <button class="btn btn-default btn-sm" data-dismiss="modal" aria-label="Close">CLOSE</button>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>

      <div class="modal fade" id="section_schedule_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-3">
                                          <strong>Section</strong>
                                          <p class="text-muted" id="selected_sectionname">--</p>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12 table-responsive"  style="height: 300px;">
                                          <table class="table table-sm table-head-fixed" style="font-size:13px !important">
                                                <thead>
                                                      <tr>
                                                            <th  width="5%"></th>
                                                            <th  width="15%">Code</th>
                                                            <th  width="35%">Subject</th>
                                                            <th  width="10%" class="text-center">Units</th>
                                                            <th  width="20%">Schedule</th>
                                                            <th  width="10%">Instructor</th>
                                                      </tr>
                                                </thead>
                                                <tbody id="section_schedule">

                                                </tbody>
                                          </table>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-4">
                                          <button class="btn btn-primary btn-sm" class="set_student_id" >SET AS STUDENT SECTION</button>
                                    </div>
                                    <div class="col-md-8 text-right">
                                          <button class="btn btn-default btn-sm" data-dismiss="modal" aria-label="Close">CLOSE</button>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
      <div class="modal fade" id="search_schedule_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-4">
                                          <label>Propectus Subjects</label>
                                          <select id="select_subject_prospectus" class="select2 form-control">
                                          </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                          <label>All Subjects</label>
                                          <select id="select_subject_all" class="select2 form-control">
                                                <option value="">SELECT SUBJECT</option>
                                                @foreach (DB::table('college_subjects')->where('deleted',0)->select('id','subjDesc')->get() as $item)
                                                      <option value="{{$item->id}}">{{$item->subjDesc}}</option>
                                                @endforeach
                                          </select>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12 table-responsive"  style="height: 300px;">
                                          <table class="table table-sm table-head-fixed" style="font-size:13px !important">
                                                <thead>
                                                      <tr>
                                                            <th  width="5%"></th>
                                                            <th  width="10%">Section</th>
                                                            <th  width="15%">Code</th>
                                                            <th  width="25%">Subject</th>
                                                            <th  width="10%" class="text-center">Units</th>
                                                            <th  width="20%">Schedule</th>
                                                            <th  width="10%">Instructor</th>
                                                      </tr>
                                                </thead>
                                                <tbody id="search_schedule_table">

                                                </tbody>
                                          </table>
                                    </div>
                              </div>
                              <div class="row mt-2">
                                    <div class="col-md-12 text-right">
                                          <button class="btn btn-default btn-sm" data-dismiss="modal" aria-label="Close">CLOSE</button>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>

    <section class="content-header pt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <button class="btn btn-primary btn-sm" id="reload_data"><i class="fas fa-sync-alt"></i> RELOAD</button>
                </div>
                <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item active">College Schedule</li>
                        </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content pt-0">
        <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-header">
                        <div class="row">
                              <div class="col-md-2">
                                    <button class="btn btn-primary btn-sm" id="view_students_button"><i class="fas fa-users"></i> SELECT STUDENT</button>
                              </div>
                              <div class="col-md-2">
                                    <button class="btn btn-sm btn-primary" id="shift_student_course" disabled="disabled"><i class="fas fa-sync-alt"></i> CHANGE COURSE</button>
                              </div>
                              <div class="col-md-2"></div>
                              <div class="col-md-6 text-right">
                                    <button class="btn btn-info btn-sm" id="view_pre_enrolled_students_button"><i class="fas fa-users"></i> Pre-Enrolled Students ( <span id="pre_enrolled_count"></span> )</button>
                              </div>
                        </div>
                       
                  </div>
                  <div class="card-body">
                        <div class="row" style="font-size:13px !important">
                              <div class="col-md-3">
                                    <strong>Name</strong>
                                    <p class="text-muted" id="student_name">--</p>
                              </div>
                              <div class="col-md-3">
                                    <strong>STUDENT ID</strong>
                                    <p class="text-muted" id="student_sid">--</p>
                              </div>
                              <div class="col-md-3">
                                    <strong>Grade Level</strong>
                                    <p class="text-muted" id="student_gradelevel">--</p>
                              </div>
                              <div class="col-md-3">
                                    <strong>Enrollment Status</strong>
                                    <p class="text-muted" id="student_enrollment">--</p>
                              </div>
                        </div>
                         <div class="row" style="font-size:13px !important">
                              <div class="col-md-4">
                                    <strong>Assigned Section</strong>
                                    <p class="text-muted" id="student_assigned_section">--</p>
                              </div>
                              <div class="col-md-4">
                                    <strong>Enrolled Section</strong>
                                    <p class="text-muted" id="student_enrolled_section">--</p>
                              </div>
                        </div>
                        <div class="row" style="font-size:13px !important">
                              <div class="col-md-8">
                                    <strong>Course</strong>
                                    <p class="text-muted" id="student_course">--</p>
                              </div>
                              <div class="col-md-4">
                                    <strong>Curriculum</strong>
                                    <p class="text-muted" id="student_curriculum">--</p>
                              </div>
                           
                        </div>
                        <hr>
                        <div class="row">
                              <div class="col-md-4 form-group">
                                    <label for="">School Year</label>
                                    <select name="" id="school_year" class="form-control form-control-sm" style="font-size:13px !important">
                                          @foreach ($sylist as $item)
                                                <option value="{{$item->id}}" {{$item->isactive == 1?'selected="selected':''}}>{{$item->sydesc}}</option>
                                          @endforeach
                                    </select>
                              </div>
                              <div class="col-md-4 form-group">
                                    <label for="">Semester</label>
                                    <select name="" id="semester" class="form-control form-control-sm">
                                          @foreach ($semlist as $item)
                                                <option value="{{$item->id}}" {{$item->isactive == 1?'selected="selected':''}}>{{$item->semester}}</option>
                                          @endforeach
                                    </select>
                              </div>
                        </div>
                        <hr class="m-1">
                        <div class="row pt-2 pb-2">
                              <div class="col-md-8">
                                    <label for="">STUDENT SCHEDULE</label>
                              </div>
                              <div class="col-md-4 text-right">
                                    <button class="btn btn-sm btn-primary" id="search_schedule_button"><i class="fas fa-search-plus"></i> SEARCH SCHEDULE</button>
                              </div>
                              {{-- <div class="col-md-2">
                                    <button class="btn btn-sm btn-danger btn-block"><i class="fas fa-trash"></i> REMOVE ALL</button>
                              </div> --}}
                        </div>


                        <div class="row">
                              <div class="col-md-2 table-responsive border-right h-100"  style="font-size:13px !important">
                                     <div class="row">
                                          <div class="col-md-12 table-responsive" style="height: 250px;">
                                                <table class="table table-sm table-head-fixed" >
                                                      <thead>
                                                            <tr>
                                                                  <th>Section List</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="section_holder">
                                                           
                                                      </tbody>
                                                </table>
                                          </div>
                                          <hr>
                                          <div class="col-md-12">
                                                <strong>Enrollment Status</strong>
                                                <p class="text-muted" id="e_status">--</p>
                                          </div>
                                          <div class="col-md-12">
                                                <strong>Course</strong>
                                                <p class="text-muted" id="e_course">--</p>
                                          </div>
                                          <div class="col-md-12">
                                                <strong>Section</strong>
                                                <p class="text-muted" id="e_section">--</p>
                                          </div>
                                          <div class="col-md-12">
                                                <strong>Grade Level</strong>
                                                <p class="text-muted" id="e_gradelevel">--</p>
                                          </div>
                                          <div class="col-md-12">
                                                <strong>Curriculum</strong>
                                                <p class="text-muted" id="e_curriculum">--</p>
                                          </div>
                                    </div>
                              </div>
                              <div class="col-md-10">
                                    <div class="row">
                                          <div class="col-md-12 table-responsive"  style="height: 400px;">
                                                <table class="table table-sm table-head-fixed" style="font-size:13px !important">
                                                      <thead>
                                                            <tr>
                                                                  <th colspan="6">STUDENT SCHEDULE</th>
                                                            </tr>
                                                            <tr>
                                                                  <th  width="10%">Section</th>
                                                                  <th  width="10%">Code</th>
                                                                  <th  width="35%">Subject</th>
                                                                  <th  width="10%" class="text-center">Units</th>
                                                                  <th  width="20%">Schedule</th>
                                                                  <th  width="15%">Instructor</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="subject_list">
                                                           
                                                      </tbody>
                                                     
                                                </table>
                                          </div>
                                          <div class="col-md-12" style="height: 400px;">
                                                <table class="table table-sm table-head-fixed" style="font-size:13px !important">
                                                      <thead>
                                                            <tr>
                                                                  <th colspan="7">STUDENT GRADES</th>
                                                            </tr>
                                                            <tr>
                                                                  <th  width="15%">Code</th>
                                                                  <th  width="35%">Subject</th>
                                                                  <th  width="10%" class="text-center">PRELIM</th>
                                                                  <th  width="10%">MIDTERM</th>
                                                                  <th  width="10%">PREFI</th>
                                                                  <th  width="10%">FINAL</th>
                                                                  <th  width="10%">REMARKS</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="student_grade">
                                                           
                                                      </tbody>
                                                     
                                                </table>
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

      <script>
            $(document).ready(function(){



                  var all_student
                  var courseid
                  var curriculumid
                  var prospectus = []
                  var current_student
                  var seleted_section

                  $('.select2').select2()
                  
                  $('#reload_data').on('click',function(){
                        $('#student_list_select').empty()
                        loadstudent()
                  })



                  //var interval = setInterval(function () { load_college_preenrolled() }, 60000);

                  $(document).on('click','#view_students_button',function(){
                        $('#student_list').modal()
                  })


                  $(document).on('click','#view_pre_enrolled_students_button',function(){
                        $('#pre_enrolled_student_list').modal()
                  })
                  
                  $(document).on('click','#search_schedule_button',function(){
                        $('#search_schedule_modal').modal()
                  })

                  $(document).on('click','.set_student_id',function(){
                        var sectionid = $(this).attr('data-id') 
                        var section_desc = $(this).attr('data-desc')
                        Swal.fire({
                              title: 'Are you sure you want',
                              text: "set this as the student section?",
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Yes!'
                        }).then((result) => {
                              if (result.value) {
                                    set_section(sectionid, section_desc)
                              }
                        })
                  })
                

                  function set_section(t_sectionid , t_sectiondesc) {
                        var semester = $('#semester').val()
                        var syid = $('#school_year').val()
                        $.ajax({
                              type:'GET',
                              url:'/registrar/college/set/student/section',
                              data: {
                                    syid:syid,
                                    semid:semester,
                                    sectionid:t_sectionid,
                                    sectiondesc:t_sectiondesc,
                                    studid:current_student[0].id
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Swal.fire({
                                                type: 'success',
                                                title: 'Updated Successfully',
                                          })
                                          subject_enrollment_record()
                                    }
                                    else{
                                          Swal.fire({
                                                type: 'error',
                                                title: 'Something went wrong!',
                                          }) 
                                    }
                              },
                        })
                  }
                  

                  
                  $(document).on('click','.view_sched',function(){

                        $('#section_schedule_modal').modal();
                        seleted_section = $(this).attr('td-id')
                        $('#selected_sectionname').text($(this).attr('td-text'))

                        $('.set_student_id').attr('data-id',$(this).attr('td-id'))
                        $('.set_student_id').attr('data-desc',$(this).attr('td-text'))

                        $('.view_sched').removeClass('bg-success')
                        $(this).addClass('bg-success')

                        loadsectionschedule()
                  })

                  $(document).on('click','input[type="checkbox"]',function(){
                       
                        if($(this).prop('checked') == true){
                            add_sched($(this).attr('input-id'))
                            //   Swal.fire({
                            //         title: 'Are you sure?',
                            //         text: "You want to add schedule?",
                            //         type: 'warning',
                            //         showCancelButton: true,
                            //         confirmButtonColor: '#3085d6',
                            //         cancelButtonColor: '#d33',
                            //         confirmButtonText: 'Yes, add it!'
                            //   }).then((result) => {
                            //         if (result.value) {
                            //               add_sched($(this).attr('input-id'))
                            //         }else{
                            //               $(this).prop('checked',false)
                            //         }
                            //   })
                        }else{
                            remove_sched($(this).attr('input-id'))
                            //   Swal.fire({
                            //         title: 'Are you sure?',
                            //         text: "You want to remove schedule?",
                            //         type: 'warning',
                            //         showCancelButton: true,
                            //         confirmButtonColor: '#3085d6',
                            //         cancelButtonColor: '#d33',
                            //         confirmButtonText: 'Yes, remove it!'
                            //   }).then((result) => {
                            //         if (result.value) {
                            //               remove_sched($(this).attr('input-id'))
                                          
                            //         }else{
                            //               $(this).prop('checked',true)
                            //         }
                            //   })
                        }
                  })

                  var selected_student_id

                  $(document).on('click','#select_student_button',function(){
                        load_student_data()
                  })

                  function load_student_data(){

                        if($('#student_list_select').val() == ""){
                              $('#student_name').text('--')
                              $('#student_course').text('--')
                              $('#student_gradelevel').text('--')
                              $('#student_enrollment').text('--')
                              $('#student_curriculum').text('--')
                              $('#student_enrolled_section').text('--')
                              $('#student_assigned_section').text('--')
                              $('#section_holder').empty()
                              $('#subject_list').empty()
                              $('#student_grade').empty()
                              $('#shift_student_course').attr('disabled','disabled')
                        }else{

                              $('#shift_student_course').removeAttr('disabled')

                              var student = $('#student_list_select').val()
                              selected_student_id = $('#student_list_select').val()
                              var temp_student = all_student.filter(x=>x.id==student)

                              current_student = temp_student

                              $('#student_name').text(temp_student[0].lastname+', '+temp_student[0].firstname)
                              $('#student_course').text(temp_student[0].courseDesc)
                              $('#student_gradelevel').text(temp_student[0].levelname)
                              $('#student_enrollment').text(temp_student[0].description)
                              $('#student_curriculum').text(temp_student[0].curriculumname)
                              $('#student_sid').text(temp_student[0].sid)

                              if(temp_student[0].sectionname != null){
                                    $('#student_assigned_section').text(temp_student[0].sectionname)
                              }else{
                                    $('#student_assigned_section').text('NOT ASSIGNED')
                              }

                              
                              if(temp_student[0].studstatus  == 1){
                                    $('#student_enrolled_section').text(temp_student[0].sectionname)
                              }else{
                                 
                              }
                              courseid = temp_student[0].courseid
                              course_subjects()
                              curriculumid = temp_student[0].curriculumid
                              loadsection()
                              loadprospectus()
                              load_grades()
                              $('#student_grade').empty();
                        }

                  }

                  $(document).on('click','#select_pre_enrolled_student_button',function(){
                        if($('#pre_enrolled_student_list_select').val() == ""){
                              $('#student_name').text('--')
                              $('#student_course').text('--')
                              $('#student_gradelevel').text('--')
                              $('#student_enrollment').text('--')
                              $('#student_curriculum').text('--')
                              $('#student_enrolled_section').text('--')
                              $('#student_assigned_section').text('--')
                              $('#section_holder').empty()
                              $('#subject_list').empty()
                              $('#student_grade').empty()
                        }else{
                              var student = $('#pre_enrolled_student_list_select').val()
                              var temp_student = all_student.filter(x=>x.id==student)
                              selected_student_id = $('#pre_enrolled_student_list_select').val()
                              current_student = temp_student
                              $('#student_name').text(temp_student[0].lastname+', '+temp_student[0].firstname)
                              $('#student_course').text(temp_student[0].courseDesc)
                              $('#student_gradelevel').text(temp_student[0].levelname)
                              $('#student_enrollment').text(temp_student[0].description)
                              $('#student_curriculum').text(temp_student[0].curriculumname)
                              $('#student_sid').text(temp_student[0].sid)

                              if(temp_student[0].sectionname != null){
                                    $('#student_assigned_section').text(temp_student[0].sectionname)
                              }else{
                                    $('#student_assigned_section').text('NOT ASSIGNED')
                              }

                              
                              if(temp_student[0].studstatus  == 1){
                                    $('#student_enrolled_section').text(temp_student[0].sectionname)
                              }else{
                                 
                              }
                              courseid = temp_student[0].courseid
                              course_subjects()
                              curriculumid = temp_student[0].curriculumid
                              loadsection()
                              loadprospectus()
                              load_grades()
                              $('#student_grade').empty();
                        }
                  })

                  $(document).on('change','#semester',function(){
                        loadstudentenrollment()
                        $('#student_grade').empty();
                        load_grades()
                        var semester = $('#semester').val()
                        var syid = $('#school_year').val()
                        var temp_sections = sections.filter(x=>x.syID == syid && x.semesterID == semester)
                        $('#section_holder').empty()
                        $('#search_schedule_table').empty();

                        $('#select_subject_prospectus, #select_subject_all').val("").change()

                        $.each(temp_sections,function(a,b){
                              $('#section_holder').append('<tr class="view_sched" td-id="'+b.id+'" td-text="'+b.sectionDesc+'"><td>'+b.sectionDesc+'</td></tr>')
                        })

                        
                       

                  })


                  $(document).on('change','#select_subject_prospectus, #select_subject_all',function(){
                        var actual_subjectid = $(this).val()
                        var semester = $('#semester').val()
                        var syid = $('#school_year').val()
                        if(actual_subjectid != ""){
                              $.ajax({
                                    type:'GET',
                                    url:'/subject/schedule',
                                    data: {
                                          syid:syid,
                                          semid:semester,
                                          subjectid:actual_subjectid
                                    },
                                    success:function(data) {
                                       
                                          var temp_data = []
                                          $.each(data,function(a,b){
                                                temp_data.push(b[0])
                                          })
                                          load_sched_table("#search_schedule_table",temp_data)
                                    },
                              })
                        }
                       
                  })

                  function load_sched_table(holder_id,data) {
                        $(holder_id).empty()
                        $.each(data,function(a,b){
                              var lastname = ''
                              var firstname = ''
                              var teacher = ''
                              var temp_stime = (new Date('2020-01-01T'+b.stime)).toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })
                              var temp_etime = (new Date('2020-01-01T'+b.etime)).toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })
                              if(b.lastname != null){
                                    lastname = b.lastname
                              }
                              if(b.firstname != null){
                                    firstname = b.firstname
                              }
                              if(b.lastname != null || b.lastname != null){
                                    teacher = lastname+', '+firstname
                                    teacher = teacher.substring(0, 15) + "..." 
                              }
                              totalUnits = b.lecunits + b.labunits
                              var checked = ''
                              var data_exist = enrollment_record.filter(x=>x.id == b.id)
                              if(data_exist.length != 0){
                                    checked = 'checked="checked"'
                              }
                              $(holder_id).append('<tr><td class="text-center"><input type="checkbox" '+checked+' input-id="'+b.id+'"></td><td>'+b.sectionDesc+'</td><td>'+b.subjCode+'</td><td>'+b.subjDesc+'</td><td class="text-center">'+totalUnits+'</td><td>'+b.description+' / '+temp_stime+' - '+temp_etime+'</td><td>'+teacher+'</td></tr>')
                        })

                        if(data.length == 0){
                              $(holder_id).append('<tr><td colspan="7">NO SCHEDULE AVAILABLE FOR SELECTED SUBJECT</td></tr>')
                        }
                  }

                  loadstudent()
                  load_college_preenrolled()

                  function add_sched(schedid){
                        var semester = $('#semester').val()
                        var syid = $('#school_year').val()
                        $.ajax({
                              type:'GET',
                              url: '/registrar/college/add/studentsched',
                              data:{
                                    syid:syid,
                                    semid:semester,
                                    schedid:schedid,
                                    studid:current_student[0].id
                              },
                              success:function(data) {

                                    if(data[0].status == 1){
                                        //   Swal.fire({
                                        //         type: 'success',
                                        //         title: 'Added Successfully',
                                        //         showConfirmButton: false,
                                        //         timer: 1500
                                        //   })
                                          subject_enrollment_record()
                                    }
                                    else{
                                          Swal.fire({
                                                type: 'error',
                                                title: 'Something went wrong!',
                                                showConfirmButton: false,
                                                timer: 1500
                                          }) 
                                    }
                              }
                        })
                  }

                   function remove_sched(schedid){
                        var semester = $('#semester').val()
                        var syid = $('#school_year').val()
                        $.ajax({
                              type:'GET',
                              url: '/registrar/college/remove/studentsched',
                              data:{
                                    syid:syid,
                                    semid:semester,
                                    schedid:schedid,
                                    studid:current_student[0].id
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                        //   Swal.fire({
                                        //         type: 'success',
                                        //         title: 'Removed Successfully',
                                        //         showConfirmButton: false,
                                        //         timer: 1500
                                        //   })
                                          subject_enrollment_record()
                                    }
                                    else{
                                          Swal.fire({
                                                type: 'error',
                                                title: 'Something went wrong!',
                                                showConfirmButton: false,
                                                timer: 1500
                                          }) 
                                    }
                              }
                        })
                  }

                  

                  function load_college_preenrolled(){
                        $.ajax({
                              type:'GET',
                              url: '/registrar/college/student/pre-enrolled',
                              success:function(data) {
                                  
                                    $('#pre_enrolled_student_list_select').empty()
                                    $('#pre_enrolled_student_list_select').append('<option value="">SELECT PRE-ENROLLED STUDENT</option>')
                                    $('#pre_enrolled_count').text(data.length)
                                    $.each(data,function(a,b){
                                          $('#pre_enrolled_student_list_select').append('<option value="'+b.id+'">'+b.sid+' - '+b.lastname+', '+b.firstname+'</option>')
                                    })
                              }
                        })
                  }


                  function loadstudent(){
                        $.ajax({
                              type:'GET',
                              url: '/registrar/college/students',
                              success:function(data) {
                                    $('#student_list_select').append('<option value="">SELECT STUDENT</option>')
                                    all_student = data;
                                    $.each(data,function(a,b){
                                          var selected = ''
                                          if(b.id == selected_student_id){
                                                selected = 'selected="selected"'
                                          }
                                          $('#student_list_select').append('<option '+selected+' value="'+b.id+'">'+b.sid+' - '+b.lastname+', '+b.firstname+'</option>')

                                          if(b.id == selected_student_id){
                                                load_student_data()
                                          }
                                    })
                              }
                        })
                  }


                  var sections

                  function course_subjects(){
                        $.ajax({
                              type:'GET',
                              url:'/college/subjects',
                              data:{
                                    courseid:courseid
                              },
                              success:function(data) {
                                    all_subjects = data
                                    $('#select_subject_prospectus').empty();
                                    $('#select_subject_prospectus').append('<option value="">SELECT SUBJECT</option>');
                                    $.each(data,function(a,b){
                                          $('#select_subject_prospectus').append('<option value="'+b.subjectID+'">'+b.subjCode+' - '+b.subjDesc+'</option>');
                                    })
                              }
                        })
                  }
                  

                  function loadsection(){
                        $.ajax({
                              type:'GET',
                              url: '/registrar/college/sections',
                              data:{
                                    courseid:courseid
                              },
                              success:function(data) {
                                    sections = data
                                    $('#section_holder').empty()
                                    var semester = $('#semester').val()
                                    var syid = $('#school_year').val()
                                    var temp_sections = sections.filter(x=>x.syID == syid && x.semesterID == semester)
                                    $.each(temp_sections,function(a,b){
                                          $('#section_holder').append('<tr class="view_sched" td-id="'+b.id+'" td-text="'+b.sectionDesc+'"><td>'+b.sectionDesc+'</td></tr>')
                                    })
                              }
                        })
                  }

                  
                  function loadprospectus(){

                        $.ajax({
                              type:'GET',
                              url: '/registrar/college/curriculumprospectus',
                              data:{
                                    curriculumid:curriculumid
                              },
                              success:function(data) {
                                    prospectus = data
                                    loadstudentenrollment()
                              }
                        })
                  }

                  var enrollment_record = []

                  function subject_enrollment_record(){
                        
                        var semester = $('#semester').val()
                        var syid = $('#school_year').val()

                        $.ajax({
                              type:'GET',
                              url: '/registrar/college/enrollment/record/subject',
                              data:{
                                    syid:syid,
                                    semid:semester,
                                    studid:selected_student_id
                              },
                              success:function(data) {
                                    enrollment_record = data
                                    $('#student_grade').empty()
                                    $('.td_section').text(null)
                                    $('.td_schedule').text(null)
                                    $('.td_teacher').text(null)

                                    $('.diff_sect').remove()
									
                                    $.each(data,function(a,b){
                                                var lastname = ''
                                                var firstname = ''
                                                var teacher = ''
                                                var temp_stime = (new Date('2020-01-01T'+b.stime)).toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })
                                                var temp_etime = (new Date('2020-01-01T'+b.etime)).toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })
                                                if(b.lastname != null){
                                                      lastname = b.lastname
                                                }
                                                if(b.firstname != null){
                                                      firstname = b.firstname
                                                }
                                                if(b.lastname != null || b.lastname != null){
                                                      teacher = lastname+', '+firstname
                                                      teacher = teacher.substring(0, 15) + "..." 
                                                }

                                          if($('.td_teacher[data-subj="'+b.subjID+'"]').length != 0){

                                                $('.td_section[data-subj="'+b.subjID+'"]').text(b.sectionDesc)
                                                $('.td_schedule[data-subj="'+b.subjID+'"]').text(b.description +' / '+ temp_stime + ' - ' + temp_etime)
                                                $('.td_teacher[data-subj="'+b.subjID+'"]').text(teacher)

                                                  $('#student_grade').append('<tr class="tr-row" tr-id="'+b.id+'"><td>'+b.subjCode+'</td><td tr-id="'+b.id+'" >'+b.subjDesc+'</td><td tr-id="'+b.id+'" class="tr-prelim text-center"></td><td tr-id="'+b.id+'" class="tr-midterm text-center"></td><td tr-id="'+b.id+'" class="tr-prefigrade text-center"></td><td tr-id="'+b.id+'" class="tr-finalgrade text-center"></td><td tr-id="'+b.id+'" class="tr-remark text-center"></td></tr>')

                                          }else{
                                                totalUnits = b.lecunits + b.labunits
                                                
                                                $('#subject_list').append('<tr class="diff_sect"><td><a href="#" class="set_student_id" data-id="'+b.sectionid+'" data-desc="'+b.sectionDesc+'">'+b.sectionDesc+'</a></td><td>'+b.subjCode+'</td><td>'+b.subjDesc+'</td><td class="text-center">'+totalUnits+'</td><td>'+b.description +' / '+ temp_stime + ' - ' + temp_etime+'</td><td>'+teacher+'</td></tr>')

                                                $('#student_grade').append('<tr class="tr-row" tr-id="'+b.id+'"><td>'+b.subjCode+'</td><td tr-id="'+b.id+'" >'+b.subjDesc+'</td><td tr-id="'+b.id+'" class="tr-prelim text-center"></td><td tr-id="'+b.id+'" class="tr-midterm text-center"></td><td tr-id="'+b.id+'" class="tr-prefigrade text-center"></td><td tr-id="'+b.id+'" class="tr-finalgrade text-center"></td><td tr-id="'+b.id+'" class="tr-remark text-center"></td></tr>')
                                          }
                                    })

                                    load_grades()
                                   
                                   
                              }
                        })
                  }

                  var enrollment_record

                  function loadstudentenrollment(){

                        var semester = $('#semester').val()
                        var syid = $('#school_year').val()

                        $.ajax({
                              type:'GET',
                              url: '/registrar/college/enrollment/record',
                              data:{
                                    syid:syid,
                                    semid:semester,
                                    studid:selected_student_id
                              },
                              success:function(data) {
                                  
                                    $('#e_section').text('--')
                                    $('#e_status').text('--')
                                    $('#e_course').text('--')
                                    $('#e_gradelevel').text('--')
                                    $('#e_curriculum').text('--')

                                    if(data.length > 0){
                                          $('#e_section').text(data[0].sectionDesc)
                                          $('#e_status').text(data[0].description)
                                          $('#e_course').text(data[0].courseDesc)
                                          $('#e_gradelevel').text(data[0].levelname)
                                          $('#e_curriculum').text(data[0].curriculumname)
                                    }else{
                                          var temp_student = all_student.filter(x=>x.id==selected_student_id)

                                          console.log(temp_student)
                                          $('#e_section').text(temp_student[0].sectionDesc)
                                          $('#e_status').text(temp_student[0].description)
                                          $('#e_course').text(temp_student[0].courseDesc)
                                          $('#e_gradelevel').text(temp_student[0].levelname)
                                          $('#e_curriculum').text(temp_student[0].curriculumname)
                                    }
                                  

                                    var subjects

                                    if(data.length != 0){
                                          var subjects = prospectus.filter(x=>x.semesterID == data[0].semid && x.yearID == data[0].yearLevel)
                                    }else{
                                          var subjects = prospectus.filter(x=>x.semesterID == semester && x.yearID == current_student[0].levelid)
                                    }

                                    $('#subject_list').empty()
                                    var done = false
                                    p_length = subjects.length

                                    var temp_enrollment_record = data.filter(x=>x.syid == syid && x.semid == semester )

                                    if(temp_enrollment_record.length > 0){

                                          $('.view_sched[td-id="'+temp_enrollment_record[0].sectionid+'"]').addClass('bg-primary')

                                    }else{

                                          $('.view_sched[td-id="'+current_student[0].sectionid+'"]').addClass('bg-primary')
                                      
                                    }
                                  
                                    $.each(subjects,function(a,b){

                                          totalUnits = b.lecunits + b.labunits

                                          $('#subject_list').append('<tr><td class="td_section" data-subj="'+b.id+'"></td><td >'+b.subjCode+'</td><td>'+b.subjDesc+'</td><td class="text-center">'+totalUnits+'</td><td class="td_schedule" data-subj="'+b.id+'"></td><td class="td_teacher" data-subj="'+b.id+'"></td></tr>')

                                          if(a+1 == p_length){
                                                subject_enrollment_record()
                                          }
                                    })

                              }
                        })
                  }

                  function loadsectionschedule(){

                        $.ajax({
                              type:'GET',
                              url: '  /registrar/college/section/schedule',
                              data:{
                                    sectionid:seleted_section
                              },
                              success:function(data) {

                                    $('#section_schedule').empty()
                                    $.each(data,function(a,b){
                                          
                                          
                                          var lastname = ''
                                          var firstname = ''
                                          var teacher = ''
                                          var temp_stime = ''
                                          var temp_etime = ''
                                          var time = ''
                                          var schedule = ''

                                          if(b.stime != null){
                                                temp_stime = (new Date('2020-01-01T'+b.stime)).toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })
                                          }

                                          if(b.etime != null){
                                                temp_etime = (new Date('2020-01-01T'+b.etime)).toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })
                                          }
                                          
                                          if(temp_stime != '' && temp_etime != ''){
                                                var time = temp_stime + ' - ' + temp_etime
                                          }
                                          
                                          if( time != '' && b.description != null){
                                                schedule = b.description+' / '+time
                                          }
                                         
                                    
                                         
                                          if(b.lastname != null){
                                                lastname = b.lastname
                                          }
                                          if(b.firstname != null){
                                                firstname = b.firstname
                                          }

                                          if(b.lastname != null || b.lastname != null){
                                                teacher = lastname+', '+firstname
                                                teacher = teacher.substring(0, 15) + "..." 
                                          }
                                          totalUnits = b.lecunits + b.labunits

                                          var checked = ''

                                          var data_exist = enrollment_record.filter(x=>x.id == b.id)

                                          if(data_exist.length != 0){
                                                checked = 'checked="checked"'
                                          }

                                          $('#section_schedule').append('<tr><td class="text-center"><input type="checkbox" '+checked+' input-id="'+b.id+'"></td><td>'+b.subjCode+'</td><td>'+b.subjDesc+'</td><td class="text-center">'+totalUnits+'</td><td>'+schedule+'</td><td>'+teacher+'</td></tr>')
                                    })
                              }
                        })

                  }

                  function load_grades(){

                        var syid = $('#school_year').val()
                        var semid =  $('#semester').val()
                        var studid =  selected_student_id

                        $.ajax({
                              type:'GET',
                              url: '/registrar/college/grades',
                              data:{
                                    syid:syid,
                                    semid:semid,
                                    studid:studid
                              },
                              success:function(data) {

                                    

                                    $.each(data,function(a,b){

                                          $('.tr-prelim[tr-id="'+b.id+'"]').text(b.prelemgrade)
                                          $('.tr-midterm[tr-id="'+b.id+'"]').text(b.midtermgrade)
                                          $('.tr-prefigrade[tr-id="'+b.id+'"]').text(b.prefigrade)
                                          $('.tr-finalgrade[tr-id="'+b.id+'"]').text(b.finalgrade)
                                          $('.tr-remark[tr-id="'+b.id+'"]').text(b.remarks)

                                          if(b.remarks == 'DROPPED' || b.remarks == 'INC' || b.remarks == 'FAILED'){
                                                $('.tr-row[tr-id="'+b.id+'"]').addClass('bg-danger')
                                          }
                                          if(b.remarks == 'PASSED'){
                                                $('.tr-row[tr-id="'+b.id+'"]').addClass('bg-success')
                                          }

                                    })
                              
                              }
                        })

                  }
    
    
                var courses = []
                  loadcourses()

                  function loadcourses(){
                        $.ajax({
                              type:'GET',
                              url: '/registrar/college/courses',
                              success:function(data) {
                                    $('#s_course').append('<option value="">SELECT COURSE</option>')
                                    courses = data;
                                    $.each(data,function(a,b){
                                          $('#s_course').append('<option value="'+b.id+'">'+b.courseDesc+'</option>')
                                    })
                              }
                        })
                  }



                  var curriculum = []
                  loadcurriculum()

                  function loadcurriculum(){
                        $.ajax({
                              type:'GET',
                              url: '/registrar/college/curriculum',
                              success:function(data) {
                                    $('#s_curriculum').append('<option value="">SELECT CURRICULUM</option>')
                                    curriculum = data;
                                    // $.each(data,function(a,b){
                                    //       $('#student_list_select').append('<option value="'+b.id+'">'+b.sid+' - '+b.lastname+', '+b.firstname+'</option>')
                                    // })
                              }
                        })
                  }
                  
                  $(document).on('click','#shift_student_course',function (){
                        $('#shift_course_modal').modal()
                       
                  })

                  $(document).on('change','#s_course',function (){
                        $('#s_curriculum').empty();
                        console.log($('#s_course').val())
                        var temp_curriculum = curriculum.filter(x=>x.courseID == $('#s_course').val())
                        $('#s_curriculum').append('<option value="">SELECT CURRICULUM</option>')
                        $.each(temp_curriculum,function(a,b){
                              $('#s_curriculum').append('<option value="'+b.id+'">'+b.curriculumname+'</option>')
                        })
                       
                  })


                  var sections

                  function course_subjects(){
                        $.ajax({
                              type:'GET',
                              url:'/college/subjects',
                              data:{
                                    courseid:courseid
                              },
                              success:function(data) {
                                    all_subjects = data
                                    $('#select_subject_prospectus').empty();
                                    $('#select_subject_prospectus').append('<option value="">SELECT SUBJECT</option>');
                                    $.each(data,function(a,b){
                                          $('#select_subject_prospectus').append('<option value="'+b.subjectID+'">'+b.subjCode+' - '+b.subjDesc+'</option>');
                                    })
                              }
                        })
                  }
                  

                  function loadsection(){
                        $.ajax({
                              type:'GET',
                              url: '/registrar/college/sections',
                              data:{
                                    courseid:courseid
                              },
                              success:function(data) {
                                    sections = data
                                    $('#section_holder').empty()
                                    var semester = $('#semester').val()
                                    var syid = $('#school_year').val()
                                    var temp_sections = sections.filter(x=>x.syID == syid && x.semesterID == semester)
                                    $.each(temp_sections,function(a,b){
                                          $('#section_holder').append('<tr class="view_sched" td-id="'+b.id+'" td-text="'+b.sectionDesc+'"><td>'+b.sectionDesc+'</td></tr>')
                                    })
                              }
                        })
                  }

                  $(document).on('click','#proccess_shift_course',function(){
                        $.ajax({
                              type:'GET',
                              url:'/registrar/shift/student/course',
                              data:{
                                    studid:selected_student_id,
                                    courseid:$('#s_course').val(),
                                    curriculumid:$('#s_curriculum').val()
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Swal.fire({
                                                type: 'success',
                                                title: 'Student course was updated successfully',
                                          }) 
                                    }else{
                                          Swal.fire({
                                                type: 'error',
                                                title: data[0].data,
                                          }) 
                                    }
                              }
                        })
                  })

                

                  
            })
            
      </script>


    
@endsection

