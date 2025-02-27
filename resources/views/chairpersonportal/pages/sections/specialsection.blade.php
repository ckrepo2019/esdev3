
@extends('chairpersonportal.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/gijgo.min.css') }}"> 
      <style>
            .font-sm-diff{
                  font-size: 12px !important;
            }
      </style>
@endsection

@section('content')
      <div class="modal fade" id="schedule_modal" style="display: none;" aria-hidden="true">
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
                              <button class="btn btn-primary savebutton" id="add_instructor">Add Instructor</button>
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
                        <div class="card ">
                              <div class="card-header  card-title bg-primary">
                                    {{$sectionInfo->sectionDesc}} SCHEDULE
                              </div>
                              <div class="card-body">
                                    <div class="row">
                                          <div class="card-body p-0 table-responsive" id="college_sched_holder" style="height:400px !important">
                                  
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card">
                              <div class="card-header bg-primary p-1"></div>
                              <div class="card-body" >
                                    <div class="row mt-2">
                                          <div class="col-md-12">
                                                <input placeholder="Search Subject" class="search form-control" id="search_subject">
                                          </div>
                                    </div>
                                    <div class="table-responsive mt-4" style="height: 600px;">
                                          <div class="row w-100" id="subject_holder"   >

                                          </div>
                                    </div>
                                  
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
      <script>
            $(document).ready(function(){

                  var all_subjects

                  $.ajax({
                        type:'GET',
                        url:"/college/subjects",
                        data:{
                              courseid:'{{$sectionInfo->courseID}}'
                        },
                        success:function(data) {
                              all_subjects = data
                              $.each(data,function(a,b){
                                    var string = '<div class="col-md-4 mt-2 "><div class="card h-100"><div class="card-body text-sm text-muted font-sm-diff pb-0">'
                                    string += '<div class="form-group row mb-0 ">'
                                    string += '<label class=" col-md-4">Subject: </label>'
                                    string += '<div class="col-md-8">'+b.subjDesc+'</div></div>'
                                    string += '<div class="form-group row mb-0">'
                                    string += '<label class=" col-md-4">Code: </label>'
                                    string += '<div class="col-md-8">'+b.subjCode+'</div></div>'
                                    string += '<div class="form-group row mb-0">'
                                    string += '<label class="col-md-4">Curriculum: </label>'
                                    string += '<div class="col-md-8">'+b.curriculumname+'</div></div>'
                                    string += '<div class="form-group row mb-0">'
                                    string += '<label class="col-md-4">Year Level: </label>'
                                    string += '<div class="col-md-8">'+b.semester+'</div></div>'
                                    string += '<div class="form-group row mb-0">'
                                    string += '<label class="col-md-4">Semester: </label>'
                                    string += '<div class="col-md-8">'+b.levelname+'</div></div>'
                                    string += '</div>'
                                    string += '<div class="card-footer p-1"><button class="btn btn-primary btn-sm btn-block add_subject" data-subj="'+b.id+'">ADD SUBJECT</button></div>'
                                    string +='</div></div>'
                                    $('#subject_holder').append(string)
                              })
                        },
                  })

                  var search_val

                  $(document).on('input','#search_subject',function(){
                        search_val = $(this).val()
                        var temp_array = []
                        $.each(all_subjects,function(a,b){
                              if(b.subjDesc != null){
                                if((b.subjDesc.toLowerCase()).includes(search_val.toLowerCase()) || (b.subjCode.toLowerCase()).includes(search_val.toLowerCase())){
                                    temp_array.push(b)
                                }
                             }
                        })
                        $('#subject_holder').empty()
                        $.each(temp_array,function(a,b){
                              var string = '<div class="col-md-4 mt-2 "><div class="card h-100"><div class="card-body text-sm text-muted font-sm-diff pb-0">'
                              string += '<div class="form-group row mb-0 ">'
                              string += '<label class=" col-md-4">Subject: </label>'
                              string += '<div class="col-md-8">'+b.subjDesc+'</div></div>'
                              string += '<div class="form-group row mb-0">'
                              string += '<label class=" col-md-4">Code: </label>'
                              string += '<div class="col-md-8">'+b.subjCode+'</div></div>'
                              string += '<div class="form-group row mb-0">'
                              string += '<label class="col-md-4">Curriculum: </label>'
                              string += '<div class="col-md-8">'+b.curriculumname+'</div></div>'
                              string += '<div class="form-group row mb-0">'
                              string += '<label class="col-md-4">Year Level: </label>'
                              string += '<div class="col-md-8">'+b.semester+'</div></div>'
                              string += '<div class="form-group row mb-0">'
                              string += '<label class="col-md-4">Semester: </label>'
                              string += '<div class="col-md-8">'+b.levelname+'</div></div>'
                              string += '</div>'
                              string += '<div class="card-footer p-1"><button class="btn btn-primary btn-sm btn-block add_subject" data-subj="'+b.id+'">ADD SUBJECT</button></div>'
                              string +='</div></div>'
                              $('#subject_holder').append(string)
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

                  $(document).on('click','.add_subject',function(){

                        var selected_subject = $(this).attr('data-subj')

                        Swal.fire({
                              text: "Are you sure you want to add this subject?",
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Yes!'
                        }).then((result) => {
                              if (result.value) {
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
                                                if(data[0].status == 1){
                                                      Swal.fire({
                                                            type: 'success',
                                                            title: 'Added Successfully!',
                                                      })
                                                      showsched()
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
                        })
                  })


                  //----------------------------------------------------------------------------

                  
                  showsched()

                  function showsched(){

                        $.ajax({
                              type:'POST',
                              url:'/collegeschedule?scheddetail=scheddetail&table=table&sectionid='+'{{$sectionInfo->id}}'+'&syid='+'{{$sectionInfo->syID}}'+'&semid='+'{{$sectionInfo->semesterID}}',
                              data: {'_token': '{{ csrf_token() }}'},
                              success:function(data) {
                                   
                                    $('#college_sched_holder').empty()
                                    $('#college_sched_holder').append(data)
                                    $('.removeSched').removeAttr('hidden')
                              },
                        })

                  }

                  $(document).on('click','.removeSched',function(){

                        var schedid = $(this).attr('data-schedid')

                        Swal.fire({
                              text: "Are you sure you want to deleted this schedule?",
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Yes!'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'GET',
                                          url:"/college/section/remove/schedule",
                                          data:{
                                                schedid:schedid
                                          },
                                          success:function(data) {
                                                if(data[0].status == 1){
                                                      Swal.fire({
                                                            type: 'success',
                                                            text: data[0].data,
                                                      })
                                                      showsched()
                                                      $('tr[data-schedid="'+schedid+'"]').remove()
                                                }
                                                if(data[0].status == 0){
                                                      Swal.fire({
                                                            type: 'error',
                                                            text: data[0].data,
                                                      })
                                                }

                                          },
                                    })
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
                                    }
                                   
                              },
                        })
                  })

                  $(document).on('click','.removesched',function(){
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
                                          url:'/collegeschedule?scheddetail=scheddetail&remove=remove&scheddetailid='+$(this).attr('data-id')+'&sectionid='+'{{$sectionInfo->id}}'+'&syid='+'{{$sectionInfo->syID}}'+'&semid='+'{{$sectionInfo->semesterID}}',
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

                  $(function () {
                        $('.select2').select2({
                              theme: 'bootstrap4'
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

                  var selected_sched

                  $(document).on('click','.addsched',function(){
                        selected_subject = $(this).attr('data-subj')
                        $('#sched_class option:not(:selected)').prop("disabled", true);
                        $('#selected_subject').val($(this).attr('data-value'))
                        actionFrom = 1
                        $('#schedule_modal').modal()

                        selected_sched = $(this).attr('data-schedid')
                  })

                  $('#t_from').timepicker();
                  $('#t_to').timepicker();
               
                  var timepicker = $('#t_from').timepicker({
                        format: 'HH.MM'
                  });

                 

            })
      </script>

@endsection

