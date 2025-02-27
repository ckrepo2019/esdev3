@extends('adminITPortal.layouts.app')


@section('pagespecificscripts')
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-interaction/main.min.css')}}">
<style>
.afinance .card-body, .aregistrar .card-body, .aprincipal .card-body{
  height: 120px;
  /* margin: auto; */
}
.card-header {
  text-shadow: 1px 1px 3px gray;
}
.afacstaff .card-body, .astudentlist .card-body{
  height: 158px;
  overflow-y: scroll;
  font-size: 14px;
}
.aschoolcalendar .card-body{
  height: 95px;
}
.afacstaff .card-header {
  background-color: #fd7e14;
  color: #fff;
}
.astudentlist .card-header {
  background-color: #d81b60;
  color: #fff;
}

.btn-app {
  color: #fff;
  border: none;
  width: 90px;
  border-radius: 3px;
  background-color: transparent!important;
  font-size: 12px;
  height: 60px;
  margin: 0 0 10px 10px;
  min-width: 80px;
  padding: 15px 5px;
  position: relative;
  text-align: center;
  border: 0 !important;
} 
.btn-app span{
  color: #fff;
  font-size: 13px;
  font-weight: 600;
}
.btn-app i {
  -webkit-text-stroke-width: .3px;
  -webkit-text-stroke-color: #713333;
  transition: .3s;
  color: #ffc107;
 
}
.btn-app:hover i{
  font-size: 35px;
  transition: .3s;
}
.small-box-footer {
  color: #fff;
}

/* .tschoolschedule .card-body { height:250px; } */
    .tschoolcalendar            { font-size: 12px; }
    /* .tschoolcalendar .card-body { height: 250px; overflow-x: scroll; } */

@media screen and (max-width: 906px)
{
    button {
        padding: 2px !important;
        font-size: 12px !important;
    }
     .fc-toolbar-title {
        font-size: 12px !important;
     }
}
</style>

@endsection


