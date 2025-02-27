@extends('principalsportal.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-daygrid/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-timegrid/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-bootstrap/main.min.css') }}">
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar/main.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-daygrid/main.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-timegrid/main.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-interaction/main.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-bootstrap/main.min.js') }}"></script>
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@endsection

@section('content')
<style>
    
   
    .principalcalendar {
        font-size: 12px;
    }
    
    .principald ul li a{
    color: #fff;
    -webkit-transition: .3s;
    }
    .principald li ul li{
        -webkit-transition: .3s;
        border-radius: 5px;
        background: rgba(173, 177, 173, 0.3);
        margin-left: 2px;
    }
    .principald li ul li a:hover {
        transition: .3s;
        border-radius: 5px;
        color: #17a2b8;
    }
    
    
    .info-box {
		box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
		border-radius: .25rem;
		background: #fff;
		line-height:
		display: -ms-flexbox;
		display: flex;
		margin-bottom: 1rem;
		min-height: none!important;
		height: 50px!important;
		padding: .5rem;
		position: relative;
    }
</style>

	<section class="content-header">
	</section>
	
	@php
		$schoolinfo = DB::table('schoolinfo')->first();
	@endphp
	  
	@if($schoolinfo->withmsteams == 1)
		<div class="card collapsed-card col-md-12 shadow container-fluid bg-warning">
			<div class="card-header bg-warning" data-card-widget="collapse" style="cursor: pointer;">
				<h3 class="card-title"><i class="fa fa-exclamation-triangle"></i> MSTeams Credentials</h3>
			</div>
			<div class="card-body">
			
			  @php
				try
				{
					$msteamsaccount = DB::table('msteams_creds')
						->where('studid', DB::table('teacher')->where('userid', auth()->user()->id)->first()->id)
						->where('department', 'TEACHER')
						->first();
				}catch(\Exception $error)
				{
					$msteamsaccount = false;
				}
			  @endphp
			  
				<div class="row">
					<div class="col-6">
						<label>Username</label><br/>
						@if($msteamsaccount)
						  <em>{{$msteamsaccount->username}}</em>
						@endif
					</div>
					<div class="col-6">
						<label>Password</label><br/>
						  <div class="input-group" data-target-input="nearest">
							  @if($msteamsaccount)
								  <input type="password" value="{{$msteamsaccount->password}}" id="msteamspassword" class="form-control form-control-sm" readonly/>
								  <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
									  <div class="input-group-text" id="msteamsvisible"><i class="fa fa-eye"></i></div>
								  </div>
							  @endif
						  </div>
					</div>
				</div>
			</div>
		</div>
	@endif
	
	<div class="row">
		<div class="col-md-12">
			<div class="card shadow">
				<div class="card-footer">
					<div class="row">
						<div class="col-sm-3 col-6">
							<div class="description-block border-right">
								<h5 class="description-header">{{$present}} / {{count($teacheratt)}}</h5>
								<span class="description-text text-success">Present Faculty</span>
							</div>
						</div>
						<div class="col-sm-3 col-6">
							<div class="description-block border-right">
								<h5 class="description-header">{{$absent}} / {{count($teacheratt)}}</h5>
								<span class="description-text text-danger">Absent Faculty</span>
							</div>
						</div>
						<div class="col-sm-3 col-6">
							<div class="description-block border-right">
								<h5 class="description-header">{{$ontime}} / {{count($teacheratt)}}</h5>
								<span class="description-text text-success">Ontime Faculty</span>
							</div>
						</div>
						<div class="col-sm-3 col-6">
							<div class="description-block border-right">
								<h5 class="description-header">{{$late}} / {{count($teacheratt)}}</h5>
								<span class="description-text text-warning">Late Faculty</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow ">
            <div class="card-header ">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title mt-2">
                                Faculty and Staff Attendance 
                        </h3>
                    </div>
                    <div class="col-md-4">
                        <select name="select_filter" id="select_filter" class="form-control form-control-sm">
                            <option value="">All</option>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                        </select>
                    </div>
                </div>
               
            </div>
            <div class="card-body pt-0 table-responsive"style="height: 250px;" >
                <div class="row " >
                    <div class="col-md-12 " >
                        <table class="table table-head-fixed table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Time In</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            @foreach($teacheratt as $item)
                                @php
                                    $type = '';
                                    if($item->time=='00:00'){
                                        $type = "absent";
                                    }else if(\Carbon\Carbon::createFromTimeString($item->time,'Asia/Manila')->isoFormat('HH:mm')<=\Carbon\Carbon::createFromTimeString('7:30:00','Asia/Manila')->isoFormat('HH:mm')){
                                        $type = "present";
                                    }else{
                                        $type = "late";
                                    }
        
                                @endphp
        
                                <tr class="{{$type}} all">
                                    <td>
                                        {{$item->teacher}}
                                    </td>
                                    @if($item->time!='00:00')
                                        <td>
                                            {{$item->time}}
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if($item->time=='00:00')
                                        <td>
                                            <span class="badge bg-danger">ABSENT</span>
                                        </td>
                                    @elseif(\Carbon\Carbon::createFromTimeString($item->time,'Asia/Manila')->isoFormat('HH:mm')<=\Carbon\Carbon::createFromTimeString('7:30:00','Asia/Manila')->isoFormat('HH:mm'))
                                        <td>
                                            <span class="badge bg-success">PRESENT</span>
                                        </td>
                                    @else
                                        <td>
                                            <span class="badge bg-warning">LATE</span>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    
    <div class="col-md-6" style="height: 250px;">
	
	<div class="card principald shadow">
            <div class="card-header bg-primary">
                <span style="font-size: 20px"><i style="color: #ffc107;" class="fas fa-users"></i> <b>STUDENTS LIST</b></span>
            </div>
            <div class="card-body pt-0"  style="overflow-y:scroll;">
                <table class="table table-head-fixed table-sm">
                    <thead>
                        <tr>
                            <th>Total Numbers</th>
                            <th>Academic Program</th>
                        </tr>
                    </thead>
                    <tbody>
					
						@php
							$activesy = DB::table('sy')
											->where('isactive',1)
											->first();
											
							$teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;
											
							$acadprogid = DB::table('teacheracadprog')
								->join('academicprogram',function($join){
									$join->on('teacheracadprog.acadprogid','=','academicprogram.id');
									$join->where('academicprogram.id','!=',6);
								})
								->where('teacherid',$teacherid)
								->where('acadprogutype',2)
								->where('syid',$activesy->id)
								->select('academicprogram.*')
								->where('deleted',0)
								->orderBy('acadprogid')
								->get();
						@endphp
					
					@if(isset(Session::get('prinInfo')->id))
						@foreach ($acadprogid as $item)
                            <tr>
                                <td>
                                    @if($item->id == 2)
                                        {{$studcount  = Session::get('psstudentcount')}} 
                                    @elseif($item->id == 3)
                                        {{$studcount  = Session::get('gsstudentcount')}}
                                    @elseif($item->id == 4)
                                        {{$studcount  = Session::get('jhstudentcount')}}
                                    @elseif($item->id == 5)
                                        {{$studcount  = Session::get('shstudentcount')}}
                                    @endif
                                </td>
                                <td>
                                    <option class=" {{ $studcount  > 0 ? 'text-success':'text-danger'}}" value="{{Crypt::encrypt($item->id)}}">{{$item->progname}} 
								</td>
                            </tr>
                        @endforeach
					@endif
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
		<div class="col-row principalcalendar">
			<div class="card h-100 shadow">
				<div class="card-header bg-info">
					<h3 class="card-title">
						<i style="color: #ffc107" class="fas fa-calendar-day"></i>
						School Calendar 
					</h3>
				</div>
				<div class="card-body  p-0">
					<div id="newcal" ></div>
				</div>
			</div>
		</div>
    </div>
    
