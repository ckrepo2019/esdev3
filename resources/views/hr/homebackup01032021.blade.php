

@extends('hr.layouts.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-interaction/main.min.css')}}">
@php
  $leavestoday = DB::table('employee_leavesdetail')
    ->select('teacher.*','employee_leavesdetail.leavestatus','employee_leaves.createddatetime')
    ->join('employee_leaves','employee_leavesdetail.headerid','=','employee_leaves.id')
    ->join('teacher','employee_leaves.employeeid','=','teacher.id')
    ->where('employee_leavesdetail.ldate', date('Y-m-d'))
    // ->where('employee_leavesdetail.leavestatus','1')
    ->where('employee_leavesdetail.deleted','0')
    ->get();

@endphp
<style>
  .alert-primary {
    color: #004085;
    background-color: #cce5ff;
    border-color: #b8daff;
  }
  .alert-secondary {
      color: #383d41;
      background-color: #e2e3e5;
      border-color: #d6d8db;
  }
  .alert-success {
      color: #155724;
      background-color: #d4edda;
      border-color: #c3e6cb;
  }
  .alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
  }
  .alert-warning {
      color: #856404;
      background-color: #fff3cd;
      border-color: #ffeeba;
  }
  .alert-info {
      color: #0c5460;
      background-color: #d1ecf1;
      border-color: #bee5eb;
  }
  .alert-dark {
      color: #1b1e21;
      background-color: #d6d8d9;
      border-color: #c6c8ca;
  }
</style>
@php
  $employees = DB::table('teacher')
    ->select('id','userid','lastname','firstname','middlename')
    ->where('isactive','1')
    ->where('deleted','0')
    ->orderBy('lastname','asc')
    ->get();

  if(count($employees)>0)
  {
    foreach($employees as $employee)
    {
      $countrecords = 0;
      $checktap = DB::table('taphistory')
        ->where('studid', $employee->id)
        ->where('tdate', date('Y-m-d'))
        ->where('deleted','0')
        ->count();
      $countrecords+=$checktap;
      $checkatt = DB::table('hr_attendance')
        ->where('studid', $employee->id)
        ->where('tdate', date('Y-m-d'))
        ->where('deleted','0')
        ->count();
      $countrecords+=$checkatt;
      if($countrecords == 0)
      {
        $employee->status = 0;
      }else{
        $employee->status = 1;
      }
    }
  }
@endphp
<div class="row">
  <div class="col-md-4">
		<div class="card flex-fill dash-statistics" style="height: 500px;">
			<div class="card-header bg-success">
				<h3 class="card-title"><i style="color: #ffc107" class="fas fa-bars"></i> <b>Attendance</b></h3>
			</div>
			<div class="card-body"  style="overflow: scroll;">
          <table class="table responsive" >
              <thead>
                  <tr>
                      <th>Name</th>
                      <th>Status</th>	
                  </tr>
              </thead>
              <tbody>
                  @foreach($employees as $employee)
                  <tr>
                  <td>{{$employee->lastname}}, {{$employee->firstname}} </td>
                  <td>
                      @if($employee->status == '0')
                        <span class="right badge badge-danger">Absent</span>
                      @else
                        <span class="right badge badge-success">Present</span>
                      @endif
                    </td>
                  </tr>
                  @endforeach
              </tbody>
          </table>
			</div>
		</div>
	</div>
  	<div class="col-md-8 tschoolcalendar">
      <div class="card card-primary tschoolcalendar" style="height: 500px;">
          <div class="card-header bg-info">
              <h3 class="card-title">School Calendar</h3>
          </div>
          <div class="card-body p-1" style="overflow: scroll;">
              <div class="calendarHolder">
                  <div id='newcal'></div>
              </div>
          </div>
      </div>
		{{-- <div class="card flex-fill dash-statistics" style="height: 300px;">
			<div class="card-header bg-warning">
				<h3 class="card-title"><i style="color: #ffc107" class="fas fa-bars"></i> <b>Employees</b> ( {{count($employees)}} )</h3>
			</div>
			<div class="card-body" style="overflow: scroll;">
              <table class="table responsive" >
						<thead>
							<tr>
								<th>Name</th>
								<th>Department</th>
								<th>Designation</th>
								<th>Contact #</th>	
							</tr>
						</thead>
						<tbody>
							@foreach($employees as $employee)
							<tr>
							<td>{{$employee->lastname}}, {{$employee->firstname}} </td>
							<td>{{$employee->department}}</td>
							<td>{{$employee->designation}}</td>
							<td>{{$employee->contactnum}}</td>
							</tr>
							@endforeach
						</tbody>
              </table>
			</div>
		</div> --}}
	</div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="alert alert-success" role="alert">
            <label>On Leave Today ({{collect($leavestoday)->where('leavestatus','1')->count()}})</label>
            @if(collect($leavestoday)->where('leavestatus','1')->count() == 0)
            @else
              <ol>
              @foreach(collect($leavestoday)->where('leavestatus','1') as $leavetoday)
                <li>
                  <small>{{$leavetoday->lastname}}, {{$leavetoday->firstname}}</small>
                </li>
              @endforeach
            </ol>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="alert alert-info" role="alert">
            <label>Scheduled Today ({{collect($leavestoday)->where('leavestatus','0')->count()}})</label>
            @if(collect($leavestoday)->where('leavestatus','0')->count() == 0)
            @else
              <ol class="pl-1" >
              @foreach(collect($leavestoday)->where('leavestatus','0') as $leavetoday)
                <li>
                  <small><span class="badge badge-warning">Pending</span> {{ucwords(strtolower($leavetoday->lastname))}}, {{ucwords(strtolower($leavetoday->firstname))}}</small> 
                </li>
              @endforeach
            </ol>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="alert alert-warning" role="alert">
            <label>Applied Today ({{collect($leavestoday)->where('leavestatus','0')->where('createddatetime','>',date('Y-m-d 00:00:00'))->where('createddatetime','<',date('Y-m-d 23:59'))->count()}})</label>
            @if(collect($leavestoday)->where('leavestatus','0')->where('createddatetime','>',date('Y-m-d 00:00:00'))->where('createddatetime','<',date('Y-m-d 23:59'))->count() == 0)
            @else
              <ol class="pl-1" >
              @foreach(collect($leavestoday)->where('leavestatus','0')->where('createddatetime','>',date('Y-m-d 00:00:00'))->where('createddatetime','<',date('Y-m-d 23:59')) as $leavetoday)
                <li>
                  <small><span class="badge badge-warning">Pending</span> {{ucwords(strtolower($leavetoday->lastname))}}, {{ucwords(strtolower($leavetoday->firstname))}}</small> 
                </li>
              @endforeach
            </ol>
            @endif
        </div>
    </div>
