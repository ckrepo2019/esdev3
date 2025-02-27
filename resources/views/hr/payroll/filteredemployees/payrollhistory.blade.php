
  

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
<div class="row mt-2">
    <div class="col-md-12">
        <div class="alert alert-warning alert-dismissible">
            {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
            <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
            Warning alert preview. This page is under maintenance.
        </div>
    </div>
</div>
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
                    <button type="button" class="btn btn-default float-right m-0 exportsummary" style="display: block;" exporttype="excel">Excel</button></span>
              </div>
            </div>
          </div>
      </div>
  </div>
    <div class="row d-flex align-items-stretch text-uppercase mt-3">
      @if(count($employees)>0)
        <div class="col-md-12">
            
            <table style="width: 100%;text-align: center;table-layout: fixed;">
                <thead>
                    <tr>
                        <th style="width: 20%;">Name</th>
                        <th>Basic<br/>Salary</th>
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
                                        <td class="text-center">
                                            @if(count($employee->payrollinfo)>0)
                                                {{$employee->payrollinfo[0]->basicpay}}
                                            @endif                                        
                                        </td>
                                        <td class="text-center text-warning">
                                            @if(count($employee->payrollinfo)>0)
                                                {{$employee->payrollinfo[0]->daysabsentamount+$employee->payrollinfo[0]->lateamount}}
                                            @endif      
                                        </td>
                                        <td class="text-center text-success">
                                            @if(count($employee->payrollinfo)>0)
                                                {{$employee->payrollinfo[0]->daysabsentamount+$employee->payrollinfo[0]->lateamount}}
                                            @endif
                                        </td>
                                        <td class="text-center text-warning">
                                            @if(count($employee->payrollinfo)>0)
                                                {{$employee->payrollinfo[0]->daysabsentamount+$employee->payrollinfo[0]->lateamount}}
                                            @endif
                                        </td>
                                        <td class="text-center text-success">
                                            @if(count($employee->payrollinfo)>0)
                                                {{$employee->payrollinfo[0]->daysabsentamount+$employee->payrollinfo[0]->lateamount}}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(count($employee->payrollinfo)>0)
                                                {{$employee->payrollinfo[0]->daysabsentamount+$employee->payrollinfo[0]->netpay}}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{-- @php
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
                                            @endif --}}
                                            {{-- {{$overrideconfigured}} --}}
                                            {{-- @if(count($employee->salarydetails->standarddeductions)>0 || count($employee->salarydetails->otherdeductions)>0 || count($employee->salarydetails->standardallowances)>0 || count($employee->salarydetails->otherallowances)>0 || count($employee->salarydetails->leavedetails)>0)
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
                                            @else --}}
                                                {{-- @if($employee->released == 1)
                                                <button type="button" class="btn btn-sm btn-default printslip " exporttype="pdf"><i  class="fa">&#xf1c1;</i> PDF</button>
                                                <button type="button" class="btn btn-sm btn-default printslip " exporttype="excel"><i  class="fa">&#xf1c3;</i> EXCEL</button>
                                                @else --}}
                                                <button type="button" class="btn btn-sm btn-default printslip " exporttype="pdf"><i  class="fa">&#xf1c1;</i> PDF</button>
                                                <button type="button" class="btn btn-sm btn-default printslip " exporttype="excel"><i  class="fa">&#xf1c3;</i> EXCEL</button>
                                                {{-- @endif --}}
                                            {{-- @endif --}}
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
                    </div>
                </div>
                <!-- /.card-body -->
              </div>
        </div>
        @endforeach
      @endif
    </div>
    {{-- <script>
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
    </script> --}}