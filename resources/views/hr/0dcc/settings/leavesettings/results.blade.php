
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
       @if(count($leaves) == 0)        
            <div class="alert alert-warning" role="alert">
                No leave types shown
            </div>
        @else
            <div class="card-body p-0" style="overflow: scroll;">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" style="font-size: 12px;">
                            <thead>
                                <tr class="text-center">
                                    <th>Type</th>
                                    <th style="width: 10%;"># of days<br/>per employee</th>
                                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sait')
                                    <th style="width: 15%;">Approvals</th>
                                    @endif
                                    <th style="width: 10%;"># of Employees<br/>applied</th>
                                    <th style="width: 10%;">With Pay</th>
                                    <th style="width: 10%;">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaves as $leave)
                                    <tr>
                                        <td><input type="text" class="form-control leavename" value="{{$leave->leave_type}}" readonly="true" ondblclick="this.readOnly='';" data-id="{{$leave->id}}"/> </td>
                                        <td><input type="text" class="form-control leavedays" value="{{$leave->days}}"  readonly="true" ondblclick="this.readOnly='';" data-id="{{$leave->id}}"/> </td>
                                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sait')
                                        <td><button type="button" class="btn btn-block btn-default btn-sm btn-view-approvals" data-leaveid="{{$leave->id}}"><i class="fa fa-cogs"></i> Approval</button></td>
                                        @endif
                                        <td style="vertical-align: middle;" class="text-center"><span class="badge @if(count($leave->employees)>0)badge-success @else @endif" style="font-size: 12px;">{{count($leave->employees)}}</span></td>
                                        <td class="text-center"  style="vertical-align: middle;"><div class="icheck-primary d-inline mr-3"><input type="checkbox" id="{{$leave->id}}radioPrimary1" name="withpay" @if($leave->withpay == 1) value="1" checked @else value="0" @endif  data-id="{{$leave->id}}"/> <label for="{{$leave->id}}radioPrimary1"></label></div></td>
                                        <td>@if(count($leave->employees)==0)<button type="button" class="btn btn-block btn-default btn-deleteleave" data-id="{{$leave->id}}"><i class="fa fa-trash-alt"></i></button>@endif</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif