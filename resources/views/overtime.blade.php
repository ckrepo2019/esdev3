
@extends(''.$extends.'')
@section('content')
<!-- Ekko Lightbox -->
<link rel="stylesheet" href="{{asset('plugins/ekko-lightbox/ekko-lightbox.css')}}">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
<style>
.gallery{margin: 10px 0;display: block;}
.imgdiv
{
    position: relative;
    width: 100px;
    height: 115px;
}
.imgdiv img{width: 100%;height: 115px;}
div.ekko-lightbox-container{height: 475px !important; }
.ekko-lightbox.modal.fade.in div.modal-dialog{
  max-width:40% !important;
  /* height: 475px */
;}
.img-fluid{
    height: 100% !important;
}
</style>
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h3 class="page-title">Overtime</h3>
            <ul class="breadcrumb col-md-12">
                <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                <li class="breadcrumb-item active">Overtime</li>
            </ul>
            {{-- <div class="col-md-2 float-right ml-auto">
                <a href="#" class="btn btn-block" data-toggle="modal" data-target="#add_leave"><i class="fa fa-plus"></i> Add Overtime</a>
            </div> --}}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3">
        <div class="stats-info" style="border: 2px solid #9DD6FB; background: rgb(255,255,255);background: linear-gradient(0deg, rgba(255,255,255,1) 30%, rgba(157,214,251,1) 100%);">
            <h6>Overtime Employee</h6>
            <h4>{{$countemployeesovertimethismonth}} <span>this month</span></h4>
        </div>
    </div>
    <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3">
        <div class="stats-info" style="border: 2px solid #9DD6FB; background: rgb(255,255,255);background: linear-gradient(0deg, rgba(255,255,255,1) 30%, rgba(157,214,251,1) 100%);">
            <h6>Overtime Hours</h6>
            <h4>{{$overtimehours}} <span>this month</span></h4>
        </div>
    </div>
    <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3">
        <div class="stats-info" style="border: 2px solid #9DD6FB; background: rgb(255,255,255);background: linear-gradient(0deg, rgba(255,255,255,1) 30%, rgba(157,214,251,1) 100%);">
            <h6>Pending Request</h6>
            <h4>{{$countpendingovertimethismonth}}</h4>
        </div>
    </div>
    <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3">
        <div class="stats-info" style="border: 2px solid #9DD6FB; background: rgb(255,255,255);background: linear-gradient(0deg, rgba(255,255,255,1) 30%, rgba(157,214,251,1) 100%);">
            <h6>Rejected</h6>
            <h4>{{$countrejectedovertimethismonth}}</h4>
        </div>
    </div>
</div>
@if(session()->has('updated'))
    <div class="alert alert-success alert-dismissible col-12">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('updated') }}
    </div>
@endif
@if(session()->has('messageDispproved'))
    <div class="alert alert-success alert-dismissible col-12">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('messageDispproved') }}
    </div>
