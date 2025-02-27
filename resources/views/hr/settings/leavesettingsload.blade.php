
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
            @foreach($leaves as $leave)
                <div class="card collapsed-card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Type</label>
                                <input type="text" class="form-control leavename" value="{{$leave->leave_type}}" readonly="true" ondblclick="this.readOnly='';" data-id="{{$leave->id}}"/> 
                            </div>
                            <div class="col-md-1">&nbsp;</div>
                            <div class="col-md-4">
                                <label>
                                    <small>No. of applications per employee</small>
                                </label>
                                <input type="text" class="form-control leavedays" value="{{$leave->days}}"  readonly="true" ondblclick="this.readOnly='';" data-id="{{$leave->id}}"/> 
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
                            <div class="col-md-1 text-right"><label>&nbsp;</label><br/><button type="button" class="btn btn-default btn-deleteleave" data-id="{{$leave->id}}"><i class="fa fa-trash-alt"></i></button></div>
                        </div>
                        <button type="button" class="btn btn-tool p-0 mt-2 mb-2" data-card-widget="collapse">
                            <i class="fas fa-plus"></i> View Details
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-4">
                                @php
                                    $datescovered = collect($leave->dates)->toArray();
                                    $datescovered = array_chunk($datescovered, 2);            
                                @endphp
                                <table class="table" style="font-size: 11px;">
                                    <thead>
                                        <tr>
                                            <th class="p-1">Dates covered</th>
                                            <th class="text-right p-0">
                                                <button type="button" class="btn btn-default btn-sm btn-addmoredates" data-leaveid="{{$leave->id}} "style="font-size: 11px;"><i class="fa fa-plus"></i> Add dates</button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($datescovered) == 0)
                                            <tr>
                                                <td colspan="2" class="p-0 text-center">
                                                    <label>No dates selected</label>
                                                </td>
                                            </tr>
                                        @else
                                            @foreach($datescovered as $datecovered)
                                                @foreach($datecovered as $date)
                                                    <tr>
                                                        <td colspan="2" class="p-1">
                                                            <button type="button" class="btn btn-default btn-sm btn-delete-date" data-id="{{$date->id}}" style="font-size: 11px;">{{date('D, M d, Y', strtotime($date->ldatefrom))}} - {{date('D, M d, Y', strtotime($date->ldateto))}}</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-1">&nbsp;</div>
                            <div class="col-md-7">
                                <table class="table" style="font-size: 11px;">
                                    <thead>
                                        <tr>
                                            <th class="p-1" style="width: 70%;">&nbsp;</th>
                                            <th class="text-right p-0">
                                                <button type="button" class="btn btn-default btn-sm btn-addmoreemployees" data-leaveid="{{$leave->id}}" style="font-size: 11px;"><i class="fa fa-plus"></i> Add employees</button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($leave->employees)==0)
                                            <tr>
                                                <td colspan="2" class="p-0 text-center">
                                                    <label>No employees selected</label>
                                                </td>
                                            </tr>
                                        @else
                                            @foreach($leave->employees as $employeekey => $employee)
                                                <tr>
                                                    <td class="p-1">
                                                        <button type="button" class="btn btn-default btn-sm btn-block btn-delete-employee" data-id="{{$employee->id}}" style="font-size: 11px; text-align: left;">{{$employeekey+1}}. {{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}}</button>
                                                    </td>
                                                    <td class="p-1">
                                                        <button type="button" class="btn btn-default btn-sm btn-block btn-view-approvals" data-leaveempid="{{$employee->id}}" data-leaveid="{{$leave->id}}" style="font-size: 11px;"><i class="fa fa-cogs"></i> Approval</button>
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
            @endforeach
        @endif