
@extends('chairpersonportal.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      
      <style>
            #enrollinfo .card-body table tr td{
                  cursor: pointer;
            }
            #schedtable tbody tr{
                  cursor: pointer;
            }
            #studentSched table tbody tr{
                  cursor: pointer;
            }
            .font-sm{
                  font-size: 13px;
            }
      </style>

@endsection

@section('content')
<div class="modal fade" id="update_student_curriculum" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm">
          <div class="modal-content">
          <div class="modal-header bg-primary">
                  <h5 class="modal-title">STUDENT CURRICULUM</h5>
                  @if($withCurriculum)
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                        </button>
                  @endif
          </div>
          <form id="roomform" method="GET" action="/chairperson/update/student/curriculum">
                  <div class="modal-body">
                        <input type="hidden" name="studid" value="{{$student->id}}">
                        <div class="form-group">
                              <label for="">Student Curriculum</label>
                              <select name="curriculum" id="curriculum" class="form-control select2">
                                    <option value="">Select student curirriculum</option>
                                   
                                          @if($withCurriculum)
                                                @foreach ($curriculum as $item)
                                                      <option value="{{$item->id}}" {{$checkForCurriculum->curriculumid == $item->id?'selected':''}}>{{$item->curriculumname}}</option>
                                                @endforeach
                                          @else
                                                @foreach ($curriculum as $item)
                                                      <option value="{{$item->id}}">{{$item->curriculumname}}</option>
                                                @endforeach
                                          @endif
                              </select>
                        </div>
                        <div class="form-group">
                              <button class="btn btn-success">UPDATE STUDENT CURRICULUM</button>
                        </div>
                  </div>
            </form>
          </div>
      </div>
</div>

<div class="modal fade" id="student_information" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
          <div class="modal-header bg-primary">
              <h5 class="modal-title">STUDENT INFORMATION</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
              </button>
          </div>
              <div class="modal-body">

                  <div class="row">
                        <div class="col-md-4 ">
                              <label>Student Name</label>  <label class="float-right">:</label>
                        </div>
                        <div class="col-md-8 text-left">
                             <p>{{$student->lastname}}, {{$student->firstname}}</p>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-4 ">
                              <label>Grade Level </label><label class="float-right">:</label>
                        </div>
                        <div class="col-md-8 text-left">
                              <p>{{$student->levelname}}</p>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-4 ">
                              <label>Section</label> <label class="float-right">:</label>
                        </div>
                        <div class="col-md-8 text-left">
                              <p>{{$student->sectionname}}</p>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-4 ">
                              <label>Contact Number</label> <label class="float-right">:</label>
                        </div>
                        <div class="col-md-8 text-left">
                              <p>{{$student->contactno}}</p>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-4 ">
                              <label>Email</label> <label class="float-right">:</label>
                        </div>
                        <div class="col-md-8 text-left">
                              <p>{{$student->semail}}</p>
                        </div>
                  </div>
                  <hr>
                  <div class="row">
                        <div class="col-md-4 ">
                              <label>Father's Name</label> <label class="float-right">:</label>
                        </div>
                        <div class="col-md-8 text-left">
                              <p>{{$student->fathername}}</p>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-4 ">
                              <label>Contact Number</label> <label class="float-right">:</label>
                        </div>
                        <div class="col-md-8 text-left">
                              <p>{{$student->fcontactno}}</p>
                        </div>
                  </div>
                  <hr>
                  <div class="row">
                        <div class="col-md-4 ">
                              <label>Mother's Name</label> <label class="float-right">:</label>
                        </div>
                        <div class="col-md-8 text-left">
                              <p>{{$student->mothername}}</p>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-4 ">
                              <label>Contact Number</label> <label class="float-right">:</label>
                        </div>
                        <div class="col-md-8 text-left">
                              <p>{{$student->mcontactno}}</p>
                        </div>
                  </div>
                  <hr>
                  <div class="row">
                        <div class="col-md-4 ">
                              <label>Guardian's Name</label> <label class="float-right">:</label>
                        </div>
                        <div class="col-md-8 text-left">
                              <p>{{$student->guardianname}}</p>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-4 ">
                              <label>Contact Number</label> <label class="float-right">:</label>
                        </div>
                        <div class="col-md-8 text-left">
                              <p>{{$student->gcontactno}}</p>
                        </div>
                  </div>
                 
                 
              </div>
              {{-- <div class="modal-footer justify-content-between">
                  <button id="getSched" class="btn btn-primary savebutton">SUBMIT</button>
              </div> --}}
          
          </div>
      </div>
</div>

<div class="modal fade " id="studentInfo" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
          <div class="modal-header ">
              <h5 class="modal-title">SUBJECT FORM</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
              </button>
          </div>
              <div class="modal-body">
                  <label>SELECT SUBJECT</label>
                  <select id="selectSubject" class="select2 form-control">
                        @foreach (DB::table('college_subjects')->get() as $item)
                              <option value="{{$item->subjDesc}}">{{$item->subjDesc}}</option>
                        @endforeach
                  </select>
              </div>
              <div class="modal-footer justify-content-between">
                  <button id="getSched" class="btn btn-primary savebutton">SUBMIT</button>
              </div>
          </div>
      </div>
</div>

