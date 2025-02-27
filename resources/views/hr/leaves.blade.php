

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@extends(''.$extends.'')
@section('content')
<style>
    
    @media screen and (max-width : 906px){
        .desk{
        visibility:hidden;
        }
        .div-only-mobile{
        visibility:visible;
        }
        .viewtime{
            width: 200px !important;
        }
    }

</style>
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Leaves</h1> -->
          <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
          <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
          LEAVES</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Leaves</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  {{-- <div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning alert-dismissible">
            <h5><i class="icon fas fa-exclamation"></i> Alert!</h5>
            This page is under maintenance.
        </div>
    </div>
  </div> --}}
{{-- <div class="row">
    <div class="col-md-3">
        <div class="stats-info">
            <h6>Today Presents</h6>
            <h4>12 / 60</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-info">
            <h6>Planned Leaves</h6>
            <h4>8 <span>Today</span></h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-info">
            <h6>Unplanned Leaves</h6>
            <h4>0 <span>Today</span></h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-info">
            <h6>Pending Requests</h6>
            <h4>12</h4>
        </div>
    </div>
</div> --}}
@if(session()->has('messageApproved'))
    <div class="alert alert-success alert-dismissible col-12">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('messageApproved') }}
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
    @if(auth()->user()->type == '10')
        <div class="card-header bg-info">
            <div class="float-left">
                <p class=""  >
                    <a href="#" class="btn btn-sm btn-light" data-toggle="modal" data-target="#addemployeeleave">
                        <i class="fa fa-plus text-muted"></i>
                    </a>
                    Apply leave 
                </p>
                <div id="addemployeeleave" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered modal-md" role="document" style="color: black;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" >Leave Application</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body" style="text-align: none !important">
                                <form action="/hr/globalapplyleave" method="get">
                                    @csrf
                                    <label>Employee/s:</label>
                                    <div class="">
                                        <select id="select2" class="form-control select2 m-0 text-uppercase" multiple="multiple" data-placeholder="Select employee/s:" name="leaveapplicants[]" required>
                                            <option></option>
                                            @foreach($employees as $employee)
                                                <option value="{{$employee->id}}">
                                                    {{strtoupper($employee->lastname)}}, {{strtoupper($employee->firstname)}} {{strtoupper($employee->suffix)}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <br>
                                        <strong>Leave Type</strong>
                                        <select class="form-control form-control-sm" name="leavetype" required>
                                            <option></option>
                                            @foreach ($leavetypes as $leavetype)
                                                <option value="{{$leavetype->id}}">{{$leavetype->leave_type}}</option>                        
                                            @endforeach
                                        </select>
                                        <br>
                                        <label>DATE</label>
                                        <br>
                                        <label>From - To</label>
                                        <input type="text" class="form-control" id="leavedaterange" name="leavedaterange" required>
                                        <br>
                                        <label>Remarks</label>
                                        <br>
                                        <textarea id="compose-textarea" class="form-control" name="leaveremarks" style="height: 100px" required></textarea>
                                        <br>
                                    </div>
                                    <div class="submit-section">
                                        <button type="submit" class="btn btn-primary submit-btn float-right">Apply</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="float-right"><p class=""  >Settings <a href="/leavesettings" class="btn btn-sm btn-light"><i class="fa fa-cogs text-muted"></i></a></p></div>
        </div>
    @endif
    
    {{-- {{collect($leaves)}} --}}
    <div class="card-body">
        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 " style="overflow: scroll">
                    <table id="example1" style="font-size: 12px" class="table table-bordered table-striped dataTable text-uppercase" role="grid" aria-describedby="example1_info">
                        <thead class="bg-warning">
                            <tr>
                                {{-- <th>#</th> --}}
                                <th>Employee</th>
                                <th style="width:13%">Leave Type</th>
                                <th style="width:10%">From</th>
                                <th style="width:10%">To</th>
                                <th style="width:10%">Days</th>
                                <th>Reason</th>
                                <th>Status</th>
                                {{-- <th>Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            
                            @if(count($leaves)>0)
                            @foreach ($leaves as $leave)
                                <tr>
                                    <td>{{$leave->lastname}}, {{$leave->firstname}}</td>
                                    <td>{{$leave->leave_type}}</td>
                                    <td>{{$leave->datefrom}}</td>
                                    <td>{{$leave->dateto}}</td>
                                    <td>
                                        {{$leave->numofdays}}
                                    </td>
                                    <td>{{$leave->reason}}</td>
                                    <td>
                                        @if($leave->status == '2')
                                            {{-- @if($leave->createdby == auth()->user()->id && $leave->employeeid == DB::table('teacher')->where('userid', auth()->user()->id)->first()->id)
                                                <button class="btn btn-sm btn-block btn-warning" data-toggle="modal" data-target="#pending{{$leave->id}}"><strong>Pending</strong></button>
                                                <div class="modal fade" id="pending{{$leave->id}}" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog modal-md">
                                                        
                                                        @if(auth()->user()->type == '1')

                                                        <form action="/teacher/leaves/changestatus" method="get" id="{{$leave->id}}" name="changestatus">
                                                            
                                                        @elseif(auth()->user()->type == '2')

                                                        <form action="/teacher/leaves/changestatus" method="get" id="{{$leave->id}}" name="changestatus">

                                                        @elseif(auth()->user()->type == '3' || auth()->user()->type == '8' )

                                                        <form action="/teacher/leaves/changestatus" method="get" id="{{$leave->id}}" name="changestatus">

                                                        @elseif(auth()->user()->type == '4' || auth()->user()->type == '15')

                                                        <form action="/teacher/leaves/changestatus" method="get" id="{{$leave->id}}" name="changestatus">

                                                        @elseif(auth()->user()->type == '6')

                                                        <form action="/teacher/leaves/changestatus" method="get" id="{{$leave->id}}" name="changestatus">

                                                        @elseif(auth()->user()->type == '10')

                                                        <form action="/hr/leaves/changestatus" method="get" id="{{$leave->id}}" name="changestatus">

                                                        @elseif(auth()->user()->type == '12')

                                                        <form action="/teacher/leaves/changestatus" method="get" id="{{$leave->id}}" name="changestatus">

                                                        @endif
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    @if($leave->usertypeid == '6')
                                                                    <h4 class="modal-title">{{$leave->name}}</h4>
                                                                    @else
                                                                    <h4 class="modal-title text-uppercase">{{$leave->lastname}}, {{$leave->firstname}}</h4>
                                                                    @endif
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body leaveapprovalcontainer" style="text-align: none !important;">
                                                                    <input id="{{$leave->id}}" type="hidden" value="{{$leave->id}}" name="leaveid"/>
                                                                    <h4><strong>{{$leave->leave_type}} Leave</strong></h4>
                                                                    <br>
                                                                    Days: {{$leave->numofdays}}
                                                                    <br>
                                                                    <br>
                                                                    From: {{$leave->datefrom}}
                                                                    <br>
                                                                    <br>
                                                                    To: {{$leave->dateto}}
                                                                    <br>
                                                                    <br>
                                                                    <strong>Remarks</strong>
                                                                    <textarea id="{{$leave->id}}" class="form-control" name="content" rows="2" disabled>{{$leave->reason}}</textarea>
                                                                    <br>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <input id="{{$leave->id}}" type="hidden" value="sad" name="status"/>
                                                                    <button type="button" class="btn btn-warning disapproved" id="disapproved{{$leave->id}}">Disapprove</button>
                                                                    <button type="button" class="btn btn-primary approves" id="approved{{$leave->id}}">Approve</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @else --}}
                                                @if(auth()->user()->type == 10)
                                                    <button type="button" class="btn btn-sm btn-success overrideactivation" data-id="{{$leave->id}}"><i class="fa fa-reply"></i></button>
                                                    @if($leave->createdby == auth()->user()->id)
                                                        <button type="button" class="btn btn-sm btn-warning editleave" data-id="{{$leave->id}}"data-toggle="modal" data-target="#edit{{$leave->id}}"><i class="fa fa-edit"></i></button>
                                                        <div class="modal fade" id="edit{{$leave->id}}" style="display: none;" aria-hidden="true">
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
                                                                            <input type="text" class="form-control editrange" id="editrange" name="date" value="{{$leave->datefrom}} - {{$leave->dateto}}"required>
                                                                            <br>
                                                                            <strong>Reason</strong>
                                                                            <textarea class="form-control" name="content" style="height: 300px" required>{{$leave->reason}}</textarea>
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
                                                        <button type="button" class="btn btn-sm btn-danger deleteleave" data-id="{{$leave->id}}" data-toggle="modal" data-target="#delete{{$leave->id}}"><i class="fa fa-trash"></i></button>
                                                        <div class="modal fade" id="delete{{$leave->id}}" style="display: none;" aria-hidden="true">
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
                                                                            <strong>Leave Type</strong>
                                                                            {{$leave->leave_type}}
                                                                            <br>
                                                                            <strong>From-to</strong>
                                                                            {{$leave->datefrom}} - {{$leave->dateto}}
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
                                                    @endif
                                                    <br/>
                                                    <br/>
                                                    
                                                    @if(isset($leave->approvals))    
                                                        @if(count($leave->approvals)>0)
                                                            @foreach ($leave->approvals as $approvestatus)
                                                                @if($approvestatus->status == 1)
                                                                <span class="right badge badge-success" data-toggle="tooltip" data-placement="bottom" title="{{$approvestatus->name}}"><i class="fa fa-check"></i></span>
                                                                {{-- @elseif($approve->status == 2) --}}
                                                                @elseif($approvestatus->status == 3)
                                                                <span class="right badge badge-danger" data-toggle="tooltip" data-placement="bottom" title="{{$approvestatus->name}}"><i class="fa fa-times"></i></span>
                                                                @else
                                                                <span class="right badge badge-warning" data-toggle="tooltip" data-placement="bottom" title="{{$approvestatus->name}}"> . . .</span>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                @else
                                                    <button class="btn btn-sm btn-block btn-warning" data-toggle="modal" data-target="#pending{{$leave->id}}"><strong>Pending</strong></button>
                                                    <div class="modal fade" id="pending{{$leave->id}}" style="display: none;" aria-hidden="true">
                                                        <div class="modal-dialog modal-md">
                                                            
                                                            @if(auth()->user()->type == '1')
    
                                                            <form action="/teacher/leaves/changestatus" method="get" id="{{$leave->id}}" name="changestatus">
                                                                
                                                            @elseif(auth()->user()->type == '2')
    
                                                            <form action="/principal/leaves/changestatus" method="get" id="{{$leave->id}}" name="changestatus">
    
                                                            @elseif(auth()->user()->type == '3' || auth()->user()->type == '8' )
    
                                                            <form action="/registrar/leaves/changestatus" method="get" id="{{$leave->id}}" name="changestatus">
    
                                                            @elseif(auth()->user()->type == '4' || auth()->user()->type == '15')
    
                                                            <form action="/teacher/leaves/changestatus" method="get" id="{{$leave->id}}" name="changestatus">
    
                                                            @elseif(auth()->user()->type == '6')
    
                                                            <form action="/teacher/leaves/changestatus" method="get" id="{{$leave->id}}" name="changestatus">
    
                                                            @elseif(auth()->user()->type == '10')
    
                                                            <form action="/hr/leaves/changestatus" method="get" id="{{$leave->id}}" name="changestatus">
    
                                                            @elseif(auth()->user()->type == '12')
    
                                                            <form action="/teacher/leaves/changestatus" method="get" id="{{$leave->id}}" name="changestatus">
    
                                                            @endif
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        @if($leave->usertypeid == '6')
                                                                        <h4 class="modal-title">{{$leave->name}}</h4>
                                                                        @else
                                                                        <h4 class="modal-title text-uppercase">{{$leave->lastname}}, {{$leave->firstname}}</h4>
                                                                        @endif
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body leaveapprovalcontainer" style="text-align: none !important;">
                                                                        <input id="{{$leave->id}}" type="hidden" value="{{$leave->id}}" name="leaveid"/>
                                                                        <h4><strong>{{$leave->leave_type}} Leave</strong></h4>
                                                                        <br>
                                                                        Days: {{$leave->numofdays}}
                                                                        <br>
                                                                        <br>
                                                                        From: {{$leave->datefrom}}
                                                                        <br>
                                                                        <br>
                                                                        To: {{$leave->dateto}}
                                                                        <br>
                                                                        <br>
                                                                        <strong>Remarks</strong>
                                                                        <textarea id="{{$leave->id}}" class="form-control" name="content" rows="2" disabled>{{$leave->reason}}</textarea>
                                                                        <br>
                                                                    </div>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <input id="{{$leave->id}}" type="hidden" value="sad" name="status"/>
                                                                        <button type="button" class="btn btn-warning disapproved" id="disapproved{{$leave->id}}">Disapprove</button>
                                                                        <button type="button" class="btn btn-primary approves" id="approved{{$leave->id}}">Approve</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                            {{-- @endif     --}}
                                        @elseif($leave->status == '3')
                                            <button class="btn btn-sm btn-block btn-danger">Disapproved</button>
                                            @if(auth()->user()->type == 10)
                                                <br/>
                                                @if(isset($leave->approvals))    
                                                    @if(count($leave->approvals)>0)
                                                        @foreach ($leave->approvals as $approvestatus)
                                                            @if($approvestatus->status == 1)
                                                            <span class="right badge badge-success" data-toggle="tooltip" data-placement="top" title="{{$approvestatus->name}}"><i class="fa fa-check"></i></span>
                                                            {{-- @elseif($approve->status == 2) --}}
                                                            @elseif($approvestatus->status == 3)
                                                            <span class="right badge badge-danger" data-toggle="tooltip" data-placement="top" title="{{$approvestatus->name}}"><i class="fa fa-times"></i></span>
                                                            @else
                                                            <span class="right badge badge-warning" data-toggle="tooltip" data-placement="top" title="{{$approvestatus->name}}"> . . .</span>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endif
                                            @endif
                                        @elseif($leave->status == '1')
                                            <button class="btn btn-sm btn-block btn-success">Approved</button>
                                            @if(auth()->user()->type == 10)
                                                <br/>
                                                @if(isset($leave->approvals))    
                                                    @if(count($leave->approvals)>0)
                                                        @foreach ($leave->approvals as $approvestatus)
                                                            @if($approvestatus->status == 1)
                                                            <span class="right badge badge-success" data-toggle="tooltip" data-placement="top" title="{{$approvestatus->name}}"><i class="fa fa-check"></i></span>
                                                            {{-- @elseif($approve->status == 2) --}}
                                                            @elseif($approvestatus->status == 3)
                                                            <span class="right badge badge-danger" data-toggle="tooltip" data-placement="top" title="{{$approvestatus->name}}"><i class="fa fa-times"></i></span>
                                                            @else
                                                            <span class="right badge badge-warning" data-toggle="tooltip" data-placement="top" title="{{$approvestatus->name}}"> . . .</span>
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
        </div>
    </div>
</div>
<div class="modal fade" id="modal-forceactivation" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/hr/leave/forcepermission" method="GET">
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
                        <input type="hidden" id="employeeleaveid" name="employeeleaveid">
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
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script>
    $(function () {
        
        $('.select2').select2();

        $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
        });
        
        $('.compose-textarea').summernote({
            
            toolbar: []
        });
        $('#leavedaterange').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    })
   
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
        $('.editrange').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            }
        })
        $('.note-editable').attr('contenteditable','false');
                $('.note-editable').css('backgroundColor','white');
                $('.note-editable').css('backgroundColor','white');
                $('.note-editor').removeClass('card');
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
   })
   $(document).on('click', '.disapproved', function(){
    //    console.log($(this).closest('form[name=changestatus]'));
       $(this).prev('input').val($(this)[0].innerText);
       $(this).closest('form[name=changestatus]').submit();

   })
   $(document).on('click', '.approves', function(){
    //    console.log($(this).closest('form[name=changestatus]'));
       $(this).prev().prev('input').val($(this).text());
       $(this).closest('form[name=changestatus]').submit();

   });
   $(document).on('click','.overrideactivation', function(){
       $('#modal-forceactivation').modal('show')
       $('#employeeleaveid').val($(this).attr('data-id'))
   })
//    $(document).on('click', 'input[name=withorwithoutpay]', function(){
//        if($(this).val() == '1'){
//             $('.leaveapprovalcontainer').append(
//                 '<labe>Amount</label>'+
//                 '<input type="number" class="form-control form-controlsm" name="payamount" id="payamount" placeholder="0.00" required>;'
//             )
//        }
//        else{
//            $('#payamount').remove();
//        }
//    })
  </script>
@endsection