@endif
@if(isset($message))
    <div class="alert alert-light alert-dismissible col-12">
        {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
        <h5><i class="icon fas fa-ban"></i> Alert!</h5>
        {{ $message }}<br>
        Posibble reason/s:
        <ul>
            <li>
                @if(isset($payrolldateexists))
                    @if($payrolldateexists == 0)
                        Payroll date not set
                        <br>
                        <a href="/employeesalary/dashboard">Click to set payroll date</a>
                    @else
                        <strike>Payroll date not set</strike>
                    @endif
                @endif
            </li>
            <li>
                @if(isset($rateexists))
                    @if($rateexists == 0)
                        Payroll Items: Designation rates not set
                        <br>
                        <a href="/payrollitems/dashboard">Click to set payroll items</a>
                    @endif
                @endif
            </li>
        </ul>
    </div>
@endif
<div class="card">
    <div class="card-body">
        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow: scroll">
            <div class="row">
                <div class="col-sm-12"  style="overflow:scroll;">
                    <table id="example1" style="font-size: 12px" class="table table-bordered table-striped dataTable text-uppercase" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr>
                                {{-- <th>#</th> --}}
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Number of Hours</th>
                                <th>Remarks</th>
                                <th>Attachments</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($overtimes))
                                @foreach ($overtimes as $overtime)
                                    <tr>
                                        <td>{{$overtime->overtimedetail->lastname}}, {{$overtime->overtimedetail->firstname}}</td>
                                        <td>{{$overtime->overtimedetail->daterequest}}</td>
                                        <td>{{$overtime->overtimedetail->numofhours}}</td>
                                        <td>{{$overtime->overtimedetail->remarks}}</td>
                                        <td>
                                            @if(count($overtime->attachments) == 0)
                                            <center>--------------</center>
                                            @else
                                                @foreach($overtime->attachments as $attachments)
                                                    <a  href="{{asset($attachments->picurl)}}" data-toggle="lightbox" data-title="Attachments" data-gallery="gallery" style="display: inline;width: 25% !important;">
                                                        <img src="{{asset($attachments->picurl)}}" class="mb-2 " alt="attatchment" style="width: 30% !important;" style="display: inline;"/>
                                                    </a>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if($overtime->overtimedetail->status == 'pending')
                                                @if($overtime->overtimedetail->self == '1')
                                                <button class="btn btn-sm btn-block btn-warning" disabled><strong>Pending</strong></button>
                                                @else
                                                <button class="btn btn-sm btn-block btn-warning" data-toggle="modal" data-target="#pending{{$overtime->overtimedetail->id}}"><strong>Pending</strong></button>
                                                @endif
                                                <div class="modal fade" id="pending{{$overtime->overtimedetail->id}}" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm">
                                                        <form action="/overtime/changestatus" method="get" id="{{$overtime->overtimedetail->id}}" name="changestatus">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">{{$overtime->overtimedetail->lastname}}, {{$overtime->overtimedetail->firstname}}</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input id="{{$overtime->overtimedetail->id}}" type="hidden" value="{{$overtime->overtimedetail->id}}" name="overtimeid"/>
                                                                    {{-- <h4><strong>{{$leave->leave_type}} Leave</strong></h4> --}}
                                                                    <br>
                                                                    Date: {{$overtime->overtimedetail->daterequest}}
                                                                    <br>
                                                                    <br>
                                                                    Number of Hours: {{$overtime->overtimedetail->numofhours}}
                                                                    <br>
                                                                    <br>
                                                                    <strong>Reason</strong>
                                                                    <textarea id="compose-textarea{{$overtime->overtimedetail->id}}" class="form-control" name="reason" style="height: 100px" readonly>
                                                                        {{$overtime->overtimedetail->remarks}}
                                                                    </textarea>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <input id="{{$overtime->overtimedetail->id}}" type="hidden" value="" name="status"/>
                                                                    <button type="button" class="btn btn-danger disapproved" id="disapproved{{$overtime->overtimedetail->id}}">Reject</button>
                                                                    <button type="button" class="btn btn-primary approved" id="approved{{$overtime->overtimedetail->id}}">Approve</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @elseif($overtime->overtimedetail->status == 'disapproved')
                                                @if($overtime->overtimedetail->self == '1')
                                                    <button class="btn btn-sm btn-block btn-danger" disabled>Disapproved</button>
                                                @else
                                                    <button class="btn btn-sm btn-block btn-danger">Disapproved</button>
                                                @endif
                                            @elseif($overtime->overtimedetail->status == 'approved')
                                                @if($overtime->overtimedetail->self == '1')
                                                    <button class="btn btn-sm btn-block btn-success" disabled>Approved</button>
                                                @else
                                                    <button class="btn btn-sm btn-block btn-success">Approved</button>
                                                @endif
                                            @endif
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
</div>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<!-- Ekko Lightbox -->
<script src="{{asset('plugins/ekko-lightbox/ekko-lightbox.min.js')}}"></script>
<script>
    $(function () {
        $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
        });
    })
    $(document).ready(function(){
    $('body').addClass('sidebar-collapse')
        window.setTimeout(function () {
            $(".alert-success").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
        window.setTimeout(function () {
            $(".alert-danger").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
        $(document).on('click', '.disapproved', function(){
            //    console.log($(this).closest('form[name=changestatus]'));
            $('input[name=status]').val($(this).text())
            $(this).closest('form[name=changestatus]').submit();

        })
        $(document).on('click', '.approved', function(){
            //    console.log($(this).closest('form[name=changestatus]'));
            $('input[name=status]').val($(this).text())
            $(this).closest('form[name=changestatus]').submit();

        })
   })
  $(function () {
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox({
        alwaysShowClose: true
      });
    });

    $('.filter-container').filterizr({gutterPixels: 3});
    $('.btn[data-filter]').on('click', function() {
      $('.btn[data-filter]').removeClass('active');
      $(this).addClass('active');
    });
  })
  </script>
@endsection