<div class="modal fade " id="search_schedule_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
                  <div class="modal-header bg-primary">
                        <h5 class="modal-title">SEARCH SUBJECT</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                  </div>
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-4 form-group">
                                    <label for="">Course Subject</label>
                                    <select name="select_subject" id="select_subject" class="select2 form-control">
                                          <option value="">SELECT SUBJECT</option>
                                    </select>
                              </div>   
                              <div class="col-md-4 form-group">
                                    <label for="">All Subject</label>
                                    <select name="select_subject" id="select_subject" class="select2 form-control">
                                          <option value="">SELECT SUBJECT</option>
                                    </select>
                              </div>      
                        </div>
                        <div class="row mt-2 table-responsive" style="height: 300px">
                              <table class="table table-sm font-sm table-bordered">
                                    <thead>
                                          <tr>
                                                <th rowspan="2" class="p-0 align-middle text-center" width="10%"></th>
                                                <th rowspan="2" class="border-bottom align-middle pl-2" width="13%">SECTION</th>
                                                <th rowspan="2" class="border-bottom align-middle" width="12%">CODE</th>
                                                <th rowspan="2" class="border-bottom p-0 align-middle" width="20%">DESCRIPTION</th>
                                                <th colspan="3" class="text-center p-0 align-middle" width="10%">UNIT</th>
                                                <th  rowspan="2" class="border-bottom p-0 align-middle text-center" width="20%">SCHEDULE / ROOM</th>
                                                <th  rowspan="2" class="border-bottom p-0 align-middle text-center" width="15%">FACULTY</th>
                                                
                                          </tr>
                                          <tr>
                                                <th  class="border-bottom p-0 align-middle text-center pr-1 pl-1" >Lec</th>
                                                <th class="border-bottom p-0 align-middle text-center pr-1 pl-1" >Lab</th>
                                                <th class="border-bottom p-0 align-middle text-center pr-1 pl-1" >Credit</th>
                                                
                                          </tr>
                                    </thead>
                                    <tbody id="available_sched">

                                    </tbody>

                              </table>
                        </div>
                  </div>
          </div>
      </div>
</div>

<section class="content-header">
      <div class="container-fluid">
            <div class="row ">
                  <div class="col-sm-6">
                       
                        <h6 >S.Y.: {{DB::table('sy')->where('isactive',1)->first()->sydesc}} | Semester: {{DB::table('semester')->where('isactive',1)->first()->semester}}</h6>
                        
                  </div>
                  <div class="col-sm-2">
                  </div>
                  {{-- <div class="col-sm-4">
                        @if($prev_promotion_status->status == 1)
                              <h6 class="text-success float-right">{{$prev_promotion_status->message}}</h6>
                        @endif
                  </div> --}}
              </div>
            <div class="row mb-2">
                  <div class="col-sm-6">
                              <h1 class="text-primary">{{$student->lastname}}, {{$student->firstname}}</h1>
                  </div>
                  <div class="col-sm-6">
                         <ol class="breadcrumb float-sm-right">
                              <li class="breadcrumb-item"><a href="/home">Home</a></li>
                              <li class="breadcrumb-item active">Student Scheduling</li>
                        </ol>
                  </div>
            </div>
      </div>
</section>


