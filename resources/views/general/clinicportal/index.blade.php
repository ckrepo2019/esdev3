
<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-interaction/main.min.css')}}">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
<!-- Toastr -->
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
@php

$check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

@endphp

@extends($extends)
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
    .select2-container .select2-selection--single {
            height: 40px !important;
        }
    
    #createappointment .modal {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        overflow: hidden;
    }

    #createappointment .modal-dialog {
        position: fixed;
        margin: 0;
        width: 100%;
        height: 100%;
        padding: 0;
    }

      .list-display li {
        margin-bottom:.4rem;
        font-size:1.1rem;
      }
      .list-checkmarks {
        padding-left:1.5rem;
      }
      .list-checkmarks li {
        list-style-type:none;
        padding-left:1rem;
      }
      .list-checkmarks li:before {    
      font-family: 'FontAwesome';
      content: "\f00c";
      margin:0 10px 0 -28px;
      color: #17aa1c;
      }

    @media (min-width: 576px)
    {
        #createappointment .modal-dialog {
            max-width:  unset !important;
            margin: unset !important;
        }
      #createappointment .modal-content {
          top: 0;
          right: 0;
          bottom: 0;
          left: 0;
      }
    }
    #createappointment .modal-content {
        position: absolute;
        top: 0;
        right: 0;
        /* bottom: 50; */
        bottom: 0;
        left: 0;
        border: 2px solid #3c7dcf;
        border-radius: 0;
        box-shadow: none;
        width: unset !important;
    }


    #createappointment .modal-body {
        position: absolute;
        /* top: 50px; */
        top: 0;
        /* bottom: 60px; */
        bottom: 0;
        width: 100%;
        font-weight: 300;
        overflow: auto;
        background-color: rgba(0,0,0,.0001) !important;
    }
