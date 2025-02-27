
@php
$te = 0;
$td = 0;
$saved = 0;
@endphp

@php
$overrideconfigured = 0;
@endphp
@if(count($standarddeductions)>0)
@if($standarddeductions[0]->fullamount>0)
    @php
        $overrideconfigured+=1;
    @endphp
@endif
@endif
@if(count($otherdeductions)>0)
@if($otherdeductions[0]->fullamount>0)
    @php
        $overrideconfigured+=1;
    @endphp
@endif
@endif
@if(count($standardallowances)>0)
@if($standardallowances[0]->fullamount>0)
    @php
        $overrideconfigured+=1;
    @endphp
@endif
@endif
@if(count($otherallowances)>0)
@if($otherallowances[0]->fullamount>0)
    @php
        $overrideconfigured+=1;
    @endphp
@endif
@endif
<div class="row">
    <div class="col-md-2">
        <div class="table-avatar">
            @php
                    $number = rand(1,3);
                    if(strtoupper($personalinfo->gender) == 'FEMALE'){
                        $avatar = 'avatar/T(F) '.$number.'.png';
                    }
                    else{
                        $avatar = 'avatar/T(M) '.$number.'.png';
                    }
                @endphp
            <a href="#" class="avatar">
                    <img src="{{ asset($picurl) }}" alt="" onerror="this.onerror = null, this.src='{{asset($avatar)}}'" style="width: 70px;"/>
           
        </div>
    </div>
    <div class="col-md-10">
        <a href="/hr/employees/profile/index?employeeid={{$personalinfo->employeeid}}">   {{$personalinfo->lastname}}, {{$personalinfo->firstname}} {{$personalinfo->middlename}} {{$personalinfo->suffix}} </a>
        <div class="form-group m-0" style="display: -webkit-box;">
            <div class="form-check pr-2">
              <input class="form-check-input" type="checkbox" @if($basicsalaryinfo->mondays == 1) checked @endif disabled>
              <label class="form-check-label">M</label>
            </div>
            <div class="form-check pr-2">
              <input class="form-check-input" type="checkbox" @if($basicsalaryinfo->tuesdays == 1) checked @endif disabled>
              <label class="form-check-label">T</label>
            </div>
            <div class="form-check pr-2">
              <input class="form-check-input" type="checkbox" @if($basicsalaryinfo->wednesdays == 1) checked @endif disabled>
              <label class="form-check-label">W</label>
            </div>
            <div class="form-check pr-2">
              <input class="form-check-input" type="checkbox" @if($basicsalaryinfo->thursdays == 1) checked @endif disabled>
              <label class="form-check-label">Th</label>
            </div>
            <div class="form-check pr-2">
              <input class="form-check-input" type="checkbox" @if($basicsalaryinfo->fridays == 1) checked @endif disabled>
              <label class="form-check-label">F</label>
            </div>
            <div class="form-check pr-2">
              <input class="form-check-input bg-primary" type="checkbox" @if($basicsalaryinfo->saturdays == 1) checked @endif disabled>
              <label class="form-check-label">Sat</label>
            </div>
            <div class="form-check pr-2">
              <input class="form-check-input" type="checkbox" @if($basicsalaryinfo->sundays == 1) checked @endif disabled>
              <label class="form-check-label">Sun</label>
            </div>
        </div>
        <table class="table m-0" style="width: 100%; font-size: 14px; table-layout: fixed;">
            <tr>
                <td class="p-0" style="width: 20%;"><small>Civil Status : </small></td>
                <td class="p-0"><small>{{$personalinfo->civilstatus}}</small></td>
                <td class="p-0" style="width: 20%;"><small>Designation : </small></td>
                <td class="p-0 text-right"><small>{{$personalinfo->utype}}</small></td>
            </tr>
        </table>
    </div>
