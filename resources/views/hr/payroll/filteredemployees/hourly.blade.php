
  
<script>
    var $ = jQuery;
    $(document).ready(function(){
        $(".filter").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".card").each(function() {
                if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                $(".card-group.card-group-filter:first-of-type").append($(this));
                $(this).hide();
                hiddenCards++;

                } else {

                $(".card-group.card-group-filter:last-of-type").prepend($(this));
                $(this).show();
                visibleCards++;

                if (((visibleCards % 4) == 0)) {
                    $(".container").append($("<div class='card-group card-group-filter'></div>"));
                }
                }
            });

        });
    })
</script>
  <div class="row mb-2 mt-2">
      <div class="col-md-3">
          <label>Search employee</label>
        <input class="filter form-control " placeholder="Search employee" />
      </div>
      <div class="col-md-9">
          <label>&nbsp;</label>
        <div class="form-group float-right">
            <div class="input-group">
              <div class="input-group-append">
                <span class="input-group-text">Export Summary</span>
              </div>
              <div class="input-group-append p-0">
                <span class="input-group-text p-0">
                    <button type="button" class="btn btn-default float-right m-0 exportsummary" style="display: block;" exporttype="pdf">PDF</button></span>
              </div>
              <div class="input-group-append p-0">
                <span class="input-group-text p-0">
                    <button type="button" class="btn btn-default float-right m-0 exportsummary" style="display: block;" exporttype="excel" disabled>Excel</button></span>
              </div>
            </div>
          </div>
      </div>
  </div>
    <div class="row d-flex align-items-stretch text-uppercase mt-3">
      @if(count($employees)>0)
        <div class="col-md-12">
            
            <table style="width: 100%;text-align: center;table-layout: fixed;font-size: 11px;">
                <thead>
                    <tr>
                        <th style="width: 20%;">Name</th>
                        <th>Per hour</th>
                        <th>Total # hours rendered</th>
                        <th>Basic Salary</th>
                        <th>Absences/<br/>Tardiness</th>
                        <th>Gross<br/>Salary<br/>Pay</th>
                        <th>Deductions</th>
                        <th>Allowances</th>
                        <th>Net Pay</th>
                        <th>Release</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
            </table>
            <br/>
        </div>
        @foreach($employees as $employee)
            
            @if($employee->released == 1)
                <div class="col-md-12">
                    <div class="card card-primary collapsed-card"  data-string="{{$employee->firstname}} {{$employee->middlename}} {{$employee->lastname}} {{$employee->utype}}<" data-id="{{$employee->employeeid}}">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <table style="width: 100%;table-layout: fixed;">
                                        <tbody>
                                            <tr>
                                                <td style="width: 20%;"> <strong>{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}}</strong></td>
                                                <td class="text-right">{{$employee->salarydetails->basicsalaryinfo->amount}}</td>
                                                <td class="text-right">
                                                    {{$employee->payrollhistory[0]->hoursrendered}}
                                                </td>
                                                <td class="text-right">{{$employee->salarydetails->basicsalaryinfo->payrollbasic}}</td>
                                                <td class="text-right text-warning">
                                                    {{$employee->payrollhistory[0]->daysabsentamount+$employee->payrollhistory[0]->lateamount}}
                                                </td>
                                                <td class="text-right text-success">{{$employee->payrollhistory[0]->grosssalarypay}}</td>
                                                <td class="text-right text-warning">{{$employee->totaldeductions}}</td>
                                                <td class="text-right text-success">{{$employee->totalallowances}}</td>
                                                <td class="text-right @if($employee->payrollhistory[0]->netpay > 0) text-success @else text-danger @endif">{{$employee->payrollhistory[0]->netpay}}</td>
                                                <td class="text-center">
                                                </td>
                                                <td>
                                                    
                                                    <div class="card-tools float-right">
                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus text-secondary"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand text-secondary"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body"  style="display: none;">
                            <div class="row mb-2">
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-sm btn-default printslip " exporttype="pdf"><i  class="fa">&#xf1c1;</i> PDF</button>
                                    <button type="button" class="btn btn-sm btn-default printslip " exporttype="excel"><i  class="fa">&#xf1c3;</i> EXCEL</button>
                                </div>
                            </div>
                            <div class="row text-muted">
                                <div class="col-md-4"  style=" font-size: 12px;">
                                    <div class="row">
                                        <div class="col-12">
                                            <label>Attendance</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            DAYS PRESENT 
                                        </div>
                                        <div class="col-md-3" >
                                            : {{$employee->payrollhistory[0]->dayspresent}} day(s)<br/>
                                            {{-- : {{$employee->payrollhistory[0]->dayspresent}} hour(s) --}}
                                        </div>
                                        <div class="col-md-6">
                                            : {{$employee->payrollhistory[0]->dayspresentamount}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            Tardiness (LATE)
                                        </div>
                                        <div class="col-md-3">
                                            : {{$employee->payrollhistory[0]->lateminutes}} min(s)
                                        </div>
                                        <div class="col-md-6">
                                            : {{$employee->payrollhistory[0]->lateamount}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            Tardiness (Undertime)
                                        </div>
                                        <div class="col-md-3" >
                                            : {{$employee->payrollhistory[0]->undertimeminutes}} min(s)
                                        </div>
                                        <div class="col-md-6" data-id="" >
                                            : 
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            DAYS ABSENT 
                                        </div>
                                        <div class="col-md-3">
                                            :  {{$employee->payrollhistory[0]->daysabsent}}  day(s)
                                        </div>
                                        <div class="col-md-6">
                                            : {{$employee->payrollhistory[0]->daysabsentamount}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            HOLIDAY
                                        </div>
                                        <div class="col-md-3">
                                            : {{$employee->payrollhistory[0]->holidaypay}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-12">
                                            
                                            <label>Deduction</label>
                                        </div>
                                    </div>
                                    @if(count($employee->payrollhistorydetail)>0)
                                        <div class="row">
                                            <div class="col-12">
                                                <small><strong>STANDARD DEDUCTION</strong></small>
                                            </div>
                                        </div>
                                        @foreach($employee->payrollhistorydetail as $standarddeduction)
                                            @if($standarddeduction->type == 'standard' && $standarddeduction->deductionid > 0)
                                                <div class="row">
                                                    <div class="col-6 ">
                                                        <small>{{$standarddeduction->deductiondesc}} </small>
                                                    </div>
                                                    <div class="col-6">
                                                        <small>{{$standarddeduction->amount}}</small>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    @if(count($employee->payrollhistorydetail)>0)
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <small><strong>Other DEDUCTION</strong></small>
                                            </div>
                                        </div>
                                        @foreach($employee->payrollhistorydetail as $otherdeduction)
                                            @if($otherdeduction->type == 'other' && $otherdeduction->deductionid > 0)
                                                <div class="row">
                                                    <div class="col-6 ">
                                                        <small>{{$otherdeduction->deductiondesc}}</small>
                                                    </div>
                                                    <div class="col-6">
                                                        <small>{{$otherdeduction->amount}}</small>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <label>Allowance</label>
                                        </div>
                                    </div>
                                    @if(count($employee->payrollhistorydetail)>0)
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <small><strong>STANDARD ALLOWANCE</strong></small>
                                            </div>
                                        </div>
                                        @foreach($employee->payrollhistorydetail as $standardallowance)
                                            @if($standardallowance->type == 'standard' && $standardallowance->allowanceid > 0)
                                                <div class="row">
                                                    <div class="col-6 ">
                                                        <small>{{$standardallowance->allowancedesc}}</small>
                                                    </div>
                                                    <div class="col-6">
                                                        <small>{{$standardallowance->amount}}</small>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    {{-- <div class="row mt-2">
                                        <div class="col-12">
                                            <small><strong>Other allowance</strong></small>
                                        </div>
                                    </div> --}}
                                    @if(count($employee->payrollhistorydetail)>0)
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <small><strong>Other Allowance(s)</strong></small>
                                            </div>
                                        </div>
                                        @foreach($employee->payrollhistorydetail as $otherallowance)
                                            @if($otherallowance->type == 'other' && $otherallowance->allowanceid > 0)
                                                <div class="row">
                                                    <div class="col-6 ">
                                                        <small>{{$otherallowance->allowancedesc}}</small>
                                                    </div>
                                                    <div class="col-6">
                                                        <small>{{$otherallowance->amount}}</small>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    @if(count($employee->payrollhistorydetail)>0)
                                        <div class="row text-muted text-uppercase mt-2">
                                            <div class="col-md-12">
                                                <small><strong>LEAVES</strong></small>
                                            </div>
                                        </div>
                                        @foreach($employee->payrollhistorydetail as $leavedetail)
                                            @if($leavedetail->employeeleaveid > 0)
                                                <div class="row text-muted text-uppercase">
                                                    <div class="col-md-3 text-right" data-id="{{$leavedetail->employeeleaveid}}">
                                                        <small>{{$leavedetail->description}}</small>
                                                    </div>
                                                    <div class="col-md-3" data-id="{{$leavedetail->days}}">
                                                        <small>: {{$leavedetail->days}} day(s)  </small>
                                                        
                                                    </div>
                                                    <div class="col-md-6 text-success" >
                                                        <small>: <strong>{{$leavedetail->amount}}</strong>  </small>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    @if(count($employee->payrollhistorydetail)>0)
                                        <div class="row text-muted text-uppercase mt-2">
                                            <div class="col-md-12">
                                                <small><strong>OVERTIME</strong></small>
                                            </div>
                                        </div>
                                        @foreach($employee->payrollhistorydetail as $overtimedetail)
                                            @if($overtimedetail->employeeovertimeid > 0)
                                                <div class="row text-muted text-uppercase">
                                                    <div class="col-md-3 text-right">
                                                        <small>{{$overtimedetail->description}}</small>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <small>: {{$overtimedetail->overtimehours}} hour(s)  </small>
                                                        
                                                    </div>
                                                    <div class="col-md-6 text-success" >
                                                        <small>: <strong>{{$overtimedetail->amount}}</strong>  </small>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    {{-- @if(count($employee->salarydetails->overtimedetails)>0)
                                        <div class="row text-muted text-uppercase mt-2">
                                            <div class="col-md-12">
                                                <small><strong>OVERTIME</strong></small>
                                            </div>
                                        </div>
                                        @foreach($employee->salarydetails->overtimedetails as $overtimedetail)
                                            <div class="row text-muted text-uppercase employeeovertimeid" data-id="{{$overtimedetail->id}}" >
                                                <div class="col-md-3 ">
                                                    <small>Regular</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <small>: {{$overtimedetail->dailyovertimehours}} hour(s)  </small>
                                                </div>
                                                    <div class="col-md-6 text-success overtimepay" data-id="{{$overtimedetail->overtimesalary}}">
                                                        <small>: <strong>{{$overtimedetail->overtimesalary}}</strong>  </small>
                                                    </div>
                                            </div>
                                            <div class="row text-muted text-uppercase">
                                                <div class="col-md-3 ">
                                                    <small>Holiday</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <small>: {{$overtimedetail->dailyovertimehours}} hour(s)  </small>
                                                </div>
                                                    <div class="col-md-6 text-success holidayovertimepay" data-id="{{$overtimedetail->holidayovertimepay}}">
                                                        <small>: <strong>{{$overtimedetail->holidayovertimepay}}</strong>  </small>
                                                    </div>
                                            </div>
                                        @endforeach
                                    @endif --}}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-12">
                    <div class="card card-primary collapsed-card"  data-string="{{$employee->firstname}} {{$employee->middlename}} {{$employee->lastname}} {{$employee->utype}}<" data-id="{{$employee->employeeid}}">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <table style="width: 100%;table-layout: fixed;">
                                        <tbody>
                                            <tr>
                                                <td style="width: 20%;"> <strong>{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}}</strong></td>
                                                <td class="text-center">{{$employee->salarydetails->basicsalaryinfo->amount}}</td>
                                                <td class="text-center hoursrendered" data-id="{{$employee->salarydetails->attendancedetails->hoursrendered}}">{{$employee->salarydetails->attendancedetails->hoursrendered}}</td>
                                                <td class="text-center">{{$employee->salarydetails->basicsalaryinfo->payrollbasic}}</td>
                                                <td class="text-center text-warning">{{$employee->absencesandtardiness}}</td>
                                                <td class="text-center text-success grosssalary" data-id="{{$employee->grosssalarypay}}">{{$employee->grosssalarypay}}</td>
                                                <td class="text-center text-warning">{{$employee->totaldeductions}}</td>
                                                <td class="text-center text-success">{{$employee->totalallowances}}</td>
                                                <td class="text-center @if($employee->netpay > 0) text-success @else text-danger @endif">{{$employee->netpay}}</td>
                                                <td class="text-center">
                                                    @php
                                                        $overrideconfigured = 0;
                                                    @endphp
                                                    @if(count($employee->salarydetails->standarddeductions)>0)
                                                        @if($employee->salarydetails->standarddeductions[0]->fullamount>0)
                                                            @php
                                                                $overrideconfigured+=1;
                                                            @endphp
                                                        @endif
                                                    @endif
                                                    @if(count($employee->salarydetails->otherdeductions)>0)
                                                        @if($employee->salarydetails->otherdeductions[0]->fullamount>0)
                                                            @php
                                                                $overrideconfigured+=1;
                                                            @endphp
                                                        @endif
                                                    @endif
                                                    @if(count($employee->salarydetails->standardallowances)>0)
                                                        @if($employee->salarydetails->standardallowances[0]->fullamount>0)
                                                            @php
                                                                $overrideconfigured+=1;
                                                            @endphp
                                                        @endif
                                                    @endif
                                                    @if(count($employee->salarydetails->otherallowances)>0)
                                                        @if($employee->salarydetails->otherallowances[0]->fullamount>0)
                                                            @php
                                                                $overrideconfigured+=1;
                                                            @endphp
                                                        @endif
                                                    @endif
                                                    {{-- {{$overrideconfigured}} --}}
                                                    @if(count($employee->salarydetails->standarddeductions)>0 || count($employee->salarydetails->otherdeductions)>0 || count($employee->salarydetails->standardallowances)>0 || count($employee->salarydetails->otherallowances)>0 || count($employee->salarydetails->leavedetails)>0)
                                                        @if($employee->configured == 1)
                                                            @if($employee->released == 1)
                                                            <button type="button" class="btn btn-sm btn-default printslip " exporttype="pdf"><i  class="fa">&#xf1c1;</i> PDF</button>
                                                            <button type="button" class="btn btn-sm btn-default printslip " exporttype="excel"><i  class="fa">&#xf1c3;</i> EXCEL</button>
                                                            @else
                                                                <button type="button" class="btn btn-sm btn-success releaseslip " exporttype="pdf"><i  class="fa">&#xf1c1;</i> PDF</button>
                                                                <button type="button" class="btn btn-sm btn-success releaseslip " exporttype="excel"><i  class="fa">&#xf1c3;</i> EXCEL</button>
                                                            @endif
                                                        @else
                                                            @if($overrideconfigured>0)
                                                                <button type="button" class="btn btn-sm btn-secondary btn-block" disabled>Not yet configured</button>
                                                            @else
                                                                <button type="button" class="btn btn-sm btn-success releaseslip " exporttype="pdf"><i  class="fa">&#xf1c1;</i> PDF</button>
                                                                <button type="button" class="btn btn-sm btn-success releaseslip " exporttype="excel"><i  class="fa">&#xf1c3;</i> EXCEL</button>
                                                            @endif
                                                        @endif
                                                    @else
                                                        @if($employee->released == 1)
                                                        <button type="button" class="btn btn-sm btn-default printslip " exporttype="pdf"><i  class="fa">&#xf1c1;</i> PDF</button>
                                                        <button type="button" class="btn btn-sm btn-default printslip " exporttype="excel"><i  class="fa">&#xf1c3;</i> EXCEL</button>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-success releaseslip " exporttype="pdf"><i  class="fa">&#xf1c1;</i> PDF</button>
                                                            <button type="button" class="btn btn-sm btn-success releaseslip " exporttype="excel"><i  class="fa">&#xf1c3;</i> EXCEL</button>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    
                                                    <div class="card-tools float-right">
                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus text-secondary"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand text-secondary"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body" style="display: none;">
                            <div class="row text-muted">
                                <div class="col-md-4"  style=" font-size: 12px;">
                                    <div class="row">
                                        <div class="col-12">
                                            
                                            <label>Attendance</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            DAYS PRESENT 
                                        </div>
                                        <div class="col-md-3 dayspresent" data-id="{{count($employee->salarydetails->attendancedetails->attendancepresent)}}" >
                                            : {{count($employee->salarydetails->attendancedetails->attendancepresent)}} day(s)<br/>: {{$employee->salarydetails->attendancedetails->presentminutes/60}} hour(s)
                                        </div>
                                        <div class="col-md-6 dayspresentamount" data-id="{{$employee->salarydetails->attendancedetails->attendanceearnings}}" >
                                            : {{$employee->salarydetails->attendancedetails->attendanceearnings}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            Tardiness (LATE)
                                        </div>
                                        <div class="col-md-3 lateminutes" data-id="{{$employee->salarydetails->attendancedetails->lateamin+$employee->salarydetails->attendancedetails->latepmin}}">
                                            : {{$employee->salarydetails->attendancedetails->lateamin+$employee->salarydetails->attendancedetails->latepmin}} min(s)
                                        </div>
                                        <div class="col-md-6 lateamount" data-id="{{$employee->salarydetails->attendancedetails->latedeductionamount}}">
                                            : {{$employee->salarydetails->attendancedetails->latedeductionamount}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            Tardiness (Undertime)
                                        </div>
                                        <div class="col-md-3 undertimeminutes" data-id="{{$employee->salarydetails->attendancedetails->undertimeamout+$employee->salarydetails->attendancedetails->undertimepmout}}">
                                            : {{$employee->salarydetails->attendancedetails->undertimeamout+$employee->salarydetails->attendancedetails->undertimepmout}} min(s)
                                        </div>
                                        <div class="col-md-6 undertimeamount" data-id="" >
                                            : 
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            DAYS ABSENT 
                                        </div>
                                        <div class="col-md-3 daysabsent" data-id="{{count($employee->salarydetails->attendancedetails->attendanceabsent)}}">
                                            : {{count($employee->salarydetails->attendancedetails->attendanceabsent)}} day(s)
                                        </div>
                                        <div class="col-md-6 daysabsentamount" data-id="{{$employee->salarydetails->attendancedetails->attendancedeductions}}">
                                            : {{$employee->salarydetails->attendancedetails->attendancedeductions}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            HOLIDAY
                                        </div>
                                        <div class="col-md-3 holidaypay" data-id="{{$employee->salarydetails->attendancedetails->holidaypay}}">
                                            : {{$employee->salarydetails->attendancedetails->holidaypay}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-12">
                                            
                                            <label>Deduction</label>
                                        </div>
                                    </div>
                                    @if(count($employee->salarydetails->standarddeductions)>0)
                                        <div class="row">
                                            <div class="col-12">
                                                <small><strong>STANDARD DEDUCTION</strong></small>
                                            </div>
                                        </div>
                                        @foreach($employee->salarydetails->standarddeductions[0]->standarddeductions as $standarddeduction)
                                        <div class="row">
                                            <div class="col-6 ">
                                                <small>{{$standarddeduction->description}} </small>
                                            </div>
                                            <div class="col-6">
                                                
                                                @if($standarddeduction->forcefull == 1 || $employee->salarydetails->standarddeductions[0]->payrollstatus == 1)
                                                <small>Paid for this month ({{$standarddeduction->paidamount}})</small>
                                                @else
                                                <small>{{$standarddeduction->eesamount}}</small>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                    @if(count($employee->salarydetails->otherdeductions)>0)
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <small><strong>Other DEDUCTION</strong></small>
                                            </div>
                                        </div>
                                        @foreach($employee->salarydetails->otherdeductions[0]->otherdeductions as $otherdeduction)
                                        <div class="row">
                                            <div class="col-6 ">
                                                <small>{{$otherdeduction->description}}</small>
                                            </div>
                                            <div class="col-6">
                                                
                                                @if($otherdeduction->amount < 1)
                                                <small>Paid for this month ({{$otherdeduction->paidamount}})</small>
                                                @else
                                                <small>{{$otherdeduction->amount}}</small>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <label>Allowance</label>
                                        </div>
                                    </div>
                                    @if(count($employee->salarydetails->standardallowances)>0)
                                        <div class="row">
                                            <div class="col-12">
                                                <small><strong>STANDARD allowance</strong></small>
                                            </div>
                                        </div>
                                        @foreach($employee->salarydetails->standardallowances[0]->standardallowances as $standardallowance)
                                        <div class="row">
                                            <div class="col-6">
                                                <small>{{$standardallowance->description}}</small>
                                            </div>
                                            <div class="col-6">
                                                
                                                @if($employee->salarydetails->standardallowances[0]->payrollstatus == 1)
                                                <small>Paid for this month</small>
                                                @else
                                                <small>{{$standardallowance->eesamount}}</small>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <small><strong>Other allowance</strong></small>
                                        </div>
                                    </div>
                                    @if(count($employee->salarydetails->otherallowances)>0)
                                        @foreach($employee->salarydetails->otherallowances[0]->otherallowances as $otherallowance)
                                        <div class="row">
                                            <div class="col-6 ">
                                                <small>{{$otherallowance->description}}</small>
                                            </div>
                                            <div class="col-6">
                                                {{-- <small>{{$otherallowance->amount}}</small> --}}
                                                @if($employee->salarydetails->otherallowances[0]->payrollstatus == 1)
                                                    <small>Paid for this month</small>
                                                @else
                                                    <small>{{$otherallowance->amount}}</small>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                    @if(count($employee->salarydetails->leavedetails)>0)
                                        <div class="row text-muted text-uppercase mt-2">
                                            <div class="col-md-12">
                                                <small><strong>LEAVES</strong></small>
                                            </div>
                                        </div>
                                        @foreach($employee->salarydetails->leavedetails as $leavedetail)
                                            {{-- @php
                                            array_push($leaveids, $leavedetail->leaveid);
                                            array_push($leavedays, $leavedetail->noofdays);
                                            array_push($leaveamount, $leavedetail->amountearn);
                                            @endphp --}}
                                            <div class="row text-muted text-uppercase">
                                                <div class="col-md-3 text-right leaveids" data-id="{{$leavedetail->employeeleaveid}}">
                                                    <small>{{$leavedetail->leave_type}}</small>
                                                </div>
                                                <div class="col-md-3 leavedays" data-id="{{$leavedetail->days}}">
                                                    <small>: {{$leavedetail->days}} day(s)  </small>
                                                    
                                                </div>
                                                {{-- @if($leavedetail->status == 1) --}}
                                                    <div class="col-md-6 text-success leaveamounts"  data-id="{{$leavedetail->amount}}">
                                                        <small>: <strong>{{$leavedetail->amount}}</strong>  </small>
                                                    </div>
                                                {{-- @else
                                                    <div class="col-md-6 text-success">
                                                        <small>:<strong> {{$leavedetail->amountdeduct}}</strong>  </small>
                                                    </div>
                                                @endif --}}
                                            </div>
                                        @endforeach
                                    @endif
                                    @if(count($employee->salarydetails->overtimedetails)>0)
                                        <div class="row text-muted text-uppercase mt-2">
                                            <div class="col-md-12">
                                                <small><strong>OVERTIME</strong></small>
                                            </div>
                                        </div>
                                        @foreach($employee->salarydetails->overtimedetails as $overtimedetail)
                                            <div class="row text-muted text-uppercase employeeovertimeid" data-id="{{$overtimedetail->id}}" >
                                                <div class="col-md-3 ">
                                                    <small>Regular</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <small>: {{$overtimedetail->dailyovertimehours}} hour(s)  </small>
                                                </div>
                                                    <div class="col-md-6 text-success overtimepay" data-id="{{$overtimedetail->overtimesalary}}">
                                                        <small>: <strong>{{$overtimedetail->overtimesalary}}</strong>  </small>
                                                        {{-- @php
                                                            $td+=$leavedetail->amountdeduct;
                                                        @endphp --}}
                                                    </div>
                                            </div>
                                            <div class="row text-muted text-uppercase">
                                                <div class="col-md-3 ">
                                                    <small>Holiday</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <small>: {{$overtimedetail->dailyovertimehours}} hour(s)  </small>
                                                </div>
                                                    <div class="col-md-6 text-success holidayovertimepay" data-id="{{$overtimedetail->holidayovertimepay}}">
                                                        <small>: <strong>{{$overtimedetail->holidayovertimepay}}</strong>  </small>
                                                    </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
      @endif
    </div>
    <script>
        $('.releaseslip').on('click', function(){
            
            var thiscard            = $(this).closest('.card');
            var payrollid           = $('#selectedpayrollid').val();
            var employeeid          = thiscard.attr('data-id');
            var dayspresent         = thiscard.find('.dayspresent').attr('data-id') ;
            var dayspresentamount   = thiscard.find('.dayspresentamount').attr('data-id') ;
            var lateminutes         = thiscard.find('.lateminutes').attr('data-id') ;
            var lateamount          = thiscard.find('.lateamount').attr('data-id') ;
            var undertimeminutes    = thiscard.find('.undertimeminutes').attr('data-id') ;
            var undertimeamount     = thiscard.find('.undertimeamount').attr('data-id') ;
            var daysabsent          = thiscard.find('.daysabsent').attr('data-id') ;
            var daysabsentamount    = thiscard.find('.daysabsentamount').attr('data-id') ;
            var holidaypay          = thiscard.find('.holidaypay').attr('data-id') ;
            var hoursrendered       = thiscard.find('.hoursrendered').attr('data-id') ;
            var grosssalary         = thiscard.find('.grosssalary').attr('data-id') ;

            // var overtimepay         = thiscard.find('.overtimepay').attr('data-id') ;
            var overtimepay         = [];
            if(thiscard.find('.overtimepay').length > 0)
            {
                thiscard.find('.overtimepay').each(function(){
                    overtimepay.push($(this).attr('data-id'))
                })
            }
            // var holidayovertimepay  = thiscard.find('.holidayovertimepay').attr('data-id') ;
            var holidayovertimepay         = [];
            if(thiscard.find('.holidayovertimepay').length > 0)
            {
                thiscard.find('.holidayovertimepay').each(function(){
                    holidayovertimepay.push($(this).attr('data-id'))
                })
            }
            var employeeovertimeids         = [];
            if(thiscard.find('.employeeovertimeid').length > 0)
            {
                thiscard.find('.employeeovertimeid').each(function(){
                    employeeovertimeids.push($(this).attr('data-id'))
                })
            }
            
            var leaveids            = thiscard.find('.leaveids').attr('data-id');
            var leavedays           = thiscard.find('.leavedays').attr('data-id');
            var leaveamount         = thiscard.find('.leaveamounts').attr('data-id');
            var paramet = {
                payrollid           :   payrollid,
                employeeid          :   employeeid,
                dayspresent         :   dayspresent,
                dayspresentamount   :   dayspresentamount,
                lateminutes         :   lateminutes,
                lateamount          :   lateamount,
                undertimeminutes    :   undertimeminutes,
                undertimeamount     :   undertimeamount,
                daysabsent          :   daysabsent,
                daysabsentamount    :   daysabsentamount,
                holidaypay          :   holidaypay,
                employeeovertimeids :   employeeovertimeids,
                overtimepay         :   overtimepay,
                holidayovertimepay  :   holidayovertimepay,
                leaveids            :   leaveids,
                leavedays           :   leavedays,
                leaveamount         :   leaveamount,
                hoursrendered       :   hoursrendered,
                grosssalary         :   grosssalary
            }
            
            var exporttype = $(this).attr('exporttype');
            Swal.fire({
                title: 'Once released, you can never reconfigure the pay again!',
                type: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Release',
                showCancelButton: true,
                allowOutsideClick: false
            }).then((confirm) => {
                    if (confirm.value) {
                        window.open("/hr/payrollsummary/releaseslipsingle?exporttype="+exporttype+"&"+$.param(paramet));
                    }
                })

        })
        $('.printslip').on('click', function(){
            var payrollid           = $('#selectedpayrollid').val()
            var employeeid          = $(this).closest('.card').attr('data-id')
            var exporttype          = $(this).attr('exporttype');
            window.open("/hr/payrollsummary/viewslip?exporttype="+exporttype+"&payrollid="+payrollid+"&employeeid="+employeeid+"&exportclass=single");
        })
        $('.exportsummary').on('click', function(){
            var selectedpayrollid           = $('#selectedpayrollid').val()
            var selecteddepartmentid        = $('#selecteddepartmentid').val()
            var selectedemploymentstatusid  = $('#selectedemploymentstatusid').val()
            var selectedsalarytypeid        = $('input[name="basistype"]:checked').val()
            var exporttype = $(this).attr('exporttype')
            var paramet = {
                exporttype                 :   exporttype,
                selectedpayrollid          :   selectedpayrollid,
                selecteddepartmentid       :   selecteddepartmentid,
                selectedemploymentstatusid :   selectedemploymentstatusid,
                selectedsalarytypeid       :   selectedsalarytypeid
            }
            window.open("/hr/payrollsummary/exportsummary?"+$.param(paramet));
        })
    </script>