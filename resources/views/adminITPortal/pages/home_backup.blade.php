@extends('adminITPortal.layouts.app')


@section('pagespecificscripts')
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
</style>

@endsection


@section('content')
  <section class="content-header">
    <div class="container-fluid">
          <div class="row mb-2">
                <div class="col-sm-6">
                      <h1 class="m-0 text-dark">{{Session::get('schoolinfo')->schoolname}}</h1>
                </div>
                <div class="col-sm-6">
                </div>
          </div>
    </div>
  </section>
  <section class="content p-0">
    <div class="row container">
      <div class="col-lg-5">
      <div class="col-md-12 afinance">
      <div class="card  bg-success">
          <div class="card-header bg-success">a
          <h3 class="card-title"><i style="color: #ffc107" class="fas fa-coins"></i> <b>FINANCE</b></h3>
          </div>
          <div class="card-body" style="overflow-y: scroll">
            {{-- <a href="#" class="btn btn-app">
              <i class="fas fa-cubes"></i> <b><span>Enrollment</span></b>
            </a> --}}
            <a href="/cashtransReport" class="btn btn-app">
              <i class="fas fa-exchange-alt"></i> <b><span>Cash Transaction</span></b>
            </a>
            {{-- <a href="#" class="btn btn-app">
              <i class="fas fa-money-bill-wave"></i> <b><span>Cash Reciept Summary</span></b>
            </a> --}}
          </div>
          {{-- <div class="card-footer">
            <a href="/gotoPortal/4" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div> --}}
      </div>
      <!-- /.card -->
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
            {{-- <a href="#" class="btn btn-app">
              <i class="fas fa-th-list"></i> <b><span>Strand List</span></b>
            </a>
            <a href="#" class="btn btn-app">
              <i class="fas fa-list-alt"></i> <b><span>Track List</span></b>
            </a> --}}
          </div>
          {{-- <div class="card-footer">
            <a href="/gotoPortal/3" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div> --}}
      </div>
      <!-- /.card -->
      </div>

      <div class="col-md-12 aprincipal">
      {{-- <div class="card bg-gray">
          <div class="card-header bg-gray">
          <h3 class="card-title"><i style="color: #ffc107" class="fas fa-users"></i> <b>PRINCIPAL</b></h3>
          </div>
          <div class="card-body" style="overflow-y: scroll">
            <a href="#" class="btn btn-app">
              <span class="badge bg-info">12</span>
              <i class="fas fa-chalkboard-teacher"></i> <b><span>Teachers</span></b>
            </a>

            <a href="#" class="btn btn-app">
              <span class="badge bg-info">12</span>
              <i class="fas fa-user"></i> <b><span>Students</span></b>
            </a>

            <a href="#" class="btn btn-app">
              <span class="badge bg-info">12</span>
              <i class="fas fa-file-alt"></i> <b><span>Subjects</span></b>
            </a>
          </div>
          <div class="card-footer">
            <a href="/gotoPortal/2" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
      </div> --}}
      <!-- /.card -->
      </div>
      </div>

      <!--  -->
      {{-- <div class="col-lg-7">
      <div class="col-md-12 afacstaff">
      <div class="card">
          <div class="card-header">
          <h3 class="card-title"><i style="color: #ffc107" class="fas fa-users"></i> <b>FACULTY AND STAFF</b></h3>
          <div class="input-group input-group-sm w-50 float-right search">
            <input type="text" id="search" name="search" class="form-control float-right" placeholder="Search">
            <div class="input-group-append">
                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
            </div>
          </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body pt-0">
          <table class="table table-hover table-sm  p-0" style="min-width:500px; table-layout:fixed;">
            <thead>
              <tr>
                <th width="20%">Email</th>
                <th>Name</th>
                <th>Designation</th>
              </tr>
            </thead>
            <tbody>
                @foreach($fsinfo[0]->data as $item)
                <tr>
                  <td>{{$item->email}}</td>
                  <td><a href="#">{{$item->firstname}} {{$item->middlename}} {{$item->lastname}} {{$item->suffix}}</a></td>
                  <td>{{$item->utype}}</td>
                </tr> 
                @endforeach 
            </tbody>
          </table>
          </div>
          <!-- /.card-body -->
          
      </div>
      <!-- /.card -->
      </div>
      <div class="col-md-12 astudentlist">
      <div class="card">
          <div class="card-header">
          <h3 class="card-title"><i style="color: #ffc107" class="fas fa-users"></i> <b>STUDENT LIST</b></h3>
          <div class="input-group input-group-sm w-50 float-right search">
            <input type="text" id="search" name="search" class="form-control float-right" placeholder="Search">
            <div class="input-group-append">
                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
            </div>
          </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body pt-0">
          <table class="table table-hover table-sm" style="min-width:500px; table-layout:fixed;">
            <thead>
              <tr>
                <th>Level</th>
                <th>Total Numbers</th>
              </tr>
            </thead>
            <tbody>
                @foreach($glevel as $item)
                <tr>
                  <td><a href="#">{{$item->levelname}}</a></td>
                  <center><td>{{$item->studCount}}</td></center>
                </tr> 
                @endforeach
            </tbody>
          </table>
          </div>
          <!-- /.card-body -->
          
      </div>
      <!-- /.card -->
      </div>

      <div class="col-md-12 aschoolcalendar"><div class="card">
        <div class="card-header bg-primary">
        <div class="row">
        <h3 class="card-title"><i style="color: #ffc107" class="fas fa-users"></i> <b>School Calendar</b></h3>
        </div>
          <button class="btn btn-sm btn-outline-primary bg-white pt-1" data-toggle="modal" data-target="#modal-primary" title="Contacts" data-widget="chat-pane-toggle" style="color: #000"><i class="fas fa-plus"></i> Add Holiday</button>
          <div class="input-group input-group-sm w-50 float-right search">
            <input type="text" id="search" name="search" class="form-control float-right" placeholder="Search">
            <div class="input-group-append">
                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
            </div>
          </div>
        </div>
        <div class="card-body table-responsive p-0 " id="dataholder">
        <input type="hidden" value="0" id="searchCount">



        <table class="table table-hover smfont table-sm" style="min-width:500px; table-layout:fixed;">
          <thead>
            <tr>
              <th width="2%"></th>
              <th width="20%" class="appadd">DATE</th>
              <th width="10%" class="appadd">DAY</th>
              <th width="29%" class="appadd">DESCRIPTION</th>
              <th width="23%">TYPE</th>
              <th width="8%"></th>
              <th width="8%"></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="7" class="text-center">No results found</td>
            </tr>
          </tbody>
        </table>
            </div>
            <div class="card-footer">
              <div class="mt-3" id="data-container">
            <div class="paginationjs" style="display: none;"><div class="paginationjs-pages"><ul><li class="paginationjs-prev disabled"><a>«</a></li><li class="paginationjs-next disabled"><a>»</a></li></ul></div></div></div>
          </div>
        </div>
          
      <!-- /.card -->
      </div>
      </div> --}}
      <!--  -->
      <div class="col-lg-12">
      

      <!-- <div class="col-md-12 anotification">
      <div class="card">
          <div class="card-header">
          <h3 class="card-title"><i style="color: #ffc107" class="fas fa-users"></i> <b>NOTIFICATION</b></h3>
          </div>
          <div class="card-body">
              <h6><b>REPORTS</b></h6>
          </div>
          
      </div>
      </div> -->
      </div>









    </div>
  </section>
@endsection


@section('footerjavascript')
  <script>
    $(document).ready(function(){
      fetch('http://essentielv2.ck/studentmasterlist?enrolled=enrolled&count=count',{ mode: 'no-cors' })
      
    })

  </script>

@endsection

<script>
  $(document).ready(function(){
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
              id              : '{{$item->id}}'
          })

      @endforeach


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


  //-------------
  //- DONUT CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
  var donutChartCanvas = $('#pieChart').get(0).getContext('2d')
  var donutData        = {
        labels: {!! collect($gradelevels)->pluck('levelname') !!},
    datasets: [
      {
        data: {!! collect($gradelevels)->pluck('numberofstudents') !!},
        backgroundColor : {!! collect($gradelevels)->pluck('color') !!},
      }
    ]
  }
  var donutOptions     = {
    maintainAspectRatio : false,
    responsive : true,
  }
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  new Chart(donutChartCanvas, {
    type: 'doughnut',
    data: donutData,
    options: donutOptions
  })

  
  })

</script>