</div>
@endsection

@section('footerjavascript')

	<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
	<script>
		$(document).on('change','#select_filter',function(){
				if($(this).val() == ""){
					$('.all').removeAttr('hidden')
				}else{
					$('.all').attr('hidden','hidden')
					$('.'+$(this).val()).removeAttr('hidden')
				}
			})

	</script>

<script>

    $( document ).ready(function() {
		
		var syid = '<?php echo DB::table('sy')->where('isactive',1)->first()->id; ?>';
        var currentportal = @json(Session::get('currentPortal'));
		
		
        $(document).on('click','#msteamsvisible', function(){
            if ($('#msteamspassword').attr('type') === "password") {
                $('#msteamspassword').attr('type','text');
                $('#msteamspassword').val($.trim($('#msteamspassword').val()))
            } else {
                $('#msteamspassword').attr('type','password');
            }
        })

        function showTime(){
            var datetime = new Date().toLocaleString("en-US", {timeZone: "Asia/Manila"})
        }

        setInterval(showTime,1000);

        var datefrom = new Date().toLocaleString("en-US", {timeZone: "Asia/Manila"})

        $(function () {
            $('#day').daterangepicker({
                locale: {
                    format: 'YYYY/MM/DD'
                }
            })
        })

        var date = new Date()
        var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()

        var schedule = [];

        @foreach($schoolcalendar as $item)

            @if($item->noclass == 1)
                var backgroundcolor = '#dc3545';
            @else
                var backgroundcolor = '#00a65a';
            @endif

            schedule.push({
                title          : '{{$item->description}}',
                start          : '{{$item->datefrom}}',
                end            : '{{$item->dateto}}',
                backgroundColor: backgroundcolor,
                borderColor    : backgroundcolor,
                allDay         : true,
                id: '{{$item->id}}'
            })

        @endforeach


        var Calendar = FullCalendar.Calendar;

        var calendarEl = document.getElementById('newcal');

        var calendar = new Calendar(calendarEl, {
            plugins: [ 'bootstrap', 'interaction', 'dayGrid'],
            header    : {
                left:   'title',
                center: '',
                right:  'today prev,next'
            },
            // events    : schedule,
            events    : '/school-calendar/getall-event/'+currentportal+'/'+syid,
            themeSystem: 'bootstrap',
            eventStartEditable: false,
            timeZone: 'UTC',
            height : 'auto',
            eventClick: function(info) {
            
                $('#modal-primary').modal('show')
                $.ajax({
                type:'GET',
                url:'/principalgetevent',
                data:{
                id:info.event.id
                },
                
                success:function(data) {

                    $('#des').empty()
                    $('#date').empty()
                    $('#des').empty()
                    $('#type').empty()

                    if(data[0].data[0].annual == 1){
                    
                    $('#annual').prop('checked','checked')
                    }

                    if(data[0].data[0].noclass == 1){
                    $('#noclass').prop('checked','checked')
                    }
        
                    // $('#clas').val(data[0].data[0].eventtype)
            
                    var typeid = data[0].data[0].eventtype
                    var scholcaltypeid = data[0].data[0].typeid

            
                
                    $.ajax({
                    type:'GET',
                    url:'/principalgeteventtype',
                    data:{
                        id:typeid
                    },
                    success:function(data) {
            
                        $('#type').empty();

                        $.each(data,function(key,value){

                        if(scholcaltypeid == value.id){
                            $('#type').append(value.typename)
                        }
                        
                        
                        })
                        
                    }
                    })
                    var datefrom = new Date(data[0].data[0].datefrom)
                    var dateto = new Date(data[0].data[0].dateto)
                    $('#des').append(data[0].data[0].description)
                    $('#date').append(moment(datefrom).format('MMM DD, YYYY'))
                        
                    if(moment(datefrom).format('MMM DD, YYYY') != moment(dateto).format('MMM DD, YYYY')){
                        $('#date').append(' - '+ moment(dateto).format('MMM DD, YYYY'))
                    }

                }
            })

            },

        });

        calendar.render();

        // $('.fc-prev-button').addClass('btn-sm')
        // $('.fc-next-button').addClass('btn-sm')
        $('.fc-today-button').addClass('btn-sm')
		$('.fc-today-button').css('text-transform', 'capitalize') 
        $('.fc-left').css('font-size','13px')
        $('.fc-toolbar').css('margin','0')
        $('.fc-toolbar').css('padding-top','0')
        
        });

</script>



    
@endsection
