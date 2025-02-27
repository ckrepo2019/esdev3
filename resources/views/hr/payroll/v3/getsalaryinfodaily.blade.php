<style>
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
.card {
    box-shadow: none!important;
}
.dis {
  position: relative;
  display: block;
  padding-left: 0rem!important;
}
</style>
<div class="card border-light mb-3" style="">
  <div class="card-body">

    <div class="row">
      {{-- <div class="col-md-2 col-12">
          <h4>Payroll Period</h4>
      </div> --}}
      <div class="col-lg-3 col-md-4 col-sm-12 col-12">
        <div class="row">
          <div class="col-md-12 text-center">
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
                <img src="{{ asset($employeeinfo->picurl) }}" alt="" onerror="this.onerror = null, this.src='{{asset($avatar)}}'" style="width: 200px; height: 200px;"/>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-9 col-md-8 col-sm-12 col-12">
        <div class="row">
          <div class="col-md-12">
            <h4><a href="javascript:void(0)">{{$employeeinfo->lastname}}, {{$employeeinfo->firstname}}</a></h4>
            <span class="info-box-number">{{$employeeinfo->tid}}</span> <br>
            <span class="info-box-number">{{$employeeinfo->utype}}</span>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <label>Working days</label>
            <div class="form-group mb-0 text-left" style="display: -webkit-box;">
              <div class="form-check dis">
                @if($basicsalaryinfo->mondays == 1)
                  <i class="fas fa-check-square text-success"></i>
                  <label class="form-check-label pr-2">M</label>
                @else
                  <i class="far fa-square" style="opacity: .5"></i>
                  <label class="form-check-label pr-2">M</label>
                @endif
              </div>
              <div class="form-check dis">
                @if($basicsalaryinfo->tuesdays == 1)
                  <i class="fas fa-check-square text-success"></i>
                  <label class="form-check-label pr-2">T</label>
                @else
                  <i class="far fa-square" style="opacity: .5"></i>
                  <label class="form-check-label pr-2">T</label>
                @endif
              </div>
              <div class="form-check dis">
                @if($basicsalaryinfo->wednesdays == 1)
                  <i class="fas fa-check-square text-success"></i>
                  <label class="form-check-label pr-2">W</label>
                @else
                  <i class="far fa-square" style="opacity: .5"></i>
                  <label class="form-check-label pr-2">W</label>
                @endif
              </div>
              <div class="form-check dis">
                @if($basicsalaryinfo->thursdays == 1)
                  <i class="fas fa-check-square text-success"></i>
                  <label class="form-check-label pr-2">TH</label>
                @else
                  <i class="far fa-square" style="opacity: .5"></i>
                  <label class="form-check-label pr-2">TH</label>
                @endif
              </div>
              <div class="form-check dis">
                @if($basicsalaryinfo->fridays == 1)
                  <i class="fas fa-check-square text-success"></i>
                  <label class="form-check-label pr-2">F</label>
                @else
                  <i class="far fa-square" style="opacity: .5"></i>
                  <label class="form-check-label pr-2">F</label>
                @endif
              </div>
              <div class="form-check dis">
                @if($basicsalaryinfo->saturdays == 1)
                  <i class="fas fa-check-square text-success"></i>
                  <label class="form-check-label pr-2">Sat</label>
                @else
                  <i class="far fa-square" style="opacity: .5"></i>
                  <label class="form-check-label pr-2">Sat</label>
                @endif
              </div>
              <div class="form-check dis">
                @if($basicsalaryinfo->sundays == 1)
                  <i class="fas fa-check-square text-success"></i>
                  <label class="form-check-label pr-2">Sun</label>
                @else
                <i class="far fa-square" style="opacity: .5"></i>
                  <label class="form-check-label pr-2">Sun</label>
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <label>Daily rate: {{number_format($basicsalaryinfo->amount,2)}}</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <table class="table table-bordered" style="font-size: 15px; width: 100%; table-layout: fixed;">
              <thead class="text-center">
                  <tr>
                      <th>Present</th>
                      <th>Absent</th>
                      <th>Late</th>
                      <th>Total Hours Worked</th>
                      <th hidden>Amount Per Day</th>
                  </tr>
              </thead>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <label for=""><h5>Earnings</h5></label>
        <hr>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <label for=""><h5>Deductions</h5></label>
        <hr>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-12 col-12">
          <label for=""><h5>Total Earnings</h5></label>
          <hr>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <label for=""><h5>Total Deductions</h5></label>
        <hr>
      </div>
    </div>
  </div>
</div>