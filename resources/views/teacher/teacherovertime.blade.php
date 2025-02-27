

@extends('teacher.layouts.app')
@section('content')
<style>
    div[role=wrapper]{
        float: right !important;
        width: 70% !important;
    }
</style>
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
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
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
        <div class="stats-info">
            <h6>Overtime Employee</h6>
            <h4><span>this month</span></h4>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
        <div class="stats-info">
            <h6>Overtime Hours</h6>
            <h4> <span>this month</span></h4>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
        <div class="stats-info">
            <h6>Pending Request</h6>
            <h4></h4>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
        <div class="stats-info">
            <h6>Rejected</h6>
            <h4></h4>
        </div>
    </div>
</div>

@if(isset($message))
    <div class="alert alert-warning alert-dismissible col-12">
        {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
        <h5><i class="icon fas fa-ban"></i> Alert!</h5>
        {{ $message}}
    </div>
@endif
@if(isset($overtimes))
<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#requestovertime">Request</button>
<div class="modal fade" id="requestovertime" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <form action="/teacherovertime/request" method="get">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Request Overtime</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <strong>Reason</strong>
                    <textarea name="reason" class="form-control" required></textarea>
                    <br>
                    <strong>Date</strong><input type="text" class="currentDate" id="currentDate" name="overtimeon" width="176" />
                    <br>
                    <br>
                    <span><strong>Time Range</strong></span>
                    <br>
                    <input type="text" class="form-control" class="reservation" id="reservation" name="overtimerange" required>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btncancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endif
<br>&nbsp;
@if(session()->has('requested'))
    <div class="alert alert-success alert-dismissible col-12">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('requested') }}
    </div>
@endif
@if(session()->has('messageDispproved'))
    <div class="alert alert-success alert-dismissible col-12">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('messageDispproved') }}
    </div>
@endif
@if(isset($overtimes))
<div class="card">
    <div class="card-body">
        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow: scroll">
            <div class="row">
                <div class="col-sm-12">
                    <table id="example1" style="table-layout: fixed;font-size: 12px" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr>
                                {{-- <th>#</th> --}}
                                <th>OT Date</th>
                                <th>Time Range</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($overtimes)>0)
                                @foreach ($overtimes as $overtime)
                                    <tr>
                                        <td>{{$overtime->date_request}}</td>
                                        <td>{{$overtime->time_from}} - {{$overtime->time_to}}</td>
                                        <td>{{$overtime->reason}}</td>
                                        <td>
                                            @if($overtime->status == 'pending')
                                                <button class="btn btn-block btn-warning btn-sm">Pending</button>
                                            @elseif($overtime->status == 'approved')
                                                <button class="btn btn-block btn-success btn-sm">Approved</button>
                                            @elseif($overtime->status == 'disapproved')
                                                <button class="btn btn-block btn-secondary btn-sm">Disapproved</button>
                                            @endif
                                        </td>
                                        {{-- <td></td> --}}
                                        {{-- <td></td> --}}
                                        {{-- <td class="p-0">
                                            <textarea id="compose-textarea{{$leave->id}}" class="form-control compose-textarea" name="content" style="height: 300px">{{$leave->reason}}</textarea>
                                        </td> --}}
                                        <td>
                                            @if($overtime->status == 'pending')
                                            <button class="btn btn-sm btn-warning float-left editbtn" date_request="{{$overtime->date_req}}" overtimeid="{{$overtime->id}}" time_from="{{$overtime->time_from}}" time_to="{{$overtime->time_to}}" reason="{{$overtime->reason}}"><strong><i class="fa fa-edit"></i>Edit</strong></button>
                                            <button class="btn btn-sm btn-danger float-right deletebtn" date_request="{{$overtime->date_req}}" overtimeid="{{$overtime->id}}" time_from="{{$overtime->time_from}}" time_to="{{$overtime->time_to}}" reason="{{$overtime->reason}}"><strong><i class="fa fa-edit"></i>Delete</strong></button>
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
@endif
<div class="modal fade" id="editrequest" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <form action="/teacherovertime/editrequest" method="get">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Request</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <strong>Reason</strong>
                    <textarea name="reason" class="form-control" id="editreason" required></textarea>
                    <br>
                    <strong>Date</strong><input type="text" id="editdate" name="overtimeon" width="176" required />
                    <br>
                    <br>
                    <span><strong>Time Range</strong></span>
                    <br>
                    <input type="text" class="form-control" id="edittimerange" name="overtimerange" required>
                    <input type="hidden" class="form-control" id="overtimeid" name="overtimeid" required>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btncancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="deleterequest" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <form action="/teacherovertime/deleterequest" method="get">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Request</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <strong>Reason</strong>
                    <textarea name="reason" class="form-control" id="deletereason" readonly></textarea>
                    <br>
                    <strong>Date</strong><input type="text" id="deletedate" class="form-control" name="overtimeon" width="176" readonly />
                    <br>
                    <br>
                    <span><strong>Time Range</strong></span>
                    <br>
                    <input type="text" class="form-control" id="deletetimerange" name="overtimerange" readonly>
                    <input type="hidden" class="form-control" id="deleteovertimeid" name="overtimeid" readonly>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btncancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
<!-- InputMask -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script>
    $(function () {
        $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
        });
        $('#reservation').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            timePickerIncrement: 1,
            timePickerSeconds: true,
            locale: {
                format: 'h:mm:ss A'
            }
        }).on('show.daterangepicker', function (ev, picker) {
            picker.container.find(".calendar-table").hide();
        });
        $('#edittimerange').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            timePickerIncrement: 1,
            timePickerSeconds: true,
            locale: {
                format: 'h:mm:ss A'
            }
        }).on('show.daterangepicker', function (ev, picker) {
            picker.container.find(".calendar-table").hide();
        });
    })
    $('#currentDate').datepicker({
        format: 'yyyy-mm-dd',
        minDate: '{{$currentdate}}'
    });
    $(document).ready(function(){
        
        $('#editdate').datepicker({
            format: 'yyyy-mm-dd',
            minDate: '{{$currentdate}}'
        });
    $('.editbtn').on('click', function(){
        var timerange = $(this).attr('time_from')+' - '+$(this).attr('time_to')
        $('#editreason').val($(this).attr('reason'));
        $('#edittimerange').val(timerange);
        $('#deletedate').val($(this).attr('date_request'));
        $('#overtimeid').val($(this).attr('overtimeid'));
        $('#editrequest').modal();
    })
    $('.deletebtn').on('click', function(){
        var timerange = $(this).attr('time_from')+' - '+$(this).attr('time_to')
        $('#deletereason').val($(this).attr('reason'));
        $('#deletetimerange').val(timerange);
        $('#deletedate').val($(this).attr('date_request'));
        $('#deleteovertimeid').val($(this).attr('overtimeid'));
        $('#deleterequest').modal();
    })
        $('[data-dismiss=modal]').on('click', function (e) {
            var $t = $(this),
                target = $t[0].href || $t.data("target") || $t.parents('.modal') || [];

            $(target)
                .find("input,textarea,select")
                .val('')
                .end()
                .find("input[type=checkbox], input[type=radio]")
                .prop("checked", "")
                .end();
        })
    })
  </script>
@endsection

