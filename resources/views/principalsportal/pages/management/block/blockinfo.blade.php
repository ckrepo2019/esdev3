@if(auth()->user()->type == 2)

    @php
        $xtend = 'principalsportal.layouts.app2';
    @endphp

@else

    @php
        $refid = DB::table('usertype')->where('id',auth()->user()->type)->where('deleted',0)->select('refid')->first();
    @endphp
    
    @if( $refid->refid == 20)
        @php
            $xtend = 'principalassistant.layouts.app2';
        @endphp
    @elseif( $refid->refid == 22)
        @php
            $xtend = 'principalcoor.layouts.app2';
        @endphp
    @endif

@endif

@extends($xtend)

@section('pagespecificscripts')

    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

    <style>
        .smfont{
            font-size:14px;
        }
        /* .select2-container--default .select2-selection--single, .form-control {
            border-radius: 0 !important; 
            font-size:14px !important;
        } */
        .calendar-table{
            display: none;
        }
        .drp-buttons{
            display: none !important;
        }
        #et{
            height: 10px;
            visibility: hidden;
        }
       
    </style>

@endsection

@section('modalSection')
    <div class="modal fade" id="modal_teacher" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                    <div class="modal-header ">
                        <h4 class="modal-title">Update Teacher
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <label>Teacher</label>
                        <select data-placeholder="Select a teacher"  name="newteacher" id="newteacher" class="form-control select2">
                                <option value="">Select Teacher</option>
                                @foreach(App\Models\Principal\SPP_Teacher::filterTeacherFaculty(null,null,null,1,null,5)[0]->data as $item)
                                    <option value="{{$item->id}}" >{{$item->lastname.', '.$item->firstname}}</option>
                                @endforeach 
                        </select>
                    </div>
                    <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-primary newteacher_button">Proceed</button>
                    </div>
                    
            </div>
        </div>
    </div>
    <div class="modal fade" id="block_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Class Schedule</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
                {{-- <input id="temp" type="hidden">
                <div class="scheduleInfo mt-1">
                </div>
                <hr>
                <div class="form-group">
                    <label for="">Schedule classification</label>
                    <select name="classification" id="classification" class="form-control">
                        @foreach(DB::table('schedclassification')->where('deleted','0')->get() as $item)
                            <option value="{{$item->id}}">{{$item->description}}</option>
                        @endforeach
                       
                    </select>
                </div> --}}
                <div class="form-group">
                    <label>Teacher</label>
                    <select data-placeholder="Select a teacher"  name="secttea" id="secttea" class="form-control select2">
                            <option value="">Select Teacher</option>
                            @foreach(App\Models\Principal\SPP_Teacher::filterTeacherFaculty(null,null,null,1,null,5)[0]->data as $item)
                                <option value="{{$item->id}}" >{{$item->lastname.', '.$item->firstname}}</option>
                            @endforeach 
                    </select>
                </div>
                <div class="form-group">
                    <label>Room</label>
                    <select data-placeholder="Select a Room" class="form-control select2 @error('r') is-invalid @enderror"  name="sectroo" id="sectroo" style="width: 100%;">
                            @php
                                $vacantRooms = App\Models\Principal\SPP_Rooms::getRooms(null,null,null,null);
                            @endphp
                            <option value="" selected disabled>Select Room</option>
                            @foreach ($vacantRooms[0]->data  as $room)
                                <option value="{{$room->id}}">{{$room->roomname}}</option>
                            @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Time</label>
                    <input type="text" class="form-control reservationtime" name="time" id="time">
                </div>
                <div class="form-group">
                    <label for="">Schedule classification</label>
                    <select name="classification" id="classification" class="form-control">
                        @foreach(DB::table('schedclassification')->where('deleted','0')->get() as $item)
                            <option value="{{$item->id}}">{{$item->description}}</option>
                        @endforeach
                       
                    </select>
                </div>
                <p>Apply changes to:</p>
                <div class="form-group clearfix">
                    <div class="icheck-primary d-inline mr-3">
                      <input type="checkbox" id="Mon" class="day" value="1" >
                      <label for="Mon">Mon
                      </label>
                    </div>
                    <div class="icheck-primary d-inline mr-3">
                        <input type="checkbox" id="Tue" class="day" value="2" >
                        <label for="Tue">Tue
                        </label>
                    </div>
                    <div class="icheck-primary d-inline mr-3">
                        <input type="checkbox" id="Wed" class="day" value="3" >
                        <label for="Wed">Wed
                        </label>
                    </div>
                    <div class="icheck-primary d-inline mr-3">
                        <input type="checkbox" id="Thu" class="day" value="4" >
                        <label for="Thu">Thu
                        </label>
                    </div>
                    <div class="icheck-primary d-inline mr-3">
                        <input type="checkbox" id="Fri" class="day" value="5" >
                        <label for="Fri">Fri
                        </label>
                    </div>
                    <div class="icheck-primary d-inline mr-3">
                        <input type="checkbox" id="Sat" class="day" value="6" >
                        <label for="Sat">Sat
                        </label>
                    </div>
                    <div class="retun-message mt-1">

                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-primary eval">Proceed</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="modal-block" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">Section Form</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            </div>
            <form action="/principalupdateblock" method="GET">
                <div class="modal-body">
                    <input name="id" hidden value="{{$blockinfo->id}}">
                    <div class="form-group">
                        <label>Block Name</label>
                        <input value="{{$blockinfo->blockname}}" name="bn" class="form-control  @error('bn') is-invalid @enderror" id="bn" placeholder="Enter section name">
                        @if($errors->has('bn'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('bn') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Gradelevel</label>
                        <select class="form-control @if (\Session::has('gradelevel')) is-invalid @endif" name="gradelevel" id="gradelevel">
                            <option value="" selected disabled>Select Gradelevel</option>
                            @foreach (DB::table('gradelevel')->where('deleted',0)->where('acadprogid',5)->get() as $item)
                                @if($item->id == $blockinfo->levelid)
                                    <option value="{{$item->id}}" selected>{{$item->levelname}}</option>
                                @else
                                    <option value="{{$item->id}}" >{{$item->levelname}}</option>
                                @endif
                               
                            @endforeach
                        </select>
                        @if (\Session::has('gradelevel'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{\Session::get('si')->message}}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Strand</label>
                        <select class="form-control  @error('si') is-invalid @enderror" name="si" id="si">
                            <option value="" selected>Select Strand</option>
                            @foreach (\App\Models\Principal\SPP_Strand::loadSHStrands() as $item)
                                <option {{ $blockinfo->strandid == $item->id ? 'selected' : '' }} value="{{$item->id}}" >{{$item->strandname}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('si'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('si') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button onClick="this.form.submit(); this.disabled=true; " type="submit" class="btn btn btn-outline-success submitform"  data-toggle="modal" data-target="#modal-section"><i class="far fa-edit mr-1"></i>Save</button>
                </div>
            </form>
            </div>
        </div>
    </div>

@endsection

@section('content')

<section class="content-header">
    
</section>

<section class="content-header">
    @if(isset($blockinfo))
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        @if(auth()->user()->type == 2)
                            {{-- <div class="card-tools">
                                <div class="btn-group">
                                    <small><button type="button" class="btn btn-primary btn-xs mr-1 ml-2" id="e">
                                        Edit Schedule
                                    </button> </small>
                                </div>
                            </div> --}}
                        @endif
                        <h3 class="card-title">Block Schedule
                        </h3>
                        <div class="options">
                            <div id="message"></div>
                        </div>
                        
                    </div>
                    <div class="card-body table-responsive p-0" id="cs" style="height: 400px;">
                        <table class="table smfont table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%"></th>
                                    <th width="35%">Subject</th>
                                    <th width="20%">Day</th>
                                    <th width="12%">Time</th>
                                    <th width="13%" >Room</th>
                                    <th width="15%">Teacher</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody class="schedule" id="scheduletable">
                               
                                {{-- @foreach ($testingSched as $key=>$item) --}}
                                    {{-- <tr id="{{$item->subject->csid}}">
                                         <td class="align-middle tablesub" id="{{$item->subject->id}}">{{$item->subject->subjcode}}</td>
                                        @foreach($item->datetime as $sched)
                                            <td class="p-1 align-middle text-center" id="">{{$sched->daysum}}</td>
                                            <td class="p-1 align-middle text-center">
                                                {{\Carbon\Carbon::create($sched->scheddetail->stime)->isoFormat('hh : mm a')}}
                                                <br>
                                                {{\Carbon\Carbon::create($sched->scheddetail->etime)->isoFormat('hh : mm a')}}
                                            </td>
                                            <td class="p-1 align-middle text-center" id="{{$sched->scheddetail->roomid}}">{{$sched->scheddetail->roomname}}</td>
                                            <td class="p-1 align-middle text-center" id="{{$sched->scheddetail->teacherid}}">
                                                {{$sched->scheddetail->lastname}}, 
                                                {{explode(' ',trim($sched->scheddetail->firstname))[0]}}
                                            </td>
                                            <td class="p-1 align-middle text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/removeblocksched/{{Crypt::encrypt($sched->scheddetail->id)}}" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                                                  
                                                    
                                                </div>
                                            </td>
                                        @endforeach 
                                        
                                    </tr> --}}
                                    {{-- <tr>
                                        <td>{{Str::limit($item->subjinfo->subjdesc,  20, $end='...')}}<br>
                                            {{Str::limit($item->subjinfo->schedclass,  20, $end='...')}}</td>
                                        <td>{{$item->daysum}}</td>
                                        <td> {{\Carbon\Carbon::create($item->subjinfo->stime)->isoFormat('hh : mm a')}}
                                            <br>
                                            {{\Carbon\Carbon::create($item->subjinfo->etime)->isoFormat('hh : mm a')}}
                                        </td>
                                        <td>{{$item->subjinfo->roomname}}</td>
                                        <td>{{$item->subjinfo->lastname}}, {{$item->subjinfo->firstname}}</td>
                                        <td class="p-1 align-middle text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="/removeblocksched/{{Crypt::encrypt($item->subjinfo->id)}}" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer p-0">
                    <div class="sc">
                    </div>
                </div>
            </div>
            <div class="col-md-3 ">
                <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i>About Block</h3>
                    </div>
                    <div class="card-body">
                        <strong><i class="fas fa-pen-square mr-1"></i>Block Name</strong>
                        <p class="text-muted small">
                           {{$blockinfo->blockname}}
                        </p>
                        <hr>
                        <strong><i class="fas fa-pen-square mr-1"></i>Grade Level</strong>
                        <p class="text-muted small">
                            {{$blockinfo->levelname}} 
                        </p>
                        <hr>
                        <strong><i class="fas fa-pen-square mr-1"></i>Strand</strong>
                        <p class="text-muted small">
                            {{$blockinfo->strandname}} 
                        </p>
                        <hr>
                        
                        <strong><i class="fas fa-pen-square mr-1"></i>Created</strong>
                        <p class="text-muted small">
                            Created by: <span class="float-right">{{$blockinfo->cbname}}</span>
                            Date: <span class="float-right">{{$blockinfo->createddatetime}}</span>
                        </p>
                        <hr>
                        <strong><i class="fas fa-plus-square mr-1"></i></i>Updated</strong>
                        <p class="text-muted small">
                            Created by: <span class="float-right">{{$blockinfo->ubname}}</span>
                            Date: <span class="float-right">{{$blockinfo->createddatetime}}</span>
                        </p>
                        @if(auth()->user()->type == 2)
                            <span><button type="button" class="btn btn-sm btn-outline-success btn-block" id="us" data-toggle="modal" data-target="#modal-block"><i class="far fa-edit mr-1"></i>Edit Block</button></span>
                        @endif
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>

    @endif
    
</section>
  


@endsection


@section('footerjavascript')

    <script src="{{asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

@if(isset($blockinfo))

    <script>

          
        function loadSectionSubjects(){

                $.ajax({
                    type:'GET',
                    url:'/sectionScheduleV2?table=table&block='+'{{$blockinfo->id}}'+'&acadid=5&strand='+'{{$blockinfo->strandid}}'+'&levelid='+'{{$blockinfo->levelid}}',
                    success:function(data) {

                            $('#scheduletable').empty()
                            $('#scheduletable').append(data)
                    }
                })

        }

        loadSectionSubjects()

        $(document).on('click','.add_block_sched',function(){
			
			var days = [];

            $('.day').each(function(){
                if($(this).is(":checked")){
                    days.push($(this).val())
                }
            })

            $('#block_modal').modal()

            $('.evalupdate').addClass('eval')
            $('.eval').removeClass('evalupdate')
            selectedSubject = $(this).attr('data-id')

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
        })

        var selectedSubject

        $(function () {
             
             $('.select2').select2()
         
             $('.select2bs4').select2({
                 theme: 'bootstrap4'
             })
         });

        $(document).on('click','.eval',function(){

            var days = [];

            $('.day').each(function(){
                if($(this).is(":checked")){
                    days.push($(this).val())
                }
            })

            $.ajax({
                type:'GET',
                url:'/prinicipalstoreblocksched',
                data:{
                    bid:'{{$blockinfo->id}}',
                    t:$('#time').val(),
                    s:selectedSubject,
                    tea:$('#secttea').val(),
                    r:$('#sectroo').val(),
                    days:days,
                    class:$('#classification').val()
                    },
                success:function(data) {
                    Swal.fire({
                        type: 'success',
                        title: 'Created Successfully!',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    loadSectionSubjects()
                    $('#block_modal').modal('hide')
                }
            })

        })


        $(document).on('click','.remove_block_sched',function(){
            $.ajax({
                type:'GET',
                url:'/removeblocksched/'+$(this).attr('data-id'),
                success:function(data) {
                    Swal.fire({
                        type: 'success',
                        title: 'Deleted Successfully!',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    loadSectionSubjects()
                }
            })

        })

        
        var newTeacherSubject = null;

        $(document).on('click','.update_teacher',function(){

            $('#modal_teacher').modal()

            newTeacherSubject = $(this).attr('data-subjid')
            
            var teacherid = $('.teacher[data-subj="'+$(this).attr('data-subj')+'"]').attr('data-id');

            $('select[name="newteacher"]').val($(this).attr('data-id')).change();
        


        })


        $(document).on('click','.newteacher_button',function(){

            $.ajax({
                type:'GET',
                url:'/updateBlockSubjectTeacher',
                data:{
                    bid:'{{$blockinfo->id}}',
                    teacher: $('#newteacher').val(),
                    subject: newTeacherSubject
                },
                success:function(data) {

                    Swal.fire({
                        type: 'success',
                        title: 'Updated Successfully!',
                        showConfirmButton: false,
                        timer: 1500
                    })

                    $('#modal_teacher').modal('hide')

                    $('#scheduletable').empty()
                    loadSectionSubjects()
                }
            })

        })

        


    </script>

    <script>
        $(document).ready(function(){
            var newTeacherSubject = null;
            var selectedSubject = null;
            var selectedtype = null;
            var selectedHeader = null;



            $(document).on('click','.evalupdate',function(){

                var days = [];

                $('.day').each(function(){
                    if($(this).is(":checked")){
                        days.push($(this).val())
                    }
                })

                $.ajax({
                    type:'GET',
                    url:'/getSubjSchedInfo',
                    data:{
                        update: 'update',
                        type: selectedtype,
                        section:'{{$blockinfo->id}}',
                        subject: selectedSubject,
                        teacher: $('#secttea').val(),
                        roomid: $('#sectroo').val(),
                        time: $('#time').val(),
                        detailid: selectedHeader,
                        classification: $('#classification').val(),
                        days:days
                    },
                    success:function(data) {

                        Swal.fire({
                            type: 'success',
                            title: 'Updated Successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        })

                        $('#block_modal').modal('hide')

                        $('#scheduletable').empty()
                        loadSectionSubjects()
                     
                    }
                })

            })

            $(document).on('click','.update_sched',function(){

                $('.day').removeAttr('checked')

                $('.eval').addClass('evalupdate')
                $('.evalupdate').removeClass('eval')
                $('.day').removeAttr('checked')

                selectedSubject = $(this).attr('data-subjid')
                selectedtype = $(this).attr('data-type')
                selectedHeader = $(this).attr('data-headerid')

                getSubjSchedInfo()

            })

            function getSubjSchedInfo(){

                $('#block_modal').modal()

                $.ajax({
                    type:'GET',
                    url:'/getSubjSchedInfo',
                    data:{
                        view: 'view',
                        type: selectedtype,
                        section:'{{$blockinfo->id}}',
                        subject: selectedSubject,
                        detailid: selectedHeader
                    },
                    success:function(data) {

                        $('.reservationtime').daterangepicker({
                            timePicker: true,
                            startDate: data[0].stime,
                            endDate: data[0].etime,
                            timePickerIncrement: 5,
                            locale: {
                                format: 'hh:mm A',
                                cancelLabel: 'Clear'

                            }
                        })

                        $('#secttea').val(data[0].teacherid).change();
                        $('#sectroo').val(data[0].room).change();
                        $('#classification').val(data[0].classification).change();

                        $.each(data[0].days,function(key,value){

                            if(value.days == 6){
                                $('#Sat').attr('checked','checked')
                            }
                            if(value.days == 5){
                                $('#Fri').attr('checked','checked')
                            }
                            if(value.days == 4){
                                $('#Thu').attr('checked','checked')
                            }
                            if(value.days == 3){
                                $('#Wed').attr('checked','checked')
                            }
                             if(value.days == 2){
                                $('#Tue').attr('checked','checked')
                            } if(value.days == 1){
                                
                                $('#Mon').attr('checked','checked')
                            }

                            console.log(value.days);
                        })
                     
                    }
                })

            }
        })
    </script>



@endif

@endsection