</div>
@if($basicsalaryinfo->basicsalary != '0.00')
<div class="row" style="font-size: 14px;">
    <div class="col-md-5">
        <table class="table m-0">
            <tr>
                <td class="p-0" style="width: 35%;"><small>Basic Salary </small></td>
                <td class="p-0"><small>: {{number_format($basicsalaryinfo->payrollbasic,2)}}</small></td>
            </tr>
            <tr>
                <td class="p-0"><small>Salary Basis </small></td>
                <td class="p-0"><small>: {{$basicsalaryinfo->salarytype}}</small></td>
            </tr>
            <tr>
                <td class="p-0"><small>Work Shift </small></td>
                <td class="p-0">
                    @if($basicsalaryinfo->shiftid == 1)
                        <small>: Morning Shift</small>
                    @elseif($basicsalaryinfo->shiftid == 2)
                        <small>: Night Shift</small>
                    @else
                        <small>: Whole Day</small>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-7">
        <table class="table m-0">
            <tr>
                <td class="p-0" style="width: 30%;"><small>Working Days</small></td>
                <td class="p-0"><small>: {{count($payrollworkingdays)}} day(s)</small></td>
                <td class="p-0 text-right"><small><strong>{{number_format($basicsalaryinfo->payrollbasic,2)}}</strong></small></td>
            </tr>
            <tr>
                <td class="p-0" style="width: 30%;"><small>Days Present</small></td>
                <td class="p-0"><small>: {{count($attendancedetails->attendancepresent)}} day(s)</small></td>
                <td class="p-0 text-right text-success text-bold"><small><strong>+ {{number_format($attendancedetails->attendanceearnings,2)}}</strong></small></td>
            </tr>
            @if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
            <tr>
                <td class="p-0" style="width: 30%;"><small>Hours rendered</small></td>
                <td class="p-0"><small>: {{$attendancedetails->hoursrendered}} hour(s)</small></td>
                <td class="p-0 text-right text-bold"><small><strong>{{number_format($attendancedetails->attendanceearnings,2)}}</strong></small></td>
            </tr>
            @endif
            @php
                $days_absent = collect($attendancedetails->attendanceabsent)->map(function($value, $key){
                    return $value <= date('Y-m-d');
                })->count()
            @endphp
            <tr>
                <td class="p-0" style="width: 30%;"><small>Days Absent</small></td>
                <td class="p-0"><small>: {{$days_absent}} day(s)</small></td>
                <td class="p-0 text-right text-danger text-bold"><small><strong>- {{number_format($attendancedetails->attendancedeductions,2)}}</strong></small></td>
            </tr>
            <tr>
                <td class="p-0" style="width: 30%;"><small>Late</small></td>
                <td class="p-0">
                    @if($attendancedetails->lateminutes == 0)
                    <small>: </small>
                    @else
                    <small>:  <a href="#" class="btn-lates" data-toggle="modal" data-target="#modal-lates">{{$attendancedetails->lateminutes}} min(s).</a> </small>
                    
                        <div class="modal fade" id="modal-lates">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Lates</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body p-0">
                                        <table class="table table-bordered m-0" style="font-size: 11px;">
                                            <thead>
                                                <tr>
                                                    <th class="p-1"></th>
                                                    <th class="p-1">AM Late Minutes</th>
                                                    <th class="p-1">PM Late Minutes</th>
                                                    <th class="p-1">Amount</th>
                                                </tr>
                                            </thead>
                                            @foreach($attendancedetails->attendancelate as $eachlate)
                                                <tr>
                                                    <td class="p-1"></td>
                                                    <td class="p-1 text-right">{{$eachlate->lateamin}}</td>
                                                    <td class="p-1 text-right">{{$eachlate->latepmin}}</td>
                                                    <td class="p-1 text-right">
                                                        @if($eachlate->latedeductionamount == 0)
                                                        No tardiness bracket found
                                                        @else
                                                        {{$eachlate->latedeductionamount}}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th class="p-1">Total</th>
                                                <th class="p-1 text-right">{{collect($attendancedetails->attendancelate)->sum('lateamin')}}</th>
                                                <th class="p-1 text-right">{{collect($attendancedetails->attendancelate)->sum('latepmin')}}</th>
                                                <th class="p-1 text-right">{{collect($attendancedetails->attendancelate)->sum('latedeductionamount')}}</th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>                    
                            </div>                    
                        </div>
                    @endif
                </td>
                <td class="p-0 text-right text-danger">
                    <small><strong>- {{number_format($attendancedetails->latedeductionamount,2)}}</strong></small>
                    @php
                        $td+=collect($attendancedetails->attendancelate)->sum('latedeductionamount');
                    @endphp
                </td>
            </tr>
        </table>
    </div>
</div>
@endif
{{-- <div class="row text-muted text-uppercase">
    <div class="col-md-4">
        <small>Civil Status </small>
    </div>
    <div class="col-md-8">
        <small>: {{$personalinfo->civilstatus}}</small>
    </div>
</div>
<div class="row text-muted text-uppercase">
    <div class="col-md-4">
        <small>Department </small>
    </div>
    <div class="col-md-8">
        <small>: {{$personalinfo->department}}</small>
    </div>
</div>
<div class="row text-muted text-uppercase">
    <div class="col-md-4">
        <small>Designation </small>
    </div>
    <div class="col-md-8">
        <small>: {{$personalinfo->utype}}</small>
    </div>
</div> --}}
{{-- <hr/> --}}
{{-- <div class="row text-muted text-uppercase">
    <div class="col-md-4">
        <small>Basic Salary </small>
    </div>
    <div class="col-md-8">
        <small>: {{$basicsalaryinfo->payrollbasic}}</small>
    </div>
</div>
<div class="row text-muted text-uppercase">
    <div class="col-md-4">
        <small>Salary Basis </small>
    </div>
    <div class="col-md-8">
        <small>: {{$basicsalaryinfo->salarytype}}</small>
    </div>
</div>

<div class="row text-muted text-uppercase">
    <div class="col-md-4">
        <small>Work Shift </small>
    </div>
    <div class="col-md-8">
        @if($basicsalaryinfo->shiftid == 1)
            <small>: Morning Shift</small>
        @elseif($basicsalaryinfo->shiftid == 2)
            <small>: Night Shift</small>
        @else
            <small>: Whole Day</small>
        @endif
    </div>
</div> --}}
{{-- <div class="row text-muted text-uppercase">
    <div class="col-md-12">
        <div class="form-group m-0" style="display: -webkit-box;">
            <div class="form-check pr-2">
              <input class="form-check-input" type="checkbox" @if($basicsalaryinfo->mondays == 1) checked @endif disabled>
              <label class="form-check-label">M</label>
            </div>
            <div class="form-check pr-2">
              <input class="form-check-input" type="checkbox" @if($basicsalaryinfo->tuesdays == 1) checked @endif disabled>
              <label class="form-check-label">T</label>
            </div>
            <div class="form-check pr-2">
              <input class="form-check-input" type="checkbox" @if($basicsalaryinfo->wednesdays == 1) checked @endif disabled>
              <label class="form-check-label">W</label>
            </div>
            <div class="form-check pr-2">
              <input class="form-check-input" type="checkbox" @if($basicsalaryinfo->thursdays == 1) checked @endif disabled>
              <label class="form-check-label">Th</label>
            </div>
            <div class="form-check pr-2">
              <input class="form-check-input" type="checkbox" @if($basicsalaryinfo->fridays == 1) checked @endif disabled>
              <label class="form-check-label">F</label>
            </div>
            <div class="form-check pr-2">
              <input class="form-check-input bg-primary" type="checkbox" @if($basicsalaryinfo->saturdays == 1) checked @endif disabled>
              <label class="form-check-label">Sat</label>
            </div>
            <div class="form-check pr-2">
              <input class="form-check-input" type="checkbox" @if($basicsalaryinfo->sundays == 1) checked @endif disabled>
              <label class="form-check-label">Sun</label>
            </div>
          </div>
    </div>
</div> --}}
{{-- <hr class="m-0"/> --}}

