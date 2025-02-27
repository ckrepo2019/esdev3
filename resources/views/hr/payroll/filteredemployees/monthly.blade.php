
  
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
      <div class="col-md-6">
          <label>Search employee</label>
        <input class="filter form-control " placeholder="Search employee" />
      </div>
      <div class="col-md-6">
          <label>&nbsp;</label><br/>
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
  <hr/>
    <div class="row d-flex align-items-stretch text-uppercase mt-3">
      @if(count($employees)>0)
        @foreach($employees as $employee)
        @if($employee->released == 1)
            <div class="col-md-12">
                <div class="card card-primary collapsed-card mb-0"  data-string="{{$employee->firstname}} {{$employee->middlename}} {{$employee->lastname}} {{$employee->utype}}<" data-id="{{$employee->employeeid}}">
                    <div class="card-header pb-0">
                        <div class="row mb-2">
                            <div class="col-md-8"><strong>{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}}</strong></div>
                            <div class="col-md-4">
                                <div class="card-tools float-right">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus text-secondary"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand text-secondary"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <table class="table" style="font-size: 12px;">
                                <tr>
                                    <th class="p-0">Basic Salaray</th>
                                    <th class="p-0 text-right">Gross Pay</th>
                                    <th class="p-0 text-right">Total Earnings</th>
                                    <th class="p-0 text-right">Total Deductions</th>
                                    <th class="p-0 text-right">Net Pay</th>
                                    <th class="text-right" rowspan="2"><button type="button" class="btn btn-sm btn-default printslip " exporttype="pdf"><i  class="fa">&#xf1c1;</i> PDF</button></th>
                                </tr>
                                <tr>
                                    <td class="p-0">{{number_format($employee->salarydetails->basicsalaryinfo->payrollbasic,2)}}</td>
                                    <td class="p-0 grosspay text-right" data-grosspay="{{number_format($employee->payrollhistory[0]->grosssalarypay,2)}}">{{number_format($employee->payrollhistory[0]->grosssalarypay,2)}}</td>
                                    <td class="p-0 text-right">{{number_format($employee->totalallowances,2)}}</td>
                                    <td class="p-0 text-right">{{number_format($employee->totaldeductions,2)}}</td>
                                    <td class="p-0  text-right @if($employee->payrollhistory[0]->netpay > 0) text-success @else text-danger @endif" style="font-size: 15px;"><u>{{number_format($employee->payrollhistory[0]->netpay,2)}}</u></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="card-body p-0" style="display: none; font-size: 12px;">
                        <table class="table m-0" style="table-layout: fixed;">
                            <thead class="text-center">
                                <tr>
                                    <th>Attendance</th>
                                    <th>Allowances</th>
                                    <th>Deductions</th>
                                    <th>Others</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="p-0">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td class="p-0">DAYS PRESENT</td>
                                                <td class="p-0 dayspresent" data-id="{{$employee->payrollhistory[0]->dayspresent}}">{{$employee->payrollhistory[0]->dayspresent}} day(s)</td>
                                                <td class="p-0 dayspresentamount text-right" data-id="{{$employee->payrollhistory[0]->dayspresentamount}}">{{$employee->payrollhistory[0]->dayspresentamount}}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0">Tardiness (LATE)</td>
                                                <td class="p-0 lateminutes" data-id="{{$employee->payrollhistory[0]->lateminutes}}">{{$employee->payrollhistory[0]->lateminutes}} min(s)</td>
                                                <td class="p-0 lateamount text-right" data-id="{{$employee->payrollhistory[0]->lateamount}}">{{$employee->payrollhistory[0]->lateamount}}</td>
                                            </tr>
                                            {{-- <tr>
                                                <td class="p-0">Tardiness (Undertime)</td>
                                                <td class="p-0 undertimeminutes" data-id="{{$employee->payrollhistory[0]->undertimeminutes}}">{{$employee->payrollhistory[0]->undertimeminutes}} min(s)</td>
                                                <td class="p-0 undertimeamount text-right">
                                                    
                                                </td>
                                            </tr> --}}
                                            <tr>
                                                <td class="p-0">DAYS ABSENT</td>
                                                <td class="p-0 daysabsent" data-id="{{$employee->payrollhistory[0]->daysabsent}}">{{$employee->payrollhistory[0]->daysabsent}} day(s)</td>
                                                <td class="p-0 daysabsentamount text-right" data-id="{{$employee->payrollhistory[0]->daysabsentamount}}">{{$employee->payrollhistory[0]->daysabsentamount}}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0">HOLIDAY</td>
                                                <td colspan="2" class="p-0 holidaypay" data-id="{{$employee->payrollhistory[0]->holidaypay}}">{{$employee->payrollhistory[0]->holidaypay}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="p-0">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center p-0">Standard Allowances</th>
                                            </tr>
                                            @if(collect($employee->payrollhistorydetail)->where('type','standard')->where('allowanceid','>','0')->count()>0)
                                                @foreach(collect($employee->payrollhistorydetail)->where('type','standard')->where('allowanceid','>','0')->values() as $standardallowance)
                                                <tr>
                                                    <td class="p-0">{{$standardallowance->allowancedesc}}</td>
                                                    <td class="p-0">
                                                        {{$standardallowance->amount}}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2" class="p-0 text-center">None</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th colspan="2" class="text-center p-0">Other Allowances</th>
                                            </tr>
                                            @if(collect($employee->payrollhistorydetail)->where('type','other')->where('allowanceid','>','0')->count()>0)
                                                @foreach(collect($employee->payrollhistorydetail)->where('type','other')->where('allowanceid','>','0')->values() as $otherallowance)
                                                    <tr>
                                                        <td class="p-0">{{$otherallowance->allowancedesc}}</td>
                                                        <td class="p-0">
                                                            {{$otherallowance->amount}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2" class="p-0 text-center">None</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                    <td class="p-0">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center p-0">Standard Deductions</th>
                                            </tr>
                                            @if(collect($employee->payrollhistorydetail)->where('type','standard')->count()>0)
                                                @foreach(collect($employee->payrollhistorydetail)->where('type','standard')->where('deductionid','>','0')->values() as $standarddeduction)
                                                    <tr>
                                                        <td class="p-0">{{$standarddeduction->deductiondesc}}</td>
                                                        <td class="p-0">
                                                            @if($standarddeduction->paymentoption < 1) {{--|| $employee->salarydetails->standarddeductions[0]->payrollstatus == 1)--}}
                                                            <small>Paid for this month ({{$standarddeduction->amount}})</small>
                                                            @else
                                                            <small>Paid half for this month ({{$standarddeduction->amount}})</small>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2" class="p-0 text-center">None</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th colspan="2" class="text-center p-0">Other Deductions</th>
                                            </tr>
                                            @if(collect($employee->payrollhistorydetail)->where('type','other')->count()>0)
                                                @foreach(collect($employee->payrollhistorydetail)->where('type','other')->where('deductionid','>','0')->values() as $otherdeduction)
                                                <tr>
                                                    <td class="p-0">{{$otherdeduction->deductiondesc}}</td>
                                                    <td class="p-0">
                                                        @if($otherdeduction->amount < 1)
                                                        <small>Paid for this month ({{$otherdeduction->paidamount}})</small>
                                                        @else
                                                        <small>Paid half for this month ({{$otherdeduction->amount}})</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2" class="p-0 text-center">None</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                    <td class="p-0">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center p-0">LEAVES</th>
                                            </tr>
                                            @if(count($employee->payrollhistorydetail)>0)
                                                @php
                                                    $leaves = array();
                                                    if(collect($employee->payrollhistorydetail)->where('employeeleaveid','>','0')->count() > 0)
                                                    {
                                                        foreach(collect($employee->payrollhistorydetail)->where('employeeleaveid','>','0')->values() as $leave)
                                                        {
                                                            $leaveinfo = DB::table('employee_leavesdetail')
                                                                ->join('employee_leaves', 'employee_leavesdetail.headerid','=','employee_leaves.id')
                                                                ->join('hr_leaves', 'employee_leaves.leaveid','=','hr_leaves.id')
                                                                ->select('hr_leaves.id','hr_leaves.leave_type','employee_leavesdetail.ldate','employee_leavesdetail.id as ldateid')
                                                                ->first();

                                                            if($leaveinfo)
                                                            {   
                                                                $leaveinfo->amount = $leave->amount;
                                                                array_push($leaves, $leaveinfo);
                                                            }
                                                        }
                                                    }
                                                    $leavedetails = collect($leaves)->groupBy('leave_type');
                                                @endphp
                                                @foreach($leavedetails as $leavetype => $leavedetail)
                                                    <tr>
                                                        <td colspan="2" class="p-0 leavedays" data-id="{{count($leavedetail)}}">{{$leavetype}}</td>
                                                    </tr>
                                                    @foreach($leavedetail as $leave)
                                                        <tr class="eachleave" data-ldate="{{$leave->ldateid}}" data-amount="{{number_format($leave->amount,2)}}">
                                                            <td class="p-0 leaveids pl-3" data-id="{{$leave->ldateid}}">{{date('m/d/Y - l', strtotime($leave->ldate))}}</td>
                                                            <td class="p-0 leaveamounts text-right" data-id="{{number_format($leave->amount,2)}}">
                                                                {{number_format($leave->amount,2)}}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2" class="p-0 text-center">None</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th colspan="2" class="text-center p-0">OVERTIMES</th>
                                            </tr>
                                            @if(collect($employee->payrollhistorydetail)->where('employeeovertimeid','>','0')->count()>0)
                                                @foreach(collect($employee->payrollhistorydetail)->where('employeeovertimeid','>','0')->values() as $overtime)
                                                    @php
                                                        $overtime->datefrom = DB::table('employee_overtime')
                                                            ->where('id',$overtime->id)->first()->datefrom;   
                                                    @endphp
                                                    <tr>
                                                        <td class="p-0" colspan="2"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="p-0 pl-3">{{date('m/d/Y',strtotime($overtime->datefrom))}} - {{$overtime->overtimehours}} hour(s)</td>
                                                        <td class="p-0 overtimepay text-right" data-id="{{$overtime->id}}" data-hours="{{$overtime->overtimehours}}" data-amount="{{$overtime->amount}}">
                                                            {{$overtime->amount}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2" class="p-0 text-center">None</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr/>
            </div>
        @else
            <div class="col-md-12">
                <div class="card card-primary collapsed-card mb-0"  data-string="{{$employee->firstname}} {{$employee->middlename}} {{$employee->lastname}} {{$employee->utype}}<" data-id="{{$employee->employeeid}}">
                    <div class="card-header pb-0">
                        <div class="row mb-2">
                            <div class="col-md-8"><strong>{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}}</strong></div>
                            <div class="col-md-4">
                                <div class="card-tools float-right">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus text-secondary"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand text-secondary"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <table class="table" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th class="p-0">Basic Salaray</th>
                                        <th class="p-0">Gross Pay</th>
                                        <th class="p-0">Total Earnings</th>
                                        <th class="p-0">Total Deductions</th>
                                        <th class="p-0">Net Pay</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="p-0">{{number_format($employee->salarydetails->basicsalary,2)}}</td>
                                        <td class="p-0 grosspay" data-grosspay="{{number_format($employee->grosssalarypay,2)}}">{{number_format($employee->grosssalarypay,2)}}</td>
                                        <td class="p-0">{{number_format($employee->totalallowances,2)}}</td>
                                        <td class="p-0">{{number_format($employee->totaldeductions,2)}}</td>
                                        <td class="p-0 @if($employee->netpay > 0) text-success @else text-danger @endif" style="font-size: 15px;"><u>{{number_format($employee->netpay,2)}}</u></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12 text-right">
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
                                @if(count($employee->salarydetails->standarddeductions)>0 || count($employee->salarydetails->otherdeductions)>0 || count($employee->salarydetails->standardallowances)>0 || count($employee->salarydetails->otherallowances)>0 || count($employee->salarydetails->leavedetails)>0)
                                    @if($employee->configured == 1)
                                        @if($employee->released == 1)
                                            <button type="button" class="btn btn-sm btn-default printslip " exporttype="pdf"><i  class="fa">&#xf1c1;</i> PDF</button>
                                        {{-- <button type="button" class="btn btn-sm btn-default printslip " exporttype="excel"><i  class="fa">&#xf1c3;</i> EXCEL</button> --}}
                                        @else
                                            <button type="button" class="btn btn-sm btn-success releaseslip " exporttype="pdf"><i  class="fa">&#xf1c1;</i> PDF</button>
                                            {{-- <button type="button" class="btn btn-sm btn-success releaseslip " exporttype="excel"><i  class="fa">&#xf1c3;</i> EXCEL</button> --}}
                                        @endif
                                    @else
                                        @if($overrideconfigured>0)
                                            <button type="button" class="btn btn-sm btn-secondary btn-block" disabled>Not yet configured</button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-success releaseslip " exporttype="pdf"><i  class="fa">&#xf1c1;</i> PDF</button>
                                            {{-- <button type="button" class="btn btn-sm btn-success releaseslip " exporttype="excel"><i  class="fa">&#xf1c3;</i> EXCEL</button> --}}
                                        @endif
                                    @endif
                                @else
                                    @if($employee->released == 1)
                                    <button type="button" class="btn btn-sm btn-default printslip " exporttype="pdf"><i  class="fa">&#xf1c1;</i> PDF</button>
                                    {{-- <button type="button" class="btn btn-sm btn-default printslip " exporttype="excel"><i  class="fa">&#xf1c3;</i> EXCEL</button> --}}
                                    @else
                                        <button type="button" class="btn btn-sm btn-success releaseslip " exporttype="pdf"><i  class="fa">&#xf1c1;</i> PDF</button>
                                        {{-- <button type="button" class="btn btn-sm btn-success releaseslip " exporttype="excel"><i  class="fa">&#xf1c3;</i> EXCEL</button> --}}
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0" style="display: none; font-size: 12px;">
                        <table class="table m-0" style="table-layout: fixed;">
                            <thead class="text-center">
                                <tr>
                                    <th>Attendance</th>
                                    <th>Allowances</th>
                                    <th>Deductions</th>
                                    <th>Others</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="p-0">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td class="p-0">DAYS PRESENT</td>
                                                <td class="p-0 dayspresent" data-id="{{count($employee->salarydetails->attendancedetails->attendancepresent)}}">{{count($employee->salarydetails->attendancedetails->attendancepresent)}} day(s)</td>
                                                <td class="p-0 dayspresentamount text-right" data-id="{{$employee->salarydetails->attendancedetails->attendanceearnings}}">{{$employee->salarydetails->attendancedetails->attendanceearnings}}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0">Tardiness (LATE)</td>
                                                <td class="p-0 lateminutes" data-id="{{$employee->salarydetails->attendancedetails->lateamin+$employee->salarydetails->attendancedetails->latepmin}}">{{$employee->salarydetails->attendancedetails->lateamin+$employee->salarydetails->attendancedetails->latepmin}} min(s)</td>
                                                <td class="p-0 lateamount text-right" data-id="{{$employee->salarydetails->attendancedetails->latedeductionamount}}">{{$employee->salarydetails->attendancedetails->latedeductionamount}}</td>
                                            </tr>
                                            {{-- <tr>
                                                <td class="p-0">Tardiness (Undertime)</td>
                                                <td class="p-0 undertimeminutes" data-id="{{$employee->salarydetails->attendancedetails->undertimeamout+$employee->salarydetails->attendancedetails->undertimepmout}}">{{$employee->salarydetails->attendancedetails->undertimeamout+$employee->salarydetails->attendancedetails->undertimepmout}} min(s)</td>
                                                <td class="p-0 undertimeamount text-right" data-id="{{$employee->salarydetails->attendancedetails->latedeductionamount}}">{{$employee->salarydetails->attendancedetails->latedeductionamount}}</td>
                                            </tr> --}}
                                            <tr>
                                                <td class="p-0">DAYS ABSENT</td>
                                                <td class="p-0 daysabsent" data-id="{{count($employee->salarydetails->attendancedetails->attendanceabsent)}}">{{count($employee->salarydetails->attendancedetails->attendanceabsent)}} day(s)</td>
                                                <td class="p-0 daysabsentamount text-right" data-id="{{$employee->salarydetails->attendancedetails->attendancedeductions}}">{{$employee->salarydetails->attendancedetails->attendancedeductions}}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0">HOLIDAY</td>
                                                <td colspan="2" class="p-0 holidaypay" data-id="{{$employee->salarydetails->attendancedetails->holidaypay}}">{{$employee->salarydetails->attendancedetails->holidaypay}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="p-0">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center p-0">Standard Allowances</th>
                                            </tr>
                                            @if(count($employee->salarydetails->standardallowances)>0)
                                                @foreach($employee->salarydetails->standardallowances[0]->standardallowances as $standardallowance)
                                                <tr>
                                                    <td class="p-0">{{$standardallowance->description}}</td>
                                                    <td class="p-0">
                                                        @if($employee->salarydetails->standardallowances[0]->payrollstatus == 1)
                                                        <small>Paid for this month</small>
                                                        @else
                                                        <small>{{$standardallowance->eesamount}}</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2" class="p-0 text-center">None</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th colspan="2" class="text-center p-0">Other Allowances</th>
                                            </tr>
                                            @if(count($employee->salarydetails->otherallowances)>0)
                                                @foreach($employee->salarydetails->otherallowances[0]->otherallowances as $otherallowance)
                                                <tr>
                                                    <td class="p-0">{{$otherallowance->description}}</td>
                                                    <td class="p-0">
                                                        @if($employee->salarydetails->otherallowances[0]->payrollstatus == 1)
                                                            <small>Paid for this month</small>
                                                        @else
                                                            <small>{{$otherallowance->amount}}</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2" class="p-0 text-center">None</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                    <td class="p-0">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center p-0">Standard Deductions</th>
                                            </tr>
                                            @if(count($employee->salarydetails->standarddeductions)>0)
                                                @foreach($employee->salarydetails->standarddeductions[0]->standarddeductions as $standarddeduction)
                                                <tr>
                                                    <td class="p-0">{{$standarddeduction->description}}</td>
                                                    <td class="p-0">
                                                        @if($standarddeduction->forcefull == 1 || $employee->salarydetails->standarddeductions[0]->payrollstatus == 1)
                                                        <small>Paid for this month ({{$standarddeduction->paidamount}})</small>
                                                        @else
                                                        <small>{{$standarddeduction->eesamount}}</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2" class="p-0 text-center">None</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th colspan="2" class="text-center p-0">Other Deductions</th>
                                            </tr>
                                            @if(count($employee->salarydetails->otherdeductions)>0)
                                                @foreach($employee->salarydetails->otherdeductions[0]->otherdeductions as $otherdeduction)
                                                <tr>
                                                    <td class="p-0">{{$otherdeduction->description}}</td>
                                                    <td class="p-0">
                                                        @if($otherdeduction->amount < 1)
                                                        <small>Paid for this month ({{$otherdeduction->paidamount}})</small>
                                                        @else
                                                        <small>{{$otherdeduction->amount}}</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2" class="p-0 text-center">None</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                    <td class="p-0">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-center p-0">LEAVES</th>
                                            </tr>
                                            @if(count($employee->salarydetails->leavedetails)>0)
                                                @php
                                                    $leavedetails = collect($employee->salarydetails->leavedetails)->groupBy('leave_type');
                                                @endphp
                                                @foreach($leavedetails as $leavetype => $leavedetail)
                                                    <tr>
                                                        <td colspan="2" class="p-0 leavedays" data-id="{{count($leavedetail)}}">{{$leavetype}}</td>
                                                    </tr>
                                                    @foreach($leavedetail as $leave)
                                                        <tr class="eachleave" data-ldate="{{$leave->ldateid}}" data-amount="{{number_format($leave->amount,2)}}">
                                                            <td class="p-0 leaveids pl-3" data-id="{{$leave->ldateid}}">{{date('m/d/Y - l', strtotime($leave->ldate))}}</td>
                                                            <td class="p-0 leaveamounts text-right" data-id="{{number_format($leave->amount,2)}}">
                                                                {{number_format($leave->amount,2)}}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2" class="p-0 text-center">None</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th colspan="2" class="text-center p-0">OVERTIMES</th>
                                            </tr>
                                            @if(count($employee->salarydetails->overtimedetails)>0)
                                                @foreach($employee->salarydetails->overtimedetails as $overtime)
                                                    <tr>
                                                        <td class="p-0" colspan="2">@if($overtime->holiday == 0)Regular @else Holiday @endif</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="p-0 pl-3">{{date('m/d/Y',strtotime($overtime->datefrom))}} - {{$overtime->numofhours}} hour(s)</td>
                                                        <td class="p-0 overtimepay text-right" data-id="{{$overtime->id}}" data-hours="{{$overtime->numofhours}}" data-amount="{{$overtime->amount}}" data-holiday="{{$overtime->holiday}}">
                                                            {{$overtime->amount}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2" class="p-0 text-center">None</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <hr/>
            </div>
        @endif
        @endforeach
      @endif
    </div>
    <script>
        $('.releaseslip').on('click', function(){
            
            var thiscard            = $(this).closest('.card');
            var payrollid           = $('#selectedpayrollid').val()
            var employeeid          = thiscard.attr('data-id')
            var grosspay            = thiscard.find('.grosspay').attr('data-grosspay');
            var dayspresent         = thiscard.find('.dayspresent').attr('data-id') ;
            var dayspresentamount   = thiscard.find('.dayspresentamount').attr('data-id') ;
            var lateminutes         = thiscard.find('.lateminutes').attr('data-id') ;
            var lateamount          = thiscard.find('.lateamount').attr('data-id') ;
            var undertimeminutes    = thiscard.find('.undertimeminutes').attr('data-id') ;
            var undertimeamount     = thiscard.find('.undertimeamount').attr('data-id') ;
            var daysabsent          = thiscard.find('.daysabsent').attr('data-id') ;
            var daysabsentamount    = thiscard.find('.daysabsentamount').attr('data-id') ;
            var holidaypay          = thiscard.find('.holidaypay').attr('data-id') ;
            var leaves              = [];
            if(thiscard.find('.eachleave').length > 0)
            {
                thiscard.find('.eachleave').each(function(){
                    obj = {
                        'ldateid'  : $(this).find('.leaveids').attr('data-id'),
                        'amount'  : $(this).find('.leaveamounts').attr('data-id')
                    }
                    leaves.push(obj);
                })
            }
            var overtimes           = []; 
            if(thiscard.find('.overtimepay').length > 0)
            {
                thiscard.find('.overtimepay').each(function(){
                    obj = {
                        'overtimeid'  : $(this).attr('data-id'),
                        'numofhours'  : $(this).attr('data-hours'),
                        'amount'  : $(this).attr('data-amount'),
                        'holiday'  : $(this).attr('data-holiday')
                    }
                    overtimes.push(obj);
                })
            }
            var paramet = {
                    payrollid          :   payrollid,
                    employeeid          :   employeeid,
                    grosssalarypay          :   grosspay,
                    dayspresent          :   dayspresent,
                    dayspresentamount          :   dayspresentamount,
                    lateminutes          :   lateminutes,
                    lateamount          :   lateamount,
                    undertimeminutes          :   undertimeminutes,
                    undertimeamount          :   undertimeamount,
                    daysabsent          :   daysabsent,
                    daysabsentamount          :   daysabsentamount,
                    holidaypay          :   holidaypay,
                    leaves          :   JSON.stringify(leaves),
                    overtimes          :   JSON.stringify(overtimes),
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
                        window.open("/hr/payrollsummary/releaseslipsingle?exporttype="+exporttype+"&"+$.param(paramet),'_blank');
                    }
                })

        })
        $('.printslip').on('click', function(){
            var payrollid           = $('#selectedpayrollid').val()
            var employeeid          = $(this).closest('.card').attr('data-id')
            var exporttype          = $(this).attr('exporttype');
            window.open("/hr/payrollsummary/viewslip?exporttype="+exporttype+"&payrollid="+payrollid+"&employeeid="+employeeid+"&exportclass=single",'_blank');
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
            window.open("/hr/payrollsummary/exportsummary?"+$.param(paramet),'_blank');
        })
    </script>