</div>
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
    $(function () {
		// var table =  $("#example1").DataTable({
		// 	pageLength : 10,
		// 	lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']],
		// 	// scrollY:        "500px",
		// 	// scrollX:        true,
		// 	scrollCollapse: true,
		// 	paging:         false,
		// 	fixedColumns:   true,
		// 	bFilter: false
      // });
      /* ChartJS
       * -------
       * Here we will create a few charts using ChartJS
       */
  
      //--------------
      //- AREA CHART -
      //--------------
  
      // Get context with jQuery - using jQuery's .get() method.
   
  
    
      //-------------
      //- DONUT CHART -
      //-------------
     
  
      //-------------
      //- BAR CHART -
      //-------------
        var barChartCanvas = $('#barChart').get(0).getContext('2d')

        var areaChartData = {
            labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [
                {
                label               : 'Total Income',
                backgroundColor     : 'rgba(60,141,188,0.9)',
                borderColor         : 'rgba(60,141,188,0.8)',
                pointRadius          : false,
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data                : [28, 48, 40, 19, 86, 27, 90]
                },
                {
                label               : 'Total Outcome',
                backgroundColor     : 'rgba(210, 214, 222, 1)',
                borderColor         : 'rgba(210, 214, 222, 1)',
                pointRadius         : false,
                pointColor          : 'rgba(210, 214, 222, 1)',
                pointStrokeColor    : '#c1c7d1',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(220,220,220,1)',
                data                : [65, 59, 80, 81, 56, 55, 40]
                },
            ]
            }

        var barChartData = jQuery.extend(true, {}, areaChartData)
        var temp0 = areaChartData.datasets[0]
        var temp1 = areaChartData.datasets[1]
        barChartData.datasets[0] = temp1
        barChartData.datasets[1] = temp0

        var barChartOptions = {
        responsive              : true,
        maintainAspectRatio     : false,
        datasetFill             : false
        }

        var barChart = new Chart(barChartCanvas, {
        type: 'bar', 
        data: barChartData,
        options: barChartOptions
        })

  
      //---------------------
      //- STACKED BAR CHART -
      //---------------------
      
    });
	 
    $( document ).ready(function() {

		if($(window).width()<500){

			$('.fc-prev-button').addClass('btn-sm')
			$('.fc-next-button').addClass('btn-sm')
			$('.fc-today-button').addClass('btn-sm')
			$('.fc-left').css('font-size','13px')
			$('.fc-toolbar').css('margin','0')
			$('.fc-toolbar').css('padding-top','0')

			var header = {
				left:   'title',
				center: '',
				right:  'today prev,next'
			}
			console.log(header)


			}
			else{
			var header = {
				left  : 'prev,next today',
				center: 'title',
				right : 'dayGridMonth,timeGridWeek,timeGridDay'
			}
			console.log(header)
			}

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
				plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
				header    : header,
				events    : schedule,
				height : 'auto',
				themeSystem: 'bootstrap',
				eventStartEditable: false
			});

			calendar.render();
			});
			$(function () {
			$("#example1").DataTable({
				pageLength : 10,
				lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
			});
			});
			$(".notification").click(function () {
			var notification_id = $(this).attr("name");
			var parent_class = $(this).parent().parent();
			parent_class.css('border','solid','#218838');
			$.ajax({
				url: '/teacherNotification/'+notification_id,
				type:"GET",
				dataType:"json",
				data:{
					getStudents:'getGradeLevel'
				},
				success:function(data) {
					$(".badge-notify").text(data[0]);
					if(data[0]==0){
							$(".badge-notify").hide();
							$(".card"+data[2]).css('color','#218838');
					}
					$.each(data[1], function(key, value){
							if(value.status){
								$(".notification").click(function () {
									$(".notif"+value.id).css('color','black');
									$(".card"+data[2]).css('color','white');
									// #218838
								});
							}
					});
				}
			});
			});
  </script>
@endsection