@if($basicsalaryinfo->basicsalary != '0.00')
    {{-- <div class="row text-muted">
        <div class="col-md-4">
            <small>Working Days </small>
        </div>
        <div class="col-md-4">
            <small>: {{count($payrollworkingdays)}} day(s)</small>
        </div>
        <div class="col-md-4">
            <small>: {{number_format($basicsalaryinfo->payrollbasic,2)}}</small>
        </div>
    </div>
    <div class="row text-muted">
        <div class="col-md-4">
            <small>Days Present </small>
        </div>
        <div class="col-md-4">
            <small>: {{count($attendancedetails->attendancepresent)}} day(s)</small>
        </div>
        <div class="col-md-4 text-success">
            <small>: <strong>{{number_format($attendancedetails->attendanceearnings,2)}}</strong></small> --}}
            @php
                $te+=$attendancedetails->attendanceearnings;
            @endphp
        {{-- </div>
    </div> --}}
    {{-- @if(strtolower($basicsalaryinfo->salarytype) == 'hourly')
        <div class="row text-muted">
            <div class="col-md-4">
                <small>Hours rendered </small>
            </div>
            <div class="col-md-4">
                <small>: {{$attendancedetails->hoursrendered}} hour(s)</small>
            </div>
            <div class="col-md-4 text-success">
            </div>
        </div>
    @endif --}}
    {{-- <div class="row text-muted">
        <div class="col-md-4">
            <small>Days Absent </small>
        </div>
        <div class="col-md-4">
            @php
                $days_absent = collect($attendancedetails->attendanceabsent)->map(function($value, $key){
                    return $value <= date('Y-m-d');
                })->count()
            @endphp
            <small>:  {{$days_absent}}day(s)</small>
        </div>
        <div class="col-md-4 text-danger">
            <small>: <strong>{{number_format($attendancedetails->attendancedeductions,2)}}</strong></small>
        </div>
    </div> --}}
    {{-- <div class="row text-muted">
        <div class="col-md-4">
            <small><strong>Tardiness</strong> </small>
        </div>
    </div> --}}
    {{-- <div class="row text-muted">
        <div class="col-md-4">
            <small>Late </small>
        </div>
        <div class="col-md-4">
            @if($attendancedetails->lateminutes == 0)
            <small>:  {{$attendancedetails->lateminutes}} min(s). </small>
            @else
            <small>:  <a href="#" class="btn-lates" data-toggle="modal" data-target="#modal-lates">{{$attendancedetails->lateminutes}} min(s).</a> </small>
            
                <div class="modal fade" id="modal-lates">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Lates</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body p-0">
                                <table class="table table-bordered m-0" style="font-size: 11px;">
                                    <thead>
                                        <tr>
                                            <th class="p-1"></th>
                                            <th class="p-1">AM Late Minutes</th>
                                            <th class="p-1">PM Late Minutes</th>
                                            <th class="p-1">Amount</th>
                                        </tr>
                                    </thead>
                                    @foreach($attendancedetails->attendancelate as $eachlate)
                                        <tr>
                                            <td class="p-1"></td>
                                            <td class="p-1 text-right">{{$eachlate->lateamin}}</td>
                                            <td class="p-1 text-right">{{$eachlate->latepmin}}</td>
                                            <td class="p-1 text-right">
                                                @if($eachlate->latedeductionamount == 0)
                                                No tardiness bracket found
                                                @else
                                                {{$eachlate->latedeductionamount}}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th class="p-1">Total</td>
                                        <th class="p-1 text-right">{{collect($attendancedetails->attendancelate)->sum('lateamin')}}</th>
                                        <th class="p-1 text-right">{{collect($attendancedetails->attendancelate)->sum('latepmin')}}</th>
                                        <th class="p-1 text-right">{{collect($attendancedetails->attendancelate)->sum('latedeductionamount')}}</th>
                                    </tr>
                                </table>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>                    
                    </div>                    
                </div>
            @endif
        </div>
        <div class="col-md-4 text-danger">
            <small>: <strong>{{number_format($attendancedetails->latedeductionamount,2)}}</strong></small>
            @php
                $td+=$attendancedetails->latedeductionamount;
            @endphp
        </div>
    </div> --}}
    {{-- <div class="row text-muted">
        <div class="col-md-4">
            <small>Undertime </small>
        </div>
        <div class="col-md-4">
            <small>:  {{$attendancedetails->undertimeamout + $attendancedetails->undertimepmout}} min(s) </small>
        </div>
        <div class="col-md-4">
            <small>:  </small>
        </div>
    </div> --}}
    @if(count($leavedetails)>0)
    <hr/>
        <div class="row text-muted">
            <div class="col-md-12">
                <small><strong>LEAVES</strong></small>
            </div>
        </div>
        @php
            $leavedetails = collect($leavedetails)->groupBy('leave_type');
        @endphp
        @foreach($leavedetails as $leavetype => $leavedetail)
            <div class="row text-muted">
                <div class="col-md-4">
                    <small>{{$leavetype}} ({{count($leavedetail)}} d(s)) </small>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        @foreach($leavedetail as $leave)
                            <div class="col-md-12">
                                <small>:{{date('m/d/Y', strtotime($leave->ldate))}}  </small>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        @foreach($leavedetail as $leave)
                            <div class="col-md-12">
                                <small>:<strong><span class="text-success"> {{number_format($leave->amount,2)}}</span></strong>  </small>
                                @php
                                    $te+=$leave->amount;
                                @endphp
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @endif
    @if(count($overtimedetails)>0)
        <div class="row text-muted">
            <div class="col-md-12">
                <small><strong>Overtimes</strong></small>
            </div>
        </div>
        @foreach($overtimedetails as $overtime)
        <div class="row text-muted">
            <div class="col-md-4">
                @if($overtime->holiday == 1)
                <small>Holiday</small>
                @else
                <small>Regular</small>
                @endif
            </div>
            <div class="col-md-4">
                @if($overtime->holiday == 1)
                <small>: {{date('m/d/Y', strtotime($overtime->datefrom))}}</small>
                @else
                <small>: {{date('m/d/Y', strtotime($overtime->datefrom))}}</small>
                @endif
                <small><strong> {{$overtimedetails[0]->numofhours}} hr(s)</strong> </small>
            </div>
            <div class="col-md-4 text-success">
                <small>: {{$overtimedetails[0]->amount}} </small>
                @php
                    $te+=$overtimedetails[0]->amount;
                @endphp
            </div>
        </div>
        @endforeach
    @endif
    @if(count($standarddeductions)>0)
        <hr class="m-1"/>        
        {{-- <div style="border: 2px solid #ddd"> --}}
            @if(count($standarddeductions[0]->standarddeductions) > 0)
                <div class="row text-muted text-uppercase">
                    <div class="col-md-12">
                        <small><strong>Standard Deductions</strong></small>
                    </div>
                </div>
                @foreach($standarddeductions[0]->standarddeductions as $standarddeduction)
                    <div class="row mt-2 text-muted text-uppercase">
                        <div class="col-md-4">
                            <small>{{$standarddeduction->description}}</small>
                        </div>
                        @if($standarddeduction->saved == 1)
                            @php
                            $saved+=1;
                            @endphp
                        @endif
                        @if($standarddeduction->paymentoption == 2)
                            <div class="col-md-8">
                                <div class="row"  data-id="{{$standarddeduction->id}}" data-desc="{{$standarddeduction->description}}" data-paymentoption="1">
                                    <div class="col-md-6">
                                        <small>: Half </small>
                                    </div>
                                    <div class="col-md-6">
                                        @if($checkifreleased == 1 && $standarddeduction->forcefull == 1 || $standarddeductions[0]->payrollstatus == 1)
                                        @else
                                        <small>: 
                                            <div class="icheck-primary d-inline " disabled>
                                            <input type="radio" value="{{$standarddeduction->eesamount/2}}" class="standarddeductions" id="standarddeduction{{$standarddeduction->id}}1" name="standarddeduction{{$standarddeduction->id}}" @if($checkifreleased == 1 || $standarddeduction->forcefull == 1) disabled @endif>
                                            <label for="standarddeduction{{$standarddeduction->id}}1" >
                                                    {{$standarddeduction->eesamount/2}} 
                                            </label>
                                            </div>
                                        </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-2" data-id="{{$standarddeduction->id}}" data-desc="{{$standarddeduction->description}}" data-paymentoption="2">
                                    <div class="col-md-6">
                                        <small>: Full </small>
                                    </div>
                                    <div class="col-md-6">
                                        
                                        @if($checkifreleased == 1 && $standarddeduction->forcefull == 1 || $standarddeductions[0]->payrollstatus == 1)
                                        <small>Paid for this month</small>
                                        @else
                                        <small>: 
                                            <div class="icheck-primary d-inline">
                                            <input type="radio" value="{{$standarddeduction->eesamount}}" class="standarddeductions" id="standarddeduction{{$standarddeduction->id}}2" name="standarddeduction{{$standarddeduction->id}}"checked
                                            @if($checkifreleased == 1 || $standarddeduction->forcefull == 1) disabled @endif>
                                            <label for="standarddeduction{{$standarddeduction->id}}2">
                                                    {{$standarddeduction->eesamount}} 
                                            </label>
                                            </div>
                                        </small>
                                            
                                        @php
                                            $td+=$standarddeduction->eesamount;
                                        @endphp
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-8">
                                <div class="row"  data-id="{{$standarddeduction->id}}" data-desc="{{$standarddeduction->description}}" data-paymentoption="1">
                                    <div class="col-md-6">
                                        <small>: Half </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small>: 
                                            <div class="icheck-primary d-inline">
                                            <input type="radio" value="{{$standarddeduction->eesamount}}" class="standarddeductions" id="standarddeduction{{$standarddeduction->id}}1" name="standarddeduction{{$standarddeduction->id}}" checked @if($checkifreleased == 1 || $standarddeduction->forcefull == 1) disabled @endif>
                                            <label for="standarddeduction{{$standarddeduction->id}}1">
                                                    {{$standarddeduction->eesamount}} 
                                            </label>
                                            </div>
                                        </small>
                                            
                                        @php
                                            $td+=$standarddeduction->eesamount;
                                        @endphp
                                    </div>
                                </div>
                                <div class="row mt-2" data-id="{{$standarddeduction->id}}" data-desc="{{$standarddeduction->description}}" data-paymentoption="2">
                                    <div class="col-md-6">
                                        <small>: Full </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small>: 
                                            <div class="icheck-primary d-inline">
                                            <input type="radio" value="{{$standarddeduction->eesamount*2}}" class="standarddeductions" id="standarddeduction{{$standarddeduction->id}}2" name="standarddeduction{{$standarddeduction->id}}"
                                            @if($checkifreleased == 1 || $standarddeduction->forcefull == 1) disabled @endif>
                                            <label for="standarddeduction{{$standarddeduction->id}}2">
                                                    {{$standarddeduction->eesamount*2}} 
                                            </label>
                                            </div>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        {{-- </div> --}}
    @endif
    @if(count($otherdeductions)>0)
    <hr class="m-1"/>        
        {{-- <div style="border: 2px solid #ddd"> --}}
            @if(count($otherdeductions[0]->otherdeductions) > 0)
                <div class="row text-muted text-uppercase mt-2">
                    <div class="col-md-12">
                        <small><strong>Other Deductions</strong></small>
                    </div>
                </div>
                @foreach($otherdeductions[0]->otherdeductions as $otherdeduction)
                    <div class="row mt-2 text-muted text-uppercase">
                        <div class="col-md-4">
                            <small>{{$otherdeduction->description}}</small>
                        </div>
                        @if($otherdeduction->saved == 1)
                            @php
                            $saved+=1;
                            @endphp
                        @endif
                        @if($otherdeduction->paymentoption == 2)
                            <div class="col-md-8">
                                <div class="row"  data-id="{{$otherdeduction->id}}" data-desc="{{$otherdeduction->description}}" data-paymentoption="1">
                                    <div class="col-md-6">
                                        <small>: Half </small>
                                    </div>
                                    <div class="col-md-6">
                                        
                                        @if($checkifreleased == 1 && $otherdeduction->forcefull == 1 || $otherdeductions[0]->payrollstatus == 1)
                                        @else
                                        <small>: 
                                            <div class="icheck-primary d-inline">
                                            <input type="radio" value="{{(float)$otherdeduction->amount/2}}" class="otherdeductions" id="otherdeduction{{$otherdeduction->id}}1" name="otherdeduction{{$otherdeduction->id}}" @if($checkifreleased == 1 || $otherdeduction->forcefull == 1) disabled @endif>
                                            <label for="otherdeduction{{$otherdeduction->id}}1">
                                                    {{(float)$otherdeduction->amount/2}} 
                                            </label>
                                            </div>
                                        </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-2" data-id="{{$otherdeduction->id}}" data-desc="{{$otherdeduction->description}}" data-paymentoption="2">
                                    <div class="col-md-6">
                                        <small>: Full </small>
                                    </div>
                                    <div class="col-md-6">
                                        
                                        @if($checkifreleased == 1 && $otherdeduction->forcefull == 1 || $otherdeductions[0]->payrollstatus == 1)
                                        <small>Paid for this month</small>
                                        @else
                                        <small>: 
                                            <div class="icheck-primary d-inline">
                                            <input type="radio" value="{{(float)$otherdeduction->amount}}" class="otherdeductions" id="otherdeduction{{$otherdeduction->id}}2" name="otherdeduction{{$otherdeduction->id}}"checked
                                            @if($checkifreleased == 1 || $otherdeduction->forcefull == 1) disabled @endif>
                                            <label for="otherdeduction{{$otherdeduction->id}}2">
                                                    {{(float)$otherdeduction->amount}} 
                                            </label>
                                            </div>
                                        </small>
                                        
                                        @php
                                        $td+=(float)$otherdeduction->amount;
                                    @endphp
                                        @endif
                                        {{-- <small>: {{$standarddeduction->eesamount}} </small> --}}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-8">
                                <div class="row"  data-id="{{$otherdeduction->id}}" data-desc="{{$otherdeduction->description}}" data-paymentoption="1">
                                    <div class="col-md-6">
                                        <small>: Half </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small>: 
                                            <div class="icheck-primary d-inline">
                                            <input type="radio" value="{{$otherdeduction->amount}}" class="otherdeductions" id="otherdeduction{{$otherdeduction->id}}1" name="otherdeduction{{$otherdeduction->id}}" checked @if($checkifreleased == 1 || $otherdeduction->forcefull == 1) disabled @endif>
                                            <label for="otherdeduction{{$otherdeduction->id}}1">
                                                    {{$otherdeduction->amount}} 
                                            </label>
                                            </div>
                                        </small>
                                            
                                        @php
                                            $td+=$otherdeduction->amount;
                                        @endphp
                                    </div>
                                </div>
                                <div class="row mt-2" data-id="{{$otherdeduction->id}}" data-desc="{{$otherdeduction->description}}" data-paymentoption="2">
                                    <div class="col-md-6">
                                        <small>: Full </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small>: 
                                            <div class="icheck-primary d-inline">
                                            <input type="radio" value="{{$otherdeduction->amount*2}}" class="otherdeductions" id="otherdeduction{{$otherdeduction->id}}2" name="otherdeduction{{$otherdeduction->id}}"
                                            @if($checkifreleased == 1 || $otherdeduction->forcefull == 1) disabled @endif>
                                            <label for="otherdeduction{{$otherdeduction->id}}2">
                                                    {{$otherdeduction->amount*2}} 
                                            </label>
                                            </div>
                                        </small>
                                        {{-- <small>: {{$standarddeduction->eesamount}} </small> --}}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        {{-- </div> --}}
    @endif
    @if(count($standardallowances)>0)
        <hr class="m-1"/>        
        {{-- <div style="border: 2px solid #ddd"> --}}
            @if(count($standardallowances[0]->standardallowances) > 0)
                <div class="row text-muted text-uppercase mt-2">
                    <div class="col-md-12">
                        <small><strong>Standard Allowances</strong></small>
                    </div>
                </div>
                @foreach($standardallowances[0]->standardallowances as $standardallowance)
                    <div class="row mt-2 text-muted text-uppercase">
                        <div class="col-md-4">
                            <small>{{$standardallowance->description}}</small>
                        </div>
                        @if($standardallowance->saved == 1)
                            @php
                            $saved+=1;
                            @endphp
                        @endif
                        @if($standardallowance->paymentoption == 2)
                            <div class="col-md-8">
                                <div class="row"  data-id="{{$standardallowance->id}}" data-desc="{{$standardallowance->description}}" data-paymentoption="1">
                                    <div class="col-md-6">
                                        <small>: Half </small>
                                    </div>
                                    <div class="col-md-6">
                                        
                                        @if($checkifreleased == 1 && $standardallowance->forcefull == 1 || $standardallowances[0]->payrollstatus == 1)
                                        @else
                                        <small>: 
                                            <div class="icheck-primary d-inline">
                                            <input type="radio" value="{{$standardallowance->eesamount/2}}" class="standardallowances" id="standardallowance{{$standardallowance->id}}1" name="standardallowance{{$standardallowance->id}}" @if($checkifreleased == 1 || $standardallowance->forcefull == 1) disabled @endif>
                                            <label for="standardallowance{{$standardallowance->id}}1">
                                                    {{$standardallowance->eesamount/2}} 
                                            </label>
                                            </div>
                                        </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-2" data-id="{{$standardallowance->id}}" data-desc="{{$standardallowance->description}}" data-paymentoption="2">
                                    <div class="col-md-6">
                                        <small>: Full </small>
                                    </div>
                                    <div class="col-md-6">
                                        @if($checkifreleased == 1 && $standardallowance->forcefull == 1 || $standardallowances[0]->payrollstatus == 1)
                                        <small>Paid for this month</small>
                                        @else
                                        <small>: 
                                            <div class="icheck-primary d-inline">
                                            <input type="radio" value="{{$standardallowance->eesamount}}" class="standardallowances" id="standardallowance{{$standardallowance->id}}2" name="standardallowance{{$standardallowance->id}}"checked
                                            @if($checkifreleased == 1 || $standardallowance->forcefull == 1) disabled @endif>
                                            <label for="standardallowance{{$standardallowance->id}}2">
                                                    {{$standardallowance->eesamount}} 
                                            </label>
                                            </div>
                                        </small>
                                        
                                        @php
                                            $te+=$standardallowance->eesamount;
                                        @endphp
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-8">
                                <div class="row"  data-id="{{$standardallowance->id}}" data-desc="{{$standardallowance->description}}" data-paymentoption="1">
                                    <div class="col-md-6">
                                        <small>: Half </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small>: 
                                            <div class="icheck-primary d-inline">
                                            <input type="radio" value="{{$standardallowance->eesamount}}" class="standardallowances" id="standardallowance{{$standardallowance->id}}1" name="standardallowance{{$standardallowance->id}}" checked @if($checkifreleased == 1 || $standardallowance->forcefull == 1) disabled @endif>
                                            <label for="standardallowance{{$standardallowance->id}}1">
                                                    {{$standardallowance->eesamount}} 
                                            </label>
                                            </div>
                                        </small>
                                            
                                        @php
                                            $te+=$standardallowance->eesamount;
                                        @endphp
                                    </div>
                                </div>
                                <div class="row mt-2" data-id="{{$standardallowance->id}}" data-desc="{{$standardallowance->description}}" data-paymentoption="2">
                                    <div class="col-md-6">
                                        <small>: Full </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small>: 
                                            <div class="icheck-primary d-inline">
                                            <input type="radio" value="{{$standardallowance->eesamount*2}}" class="standardallowances" id="standardallowance{{$standardallowance->id}}2" name="standardallowance{{$standardallowance->id}}"
                                            @if($checkifreleased == 1 || $standardallowance->forcefull == 1) disabled @endif>
                                            <label for="standardallowance{{$standardallowance->id}}2">
                                                    {{$standardallowance->eesamount*2}} 
                                            </label>
                                            </div>
                                        </small>
                                        {{-- <small>: {{$standarddeduction->eesamount}} </small> --}}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        {{-- </div> --}}
    @endif
    @if(count($otherallowances)>0)
    <hr class="m-1"/>        
        {{-- <div style="border: 2px solid #ddd"> --}}
            @if(count($otherallowances[0]->otherallowances) > 0)
                <div class="row text-muted text-uppercase mt-2">
                    <div class="col-md-12">
                        <small><strong>Other Allowances</strong></small>
                    </div>
                </div>
                @foreach($otherallowances[0]->otherallowances as $otherallowance)
                    <div class="row mt-2 text-muted text-uppercase">
                        <div class="col-md-4">
                            <small>{{$otherallowance->description}}</small>
                        </div>
                        @if($otherallowance->saved == 1)
                            @php
                            $saved+=1;
                            @endphp
                        @endif
                        @if($otherallowance->paymentoption == 2)
                            <div class="col-md-8">
                                <div class="row"  data-id="{{$otherallowance->id}}" data-desc="{{$otherallowance->description}}" data-paymentoption="1">
                                    <div class="col-md-6">
                                        <small>: Half </small>
                                    </div>
                                    <div class="col-md-6">
                                        @if($checkifreleased == 1 && $otherallowance->forcefull == 1 || $otherallowances[0]->payrollstatus == 1)
                                        @else
                                        <small>: 
                                            <div class="icheck-primary d-inline">
                                            <input type="radio" value="{{number_format($otherallowance->amount/2,2)}}" class="otherallowances" id="otherallowance{{$otherallowance->id}}1" name="otherallowance{{$otherallowance->id}}" @if($checkifreleased == 1 || $otherallowance->forcefull == 1) disabled @endif>
                                            <label for="otherallowance{{$otherallowance->id}}1">
                                                {{number_format($otherallowance->amount/2,2)}}
                                            </label>
                                            </div>
                                        </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-2" data-id="{{$otherallowance->id}}" data-desc="{{$otherallowance->description}}" data-paymentoption="2">
                                    <div class="col-md-6">
                                        <small>: Full </small>
                                    </div>
                                    <div class="col-md-6">
                                        @if($checkifreleased == 1 && $otherallowance->forcefull == 1 || $otherallowances[0]->payrollstatus == 1)
                                        <small>Paid for this month</small>
                                        @else
                                        <small>: 
                                            <div class="icheck-primary d-inline">
                                            <input type="radio" value="{{number_format($otherallowance->amount,2)}}" class="otherallowances" id="otherallowance{{$otherallowance->id}}2" name="otherallowance{{$otherallowance->id}}"checked
                                            @if($checkifreleased == 1 || $otherallowance->forcefull == 1) disabled @endif>
                                            <label for="otherallowance{{$otherallowance->id}}2">
                                                {{number_format($otherallowance->amount,2)}}
                                            </label>
                                            </div>
                                        </small>
                                        
                                        @php
                                            $te+=$otherallowance->amount;
                                        @endphp
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-8">
                                <div class="row"  data-id="{{$otherallowance->id}}" data-desc="{{$otherallowance->description}}" data-paymentoption="1">
                                    <div class="col-md-6">
                                        <small>: Half </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small>: 
                                            <div class="icheck-primary d-inline">
                                            <input type="radio" value="{{$otherallowance->amount}}" class="otherallowances" id="otherallowance{{$otherallowance->id}}1" name="otherallowance{{$otherallowance->id}}" checked @if($checkifreleased == 1 || $otherallowance->forcefull == 1) disabled @endif>
                                            <label for="otherallowance{{$otherallowance->id}}1">
                                                    {{$otherallowance->amount}} 
                                            </label>
                                            </div>
                                        </small>
                                            
                                        @php
                                            $te+=$otherallowance->amount;
                                        @endphp
                                    </div>
                                </div>
                                <div class="row mt-2" data-id="{{$otherallowance->id}}" data-desc="{{$otherallowance->description}}" data-paymentoption="2">
                                    <div class="col-md-6">
                                        <small>: Full </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small>: 
                                            <div class="icheck-primary d-inline">
                                            <input type="radio" value="{{$otherallowance->amount*2}}" class="otherallowances" id="otherallowance{{$otherallowance->id}}2" name="otherallowance{{$otherallowance->id}}"
                                            @if($checkifreleased == 1 || $otherallowance->forcefull == 1) disabled @endif>
                                            <label for="otherallowance{{$otherallowance->id}}2">
                                                    {{$otherallowance->amount*2}} 
                                            </label>
                                            </div>
                                        </small>
                                        {{-- <small>: {{$standardallowance->eesamount}} </small> --}}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        {{-- </div> --}}
    @endif
    @if($checkifreleased == 0)
    <hr class="m-1"/>        
        <div class="row mt-2">
            <div class="col-md-12">
                @if($overrideconfigured>0)
                    <button type="button" class="btn btn-sm btn-primary btn-block" id="saveconfig">Save Configuration</button>
                @endif
            </div>
        </div>
    @endif
    <div class="row mt-2">
        <div class="col-md-8">
            <small><strong>Total earnings</strong> </small>
        </div>
        <div class="col-md-4">
            : {{number_format($te,2)}}
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-8">
            <small><strong>Total deductions</strong> </small>
        </div>
        <div class="col-md-4">
            : {{number_format($td,2)}}
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-8">
            <strong>Net Salary</strong>
        </div>
        <div class="col-md-4">
            :  {{number_format($te-$td,2)}}
        </div>
    </div>
    <script>
        function getdetails(employeeid){
            $.ajax({
                url: '/hr/payroll/getsalarydetails',
                type: 'get',
                data: {
                    employeeid          :   employeeid
                },
                success: function(data){
                    $('#salarydetailscontainer').empty()
                    $('#salarydetailscontainer').append(data)
                }
            })
        }
        $('#saveconfig').on('click', function(){
            var standarddeductionids            = [];
            var standarddeductiondescs          = [];
            var standarddeductionamounts        = [];
            var standarddeductionpaymentoptions = [];
            // var standarddeductions
            $('.standarddeductions:checked').each(function(){
                var id              = $(this).closest('.row').attr('data-id');
                var description     = $(this).closest('.row').attr('data-desc');
                var amount          = $(this).val();
                var paymentoption   = $(this).closest('.row').attr('data-paymentoption');
                standarddeductionids.push(id)
                standarddeductiondescs.push(description)
                standarddeductionamounts.push(amount)
                standarddeductionpaymentoptions.push(paymentoption)
            })
            var otherdeductionids            = [];
            var otherdeductiondescs          = [];
            var otherdeductionamounts        = [];
            var otherdeductionpaymentoptions = [];
            $('.otherdeductions:checked').each(function(){
                var id              = $(this).closest('.row').attr('data-id');
                var description     = $(this).closest('.row').attr('data-desc');
                var amount          = $(this).val();
                var paymentoption   = $(this).closest('.row').attr('data-paymentoption');
                otherdeductionids.push(id)
                otherdeductiondescs.push(description)
                otherdeductionamounts.push(amount)
                otherdeductionpaymentoptions.push(paymentoption)
            })
            var standardallowanceids            = [];
            var standardallowancedescs          = [];
            var standardallowanceamounts        = [];
            var standardallowancepaymentoptions = [];
            // var standardallowances
            $('.standardallowances:checked').each(function(){
                var id              = $(this).closest('.row').attr('data-id');
                var description     = $(this).closest('.row').attr('data-desc');
                var amount          = $(this).val();
                var paymentoption   = $(this).closest('.row').attr('data-paymentoption');
                standardallowanceids.push(id)
                standardallowancedescs.push(description)
                standardallowanceamounts.push(amount)
                standardallowancepaymentoptions.push(paymentoption)
            })
            var otherallowanceids            = [];
            var otherallowancedescs          = [];
            var otherallowanceamounts        = [];
            var otherallowancepaymentoptions = [];
            $('.otherallowances:checked').each(function(){
                var id              = $(this).closest('.row').attr('data-id');
                var description     = $(this).closest('.row').attr('data-desc');
                var amount          = $(this).val();
                var paymentoption   = $(this).closest('.row').attr('data-paymentoption');
                otherallowanceids.push(id)
                otherallowancedescs.push(description)
                otherallowanceamounts.push(amount)
                otherallowancepaymentoptions.push(paymentoption)
            })
            $.ajax({
                url: '/hr/payroll/saveconfiguration',
                type: 'GET',
                data:{
                    employeeid                          : '{{$employeeid}}',
                    standarddeductionids                : standarddeductionids,
                    standarddeductiondescs              : standarddeductiondescs,
                    standarddeductionamounts            : standarddeductionamounts,
                    standarddeductionpaymentoptions     : standarddeductionpaymentoptions,
                    otherdeductionids                   : otherdeductionids,
                    otherdeductiondescs                 : otherdeductiondescs,
                    otherdeductionamounts               : otherdeductionamounts,
                    otherdeductionpaymentoptions        : otherdeductionpaymentoptions,
                    standardallowanceids                : standardallowanceids,
                    standardallowancedescs              : standardallowancedescs,
                    standardallowanceamounts            : standardallowanceamounts,
                    standardallowancepaymentoptions     : standardallowancepaymentoptions,
                    otherallowanceids                   : otherallowanceids,
                    otherallowancedescs                 : otherallowancedescs,
                    otherallowanceamounts               : otherallowanceamounts,
                    otherallowancepaymentoptions        : otherallowancepaymentoptions,
                }, success:function(data)
                {
                    var employeeid = '{{$employeeid}}';
                    getdetails(employeeid)
                    toastr.success('Updated successfully!','Configuration')
                }
            })
        })
    </script>
@else
<div class="row text-muted text-uppercase">
    <div class="col-md-12">
        <em>Please configure the employee's basic salary information</em>
    </div>
</div>
@endif
