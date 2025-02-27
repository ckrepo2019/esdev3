@extends('teacher.layouts.app')
@section('content')

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
<style>

</style>
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h3 class="page-title">Overtime</h3>
            <ul class="breadcrumb col-md-12">
                <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                <li class="breadcrumb-item active">Leaves</li>
            </ul>
            {{-- <div class="col-md-2 float-right ml-auto">
                <a href="#" class="btn btn-block" data-toggle="modal" data-target="#add_leave"><i class="fa fa-plus"></i> Add Overtime</a>
            </div> --}}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="stats-info">
            <h6>Remaining Leaves</h6>
            <h4>{{$myleavesheader[0]->mynoofleavesremaining}}/{{$myleavesheader[0]->noofdaysleave }}</h4>
        </div>
    </div>
    {{-- <div class="col-md-4">
        <div class="stats-info">
            <h6>No. of days leave</h6>
            <h4>8 <span>Today</span></h4>
        </div>
    </div> --}}
    {{-- <div class="col-md-3">
        <div class="stats-info">
            <h6>Unplanned Leaves</h6>
            <h4>0 <span>Today</span></h4>
        </div>
    </div> --}}
    <div class="col-md-4">
        <div class="stats-info">
            <h6>Pending Requests</h6>
            <h4>{{$myleavesheader[0]->mynoofleavespending}}</h4>
        </div>
    </div>
</div>
@if(isset($leaves))
<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-lg">Apply Leave</button>
<br>
&nbsp;
<div class="modal fade" id="modal-lg" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <form action="/applyleave/applyleave" method="get">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Apply Leave</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <strong>Leave Type</strong>
                    <select class="form-control form-control-sm" name="applyleavetype">
                        <option value="" requests=""></option>
                        @foreach ($leaves as $leave)
                            <option requests="{{$leave->requests}}" value="{{$leave->id}}">{{$leave->requests}} {{$leave->description}}</option>                        
                        @endforeach
                    </select>
                    <br>
                    <strong>From-to</strong>
                    <input type="text" class="form-control" id="reservation" name="date" required>
                    <input type="hidden" id="requests" required>
                    <br>
                    <strong>Remarks</strong>
                    <textarea id="compose-textarea" class="form-control" name="content" style="height: 300px" required></textarea>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btncancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endif
@if(isset($message))
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Alert!</h5>
        {{$message}}
    </div>
@endif
@if(session()->has('messageAdd'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('messageAdd') }}
    </div>
