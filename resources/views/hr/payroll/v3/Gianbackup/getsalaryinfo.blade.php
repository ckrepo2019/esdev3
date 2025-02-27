@php
    $totalearnings = 0;
    $totaldeductions = 0;
    $totalleaveamount = 0;
@endphp
<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-md-4 text-center" style="padding-top: 20px">
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
            <img class="img-circle elevation-2" src="{{asset($employeeinfo->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'))}}" id="profilepic" style="width:160px;height:160px;"  onerror="this.onerror = null, this.src='{{asset($avatar)}}'" alt="User Avatar">
          </a>
        </div>
      </div>
      <div class="col-md-8 p-0">
        <div class="text-right">
          <button type="button" class="btn btn-sm btn-info" id="btn-refresh"><i class="fa fa-sync"></i> Refresh</button>
        </div>
        <div><span style="font-size: 25px;"><a href="#"><b>{{$employeeinfo->lastname}}, {{$employeeinfo->firstname}}</b></a></span></div>
        <div><b class="text-muted">User ID : </b><span class="info-box-number">{{$employeeinfo->tid}}</span></div>
        <div><b class="text-muted">User Type : </b><span class="info-box-number">{{$employeeinfo->utype}}</span></div>
        <span><b>Working Days</b></span>
        <div class="form-group mb-0" style="display: -webkit-box;">
          <div class="form-check pr-2">
            <input class="form-check-input" type="checkbox" @if($basicsalaryinfo) @if($basicsalaryinfo->mondays == 1) checked @endif @endif style="pointer-events: none!important;">
            <label class="form-check-label">M</label>
          </div>
          <div class="form-check pr-2">
            <input class="form-check-input" type="checkbox" @if($basicsalaryinfo)  @if($basicsalaryinfo->tuesdays == 1) checked @endif @endif style="pointer-events: none!important;">
            <label class="form-check-label">T</label>
          </div>
          <div class="form-check pr-2">
            <input class="form-check-input" type="checkbox" @if($basicsalaryinfo)  @if($basicsalaryinfo->wednesdays == 1) checked @endif @endif style="pointer-events: none!important;">
            <label class="form-check-label">W</label>
          </div>
          <div class="form-check pr-2">
            <input class="form-check-input" type="checkbox" @if($basicsalaryinfo)  @if($basicsalaryinfo->thursdays == 1) checked @endif @endif style="pointer-events: none!important;">
            <label class="form-check-label">Th</label>
          </div>
          <div class="form-check pr-2">
            <input class="form-check-input" type="checkbox" @if($basicsalaryinfo)  @if($basicsalaryinfo->fridays == 1) checked @endif @endif style="pointer-events: none!important;">
            <label class="form-check-label">F</label>
          </div>
          <div class="form-check pr-2">
            <input class="form-check-input bg-primary" type="checkbox" @if($basicsalaryinfo)  @if($basicsalaryinfo->saturdays == 1) checked @endif @endif style="pointer-events: none!important;">
            <label class="form-check-label">Sat</label>
          </div>
          <div class="form-check pr-2">
            <input class="form-check-input" type="checkbox" @if($basicsalaryinfo)  @if($basicsalaryinfo->sundays == 1) checked @endif @endif style="pointer-events: none!important;">
            <label class="form-check-label">Sun</label>
          </div>
        </div>
        @if ($basicsalaryinfo)
          @if ($basicsalaryinfo->amountperday)
            <div>
              <span style="" id="employeerates" data-basicsalaryamount="{{$basicsalaryinfo->amountperday}}" data-basicsalarytype="{{$basicsalaryinfo->salarybasistype}}">
                <b>Monthly : </b>
                @php
                    $monthlysalary = $workingdays * $basicsalaryinfo->amountperday;
                    $monthlysalary = number_format($monthlysalary,2,'.',',')
                @endphp
                {{$monthlysalary}} | <b>Daily Rate : </b> {{number_format($basicsalaryinfo->amountperday)}}
              
              </span>
            </div>
          @endif
        @endif
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 p-0">
        <table width="100%" class="table table-sm table-bordered" id="datatable_1">
          <thead class="bg-dark">
            <tr>
              <th class="text-center">Present</th>
              <th class="text-center">Leave w/ Pay</th>
              <th class="text-center">Absent</th>
              <th class="text-center">Late</th>
              <th class="text-center">Hours Worked</th>
              <th class="text-center">Late Deduction</th>
              <th class="text-center">Absent Deduction</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-center" id="presentdays" data-dayspresent="{{$dayspresent}}">
                <input type="hidden" id="workingdays" value="{{$workingdays}}">
                <span>{{$dayspresent}} days</span>
              </td>
              <td class="text-center"></td>
              <td class="text-center" id="absentdays" data-daysabsent="{{$daysabsent}}"><span>{{$daysabsent}} days</span></td>
              <td class="text-center">
                <span>
                  @php
                    define('MINUTES_PER_HOUR', 60);
                    $total_minutes = $lateminutes;
                    $hours = intval($total_minutes / MINUTES_PER_HOUR);  // integer division
                    $mins = $total_minutes % MINUTES_PER_HOUR;           // modulo
                    $latedays = array();
                    $amountperday = 0;
                  @endphp
                  <input type="hidden" id="lateminutes" value="{{$total_minutes}}">
                  {{$total_minutes}} min(s)
                </span></td>
              <td class="text-center" id="totalhoursworked" data-totalhoursworked="{{$totalworkedhours}}"><span>{{$totalworkedhours}} hour(s)</span></td>
              <td class="text-center" id="lateamount" data-lateamount="{{$totalamountdeductlate}}">
                <input type="hidden" id="undertimeminutes" value="{{$undertimeminutes}}">
                <span>
                  @if ($totalamountdeductlate)
                    &#8369; {{$totalamountdeductlate}}
                  @else
                    &#8369; 0.00
                  @endif
                </span>
              </td>
              <td class="text-center">
                <span>
                  @if ($daysabsent)
                    &#8369; {{number_format($totalamountdeductabsent)}}
                  @else
                    &#8369; 0.00
                  @endif
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    {{----------------------------------- Allowance and Deduction Area -----------------------------------------}}
    <div class="row" style="margin-top: 5px; border-top: 1px solid #dee2e6;">


      {{-- ############################################ ALLOWANCES ############################################ --}}
      <div class="col-md-5" style="padding: 0px 10px 0px 10px; box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 2px 0px;!important;">
        <h5 style="padding-top: 12px;"><b>EARNINGS</b></h5>
        <hr>
        @if($basicsalaryinfo) 
        <table style="width: 100%; table-layout: fixed; font-size: 14px;">
          <tr>
            <td>BASIC PAY</td>
            @if($released == 1)
            <td class="text-right text-bold text-success">
              {{number_format($payrollinfo->basicsalaryamount,2,'.',',')}}
            </td>
            @else
            <td class="text-right text-bold text-success" id="td-basicpay-amount" data-amount="{{$basicsalaryinfo->amountperday}}">
              @if($basicsalaryinfo->amount > 0 )
                @php
                  $basicpay = $basicsalaryinfo->amountperday * $workingdays;

                  $totalearnings = $basicpay;
                @endphp
                  {{number_format($basicpay)}}
                  <input type="hidden" id="basicpay" value="{{number_format($basicpay)}}">
                @endif
              </td>
              @endif
            </tr>  
          </table>

          {{-- ############################################ LEAVES ############################################ --}}
          @if(collect($leaves)->where('amount','>','0')->count()>0)
            <table style="width: 100%; table-layout: fixed; font-size: 14px;">
              @foreach(collect($leaves)->where('amount','>','0')->values() as $eachleaf)
                <tr>
                  <td>{{$eachleaf->leave_type}} <small class="text-bold">{{date('m/d/Y', strtotime($eachleaf->ldate))}}</small></td>
                  <td class="text-right text-bold text-success td-leaves" data-ldateid="{{$eachleaf->ldateid}}" data-amount="{{$eachleaf->amount}}" data-description="{{$eachleaf->leave_type}}"  data-empleaveid="{{$eachleaf->employeeleaveid}}"  data-ldateid="{{$eachleaf->ldateid}}">
                      {{$eachleaf->amount}}
                      @php
                          $totalearnings += $eachleaf->amount;
                          $totalleaveamount += $eachleaf->amount;
                      @endphp
                  </td>
                </tr>  
              @endforeach            
            </table>
          @endif
          {{-- ################################################################################################# --}}



          {{-- ############################################ STANDARD ALLOWANCES ####################################### --}}
          @if(count($standardallowances)>0)
            <table style="width: 100% !important;">
              @foreach($standardallowances as $eachstandard)
                <tr>
                  <td style="width: 40%;"><small>{{strtoupper($eachstandard->description)}}</small></td>
                  <td>
                    @if($eachstandard->paidforthismonth == 0)
                      <div class="icheck-primary d-inline mr-2 standardallowance" data-allowancetype="0" data-standardid="{{$eachstandard->id}}" data-allowanceid="{{$eachstandard->empallowanceid}}" data-totalamount="{{$eachstandard->totalamount}}" data-amount="{{$eachstandard->totalamount}}" data-description="{{$eachstandard->description}}" style="font-size: 11.5px;">
                        <input type="radio" class="sa_radio" id="standardallowance{{$eachstandard->id}}1"  name="standardallowance{{$eachstandard->id}}" style="height:35px; width:35px; vertical-align: middle;" @if(($configured == 1 && $eachstandard->paymenttype == 0) || ($eachstandard->lock == 1 && $configured == 1 && $eachstandard->paymenttype == 0))checked @endif @if($eachstandard->lock == 1 && $eachstandard->paymenttype == 0) disabled @endif>
                        <label for="standardallowance{{$eachstandard->id}}1">Full
                        </label>
                      </div>
                      <div class="icheck-primary d-inline mr-2 standardallowance" data-allowancetype="1" data-standardid="{{$eachstandard->id}}" data-allowanceid="{{$eachstandard->empallowanceid}}" data-totalamount="{{$eachstandard->totalamount}}" data-amount="{{$eachstandard->totalamount/2}}" data-description="{{$eachstandard->description}}" style="font-size: 11.5px;">
                        <input type="radio" class="sa_radio" id="standardallowance{{$eachstandard->id}}2" name="standardallowance{{$eachstandard->id}}" style="height:35px; width:35px; vertical-align: middle;" @if(($configured == 1 && $eachstandard->paymenttype == 1) || ($eachstandard->lock == 1 && $eachstandard->paymenttype == 1))checked @endif>
                        <label for="standardallowance{{$eachstandard->id}}2">Half
                        </label>
                      </div>
                    @else
                      Paid for this month
                    @endif
                  </td>
                  <td class="text-right text-success"><small class=" text-bold" id="text-amount-standardallowance-{{$eachstandard->empallowanceid}}">{{number_format($eachstandard->totalamount,2,'.',',')}}</small></td>
                </tr>
                {{-- @php
                  if($eachstandard->paymenttype == 0){
                  $totalearnings+=((float)$eachstandard->totalamount);
                  }else{
                  $totalearnings+=((float)number_format($eachstandard->totalamount/2,2));
                  }
                @endphp --}}
              @endforeach
              
            </table>
          <!-- ######################################################################################################### -->
          @endif


          <table style="width: 100% !important;">
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="3">@if($released == 0)<button type="button" class="btn btn-sm btn-warning pt-0 pb-0 btn-add-particular" data-type="1"><i class="fa fa-plus fa-xs"></i>&nbsp;Add earning</button>@endif</td>
            </tr>
            <tr>
              <td colspan="3" style="vertical-align: top;" class="p-0">
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
            </tr>
          </table>

        @endif
        <table width="100%" class="table table-sm" style="table-layout: fixed; font-size: 17px; margin-top: 50px;">
          @if($released == 1)
            <span>Masaya</span>
          @else
            <tr>
              <th>Total Earnings</th>
              @if ($basicsalaryinfo)
                <th class="text-right text-success" style="font-size: 17px;"><span id="span-total-earn-display">{{$totalearnings}}</span><span id="span-total-earn" hidden>{{$totalearnings}}</span></th>
              @else
                <th class="text-right text-success" style="font-size: 17px;"><span id="span-total-earn-display">0</span><span id="span-total-earn" hidden>0</span></th>
              @endif
            </tr>
          @endif
        </table>
      </div>
      {{-- ############################################################################################################## --}}



      {{-- ################################################# DEDUCTIONS ################################################# --}}
      <div class="col-md-5" style="padding: 0px 10px 0px 10px; box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 2px 0px;!important;">
        <h5 style="padding-top: 12px;"><b>DEDUCTIONS</b></h5>
        <hr>
        @if($basicsalaryinfo) 
          <table style="width: 100% !important;">
            <tr>
              <td style="width: 50%;">Absent</td>
              @if($released == 1)
                <td style="width: 50%; text-align: right;" class="text-danger text-bold">{{number_format($payrollinfo->daysabsentamount,2,'.',',')}}</td>
              @else
                <td style="width: 50%; text-align: right;" class="text-danger text-bold" id="amountabsent" data-value="{{$totalamountdeductabsent}}">{{$totalamountdeductabsent}}</td>
              @endif
            </tr>
            <tr>
              <td style="width: 50%;">Late</td>
              @if($released == 1)
                <td style="width: 50%; text-align: right;" class="text-danger text-bold">{{number_format($payrollinfo->lateamount,2,'.',',')}}</td>
              @else
                <td style="width: 50%; text-align: right;" class="text-danger text-bold" id="amountlate" data-value="{{$totalamountdeductlate}}">{{$totalamountdeductlate}}</td>
              @endif
            </tr>
          </table>


          {{-- STANDARD DEDUCTION --}}
          @if (count($standarddeductions)>0)
            <table style="width: 100% !important;">
              @foreach($standarddeductions as $eachstandarddeduction)
              <tr>
                <td style="width: 40%;"><small>{{strtoupper($eachstandarddeduction->description)}}</small></td>
                <td>
                  @if($eachstandarddeduction->paidforthismonth == 0)
                  <div class="icheck-primary d-inline mr-2 standarddeduction" data-deducttype="0" data-deductionid="{{$eachstandarddeduction->id}}" data-totalamount="{{$eachstandarddeduction->totalamount}}" data-amount="{{$eachstandarddeduction->totalamount}}" data-description="{{$eachstandarddeduction->description}}" style="font-size: 11.5px;">
                    <input type="radio" id="standarddeduction{{$eachstandarddeduction->id}}1" name="standarddeduction{{$eachstandarddeduction->id}}" style="height:35px; width:35px; vertical-align: middle;" @if(($configured == 1 && $eachstandarddeduction->paymenttype == 0) || ($eachstandarddeduction->lock == 1 && $eachstandarddeduction->paymenttype == 0))checked @endif @if($eachstandarddeduction->lock == 1) disabled @endif>
                    <label for="standarddeduction{{$eachstandarddeduction->id}}1">Full 
                    </label>
                  </div>
                  <div class="icheck-primary d-inline mr-2 standarddeduction" data-deducttype="1" data-deductionid="{{$eachstandarddeduction->id}}" data-totalamount="{{$eachstandarddeduction->totalamount}}" data-amount="{{number_format($eachstandarddeduction->totalamount/2,2)}}" data-description="{{$eachstandarddeduction->description}}" style="font-size: 11.5px;">
                    <input type="radio" id="standarddeduction{{$eachstandarddeduction->id}}2" name="standarddeduction{{$eachstandarddeduction->id}}" style="height:35px; width:35px; vertical-align: middle;" @if(($configured == 1 && $eachstandarddeduction->paymenttype == 1) || ($eachstandarddeduction->lock == 1 && $eachstandarddeduction->paymenttype == 1))checked @endif @if($eachstandarddeduction->lock == 1) disabled @endif>
                    <label for="standarddeduction{{$eachstandarddeduction->id}}2">Half
                    </label>
                  </div>
                  @else
                  Paid for this month
                  @endif
                </td>
                <td class="text-danger text-right"><small class=" text-bold" id="text-amount-standarddeduction-{{$eachstandarddeduction->id}}">{{number_format($eachstandarddeduction->totalamount,2,'.',',')}}</small>
                </td>
              </tr>
              @endforeach
            </table>
          @endif
          @if(count($otherdeductions)>0)
          <table style="width: 100% !important;">
            @foreach($otherdeductions as $eachotherdeduction)
            <tr>
              <td style="width: 40%;"><small>{{strtoupper($eachotherdeduction->description)}}</small></td>
              <td>
                @if($eachotherdeduction->paidforthismonth == 0)
                <div class="icheck-primary d-inline mr-2 otherdeduction" data-deducttype="0" data-deductionid="{{$eachotherdeduction->id}}" data-totalamount="{{$eachotherdeduction->totalamount}}" data-amount="{{$eachotherdeduction->totalamount}}" data-description="{{$eachotherdeduction->description}}" style="font-size: 11.5px;">
                  <input type="radio" id="otherdeduction{{$eachotherdeduction->id}}1" name="otherdeduction{{$eachotherdeduction->id}}" style="height:35px; width:35px; vertical-align: middle;" @if(($configured == 1 && $eachotherdeduction->paymenttype == 0) || ($eachotherdeduction->lock == 1 && $eachotherdeduction->paymenttype == 0))checked @endif @if($eachotherdeduction->lock == 1) disabled @endif>
                  <label for="otherdeduction{{$eachotherdeduction->id}}1">Full 
                  </label>
                </div>
                <div class="icheck-primary d-inline mr-2 otherdeduction" data-deducttype="1" data-deductionid="{{$eachotherdeduction->id}}" data-totalamount="{{$eachotherdeduction->totalamount}}" data-amount="{{$eachotherdeduction->totalamount/2}}" data-description="{{$eachotherdeduction->description}}" style="font-size: 11.5px;">
                  <input type="radio" id="otherdeduction{{$eachotherdeduction->id}}2" name="otherdeduction{{$eachotherdeduction->id}}" style="height:35px; width:35px; vertical-align: middle;" @if(($configured == 1 && $eachotherdeduction->paymenttype == 1) || ($eachotherdeduction->lock == 1 && $eachotherdeduction->paymenttype == 1))checked @endif @if($eachotherdeduction->lock == 1) disabled @endif>
                  <label for="otherdeduction{{$eachotherdeduction->id}}2">Half
                  </label>
                </div>
                @else
                  Paid for this month
                @endif
              </td>
              <td class="text-danger text-right"><small class=" text-bold" id="text-amount-otherdeduction-{{$eachotherdeduction->id}}">{{number_format($eachotherdeduction->totalamount,2,'.',',')}}</small>
              </td>
            </tr>
            @endforeach
          </table>
          <!-- /.card-body -->
          @endif

          <table style="width: 100% !important;">
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="3">@if($released == 0)<button type="button" class="btn btn-sm btn-warning pt-0 pb-0 btn-add-particular" data-type="2"><i class="fa fa-plus fa-xs"></i>&nbsp;Add deduction</button>@endif</td>
            </tr>
            <tr>
              <td colspan="3" style="vertical-align: top;">
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
          </table>
        @endif
        <table width="100%" class="table table-sm" style="table-layout: fixed; font-size: 17px; margin-top: 50px;">
          @if($released == 1)
            <span>Masaya</span>
          @else
            <tr>
              <th>Total Deductions</th>
              @if ($basicsalaryinfo)
                <th class="text-right text-danger" style="font-size: 17px;">
                  <span id="span-total-deduct-display">
                    @php
                      $totaldeductions = $totalamountdeductabsent + $totalamountdeductlate;
                    @endphp
                    {{$totaldeductions}}
                  </span>
                  <span id="span-total-deduct" hidden>{{$totalamountdeductabsent}}</span>
                </th>
              @else
                <th class="text-right text-danger" style="font-size: 17px;"><span id="span-total-deduct-display">0</span><span id="span-total-deduct" hidden>0</span></th>
              @endif
            </tr>
          @endif
        </table>
        
        
      </div>

      
      {{-- ############################################################################################################## --}}


      <div class="col-md-2 text-left" style="padding: 0px 10px 0px 10px; box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 2px 0px;!important;">
        <h5 style="padding-top: 12px;"><b>NET PAY</b></h5>
        <hr>
        @php
            $netpay = $totalearnings - $totaldeductions;
        @endphp
        <h3 class="text-right" id="netsalary">&#8369; {{number_format($netpay,2,'.',',')}}</h3>
        <button type="button" class="btn btn-block btn-primary btn-compute" data-id="1" id="btn-save-computation">&nbsp;Save this computation&nbsp;</button>
      </div>
    </div>
    {{-- ---------------------------------------------------------------------------------------------------- --}}
  </div>
</div>

{{-- MODAL --}}
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
  $(document).ready(function() {
    $('#datatable_1').DataTable({
      destroy: true,
      lengthChange: false,
      scrollX: true,
      autoWidth: true,
      order: false,
      paging: false,
      searching: false,
      info: false
    })
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
    });
    var totalearnings = 0
    var totaldeductions = 0
    var totalstandardallowance = 0
    var totalstandarddeduction = 0
    var totalotherdeduction = 0
    var additionalearnings = 0;
    var additionaldeductions = 0;
    var employeeinfo = @json($employeeinfo);

    $('#btn-refresh').on('click', function(){
      $('#select-employee').trigger('change')
    })


  // STANDARD ALLOWANCE
  $('.standardallowance').on('click', function(){
    totalstandardallowance = 0
    $('.amountcontainer').removeClass('text-bold')
    var standardid = $(this).attr('data-standardid')
    var allowanceid = $(this).attr('data-allowanceid')
    var allowancetype = $(this).attr('data-allowancetype')

    if(allowancetype == 3)
    {
      var amount = $(this).find('input[type="number"]').val()
    }else{
      var amount = $(this).attr('data-amount')
    }
    $('#text-amount-standardallowance-'+allowanceid).text(amount)

    var thisradioinputs = $(this).closest('td').find('.standardallowance')
    thisradioinputs.each(function(){
      $(this).removeClass('checked')
    })

    $(this).addClass('checked')
    $('.standardallowance.checked').each(function(){
      totalstandardallowance += parseFloat($(this).attr('data-amount'))
    })
    computetotalearnings()
  })

  // STANDARD DEDUCTION
  $('.standarddeduction').on('click', function(){
    totalstandarddeduction = 0
    $('.amountcontainer').removeClass('text-bold')
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

    var thisradioinputs = $(this).closest('td').find('.standarddeduction')
    thisradioinputs.each(function(){
      $(this).removeClass('checked')
    })
    $(this).addClass('checked')
    $('.standarddeduction.checked').each(function(){
      totalstandarddeduction += parseFloat($(this).attr('data-amount'))
    })

    // console.log(totalstandarddeduction);
    computetotaldeduction()
  })


  // OTHER DEDUCTION
  $('.otherdeduction').on('click', function(){
    totalotherdeduction = 0
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
    
    // if($('#container-added-deduction').find('#otherdeduction'+deductionid).length == 0)
    // {
    //   $('#container-added-deduction').append('<p id="otherdeduction'+deductionid+'" class="text-right m-0 amountcontainer">'+amount+'</p>')
    // }else{
    //   $('#otherdeduction'+deductionid).text(amount)
    // }
    var thisradioinputs = $(this).closest('td').find('.otherdeduction')
      thisradioinputs.each(function(){
      $(this).removeClass('checked')
    })
    $(this).addClass('checked')
    $('.otherdeduction.checked').each(function(){
      totalotherdeduction += parseFloat($(this).attr('data-amount'))
    })
    $('#otherdeduction'+deductionid).addClass('text-bold')
    $('#otherdeduction'+deductionid).addClass('text-danger')
    $('#text-amount-otherdeduction-'+deductionid).text(amount)


    computetotaldeduction()
  })



  // click Save this computation button
  $('.btn-compute').on('click', function(){
    var basicpay = parseFloat($('#basicpay').val().replace(',', ''));
    var payrollid = '{{$payrollperiod->id}}'
    var employeeid = $('#select-employee').val()
    var workingdays = $('#workingdays').val()
    var basicsalaryamount = parseFloat($('#employeerates').attr('data-basicsalaryamount'))
    var basicsalarytype = $('#employeerates').attr('data-basicsalarytype')
    var dailyrate = parseFloat($('#employeerates').attr('data-basicsalaryamount'))
    var monthlysalary = (parseFloat(workingdays) * 2) * dailyrate
    var presentdays = $('#presentdays').attr('data-dayspresent')
    var absentdays = $('#absentdays').attr('data-daysabsent')
    var dayspresentamount = parseFloat(presentdays) * parseFloat(dailyrate)
    var daysabsentamount = 0
    if (absentdays > 0) {
        var daysabsentamount = absentdays * dailyrate
    }
    var lateminutes = $('#lateminutes').val()
    var lateamount = $('#lateamount').attr('data-lateamount')
    var undertimeminutes = $('#undertimeminutes').val()
    var totalworkedhours = $('#totalhoursworked').attr('data-totalhoursworked')
    var amountperday = 0
    if (basicsalarytype == 5) {
        var amountperday = dailyrate
    } else if(basicsalarytype == 6){
        var amountperday = basicsalaryamount / workingdays
    }

    var totalearnings = basicpay
    var totaldeductions = parseFloat(daysabsentamount) + parseFloat(lateamount);
    var netsalary = parseFloat($('#netsalary').text().replace(',', ''))
      
      $.ajax({
        type: "GET",
        url: "/hr/payrollv3/configuration",
        data: {
            basicpay : basicpay,
            payrollid : payrollid,
            employeeid : employeeid,
            workingdays : workingdays,
            basicsalaryamount : basicsalaryamount,
            basicsalarytype : basicsalarytype,
            dailyrate : dailyrate,
            presentdays : presentdays,
            absentdays : absentdays,
            dayspresentamount : dayspresentamount,
            daysabsentamount : daysabsentamount,
            lateminutes : lateminutes,
            lateamount : lateamount,
            undertimeminutes : undertimeminutes,
            amountperday : amountperday,
            totalworkedhours : totalworkedhours,
            totalearnings : totalearnings,
            totaldeductions : totaldeductions,
            netsalary : netsalary,
            monthlysalary : monthlysalary
        },
        success: function (data) {
            if(data[0].status == 0){
            Toast.fire({
                type: 'error',
                title: data[0].message
            })
            }else{
            Toast.fire({
                type: 'success',
                title: data[0].message
            })
          }
        }
      });
    })


    // WHEN ADD EARNING AND ADD DEDUCTION IS CLICK
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
            '<input type="number" class="form-control" id="input-particular-amount" value="0.00"/>'+
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

    // SUBMIT PARTICULAR
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
                '<td><span class="text-right m-0 span-amount float-right text-success" data-type="'+particulartype+'"><b>'+amount+'</b></span></td>'+
            '</tr>'
          )
        }
        if(particulartype == 2)
        {
          $('#container-adddeductions').append(
            '<tr>'+
                '<td> <i class="fa fa-times fa-xs text-danger remove-particular" data-id="1" data-amount="'+amount+'" style="cursor: pointer;"></i> <span id="0" class="text-right m-0 span-description additional-paticular text-success" data-type="'+particulartype+'" data-amount="'+amount+'">'+description+'</span></td>'+
                '<td><span class="text-right m-0 span-amount float-right text-danger" data-type="'+particulartype+'"><b>'+amount+'</b></span></td>'+
            '</tr>'
          )
        }
      }
    })



    // FUNCTIONS

    function computetotalearnings(){
      totalearnings = 0
      var basicpay = parseFloat('{{$basicsalaryinfo->amount}}')
      var totalleaveamount = parseFloat('{{$totalleaveamount}}')

      totalearnings += basicpay
      totalearnings += totalleaveamount
      totalearnings += totalstandardallowance

      $('#span-total-earn-display').text(totalearnings)
      computenetpay()
    }

    function computetotaldeduction(){
      computetotalearnings()

      // console.log(totalotherdeduction);
      totaldeductions = 0
      var absentamount = parseFloat('{{$totalamountdeductabsent}}')
      var lateamount = parseFloat('{{$totalamountdeductlate}}')

      totaldeductions += absentamount
      totaldeductions += lateamount
      totaldeductions += totalstandarddeduction
      totaldeductions += totalotherdeduction

      $('#span-total-deduct-display').text(totaldeductions)
    }

    function computenetpay(){
      var totalearnings = parseFloat($('#span-total-earn-display').text())
      var totaldeductions = parseFloat($('#span-total-deduct-display').text())
      var netpay = (totalearnings - totaldeductions)
      if (netpay > 0) {
        $('#netsalary').removeClass('text-danger')
        $('#netsalary').addClass('text-success')
      } else {
        $('#netsalary').removeClass('text-success')
        $('#netsalary').addClass('text-danger')
        
      }
      netpay = netpay.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
      
      $('#netsalary').html('&#8369;' + netpay)
    }

  })
</script>