
  
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
  </div>
    <div class="row d-flex align-items-stretch text-uppercase mt-3">
      @if(count($employees)>0)
        <div class="col-md-12">
            
            <table style="width: 100%;text-align: center;table-layout: fixed;">
                <thead>
                    <tr>
                        <th style="width: 20%;">Name</th>
                        <th>Basic Salary</th>
                        <th>Absences/Tardiness</th>
                        <th>Gross Salary Pay</th>
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
        <div class="col-md-12">
            <div class="card card-primary collapsed-card"  data-string="{{$employee->firstname}} {{$employee->middlename}} {{$employee->lastname}} {{$employee->utype}}<" data-id="{{$employee->employeeid}}">
                <div class="card-header">
                    {{-- <div class="row">
                        <div class="col-md-11">
                            <table class="table m-0 p-0">
                                <thead>
                                    <tr>
                                        <th style="width: 60%;"> <strong>{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}}</strong></th>
                                        <th>{{$employee->salarydetails->basicsalaryinfo->basicsalary}}</th>
                                        <th>{{$employee->salarydetails->attendancedetails->attendancedeductions + $employee->salarydetails->attendancedetails->latedeductionamount}}</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-md-1">
                            
                  <div class="card-tools float-right">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus text-secondary"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand text-secondary"></i>
                    </button>
                  </div>
                        </div>
                    </div> --}}
                {{-- <div class="card-title"> --}}
                    <div class="row">
                        <div class="col-12">
                            <table style="width: 100%;table-layout: fixed;">
                                <tbody>
                                    <tr>
                                        <td style="width: 20%;"> <strong>{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}}</strong></td>
                                        <td class="text-center">{{$employee->salarydetails->basicsalaryinfo->basicsalary}}</td>
                                        <td class="text-center text-warning">{{$employee->absencesandtardiness}}</td>
                                        <td class="text-center text-success">{{$employee->grosssalarypay}}</td>
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
                {{-- </div> --}}

                  {{-- <h3 class="card-title">{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}}</h3> --}}
  
                  <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body" style="display: none;">
                    <div class="row text-muted">
                        <div class="col-md-4">
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
                                     ({{count($employee->salarydetails->attendancedetails->attendancepresent)}} day(s))
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
                                    {{$employee->salarydetails->attendancedetails->lateamin+$employee->salarydetails->attendancedetails->latepmin}} min(s)
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
                                    {{$employee->salarydetails->attendancedetails->undertimeamout+$employee->salarydetails->attendancedetails->undertimepmout}} min(s)
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
                                     ({{count($employee->salarydetails->attendancedetails->attendanceabsent)}} day(s))
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
                                        <small>Paid for this month</small>
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
                                        <small>Paid for this month</small>
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
                                            @php
                                                $configure+=1;   
                                            @endphp
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
                                        <div class="col-md-3 text-right leaveids" data-id="{{$leavedetail->leaveid}}">
                                            <small>{{$leavedetail->description}}</small>
                                        </div>
                                        <div class="col-md-3 leavedays" data-id="{{$leavedetail->noofdays}}">
                                            <small>: {{$leavedetail->noofdays}} day(s)  </small>
                                            
                                        </div>
                                        @if($leavedetail->status == 1)
                                            <div class="col-md-6 text-success leaveamounts"  data-id="{{$leavedetail->amountearn}}">
                                                <small>: <strong>{{$leavedetail->amountearn}}</strong>  </small>
                                            </div>
                                        @else
                                            <div class="col-md-6 text-success">
                                                <small>:<strong> {{$leavedetail->amountdeduct}}</strong>  </small>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                            @if(count($employee->salarydetails->overtimedetails)>0)
                                <div class="row text-muted text-uppercase mt-2">
                                    <div class="col-md-12">
                                        <small><strong>OVERTIME</strong></small>
                                    </div>
                                </div>
                                {{-- @foreach($employee->salarydetails->overtimedetails as $overtimedetail) --}}
                                    <div class="row text-muted text-uppercase">
                                        <div class="col-md-3 ">
                                            <small>Regular</small>
                                        </div>
                                        <div class="col-md-3">
                                            <small>: {{$employee->salarydetails->overtimedetails[0]->dailyovertimehours}} hour(s)  </small>
                                        </div>
                                            <div class="col-md-6 text-success overtimepay" data-id="{{$employee->salarydetails->overtimedetails[0]->overtimesalary}}">
                                                <small>: <strong>{{$employee->salarydetails->overtimedetails[0]->overtimesalary}}</strong>  </small>
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
                                            <small>: {{$employee->salarydetails->overtimedetails[0]->dailyovertimehours}} hour(s)  </small>
                                        </div>
                                            <div class="col-md-6 text-success holidayovertimepay" data-id="{{$employee->salarydetails->overtimedetails[0]->holidayovertimepay}}">
                                                <small>: <strong>{{$employee->salarydetails->overtimedetails[0]->holidayovertimepay}}</strong>  </small>
                                            </div>
                                    </div>
                                {{-- @endforeach --}}
                            @endif
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
              </div>
        </div>
        @endforeach
      @endif
    </div>
    <script>
        $('.releaseslip').on('click', function(){
            
            var thiscard            = $(this).closest('.card');
            var payrollid           = $('#selectedpayrollid').val()
            var employeeid          = thiscard.attr('data-id')
            var dayspresent         = thiscard.find('.dayspresent').attr('data-id') ;
            var dayspresentamount   = thiscard.find('.dayspresentamount').attr('data-id') ;
            var lateminutes         = thiscard.find('.lateminutes').attr('data-id') ;
            var lateamount          = thiscard.find('.lateamount').attr('data-id') ;
            var undertimeminutes    = thiscard.find('.undertimeminutes').attr('data-id') ;
            var undertimeamount     = thiscard.find('.undertimeamount').attr('data-id') ;
            var daysabsent          = thiscard.find('.daysabsent').attr('data-id') ;
            var daysabsentamount    = thiscard.find('.daysabsentamount').attr('data-id') ;
            var holidaypay          = thiscard.find('.holidaypay').attr('data-id') ;
            var overtimepay         = thiscard.find('.overtimepay').attr('data-id') ;
            var holidayovertimepay  = thiscard.find('.holidayovertimepay').attr('data-id') ;
            var leaveids            = thiscard.find('.leaveids').attr('data-id');
            var leavedays           = thiscard.find('.leavedays').attr('data-id');
            var leaveamount         = thiscard.find('.leaveamounts').attr('data-id');
            var paramet = {
                    payrollid          :   payrollid,
                    employeeid          :   employeeid,
                    dayspresent          :   dayspresent,
                    dayspresentamount          :   dayspresentamount,
                    lateminutes          :   lateminutes,
                    lateamount          :   lateamount,
                    undertimeminutes          :   undertimeminutes,
                    undertimeamount          :   undertimeamount,
                    daysabsent          :   daysabsent,
                    daysabsentamount          :   daysabsentamount,
                    holidaypay          :   holidaypay,
                    overtimepay          :   overtimepay,
                    holidayovertimepay          :   holidayovertimepay,
                    leaveids          :   leaveids,
                    leavedays          :   leavedays,
                    leaveamount          :   leaveamount
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
    </script>