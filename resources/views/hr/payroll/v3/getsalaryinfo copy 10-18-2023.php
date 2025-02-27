<style>
  .listholiday i {
    /* ... other styles ... */
    transition: transform 0.6s ease-in-out;
  }
  /* Styling for the "List Here" badge */
  .listholiday, .listholidayclose {
    padding: 4px !important;
    transition: all 0.3s;
  }
  .listholiday:hover{
    transform: translate(-2px);
  }
  .listholidayclose:hover {
    transform: translate(-2px);
  }
  /* Define animation classes for opening and closing */
  .listherediv {
    opacity: 0;
    max-height: 0;
    overflow:hidden;
    /* transition: max-height 0.6s ease-in-out, opacity 0.6s ease-in-out; */
  }

  .listherediv.active {
    opacity: 1;
    max-height: 1000px;
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
</style>
@if($basicsalaryinfo)
@php
define('MINUTES_PER_HOUR', 60);
$basicsalaryamount = 0;
if($basicsalaryinfo->amount > 0 )
{
  if(strtolower($basicsalaryinfo->ratetype) == 'daily')
  {
    $totalearnings = (float)number_format($basicsalaryinfo->amount + collect($leaves)->sum('amount'),2);
    $basicsalaryamount = $basicsalaryinfo->amount;
  }else{
    $totalearnings = (float)number_format(($basicsalaryinfo->amount/2) + collect($leaves)->sum('amount'),2);
    $basicsalaryamount = number_format($basicsalaryinfo->amount/2,2);
  }
}
@endphp
@php
    $totalearnings = 0;
    $totalholidaypay = 0;
    $totaldeductions = 0;
    $totalleaveamount = 0;
    $totalHolidayPay = 0;
@endphp
{{-- MODAL --}}
<div class="modal fade" tabindex="-1" role="dialog" id="modal_allreleasedpayroll">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      {{-- <div class="modal-header">
        <h4 class="modal-title"><span id="modal_desc">All Payroll Released</span> </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> --}}
      <div class="modal-body">
          {{-- <div class="row">
            <div class="col-md-3">
              <select class="form-control select2" id="select-payrolldatesreleased"></select>
            </div>
            <div class="col-md-9"></div>
          </div> --}}
          <div class="row">
            <div class="col-md-12">
              <table width="100%" class="table table-head-fixed" id="listreleasedpayroll_datatable"  style="table-layout: fixed; font-size: 16px">
                <thead>
                    <tr>
                        <th width="40%" class="p-1" style="vertical-align: middle;">Payroll Dates</th>
                        <th width="20%" class="" style="vertical-align: middle;">Total Earning</th>
                        <th width="20%" class="" style="vertical-align: middle;">Total Deduction</th>
                        <th width="10%" class="" style="vertical-align: middle;">Net Pay</th>
                        <th width="10%" class="p-1 text-center" style="vertical-align: middle;">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            </div>
          </div>
          <div class="row">
            {{-- <div class="col-md-12" id="viewdeductionledger"></div> --}}
            <div class="col-md-12 justify-content-between" style="display: flex;">
              <button type="button" class="btn btn-primary btn-sm" id="btn_savestandardallowance" style="visibility: hidden"><i class="fas fa-plus"></i> Add</button>
              <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
{{-- END MODAL --}}
{{-- MODAL --}}
<div class="modal fade" tabindex="-1" role="dialog" id="modal_payrolldetails">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      {{-- <div class="modal-header">
        <h4 class="modal-title"><span id="modal_desc">All Payroll Released</span> </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> --}}
      <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <table class="table table-bordered" style="font-size: 10.5px; width: 100%; table-layout: fixed;" id="modal_payrolldetailsdatatable">
                <thead class="text-center">
                    <tr>
                      <th style="text-align: center; vertical-align: middle;">Day</th>
                      <th style="text-align: center; vertical-align: middle;">AM IN</th>
                      <th style="text-align: center; vertical-align: middle;">AM OUT</th>
                      <th style="text-align: center; vertical-align: middle;">PM IN</th>
                      <th style="text-align: center; vertical-align: middle;">PM OUT</th>
                      <th style="text-align: center; vertical-align: middle;">Late <br/>(mins)</th>
                      <th style="text-align: center; vertical-align: middle;">Undertime<br/>(mins)</th>
                      <th style="text-align: center; vertical-align: middle;">TWH</th>
                      <th style="text-align: center; vertical-align: middle;">Late<br>Penalty</th>
                      <th style="text-align: center; vertical-align: middle;">Undertime<br>Penalty</th>
                    </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6">
              <span><b>Earnings</b></span>
              <div id="additionaldetailsearnings"></div>
            </div>
            <div class="col-md-6">
              <span><b>Deductions</b></span>
              <div id="additionaldetailsdeductions"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 justify-content-between" style="display: flex;">
              <button type="button" class="btn btn-primary btn-sm" id="btn_savestandardallowance" style="visibility: hidden"><i class="fas fa-plus"></i> Add</button>
              <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </div>
          </div>
      </div>
      
    </div>
  </div>
</div>
{{-- END MODAL --}}
<div class="card shadow" style="border: none !important; border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
  <div class="card-header pb-0">
      <div class="row">
        <div class="col-md-4 text-center" >
          
          <div class="table-avatar">
              @php
                      $number = rand(1,3);
                      if(strtoupper($employeeinfo->gender) == 'FEMALE'){
                          $avatar = 'avatar/T(F) '.$number.'.png';
                      }
                      else{
                          $avatar = 'avatar/T(M) '.$number.'.png';
                      }
                  @endphp
                <a href="#" class="avatar">
                      <img src="{{ asset($employeeinfo->picurl) }}" alt="" onerror="this.onerror = null, this.src='{{asset($avatar)}}'" style="width: 100px;"/>
              </a>
             
          </div>
           <span class="info-box-number">{{$employeeinfo->tid}}</span>
           <br/>
           <span class="info-box-number">{{$employeeinfo->utype}}</span>
        </div>
        <div class="col-md-8">
          <div class="row">
            <div class="col-md-7">
              <h5><a href="/hr/employees/profile/index?employeeid={{$employeeid}}" target="_blank">{{$employeeinfo->lastname}}, {{$employeeinfo->firstname}}</a></h5>
            </div>
            <div class="col-md-5 text-right">
              <button type="button" class="btn btn-sm btn-outline-info" id="all_releasedpayslip">All Released Payslip</button>
              <button type="button" class="btn btn-sm btn-outline-info" id="btn-refresh"><i class="fa fa-sync"></i> Refresh</button>
            </div>
            <div class="col-md-12">
              <label>Working days</label>
              <div class="form-group mb-0" style="display: -webkit-box;">
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
            <div class="col-md-12">
              @if($released == 1)
              <span>{{$payrollinfo->basicsalarytype}} : {{number_format($payrollinfo->monthlysalary,2,'.',',')}}</span>@if(strtolower($basicsalaryinfo->ratetype) != 'daily') / Daily rate: {{number_format($basicsalaryinfo->amountperday,2)}} @endif/ Hourly rate: {{number_format($basicsalaryinfo->amountperhour,2)}}
              @else
              <label><span id="salarytype" data-dailyrate="{{$basicsalaryinfo->amountperday}}">{{$basicsalaryinfo->salarytype}}</span> : @if(strtolower($basicsalaryinfo->ratetype) != 'daily'){{number_format($basicsalaryinfo->amount,2,'.',',')}}@else{{number_format($basicsalaryinfo->amount/count($attendance),2,'.',',')}} @endif<span id="monthlysalary" hidden>{{$basicsalaryinfo->amount}}</span></label>@if(strtolower($basicsalaryinfo->ratetype) != 'daily') / Daily rate: {{number_format($basicsalaryinfo->amountperday,2)}} @endif / Hourly rate: {{number_format($basicsalaryinfo->amountperhour,2)}}
              @endif
            </div>
            <div class="col-md-12">
              
              @if($released == 1)
                <table class="table table-bordered" style="font-size: 10.5px; width: 100%; table-layout: fixed;">
                  <thead class="text-center">
                      <tr>
                          <th>Present</th>
                          <th>Absent</th>
                          <th>Late</th>
                          <th>Total Hours Worked</th>
                          <th hidden>Amount Per Day</th>
                          {{-- <th>Attendance</th> --}}
                      </tr>
                  </thead>
                  <tr>
                    <td class="text-center">{{$payrollinfo->presentdays}}</td>
                    <td class="text-center">{{$payrollinfo->absentdays}}</td>
                    <td class="text-center">{{$payrollinfo->lateminutes}} min(s)</td>
                    <td class="text-center">{{$payrollinfo->totalhoursworked}}</td>
                    <td hidden></td>
                    {{-- <td></td> --}}
                  </tr>
                </table>
              @else
                <table class="table table-bordered" style="font-size: 10.5px; width: 100%;">
                    <thead class="text-center">
                        <tr>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Late</th>
                            <th>Undertime</th>
                            <th>Total Hours Worked</th>
                            <th hidden>Amount Per Day</th>
                            <th>Attendance</th>
                        </tr>
                    </thead>
                    <tr>
                        <td id="dayspresent" class="text-center"><strong>{{collect($attendance)->where('totalworkinghours','>',0)->count()}}</strong></td>
                        <td id="daysabsent" class="text-center"><strong>{{collect($attendance)->where('totalworkinghours',0)->count()}}</strong></td>
                        <td id="lateminutes" data-value="{{collect($attendance)->where('totalworkinghours','>',0)->sum('latehours')*60}}" class="text-center">
                          @php
                            $total_minutes = collect($attendance)->where('totalworkinghours','>',0)->sum('latehours')*60;
                            $hours = intval($total_minutes / MINUTES_PER_HOUR);  // integer division
                            $mins = $total_minutes % MINUTES_PER_HOUR;           // modulo
                            $latedays = array();
                            $amountperday = 0;
                          @endphp
						  @php
							$latedays =  collect($attendance)->where('totalworkinghours','>',0)->where('latehours','>',0);   
						  @endphp
                          @if($hours>0 || $mins>0)
						  {{--<strong>{{$total_minutes}} min(s)</strong>--}}
							<strong>{{number_format(collect($latedays)->sum('latehours') * 60,2)}} min(s)</strong>
                          {{-- <strong>{{$hours}}</strong>hr(s) & <strong>{{$mins}}</strong>min(s) --}}
                          @else
                          {{--<strong>{{$total_minutes}} min(s)</strong>--}}
							<strong>{{number_format(collect($latedays)->sum('latehours') * 60,2)}} min(s)</strong>
                          {{-- <strong>{{$hours}}</strong>hr(s) & <strong>{{$mins}}</strong>min(s) --}}
                          @endif
                        </td>
                        <td id="undertimeminutes" data-value="{{collect($attendance)->where('totalworkinghours','>',0)->sum('undertimehours')*60}}" class="text-center"><strong>{{collect($attendance)->where('totalworkinghours','>',0)->sum('undertimehours')*60}} mins</strong></td>
                        <td id="totalworkedhours" data-value="{{collect($attendance)->where('totalworkinghours','>',0)->sum('totalworkinghoursrender')}}" class="text-center"><strong>{{collect($attendance)->where('totalworkinghours','>',0)->sum('totalworkinghoursrender')}}</strong></td>
                        <th hidden class="text-right" id="amountperday" data-value="@if($basicsalaryinfo->amount > 0 && count($attendance) > 0){{number_format(floor((($basicsalaryinfo->amount/2)/count($attendance))*100)/100,2)}}@endif">@if($basicsalaryinfo->amount > 0 && count($attendance) > 0){{number_format(floor((($basicsalaryinfo->amount/2)/count($attendance))*100)/100,2)}} @php $amountperday=floor((($basicsalaryinfo->amount/2)/count($attendance))*100)/100; @endphp @endif</th>
                        <th>
                          @php
                            // define('MINUTES_PER_HOUR', 60);
                            $total_minutes = collect($attendance)->where('totalworkinghours','>',0)->sum('latehours')*60;
                            $hours = intval($total_minutes / MINUTES_PER_HOUR);  // integer division
                            $mins = $total_minutes % MINUTES_PER_HOUR;           // modulo
                            $latedays = array();
                          @endphp
                          <button type="button" class="btn btn-sm btn-warning p-0 btn-block" data-toggle="modal" data-target="#modal-view-late-history" style="font-size: 11px;">View</button>
                          <div class="modal fade" id="modal-view-late-history">
                            <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title">Attendance Details  @if($basicsalaryinfo) @if ($basicsalaryinfo->flexitime == 1) <span class="badge badge-success">Flexi Time</span> @endif @endif</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body" style="font-size: 12px !important;">
                                  @php
                                    $latedays =  collect($attendance)->where('totalworkinghours','>',0)->where('latehours','>',0); 
                                    // dd($latedays);
                                  @endphp
								  
									<table class="table table-striped m-0">
                                      {{-- <tr>
                                        <th colspan="7">Late Computation Details</th>
                                      </tr>
                                      <tr>
                                        <td colspan="2" style="width: 30% !important;">Late Duration   : {{$latecomputationdetails->lateduration}} @if($latecomputationdetails->durationtype == 1)mins @else hrs @endif</td>
                                        <td colspan="2"></td>
                                        <td colspan="2">Deduction Amount:</td>
                                        <td class="text-right text-bold" style="width: 20% !important;"><span id="lateminutes" hidden>{{collect($attendance)->where('lateminutes','>',0)->sum('lateminutes')}}</span><span id="tardinessamount">{{collect($attendance)->where('amountdeduct','>',0)->sum('amountdeduct')}}</span></td>
                                      </tr>
                                      <tr>
                                        <td colspan="7">&nbsp;</td>
                                      </tr> --}}
                                      {{-- @php
                                          dd($attendance);
                                      @endphp --}}
                                      <tr>
                                        <th style="text-align: center; vertical-align: middle;">Day</th>
                                        <th style="text-align: center; vertical-align: middle;">AM IN</th>
                                        <th style="text-align: center; vertical-align: middle;">AM OUT</th>
                                        <th style="text-align: center; vertical-align: middle;">PM IN</th>
                                        <th style="text-align: center; vertical-align: middle;">PM OUT</th>
                                        <th style="text-align: center; vertical-align: middle;">Late <br/>(mins)</th>
                                        <th style="text-align: center; vertical-align: middle;">Undertime<br/>(mins)</th>
                                        <th style="text-align: center; vertical-align: middle;">TWH</th>
                                        <th style="text-align: center; vertical-align: middle;">Late<br>Penalty</th>
                                        <th style="text-align: center; vertical-align: middle;">Undertime<br>Penalty</th>
                                        {{-- <th style="text-align: center; vertical-align: middle;">Penalty<br/>Amount</th> --}}
                                        {{-- @php
                                            dd(collect($attendance));
                                        @endphp --}}
                                      </tr>
                                      {{-- @php
                                          dd($attendance);
                                      @endphp --}}
                                      @foreach($attendance as $eachattendance)
                                        @php
                                        // $total_minutes = $lateday->latehours*60;
                                        // $hours = intval($total_minutes / MINUTES_PER_HOUR);  // integer division
                                        // $mins = $total_minutes % MINUTES_PER_HOUR;           // modulo
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{$eachattendance->daystring}}<br/>{{$eachattendance->day}}</td>
                                            <td class="text-center" style="vertical-align: middle;">
                                              @if($eachattendance->amtimein != '00:00:00' && $eachattendance->amtimein != null)
                                              {{date('h:i', strtotime($eachattendance->amtimein))}}
                                              @endif
                                            </td>
                                            <td class="text-center" style="vertical-align: middle;">
                                              @if($eachattendance->amtimeout != '00:00:00' && $eachattendance->amtimeout != null)
                                              {{date('h:i', strtotime($eachattendance->amtimeout))}}
                                              @endif
                                            </td>
                                            <td class="text-center" style="vertical-align: middle;">
                                              @if($eachattendance->pmtimein != '00:00:00' && $eachattendance->pmtimein != null)
                                              {{date('h:i', strtotime($eachattendance->pmtimein))}}
                                              @endif
                                            </td>
                                            <td class="text-center" style="vertical-align: middle;">
                                              @if($eachattendance->pmtimeout != '00:00:00' && $eachattendance->pmtimeout != null)
                                              {{date('h:i', strtotime($eachattendance->pmtimeout))}}
                                              @endif
                                            </td>
                                            <td class="text-center" style="vertical-align: middle;">

                                              @if ($basicsalaryinfo->flexitime == 1)
                                                @if($eachattendance->latehours > 0)
                                                  {{-- {{number_format($eachattendance->flexihoursundertime * 60,0)}} mins --}}
                                                  0 min
                                                @else
                                                  0 min
                                                @endif
                                              @else
                                                @if($eachattendance->latehours > 0)
                                                  {{number_format($eachattendance->latehours * 60,0)}} mins
                                                @else
                                                  0 min
                                                @endif
                                              @endif


                                              {{-- @if($eachattendance->latehours > 0)
                                                {{number_format($eachattendance->latehours * 60,0)}} mins
                                              @else
                                                0
                                              @endif --}}
                                                
                                            </td>
                                            <td class="text-center" style="vertical-align: middle;">
                                              @if ($basicsalaryinfo->flexitime == 1)
                                                @if($eachattendance->latehours > 0)
                                                  {{number_format($eachattendance->flexihoursundertime * 60,0)}} mins
                                                @else
                                                  0 min
                                                @endif
                                              @else
                                                {{($eachattendance->undertimehours*60)}} mins
                                              @endif
                                            </td>
                                            {{-- <td class="text-center" style="vertical-align: middle;">{{($eachattendance->undertimehours*60)}} mins</td> --}}
                                            {{--<td class="text-center" style="vertical-align: middle;">{{($eachattendance->totalworkinghours)}}&nbsp;hours</td>--}}
                                            <td class="text-center" style="vertical-align: middle;">
                                              @if ($basicsalaryinfo->flexitime == 1)
                                                {{ floor($eachattendance->flexihours) }} hours
                                                {{ number_format(($eachattendance->flexihours - floor($eachattendance->flexihours)) * 60, 0) }} minutes
                                              @else
                                                {{ floor($eachattendance->totalworkinghoursrender) }} hours
                                                {{ number_format(($eachattendance->totalworkinghoursrender - floor($eachattendance->totalworkinghoursrender)) * 60, 0) }} minutes
                                              @endif
                                            </td>
                                            <td class="text-center" style="vertical-align: middle;">
                                              
                                              @php
                                                // if($basicsalaryinfo->attendancebased == 1){
                                                //   if ($tardinessbaseonsalary) {
                                                //     if ($basicsalaryinfo->flexitime == 1) {
                                                //       $latetime = $eachattendance->flexihoursundertime * 60;

                                                //       $amountperhour = $basicsalaryinfo->amountperhour / 60;
                                                //       $min = number_format($amountperhour - .005, 2) * $latetime;
                                                //     } else {
                                                //       $latetime = $eachattendance->lateminutes;
                                                //       $amountperhour = $basicsalaryinfo->amountperhour / 60;
                                                //       $min = number_format($amountperhour - .005, 2) * $latetime;
                                                //     }
                                                    
                                                //   } else {
                                                //     if ($basicsalaryinfo->flexitime == 1) {
                                                //       $min = $eachattendance->latedeductionamount;
                                                //     } else {
                                                //       $min = $eachattendance->latedeductionamount;
                                                //     }
                                                //   }
                                                // } else {
                                                //   $min = 0;
                                                // }
                                                if ($basicsalaryinfo->flexitime == 1) {
                                                  $min = 0;
                                                } else {
                                                  if($basicsalaryinfo->attendancebased == 1){
                                                      if ($tardinessbaseonsalary) {
                                                        if ($basicsalaryinfo->flexitime == 1) {
                                                          $latetime = $eachattendance->flexihoursundertime * 60;

                                                          $amountperhour = $basicsalaryinfo->amountperhour / 60;
                                                          $min = number_format($amountperhour - .005, 2) * $latetime;
                                                        } else {
                                                          $latetime = $eachattendance->lateminutes;
                                                          $amountperhour = $basicsalaryinfo->amountperhour / 60;
                                                          $min = number_format($amountperhour - .005, 2) * $latetime;
                                                        }
                                                        
                                                      } else {
                                                        if ($basicsalaryinfo->flexitime == 1) {
                                                          $min = $eachattendance->latedeductionamount;
                                                        } else {
                                                          $min = $eachattendance->latedeductionamount;
                                                        }
                                                      }
                                                    } else {
                                                      $min = 0;
                                                    }
                                                }
                                              @endphp
                                              {{$min}}
                                            </td>
                                              
                                            
                                            <td  class="text-center" style="vertical-align: middle;">
                                              @if ($basicsalaryinfo->flexitime == 1)
                                                @php
                                                    if($basicsalaryinfo->attendancebased == 1){
                                                      if ($tardinessbaseonsalary) {
                                                        if ($basicsalaryinfo->flexitime == 1) {
                                                          $latetime = $eachattendance->flexihoursundertime * 60;

                                                          $amountperhour = $basicsalaryinfo->amountperhour / 60;
                                                          $min = number_format($amountperhour - .005, 2) * $latetime;
                                                        } else {
                                                          $latetime = $eachattendance->lateminutes;
                                                          $amountperhour = $basicsalaryinfo->amountperhour / 60;
                                                          $min = number_format($amountperhour - .005, 2) * $latetime;
                                                        }
                                                        
                                                      } else {
                                                        if ($basicsalaryinfo->flexitime == 1) {
                                                          $min = $eachattendance->latedeductionamount;
                                                        } else {
                                                          $min = $eachattendance->latedeductionamount;
                                                        }
                                                      }
                                                    } else {
                                                      $min = 0;
                                                    }
                                                @endphp
                                              @else
                                                @php
                                                    $undertime = number_format($eachattendance->undertimehours * 60,2);
                                                    $amountperhour = $basicsalaryinfo->amountperhour / 60;
                                                    $min = number_format($amountperhour - .005, 2) * $undertime;
                                                @endphp
                                              @endif

                                              {{$min}}
                                            </td>
                                            {{-- <td  class="text-center" style="vertical-align: middle;">{{($eachattendance->undertimehours*60)}}mins</td> --}}
                                            {{-- <td class="text-right">{{($lateday->amountdeduct)}}&nbsp;&nbsp;&nbsp;</td> --}}
                                        </tr>
                                      @endforeach
                                      <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center" id="countlateminutes" data-value="
                                          @if ($basicsalaryinfo->flexitime == 1) 
                                            {{-- {{collect($latedays)->sum('flexihoursundertime')}} --}}
                                            0
                                          @else
                                            {{collect($latedays)->sum('lateminutes')}}
                                          @endif
                                          ">
                                          @if ($basicsalaryinfo->flexitime == 1)
                                            {{-- {{number_format(collect($latedays)->sum('flexihoursundertime') * 60,2)}} min(s) --}}
                                            0 min

                                          @else
                                            {{number_format(collect($latedays)->sum('latehours') * 60,2)}} min(s)
                                          @endif
                                        </td>
                                        <td class="text-center" id="countlateminutes" data-value="
                                          @if ($basicsalaryinfo->flexitime == 1) 
                                            {{collect($latedays)->sum('flexihoursundertime')}}
                                          @else
                                            {{collect($latedays)->sum('undertime')}}
                                          @endif
                                          ">
                                          @if ($basicsalaryinfo->flexitime == 1)
                                            {{number_format(collect($latedays)->sum('flexihoursundertime') * 60,2)}} min(s)

                                          @else
                                            {{number_format(collect($latedays)->sum('undertimehours') * 60,2)}} min(s)
                                          @endif
                                        </td>
                                        <td id="totalworkedhours" 
                                          data-value="
                                          @if ($basicsalaryinfo->flexitime == 1)
                                            {{collect($attendance)->where('flexihours','>',0)->sum('flexihours')}}
                                          @else
                                            {{collect($attendance)->where('totalworkinghoursrender','>',0)->sum('totalworkinghoursrender')}}
                                          @endif
                                          " class="text-center">
                                          @if ($basicsalaryinfo->flexitime == 1)
                                              {{ number_format(collect($attendance)->where('flexihours', '>', 0)->sum('flexihours'), 2) }}
                                          @else
                                              {{ collect($attendance)->where('totalworkinghoursrender', '>', 0)->sum('totalworkinghoursrender') }}
                                          @endif
                                        </td>
                                        <td class="text-center">
                                          @if ($basicsalaryinfo->flexitime == 1)
                                          @php
                                                $min = 0;
                                              
                                          @endphp
                                          @else 
                                            @php
                                              if($basicsalaryinfo->attendancebased == 1){

                                                if ($basicsalaryinfo->flexitime == 1) {
                                                  if ($tardinessbaseonsalary) {
                                                    $latetime = number_format(collect($latedays)->sum('flexihoursundertime') * 60,2);
                                                    $amountperhour = $basicsalaryinfo->amountperhour / 60;
                                                    $min = number_format($amountperhour - .005, 2) * $latetime;
                                                  } else {
                                                    $min = collect($latedays)->sum('amountdeduct');
                                                  }
                                                } else {
                                                  if ($tardinessbaseonsalary) {
                                                    $latetime = number_format(collect($latedays)->sum('latehours') * 60,2);
                                                    $amountperhour = $basicsalaryinfo->amountperhour / 60;
                                                    $min = number_format($amountperhour - .005, 2) * $latetime;
                                                  } else {
                                                    if ($employeeinfo->tardinessperdepactivated === 1) {
                                                      $min = collect($latedays)->sum('amountdeduct');
                                                    } else {
                                                      $min = 0;
                                                    }
                                                  }
                                                }
                                                
                                              } else {
                                                $min = 0;
                                              }
                                            @endphp
                                          @endif
                                          {{$min}}
                                          </td>
                                          <td  class="text-center" style="vertical-align: middle;">
                                            @if ($basicsalaryinfo->flexitime == 1)
                                              @php
                                              if($basicsalaryinfo->attendancebased == 1){

                                                if ($basicsalaryinfo->flexitime == 1) {
                                                  if ($tardinessbaseonsalary) {
                                                    $latetime = number_format(collect($latedays)->sum('flexihoursundertime') * 60,2);
                                                    $amountperhour = $basicsalaryinfo->amountperhour / 60;
                                                    $min = number_format($amountperhour - .005, 2) * $latetime;
                                                  } else {
                                                    $min = collect($latedays)->sum('amountdeduct');
                                                  }
                                                } else {
                                                  if ($tardinessbaseonsalary) {
                                                    $latetime = number_format(collect($latedays)->sum('latehours') * 60,2);
                                                    $amountperhour = $basicsalaryinfo->amountperhour / 60;
                                                    $min = number_format($amountperhour - .005, 2) * $latetime;
                                                  } else {
                                                    if ($employeeinfo->tardinessperdepactivated === 1) {
                                                      $min = collect($latedays)->sum('amountdeduct');
                                                    } else {
                                                      $min = 0;
                                                    }
                                                  }
                                                }
                                                
                                              } else {
                                                $min = 0;
                                              }
                                            @endphp
                                            @else
                                              @php

                                                  $undertime = number_format(collect($attendance)->where('totalworkinghours','>',0)->sum('undertimehours') * 60,2);
                                                  $amountperhour = $basicsalaryinfo->amountperhour / 60;
                                                  $min = number_format($amountperhour - .005, 2) * $undertime;


                                              @endphp
                                            @endif
                                            {{$min}}
                                          </td>
                                        {{-- <td class="text-right">{{collect($latedays)->sum('undertimehours')}}&nbsp;&nbsp;&nbsp;</td>
                                        <td class="text-right">{{collect($latedays)->sum('amountdeduct')}}&nbsp;&nbsp;&nbsp;</td> --}}
                                      </tr>
                                    </table>
                                </div>
                                <div class="modal-footer justify-content-between">
                                </div>
                              </div>
                              <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                          </div>
                        </th>
                    </tr>
                </table>
              @endif
            </div>
          </div>
        </div>
      </div>
  </div>
  <div class="card-body p-1">
    <table style="width: 100%; table-layout: fixed;">
      <thead>
        <tr>
          <th style="width: 35%;">Earnings</th>
          <th style="text-align: right;">Amount</th>
          <th style="width: 6%;">&nbsp;</th>
          <th style="width: 35%;">Deductions</th>
          <th style="text-align: right;">Amount</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="2" style="vertical-align: top;">
            <table style="width: 100%; table-layout: fixed; font-size: 14px;">
              <tr>
                <td>BASIC PAY</td>
                @if($released == 1)
                <td class="text-right text-bold text-success">
                  {{number_format($payrollinfo->basicsalaryamount,2,'.',',')}}
                </td>
                @else
                <td class="text-right text-bold text-success" id="td-basicpay-amount" data-amount="{{$basicsalaryamount}}">
                  @if($basicsalaryinfo->amount > 0 )
                    {{$basicsalaryamount}}
                  @endif
                </td>
                @endif
              </tr>              
            </table>
            {{-- @php
                dd($leaves);
            @endphp --}}
            @if(collect($leaves)->where('amount','>','0')->count()>0)
              <table style="width: 100%; table-layout: fixed; font-size: 14px;">
                @foreach(collect($leaves)->where('amount','>','0')->values() as $eachleaf)
                  <tr>
                    <td>{{$eachleaf->leave_type}} <small class="text-bold">{{date('m/d/Y', strtotime($eachleaf->ldate))}}</small></td>
                    {{-- @if($released == 1)
                    <td class="text-right text-bold text-success">
                      {{number_format($payrollinfo->basicsalaryamount,2,'.',',')}}
                    </td>
                    @else --}}
                    <td class="text-right text-bold text-success td-leaves" data-ldateid="{{$eachleaf->ldateid}}" data-amount="{{$eachleaf->amount}}" data-description="{{$eachleaf->leave_type}}"  data-empleaveid="{{$eachleaf->employeeleaveid}}"  data-ldateid="{{$eachleaf->ldateid}}">
                        {{$eachleaf->amount}}
                    </td>
                    {{-- @endif --}}
                  </tr>  
                @endforeach            
              </table>
            @endif
            {{-- {{$empstatus}} --}}
            {{-- @php
                dd($alldaysstandardallowance)
            @endphp --}}
            {{-- @if(count($alldaysstandardallowance)>0)
              <table style="width: 100%; table-layout: fixed; font-size: 14px;">
                @foreach($alldaysstandardallowance as $eachstandarddaysallowance)
                  <tr>
                    <td><small>{{$eachstandarddaysallowance->description}}</small></td>
                    <td class="text-right text-bold text-success" id="td-daysallowance-amount" data-amount="{{$eachstandarddaysallowance->totaldaysallowanceamount}}">{{$eachstandarddaysallowance->totaldaysallowanceamount}}</td>
                  </tr>
                @endforeach
              </table>
            @endif --}}

            @if ($empstatus === 1)
              @if(count($holidays) > 0)
                <table style="width: 100%; table-layout: fixed; font-size: 14px;">
                  <tr>
                    <td>Holiday&nbsp;<span class="text-info listholiday"><i class="fas fa-caret-right"></i></span></td>
                    @if($released == 1)
                    <td class="text-right text-bold text-success">
                      {{number_format($totalPresentHolidaypay,2,'.',',')}}
                    </td>
                    @else
                    <td class="text-right text-bold text-success">
                      @if($totalPresentHolidaypay > 0 )
                        {{$totalPresentHolidaypay}}
                      @endif
                    </td>
                    @endif
                  </tr>              
                </table>
                {{-- {{$employeeinfo->employmentstatus}} --}}
                {{-- <div class="listherediv">
               
                  <table style="width: 100% !important;">
                      @foreach($holidays as $eachholiday)
                          @if($eachholiday->withpay === 1)
                              <tr>
                                  <td style="width: 70%;"><small><span style="padding-left: 18px!important"><i class="fas fa-circle fa-rotate-270 fa-xs"></i> {{$eachholiday->typename}}
                                      @if ($eachholiday->duration > 1)
                                          &nbsp;<span class="text-info" style="text-transform: uppercase;">({{$eachholiday->description}})</span>&nbsp;<span class="text-info" style="text-transform: uppercase;">({{ \Carbon\Carbon::parse($eachholiday->start)->format('Y-m-d')}})</span> - <span class="text-info" style="text-transform: uppercase;">({{ \Carbon\Carbon::parse($eachholiday->end)->format('Y-m-d')}})</span>
                                      @else
                                          &nbsp;<span class="text-info" style="text-transform: uppercase;">({{$eachholiday->description}})</span>&nbsp;<span class="text-info" style="text-transform: uppercase;">({{ \Carbon\Carbon::parse($eachholiday->start)->format('Y-m-d')}})</span>
                                      @endif  
                                  </small></td>
                                  @if($released == 1)
                                      <td style="width: 30%; text-align: right;" class="text-success"><small><b>{{number_format($eachholiday->holidaypay, 2, '.', ',')}}</b></small></td>
                                  @else
                                      <td style="width: 30%; text-align: right;" class="text-success amountholiday" data-typename="{{$eachholiday->typename}}" data-id="{{$eachholiday->id}}" 
                                      data-amount="{{$eachholiday->holidaypay}}"><small><b>{{$eachholiday->holidaypay}}</b></small></td>
                                      @php
                                          $totalHolidayPay += $eachholiday->holidaypay; // Add the holiday pay to the total
                                      @endphp
                                  @endif
                              </tr>
                          @endif
                      @endforeach
                  </table>

                  <!-- Output the total holiday pay -->
                  <p hidden>Total Holiday Pay: <span id="holiday-amount" data-value="{{$totalHolidayPay}}">{{ number_format($totalHolidayPay, 2, '.', ',') }}</span></p>
                </div> --}}
                <div class="listherediv">
                  <table style="width: 100% !important;">
                      @foreach($holidays as $eachholiday)
                          @if($eachholiday['withpay'] === 1)
                              <tr>
                                  <td style="width: 70%;"><small><span style="padding-left: 18px!important"><i class="fas fa-circle fa-rotate-270 fa-xs"></i> {{$eachholiday['typename']}}</span>
                                      @if ($eachholiday['duration'] > 1)
                                          &nbsp;<span class="text-info" style="text-transform: uppercase;">({{$eachholiday['description']}})</span>&nbsp;<span class="text-info" style="text-transform: uppercase;">({{ \Carbon\Carbon::parse($eachholiday['start'])->format('Y-m-d')}})</span> - <span class="text-info" style="text-transform: uppercase;">({{ \Carbon\Carbon::parse($eachholiday['end'])->format('Y-m-d')}})</span>
                                      @else
                                          &nbsp;<span class="text-info" style="text-transform: uppercase;">({{$eachholiday['description']}})</span>&nbsp;<span class="text-info" style="text-transform: uppercase;">({{ \Carbon\Carbon::parse($eachholiday['start'])->format('Y-m-d')}})</span>
                                      @endif  
                                  </small></td>
                                  @if($released == 1)
                                      <td style="width: 30%; text-align: right;" class="text-success"><small><b>{{number_format($eachholiday['holidaypay'], 2, '.', ',')}}</b></small></td>
                                  @else
                                      <td style="width: 30%; text-align: right;" class="text-success amountholiday" data-typename="{{$eachholiday['typename']}}" data-id="{{$eachholiday['id']}}" 
                                      data-amount="{{$eachholiday['holidaypay']}}"><small><b>{{$eachholiday['holidaypay']}}</b></small></td>
                                      @php
                                          $totalHolidayPay += $eachholiday['holidaypay']; // Add the holiday pay to the total
                                      @endphp
                                  @endif
                              </tr>
                          @endif
                      @endforeach
                  </table>

                  <!-- Output the total holiday pay -->
                  <p hidden>Total Holiday Pay: <span id="holiday-amount" data-value="{{$totalHolidayPay}}">{{ number_format($totalHolidayPay, 2, '.', ',') }}</span></p>
                </div>
              @endif
            @endif

            @if(count($standardallowances)>0)
                <table style="width: 100% !important;">
                  @foreach($standardallowances as $eachstandard)
                    @if ($eachstandard->baseonattendance == 1)
                      <tr>
                        <td style="width: 40%;"><small>{{strtoupper($eachstandard->description)}}</small></td>
                        <td>
                          @if($eachstandard->paidforthismonth == 0)
                          <div class="icheck-primary d-inline mr-2 standardallowance" 
                          data-allowancetype="0" 
                          data-allowanceid="{{$eachstandard->empallowanceid}}" 
                          data-totalamount="{{$eachstandard->totalDaysAmount}}" 
                          dataid="{{$eachstandard->id}}"
                          data-amount="{{$eachstandard->totalDaysAmount}}" 
                          data-description="{{$eachstandard->description}}" style="font-size: 11.5px;">
                            <input type="radio" id="standardallowance{{$eachstandard->id}}1" name="standardallowance{{$eachstandard->id}}" style="height:35px; width:35px; vertical-align: middle;" 
                            @if(($configured == 1 && $eachstandard->paymenttype == 0) || ($eachstandard->lock == 1 && $configured == 1 && $eachstandard->paymenttype == 0))checked disabled @endif 
                            @if($eachstandard->lock == 1 && $eachstandard->paymenttype == 0) @endif @if($eachstandard->paymenttype == 1) disabled @endif checked>
                            <label for="standardallowance{{$eachstandard->id}}1">Full   &nbsp;&nbsp;&nbsp;<span class="text-info">Base on Attendance ( {{$eachstandard->amountperday}} * {{$eachstandard->totalDayspresent}} )</span>
                            </label>
                            
                          </div>
                          @else
                            <div class="icheck-primary d-inline mr-2 standardallowance" 
                            data-allowancetype="0" 
                            data-allowanceid="{{$eachstandard->empallowanceid}}" 
                            data-totalamount="{{$eachstandard->totalDaysAmount}}" 
                            dataid="{{$eachstandard->id}}"
                            data-amount="{{$eachstandard->totalDaysAmount}}" 
                            data-description="{{$eachstandard->description}}" style="font-size: 11.5px;">
                              <input type="radio" id="standardallowance{{$eachstandard->id}}1" name="standardallowance{{$eachstandard->id}}" style="height:35px; width:35px; vertical-align: middle;" 
                              @if(($configured == 1 && $eachstandard->paymenttype == 0) || ($eachstandard->lock == 1 && $configured == 1 && $eachstandard->paymenttype == 0))checked disabled @endif 
                              @if($eachstandard->lock == 1 && $eachstandard->paymenttype == 0) @endif @if($eachstandard->paymenttype == 1) disabled @endif checked>
                              <label for="standardallowance{{$eachstandard->id}}1">Full   &nbsp;&nbsp;&nbsp;<span class="text-info">Base on Attendance ( {{$eachstandard->amountperday}} * {{$eachstandard->totalDayspresent}} )</span>
                              </label>
                              
                            </div>
                          @endif
                        </td>
                        <td class="text-right text-success"><small class=" text-bold" id="text-amount-standardallowance-{{$eachstandard->empallowanceid}}">{{number_format($eachstandard->totalDaysAmount,2,'.',',')}}</small></td>
                      </tr>
                    @elseif ($eachstandard->amountbaseonsalary == 1)
                      <tr>
                        <td style="width: 40%;"><small>{{strtoupper($eachstandard->description)}}</small></td>
                        <td>
                          @if($eachstandard->paidforthismonth == 0)
                          <div class="icheck-primary d-inline mr-2 standardallowance" 
                          data-allowancetype="0" 
                          data-allowanceid="{{$eachstandard->empallowanceid}}" 
                          data-totalamount="{{$eachstandard->amount}}" 
                          dataid="{{$eachstandard->id}}"
                          data-amount="{{$eachstandard->totalamount}}" 
                          data-description="{{$eachstandard->description}}" style="font-size: 11.5px;">
                            <input type="radio" id="standardallowance{{$eachstandard->id}}1" name="standardallowance{{$eachstandard->id}}" style="height:35px; width:35px; vertical-align: middle;" 
                            @if(($configured == 1 && $eachstandard->paymenttype == 0) || ($eachstandard->lock == 1 && $configured == 1 && $eachstandard->paymenttype == 0))checked disabled @endif 
                            @if($eachstandard->lock == 1 && $eachstandard->paymenttype == 0) @endif @if($eachstandard->paymenttype == 1) disabled @endif checked>
                            <label for="standardallowance{{$eachstandard->id}}1">Full   &nbsp;&nbsp;&nbsp;<span class="text-info">Amount Base on Daily Salary</span>
                            </label>
                            
                          </div>
                          @endif
                        </td>
                        <td class="text-right text-success"><small class=" text-bold" id="text-amount-standardallowance-{{$eachstandard->empallowanceid}}">{{number_format($eachstandard->totalamount,2,'.',',')}}</small></td>
                      </tr>
                    @else
                      <tr>
                        <td style="width: 40%;"><small>{{strtoupper($eachstandard->description)}}</small></td>
                        <td>
                          @if($eachstandard->paidforthismonth == 0)
                          <div class="icheck-primary d-inline mr-2 standardallowance" 
                          data-allowancetype="0" 
                          data-allowanceid="{{$eachstandard->empallowanceid}}" 
                          data-totalamount="{{$eachstandard->totalamount}}" 
                          dataid="{{$eachstandard->id}}"
                          data-amount="{{$eachstandard->totalamount}}" 
                          data-description="{{$eachstandard->description}}" style="font-size: 11.5px;">
                            <input type="radio" id="standardallowance{{$eachstandard->id}}1" name="standardallowance{{$eachstandard->id}}" style="height:35px; width:35px; vertical-align: middle;" 
                            @if(($configured == 1 && $eachstandard->paymenttype == 0) || ($eachstandard->lock == 1 && $configured == 1 && $eachstandard->paymenttype == 0))checked disabled @endif 
                            @if($eachstandard->lock == 1 && $eachstandard->paymenttype == 0) @endif @if($eachstandard->paymenttype == 1) disabled @endif>
                            <label for="standardallowance{{$eachstandard->id}}1">Full
                            </label>
                          </div>
                          <div class="icheck-primary d-inline mr-2 standardallowance" 
                          data-allowancetype="1" 
                          data-allowanceid="{{$eachstandard->empallowanceid}}" 
                          data-totalamount="{{$eachstandard->totalamount}}" 
                          dataid="{{$eachstandard->id}}"
                          data-amount="{{$eachstandard->totalamount/2}}" 
                          data-description="{{$eachstandard->description}}" style="font-size: 11.5px;">
                            <input type="radio" id="standardallowance{{$eachstandard->id}}2" name="standardallowance{{$eachstandard->id}}" style="height:35px; width:35px; vertical-align: middle;" 
                            @if(($configured == 1 && $eachstandard->paymenttype == 1) || ($eachstandard->lock == 1 && $eachstandard->paymenttype == 1))checked  disabled @endif>
                            <label for="standardallowance{{$eachstandard->id}}2">Half
                            </label>
                          </div>
                          @else
                          Paid for this month
                          @endif
                        </td>
                        <td class="text-right text-success"><small class=" text-bold" id="text-amount-standardallowance-{{$eachstandard->empallowanceid}}">{{number_format($eachstandard->totalamount,2,'.',',')}}</small></td>
                      </tr>
                    @endif
                    
                    @php
                      if($eachstandard->paymenttype == 0){
                        $totalearnings+=((float)number_format($eachstandard->totalamount/2,2));
                      }else{
                        $totalearnings+=((float)number_format($eachstandard->totalamount/2,2));
                      }
                    @endphp
                  @endforeach
                </table>
              <!-- /.card-body -->
            @endif
            @if(count($otherallowances)>0)
                <table style="width: 100% !important;">
                  @foreach($otherallowances as $eachotherallowance)
                  <tr>
                    <td style="width: 40%;"><small>{{strtoupper($eachotherallowance->description)}}</small></td>
                    <td>
                      @if($eachotherallowance->paidforthismonth == 0)
                      <div class="icheck-primary d-inline mr-2 otherallowance" 
                        data-allowancetype="0" 
                        data-allowanceid="{{$eachotherallowance->id}}" 
                        data-totalamount="{{$eachotherallowance->totalamount}}"
                        dataid="{{$eachotherallowance->id}}"  
                        {{-- data-odid="{{$eachotherallowance->id}}" --}}
                        data-amount="{{$eachotherallowance->totalamount}}" 
                        data-description="{{$eachotherallowance->description}}" style="font-size: 11.5px;">
                        <input type="radio" id="otherallowance{{$eachotherallowance->id}}1" name="otherallowance{{$eachotherallowance->id}}" style="height:35px; width:35px; vertical-align: middle;" @if($configured == 1 && $eachotherallowance->paymenttype == 0 || $eachotherallowance->lock == 1)checked @endif @if($eachotherallowance->lock == 1) disabled @endif>
                        <label for="otherallowance{{$eachotherallowance->id}}1">Full 
                        </label>
                      </div>
                      <div class="icheck-primary d-inline mr-2 otherallowance" 
                      data-allowancetype="1" 
                      data-allowanceid="{{$eachotherallowance->id}}"
                      dataid="{{$eachotherallowance->id}}"
                      {{-- data-odid="{{$eachotherallowance->id}}" --}}
                      data-totalamount="{{$eachotherallowance->totalamount}}" 
                      data-amount="{{number_format($eachotherallowance->totalamount/2,2)}}" 
                      data-description="{{$eachotherallowance->description}}" style="font-size: 11.5px;">
                        <input type="radio" id="otherallowance{{$eachotherallowance->id}}2" name="otherallowance{{$eachotherallowance->id}}" style="height:35px; width:35px; vertical-align: middle;" @if($configured == 1 && $eachotherallowance->paymenttype == 1 || $eachotherallowance->lock == 1)checked @endif @if($eachotherallowance->lock == 1) disabled @endif>
                        <label for="otherallowance{{$eachotherallowance->id}}2">Half
                        </label>
                      </div>
                      @else
                      Paid for this month
                      @endif
                    </td>
                    <td class="text-right text-success"><small class=" text-bold"  id="text-amount-otherallowance-{{$eachotherallowance->id}}">{{number_format($eachotherallowance->totalamount,2,'.',',')}}</small></td>
                  </tr>
                  @php
                    if($eachotherallowance->paymenttype == 0){
                    $totalearnings+=($eachotherallowance->totalamount);
                    }else{
                    $totalearnings+=(number_format($eachotherallowance->totalamount/2,2));
                    }
                  @endphp
                  @endforeach
                </table>
              <!-- /.card-body -->
            @endif
            @if(count($filedovertimes)>0)
            <table style="width: 100%; table-layout: fixed; font-size: 14px;" id="table-overtimes">
              <tr>
                <td>Overtime Pay</td>
                <td>{{collect($filedovertimes)->sum('totalhours')}} hr(s)</td>
                <td class="text-right text-bold text-success" id="td-overtimepay" data-amount="{{collect($filedovertimes)->sum('amount')}}">
                  {{number_format(collect($filedovertimes)->sum('amount'),2,'.',',')}}
                </td>
                {{-- @if($released == 1)
                <td class="text-right text-bold text-success">
                  {{number_format($payrollinfo->basicsalaryamount,2,'.',',')}}
                </td>
                @else
                <td class="text-right text-bold text-success" id="td-basicpay-amount" data-amount="{{$basicsalaryamount}}">
                  @if($basicsalaryinfo->amount > 0 )
                    {{$basicsalaryamount}}
                  @endif
                </td>
                @endif --}}
              </tr>        
              @foreach($filedovertimes as $filedovertime)
              <tr>
                <td></td>
                <td></td>
                <td class="text-right text-bold text-success filedovertimes" data-id="{{$filedovertime->id}}"  data-amount="{{$filedovertime->amount}}" data-totalhours="{{$filedovertime->totalhours}}">
                </td>
              </tr>  
              @php
              $totalearnings+=($filedovertime->amount);
              @endphp
              @endforeach      
            </table>
            @endif
            </td>
          <td>&nbsp;</td>
          <td colspan="2" style="vertical-align: top;">
            <table style="width: 100% !important;">
              <tr>
                <td style="width: 50%;"><small>Absent</small></td>
                @if($released == 1)
                <td style="width: 50%; text-align: right; padding-right: 19px!important" class="text-danger"><small class="text-bold">{{number_format($payrollinfo->daysabsentamount,2,'.',',')}}</small></td>
                @else
                <td style="width: 50%; text-align: right; padding-right: 19px!important" class="text-danger text-bold" id="amountabsent" data-value="{{$basicsalaryinfo->attendancebased == 1 ? (bcdiv(collect($attendance)->where('totalworkinghours',0)->count()*$basicsalaryinfo->amountperday,1,2)) : 0}}"><small class="text-bold">{{$basicsalaryinfo->attendancebased == 1 ? (bcdiv(collect($attendance)->where('totalworkinghours',0)->count()*$basicsalaryinfo->amountperday,1,2)) : 0}}</small></td>
                @endif
              </tr>
              <tr>
                <td style="width: 50%;"><small>Late</small></td>
                @if($released == 1)
                <td style="width: 50%; text-align: right; padding-right: 19px!important" class="text-danger"><small class="text-bold">{{number_format($payrollinfo->lateamount,2,'.',',')}}</small></td>
                @else
                {{-- original --}}
                {{-- <td style="width: 50%; text-align: right;" class="text-danger text-bold" id="amountlate" data-value="{{$basicsalaryinfo->attendancebased == 1 ? bcdiv(collect($latedays)->sum('amountdeduct'),1,2) : 0}}">{{$basicsalaryinfo->attendancebased == 1 ? bcdiv(collect($latedays)->sum('amountdeduct'),1,2) : 0}}</td> --}}
                
                {{-- first update --}}
                {{-- <td style="width: 50%; text-align: right;" class="text-danger text-bold" id="amountlate" 
                  data-value="{{$basicsalaryinfo->attendancebased == 1 ? bcdiv(collect($latedays)->sum('amountdeduct'),1,2) : 0}}">
                  {{$basicsalaryinfo->attendancebased == 1 ? bcdiv(collect($latedays)->sum('amountdeduct'),1,2) : 0}}
                </td> --}}
               
                {{-- second update --}}
                {{-- <td style="width: 50%; text-align: right;" class="text-danger text-bold" id="amountlate" 
                  data-value="@php
                    if($basicsalaryinfo->attendancebased == 1){
                      $latetime = collect($latedays)->sum('latehours') * 60;
                    $amountperhour = $basicsalaryinfo->amountperhour / 60;
                    $min = number_format($amountperhour - .005, 2) * $latetime;
                    } else {
                      $min = 0;
                    }
                  @endphp {{$min}} ">
                  @php
                    if($basicsalaryinfo->attendancebased == 1){
                      $latetime = collect($latedays)->sum('latehours') * 60;
                    $amountperhour = $basicsalaryinfo->amountperhour / 60;
                    $min = number_format($amountperhour - .005, 2) * $latetime;
                    } else {
                      $min = 0;
                    }
                  @endphp

                  {{$min}}
                </td> --}}

                <td style="width: 50%; text-align: right; padding-right: 19px!important" class="text-danger text-bold" id="amountlate" 
                  data-value="
                  @php
                    // if($basicsalaryinfo->attendancebased == 1){

                    //   if ($basicsalaryinfo->flexitime == 1) {
                    //     if ($tardinessbaseonsalary) {
                    //       $latetime = number_format(collect($latedays)->sum('flexihoursundertime') * 60,2);
                    //       $amountperhour = $basicsalaryinfo->amountperhour / 60;
                    //       $min = number_format($amountperhour - .005, 2) * $latetime;
                    //     } else {
                    //       $min = collect($latedays)->sum('amountdeduct');
                    //     }
                    //   } else {
                    //     if ($tardinessbaseonsalary) {
                    //       $latetime = number_format(collect($latedays)->sum('latehours') * 60,2);
                    //       $amountperhour = $basicsalaryinfo->amountperhour / 60;
                    //       $min = number_format($amountperhour - .005, 2) * $latetime;
                    //     } else {
                    //       if ($employeeinfo->tardinessperdepactivated === 1) {
                    //         $min = collect($latedays)->sum('amountdeduct');
                    //       } else {
                    //         $min = 0;
                    //       }
                    //     }
                    //   }
                      
                    // } else {
                    //   $min = 0;
                    // }
                    if($basicsalaryinfo->attendancebased == 1){
                      if ($basicsalaryinfo->flexitime == 1) {
                        $min = 0;
                      } else {
                        if ($tardinessbaseonsalary) {
                            $latetime = number_format(collect($latedays)->sum('latehours') * 60,2);
                            $amountperhour = $basicsalaryinfo->amountperhour / 60;
                            $min = number_format($amountperhour - .005, 2) * $latetime;
                          } else {
                            if ($employeeinfo->tardinessperdepactivated == 1) {
                              $min = collect($latedays)->sum('amountdeduct');
                            } else {
                              $min = 0;
                            }
                          }
                      }
                    } else {
                      $min = 0;
                    }
                  @endphp
                  {{$min}}
                  ">
                  @php
                  
                  // if($basicsalaryinfo->attendancebased == 1){

                  //   if ($basicsalaryinfo->flexitime == 1) {
                  //     if ($tardinessbaseonsalary) {
                  //       $a = 0;
                  //       $latetime = number_format(collect($latedays)->sum('flexihoursundertime') * 60,2);
                  //       $amountperhour = $basicsalaryinfo->amountperhour / 60;
                  //       $min = number_format($amountperhour - .005, 2) * $latetime;
                  //     } else {
                  //       $min = collect($latedays)->sum('amountdeduct');
                  //     }
                  //   } else {
                  //     if ($tardinessbaseonsalary) {
                  //       $a = 1;
                  //       $latetime = number_format(collect($latedays)->sum('latehours') * 60,2);
                  //       $amountperhour = $basicsalaryinfo->amountperhour / 60;
                  //       $min = number_format($amountperhour - .005, 2) * $latetime;
                  //     } else {
                  //       // $min = collect($latedays)->sum('amountdeduct');
                  //       if ($employeeinfo->tardinessperdepactivated === 1) {
                  //         $min = collect($latedays)->sum('amountdeduct');
                  //       } else {
                  //         $min = 0;
                  //       }
                  //     }
                  //   }
                    
                  // } else {
                  //   $min = 0;
                  // }
                  if($basicsalaryinfo->attendancebased == 1){
                    if ($basicsalaryinfo->flexitime == 1) {
                      $min = 0;
                    } else {
                      if ($tardinessbaseonsalary) {
                          $latetime = number_format(collect($latedays)->sum('latehours') * 60,2);
                          $amountperhour = $basicsalaryinfo->amountperhour / 60;
                          $min = number_format($amountperhour - .005, 2) * $latetime;
                        } else {
                          if ($employeeinfo->tardinessperdepactivated == 1) {
                            $min = collect($latedays)->sum('amountdeduct');
                          } else {
                            $min = 0;
                          }
                        }
                    }
                } else {
                    $min = 0;
                  }
                @endphp
                  @php
                      $mind = collect($latedays)->sum('amountdeduct');
                  @endphp
                  <small class="text-bold">{{number_format($min,2,'.','')}}</small>
                  <span></span>
                </td>
                
                @endif
              </tr>
              <tr>
                <td style="width: 50%;"><small>Undertime</small></td>
                @if($released == 1)
                  <td style="width: 50%; text-align: right; padding-right: 19px!important" class="text-danger"><small class="text-bold">{{number_format($payrollinfo->undertimeamount,2,'.',',')}}</small></td>
                @else 
                <td class="text-right text-danger" style="width: 50%; padding-right: 19px!important" id="amountundertime" 
                  data-value=" 
                  @php
                    if ($basicsalaryinfo->flexitime == 1) {
                      if ($basicsalaryinfo->flexitime == 1) {
                        if ($tardinessbaseonsalary) {
                          $latetime = number_format(collect($latedays)->sum('flexihoursundertime') * 60,2);
                          $amountperhour = $basicsalaryinfo->amountperhour / 60;
                          $min = number_format($amountperhour - .005, 2) * $latetime;
                        } else {
                          $min = collect($latedays)->sum('amountdeduct');
                        }
                      } else {
                        $min = 0;
                      }
                    } else {
                      $undertime = number_format(collect($attendance)->where('totalworkinghours','>',0)->sum('undertimehours') * 60,2);
                      $amountperhour = $basicsalaryinfo->amountperhour / 60;
                      $min = number_format($amountperhour - .005, 2) * $undertime;
                    }
                  @endphp
                    {{$min}}">
                    <small class="text-bold">{{number_format($min,2,'.','')}}</small>
                </td>
                @endif
                
              </tr>
            </table>
            {{-- @php
                dd(collect($payrollperiod));
            @endphp --}}
            @if(count($standarddeductions)>0)
              <table style="width: 100% !important;">
                @foreach($standarddeductions as $eachstandarddeduction)
                <tr>
                  <td style="width: 30%;"><small>{{strtoupper($eachstandarddeduction->description)}}</small></td>
                  <td>
                    @if($eachstandarddeduction->paidforthismonth == 0)
                    <div class="icheck-primary d-inline mr-2 standarddeduction" 
                        data-deducttype="0" 
                        data-deductionid="{{$eachstandarddeduction->id}}" 
                        dataid="{{$eachstandarddeduction->id}}"
                        data-totalamount="{{$eachstandarddeduction->totalamount}}" 
                        data-amount="{{$eachstandarddeduction->totalamount}}" 
                        data-description="{{$eachstandarddeduction->description}}" style="font-size: 11.5px;">

                          <input type="radio" id="standarddeduction{{$eachstandarddeduction->id}}1" name="standarddeduction{{$eachstandarddeduction->id}}" style="height:35px; width:35px; vertical-align: middle;" 
                          @if(($configured == 1 && $eachstandarddeduction->paymenttype == 0) || ($eachstandarddeduction->lock == 1 && $eachstandarddeduction->paymenttype == 0))checked  @endif @if($eachstandarddeduction->lock == 1) 
                            disabled
                          @endif>
                          <label for="standarddeduction{{$eachstandarddeduction->id}}1">Full 
                          </label>
                      
                    </div>
                    <div class="icheck-primary d-inline mr-2 standarddeduction" 
                        data-deducttype="1" 
                        data-deductionid="{{$eachstandarddeduction->id}}" 
                        dataid="{{$eachstandarddeduction->id}}"
                        data-totalamount="{{$eachstandarddeduction->totalamount}}" 
                        data-amount="{{$eachstandarddeduction->totalamount/2}}" 
                        data-description="{{$eachstandarddeduction->description}}" style="font-size: 11.5px;">
                    
                        <input type="radio" id="standarddeduction{{$eachstandarddeduction->id}}2" name="standarddeduction{{$eachstandarddeduction->id}}" style="height:35px; width:35px; vertical-align: middle;" @if(($configured == 1 && $eachstandarddeduction->paymenttype == 1) || ($eachstandarddeduction->lock == 1 && $eachstandarddeduction->paymenttype == 1))checked @endif @if($eachstandarddeduction->lock == 1) disabled @endif>
                        <label for="standarddeduction{{$eachstandarddeduction->id}}2">Half
                        </label>

                      
                    </div>
                    @else
                    Paid for this month
                    @endif
                  </td>
                  <td class="text-danger text-right" style="padding-right: 19px!important"><small class=" text-bold" id="text-amount-standarddeduction-{{$eachstandarddeduction->id}}">{{number_format($eachstandarddeduction->totalamount,2,'.',',')}}</small>
                  </td>
                </tr>
                @endforeach
              </table>
            @endif
            
            @if(count($otherdeductions)>0)
            
                <table style="width: 100% !important;">
                  @foreach($otherdeductions as $eachotherdeduction)
                  <tr>
                    <td style="width: 30%;"><small>{{ strtoupper($eachotherdeduction['description']) }}</small></td>
                    <td>
                      @if($eachotherdeduction['paidforthismonth'] == 0)
                      <div class="icheck-primary d-inline mr-2 otherdeduction" 
                        data-deducttype="0" 
                        {{-- data-odid="{{$eachotherdeduction->deductionotherid}}" --}}
                        data-deductionid="{{$eachotherdeduction['id']}}"
                        dataid="{{$eachotherdeduction['deductionotherid']}}"
                        data-totalamount="{{$eachotherdeduction['totalamount']}}" 
                        data-amount="{{$eachotherdeduction['totalamount']}}" 
                        data-description="{{$eachotherdeduction['description']}}" style="font-size: 11.5px;">

                        <input type="radio" id="otherdeduction{{$eachotherdeduction['id']}}1" name="otherdeduction{{$eachotherdeduction['id']}}" style="height:35px; width:35px; vertical-align: middle;"
                            @if(($configured == 1 && $eachotherdeduction['paymenttype'] == 0) || ($eachotherdeduction['lock'] == 1 && $eachotherdeduction['paymenttype'] == 0))checked @endif @if($eachotherdeduction['lock'] == 1)
                              disabled 
                          @endif>
                        <label for="otherdeduction{{$eachotherdeduction['id']}}1">Full 
                        </label>
                      </div>
                      <div class="icheck-primary d-inline mr-2 otherdeduction" 
                        data-deducttype="1" 
                        {{-- data-odid="{{$eachotherdeduction->deductionotherid}}" --}}
                        {{-- data-deductionid="{{$eachotherdeduction->id}}"  --}}
                        data-deductionid="{{$eachotherdeduction['id']}}"
                        dataid="{{$eachotherdeduction['deductionotherid']}}"
                        data-totalamount="{{$eachotherdeduction['totalamount']}}" 
                        data-amount="{{$eachotherdeduction['totalamount']/2}}" 
                        data-description="{{$eachotherdeduction['description']}}" style="font-size: 11.5px;">


                        <input type="radio" id="otherdeduction{{$eachotherdeduction['id']}}2" name="otherdeduction{{$eachotherdeduction['id']}}" style="height:35px; width:35px; vertical-align: middle;" 
                            @if(($configured == 1 && $eachotherdeduction['paymenttype'] == 1) || ($eachotherdeduction['lock'] == 1 && $eachotherdeduction['paymenttype'] == 1))checked @endif @if($eachotherdeduction['lock'] == 1)
                            disabled 
                          @endif>
                        <label for="otherdeduction{{$eachotherdeduction['id']}}2">Half
                        </label>
                      </div>
                      @else
                      Paid for this month
                      @endif
                    </td>
                    <td class="text-info" style="font-size: 12px;">Amount Paid :<b> {{number_format($eachotherdeduction['totalotherdeductionpaid'],2)}}</b></td>
                    <td class="text-danger text-right">
                        <input type="number" class="form-control text-right"  id="text-amount-otherdeduction-{{$eachotherdeduction['id']}}" placeholder="Amount" style="
                        padding: 0px;
                        height: 25px;
                        width: 90px;
                        padding-left: 20px!important;
                        margin-left: 40px;
                        font-size: 14px;" min="0" value="{{$eachotherdeduction['totalamount']}}">
                      {{-- @if ($eachotherdeduction['totalotherdeductionpaid'] > $eachotherdeduction['fullamount'])
                        <small class="text-bold" id="text-amount-otherdeduction-{{$eachotherdeduction['id']}}">{{number_format(0,2)}}</small></td>
                      @else
                        <small class="text-bold" id="text-amount-otherdeduction-{{$eachotherdeduction['id']}}">{{number_format($eachotherdeduction['totalamount'],2,'.',',')}}</small></td>
                      @endif --}}
                    </td>
                      {{-- <td class="text-danger text-right"><small class=" text-bold" id="text-amount-otherdeduction-{{$eachotherdeduction->id}}">{{number_format($eachotherdeduction->amount,2,'.',',')}}</small></td> --}}
                  </tr>
                  @endforeach
                </table>
              <!-- /.card-body -->
            @endif
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2">@if($released == 0)<button type="button" class="btn btn-sm btn-warning pt-0 pb-0 btn-add-particular" data-type="1"><i class="fa fa-plus fa-xs"></i>&nbsp;Add earning</button>@endif</td>
          <td>&nbsp;</td>
          <td colspan="2">@if($released == 0)<button type="button" class="btn btn-sm btn-warning pt-0 pb-0 btn-add-particular" data-type="2"><i class="fa fa-plus fa-xs"></i>&nbsp;Add deduction</button>@endif</td>
        </tr>
        <tr>
          <td colspan="2" style="vertical-align: top;" class="p-0">
            <table style="width: 100%;" id="container-addearnings">
                @if(collect($addedparticulars)->where('type','1')->count()>0)
                  @foreach(collect($addedparticulars)->where('type','1')->values() as $addedparticular)
                    <tr>
                      <td>@if($released == 0)<i class="fa fa-times fa-xs text-danger delete-particular" data-id="{{$addedparticular->id}}" data-amount="{{$addedparticular->amount}}" style="cursor: pointer;"></i>@endif <span id="{{$addedparticular->id}}" class="text-right m-0 span-description additional-paticular" data-type="1" data-amount="{{$addedparticular->amount}}">{{$addedparticular->description}}</span></td>
                      <td><span class="text-right m-0 span-amount float-right text-success" data-type="1">{{$addedparticular->amount}}</span></td>
                    </tr>
                  @endforeach
                @endif
            </table>
          </td>
          <td></td>
          <td colspan="2" style="vertical-align: top;">
            <table style="width: 100%;" id="container-adddeductions">
              @if(collect($addedparticulars)->where('type','2')->count()>0)
                @foreach(collect($addedparticulars)->where('type','2')->values() as $addedparticular)
                  <tr>
                    <td>@if($released == 0)<i class="fa fa-times fa-xs text-danger delete-particular" data-id="{{$addedparticular->id}}" data-amount="{{$addedparticular->amount}}" style="cursor: pointer;"></i>@endif <span id="{{$addedparticular->id}}" class="text-right m-0 span-description additional-paticular" data-type="2" data-amount="{{$addedparticular->amount}}">{{$addedparticular->description}}</span></td>
                    <td><span class="text-right m-0 span-amount float-right text-danger" data-type="2">{{$addedparticular->amount}}</span></td>
                  </tr>
                @endforeach
              @endif
            </table>
          </td>
        </tr>
      </tbody>
      @if($released == 1)
      <tfoot>
        <tr>
          <th>Total Earnings</th>
          <th class="text-right text-success" style="font-size: 17px;">{{number_format($payrollinfo->totalearning,2,'.',',')}}</th>
          <th></th>
          <th>Total Deductions</th>
          <th class="text-right text-danger" style="font-size: 17px;">{{number_format($payrollinfo->totaldeduction,2,'.',',')}}
          </th>
        </tr>
        <tr>
          <th colspan="2" style="border-top: 1px solid #ddd;" class="text-left">
            {{-- Employees Contribution --}}            
          </th>
          <td></td>
          <td style="border-top: 1px solid #ddd;" class="text-bold">NET PAY</td>
          <td style="border-top: 1px solid #ddd; font-size: 30px;" class="text-bold text-right text-success"></td>
        </tr>
        <tr>
          <th colspan="2" style="" class="text-left">
            {{-- Employees Contribution --}}            
          </th>
          <td></td>
          <td style="font-size: 40px;" colspan="2" class="text-bold text-right text-success" id="">{{number_format($payrollinfo->netsalary,2,'.',',')}}</td>
        </tr>
        <tr>
          <td colspan="2">
            @if($configured == 1)<span class="badge badge-success">Configured</span> @endif @if($released == 1)<span class="badge badge-success">Released</span> <a  href="javascript:void(0)" class="text-danger" id="void_payslip">&nbsp;<i class="fas fa-stop-circle"></i> Void&nbsp;</a>@endif</td>
          <td></td>
          <td colspan="2" class="text-right">
            @if($released == 0)
            <button type="button" class="btn btn-sm btn-default" id="btn-release-payslip">&nbsp;Release Payslip&nbsp;</button>
            
            <button type="button" class="btn btn-sm btn-primary btn-compute p-0" data-id="0" hidden>&nbsp;Compute&nbsp;</button>
            <button type="button" class="btn btn-sm btn-primary btn-compute" data-id="1" id="btn-save-computation">&nbsp;Save this computation&nbsp;</button>
            @else
            <button type="button" class="btn btn-sm btn-secondary" id="btn-export-payslip">&nbsp;<i class="fa fa-print"></i> Print Payslip&nbsp;</button>
            @endif
          </td>
        </tr>
      </tfoot>
      @else
      <tfoot>
        <tr>
          <th>Total Earnings</th>
          <th class="text-right text-success" style="font-size: 17px;"><span id="span-total-earn-display">{{$totalearnings}}</span><span id="span-total-earn" hidden>{{$totalearnings}}</span></th>
          <th></th>
          <th>Total Deductions</th>
          <th class="text-right text-danger" style="font-size: 17px;"><span id="span-total-deduct-display"></span>
            <span id="span-total-deduct" hidden></span>
          </th>
        </tr>
        <tr>
          <th colspan="2" style="border-top: 1px solid #ddd;" class="text-left">
            {{-- Employees Contribution --}}            
          </th>
          <td></td>
          <td style="border-top: 1px solid #ddd;" class="text-bold">NET PAY</td>
          <td style="border-top: 1px solid #ddd; font-size: 30px;" class="text-bold text-right text-success"></td>
        </tr>
        <tr>
          <th colspan="2" style="" class="text-left">
            {{-- Employees Contribution --}}            
          </th>
          <td></td>
          <td style="font-size: 40px;" colspan="2" class="text-bold text-right text-success"><span id="span-netpay-display"></span><span id="span-netpay" hidden></span></td>
        </tr>
        <tr>
          <td colspan="2">
            @if($configured == 1)<span class="badge badge-success">Configured</span> @endif @if($released == 1)<span class="badge badge-success">Released</span> @endif</td>
          <td></td>
          <td colspan="2" class="text-right">
            @if($released == 0)
            <button type="button" class="btn btn-sm btn-default" id="btn-release-payslip">&nbsp;Release Payslip&nbsp;</button>
            <button type="button" class="btn btn-sm btn-primary btn-compute p-0" data-id="0" hidden>&nbsp;Compute&nbsp;</button>
            <button type="button" class="btn btn-sm btn-primary btn-compute" data-id="1" id="btn-save-computation">&nbsp;Save this computation&nbsp;</button>
            @else
            <button type="button" class="btn btn-sm btn-secondary" id="btn-release-payslip">&nbsp;<i class="fa fa-print"></i> Print Payslip&nbsp;</button>
            @endif
          </td>
        </tr>
      </tfoot>
      @endif
    </table>
  </div>
