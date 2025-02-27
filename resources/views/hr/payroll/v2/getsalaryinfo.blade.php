
@if($basicsalaryinfo)
@php
if($basicsalaryinfo->amount > 0 )
{
  if(count($attendance) > 0){
    $basicsalaryamount = number_format((($basicsalaryinfo->amount/2)/count($attendance)) * collect($attendance)->where('totalworkinghours','>',0)->count(),2);
  }else{
    $basicsalaryamount = number_format(($basicsalaryinfo->amount/2),2);
  }
}
@endphp
<div class="row">
    <div class="col-md-12">
        <h5 class="text-muted">{{$basicsalaryinfo->salarytype}} : {{$basicsalaryinfo->amount}}</h5>

    </div>
    
    <div class="col-md-12 mb-0">
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
    <div class="col-md-12 mt-0 pt-0">
        <table class="table table-bordered mt-0" style="font-size: 10.5px; table-layout: fixed;">
            <thead>
                <tr>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Late</th>
                    <th>Undertime</th>
                    <th>Total Hours Worked</th>
                    <th>Amount Per Day</th>
                </tr>
            </thead>
            <tr>
                <td id="dayspresent">{{collect($attendance)->where('totalworkinghours','>',0)->count()}}</td>
                <td id="daysabsent">{{collect($attendance)->where('totalworkinghours',0)->count()}}</td>
                <td>{{collect($attendance)->where('totalworkinghours','>',0)->sum('latehours')*60}} mins</td>
                <td>{{collect($attendance)->where('totalworkinghours','>',0)->sum('undertimehours')*60}} mins</td>
                <td>{{collect($attendance)->where('totalworkinghours','>',0)->sum('totalworkinghours')}}</td>
                <th class="text-right">@if($basicsalaryinfo->amount > 0 && count($attendance) > 0){{number_format(($basicsalaryinfo->amount/2)/count($attendance),2)}}@endif</th>
            </tr>
        </table>
    </div>
    <div class="col-md-12">
      <table class="table">
        <thead>
          <tr>
            <th style="width: 65% !important;">Particulars</th>
            <th style="width: 35% !important;">Computation</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Basic Salary</td>
            <td class="text-right" id="basicsalary" data-salarytype="{{$basicsalaryinfo->salarytype}}">
              @if($basicsalaryinfo->amount > 0 )
                @if(count($attendance) > 0)
                {{number_format((($basicsalaryinfo->amount/2)/count($attendance)) * collect($attendance)->where('totalworkinghours','>',0)->count(),2)}}
                @else
                {{number_format(($basicsalaryinfo->amount/2),2)}}
                @endif
              @endif
            </td>
          </tr>
          <tr>
            <td style="width: 65% !important;">
              @if(count($standardallowances)>0)
              <div class="card card-primary collapsed-card mb-1">
                <div class="card-header p-1">
                  <h6 class="card-title" style="font-size: 15px;">Standard Allowances</h6>
  
                  <div class="card-tools pr-1">
                    <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse"><i class="fas fa-plus"></i>
                    </button>
                  </div>
                  <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0 pr-1 pl-1">
                  <table style="width: 100% !important;">
                    @foreach($standardallowances as $eachstandard)
                    <tr>
                      <td colspan="3"><span class=" text-bold">-</span><small>{{$eachstandard->description}}</small></td>
                      <td style="width: 40%;" class="pr-3"><small class=" text-bold float-right">{{$eachstandard->amount}}</small></td>
                    </tr>
                    <tr style=" font-size: 11.5px;">
                      <td style="width: 20% !important;">
                        <div class="icheck-primary d-inline mr-2 standardallowance" data-allowancetype="0" data-allowanceid="{{$eachstandard->id}}" data-totalamount="{{$eachstandard->amount}}" data-amount="{{$eachstandard->amount}}" data-description="{{$eachstandard->description}}">
                          <input type="radio" id="standardallowance{{$eachstandard->id}}1" name="standardallowance{{$eachstandard->id}}" style="height:35px; width:35px; vertical-align: middle;">
                          <label for="standardallowance{{$eachstandard->id}}1">Full 
                          </label>
                        </div>
                      </td>
                      <td style="width: 20% !important;">
                        <div class="icheck-primary d-inline mr-2 standardallowance" data-deducttype="1" data-allowanceid="{{$eachstandard->id}}" data-totalamount="{{$eachstandard->amount}}" data-amount="{{number_format($eachstandard->amount/2,2)}}" data-description="{{$eachstandard->description}}">
                          <input type="radio" id="standardallowance{{$eachstandard->id}}2" name="standardallowance{{$eachstandard->id}}" style="height:35px; width:35px; vertical-align: middle;">
                          <label for="standardallowance{{$eachstandard->id}}2">Half
                          </label>
                        </div>
                      </td>
                      <td colspan="2"  style="width: 60% !important;">
                        {{-- <div class="icheck-primary d-inline standardallowance" data-allowancetype="3" data-allowanceid="{{$eachstandard->id}}">
                          <input type="radio" id="standardallowance{{$eachstandard->id}}3" name="standardallowance{{$eachstandard->id}}" style="height:15px !important; width:15px !important; vertical-align: middle !important;">
                          <label for="standardallowance{{$eachstandard->id}}3"><input type="number" style="width: 90px;" placeholder="Custom" class="standardallowancecustom"/>
                          </label>
                        </div> --}}
                      </td>
                    </tr>
                    @endforeach
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              @endif
              @if(count($otherallowances)>0)
              <div class="card card-primary collapsed-card mb-1">
                <div class="card-header p-1">
                  <h6 class="card-title" style="font-size: 15px;">Other Allowances</h6>
  
                  <div class="card-tools pr-1">
                    <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse"><i class="fas fa-plus"></i>
                    </button>
                  </div>
                  <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0 pr-1 pl-1">
                  <table style="width: 100% !important;">
                    @foreach($otherallowances as $eachotherallowance)
                    <tr>
                      <td colspan="3"><span class=" text-bold">-</span><small>{{$eachotherallowance->description}}</small></td>
                      <td style="width: 40%;" class="pr-3"><small class=" text-bold float-right">{{$eachotherallowance->amounttopay}}</small></td>
                    </tr>
                    <tr style=" font-size: 11.5px;">
                      <td style="width: 20% !important;">
                        <div class="icheck-primary d-inline mr-2 otherallowance" data-allowancetype="0" data-allowanceid="{{$eachotherallowance->id}}" data-totalamount="{{$eachotherallowance->amount}}"  data-amount="{{$eachotherallowance->amounttopay}}" data-description="{{$eachotherallowance->description}}">
                          <input type="radio" id="otherallowance{{$eachotherallowance->id}}1" name="otherallowance{{$eachotherallowance->id}}" style="height:35px; width:35px; vertical-align: middle;">
                          <label for="otherallowance{{$eachotherallowance->id}}1">Full 
                          </label>
                        </div>
                      </td>
                      <td style="width: 20% !important;">
                        <div class="icheck-primary d-inline mr-2 otherallowance" data-deducttype="1" data-allowanceid="{{$eachotherallowance->id}}" data-totalamount="{{$eachotherallowance->amount}}" data-amount="{{number_format($eachotherallowance->amounttopay/2,2)}}" data-description="{{$eachotherallowance->description}}">
                          <input type="radio" id="otherallowance{{$eachotherallowance->id}}2" name="otherallowance{{$eachotherallowance->id}}" style="height:35px; width:35px; vertical-align: middle;">
                          <label for="otherallowance{{$eachotherallowance->id}}2">Half
                          </label>
                        </div>
                      </td>
                      <td colspan="2"  style="width: 60% !important;">
                        {{-- <div class="icheck-primary d-inline standardallowance" data-allowancetype="3" data-allowanceid="{{$eachstandard->id}}">
                          <input type="radio" id="standardallowance{{$eachstandard->id}}3" name="standardallowance{{$eachstandard->id}}" style="height:15px !important; width:15px !important; vertical-align: middle !important;">
                          <label for="standardallowance{{$eachstandard->id}}3"><input type="number" style="width: 90px;" placeholder="Custom" class="standardallowancecustom"/>
                          </label>
                        </div> --}}
                      </td>
                    </tr>
                    @endforeach
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              @endif
              @if(count($standarddeductions)>0)
              <div class="card card-primary collapsed-card mb-1">
                <div class="card-header p-1">
                  <h6 class="card-title" style="font-size: 15px;">Standard Deductions</h6>
  
                  <div class="card-tools pr-1">
                    <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse"><i class="fas fa-plus"></i>
                    </button>
                  </div>
                  <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0 pr-1 pl-1">
                  <table style="width: 100% !important;">
                    @foreach($standarddeductions as $eachstandard)
                    <tr>
                      <td colspan="3"><span class=" text-bold">-</span><small>{{$eachstandard->description}}</small></td>
                      <td style="width: 40%;" class="pr-3"><small class=" text-bold float-right">{{$eachstandard->amount}}</small></td>
                    </tr>
                    <tr style=" font-size: 11.5px;">
                      <td style="width: 20% !important;">
                        <div class="icheck-primary d-inline mr-2 standarddeduction" data-deducttype="0" data-deductionid="{{$eachstandard->id}}" data-totalamount="{{$eachstandard->amount}}" data-amount="{{$eachstandard->amount}}" data-description="{{$eachstandard->description}}">
                          <input type="radio" id="standarddeduction{{$eachstandard->id}}1" name="standarddeduction{{$eachstandard->id}}" style="height:35px; width:35px; vertical-align: middle;">
                          <label for="standarddeduction{{$eachstandard->id}}1">Full 
                          </label>
                        </div>
                      </td>
                      <td style="width: 20% !important;">
                        <div class="icheck-primary d-inline mr-2 standarddeduction" data-deducttype="1" data-deductionid="{{$eachstandard->id}}" data-totalamount="{{$eachstandard->amount}}" data-amount="{{number_format($eachstandard->amount/2,2)}}" data-description="{{$eachstandard->description}}">
                          <input type="radio" id="standarddeduction{{$eachstandard->id}}2" name="standarddeduction{{$eachstandard->id}}" style="height:35px; width:35px; vertical-align: middle;">
                          <label for="standarddeduction{{$eachstandard->id}}2">Half
                          </label>
                        </div>
                      </td>
                      <td colspan="2"  style="width: 60% !important;">
                        <div class="icheck-primary d-inline standarddeduction" data-deducttype="2" data-deductionid="{{$eachstandard->id}}">
                          <input type="radio" id="standarddeduction{{$eachstandard->id}}3" name="standarddeduction{{$eachstandard->id}}" style="height:15px !important; width:15px !important; vertical-align: middle !important;">
                          <label for="standarddeduction{{$eachstandard->id}}3"><input type="number" style="width: 90px;" placeholder="Custom" class="standarddedductioncustom"/>
                          </label>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              @endif
              @if(count($otherdeductions)>0)
              <div class="card card-primary collapsed-card mb-1">
                <div class="card-header p-1">
                  <h6 class="card-title" style="font-size: 15px;">Other Deductions</h6>
  
                  <div class="card-tools pr-1">
                    <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse"><i class="fas fa-plus"></i>
                    </button>
                  </div>
                  <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0 pr-1 pl-1">
                  <table style="width: 100% !important;">
                    @foreach($otherdeductions as $eachotherdeduction)
                    <tr>
                      <td colspan="3"><span class=" text-bold">-</span><small>{{$eachotherdeduction->description}}</small></td>
                      <td style="width: 40%;" class="pr-3"><small class=" text-bold float-right">{{$eachotherdeduction->amounttopay}}</small></td>
                    </tr>
                    <tr style=" font-size: 11.5px;">
                      <td style="width: 20% !important;">
                        <div class="icheck-primary d-inline mr-2 otherdeduction" data-deductiontype="0" data-deductionid="{{$eachotherdeduction->id}}" data-totalamount="{{$eachotherdeduction->amount}}" data-amount="{{$eachotherdeduction->amounttopay}}" data-description="{{$eachotherdeduction->description}}">
                          <input type="radio" id="otherdeduction{{$eachotherdeduction->id}}1" name="otherdeduction{{$eachotherdeduction->id}}" style="height:35px; width:35px; vertical-align: middle;">
                          <label for="otherdeduction{{$eachotherdeduction->id}}1">Full 
                          </label>
                        </div>
                      </td>
                      <td style="width: 20% !important;">
                        <div class="icheck-primary d-inline mr-2 otherdeduction" data-deducttype="1" data-deductionid="{{$eachotherdeduction->id}}" data-totalamount="{{$eachotherdeduction->amount}}" data-amount="{{number_format($eachotherdeduction->amounttopay/2,2)}}" data-description="{{$eachotherdeduction->description}}">
                          <input type="radio" id="otherdeduction{{$eachotherdeduction->id}}2" name="otherdeduction{{$eachotherdeduction->id}}" style="height:35px; width:35px; vertical-align: middle;">
                          <label for="otherdeduction{{$eachotherdeduction->id}}2">Half
                          </label>
                        </div>
                      </td>
                      <td colspan="2"  style="width: 60% !important;">
                        <div class="icheck-primary d-inline otherdeduction" data-deductiontype="2" data-deductionid="{{$eachotherdeduction->id}}" data-totalamount="{{$eachotherdeduction->amount}}" data-description="{{$eachotherdeduction->description}}">
                          <input type="radio" id="otherdeduction{{$eachotherdeduction->id}}3" name="otherdeduction{{$eachotherdeduction->id}}" style="height:15px !important; width:15px !important; vertical-align: middle !important;">
                          <label for="otherdeduction{{$eachotherdeduction->id}}3"><input type="number" style="width: 90px;" placeholder="Custom" class="otherdeductioncustom"/>
                          </label>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              @endif
              {{-- <div id="accordion">
              @if(count($standarddeductions)>0)
              <h6>
                <a class="d-block w-100" data-toggle="collapse" href="#collapsestandarddeductions">
                  Collapsible Group Danger
                </a>
              </h6>
              <div id="collapsestandarddeductions" class="collapse" data-parent="#accordion">
                  <table style="width: 100% !important;">
                    @foreach($standarddeductions as $eachstandard)
                    <tr>
                      <td colspan="3"><span class=" text-bold">-</span><small>{{$eachstandard->description}}</small></td>
                      <td style="width: 40%;" class="pr-3"><small class=" text-bold float-right">{{$eachstandard->amount}}</small></td>
                    </tr>
                    <tr style=" font-size: 11.5px;">
                      <td style="width: 20% !important;">
                        <div class="icheck-primary d-inline mr-2 standarddeduction" data-deducttype="0" data-deductionid="{{$eachstandard->id}}" data-amount="{{$eachstandard->amount}}">
                          <input type="radio" id="standarddeduction{{$eachstandard->id}}1" name="standarddeduction{{$eachstandard->id}}" style="height:35px; width:35px; vertical-align: middle;">
                          <label for="standarddeduction{{$eachstandard->id}}1">Full 
                          </label>
                        </div>
                      </td>
                      <td style="width: 20% !important;">
                        <div class="icheck-primary d-inline mr-2 standarddeduction" data-deducttype="1" data-deductionid="{{$eachstandard->id}}" data-amount="{{number_format($eachstandard->amount/2,2)}}">
                          <input type="radio" id="standarddeduction{{$eachstandard->id}}2" name="standarddeduction{{$eachstandard->id}}" style="height:35px; width:35px; vertical-align: middle;">
                          <label for="standarddeduction{{$eachstandard->id}}2">Half
                          </label>
                        </div>
                      </td>
                      <td colspan="2"  style="width: 60% !important;">
                        <div class="icheck-primary d-inline standarddeduction" data-deducttype="3" data-deductionid="{{$eachstandard->id}}">
                          <input type="radio" id="standarddeduction{{$eachstandard->id}}3" name="standarddeduction{{$eachstandard->id}}" style="height:15px !important; width:15px !important; vertical-align: middle !important;">
                          <label for="standarddeduction{{$eachstandard->id}}3"><input type="number" style="width: 90px;" placeholder="Custom" class="standarddedductioncustom"/>
                          </label>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </table>
              </div>
              @endif
            </div> --}}
                
                  {{-- <div class="info-box shadow" style=" @if(count($eachstandard->balances) > 0)border: 1px solid red;@endif cursor: pointer;" data-toggle="modal" data-target="#modal-standarddeduction{{$eachstandard->id}}">

                    <div class="info-box-content p-0">
                      <span class="info-box-text"><span class=" text-bold">-</span><small>{{$eachstandard->description}}</small></span>
                      <span class="info-box-number"><small class=" text-bold">{{$eachstandard->amount}}</small></span>
                    </div>
                  </div> --}}
            </td>
            <td id="container-computation" style="width: 35%;">
                <div id="container-added-allowance"></div>
                <div id="container-added-deduction"></div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="col-md-12" id="div-container-particulars"></div>
</div>
<script>
  @if($basicsalaryinfo->amount > 0 )
  $('#netsalary').text('{{$basicsalaryamount}}')
  @endif
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
    if($('#container-added-allowance').find('#stanallowance'+allowanceid).length == 0)
    {
      $('#container-added-allowance').append('<p id="stanallowance'+allowanceid+'" class="text-right m-0 amountcontainer">'+amount+'</p>')
    }else{
      $('#stanallowance'+allowanceid).text(amount)
    }
    $('#stanallowance'+allowanceid).addClass('text-bold')
    $('#stanallowance'+allowanceid).addClass('text-success')
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
    if($('#container-added-deduction').find('#otherdeduction'+deductionid).length == 0)
    {
      $('#container-added-deduction').append('<p id="otherdeduction'+deductionid+'" class="text-right m-0 amountcontainer">'+amount+'</p>')
    }else{
      $('#otherdeduction'+deductionid).text(amount)
    }
    $('#otherdeduction'+deductionid).addClass('text-bold')
    $('#otherdeduction'+deductionid).addClass('text-danger')
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
</script>
@endif