<section class="content pt-0">

      <div class="row">
            <div class="col-md-3">
                  <div class="card">
                        <div  class="card-header card-title bg-info ">
                              ABOUT STUDENT
                        </div>
                        <div class="card-body table-responsive text-sm" style="height: 600px">
                               
                              
                              <label><i class="fa fa-door-open mr-2"></i>ENROLLMENT STATUS</label>
                              <p class="text-success pl-2" id="studsect">
                                    @if($check_for_promotion->studstatus == 1 && $student->studstatus != 1)
                                          ENROLLED
                                    @elseif($check_for_promotion->studstatus == null && $student->studstatus == 1)
                                          ENROLLED
                                    @else
                                          NOT ENROLLED
                                    @endif
                              </p>
                              <hr>
                              @if((isset($check_for_promotion->promotionstatus) && $check_for_promotion->promotionstatus != null))
                              <label><i class="fa fa-door-open mr-2"></i>PROMOTION STATUS</label>
                              <p class="text-success pl-2">
                                    @if($check_for_promotion->promotionstatus == 1)
                                          PROMOTED
                                    @endif
                              </p>
                              <hr>

                              @endif
                              <label><i class="fa fa-door-open mr-2"></i>COURSE</label>
                              <p class="text-success pl-2">
                                    @if($student->courseDesc != null)
                                          {{$student->courseDesc}}
                                    @else
                                          NOT ENROLLED
                                    @endif
                              </p>
                              <hr>
                              <label><i class="fa fa-door-open mr-2"></i>GRADE LEVEL</label>
                              <p class="text-success pl-2">{{$student->levelname}}</p>
                              <hr>
                              <label><i class="fa fa-door-open mr-2"></i>SECTION</label>
                              <p class="text-success pl-2" id="studsect">
                                   
                                    @if(isset($check_for_promotion->sectionDesc))
                                          {{$check_for_promotion->sectionDesc}}
                                    @else
                                          NOT ASSIGNED
                                    @endif
                              </p>
                              <hr>
                              <label><i class="fa fa-door-open mr-2"></i>CURRICULUM</label>
                              <p class="text-success pl-2" id="studsect">
                                    @if($withCurriculum)
                                          {{$checkForCurriculum->curriculumname}}
                                    @endif
                              </p>
                              <hr>
                              <label>
                              <a href="#" class="pl-2" id="more_student_info">
                                   More Information ...
                              </a>
                              {{-- @if()

                              @endif --}}
                        </div>
                        <div class="card-footer">
                              @if($student->studstatus == 1)
                                    <a href="/printcor/{{Crypt::encrypt($student->id)}}" class="btn btn-sm btn-primary btn-block" target="_blank"><i class="fas fa-print"></i> PRINT COR</a>
                              @else
                                    <a href="#" class="btn btn-sm btn-primary btn-block disabled"><i class="fas fa-print"></i> COR</a>
                              @endif
                        </div>
                  </div>
            </div> 

            <div class="col-md-9 ">
                  <div class="row">
                        <div class="card">
                              <div class="card-header card-title bg-info ">
                                    STUDENT CLASS SCHEDULE
                                    @if($student->studstatus != 1)
                                          <button class="btn btn-primary float-right btn-sm" id="update_curriculum">UPDATE CURRICULUM</button>
                                    @endif
                                    {{-- <button class="btn btn-primary float-right btn-sm" id="update_curriculum">UPDATE CURRICULUM</button> --}}
                              </div>
                              <div class="card-body table-responsive p-0" id="studentSched" style="height: 600px; " >
                                    <table class="table table-striped table-borderless table-sm" style="width:1000px">
                                          <thead>
                                                <tr>
                                                      <th rowspan="2" class="border-bottom align-middle pl-2" width="13%">SECTION</th>
                                                      <th rowspan="2" class="border-bottom align-middle" width="12%">CODE</th>
                                                      <th rowspan="2" class="border-bottom p-0 align-middle" width="20%">DESCRIPTION</th>
                                                      <th colspan="3" class="text-center p-0 align-middle" width="10%">UNIT</th>
                                                      <th  rowspan="2" class="border-bottom p-0 align-middle text-center" width="30%">SCHEDULE / ROOM</th>
                                                      <th  rowspan="2" class="border-bottom p-0 align-middle text-center" width="15%">FACULTY</th>
                                                </tr>
                                                <tr>
                                                      <th  class="border-bottom p-0 align-middle text-center pr-1 pl-1" >Lec</th>
                                                      <th class="border-bottom p-0 align-middle text-center pr-1 pl-1" >Lab</th>
                                                      <th class="border-bottom p-0 align-middle text-center pr-1 pl-1" >Credit</th>
                                                      
                                                </tr>
                                          </thead>
                                          <tbody>
                                                @php
                                                      $unit_count = 0;
                                                @endphp
                                                @foreach($studentSched as $itemclass)
                                                      @if(isset($itemclass[0]->id))
                                                            <tr style="font-size:12px !important" data-value="{{$itemclass[0]->id}}"
                                                            data-subj="{{$itemclass[0]->subjID}}"
                                                            data-subjectID="{{$itemclass[0]->subjectID}}"
                                                            >
                                                      @else
                                                            <tr style="font-size:12px !important" 
                                                            data-subj="{{$itemclass[0]->subjID}}"
                                                            data-subjectID="{{$itemclass[0]->subjectID}}"
                                                            >
                                                      @endif
                                                            <td class="pl-2">{{$itemclass[0]->sectionDesc}}</td>
                                                            <td>{{$itemclass[0]->subjCode}}</td>
                                                            <td>{{$itemclass[0]->subjDesc}}</td>
                                                            <td class="text-center pl-0 pr-0 text-center">{{number_format($itemclass[0]->lecunits,1)}}</td>
                                                            <td class="text-center pl-0 pr-0 text-center"">{{number_format($itemclass[0]->labunits,1)}}</td>
                                                            <td class="text-center pl-0 pr-0 text-center"">{{number_format($itemclass[0]->lecunits + $itemclass[0]->labunits,1) }}</td>
                                                            <td>
                                                                  @foreach($itemclass as $item)
                                                                              @if($item->scheddetialclass == 1)
                                                                                    Lec.
                                                                              @elseif($item->scheddetialclass == 2)
                                                                                    Lab.
                                                                              @endif

                                                                              {{$item->description}}   

                                                                              @if($item->stime!=null)
                                                                                    {{\Carbon\Carbon::create($item->stime)->isoFormat('hh:mm A')}} - {{\Carbon\Carbon::create($item->etime)->isoFormat('hh:mm A')}}  
                                                                              @endif

                                                                        
                                                                              {{$item->roomname}}   
                                                                              <br>
                                                                  
                                                                  @endforeach
                                                            </td>
                                                            <td class="text-center">
                                                                  @if($itemclass[0]->lastname != null && $itemclass[0]->firstname != null)
                                                                        {{$itemclass[0]->lastname}}, {{$itemclass[0]->firstname}}
                                                                  @endif
                                                            </td>
                                                      </tr>
                                                      @php
                                                            if($itemclass[0]->sectionDesc != null){
                                                                  $unit_count += number_format($itemclass[0]->lecunits + $itemclass[0]->labunits,1);
                                                            }
                                                      @endphp
                                                @endforeach
                                          </tbody>
                                    </table>
                              </div>
                              <div class="card-footer"  >
                                    <div class="row">
                                          <div id="studentSchedfooter" class="col-md-4 m-0"></div>
                                          <div id="unit_count" class="col-md-8"><h5 class="float-right">Total Units: <span id="unit_count_text">{{$unit_count}}</span></h5></div>
                                    </div>
                                 
                              </div>      
                        </div>
                  </div>
            </div>
      </div>
      <div class="row">
            <div class="col-md-3">
                  <div class="card " id="enrollinfo">
                        <div class="card-header card-title bg-primary">
                              SECTIONS LIST
                        </div>
                        <div class="card-body p-0" >
                              <div class="m-2"><button class="btn btn-default btn-block" id="search_schedule_button"><i class="fas fa-search"></i> Search Schedule</button></div>
                             
                              <div class="table-responsive" style="height:451px">
                                    <table class="table table-sm table-hover">
                                          @if(count($sections) > 0)
                                                @foreach ($sections as $section)
                                                      @if(isset($check_for_promotion->sectionDesc))
                                                            @if($section->id == $check_for_promotion->sectionID)
                                                                  <tr class="sectionselection bg-primary" data-value="{{$section->id}}">
                                                            @else
                                                                  <tr class="sectionselection" data-value="{{$section->id}}">
                                                            @endif      
                                                                  <td width="70%" class="section_description" data-value="{{$section->id}}">   
                                                                        {{$section->sectionDesc}}</span>
                                                                  </td>
                                                                  <td width="30%" class="align-middle text-center p-0">
                                                                        ( {{$section->count}} )
                                                                  </td>
                                                            </tr>
                                                      @else
                                                            @if($section->id == $student->sectionid)
                                                                  <tr class="sectionselection bg-primary" data-value="{{$section->id}}">
                                                            @else
                                                                  <tr class="sectionselection" data-value="{{$section->id}}">
                                                            @endif      
                                                                  <td width="70%" class="section_description" data-value="{{$section->id}}">   
                                                                        {{$section->sectionDesc}}</span>
                                                                  </td>
                                                                  <td width="30%" class="align-middle text-center p-0">
                                                                        ( {{$section->count}} )
                                                                  </td>
                                                            </tr>
                                                      @endif
                                                @endforeach
                                          @else
                                                <tr class="text-center"><td>NO AVAILABLE SECTIONS</td></tr>
                                          @endif
                                    </table>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="col-md-9 pl-0">
                  <div class="card" id="schedCard">
                        <div class="card-header card-title bg-success">
                              CLASS SCHEDULE
                              {{-- @if($student->studstatus != 1)
                                    <button class="btn btn-default float-right btn-sm" id="select_all">SELECT ALL</button>
                              @endif
                              @if($student->studstatus != 1)
                                    <button class="btn btn-default float-right btn-sm" id="unselect_all">UNSELECT ALL</button>
                              @endif --}}
                              @if( (isset($check_for_promotion->studstatus) && $check_for_promotion->studstatus == 0) )
                                    <button class="btn btn-default float-right btn-sm ml-1 mr-1" id="select_all" >SELECT ALL</button>
                                    <button class="btn btn-default float-right btn-sm ml-1 mr-1" id="unselect_all">UNSELECT ALL</button>
                              @endif

                        </div>
                        <div class="card-body table-responsive p-0" id="classschedule" style="height:500px">
                              @include('collegeportal.pages.tables.enrollmentsched')
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


                  
                  @if(!$withCurriculum)
                       $('#update_student_curriculum').modal()
                  @endif

                  $(document).on('click','#more_student_info',function(){

                        $('#student_information').modal()


                  })

                  
                  $(function () {
                              $('.select2').select2({
                                    theme: 'bootstrap4'
                              })
                        })
                  
                  $(document).on('click','#getSched',function(){
                        $('#subjectModal').modal('hide')

                        var subjDesc = $('#selectSubject').val().toLowerCase().replace(/\s+/g, '-')
                        
                        $.ajax({
                              type:'GET',
                              url:'/chairperson/getsched/allcollege/'+subjDesc,
                              success:function(data) {
                                    $('#classschedule').empty();
                                    $('#classschedule').append(data);
                              }
                        })
                  })
            })
      </script>

      @if($check_for_promotion->studstatus == 0)
            <script>

                  $(document).ready(function(){

                        $(document).on('click','#search_schedule_button',function(){
                              $('#search_schedule_modal').modal()

                              
                            
                        })

                        var allsched = @json($studentSched)

                        var all_subjects
                        var student_selected_subject

                        $.ajax({
                              type:'GET',
                              url:'/college/subjects',
                              data:{
                                    courseid:'{{$student->courseid}}'
                              },
                              success:function(data) {
                                    all_subjects = data
                                    $('#select_subject').empty();
                                    $('#select_subject').append('<option value="">Select subject</option>');
                                    $.each(data,function(a,b){
                                          $('#select_subject').append('<option value="'+b.subjectID+'">'+b.subjCode+' - '+b.subjDesc+'</option>');
                                    })
                              }
                        })


                        $(document).on('change','#select_subject',function(){

                              var actual_subjectid = $(this).val()

                              $.ajax({
                                    type:'GET',
                                    url:'/subject/schedule',
                                    data: {
                                          syid:'{{DB::table('sy')->where('isactive',1)->select('id')->first()->id}}',
                                          semid:'{{DB::table('semester')->where('isactive',1)->select('id')->first()->id}}',
                                          subjectid:actual_subjectid
                                    },
                                    success:function(data) {

                                          $('#available_sched').empty()

                                          if(data.length == 0){
                                                $('#available_sched').append('<tr style="font-size:12px !important"><td colspan="9">NO AVAILABLE SCHEDULE</td></tr>')
                                          }

                                          student_selected_subject = []


                                          $.each(allsched,function(a,b){
                                                if(b[0].subjectID == actual_subjectid && b[0].sectionDesc != null){
                                                      student_selected_subject.push(b[0])
                                                }
                                          })

                                          $.each(data,function(a,b){

                                               

                                                var schedule = ''
                                                var teacher = ''
                                                var checkbox = ''
                                                var checked = ''
                                                var totalunits = parseInt(b[0].labunits) + parseInt(b[0].lecunits)



                                                if(student_selected_subject.length != 0 && student_selected_subject[0].id == b[0].id){
                                                      checked = 'checked="checked"'
                                                }

                                                checkbox += '<div class="icheck-success d-inline">'
                                                checkbox += '<input '+checked+'type="checkbox" id="sched'+b[0].id+'" data-value="'+b[0].id+'" data-subj="'+b[0].subjID+'"' 
                                                checkbox += 'data-units="'+totalunits+'" data-subjectid="'+b[0].subjectID+'" data-sectionid="'+b[0].sectionid+'" data-sectionname="'+b[0].sectionDesc+'" class="search_sched_checkbox">'
                                                checkbox += '      <label for="sched'+b[0].id+'">'
                                                checkbox += '      </label>'
                                                checkbox += '</div>'

                                                var temp_stime = (new Date('2020-01-01T'+b[0].stime)).toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })
                                                var temp_etime = (new Date('2020-01-01T'+b[0].etime)).toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })

                                                if(b[0].scheddetialclass == 1){
                                                      schedule += 'Lec. '+b[0].description+' '+temp_stime+' - '+temp_etime+' '+b[0].roomname
                                                }else if(b[0].scheddetialclass == 2){
                                                      schedule += 'Lab. '+b[0].description+' '+temp_stime+' - '+temp_etime+' '+b[0].roomname
                                                }

                                                if(b[0].lastname != null && b[0].firstname != null){
                                                      teacher += b[0].lastname+', '+b[0].firstname
                                                }

                                                $('#available_sched').append('<tr style="font-size:12px !important" data-value="'+b[0].id+'" class="searched_sched"><td class="text-center align-middle">'+checkbox+'</td><td>'+b[0].sectionDesc+'</td><td>'+b[0].subjCode+'</td><td>'+b[0].subjDesc+'</td><td class="text-center">'+b[0].lecunits+'</td><td class="text-center">'+b[0].labunits+'</td><td class="text-center">'+totalunits+'</td><td>'+schedule+'</td><td>'+teacher+'</td></tr>')
                                          })
                                    },
                              })
                        })
                      
                        
                        var sections = []
                        var a;
                        var ad;
                        var svalue;
                        var b = '{{$student->id}}';
                        var b2 = '{{sprintf("%06d", $student->id)}}';
                        var d = [];
                        var clickedCheckbox;
                        var process_count = 0
                        var process_length = 0
                        var searched_sched = false

                        $(document).on('click','#update_curriculum',function(){

                              $('#update_student_curriculum').modal();

                        })
                        

                        @if(isset($check_for_promotion->sectionID))
                              schedTable('{{$check_for_promotion->sectionID}}')
                              ad =  '{{$check_for_promotion->sectionDesc}}'     
                        @else
                              @if($student->sectionname != null)
                                    schedTable('{{$student->sectionid}}')
                                    ad =  '{{$student->sectionname}}'                    
                              @endif
                        @endif

                       

                        @if($withSched)

                              $('#studentSched table tbody tr').each(function(){

                                    if($(this)[0].hasAttribute('data-value')){

                                          d.push( $(this).attr('data-value') ) 
                                          sections.push($(this).closest('tr')[0].children[0].innerText)
                                          
                                    }
                              })

                        @endif


                              $(document).on('click','#select_all',function(){

                                    process_count = 0
                                    process_length  = $('input[type=checkbox]').length


                                    $('.section_sched').each(function(){
                                          var innerHtml = $(this).closest('tr')[0].innerHtml
                                          var dataValue =  $(this).attr('data-value');
                                          var dataSujb =  $(this);
                                          var classschedule = $(this).closest('tr')[0].children[2].innerText;
                                          var trExist = false;
                                          var subjectPassed = true;
                                          var schedExist = false;
                                          $(this).prop('checked',true)

                                       

                                          checkSubjAvailability($(this).closest('tr'), dataSujb)
                                          
                                    })


                              })

                              $(document).on('click','#unselect_all',function(){
                                    process_count = 0
                                    var process_length = $('input[type=checkbox]').length

                                    $('.section_sched').each(function(){
                                          if($(this).prop('checked')){
                                                $('#unit_count_text').text(0)
                                                removeStudentSched($(this).attr('data-value'))
                                                $(this).prop('checked',false)    
                                                if(process_length == process_count){
                                                      $('#update_student_schedule_button').removeClass('disabled')
                                                }  
                                          }
                                    })
                              })

                              
                  

                              @if($withSched)

                                    $('#studentSchedfooter').append('<a href="/chairperson/addtosched/'+d+'/section/'+sections+'" class="btn btn-success btn-sm disabled" id="update_student_schedule_button">UPDATE STUDENT SCHEDULE</a>')

                              @else

                                    $('#studentSchedfooter').append('<a href="/chairperson/addtosched/'+d+'/section/'+sections+'" class="btn btn-primary btn-sm disabled" id="update_student_schedule_button">SUBMIT STUDENT SCHEDULE</a>')

                              @endif

                              

                        $(document).on('click','input[type=checkbox]',function(){

                              clickedCheckbox = $(this);
                              process_count = 0
                              process_length = 1
                              
                              if($(this).is(":checked") == true){
                                    var dataValue =  $(this).attr('data-value');
                                    var dataSujb =  $(this);
                                    var trExist = false;
                                    var subjectPassed = true;
                                    var schedExist = false;
                                    $('#studentSched table tbody tr').each(function(key,value){
                                          if(dataValue == $(this).attr('data-value')){

                                                schedExist = true;

                                                Swal.fire({

                                                      type: 'error',
                                                      title: 'Schedule already exist!',
                                                      showConfirmButton: false,
                                                      timer: 1500

                                                })
                                          }
                                    })
                              
                                    if(!schedExist){
                                          if($(this).closest('tr').hasClass('searched_sched')){
                                                searched_sched = true;
                                                a = clickedCheckbox.attr('data-sectionid');
                                                ad =  clickedCheckbox.attr('data-sectionname')
                                                
                                                $('.search_sched_checkbox').each(function(){
                                                      if($(this).attr('data-value') != dataValue && $(this).prop('checked')){

                                                            $('#unit_count_text').text(parseInt($('#unit_count_text').text()) - parseInt($(this).attr('data-units')))
                                                            $(this).prop('checked',false)
                                                      }
                                                })
                                                $('.section_sched[data-subjectid="'+dataSujb.attr('data-subjectid')+'"]').each(function(){
                                                      if($(this).attr('data-value') != dataSujb.attr('data-value')){
                                                            $(this).prop('checked',false)
                                                      }
                                                })
                                          }
                                          checkSubjAvailability($(this).closest('tr'), dataSujb)
                                    }
                              }
                              else{
                                    $(this).prop('checked',true)
                                    Swal.fire({
                                          title: 'Are you sure?',
                                          text: "You want to remove schedule?",
                                          icon: 'warning',
                                          showCancelButton: true,
                                          confirmButtonColor: '#3085d6',
                                          cancelButtonColor: '#d33',
                                          confirmButtonText: 'Yes, remove it!'
                                    }).then((result) => {
                                          if (result.value) {
                                                removeStudentSched($(this).attr('data-value'))
                                                $('#unit_count_text').text(parseInt($('#unit_count_text').text()) - parseInt(clickedCheckbox.attr('data-units')))
                                                var temp_array = []
                                                $.each(allsched,function(a,b){
                                                      console.log(b.id +' - '+$(this).attr('data-value'))
                                                      if(b.id != $(this).attr('data-value')){
                                                            temp_array.push(b) 
                                                      }
                                                })
                                                allsched = temp_array
                                                $(this).prop('checked',false)
                                                $('input[type="checkbox"][data-value="'+$(this).attr('data-value')+'"]').prop('checked',false)
                                          }
                                    })
                              }
                        })

                        function checkSubjAvailability(trdataValue, checkValue){

                              var innerHtml = trdataValue[0].innerHTML
                              var dataValue =  trdataValue.attr('data-value');

                              if(searched_sched){
                                    var classschedule = trdataValue[0].children[3].innerText;
                              }else{
                                    var classschedule = trdataValue[0].children[2].innerText;
                              }
                             
                              var trExist = false;
                              var subjectPassed = true;
                              var schedExist = false;

                              $.ajax({
                                    type:'GET',
                                    url:'/chairperson/checkifpassed/'+dataValue+'/'+b2,
                                    success:function(data) {
                                          if(data == 0){
                                                subjectPassed = false;
                                          }
                                    }
                              }).done(function(){
                                    process_count += 1

                                    $('#unit_count_text').text(parseInt($('#unit_count_text').text()) + parseInt(checkValue.attr('data-units')))

                                    if(process_length == process_count){
                                          $('#update_student_schedule_button').removeClass('disabled')
                                    }

                                    subjectPassed = true

                                    if(subjectPassed){
                                          var temp_array = [{
                                                                  'id':$(checkValue).attr('data-value'),
                                                                  'subjID':$(checkValue).attr('data-subj'),
                                                                  'subjectID':$(checkValue).attr('data-subjectid'),
                                                                  'sectionDesc':$(checkValue).attr('data-sectionDesc')
                                                            }]
                                      
                                          allsched[parseInt(Object.keys(allsched).pop()) + 1] = temp_array

                                          $('.section_sched[data-value="'+$(trdataValue).attr('data-value')+'"]').prop('checked',true)

                                          $('#studentSched table tbody tr').each(function(key,value){
                                               
                                                if(
                                                      value.children[2].innerText == classschedule && 
                                                      checkValue.attr('data-subjectid') == $(this).attr('data-subjectid')){
                                                
                                                      trExist = true;
                                                
                                                      if($(this)[0].hasAttribute('data-value')){
                                                            
                                                            if($(this).attr('data-value') != dataValue){
                                                                  var index = d.indexOf($(this).attr('data-value'));
                                                                  if (index !== -1) d.splice(index, 1);
                                                                  d.push(dataValue) 
                                                            }
                                                      
                                                      }
                                                      else{
                                                            d.push(dataValue) 
                                                      }

                                                      var isFirst = true;
                                                      if(checkValue.attr('othercollege') == 'othercollege'){
                                                            $(this).replaceWith('<tr style="font-size:12px !important" data-subjectID="'+checkValue.attr('data-subjectID')+'" class="latest" data-value="'+dataValue+'">'+innerHtml+'</tr>')
                                                            $('#studentSched table tbody tr[data-value="'+dataValue+'"]').find("td:eq(0)").remove();
                                                      }
                                                      else{
                                                            $(this).replaceWith('<tr style="font-size:12px !important" data-subjectID="'+checkValue.attr('data-subjectID')+'" class="latest" data-value="'+dataValue+'"><td class="pl-2">'+ad+'</td>'+innerHtml+'</tr>')

                                                            $('#studentSched table tbody tr[data-value="'+dataValue+'"]').find("td:eq(1)").remove();

                                                            if(searched_sched){
                                                                  $('#studentSched table tbody tr[data-value="'+dataValue+'"]').find("td:eq(1)").remove();
                                                            }

                                                            $('.latest').removeClass('latest')
                                                      }

                                                }

                                                if( d.length > 0){

                                                      // $('#studentSchedfooter').empty();

                                                      // getSelectedSections()

                                                      // @if($withSched)
                                                      //       $('#studentSchedfooter').append('<a href="/chairperson/addtosched/'+d+'/section/'+sections+'" class="btn btn-success btn-sm" id="update_student_schedule_button">SUBMIT UPDATED STUDENT SCHEDULE</a>')
                                                      // @else
                                                      //       $('#studentSchedfooter').append('<a href="/chairperson/addtosched/'+d+'/section/'+sections+'" class="btn btn-primary btn-sm" id="update_student_schedule_button">SUBMIT STUDENT SCHEDULE</a>')
                                                      // @endif

                                                }

                                          })


                                          
                                    

                                          if(!trExist){
                                                d.push(dataValue) 
                                                $('.latest').removeClass('latest')
                                                getSelectedSections()
                                                if(checkValue.attr('othercollege') == 'othercollege'){
                                                      $(this).replaceWith('<tr data-subjectID="'+checkValue.attr('data-subjectID')+'" class="latest" data-value="'+dataValue+'">'+innerHtml+'</tr>')
                                                      $('#studentSched table tbody tr[data-value="'+dataValue+'"]').find("td:eq(0)").remove();
                                                      $('.latest').removeClass('latest')
                                                }
                                                else{
                                                      $('#studentSched table tbody').prepend('<tr style="font-size:12px !important" data-subjectID="'+checkValue.attr('data-subjectID')+'" class="latest" data-value="'+dataValue+'" isExtra="isExtra"><td class="align-middle pl-2">'+ad+'</td>'+innerHtml+'</tr>')
                                                      $('#studentSched table tbody tr[data-value="'+dataValue+'"]').find("td:eq(1)").remove();
                                                      if(searched_sched){
                                                            $('#studentSched table tbody tr[data-value="'+dataValue+'"]').find("td:eq(1)").remove()
                                                      }
                                                }
                                          }

                                          getSelectedSections()

                                          $('#studentSchedfooter a').attr('href','/chairperson/addtosched/'+d+'/section/'+sections)


                                    }
                                    else{

                                          clickedCheckbox.prop('checked',false)

                                          Swal.fire({
                                                type: 'error',
                                                title: 'Subject Pre-requisite \nFailed or not yet taken.',
                                                showConfirmButton: false,
                                                timer: 1500
                                          })
                                    }




                              })
                        }

                        function getSelectedSections(){

                              var sameDataValue = 0;
                              sections = [];
                              d = [];

                              $('#studentSched table tbody tr').each(function(){
                              
                                    if(sameDataValue != $(this).attr('data-value') &&  $(this).attr('data-value') != undefined){

                                          if($(this)[0].children[0].innerText != ''){

                                                sections.push($(this)[0].children[0].innerText)
                                                d.push($(this).attr('data-value'))
                                          }

                                          sameDataValue = $(this).attr('data-value')

                                    }
                                    
                              })

                              if(sections.length == 0){
                                    sections = ["0"]
                              }

                        }


                        function removeStudentSched(attrDataValue){

                              process_count += 1
                              if(process_length == process_count){
                                    $('#update_student_schedule_button').removeClass('disabled')
                              }

                             
                              var tableRow = $('#studentSched table tbody tr[data-value="'+attrDataValue+'"]')
                              var temp_day = d
                        
                                                
                                                // $('#studentSchedfooter').empty()

                                                if(d.length > 0){

                                                      // getSelectedSections()

                                                      // @if($withSched)
                                                      //       $('#studentSchedfooter').append('<a href="/chairperson/addtosched/'+d+'/section/'+sections+'" class="btn btn-success btn-sm disabled" id="update_student_schedule_button">SUBMIT UPDATED STUDENT SCHEDULE</a>')
                                                      // @else
                                                      //       $('#studentSchedfooter').append('<a href="/chairperson/addtosched/'+d+'/section/'+sections+'/" class="btn btn-primary btn-sm disabled" id="update_student_schedule_button">SUBMIT STUDENT SCHEDULE</a>')
                                                      // @endif

                                                }

                                                var index = d.indexOf(tableRow.attr('data-value'));

                                                if (index !== -1) d.splice(index, 1);

                                                if(d.length == 0){
                                                      temp_day = 0
                                                      d = []

                                                }

                                                if(tableRow.attr('isExtra') == 'isExtra'){

                                                      tableRow.remove();

                                                      getSelectedSections()

                                                      if(d.length == 0){
                                                            temp_day = 0
                                                            d = [];
                                                      }

                                                      $('#studentSchedfooter a').attr('href','/chairperson/addtosched/'+temp_day+'/'+'section/'+sections)

                                                }
                                                else{

                                                      var isFirst = true;

                                                      var dataValue = tableRow.attr('data-value')

                                                      $('#studentSched table tbody tr').each(function(){

                                                            if($(this).attr('data-value') == dataValue){
                                                            
                                                                  if(isFirst){
                                                                        isFirst = false;
                                                                        $(this).removeAttr('data-value')
                                                                        // $(this)[0].children[2].innerText = '';
                                                                        // $(this)[0].children[3].innerText = '';
                                                                        // $(this)[0].children[4].innerText = '';
                                                                        $(this)[0].children[6].innerText = '';
                                                                        $(this)[0].children[7].innerText = '';
                                                                        $(this)[0].children[0].innerText = '';
                                                                        // $(this)[0].children[8].innerText = '';

                                                                  }
                                                                  else{

                                                                        $(this).remove();

                                                                  }

                                                            }

                                                      })
                                                
                                                      getSelectedSections()
                                                
                                                      if(d.length == 0){
                                                            temp_day = 0
                                                            d = [];
                                                      }

                                                      $('#studentSchedfooter a').attr('href','/chairperson/addtosched/'+temp_day+'/'+'section/'+sections)

                                                }

                        }

                        $(document).on('click','.sectionselection',function(){
                              $('.sectionselection').removeClass('bg-primary')
                              $(this).addClass('bg-primary')
                              a = $(this).attr('data-value');
                              ad = $('.section_description[data-value="'+a+'"]').text()
                              var table = schedTable(a)
                        })

                        function schedTable(a){
                              
                              return $.ajax({
                                    type:'GET',
                                    url:'/chairperson/scheduling/sectscshed/'+a,
                                    success:function(data) {

                                          $('#classschedule').empty();
                                          $('#classschedule').append(data);

                                          @if($withSched)

                                                $('#studentSched table tbody tr').each(function(){
                                                      if($(this)[0].hasAttribute('data-value')){
                                                      $('#classschedule table tbody input[data-value="'+$(this).attr('data-value')+'"]').prop('checked',true)
                                                      }
                                                })

                                          @endif

                                          table = true
                                          
                                    }
                              })
                        }
                  
                        $(document).on('click','.dropsubject',function(){
                              Swal.fire({
                                    title: 'Are you sure?',
                                    text: "You won't be able to revert this!",
                                    type: 'info',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes, drop it!'
                                    }).then((result) => {
                                    if (result.value) {
                                    
                                          fetch('{{Request::root()}}'+'/college/enrollment/dropsubject/'+$(this).attr('data-value'))
                                          
                                    }
                              })
                        })


                        $(document).on('click','#enrollstudent',function(){
                              Swal.fire({
                                    title: 'Enroll Student?',
                                    type: 'info',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes!'
                                    }).then((result) => {
                                          if (result.value) {
                                                var c = $("input[name='c[]']").map(function () {
                                                                  if($(this).prop("checked")  == true){
                                                                        return this.value; 
                                                                  }
                                                            }).get();
                                                $.ajax({
                                                      type:'GET',
                                                      data: {
                                                            c : c
                                                      },
                                                      url:'/chairperson/schedule/student/'+b+'/'+a,
                                                      success:function(data) {
                                                            $("input[name='c[]']").map(function () {
                                                                  if($(this).prop("checked")  == true){
                                                                        $(this).attr('onclick', 'return false')
                                                                  }
                                                                  else{
                                                                        $(this).attr('onclick', 'return false')
                                                                  }
                                                            }).get();

                                                            $('#schedCard .card-footer').empty()
                                                            $('#enrollinfo').empty();
                                                            $('#enrollinfo').append(data);

                                                            Swal.fire({
                                                                  title: 'Enrollment successful!',
                                                                  type: 'success',
                                                                  showConfirmButton: false,
                                                                  timer: 1500
                                                            })
                                                            
                                                      }
                                                      
                                                })
                                          }
                                    })

                              
                        })
                  
                  })



            </script>

      @else
            <script>
                  $(document).ready(function(){
                        
                        var promotion_info = @json($check_for_promotion)
                        

                        schedTable(promotion_info.sectionID)

                        function schedTable(a){
                              
                              return $.ajax({
                                    type:'GET',
                                    url:'/chairperson/scheduling/sectscshed/'+a,
                                    success:function(data) {

                                          $('#classschedule').empty();
                                          $('#classschedule').append(data);

                                          @if($withSched)

                                                $('#studentSched table tbody tr').each(function(){
                                                      if($(this)[0].hasAttribute('data-value')){
                                                      $('#classschedule table tbody input[data-value="'+$(this).attr('data-value')+'"]').prop('checked',true)
                                                      }
                                                })

                                          @endif

                                          table = true
                                          
                                    }
                              })
                        }

                        $(document).on('click','input[type=checkbox]',function(){
                              return false
                        })

                  })      
            </script>              

      @endif
@endsection