</div>
<div class="row">
    <div class="col-md-12" id="div-container-particulars"></div>
</div>
<div class="modal fade" id="modal-add-particular">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Particular</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal-particular-container">
        
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btn-submit-particular">Add particular</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<script>
  // $('.listherediv').prop('hidden', true)
  $('#btn-refresh').on('click', function(){
    $('#select-employee').trigger('change')
  })
  $('#btn-save-computation').on('click', function(){
    $('.btn-compute[data-id="1"]').click()
  })
  $('#btn-printslip').hide()
  $('#card-footer-computation').show()
  @if($configured == 1)
  $('.card').removeClass('collapsed-card')
    $('#btn-release-payslip').show();
  @else
  
  $('#btn-release-payslip').hide();
  @endif
  @if($released == 1)
  $('.card').removeClass('collapsed-card')
    $('#btn-save-computation').hide();
    $('input[type=radio]').attr("disabled",true);
  $('#btn-printslip').show()
  // $('#btn-release-payslip').hide();
  @else
  
  $('#btn-save-computation').show();
  $('#btn-printslip').hide()
  @endif

  @if($basicsalaryinfo->amount > 0 )
  $('#netsalary').text('{{$basicsalaryamount}}')
  @endif
  
  $(document).on('click', '.listholiday', function() {
    $('.listholiday i').addClass('fa-caret-down');
    $('.listholiday i').removeClass('fa-caret-right');
    $('.listholiday').addClass('listholidayclose').removeClass('listholiday');
    $('.listherediv').toggleClass('active'); // Toggle animation class
    $('.listherediv').addClass('activediv'); // Toggle animation class
    $('.listherediv').removeClass('inactivediv'); // Toggle animation class
    $('.listherediv').css('display', 'contents'); // Toggle animation class
    
  });

  $(document).on('click', '.listholidayclose', function() {
    $('.listholidayclose i').removeClass('fa-caret-down');
    $('.listholidayclose i').addClass('fa-caret-right');
    
    $('.listholidayclose').addClass('listholiday').removeClass('listholidayclose');
    $('.listherediv').toggleClass('active'); // Toggle animation class
    $('.listherediv').addClass('inactivediv'); // Toggle animation class
    $('.listherediv').removeClass('activediv'); // Toggle animation class
    $('.listherediv').css('display', 'block'); // Toggle animation class
    
  });

  $('.standardallowance').on('click', function(){
    $('.amountcontainer').removeClass('text-bold')
    // $('.amountcontainer').removeClass('text-danger')
    var allowanceid = $(this).attr('data-allowanceid');
    var allowancetype = $(this).attr('data-allowancetype');
    if(allowancetype == 3)
    {
      var amount = $(this).find('input[type="number"]').val();
    }else{
      var amount = $(this).attr('data-amount');
    }
    // if($('#container-added-allowance').find('#stanallowance'+allowanceid).length == 0)
    // {
    //   $('#container-added-allowance').append('<p id="stanallowance'+allowanceid+'" class="text-right m-0 amountcontainer">'+amount+'</p>')
    // }else{
    //   $('#stanallowance'+allowanceid).text(amount)
    // }
    // $('#stanallowance'+allowanceid).addClass('text-bold')
    // $('#stanallowance'+allowanceid).addClass('text-success')
    $('#text-amount-standardallowance-'+allowanceid).text(amount)
    $('.btn-compute[data-id="0"]').click()
  })
  $('.otherallowance').on('click', function(){
    $('.amountcontainer').removeClass('text-bold')
    // $('.amountcontainer').removeClass('text-danger')
    var allowanceid = $(this).attr('data-allowanceid');
    var allowancetype = $(this).attr('data-allowancetype');
    if(allowancetype == 3)
    {
      var amount = $(this).find('input[type="number"]').val();
    }else{
      var amount = $(this).attr('data-amount');
    }
    if($('#container-added-allowance').find('#otherallowance'+allowanceid).length == 0)
    {
      $('#container-added-allowance').append('<p id="otherallowance'+allowanceid+'" class="text-right m-0 amountcontainer">'+amount+'</p>')
    }else{
      $('#otherallowance'+allowanceid).text(amount)
    }
    $('#otherallowance'+allowanceid).addClass('text-bold')
    $('#otherallowance'+allowanceid).addClass('text-success')
    $('#text-amount-otherallowance-'+allowanceid).text(amount)
    $('.btn-compute[data-id="0"]').click()
  })
  $('.standarddeduction').on('click', function(){
    $('.amountcontainer').removeClass('text-bold')
    // $('.amountcontainer').removeClass('text-danger')
    var deductionid = $(this).attr('data-deductionid');
    var deducttype = $(this).attr('data-deducttype');
    if(deducttype == 3)
    {
      var amount = $(this).find('input[type="number"]').val();
    }else{
      var amount = $(this).attr('data-amount');
    }
    if($('#container-added-deduction').find('#standeduction'+deductionid).length == 0)
    {
      $('#container-added-deduction').append('<p id="standeduction'+deductionid+'" class="text-right m-0 amountcontainer">'+amount+'</p>')
    }else{
      $('#standeduction'+deductionid).text(amount)
    }
    $('#standeduction'+deductionid).addClass('text-bold')
    $('#standeduction'+deductionid).addClass('text-danger')
    $('#text-amount-standarddeduction-'+deductionid).text(amount)
    $('.btn-compute[data-id="0"]').click()
  })
  $('.standarddedductioncustom').on('input', function(){
    $('.amountcontainer').removeClass('text-bold')
    // $('.amountcontainer').removeClass('text-danger')
    var deductionid = $(this).closest('.standarddeduction').attr('data-deductionid');
    var amount = $(this).val();
    if(!$('#standarddeduction'+deductionid+'3').is(':checked'))
    {
      $('#standarddeduction'+deductionid+'3').attr('checked',true)
    }
    if($('#container-added-deduction').find('#standeduction'+deductionid).length == 0)
    {
      $('#container-added-deduction').append('<p id="standeduction'+deductionid+'" class="text-right m-0 amountcontainer">'+amount+'</p>')
    }else{
      $('#standeduction'+deductionid).text(amount)
    }
    $('#standeduction'+deductionid).addClass('text-bold')
    $('#standeduction'+deductionid).addClass('text-danger')
    
    $('.btn-compute[data-id="0"]').click()
  })
  $('.otherdeduction').on('click', function(){
    $('.amountcontainer').removeClass('text-bold')
    // $('.amountcontainer').removeClass('text-danger')
    var deductionid = $(this).attr('data-deductionid');
    var deductiontype = $(this).attr('data-deductiontype');
    if(deductiontype == 3)
    {
      var amount = $(this).find('input[type="number"]').val();
    }else{
      var amount = $(this).attr('data-amount');
    }

    console.log(amount);
    if($('#container-added-deduction').find('#otherdeduction'+deductionid).length == 0)
    {
      $('#container-added-deduction').append('<p id="otherdeduction'+deductionid+'" class="text-right m-0 amountcontainer">'+amount+'</p>')
    }else{
      $('#otherdeduction'+deductionid).text(amount)
    }
    $('#otherdeduction'+deductionid).addClass('text-bold')
    $('#otherdeduction'+deductionid).addClass('text-danger')
    $('#text-amount-otherdeduction-'+deductionid).val(amount)
    $('.btn-compute[data-id="0"]').click()
  })
  $('.otherdeductioncustom').on('input', function(){
    $('.amountcontainer').removeClass('text-bold')
    // $('.amountcontainer').removeClass('text-danger')
    var deductionid = $(this).closest('.otherdeduction').attr('data-deductionid');
    var amount = $(this).val();
    if(!$('#otherdeduction'+deductionid+'3').is(':checked'))
    {
      $('#otherdeduction'+deductionid+'3').attr('checked',true)
    }
    if($('#container-added-deduction').find('#otherdeduction'+deductionid).length == 0)
    {
      $('#container-added-deduction').append('<p id="otherdeduction'+deductionid+'" class="text-right m-0 amountcontainer">'+amount+'</p>')
    }else{
      $('#otherdeduction'+deductionid).text(amount)
    }
    $('#otherdeduction'+deductionid).addClass('text-bold')
    $('#otherdeduction'+deductionid).addClass('text-danger')
    $('.btn-compute[data-id="0"]').click()
  })
  
  $('.btn-add-particular').on('click', function(){
    var datatype = $(this).attr('data-type');
    var selected1 = '';
    var selected2 = '';
    if(datatype == 1)
    {
      selected1 = 'selected';
    }
    if(datatype == 2)
    {
      selected2 = 'selected';
    }
    $('#modal-add-particular').modal('show')
    $('#modal-particular-container').empty()
    $('#modal-particular-container').append(
      '<div class="row">'+
        '<div class="col-md-12">'+
          '<label>Description</label>'+
          '<input type="text" class="form-control" id="input-particular-description"/>'+
        '</div>'+
        '<div class="col-md-6">'+
          '<label>Amount</label>'+
          '<input type="number" class="form-control" id="input-particular-amount" value="0.00" min="0"/>'+
        '</div>'+
        '<div class="col-md-6">'+
          '<label>Type</label>'+
          '<select class="form-control" id="select-particular-type" disabled>'+
            '<option value="1" '+selected1+'>Earning</option>'+
            '<option value="2" '+selected2+'>Deduction</option>'+
          '</select>'+
        '</div>'+
      '</div>'
    )
  })
  $('#btn-submit-particular').on('click', function(){
    var validation = 0;
    var description = $('#input-particular-description').val();
    var amount = $('#input-particular-amount').val();
    if(description.replace(/^\s+|\s+$/g, "").length == 0)
    {
      $('#input-particular-description').css('border','1px solid red');
      validation = 1;
    }else{
      $('#input-particular-description').removeAttr('style');
    }
    if(amount.replace(/^\s+|\s+$/g, "").length == 0)
    {
      $('#input-particular-amount').css('border','1px solid red');
      validation = 1;
    }else{
      $('#input-particular-amount').removeAttr('style');
    }
    if(validation == 0)
    {
      var particulartype = $('#select-particular-type').val();
      if(particulartype == 1)
      {
        $('#container-addearnings').append(
          '<tr>'+
              '<td> <i class="fa fa-times fa-xs text-danger remove-particular" data-id="1" data-amount="'+amount+'" style="cursor: pointer;"></i> <span id="0" class="text-right m-0 span-description additional-paticular text-success" data-type="'+particulartype+'" data-amount="'+amount+'">'+description+'</span></td>'+
              '<td><span class="text-right m-0 span-amount float-right text-success" data-type="'+particulartype+'">'+amount+'</span></td>'+
          '</tr>'
        )
      }
      if(particulartype == 2)
      {
        $('#container-adddeductions').append(
          '<tr>'+
              '<td> <i class="fa fa-times fa-xs text-danger remove-particular" data-id="1" data-amount="'+amount+'" style="cursor: pointer;"></i> <span id="0" class="text-right m-0 span-description additional-paticular text-success" data-type="'+particulartype+'" data-amount="'+amount+'">'+description+'</span></td>'+
              '<td><span class="text-right m-0 span-amount float-right text-success" data-type="'+particulartype+'">'+amount+'</span></td>'+
          '</tr>'
        )
      }
    $('.btn-compute[data-id="0"]').click()
    }
  })
</script>
@else
<div class="row">
    <div class="col-md-12">        
        <div class="alert alert-danger" role="alert">
            Basic Salary Information is not yet configured!
            Configure the employee's <span class="text-bold">Basic Salary Information</span> <a href="/hr/employees/profile/index?employeeid={{$employeeid}}">now</a>!
        </div>

    </div>
    
</div>
<script>
  
  $('#netsalary').text('')
  $('#card-footer-computation').hide()
</script>

@endif