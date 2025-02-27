@php

      if(!Auth::check()){ 
            header("Location: " . URL::to('/'), true, 302);
            exit();
      }
      $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();
      if(Session::get('currentPortal') == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(Session::get('currentPortal') == 6){
            $extend = 'adminPortal.layouts.app2';
      }else if(Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
      }
      else{
        if(isset($check_refid->refid)){
              if($check_refid->refid == 27){
                    $extend = 'academiccoor.layouts.app2';
              }
        }else{
          header("Location: " . URL::to('/'), true, 302);
          exit();
        }
       
      }
@endphp

@extends($extend)

@section('pagespecificscripts')

  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">

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
        .view_info {
              cursor: pointer;
        }
        .tableFixHead thead th {
                  position: sticky;
                  top: 0;
                  background-color: #fff;
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
            
            }

            .calendar-table{
            display: none;
        }

        .drp-buttons{
            display: none !important;
        }
  </style>
@endsection

@php
 $sy = DB::table('sy')->select('id','sydesc as text','isactive','sydesc')->get();
 $semester = DB::table('semester')->select('id','semester as text','isactive','semester')->get();
 $schedtimetemplate = DB::table('schedtimetemplate')->where('deleted',0)->select('id','description as text')->get();
 $teacher = DB::table('teacher')
              ->where('deleted',0)
              ->where('isactive',1)
              ->select(
                'id',
                DB::raw("CONCAT(teacher.tid,' - ',teacher.lastname,', ',teacher.firstname) as text")
              )
              ->get();
@endphp

@section('modalSection')

  
  


  <div class="modal fade" id="room_form_modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header pb-2 pt-2 border-0">
                <h4 class="modal-title">Room Form</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
                <div class="message"></div>
                <div class="form-group">
                    <label>Room Name</label>
                    <input id="roomName"  name="roomName" class="form-control form-control-sm" placeholder="Room Name" onkeyup="this.value = this.value.toUpperCase();">
                </div>
                <div class="form-group">
                  <label>Room Capacity</label>
                  <input id="roomCapacity" placeholder="Room Capacity" name="roomCapacity" class="form-control form-control-sm" min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" >
                </div>
                <div class="form-group">
                  <label>Building</label>
                  <select name="building" id="building" class="form-control select2">
                      <option selected value="">SELECT BUILDING</option>
                  </select>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <button  type="button" class="btn btn-primary btn-sm" id="create_room">Create</button>
                  </div>
                </div>
            </div>
          
        </div>
    </div>
  </div>

  <div class="modal fade" id="view_roominfo_modal" style="display: none; padding-right: 17px;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header pb-2 pt-2 border-0">
              <h4 class="modal-title" style="font-size: 1.1rem !important">Room Information : <span id="room_name"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
        </div>
          <div class="modal-body pt-0">
             <div class="row">
                <div class="col-md-2">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="card shadow h-100">
                        <div class="card-body p-2" style="font-size: .8rem! important">
                          <div class="row mt-2">
                            <div class="col-md-12 form-group mb-2">
                              <label>Room Name</label>
                              <input id="update_roomname"  name="roomName" class="form-control form-control-sm" placeholder="Room Name" onkeyup="this.value = this.value.toUpperCase();">
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12 form-group mb-2">
                              <label>Room Capacity</label>
                              <input id="update_roomcap" placeholder="Room Capacity" name="roomCapacity" class="form-control form-control-sm" min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" >
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12 form-group">
                              <label>Building</label>
                              <select name="building" id="update_roombuilding" class="form-control form-control-sm select2">
                                  <option selected value="">SELECT BUILDING</option>
                              </select>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12 ">
                              <button class="btn btn-success btn-sm btn-block" id="update_information" style="font-size:.8rem !important">
                                <i class="fa fa-save"></i> Update Information
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-10">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="card shadow">
                        <div class="card-body p-2">
                          <div class="row">
                            <div class="col-md-5">
                              <button type="button" id="print_sched" class="btn btn-sm btn-outline-primary" style="font-size:.8rem !important"  ><i class="fa fa-print mr-1" ></i>Print Schedule</button>
                            </div>
                            <div class="col-md-2 text-right">
                              <label for="" style="font-size:.9rem !important">School Year: </label> 
                            </div>
                            <div class="col-md-2">
                                <select class="form-control form-control-sm teacher select2"  id="filter_acad_sy">
                                    @foreach ($sy as $item)
                                          @if($item->isactive == 1)
                                                <option value="{{$item->id}}" selected="selected">{{$item->text}}</option>
                                          @else
                                                <option value="{{$item->id}}">{{$item->text}}</option>
                                          @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 text-right">
                              <label for="" style="font-size:.9rem !important">Semester: </label> 
                            </div>
                            <div class="col-md-2">
                                <select class="form-control form-control-sm teacher select2"  id="filter_semester">
                                    @foreach ($semester as $item)
                                          @if($item->isactive == 1)
                                                <option value="{{$item->id}}" selected="selected">{{$item->text}}</option>
                                          @else
                                                <option value="{{$item->id}}">{{$item->text}}</option>
                                          @endif
                                    @endforeach
                                </select>
                            </div>
                          </div>
                         
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="card shadow">
                        <div class="card-body p-2">
                          <div class="row">
                            <div class="col-md-2">
                              <button class="btn btn-sm btn-primary add_sched" >Add Schedule</button>
                            </div>
                              <div class="col-md-5"></div>
                              <div class="col-md-2">
                                <button class="btn btn-sm btn-primary btn-block" id="time_templatelist">Time Template List</button>
                              </div>
                              <div class="col-md-3  mt-1">
                                  <select class="form-control form-control-sm teacher select2"  id="filter_timetemplate">
                                  </select>
                              </div>
                            
                          </div>
                          <div class="row mt-3">
                            <div class="col-md-12">
                              <p class="mb-0 text-sm"><i>Click the subject name to edit or delete schedule.</i></p>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12 table-responsive tableFixHead" style="height: 400px;">
                              <table class="table-sm table-bordered table table-head-fixed mb-0" id="sched_holder"  style="font-size:.7rem !important">
                                
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
             </div>
          </div>
      </div>
    </div>
</div>

<div class="modal fade" id="modal_schedule" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header pb-2  border-0">
              <h4 class="modal-title">Schedule Form</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body pt-0">
          <div class="row">
            <div class="col-md-6">
              <div class="row">
                <div class="form-group col-md-12">
                    <label>Section</label>
                    <select data-placeholder="Select a Section"  name="input_section" id="input_section" class="form-control select2"   >
                    </select>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-12">
                    <label>Subject</label>
                    <select data-placeholder="Select a Subject"  name="input_subject" id="input_subject" class="form-control select2"   >
                    </select>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-12">
                    <label>Subject Teacher</label>
                    <select data-placeholder="Select a teacher"  name="secttea" id="secttea" class="form-control select2"   >
                            {{-- <option value="">Select Teacher</option>
                            @foreach(\App\Models\Principal\SPP_Teacher::filterTeacherFaculty(null,null,null,null,null)[0]->data as $item)
                                <option value="{{$item->id}}">{{$item->tid}} - {{$item->lastname.', '.$item->firstname}}</option>
                            @endforeach  --}}
                    </select>
                </div>
              </div>
              <div class="row" id="apptocon_holder" hidden>
                <!-- <div class="col-md-12">
                    <hr class="mb-2 mt-2">
                </div> -->
                <div class="col-md-12">
                    <div class="icheck-primary d-inline mr-3">
                        <input type="checkbox" id="apptocon" >
                        <label for="apptocon">Apply to all components.</label>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 border-left-1">
              <div class="row">
                  <div class="form-group col-md-12">
                      <label for="">Schedule Classification  <a href="javascript:void(0)" class="edit_schedclass pl-2" hidden><i class="far fa-edit"></i></a>
                          <a href="javascript:void(0)" class="delete_schedclass pl-2" hidden><i class="far fa-trash-alt text-danger"></i></a></label>
                          <select name="classification" id="classification" class="form-control select2"></select>
                  </div>
              </div>
              <div class="row">
                <div class="form-group col-md-12">
                    <label for="">Time</label>
                    <input type="text" class="form-control  form-control-sm reservationtime" name="time" id="time">
                </div>
              </div>
                <label>Day</label>
                <div class="row pl-2">
                    <div class="icheck-primary d-inline col-md-2 mb-0">
                        <input type="checkbox" id="Mon" class="day" value="1" >
                        <label for="Mon">Mon</label>
                    </div>
                    <div class="icheck-primary d-inline col-md-2 mb-0">
                            <input type="checkbox" id="Tue" class="day" value="2" >
                            <label for="Tue">Tue</label>
                    </div>
                    <div class="icheck-primary d-inline col-md-2 mb-0">
                            <input type="checkbox" id="Wed" class="day" value="3" >
                            <label for="Wed">Wed</label>
                    </div>
                    <div class="icheck-primary d-inline col-md-2 mb-0">
                            <input type="checkbox" id="Thu" class="day" value="4" >
                            <label for="Thu">Thu</label>
                    </div>
                    <div class="icheck-primary d-inline col-md-2">
                              <input type="checkbox" id="Fri" class="day" value="5" >
                              <label for="Fri">Fri</label>
                      </div>
                     
                </div>
                <div class="row  pl-2">
                      <div class="icheck-primary  col-md-2">
                          <input type="checkbox" id="Sat" class="day" value="6" >
                          <label for="Sat">Sat</label>
                      </div>
                      <div class="icheck-primarycol-md-2">
                          <input type="checkbox" id="Sun" class="day" value="7" >
                          <label for="Sun">Sun</label>
                      </div>
                </div>
            </div>
          </div>
          <div class="row" id="sched_con_holder">
              <div class="col-md-12">
                  <hr class="mb-2">
              </div>
              <div class="col-md-12">
                 <a href="javascript:void(0)" id="view_conflict_info"|>View conflict information</a>
              </div>
              <!-- <div class="col-md-12">
                  <hr class="mb-2">
              </div>
              <div class="col-md-12">
                  <label for="">Schedule Conflict:</label>
              </div>
              <div class="col-md-12">
                  <hr class="mt-0">
              </div>
              <div class="col-md-12">
                  <strong>Conflict Status:</strong> <span id="con_stat"></span>
              </div>
              <div class="col-md-12">
                  <strong>Section:</strong> <span id="con_sect"></span>
              </div>
              <div class="col-md-12">
                  <strong>Subject:</strong> <span id="con_subj"></span>
              </div>
              <div class="col-md-12">
                  <strong>Day:</strong> <span id="con_day"></span>
              </div>
              <div class="col-md-12">
                  <strong>Time:</strong> <span id="con_time"></span>
              </div> -->
          </div>
          <hr>
          <div class="row">
            <div class="col-md-6">
              <button class="btn btn-primary btn-sm eval">Create Schedule</button>
            </div>
            <div class="col-md-6 text-right">
              <button class="btn btn-danger btn-sm remove_sched" hidden>Remove Schedule</button>
            </div>
          </div>
        </div>
        
      </div>
  </div>
</div>

<div class="modal fade" id="conflict_info_modal" style="display: none;" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header pb-2 pt-2 border-0">
                  <h4 class="modal-title" style="font-size: 1.1rem !important">Conflict Information</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body pt-0" >
            <div class="row" id="conflict_holder">

            </div>
          </div>
      </div>
  </div>
</div>



<div class="modal fade" id="schedclass_form_modal" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
          <div class="modal-header pb-2 pt-2 border-0">
                  <h4 class="modal-title" style="font-size: 1.1rem !important">Schedule Classification Form</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body pt-0">
             <div class="row">
                  <div class="col-md-12 form-group">
                      <label for="">Schedule Classification Description</label>
                      <input class="form-control form-control-sm" id="schedclass_desc">
                  </div>
             </div>
             <div class="row">
                  <div class="col-md-12">
                      <button class="btn btn-sm btn-primary" id="create_schedclass_button">Create Schedule Classification</button>
                      <button class="btn btn-success btn-primary" id="update_schedclass_button" hidden>Update Schedule Classification</button>
                  </div>
             </div>
          </div>
      </div>
  </div>
</div>



<div class="modal fade" id="timetemp_modal" style="display: none;" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header pb-2 pt-2 border-0">
              <h4 class="modal-title">Time Template List</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <div class="row">
              <div class="col-md-6 form-group">
                  <label for="">Time Template <a href="javascript:void(0)" class="edit_timetemp pl-2" hidden><i class="far fa-edit"></i></a>
                  <a href="javascript:void(0)" class="delete_timetemp pl-2" hidden><i class="far fa-trash-alt text-danger"></i></a></label>
                  <select name="" id="input_timetemplate" class="form-control select2">
                      @foreach ($schedtimetemplate as $item)
                          <option value="{{$item->id}}">{{$item->text}}</option>
                      @endforeach
                  </select>
              </div>
          </div>   
          <div class="row">
            <div class="col-md-12" style="font-size:.8rem !important">
              <table class="table table-sm table-striped table-bordered table-hovered table-hover " id="timetempdetail_datatable">
                <thead>
                  <tr>
                    <th width="40%">Start Time</th>
                    <th width="40%">End Time</th>
                    <th width="10%"></th>
                    <th width="10%"></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>  
          </div> 
        </div>
      </div>
  </div>
</div>

<div class="modal fade" id="timetemp_form_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
          <div class="modal-header pb-2 pt-2 border-0">
                  <h4 class="modal-title" style="font-size: 1.1rem !important">Time Template Form</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body pt-0">
             <div class="row">
                  <div class="col-md-12 form-group">
                      <label for="">Time Template Description</label>
                      <input class="form-control form-control-sm" id="timetemp_desc">
                  </div>
             </div>
             <div class="row">
                  <div class="col-md-12">
                      <button class="btn btn-sm btn-primary" id="create_timetemp_button" style="font-size:.8rem !important">Create Time</button>
                      <button class="btn btn-success btn-primary" id="update_timetemp_button" hidden style="font-size:.8rem !important">Update Time</button>
                  </div>
             </div>
          </div>
      </div>
  </div>
</div>

<div class="modal fade" id="timetempdeatil_form_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
          <div class="modal-header pb-2 pt-2 border-0">
                  <h4 class="modal-title" style="font-size: 1.1rem !important">Time Detail Form</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body pt-0">
             <div class="row">
                <div class="form-group col-md-12">
                  <label for="">Time</label>
                  <input type="text" class="form-control  form-control-sm" name="timetempdetail_input" id="timetempdetail_input">
                </div>
             </div>
             <div class="row">
                  <div class="col-md-12" >
                      <button class="btn btn-sm btn-primary" id="create_timetempdetail_button" style="font-size:.8rem !important">Create Time Template</button>
                      <button class="btn btn-success btn-primary" id="update_timetempdetail_button" hidden style="font-size:.8rem !important">Update Time Tempate</button>
                  </div>
             </div>
          </div>
      </div>
  </div>
</div>


@endsection


@section('content')
<section class="content-header">
  <div class="container-fluid">
        <div class="row mb-2">
              <div class="col-sm-6">
                    <h1>Room</h1>
              </div>
              <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Room</li>
              </ol>
              </div>
        </div>
  </div>
</section>
<section class="content p-0">
    <div class="container-fluid">
        <div class="card shadow">
          <div class="card-body" style="font-size:.8rem !important">
            <table class="table table-sm table-striped table-bordered table-hovered table-hover " id="rooms_datatable">
              <thead>
                <tr>
                  <th width="20%">Room Name</th>
                  <th width="20%">Building</th>
                  <th width="10%" class="p-0 text-center align-middle">Capacity</th>
                  <th width="27%"></th>
                  <th width="15%"></th>
                  <th width="4%"></th>
                  <th width="4%"></th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
      </div>
    </div>
</section>
@endsection

@section('footerjavascript')

<script src="{{asset('plugins/moment/moment.min.js') }}"></script>
  <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
  <script src="{{asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
  <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
  <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>

  <script>
    $(document).on('click','#view_conflict_info',function(){
      $('#conflict_info_modal').modal()
    })
  </script>

  <script>
    //time temp detail
    var all_timetempdetail = [];
    var selected_timetempdetail = null;


    function get_timetempdetail(){
        $.ajax({
            type:'GET',
            url:'/setup/timetempdetails/list',
            data:{
              headerid:$('#input_timetemplate').val()
            },
            success:function(data) {
              all_timetempdetail = data
              timetempdetail_datatable()
            },
        })
    }

    function create_timetempdetail(){
        $.ajax({
            type:'GET',
            url:'/setup/timetempdetails/create',
            data:{
              timetempdetail:$('#timetempdetail_input').val(),
              headerid:$('#input_timetemplate').val()
            },
            success:function(data) {
                if(data[0].status == 1){
                  all_timetempdetail = data[0].data
                  timetempdetail_datatable()
                  if($('#filter_timetemplate').val() != null && $('#filter_timetemplate').val() != ""){
                    get_sched()
                  }
                }
                Toast.fire({
                    type: data[0].icon,
                    title: data[0].message
                })
            },
        })
    }

    function update_timetempdetail(){
        $.ajax({
            type:'GET',
            url:'/setup/timetempdetails/update',
            data:{
              id:selected_timetempdetail,
              timetempdetail:$('#timetempdetail_input').val(),
              headerid:$('#input_timetemplate').val()
            },
            success:function(data) {
                if(data[0].status == 1){
                  all_timetempdetail = data[0].data
                  timetempdetail_datatable()
                  if($('#filter_timetemplate').val() != null && $('#filter_timetemplate').val() != ""){
                    get_sched()
                  }
                }
                Toast.fire({
                    type: data[0].icon,
                    title: data[0].message
                })
            },
        })
    }

    function delete_timetempdetail(){
        $.ajax({
            type:'GET',
            url:'/setup/timetempdetails/delete',
            data:{
                id:selected_timetempdetail,
                headerid:$('#input_timetemplate').val()
            },
            success:function(data) {
                if(data[0].status == 1){
                  all_timetempdetail = data[0].data
                  timetempdetail_datatable()
                  if($('#filter_timetemplate').val() != null && $('#filter_timetemplate').val() != ""){
                    get_sched()
                  }
                }
                Toast.fire({
                    type: data[0].icon,
                    title: data[0].message
                })
            },
        })
    }

    function timetempdetail_datatable(){
      $("#timetempdetail_datatable").DataTable({
            destroy: true,
            data:all_timetempdetail,
            lengthChange : false,
            stateSave: true,
            autoWidth: false,
            columns: [
                  { "data": "stime_display" },
                  { "data": "etime_display" },
                  { "data": null },
                  { "data": null },
            ],
            columnDefs: [
              {
                'targets': 2,
                'orderable': false, 
                'createdCell':  function (td, cellData, rowData, row, col) {
                      var buttons = '<a href="javascript:void(0)" class="edit_timetempdetail" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                      $(td)[0].innerHTML =  buttons
                      $(td).addClass('text-center')
                      $(td).addClass('align-middle')
                      
                }
              },
              {
                'targets': 3,
                'orderable': false, 
                'createdCell':  function (td, cellData, rowData, row, col) {
                      var disabled = '';
                      var buttons = '<a href="javascript:void(0)" '+disabled+' class="delete_timetempdetail" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                      $(td)[0].innerHTML =  buttons
                      $(td).addClass('text-center')
                      $(td).addClass('align-middle')
                }
              },
            ],
      });

      var label_text = $($('#timetempdetail_datatable_wrapper')[0].children[0])[0].children[0]
      $(label_text)[0].innerHTML = '<button class="btn btn-sm btn-primary" id="create_timetempdetail_modal_button">Create Time</button>'
                    
    }


    $(document).on('click','#create_timetempdetail_button',function(){
      create_timetempdetail()
    })

    $(document).on('click','#update_timetempdetail_button',function(){
      update_timetempdetail()
    })


    $(document).on('click','#create_timetempdetail_modal_button',function(){
      $('#timetempdetail_input').daterangepicker({
        timePicker: true,
        startDate: '07:30 AM',
        endDate: '08:30 AM',
        timePickerIncrement: 5,
        locale: {
            format: 'hh:mm A',
            cancelLabel: 'Clear'
        }
      })
      $('#update_timetempdetail_button').attr('hidden','hidden')
      $('#create_timetempdetail_button').removeAttr('hidden')
      $('#timetempdeatil_form_modal').modal()
    })


    

    $(document).on('click','.edit_timetempdetail',function(){
      selected_timetempdetail = $(this).attr('data-id')
      var temp_detail = all_timetempdetail.filter(x=>x.id == selected_timetempdetail)
      $('#timetempdetail_input').daterangepicker({
        timePicker: true,
        startDate: temp_detail[0].stime_display,
        endDate: temp_detail[0].etime_display,
        timePickerIncrement: 5,
        locale: {
            format: 'hh:mm A',
            cancelLabel: 'Clear'
        }
      })

      $('#update_timetempdetail_button').removeAttr('hidden')
      $('#create_timetempdetail_button').attr('hidden','hidden')

      $('#timetempdeatil_form_modal').modal()
    })
    
    $(document).on('click','.delete_timetempdetail',function(){
        selected_timetempdetail = $(this).attr('data-id')
        Swal.fire({
                text: 'Are you sure you want to remove detail?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
        }).then((result) => {
                if (result.value) {
                  delete_timetempdetail()
                }
        })
    })


  </script>


  
  <script>
    //time template
    var all_timetemplate = @json($schedtimetemplate);
    var selected_timetemp = null;

    function display_option(){
      $('#input_timetemplate').empty()
      $('#input_timetemplate').append('<option value="">Time Template</option>')
      $('#input_timetemplate').append('<option value="create"><i class="fas fa-plus"></i>Create Time Template</option>')
      $("#input_timetemplate").select2({
              data: all_timetemplate,
              allowClear: true,
              placeholder: "Time Template",
      })
      if(selected_timetemp != null){
        $('#input_timetemplate').val(selected_timetemp).change()
      }
    }

    // get_timetempn()
    function get_timetemp(){
        $.ajax({
            type:'GET',
            url:'/setup/scheduletimetemp/list',
            success:function(data) {
                all_timetemplate = data[0].data
                display_option()
            },
        })
    }

    function create_timetemp(){
        if($('#timetemp_desc').val() == ""){
            Toast.fire({
                type: 'info',
                title: 'Decription is empty!',
            })
            return false
        }
        $.ajax({
            type:'GET',
            url:'/setup/scheduletimetemp/create',
            data:{
                timetemp_desc:$('#timetemp_desc').val()
            },
            success:function(data) {
                if(data[0].status == 1){
                    all_timetemplate = data[0].data
                    display_option()
                    display_time_template()
                }
                Toast.fire({
                    type: data[0].icon,
                    title: data[0].message
                })
            },
        })
    }
    
    function update_timetemp(){
        if($('#timetemp_desc').val() == ""){
            Toast.fire({
                type: 'info',
                title: 'Decription is empty!',
            })
            return false
        }
        $.ajax({
            type:'GET',
            url:'/setup/scheduletimetemp/update',
            data:{
                timetemp_desc:$('#timetemp_desc').val(),
                id:selected_timetemp
            },
            success:function(data) {
                if(data[0].status == 1){
                  all_timetemplate = data[0].data
                  display_time_template()
                  display_option()
                }
                Toast.fire({
                    type: data[0].icon,
                    title: data[0].message
                })
            },
        })
    }

    function delete_timetemp(){
        $.ajax({
            type:'GET',
            url:'/setup/scheduletimetemp/delete',
            data:{
                id:selected_timetemp
            },
            success:function(data) {
                if(data[0].status == 1){
                    all_timetemplate = data[0].data
                    display_time_template()
                    display_option()
                    $('.edit_timetemp').attr('hidden','hidden')
                    $('.delete_timetemp').attr('hidden','hidden')
                }
                Toast.fire({
                    type: data[0].icon,
                    title: data[0].message
                })
            },
        })
    }

    display_option()

    $(document).on('change','#input_timetemplate',function(){
        if($(this).val() != "" && $(this).val() != "create"){
            get_timetempdetail()
            $('.edit_timetemp').removeAttr('hidden')
            $('.delete_timetemp').removeAttr('hidden')
        }else{
            if($(this).val() == "create"){
                selected_timetemp = null
                $('#timetemp_desc').val("")
                $('#create_timetemp_button').removeAttr('hidden')
                $('#update_timetemp_button').attr('hidden','hidden')
                $('#timetemp_form_modal').modal()
            }else{
              all_timetempdetail = []
              timetempdetail_datatable()
            }
            $('.edit_timetemp').attr('hidden','hidden')
            $('.delete_timetemp').attr('hidden','hidden')
        }
    })

    $(document).on('click','#time_templatelist',function(){
        $('#input_timetemplate').val("").change()
        $('#timetemp_modal').modal()
    })
    
    $(document).on('click','#create_timetemp_button',function(){
        create_timetemp()
    })

    $(document).on('click','#update_timetemp_button',function(){
        update_timetemp()
    })

    $(document).on('click','.delete_timetemp',function(){
        selected_timetemp = $('#input_timetemplate').val()
        Swal.fire({
                text: 'Are you sure you want to remove time template?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
        }).then((result) => {
                if (result.value) {
                    delete_timetemp()
                }
        })
    })

    $(document).on('click','.edit_timetemp',function(){
        $('#create_timetemp_button').attr('hidden','hidden')
        $('#update_timetemp_button').removeAttr('hidden')
        $('#timetemp_desc').val($('#input_timetemplate option:selected').text())
        selected_timetemp = $('#input_timetemplate').val()
        $('#timetemp_form_modal').modal()
    })

    // input_timetemplate
  </script>

  <script>
     const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })
  </script>

  <script>

    var selected_detailinfo = null;
    var iscreate = true;

    $(document).ready(function(){

     
      var allowconflict = 0

      $(document).on('click','.remove_sched',function(){

        var days = [];

        $('.day').each(function(){
            if($(this).is(":checked")){
                days.push($(this).val())
            }
        })

        if(days.length == 0){
            Toast.fire({
                type: 'error',
                title: 'No days selected!'
            })
            return false;
        }

        var temp_sched_info = all_sched.filter(x=>x.detailid == $(this).attr('data-id'))

        var copy_com = 0;

    
        if(selected_detailinfo[0].levelid == 14 || selected_detailinfo[0].levelid == 15){
            copy_com = 0;
        }else{
            if($('#apptocon').prop('checked') == true){
                copy_com = 1;
            }
        }
        
        Swal.fire({
                text: 'Are you sure you want to remove schedule?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
        }).then((result) => {
          $.ajax({
                type:'GET',
                url:'/principal/setup/schedule/removesched',
                data:{
                    applycom:copy_com,
                    days:days,
                    dataid:selected_detailinfo[0].detailid,
                    levelid:selected_detailinfo[0].levelid
                },
                success:function(data) {
                      if(data[0].status == 1){
                                Toast.fire({
                                    type: 'success',
                                    title: data[0].message
                                })
                                $('#modal_schedule').modal('hide')
                                get_sched(selected_roomid)
                      }else{
                          Toast.fire({
                              type: 'success',
                              title: data[0].message
                          })
                      }
                        
                }
            })
          })
      })

      $(document).on('change','#secttea, #input_section , #input_subject, #classification, #reservationtime, #time',function(){
        $('#sched_con_holder').attr('hidden','hidden')
        allowconflict = 0
        if(iscreate && selected_detailinfo == null){
            $('.eval').text('Create Schedule')
        }else{
            $('.eval').text('Update Schedule')
        }
      })

      $(document).on('click','.day',function(){
        $('#sched_con_holder').attr('hidden','hidden')
        allowconflict = 0
        if(iscreate  && selected_detailinfo == null){
            $('.eval').text('Create Schedule')
        }else{
            $('.eval').text('Update Schedule')
        }
      })


      $(document).on('click','.edit_sched',function(){

        var temp_sched_info = all_sched.filter(x=>x.detailid == $(this).attr('data-id'))
        selected_detailinfo = temp_sched_info
        $('#apptocon').prop('checked',false)

        if($(this).attr('iscon') == 1){
            $('#apptocon_holder').removeAttr('hidden')
           
        }else{
            $('#apptocon_holder').attr('hidden','hidden')
        }

        $('.remove_sched').removeAttr('hidden')


        iscreate = false

        if(iscreate){
            $('.eval').text('Create Schedule')
            $('.eval').addClass('btn-primary')
            $('.eval').removeClass('btn-success')
        }else{
            $('.eval').text('Update Schedule')
            $('.eval').addClass('btn-success')
            $('.eval').addClass('btn-primary')
          
        }
        
 
        $('.eval').removeClass('.evalupdate')
        $('.apptocon').prop('checked',false)

        $('#sched_con_holder').attr('hidden','hidden')
        $('#con_stat').text("")
        $('#con_sect').text("")
        $('#con_subj').text("")
        $('#con_day').text("")
        $('#con_time').text("")
        allowconflict = 0
        conflicts = []

        $('#conflict_holder').empty()

        $('.evalupdate').addClass('eval')
        $('.eval').removeClass('.evalupdate')
        $('#modal_schedule').modal()


        $('#secttea').val(temp_sched_info[0].teacherid).change()
        $('#input_section').val(temp_sched_info[0].sectionid).change()
        $('#input_subject').val(temp_sched_info[0].subjid).change()
        $('#classification').val(temp_sched_info[0].schedclassid).change()

        $('.day').prop('checked',false)
        $('.eval').removeAttr('disabled')



        $('.reservationtime').daterangepicker({
            timePicker: true,
            startDate: temp_sched_info[0].stime,
            endDate: temp_sched_info[0].etime,
            timePickerIncrement: 5,
            locale: {
                format: 'hh:mm A',
                cancelLabel: 'Clear'
            }
        })

        var temp_days = all_sched.filter(x=>x.time == temp_sched_info[0].time && x.subjid == temp_sched_info[0].subjid && x.schedclassid == temp_sched_info[0].schedclassid)


        $.each(temp_days,function(a,b){
          $('.day[value="'+b.days+'"]').prop('checked',true)
        })

        selectedSubject = $(this).attr('data-id')
    
        $('#conflict_holder').empty()
      })

      $(document).on('click','.eval',function(){

        var section = all_sections.filter(x=>x.id == $('#input_section').val())[0]

        $('#modal-primary').modal('hide')
        var days = [];
        var valid_data = true

        $('.day').each(function(){
            if($(this).is(":checked")){
                days.push($(this).val())
            }
        })

        if(days.length == 0){
            Toast.fire({
                type: 'error',
                title: 'No days selected!'
            })
            valid_data= false
        }

        if($('#classification').val() == ""){
            Toast.fire({
                type: 'error',
                title: 'No Classification Selected'
            })
            valid_data= false
        }

        var copy_com = 0;

          if(valid_data){

              var temp_url = section.acadprogid == 5 ? '/principal/setup/schedule/sh/add' : '/principal/setup/schedule/gshs/add'
              $(this).attr('disabled','disabled')

              if(section.acadprogid == 5){
                  copy_com = 0;
              }else{
                  if($('#apptocon').prop('checked') == true){
                      copy_com = 1;
                  }
              }
      
          $.ajax({
              type:'GET',
              url:temp_url,
              data:{
                  applycom:copy_com,
                  section:$('#input_section').val(),
                  t:$('#time').val(),
                  s:$('#input_subject').val(),
                  tea:$('#secttea').val(),
                  r:selected_roomid,
                  days:days,
                  class:$('#classification').val(),
                  syid:$('#filter_acad_sy').val(),
                  semid:$('#filter_semester').val(),
                  allowconflict:allowconflict,
                  iscreate:iscreate,
                  schedinfo:selected_detailinfo
              },
              success:function(data) {
                      if(data[0].status == 1){
                              if(iscreate){
                                  $('.eval').text('Create Schedule')
                              }else{
                                  $('.eval').text('Update Schedule')
                              }
                              Toast.fire({
                                  type: 'success',
                                  title: 'Successful!'
                              })
                              allowconflict = 0
                              get_sched(selected_roomid)
                              $('#sched_con_holder').attr('hidden','hidden')
                              $('#sched_conflict_holder').empty()
                              $('#con_stat').text("")
                              $('#con_sect').text("")
                              $('#con_subj').text("")
                              $('#con_day').text("")
                              $('#con_time').text("")
                      }else{
                              if(data[0].data == 'conflict'){
                                  $('#conflict_holder').empty()
                                  $('#sched_con_holder').removeAttr('hidden')
                                  $('#sched_conflict_holder').empty()

                                  $.each(data[0].details,function(a,b){

                                    var contype = ''
                                    if(b.conflict == 'RSC'){
                                      contype = 'Room'
                                    }else if(b.conflict == 'TSC'){
                                      contype = 'Teacher'
                                    }else if(b.conflict == 'SSC'){
                                      contype = 'Section'
                                    }

                                    var text = '<div class="col-md-6"><div class="card" style="font-size:.8rem !important"><div class="card-body p-2"><p class="mb-0">Conflict: '+contype+'</p><p class="mb-0">Subject : '+b.subject+'</p><p class="mb-0">Section : '+b.section+'</p><p class="mb-0">Day : '+b.days+'</p><p class="mb-0">Room : '+b.room+'</p></div></div></div>'

                                    $('#conflict_holder').append(text)
                                  })
                                  // $('#con_stat')[0].innerHTML = '<span class="text-danger">'+data[0].conflicttype+'</span>'
                                  // $('#con_sect').text(data[0].section)
                                  // $('#con_subj').text(' ( '+data[0].subjcode+' ) '+data[0].subjdesc)
                                  // $('#con_day').text(data[0].day)
                                  // $('#con_time').text(data[0].time)
                                  Toast.fire({
                                          type: 'error',
                                          title: 'Schedule Conflict'
                                  })
                                  if(iscreate){
                                      $('.eval').text('Conflict : Proceed Create')
                                  }else{
                                      $('.eval').text('Conflict : Proceed Update')
                                  }
                                
                                  allowconflict = 1
                              }else{
                                  Toast.fire({
                                          type: 'error',
                                          title: data[0].data
                                  })
                                  $('.eval').text('Update Schedule')
                                  allowconflict = 0
                              }
                      }

                      $('.eval').removeAttr('disabled')
              }
          })
        }

      })
    })
  </script>

  <script>
    var all_sections = []
    var all_subjects = []
    var all_teacher = @json($teacher);
    

    $('#secttea').empty()
    $('#secttea').append('<option value="">Select Teacher</option>')
    $("#secttea").select2({
            data: all_teacher,
            allowClear: true,
            placeholder: "Select Teacher",
    })

    display_time_template()

    function display_time_template(){

      var temp_id = null
      if($('#filter_timetemplate').val() != null && $('#filter_timetemplate').val() != ''){
        temp_id = $('#filter_timetemplate').val()
      }
      $('#filter_timetemplate').empty()
      $('#filter_timetemplate').append('<option value="">Time Template</option>')
      $("#filter_timetemplate").select2({
              data: all_timetemplate,
              allowClear: true,
              placeholder: "Time Template",
      })
      if(temp_id != null){
        $('#filter_timetemplate').val(temp_id).change()
      }
    }

    

    
    
    function getsections(){
        $.ajax({
            type:'GET',
            url:'/rooms/getsections',
            data:{
              syid:$('#filter_acad_sy').val()
            },
            success:function(data) {
              if(data[0].status == 1){
                all_sections = data[0].data
                $('#input_section').empty()
                $('#input_section').append('<option value="">Select Section</option>')
                $("#input_section").select2({
                        data: all_sections,
                        allowClear: true,
                        placeholder: "Select Section",
                })
              }else{

              }
            },
        })
    }

    

    function getsubjects(sectionid){

      var temp_levelid = all_sections.filter(x=>x.id == sectionid)[0].levelid

        $.ajax({
            type:'GET',
            url:'/rooms/getsubjects',
            data:{
              syid:$('#filter_acad_sy').val(),
              semid:$('#filter_semester').val(),
              sectionid:sectionid,
              levelid:temp_levelid
            },
            success:function(data) {
              if(data[0].status == 1){
                all_subjects = data[0].data
                $('#input_subject').empty()
                $('#input_subject').append('<option value="">Select Subject</option>')
                $("#input_subject").select2({
                        data: all_subjects,
                        allowClear: true,
                        placeholder: "Select Subject",
                })

                if(selected_detailinfo != null){
                  $('#input_subject').val(selected_detailinfo[0].subjid).change()
                }


              }else{

              }
            },
        })
    }

    getsections()

    $(document).on('change','#input_section',function(){
      if($(this).val() == "" || $(this).val() == null){
        all_subjects = []
        $('#input_subject').empty()
        $('#input_subject').append('<option value="">Select Subject</option>')
        $("#input_subject").select2({
                data: all_subjects,
                allowClear: true,
                placeholder: "Select Subject",
        })
      }else{

        var temp_sectionid = $(this).val()
        var temp_sectioninfo = all_sections.filter(x=>x.id == temp_sectionid)

        if(selected_detailinfo != null){
          $('#secttea').val(selected_detailinfo[0].teacherid).change()
        }
        else if(temp_sectioninfo.length > 0){
          $('#secttea').val(temp_sectioninfo[0].teacherid).change()
        }
      
        getsubjects(temp_sectionid)
      }

      
      
    })

    $(document).on('change','#input_subject',function(){
      var temp_subjid = $(this).val()
      if(temp_subjid != "" && temp_subjid != null){
        var temp_subjinfo = all_subjects.filter(x=>x.id == temp_subjid)
        if(temp_subjinfo[0].subjCom != null){
            $('#apptocon_holder').removeAttr('hidden')
        }else{
            $('#apptocon_holder').attr('hidden','hidden')
        }
      }
     
    })


  </script>

  <script>
    //schedule classification
    var scheduleclassification = []
    var selected_schedclass = null
    get_schedclassification()
    function get_schedclassification(){
        $.ajax({
            type:'GET',
            url:'/setup/scheduleclassification/list',
            success:function(data) {
                scheduleclassification = data
                $('#classification').empty()
                $('#classification').append('<option value="">Schedule Classification</option>')
                $('#classification').append('<option value="create"><i class="fas fa-plus"></i>Create Schedule Classification</option>')
                $("#classification").select2({
                        data: scheduleclassification,
                        allowClear: true,
                        placeholder: "Schedule Classification",
                })
            },
        })
    }
    function create_schedclassification(){

        if($('#schedclass_desc').val() == ""){
            Toast.fire({
                type: 'info',
                title: 'Decription is empty!',
            })
            return false
        }

        $.ajax({
            type:'GET',
            url:'/setup/scheduleclassification/create',
            data:{
                schedclass_desc:$('#schedclass_desc').val()
            },
            success:function(data) {
                if(data[0].status == 1){
                    scheduleclassification = data[0].data
                    $('#classification').empty()
                    $('#classification').append('<option value="">Schedule Classification</option>')
                    $('#classification').append('<option value="create"><i class="fas fa-plus"></i>Create Schedule Classification</option>')
                    $("#classification").select2({
                            data: scheduleclassification,
                            allowClear: true,
                            placeholder: "Schedule Classification",
                    })
                }

                Toast.fire({
                    type: data[0].icon,
                    title: data[0].message
                })
                
            },
        })
    }
    function update_schedclassification(){
        $.ajax({
            type:'GET',
            url:'/setup/scheduleclassification/update',
            data:{
                schedclass_desc:$('#schedclass_desc').val(),
                id:selected_schedclass
            },
            success:function(data) {
                if(data[0].status == 1){
                    scheduleclassification = data[0].data
                    $('#classification').empty()
                    $('#classification').append('<option value="">Schedule Classification</option>')
                    $('#classification').append('<option value="create"><i class="fas fa-plus"></i>Create Schedule Classification</option>')
                    $("#classification").select2({
                            data: scheduleclassification,
                            allowClear: true,
                            placeholder: "Schedule Classification",
                    })
                    $('#classification').val(selected_schedclass).change()
                }

                Toast.fire({
                    type: data[0].icon,
                    title: data[0].message
                })
            },
        })
    }
    function delete_schedclassification(){
        $.ajax({
            type:'GET',
            url:'/setup/scheduleclassification/delete',
            data:{
                id:selected_schedclass
            },
            success:function(data) {
                if(data[0].status == 1){
                    scheduleclassification = data[0].data
                    $('#classification').empty()
                    $('#classification').append('<option value="">Schedule Classification</option>')
                    $('#classification').append('<option value="create"><i class="fas fa-plus"></i>Create Schedule Classification</option>')
                    $("#classification").select2({
                            data: scheduleclassification,
                            allowClear: true,
                            placeholder: "Schedule Classification",
                    })
                    $('.edit_schedclass').attr('hidden','hidden')
                    $('.delete_schedclass').attr('hidden','hidden')
                }

                Toast.fire({
                    type: data[0].icon,
                    title: data[0].message
                })
            },
        })
    }
    $(document).on('change','#classification',function(){
        if($(this).val() != "" && $(this).val() != "create"){
            $('.edit_schedclass').removeAttr('hidden')
            $('.delete_schedclass').removeAttr('hidden')
        }else{
            if($(this).val() == "create"){
                selected_schedclass = null
                $('#schedclass_desc').val("")
                $('#classification').val("").change()
                $('#create_schedclass_button').removeAttr('hidden')
                $('#update_schedclass_button').attr('hidden','hidden')
                $('#schedclass_form_modal').modal()
            }
            $('.edit_schedclass').attr('hidden','hidden')
            $('.delete_schedclass').attr('hidden','hidden')
        }
    })
    
    $(document).on('click','#create_schedclass_button',function(){
        create_schedclassification()
    })

    $(document).on('click','#update_schedclass_button',function(){
        update_schedclassification()
    })


    $(document).on('click','.delete_schedclass',function(){
        selected_schedclass = $('#classification').val()

        Swal.fire({
                text: 'Are you sure you want to remove schedule classification?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
        }).then((result) => {
                if (result.value) {
                    delete_schedclassification()
                }
        })
    })

    $(document).on('click','.edit_schedclass',function(){
        $('#create_schedclass_button').attr('hidden','hidden')
        $('#update_schedclass_button').removeAttr('hidden')
        $('#schedclass_desc').val($('#classification option:selected').text())
        selected_schedclass = $('#classification').val()
        $('#schedclass_form_modal').modal()
    })
  </script>


  <script>
    $(document).ready(function(){

      // $('#secttea').select2()
      $('#sectroo').select2()
      $('#input_section').select2()
      $('#input_subject').select2()
      

      $(document).on('click','.add_sched',function(){
        
        selected_detailinfo = null

        $('#apptocon').prop('checked',false)
        if($(this).attr('iscon') == 1){
            $('#apptocon_holder').removeAttr('hidden')
        }else{
            $('#apptocon_holder').attr('hidden','hidden')
        }

        $('.remove_sched').attr('hidden','hidden')

        iscreate = true

        if(iscreate){
            $('.eval').text('Create Schedule')
        }else{
            $('.eval').text('Update Schedule')
        }

        $('.eval').addClass('btn-primary')
        $('.eval').removeClass('btn-success')
        $('.eval').removeClass('.evalupdate')
        $('.apptocon').prop('checked',false)

        $('#sched_con_holder').attr('hidden','hidden')
        $('#con_stat').text("")
        $('#con_sect').text("")
        $('#con_subj').text("")
        $('#con_day').text("")
        $('#con_time').text("")
        allowconflict = 0
        conflicts = []
        $('#conflict_holder').empty()
        $('.evalupdate').addClass('eval')
        $('.eval').removeClass('.evalupdate')
        $('#modal_schedule').modal()
        $('#secttea').val("").change()
        $('#input_subject').val("").change()
        $('#input_section').val("").change()
        
        $('#sectroo').val("").change()
        $('.day').prop('checked',false)
        $('.eval').removeAttr('disabled')


        if($(this).attr('data-stime') != null){
          $('.reservationtime').daterangepicker({
            timePicker: true,
            startDate: $(this).attr('data-stime'),
            endDate: $(this).attr('data-etime'),
            timePickerIncrement: 5,
            locale: {
                format: 'hh:mm A',
                cancelLabel: 'Clear'
            }
          })
        }else{

          $('.reservationtime').daterangepicker({
            timePicker: true,
            startDate: '07:30 AM',
            endDate: '08:30 AM',
            timePickerIncrement: 5,
            locale: {
                format: 'hh:mm A',
                cancelLabel: 'Clear'
            }
          })
        }

        
        var temp_classid = $('#classification option:eq(2)').val()
        $('#classification').val(temp_classid).change()
        // add_sched

        $('#time').removeAttr('disabled')
        selectedSubject = $(this).attr('data-id')
        // var temp_sched = all_sched.filter(x=>x.subjid == selectedSubject)

        // $('#secttea').val(section.teacherid).change()
        // $('#sectroo').val(section.roomid).change()

        // $.each(temp_sched[0].schedule,function(a,b){
        //     $('#secttea').val(b.teacherid).change()
        //     $('#sectroo').val(b.roomid).change()
          
        // })


        // $('#subject_desc').val(temp_sched[0].subjcode + ' - '+temp_sched[0].subjdesc )
        $('#conflict_holder').empty()
      })

     

      var select_id = null

      $(document).on('click','#create_room_button',function(){
        $('#roomName').val("")
        $('#roomCapacity').val("")
        $('#building').val("").change()
        $('#room_form_modal').modal()
        $('#create_room').text('Create')
        $('#create_room').removeClass('btn-success')
        $('#create_room').addClass('bg-primary')
        $('#create_room').attr('data-id',1)
        select_id = null
      })

      $(document).on('change','#filter_acad_sy',function(){
        get_sched(selected_roomid)
      })

      $(document).on('change','#filter_semester',function(){
        get_sched(selected_roomid)
      })

      $(document).on('change','#filter_timetemplate',function(){
        get_sched(selected_roomid)
      })

      
      $(document).on('click','#print_sched',function(){
          var temp_roomid = $(this).attr('data-id')
          window.open('/principal/setup/section/print?schedtype=room&syid='+$('#filter_acad_sy').val()+'&semid='+$('#filter_semester').val()+'&roomid='+temp_roomid+'&timetemp='+$('#filter_timetemplate').val(), '_blank');
      })

      $(document).on('click','.view_info',function(){
        var temp_id = $(this).attr('data-id')
        var data = all_rooms.filter(x=>x.id == temp_id)
        select_id = temp_id

        $('#print_sched').attr('data-id',select_id)

        console.log(data[0].buildingid)

        $('#update_roomname').val(data[0].roomname)
        $('#update_roomcap').val(data[0].capacity)

        
        // $('#update_roombuilding').val(data[0].buildingid).change()

        $('#update_roombuilding').empty()
        $('#update_roombuilding').append('<option value="' + data[0].buildingid + '">'+data[0].description+'</option>')

        $('#room_name').text(data[0].roomname)
        // $('#room_form_modal').modal()
        $('#create_room').text('Update')
        $('#create_room').removeClass('btn-primary')
        $('#create_room').addClass('btn-success')
        $('#create_room').attr('data-id',2)
      })

      $(document).on('click','#create_room',function(){

        var roomname = $('#roomName').val()
        var capacity = $('#roomCapacity').val()
        var building = $('#building').val()

        if($(this).attr('data-id') == 1){
          var check_duplicate = all_rooms.filter(x=>x.roomname == roomname)
        }else{
          var check_duplicate = all_rooms.filter(x=>x.roomname == roomname && x.id != select_id)
        }
       
       
        
        if(check_duplicate.length > 0){
            Toast.fire({
              type: 'warning',
              title: 'Already Exist'
            })
            return false;
        }

        if(roomname == ""){
          Toast.fire({
            type: 'warning',
            title: 'Room Name is empty'
          })
          return false;
        }
        
        if(capacity == ""){
          Toast.fire({
            type: 'warning',
            title: 'Capacity is empty'
          })
          return false;
        }

        if(building == ""){
          Toast.fire({
            type: 'warning',
            title: 'Building is empty'
          })
          return false;
        }
        
        if($(this).attr('data-id') == 1){
          create_room()
        }else{
          update_room()
        }

      })

      $(document).on('click','.delete_room',function(){
        select_id = $(this).attr('data-id')
        delete_room()
      })

      var all_rooms = []
      var all_building = []

      rooms_datatable()
      // get_buildings()
      
      // function get_buildings(){
      //   $.ajax({
			// 		type:'GET',
			// 		url: '/buildings/get',
			// 		success:function(data) {
      //       building = data;     
      //       $("#building").select2({
      //             data: building,
      //             allowClear: true,
      //             placeholder: "Select Building",
      //       })

      //       $("#update_roombuilding").select2({
      //             data: building,
      //             allowClear: true,
      //             placeholder: "Select Building",
      //       })
			// 		}
			// 	})
      // }

      $('#building , #update_roombuilding').select2({
                placeholder: "All",
                allowClear:true,
                delay: 250 ,
                ajax: {
                      url: '/api/buildings/select',
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
                            console.log(data.pagination.more)
                            return {
                                  results: data.results,
                                  pagination: {
                                    more: data.pagination.more
                                  }
                            };
                      }
                }
          });

      function create_room(){
        $('#create_room').attr('disabled','disabled')
        $.ajax({
					type:'GET',
					url: '/rooms/create',
          data:{
            roomname:$('#roomName').val(),
            capacity:$('#roomCapacity').val(),
            building:$('#building').val(),
          },
					success:function(data) {
            $('#create_room').removeAttr('disabled')
            if(data[0].status == 1){
              rooms_datatable()
            }
            prompt(data[0].status,data[0].message)
					},
          error:function(){
            prompt(0,'Something went wrong')
          }
				})
      }

      function delete_room(){
        $.ajax({
					type:'GET',
					url: '/rooms/delete',
          data:{
            id:select_id,
          },
					success:function(data) {
            if(data[0].status == 1){
              rooms_datatable()
            }
            prompt(data[0].status,data[0].message)
					},
          error:function(){
            prompt(0,'Something went wrong')
          }
				})
      }


      function prompt(status,message){
          if(status == 1){
            var type = 'success'
          }else if(status == 2){
            var type = 'warning'
          }else if(status == 0){
            var type = 'error'
          }
          Toast.fire({
            type: type,
            title: message
          })
      }

      $(document).on('click','#update_information',function(){
        update_room()
      })
      
      function update_room(){
        $('#create_room').attr('disabled','disabled')
        $.ajax({
					type:'GET',
					url: '/rooms/update',
          data:{
            id:select_id,
            roomname:$('#update_roomname').val(),
            capacity:$('#update_roomcap').val(),
            building:$('#update_roombuilding').val(),
          },
					success:function(data) {
            $('#create_room').removeAttr('disabled')
            if(data[0].status == 1){
              Toast.fire({
                type: 'success',
                title: 'Room Updated!'
              })
              $('#room_name').text($('#update_roomname').val())
              rooms_datatable()
            }else{
              Toast.fire({
                type: 'error',
                title: 'Something went wrong!'
              })
            }
					},
          error:function(){
            $('#create_room').removeAttr('disabled')
            Toast.fire({
              type: 'error',
              title: 'Something went wrong!'
            })
          }
				})
      }

      function rooms_datatable(){

        $("#rooms_datatable").DataTable({
              destroy: true,
              // data:all_rooms,
              lengthChange : false,
              stateSave: true,
              autoWidth: false,
              serverSide: true,
              processing: true,
              ajax:{
                  url: 'rooms/get',
                  type: 'GET',
                  dataSrc: function ( json ) {
                        all_rooms = json.data
                        return json.data;
                  }
              },
              columns: [
                    { "data": "roomname" },
                    { "data": "description" },
                    { "data": "capacity" },
                    { "data": null },
                    { "data": null },
                    { "data": null },
                    { "data": null },
              ],
              columnDefs: [
                {
                  'targets': 2,
                  'orderable': false, 
                  'createdCell':  function (td, cellData, rowData, row, col) {
                       $(td)[0].innerHTML = rowData.capacity
                       $(td).addClass('align-middle')
                       $(td).addClass('text-center')
                  }
                },
                

                {
                  'targets': 3,
                  'orderable': false, 
                  'createdCell':  function (td, cellData, rowData, row, col) {
                        // var buttons = '<button style="font-size:.7rem !important" class="view_sched btn btn-sm btn-primary btn-block" data-id="'+rowData.id+'">View Sched</button>';
                        // $(td)[0].innerHTML =  buttons
                        $(td).text(null)
                        $(td).addClass('text-center')
                        $(td).addClass('align-middle')
                        
                  }
                },

                {
                  'targets': 4,
                  'orderable': false, 
                  'createdCell':  function (td, cellData, rowData, row, col) {
                        // var buttons = '<button class="btn btn-primary btn-sm" data-id="'+rowData.id+'">View Schedule</button>';
                        // $(td)[0].innerHTML =  buttons
                        // $(td).addClass('text-center')
                        // $(td).addClass('align-middle')
                        $(td).text(null)
                        
                  }
                },
                {
                  'targets': 5,
                  'orderable': false, 
                  'createdCell':  function (td, cellData, rowData, row, col) {
                        // var buttons = '<a href="javascript:void(0)" class="edit_room" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                        // $(td)[0].innerHTML =  buttons
                        // $(td).addClass('text-center')
                        // $(td).addClass('align-middle')
                        $(td).text(null)
                        
                  }
                },
                {
                  'targets': 6,
                  'orderable': false, 
                  'createdCell':  function (td, cellData, rowData, row, col) {
                        // var disabled = '';
                        // var buttons = '<a href="javascript:void(0)" '+disabled+' class="delete_room" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                        // $(td)[0].innerHTML =  buttons
                        // $(td).addClass('text-center')
                        // $(td).addClass('align-middle')
                        $(td).text(null)
                  }
                },

              ],
              createdRow: function (row, data, dataIndex) {
                    $(row).attr("data-id",data.id);
                    $(row).addClass("view_info");
              },
        });

        var label_text = $($('#rooms_datatable_wrapper')[0].children[0])[0].children[0]
        $(label_text)[0].innerHTML = '<button class="btn btn-sm btn-primary" title="Room" id="create_room_button">Create Room</button>'
                        
                  

        // return temp_room;

      }

      $(document).on('click','.view_info',function(){
        $('#view_roominfo_modal').modal()
        selected_roomid = $(this).attr('data-id')
        get_sched(selected_roomid)
      })


     
        
    })

    var selected_roomid = null

  </script>

  <script>

    var all_sched = []

     function get_sched(roomid){
        $('#sched_holder').empty()
        $.ajax({
					type:'GET',
					url: '/principal/setup/schedule/get/sched',
          data:{
            roomid:roomid,
            syid:$('#filter_acad_sy').val(),
            semid:$('#filter_semester').val(),
            schedtype:'room',
            timetemp:$('#filter_timetemplate').val()
          },
					success:function(data) {
            all_sched = data[0].sched
            var temp_sched = data[0].sched
            var temp_daylist = data[0].day_list
            var temp_timetemp = data[0].time_temp
            var temp_timelist = data[0].time_list
            var temp_width =  88 / temp_daylist.length;

            //display header
            if(temp_daylist.length != 0){
              var text = '<thead><tr><th width="12%" style="background-color:CornflowerBlue; color:white" class="text-center"></th>';
              $.each(temp_daylist, function(a,b){
                text += '<th width="'+temp_width+'%"  class="text-center align-middle" style="background-color:CornflowerBlue; color:white">'+b+'</th>'
              })
              text += '</tr></thead><tbody>'
              $('#sched_holder').append(text)

              $.each(temp_timelist, function(a,b){
                var timeinfo = temp_sched.filter(x=>x.time == b)[0];
                var timetemp_display = '<span class="text-danger">Time Template</span>'
                var check_timetemp = temp_timetemp.filter(x=>x.time == b)

                if(check_timetemp.length == 0){
                  timetemp_display = '<span class="text-danger">Customize</span>'
                }


                var text = '<tr><td class="text-center align-middle"><p class="mb-0">'+timetemp_display+'</p><b><span style="font-size:.8rem !important">'+timeinfo.stime+'<br>'+timeinfo.etime+'</b></span><p class="mb-0"><a href="javascript:void(0)" class="add_sched " data-stime="'+timeinfo.stime+'" data-etime="'+timeinfo.etime+'">Add Schedule</a></p>'+'</td>';
                $.each(temp_daylist, function(c,d){
                  var day_sched = temp_sched.filter(x=>x.description == d && x.time == b)
                  var temp_bg = '';
                  
                  if(day_sched.length > 1){
                    temp_bg = 'bg-warning'
                  }

                  text += '<td class="text-center align-middle '+temp_bg+'">'

                  $.each(day_sched, function(e,f){
                    text += '<p  class="mb-0"><b><a href="javascript:void(0)" class="edit_sched" data-id="'+f.detailid+'">'+f.subjdesc+'</a></b></p>'
                    text += '<p style="font-size:.6rem !important" class="mb-0">'+f.sectionname+'</p>'
                    text += '<p style="font-size:.6rem !important;"  class="mb-0"><i>'+f.teacher+'</i></p>'
                    text += ' <p style="font-size:.6rem !important; color:red" class="mb-1">'+f.classification+'</p>'
                  })

                  text += '</td>'
                })
                text += '</tr>'
                $('#sched_holder').append(text)
              })
              text = '</tbody>'
              $('#sched_holder').append(text)
            }else{

              var text = '<tr><td class="text-center" width="100%">No Schedule Available</td></tr>'
              $('#sched_holder').append(text)
            }

					},
          error:function(){
            $('#create_room').removeAttr('disabled')
            Toast.fire({
              type: 'error',
              title: 'Something went wrong!'
            })
          }
				})
        
      }
  </script>
    
@endsection

