
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
        .card{
            border: 1px solid #56ba9c;
            box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
        }
      </style>
        {{-- MODAL  --}}
        
        <div class="modal fade" id="modalsetupdayyears">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">DAYS PER YEAR OF SERVICE</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <input type="hidden" id="leaveid">
                            <button type="button" class="btn btn-sm btn-primary addyearsdays"><i class="fas fa-plus"></i> Add Setup</button>
                        </div>
                        <div class="col-md-12 mt-2 container-daysyears">
                            {{-- <table width="100%" class="" id="daysyears-table"  style="font-size: 16px" border="0">
                                <tr>
                                    <td width="30%"><b>YEARS</b></td>
                                    <td width="30%"><b>DAYS</b></td>
                                    <td width="10%"><b>ACTION</b></td>
                                </tr>
                            </table> --}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="" style="visibility: hidden">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <div class="modal fade" id="modaladddayyears">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="modyearsofservice">Years of Service</label>
                            <input type="number" value="0" class="form-control" id="modyearsofservice" min="0">
                        </div>
                        <div class="form-group">
                            <label for="moddays">Days</label>
                            <input type="number" value="0" class="form-control" id="moddays" min="0">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btnadddaysyears">Add</button>
                    </div>
                </div>
            </div>
        </div>
        {{--  --}}
        @if(count($leaves) == 0)        
            <div class="alert alert-warning" role="alert">
                Currently, there are no leave records available. Please create a new leave entry to proceed.
            </div>
        @else
            {{-- <div class="card">
                <div class="card-header">
                    <h4 class="title">YEAR</h4>
                </div> --}}
                <div class="card-body p-0" style="overflow: scroll;">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered" style="font-size: 12px;">
                                <thead>
                                    <tr class="text-center">
                                        <th>Type</th>
                                        {{-- <th style="width: 10%;"># of days</th> --}}
                                        <th style="width: 15%;">Approvals</th>
                                        <th style="width: 10%;"># of Employees<br/>applied</th>
                                        <th style="width: 10%;">With Pay</th>
                                        {{-- <th style="width: 10%;">Year of Service</th> --}}
                                        {{-- <th style="width: 10%;">Convert to Cash</th> --}}
                                        <th style="width: 10%;">Set Days Per Years of service</th>
                                        <th style="width: 10%;">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaves as $leave)
                                        <tr>
                                            <td><input type="text" class="form-control leavename" value="{{$leave->leave_type}}" readonly="true" ondblclick="this.readOnly='';" data-id="{{$leave->id}}"/> </td>
                                            {{-- <td><input type="text" class="form-control text-center leavedays" value="{{$leave->days}}"  readonly="true" ondblclick="this.readOnly='';" data-id="{{$leave->id}}"/> </td> --}}
                                            <td><button type="button" class="btn btn-block btn-default btn-sm btn-view-approvals" data-leaveid="{{$leave->id}}"><i class="fa fa-cogs"></i> Approval</button></td>
                                            <td style="vertical-align: middle;" class="text-center"><span class="badge @if(count($leave->employees)>0)badge-success @else @endif" style="font-size: 12px;">{{count($leave->employees)}}</span></td>
                                            <td class="text-center"  style="vertical-align: middle;"><div class="icheck-primary d-inline"><input type="checkbox" id="{{$leave->id}}radioPrimary1" name="withpay" @if($leave->withpay == 1) value="1" checked @else value="0" @endif  data-id="{{$leave->id}}"/> <label for="{{$leave->id}}radioPrimary1"></label></div></td>
                                            {{-- <td><input type="text" class="form-control text-center yearofservice" value="{{$leave->yos}}"  readonly="true" ondblclick="this.readOnly='';" data-id="{{$leave->id}}"/> </td> --}}
                                            {{-- <td class="text-center"  style="vertical-align: middle;"><div class="icheck-primary d-inline"><input type="checkbox" id="{{$leave->id}}radioPrimary2" name="tocash" @if($leave->cash == 1) value="1" checked @else value="0" @endif  data-id="{{$leave->id}}"/> <label for="{{$leave->id}}radioPrimary2"></label></div></td> --}}
                                            <td class="text-center" style="font-size: 18px; vertical-align: middle;"><a href="javascript:void(0)" class="set_days_years" id="set_days_years{{$leave->id}}" data-id="{{$leave->id}}"><i class="fas fa-calendar-alt"></i></a></td>
                                            <td>@if(count($leave->employees)==0)<button type="button" class="btn btn-block btn-default btn-deleteleave" data-id="{{$leave->id}}"><i class="fa fa-trash-alt"></i></button>@endif</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            {{-- </div> --}}
                    {{-- <div class="card-body">
            @foreach($leaves as $leave)
                        <div class="row">
                            <div class="col-md-12">
                                <hr/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Type</label>
                                <input type="text" class="form-control leavename" value="{{$leave->leave_type}}" readonly="true" ondblclick="this.readOnly='';" data-id="{{$leave->id}}"/> 
                            </div>
                            <div class="col-md-4">
                                <label>
                                    <small>No. of days per employee</small>
                                </label>
                                <input type="text" class="form-control leavedays" value="{{$leave->days}}"  readonly="true" ondblclick="this.readOnly='';" data-id="{{$leave->id}}"/> 
                            </div>
                            <div class="col-md-2 text-right">
                                <label>&nbsp;</label><br/>
                                <button type="button" class="btn btn-default btn-sm btn-view-approvals" data-leaveid="{{$leave->id}}"><i class="fa fa-cogs"></i> Approval</button>
                            </div>
                            <div class="col-md-2 text-right">
                                <label>&nbsp;</label><br/>
                                <div class="icheck-primary d-inline mr-3">
                                    <input type="checkbox" id="{{$leave->id}}radioPrimary1" name="withpay" @if($leave->withpay == 1) value="1" checked @else value="0" @endif  data-id="{{$leave->id}}"/> 
                                    <label for="{{$leave->id}}radioPrimary1">
                                        With Pay
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-5 mt-2">
                            </div>
                            <div class="col-md-4 mt-2">
                                <button type="button" class="btn btn-default btn-sm btn-block btn-view-approvals" data-leaveid="{{$leave->id}}" style="font-size: 11px;"><i class="fa fa-cogs"></i> Approval</button>
                            </div>
                            <div class="col-md-3 text-right">
                                &nbsp;<button type="button" class="btn btn-default btn-deleteleave" data-id="{{$leave->id}}"><i class="fa fa-trash-alt"></i></button></div>
                        </div>
            @endforeach
            </div> --}}
        @endif

        <script>
            $(document).ready(function () {
                // modal close
                $('#modalsetupdayyears').on('hidden.bs.modal', function (e) {
                    $(this).find('input').val('');
                });
                $('#modaladddayyears').on('hidden.bs.modal', function (e) {
                    $(this).find('input').val('');
                });
            });
        </script>