@section('content')
@php
    use \Carbon\Carbon;
    use App\Models\HR\HREmployeeAttendance;
    $now = Carbon::now();
    $comparedDate = $now->toDateString();
    
    // $employees = DB::table('teacher')
    //     ->where('deleted','0')
    //     ->where('isactive','1')
    //     ->orderBy('lastname','asc')
    //     ->get();

    // if(count($employees) > 0)
    // {
    //     foreach($employees as $employee)
    //     {
    //         $employee->lastactivity = HREmployeeAttendance::principalname(date('Y-m-d'),$employee)->lastactivity;
    //     }
    // }
    $designations = Db::table('usertype')
        ->select(
            'usertype.id',
            'usertype.utype as designation'
            )
        ->where('usertype.deleted','0')
        ->where('usertype.utype','!=','PARENT')
        ->where('usertype.utype','!=','STUDENT')
        ->where('usertype.utype','!=','SUPER ADMIN')
        ->where('usertype.utype','!=','ADMINADMIN')
        ->where('usertype.utype','!=','ADMIN')
        ->get();

      $gradelevels = DB::table('gradelevel')->where('deleted','0')->orderBy('sortid','asc')->get();

      $syid = DB::table('sy')->where('isactive','1')->first()->id;
      $semid = DB::table('semester')->where('isactive','1')->first()->id;
      function randomHex() {
   $chars = 'ABCDEF0123456789';
   $color = '#';
   for ( $i = 0; $i < 6; $i++ ) {
      $color .= $chars[rand(0, strlen($chars) - 1)];
   }
   return $color;
}
      if(count($gradelevels)>0)
      {
        foreach($gradelevels as $gradelevel)
        {
          if($gradelevel->acadprogid == 6)
          {
            $gradelevel->studentids = DB::table('college_enrolledstud')->select('studid')->where('syid', $syid)->where('semid', $semid)->where('yearLevel', $gradelevel->id)->whereIn('studstatus',[1,2,4])->where('deleted','0')->get();
          }
          elseif($gradelevel->acadprogid == 5)
          {
            $gradelevel->studentids = DB::table('sh_enrolledstud')->select('studid')->where('syid', $syid)->where('semid', $semid)->where('levelid', $gradelevel->id)->whereIn('studstatus',[1,2,4])->where('deleted','0')->get();
          }else{
            $gradelevel->studentids = DB::table('enrolledstud')->select('studid')->where('levelid', $gradelevel->id)->where('syid', $syid)->whereIn('studstatus',[1,2,4])->where('deleted','0')->get();
          }
            $gradelevel->numberofstudents = count($gradelevel->studentids);
            $gradelevel->levelnameatt = $gradelevel->levelname;
            $gradelevel->levelname = $gradelevel->levelname.' ('.count($gradelevel->studentids).')';
          $gradelevel->attendance = DB::table('studattendance')->where('syid', $syid)->where('semid', $semid)->where('absent', 0)->where('tdate', date('Y-m-d'))->whereIn('studid', collect($gradelevel->studentids)->pluck('studid'))->count();
          $gradelevel->color = randomHex();
        }
      }
            // $schoolcalendar = DB::table('schoolcal')
            //     ->select('schoolcal.id','schoolcal.description','schoolcal.datefrom','schoolcal.dateto','schoolcaltype.typename','schoolcal.noclass','schoolcal.annual')
            //     ->join('schoolcaltype','schoolcal.type','=','schoolcaltype.id')
            //     ->where('schoolcal.deleted','0')
            //     ->get();
    @endphp
  <section class="content-header">
      <div class="card m-0" style="border: none; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
        <div class="card-body">
          {{-- {{Session::get('schoolinfo')->id}} --}}
          <h1 class="m-0" style="font-size: 30px;">{{Session::get('schoolinfo')->schoolname}}</h1>
        </div>
      </div>
  </section>
  <section class="content pt-0">
    {{-- <div class="container"> --}}
      <div class="row">
        <div class="col-md-4">
          <div class="info-box bg-success" style="border: none; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
            {{-- <span class="info-box-icon"><i class="far fa-calendar"></i></span> --}}
            <div class="info-box-content">
              <h2 class="text-bold" id="h2-sy"></h2>
              <h1 class="text-bold" id="h2-semester"></h1>
            </div>
            </div>
          
          <div class="card" style="border: none; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
            <div class="card-header">
              <h3 class="card-title">Employee Attendance</h3>
              <div class="card-tools text-secondary">
                {{-- <button type="button" class="btn btn-tool" data-card-widget="maximize">
                <i class="fas fa-expand"></i>
                </button> --}}
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
              </div>          
            </div>          
            <div class="card-body p-1">
              <input type="text" class="form-control form-control-sm mb-2" placeholder="Search Employee..." id="filter-attendance"/>
              <div id="container-attendance" style="height: 480px; overflow: scroll;">
                {{-- @if(count($employees)> 0)
                    @foreach($employees as $employee)
                        <div class="row eachemployee m-0" data-string="{{$employee->lastname}}, {{$employee->firstname}} {{$employee->suffix}}<" style="border: 1px solid #ddd; width: 100%;">
                            <div class="col-md-12">
                              <small>@if($employee->lastactivity == '' || $employee->lastactivity == null)<span class="float-left badge badge-warning mt-1">ABSENT</span>@else<span class="float-left badge badge-success mt-1">PRESENT</span>@endif</small>&nbsp;&nbsp;
                              <small><span class="badge badge-info float-left mt-1">{{$employee->lastactivity}}</span></small> <small><strong>{{strtoupper($employee->lastname)}}</strong>,</small> <small>{{ucwords(strtolower($employee->firstname))}}</small>
                            </div>
                        </div>
                    @endforeach
                @endif --}}
              </div>
            </div>
          </div> 
          {{-- <div class="card" style="border: none; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
            <div class="card-header">
              <h3 class="card-title">Designations</h3>
              <div class="card-tools text-secondary">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
              </div>          
            </div>          
            <div class="card-body p-1">
              @foreach($designations as $designation)
                <button type="button" class="btn btn-sm btn-default p-0 pr-1 pl-1 mr-1 mb-1">{{ucwords(strtolower($designation->designation))}} <span class="badge badge-success">({{collect($employees)->where('usertypeid', $designation->id)->count()}})</span></button>
              @endforeach
            </div>
          </div>          --}}
        </div>
        <div class="col-md-8">
          
          <div class="info-box" style="background-color: #d4edda;  border-color: #c3e6cb; border: none; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
            <span class="info-box-icon text-success"><i class="far fa-calendar"></i></span>
            <div class="info-box-content text-right">
              <h1 class="text-bold text-success" style="font-size: 45px;">{{date('l')}}</h1>
              <h3 class="text-bold text-success">{{date('M d, Y')}} <span id="clock-wrapper">{{date('h:i:s')}}</span></h3>
            </div>
            
            </div>
            <div class="card card-primary tschoolcalendar">
                <div class="card-header bg-success">
                    <h3 class="card-title">School Calendar</h3>
                </div>
                <div class="card-body p-1">
                    <div class="calendarHolder">
                        <div id='newcal'></div>
                    </div>
                </div>
            </div>
          
          {{-- <div class="card" style="border: none; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
            <div class="card-header">
              <h3 class="card-title">
                Number of Students<br/>Today's Present
              </h3>
              <div class="card-tools text-secondary">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
              </div>      
            </div>
            <div class="card-body">
              <div class="row">
                @foreach($gradelevels as $gradelevel)
                  <div class="col-4 mb-1">
                    <button type="button" class="btn btn-sm btn-default btn-block p-0" style="font-size: 11px;">{{$gradelevel->levelnameatt}} 
                      @if($gradelevel->attendance == 0)
                      <span class="badge badge-secondary" style="font-size: 10px;">({{$gradelevel->attendance}})</span>
                      @else
                      <span class="badge badge-success" style="font-size: 10px;">({{$gradelevel->attendance}})</span>
                      @endif
                    </button>
                  </div>
                @endforeach
              </div>
            </div>
          </div> --}}
          {{-- <div class="card" style="border: none; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
            <div class="card-header">
              <h3 class="card-title">
                Enrolled Students Per Grade Level
              </h3>
              <div class="card-tools text-secondary">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
              </div>      
            </div>
            <div class="card-body">
              <canvas id="pieChart" style="height: 500px; max-width: 100%;"></canvas>
            </div>
          </div> --}}
        </div>
      </div>
    {{-- </div> --}}
  </div>

    {{-- <div class="row container">
      <div class="col-lg-5">
      <div class="col-md-12 afinance">
      <div class="card  bg-success">
          <div class="card-header bg-success">
          <h3 class="card-title"><i style="color: #ffc107" class="fas fa-coins"></i> <b>FINANCE</b></h3>
          </div>
          <div class="card-body" style="overflow-y: scroll">
            <a href="/cashtransReport" class="btn btn-app">
              <i class="fas fa-exchange-alt"></i> <b><span>Cash Transaction</span></b>
            </a>
          </div>
      </div>
      </div>

      <div class="col-md-12 aregistrar">
      <div class="card bg-info">
          <div class="card-header bg-info">
          <h3 class="card-title"><i style="color: #ffc107" class="fas fa-user-edit"></i> <b>REGISTRAR</b></h3>
          </div>
          <div class="card-body" style="overflow-y: scroll">
            <a href="/enrollmentReport" class="btn btn-app">
              <i class="fas fa-clipboard"></i> <b><span>Enrollment</span></b>
            </a>
          </div>
      </div>
      </div>

      <div class="col-md-12 aprincipal">
      </div>
      </div>









    </div> --}}
  </section>
