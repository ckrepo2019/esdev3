
<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-interaction/main.min.css')}}">

@extends('clinic_nurse.layouts.app')

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

      .todo-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    font-weight: bold;
    border-bottom: 1px solid #dee2e6;
  }
  
  .todo-list-item {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding: 10px;
    border-bottom: 1px solid #dee2e6;
    font-size: 13px;
  }
  
  .todo-list-item-name {
    flex: 1;
  }
  
  .todo-list-item-desc {
    flex: 1;
  }
  
  .todo-list-item-prescribed {
    flex: 1;
  }
  
  .todo-list-item-time {
    flex: 1;
    font-size: 14px;
    color: #6c757d;
    text-align: right;
  }

  .shadow {
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
    }

</style>
@section('content')
@php
    use \Carbon\Carbon;
    $now = Carbon::now();
    $name_showlast = "";
    $name_showfirst = "";

        $complaints = DB::table('clinic_complaints')
            ->select('clinic_complaints.*','users.type')
            ->leftJoin('users','clinic_complaints.userid','=','users.id')
            ->where('clinic_complaints.deleted','0')
            ->where('clinic_complaints.cdate', date('Y-m-d'))
            ->get();
            
        if(count($complaints)>0)
        {
            foreach($complaints as $complaint)
            {

                $has = DB::table('clinic_prescription')
              ->select('Approve', 'id')
              ->where('deleted','0')
              ->where('complaintid', $complaint->id)
              ->get();

              $has1 = "";

              if(count($has) > 0){
                $has1 = 'Yes';
                $complaint->prescription = $has1;
                $complaint->prescription2 = $has[0]->Approve;
              }else{
                $has1 = 'No';
                $complaint->prescription = $has1;
                $complaint->prescription2 = "N/A";
              }

                if($complaint->type == 7)
                {

                    $sid = DB::table('users')
                        ->where('id', $complaint->userid)
                        ->value('email');


                    $studid = DB::table('studinfo')->where('sid', str_replace('S', '', $sid))->value('id');


                    $info = DB::table('studinfo')
                        ->where('id', $studid)
                        ->first();


                    $info->title = "";
                    $info->utype = 'STUDENT';
                }else{
                    $info = Db::table('teacher')
                        ->select('teacher.*','usertype.utype','employee_personalinfo.gender')
                        ->join('usertype','teacher.usertypeid','=','usertype.id')
                        ->leftJoin('employee_personalinfo','teacher.usertypeid','=','usertype.id')
                        ->where('userid', $complaint->userid)
                        ->where('teacher.deleted','0')
                        ->first();
                }


                if(isset($info)){
                    
                    $complaint->picurl = $info->picurl;
                    $complaint->gender = $info->gender;
                    $complaint->utype = $info->utype;
                
                    $name_showfirst = "";
                    $name_showlast = "";
                
                    if($info->title != null)
                    {
                        $name_showfirst.=$info->title.' ';
                    }
                    $name_showfirst.=$info->firstname.' ';

                    if($info->middlename != null)
                    {
                        $name_showfirst.=$info->middlename[0].'. ';
                    }
                    $name_showfirst.=$info->lastname.' ';
                    $name_showfirst.=$info->suffix.' ';

                    $complaint->name_showfirst = $name_showfirst;

                    $name_showlast = "";

                    if($info->title != null)
                    {
                        $name_showlast.=$info->title.' ';
                    }
                    $name_showlast.=$info->lastname.', ';
                    $name_showlast.=$info->firstname.' ';

                    if($info->middlename != null)
                    {
                        $name_showlast.=$info->middlename[0].'. ';
                    }
                    $name_showlast.=$info->suffix.' ';

                    $complaint->name_showlast = $name_showlast;
                } else{
                    
                }
            }
        }

    $appointments = DB::table('clinic_appointments')
            ->select('clinic_appointments.*','users.type','usertype.utype')
            ->join('users','clinic_appointments.userid','=','users.id')
            ->join('usertype','users.type','=','usertype.id')
            ->where('clinic_appointments.adate', date('Y-m-d'))
            ->where('clinic_appointments.admitted','1')
            ->where('clinic_appointments.admittedby',auth()->user()->id)
            ->get();

      if(count($appointments)>0)
      {
          foreach($appointments as $appointment)
          {
              if($appointment->type == 7)
              {


                  $sid = DB::table('users')
                        ->where('id', $appointment->userid)
                        ->value('email');


                    $studid = DB::table('studinfo')->where('sid', str_replace('S', '', $sid))->value('id');


                    $info = DB::table('studinfo')
                        ->where('id', $studid)
                        ->first();

                  $info->title = null;
              }else{
                  $info = DB::table('teacher')
                      ->where('userid', $appointment->userid)
                      ->first();
              }
              $name_showfirst = "";

              if($info->title != null)
              {
                  $name_showfirst.=$info->title.' ';
              }
              $name_showfirst.=$info->firstname.' ';

              if($info->middlename != null)
              {
                  $name_showfirst.=$info->middlename[0].'. ';
              }
              $name_showfirst.=$info->lastname.' ';
              $name_showfirst.=$info->suffix.' ';

              $appointment->name_showfirst = $name_showfirst;

              $name_showlast = "";

              if($info->title != null)
              {
                  $name_showlast.=$info->title.' ';
              }
              $name_showlast.=$info->lastname.', ';
              $name_showlast.=$info->firstname.' ';

              if($info->middlename != null)
              {
                  $name_showlast.=$info->middlename[0].'. ';
              }
              $name_showlast.=$info->suffix.' ';

              $appointment->name_showlast = $name_showlast;
              $appointedname = '';
          }
      }


        