/* Overall body and content */
.contentdate {
    overflow: none;
    /* max-width: 790px; */
    /* padding: 0px 0; */
    /* height: 500px; */
    height: -webkit-fill-available;
    position: relative;
    /* margin: 20px auto; */
    background: #52A0FD;
    background: -moz-linear-gradient(right,  #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);
    background: -webkit-linear-gradient(right,  #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);
    background: linear-gradient(to left,  #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);    
    border-radius: 3px;
    box-shadow: 3px 8px 16px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
    -moz-box-shadow: 3px 8px 16px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
    -webkit-box-shadow: 3px 8px 16px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
}

/*  Events display */
.events-container {
    overflow-y: scroll;
    height: 100%;
    float: right;
    margin: 0px auto; 
    font: 13px Helvetica, Arial, sans-serif; 
    display: inline-block; 
    padding: 0 10px;
    border-bottom-right-radius: 3px;
    border-top-right-radius: 3px;
}
.events-container:after{
    clear:both;
}
.event-card {
    padding: 20px 0;
    width: 350px;
    margin: 20px auto;
    display: block;
    background: #fff;
    border-left: 10px solid #52A0FD;
    border-radius: 3px;
    box-shadow: 3px 8px 16px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
    -moz-box-shadow: 3px 8px 16px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
    -webkit-box-shadow: 3px 8px 16px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
}
.event-count, .event-name, .event-cancelled {
    display: inline;
    padding: 0 10px;
    font-size: 1rem;
}
.event-count {
    color: #52A0FD;
    text-align: right;
}
.event-name {
    padding-right:0;
    text-align: left;
}
.event-cancelled {
    color: #FF1744;
    text-align: right;
}

/*  Calendar wrapper */
.calendar-container  { 
    float: left;
    position: relative;
    margin: 0px auto; 
    height: 100%;
    background: #fff;
    font: 13px Helvetica, Arial, san-serif; 
    display: inline-block; 
    border-bottom-left-radius: 3px;
    border-top-left-radius: 3px;
}
.calendar-container:after{
    clear:both;
}
.calendar {
    display: table;
}

/* Calendar Header */
.year-header { 
    background: #52A0FD;
    background: -moz-linear-gradient(left,  #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);
    background: -webkit-linear-gradient(left,  #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);
    background: linear-gradient(to right,  #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);    
    font-family: Helvetica;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
    -moz-box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
    -webkit-box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
    height: 40px; 
    text-align: center;
    position: relative; 
    color:#fff; 
    border-top-left-radius: 3px;
} 
.year-header span { 
    display:inline-block; 
    font-size: 20px;
    line-height:40px; 
}
.left-button, .right-button { 
    cursor: pointer;
    width:28px; 
    text-align:center; 
    position:absolute; 
} 
.left-button { 
    left:0; 
    -webkit-border-top-left-radius: 5px; 
    -moz-border-radius-topleft: 5px; 
    border-top-left-radius: 5px; 
} 
.right-button { 
    right:0; 
    top:0; 
    -webkit-border-top-right-radius: 5px; 
    -moz-border-radius-topright: 5px; 
    border-top-right-radius: 5px; 
} 
.left-button:hover {
    background: #3FADFF;
}
.right-button:hover { 
    background: #00C1FF;
}

/* Buttons */
.button{
    cursor: pointer;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    outline: none;
    font-size: 1rem;
    border-radius: 25px;
    padding: 0.65rem 1.9rem;
    transition: .2s ease all;
    color: white;
    border: none;
    box-shadow: -1px 10px 20px #9BC6FD;
    background: #52A0FD;
    background: -moz-linear-gradient(left,  #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);
    background: -webkit-linear-gradient(left,  #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);
    background: linear-gradient(to right,  #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);
}
#cancel-button {
    box-shadow: -1px 10px 20px #FF7DAE;
    background: #FF1744;
    background: -moz-linear-gradient(left,  #FF1744 0%, #FF5D95 80%, #FF5D95 100%);
    background: -webkit-linear-gradient(left,  #FF1744 0%, #FF5D95 80%, #FF5D95 100%);
    background: linear-gradient(to right,  #FF1744 0%, #FF5D95 80%, #FF5D95 100%);
}
#close-button {
    display: block;
    position: absolute;
    float: left;
    margin-left: 5%;
    //bottom: 20px;
}
#add-button {
    display: block;
    position: absolute;
    right:20px;
   // bottom: 20px;
}
#cancel-button {
    display: block;
    position: fixed;
    float: left;
    margin-left: 5%;
    bottom: 35px;
}
#ok-button {
    display: block;
    position: fixed;
    right: 20px;
    bottom: 35px;
}
#add-button:hover, #ok-button:hover, #cancel-button:hover {
    transform: scale(1.03);
}
#add-button:active, #ok-button:active, #cancel-button:active {
    transform: translateY(3px) scale(.97);
}

/* Days/months tables */
.days-table, .dates-table, .months-table { 
    border-collapse:separate; 
    text-align: center;
    width: -webkit-fill-available;
} 
.day { 
    height: 26px;
    width: 26px;
    padding: 0 10px;
    line-height: 26px; 
    border: 2px solid transparent;
    text-transform:uppercase; 
    font-size:90%; 
    color:#9e9e9e; 
} 
.month {
    cursor: default;
    height: 26px;
    width: 26px;
    padding: 0 2px;
    padding-top:10px;
    line-height: 26px; 
    text-transform:uppercase; 
    font-size: 11px; 
    color:#9e9e9e; 
    transition: all 250ms;
}
.active-month {
    font-weight: bold;
    font-size: 14px;
    color: #FF1744;
    text-shadow: 0 1px 4px RGBA(255, 50, 120, .8);
}
.month:hover {
    color: #FF1744;
    text-shadow: 0 1px 4px RGBA(255, 50, 120, .8);
}

/*  Dates table */
.table-date {
    cursor: default;
    color:#2b2b2b; 
    height:26px;
    width: 26px;
    font-size: 15px;
    padding: 10px;
    line-height:26px; 
    text-align:center; 
    border-radius: 50%;
    border: 2px solid transparent;
    transition: all 250ms;
}
.table-date:not(.nil):hover { 
    border-color: #FF1744;
    box-shadow: 0 2px 6px RGBA(255, 50, 120, .9);
}
.event-date {
    border-color:#52A0FD;
    box-shadow: 0 2px 8px RGBA(130, 180, 255, .9);
}
.active-date{ 
    background: #FF1744;
    box-shadow: 0 2px 8px RGBA(255, 50, 120, .9);
    color: #fff;
}
.event-date.active-date {
    background: #52A0FD;
    box-shadow: 0 2px 8px RGBA(130, 180, 255, .9);
}

/* input dialog */
.dialog{
    z-index: 5;
    background: #fff;
    position:absolute;
    /* width:415px; */
    width:unset;
    /* height: 500px; */
    height: -webkit-fill-available;
    left:410px;
    border-top-right-radius:3px;
    border-bottom-right-radius: 3px;
    display:none;
    border-left: 1px #aaa solid;
}
.dialog-header {
    margin: 20px;
    color:#333;
    text-align: center;
}
/* .form-container {
    margin-top:25%;
} */
.form-label {
    color:#333;
}
.input {
    border:none;
    background: none;
    border-bottom: 1px #aaa solid;
    display:block;
    margin-bottom:50px;
    width: 200px;
    height: 20px;
    text-align: center;
    transition: border-color 250ms;
}
.input:focus {
    outline:none;
    border: 1px solid #00C9FB;
}
.error-input {
    border: 1px solid #FF1744 !important;
}

/* Tablets and smaller */
@media only screen and (max-width: 780px) {
    .contentdate {
        overflow: visible;
        position:relative;
        max-width: 100%;
        width: 370px;
        height: 100%;
        background: #52A0FD;
        background: -moz-linear-gradient(left,  #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);
        background: -webkit-linear-gradient(left,  #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);
        background: linear-gradient(to right,  #52A0FD 0%, #00C9FB 80%, #00C9FB 100%);  
    }
    .dialog {
        /* width:370px;
        height: 450px; */
        border-radius: 3px;
        top:0;
        left:0;
    }
    .events-container {
        float:none;
        overflow: visible;
        margin: 0 auto;
        padding: 0;
        display: block;
        left: 0;
        border-radius: 3px;
    }

    .calendar-container {
        float: none;
        padding: 0;
        margin: 0 auto;
        margin-right: 0;
        display: block;
        left: 0;
        border-radius: 3px;
        box-shadow: 3px 8px 16px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
        -moz-box-shadow: 3px 8px 16px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
        -webkit-box-shadow: 3px 8px 16px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
    }
}

/* Small phone screens */
@media only screen and (max-width: 400px) {
    .contentdate, .events-container, .year-header, .calendar-container {
        /* width: 320px; */
        width: 100%;
    }
    .dialog {
        /* width: 320px; */
        width: 100%;
    }
    .months-table {
        display: block;
        margin: 0 auto;
        width: 320px;
    }
    .event-card {
        width: 300px;
    }
    .day {
        padding: 0 7px;
    }
    .month {
        display: inline-block;
        padding: 10px 10px;
        font-size: .8rem;
    }
    .table-date {
        width: 20px;
        height: 20px;
        line-height: 20px;
    }
    .event-name, .event-count, .event-cancelled {
        font-size: .8rem;
    }
    .add-button{
        bottom: 10px;
        right: 10px;
        padding: 0.5rem 1.5rem;
    }
} 
</style>
@section('content')
@php
    use \Carbon\Carbon;
    $now = Carbon::now();
    $comparedDate = $now->toDateString();
@endphp
{{-- <div class="row">
    <div class="col-md-12">
      <label>Appointment Timeline</label>
    </div>
    <div class="col-md-12">
      <label>Appointment Timeline</label>
    </div>
</div> --}}
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Create Appointment</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Create Appointment</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<section class="content">
  <div class="container-fluid">
    <!-- Timelime example  -->
    <div class="row">
      <div class="col-md-8">
        <!-- The time line -->
        @if(count($myappointments)>0)
          @foreach($myappointments as $myappointment)
          <div class="timeline" data-id="timeline{{$myappointment->id}}">
              <!-- timeline time label -->
              <div class="time-label">
                <span class="bg-success">{{date('d M. Y', strtotime($myappointment->adate))}} </span>
              </div>
              <!-- /.timeline-label -->
              <!-- timeline item -->
              <div>
                <i class="fas fa-share bg-blue"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fas fa-clock"></i> {{date('d M. Y h:i A', strtotime($myappointment->createddatetime))}}</span>
                  <h3 class="timeline-header"><a href="#">Appointment added</a> </h3>

                  <div class="timeline-body text-muted">
                    <label class="mb-0">Description:</label>
                    {{$myappointment->description}}
                    <br/>
                    <label class="mb-0">Time Slot:</label>
                    @if($myappointment->atime != null)
                      {{date('h:i A', strtotime($myappointment->atime))}}
                    @endif
                    <br/>
                    @if($myappointment->admitted == '0')
                      <label class="mb-0">Available:</label>
                    @else
                      <label class="mb-0">Assigned Doctor:</label>
                    @endif
                    @php
                    $time = $myappointment->atime;
                    $doctors = DB::table('clinic_schedavailability')
                                ->where('deleted', 0)
                                ->where('scheddate', $myappointment->adate)
                                ->whereRaw('? between timefrom and timeto', [$time])
                                ->get();
                    $experiences = DB::table('clinic_experiences')
                                ->where('deleted','0')
                                ->orderBy('description')
                                ->get();
                    @endphp
                    @if(count($doctors) > 0)
                    @foreach($doctors as $doctor)
                          @php
                          $name = DB::table('users')
                                    ->where('id', $doctor->createdby)
                                    ->first();
                          @endphp
                          <ul class="list-display list-checkmarks">
                            <li>Dr. {{$name->name}}</li> </ul>
                    @endforeach
                    @else
                      No Doctor Available
                    @endif
                    {{-- @if($doctors)
                    @
                      Available: Dr. {{$name->name}}
                    <br/>
                    @else
                      No Doctor Available
                      <br/>

                    @endif --}}
                    <br/>
                    <label class="mb-0">Status:</label>
                    @if($myappointment->admitted == '1')
                      <span class="badge badge-success">Approved</span>
                      @if($myappointment->label == 1)
                        <span class="badge badge-success">Done</span>
                      @else
                        @if(date('H')>= 8)
                          <span class="badge badge-danger">Missed</span>
                        @else
                          <span class="badge badge-warning">...</span>
                        @endif
                      @endif
                    @else
                      <span class="badge badge-warning">Pending</span>
                    @endif
                  </div>
                  @if($myappointment->admitted != '1')
                  <div class="timeline-footer">
                    <a type="button" class="btn btn-warning btn-sm btn-editappointment" data-id="{{$myappointment->id}}">Edit</a>
                    <a type="button" class="btn btn-danger btn-sm btn-deleteappointment" data-id="{{$myappointment->id}}">Delete</a>
                  </div>
                  @endif
                </div>
              </div>
              </div>
          @endforeach
        @endif
        </div>
        <div class="col-md-4">
          <button type="button" class="btn btn-block btn-success mb-3" id="btn-addappointment"><i class="fa fa-plus"></i> Appointment</button>
          <div class="info-box mb-3 bg-info">
            <span class="info-box-icon"><i class="fas fa-share"></i></span>
  
            <div class="info-box-content">
              <span class="info-box-text">Appointment Applied {{count($myappointments)}}</span>
              {{-- <span class="info-box-number">{{count($myappointments)}}</span> --}}
            </div>
            {{-- <!-- /.info-box-content -->
          </div>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Notifications</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <ul class="products-list product-list-in-card pl-2 pr-2">
                <li class="item">
                  <div class="product-img">
                    <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Samsung TV
                      <span class="badge badge-warning float-right">$1800</span></a>
                    <span class="product-description">
                      Samsung 32" 1080p 60Hz LED Smart HDTV.
                    </span>
                  </div>
                </li>
                <!-- /.item -->
                <li class="item">
                  <div class="product-img">
                    <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Bicycle
                      <span class="badge badge-info float-right">$700</span></a>
                    <span class="product-description">
                      26" Mongoose Dolomite Men's 7-speed, Navy Blue.
                    </span>
                  </div>
                </li>
                <!-- /.item -->
                <li class="item">
                  <div class="product-img">
                    <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">
                      Xbox One <span class="badge badge-danger float-right">
                      $350
                    </span>
                    </a>
                    <span class="product-description">
                      Xbox One Console Bundle with Halo Master Chief Collection.
                    </span>
                  </div>
                </li>
                <!-- /.item -->
                <li class="item">
                  <div class="product-img">
                    <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">PlayStation 4
                      <span class="badge badge-success float-right">$399</span></a>
                    <span class="product-description">
                      PlayStation 4 500GB Console (PS4)
                    </span>
                  </div> --}}
                </li>
                <!-- /.item -->
              </ul>
            </div>
            <!-- /.card-body -->
            {{-- <div class="card-footer text-center">
              <a href="javascript:void(0)" class="uppercase">View All Products</a>
            </div>
            <!-- /.card-footer --> --}}
          </div>
        </div>
      </div>
      <!-- /.col -->
    </div>
  </div>
  <!-- /.timeline -->
<div class="modal fade" id="createappointment">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body p-0">
        <div class="contentdate">
        <div class="calendar-container">
          <div class="calendar"> 
            <div class="year-header"> 
              <span class="left-button" id="prev"> &lang; </span> 
              <span class="year" id="label"></span> 
              <span class="right-button" id="next"> &rang; </span>
            </div> 
            <table class="months-table"> 
              <tbody>
                <tr class="months-row">
                  <td class="month">Jan</td> 
                  <td class="month">Feb</td> 
                  <td class="month">Mar</td> 
                  <td class="month">Apr</td> 
                  <td class="month">May</td> 
                  <td class="month">Jun</td> 
                  <td class="month">Jul</td>
                  <td class="month">Aug</td> 
                  <td class="month">Sep</td> 
                  <td class="month">Oct</td>          
                  <td class="month">Nov</td>
                  <td class="month">Dec</td>
                </tr>
              </tbody>
            </table> 
            
            <table class="days-table"> 
              <td class="day">Sun</td> 
              <td class="day">Mon</td> 
              <td class="day">Tue</td> 
              <td class="day">Wed</td> 
              <td class="day">Thu</td> 
              <td class="day">Fri</td> 
              <td class="day">Sat</td>
            </table> 
            <div class="frame"> 
              <table class="dates-table"> 
                  <tbody class="tbody">             
                  </tbody> 
              </table>
            </div> 
            <button class="button" id="close-button" data-dismiss="modal">Close</button>
            <button class="button" id="add-button">Add Appointment</button>
          </div>
        </div>
        <div class="events-container">
        </div>
        <div class="dialog" id="dialog">
            <h2 class="dialog-header"> Add New Event </h2>
                <div class="row m-2">
                  <div class="col-md-12">
                    <label>What are you being seen for?</label>
                    <input type="text" class="form-control" id="feelingtoday"/>
                  </div>
                </div>
                <div class="row m-2">
                  <div class="col-md-12">
                    <label>Please Check if You Have Experienced Any of the Following:</label>
                  </div>
                  @if(count($experiences)>0)
                    @foreach($experiences as $experience)
                    <div class="col-sm-6" data-column="{{$experience->id}}">
                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="checkboxPrimary{{$experience->id}}" class="feelingexperiences" value="{{$experience->id}}">
                                <label for="checkboxPrimary{{$experience->id}}">{{$experience->description}}</label>
                            </div>
                        </div>
                    </div>
                    @endforeach
                  @endif
                </div>
                <div class="row">
                  <div class="col-md-6 p-4">
                    <label>Time Slot</label>
                    <input type="time" class="form-control" id="selectedtimeslot"/>
                  </div>
                </div>
                <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary collapsed-card" style="border: none !important; box-shadow: unset;">
                                <div class="card-header">
                                <h3 class="card-title">Medical History</h3>
                
                                <div class="card-tools pt-2">
                                    <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <!-- /.card-tools -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-md-12 mb-3">
                                            <label><em>Please Answer the Following Medical History Questions:</em></label>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <span>List All Hospitalizations / Surgeries (include dates)</span><br/>
                                            <textarea class="form-control" id="hospitalization"></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <span>Family History of Illness</span><br/>
                                            <textarea class="form-control " id="familyhistory"></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    Do You or Have You Ever Smoked? <br/>
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" id="smokedstatus">
                                                        <label for="smokedstatus">Yes</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    Packs Per Day: <input type="number" step="any" id="packs" style="width: 50%;"/>
                                                </div>
                                                <div class="col-md-4">
                                                    Age Started: <input type="number" step="any" id="agestarted" style="width: 50%;"/>
                                                </div>
                                                <div class="col-md-4">
                                                    Age You Quit: <input type="number" step="any" id="agequit" style="width: 50%;"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    Do You Drink Alcohol? <br/>
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" id="drinkstatus">
                                                        <label for="drinkstatus">Yes</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    Average Drinks Per Day: <input type="number" step="any" id="averagedrink" style="width: 30%;"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label>Current Medications (include strength & dosage)</label><br/>
                                            <textarea class="form-control" id="currentMedications"></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label>Allergies {{--to Medication--}}</label><br/>
                                            <textarea class="form-control" id="allergies"></textarea>
                                             </div>
                                        <input type="button" value="Cancel" class="button" id="cancel-button">
                                        <input type="button" value="Submit" class="button" id="ok-button">
                                  </div>
                                          </div>
                                    </div>
                        
                                                        </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
               
      </div>

        <!-- Dialog Box-->
        {{-- <script
          src="https://code.jquery.com/jquery-3.2.1.min.js"
          integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
          crossorigin="anonymous">
        </script> --}}
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-selectstudent">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Select Student</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
                  <div class="justify-content-between">
                        <label>Student name: <span class="text-danger">*</span></label><br/>
                        <select class="select2" style="width: 100%; height: 1%;" id="selectstudent-studentid">
                        </select>
                  </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close-user">Close</button>
            <button type="button" class="btn btn-primary" id="btn-submit-user"><i class="fa fa-share"></i> Proceed</button>
          </div>
        </div>
        </div>
        </div>


<div class="modal" id="modal-editappointment">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Appointment</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-8 mb-2">
                        <label>Description <span class="text-danger">*</span></label><br/>
                        <input type="text" class="form-control" id="description">
                            
                        </select>
                  </div>
                  <div class="col-md-12 mt-2">
                      <label>Time Slot</label><br/>
                      <input type="time" class="form-control" id="timeslot">
                  </div>
              </div>
          </div>
          <div class="modal-footer">
                  <div class="col-md-4 text-left">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close-editmedication">Close</button>
                </div>
                  <div class="col-md-8 text-right">
                    <button type="button" class="btn btn-primary" id="btn-submit-editappointment" data-id=""><i class="fa fa-edit"></i> Save Changes</button>
                  </div>
          </div>
        </div>
      {{-- <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> --}}
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
</section>
@endsection
@section('footerjavascript')
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- SweetAlert2 -->
<script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<!-- Toastr -->
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<script>
  $(function(){
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    
  })
    $(document).ready(function(){
      $('#btn-submit-user').on('click', function(){
          
          $.each($("#selectstudent-studentid"), function(){
                  $('#createappointment').modal('show')
                  $('#btn-close-user').click()            
                  var userid=($(this).val());
                  console.log(userid);
        
            });
      });
        $('#selectstudent-studentid').select2({
        });
      
        $('#btn-addappointment').on('click', function(){
          var checkbox = document.getElementById("smokedstatus");
          var checkbox2 = document.getElementById("drinkstatus");
          var usertype = <?php echo json_encode($check_refid);?>;
          if(usertype.refid == 23 || usertype.refid == 24){
              $('#modal-selectstudent').modal('show')
          }

          



          


          if(usertype.refid == 23 || usertype.refid == 24){
          $.ajax({
                    url:    '/clinic/complaints/getallusers',
                    type:   'GET',
                    success:function(data)
                    {
                        
                        $('#selectstudent-studentid').append(data)
                        
                        // $('#editcomplaint-complainant').val(userid)
                    }
                })

              }else{
          $('#createappointment').modal('show')
            }
        });
        var date = new Date();
        var today = date.getDate();
        // Set click handlers for DOM elements
        $(".right-button").click({date: date}, next_year);
        $(".left-button").click({date: date}, prev_year);
        $(".month").click({date: date}, month_click); 
        $("#add-button").click({date: date}, new_event);
        // Set current month as active
        $(".months-row").children().eq(date.getMonth()).addClass("active-month");
        init_calendar(date);
        var events = check_events(today, date.getMonth()+1, date.getFullYear());
        show_events(events, months[date.getMonth()], today);

        $('#btn-addappointment').on('click', function(){

        })
    });



// Initialize the calendar by appending the HTML dates
function init_calendar(date) {
    $(".tbody").empty();
    $(".events-container").empty();
    var calendar_days = $(".tbody");
    var month = date.getMonth();
    var year = date.getFullYear();
    var day_count = days_in_month(month, year);
    var row = $("<tr class='table-row'></tr>");
    var today = date.getDate();


    // Set date to 1 to find the first day of the month
    date.setDate(1);
    var first_day = date.getDay();
    // 35+firstDay is the number of date elements to be added to the dates table
    // 35 is from (7 days in a week) * (up to 5 rows of dates in a month)
    for(var i=0; i<35+first_day; i++) {
        // Since some of the elements will be blank, 
        // need to calculate actual date from index
        var day = i-first_day+1;
        // If it is a sunday, make a new row
        if(i%7===0) {
            calendar_days.append(row);
            row = $("<tr class='table-row'></tr>");
        }
        // if current index isn't a day in this month, make it blank
        if(i < first_day || day > day_count) {
            var curr_date = $("<td class='table-date nil'>"+"</td>");
            row.append(curr_date);
        }   
        else {
            var curr_date = $("<td class='table-date'>"+day+"</td>");
            var events = check_events(day, month+1, year);
            if(today===day && $(".active-date").length===0) {
                curr_date.addClass("active-date");
                show_events(events, months[month], day);
            }
            // If this date has any events, style it with .event-date
            if(events.length!==0) {
                curr_date.addClass("event-date");
            }
            // Set onClick handler for clicking a date
            curr_date.click({events: events, month: months[month], day:day}, date_click);
            row.append(curr_date);
        }
    }
    // Append the last row and set the current year
    calendar_days.append(row);
    $(".year").text(year);
}

// Get the number of days in a given month/year
function days_in_month(month, year) {
    var monthStart = new Date(year, month, 1);
    var monthEnd = new Date(year, month + 1, 1);
    return (monthEnd - monthStart) / (1000 * 60 * 60 * 24);    
}

// Event handler for when a date is clicked
function date_click(event) {
    $(".events-container").show(250);
    $("#dialog").hide(250);
    $(".active-date").removeClass("active-date");
    $(this).addClass("active-date");
    show_events(event.data.events, event.data.month, event.data.day);
};

// Event handler for when a month is clicked
function month_click(event) {
    $(".events-container").show(250);
    $("#dialog").hide(250);
    var date = event.data.date;
    $(".active-month").removeClass("active-month");
    $(this).addClass("active-month");
    var new_month = $(".month").index(this);
    date.setMonth(new_month);
    init_calendar(date);
}

// Event handler for when the year right-button is clicked
function next_year(event) {
    $("#dialog").hide(250);
    var date = event.data.date;
    var new_year = date.getFullYear()+1;
    $("year").html(new_year);
    date.setFullYear(new_year);
    init_calendar(date);
}

// Event handler for when the year left-button is clicked
function prev_year(event) {
    $("#dialog").hide(250);
    var date = event.data.date;
    var new_year = date.getFullYear()-1;
    $("year").html(new_year);
    date.setFullYear(new_year);
    init_calendar(date);
}

// Event handler for clicking the new event button
function new_event(event) {
  var usertype = <?php echo json_encode($check_refid);?>;
  var checkbox = document.getElementById("smokedstatus");
  var checkbox2 = document.getElementById("drinkstatus");
  if(usertype.refid == 23 || usertype.refid == 24){
          $.each($("#selectstudent-studentid"), function(){
                      uid=($(this).val());
                      console.log('dsfdsfd',uid);
            
                });
                  }else{
                  uid = <?php echo json_encode(auth()->user()->id);?>;
                  }

          $.ajax({
                    url:'/clinic/medicalhistory/gethistory',
                            type:'GET',
                            dataType: 'json',
                            data: {
                                id      :  uid
                            },
                            success:function(data1) {
                                console.log(data1)
                              
                                if(data1.length> 0){
                                    console.log("Success");
                                    $('#hospitalization').val(data1[0].hospitalization);
                                    $('#familyhistory').val(data1[0].familyhistory);
                                    $('#agestarted').val(data1[0].agestarted);
                                    $('#agequit').val(data1[0].agequit);
                                    $('#packs').val(data1[0].packs);
                                    if(data1[0].smoke == 0){
                                        checkbox.checked = true;
                                    }
                                    if(data1[0].alcohol == 0){
                                        checkbox2.checked = true;
                                    }
                                    $('#averagedrink').val(data1[0].averagedrink);
                                    $('#currentMedications').val(data1[0].currentMedications);
                                    $('#allergies').val(data1[0].allergies);
                                }else{
                                    $('#hospitalization').val("");
                                    $('#familyhistory').val("");
                                    $('#agestarted').val("");
                                    $('#agequit').val("");
                                    $('#packs').val("");
                                    checkbox.checked = false;
                                    checkbox2.checked = false;
                                    $('#averagedrink').val("");
                                    $('#currentMedications').val("");
                                    $('#allergies').val("");
                                }
        
                            }
            })
  var currentdate = new Date();

    // if a date isn't selected then do nothing
   var day = parseInt($(".active-date").html());
   var date = event.data.date.getDate() + day - 1;
   var month = event.data.date.getMonth() *1000;
   var year = event.data.date.getFullYear() ;
   var date2 = currentdate.getDate();
   var month2 = currentdate.getMonth() *1000;
   var year2 = currentdate.getFullYear();
   var num1 = date+month+year;
   var num2 = date2+month2+year2;
   var check = 0;

   if(year > year2){
       check = 1;
   }
  console.log(num1);
  console.log(num2);
  console.log(typeof check);
  if(check == 0){
  if (num1 < num2 || year < year2) {
      Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Please ensure the selected date is not in the past.'
                    
                    })
    return;
    }
    }

    if($(".active-date").length===0)
        return;
    // remove red error input on click
    $("input").click(function(){
        $(this).removeClass("error-input");
    })
    // empty inputs and hide events
    $("#dialog input[type=text]").val('');
    // $("#dialog input[type=number]").val('');
    $(".events-container").hide(250);
    $("#dialog").show(250);
    // Event handler for cancel button
    $("#cancel-button").click(function() {
        $("#feelingtoday").removeClass("error-input");
        // $("#count").removeClass("error-input");
        $("#dialog").hide(250);
        $(".events-container").show(250);
    });
    // Event handler for ok button
    $("#ok-button").unbind().click({date: event.data.date}, function() {
        var usertype = <?php echo json_encode($check_refid);?>;
        var date = event.data.date;
        if(usertype.refid == 23 || usertype.refid == 24){
          $.each($("#selectstudent-studentid"), function(){
                      $('#createappointment').modal('show')
                      $('#btn-close-user').click()            
                      uid=($(this).val());
                      console.log(uid);
            
                });
        }else{
        uid = <?php echo json_encode(auth()->user()->id);?>;
        }
        console.log("Hola");
        var feelingtoday = $("#feelingtoday").val().trim();
        // var count = parseInt($("#count").val().trim());
        var experienced = [];
        $('.feelingexperiences:checked').each(function(){
          experienced.push($(this).val())
        })

        
        var day = parseInt($(".active-date").html());
        var month = $(".active-month").text();
        // Basic form validation
        if(feelingtoday.length === 0) {
            $("#feelingtoday").addClass("error-input");
        }
        else {
            $("#dialog").hide(250);
            var selectedtimeslot = $('#selectedtimeslot').val();
            var hospitalization = $('#hospitalization').val();
            var familyhistory = $('#familyhistory').val();
            var packs = $('#packs').val();
            var agestarted = $('#agestarted').val();
            var agequit   = $('#agequit').val();
            var averagedrink = $('#averagedrink').val();
            var currentMedications = $('#currentMedications').val();
            var allergies = $('#allergies').val();
            var smokedstatus;
            var drinkstatus;
            var i = document.querySelector('#smokedstatus:checked') !== null;
            var j = document.querySelector('#drinkstatus:checked') !== null;

            if(i==true){
                            smokedstatus = 0;
                        }else{
                            smokedstatus = 1;
                        }
            if(j==true){
                            drinkstatus = 0;
                        }else{
                            drinkstatus = 1;
                        }
            console.log(drinkstatus, 'and', smokedstatus);
            $.ajax({
              url:'/clinic/patientdashboard/createapp',
              type:'GET',
              dataType:'json',
              data:{
                uid                 : uid,
                month               : month,
                year                : $('#label').text(),
                feelingtoday        : feelingtoday,
                experienced         : experienced,
                day                 : day,
                timeslot            : $('#selectedtimeslot').val(),
                currentMedications  : currentMedications,
                averagedrink        : averagedrink,
                agequit             : agequit,
                agestarted          : agestarted,
                packs               : packs,
                familyhistory       : familyhistory,
                hospitalization     : hospitalization,
                smokedstatus        : smokedstatus,
                drinkstatus         : drinkstatus,
              },success:function(data)
              {
                if(data == 0)
                {
                  toastr.warning('You already submitted an appointment for the selected date!','Pending Appoinment')
                }else{
                  toastr.success('You successfully submitted an appointment for the selected date!','Submit Appoinment')
                  $('#close-button').click();
                  // window.location.reload();
                }
              },error: function(xhr, status, error) {
                alert(error);
              }
            })
            // // console.log("new event");
            // new_event_json(feelingtoday, experienced, date, day);
            // date.setDate(day);
            // init_calendar(date);
        }
    });
}

// Adds a json event to event_data
function new_event_json(feelingtoday, experienced, date, day) {
  var uid;
  // console.log( $(".year").text())
  // console.log( $(".month").text())
  // console.log( $(".day").text())
  // console.log( $(".timeslot").text())
  // console.log( $(".feelingexperiences").text())
  // console.log( $(".feelingtoday").text())
  // console.log( $(".experienced").text())
  // console.log( $(".selectedtimeslot").text())
  // console.log( $(".selectedtimeslot").val()) 
    var event = {
        "feelingtoday": feelingtoday,
        "experienced": experienced,
        "year": date.getFullYear(),
        "month": date.getMonth()+1,
        "day": day
    };
    var usertype = <?php echo json_encode($check_refid);?>;
    if(usertype.refid == 23 || usertype.refid == 24 ){
      $.each($("#selectstudent-studentid"), function(){
                  $('#createappointment').modal('show')
                  $('#btn-close-user').click()            
                  uid=($(this).val());
                  console.log(uid);
        
            });
    }else{
    uid = <?php echo json_encode(auth()->user()->id);?>;
    }

    
    $.ajax({
      url: '/clinic/patientdashboard/createapp',
      type: 'GET',
      dataType: 'json',
      data: {
        uid : uid,
        feelingtoday : feelingtoday,
        experienced : experienced,
        year :  $(".year").text(),
        month : date.getMonth()+1,
        day : day
      }, success:function()
      {
        if(data == '1')
        {
          event_data["appointments"].push(event);
        }else{

        }
      }
    })
}

// Display all events of the selected date in card views
function show_events(events, month, day) {
      // console.log(events)
    // Clear the dates container
    $(".events-container").empty();
    $(".events-container").show(250);
    // console.log(event_data["events"]);
    // If there are no events for this date, notify the user
    
    if(events.length===0) {
        var event_card = $("<div class='event-card'></div>");
        var event_name = $("<div class='event-name'>There are no appointments admitted for "+month+" "+day+".</div>");
        $(event_card).css({ "border-left": "10px solid #FF1744" });
        $(event_card).append(event_name);
        $(".events-container").append(event_card);
    }
    else {
        // Go through and add each event as a card to the events container
        for(var i=0; i<events.length; i++) {
          $.ajax({
            url: '/clinic/patientdashboard/getappointments',
            type: 'GET',
            dataType: 'json',
            data: {
              year :  events[i]['year'],
              month : events[i]['month'],
              day : events[i]['day']
            }, success:function(data)
            {
              if(data.length==0)
              {
                  var event_card = $("<div class='event-card'></div>");
                  var event_name = $("<div class='event-name'>There are no appointments admitted for "+month+" "+day+".</div>");
                  $(event_card).css({ "border-left": "10px solid #FF1744" });
                  $(event_card).append(event_name);
                  $(".events-container").append(event_card);
              }else{
                $.each(data, function(key, value){
                  $('#appid'+value.id).remove()
                  var event_card = $("<div class='event-card' id='appid"+value.id+"'></div>");
                  // var event_count = $("<div class='event-count'>"+events[i]["experienced"]+" Invited</div>");
                  if(value.createdby == '{{auth()->user()->id}}')
                  {
                    if(value.admitted == 1)
                    {
                      var event_name = "<div class='row'>"+
                                          "<div class='col-md-12'><label>You submitted an appointment</label></div>"+
                                          "<div class='col-md-12'><label>Time Slot:  "+value.atime+"</label></div>"+
                                          "<div class='col-md-12'><label>Status:  Admitted</label></div>"+
                                      "</div>";
                      $(event_card).css({
                          "border-left": "10px solid #28a745"
                      });
                    }else{
                      var event_name = "<div class='row'>"+
                                          "<div class='col-md-12'><label>You submitted an appointment</label></div>"+
                                          "<div class='col-md-12'><label>Time Slot:  "+value.atime+"</label></div>"+
                                          "<div class='col-md-12'><label>Status:  Pending</label></div>"+
                                      "</div>";
                      $(event_card).css({
                          "border-left": "10px solid #f0ad4e"
                      });
                    }
                  }else{
                    var event_name ="<div class='row'>"+
                                        "<div class='col-md-12'><label>Time Slot:  "+value.atime+"</label></div>"+
                                    "</div>";;
                    $(event_card).css({
                        "border-left": "10px solid #ffc107"
                    });
                  }
                  $(event_card).append(event_name);
                  $(".events-container").append(event_card);
                })
              }
            }
          })
            // var event_card = $("<div class='event-card'></div>");
            // var event_name = $("<div class='event-name'>"+events[i]["feelingtoday"]+":</div>");
            // var event_count = $("<div class='event-count'>"+events[i]["experienced"]+" Invited</div>");
            // if(events[i]["cancelled"]===true) {
            //     $(event_card).css({
            //         "border-left": "10px solid #FF1744"
            //     });
            //     event_count = $("<div class='event-cancelled'>Cancelled</div>");
            // }
            // $(event_card).append(event_name).append(event_count);
            // $(".events-container").append(event_card);
        }
    }
    // $.ajax({
    //   url: '/clinic/patientdashboard/getappointments',
    //   type: 'GET',
    //   dataType: 'json',
    //   data: {
    //     feelingtoday : feelingtoday,
    //     experienced : experienced,
    //     year :  $(".year").text(),
    //     month : date.getMonth()+1,
    //     day : day
    //   }, success:function()
    //   {
    //     if(data == '1')
    //     {
    //       event_data["appointments"].push(event);
    //     }else{

    //     }
    //   }
    // })
}

// Checks if a specific date has any events
function check_events(day, month, year) {
    var events = [];
    for(var i=0; i<event_data["appointments"].length; i++) {
        var event = event_data["appointments"][i];

        if(event["day"]==day &&
            event["month"]==month &&
            event["year"]==year) {
                events.push(event);
            }
    }
// console.log(events)
    // console.log(day)
    // console.log(month)
    // console.log(year)
    // console.log(events)
    // console.log(event_data)
    return events;
}

var appointmentdates = @php echo json_encode($appointmentdates); @endphp;
// console.log(appointmentdates)
// Given data for events in JSON format
var event_data = {
    "appointments": appointmentdates
    // [
    // {
    //     "feelingtoday": " Repeated Test Event ",
    //     "experienced": 120,
    //     "year": 2017,
    //     "month": 5,
    //     "day": 10,
    //     "cancelled": true
    // },
    // {
    //     "feelingtoday": " Repeated Test Event ",
    //     "experienced": 120,
    //     "year": 2017,
    //     "month": 5,
    //     "day": 10,
    //     "cancelled": true
    // },
    //     {
    //     "feelingtoday": " Repeated Test Event ",
    //     "experienced": 120,
    //     "year": 2017,
    //     "month": 5,
    //     "day": 10,
    //     "cancelled": true
    // },
    // {
    //     "feelingtoday": " Repeated Test Event ",
    //     "experienced": 120,
    //     "year": 2017,
    //     "month": 5,
    //     "day": 10
    // },
    //     {
    //     "feelingtoday": " Repeated Test Event ",
    //     "experienced": 120,
    //     "year": 2017,
    //     "month": 5,
    //     "day": 10,
    //     "cancelled": true
    // },
    // {
    //     "feelingtoday": " Repeated Test Event ",
    //     "experienced": 120,
    //     "year": 2017,
    //     "month": 5,
    //     "day": 10
    // },
    //     {
    //     "feelingtoday": " Repeated Test Event ",
    //     "experienced": 120,
    //     "year": 2017,
    //     "month": 5,
    //     "day": 10,
    //     "cancelled": true
    // },
    // {
    //     "feelingtoday": " Repeated Test Event ",
    //     "experienced": 120,
    //     "year": 2017,
    //     "month": 5,
    //     "day": 10
    // },
    //     {
    //     "feelingtoday": " Repeated Test Event ",
    //     "experienced": 120,
    //     "year": 2017,
    //     "month": 5,
    //     "day": 10,
    //     "cancelled": true
    // },
    // {
    //     "feelingtoday": " Repeated Test Event ",
    //     "experienced": 120,
    //     "year": 2017,
    //     "month": 5,
    //     "day": 10
    // },
    // {
    //     "feelingtoday": " Test Event",
    //     "experienced": 120,
    //     "year": 2017,
    //     "month": 5,
    //     "day": 11
    // }
    // ]
};

const months = [ 
    "January", 
    "February", 
    "March", 
    "April", 
    "May", 
    "June", 
    "July", 
    "August", 
    "September", 
    "October", 
    "November", 
    "December" 
];
</script>
<script>
    $(document).ready(function(){
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
      $('.btn-deleteappointment').on('click', function(){
          var id = $(this).attr('data-id');
          Swal.fire({
          title: 'Are you sure you want to delete pending appointment?',
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Delete'
          })
          .then((result) => {
              if (result.value) {
                  $.ajax({
                      url: '/clinic/patientdashboard/deleteappointment',
                      type: 'GET',
                      dataType: 'json',
                      data:{
                          id   : id
                      }, success:function(data){
                          if(data == 1)
                          {
                              Toast.fire({
                                  type: 'success',
                                  title: 'Deleted successfully!'
                              })
                              $('[data-id="timeline'+id+'"]').remove();
                          }else{
                              Toast.fire({
                                  type: 'error',
                                  title: 'Something went wrong!'
                              })
                          }
                          window.location.reload()
                      }
                  })
              }
          })
      })
      $('.btn-editappointment').on('click', function(){
                $('#modal-editappointment').modal('show');
                var id = $(this).attr('data-id');
                $('#btn-submit-editappointment').attr('data-id',id);
                
            })

                $('#btn-submit-editappointment').on('click', function(){
                var id = $(this).attr('data-id');
                var description  = $('#description').val();
                var timeslot =$('#timeslot').val();
                console.log(id); 
                console.log(description);
                console.log(timeslot);
                
                var checkvalidation = 0;

                if(description.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    checkvalidation=1;
                    $('#description').css('border','1px solid red')
                }else{
                    $('#description').removeAttr('style')
                }
                if(timeslot.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    checkvalidation=1;
                    $('#timeslot').css('border','1px solid red')
                }else{
                    $('#timeslot').removeAttr('style')
                }


                if(checkvalidation == 0)
                {
                    $.ajax({
                        url: '/clinic/patientdashboard/editappointments',
                        type: 'GET',
                        dataType: 'json',
                        data:{
                            id          : id,
                            description : description,
                            timeslot    : timeslot,
                        }, success:function(data){
                            if(data == 1)
                            {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Updated successfully!'
                                })
                                $('#btn-close-editmedication').click()
                                $('.form-container input,textarea').val("")
                                window.location.reload();

                            }else{
                                Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong!'
                                })
                            }
                            window.location.reload();
                        }
                    })
                }
                
            })
      })

</script>
@endsection
