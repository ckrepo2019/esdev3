
<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}">

@php

    $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();
    $docid1 = DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted','0')->first()->id;
                
                    if($check_refid->refid == 23)
                    {
                        // return view('clinic.index');
                        $extend = 'clinic.layouts.app';
                    }
                    if($check_refid->refid == 25)
                    {
                        // return view('clinic_doctor.index');
                        $extend = 'clinic_doctor.layouts.app';
                        $docid1 = DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted','0')->first()->id;
                    }
        
@endphp


@extends($extend)

<style>
    .dataTable                  { font-size:80%; }
    .tschoolschedule .card-body { height:250px; }
    .tschoolcalendar            { font-size: 12px; }
    .tschoolcalendar .card-body { height: 250px; overflow-x: scroll; }
    .teacherd ul li a           { color: #fff; -webkit-transition: .3s; }
    .teacherd ul li             { -webkit-transition: .3s; border-radius: 5px; background: rgba(173, 177, 173, 0.3); margin-left: 2px; }
    .sf5                        { background: rgba(173, 177, 173, 0.3)!important; border: none!important; }
    .sf5menu a:hover            { background-color: rgba(173, 177, 173, 0.3)!important; }
    .teacherd ul li:hover       { transition: .3s; border-radius: 5px; padding: none; margin: none; }

    .small-box                  { box-shadow: 1px 2px 2px #001831c9; overflow-y: auto scroll; }

    .small-box h5               { text-shadow: 1px 1px 2px gray; }

    img{
        border-radius: unset !important;
    }

    .list-timeline {
  margin: 0;
  padding: 5px 0;
  position: relative
}

.list-timeline:before {
  width: 1px;
  background: #ccc;
  position: absolute;
  left: 6px;
  top: 0;
  bottom: 0;
  height: 100%;
  content: ''
}

.list-timeline .list-timeline-item {
  margin: 0;
  padding: 0;
  padding-left: 24px !important;
  position: relative
}

.list-timeline .list-timeline-item:before {
  width: 12px;
  height: 12px;
  background: #fff;
  border: 2px solid #ccc;
  position: absolute;
  left: 0;
  top: 4px;
  content: '';
  border-radius: 100%;
  -webkit-transition: all .3 ease-in-out;
  transition: all .3 ease-in-out
}

.list-timeline .list-timeline-item[data-toggle=collapse] {
  cursor: pointer
}

.list-timeline .list-timeline-item.active:before,
.list-timeline .list-timeline-item.show:before {
  background: #ccc
}

.list-timeline.list-timeline-light .list-timeline-item.active:before,
.list-timeline.list-timeline-light .list-timeline-item.show:before,
.list-timeline.list-timeline-light:before {
  background: #f8f9fa
}

.list-timeline .list-timeline-item.list-timeline-item-marker-middle:before {
  top: 50%;
  margin-top: -6px
}

.list-timeline.list-timeline-light .list-timeline-item:before {
  border-color: #f8f9fa
}

.list-timeline.list-timeline-grey .list-timeline-item.active:before,
.list-timeline.list-timeline-grey .list-timeline-item.show:before,
.list-timeline.list-timeline-grey:before {
  background: #e9ecef
}

.list-timeline.list-timeline-grey .list-timeline-item:before {
  border-color: #e9ecef
}

.list-timeline.list-timeline-grey-dark .list-timeline-item.active:before,
.list-timeline.list-timeline-grey-dark .list-timeline-item.show:before,
.list-timeline.list-timeline-grey-dark:before {
  background: #495057
}

.list-timeline.list-timeline-grey-dark .list-timeline-item:before {
  border-color: #495057
}

.list-timeline.list-timeline-primary .list-timeline-item.active:before,
.list-timeline.list-timeline-primary .list-timeline-item.show:before,
.list-timeline.list-timeline-primary:before {
  background: #55A79A
}

.list-timeline.list-timeline-primary .list-timeline-item:before {
  border-color: #55A79A
}

.list-timeline.list-timeline-primary-dark .list-timeline-item.active:before,
.list-timeline.list-timeline-primary-dark .list-timeline-item.show:before,
.list-timeline.list-timeline-primary-dark:before {
  background: #33635c
}

.list-timeline.list-timeline-primary-dark .list-timeline-item:before {
  border-color: #33635c
}

.list-timeline.list-timeline-primary-faded .list-timeline-item.active:before,
.list-timeline.list-timeline-primary-faded .list-timeline-item.show:before,
.list-timeline.list-timeline-primary-faded:before {
  background: rgba(85, 167, 154, .3)
}

.list-timeline.list-timeline-primary-faded .list-timeline-item:before {
  border-color: rgba(85, 167, 154, .3)
}

.list-timeline.list-timeline-info .list-timeline-item.active:before,
.list-timeline.list-timeline-info .list-timeline-item.show:before,
.list-timeline.list-timeline-info:before {
  background: #17a2b8
}

.list-timeline.list-timeline-info .list-timeline-item:before {
  border-color: #17a2b8
}

.list-timeline.list-timeline-success .list-timeline-item.active:before,
.list-timeline.list-timeline-success .list-timeline-item.show:before,
.list-timeline.list-timeline-success:before {
  background: #28a745
}

.list-timeline.list-timeline-success .list-timeline-item:before {
  border-color: #28a745
}

.list-timeline.list-timeline-warning .list-timeline-item.active:before,
.list-timeline.list-timeline-warning .list-timeline-item.show:before,
.list-timeline.list-timeline-warning:before {
  background: #ffc107
}

.list-timeline.list-timeline-warning .list-timeline-item:before {
  border-color: #ffc107
}

.list-timeline.list-timeline-danger .list-timeline-item.active:before,
.list-timeline.list-timeline-danger .list-timeline-item.show:before,
.list-timeline.list-timeline-danger:before {
  background: #dc3545
}

.list-timeline.list-timeline-danger .list-timeline-item:before {
  border-color: #dc3545
}

.list-timeline.list-timeline-dark .list-timeline-item.active:before,
.list-timeline.list-timeline-dark .list-timeline-item.show:before,
.list-timeline.list-timeline-dark:before {
  background: #343a40
}

.list-timeline.list-timeline-dark .list-timeline-item:before {
  border-color: #343a40
}

.list-timeline.list-timeline-secondary .list-timeline-item.active:before,
.list-timeline.list-timeline-secondary .list-timeline-item.show:before,
.list-timeline.list-timeline-secondary:before {
  background: #6c757d
}

.list-timeline.list-timeline-secondary .list-timeline-item:before {
  border-color: #6c757d
}

.list-timeline.list-timeline-black .list-timeline-item.active:before,
.list-timeline.list-timeline-black .list-timeline-item.show:before,
.list-timeline.list-timeline-black:before {
  background: #000
}

.list-timeline.list-timeline-black .list-timeline-item:before {
  border-color: #000
}

.list-timeline.list-timeline-white .list-timeline-item.active:before,
.list-timeline.list-timeline-white .list-timeline-item.show:before,
.list-timeline.list-timeline-white:before {
  background: #fff
}

.list-timeline.list-timeline-white .list-timeline-item:before {
  border-color: #fff
}

.list-timeline.list-timeline-green .list-timeline-item.active:before,
.list-timeline.list-timeline-green .list-timeline-item.show:before,
.list-timeline.list-timeline-green:before {
  background: #55A79A
}

.list-timeline.list-timeline-green .list-timeline-item:before {
  border-color: #55A79A
}

.list-timeline.list-timeline-red .list-timeline-item.active:before,
.list-timeline.list-timeline-red .list-timeline-item.show:before,
.list-timeline.list-timeline-red:before {
  background: #BE3E1D
}

.list-timeline.list-timeline-red .list-timeline-item:before {
  border-color: #BE3E1D
}

.list-timeline.list-timeline-blue .list-timeline-item.active:before,
.list-timeline.list-timeline-blue .list-timeline-item.show:before,
.list-timeline.list-timeline-blue:before {
  background: #00ADBB
}

.list-timeline.list-timeline-blue .list-timeline-item:before {
  border-color: #00ADBB
}

.list-timeline.list-timeline-purple .list-timeline-item.active:before,
.list-timeline.list-timeline-purple .list-timeline-item.show:before,
.list-timeline.list-timeline-purple:before {
  background: #b771b0
}

.list-timeline.list-timeline-purple .list-timeline-item:before {
  border-color: #b771b0
}

.list-timeline.list-timeline-pink .list-timeline-item.active:before,
.list-timeline.list-timeline-pink .list-timeline-item.show:before,
.list-timeline.list-timeline-pink:before {
  background: #CC164D
}

.list-timeline.list-timeline-pink .list-timeline-item:before {
  border-color: #CC164D
}

.list-timeline.list-timeline-orange .list-timeline-item.active:before,
.list-timeline.list-timeline-orange .list-timeline-item.show:before,
.list-timeline.list-timeline-orange:before {
  background: #e67e22
}

.list-timeline.list-timeline-orange .list-timeline-item:before {
  border-color: #e67e22
}

.list-timeline.list-timeline-lime .list-timeline-item.active:before,
.list-timeline.list-timeline-lime .list-timeline-item.show:before,
.list-timeline.list-timeline-lime:before {
  background: #b1dc44
}

.list-timeline.list-timeline-lime .list-timeline-item:before {
  border-color: #b1dc44
}

.list-timeline.list-timeline-blue-dark .list-timeline-item.active:before,
.list-timeline.list-timeline-blue-dark .list-timeline-item.show:before,
.list-timeline.list-timeline-blue-dark:before {
  background: #34495e
}

.list-timeline.list-timeline-blue-dark .list-timeline-item:before {
  border-color: #34495e
}

.list-timeline.list-timeline-red-dark .list-timeline-item.active:before,
.list-timeline.list-timeline-red-dark .list-timeline-item.show:before,
.list-timeline.list-timeline-red-dark:before {
  background: #a10f2b
}

.list-timeline.list-timeline-red-dark .list-timeline-item:before {
  border-color: #a10f2b
}

.list-timeline.list-timeline-brown .list-timeline-item.active:before,
.list-timeline.list-timeline-brown .list-timeline-item.show:before,
.list-timeline.list-timeline-brown:before {
  background: #91633c
}

.list-timeline.list-timeline-brown .list-timeline-item:before {
  border-color: #91633c
}

.list-timeline.list-timeline-cyan-dark .list-timeline-item.active:before,
.list-timeline.list-timeline-cyan-dark .list-timeline-item.show:before,
.list-timeline.list-timeline-cyan-dark:before {
  background: #008b8b
}

.list-timeline.list-timeline-cyan-dark .list-timeline-item:before {
  border-color: #008b8b
}

.list-timeline.list-timeline-yellow .list-timeline-item.active:before,
.list-timeline.list-timeline-yellow .list-timeline-item.show:before,
.list-timeline.list-timeline-yellow:before {
  background: #D4AC0D
}

.list-timeline.list-timeline-yellow .list-timeline-item:before {
  border-color: #D4AC0D
}

.list-timeline.list-timeline-slate .list-timeline-item.active:before,
.list-timeline.list-timeline-slate .list-timeline-item.show:before,
.list-timeline.list-timeline-slate:before {
  background: #5D6D7E
}

.list-timeline.list-timeline-slate .list-timeline-item:before {
  border-color: #5D6D7E
}

.list-timeline.list-timeline-olive .list-timeline-item.active:before,
.list-timeline.list-timeline-olive .list-timeline-item.show:before,
.list-timeline.list-timeline-olive:before {
  background: olive
}

.list-timeline.list-timeline-olive .list-timeline-item:before {
  border-color: olive
}

.list-timeline.list-timeline-teal .list-timeline-item.active:before,
.list-timeline.list-timeline-teal .list-timeline-item.show:before,
.list-timeline.list-timeline-teal:before {
  background: teal
}

.list-timeline.list-timeline-teal .list-timeline-item:before {
  border-color: teal
}

.list-timeline.list-timeline-green-bright .list-timeline-item.active:before,
.list-timeline.list-timeline-green-bright .list-timeline-item.show:before,
.list-timeline.list-timeline-green-bright:before {
  background: #2ECC71
}

.list-timeline.list-timeline-green-bright .list-timeline-item:before {
  border-color: #2ECC71
}
</style>
@section('content')
@php
date_default_timezone_set('Asia/Manila');
$day = date('l');
$ddate = date('Y-m-d');
$date = new DateTime($ddate);
$week = $date->format("W");
@endphp
<div class="row">
  {{-- <div class="col-md-4">
      <div class="card each-day p-1 @if(strtolower($day) == 'monday') bg-success day-active @endif" id="monday" style="border-radius: 10px; border: none;">
        <div class="card-body p-1">
          <h5>Monday</h5>
        </div>
      </div>
      <div class="card each-day p-1 @if(strtolower($day) == 'tuesday') bg-success day-active @endif" id="tuesday" style="border-radius: 10px; border: none;">
        <div class="card-body p-1">
          <h5>Tuesday</h5>
        </div>
      </div>
      <div class="card each-day p-1 @if(strtolower($day) == 'wednesday') bg-success day-active @endif" id="wednesday" style="border-radius: 10px; border: none;">
        <div class="card-body p-1">
          <h5>Wednesday</h5>
        </div>
      </div>
      <div class="card each-day p-1 @if(strtolower($day) == 'thursday') bg-success day-active @endif" id="thursday" style="border-radius: 10px; border: none;">
        <div class="card-body p-1">
          <h5>Thursday</h5>
        </div>
      </div>
      <div class="card each-day p-1 @if(strtolower($day) == 'friday') bg-success day-active @endif" id="friday" style="border-radius: 10px; border: none;">
        <div class="card-body p-1">
          <h5>Friday</h5>
        </div>
      </div>
      <div class="card each-day p-1 @if(strtolower($day) == 'saturday') bg-success day-active @endif" id="saturday" style="border-radius: 10px; border: none;">
        <div class="card-body p-1">
          <h5>Saturday</h5>
        </div>
      </div>
  </div> --}}
          <div class="col-md-4">

            @if($check_refid->refid == 23)
            @php
            $doctors = DB::table('teacher')
                        ->select('id','userid','lastname','firstname','middlename')
                        ->where('deleted', 0)
                        ->where('usertypeid', 34)
                        ->get();
          
            @endphp
            @endif
            @if($check_refid->refid == 23)
              <label>Select Doctor</label>
                            <select class="form-control select2" style="width: 100%;" id="selectdoctor">
                                  @foreach($doctors as $doctor)
                                  @php
                                    $name ="";
                                    $name.=$doctor->lastname;
                                    $name.=" ";
                                    $name.= $doctor->firstname;
                                  @endphp
                                  @if(isset($name))
                                    <option value="{{$doctor->id}}">{{$name}}</option>
                                  @endif
                                  @endforeach
                        
              </select>
              @endif
              
              <br/>
            <input type="week" class="form-control" name="week" id="select-week" value="{{date('Y')}}-W{{$week}}" required>
          
          </div>
          <div class="col-md-12" id="availability-container">
          </div>

          
    {{-- <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Time Availability</button> --}}

{{-- <div class="row">
</div> --}}
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar/main.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar-daygrid/main.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar-timegrid/main.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar-interaction/main.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar-bootstrap/main.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script>
    var docids;
    $(document).ready(function(){

      // $('#selectdoctor').on('change',function(){
      //     var userid   = $(this).val();
      //     docid = userid;
      //     console.log(docids);

      // })
        function getavailability()
        {
            var weekid = $('#select-week').val();
            $.ajax({
                url:'/clinic/doctor/availablity/getschedavailability',
                type:'GET',
                data: {
                    weekid      :  weekid
                },
                success:function(data) {
                    $('#availability-container').empty()
                    $('#availability-container').append(data)
                    // if(data == 1)
                    // {
                    //     Toast.fire({
                    //         type: 'success',
                    //         title: 'Appointment marked '+labelstring+'!'
                    //     })
                    // }else{
                    //     Toast.fire({
                    //         type: 'error',
                    //         title: 'Something went wrong!'
                    //     })
                    // }
                }
            })

        }
        getavailability()
        $('#select-week').on('change', function(){
            getavailability()
        })
        $('#btn-createapp').on('click', function(){
            window.open("/clinic/appointment/index");
        })
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        $('.check-appointment').on('click', function(){
              var appointmentid   = $(this).val();
              var applabel           = 0;
              var labelstring     = 'undone';
              if($(this).prop('checked'))
              {
                applabel         = 1;
                labelstring       = 'done'
              }
              Swal.fire({
                  title: 'You are going to mark this appointment '+labelstring+'.',
                  text: 'Would you like to continue?',
                  type: 'info',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Continue'
              })
              .then((result) => {
                  if (result.value) {
                      $.ajax({
                          url:'/clinic/appointment/markdone',
                          type:'GET',
                          dataType: 'json',
                          data: {
                              id        :  appointmentid,
                              applabel  :  applabel
                          },
                          success:function(data) {
                              if(data == 1)
                              {
                                  Toast.fire({
                                      type: 'success',
                                      title: 'Appointment marked '+labelstring+'!'
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
        function tConvert (time) {
            // Check correct time format and split into components
            time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

            if (time.length > 1) { // If time format correct
                time = time.slice (1);  // Remove full string match value
                time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
                time[0] = +time[0] % 12 || 12; // Adjust hours
            }
            return time.join (''); // return adjusted time or original string
        }

        $(document).on('click','.btn-savetime', function(){

            var usertype = <?php echo json_encode($check_refid);?>;
            
            
            if(usertype.refid == 23){
              $.each($("#selectdoctor"), function(){            
                  var userid1=($(this).val());
                  docids = userid1;
                  console.log(usertype.refid);
                  console.log(docids);
        
            });
            }
            else{
                var id = <?php echo json_encode($docid1);?>;
                docids = id;
                usertype = id;
                console.log(id);
            }
        
            var scheddate = $(this).attr('data-date');

            var userid   = $(this).val();
            var thiscontainer = $(this).closest('.row');
            var thisli = $(this).closest('li');
            var validation = 0;
            if(thiscontainer.find('.timefrom').val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                thiscontainer.find('.timefrom').css('border','1px solid red')
                validation=1;
            }
            if(thiscontainer.find('.timeto').val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                thiscontainer.find('.timeto').css('border','1px solid red')
                validation=1;
            }
            if(validation == 0)
            {
                var timefrom = thiscontainer.find('.timefrom').val();
                var timeto = thiscontainer.find('.timeto').val();  
                $.ajax({
                    url:'/clinic/doctor/availablity/submittime',
                    type:'GET',
                    data: {
                        docids           : docids,
                        scheddate      :  scheddate,
                        timefrom      :  timefrom,
                        timeto      :  timeto,

                    },
                    success:function(data) {
                        if(data == 1)
                        {
                            timefrom = tConvert(timefrom)
                            timeto = tConvert(timeto)
                            thisli.empty()
                            thisli.append('<p class="my-0 text-muted flex-fw text-sm text-uppercase">'+timefrom+' - '+timeto+' </p>')
                            Toast.fire({
                                type: 'success',
                                title: 'Created successfully!'
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
        $(document).on('click','.delete-time', function(){
            var id = $(this).attr('data-id')
            var thisli = $(this).closest('li')
            // console.log(thisli) 
              Swal.fire({
                  title: 'Are you sure you want to delete this?',
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Delete'
              })
              .then((result) => {
                  if (result.value) {
                      $.ajax({
                          url:'/clinic/doctor/availablity/deletetime',
                          type:'GET',
                          dataType: 'json',
                          data: {
                              id        :  id
                          },
                          success:function(data) {
                              if(data == 1)
                              {
                                thisli.remove()
                                  Toast.fire({
                                      type: 'success',
                                    title: 'Deleted successfully!'
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
        $(document).on('click','.getappointments', function(){
          var schedavailabilityid = $(this).attr('data-schedid')
            
            $.ajax({
                      url:'/clinic/doctor/availablity/getappointments',
                      type:'GET',
                      data: {
                        schedavailabilityid      :  schedavailabilityid
                      },
                      success:function(data) {
                          if(data == 1)
                          {
                              timefrom = tConvert(timefrom)
                              timeto = tConvert(timeto)
                              thisli.empty()
                              thisli.append('<p class="my-0 text-muted flex-fw text-sm text-uppercase">'+timefrom+' - '+timeto+' </p>')
                              Toast.fire({
                                  type: 'success',
                                  title: 'Created successfully!'
                              })
                          }else{
                              Toast.fire({
                                  type: 'error',
                                  title: 'Something went wrong!'
                              })
                          }
                      }
                  })
        })
    })
</script>
@endsection