@endphp

<div class="row">
  <div class="col-md-4 ">
      <div class="info-box mb-3 bg-warning" >
        <span class="info-box-icon">
            <i class="fa fa-laptop-medical"></i>
          </span>

        <div class="info-box-content">
          <span class="info-box-text">Medical</span>
          <span class="info-box-number">History</span>
        </div>
        <!-- /.info-box-content -->
      </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="card shadow">
        <div class="card-header bg-primary" >
            <h3 class="card-title">
            <i class="ion ion-clipboard mr-1" ></i>
                <strong >Complaints for today</strong>
            </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @if(count($complaints)>0)
              <ul data-widget="todo-list">
                <li class="todo-list-header">
                  <div class="todo-list-item-name">Name</div>
                  <div class="todo-list-item-desc">Complaint</div>
                  <div class="todo-list-item-prescribed">Prescribed</div>
                  <div class="todo-list-item-time">Timestamp</div>
                </li>
                @foreach($complaints as $complaint)
                
                      <li class="todo-list-item">
                      <div class="todo-list-item-name">{{$complaint->name_showlast}}</div>
                      <div class="todo-list-item-desc">{{$complaint->description}}</div>
                      @if($complaint->prescription == 'Yes')
                      @if($complaint->prescription2 == 0)
                      <div class="todo-list-item-prescribed" style="margin-left: 23px"><span class="badge badge-primary" id = "viewPrescription" data-id="{{$complaint->id}}">Yes</span></div>
                      @else
                      <div class="todo-list-item-prescribed" style="margin-left: 23px"><span class="badge badge-secondary">Yes</span></div>
                      @endif
                      @else
                      <div class="todo-list-item-prescribed">No</div>
                      @endif
                      <div class="todo-list-item-time">

                      @php
                      date_default_timezone_set('Asia/Manila');
                      $now = Carbon::now();
                      $created_at = Carbon::parse($complaint->ctime);
                      $diffHours = $created_at->diffInHours($now);  // 3 
                      $diffMinutes = $created_at->diffInMinutes($now)   // 180
                      @endphp
                      @if($diffMinutes<60)
                        {{$diffMinutes}}m ago
                      @else
                        {{$diffHours}}h ago
                      @endif
                      </div>
                  </li>
                @endforeach
            @else
            <div class="row">
              <div class="col-md-12 text-center">No Complaints for today</div>
            </div>
            @endif
            </ul>
        </div>
        <!-- /.card-body -->
        {{-- <div class="card-footer clearfix">
            <button type="button" class="btn btn-info float-right"><i class="fas fa-plus"></i> Add item</button>
        </div> --}}
    </div>
  </div>
  <div class="col-md-6">
      <div class="card shadow">
          <div class="card-header bg-success" >
              <h3 class="card-title">
              <i class="ion ion-clipboard mr-1"></i>
                  <strong>Appointments admitted for today</strong>
              </h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            @if(count($appointments)>0)
              <ul  data-widget="todo-list">
                <li class="todo-list-header">
                    <div class="todo-list-item-name">Name</div>
                    <div class="todo-list-item-desc">Description</div>
                    <div class="todo-list-item-prescribed">Physician</div>
                    <div class="todo-list-item-time">Date</div>
                  </li>
                @foreach($appointments as $appointment)
                  <li class="todo-list-item">
                    <div class="todo-list-item-name">{{$appointment->name_showlast}}</div>
                    <div class="todo-list-item-desc">{{$appointment->description}}</div>
                    @php
                    $doctorname = DB::table('teacher')
                            ->select('lastname','firstname','middlename')
                            ->where('id', $appointment->docid)
                            ->first();
                    @endphp
                    <div class="todo-list-item-prescribed">Dr. {{$doctorname->lastname}},{{$doctorname->firstname}}</div>
                    @if($appointment->atime == null)
                            @else
                            <div class="todo-list-item-time"><i class="far fa-clock"></i> {{date('M d, Y h:i A', strtotime($appointment->atime))}}</div>
                            @endif
                    </div>
                  </li>
                @endforeach
              </ul>
            @else
            <div class="row">
              <div class="col-md-12 text-center">No Appointments for today</div>
            </div>
            @endif
          </div>
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
    $(document).ready(function(){
      $(document).on('click','#viewPrescription', function(){
        var complaintid = $(this).attr('data-id');
        window.open('/pdf/download?complainid='+complaintid,'_blank')
        console.log("Hello!");
      })

        $('#btn-createapp').on('click', function(){
            window.open("/clinic/appointment/index");
        })
    })
</script>
@endsection