@endsection


@section('footerjavascript')
<script src="{{asset('plugins/fullcalendar/main.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar-daygrid/main.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar-timegrid/main.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar-interaction/main.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar-bootstrap/main.min.js')}}"></script>
<script>                
  $.ajax({
      url: '{{$schoolInfo->eslink}}/passData',
      type:'GET',
      dataType: 'json',
      data: {
          action        :  'getschoolyears'
      },
      success:function(data) {
        console.log(data)
        $.each(data, function(key,value){
          if(value.isactive == 1)
          {
            $('#h2-sy').text('S.Y. ' + value.sydesc)
          }
        })
      }
  })         
  $.ajax({
      url: '{{$schoolInfo->eslink}}/passData',
      type:'GET',
      dataType: 'json',
      data: {
          action        :  'getsemesters'
      },
      success:function(data) {
        $.each(data, function(key,value){
          if(value.isactive == 1)
          {
            $('#h2-semester').text(value.semester)
          }
        })
      }
  })
  $.ajax({
      url: '{{$schoolInfo->eslink}}/passData',
      type:'GET',
      dataType: 'json',
      data: {
          action        :  'getemployeeattendance'
      },
      success:function(data) {
        $('#container-attendance').empty();
        $.each(data, function(key, value){

            // var  appendthis = '<div class="row eachemployee m-0" data-string="'+value.lastname+', '+value.firstname+' '+value.suffix+'<" style="border: 1px solid #ddd; width: 100%;">'+
            //     '<div class="col-md-12"><small>';

            //       if(value.lastactivity == '' || value.lastactivity == null)
            //       {
            //         appendthis+='<span class="float-left badge badge-warning mt-1">ABSENT</span>';
            //       }else{
            //         appendthis+='<span class="float-left badge badge-success mt-1">PRESENT</span>';
            //       }

            //       appendthis+='</small>&nbsp;&nbsp;<small><span class="badge badge-info float-left mt-1">'+value.lastactivity+'</span></small> <small><strong>'+value.lastname+'</strong>,</small> <small>'+value.firstname+'</small>';
            //       appendthis+='</div>';
            //       appendthis+='</div>';

            var  appendthis = '<div class="row eachemployee m-0" data-string="'+value.lastname+', '+value.firstname+' '+value.suffix+'<" style="border: 1px solid #ddd; width: 100%;">'+
                '<div class="col-md-12"><small>';
                

                  if(value.lastactivity == '' || value.lastactivity == null)
                  {
                    appendthis+='<span class="float-left badge badge-danger mt-1">ABSENT</span>';
                  }else{
                      var ambadgecolor = 'badge-success';
                    if(value.amin != '00:00:00')
                    {
                      if((value.amin).slice(0,-3) > formatTime(value.customamin))
                      {
                      var ambadgecolor = 'badge-warning';
                      }
                      appendthis+='<span class="float-left badge '+ambadgecolor+' mt-1">AM IN: '+(value.amin).slice(0,-3)+'</span>';
                    }else{
                      if(value.pmin !=  '00:00:00')
                      {
                          if((value.pmin).slice(0,-3) > formatTime(value.custompmin))
                          {
                          var ambadgecolor = 'badge-warning';
                          }
                        appendthis+='<span class="float-left badge '+ambadgecolor+' mt-1">PM IN: '+(value.pmin).slice(0,-3)+'</span>';
                      }
                    }
                      var pmbadgecolor = 'badge-success';
                    if(value.pmout !=  '00:00:00')
                    {
                          if((value.pmout).slice(0,-3) > formatTime(value.custompmout))
                          {
                          var pmbadgecolor = 'badge-warning';
                          }
                      appendthis+='<span class="float-left badge '+pmbadgecolor+' mt-1">PM OUT: '+(value.pmout).slice(0,-3)+'</span>';
                    }else{
                      if(value.amout !=  '00:00:00')
                      {
                          if((value.amout).slice(0,-3) < formatTime(value.customamout))
                          {
                          var pmbadgecolor = 'badge-warning';
                          }
                        appendthis+='<span class="float-left badge '+pmbadgecolor+' mt-1">AM OUT: '+(value.amout).slice(0,-3)+'</span>';
                      }
                    }
                  }

                  appendthis+='<small><strong>'+value.lastname+'</strong>,</small> <small>'+value.firstname+'</small>';
                  appendthis+='</div>';
                  appendthis+='</div>';
                  
        $('#container-attendance').append(appendthis);
        })
      }
  })

  
  $("#filter-attendance").on("keyup", function() {
        var input = $(this).val().toUpperCase();
        var visibleCards = 0;
        var hiddenCards = 0;

        $("#container-attendance").append($("<div class='card-group card-group-filter'></div>"));


        $(".eachemployee").each(function() {
            if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

            $(".card-group.card-group-filter:first-of-type").append($(this));
            $(this).hide();
            hiddenCards++;

            } else {

            $(".card-group.card-group-filter:last-of-type").prepend($(this));
            $(this).show();
            visibleCards++;

            if (((visibleCards % 4) == 0)) {
                $("#container-attendance").append($("<div class='card-group card-group-filter'></div>"));
            }
            }
        });

    });

    $.ajax({
        url: '{{$schoolInfo->eslink}}/passData',
        type:'GET',
        // dataType: 'json',
        data: {
            action        :  'getschoolcalendar'
        },
        success:function(data) {
          schoolcalendar = data;
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

          $.each(schoolcalendar, function(key, val){
            if(val.noclass == 1)
            {
                  var backgroundcolor = '#dc3545';
            }else{
                  var backgroundcolor = '#00a65a';
            }
              schedule.push({
                  title          : val.description,
                  start          : val.datefrom,
                  end            : val.dateto,
                  backgroundColor: backgroundcolor,
                  borderColor    : backgroundcolor,
                  allDay         : true,
                  id              : val.id
              })
          })


          var Calendar = FullCalendar.Calendar;

          console.log(schedule);

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
          
        setInterval(function() {
            var date = new Date();
            $('#clock-wrapper').html(
                date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds()
                );
        }, 500);


      
        }
    })
</script>

<script>
  $(document).ready(function(){
  })

</script>

@endsection