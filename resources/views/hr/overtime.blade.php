
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
    border-radius: inherit !important
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

{{-- <div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning alert-dismissible">
            <h5><i class="icon fas fa-exclamation"></i> Alert!</h5>
            This page is under maintenance.
        </div>
    </div>
  </div> --}}
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
<div class="card">
    <div class="card-body">
        {{-- <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow: scroll"> --}}
            <div class="row">
                <div class="col-sm-12"  style="overflow:scroll;">
                    <table id="overtimetable" style="font-size: 12px" class="table table-bordered table-striped dataTable text-uppercase" role="grid" aria-describedby="example1_info">
                        <thead class="bg-warning">
                            <tr>
                                <th>Details</th>
                                {{-- <th>Date</th>
                                <th>Number of Hours</th>
                                <th>Remarks</th>
                                <th>Attachments</th> --}}
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @if(count($overtimes)>0)
                            @foreach ($overtimes as $overtime)
                                <tr>
                                    {{-- <td>{{$overtime->lastname}}, {{$overtime->firstname}}</td> --}}
                                        <td>
                                            <div class="row">
                                                <div class="col-4">
                                                    <p>Employee</p>
                                                </div>
                                                <div class="col-8">
                                                    : {{$overtime->lastname}}, {{$overtime->firstname}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <p>Date submitted</p>
                                                </div>
                                                <div class="col-8">
                                                    : {{$overtime->createddatetime}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <p>From</p>
                                                </div>
                                                <div class="col-8">
                                                    : {{$overtime->datefrom}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <p>To</p>
                                                </div>
                                                <div class="col-8">
                                                    : {{$overtime->dateto}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <p>No. of Hours</p>
                                                </div>
                                                <div class="col-8">
                                                    : {{$overtime->numofhours}} hr(s).
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <p>Reason</p>
                                                </div>
                                                <div class="col-8">
                                                    : {{$overtime->reason}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <p>Attachments</p>
                                                </div>
                                                <div class="col-8">
                                                    :
                                                    @if(count($overtime->attachments) == 0)
                                                    <center>--------------</center>
                                                    @else
                                                        @foreach($overtime->attachments as $attachments)
                                                            <a  href="{{asset($attachments->picurl)}}" data-toggle="lightbox" data-title="Attachments" style="display: inline;width: 25% !important;border-radius: inherit !important">
                                                                <img src="{{asset($attachments->picurl)}}" class="mb-2 attachmentimg" alt="white sample" style="width: 10% !important;display: inline;border-radius: inherit !important"/>
                                                            </a>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    <td>
                                        @if($overtime->status == '2')
                                                @if(auth()->user()->type == 10)
                                                    <button type="button" class="btn btn-sm btn-warning overrideactivation btn-block" data-id="{{$overtime->id}}">
                                                        {{-- <i class="fa fa-reply"></i> --}}
                                                        Pending
                                                    </button>
                                                    @if($overtime->createdby == auth()->user()->id)
                                                        {{-- <button type="button" class="btn btn-sm btn-warning editleave" data-id="{{$overtime->id}}"data-toggle="modal" data-target="#edit{{$overtime->id}}"><i class="fa fa-edit"></i></button>
                                                        <div class="modal fade" id="edit{{$overtime->id}}" style="display: none;" aria-hidden="true">
                                                            <div class="modal-dialog modal-md">
                                                                <form action="/hr/leaves/editrequest" method="get">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">Edit Request</h4>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">×</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <strong>From-to</strong>
                                                                            <input type="text" class="form-control editrange" id="editrange" name="date" value="{{$overtime->datefrom}} - {{$overtime->dateto}}"required>
                                                                            <br>
                                                                            <strong>Reason</strong>
                                                                            <textarea class="form-control" name="content" style="height: 300px" required>{{$overtime->reason}}</textarea>
                                                                            <input type="hidden" name="requestid" value="{{$overtime->id}}"/>
                                                                        </div>
                                                                        <div class="modal-footer justify-content-between">
                                                                            <button type="button" class="btn btn-default btncancel" data-dismiss="modal">Cancel</button>
                                                                            <button type="submit" class="btn btn-warning">Save Changes</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div> --}}
                                                        {{-- <button type="button" class="btn btn-sm btn-danger deleteleave" data-id="{{$overtime->id}}" data-toggle="modal" data-target="#delete{{$overtime->id}}"><i class="fa fa-trash"></i></button>
                                                        <div class="modal fade" id="delete{{$overtime->id}}" style="display: none;" aria-hidden="true">
                                                            <div class="modal-dialog modal-md">
                                                                <form action="/hr/leaves/deleterequest" method="get">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">Delete Request</h4>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">×</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <br>
                                                                            <strong>From-to</strong>
                                                                            {{$overtime->datefrom}} - {{$overtime->dateto}}
                                                                            <br>
                                                                            <strong>Remarks</strong>
                                                                            
                                                                                {!! html_entity_decode($overtime->reason) !!}
                                                                            <input type="hidden" name="requestid" value="{{$overtime->id}}"/>
                                                                        </div>
                                                                        <div class="modal-footer justify-content-between">
                                                                            <button type="button" class="btn btn-default btncancel" data-dismiss="modal">Cancel</button>
                                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div> --}}
                                                    @endif
                                                    {{-- <br/>
                                                    <br/> --}}
                                                    
                                                    @if(isset($overtime->approvals))    
                                                        @if(count($overtime->approvals)>0)
                                                            @foreach ($overtime->approvals as $approvestatus)
                                                                @if($approvestatus->status == 1)
                                                                <span class="right badge badge-success" data-toggle="tooltip" data-placement="bottom" title="{{$approvestatus->name}}"><i class="fa fa-check"></i></span>
                                                                {{-- @elseif($approve->status == 2) --}}
                                                                @elseif($approvestatus->status == 3)
                                                                <span class="right badge badge-danger" data-toggle="tooltip" data-placement="bottom" title="{{$approvestatus->name}}"><i class="fa fa-times"></i></span>
                                                                @else
                                                                <span class="right badge badge-warning" data-toggle="tooltip" data-placement="bottom" title="{{$approvestatus->name}}"> P</span>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                @else
                                                    <button class="btn btn-sm btn-block btn-warning" data-toggle="modal" data-target="#pending{{$overtime->id}}"><strong>Pending</strong></button>
                                                    <div class="modal fade" id="pending{{$overtime->id}}" style="display: none;" aria-hidden="true">
                                                        <div class="modal-dialog modal-md">
                                                            
                                                            @if(auth()->user()->type == '1')
    
                                                            <form action="/teacher/overtimes/changestatus" method="get" id="{{$overtime->id}}" name="changestatus">
                                                                
                                                            @elseif(auth()->user()->type == '2')
    
                                                            <form action="/principal/overtime/changestatus" method="get" id="{{$overtime->id}}" name="changestatus">
    
                                                            @elseif(auth()->user()->type == '3' || auth()->user()->type == '8' )
    
                                                            <form action="/registrar/overtimes/changestatus" method="get" id="{{$overtime->id}}" name="changestatus">
    
                                                            @elseif(auth()->user()->type == '4' || auth()->user()->type == '15')
    
                                                            <form action="/teacher/overtimes/changestatus" method="get" id="{{$overtime->id}}" name="changestatus">
    
                                                            @elseif(auth()->user()->type == '6')
    
                                                            <form action="/teacher/overtimes/changestatus" method="get" id="{{$overtime->id}}" name="changestatus">
    
                                                            @elseif(auth()->user()->type == '10')
    
                                                            <form action="/hr/overtimes/changestatus" method="get" id="{{$overtime->id}}" name="changestatus">
    
                                                            @elseif(auth()->user()->type == '12')
    
                                                            <form action="/teacher/overtimes/changestatus" method="get" id="{{$overtime->id}}" name="changestatus">
    
                                                            @endif
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title text-uppercase">{{$overtime->lastname}}, {{$overtime->firstname}}</h4>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body leaveapprovalcontainer" style="text-align: none !important;">
                                                                        <input id="{{$overtime->id}}" type="hidden" value="{{$overtime->id}}" name="overtimeid"/>
                                                                        Hour(s): {{$overtime->numofhours}}
                                                                        <br>
                                                                        <br>
                                                                        From: {{$overtime->datefrom}}
                                                                        <br>
                                                                        <br>
                                                                        To: {{$overtime->dateto}}
                                                                        <br>
                                                                        <br>
                                                                        <strong>Remarks</strong>
                                                                        <textarea id="{{$overtime->id}}" class="form-control" name="content" rows="2" disabled>{{$overtime->reason}}</textarea>
                                                                        <br>
                                                                    </div>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <input id="{{$overtime->id}}" type="hidden" value="sad" name="status"/>
                                                                        <button type="button" class="btn btn-warning disapproved" id="disapproved{{$overtime->id}}">Disapprove</button>
                                                                        <button type="button" class="btn btn-primary approved" id="approved{{$overtime->id}}">Approve</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                            {{-- @endif     --}}
                                        @elseif($overtime->status == '3')
                                            <button class="btn btn-sm btn-block btn-danger">Disapproved</button>
                                            @if(auth()->user()->type == 10)
                                                <br/>
                                                @if(isset($overtime->approvals))    
                                                    @if(count($overtime->approvals)>0)
                                                        @foreach ($overtime->approvals as $approvestatus)
                                                            @if($approvestatus->status == 1)
                                                            <span class="right badge badge-success" data-toggle="tooltip" data-placement="top" title="{{$approvestatus->name}}"><i class="fa fa-check"></i></span>
                                                            {{-- @elseif($approve->status == 2) --}}
                                                            @elseif($approvestatus->status == 3)
                                                            <span class="right badge badge-danger" data-toggle="tooltip" data-placement="top" title="{{$approvestatus->name}}"><i class="fa fa-times"></i></span>
                                                            @else
                                                            <span class="right badge badge-warning" data-toggle="tooltip" data-placement="top" title="{{$approvestatus->name}}"> P</span>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endif
                                            @endif
                                        @elseif($overtime->status == '1')
                                            <button class="btn btn-sm btn-block btn-success">Approved</button>
                                            @if(auth()->user()->type == 10)
                                                <br/>
                                                @if(isset($overtime->approvals))    
                                                    @if(count($overtime->approvals)>0)
                                                        @foreach ($overtime->approvals as $approvestatus)
                                                            @if($approvestatus->status == 1)
                                                            <span class="right badge badge-success" data-toggle="tooltip" data-placement="top" title="{{$approvestatus->name}}"><i class="fa fa-check"></i></span>
                                                            {{-- @elseif($approve->status == 2) --}}
                                                            @elseif($approvestatus->status == 3)
                                                            <span class="right badge badge-danger" data-toggle="tooltip" data-placement="top" title="{{$approvestatus->name}}"><i class="fa fa-times"></i></span>
                                                            @else
                                                            <span class="right badge badge-warning" data-toggle="tooltip" data-placement="top" title="{{$approvestatus->name}}"> P</span>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endif
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
        {{-- </div> --}}
    </div>
</div>
<div class="modal fade" id="modal-forceactivation" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/hr/overtimeforcepermission" method="GET">
                @csrf
                <div class="modal-header">
                <h4 class="modal-title">Override Permission</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
                <div class="modal-body">
                    <p>
                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                            <input type="radio" id="radioPrimary1" name="status" checked="" value="1">
                            <label for="radioPrimary1">
                                Approve
                            </label>
                            </div>
                            <div class="icheck-primary d-inline ml-2">
                            <input type="radio" id="radioPrimary2" name="status" value="3">
                            <label for="radioPrimary2">
                                Disapprove
                            </label>
                            </div>
                        </div>
                        <input type="hidden" id="employeeovertimeid" name="employeeovertimeid">
                    </p>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
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
<!-- Ekko Lightbox -->
<script src="{{asset('plugins/ekko-lightbox/ekko-lightbox.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
        $("#overtimetable").DataTable({
            aLengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"]
            ],
            iDisplayLength: -1,
            // "order": [[ 1, 'asc' ]],
            paging: false
        });
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
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox({
            alwaysShowClose: true
        });
        });

        // $('.filter-container').filterizr({gutterPixels: 3});
        $('.btn[data-filter]').on('click', function() {
        $('.btn[data-filter]').removeClass('active');
        $(this).addClass('active');
        });
   })
    $(document).on('click','.overrideactivation', function(){
        $('#modal-forceactivation').modal('show')
        $('#employeeovertimeid').val($(this).attr('data-id'))
    })
  </script>
@endsection