@endif
@if(session()->has('messageExists'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Alert!</h5>
        {{ session()->get('messageExists') }}
    </div>
@endif
@if(isset($myleaves))
<div class="card">
            <div class="card-header">
                <h3 class="card-title">My Leaves</h3>
            </div>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow: scroll">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="example1" style="font-size: 13px;" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                <thead>
                                    <tr>
                                        {{-- <th>#</th> --}}
                                        <th style="width:15%">Date Submitted</th>
                                        <th>Leave Type</th>
                                        <th style="width:12%">From</th>
                                        <th style="width:12%">To</th>
                                        <th style="width:20%">Remarks</th>
                                        <th style="width:10%">Status</th>
                                        <th style="width:10%">Actions</th>
                                        {{-- <th style="width:10%">Actions</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach ($myleaves as $leave)
                                            <tr>
                                                <td>{{$leave->date_submitted}}</td>
                                                <td>
                                                    {{$leave->leave_type}}
                                                </td>
                                                <td>{{$leave->date_from}}</td>
                                                <td>{{$leave->date_to}}</td>
                                                <td class="tdreason">
                                                    {{$leave->reason}}</td>
                                                <td>
                                                    @if($leave->status == 'pending')
                                                        <span class="right badge badge-warning col-12"><strong>Pending</strong></span>
                                                    @elseif($leave->status == 'disapproved')
                                                        <span class="right badge badge-danger col-12">Disapproved</span>
                                                    @elseif($leave->status == 'approved')
                                                        <span class="right badge badge-success col-12">Approved</span>
                                                    @endif
                                                </td>
                                                <td class="m-0">
                                                    @if($leave->status == 'pending')
                                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editrequest{{$leave->id}}"><i class="fa fa-edit"></i></button>
                                                        <div class="modal fade" id="editrequest{{$leave->id}}" style="display: none;" aria-hidden="true">
                                                            <div class="modal-dialog modal-md">
                                                                <form action="/applyleave/editleave" method="get">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">Edit Request</h4>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">×</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <strong>Leave Type</strong>
                                                                            <select class="form-control form-control-sm" name="leavetype">
                                                                                @foreach ($leaves as $leavesid)
                                                                                    <option value="{{$leavesid->id}}" {{$leavesid->id == $leave->leaveid ? 'selected' : ''}}>{{$leavesid->description}}</option>                   
                                                                                @endforeach
                                                                            </select>
                                                                            <br>
                                                                            <strong>From-to</strong>
                                                                            <input type="text" class="form-control editrange" id="editrange" name="date" value="{{$leave->date_from_int}} - {{$leave->date_to_int}}"required>
                                                                            <br>
                                                                            <strong>Remarks</strong>
                                                                            <textarea class="form-control editreason" name="content" style="height: 300px" required>{{$leave->reason}}</textarea>
                                                                            <input type="hidden" name="requestid" value="{{$leave->id}}"/>
                                                                        </div>
                                                                        <div class="modal-footer justify-content-between">
                                                                            <button type="button" class="btn btn-default btncancel" data-dismiss="modal">Cancel</button>
                                                                            <button type="submit" class="btn btn-warning">Save Changes</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                              <!-- /.modal-content -->
                                                            </div>
                                                        </div>
                                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleterequest{{$leave->id}}"><i class="fa fa-trash"></i></button>
                                                        <div class="modal fade" id="deleterequest{{$leave->id}}" style="display: none;" aria-hidden="true">
                                                            <div class="modal-dialog modal-md">
                                                                <form action="/applyleave/deleteleave" method="get">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">Delete Request</h4>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">×</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body deleterequest">
                                                                            <strong>Leave Type</strong>
                                                                            <select class="form-control form-control-sm" name="leavetype" disabled>
                                                                                @foreach ($leaves as $leavesid)
                                                                                    <option value="{{$leavesid->id}}" {{$leavesid->id == $leave->leaveid ? 'selected' : ''}}>{{$leavesid->description}}</option>                   
                                                                                @endforeach
                                                                            </select>
                                                                            <br>
                                                                            <strong>From-to</strong>
                                                                            <input type="text" class="form-control editrange" id="editrange" name="date" value="{{$leave->date_from_int}} - {{$leave->date_to_int}}" disabled>
                                                                            <br>
                                                                            <strong>Remarks</strong>
                                                                            
                                                                                {!! html_entity_decode($leave->reason) !!}
                                                                            <input type="hidden" name="requestid" value="{{$leave->id}}"/>
                                                                        </div>
                                                                        <div class="modal-footer justify-content-between">
                                                                            <button type="button" class="btn btn-default btncancel" data-dismiss="modal">Cancel</button>
                                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                              <!-- /.modal-content -->
                                                            </div>
                                                        </div>
                                                    @elseif($leave->status == 'rejected')
                                                    @elseif($leave->status == 'approved')
                                                    @endif
                                                </td>
                                                {{-- <td></td> --}}
                                            </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
<script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script>
    $(document).on('click','.btncancel', function(){
        window.location.reload();
    })
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
   $(document).ready(function(){
        $('select[name=applyleavetype] option').each(function(){
                // console.log($(this).attr('requests'))
            if($(this).attr('requests') == '(0)'){
                $(this).attr('disabled','disabled')
            }
        });
        $('.tdreason div.note-editable').attr('contenteditable','false');
        $('.tdreason div.note-editable').css('backgroundColor','white');
        $('.tdreason div.note-editable').css('backgroundColor','white');
        $('.tdreason div.note-editor').removeClass('card');
        $('.deleterequest div.note-editable').attr('contenteditable','false');
        $('.deleterequest div.note-editable').css('backgroundColor','white');
        $('.deleterequest div.note-editable').css('backgroundColor','white');
        $('.deleterequest div.note-editor').removeClass('card');
        $('select[name=applyleavetype]').on('change',function(){
            // console.log();
            $('#requests').val($(this)[0].innerText.match(/\d+/)[0])
    $(function () {

        console.log($('#requests').val());
        $('#reservation').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            dateLimit: { days:  $('#requests').val() - 1},
            minDate: '{{$currentdate}}'
        })
    })
        })
    $(function () {
        $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
        });
        $('.editrange').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            minDate: '{{$currentdate}}'
        })
    })
    $(function () {
        $('.editreason').summernote({
            height: 300,
            toolbar: [
                [ 'style', [ 'style' ] ],
                [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
                [ 'fontname', [ 'fontname' ] ],
                [ 'fontsize', [ 'fontsize' ] ],
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
                [ 'table', [ 'table' ] ],
                [ 'insert', [ 'link'] ],
                [ 'view', [ 'undo', 'redo', 'fullscreen', 'help' ] ]
            ]
        });
        $('.compose-textarea-table').summernote({
            
            toolbar: []
        });
        $('#compose-textarea').summernote({
            height: 300,
            toolbar: [
                [ 'style', [ 'style' ] ],
                [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
                [ 'fontname', [ 'fontname' ] ],
                [ 'fontsize', [ 'fontsize' ] ],
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
                [ 'table', [ 'table' ] ],
                [ 'insert', [ 'link'] ],
                [ 'view', [ 'undo', 'redo', 'fullscreen', 'help' ] ]
            ]
        })
    })
   })
  </script>
@endsection